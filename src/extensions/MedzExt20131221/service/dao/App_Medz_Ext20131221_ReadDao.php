<?php
defined('WEKIT_VERSION') or exit(403);
/**
 * App_Medz_Ext20131221_ReadDao - dao
 *
 * @author Sivay Du <lovevipdsw@vip.qq.com>
 * @copyright http://www.medz.cn
 * @license http://www.medz.cn
 */
class App_Medz_Ext20131221_ReadDao extends PwBaseDao {
	
	/**
	 * table name
	 */
	protected $_table = 'medz_ext20131221_read';
	/**
	 * primary key
	 */
	protected $_pk = 'tid';
	/**
	 * table fields
	 */
	protected $_dataStruct = array( 'tid' , 'json' );
	
	public function add($fields) {
		return $this->_add($fields, true);
	}
	
	public function update($tid, $fields) {
		return $this->_update($tid, $fields);
	}
	
	public function delete($tid) {
		return $this->_delete($tid);
	}
	
	public function get($tid) {
		return $this->_get($tid);
	}
	
	public function isPid( $tid, $pid ) {
		$data = $this->get( $tid );
		if( is_array( $data ) && !empty( $data ) ) {
			$data = Pw::jsonDecode( $data['json'] );
			if( is_array( $data ) && !empty( $data ) ) {
				foreach( $data as $v ) {
					if( $v == $pid ) {
						return true;
					}
				}
			}
		}
		return false;
	}
}

?>