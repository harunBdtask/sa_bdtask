<?php 
// k
namespace App\Modules\Material\Controllers;
use App\Modules\Material\Views;
use CodeIgniter\Controller;
use App\Modules\Material\Models\Bdtaskt1m12ItemsModel;
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
        $data['title']      = get_phrases(['material', 'list']);
        $data['moduleTitle']= get_phrases(['raw','material']);
        $data['isDTables']  = true;
        $data['module']     = "Material";
        $data['page']       = "items/list";
        $data['setting']    = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_03_getRow('setting');

        $data['hasCreateAccess']        = $this->permission->method('wh_material_items', 'create')->access();
        $data['hasPrintAccess']        = $this->permission->method('wh_material_items', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('wh_material_items', 'export')->access();

        $data['categories'] = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_categories', array('status'=>1));
        $data['units']      = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('list_data', array('status'=>1,'list_id'=>15));
        $data['supplier']   = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_supplier_information', array('status'=>1));
        $data['where_use']  = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('list_data', array('status'=>1,'list_id'=>27));
        $data['stores']     = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_store', array('status'=>1));
        //var_dump($data['hasExportAccess']);exit;
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
        $quatation = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_quatation_details', array('item_id'=>$id));
        $requisition = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_requisition_details', array('item_id'=>$id));
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

        $data = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_06_Deleted('wh_material', array('id'=>$id));
        // Store log data
        $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','list']), get_phrases(['deleted']), $id, 'wh_material');
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
            'item_code'         => $this->request->getVar('item_code'), 
            'store_id'          => $this->request->getVar('store_id'),
            'aprox_monthly_consumption' => ($this->request->getVar('aprox_monthly_consumption') ? $this->request->getVar('aprox_monthly_consumption') : 0), 
            'alert_qty'         => ($this->request->getVar('alert_qty') ? $this->request->getVar('alert_qty') : 0), 
            'minor_alert_qty'   => ($this->request->getVar('minor_alert_qty') ? $this->request->getVar('minor_alert_qty') : 0), 
            'cat_id'            => ($this->request->getVar('cat_id')=='')?0:$this->request->getVar('cat_id'), 
            'unit_id'           => $this->request->getVar('unit_id'), 
            'where_use'         => $this->request->getVar('where_use'), 
            'wastage'           => ($this->request->getVar('wastage') ? $this->request->getVar('wastage') : 0), 
            'price'             => $this->request->getVar('price'), 
            'expire'            => $this->request->getVar('expire'), 
            'country_origin'    => $this->request->getVar('country_origin'), 
            'description'       => $this->request->getVar('description'),
            'vat_applicable'    => ($this->request->getVar('vat_applicable')!='')?1:0,
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
                $isExist = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('item_code'=>$this->request->getVar('item_code')));

                if( !empty($isExist) ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['item','code','already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    $data['created_by']     = session('id');
                    $data['created_date']   = date('Y-m-d H:i:s');

                    $result = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_01_Insert('wh_material',$data);

                    // Store log data
                    $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','list']), get_phrases(['created']), $result, 'wh_material');

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

                $isExist = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('item_code'=>$this->request->getVar('item_code'), 'id !='=> $id ));

                if( !empty($isExist) ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['item','code','already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    $data['updated_by']          = session('id');
                    $data['updated_date']        = date('Y-m-d H:i:s');
                    
                    $result = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_02_Update('wh_material',$data, array('id'=>$id));

                    $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_06_Deleted('wh_material_supplier', array('item_id'=>$id));

                    // Store log data
                    $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','list']), get_phrases(['updated']), $id, 'wh_material');

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
            
        }
        
        echo json_encode($response);
    }

    /*--------------------------
    | Get items by ID
    *--------------------------*/
    public function bdtaskt1m12c2_04_getItemsById($id)
    { 
        $data = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=>$id));
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
