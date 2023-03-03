<?php namespace App\Modules\Section\Controllers;
use App\Modules\Section\Views;
use CodeIgniter\Controller;
use App\Modules\Section\Models\Bdtaskt1m4Branch;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1c4Branchs extends BaseController
{
    private $bdtaskt1c4_01_branchModel;
    private $bdtaskt1c4_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1c4_01_branchModel = new Bdtaskt1m4Branch();
        $this->bdtaskt1c4_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['branch', 'list']);
        $data['moduleTitle']= get_phrases(['section']);
        $data['isDTables']  = true;
        $data['module']     = "Section";
        $data['page']       = "branch/list";

        $data['hasCreateAccess']        = $this->permission->method('branch_list', 'create')->access();
        $data['hasPrintAccess']        = $this->permission->method('branch_list', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('branch_list', 'export')->access();
        
        $data['branchs']       = $this->bdtaskt1c4_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1, 'id'=>session('branchId')));
        //var_dump($data['branchs']);exit;
        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | Get branch info
    *--------------------------*/
    public function bdtaskt1c4_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c4_01_branchModel->bdtaskt1m4_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete branch by ID
    *--------------------------*/
    public function bdtaskt1c4_02_deleteDepart($id)
    { 
        $appointment = $this->bdtaskt1c4_02_CmModel->bdtaskt1m1_05_getResultWhere('appointment', array('branch_id'=>$id, 'status'=>1 ));
        $employees = $this->bdtaskt1c4_02_CmModel->bdtaskt1m1_05_getResultWhere('employees', array('branch_id'=>$id, 'status'=>1 ));
        $warehouse = $this->bdtaskt1c4_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_production_store', array('branch_id'=>$id, 'status'=>1 ));
        $MesTitle = get_phrases(['branch', 'record']);

        if(!empty($appointment) || !empty($employees) || !empty($warehouse)){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['relational', 'data', 'exists']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }

        $data = $this->bdtaskt1c4_02_CmModel->bdtaskt1m1_06_Deleted('branch', array('id'=>$id));
        //$MesTitle = get_phrases(['branch', 'record']);
        if(!empty($data)){
            // Store log data
            $this->bdtaskt1c4_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['branch']), get_phrases(['deleted']), $id, 'branch', 3);
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
    | Add branch info
    *--------------------------*/
    public function bdtaskt1c4_03_addBranch()
    { 

        $old_image = $this->request->getVar('old_image'); 
        $action = $this->request->getVar('action');
        $data = array(
            'nameE'        => $this->request->getVar('branch_name'), 
            'nameA'        => $this->request->getVar('branch_nameA'), 
            'vat_no'       => $this->request->getVar('vat_no'), 
            //'flaticon'    => $this->request->getVar('flaticon'), 
            //'description' => $this->request->getVar('description'), 
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'branch_name'      => 'required|min_length[6]|max_length[150]',
                'branch_nameA'      => 'required|min_length[6]|max_length[150]',
            ];
        }
        $MesTitle = get_phrases(['branch', 'record']);
        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            $filePath = '';//$this->bdtaskt1c1_01_fileUpload->doUpload('./assets/dist/img/branch', $this->request->getFile('image'));
            if($action=='add'){
                $isExist = $this->bdtaskt1c4_02_CmModel->bdtaskt1m1_03_getRow('branch', array('nameE'=>$this->request->getVar('branch_name')));
                $isExist2 = $this->bdtaskt1c4_02_CmModel->bdtaskt1m1_03_getRow('branch', array('nameA'=>$this->request->getVar('branch_nameA')));
                if(!empty($isExist) || !empty($isExist2)){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['name', 'already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    $data['image'] = $filePath;
                    $result = $this->bdtaskt1c4_02_CmModel->bdtaskt1m1_01_Insert('branch',$data);
                    if($result){
                        // Store log data
                        $this->bdtaskt1c4_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['branch']), get_phrases(['created']), $result, 'branch', 1);
                        $response = array(
                            'success'  =>true,
                            'message'  => get_phrases(['added', 'successfully']),
                            'title'    => $MesTitle
                        );
                    }else{
                        $response = array(
                            'success'  =>false,
                            'message'  => get_phrases(['something', 'want', 'wrong']),
                            'title'    => $MesTitle
                        );
                    }
                }
            }else{
                $id = $this->request->getVar('id');
                $data['image'] = !empty($filePath)?$filePath:$old_image;
                $result = $this->bdtaskt1c4_02_CmModel->bdtaskt1m1_02_Update('branch',$data, array('id'=>$id));
                if($result){
                    // Store log data
                    $this->bdtaskt1c4_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['branch']), get_phrases(['updated']), $id, 'branch', 2);
                    $response = array(
                        'success'  =>true,
                        'message'  => get_phrases(['updated', 'successfully']),
                        'title'    => $MesTitle
                    );
                }else{
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['something', 'want', 'wrong']),
                        'title'    => $MesTitle
                    );
                }
            }
            
        }
        
        echo json_encode($response);
    }

    /*--------------------------
    | Get branch by ID
    *--------------------------*/
    public function bdtaskt1c4_04_getDepartById($id)
    { 
        $data = $this->bdtaskt1c4_02_CmModel->bdtaskt1m1_03_getRow('branch', array('id'=>$id));
        echo json_encode($data);
    }

   
}
