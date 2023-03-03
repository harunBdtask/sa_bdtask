<?php namespace App\Modules\Section\Controllers;
use App\Modules\Section\Views;
use CodeIgniter\Controller;
use App\Modules\Section\Models\Bdtaskt1m1Department;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1c1Departments extends BaseController
{
    private $bdtaskt1c1_01_deptModel;
    private $bdtaskt1c1_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1c1_01_deptModel = new Bdtaskt1m1Department();
        $this->bdtaskt1c1_02_CmModel = new Bdtaskt1m1CommonModel();
        if(session('defaultLang')=='english'){
            $this->langColumn = 'nameE';
        }else{
            $this->langColumn = 'nameA';
        }
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['department', 'list']);
        $data['moduleTitle']= get_phrases(['section']);
        $data['isDTables']  = true;
        $data['module']     = "Section";
        $data['page']       = "department/list";

        $data['hasCreateAccess']        = $this->permission->method('department_list', 'create')->access();
        $data['hasPrintAccess']        = $this->permission->method('department_list', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('department_list', 'export')->access();
        
        $data['branchs']       = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1, 'id'=>session('branchId')));
        $data['departments']       = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_05_getResultWhere('department', array('status'=>1));
        $data['store_list']       = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_sale_store', array('status'=>1,'branch_id'=>session('branchId')));
        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | Get Department info
    *--------------------------*/
    public function bdtaskt1c1_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c1_01_deptModel->bdtaskt1m1_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete department by ID
    *--------------------------*/
    public function bdtaskt1c1_02_deleteDepart($id)
    { 
        $appointment = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_05_getResultWhere('appointment', array('department_id'=>$id, 'status'=>1 ));
        $employees = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_05_getResultWhere('employees', array('department_id'=>$id, 'status'=>1 ));
        $MesTitle = get_phrases(['department', 'record']);

        if(!empty($appointment) || !empty($employees)){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['relational', 'data', 'exists']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }

        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_06_Deleted('department', array('id'=>$id));
        //$MesTitle = get_phrases(['department', 'record']);
        if(!empty($data)){
            // Store log data
            $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['department']), get_phrases(['deleted']), $id, 'department', 3);
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
    | Add Department info
    *--------------------------*/
    public function bdtaskt1c1_03_addDepartment()
    { 
        $action = $this->request->getVar('action');
        $data = array(
            'nameE'        => $this->request->getVar('nameE'), 
            'nameA'        => $this->request->getVar('nameA'), 
            'parent_id'    => ($this->request->getVar('parent_id')=='')?0:$this->request->getVar('parent_id'), 
            //'branch_id'    => session('branchId'),
            'store_id' => ($this->request->getVar('store_id')=='')?0:$this->request->getVar('store_id'),
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'nameE'         => 'required|min_length[4]|max_length[150]',
                'nameA'         => 'required|min_length[4]|max_length[150]',
                //'branch_id'     => 'required',
                //'store_id'  => 'required',
            ];
        }
        $MesTitle = get_phrases(['department', 'record']);
        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            if($action=='add'){
                $isExist = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('department', array('nameE'=>$this->request->getVar('nameE')));
                if(!empty($isExist)){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['name', 'already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('department',$data);
                    if($result){
                        // Store log data
                        $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['department']), get_phrases(['created']), $result, 'department', 1);
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
                }
            }else{
                $id = $this->request->getVar('id');
                $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_02_Update('department',$data, array('id'=>$id));
                if($result){
                    // Store log data
                    $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['department']), get_phrases(['updated']), $id, 'department', 2);
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

    /*--------------------------
    | Get department by ID
    *--------------------------*/
    public function bdtaskt1c1_04_getDepartById($id)
    { 
        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('department', array('id'=>$id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get department list
    *--------------------------*/
    public function bdtaskt1c1_05_getDepartmentList()
    { 
        $data = $this->bdtaskt1c1_01_deptModel->bdtaskt1m1_03_getDepartmentList();
        echo json_encode($data);
    }

    /*--------------------------
    | Get departments list by branch
    *--------------------------*/
    public function bdtaskt1c1_06_getDepartmentsByBranch($branch)
    { 
        $data = $this->bdtaskt1c1_01_deptModel->bdtaskt1m1_04_getDepartmentsByBranch($branch);
        echo json_encode($data);
    }
    
}
