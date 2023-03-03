<?php namespace App\Modules\Account\Controllers;
use CodeIgniter\Controller;
use App\Modules\Account\Models\Bdtaskt1m8AccountModel;
use App\Modules\Account\Models\Bdtaskt1m8ReceiptVModel;
use App\Models\Bdtaskt1m1CommonModel;
class Bdtaskt1m8c3ReceiptVoucher extends BaseController
{
    private $bdtaskt1m8c3_01_accModel;
    private $bdtaskt1m8c3_02_CmModel;
    private $bdtaskt1m8c3_03_RVModel;
    /**
     * Constructor. 
     */
    public function __construct()
    {
        $this->bdtaskt1m8c3_01_accModel = new Bdtaskt1m8AccountModel();
        $this->bdtaskt1m8c3_02_CmModel = new Bdtaskt1m1CommonModel();
        $this->bdtaskt1m8c3_03_RVModel = new Bdtaskt1m8ReceiptVModel();
    }

    /*--------------------------
    | create a receipt voucher
    *--------------------------*/
    public function bdtaskt1m8c3_01_ReceiptVoucher()
    {
        $data['title']      = get_phrases(['receipt','voucher']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['branchInfo'] = $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_03_getRow('branch', array('id'=>session('branchId')));
        $data['isDateTimes']  = true;
        $data['module']     = "Account";
        $data['page']       = "receipt/create_voucher";
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Get package services by Id
    *--------------------------*/
    public function bdtaskt1m8c3_02_psckServById($pack)
    {
        $data = $this->bdtaskt1m8c3_01_accModel->bdtaskt1m8_04_getPackServById($pack);
        echo json_encode($data); 
    }

     /*--------------------------
    | Create service invoice 120=cash, 121=span, 122=visa, 123=master, 124=amex, 125=bank transfer, |126=credit, 127 = paid advance  
    *--------------------------*/
    public function bdtaskt1m8c3_03_saveRVoucher()
    { 
        $COAID       = $this->request->getVar('acc_head');
        $balance     = $this->request->getVar('acc_balance');
        $doctorId    = $this->request->getVar('doctor_id');
        $nameE       = $this->request->getVar('nameE');
        $file_no     = $this->request->getVar('file_no');
        $invoice_id  = $this->request->getVar('invoice_id');
        $sub_vat     = $this->request->getVar('sub_vat');
        $patient_id  = $this->request->getVar('patient_id');
        $package_id  = $this->request->getVar('package_id');
        // services
        $id          = $this->request->getVar('id');
        $service_id  = $this->request->getVar('service_id');
        $price       = $this->request->getVar('price');
        $qty         = $this->request->getVar('qty');
        $discount    = $this->request->getVar('discount');
        $vat         = $this->request->getVar('vat');
        $subtotal    = $this->request->getVar('subtotal');
        $payable    = $this->request->getVar('payable');
        // payment info
        $pay_method  = $this->request->getVar('pm_name');
        $pay_amount  = $this->request->getVar('pay_amount');
        $ac_no       = $this->request->getVar('ac_no');
        $exdate      = $this->request->getVar('edate');
        $bank_name   = $this->request->getVar('bank_name');

        $due = $this->request->getVar('due_total');

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'patient_id'  => ['label'=> get_phrases(['patient', 'name']), 'rules'=>'required'],
                'doctor_id'   => ['label'=> get_phrases(['doctor', 'name']), 'rules'=>'required'],
                'payable'     => ['label'=> get_phrases(['payable', 'amount']), 'rules'=>'required']
            ];
        }
        
        if (! $this->validate($rules)) {
            $this->session->setFlashdata('exception', $this->validator->listErrors());
            return redirect()->route('account/accounts/receipt_voucher');
        }else{
            if(!empty($pay_method)){
                $mpay = 0;
                for ($j=0; $j < sizeof($pay_method); $j++) { 
                    $mpay += $pay_amount[$j];
                }
                if($payable==$mpay){
                    $aFile = $this->request->getFile('attach_file');
                    if(!empty($aFile)){
                        $attachFile = $this->base_03_fileUpload->doUpload('/assets/attached/receipts', $aFile);
                    }else{
                        $attachFile = null;
                    }

                    $receipt     = ($payable - $this->request->getVar('due_total'));
                    $data = array(
                        'branch_id'    => session('branchId'),
                        'patient_id'   => $patient_id,
                        'package_id'   => $package_id,
                        'vtype'        => 'RV',  
                        'total'        => $this->request->getVar('total_price'), 
                        'receipt'      => $receipt, 
                        'due'          => !empty($invoice_id)?0.00:$due, 
                        'remaining_balance'=> !empty($invoice_id)?0.00:$receipt, 
                        'vat'          => !empty($invoice_id)?0.00:$this->request->getVar('sub_vat'), 
                        'vat_percent'  => $this->request->getVar('vat_percent'), 
                        'grand_total'  => $payable, 
                        'voucher_date' => date('Y-m-d', strtotime(str_replace('/', '-', $this->request->getVar('voucher_date')))), 
                        'doctor_id'    => $doctorId, 
                        'update_notes' => $this->request->getVar('notes'),
                        'isCredit'     => !empty($invoice_id)?$invoice_id:0,
                        'created_by'   => session('id'),
                        'attach_file'  => $attachFile,
                        'status'       => 1
                    );

                    $result = $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_01_Insert('vouchers',$data);
                    if($result){
                        if(!empty($service_id) && is_array($service_id)){
                            $details = array();
                            for ($i=0; $i < sizeof($service_id); $i++) { 
                                $details[$i] = array(
                                    'voucher_id'     => $result, 
                                    'service_id'     => $service_id[$i], 
                                    'qty'            => $qty[$i], 
                                    'price'          => $price[$i], 
                                    'discount'       => $discount[$i], 
                                    'vat'            => !empty($invoice_id)?0.00:$vat[$i], 
                                    'amount'         => $subtotal[$i]
                                );
                                $updateData[$i] = array(
                                    'id'         => $id[$i], 
                                    'voucher_id' => $result, 
                                );
                            }
                            $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_02_Update_Batch('appoint_services', $updateData, 'id');
                            $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_01_Insert_Batch('voucher_details',$details);
                        }

                        if(!empty($pay_method) && is_array($pay_method)){
                            $payments = array();
                            for ($i=0; $i < sizeof($pay_method); $i++) { 
                                $payments[$i] = array(
                                    'voucher_id'       => $result, 
                                    'pay_method_id'    => $pay_method[$i], 
                                    'card_or_cheque_no'=> !isset($ac_no[$i])?'':$ac_no[$i], 
                                    'bank_name'        => !isset($bank_name[$i])?'':$bank_name[$i], 
                                    'expiry_date'      => !isset($exdate[$i])?'0000-00-00':date('Y-m-d', strtotime($exdate[$i])), 
                                    'amount'           => $pay_amount[$i]
                                );
                                //120=cash, 121=span, 122=visa, 123=master, 124=amex, 125=bank transfer, |126=credit, 127 = paid advance
                                if($pay_method[$i]=='120'){
                                    //ACC cash in hand receivable  Debit
                                    $receiptMethod = array(
                                        'VNo'         => 'RV-'.$result,
                                        'Vtype'       => 'RV',
                                        'VDate'       => date('Y-m-d'),
                                        'COAID'       => 121100001,
                                        'Narration'   => 'Receipt Voucher cash from - '.$file_no.'-'.$nameE,
                                        'Debit'       => $pay_amount[$i],
                                        'Credit'      => 0,
                                        'PatientID'   => $data['patient_id'],
                                        'DoctorId'    => $doctorId,
                                        'BranchID'     => session('branchId'),
                                        'IsPosted'    => 1,
                                        'CreateBy'    => session('id'),
                                        'CreateDate'  => date('Y-m-d H:i:s'),
                                        'IsAppove'    => 1
                                    ); 
                                }else if($pay_method[$i]=='121'){
                                    //ACC span receivable  Debit
                                    $receiptMethod = array(
                                        'VNo'         => 'RV-'.$result,
                                        'Vtype'       => 'RV',
                                        'VDate'       => date('Y-m-d'),
                                        'COAID'       => 121100002,
                                        'Narration'   => 'Receipt Voucher From Span - '.$file_no.'-'.$nameE,
                                        'Debit'       => $pay_amount[$i],
                                        'Credit'      => 0,
                                        'PatientID'   => $data['patient_id'],
                                        'DoctorId'    => $doctorId,
                                        'BranchID'     => session('branchId'),
                                        'IsPosted'    => 1,
                                        'CreateBy'    => session('id'),
                                        'CreateDate'  => date('Y-m-d H:i:s'),
                                        'IsAppove'    => 1
                                    ); 
                                }else if($pay_method[$i]=='122'){
                                    //ACC visa receivable  Debit
                                    $receiptMethod = array(
                                        'VNo'         => 'RV-'.$result,
                                        'Vtype'       => 'RV',
                                        'VDate'       => date('Y-m-d'),
                                        'COAID'       => 121100004,
                                        'Narration'   => 'Receipt Voucher from Visa - '.$file_no.'-'.$nameE,
                                        'Debit'       => $pay_amount[$i],
                                        'Credit'      => 0,
                                        'PatientID'   => $data['patient_id'],
                                        'DoctorId'    => $doctorId,
                                        'BranchID'     => session('branchId'),
                                        'IsPosted'    => 1,
                                        'CreateBy'    => session('id'),
                                        'CreateDate'  => date('Y-m-d H:i:s'),
                                        'IsAppove'    => 1
                                    ); 
                                }else if($pay_method[$i]=='123'){
                                    //ACC span receivable  Debit
                                    $receiptMethod = array(
                                        'VNo'         => 'RV-'.$result,
                                        'Vtype'       => 'RV',
                                        'VDate'       => date('Y-m-d'),
                                        'COAID'       => 121100003,
                                        'Narration'   => 'Receipt Voucher from Master - '.$file_no.'-'.$nameE,
                                        'Debit'       => $pay_amount[$i],
                                        'Credit'      => 0,
                                        'PatientID'   => $data['patient_id'],
                                        'DoctorId'    => $doctorId,
                                        'BranchID'     => session('branchId'),
                                        'IsPosted'    => 1,
                                        'CreateBy'    => session('id'),
                                        'CreateDate'  => date('Y-m-d H:i:s'),
                                        'IsAppove'    => 1
                                    ); 
                                }else if($pay_method[$i]=='124'){
                                    //ACC amex receivable  Debit
                                    $receiptMethod = array(
                                        'VNo'         => 'RV-'.$result,
                                        'Vtype'       => 'RV',
                                        'VDate'       => date('Y-m-d'),
                                        'COAID'       => 121100005,
                                        'Narration'   => 'Receipt Voucher from Amex Card - '.$file_no.'-'.$nameE,
                                        'Debit'       => $pay_amount[$i],
                                        'Credit'      => 0,
                                        'PatientID'   => $data['patient_id'],
                                        'DoctorId'    => $doctorId,
                                        'BranchID'     => session('branchId'),
                                        'IsPosted'    => 1,
                                        'CreateBy'    => session('id'),
                                        'CreateDate'  => date('Y-m-d H:i:s'),
                                        'IsAppove'    => 1
                                    ); 
                                }else if($pay_method[$i]=='125'){
                                    //ACC bank transfer receivable  Debit
                                    $receiptMethod = array(
                                        'VNo'         => 'RV-'.$result,
                                        'Vtype'       => 'RV',
                                        'VDate'       => date('Y-m-d'),
                                        'COAID'       => 121100006,
                                        'Narration'   => 'Receipt Voucher from bank transfer - '.$file_no.'-'.$nameE,
                                        'Debit'       => $pay_amount[$i],
                                        'Credit'      => 0,
                                        'PatientID'   => $data['patient_id'],
                                        'DoctorId'    => $doctorId,
                                        'BranchID'     => session('branchId'),
                                        'IsPosted'    => 1,
                                        'CreateBy'    => session('id'),
                                        'CreateDate'  => date('Y-m-d H:i:s'),
                                        'IsAppove'    => 1
                                    ); 
                                }else if($pay_method[$i]=='126'){
                                    //ACC check receivable  Debit
                                    $receiptMethod = array(
                                        'VNo'         => 'RV-'.$result,
                                        'Vtype'       => 'RV',
                                        'VDate'       => date('Y-m-d'),
                                        'COAID'       => 121100011,
                                        'Narration'   => 'Receipt Voucher from check - '.$file_no.'-'.$nameE,
                                        'Debit'       => $pay_amount[$i],
                                        'Credit'      => 0,
                                        'PatientID'   => $data['patient_id'],
                                        'DoctorId'    => $doctorId,
                                        'BranchID'     => session('branchId'),
                                        'IsPosted'    => 1,
                                        'CreateBy'    => session('id'),
                                        'CreateDate'  => date('Y-m-d H:i:s'),
                                        'IsAppove'    => 1
                                    ); 
                                }else{
                                    
                                }
                                // payment
                                $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$receiptMethod);
                            }
                            $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_01_Insert_Batch('voucher_payment_method',$payments);
                        }

                        // patient for credit 
                        $patientInCredit = array(
                            'VNo'         => 'RV-'.$result,
                            'Vtype'       => 'RV',
                            'VDate'       => date('Y-m-d'),
                            'COAID'       => $COAID,
                            'Narration'   => 'Receipt Voucher csah in credited to - '.$COAID.'-'.$nameE,
                            'Debit'       => 0,
                            'Credit'      => $receipt,
                            'PatientID'   => $data['patient_id'],
                            'BranchID'     => session('branchId'),
                            'IsPosted'    => 1,
                            'CreateBy'    => session('id'),
                            'CreateDate'  => date('Y-m-d H:i:s'),
                            'IsAppove'    => 1
                        ); 
                        // transaction total patient credit
                        $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$patientInCredit);
                        // total patient balance
                        $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_26_updatePatientBLCredit($receipt, array('patient_id'=>$data['patient_id']));

                        if(!empty($invoice_id)){
                            $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_02_Update('service_invoices', array('isCreditPaid'=>1), array('id' => $invoice_id));
                        }else{
                            if($sub_vat>0){
                                // VAT Adjustment - Imports
                                $vatInDebit = array(
                                    'VNo'         => 'RV-'.$result,
                                    'Vtype'       => 'RV',
                                    'VDate'       => date('Y-m-d'),
                                    'COAID'       => 125000002, //VAT Adjustment - Imports (VAI)
                                    'Narration'   => 'Patient VAT Adjustment - Imports Debit - '.$file_no.'-'.$nameE,
                                    'Debit'       => $sub_vat,
                                    'Credit'      => 0,
                                    'PatientID'   => $data['patient_id'],
                                    'BranchID'     => session('branchId'),
                                    'IsPosted'    => 1,
                                    'CreateBy'    => session('id'),
                                    'CreateDate'  => date('Y-m-d H:i:s'),
                                    'IsAppove'    => 1
                                );

                                // VAT Output
                                $vatOutInCredit = array(
                                    'VNo'         => 'RV-'.$result,
                                    'Vtype'       => 'RV',
                                    'VDate'       => date('Y-m-d'),
                                    'COAID'       => 222000002, //VAT Output Final (VO)
                                    'Narration'   => 'Patient VAT Output credit - '.$file_no.'-'.$nameE,
                                    'Debit'       => 0,
                                    'Credit'      => $sub_vat,
                                    'PatientID'   => $data['patient_id'],
                                    'BranchID'     => session('branchId'),
                                    'IsPosted'    => 1,
                                    'CreateBy'    => session('id'),
                                    'CreateDate'  => date('Y-m-d H:i:s'),
                                    'IsAppove'    => 1
                                ); 
                                $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$vatInDebit);
                                $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$vatOutInCredit);
                            }
                        }

                        // store activity log
                        $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['Receipt', 'Voucher']), get_phrases(['created']), $result, 'vouchers', 1);

                        $this->session->setFlashdata('message', get_phrases(['created', 'Successfully']));
                        return redirect()->to('receiptVDetails/'.$result);
                    }else{
                        $this->session->setFlashdata('exception', get_phrases(['something', 'went', 'wrong']));
                        return redirect()->route('account/accounts/receipt_voucher');
                    }
                }else{
                    $this->session->setFlashdata('exception', get_notify('payment_amount_is_not_equal_to_payable_amount'));
                    return redirect()->route('account/accounts/receipt_voucher');
                }
            }else{
                $this->session->setFlashdata('exception', get_phrases(['payment', 'method', 'missing']));
                return redirect()->route('account/accounts/receipt_voucher');
            }
        }
    }

    /*--------------------------
    | Receipt voucher details by ID
    *--------------------------*/
    public function bdtaskt1m8c3_04_voucherDetails($vid)
    {
        $data['title']      = get_phrases(['receipt','voucher', 'details']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['module']     = "Account";
        $data['setting']    = $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['phrases']    = $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_03_getRow('setting_vouchers', array('type'=>'receipt_voucher'));
        $data['lang']       = session('defaultLang');
        $data['vouchers']   = $this->bdtaskt1m8c3_03_RVModel->bdtaskt1m8_02_getRVDetailsId($vid);
        // echo "<pre>";
        // print_r($data['vouchers']);die();
        $data['page']       = "receipt/voucher_details";
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Receipt voucher List
    *--------------------------*/
    public function bdtaskt1m8c3_05_RVList()
    {
        $data['title']      = get_phrases(['receipt','voucher', 'list']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['isDTables']  = true;
        $data['isDateTimes']  = true;
        $data['module']     = "Account";
        $data['page']       = "receipt/list";
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Get all receipt voucher
    *--------------------------*/
    public function bdtaskt1m8c3_06_getRVList()
    {
        $postData = $this->request->getVar();
        $data     = $this->bdtaskt1m8c3_03_RVModel->bdtaskt1m8_01_getAllReceipt($postData);
        echo json_encode($data); 
    }

    /*--------------------------
    | Delete voucher
    *--------------------------*/
    public function bdtaskt1m8c3_07_deleteVoucher()
    { 
        $type    = $this->request->getVar('type');
        $id      = $this->request->getVar('voucher_id');
        $MesTitle = get_phrases(['receipt', 'record']);
        if($type=='undo'){
            $data = array(
                'isDelReq'     => 0
            );
            $data = $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_02_Update('vouchers', $data, array('id' => $id));
            if($data){
                $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['receipt', 'voucher']), get_phrases(['undo', 'request']), $id, 'vouchers', 2);
                $response = array(
                    'success'  => true,
                    'message'  => get_phrases(['request', 'undo', 'successfully']),
                    'title'    => $MesTitle
                );
            }else{
               $response = array(
                    'success'  => true,
                    'message'  => get_phrases(['please_try_again']),
                    'title'    => $MesTitle
                );
            }
        }else{
            $reasons = $this->request->getVar('reasons');
            $ref_no  = $this->request->getVar('delete_ref_id');
            $data = array(
                'remarks'      => implode(",", (array)$reasons),
                'delete_ref_id'=> !empty($ref_no)?$ref_no:0,
                'isDelReq'     => 1,
                'del_req_by'   => session('id'),
                'del_req_date' => date('Y-m-d H:i:s')
            );
            
            $data = $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_02_Update('vouchers', $data, array('id' => $id));
            if($data){
                $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['receipt', 'voucher']), get_phrases(['deleted', 'request']), $id, 'vouchers', 2);
                $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['request', 'sent', 'successfully']),
                    'title'    => $MesTitle
                );
            }else{
               $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['please_try_again']),
                    'title'    => $MesTitle
                );
            }
        }
        echo json_encode($response);
    }

    /*--------------------------
    | Get all receipt voucher
    *--------------------------*/
    public function bdtaskt1m8c3_08_getVoucherInfo($id)
    {
        $data     = $this->bdtaskt1m8c3_03_RVModel->bdtaskt1m8_10_getRvData($id);
        echo json_encode($data); 
    }

    /*--------------------------
    | update voucher
    *--------------------------*/
    public function bdtaskt1m8c3_09_updateVoucher()
    { 
        $id      = $this->request->getVar('voucher_id');
        $patient_id  = $this->request->getVar('patient_id');
        $doctor_id   = $this->request->getVar('doctor_id');
        $branch_id   = $this->request->getVar('branch_id');
        $total_payable   = number_format($this->request->getVar('total_payable'), 2);

        $pay_method  = $this->request->getVar('pm_name');
        $pay_amount  = $this->request->getVar('pay_amount');
        $ac_no       = $this->request->getVar('ac_no');
        $exdate      = $this->request->getVar('edate');
        $bank_name   = $this->request->getVar('bank_name');

        $date = $this->request->getVar('old_date');
        $data = array(
            'voucher_date'   => date('Y-m-d', strtotime($date)),
            'updated_by'     => session('id'),
            'updated_date'   => date('Y-m-d H:i:s')
        );
        $total_pay = 0;
        if(!empty($pay_amount)){
            for ($i=0; $i < sizeof($pay_amount); $i++) { 
                $total_pay +=$pay_amount[$i];
            }
        }

        $MesTitle = get_phrases(['voucher', 'record']);
        $blance = $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_03_getRow('vouchers', array('id' => $id));
        if($blance->receipt == $total_pay){
            $method = array(121100005, 121100006, 121100001, 121100011, 121100003, 121100002, 121100004, 121100012, 121100013);
            $methodId = array(120, 121, 122, 123, 124, 125, 126);
            $this->bdtaskt1m8c3_03_RVModel->bdtaskt1m8_11_deleteReceiptPayMethod($id, $methodId);
            
            $data = $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_02_Update('vouchers', $data, array('id' => $id));
            if($data){
                $this->bdtaskt1m8c3_03_RVModel->bdtaskt1m8_12_deleteTransMethod('RV-'.$id, $method);
                if(!empty($pay_method)){
                    $details   = 'Updated receipt payment method, Voucher no - '.$id;
                    for ($i=0; $i < sizeof($pay_method); $i++) { 
                        $payments = array(
                            'voucher_id'       => $id, 
                            'pay_method_id'     => $pay_method[$i], 
                            'card_or_cheque_no'=> !isset($ac_no[$i])?'':$ac_no[$i], 
                            'bank_name'        => !isset($bank_name[$i])?'':$bank_name[$i], 
                            'expiry_date'      => $exdate[$i] !=''?'0000:00:00':date('Y-m-d', strtotime($exdate[$i])), 
                            'amount'           => $pay_amount[$i]
                        );
                        $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_01_Insert('voucher_payment_method', $payments);
                        if($pay_method[$i]==120){
                            $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_21_AccTrans('RV-'.$id, 'RV', 121100001, $details, $pay_amount[$i], 0, $patient_id, $doctor_id, 1, $branch_id, $date, null, $blance->created_by);
                        }else if($pay_method[$i]==121){
                            $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_21_AccTrans('RV-'.$id, 'RV', 121100002, $details, $pay_amount[$i], 0, $patient_id, $doctor_id, 1, $branch_id, $date, null, $blance->created_by);
                        }else if($pay_method[$i]==122){
                            $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_21_AccTrans('RV-'.$id, 'RV', 121100004, $details, $pay_amount[$i], 0, $patient_id, $doctor_id, 1, $branch_id, $date, null, $blance->created_by);
                        }else if($pay_method[$i]==123){
                            $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_21_AccTrans('RV-'.$id, 'RV', 121100003, $details, $pay_amount[$i], 0, $patient_id, $doctor_id, 1, $branch_id, $date, null, $blance->created_by);
                        }else if($pay_method[$i]==124){
                            $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_21_AccTrans('RV-'.$id, 'RV', 121100005, $details, $pay_amount[$i], 0, $patient_id, $doctor_id, 1, $branch_id, $date, null, $blance->created_by);
                        }else if($pay_method[$i]==125){
                            $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_21_AccTrans('RV-'.$id, 'RV', 121100006, $details, $pay_amount[$i], 0, $patient_id, $doctor_id, 1, $branch_id, $date, null, $blance->created_by);
                        }else if($pay_method[$i]==126){
                            $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_21_AccTrans('RV-'.$id, 'RV', 121100011, $details, $pay_amount[$i], 0, $patient_id, $doctor_id, 1, $branch_id, $date, null, $blance->created_by);
                        }else if($pay_method[$i]==127){
                            $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_21_AccTrans('RV-'.$id, 'RV', 310000003, $details, $pay_amount[$i], 0, $patient_id, $doctor_id, 1, $branch_id, $date, null, $blance->created_by);
                             $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_25_updatePatientBLDebit($pay_amount[$i], array('patient_id'=>$patient_id));
                        }else{

                        }
                    }
                }
                $trans = array(
                    'VDate'      => date('Y-m-d', strtotime($date)),
                    'CreateDate' => date('Y-m-d H:i:s', strtotime($date.' '.date('H:i:s'))),
                    'UpdateBy'   => session('id'),
                    'UpdateDate' => date('Y-m-d H:i:s'),
                );

                $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_02_Update('acc_transaction', $trans, array('VNo' => 'RV-'.$id));

                // store activity log
                $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['Receipt', 'Voucher']), get_phrases(['updated']), $id, 'vouchers', 2);
               $response = array(
                    'success'  => true,
                    'message'  => get_phrases(['updated', 'successfully']),
                    'title'    => $MesTitle
                );
            }else{
               $response = array(
                    'success'  => true,
                    'message'  => get_phrases(['please_try_again']),
                    'title'    => $MesTitle
                );
            }
        }else{
             $response = array(
                'success'  => false,
                'message'  => get_notify('balance_are_not_equal_please_check').'!',
                'title'    => $MesTitle
            );
        }
        echo json_encode($response);
    }

    /*--------------------------
    | Get delele voucher voucher
    *--------------------------*/
    public function bdtaskt1m8c3_10_deleteVoucherList()
    { 
        $postData = $this->request->getVar();
        $data     = $this->bdtaskt1m8c3_03_RVModel->bdtaskt1m8_03_getDeleteVoucher($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | Get credit invoice list 
    *--------------------------*/
    public function bdtaskt1m8c3_11_searchCreditInvoices()
    { 
        $patient_id = $this->request->getVar('patient_id');
        $text       = $this->request->getVar('search');
        $data       = $this->bdtaskt1m8c3_03_RVModel->bdtaskt1m8_07_getCreditInvoices($text, $patient_id);
        echo json_encode($data);
    }

    /*--------------------------
    | Get credit invoice list
    *--------------------------*/
    public function bdtaskt1m8c3_12_getCreditInvoiceById($id)
    { 
        $data = $this->bdtaskt1m8c3_03_RVModel->bdtaskt1m8_08_getCreditInvoiceById($id);
        echo json_encode($data);
    }

    /*--------------------------
    | Approved Delete Request 
    *--------------------------*/
    public function bdtaskt1m8c3_13_appReceiptDelReq()
    { 
        $id        = $this->request->getVar('voucher_id');
        $acc_head  = $this->request->getVar('patient_id');
        $data = array(
            'del_appr_by'   => session('id'),
            'del_appr_date' => date('Y-m-d H:i:s'),
            'status'        => 0
        );
        $MesTitle = get_phrases(['receipt', 'record']);
        
        $data = $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_02_Update('vouchers', $data, array('id' => $id));
        if($data){
            $credit = $this->bdtaskt1m8c3_03_RVModel->bdtaskt1m8_09_getDeleteBalance($id);
            if(!empty($credit)){
                $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_25_updatePatientBLDebit($credit->amount, array('acc_head'=>$acc_head));
                $where = array('branch_id'=>session('branchId'), 'headCode '=>$acc_head);
                    $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_30_updateColumnAmount('acc_coa_balance', $where, 'balance', $credit->amount, false);
            }
            $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_02_Update('acc_transaction', array('IsAppove '=>2), array('VNo' => 'RV-'.$id));
            
            $this->bdtaskt1m8c3_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['receipt', 'voucher']), get_phrases(['deleted']), $id, 'vouchers', 3);
            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['deleted', 'successfully']),
                'title'    => $MesTitle
            );
        }else{
           $response = array(
                'success'  =>true,
                'message'  => get_phrases(['please_try_again']),
                'title'    => $MesTitle
            );
        }
        echo json_encode($response);
    }

    /*--------------------------
    | Get credit invoice list 
    *--------------------------*/
    public function bdtaskt1m8c3_14_getPackageByPid()
    { 
        $patient_id = $this->request->getVar('patient_id');
        $data       = $this->bdtaskt1m8c3_03_RVModel->bdtaskt1m8_13_getPackageByPid($patient_id);
        echo json_encode($data);
    }

}
