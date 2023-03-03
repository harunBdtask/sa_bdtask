<?php namespace App\Modules\Production\Controllers;
use App\Modules\Production\Views;
use CodeIgniter\Controller;
use App\Modules\Production\Models\Bdtaskt1BagConsumptionModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1c2BagConsumption extends BaseController
{
    private $bdtaskt1m12c13_01_item_approveModel;
    private $bdtaskt1m12c13_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c13_01_item_approveModel = new Bdtaskt1BagConsumptionModel();
        $this->bdtaskt1m12c13_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['bag', 'request','list']);
        $data['moduleTitle']= get_phrases(['production']);
        $data['isDTables']  = true;
        $data['module']     = "Production";
        $data['page']       = "bag_consumption/list";
        $data['setting']    = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('setting');

        $data['hasPrintAccess']        = $this->permission->method('approve_bag_request', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('approve_bag_request', 'export')->access();

        $data['bag_requests_list']       = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_requests', array('status'=>1), null, null, array('id','DESC'));
        $data['production_list']       = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_receive', array('status'=>1), null, null, array('id','DESC'));
        $data['sub_store_list']       = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_machine_store', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag', array('status'=>1));
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
        $data = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_06_Deleted('wh_bag', array('id'=>$id));
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
            $data = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_requests', array('id'=>$id, 'isApproved'=>2));
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

            $data = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_requests', array('id'=>$id, 'isApproved'=>1));
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

            for($i=1; $i<=$item_counter; $i++){
                $qty = $this->request->getVar('qty'.$i);
                $aqty = $this->request->getVar('aqty'.$i);
                $avail_qty = $this->request->getVar('avail_qty'.$i);
                $item_id = $this->request->getVar('item_id'.$i);

                if($aqty > $qty || $aqty > $avail_qty ){
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['approve','quantity', 'should', 'be','less']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);
                    exit;
                } 
                if( $aqty >0 ){
                    
                    $batch_info = $this->bdtaskt1m12c13_01_item_approveModel->bdtaskt1m12_06_getItemStockDetailsById($item_id);
                    $total_avail_qty = 0;
                    foreach($batch_info as $batch){
                        $total_avail_qty += $batch->avail_qty;
                    }
                    if( $aqty > $total_avail_qty ){
                        $item_row = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('wh_bag', array('id'=> $item_id ));
                        $response = array(
                            'success'  =>false,
                            'message'  => get_phrases(['stock', 'not','available']),
                            'title'    => (!empty($item_row))?$item_row->nameE:$MesTitle
                        );
                        echo json_encode($response);
                        exit;
                    }
                } else {
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['quantity', 'is','required']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);
                    exit;                    
                }
            }
            
            $data = array(
                'isResend'          => 0, 
                'isApproved'        => 1, 
                'approved_by'       => session('id'), 
                'approved_date'     => date('Y-m-d H:i:s'), 
            );

            $result = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_02_Update('wh_bag_requests',$data, array('id'=>$id));

            $sub_store_id = $this->request->getVar('sub_store_id');
            //$item_counter    = $this->request->getVar('item_counter');
            
            for($i=1; $i<=$item_counter; $i++){

                $aqty = $this->request->getVar('aqty'.$i);
                $item_id = $this->request->getVar('item_id'.$i);

                $receive_details = $this->bdtaskt1m12c13_01_item_approveModel->bdtaskt1m12_07_decreaseReceiveStock($item_id, $aqty);

                $data2 = array(
                    'aqty'              => $aqty, 
                    'avail_qty'         => $aqty, 
                    'price'             => empty($receive_details)?0:$receive_details['price'], 
                    'batch_no'          => empty($receive_details)?'':$receive_details['batch_no'], 
                    'expiry_date'       => empty($receive_details)?'':$receive_details['expiry_date'], 
                    'barcode'           => empty($receive_details)?'':$receive_details['barcode'], 
                    'receive_details_id'=> empty($receive_details)?'':$receive_details['receive_details_id'],  
                    'receive_avail_qty' => empty($receive_details)?'':$receive_details['receive_avail_qty'], 
                    'receive_type'      => 'auto', 
                );
                $where = array('request_id'=> $id,'item_id'=> $item_id );
                $result2 = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_02_Update('wh_bag_request_details', $data2, $where);

                /*$where2 = array('sub_store_id'=> $sub_store_id,'item_id'=> $item_id );
                $affectedRows2 = $this->bdtaskt1m12c13_01_item_approveModel->bdtaskt1m12_05_increaseStock($aqty, $where2);

                if( !$affectedRows2 && $aqty >0 ){
                    $data3 = array('sub_store_id'=> $sub_store_id,'item_id'=> $item_id,'stock' => $aqty,'stock_in' => $aqty );
                    $result3 = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_01_Insert('wh_machine_stock',$data3);
                }

                $where3 = array('store_id'=> empty($receive_details->store_id)?0:$receive_details->store_id,'item_id'=> $item_id );
                $affectedRows3 = $this->bdtaskt1m12c13_01_item_approveModel->bdtaskt1m12_06_decreaseStock($aqty, $where3);

                $where4 = array('id'=> $receive_details_id );
                $affectedRows4 = $this->bdtaskt1m12c13_01_item_approveModel->bdtaskt1m12_07_decreaseReceiveStock($aqty, $where4, $receive_type);*/
            }

            // Store log data
            $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','request']), get_phrases(['approved']), $id, 'wh_bag_requests');

        } else if($action=='collect'){
            
            $item_counter    = $this->request->getVar('item_counter');
            
            for($i=1; $i<=$item_counter; $i++){

                $where11 = array('request_id'=> $id, 'item_id'=>$this->request->getVar('item_id'.$i), 'avail_qty <'=>$this->request->getVar('return_qty'.$i) );
                $request_details_stock = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_request_details', $where11);
                
                if( !empty($request_details_stock) ){
                    $item = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('wh_bag', array('id'=> $this->request->getVar('item_id'.$i) ));
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
      
            $result = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_02_Update('wh_bag_requests',$data, array('id'=>$id));
            // Store log data
            $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','request']), get_phrases(['rejected']), $id, 'wh_bag_requests');
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
        $data = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('wh_bag', array('id'=>$id));
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

        $html = '<table class="table table-bordered w-100"><tr><th>'.get_phrases(['bag','name']).'</th><th class="text-right">'.get_phrases(['request','quantity']).'</th><th class="text-right">'.get_phrases(['available','stock']).'</th><th class="text-right">'.get_phrases(['approve','quantity']).'</th><th>'.get_phrases(['unit']).'</th></tr>';/*<th class="text-center">'.get_phrases(['batch']).'</th>*/

        $request = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_requests', array('id'=>$request_id));
        $request_details = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_request_details', array('request_id'=>$request_id));

        $item_counter = 1;
        foreach($request_details as $details)
        {
            $item_row = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('wh_bag', array('id'=>$details->item_id));
            if( !empty($item_row) ){
                $unit_row = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));

                $batch_info = $this->bdtaskt1m12c13_01_item_approveModel->bdtaskt1m12_06_getItemStockDetailsById($details->item_id);
                $batch_hidden = '';
                $batch_option = '<option value="" selected>Select Batch</option>';
                $total_avail_qty = 0;
                foreach($batch_info as $batch){
                    $avail_qty = $batch->avail_qty;//number_format($batch->avail_qty, 2, '.', '');
                    if( $avail_qty <0 ){
                        $avail_qty = 0;
                    } else {
                        $total_avail_qty += $avail_qty;
                    }

                    /*$expiry_date = implode("/", array_reverse(explode("-", $batch->expiry_date)));
                    $expiry_date_new = implode("", explode("-", $batch->expiry_date));
                    $today_date = date('Ymd');

                    $batch_option .='<option value="rec_'.$batch->id.'">'.$batch->voucher_no.' > '.$batch->batch_no.' > '.$avail_qty.' '.(($batch->expiry_date =='')?'':(($today_date > $expiry_date_new)?'Expired':'Exp. '.$expiry_date)).'</option>';
                    $batch_hidden .='<input type="hidden" id="receive_avail_qty_rec_'.$batch->id.'" value="'.$avail_qty.'">';*/
                    
                }           
                            
                $stock_value = 0;                
                $aqty = $details->qty;
                if( $details->qty > $total_avail_qty){
                    $aqty = $total_avail_qty;
                }

                $html .= '<tr>
                            <td width="30%" class="valign-middle">'.$item_row->nameE.' ('.$item_row->company_code.')<input type="hidden" name="item_id'.$item_counter.'" id="item_id'.$item_counter.'" value="'.$details->item_id.'"></td>
                            <td width="20%" align="right" class="valign-middle">'.number_format($details->qty,0).'<input type="hidden" name="qty'.$item_counter.'" id="qty'.$item_counter.'" value="'.$details->qty.'"></td>
                            <td width="20%" class="valign-middle" align="right">'.number_format($total_avail_qty,0).'<input type="hidden" name="avail_qty'.$item_counter.'" id="avail_qty'.$item_counter.'" value="'.$total_avail_qty.'"></td>
                            <td width="20%" class="valign-middle" align="right">'.number_format($aqty,0).'<input type="hidden" name="aqty'.$item_counter.'" id="aqty'.$item_counter.'" value="'.$aqty.'"></td>
                            <td width="10%" class="valign-middle">'.(($unit_row)?$unit_row->nameE:'').'</td>
                        </tr>';//'.(($stock_value >0)?'':'readonly').'
                /*<td width="15%" class="valign-middle" align="right"><select name="receive_details_id'.$item_counter.'" id="receive_details_id'.$item_counter.'" onchange="change_avail_qty(this.value, '.$item_counter.')" class="form-control custom-select" required>'.$batch_option.'</select>'.$batch_hidden.'</td>*/

                $item_counter++;
            }
        }
        $html .= '</table><input type="hidden" name="item_counter" id="item_counter" value="'.($item_counter-1).'">';

        echo $html;
    }
   
    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c9_06_getItemPricingDetailsById()
    { 
        $request_id = $this->request->getVar('request_id');

        $html = '<table class="table table-bordered w-100"><tr><th>'.get_phrases(['bag','name']).'</th><th class="text-right">'.get_phrases(['request','quantity']).'</th><th class="text-right">'.get_phrases(['approved','quantity']).'</th><th>'.get_phrases(['unit']).'</th><th>'.get_phrases(['batch']).'</th></tr>';

        $request_details = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_request_details', array('request_id'=>$request_id));

        $item_counter = 1;
        foreach($request_details as $details)
        {
            $item_row = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('wh_bag', array('id'=>$details->item_id));
            if( !empty($item_row) ){
                $unit_row = $this->bdtaskt1m12c13_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
                $company_code = ($item_row->company_code?' ('.$item_row->company_code.')':' ');        
                $html .= '<tr>
                            <input type="hidden" name="item_id'.$item_counter.'" id="item_id'.$item_counter.'" value="'.$details->item_id.'">
                            <input type="hidden" name="receive_details_id'.$item_counter.'" id="receive_details_id'.$item_counter.'" value="'.$details->receive_details_id.'">
                            <input type="hidden" name="receive_type'.$item_counter.'" id="receive_type'.$item_counter.'" value="'.$details->receive_type.'">
                            <input type="hidden" name="aqty'.$item_counter.'" id="aqty'.$item_counter.'" value="'.$details->aqty.'">
                            <td width="45%">'.$item_row->nameE.$company_code.'</td>
                            <td width="15%" align="right">'.number_format($details->qty, 0).'</td>
                            <td width="15%" align="right">'.(($details->aqty >0)?number_format($details->aqty, 0):'Not yet').'</td>
                            <td width="10%">'.(($unit_row)?$unit_row->nameE:'').'</td>
                            <td width="15%">'.$details->batch_no.'</td>
                        </tr>';
                $item_counter++;
            }
        }
        $html .= '</table><input type="hidden" name="item_counter" id="item_counter" value="'.($item_counter-1).'">';

        echo $html;
    }


}
