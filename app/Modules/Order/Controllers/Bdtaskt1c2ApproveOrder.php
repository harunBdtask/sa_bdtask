<?php namespace App\Modules\Order\Controllers;
use App\Modules\Order\Views;
use CodeIgniter\Controller;
use App\Modules\Order\Models\Bdtaskt1ApproveOrderModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1c2ApproveOrder extends BaseController
{
    private $bdtaskt1m12c11_01_request_approveModel;
    private $bdtaskt1m12c11_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c11_01_request_approveModel = new Bdtaskt1ApproveOrderModel();
        $this->bdtaskt1m12c11_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/

    public function index()
    {
        $data['title']      = get_phrases(['item','order','list']);
        $data['moduleTitle']= get_phrases(['staff','order']);
        $data['isDTables']  = true;
        $data['module']     = "Order";
        $data['page']       = "approve_order/list";
        
        $data['hasPrintAccess']        = $this->permission->method('wh_order_approve', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('wh_order_approve', 'export')->access();
        
        $data['dealer_list']        = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_dealer_info', array('status'=>1));
        $data['main_store_list']       = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_production_store', array('status'=>1));
        $data['sub_store_list']       = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_sale_store', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('status'=>1));
        //var_dump($data['request_approve']);exit;
        //$data['voucher_no']       = rand();
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get request_approve info
    *--------------------------*/
    public function bdtaskt1m12c11_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c11_01_request_approveModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete request_approve by ID
    *--------------------------*/
    public function bdtaskt1m12c11_02_deleteRequestApprove($id)
    { 
        $data = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_06_Deleted('wh_items', array('id'=>$id));
        $MesTitle = get_phrases(['request', 'record']);
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

    function action_duplicate($id, $action){

        if($action !='collect'){
            $data11 = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_03_getRow('wh_order', array('id'=>$id, 'isApproved'=>2));
            $MesTitle = get_phrases(['request', 'record']);
            if(!empty($data11)){
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['already', 'rejected']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);
                exit;
            }
            $data11 = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_03_getRow('wh_order', array('id'=>$id, 'isApproved'=>1));
            $MesTitle = get_phrases(['request', 'record']);
            if(!empty($data11)){
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['already', 'approved']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);
                exit;
            }
        }
        $data11 = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_03_getRow('wh_order', array('id'=>$id, 'isReceived'=>1));
        $MesTitle = get_phrases(['request', 'record']);
        if(!empty($data11)){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['already', 'collected']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }
    }
    /*--------------------------
    | Add request_approve info
    *--------------------------*/
    public function bdtaskt1m12c11_03_addRequestApprove()
    { 

        //$old_image = $this->request->getVar('old_image'); 
        $action = $this->request->getVar('action');
        //echo $action;exit;
        $data = array(
            'isResend'          => 0, 
            'isApproved'        => ($action=='reject')?2:1, 
            'approved_by'       => session('id'), 
            'approved_date'     => date('Y-m-d H:i:s'), 
        );
        
        $MesTitle = get_phrases(['request', 'record']);
        $id       = $this->request->getVar('id');
        
        $this->action_duplicate($id, $action);

        if($action=='approve'){

            $item_counter           = $this->request->getVar('item_counter');
            
            $quantity_check = 1;
            $stock_check = 0;
            for($i=1; $i<=$item_counter; $i++){
                $req_qty = $this->request->getVar('req_qty'.$i);
                $aqty = $this->request->getVar('aqty'.$i);
                if($aqty > $req_qty){
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['approve','quantity', 'should', 'be','less', 'than','request','quantity']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);
                    exit;
                } 
                if( $aqty >0 ){
                    $quantity_check = 0;
                    
                    $request_details_id_full = $this->request->getVar('request_details_id'.$i);
                    $request_details_id_arr = explode("_", $request_details_id_full);
                    if( empty($request_details_id_full) || count($request_details_id_arr) <2 ){
                        $request_type = 'req';
                        $request_details_id = 0;
                    } else {
                        $request_type = $request_details_id_arr[0];
                        $request_details_id = $request_details_id_arr[1];
                    }

                    $where_check = array('id'=>$request_details_id, 'avail_qty < '=> $aqty);

                    if($request_type == 'req'){
                        $stock_data = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_03_getRow('wh_item_request_details', $where_check );
                    } else {
                        $stock_data = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_03_getRow('wh_item_transfer_details', $where_check );
                    }
                    if( !empty($stock_data) || ($aqty >0 && empty($request_details_id)) ){
                        $stock_check = $this->request->getVar('item_id'.$i);
                        break;
                    }
                }
            }
            if($quantity_check){
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['quantity', 'is','required']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);
                exit;
            } 
            if($stock_check){
                $item_row = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_03_getRow('wh_items', array('id'=> $stock_check));
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['stock', 'not','available']),
                    'title'    => (!empty($item_row))?$item_row->nameE:$MesTitle
                );
                echo json_encode($response);
                exit;
            }

            $result = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_02_Update('wh_order',$data, array('id'=>$id));

            
            for($i=1; $i<=$item_counter; $i++){
                $request_details_id_full = $this->request->getVar('request_details_id'.$i);
                $request_details_id_arr = explode("_", $request_details_id_full);
                if( empty($request_details_id_full) || count($request_details_id_arr) <2 ){
                    $request_type = 'req';
                    $request_details_id = 0;
                } else {
                    $request_type = $request_details_id_arr[0];
                    $request_details_id = $request_details_id_arr[1];
                }

                if($request_type == 'req'){
                    $request_details = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_03_getRow('wh_item_request_details', array('id'=>$request_details_id));
                } else {
                    $request_details = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_03_getRow('wh_item_transfer_details', array('id'=>$request_details_id));
                }

                $where = array('request_id'=> $id,'item_id'=> $this->request->getVar('item_id'.$i) );
                $data2 = array(
                    'aqty'              => $this->request->getVar('aqty'.$i),
                    'price'             => ($request_details)?$request_details->price:0, 
                    'batch_no'          => ($request_details)?$request_details->batch_no:'', 
                    'expiry_date'       => ($request_details)?$request_details->expiry_date:'', 
                    'barcode'           => ($request_details)?$request_details->barcode:'', 
                    'request_details_qty'=> ($request_details)?$request_details->avail_qty:0, 
                    'request_details_id' => $request_details_id, 
                    'request_type'       => $request_type, 
                );
                $result2 = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_02_Update('wh_order_details', $data2, $where);
                
                /*$where3 = array('sub_store_id'=> $sub_store_id,'item_id'=> $this->request->getVar('item_id'.$i) );
                $affectedRows3 = $this->bdtaskt1m12c11_01_request_approveModel->bdtaskt1m12_05_decreaseStock($this->request->getVar('aqty'.$i), $where3);*/
            }

            // Store log data
            $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','order']), get_phrases(['approved']), $id, 'wh_order');
            
        } else if($action=='collect'){
            
            $data = array(
                'isReceived'        => 1, 
                'received_by'       => session('id'), 
                'received_date'     => date('Y-m-d H:i:s'), 
            );
            $result = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_02_Update('wh_order',$data, array('id'=>$id));

            $sub_store_id      = $this->request->getVar('sub_store_id');
            $item_counter    = $this->request->getVar('item_counter');
            
            for($i=1; $i<=$item_counter; $i++){
                $where4 = array('id'=> $this->request->getVar('request_details_id'.$i),'item_id'=> $this->request->getVar('item_id'.$i) );
                $result2 = $this->bdtaskt1m12c11_01_request_approveModel->bdtaskt1m12_08_increaseQty($this->request->getVar('aqty'.$i), $where4, $this->request->getVar('request_type'.$i));
                
                $where3 = array('sub_store_id'=> $sub_store_id,'item_id'=> $this->request->getVar('item_id'.$i) );
                $affectedRows3 = $this->bdtaskt1m12c11_01_request_approveModel->bdtaskt1m12_04_increaseStock($this->request->getVar('aqty'.$i), $where3);
            }
            // Store log data
            $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','order']), get_phrases(['collected']), $id, 'wh_order');

        } else if($action=='reject'){
            
            $result = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_02_Update('wh_order',$data, array('id'=>$id));

            $cons = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_03_getRow('wh_order', array('id'=>$id));
            if( !empty($cons) ){
                $where = array('invoiceID'=> $cons->invoice_id,'service_id'=> $cons->service_id );
                $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_02_Update('appoint_services', array('isItemReqSend'=>0), $where );
            }
            // Store log data
            $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','order']), get_phrases(['rejected']), $id, 'wh_order');
        }

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

        echo json_encode($response);
    }

    /*--------------------------
    | Get request_approve by ID
    *--------------------------*/
    public function bdtaskt1m12c11_04_getRequestApproveById($id)
    { 
        $data = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_03_getRow('wh_items', array('id'=>$id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get item details by ID
    *--------------------------*/
    public function bdtaskt1m12c11_05_getRequestApproveDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c11_01_request_approveModel->bdtaskt1m12_03_getRequestApproveDetailsById($id);
        echo json_encode($data);
    }

   
    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c11_06_getItemPricingDetailsById()
    { 
        $request_id = $this->request->getVar('request_id');
        $sub_store_id = $this->request->getVar('sub_store_id');
        $action = $this->request->getVar('action');

        $html = '<table class="table table-bordered w-100"><tr><th class="text-center">'.get_phrases(['item']).'</th><th class="text-center">'.get_phrases(['request','quantity']).'</th><th class="text-center">'.get_phrases(['unit']).'</th>';
        if($action=='preview' || $action=='approve'){
            $html .= '<th class="text-center">'.get_phrases(['batch']).'</th>';
        }
        $html .= '<th class="text-center">'.get_phrases(['available','stock']).'</th><th class="text-center">'.get_phrases([($action=='collect')?'returned':'approve','quantity']).'</th><th class="text-center">'.get_phrases(['unit']).'</th></tr>';

        $request_details = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_order_details', array('request_id'=>$request_id));

        $item_counter = 1;
        $total_price = 0;
        foreach($request_details as $details)
        {
            $item_row = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_03_getRow('wh_items', array('id'=>$details->item_id));
            $unit_row = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
            $stock_row = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_03_getRow('wh_sale_stock', array('sub_store_id'=>$sub_store_id, 'item_id'=>$details->item_id ));
            
            $stock_value = 0;
            $aqty = 0;//$details->qty;
            /*if(!empty($stock_row)){                
                if($stock_row->stock >0 ){
                    $stock_value = $stock_row->stock;
                }

                if($stock_value < $aqty){
                    $aqty = $stock_value;
                }
            } else {
                $aqty = 0;
            }*/
            if($action=='collect'){
                $aqty = $details->return_qty;
            }
            if($action=='preview'){
                $aqty = $details->aqty;
                $stock_value = $details->request_details_qty;
            }

            $html .= '<tr>
                        <td width="25%" class="valign-middle">'.$item_row->nameE.' ('.$item_row->company_code.')<input type="hidden" name="item_id'.$item_counter.'" id="item_id'.$item_counter.'" value="'.$details->item_id.'"></td>
                        <td width="15%" align="right" class="valign-middle"><input type="text" name="req_qty'.$item_counter.'" id="req_qty'.$item_counter.'" class="form-control text-right" value="'.$details->qty.'" readonly></td>
                        <td width="5%" class="valign-middle">'.(($unit_row)?$unit_row->nameE:'').'</td>';
            if($action=='approve'){
                
                $html .= '<td width="20%"><select name="request_details_id'.$item_counter.'" id="request_details_id'.$item_counter.'" class="form-control custom-select" onchange="change_avail_qty(this.value, '.$item_counter.')"><option value="" selected>Select Batch</option>';
                $hidden = '';

                $item_receive = $this->bdtaskt1m12c11_01_request_approveModel->bdtaskt1m12_06_getItemReceiveDetails($details->item_id, $sub_store_id);
                foreach($item_receive as $receive){
                    $avail_qty = number_format($receive->avail_qty, 2, '.', '');
                    if( $avail_qty <0 ){
                        $avail_qty = 0;
                    }
                    $expiry_date = implode("/", array_reverse(explode("-", $receive->expiry_date)));
                    $expiry_date_new = implode("", explode("-", $receive->expiry_date));
                    $today_date = date('Ymd');

                    $html .= '<option value="req_'.$receive->id.'">'.$receive->voucher_no.' > '.$receive->batch_no.' > '.$avail_qty.' '.(($receive->expiry_date =='')?'':(($today_date > $expiry_date_new)?'Expired':'Exp. '.$expiry_date)).'</option>';
                    $hidden .= '<input type="hidden" id="receive_avail_qty_req_'.$receive->id.'" value="'.$avail_qty.'">';
                    
                }

                $item_receive = $this->bdtaskt1m12c11_01_request_approveModel->bdtaskt1m12_07_getItemTransferDetails($details->item_id, $sub_store_id);
                foreach($item_receive as $receive){
                    $avail_qty = number_format($receive->avail_qty, 2, '.', '');
                    if( $avail_qty <0 ){
                        $avail_qty = 0;
                    }
                    $expiry_date = implode("/", array_reverse(explode("-", $receive->expiry_date)));
                    $expiry_date_new = implode("", explode("-", $receive->expiry_date));
                    $today_date = date('Ymd');

                    $html .= '<option value="tran_'.$receive->id.'">'.$receive->voucher_no.' > '.$receive->batch_no.' > '.$avail_qty.' '.(($receive->expiry_date =='')?'':(($today_date > $expiry_date_new)?'Expired':'Exp. '.$expiry_date)).'</option>';
                    $hidden .= '<input type="hidden" id="receive_avail_qty_tran_'.$receive->id.'" value="'.$avail_qty.'">';
                    
                }
                
                $html .= '</select>'.$hidden.'</td>';
            }
            if($action=='preview'){
                $html .= '<td width="20%" class="valign-middle" align="right">'.$details->batch_no.'</td>';
                $total_price = $details->aqty*$details->price;
            }
            $html .= '<td width="15%" class="valign-middle" align="right"><input type="text" name="avail_qty'.$item_counter.'" id="avail_qty'.$item_counter.'" class="form-control text-right" value="'.$stock_value.'" readonly></td>
                        <td width="15%"><input type="text" name="aqty'.$item_counter.'" id="aqty'.$item_counter.'" class="form-control text-right onlyNumber" value="'.$aqty.'" onkeyup="check_quantity_exceed('.$item_counter.')" '.(($action=='collect' || $action=='preview')?'readonly':'').'></td>
                        <td width="5%" class="valign-middle">'.(($unit_row)?$unit_row->nameE:'').'</td>';
            
            $html .= '</tr>';
            if($action=='collect'){
                $html .= '<input type="hidden" name="request_details_id'.$item_counter.'" id="request_details_id'.$item_counter.'" value="'.$details->request_details_id.'"><input type="hidden" name="request_type'.$item_counter.'" id="request_type'.$item_counter.'" value="'.$details->request_type.'">';
            }

            $item_counter++;
        }

        //if($action=='preview'){
            //$html .= '<td colspan="6" class="valign-middle" align="right"><strong>'.get_phrases(['grand','total']).'</strong></td><td colspan="6" class="valign-middle" align="right">'.number_format($total_price, 2).'</td>';
        //}

        $html .= '</table><input type="hidden" name="item_counter" id="item_counter" value="'.($item_counter-1).'">';

        echo $html;
    }

    public function sub_stock_reset(){
        $this->bdtaskt1m12c11_01_request_approveModel->sub_stock_reset();

        $sub_store_list       = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_sale_store', array('status'=>1));
        $item_list       = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('status'=>1));
        foreach($sub_store_list as $dept){
            foreach($item_list as $item){
                $data[] = array('sub_store_id'=>$dept->id, 'item_id'=>$item->id, 'stock'=>1000 );
            }
        }
        $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_sale_stock', $data);
        echo 'done';
    }

    public function main_stock_reset(){
        $this->bdtaskt1m12c11_01_request_approveModel->main_stock_reset();

        $main_store_list       = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_production_store', array('status'=>1));
        $item_list       = $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('status'=>1));
        foreach($main_store_list as $branch){
            foreach($item_list as $item){
                $data[] = array('store_id'=>$branch->id, 'item_id'=>$item->id, 'stock'=>1000 );
            }
        }
        $this->bdtaskt1m12c11_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_production_stock', $data);
        echo 'done';
    }
}
