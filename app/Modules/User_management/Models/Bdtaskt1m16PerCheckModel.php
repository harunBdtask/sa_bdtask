<?php namespace App\Modules\User_management\Models;
use App\Libraries\Permission;
use CodeIgniter\Model;
class Bdtaskt1m16PerCheckModel extends Model
{
    protected $table = 'user';

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();
        //$this->hasReadAccess = $this->permission->method('sys_users', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('sys_users', 'create')->access();
        //$this->hasUpdateAccess = $this->permission->method('sys_users', 'update')->access();
        //$this->hasDeleteAccess = $this->permission->method('sys_users', 'delete')->access();

    }

    /*--------------------------
    | Check username
    *--------------------------*/
    public function bdtaskt1m16_01_get_check_result($emp_id, $module, $sub_module){
        $result = array();
        $query = $this->db->table('sec_userrole')->select("roleid")
                        ->where('user_id', $emp_id)
                        ->get()->getResult();
        $roles = array();
        foreach($query as $row){
            $roles[] = $row->roleid;
        }
        //echo get_last_query();exit;
        if(!empty($roles)){

            $create = $this->db->table('role_permission')->select("*")
                        ->where('fk_module_id', $sub_module)
                        ->whereIn('role_id', $roles)
                        ->where('create', 1)
                        ->get()->getRow();
            $read = $this->db->table('role_permission')->select("*")
                        ->where('fk_module_id', $sub_module)
                        ->whereIn('role_id', $roles)
                        ->where('read', 1)
                        ->get()->getRow();
            $update = $this->db->table('role_permission')->select("*")
                        ->where('fk_module_id', $sub_module)
                        ->whereIn('role_id', $roles)
                        ->where('update', 1)
                        ->get()->getRow();
            $delete = $this->db->table('role_permission')->select("*")
                        ->where('fk_module_id', $sub_module)
                        ->whereIn('role_id', $roles)
                        ->where('delete', 1)
                        ->get()->getRow();
            $print = $this->db->table('role_permission')->select("*")
                        ->where('fk_module_id', $sub_module)
                        ->whereIn('role_id', $roles)
                        ->where('print', 1)
                        ->get()->getRow();
            $export = $this->db->table('role_permission')->select("*")
                        ->where('fk_module_id', $sub_module)
                        ->whereIn('role_id', $roles)
                        ->where('export', 1)
                        ->get()->getRow();        
        }
        $mod = $this->db->table('module')->select("directory")
                    ->where('id', $module)
                    ->where('status', 1)
                    ->get()->getRow();

        $sub = $this->db->table('sub_module')->select("directory")
                    ->where('id', $sub_module)
                    ->where('status', 1)
                    ->get()->getRow();
        $result['mod'] = (!empty($mod))?$this->permission->check_label($mod->directory)->access():0;
        $result['sub'] = (!empty($sub))?$this->permission->module($sub->directory)->access():0;
        $result['create'] = !empty($create)?1:0;
        $result['read'] = !empty($read)?1:0;
        $result['update'] = !empty($update)?1:0;
        $result['delete'] = !empty($delete)?1:0;
        $result['print'] = !empty($print)?1:0;
        $result['export'] = !empty($export)?1:0;

        return $result;
    }
   
    public function bdtaskt1m16_02_getSubModule($module){
        $query = $this->db->table('sub_module')->select("id, CONCAT(parent_name, ' > ', nameE) as text")
                        ->where('mid', $module)
                        ->where('status', 1)
                        ->orderBy('parent_name')
                        ->get()->getResult();
        return $query;
    }
}
?>