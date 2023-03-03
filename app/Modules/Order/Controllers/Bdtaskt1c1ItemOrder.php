<?php namespace App\Modules\Order\Controllers;
use App\Modules\Order\Views;
use CodeIgniter\Controller;
use App\Modules\Order\Models\Bdtaskt1ItemOrderModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1c1ItemOrder extends BaseController
{
    private $bdtaskt1m4c9_01_item_requestModel;
    private $bdtaskt1m4c9_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m4c9_01_item_requestModel = new Bdtaskt1ItemOrderModel();
        $this->bdtaskt1m4c9_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['item', 'order','list']);
        $data['moduleTitle']= get_phrases(['staff','order']);
        $data['isDTables']  = true;
        $data['module']     = "Order";
        $data['page']       = "item_order/list";

        $data['hasCreateAccess']        = $this->permission->method('item_order_request', 'create')->access();
        $data['hasPriceAccess']         = $this->permission->method('item_order_price', 'read')->access();
        $data['hasPrintAccess']        = $this->permission->method('item_order_request', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('item_order_request', 'export')->access();

        $data['dealer_list']        = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_dealer_info', array('status'=>1));
        $data['sub_store_list']    = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_sale_store', array('status'=>1,'branch_id'=>session('branchId')));
        //$data['department_item_list']   = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_21_getResultWhereOR('wh_items', array('consumed_by'=>'department','status'=>1), array('consumed_by'=>'both','status'=>1));
        //echo get_last_query();exit;
        $data['item_list']      = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('status'=>1));
        $default_warehouse   = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_03_getRow('hrm_departments', array('id'=>session('departmentId')));
        $data['default_store_id']       = ($default_warehouse)?$default_warehouse->store_id:0;
        //var_dump($data['item_request']);exit;
        //$data['voucher_no']       = getMAXID('wh_order','id');
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get item_request info
    *--------------------------*/
    public function bdtaskt1m4c9_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m4c9_01_item_requestModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete item_request by ID
    *--------------------------*/
    public function bdtaskt1m4c9_02_deleteItemRequest($id)
    { 
        $data = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_03_getRow('wh_order', array('id'=>$id, 'isApproved'=>1));
        $MesTitle = get_phrases(['request', 'record']);
        if(!empty($data)){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['already', 'approved']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }

        /*$data = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_03_getRow('wh_order', array('id'=>$id));
        if(!empty($data)){
            $where = array('invoiceID'=> $data->invoice_id,'patient_id'=> $data->patient_id,'service_id'=> $data->service_id,'created_by'=> $data->doctor_id  );
            $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_02_Update('appoint_services', array('isItemReqSend'=>0), $where);
        }*/

        $data = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_06_Deleted('wh_order', array('id'=>$id));
        $data = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_06_Deleted('wh_order_details', array('request_id'=>$id));
        if(!empty($data)){
            // Store log data
            $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','order','request']), get_phrases(['deleted']), $id, 'wh_order');
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
    | Add item_request info
    *--------------------------*/
    public function bdtaskt1m4c9_03_addItemRequest()
    { 

        $action = $this->request->getVar('action');
        
        $voucher_no = 'ORD-'.getMAXID('wh_order', 'id');


        $data = array(
            'dealer_id'             => $this->request->getVar('dealer_id'), 
            'sub_store_id'          => $this->request->getVar('sub_store_id'), 
            'voucher_no'            => $voucher_no, 
            'date'                  => $this->date_db_format($this->request->getVar('date')), 
            'notes'                 => $this->request->getVar('notes'), 
            'request_by'            => session('id'), 
            'request_date'          => date('Y-m-d H:i:s')
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'dealer_id'        => 'required',
                'sub_store_id'     => 'required',
                'notes'            => 'max_length[250]',
            ];
        }
        $MesTitle = get_phrases(['request', 'record']);
        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            //$filePath = $this->bdtaskt1c1_01_fileUpload->doUpload('./assets/dist/img/item_request', $this->request->getFile('image'));
            if($action=='add'){
                
                $item_id    = $this->request->getVar('item_id');
                $qty        = $this->request->getVar('qty');
                
                $quantity_check = 1;
                foreach($item_id as $key => $item){
                    $qty = $qty[$key];
                    if( $qty >0 ){
                        $quantity_check = 0;
                        break;
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

                $isExist = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_03_getRow('wh_order', array('voucher_no'=>$voucher_no ));
                //$isExist2 = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_03_getRow('wh_items', array('nameA'=>$this->request->getVar('nameA')));
                if( !empty($isExist)  ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    //$data['image'] = $filePath;
                    $result = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_01_Insert('wh_order',$data);

                    $item_id    = $this->request->getVar('item_id');
                    $qty        = $this->request->getVar('qty');

                    $data2 = array();
                    foreach($item_id as $key => $item){
                        $data2[] = array('request_id'=> $result,'item_id'=> $item, 'qty'=> $qty[$key] );
                    }

                    $result2 = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_order_details',$data2);                  

                    if($result){
                        // Store log data
                        $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','order','request']), get_phrases(['created']), $result, 'wh_order');
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
                $result = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_02_Update('wh_order',$data, array('id'=>$id));
                if($result){
                    // Store log data
                    $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','order','request']), get_phrases(['updated']), $id, 'wh_order');
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

    function duplicate_action($id, $action){

        if( $action != 'return' ){
            $data = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_03_getRow('wh_order', array('id'=>$id, 'isResend'=>1));
            $MesTitle = get_phrases(['request', 'record']);
            if(!empty($data)){
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['already', 'resend']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);
                exit;
            }
            $data = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_03_getRow('wh_order', array('id'=>$id, 'isCollected'=>1));
            $MesTitle = get_phrases(['request', 'record']);
            if(!empty($data)){
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['already', 'collected']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);
                exit;
            }
        }
        $data = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_03_getRow('wh_order', array('id'=>$id, 'isReturned'=>1));
        $MesTitle = get_phrases(['request', 'record']);
        if(!empty($data)){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['already', 'returned']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }

    }

    function bdtaskt1m4c9_09_addItemReturn(){
        $action = $this->request->getVar('action2');
        $id = $this->request->getVar('id2');
        $afftedRows = 0;

        $this->duplicate_action($id, $action);

        if($action =='collect'){
            
            $sub_store_id      = $this->request->getVar('sub_store_id');
            $item_counter    = $this->request->getVar('item_counter');
            
            for($i=1; $i<=$item_counter; $i++){

                //$where = array('sub_store_id'=> $sub_store_id,'item_id'=> $this->request->getVar('item_id'.$i), 'stock <'=>$this->request->getVar('aqty'.$i) );
                //$sub_stock = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_03_getRow('wh_sale_stock', $where);

                $request_type = $this->request->getVar('request_type'.$i);
                $request_details_id = $this->request->getVar('request_details_id'.$i);
                $aqty = $this->request->getVar('aqty'.$i);
                
                $where11 = array('id'=> $request_details_id, 'avail_qty <'=>$aqty );
                if($request_type =='req'){
                    $request_details_stock = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_03_getRow('wh_item_request_details', $where11);
                } else {
                    $request_details_stock = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_03_getRow('wh_item_transfer_details', $where11);
                }
                
                if( !empty($request_details_stock) || ($aqty >0 && empty($request_details_id)) ){
                    $item = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_03_getRow('wh_items', array('id'=> $this->request->getVar('item_id'.$i) ));
                    $response = array(
                        'success'  =>false,
                        'message'  => $item->nameE,
                        'title'    => get_phrases(['stock', 'not', 'available'])
                    );
                    echo json_encode($response);exit;
                }

            }

            $data = array(
                'isCollected'        => 1, 
                'collected_by'       => session('id'), 
                'collected_date'     => date('Y-m-d H:i:s'), 
            );
            $where = array('id'=> $id  );
            $afftedRows += $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_02_Update('wh_order', $data, $where);
            $status = 'collected';
            
            for($i=1; $i<=$item_counter; $i++){
                $where3 = array('sub_store_id'=> $sub_store_id,'item_id'=> $this->request->getVar('item_id'.$i) );
                $affectedRows3 = $this->bdtaskt1m4c9_01_item_requestModel->bdtaskt1m12_04_updateStock($this->request->getVar('aqty'.$i), $where3);
                
                $request_type = $this->request->getVar('request_type'.$i);
                $where4 = array('id'=> $this->request->getVar('request_details_id'.$i) );
                $affectedRows4 = $this->bdtaskt1m4c9_01_item_requestModel->bdtaskt1m12_08_decreaseStock($this->request->getVar('aqty'.$i), $where4, $request_type);
            }

            $consumption = $this->bdtaskt1m4c9_01_item_requestModel->bdtaskt1m1_08_getConsumptionAmount($id);
            if( !empty($consumption) ){
                $this->bdtaskt1m12_11_accTransaction($id, $consumption->total_amount, $action);
            }

            
            // Store log data
            $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','order','request']), get_phrases(['collected']), $id, 'wh_order');
        }


        else if($action =='resend'){

            $data = array(
                'isApproved'      => 0, 
                'isResend'        => 1, 
                'resend_by'       => session('id'), 
                'resend_date'     => date('Y-m-d H:i:s'), 
            );
            $where = array('id'=> $id  );
            $afftedRows += $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_02_Update('wh_order', $data, $where);
            $status = 'resend';
            
            // Store log data
            $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['resend','order','request']), get_phrases(['resend']), $id, 'wh_order');
        }

        else if($action =='return'){
            $MesTitle = get_phrases(['item', 'order']);

            $item_counter       = $this->request->getVar('return_item_counter');
            $request_id         = $this->request->getVar('request_id');
            //$sub_store_id  = $this->request->getVar('sub_store_id');
            $return_qty_check = 1;
            $stock_check = 0;
            for($i=1; $i<=$item_counter; $i++){
                $return_qty = $this->request->getVar('return_qty'.$i);
                if( $return_qty >0 ){
                    $return_qty_check = 0;
                    
                    $where_check = array('request_id'=>$request_id, 'item_id'=> $this->request->getVar('item_id'.$i), 'aqty < '=> $return_qty);
                    $stock_data = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_03_getRow('wh_order_details', $where_check);
                    
                    if( !empty($stock_data) ){
                        $stock_check = 1;
                        break;
                    }
                }
            }
            if($return_qty_check){
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['quantity', 'is','required']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);
                exit;
            }
            if($stock_check){
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['quantity', 'should', 'be','less']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);
                exit;
            }
            
            for($i=1; $i<=$item_counter; $i++){
                $where = array('request_id'=> $request_id,'item_id'=> $this->request->getVar('item_id'.$i)  );
                $data = array('return_qty'=> $this->request->getVar('return_qty'.$i));
                $afftedRows += $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_02_Update('wh_order_details', $data, $where);

                //$where2 = array('sub_store_id'=> $sub_store_id,'item_id'=> $this->request->getVar('item_id'.$i)  );
                //$afftedRows2 = $this->bdtaskt1m4c9_01_item_requestModel->bdtaskt1m12_04_updateStock($this->request->getVar('return_qty'.$i), $where2);
            }

            $return_voucher_no = 'CONSRET-'.getMAXID('wh_order','id');

            $data2 = array(
                'return_voucher_no' => $return_voucher_no, 
                'return_date'       => date_db_format($this->request->getVar('return_date')), 
                'isReturned'        => 1, 
                'returned_by'       => session('id'), 
                'returned_date'     => date('Y-m-d H:i:s'), 
            );
            $where2 = array('id'=> $id  );
            $afftedRows3 = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_02_Update('wh_order', $data2, $where2);
            $status = 'returned';

            $consumption = $this->bdtaskt1m4c9_01_item_requestModel->bdtaskt1m1_09_getConsumptionReturnAmount($id);
            if( !empty($consumption) ){
                $this->bdtaskt1m12_11_accTransaction($id, $consumption->total_amount, $action);
            }

            // Store log data
            $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','order','request']), get_phrases(['returned']), $id, 'wh_order');
        }

        $MesTitle = get_phrases(['item']);

        if( $afftedRows ){
             $response = array(
                'success'  =>true,
                'message'  => get_phrases([$status, 'successfully']),
                'title'    => $MesTitle
            );
        } else {
            $response = array(
                'success'  =>false,
                'message'  => (ENVIRONMENT == 'production')?get_phrases(['something', 'went', 'wrong']):get_db_error(),
                'title'    => $MesTitle
            );
        }

        echo json_encode($response);
    }

    /*--------------------------
    | Get item_request by ID
    *--------------------------*/
    public function bdtaskt1m4c9_04_getItemRequestById($id)
    { 
        $data = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_03_getRow('wh_items', array('id'=>$id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get item details by ID
    *--------------------------*/
    public function bdtaskt1m4c9_05_getItemRequestDetailsById($id)
    { 
        $data = $this->bdtaskt1m4c9_01_item_requestModel->bdtaskt1m12_03_getItemRequestDetailsById($id);
        echo json_encode($data);
    }
   
    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m4c9_06_getItemPricingDetailsById()
    { 
        $request_id = $this->request->getVar('request_id');

        $request = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_03_getRow('wh_order', array('id'=>$request_id));
        
        $html = '<table class="table table-bordered w-100"><tr><th class="text-center">'.get_phrases(['item']).'</th><th class="text-center">'.get_phrases(['unit']).'</th><th class="text-center price_text">'.get_phrases(['unit','price']).'</th><th class="text-center">'.get_phrases(['request','quantity']).'</th><th class="text-center">'.get_phrases(['approved','quantity']).'</th><th class="text-center">'.get_phrases(['batch']).'</th><th class="text-center price_text">'.get_phrases(['approved','total','price']).'</th>';
        if($request->isReturned){
            $html .= '<th class="text-center return_info">'.get_phrases(['returned','quantity']).'</th><th class="text-center return_info price_text">'.get_phrases(['returned','total','price']).'</th>';
            $html .= '<th class="text-center consume_info">'.get_phrases(['consumed','quantity']).'</th><th class="text-center consume_info price_text">'.get_phrases(['consumed','total','price']).'</th>';
        }
        $html .= '</tr>';

        $request_details = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_order_details', array('request_id'=>$request_id));

        $item_counter=1;
        $grand_total = 0;
        $grand_total_return = 0;
        $grand_total_consumed = 0;
        foreach($request_details as $details)
        {
            $item_row = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_03_getRow('wh_items', array('id'=>$details->item_id));
            $unit_row = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
                        
            $html .= '<tr>
                        <td width="15%">'.$item_row->nameE.' ('.$item_row->company_code.')<input type="hidden" name="item_id'.$item_counter.'" id="item_id'.$item_counter.'" value="'.$details->item_id.'"><input type="hidden" name="aqty'.$item_counter.'" id="aqty'.$item_counter.'" value="'.$details->aqty.'"><input type="hidden" name="request_details_id'.$item_counter.'" id="request_details_id'.$item_counter.'" value="'.$details->request_details_id.'"><input type="hidden" name="request_type'.$item_counter.'" id="request_type'.$item_counter.'" value="'.$details->request_type.'"></td>
                        <td width="5%">'.(($unit_row)?$unit_row->nameE:'').'</td>
                        <td width="10%" align="right" class="price_text">'.number_format($details->price, 2).'</td>
                        <td width="10%" align="right">'.number_format($details->qty, 2).'</td>
                        <td width="10%" align="right">'.number_format($details->aqty, 2).'</td>
                        <td width="10%" >'.strtoupper($details->request_type).' > '.$details->batch_no.'</td>
                        <td width="10%" align="right" class="price_text">'.number_format($details->aqty*$details->price, 2).'</td>';
                        if($request->isReturned){
                            $html .= '<td width="10%" align="right" class="return_info">'.number_format($details->return_qty, 2).'</td>
                                <td width="10%" align="right" class="return_info price_text">'.number_format($details->return_qty*$details->price, 2).'</td>';
                            
                            $html .= '<td width="10%" align="right" class="consume_info">'.number_format($details->aqty - $details->return_qty, 2).'</td>
                                <td width="10%" align="right" class="consume_info price_text">'.number_format(($details->aqty - $details->return_qty)*$details->price, 2).'</td>';
                        }
            $html .= '</tr>';

            $grand_total += $details->aqty*$details->price;
            $grand_total_return += $details->return_qty*$details->price;
            $grand_total_consumed += ($details->aqty - $details->return_qty)*$details->price;
            $item_counter++;
        }
        
        $html .= '</table><input type="hidden" name="item_counter" id="item_counter" value="'.($item_counter-1).'"><input type="hidden" name="sub_store_id" id="sub_store_id" value="'.$request->sub_store_id.'">
            <div class="row form-group price_text">
                <div class="col-sm-12 text-right">
                    <strong>'.get_phrases(['approved','grand','total']).':</strong> '.number_format($grand_total,2).'
                </div>
            </div>';
        if($request->isReturned){
            $html .= '
                <div class="row form-group return_info price_text">
                    <div class="col-sm-12 text-right">
                        <strong>'.get_phrases(['returned','grand','total']).':</strong> '.number_format($grand_total_return,2).'
                    </div>
                </div>';
            $html .= '
                <div class="row form-group consume_info price_text">
                    <div class="col-sm-12 text-right">
                        <strong>'.get_phrases(['consumed','grand','total']).':</strong> '.number_format($grand_total_consumed,2).'
                    </div>
                </div>';
        }

        echo $html;
    }

    /*--------------------------
    | Get item details by ID
    *--------------------------*/
    public function bdtaskt1m4c9_07_getItemDetailsById($id)
    { 
        $data = $this->bdtaskt1m4c9_01_item_requestModel->bdtaskt1m4_05_getItemDetailsById($id);
        echo json_encode($data);
    }


    /*--------------------------
    | Get item return details by ID
    *--------------------------*/
    public function bdtaskt1m4c9_08_getItemReturnDetailsById()
    { 
        $request_id = $this->request->getVar('request_id');

        $html = '<table class="table table-bordered w-100"><tr><th class="text-center">'.get_phrases(['item']).'</th><th class="text-center">'.get_phrases(['request','quantity']).'</th><th class="text-center">'.get_phrases(['unit']).'</th><th class="text-center">'.get_phrases(['approved','quantity']).'</th><th class="text-center">'.get_phrases(['unit']).'</th><th class="text-center">'.get_phrases(['return','quantity']).'</th><th class="text-center">'.get_phrases(['unit']).'</th></tr>';
        $requests = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_03_getRow('wh_order', array('id'=>$request_id));

        $request_details = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_order_details', array('request_id'=>$request_id));

        $item_counter = 1;
        foreach($request_details as $details)
        {
            $item_row = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_03_getRow('wh_items', array('id'=>$details->item_id));
            $unit_row = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
                        
            $html .= '<tr>
                        <td width="25%">'.$item_row->nameE.'</td>
                        <td width="15%" align="right">'.number_format($details->qty, 2).'</td>
                        <td width="10%">'.(($unit_row)?$unit_row->nameE:'').'</td>
                        <td width="15%" align="right"><input type="text" name="aqty'.$item_counter.'" id="aqty'.$item_counter.'" value="'.$details->aqty.'" class="form-control text-right onlyNumber" readonly></td>
                        <td width="10%">'.(($unit_row)?$unit_row->nameE:'').'</td>
                        <td width="15%" align="right"><input type="text" name="return_qty'.$item_counter.'" id="return_qty'.$item_counter.'" value="'.$details->aqty.'" class="form-control text-right onlyNumber" onkeyup="calculation('.$item_counter.')"><input type="hidden" name="item_id'.$item_counter.'" id="item_id'.$item_counter.'" value="'.$details->item_id.'"></td>
                        <td width="10%">'.(($unit_row)?$unit_row->nameE:'').'</td>
                    </tr>';
            $item_counter++;
        }
        $html .= '</table><input type="hidden" name="return_item_counter" id="return_item_counter" value="'.($item_counter-1).'"><input type="hidden" name="request_id" id="request_id" value="'.$request_id.'"><input type="hidden" name="sub_store_id" id="sub_store_id" value="'.$requests->sub_store_id.'">';

        echo $html;
    }


    public function bdtaskt1m12_11_accTransaction($id, $amount, $action){

        if($action =='collect'){
            $exp_debit = $amount;
            $exp_credit = 0;

            $cs_debit = 0;
            $cs_credit = $amount;
        }
        if($action =='return'){
            $exp_debit = 0;
            $exp_credit = $amount;

            $cs_debit = $amount;
            $cs_credit = 0;
        }

        if( $amount>0 ){
            $data = array(
                'VNo'         => 'CONS-'.$id,
                'Vtype'       => 'CONS',
                'VDate'       => date('Y-m-d'),
                'COAID'       => '420000006',//GeneralityStockIssuanceExpens
                'Narration'   => 'Item order '.$action,
                'Debit'       => $exp_debit,
                'Credit'      => $exp_credit,
                'PatientID'   => 0,
                'BranchID'     => session('branchId'),
                'IsPosted'    => 1,
                'CreateBy'    => session('id'),
                'CreateDate'  => date('Y-m-d H:i:s'),
                'IsAppove'    => 1
            ); 
            $result = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction', $data);

            $data = array(
                'VNo'         => 'CONS-'.$id,
                'Vtype'       => 'CONS',
                'VDate'       => date('Y-m-d'),
                'COAID'       => '124000001',//Closing Stock
                'Narration'   => 'Item order '.$action,
                'Debit'       => $cs_debit,
                'Credit'      => $cs_credit,
                'PatientID'   => 0,
                'BranchID'     => session('branchId'),
                'IsPosted'    => 1,
                'CreateBy'    => session('id'),
                'CreateDate'  => date('Y-m-d H:i:s'),
                'IsAppove'    => 1
            ); 
            $result = $this->bdtaskt1m4c9_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction', $data);
            
            return $result;
        }
    }

    /*--------------------------
    | Get item list 
    *--------------------------*/
    public function bdtaskt1m12c17_13_getItemList($consumed_by)
    { 
        $data = $this->bdtaskt1m4c9_01_item_requestModel->bdtaskt1m12_11_getItemList($consumed_by);
        echo json_encode($data);
    }
}
