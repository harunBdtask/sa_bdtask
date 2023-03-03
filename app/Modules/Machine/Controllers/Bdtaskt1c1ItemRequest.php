<?php namespace App\Modules\Machine\Controllers;
use App\Modules\Machine\Views;
use CodeIgniter\Controller;
use App\Modules\Machine\Models\Bdtaskt1ItemRequestModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1c1ItemRequest extends BaseController
{
    private $bdtaskt1m12c9_01_item_requestModel;
    private $bdtaskt1m12c9_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c9_01_item_requestModel = new Bdtaskt1ItemRequestModel();
        $this->bdtaskt1m12c9_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        //echo session('departmentId');exit;
        $data['title']      = get_phrases(['consumption', 'request','list']);
        $data['moduleTitle']= get_phrases(['plant']);
        $data['isDTables']  = true;
        $data['module']     = "Machine";
        $data['page']       = "item_request/list";

        $data['hasCreateAccess']        = $this->permission->method('wh_machine_request', 'create')->access();
        $data['hasPrintAccess']        = $this->permission->method('wh_machine_request', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('wh_machine_request', 'export')->access();
        
        $data['main_store_list']       = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_store', array('status'=>1,'branch_id'=>session('branchId')));
        
        if(session('store_id') !=''  && session('store_id') !='0'){
            $where = array('status'=>1, 'id'=>session('store_id') );
        } else {
            $where = array('status'=>1,'branch_id'=>session('branchId') );
        }

        $data['sub_store_list']       = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_machine_store', $where);
        $data['item_list']       = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material', array('status'=>1));
        //$main_store       = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_03_getRow('wh_material_store', array('status'=>1,'branch_id'=>session('branchId')));

        //$data['main_store_id']       = ($main_store)?$main_store->id:0;
        //$data['main_store_name']       = ($main_store)?$main_store->nameE:'';

        $default_warehouse       = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_03_getRow('hrm_departments', array('id'=>session('departmentId')));
        $data['default_store_id']       = ($default_warehouse)?$default_warehouse->store_id:0;
        
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get item_request info
    *--------------------------*/
    public function bdtaskt1m12c9_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c9_01_item_requestModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete item_request by ID
    *--------------------------*/
    public function bdtaskt1m12c9_02_deleteItemRequest($id)
    { 
        $data = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_03_getRow('wh_machine_requests', array('id'=>$id, 'isApproved'=>1));
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
        $data = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_06_Deleted('wh_machine_requests', array('id'=>$id));
        $data = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_06_Deleted('wh_machine_request_details', array('request_id'=>$id));
        
        // Store log data
        $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','request']), get_phrases(['deleted']), $id, 'wh_machine_requests');
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
    | Add item_request info
    *--------------------------*/
    public function bdtaskt1m12c9_03_addItemRequest()
    { 

        //$old_image = $this->request->getVar('old_image'); 
        $action = $this->request->getVar('action');
        $voucher_no = 'REQ-'.getMAXID('wh_machine_requests', 'id');

        $data = array(
            'sub_store_id'     => $this->request->getVar('sub_store_id'), 
            'main_store_id'   => $this->request->getVar('main_store_id'), 
            'voucher_no'            => $voucher_no, 
            'date'                  => $this->date_db_format($this->request->getVar('date')), 
            'notes'                 => $this->request->getVar('notes'), 
            'request_by'            => session('id'), 
            'request_date'          => date('Y-m-d H:i:s')
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'voucher_no'    => 'required|min_length[4]|max_length[20]',
                'notes'         => 'max_length[250]',
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
                    $quantity = $qty[$key];
                    if( $quantity >0 ){
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

                $isExist = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_03_getRow('wh_machine_requests', array('voucher_no'=>$voucher_no ));
                //$isExist2 = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('nameA'=>$this->request->getVar('nameA')));
                if( !empty($isExist)  ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    //$data['image'] = $filePath;
                    $result = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_01_Insert('wh_machine_requests',$data);

                    $item_id    = $this->request->getVar('item_id');
                    $qty        = $this->request->getVar('qty');

                    $data2 = array();
                    foreach($item_id as $key => $item){
                        $data2[] = array('request_id'=> $result, 'item_id'=> $item, 'qty'=> $qty[$key] );
                    }

                    $result2 = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_machine_request_details',$data2);
                    // Store log data
                    $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','request']), get_phrases(['created']), $result, 'wh_machine_requests');                  

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
                //$data['image'] = !empty($filePath)?$filePath:$old_image;
                $result = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_02_Update('wh_machine_requests',$data, array('id'=>$id));
                // Store log data
                $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','request']), get_phrases(['updated']), $id, 'wh_machine_requests');
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

    function action_duplicate($id, $action){

        if($action !='return'){
            $data = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_03_getRow('wh_machine_requests', array('id'=>$id, 'isResend'=>1));
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
            $data = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_03_getRow('wh_machine_requests', array('id'=>$id, 'isCollected'=>1));
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
        $data = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_03_getRow('wh_machine_requests', array('id'=>$id, 'isReturned'=>1));
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

    /* Collect item */
    public function bdtaskt1m12c9_07_itemCollect()
    { 

        $action = $this->request->getVar('action');       
        
        $MesTitle   = get_phrases(['request', 'record']);
        $id         = $this->request->getVar('id');
        
        $this->action_duplicate($id, $action);

        if($action=='return'){

            $item_counter    = $this->request->getVar('item_counter');
            
            $return_qty_check = 1;
            $stock_check = 0;
            for($i=1; $i<=$item_counter; $i++){
                $return_qty = $this->request->getVar('return_qty'.$i);
                if( $return_qty >0 ){
                    $return_qty_check = 0;

                    $stock_data = $this->bdtaskt1m12c9_01_item_requestModel->bdtaskt1m12_08_checkAvailQty($id, $this->request->getVar('item_id'.$i), $return_qty);
                    
                    if( !empty($stock_data) ){
                        $stock_check = $this->request->getVar('item_id'.$i);
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
                $item_row = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=>$stock_check));
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['stock', 'not', 'available']),
                    'title'    => (!empty($item_row))?$item_row->nameE:$MesTitle
                );
                echo json_encode($response);
                exit;
            }
            
            $data3 = array(
                'isReturned'        => 1, 
                'returned_by'       => session('id'), 
                'returned_date'     => date('Y-m-d H:i:s'), 
            );

            $result3 = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_02_Update('wh_machine_requests',$data3, array('id'=>$id));

            $voucher_no = 'RET-'.getMAXID('wh_item_return', 'id');

            $data = array(
                'request_id'            => $id, 
                'voucher_no'            => $voucher_no, 
                'sub_store_id'     => $this->request->getVar('sub_store_id'), 
                'main_store_id'   => $this->request->getVar('main_store_id'), 
                'date'                  => $this->date_db_format($this->request->getVar('date')), 
                //'notes'                 => $this->request->getVar('notes'), 
                'request_by'            => session('id'), 
                'request_date'          => date('Y-m-d H:i:s')
            );

            $result = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_01_Insert('wh_machine_return',$data);
            
            //$item_counter    = $this->request->getVar('item_counter');

            $data2 = array();
            for($i=1; $i<=$item_counter; $i++){
                $item_id    = $this->request->getVar('item_id'.$i);
                $qty        = $this->request->getVar('qty'.$i);
                $aqty       = $this->request->getVar('aqty'.$i);
                $price      = $this->request->getVar('price'.$i);
                $return_qty         = $this->request->getVar('return_qty'.$i);
                $used_qty           = $this->request->getVar('used_qty'.$i);
                $avail_qty          = $this->request->getVar('avail_qty'.$i);
                $batch_no           = $this->request->getVar('batch_no'.$i);
                $expiry_date        = $this->request->getVar('expiry_date'.$i);
                $barcode            = $this->request->getVar('barcode'.$i);
                $receive_details_id = $this->request->getVar('receive_details_id'.$i);
                $receive_avail_qty  = $this->request->getVar('receive_avail_qty'.$i);

            
                $data2[] = array('return_id'=> $result,'item_id'=> $item_id, 'aqty'=> $aqty, 'qty'=> $qty, 'price'=> $price, 'return_qty'=> $return_qty, 'used_qty'=> $used_qty, 'avail_qty'=> $avail_qty, 'batch_no'=> $batch_no, 'expiry_date'=> $expiry_date, 'barcode'=> $barcode, 'receive_details_id'=> $receive_details_id, 'receive_avail_qty'=> $receive_avail_qty );
            }

            $result2 = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_machine_return_details',$data2);                  

        } else if($action=='collect'){
            $sub_store_id      = $this->request->getVar('sub_store_id');
            $main_store_id    = $this->request->getVar('main_store_id');

            $item_counter    = $this->request->getVar('item_counter');
            
            for($i=1; $i<=$item_counter; $i++){
                //$where = array('store_id'=> $main_store_id,'item_id'=> $this->request->getVar('item_id'.$i), 'stock <'=>$this->request->getVar('aqty'.$i) );
                //$main_stock = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_03_getRow('wh_material_stock', $where);

                $receive_type = $this->request->getVar('receive_type'.$i);
                $receive_details_id = $this->request->getVar('receive_details_id'.$i);
                $aqty = $this->request->getVar('aqty'.$i);
                
                $where11 = array('id'=> $receive_details_id, 'avail_qty <'=>$aqty );
                if($receive_type =='rec'){
                    $receive_details_stock = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_03_getRow('wh_material_receive_details', $where11);
                } else {
                    $receive_details_stock = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_03_getRow('wh_material_transfer_details', $where11);
                }
                
                if( !empty($receive_details_stock) || ($aqty >0 && empty($receive_details_id)) ){
                    $item = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=> $this->request->getVar('item_id'.$i) ));
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

            $result = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_02_Update('wh_machine_requests',$data, array('id'=>$id));

            for($i=1; $i<=$item_counter; $i++){
                /*$where = array('request_id'=> $id,'item_id'=> $this->request->getVar('item_id'.$i) );
                $data2 = array('aqty' => $this->request->getVar('aqty'.$i) );
                $result2 = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_02_Update('wh_machine_request_details', $data2, $where);*/

                $where2 = array('sub_store_id'=> $sub_store_id,'item_id'=> $this->request->getVar('item_id'.$i) );
                $affectedRows2 = $this->bdtaskt1m12c9_01_item_requestModel->bdtaskt1m12_05_increaseStock($this->request->getVar('aqty'.$i), $where2);

                if( !$affectedRows2 && $this->request->getVar('aqty'.$i) >0 ){
                    $data3 = array('sub_store_id'=> $sub_store_id,'item_id'=> $this->request->getVar('item_id'.$i),'stock' => $this->request->getVar('aqty'.$i),'stock_in' => $this->request->getVar('aqty'.$i) );
                    $result3 = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_01_Insert('wh_machine_stock',$data3);
                }

                $where3 = array('store_id'=> $main_store_id,'item_id'=> $this->request->getVar('item_id'.$i) );
                $affectedRows3 = $this->bdtaskt1m12c9_01_item_requestModel->bdtaskt1m12_06_decreaseStock($this->request->getVar('aqty'.$i), $where3);

                $receive_type = $this->request->getVar('receive_type'.$i);
                $where4 = array('id'=> $this->request->getVar('receive_details_id'.$i) );
                $affectedRows4 = $this->bdtaskt1m12c9_01_item_requestModel->bdtaskt1m12_07_decreaseReceiveStock($this->request->getVar('aqty'.$i), $where4, $receive_type);


                $balance = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_03_getRow('wh_material_stock', array('store_id'=> $main_store_id,'item_id'=> $this->request->getVar('item_id'.$i) ));

                $stock = 0;
                if( !empty($balance) ){
                    $stock = $balance->stock;
                }

                $data4 = array( 'transfer_id'=> $id, 'store_id'=> $main_store_id, 'item_id'=> $this->request->getVar('item_id'.$i), 'stock' => $stock, 'stock_out' => $this->request->getVar('aqty'.$i), 'created_by' => session('id'), 'created_at' => date('Y-m-d H:i:s') );
                $result4 = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_01_Insert('wh_material_stock_entry',$data4);
            }
        } else if($action=='resend'){

            $data = array(
                'isApproved'        => 0, 
                'isResend'          => 1, 
                'resend_by'         => session('id'), 
                'resend_date'       => date('Y-m-d H:i:s'), 
            );

            $result = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_02_Update('wh_machine_requests',$data, array('id'=>$id));

        }
            
    
        if($result){
             $response = array(
                'success'  =>true,
                'message'  => get_phrases([($action=='resend')?$action:$action.'ed', 'successfully']),
                'title'    => $MesTitle
            );
        } else {
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['something', 'want', 'wrong']),
                'title'    => $MesTitle
            );
        }
        
        echo json_encode($response);
    }

    /*--------------------------
    | Get item_request by ID
    *--------------------------*/
    public function bdtaskt1m12c9_04_getItemRequestById($id)
    { 
        $data = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=>$id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get item details by ID
    *--------------------------*/
    public function bdtaskt1m12c9_05_getItemRequestDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c9_01_item_requestModel->bdtaskt1m12_03_getItemRequestDetailsById($id);
        echo json_encode($data);
    }

   
    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c9_06_getItemPricingDetailsById()
    { 
        $request_id = $this->request->getVar('request_id');

        $html = '<table class="table table-bordered w-100"><tr><th class="text-center">'.get_phrases(['item']).'</th><th class="text-center">'.get_phrases(['request','quantity']).'</th><th class="text-center">'.get_phrases(['approved','quantity']).'</th><th class="text-center">'.get_phrases(['unit']).'</th><th class="text-center">'.get_phrases(['batch']).'</th></tr>';

        $request_details = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_machine_request_details', array('request_id'=>$request_id));

        $item_counter = 1;
        foreach($request_details as $details)
        {
            $item_row = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=>$details->item_id));
            $unit_row = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
                        
            $html .= '<tr>
                        <td width="45%">'.$item_row->nameE.' ('.$item_row->company_code.')
                            <input type="hidden" name="item_id'.$item_counter.'" id="item_id'.$item_counter.'" value="'.$details->item_id.'">
                            <input type="hidden" name="receive_details_id'.$item_counter.'" id="receive_details_id'.$item_counter.'" value="'.$details->receive_details_id.'">
                            <input type="hidden" name="receive_type'.$item_counter.'" id="receive_type'.$item_counter.'" value="'.$details->receive_type.'">
                            <input type="hidden" name="aqty'.$item_counter.'" id="aqty'.$item_counter.'" value="'.$details->aqty.'">
                        </td>
                        <td width="15%" align="right">'.number_format($details->qty, 2).'</td>
                        <td width="15%" align="right">'.number_format($details->aqty, 2).'</td>
                        <td width="10%">'.(($unit_row)?$unit_row->nameE:'').'</td>
                        <td width="15%">'.strtoupper($details->receive_type).' > '.$details->batch_no.'</td>
                    </tr>';
            $item_counter++;
        }
        $html .= '</table><input type="hidden" name="item_counter" id="item_counter" value="'.($item_counter-1).'">';

        echo $html;
    }


    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c9_07_getItemReturnQuantityDetailsById()
    { 
        $request_id = $this->request->getVar('request_id');

        $html = '<table class="table table-bordered w-100"><tr><th class="text-center">'.get_phrases(['item']).'</th><th class="text-center">'.get_phrases(['request','qty']).'</th><th class="text-center">'.get_phrases(['approved','qty']).'</th><th class="text-center">'.get_phrases(['available','qty']).'</th><th class="text-center">'.get_phrases(['return','qty']).'</th><th class="text-center">'.get_phrases(['unit']).'</th><th class="text-center">'.get_phrases(['batch']).'</th></tr>';

        $request_details = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_machine_request_details', array('request_id'=>$request_id));

        $item_counter = 1;
        foreach($request_details as $details)
        {
            $item_row = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=>$details->item_id));
            $unit_row = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
            
            $avail_qty = $details->avail_qty - $details->adjust_in;
            if( $avail_qty <0 ){
                $avail_qty = 0;
            }

            $html .= '<tr>
                        <td width="25%">'.$item_row->nameE.' ('.$item_row->company_code.')<input type="hidden" name="item_id'.$item_counter.'" id="item_id'.$item_counter.'" value="'.$details->item_id.'"></td>                        
                        <td width="15%" align="right">'.number_format($details->qty, 2).'<input type="hidden" name="qty'.$item_counter.'" id="qty'.$item_counter.'" value="'.$details->qty.'"></td>
                        <td width="15%" align="right">'.number_format($details->aqty, 2).'<input type="hidden" name="aqty'.$item_counter.'" id="aqty'.$item_counter.'" value="'.$details->aqty.'"></td>
                        <td width="15%" align="right">'.number_format($details->avail_qty, 2).'<input type="hidden" name="avail_qty'.$item_counter.'" id="avail_qty'.$item_counter.'" value="'.$avail_qty.'"></td>
                        <td width="15%"><input type="text" name="return_qty'.$item_counter.'" id="return_qty'.$item_counter.'" class="form-control text-right" onkeyup="avail_quantity_check('.$item_counter.')"></td>
                        <td width="5%">'.(($unit_row)?$unit_row->nameE:'').'</td>
                        <td width="10%">'.$details->batch_no.'<input type="hidden" name="batch_no'.$item_counter.'" id="batch_no'.$item_counter.'" value="'.$details->batch_no.'"><input type="hidden" name="price'.$item_counter.'" id="price'.$item_counter.'" value="'.$details->price.'"><input type="hidden" name="used_qty'.$item_counter.'" id="used_qty'.$item_counter.'" value="'.$details->used_qty.'"><input type="hidden" name="avail_qty'.$item_counter.'" id="avail_qty'.$item_counter.'" value="'.$details->avail_qty.'"><input type="hidden" name="expiry_date'.$item_counter.'" id="expiry_date'.$item_counter.'" value="'.$details->expiry_date.'"><input type="hidden" name="barcode'.$item_counter.'" id="barcode'.$item_counter.'" value="'.$details->barcode.'"><input type="hidden" name="receive_avail_qty'.$item_counter.'" id="receive_avail_qty'.$item_counter.'" value="'.$details->receive_avail_qty.'"><input type="hidden" name="receive_details_id'.$item_counter.'" id="receive_details_id'.$item_counter.'" value="'.$details->receive_details_id.'"><input type="hidden" name="receive_type'.$item_counter.'" id="receive_type'.$item_counter.'" value="'.$details->receive_type.'"></td>
                    </tr>';
            $item_counter++;
        }
        $html .= '</table><input type="hidden" name="item_counter" id="return_item_counter" value="'.($item_counter-1).'">';

        echo $html;

    }


    public function bdtaskt1m12c9_08_getItemDetailsById($item_id){
        $sub_store_id = $this->request->getVar('sub_store_id');
        $main_store_id = $this->request->getVar('main_store_id');

        $data = $this->bdtaskt1m12c9_01_item_requestModel->bdtaskt1m12_09_getItemDetailsById($item_id);
        
        $plant = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_03_getRow('wh_machine_stock', array('item_id'=>$item_id, 'sub_store_id'=>$sub_store_id));
        $stock = 0;
        if( !empty($plant) ){
            $stock = $plant->stock;
        }
        $data['stock'] = $stock;
        
        $store = $this->bdtaskt1m12c9_02_CmModel->bdtaskt1m1_03_getRow('wh_material_stock', array('item_id'=>$item_id, 'store_id'=>$main_store_id));
        $store_stock = 0;
        if( !empty($store) ){
            $store_stock = $store->stock;
        }
        $data['store_stock'] = $store_stock;

        echo json_encode($data);
    }
}
