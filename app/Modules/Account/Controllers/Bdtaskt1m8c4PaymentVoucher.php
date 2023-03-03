<?php namespace App\Modules\Account\Controllers;
use CodeIgniter\Controller;
use App\Modules\Account\Models\Bdtaskt1m8ReceiptVModel;
use App\Modules\Account\Models\Bdtaskt1m8PaymentVModel;
use App\Models\Bdtaskt1m1CommonModel;
class Bdtaskt1m8c4PaymentVoucher extends BaseController
{
    private $bdtaskt1m8c4_01_rvModel;
    private $bdtaskt1m8c4_02_CmModel;
    private $bdtaskt1m8c4_03_pvModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bdtaskt1m8c4_01_rvModel = new Bdtaskt1m8ReceiptVModel();
        $this->bdtaskt1m8c4_02_CmModel = new Bdtaskt1m1CommonModel();
        $this->bdtaskt1m8c4_03_pvModel = new Bdtaskt1m8PaymentVModel();
    }

    /*--------------------------
    | create a payment voucher
    *--------------------------*/
    public function bdtaskt1m8c4_01_paymentVoucher()
    {
        $data['title']      = get_phrases(['payment','voucher']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['branchInfo'] = $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_03_getRow('branch', array('id'=>session('branchId')));
        $data['isDateTimes']= true;
        $data['module']     = "Account";
        $data['page']       = "payment/create_voucher";
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Get payment voucher details by Id
    *--------------------------*/
    public function bdtaskt1m8c4_02_rvDetailsById($vid)
    {
        $data = $this->bdtaskt1m8c4_01_rvModel->bdtaskt1m8_03_getRVPntDetails($vid);
        $data->details = $this->bdtaskt1m8c4_01_rvModel->bdtaskt1m8_04_getRVServDetails($vid);
        echo json_encode($data); 
    }

     /*--------------------------
    | Create service invoice
    *--------------------------*/
    public function bdtaskt1m8c4_03_savePVoucher()
    { 
        // echo "<pre>"; 
        // print_r($this->request->getVar());die();
        $COAID       = $this->request->getVar('acc_head');
        $balance     = $this->request->getVar('acc_balance');
        $doctorId    = $this->request->getVar('doctorId');
        $nameE       = $this->request->getVar('nameE');
        $file_no     = $this->request->getVar('file_no');
        $action      = $this->request->getVar('action');
        $voucherId   = $this->request->getVar('voucher_id');
        $vBalance    = $this->request->getVar('v_remain_amount');
        $pay_vat     = $this->request->getVar('pay_vat');
        // payment info
        $pay_method  = $this->request->getVar('pm_name');
        $pay_amount  = $this->request->getVar('pay_amount');
        $ac_no       = $this->request->getVar('ac_no');
        $exdate      = $this->request->getVar('edate');
        $bank_name   = $this->request->getVar('bank_name');

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'voucher_id'  => ['label' => get_phrases(['voucher', 'no']), 'rules'=> 'required'],
                'doctorId'  => ['label' => get_phrases(['doctor', 'name']), 'rules'=> 'required'],
                'payable_amount'  => ['label' => get_phrases(['payable', 'amount']), 'rules'=> 'required']
            ];
        }
        
        if (! $this->validate($rules)) {
            $this->session->setFlashdata('exception', $this->validator->listErrors());
            return redirect()->route('account/accounts/payment_voucher');
        }else{
            $aFile = $this->request->getFile('attach_file');
            if(!empty($aFile)){
                $attachFile = $this->base_03_fileUpload->doUpload('/assets/attached/payments', $aFile);
            }else{
                $attachFile = null;
            }

            $payment = 0;
            if(!empty($pay_method) && is_array($pay_method)){
                for ($j=0; $j < sizeof($pay_method); $j++) { 
                    if(!empty($pay_method[$j])){
                        $payment +=$pay_amount[$j];
                    }
                }
            }
            $data = array(
                'patient_id'   => $this->request->getVar('patient_id'),
                'package_id'   => $this->request->getVar('packId'),
                'branch_id'    => session('branchId'),
                'vtype'        => 'PV',  
                'total'        => $this->request->getVar('total_price'), 
                'remaining'    => $this->request->getVar('remaining'),
                'payment'      => $payment,
                'vat'          => $pay_vat,
                'voucher_date' => date('Y-m-d'), 
                'doctor_id'    => $doctorId, 
                'ref_voucher'  => $voucherId,
                'remarks'      => $this->request->getVar('notes'),
                'created_by'   => session('id'),
                'attach_file'  => $attachFile,
                'status'       => 1
            );
            if($action=='add'){
                $result = $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_01_Insert('payment_vouchers',$data);
                if($result){
                    $totalB =0;
                    if(!empty($pay_method) && is_array($pay_method)){
                        $payments = array();
                        for ($i=0; $i < sizeof($pay_method); $i++) { 
                            $payments[$i] = array(
                                'voucher_id'       => $result, 
                                'pay_method_id'    => $pay_method[$i], 
                                'card_or_cheque_no'=> !isset($ac_no[$i])?'':$ac_no[$i], 
                                'bank_name'        => !isset($bank_name[$i])?'':$bank_name[$i], 
                                'expiry_date'      => !isset($exdate[$i])?'':date('Y-m-d', strtotime($exdate[$i])), 
                                'amount'           => $pay_amount[$i]
                            );

                            if($pay_method[$i]=='120'){
                                //ACC cash in hand payment credit
                                //$VNo, $Vtype, $COAID, $details=null, $debit==0, $credit==0, $patient=0, $doctor=0 $isApproved=1
                                $details   = $file_no.'-'.$nameE.' - Credit form cash, Voucher no - '.$voucherId;
                                $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_21_AccTrans('PV-'.$result, 'PV', 121100001, $details, 0, $pay_amount[$i], $data['patient_id'], $doctorId);
                                
                            }else if($pay_method[$i]=='121'){
                                //ACC span payment credit
                                $details   = $file_no.'-'.$nameE.' - Credit From Span, Voucher no - '.$voucherId;
                                $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_21_AccTrans('PV-'.$result, 'PV', 121100002, $details, 0, $pay_amount[$i], $data['patient_id'], $doctorId);
                            }else if($pay_method[$i]=='122'){
                                //ACC visa payment credit
                                $details   = $file_no.'-'.$nameE.' - Credit from Visa, Voucher no - '.$voucherId;
                                $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_21_AccTrans('PV-'.$result, 'PV', 121100004, $details, 0, $pay_amount[$i], $data['patient_id'], $doctorId);
                            }else if($pay_method[$i]=='123'){
                                //ACC master card payment credit
                                $details   = $file_no.'-'.$nameE.' - Credit from Master, Voucher no - '.$voucherId;
                                $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_21_AccTrans('PV-'.$result, 'PV', 121100003, $details, 0, $pay_amount[$i], $data['patient_id'], $doctorId);
                            }else if($pay_method[$i]=='124'){
                                //ACC Amex card payment credit
                                $details   = $file_no.'-'.$nameE.' - Credit from Amex Card, Voucher no - '.$voucherId;
                                $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_21_AccTrans('PV-'.$result, 'PV', 121100005, $details, 0, $pay_amount[$i], $data['patient_id'], $doctorId);
                            }else if($pay_method[$i]=='125'){
                                //ACC bank transfer payment credit
                                $details   = $file_no.'-'.$nameE.' - Credit from bank transfer, Voucher no - '.$voucherId;
                                $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_21_AccTrans('PV-'.$result, 'PV', 121100006, $details, 0, $pay_amount[$i], $data['patient_id'], $doctorId);
                                
                            }else if($pay_method[$i]=='126'){
                                //ACC check payment credit
                                $details   = $file_no.'-'.$nameE.' - Credit form check, Voucher no - '.$voucherId;
                                $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_21_AccTrans('PV-'.$result, 'PV', 121100011, $details, 0, $pay_amount[$i], $data['patient_id'], $doctorId);
                            }else{
                                
                            }

                            if($pay_method[$i]=='129'){
                                //balance Transfer to patient account
                                $details   = $file_no.'-'.$nameE.' - Patient balance transfer, Voucher no - '.$voucherId;
                                $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_21_AccTrans('PV-'.$result, 'PV', 121100001, $details, 0, $pay_amount[$i], $data['patient_id'], $doctorId);
                            }else{
                                // total patient balance
                                $totalB += $pay_amount[$i];
                            }
                        }
                        $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_01_Insert_Batch('payment_voucher_method',$payments);
                    }
                    // patient for Debit 
                    $details1   = 'Payment Voucher debited to'.$COAID.'-'.$nameE.' from Receipt Voucher no - '.$voucherId;
                    $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_21_AccTrans('PV-'.$result, 'PV', $COAID, $details1, $payment, 0, $data['patient_id'], $doctorId);

                     if($pay_vat > 0 ){
                        // VAT Adjustment - Imports
                        $details2   = 'Patient VAT Adjustment - Imports Credit - '.$file_no.'-'.$nameE;
                        $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_21_AccTrans('PV-'.$result, 'PV', 125000002, $details2, 0, $pay_vat, $data['patient_id'], $doctorId);
            
                        // VAT Output
                        $details3  = 'Patient VAT Output Debited - '.$file_no.'-'.$nameE;
                        $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_21_AccTrans('PV-'.$result, 'PV', 222000002, $details3, $pay_vat, 0, $data['patient_id'], $doctorId);
                    }

                    // total patient balance
                    $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_25_updatePatientBLDebit($payment, array('patient_id'=>$data['patient_id']));
                    
                    if(!empty($vBalance > $payment)){
                        $remaining = $vBalance -$payment;
                    }else{
                        $remaining = 0.00;
                    }
                    $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_02_Update('vouchers', array('remaining_balance'=>$remaining), array('id' => $voucherId));
            
                    $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_02_Update('vouchers',array('isPV'=>$result), array('id'=> $voucherId));
                    // Store log data
                    $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['payment', 'voucher']), get_phrases(['created']), $result, 'payment_vouchers', 1);
    
                    $this->session->setFlashdata('message', get_phrases(['created', 'Successfully']));
                    return redirect()->to('paymentVDetails/'.$result);
                }else{
                    $this->session->setFlashdata('exception', get_phrases(['something', 'went', 'wrong']));
                    return redirect()->route('account/accounts/payment_voucher');
                }
            }
        }
    }

    /*--------------------------
    | Receipt voucher details by ID 
    *--------------------------*/
    public function bdtaskt1m8c4_04_pvDetails($vid)
    {
        $data['title']      = get_phrases(['payment','voucher', 'details']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['module']     = "Account";
        $data['setting']   = $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['vouchers']   = $this->bdtaskt1m8c4_03_pvModel->bdtaskt1m8_02_getPVDetailsId($vid);
        // echo "<pre>";
        // print_r($data['vouchers']);die();
        $data['page']       = "payment/voucher_details";
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Receipt voucher List
    *--------------------------*/
    public function bdtaskt1m8c4_05_paymentVList()
    {
        $data['title']      = get_phrases(['payment','voucher', 'list']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['isDTables']  = true;
        $data['isDateTimes']= true;
        $data['module']     = "Account";
        $data['page']       = "payment/list";
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Get all receipt voucher
    *--------------------------*/
    public function bdtaskt1m8c4_06_getPVList()
    {
        $postData = $this->request->getVar();
        $data     = $this->bdtaskt1m8c4_03_pvModel->bdtaskt1m8_01_getAllPVoucher($postData);
        echo json_encode($data); 
    }

    /*--------------------------
    | Delete Voucher Request 
    *--------------------------*/
    public function bdtaskt1m8c4_07_deleteVoucherReq()
    { 
        $type     = $this->request->getVar('type');
        $id       = $this->request->getVar('voucher_id');
        $action   = $this->request->getVar('action');
        $MesTitle = get_phrases(['voucher', 'record']);
        if($type=='undo'){
            $data = array(
                'isDelReq'     => 0
            );
            $data = $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_02_Update('payment_vouchers', $data, array('id' => $id));
            if($data){
                $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_22_addActivityLog($action, get_phrases(['undo', 'request']), $id, 'payment_vouchers', 2);
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
            $payment = 0;
            $data = array(
                'delete_reason'=> implode(",", (array)$reasons),
                'delete_ref_id'=> !empty($ref_no)?$ref_no:0,
                'isDelReq'     => 1,
                'del_req_by'   => session('id'),
                'del_req_date' => date('Y-m-d H:i:s')
            );
            
            $data = $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_02_Update('payment_vouchers', $data, array('id' => $id));
            if($data){
                //$patient = $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_03_getRow('payment_vouchers', array('id' => $id));
                //$this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_26_updatePatientBLCredit($patient->payment, array('patient_id'=>$patient->patient_id));
                //$this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_02_Update('vouchers',array('isPV'=>0), array('id'=> $id));
                //$this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_06_Deleted('acc_transaction', array('VNo'=> 'PV-'.$id));
                $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_22_addActivityLog($action, get_phrases(['deleted', 'request']), $id, 'payment_vouchers', 2);
                $response = array(
                    'success'  => true,
                    'message'  => get_phrases(['request', 'sent', 'successfully']),
                    'title'    => $MesTitle
                );
            }else{
                $response = array(
                    'success'  => true,
                    'message'  => get_phrases(['please_try_again']),
                    'title'    => $MesTitle
                );
            }
        }
        
        echo json_encode($response);
    }

    /*--------------------------
    | Approved Delete Request
    *--------------------------*/
    public function bdtaskt1m8c4_08_approvedPaymentReq()
    { 
        $id        = $this->request->getVar('voucher_id');
        $patient_id= $this->request->getVar('patient_id');
        $action    = $this->request->getVar('action');
        $data = array(
            'del_appr_by'   => session('id'),
            'del_appr_date' => date('Y-m-d H:i:s'),
            'status'        => 0
        );
        $MesTitle = get_phrases(['voucher', 'record']);
        
            $data = $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_02_Update('payment_vouchers', $data, array('id' => $id));
            if($data){
                if($action=='refund'){
                    $type = get_phrases(['refund', 'voucher']);
                    $trans_amt  = $this->bdtaskt1m8c4_03_pvModel->bdtaskt1m8_07_getTransferAmount($id);
                    if(!empty($trans_amt)){
                        $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_25_updatePatientBLDebit($trans_amt->amount, array('patient_id'=>$patient_id));
                    }
                    $VNo = 'RFV-'.$id;
                }else{
                    $credit = $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_03_getRow('payment_vouchers', array('id'=>$id));
                    if(!empty($credit)){
                        $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_26_updatePatientBLCredit($credit->payment, array('patient_id'=>$patient_id));
                        $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_02_Update('vouchers',array('isPV'=>0), array('id'=> $credit->ref_voucher));
                    }

                    $type = get_phrases(['payment', 'voucher']);
                    $VNo = 'PV-'.$id;
                }
                $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_02_Update('acc_transaction', array('IsAppove'=>2), array('VNo' => $VNo));
                $this->bdtaskt1m8c4_02_CmModel->bdtaskt1m1_22_addActivityLog($type, get_phrases(['deleted']), $id, 'payment_vouchers', 3);
                $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['deleted', 'successfully']),
                    'title'    => $MesTitle
                );
            }else{
               $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['please_try_again']),
                    'title'    => $MesTitle
                );
            }
        echo json_encode($response);
    }


}
