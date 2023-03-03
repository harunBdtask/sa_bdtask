<?php namespace App\Modules\Human_resource\Controllers;
use CodeIgniter\Controller;
use App\Modules\Human_resource\Models\Bdtaskt1m2Setup;
use App\Modules\Human_resource\Models\Bdtaskt1m1Employee;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1m9c2Setup extends BaseController
{
    private $bdtaskt1c1_02_Setup;
    private $bdtaskt1c1_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->db = db_connect();

        $this->permission = new Permission();
        $this->bdtaskt1c1_02_Setup = new Bdtaskt1m2Setup();
        $this->bdtaskt1c1_01_EmpModel = new Bdtaskt1m1Employee();
        $this->bdtaskt1c1_02_CmModel = new Bdtaskt1m1CommonModel();
    }


    /* Employee Basic salary CRUD starts*/

    /*--------------------------
    | basicSalaryList
    *--------------------------*/
    public function bdtask1c2_01_basicSalaryList()
    {
        $data['title']      = get_phrases(['basic','salary','setup', 'list']);
        $data['moduleTitle']= get_phrases(['human', 'resource']);
        $data['isDTables']  = true;
        $data['module']     = "human_resource";
        $data['page']       = "setup/basic_salary_setup_list";

        $data['employee_types']   = $this->bdtaskt1c1_02_Setup->employee_types_for_basic_salaries();

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | getBasicSalaryList
    *--------------------------*/
    public function bdtask1c2_02_getBasicSalaryList()
    {

        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c1_02_Setup->bdtaskt1m1_01_getBasicSalaryList($postData);
        echo json_encode($data);
    }

    /*--------------------------
    |_basicSalaryCreate / Edit
    *--------------------------*/
    public function bdtask1c2_03_basicSalaryCreate()
    { 
        $id = $this->request->getVar('id');
        $action = $this->request->getVar('action');
        $data = array(
            'employee_type' => $this->request->getVar('employee_type'),
            'percent'       => $this->request->getVar('percent'),
            'details'       => $this->request->getVar('details'),
        );

        $MesTitle = get_phrases(['basic', 'salaery', 'setup']);

        // Percent must be lesser than 100...
        if((int)$this->request->getVar('percent') > 100){

            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['percent', 'should', 'not', 'exceed', '100']),
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;
        }
        // End
       
        if($action=='update'){

            unset($data['employee_type']);

            $data['UpdateBy']   = session('id');
            $data['UpdateDate'] = date("Y-m-d h:i:s");

            // echo "<pre>";
            // print_r($data);
            // exit;

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_02_Update('hrm_basic_salary_setup', $data, array('id'=>$id));
            if($result){
                // Store log data
                $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['basic', 'salaery', 'setup']), get_phrases(['updated']), $id, 'hrm_basic_salary_setup', 2);
                
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

            // echo "<pre>";
            // print_r($data);
            // exit;

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('hrm_basic_salary_setup', $data);
            if($result){
                // Store log data
                $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['basic', 'salaery', 'setup']), get_phrases(['created']), $result, 'hrm_basic_salary_setup', 1);
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
    public function bdtask1c2_04_basicSalaryById($id)
    {

        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_basic_salary_setup', array('id'=>$id));

        // Get e,ployee type data
        $respo = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_employee_types', array('id'=>$data->employee_type));
        $data->type_name = $respo->type;

        echo json_encode($data);
    }


    /*--------------------------
    | deleteBasicSalary
    *--------------------------*/
    public function bdtask1c2_06_deleteBasicSalary($id)
    {

        $MesTitle = get_phrases(['department', 'record']);

        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_06_Deleted('hrm_basic_salary_setup', array('id'=>$id));

        if(!empty($data)){

            // Store log data
            $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['basic', 'salaery', 'setup']), get_phrases(['deleted']), $id, 'hrm_basic_salary_setup', 3);

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

    /* Employee Basic salary CRUD endss*/

    /* Employee Allowance CRUD starts*/

    /*--------------------------
    | basicSalaryList
    *--------------------------*/
    public function bdtask1c2_07_allowanceList()
    {
        $data['title']      = get_phrases(['allowance','setup', 'list']);
        $data['moduleTitle']= get_phrases(['human', 'resource']);
        $data['isDTables']  = true;
        $data['module']     = "human_resource";
        $data['page']       = "setup/allowance_setup_list";

        $data['employee_types']   = $this->bdtaskt1c1_02_Setup->employee_types_for_allowance_types();

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | getBasicSalaryList
    *--------------------------*/
    public function bdtask1c2_08_getallowanceList()
    {

        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c1_02_Setup->bdtaskt1m1_02_getallowanceList($postData);
        echo json_encode($data);
    }

    /*--------------------------
    |_basicSalaryCreate / Edit
    *--------------------------*/
    public function bdtask1c2_09_allowanceCreate()
    { 
        $id = $this->request->getVar('id');
        $action = $this->request->getVar('action');
        $data = array(
            'employee_type' => $this->request->getVar('employee_type'),
            'title'       => $this->request->getVar('title'),
            'amount'       => $this->request->getVar('amount'),
            'details'       => $this->request->getVar('details'),
        );

        // echo "<pre>";
        // var_dump($this->request->getVar('employee_type'));
        // print_r($data);
        // exit;

        $MesTitle = get_phrases(['allowance', 'setup']);

        // Percent must be lesser than 100...
        if($this->request->getVar('title') == '' || $this->request->getVar('amount') == ''){

            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['please', 'fill', 'up', 'required', 'fields']),
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;
        }
        // End
       
        if($action=='update'){

            $respo_allowance = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_allowance_setup', array('id'=>$id));

            // Check , if the same title ... then ignore the title and show message
            if($respo_allowance->title != $this->request->getVar('title')){

                $respo_allowance_title = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_allowance_setup', array('title'=>$this->request->getVar('title'),'employee_type'=>$respo_allowance->employee_type));
                
                if($respo_allowance_title){

                    $response = array(
                        'success'  =>false,
                        'message'  => get_notify('similar_title_already_used_for_selected_type'),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);exit;
                }
            }

            unset($data['employee_type']);

            $data['UpdateBy']   = session('id');
            $data['UpdateDate'] = date("Y-m-d h:i:s");

            // echo "<pre>";
            // print_r($data);
            // exit;

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_02_Update('hrm_allowance_setup', $data, array('id'=>$id));
            if($result){
                // Store log data
                $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['allowance', 'setup']), get_phrases(['updated']), $id, 'hrm_allowance_setup', 2);
                
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

            // employee_type empty check...
            if($this->request->getVar('employee_type') == ''){

                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['please', 'fill', 'up', 'required', 'fields']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;
            }
            // End

            $respo_allowance_title = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_allowance_setup', array('title'=>$this->request->getVar('title'),'employee_type'=>$this->request->getVar('employee_type')));
                
            if($respo_allowance_title){

                $response = array(
                    'success'  =>false,
                    'message'  => get_notify('this_title_already_used_for_selected_type'),
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;
            }

            $data['CreateBy']   = session('id');
            $data['CreateDate'] = date("Y-m-d h:i:s");

            // echo "<pre>";
            // print_r($data);
            // exit;

            $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('hrm_allowance_setup', $data);
            if($result){
                // Store log data
                $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['allowance', 'setup']), get_phrases(['created']), $result, 'hrm_allowance_setup', 1);
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
    | allowanceById
    *--------------------------*/
    public function bdtask1c2_10_allowanceById($id)
    {

        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_allowance_setup', array('id'=>$id));

        // Get e,ployee type data
        $respo = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_employee_types', array('id'=>$data->employee_type));
        $data->type_name = $respo->type;

        echo json_encode($data);
    }

    /*--------------------------
    | deleteBasicSalary
    *--------------------------*/
    public function bdtask1c2_12_deleteAllowance($id)
    {

        $MesTitle = get_phrases(['allowance','setup', 'record']);

        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_06_Deleted('hrm_allowance_setup', array('id'=>$id));

        if(!empty($data)){

            // Store log data
            $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['allowance', 'setup']), get_phrases(['deleted']), $id, 'hrm_allowance_setup', 3);

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
   
}
