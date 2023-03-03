<?php namespace App\Modules\Foreign_purchase\Controllers;
use App\Modules\Foreign_purchase\Views;
use CodeIgniter\Controller;
use App\Modules\Foreign_purchase\Models\Bdtaskt1m12PurchaseOrderModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1m12c10PurchaseOrder extends BaseController
{
    private $bdtaskt1m12c10_01_purchase_orderModel;
    private $bdtaskt1m12c10_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c10_01_purchase_orderModel = new Bdtaskt1m12PurchaseOrderModel();
        $this->bdtaskt1m12c10_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']              = get_phrases([ 'purchase','order']);
        $data['moduleTitle']        = get_phrases(['purchase']);
        $data['isDTables']          = true;
        $data['module']             = "Foreign_purchase";
        $data['page']               = "purchase_order/list";

        $data['hasCreateAccess']        = $this->permission->method('wh_foreign_purchase_order', 'create')->access();
        $data['hasPrintAccess']        = $this->permission->method('wh_foreign_purchase_order', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('wh_foreign_purchase_order', 'export')->access();
        
        $data['supplier_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('ah_supplier_information', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('ah_material', array('status'=>1));
       
        $data['store_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('ah_material_store', array('status'=>1,'branch_id'=>session('branchId')));
        
        //$store_list       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('ah_material_store', array('status'=>1,'branch_id'=>session('branchId')));

        //$data['store_id']       = ($store_list)?$store_list->id:0;
        //$data['store_name']       = ($store_list)?$store_list->nameE:'';
        
        $data['vat']       = get_setting('default_vat');
        
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get purchase_order info
    *--------------------------*/
    public function bdtaskt1m12c10_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c10_01_purchase_orderModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete purchase_order by ID
    *--------------------------*/
    public function bdtaskt1m12c10_02_deletePurchaseOrder($id)
    { 
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('ah_material_purchase', array('id'=>$id, 'isApproved'=>1));

        $MesTitle = get_phrases(['purchase', 'record']);
        if(!empty($data)){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['already', 'approved']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('ah_material_purchase', array('id'=>$id));
        $data2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('ah_material_purchase_details', array('purchase_id'=>$id));

        // Store log data
        $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['deleted']), $id, 'wh_material_purchase');
        //$MesTitle = get_phrases(['purchase', 'record']);
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

    public function date_db_format($date){
        if($date==''){
            return $date;
        }
        return implode("-", array_reverse(explode("/", $date)));
    }

    /*--------------------------
    | Add purchase_order info
    *--------------------------*/
    public function bdtaskt1m12c10_03_addPurchaseOrder()
    { 

        //$old_image = $this->request->getVar('old_image'); 
        $action = $this->request->getVar('action');

        $data = array(
            'store_id'      => $this->request->getVar('store_id'), 
            'supplier_id'   => $this->request->getVar('supplier_id'), 
            'sub_total'         => $this->request->getVar('sub_total'), 
            'grand_total'       => $this->request->getVar('grand_total'), 
            'vat'               => $this->request->getVar('vat'),
            
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'date'      => 'required',
                // 'supplier_id'   => 'required',
            ];
        }
        $MesTitle = get_phrases(['purchase', 'record']);
        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            //$filePath = $this->bdtaskt1c1_01_fileUpload->doUpload('./assets/dist/img/purchase_order', $this->request->getFile('image'));
            if($action=='add'){
                $voucher_no     = 'PO-'.getMAXID('ah_material_purchase', 'id');
                
                $isExist = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('ah_material_purchase', array('voucher_no'=>$voucher_no) );
                //$isExist2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('nameA'=>$this->request->getVar('nameA')));
                if( !empty($isExist)  ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    $data['voucher_no']     = $voucher_no;
                    $data['date']           = $this->date_db_format($this->request->getVar('date'));
                    $data['created_by']     = session('id');
                    $data['created_at']     = date('Y-m-d H:i:s');

                    $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert('ah_material_purchase',$data);

                    $item_id    = $this->request->getVar('item_id');
                    $carton     = $this->request->getVar('carton');
                    $carton_qty = $this->request->getVar('carton_qty');
                    $box_qty    = $this->request->getVar('box_qty');
                    $box        = $this->request->getVar('box');
                    $qty        = $this->request->getVar('qty');
                    $price      = $this->request->getVar('price');
                    $org_price  = $this->request->getVar('org_price');
                    $total      = $this->request->getVar('total');
                    $vat_applicable= $this->request->getVar('vat_applicable');

                    $data2 = array();
                    //$approve = 1;
                    foreach($item_id as $key => $item){
                        $data2[] = array('purchase_id'=> $result,'item_id'=> $item, 'carton'=> $carton[$key], 'carton_qty'=> $carton_qty[$key], 'box_qty'=> $box_qty[$key], 'box'=> $box[$key], 'qty'=> $qty[$key], 'price'=> $price[$key], 'total'=> $total[$key], 'vat_applicable'=> $vat_applicable[$key] );

                        /*if($price[$key] > $org_price[$key]){
                            $approve = 0;
                        }*/
                    }

                    $result2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert_Batch('ah_material_purchase_details',$data2);

                    // Store log data
                    $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['created']), $result, 'ah_material_purchase');
                    /*if($approve){
                        $data3 = array();
                        $data3['isApproved']       = 1;
                        $data3['approved_by']      = session('id');
                        $data3['approved_date']    = date('Y-m-d H:i:s');

                        $result3 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('ah_material_purchase',$data3, array('id'=>$result));
                    }*/
                    /*$store_id = $this->request->getVar('store_id');
                    foreach($item_id as $key => $item){
                        $where = array('store_id'=> $store_id,'item_id'=> $item );
                        $affectedRows = $this->bdtaskt1m12c10_01_purchase_orderModel->bdtaskt1m12_04_updateStock($qty[$key], $where);
                        if( $affectedRows ==0 ){
                            $data3 = array('store_id'=> $store_id,'item_id'=> $item, 'stock'=> $qty[$key] );
                            $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert('wh_production_stock',$data3);
                        }
                    }*/

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

                $data['updated_by']     = session('id');
                $data['updated_at']     = date('Y-m-d H:i:s');

                $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('ah_material_purchase',$data, array('id'=>$id));

                $item_id    = $this->request->getVar('item_id');
                $carton     = $this->request->getVar('carton');
                $carton_qty = $this->request->getVar('carton_qty');
                $box_qty    = $this->request->getVar('box_qty');
                $box        = $this->request->getVar('box');
                $qty        = $this->request->getVar('qty');
                $price      = $this->request->getVar('price');
                $org_price  = $this->request->getVar('org_price');
                $total      = $this->request->getVar('total');
                $vat_applicable= $this->request->getVar('vat_applicable');

                $data2 = array();
                //$approve = 1;
                foreach($item_id as $key => $item){
                    $data2[] = array('purchase_id'=> $id,'item_id'=> $item, 'carton'=> $carton[$key], 'carton_qty'=> $carton_qty[$key], 'box_qty'=> $box_qty[$key], 'box'=> $box[$key], 'qty'=> $qty[$key], 'price'=> $price[$key], 'total'=> $total[$key], 'vat_applicable'=> $vat_applicable[$key] );

                    /*if($price[$key] > $org_price[$key]){
                        $approve = 0;
                    }*/
                }

                $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('ah_material_purchase_details', array('purchase_id'=>$id));
                $result2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert_Batch('ah_material_purchase_details',$data2);

                /*if($approve){
                    $data3 = array();
                    $data3['isApproved']       = 1;
                    $data3['approved_by']      = session('id');
                    $data3['approved_date']    = date('Y-m-d H:i:s');

                    $result3 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('wh_material_purchase',$data3, array('id'=>$id));
                }*/
                // Store log data
                $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['updated']), $id, 'wh_material_purchase');

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

    /* Approve purchase order*/
    public function bdtaskt1m12c10_07_approvePurchaseOrder(){

        $id = $this->request->getVar('id');
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('ah_material_purchase', array('id'=>$id, 'isApproved'=>1));

        $MesTitle = get_phrases(['purchase', 'record']);
        if(!empty($data)){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['already', 'approved']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }
        
        $data = array();
        $data['isApproved']       = 1;
        $data['approved_by']      = session('id');
        $data['approved_date']    = date('Y-m-d H:i:s');

        $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('ah_material_purchase',$data, array('id'=>$id));
        
        // Store log data
        $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['approved']), $id, 'ah_material_purchase');

        $MesTitle = get_phrases(['purchase', 'record']);

        if($result){
             $response = array(
                'success'  =>true,
                'message'  => get_phrases(['approved', 'successfully']),
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
    | Get purchase_order by ID
    *--------------------------*/
    public function bdtaskt1m12c10_04_getPurchaseOrderById($id)
    { 
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('ah_material', array('id'=>$id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get item details by ID
    *--------------------------*/
    public function bdtaskt1m12c10_05_getPurchaseOrderDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c10_01_purchase_orderModel->bdtaskt1m12_03_getPurchaseOrderDetailsById($id);
        echo json_encode($data);
    }

    
    /*--------------------------
    | Get supplier item details by ID
    *--------------------------*/
    public function bdtaskt1m12c10_09_getSupplierItemDetailsById()
    { 
        $item_id = $this->request->getVar('item_id');
        $supplier_id = $this->request->getVar('supplier_id');

        $data = $this->bdtaskt1m12c10_01_purchase_orderModel->bdtaskt1m12_06_getSupplierItemDetailsById($item_id, $supplier_id);

        $store_list       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('ah_material_store', array('status'=>1,'branch_id'=>session('branchId')));
        $store_id       = ($store_list)?$store_list->id:0;

        $data['main_stock'] = 0;
        $data['sub_stock'] = 0;
        if( $store_id >0 ){
            $data['main_stock'] = $this->bdtaskt1m12c10_01_purchase_orderModel->bdtaskt1m12_08_getWarehouseItemStock($item_id, $store_id);
            $data['sub_stock'] = $this->bdtaskt1m12c10_01_purchase_orderModel->bdtaskt1m12_09_getSubWarehouseItemStock($item_id, session('branchId'));
        }

        echo json_encode($data);
    }

   
    /*--------------------------
    | Get item list by supplier ID
    *--------------------------*/
    public function bdtaskt1m12c10_08_get_item_list()
    { 
        $supplier_id = $this->request->getVar('supplier_id');
        $item_list = $this->bdtaskt1m12c10_01_purchase_orderModel->bdtaskt1m12_05_get_item_list($supplier_id);
        $html = '<option value="">Select</option>';
        foreach($item_list as $items){
            $html .='<option value="'.$items->id.'">'.$items->nameE.'</option>';
        }
        echo $html;
   }
    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c10_06_getPurchaseOrderPricingDetailsById()
    { 
        $purchase_id = $this->request->getVar('purchase_id');

        $html = '<table class="table table-bordered w-100"><tr><th>'.get_phrases(['SL']).'</th><th>'.get_phrases(['item']).'</th><th class="text-right">'.get_phrases(['request','quantity']).'</th><th class="text-center">'.get_phrases(['unit']).'</th><th class="text-right">'.get_phrases(['purchase', 'quantity']).'</th><th  class="text-right">'.get_phrases(['purchase', 'price']).'</th><th  class="text-right">'.get_phrases(['subtotal']).'</th></tr>';

        $purchases_details = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('ah_material_purchase_details', array('purchase_id'=>$purchase_id));

        $sl = 0;
        foreach($purchases_details as $details)
        {
            $sl++;
            $item_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('ah_material', array('id'=>$details->item_id));            
            $unit_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
            
            $html .= '<tr>
                        <td width="5%">'.$sl.'</td>
                        <td width="15%">'.$item_row->nameE.' ('.$item_row->item_code.')</td>
                        <td width="10%" align="right">'.number_format(($details)?$details->requested_qty:0, 2).'</td>
                        <td width="5%" align="center">'.(($unit_row)?$unit_row->nameE:'').'</td>
                        <td width="10%" align="right">'.number_format(($details)?$details->qty:0, 2).'</td>
                        <td width="10%" align="right">'.number_format(($details)?$details->price:0,2).'</td>
                        <td width="10%" align="right">'.number_format(($details)?($details->qty*$details->price):0,2).'</td>
                    </tr>';
        }
        $html .= '</table>';

        echo $html;
    }

    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c10_07_getPurchaseOrderItemDetailsById()
    { 
        $purchase_id = $this->request->getVar('purchase_id');
        $supplier_id = $this->request->getVar('supplier_id');

        $data = $this->bdtaskt1m12c10_01_purchase_orderModel->bdtaskt1m12_07_getPurchaseOrderItemDetailsById($purchase_id, $supplier_id);
        echo json_encode($data);
    }
   
}
