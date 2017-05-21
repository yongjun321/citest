<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/2/7
 * Time: 14:19
 */
class Barrage extends CI_Controller{
    public function __construct(){
        parent::__construct();
        date_default_timezone_set("PRC");
        $this->load->model('barrage_model');
        $this->load->library('Dredis');
        //$this->output->enable_profiler(true);
    }

    /**
     * 加载首页
     * 1
     * @return [html]
     */
    public function index(){
        if(!$result = $this->dredis->lrange('list',0,100)){
            $result = $this->barrage_model->listBarrage(30);


            $list = '';

            foreach($result as $val){
                $datas = array(
                    'content' => $val['content'], 
                );
                //json_encode($datas);
                $this->dredis->lpush('list',json_encode($datas));
                $list .= $this->returnHtml($val['content']);
            }
        }else{  
            $list = '';
            foreach($result as $v){
                $val = json_decode($v,true);
                if(strstr($val['content'],'地铁') || stristr($val['content'],'wifi')){
                    continue;
                }
                $list .= $this->returnHtml($val['content']);
            }
        }
        $data['list'] = $list;
        $this->load->view('index',$data);
    }
    /**
     * ajax回去弹幕列表
     * @return html
     */
    public function lists(){
        //从redis获取20条内容
        $list = $this->dredis->lrange('list',0,50);
        $data = '';
        foreach($list as $v){
            $val = json_decode($v,true);
           
            $data .= $this->returnHtml($val['content']);
        }
        echo $data;
    }

    /**
     * 保存弹幕
     * @return json
     */
    public function save(){


        // $user = $this->session->userdata('user');

        // if(empty($user)){
        //     $code = '-1';
        //     $msg  = '显示注册页面';
        //     echo json_encode(array('code'=>$code,'msg'=>$msg));
        //     return;
        // }

       // $phone   = $user['phone'];   //手机号码
       // $u_id    = $user['id'];      //账户id
       // $sex     = $user['sex'];                //头像类型
        $content = $this->input->post('content',true);  //发射内容


        if(empty($content)){
            $code = '-2';
            $msg  = '发射内容不能为空';
            echo json_encode(array('code'=>$code,'msg'=>$msg));
            return;
        }

        if(mb_strlen($content,'UTF8') > 70){
            $code = '-2';
            $msg  = '发射的内容过多';
            echo json_encode(array('code'=>$code,'msg'=>$msg));
            return;
        }
        
        $key   = md5($this->input->ip_address());
        //获取上一发送的时间
        $dTime = $this->dredis->get($key);
        if(!empty($dTime) && time() < $dTime+10){
            $code = '-2';
            $msg = '宝贝你说的太快了，休息一下再发吧！';
            echo json_encode(array('code'=>$code,'msg'=>$msg));
            return;
        }
        //验证敏感词
        $badword = file_get_contents('./public/sensitive.txt');
        $hei = explode("\n",$badword);
        $blacklist="/".implode("|",$hei)."/i";

        if(preg_match($blacklist, $content, $matches)){
            $code = '-2';
            $msg  = '宝贝你说的有点猛，重新编辑一下吧！';
            echo json_encode(array('code'=>$code,'msg'=>$msg));
            return;
        }
        $data = array(
            'phone'    => '12',
            'addTime'  => time(),
            'content'  => $content,
            'u_id'     => 0,
            'sex'      => 0,
        );
        if($this->barrage_model->addBarrage($data)){
            //保存发送时间判断发送间隔
            $this->dredis ->set($key,time()); 
            if($this->dredis ->lpush('list',json_encode($data)) > 500){
               $this->dredis->RPOP('list');   //删除旧的元素
            }
            $code = 1;
            $path    = base_url();
            $head    = $path.'public/images/head3.png';
            $con = preg_replace('/\[em_([0-9]*)\]/','<img src="'.$path.'public/face/$1.gif" border="0" />',$content);
            $msg = '<div class="message-box" tag="me"><img src="'.$head.'" alt=""  class="tx-ico"><div class="message">'.$con.'</div></div>';
            echo json_encode(array('code'=>$code,'msg'=>$msg));
        }

    }

    /**
     * 注册登录
     * @return json
     */
    public function reg(){
        $phone  = $this->input->post('phone');
        $sex    = $this->input->post('sex');
        $search ='/^1[34578]\d{9}$/';
        //验证手机号是否合法
        if(empty($phone) || !preg_match($search,$phone)) {
            $code = -1;
            $msg  = '亲爱的手机号好像有问题，重新检查下哟！';
            echo json_encode(array('code'=>$code,'msg'=>$msg));
            return;
        }
        if(empty($sex) && ($sex!=1 || $sex!=2)){
            $code = -1;
            $msg  = '告诉我你是哥哥还是妹妹哦！';
            echo json_encode(array('code'=>$code,'msg'=>$msg));
            return;
        }
        //如果手机号码存在就登陆
        if($datas = $this->barrage_model->getUser($phone)){
            $code = 1;
            $msg  = '成功';
            $u_id = $datas['id'];
            $sex  = $datas['sex'];
        }else{
            //添加操作
            $data = array(
                'phone' => $phone,
                'sex'  => $sex
            );
            $code = -1;
            $msg  = '添加号码失败';
            if($u_id = $this->barrage_model->addUser($data)) {
                $code = 1;
                $msg  = '成功';
            }
        }
        $user = array(
            'id'    => $u_id,
            'phone' =>$phone,
            'sex'   => $sex,
        );
        $this->session->set_userdata('user',$user);
        echo json_encode(array('code'=>$code,'msg'=>$msg));
    }
    


    //获取所有用户
    public function userList(){
        //获取用户消息
        $data = $this->barrage_model->userList();

        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        $resultPHPExcel = new Phpexcel();
        $resultPHPExcel->getActiveSheet()->setCellValue('A1', '序号');
        $resultPHPExcel->getActiveSheet()->setCellValue('B1', '手机号');
        $resultPHPExcel->getActiveSheet()->setCellValue('C1', '性别');
        $i = 2;
        foreach($data as $val){
            $sex = $val['sex'] == 1 ? '男' : '女';
            $resultPHPExcel->getActiveSheet()->setCellValue('A' . $i, $val['id']);
            $resultPHPExcel->getActiveSheet()->setCellValue('B' . $i, $val['phone']);
            $resultPHPExcel->getActiveSheet()->setCellValue('C' . $i, $sex);
            $i ++;
        }

        $outputFileName = "user.xls";
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

    //导出所有弹幕
    public function barrageList(){

        //获取用户消息
        $data = $this->barrage_model->getBarrageList();

        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        $resultPHPExcel = new PHPExcel();
        $resultPHPExcel->getActiveSheet()->setCellValue('A1', '序号');
        $resultPHPExcel->getActiveSheet()->setCellValue('B1', '手机号');
       // $resultPHPExcel->getActiveSheet()->setCellValue('C1', '性别');
        $resultPHPExcel->getActiveSheet()->setCellValue('D1', '内容');
        $resultPHPExcel->getActiveSheet()->setCellValue('E1', '发布时间');
        $i = 2;
        foreach($data as $val){
            $sex = $val['sex'] == 1 ? '男' : '女';
            $con  = preg_replace('/\[em_([0-9]*)\]/','<img src="'.base_url().'public/face/$1.gif" border="0" />',$val['content']);
            $time = date('Y-m-d H:i:s',$val['addTime']);
            $resultPHPExcel->getActiveSheet()->setCellValue('A' . $i, $val['id']);
            $resultPHPExcel->getActiveSheet()->setCellValue('B' . $i, $val['phone']);
           // $resultPHPExcel->getActiveSheet()->setCellValue('C' . $i, $sex);
            $resultPHPExcel->getActiveSheet()->setCellValue('D' . $i, $con);
            $resultPHPExcel->getActiveSheet()->setCellValue('E' . $i, $time);
            $i ++;
        }
        $outputFileName = "barrageList.xls";
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
    //测试
    public function demo(){

       // echo $this->input->ip_address();

        //$this->dredis->set('name','zhangsan');

        // echo $this->dredis->get('name');
        //$this->dredis->ltrim('list',1,0);

        $result = $this->dredis->lrange('list',0,30);
        var_dump($result);
        
    }

    //删除弹幕消息
    public function delBarrage(){

        $id = $this->input->post('id',true);

        if($this->barrage_model->delBarrage($id)){
            echo 1;
        }
    }
    //
    public function listBarrage($offset = 0){

            $this->load->library('pagination');
            #配置分页信息
            $config['base_url']   = site_url('barrage/listBarrage');
            $config['total_rows'] = $this->barrage_model->countBarrage();
            $config['per_page']   = 20;
    // Bracket Highlighter       //$config['uri_segment']=3;

            #自定义分页链接
            $config['first_link'] = '首页';
            $config['last_link']  = '尾页';
            $config['prev_link']  = '上一页';
            $config['next_link']  = '下一页';

            #初始化分页类
            $this->pagination->initialize($config);
            #生成分页信息
            $data['pageInfo']  = $this->pagination->create_links();
            $limit = $config['per_page'];
            $data['barrageList'] = $this->barrage_model->listBarrage($limit,$offset);
           // var_dump($data);
            $this->load->view('barrage_list',$data);
    }
    //返回弹幕消息
    public function returnHtml($content,$isOwn = false){
        $head = rand(1,2);
        if($isOwn){
            $head = "3";
        }

        $path    = base_url();
        $headUrl = $path.'public/images/head'.$head.'.png';
        $con = preg_replace('/\[em_([0-9]*)\]/','<img src="'.$path.'public/face/$1.gif" border="0" />',$content);
        $html = '<div class="message-box" ><img src="'.$headUrl.'" alt=""  class="tx-ico"><div class="message"><p>'.$con.'</p></div></div>';
        return $html;
    }

    public function demoShell(){
       //var_dump(system("/bin/make.sh"));
       exec("/Users/yongjunxie/findNWrite.sh demo netcon 10.84.115.187 ");
     //  var_dump($res);
       //  var_dump($staus);

        //exec("/bin/make.sh");
        // var_dump($output);
    }

}

