<?php namespace App\Modules\Store\Controllers;
use App\Modules\Store\Views;
use CodeIgniter\Controller;
use App\Modules\Store\Models\Bdtaskt1MaterialStoreModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1c1MaterialStore extends BaseController
{
    private $bdtaskt1m12c4_01_main_storeModel;
    private $bdtaskt1m12c4_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c4_01_main_storeModel = new Bdtaskt1MaterialStoreModel();
        $this->bdtaskt1m12c4_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['store', 'list']);
        $data['moduleTitle']= get_phrases(['material','store']);
        $data['isDTables']  = true;
        $data['module']     = "Store";
        $data['page']       = "material_store/list";

        $data['hasCreateAccess']        = $this->permission->method('wh_material_store', 'create')->access();
        $data['hasPrintAccess']        = $this->permission->method('wh_material_store', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('wh_material_store', 'export')->access();
        
        $data['branch']       = $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        //var_dump($data['main_store']);exit;
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get main_store info
    *--------------------------*/
    public function bdtaskt1m12c4_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c4_01_main_storeModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete main_store by ID
    *--------------------------*/
    public function bdtaskt1m12c4_02_deleteMainStore($id)
    { 
        $material = $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material', array('store_id'=>$id));
        $material_receive = $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_receive', array('store_id'=>$id));
        $main_stock = $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_stock', array('store_id'=>$id, 'stock >'=>0 ));
        $MesTitle = get_phrases(['store', 'record']);
        if(!empty($material_receive) || !empty($material) || !empty($main_stock) ){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['store','record', 'exists']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }

        $data = $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_06_Deleted('wh_material_store', array('id'=>$id));
        // Store log data
        $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['material','store']), get_phrases(['deleted']), $id, 'wh_material_store');
        //$MesTitle = get_phrases(['store', 'record']);
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
    | Add main_store info
    *--------------------------*/
    public function bdtaskt1m12c4_03_addMainStore()
    { 

        //$old_image = $this->request->getVar('old_image'); 
        $action = $this->request->getVar('action');
        $data = array(
            'nameE'        => $this->request->getVar('nameE'), 
            'branch_id'        => session('branchId'), 
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'nameE'      => 'required|min_length[2]|max_length[150]',
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
            //$filePath = $this->bdtaskt1c1_01_fileUpload->doUpload('./assets/dist/img/main_store', $this->request->getFile('image'));
            if($action=='add'){
                $isExist = $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_03_getRow('wh_material_store', array('nameE'=>$this->request->getVar('nameE')));
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

                    $result = $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_01_Insert('wh_material_store',$data);
                    if($result){
                        $maxCoa = $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_18_getLikeMaxData('acc_coa', '2000','HeadCode');
                        $Coa = ((int)$maxCoa + 1);
                        $coaData = [
                            'HeadCode'         => $Coa,
                            'HeadName'         => $data['nameE'],
                            'PHeadName'        => 'Inventory',
                            'nameE'            => $data['nameE'],
                            'HeadLevel'        => '4',
                            'IsActive'         => '1',
                            'IsTransaction'    => '1',
                            'IsGL'             => '0',
                            'HeadType'         => 'L',
                            'IsBudget'         => '0',
                            'IsDepreciation'   => '0',
                            'DepreciationRate' => '0',
                            'CreateBy'         => session('id'),
                            'CreateDate'       => date('Y-m-d H:i:s'),
                        ]; 
                        $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_01_Insert('acc_coa',$coaData);
                        $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_02_Update('wh_material_store',array('acc_head'=>$Coa), array('id'=>$result));

                        // Store log data
                        $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['material','store']), get_phrases(['created']), $result, 'wh_material_store');
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
                $store_row = $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_03_getRow('wh_material_store', array('id'=>$id));
                //$data['image'] = !empty($filePath)?$filePath:$old_image;
                $data['updated_by']          = session('id');
                $data['updated_date']        = date('Y-m-d H:i:s');

                $result = $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_02_Update('wh_material_store',$data, array('id'=>$id));
                if($result){
                    $maxCoa = $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_18_getLikeMaxData('acc_coa', '2000','HeadCode');
                    $Coa = ((int)$maxCoa + 1);
                    $coaData = [
                        'HeadCode'         => $Coa,
                        'HeadName'         => $data['nameE'],
                        'PHeadName'        => 'Inventory',
                        'nameE'            => $data['nameE'],
                        'HeadLevel'        => '4',
                        'IsActive'         => '1',
                        'IsTransaction'    => '1',
                        'IsGL'             => '0',
                        'HeadType'         => 'L',
                        'IsBudget'         => '0',
                        'IsDepreciation'   => '0',
                        'DepreciationRate' => '0',
                        'CreateBy'         => session('id'),
                        'CreateDate'       => date('Y-m-d H:i:s'),
                    ]; 
                    if (empty($store_row->acc_head)) {
                        $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_01_Insert('acc_coa',$coaData);
                        $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_02_Update('wh_material_store',array('acc_head'=>$Coa), array('id'=>$id));
                    }else{
                        $acc_coa_data['HeadName']          = $data['nameE'];
                        $acc_coa_data['nameE']             = $data['nameE'];
                        $acc_coa_data['UpdateBy']          = session('id');
                        $acc_coa_data['UpdateDate']        = date('Y-m-d H:i:s');

                        $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_02_Update('acc_coa',$acc_coa_data, array('HeadCode'=>$store_row->acc_head));
                    }
                    // Store log data
                    $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['material','store']), get_phrases(['updated']), $id, 'wh_material_store');
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
    | Get main_store by ID
    *--------------------------*/
    public function bdtaskt1m12c4_04_getMainStoreById($id)
    { 
        $data = $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_03_getRow('wh_material_store', array('id'=>$id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get warehouse details by ID
    *--------------------------*/
    public function bdtaskt1m12c4_05_getMainStoreDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c4_01_main_storeModel->bdtaskt1m12_03_getMainStoreDetailsById($id);
        echo json_encode($data);
    }

   
}
