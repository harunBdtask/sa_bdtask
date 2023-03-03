<?php namespace App\Modules\Purchase\Controllers;
use App\Modules\Purchase\Views;
use CodeIgniter\Controller;
use App\Modules\Purchase\Models\Bdtaskt1m12RequisitionModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1m12c10Requisition extends BaseController
{
    private $bdtaskt1m12c10_01_requisitionModel;
    private $bdtaskt1m12c10_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c10_01_requisitionModel = new Bdtaskt1m12RequisitionModel();
        $this->bdtaskt1m12c10_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function quotation_cs_index()
    {
        $data['title']              = get_phrases(['quotation', 'CS']);
        $data['moduleTitle']        = get_phrases(['local','purchase']);
        $data['isDTables']          = true;
        $data['module']             = "Purchase";
        $data['page']               = "quotation_cs/list";
        $data['setting']            = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('setting');

        $data['hasCreateAccess']        = $this->permission->method('wh_material_requisition', 'create')->access();
        $data['hasPrintAccess']        = $this->permission->method('wh_material_requisition', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('wh_material_requisition', 'export')->access();
        
        $data['supplier_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_supplier_information', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material', array('status'=>1));
       
        $data['store_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_store', array('status'=>1));
        $data['spr_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_requisition', array('status'=>1));
        
        
        $data['vat']       = get_setting('default_vat');
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    public function index()
    {
        $data['title']              = get_phrases(['store', 'purchase','requisition']);
        $data['moduleTitle']        = get_phrases(['local','purchase']);
        $data['isDTables']          = true;
        $data['module']             = "Purchase";
        $data['page']               = "requisition/list";
        $data['setting']            = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('setting');

        $data['hasCreateAccess']        = $this->permission->method('wh_material_requisition', 'create')->access();
        $data['hasPrintAccess']        = $this->permission->method('wh_material_requisition', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('wh_material_requisition', 'export')->access();
        
        $data['supplier_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_supplier_information', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material', array('status'=>1));
       
        $data['store_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_store', array('status'=>1));
        $data['spr_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_requisition', array('status'=>1));
 
        
        $data['vat']       = get_setting('default_vat');
        
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get purchase_order info
    *--------------------------*/
    public function bdtaskt1m12c10_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c10_01_requisitionModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete purchase_order by ID
    *--------------------------*/
    public function bdtaskt1m12c10_02_deletePurchaseOrder($id)
    { 
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material_requisition', array('id'=>$id, 'isApproved'=>1));
        $data_q = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_quatation', array('requisition_id'=>$id));
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
        if(!empty($data_q)){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['using', 'quatation']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('wh_material_requisition', array('id'=>$id));
        $data2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('wh_material_requisition_details', array('purchase_id'=>$id));

        // Store log data
        $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['deleted']), $id, 'wh_material_requisition');
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

        $action = $this->request->getVar('action');

        $data = array(
            'store_id'      => $this->request->getVar('store_id'),  
            'sub_total'         => $this->request->getVar('sub_total'), 
            'grand_total'       => $this->request->getVar('grand_total'), 
            'vat'               => $this->request->getVar('vat'),
            
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'date'      => 'required',
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
                $voucher_no     = 'SPR-'.getMAXID('wh_material_requisition', 'id');
                
                $isExist = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material_requisition', array('voucher_no'=>$voucher_no) );
                if( !empty($isExist)  ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    $data['voucher_no']     = $voucher_no;
                    $data['date']           = $this->date_db_format($this->request->getVar('date'));
                    $data['created_by']     = session('id');
                    $data['created_at']     = date('Y-m-d H:i:s');

                    $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert('wh_material_requisition',$data);

                    $item_id    = $this->request->getVar('item_id');
                    $carton     = '';
                    $carton_qty = '';
                    $box_qty    = '';
                    $box        = '';
                    $store      = ($this->request->getVar('store')?$this->request->getVar('store'):0);
                    $qty        = $this->request->getVar('qty');
                    $price      = '';
                    $org_price  = $this->request->getVar('org_price');
                    $total      = $this->request->getVar('total');
                    $vat_applicable= $this->request->getVar('vat_applicable');
                    $monthly_consumption= ($this->request->getVar('monthly_consumption')?$this->request->getVar('monthly_consumption'):0);
                    $last_purchase_qnty= ($this->request->getVar('last_purchase_qnty')?$this->request->getVar('last_purchase_qnty'):0);
                    $last_purchase_date= ($this->request->getVar('last_purchase_date')?$this->request->getVar('last_purchase_date'):null);
                    $main_stock= ($this->request->getVar('main_stock')?$this->request->getVar('main_stock'):0);

                    $data2 = array();
                    foreach($item_id as $key => $item){
                        $data2[] = array('purchase_id'=> $result,'item_id'=> $item, 'qty'=> $qty[$key], 'store_id'=> $store[$key], 'monthly_consumption'=> ($monthly_consumption[$key]?$monthly_consumption[$key]:0), 'last_purchase_qnty'=> ($last_purchase_qnty[$key]?$last_purchase_qnty[$key]:0), 'last_purchase_date'=> ($last_purchase_date[$key]?$last_purchase_date[$key]:null), 'last_stock_qnty'=> ($main_stock[$key]?$main_stock[$key]:0) );

                    }
                    $result2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_material_requisition_details',$data2);

                    // Store log data
                    $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['created']), $result, 'wh_material_requisition');
                    

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

                $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('wh_material_requisition',$data, array('id'=>$id));

                $item_id    = $this->request->getVar('item_id');
                
                $store        = $this->request->getVar('store');
                $qty        = $this->request->getVar('qty');
                $monthly_consumption= $this->request->getVar('monthly_consumption');
                $last_purchase_qnty= $this->request->getVar('last_purchase_qnty');
                $last_purchase_date= $this->request->getVar('last_purchase_date');
                $main_stock= $this->request->getVar('main_stock');

                $data2 = array();
                foreach($item_id as $key => $item){
                    $data2[] = array('purchase_id'=> $id,'item_id'=> $item, 'qty'=> $qty[$key], 'store_id'=> $store[$key], 'monthly_consumption'=> ($monthly_consumption[$key]?$monthly_consumption[$key]:0), 'last_purchase_qnty'=> ($last_purchase_qnty[$key]?$last_purchase_qnty[$key]:0), 'last_purchase_date'=> ($last_purchase_date[$key]?$last_purchase_date[$key]:null), 'last_stock_qnty'=> ($main_stock[$key]?$main_stock[$key]:0) );

                }

                $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('wh_material_requisition_details', array('purchase_id'=>$id));
                $result2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_material_requisition_details',$data2);

                // Store log data
                $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['updated']), $id, 'wh_material_requisition');

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
    public function bdtaskt1m12c10_07_approvePurchaseOrder(){

        $id = $this->request->getVar('id');
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material_requisition', array('id'=>$id, 'isApproved'=>1));

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

        $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('wh_material_requisition',$data, array('id'=>$id));
        
        // Store log data
        $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['approved']), $id, 'wh_material_requisition');

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
        $data = $this->bdtaskt1m12c10_01_requisitionModel->bdtaskt1m12_03_getPurchaseOrderDetailsById($id);
        echo json_encode($data);
    }

    /*--------------------------
    | Get supplier item details by ID
    *--------------------------*/
    public function bdtaskt1m12c10_09_getSupplierItemDetailsById()
    { 
        $item_id = $this->request->getVar('item_id');
        $supplier_id = $this->request->getVar('supplier_id');

        $data = $this->bdtaskt1m12c10_01_requisitionModel->bdtaskt1m12_06_getSupplierItemDetailsById($item_id, $supplier_id);

        $store_list       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material_store', array('status'=>1,'branch_id'=>session('branchId')));
        $store_id       = ($store_list)?$store_list->id:0;

        $data['main_stock'] = 0;
        $data['sub_stock'] = 0;
        if( $store_id >0 ){
            $data['main_stock'] = $this->bdtaskt1m12c10_01_requisitionModel->bdtaskt1m12_08_getWarehouseItemStock($item_id, $store_id);
            $data['sub_stock'] = $this->bdtaskt1m12c10_01_requisitionModel->bdtaskt1m12_09_getSubWarehouseItemStock($item_id, session('branchId'));
        }

        echo json_encode($data);
    }

   
    /*--------------------------
    | Get item list by supplier ID
    *--------------------------*/
    public function bdtaskt1m12c10_08_get_item_list()
    { 
        $supplier_id = $this->request->getVar('supplier_id');
        $item_list = $this->bdtaskt1m12c10_01_requisitionModel->bdtaskt1m12_05_get_item_list($supplier_id);
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
        $supplier_id = $this->request->getVar('supplier_id');

        $html = '<table class="table table-bordered w-100"><tr><th>'.get_phrases(['raw', 'material', 'name']).'</th><th class="text-right">'.get_phrases(['request','quantity']).'</th><th class="text-center">'.get_phrases(['unit']).'</th><th class="text-center">'.get_phrases(['last', 'receive', 'date']).'</th><th  class="text-right">'.get_phrases(['last', 'receive', 'quantity']).'</th><th  class="text-right">'.get_phrases(['present', 'stock']).'</th><th  class="text-right">'.get_phrases(['monthly', 'consumption']).'</th><th>'.get_phrases(['where', 'use']).'</th></tr>';


        $purchases = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material_requisition', array('id'=>$purchase_id));
        $purchases_details = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_requisition_details', array('purchase_id'=>$purchase_id));

        foreach($purchases_details as $details)
        {
            $item_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=>$details->item_id));            
            $unit_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
            $where_use = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->where_use));
            
            $items_supplier = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material_supplier', array('supplier_id'=>$supplier_id,'item_id'=>$details->item_id));

            $main_stock = $this->bdtaskt1m12c10_01_requisitionModel->bdtaskt1m12_08_getWarehouseItemStock($details->item_id, $purchases->store_id);
            $sub_stock = $this->bdtaskt1m12c10_01_requisitionModel->bdtaskt1m12_09_getSubWarehouseItemStock($details->item_id, session('branchId'));

            $used30 = $this->bdtaskt1m12c10_01_requisitionModel->bdtaskt1m12_10_getConsumed30($details->item_id, session('branchId'));
            $mfs30 = ($used30 >0)?number_format(($main_stock+$sub_stock)/$used30, 2):0;
            $used90 = $this->bdtaskt1m12c10_01_requisitionModel->bdtaskt1m12_11_getConsumed90($details->item_id, session('branchId'));
            $mfs90 = ($used90 >0)?number_format(($main_stock+$sub_stock)/$used90, 2):0;

            $html .= '<tr>
                        <td width="15%">'.$item_row->nameE.' ('.$item_row->item_code.')</td>
                        <td width="10%" align="right">'.number_format(($details)?$details->qty:0, 2).'</td>
                        <td width="5%" align="center">'.(($unit_row)?$unit_row->nameE:'').'</td>
                        <td width="10%" align="center">'.(($details->last_purchase_date != '0000-00-00')?$details->last_purchase_date:'NA').'</td>
                        <td width="10%" align="right">'.number_format(($details)?$details->last_purchase_qnty:0, 2).'</td>
                        <td width="10%" align="right">'.number_format(($details)?$details->last_stock_qnty:0, 2).'</td>
                        <td width="10%" align="right">'.number_format(($details)?$details->monthly_consumption:0, 2).'</td>
                        <td width="10%">'.(($where_use)?$where_use->nameE:'').'</td>
                    </tr>';
        }
        $html .= '</table>';

        echo $html;
    }

    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c10_07_getRequisitionItemDetailsById()
    { 
        $purchase_id = $this->request->getVar('purchase_id');
        $supplier_id = $this->request->getVar('supplier_id');

        $data = $this->bdtaskt1m12c10_01_requisitionModel->bdtaskt1m12_07_getPurchaseOrderItemDetailsById($purchase_id, $supplier_id);
        echo json_encode($data);
    }


    public function bdtaskt1m12c10_12_get_quatation_cs()
    { 
        $requisition_id = $this->request->getVar('requisition_id');
        $quatation_details   = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_quatation_details', array('requisition_id '=>$requisition_id));
        
        $html = '<table class="table table-bordered w-100"><tr><th>'.get_phrases(['SL']).'</th><th class="text-right">'.get_phrases(['quotation', 'ID']).'</th><th class="text-center">'.get_phrases(['supplier','/', 'party']).'</th><th class="text-center">'.get_phrases(['material','name']).'</th><th class="text-right">'.get_phrases(['unit','price']).'</th><th class="text-right">'.get_phrases(['quantity']).'</th><th class="text-right">'.get_phrases(['previous','price']).'</th></tr>';

        $sl = 0;
        if ($quatation_details) {
            foreach($quatation_details as $details)
            {
                $sl++;
                $qty = $details->qty - $details->received_qty;
                $item_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=>$details->item_id));  
                $supplier_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_supplier_information', array('id'=>$details->supplier_id));  
                $purchase_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material_purchase', array('supplier_id'=>$details->supplier_id, 'item_id'=>$details->item_id));  
                $html .= '<tr>
                            <td width="5%">'.$sl.'</td>
                            <td width="5%" align="right">'.$details->quatation_id.'</td>
                            <td width="10%" align="center">'.$supplier_row->nameE.' ( '.$supplier_row->code_no.' ) </td>
                            <td width="10%" align="center">'.$item_row->nameE.'</td>
                            <td width="5%" align="right">'.$details->price.'</td>
                            <td width="5%" align="right">'.$details->qty.'</td>
                            <td width="5%" align="right">'.(($purchase_row)?$purchase_row->sub_total/$purchase_row->po_qty:0).'</td>
                        </tr>';
            }
            $html .= '</table>';
            
    
            echo $html;
        } else {
            $html .= '<tr><td colspan="7" align="center">No Data Found</td></tr>';
            $html .= '</table>';
    
            echo $html;
        }
    }
   
}
