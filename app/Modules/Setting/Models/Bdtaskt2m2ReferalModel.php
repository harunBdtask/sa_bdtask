<?php namespace App\Modules\Setting\Models;

class Bdtaskt2m2ReferalModel
{
	
	 public function __construct()
    {
        $this->db = db_connect();
    }


    public function bdtaskt1m1_01_getReferal(){
        $builder = $this->db->table('referal_commission_setting')
                             ->get()
                             ->getRow(); 
        return $builder;
    }

    public function bdtaskt1m1_03_saveSetting($data=[]){
        $builder = $this->db->table('referal_commission_setting');
        return  $builder->insert($data);
    }

    public function bdtaskt1m1_04_updateSetting($data=[]){
        $query = $this->db->table('referal_commission_setting');   
        $query->where('id', $data['id']);
        return $query->update($data);   
    }
}