<?php namespace App\Modules\Human_resource\Controllers;
use CodeIgniter\Controller;
use App\Modules\Human_resource\Models\Bdtaskt1m7DutyRoster;
use App\Modules\Human_resource\Models\Bdtaskt1m1Employee;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

use CodeIgniter\I18n\Time;

class Bdtaskt1m9c6DutyRoster extends BaseController
{

	private $bdtaskt1c1_07_DutyRoster;
    private $bdtaskt1c1_01_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->db = db_connect();

        $this->permission = new Permission();
        $this->bdtaskt1c1_07_DutyRoster = new Bdtaskt1m7DutyRoster();
        $this->bdtaskt1c1_01_CmModel = new Bdtaskt1m1CommonModel();
        $this->bdtaskt1c1_01_EmpModel = new Bdtaskt1m1Employee();

        $this->time = new Time();

        $this->db = db_connect();

        $setting = $this->db->table('setting')->select('*')->get()->getRow();

        $timeZone = (!empty($setting->time_zone)?$setting->time_zone:'Asia/Dhaka');
        @date_default_timezone_set($timeZone);
    }

    public function index(){

        $data['title']      = get_phrases(['shift', 'list']);
        $data['moduleTitle']= get_phrases(['human','resource']);
        $data['isDTables']  = true;
        $data['module']     = "Human_resource";
        $data['page']       = "duty_roster/shift_list";

        $data['departments_list'] = $this->bdtaskt1c1_01_EmpModel->departments_list();

        $data['hasCreateAccess']  = $this->permission->method('shift', 'create')->access();
        $data['hasPrintAccess']   = $this->permission->method('shift', 'print')->access();
        $data['hasExportAccess']  = $this->permission->method('shift', 'export')->access();

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | Add/Edit Department info for company structure
    *--------------------------*/
    public function bdtaskt1c6_01_createShift()
    { 
        $MesTitle = get_phrases(['shift', 'record']);

        $action = $this->request->getVar('action');
        $shift_id = $this->request->getVar('id');

        $postData = array(
            'shift_name'      => $this->request->getVar('shift_name'),
            'department_id'   => $this->request->getVar('department'),
            'shift_start'     => $this->request->getVar('shift_start'),
            'shift_end'       => $this->request->getVar('shift_end'),
            'shift_duration'  => $this->request->getVar('shift_duration'),
        );

        // echo json_encode($postData);exit;

        if ($this->request->getMethod() == 'post') {

            $rules = [
                'shift_name'      => ['label' => get_phrases(['shift','name']),'rules' => 'required'],
                'department'      => ['label' => get_phrases(['department']),'rules' => 'required'],
                'shift_start'     => ['label' => get_phrases(['shift', 'start']),'rules' => 'required'],
                'shift_end'       => ['label' => get_phrases(['shift', 'end']),'rules' => 'required'],
                'shift_duration'  => ['label' => get_phrases(['shift', 'duration']),'rules' => 'required'],
            ];
        }

        if ($action=='update') {
            unset($rules['department']);
        }

        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            if($action=='add'){

                //Check , if the shift_nameE already added... then not allow to add same shift_nameE
                $countshift = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_empwork_shift', array('department_id'=>$postData['department_id'],'shift_name'=>$this->request->getVar('shift_name')));
                if(!empty($countshift)){

                    $response = array(
                        'success'  =>'exist',
                        'message'  => get_phrases(['shift','name', 'already', 'exists']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);exit;
                }

                // Check any of the shift already fall under existing shift time
                $duplicate_time_found = 0;
                $i = 0;
                $arr_test = array();

                $res_shift_duplicate_time = $this->bdtaskt1c6_checkShiftTimeUnderExistingShift($postData);
                foreach ($res_shift_duplicate_time as $row){

                    $respo = $this->isBetween($row->shift_start,$row->shift_end,$postData['shift_start'],$postData['shift_end']);
                    if($respo){
                        $duplicate_time_found = 1;
                        $arr_test[$i] = $respo;
                        $i++;
                    }

                }
                
                if($duplicate_time_found > 0){

                    $response = array(
                        'success'  =>'exist',
                        'message'  => get_notify('shift_start_or_end_time_falling_under_existing_shift_for_selected_department'),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);exit;
                }

                // echo json_encode($arr_test);exit;

                //Ends

                // Add datetime and create_by
                $postData['CreateDate'] = date("Y-m-d h:i:s");
                $postData['CreateBy']   = $this->session->get('id');

                $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_01_Insert('hrm_empwork_shift',$postData);
                if($result){
                    // Store log data
                    $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['shift','time']), get_phrases(['created']), $result, 'hrm_empwork_shift', 1);
                    $response = array(
                        'success'  =>true,
                        'message'  => get_phrases(['added', 'successfully']),
                        'title'    => $MesTitle
                    );
                }else{
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['something', 'went', 'wrong']),
                        'title'    => $MesTitle
                    );
                }

            }else{

                //Update shift code will go here..
                //Check , if the shift_nameE already added... then not allow to add same shift_nameE
                $shiftInfo = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_empwork_shift', array('shiftid'=>$shift_id));
                $postData['department_id'] = $shiftInfo->department_id;
                // echo json_encode($shiftInfo);exit;

                if($shiftInfo->shift_name != $postData['shift_name']){

                    //$isExistShift = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_empwork_shift',array('shift_name'=>trim($postData['shift_name'])));
                    $isExistShift = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_empwork_shift', array('department_id'=>$shiftInfo->department_id,'shift_name'=>trim($postData['shift_name'])));

                    if (!empty($isExistShift)) {

                        $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['shift','name', 'already', 'exists']),
                            'title'    => $MesTitle
                        );
                        echo json_encode($response);exit;
                    }
                }
                //Ends

                // Check any of the shift already fall under existing shift time
                $duplicate_time_found = 0;
                $i = 0;
                $arr_test = array();

                $res_shift_duplicate_time = $this->bdtaskt1c6_shiftTimeUnderExistingShiftExceptExisting($postData, $shift_id);
                foreach ($res_shift_duplicate_time as $row){

                    $respo = $this->isBetweenWhenUpdate($row->shift_start,$row->shift_end,$postData['shift_start'],$postData['shift_end']);
                    if($respo){
                        $duplicate_time_found = 1;
                        $arr_test[$i] = $respo;
                        $i++;
                    }

                }
                
                if($duplicate_time_found > 0){

                    $response = array(
                        'success'  =>'exist',
                        'message'  => get_notify('shift_start_or_end_time_falling_under_existing_shift_for_selected_department'),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);exit;
                }

                // echo json_encode($arr_test);exit;

                //Ends

                // updating hrm_empwork_shift data and add datetime and UpdateBy
                unset($postData['department_id']);

                $postData['UpdateDate'] = date("Y-m-d h:i:s");
                $postData['UpdateBy']   = $this->session->get('id');

                $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_02_Update('hrm_empwork_shift',$postData, array('shiftid'=>$shift_id));

                if($result){

                    // Store log data
                    $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['shift','time']), get_phrases(['updated']), $shift_id, 'hrm_empwork_shift', 1);

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
            } 
        }
        
        echo json_encode($response);
    }

    public function bdtaskt1c6_02_checkInshift(){

        $MesTitle = get_phrases(['shift', 'record']);

        $department_id = $this->request->getVar('department');
        $shift_start =  $this->request->getVar('shift_start');
        $shift_end   =  $this->request->getVar('shift_end');

        $builder3 = $this->db->table("hrm_empwork_shift");
        $builder3->select("*");
        $builder3->where('cast(shift_start AS Time) >=', $shift_start);
        $builder3->where('cast(shift_end AS Time) <=', $shift_end);
        $builder3->where('department_id', $department_id);
        $result = $builder3->get()->getResult();

        if(!empty($result)){

             echo 1;
        }
        
    }

    public function bdtaskt1c6_03_chkduplicateshift(){

        $MesTitle = get_phrases(['shift', 'record']);

        $department_id = $this->request->getVar('department');
        $shift_start   = $this->request->getVar('shift_start');
        $shift_end     = $this->request->getVar('shift_end');

        $condi1 = "'".$shift_start."' BETWEEN cast(shift_start AS Time) AND cast(shift_end AS Time) AND department_id='".$department_id."'";
        $condi2 = "'".$shift_end."' BETWEEN cast(shift_start AS Time) AND cast(shift_end AS Time) AND department_id='".$department_id."'";

        $builder3 = $this->db->table("hrm_empwork_shift");
        $builder3->select("*");
        $builder3->where($condi1);
        $builder3->orWhere($condi2);

        $result = $builder3->get()->getResult();

        if(!empty($result)){

            echo 1;
        }
        
    }

    /*--------------------------
    | shiftDataList
    *--------------------------*/
    public function bdtaskt1c6_04_shiftDataList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c1_07_DutyRoster->bdtaskt1m4_01_shiftDataList($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | Get getShiftById
    *--------------------------*/
    public function bdtaskt1c6_05_getShiftById($id)
    { 
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_empwork_shift', array('shiftid '=>$id));
        echo json_encode($data);
    }


    /*--------------------------
    | deleteShiftById
    *--------------------------*/
    public function bdtaskt1c6_06_deleteShiftById($id)
    {
        $MesTitle = get_phrases(['shift', 'record']);

        // Check if the shift already assigned for any roster, then not allow to delete
        $roster_by_shift = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_duty_roster', array('shift_id'=>$id));

        if($roster_by_shift){
            
            $response = array(
                'success'  =>"exist",
                'message'  => get_phrases(['shift', 'already', 'assigned', 'for' ,'a', 'roster']),
                'title'    => $MesTitle
            );

            echo json_encode($response);
            exit;
        }
        // End of Check if the shift already assigned for any roster

        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_06_Deleted('hrm_empwork_shift', array('shiftid'=>$id));

        if(!empty($data)){

            // Store log data
            $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['shift','time']), get_phrases(['deleted']), $id, 'hrm_empwork_shift', 3);

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

    public function bdtaskt1c6_07_rosterList(){

        $data['title']      = get_phrases(['roster', 'list']);
        $data['moduleTitle']= get_phrases(['human','resource']);
        $data['isDTables']  = true;
        // $data['isDateTimes']= true;
        $data['module']     = "Human_resource";
        $data['page']       = "duty_roster/roster_list";

        $data['hasCreateAccess']  = $this->permission->method('roster', 'create')->access();
        $data['hasPrintAccess']   = $this->permission->method('roster', 'print')->access();
        $data['hasExportAccess']  = $this->permission->method('roster', 'export')->access();

        $data['departments_list'] = $this->bdtaskt1c1_01_EmpModel->departments_list();

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);
    }

    public function bdtaskt1c6_08_checkshift_data1(){

        $department_id =  $this->request->getVar('department_id');
        $shift_id      =  $this->request->getVar('shift_id');

        $start_date =  $this->request->getVar('start_date');
        $end_date   =  $this->request->getVar('end_date');

        $builder3 = $this->db->table("hrm_duty_roster");
        $builder3->select("*");
        $builder3->where('roster_start >=', $start_date);
        $builder3->where('roster_end <=', $end_date);
        $builder3->where('department_id', $department_id);
        // $builder3->where('shift_id', $shift_id);

        $result = $builder3->get()->getResult();

        if(!empty($result)){

            echo 1;
        }
        
    }

    public function bdtaskt1c6_09_checkshift_data2(){

        $department_id =  $this->request->getVar('department_id');
        $shift_id      =  $this->request->getVar('shift_id');

        $start_date =  $this->request->getVar('start_date');
        $end_date   =  $this->request->getVar('end_date');

        $builder3 = $this->db->table("hrm_duty_roster");
        $builder3->select("*");
        $builder3->where('roster_start <=', $start_date);
        $builder3->where('roster_end >=', $start_date);
        $builder3->where('department_id', $department_id);
        // $builder3->where('shift_id', $shift_id);

        $result = $builder3->get()->getResult();

        // echo json_encode($result);exit;

        if(!empty($result)){

            echo 1;
        }
        
    }


    /*--------------------------
    | Add/Edit Department info for company structure
    *--------------------------*/
    public function bdtaskt1c6_10_createRoster()
    { 
        $MesTitle = get_phrases(['roster', 'record']);

        $action = $this->request->getVar('action');
        $shift_id = $this->request->getVar('id');

        // $postData = array(
        //     'department_id'   => $this->request->getVar('department_id'),  
        //     'shift_id'        => $this->request->getVar('shift_id'),  
        //     'roster_start'    => $this->request->getVar('roster_start'),
        //     'roster_end'      => $this->request->getVar('roster_end'),
        //     'roster_dsys'     => $this->request->getVar('roster_duration'),
        // );

        // echo json_encode($this->request->getVar('shift_id'));exit;
        

        if ($this->request->getMethod() == 'post') {

            $rules = [
                'department_id'    => ['label' => get_phrases(['department']),'rules' => 'required'],
                'shift_id'         => ['label' => get_phrases(['shift']),'rules' => 'required'],
                'roster_start'     => ['label' => get_phrases(['roster', 'start']),'rules' => 'required'],
                'roster_end'       => ['label' => get_phrases(['roster', 'end']),'rules' => 'required'],
                'roster_duration'  => ['label' => get_phrases(['roster', 'duration']),'rules' => 'required'],
            ];
        }

        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            if($action=='add'){

                // Form the postData array and Add datetime and create_by
                $shift_idall = $this->request->getVar('shift_id');
                $randomid = $this->randID();

                $i = 0;
                $respo_flag = true;

                foreach ($shift_idall as $shift) {

                    $postData = array(
                            'shift_id'      => $shift_idall[$i],
                            'department_id' => $this->request->getVar('department_id'),  
                            'rostentry_id'  => $randomid,
                            'roster_start'  => $this->request->getVar('roster_start'),
                            'roster_end'    => $this->request->getVar('roster_end'),
                            'roster_dsys'   => $this->request->getVar('roster_duration'),
                        );

                    $postData['rostentry_id']  = $randomid;
                    $postData['CreateDate'] = date("Y-m-d h:i:s");
                    $postData['CreateBy']   = $this->session->get('id');

                    $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_01_Insert('hrm_duty_roster',$postData);
                    if($result){

                        // Store log data
                        $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['roster']), get_phrases(['created']), $result, 'hrm_duty_roster', 1);

                    }else{
                         $respo_flag = false;
                    }

                    $i++;
                    
                }

                if($respo_flag){

                    $response = array(
                        'success'  =>true,
                        'message'  => get_phrases(['added', 'successfully']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);exit;

                }else{

                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['something', 'went', 'wrong']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);exit;
                }

                // exit;

                // $postData['rostentry_id']  = $randomid;
                // $postData['CreateDate']    = date("Y-m-d h:i:s");
                // $postData['CreateBy']      = $this->session->get('id');

                // echo json_encode($postData);exit;

                // $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_01_Insert('hrm_duty_roster',$postData);
                // if($result){

                //     // Store log data
                //     $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['roster']), get_phrases(['created']), $result, 'hrm_duty_roster', 1);


                //     $response = array(
                //         'success'  =>true,
                //         'message'  => get_phrases(['added', 'successfully']),
                //         'title'    => $MesTitle
                //     );
                //     echo json_encode($response);exit;

                // }else{

                //     $response = array(
                //         'success'  =>false,
                //         'message'  => get_phrases(['something', 'went', 'wrong']),
                //         'title'    => $MesTitle
                //     );
                //     echo json_encode($response);exit;
                // }

                // exit;

            }else{

                //Update shift code will go here..
                //Check , if the shift_nameE already added... then not allow to add same shift_nameE
                $shiftInfo = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_empwork_shift', array('shiftid'=>$shift_id));
                echo json_encode($shiftInfo);exit;

                // // Check , if the start and end date falls under existing roster
                // $checkshift_data1 = $this->bdtaskt1c6_08_checkshift_data1_inside_controller($postData);
                // $checkshift_data2 = $this->bdtaskt1c6_09_checkshift_data2_inside_controller($postData);

                // if($checkshift_data1 || $checkshift_data2){

                //     $response = array(
                //         'success'  =>false,
                //         'message'  => get_phrases(['something', 'went', 'wrong']),
                //         'title'    => $MesTitle
                //     );
                //     echo json_encode($response);exit;
                // }
                // //Ends

                // updating hrm_empwork_shift data and add datetime and UpdateBy
                $postData['UpdateDate'] = date("Y-m-d h:i:s");
                $postData['UpdateBy']   = $this->session->get('id');

                $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_02_Update('hrm_empwork_shift',$postData, array('shiftid'=>$shift_id));

                if($result){

                    // Store log data
                    $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['shift','time']), get_phrases(['updated']), $shift_id, 'hrm_empwork_shift', 1);

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
            } 
        }
        
        echo json_encode($response);
    }


    public function bdtaskt1c6_08_checkshift_data1_inside_controller($data){

        $department_id =  $data['department_id'];
        $shift_id =  $data['shift_id'];

        $start_date =  $data['roster_start'];
        $end_date =  $data['roster_end'];

        $builder3 = $this->db->table("hrm_duty_roster");
        $builder3->select("*");
        $builder3->where('roster_start >=', $start_date);
        $builder3->where('roster_end <=', $end_date);
        $builder3->where('department_id', $department_id);
        $builder3->where('shift_id', $shift_id);

        $result = $builder3->get()->getResult();

        if(!empty($result)){

            return true;
        }
        return false;
        
    }

    public function bdtaskt1c6_09_checkshift_data2_inside_controller($data){

        $department_id =  $data['department_id'];
        $shift_id =  $data['shift_id'];

        $start_date =  $data['roster_start'];
        $end_date =  $data['roster_end'];

        $builder3 = $this->db->table("hrm_duty_roster");
        $builder3->select("*");
        $builder3->where('roster_start <=', $start_date);
        $builder3->where('roster_end >=', $start_date);
        $builder3->where('department_id', $department_id);
        $builder3->where('shift_id', $shift_id);

        $result = $builder3->get()->getResult();

        // echo json_encode($result);exit;

        if(!empty($result)){

            return true;
        }
        return false;
        
    }


    /*--------------------------
    | shift list
    *--------------------------*/
    public function bdtaskt1c6_08_getShiftList()
    { 
        $data = $this->bdtaskt1c1_07_DutyRoster->bdtaskt1m8_2_getShiftList();
        echo json_encode($data);
    }


    /*--------------------------
    | rosterDataList
    *--------------------------*/
    public function bdtaskt1c6_11_rosterDataList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c1_07_DutyRoster->bdtaskt1m8_02_rosterDataList($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | getRosterById
    *--------------------------*/
    public function bdtaskt1c6_12_getRosterById($id)
    { 
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_duty_roster', array('roster_id'=>$id));
        $respo = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_empwork_shift', array('shiftid'=>$data->shift_id));

        $data->shift_name = $respo->shift_name;

        echo json_encode($data);
    }

    /*--------------------------
    | deleteRosterByDate
    *--------------------------*/
    public function bdtaskt1c6_13_deleteRosterByDate()
    {
        $MesTitle = get_phrases(['shift', 'record']);

        $id = $this->request->getVar('id');
        $start_date = $this->request->getVar('start_date');
        $end_date = $this->request->getVar('end_date');

        // Check if the roster already assigned for any employee, then not allow to delete
        $roster_assigned = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_emproster_assign', array('roster_id'=>$id));

        if($roster_assigned){
            
            $response = array(
                'success'  =>"exist",
                'message'  => get_phrases(['roster', 'already', 'assigned']),
                'title'    => $MesTitle
            );

            echo json_encode($response);
            exit;
        }
        // End of Check if the roster already assigned for employess 

        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_06_Deleted('hrm_duty_roster', array('roster_id'=>$id));

        if(!empty($data)){

            // Store log data
            $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['roster']), get_phrases(['deleted']), $id, 'hrm_duty_roster', 3);

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
    | rosterAssignList
    *--------------------------*/
    public function bdtaskt1c6_14_rosterAssignList()
    {
        $data['title']      = get_phrases(['assigned','employee','list']);
        $data['moduleTitle']= get_phrases(['human','resource']);
        $data['isDTables']  = true;
        $data['module']     = "Human_resource";
        $data['page']       = "duty_roster/shift_assign_list";

        $data['hasReadAccess']    = $this->permission->method('roster_assign', 'read')->access();
        $data['hasCreateAccess']  = $this->permission->method('roster_assign', 'create')->access();
        $data['hasPrintAccess']   = $this->permission->method('roster_assign', 'print')->access();
        $data['hasExportAccess']  = $this->permission->method('roster_assign', 'export')->access();
        $data['hasDeleteAccess']  = $this->permission->method('roster_assign', 'delete')->access();

        $data['sftasn_datalist']   = $this->bdtaskt1c1_07_DutyRoster->bdtaskt1m8_05_shiftassignData();

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);

    }

    /*--------------------------
    | rosterAssignList
    *--------------------------*/
    public function bdtaskt1c6_15_rosterShiftAssignAdd()
    {
        $data['title']      = get_phrases(['roster','assign']);
        $data['moduleTitle']= get_phrases(['human','resource']);
        $data['isDTables']  = true;
        $data['module']     = "Human_resource";
        $data['page']       = "duty_roster/shift_assign_add";

        $data['roster_list'] = $this->bdtaskt1c1_07_DutyRoster->bdtaskt1m8_04_rosterShiftAssignAdd();
        // $data['emp_list']   = $this->bdtaskt1c1_07_DutyRoster->bdtaskt1m8_05_empData($duty_roster->department_id);

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);

    }

    /*--------------------------
    | createShiftAssign
    *--------------------------*/
    public function bdtaskt1c6_16_createShiftAssign()
    {

        if ($this->request->getMethod() == 'post') {

            $rules = [
                'roster_id' => ['label' => get_phrases(['roster']),'rules' => 'required'],
            ];
        }

        if (! $this->validate($rules)) {

            $this->session->setFlashdata('exception', get_phrases(['roster', 'is', 'required']));
            return redirect()->route('human_resources/duty_roster/roster_shift_assign');

        }else{

            $array = array();
            $roster_id = $this->request->getVar('roster_id');
            $total_emp = $this->request->getVar('emp_id');

            $emp_id = $this->request->getVar('emp_id');
            
            $rstr_start_date = $this->request->getVar('rstr_start_date');
            $rstr_end_date = $this->request->getVar('rstr_end_date');

            $rstr_start_time = $this->request->getVar('rstr_start_time');
            $rstr_end_time = $this->request->getVar('rstr_end_time');
            
            $firstdate = strtotime($rstr_start_date);
            $lastdate = strtotime($rstr_end_date);

            // Get roster and shift data
            $roster_info = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_duty_roster', array('roster_id'=>$roster_id));
            $shift_info = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_empwork_shift', array('shiftid'=>$roster_info->shift_id));
            // Ends

            // $test_arr = array(); ////////////

            for ($i=0, $n=count($total_emp); $i < $n; $i++) {
                    
                $employee_id = $emp_id[$i];

                // $second_arr = array(); //////////////
                // $j = 0;

                for ($currentDate = $firstdate; $currentDate <= $lastdate; $currentDate += (86400)) {
                                                
                    $perdate = date('Y-m-d', $currentDate);
                    $enddate = date('Y-m-d', $currentDate);

                    $new_current_date = 0;

                    if(strtotime($shift_info->shift_start) > strtotime($shift_info->shift_end)){
                        $next_day_date = $currentDate + 86400;
                        $enddate = date('Y-m-d', $next_day_date);
                    }

                    $postData = array(
                        'roster_id'             =>$roster_id,
                        'emp_id'                =>$employee_id,
                        'emp_startroster_date'  =>$perdate,
                        'emp_endroster_date'    =>$enddate,
                        'emp_startroster_time'  =>$rstr_start_time,
                        'emp_endroster_time'    =>$rstr_end_time,
                    );

                    // $second_arr[$j] = $postData;
                    // $j++;

                    $postData['CreateDate'] = date("Y-m-d h:i:s");
                    $postData['CreateBy']   = $this->session->get('id');
                    
                    $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_01_Insert('hrm_emproster_assign',$postData);

                    if($result){

                         $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['roster','assign']), get_phrases(['created']), $result, 'hrm_emproster_assign', 1);
                    }
                }

                 // $test_arr[$i] = $second_arr;
            }

            // echo "<pre>";
            // echo print_r($test_arr);exit;
           
           
            $this->session->setFlashdata('message', get_phrases(['created', 'Successfully']));
            return redirect()->route('human_resources/duty_roster/roster_assign');

        }
    }

    public function bdtaskt1c6_17_empdatashow(){

        $roster_id =  $this->request->getVar('roster_id');

        $builder3 = $this->db->table('hrm_duty_roster');
        $builder3->select("*");
        $builder3->where('roster_id',$roster_id);

        $duty_roster = $builder3->get()->getRow();

        $data['rstrt_date']   = $duty_roster->roster_start; //roster start date
        $data['rend_date']    = $duty_roster->roster_end; //roster end date
        $data['roster_id']    = $roster_id; //roster end date
        $data['title']        = get_phrases(['roster','list']);

        $data['module']     = "Human_resource";
        $data['page']       = "duty_roster/employeelistview";
        
        $data['emp_list']   = $this->bdtaskt1c1_07_DutyRoster->bdtaskt1m8_05_empData($duty_roster->department_id);

        // echo json_encode($data);exit;

        return view('App\Modules\Human_resource\Views\duty_roster\employeelistview',$data);
        
    }

    public function bdtaskt1c6_18_rosteDateTimedata(){

        $roster_id =  $this->request->getVar('roster_id');

        $builder3 = $this->db->table('hrm_duty_roster');
        $builder3->select('hrm_duty_roster.*,hrm_empwork_shift.*');
        $builder3->join('hrm_empwork_shift','hrm_empwork_shift.shiftid = hrm_duty_roster.shift_id','left');
        $builder3->where('roster_id', $roster_id);

        $query = $builder3->get()->getRow();

        echo json_encode($query);
    }

    public function bdtaskt1c6_19_deleteShiftassign(){

        $roster_id =  $this->request->getVar('roster_id');
        $emp_id =  $this->request->getVar('emp_id');

        $MesTitle = get_phrases(['roster', 'assign']);

        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_06_Deleted('hrm_emproster_assign', array('roster_id'=>$roster_id,'emp_id'=>$emp_id));

        if(!empty($data)){

            // Store log data
            $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['roster','assign']), get_phrases(['deleted']), $roster_id, 'hrm_emproster_assign', 3);

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
    | shiftRosterList , which are assigned to employees only those roster will come
    *--------------------------*/
    public function bdtaskt1c6_20_shiftRosterList()
    {
        $data['title']      = get_phrases(['assigned','roster','list']);
        $data['moduleTitle']= get_phrases(['human','resource']);
        $data['isDTables']  = true;
        $data['module']     = "Human_resource";
        $data['page']       = "duty_roster/shift_roster_list";

        $data['hasReadAccess']    = $this->permission->method('roster_assign', 'read')->access();
        $data['hasUpdateAccess']  = $this->permission->method('roster_assign', 'update')->access();
        $data['hasCreateAccess']  = $this->permission->method('roster_assign', 'create')->access();
        $data['hasPrintAccess']   = $this->permission->method('roster_assign', 'print')->access();
        $data['hasExportAccess']  = $this->permission->method('roster_assign', 'export')->access();
        $data['hasDeleteAccess']  = $this->permission->method('roster_assign', 'delete')->access();

        $data['sftrosterlist']   = $this->bdtaskt1c1_07_DutyRoster->bdtaskt1m8_06_shiftRosterData();

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);

    }

    /*--------------------------
    | update_shiftAssignForm
    *--------------------------*/
    public function bdtaskt1c6_21_update_shiftAssignForm($roster_id)
    {
        $data['roster_id'] = $roster_id;
        $data['title']      = get_phrases(['roster','assign']);
        $data['moduleTitle']= get_phrases(['human','resource']);
        $data['isDTables']  = true;
        $data['module']     = "Human_resource";
        $data['page']       = "duty_roster/shift_assign_edit";

        $data['rstasninfo']    = $this->bdtaskt1c1_07_DutyRoster->bdtaskt1m8_07_rstasnInfoDataById($roster_id);

        $builder3 = $this->db->table('hrm_duty_roster');
        $builder3->select("*");
        $builder3->where('roster_id',$roster_id);

        $duty_roster = $builder3->get()->getRow();

        $data['editemp_list']  = $this->bdtaskt1c1_07_DutyRoster->bdtaskt1m8_05_empData($duty_roster->department_id);

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);

    }

    /*--------------------------
    | addSingleEmpRoster
    *--------------------------*/
    public function bdtaskt1c6_22_update_addSingleEmpRoster()
    {

        $MesTitle = get_phrases(['roster', 'assign']);

        $today           = date('Y-m-d');
        $roster_id       = $this->request->getVar('roster_id');
        $emp_id          = $this->request->getVar('emp_id');
        $rstr_start_date = $this->request->getVar('rstr_start_date');
        $rstr_end_date   = $this->request->getVar('rstr_end_date');
        $rstr_start_time = $this->request->getVar('rstr_start_time');
        $rstr_end_time   = $this->request->getVar('rstr_end_time');

        $firstdate       = strtotime($rstr_start_date);
        $lastdate        = strtotime($rstr_end_date);
        $tomorrow        = date("Y-m-d", strtotime("+1 day"));

        // Get roster and shift data
        $roster_info = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_duty_roster', array('roster_id'=>$roster_id));
        $shift_info = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_empwork_shift', array('shiftid'=>$roster_info->shift_id));
        // Ends

        if ($rstr_start_date < $today && $rstr_end_date > $today) {

            // It will check, if any roster assign employee where current_date is greater than roster_start date and less than roster_end date.. then from next day of current date. roster time shift will be assigned to the selected employee
            for ($currentDate = strtotime($tomorrow); $currentDate <= $lastdate; $currentDate += (86400)) {
                                                
                $perdate = date('Y-m-d', $currentDate);
                $enddate = date('Y-m-d', $currentDate);

                $new_current_date = 0;
                // Checking, if the start time is greater than end time means, shift is night or day over shift.. then end date will be next day date
                if(strtotime($shift_info->shift_start) > strtotime($shift_info->shift_end)){
                    $next_day_date = $currentDate + 86400;
                    $enddate = date('Y-m-d', $next_day_date);
                }
                // End

                $postData = array(
                    'roster_id'             =>$roster_id,
                    'emp_id'                =>$emp_id,
                    'emp_startroster_date'  =>$perdate,
                    'emp_endroster_date'    =>$enddate,
                    'emp_startroster_time'  =>$rstr_start_time,
                    'emp_endroster_time'    =>$rstr_end_time,
                );

                $postData['CreateDate'] = date("Y-m-d h:i:s");
                $postData['CreateBy']   = $this->session->get('id');
                
                $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_01_Insert('hrm_emproster_assign',$postData);

                if($result){

                     $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['roster','assign']), get_phrases(['created']), $result, 'hrm_emproster_assign', 1);
                }
            }

            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['employee','is','added','in','this','roster']),
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;
        }
        else {
            for ($currentDate = $firstdate; $currentDate <= $lastdate; $currentDate += (86400)) {                            
                $perdate = date('Y-m-d', $currentDate);
                $enddate = date('Y-m-d', $currentDate);

                $new_current_date = 0;
                // Checking, if the start time is greater than end time means, shift is night or day over shift.. then end date will be next day date
                if(strtotime($shift_info->shift_start) > strtotime($shift_info->shift_end)){
                    $next_day_date = $currentDate + 86400;
                    $enddate = date('Y-m-d', $next_day_date);
                }
                // End

                $postData2 = array(
                    'roster_id'             =>$roster_id,
                    'emp_id'                =>$emp_id,
                    'emp_startroster_date'  =>$perdate,
                    'emp_endroster_date'    =>$enddate,
                    'emp_startroster_time'  =>$rstr_start_time,
                    'emp_endroster_time'    =>$rstr_end_time,  
                );

                $postData2['CreateDate'] = date("Y-m-d h:i:s");
                $postData2['CreateBy']   = $this->session->get('id');
                
                $result2 = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_01_Insert('hrm_emproster_assign',$postData2);

                if($result2){

                     $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['roster','assign']), get_phrases(['created']), $result2, 'hrm_emproster_assign', 1);
                }

            }

            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['employee','is','added','in','this','roster']),
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;
        }

    }

    /*--------------------------
    | romoveSingleEmpRoster
    *--------------------------*/
    public function bdtaskt1c6_23_update_romoveSingleEmpRoster(){

        $MesTitle = get_phrases(['roster', 'assign']);

        $today           = date('Y-m-d');
        $roster_id       = $this->request->getVar('roster_id');
        $emp_id          = $this->request->getVar('emp_id');
        $rstr_start_date = $this->request->getVar('rstr_start_date');
        $rstr_end_date   = $this->request->getVar('rstr_end_date');

        if ($rstr_start_date < $today && $rstr_end_date > $today) {

            $result = $this->db->table('hrm_emproster_assign')->where('emp_id',$emp_id)->where('roster_id',$roster_id)->where('emp_startroster_date >',$today)->delete();

            if($result){
                $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['roster','assign']), get_phrases(['deleted']), $roster_id, 'hrm_emproster_assign', 3);

                $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['employee','is','removed','from','this','roster']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;

            }else{

                $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['something','went','wrong']),
                    'title'    => $MesTitle
                );

                echo json_encode($response);exit;
            }

        }else {
            
            $result2 = $this->db->table('hrm_emproster_assign')->where('roster_id',$roster_id)->where('emp_id',$emp_id)->where('roster_id',$roster_id)->delete();
            
            if($result2){
                $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['roster','assign']), get_phrases(['deleted']), $roster_id, 'hrm_emproster_assign', 3);

                $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['employee','is','removed','from','this','roster']),
                    'title'    => $MesTitle
                );

                echo json_encode($response);exit;
            }else{

                $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['something','went','wrong']),
                    'title'    => $MesTitle
                );

                echo json_encode($response);exit;

            }
        }
    }

    /*--------------------------
    | rosterEmpView
    *--------------------------*/
    public function bdtaskt1c6_24_rosterEmpView()
    {

        // echo json_encode($this->request->getVar());exit;

        $data['title']      = get_phrases(['roster','view']);
        $data['module']     = "Human_resource";
        $data['roster_id'] = $roster_id = $this->request->getVar('roster_id');

        $data['rstr_vdata']   = $this->bdtaskt1c1_07_DutyRoster->bdtaskt1m8_08_roster_viewdata($roster_id);
        $data['rosterempdata']= $this->bdtaskt1c1_07_DutyRoster->bdtaskt1m8_09_roster_emp_data($roster_id);

        // echo json_encode($data);exit;

        return view('App\Modules\Human_resource\Views\duty_roster\emp_roster_data_view',$data);

    }

    public function bdtaskt1c6_25_delete_assigned_roster(){

        $roster_id =  $this->request->getVar('roster_id');

        $MesTitle = get_phrases(['assigned','roster']);

        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_06_Deleted('hrm_emproster_assign', array('roster_id'=>$roster_id));

        if(!empty($data)){

            // Store log data
            $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['assigned','roster']), get_phrases(['deleted']), $roster_id, 'hrm_emproster_assign', 3);

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

    public function bdtaskt1c6_26_attendance_dashboard()
    { 
        $data['title']      = get_phrases(['attendance','dashboard']);
        $data['moduleTitle']= get_phrases(['human','resource']);
        $data['isDateTimes']= true;
        $data['module']     = "Human_resource";
        $data['page']       = "duty_roster/attendance_dashboard_view";

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);
    }

    public function bdtaskt1c6_27_loadallshift(){

        $data['title']      = get_phrases(['attendance','shift','view']);
        $data['module']     = "Human_resource";

        $data['cur_shlist']  = $this->bdtaskt1c1_07_DutyRoster->bdtaskt1m8_10_current_shift_list();
        $data['cur_emplist'] = $this->bdtaskt1c1_07_DutyRoster->bdtaskt1m8_11_current_date_emps();

        // echo json_encode($data);exit;
        
        return view('App\Modules\Human_resource\Views\duty_roster\attenshift_view',$data);
            
    }

    public function bdtaskt1c6_28_load_clkshftemp(){

        $data['title']      = get_phrases(['shift','data','view']);
        $data['module']     = "Human_resource";

        $clk_shiftid = $this->request->getVar('clk_shiftid');
        $clickdate = $this->request->getVar('clickdate');
        $data['clsh_datalist']= $this->bdtaskt1c1_07_DutyRoster->bdtaskt1m8_12_click_shift_data($clk_shiftid, $clickdate);
        $data['clkcngdate']      = $clickdate;

        return view('App\Modules\Human_resource\Views\duty_roster\clsh_data_view',$data);
            
    }

    public function bdtaskt1c6_29_loadcngdate(){

        $crdate = date("Y-m-d");

        $cndate = $this->request->getVar('cndate');

        $data['cng_shlist']  = $this->bdtaskt1c1_07_DutyRoster->bdtaskt1m8_13_cndate_shift_list($cndate);
        $data['cuuentshiftid'] = $this->bdtaskt1c1_07_DutyRoster->bdtaskt1m8_14_cuuentshiftid();
        if ($crdate == $cndate) {
            
            $data['cng_emplist'] = $this->bdtaskt1c1_07_DutyRoster->bdtaskt1m8_15_cng_date_currstr_emps($cndate);
        }else {
            $data['cng_emplist'] = $this->bdtaskt1c1_07_DutyRoster->bdtaskt1m8_16_cng_date_emps($cndate);
            
        }
        $data['change_date'] = $cndate;

        return view('App\Modules\Human_resource\Views\duty_roster\cngdate_view',$data);
            
    }

    public function bdtaskt1c6_30_updtempshift_frm(){

        $id = $this->request->getVar('id');
        $cldate = $this->request->getVar('cldate');
        
        $data['emp_id'] = $id;  
        $data['clk_date'] = $cldate;

        return view('App\Modules\Human_resource\Views\duty_roster\empshift_edit',$data);
    }

    public function bdtaskt1c6_31_load_checkedshift(){

        $chksh_id =  $this->request->getVar('chksh_id');

        $query = $this->db->table('hrm_empwork_shift')->select('*')->where('shiftid', $chksh_id)->get()->getRow();
        
        echo json_encode($query);
    }

    public function bdtaskt1c6_32_load_checkedroster(){

        $chksh_id =  $this->request->getVar('chksh_id');
        $cng_date =  $this->request->getVar('cng_date');

        $query = $this->db->table('hrm_duty_roster')->select('*')->where('roster_start <=', $cng_date)->where('roster_end >=', $cng_date)->where('shift_id', $chksh_id)->get()->getRow();
    
        echo json_encode($query); 
    }

    public function  bdtaskt1c6_33_emp_shift_update($id = null)
    {

        $postData = array(   
            
            'sftasnid'              => $this->request->getVar('sftasnid'),
            'roster_id'             => $this->request->getVar('roster_id'),
            'emp_id'                => $this->request->getVar('emp_id'),
            'emp_startroster_date'  => $this->request->getVar('emp_startroster_date'),
            'emp_endroster_date'    => $this->request->getVar('emp_endroster_date'),
            'emp_startroster_time'  => $this->request->getVar('emp_startroster_time'),
            'emp_endroster_time'    => $this->request->getVar('emp_endroster_time'),
            'is_edited'             => 1,
        );

        // echo "<pre>";
        // echo json_encode($postData);exit;  

        if ($this->request->getMethod() == 'post') {

            $rules = [
                'sftasnid'            => ['label' => get_phrases(['shift','id']),'rules' => 'required'],
                'roster_id'           => ['label' => get_phrases(['roster', 'id']),'rules' => 'required'],
                'emp_id'              => ['label' => get_phrases(['emp', 'id']),'rules' => 'required'],
                'emp_startroster_date'=> ['label' => get_phrases(['start','roster', 'date']),'rules' => 'required'],
                'emp_endroster_date'  => ['label' => get_phrases(['end','roster', 'date']),'rules' => 'required'],
                'emp_startroster_time'=> ['label' => get_phrases(['start','roster', 'time']),'rules' => 'required'],
                'emp_endroster_time'  => ['label' => get_phrases(['start','roster', 'time']),'rules' => 'required'],
            ];
        }

        if (! $this->validate($rules)) {
            // $response = array(
            //     'success'  =>false,
            //     'message'  => $this->validator->listErrors(),
            //     'title'    => $MesTitle
            // );

             $this->session->setFlashdata('exception',$this->validator->listErrors());

        }else{

            if ($this->bdtaskt1c1_07_DutyRoster->bdtaskt1m8_17_emp_data_shift_update($postData)) { 

                // Store log data
                $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['change','shift','from','attendance','dashboard']), get_phrases(['updated']), $postData['sftasnid'], 'hrm_emproster_assign', 2);

                $this->session->setFlashdata('message', get_phrases(['successfully', 'updated']));

            } else {

                $this->session->setFlashdata('exception', get_phrases(['please', 'try', 'again']));
            }
        }

        return redirect()->route('human_resources/duty_roster/attendance_dashboard');

    }

    public function get_department_shifts($department_id){

        $shift_list = $this->bdtaskt1c1_07_DutyRoster->department_shift_list($department_id);

        // echo json_encode($shift_list);
        // exit;

        echo  "<option value=''>Select shift</option>";
        $html = "";
        foreach($shift_list as $data){
            $html .="<option value='$data->shiftid'>$data->shift_name</option>";
            
        }
        echo $html;

    }













    //////////////////////////////////////////// Othetrs required functions /////////////////////////////////////

    public function bdtaskt1c6_checkShiftTimeUnderExistingShift($data){

        $department_id =  $data['department_id'];
        $shift_start   =  $data['shift_start'];
        $shift_end     =  $data['shift_end'];

        $builder3 = $this->db->table("hrm_empwork_shift");
        $builder3->select("*");
        $builder3->where('department_id', $department_id);
        $result = $builder3->get()->getResult();

        return $result;
        
    }

    public function bdtaskt1c6_shiftTimeUnderExistingShiftExceptExisting($data , $shift_id = null){

        $department_id =  $data['department_id'];
        $shift_start   =  $data['shift_start'];
        $shift_end     =  $data['shift_end'];

        $builder3 = $this->db->table("hrm_empwork_shift");
        $builder3->select("*");
        $builder3->where('department_id', $department_id);
        $builder3->where('shiftid !=', $shift_id);
        $result = $builder3->get()->getResult();

        return $result;
        
    }

    function isBetween($from, $till = null, $input_start = null, $input_end = null){

        $from_time  = date('H:i',strtotime($from));
        $to_time    = date('H:i',strtotime($till));

        $input_start_time = date('H:i',strtotime($input_start));
        $input_end_time = date('H:i',strtotime($input_end));

        $from_date = "";
        $to_date   = "";

        if($from_time > $to_time){

            $from_date = date('Y')."-1-1 ".$from_time;
            $to_date   = date('Y')."-1-2 ".$to_time;

        }else{

            $from_date = date('Y')."-1-1 ".$from_time;
            $to_date   = date('Y')."-1-1 ".$to_time;
        }

        $input_start_date = "";
        $input_end_date   = "";

        if($input_start_time > $input_end_time){

            $input_start_date = date('Y')."-1-1 ".$input_start_time;
            $input_end_date   = date('Y')."-1-2 ".$input_end_time;

        }else{

            $input_start_date = date('Y')."-1-1 ".$input_start_time;
            $input_end_date   = date('Y')."-1-2 ".$input_end_time;

        }

        // Starting comparing, if any input time under existing shift time
        if(strtotime($input_start_date) >= strtotime($from_date) && strtotime($input_start_date) <= strtotime($to_date)){

            return true;
        }
        if(strtotime($input_end_date) >= strtotime($from_date) && strtotime($input_end_date) <= strtotime($to_date)){

            return true;
        }

        // $arr['from_date']        = $from_date;
        // $arr['to_date']          = $to_date;
        // $arr['input_start_date'] = $input_start_date;
        // $arr['input_end_date']   = $input_end_date;

        // return $arr;

    }

    function isBetweenWhenUpdate($from, $till = null, $input_start = null, $input_end = null){

        $exist_start  = $this->time::createFromFormat('!H:i', $from);
        $exist_end    = $this->time::createFromFormat('!H:i', $till);

        $shift_start   =  $this->time::createFromFormat('!H:i', $input_start);
        $shift_end     =  $this->time::createFromFormat('!H:i', $input_end);

        if ($exist_start > $exist_end) $exist_end->modify('+1 day');
        
        //Check shift start is falling in between the existing shift time....
        if(($exist_start <= $shift_start && $shift_start <= $exist_end) || ($exist_start <= $shift_start->modify('+1 day') && $shift_start <= $exist_end)){

            return true;
        }
        //Check shift end is falling in between the existing shift time....
        if(($exist_start <= $shift_end && $shift_end <= $exist_end) || ($exist_start <= $shift_end->modify('+1 day') && $shift_end <= $exist_end)){

            return true;
        }

    }

    public function randID()
    {
        $t=time();
        $result = ""; 
        $chars = $t."ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        $charArray = str_split($chars);
        for($i = 0; $i < 7; $i++) {
            $randItem = array_rand($charArray);
            $result .="".$charArray[$randItem];
        }
        return "RS".$result;
    }



}