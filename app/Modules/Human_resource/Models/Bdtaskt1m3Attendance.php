<?php namespace App\Modules\Human_resource\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;
class Bdtaskt1m3Attendance extends Model
{
    protected $table = 'employees';

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();
        
        if(session('defaultLang')=='english'){
            $this->langColumn = 'nameE';
        }else{
            $this->langColumn = 'nameA';
        }

        $this->hasAttendanceSetupUpdateAccess = $this->permission->method('attendance_setup', 'update')->access();
        $this->hasAttendanceSetupDeleteAccess = $this->permission->method('attendance_setup', 'delete')->access();

        $this->hasAttendanceSetupUpdateAccess = $this->permission->method('attendance_setup', 'update')->access();
        $this->hasAttendanceSetupDeleteAccess = $this->permission->method('attendance_setup', 'delete')->access();

        $this->hasAttendanceMapUpdateAccess = $this->permission->method('attendance_machine_map', 'update')->access();
        $this->hasAttendanceMapDeleteAccess = $this->permission->method('attendance_machine_map', 'delete')->access();

    }

    /*--------------------------
    | Get all employees
    *--------------------------*/
    public function bdtaskt1m3_01_getAllEmployees(){

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
    | Get individual employee
    *--------------------------*/
    public function bdtaskt1m3_02_getEmployee($id){

        $employee = $this->db->table('hrm_employees')->select("employee_id,first_name,last_name,email")
                ->where("employee_id ", $id)
                ->get()->getRow();

        return $employee;

    }

    public function bdtaskt1m3_03_getSetting(){
        $builder = $this->db->table('setting')
                             ->get()
                             ->getRow(); 
        return $builder;
    }

    // attendance log datebetween search
    // public function bdtaskt1m3_04_att_log_datebetween($id,$from_date,$to_date){

    //     $att = "SELECT *, DATE(time) as mydate FROM `hrm_attendance_history` WHERE `uid`=$id AND DATE(time) BETWEEN '" . $from_date . "' AND  '" . $to_date . "' GROUP BY mydate ORDER BY time desc";
    //     $query = $this->db->query($att)->getResult();

    //     $att_in = [];
    //     $i=1;

    //     foreach ($query as $attendance) {

    //         if($attendance->branch_id == session('branchId')){

    //             $att_in[$i] = $this->db->table("hrm_attendance_history a")
    //                     ->select('a.time,MIN(a.time) as intime,MAX(a.time) as outtime,a.uid')
    //                     ->like('a.time',date( "Y-m-d", strtotime($attendance->mydate)),'after')
    //                     ->where('a.uid',$attendance->uid)
    //                     ->orderBy('a.time','DESC')
    //                     ->get()
    //                     ->getResult();
    //             $i++;
    //         } 
    //     }

    //     return $att_in;
        
    // }

    public function bdtaskt1m3_04_att_log_datebetween($id,$from_date,$to_date){

        $att = "SELECT *, DATE(atten_date) as mydate FROM `hrm_attendance_history` WHERE `uid`=$id AND DATE(atten_date) BETWEEN '" . $from_date . "' AND  '" . $to_date . "' GROUP BY mydate ORDER BY time desc";
        $query = $this->db->query($att)->getResult();

        $att_in = [];
        $i=1;

        foreach ($query as $attendance) {

            $att_in[$i] = $this->db->table("hrm_attendance_history a")
                    ->select('a.time,MIN(a.time) as intime,MAX(a.time) as outtime,a.uid,a.atten_date')
                    // ->like('a.time',date( "Y-m-d", strtotime($attendance->mydate)),'after')
                    ->where('a.atten_date',date("Y-m-d", strtotime($attendance->mydate)))
                    ->where('a.uid',$attendance->uid)
                    ->orderBy('a.time','DESC')
                    ->get()
                    ->getResult();
            $i++;
        }

        return $att_in;
        
    }

    public function bdtaskt1m3_05_att_emp_name($uid){

        $result = $this->db->table("hrm_employees")
                    ->select("first_name,last_name,email,employee_id")
                    ->where('employee_id',$uid)
                    ->get()
                    ->getRow();

        return $result;
    }


    public function bdtaskt1m3_06_attendanceSetupList($postData=null){

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
           //$searchQuery = " (branch.nameE like '%".$searchValue."%' OR branch.nameA like '%".$searchValue."%') ";
        }

        ## Fetch records
        $builder3 = $this->db->table('hrm_attendance_setup');
        $builder3->select("*");

        if(!session('isAdmin')){
            $builder3->where('hrm_attendance_setup.CreateBy',session('id'));
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

        // echo json_encode($records);exit;

        $i = 1;
        
        foreach($records as $record ){

            $button = '';

            $button .= (!$this->hasAttendanceSetupUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.get_phrases(['update']).'" data-id="'.$record['attendance_setup_id'].'"><i class="far fa-edit"></i></a>';
            //$button .= (!$this->hasAttendanceSetupDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.get_phrases(['delete']).'" data-id="'.$record['attendance_setup_id'].'"><i class="far fa-trash-alt"></i></a>';


            $data[] = array( 
                'sl'                    => $i,
                'attendance_setup_id'  => $record['attendance_setup_id'],
                'in_time'               => $record['in_time'],
                'out_time'              => $record['out_time'],
                'CreateDate'            => $record['CreateDate'],
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


    /*
    | getEmployees for attendance machine map for hrm_atten_machine_map table...
    */
    public function bdtaskt1m3_07_employeesForAttenMap()
    {

        $atten_machine_map_employees = $this->db->table("hrm_atten_machine_map")
                                ->select('emp_id')
                                ->where('branch_id',session('branchId'))
                                ->get()
                                ->getResultArray();

        $arr_atten_machine_map_employees = array_column($atten_machine_map_employees, 'emp_id');

        $builder3 = $this->db->table("employees");
        $builder3->select("employees.emp_id, employees.status ,employees.$this->langColumn as emp_name");

        $builder3->where('employees.status',1);

        if(count($arr_atten_machine_map_employees) > 0){
            $builder3->whereNotIn('employees.emp_id',$arr_atten_machine_map_employees);
        }
        if(session('branchId') > 0){

            $builder3->where("FIND_IN_SET('".session('branchId')."', employees.branch_id)");
            // $builder3->where('employees.branch_id',session('branchId'));
        }

        $employees = $builder3->get()->getResultArray();

        return $employees;

    }


    public function bdtaskt1m3_08_add_atten_machine_map_list($postData=null){

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
           $searchQuery = " (hrm_atten_machine_map.zkt_machine_id like '%".$searchValue."%' OR employees.nameE like '%".$searchValue."%' OR employees.nameE like '%".$searchValue."%' OR branch.nameE like '%".$searchValue."%' OR branch.nameA like '%".$searchValue."%') ";
        }

        ## Fetch records
        $builder3 = $this->db->table('hrm_atten_machine_map');
        $builder3->select("hrm_atten_machine_map.*, employees.$this->langColumn as emp_name, branch.$this->langColumn as branch_name");
        $builder3->join("employees", "employees.emp_id = hrm_atten_machine_map.emp_id ", "left");
        $builder3->join("branch", "branch.id = hrm_atten_machine_map.branch_id ", "left");

        if(session('branchId') > 0 ){
            
            $builder3->where("hrm_atten_machine_map.branch_id", session('branchId'));
        }

        if(!session('isAdmin')){
            $builder3->where('hrm_atten_machine_map.CreateBy',session('id'));
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

        // echo json_encode($records);exit;

        $i = 1;
        
        foreach($records as $record ){

            $button = '';

            $button .= (!$this->hasAttendanceMapUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.get_phrases(['update']).'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .= (!$this->hasAttendanceMapDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.get_phrases(['delete']).'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';


            $data[] = array( 
                'sl'                    => $i,
                'id '                   => $record['id'],
                'zkt_machine_id'        => $record['zkt_machine_id'],
                'emp_name'              => $record['emp_name'],
                'branch_name'           => $record['branch_name'],
                'CreateDate'            => $record['CreateDate'],
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
    | getAttendances
    *--------------------------*/
    public function bdtaskt1m3_09_getAttendances($data){

        $builder = $this->db->table('hrm_attendance_history');
        $builder->select('*');
        $builder->where("uid ", $data['uid']);
        $builder->where("atten_date", $data['atten_date']);
        $builder->orderBy("time ",'asc');
        $query=$builder->get();
        $data=$query->getResult();

        return $data;

    }

    /*--------------------------
    | getAttendances
    *--------------------------*/
    public function bdtaskt1m3_10_previousAttendnace($data){

        $punch_time_y = date("Y", strtotime($data['punch_time_date']));
        $punch_time_m = date("m", strtotime($data['punch_time_date']));
        $punch_time_d = date("d", strtotime($data['punch_time_date']));

        $builder = $this->db->table('hrm_attendance_history');
        $builder->select('*');
        $builder->where("uid ", $data['uid']);
        $builder->where("YEAR(time)=".$punch_time_y,NULL, FALSE);
        $builder->where("MONTH(time)=".$punch_time_m,NULL, FALSE);
        $builder->where("DAY(time)=".$punch_time_d,NULL, FALSE);
        $builder->orderBy("time ",'asc');
        $query=$builder->get();
        $respodata=$query->getLastRow();

        return $respodata;

    }

    /*--------------------------
    | getAttendances
    *--------------------------*/
    public function bdtaskt1m3_11_empNextDayAttendnace($data){

        $next_day_date = strtotime("1 day", strtotime($data['atten_date']));
        $next_attendance_date = date("Y-m-d", $next_day_date);

        $builder = $this->db->table('hrm_attendance_history');
        $builder->select('*');
        $builder->where("uid ", $data['uid']);
        $builder->where("atten_date",$next_attendance_date);
        $builder->orderBy("time ",'desc');
        $query=$builder->get();
        $respodata=$query->getLastRow();

        return $respodata;

    }

    /*--------------------------
    | check_govt_holiday
    *--------------------------*/
    public function bdtaskt1m3_12_check_govt_holiday($data){

        $punch_time_date = date("Y-m-d", strtotime($data['atten_date']));

        // return $data['atten_date'];

        $builder = $this->db->table('hrm_holidays');
        $builder->select('*');
        $builder->where("start_date <=",$data['atten_date']);
        $builder->where("end_date >=",$data['atten_date']);
        $query=$builder->get();
        $respodata=$query->getRow();

        return $respodata;

    }

    /*--------------------------
    | emp_total_cpl_leave
    *--------------------------*/
    public function bdtaskt1m3_13_emp_total_cpl_leave($emp_id,$year){

        $builder = $this->db->table('hrm_cpl_leave');
        $builder->select('*');
        $builder->where("employee_id",$emp_id);
        $builder->where("year",$year);
        $query=$builder->get();
        $respodata=$query->getRow();

        return $respodata;

    }

    /*--------------------------
    | emp_total_cpl_leave
    *--------------------------*/
    public function bdtaskt1m3_14_cpl_leave_already_inserted($emp_id,$data){

        $builder = $this->db->table('hrm_cpl_leave_history');
        $builder->where("employee_id",$emp_id);
        $builder->where("date",$data['atten_date']);
        $query=$builder->get();
        $respodata=$query->getRow();

        return $respodata;

    }

    /*--------------------------
    | check_govt_holiday_for_earned_leave
    *--------------------------*/
    public function bdtaskt1m3_15_check_govt_holiday_for_earned_leave($date){

        $builder = $this->db->table('hrm_holidays');
        $builder->select('*');
        $builder->where("start_date <=",$date);
        $builder->where("end_date >=",$date);
        $query=$builder->get();
        $respodata=$query->getRow();

        if($respodata){

            return true;
        }
        return false;

    }

    /*--------------------------
    | check_govt_holiday_for_earned_leave
    *--------------------------*/
    public function bdtaskt1m3_16_check_attendance_by_date($date,$employee_id){

        $builder = $this->db->table('hrm_attendance_history');
        $builder->select('*');
        $builder->where("uid",$employee_id);
        $builder->where("atten_date",$date);
        $query=$builder->get();
        $respodata=$query->getRow();

        if($respodata){

            return true;
        }
        return false;

    }

    /*--------------------------
    | check_govt_holiday_for_leave_application
    *--------------------------*/
    public function bdtaskt1m3_17_check_govt_holiday_for_leave_application($date){

        $builder = $this->db->table('hrm_holidays');
        $builder->select('*');
        $builder->where("start_date <=",$date);
        $builder->where("end_date >=",$date);
        $query=$builder->get();
        $respodata=$query->getRow();

        if($respodata){

            return true;
        }
        return false;

    }


}
?>