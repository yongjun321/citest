<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  前台控制器
 */
class Home_Controller extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		#开启皮肤功能
		$this->load->switch_theme_on();
	}
}


/**
 * 后台控制器
 */
class Admin_Controller extends CI_Controller {
	
	function __construct() {
		
		parent::__construct();
		
		#关闭皮肤功能
		$this->load->switch_theme_off();
	}
}

