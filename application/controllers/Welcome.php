<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends Home_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{


		//$this->cache->redis->lpush('mylist', 'name');


		$this->load->library('Dredis');


		//var_dump($this->dredis);exit;
		//$redisObj    = new dredis('127.0.0.1');
		$this->dredis ->set('name','1111');
		echo $this->dredis->get('name');
//		$this->dredis->lpush('list1','1');
//		$this->dredis->lpush('list1','2');
//		$this->dredis->lpush('list1','3');
//		$this->dredis->lpush('list1','4');
//		$this->dredis->lpush('list1','5');
//		$this->dredis->lpush('list1','6');
//		$this->dredis->lpush('list1','7');
//		$this->dredis->lpush('list1','8');
//		$this->dredis->lpush('list1','9');
//		$this->dredis->lpush('list1','10');
//		$this->dredis->lpush('list1','11');
//		$this->dredis->lpush('list1','12');
//		$this->dredis->lpush('list1','13');
//		$this->dredis->lpush('list1','14');
		//echo $this->dredis->RPOP('list1');
		if($this->dredis->lpush('list1','17') > 18){
			echo $this->dredis->RPOP('list1');
		};
		var_dump($this->dredis->lrange('list1',0,-1));
		exit;

		$this->dredis->lPush('name','张三');
		//$this->dredis->lPush('name','李四');
		//->lPush('name','王五');
		var_dump($this->dredis);exit;
		var_dump($redisObj->lIndex('name',1,3));

		//$this->load->helper(array('form', 'url'));
		///$appObj    = new Apkparser();
		//$targetFile = './upload/ssssss.apk';
	//	$res     = $appObj->open($targetFile);

		// $appObj->getAppName()."\n";         // 应用名称

		//echo $appObj->getPackage()."\r\n";        // 应用包名

		//echo $appObj->getVersionName()."\r";    // 版本名称

		//echo $appObj->getVersionCode()."\n";    // 版本代码

		//	$this->load->view('welcome_message');
	}

	public function demo(){

		$this->load->driver('cache');

		if($this->cache->memcached->save('foo','1231')){
			echo 1;
		}

	}
}
