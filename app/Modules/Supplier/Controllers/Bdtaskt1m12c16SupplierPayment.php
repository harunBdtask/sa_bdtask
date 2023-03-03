<?php namespace App\Modules\Supplier\Controllers;
use App\Modules\Supplier\Views;
use CodeIgniter\Controller;
use App\Modules\Supplier\Models\Bdtaskt1m12SupplierPaymentModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1m12c16SupplierPayment extends BaseController
{
    private $bdtaskt1m12c16_01_supplier_paymentModel;
    private $bdtaskt1m12c16_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c16_01_supplier_paymentModel = new Bdtaskt1m12SupplierPaymentModel();
        $this->bdtaskt1m12c16_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']              = get_phrases(['supplier','payment']);
        $data['moduleTitle']        = get_phrases(['supplier','management']);
        $data['isDTables']          = true;
        $data['module']             = "Supplier";
        $data['page']               = "supplier_payment/list";

        $data['hasCreateAccess']        = $this->permission->method('wh_supplier_payment', 'create')->access();
        $data['hasPrintAccess']        = $this->permission->method('wh_supplier_payment', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('wh_supplier_payment', 'export')->access();
        
        $data['payment_method_list']     = $this->bdtaskt1m12c16_02_CmModel->bdtaskt1m1_05_getResultWhere('list_data', array('status'=>1,'list_id'=>16, 'id !='=>130,  'id !='=>127,  'id !='=>129));
        $data['receive_list']  = $this->bdtaskt1m12c16_01_supplier_paymentModel->bdtaskt1m12_05_getReceiveVoucherAll();
               
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get supplier_payment info
    *--------------------------*/
    public function bdtaskt1m12c16_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c16_01_supplier_paymentModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete supplier_payment by ID
    *--------------------------*/
    public function bdtaskt1m12c16_02_deleteSupplierPayment($id)
    { 
        $data = $this->bdtaskt1m12c16_02_CmModel->bdtaskt1m1_06_Deleted('wh_material', array('id'=>$id));
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
    | Add supplier_payment info
    *--------------------------*/
    public function bdtaskt1m12c16_03_addSupplierPayment()
    { 

        //$old_image = $this->request->getVar('old_image'); 
        $action = $this->request->getVar('action');
        $id     = $this->request->getVar('id');
        
        $voucher_no = 'SUPI-'.getMAXID('wh_supplier_payment', 'id');

        $data = array(
            'receive_id'    => $this->request->getVar('id'),
            'supplier_id'   => $this->request->getVar('supplier_id'),
            'paid_amount'   => $this->request->getVar('receipt'),
            'paid_date'     => date('Y-m-d H:i:s'),
            'voucher_no'    => $voucher_no,
            'previous_paid' => $this->request->getVar('paid_total'),
            'previous_due'  => $this->request->getVar('due_total'),
            'due'           => $this->request->getVar('due'),
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'voucher_no'    => 'required'
            ];
        }
        $MesTitle = get_phrases(['payment', 'record']);
        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            //$filePath = $this->bdtaskt1c1_01_fileUpload->doUpload('./assets/dist/img/supplier_payment', $this->request->getFile('image'));
            if($action=='add'){
                $isExist = 0;//$this->bdtaskt1m12c16_02_CmModel->bdtaskt1m1_03_getRow('wh_production', array('voucher_no'=>$this->request->getVar('voucher_no')));
                //$isExist2 = $this->bdtaskt1m12c16_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('nameA'=>$this->request->getVar('nameA')));
                if( !empty($isExist)  ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    //$data['image'] = $filePath;
                    $result = $this->bdtaskt1m12c16_02_CmModel->bdtaskt1m1_01_Insert('wh_supplier_payment', $data);

                    if($this->request->getVar('due') == 0){
                        $data2 = array('paid_status'=> 1 );
                        $where2 = array('receive_id'=> $this->request->getVar('id') );
                        $result2 = $this->bdtaskt1m12c16_02_CmModel->bdtaskt1m1_02_Update('wh_supplier_credit', $data2, $where2 );
                    }

                    $where3 = array('receive_id'=> $this->request->getVar('id') );
                    $amount3 = ($this->request->getVar('receipt') =='' )?0:$this->request->getVar('receipt');
                    $result3 = $this->bdtaskt1m12c16_01_supplier_paymentModel->bdtaskt1m12_04_updateAmount($amount3, $where3);

                    $payment_method     = $this->request->getVar('payment_method');
                    $pay_acc_no             = $this->request->getVar('pay_acc_no');
                    $amount             = $this->request->getVar('amount');
                    $supplier_id    = $this->request->getVar('supplier_id');

                    foreach($payment_method as $key => $method){
                        if( $amount[$key] >0 ){
                            $data4 = array('payment_id'=> $result, 'payment_method'=> $method, 'pay_acc_no'=> $pay_acc_no[$key], 'amount'=> $amount[$key] );
                            $result4 = $this->bdtaskt1m12c16_02_CmModel->bdtaskt1m1_01_Insert('wh_supplier_credit_payment',$data4);
                       
                            $this->bdtaskt1m16_09_supplierTransaction($result, $supplier_id, $amount[$key], $method);
                        }
                    }
                    // Store log data
                    $this->bdtaskt1m12c16_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['supplier','payment']), get_phrases(['created']), $result, 'wh_supplier_payment');

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
                $result = $this->bdtaskt1m12c16_02_CmModel->bdtaskt1m1_02_Update('wh_supplier_payment',$data, array('id'=>$id));
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
    | Get supplier_payment by ID
    *--------------------------*/
    public function bdtaskt1m12c16_04_getSupplierPaymentById($id)
    { 
        $data = $this->bdtaskt1m12c16_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=>$id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get item details by ID
    *--------------------------*/
    public function bdtaskt1m12c16_05_getSupplierPaymentDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c16_01_supplier_paymentModel->bdtaskt1m12_03_getSupplierPaymentDetailsById($id);
        echo json_encode($data);
    }

    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c16_06_getItemPricingDetailsById()
    { 
        $purchase_id = $this->request->getVar('purchase_id');

        $html = '<table class="table table-bordered w-100"><tr class="table-info"><th class="text-center">'.get_phrases(['item']).'</th><th class="text-center">'.get_phrases(['no','of','carton']).'</th><th class="text-center">'.get_phrases(['no','of','box']).'</th><th class="text-center">'.get_phrases(['box','quantity']).'</th><th class="text-center">'.get_phrases(['received','quantity']).'</th><th class="text-center">'.get_phrases(['unit']).'</th><th class="text-center">'.get_phrases(['price']).'</th><th  class="text-center">'.get_phrases(['total','price']).'</th></tr>';

        $purchases_details = $this->bdtaskt1m12c16_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_requisition_details', array('purchase_id'=>$purchase_id));

        foreach($purchases_details as $details)
        {
            $item_row = $this->bdtaskt1m12c16_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=>$details->item_id));            
            $unit_row = $this->bdtaskt1m12c16_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
            
            $receive_details = $this->bdtaskt1m12c16_02_CmModel->bdtaskt1m1_03_getRow('wh_material_receive_details', array('purchase_id'=>$purchase_id,'item_id'=>$details->item_id));

            $html .= '<tr>
                        <td width="30%">'.$item_row->nameE.'</td>
                        <td width="10%" align="right">'.(($receive_details)?$receive_details->carton:0).'</td>
                        <td width="10%" align="right">'.(($receive_details)?$receive_details->box:0).'</td>
                        <td width="10%" align="right">'.number_format(($receive_details)?$receive_details->box_qty:0, 2).'</td>
                        <td width="10%" align="right">'.number_format(($receive_details)?$receive_details->qty:0, 2).'</td>
                        <td width="10%" >'.(($unit_row)?$unit_row->nameE:'').'</td>
                        <td width="10%" align="right">'.number_format(($receive_details)?$receive_details->price:0,2).'</td>
                        <td width="10%" align="right">'.number_format(($receive_details)?($receive_details->qty*$receive_details->price):0,2).'</td>
                    </tr>';
        }
        $html .= '</table>';

        echo $html;
    }
   
    public function bdtaskt1m16_09_supplierTransaction($payment_id, $supplier_id, $amount, $payment_method_id){

        $supplier_row = $this->bdtaskt1m12c16_02_CmModel->bdtaskt1m1_03_getRow('wh_supplier_information', array('id'=>$supplier_id));

        $payment_method = $this->bdtaskt1m12c16_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$payment_method_id,'list_id'=>16));
        $payment_method_name = $payment_method->nameE;
        $debit = 0;
        $credit = $amount;      

        $data = array(
            'VNo'         => 'SUPI-'.$payment_id,
            'Vtype'       => 'SUPI',
            'VDate'       => date('Y-m-d'),
            'COAID'       => $supplier_row->acc_head,
            'Narration'   => $payment_method_name.' Payment to Supplier, Name: '.$supplier_row->nameE,
            'Debit'       => $debit,
            'Credit'      => $credit,
            'PatientID'   => 0,
            'BranchID'     => session('branchId'),
            'IsPosted'    => 1,
            'CreateBy'    => session('id'),
            'CreateDate'  => date('Y-m-d H:i:s'),
            'IsAppove'    => 1
        ); 
        $result = $this->bdtaskt1m12c16_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction', $data);

        $debit = $amount;
        $credit = 0;

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
            'VNo'         => 'SUPI-'.$payment_id,
            'Vtype'       => 'SUPI',
            'VDate'       => date('Y-m-d'),
            'COAID'       => $acc_head,
            'Narration'   => $payment_method_name.' Payment to Supplier, Name: '.$supplier_row->nameE,
            'Debit'       => $debit,
            'Credit'      => $credit,
            'PatientID'   => 0,
            'BranchID'     => session('branchId'),
            'IsPosted'    => 1,
            'CreateBy'    => session('id'),
            'CreateDate'  => date('Y-m-d H:i:s'),
            'IsAppove'    => 1
        ); 
        $result = $this->bdtaskt1m12c16_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction', $data);

        return $result;
    }

    /*--------------------------
    | Get payment details by ID
    *--------------------------*/
    public function bdtaskt1m12c16_10_getPaymentDetailsById()
    { 
        $id = $this->request->getVar('id');

        $supplier_payment = $this->bdtaskt1m12c16_02_CmModel->bdtaskt1m1_03_getRow('wh_supplier_payment', array('id'=>$id));
        $html = '<table class="table table-bordered w-100"><tr class="table-info"><th class="text-center">'.get_phrases(['payment','method']).'</th><th class="text-center">'.get_phrases(['payment','information']).'</th><th class="text-center">'.get_phrases(['amount']).'</th></tr>';

        $payment_details = $this->bdtaskt1m12c16_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_supplier_credit_payment', array('payment_id'=>$supplier_payment->id));

        $total = 0;
        foreach($payment_details as $details)
        {
            $payment_method = $this->bdtaskt1m12c16_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('list_id'=>16,'id'=>$details->payment_method));            
            $html .= '<tr>
                        <td width="30%">'.$payment_method->nameE.'</td>
                        <td width="20%">'.$details->pay_acc_no.'</td>
                        <td width="20%" align="right">'.number_format($details->amount, 2).'</td>
                    </tr>';
            $total += $details->amount;
        }
        /*$html .= '<tr>
                        <td width="30%" align="right"><strong>'.get_phrases(['total','paid']).'</strong></td>
                        <td width="20%" align="right">'.number_format($total, 2).'</td>
                    </tr>';*/
        $html .= '</table>';


        echo json_encode(array('voucher_no'=>$supplier_payment->voucher_no,'paid_date'=>$supplier_payment->paid_date,'previous_paid'=>$supplier_payment->previous_paid,'previous_due'=>$supplier_payment->previous_due,'paid_amount'=>$supplier_payment->paid_amount,'due'=>$supplier_payment->due,'html'=>$html));
    }

    /*--------------------------
    | Get payment details by ID
    *--------------------------*/
    public function bdtaskt1m12c16_11_getPaymentDueById()
    { 
        $receive_id = $this->request->getVar('receive_id');

        $supplier_credit = $this->bdtaskt1m12c16_02_CmModel->bdtaskt1m1_03_getRow('wh_supplier_credit', array('receive_id'=>$receive_id)); 
        $paid_amount = 0;
        $due_amount = 0;
        if($supplier_credit){
            $paid_amount = $supplier_credit->paid_amount;
            $due_amount = $supplier_credit->credit_amount - $supplier_credit->paid_amount;
        }
        echo json_encode( array( 'paid_amount'=>number_format($paid_amount, 2, '.', ''), 'due_amount'=>number_format($due_amount, 2, '.', '') ) );
    }

}
