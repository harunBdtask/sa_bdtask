<?php namespace App\Modules\Machine\Controllers;
use App\Modules\Machine\Views;
use CodeIgniter\Controller;
use App\Modules\Machine\Models\Bdtaskt1MachineStockModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1c4MachineStock extends BaseController
{
    private $bdtaskt1m12c6_01_sub_stockModel;
    private $bdtaskt1m12c6_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c6_01_sub_stockModel = new Bdtaskt1MachineStockModel();
        $this->bdtaskt1m12c6_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['item','stock']);
        $data['moduleTitle']= get_phrases(['plant']);
        $data['isDTables']  = true;
        $data['module']     = "Machine";
        $data['page']       = "machine_stock/list";

        $data['hasPrintAccess']        = $this->permission->method('wh_machine_stock', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('wh_machine_stock', 'export')->access();
        
        if(session('store_id') !='' && session('store_id') !='0'){
            $where = array('status'=>1, 'id'=>session('store_id') );
        } else {
            $where = array('status'=>1,'branch_id'=>session('branchId') );
        }

        $data['sub_store_list']       = $this->bdtaskt1m12c6_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_machine_store', $where);
        
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get sub_stock info
    *--------------------------*/
    public function bdtaskt1m12c6_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c6_01_sub_stockModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete sub_stock by ID
    *--------------------------*/
    public function bdtaskt1m12c6_02_deleteSubStock($id)
    { 
        $data = $this->bdtaskt1m12c6_02_CmModel->bdtaskt1m1_06_Deleted('wh_machine_stock', array('id'=>$id));
        $MesTitle = get_phrases(['stock', 'record']);
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
    | Add sub_stock info
    *--------------------------*/
    public function bdtaskt1m12c6_03_addSubStock()
    { 

        //$old_image = $this->request->getVar('old_image'); 
        $action = $this->request->getVar('action');
        $data = array(
            'nameE'        => $this->request->getVar('nameE'), 
            'nameA'        => $this->request->getVar('nameA'), 
            'branch_id'        => $this->request->getVar('branch_id'), 
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'nameE'      => 'required|min_length[4]|max_length[150]',
                'nameA'      => 'required|min_length[4]|max_length[150]',
            ];
        }
        $MesTitle = get_phrases(['stock', 'record']);
        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            //$filePath = $this->bdtaskt1c1_01_fileUpload->doUpload('./assets/dist/img/sub_stock', $this->request->getFile('image'));
            if($action=='add'){
                $isExist = $this->bdtaskt1m12c6_02_CmModel->bdtaskt1m1_03_getRow('wh_machine_stock', array('nameE'=>$this->request->getVar('nameE')));
                $isExist2 = $this->bdtaskt1m12c6_02_CmModel->bdtaskt1m1_03_getRow('wh_machine_stock', array('nameA'=>$this->request->getVar('nameA')));
                if(!empty($isExist) || !empty($isExist2) ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    //$data['image'] = $filePath;
                    $result = $this->bdtaskt1m12c6_02_CmModel->bdtaskt1m1_01_Insert('wh_machine_stock',$data);
                    if($result){
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
                $result = $this->bdtaskt1m12c6_02_CmModel->bdtaskt1m1_02_Update('wh_machine_stock',$data, array('id'=>$id));
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
    | Get sub_stock by ID
    *--------------------------*/
    public function bdtaskt1m12c6_04_getSubStockById($id)
    { 
        $data = $this->bdtaskt1m12c6_02_CmModel->bdtaskt1m1_03_getRow('wh_machine_stock', array('id'=>$id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get stock details by ID
    *--------------------------*/
    public function bdtaskt1m12c6_05_getSubStockDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c6_01_sub_stockModel->bdtaskt1m12_03_getSubStockDetailsById($id);
        echo json_encode($data);
    }

   
}
