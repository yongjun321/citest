<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/2/7
 * Time: 14:19
 */
class Sellog extends CI_Controller{
    public function __construct(){

        parent::__construct();
       // $this->load->library('Dredis');
        //$this->load->model('ad_model');
        //$this->output->enable_profiler(true);
    }

    /**
     * 加载首页
     *
     * @return [html]
     */
    public function index(){

        $this->load->view('sellog.php');
    }

    public function save(){

       
    }

}

