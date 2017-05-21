<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class MY_Loader extends CI_Loader {
	
	protected $theme = 'default/';
	
	//打开皮肤功能
	public function switch_theme_on(){
	
		$this->_ci_view_paths = array(FCPATH . THEME_DIR . $this -> theme => TRUE);
	}
	
	//关闭皮肤功能
	public function switch_theme_off(){
		
	}
}
