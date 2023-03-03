<?php namespace App\Modules\Human_resource\Controllers;
use CodeIgniter\Controller;
use App\Modules\Human_resource\Models\Bdtaskt1m5Holidays;
use App\Modules\Human_resource\Models\Bdtaskt1m1Employee;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1m9c4Holidays extends BaseController
{
    private $bdtaskt1c1_02_Holidays;
    private $bdtaskt1c1_01_EmpModel;
    private $bdtaskt1c1_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->db = db_connect();

        $this->permission = new Permission();
        $this->bdtaskt1c1_02_Holidays = new Bdtaskt1m5Holidays();
        $this->bdtaskt1c1_01_EmpModel = new Bdtaskt1m1Employee();
        $this->bdtaskt1c1_02_CmModel = new Bdtaskt1m1CommonModel();
    }


    /* Employee Basic salary CRUD starts*/

    /*--------------------------
    | basicSalaryList
    *--------------------------*/
    public function bdtask1c4_01_weekendsList()
    {
        $data['title']      = get_phrases(['weekends']);
        $data['moduleTitle']= get_phrases(['human', 'resource']);
        $data['isDTables']  = true;
        $data['module']     = "human_resource";
        $data['page']       = "holidays/weekends_list";

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | getBasicSalaryList
    *--------------------------*/
    public function bdtask1c4_02_getWeekendsList()
    {

        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c1_02_Holidays->bdtaskt1m1_01_getWeekendsList($postData);
        echo json_encode($data);
    }

    /*--------------------------
    |_basicSalaryCreate / Edit
    *--------------------------*/
    public function bdtask1c4_03_add_weekEndDays(){

        $MesTitle = get_phrases(['weekends']);

        $id = $this->request->getVar('id');
        $action = $this->request->getVar('action');

        if($this->request->getVar('week_ends') == null){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['must', 'select','weekends']),
                'title'    => $MesTitle,
            );

            echo json_encode($response);exit;
        }

        if(count($this->request->getVar('week_ends')) == 7){

            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['should', 'not','select', 'all', 'days', 'as' ,'weekends']),
                'title'    => $MesTitle,
            );

            echo json_encode($response);exit;

        }

        $data = array(
            'weekend_days' => implode(',', $this->request->getVar('week_ends')),
        );

        // echo json_encode($data);exit;
       
        if($action=='update'){

            $data['UpdateBy']   = session('id');
            $data['UpdateDate'] = date("Y-m-d h:i:s");

            // echo json_encode($data);exit;

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_02_Update('hrm_weekends', $data, array('weekend_id'=>$id));
            if($result){
                // Store log data
                $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['weekends']), get_phrases(['updated']), $id, 'hrm_weekends', 2);
                
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

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('hrm_weekends', $data);
            if($result){
                // Store log data
                $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['weekends']), get_phrases(['created']), $result, 'hrm_weekends', 1);
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
    | basicSalaryById
    *--------------------------*/
    public function bdtask1c4_04_getWeekEndDaysById($id)
    {

        $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_weekends', array('weekend_id'=>$id));

        if(!empty($result)){
            $weekends = explode(',', $result->weekend_days);
            $result->week_ends_days = $weekends;
        }else{
            $weekends = array();
            $result->week_ends_days = $weekends;
        }
        echo json_encode($result);
        exit();
    }



    /* Employee Basic salary CRUD endss*/

    /* Employee Allowance CRUD starts*/

    /*--------------------------
    | basicSalaryList
    *--------------------------*/
    public function bdtask1c4_05_govt_holidays()
    {
        $data['title']      = get_phrases(['goverment','holidays']);
        $data['moduleTitle']= get_phrases(['human', 'resource']);
        $data['isDTables']  = true;
        $data['isDateTimes']= true;
        $data['module']     = "human_resource";
        $data['page']       = "holidays/goverment_holidays";

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | getBasicSalaryList
    *--------------------------*/
    public function bdtask1c4_06_govt_holidays_list()
    {

        $postData = $this->request->getVar();
       $data = $this->bdtaskt1c1_02_Holidays->bdtaskt1m1_02_govt_holidays_list($postData);
        echo json_encode($data);
    }



    /*--------------------------
    |_basicSalaryCreate / Edit
    *--------------------------*/
    public function bdtask1c4_07_addGovtHoliday(){

        $MesTitle = get_phrases(['government', 'holidays']);

        $id = $this->request->getVar('id');
        $action = $this->request->getVar('action');

        // Validation Checking
        if($this->request->getVar('holiday_name') == '' || $this->request->getVar('start_date') == '' || $this->request->getVar('end_date') == ''){
            $response = array(
                'success'  =>false,
                'message'  => get_notify('please_fill_up_all_required_fields'),
                'title'    => $MesTitle,
            );

            echo json_encode($response);exit;
        }

        if(strtotime($this->request->getVar('start_date')) > strtotime($this->request->getVar('end_date'))){

            $response = array(
                'success'  =>false,
                'message'  => get_notify('start_date_must_be_smaller_than_end_date'),
                'title'    => $MesTitle,
            );

            echo json_encode($response);exit;

        } 

        // Count holidays elminating the weekends

        $date_ranges = $this->date_range_by_one($this->request->getVar('start_date'), $this->request->getVar('end_date'), $step = '+1 day', $output_format = 'Y-m-d' );
        $weekends = explode(',', $this->bdtaskt1c1_02_Holidays->get_weekends()->weekend_days);

        $no_of_days = 0;
        foreach ($date_ranges as $key => $date) {

            $dayname = date("l",strtotime($date));
            if(!in_array($dayname,$weekends)){

                $no_of_days = $no_of_days + 1;
            }
        }

        // End of validation

        $data = array(
            'holiday_name' => $this->request->getVar('holiday_name'),
            'start_date'   => $this->request->getVar('start_date'),
            'end_date'     => $this->request->getVar('end_date'),
            'no_of_days'   => $no_of_days,
        );

        // echo json_encode($data);exit;
       
        if($action=='update'){

            /////////////////////// UPDATING RECORD/////////////////////////////////

            // Check , if the start and end date changed form the existing date
            $govt_holiday = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_holidays', array('holiday_id'=>$id));

            if(strtotime($govt_holiday->start_date) != strtotime($this->request->getVar('start_date')) || strtotime($govt_holiday->end_date) != strtotime($this->request->getVar('end_date'))){

                // This is for checking, if the start date belongs to the date range of current holiday which is updating but end_date is greater than the end_date of the current record...which is updating
                if ((strtotime($govt_holiday->start_date) <= strtotime($this->request->getVar('start_date')) && strtotime($govt_holiday->end_date) >= strtotime($this->request->getVar('start_date'))) && (strtotime($govt_holiday->end_date) < strtotime($this->request->getVar('end_date')))) {
   
                    $end_date_in_other_holiday = $this->bdtaskt1c1_02_Holidays->duplicate_end_date_for_existing_holiday($id,$this->request->getVar('end_date'));
                    if($end_date_in_other_holiday){
                        $response = array(
                            'success'  =>false,
                            'message'  => get_notify('end_date_belongs_to_another_holiday'),
                            'title'    => $MesTitle,
                        );
                        echo json_encode($response);exit;
                    }

                    $invalid_start_end_date_for_holiday = $this->bdtaskt1c1_02_Holidays->invalid_start_end_date_for_existing_holiday($id,$this->request->getVar('start_date'),$this->request->getVar('end_date'));

                    // echo json_encode($invalid_start_end_date_for_holiday);exit;

                    if($invalid_start_end_date_for_holiday ){
                         $response = array(
                            'success'  =>false,
                            'message'  => get_notify('invalid_start_and_end_date_entry'),
                            'title'    => $MesTitle,
                        );
                        echo json_encode($response);exit;
                    }

                }
                elseif ((strtotime($govt_holiday->start_date) > strtotime($this->request->getVar('start_date'))) && (strtotime($govt_holiday->start_date) <= strtotime($this->request->getVar('end_date')) && strtotime($govt_holiday->end_date) >= strtotime($this->request->getVar('end_date')))) {

                    // This is for checking, if the end date belongs to the date range of current holiday which is updating but start_date is lesser than the start_date of the current record...which is updating
                    $end_date_in_other_holiday = $this->bdtaskt1c1_02_Holidays->duplicate_start_date_for_existing_holiday($id,$this->request->getVar('start_date'));
                    if($end_date_in_other_holiday){
                        $response = array(
                            'success'  =>false,
                            'message'  => get_notify('start_date_belongs_to_another_holiday'),
                            'title'    => $MesTitle,
                        );
                        echo json_encode($response);exit;
                    }

                    $invalid_start_end_date_for_holiday = $this->bdtaskt1c1_02_Holidays->invalid_start_end_date_for_existing_holiday($id,$this->request->getVar('start_date'),$this->request->getVar('end_date'));

                    // echo json_encode($invalid_start_end_date_for_holiday);exit;

                    if($invalid_start_end_date_for_holiday ){
                         $response = array(
                            'success'  =>false,
                            'message'  => get_notify('invalid_start_and_end_date_entry'),
                            'title'    => $MesTitle,
                        );
                        echo json_encode($response);exit;
                    }
                    
                }
                else{

                    // Check , if the selected date range already taken as input for holiday

                    $duplicate_start_date_for_existing_holiday = $this->bdtaskt1c1_02_Holidays->duplicate_start_date_for_existing_holiday($id,$this->request->getVar('start_date'));
                    if($duplicate_start_date_for_existing_holiday){
                        $response = array(
                            'success'  =>false,
                            'message'  => get_notify('start_date_belongs_to_another_holiday'),
                            'title'    => $MesTitle,
                        );
                        echo json_encode($response);exit;
                    }

                    $duplicate_end_date_for_existing_holiday = $this->bdtaskt1c1_02_Holidays->duplicate_end_date_for_existing_holiday($id,$this->request->getVar('end_date'));
                    if($duplicate_end_date_for_existing_holiday){
                        $response = array(
                            'success'  =>false,
                            'message'  => get_notify('end_date_belongs_to_another_holiday'),
                            'title'    => $MesTitle,
                        );
                        echo json_encode($response);exit;
                    }

                    $invalid_start_end_date_for_holiday = $this->bdtaskt1c1_02_Holidays->invalid_start_end_date_for_existing_holiday($id,$this->request->getVar('start_date'),$this->request->getVar('end_date'));

                    // echo json_encode($invalid_start_end_date_for_holiday);exit;

                    if($invalid_start_end_date_for_holiday ){
                         $response = array(
                            'success'  =>false,
                            'message'  => get_notify('invalid_start_and_end_date_entry'),
                            'title'    => $MesTitle,
                        );
                        echo json_encode($response);exit;
                    }
                }
            }

            // exit;

            // End

            $data['UpdateBy']   = session('id');
            $data['UpdateDate'] = date("Y-m-d h:i:s");

            // echo json_encode($data);exit;

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_02_Update('hrm_holidays', $data, array('holiday_id'=>$id));

            if($result){
                // Store log data
                $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['government','holidays']), get_phrases(['updated']), $id, 'hrm_holidays', 2);
                
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

            /////////////////////// ADDING RECORD/////////////////////////////////

            // Check , if the selected date range already taken as input for holiday

            $start_date_in_other_holiday = $this->bdtaskt1c1_02_Holidays->duplicate_start_date_for_holiday($this->request->getVar('start_date'));
            if($start_date_in_other_holiday){
                $response = array(
                    'success'  =>false,
                    'message'  => get_notify('start_date_belongs_to_another_holiday'),
                    'title'    => $MesTitle,
                );
                echo json_encode($response);exit;
            }

            $end_date_in_other_holiday = $this->bdtaskt1c1_02_Holidays->duplicate_end_date_for_holiday($this->request->getVar('end_date'));
            if($end_date_in_other_holiday){
                $response = array(
                    'success'  =>false,
                    'message'  => get_notify('end_date_belongs_to_another_holiday'),
                    'title'    => $MesTitle,
                );
                echo json_encode($response);exit;
            }

            $invalid_start_end_date_for_holiday = $this->bdtaskt1c1_02_Holidays->invalid_start_end_date_for_holiday($this->request->getVar('start_date'),$this->request->getVar('end_date'));
            if($invalid_start_end_date_for_holiday ){
                 $response = array(
                    'success'  =>false,
                    'message'  => get_notify('invalid_start_and_end_date_entry'),
                    'title'    => $MesTitle,
                );
                echo json_encode($response);exit;
            }

            // echo json_encode($data);
            // exit;

            // End

            $data['CreateBy']   = session('id');
            $data['CreateDate'] = date("Y-m-d h:i:s");

            // echo json_encode($data);exit;

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('hrm_holidays', $data);
            if($result){
                // Store log data
                $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['government','holidays']), get_phrases(['created']), $result, 'hrm_holidays', 1);
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
    |_basicSalaryCreate / Edit
    *--------------------------*/
    public function bdtask1c4_08_getNoOfGovtHolidays(){

        $MesTitle = get_phrases(['government', 'holidays']);

        // Validation Checking
        if($this->request->getVar('start_date') == '' || $this->request->getVar('end_date') == ''){
            $response = array(
                'success'  =>false,
                'message'  => get_notify('please_fill_up_start_and_end_date'),
                'title'    => $MesTitle,
            );

            echo json_encode($response);exit;
        }

        if(strtotime($this->request->getVar('start_date')) > strtotime($this->request->getVar('end_date'))){

            $response = array(
                'success'  =>false,
                'message'  => get_notify('start_date_must_be_smaller_than_end_date'),
                'title'    => $MesTitle,
            );

            echo json_encode($response);exit;

        } 

        // Count holidays elminating the weekends

        $date_ranges = $this->date_range_by_one($this->request->getVar('start_date'), $this->request->getVar('end_date'), $step = '+1 day', $output_format = 'Y-m-d' );
        $weekends = explode(',', $this->bdtaskt1c1_02_Holidays->get_weekends()->weekend_days);

        $no_of_days = 0;
        foreach ($date_ranges as $key => $date) {

            $dayname = date("l",strtotime($date));
            if(!in_array($dayname,$weekends)){

                $no_of_days = $no_of_days + 1;
            }
        }

        // End of validation

        $data = array(
            'no_of_days'   => $no_of_days,
        );

        echo json_encode($data);exit;

    }    


    /*--------------------------
    | allowanceById
    *--------------------------*/
    public function bdtask1c4_09_getGovtHolidayById($id)
    {

        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_holidays', array('holiday_id'=>$id));

        echo json_encode($data);
    }

    /*--------------------------
    | deleteBasicSalary
    *--------------------------*/
    public function bdtask1c4_10_deleteGovtHoliday($id)
    {

        $MesTitle = get_phrases(['government', 'holidays']);

        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_06_Deleted('hrm_holidays', array('holiday_id'=>$id));

        if(!empty($data)){

            // Store log data
            $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['government','holidays']), get_phrases(['deleted']), $id, 'hrm_holidays', 3);

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


    /* Employee Allowance CRUD ends*/





    /*   
        Image File validation check
    */
    public function image_file_type($type = ""){

        $file_types = ['jpg','jpeg','png'];

        if(in_array($type, $file_types)){

            return true;

        }else{

            return false;
        }
    }

    /*   
        Image File validation check
    */
    public function nid_file_type($type = ""){

        $file_types = ['pdf','doc','docx','jpg','jpeg','png'];

        if(in_array($type, $file_types)){

            return true;

        }else{

            return false;
        }
    }

    function date_range_by_one($first, $last, $step = '+1 day', $output_format = 'Y-m-d' ) {

        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);

        while( $current <= $last ) {

            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }

        return $dates;
    }
   
}
