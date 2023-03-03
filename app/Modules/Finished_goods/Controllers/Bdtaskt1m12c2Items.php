<?php namespace App\Modules\Finished_goods\Controllers;
use App\Modules\Finished_goods\Views;
use CodeIgniter\Controller;
use App\Modules\Finished_goods\Models\Bdtaskt1m12ItemsModel;
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
        $data['moduleTitle']= get_phrases(['finished','goods']);
        $data['isDTables']  = true;
        $data['module']     = "Finished_goods";
        $data['page']       = "items/list";

        $data['hasCreateAccess']        = $this->permission->method('fg_items', 'create')->access();
        $data['hasPrintAccess']        = $this->permission->method('fg_items', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('fg_items', 'export')->access();

        $data['categories'] = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_categories', array('status'=>1));
        $data['goods_types'] = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('list_data', array('status'=>1,'list_id'=>30));
        $data['units']      = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('list_data', array('status'=>1,'list_id'=>15));
        $data['supplier']   = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_supplier_information', array('status'=>1));
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
        $main_stock = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_production_stock', array('item_id'=>$id, 'stock >'=>0 ));
        $sub_stock = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_sale_stock', array('item_id'=>$id, 'stock >'=>0 ));
        $MesTitle = get_phrases(['item', 'record']);
        if(!empty($main_stock) || !empty($sub_stock)){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['item', 'stock', 'exists']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }

        $data = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_06_Deleted('wh_items', array('id'=>$id));
        // Store log data
        $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','list']), get_phrases(['deleted']), $id, 'wh_items');
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
            'company_code'      => $this->request->getVar('company_code'), 
            'alert_qty'         => ($this->request->getVar('alert_qty') ? $this->request->getVar('alert_qty') : 0), 
            'minor_alert_qty'   => ($this->request->getVar('minor_alert_qty') ? $this->request->getVar('minor_alert_qty') : 0), 
            'cat_id'            => ($this->request->getVar('cat_id')=='')?0:$this->request->getVar('cat_id'), 
            'type_id'           => ($this->request->getVar('type_id')=='')?0:$this->request->getVar('type_id'), 
            'unit_id'           => $this->request->getVar('unit_id'), 
            'vat_applicable'    => ($this->request->getVar('vat_applicable')!='')?1:0,
            'has_expiry'        => ($this->request->getVar('has_expiry')!='')?1:0,
            'item_type'         => 'both',//$this->request->getVar('item_type'), 
            'consumed_by'       => 'both',
            'price'             => $this->request->getVar('price'),
            'bag_weight'        => $this->request->getVar('bag_weight'),
            'com_rate'          => $this->request->getVar('com_rate'), 
            'description'       => $this->request->getVar('description'),//($this->request->getVar('item_type')=='sellable')?'':$this->request->getVar('consumed_by') 
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'nameE'         => 'required|min_length[4]|max_length[150]',
                'company_code'  => 'required|min_length[2]|max_length[150]',
                'alert_qty'     => 'required|numeric',
                'price'         => 'required|numeric',
                'unit_id'       => 'required',
                'com_rate'      => 'required',
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
                $isExist = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_03_getRow('wh_items', array('company_code'=>$this->request->getVar('company_code')));

                if( !empty($isExist) ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['code','already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    $data['item_code']      = 'ITEM-'.getMAXID('wh_items', 'id');
                    $data['created_by']     = session('id');
                    $data['created_date']   = date('Y-m-d H:i:s');

                    $result = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_01_Insert('wh_items',$data);

                    // Store log data
                    $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','list']), get_phrases(['created']), $result, 'wh_items');

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

                $isExist = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_03_getRow('wh_items', array('company_code'=>$this->request->getVar('company_code'), 'id !='=> $id ));

                if( !empty($isExist) ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['company','code','already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    $data['updated_by']          = session('id');
                    $data['updated_date']        = date('Y-m-d H:i:s');
                    
                    $result = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_02_Update('wh_items',$data, array('id'=>$id));

                    // Store log data
                    $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','list']), get_phrases(['updated']), $id, 'wh_items');

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
        $data = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_03_getRow('wh_items', array('id'=>$id));
        $data2 = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items_supplier', array('item_id'=>$id));
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
        $result = array('item'=>$data, 'supplier'=>$data2);
        echo json_encode($result);
    }

   
}
