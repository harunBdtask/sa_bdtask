<?php namespace App\Modules\Human_resource\Controllers;
use CodeIgniter\Controller;
use App\Modules\Human_resource\Models\Bdtaskt1m6LeaveManagement;
use App\Modules\Human_resource\Models\Bdtaskt1m5Holidays;
use App\Modules\Human_resource\Models\Bdtaskt1m3Attendance;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1m9c5LeaveManagement extends BaseController
{
    private $bdtaskt1c1_06_LeaveManagement;
    private $bdtaskt1c1_01_CmModel;
    private $bdtaskt1c1_02_Holidays;
    private $bdtaskt1c1_03_AttenModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->db = db_connect();

        $this->permission = new Permission();
        $this->bdtaskt1c1_06_LeaveManagement = new Bdtaskt1m6LeaveManagement();
        $this->bdtaskt1c1_01_CmModel = new Bdtaskt1m1CommonModel();
        $this->bdtaskt1c1_02_Holidays = new Bdtaskt1m5Holidays();
        $this->bdtaskt1c1_03_AttenModel = new Bdtaskt1m3Attendance();

        $this->db = db_connect();

        $setting = $this->db->table('setting')->select('*')->get()->getRow();

        $timeZone = (!empty($setting->time_zone)?$setting->time_zone:'Asia/Dhaka');
        @date_default_timezone_set($timeZone);
    }


    /*--------------------------
    | leave_type List
    *--------------------------*/
    public function bdtask1c5_01_leave_type()
    {
        $data['title']      = get_phrases(['leave','types']);
        $data['moduleTitle']= get_phrases(['human', 'resource']);
        $data['isDTables']  = true;
        $data['module']     = "human_resource";
        $data['page']       = "leave/leave_types";

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | getBasicSalaryList
    *--------------------------*/
    public function bdtask1c5_02_leave_types_list()
    {

        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c1_06_LeaveManagement->bdtaskt1m4_01_leave_types_list($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | add_leave_type / Edit
    *--------------------------*/
    public function bdtask1c5_03_add_leave_type(){

        $MesTitle = get_phrases(['leave','type']);

        $id = $this->request->getVar('id');
        $action = $this->request->getVar('action');

        if($this->request->getVar('leave_type') == null || $this->request->getVar('leave_days') == null){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['please', 'select','all', 'required', 'fields']),
                'title'    => $MesTitle,
            );

            echo json_encode($response);exit;
        }

        $data = array(
            'leave_type' => $this->request->getVar('leave_type'),
            'leave_days' => $this->request->getVar('leave_days'),
        );

        // echo json_encode($action);exit;
       
        if($action=='update'){

            // Check, id similar name taking again as leave_type
            $leave_type_respo = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_leave_types', array('leave_type_id'=>$id));
            if($leave_type_respo->leave_type != $this->request->getVar('leave_type')){
                $duplicate_type = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_leave_types', array('leave_type'=>$this->request->getVar('leave_type')));
                if($duplicate_type){

                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['duplicate', 'leave','type']),
                        'title'    => $MesTitle,
                    );

                    echo json_encode($response);exit;
                }
            }

            $data['UpdateBy']   = session('id');
            $data['UpdateDate'] = date("Y-m-d h:i:s");

            // echo json_encode($data);exit;

            $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_02_Update('hrm_leave_types', $data, array('leave_type_id'=>$id));
            if($result){
                // Store log data
                $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['leave','type']), get_phrases(['updated']), $id, 'hrm_leave_types', 2);
                
                $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['updated', 'successfully']),
                    'title'    => $MesTitle,
                );
            }else{
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['something', 'went', 'wrong']),
                    'title'    => $MesTitle
                );
            }
        }else{

            // Check, id similar name taking again as leave_type
            $duplicate_type = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_leave_types', array('leave_type'=>$this->request->getVar('leave_type')));
            if($duplicate_type){

                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['duplicate', 'leave','type']),
                    'title'    => $MesTitle,
                );

                echo json_encode($response);exit;
            }

            $data['CreateBy']   = session('id');
            $data['CreateDate'] = date("Y-m-d h:i:s");

            // echo json_encode($data);exit;

            $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_01_Insert('hrm_leave_types', $data);
            if($result){
                // Store log data
                $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['leave','type']), get_phrases(['created']), $result, 'hrm_leave_types', 1);
                $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['created', 'successfully']),
                    'title'    => $MesTitle,
                );
            }else{
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['something', 'went', 'wrong']),
                    'title'    => $MesTitle
                );
            }
        }
        echo json_encode($response);

    }

    /*--------------------------
    | leave_type_by_Id
    *--------------------------*/
    public function bdtask1c5_04_leave_type_by_Id($id)
    {

        $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_leave_types', array('leave_type_id'=>$id));
        echo json_encode($result);
        exit();
    }

    /*--------------------------
    | delete_leave_type
    *--------------------------*/
    public function bdtask1c5_05_delete_leave_type($id)
    {

        $MesTitle = get_phrases(['leave', 'type']);

        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_06_Deleted('hrm_leave_types', array('leave_type_id'=>$id));

        if(!empty($data)){

            // Store log data
            $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['leave','type']), get_phrases(['deleted']), $id, 'hrm_leave_types', 3);

            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['deleted', 'successfully']),
                'title'    => $MesTitle
            );
            
        }else{
            $response = array(
                'success'  =>false,
                'message'  => (ENVIRONMENT == 'production')?get_phrases(['something', 'went', 'wrong']):get_db_error(),
                'title'    => $MesTitle
            );
        }
        echo json_encode($response);
    }


    /*--------------------------
    | CPL Leave List
    *--------------------------*/
    public function bdtask1c5_06_cpl_leave()
    {
        $data['title']      = get_phrases(['cpl','leave']);
        $data['moduleTitle']= get_phrases(['human', 'resource']);
        $data['isDTables']  = true;
        $data['module']     = "human_resource";
        $data['page']       = "leave/cpl_leave";

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | cpl_leave_list
    *--------------------------*/
    public function bdtask1c5_07_cpl_leave_list()
    {

        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c1_06_LeaveManagement->bdtaskt1m4_02_cpl_leave_list($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | cpl_leave_by_Id
    *--------------------------*/
    public function bdtask1c5_08_cpl_leave_by_Id($id)
    {

        $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_cpl_leave', array('id'=>$id));

        $emp_info = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_employees', array('employee_id'=>$result->employee_id));
        $result->employee_name = $emp_info->first_name.' '.$emp_info->last_name;
        
        echo json_encode($result);
        exit();
    }

    /*--------------------------
    | CPL Leave List
    *--------------------------*/
    public function bdtask1c5_09_earned_leave()
    {
        $data['title']      = get_phrases(['earned','leave']);
        $data['moduleTitle']= get_phrases(['human', 'resource']);
        $data['isDTables']  = true;
        $data['isDateTimes']= true;
        $data['module']     = "human_resource";
        $data['page']       = "leave/earned_leave";

        $data['employees']   = $this->bdtaskt1c1_06_LeaveManagement->bdtaskt1m4_04_getAllEmployees();

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | earned_leave_list
    *--------------------------*/
    public function bdtask1c5_10_earned_leave_list()
    {

        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c1_06_LeaveManagement->bdtaskt1m4_03_earned_leave_list($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | earned_leave_by_Id
    *--------------------------*/
    public function bdtask1c5_12_earned_leave_by_Id($id)
    {

        $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_earned_leave', array('id'=>$id));

        $emp_info = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_employees', array('employee_id'=>$result->employee_id));
        $result->employee_name = $emp_info->first_name.' '.$emp_info->last_name;
        
        echo json_encode($result);
        exit();
    }

    /*--------------------------
    | generate_earned_leave / Edit
    *--------------------------*/
    public function bdtask1c5_11_generate_earned_leave(){

        $MesTitle = get_phrases(['earned','leave']);

        $id = $this->request->getVar('id');
        $action = $this->request->getVar('action');

        // Validation starts
        if($this->request->getVar('employee_id') == null || $this->request->getVar('from_date') == null || $this->request->getVar('to_date') == null){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['please', 'select','all', 'required', 'fields']),
                'title'    => $MesTitle,
            );
            echo json_encode($response);exit;
        }
        if(strtotime($this->request->getVar('to_date')) <= strtotime($this->request->getVar('from_date'))){
            $response = array(
                'success'  =>false,
                'message'  => get_notify('from_date_must_be_greater_than_to_date'),
                'title'    => $MesTitle,
            );
            echo json_encode($response);exit;
        }
        // Check , if the from_date/start_date is of first janauary.... otherwise throw error, as it will require to calculate all earned leave for an employee from first janauary...otherwise it will create issue on leave applicaiton and it's calculation
        $start_date_d = date('d',strtotime($this->request->getVar('from_date')));
        $start_date_m = date('m',strtotime($this->request->getVar('from_date')));
        $start_date_y = date('Y',strtotime($this->request->getVar('from_date')));

        if((int)$start_date_d != 1 || (int)$start_date_m != 1){
            $response = array(
                'success'  =>false,
                'message'  => get_notify('from_date_should_be_first_of_january'),
                'title'    => $MesTitle,
            );
            echo json_encode($response);exit;
        }
        //Check, Start and end date must be of same year....
        $end_date_y = date('Y',strtotime($this->request->getVar('to_date')));
        if($start_date_y != $end_date_y){
            $response = array(
                'success'  =>false,
                'message'  => get_notify('from_and_to_date_year_should_be_same'),
                'title'    => $MesTitle,
            );
            echo json_encode($response);exit;
        }
        //Check,Earned leave already generated for the year for the requested employee, then end_date/to_date must be greater than the last request end_date/to_date
        $existingEarnedLeaveForTheYear = $this->bdtaskt1c1_06_LeaveManagement->bdtaskt1m4_05_existingEarnedLeaveForTheYear(array('employee_id'=>$this->request->getVar('employee_id'),'end_date'=>$this->request->getVar('to_date')));
        if($existingEarnedLeaveForTheYear != null){
            if(strtotime($this->request->getVar('to_date')) <= strtotime($existingEarnedLeaveForTheYear->end_date)){
                $response = array(
                    'success'  =>false,
                    'message'  => get_notify('to_date_must_be_greater_than_existing_generated_earned_leave_to_date_for_the_employee'),
                    'title'    => $MesTitle,
                );
                echo json_encode($response);exit;
            }
        }

        // Validation ends

        $data = array(
            'employee_id' => $this->request->getVar('employee_id'),
            'start_date'  => $this->request->getVar('from_date'),
            'end_date'    => $this->request->getVar('to_date'),
        );
        
        // Start calculating total_earned_leave///

        $all_dates = array();
        $start_date = strtotime($data['start_date']);
        $end_date = strtotime($data['end_date']);

        $weekends = explode(',', $this->bdtaskt1c1_02_Holidays->get_weekends()->weekend_days);
        // Increment by one day from start date to end date and calculation for earned leave
        $total_earned_leave = 0;
        $counter = 18;
        $k = 0;

        for ($currentDate = $start_date; $currentDate <= $end_date; $currentDate += (86400)) {
            $perdate = date('Y-m-d', $currentDate);
            // $all_dates[] = $perdate;

            $dayname = date("l",strtotime($perdate));
            $govt_holiday = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_15_check_govt_holiday_for_earned_leave($perdate); // Check, if fall in govt_holiday
            if(!in_array($dayname,$weekends) && !$govt_holiday){

                //Check , if the $perdate is in attendance_history, then increment $k by 1... otherwise set $k to 0 again...
                $check_attendance_by_date = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_16_check_attendance_by_date($perdate,$data['employee_id']);
                // $all_dates[$perdate] = $check_attendance_by_date;
                if($check_attendance_by_date){
                    $k = $k + 1;
                }else{
                    $k = 0;
                }
                // Check if $k is 18 or equal $counter value, then Set one earned leave
                if($k == $counter){
                    $total_earned_leave = $total_earned_leave + 1;
                    // Set $k to 0, as after 18 or equal $counter value, it will start calculating up to 18 or equal $counter value again
                    $k = 0;
                }

            }

        }
        //End
        $data['total_leave']   = $total_earned_leave;
        
        // echo json_encode($data);exit;

       
        if($action=='update'){

            $data['UpdateBy']   = session('id');
            $data['UpdateDate'] = date("Y-m-d h:i:s");

            //echo json_encode($data);exit;

            $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_02_Update('hrm_earned_leave', $data, array('id'=>$id));
            if($result){
                // Store log data
                $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['earned','leave']), get_phrases(['updated']), $id, 'hrm_earned_leave', 2);
                
                $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['updated', 'successfully']),
                    'title'    => $MesTitle,
                );
            }else{
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['something', 'went', 'wrong']),
                    'title'    => $MesTitle
                );
            }
        }else{

            // Check, if already earned leave generated for the employee for the year...
            if($existingEarnedLeaveForTheYear != null){
                $response = array(
                    'success'  =>false,
                    'message'  => get_notify('earned_leave_already_generated_for_the_employee'),
                    'title'    => $MesTitle,
                );
                echo json_encode($response);exit;
            }
            //End

            $data['CreateBy']   = session('id');
            $data['CreateDate'] = date("Y-m-d h:i:s");

            // echo json_encode($data);exit;

            $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_01_Insert('hrm_earned_leave', $data);
            if($result){
                // Store log data
                $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['earned','leave']), get_phrases(['created']), $result, 'hrm_earned_leave', 1);
                $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['created', 'successfully']),
                    'title'    => $MesTitle,
                );
            }else{
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['something', 'went', 'wrong']),
                    'title'    => $MesTitle
                );
            }
        }
        echo json_encode($response);

    }

    /*--------------------------
    | leave_approval
    *--------------------------*/
    public function bdtask1c5_13_leave_approval()
    {
        $data['title']      = get_phrases(['leave','approval']);
        $data['moduleTitle']= get_phrases(['human', 'resource']);
        $data['isDTables']  = true;
        $data['isDateTimes']= true;
        $data['module']     = "human_resource";
        $data['page']       = "leave/leave_approval";

        $data['employees']   = $this->bdtaskt1c1_06_LeaveManagement->bdtaskt1m4_04_getAllEmployees();
        $data['leave_types']   = $this->bdtaskt1c1_06_LeaveManagement->bdtaskt1m4_06_getAllLeaveTypes();

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);
    }




    /*--------------------------
    | calculateDays
    *--------------------------*/
    public function bdtask1c5_18_calculateDays()
    {
        $MesTitle = get_phrases(['leave','application']);

        if($this->request->getVar('apply_strt_date') && $this->request->getVar('apply_end_date')){

            $apply_strt_date = $this->request->getVar('apply_strt_date');
            $apply_end_date = $this->request->getVar('apply_end_date');

            if($apply_strt_date > $apply_end_date){

                $response = array(
                    'success'  =>false,
                    'message'  => get_notify('apply_start_date_should_be_smaller_than_end_date'),
                    'title'    => $MesTitle,
                );
                echo json_encode($response);exit;

            }else{

                //Elminating weekends and govt holidays
                $start_date = strtotime($apply_strt_date);
                $end_date = strtotime($apply_end_date);

                $weekends = explode(',', $this->bdtaskt1c1_02_Holidays->get_weekends()->weekend_days);
                $days = 0;

                for ($currentDate = $start_date; $currentDate <= $end_date; $currentDate += (86400)) {
                    $perdate = date('Y-m-d', $currentDate);

                    $dayname = date("l",strtotime($perdate));
                    $govt_holiday = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_17_check_govt_holiday_for_leave_application($perdate); // Check, if fall in govt_holiday
                    if(!in_array($dayname,$weekends) && !$govt_holiday){

                        $days = $days + 1;
                    }
                }
                //Ends

                $response = array(
                    'success'  =>true,
                    'days'    =>  $days,
                );

                echo json_encode($response);exit;
            }

            // echo json_encode($response);
        }
    }

    public function bdtask1c5_19_calculateDaysOthers()
    {
        $MesTitle = get_phrases(['leave','application']);

        if($this->request->getVar('leave_aprv_strt_date') && $this->request->getVar('leave_aprv_end_date')){

            $leave_aprv_strt_date = $this->request->getVar('leave_aprv_strt_date');
            $leave_aprv_end_date = $this->request->getVar('leave_aprv_end_date');

            if($leave_aprv_strt_date > $leave_aprv_end_date){

                $response = array(
                    'success'  =>false,
                    'message'  => get_notify('leave_approve_start_date_should_be_smaller_than_end_date'),
                    'title'    => $MesTitle,
                );
                echo json_encode($response);exit;

            }else{

                //Elminating weekends and govt holidays
                $start_date = strtotime($leave_aprv_strt_date);
                $end_date = strtotime($leave_aprv_end_date);

                $weekends = explode(',', $this->bdtaskt1c1_02_Holidays->get_weekends()->weekend_days);
                $days = 0;

                for ($currentDate = $start_date; $currentDate <= $end_date; $currentDate += (86400)) {
                    $perdate = date('Y-m-d', $currentDate);

                    $dayname = date("l",strtotime($perdate));
                    $govt_holiday = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_17_check_govt_holiday_for_leave_application($perdate); // Check, if fall in govt_holiday
                    if(!in_array($dayname,$weekends) && !$govt_holiday){

                        $days = $days + 1;
                    }
                }
                //Ends

                $response = array(
                    'success'  =>true,
                    'days'    =>  $days,
                );

                echo json_encode($response);exit;
            }

            // echo json_encode($response);
        }
    }

    /*--------------------------
    | create_leave_approval / Edit... this is the final approval after superior approval
    *--------------------------*/
    public function bdtask1c5_15_create_leave_approval(){

        $MesTitle = get_phrases(['leave','approval']);

        $id = $this->request->getVar('id');
        $action = $this->request->getVar('action');

        $leave_application_info = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_leave_application', array('leave_appl_id'=>$id));

        if($this->request->getVar('leave_aprv_strt_date') == null || $this->request->getVar('leave_aprv_end_date') == null || $this->request->getVar('status') == null){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['please', 'select','all', 'required', 'fields']),
                'title'    => $MesTitle,
            );

            echo json_encode($response);exit;
        }

        // Check if superior end approval is done , then allow to od the final approval
        if($leave_application_info->superior_approval == 0){

            $response = array(
                'success'  =>false,
                'message'  => get_notify('waiting_for_superior_approval').' !',
                'title'    => $MesTitle,
            );

            echo json_encode($response);exit;
        }
        if($leave_application_info->superior_approval == 2){

            $response = array(
                'success'  =>false,
                'message'  => get_notify('application_already_rejected_by_applicant_superior').' !',
                'title'    => $MesTitle,
            );

            echo json_encode($response);exit;
        }
        // End

        $data = array(
            'leave_aprv_strt_date' => $this->request->getVar('leave_aprv_strt_date'),
            'leave_aprv_end_date'  => $this->request->getVar('leave_aprv_end_date'),
            'status'               => (int)$this->request->getVar('status'),
            'reason'               => $this->request->getVar('reason'),
        );

        // Applied and numbers of approved day calculations

        $applied_days = $this->bdtask1c5_20_leaveDays($leave_application_info->apply_strt_date,$leave_application_info->apply_end_date);
        $approved_days = $this->bdtask1c5_20_leaveDays($this->request->getVar('leave_aprv_strt_date'),$this->request->getVar('leave_aprv_end_date'));

        //$data['apply_day']    = $applied_days;
        $data['num_aprv_day'] = $approved_days;

        // Get all the leave for the selected employee
        $leave_from_leave_types  = $this->bdtaskt1c1_06_LeaveManagement->get_leave_from_leave_types();
        $leave_from_cpl_leave    = $this->bdtaskt1c1_06_LeaveManagement->get_leave_from_cpl_leave($leave_application_info->employee_id,$data['leave_aprv_strt_date']);
        $leave_from_earned_leave = $this->bdtaskt1c1_06_LeaveManagement->get_leave_from_earned_leave($leave_application_info->employee_id,$data['leave_aprv_strt_date']);

        $total_leave = (int)$leave_from_leave_types + (int)$leave_from_cpl_leave + (int)$leave_from_earned_leave;

        //Ends

        // Now get that, if the employee already has taken any leave for the running year.. then compare with the total leave, and if remain leave then allow... otherwise give warning message....
        $already_used_leave  = $this->bdtaskt1c1_06_LeaveManagement->get_already_used_leave($leave_application_info->employee_id,$data['leave_aprv_strt_date']);
        $total_leve_used_for_employee = $already_used_leave + (int)$approved_days;

        //Ends

        // echo json_encode($already_used_leave);exit;
       
        if($action=='update'){

            // Check, if the approved leave is increased... then also check that much leave is available or not.....
            if($approved_days > $leave_application_info->num_aprv_day){

                // $leave_increased = (int)$approved_days - (int)$leave_application_info->num_aprv_day;
                // $new_used_leave  = (int)$already_used_leave + (int)$approved_days;
                $new_used_and_apprved_leave  = (int)$already_used_leave + (int)$approved_days;

                // Check if all leave already used..
                if($total_leave < $new_used_and_apprved_leave){
                    $leave_ramaining = $total_leave - $already_used_leave;

                    $response = array(
                        'success'  =>false,
                        'message'  => "There is ".$leave_ramaining." leave remaining for the employee !",
                        'title'    => $MesTitle,
                    );
                    echo json_encode($response);exit;
                }
            }
            //Ends
            // $data['apply_date']    = date("Y-m-d");
            $data['approve_date'] = date("Y-m-d");
            $data['approved_by'] = session('id');

            $data['UpdateBy']   = session('id');
            $data['UpdateDate'] = date("Y-m-d h:i:s");

            // echo json_encode($data);exit;

            $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_02_Update('hrm_leave_application', $data, array('leave_appl_id'=>$id));
            if($result){
                // Store log data
                $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['leave','approval']), get_phrases(['updated']), $id, 'hrm_leave_application', 2);
                
                $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['updated', 'successfully']),
                    'title'    => $MesTitle,
                );
            }else{
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['something', 'went', 'wrong']),
                    'title'    => $MesTitle
                );
            }

        }else{

            // // Check if all leave already used..
            // if($total_leave < $total_leve_used_for_employee){
            //     $leave_ramaining = $total_leave - $already_used_leave;

            //     $response = array(
            //         'success'  =>false,
            //         'message'  => "There is ".$leave_ramaining." leave remaining for the employee !",
            //         'title'    => $MesTitle,
            //     );
            //     echo json_encode($response);exit;
            // }
            // //Ends

            // $data['apply_date']    = date("Y-m-d");
            // $data['approve_date'] = date("Y-m-d");
            // $data['approved_by'] = session('id');
            // $data['status'] = 1;

            // $data['CreateBy']   = session('id');
            // $data['CreateDate'] = date("Y-m-d h:i:s");

            // // echo json_encode($data);exit;

            // $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_01_Insert('hrm_leave_application', $data);
            // if($result){
            //     // Store log data
            //     $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['leave','approval']), get_phrases(['created']), $result, 'hrm_leave_application', 1);
            //     $response = array(
            //         'success'  =>true,
            //         'message'  => get_phrases(['created', 'successfully']),
            //         'title'    => $MesTitle,
            //     );
            // }else{
            //     $response = array(
            //         'success'  =>false,
            //         'message'  => get_phrases(['something', 'went', 'wrong']),
            //         'title'    => $MesTitle
            //     );
            // }

        }
        echo json_encode($response);

    }


    /*--------------------------
    | delete_leave_application
    *--------------------------*/
    public function bdtask1c5_17_delete_leave_application($id)
    {

        $MesTitle = get_phrases(['leave','application']);

        $leave_application_info = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_leave_application', array('leave_appl_id'=>$id));

        //Starts validation for leave application 
        if($leave_application_info->status == 1){

            $response = array(
                'success'  =>false,
                'message'  => "Can not delete as your leave application is already approved !",
                'title'    => $MesTitle,
            );
            echo json_encode($response);exit;
        }
        if($leave_application_info->status == 2){

            $response = array(
                'success'  =>false,
                'message'  => "Can not delete as your leave application is already rejected !",
                'title'    => $MesTitle,
            );
            echo json_encode($response);exit;
        }
        if($leave_application_info->superior_approval == 1){

            $response = array(
                'success'  =>false,
                'message'  => "Can not delete as your leave application is already approved by your superior !",
                'title'    => $MesTitle,
            );
            echo json_encode($response);exit;
        }
        if($leave_application_info->superior_approval == 2){

            $response = array(
                'success'  =>false,
                'message'  => "Can not delete as your leave application is already rejected by your superior !",
                'title'    => $MesTitle,
            );
            echo json_encode($response);exit;
        }
        //Ends

        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_06_Deleted('hrm_leave_application', array('leave_appl_id'=>$id));

        if(!empty($data)){

            // Store log data
            $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['leave','application']), get_phrases(['deleted']), $id, 'hrm_leave_application', 3);

            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['deleted', 'successfully']),
                'title'    => $MesTitle
            );
            
        }else{
            $response = array(
                'success'  =>false,
                'message'  => (ENVIRONMENT == 'production')?get_phrases(['something', 'went', 'wrong']):get_db_error(),
                'title'    => $MesTitle
            );
        }
        echo json_encode($response);
    }

    /*--------------------------
    | delete_leave_application by final approver like hr or role based user
    *--------------------------*/
    public function bdtask1c5_26_del_lve_apli_by_final_approv($id)
    {

        $MesTitle = get_phrases(['leave','application']);

        $leave_application_info = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_leave_application', array('leave_appl_id'=>$id));

        //Starts validation for leave application 
        if($leave_application_info->status == 1){

            $response = array(
                'success'  =>false,
                'message'  => "Can not delete as your leave application is already approved !",
                'title'    => $MesTitle,
            );
            echo json_encode($response);exit;
        }
        if($leave_application_info->status == 2){

            $response = array(
                'success'  =>false,
                'message'  => "Can not delete as your leave application is already rejected !",
                'title'    => $MesTitle,
            );
            echo json_encode($response);exit;
        }
        //Ends

        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_06_Deleted('hrm_leave_application', array('leave_appl_id'=>$id));

        if(!empty($data)){

            // Store log data
            $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['leave','application']), get_phrases(['deleted']), $id, 'hrm_leave_application', 3);

            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['deleted', 'successfully']),
                'title'    => $MesTitle
            );
            
        }else{
            $response = array(
                'success'  =>false,
                'message'  => (ENVIRONMENT == 'production')?get_phrases(['something', 'went', 'wrong']):get_db_error(),
                'title'    => $MesTitle
            );
        }
        echo json_encode($response);
    }


    /* Employee leave application starts*/

    /*--------------------------
    | leave_application
    *--------------------------*/
    public function bdtask1c5_20_leave_application()
    {
        $data['title']      = get_phrases(['leave','application']);
        $data['moduleTitle']= get_phrases(['human', 'resource']);
        $data['isDTables']  = true;
        $data['isDateTimes']= true;
        $data['module']     = "human_resource";
        $data['page']       = "leave/leave_application";
        $data['employee_id'] = session('id');

        $data['employees']   = $this->bdtaskt1c1_06_LeaveManagement->bdtaskt1m4_04_getAllEmployees();
        $data['leave_types']   = $this->bdtaskt1c1_06_LeaveManagement->bdtaskt1m4_06_getAllLeaveTypes();

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | emp_leave_application_list
    *--------------------------*/
    public function bdtask1c5_21_emp_leave_application_list()
    {

        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c1_06_LeaveManagement->bdtaskt1m4_05_emp_leave_application_list($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | create_leave_application / Edit
    *--------------------------*/
    public function bdtask1c5_22_create_leave_application(){

        $MesTitle = get_phrases(['leave','application']);

        // echo "<pre>";
        // print_r($this->request->getVar());
        // exit;

        $id = $this->request->getVar('id');
        $action = $this->request->getVar('action');

        if($this->request->getVar('leave_type') == null || $this->request->getVar('apply_strt_date') == null || $this->request->getVar('apply_end_date') == null){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['please', 'select','all', 'required', 'fields']),
                'title'    => $MesTitle,
            );

            echo json_encode($response);exit;
        }

        $file_path = '';

        // Upload image file and File type check and show message for invalid image file..

        if(isset($_FILES['application_hard_copy']['name']) && !empty($_FILES['application_hard_copy']['name'])){

            $path = $_FILES['application_hard_copy']['name'];
            $nid_ext = pathinfo($path, PATHINFO_EXTENSION);
            $is_valid_id_file = $this->file_type($nid_ext);

            if(!$is_valid_id_file){

               $this->session->setFlashdata('exception', get_phrases(['invalid','file','type']));
               return  redirect()->to(base_url('human_resources/leave_management/leave_application'));
            }

            $file_path = $this->base_01_fileUpload->doUpload('./assets/dist/documents/leave/', $this->request->getFile('application_hard_copy'));

        }

        $data = array(
            'employee_id'          => session('id'),
            'leave_type'           => $this->request->getVar('leave_type'),
            'apply_strt_date'      => $this->request->getVar('apply_strt_date'),
            'apply_end_date'       => $this->request->getVar('apply_end_date'),
            'reason'               => $this->request->getVar('reason'),
            'apply_hard_copy'      =>  (($file_path !='/')?str_replace("./","/",$file_path):''),
        );

        //echo json_encode($data);exit;

        // Applied and numbers of approved day calculations

        $applied_days = $this->bdtask1c5_20_leaveDays($this->request->getVar('apply_strt_date'),$this->request->getVar('apply_end_date'));
        $approved_days = $this->bdtask1c5_20_leaveDays($this->request->getVar('leave_aprv_strt_date'),$this->request->getVar('leave_aprv_end_date'));

        $data['apply_day']    = $applied_days;
        $approved_days = 0;
        // $data['num_aprv_day'] = $approved_days;

        // Get all the leave for the selected employee
        $leave_from_leave_types  = $this->bdtaskt1c1_06_LeaveManagement->get_leave_from_leave_types();
        $leave_from_cpl_leave    = $this->bdtaskt1c1_06_LeaveManagement->get_leave_from_cpl_leave($data['employee_id'],$data['apply_strt_date']);
        $leave_from_earned_leave = $this->bdtaskt1c1_06_LeaveManagement->get_leave_from_earned_leave($data['employee_id'],$data['apply_strt_date']);

        $total_leave = (int)$leave_from_leave_types + (int)$leave_from_cpl_leave + (int)$leave_from_earned_leave;

        //Ends

        // Now get that, if the employee already has taken any leave for the running year.. then compare with the total leave, and if remain leave then allow... otherwise give warning message....
        $already_used_leave  = $this->bdtaskt1c1_06_LeaveManagement->get_already_used_leave($data['employee_id'],$data['apply_strt_date']);
        $total_leve_used_for_employee = $already_used_leave + (int)$approved_days;

        //Ends

        // Check if all leave already used..
        if($total_leave < $total_leve_used_for_employee || $total_leave < $applied_days){
            $leave_ramaining = $total_leave - $already_used_leave;

            $response = array(
                'success'  =>false,
                'message'  => "Total ".$leave_ramaining." leave remaining for the employee !",
                'title'    => $MesTitle,
            );
            echo json_encode($response);exit;
        }
        //Ends

        // Employee info for superior
        $employee_info = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_employees', array('employee_id'=>session('id')));

        // echo json_encode($approved_days);exit;
       
        if($action=='update'){

            $leave_application_info = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_leave_application', array('leave_appl_id'=>$id));

            // Check if the leave application already approved by either superior or hr, then not allow to update...
            if($leave_application_info->superior_approval == 1){
                $response = array(
                    'success'  =>false,
                    'message'  => "Your leave is already approved by your superior !",
                    'title'    => $MesTitle,
                );
                echo json_encode($response);exit;

            }
            if($leave_application_info->superior_approval == 2){
                $response = array(
                    'success'  =>false,
                    'message'  => "Your leave is rejected by your superior !",
                    'title'    => $MesTitle,
                );
                echo json_encode($response);exit;

            }
            if($leave_application_info->status == 1){
                $response = array(
                    'success'  =>false,
                    'message'  => "Your leave is approved !",
                    'title'    => $MesTitle,
                );
                echo json_encode($response);exit;

            }
            if($leave_application_info->status == 2){
                $response = array(
                    'success'  =>false,
                    'message'  => "Your leave is rejected !",
                    'title'    => $MesTitle,
                );
                echo json_encode($response);exit;

            }
            // Ends

            $data['UpdateBy']   = session('id');
            $data['UpdateDate'] = date("Y-m-d h:i:s");

            // echo json_encode($data);exit;

            $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_02_Update('hrm_leave_application', $data, array('leave_appl_id'=>$id));
            if($result){
                // Store log data
                $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['leave','application']), get_phrases(['updated']), $id, 'hrm_leave_application', 2);
                
                $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['updated', 'successfully']),
                    'title'    => $MesTitle,
                );
            }else{
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['something', 'went', 'wrong']),
                    'title'    => $MesTitle
                );
            }

        }else{

            $data['superior_id']= $employee_info->superior;

            $data['apply_date'] = date("Y-m-d");
            $data['CreateBy']   = session('id');
            $data['CreateDate'] = date("Y-m-d h:i:s");

            // echo json_encode($data);exit;

            $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_01_Insert('hrm_leave_application', $data);
            if($result){
                // Store log data
                $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['leave','application']), get_phrases(['created']), $result, 'hrm_leave_application', 1);
                $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['created', 'successfully']),
                    'title'    => $MesTitle,
                );
            }else{
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['something', 'went', 'wrong']),
                    'title'    => $MesTitle
                );
            }
        }
        echo json_encode($response);

    }


    /*Employee leave application ends*/

    /* Employee superior leave approval starts*/

    /*--------------------------
    | leave_application
    *--------------------------*/
    public function bdtask1c5_23_superior_approval()
    {
        $data['title']      = get_phrases(['superior','approval']);
        $data['moduleTitle']= get_phrases(['human', 'resource']);
        $data['isDTables']  = true;
        $data['isDateTimes']= true;
        $data['module']     = "human_resource";
        $data['page']       = "leave/superior_approval";
        $data['employee_id'] = session('id');

        $data['employees']   = $this->bdtaskt1c1_06_LeaveManagement->bdtaskt1m4_04_getAllEmployees();
        $data['leave_types']   = $this->bdtaskt1c1_06_LeaveManagement->bdtaskt1m4_06_getAllLeaveTypes();

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | superior_leave_application_list
    *--------------------------*/
    public function bdtask1c5_24_superior_leave_application_list()
    {

        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c1_06_LeaveManagement->bdtaskt1m4_06_superior_leave_approval_list($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | update_leave_application_superior
    *--------------------------*/
    public function bdtask1c5_25_update_leave_application_superior(){

        $MesTitle = get_phrases(['superior','leave','approval']);

        // echo "<pre>";
        // print_r($this->request->getVar());
        // exit;

        $id = $this->request->getVar('id');
        $action = $this->request->getVar('action');

        if($this->request->getVar('superior_approval') == null){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['please', 'select','superior','approval']),
                'title'    => $MesTitle,
            );

            echo json_encode($response);exit;
        }

        $data = array(
            'superior_approval'      => $this->request->getVar('superior_approval'),
            'superior_approval_rsn'   => $this->request->getVar('superior_reason'),
        );

        //echo json_encode($data);exit;
       
        if($action=='update'){

            $leave_application_info = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_leave_application', array('leave_appl_id'=>$id));

            // Check if the leave application already approved by either superior or hr, then not allow to update...
            if($leave_application_info->status == 1){
                $response = array(
                    'success'  =>false,
                    'message'  => "Can not update further as it's already apprved in final stage !",
                    'title'    => $MesTitle,
                );
                echo json_encode($response);exit;

            }
            if($leave_application_info->status == 2){
                $response = array(
                    'success'  =>false,
                    'message'  => "Can not update further as it's already rejected in final stage !",
                    'title'    => $MesTitle,
                );
                echo json_encode($response);exit;

            }
            // Ends

            $data['UpdateBy']   = session('id');
            $data['UpdateDate'] = date("Y-m-d h:i:s");

            // echo json_encode($data);exit;

            $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_02_Update('hrm_leave_application', $data, array('leave_appl_id'=>$id));
            if($result){
                // Store log data
                $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['leave','application']), get_phrases(['updated']), $id, 'hrm_leave_application', 2);
                
                $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['updated', 'successfully']),
                    'title'    => $MesTitle,
                );
            }else{
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['something', 'went', 'wrong']),
                    'title'    => $MesTitle
                );
            }

        }

        echo json_encode($response);

    }


     /* Employee superior leave approval ends*/


    /*   
        file_type validation check
    */
    public function file_type($type = ""){

        $file_types = ['pdf','doc','docx','jpg','jpeg','png'];

        if(in_array($type, $file_types)){

            return true;

        }else{

            return false;
        }
    }

    public function bdtask1c5_20_leaveDays($leave_strt_date,$leave_end_date)
    {
        //Elminating weekends and govt holidays
        $start_date = strtotime($leave_strt_date);
        $end_date = strtotime($leave_end_date);

        $weekends = explode(',', $this->bdtaskt1c1_02_Holidays->get_weekends()->weekend_days);
        $days = 0;

        for ($currentDate = $start_date; $currentDate <= $end_date; $currentDate += (86400)) {
            $perdate = date('Y-m-d', $currentDate);

            $dayname = date("l",strtotime($perdate));
            $govt_holiday = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_17_check_govt_holiday_for_leave_application($perdate); // Check, if fall in govt_holiday
            if(!in_array($dayname,$weekends) && !$govt_holiday){

                $days = $days + 1;
            }
        }
        //Ends

        return $days;
    }


    /*--------------------------
    | leave_application_list
    *--------------------------*/
    public function bdtask1c5_14_leave_application_list()
    {

        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c1_06_LeaveManagement->bdtaskt1m4_04_leave_application_list($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | leave_application_by_Id
    *--------------------------*/
    public function bdtask1c5_16_leave_application_by_Id($id)
    {

        $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_leave_application', array('leave_appl_id'=>$id));

        $emp_info = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_employees', array('employee_id'=>$result->employee_id));
        $result->employee_name = $emp_info->first_name.' '.$emp_info->last_name;
        
        echo json_encode($result);
        exit();
    }

   
}
