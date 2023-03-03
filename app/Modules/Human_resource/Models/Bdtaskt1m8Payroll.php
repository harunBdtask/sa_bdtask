<?php namespace App\Modules\Human_resource\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;
class Bdtaskt1m8Payroll extends Model
{
    protected $table = 'employees';

    public function __construct()
    {
        $this->db = db_connect();

        $this->permission = new Permission();
        $this->hasReadBenefit = $this->permission->method('benefit', 'read')->access();
        $this->hasBenefitUpdateAccess = $this->permission->method('benefit', 'update')->access();
        $this->hasBenefitDeleteAccess = $this->permission->method('benefit', 'delete')->access();

        $this->hasSalarySetupListReadAccess = $this->permission->method('salary_setup_list', 'read')->access();
        $this->hasSalarySetupListUpdateAccess = $this->permission->method('salary_setup_list', 'update')->access();
        $this->hasSalarySetupListDeleteAccess = $this->permission->method('salary_setup_list', 'delete')->access();

        $this->hasOverTimeReadAccess = $this->permission->method('over_time', 'read')->access();
        $this->hasOverTimeUpdateAccess = $this->permission->method('over_time', 'update')->access();
        $this->hasOverTimeDeleteAccess = $this->permission->method('over_time', 'delete')->access();

        $setting = $this->db->table('setting')->select('*')->get()->getRow();

        $timeZone = (!empty($setting->time_zone)?$setting->time_zone:'Asia/Dhaka');
        @date_default_timezone_set($timeZone);
    }


    public function benefit_list(){

        $list = $this->db->table('hrm_salary_benefit')
                 ->get()
                 ->getResultArray(); 

        return $list;


    }

    public function check_exist($data=[]){
         $exitstdata = $this->db->table('hrm_salary_benefit')
                     ->where('benefit_name', $data['benefit_name'])
                     ->countAllResults(); 
                    
       if($exitstdata > 0){
        return true;

       }else{

        return false;
       }              
    }

    public function save_salary_benefit($data=[]){
        $builder = $this->db->table('hrm_salary_benefit');
         $add_salary_benefit = $builder->insert($data);
       
        if($add_salary_benefit){
            return $this->db->insertId();
        }else{
            return false;
        }
    }

    public function update_salary_benefit($data=[]){
         $query = $this->db->table('hrm_salary_benefit');   
         $query->where('id', $data['id']);
         return $query->update($data);  

    }

    public function singledata($id){
        $builder = $this->db->table('hrm_salary_benefit')
                 ->where('id', $id)
                 ->get()
                 ->getRow(); 

        return $builder;


    }

    public function employee_list()
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

    public function delete_salary_benefit($id){
            $builder = $this->db->table('hrm_salary_benefit');
            $builder->where('id', $id);
     return $builder->delete();

    }


    public function getsalarysetupList($postData=null){

        $response        = array();
        $draw            = $postData['draw'];
        $start           = $postData['start'];
        $rowperpage      = $postData['length']; // Rows display per page
        $columnIndex     = $postData['order'][0]['column']; // Column index
        $columnName      = $postData['columns'][$columnIndex]['data']; // 
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue     = $postData['search']['value']; // Search value

        $searchQuery     = "";
        if($searchValue != ''){
            $searchQuery  = " (c.first_name like '%".$searchValue."%' or c.last_name like'%".$searchValue."%' or a.create_date like'%".$searchValue."%') ";
        }

        ## Total number of records without filtering
        $builder1 = $this->db->table('hrm_employee_salary_setup a');
        $builder1->select("count(*) as allcount");
        $builder1->join('hrm_employees c','c.employee_id = a.employee_id','left');

        if($searchValue != ''){
           $builder1->where($searchQuery);
        }
        $builder1->groupBy('a.employee_id');    
        $query1       = $builder1->countAllResults();
        $totalRecords = $query1;


        ## Total number of record with filtering
        $builder2 = $this->db->table('hrm_employee_salary_setup a');
        $builder2->select("count(*) as allcount");
        $builder2->join('hrm_employees c','c.employee_id = a.employee_id','left');

        if($searchValue != ''){
            $builder2->where($searchQuery);
        }
        $builder2->groupBy('a.employee_id');
        $query2      =  $builder2->countAllResults();
        $totalRecordwithFilter = $query2;

        ## Fetch records
        $builder3 = $this->db->table('hrm_employee_salary_setup a');
        $builder3->select("a.*,c.first_name,c.last_name");
        $builder3->join('hrm_employees c','c.employee_id = a.employee_id','left');

        if($searchValue != ''){
            $builder3->where($searchQuery);
        }     

        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $builder3->groupBy('a.employee_id');
        $query3   =  $builder3->get();
        $records  =   $query3->getResult();
        $data     = array();
        $sl       = 1;

        foreach($records as $record ){ 

            $button = '';
            $base_url = base_url();

            $jsaction = "return confirm('Are You Sure ?')";

            if($this->hasSalarySetupListUpdateAccess){
                $button .=' <a href="'.$base_url.'/human_resources/payroll/edit_salary_setup/'.$record->employee_id.'" class="btn btn-success-soft btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fas fa-edit" aria-hidden="true"></i></a>';
            }
            if($this->hasSalarySetupListDeleteAccess){
                $button .=' <a onclick="'.$jsaction.'" href="'.$base_url.'/human_resources/payroll/delete_salsetup/'.$record->employee_id.'"  class="btn btn-danger-soft btn-sm" data-toggle="tooltip" data-placement="right" title="Delete "><i class="far fa-trash-alt" aria-hidden="true"></i></a>';
            }

            $data[] = array( 
                'sl'               =>$sl,
                'employee'         =>$record->first_name.' '.$record->last_name,
                'salary_type'      =>($record->sal_type == 'salary'?get_phrases(['salary']):get_phrases(['hourly'])),
                'create_date'      =>$record->create_date,
                'gross_salary'     =>$record->gross_salary,
                'button'           =>$button,
                
            ); 

            $sl++;
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

    public function salary_typeName()
    {
        return  $this->db->table('hrm_salary_benefit')
                 ->where('benefit_type', 1)
                 ->where('status',1)
                 ->get()
                 ->getResult(); 
    }

   public function salary_typedName()
    {
        return  $this->db->table('hrm_salary_benefit')
                 ->where('benefit_type', 2)
                 ->where('status',1)
                 ->get()
                 ->getResult(); 
    }

    public function check_exist_salarysetup($employee_id){

      $exitstdata = $this->db->table('hrm_employee_salary_setup')
                     ->where('employee_id', $employee_id)
                     ->countAllResults(); 
                    
       if($exitstdata > 0){
        return $exitstdata;
       }else{
        return $exitstdata;
       }             

    }

    public function salary_setup_create($data = array())
    {
       
         $builder = $this->db->table('hrm_employee_salary_setup');
         $add_salary_setup = $builder->insert($data);
       
        if($add_salary_setup){
           return true;
        }else{
            return false;
        }
    }

    public function salary_s_updateForm($id)
    {
        return $data = $this->db->table('hrm_employee_salary_setup')
                     ->where('employee_id',$id)
                     ->get()
                     ->getResultArray(); 
    }

    public function employee_informationId($id)
    {
        return $result = $this->db->table('hrm_employees')
                      ->select('*')
                      ->where('employee_id',$id)
                      ->get()
                      ->getResultArray();

    }

    public function setup_benefit_value($id,$benefit)
    {
        return $data = $this->db->table('hrm_employee_salary_setup ')
                     ->select('*')
                     ->where('employee_id',$id)
                     ->where('salary_type_id',$benefit)
                     ->get()
                     ->getRow(); 
    }

    public function update_sal_stup($data = array())
    {
        $term = array('employee_id' => $data['employee_id'], 'salary_type_id' => $data['salary_type_id']);

        $employee_salary_setup  = $this->db->table('hrm_employee_salary_setup')
                                ->where('employee_id', $data['employee_id'])
                                ->where('salary_type_id', $data['salary_type_id'])
                                ->get()
                                ->getRow();

        if($employee_salary_setup){

            $salupdate = $this->db->table('hrm_employee_salary_setup');   
            $salupdate->where($term);
            return $salupdate->update($data); 

        }else{

            $data['create_date'] =  $data['update_date'];
            $data['create_by']   =  $data['update_by'];

            unset($data['update_date']);
            unset($data['update_by']);

            $builder = $this->db->table('hrm_employee_salary_setup');
            $add_salary_setup = $builder->insert($data);

             return $add_salary_setup;
        }

        // $salupdate = $this->db->table('hrm_employee_salary_setup');   
        // $salupdate->where($term);
        // return $salupdate->update($data); 
    }

    public function emp_salstup_delete($id = null)
    {
        $builder = $this->db->table('hrm_employee_salary_setup');
        $builder->where('employee_id', $id);
        $builder->delete();
        if ($this->db->affectedRows()) {
            return true;
        } else {
            return false;
        }
    }


    public function department_list()
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



    /*
    | getEmployees for overtime generate from salary_setup table where existing employee should not come for whom already overtime generate done...
    */
    public function bdtaskt1m8_14_employeesForOvertimes()
    {

        $date_y = date('Y');
        $date_m = date('m');

        //Get previous Month // Open the below commented section later **********
        ////////////////////////////////////////////////If we want to check for current month year then this seciton can be commented
        $date_m = $date_m - 1;

        if($date_m == 0){
            $date_y = $date_y - 1;
            $date_m = 12;
        }
        ////////////////////////////////////////////////

        $over_times_employees = $this->db->table("hrm_over_times")
                                ->select('emp_id')
                                ->where('YEAR(hrm_over_times.overtime_for)', $date_y)
                                ->where('MONTH(hrm_over_times.overtime_for)', $date_m)
                                ->get()
                                ->getResultArray();

        $prev_month_over_times_employees = array_column($over_times_employees, 'emp_id');

        $builder3 = $this->db->table("hrm_employees");
        $builder3->select("hrm_employees.first_name, hrm_employees.last_name, hrm_employees.employee_id");

        $builder3->where('hrm_employees.status',1);

        if(count($prev_month_over_times_employees) > 0){
            $builder3->whereNotIn('hrm_employees.employee_id',$prev_month_over_times_employees);
        }

        $employees = $builder3->get()->getResultArray();

        return $employees;

    }


    public function bdtaskt1m9_16_overTimesList($postData=null){

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
           $searchQuery = " (emp.emp_name like '%".$searchValue."%') ";
        }

        ## Fetch records
        $builder3 = $this->db->table('hrm_over_times ovtime');
        $builder3->select("ovtime.*, CONCAT_WS(' ', emp.first_name, ,emp.last_name) as emp_name,emp.last_name,crtemp.first_name as created_by,upemp.first_name as updated_by");
        $builder3->join("hrm_employees emp", "emp.employee_id = ovtime.emp_id ", "left");
        $builder3->join("hrm_employees crtemp", "crtemp.employee_id = ovtime.CreateBy ", "left");
        $builder3->join("hrm_employees upemp", "upemp.employee_id = ovtime.UpdateBy ", "left");

        if(!session('isAdmin')){
            $builder3->where('ovtime.CreateBy',session('id'));
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
            $year_month = date('F', strtotime($record['overtime_for'])).' '.date('Y', strtotime($record['overtime_for']));

            $status = '';
            if($record['status'] != null){
                $status = $record['status']=='accept'?'<span class="badge badge-pill badge-success">'.get_phrases(['accepted']).'</span>':'<span class="badge badge-pill badge-danger">'.get_phrases(['declined']).'</span>';
            }else{
                $status = '<span class="badge badge-pill badge-warning">'.get_phrases(['pending']).'</span>';
            }
            
            $button .= (!$this->hasOverTimeUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.get_phrases(['update']).'" data-id="'.$record['over_time_id'].'"><i class="far fa-edit"></i></a>';
            $button .= (!$this->hasOverTimeDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.get_phrases(['delete']).'" data-id="'.$record['over_time_id'].'"><i class="far fa-trash-alt"></i></a>';


            $data[] = array(
                'sl'           => $i,
                'over_time_id' => $record['over_time_id'],
                'emp_name'   => $record['emp_name'],
                'time'         => $record['time'],
                'overtime_for' => $year_month,
                'status'       => $status,
                'created_by'   => $record['created_by'],
                'CreateDate'   => $record['CreateDate'],
                'updated_by'   => $record['updated_by'],
                'UpdateDate'   => $record['UpdateDate'],
                'button'       => $button
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
    | Numbers of working dates for an employee for a month
    */
    public function bdtaskt1m9_07_totalWorkingDates($id)
    {
        //Calculate monthly working days for the employee
        $hrm_weekends = $this->db->table("hrm_weekends")->get()->getRow();
        
        $weekends_days = explode(',', $hrm_weekends->weekend_days);
        
        $weekends = array();
        foreach ($weekends_days as $value) {

            if($value == "Saturday"){
                $weekends[] = "Sat";
            }
            elseif($value == "Sunday"){
                $weekends[] = "Sun";

            }elseif($value == "Monday"){
                $weekends[] = "Mon";

            }elseif($value == "Tuesday"){
                $weekends[] = "Tue";

            }elseif($value == "Wednesday"){
                $weekends[] = "Wed";

            }elseif($value == "Thursday"){
                $weekends[] = "Thu";

            }else{
                $weekends[] = "Fri";
            }
        }
        // return $weekends;

        //Now calculate Monthly Working Days
        $workdays = array();
        $date_y = date('Y');
        $date_m = date('m');

        //Get previous Month // Open the below commented section later ************
        ////////////////////////////////////////////////If we want to check for current month year then this seciton can be commented
        $date_m = $date_m - 1;

        if($date_m == 0){
            $date_y = $date_y - 1;
            $date_m = 12;
        }
        ////////////////////////////////////////////////

        $day_count = cal_days_in_month(CAL_GREGORIAN, $date_m, $date_y);

        //loop through all days
        for ($i = 1; $i <= $day_count; $i++) {

                $date = $date_y.'/'.$date_m.'/'.$i; //format date
                $get_name = date('l', strtotime($date)); //get week day
                $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

                //if not a weekend add day to array
                if(!in_array($day_name, $weekends)){

                    $date_modified = $date_y.'-'.$date_m.'-'.$i;

                    // Check if $date exists for any government holidays...
                    $builder = $this->db->table('hrm_holidays');
                    $builder->select('*');
                    $builder->where("start_date <=",$date_modified);
                    $builder->where("end_date >=",$date_modified);
                    $query=$builder->get();
                    $govt_holiday_data=$query->getRow();

                    if(!$govt_holiday_data){

                        $workdays[] = $date_y.'-'.$date_m.'-'.$i;

                    }
                    //Ends

                    // Also check if $date exists for any leave application , which approved...
                    // $builder2 = $this->db->table('hrm_leave_application');
                    // $builder2->select('*');
                    // $builder2->where("employee_id",$id);
                    // $builder2->where("leave_aprv_strt_date <=",$date_modified);
                    // $builder2->where("leave_aprv_end_date >=",$date_modified);
                    // $query2=$builder2->get();
                    // $leave_approved_data=$query2->getRow();

                    // if(!$govt_holiday_data && !$leave_approved_data){

                    //     $workdays[] = $date_y.'-'.$date_m.'-'.$i;

                    // }
                    //Ends

                }
        }

        // return count($workdays);
        return $workdays;

    }

    /*
    | Numbers of working hours for an employee for a month
    */
    public function bdtaskt1m9_08_totalWorkingHours($employee_id, $working_dates_monthly)
    {
        $data = array();

        $attendnace_setup = $this->db->table("hrm_attendance_setup")->get()->getRow();

        // return $attendnace_setup;

        //Calculate monthly working hours for the employee
        $total_work_hours = 0;
        $i = 0;

        $total = [];
        $totalhour = [];

        foreach ($working_dates_monthly as $key => $value) {
            
            $minutes = 0;
            $data_set = array();
            // Check work hour date wise from duty roster...if not available, then check from Normal attendance setup
            $builder = $this->db->table('hrm_emproster_assign');
            $builder->select('*');
            $builder->where("emp_id",$employee_id);
            $builder->where("emp_startroster_date",$value);
            $query=$builder->get();
            $emproster_assign_data=$query->getRow();

            // $data[$i] = $this->db->getLastQuery()->getQuery();

            if($emproster_assign_data){

                $data_set['in_time'] = $value.' '.$emproster_assign_data->emp_startroster_time;
                $data_set['out_time'] = $value.' '.$emproster_assign_data->emp_endroster_time;

                $data[$i] = $data_set;

            }else{

                $data_set['in_time'] = $value.' '.$attendnace_setup->in_time;
                $data_set['out_time'] = $value.' '.$attendnace_setup->out_time;

                $data[$i] = $data_set;
            }

            /////////////////// hours, mins, and seconds evaluation ///////////////////////
            $intervalDate = strtotime($data_set['out_time']) - strtotime($data_set['in_time']);

            $totalhour[$i]= gmdate('H:i:s',$intervalDate);

            $i++;

            //Ends
        }

        /////////////////// hours, mins, and seconds evaluation ///////////////////////

        $seconds = 0;
        foreach($totalhour as $t)
        {
            $timeArr = array_reverse(explode(":", $t));

            foreach ($timeArr as $key => $value)
            {
                if ($key > 2) break;
                    $seconds += pow(60, $key) * $value;
            }

        }

        $hours = floor($seconds / 3600);
        $mins = floor(($seconds - ($hours*3600)) / 60);
        $secs = floor($seconds % 60);

        $total['hours'] = $hours;
        $total['mins'] = $mins;
        $total['secs'] = $secs;

        ///////////////////End of hours, mins, and seconds evaluation ///////////////////////

        // return $totalhour;
        return $total['hours'].':'.$total['mins'];

    }

    /*
    | Numbers of working hours for an employee for a month
    */
    public function bdtaskt1m9_09_totalWorkedHours($employee_id)
    {
        $date_y = date('Y');
        $date_m = date('m');

        //Get previous Month // Open the below commented section later *************
        ////////////////////////////////////////////////If we want to check for current month year then this seciton can be commented
        $date_m = $date_m - 1;

        if($date_m == 0){
            $date_y = $date_y - 1;
            $date_m = 12;
        }
        ////////////////////////////////////////////////

        $current_date = $date_y.'-'.$date_m.'-01';
        $from_date = $current_date;
        $to_date    = date("Y-m-t", strtotime($current_date));

        // return "From Date: ".$from_date." , To Date: ".$to_date;

        $att = "SELECT *, DATE(atten_date) as mydate FROM `hrm_attendance_history` WHERE `uid`=$employee_id AND DATE(time) BETWEEN '" . $from_date . "' AND  '" . $to_date . "' GROUP BY mydate ORDER BY time desc";
        $query = $this->db->query($att)->getResult();

        $atten_in = [];
        $i=1;

        foreach ($query as $attendance) {

            $atten_in[$i] = $this->db->table("hrm_attendance_history a")
                    ->select('a.time,MIN(a.time) as intime,MAX(a.time) as outtime,a.uid,a.atten_date')
                    ->like('a.atten_date',date( "Y-m-d", strtotime($attendance->mydate)))
                    ->where('a.uid',$attendance->uid)
                    ->orderBy('a.time','DESC')
                    ->get()
                    ->getResult();
            $i++;
        }

        //return $atten_in;

        // Calculate Net working hours
        $idx=1;
        $totalhour=[];
        $totalwasthour = [];
        $totalnetworkhour = [];

        foreach ($atten_in as $attendancedata) {

            $date_a = strtotime($attendancedata[0]->outtime);
            $date_b = strtotime($attendancedata[0]->intime);
            $intervalDate = $date_a - $date_b;

            $interval= gmdate('H:i:s',$intervalDate);

            $totalwhour = $interval;
            $totalhour[$idx] = $totalwhour;

            $att_dates = date( "Y-m-d", strtotime($attendancedata[0]->atten_date));  

            $att_in = $this->db->table("hrm_attendance_history a")
            ->select('a.*,b.first_name,b.last_name')
            ->join('hrm_employees b','a.uid = b.employee_id','left')
            ->like('a.atten_date',$att_dates,'after')
            ->where('a.uid',$attendancedata[0]->uid)
            ->orderBy('a.time','ASC')
            ->get()
            ->getResult();

            $ix=1;
            $in_data = [];
            $out_data = [];

            foreach ($att_in as $attendancedata) {

                if($ix % 2){
                $status = "IN";
                $in_data[$ix] = $attendancedata->time;

                }else{
                $status = "OUT";
                $out_data[$ix] = $attendancedata->time;
                }
                $ix++;
            }


            $result_in = array_values($in_data);
            $result_out = array_values($out_data);
            $total = [];
            $count_out = count($result_out);

            if($count_out >= 2){
                $n_out = $count_out-1;
            }else{
                $n_out = 0;   
            }

            for($i=0;$i < $n_out; $i++) {

                $date_a = strtotime($result_in[$i+1]);
                $date_b = strtotime($result_out[$i]);
                $intervalDate = $date_a - $date_b;

                $total[$i]= gmdate('H:i:s',$intervalDate);


            }

            $hou = 0;
            $min = 0;
            $sec = 0;

            $totaltime = '00:00:00';
            $length = sizeof($total);

            for($x=0; $x <= $length; $x++){

                $split = explode(":", @$total[$x]); 
                $hou += @(integer)$split[0];
                $min += @(int)$split[1];
                $sec += @(int)$split[2];

            }

            $seconds = $sec % 60;
            $minutes = $sec / 60;
            $minutes = (integer)$minutes;
            $minutes += $min;
            $hours = $minutes / 60;
            $minutes = $minutes % 60;
            $hours = (integer)$hours;
            $hours += $hou % 24;

            $totalwastage = $hours.":".$minutes.":".$seconds;
            $totalwasthour[$idx] = $totalwastage;

            $date_a = strtotime($totalwhour);
            $date_b = strtotime($totalwastage);
            $intervalDate = $date_a - $date_b;

            $ntworkh= gmdate('H:i:s',$intervalDate);

            $totalnetworkhour[$idx] = $ntworkh;

            $idx++; 
        }

        //////////
        $seconds = 0;
        foreach($totalhour as $t)
        {
            $timeArr = array_reverse(explode(":", $t));

            foreach ($timeArr as $key => $value)
            {
                if ($key > 2) break;
                    $seconds += pow(60, $key) * $value;
            }

        }

        $hours = floor($seconds / 3600);
        $mins = floor(($seconds - ($hours*3600)) / 60);
        $secs = floor($seconds % 60);

        $wastsecond = 0;
        foreach($totalwasthour as $wastagetime)
        {
            $wastimearray = array_reverse(explode(":", $wastagetime));

            foreach ($wastimearray as $key => $value)
            {
                if ($key > 2) break;
                    $wastsecond += pow(60, $key) * $value;
            }

        }

        $wasthours = floor($wastsecond / 3600);
        $wastmin = floor(($wastsecond - ($wasthours*3600)) / 60);
        $wastsc = floor($wastsecond % 60);

        $netsecond = 0;
        foreach($totalnetworkhour as $nettime)
        {
            $nettimearray = array_reverse(explode(":", $nettime));

            foreach ($nettimearray as $key => $value)
            {
                if ($key > 2) break;
                    $netsecond += pow(60, $key) * $value;
            }

        }

        $nettlehour = floor($netsecond / 3600);
        $netmin = floor(($netsecond - ($nettlehour*3600)) / 60);
        $ntsec = floor($netsecond % 60);

        return $nettlehour.':'.$netmin;
    }


    public function bdtaskt1m9_15_employeesOvertimeAlreadyGenerated($emp_id)
    {

        $date_y = date('Y');
        $date_m = date('m');

        //Get previous Month // Open the below commented section later **********
        ////////////////////////////////////////////////If we want to check for current month year then this seciton can be commented
        $date_m = $date_m - 1;

        if($date_m == 0){
            $date_y = $date_y - 1;
            $date_m = 12;
        }
        ////////////////////////////////////////////////

        $over_times_employee = $this->db->table("hrm_over_times")
                                ->select('*')
                                ->where('YEAR(hrm_over_times.overtime_for)', $date_y)
                                ->where('MONTH(hrm_over_times.overtime_for)', $date_m)
                                ->where('emp_id',$emp_id)
                                ->get()
                                ->getResult();

        if($over_times_employee){

            return true;
        }

    }

    public function bdtask_007_salary_generate($data = array())
    {

         $builder = $this->db->table('hrm_salary_sheet_generate');
         $salary_generate = $builder->insert($data);
       
        if($salary_generate){
           return true;
        }else{
            return false;
        }
    }


    /*
    | Numbers of working dates for an employee for a month
    */
    public function bdtaskt1m9_10_salaryGen_totalWorkingDates($id,$date_m,$date_y)
    {
        //Calculate monthly working days for the employee
        $hrm_weekends = $this->db->table("hrm_weekends")->get()->getRow();
        
        $weekends_days = explode(',', $hrm_weekends->weekend_days);
        
        $weekends = array();
        foreach ($weekends_days as $value) {

            if($value == "Saturday"){
                $weekends[] = "Sat";
            }
            elseif($value == "Sunday"){
                $weekends[] = "Sun";

            }elseif($value == "Monday"){
                $weekends[] = "Mon";

            }elseif($value == "Tuesday"){
                $weekends[] = "Tue";

            }elseif($value == "Wednesday"){
                $weekends[] = "Wed";

            }elseif($value == "Thursday"){
                $weekends[] = "Thu";

            }else{
                $weekends[] = "Fri";
            }
        }

        //Now calculate Monthly Working Days
        $workdays = array();
        $day_count = cal_days_in_month(CAL_GREGORIAN, $date_m, $date_y);

        //loop through all days
        for ($i = 1; $i <= $day_count; $i++) {

                $date = $date_y.'/'.$date_m.'/'.$i; //format date
                $get_name = date('l', strtotime($date)); //get week day
                $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

                //if not a weekend add day to array
                if(!in_array($day_name, $weekends)){

                    $date_modified = $date_y.'-'.$date_m.'-'.$i;

                    // Check if $date exists for any government holidays...
                    $builder = $this->db->table('hrm_holidays');
                    $builder->select('*');
                    $builder->where("start_date <=",$date_modified);
                    $builder->where("end_date >=",$date_modified);
                    $query=$builder->get();
                    $govt_holiday_data=$query->getRow();

                    if(!$govt_holiday_data){

                        $workdays[] = $date_y.'-'.$date_m.'-'.$i;

                    }
                    //Ends

                    // Also check if $date exists for any leave application , which approved...
                    // $builder2 = $this->db->table('hrm_leave_application');
                    // $builder2->select('*');
                    // $builder2->where("employee_id",$id);
                    // $builder2->where("leave_aprv_strt_date <=",$date_modified);
                    // $builder2->where("leave_aprv_end_date >=",$date_modified);
                    // $query2=$builder2->get();
                    // $leave_approved_data=$query2->getRow();

                    // if(!$govt_holiday_data && !$leave_approved_data){

                    //     $workdays[] = $date_y.'-'.$date_m.'-'.$i;

                    // }
                    //Ends

                }
        }

        return $workdays;

    }

    /*
    | Total working hours for a month, when generating salary
    */

    public function bdtaskt1m9_11_salaryGen_totalWorkedHours($employee_id,$date_m,$date_y)
    {

        $current_date = $date_y.'-'.$date_m.'-01';
        $from_date = $current_date;
        $to_date    = date("Y-m-t", strtotime($current_date));

        // return "From Date: ".$from_date." , To Date: ".$to_date;

        $att = "SELECT *, DATE(atten_date) as mydate FROM `hrm_attendance_history` WHERE `uid`=$employee_id AND DATE(time) BETWEEN '" . $from_date . "' AND  '" . $to_date . "' GROUP BY mydate ORDER BY time desc";
        $query = $this->db->query($att)->getResult();

        $atten_in = [];
        $i=1;

        foreach ($query as $attendance) {

            $atten_in[$i] = $this->db->table("hrm_attendance_history a")
                    ->select('a.time,MIN(a.time) as intime,MAX(a.time) as outtime,a.uid,a.atten_date')
                    ->like('a.atten_date',date( "Y-m-d", strtotime($attendance->mydate)))
                    ->where('a.uid',$attendance->uid)
                    ->orderBy('a.time','DESC')
                    ->get()
                    ->getResult();
            $i++;
        }

        //return $atten_in;

        // Calculate Net working hours
        $idx=1;
        $totalhour=[];
        $totalwasthour = [];
        $totalnetworkhour = [];

        foreach ($atten_in as $attendancedata) {

            $date_a = strtotime($attendancedata[0]->outtime);
            $date_b = strtotime($attendancedata[0]->intime);
            $intervalDate = $date_a - $date_b;

            $interval= gmdate('H:i:s',$intervalDate);

            $totalwhour = $interval;
            $totalhour[$idx] = $totalwhour;

            $att_dates = date( "Y-m-d", strtotime($attendancedata[0]->atten_date));  

            $att_in = $this->db->table("hrm_attendance_history a")
            ->select('a.*,b.first_name,b.last_name')
            ->join('hrm_employees b','a.uid = b.employee_id','left')
            ->like('a.atten_date',$att_dates,'after')
            ->where('a.uid',$attendancedata[0]->uid)
            ->orderBy('a.time','ASC')
            ->get()
            ->getResult();

            $ix=1;
            $in_data = [];
            $out_data = [];

            foreach ($att_in as $attendancedata) {

                if($ix % 2){
                $status = "IN";
                $in_data[$ix] = $attendancedata->time;

                }else{
                $status = "OUT";
                $out_data[$ix] = $attendancedata->time;
                }
                $ix++;
            }


            $result_in = array_values($in_data);
            $result_out = array_values($out_data);
            $total = [];
            $count_out = count($result_out);

            if($count_out >= 2){
                $n_out = $count_out-1;
            }else{
                $n_out = 0;   
            }

            for($i=0;$i < $n_out; $i++) {

                $date_a = strtotime($result_in[$i+1]);
                $date_b = strtotime($result_out[$i]);
                $intervalDate = $date_a - $date_b;

                $total[$i]= gmdate('H:i:s',$intervalDate);


            }

            $hou = 0;
            $min = 0;
            $sec = 0;

            $totaltime = '00:00:00';
            $length = sizeof($total);

            for($x=0; $x <= $length; $x++){

                $split = explode(":", @$total[$x]); 
                $hou += @(integer)$split[0];
                $min += @(int)$split[1];
                $sec += @(int)$split[2];

            }

            $seconds = $sec % 60;
            $minutes = $sec / 60;
            $minutes = (integer)$minutes;
            $minutes += $min;
            $hours = $minutes / 60;
            $minutes = $minutes % 60;
            $hours = (integer)$hours;
            $hours += $hou % 24;

            $totalwastage = $hours.":".$minutes.":".$seconds;
            $totalwasthour[$idx] = $totalwastage;

            $date_a = strtotime($totalwhour);
            $date_b = strtotime($totalwastage);
            $intervalDate = $date_a - $date_b;

            $ntworkh= gmdate('H:i:s',$intervalDate);

            $totalnetworkhour[$idx] = $ntworkh;

            $idx++; 
        }

        //////////
        $seconds = 0;
        foreach($totalhour as $t)
        {
            $timeArr = array_reverse(explode(":", $t));

            foreach ($timeArr as $key => $value)
            {
                if ($key > 2) break;
                    $seconds += pow(60, $key) * $value;
            }

        }

        $hours = floor($seconds / 3600);
        $mins = floor(($seconds - ($hours*3600)) / 60);
        $secs = floor($seconds % 60);

        $wastsecond = 0;
        foreach($totalwasthour as $wastagetime)
        {
            $wastimearray = array_reverse(explode(":", $wastagetime));

            foreach ($wastimearray as $key => $value)
            {
                if ($key > 2) break;
                    $wastsecond += pow(60, $key) * $value;
            }

        }

        $wasthours = floor($wastsecond / 3600);
        $wastmin = floor(($wastsecond - ($wasthours*3600)) / 60);
        $wastsc = floor($wastsecond % 60);

        $netsecond = 0;
        foreach($totalnetworkhour as $nettime)
        {
            $nettimearray = array_reverse(explode(":", $nettime));

            foreach ($nettimearray as $key => $value)
            {
                if ($key > 2) break;
                    $netsecond += pow(60, $key) * $value;
            }

        }

        $nettlehour = floor($netsecond / 3600);
        $netmin = floor(($netsecond - ($nettlehour*3600)) / 60);
        $ntsec = floor($netsecond % 60);

        return $nettlehour.':'.$netmin;
    }


    public function get_salarygenerateList($postData=null)
    {
        $response        = array();
        $draw            = $postData['draw'];
        $start           = $postData['start'];
        $rowperpage      = $postData['length']; // Rows display per page
        $columnIndex     = $postData['order'][0]['column']; // Column index
        $columnName      = $postData['columns'][$columnIndex]['data']; // 
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue     = $postData['search']['value']; // Search value
        $searchQuery     = "";
        if($searchValue != ''){
        $searchQuery  = " (c.fullname like '%".$searchValue."%' or a.date like'%".$searchValue."%' or a.gdate like'%".$searchValue."%' or d.name like'%".$searchValue."%') ";
        }

        ## Total number of records without filtering
        $builder1 = $this->db->table('hrm_salary_sheet_generate a');
        $builder1->select("count(*) as allcount");
        $builder1->join('user c','c.id = a.generate_by','left');
        $builder1->join('hrm_departments d','d.id = a.department_id','left');

        if($searchValue != ''){
           $builder1->where($searchQuery);
        }   

        $query1       = $builder1->countAllResults();
        $totalRecords = $query1;

         
        ## Total number of record with filtering
        $builder2 = $this->db->table('hrm_salary_sheet_generate a');
        $builder2->select("count(*) as allcount");
        $builder2->join('user c','c.id = a.generate_by','left');
        $builder2->join('hrm_departments d','d.id = a.department_id','left');

        if($searchValue != ''){
           $builder2->where($searchQuery);
        }

        $query2      =  $builder2->countAllResults();
        $totalRecordwithFilter = $query2;


        ## Fetch records
        $builder3 = $this->db->table('hrm_salary_sheet_generate a');
        $builder3->select("a.*,c.fullname,d.name as department_name");
        $builder3->join('user c','c.id = a.generate_by','left');
        $builder3->join('hrm_departments d','d.id = a.department_id','left');

        if($searchValue != ''){
            $builder3->where($searchQuery);
        }

        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records  =   $query3->getResult();
        $data     = array();
        $sl       = 1;
        
        foreach($records as $record ){ 

            $button = '';
            $base_url = base_url();
             
            $jsaction = "return confirm('Are You Sure ?')";
            if($this->permission->method('salary_sheet','delete')->access()){
               $button .=' <a onclick="'.$jsaction.'" href="'.$base_url.'/human_resources/payroll/delete_salaryshett/'.$record->ssg_id.'"  class="btn btn-danger-soft btn-sm" data-toggle="tooltip" data-placement="right" title="Delete "><i class="far fa-trash-alt" aria-hidden="true"></i></a>';
            }

            $data[] = array(
                'sl'               =>$sl,
                'department_name'  =>$record->department_name,
                'generate_by'      =>$record->fullname,
                'gdate'            =>$record->gdate ,
                'date'             =>$record->date,
                'button'           =>$button,
                
            ); 

            $sl++;

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


    public function sal_generate_delete($id = null) {

        $esalary = $this->db->table('hrm_employee_salary_payment');
        $esalary->where('generate_id', $id);
        $esalary->delete();

        $gtable = $this->db->table('hrm_salary_sheet_generate');
        $gtable->where('ssg_id', $id);
        $gtable->delete();
        if ($this->db->affectedRows()) {
            return true;
        } else {
            return false;
        }
    
    } 


    public function get_salarypaymentList($postData=null)
    {
         $response        = array();
         $draw            = $postData['draw'];
         $start           = $postData['start'];
         $rowperpage      = $postData['length']; // Rows display per page
         $columnIndex     = $postData['order'][0]['column']; // Column index
         $columnName      = $postData['columns'][$columnIndex]['data']; // 
         $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
         $searchValue     = $postData['search']['value']; // Search value
         $searchQuery     = "";

         if($searchValue != ''){
            $searchQuery  = " (c.first_name like '%".$searchValue."%' or c.last_name like'%".$searchValue."%' or a.create_date like'%".$searchValue."%') ";
         }

        ## Total number of records without filtering

        $builder1 = $this->db->table('hrm_employee_salary_payment a');
        $builder1->select("count(*) as allcount");
        $builder1->join('hrm_employees c','c.employee_id = a.employee_id','left');
           if($searchValue != ''){
               $builder1->where($searchQuery);
           }   
        $query1       = $builder1->countAllResults();
        $totalRecords = $query1;
         
        ## Total number of record with filtering

        $builder2 = $this->db->table('hrm_employee_salary_payment a');
        $builder2->select("count(*) as allcount");
        $builder2->join('hrm_employees c','c.employee_id = a.employee_id','left');
           if($searchValue != ''){
               $builder2->where($searchQuery);
           }
             $query2      =  $builder2->countAllResults();
        $totalRecordwithFilter = $query2;

        ## Fetch records
        $builder3 = $this->db->table('hrm_employee_salary_payment a');
        $builder3->select("a.*,c.first_name,c.last_name,d.fullname");
        $builder3->join('hrm_employees c','c.employee_id = a.employee_id','left');
        $builder3->join('user d','d.id = a.paid_by','left');

        if($searchValue != ''){
            $builder3->where($searchQuery);
        }     

        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records  =   $query3->getResult();
        $data     = array();
        $sl       = 1;
        
         foreach($records as $record ){ 

            $button = '';
            $base_url = base_url();

            $params = $record->emp_sal_pay_id.",".$record->employee_id.","."$record->total_salary".","."$record->total_working_minutes".","."$record->working_period".","."'$record->salary_month'";
            $payslip = '<a href="'.base_url('human_resources/payroll/payslip/'.$record->emp_sal_pay_id).'" class="btn btn-primary-soft">'.get_phrases(['payslip']).'</a>';

            $button .= ' <button class="client-add-btn btn btn-success" type="button" aria-hidden="true" onclick="payment_modal('.$params.')">'.get_phrases(['pay_now']).'</button>';

            $data[] = array( 

                'sl'                    =>$sl,
                'employee'              =>$record->first_name.' '.$record->last_name,
                'total_salary'          =>$record->total_salary ,
                'total_working_minutes' =>floor($record->total_working_minutes / 60).':'.($record->total_working_minutes -   floor($record->total_working_minutes / 60) * 60),
                'working_period'        =>$record->working_period,
                'payment_due'           =>$record->payment_due,
                'payment_date'          =>$record->payment_date,
                'paid_by'               =>$record->fullname,
                'salary_month'          =>$record->salary_month,
                'button'                =>($record->payment_due == 'paid'?$payslip:$button),
            ); 

            $sl++;

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



}