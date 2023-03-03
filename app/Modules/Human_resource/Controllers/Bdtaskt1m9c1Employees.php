<?php namespace App\Modules\Human_resource\Controllers;
use CodeIgniter\Controller;
use App\Modules\Human_resource\Models\Bdtaskt1m1Employee;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1m9c1Employees extends BaseController
{
    private $bdtaskt1c1_01_EmpModel;
    private $bdtaskt1c1_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->db = db_connect();

        $this->permission = new Permission();
        $this->bdtaskt1c1_01_EmpModel = new Bdtaskt1m1Employee();
        $this->bdtaskt1c1_02_CmModel = new Bdtaskt1m1CommonModel();
    }


    /* Department CRUD starts*/

    /*--------------------------
    | departments List
    *--------------------------*/
    public function bdtask1c1_01_departmentList()
    {
        $data['title']      = get_phrases(['departments', 'list']);
        $data['moduleTitle']= get_phrases(['human', 'resource']);
        $data['isDTables']  = true;
        $data['module']     = "human_resource";
        $data['page']       = "employee/departments";

        $data['employees'] = $this->bdtaskt1c1_01_EmpModel->get_all_employee_list();

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | Get departments info
    *--------------------------*/
    public function bdtask1c1_02_getDepartmentList()
    { 

        // echo "<pre>";
        // print_r(session('branchId'));
        // exit;


        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c1_01_EmpModel->bdtaskt1m1_01_getAllDepartments($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | Add departments info
    *--------------------------*/
    public function bdtask1c1_03_addDepartment()
    { 
        $id = $this->request->getVar('id');
        $action = $this->request->getVar('action');
        $data = array(
            'name'             => $this->request->getVar('name'),
            'department_head'  => $this->request->getVar('department_head'),
            'details'          => $this->request->getVar('details'),
        );

        // echo "<pre>";
        // print_r($data);
        // exit;

        $MesTitle = get_phrases(['department', 'record']);
       
        if($action=='update'){

            $respo_department = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_departments', array('id'=>$id));

            // Check , if the same title ... then ignore the title and show message
            if($respo_department->name != $this->request->getVar('name')){

                $respo_department_name = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_departments', array('name'=>$this->request->getVar('name')));
                
                if($respo_department_name){

                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['similar', 'name', 'already', 'used','for','other','department']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);exit;
                }
            }

            $data['UpdateBy']   = session('id');
            $data['UpdateDate'] = date("Y-m-d h:i:s");

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_02_Update('hrm_departments', $data, array('id'=>$id));
            if($result){
                // Store log data
                $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['department','name']), get_phrases(['updated']), $id, 'hrm_departments', 2);
                
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

            $respo_departments_name = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_departments', array('name'=>$this->request->getVar('name')));
                
            if($respo_departments_name){

                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['this', 'name', 'already', 'used']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;
            }


            $data['CreateBy']   = session('id');
            $data['CreateDate'] = date("Y-m-d h:i:s");

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('hrm_departments', $data);
            if($result){
                // Store log data
                $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['department','name']), get_phrases(['created']), $result, 'hrm_departments', 1);
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
    | getdepartmentById
    *--------------------------*/
    public function bdtask1c1_04_getdepartmentById($dept_id)
    {

        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_departments', array('id'=>$dept_id));
        echo json_encode($data);
    }


    /*--------------------------
    | deleteDepartmentById
    *--------------------------*/
    public function bdtask1c1_05_deleteDepartmentById($dept_id)
    {

        $MesTitle = get_phrases(['department', 'record']);

        // Check , if the Department already used for any employee

        $employee_info = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_employees', array('department'=>$dept_id));

        if($employee_info != null && (int)$employee_info->department > 0){

            $response = array(
                'success'  => false,
                'message'  => get_notify('employee_already_assigned_to_this_department').' !',
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }

        // End

        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_06_Deleted('hrm_departments', array('id'=>$dept_id));

        if(!empty($data)){

            // Store log data
            $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['department','name']), get_phrases(['deleted']), $dept_id, 'hrm_departments', 3);

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

    /* Department CRUD ends*/

    /* Employee Type CRUD starts*/

    /*--------------------------
    | departments List
    *--------------------------*/
    public function bdtask1c1_06_employeeTypesList()
    {
        $data['title']      = get_phrases(['employee', 'types']);
        $data['moduleTitle']= get_phrases(['human', 'resource']);
        $data['isDTables']  = true;
        $data['module']     = "human_resource";
        $data['page']       = "employee/employee_types";
        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | Get departments info
    *--------------------------*/
    public function bdtask1c1_07_getEmployeeTypesList()
    {

        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c1_01_EmpModel->bdtaskt1m1_02_getEmployeeTypesList($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | Add appointment info
    *--------------------------*/
    public function bdtask1c1_08_addEmpployeeType()
    { 
        $id = $this->request->getVar('id');
        $action = $this->request->getVar('action');
        $data = array(
            'type'     => $this->request->getVar('type'),
            'details'  => $this->request->getVar('details'),
        );

        $MesTitle = get_phrases(['employee', 'type']);
       
        if($action=='update'){

            $respo_employee_type = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_employee_types', array('id'=>$id));

            // Check , if the same type ... then ignore the type and show message
            if($respo_employee_type->type != $this->request->getVar('type')){

                $respo_emp_type = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_employee_types', array('type'=>$this->request->getVar('type')));
                
                if($respo_emp_type){

                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['similar', 'type', 'already', 'used']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);exit;
                }
            }
            // End

            $data['UpdateBy']   = session('id');
            $data['UpdateDate'] = date("Y-m-d h:i:s");

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_02_Update('hrm_employee_types', $data, array('id'=>$id));
            if($result){
                // Store log data
                $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['employee','type']), get_phrases(['updated']), $id, 'hrm_employee_types', 2);
                
                $response = array(
                    'success'  => true,
                    'message'  => get_phrases(['updated', 'successfully']),
                    'title'    => $MesTitle,
                );
            }else{
                $response = array(
                    'success'  => false,
                    'message'  => get_phrases(['something', 'went', 'wrong']),
                    'title'    => $MesTitle
                );
            }
        }else{

            // Check , if the same type ... then ignore the type and show message

            $respo_emp_type = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_employee_types', array('type'=>$this->request->getVar('type')));
                
            if($respo_emp_type){

                $response = array(
                    'success'  => false,
                    'message'  => get_phrases(['similar', 'type', 'already', 'used']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;
            }
            // End

            $data['CreateBy']   = session('id');
            $data['CreateDate'] = date("Y-m-d h:i:s");

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('hrm_employee_types', $data);
            if($result){
                // Store log data
                $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['employee','type']), get_phrases(['created']), $result, 'hrm_employee_types', 1);
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
    | getdepartmentById
    *--------------------------*/
    public function bdtask1c1_09_getEmpTypeById($type_id)
    {

        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_employee_types', array('id'=>$type_id));
        echo json_encode($data);
    }

    /*--------------------------
    | deleteEmpTypeById
    *--------------------------*/
    public function bdtask1c1_10_deleteEmpTypeById($type_id)
    {

        $MesTitle = get_phrases(['employee', 'type']);

        // Check , if the Employee Type already used for any employee

        $employee_info = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_employees', array('employee_type'=>$type_id));

        if($employee_info != null && (int)$employee_info->employee_type > 0){

            $response = array(
                'success'  => false,
                'message'  => get_notify('employee_already_assigned_to_this_type').' !',
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }

        // End

        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_06_Deleted('hrm_employee_types', array('id'=>$type_id));

        if(!empty($data)){

            // Store log data
            $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['employee','type']), get_phrases(['deleted']), $type_id, 'hrm_employee_types', 3);

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

    /* Employee Type CRUD ends*/

    /* Employee CRUD starts*/


    /*--------------------------
    | employeeList
    *--------------------------*/
    public function bdtask1c1_11_employeeList()
    {
        $data['title']      = get_phrases(['employees']);
        $data['moduleTitle']= get_phrases(['human', 'resource']);
        $data['isDTables']  = true;
        $data['module']     = "human_resource";
        $data['page']       = "employee/employee_list";
        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | getEmployeeList
    *--------------------------*/
    public function bdtask1c1_12_getEmployeeList()
    {

        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c1_01_EmpModel->bdtaskt1m1_03_getEmployeeList($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | employee add form
    *--------------------------*/
    public function bdtask1c1_13_createEmployee()
    {
        $data['title']      = get_phrases(['create','employee']);
        $data['moduleTitle']= get_phrases(['human', 'resource']);
        $data['module']     = "human_resource";
        $data['isDateTimes']= true;
        $data['page']       = "employee/create_employee";

        $data['departments_list'] = $this->bdtaskt1c1_01_EmpModel->departments_list();
        $data['employee_types']   = $this->bdtaskt1c1_01_EmpModel->employee_types();
        $data['designation_list'] = $this->bdtaskt1c1_01_EmpModel->designations_list();
        $data['branch_list']      = $this->bdtaskt1c1_01_EmpModel->branch_list();
        $data['employee_list']    = $this->bdtaskt1c1_01_EmpModel->get_all_employee_list();
        $data['country_list']     = $this->bdtaskt1c1_01_EmpModel->country_list();
        $data['attachment_type_list']    = $this->bdtaskt1c1_01_EmpModel->attachment_types();

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | Add employee info
    *--------------------------*/
    public function bdtask1c1_14_addEmpployee()
    {

        helper(['form','url']);
        $this->validation =  \Config\Services::validation();

        $MesTitle = get_phrases(['employee', 'record']);

        // echo json_encode($this->request->getVar());
        // echo json_encode($this->request->getVar());
        // exit;

        // All attachment files validation of Multiple attachment section /////////////////////////////
        $attachments_arr = array();
        $invalid_attachment_file = false;
        $check_attachment_field_empty = false;

        if($this->request->getVar('attachment_type') != null){
            for ($i=0; $i < count($this->request->getVar('attachment_type')); $i++) { 

                // check if any attachment field empty..
                if(empty($_FILES['file']['name'][$i]) || empty($this->request->getVar('attachment_type')[$i])){
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['please','select','all','attachment','fields']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);exit;
                }

                // Upload image file and File type check and show message for invalid image file..
                if(isset($_FILES['file']['name'][$i]) && !empty($_FILES['file']['name'][$i]) ){

                    $path = $_FILES['file']['name'][$i];
                    $img_ext = pathinfo($path, PATHINFO_EXTENSION);
                    $is_valid_attachment_file = $this->attachment_file_type($img_ext);

                    if(!$is_valid_attachment_file){
                        $invalid_attachment_file = true;
                    }

                    $file_arr['error']    = $_FILES['file']['error'][$i];
                    $file_arr['name']     = $_FILES['file']['name'][$i];
                    $file_arr['size']     = $_FILES['file']['size'][$i];
                    $file_arr['tmp_name'] = $_FILES['file']['tmp_name'][$i];
                    $file_arr['type']     = $_FILES['file']['type'][$i];
                    $attachments_arr[$i]  = $file_arr;
                }
            }
            if($invalid_attachment_file){
                $response = array(
                    'test'  =>$invalid_attachment_file,
                    'success'  =>false,
                    'message'  => get_phrases(['invalid','attachment','file']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;
            }
        }

        // echo print_r($attachments_arr);
        // exit;
        //End of All attachment files validation of Multiple attachment section /////////////////////////////

        $image_path = '';
        $nid_path = '';
        $signature_path = '';

        $in_duty_roster = 0;
        if($this->request->getVar('in_duty_roster') != null){
            $in_duty_roster = 1;
        }

        // Upload image file and File type check and show message for invalid image file..

        if(isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])){

            $path = $_FILES['image']['name'];
            $img_ext = pathinfo($path, PATHINFO_EXTENSION);
            $is_valid_file = $this->image_file_type($img_ext);

            if(!$is_valid_file){

                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['invalid','image','file']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;
            }

            $image_path = $this->base_01_fileUpload->doUpload('./assets/dist/img/employee/', $this->request->getFile('image'));

        }

        // Upload nid file and File type check and show message for invalid nid file..

        if(isset($_FILES['nid_file']['name']) && !empty($_FILES['nid_file']['name'])){

            $path = $_FILES['nid_file']['name'];
            $nid_ext = pathinfo($path, PATHINFO_EXTENSION);
            $is_valid_id_file = $this->nid_file_type($nid_ext);

            if(!$is_valid_id_file){

                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['invalid','nid','file']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;
            }

            $nid_path = $this->base_01_fileUpload->doUpload('./assets/dist/img/employee/', $this->request->getFile('nid_file'));

        }

        // Upload signature file and File type check and show message for invalid nid file..

        if(isset($_FILES['signature_file']['name']) && !empty($_FILES['signature_file']['name'])){

            $path = $_FILES['signature_file']['name'];
            $nid_ext = pathinfo($path, PATHINFO_EXTENSION);
            $is_valid_id_file = $this->signature_file_type($nid_ext);

            if(!$is_valid_id_file){

                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['invalid','signature','file']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;
            }

            $signature_path = $this->base_01_fileUpload->doUpload('./assets/dist/img/employee_signature/', $this->request->getFile('signature_file'));

        }

        $employee_h = $this->bdtaskt1c1_01_EmpModel->last_employee();
        $employee_id=1;

        $max_id = @$employee_h->employee_id?@$employee_h->employee_id:0;
        if(!empty($max_id)){
        $employee_id = $max_id+1;   
        }

         $data['employee'] = (object)$empData = array(
            'employee_id'              => $employee_id,
            'first_name'               => $this->request->getVar('firstname', FILTER_SANITIZE_STRING),
            'last_name'                => $this->request->getVar('lastname', FILTER_SANITIZE_STRING),
            'employee_type'            => $this->request->getVar('employee_type', FILTER_SANITIZE_STRING),
            'department'               => $this->request->getVar('department', FILTER_SANITIZE_STRING),
            'email'                    => $this->request->getVar('email', FILTER_SANITIZE_STRING),
            'salary_type'              => $this->request->getVar('salary_type', FILTER_SANITIZE_STRING),
            'gross_salary'             => $this->request->getVar('gross_salary', FILTER_SANITIZE_STRING),
            //'country'                  => $this->request->getVar('country', FILTER_SANITIZE_STRING),
            'city'                     => $this->request->getVar('city', FILTER_SANITIZE_STRING),
            'zip_code'                 => $this->request->getVar('zip_code', FILTER_SANITIZE_STRING),
            'joining_date'             => $this->request->getVar('joining_date', FILTER_SANITIZE_STRING),
            'gender'                   => $this->request->getVar('gender', FILTER_SANITIZE_STRING),
            'mobile_no1'               => $this->request->getVar('mobile_no1', FILTER_SANITIZE_STRING),
            'mobile_no2'               => $this->request->getVar('mobile_no2', FILTER_SANITIZE_STRING),
            'present_address'          => $this->request->getVar('present_address', FILTER_SANITIZE_STRING),
            'permanent_address'        => $this->request->getVar('permanent_address', FILTER_SANITIZE_STRING),
            'nid_no'                   => $this->request->getVar('nid_no', FILTER_SANITIZE_STRING),
            'nid_file'                 =>  (($nid_path !='/')?str_replace("./","/",$nid_path):''),
            'image'                    =>  (($image_path !='/')?str_replace("./","/",$image_path):''),
            'signature_file'           =>  (($signature_path !='/')?str_replace("./","/",$signature_path):''),
            'blood_group'              => $this->request->getVar('blood_group', FILTER_SANITIZE_STRING),
            'in_duty_roster'           => $in_duty_roster,
            'status'                   => $this->request->getVar('status'),

            'father_name'              => $this->request->getVar('father_name', FILTER_SANITIZE_STRING),
            'mother_name'              => $this->request->getVar('mother_name', FILTER_SANITIZE_STRING),
            'bangla_name'              => $this->request->getVar('banglaname', FILTER_SANITIZE_STRING),
            'designation'              => $this->request->getVar('designation', FILTER_SANITIZE_STRING),
            'educational_qualification' => $this->request->getVar('educational_qualification', FILTER_SANITIZE_STRING),
            'work_place'               => $this->request->getVar('work_place', FILTER_SANITIZE_STRING),
            'superior'                 => $this->request->getVar('superior', FILTER_SANITIZE_STRING),
            'birth_date'               => $this->request->getVar('birth_date', FILTER_SANITIZE_STRING),
            'nationality'              => $this->request->getVar('nationality', FILTER_SANITIZE_STRING),
            'branch'                   => $this->request->getVar('branch', FILTER_SANITIZE_STRING),
        );

        if ($this->request->getMethod() == 'post') {
            
            $rules = [
                'firstname'           => ['label' => get_phrases(['first','name']),'rules'   => 'required|min_length[2]|max_length[20]'],
                'lastname'            => ['label' => get_phrases(['last','name']),'rules'   => 'required|min_length[2]|max_length[20]'],
                'employee_type'       => ['label' => get_phrases(['employee','type']),'rules' => 'required'],
                'department'          => ['label' => get_phrases(['department']),'rules'   => 'required'],

                'salary_type'         => ['label' => get_phrases(['salary','type']),'rules'   => 'required'],
                'gross_salary'        => ['label' => get_phrases(['gross','salary']),'rules'   => 'required'],

                'nid_no' => [
                    'label'  => get_phrases(['nid','no']),
                    'rules'  => 'numeric|is_unique[hrm_employees.nid_no]',
                    'errors' => [
                        'numeric' => get_phrases(['please', 'provide', 'valid']).' '.'{field}',
                        'is_unique' => '{field}'.' '.get_phrases(['already', 'exist'])
                    ]
                ],

                'joining_date'        => ['label' => get_phrases(['joining','date']),'rules'       => 'required'], 
                'gender'              => ['label' => get_phrases(['gender']),'rules'       => 'required'],
                'mobile_no1'          => ['label' => get_phrases(['mobile','no','1']),'rules'       => 'required'],
                'mobile_no2'          => ['label' => get_phrases(['emergency','no']),'rules'      => 'required'],
                'present_address'     => ['label' => get_phrases(['present','address']),'rules'      => 'required'],
                'status'              => ['label' => get_phrases(['status']),'rules'      => 'required'],

                'father_name'         => ['label' => get_phrases(['father','name']),'rules'       => 'required'], 
                'mother_name'         => ['label' => get_phrases(['mother','name']),'rules'       => 'required'],
                'banglaname'          => ['label' => get_phrases(['bangla','name']),'rules'       => 'required'],
                'designation'         => ['label' => get_phrases(['designation']),'rules'      => 'required'],
                'educational_qualification'  => ['label' => get_phrases(['educational','qualification']),'rules'      => 'required'],
                'work_place'          => ['label' => get_phrases(['work','place']),'rules'      => 'required'],
                //'superior'          => ['label' => get_phrases(['superior']),'rules'      => 'required'],
                'birth_date'          => ['label' => get_phrases(['birth','date']),'rules'      => 'required'],
                'nationality'          => ['label' => get_phrases(['nationality']),'rules'      => 'required'],
                'branch'          => ['label' => get_phrases(['branch']),'rules'      => 'required'],
            ];

            if($this->request->getVar('email')){

                 $rules['email'] = [
                        'label'  => get_phrases(['email']),
                        'rules'  => 'valid_email|is_unique[hrm_employees.email]',
                        'errors' => [
                            'valid_email' => get_phrases(['please', 'provide', 'valid']).' '.'{field}',
                            'is_unique' => '{field}'.' '.get_phrases(['already', 'exist'])
                        ]
                ];

            }

            // Validation check

            if (! $this->validate($rules)) {

                $response = array(
                    'success'  =>false,
                    'message'  => $this->validator->listErrors(),
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;

            }else{

                $empData['CreateBy']   = session('id');
                $empData['CreateDate'] = date("Y-m-d h:i:s");

                // echo "<pre>";
                // print_r($empData);
                // exit;

                // Save data after all validation
                $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('hrm_employees', $empData);
                if($result){

                    // Store log data
                    $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['employee','created']), get_phrases(['created']), $result, 'hrm_employees', 1);

                    // Upload all attachment files after validation check to of this function/////////////////////////////

                   if(count($attachments_arr) > 0){

                        $total = count($attachments_arr);

                        for ($i=0; $i < count($this->request->getVar('attachment_type')); $i++) {

                            $attachment = $attachments_arr[$i];
                            $target_dir = "./assets/dist/img/employee_attachments/";

                            $temp = explode(".", $attachment["name"]);
                            $newfilename = round(microtime(true)).rand(10,100).$total . '.' . end($temp);
                            $total++;

                            $target_file = $target_dir . $newfilename;
                            $attachment_path = "";

                            if (move_uploaded_file($attachment["tmp_name"], $target_file)) {
                                $attachment_path = "./assets/dist/img/employee_attachments/".$newfilename;

                            }
                            // echo $attachment_path;
                            $attachmentData = array(
                                'employee_id'     => $employee_id,
                                'attachment_type' => $this->request->getVar('attachment_type')[$i],
                                'attachment_path' => $attachment_path,
                                'description'     => $this->request->getVar('description')[$i],
                                'date'            => $this->request->getVar('date')[$i]?$this->request->getVar('date')[$i]:null,

                            );

                            $employee_attachments_result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('hrm_employee_attachments', $attachmentData);

                        }
                    }

                    //End of all attachment files upload/////////////////////////////

                    $response = array(
                        'success'  =>true,
                        'message'  => get_phrases(['added', 'successfully']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);exit;
                }
                else{

                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['something', 'went', 'wrong']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);exit;
                }
            }

        }
    }

    /*--------------------------
    | employee update form
    *--------------------------*/
    public function bdtask1c1_15_updateEmployeeForm($id=null)
    {
        $data['title']      = get_phrases(['update','employee']);
        $data['moduleTitle']= get_phrases(['human', 'resource']);
        $data['module']     = "human_resource";
        $data['page']       = "employee/update_employee";

        $data['isDateTimes']= true;

        $data['departments_list'] = $this->bdtaskt1c1_01_EmpModel->departments_list();
        $data['employee_types']   = $this->bdtaskt1c1_01_EmpModel->employee_types();
        $data['designation_list'] = $this->bdtaskt1c1_01_EmpModel->designations_list();
        $data['branch_list']      = $this->bdtaskt1c1_01_EmpModel->branch_list();
        $data['employee_list']    = $this->bdtaskt1c1_01_EmpModel->get_all_employee_list();
        $data['country_list']     = $this->bdtaskt1c1_01_EmpModel->country_list();
        $data['attachment_type_list']    = $this->bdtaskt1c1_01_EmpModel->attachment_types();
        //Employee all attachments

        // Get employee individual info from hrm_employees table by the id
        $data['employee']   = $employee_info = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_employees', array('id'=>$id));
        $data['employee_attachments']    = $this->bdtaskt1c1_01_EmpModel->employee_attachments($employee_info->employee_id);

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | Update employee info
    *--------------------------*/
    public function bdtask1c1_16_updateEmpployee($id=null)
    {

        helper(['form','url']);
        $this->validation =  \Config\Services::validation();

        $MesTitle = get_phrases(['employee', 'record']);

        // echo json_encode($this->request->getVar());
        // exit;

        // All attachment files validation of Multiple attachment section /////////////////////////////
        $attachments_arr = array();
        $invalid_attachment_file = false;
        $check_attachment_field_empty = false;

        if($this->request->getVar('attachment_type') != null){

            for ($i=0; $i < count($this->request->getVar('attachment_type')); $i++) { 

                // check if any attachment field empty..
                if(empty($_FILES['file']['name'][$i]) || empty($this->request->getVar('attachment_type')[$i])){
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['please','select','all','attachment','fields']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);exit;
                }

                // Upload image file and File type check and show message for invalid image file..
                if(isset($_FILES['file']['name'][$i]) && !empty($_FILES['file']['name'][$i]) ){

                    $path = $_FILES['file']['name'][$i];
                    $img_ext = pathinfo($path, PATHINFO_EXTENSION);
                    $is_valid_attachment_file = $this->attachment_file_type($img_ext);

                    if(!$is_valid_attachment_file){
                        $invalid_attachment_file = true;
                    }

                    $file_arr['error']    = $_FILES['file']['error'][$i];
                    $file_arr['name']     = $_FILES['file']['name'][$i];
                    $file_arr['size']     = $_FILES['file']['size'][$i];
                    $file_arr['tmp_name'] = $_FILES['file']['tmp_name'][$i];
                    $file_arr['type']     = $_FILES['file']['type'][$i];
                    $attachments_arr[$i]  = $file_arr;
                }
            }
            if($invalid_attachment_file){
                $response = array(
                    'test'  =>$invalid_attachment_file,
                    'success'  =>false,
                    'message'  => get_phrases(['invalid','attachment','file']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;
            }
        }

        // echo print_r($attachments_arr);
        // exit;
        //End of All attachment files validation of Multiple attachment section /////////////////////////////

        $employee_info = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_employees', array('id'=>$id));

        $image_path = '';
        $nid_path = '';
        $signature_path = '';

        $old_email  = $this->request->getVar('old_email', FILTER_SANITIZE_STRING);
        $nid_no     = $employee_info->nid_no;

        // Upload image file and File type check and show message for invalid image file..
        if(isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])){

            $path = $_FILES['image']['name'];
            $img_ext = pathinfo($path, PATHINFO_EXTENSION);
            $is_valid_file = $this->image_file_type($img_ext);

            if(!$is_valid_file){

               // $this->session->setFlashdata('exception', get_phrases(['invalid','image','file']));
               //  return  redirect()->to(base_url('human_resources/employees/update_employee/'.$id));
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['invalid','image','file']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;
            }

            $image_path = $this->base_01_fileUpload->doUpload('./assets/dist/img/employee/', $this->request->getFile('image'));

        }

        // Upload nid file and File type check and show message for invalid nid file..
        if(isset($_FILES['nid_file']['name']) && !empty($_FILES['nid_file']['name'])){

            $path = $_FILES['nid_file']['name'];
            $nid_ext = pathinfo($path, PATHINFO_EXTENSION);
            $is_valid_id_file = $this->nid_file_type($nid_ext);

            if(!$is_valid_id_file){

               // $this->session->setFlashdata('exception', get_phrases(['invalid','nid','file']));
               //  return  redirect()->to(base_url('human_resources/employees/update_employee/'.$id));
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['invalid','nid','file']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;
            }

            $nid_path = $this->base_01_fileUpload->doUpload('./assets/dist/img/employee/', $this->request->getFile('nid_file'));

        }

        // Upload signature file and File type check and show message for invalid nid file..

        if(isset($_FILES['signature_file']['name']) && !empty($_FILES['signature_file']['name'])){

            $path = $_FILES['signature_file']['name'];
            $nid_ext = pathinfo($path, PATHINFO_EXTENSION);
            $is_valid_id_file = $this->signature_file_type($nid_ext);

            if(!$is_valid_id_file){

               // $this->session->setFlashdata('exception', get_phrases(['invalid','signature','file']));
               // return  redirect()->to(base_url('human_resources/employees/employee_list'));
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['invalid','signature','file']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;
            }

            $signature_path = $this->base_01_fileUpload->doUpload('./assets/dist/img/employee_signature/', $this->request->getFile('signature_file'));

        }

        // New Image and nid file path
        $new_image_path = '';
        $new_nid_path = '';
        $new_signature_path = '';

        if($image_path != ''){
            $new_image_path = str_replace("./","/",$image_path);
        }else{
            $new_image_path =$this->request->getVar('old_image');
        }
        if($nid_path != ''){
            $new_nid_path = str_replace("./","/",$nid_path);
        }else{
            $new_nid_path = $this->request->getVar('old_nid_file');
        }
        if($signature_path != ''){
            $new_signature_path = str_replace("./","/",$signature_path);
        }else{
            $new_signature_path = $this->request->getVar('old_signature_file');
        }
        //End

         $data['employee'] = (object)$empData = array(
            'first_name'        => $this->request->getVar('firstname', FILTER_SANITIZE_STRING),
            'last_name'         => $this->request->getVar('lastname', FILTER_SANITIZE_STRING),
            'employee_type'     => $this->request->getVar('employee_type', FILTER_SANITIZE_STRING),
            'department'        => $this->request->getVar('department', FILTER_SANITIZE_STRING),
            'salary_type'       => $this->request->getVar('salary_type', FILTER_SANITIZE_STRING),
            'gross_salary'      => $this->request->getVar('gross_salary', FILTER_SANITIZE_STRING),
            'email'             => $this->request->getVar('email', FILTER_SANITIZE_STRING),
            // 'country'           => $this->request->getVar('country', FILTER_SANITIZE_STRING),
            'city'              => $this->request->getVar('city', FILTER_SANITIZE_STRING),
            'zip_code'          => $this->request->getVar('zip_code', FILTER_SANITIZE_STRING),
            'joining_date'      => $this->request->getVar('joining_date', FILTER_SANITIZE_STRING),
            'gender'            => $this->request->getVar('gender', FILTER_SANITIZE_STRING),
            'mobile_no1'        => $this->request->getVar('mobile_no1', FILTER_SANITIZE_STRING),
            'mobile_no2'        => $this->request->getVar('mobile_no2', FILTER_SANITIZE_STRING),
            'present_address'   => $this->request->getVar('present_address', FILTER_SANITIZE_STRING),
            'permanent_address' => $this->request->getVar('permanent_address', FILTER_SANITIZE_STRING),
            'nid_no'            => $this->request->getVar('nid_no', FILTER_SANITIZE_STRING),
            'nid_file'          => $new_nid_path,
            'image'             => $new_image_path,
            'signature_file'    => $new_signature_path,
            'blood_group'       => $this->request->getVar('blood_group', FILTER_SANITIZE_STRING),
            'status'            => $this->request->getVar('status'),
            'in_duty_roster'    => $this->request->getVar('in_duty_roster'),

            'father_name'              => $this->request->getVar('father_name', FILTER_SANITIZE_STRING),
            'mother_name'              => $this->request->getVar('mother_name', FILTER_SANITIZE_STRING),
            'bangla_name'              => $this->request->getVar('banglaname', FILTER_SANITIZE_STRING),
            'designation'              => $this->request->getVar('designation', FILTER_SANITIZE_STRING),
            'educational_qualification' => $this->request->getVar('educational_qualification', FILTER_SANITIZE_STRING),
            'work_place'               => $this->request->getVar('work_place', FILTER_SANITIZE_STRING),
            'superior'                 => $this->request->getVar('superior', FILTER_SANITIZE_STRING),
            'birth_date'               => $this->request->getVar('birth_date', FILTER_SANITIZE_STRING),
            'nationality'              => $this->request->getVar('nationality', FILTER_SANITIZE_STRING),
            'branch'                   => $this->request->getVar('branch', FILTER_SANITIZE_STRING),
        );

         if ($this->request->getMethod() == 'post') {
            
            $rules = [
                'firstname'           => ['label' => get_phrases(['first','name']),'rules'    => 'required|min_length[2]|max_length[20]'],
                'lastname'            => ['label' => get_phrases(['last','name']),'rules'     => 'required|min_length[2]|max_length[20]'],
                'employee_type'       => ['label' => get_phrases(['employee','type']),'rules' => 'required'],
                'department'          => ['label' => get_phrases(['department']),'rules'      => 'required'],

                'salary_type'         => ['label' => get_phrases(['salary','type']),'rules'   => 'required'],
                'gross_salary'        => ['label' => get_phrases(['gross','salary']),'rules'   => 'required'],
                
                'joining_date'        => ['label' => get_phrases(['joining','date']),'rules'  => 'required'], 
                'gender'              => ['label' => get_phrases(['gender']),'rules'          => 'required'], 
                'mobile_no1'          => ['label' => get_phrases(['mobile','no','1']),'rules' => 'required'],
                'mobile_no2'          => ['label' => get_phrases(['emergency','no']),'rules'  => 'required'],
                'present_address'     => ['label' => get_phrases(['present','address']),'rules'=> 'required'],
                'nid_no'              => ['label' => get_phrases(['nid','no']),'rules'        => 'required'],
                'status'              => ['label' => get_phrases(['status']),'rules'          => 'required'],

                'father_name'         => ['label' => get_phrases(['father','name']),'rules'       => 'required'], 
                'mother_name'         => ['label' => get_phrases(['mother','name']),'rules'       => 'required'],
                'banglaname'          => ['label' => get_phrases(['bangla','name']),'rules'       => 'required'],
                'designation'         => ['label' => get_phrases(['designation']),'rules'      => 'required'],
                'educational_qualification'  => ['label' => get_phrases(['educational','qualification']),'rules'      => 'required'],
                'work_place'          => ['label' => get_phrases(['work','place']),'rules'      => 'required'],
                //'superior'          => ['label' => get_phrases(['superior']),'rules'      => 'required'],
                'birth_date'          => ['label' => get_phrases(['birth','date']),'rules'      => 'required'],
                'nationality'          => ['label' => get_phrases(['nationality']),'rules'      => 'required'],
                'branch'          => ['label' => get_phrases(['branch']),'rules'      => 'required'],
            ];

            // If the mail address is not matching with the old mail, then will compare with existing employee email
            if($this->request->getVar('email') && $this->request->getVar('email', FILTER_SANITIZE_STRING) != $old_email){

                $rules['email'] = [
                        'label'  => get_phrases(['email']),
                        'rules'  => 'valid_email|is_unique[hrm_employees.email]',
                        'errors' => [
                            'valid_email' => get_phrases(['please', 'provide', 'valid']).' '.'{field}',
                            'is_unique' => '{field}'.' '.get_phrases(['already', 'exist'])
                        ]
                ];
            }

             // If the nid_no is not matching with the old nid_no, then will compare with existing employee nid_no
            if($this->request->getVar('nid_no') && (int)$this->request->getVar('nid_no', FILTER_SANITIZE_STRING) != (int)$nid_no){

                $rules['nid_no'] = [
                        'label'  => get_phrases(['nid','no']),
                        'rules'  => 'numeric|is_unique[hrm_employees.nid_no]',
                        'errors' => [
                            'numeric' => get_phrases(['please', 'provide', 'valid']).' '.'{field}',
                            'is_unique' => '{field}'.' '.get_phrases(['already', 'exist'])
                        ]
                ];
            }

            // Validation check

            if (! $this->validate($rules)) {
                 // $this->session->setFlashdata(array('exception'=>$this->validator->listErrors()));
                 //  return  redirect()->to(base_url('human_resources/employees/update_employee/'.$id));
                $response = array(
                    'success'  =>false,
                    'message'  => $this->validator->listErrors(),
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;

            }else{

                $empData['UpdateBy']   = session('id');
                $empData['UpdateDate'] = date("Y-m-d h:i:s");

                // echo "<pre>";
                // print_r($empData);
                // exit;

                // Save data after all validation
                $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_02_Update('hrm_employees', $empData, array('id' => $id));
                if($result){

                    // Store log data
                    $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['employee','updated']), get_phrases(['created']), $id, 'hrm_employees', 2);

                    // Upload all attachment files after validation check to of this function/////////////////////////////

                   if(count($attachments_arr) > 0){

                        $total = count($attachments_arr);
                        
                        //Delete all attachments for the employee from employee_attachments table
                        $employee_attachments_delete = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_06_Deleted('hrm_employee_attachments', array('employee_id'=>$employee_info->employee_id));

                        if($employee_attachments_delete){

                            for ($i=0; $i < count($this->request->getVar('attachment_type')); $i++) {

                                $attachment = $attachments_arr[$i];
                                $target_dir = "./assets/dist/img/employee_attachments/";

                                $temp = explode(".", $attachment["name"]);
                                $newfilename = round(microtime(true)).rand(10,100).$total . '.' . end($temp);
                                $total++;

                                $target_file = $target_dir . $newfilename;
                                $attachment_path = "";

                                if (move_uploaded_file($attachment["tmp_name"], $target_file)) {
                                    $attachment_path = "./assets/dist/img/employee_attachments/".$newfilename;

                                }
                                // echo $attachment_path;
                                $attachmentData = array(
                                    'employee_id'     => $employee_info->employee_id,
                                    'attachment_type' => $this->request->getVar('attachment_type')[$i],
                                    'attachment_path' => $attachment_path,
                                    'description'     => $this->request->getVar('description')[$i],
                                    'date'            => $this->request->getVar('date')[$i]?$this->request->getVar('date')[$i]:null,

                                );

                                $employee_attachments_result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('hrm_employee_attachments', $attachmentData);

                            }
                        }
                    }

                    //End of all attachment files upload/////////////////////////////

                   // $this->session->setFlashdata('message', get_phrases(['save','successfully']));
                   // return  redirect()->to(base_url('human_resources/employees/employee_list'));
                    $response = array(
                        'success'  =>true,
                        'message'  => get_phrases(['updated', 'successfully']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);exit;
                }
                else{

                    // $this->session->setFlashdata('exception', get_phrases(['please','try','again']));
                    // return  redirect()->to(base_url('human_resources/employees/employee_list'));
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['please','try','again']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);exit;
                }
            }

        }
    }

    public function bdtask1c1_17_deleteEmployee($id)
    {

        $MesTitle = get_phrases(['employee', 'type']);

        $employee_info = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_employees', array('id'=>$id));

        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_06_Deleted('hrm_employees', array('id'=>$id));

        if(!empty($data)){

            //Delete all attachments for the employee from employee_attachments table
            $employee_attachments_delete = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_06_Deleted('hrm_employee_attachments', array('employee_id'=>$employee_info->employee_id));

            // Store log data
            $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['employee','deleted']), get_phrases(['deleted']), $id, 'hrm_employees', 3);

            $this->session->setFlashdata('message', get_phrases(['deleted','successfully']));
            return  redirect()->to(base_url('human_resources/employees/employee_list'));
            
        }else{

            $this->session->setFlashdata('exception', get_phrases(['please','try','again']));
            return  redirect()->to(base_url('human_resources/employees/employee_list'));
        }
    }

    /*--------------------------
    | Check empployee already assigned to Duty Roster
    *--------------------------*/
    public function bdtaskt1c1_19_checkEmpRoster()
    {

        $date=date('Y-m-d');
        $signin   = date("h:i:s a", time());
        $sin_time = date("H:i", time());
        $emp_id   = $this->request->getVar('emp_id');

        $builder3 = $this->db->table('hrm_emproster_assign');
        $builder3->where('emp_startroster_date >=',$date);
        $builder3->where('emp_id',$emp_id);
        $getRes = $builder3->countAllResults();

        echo json_encode($getRes);

    }

    /*--------------------------
    | employeeProfile
    *--------------------------*/
    public function bdtask1c1_20_employeeProfile($id=null)
    {
        $data['title']      = get_phrases(['employee','profile']);
        $data['moduleTitle']= get_phrases(['human', 'resource']);
        $data['module']     = "human_resource";
        $data['page']       = "employee/employee_profile";

        // Get employee individual info from hrm_employees table by the id
        $data['employee']   = $employee_info = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_employees', array('id'=>$id));
        $data['employee_profile_info'] = $this->bdtaskt1c1_01_EmpModel->employee_profile_info($employee_info->employee_id);
        //Employee all attachments
        $data['employee_attachments'] = $this->bdtaskt1c1_01_EmpModel->employee_attachments($employee_info->employee_id);
        $data['attachment_type_list']    = $this->bdtaskt1c1_01_EmpModel->attachment_types();

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | employeeProfile
    *--------------------------*/
    public function bdtask1c1_21_employeeDetailsPrint($id=null)
    {
        $data['title']      = get_phrases(['employee','profile']);
        $data['moduleTitle']= get_phrases(['human', 'resource']);
        $data['module']     = "human_resource";
        $data['page']       = "employee/employee_profile";

        // Get employee individual info from hrm_employees table by the id
        $data['employee']   = $employee_info = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_employees', array('id'=>$id));
        $data['employee_profile_info'] = $employee_profile_info = $this->bdtaskt1c1_01_EmpModel->employee_profile_info($employee_info->employee_id);

        return view('App\Modules\Human_resource\Views\employee\employee_print_details',$data);
    }

    /* Employee CRUD ends*/

    /* Best employee CRUD starts*/

    /*--------------------------
    | best_employee_list
    *--------------------------*/
    public function bdtask1c1_22_best_employee_list()
    {
        $data['title']      = get_phrases(['best', 'employees']);
        $data['moduleTitle']= get_phrases(['human', 'resource']);
        $data['isDTables']  = true;
        // $data['isDateTimes']= true;

        $data['employee_list']    = $this->bdtaskt1c1_01_EmpModel->get_all_employee_list();

        $data['module']     = "human_resource";
        $data['page']       = "employee/best_employees";

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | Add departments info
    *--------------------------*/
    public function bdtask1c1_24_add_best_employee()
    { 
        $id = $this->request->getVar('id');
        $action = $this->request->getVar('action');
        $data = array(
            'date'             => $this->request->getVar('date'),
            'employee_id'  => $this->request->getVar('employee_id'),
            'reason'          => $this->request->getVar('reason'),
        );

        // echo "<pre>";
        // print_r($data);
        // exit;

        $MesTitle = get_phrases(['best', 'employee']);
       
        if($action=='update'){

            $best_employee = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_best_employees', array('id'=>$id));

            // Check , if not the same emplpoyee ... then check that id date already applied
            if($best_employee->employee_id != $this->request->getVar('employee_id') || $best_employee->date != $this->request->getVar('date')){

                 $respo_best_employee = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_best_employees', array('employee_id'=>$this->request->getVar('employee_id'),'date'=>$this->request->getVar('date')));
                
                if($respo_best_employee){

                    $response = array(
                        'success'  =>false,
                        'message'  => get_notify('employee_already_assigned_for_the_selected_date'),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);exit;
                }
            }

            $data['UpdateBy']   = session('id');
            $data['UpdateDate'] = date("Y-m-d h:i:s");

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_02_Update('hrm_best_employees', $data, array('id'=>$id));
            if($result){
                // Store log data
                $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['best','employee']), get_phrases(['updated']), $id, 'hrm_best_employees', 2);
                
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

            $respo_best_employee = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_best_employees', array('employee_id'=>$this->request->getVar('employee_id'),'date'=>$this->request->getVar('date')));
                
            if($respo_best_employee){

                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['this', 'employee', 'already', 'selected']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;
            }


            $data['CreateBy']   = session('id');
            $data['CreateDate'] = date("Y-m-d h:i:s");

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('hrm_best_employees', $data);
            if($result){
                // Store log data
                $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['best','employee']), get_phrases(['created']), $result, 'hrm_best_employees', 1);

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
    | get_best_employee_list
    *--------------------------*/
    public function bdtask1c1_23_get_best_employee_list()
    {

        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c1_01_EmpModel->bdtaskt1m1_06_getAllBesEmployees($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | get_best_employee_by_id
    *--------------------------*/
    public function bdtask1c1_25_get_best_employee_by_id($id)
    {

        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_best_employees', array('id'=>$id));
        echo json_encode($data);
    }


    /*--------------------------
    | deleteDepartmentById
    *--------------------------*/
    public function bdtask1c1_26_delete_best_employee_by_id($id)
    {

        $MesTitle = get_phrases(['best', 'employee']);

        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_06_Deleted('hrm_best_employees', array('id'=>$id));

        if(!empty($data)){

            // Store log data
            $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['best','employee']), get_phrases(['deleted']), $id, 'hrm_best_employees', 3);

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

    /* Best employee CRUD ends*/


    /* Employee Basic salary CRUD starts*/



    /* Employee Basic salary CRUD endss*/

    /*Bulk empoloyee import starts*/

    public function bdtask1c1_18_bulkImportEmployees()
    { 
        $response = array();

        $action = $this->request->getVar('action');

        $MesTitle = get_notify('bulk_employee_upload');

        if($action=='add'){

            if(isset($_FILES['employee_list']['name']) && !empty($_FILES['employee_list']['name'])){

                $fileName = $_FILES["employee_list"]["tmp_name"];

                if ($_FILES["employee_list"]["size"] > 0) {                        
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
                            'message'  => "Total ".$respo['total_inserted']." rows inserted out of ".$respo['total_rows']." rows",
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

    function csv_read($fileName){

        //return $fileName;

        $file = fopen($fileName, "r");

        fgetcsv($file);

        $total_rows = 0;
        $arr = array();

        $total_inserted = 0;
        $duplicate_nid = array();
        $final_arr = array();

        //Get last employee to check employee_id and generate employee_id
        $employee_id=1;
        $employee_h = $this->bdtaskt1c1_01_EmpModel->last_employee();
        $employee_id = $employee_h->employee_id;
        //End
                        
        while (($column = fgetcsv($file)) !== FALSE) {
            
            $first_name = "";
            if (isset($column[0])) {
                $first_name = esc($column[0]);
            }
            $last_name = "";
            if (isset($column[1])) {
                $last_name = esc($column[1]);
            }

            $employee_type = "";
            if (isset($column[2])) {
                $employee_type = esc($column[2]);

                $employee_type_info = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_employee_types', array('type'=>$employee_type));
                if($employee_type_info){

                    $employee_type = $employee_type_info->id;
                }
            }

            $department = "";
            if (isset($column[3])) {
                $department = esc($column[3]);

                $department_info = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_departments', array('name'=>$department));
                if($department_info){

                    $department = $department_info->id;
                }
            }

            $joining_date = "";
            if (isset($column[4])) {
                $joining_date = date("Y-m-d",strtotime(esc($column[4])));
            }
            $gender = "";
            if (isset($column[5])) {
                $gender = esc($column[5]);
            }
            $mobile_no_1 = "";
            if (isset($column[6])) {
                $mobile_no_1 = esc($column[6]);
            }
            $empergency_no = "";
            if (isset($column[7])) {
                $empergency_no = esc($column[7]);
            }
            $nid_no = "";
            if (isset($column[8])) {
                $nid_no = esc($column[8]);
            }
            $salary_type = "";
            if (isset($column[9])) {
                $salary_type = strtolower(esc($column[9]));
            }
            $gross_salary = "";
            if (isset($column[10])) {
                $gross_salary = esc($column[10]);
            }
            $in_duty_roster = 0;
            if (isset($column[11])) {
                $in_duty_roster = strtolower(esc($column[11])) == "yes"?1:0;
            }

            // Increment employee_id by 1 everytime
            $employee_id = $employee_id+1; 
            
            //Arrange employee data to push
            $data = array(
                'employee_id'   =>$employee_id,
                'first_name'    =>$first_name,
                'last_name'     =>$last_name,
                'employee_type' =>$employee_type,
                'department'    =>$department,
                'joining_date'  =>$joining_date,
                'gender'        =>$gender,
                'mobile_no1'    =>$mobile_no_1,
                'mobile_no2'    =>$empergency_no,
                'nid_no'        =>$nid_no,
                'salary_type'   =>$salary_type,
                'gross_salary'  =>$gross_salary,
                'in_duty_roster'=>$in_duty_roster,
                'status'        =>1,
                'imported'      =>1,
                'CreateBy'      =>session('id'),
                'CreateDate'    =>date("Y-m-d h:i:s"),
            );

            // $arr[$total_rows] =  $data;

            $total_rows++;

            ////////////////// Inserting data and Dulicate check

            $employee_info = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_employees', array('nid_no'=>$nid_no));

            if($employee_info){

                $duplicate_nid[$total_rows] =  $nid_no;
            }else{

                $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('hrm_employees', $data);
                $total_inserted++;
            }

            /////////////////////END//////////////////
                        
        }

        // return $arr;

        ////////////////// Inserting data and Dulicate check response

        $final_arr['total_rows']     =  $total_rows;
        $final_arr['total_inserted'] =  $total_inserted;
        $final_arr['duplicate_nid']  =  $duplicate_nid;
        
        return $final_arr;

        /////////////////////END//////////////////
    }

    /*Bulk empoloyee import ends*/




    /*   
        Image File validation check
    */
    public function attachment_file_type($type = ""){

        $file_types = ['jpg','jpeg','png','gif','pdf'];

        if(in_array($type, $file_types)){

            return true;

        }else{

            return false;
        }
    }

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

    /*   
        Signature File validation check
    */
    public function signature_file_type($type = ""){

        $file_types = ['jpg','jpeg','png'];

        if(in_array($type, $file_types)){

            return true;

        }else{

            return false;
        }
    }

    ///////////////////////////////*Code start for SA-Agro group*//////////////////////////////////

    /*--------------------------
    | deleteDepartmentById
    *--------------------------*/
    public function bdtask1c1_20_departmentHeadAlreadyAssigned($employee_id)
    {

        $MesTitle = get_phrases(['department', 'record']);

        // Check , if the Department already used for any employee

        $dept_head_info = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_departments', array('department_head'=>$employee_id));


        if($dept_head_info != null || $dept_head_info){

            $response = array(
                'success'  => "exist",
                'message'  => get_notify('employee_already_assigned_as_department_head').' !',
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }

        // End
    }

    /* Designation CRUD starts*/

    /*--------------------------
    | designation List
    *--------------------------*/
    public function bdtask1c1_21_designationList()
    {
        $data['title']      = get_phrases(['designation', 'list']);
        $data['moduleTitle']= get_phrases(['human', 'resource']);
        $data['isDTables']  = true;
        $data['module']     = "human_resource";
        $data['page']       = "employee/designations";

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | Get designation info
    *--------------------------*/
    public function bdtask1c1_22_getDesignationsList()
    { 


        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c1_01_EmpModel->bdtaskt1m1_04_getAllDesignations($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | Add designation info
    *--------------------------*/
    public function bdtask1c1_23_addDesignation()
    { 
        $id = $this->request->getVar('id');
        $action = $this->request->getVar('action');
        $data = array(
            'name'             => $this->request->getVar('name'),
            'details'          => $this->request->getVar('details'),
        );

        // echo "<pre>";
        // print_r($data);
        // exit;

        $MesTitle = get_phrases(['designation', 'record']);
       
        if($action=='update'){

            $respo_designation = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_designation', array('id'=>$id));

            // Check , if the same title ... then ignore the title and show message
            if($respo_designation->name != $this->request->getVar('name')){

                $respo_designation_name = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_designation', array('name'=>$this->request->getVar('name')));
                
                if($respo_designation_name){

                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['similar', 'designation', 'already', 'created']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);exit;
                }
            }

            $data['UpdateBy']   = session('id');
            $data['UpdateDate'] = date("Y-m-d h:i:s");

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_02_Update('hrm_designation', $data, array('id'=>$id));
            if($result){
                // Store log data
                $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['designation','name']), get_phrases(['updated']), $id, 'hrm_designation', 2);
                
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

            $respo_designation_name = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_designation', array('name'=>$this->request->getVar('name')));
                
            if($respo_designation_name){

                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['this', 'name', 'already', 'used']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;
            }


            $data['CreateBy']   = session('id');
            $data['CreateDate'] = date("Y-m-d h:i:s");

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('hrm_designation', $data);
            if($result){
                // Store log data
                $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['designation','name']), get_phrases(['created']), $result, 'hrm_designation', 1);
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
    | getdesignationById
    *--------------------------*/
    public function bdtask1c1_24_getdesignationById($designation_id)
    {

        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_designation', array('id'=>$designation_id));
        echo json_encode($data);
    }

    /*--------------------------
    | deleteDesignationById
    *--------------------------*/
    public function bdtask1c1_25_deleteDesignationById($designation_id)
    {

        $MesTitle = get_phrases(['designation', 'record']);

        // Check , if the Department already used for any employee

        $employee_info = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_employees', array('designation'=>$designation_id));

        if($employee_info != null && (int)$employee_info->designation > 0){

            $response = array(
                'success'  => false,
                'message'  => get_notify('employee_already_assigned_to_this_designation').' !',
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }

        // End

        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_06_Deleted('hrm_designation', array('id'=>$designation_id));

        if(!empty($data)){

            // Store log data
            $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['designation','name']), get_phrases(['deleted']), $designation_id, 'hrm_designation', 3);

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

    /* Designation CRUD ends*/


    /* Branch CRUD starts*/

    /*--------------------------
    | branchList
    *--------------------------*/
    public function bdtask1c1_26_branchList()
    {
        $data['title']      = get_phrases(['branch', 'list']);
        $data['moduleTitle']= get_phrases(['human', 'resource']);
        $data['isDTables']  = true;
        $data['module']     = "human_resource";
        $data['page']       = "employee/branch";

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | Get designation info
    *--------------------------*/
    public function bdtask1c1_27_getBranchList()
    { 

        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c1_01_EmpModel->bdtaskt1m1_05_getAllBranches($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | Add branch info
    *--------------------------*/
    public function bdtask1c1_28_addBranch()
    { 
        $id = $this->request->getVar('id');
        $action = $this->request->getVar('action');
        $data = array(
            'name'             => $this->request->getVar('name'),
            'details'          => $this->request->getVar('details'),
        );

        // echo "<pre>";
        // print_r($data);
        // exit;

        $MesTitle = get_phrases(['branch', 'record']);
       
        if($action=='update'){

            $respo_branch = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_branch', array('id'=>$id));

            // Check , if the same title ... then ignore the title and show message
            if($respo_branch->name != $this->request->getVar('name')){

                $respo_branch_name = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_branch', array('name'=>$this->request->getVar('name')));
                
                if($respo_branch_name){

                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['similar', 'branch', 'already', 'created']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);exit;
                }
            }

            $data['UpdateBy']   = session('id');
            $data['UpdateDate'] = date("Y-m-d h:i:s");

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_02_Update('hrm_branch', $data, array('id'=>$id));
            if($result){
                // Store log data
                $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['branch','name']), get_phrases(['updated']), $id, 'hrm_branch', 2);
                
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

            $respo_branch_name = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_branch', array('name'=>$this->request->getVar('name')));
                
            if($respo_branch_name){

                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['this', 'name', 'already', 'used']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;
            }


            $data['CreateBy']   = session('id');
            $data['CreateDate'] = date("Y-m-d h:i:s");

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('hrm_branch', $data);
            if($result){
                // Store log data
                $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['branch','name']), get_phrases(['created']), $result, 'hrm_branch', 1);
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
    | getdesignationById
    *--------------------------*/
    public function bdtask1c1_29_getbranchById($branch_id)
    {

        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_branch', array('id'=>$branch_id));
        echo json_encode($data);
    }

    /*--------------------------
    | deleteDesignationById
    *--------------------------*/
    public function bdtask1c1_30_deleteBranchById($branch_id)
    {

        $MesTitle = get_phrases(['branch', 'record']);

        // Check , if the Department already used for any employee

        $employee_info = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_employees', array('branch'=>$branch_id));

        if($employee_info != null && (int)$employee_info->branch > 0){

            $response = array(
                'success'  => false,
                'message'  => get_notify('employee_already_assigned_to_this_branch').' !',
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }

        // End

        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_06_Deleted('hrm_branch', array('id'=>$branch_id));

        if(!empty($data)){

            // Store log data
            $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['branch','name']), get_phrases(['deleted']), $branch_id, 'hrm_branch', 3);

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

    /* Branch CRUD ends*/
   
}
