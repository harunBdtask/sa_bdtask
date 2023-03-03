<?php namespace App\Modules\Human_resource\Controllers;

use CodeIgniter\Controller;
use App\Modules\Human_resource\Models\Bdtaskt1m8Payroll;
use App\Modules\Human_resource\Models\Bdtaskt1m1Employee;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

use CodeIgniter\I18n\Time;

class Bdtaskt1m9c7Payroll extends BaseController
{
    private $bdtaskt1c1_08_Payroll;
    private $bdtaskt1c1_01_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->db = db_connect();

        $this->permission = new Permission();
        $this->bdtaskt1c1_08_Payroll = new Bdtaskt1m8Payroll();
        $this->bdtaskt1c1_01_CmModel = new Bdtaskt1m1CommonModel();
        $this->bdtaskt1c1_01_EmpModel = new Bdtaskt1m1Employee();

        $this->time = new Time();

        $setting = $this->db->table('setting')->select('*')->get()->getRow();

        $timeZone = (!empty($setting->time_zone)?$setting->time_zone:'Asia/Dhaka');
        @date_default_timezone_set($timeZone);
    }


    public function index()
    {
        

        $data['title']      = get_phrases(['benefit','list']);
        $data['moduleTitle']= get_phrases(['human','resource']);
        $data['isDTables']  = true;
        $data['module']     = "Human_resource";
        $data['list']       = $this->bdtaskt1c1_08_Payroll->benefit_list();
        $data['page']       = "payroll/benefit_list"; 

        $data['hasReadAccess']    = $this->permission->method('benefit', 'read')->access();
        $data['hasCreateAccess']  = $this->permission->method('benefit', 'create')->access();
        $data['hasPrintAccess']   = $this->permission->method('benefit', 'print')->access();
        $data['hasExportAccess']  = $this->permission->method('benefit', 'export')->access();
        $data['hasDeleteAccess']  = $this->permission->method('benefit', 'delete')->access();

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);

    }

    public function bdtask_0001_benefits_form($id = null)
    {
        $data = [];

        $data['benefits'] = (object)$benefitdata = array(
            'id'              => ($this->request->getVar('id')?$this->request->getVar('id'):null),
            'benefit_name'    => $this->request->getVar('benefit_name', FILTER_SANITIZE_STRING),
            'benefit_type'    => $this->request->getVar('benefit_type', FILTER_SANITIZE_STRING),
            'status'          => $this->request->getVar('status',FILTER_SANITIZE_STRING),
        );

        if ($this->request->getMethod() == 'post') {

            $rules = [
            'benefit_name' => ['label' => get_phrases(['benefit','name']),'rules' => 'required'],
            'benefit_type' => ['label' => get_phrases(['benefit','type']),'rules' => 'required'],
            'status'       => ['label' => get_phrases(['status']),'rules'       => 'required'],

            ];

            if (! $this->validate($rules)) {
                $data['validation'] = $this->validator;
            }else{
                if(empty($id)){

                    $check = $this->bdtaskt1c1_08_Payroll->check_exist($benefitdata);

                    if($check == false){

                        $result = $this->bdtaskt1c1_08_Payroll->save_salary_benefit($benefitdata);

                        if($result){

                            // Store log data
                            $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['benefit', 'type', 'setup']), get_phrases(['created']), $result, 'hrm_salary_benefit', 1);

                            $this->session->setFlashdata('message', get_phrases(['save','successfully']));
                            return  redirect()->to(base_url('/human_resources/payroll/benefit_list/'));  
                        }else{

                            $this->session->setFlashdata('exception', get_phrases(['please','try','again']));
                            return  redirect()->to(base_url('/human_resources/payroll/benefit_list/'));
                        }

                    }else{

                        $this->session->setFlashdata('exception', 'Benefit Type Already Added');
                        return  redirect()->to(base_url('/human_resources/payroll/add_benefits/')); 
                    }


                }else{

                    $up_respo = $this->bdtaskt1c1_08_Payroll->update_salary_benefit($benefitdata);

                    if($up_respo){

                        // Store log data
                        $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['benefit', 'type', 'setup']), get_phrases(['updated']), $this->request->getVar('id'), 'hrm_salary_benefit', 2);

                        $this->session->setFlashdata('message', get_phrases(['successfully','updated']));
                    }else{

                        $this->session->setFlashdata('exception', get_phrases(['please','try','again']));
                    }

                    return  redirect()->to(base_url('/human_resources/payroll/benefit_list/'));

                }

            }
        }

        $data['moduleTitle']= get_phrases(['human','resource']);
        $data['module']           = "Human_resource";

        if(!empty($id)){

            $data['benefits']         = $this->bdtaskt1c1_08_Payroll->singledata($id); 
        }

        $data['title']            = get_phrases(['add','benefits']);
        $data['employee_list']    = $this->bdtaskt1c1_08_Payroll->employee_list();
        $data['page']             = "payroll/benefit_form"; 

        return $this->base_02_template->layout($data);
    }

    public function delete_benefit($id = null)
    { 
        if ($this->bdtaskt1c1_08_Payroll->delete_salary_benefit($id)) {

            // Store log data
            $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['benefit', 'type']), get_phrases(['deleted']), $id, 'hrm_salary_benefit', 3);

            $this->session->setFlashdata('message', get_phrases(['successfully','deleted']));
        } else {
            $this->session->setFlashdata('exception', get_phrases(['please','try','again']));
        }

        return  redirect()->to(base_url('/human_resources/payroll/benefit_list/'));
    }

    public function bdtask_0002_salarysetup_form()
    {

        $data['title']            = get_phrases(['salary','setup']);
        $data['slname']           = $this->bdtaskt1c1_08_Payroll->salary_typeName();
        $data['sldname']          = $this->bdtaskt1c1_08_Payroll->salary_typedName();
        $data['employee']         = $this->bdtaskt1c1_08_Payroll->employee_list();
        $data['moduleTitle']      = get_phrases(['human','resource']);
        $data['module']           = "Human_resource";
        $data['page']             = "payroll/salarysetup_form"; 

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data); 
    }

    public function employeebasic(){

        $id    = $this->request->getVar('employee_id');
        $employee_data  = $this->db->table('hrm_employees')->where('employee_id', $id)->get()->getRow();

        if($employee_data->salary_type == 'hourly'){
            $type = 'Hourly';
        }else{
            $type = 'Salary';   
        }

        //GET basic salary based on basic salary setup of the selected emplyees employee_type
        $basic_salary_setup  = $this->db->table('hrm_basic_salary_setup')->where('employee_type', $employee_data->employee_type)->get()->getRow();
        if($basic_salary_setup){

            $basic_salary = ($basic_salary_setup->percent / 100) * $employee_data->gross_salary;
            // echo json_encode($basic_salary_setup);exit;

            $data = array(
                'status'        =>  true,
                'basic'         =>  number_format($basic_salary,2),
                'basic_percent' =>  $basic_salary_setup->percent,
                'gross'         =>  $employee_data->gross_salary,
                'rate_type'     =>  $employee_data->salary_type,
                'stype'         =>  $type
            );
            echo json_encode($data);exit;

        }else{

            $data = array(
                'status'      =>  false,
                'msg' =>  get_notify("basic_salary_setup_is_not_completed_for_the_employee_type_of_this_employee")
            );
            echo json_encode($data);exit;
        }
        
    }

    public function salary_setup_entry()
    {
        $date=date('Y-m-d');
        $check_exist = $this->bdtaskt1c1_08_Payroll->check_exist_salarysetup($this->request->getVar('employee_id'));

        if($check_exist == 0){

            $amount=$this->request->getVar('amount');

            // Calculate the total additional percet with basic percent.... it should be 100 %, otherwise throw error message..
            $addition_percent = (int)$this->request->getVar('basic_percent');
            foreach($amount as $key=>$value)
            {
                $benefit_info = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_salary_benefit', array('id'=>$key));
                if(@(int)$benefit_info->benefit_type == 1){

                    $addition_percent = $addition_percent + $value;
                }
            }
            // If $addition_percent not 100 then show error
            if($addition_percent < 100){

                $this->session->setFlashdata('exception',get_notify('addition_percent_should_be_100_percent_along_with_basic_percent'));

                return redirect()->to(site_url('/human_resources/payroll/add_salarysetup'));
            }
            //End

            foreach($amount as $key=>$value)
            {   
                $postData = [
                'employee_id'           => $this->request->getVar('employee_id'),
                'sal_type'              => $this->request->getVar('sal_type'),
                'salary_type_id'        => $key,
                'amount'                => (!empty($value)?$value:0),
                'create_date'           => $date,
                'create_by'             => session('id'),
                'gross_salary'          => $this->request->getVar('gross_salary'),
                ]; 

                $this->bdtaskt1c1_08_Payroll->salary_setup_create($postData);

            }

            // Store log data
            $data['employee_id'] = $this->request->getVar('employee_id');
            $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['salary', 'setup']), get_phrases(['created']), $data, 'hrm_employee_salary_setup', 1);

            $this->session->setFlashdata('message',get_phrases(['save','successfully']));

            return redirect()->to(site_url('/human_resources/payroll/add_salarysetup'));

        }else{

            $this->session->setFlashdata('exception',get_phrases(['already','exist']));
            return redirect()->to(site_url('/human_resources/payroll/add_salarysetup'));
        }
    }

    public function bdtask_0005_salarysetup_list()
    {
        $data['title']            = get_phrases(['salary','setup','list']);
        $data['moduleTitle']       = get_phrases(['human','resource']);
        $data['isDTables']         = true;
        $data['module']           = "Human_resource";
        $data['page']             = "payroll/salarysetup_list"; 
        return $this->base_02_template->layout($data); 
    }

    public function bdtask_salarysetup_listdata()
    {
        $postData = $this->request->getVar();
        $data     = $this->bdtaskt1c1_08_Payroll->getsalarysetupList($postData);
        echo json_encode($data);
    }

    public function salsetup_upform($id)
    {
        $data['title']       = get_phrases(['edit','setup','update']);
        $data['data']        = $this->bdtaskt1c1_08_Payroll->salary_s_updateForm($id);
        $data['samlft']      = $this->bdtaskt1c1_08_Payroll->salary_typedName();
        $data['amo']         = $this->bdtaskt1c1_08_Payroll->salary_typeName();
        $data['employee']    = $this->bdtaskt1c1_08_Payroll->employee_list();
        $data['EmpRate']     = $EmpInfo = $this->bdtaskt1c1_08_Payroll->employee_informationId($id);
        $data['moduleTitle'] = get_phrases(['human','resource']);
        $data['module']      = "Human_resource";
        $data['page']        = "payroll/salary_setup_edit";

        // Get val;ue for tax field , if there is any
        $tax_field_value = 0;
        $employee_salary_setup_info  = $this->db->table('hrm_employee_salary_setup')->where('employee_id', $id)->get()->getResult();
        foreach ($employee_salary_setup_info as $key => $value) {
            
            //check if any not exist in benefit_type table
            $benefit_info = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_salary_benefit', array('id'=>$value->salary_type_id));
            if(!$benefit_info){

                $tax_field_value = $value->amount;
            }
        }
        $data['tax_field_value']   = $tax_field_value;
        //End

        //GET basic salary based on basic salary setup of the selected emplyees employee_type
        $basic_salary = 0;
        $basic_percent = 0;

        $basic_salary_setup  = $this->db->table('hrm_basic_salary_setup')->where('employee_type', $EmpInfo['0']['employee_type'])->get()->getRow();
        if($basic_salary_setup){

            $basic_salary = ($basic_salary_setup->percent / 100) * $EmpInfo['0']['gross_salary'];
            $basic_percent = $basic_salary_setup->percent;
        }
        $data['basic_salary']   = $basic_salary;
        $data['basic_percent']  = $basic_percent;


        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);  
    }

    public function salary_setup_update(){

        $date=date('Y-m-d');

        $amount=$this->request->getVar('amount');

        // Calculate the total additional percet with basic percent.... it should be 100 %, otherwise throw error message..
        $addition_percent = (int)$this->request->getVar('basic_percent');
        foreach($amount as $key=>$value)
        {
            $benefit_info = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_salary_benefit', array('id'=>$key));
            if(@(int)$benefit_info->benefit_type == 1){

                $addition_percent = $addition_percent + $value;
            }
        }
        // If $addition_percent not 100 then show error
        if($addition_percent < 100){

            $this->session->setFlashdata('exception',get_notify('addition_percent_should_be_100_percent_along_with_basic_percent'));

            return redirect()->to(site_url('/human_resources/payroll/edit_salary_setup/'.$this->request->getVar('emp_id')));
        }
        //End

        foreach($amount as $key=>$value){

            $postData = array(
                'employee_id'        => $this->request->getVar('emp_id'),
                'sal_type'           => $this->request->getVar('sal_type'),
                'salary_type_id'     => $key,
                'amount'             => $value,
                'gross_salary'       => $this->request->getVar('gross_salary'),
                'update_date'        => $date,
                'update_by'          => session('id'),
            );

            $this->bdtaskt1c1_08_Payroll->update_sal_stup($postData);
        }

        // Store log data
        $data['employee_id'] = $this->request->getVar('emp_id');
        $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['salary', 'setup']), get_phrases(['updated']), $data, 'hrm_employee_salary_setup', 2);

        $this->session->setFlashdata('message','Successfully Updated');    
        return redirect()->to(site_url('/human_resources/payroll/salary_setup_list'));
    }

    public function delete_salsetup($id = null) { 

        if ($this->bdtaskt1c1_08_Payroll->emp_salstup_delete($id)) {
            #set success message
            $this->session->setFlashdata('message',get_phrases(['successfully','deleted']));
        } else {
            #set error_message message
        $this->session->setFlashdata('exception',get_phrases(['please','try','again']));
        }
        return redirect()->to(site_url('/human_resources/payroll/salary_setup_list'));
    }

    public function bdtask_006_salary_generate()
    {

        $data['title']       = get_phrases(['salary','generate']);
        $data['moduleTitle']= get_phrases(['human','resource']);

        $data['departments']    = $this->bdtaskt1c1_08_Payroll->department_list();

        $data['module']      = "Human_resource";
        $data['page']        = "payroll/salary_generate"; 

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);  
    }

    public function delete_salgenerate($id = null) { 

        if ($this->bdtaskt1c1_08_Payroll->sal_generate_delete($id)) {

            $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['salary', 'sheet','generate']), get_phrases(['deleted']), $id, 'hrm_salary_sheet_generate', 3);

            #set success message
            $this->session->setFlashdata('message',get_phrases(['successfully','deleted']));
        } else {
            #set error_message message
            $this->session->setFlashdata('exception',get_phrases(['please','try','again']));
        }
       return redirect()->to(site_url('/human_resources/payroll/salary_sheet'));
    }


    public function bdtask_0009_salary_payment()
    {
        $data['title']         = get_phrases(['salary','payment']);
        $data['moduleTitle']   = get_phrases(['human','resource']);
        $data['module']        = "Human_resource";
        $data['isDTables']     = true;
        $data['page']          = "payroll/salary_payment"; 

        return $this->base_02_template->layout($data); 
    }


    public function bdtask_getSalarypayment_list()
     {
        $postData = $this->request->getVar();
        $data     = $this->bdtaskt1c1_08_Payroll->get_salarypaymentList($postData);
        echo json_encode($data);
    }


    public function create_salary_generate(){

        list($month,$year) = explode(' ',$this->request->getVar('myDate'));
        $department_id = $this->request->getVar('department_id');
        $month_number = $this->get_month_number($month);

        // Check, if salary already generated for the selected department employee for selected month - year
        $query =$this->db->table('hrm_salary_sheet_generate')->select('*')->where('department_id',$department_id)->where('gdate',$this->request->getVar('myDate'))->countAllResults();

        if ($query > 0) {
            $this->session->setFlashdata(array('exception' => get_phrases(['the','salary','of']).' '.$month.'-'.$year.' '. get_phrases(['already','generated'])));
            return redirect()->to(site_url('/human_resources/payroll/salary_generate'));
        }
        // End

        $employees = $this->db->table('hrm_employees')->where('department',$department_id)->get()->getResult();

        // Check all employees salary setup completed or not...
        if(count($employees)){
            foreach ($employees as $employee) {
                $salary_setup = $this->db->table('hrm_employee_salary_setup')->select('employee_id')->where('employee_id', $employee->employee_id)->get()->getRow();
                if(!$salary_setup){

                    $this->session->setFlashdata(array('exception' => get_notify('salary_setup_is_not_completed_for').' '.$employee->first_name.' '.$employee->last_name.' '.get_notify('for_selected_department')));
                    return redirect()->to(site_url('/human_resources/payroll/salary_generate'));
                }
            }
        }else{

            $this->session->setFlashdata(array('exception' => get_notify('no_employee_found_for_the_selected_department')));
            return redirect()->to(site_url('/human_resources/payroll/salary_generate'));
        }
        // End

        // Insert salary year and month data along with department to hrm_salary_sheet_generate table

        $fdate   = $year.'-'.$month_number.'-'.'1';
        $lastday = date('t',strtotime($fdate));
        $edate   = $year.'-'.$month_number.'-'.$lastday;
        $startd  = $fdate;
        $ab      = $this->request->getVar('myDate');
           
        $postData = [
            'department_id' =>  $department_id,
            'date'          =>  date('Y-m-d'),
            'gdate'         =>  $ab,
            'start_date'    =>  $fdate, 
            'end_date'      =>  $edate, 
            'generate_by'   =>  session('id'), 
        ];

        $this->bdtaskt1c1_08_Payroll->bdtask_007_salary_generate($postData);
        $generate_id = $this->db->insertId();

        if($generate_id){
            $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['salary', 'sheet','generate']), get_phrases(['created']), $generate_id, 'hrm_salary_sheet_generate', 1);
        }
 
        // End 

        // Now start employee wise salary generate... keepting relation with $generate_id

        $test = array();
        $i = 0;

        foreach ($employees as $key => $value) {

            $check_for_overtime = false;
            $hour_rate = 0;
            $minute_rate = 0;

            $overtime_amount = 0;
            $allowance_amount = 0;
            $salary = 0;
            $netAmount = 0;
            
            // Get targeted total work hours for the month 
            $working_dates_monthly = $this->bdtaskt1c1_08_Payroll->bdtaskt1m9_10_salaryGen_totalWorkingDates($value->employee_id,$month_number,$year);
            $target_working_hours_monthly = $this->bdtaskt1c1_08_Payroll->bdtaskt1m9_08_totalWorkingHours($value->employee_id, $working_dates_monthly);

            $total_target_working_hours_monthly_arr = explode(':', $target_working_hours_monthly);
            $num_of_working_hours_monthly_in_minutes = ($total_target_working_hours_monthly_arr[0] * 60) + $total_target_working_hours_monthly_arr[1];

            // Get total hours worked for the month.... if it's lesser than targeted hours, then divide gross with targeted hours get hourly rate , then multiply that rate with the hours worked
            $total_hours_worked_monthly = $this->bdtaskt1c1_08_Payroll->bdtaskt1m9_11_salaryGen_totalWorkedHours($value->employee_id,$month_number,$year);

            $total_hours_worked_monthly_arr = explode(':', $total_hours_worked_monthly);
            $total_hours_worked_monthly_in_minutes = ($total_hours_worked_monthly_arr[0] * 60) + $total_hours_worked_monthly_arr[1];

            if($num_of_working_hours_monthly_in_minutes < $total_hours_worked_monthly_in_minutes){
                $check_for_overtime = true;
                $salary = (int)$value->gross_salary;

            }else{

                // Get gross with hour rate
                $hour_rate_cal = (int)$value->gross_salary / (int)$total_target_working_hours_monthly_arr[0];
                $hour_rate = $hour_rate_cal;
                $minute_rate_cal = $hour_rate / 60;
                $minute_rate = $minute_rate_cal;

                $salary = ((int)$total_hours_worked_monthly_arr[0] * $hour_rate) + ((int)$total_hours_worked_monthly_arr[1] * $minute_rate);
            }

            // Check if targeted hours less then  total hours worked then, if overtime available, then consider that for given formula for overtime.. and calculate overtime amount
            if($check_for_overtime){
                //Get overtime and calculate as per employee type slot and formula
                $overtime_amount = $this->bdtask_0009_get_overtime_amount($value);
            }

            // Check, if the employee has any allowance, then afetch that
            $allowances =$this->db->table('hrm_allowance_setup')->select('*')->where('employee_type',$value->employee_type)->get()->getResult();
            if($allowances){

                foreach ($allowances as $allowance) {
                    $allowance_amount = $allowance_amount + (int)$allowance->amount;
                }
            }

            $netAmount = (int)$salary + (int)$overtime_amount + (int)$allowance_amount;

            // End of all calculations

            // Total working day calculations
            $count_query = "SELECT COUNT(DISTINCT `atten_date`) as total_work_days FROM hrm_attendance_history WHERE uid = '$value->employee_id' AND atten_date >= '$startd' AND atten_date <= '$edate';";
            $workingper   = $this->db->query($count_query)->getResult();
            // End

            $paymentData = array(
                'generate_id'           => @$generate_id,
                'employee_id'           => $value->employee_id,
                'total_salary'          => number_format($netAmount, 2, '.', ''),
                'total_working_minutes' => $total_hours_worked_monthly_in_minutes,
                'working_period'        => $workingper[0]->total_work_days, 
                'salary_month'          => @$ab,
            );

            $emp_salary = $this->db->table('hrm_employee_salary_payment');
            $emp_salary->insert($paymentData);
            $emp_sal_pay_id = $this->db->insertId();

            if($emp_salary){
                $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['employee', 'salary']), get_phrases(['created']), $emp_sal_pay_id, 'hrm_employee_salary_payment', 1);
            }


            // $newArr['employee_id'] = $value->employee_id;
            // $newArr['target_working_hours_monthly'] = $target_working_hours_monthly;
            // $newArr['total_hours_worked_monthly'] = $total_hours_worked_monthly;
            // $newArr['working_dates_monthly'] = count($working_dates_monthly);
            // $newArr['gross_salary'] = $value->gross_salary;
            // $newArr['hour_rate'] = $hour_rate;
            // $newArr['minute_rate'] = $minute_rate;
            // $newArr['salary'] = $salary;
            // $newArr['overtime_amount'] = $overtime_amount;
            // $newArr['allowance_amount'] = $allowance_amount;

            // $newArr['paymentData'] = $paymentData;

            // $test[$i] = $newArr;
            // $i++;

        }

        // echo "<pre>";
        // print_r($test);
        // exit;

        $this->session->setFlashdata('message', get_phrases(['successfully','generated']));
        return redirect()->to(site_url('/human_resources/payroll/salary_sheet'));

        // End


    }


    public function bdtask_0008_salar_sheet()
    {
        $data['title']        = get_phrases(['salary','sheet']);
        $data['moduleTitle']  = get_phrases(['human','resource']);
        $data['module']       = "Human_resource";
        $data['isDTables']    = true;
        $data['page']         = "payroll/generate_list"; 

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data); 
    }

    public function bdtask_getSalarygenerate_list()
     {
        $postData = $this->request->getVar();
        $data     = $this->bdtaskt1c1_08_Payroll->get_salarygenerateList($postData);
        echo json_encode($data);
    }

    // Get overitme amount after finding is there any overtime generated for the employee.. then apply that for the given formula
    public function bdtask_0009_get_overtime_amount($emp_data)
    {

        $amount = 0;
        $basic_salary = 0;

        $overtime =$this->db->table('hrm_over_times')->select('*')->where('emp_id',$emp_data->employee_id)->get()->getRow();
        if($overtime){

            $overtime_arr = explode(':', $overtime->time);
            $overtime_hours = (int)$overtime_arr[0];
            $overtime_minutes = (int)$overtime_arr[1];

            //Get basic salary, by getting the basic percentage form basic salary setup
            $basic_salary_setup =$this->db->table('hrm_basic_salary_setup')->select('*')->where('employee_type',$emp_data->employee_type)->get()->getRow();
            $basic_salary = ($basic_salary_setup->percent / 100) * $emp_data->gross_salary;

            $employee_type =$this->db->table('hrm_employee_types')->select('*')->where('id',$emp_data->employee_type)->get()->getRow();

            if($employee_type->type == "Security"){

                $amount = $basic_salary/26/8*2*$overtime_hours;
            }
            elseif ($employee_type->type == "Permanent worker") {

                $amount = $basic_salary/26/8*2*$overtime_hours;
            }
            elseif ($employee_type->type == "Casual Worker") {

               $amount = $emp_data->gross_salary/26/8*$overtime_hours;
            }
            elseif ($employee_type->type == "Officer") {

                $hour_rate = 0;

                if($overtime_hours > 0 && $overtime_hours < 3){
                    $hour_rate = 150;
                }
                elseif ($overtime_hours >= 3 && $overtime_hours < 5) {
                    $hour_rate = 200;
                }
                elseif ($overtime_hours >= 5 && $overtime_hours < 9) {
                    $hour_rate = 350;
                }
                elseif ($overtime_hours >= 9) {
                    $hour_rate = 400;
                }

                $amount = $hour_rate*$overtime_hours;
            }

        }

        return $amount;
        
    }












    ////////////////////////////////////////////////////Overtime Caoculation section starts ///////////////////////////////////////

    /*-----------------------------*
    | OverTimes
    *------------------------------*/
    public function bdtaskt1c7_20_OverTimes() {

        $data['title']      = get_phrases(['employee','over','time','list']);
        $data['moduleTitle']= get_phrases(['human','resource']);
        $data['isDTables']  = true;
        $data['module']     = "Human_resource";
        $data['page']       = "payroll/overtime/over_time";

        $data['hasCreateAccess']  = $this->permission->method('over_time', 'create')->access();
        $data['hasPrintAccess']   = $this->permission->method('over_time', 'print')->access();
        $data['hasExportAccess']  = $this->permission->method('over_time', 'export')->access();

        //Get all employees except the logged in employee
        $data['employees'] = $this->bdtaskt1c1_08_Payroll->bdtaskt1m8_14_employeesForOvertimes();

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);
    }


    /*--------------------------
    | overTimesList
    *--------------------------*/
    public function bdtaskt1c7_22_overTimesList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c1_08_Payroll->bdtaskt1m9_16_overTimesList($postData);
        echo json_encode($data);
    }

    /*-----------------------------*
    | getOverTimeByEmpId
    *------------------------------*/
    public function bdtaskt1c7_26_getOverTimeByEmpId($id){

        $MesTitle = get_phrases(['over','time']);

        // $data= $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_salary_setup', array('emp_id'=>$id));

            //Overtime hours calculation  // overtime_hours //////////

            //Getting total work hours elminating weekends from weekends table and other leave for an employee
            $working_dates_monthly = $this->bdtaskt1c1_08_Payroll->bdtaskt1m9_07_totalWorkingDates($id);
            $target_working_hours_monthly = $this->bdtaskt1c1_08_Payroll->bdtaskt1m9_08_totalWorkingHours($id, $working_dates_monthly);

            $total_target_working_hours_monthly_arr = explode(':', $target_working_hours_monthly);
            $num_of_working_hours_monthly_in_minutes = ($total_target_working_hours_monthly_arr[0] * 60) + $total_target_working_hours_monthly_arr[1];

            //Get total worked hours
            $total_hours_worked_monthly = $this->bdtaskt1c1_08_Payroll->bdtaskt1m9_09_totalWorkedHours($id);

            $total_hours_worked_monthly_arr = explode(':', $total_hours_worked_monthly);
            $total_hours_worked_monthly_in_minutes = ($total_hours_worked_monthly_arr[0] * 60) + $total_hours_worked_monthly_arr[1];

            // echo json_encode($total_hours_worked_monthly_in_minutes);exit;


            $overtime_minutes = 0; // it will be set to 0 ************
            $overtime_hours   = '0';
            //$num_of_working_hours_monthly_in_minutes = 240;  /// Comment this line.. it's just only for test ************
            if($num_of_working_hours_monthly_in_minutes < $total_hours_worked_monthly_in_minutes){
                $overtime_minutes = $total_hours_worked_monthly_in_minutes - $num_of_working_hours_monthly_in_minutes;
            }
            if($overtime_minutes > 0){
                $overtime_hours = number_format($overtime_minutes / 60,2);
            }

            $hours = (int) ($overtime_minutes / 60);
            $minutes = $overtime_minutes % 60;

            $final_overtime_hours = $hours.':'.$minutes;

            $response = array(
                'success'    =>true,
                'over_time'  => $final_overtime_hours
            );

            echo json_encode($response);exit;

    }

        /*--------------------------
    | saveOverTimes info
    *--------------------------*/

    public function bdtaskt1c7_21_saveOverTimes(){

        $MesTitle = get_phrases(['over','time']);

        $id = $this->request->getVar('id');
        $action = $this->request->getVar('action');

        // echo json_encode($this->request->getVar());exit;

        if($action!='update' && $this->request->getVar('emp_id') == null){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['must', 'select','employee']),
                'title'    => $MesTitle,
            );

            echo json_encode($response);exit;
        }

        $data = array(
            'emp_id' => $this->request->getVar('emp_id'),
            'time' => $this->request->getVar('time'),
            'status' => $this->request->getVar('status')
        );
       
        if($action=='update'){

            unset($data['emp_id']);
            $data['UpdateBy']   = session('id');
            $data['UpdateDate'] = date("Y-m-d h:i:s");

            $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_02_Update('hrm_over_times', $data, array('over_time_id'=>$id));
            if($result){
                // Store log data
                $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['over','times']), get_phrases(['updated']), $id, 'hrm_over_times', 2);
                
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

            // Get last month already generated overtime for employee if exists...
            $existing_employee_overtime= $this->bdtaskt1c1_08_Payroll->bdtaskt1m9_15_employeesOvertimeAlreadyGenerated($data['emp_id']);
            // echo json_encode($existing_employee_overtime);exit;
            if($existing_employee_overtime){

                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['over', 'time','already', 'generated','for','the','selected','employee']),
                    'title'    => $MesTitle,
                );

                echo json_encode($response);exit;
            }

            $first_date = $date_y.'-'.$date_m.'-01';
            $data['overtime_for'] = date("Y-m-t", strtotime($first_date));

            $data['CreateBy']   = session('id');
            $data['CreateDate'] = date("Y-m-d");

            // echo json_encode($data);exit;


            $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_01_Insert('hrm_over_times', $data);
            if($result){
                // Store log data
                $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['over','times']), get_phrases(['created']), $result, 'hrm_over_times', 1);
                $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['generated', 'successfully']),
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
    | overTimesById
    *--------------------------*/
    public function bdtaskt1c7_24_overTimesById($id)
    {

        $data= $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_over_times', array('over_time_id'=>$id));
        $employee= $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_employees', array('employee_id'=>$data->emp_id));

        if($employee->first_name){
            $data->emp_name = $employee->first_name.' '.$employee->last_name;
        }

        echo json_encode($data);
    }

    /*--------------------------
    | deleteOverTimeById
    *--------------------------*/
    public function bdtaskt1c7_25_deleteOverTimeById($id)
    {
        $MesTitle = get_phrases(['over', 'time']);

        //Check if the generated overtime already applied/reflected into salary generate for employee, then not allow to update...
        // $over_time= $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_over_times', array('over_time_id'=>$id));
        // $over_time_applied_to_salary = $this->bdtaskt1c1_08_Payroll->bdtaskt1m9_17_overTimeAppliedToSalary($over_time->emp_id,$over_time->overtime_for);
        // if($over_time_applied_to_salary != null){

        //     $response = array(
        //         'success'  =>"exist",
        //         'message'  => get_phrases(['salary', 'already', 'generated', 'so','you','can','not', 'delete','the','record']),
        //         'title'    => $MesTitle,
        //     );
        //     echo json_encode($response);exit;
        // }
        //End

        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_06_Deleted('hrm_over_times', array('over_time_id'=>$id));

        if(!empty($data)){

            // Store log data
            $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['over','time']), get_phrases(['deleted']), $id, 'hrm_over_times', 3);

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

    ////////////////////////////////////////////////////Overtime Caoculation section ends ///////////////////////////////////////


    public function get_month_number($month_name)
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



}