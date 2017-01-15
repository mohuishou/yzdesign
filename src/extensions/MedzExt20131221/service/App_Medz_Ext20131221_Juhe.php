<?php
defined('WEKIT_VERSION') or exit(403);
Wind::import('EXT:MedzExt20131221.service.dm.App_Medz_Ext20131221_JuheDm');
/**
 * App_Medz_Ext20131221_Juhe - 数据服务接口
 *
 * @author Sivay Du <lovevipdsw@vip.qq.com>
 * @copyright http://www.medz.cn
 * @license http://www.medz.cn
 */
class App_Medz_Ext20131221_Juhe {
	
	/**
	 * add record
	 *
	 * @param App_Medz_Ext20131221_JuheDm $dm
	 * @return multitype:|Ambigous <boolean, number, string, rowCount>
	 */
	public function add(App_Medz_Ext20131221_JuheDm $dm) {
		if (true !== ($r = $dm->beforeAdd())) return $r;
		return $this->_loadDao()->add($dm->getData());
	}
	
	/**
	 * update record
	 *
	 * @param App_Medz_Ext20131221_JuheDm $dm
	 * @return multitype:|Ambigous <boolean, number, rowCount>
	 */
	public function update(App_Medz_Ext20131221_JuheDm $dm) {
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
			$array['type'] = Pw::jsonDecode( $data['type'] );
			if( !empty( $array['type'] ) && is_array( $array['type'] ) ) {
				foreach( $array['type'] as $k => $v ) {
					if( !empty( $v ) || !$v ) {
						$array['type'][$k] = $v;
					}
				}
			}
			$array['tids'] = Pw::jsonDecode( $data['tids'] );
			if( !empty( $array['tids'] ) && is_array( $array['tids'] ) ) {
				foreach( $array['tids'] as $k => $v ) {
					if( !empty( $v ) || !$v ) {
						$array['tids'][$k] = $v;
					}
				}
			}
			return $array;
		} else {
			return false;
		}
	}
	
	public function isTid( $tid, $jutid ) {
		return $this->_loadDao()->isTid( $tid, $jutid );
	}
	
	public function addType( $tid, $jutid, $type ) {
		$data = $this->get( $tid );
		$t = $type;
		$type = $data['type'];
		$type[$jutid] = $t;
		return Pw::jsonEncode( $type );
	}
	
	public function addTid( $tid, $jutid ) {
		$data = $this->get( $tid );
		$data = $data['tids'];
		$data[md5(time())] = $jutid;
		$i = 0;
		foreach( $data as $v ) {
			$array[$i] = $v;
			$i++;
		}
		return Pw::jsonEncode( $array );
	}
	
	public function daleteTid( $tid, $jutid ) {
		$data = $this->get( $tid );
		if( is_array( $data['tids'] ) && !empty( $data['tids'] ) ) {
			$i = 0;
			foreach( $data['tids'] as $v ) {
				if( $v != $jutid ) {
					$tmp[$i] = $v;
					$i++;
				}
			}
		}
		if( !$tmp ) { return null; }
		return Pw::jsonEncode( $tmp );
	}
	
	public function daletetype( $tid, $tids ) {
		$data = $this->get( $tid );
		$type = $data['type'];
		$array = array();
		if( is_array( $type ) && !empty( $type ) ) {
			foreach( $type as $tid2 => $t ) {
				if( $tids != $tid2 ) {
					$array[$tid2] = $t;
				}
			}
		}
		if( !$array ) { return null; }
		return Pw::jsonEncode( $array );
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
		return Wekit::loadDao('EXT:MedzExt20131221.service.dao.App_Medz_Ext20131221_JuheDao');
	}
}

?>