<?php namespace App\Models;
use CodeIgniter\Model;
class Bdtaskt1m2AttendanceEmployee extends Model
{

	/*  Insert purchase_info */
    public function bdtaskt1m2_01_Insert($table, $data=[])
    { 
      if(!empty($table) && !empty($data)){

      	$builder = $this->db->table($table);
        $info_insert = $builder->insert($data);

        if($info_insert){
        	return true;
        }else{
        	return false;
        }

      }else{
        return false;
      }
    }

    /*--------------------------
    | Get all rows data
    *--------------------------*/
    public function bdtaskt1m2_02_getResult($table)
    { 
      if(!empty($table)){
        return $this->db->table($table)->get()->getResult();
      }else{
        return false;
      }
    }

    /*--------------------------
    | Deleted data
    *--------------------------*/
    public function bdtaskt1m2_03_Deleted($table, $where)
    { 
      if(!empty($table)){
        return $this->db->table($table)->where($where)->delete();
      }else{
        return false;
      }
    }

}