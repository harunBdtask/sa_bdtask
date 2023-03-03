<?php namespace App\Modules\Account\Controllers;
use CodeIgniter\Controller;
use App\Modules\Account\Models\Bdtaskt1m8BlncTransModel;
use App\Models\Bdtaskt1m1CommonModel;
class Bdtaskt1m8c8BalanceTransfer extends BaseController
{
    private $bdtaskt1m8c8_01_rvModel;
    private $bdtaskt1m8c8_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bdtaskt1m8c8_01_btModel = new Bdtaskt1m8BlncTransModel();
        $this->bdtaskt1m8c8_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Balance transfer List
    *--------------------------*/
    public function bdtaskt1m8c8_01_PatientBlnTrans()
    {
        $data['title']      = get_phrases(['patient', 'balance', 'transfer']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['isDTables']= true;
        $data['module']     = "Account";
        $data['page']       = "balance_transfer/patients";
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Get all Balance transfer list
    *--------------------------*/
    public function bdtaskt1m8c8_02_getPntBTransfer()
    {
        $postData = $this->request->getVar();
        $data     = $this->bdtaskt1m8c8_01_btModel->bdtaskt1m8_01_getPntBTransfer($postData);
        echo json_encode($data); 
    }

    /*--------------------------
    | Get patient current balance
    *--------------------------*/
    public function bdtaskt1m8c8_03_pntCurrBalance($pntId, $branch_id)
    {
        $data     = $this->bdtaskt1m8c8_01_btModel->bdtaskt1m8_02_getPntBalance($pntId, $branch_id);
        echo json_encode($data); 
    }

    /*--------------------------
    | Save transaction
    *--------------------------*/
    public function bdtaskt1m8c8_04_savePntTrans()
    { 

        $from_patient= $this->request->getVar('from_patient');
        $to_patient  = $this->request->getVar('to_patient');
        $from_head   = $this->request->getVar('from_head');
        $to_head     = $this->request->getVar('to_head');
        $from_name   = $this->request->getVar('from_name');
        $to_name     = $this->request->getVar('to_name');
        $from_bln    = $this->request->getVar('from_current_blnc');
        $to_bln      = $this->request->getVar('to_current_blnc');
        $trans_amount= $this->request->getVar('trans_amount');

        $data = array(
            'branch_id'   => session('branchId'),
            'trans_id'    => $this->request->getVar('trans_id'),
            'from_patient'=> $from_patient,
            'to_patient'  => $to_patient,  
            'amount'      => $trans_amount, 
            'trans_date'  => $this->request->getVar('trans_date'), 
            'remarks'     => $this->request->getVar('remarks'), 
            'created_by'  => session('id'),
            'isApproved'  => 0 //$isApproved
        );
        $MesTitle = get_phrases(['patient', 'balance']);
        if($trans_amount > 500){
            $response = array(
                'success'  => false,
                'message'  => 'Can not transfer more than 500 amount!',
                'title'    => $MesTitle
            );
        }else{
            $result = $this->bdtaskt1m8c8_02_CmModel->bdtaskt1m1_01_Insert('patient_balance_transfer',$data);
            if($result){
                // Store log data
                $this->bdtaskt1m8c8_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['patient', 'balance', 'transfer']), get_phrases(['updated']), $result, 'patient_balance_transfer', 2);
                 $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['transfer', 'successfully']),
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
    | Get patient current balance
    *--------------------------*/
    public function bdtaskt1m8c8_05_pntTransById($transId)
    {
        $data     = $this->bdtaskt1m8c8_01_btModel->bdtaskt1m8_03_getPntTransById($transId);
        echo json_encode($data); 
    }

    /*--------------------------
    | Save transaction
    *--------------------------*/
    public function bdtaskt1m8c8_06_approvedPntTrans()
    { 
        $transId     = $this->request->getVar('transId');
        $from_patient= $this->request->getVar('from_id');
        $to_patient  = $this->request->getVar('to_id');
        $from_head   = $this->request->getVar('from_head');
        $to_head     = $this->request->getVar('to_head');
        $from_name   = $this->request->getVar('from_name');
        $to_name     = $this->request->getVar('to_name');
        $from_bln    = $this->request->getVar('from_current_blnc');
        $to_bln      = $this->request->getVar('to_current_blnc');
        $trans_amount= $this->request->getVar('trans_amount');
        $approved    = $this->request->getVar('approval');
        //print_r($approved);die();

        $data = array(
            'updated_by'  => session('id'),
            'updated_date'=> date('Y-m-d H:i:s'),
            'isApproved'  => $approved
        );

        $result = $this->bdtaskt1m8c8_02_CmModel->bdtaskt1m1_17_Update_getAffectedRows('patient_balance_transfer',$data, array('id'=>$transId));
        $MesTitle = get_phrases(['patient', 'balance']);
        if($result){
            if($approved==1){
                // Store log data
                $this->bdtaskt1m8c8_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['balance', 'transfer', 'approval']), get_phrases(['updated']), $transId, 'patient_balance_transfer', 2);
                // from patient credit 
                $fromPntCredit = array(
                    'VNo'         => 'PBT-'.$result,
                    'Vtype'       => 'Patient Balance Transfer',
                    'VDate'       => date('Y-m-d'),
                    'COAID'       => $from_head,
                    'Narration'   => 'Balance Transfer '.$from_name.' to '.$to_name,
                    'Debit'       => $trans_amount,
                    'Credit'      => 0,
                    'PatientID'   => $to_patient,
                    'BranchID'     => session('branchId'),
                    'IsPosted'    => 1,
                    'CreateBy'    => session('id'),
                    'CreateDate'  => date('Y-m-d H:i:s'),
                    'IsAppove'    => $approved
                ); 

                // to patient Debit 
                $toPntDebit = array(
                    'VNo'         => 'PBT-'.$result,
                    'Vtype'       => 'Patient Balance Transfer',
                    'VDate'       => date('Y-m-d'),
                    'COAID'       => $to_head,
                    'Narration'   => 'Balance Transfer '.$from_name.' to '.$to_name,
                    'Debit'       => 0,
                    'Credit'      => $trans_amount,
                    'PatientID'   => $from_patient,
                    'BranchID'     => session('branchId'),
                    'IsPosted'    => 1,
                    'CreateBy'    => session('id'),
                    'CreateDate'  => date('Y-m-d H:i:s'),
                    'IsAppove'    => $approved
                ); 

                //patient balance
                $fromBalance = floatval($from_bln) - floatval($trans_amount);
                $toBalance   = floatval($to_bln) + floatval($trans_amount);
                // from patient
                $this->bdtaskt1m8c8_02_CmModel->bdtaskt1m1_02_Update('patient', array('balance'=>$fromBalance), array('patient_id' => $from_patient));
                // to patient
                $this->bdtaskt1m8c8_02_CmModel->bdtaskt1m1_02_Update('patient', array('balance'=>$toBalance), array('patient_id' => $to_patient));

                // transaction total patient credit
                $this->bdtaskt1m8c8_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$fromPntCredit);
                $this->bdtaskt1m8c8_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$toPntDebit);
                $response = array(
                    'success'  => true,
                    'message'  => get_phrases(['approved', 'successfully']),
                    'title'    => $MesTitle
                );
            }else{
                $response = array(
                    'success'  => true,
                    'message'  => get_phrases(['rejected', 'successfully']),
                    'title'    => $MesTitle
                );
            }
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
    | Balance transfer List 
    *--------------------------*/
    public function bdtaskt1m8c8_07_branchToBranch()
    {
        $data['title']      = get_phrases(['branch', 'balance', 'transfer']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['isDTables']= true;
        $data['module']     = "Account";
        $data['page']       = "balance_transfer/branch_to_branch";
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Get branch list without selected branch
    *--------------------------*/
    public function bdtaskt1m8c8_08_branchList()
    {
        $column = ["id, CONCAT(nameE) as text"];
        $data     = $this->bdtaskt1m8c8_02_CmModel->bdtaskt1m1_07_getSelect2Data('branch', array('id !='=>session('branchId')), $column);
        echo json_encode($data); 
    }

    /*--------------------------
    | Get patient current balance by code
    *--------------------------*/
    public function bdtaskt1m8c8_09_patientBalanceByCode($pntId, $branch_id)
    {
        $data     = $this->bdtaskt1m8c8_01_btModel->bdtaskt1m8_04_getBalanceByCode($pntId, $branch_id);
        echo json_encode($data); 
    }

    /*--------------------------
    | Save branch balance transaction
    *--------------------------*/
    public function bdtaskt1m8c8_10_saveBranchBalTrans()
    { 

        $patient_id  = $this->request->getVar('patient_id');
        $to_branch     = $this->request->getVar('to_branch');
        $trans_amount= $this->request->getVar('trans_amount');

        $data = array(
            'branch_id'   => session('branchId'),
            'trans_id'    => $this->request->getVar('trans_id'),
            'to_branch'   => $to_branch,
            'head_code'   => $patient_id,  
            'amount'      => $trans_amount, 
            'trans_date'  => $this->request->getVar('trans_date'), 
            'remarks'     => $this->request->getVar('remarks'), 
            'created_by'  => session('id'),
            'isApproved'  => 0 //$isApproved
        );
        $MesTitle = get_phrases(['patient', 'balance']);
        $result = $this->bdtaskt1m8c8_02_CmModel->bdtaskt1m1_01_Insert('branch_balance_transfer',$data);
        if($result){
            // Store log data
            $this->bdtaskt1m8c8_02_CmModel->bdtaskt1m1_22_addActivityLog('Branch to branch balance', 'Created', $result, 'branch_balance_transfer', 1);
             $response = array(
                'success'  =>true,
                'message'  => get_phrases(['transfer', 'successfully']),
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
    | Get all branch balance transfer list
    *--------------------------*/
    public function bdtaskt1m8c8_11_branchTransferBal()
    {
        $postData = $this->request->getVar();
        $data     = $this->bdtaskt1m8c8_01_btModel->bdtaskt1m8_05_getBranchTransfer($postData);
        echo json_encode($data); 
    }

    /*--------------------------
    | Get branch trans by id
    *--------------------------*/
    public function bdtaskt1m8c8_12_branchTransById($transId)
    {
        $data = $this->bdtaskt1m8c8_01_btModel->bdtaskt1m8_06_getBranchTransById($transId);
        echo json_encode($data); 
    }

    /*--------------------------
    | Approved branch transaction
    *--------------------------*/
    public function bdtaskt1m8c8_13_approvedBranchTrans()
    { 
        $transId     = $this->request->getVar('transId');
        $from_patient= $this->request->getVar('from_id');
        $to_patient  = $this->request->getVar('to_id');
        $from_head   = $this->request->getVar('from_head');
        $to_head     = $this->request->getVar('to_head');
        $from_name   = $this->request->getVar('from_name');
        $to_name     = $this->request->getVar('to_name');
        $from_bln    = $this->request->getVar('from_current_blnc');
        $to_bln      = $this->request->getVar('to_current_blnc');
        $trans_amount= $this->request->getVar('trans_amount');
        $approved    = $this->request->getVar('approval');
        //print_r($approved);die();

        $data = array(
            'updated_by'  => session('id'),
            'updated_date'=> date('Y-m-d H:i:s'),
            'isApproved'  => $approved
        );

        $result = $this->bdtaskt1m8c8_02_CmModel->bdtaskt1m1_17_Update_getAffectedRows('patient_balance_transfer',$data, array('id'=>$transId));
        $MesTitle = get_phrases(['patient', 'balance']);
        if($result){
            if($approved==1){
                // Store log data
                $this->bdtaskt1m8c8_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['balance', 'transfer', 'approval']), get_phrases(['updated']), $transId, 'patient_balance_transfer', 2);
                // from patient credit 
                $fromPntCredit = array(
                    'VNo'         => 'PBT-'.$result,
                    'Vtype'       => 'Patient Balance Transfer',
                    'VDate'       => date('Y-m-d'),
                    'COAID'       => $from_head,
                    'Narration'   => 'Balance Transfer '.$from_name.' to '.$to_name,
                    'Debit'       => $trans_amount,
                    'Credit'      => 0,
                    'PatientID'   => $to_patient,
                    'BranchID'     => session('branchId'),
                    'IsPosted'    => 1,
                    'CreateBy'    => session('id'),
                    'CreateDate'  => date('Y-m-d H:i:s'),
                    'IsAppove'    => $approved
                ); 

                // to patient Debit 
                $toPntDebit = array(
                    'VNo'         => 'PBT-'.$result,
                    'Vtype'       => 'Patient Balance Transfer',
                    'VDate'       => date('Y-m-d'),
                    'COAID'       => $to_head,
                    'Narration'   => 'Balance Transfer '.$from_name.' to '.$to_name,
                    'Debit'       => 0,
                    'Credit'      => $trans_amount,
                    'PatientID'   => $from_patient,
                    'BranchID'     => session('branchId'),
                    'IsPosted'    => 1,
                    'CreateBy'    => session('id'),
                    'CreateDate'  => date('Y-m-d H:i:s'),
                    'IsAppove'    => $approved
                ); 

                //patient balance
                $fromBalance = floatval($from_bln) - floatval($trans_amount);
                $toBalance   = floatval($to_bln) + floatval($trans_amount);
                // from patient
                $this->bdtaskt1m8c8_02_CmModel->bdtaskt1m1_02_Update('patient', array('balance'=>$fromBalance), array('patient_id' => $from_patient));
                // to patient
                $this->bdtaskt1m8c8_02_CmModel->bdtaskt1m1_02_Update('patient', array('balance'=>$toBalance), array('patient_id' => $to_patient));

                // transaction total patient credit
                $this->bdtaskt1m8c8_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$fromPntCredit);
                $this->bdtaskt1m8c8_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$toPntDebit);
                $response = array(
                    'success'  => true,
                    'message'  => get_phrases(['approved', 'successfully']),
                    'title'    => $MesTitle
                );
            }else{
                $response = array(
                    'success'  => true,
                    'message'  => get_phrases(['rejected', 'successfully']),
                    'title'    => $MesTitle
                );
            }
        }else{
            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['please_try_again']),
                'title'    => $MesTitle
            );
        }
        echo json_encode($response); 
    }

}
