<?php namespace App\Modules\Human_resource\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;
class Bdtaskt1m6LeaveManagement extends Model
{
    protected $emp_table = 'employees';

    public function __construct()
    {
        $this->db = db_connect();
        // if(session('defaultLang')=='english'){
        //     $this->langColumn = 'nameE';
        // }else{
        //     $this->langColumn = 'nameA';
        // }

        $this->permission = new Permission();

        $this->hasLeaveTypeReadAccess   = $this->permission->method('leave_type', 'read')->access();
        $this->hasLeaveTypeUpdateAccess = $this->permission->method('leave_type', 'update')->access();
        $this->hasLeaveTypeDeleteAccess = $this->permission->method('leave_type', 'delete')->access();

        $this->hasCplLeaveReadAccess   = $this->permission->method('cpl_leave', 'read')->access();
        $this->hasCplLeaveUpdateAccess = $this->permission->method('cpl_leave', 'update')->access();
        $this->hasCplLeaveDeleteAccess = $this->permission->method('cpl_leave', 'delete')->access();

        $this->hasEarnedLeaveReadAccess   = $this->permission->method('earned_leave', 'read')->access();
        $this->hasEarnedLeaveUpdateAccess = $this->permission->method('earned_leave', 'update')->access();
        $this->hasEarnedLeaveDeleteAccess = $this->permission->method('earned_leave', 'delete')->access();

        $this->hasLeaveApplicationReadAccess   = $this->permission->method('leave_application', 'read')->access();
        $this->hasLeaveApplicationUpdateAccess = $this->permission->method('leave_application', 'update')->access();
        $this->hasLeaveApplicationDeleteAccess = $this->permission->method('leave_application', 'delete')->access();
    }


    /*--------------------------
    | leave_types_list
    *--------------------------*/

    public function bdtaskt1m4_01_leave_types_list($postData=null){

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

            $searchQuery = " (leave_type like '%".$searchValue."%') ";
        }

        ## Fetch records
        $builder3 = $this->db->table('hrm_leave_types');
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

            $button .= (!$this->hasLeaveTypeUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.get_phrases(['update']).'" data-id="'.$record['leave_type_id'].'"><i class="far fa-edit"></i></a>';
            $button .= (!$this->hasLeaveTypeDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.get_phrases(['delete']).'" data-id="'.$record['leave_type_id'].'"><i class="far fa-trash-alt"></i></a>';


            $data[] = array( 
                'sl'             => $i,
                'leave_type'     => $record['leave_type'],
                'leave_days'     => $record['leave_days'],
                'CreateDate'     => date("Y-m-d",strtotime($record['CreateDate'])),
                'button'         => $button
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
    | cpl_leave_list
    *--------------------------*/

    public function bdtaskt1m4_02_cpl_leave_list($postData=null){

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
        $builder3 = $this->db->table('hrm_cpl_leave');
        $builder3->select("hrm_cpl_leave.*,hrm_employees.first_name,hrm_employees.last_name");
        $builder3->join('hrm_employees','hrm_cpl_leave.employee_id = hrm_employees.employee_id','left');

        if(!session('isAdmin')){
            $builder3->where('hrm_cpl_leave.CreateBy',session('id'));
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

            $button .= (!$this->hasCplLeaveUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.get_phrases(['update']).'" data-id="'.$record['id'].'"><i class="far fa-eye"></i></a>';
            //$button .= (!$this->hasCplLeaveDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.get_phrases(['delete']).'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';


            $data[] = array( 
                'sl'             => $i,
                'first_name'     => $record['first_name'],
                'last_name'      => $record['last_name'],
                'total_leave'    => $record['total_leave'],
                'year'           => $record['year'],
                'button'         => $button
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
    | earned_leave_list
    *--------------------------*/

    public function bdtaskt1m4_03_earned_leave_list($postData=null){

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
        $builder3 = $this->db->table('hrm_earned_leave');
        $builder3->select("hrm_earned_leave.*,hrm_employees.first_name,hrm_employees.last_name");
        $builder3->join('hrm_employees','hrm_earned_leave.employee_id = hrm_employees.employee_id','left');

        if(!session('isAdmin')){
            $builder3->where('hrm_earned_leave.CreateBy',session('id'));
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

            $button .= (!$this->hasEarnedLeaveReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionView mr-2 custool" title="'.get_phrases(['update']).'" data-id="'.$record['id'].'"><i class="far fa-eye"></i></a>';
            $button .= (!$this->hasEarnedLeaveUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionGenerateEdit mr-2 custool" title="'.get_phrases(['update']).'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            //$button .= (!$this->hasEarnedLeaveDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.get_phrases(['delete']).'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>'; 


            $data[] = array( 
                'sl'             => $i,
                'first_name'     => $record['first_name'],
                'last_name'      => $record['last_name'],
                'total_leave'    => $record['total_leave'],
                'start_date'     => $record['start_date'],
                'end_date'       => $record['end_date'],
                'button'         => $button
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
    | leave_application_list
    *--------------------------*/

    public function bdtaskt1m4_04_leave_application_list($postData=null){

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
        $builder3 = $this->db->table('hrm_leave_application');
        $builder3->select("hrm_leave_application.*,hrm_employees.first_name,hrm_employees.last_name,hrm_leave_types.leave_type as leave_type_name");
        $builder3->join('hrm_employees','hrm_leave_application.employee_id = hrm_employees.employee_id','left');
        $builder3->join('hrm_leave_types','hrm_leave_application.leave_type = hrm_leave_types.leave_type_id','left');

        if(!session('isAdmin')){
            $builder3->where('hrm_leave_application.CreateBy',session('id'));
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

        // print_r($records);

        $i = 1;
        
        foreach($records as $record ){

            $button = '';
            $status = '';
            if($record['superior_approval']==0){
                $status .= '<span class="badge badge-warning text-white">'.get_phrases(['superior','pending']).'</span>';

            }elseif ($record['superior_approval']==2) {
                $status .= '<span class="badge badge-danger text-white">'.get_phrases(['superior','rejected']).'</span>';

            }elseif ($record['superior_approval']==1) {
                $status .= '<span class="badge badge-success text-white">'.get_phrases(['superior','approved']).'</span>';
            }

            if($record['status']==0){
                $status .= '<span class="badge badge-warning text-white">'.get_phrases(['pending']).'</span>';

            }elseif ($record['status']==2) {
                $status .= '<span class="badge badge-danger text-white">'.get_phrases(['rejected']).'</span>';

            }elseif ($record['status']==1) {
                $status .= '<span class="badge badge-success text-white">'.get_phrases(['approved']).'</span>';
            }

            $button .= (!$this->hasLeaveApplicationUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.get_phrases(['update']).'" data-id="'.$record['leave_appl_id'].'"><i class="far fa-edit"></i></a>';
            $button .= (!$this->hasLeaveApplicationDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.get_phrases(['delete']).'" data-id="'.$record['leave_appl_id'].'"><i class="far fa-trash-alt"></i></a>'; 


            $data[] = array( 
                'sl'                    => $i,
                'first_name'            => $record['first_name'],
                'last_name'             => $record['last_name'],
                'leave_type_name'       => $record['leave_type_name'],
                'apply_strt_date'       => $record['apply_strt_date'],
                'apply_end_date'        => $record['apply_end_date'],
                'leave_aprv_strt_date'  => $record['leave_aprv_strt_date'],
                'leave_aprv_end_date'   => $record['leave_aprv_end_date'],
                'apply_day'             => $record['apply_day'],
                'num_aprv_day'          => $record['num_aprv_day'],
                'status'                => $status,
                'button'                => $button
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
    | emp_leave_application_list
    *--------------------------*/

    public function bdtaskt1m4_05_emp_leave_application_list($postData=null){

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
        $builder3 = $this->db->table('hrm_leave_application');
        $builder3->select("hrm_leave_application.*,hrm_employees.first_name,hrm_employees.last_name,hrm_leave_types.leave_type as leave_type_name");
        $builder3->join('hrm_employees','hrm_leave_application.employee_id = hrm_employees.employee_id','left');
        $builder3->join('hrm_leave_types','hrm_leave_application.leave_type = hrm_leave_types.leave_type_id','left');

        $builder3->where('hrm_leave_application.employee_id',session('id'));


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

        // print_r($records);

        $i = 1;
        
        foreach($records as $record ){

            $button = '';
            $status = '';

            if($record['superior_approval']==0){
                $status .= '<span class="badge badge-warning text-white">'.get_phrases(['superior','pending']).'</span>';

            }elseif ($record['superior_approval']==2) {
                $status .= '<span class="badge badge-danger text-white">'.get_phrases(['superior','rejected']).'</span>';

            }elseif ($record['superior_approval']==1) {
                $status .= '<span class="badge badge-success text-white">'.get_phrases(['superior','approved']).'</span>';
            }

            if($record['status']==0){
                $status .= '<span class="badge badge-warning text-white">'.get_phrases(['pending']).'</span>';

            }elseif ($record['status']==2) {
                $status .= '<span class="badge badge-danger text-white">'.get_phrases(['rejected']).'</span>';

            }elseif ($record['status']==1) {
                $status .= '<span class="badge badge-success text-white">'.get_phrases(['approved']).'</span>';
            }

            $button .= (!$this->hasLeaveApplicationUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.get_phrases(['update']).'" data-id="'.$record['leave_appl_id'].'"><i class="far fa-edit"></i></a>';
            $button .= (!$this->hasLeaveApplicationDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.get_phrases(['delete']).'" data-id="'.$record['leave_appl_id'].'"><i class="far fa-trash-alt"></i></a>'; 


            $data[] = array( 
                'sl'                    => $i,
                'first_name'            => $record['first_name'],
                'last_name'             => $record['last_name'],
                'leave_type_name'       => $record['leave_type_name'],
                'apply_strt_date'       => $record['apply_strt_date'],
                'apply_end_date'        => $record['apply_end_date'],
                'leave_aprv_strt_date'  => $record['leave_aprv_strt_date'],
                'leave_aprv_end_date'   => $record['leave_aprv_end_date'],
                'apply_day'             => $record['apply_day'],
                'num_aprv_day'          => $record['num_aprv_day'],
                'status'                => $status,
                'button'                => $button
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
    | superior_leave_approval_list
    *--------------------------*/

    public function bdtaskt1m4_06_superior_leave_approval_list($postData=null){

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
        $builder3 = $this->db->table('hrm_leave_application');
        $builder3->select("hrm_leave_application.*,hrm_employees.first_name,hrm_employees.last_name,hrm_leave_types.leave_type as leave_type_name");
        $builder3->join('hrm_employees','hrm_leave_application.employee_id = hrm_employees.employee_id','left');
        $builder3->join('hrm_leave_types','hrm_leave_application.leave_type = hrm_leave_types.leave_type_id','left');

        $builder3->where('hrm_leave_application.superior_id',session('id'));


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

        // print_r($records);

        $i = 1;
        
        foreach($records as $record ){

            $button = '';
            $status = '';
            if($record['superior_approval']==0){
                $status .= '<span class="badge badge-warning text-white">'.get_phrases(['superior','pending']).'</span>';

            }elseif ($record['superior_approval']==2) {
                $status .= '<span class="badge badge-danger text-white">'.get_phrases(['superior','rejected']).'</span>';

            }elseif ($record['superior_approval']==1) {
                $status .= '<span class="badge badge-success text-white">'.get_phrases(['superior','approved']).'</span>';
            }

            if($record['status']==0){
                $status .= '<span class="badge badge-warning text-white">'.get_phrases(['pending']).'</span>';

            }elseif ($record['status']==2) {
                $status .= '<span class="badge badge-danger text-white">'.get_phrases(['rejected']).'</span>';

            }elseif ($record['status']==1) {
                $status .= '<span class="badge badge-success text-white">'.get_phrases(['approved']).'</span>';
            }

            $button .= (!$this->hasLeaveApplicationUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.get_phrases(['update']).'" data-id="'.$record['leave_appl_id'].'"><i class="far fa-edit"></i></a>';
            //$button .= (!$this->hasLeaveApplicationDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.get_phrases(['delete']).'" data-id="'.$record['leave_appl_id'].'"><i class="far fa-trash-alt"></i></a>'; 


            $data[] = array( 
                'sl'                    => $i,
                'first_name'            => $record['first_name'],
                'last_name'             => $record['last_name'],
                'leave_type_name'       => $record['leave_type_name'],
                'apply_strt_date'       => $record['apply_strt_date'],
                'apply_end_date'        => $record['apply_end_date'],
                'leave_aprv_strt_date'  => $record['leave_aprv_strt_date'],
                'leave_aprv_end_date'   => $record['leave_aprv_end_date'],
                'apply_day'             => $record['apply_day'],
                'num_aprv_day'          => $record['num_aprv_day'],
                'status'                => $status,
                'button'                => $button
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
    | Get all employees
    *--------------------------*/
    public function bdtaskt1m4_04_getAllEmployees(){

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

    /*--------------------------
    | getAttendances
    *--------------------------*/
    public function bdtaskt1m4_05_existingEarnedLeaveForTheYear($data){

        $end_date_y = date("Y", strtotime($data['end_date']));

        $builder = $this->db->table('hrm_earned_leave');
        $builder->select('*');
        $builder->where("employee_id ", $data['employee_id']);
        $builder->where("YEAR(end_date)=".$end_date_y,NULL, FALSE);
        $query=$builder->get();
        $respodata=$query->getRow();

        return $respodata;

    }

    /*--------------------------
    | Get all employees
    *--------------------------*/
    public function bdtaskt1m4_06_getAllLeaveTypes(){

        $builder = $this->db->table('hrm_leave_types');
        $builder->select('*');
        $query=$builder->get();
        $data=$query->getResult();

        $list = array('' => 'Select leave type');
        if(!empty($data)){
            foreach ($data as $value){

                $list[$value->leave_type_id]=$value->leave_type;
            }
        }
        return $list;

    }

    /*--------------------------
    | get_leave_from_leave_types
    *--------------------------*/
    public function get_leave_from_leave_types(){

        $builder = $this->db->table('hrm_leave_types');
        $builder->select('*');
        $query=$builder->get();
        $respodata=$query->getResult();

        $total_leave = 0;

        foreach ($respodata as $key => $value) {
            $total_leave =  $total_leave + (int)$value->leave_days;
        }

        return $total_leave;

    }

    /*--------------------------
    | get_leave_from_cpl_leave
    *--------------------------*/
    public function get_leave_from_cpl_leave($employee_id,$date){

        $year = date('Y',strtotime($date));

        $builder = $this->db->table('hrm_cpl_leave');
        $builder->select('*');
        $builder->where("employee_id",$employee_id);
        $builder->where("year",$year);
        $query=$builder->get();
        $respodata=$query->getRow();

        if($respodata){
            return $respodata->total_leave;
        }

        return 0;

    }

    /*--------------------------
    | get_leave_from_earned_leave
    *--------------------------*/
    public function get_leave_from_earned_leave($employee_id,$date){

        $year = date('Y',strtotime($date));

        $builder = $this->db->table('hrm_earned_leave');
        $builder->select('*');
        $builder->where("employee_id",$employee_id);
        $builder->where("YEAR(start_date)=".$year,NULL, FALSE);
        $query=$builder->get();
        $respodata=$query->getRow();

       if($respodata){
            return $respodata->total_leave;
        }

        return 0;

    }

    /*--------------------------
    | get_already_used_leave
    *--------------------------*/
    public function get_already_used_leave($employee_id,$date){

        $year = date('Y',strtotime($date));

        $builder = $this->db->table('hrm_leave_application');
        $builder->select('*');
        $builder->where("employee_id",$employee_id);
        $builder->where("status",1);
        $builder->where("YEAR(leave_aprv_strt_date)=".$year,NULL, FALSE);
        $query=$builder->get();
        $respodata=$query->getResult();

        $total_leave_used = 0;

        foreach ($respodata as $key => $value) {
            $total_leave_used =  $total_leave_used + (int)$value->num_aprv_day;
        }

        return $total_leave_used;

    }
    

}
?>