<?php namespace App\Modules\Machine\Controllers;
use App\Modules\Machine\Views;
use CodeIgniter\Controller;
use App\Modules\Machine\Models\Bdtaskt1MachineStoreModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1c3MachineStore extends BaseController
{
    private $bdtaskt1m12c3_01_sub_storeModel;
    private $bdtaskt1m12c3_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c3_01_sub_storeModel = new Bdtaskt1MachineStoreModel();
        $this->bdtaskt1m12c3_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['plant', 'list']);
        $data['moduleTitle']= get_phrases(['plant']);
        $data['isDTables']  = true;
        $data['module']     = "Machine";
        $data['page']       = "machine_store/list";

        $data['hasCreateAccess']        = $this->permission->method('wh_machine_store', 'create')->access();
        $data['hasPrintAccess']        = $this->permission->method('wh_machine_store', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('wh_machine_store', 'export')->access();
        
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get sub_store info
    *--------------------------*/
    public function bdtaskt1m12c3_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c3_01_sub_storeModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete sub_store by ID
    *--------------------------*/
    public function bdtaskt1m12c3_02_deleteSubStore($id)
    { 
        $sub_stock = $this->bdtaskt1m12c3_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_machine_stock', array('sub_store_id'=>$id, 'stock >'=>0 ));
        $MesTitle = get_phrases(['store', 'record']);
        if(!empty($sub_stock)){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['item','stock', 'exists']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }

        $data = $this->bdtaskt1m12c3_02_CmModel->bdtaskt1m1_06_Deleted('wh_machine_store', array('id'=>$id));
        //$MesTitle = get_phrases(['store', 'record']);
        // Store log data
        $this->bdtaskt1m12c3_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['plant']), get_phrases(['deleted']), $id, 'wh_machine_store');
        if(!empty($data)){
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
    | Add sub_store info
    *--------------------------*/
    public function bdtaskt1m12c3_03_addSubStore()
    { 

        //$old_image = $this->request->getVar('old_image'); 
        $action = $this->request->getVar('action');
        $data = array(
            'nameE'        => $this->request->getVar('nameE'), 
            'nameA'        => $this->request->getVar('nameA'), 
            'branch_id'        => session('branchId')
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'nameE'      => 'required|min_length[2]|max_length[150]',
                'nameA'      => 'required|min_length[2]|max_length[150]',
            ];
        }
        $MesTitle = get_phrases(['store', 'record']);
        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            //$filePath = $this->bdtaskt1c1_01_fileUpload->doUpload('./assets/dist/img/sub_store', $this->request->getFile('image'));
            if($action=='add'){
                $isExist = $this->bdtaskt1m12c3_02_CmModel->bdtaskt1m1_03_getRow('wh_machine_store', array('nameE'=>$this->request->getVar('nameE')));
                $isExist2 = $this->bdtaskt1m12c3_02_CmModel->bdtaskt1m1_03_getRow('wh_machine_store', array('nameA'=>$this->request->getVar('nameA')));
                if(!empty($isExist) || !empty($isExist2) ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    //$data['image'] = $filePath;
                    $data['created_by']          = session('id');
                    $data['created_date']        = date('Y-m-d H:i:s');
                    
                    $result = $this->bdtaskt1m12c3_02_CmModel->bdtaskt1m1_01_Insert('wh_machine_store',$data);
                    if($result){
                        // Store log data
                        $this->bdtaskt1m12c3_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['plant']), get_phrases(['created']), $result, 'wh_machine_store');
                         $response = array(
                            'success'  =>true,
                            'message'  => get_phrases(['added', 'successfully']),
                            'title'    => $MesTitle
                        );
                    }else{
                        $response = array(
                            'success'  =>false,
                            'message'  => (ENVIRONMENT == 'production')?get_phrases(['something', 'went', 'wrong']):get_db_error(),
                            'title'    => $MesTitle
                        );
                    }
                }
            }else{
                $id = $this->request->getVar('id');
                //$data['image'] = !empty($filePath)?$filePath:$old_image;
                $data['updated_by']          = session('id');
                $data['updated_date']        = date('Y-m-d H:i:s');

                $result = $this->bdtaskt1m12c3_02_CmModel->bdtaskt1m1_02_Update('wh_machine_store',$data, array('id'=>$id));
                // Store log data
                $this->bdtaskt1m12c3_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['plant']), get_phrases(['updated']), $id, 'wh_machine_store');
                if($result){
                     $response = array(
                        'success'  =>true,
                        'message'  => get_phrases(['updated', 'successfully']),
                        'title'    => $MesTitle
                    );
                }else{
                    $response = array(
                        'success'  =>false,
                        'message'  => (ENVIRONMENT == 'production')?get_phrases(['something', 'went', 'wrong']):get_db_error(),
                        'title'    => $MesTitle
                    );
                }
            }
            
        }
        
        echo json_encode($response);
    }

    /*--------------------------
    | Get sub_store by ID
    *--------------------------*/
    public function bdtaskt1m12c3_04_getSubStoreById($id)
    { 
        $data = $this->bdtaskt1m12c3_02_CmModel->bdtaskt1m1_03_getRow('wh_machine_store', array('id'=>$id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get warehouse details by ID
    *--------------------------*/
    public function bdtaskt1m12c3_05_getSubStoreDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c3_01_sub_storeModel->bdtaskt1m12_03_getSubStoreDetailsById($id);
        echo json_encode($data);
    }

   
}
