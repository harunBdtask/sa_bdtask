<?php namespace App\Modules\Assets_purchase\Controllers;
use App\Modules\Assets_purchase\Views;
use CodeIgniter\Controller;
use App\Modules\Assets_purchase\Models\Bdtaskt1m12ItemPurchaseModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1m12c7ItemPurchase extends BaseController
{
    private $bdtaskt1m12c7_01_item_purchaseModel;
    private $bdtaskt1m12c7_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c7_01_item_purchaseModel = new Bdtaskt1m12ItemPurchaseModel();
        $this->bdtaskt1m12c7_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']              = get_phrases(['received', 'voucher']);
        $data['moduleTitle']        = get_phrases(['assets', 'purchase']);
        $data['isDTables']          = true;
        $data['module']             = "Assets_purchase";
        $data['page']               = "item_purchase/list";
        
        $data['hasPrintAccess']        = $this->permission->method('wh_bag_received_voucher', 'print')->access();
        $data['hasExportAccess']       = $this->permission->method('wh_bag_received_voucher', 'export')->access();
        
        $data['store_list']     = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_store', array('status'=>1,'branch_id'=>session('branchId')));
        $data['supplier_list']  = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_supplier_information', array('status'=>1));
               
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get item_purchase info
    *--------------------------*/
    public function bdtaskt1m12c7_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c7_01_item_purchaseModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete item_purchase by ID
    *--------------------------*/
    public function bdtaskt1m12c7_02_deleteItemPurchase($id)
    { 
        $data = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_06_Deleted('wh_assets', array('id'=>$id));
        $MesTitle = get_phrases(['purchase', 'record']);
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
    public function bdtaskt1m12c7_03_addItemPurchase()
    { 

        //$old_image = $this->request->getVar('old_image'); 
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
            //$filePath = $this->bdtaskt1c1_01_fileUpload->doUpload('./assets/dist/img/item_purchase', $this->request->getFile('image'));
            if($action=='add'){
                $isExist = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_requisition', array('voucher_no'=>$this->request->getVar('voucher_no'),'type'=>2));
                if( !empty($isExist)  ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    //$data['image'] = $filePath;
                    $result = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_01_Insert('wh_bag_requisition',$data);

                    $item_id    = $this->request->getVar('item_id');
                    $qty        = $this->request->getVar('qty');
                    $price      = $this->request->getVar('price');
                    $total      = $this->request->getVar('total');

                    $data2 = array();
                    foreach($item_id as $key => $item){
                        $data2[] = array('purchase_id'=> $result,'item_id'=> $item, 'qty'=> $qty[$key], 'price'=> $price[$key], 'total'=> $total[$key] );
                    }

                    $result2 = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_bag_requisition_details',$data2);

                    /*$store_id = $this->request->getVar('store_id');
                    foreach($item_id as $key => $item){
                        $where = array('store_id'=> $store_id,'item_id'=> $item );
                        $affectedRows = $this->bdtaskt1m12c7_01_item_purchaseModel->bdtaskt1m12_04_updateStock($qty[$key], $where);
                        if( $affectedRows ==0 ){
                            $data3 = array('store_id'=> $store_id,'item_id'=> $item, 'stock'=> $qty[$key] );
                            $result = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_01_Insert('wh_bag_stock',$data3);
                        }
                    }*/

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
                $result = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_02_Update('wh_bag_requisition',$data, array('id'=>$id,'type'=>2));
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
    | Get item_purchase by ID
    *--------------------------*/
    public function bdtaskt1m12c7_04_getItemPurchaseById($id)
    { 
        $data = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_03_getRow('wh_assets', array('id'=>$id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get item details by ID
    *--------------------------*/
    public function bdtaskt1m12c7_05_getItemPurchaseDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c7_01_item_purchaseModel->bdtaskt1m12_03_getItemPurchaseDetailsById($id);
        echo json_encode($data);
    }

    /*--------------------------
    | Get item details by ID
    *--------------------------*/
    public function bdtaskt1m12c7_05_getItemReceiveDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c7_01_item_purchaseModel->bdtaskt1m12_03_getItemReceiveDetailsById($id);
        echo json_encode($data);
    }

    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c7_06_getItemPricingDetailsById()
    { 
        $material_receive_id = $this->request->getVar('purchase_id');

        $html = '<table class="table table-bordered w-100"><tr><th>'.get_phrases(['item']).'</th><th>'.get_phrases(['unit']).'</th><th class="text-right">'.get_phrases(['quantity']).'</th><th>'.get_phrases(['remarks']).'</th><th class="text-center">'.get_phrases(['QC','attachment']).'</th></tr>';

        $purchases_details = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_receive_details', array('receive_id'=>$material_receive_id));

        foreach($purchases_details as $details)
        {
            $item_row = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_03_getRow('wh_assets', array('id'=>$details->item_id));            
            $unit_row = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));

            $html .= '<tr>
                        <td width="30%">'.$item_row->nameE.'</td>   
                        <td width="10%">'.(($unit_row)?$unit_row->nameE:'').'</td>
                        <td width="10%" align="right">'.number_format(($details->qty)?$details->qty:0, 2).'</td>
                        <td width="10%">'.(($details->remarks)?$details->remarks:"").'</td>
                        <td width="10%" align="center">'.(($details->qc_attachment)?'<a href=" '.base_url().$details->qc_attachment.' " target="_blank" rel="noopener noreferrer" class="btn btn-primary"><i class="fa fa-download"></i> </a>':"").'</td>
                    </tr>';
        }
        $html .= '</table>';

        echo $html;
    }


    /*--------------------------
    | Get return details by ID
    *--------------------------*/
    public function bdtaskt1m12c7_07_getReturnDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c7_01_item_purchaseModel->bdtaskt1m12_05_getItemReturnDetailsById($id);
        echo json_encode($data);
    }
   
    /*--------------------------
    | Get return item details by ID
    *--------------------------*/
    public function bdtaskt1m12c7_08_getReturnItemDetailsById()
    { 
        $purchase_id = $this->request->getVar('purchase_id');

        $html = '<table class="table table-bordered w-100"><tr><th class="text-center">'.get_phrases(['item']).'</th><th class="text-center">'.get_phrases(['no','of','carton']).'</th><th class="text-center">'.get_phrases(['no','of','box']).'</th><th class="text-center">'.get_phrases(['box','quantity']).'</th><th class="text-center">'.get_phrases(['returned','quantity']).'</th><th class="text-center">'.get_phrases(['unit']).'</th><th class="text-center">'.get_phrases(['price']).'</th><th  class="text-center">'.get_phrases(['total','price']).'</th></tr>';

        $purchases_details = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_requisition_details', array('purchase_id'=>$purchase_id));

        foreach($purchases_details as $details)
        {
            $item_row = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_03_getRow('wh_assets', array('id'=>$details->item_id));            
            $unit_row = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
            
            $return_details = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_return_details', array('purchase_id'=>$purchase_id,'item_id'=>$details->item_id));

            $html .= '<tr>
                        <td width="30%">'.$item_row->nameE.' ('.$item_row->company_code.')</td>
                        <td width="10%" align="right">'.(($return_details)?$return_details->carton:0).'</td>
                        <td width="10%" align="right">'.(($return_details)?$return_details->box:0).'</td>
                        <td width="10%" align="right">'.number_format(($return_details)?$return_details->box_qty:0, 2).'</td>
                        <td width="10%" align="right">'.number_format(($return_details)?$return_details->qty:0, 2).'</td>
                        <td width="10%" >'.(($unit_row)?$unit_row->nameE:'').'</td>
                        <td width="10%" align="right">'.number_format(($return_details)?$return_details->price:0,2).'</td>
                        <td width="10%" align="right">'.number_format(($return_details)?($return_details->qty*$return_details->price):0,2).'</td>
                    </tr>';
        }
        $html .= '</table>';

        echo $html;
    }
   
   
}
