<?php namespace App\Modules\Section\Controllers;
use App\Modules\Section\Views;
use CodeIgniter\Controller;
use App\Modules\Section\Models\Bdtaskt1m3Dealer;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1c3Dealer extends BaseController
{
    private $bdtaskt1c1_01_deptModel;
    private $bdtaskt1c1_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1c1_01_deptModel = new Bdtaskt1m3Dealer();
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
        $data['title']      = get_phrases(['dealer', 'list']);
        $data['moduleTitle']= get_phrases(['section']);
        $data['isDTables']  = true;
        $data['module']     = "Section";
        $data['page']       = "dealer/list";

        $data['hasCreateAccess']        = $this->permission->method('dealer_list', 'create')->access();
        $data['hasPrintAccess']        = $this->permission->method('dealer_list', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('dealer_list', 'export')->access();
        
        //$data['branchs']       = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1, 'id'=>session('branchId')));
        $data['reference_list']       = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_dealer_info', array('status'=>1));
        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | Get Dealer info
    *--------------------------*/
    public function bdtaskt1c1_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c1_01_deptModel->bdtaskt1m1_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete dealer by ID
    *--------------------------*/
    public function bdtaskt1c1_02_deleteDealer($id)
    { 
        $MesTitle = get_phrases(['dealer', 'record']);

        /*$appointment = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_05_getResultWhere('appointment', array('dealer_id'=>$id, 'status'=>1 ));
        $employees = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_05_getResultWhere('employees', array('dealer_id'=>$id, 'status'=>1 ));
        if(!empty($appointment) || !empty($employees)){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['relational', 'data', 'exists']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }*/

        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_06_Deleted('wh_dealer_info', array('id'=>$id));
        //$MesTitle = get_phrases(['dealer', 'record']);
        if(!empty($data)){
            // Store log data
            $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['dealer']), get_phrases(['deleted']), $id, 'wh_dealer_info', 3);
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
    | Add Dealer info
    *--------------------------*/
    public function bdtaskt1c1_03_addDealer()
    { 
        $action = $this->request->getVar('action');

        $affiliate_id = time();
        $acc_head = '1000'.getMAXID('wh_dealer_info', 'id');

        $data = array(
            'name'              => $this->request->getVar('name'), 
            'reference_id'      => $this->request->getVar('reference_id'),
            'affiliate_id'      => $affiliate_id, 
            'acc_head'          => $acc_head
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'name'         => 'required|min_length[4]|max_length[150]',
                'reference_id' => 'required',
            ];
        }
        $MesTitle = get_phrases(['dealer', 'record']);
        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            if($action=='add'){
                $isExist = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('wh_dealer_info', array('name'=>$this->request->getVar('nameE')));
                if(!empty($isExist)){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['name', 'already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert('wh_dealer_info',$data);
                    if($result){
                        // Store log data
                        $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['dealer']), get_phrases(['created']), $result, 'wh_dealer_info', 1);
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
                $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_02_Update('wh_dealer_info',$data, array('id'=>$id));
                if($result){
                    // Store log data
                    $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['dealer']), get_phrases(['updated']), $id, 'wh_dealer_info', 2);
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
    | Get dealer by ID
    *--------------------------*/
    public function bdtaskt1c1_04_getDealerById($id)
    { 
        $data = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('wh_dealer_info', array('id'=>$id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get dealer list
    *--------------------------*/
    public function bdtaskt1c1_05_getDealerList()
    { 
        $data = $this->bdtaskt1c1_01_deptModel->bdtaskt1m1_03_getDealerList();
        echo json_encode($data);
    }

    /*--------------------------
    | Get dealers list by branch
    *--------------------------*/
    public function bdtaskt1c1_06_getDealersByBranch($branch)
    { 
        $data = $this->bdtaskt1c1_01_deptModel->bdtaskt1m1_04_getDealersByBranch($branch);
        echo json_encode($data);
    }
    
}
