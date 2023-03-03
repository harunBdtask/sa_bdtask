<?php namespace App\Modules\Human_resource\Controllers;
use CodeIgniter\Controller;
use App\Modules\Human_resource\Models\Bdtaskt1m3Attendance;
use App\Modules\Human_resource\Models\Bdtaskt1m5Holidays;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

use App\Modules\Human_resource\Models\Bdtaskt1m4AttendanceLogs;

class Bdtaskt1m9c2Attendances extends BaseController
{
    private $bdtaskt1c1_03_AttenModel;
    private $bdtaskt1c1_02_CmModel;

    private $langColumn;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1c1_03_AttenModel = new Bdtaskt1m3Attendance();
        $this->bdtaskt1c1_02_CmModel = new Bdtaskt1m1CommonModel();
        $this->bdtaskt1c1_02_Holidays = new Bdtaskt1m5Holidays();

        $this->db = db_connect();

        $setting = $this->db->table('setting')->select('*')->get()->getRow();

        $timeZone = (!empty($setting->time_zone)?$setting->time_zone:'Asia/Dhaka');
        @date_default_timezone_set($timeZone);
    }

    public function index(){

        $data['title']      = get_phrases(['attendance','form']);
        $data['moduleTitle']= get_phrases(['human','resource']);
        $data['isDateTimes']= true;
        $data['module']     = "Human_resource";
        $data['page']       = "attendance/create_attendance";

        $data['employees']   = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_01_getAllEmployees();

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);
    }

    public function bdtaskt1c2_01_empList(){

        $lang = session('defaultLang')=='english'?'nameE':'nameA';
        $column = ['emp_id as id', $lang.' as text'];
        $where = array('status'=>1);
        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_07_getSelect2Data('employees', $where, $column);
        echo json_encode($data);
    }

    /*--------------------------
    | save attn info
    *--------------------------*/
    public function bdtaskt1c2_02_attn_create()
    { 
        $MesTitle = get_phrases(['attendance']);

        $db = db_connect();

        $atten_date = $this->request->getVar('atten_date'); //For which date i am taking attendance
        $uid        = $this->request->getVar('uid');
        $time       = $this->request->getVar('time');

        // Validation starts

        if($atten_date == "" || $uid == "" || $time == ""){

            $response = array(
                'success'  => false,
                'message'  => get_notify('please_fill_up_all_required_fields'),
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;
        }

        //check, if attaendance date is larger than the punch  time, then trrow error
        if(strtotime($atten_date) > strtotime($time)){

            $response = array(
                'success'  => false,
                'message'  => get_notify('attendance_date_should_be_smaller_than_punch_time'),
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;
        }

        //Check, if punchtime is greater than the next day comparing to attendnace date.. then throw error
        $next_day_date = strtotime("2 day", strtotime($atten_date));
        $next_day = date("Y-m-d", $next_day_date);

        $punch_time_date = date("Y-m-d", strtotime($time));

        if(strtotime($punch_time_date) >= strtotime($next_day)){

            $response = array(
                'success'  => false,
                'message'  => get_notify('punch_time_should_be_in_between_next_day_comparing_with_attendance_date'),
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;
        }

        //Ends

        // base data
        $data = array(
            'atten_date'  => $this->request->getVar('atten_date'),
            'uid'         => $this->request->getVar('uid'), 
            'time'        => $this->request->getVar('time'),  
            'state'       => 1
        );
        
        //Check , if the time is duplicate or not
        $existingTime = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_attendance_history', array('uid'=>$this->request->getVar('uid'),'time'=>$this->request->getVar('time'),'atten_date'=>$this->request->getVar('atten_date')));
        if($existingTime){

            $response = array(
                'success'  =>"exist",
                'message'  => get_phrases(['duplicate', 'entry']),
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;
        }

        /////////////////////////////////////////////////////////////////////////////////
        // $date     = date('Y-m-d',strtotime($this->request->getVar('atten_date')));
        // $signin   = date("h:i:s a",strtotime($this->request->getVar('time')));
        // $sin_time = date("H:i",strtotime($this->request->getVar('time')));
        // $emp_id   = $this->request->getVar('uid');
        // $time   = $this->request->getVar('time');

        // $query = "SELECT * FROM hrm_emproster_assign WHERE cast(concat(emp_startroster_date, ' ', emp_startroster_time) as datetime) <= '$time' AND cast(concat(emp_endroster_date, ' ', emp_endroster_time) as datetime) >= '$time' AND emp_startroster_date = '$date' AND emp_id = '$emp_id'";

        // // echo $query;

        // $query_respo = $this->db->query($query)->getRow();

        // $last_query = $this->db->getLastQuery()->getQuery();

        // echo json_encode($query_respo);
        // exit;
        /////////////////////////////////////////////////////////////////////////////////
        
        if($data){

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('hrm_attendance_history',$data);
            
            if($result){

                /// ****** CPL Leave section starts ******//
                $cplLeave = $this->bdtask1c1_23_cplLeave($data);
                /// ******CPL Leave section Ends ******///

                // ******Note : Here need to be sure.. if any employee is unchecked from duty roster, who is already in duty roster .. we need to remove his/her history from employeeroster_assign table for upcoming days from current_day
                
                // Update hrm_emproster_assign table for the emp_is as ateended means is_complete = 1
                $date     = date('Y-m-d',strtotime($this->request->getVar('atten_date')));
                $signin   = date("h:i:s a",strtotime($this->request->getVar('time')));
                $sin_time = date("H:i",strtotime($this->request->getVar('time')));
                $emp_id   = $this->request->getVar('uid');
                $time     = $this->request->getVar('time');

                if ($this->db->tableExists('hrm_emproster_assign')) {

                    $is_complete = array('is_complete'=>1);

                    $query = "SELECT * FROM hrm_emproster_assign WHERE cast(concat(emp_startroster_date, ' ', emp_startroster_time) as datetime) <= '$time' AND cast(concat(emp_endroster_date, ' ', emp_endroster_time) as datetime) >= '$time' AND emp_startroster_date = '$date' AND emp_id = '$emp_id'";

                    $query_respo = $this->db->query($query)->getRow();

                    // echo json_encode($query_respo);
                    // exit;

                    if($query_respo){

                        $builder3 = $this->db->table('hrm_emproster_assign');
                        $builder3->where('sftasnid',$query_respo->sftasnid);
                        $upRes = $builder3->update($is_complete);
                    }
                }
                // End of Update hrm_emproster_assign table for the emp_is as ateended means is_complete = 1

                $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['created', 'successfully']),
                    'title'    => $MesTitle
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

    // public function bdtaskt1c2_03_attendance_log()
    // {
    //     $request = service('request');
    //     $searchData = $request->getGet();

    //     // Get data 
    //     $attn_logs = new Bdtaskt1m4AttendanceLogs();

    //     $paginateData =$attn_logs->select('*,DATE(time) as mydate')
    //             ->groupBy("mydate")
    //             ->orderBy('time','DESC')         
    //             ->paginate(1);

    //     $data = [
    //         'attn_logs' => $paginateData,
    //         'pager' => $attn_logs->pager
    //     ];

    //     $data['title']      = get_phrases(['attendance','logs']);
    //     $data['moduleTitle']= get_phrases(['human','resource']);
    //     $data['isDateTimes']= true;
    //     $data['module']     = "Human_resource";
    //     $data['page']       = "attendance/attendance_logs";

    //     $data['employees']   = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_01_getAllEmployees();

    //     return $this->base_02_template->layout($data);
    // }

    public function bdtaskt1c2_03_attendance_log()
    {
        $request = service('request');
        $searchData = $request->getGet();

        // Get data 
        $attn_logs = new Bdtaskt1m4AttendanceLogs();

        $paginateData =$attn_logs->select('*,DATE(atten_date) as mydate')
                ->groupBy("mydate")
                ->orderBy('time','DESC')         
                ->paginate(1);

        $data = [
            'attn_logs' => $paginateData,
            'pager' => $attn_logs->pager
        ];

        // echo "<pre>";
        // print_r($data);
        // exit;

        $data['title']      = get_phrases(['attendance','logs']);
        $data['moduleTitle']= get_phrases(['human','resource']);
        $data['isDateTimes']= true;
        $data['module']     = "Human_resource";
        $data['page']       = "attendance/attendance_logs";

        $data['employees']   = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_01_getAllEmployees();

        return $this->base_02_template->layout($data);
    }

    // public function bdtaskt1c2_04_user_attendanc_details($uid)
    // {

    //     // Get data 
    //     $attn_logs = new Bdtaskt1m4AttendanceLogs();

    //     $paginateData =$attn_logs->select('*,DATE(time) as mydate')
    //             ->where('uid',$uid)
    //             ->where('branch_id',session('branchId'))
    //             ->groupBy("mydate")
    //             ->orderBy('time','DESC')         
    //             ->paginate(2);

    //     $data = [
    //         'attn_logs' => $paginateData,
    //         'pager' => $attn_logs->pager
    //     ];

    //     $data['company'] = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_03_getSetting();
    //     $data['user']  = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_02_getEmployee($uid);
    //     $data['id'] = $uid;

    //     $data['title']      = get_phrases(['attendance','logs']);
    //     $data['moduleTitle']= get_phrases(['human','resource']);
    //     $data['isDateTimes']= true;
    //     $data['module']     = "Human_resource";
    //     $data['page']       = "attendance/attendance_log_userdetails";
        
    //     return $this->base_02_template->layout($data);
    // }


    public function bdtaskt1c2_04_user_attendanc_details($uid)
    {

        // Get data 
        $attn_logs = new Bdtaskt1m4AttendanceLogs();

        $paginateData =$attn_logs->select('*,DATE(atten_date) as mydate')
                ->where('uid',$uid)
                ->groupBy("mydate")
                ->orderBy('time','DESC')         
                ->paginate(2);

        $data = [
            'attn_logs' => $paginateData,
            'pager' => $attn_logs->pager
        ];

        $data['company'] = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_03_getSetting();
        $data['user']  = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_02_getEmployee($uid);
        $data['id'] = $uid;

        $data['title']      = get_phrases(['attendance','logs']);
        $data['moduleTitle']= get_phrases(['human','resource']);
        $data['isDateTimes']= true;
        $data['module']     = "Human_resource";
        $data['page']       = "attendance/attendance_log_userdetails";
        
        return $this->base_02_template->layout($data);
    }

    public function bdtaskt1c2_05_user_attendanc_edit_page($attn_id){

        // Get attendance data along with employee
        $att_emp = $this->db->table("hrm_attendance_history a")
                    ->select("a.*,b.first_name,b.last_name,b.employee_id")
                    ->join('hrm_employees b','a.uid = b.employee_id','left')
                    ->where('a.atten_his_id',$attn_id)
                    ->get()
                    ->getRow();

        if($att_emp){

            $data['att_emp']    = $att_emp;
            $data['title']      = get_phrases(['attendance','form']);
            $data['moduleTitle']= get_phrases(['human','resource']);
            $data['isDateTimes']= true;
            $data['module']     = "Human_resource";
            $data['page']       = "attendance/edit_attendance";

            $data['employees']   = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_01_getAllEmployees();

            return $this->base_02_template->layout($data);

        }else{

            $this->session->setFlashdata('exception', get_phrases(['something', 'went', 'wrong']));
            return redirect()->route('human_resources/attendance/attendance_log');
        }
    }

    /*--------------------------
    | save attn info
    *--------------------------*/
    // public function bdtaskt1c2_06_attn_update()
    // { 
    //     $db = db_connect();
    //     $this->base_03_validation->setRules([
    //         'time' => [
    //             'label'  => get_phrases(['punch','time']),
    //             'rules'  => 'required|valid_date',
    //             'errors' => [
    //                 'numeric' => '{field}'.' '.get_phrases(['should', 'be', 'date','time']),
    //             ]
    //         ],
    //         'uid' => [
    //             'label'  => get_phrases(['employee']),
    //             'rules'  => 'required',
    //             'errors' => [
    //                 'valid_url' => '{field}'.' '.get_phrases(['is','invalid'])
    //             ]
    //         ],
    //     ]);
    //     // base data
    //     $postData = array(
    //         'uid'          => $this->request->getVar('uid'), 
    //         'time'         => $this->request->getVar('time')
    //     );
        
    //     $atten_his_id = $this->request->getVar('atten_his_id');

    //     // echo json_encode($postData);

    //     $MesTitle = get_phrases(['attendance','update']);

    //     //Check duplicate entry
    //     $existingTime = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_attendance_history', array('uid'=>$this->request->getVar('uid'),'time'=>$this->request->getVar('time'),'branch_id'=>session('branchId')));
    //     if($existingTime){

    //         $response = array(
    //             'success'  =>"exist",
    //             'message'  => get_phrases(['duplicate', 'entry']),
    //             'title'    => $MesTitle
    //         );
    //         echo json_encode($response);exit;
    //     }
        
    //     if (!$this->base_03_validation->withRequest($this->request)
    //        ->run()) {
    //         $validation = $this->base_03_validation->listErrors();
    //         $response = array(
    //             'success'  =>false,
    //             'message'  => $validation,
    //             'title'    => $MesTitle
    //         );
    //     }else{

    //         $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_02_Update('hrm_attendance_history',$postData, array('atten_his_id'=>$atten_his_id));  
            
    //         if($result){
            

    //             $response = array(
    //                 'success'  =>true,
    //                 'message'  => get_phrases(['updated', 'successfully']),
    //                 'title'    => $MesTitle
    //             );
    //         }else{
    //             $response = array(
    //                 'success'  =>false,
    //                 'message'  => get_phrases(['something', 'went', 'wrong']),
    //                 'title'    => $MesTitle
    //             );
    //         }
            
    //     }
        
    //     echo json_encode($response);
    // }

    public function bdtaskt1c2_06_attn_update()
    { 
        $db = db_connect();

        $MesTitle = get_phrases(['attendance']);

        $uid        = $this->request->getVar('uid');
        $time       = $this->request->getVar('time');

        $atten_his_id = $this->request->getVar('atten_his_id');
        $atten_his_info = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_attendance_history', array('atten_his_id'=>$atten_his_id));

        //echo json_encode($atten_his_info);exit;

        // Check, if attendance time or employee not modified, then give message that.. nothing modified...
        if(strtotime($atten_his_info->time) == strtotime($time) && $atten_his_info->uid == $uid){

            $response = array(
                'success'  => false,
                'message'  => get_notify('you_did_not_do_any_modification'),
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;

        }

        // Validation starts

        if($uid == "" || $time == ""){

            $response = array(
                'success'  => false,
                'message'  => get_notify('please_fill_up_all_required_fields'),
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;
        }

        //check, if attaendance date is larger than the punch  time, then trrow error
        if(strtotime($atten_his_info->atten_date) > strtotime($time)){

            $response = array(
                'success'  => false,
                'message'  => get_notify('attendance_date_should_be_smaller_than_punch_time'),
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;
        }

        //Check, if punchtime is greater than the next day comparing to attendnace date.. then throw error
        $next_day_date = strtotime("2 day", strtotime($atten_his_info->atten_date));
        $next_day = date("Y-m-d", $next_day_date);

        $punch_time_date = date("Y-m-d", strtotime($time));

        if(strtotime($punch_time_date) >= strtotime($next_day)){

            $response = array(
                'success'  => false,
                'message'  => get_notify('punch_time_should_be_in_between_next_day_comparing_with_attendance_date'),
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;
        }

        // echo json_encode($punch_time_date);exit;

        //Ends

        // base data
        $postData = array(
            'uid'          => $this->request->getVar('uid'), 
            'time'         => $this->request->getVar('time')
        );

        // echo json_encode($postData);

        $MesTitle = get_phrases(['attendance','update']);

        //Check duplicate entry
        $existingTime = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_attendance_history', array('uid'=>$this->request->getVar('uid'),'time'=>$this->request->getVar('time')));
        if($existingTime){

            $response = array(
                'success'  =>"exist",
                'message'  => get_phrases(['duplicate', 'entry']),
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;
        }
        
        $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_02_Update('hrm_attendance_history',$postData, array('atten_his_id'=>$atten_his_id));  
        
        if($result){
        

            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['updated', 'successfully']),
                'title'    => $MesTitle
            );
        }else{
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['something', 'went', 'wrong']),
                'title'    => $MesTitle
            );
        }
        
        echo json_encode($response);
    }

    /*--------------------------
    | delete atten  by atten_his_id
    *--------------------------*/ 
    public function bdtaskt1c2_07_atten_delete($id)
    {
        $MesTitle = get_phrases(['attendance', 'record']);

        //Beofre do any delete action, save the attendance data to be deleted into an array and then check if it has any CPL leave, then remove that Cpl leave
        $existingAttenData = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_attendance_history', array('atten_his_id'=>$id));

        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_06_Deleted('hrm_attendance_history', array('atten_his_id'=>$id));
        if(!empty($data)){
            // Store log data
            $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['attendance']), get_phrases(['deleted']), $id, 'hrm_attendance_history', 3);
            
            //Now if all the attendance data deleted for any date and if that date has any CPL leave generated, then remove/decrement that cpl leave also..
            $cplLeaveRemove = $this->bdtask1c1_24_cplLeaveRemove($existingAttenData);
            // echo json_encode($cplLeaveRemove);exit;
        
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
    | atten_log_search
    *--------------------------*/ 
    public function bdtaskt1c2_08_atten_log_search()
    {
        $this->base_03_validation->setRules([
            'from_date' => [
                'label'  => get_phrases(['from','date']),
                'rules'  => 'required'
            ],
            'to_date' => [
                'label'  => get_phrases(['to','date']),
                'rules'  => 'required'
            ],
            'emp_id' => [
                'label'  => get_phrases(['employee']),
                'rules'  => 'required'
            ],
        ]);
        // base data
        $data = array(
            'emp_id'     => $this->request->getVar('emp_id'), 
            'from_date'  => $this->request->getVar('from_date'),  
            'to_date'    => $this->request->getVar('to_date')
        );
        
        // echo json_encode($data);
        // exit;

        $MesTitle = get_phrases(['attendance','search']);

        if (!$this->base_03_validation->withRequest($this->request)
           ->run()) {
            $validation = $this->base_03_validation->listErrors();
            $response = array(
                'success'  =>false,
                'message'  => $validation,
                'title'    => $MesTitle
            );

        }else{

            // Check start date is greater than end date
            if(strtotime($data['from_date']) > strtotime($data['to_date'])){

                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['from','date','should','be','lesser','than','to','date']),
                    'title'    => $MesTitle
                );

            }else{

                 $response = array(
                    'flag'     => true
                );
            }
        }

        echo json_encode($response);
    }

    /*--------------------------
    | atten_log_search
    *--------------------------*/ 
    public function bdtaskt1c2_09_searched_atten_log()
    {

        $uid        = $this->request->getVar('emp_id');
        $from_date  = $this->request->getVar('from_date');
        $to_date    = $this->request->getVar('to_date');

        $data['atten_in'] =  $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_04_att_log_datebetween($uid,$from_date,$to_date);
        $data['user']  = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_05_att_emp_name($uid);
        $data['company'] = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_03_getSetting();
        $data['employees']   = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_01_getAllEmployees();

        $data['id'] = $uid;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;

        // echo "<pre>";
        // print_r($data);
        // exit;

        $data['title']      = get_phrases(['searched','attendance']);
        $data['moduleTitle']= get_phrases(['human','resource']);
        $data['isDateTimes']= true;
        $data['module']     = "Human_resource";
        $data['page']       = "attendance/attendance_log_datebetween";
        
        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | attendance_setup
    *--------------------------*/ 
    public function bdtaskt1c7_10_attendance_setup(){

        $data['title']      = get_phrases(['attendance','setup']);
        $data['moduleTitle']= get_phrases(['human','resource']);
        $data['isDateTimes']= true;
        $data['isDTables']  = true;
        $data['module']     = "Human_resource";
        $data['page']       = "attendance/attendance_setup/list";

        // echo "<pre>";
        // print_r($data);
        // exit;

        $data['hasCreateAccess']  = $this->permission->method('attendance_setup', 'create')->access();
        $data['hasPrintAccess']   = $this->permission->method('attendance_setup', 'print')->access();
        $data['hasExportAccess']  = $this->permission->method('attendance_setup', 'export')->access();

        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | add_attendanceSetup
    *--------------------------*/

    public function bdtaskt1c7_11_add_attendanceSetup(){

        $MesTitle = get_phrases(['attendance','setup']);

        $id = $this->request->getVar('id');
        $action = $this->request->getVar('action');

        // Validation starts
        if($this->request->getVar('in_time') == null || $this->request->getVar('out_time') == null){
            $response = array(
                'success'  =>false,
                'message'  => get_notify('please_select_all_required_fields'),
                'title'    => $MesTitle,
            );

            echo json_encode($response);exit;
        }

        if(strtotime($this->request->getVar('in_time')) >= strtotime($this->request->getVar('out_time'))){

            $response = array(
                'success'  =>false,
                'message'  => get_notify('in_time_should_be_smaller_than_out_time'),
                'title'    => $MesTitle,
            );

            echo json_encode($response);exit;
        }

        //Validation Ends

        $data = array(
            'in_time'  => $this->request->getVar('in_time'),
            'out_time' => $this->request->getVar('out_time'),
        );

        // echo "<pre>";
        // print_r($data);
        // exit;
       
        if($action=='update'){

            $data['UpdateBy']   = session('id');
            $data['UpdateDate'] = date("Y-m-d h:i:s");

            // echo json_encode($id);exit;

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_02_Update('hrm_attendance_setup', $data, array('attendance_setup_id'=>$id));
            if($result){
                // Store log data
                $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['attendance','setup']), get_phrases(['updated']), $id, 'hrm_attendance_setup', 2);
                
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

            $data['CreateBy']   = session('id');
            $data['CreateDate'] = date("Y-m-d h:i:s");

            // echo json_encode($data);exit;

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('hrm_attendance_setup', $data);
            if($result){
                // Store log data
                $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['attendance','setup']), get_phrases(['created']), $result, 'hrm_attendance_setup', 1);
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
    | attendanceSetupList
    *--------------------------*/
    public function bdtaskt1c7_12_attendanceSetupList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_06_attendanceSetupList($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | getWeekEndDaysById
    *--------------------------*/
    public function bdtaskt1c7_13_attendanceSetupById($id)
    { 
        $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_attendance_setup', array('attendance_setup_id'=>$id));
        echo json_encode($result);
    }

    /*--------------------------
    | deleteAttendanceSetupById
    *--------------------------*/
    public function bdtaskt1c7_14_deleteAttendanceSetupById($id)
    {

        $MesTitle = get_phrases(['attendance','time']);

        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_06_Deleted('hrm_attendance_setup', array('attendance_setup_id'=>$id));

        if(!empty($data)){

            // Store log data
            $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['attendance','setup']), get_phrases(['deleted']), $id, 'hrm_attendance_setup', 3);

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

    public function bdtaskt1c7_15_atten_machine_map(){

        $data['title']      = get_phrases(['attendance','machine', 'map']);
        $data['moduleTitle']= get_phrases(['human','resource']);
        $data['isDateTimes']= true;
        $data['isDTables']= true;
        $data['module']     = "Human_resource";

        $data['hasCreateAccess']  = $this->permission->method('attendance_machine_map', 'create')->access();
        $data['hasPrintAccess']   = $this->permission->method('attendance_machine_map', 'print')->access();
        $data['hasExportAccess']  = $this->permission->method('attendance_machine_map', 'export')->access();

        $data['employees']  = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_07_employeesForAttenMap();

        // echo "<pre>";
        // print_r($data);
        // exit;

        $data['page']       = "attendance/atten_machine_map/list";
        return $this->base_02_template->layout($data);
    }


    /*--------------------------
    | add_atten_machine_map
    *--------------------------*/

    public function bdtaskt1c7_16_add_atten_machine_map(){

        $MesTitle = get_phrases(['attendance','map']);

        $id = $this->request->getVar('id');
        $action = $this->request->getVar('action');

        if($this->request->getVar('zkt_machine_id') == null){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['require','zkt','machine','id','for','selected','employee','for','existing','branch']),
                'title'    => $MesTitle,
            );

            echo json_encode($response);exit;
        }

        $data = array(
            'emp_id'         => $this->request->getVar('emp_id'),
            'zkt_machine_id' => $this->request->getVar('zkt_machine_id')
        );

        // echo json_encode($data);
       
        if($action=='update'){

            $existing_zkt_id_map_info = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_atten_machine_map',array('branch_id'=>session('branchId'),'id'=>$id));
            if($existing_zkt_id_map_info->zkt_machine_id != $data['zkt_machine_id']){

                $existing_zkt_info = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_atten_machine_map',array('branch_id'=>session('branchId'),'zkt_machine_id'=>$data['zkt_machine_id']));
                if($existing_zkt_info){

                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['this','zkt','machine','id','already','exists','for','current','branch']).' !',
                        'title'    => $MesTitle,
                    );

                    echo json_encode($response);exit;
                }
            }


            unset($data['emp_id']);

            $data['UpdateBy']   = session('id');
            $data['UpdateDate'] = date("Y-m-d");

            // echo json_encode($data);exit;

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_02_Update('hrm_atten_machine_map', $data, array('id'=>$id));
            if($result){
                // Store log data
                $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['attendance','machine','map']), get_phrases(['updated']), $id, 'hrm_atten_machine_map', 2);
                
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

            if($this->request->getVar('emp_id') == ""){
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['employee','required']),
                    'title'    => $MesTitle,
                );

                echo json_encode($response);exit;
            }

            $existing_zkt_id_map = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_atten_machine_map', array('branch_id'=>session('branchId'),'zkt_machine_id' => $data['zkt_machine_id']));

            // echo json_encode($existing_zkt_id_map);exit;

            if($existing_zkt_id_map){
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['zkt','machine','id', 'already','mapped','for','this','branch']),
                    'title'    => $MesTitle,
                );

                echo json_encode($response);exit;
            }

            $data['CreateBy']   = session('id');
            $data['CreateDate'] = date("Y-m-d");

            // echo json_encode($data);exit;

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('hrm_atten_machine_map', $data);
            if($result){
                // Store log data
                $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['attendance','machine','map']), get_phrases(['created']), $result, 'hrm_atten_machine_map', 1);
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
    | add_atten_machine_map_list
    *--------------------------*/
    public function bdtaskt1c7_17_add_atten_machine_map_list()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_08_add_atten_machine_map_list($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | atten_machine_map_by_id
    *--------------------------*/
    public function bdtaskt1c7_18_atten_machine_map_by_id($id)
    { 
        $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_atten_machine_map', array('id'=>$id, 'branch_id'=> session('branchId')));
        $empInfo = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('employees', array('emp_id'=>$result->emp_id));

        $result->emp_name = $this->langColumn == 'nameE'?$empInfo->nameE:$empInfo->nameA;

        echo json_encode($result);
    }

    /*--------------------------
    | del_atten_machine_map_by_id
    *--------------------------*/
    public function bdtaskt1c7_19_del_atten_machine_map_by_id($id)
    {

        $MesTitle = get_phrases(['attendance','map']);

        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_06_Deleted('hrm_atten_machine_map', array('id'=>$id));

        if(!empty($data)){

            // Store log data
            $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['attendance','machine','map']), get_phrases(['deleted']), $id, 'hrm_atten_machine_map', 3);

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



    /*Bulk empoloyee import starts*/

    public function bdtask1c1_20_bulkImportEmployees()
    { 
        $response = array();

        $action = $this->request->getVar('action');

        $MesTitle = get_notify('bulk_attendance_upload');

        if($action=='add'){

            if(isset($_FILES['atten_logs_list']['name']) && !empty($_FILES['atten_logs_list']['name'])){

                $fileName = $_FILES["atten_logs_list"]["tmp_name"];

                if ($_FILES["atten_logs_list"]["size"] > 0) {                        
                    $respo = $this->csv_read($fileName);

                    // echo $fileName;
                    // echo json_encode($respo);
                    // exit;

                    if($respo['total_inserted'] > 0){

                        $response = array(
                            'success'  => true,
                            'message'  => "Total ".$respo['total_inserted']." rows inserted out of ".$respo['total_rows']." rows",
                            'title'    => $MesTitle
                        );
                        echo json_encode($response);exit;
                    }else{

                        $response = array(
                            'success'  => false,
                            'message'  => "Total ".$respo['total_inserted']." rows inserted out of ".$respo['total_rows']." rows as it has ".$respo['duplicate_atten_rec']." duplicate attendance records and ".$respo['invalid_employee_id']." invalid employees id",
                            'title'    => $MesTitle
                        );
                        echo json_encode($response);exit;
                    }

                }else{

                    $response = array(
                        'success'  => false,
                        'message'  => get_phrases(['something', 'went', 'wrong']),
                        'title'    => $MesTitle
                    );
                }

            }else{
                $response = array(
                    'success'  => false,
                    'message'  => get_phrases(['something', 'went', 'wrong']),
                    'title'    => $MesTitle
                );
            }
        }else{

             $response = array(
                'success'  => false,
                'message'  => get_phrases(['something', 'went', 'wrong']),
                'title'    => $MesTitle
            );
        }
        
        echo json_encode($response);
    }

    // function csv_read($fileName){

    //     //return $fileName;

    //     $file = fopen($fileName, "r");

    //     fgetcsv($file);

    //     $total_rows = 0;
    //     $arr = array();

    //     $total_inserted = 0;
    //     $duplicate_atten_rec = 0;
    //     $invalid_employee_id = 0;
    //     $final_arr = array();
                        
    //     while (($column = fgetcsv($file)) !== FALSE) {
            
    //         $employee_id = "";
    //         if (isset($column[0])) {
    //             $employee_id = esc($column[0]);
    //         }
    //         $time = "";
    //         if (isset($column[1])) {
    //             $time = esc($column[1]);
    //             $time = date('Y-m-d H:i', strtotime($time));
    //         }
            
    //         $data = array(
    //             'uid'   =>$employee_id,
    //             'time'  =>$time,
    //             'id'    =>1,
    //             'state' =>1,
    //         );

    //         // $arr[$total_rows] =  $data;
    //         $total_rows++;

    //         ////////////////// Inserting data and Dulicate check
    //         $employee_info = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_employees', array('employee_id'=>$employee_id));
    //         if($employee_info){
    //             $dup_atten_history = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_attendance_history', array('uid'=>$employee_id,'time'=>$time));
    //             if($dup_atten_history){

    //                 $duplicate_atten_rec++;
    //             }
    //             else{
    //                 $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('hrm_attendance_history', $data);
    //                 $total_inserted++;
    //             }
    //         }else{
    //             $invalid_employee_id++;
    //         }
    //         /////////////////////END//////////////////
                        
    //     }

    //     $final_arr['total_rows']          =  $total_rows;
    //     $final_arr['total_inserted']      =  $total_inserted;
    //     $final_arr['invalid_employee_id'] =  $invalid_employee_id;
    //     $final_arr['duplicate_atten_rec'] =  $duplicate_atten_rec;
        
    //     return $final_arr;

    //     // return $arr;

    //     // return 1;
    // }

    function csv_read($fileName){

        //return $fileName;

        $file = fopen($fileName, "r");

        fgetcsv($file);

        $total_rows = 0;
        $arr = array();

        $total_inserted = 0;
        $fail_to_insert = 0;
        $duplicate_atten_rec = 0;
        $invalid_employee_id = 0;
        $final_arr = array();
                        
        while (($column = fgetcsv($file)) !== FALSE) {
            
            $employee_id = "";
            if (isset($column[0])) {
                $employee_id = esc($column[0]);
            }

            $atten_date = "";
            if (isset($column[1])) {
                $atten_date = esc($column[1]);
                $atten_date = date('Y-m-d', strtotime($atten_date));
            }

            $time = "";
            if (isset($column[2])) {
                $time = esc($column[2]);
                $time = date('Y-m-d H:i', strtotime($time));
            }
            
            $data = array(
                'atten_date'  => $atten_date,
                'uid'         => $employee_id,
                'time'        => $time,
                'id'          => 1,
                'state'       => 1,
            );

            // $arr[$total_rows] =  $data;
            $total_rows++;

            ////////////////// Inserting data and Dulicate check

            $employee_info = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_employees', array('employee_id'=>$employee_id));
            if($employee_info){
                $dup_atten_history = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_attendance_history', array('uid'=>$employee_id,'time'=>$time,'atten_date'=>$atten_date));

                if($dup_atten_history){

                    $duplicate_atten_rec++;
                }
                else{

                    $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('hrm_attendance_history', $data);
                    if($result){

                        //Check in insert CPL leave if applicable
                        unset($data['id']);
                        $this->bdtask1c1_23_cplLeave($data);
                        //Ends
                    
                        // Update hrm_emproster_assign table for the emp_is as ateended means is_complete = 1
                        $date     = date('Y-m-d',strtotime($atten_date));
                        $signin   = date("h:i:s a",strtotime($time));
                        $sin_time = date("H:i",strtotime($time));
                        $emp_id   = $employee_id;
                        $time     = $time;

                        if ($this->db->tableExists('hrm_emproster_assign')) {
                            $is_complete = array('is_complete'=>1);

                            $query = "SELECT * FROM hrm_emproster_assign WHERE cast(concat(emp_startroster_date, ' ', emp_startroster_time) as datetime) <= '$time' AND cast(concat(emp_endroster_date, ' ', emp_endroster_time) as datetime) >= '$time' AND emp_startroster_date = '$date' AND emp_id = '$emp_id'";
                            $query_respo = $this->db->query($query)->getRow();
                            if($query_respo){
                                $builder3 = $this->db->table('hrm_emproster_assign');
                                $builder3->where('sftasnid',$query_respo->sftasnid);
                                $upRes = $builder3->update($is_complete);
                            }
                        }
                        // End of Update hrm_emproster_assign table for the emp_is as ateended means is_complete = 1

                        $total_inserted++;

                    }else{

                        $fail_to_insert++;
                    }
                }
            }else{
                $invalid_employee_id++;
            }

            /////////////////////END//////////////////                    
        }

        $final_arr['total_rows']          =  $total_rows;
        $final_arr['total_inserted']      =  $total_inserted;
        $final_arr['total_fail']          =  $fail_to_insert;
        $final_arr['invalid_employee_id'] =  $invalid_employee_id;
        $final_arr['duplicate_atten_rec'] =  $duplicate_atten_rec;
        
        return $final_arr;

        // return $arr;

    }

    /*Bulk empoloyee import ends*/

    /* New functionality for atetndnace time check*/

    // Check if the punch time / attendance time is falling betwwn two attendance time which are already taken.. then show a message for that..

    /*--------------------------
    | checkPunchTimeUnderTwoRemainingTime when adding attendance
    *--------------------------*/

    public function bdtask1c1_21_checkPunchTimeUnderTwoRemainingTime(){

        $MesTitle = get_phrases(['attendance']);

        $atten_date = $this->request->getVar('atten_date'); //For which date i am taking attendance
        $uid        = $this->request->getVar('uid');
        $time       = $this->request->getVar('time');

        //Check, if punchtime is greater than the next day comparing to attendnace date.. then throw error
        $next_day_date = strtotime("2 day", strtotime($atten_date));
        $next_day = date("Y-m-d", $next_day_date);

        $punch_time_date = date("Y-m-d", strtotime($time));

        if(strtotime($punch_time_date) < strtotime($atten_date)){

            $response = array(
                'success'  => false,
                'message'  => get_notify('punch_time_should_be_greater_than_attendance_date'),
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;
        }
        if(strtotime($punch_time_date) >= strtotime($next_day)){

            $response = array(
                'success'  => false,
                'message'  => get_notify('punch_time_should_be_in_between_next_day_comparing_with_attendance_date'),
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;
        }
       
        // Ends

        $atten_times = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_09_getAttendances(array('atten_date'=>$atten_date,'uid'=>$uid));

        // Check , if there is any attendance time for the employee for same date.. before creating first attendnace
        if($time){

            
            $previousAttendnace = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_10_previousAttendnace(array('punch_time_date'=>$punch_time_date,'uid'=>$uid));
            // Check , if the new attendance time is lesser than the same date attendnace time for the employee...
            if($previousAttendnace != null && strtotime($time) <= strtotime($previousAttendnace->time)){

                $response = array(
                    'success'  => false,
                    'message'  => get_notify('punch_time_should_be_greater_than_previous_attendance_time').' '.$previousAttendnace->time,
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;
            }  

        }

        // If there is already one attendance time inserted...
        if(count($atten_times) == 1){

            $atten_time_first = end($atten_times);

            $response = array(
                'success'  => "exist",
                'message'  => get_notify('one_attendance_time_inserted_for_employee_which_is').', '.$atten_time_first->time,
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;

        }

        if(count($atten_times) > 1){

            // Check, if the new attendance time is smaller than the first attendance time for the employee for same date...
            if(strtotime($time) < strtotime($atten_times[0]->time)){
                $response = array(
                    'success'  => false,
                    'message'  => get_notify('new_attendnace_time_should_be_greater_than_previous_or_delete_previous_records_first'),
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;
            }
            //Ends

            if(count($atten_times) == 2){

                $atten_time_first = $atten_times[0];
                $atten_time_last = end($atten_times);

                if(strtotime($time) > strtotime($atten_time_first->time) && strtotime($time) < strtotime($atten_time_last->time)){

                    $response = array(
                        'success'  => "exist",
                        'message'  => get_notify('are_you_sure').' ? '.get_notify('as_it_belongs_between_two_attendnace_times'),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);exit;
                }

                // If insert the attendance data after getting this message, then require to insert one more attendnace entry to make it 4entry for calculations
                if(strtotime($time) < strtotime($atten_time_first->time)){

                    $response = array(
                        'success'  => "exist",
                        'message'  => get_notify('are_you_sure').' ? '.get_notify('as_it_is_smaller_than_two_attendnace_times_already_inserted'),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);exit;
                }

                // Already there is two attendnace time for the selected date and employee
                $response = array(
                    'success'  => "exist",
                    'message'  => get_notify('are_you_sure').' ? '.get_notify('two_attendnace_times_already_inserted'),
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;
               
            }
            else if(count($atten_times) >= 3){

                $response = array(
                    'success'  => "exist",
                    'message'  => get_notify('please_be_sure_about_your_other_attendance_times_that_all_are_in_a_sequence'),
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;
            }

        }

        // echo json_encode(count($atten_times));exit;

    } 

    /*--------------------------
    | update PunchTimeUnderTwoRemainingTime
    *--------------------------*/

    public function bdtask1c1_22_upPunchTimeUnderTwoRemainingTime(){

        $MesTitle = get_phrases(['attendance']);

        // $atten_date = $this->request->getVar('atten_date'); //For which date i am taking attendance
        $atten_his_id = $this->request->getVar('atten_his_id');
        $uid          = $this->request->getVar('uid');
        $time         = $this->request->getVar('time');

        $atten_his_info  = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_attendance_history', array('atten_his_id'=>$atten_his_id));
        $atten_duplicate = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_attendance_history', array('uid'=>$uid,'time'=>$time));

        if(strtotime($time) == strtotime($atten_his_info->time) || $atten_duplicate){

            $response = array(
                'success'  => false,
                'message'  => get_notify('duplicate_entry'),
                'time'     => $atten_his_info->time,
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;
        }

        //Check, if punchtime is greater than the next day comparing to attendnace date.. then throw error
        $next_day_date = strtotime("2 day", strtotime($atten_his_info->atten_date));
        $next_day = date("Y-m-d", $next_day_date);

        $punch_time_date = date("Y-m-d", strtotime($time));

        if(strtotime($punch_time_date) < strtotime($atten_his_info->atten_date)){

            $response = array(
                'success'  => false,
                'message'  => get_notify('attendance_time_should_be_greater_than_attendance_date'),
                'time'     => $atten_his_info->time,
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;
        }
        if(strtotime($punch_time_date) >= strtotime($next_day)){

            $response = array(
                'success'  => false,
                'message'  => get_notify('attendance_time_should_be_in_between_next_day_comparing_with_attendance_date'),
                'time'     => $atten_his_info->time,
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;
        }

        // Check employee immediate next date attendance, if already available.... then not allow to take greater than the next date intime attendance..
        $next_day_in_time = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_11_empNextDayAttendnace(array('atten_date'=>$atten_his_info->atten_date,'uid'=>$uid));
        if($next_day_in_time && strtotime($time) >= strtotime($next_day_in_time->time)){

            $response = array(
                'success'  => false,
                'message'  => get_notify('attendance_time_should_be_smaller_then_next_date_in_time'),
                'time'     => $atten_his_info->time,
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;

        }

    } 

    // Evaluation of CPL leave and push the leave to database
    public function bdtask1c1_23_cplLeave($data = []){

        $weekends = explode(',', $this->bdtaskt1c1_02_Holidays->get_weekends()->weekend_days);
        $govt_holiday = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_12_check_govt_holiday($data);

        // Check, if there any weekends or govt holiday.. before do any further execution..
        $dayname = date("l",strtotime($data['atten_date']));
        if(in_array($dayname,$weekends) || $govt_holiday){

            //Check CPL leave alredy inserted, then not allow to take further actin after bottom line.... for other attendance time on same attendnace_date
            $cpl_leave_already_inserted = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_14_cpl_leave_already_inserted($data['uid'],$data);

            if($cpl_leave_already_inserted == null){

                // Check total cpl leave for employee for the year
                $total_leave = 0;
                $year =  date("Y",strtotime($data['atten_date']));
                $total_cpl_leave = $this->bdtaskt1c1_03_AttenModel->bdtaskt1m3_13_emp_total_cpl_leave($data['uid'],$year);

                if($total_cpl_leave != null){

                    $total_leave = (int)$total_cpl_leave->total_leave + 1;
                }else{
                    $total_leave = 1;
                }
                // base data
                $data_cpl_leave = array(
                    'employee_id ' => $data['uid'],
                    'total_leave'  => $total_leave, 
                    'year'         => $year,
                );

                // base data
                $data_cpl_leave_his = array(
                    'employee_id ' => $data['uid'],
                    'date'         => $data['atten_date'], 
                    'CreateDate'   => date("Y-m-d h:i:s"),  
                    'CreateBy'     => session('id'),  
                    'is_leave'     => 1
                );

                if($total_cpl_leave != null){
                    //If there is already CPL leave for the year, then update that total_leave for the year for that employee
                    unset($data_cpl_leave['employee_id']);
                    unset($data_cpl_leave['year']);

                    //If there is no CPL leave for the year
                    $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('hrm_cpl_leave_history', $data_cpl_leave_his);
                    if($result){

                        //Store activity logs
                        $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['cpl','leave','history']), get_phrases(['created']), $result, 'hrm_cpl_leave_history', 1);

                        $result_cpl_leave_up = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_02_Update('hrm_cpl_leave', $data_cpl_leave, array('id '=>$total_cpl_leave->id));
                    }

                }else{
                    //If there is no CPL leave for the year
                    $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('hrm_cpl_leave_history', $data_cpl_leave_his);
                    if($result){
                        //Store activity logs
                        $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['cpl','leave','history']), get_phrases(['created']), $result, 'hrm_cpl_leave_history', 1);

                        $result_cpl_leave = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('hrm_cpl_leave', $data_cpl_leave);

                    }
                }
            }
        }
    }


    public function bdtask1c1_24_cplLeaveRemove($data = []){

        //Check if all the attendance data deleted for the atedance date, which is deleting by attendance delete button..in user_attendanc_details page
        $attendance_history = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_05_getResultWhere('hrm_attendance_history', array('uid'=>$data->uid,'atten_date'=>$data->atten_date));
        if(empty($attendance_history)){

            //Check if there is any cpl_leave_history on this date... then delete that, along with decrement one day from total_leave of cpl_leave
            $cpl_leave_history = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_cpl_leave_history', array('employee_id'=>$data->uid,'date'=>$data->atten_date));
            if($cpl_leave_history){

                $del_cpl_leave_history = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_06_Deleted('hrm_cpl_leave_history', array('id'=>$cpl_leave_history->id));
                if($del_cpl_leave_history){

                    //Store activity logs
                    $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['cpl','leave','history','deleted']), get_phrases(['deleted']), $cpl_leave_history->id, 'hrm_cpl_leave_history', 3);

                    //Also... reduce the total_leave by 1 from hrm_cpl_leave fir the employee, for the year from atten_date
                    $year =  date("Y",strtotime($data->atten_date));
                    $cpl_leave = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_cpl_leave', array('employee_id'=>$data->uid,'year'=>$year));
                    if($cpl_leave && $cpl_leave->total_leave > 0){

                        // base data
                        $data_cpl_leave = array(
                            'total_leave'  => (int)$cpl_leave->total_leave - 1, 
                        );

                        $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_02_Update('hrm_cpl_leave', $data_cpl_leave, array('id'=>$cpl_leave->id));

                        //Store activity logs
                        $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['cpl','leave','reduced']), get_phrases(['updated']), $cpl_leave->id, 'hrm_cpl_leave', 2);

                    }
                }
            }
        }
    }

    //End of CPL leave evaluation



    /*Ends*/
   
}
