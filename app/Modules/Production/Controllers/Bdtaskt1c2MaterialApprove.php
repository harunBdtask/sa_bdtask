<?php namespace App\Modules\Production\Controllers;
use App\Modules\Production\Views;
use CodeIgniter\Controller;
use App\Modules\Production\Models\Bdtaskt1MaterialApproveModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1c2MaterialApprove extends BaseController
{
    private $bdtaskt1m12c13_01_item_approveModel;
    private $bdtaskt1m12c13_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c13_01_item_approveModel = new Bdtaskt1MaterialApproveModel();
        $this->bdtaskt1m12c13_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['material', 'consumption','list']);
        $data['moduleTitle']= get_phrases(['production']);
        $data['isDTables']  = true;
        $data['module']     = "Production";
        $data['page']       = "material_approve/list";

        $data['hasPrintAccess']        = $this->permission->method('wh_request_approve', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('wh_request_approve', 'export')->access();

        $data['production_list']       = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_production', array('status'=>1), null, null, array('id','DESC'));
        $data['sub_store_list']       = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_machine_store', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material', array('status'=>1));
        //var_dump($data['item_approve']);exit;
        //$data['voucher_no']       = rand();
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get item_approve info
    *--------------------------*/
    public function bdtaskt1m12c13_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c13_01_item_approveModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete item_approve by ID
    *--------------------------*/
    public function bdtaskt1m12c13_02_deleteItemApprove($id)
    { 
        $data = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_06_Deleted('wh_material', array('id'=>$id));
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
            $data = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('wh_machine_requests', array('id'=>$id, 'isApproved'=>2));
            $MesTitle = get_phrases(['request', 'record']);
            if(!empty($data)){
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['already', 'rejected']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);
                exit;
            }

            $data = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('wh_machine_requests', array('id'=>$id, 'isApproved'=>1));
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
        }

        $data = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('wh_machine_return', array('request_id'=>$id, 'isCollected'=>1));
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
    /*--------------------------
    | Add item_approve info
    *--------------------------*/
    public function bdtaskt1m12c13_03_addItemApprove()
    { 

        $action = $this->request->getVar('action');
        
        $MesTitle   = get_phrases(['request', 'record']);
        $id         = $this->request->getVar('id');
        
        $this->action_duplicate($id, $action);

        if($action=='approve'){

            $item_counter    = $this->request->getVar('item_counter');

            $quantity_check = 1;
            $stock_check = 0;
            for($i=1; $i<=$item_counter; $i++){
                $qty = $this->request->getVar('qty'.$i);
                $aqty = $this->request->getVar('aqty'.$i);
                if($aqty > $qty){
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

                    $receive_details_id_full = $this->request->getVar('receive_details_id'.$i);
                    $receive_details_id_arr = explode("_", $receive_details_id_full);
                    if( empty($receive_details_id_full) || count($receive_details_id_arr) <2 ){
                        $receive_type = 'rec';
                        $receive_details_id = 0;
                    } else {
                        $receive_type = $receive_details_id_arr[0];
                        $receive_details_id = $receive_details_id_arr[1];
                    }

                    $where_check = array('id'=>$receive_details_id, 'avail_qty < '=> $aqty);

                    $table = 'wh_material_receive_details';
                    if($receive_type == 'tran'){
                        $table = 'wh_material_transfer_details';
                    }
                    $stock_data = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow($table, $where_check);
                    
                    if( !empty($stock_data) || ($aqty >0 && empty($receive_details_id)) ){
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
                $item_row = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=> $stock_check ));
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['stock', 'not','available']),
                    'title'    => (!empty($item_row))?$item_row->nameE:$MesTitle
                );
                echo json_encode($response);
                exit;
            } 
            
            $data = array(
                'isResend'          => 0, 
                'isApproved'        => 1, 
                'approved_by'       => session('id'), 
                'approved_date'     => date('Y-m-d H:i:s'), 
            );

            $result = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_02_Update('wh_machine_requests',$data, array('id'=>$id));

            $sub_store_id = $this->request->getVar('sub_store_id');
            //$item_counter    = $this->request->getVar('item_counter');
            
            for($i=1; $i<=$item_counter; $i++){

                $aqty = $this->request->getVar('aqty'.$i);
                $item_id = $this->request->getVar('item_id'.$i);

                $receive_details_id_full = $this->request->getVar('receive_details_id'.$i);
                $receive_details_id_arr = explode("_", $receive_details_id_full);
                if( empty($receive_details_id_full) || count($receive_details_id_arr) <2 ){
                    $receive_type = 'rec';
                    $receive_details_id = 0;
                } else {
                    $receive_type = $receive_details_id_arr[0];
                    $receive_details_id = $receive_details_id_arr[1];
                }

                $table = 'wh_material_receive_details';
                if($receive_type == 'tran'){
                    $table = 'wh_material_transfer_details';
                }
                $receive_details = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow($table, array('id'=>$receive_details_id));

                $where = array('request_id'=> $id,'item_id'=> $item_id );
                $data2 = array(
                    'aqty'              => $aqty, 
                    'avail_qty'         => $aqty, 
                    'price'             => ($receive_details)?$receive_details->price:0, 
                    'batch_no'          => ($receive_details)?'MR-'.$receive_details->receive_id:'', 
                    'expiry_date'       => ($receive_details)?$receive_details->expiry_date:'', 
                    'barcode'           => ($receive_details)?$receive_details->barcode:'', 
                    'receive_avail_qty' => ($receive_details)?$receive_details->avail_qty:0,  
                    'receive_details_id'=> $receive_details_id, 
                    'receive_type'      => $receive_type, 
                );
                $result2 = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_02_Update('wh_machine_request_details', $data2, $where);

                $where2 = array('sub_store_id'=> $sub_store_id,'item_id'=> $item_id );
                $affectedRows2 = $this->bdtaskt1m12c13_01_item_approveModel->bdtaskt1m12_05_increaseStock($aqty, $where2);

                if( !$affectedRows2 && $aqty >0 ){
                    $data3 = array('sub_store_id'=> $sub_store_id,'item_id'=> $item_id,'stock' => $aqty,'stock_in' => $aqty );
                    $result3 = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_01_Insert('wh_machine_stock',$data3);
                }

                $where3 = array('store_id'=> empty($receive_details->store_id)?0:$receive_details->store_id,'item_id'=> $item_id );
                $affectedRows3 = $this->bdtaskt1m12c13_01_item_approveModel->bdtaskt1m12_06_decreaseStock($aqty, $where3);

                $where4 = array('id'=> $receive_details_id );
                $affectedRows4 = $this->bdtaskt1m12c13_01_item_approveModel->bdtaskt1m12_07_decreaseReceiveStock($aqty, $where4, $receive_type);
            }

            // Store log data
            $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','request']), get_phrases(['approved']), $id, 'wh_machine_requests');

        } else if($action=='collect'){
            
            $item_counter    = $this->request->getVar('item_counter');
            
            for($i=1; $i<=$item_counter; $i++){

                $where11 = array('request_id'=> $id, 'item_id'=>$this->request->getVar('item_id'.$i), 'avail_qty <'=>$this->request->getVar('return_qty'.$i) );
                $request_details_stock = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('wh_machine_request_details', $where11);
                
                if( !empty($request_details_stock) ){
                    $item = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=> $this->request->getVar('item_id'.$i) ));
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
      
            $result = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_02_Update('wh_machine_return',$data, array('request_id'=>$id));

            $sub_store_id      = $this->request->getVar('sub_store_id');
            $main_store_id    = $this->request->getVar('main_store_id');

            //$item_counter    = $this->request->getVar('item_counter');
            
            for($i=1; $i<=$item_counter; $i++){
                
                $where2 = array('store_id'=> $main_store_id,'item_id'=> $this->request->getVar('item_id'.$i) );
                $affectedRows2 = $this->bdtaskt1m12c13_01_item_approveModel->bdtaskt1m12_04_increaseStock($this->request->getVar('return_qty'.$i), $where2);

                $where3 = array('id'=> $this->request->getVar('receive_details_id'.$i) );
                $affectedRows3 = $this->bdtaskt1m12c13_01_item_approveModel->bdtaskt1m12_05_updateStock($this->request->getVar('return_qty'.$i), $where3);

                $where4 = array('request_id'=> $id,'item_id'=> $this->request->getVar('item_id'.$i) );
                $affectedRows4 = $this->bdtaskt1m12c13_01_item_approveModel->bdtaskt1m12_07_decreaseStock($this->request->getVar('return_qty'.$i), $where4);

                $where5 = array('sub_store_id'=> $sub_store_id,'item_id'=> $this->request->getVar('item_id'.$i) );
                $affectedRows5 = $this->bdtaskt1m12c13_01_item_approveModel->bdtaskt1m12_08_decreaseSubStock($this->request->getVar('return_qty'.$i), $where5);
            }
            // Store log data
            $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','return']), get_phrases(['collected']), $id, 'wh_machine_return');

        } else if($action=='reject'){      
            
            $data = array(
                'isResend'          => 0, 
                'isApproved'        => 2, 
                'approved_by'       => session('id'), 
                'approved_date'     => date('Y-m-d H:i:s'), 
            );
      
            $result = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_02_Update('wh_machine_requests',$data, array('id'=>$id));
            // Store log data
            $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','request']), get_phrases(['rejected']), $id, 'wh_machine_requests');
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
                'message'  => get_phrases(['something', 'want', 'wrong']),
                'title'    => $MesTitle
            );
        }
        
        echo json_encode($response);
    }

    /*--------------------------
    | Get item_approve by ID
    *--------------------------*/
    public function bdtaskt1m12c13_04_getItemApproveById($id)
    { 
        $data = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=>$id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get item details by ID
    *--------------------------*/
    public function bdtaskt1m12c13_05_getItemApproveDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c13_01_item_approveModel->bdtaskt1m12_03_getItemApproveDetailsById($id);
        echo json_encode($data);
    }

   
    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c13_06_getItemPricingDetailsById()
    { 
        $request_id = $this->request->getVar('request_id');

        $html = '<table class="table table-bordered w-100"><tr><th class="text-center">'.get_phrases(['item']).'</th><th class="text-center">'.get_phrases(['required','quantity']).'</th><th class="text-center">'.get_phrases(['batch']).'</th><th class="text-center">'.get_phrases(['available','stock']).'</th><th class="text-center">'.get_phrases(['approve','quantity']).'</th><th class="text-center">'.get_phrases(['unit']).'</th></tr>';

        $request = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('wh_machine_requests', array('id'=>$request_id));
        $request_details = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_machine_request_details', array('request_id'=>$request_id));

        if( empty($request_details) ){
            $html .= '<tr><td colspan="6" align="center" class="text-danger">No data found!</td></tr>';
        }
        $item_counter = 1;
        foreach($request_details as $details)
        {
            $item_row = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=>$details->item_id));
            if( empty($item_row) ){
                $html .= '<tr><td colspan="6" align="center" class="text-danger">Raw material not found!</td></tr>';
            } else {
                $unit_row = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));

                $batch_info = $this->bdtaskt1m12c13_01_item_approveModel->bdtaskt1m12_06_getItemStockDetailsById($details->item_id);
                $batch_hidden = '';
                $batch_option = '<option value="" selected>Select Batch</option>';
                foreach($batch_info as $batch){
                    $avail_qty = $batch->avail_qty;//number_format(, 2, '.', '');
                    if( $avail_qty <0 ){
                        $avail_qty = 0;
                    }

                    $expiry_date = implode("/", array_reverse(explode("-", $batch->expiry_date)));
                    $expiry_date_new = implode("", explode("-", $batch->expiry_date));
                    $today_date = date('Ymd');

                    $batch_option .='<option value="rec_'.$batch->id.'">'.$batch->voucher_no.' > '.$batch->batch_no.' > '.number_format($avail_qty, 2).' '.(($batch->expiry_date =='')?'':(($today_date > $expiry_date_new)?'Expired':'Exp. '.$expiry_date)).'</option>';
                    $batch_hidden .='<input type="hidden" id="receive_avail_qty_rec_'.$batch->id.'" value="'.$avail_qty.'">';
                    
                }           
                            
                $stock_value = 0;
                $aqty = 0;

                $html .= '<tr>
                            <td width="35%" class="valign-middle">'.$item_row->nameE.' ('.$item_row->item_code.')<input type="hidden" name="item_id'.$item_counter.'" id="item_id'.$item_counter.'" value="'.$details->item_id.'"></td>
                            <td width="15%" align="right" class="valign-middle"><input type="text" name="qty'.$item_counter.'" id="qty'.$item_counter.'" class="form-control text-right" value="'.$details->qty.'" readonly></td>
                            <td width="15%" class="valign-middle" align="right"><select name="receive_details_id'.$item_counter.'" id="receive_details_id'.$item_counter.'" onchange="change_avail_qty(this.value, '.$item_counter.')" class="form-control custom-select" required>'.$batch_option.'</select>'.$batch_hidden.'</td>
                            <td width="15%" class="valign-middle" align="right"><input type="text" name="avail_qty'.$item_counter.'" id="avail_qty'.$item_counter.'" class="form-control text-right" value="'.$stock_value.'" readonly></td>
                            <td width="15%"><input type="text" name="aqty'.$item_counter.'" id="aqty'.$item_counter.'" class="form-control text-right onlyNumber" value="'.$aqty.'" onkeyup="check_quantity_exceed('.$item_counter.')" required></td>
                            <td width="5%" class="valign-middle">'.(($unit_row)?$unit_row->nameE:'').'</td>
                        </tr>';//'.(($stock_value >0)?'':'readonly').'
            }
            $item_counter++;
        }
        $html .= '</table><input type="hidden" name="item_counter" id="item_counter" value="'.($item_counter-1).'">';

        echo $html;
    }

    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c13_07_getItemCollectQuantityDetailsById()
    { 
        $request_id = $this->request->getVar('request_id');

        $html = '<table class="table table-bordered w-100"><tr><th class="text-center">'.get_phrases(['item']).'</th><th class="text-center">'.get_phrases(['requested','quantity']).'</th><th class="text-center">'.get_phrases(['approved','quantity']).'</th><th class="text-center">'.get_phrases(['returned','quantity']).'</th><th class="text-center">'.get_phrases(['unit']).'</th><th class="text-center">'.get_phrases(['batch']).'</th></tr>';

        $request = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('wh_machine_return', array('request_id'=>$request_id));
        $request_details = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_machine_return_details', array('return_id'=>$request->id));

        $item_counter = 1;
        foreach($request_details as $details)
        {
            $item_row = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=>$details->item_id));
            $unit_row = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
            
            $html .= '<tr>
                        <td width="30%">'.$item_row->nameE.' ('.$item_row->item_code.')<input type="hidden" name="item_id'.$item_counter.'" id="item_id'.$item_counter.'" value="'.$details->item_id.'"></td>
                        <td width="15%" align="right">'.number_format($details->qty, 2).'</td>
                        <td width="15%" align="right">'.number_format($details->aqty, 2).'</td>
                        <td width="15%" align="right">'.number_format($details->return_qty, 2).'<input type="hidden" name="return_qty'.$item_counter.'" id="return_qty'.$item_counter.'" value="'.$details->return_qty.'"></td>
                        <td width="10%" >'.(($unit_row)?$unit_row->nameE:'').'</td>
                        <td width="15%">'.$details->batch_no.'<input type="hidden" name="receive_details_id'.$item_counter.'" id="receive_details_id'.$item_counter.'" value="'.$details->receive_details_id.'"></td>
                    </tr>';//'.(($stock_value >0)?'':'readonly').'

            $item_counter++;
        }
        $html .= '</table><input type="hidden" name="item_counter" id="item_counter" value="'.($item_counter-1).'">';

        echo $html;
    }
    /*--------------------------
    | Get item details by ID
    *--------------------------*/
    public function bdtaskt1m12c9_05_getItemRequestDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c13_01_item_approveModel->bdtaskt1m12_03_getItemRequestDetailsById($id);
        echo json_encode($data);
    }

    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c9_06_getItemPricingDetailsById()
    { 
        $request_id = $this->request->getVar('request_id');

        $html = '<table class="table table-bordered w-100"><tr><th class="text-center">'.get_phrases(['item']).'</th><th class="text-center">'.get_phrases(['required','quantity']).'</th><th class="text-center">'.get_phrases(['approved','quantity']).'</th><th class="text-center">'.get_phrases(['unit']).'</th><th class="text-center">'.get_phrases(['batch']).'</th></tr>';

        $request_details = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_machine_request_details', array('request_id'=>$request_id));

        if( empty($request_details) ){
            $html .= '<tr><td colspan="5" align="center" class="text-danger">No data found!</td></tr>';
        }
        $item_counter = 1;
        foreach($request_details as $details)
        {
            $item_row = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=>$details->item_id));
            if( empty($item_row) ){
                $html .= '<tr><td colspan="5" align="center" class="text-danger">Raw material not found!</td></tr>';
            } else {
                $unit_row = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
                            
                $html .= '<tr>
                            <td width="45%">'.$item_row->nameE.' ('.$item_row->item_code.')
                                <input type="hidden" name="item_id'.$item_counter.'" id="item_id'.$item_counter.'" value="'.$details->item_id.'">
                                <input type="hidden" name="receive_details_id'.$item_counter.'" id="receive_details_id'.$item_counter.'" value="'.$details->receive_details_id.'">
                                <input type="hidden" name="receive_type'.$item_counter.'" id="receive_type'.$item_counter.'" value="'.$details->receive_type.'">
                                <input type="hidden" name="aqty'.$item_counter.'" id="aqty'.$item_counter.'" value="'.$details->aqty.'">
                            </td>
                            <td width="15%" align="right">'.number_format($details->qty, 2).'</td>
                            <td width="15%" align="right">'.number_format($details->aqty, 2).'</td>
                            <td width="10%">'.(($unit_row)?$unit_row->nameE:'').'</td>
                            <td width="15%">'.(empty($details->batch_no)?'':strtoupper($details->receive_type).' > ').$details->batch_no.'</td>
                        </tr>';
            }
            $item_counter++;
        }
        $html .= '</table><input type="hidden" name="item_counter" id="item_counter" value="'.($item_counter-1).'">';

        echo $html;
    }
}
