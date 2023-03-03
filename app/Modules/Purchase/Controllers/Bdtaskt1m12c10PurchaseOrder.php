<?php namespace App\Modules\Purchase\Controllers;
use App\Modules\Purchase\Views;
use CodeIgniter\Controller;
use App\Modules\Purchase\Models\Bdtaskt1m12PurchaseOrderModel;
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
        $data['moduleTitle']        = get_phrases(['local','purchase']);
        $data['isDTables']          = true;
        $data['summernote']         = true;
        $data['module']             = "Purchase";
        $data['page']               = "purchase_order/list";
        $data['setting']            = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('setting');

        $data['hasCreateAccess']        = $this->permission->method('wh_material_purchase', 'create')->access();
        $data['hasPrintAccess']        = $this->permission->method('wh_material_purchase', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('wh_material_purchase', 'export')->access();
        
        $data['supplier_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_supplier_information', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material', array('status'=>1));
       
        $data['store_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_store', array('status'=>1));
        $data['all_po']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_purchase', array('status'=>1));
        $data['all_spr']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_requisition', array('status'=>1));
        $data['requisition_list']    = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_requisition', array('received !='=>1, 'isApproved'=>1));
        
        
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
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material_purchase', array('id'=>$id, 'isApproved'=>1));

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
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('wh_material_purchase', array('id'=>$id));
        $data2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('wh_material_purchase_details', array('purchase_id'=>$id));

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
        // print_r($_POST);exit;
        $voucher_no = 'SAAF/Purchase/'.date("Y").'-'.getMAXID('wh_material_purchase', 'id');
        $action     = $this->request->getVar('action');
        $remain_po_quantity    = $this->request->getVar('remain_po_quantity');
        $item_id    = $this->request->getVar('spr_item_list');
        $qty        = $this->request->getVar('po_quantity');
        $quatation_id = $this->request->getVar('quatation_id');
        $spr_qty    = $this->request->getVar('spr_qty_'.$quatation_id);

        $data = array( 
            'quatation_id'      => $quatation_id, 
            'requisition_id'    => $this->request->getVar('requisition_id'), 
            'date'              => $this->request->getVar('date'), 
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'date'      => 'required',
                'quatation_id'   => 'required',
                'po_quantity'   => 'required',
            ];
        }
        $MesTitle = get_phrases(['purchase', 'record']);
        if ($qty > $remain_po_quantity) {
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['invalid', 'quantity']),
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;
        }
        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            if($action=='add'){
                if( empty($data['quatation_id']) && empty($data['requisition_id']) ){
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['invalid', 'request']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);
                    exit;
                }
                
                if( $qty > $spr_qty ){
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['invalid', 'quantity']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);
                    exit;
                }
                $quatation_details = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material_quatation_details', array('quatation_id'=>$data['quatation_id'], 'item_id'=>$item_id, 'requisition_id'=>$data['requisition_id'] ) );
                if ($quatation_details) {
                    $data['item_id']        = $item_id;
                    $data['po_qty']         = $qty;
                    $data['supplier_id'] = $quatation_details->supplier_id;
                    $unit_price = $quatation_details->price;
                    $vat_applicable = $quatation_details->vat_applicable;
                    $data['sub_total'] = $unit_price * $qty;
                    if ($vat_applicable == 1 && get_setting('default_vat') > 0 ) {
                        $data['vat'] = $data['sub_total'] * get_setting('default_vat')/100;
                    }else{
                        $data['vat'] = 0;
                    }
                    $data['grand_total'] = $data['vat'] + $data['sub_total'];
                }else{
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['invalid', 'quatation']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);
                    exit;
                }
                $isExist = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material_purchase', array('voucher_no'=>$voucher_no) );
                if( !empty($isExist)  ){
                    $response = array(
                        'success'  =>'exist',
                        'message'  => get_phrases(['voucher', 'already', 'exists']),
                        'title'    => $MesTitle
                    );
                }else{
                    $data['voucher_no']     = $voucher_no;
                    $data['date']           = $this->date_db_format($this->request->getVar('date'));
                    $data['created_by']     = session('id');
                    $data['created_at']     = date('Y-m-d H:i:s');

                    $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert('wh_material_purchase',$data);

                    //details array
                    $data2 = array(
                        'purchase_id'=> $result,
                        'item_id'=> $item_id,
                        'requisition_id'=> $data['requisition_id'],
                        'qty'=> $qty,
                        'price'=> $unit_price,
                        'total'=> $qty * $unit_price,
                        'vat_applicable'=> $vat_applicable,
                        'vat_amount'=> $data['vat'],
                        'requested_qty'=> $spr_qty,
                    );

                    $result2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert('wh_material_purchase_details',$data2);

                    // Store log data
                    $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['created']), $result, 'wh_material_purchase');
                    
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
            } elseif($action=='update'){
                $data['updated_by']     = session('id');
                $data['updated_at']     = date('Y-m-d H:i:s');
                $id = $this->request->getVar('id');

                $po_info = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material_purchase', array('id'=>$id) );
                $spr_id = $po_info->requisition_id;
                $item_id = $po_info->item_id;
                $data['date'] = $po_info->date;
                if( empty($data['quatation_id']) && empty($spr_id) ){
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['invalid', 'request']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);
                    exit;
                }
                
                if( $qty > $spr_qty ){
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['invalid', 'quantity']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);
                    exit;
                }
                $quatation_details = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material_quatation_details', array('quatation_id'=>$data['quatation_id'], 'item_id'=>$item_id, 'requisition_id'=>$spr_id ) );
                if ($quatation_details) {
                    $data['po_qty']         = $qty;
                    $data['requisition_id'] = $spr_id;
                    $data['supplier_id'] = $quatation_details->supplier_id;
                    $unit_price = $quatation_details->price;
                    $vat_applicable = $quatation_details->vat_applicable;
                    $data['sub_total'] = $unit_price * $qty;
                    if ($vat_applicable == 1 && get_setting('default_vat') > 0 ) {
                        $data['vat'] = $data['sub_total'] * get_setting('default_vat')/100;
                    }else{
                        $data['vat'] = 0;
                    }
                    $data['grand_total'] = $data['vat'] + $data['sub_total'];
                }else{
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['invalid', 'quatation']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);
                    exit;
                }
                
                
                $isExist = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material_purchase', array('voucher_no'=>$voucher_no) );
                if( !empty($isExist)  ){
                    $response = array(
                        'success'  =>'exist',
                        'message'  => get_phrases(['voucher', 'already', 'exists']),
                        'title'    => $MesTitle
                    );
                }else{
                    $data['updated_by']     = session('id');
                    $data['updated_at']     = date('Y-m-d H:i:s');
                    $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('wh_material_purchase',$data, array('id'=>$id));

                    //details array
                    $data2 = array(
                        'purchase_id'=> $id,
                        'item_id'=> $item_id,
                        'requisition_id'=> $spr_id,
                        'qty'=> $qty,
                        'price'=> $unit_price,
                        'total'=> $qty * $unit_price,
                        'vat_applicable'=> $vat_applicable,
                        'vat_amount'=> $data['vat'],
                        'requested_qty'=> $spr_qty,
                    );
                    $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('wh_material_purchase_details', array('purchase_id'=>$id));
                    $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert('wh_material_purchase_details',$data2);

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
            }else{
                print_r($_POST);exit;
            }
            
        }
        
        echo json_encode($response);
        exit;
    }

    /* Approve purchase order*/
    public function bdtaskt1m12c10_07_approvePurchaseOrder(){

        $id = $this->request->getVar('id');//PO
        $item_id = $this->request->getVar('item_id');
        $item_qty = $this->request->getVar('item_qty');
        if (count($item_id) == 1) {
            $item = $item_id[0];
            $item_q = $item_qty[0];
        }else{
            print_r($item_id);
            exit;
        }
        $info = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material_purchase', array('id'=>$id));
        $quatation_id = $info->quatation_id;
        $spr_id = $info->requisition_id;
        $spr_details_info = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material_requisition_details', array('purchase_id'=>$spr_id, 'item_id'=>$item));
        
        $MesTitle = get_phrases(['purchase', 'record']);
        if($info->isApproved == 1){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['already', 'approved']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }

        if(($spr_details_info->po_qty + $item_q) > $spr_details_info->qty){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['maximum', 'quantity', 'received']),
                'title'    => $MesTitle,
            );
            echo json_encode($response);
            exit;
        }
        $terms_conditions = $this->request->getVar('terms_conditions');//PO
        if(empty($terms_conditions)){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['terms', 'conditions', 'required']),
                'title'    => $MesTitle,
            );
            echo json_encode($response);
            exit;
        }

        $terms_conditions_data = array(
            'po_id'=> $id,
            'terms_conditions'=> $terms_conditions,
            'status'=> 1,
        );
        $rstc = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert('wh_material_terms_conditions',$terms_conditions_data);
        
        $data = array();
        $data['terms_conditions_id'] = $rstc;
        $data['isApproved']       = 1;
        $data['approved_by']      = session('id');
        $data['approved_date']    = date('Y-m-d H:i:s');

        
        $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('wh_material_purchase',$data, array('id'=>$id));
        if ($result) {
            $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_UpdateSet('wh_material_requisition_details', 'po_qty', $item_q, array('purchase_id'=>$spr_id, 'item_id'=>$item));
            $remain_qty = db_connect()->table('wh_material_requisition_details')->select('SUM(qty - po_qty) as remain_qty')->where('purchase_id',$spr_id)->get()->getRowArray();
            if(($spr_details_info->po_qty + $item_q) == $spr_details_info->qty){
                if ($remain_qty['remain_qty'] == $item_q) {
                    $received = 1;
                }else{
                    $received = 2;
                }
            }else{
                $received = 2;
            }
            $spr_data = array(
                'received' => $received,
                'approved_by' => session('id'),
                'approved_date' => date('Y-m-d H:i:s'),
            );
            $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('wh_material_requisition',$spr_data, array('id'=>$spr_id));
            $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('wh_quatation',array('isApproved'=>1), array('id'=>$quatation_id));
            $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_UpdateSet('wh_material_quatation_details', 'received_qty', $item_q, array('quatation_id'=>$quatation_id, 'item_id'=>$item));
            
        }
        // Store log data
        $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['approved']), $id, 'wh_material_purchase');

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
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=>$id));
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

        $store_list       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material_store', array('status'=>1,'branch_id'=>session('branchId')));
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

        $html = '<table class="table table-bordered w-100"><tr><th>'.get_phrases(['SL']).'</th><th>'.get_phrases(['raw', 'material', 'name']).'</th><th class="text-right">'.get_phrases(['request','quantity']).'</th><th class="text-center">'.get_phrases(['unit']).'</th><th class="text-right">'.get_phrases(['purchase', 'quantity']).'</th><th  class="text-right">'.get_phrases(['purchase', 'price']).'</th><th  class="text-right">'.get_phrases(['subtotal']).'</th></tr>';

        $purchases_details = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_purchase_details', array('purchase_id'=>$purchase_id));

        $sl = 0;
        foreach($purchases_details as $details)
        {
            $sl++;
            $item_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=>$details->item_id));            
            $unit_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
            
            $html .= '<tr>
                        <td width="5%">'.$sl.'</td>
                        <td width="15%">'.$item_row->nameE.' ('.$item_row->item_code.')</td>
                        <td width="10%" align="right">'.number_format(($details)?$details->requested_qty:0, 2).'</td>
                        <td width="5%" align="center">'.(($unit_row)?$unit_row->nameE:'').'</td>
                        <td width="10%" align="right">'.number_format(($details)?$details->qty:0, 2).'</td>
                        <td width="10%" align="right">'.number_format(($details)?$details->price:0,2).'</td>
                        <td width="10%" align="right">'.number_format(($details)?($details->qty*$details->price):0,2).'</td>
                        <input type="hidden" name="item_id[]" id="item_id'.$sl.'" value="'.$details->item_id.'">
                        <input type="hidden" name="item_qty[]" id="item_qty'.$sl.'" value="'.$details->qty.'">
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

    public function bdtaskt1m12c10_05_get_spr_item_list()
    { 
        $requisition_id = $this->request->getVar('requisition_id');
        $data = $this->bdtaskt1m12c10_01_purchase_orderModel->bdtaskt1m12_05_get_item_select2($requisition_id);
        echo json_encode($data);
    }

    public function bdtaskt1m12c10_06_get_po_info()
    { 
        $po_id = $this->request->getVar('po_id');
        $data = $this->bdtaskt1m12c10_01_purchase_orderModel->bdtaskt1m12_06_get_po_info($po_id);
        $num = $data['total_price'];
        if ($num > 0) {
            $data['amount_in_word'] = ucwords(strtolower(numberToWords($num)));
        }else{
            $data['amount_in_word'] = '';
        }
        echo json_encode($data);
    }

    public function bdtaskt1m12c10_06_get_quatation_list()
    { 
        $requisition_id = $this->request->getVar('requisition_id');
        $item_id = $this->request->getVar('item_id');
        $requisition_details = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material_requisition_details', array('item_id'=>$item_id, 'purchase_id'=>$requisition_id));
        $quatation_details   = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_quatation_details', array('item_id'=>$item_id, 'requisition_id '=>$requisition_id));
        
        $html = '<table class="table table-bordered w-100"><tr><th>'.get_phrases(['SL']).'</th><th class="text-right">'.get_phrases(['quotation', 'ID']).'</th><th class="text-center">'.get_phrases(['supplier','/', 'party']).'</th><th class="text-right">'.get_phrases(['unit','price']).'</th><th class="text-right">'.get_phrases(['available','quantity']).'</th><th>'.get_phrases(['select','quatation']).'</th></tr>';

        $sl = 0;
        if ($quatation_details) {
            foreach($quatation_details as $details)
            {
                $sl++;
                $qty = $details->qty - $details->received_qty;
                $supplier_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_supplier_information', array('id'=>$details->supplier_id));  
                $html .= '<tr>
                            <td width="5%">'.$sl.'</td>
                            <td width="5%" align="right">'.$details->quatation_id.'</td>
                            <td width="10%" align="center">'.$supplier_row->nameE.' ( '.$supplier_row->code_no.' ) </td>
                            <td width="5%" align="right">'.$details->price.'</td>
                            <td width="5%" align="right">'.$qty.'</td>
                            <td width="5%"> <label class="input_container"><input type="radio" onclick="radioBtn_Click('.$details->quatation_id.');" name="quatation_id" id="quatation_id'.$details->quatation_id.'" required value="'.$details->quatation_id.'"><span class="checkmark"></span></label> </td>
                            
                            <input type="hidden" name="spr_qty_'.$details->quatation_id.'" id="spr_qty'.$details->quatation_id.'" value="'.$qty.'">
                            </tr>';
            }
            $html .= '</table>';
            //quantity show
            $html .="<script>$('#spr_quantity').text(".$requisition_details->qty.");$('#purchased_quantity').text(".$requisition_details->po_qty.");$('#remain_quantity').text(".($requisition_details->qty-$requisition_details->po_qty).");$('#remain_po_quantity').val(".($requisition_details->qty-$requisition_details->po_qty).");</script>";

            //checked radio in update modal
            $id = $this->request->getVar('po_id');
            if (!empty($id)) {
                $po_info = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material_purchase', array('id'=>$id) );
                $quatation_id = $po_info->quatation_id;
                $html .='<script>$(document).ready(function(){$("#quatation_id'.$quatation_id.'").prop("checked", true);});</script>';
            }
    
            echo $html;
        } else {
            $html .= '<tr><td colspan="6" align="center">No Data Found</td></tr>';
            $html .= '</table>';
    
            echo $html;
        }
    }
    
    public function bdtaskt1m12c10_13_select_spr()
    { 
        $column = ["id, CONCAT(voucher_no) as text"];
        $data     = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_07_getSelect2Data('wh_material_requisition', array('received !='=>1, 'isApproved'=>1), $column);
        echo json_encode($data); 
        
    }
   
}
