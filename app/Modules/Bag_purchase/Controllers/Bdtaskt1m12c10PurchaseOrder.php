<?php namespace App\Modules\Bag_purchase\Controllers;
use App\Modules\Bag_purchase\Views;
use CodeIgniter\Controller;
use App\Modules\Bag_purchase\Models\Bdtaskt1m12PurchaseOrderModel;
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
        $data['moduleTitle']        = get_phrases(['bag', 'purchase']);
        $data['title']              = get_phrases(['purchase','order']);
        $data['isDTables']          = true;
        $data['summernote']         = true;
        $data['module']             = "Bag_purchase";
        $data['page']               = "purchase_order/list";
        $data['setting']    = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('setting');

        $data['hasCreateAccess']        = $this->permission->method('wh_bag_purchase', 'create')->access();
        $data['hasPrintAccess']        = $this->permission->method('wh_bag_purchase', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('wh_bag_purchase', 'export')->access();
        
        $data['supplier_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_supplier_information', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag', array('status'=>1));
       
        $data['store_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_store', array('status'=>1));
        $data['requisition_list']    = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_requisition', array('received !='=>1, 'isApproved'=>1, 'type !='=>2), null, null, array('id', 'desc'));
        $data['all_spr']    = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_requisition', array('isApproved'=>1), null, null, array('id', 'desc'));
        $data['all_po']    = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_purchase', array('isApproved'=>1), null, null, array('id', 'desc'));
        
        
        $vat       = get_setting('default_vat');
        
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    public function test()
    {
        $data['moduleTitle']        = get_phrases(['bag', 'purchase']);
        $data['title']              = get_phrases(['purchase','order']);
        $data['isDTables']          = true;
        $data['module']             = "Bag_purchase";
        $data['page']               = "purchase_order/print_view";

        
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
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_purchase', array('id'=>$id, 'isApproved'=>1));

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
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('wh_bag_purchase', array('id'=>$id));
        $data2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('wh_bag_purchase_details', array('purchase_id'=>$id));

        // Store log data
        $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['deleted']), $id, 'wh_bag_purchase');
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

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'date'      => 'required',
                'spr_item_list'   => 'required',
                'requisition_id'   => 'required',
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
            // print_r($_POST);exit;
            $voucher_no = 'SAAF/Purchase/'.date("Y").'-'.getMAXID('wh_bag_purchase', 'id');
            $action     = $this->request->getVar('action');
            $item_id    = $this->request->getVar('spr_item_list');
            $qty        = $this->request->getVar('po_quantity');
            $requisition_id = $this->request->getVar('requisition_id');
            $quatation_id = $this->request->getVar('spr_item_list');
            $spr_qty    = $this->request->getVar('spr_qty_'.$quatation_id);
            $date = $this->date_db_format($this->request->getVar('date'));

            if($action=='add'){
                if( empty($quatation_id) && empty($requisition_id) ){
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['invalid', 'request']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);
                    exit;
                }
                
                $bag_quatation = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_quatation', array('id'=>$quatation_id, 'requisition_id'=>$requisition_id ) );
                if ($bag_quatation) {
                    $supplier_id = $bag_quatation->supplier_id;
                    $sub_total = $bag_quatation->sub_total;
                    $vat = $bag_quatation->vat;
                    $grand_total = $bag_quatation->grand_total;
                }else{
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['invalid', 'quatation']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);
                    exit;
                }
                $isExist = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_purchase', array('voucher_no'=>$voucher_no) );
                if( !empty($isExist)  ){
                    $response = array(
                        'success'  =>'exist',
                        'message'  => get_phrases(['voucher', 'already', 'exists']),
                        'title'    => $MesTitle
                    );
                }else{
                    $po_data = array(
                        'quatation_id' => $quatation_id,
                        'requisition_id' => $requisition_id,
                        'supplier_id' => $supplier_id,
                        'voucher_no' => $voucher_no,
                        'date' => $date,
                        'sub_total' => $sub_total,
                        'vat' => $vat,
                        'grand_total' => $grand_total,
                        'created_by' => session('id'),
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                    $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert('wh_bag_purchase', $po_data);

                    //details array
                    $data2 = array();
                    $quatation_details   = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_quatation_details', array('quatation_id'=>$quatation_id, 'requisition_id '=>$requisition_id));
                    foreach($quatation_details as $item){
                        $data2[] = array('purchase_id'=> $result, 'item_id'=> $item->item_id, 'qty'=> $item->qty, 'price'=> $item->price, 'total'=> $item->total, 'vat_applicable'=> $item->vat_applicable, 'vat_amount'=> $item->vat_amount );

                    }
                    $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_bag_purchase_details',$data2);


                    // Store log data
                    $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['created']), $result, 'wh_bag_purchase');
                    
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

                $po_info = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_purchase', array('id'=>$id) );
                $spr_id = $po_info->requisition_id;
                $item_id = $po_info->item_id;
                $date = $po_info->date;
                if( empty($quatation_id) && empty($spr_id) ){
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
                $bag_quatation = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_quatation_details', array('quatation_id'=>$quatation_id, 'item_id'=>$item_id, 'requisition_id'=>$spr_id ) );
                if ($bag_quatation) {
                    $data['po_qty']         = $qty;
                    $requisition_id = $spr_id;
                    $supplier_id = $bag_quatation->supplier_id;
                    $unit_price = $bag_quatation->price;
                    $vat_applicable = $bag_quatation->vat_applicable;
                    $sub_total = $unit_price * $qty;
                    if ($vat_applicable == 1 && get_setting('default_vat') > 0 ) {
                        $vat = $sub_total * get_setting('default_vat')/100;
                    }else{
                        $vat = 0;
                    }
                    $grand_total = $vat + $sub_total;
                }else{
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['invalid', 'quatation']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);
                    exit;
                }
                
                
                $isExist = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_purchase', array('voucher_no'=>$voucher_no) );
                if( !empty($isExist)  ){
                    $response = array(
                        'success'  =>'exist',
                        'message'  => get_phrases(['voucher', 'already', 'exists']),
                        'title'    => $MesTitle
                    );
                }else{
                    $data['updated_by']     = session('id');
                    $data['updated_at']     = date('Y-m-d H:i:s');
                    $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('wh_bag_purchase',$data, array('id'=>$id));

                    //details array
                    $data2 = array(
                        'purchase_id'=> $id,
                        'item_id'=> $item_id,
                        'requisition_id'=> $spr_id,
                        'qty'=> $qty,
                        'price'=> $unit_price,
                        'total'=> $qty * $unit_price,
                        'vat_applicable'=> $vat_applicable,
                        'vat_amount'=> $vat,
                        'requested_qty'=> $spr_qty,
                    );
                    $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('wh_bag_purchase_details', array('purchase_id'=>$id));
                    $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert('wh_bag_purchase_details',$data2);

                    // Store log data
                    $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['updated']), $id, 'wh_bag_purchase');
                    
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

    /*--------------------------
    | Get purchase_order by ID
    *--------------------------*/
    public function bdtaskt1m12c10_04_getPurchaseOrderById($id)
    { 
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_bag', array('id'=>$id));
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
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c10_06_getPurchaseOrderPricingDetailsById()
    { 
        $purchase_id = $this->request->getVar('purchase_id');

        $html = '<table class="table table-bordered w-100"><tr><th>'.get_phrases(['SL']).'</th><th>'.get_phrases(['item']).'</th><th class="text-right">'.get_phrases(['request','quantity']).'</th><th class="text-center">'.get_phrases(['unit']).'</th><th class="text-right">'.get_phrases(['purchase', 'quantity']).'</th><th  class="text-right">'.get_phrases(['purchase', 'price']).'</th><th  class="text-right">'.get_phrases(['subtotal']).'</th></tr>';

        $purchases_details = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_purchase_details', array('purchase_id'=>$purchase_id));

        $sl = 0;
        foreach($purchases_details as $details)
        {
            $sl++;
            $item_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_bag', array('id'=>$details->item_id));            
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


    /* Approve purchase order*/
    public function bdtaskt1m12c10_07_approvePurchaseOrder(){

        $id = $this->request->getVar('id');//PO
        
        $info = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_purchase', array('id'=>$id));
        $quatation_id = $info->quatation_id;
        $spr_id = $info->requisition_id;
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
            'terms_conditions'=> $terms_conditions,
            'status'=> 1,
        );
        $rstc = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert('wh_material_terms_conditions',$terms_conditions_data);
        
        $data = array();
        $data['terms_conditions_id'] = $rstc;
        $data['isApproved']       = 1;
        $data['approved_by']      = session('id');
        $data['approved_date']    = date('Y-m-d H:i:s');

        
        $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('wh_bag_purchase',$data, array('id'=>$id));
        if ($result) {
            
            $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('wh_bag_requisition',array('received'=>1), array('id'=>$spr_id));
            $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('wh_bag_quatation',array('isApproved'=>1), array('id'=>$quatation_id));
            
        }
        // Store log data
        $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['approved']), $id, 'wh_bag_purchase');


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
        exit;
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
    | Get supplier item details by ID
    *--------------------------*/
    public function bdtaskt1m12c10_09_getSupplierItemDetailsById()
    { 
        $item_id = $this->request->getVar('item_id');
        $supplier_id = $this->request->getVar('supplier_id');

        $data = $this->bdtaskt1m12c10_01_purchase_orderModel->bdtaskt1m12_06_getSupplierItemDetailsById($item_id, $supplier_id);

        $store_list       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_store', array('status'=>1));
        $store_id       = ($store_list)?$store_list->id:0;

        $data['main_stock'] = 0;
        $data['sub_stock'] = 0;
        if( $store_id >0 ){
            $data['main_stock'] = $this->bdtaskt1m12c10_01_purchase_orderModel->bdtaskt1m12_08_getWarehouseItemStock($item_id, $store_id);
            $data['sub_stock'] = $this->bdtaskt1m12c10_01_purchase_orderModel->bdtaskt1m12_09_getSubWarehouseItemStock($item_id, session('branchId'));
        }

        echo json_encode($data);
    }

    public function bdtaskt1m12c10_10_getPrintView()
    {
        //action_id
        $purchase_id = $this->request->getVar('action_id');
        $purchase_info = $this->bdtaskt1m12c10_01_purchase_orderModel->bdtaskt1m12_03_getPurchaseOrderDetailsById($purchase_id);
        //{item_table}
        $html = '<table class="table table-bordered w-100"><tr class="text-center"><th>ক্রম নং</th><th>ওভেন ব্যাগের স্পেসিফিকেশন ও সাইজ</th><th>ব্যাগের ওজন(গ্রাম)</th><th>লাইনারের ওজন(গ্রাম)</th><th>ব্যাগের সর্বমােট ওজন(গ্রাম)</th><th>পরিমাণ (পিস) ও মোট ওজন কেজি</th><th>একক ও মােট মূল্য (টাকা)</th></tr>';
        $purchases_details = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_purchase_details', array('purchase_id'=>$purchase_id));
        $sl = 0;
        foreach($purchases_details as $details)
        {
            $sl++;
            $item_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_bag', array('id'=>$details->item_id)); 
            if (!empty($item_row)) {
                $specification = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->specification)); 
                // $unit_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
                $bag_weight = $item_row->bag_weight + $item_row->liner_weight;
                $bag_qty = $details->qty;
                $bag_price = $details->price;
                $html .= '<tr>
                        <td width="5%">'.$sl.'</td>
                        <td width="20%">'.$item_row->nameE.' '.$specification->nameE.' Bag Size:'.$item_row->bag_size.' Liner Size:'.$item_row->liner_size.'</td>
                        <td width="10%" align="right">'.number_format($item_row->bag_weight, 2).'</td>
                        <td width="5%" align="center">'.number_format($item_row->liner_weight, 2).'</td>
                        <td width="10%" align="right">'.number_format(($bag_weight)?$bag_weight:0, 2).'(±5)</td>
                        <td width="10%" align="right">'.number_format($bag_qty,2).' & '.number_format((($bag_weight*$bag_qty)/1000),2).'</td>
                        <td width="10%" align="right">'.number_format($bag_price,2).' & '.number_format(($bag_price*$bag_qty),2).'</td>
                    </tr>';
            }else{
                $html .='<tr></tr>';
            }      
            
        }
        $html .= '</table>';
        if (!empty($purchase_info['signature'])) {
            $img = '<img class="img-thambnail" src="'.base_url().$purchase_info['signature'].'" height="70px" width="70px"/>';
        }else{
            $img = '';
        }
        $template_data = [
            'supplier_name' => $purchase_info['supplier_name'],
            'supplier_address' => $purchase_info['supplier_address'],
            'signature' => $img,
            'item_table' => $html,
            'terms_conditions' => $purchase_info['terms_conditions'],
        ];
        
        $data = [];
        $record = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_template', array('template_name'=>'bagPOPrintView', 'isApproved'=>1));
        if (!empty($record)) {
            $data['template'] = htmlspecialchars_decode($this->parser->setData($template_data)->render($record->template_path));
            $data['template_header'] = $record->template_header;
            $data['template_footer'] = $record->template_footer;
        }
        echo json_encode($data);
    }

    public function bdtaskt1m12c10_11_get_quatation_list()
    { 
        $requisition_id = $this->request->getVar('requisition_id');
        $item_id = $this->request->getVar('item_id');
        $bag_quatation = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_quatation', array('id'=>$item_id));
        $quatation_details   = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_quatation_details', array('quatation_id'=>$item_id, 'requisition_id '=>$requisition_id));
        
        $html = '<table class="table table-bordered w-100"><tr><th>'.get_phrases(['SL']).'</th><th class="text-center">'.get_phrases(['bag','name']).'</th><th class="text-right">'.get_phrases(['unit','price']).'</th><th class="text-right">'.get_phrases(['quantity']).'</th><th class="text-right">'.get_phrases(['total']).'</th></tr>';

        $sl = 0;
        if ($quatation_details) {
            $supplier_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_supplier_information', array('id'=>$bag_quatation->supplier_id));  
            $supplier_name = $supplier_row->nameE.' ( '.$supplier_row->code_no.' )';
            foreach($quatation_details as $details)
            {
                $wh_bag = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_bag', array('id'=>$details->item_id));  
                $bag_name = $wh_bag->nameE;
                $sl++;
                $html .= '<tr>
                            <td width="5%">'.$sl.'</td>
                            <td width="10%" align="center">'.$bag_name.'</td>
                            <td width="5%" align="right">'.$details->price.'</td>
                            <td width="5%" align="right">'.$details->qty.'</td>
                            <td width="5%" align="right">'.$details->total.'</td>

                        </tr>';
            }
            $html .= '</table>';
            //info show
            $html .="<script>$('#quatation_details_sub_total').text('".$bag_quatation->sub_total."');$('#quatation_details_vat').text('".$bag_quatation->vat."');$('#quatation_details_grand_total').text('".$bag_quatation->grand_total."');</script>";
            $html .="<script>$('#quotation_supplier').text('".$supplier_name."');$('#quotation_date').text('".$bag_quatation->date."');$('#quotation_remarks').text('".$bag_quatation->remarks."');</script>";
            $filelink = '<a href=" '.base_url().$bag_quatation->file.' " target="_blank" rel="noopener noreferrer" class="btn btn-primary"><i class="fa fa-download"></i> </a>';
            $html .="<script>$('#quotation_attachment').html(' ".$filelink." ');</script>";
    
            echo $html;
        } else {
            $html .= '<tr><td colspan="5" align="center">No Data Found</td></tr>';
            $html .= '</table>';
    
            echo $html;
        }
    }
    
    public function bdtaskt1m12c10_12_get_spr_item_list()
    {
        $requisition_id = $this->request->getVar('requisition_id');
        $column = ["id, CONCAT(id) as text"];
        $data     = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_07_getSelect2Data('wh_bag_quatation', array('isApproved'=>0, 'requisition_id'=>$requisition_id), $column);
        echo json_encode($data);
    }
   
}
