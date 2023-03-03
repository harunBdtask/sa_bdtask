<?php namespace App\Modules\Lc\Controllers;
use CodeIgniter\Controller;
use App\Libraries\Permission;

use App\Modules\Lc\Models\Lcmodel;
use App\Models\Bdtaskt1m1CommonModel;


class Bdtaskt1m12c1Lc extends BaseController
{
    private $bdtaskt1m12c1_01_lcmodel;
    /**
    * Constructor.
    */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c1_01_lcmodel = new Lcmodel();
        $this->bdtaskt1m12c14_02_CmModel = new Bdtaskt1m1CommonModel();
    }
    /*--------------------------
    | Lc list
    *--------------------------*/
    public function index()
    {
        $data['title']      = 'LC List';
        $data['moduleTitle']= 'Manage LC';
        $data['hasCreateAccess']    = $this->permission->method('wh_lc', 'create')->access();
        $data['hasPrintAccess']     = $this->permission->method('wh_lc', 'print')->access();
        $data['hasExportAccess']    = $this->permission->method('wh_lc', 'export')->access();
        $data['isDTables']  = true;
        $data['module']     = "Lc";
        $data['page']       = "lc/list";
        $data['setting']    = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['banks'] = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_04_getResult('ah_bank');
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    public function bdtaskt1m12c1_01_lcInfo($id=null)
    {
        $data['title']      = 'LC Info';
        $data['moduleTitle']= 'Manage LC';
        $data['hasCreateAccess']    = $this->permission->method('wh_lc', 'create')->access();
        $data['module']     = "Lc";
        $data['page']       = "lc/lc_info";
        $data['vat']        = get_setting('default_vat');
        //edit data
        $data['id']     = $id;
        $data['ah_lc']= $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('ah_lc', array('row_id'=>$id));
        $data['ah_lc_attachment']= $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_05_getResultWhere('ah_lc_attachment', array('lc_id'=>$id));
        $data['wh_lc_reference']= $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('wh_lc_reference', array('lc_id'=>$id));
        $data['wh_lc_shipment']= $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_lc_shipment', array('lc_id'=>$id));
        ///
        // $data['banks']      = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_04_getResult('ah_bank');
        $data['banks']      = $this->bdtaskt1m12c14_02_CmModel->bankLC_heads();
        $data['currency']   = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_05_getResultWhere('list_data', array('list_id'=>29, 'status'=>1));
        $data['supplier_list'] = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_supplier_information', array('status'=>1));
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    public function bdtaskt1m12c1_01_get_item_list()
    { 
        $item_list = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_04_getResult('wh_material');
        $html = '<option value="">Select</option>';
        foreach($item_list as $items){
            $html .='<option value="'.$items->id.'">'.$items->nameE.'</option>';
        }
        echo $html;
    }

    public function bdtaskt1m12c1_01_getItemDetailsById()
    { 
        $item_id = $this->request->getVar('item_id');
        $item_row = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=>$item_id));  
        $unit_row = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
        $data = array();
        $data['unit_name'] = $unit_row->nameE;
        $data['is_vat'] = $item_row->vat_applicable;
        echo json_encode($data);
    }

    public function bdtaskt1m12c1_01_getPurchasedItemDetailsById()
    { 
        $data = array();
        $lc_id = $this->request->getVar('lc_id');
        $data = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_purchase_details', array('lc_id'=>$lc_id)); 
        $purchase_row = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('wh_material_purchase', array('lc_id'=>$lc_id));
        $data['voucher_no'] = $purchase_row->voucher_no;
        $data['date'] = implode("/", array_reverse(explode("-", $purchase_row->date)));
        $data['supplier_id'] = $purchase_row->supplier_id;
        echo json_encode($data);
    }

    public function date_db_format($date){
        if($date==''){
            return $date;
        }
        return implode("-", array_reverse(explode("/", $date)));
    }
    /*--------------------------
    | Get all LC info
    *--------------------------*/
    public function bdtaskt1m12c1_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c1_01_lcmodel->bdtaskt1m12c1_01_getList($postData);
        echo json_encode($data);
    }
    /*--------------------------
    | Add new LC
    *--------------------------*/
    public function bdtaskt1m12c1_01_add_lc()
    {
        
        $MesTitle = 'LC Record';
        $ship_cost_counter = $this->request->getVar('ship_cost_counter');
        $id = $this->request->getVar('id');
        if (empty($id)) {
            $action = 'add';
        }else{
            $action = 'update';
        }
        
        $data = array(
            'lc_number'        => $this->request->getVar('lc_number'), 
            'lc_open_date'     => $this->request->getVar('lc_open_date'),
            'lc_bank_id'       => $this->request->getVar('lc_bank_id'),
            'lc_margin'        => $this->request->getVar('lc_margin'),
            'country_code'     => $this->request->getVar('country_code'),
            'lc_amount'        => $this->request->getVar('lc_amount'),
            'bdt_rate'         => $this->request->getVar('bdt_rate'),
            'bdt_amount'       => $this->request->getVar('lc_amount_bdt'),
            'invoiceNo'        => $this->request->getVar('invoiceNo'),
            'currency'         => $this->request->getVar('currency'),
            'amendmentCost'    => $this->request->getVar('amendmentCost'),
            'bankCharge'       => $this->request->getVar('bankCharge'),
        );

        $rules=array();
        
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'invoiceNo'             => 'required',
                'lc_number'             => 'required',
                'lc_open_date'          => 'required',
                'lc_bank_id'            => 'required',
                'lc_margin'             => 'required',
                'lc_amount'             => 'required',
                'bdt_rate'              => 'required',
                'lc_amount_bdt'         => 'required'
            ];
        }

        
        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;
        }else{
            if($action=='add'){
                $lc_number = $this->request->getVar('lc_number');
                $isExist2 = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('ah_lc', array('lc_number'=>$lc_number));
                if(!empty($isExist2) ){
                    $response = array(
                        'success'  => false,
                        'message'  => get_phrases(['lc', 'number', 'exists']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);exit;
                }else{
                    
                    $data['created_by']         = session('id');
                    $data['created_at']         = date('Y-m-d H:i:s');

                    $result = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert('ah_lc',$data);
                    
                    if($result){
                        //lc_attachment
                        $lcname = $this->request->getVar('name');
                        $lc_attc=$this->request->getFileMultiple('lc_attc');
                        if ($lc_attc) {
                            foreach($lc_attc as $key=> $fil)
                            {
                                $attachment = $this->bdtaskt1c1_01_fileUpload->doUpload('/assets/dist/lc', $fil);
                                if (empty($attachment)) {
                                    $attachment = null;
                                }
                                $data2 = array(
                                    'attachment' => $attachment,
                                    'name' => $lcname[$key],
                                    'lc_id' => $result
                                ); 
                                $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert('ah_lc_attachment',$data2);  
                            }
                        }
                        //shipment_cost
                        $shipment_data = array();
                        for($i=1; $i<=$ship_cost_counter; $i++){
                            $shipment_code = $this->request->getVar('shipment_code'.$i);
                            $cf_cost = $this->request->getVar('cf_cost'.$i);
                            $transport_cost = $this->request->getVar('transport_cost'.$i);
                            $extra_cost = $this->request->getVar('extra_cost'.$i);
                            $duty_cost = $this->request->getVar('duty_cost'.$i);
                            if ($cf_cost > 0) {
                                $shipment_data[] = array('lc_id'=>$result, 'shipment_code'=>$shipment_code, 'cf_cost'=> $cf_cost, 'transport_cost'=> $transport_cost, 'extra_cost'=> $extra_cost, 'duty_cost'=> $duty_cost);
                            }
                        }
                        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_lc_shipment', $shipment_data);
                        //reference
                        $reference_data = array(
                            'lc_id' => $result,
                            'lcaf_no' => $this->request->getVar('lcaf_no'),
                            'bin_vat_no' => $this->request->getVar('bin_vat_no'),
                            'irc_no' => $this->request->getVar('irc_no'),
                            'tin_no' => $this->request->getVar('tin_no'),
                            'bank_bin_no' => $this->request->getVar('bank_bin_no'),
                            'container_no' => $this->request->getVar('container_no'),
                            'seal_no' => $this->request->getVar('seal_no'),
                            'bl_no' => $this->request->getVar('bl_no'),
                            'vessel' => $this->request->getVar('vessel'),
                            'voyage_no' => $this->request->getVar('voyage_no'),
                            'ref_country' => $this->request->getVar('ref_country'),
                            'remarks' => $this->request->getVar('remarks'),
                            'created_by' => session('id'),
                            'created_at' => date('Y-m-d H:i:s'),
                        );
                        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert('wh_lc_reference', $reference_data);
                        //purchase
                        $voucher_no = 'SAAF/Purchase/'.date("Y").'-'.getMAXID('wh_material_purchase', 'id');
                        $po_data = array(
                            'supplier_id' => $this->request->getVar('supplier_id'),
                            'lc_id' => $result,
                            'voucher_no' => $voucher_no,
                            'date' => $this->date_db_format($this->request->getVar('date')),
                            'type' => 'lc',
                            'lc_id' => $result,
                            'isApproved' => '0',
                            'sub_total' => $this->request->getVar('sub_total'),
                            'vat' => $this->request->getVar('vat'),
                            'grand_total' => $this->request->getVar('grand_total'),
                            'created_by' => session('id'),
                            'created_at' => date('Y-m-d H:i:s'),
                        );
                        $purchase_result = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert('wh_material_purchase', $po_data);
                        $item_id    = $this->request->getVar('item_id');
                        $hs_code    = $this->request->getVar('hs_code');
                        $qty        = $this->request->getVar('qty');
                        $price      = $this->request->getVar('po_price');
                        $total      = $this->request->getVar('total');
                        $vat_applicable = $this->request->getVar('vat_applicable');
                        $vat_amount     = $this->request->getVar('vat_amount');
                        $po_details = array();
                        foreach($item_id as $key => $item){
                            $po_details[] = array('purchase_id'=>$purchase_result, 'item_id'=>$item, 'lc_id'=>$result, 'hs_code'=>$hs_code[$key], 'requested_qty'=>$qty[$key], 'qty'=>$qty[$key], 'price'=>$price[$key], 'total'=>$total[$key], 'vat_applicable'=>$vat_applicable[$key], 'vat_amount'=> $vat_amount[$key]);
                        }
                        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_material_purchase_details',$po_details);
                        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_02_Update('ah_lc', array('purchase_id'=>$purchase_result), array('row_id'=>$result));

                        // Store log data
                        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','LC']), get_phrases(['created']), $result, 'ah_lc');
                        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','purchase']), get_phrases(['created']), $result, 'wh_material_purchase');
                        $response = array(
                            'success'  => true,
                            'message'  => get_phrases(['added', 'successfully']),
                            'title'    => $MesTitle
                        );
                        echo json_encode($response);exit;
                    }
                    else{
                        $response = array(
                            'success'  =>false,
                            'message'  => (ENVIRONMENT == 'production')?get_phrases(['something', 'went', 'wrong']):get_db_error(),
                            'title'    => $MesTitle
                        );
                        echo json_encode($response);exit;
                    }
                }

            }
            else{
                $info = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('ah_lc', array('row_id'=>$id));
                if($info->isApproved == 1 ){
                    $response = array(
                        'success'  => false,
                        'message'  => get_phrases(['already', 'approved']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);exit;
                    // $this->session->setFlashdata('exception', get_phrases(['already', 'approved']) );
                    // return redirect()->route('lc/lcs');exit;
                }

                $lcname = $this->request->getVar('name');
                $lc_attc= $this->request->getFileMultiple('lc_attc');
                if ($lc_attc) {
                    $info2 = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_05_getResultWhere('ah_lc_attachment', array('lc_id'=>$id));
                    $lc_attc_data = array();
                    foreach($lc_attc as $key=> $fil)
                    {
                        $attachment = $this->bdtaskt1c1_01_fileUpload->doUpload('/assets/dist/lc', $fil);
                        if($lcname[$key]){
                            if($attachment){
                                $lc_attc_data[] = array(
                                    'attachment' => $attachment,
                                    'name' => $lcname[$key],
                                    'lc_id' => $id
                                ); 
                            }
                            else{
                                $lc_attc_data[] = array(
                                    'attachment' => $info2[$key-1]->attachment,
                                    'name' => $lcname[$key],
                                    'lc_id' => $id
                                ); 
                            }
                        }
                    }
                    $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_06_Deleted('ah_lc_attachment', array('lc_id'=>$id));
                    $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert_Batch('ah_lc_attachment',$lc_attc_data);
                }
                $data['updated_by']     = session('id');
                $data['updated_date']     = date('Y-m-d H:i:s');
                $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_02_Update('ah_lc', $data, array('row_id'=>$id));
                //shipment_cost
                $shipment_data = array();
                for($i=1; $i<=$ship_cost_counter; $i++){
                    $shipment_code = $this->request->getVar('shipment_code'.$i);
                    $cf_cost = $this->request->getVar('cf_cost'.$i);
                    $transport_cost = $this->request->getVar('transport_cost'.$i);
                    $extra_cost = $this->request->getVar('extra_cost'.$i);
                    $duty_cost = $this->request->getVar('duty_cost'.$i);
                    if ($cf_cost > 0) {
                        $shipment_data[] = array('lc_id'=>$id, 'shipment_code'=>$shipment_code, 'cf_cost'=> $cf_cost, 'transport_cost'=> $transport_cost, 'extra_cost'=> $extra_cost, 'duty_cost'=> $duty_cost);
                    }
                }
                $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_06_Deleted('wh_lc_shipment', array('lc_id'=>$id));
                $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_lc_shipment', $shipment_data);
                //reference
                $reference_data = array(
                    'lcaf_no' => $this->request->getVar('lcaf_no'),
                    'bin_vat_no' => $this->request->getVar('bin_vat_no'),
                    'irc_no' => $this->request->getVar('irc_no'),
                    'tin_no' => $this->request->getVar('tin_no'),
                    'bank_bin_no' => $this->request->getVar('bank_bin_no'),
                    'container_no' => $this->request->getVar('container_no'),
                    'seal_no' => $this->request->getVar('seal_no'),
                    'bl_no' => $this->request->getVar('bl_no'),
                    'vessel' => $this->request->getVar('vessel'),
                    'voyage_no' => $this->request->getVar('voyage_no'),
                    'ref_country' => $this->request->getVar('ref_country'),
                    'remarks' => $this->request->getVar('remarks'),
                    'updated_by' => session('id'),
                    'updated_date' => date('Y-m-d H:i:s'),
                );
                $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_02_Update('wh_lc_reference', $reference_data, array('lc_id'=>$id));
                //purchase
                $voucher_no = 'SAAF/Purchase/'.date("Y").'-'.getMAXID('wh_material_purchase', 'id');
                $po_data = array(
                    'supplier_id' => $this->request->getVar('supplier_id'),
                    'sub_total' => $this->request->getVar('sub_total'),
                    'vat' => $this->request->getVar('vat'),
                    'grand_total' => $this->request->getVar('grand_total'),
                    'updated_by' => session('id'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_02_Update('wh_material_purchase', $po_data, array('id'=>$info->purchase_id ));
                $item_id    = $this->request->getVar('item_id');
                $hs_code    = $this->request->getVar('hs_code');
                $qty        = $this->request->getVar('qty');
                $price      = $this->request->getVar('po_price');
                $total      = $this->request->getVar('total');
                $vat_applicable = $this->request->getVar('vat_applicable');
                $vat_amount     = $this->request->getVar('vat_amount');
                $po_details = array();
                foreach($item_id as $key => $item){
                    $po_details[] = array('purchase_id'=>$info->purchase_id, 'item_id'=>$item, 'lc_id'=>$id, 'hs_code'=>$hs_code[$key], 'requested_qty'=>$qty[$key], 'qty'=>$qty[$key], 'price'=>$price[$key], 'total'=>$total[$key], 'vat_applicable'=>$vat_applicable[$key], 'vat_amount'=> $vat_amount[$key]);
                }
                $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_06_Deleted('wh_material_purchase_details', array('lc_id'=>$id));
                $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_material_purchase_details',$po_details);
                // Store log data
                $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','LC']), get_phrases(['updated']), $id, 'ah_lc');
                $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['LC','purchase']), get_phrases(['updated']), $info->purchase_id, 'wh_material_purchase');
                $response = array(
                    'success'  => true,
                    'message'  => get_phrases(['updated', 'successfully']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;
            }
        }
    }
    

    /*--------------------------
    | delete  by ID
    *--------------------------*/
    public function bdtaskt1m12c1_01_delete_lc()
    {
        $id = $this->request->getVar('lc_id');
        $uselc = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_05_getResultWhere('ah_lc', array('row_id'=>$id, 'isApproved'=>1));
        $MesTitle = get_phrases(['LC', 'record']);

        if(!empty($uselc)){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['LC', 'approved']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;

        }

        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_06_Deleted('ah_lc_attachment', array('lc_id'=>$id));
        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_06_Deleted('wh_lc_reference', array('lc_id'=>$id));
        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_06_Deleted('wh_lc_shipment', array('lc_id'=>$id));
        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_06_Deleted('wh_material_purchase', array('lc_id'=>$id));
        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_06_Deleted('wh_material_purchase_details', array('lc_id'=>$id));
        $data = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_06_Deleted('ah_lc', array('row_id'=>$id));

        if(!empty($data)){
            
            $response = array(
                'success'  => true,
                'message'  => get_phrases(['deleted', 'successfully']),
                'title'    => $MesTitle
            );

        }else{

            $response = array(
                'success'  => false,
                'message'  => get_phrases(['something', 'went', 'wrong']),
                'title'    => $MesTitle
            );

        }
        echo json_encode($response);
    }


    /*--------------------------
    | Get Lc by ID
    *--------------------------*/
    public function bdtaskt1m12c1_01_get_lc($id)
    { 
        $data = $this->bdtaskt1m12c1_01_lcmodel->get_lc_by_id($id);
        echo json_encode($data);
    }

    /* Approve */
    public function bdtaskt1m12c1_01_approve(){

        $id = $this->request->getVar('id');
        $purchase_id = $this->request->getVar('purchase_id');
        $data = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('ah_lc', array('row_id'=>$id, 'isApproved'=>1));

        $MesTitle = get_phrases(['lc', 'record']);
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

        $result = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_02_Update('ah_lc',$data, array('row_id'=>$id));
        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_02_Update('wh_material_purchase',$data, array('id'=>$purchase_id));
        
        // Store log data
        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['lc','record']), get_phrases(['approved']), $id, 'ah_lc');

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
    | Get supplier details by ID
    *--------------------------*/
    public function bdtaskt1m12c1_01_lc_view($id)
    { 

        $data = $this->bdtaskt1m12c1_01_lcmodel->get_lc_by_id($id);
        $attach = $this->bdtaskt1m12c1_01_lcmodel->get_lc_attach_by_id($id);
        $po_info = $this->bdtaskt1m12c1_01_lcmodel->get_lc_po_info($id);
        $po_details = $this->bdtaskt1m12c1_01_lcmodel->get_lc_po_details($id);
        $lc_shipment = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_lc_shipment', array('lc_id'=>$id));
        $lc_reference = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('wh_lc_reference', array('lc_id'=>$id));
        $list = '';
        if(!empty($data)){
            $country_name = ($data->country_code)?countries($data->country_code):'';
            
            $list .= '<tr>
                        <th class="text-right">'.get_phrases(['LC Number']).'</th>
                        <td class="text-left">'.$data->lc_number.'</td>
                        <th class="text-right">'.get_phrases(['LC Bank']).'</th>
                        <td class="text-left">'.$data->bank_name.'</td>
                    </tr>

                    <tr>
                        <th class="text-right">'.get_phrases(['LC', 'Amount']).'</th>
                        <td class="text-left">'.$data->lc_amount.'</td>
                        <th class="text-right">'.get_phrases(['LC Margin']).'</th>
                        <td class="text-left">'.$data->lc_margin.'</td>
                    </tr>
                    <tr>
                        <th class="text-right">'.get_phrases(['BDT Rate']).'</th>
                        <td class="text-left">'.$data->bdt_rate.'</td>
                        <th class="text-right">'.get_phrases(['BDT Amount']).'</th>
                        <td class="text-left">'.$data->bdt_amount.'</td>
                    </tr>

                    <tr>
                        <th class="text-right">'.get_phrases(['LC', 'Open Date']).'</th>
                        <td class="text-left">'.$data->lc_open_date.'</td>
                        <th class="text-right">'.get_phrases(['Country']).'</th>
                        <td class="text-left">'.$country_name.'</td>
                    </tr>';

                    if(!empty($attach)){
                        foreach ($attach as $value) {
                            $list.='<tr>
                                <th class="text-right" colspan="2">'.$value->name.'</th>
                                <td class="text-left" colspan="2"><a href="'.base_url().$value->attachment.'" target="_blank" rel="noopener noreferrer" class="btn btn-success"><i class="fa fa-download"></i> </a></td>
                            </tr>';
                        }
                    }

                    if(!empty($lc_shipment)){
                        $list .= '
                        <tr>
                            <th class="text-right" colspan="2">Shipment Info</th>
                        </tr>
                        ';
                        foreach ($lc_shipment as $val) {
                            $list.='
                            <tr>
                                <th class="text-right">'.get_phrases(['shipment', 'code']).'</th>
                                <td class="text-left">'.$val->shipment_code.'</td>
                                <th class="text-right">'.get_phrases(['CF', 'cost']).'</th>
                                <td class="text-left">'.$val->cf_cost.'</td>
                            </tr>
                            <tr>
                                <th class="text-right">'.get_phrases(['transport', 'cost']).'</th>
                                <td class="text-left">'.$val->transport_cost.'</td>
                                <th class="text-right">'.get_phrases(['extra', 'cost']).'</th>
                                <td class="text-left">'.$val->extra_cost.'</td>
                            </tr>
                            <tr>
                                <th class="text-right">'.get_phrases(['duty', 'cost']).'</th>
                                <td class="text-left">'.$val->duty_cost.'</td>
                            </tr>
                            ';
                        }
                    }

                    if(!empty($lc_reference)){
                        $list.='
                        <tr>
                            <th class="text-right" colspan="2">Reference Info</th>
                        </tr>
                        <tr>
                            <th class="text-right">'.get_phrases(['LCAF', 'no']).'</th>
                            <td class="text-left">'.$lc_reference->lcaf_no.'</td>
                            <th class="text-right">'.get_phrases(['BIN', 'VAT', 'no']).'</th>
                            <td class="text-left">'.$lc_reference->bin_vat_no.'</td>
                        </tr>
                        <tr>
                            <th class="text-right">'.get_phrases(['IRC', 'no']).'</th>
                            <td class="text-left">'.$lc_reference->irc_no.'</td>
                            <th class="text-right">'.get_phrases(['TIN', 'no']).'</th>
                            <td class="text-left">'.$lc_reference->tin_no.'</td>
                        </tr>
                        <tr>
                            <th class="text-right">'.get_phrases(['bank', 'BIN', 'no']).'</th>
                            <td class="text-left">'.$lc_reference->bank_bin_no.'</td>
                            <th class="text-right">'.get_phrases(['container', 'no']).'</th>
                            <td class="text-left">'.$lc_reference->container_no.'</td>
                        </tr>
                        <tr>
                            <th class="text-right">'.get_phrases(['seal', 'no']).'</th>
                            <td class="text-left">'.$lc_reference->seal_no.'</td>
                            <th class="text-right">'.get_phrases(['BL', 'no']).'</th>
                            <td class="text-left">'.$lc_reference->bl_no.'</td>
                        </tr>
                        <tr>
                            <th class="text-right">'.get_phrases(['vessel']).'</th>
                            <td class="text-left">'.$lc_reference->vessel.'</td>
                            <th class="text-right">'.get_phrases(['voyage', 'no']).'</th>
                            <td class="text-left">'.$lc_reference->voyage_no.'</td>
                        </tr>
                        <tr>
                            <th class="text-right">'.get_phrases(['ref_country']).'</th>
                            <td class="text-left">'.(($lc_reference->ref_country)?countries($lc_reference->ref_country):'').'</td>
                            <th class="text-right">'.get_phrases(['remarks']).'</th>
                            <td class="text-left">'.$lc_reference->remarks.'</td>
                        </tr>
                        ';
                    }
                    if(!empty($po_info)){
                        $list.='
                        <tr>
                            <th class="text-right" colspan="2">PO Information</th>
                        </tr>
                        <tr>
                            <th class="text-right">'.get_phrases(['PO', 'no']).'</th>
                            <td class="text-left">'.$po_info->voucher_no.'</td>
                            <th class="text-right">'.get_phrases(['supplier', 'name']).'</th>
                            <td class="text-left">'.$po_info->nameE.' - '.$po_info->code_no.'</td>
                        </tr>
                        <tr>
                            <th class="text-right">'.get_phrases(['grand', 'total']).'</th>
                            <td class="text-left">'.$po_info->grand_total.'</td>
                        </tr>
                        ';
                    }
                    
                    if(!empty($po_details)){
                        $list.='
                            <tr>
                                <th class="text-right">'.get_phrases(['item', 'name']).'</th>
                                <th>'.get_phrases(['H.S.', 'code']).'</th>
                                <th>'.get_phrases(['quantity']).'</th>
                                <th>'.get_phrases(['unit', 'price']).'</th>
                            </tr>
                            ';
                        foreach ($po_details as $values) {
                            $list.='
                            <tr>
                                <td class="text-right">'.$values->nameE.' - '.$values->item_code.'</td>
                                <td>'.$values->hs_code.'</td>
                                <td>'.$values->qty.'</td>
                                <td>'.$values->price.'</td>
                            </tr>
                            ';
                        }
                    }
                   
        }
        echo json_encode(array('data'=>$list));
    }

    
    public function bdtaskt1m12c1_01_lcPayment()
    {
        $data['title']      = 'LC Payment';
        $data['moduleTitle']= 'Manage LC';
        $data['hasCreateAccess']    = $this->permission->method('wh_lc', 'create')->access();
        $data['hasPrintAccess']     = $this->permission->method('wh_lc', 'print')->access();
        $data['hasExportAccess']    = $this->permission->method('wh_lc', 'export')->access();
        $data['isDTables']  = true;
        $data['module']     = "Lc";
        $data['page']       = "lc/lc_payment";
        // $data['bank_list']              = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_05_getResultWhere('acc_coa', array('PHeadName'=>'Cash At Bank'));
        $data['bank_list']              = $this->bdtaskt1m12c14_02_CmModel->bank_heads();
        $data['predhead']              = $this->bdtaskt1m12c14_02_CmModel->getcoaPredefineHead();
        // $data['payment_methods']        = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_05_getResultWhere('acc_coa', array('PHeadName'=>'CashBoxes'));
        $data['lc_loan']        = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_05_getResultWhere('acc_coa', array('PHeadName'=>'LC-LOAN'));
        
        return $this->bdtaskt1c1_02_template->layout($data);
    }
    
    public function bdtaskt1m12c1_01_lc_loan_info()
    {
        $coaid = $this->request->getVar('coaid');
        $column = ["id, CONCAT(lc_number) as text"];
        $loan_history = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_07_getSelect2Data('wh_lc_loan_history', array('bank_head_code'=>$coaid), $column);
        // $credit = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getSumRow('acc_transaction', 'Credit', array('COAID'=>$coaid,'Vtype'=>'LC'));
        $data = array(
            // 'credit' => $credit->Credit, 
            'loan_history' => $loan_history, 
        );
        $item_id = $this->request->getVar('item_id');
        if ($item_id) {
            $history_info = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('wh_lc_loan_history', array('id'=>$item_id));
            $data['due_amount'] = $history_info->loan_amount - $history_info->paid_amount;
        }
        
        echo json_encode($data);
    }


    public function bdtaskt1m12c1_01_loan_repay()
    {
        $MesTitle = get_phrases(['LC', 'Payment']);
        $payment_head_code = ($this->request->getVar('payment_method') == 1 ? $this->request->getVar('bank_id') : $this->request->getVar('payment_method'));
        $loan_pay = $this->request->getVar('loan_pay');
        $loan_due = $this->request->getVar('loan_due');
        $voucher_no = $this->request->getVar('voucher_no');

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'payment_method' => 'required',
                'loan_pay'       => 'required',
                'voucher_no'     => 'required',
                'lc_loan'       => 'required',
                'spr_item_list' => 'required',
            ];
        }
        if (! $this->validate($rules)) {
            $response = array(
                'success'  => false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
            echo json_encode($response);exit;
        }

        if ($loan_pay>0 & $loan_due>0 & $loan_pay<=$loan_due) {
            $loanHeadCode = $this->request->getVar('lc_loan');
            $debit = $loan_pay;      
            $credit = 0;    
            $data = array(
                'VNo'         => $voucher_no,
                'Vtype'       => 'LCP',
                'VDate'       => date('Y-m-d'),
                'COAID'       => $loanHeadCode,
                'Narration'   => 'LC Loan Credited: '.$voucher_no.' Against COAID '.$payment_head_code,
                'Debit'       => $debit,
                'Credit'      => $credit,
                'PatientID'   => 0,
                'BranchID'    => session('branchId'),
                'IsPosted'    => 1,
                'CreateBy'    => session('id'),
                'CreateDate'  => date('Y-m-d H:i:s'),
                'IsAppove'    => 1
            ); 
            $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction', $data);
            //expense
            $debit = 0;
            $credit = $loan_pay; 
            $data = array(
                'VNo'         => $voucher_no,
                'Vtype'       => 'LCP',
                'VDate'       => date('Y-m-d'),
                'COAID'       => $payment_head_code,
                'Narration'   => 'LC Loan Debited agnaist: '.$voucher_no.' Against COAID '.$loanHeadCode,
                'Debit'       => $debit,
                'Credit'      => $credit,
                'PatientID'   => 0,
                'BranchID'    => session('branchId'),
                'IsPosted'    => 1,
                'CreateBy'    => session('id'),
                'CreateDate'  => date('Y-m-d H:i:s'),
                'IsAppove'    => 1
            ); 
            $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction', $data);

            $id = $this->request->getVar('spr_item_list');
            $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_02_Update('wh_lc_loan_history', array('paid_amount'=>$loan_pay), array('id'=>$id));
            
            $response = array(
                'success'  => true,
                'message'  => get_phrases(['added', 'successfully']),
                'title'    => $MesTitle,
            );
        }
        else{
            $response = array(
                'success'  => false,
                'message'  => get_phrases(['invalid', 'request']),
                'title'    => $MesTitle
            );
        }
        echo json_encode($response);
    }


    #route: /lc/truncate_lc
    public function bdtaskt1m12c12_04_truncate_lc()
    {
        // exit;
        // db_connect()->table('wh_material_purchase')->set('received', '0', FALSE)->update();
        db_connect()->table('wh_material_purchase_details')->where('lc_id !=', 'NULL')->delete();
        db_connect()->table('wh_material_purchase')->where('type', 'lc')->delete();
        // delete_files('./assets/dist/lc/');
        db_connect()->table('ah_lc')->truncate();
        db_connect()->table('ah_lc_attachment')->truncate();
        db_connect()->table('wh_lc_reference')->truncate();
        db_connect()->table('wh_lc_shipment')->truncate();


        echo 200;

    }


}
