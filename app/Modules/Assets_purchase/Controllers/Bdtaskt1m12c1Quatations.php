<?php namespace App\Modules\Assets_purchase\Controllers;
use App\Modules\Assets_purchase\Views;
use CodeIgniter\Controller;
use App\Modules\Assets_purchase\Models\Bdtaskt1m12QuatationsModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;
use TCPDF;
class Bdtaskt1m12c1Quatations extends BaseController
{
    private $bdtaskt1m12c1_01_quatationModel;
    private $bdtaskt1m12c1_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c1_01_quatationModel = new Bdtaskt1m12QuatationsModel();
        $this->bdtaskt1m12c1_02_CmModel = new Bdtaskt1m1CommonModel();
    }


    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['moduleTitle']= get_phrases(['assets', 'purchase']);
        $data['title']      = get_phrases(['quotation', 'list']);
        $data['isDTables']  = true;
        $data['summernote'] = true;
        $data['module']     = "Assets_purchase";
        $data['page']       = "quatation/list";

        $data['hasCreateAccess']    = $this->permission->method('wh_bag_quatation', 'create')->access();
        $data['hasPrintAccess']     = $this->permission->method('wh_bag_quatation', 'print')->access();
        $data['hasExportAccess']    = $this->permission->method('wh_bag_quatation', 'export')->access();

        $data['approved_requisition_list']    = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_requisition', array('isApproved'=>1,'type'=>2));
        $data['supplier_list']       = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_supplier_information', array('status'=>1));
        
        $data['vat']       = get_setting('default_vat');
        
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get categories info
    *--------------------------*/
    public function bdtaskt1m12c1_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c1_01_quatationModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete categories by ID
    *--------------------------*/
    public function bdtaskt1m12c1_02_deleteCategories($id)
    { 
        $info = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_quatation', array('id'=>$id, 'status'=>1));
        $items = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_purchase', array('quatation_id'=>$id, 'status'=>1 ));
        $MesTitle = get_phrases(['quotation', 'record']);
        if( !empty($items) ){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['quotation', 'exists','in','PO']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }
        if(file_exists('.'.$info->file)){
            unlink('.'.$info->file);
        }
        $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_06_Deleted('wh_bag_quatation_details', array('quatation_id'=>$id));
        $data = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_06_Deleted('wh_bag_quatation', array('id'=>$id));
        // Store log data
        $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['quotation','record']), get_phrases(['deleted']), $id, 'wh_bag_quatation');

        $MesTitle = get_phrases(['quotation', 'record']);
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

    /*--------------------------
    | Add categories info
    *--------------------------*/
    public function bdtaskt1m12c1_03_addQuatation()
    { 
        // print_r($_POST);exit;
        $id = $this->request->getVar('id');
        $terms_conditions = $this->request->getVar('terms_conditions');
        $action = $this->request->getVar('action');
        $get_file = $this->request->getFile('attachment');
        $data = array(
            'supplier_id'       => $this->request->getVar('supplier_id'), 
            'date'              => $this->request->getVar('date'), 
            'remarks'           => $this->request->getVar('remarks'), 
            'sub_total'         => $this->request->getVar('sub_total'), 
            'vat'               => $this->request->getVar('vat'), 
            'grand_total'       => $this->request->getVar('grand_total'), 
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'supplier_id'       => 'required',
                'date'              => 'required'
            ];
        }
        if (empty($id)) {
            if (empty($_FILES['attachment']['name']))
            {
                $rules = [
                    'attachment'    => 'required',
                ];
                
            }else{
                $rules = [
                    'attachment' => [
                        'uploaded[attachment]',
                        'mime_in[attachment,image/jpg,image/jpeg,image/gif,image/png,application/pdf]',
                        'max_size[attachment,4096]',
                    ],
                ];
            }
        }
        $MesTitle = get_phrases(['quotation', 'record']);
        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            if($action=='add'){
                $data['requisition_id']      = $this->request->getVar('requisition_id');
                $spr_status = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_requisition', array('id'=>$data['requisition_id'],'received'=>1,'type'=>2));
                if(!empty($spr_status)){
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['SPR','already', 'received']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);
                    exit;
                }
                $isExist = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_quatation', array('created_date'=>$this->request->getVar('created_date')));
                if(!empty($isExist)){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    $data['created_by']          = session('id');
                    $data['created_date']        = date('Y-m-d H:i:s');
                    
                    $attachment = $this->bdtaskt1c1_01_fileUpload->doUpload('/assets/dist/quatation', $get_file);
                    $data['file'] = $attachment;
                    
                    if (!empty($terms_conditions)) {
                        $terms_conditions_data = array(
                            'terms_conditions'=> $terms_conditions,
                            'status'=> 1,
                        );
                        $rstc = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_01_Insert('wh_material_terms_conditions',$terms_conditions_data);
                    }else{
                        $rstc = null;
                    }
                    
                    $data['terms_conditions_id'] = $rstc;
                    $result = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_01_Insert('wh_bag_quatation',$data);


                    $item_id    = $this->request->getVar('item_id');
                    $qty = $this->request->getVar('po_qty');
                    $price = $this->request->getVar('po_price');
                    $vat_applicable = $this->request->getVar('vat_applicable');
                    $vat_amount = $this->request->getVar('vat_amount');
                    $total = $this->request->getVar('total');

                    $data2 = array();
                    foreach($item_id as $key => $item){
                        $data2[] = array('quatation_id'=> $result, 'item_id'=> $item, 'requisition_id'=> $data['requisition_id'], 'supplier_id'=> $data['supplier_id'], 'qty'=> $qty[$key], 'price'=> $price[$key], 'vat_applicable'=> $vat_applicable[$key], 'vat_amount'=> $vat_amount[$key], 'total'=> $total[$key] );

                    }
                    $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_bag_quatation_details',$data2);

                    // Store log data
                    $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','quatation']), get_phrases(['created']), $result, 'wh_bag_quatation');
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
                $info = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_quatation', array('id'=>$id));
                
                $attachment = $this->bdtaskt1c1_01_fileUpload->doUpload('/assets/dist/quatation', $get_file);
                if (!empty($attachment)) {
                    $data['file'] = $attachment;
                    if(file_exists('.'.$info->file)){
                        unlink('.'.$info->file);
                    }
                }
                $data['updated_by']          = session('id');
                $data['updated_date']        = date('Y-m-d H:i:s');

                if (!empty($terms_conditions)) {
                    $terms_conditions_data = array(
                        'terms_conditions'=> $terms_conditions,
                        'status'=> 1,
                    );
                    $rstc = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_01_Insert('wh_material_terms_conditions',$terms_conditions_data);
                }else{
                    $rstc = null;
                }

                $data['terms_conditions_id'] = $rstc;
                $result = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_02_Update('wh_bag_quatation',$data, array('id'=>$id));
                
                $item_id    = $this->request->getVar('item_id');
                $qty = $this->request->getVar('po_qty');
                $price = $this->request->getVar('po_price');
                $vat_applicable = $this->request->getVar('vat_applicable');
                $vat_amount = $this->request->getVar('vat_amount');
                $total = $this->request->getVar('total');

                $data2 = array();
                foreach($item_id as $key => $item){
                    $data2[] = array('quatation_id'=> $id, 'item_id'=> $item, 'requisition_id'=> $info->requisition_id, 'supplier_id'=> $data['supplier_id'], 'qty'=> $qty[$key], 'price'=> $price[$key], 'vat_applicable'=> $vat_applicable[$key], 'vat_amount'=> $vat_amount[$key], 'total'=> $total[$key] );

                }
                $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_06_Deleted('wh_bag_quatation_details', array('quatation_id'=>$id));
                $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_bag_quatation_details',$data2);
                
                // Store log data
                $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','quatation']), get_phrases(['updated']), $id, 'wh_bag_quatation');
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

    public function bdtaskt1m12c10_10_getPrintView()
    {
        //action_id
        $purchase_id = $this->request->getVar('action_id');
        $purchase_info = $this->bdtaskt1m12c1_01_quatationModel->bdtaskt1m12_03_getPurchaseOrderDetailsById($purchase_id);
        //{item_table}
        $html = '<table class="table table-bordered w-100"><tr class="text-center"><th>ক্রম নং</th><th>ওভেন ব্যাগের স্পেসিফিকেশন ও সাইজ</th><th>ব্যাগের ওজন(গ্রাম)</th><th>লাইনারের ওজন(গ্রাম)</th><th>ব্যাগের সর্বমােট ওজন(গ্রাম)</th><th>পরিমাণ (পিস) ও মোট ওজন কেজি</th><th>একক ও মােট মূল্য (টাকা)</th></tr>';
        $purchases_details = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_purchase_details', array('purchase_id'=>$purchase_id));
        $sl = 0;
        foreach($purchases_details as $details)
        {
            $sl++;
            $item_row = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('wh_bag', array('id'=>$details->item_id)); 
            if (!empty($item_row)) {
                $specification = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->specification)); 
                // $unit_row = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
                $bag_weight = $item_row->bag_weight + $item_row->liner_weight;
                $bag_qty = $details->qty;
                $bag_price = $details->price;
                $html .= '<tr>
                        <td width="5%">'.$sl.'</td>
                        <td width="20%">'.$item_row->nameE.' '.$specification->nameE.' Bag Size:'.$item_row->bag_size.' Liner Size:'.$item_row->liner_size.'</td>
                        <td width="10%" align="right">'.number_format($item_row->bag_weight, 2).'</td>
                        <td width="5%" align="center">'.number_format($item_row->liner_weight, 2).'</td>
                        <td width="10%" align="right">'.number_format(($bag_weight)?$bag_weight:0, 2).'(±5)</td>
                        <td width="10%" align="right">'.number_format($bag_qty,2).' & '.number_format((($bag_weight*$bag_qty)/1000),2).'</td>
                        <td width="10%" align="right">'.number_format($bag_price,2).' & '.number_format(($bag_price*$bag_qty),2).'</td>
                    </tr>';
            }else{
                $html .='<tr></tr>';
            }      
            
        }
        $html .= '</table>';
        if (!empty($purchase_info['signature'])) {
            $img = '<img class="img-thambnail" src="'.base_url().$purchase_info['signature'].'" height="70px" width="70px"/>';
        }else{
            $img = '';
        }
        $template_data = [
            'supplier_name' => $purchase_info['supplier_name'],
            'supplier_address' => $purchase_info['supplier_address'],
            'signature' => $img,
            'item_table' => $html,
            'terms_conditions' => $purchase_info['terms_conditions'],
        ];
        
        $data = [];
        $record = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('wh_template', array('template_name'=>'bagPOPrintView', 'isApproved'=>1));
        if (!empty($record)) {
            $data['template'] = htmlspecialchars_decode($this->parser->setData($template_data)->render($record->template_path));
            $data['template_header'] = $record->template_header;
            $data['template_footer'] = $record->template_footer;
        }
        echo json_encode($data);
    }

    /*--------------------------
    | Get by ID
    *--------------------------*/
    public function bdtaskt1m12c1_04_getCategoriesById($id)
    { 
        $data = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_quatation', array('id'=>$id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get details by ID
    *--------------------------*/
    public function bdtaskt1m12c1_05_getCategoryDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c1_01_quatationModel->bdtaskt1m12_03_getCategoryDetailsById($id);
        echo json_encode($data);
    }

    public function bdtaskt1m12c10_05_getPurchaseOrderDetailsById($id)
    {
        $data = $this->bdtaskt1m12c1_01_quatationModel->bdtaskt1m12_03_getPurchaseOrderDetailsById($id);
        echo json_encode($data);
    }

    public function bdtaskt1m12c10_05_get_quatation_details_by_id()
    {
        $quatation_id = $this->request->getVar('quatation_id');
        $data = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_quatation_details', array('quatation_id'=>$quatation_id ));
        echo json_encode($data);
    }

    public function bdtaskt1m12c10_05_get_spr_list()
    { 
        $requisition_id = $this->request->getVar('requisition_id');
        $item_list = $this->bdtaskt1m12c1_01_quatationModel->bdtaskt1m12_03_get_item_list($requisition_id);
        $html = '<option value="">Select</option>';
        foreach($item_list as $items){
            $html .='<option value="'.$items->id.'">'.$items->nameE.'</option>';
        }
        echo $html;
    }

    public function bdtaskt1m12c10_09_getSprItemDetailsById()
    { 
        $item_id = $this->request->getVar('item_id');
        $requisition_id = $this->request->getVar('requisition_id');

        $data = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRowArray('wh_bag_requisition_details', array('item_id'=>$item_id, 'purchase_id'=>$requisition_id));
        $item_row = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('wh_assets', array('id'=>$item_id));  
        $unit_row = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));

        $data['unit_name'] = $unit_row->nameE;
        $data['is_vat'] = $item_row->vat_applicable;
        
        echo json_encode($data);
    }

    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c10_06_getPurchaseOrderPricingDetailsById()
    { 
        $purchase_id = $this->request->getVar('purchase_id');
        $quatation_id = $this->request->getVar('quatation_id');

        $html = '<table class="table table-bordered w-100"><tr><th>'.get_phrases(['SL']).'</th><th>'.get_phrases(['assets', 'name']).'</th><th class="text-right">'.get_phrases(['request','quantity']).'</th><th class="text-center">'.get_phrases(['unit']).'</th><th class="text-right">'.get_phrases(['unit', 'price']).'</th><th  class="text-right">'.get_phrases(['total', 'price']).'</th></tr>';

        $purchases_details = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_quatation_details', array('requisition_id'=>$purchase_id, 'quatation_id'=>$quatation_id));
        $sl = 0;
        foreach($purchases_details as $details)
        {
            $sl++;
            $item_row = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('wh_assets', array('id'=>$details->item_id));   
                   
            if (!empty($item_row)) {
                $unit_row = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
                
                $html .= '<tr>
                            <td width="5%">'.$sl.'</td>
                            <td width="20%">'.$item_row->nameE.'</td>
                        
                            <td width="10%" align="right">'.number_format(($details)?$details->qty:0, 2).'</td>
                            <td width="5%" align="center">'.(($unit_row)?$unit_row->nameE:'').'</td>
                            <td width="10%" align="right">'.number_format(($details)?$details->price:0, 2).'</td>
                            <td width="10%" align="right">'.number_format(($details)?$details->total:0, 2).'</td>
                        </tr>';
            }else{
                $html .='<tr></tr>';
            }    

        }
        $html .= '</table>';

        echo $html;
    }

    public function bdtaskt1m12c10_07_approvePurchaseOrder()
    {

        $id = $this->request->getVar('id');
        $party_id = $this->request->getVar('party_id');
        $quatation_id = $this->request->getVar('quatation_id');
        $data = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_requisition', array('id'=>$id,'isApproved'=>0,'type'=>2));
        $MesTitle = get_phrases(['quotation', 'approve']);
        if(empty($data)){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['already', 'approved']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'po_price'      => 'required',
                'po_qty'      => 'required',
            ];
        }

        if (! $this->validate($rules)) {
            $response = array(
                'success'  => false,
                'message'  => get_phrases(['field', 'required']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }
        
        $voucher_no     = 'PO-'.getMAXID('wh_bag_purchase', 'id');
        $isExist = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_purchase', array('voucher_no'=>$voucher_no) );
        if( !empty($isExist)  ){
            $response = array(
                    'success'  =>'exist',
                    'message'  => get_phrases(['already', 'exists']),
                    'title'    => $MesTitle
                );
        }else{
            $data_1['voucher_no']     = $voucher_no;
            $data_1['quatation_id']   = $quatation_id;
            $data_1['requisition_id'] = $id;
            $data_1['supplier_id']    = $party_id;
            $data_1['sub_total']      = $this->request->getVar('sub_total');
            $data_1['vat']            = $this->request->getVar('vat');
            $data_1['grand_total']    = $this->request->getVar('grand_total');
            $data_1['isApproved']     = 1;
            $data_1['status']         = 1;
            $data_1['date']           = date('Y-m-d');
            $data_1['created_by']     = session('id');
            $data_1['approved_by']    = session('id');
            $data_1['created_at']     = date('Y-m-d H:i:s');
            $data_1['approved_date']  = date('Y-m-d H:i:s');

            $result = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_01_Insert('wh_bag_purchase',$data_1);

            $requisition_details = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_requisition_details', array('purchase_id'=>$data->id));
            
            $po_price = $this->request->getVar('po_price');
            $po_qty = $this->request->getVar('po_qty');
            $vat_applicable = $this->request->getVar('vat_applicable');
            $vat_amount = $this->request->getVar('vat_amount');
            $total = $this->request->getVar('total');

            foreach($requisition_details as $key => $details)
            {
                $data2[] = array(
                    'purchase_id'=> $result,
                    'item_id'=> $details->item_id, 
                    'store_id'=> $details->store_id, 
                    'carton'=> $details->carton, 
                    'carton_qty'=> $details->carton_qty, 
                    'box_qty'=> $details->box_qty, 
                    'box'=> $details->box, 
                    'requested_qty'=> $details->qty, 
                    'qty'=> $po_qty[$key], 
                    'price'=> $po_price[$key], 
                    'total'=> $total[$key], 
                    'vat_applicable'=> $vat_applicable[$key], 
                    'vat_amount'=> $vat_amount[$key] 
                );
                
            }

            $result2 = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_bag_purchase_details',$data2);

            // Store log data
            $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['created']), $result, 'wh_bag_purchase');
            

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

        if (!empty(session('id'))) {
            $data = array();
            $data['isApproved']       = 1;
            $data['approved_by']      = session('id');
            $data['approved_date']    = date('Y-m-d H:i:s');
    
            $result = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_02_Update('wh_bag_requisition',$data, array('id'=>$id,'type'=>2));
            $result_q = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_02_Update('wh_bag_quatation',array('isApproved'=>1), array('id'=>$quatation_id));
        }
        // Store log data
        $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['purchase','order']), get_phrases(['approved']), $id, 'wh_bag_requisition');

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

    
    public function bdtaskt1m12c1_12_getSpr()
    { 
        $id = $this->request->getVar('spr');
        $data = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_requisition', array('id'=>$id,'type'=>2));
        echo json_encode($data);
    }


    
    public function bdtaskt1m12c1_13_quatation_dompdf()
    {
        $dompdf = new \Dompdf\Dompdf(); 

        $html = $this->request->getVar('html');
        $dompdf->loadHtml($html);
        // $dompdf->loadHtml(view('App\Modules\Pharmacy\Views\sale\pos_invoice_pdf', $data));
        // setting paper to portrait, also we have landscape
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        // Download pdf
        $dompdf->stream('quatation_'.time().'.pdf'); 

        $url = 'true';
        echo json_encode($url);
    }


    
    // public function testPdf()
    // {
    //     $pdf=new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    //     $pdf->SetCreator(PDF_CREATOR);
    //     //$pdf->SetSubject('TCPDF Tutorial');
    //     //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
         
    //     $pdf->setPrintHeader(false);
    //     $pdf->setPrintFooter(false);
         
    //     // set default monospaced font
    //     $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
         
    //     // set margins
    //     $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    //     $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    //     $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
         
    //     // set auto page breaks
    //     $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
         
    //     // set image scale factor
    //     $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
         
    //     // set some language-dependent strings (optional)
    //     if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    //     require_once(dirname(__FILE__).'/lang/eng.php');
    //     $pdf->setLanguageArray($l);
    //     }
         
    //     // ---------------------------------------------------------
         
    //     $lg['a_meta_charset'] = 'UTF-8';
    //     $lg['a_meta_dir'] = 'rtl';
    //     $lg['a_meta_language'] = 'fa';
    //     $lg['w_page'] = 'page';
         
    //     // set some language-dependent strings (optional)
    //     $pdf->setLanguageArray($lg);
        
    //     // add a page
    //     $pdf->AddPage();
         
    //     //$pdf->Write(0, 'Tax Invoice <br/>', '', 0, 'C', true, 0, false, false, 0);
         
    //     $pdf->SetFont('dejavusans', '', 14);
    //     $pdf->setRTL(false);

    //     $html = '<!DOCTYPE html>
    //     <html>
    //     <title>HTML Tutorial</title>
    //     <body>
        
    //     <h1>This is a heading</h1>
    //     <p>This is a paragraph.</p>
        
    //     </body>
    //     </html>';
    //     //$rc = view('App\Modules\Dashboard\Views\pdf\receipt_pdf', $data);
    //     $pdf->writeHTML($html, true, 0, true, 0);
    //     $pdf->SetTitle('Test Title');
    //     //Close and output PDF document
    //     $pdf->Output(date('Y-m-d').'.pdf', 'D');
    // }


    // public function dompdf()
    // {
    //     $dompdf = new \Dompdf\Dompdf(); 

    //     $html = '<!DOCTYPE html>
    //     <html>
    //     <title>HTML Tutorial</title>
    //     <body>
        
    //     <h1>This is a heading</h1>
    //     <p>This is a paragraph.</p>
        
    //     </body>
    //     </html>';
    //     $dompdf->loadHtml($html);
    //     $dompdf->setPaper('A4', 'portrait');
    //     $dompdf->render();
    //     $dompdf->stream('test_dompdf_'.time().'.pdf'); 
    // }




   
}
