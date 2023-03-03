<?php namespace App\Modules\Dashboard\Models;
use CodeIgniter\Model;
class Bdtaskt1m1AuthModel extends Model
{

    /**
     * Constructor.
     */
	 public function __construct()
    {
        $this->db = db_connect();
    }

    public function bdtaslt1m1_01_getUsers()
    {
        $builder = $this->db->table('user');
		$builder->select('*');
		$query   = $builder->get(); 
		return $query->getResult();
    }

    public function bdtaslt1m1_02_checkUser($data = array())
	{
        $builder = $this->db->table('user');
		$builder->select("user.*, hrm_employees.image as photo,hrm_employees.signature_file as signature, sec_role.type");
        $builder->join('hrm_employees', 'hrm_employees.employee_id=user.emp_id', 'left');
        $builder->join('sec_role', 'sec_role.id=user.user_role', 'left');
		$builder->where('user.username', $data['username']);
		$builder->where('user.password', md5($data['password']));

        // if($data['branch_id'] == 0){
           // $builder->where('user.user_role', 1);
        // } else {
        //     $builder->groupStart();
        //     $builder->where('user.user_role', 1);
        //     $builder->orWhere('FIND_IN_SET('.$data['branch_id'].', user.branch_id)');
        //     $builder->groupEnd();
        // }

		return $query = $builder->get(); 
	}



	// //role permission check
	public function bdtaslt1m1_03_userPermissionadmin($roleId = null)
    {
        
        return $this->db->table('role_permission')->select("
            module.directory as mdir,
            sub_module.directory, 
            role_permission.fk_module_id, 
            role_permission.create, 
            role_permission.read, 
            role_permission.update, 
            role_permission.delete,
            role_permission.print,
            role_permission.export
            ")
            ->join('sub_module', 'sub_module.id = role_permission.fk_module_id', 'full')
            ->join('module', 'module.id = sub_module.mid', 'left')
            ->where('role_permission.role_id', $roleId)
            ->where('sub_module.status', 1)
            ->where('sub_module.display', 1)
            ->groupStart()
                ->where('create', 1)
                ->orWhere('read', 1)
                ->orWhere('update', 1)
                ->orWhere('delete', 1)
                ->orWhere('print', 1)
                ->orWhere('export', 1)
            ->groupEnd()
            ->get()
            ->getResult();
    }

    public function bdtaslt1m1_04_userPermission($id = null)
    {
        

        $userrole=$this->db->table('sec_userrole')
                        ->select('sec_userrole.*,sec_role.*')
                       ->join('sec_role','sec_userrole.roleid=sec_role.id')
                       ->where('sec_userrole.user_id',$id)
                       ->get()
                       ->getResult();

        $roleid = array();
        foreach ($userrole as $role) {
            $roleid[] =$role->roleid;
        }
    
        if(!empty($roleid)){
         return $result =  $this->db->table('role_permission')
                                ->select("
                    module.directory as mdir,
                    role_permission.fk_module_id, 
                    sub_module.directory,
                    IF(SUM(role_permission.create) >= 1,1,0) AS 'create', 
                    IF(SUM(role_permission.read) >= 1,1,0) AS 'read', 
                    IF(SUM(role_permission.update) >= 1,1,0) AS 'update', 
                    IF(SUM(role_permission.delete) >= 1,1,0) AS 'delete',
                    IF(SUM(role_permission.print) >= 1,1,0) AS 'print',
                    IF(SUM(role_permission.export) >= 1,1,0) AS 'export',
                ")
                ->join('sub_module', 'sub_module.id = role_permission.fk_module_id', 'full')
                ->join('module', 'module.id = sub_module.mid', 'left')
                ->whereIn('role_permission.role_id',$roleid)
                ->where('sub_module.status', 1)
                ->where('sub_module.display', 1)
                ->groupBy('role_permission.fk_module_id')
                ->groupStart()
                    ->where('create', 1)
                    ->orWhere('read', 1)
                    ->orWhere('update', 1)
                    ->orWhere('delete', 1)
                    ->orWhere('print', 1)
                    ->orWhere('export', 1)
                ->groupEnd()
                
                ->get()
                ->getResult();
            }else{

            return $this->db->table('role_permission')
                            ->select("
                sub_module.directory, 
                role_permission.fk_module_id, 
                role_permission.create, 
                role_permission.read, 
                role_permission.update, 
                role_permission.delete,
                role_permission.print,
                role_permission.export,
                ")
                ->join('sub_module', 'sub_module.id = role_permission.fk_module_id', 0)
                ->where('role_permission.role_id', 0)
                ->where('sub_module.status', 1)
                ->where('sub_module.display', 1)
                ->groupStart()
                    ->where('create', 1)
                    ->orWhere('read', 1)
                    ->orWhere('update', 1)
                    ->orWhere('delete', 1)
                    ->orWhere('print', 1)
                    ->orWhere('export', 1)
                ->groupEnd()
                ->get()
                ->getResult();
            }
    }

    public function bdtaslt1m1_05_last_login($ipadd)
    {
        $data = array(
            'user_id' => session('id'), 
            'ip_address'=>$ipadd, 
            'date'=>date('Y-m-d H:i:s'), 
            'status'=>1
        );

        $builder = $this->db->table('login_status');
        $builder->insert($data);
        return true;
    }

    public function bdtaslt1m1_06_getLangData($language)
    {
        $table = 'language';
        $db      = db_connect();
        if ($db->fieldExists($language, $table)) {

            $result = $db->table($table)
                  ->select('*')
                 ->get()
                 ->getResultArray();

            if (!empty($result)) {
                $output[] = "";
                $i = 0;
                foreach ($result as $val) {
                    $output[$val['phrase']] = $val[$language];
                    $i++;
                }
                return $output;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    public function bdtaslt1m1_07_last_logout($ipadd, $actionData)
    {
        if(!empty(session('id'))){
            $data = array(
                'user_id' => session('id'), 
                'ip_address'=>$ipadd, 
                'date'=>date('Y-m-d H:i:s'), 
                'status'=>0
            );
            $this->db->table('activity_logs')->insert($actionData);
            $builder = $this->db->table('login_status');
            $builder->insert($data);
            return true;
        }
    }

    public function bdtaslt1m1_08_updateUser($id, $data)
    {
        $builder = $this->db->table('user');
        $builder->where('emp_id', $id);
        $builder->update(array('auth_token'=> $data));
        return true;
    }

    public function bdtaslt1m1_09_updateLanguage($language=[])
    {
        $builder = $this->db->table('setting');
        $builder->where('id', 1);
        $builder->update($language);
        return true;
    }

    public function bdtaslt1m1_10_getBranchs()
    {
        $builder = $this->db->table('branch');
        $builder->select('id, nameE as text');
        $builder->where('status', 1);
        $query   = $builder->get(); 

        $data = array('0'=>'All Branch');
        foreach ($query->getResult() as $row) {
            $data[$row->id] = $row->text;
        }
        return $data;
    }

    public function bdtaslt1m1_11_clearLog()
    {
        /*$data = array(
            'nameE' => 'Test med'
        );
        $builder = $this->db->table('ph_medicine');
        $builder->insert($data);
        return 1;*/
        $builder = $this->db->table('activity_logs');
        $builder->where('DATEDIFF(CURDATE(), DATE(created_date)) >', 3);
        $builder->update(array('form_data'=> ''));
        return $this->db->affectedRows();
    }
}