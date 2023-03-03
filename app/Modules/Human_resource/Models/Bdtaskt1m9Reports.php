<?php namespace App\Modules\Human_resource\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m9Reports extends Model
{
    protected $table = 'employees';

    public function __construct()
    {
        $this->db = db_connect();

        $this->permission = new Permission();
        $this->hasReadAttendnaceSheet = $this->permission->method('attendance_sheet', 'read')->access();
        $this->hasAttendnaceSheetUpdateAccess = $this->permission->method('attendance_sheet', 'update')->access();
        $this->hasAttendnaceSheetDeleteAccess = $this->permission->method('attendance_sheet', 'delete')->access();

        $setting = $this->db->table('setting')->select('*')->get()->getRow();

        $timeZone = (!empty($setting->time_zone)?$setting->time_zone:'Asia/Dhaka');
        @date_default_timezone_set($timeZone);
    }


    public function bdtaskt1m9_01_getAttendanceSheetReport($employee_type, $date)
    {
        list($month,$year) = explode(' ',$date);
        $employee_type_id = $employee_type;
        $month_number = $this->get_selected_month_number($month);

        // Get total working days elminating weekends and government holidays for selected date
        $working_dates_monthly = count($this->totalWorkingDates($month_number,$year));
        // Find weekends and government holidays
        $weekends_holidays = count($this->totalWeekendsAndGovtHolidays($month_number,$year));
        //Total days for the month
        $total_days = cal_days_in_month(CAL_GREGORIAN, $month_number, $year);
        //Total working days
        $working_days = (int)$total_days - (int)$weekends_holidays;

        // Get employees by employee type
        $employees = $this->db->table('hrm_employees a')
                    ->select('a.*,b.type as employee_type,c.name as department_name')
                    ->join('hrm_employee_types b','a.employee_type = b.id','left')
                    ->join('hrm_departments c','a.department = c.id','left')
                    ->where('employee_type',$employee_type_id)
                    ->get()->getResult();

        $i = 0;
        $respo_arr = array();

        foreach ($employees as $key => $value) {

            $child_arr['employee_id'] = $value->employee_id;
            $child_arr['name'] = $value->first_name.' '.$value->last_name;
            $child_arr['department'] = $value->department_name;
            $child_arr['date_of_joining'] = $value->joining_date;
            $child_arr['total_days'] = $total_days;
            $child_arr['wh_gh'] = $weekends_holidays;
            $child_arr['working_days'] = $working_days;

            $child_arr['present_days'] = 0;
            $child_arr['cl'] = 0;
            $child_arr['sl'] = 0;
            $child_arr['total_leave'] = 0;
            $child_arr['cpl'] = 0;
            $child_arr['payable_days'] = 0;
            
            //Check present_days
            $present_days = $this->totalDaysWorkedForTheMonth($value->employee_id,$month_number,$year);
            if($present_days){
                $child_arr['present_days'] = $present_days;
            }
            //Check casual leave taken or not
            $cacual_leaves = $this->totalCasuaOrSicklLeaveTaken($value->employee_id,$month_number,$year,2);
            if($cacual_leaves){
                $child_arr['cl'] = $cacual_leaves;
            }
            //Check casual leave taken or not
            $sick_leaves = $this->totalCasuaOrSicklLeaveTaken($value->employee_id,$month_number,$year,1);
            if($sick_leaves){
                $child_arr['sl'] = $sick_leaves;
            }
            $child_arr['total_leave'] = $total_leave = (int)$cacual_leaves + (int)$sick_leaves;

            //Check total col leave for the month for the employee
            $cpl_leaves = $this->totalCplLeaveEarned($value->employee_id,$month_number,$year);
            if($cpl_leaves){
                $child_arr['cpl'] = $cpl_leaves;
            }
            $child_arr['payable_days'] = (int)$present_days + (int)$total_leave;

            // Set all data from child_arr to respo_arr

            // $respo_arr[$i] = $child_arr;

            if(!isset($respo_arr[$value->department_name])){

                $respo_arr[$value->department_name][0] = $child_arr;
            }else{

                $index = count($respo_arr[$value->department_name]);
                $respo_arr[$value->department_name][$index] = $child_arr;
            }

            $i++;
        }

        return $respo_arr;

    }

    /*
    | Check total col leave for the month for the employee
    */

    public function bdtaskt1m9_02_getDepartments($employee_type, $date)
    {
        // Get employees by employee type
        $departments = $this->db->table('hrm_employees a')
                    ->select('b.type as employee_type,c.name as department_name')
                    ->join('hrm_employee_types b','a.employee_type = b.id','left')
                    ->join('hrm_departments c','a.department = c.id','left')
                    ->where('employee_type',$employee_type)
                    ->groupBy('c.id')
                    ->get()->getResult();

        $respo_departments = array();

        foreach ($departments as $key => $value) {

            $respo_departments[] = $value->department_name;
        }

        return $respo_departments;
    }

    /*
    | Check total col leave for the month for the employee
    */

    public function totalCplLeaveEarned($employee_id,$date_m,$date_y)
    {
       $cpl_leaves = array();

       $day_count = cal_days_in_month(CAL_GREGORIAN, 1, 2022);

       //loop through all days
        for ($i = 1; $i <= $day_count; $i++) {

                $date_modified = $date_y.'-'.$date_m.'-'.$i;

                // Also check if $date exists for any leave application , which approved...
                $builder2 = $this->db->table('hrm_cpl_leave_history');
                $builder2->select('*');
                $builder2->where("employee_id",$employee_id);
                $builder2->where("date",$date_modified);
                $query2=$builder2->get();
                $cpl_leave=$query2->getRow();

                if($cpl_leave){

                    $cpl_leaves[] = $date_y.'-'.$date_m.'-'.$i;

                }
                //Ends
        }

        return count($cpl_leaves);

    }

    /*
    | Check casual or sick leave taken or not
    */
    public function totalCasuaOrSicklLeaveTaken($employee_id,$date_m,$date_y,$leave_type)
    {
       $cacual_or_sick_leaves = array();

       $day_count = cal_days_in_month(CAL_GREGORIAN, $date_m, $date_y);

       //loop through all days
        for ($i = 1; $i <= $day_count; $i++) {

                $date_modified = $date_y.'-'.$date_m.'-'.$i;

                // Also check if $date exists for any leave application , which approved...
                $builder2 = $this->db->table('hrm_leave_application');
                $builder2->select('*');
                $builder2->where("employee_id",$employee_id);
                $builder2->where("leave_aprv_strt_date <=",$date_modified);
                $builder2->where("leave_aprv_end_date >=",$date_modified);
                $builder2->where("leave_type",$leave_type);
                $query2=$builder2->get();
                $leave_approved_data=$query2->getRow();

                if($leave_approved_data){

                    $cacual_or_sick_leaves[] = $date_y.'-'.$date_m.'-'.$i;

                }
                //Ends
        }

        return count($cacual_or_sick_leaves);

    }

    /*
    | Numbers of days worked for the month
    */
    public function totalDaysWorkedForTheMonth($employee_id,$date_m,$date_y)
    {

        $current_date = $date_y.'-'.$date_m.'-01';
        $from_date = $current_date;
        $to_date    = date("Y-m-t", strtotime($current_date));

        // return "Employee ID: ".$employee_id." , From Date: ".$from_date." , To Date: ".$to_date;

        $att = "SELECT *, DATE(atten_date) as mydate FROM `hrm_attendance_history` WHERE `uid`=$employee_id AND DATE(time) BETWEEN '" . $from_date . "' AND  '" . $to_date . "' GROUP BY mydate ORDER BY time desc";
        $query = $this->db->query($att)->getResult();

        return count($query);

    }

    /*
    | Numbers of weekends and government holidays
    */
    public function totalWeekendsAndGovtHolidays($date_m,$date_y)
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
        $holidays = array();
        $day_count = cal_days_in_month(CAL_GREGORIAN, $date_m, $date_y);

        //loop through all days
        for ($i = 1; $i <= $day_count; $i++) {

                $date = $date_y.'/'.$date_m.'/'.$i; //format date
                $get_name = date('l', strtotime($date)); //get week day
                $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

                $date_modified = $date_y.'-'.$date_m.'-'.$i;

                // Check if $date exists for any government holidays...
                $builder = $this->db->table('hrm_holidays');
                $builder->select('*');
                $builder->where("start_date <=",$date_modified);
                $builder->where("end_date >=",$date_modified);
                $query=$builder->get();
                $govt_holiday_data=$query->getRow();

                //if not a weekend add day to array
                if(in_array($day_name, $weekends) || $govt_holiday_data){

                        $holidays[] = $date_y.'-'.$date_m.'-'.$i;
                    

                }
                //Ends
        }

        return $holidays;

    }

    public function get_selected_month_number($month_name)
    {

        $month = '';

        switch ($month_name)
        {
            case "January":
                $month = '1';
                break;
            case "February":
                $month = '2';
                break;
            case "March":
                $month = '3';
                break;
            case "April":
                $month = '4';
                break;
            case "May":
                $month = '5';
                break;
            case "June":
                $month = '6';
                break;
            case "July":
                $month = '7';
                break;
            case "August":
                $month = '8';
                break;
            case "September":
                $month = '9';
                break;
            case "October":
                $month = '10';
                break;
            case "November":
                $month = '11';
                break;
            case "December":
                $month = '12';
                break;
        }

        return $month;
    }

    /*
    | Numbers of working dates  for a month
    */
    public function totalWorkingDates($date_m,$date_y)
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

                }
        }

        return $workdays;

    }

    /* For employee dashboard */

    public function best_employees_for_current_month(){

        $date = date('Y-m');

        // Get best_employees_for_current_month by date
        $employees = $this->db->table('hrm_best_employees a')
                    ->select('a.*,b.first_name,b.last_name')
                    ->join('hrm_employees b','a.employee_id = b.employee_id','left')
                    ->where('date',$date)
                    ->get()->getResult();

        $respo_best_employees = array();

        $i = 0;

        foreach ($employees as $key => $value) {

            $best_employee = array();

            $best_employee['employee_name'] = $value->first_name.' '.$value->last_name;
            $best_employee['year'] =date('Y');

            $monthNum  = date('m');
            $monthName = date('F', mktime(0, 0, 0, $monthNum, 10));
            $best_employee['month_name'] = $monthName;

            $respo_best_employees[$i] = $best_employee;

            $i++;
        }

        return $respo_best_employees;
    }

    public function count_all_employees(){

        // Get count_all_employees
        $employees = $this->db->table('hrm_employees')
                    ->select('*')
                    ->countAllResults(false);

        return $employees;
    }

    public function curent_month_hired_employees(){

        $date_y = date('Y');
        $date_m = date('m');

        // Get count_all_employees
        $employees = $this->db->table('hrm_employees')
                    ->select('*')
                    ->where("YEAR(joining_date)=".$date_y,NULL, FALSE)
                    ->where("MONTH(joining_date)=".$date_m,NULL, FALSE)
                    ->countAllResults(false);

        // return $this->db->getLastQuery()->getQuery();

        return $employees;
    }

    /*
    | Numbers totalPresentEmployees
    */
    public function totalPresentEmployees($date_y,$date_m,$date_d)
    {

        $current_date = $date_y.'-'.$date_m.'-'.$date_d;

        // $att = "SELECT * FROM `hrm_attendance_history` WHERE atten_date = '" . $current_date . "' GROUP BY uid ORDER BY atten_date desc";
        // $query = $this->db->query($att)->getResult();

        // return count($query);

        $query = $this->db->table('hrm_attendance_history')
                    ->select('*')
                    ->where('atten_date',$current_date)
                    ->groupBy('uid')
                    ->get()->getResult();

        return count($query);

    }

    public function curent_month_birth_dates_employees(){

        $date_m = date('m');

        // Get count_all_employees
        $employees = $this->db->table('hrm_employees')
                    ->select('*')
                    ->where("MONTH(birth_date)=".$date_m,NULL, FALSE)
                    ->countAllResults(false);

        // return $this->db->getLastQuery()->getQuery();

        return $employees;
    }

    /*
    | Numbers totalPresentEmployees
    */
    public function get_all_departments()
    {

        $departments = $this->db->table('hrm_departments')
                    ->select('*')
                    ->orderBy('id','desc')
                    ->get()->getResult();

        return count($departments);

        // $respo_departments = array();

        // foreach ($departments as $key => $value) {

        //     $respo_departments[$value->id] = $value->name;
        // }

        // return $respo_departments;

    }

    // Start of functionalities for HRM Reports....from 22nd May 2022 ////

    public function count_all_active_employees(){

        // Get count_all_active_employees
        $active_employees = $this->db->table('hrm_employees')
                    ->select('*')
                    ->where('status',1)
                    ->countAllResults(false);

        return $active_employees;
    }

    public function employee_attendance_daily(){

        $date_y = date('Y');
        $date_m = date('m');

        $query = $this->db->table('hrm_attendance_history')
                    ->select('atten_date,count(DISTINCT(uid)) as total_present')
                    ->where("MONTH(atten_date)=".$date_m,NULL, FALSE)
                    ->where("YEAR(atten_date)=".$date_y,NULL, FALSE)
                    ->groupBy('atten_date')
                    ->get()->getResultArray();

        return $query;
     }

     public function emp_prev_month_avg_prsnt($employee_id = null){

        $date_y = date('Y');
        $date_m = date('m');
        $date_d = date('d');

        $date_prev_m = $date_m - 1;
        if($date_prev_m == 0){
            $date_y = $date_y - 1;
            $date_prev_m = 12;
        }
        $date = $date_y."-".$date_prev_m."-".$date_d;
        // Find work days elminating weekends, govt holidays
        $total_working_days = $this->totalWorkingDates($date_prev_m,$date_y);
        //Check present_days
        $present_days = $this->totalDaysWorkedForTheMonth($employee_id,$date_prev_m,$date_y);

        $average_presents = 0;
        if((int)$present_days <= count($total_working_days)){
            $average_presents = ((int)$present_days * 100) / count($total_working_days);
        }else{
            $average_presents = 100;
        }

        return $average_presents;
     }

     public function best_employees(){

        $date = date('Y-m');

        // Get best_employees_for_current_month by date
        $employees = $this->db->table('hrm_best_employees a')
                    ->select('a.employee_id,b.first_name,b.last_name')
                    ->join('hrm_employees b','a.employee_id = b.employee_id','left')
                    ->where('date',$date)
                    ->get()->getResult();

        $respo_arr = array();
        $i = 0;
        foreach ($employees as $key => $emp) {

            $temp_arr['employee_name'] = $emp->first_name.' '.$emp->last_name;
            $temp_arr['avg_present']   = $this->emp_prev_month_avg_prsnt($emp->employee_id);

            $respo_arr[$i] = $temp_arr;

            $i = $i + 1;
        }

        return $respo_arr;
     }

     public function get_department_wise_strength(){

        // Get best_employees_for_current_month by date
        $departments_respo = $this->db->table('hrm_departments')
                    ->select('id,name')
                    ->orderBy('name','asc')
                    ->get()->getResult();

        $departments = array();
        $male        = array();
        $female      = array();

        foreach ($departments_respo as $key => $department) {
            
            $departments[] = $department->name;
            // Male and Female employees count from hrm_employees table..
            $male_employees = $this->db->table('hrm_employees')->select('*')->where('gender','Male')->where('department',$department->id)->countAllResults(false);
            $male[] = $male_employees;

            $female_employees = $this->db->table('hrm_employees')->select('*')->where('gender','Female')->where('department',$department->id)->countAllResults(false);
            $female[] = $female_employees;

        }

        $respo_data['departments'] = $departments;
        $respo_data['male']        = $male;
        $respo_data['female']      = $female;

        return $respo_data;
     }

     public function get_designation_wise_strength_data(){

        // Get best_employees_for_current_month by date
        $designations_respo = $this->db->table('hrm_designation')
                    ->select('id,name')
                    ->orderBy('name','asc')
                    ->get()->getResult();

        $respo_arr = array();
        $i = 0;
        foreach ($designations_respo as $key => $designation) {

            // Male and Female employees count from hrm_employees table..
            $male_employees = $this->db->table('hrm_employees')->select('*')->where('gender','Male')->where('designation',$designation->id)->countAllResults(false);

            $female_employees = $this->db->table('hrm_employees')->select('*')->where('gender','Female')->where('designation',$designation->id)->countAllResults(false);

            $temp_arr['designation']       = $designation->name;
            $temp_arr['male_employees']   = $male_employees;
            $temp_arr['female_employees'] = $female_employees;
            $temp_arr['total_employees']  = (int)$male_employees + (int)$female_employees;

            $respo_arr[$i] = $temp_arr;

            $i = $i + 1;

        }

        return $respo_arr;
     }

     public function get_employee_type_wise_data(){

        // Get best_employees_for_current_month by date
        $employee_type_respo = $this->db->table('hrm_employee_types')
                    ->select('id,type')
                    ->orderBy('type','asc')
                    ->get()->getResult();

        $employee_types = array();
        $male           = array();
        $female         = array();

        foreach ($employee_type_respo as $key => $employee_type) {
            
            $employee_types[] = $employee_type->type;
            // Male and Female employees count from hrm_employees table..
            $male_employees = $this->db->table('hrm_employees')->select('*')->where('gender','Male')->where('employee_type',$employee_type->id)->countAllResults(false);
            $male[] = $male_employees;

            $female_employees = $this->db->table('hrm_employees')->select('*')->where('gender','Female')->where('employee_type',$employee_type->id)->countAllResults(false);
            $female[] = $female_employees;

        }

        $respo_data['employee_types'] = $employee_types;
        $respo_data['male']           = $male;
        $respo_data['female']         = $female;

        return $respo_data;
     }

     public function get_employee_status_wise_data(){

        $status_arr = array('Active' => 1, 'In Active' => 0);

        $employee_status = array();
        $male            = array();
        $female          = array();

        foreach ($status_arr as $key => $status) {
            
            $employee_status[] = $key;
            // Male and Female employees count from hrm_employees table..
            $male_employees = $this->db->table('hrm_employees')->select('*')->where('gender','Male')->where('status',$status)->countAllResults(false);
            $male[] = $male_employees;

            $female_employees = $this->db->table('hrm_employees')->select('*')->where('gender','Female')->where('status',$status)->countAllResults(false);
            $female[] = $female_employees;

        }

        $respo_data['employee_status'] = $employee_status;
        $respo_data['male']            = $male;
        $respo_data['female']          = $female;

        return $respo_data;
     }

     // get_employee_on_leave_data for the current month...
     public function get_employee_on_leave_data(){

        $date_y = date('Y');
        $date_m = date('m');
        // $date_m = 6;

        $condi1 = "MONTH(lap.leave_aprv_strt_date)='".$date_m."' AND YEAR(lap.leave_aprv_strt_date)='".$date_y."'";
        $condi2 = "MONTH(lap.leave_aprv_end_date)='".$date_m."' AND YEAR(lap.leave_aprv_end_date)='".$date_y."'";

        $result = $this->db->table('hrm_leave_application lap')
                    ->select('emp.first_name,emp.last_name,count(DISTINCT(lap.employee_id)) as total_employee, desig.name as designation_name, dept.name as department_name')
                    ->join('hrm_employees emp','emp.employee_id=lap.employee_id')
                    ->join('hrm_designation desig','desig.id=emp.designation')
                    ->join('hrm_departments dept','dept.id=emp.department')
                    ->where('lap.status', 1)
                    ->where($condi1)
                    ->orWhere($condi2)
                    ->groupBy('lap.employee_id')
                    ->get()->getResultArray();

        return $result;
     }

    // End of functionalities for HRM Reports....////

    // Ends



}