<?php
defined('WEKIT_VERSION') or exit(403);
/**
 * Hook≈‰÷√Class
 *
 * @author Sivay Du <love@medz.cn>
 * @copyright http://www.medz.cn
 * @license http://www.medz.cn
 */
class App_Medz_Ext20131221_HookDo {
	
	/**
	 * …Ë÷√Head
	 */
	public function setHead() {
		PwHook::template( 'MedzExt20131221HeadHtml', 'EXT:MedzExt20131221.template.hook.head', true, NULL );
	}
	
	/*
	* …Ë÷√footer
	*/
	public function footer() {
		
	}

}