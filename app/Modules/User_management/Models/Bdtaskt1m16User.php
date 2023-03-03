<?php namespace App\Modules\User_management\Models;
use App\Libraries\Permission;
use CodeIgniter\Model;
class Bdtaskt1m16User extends Model
{
    protected $table = 'user';

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();
        //$this->hasReadAccess = $this->permission->method('sys_users', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('sys_users', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('sys_users', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('sys_users', 'delete')->access();

    }

    public function bdtaskt1m16_01_getUserList($postData=null){
         $response = array();
         ## Read value
      
        @$branch_id = trim($postData['branch_id']);
        @$department = trim($postData['department']);
        @$user_role = trim($postData['user_role']);

        @$draw = $postData['draw'];
        @$start = $postData['start'];
        @$rowperpage = $postData['length']; // Rows display per page
        @$columnIndex = $postData['order'][0]['column']; // Column index
        @$columnName = $postData['columns'][$columnIndex]['data']; // Column name
        @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        @$searchValue = $postData['search']['value']; // Search value
        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
           $searchQuery = " (user.fullname like '%".$searchValue."%' OR user.username like '%".$searchValue."%' OR user.mobile like '%".$searchValue."%' OR d.type like '%".$searchValue."%') ";
        }
        // if($branch_id != '' && $branch_id != '0'){
        //     if($searchQuery != ''){
        //         $searchQuery .= " AND ";
        //     }
        //    $searchQuery .= " ( FIND_IN_SET('".$branch_id."', user.branch_id) ) ";
        // }
        if($department != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " (user.department_id = '".$department."' ) ";
        }
        if($user_role != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " (user.user_role = '".$user_role."' ) ";
        }
        ## Fetch records
        $builder3 = $this->db->table('user')
                            ->join("hrm_employee_types as d", "d.id=user.department_id", "left");
                            // ->join("branch", "FIND_IN_SET(branch.id, user.branch_id)", "left");
        $builder3->select("user.*, d.type as nameE, 'bname' as branch_name");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }
        // if(session('branchId') >0 ){
        //     $builder3->where("branch.id", session('branchId'));
        // }
        ## Total number of records with filtering
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->groupBy('user.id');
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        ## Total number of records without filtering
        $totalRecords = $builder3->countAll();
        if($searchQuery == ''){
           $totalRecords = $totalRecordwithFilter;
        }
        $data = array();
        $edit = get_phrases(['edit', 'user']);
        $more = get_phrases(['add', 'more', 'role']);
        $delete = get_phrases(['delete', 'user']);
        foreach($records as $record ){ 
            $button = '';

            $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC editAction mr-1 custool" title="'.$edit.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC roleAction mr-1 custool" title="'.$more.'" data-emp="'.$record['emp_id'].'"><i class="fas fa-user-lock"></i></a>';
             $button .= (!$this->hasDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC deleteAction mr-1 custool" title="'.$delete.'" data-id="'.$record['id'].'" data-emp="'.$record['emp_id'].'"><i class="far fa-trash-alt"></i></a>';
            $data[] = array( 
                'id'           => $record['id'],
                'fullname'     => $record['fullname'],
                'branch_name'  => $record['branch_name'],
                'gender'       => $record['gender'],
                'date_of_birth'=> date('m/d/Y', strtotime($record['date_of_birth'])),
                'role'         => $this->bdtaskt1m16_04_getUserRole($record['emp_id']),
                'nameE'        => $record['nameE'],
                'username'     => $record['username'],
                'mobile'       => $record['mobile'],
                'created_date' => date('m/d/Y H:i A', strtotime($record['created_date'])),
                'status'       => $record['status']==1?'<span class="badge badge-pill badge-success">'.get_phrases(['active']).'</span>':'<span class="badge badge-pill badge-danger">'.get_phrases(['inactive']).'</span>',
                'button'       => $button
            ); 
        }

        ## Response
        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData"               => $data
        );
        return $response; 
    }

    public function bdtaskt1m16_02_getAllModules($postData=null){
         $response = array();
         ## Read value
      
        @$draw = $postData['draw'];
        @$start = $postData['start'];
        @$rowperpage = $postData['length']; // Rows display per page
        @$columnIndex = $postData['order'][0]['column']; // Column index
        @$columnName = $postData['columns'][$columnIndex]['data']; // Column name
        @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        @$searchValue = $postData['search']['value']; // Search value
        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
           $searchQuery = " (sub_module.nameE like '%".$searchValue."%' OR sub_module.nameA like '%".$searchValue."%') ";
        }
        ## Total number of records without filtering
        $builder1 = $this->db->table('sub_module');
        $builder1->select("count(*) as allcount");
        $query   =  $builder1->get();
        $records =   $query->getRow();
        $totalRecords = $records->allcount;
         
        ## Total number of record with filtering
        $builder2 = $this->db->table('sub_module');
        $builder2->select("count(*) as allcount")->join("module", "module.id=sub_module.mid", "left");
        if($searchValue != ''){
           $builder2->where($searchQuery);
        }
        $query2   =  $builder2->get();
        $records =   $query2->getRow();
        $totalRecordwithFilter = $records->allcount;
        ## Fetch records
        $builder3 = $this->db->table('sub_module')->select("sub_module.*, CONCAT_WS(' ', module.nameE, '-', module.nameA) as mName")->join("module", "module.id=sub_module.mid", "left");
        if($searchValue != ''){
           $builder3->where($searchQuery);
        }
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        $data = array();
        
        foreach($records as $record ){ 
            $button = '';

            $button .='<a href="javascript:void(0);" class="btn btn-info-soft btnC editAction mr-2" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $data[] = array( 
                'mid'      => $record['mid'],
                'mName'    => $record['mName'],
                'nameE'    => $record['nameE'],
                'nameA'    => $record['nameA'],
                'button'   => $button
            ); 
        }

        ## Response
        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData"               => $data
        );
        return $response; 
    }

    /*--------------------------
    | Get module Menu by ID
    *--------------------------*/
    public function bdtaskt1m16_03_getModuleMenu($id=null){
        return $this->db->table('sub_module')->select("sub_module.*, module.nameE as mnameE, module.nameA as mnameA")
                        ->join('module', 'module.id=sub_module.mid', 'left')
                        ->where('sub_module.id', $id)
                        ->get()->getRow();
    }

    /*--------------------------
    | Get module Menu by ID
    *--------------------------*/
    public function bdtaskt1m16_04_getUserRole($id=null){
        $query = $this->db->table('sec_userrole')->select("sec_role.type")
                        ->join('sec_role', 'sec_role.id=sec_userrole.roleid', 'left')
                        ->where('sec_userrole.user_id', $id)
                        ->get()->getResult();
        $list = '';
        if(!empty($query)){
            foreach ($query as $key => $value) {
                $list .= '<span class="badge badge-success-soft mr-1">'.$value->type.'</span>';
            }
        }
        return $list;
    }

    /*--------------------------
    | Get user info by ID
    *--------------------------*/
    public function bdtaskt1m16_05_getUserById($id=null){
        $query = $this->db->table('user')->select("*")
                        ->where('id', $id)
                        ->get()->getRow();
        if(!empty($query)){
            $roles = $this->db->table('sec_userrole')->select("*")
                        ->where('user_id', $query->emp_id)
                        ->get()->getResult();
        }
        $i=0;
        $list[] = '';
        if(!empty($roles)){
            foreach ($roles as $key => $value) {
                $list[$i]=$value->roleid;
                $i++;
            }
        }
        if(!empty($query)){
            $query->roleIds = $list;
        }
        return $query;
    }

     /*--------------------------
    | Get user roles by emp Id
    *--------------------------*/
    public function bdtaskt1m16_06_getRolesByEmpId($empId=null){
        $query = $this->db->table('user')->select("user.id, user.emp_id, user.fullname, user.user_role, role.type")
                        ->where('user.emp_id', $empId)
                        ->join('sec_role as role', 'role.id=user.user_role', 'left')
                        ->get()->getRow();
        $roles = $this->db->table('sec_userrole')->select("roleid")
                        ->where('user_id', $empId)
                        ->whereNotIn('roleid', [$query->user_role])
                        ->get()->getResult();
        $i=0;
        $list[] = '';
        if(!empty($roles)){
            foreach ($roles as $key => $value) {
                $list[$i]=$value->roleid;
                $i++;
            }
        }

        $query->roleIds = $list;
        return $query;
    }

    /*--------------------------
    | Get user info by ID
    *--------------------------*/
    public function bdtaskt1m16_07_getEmployeeById($id=null){
        $query = $this->db->table('hrm_employees')->select("*,CONCAT_WS(' ', first_name,last_name) as fullname")
                        ->where('employee_id', $id)
                        ->get()->getRow();
        
        return $query;
    }

    /*--------------------------
    | Get user info by ID
    *--------------------------*/
    public function bdtaskt1m16_08_get_store_name($id, $type){
        $query = $this->db->table($type.'_main_store')->select("*")
                        ->where('branch_id', $id)
                        ->where('status', 1)
                        ->get()->getRow();
        
        return (!empty($query))?$query:NULL;
    }

    /*--------------------------
    | Check username
    *--------------------------*/
    public function bdtaskt1m16_09_get_exist_username($emp_id, $username){
        $query = $this->db->table('user')->select("*")
                        ->where('username', $username)
                        ->whereNotIn('emp_id', [$emp_id])
                        ->get()->getRow();
        if(!empty($query)){
            return true;
        }else{
            return false;
        }
    }
   
}
?>