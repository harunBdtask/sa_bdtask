<?php namespace App\Modules\Account\Controllers;
use CodeIgniter\Controller;
use App\Modules\Account\Models\Bdtaskt1m8SerInvModel;
use App\Models\Bdtaskt1m1CommonModel;

class Bdtaskt1m8c2ServInvoices extends BaseController
{
    private $bdtaskt1m8c2_01_servInModel;
    private $bdtaskt1m8c2_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bdtaskt1m8c2_01_servInModel = new Bdtaskt1m8SerInvModel();
        $this->bdtaskt1m8c2_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Service inovices list 
    *--------------------------*/
    public function index()
    {
        // $date = date('Y-m-d');
        // print('Next Date ' . date('Y-m-d', strtotime('-29 day', strtotime($date))));exit();
        $data['title']      = get_phrases(['service','invoice', 'list']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['isDTables']  = true;
        $data['isDateTimes']= true;
        $data['module']     = "Account";
        $data['page']       = "invoices/service_invoices";
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Get invoices info
    *--------------------------*/
    public function bdtaskt1m8c2_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_01_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | Add New Service inovice
    *--------------------------*/
    public function bdtaskt1m8c2_02_addInvoice()
    {
        $data['title']      = get_phrases(['add', 'service','invoice']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['branchInfo'] = $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_03_getRow('branch', array('id'=>session('branchId')));
        $data['isDTables']  = true;
        $data['isDateTimes']= true;
        $data['module']     = "Account";
        $data['order']      = $this->request->getVar('order');
        $data['page']       = "invoices/add_service_invoice";
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Get services by Appoint Id 
    *--------------------------*/
    public function bdtaskt1m8c2_03_serviceByAppId($aid)
    {
        $data = $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_02_getServByAppId($aid);
        echo json_encode($data);
    }

    /*--------------------------
    | Get services by Patient Id
    *--------------------------*/
    public function bdtaskt1m8c2_04_serviceByPId($pid)
    {
        $data = $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_04_getServByPId($pid);
        echo json_encode($data);
    }

    /*--------------------------
    | Create service invoice 
    *--------------------------*/
    public function bdtaskt1m8c2_05_createInvoice()
    { 
        //$this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_16_updateInvService(array('id'=>252), 0, 124, 2);exit;
        $ID          = $this->request->getVar('ID');
        $COAID       = $this->request->getVar('acc_head');
        $balance     = $this->request->getVar('acc_balance');
        $isCredit    = $this->request->getVar('isCredit');
        $doctorId    = $this->request->getVar('doctor_id');
        $total_price = $this->request->getVar('total_price');
        $total_advance= $this->request->getVar('total_advance');
        $netAmount   = $this->request->getVar('netAmount');
        $nameE       = $this->request->getVar('nameE');
        $file_no     = $this->request->getVar('file_no');
        $total_discount= $this->request->getVar('total_discount');
        $total_vat   = $this->request->getVar('sub_vat');
        $due         = $this->request->getVar('due_total');
        $grand_total = $this->request->getVar('grand_total');
        $payable     = $this->request->getVar('payable');
        $action      = $this->request->getVar('action');
        $total_redeem= $this->request->getVar('total_redeem');
        // services
        $service_id  = $this->request->getVar('service_id');
        $qty         = $this->request->getVar('qty');
        $remain_qty  = $this->request->getVar('remain_qty');
        $price       = $this->request->getVar('price');
        $off_discount= $this->request->getVar('offer_discount');
        $doc_discount= $this->request->getVar('doctor_discount');
        $over_discount= $this->request->getVar('over_discount');
        $redeem_discount= $this->request->getVar('redeem_discount');
        $after_dis   = $this->request->getVar('after_dis');
        $noCommission= $this->request->getVar('no_comm_amount');
        $isCommission= $this->request->getVar('isCommission');
        $vat         = $this->request->getVar('vat');
        $subtotal    = $this->request->getVar('subtotal');
        $points      = $this->request->getVar('points');
        $point_info  = $this->request->getVar('point_info');
        $isOffer     = $this->request->getVar('isOffer');

        // payment info 
        $pay_method  = $this->request->getVar('pm_name');
        $pay_amount  = $this->request->getVar('pay_amount');
        $ac_no       = $this->request->getVar('ac_no');
        $exdate      = $this->request->getVar('edate');
        $bank_name   = $this->request->getVar('bank_name');

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'patient_id'  => ['label' => get_phrases(['patient', 'name']), 'rules'=> 'required'],
                'service_id'  => ['label' => get_phrases(['service', 'name']), 'rules'=> 'required']
            ];
        }
        
        if (! $this->validate($rules)) {
            $this->session->setFlashdata('exception', $this->validator->listErrors());
            return redirect()->route('account/services/addInvoice');
        }else{
            $aFile = $this->request->getFile('attach_file');
            if(!empty($aFile)){
                $attachFile = $this->base_03_fileUpload->doUpload('/assets/attached/invoices', $aFile);
            }else{
                $attachFile = null;
            }
            
            $totalPay = 0;
            if(!empty($pay_method) && is_array($pay_method)){
                for ($j=0; $j < sizeof($pay_method); $j++) { 
                    $totalPay += $pay_amount[$j];
                }
            }

            if($payable == $totalPay){

                $receipt     = $payable;
                $totalB = 0.00;
                if($isCredit==1){
                    $pay = 2;
                }else if($due > 0){
                    $pay = 0;
                }else{
                    $pay = 1;
                }

                $data = array(
                    'patient_id'   => $this->request->getVar('patient_id'), 
                    'branch_id'    => session('branchId'), 
                    'advance'      => 0.00,
                    'total'        => $total_price, 
                    'receipt'      => $receipt, 
                    'due'          => $due, 
                    'vat'          => $total_vat, 
                    'vat_percent'  => $this->request->getVar('vat_percent'), 
                    'grand_total'  => $grand_total, 
                    'invoice_date' => date('Y-m-d', strtotime(str_replace('/', '-', $this->request->getVar('invoice_date')))), 
                    'created_by'   => session('id'),
                    'doctor_id'    => $doctorId,
                    'isPaid'       => $pay,
                    'isCredit'     => $isCredit,
                    'attach_file'  => $attachFile,
                    'status'       => 1
                );
                if($action=='add'){
                    $result = $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_01_Insert('service_invoices',$data);
                    if($result){
                        $cashPrice = 0;
                        $creditPrice = 0;
                        $cashOrCard = 0;
                        $creditAmount = 0;
                        $creditmethod = 120;

                        if(!empty($pay_method) && is_array($pay_method)){
                            $payments = array();
                            for ($i=0; $i < sizeof($pay_method); $i++) { 
                                $payments[$i] = array(
                                    'invoice_id'       => $result, 
                                    'payment_name'     => $pay_method[$i], 
                                    'card_or_cheque_no'=> !isset($ac_no[$i])?'':$ac_no[$i], 
                                    'bank_name'        => !isset($bank_name[$i])?'':$bank_name[$i], 
                                    'expiry_date'      => !isset($exdate[$i])?'0000-00-00':date('Y-m-d', strtotime($exdate[$i])), 
                                    'amount'           => $pay_amount[$i]
                                );
                                //120=cash, 121=span, 122=visa, 123=master, 124=amex, 125=bank transfer, |126=credit, 127 = paid advance
                                if($pay_method[$i]=='120'){
                                    $cashOrCard +=$pay_amount[$i];
                                    //ACC cash in hand receivable  Debit
                                    $receiptMethod = array(
                                        'VNo'         => 'SINV-'.$result,
                                        'Vtype'       => 'SINV',
                                        'VDate'       => date('Y-m-d'),
                                        'COAID'       => 121100001,
                                        'Narration'   => 'Service Invoice from '.$file_no.'-'.$nameE,
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
                                    $cashOrCard +=$pay_amount[$i];
                                    //ACC span receivable  Debit
                                    $receiptMethod = array(
                                        'VNo'         => 'SINV-'.$result,
                                        'Vtype'       => 'SINV',
                                        'VDate'       => date('Y-m-d'),
                                        'COAID'       => 121100002,
                                        'Narration'   => 'Service Invoice From Span '.$file_no.'-'.$nameE,
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
                                    $cashOrCard +=$pay_amount[$i];
                                    //ACC visa receivable  Debit
                                    $receiptMethod = array(
                                        'VNo'         => 'SINV-'.$result,
                                        'Vtype'       => 'SINV',
                                        'VDate'       => date('Y-m-d'),
                                        'COAID'       => 121100004,
                                        'Narration'   => 'Service Invoice from Visa - '.$file_no.'-'.$nameE,
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
                                    $cashOrCard +=$pay_amount[$i];
                                    //ACC span receivable  Debit
                                    $receiptMethod = array(
                                        'VNo'         => 'SINV-'.$result,
                                        'Vtype'       => 'SINV',
                                        'VDate'       => date('Y-m-d'),
                                        'COAID'       => 121100003,
                                        'Narration'   => 'Service Invoice from Master - '.$file_no.'-'.$nameE,
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
                                    $cashOrCard +=$pay_amount[$i];
                                    //ACC Amex Card receivable  Debit
                                    $receiptMethod = array(
                                        'VNo'         => 'SINV-'.$result,
                                        'Vtype'       => 'SINV',
                                        'VDate'       => date('Y-m-d'),
                                        'COAID'       => 121100005,
                                        'Narration'   => 'Service Invoice from Amex Card - '.$file_no.'-'.$nameE,
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
                                    $cashOrCard +=$pay_amount[$i];
                                    //ACC bank transfer receivable  Debit
                                    $receiptMethod = array(
                                        'VNo'         => 'SINV-'.$result,
                                        'Vtype'       => 'SINV',
                                        'VDate'       => date('Y-m-d'),
                                        'COAID'       => 121100006,
                                        'Narration'   => 'Service Invoice from bank transfer - '.$file_no.'-'.$nameE,
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
                                    $cashOrCard +=$pay_amount[$i];
                                    //ACC check receivable  Debit
                                    $receiptMethod = array(
                                        'VNo'         => 'SINV-'.$result,
                                        'Vtype'       => 'SINV',
                                        'VDate'       => date('Y-m-d'),
                                        'COAID'       => 121100011,
                                        'Narration'   => 'Service Invoice from check - '.$file_no.'-'.$nameE,
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
                                }else if($pay_method[$i]=='127'){
                                    $cashOrCard +=$pay_amount[$i];
                                    // patient paid advance for Debit 
                                    $receiptMethod = array(
                                        'VNo'         => 'SINV-'.$result,
                                        'Vtype'       => 'SINV',
                                        'VDate'       => date('Y-m-d'),
                                        'COAID'       => $COAID,
                                        'Narration'   => 'Patient paid from advance for Service Invoice - '.$file_no.'-'.$nameE,
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

                                    // patient deduct advance for credit 
                                    $salesAdvInCredit = array(
                                        'VNo'         => 'SINV-'.$result,
                                        'Vtype'       => 'SINV',
                                        'VDate'       => date('Y-m-d'),
                                        'COAID'       => 310000003, //SalesPaidAdvance
                                        'Narration'   => 'Sales paid advance credit for Service Invoice - '.$file_no.'-'.$nameE,
                                        'Debit'       => 0,
                                        'Credit'      => $pay_amount[$i],
                                        'PatientID'   => $data['patient_id'],
                                        'DoctorId'    => $doctorId,
                                        'BranchID'     => session('branchId'),
                                        'IsPosted'    => 1,
                                        'CreateBy'    => session('id'),
                                        'CreateDate'  => date('Y-m-d H:i:s'),
                                        'IsAppove'    => 1
                                    ); 
                                    if($balance > $pay_amount[$i]){
                                        $totalB = $balance - $pay_amount[$i];
                                    }else{
                                        $totalB = 0.00;
                                    }
                                    if($total_advance > $pay_amount[$i]){
                                        $remindT = $total_advance - $pay_amount[$i];
                                    }else{
                                        $remindT = 0.00;
                                    }
                                    if(!empty($pay_amount[$i])){
                                        $total_price = $total_price - $pay_amount[$i];
                                    }
                                    $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$salesAdvInCredit);
                                    $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_25_updatePatientBLDebit($pay_amount[$i], array('patient_id'=>$data['patient_id']));
                                    $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_02_Update('vouchers', array('remaining_balance'=>$remindT), array('patient_id' => $data['patient_id'], 'package_id'=>$this->request->getVar('package_id')));
                                }else if($pay_method[$i]=='130' || $pay_method[$i]=='150'){
                                    if($receipt > 0){
                                        $creditPrice = ($total_price*$pay_amount[$i])/$receipt;
                                    }else{
                                        $creditPrice = 0;
                                    }
                                    
                                    $cashPrice = $total_price - $creditPrice;
                                    $creditAmount = $pay_amount[$i];
                                    $creditmethod = $pay_method[$i];
                                    // account sales credit in credit
                                    $accSalesCredit = array(
                                        'VNo'         => 'SINV-'.$result,
                                        'Vtype'       => 'SINV',
                                        'VDate'       => date('Y-m-d'),
                                        'COAID'       => 310000002, //SalesCredit
                                        'Narration'   => 'Patient Service Credit Invoice from '.$file_no.'-'.$nameE,
                                        'Debit'       => 0,
                                        'Credit'      => $creditPrice,
                                        'PatientID'   => $data['patient_id'],
                                        'BranchID'     => session('branchId'),
                                        'IsPosted'    => 1,
                                        'CreateBy'    => session('id'),
                                        'CreateDate'  => date('Y-m-d H:i:s'),
                                        'IsAppove'    => 0
                                    );
                                    $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$accSalesCredit);

                                    // patient due invoice for debit
                                    $receiptMethod = array(
                                        'VNo'         => 'SINV-'.$result,
                                        'Vtype'       => 'SINV',
                                        'VDate'       => date('Y-m-d'),
                                        'COAID'       => $COAID,
                                        'Narration'   => 'Credit Invoice Debited '.$file_no.'-'.$nameE,
                                        'Debit'       => $pay_amount[$i],
                                        'Credit'      => 0,
                                        'PatientID'   => $data['patient_id'],
                                        'BranchID'     => session('branchId'),
                                        'IsPosted'    => 1,
                                        'CreateBy'    => session('id'),
                                        'CreateDate'  => date('Y-m-d H:i:s'),
                                        'IsAppove'    => 0
                                    ); 
                                }else{
                                    
                                }
                                // payment
                                $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$receiptMethod);
                            }
                            $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_01_Insert_Batch('service_invoice_payment',$payments);
                        }

                        if(!empty($ID) && is_array($ID)){
                            $details = array();
                            $serviceCount = sizeof($ID);
                            if($isCredit==1){
                                $divideCredit = floatval($creditAmount)/$serviceCount;
                            }else{
                                $divideCredit = 0.00;
                            }
                            
                            $j=0;
                            $totalRedeemPoints = 0;
                            $totalGainPoints = 0;
                            foreach ($ID as $key => $row) {
                                $redeems = !empty($points[$key])?$points[$key]:0;
                                if($isOffer[$key]==0){
                                    $pInfos = explode('_', $point_info[$key]);
                                    $perPointAmt = $pInfos[0];
                                    $minGain = $pInfos[4];
                                    $maxGain = $pInfos[5];
                                    $maxPointAmt = $perPointAmt*$maxGain;
                                    $aftDisAmt = $after_dis[$key];
                                    if($aftDisAmt >= $perPointAmt && $aftDisAmt < $maxPointAmt){
                                        $gainP = $aftDisAmt/$perPointAmt;
                                    }else if($aftDisAmt > $maxPointAmt){
                                        $gainP = $maxGain;
                                    }else{
                                        $gainP = 0;
                                    }
                                }else{
                                    $gainP = 0;
                                }

                                $totalRedeemPoints += $redeems;
                                $totalGainPoints += $gainP;
                                $reqty = intval($remain_qty[$key]) - intval($qty[$key]);
                                $details[$j] = array(
                                    'invoice_id'     => $result, 
                                    'app_service_id' => $service_id[$key], 
                                    'qty'            => $qty[$key], 
                                    'price'          => $price[$key], 
                                    'offer_discount' => $off_discount[$key], 
                                    'doctor_discount'=> $doc_discount[$key], 
                                    'over_limit_discount'=> $over_discount[$key], 
                                    'redeem_discount'=> $redeem_discount[$key], 
                                    'no_commission_amt'=> $noCommission[$key], 
                                    'vat'            => $vat[$key],
                                    'amount'         => $subtotal[$key],
                                    'redeem_points'  => $redeems,
                                    'gain_points'    => $gainP,
                                    'remaining_points'=>$gainP,
                                    'isComPay'       => $isCommission[$key],
                                    'creditAmt'      => $divideCredit,
                                    'creditedOn'     => (int)$creditmethod
                                );

                                if($redeems > 0){
                                    $getPointList = $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_15_getPatientAvailablePoints($data['patient_id'], date('Y-m-d'));
        
                                    if(!empty($getPointList)){
                                        $reqPoints = $redeems;
                                        foreach ($getPointList as $value) {
                                            $rmWhere = array('id'=>$value->id);
                                            if($reqPoints > 0){
                                                if($value->remaining_points > $reqPoints){
                                                    $new_rem_points = $value->remaining_points-$reqPoints;
                                                    //update query new_rem_points
                                                    $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_02_Update('service_invoice_details', array('remaining_points'=>$new_rem_points), $rmWhere);
                                                    $reqPoints = 0;
                                                    break;
                                                }else{
                                                     $reqPoints = $reqPoints-$value->remaining_points;
                                                     //update rem 0
                                                     $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_02_Update('service_invoice_details', array('remaining_points'=>0), $rmWhere);
                                                }
                                            }else{
                                                break;
                                            }
                                        }
                                    }
                                }
                                if($qty[$key]==$remain_qty[$key]){
                                    $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_16_updateInvService(array('id'=>$row), 1, $result, $reqty, $qty[$key]);
                                }else{
                                    $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_16_updateInvService(array('id'=>$row), 0, $result, $reqty, $qty[$key]);
                                }
                                
                                $j++;
                            }

                            if($isCredit !=1 && $isOffer==0){
                                $where1 = array('patient_id'=>$data['patient_id']);
                                if($totalRedeemPoints > 0){
                                    $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_30_updateColumnAmount('patient',$where1, 'total_redeems', $totalRedeemPoints, true);
                                }
                                if($totalGainPoints > 0){
                                    $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_30_updateColumnAmount('patient',$where1, 'total_points', $totalGainPoints, true);
                                }
                            }
                            
                            $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_01_Insert_Batch('service_invoice_details',$details);
                        }

                        // partially cash payment
                        if($cashOrCard > 0 && $isCredit==1){
                            // account sales cash in credit 
                            $accSalesCashCredit = array(
                                'VNo'         => 'SINV-'.$result,
                                'Vtype'       => 'SINV',
                                'VDate'       => date('Y-m-d'),
                                'COAID'       => 310000001, //SalesCash
                                'Narration'   => 'Credit Invoice - '.$file_no.'-'.$nameE,
                                'Debit'       => 0,
                                'Credit'      => $cashPrice,
                                'PatientID'   => $data['patient_id'],
                                'BranchID'     => session('branchId'),
                                'IsPosted'    => 1,
                                'CreateBy'    => session('id'),
                                'CreateDate'  => date('Y-m-d H:i:s'),
                                'IsAppove'    => 1
                            );
                            $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$accSalesCashCredit);
                        }

                        // if due/credit invoice
                        if($isCredit==0){
                            // patient cash in credit 
                            $patientInCredit = array(
                                'VNo'         => 'SINV-'.$result,
                                'Vtype'       => 'SINV',
                                'VDate'       => date('Y-m-d'),
                                'COAID'       => $COAID,
                                'Narration'   => 'Service Invoice Credited to - '.$COAID,
                                'Debit'       => 0,
                                'Credit'      => $receipt,
                                'PatientID'   => $data['patient_id'],
                                'BranchID'     => session('branchId'),
                                'IsPosted'    => 1,
                                'CreateBy'    => session('id'),
                                'CreateDate'  => date('Y-m-d H:i:s'),
                                'IsAppove'    => 1
                            ); 

                             // patient debit in credit 
                            $patientInDebit = array(
                                'VNo'         => 'SINV-'.$result,
                                'Vtype'       => 'SINV',
                                'VDate'       => date('Y-m-d'),
                                'COAID'       => $COAID,
                                'Narration'   => 'Service Invoice debited from - '.$COAID,
                                'Debit'       => $receipt,
                                'Credit'      => 0,
                                'PatientID'   => $data['patient_id'],
                                'BranchID'     => session('branchId'),
                                'IsPosted'    => 1,
                                'CreateBy'    => session('id'),
                                'CreateDate'  => date('Y-m-d H:i:s'),
                                'IsAppove'    => 1
                            ); 

                            // account sales cash in credit 
                            $accSalesCashCredit = array(
                                'VNo'         => 'SINV-'.$result,
                                'Vtype'       => 'SINV',
                                'VDate'       => date('Y-m-d'),
                                'COAID'       => 310000001, //SalesCash
                                'Narration'   => 'Service Invoice - '.$file_no.'-'.$nameE,
                                'Debit'       => 0,
                                'Credit'      => $total_price,
                                'PatientID'   => $data['patient_id'],
                                'BranchID'     => session('branchId'),
                                'IsPosted'    => 1,
                                'CreateBy'    => session('id'),
                                'CreateDate'  => date('Y-m-d H:i:s'),
                                'IsAppove'    => 1
                            );

                            // patient VAT Output Final (VO) credit
                            $vatInCredit = array(
                                'VNo'         => 'SINV-'.$result,
                                'Vtype'       => 'SINV',
                                'VDate'       => date('Y-m-d'),
                                'COAID'       => 222000002, //VAT Output Final (VO)
                                'Narration'   => 'Total vat in Service Invoice from '.$file_no.'-'.$nameE,
                                'Debit'       => 0,
                                'Credit'      => $total_vat,
                                'PatientID'   => $data['patient_id'],
                                'BranchID'     => session('branchId'),
                                'IsPosted'    => 1,
                                'CreateBy'    => session('id'),
                                'CreateDate'  => date('Y-m-d H:i:s'),
                                'IsAppove'    => 1
                            ); 

                            // discount in Debit 
                            $discountInDebit = array(
                                'VNo'         => 'SINV-'.$result,
                                'Vtype'       => 'SINV',
                                'VDate'       => date('Y-m-d'),
                                'COAID'       => 310000010, //GivenDiscount
                                'Narration'   => 'Total discount in Service Invoice from '.$file_no.'-'.$nameE,
                                'Debit'       => $total_discount,
                                'Credit'      => 0,
                                'PatientID'   => $data['patient_id'],
                                'BranchID'     => session('branchId'),
                                'IsPosted'    => 1,
                                'CreateBy'    => session('id'),
                                'CreateDate'  => date('Y-m-d H:i:s'),
                                'IsAppove'    => 1
                            ); 
                            $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$patientInCredit);
                            $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$patientInDebit);
                            $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$accSalesCashCredit);
                        }else{
                            //  // patient cash in credit 
                            // $patientInCredit = array(
                            //     'VNo'         => 'SINV-'.$result,
                            //     'Vtype'       => 'SINV',
                            //     'VDate'       => date('Y-m-d'),
                            //     'COAID'       => $COAID,
                            //     'Narration'   => 'Service Invoice Credited to - '.$COAID,
                            //     'Debit'       => 0,
                            //     'Credit'      => $receipt,
                            //     'PatientID'   => $data['patient_id'],
                            //     'BranchID'     => session('branchId'),
                            //     'IsPosted'    => 1,
                            //     'CreateBy'    => session('id'),
                            //     'CreateDate'  => date('Y-m-d H:i:s'),
                            //     'IsAppove'    => 0
                            // ); 

                            // patient VAT Output Final (VO) credit
                            $vatInCredit = array(
                                'VNo'         => 'SINV-'.$result,
                                'Vtype'       => 'SINV',
                                'VDate'       => date('Y-m-d'),
                                'COAID'       => 222000002, //VAT Output Final (VO)
                                'Narration'   => 'Total vat in Service Invoice from '.$file_no.'-'.$nameE,
                                'Debit'       => 0,
                                'Credit'      => $total_vat,
                                'PatientID'   => $data['patient_id'],
                                'BranchID'     => session('branchId'),
                                'IsPosted'    => 1,
                                'CreateBy'    => session('id'),
                                'CreateDate'  => date('Y-m-d H:i:s'),
                                'IsAppove'    => 0
                            ); 

                            // discount in Debit 
                            $discountInDebit = array(
                                'VNo'         => 'SINV-'.$result,
                                'Vtype'       => 'SINV',
                                'VDate'       => date('Y-m-d'),
                                'COAID'       => 310000010, //GivenDiscount
                                'Narration'   => 'Total discount in Service Invoice from '.$file_no.'-'.$nameE,
                                'Debit'       => $total_discount,
                                'Credit'      => 0,
                                'PatientID'   => $data['patient_id'],
                                'BranchID'     => session('branchId'),
                                'IsPosted'    => 1,
                                'CreateBy'    => session('id'),
                                'CreateDate'  => date('Y-m-d H:i:s'),
                                'IsAppove'    => 0
                            ); 
                        } 
                        
                        // transaction total patient credit
                        $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$vatInCredit);
                        $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$discountInDebit);

                        $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['service', 'invoice']), get_phrases(['created']), $result, 'service_invoices', 1);
                        // send invoice
                        //$this->generatePDFInvoice($result);
                        $this->session->setFlashdata('message', get_phrases(['created', 'Successfully']));
                        return redirect()->to('detailsInvoice/'.$result);
                    }else{
                        $this->session->setFlashdata('exception', get_phrases(['something', 'went', 'wrong']));
                    }
                    return redirect()->route('account/services/addInvoice');
                }else{
                    
                }
            }else{
                $this->session->setFlashdata('exception', get_notify('payment_amount_is_not_equal_to_payable_amount'));
                return redirect()->route('account/services/addInvoice');
            }
        }
    }

    /*--------------------------
    | View service invoice info
    *--------------------------*/
    public function bdtaskt1m8c2_06_detailsInvoice($id, $params = null)
    {
        $data['title']      = get_phrases(['details', 'service','invoice']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['module']     = "Account";
        $data['website'] = $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['details'] = $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_03_getInvDetailsId($id);
        $data['phrases'] = $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_03_getRow('setting_vouchers', array('type'=>'service_invoice'));
        $data['lang']    = session('defaultLang');
        // echo "<pre>";
        // print_r($data['details']);die();
        
        $data['page']       = "invoices/service_details";
        return $this->base_01_template->layout($data);
    }

    function generatePDF($id){

        $dompdf = new \Dompdf\Dompdf(); 

        $data['website'] = $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['details'] = $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_03_getInvDetailsId($id);
        $data['phrases'] = $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_03_getRow('setting_vouchers', array('type'=>'service_invoice'));
        $data['lang']    = session('defaultLang');
        // Sending data to view file
        $dompdf->loadHtml(view('App\Modules\Account\Views\invoices\invoice_pdf', $data));
        // setting paper to portrait, also we have landscape
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        // Download pdf
        $dompdf->stream('invoice_'.$id.'.pdf'); 
        // to give pdf file name
        // $dompdf->stream("myfile");
        return redirect()->to('detailsInvoice/'.$id);
    }

    function generatePDFInvoice($id){

        $dompdf = new \Dompdf\Dompdf(); 

        $data['website'] = $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['details'] = $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_03_getInvDetailsId($id);
        // Sending data to view file
        $dompdf->loadHtml(view('App\Modules\Account\Views\invoices\invoice_pdf', $data));
        // setting paper to portrait, also we have landscape
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        // Download pdf
        //$dompdf->stream('invoice_'.$id.'.pdf'); 
        // to give pdf file name
        // $dompdf->stream("myfile");
        $pdf = $dompdf->output();
        file_put_contents('./assets/data/invoices/invoice_'.$id.'.pdf',$pdf);
        $this->send_usermail($data['website']->email, $data['details']->email, base_url().'/assets/data/invoices/invoice_'.$id.'.pdf');
        //return redirect()->to('detailsInvoice/'.$id);
    }

    public function send_usermail($from, $to, $file){
        helper(['form','url']);
        $message = "We are attached your bill info. Please see your service incoice.";
        $email = \Config\Services::email();
        
        $email->setFrom($from, 'Service Invouce');
        $email->setTo($to);
        $email->setSubject('Service Invouce');
        $email->setMessage($message);
        $email->attach($file);
       //$email->send();
       $email->send();
    }

    /*--------------------------
    | Delete service invoice
    *--------------------------*/
    public function bdtaskt1m8c2_07_deleteInvoice()
    { 
        // echo "<pre>";
        // print_r($this->request->getVar());die();
        $type    = $this->request->getVar('type');
        $id      = $this->request->getVar('invoice_id');
        $MesTitle = get_phrases(['invoice', 'record']);
        if($type=='undo'){
            $data = array(
                'isDelReq'     => 0
            );
            $data = $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_02_Update('service_invoices', $data, array('id' => $id));
            if($data){
                //$this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_02_Update('acc_transaction', array('IsAppove '=>0), array('VNo' => 'SINV-'.$id));
                $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['service', 'invoice']), get_phrases(['undo', 'request']), $id, 'service_invoices', 2);
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
                'delete_reason'=> implode(",", (array)$reasons),
                'ref_no'       => !empty($ref_no)?$ref_no:0,
                'isDelReq'     => 1,
                'del_req_by'   => session('id'),
                'del_req_date' => date('Y-m-d H:i:s')
            );
            
            $data = $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_02_Update('service_invoices', $data, array('id' => $id));
            if($data){
                $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['service', 'invoice']), get_phrases(['deleted', 'request']), $id, 'service_invoices', 2);
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
    | Get invoice info Id 
    *--------------------------*/
    public function bdtaskt1m8c2_08_getInvoiceInfo($invoiceId)
    {
        $data = $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_03_getRow('service_invoices', array('id'=>$invoiceId));
        echo json_encode($data);
    }

    /*--------------------------
    | Delete service invoice
    *--------------------------*/
    public function bdtaskt1m8c2_09_dateModify()
    { 
        $id          = $this->request->getVar('invoice_id');
        $patient_id  = $this->request->getVar('patient_id');
        $doctor_id   = $this->request->getVar('doctor_id');
        $branch_id   = $this->request->getVar('branch_id');

        $exist_payment  = $this->request->getVar('exist_payment');
        $pay_method  = $this->request->getVar('pm_name');
        $pay_amount  = $this->request->getVar('pay_amount');
        $ac_no       = $this->request->getVar('ac_no');
        $exdate      = $this->request->getVar('edate');
        $bank_name   = $this->request->getVar('bank_name');
        $date = $this->request->getVar('old_date');
        $data = array(
            'invoice_date'   => date('Y-m-d', strtotime($date)),
            'updated_by'     => session('id'),
            'updated_date'   => date('Y-m-d H:i:s')
        );
        $total = 0;
        if(!empty($exist_payment)){
            for ($j=0; $j < sizeof($exist_payment); $j++) { 
                $total += $exist_payment[$j];
            }
        }
        $MesTitle = get_phrases(['voucher', 'record']);
        $blance = $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_03_getRow('service_invoices', array('id' => $id));
        // echo "<pre>";
        // print_r($blance);die();
        if($blance->receipt==$total){
            $method = array(121100005, 121100006, 121100001, 121100011, 121100003, 121100002, 121100004, 121100012, 121100013);
            $methodId = array(120, 121, 122, 123, 124, 125, 126);
            $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_12_deleteInvPayMethod($id, $methodId);
            
            $data = $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_02_Update('service_invoices', $data, array('id' => $id));
            if($data){
                $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_13_deleteTransMethod('SINV-'.$id, $method);
                if(!empty($pay_method)){
                    $details   = 'Updated payment method, Voucher no - '.$id;
                    for ($i=0; $i < sizeof($pay_method); $i++) { 
                        $payments = array(
                            'invoice_id'       => $id, 
                            'payment_name'     => $pay_method[$i], 
                            'card_or_cheque_no'=> !isset($ac_no[$i])?'':$ac_no[$i], 
                            'bank_name'        => !isset($bank_name[$i])?'':$bank_name[$i], 
                            'expiry_date'      => !isset($exdate[$i])?'':date('Y-m-d', strtotime($exdate[$i])), 
                            'amount'           => $pay_amount[$i]
                        );
                        $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_01_Insert('service_invoice_payment', $payments);
                        if($pay_method[$i]==120){
                            $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_21_AccTrans('SINV-'.$id, 'SINV', 121100001, $details, $pay_amount[$i], 0, $patient_id, $doctor_id, 1, $branch_id, $date, null, $blance->created_by);
                        }else if($pay_method[$i]==121){
                            $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_21_AccTrans('SINV-'.$id, 'SINV', 121100002, $details, $pay_amount[$i], 0, $patient_id, $doctor_id, 1, $branch_id, $date, null, $blance->created_by);
                        }else if($pay_method[$i]==122){
                            $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_21_AccTrans('SINV-'.$id, 'SINV', 121100004, $details, $pay_amount[$i], 0, $patient_id, $doctor_id, 1, $branch_id, $date, null, $blance->created_by);
                        }else if($pay_method[$i]==123){
                            $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_21_AccTrans('SINV-'.$id, 'SINV', 121100003, $details, $pay_amount[$i], 0, $patient_id, $doctor_id, 1, $branch_id, $date, null, $blance->created_by);
                        }else if($pay_method[$i]==124){
                            $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_21_AccTrans('SINV-'.$id, 'SINV', 121100005, $details, $pay_amount[$i], 0, $patient_id, $doctor_id, 1, $branch_id, $date, null, $blance->created_by);
                        }else if($pay_method[$i]==125){
                            $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_21_AccTrans('SINV-'.$id, 'SINV', 121100006, $details, $pay_amount[$i], 0, $patient_id, $doctor_id, 1, $branch_id, $date, null, $blance->created_by);
                        }else if($pay_method[$i]==126){
                            $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_21_AccTrans('SINV-'.$id, 'SINV', 121100011, $details, $pay_amount[$i], 0, $patient_id, $doctor_id, 1, $branch_id, $date, null, $blance->created_by);
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

                $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_02_Update('acc_transaction', $trans, array('VNo' => 'SINV-'.$id));
                $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['service', 'invoice']), get_phrases(['updated']), $id, 'service_invoices', 2);
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
    | Get delete invoices info
    *--------------------------*/
    public function bdtaskt1m8c2_10_getDeleteInvoices()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_05_getAllDeletes($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | Get packages by patient Id 
    *--------------------------*/
    public function bdtaskt1m8c2_11_getPacksByPId($pid)
    {
        $data = $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_06_getPacksByPId($pid);
        echo json_encode($data);
    }

    /*--------------------------
    | Get packages by patient Id 
    *--------------------------*/
    public function bdtaskt1m8c2_12_getServicesByPackId($pack)
    {
        $pid = $this->request->getVar('patient_id');
        $appoint_id = $this->request->getVar('appoint_id');
        $data = $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_07_getServicesByPackId($pack, $pid, $appoint_id);
        echo json_encode($data);
    }

    /*--------------------------
    | Get services by doctor Id 
    *--------------------------*/
    public function bdtaskt1m8c2_13_getServicesByDocId($docId)
    {
        $pid = $this->request->getVar('patient_id');
        $data = $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_08_getServicesByDocId($docId, $pid);
        echo json_encode($data);
    }

    /*--------------------------
    | Approved Delete Request
    *--------------------------*/
    public function bdtaskt1m8c2_15_approvedDelReq()
    { 
        $id        = $this->request->getVar('invoice_id');
        $acc_head  = $this->request->getVar('patient_id');
        $data = array(
            'del_appr_by'   => session('id'),
            'del_appr_date' => date('Y-m-d H:i:s'),
            'status'        => 0
        );
        $MesTitle = get_phrases(['invoice', 'record']);
        $checkConsump = $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_10_getInvoiceConsumption($id);
        if(!empty($checkConsump)){
            $response = array(
                'success'  => false,
                'message'  => get_phrases(['please', 'return', 'consumption', 'items', 'first']).'!',
                'title'    => $MesTitle
            );
        }else{
            $data = $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_02_Update('service_invoices', $data, array('id' => $id));
            if($data){
                $credit = $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_09_getDelInvCredit($id);
                if(!empty($credit)){
                    $amount = $credit->amount > 0?$credit->amount:0;
                    $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_26_updatePatientBLCredit($amount, array('acc_head'=>$acc_head));
                    $where = array('branch_id'=>session('branchId'), 'headCode '=>$acc_head);
                    $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_30_updateColumnAmount('acc_coa_balance', $where, 'balance', $amount, true);
                }
                $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_02_Update('acc_transaction', array('IsAppove '=>2), array('VNo' => 'SINV-'.$id));
                //$this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_06_Deleted('acc_transaction', array('VNo' => 'SINV-'.$id));
                $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['service', 'invoice']), get_phrases(['deleted']), $id, 'service_invoices', 3);
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
        }
        echo json_encode($response);
    }

    /*--------------------------
    | Get services by order service Id 
    *--------------------------*/
    public function bdtaskt1m8c2_16_invoiceInfoByServiceOrder($id)
    {
        $data = $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_10_invoiceInfoByServiceOrder($id);
        echo json_encode($data);
    }

    /*--------------------------
    | Get invoice info Id 
    *--------------------------*/
    public function bdtaskt1m8c2_17_getInvoicePayInfo($invoiceId)
    {
        $data = $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_11_getInvDetailsForModify($invoiceId);
        echo json_encode($data);
    }

    /*--------------------------
    | Get consumption list 
    *--------------------------*/
    public function bdtaskt1m8c2_18_invoiceConsumptions($invoiceId)
    {
        $approved = $this->request->getVar('approved');
        $service_id = $this->request->getVar('service_id');
        $data['results'] = $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_14_getInvoiceConsumption($invoiceId, $approved, $service_id);

        $comsumed = view('App\Modules\Account\Views\invoices\invoice_consumed_list', $data);
        echo json_encode(array('data'=>$comsumed));
    }

    /*--------------------------
    | Get patient available points 
    *--------------------------*/
    public function bdtaskt1m8c2_19_patientAvailablePoints()
    {
        $date = $this->request->getVar('date');
        $patient_id = $this->request->getVar('patient_id');
        $data = $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_14_getPatientPoints($patient_id, $date);
        echo json_encode($data);
    }

}
