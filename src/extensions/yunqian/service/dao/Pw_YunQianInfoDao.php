<?php
defined('WEKIT_VERSION') || exit('Forbidden');

/**
 * 信息
 *
 * @package forum
 */

class Pw_YunQianInfoDao extends PwBaseDao {
	
	protected $_table = 'app_yunqian_count';
	protected $_pk = 'id';
	protected $_dataStruct = array('id', 'newmember', 'newuid','total', 'higholnum', 'higholtime', 'zqian', 'dqian', 'mqian', 'dtime', 'mtime', 'zmoney', 'dtid');
	
	public function get($id) {
		if(!$this->_get($id)){
			$this->add(array('newmember'=>'','newuid'=>0, 'total'=>0, 'higholnum'=>0, 'higholtime'=>0, 'zqian'=>0, 'dqian'=>0, 'mqian'=>0, 'mtime'=>0, 'zmoney'=>0)); 
		}
		return $this->_get($id);
	}
	
	public function add($data){
		$this->_add($data);	
	}

	public function update($id, $fields, $increaseFields = array()) {
		return $this->_update($id, $fields, $increaseFields);
	}
}