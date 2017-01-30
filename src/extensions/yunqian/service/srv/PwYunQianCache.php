<?php
/**
 *
 */
class PwYunQianCache {
	
	public function _getLevel($lid=0){
		$Level=array(
			'1'=>array(
				'title'=>'初来乍到',
				'hits'=>'1',
				'dj'=>'1',
			),
			'2'=>array(
				'title'=>'偶尔看看I',
				'hits'=>'3',
				'dj'=>'2',
			),
			'3'=>array(
				'title'=>'偶尔看看II',
				'hits'=>'7',
				'dj'=>'3',
			),
			'4'=>array(
				'title'=>'偶尔看看III',
				'hits'=>'15',
				'dj'=>'4',
			),
			'5'=>array(
				'title'=>'常住居民I',
				'hits'=>'30',
				'dj'=>'5',
			),
			'6'=>array(
				'title'=>'常住居民II',
				'hits'=>'60',
				'dj'=>'6',
			),
			'7'=>array(
				'title'=>'常住居民III',
				'hits'=>'120',
				'dj'=>'7',
			),
			'8'=>array(
				'title'=>'以坛为家I',
				'hits'=>'240',
				'dj'=>'8',
			),
			'9'=>array(
				'title'=>'以坛为家II',
				'hits'=>'365',
				'dj'=>'9',
			),
			'10'=>array(
				'title'=>'以坛为家III',
				'hits'=>'750',
				'dj'=>'10',
			),
			'11'=>array(
				'title'=>'伴坛终老',
				'hits'=>'1500',
				'dj'=>'11',
			),
		);
		if($lid) return $Level[$lid];
		return $Level;
	}
	
	public function getLei($id=0){
		$leidb=array(
			'1'=>array(
				'id'=>'1',
				'title'=>'开心',
			),
			'2'=>array(
				'id'=>'2',
				'title'=>'难过',
			),
			'3'=>array(
				'id'=>'3',
				'title'=>'郁闷',
			),
			'4'=>array(
				'id'=>'4',
				'title'=>'无聊',
			),
			'5'=>array(
				'id'=>'5',
				'title'=>'愤怒',
			),
			'6'=>array(
				'id'=>'6',
				'title'=>'流汗',
			),
			'7'=>array(
				'id'=>'7',
				'title'=>'奋斗',
			),
			'8'=>array(
				'id'=>'8',
				'title'=>'慵懒',
			),
			'9'=>array(
				'id'=>'9',
				'title'=>'倒霉',
			)
		);
		if($id) return $leidb[$id];
		return $leidb;
	}
}

?>