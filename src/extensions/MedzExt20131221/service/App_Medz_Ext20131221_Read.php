<?php
defined('WEKIT_VERSION') or exit(403);
Wind::import('EXT:MedzExt20131221.service.dm.App_Medz_Ext20131221_ReadDm');
/**
 * App_Medz_Ext20131221_Read - 数据服务接口
 *
 * @author Sivay Du <lovevipdsw@vip.qq.com>
 * @copyright http://www.medz.cn
 * @license http://www.medz.cn
 */
class App_Medz_Ext20131221_Read {
	
	/**
	 * add record
	 *
	 * @param App_Medz_Ext20131221_ReadDm $dm
	 * @return multitype:|Ambigous <boolean, number, string, rowCount>
	 */
	public function add(App_Medz_Ext20131221_ReadDm $dm) {
		if (true !== ($r = $dm->beforeAdd())) return $r;
		return $this->_loadDao()->add($dm->getData());
	}
	
	/**
	 * update record
	 *
	 * @param App_Medz_Ext20131221_ReadDm $dm
	 * @return multitype:|Ambigous <boolean, number, rowCount>
	 */
	public function update(App_Medz_Ext20131221_ReadDm $dm) {
		if (true !== ($r = $dm->beforeUpdate())) return $r;
		return $this->_loadDao()->update($dm->getId(), $dm->getData());
	}
	
	/**
	 * get a record
	 *
	 * @param unknown_type $id
	 * @return Ambigous <multitype:, multitype:unknown , mixed>
	 */
	public function get($tid) {
		$data = $this->_loadDao()->get($tid);
		if( is_array( $data ) && !empty( $data ) ) {
			$array['tid'] = $data['tid'];
			$array['pid'] = Pw::jsonDecode( $data['json'] );
			if( !empty( $array['pid'] ) && is_array( $array['pid'] ) ) {
				foreach( $array['pid'] as $k => $v ) {
					if( !empty( $v ) || !$v ) {
						$array['pid'][$k] = $v;
					}
				}
			}
			return $array;
		} else {
			return false;
		}
	}
	
	public function isPid( $tid, $pid ) {
		return $this->_loadDao()->isPid( $tid, $pid );
	}
	
	public function addPid( $tid, $pid = '' ) {
		if( !empty( $tid ) && $this->get( $tid ) != false ) {
			if( $this->isPid( $tid, $pid ) == false && !empty( $pid ) ) {
				$data = $this->get( $tid );
				$array1 = $data['pid'];
				if( !is_array( $array1 ) ) {
					$array1 = array();
				}
				if( !empty( $array1 ) ) {
					$data = implode( '!@!',$array1 ) . '!@!' . $pid;
					$data = explode( '!@!', $data );
				} else {
					$data = array( $pid );
				}
				return Pw::jsonEncode( $data );
			} else {
				$data = $this->get( $tid );
				return Pw::jsonEncode( $data['pid'] );
			}
		}
		return Pw::jsonEncode( array( $pid ) );
	}
	
	public function daletePid( $tid, $pid ) {
		$data = $this->get( $tid );
		if( is_array( $data['pid'] ) && !empty( $data['pid'] ) ) {
			$i = 0;
			foreach( $data['pid'] as $v ) {
				if( $v != $pid ) {
					$tmp[$i] = $v;
					$i++;
				}
			}
		}
		if( !$tmp ) { return null; }
		return Pw::jsonEncode( $tmp );
	}
	
	/**
	 * delete a record
	 *
	 * @param unknown_type $id
	 * @return Ambigous <number, boolean, rowCount>
	 */
	public function delete($tid) {
		return $this->_loadDao()->delete($tid);
	}
	
	/**
	 * @return App_Medz_Ext20131221_ReadDao
	 */
	private function _loadDao() {
		return Wekit::loadDao('EXT:MedzExt20131221.service.dao.App_Medz_Ext20131221_ReadDao');
	}
}

?>