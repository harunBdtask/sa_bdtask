<?php namespace App\Modules\Foreign_purchase\Controllers;
use App\Modules\Foreign_purchase\Views;
use CodeIgniter\Controller;
use App\Modules\Foreign_purchase\Models\Bdtaskt1m12ItemReceiveModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class BdtaskttItemReceive extends BaseController
{
    private $bdtaskt1m12c12_01_item_receiveModel;
    private $bdtaskt1m12c12_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c12_01_item_receiveModel = new Bdtaskt1m12ItemReceiveModel();
        $this->bdtaskt1m12c12_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['material', 'receive']);
        $data['moduleTitle']= get_phrases(['purchase']);
        $data['isDTables']  = true;
        $data['module']     = "Foreign_purchase";
        $data['page']       = "item_receive/list";

        $data['hasCreateAccess']        = $this->permission->method('wh_foreign_purchase_receive', 'create')->access();
        $data['hasPrintAccess']         = $this->permission->method('wh_foreign_purchase_receive', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('wh_foreign_purchase_receive', 'export')->access();
        
        $data['store_list']             = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_04_getResult('ah_material_store');
        $data['supplier_list']          = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_05_getResultWhere('ah_supplier_information', array('status'=>1));
        
        $data['item_list']              = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_05_getResultWhere('ah_material', array('status'=>1));
        $data['payment_method_list']    = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_05_getResultWhere('list_data', array('list_id'=>16, 'status'=>1));
        
        $data['vat']        = get_setting('default_vat');

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
        $data = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_06_Deleted('wh_material_requisition', array('id'=>$id));
        $data = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_06_Deleted('wh_material_requisition_details', array('purchase_id'=>$id));

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
    | Add item_purchase info
    *--------------------------*/
    public function bdtaskt1m12c12_03_addItemPurchase()
    { 

        $action = $this->request->getVar('action');
        $data = array(
            'store_id'      => $this->request->getVar('store_id'), 
            'supplier_id'   => $this->request->getVar('supplier_id'), 
            'voucher_no'        => $this->request->getVar('voucher_no'), 
            'date'              => $this->date_db_format($this->request->getVar('date')), 
            'grand_total'       => $this->request->getVar('grand_total'), 
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'voucher_no'    => 'required|min_length[4]|max_length[20]',
                'grand_total'   => 'required|numeric',
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
            if($action=='add'){
                $isExist = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_material_requisition', array('voucher_no'=>$this->request->getVar('voucher_no')));
                
                if( !empty($isExist)  ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('wh_material_requisition',$data);

                    $item_id    = $this->request->getVar('item_id');
                    $qty        = $this->request->getVar('qty');
                    $price      = $this->request->getVar('price');
                    $total      = $this->request->getVar('total');

                    $data2 = array();
                    foreach($item_id as $key => $item){
                        $data2[] = array('purchase_id'=> $result,'item_id'=> $item, 'qty'=> $qty[$key], 'price'=> $price[$key], 'total'=> $total[$key] );
                    }

                    $result2 = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_material_requisition_details',$data2);

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
                $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_02_Update('wh_material_requisition',$data, array('id'=>$id));
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

    /*--------------------------
    | Add item_receive info
    *--------------------------*/
    public function bdtaskt1m12c12_03_addItemReceive()
    { 

        $action = $this->request->getVar('action');
        $purchase_id = $this->request->getVar('id');

        $voucher_no ='';
        if($action=='receive'){
            $voucher_no = 'RVC-'.getMAXID('ah_material_receive', 'rv_id');
        } else {
            //$voucher_no = 'GRETI-'.getMAXID('wh_material_return', 'id');
        }

        $data = array(
            'po_id'   => $this->request->getVar('id'),  
            'voucher_no'    => $voucher_no, 
            'date'          => $this->date_db_format($this->request->getVar('date')), 
            'truck_no'      => $this->request->getVar('truck_no'),
            'chalan_no'     => $this->request->getVar('chalan_no'),
            'gr_no'         => $this->request->getVar('gr_no'),
        );
        $rules ='';
        if ($this->request->getMethod() == 'post') {

            $rules = [
                'supplier_id'   => 'required|numeric',
                'date'          => 'required',
                'truck_no'      => 'required',
                'chalan_no'     => 'required',
                'gr_no'         => 'required',
            ];
        }

        $MesTitle = get_phrases(['receive', 'record']);

        if (! $this->validate(@$rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            
            //Receive
            if($action=='receive'){
                
                $exists = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('ah_po', array('row_id'=>$purchase_id, 'received'=>1));

                $MesTitle = get_phrases(['receive', 'record']);

                if(!empty($exists)){
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['already', 'received']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);
                    exit;
                }

                $item_counter   = $this->request->getVar('item_counter');
                $sub_total_qty  = $this->request->getVar('sub_total_qty');
                $is_received    = $this->request->getVar('is_received');
                $qty_check = 1;

                for($i=1; $i<=$item_counter; $i++){

                    $quantity = $this->request->getVar('qty'.$i);
                    $avail_qty = $this->request->getVar('avail_qty'.$i);
                    
                    if( $quantity > $avail_qty || $quantity < 0 ){
                        $qty_check = 0;
                    }
                    
                }

                if(empty($sub_total_qty)){

                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['required','quantity']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);
                    exit;
                }
                 
                if($qty_check == 0){
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['invalid','quantity']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);
                    exit;
                } 
                

                $isExist = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('ah_material_receive', array('voucher_no'=>$voucher_no ));
                
                if( !empty($isExist)  ){

                    $response = array(
                        'success'  =>'exist',
                        'message'  => get_phrases(['already', 'exists']),
                        'title'    => $MesTitle
                    );

                }else{

                    $scale_attachment  = $this->bdtaskt1c1_01_fileUpload->doUpload('/assets/dist/material/item_receive', $this->request->getFile('scale_attachment'));
                    $chalan_attachment = $this->bdtaskt1c1_01_fileUpload->doUpload('/assets/dist/material/item_receive', $this->request->getFile('chalan_attachment'));

                    $data['scale_attachment']   = $scale_attachment;
                    $data['chalan_attachment']  = $chalan_attachment;
                    $data['created_by']         = session('id');
                    $data['created_at']         = date('Y-m-d H:i:s');

                    $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('ah_material_receive', $data);

                    if($result){

                        if($is_received == 1){
                            $received = 1;
                        }else{
                            $received = 2;
                        }
                        $result4 = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_02_Update('ah_po',array('received'=>$received), array('row_id'=>$purchase_id));
                    
                    }

                    $data2 = array();
                    $need_approval = 0;

                    for($i=1; $i<=$item_counter; $i++){
                        
                        $item_id = $this->request->getVar('item_id'.$i);
                        $quantity = $this->request->getVar('qty'.$i);
                        $storeid = $this->request->getVar('store_id'.$i);

                        if (empty($quantity)) {
                            $quantity = 0;
                        }
                        $remarks = $this->request->getVar('remarks'.$i);
                        if (empty($remarks)) {
                            $remarks = '';
                        }
                        $qc_attachment = $this->bdtaskt1c1_01_fileUpload->doUpload('/assets/dist/material/item_receive', $this->request->getFile('qc_attachment'.$i));
                        if (empty($qc_attachment)) {
                            $qc_attachment = null;
                        }

                        
                        if ($quantity > 0) {
                            $data2[] = array('store_id'=> $storeid,'rv_id'=> $result,'po_id'=> $purchase_id,'item_id'=> $item_id, 'qty'=> $quantity, 'avail_qty'=> $quantity, 'remarks'=> $remarks, 'qc_attachment'=> $qc_attachment );
                            
                            $where = array('store_id'=> $storeid,'item_id'=> $item_id );
                            $wh_material_stock = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_material_stock', $where);
                            if (!empty($wh_material_stock)) {
                                $stock = $wh_material_stock->stock;
                            }else{
                                $stock = 0;
                            }
                            $affectedRows = $this->bdtaskt1m12c12_01_item_receiveModel->bdtaskt1m12_04_updateStock($quantity, $where);
                            
                            $data3 = array('receive_id'=> $result,'store_id'=> $storeid,'item_id'=> $item_id, 'stock'=> ($stock), 'stock_in'=> $quantity );
                            $data3['created_by']         = session('id');
                            $data3['created_at']         = date('Y-m-d H:i:s');
                            $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('wh_material_stock_entry',$data3);

                            if( $affectedRows == 0 ){
                                $data4 = array('store_id'=> $storeid,'item_id'=> $item_id, 'stock'=> $quantity, 'stock_in'=> $quantity );
                                $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('wh_material_stock',$data4);
                            }
                        }

                    }


                    $result2 = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert_Batch('ah_material_receive_details',$data2);

                    
                    if($result){
                        // Store log data
                        $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','receive']), get_phrases(['received']), $result, 'ah_material_receive');
    
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

            //Return
            if($action=='return'){

                $exist = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_material_requisition', array('id'=>$purchase_id, 'returned'=>1));
                $existTitle = get_phrases(['return', 'record']);

                if(!empty($exist)){
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['already', 'returned']),
                        'title'    => $existTitle
                    );
                    echo json_encode($response);
                    exit;
                }

                $item_counter    = $this->request->getVar('item_counter');
                $return_qty_check = 1;
                $stock_check = 0;
                for($i=1; $i<=$item_counter; $i++){
                    $return_qty = $this->request->getVar('qty'.$i);
                    if( $return_qty >0 ){
                        $return_qty_check = 0;

                        $stock_data = $this->bdtaskt1m12c12_01_item_receiveModel->bdtaskt1m12_08_checkAvailQty($purchase_id, $this->request->getVar('item_id'.$i), $return_qty );
                        if(!empty($stock_data)){
                            $stock_check = $this->request->getVar('item_id'.$i);
                            break;
                        }
                    }
                }
                if($return_qty_check){
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['quantity', 'is','required']),
                        'title'    => $existTitle
                    );
                    echo json_encode($response);
                    exit;
                } 
                if($stock_check){
                    $item_row = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=>$stock_check));
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['stock', 'not','available']),
                        'title'    => (!empty($item_row))?$item_row->nameE:$existTitle
                    );
                    echo json_encode($response);
                    exit;
                } 

                $isExist = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_material_return', array('voucher_no'=>$voucher_no ));
                if( !empty($isExist)  ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    $data['return_by']          = session('id');
                    $data['created_at']         = date('Y-m-d H:i:s');

                    $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('wh_material_return', $data);
                    if($result){
                        $result4 = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_02_Update('wh_material_requisition',array('returned'=>1), array('id'=>$purchase_id));
                    }

                    $item_counter    = $this->request->getVar('item_counter'); 
                    $store_id = $this->request->getVar('store_id');                   

                    $data2 = array();
                    for($i=1; $i<=$item_counter; $i++){
                        $data2[] = array('purchase_id'=> $purchase_id,'return_id'=> $result,'item_id'=> $this->request->getVar('item_id'.$i), 'carton'=> $this->request->getVar('carton'.$i), 'carton_qty'=> $this->request->getVar('carton_qty'.$i), 'box'=> $this->request->getVar('box'.$i), 'box_qty'=> $this->request->getVar('box_qty'.$i), 'qty'=> $this->request->getVar('qty'.$i), 'price'=> $this->request->getVar('price'.$i), 'total'=> $this->request->getVar('total'.$i) );

                        $where = array('store_id'=> $store_id,'item_id'=> $this->request->getVar('item_id'.$i) );
                        $affectedRows = $this->bdtaskt1m12c12_01_item_receiveModel->bdtaskt1m12_06_decreaseStock($this->request->getVar('qty'.$i), $where);

                        if( $affectedRows >0 ){
                            $where3 = array('purchase_id'=> $purchase_id, 'item_id'=> $this->request->getVar('item_id'.$i) );
                            $result3 = $this->bdtaskt1m12c12_01_item_receiveModel->bdtaskt1m12_04_updateReturn($this->request->getVar('qty'.$i),$where3);
                        }
                    }

                    $result2 = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_material_return_details',$data2);


                    $supplier_id    = $this->request->getVar('supplier_id');
                    $grand_total        = $this->request->getVar('grand_total');
                    $vat                = $this->request->getVar('vat');

                    $this->bdtaskt1m12_09_supplierTransaction($result, $supplier_id, $grand_total, '', 'GRETI', $vat);

                    // Store log data
                    $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['supplier','return']), get_phrases(['returned']), $result, 'wh_material_return');
                    
                    if($result){
                         $response = array(
                            'success'  =>true,
                            'message'  => get_phrases(['returned', 'successfully']),
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

                $exist = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_material_receive', array('purchase_id'=>$purchase_id, 'isApproved'=>1));
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

                $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_02_Update('wh_material_receive', $data, array('purchase_id'=>$purchase_id));

                $item_counter    = $this->request->getVar('item_counter'); 
                $store_id = $this->request->getVar('store_id');                   

                for($i=1; $i<=$item_counter; $i++){
                    $where = array('store_id'=> $store_id,'item_id'=> $this->request->getVar('item_id'.$i) );
                    $affectedRows = $this->bdtaskt1m12c12_01_item_receiveModel->bdtaskt1m12_05_increaseStock($this->request->getVar('qty'.$i), $where);

                    if( $affectedRows ==0 ){
                        $data3 = array('store_id'=> $store_id,'item_id'=> $this->request->getVar('item_id'.$i), 'stock'=> $this->request->getVar('qty'.$i), 'stock_in'=> $this->request->getVar('qty'.$i) );
                        $result3 = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('wh_material_stock',$data3);
                    }
                   
                }
                // Store log data
                $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','receive']), get_phrases(['approved']), $result, 'wh_material_receive');

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
                
            }
            
        }
        
        echo json_encode($response);
    }

    /*--------------------------
    | Get item_receive by ID
    *--------------------------*/
    public function bdtaskt1m12c12_04_getItemReceiveById($id)
    { 
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
    | Get purchase details by ID
    *--------------------------*/
    public function bdtaskt1m12c12_07_getItemPurchaseDetailsById()
    { 
        $purchase_id = $this->request->getVar('purchase_id');
        $action = $this->request->getVar('action');


        $store_list             = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_04_getResult('ah_material_store');
        $selectstor = '';
        if(!empty($store_list)){ 
            foreach ($store_list as $key => $value) {
                $selectstor .='<option value="'.$value->id.'">'.$value->nameE.'</option>';
            }
        }


        $html = '';

        $purchases_details = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_05_getResultWhere('ah_po_details', array('po_id'=>$purchase_id));
        
        $item_counter = 1;
        foreach($purchases_details as $details)
        {
            $item_row = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('ah_material', array('id'=>$details->po_item_id));

            if (!empty($item_row)) {

                $unit_row = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
                $receive_details = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('ah_material_receive_details', array('po_id'=>$purchase_id,'item_id'=>$details->po_item_id));
                $receive_sum = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getSumRow('ah_material_receive_details', 'qty', array('po_id'=>$purchase_id,'item_id'=>$details->po_item_id));
                
                if (!empty($receive_sum)) {
                    $receive_qty    = $receive_sum->qty;
                    $avail_qty      = $details->po_item_qty - $receive_sum->qty;
                }else{
                    $receive_qty    = 0;
                    $avail_qty      = $details->qty;
                }
               
                if( $avail_qty < 0 ){
                    $avail_qty = 0;
                }

                if ($avail_qty != 0) {
                    
                    $html .= '<tr>
                                <td>'.$item_row->nameE.' ('.$item_row->item_code.')<input type="hidden" name="item_id'.$item_counter.'" id="item_id'.$item_counter.'" value="'.$details->po_item_id.'"></td>
                                <td class="valign-middle">'.(($unit_row)?$unit_row->nameE:'').'</td>
                                <td align="right">'.$details->po_item_qty.'</td>
                                <td align="right">'.$receive_qty.'</td>
                                <td align="right">'.$avail_qty.'</td>
                                <td align="right"><input type="text" name="qty'.$item_counter.'" id="qty'.$item_counter.'" onkeyup="po_calculation('.$item_counter.')" class="form-control text-right"/></td>
                                <td><input type="text" name="remarks'.$item_counter.'" id="remarks'.$item_counter.'" class="form-control"/></td>
                                <td>
                                <select name="store_id'.$item_counter.'" id="store_id'.$item_counter.'" class="custom-select form-control">
                                    <option value="">'.$selectstor.'</option>
                                </select>
                                </td>
                                <td><input type="file" name="qc_attachment'.$item_counter.'" id="qc_attachment'.$item_counter.'" class="form-control"/></td>
                                
                                <input type="hidden" name="po_avail_qty[]'.$item_counter.'" id="po_avail_qty'.$item_counter.'" class="po_subtotal">
                                <input type="hidden" name="po_total_qty[]'.$item_counter.'" id="po_total_qty'.$item_counter.'" class="po_total_qty">
                                <input type="hidden" name="avail_qty'.$item_counter.'" id="avail_qty'.$item_counter.'" value="'.$avail_qty.'">
                            </tr>';
                }

                $item_counter++;
            }else{
                $html .='<tr></tr>';
            }    

            
        }
        $html .= '<input type="hidden" name="item_counter" id="item_counter" value="'.($item_counter-1).'">';
        $html .= '<input type="hidden" name="is_received" id="is_received">';
        $html .= '<input type="hidden" name="sub_total" id="po_sub_total">';
        $html .= '<input type="hidden" name="sub_total_qty" id="po_sub_total_qty">';
        echo $html;
    }


    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c12_08_getItemPurchasePricingDetailsById()
    { 
        $purchase_id = $this->request->getVar('purchase_id');

        $html = '<table class="table table-bordered w-100"><tr><th>'.get_phrases(['SL']).'</th><th>'.get_phrases(['item']).'</th><th class="text-right">'.get_phrases(['purchase','quantity']).'</th><th class="text-center">'.get_phrases(['unit']).'</th></tr>';

        $purchases_details = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_05_getResultWhere('ah_po_details', array('po_id'=>$purchase_id));

        $sl = 0;
        foreach($purchases_details as $details)
        {
            $sl++;
            $item_row = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('ah_material', array('id'=>$details->po_item_id));  
            if (!empty($item_row)) {
                $unit_row = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
                $html .= '<tr>
                        <td width="5%">'.$sl.'</td>
                        <td width="20%">'.$item_row->nameE.' ('.$item_row->item_code.')</td>
                        <td width="10%" align="right">'.number_format(($details)?$details->po_item_qty:0, 2).'</td>
                        <td width="5%" align="center">'.(($unit_row)?$unit_row->nameE:'').'</td>
                        <td width="10%" align="right">'.number_format(($details)?$details->price:0,2).'</td>
                    </tr>';
            }else{
                $html .='<tr></tr>';
            }      
            
        }
        $html .= '</table>';

        echo $html;
    }

    public function bdtaskt1m12_09_supplierTransaction($id, $supplier_id, $amount, $payment_method_id, $voucher_type, $vat_amount=0){

        $supplier_row = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_supplier_information', array('id'=>$supplier_id));

        if($voucher_type == 'MR' && $payment_method_id ==''){
            $particulars = 'Goods Received';

            $debit = $amount - $vat_amount;
            $credit = 0;

            $vat_debit = $vat_amount;
            $vat_credit = 0;

            $sup_debit = 0;
            $sup_credit = $amount;
            
        } else if($voucher_type == 'GRETI' && $payment_method_id ==''){

            $particulars = 'Goods Returned';

            $debit = 0;
            $credit = $amount - $vat_amount;

            $vat_debit = 0;
            $vat_credit = $vat_amount;

            $sup_debit = $amount;
            $sup_credit = 0;

        } else if( $payment_method_id !=''){

            $particulars = 'Goods Received Payment';

            $debit = 0;
            $credit = 0;

            $vat_debit = 0;
            $vat_credit = 0;

            $sup_debit = 0;
            $sup_credit = $amount;

        }

        if( $debit >0 || $credit >0 ){
            $data = array(
                'VNo'         => $voucher_type.'-'.$id,
                'Vtype'       => $voucher_type,
                'VDate'       => date('Y-m-d'),
                'COAID'       => '124000001',//Closing Stock
                'Narration'   => $particulars.', Supplier Name: '.$supplier_row->nameE,
                'Debit'       => $debit,
                'Credit'      => $credit,
                'PatientID'   => 0,
                'BranchID'     => session('branchId'),
                'IsPosted'    => 1,
                'CreateBy'    => session('id'),
                'CreateDate'  => date('Y-m-d H:i:s'),
                'IsAppove'    => 1
            ); 
            $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction', $data);
        }

        if( $vat_debit >0 || $vat_credit >0 ){
            $data = array(
                'VNo'         => $voucher_type.'-'.$id,
                'Vtype'       => $voucher_type,
                'VDate'       => date('Y-m-d'),
                'COAID'       => '222000002',//VAT Output Final (VO)
                'Narration'   => $particulars.' VAT, Supplier Name: '.$supplier_row->nameE,
                'Debit'       => $vat_debit,
                'Credit'      => $vat_credit,
                'PatientID'   => 0,
                'BranchID'     => session('branchId'),
                'IsPosted'    => 1,
                'CreateBy'    => session('id'),
                'CreateDate'  => date('Y-m-d H:i:s'),
                'IsAppove'    => 1
            ); 
            $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction', $data);
        }

        if($supplier_row->acc_head =='' ){
            $maxCoa = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_18_getLikeMaxData('acc_coa', '2211','HeadCode');
            $Coa = ((int)$maxCoa + 1);
            $coaData = [
                'HeadCode'         => $Coa,
                'HeadName'         => $supplier_row->nameE,
                'PHeadName'        => 'Suppliers',
                'nameE'            => $supplier_row->nameE,
                'nameA'            => $supplier_row->nameA,
                'HeadLevel'        => '4',
                'IsActive'         => '1',
                'IsTransaction'    => '1',
                'IsGL'             => '0',
                'HeadType'         => 'L',
                'IsBudget'         => '0',
                'IsDepreciation'   => '0',
                'DepreciationRate' => '0',
                'CreateBy'         => session('id'),
                'CreateDate'       => date('Y-m-d H:i:s'),
            ]; 
            $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('acc_coa',$coaData);
            $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_02_Update('wh_supplier_information',array('acc_head'=>$Coa), array('id'=>$supplier_id));

        }

        $data = array(
            'VNo'         => $voucher_type.'-'.$id,
            'Vtype'       => $voucher_type,
            'VDate'       => date('Y-m-d'),
            'COAID'       => ($supplier_row->acc_head =='' )? $Coa : $supplier_row->acc_head,
            'Narration'   => $particulars.', Supplier Name: '.$supplier_row->nameE,
            'Debit'       => $sup_debit,
            'Credit'      => $sup_credit,
            'PatientID'   => 0,
            'BranchID'     => session('branchId'),
            'IsPosted'    => 1,
            'CreateBy'    => session('id'),
            'CreateDate'  => date('Y-m-d H:i:s'),
            'IsAppove'    => 1
        ); 
        $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction', $data);

        if($payment_method_id != ''){

            $payment_method = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$payment_method_id,'list_id'=>16));
            $payment_method_name = (!empty($payment_method))?$payment_method->nameE:'';

            if($payment_method_id == '120'){//Cash
                $acc_head = '121100001';
            } else if($payment_method_id == '121'){//Span
                $acc_head = '121100002';
            } else if($payment_method_id == '122'){//Visa Card
                $acc_head = '121100004';
            } else if($payment_method_id == '123'){//Master Card
                $acc_head = '121100003';
            } else if($payment_method_id == '124'){//Amex Card
                $acc_head = '121100005';
            } else if($payment_method_id == '125'){//Bank Transfer
                $acc_head = '121100006';
            } else if($payment_method_id == '126'){//Credit Card
                $acc_head = '121100011';
            }

            $data = array(
                'VNo'         => $voucher_type.'-'.$id,
                'Vtype'       => $voucher_type,
                'VDate'       => date('Y-m-d'),
                'COAID'       => $acc_head,
                'Narration'   => $payment_method_name.' Payment to Supplier, Name: '.$supplier_row->nameE,
                'Debit'       => $amount,
                'Credit'      => 0,
                'PatientID'   => 0,
                'BranchID'    => session('branchId'),
                'IsPosted'    => 1,
                'CreateBy'    => session('id'),
                'CreateDate'  => date('Y-m-d H:i:s'),
                'IsAppove'    => 1
            ); 
            $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction', $data);

        }
        return $result;
    }
}
