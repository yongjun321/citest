<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/2/7
 * Time: 14:19
 */
class Ad extends CI_Controller{
    public function __construct(){

        parent::__construct();
        $this->load->library('Dredis');
        //$this->load->model('ad_model');
        //$this->output->enable_profiler(true);
    }

    /**
     * 加载首页
     *
     * @return [html]
     */
    public function index(){
        $this->load->view('ad_card');
    }

    public function save(){

        $name  = $this->input->post('name',true);  //姓名
        $phone = $this->input->post('phone',true); //电话
        $city  = $this->input->post('city',true);  //城市
        $type  = $this->input->post('type',true);  //车型

         $search ='/^1[34578]\d{9}$/';
        //验证手机号是否合法
        if(empty($phone) || !preg_match($search,$phone)) {
            $code = -1;
            $msg  = '亲你的手机号好像有问题，重新检查下哟！';
            echo json_encode(array('code'=>$code,'msg'=>$msg));
            return;
        }
        //检查用户是否已经预约
        // if($this->ad_model->checkPhone($phone)){

        //     $code = -1            
        //     $msg  = '亲你已经预约了该活动！';
        //     echo json_encode(array('code'=>$code,'msg'=>$msg));
        //     return;
        // }

        $data = array(
            'name'    => $name,
            'phone'   => $phone,
            'city'    => $city,
            'type'    => $type,
            'addtime' => time(),
        );
        
        $code = 0;
        $msg  = '亲，预约成功';
        if($this->ad_model->add($data)){
            $code = 0;
            $msg  = '亲，预约成功';
            
        }
        echo json_encode(array('code'=>$code,'msg'=>$msg));
    }

}

