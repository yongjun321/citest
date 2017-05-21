<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class MY_Model extends CI_Model{

   /**
    * 该Mode对应的表
    * @var string
    */
    var $table = '';

    /**
     * 该Model对应的主键
     * 
    * @var string
    */
    var $primaryKey  = 'id';

    public function __construct(){

        parent::__construct();

        $this->load->database();
    }

    /**
     * 执行sql
     * @param $sql
     * @param bool $affect_num 是否返回影响行数
     * @param mixed
     */
    public function query($sql,$affect_num = false){

        $query = $this->db->query($sql);

        if($affect_num){
            $query = $this->db->affected_rows();
        }
        return $query;
    }

    /**
     * 返回多行数据
     * @param $sql
     * @return mixed
     */
    public function getRows($sql){

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /**
     * 返回单行数据
     * @param $sql
     * @return mixed
     */
    public function getRow($sql){

        $data = $this->getRows($sql);
        return $data[0];
    }

    /**
     * 返回单行首列数据
     * @param $sql
     * @return mixed
     */
    public function getOne($sql){

        $data = $this->getRow($sql);
        return current($data);
    }

    /**
     * 插入数据
     * @param $data  插入的数据array();
     * @param string $table 表名
     * @param bool|false $return 是否需要返回插入成功的id
     * @return bool
     */
    public function insert($data,$table = '',$return = false){

        if(!$table){
            //判断表名
            if(!$this->table) {
                return false;
            }
            $table = $this->table;
        }
        if(!in_array($data)){
            return false;
        }
        $query = $this->db->insert($table,$data);
        if($return && $query){
            $query = $this->db->insert_id();
        }
        return $query;
    }

    /**
     * 删除数据
     * @param $where array('field'=>'value')
     * @param string $table
     * @param int $limit
     * @return bool
     */
    public function delete($where,$table = '',$limit = 1){

        if(!$table){
            if(!$this->table){
                return false;
            }
            $table = $this->table;
        }
        $this->db->where = $where;
        $this->db->limit = $limit;
        $this->db->delete($table);
    }

    public function update($where,$updata,$table = ''){

        if(!$table){
            if(!$this->table){
                return false;
            }
            $table = $this->table;
        }

        $this->db->where($where);
        $this->db->update($table,$updata);
        return $this->db->affected_rows();
    }
}