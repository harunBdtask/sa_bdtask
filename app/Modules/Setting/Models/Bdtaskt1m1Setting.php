<?php namespace App\Modules\Setting\Models;

class Bdtaskt1m1Setting
{
	
	 public function __construct()
    {
        $this->db = db_connect();
    }

    public function bdtaskt1m1_01_languageList()
    { 
        if ($this->db->tableExists("language")) { 

                $fields = $this->db->getFieldData("language");

                $i = 1;
                foreach ($fields as $field)
                {  
                    if ($i++ > 2)
                    $result[$field->name] = ucfirst($field->name);
                }

                if (!empty($result)) return $result;
 

        } else {
            return false; 
        }
    }

    public function bdtaskt1m1_02_getSetting(){
        $builder = $this->db->table('setting')
                             ->get()
                             ->getRow(); 
        return $builder;
    }

    public function bdtaskt1m1_03_saveSetting($data=[]){
        $builder = $this->db->table('setting');
        return  $builder->insert($data);
    }

    public function bdtaskt1m1_04_updateSetting($data=[]){
        $query = $this->db->table('setting');   
        $query->where('id', $data['id']);
        return $query->update($data);   
    }

    public function bdtaskt1m1_05_getVoucherSettingList(){
        $query = $this->db->table('setting_vouchers')->select('id, nameE as text')->get()->getResult();   
        return $query;   
    }

    public function bdtaskt1m1_06_getFinancialTypeList(){
        $query = $this->db->table('financial_type type')
                          ->select('type.*, branch.nameE as branch_name')
                          ->join('branch', 'branch.id=type.branch_id', 'left')
                          ->orderBy('branch.nameA', 'asc')
                          ->orderBy('type.start_amount', 'asc')
                          ->get()->getResult();   
        return $query;   
    }
   
}