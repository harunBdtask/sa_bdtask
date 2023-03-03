<?php namespace App\Modules\User_management\Models;
use CodeIgniter\Model;
class Bdtaskt1m16Permission extends Model
{
    protected $table = 'module';

    public function __construct()
    {
        $this->db = db_connect();

    }

    /*--------------------------
    | Get module list
    *--------------------------*/
    public function bdtaskt1m16_01_moduleList(){
        $query = $this->db->table($this->table)->select("*")
                        ->where("status", 1)
                        ->get()->getResultArray();
        $i=0;
        foreach ($query as $key => $value) {
            $query[$i]['subModule'] = $this->bdtaskt1m16_05_getSubModule($value['id']);
            $i++;
        }
        return $query;
    }

    /*--------------------------
    | Insert New Role
    *--------------------------*/
    public function bdtaskt1m16_02_insertNewRole($data){
        $this->db->table('sec_role')->insert($data);
        return $this->db->insertID();
    }

    /*--------------------------
    | Insert role perission
    *--------------------------*/
    public function bdtaskt1m16_03_insertRolePermission($roleId, $data){
        $this->db->table('role_permission')->where('role_id', $roleId)->delete();
        return $this->db->table('role_permission')->insertBatch($data);
    }

    /*--------------------------
    | Get module permission for edit
    *--------------------------*/
    public function bdtaskt1m16_04_allPermission($roleId){
        $query = $this->db->table($this->table)->select("*")
                        ->where("status", 1)
                        ->get()->getResultArray();
        $i=0;
        foreach ($query as $key => $value) {
            $query[$i]['subModule'] = $this->bdtaskt1m16_05_getSubModule($value['id']);
            $j=0;
            foreach ( $query[$i]['subModule'] as $value1) {
                $query[$i]['subModule'][$j]->permission = $this->bdtaskt1m16_06_getModulePerm($roleId, $value1->id);
                $j++;
            }
            $i++;
        }
        return $query;
    }

    /*--------------------------
    | Get sub module by id
    *--------------------------*/
    public function bdtaskt1m16_05_getSubModule($mid){
        return $this->db->table('sub_module')->select("*")
                        ->where("mid", $mid)
                        ->orderBy('label_no ASC, parent_name ASC')
                        ->get()->getResult();
    }

    /*--------------------------
    | Get permission sub module
    *--------------------------*/
    public function bdtaskt1m16_06_getModulePerm($roleId, $id){
        
            return $this->db->table('role_permission')->select("*")
                        ->where("fk_module_id", $id)
                        ->where("role_id", $roleId)
                        ->get()->getRow();
    }

    /*--------------------------
    | Get permission sub module
    *--------------------------*/
    public function bdtaskt1m16_07_deleteRole($id){
            $this->db->table('sec_role')
                        ->where("id", $id)
                        ->delete();
            if($this->db->affectedRows()){
                $this->db->table('role_permission')
                         ->where("role_id", $id)
                        ->delete();
                return true;
            }else{
                return false;
            }
    }

}
?>