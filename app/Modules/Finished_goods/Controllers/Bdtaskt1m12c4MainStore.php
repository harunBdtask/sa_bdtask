<?php namespace App\Modules\Finished_goods\Controllers;
use App\Modules\Finished_goods\Views;
use CodeIgniter\Controller;
use App\Modules\Finished_goods\Models\Bdtaskt1m12MainStoreModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1m12c4MainStore extends BaseController
{
    private $bdtaskt1m12c4_01_main_storeModel;
    private $bdtaskt1m12c4_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c4_01_main_storeModel = new Bdtaskt1m12MainStoreModel();
        $this->bdtaskt1m12c4_02_CmModel = new Bdtaskt1m1CommonModel();
        $this->db = db_connect();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['store', 'list']);
        $data['moduleTitle']= get_phrases(['finished','goods']);
        $data['isDTables']  = true;
        $data['module']     = "Finished_goods";
        $data['page']       = "main_store/list";

        $data['hasCreateAccess']        = $this->permission->method('fg_store', 'create')->access();
        $data['hasPrintAccess']        = $this->permission->method('fg_store', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('fg_store', 'export')->access();
        
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
        $main_stock = $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_production_stock', array('store_id'=>$id, 'stock >'=>0 ));
        $MesTitle = get_phrases(['store', 'record']);
        if(!empty($main_stock) ){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['item','stock', 'exists']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }

        $data = $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_06_Deleted('wh_production_store', array('id'=>$id));
        // Store log data
        $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['goods','store']), get_phrases(['deleted']), $id, 'wh_production_store');
        //$MesTitle = get_phrases(['store', 'record']);
        if(!empty($data)){
            // $this->db->table('acc_coa')->where('goods_store_id',$id)->delete();
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
            'nameA'        => $this->request->getVar('nameA'), 
            'branch_id'        => session('branchId'), 
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
            //$filePath = $this->bdtaskt1c1_01_fileUpload->doUpload('./assets/dist/img/main_store', $this->request->getFile('image'));
            if($action=='add'){
                $isExist = $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_03_getRow('wh_production_store', array('nameE'=>$this->request->getVar('nameE')));
                $isExist2 = $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_03_getRow('wh_production_store', array('nameA'=>$this->request->getVar('nameA')));
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

                    $result = $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_01_Insert('wh_production_store',$data);
                    $store_id = $this->db->insertID();
                    if($result){
                        // $maxCoa = $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_18_getLikeMaxData('acc_coa', '2000','HeadCode');
                        // $Coa = ((int)$maxCoa + 1);
                        // $coaData = [
                        //     'HeadCode'         => $Coa,
                        //     'HeadName'         => $data['nameE'],
                        //     'PHeadName'        => 'Inventory',
                        //     'nameE'            => $data['nameE'],
                        //     'HeadLevel'        => '4',
                        //     'IsActive'         => '1',
                        //     'IsTransaction'    => '1',
                        //     'IsGL'             => '0',
                        //     'HeadType'         => 'L',
                        //     'IsBudget'         => '0',
                        //     'IsDepreciation'   => '0',
                        //     'goods_store_id'   => $store_id,
                        //     'DepreciationRate' => '0',
                        //     'CreateBy'         => session('id'),
                        //     'CreateDate'       => date('Y-m-d H:i:s'),
                        // ]; 
                        // $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_01_Insert('acc_coa',$coaData);
                        // $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_02_Update('wh_production_store',array('acc_head'=>$Coa), array('id'=>$result));
                        // Store log data
                        $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['goods','store']), get_phrases(['created']), $result, 'wh_production_store');
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

                $result = $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_02_Update('wh_production_store',$data, array('id'=>$id));
                // Store log data
                $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['goods','store']), get_phrases(['updated']), $id, 'wh_production_store');
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
    | Get main_store by ID
    *--------------------------*/
    public function bdtaskt1m12c4_04_getMainStoreById($id)
    { 
        $data = $this->bdtaskt1m12c4_02_CmModel->bdtaskt1m1_03_getRow('wh_production_store', array('id'=>$id));
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
