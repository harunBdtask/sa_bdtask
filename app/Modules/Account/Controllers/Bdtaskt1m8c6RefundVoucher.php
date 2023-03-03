<?php namespace App\Modules\Account\Controllers;
use CodeIgniter\Controller;
use App\Modules\Account\Models\Bdtaskt1m8RefundVModel;
use App\Modules\Account\Models\Bdtaskt1m8PaymentVModel;
use App\Models\Bdtaskt1m1CommonModel;
class Bdtaskt1m8c6RefundVoucher extends BaseController
{
    private $bdtaskt1m8c6_01_rvModel;
    private $bdtaskt1m8c6_02_CmModel;
    private $bdtaskt1m8c6_03_pvModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bdtaskt1m8c6_01_rvModel = new Bdtaskt1m8RefundVModel();
        $this->bdtaskt1m8c6_02_CmModel = new Bdtaskt1m1CommonModel();
        $this->bdtaskt1m8c6_03_pvModel = new Bdtaskt1m8PaymentVModel();
    }

    /*--------------------------
    | create a payment voucher 
    *--------------------------*/
    public function bdtaskt1m8c6_01_refundVoucher()
    {
        $data['title']      = get_phrases(['refund','voucher']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['branchInfo'] = $this->bdtaskt1m8c6_02_CmModel->bdtaskt1m1_03_getRow('branch', array('id'=>session('branchId')));
        $data['isDateTimes']= true;
        $data['module']     = "Account";
        $data['page']       = "refund/create";
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Get invoice details by Id
    *--------------------------*/
    public function bdtaskt1m8c6_02_invDetailsById($vid)
    {
        $data = $this->bdtaskt1m8c6_01_rvModel->bdtaskt1m8_02_getInvDetailsById($vid);
        echo json_encode($data); 
    }

     /*--------------------------
    | Create service invoice
    *--------------------------*/
    public function bdtaskt1m8c6_03_saveRefundV()
    { 
        // echo "<pre>"; 
        // print_r($this->request->getVar());die();
        $COAID       = $this->request->getVar('acc_head');
        $balance     = $this->request->getVar('acc_balance');
        $doctorId    = $this->request->getVar('doctorId');
        $nameE       = $this->request->getVar('nameE');
        $file_no     = $this->request->getVar('file_no');
        $total_price = $this->request->getVar('total_price');
        $payment     = $this->request->getVar('deduction');
        $discount    = $this->request->getVar('total_discount');
        $pay_vat     = $this->request->getVar('pay_vat');
        $action      = $this->request->getVar('action');
        $voucherId   = $this->request->getVar('invoice_id');
        // service info 
        $serviceId   = $this->request->getVar('ID');
        $amount    = $this->request->getVar('amount');
        $vatDeduct   = $this->request->getVar('vat_deduct');
        $refundAmt   = $this->request->getVar('refund_amoun');
        // payment info
        $pay_method  = $this->request->getVar('pm_name');
        $pay_amount  = $this->request->getVar('pay_amount');
        $ac_no       = $this->request->getVar('ac_no');
        $exdate      = $this->request->getVar('edate');
        $bank_name   = $this->request->getVar('bank_name');

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'invoice_id'  => ['label' => get_phrases(['invoice', 'no']), 'rules'=> 'required'],
                'deduction'   => ['label' => get_phrases(['refund', 'amount']), 'rules'=> 'required'],
                'doctorId'    => ['label' => get_phrases(['doctor', 'name']), 'rules'=> 'required'],
                'pay_amount'  => ['label' => get_phrases(['payment', 'method', 'amount']), 'rules'=> 'required']
            ];
        }
        
        if (! $this->validate($rules)) {
            $this->session->setFlashdata('exception', $this->validator->listErrors());
            return redirect()->route('account/accounts/refund_voucher');
        }else{
            $aFile = $this->request->getFile('attach_file');
            if(!empty($aFile)){
                $attachFile = $this->base_03_fileUpload->doUpload('/assets/attached/refunds', $aFile);
            }else{
                $attachFile = null;
            }

            $totalDis = ($discount * $payment)/$total_price;
            $totalPay = $payment + $totalDis;
            $patientPay = $payment + $pay_vat;
            $data = array(
                'patient_id'   => $this->request->getVar('patient_id'),
                'vtype'        => 'RFV',  
                'total'        => $total_price, 
                'remaining'    => 0.00,
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
                $result = $this->bdtaskt1m8c6_02_CmModel->bdtaskt1m1_01_Insert('payment_vouchers',$data);
                if($result){
                    if(!empty($serviceId) && is_array($serviceId)){
                        $details = array();
                        $j=0;
                        foreach ($serviceId as $key => $row) {
                            $details[$j] = array(
                                'voucher_id'     => $result, 
                                'service_id'     => $serviceId[$key], 
                                'invoice_amount' => $amount[$key], 
                                'vat'            => $vatDeduct[$key],
                                'amount'         => $refundAmt[$key]
                            );
                            $j++;
                        }
                        
                        $this->bdtaskt1m8c6_02_CmModel->bdtaskt1m1_01_Insert_Batch('payment_voucher_details',$details);
                    }

                    // patient for Sales Return Cash 
                    $saleReturnInDebit = array(
                        'VNo'         => 'RFV-'.$result,
                        'Vtype'       => 'RFV',
                        'VDate'       => date('Y-m-d'),
                        'COAID'       => 310000004, //SalesReturnCash
                        'Narration'   => $file_no.'-'.$nameE.' - Debit from invoice for Retrun',
                        'Debit'       => $totalPay,
                        'Credit'      => 0,
                        'PatientID'   => $data['patient_id'],
                        'BranchID'     => session('branchId'),
                        'IsPosted'    => 1,
                        'CreateBy'    => session('id'),
                        'CreateDate'  => date('Y-m-d H:i:s'),
                        'IsAppove'    => 1
                    ); 

                    // patient debit
                    $patientInDebit = array(
                        'VNo'         => 'RFV-'.$result,
                        'Vtype'       => 'RFV',
                        'VDate'       => date('Y-m-d'),
                        'COAID'       => $COAID,
                        'Narration'   => $file_no.'-'.$nameE.' - Debited',
                        'Debit'       => $patientPay,
                        'Credit'      => 0,
                        'PatientID'   => $data['patient_id'],
                        'BranchID'     => session('branchId'),
                        'IsPosted'    => 1,
                        'CreateBy'    => session('id'),
                        'CreateDate'  => date('Y-m-d H:i:s'),
                        'IsAppove'    => 1
                    ); 

                    // patient for credit
                    $patientInCredit = array(
                        'VNo'         => 'RFV-'.$result,
                        'Vtype'       => 'RFV',
                        'VDate'       => date('Y-m-d'),
                        'COAID'       => $COAID,
                        'Narration'   => $file_no.'-'.$nameE.' - Credited',
                        'Debit'       => 0,
                        'Credit'      => $patientPay,
                        'PatientID'   => $data['patient_id'],
                        'BranchID'     => session('branchId'),
                        'IsPosted'    => 1,
                        'CreateBy'    => session('id'),
                        'CreateDate'  => date('Y-m-d H:i:s'),
                        'IsAppove'    => 1
                    ); 

                    // profit Discount for credit
                    $profitDisCredit = array(
                        'VNo'         => 'RFV-'.$result,
                        'Vtype'       => 'RFV',
                        'VDate'       => date('Y-m-d'),
                        'COAID'       => 320000001, //profit Discount
                        'Narration'   => $file_no.'-'.$nameE.' - Credit profit discount for Retrun',
                        'Debit'       => 0,
                        'Credit'      => $totalDis,
                        'PatientID'   => $data['patient_id'],
                        'BranchID'     => session('branchId'),
                        'IsPosted'    => 1,
                        'CreateBy'    => session('id'),
                        'CreateDate'  => date('Y-m-d H:i:s'),
                        'IsAppove'    => 1
                    ); 
                    $isNotPatientAccont = 0;
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

                            $vatAmt = ($pay_vat*$pay_amount[$i])/$patientPay;
                            $payAmt = $pay_amount[$i] - number_format($vatAmt, 2);

                            if($pay_method[$i]=='120'){
                                $isNotPatientAccont +=1;
                                //ACC cash in hand payment credit
                                $payMethod = array(
                                    'VNo'         => 'RFV-'.$result,
                                    'Vtype'       => 'RFV',
                                    'VDate'       => date('Y-m-d'),
                                    'COAID'       => 121100001,
                                    'Narration'   => $file_no.'-'.$nameE.' - Credited form cash',
                                    'Debit'       => 0,
                                    'Credit'      => $payAmt,
                                    'PatientID'   => $data['patient_id'],
                                    'DoctorId'    => $doctorId,
                                    'BranchID'     => session('branchId'),
                                    'IsPosted'    => 1,
                                    'CreateBy'    => session('id'),
                                    'CreateDate'  => date('Y-m-d H:i:s'),
                                    'IsAppove'    => 1
                                ); 
                                $this->bdtaskt1m8c6_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$payMethod);
                            }else if($pay_method[$i]=='121'){
                                $isNotPatientAccont +=1;
                                //ACC span Refund credit
                                $payMethod = array(
                                    'VNo'         => 'RFV-'.$result,
                                    'Vtype'       => 'RFV',
                                    'VDate'       => date('Y-m-d'),
                                    'COAID'       => 121100002,
                                    'Narration'   => $file_no.'-'.$nameE.' - Credited From Span',
                                    'Debit'       => 0,
                                    'Credit'      => $payAmt,
                                    'PatientID'   => $data['patient_id'],
                                    'DoctorId'    => $doctorId,
                                    'BranchID'     => session('branchId'),
                                    'IsPosted'    => 1,
                                    'CreateBy'    => session('id'),
                                    'CreateDate'  => date('Y-m-d H:i:s'),
                                    'IsAppove'    => 1
                                ); 
                                $this->bdtaskt1m8c6_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$payMethod);
                            }else if($pay_method[$i]=='122'){
                                $isNotPatientAccont +=1;
                                //ACC visa Refund credit
                                $payMethod = array(
                                    'VNo'         => 'RFV-'.$result,
                                    'Vtype'       => 'RFV',
                                    'VDate'       => date('Y-m-d'),
                                    'COAID'       => 121100004,
                                    'Narration'   => $file_no.'-'.$nameE.' - Credited from Visa',
                                    'Debit'       => 0,
                                    'Credit'      => $payAmt,
                                    'PatientID'   => $data['patient_id'],
                                    'DoctorId'    => $doctorId,
                                    'BranchID'     => session('branchId'),
                                    'IsPosted'    => 1,
                                    'CreateBy'    => session('id'),
                                    'CreateDate'  => date('Y-m-d H:i:s'),
                                    'IsAppove'    => 1
                                ); 
                                $this->bdtaskt1m8c6_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$payMethod);
                            }else if($pay_method[$i]=='123'){
                                $isNotPatientAccont +=1;
                                //ACC master card Refund credit
                                $payMethod = array(
                                    'VNo'         => 'RFV-'.$result,
                                    'Vtype'       => 'RFV',
                                    'VDate'       => date('Y-m-d'),
                                    'COAID'       => 121100003,
                                    'Narration'   => $file_no.'-'.$nameE.' - Credited from Master',
                                    'Debit'       => 0,
                                    'Credit'      => $payAmt,
                                    'PatientID'   => $data['patient_id'],
                                    'DoctorId'    => $doctorId,
                                    'BranchID'     => session('branchId'),
                                    'IsPosted'    => 1,
                                    'CreateBy'    => session('id'),
                                    'CreateDate'  => date('Y-m-d H:i:s'),
                                    'IsAppove'    => 1
                                ); 
                                $this->bdtaskt1m8c6_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$payMethod);
                            }else if($pay_method[$i]=='124'){
                                $isNotPatientAccont +=1;
                                //ACC Amex card Refund credit
                                $payMethod = array(
                                    'VNo'         => 'RFV-'.$result,
                                    'Vtype'       => 'RFV',
                                    'VDate'       => date('Y-m-d'),
                                    'COAID'       => 121100005,
                                    'Narration'   => $file_no.'-'.$nameE.' - Credited from Amex Card',
                                    'Debit'       => 0,
                                    'Credit'      => $payAmt,
                                    'PatientID'   => $data['patient_id'],
                                    'DoctorId'    => $doctorId,
                                    'BranchID'     => session('branchId'),
                                    'IsPosted'    => 1,
                                    'CreateBy'    => session('id'),
                                    'CreateDate'  => date('Y-m-d H:i:s'),
                                    'IsAppove'    => 1
                                ); 
                                $this->bdtaskt1m8c6_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$payMethod);
                            }else if($pay_method[$i]=='125'){
                                $isNotPatientAccont +=1;
                                //ACC bank transfer Refund credit
                                $payMethod = array(
                                    'VNo'         => 'RFV-'.$result,
                                    'Vtype'       => 'RFV',
                                    'COAID'       => 121100006,
                                    'Narration'   => $file_no.'-'.$nameE.' - Credited from bank transfer',
                                    'Debit'       => 0,
                                    'Credit'      => $payAmt,
                                    'PatientID'   => $data['patient_id'],
                                    'DoctorId'    => $doctorId,
                                    'BranchID'     => session('branchId'),
                                    'IsPosted'    => 1,
                                    'CreateBy'    => session('id'),
                                    'CreateDate'  => date('Y-m-d H:i:s'),
                                    'IsAppove'    => 1
                                ); 
                                $this->bdtaskt1m8c6_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$payMethod);
                            }else if($pay_method[$i]=='126'){
                                $isNotPatientAccont +=1;
                                //ACC check Refund credit
                                $payMethod = array(
                                    'VNo'         => 'RFV-'.$result,
                                    'Vtype'       => 'RFV',
                                    'VDate'       => date('Y-m-d'),
                                    'COAID'       => 121100011,
                                    'Narration'   => $file_no.'-'.$nameE.' - Credited from check',
                                    'Debit'       => 0,
                                    'Credit'      => $payAmt,
                                    'PatientID'   => $data['patient_id'],
                                    'DoctorId'    => $doctorId,
                                    'BranchID'     => session('branchId'),
                                    'IsPosted'    => 1,
                                    'CreateBy'    => session('id'),
                                    'CreateDate'  => date('Y-m-d H:i:s'),
                                    'IsAppove'    => 1
                                ); 
                                $this->bdtaskt1m8c6_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$payMethod);
                            }else if($pay_method[$i]=='129'){
                                //account transfer
                                $payMethod = array(
                                    'VNo'         => 'RFV-'.$result,
                                    'Vtype'       => 'RFV',
                                    'VDate'       => date('Y-m-d'),
                                    'COAID'       => $COAID,
                                    'Narration'   => $file_no.'-'.$nameE.' - transfered to account',
                                    'Debit'       => 0,
                                    'Credit'      => $payAmt,
                                    'PatientID'   => $data['patient_id'],
                                    'DoctorId'    => $doctorId,
                                    'BranchID'     => session('branchId'),
                                    'IsPosted'    => 1,
                                    'CreateBy'    => session('id'),
                                    'CreateDate'  => date('Y-m-d H:i:s'),
                                    'IsAppove'    => 1
                                ); 
                                $this->bdtaskt1m8c6_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$payMethod);
                                // total patient balance
                                $this->bdtaskt1m8c6_02_CmModel->bdtaskt1m1_26_updatePatientBLCredit($pay_amount[$i], array('patient_id'=>$data['patient_id']));
                            }else{
                                
                            }
                        }
                        $this->bdtaskt1m8c6_02_CmModel->bdtaskt1m1_01_Insert_Batch('payment_voucher_method',$payments);
                    }

                    if($isNotPatientAccont > 0){
                        // transaction
                        $this->bdtaskt1m8c6_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$patientInDebit);
                        $this->bdtaskt1m8c6_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$patientInCredit);
                    }

                    if($pay_vat >0){
                        // VAT Output Debit 
                        $vatOutInDebit = array(
                            'VNo'         => 'RFV-'.$result,
                            'Vtype'       => 'RFV',
                            'VDate'       => date('Y-m-d'),
                            'COAID'       => 222000002, //VAT Output Final (VO)
                            'Narration'   => $file_no.'-'.$nameE.' - VAT Output Final (VO)',
                            'Debit'       => $pay_vat,
                            'Credit'      => 0,
                            'PatientID'   => $data['patient_id'],
                            'BranchID'     => session('branchId'),
                            'IsPosted'    => 1,
                            'CreateBy'    => session('id'),
                            'CreateDate'  => date('Y-m-d H:i:s'),
                            'IsAppove'    => 1
                        ); 

                        // VAT Adjustment – Imports for credit
                        $vatAdjustCredit = array(
                            'VNo'         => 'RFV-'.$result,
                            'Vtype'       => 'RFV',
                            'VDate'       => date('Y-m-d'),
                            'COAID'       => 125000002, //VAT Adjustment - Imports (VAI)
                            'Narration'   => $file_no.'-'.$nameE.' - VAT Adjustment - Imports for Retrun',
                            'Debit'       => 0,
                            'Credit'      => $pay_vat,
                            'PatientID'   => $data['patient_id'],
                            'BranchID'     => session('branchId'),
                            'IsPosted'    => 1,
                            'CreateBy'    => session('id'),
                            'CreateDate'  => date('Y-m-d H:i:s'),
                            'IsAppove'    => 1
                        ); 

                        // transaction
                        $this->bdtaskt1m8c6_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$vatOutInDebit);
                        $this->bdtaskt1m8c6_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$vatAdjustCredit);
                    }

                    // transaction
                    $this->bdtaskt1m8c6_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$profitDisCredit);
                    $this->bdtaskt1m8c6_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$saleReturnInDebit);

                    // Store log data
                    $this->bdtaskt1m8c6_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['receipt', 'voucher']), get_phrases(['created']), $result, 'payment_vouchers', 1);
    
                    $this->session->setFlashdata('message', get_phrases(['created', 'Successfully']));
                    return redirect()->to('refundVDetails/'.$result);
                }else{
                    $this->session->setFlashdata('exception', get_phrases(['something', 'went', 'wrong']));
                    return redirect()->route('account/accounts/refund_voucher');
                }
            }
        }
    }

    /*--------------------------
    | Receipt voucher details by ID
    *--------------------------*/
    public function bdtaskt1m8c6_04_rvDetails($vid)
    {
        $data['title']      = get_phrases(['refund','voucher', 'details']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['module']     = "Account";
        $data['setting']   = $this->bdtaskt1m8c6_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['vouchers']   = $this->bdtaskt1m8c6_03_pvModel->bdtaskt1m8_02_getPVDetailsId($vid);
        // echo "<pre>";
        // print_r($data['vouchers']);die();
        $data['page']       = "refund/details";
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Receipt voucher List
    *--------------------------*/
    public function bdtaskt1m8c6_05_refundVList()
    {
        $data['title']      = get_phrases(['refund','voucher', 'list']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['isDTables']  = true;
        $data['isDateTimes']= true;
        $data['module']     = "Account";
        $data['page']       = "refund/list";
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Get all receipt voucher
    *--------------------------*/
    public function bdtaskt1m8c6_06_getRefundVList()
    {
        $postData = $this->request->getVar();
        $data     = $this->bdtaskt1m8c6_01_rvModel->bdtaskt1m8_01_getRefundVoucher($postData);
        echo json_encode($data); 
    }

}
