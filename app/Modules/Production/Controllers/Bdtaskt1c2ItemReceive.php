<?php namespace App\Modules\Production\Controllers;
use App\Modules\Production\Views;
use CodeIgniter\Controller;
use App\Modules\Production\Models\Bdtaskt1ItemReceiveModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1c2ItemReceive extends BaseController
{
    private $bdtaskt1m12c12_01_item_receiveModel;
    private $bdtaskt1m12c12_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c12_01_item_receiveModel = new Bdtaskt1ItemReceiveModel();
        $this->bdtaskt1m12c12_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['plan','list']);
        $data['moduleTitle']= get_phrases(['production']);
        $data['isDTables']  = true;
        $data['module']     = "Production";
        $data['page']       = "item_receive/list";
        $data['setting']    = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('setting');
        
        $data['hasPrintAccess']         = $this->permission->method('production_entry', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('production_entry', 'export')->access();

        $data['store_list']         = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_production_store', array('status'=>1));
        $data['bag_store_list']     = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_store', array('status'=>1));
        $data['machine_list']       = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_machine_store', array('status'=>1));
        $data['item_list']          = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('status'=>1));
        $data['production_list']          = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_production', array('status'=>1));
        
        $data['vat']       = get_setting('default_vat');
        /*$store_list       = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_production_store', array('status'=>1,'branch_id'=>session('branchId')));
        $data['store_id']       = ($store_list)?$store_list->id:0;*/

        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get item_receive info
    *--------------------------*/
    public function bdtaskt1m12c12_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c12_01_item_receiveModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete item_receive by ID
    *--------------------------*/
    public function bdtaskt1m12c12_02_deleteItemReceive($id)
    { 
        $data = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_06_Deleted('wh_production', array('id'=>$id));
        $data = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_06_Deleted('wh_production_details', array('production_id'=>$id));

        $MesTitle = get_phrases(['receive', 'record']);
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
    | Add item_receive info
    *--------------------------*/
    public function bdtaskt1m12c12_03_addItemReceive()
    { 

        //$old_image = $this->request->getVar('old_image'); 
        $action = $this->request->getVar('action');
        $production_id = $this->request->getVar('id');


        if($action=='receive'){
            $voucher_no = 'PROD-'.getMAXID('wh_receive', 'id');
        } else {
            $voucher_no = 'GRETI-'.getMAXID('wh_return', 'id');
        }

        $prod_date = $this->date_db_format($this->request->getVar('date'));

        $data = array(
            'production_id'     => $this->request->getVar('id'), 
            'store_id'          => $this->request->getVar('store_id'), 
            'machine_id'        => $this->request->getVar('machine_id'), 
            'voucher_no'        => $voucher_no, 
            'date'              => $prod_date, 
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'id'            => 'required|numeric',
                'store_id'      => 'required|numeric',
                'machine_id'    => 'required|numeric',
            ];
        }
        $MesTitle = get_phrases(['production', 'receive']);
        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            //Receive
            if($action=='receive'){

                $exists = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_production', array('id'=>$production_id, 'received'=>1, 'status'=>1));
                $exists2 = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_receive', array('production_id'=>$production_id, 'status'=>1));

                $MesTitle = get_phrases(['production', 'receive']);
                if(!empty($exists) || !empty($exists2)){
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['already', 'received']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);
                    exit;
                }

                $item_counter    = $this->request->getVar('item_counter');
                $quantity_check = 1;
                for($i=1; $i<=$item_counter; $i++){
                    $quantity = $this->request->getVar('qty'.$i);
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
                for($i=1; $i<=$item_counter; $i++){
                    $item_id = $this->request->getVar('item_id'.$i);
                    $bag = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_bag', array('finish_goods'=>$item_id, 'status'=>1 ));
                    if( empty($bag)  ){
                        $response = array(
                                'success'  => false,
                                'message'  => get_phrases(['bag', 'not', 'found']),
                                'title'    => $MesTitle
                            );
                        echo json_encode($response);
                        exit;
                    }
                }

                $isExist = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_receive', array('voucher_no'=>$voucher_no ));
                if( !empty($isExist)  ){
                    $response = array(
                            'success'  => 'exist',
                            'message'  => get_phrases(['already', 'exists']),
                            'title'    => $MesTitle
                        );
                    echo json_encode($response);
                    exit;
                }else{
                    /*if(!empty($this->request->getFile('receive_invoice'))){
                        $data['receive_invoice'] = $this->bdtaskt1c1_01_fileUpload->doUpload('/assets/dist/img/receive_invoice', $this->request->getFile('receive_invoice'));
                    }*/

                    $data['created_by']         = session('id');
                    $data['created_at']         = date('Y-m-d H:i:s');
                    $data['isApproved']       = 1;
                    $data['approved_by']      = session('id');
                    $data['approved_date']    = date('Y-m-d H:i:s');
                    

                    $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('wh_receive', $data);

                    //$mresult = 0;
                    $bresult = 0;
                    if($result){

                        $result4 = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_02_Update('wh_production',array('received'=>1), array('id'=>$production_id));

                        /*
                        $voucher_no = 'CONS-'.getMAXID('wh_machine_requests', 'id');
                        $mdata = array(
                            'sub_store_id'          => $this->request->getVar('machine_id'), 
                            'production_id'         => $production_id, 
                            'voucher_no'            => $voucher_no, 
                            'date'                  => $prod_date, 
                            //'notes'                 => $this->request->getVar('notes'), 
                            'request_by'            => session('id'), 
                            'request_date'          => date('Y-m-d H:i:s')
                        );
                        $mresult = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('wh_machine_requests', $mdata);*/

                        $voucher_no = 'BGR-'.getMAXID('wh_bag_requests', 'id');
                        $bdata = array(
                            'sub_store_id'          => $this->request->getVar('machine_id'), 
                            'production_id'         => $result, 
                            'voucher_no'            => $voucher_no, 
                            'date'                  => $prod_date, 
                            //'notes'                 => $this->request->getVar('notes'), 
                            'request_by'            => session('id'), 
                            'request_date'          => date('Y-m-d H:i:s')
                        );

                        $bresult = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('wh_bag_requests', $bdata);
                        
                    }

                    $item_counter    = $this->request->getVar('item_counter'); 
                    $store_id = $this->request->getVar('store_id');                   

                    $data2 = array();
                    //$bdata2 = array();
                    $need_approval = 0;
                    $stock_amount = 0;

                    for($i=1; $i<=$item_counter; $i++){
                        $item_id        = $this->request->getVar('item_id'.$i);
                        $bag_size       = $this->request->getVar('bag_size'.$i);
                        $act_bags       = $this->request->getVar('act_bags'.$i);
                        $org_qty        = $this->request->getVar('org_qty'.$i);
                        $act_bags       = $this->request->getVar('act_bags'.$i);
                        $wip_kg         = $this->request->getVar('wip_kg'.$i);
                        $price          = $this->request->getVar('price'.$i);
                        $expiry_date    = $this->request->getVar('expiry_date'.$i);
                        $batch_no       = $this->request->getVar('batch_no'.$i);
                        $qty            = $this->request->getVar('qty'.$i);
                        $loss_kg        = $this->request->getVar('loss_kg'.$i);


                        $barcode = $item_id.$result;

                        if($batch_no == ''){
                            $batch_no = 'BTC-'.$item_id.$production_id;
                        }

                        $recipe_details = $this->bdtaskt1m12c12_01_item_receiveModel->bdtaskt1m12_10_getItemProductionItemDetailsById($production_id, $item_id);
                        $input_kg = 0;
                        foreach($recipe_details as $recipe){
                            $input_kg += $recipe->qty;
                        }

                        $prod_percent = 0;
                        if( $input_kg >0 ){
                            $prod_percent = ( empty($qty)?0:$qty / $input_kg ) * 100;
                        }
                        $loss_percent = 0;
                        if( $input_kg >0 ){
                            $loss_percent = ( empty($loss_kg)?0:$loss_kg / $input_kg ) * 100;
                        }

                        $data2[] = array(
                            'receive_id'    => $result,
                            'production_id' => $production_id,
                            'store_id'      => $store_id,
                            'item_id'       => $item_id, 
                            'bag_size'      => $bag_size, 
                            'input_kg'      => $input_kg, 
                            'act_bags'      => $act_bags, 
                            'stand_bags'    => $org_qty, 
                            'qty'           => $qty, 
                            'avail_qty'     => $act_bags, 
                            'wip_kg'        => $wip_kg, 
                            'loss_kg'       => $loss_kg, 
                            'prod_percent'  => $prod_percent, 
                            'loss_percent'  => $loss_percent, 
                            'batch_no'      => $batch_no, 
                            'price'         => $price, 
                            'expiry_date'   => $this->date_db_format($expiry_date), 
                            'barcode'       => $barcode, 
                            'prod_date'     => $prod_date 
                        );
                        //,'old_feed'=> $this->request->getVar('old_feed'.$i), 'wastage'=> $this->request->getVar('wastage'.$i)

                        if($bresult){
                            //$bag = $this->bdtaskt1m12c12_01_item_receiveModel->bdtaskt1m12_10_getBagItemDetailsById($production_id, $item_id);

                            $bag = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_bag', array('finish_goods'=>$item_id, 'status'=>1 ));
                            $bdata2[] = array('request_id'=> $bresult, 'item_id'=> empty($bag)?0:$bag->id, 'qty'=> $act_bags );
                        }
                        
                        $where = array('store_id'=> $store_id,'item_id'=> $item_id );
                        $affectedRows = $this->bdtaskt1m12c12_01_item_receiveModel->bdtaskt1m12_05_increaseStock($act_bags, $where);

                        if( $affectedRows ==0 && $act_bags >0 ){
                            $data3 = array('store_id'=> $store_id,'item_id'=> $item_id, 'stock'=> $act_bags, 'stock_in'=> $act_bags );
                            $result3 = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('wh_production_stock',$data3);
                        }
                        $stock_amount += floatval($act_bags) * floatval($price);
                    }
                    if(!empty($data2)){
                        $result2 = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_receive_details',$data2);
                    }
                    /*if($mresult){
                        $mdata2 = array();

                        $recipe_details = $this->bdtaskt1m12c12_01_item_receiveModel->bdtaskt1m12_10_getItemProductionItemDetailsById($production_id);
                        foreach($recipe_details as $recipe){

                            $mdata2[] = array('request_id'=> $mresult, 'item_id'=> $recipe->item_id, 'qty'=> $recipe->qty );
                        }
                        if(!empty($mdata2)){
                            $mresult2 = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_machine_request_details',$mdata2);
                        }
                    }*/
                    if($bresult){
                        if(!empty($bdata2)){
                            $bresult2 = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_bag_request_details',$bdata2);
                        }
                    }
                    if($result){
                        // $this->bdtaskt1m12c12_09_storeTransaction($result, $store_id, $stock_amount);
                        // Store log data
                        $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['production','entry']), get_phrases(['received']), $result, 'wh_receive');
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
            }

            //Approve
            if($action=='approve'){

                $exist = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_receive', array('production_id'=>$production_id, 'isApproved'=>1));
                $existTitle = get_phrases(['receive', 'record']);

                if(!empty($exist)){
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['already', 'approved']),
                        'title'    => $existTitle
                    );
                    echo json_encode($response);
                    exit;
                }

                $data = array();
                $data['isApproved']       = 1;
                $data['approved_by']      = session('id');
                $data['approved_date']    = date('Y-m-d H:i:s');

                $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_02_Update('wh_receive', $data, array('production_id'=>$production_id));

                $item_counter    = $this->request->getVar('item_counter'); 
                $store_id = $this->request->getVar('store_id');                   

                for($i=1; $i<=$item_counter; $i++){
                    $item_id = $this->request->getVar('item_id'.$i);
                    $qty = $this->request->getVar('qty'.$i);
                    $bag_size = $this->request->getVar('bag_size'.$i);
                    $bags = 0;
                    if($bag_size >0){
                        $bags = $qty/$bag_size;
                    }
                    $where = array('store_id'=> $store_id,'item_id'=> $item_id );
                    $affectedRows = $this->bdtaskt1m12c12_01_item_receiveModel->bdtaskt1m12_05_increaseStock($bags, $where);

                    if( $affectedRows ==0 && $qty >0 ){
                        $data3 = array('store_id'=> $store_id,'item_id'=> $item_id, 'stock'=> $bags, 'stock_in'=> $bags );
                        $result3 = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('wh_production_stock',$data3);
                    }
                   
                }

                if($result){
                    // Store log data
                    $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','receive']), get_phrases(['approved']), $result, 'wh_receive');
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
                
            }
            
        }
        
        echo json_encode($response);
    }

    /*--------------------------
    | Get item_receive by ID
    *--------------------------*/
    public function bdtaskt1m12c12_04_getItemReceiveById($id)
    { 
        //$data = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_production', array('id'=>$id));
        $data = $this->bdtaskt1m12c12_01_item_receiveModel->bdtaskt1m12_03_getItemReceiveDetailsById($id);
        echo json_encode($data);
    }

    /*--------------------------
    | Get item details by ID
    *--------------------------*/
    public function bdtaskt1m12c12_05_getItemReceiveDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c12_01_item_receiveModel->bdtaskt1m12_03_getItemReceiveDetailsById($id);
        echo json_encode($data);
    }

   
    /*--------------------------
    | Get production details by ID
    *--------------------------*/
    public function bdtaskt1m12c12_07_getItemPurchaseDetailsById()
    { 
        $production_id = $this->request->getVar('production_id');
        $action = $this->request->getVar('action');

        $html = '';

        $production_details = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_production_details', array('production_id'=>$production_id));
        
        //$item_list= $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('status'=>1)); 

        $item_counter = 1;
        foreach($production_details as $details)
        {
            $item_row = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_items', array('id'=>$details->item_id));
            //$unit_row = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
            $receive_details = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_receive_details', array('production_id'=>$production_id,'item_id'=>$details->item_id));

            $avail_qty      = ($action=='return')?($receive_details->avail_qty - $receive_details->adjust_in)*$receive_details->bag_size:0;
            $receive_qty    = ($action=='receive')?$details->qty:$receive_details->qty;
            $batch_no       = ($action=='receive')?'BTC-'.$details->item_id.$production_id:$receive_details->batch_no;
            $wip_kg         = ($action=='receive')?'':$receive_details->wip_kg;
            $loss_kg        = ($action=='receive')?'':$receive_details->loss_kg;
            $price          = ($action=='receive')?'':$receive_details->price;
            $expiry_date    = ($action=='receive')?'':$receive_details->expiry_date;
            $bag_size       = ($action=='receive')?$details->bag_size:$receive_details->bag_size;
            $act_bags       = ($action=='receive')?$details->act_bags:$receive_details->act_bags;            
            
            if( $avail_qty <0 ){
                $avail_qty = 0;
            }

            $html .= '<tr>
                        <td>'.$item_row->nameE.' ('.$item_row->company_code.')<input type="hidden" name="item_id'.$item_counter.'" id="item_id'.$item_counter.'" value="'.$details->item_id.'"></td>
                        <td class="return_info text-right">'.$receive_qty.'<input type="hidden" name="receive_qty'.$item_counter.'" id="receive_qty'.$item_counter.'" value="'.$receive_qty.'"></td>
                        <td class="return_info">'.$avail_qty.'<input type="hidden" name="avail_qty'.$item_counter.'" id="avail_qty'.$item_counter.'" value="'.$avail_qty.'"></td>
                        <td class="text-right">'.$bag_size.'<input type="hidden" name="bag_size'.$item_counter.'" id="bag_size'.$item_counter.'" value="'.$bag_size.'"></td>
                        <td class="text-right">'.number_format($details->bag_size*$details->act_bags, 2).'</td>
                        <td class="text-right"><input type="text" name="act_bags'.$item_counter.'" id="act_bags'.$item_counter.'" value="'.number_format($act_bags,0).'" onkeyup="bag_cal('.$item_counter.')" class="form-control onlyNumber text-right" '.(($action=='receive')?'required':'readonly').'></td>
                        <td class="text-right"><input type="text" name="qty'.$item_counter.'" id="qty'.$item_counter.'" value="'.$receive_qty.'" class="form-control onlyNumber text-right" readonly><input type="hidden" name="org_qty'.$item_counter.'" id="org_qty'.$item_counter.'" value="'.number_format($details->act_bags,0).'" ></td>
                        <td class="text-right"><input type="text" name="wip_kg'.$item_counter.'" id="wip_kg'.$item_counter.'" value="'.$wip_kg.'" class="form-control onlyNumber text-right" onkeyup="bag_cal('.$item_counter.')" '.(($action=='receive')?'required':'readonly').'></td>
                        <td class="text-right"><input type="text" name="loss_kg'.$item_counter.'" id="loss_kg'.$item_counter.'" value="'.$loss_kg.'" class="form-control onlyNumber text-right" readonly></td>
                        <td><input type="text" name="price'.$item_counter.'" id="price'.$item_counter.'" class="form-control text-right" value="'.$price.'" '.(($action=='receive')?'required':'readonly').'></td>
                        <td><input type="text" name="batch_no'.$item_counter.'" id="batch_no'.$item_counter.'" class="form-control" value="'.$batch_no.'" readonly></td>
                        <td><input type="date" name="expiry_date'.$item_counter.'" id="expiry_date'.$item_counter.'" class="form-control" value="'.$expiry_date.'" '.(($item_row->has_expiry)?'required':'').' '.(($action=='receive')?'':'readonly').'></td>
                        
                    </tr>';

            $item_counter++;
        }
        $html .= '<input type="hidden" name="item_counter" id="item_counter" value="'.($item_counter-1).'">';
        echo $html;
    }


    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c12_08_getItemPurchasePricingDetailsById()
    { 
        $production_id = $this->request->getVar('production_id');
        $machine_id = $this->request->getVar('machine_id');

        $html = '<table class="table table-bordered w-100"><tr><th class="text-center">'.get_phrases(['item']).'</th><th class="text-right">'.get_phrases(['bag','size']).'</th><th class="text-right">'.get_phrases(['bags']).'</th><th class="text-right">'.get_phrases(['output']).' KG</th><th class="text-center">'.get_phrases(['recipe']).'</th></tr>';

        $production_details = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_production_details', array('production_id'=>$production_id));

        foreach($production_details as $details)
        {
            $item_row = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_items', array('id'=>$details->item_id));     
            $recipe_row = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_recipe', array('id'=>$details->recipe_id));             
            //$unit_row = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
            
            $html .= '<tr>
                        <td width="40%" align="center">'.$item_row->nameE.' ('.$item_row->company_code.')</td>
                        <td width="15%" align="right">'.(($details)?$details->bag_size:0).'</td>
                        <td width="15%" align="right">'.(($details)?$details->act_bags:0).'</td>
                        <td width="15%" align="right">'.(($details)?$details->qty:0).'</td>
                        <td width="15%" align="center">'.(($recipe_row)?$recipe_row->voucher_no:'').'</td>
                    </tr>';
        }
        $html .= '</table>';

        echo $html;
    }

    public function bdtaskt1m12c12_09_storeTransaction($id, $store_id, $amount)
    {
        $debit = $amount;
        $credit = 0;

        $store_row = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_production_store', array('id'=>$store_id));
        
        if( !empty($store_row->acc_head) ){
            $data = array(
                'VNo'         => 'PROD-'.$id,
                'Vtype'       => 'PROD',
                'VDate'       => date('Y-m-d'),
                'COAID'       => $store_row->acc_head,
                'Narration'   => 'Amount in Store, Name: '.$store_row->nameE,
                'Debit'       => $debit,
                'Credit'      => $credit,
                'PatientID'   => 0,
                'BranchID'    => session('branchId'),
                'IsPosted'    => 1,
                'CreateBy'    => session('id'),
                'CreateDate'  => date('Y-m-d H:i:s'),
                'IsAppove'    => 1
            ); 
            $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction', $data);
        }

        return 1;
    }

    
}
