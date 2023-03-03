<?php namespace App\Modules\Assets_purchase\Controllers;
use App\Modules\Assets_purchase\Views;
use CodeIgniter\Controller;
use App\Modules\Assets_purchase\Models\Bdtaskt1m12RequisitionModel;
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
    public function index()
    {
        $data['moduleTitle']        = get_phrases(['assets', 'purchase']);
        $data['title']              = get_phrases(['SPR']);
        $data['isDTables']          = true;
        $data['module']             = "Assets_purchase";
        $data['page']               = "requisition/list";

        $data['hasCreateAccess']       = $this->permission->method('wh_bag_requisition', 'create')->access();
        $data['hasPrintAccess']        = $this->permission->method('wh_bag_requisition', 'print')->access();
        $data['hasExportAccess']       = $this->permission->method('wh_bag_requisition', 'export')->access();
        
        $data['supplier_list']    = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_supplier_information', array('status'=>1));
        $data['item_list']        = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_assets', array('status'=>1));
       
        $data['store_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_store', array('status'=>1,'branch_id'=>session('branchId')));
        
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
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_requisition', array('id'=>$id, 'isApproved'=>1));
        $data_q = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_quatation', array('requisition_id'=>$id));
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
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('wh_bag_requisition', array('id'=>$id));
        $data2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('wh_bag_requisition_details', array('purchase_id'=>$id));

        // Store log data
        $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['deleted']), $id, 'wh_bag_requisition');
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
        $data = array();
        // $data = array(
        //     'sub_total'         => $this->request->getVar('sub_total'), 
        //     'grand_total'       => $this->request->getVar('grand_total'), 
        //     'vat'               => $this->request->getVar('vat'),
            
        // );

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
                $voucher_no     = 'SPR-'.getMAXID('wh_bag_requisition', 'id');
                
                $isExist = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_requisition', array('voucher_no'=>$voucher_no) );
                if( !empty($isExist)  ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    $data['type']           = 2;
                    $data['voucher_no']     = $voucher_no;
                    $data['date']           = $this->date_db_format($this->request->getVar('date'));
                    $data['created_by']     = session('id');
                    $data['created_at']     = date('Y-m-d H:i:s');

                    $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert('wh_bag_requisition',$data);

                    $item_id    = $this->request->getVar('item_id');
                    $store      = $this->request->getVar('store');
                    $qty        = $this->request->getVar('qty');
                    $monthly_consumption = $this->request->getVar('monthly_consumption') ? $this->request->getVar('monthly_consumption') : 0;
                    $last_purchase_qnty = $this->request->getVar('last_purchase_qnty');
                    $last_purchase_date = $this->request->getVar('last_purchase_date');
                    $main_stock = $this->request->getVar('main_stock');

                    $data2 = array();
                    foreach($item_id as $key => $item){
                        $data2[] = array('purchase_id'=> $result,'item_id'=> $item, 'qty'=> $qty[$key], 'monthly_consumption'=> ($monthly_consumption[$key] ? $monthly_consumption[$key] : 0), 'last_purchase_qnty'=> ($last_purchase_qnty[$key] ? $last_purchase_qnty[$key] : 0), 'last_purchase_date'=> $last_purchase_date[$key], 'last_stock_qnty'=> $main_stock[$key] );

                    }
                    $result2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_bag_requisition_details',$data2);

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

                $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('wh_bag_requisition',$data, array('id'=>$id));

                $item_id    = $this->request->getVar('item_id');
                
                $store        = $this->request->getVar('store');
                $qty        = $this->request->getVar('qty');
                $monthly_consumption= $this->request->getVar('monthly_consumption');
                $last_purchase_qnty= $this->request->getVar('last_purchase_qnty');
                $last_purchase_date= $this->request->getVar('last_purchase_date');
                $main_stock= $this->request->getVar('main_stock');

                $data2 = array();
                foreach($item_id as $key => $item){
                    $data2[] = array('purchase_id'=> $id,'item_id'=> $item, 'qty'=> $qty[$key], 'monthly_consumption'=> $monthly_consumption[$key], 'last_purchase_qnty'=> $last_purchase_qnty[$key], 'last_purchase_date'=> $last_purchase_date[$key], 'last_stock_qnty'=> $main_stock[$key] );

                }

                $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('wh_bag_requisition_details', array('purchase_id'=>$id));
                $result2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_bag_requisition_details',$data2);

                // Store log data
                $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['updated']), $id, 'wh_bag_requisition');

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
    public function bdtaskt1m12c10_07_requisition_approve(){

        $id = $this->request->getVar('id');
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_requisition', array('id'=>$id, 'isApproved'=>1));

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

        $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('wh_bag_requisition',$data, array('id'=>$id));
        
        // Store log data
        $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['approved']), $id, 'wh_bag_requisition');

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
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_assets', array('id'=>$id));
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

        $store_list       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_store', array('status'=>1,'branch_id'=>session('branchId')));
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
        $item_list = $this->bdtaskt1m12c10_01_requisitionModel->bdtaskt1m12_05_get_item_list();
        $html = '<option value="">Select</option>';
        foreach($item_list as $items){
            $html .='<option value="'.$items->id.'">'.$items->nameE.'</option>';
        }
        echo $html;
   }
    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c10_06_getRequisitionPricingDetailsById()
    { 
        $purchase_id = $this->request->getVar('purchase_id');
        $supplier_id = $this->request->getVar('supplier_id');

        $html = '<table class="table table-bordered w-100"><tr><th>'.get_phrases(['item']).'</th><th class="text-right">'.get_phrases(['request','quantity']).'</th><th class="text-center">'.get_phrases(['unit']).'</th><th class="text-center">'.get_phrases(['last', 'receive', 'date']).'</th><th  class="text-right">'.get_phrases(['last', 'receive', 'quantity']).'</th><th  class="text-right">'.get_phrases(['present', 'stock']).'</th><th  class="text-right">'.get_phrases(['monthly', 'consumption']).'</th><th>'.get_phrases(['where', 'use']).'</th></tr>';


        $purchases = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_requisition', array('id'=>$purchase_id));
        $purchases_details = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_requisition_details', array('purchase_id'=>$purchase_id));

        foreach($purchases_details as $details)
        {
            $item_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_assets', array('id'=>$details->item_id));            
            $unit_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
            


            $html .= '<tr>
                        <td width="15%">'.$item_row->nameE.'</td>
                        <td width="10%" align="right">'.number_format(($details)?$details->qty:0, 2).'</td>
                        <td width="5%" align="center">'.(($unit_row)?$unit_row->nameE:'').'</td>
                        <td width="10%" align="right">'.(($details)?$details->last_purchase_date:'').'</td>
                        <td width="10%" align="right">'.number_format(($details)?$details->last_purchase_qnty:0, 2).'</td>
                        <td width="10%" align="right">'.number_format(($details)?$details->last_stock_qnty:0, 2).'</td>
                        <td width="10%" align="right">'.number_format(($details)?$details->monthly_consumption:0, 2).'</td>
                        <td width="10%">'.''.'</td>
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

        $data = $this->bdtaskt1m12c10_01_requisitionModel->bdtaskt1m12_07_getPurchaseOrderItemDetailsById($purchase_id, $supplier_id);
        echo json_encode($data);
    }
   
}
