<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/5/16
 * Time: 14:19
 */
class Activity extends CI_Controller{
    public function __construct(){

        parent::__construct();
        date_default_timezone_set("PRC");
        //$this->load->library('Dredis');
        $this->load->model('activity_model');
       // $this->load->helper('date');
        //$this->output->enable_profiler(true);
    }

    /**
     * 加载首页
     */
    public function index(){
        $this->load->view('activity');
    }

    /**
     * 保存数据
     */
    public function save(){

        $name    = $this->input->post('name',true);  //姓名
        $phone   = $this->input->post('tel',true); //电话
        $search ='/^1[34578]\d{9}$/';
        //验证手机号是否合法
        if(empty($phone) || !preg_match($search,$phone)) {
            $code = -1;
            $msg  = '请输入正确的电话号码！';
            echo json_encode(array('code'=>$code,'msg'=>$msg));
            return;
        }

        //检查用户是否已经预约
         if($this->activity_model->checkPhone($phone)){
             $code = -1;
             $msg  = '您的资料已提交，请勿重复操作。';
             echo json_encode(array('code'=>$code,'msg'=>$msg));
             return;
         }

        $data = array(
            'name'    => $name,
            'phone'   => $phone,
            'addTime' => time(),
        );
        
        $code = -1;
        $msg  = '提交失败';
        if($this->activity_model->addUser($data)){
            $code = 0;
            $msg  = '提交成功，等待开奖。';
           // $this->dredis ->set($key,time());   //保存发送时间判断发送间隔
        }
        echo json_encode(array('code'=>$code,'msg'=>$msg));
    }

    /**
     * 统计预约数据
     */
    public function getCountUser(){
        $data = $this->activity_model->countUserData(2);
        echo $data;
    }

    public function adList(){
        $list = $this->activity_model->getCallData();
        $data['list'] = $list;
        $this->load->view('ad_card_list',$data);
    }


    //导出所有弹幕
    public function activityList(){

        //获取用户消息
        $data = $this->activity_model->getActivityList();

        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        $resultPHPExcel = new PHPExcel();
        $resultPHPExcel->getActiveSheet()->setCellValue('A1', '序号');
        $resultPHPExcel->getActiveSheet()->setCellValue('B1', '姓名');
        $resultPHPExcel->getActiveSheet()->setCellValue('C1', '电话号码');
        $i = 2;
        foreach($data as $val){
            $resultPHPExcel->getActiveSheet()->setCellValue('A' . $i, $val['id']);
            $resultPHPExcel->getActiveSheet()->setCellValue('B' . $i, $val['name']);
            $resultPHPExcel->getActiveSheet()->setCellValue('C' . $i, $val['phone']);
            $i ++;
        }
        $outputFileName = "activity.xls";
        $xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel);
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outputFileName.'"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $xlsWriter->save( "php://output" );
    }

}

