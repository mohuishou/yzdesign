<?php
Wind::import('SRV:forum.srv.PwPost');
/**
 * 
 *
 */
class PwYunQianPostService {
	
	public function dopost($fid=0){
		$special = '0';
		if(!$fid) return;
		Wind::import('SRV:forum.srv.post.PwTopicPost');
		$postAction = new PwTopicPost($fid);
		$special && $postAction->setSpecial($special);
		$pwPost = new PwPost($postAction);
		
		$title=$this->_buildTitle(); $content=$this->_buildContent();  $topictype='0'; $subtopictype='0'; $reply_notice='0'; $hide='0';
		
		$postDm = $pwPost->getDm();
		$postDm->setTitle($title)
			->setContent($content)
			->setHide($hide)
			->setReplyNotice($reply_notice);
		//set topic type
		$topictype_id = $subtopictype ? $subtopictype : $topictype;
		$topictype_id && $postDm->setTopictype($topictype_id);
		
		if (($result = $pwPost->execute($postDm)) !== true) {
			$data = $result->getData();
			$data && $this->addMessage($data, 'data');
			//$this->showError($result->getError());
		}
		$tid = $pwPost->getNewId();
		return $tid;
	}
	
	public function doreply($content='', $fid=0, $tid=0, $uid=0, $username=''){
		if(!$fid || !$tid) return ;
		Wind::import('SRV:forum.srv.post.PwReplyPost');
		$postAction = new PwReplyPost($tid);
		$pwPost = new PwPost($postAction);
		$info = $pwPost->getInfo();
		$title == 'Re:' . $info['subject'] && $title = '';
		$rpid='0'; $hide='0';
		$content = $this->_buildReply($content, $uid, $username);
		$postDm = $pwPost->getDm();
		$postDm->setTitle($title)
			->setContent($content)
			->setHide($hide)
			->setReplyPid($rpid);
		
		if (($result = $pwPost->execute($postDm)) !== true) {
			$data = $result->getData();
			$data && $this->addMessage($data, 'data');
			//$this->showError($result->getError());
		}
		$pid = $pwPost->getNewId();
		return $pid;
	}
	
	protected function _buildTitle() {
		$search = array('{tdtime}', '{sitename}');
		$replace = array(Pw::time2str(Pw::getTime(), 'Y-m-d'), Wekit::C('site', 'info.name'));
		$content = str_replace($search, $replace, Wekit::C('yunqian', 'post_title'));
		return $content;
	}
	
	protected function _buildContent($uid=0, $username='') {
		$search = array('{tdtime}', '{sitename}', '{uid}', '{username}', '{yunqian}');
		$replace = array(Pw::time2str(Pw::getTime(), 'Y-m-d'), Wekit::C('site', 'info.name'), $uid, $username);
		$content = str_replace($search, $replace, Wekit::C('yunqian', 'post_content'));
		return $content;
	}
	
	protected function _buildReply($content, $uid=0, $username='') {
		$search = array('{tdtime}', '{sitename}', '{uid}', '{username}', '{yunqian}', '{content}');
		$replace = array(Pw::time2str(Pw::getTime(), 'Y-m-d'), Wekit::C('site', 'info.name'), $uid, $username, WindUrlHelper::createUrl('app/yunqian/index/run'), $content);
		$content = str_replace($search, $replace, Wekit::C('yunqian', 'post_reply'));
		return $content;
	}
}

?>