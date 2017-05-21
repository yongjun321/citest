<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Barrage_model extends CI_Model{

    const TAB_USER = 'user';
    const TAB_CONTENT = 'content';
    /**
     * 获取一条用户
     * @param $phone int 手机号码
     * @return bool
     */
    public function getUser($phone){
        $condition['phone'] = $phone;
        $query = $this->db->select('id,sex')->where($condition)->get(self::TAB_USER);
        $row   = $query->row_array();
        return $row ? $row : false;
    }

    public function userList(){

        $query = $this->db->select('*')->get(self::TAB_USER);
        return $query->result_array();
    }

    /**
     * 添加用户信息
     * @param $data
     * @return bool
     */
    public function addUser($data){

        $query =$this->db->insert(self::TAB_USER,$data);

        return $query ? $this->db->insert_id() : false;
    }


    /**
     * 添加弹幕消息
     * @param $data
     * @return bool
     */
    public function addBarrage($data){

        $query =  $this->db->insert(self::TAB_CONTENT,$data);
        return $query ? $this->db->insert_id() : false;
    }


    /**
     * 获取弹幕列表
     * @param string $limit
     * @return mixed
     */
    public function listBarrage($limit = '10',$offset = 0){

       
        $query = $this->db->select('id,content,sex')->limit($limit,$offset)->order_by('id','desc')->get(self::TAB_CONTENT);
        return $query->result_array();
    }

    /**
     * 获取全部弹幕列表
     * @param string $limit
     * @return mixed
     */

    public function getBarrageList(){

        $query = $this->db->select('*')->order_by('id','desc')->get(self::TAB_CONTENT);
        return $query->result_array();
    }

    /**
     * 根据用户id回去一条弹幕消息
     * @param $u_id
     * @return mixed
     */
    public function getBarrage($u_id){
        $condition['u_id'] = $u_id;
        $query = $this->db->select('addTime')->limit(1)->order_by('id','desc')->where($condition)->get(self::TAB_CONTENT);
        return $query->row_array();
    }

    /**
     * 删除弹幕消息
     * @param $id  要删除消息id
     * @return bool
     */
    public function delBarrage($id){

        $condition['id'] = $id;

        return $this->db->where($condition)->delete(self::TAB_CONTENT);

    }


    /***
     * 统计用户
     */
    public function countUser(){
        return $this->db->count_all(self::TAB_USER);
    }

    /***
     * 统计弹幕消息
     */
    public function countBarrage(){

        return $this->db->count_all(self::TAB_CONTENT);
    }

}