<?php namespace App\Modules\Human_resource\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;
class Bdtaskt1m1Employee extends Model
{
    protected $table = 'employees';

    public function __construct()
    {
        $this->db = db_connect();
        // if(session('defaultLang')=='english'){
        //     $this->langColumn = 'nameE';
        // }else{
        //     $this->langColumn = 'nameA';
        // }

        $this->permission = new Permission();
        $this->hasReadAccess = $this->permission->method('employee', 'read')->access();
        $this->hasPrintAccess = $this->permission->method('employee', 'print')->access();
        $this->hasUpdateAccess = $this->permission->method('employee', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('employee', 'delete')->access();

        $this->hasDepartmentsReadAccess = $this->permission->method('departments', 'read')->access();
        $this->hasDepartmentsUpdateAccess = $this->permission->method('departments', 'update')->access();
        $this->hasDepartmentsDeleteAccess = $this->permission->method('departments', 'delete')->access();

        $this->hasEmpTypeReadAccess = $this->permission->method('employee_type', 'read')->access();
        $this->hasEmpTypeUpdateAccess = $this->permission->method('employee_type', 'update')->access();
        $this->hasEmpTypeDeleteAccess = $this->permission->method('employee_type', 'delete')->access();

        $this->hasDesignationReadAccess = $this->permission->method('designations', 'read')->access();
        $this->hasDesignationUpdateAccess = $this->permission->method('designations', 'update')->access();
        $this->hasDesignationDeleteAccess = $this->permission->method('designations', 'delete')->access();

        $this->hasBranchReadAccess = $this->permission->method('branch', 'read')->access();
        $this->hasBranchUpdateAccess = $this->permission->method('branch', 'update')->access();
        $this->hasBranchDeleteAccess = $this->permission->method('branch', 'delete')->access();

        $this->hasBestEmployeeReadAccess = $this->permission->method('best_employee', 'read')->access();
        $this->hasBestEmployeeUpdateAccess = $this->permission->method('best_employee', 'update')->access();
        $this->hasBestEmployeeDeleteAccess = $this->permission->method('best_employee', 'delete')->access();
    }

    /*--------------------------
    | getAllDepartments
    *--------------------------*/

    public function bdtaskt1m1_01_getAllDepartments($postData=null){

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

            $searchQuery = " (hrm_departments.name like '%".$searchValue."%' or hrm_employees.first_name like '%".$searchValue."%' or hrm_employees.last_name like '%".$searchValue."%') ";
        }

        ## Fetch records
        $builder3 = $this->db->table('hrm_departments');
        $builder3->select("hrm_departments.*,hrm_employees.first_name,hrm_employees.last_name");
        $builder3->join('hrm_employees','hrm_departments.department_head = hrm_employees.employee_id','left');

        if(!session('isAdmin')){
            $builder3->where('hrm_departments.CreateBy',session('id'));
        }

        $totalRecords = $builder3->countAllResults(false);

        if($searchValue != ''){
           $builder3->where($searchQuery);
        }
        $totalRecordwithFilter = $builder3->countAllResults(false);

        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        $data = array();

        $i = 1;
        
        foreach($records as $record ){

            $button = '';

            $button .= (!$this->hasDepartmentsUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.get_phrases(['update']).'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .= (!$this->hasDepartmentsDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.get_phrases(['delete']).'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';


            $data[] = array( 
                'sl'               => $i,
                'name'             => $record['name'],
                'first_name'       => $record['first_name']?$record['first_name']:'',
                'last_name'        => $record['last_name']?$record['last_name']:'',
                'CreateDate'       => date("Y-m-d",strtotime($record['CreateDate'])),
                'button'           => $button
            );

            $i++;
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


    /*--------------------------
    | getEmployeeTypesList
    *--------------------------*/

    public function bdtaskt1m1_02_getEmployeeTypesList($postData=null){

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

            $searchQuery = " (type like '%".$searchValue."%') ";
        }

        ## Fetch records
        $builder3 = $this->db->table('hrm_employee_types');
        $builder3->select("*");

        if(!session('isAdmin')){
            $builder3->where('CreateBy',session('id'));
        }

        $totalRecords = $builder3->countAllResults(false);

        if($searchValue != ''){
           $builder3->where($searchQuery);
        }
        $totalRecordwithFilter = $builder3->countAllResults(false);

        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        $data = array();

        $i = 1;
        
        foreach($records as $record ){

            $button = '';

            if((int)$record['id'] > 5){
                $button .= (!$this->hasEmpTypeUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.get_phrases(['update']).'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
                $button .= (!$this->hasEmpTypeDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.get_phrases(['delete']).'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';
            }


            $data[] = array( 
                'sl'               => $i,
                'type'             => $record['type'],
                'CreateDate'       => date("Y-m-d",strtotime($record['CreateDate'])),
                'button'           => $button
            );

            $i++;
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

    /*--------------------------
    | getEmployeeTypesList
    *--------------------------*/

    public function bdtaskt1m1_03_getEmployeeList($postData=null){

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

            $searchQuery  = " (hrm_employees.employee_id like '%".$searchValue."%' or hrm_employees.first_name like '%".$searchValue."%' or hrm_employees.last_name like'%".$searchValue."%' or hrm_employees.email like'%".$searchValue."%' or hrm_employees.country like'%".$searchValue."%' or hrm_employees.city like'%".$searchValue."%' or hrm_employees.nid_no like'%".$searchValue."%' or hrm_departments.name like'%".$searchValue."%' or hrm_employee_types.type like'%".$searchValue."%') ";
        }

        ## Fetch records
        $builder3 = $this->db->table('hrm_employees');
        $builder3->select("hrm_employees.*,hrm_departments.name as dept_name,hrm_employee_types.type as emp_type");
        $builder3->join('hrm_departments','hrm_employees.department = hrm_departments.id','left');
        $builder3->join('hrm_employee_types','hrm_employees.employee_type = hrm_employee_types.id','left');

        if(!session('isAdmin')){
            $builder3->where('hrm_employees.CreateBy',session('id'));
        }

        $totalRecords = $builder3->countAllResults(false);

        if($searchValue != ''){
           $builder3->where($searchQuery);
        }
        $totalRecordwithFilter = $builder3->countAllResults(false);

        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        $data = array();

        $i = 1;
        
        foreach($records as $record ){

            $button = '';
            $emp_name = $record['first_name'].' '.$record['last_name'];
            $delete_alert = "'".get_phrases(['are','you','sure'])."'";

            $read_url = base_url('human_resources/employees/employee_profile/'.$record['id']);
            $print_url = base_url('human_resources/employees/employee_details_print/'.$record['id']);
            $edit_url = base_url('human_resources/employees/update_employee/'.$record['id']);
            $delete_url = base_url('human_resources/employees/delete_employee/'.$record['id']);

            $button .= (!$this->hasReadAccess)?'':'<a href="'.$read_url.'" class="btn btn-info-soft btnC actionRead mr-2 custool" title="'.get_phrases(['profile']).'" data-id="'.$record['id'].'"><i class="far fa-user"></i></a>';
            $button .= (!$this->hasPrintAccess)?'':'<a href="javascript:void(0);" class="btn btn-violet-soft btnC actionPrintView mr-2 custool" title="'.get_phrases(['print']).'" data-id="'.$record['id'].'"><i class="hvr-buzz-out fas fa-print"></i></a>';
            $button .= (!$this->hasUpdateAccess)?'':'<a href="'.$edit_url.'" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.get_phrases(['update']).'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .= (!$this->hasDeleteAccess)?'':'<a href="'.$delete_url.'" class="btn btn-danger-soft btnC actionDelete custool" title="'.get_phrases(['delete']).'" data-id="'.$record['id'].'" onclick="return confirm('.$delete_alert.')"><i class="far fa-trash-alt"></i></a>';


            $data[] = array( 
                'sl'               => $i,
                'first_name'       => $record['first_name'],
                'last_name'        => $record['last_name'],
                'employee_id'      => $record['employee_id'],
                'email'            => $record['email'],
                'country'          => $record['country'],
                'city'             => $record['city'],
                'nid_no'           => $record['nid_no'],
                'emp_type'         => $record['emp_type'],
                'dept_name'         => $record['dept_name'],
                'CreateDate'       => date("Y-m-d",strtotime($record['CreateDate'])),
                'button'           => $button
            );

            $i++;
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

    public function departments_list()
    {
            $builder = $this->db->table('hrm_departments');
            $builder->select('*');
            $query=$builder->get();
            $data=$query->getResult();
            
           $list = array('' => 'Select Department');
            if(!empty($data)){
                foreach ($data as $value){
                    $list[$value->id]=$value->name;
                }
            }
            return $list;  
    }

    public function employee_types()
    {
            $builder = $this->db->table('hrm_employee_types');
            $builder->select('*');
            $query=$builder->get();
            $data=$query->getResult();
            
           $list = array('' => 'Select Employee Type');
            if(!empty($data)){
                foreach ($data as $value){
                    $list[$value->id]=$value->type;
                }
            }
            return $list;  
    }

    public function last_employee()
    {
        return $this->db->table('hrm_employees')
         ->select('*')
         // ->orderBy('id','desc')
         ->get()
         ->getLastRow();
    }

    public function save_employee($data=[]){

        $builder = $this->db->table('hrm_employees');
        $add_employee = $builder->insert($data);

        if($add_employee){
           return true;
        }else{
            return false;
        }
    }

    public function get_all_employee_list()
    {
            $builder = $this->db->table('hrm_employees');
            $builder->select('*');
            $query=$builder->get();
            $data=$query->getResult();
            
           $list = array('' => 'Select Employee');
            if(!empty($data)){
                foreach ($data as $value){
                    $list[$value->employee_id]=$value->first_name.' '.$value->last_name;
                }
            }
            return $list;  
    }

    public function designations_list()
    {
            $builder = $this->db->table('hrm_designation');
            $builder->select('*');
            $query=$builder->get();
            $data=$query->getResult();
            
           $list = array('' => 'Select Designation');
            if(!empty($data)){
                foreach ($data as $value){
                    $list[$value->id]=$value->name;
                }
            }
            return $list;  
    }

    public function branch_list()
    {
            $builder = $this->db->table('hrm_branch');
            $builder->select('*');
            $query=$builder->get();
            $data=$query->getResult();
            
           $list = array('' => 'Select Branch');
            if(!empty($data)){
                foreach ($data as $value){
                    $list[$value->id]=$value->name;
                }
            }
            return $list;  
    }

    public function country_list()
    {
            $builder = $this->db->table('hrm_country_tbl');
            $builder->select('*');
            $query=$builder->get();
            $data=$query->getResult();
            
           $list = array('' => 'Select Country');
            if(!empty($data)){
                foreach ($data as $value){
                    $list[$value->country_id]=$value->country_name;
                }
            }
            return $list;  
    }

    public function attachment_types()
    {
            $builder = $this->db->table('hrm_attachment_types');
            $builder->select('*');
            $query=$builder->get();
            $data=$query->getResult();
            
           $list = array('' => 'Select Type');
            if(!empty($data)){
                foreach ($data as $value){
                    $list[$value->id]=$value->name;
                }
            }
            return $list;  
    }

    public function employee_attachments($employee_id)
    {
        $builder = $this->db->table('hrm_employee_attachments');
        $builder->select('*');
        $builder->where('employee_id',$employee_id);
        $query=$builder->get();
        $data=$query->getResult();

        return $data;  
    }

    public function employee_profile_info($employee_id)
    {
        $builder = $this->db->table('hrm_employees emp');
        $builder->select("emp.*,dept.name as dept_name,empt.type as emp_type,desig.name as emp_designation,cntry.country_name as emp_nationality,brch.name as emp_branch,emp_table.first_name as superior_firstname,emp_table.last_name as superior_lastname");
        $builder->where('emp.employee_id',$employee_id);
        $builder->join('hrm_departments dept','emp.department = dept.id','left');
        $builder->join('hrm_employee_types empt','emp.employee_type = empt.id','left');
        $builder->join('hrm_designation desig','emp.designation = desig.id','left');
        $builder->join('hrm_country_tbl cntry','emp.nationality = cntry.country_id','left');
        $builder->join('hrm_branch brch','emp.branch = brch.id','left');
        $builder->join('hrm_employees emp_table','emp.superior = emp_table.employee_id','left');
        $query=$builder->get();
        $data=$query->getRow();

        return $data;  
    }

    /*--------------------------
    | getAllDesignations
    *--------------------------*/

    public function bdtaskt1m1_04_getAllDesignations($postData=null){

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

            $searchQuery = " (name like '%".$searchValue."%') ";
        }

        ## Fetch records
        $builder3 = $this->db->table('hrm_designation');
        $builder3->select("*");

        if(!session('isAdmin')){
            $builder3->where('CreateBy',session('id'));
        }

        $totalRecords = $builder3->countAllResults(false);

        if($searchValue != ''){
           $builder3->where($searchQuery);
        }
        $totalRecordwithFilter = $builder3->countAllResults(false);

        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        $data = array();

        $i = 1;
        
        foreach($records as $record ){

            $button = '';

            if((int)$record['id'] != 4){ // Hrm department

            $button .= (!$this->hasDesignationUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.get_phrases(['update']).'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .= (!$this->hasDesignationDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.get_phrases(['delete']).'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';

            }

            $data[] = array( 
                'sl'               => $i,
                'name'             => $record['name'],
                'CreateDate'       => date("Y-m-d",strtotime($record['CreateDate'])),
                'button'           => $button
            );

            $i++;
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

    /*--------------------------
    | getAllBranches
    *--------------------------*/

    public function bdtaskt1m1_05_getAllBranches($postData=null){

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

            $searchQuery = " (name like '%".$searchValue."%') ";
        }

        ## Fetch records
        $builder3 = $this->db->table('hrm_branch');
        $builder3->select("*");

        if(!session('isAdmin')){
            $builder3->where('CreateBy',session('id'));
        }

        $totalRecords = $builder3->countAllResults(false);

        if($searchValue != ''){
           $builder3->where($searchQuery);
        }
        $totalRecordwithFilter = $builder3->countAllResults(false);

        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        $data = array();

        $i = 1;
        
        foreach($records as $record ){

            $button = '';

            $button .= (!$this->hasBranchUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.get_phrases(['update']).'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .= (!$this->hasBranchDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.get_phrases(['delete']).'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';


            $data[] = array( 
                'sl'               => $i,
                'name'             => $record['name'],
                'CreateDate'       => date("Y-m-d",strtotime($record['CreateDate'])),
                'button'           => $button
            );

            $i++;
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


    /*--------------------------
    | getAllBesEmployees
    *--------------------------*/

    public function bdtaskt1m1_06_getAllBesEmployees($postData=null){

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

            $searchQuery = " (hrm_employees.first_name like '%".$searchValue."%' or hrm_employees.last_name like '%".$searchValue."%') ";
        }

        ## Fetch records
        $builder3 = $this->db->table('hrm_best_employees');
        $builder3->select("hrm_best_employees.*,hrm_employees.first_name,hrm_employees.last_name");
        $builder3->join('hrm_employees','hrm_best_employees.employee_id = hrm_employees.employee_id','left');

        if(!session('isAdmin')){
            $builder3->where('hrm_best_employees.CreateBy',session('id'));
        }

        $totalRecords = $builder3->countAllResults(false);

        if($searchValue != ''){
           $builder3->where($searchQuery);
        }
        $totalRecordwithFilter = $builder3->countAllResults(false);

        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        $data = array();

        $i = 1;
        
        foreach($records as $record ){

            $button = '';

            $button .= (!$this->hasBestEmployeeUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.get_phrases(['update']).'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .= (!$this->hasBestEmployeeDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.get_phrases(['delete']).'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';


            $data[] = array( 
                'sl'               => $i,
                'first_name'       => $record['first_name'],
                'last_name'        => $record['last_name'],
                'date'             => date('F Y', strtotime($record['date'])),
                'CreateDate'       => date("Y-m-d",strtotime($record['CreateDate'])),
                'button'           => $button
            );

            $i++;
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


}
?>