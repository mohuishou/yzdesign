<?php
defined('WEKIT_VERSION') || exit('Forbidden');

Wind::import('SRV:user.vo.PwUserSo');
Wind::import('SRV:forum.srv.threadList.PwSpaceThread');
/**
 * 会员列表
 * Class AllUserController
 * @author mohuishou <1@lailin.xyz>
 */

class AllUserController extends PwBaseController {
    private $upgradeGroups = array('name' => '普通组', 'gid' => '0');

    private $pageNumber = 10;
	public function run() {

        list( $sGroup, $page) = $this->getInput(array('gid', 'page'));
        $vo = new PwUserSo();
        !$sGroup || $vo->setGid($sGroup);
        $page = intval($page) == 0 ? 1 : abs(intval($page));
        /* @var $searchDs PwUserSearch */
        $searchDs = Wekit::load('SRV:user.PwUserSearch');
        $count = $searchDs->countSearchUser($vo);

        $result = array();
        if ($count > 0) {
            $totalPage = ceil($count/$this->pageNumber);
            $page > $totalPage && $page = $totalPage;
            /* @var $searchDs PwUserSearch */
            $searchDs = Wekit::load('user.PwUserSearch');
            list($start, $limit) = Pw::page2limit($page, $this->pageNumber);
            $result = $searchDs->searchUser($vo, $limit, $start);
            if ($result) {
                /* @var $userDs PwUser */
                $userDs = Wekit::load('user.PwUser');
                $list = $userDs->fetchUserByUid(array_keys($result), PwUser::FETCH_DATA);
                $result = WindUtility::mergeArray($result, $list);
            }
        }

        $users=$result;
        foreach ($users as $k => $user){
            $dataSource = new PwSpaceThread($user['uid']);
            $threads=$dataSource->getData(3,0);

//            地理位置获取
            $users[$k]['address']="中国";
            if($user['location_text']){
                $tmp=explode(" ",$user['location_text']);
                $users[$k]['address']=$tmp[0];
            }

//            最新发布的帖子
            $user_threads=[];
            foreach ($threads as $key => $thread){
                $tmp['tid']=$thread['tid'];
                $tmp['fid']=$thread['fid'];
                $tmp['name']=$thread['subject'];
                $tmp['thumb']=$this->getThumb($tmp['tid']);
                $user_threads[]=$tmp;
            }
            $users[$k]['threads']=$user_threads;
        }

        //获取所有特殊用户组
        $groupDs = Wekit::load('usergroup.PwUserGroups');
        $groups = $groupDs->getGroupsByType("special");

        $data = $vo->getData();
        $this->setOutput($data, 'args');
        $this->setOutput($page, 'page');
        $this->setOutput($this->pageNumber, 'perpage');
        $this->setOutput($count, 'count');
        $this->setOutput($users, 'users');
        $this->setOutput($sGroup,'gid');
        $this->setOutput($groups, 'groups');
		$this->setTemplate("all_user");


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

}