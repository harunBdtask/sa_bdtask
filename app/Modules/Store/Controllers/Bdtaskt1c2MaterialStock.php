<?php namespace App\Modules\Store\Controllers;
use App\Modules\Store\Views;
use CodeIgniter\Controller;
use App\Modules\Store\Models\Bdtaskt1MaterialStockModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1c2MaterialStock extends BaseController
{
    private $bdtaskt1m12c5_01_main_stockModel;
    private $bdtaskt1m12c5_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c5_01_main_stockModel = new Bdtaskt1MaterialStockModel();
        $this->bdtaskt1m12c5_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['item', 'stock']);
        $data['moduleTitle']= get_phrases(['material','store']);
        $data['isDTables']  = true;
        $data['module']     = "Store";
        $data['page']       = "material_stock/list";
        $data['setting']    = $this->bdtaskt1m12c5_02_CmModel->bdtaskt1m1_03_getRow('setting');

        $data['hasPrintAccess']        = $this->permission->method('wh_material_stock', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('wh_material_stock', 'export')->access();
        
        $where = array('status'=>1);

        $data['store_list']       = $this->bdtaskt1m12c5_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_store', $where);
        $data['item_list']        = $this->bdtaskt1m12c5_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material', $where);

        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get main_stock info
    *--------------------------*/
    public function bdtaskt1m12c5_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c5_01_main_stockModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete main_stock by ID
    *--------------------------*/
    public function bdtaskt1m12c5_02_deleteMainStock($id)
    { 
        $data = $this->bdtaskt1m12c5_02_CmModel->bdtaskt1m1_06_Deleted('wh_material_stock', array('id'=>$id));
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
    | Add main_stock info
    *--------------------------*/
    public function bdtaskt1m12c5_03_addMainStock()
    { 

        //$old_image = $this->request->getVar('old_image'); 
        $action = $this->request->getVar('action');
        $data = array(
            'nameE'        => $this->request->getVar('nameE'), 
            'nameA'        => $this->request->getVar('nameA'), 
            'branch_id'    => session('branchId'), 
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
            //$filePath = $this->bdtaskt1c1_01_fileUpload->doUpload('./assets/dist/img/main_stock', $this->request->getFile('image'));
            if($action=='add'){
                $isExist = $this->bdtaskt1m12c5_02_CmModel->bdtaskt1m1_03_getRow('wh_material_stock', array('nameE'=>$this->request->getVar('nameE')));
                $isExist2 = $this->bdtaskt1m12c5_02_CmModel->bdtaskt1m1_03_getRow('wh_material_stock', array('nameA'=>$this->request->getVar('nameA')));
                if(!empty($isExist) || !empty($isExist2) ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    //$data['image'] = $filePath;
                    $result = $this->bdtaskt1m12c5_02_CmModel->bdtaskt1m1_01_Insert('wh_material_stock',$data);
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
                $result = $this->bdtaskt1m12c5_02_CmModel->bdtaskt1m1_02_Update('wh_material_stock',$data, array('id'=>$id));
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
    | Get main_stock by ID
    *--------------------------*/
    public function bdtaskt1m12c5_04_getMainStockById($id)
    { 
        $data = $this->bdtaskt1m12c5_02_CmModel->bdtaskt1m1_03_getRow('wh_material_stock', array('id'=>$id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get stock details by ID
    *--------------------------*/
    public function bdtaskt1m12c5_05_getMainStockDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c5_01_main_stockModel->bdtaskt1m12_03_getMainStockDetailsById($id);
        echo json_encode($data);
    }

   
}
