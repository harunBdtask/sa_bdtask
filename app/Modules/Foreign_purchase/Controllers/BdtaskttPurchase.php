<?php namespace App\Modules\Foreign_purchase\Controllers;
use App\Modules\Foreign_purchase\Views;
use CodeIgniter\Controller;
use App\Modules\Foreign_purchase\Models\BdtaskttPurchaseOrderModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class BdtaskttPurchase extends BaseController
{
    private $bdtasktt_purchase_orderModel;
    private $bdtaskt1m12c10_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtasktt_purchase_orderModel = new BdtaskttPurchaseOrderModel();
        $this->bdtaskt1m12c10_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {

        $data['title']              = get_phrases(['purchase','order']);
        $data['moduleTitle']        = get_phrases(['purchase']);
        $data['isDTables']          = true;
        $data['module']             = "Foreign_purchase";
        $data['page']               = "purchase_order/list";

        $data['hasCreateAccess']        = $this->permission->method('wh_foreign_purchase_order', 'create')->access();
        $data['hasPrintAccess']         = $this->permission->method('wh_foreign_purchase_order', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('wh_foreign_purchase_order', 'export')->access();
        
        $data['supplier_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('ah_supplier_information', array('status'=>1));
        $data['lclist']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('ah_lc', array('status'=>0));
    
        $data['vat']       = get_setting('default_vat');
        
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get purchase_order info
    *--------------------------*/
    public function bdtasktt_ahpo_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtasktt_purchase_orderModel->bdtasktt_ahpo_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete purchase_order by ID
    *--------------------------*/
    public function bdtasktt_delete_po($id)
    { 
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('ah_po', array('row_id'=>$id, 'isApproved'=>1));

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

        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('ah_po', array('row_id'=>$id));
        $data2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('ah_po_details', array('po_id'=>$id));

        // Store log data
        $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['deleted']), $id, 'ah_po');
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
    public function bdtasktt_create_po()
    { 

        $action = $this->request->getVar('action');
        $lc_number = $this->request->getVar('lc_number');

        $data = array(
            'po_supplier_id'       => $this->request->getVar('po_supplier_id'),
            'lc_number'             => $this->request->getVar('lc_number'), 
            'po_code'               => $this->request->getVar('po_code'),
            'po_subtotal'          => $this->request->getVar('sub_total'), 
            'po_grand_total'       => $this->request->getVar('grand_total'), 
            'po_vat'               => $this->request->getVar('vat')
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'date'      => 'required',
                'po_supplier_id'      => 'required',
            ];
        }
        $MesTitle = get_phrases(['purchase', 'record']);
        if (! $this->validate($rules)) {
            $response = array(
                'success'  => false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            if($action=='add'){

                $po_code = $this->request->getVar('po_code');
                
                $isExist = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('ah_po', array('po_code'=>$po_code) );
                
                if( !empty($isExist)  ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{

                    $data['po_date']           = $this->date_db_format($this->request->getVar('date'));
                    $data['created_by']     = session('id');
                    $data['entry_date']     = date('Y-m-d');

                    $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert('ah_po',$data);

                    $lc = array('status'=> 1);
                    $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('ah_lc',$lc, array('lc_number'=>$lc_number));

                    
                    $item_id    = $this->request->getVar('item_id');
                    $qty        = $this->request->getVar('qty');
                    $price      = $this->request->getVar('price');
                    $po_vat     = $this->request->getVar('po_vat');
                    
                    $data2 = array();
                    foreach($item_id as $key => $item){
                        $data2[] = array('po_id'=> $result,'po_item_id'=> $item, 'po_item_qty'=> $qty[$key], 'price'=> $price[$key], 'po_vat'=> $po_vat[$key]);
                    }

                    $result2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert_Batch('ah_po_details',$data2);
                    // Store log data
                    $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['created']), $result, 'ah_po');
                    
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

                $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('ah_po',$data, array('row_id'=>$id));


                $item_id    = $this->request->getVar('item_id');
                $qty        = $this->request->getVar('qty');
                $price      = $this->request->getVar('price');
                $po_vat     = $this->request->getVar('po_vat');

                
                $data2 = array();
                foreach($item_id as $key => $item){
                    $data2[] = array('po_id'=> $id,'po_item_id'=> $item, 'po_item_qty'=> $qty[$key], 'price'=> $price[$key], 'po_vat'=> $po_vat[$key]);

                }
                $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('ah_po_details', array('po_id'=>$id));
                
                $result2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert_Batch('ah_po_details',$data2);

                // Store log data
                $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['updated']), $id, 'ah_po');

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
    public function bdtasktt_approvePurchaseOrder(){

        $id = $this->request->getVar('id');
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('ah_po', array('row_id'=>$id, 'isApproved'=>1));

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

        $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('ah_po',$data, array('row_id'=>$id));
        
        // Store log data
        $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['approved']), $id, 'ah_po');

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
    public function bdtaskttt_getPurchaseDetailsById($id)
    { 
        $data = $this->bdtasktt_purchase_orderModel->bdtaskt1m12_03_getPurchaseOrderDetailsById($id);
        echo json_encode($data);
    }


    

    /*--------------------------
    | Get item details by ID
    *--------------------------*/
    public function getPurchaseItemDetails()
    { 

        $id = $this->request->getVar('id');

        $html = '<table class="table table-bordered w-100"><tr><th>'.get_phrases(['SL']).'</th>
        <th>'.get_phrases(['item']).'</th>
        <th class="text-right">'.get_phrases(['purchase', 'quantity']).'</th>
        <th  class="text-right">'.get_phrases(['purchase', 'price']).'</th></tr>';

        $purchases_details = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('ah_po_details', array('po_id'=>$id));

        $sl = 0;
        foreach($purchases_details as $details)
        {
            $sl++;
            $item_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('ah_material', array('id'=>$details->po_item_id));            
            $unit_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
            
            $html .= '<tr>
                        <td width="5%">'.$sl.'</td>
                        <td width="15%">'.$item_row->nameE.' ('.$item_row->item_code.')</td>
                        <td width="10%" align="right">'.number_format(($details)?$details->po_item_qty:0, 2).'</td>
                        <td width="10%" align="right">'.number_format(($details)?$details->price:0,2).'</td>
                    </tr>';
        }
        $html .= '</table>';

        echo $html;

    }

    

    
    /*--------------------------
    | Get item details by ID
    *--------------------------*/
    public function get_purchase_item_details()
    { 

        $id = $this->request->getVar('id');
        $data = $this->bdtasktt_purchase_orderModel->bdtasktt_getPurchaseItemDetailsById($id);
        echo json_encode($data);


    }

    




    /*--------------------------
    | Get supplier item details by ID
    *--------------------------*/
    public function bdtaskt1m12c10_09_getSupplierItemDetailsById()
    { 
        $item_id = $this->request->getVar('item_id');

        $data = $this->bdtasktt_purchase_orderModel->bdtaskt1m12_06_getSupplierItemDetailsById($item_id);

        $store_list       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('ah_material_store', array('status'=>1,'branch_id'=>session('branchId')));
        $store_id       = ($store_list)?$store_list->id:0;

        $data['main_stock'] = 0;
        $data['sub_stock'] = 0;
        if( $store_id >0 ){
            $data['main_stock'] = $this->bdtasktt_purchase_orderModel->bdtaskt1m12_08_getWarehouseItemStock($item_id, $store_id);
            $data['sub_stock'] = $this->bdtasktt_purchase_orderModel->bdtaskt1m12_09_getSubWarehouseItemStock($item_id, session('branchId'));
        }

        echo json_encode($data);
    }

   
    /*--------------------------
    | Get item list
    *--------------------------*/
    public function bdtasktt_get_item_list()
    { 

        $item_list = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_04_getResult('ah_material');
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

        $data = $this->bdtasktt_purchase_orderModel->bdtaskt1m12_07_getPurchaseOrderItemDetailsById($purchase_id, $supplier_id);
        echo json_encode($data);
    }
   
    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtasktt_get_item_info()
    { 
        $item_id = $this->request->getVar('item_id');
        $data = $this->bdtasktt_purchase_orderModel->bdtasktt_get_itemdetails($item_id);

        echo json_encode($data);
    }

    
}
