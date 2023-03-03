<?php 
// k
namespace App\Modules\Assets\Controllers;
use App\Modules\Assets\Views;
use CodeIgniter\Controller;
use App\Modules\Assets\Models\Bdtaskt1m12ItemsModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1m12c2Items extends BaseController
{
    private $bdtaskt1m12c2_01_itemsModel;
    private $bdtaskt1m12c2_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c2_01_itemsModel = new Bdtaskt1m12ItemsModel();
        $this->bdtaskt1m12c2_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['item', 'list']);
        $data['moduleTitle']= get_phrases(['assets']);
        $data['isDTables']  = true;
        $data['module']     = "Assets";
        $data['page']       = "items/list";

        $data['hasCreateAccess']        = $this->permission->method('wh_assets_items', 'create')->access();
        $data['hasPrintAccess']         = $this->permission->method('wh_assets_items', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('wh_assets_items', 'export')->access();

        $data['categories'] = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_categories', array('status'=>1));
        $data['units']      = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('list_data', array('status'=>1,'list_id'=>15));
        $data['supplier']   = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_supplier_information', array('status'=>1));
        $data['where_use']  = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('list_data', array('status'=>1,'list_id'=>27));
        $data['cat_id']= $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_assets_categories', array('status'=>1));
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get items info
    *--------------------------*/
    public function bdtaskt1m12c2_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c2_01_itemsModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete items by ID
    *--------------------------*/
    public function bdtaskt1m12c2_02_deleteItems($id)
    { 
        $quatation = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_quatation_details', array('item_id'=>$id));
        $requisition = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_requisition_details', array('item_id'=>$id));
        $main_stock = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_production_stock', array('item_id'=>$id, 'stock >'=>0 ));
        $sub_stock = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_sale_stock', array('item_id'=>$id, 'stock >'=>0 ));
        $MesTitle = get_phrases(['item', 'record']);
        if(!empty($quatation) ||!empty($requisition) ||!empty($main_stock) || !empty($sub_stock)){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['item', 'exists']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }

        $data = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_06_Deleted('wh_assets', array('id'=>$id));
        // Store log data
        $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','list']), get_phrases(['deleted']), $id, 'wh_assets');
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
    | Add items info
    *--------------------------*/
    public function bdtaskt1m12c2_03_addItems()
    { 

        $action = $this->request->getVar('action');
        
        $data = array(
            'nameE'             => $this->request->getVar('nameE'), 
            'cat_id'            => $this->request->getVar('cat_id'), 
            'item_code'         => $this->request->getVar('item_code'), 
            'unit_id'           => $this->request->getVar('unit_id'), 
            'description'       => $this->request->getVar('description'),
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'nameE'         => 'required',
                'unit_id'       => 'required',
                'item_code'     => 'required',
            ];
        }
        $MesTitle = get_phrases(['item', 'record']);
        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{

            if($action=='add'){
                $isExist = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_03_getRow('wh_assets', array('item_code'=>$data['item_code']));

                if( !empty($isExist) ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['item','code','already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    $data['created_by']     = session('id');
                    $data['created_date']   = date('Y-m-d H:i:s');

                    $result = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_01_Insert('wh_assets',$data);

                    if(!empty($data['cat_id'])){
                        // $assets_categories = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_03_getRow('wh_assets_categories', array('id'=>$data['cat_id']));
                        // $maxCoa = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_18_getLikeMaxDataWhere('acc_coa',$assets_categories->acc_head,'HeadCode',array('HeadLevel'=>3));
                        // if (empty($maxCoa)) {
                        //     $Coa = $assets_categories->acc_head . "00001";
                        // }else {
                        //     $Coa = ((int)$maxCoa + 1);
                        // }
                        // $coaData = [
                        //     'HeadCode'         => $Coa,
                        //     'HeadName'         => $data['nameE'],
                        //     'PHeadName'        => $assets_categories->nameE,
                        //     'nameE'            => $data['nameE'],
                        //     'HeadLevel'        => '3',
                        //     'IsActive'         => '1',
                        //     'IsTransaction'    => '1',
                        //     'IsGL'             => '0',
                        //     'HeadType'         => 'A',
                        //     'IsBudget'         => '1',
                        //     'IsDepreciation'   => '1',
                        //     'DepreciationRate' => '1',
                        //     'CreateBy'         => session('id'),
                        //     'CreateDate'       => date('Y-m-d H:i:s'),
                        // ]; 
                        // $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_01_Insert('acc_coa',$coaData);
                        // $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_02_Update('wh_assets',array('acc_head'=>$Coa), array('id'=>$result));

                        // Store log data
                        $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['assets','item']), get_phrases(['created']), $result, 'wh_assets');

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
                $info = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_03_getRow('wh_assets', array('id'=>$id));
                $isExist = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_03_getRow('wh_assets', array('item_code'=>$this->request->getVar('item_code'), 'id !='=> $id ));

                if( !empty($isExist) ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['item','code','already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    $data['updated_by']          = session('id');
                    $data['updated_date']        = date('Y-m-d H:i:s');
                    
                    $result = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_02_Update('wh_assets',$data, array('id'=>$id));

                    if($result){
                        // $assets_categories = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_03_getRow('wh_assets_categories', array('id'=>$data['cat_id']));
                        // $maxCoa = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_18_getLikeMaxDataWhere('acc_coa',$assets_categories->acc_head,'HeadCode',array('HeadLevel'=>3));
                        // if (empty($maxCoa)) {
                        //     $Coa = $assets_categories->acc_head . "00001";
                        // }else {
                        //     $Coa = ((int)$maxCoa + 1);
                        // }
                        // $coaData = [
                        //     'HeadCode'         => $Coa,
                        //     'HeadName'         => $data['nameE'],
                        //     'PHeadName'        => $assets_categories->nameE,
                        //     'nameE'            => $data['nameE'],
                        //     'HeadLevel'        => '3',
                        //     'IsActive'         => '1',
                        //     'IsTransaction'    => '1',
                        //     'IsGL'             => '0',
                        //     'HeadType'         => 'A',
                        //     'IsBudget'         => '1',
                        //     'IsDepreciation'   => '1',
                        //     'DepreciationRate' => '1',
                        //     'CreateBy'         => session('id'),
                        //     'CreateDate'       => date('Y-m-d H:i:s'),
                        // ];
                        
                        // if (empty($info->acc_head)) {
                        //     $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_01_Insert('acc_coa',$coaData);
                        //     $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_02_Update('wh_assets',array('acc_head'=>$Coa), array('id'=>$id));
    
                        // }else{
                        //     $acc_coa_data['HeadName']          = $data['nameE'];
                        //     $acc_coa_data['nameE']             = $data['nameE'];
                        //     $acc_coa_data['UpdateBy']          = session('id');
                        //     $acc_coa_data['UpdateDate']        = date('Y-m-d H:i:s');
    
                        //     $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_02_Update('acc_coa',$acc_coa_data, array('HeadCode'=>$info->acc_head));
                        // }
                        // Store log data
                        $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['assets','item']), get_phrases(['updated']), $id, 'wh_assets');
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
            
        }
        
        echo json_encode($response);
    }

    /*--------------------------
    | Get items by ID
    *--------------------------*/
    public function bdtaskt1m12c2_04_getItemsById($id)
    { 
        $data = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_03_getRow('wh_assets', array('id'=>$id));
        $data2 = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_supplier', array('item_id'=>$id));
        $result = array('item'=>$data, 'supplier'=>$data2);
        echo json_encode($result);
    }

    /*--------------------------
    | Get item details by ID
    *--------------------------*/
    public function bdtaskt1m12c2_05_getItemDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c2_01_itemsModel->bdtaskt1m12_03_getItemDetailsById($id);
        $data2 = $this->bdtaskt1m12c2_01_itemsModel->bdtaskt1m12_04_getItemSupplierDetailsById($id);
        if (!empty($data['country_origin'])) {
            $data['country_name'] = countries($data['country_origin']);
        }else{
            $data['country_name'] = '';
        }
        $result = array('item'=>$data, 'supplier'=>$data2);
        echo json_encode($result);
    }

   
}
