<?php
defined('WEKIT_VERSION') or exit(403);
/* 
 *	App_Medz_Helps ������񴢴������
 *	name�����⣨sivay.Du��
 *	E-mail:love@medz.cn
 *	website:http://www.med.cn 
*/

class App_Medz_Helps {

	public function medz($name, $field=false, $write=false, $data=array()) {
		$appname = 'MedzExt20131221'; //����Ӧ�ñ�ʶ
		$Suffix = 'return.php'; // ���ô����ı���׺
		$file = Wind::getRealPath('EXT:' . $appname . '.conf.' . $name, $Suffix, true);
		$conf = @include $file;
		$conf || $conf = array();
		if($write) {
			WindFile::savePhpData($file, $data);
			return $this->medz($name, $field);
		} else {
			if($field === false) {
				return $conf;
			} else {
				return $conf[$field];
			}
		}
	}
}


