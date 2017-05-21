<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity_model extends CI_Model{

    const TAB_AD = 'adactivity';
    /**
     * 验证手机是否存在
     * @param $phone int 手机号
     * @return bool
     */
    public function checkPhone($phone,$stage = ''){
        $condition['phone'] = $phone;
        if(!empty($stage)){
            $condition['stage'] = $stage;
        }
        $query = $this->db->select('id')->where($condition)->get(self::TAB_AD);
        $row   = $query->row_array();
        return $row ? true : false;
    }

    /**
     * 添加数据
     * @param $data array 要添加的数据
     * @return bool
     */
    public function addUser($data){

        $query =$this->db->insert(self::TAB_AD,$data);

        return $query ? $this->db->insert_id() : false;
    }

    /***
     * 获取全部数据
     * @return mixed
     */
    public function getActivityList(){
        
        $query = $this->db->select('id,name,phone')->order_by('id','desc')->get(self::TAB_AD);
        return $query->result_array();
    }




}