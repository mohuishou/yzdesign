<?php
defined('WEKIT_VERSION') || exit('Forbidden');

Wind::import('SRV:forum.bo.PwForumBo');
Wind::import('SRV:forum.srv.PwThreadList');

/**
 * 帖子列表页
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @license http://www.phpwind.com
 * @version $Id: ThreadController.php 23994 2013-01-18 03:51:46Z long.shi $
 * @package forum
 */

class ThreadController extends PwBaseController {
	
	protected $topictypes;

	/**
	 * 帖子列表页
	 */
	public function run() {
		$tab = $this->getInput('tab');
		$fid = intval($this->getInput('fid'));
		$type = intval($this->getInput('type','get')); //主题分类ID
		$page = $this->getInput('page', 'get');
		$orderby = $this->getInput('orderby', 'get');
		
		$pwforum = new PwForumBo($fid, true);
		if (!$pwforum->isForum()) {
			$this->showError('BBS:forum.exists.not');
		}
		if ($pwforum->allowVisit($this->loginUser) !== true) {
			$this->showError(array('BBS:forum.permissions.visit.allow', array('{grouptitle}' => $this->loginUser->getGroupInfo('name'))));
		}
		if ($pwforum->forumset['jumpurl']) {
			$this->forwardRedirect($pwforum->forumset['jumpurl']);
		}
		if ($pwforum->foruminfo['password']) {
			if (!$this->loginUser->isExists()) {
				$this->forwardAction('u/login/run', array('backurl' => WindUrlHelper::createUrl('bbs/cate/run', array('fid' => $fid))));
			} elseif (Pw::getPwdCode($pwforum->foruminfo['password']) != Pw::getCookie('fp_' . $fid)) {
				$this->forwardAction('bbs/forum/password', array('fid' => $fid));
			}
		}
		$isBM = $pwforum->isBM($this->loginUser->username);
		if ($operateThread = $this->loginUser->getPermission('operate_thread', $isBM, array())) {
			$operateThread = Pw::subArray($operateThread, array('topped', 'digest', 'highlight', 'up', 'copy', 'type', 'move', /*'unite',*/ 'lock', 'down', 'delete', 'ban'));
		}
		$this->_initTopictypes($fid, $type);

		$threadList = new PwThreadList();
		$this->runHook('c_thread_run', $threadList);

		$threadList->setPage($page)
			->setPerpage($pwforum->forumset['threadperpage'] ? $pwforum->forumset['threadperpage'] : Wekit::C('bbs', 'thread.perpage'))
			->setIconNew($pwforum->foruminfo['newtime']);
		
		$defaultOrderby = $pwforum->forumset['threadorderby'] ? 'postdate' : 'lastpost';
		!$orderby && $orderby = $defaultOrderby;

		if ($tab == 'digest') {
			Wind::import('SRV:forum.srv.threadList.PwDigestThread');
			$dataSource = new PwDigestThread($pwforum->fid, $type, $orderby);
		} elseif ($type) {
			Wind::import('SRV:forum.srv.threadList.PwSearchThread');
			$dataSource = new PwSearchThread($pwforum);
			$dataSource->setOrderby($orderby);
			$dataSource->setType($type, $this->_getSubTopictype($type));
		} elseif ($orderby == 'postdate') {
			Wind::import('SRV:forum.srv.threadList.PwNewForumThread');
			$dataSource = new PwNewForumThread($pwforum);
		} else {
			Wind::import('SRV:forum.srv.threadList.PwCommonThread');
			$dataSource = new PwCommonThread($pwforum);
		}

		//获取包含子版块的帖子列表
        $fids=[$fid];
        $fids=array_merge($fids,array_keys($pwforum->getSubForums()));
        $dataSource->setFids($fids);

		$orderby != $defaultOrderby && $dataSource->setUrlArg('orderby', $orderby);
		$threadList->execute($dataSource);

		$this->setOutput($threadList, 'threadList');
		$this->setOutput($this->threadDB($threadList->getList()), 'threaddb');
		$this->setOutput($fid, 'fid');
		$this->setOutput($type ? $type : null, 'type');
		$this->setOutput($tab, 'tab');
        $this->setOutput($pwforum, 'pwforum');
		$this->setOutput($pwforum->headguide(), 'headguide');
		$this->setOutput($threadList->icon, 'icon');
		$this->setOutput($threadList->uploadIcon, 'uploadIcon');
		$this->setOutput($operateThread, 'operateThread');



        //获取所有的目录列表
        $this->setOutput($this->getCate($pwforum), 'cateList');
		$this->setOutput($pwforum->forumset['numofthreadtitle'] ? $pwforum->forumset['numofthreadtitle'] : 26, 'numofthreadtitle');
		$this->setOutput((!$this->loginUser->uid && !$this->allowPost($pwforum)) ? ' J_qlogin_trigger' : '', 'postNeedLogin');

		$this->setOutput($threadList->page, 'page');
		$this->setOutput($threadList->perpage, 'perpage');
		$this->setOutput($threadList->total, 'count');
		$this->setOutput($threadList->maxPage, 'totalpage');
		$this->setOutput($defaultOrderby, 'defaultOrderby');
		$this->setOutput($orderby, 'orderby');
		$this->setOutput($threadList->getUrlArgs(), 'urlargs');
		$this->setOutput($this->_formatTopictype($type), 'topictypes');
		
		//版块风格
		if ($pwforum->foruminfo['style']) {
			$this->setTheme('forum', $pwforum->foruminfo['style']);
			//$this->addCompileDir($pwforum->foruminfo['style']);
		}
		
		//seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		if ($threadList->page <=1) {
			if ($type)
				$seoBo->setDefaultSeo($lang->getMessage('SEO:bbs.thread.run.type.title'), '', $lang->getMessage('SEO:bbs.thread.run.type.description'));
			else 
				$seoBo->setDefaultSeo($lang->getMessage('SEO:bbs.thread.run.title'), '', $lang->getMessage('SEO:bbs.thread.run.description'));
		}
		$seoBo->init('bbs', 'thread', $fid);
		$seoBo->set(array(
			'{forumname}' => $pwforum->foruminfo['name'],
			'{forumdescription}' => Pw::substrs($pwforum->foruminfo['descrip'], 100, 0, false),
			'{classification}' => $this->_getSubTopictypeName($type),
			'{page}' => $threadList->page
		));
		Wekit::setV('seo', $seoBo);
		Pw::setCookie('visit_referer', 'fid_' . $fid . '_page_' . $threadList->page, 300);
	}


    /**
     * 获取版块目录，包括所有的二级版块和三级版块
     * @author mohuishou<1@lailin.xyz>
     * @param PwForumBo $pwForumBo
     * @return array
     */
	private function getCate(PwForumBo $pwForumBo){
        $data=$pwForumBo->getParentFids();
        //三级目录
        $cate_data=[];
        if(count($data)==2){
            $pwForumBo2=new PwForumBo($data[0]);
            $cate_3=$pwForumBo2->getSubForums(1,false);
            $cate_data['cate_2']=$pwForumBo2->foruminfo;
            foreach ($cate_3 as $v){
                $tmp['fid']=$v['fid'];
                $tmp['name']=$v['name'];
                $tmp['threads']=$v['threads'];
                $tmp['active']=0;
                if($tmp['fid']==$pwForumBo->foruminfo['fid']){
                    $tmp['active']=1;
                }
                $cate_data[3][]=$tmp;
            }
            //用于判断是不是点击的二级目录，为1的时候显示全部
            $cate_data['all']['active']=0;
            $cate_data['all']['fid']=$pwForumBo->foruminfo['parentid'];

            //获取所有的二级版块，不需要知道帖子数目
            $pwForumBo1=new PwForumBo($data[1]);
            $cate_2=$pwForumBo1->getSubForums();
            foreach ($cate_2 as $v){
                $tmp2['fid']=$v['fid'];
                $tmp2['name']=$v['name'];
                $tmp2['active']=0;
                if($tmp2['fid']==$pwForumBo->foruminfo['parentid']){
                    $tmp2['active']=1;
                }
                $cate_data[2][]=$tmp2;
            }
        }elseif(count($data)==1){ //二级目录的时候
            $cate_data['cate_2']=$pwForumBo->foruminfo;
            //获取所有的二级版块，不需要知道帖子数目
            $pwForumBo1=new PwForumBo($data[0]);
            $cate_2=$pwForumBo1->getSubForums();
            foreach ($cate_2 as $v){
                $tmp2['fid']=$v['fid'];
                $tmp2['name']=$v['name'];
                $tmp2['active']=0;
                if($tmp2['fid']==$pwForumBo->foruminfo['fid']){
                    $tmp2['active']=1;
                }
                $cate_data[2][]=$tmp2;
            }

            $cate_3=$pwForumBo->getSubForums(1,true);
            foreach ($cate_3 as $v){
                $tmp['fid']=$v['fid'];
                $tmp['name']=$v['name'];
                $tmp['threads']=$v['threads'];
                $tmp['active']=0;
                $cate_data[3][]=$tmp;
            }
            $cate_data['all']['active']=1;
            $cate_data['all']['fid']=$pwForumBo->foruminfo['fid'];
        }
        return $cate_data;
    }

    /**
     * 去除重复数据
     * @author mohuishou<1@lailin.xyz>
     * @param $thread_db
     * @return array|boolean
     */
	private function threadDB($data){
        if(!isset($data)||!is_array($data)){
            return false;
        }
        $fid = intval($this->getInput('fid'));
        $thread_data=[];
        $tmp=[];

        foreach ($data as  $k => $v){
            $v['thumb']=$this->getThumb($v['tid']);
            if($v['topped']){
                if(!in_array($v['tid'],$tmp)){
                    $tmp[]=$v['tid'];
                    $thread_data[]=$v;
                }
            }else{
                if ($v['fid']!=$fid){
                    $pwforum = new PwForumBo($v['fid'],false);
                    $v['forum_name']=$pwforum->foruminfo['name'];
                }
                $thread_data[]=$v;
            }
        }
        return $thread_data;
    }

    /**
     * 获取缩略图路径
     * @author mohuishou<1@lailin.xyz>
     * @param $tid
     * @return bool|string
     */
    private function getThumb($tid){
        $pwThread=Wekit::load('attach.PwThreadAttach');
        $data=$pwThread->fetchAttachByTid([$tid]);
        foreach ($data as $v){
            if($v['ifthumb']){
                if($v['ifthumb']==2){
                    return Pw::getPath($v['path'],2);
                }else{
                    return Pw::getPath($v['path'],1);
                }

            }
        }

        return false;
    }


	private function _initTopictypes($fid, &$type) {
		$this->topictypes = $this->_getTopictypeService()->getTopicTypesByFid($fid);
		if (!isset($this->topictypes['all_types'][$type])) $type = 0;
	}

	private function _getSubTopictype($type) {
		if (isset($this->topictypes['sub_topic_types']) && isset($this->topictypes['sub_topic_types'][$type])) {
			return array_keys($this->topictypes['sub_topic_types'][$type]);
		}
		return array();
	}

	private function _getSubTopictypeName($type) {
		return isset($this->topictypes['all_types'][$type]) ? $this->topictypes['all_types'][$type]['name'] : '';
	}
	
	private function _formatTopictype($type) {
		$topictypes = $this->topictypes;
		if (isset($topictypes['all_types'][$type]) && $topictypes['all_types'][$type]['parentid']) {
			$topictypeService = Wekit::load('forum.srv.PwTopicTypeService');
			$topictypes = $topictypeService->sortTopictype($type, $topictypes);
		}
		return $topictypes;
	}
	
	private function _getTopictypeService(){
		return Wekit::load('forum.PwTopicType');
	}

	private function allowPost(PwForumBo $forum) {
		return $forum->foruminfo['allow_post'] ? $forum->allowPost($this->loginUser) : $this->loginUser->getPermission('allow_post');
	}
}