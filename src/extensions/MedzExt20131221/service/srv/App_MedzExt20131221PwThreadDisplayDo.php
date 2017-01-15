<?php
defined('WEKIT_VERSION') or exit(403);
Wind::import('SRV:forum.srv.threadDisplay.do.PwThreadDisplayDoBase');
/**
 * 帖子内容展示
 *
 * @author Sivay Du <lovevipdsw@vip.qq.com>
 * @copyright http://www.medz.cn
 * @license http://www.medz.cn
 */
class App_MedzExt20131221PwThreadDisplayDo extends PwThreadDisplayDoBase {
	/*
	 * @see PwThreadDisplayDoBase
	*/
	private $tid;
	private $fid;
	private $user;
	private $thread;
	private $s;
	
	public function __construct( $s = null, $tid = null, $fid = null ) {
		$this->tid = $tid;
		$this->fid = $fid; 
		if( !$tid ) { $this->tid = $s->tid; }
		if( !$fid ) { $this->fid = $s->fid; }
		$this->user = $s->user;
		$this->thread = $s->thread;
		$this->s = $s;
	}
	
	/**
	 * 在这里输出插件内容 (位置：帖子内容下方)
	 * 这里是输出帖子聚合HOOK嵌入点
	 */
	public function createHtmlAfterContent( $read = null ) {
		if( $read['lou'] == 0 ) {			
			$data = $this->_getJuheDb( $read['tid'], 1 );
			$data3 = $this->_getJuheDb( $read['tid'], 3 );
			$data2 = $this->_getJuheDb( $read['tid'], 2 );
			PwHook::template( 'MedzExt20131221createHtmlAfterContentHtml1', 'EXT:MedzExt20131221.template.hook.createHtmlAfterContent', true, $data, $data2, $data3 );
			$data1 = $this->_getAggregationDb( $read['tid'] );
			if( $data1 != false ) {
				PwHook::template( 'MedzExt20131221createHtmlContentBottomHtml', 'EXT:MedzExt20131221.template.hook.createHtmlContentBottom', true, $data1 );
			}
		}
	}
	
	/**
	 * 在这里输出插件内容 (位置：帖子操作按钮)
	 */
	public function createHtmlForThreadButton( $read = null ) {
		$tid = $read['tid'];
		if( !$tid ) { $tid = $this->tid; }
		if( $this->_isAdmin() && $read['lou'] != 0 ) {
			$pid = $read['pid'];
			if( $this->_getRead()->isPid( $tid, $pid ) ) { $data['is'] = 1; }
			$data['tid'] = $tid;
			$data['pid'] = $pid;
			PwHook::template( 'MedzExt20131221BottomHtml', 'EXT:MedzExt20131221.template.hook.bottom', true, $data );
		}
		if( $read['lou'] == 0 && $this->_isGroups() ) {
			PwHook::template( 'MedzExt20131221BottomjuheHtml', 'EXT:MedzExt20131221.template.hook.bottom', true, $tid );
		}
	}
	
	public function runJs(){
		echo '<script src="' . Wekit::url()->extres . '/MedzExt20131221/js/dialog.js" design="Sivay Du" ext="MedzExt20131221" copyright="http://www.medz.cn"></script>';
	}
	
	private function _isAdmin() {
		$info = $this->thread->info;
		$uid = $this->user->uid;
		if( $uid == $info['created_userid'] ) {
			return true;
		}
		return $this->_isGroups();
	}
	
	private function _isGroups() {
		$gid = wekit::getLoginUser()->gid;
		$group = $this->set('group');
		if( is_array( $group ) ) {
			foreach( $group as $gid2 ) {
				if( $gid2 == $gid ) {
					return true;
				}
			}
		}
		return false;
	}
	
	private function set($name, $field=false, $write=false, $data=array()) {
		$script = Wekit::load('EXT:MedzExt20131221.service.helps.App_Medz_Helps');
		return $script->medz($name, $field, $write, $data);
	}
	
	private function _getAggregationDb( $tid = '' ) {
		if( !$tid ) { $tid = $this->tid; }
		$data = $this->_getRead()->get( $tid );
		$data = $data['pid'];
		$readDb = $this->_getReadDb();
		if( is_array( $readDb ) && !empty( $readDb ) && is_array( $data ) && !empty( $data ) ) {
			foreach( $readDb as $v ) {
				foreach( $data as $pid ) {
					if( $v['pid'] == $pid && !empty( $pid ) && !empty( $v['pid'] ) ) {
						$tmp[$pid] = $v;
					}
				}
			}
			return $tmp;
		}
		return false;
	}
	
	private function _getReadDb() {
		$db = $this->s->readdb;
		return $db;
	}
	
	private function _getJuheDb( $tid, $type ) {
		if( !$tid ) { $tid = $this->tid; }
		$data = $this->_getJuhe()->get( $tid );
		$data = $data['type'];
		$array = array();
		if( is_array( $data ) && !empty( $data ) ) {
			foreach( $data as $tid => $type2 ) {
				if( $type2 == $type ) {
					$PwThred = wekit::load( 'SRV:forum.PwThread' );
					$array[$tid] = $PwThred->getThread( $tid, 3 );
					if( $type == 1 || $type == 2 || $type2 == 1 || $type2 == 2 ) {
						$array[$tid]['images'] = $this->_getAttach( $tid );
					}
				}
			}
			return $array;
		}
		return false;
	}
	
	private function _getAttach( $tid ) {
		$attach = wekit::load('SRV:attach.PwThreadAttach');
		$data = $attach->fetchAttachByTid( array( $tid ) );
		if( is_array( $data ) && !empty( $data ) ) {
			foreach( $data as $v ) {
				if( $v['type'] == 'img' ) {
					$array[] = $v;
				}
			}
		}
		return $array;
	}
	
	private function _getJuhe() {
		return Wekit::loadDao('EXT:MedzExt20131221.service.App_Medz_Ext20131221_Juhe');
	}
	
	private function _getRead() {
		return Wekit::loadDao('EXT:MedzExt20131221.service.App_Medz_Ext20131221_Read');
	}
}

