<?php namespace App\Modules\Account\Controllers;
use CodeIgniter\Controller;
use App\Modules\Account\Models\Bdtaskt1m8VoucherModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Numberconverter;
class Bdtaskt1m8c9Vouchers extends BaseController
{
    private $bdtaskt1m8c9_01_accModel;
    private $bdtaskt1m8c9_02_CmModel;
    /**
     * Constructor. 
     */
    public function __construct()
    {
        $this->bdtaskt1m8c9_01_vchrModel = new Bdtaskt1m8VoucherModel();
        $this->bdtaskt1m8c9_02_CmModel = new Bdtaskt1m1CommonModel();
       
    }

    /*--------------------------
    | Debit voucher form
    *--------------------------*/
    public function bdtaskt1m8c9_01_DebitVoucher()
    {
        $data['title']      = get_phrases(['debit','voucher']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['setting']    = $this->bdtaskt1m8c9_01_vchrModel->setting_info();
        $data['isDTables']  = true;
        $data['module']     = "Account";
        $data['page']       = "voucher/debit";
        $data['accList'] = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_02_getAccHead();
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Contra voucher form
    *--------------------------*/
    public function bdtaskt1m8c9_01_ContraVoucher()
    {
        $data['title']      = get_phrases(['contra','voucher']);
        $data['setting']    = $this->bdtaskt1m8c9_01_vchrModel->setting_info();
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['isDTables']  = true;
        $data['module']     = "Account";
        $data['page']       = "voucher/contra";
        $data['accList'] = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_02_getAccHead();
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Get type wise voucher list 
    *--------------------------*/
    public function bdtaskt1m8c9_02_getVoucherList($type)
    {
        $postData = $this->request->getVar();
        $types = explode('-', $type);
        $data = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_01_getAllVouchers($postData, $types);
        echo json_encode($data); 
    }


    /*--------------------------
    | Save Debit voucher
    *--------------------------*/
    public function bdtaskt1m8c9_03_saveDebitVoucher()
    { 
        $credit_head     = $this->request->getVar('credit_head', FILTER_SANITIZE_STRING);
        $voucher_date    = $this->request->getVar('voucher_date', FILTER_SANITIZE_STRING);
        $voucher_no      = $this->request->getVar('voucher_no', FILTER_SANITIZE_STRING);
        $account_name    = $this->request->getVar('account_name', FILTER_SANITIZE_STRING);
        $head_code       = $this->request->getVar('head_code', FILTER_SANITIZE_STRING);
        $amount          = $this->request->getVar('amount', FILTER_SANITIZE_STRING);
        $sub_type        = $this->request->getVar('sub_type', FILTER_SANITIZE_STRING);
        $sub_code        = $this->request->getVar('sub_code', FILTER_SANITIZE_STRING);
        $ledger_comments = $this->request->getVar('ledger_comments', FILTER_SANITIZE_STRING);
        $remarks         = $this->request->getVar('remarks', FILTER_SANITIZE_SPECIAL_CHARS);
        $fisyearid       = $this->bdtaskt1m8c9_01_vchrModel->getActiveFiscalyear();
        $vno             = 'DV';
        $getVouchern = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_07_getMaxvoucherno($vno);
        $total = 0;
        if(!empty($account_name) && is_array($account_name)){
            $list = array();
            for ($i=0; $i < sizeof($account_name); $i++) { 
                $total += $amount[$i];
                $subcodecount = ($sub_code?sizeof($sub_code):0);
                $subcode = ($i < $subcodecount?$sub_code[$i]:'');
                $list[$i] = array(
                    'fyear'           => $fisyearid,
                    'Vtype'           => 'DV',
                    'VNo'             => $getVouchern,
                    'VDate'           => $voucher_date,
                    'COAID'           => $head_code[$i],
                    'Debit'           => $amount[$i],
                    'Credit'          => 0,
                    'RevCodde'        => $credit_head,
                    'subType'         => ($sub_type[$i]?$sub_type[$i]:1),
                    'subCode'         => $subcode,
                    'ledgerComment'   => $ledger_comments[$i],
                    'Narration'       => $remarks,
                    'CreateBy'        => session('id'),
                    'CreateDate'      => date('Y-m-d H:i:s'),
                );
            }
            $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_01_Insert_Batch('acc_vaucher',$list);
        }
     
        $result = 'success';
        $MesTitle = get_phrases(['debit', 'voucher']);
        if(!empty($result)){
            // Store log data
            $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_22_addActivityLog($MesTitle, get_phrases(['created']), $result, 'acc_vaucher', 1);
            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['created', 'successfully']),
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
    | Update Debit voucher
    *--------------------------*/
    public function bdtaskt1m8c9_03_updateDebitVoucher()
    { 
       
        $credit_head     = $this->request->getVar('credit_head', FILTER_SANITIZE_STRING);
        $voucher_date    = $this->request->getVar('voucher_date', FILTER_SANITIZE_STRING);
        $voucher_no      = $this->request->getVar('voucher_no', FILTER_SANITIZE_STRING);
        $account_name    = $this->request->getVar('account_name', FILTER_SANITIZE_STRING);
        $head_code       = $this->request->getVar('head_code', FILTER_SANITIZE_STRING);
        $amount          = $this->request->getVar('amount', FILTER_SANITIZE_STRING);
        $sub_type        = $this->request->getVar('sub_type', FILTER_SANITIZE_STRING);
        $sub_code        = $this->request->getVar('sub_code', FILTER_SANITIZE_STRING);
        $ledger_comments = $this->request->getVar('ledger_comments', FILTER_SANITIZE_STRING);
        $remarks         = $this->request->getVar('remarks', FILTER_SANITIZE_SPECIAL_CHARS);
        $fisyearid       = $this->bdtaskt1m8c9_01_vchrModel->getActiveFiscalyear();
        $vno             = 'DV';
        $createdby       = $this->request->getVar('createdby', FILTER_SANITIZE_STRING);
        $created_date    = $this->request->getVar('createddate', FILTER_SANITIZE_STRING);
        $total = 0;
        $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_06_Deleted('acc_vaucher', array('VNo' => $voucher_no));
        if(!empty($account_name) && is_array($account_name)){
            $list = array();
            for ($i=0; $i < sizeof($account_name); $i++) { 
                $total += $amount[$i];
                $subcodecount = ($sub_code?sizeof($sub_code):0);
                $subcode = ($i <= $subcodecount?$sub_code[$i]:'');
                $list[$i] = array(
                    'fyear'           => $fisyearid,
                    'Vtype'           => $vno,
                    'VNo'             => $voucher_no,
                    'VDate'           => $voucher_date,
                    'COAID'           => $head_code[$i],
                    'Debit'           => $amount[$i],
                    'Credit'          => 0,
                    'RevCodde'        => $credit_head,
                    'subType'         => ($sub_type[$i]?$sub_type[$i]:1),
                    'subCode'         => $subcode,
                    'ledgerComment'   => $ledger_comments[$i],
                    'Narration'       => $remarks,
                    'CreateBy'        => $createdby,
                    'UpdateBy'        => session('id'),
                    'CreateDate'      => $created_date,
                    'UpdateDate'      => date('Y-m-d H:i:s'),
                );
            }
            $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_01_Insert_Batch('acc_vaucher',$list);
        }
     
        $result = 'success';
        $MesTitle = get_phrases(['debit', 'voucher']);
        if(!empty($result)){
            // Store log data
            $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_22_addActivityLog($MesTitle, get_phrases(['updated']), $result, 'acc_vaucher', 1);
            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['updated', 'successfully']),
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
    | Save Contra voucher
    *--------------------------*/
    public function bdtaskt1m8c9_03_saveContraVoucher()
    { 
        $credit_head     = $this->request->getVar('reverse_head', FILTER_SANITIZE_STRING);
        $voucher_date    = $this->request->getVar('voucher_date', FILTER_SANITIZE_STRING);
        $account_name    = $this->request->getVar('account_name', FILTER_SANITIZE_STRING);
        $head_code       = $this->request->getVar('head_code', FILTER_SANITIZE_STRING);
        $debit           = $this->request->getVar('debit', FILTER_SANITIZE_STRING);
        $credit          = $this->request->getVar('credit', FILTER_SANITIZE_STRING);
        $sub_type        = $this->request->getVar('sub_type', FILTER_SANITIZE_STRING);
        $ledger_comments = $this->request->getVar('ledger_comments', FILTER_SANITIZE_STRING);
        $remarks         = $this->request->getVar('remarks', FILTER_SANITIZE_SPECIAL_CHARS);
        $vno = 'TV';
        $getVouchern     = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_07_getMaxvoucherno($vno);
        $fisyearid       = $this->bdtaskt1m8c9_01_vchrModel->getActiveFiscalyear();
        $total = 0;
        if(!empty($account_name)){


                $list = array(
                    'fyear'           => $fisyearid,
                    'Vtype'           => 'TV',
                    'VNo'             => $getVouchern,
                    'VDate'           => $voucher_date,
                    'COAID'           => $head_code,
                    'Debit'           => ($debit?$debit:0),
                    'Credit'          => ($credit?$credit:0),
                    'RevCodde'        => $credit_head,
                    'subType'         => $sub_type,
                    'ledgerComment'   => $ledger_comments,
                    'Narration'       => $remarks,
                    'CreateBy'        => session('id'),
                    'CreateDate'      => date('Y-m-d H:i:s'),
                );
            
            
        }
        $result  = $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_01_Insert('acc_vaucher',$list);
        $MesTitle = get_phrases(['contra', 'voucher']);
        if(!empty($result)){
            // Store log data
            $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_22_addActivityLog($MesTitle, get_phrases(['created']), $result, 'acc_vaucher', 1);
            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['created', 'successfully']),
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
    | Update contra voucher
    *--------------------------*/
    public function bdtaskt1m8c9_04_updateContraVoucher()
    { 
        $credit_head     = $this->request->getVar('reverse_head', FILTER_SANITIZE_STRING);
        $voucher_date    = $this->request->getVar('voucher_date', FILTER_SANITIZE_STRING);
        $account_name    = $this->request->getVar('account_name', FILTER_SANITIZE_STRING);
        $head_code       = $this->request->getVar('head_code', FILTER_SANITIZE_STRING);
        $debit           = $this->request->getVar('debit', FILTER_SANITIZE_STRING);
        $credit          = $this->request->getVar('credit', FILTER_SANITIZE_STRING);
        $sub_type        = $this->request->getVar('sub_type', FILTER_SANITIZE_STRING);
        $ledger_comments = $this->request->getVar('ledger_comments', FILTER_SANITIZE_STRING);
        $remarks         = $this->request->getVar('remarks', FILTER_SANITIZE_SPECIAL_CHARS);
        $vno = 'TV';
        $getVouchern     = $this->request->getVar('voucher_no', FILTER_SANITIZE_STRING);;

        $total = 0;
        if(!empty($account_name)){


                $list = array(
                    'VNo'             => $getVouchern,
                    'VDate'           => $voucher_date,
                    'COAID'           => $head_code,
                    'Debit'           => ($debit?$debit:0),
                    'Credit'          => ($credit?$credit:0),
                    'RevCodde'        => $credit_head,
                    'subType'         => $sub_type,
                    'ledgerComment'   => $ledger_comments,
                    'Narration'       => $remarks,
                    'UpdateBy'        => session('id'),
                    'UpdateDate'      => date('Y-m-d H:i:s'),
                );
            
            
        }
        $result  = $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_02_Update('acc_vaucher', $list, array('VNo' => $getVouchern));
       
        $MesTitle = get_phrases(['contra', 'voucher']);
        if(!empty($result)){
            // Store log data
            $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_22_addActivityLog($MesTitle, get_phrases(['created']), $result, 'acc_vaucher', 1);
            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['updated', 'successfully']),
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
    | Voucher approved
    *--------------------------*/
    public function bdtaskt1m8c9_04_approvedVoucher($VNo)
    { 
        $status = $this->request->getVar('status');
        $data = array(
            'UpdateBy'  => session('id'),
            'UpdateDate'=> date('Y-m-d H:i:s'),
            'IsAppove'  => $status
        );

        //$data = $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_02_Update('acc_transaction', $data, array('VNo' => $VNo));

        $data = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_09_approveVoucher($VNo);
        $MesTitle = get_phrases(['voucher', 'record']);
        if($data){
           
                // Store log data
                $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['voucher', 'approved']), get_phrases(['updated']), $VNo, 'acc_transaction', 2);
                $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['approved', 'successfully']),
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
    | Voucher reverse
    *--------------------------*/
    public function bdtaskt1m8c9_04_reverseVoucher($VNo)
    { 
        $status = $this->request->getVar('status');
        $data = array(
            'UpdateBy'  => session('id'),
            'UpdateDate'=> date('Y-m-d H:i:s'),
            'IsAppove'  => 0
        );
        $data = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_09_reverseVoucher($VNo);
        $MesTitle = get_phrases(['voucher', 'record']);
        if($data){
   
                // Store log data
                $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['voucher', 'reverse']), get_phrases(['updated']), $VNo, 'acc_vaucher', 2);
                $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['reverse', 'successfully']),
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
    | Voucher delete
    *--------------------------*/
    public function bdtaskt1m8c9_05_deleteVoucher($VNo)
    { 

        $data = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_10_deleteVoucher($VNo);
        $MesTitle = get_phrases(['voucher', 'record']);
        if($data){
   
                // Store log data
                $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['voucher', 'delete']), get_phrases(['updated']), $VNo, 'acc_vaucher', 2);
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
    | Voucher details by ID
    *--------------------------*/
    public function bdtaskt1m8c9_05_voucherDetails($vid)
    {
        $vtype = explode('-',$vid);
        $data['results']      = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_03_getVoucherDetails($vid);
        $data['settings_info']= $this->bdtaskt1m8c9_01_vchrModel->setting_info();
        $data['title']  = 'Voucher Print';
        if($vtype[0] == 'DV'){
        $details = view('App\Modules\Account\Views\voucher\view_details', $data);
        }
        if($vtype[0] == 'CV'){
            $details = view('App\Modules\Account\Views\voucher\cv_view_details', $data);
        }
        if($vtype[0] == 'JV'){
            $details = view('App\Modules\Account\Views\voucher\jv_view_details', $data);
        }
        if($vtype[0] == 'TV'){
            $details = view('App\Modules\Account\Views\voucher\tv_view_details', $data);
        }
        echo json_encode(array('data'=>$details));
    }

    /*--------------------------
    | Credit voucher form
    *--------------------------*/
    public function bdtaskt1m8c9_06_CreditVoucher()
    {
        $data['title']      = get_phrases(['credit','voucher']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['setting']    = $this->bdtaskt1m8c9_01_vchrModel->setting_info();
        $data['isDTables']  = true;
        $data['module']     = "Account";
        $data['page']       = "voucher/credit";
        $data['accList'] = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_02_getAccHead();
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Save Credit voucher
    *--------------------------*/
    public function bdtaskt1m8c9_07_saveCreditVoucher()
    { 
        $credit_head     = $this->request->getVar('credit_head', FILTER_SANITIZE_STRING);
        $voucher_date    = $this->request->getVar('voucher_date', FILTER_SANITIZE_STRING);
        $voucher_no      = $this->request->getVar('voucher_no', FILTER_SANITIZE_STRING);
        $account_name    = $this->request->getVar('account_name', FILTER_SANITIZE_STRING);
        $head_code       = $this->request->getVar('head_code', FILTER_SANITIZE_STRING);
        $sub_type        = $this->request->getVar('sub_type', FILTER_SANITIZE_STRING);
        $sub_code        = $this->request->getVar('sub_code', FILTER_SANITIZE_STRING);
        $ledger_comments = $this->request->getVar('ledger_comments', FILTER_SANITIZE_STRING);
        $amount          = $this->request->getVar('amount', FILTER_SANITIZE_STRING);
        $remarks         = $this->request->getVar('remarks', FILTER_SANITIZE_SPECIAL_CHARS);
        $fisyearid       = $this->bdtaskt1m8c9_01_vchrModel->getActiveFiscalyear();
        $vno = 'CV';
        $getVouchern = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_07_getMaxvoucherno($vno);
        $total = 0;
        if(!empty($account_name) && is_array($account_name)){
            $list = array();
            for ($i=0; $i < sizeof($account_name); $i++) { 
                $total += $amount[$i];
                $subcodecount = ($sub_code?sizeof($sub_code):0);
                $subcode = ($i < $subcodecount?$sub_code[$i]:'');
                $list[$i] = array(
                    'fyear'           => $fisyearid ,
                    'Vtype'           => 'CV',
                    'VNo'             => $getVouchern,
                    'VDate'           => $voucher_date,
                    'COAID'           => $head_code[$i],
                    'Debit'           => 0,
                    'Credit'          => $amount[$i],
                    'RevCodde'        => $credit_head,
                    'subType'         => ($sub_type[$i]?$sub_type[$i]:1),
                    'subCode'         => $subcode,
                    'ledgerComment'   => $ledger_comments[$i],
                    'Narration'       => $remarks,
                    'CreateBy'        => session('id'),
                    'CreateDate'      => date('Y-m-d H:i:s'),
                );
            }
            $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_01_Insert_Batch('acc_vaucher',$list);
        }

        
        // transaction Debit
        $result = 'success';
        $MesTitle = get_phrases(['credit', 'voucher']);
        if(!empty($result)){
            // Store log data
            $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_22_addActivityLog($MesTitle, get_phrases(['created']), $result, 'acc_vaucher', 1);
            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['created', 'successfully']),
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
    | Update Credit voucher
    *--------------------------*/

    public function bdtaskt1m8c9_09_updateCreditVoucher()
    {
        $credit_head     = $this->request->getVar('credit_head', FILTER_SANITIZE_STRING);
        $voucher_date    = $this->request->getVar('voucher_date', FILTER_SANITIZE_STRING);
        $voucher_no      = $this->request->getVar('voucher_no', FILTER_SANITIZE_STRING);
        $account_name    = $this->request->getVar('account_name', FILTER_SANITIZE_STRING);
        $head_code       = $this->request->getVar('head_code', FILTER_SANITIZE_STRING);
        $sub_type        = $this->request->getVar('sub_type', FILTER_SANITIZE_STRING);
        $sub_code        = $this->request->getVar('sub_code', FILTER_SANITIZE_STRING);
        $ledger_comments = $this->request->getVar('ledger_comments', FILTER_SANITIZE_STRING);
        $amount          = $this->request->getVar('amount', FILTER_SANITIZE_STRING);
        $remarks         = $this->request->getVar('remarks', FILTER_SANITIZE_SPECIAL_CHARS);
        $fisyearid       = $this->bdtaskt1m8c9_01_vchrModel->getActiveFiscalyear();
        $vno             = 'CV';
        $createdby       = $this->request->getVar('createdby', FILTER_SANITIZE_STRING);
        $created_date    = $this->request->getVar('createddate', FILTER_SANITIZE_STRING);
        $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_06_Deleted('acc_vaucher', array('VNo' => $voucher_no));
        $total = 0;
        if(!empty($account_name) && is_array($account_name)){
            $list = array();
            for ($i=0; $i < sizeof($account_name); $i++) { 
                $total += $amount[$i];
                $subcodecount = ($sub_code?sizeof($sub_code):0);
                $subcode = ($i < $subcodecount?$sub_code[$i]:'');
                $list[$i] = array(
                    'fyear'           => $fisyearid ,
                    'Vtype'           => $vno,
                    'VNo'             => $voucher_no,
                    'VDate'           => $voucher_date,
                    'COAID'           => $head_code[$i],
                    'Debit'           => 0,
                    'Credit'          => $amount[$i],
                    'RevCodde'        => $credit_head,
                    'subType'         => ($sub_type[$i]?$sub_type[$i]:1),
                    'subCode'         => $subcode,
                    'ledgerComment'   => $ledger_comments[$i],
                    'Narration'       => $remarks,
                    'CreateBy'        => $createdby,
                    'UpdateBy'        => session('id'),
                    'CreateDate'      => $created_date,
                    'UpdateDate'      => date('Y-m-d H:i:s'),
                );
            }
            $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_01_Insert_Batch('acc_vaucher',$list);
        }

        
        // transaction Debit
        $result = 'success';
        $MesTitle = get_phrases(['credit', 'voucher']);
        if(!empty($result)){
            // Store log data
            $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_22_addActivityLog($MesTitle, get_phrases(['updated']), $result, 'acc_vaucher', 1);
            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['updated', 'successfully']),
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
    | Journal voucher form
    *--------------------------*/
    public function bdtaskt1m8c9_08_JournalVoucher()
    {
        $data['title']      = get_phrases(['journal','voucher']);
        $data['setting']    = $this->bdtaskt1m8c9_01_vchrModel->setting_info();
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['isDTables']  = true;
        $data['module']     = "Account";
        $data['page']       = "voucher/journal";
        $data['accList'] = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_02_getAccHead();
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Save Journal voucher
    *--------------------------*/
    public function bdtaskt1m8c9_09_saveJournalVoucher()
    { 
        $action           = $this->request->getVar('action', FILTER_SANITIZE_STRING);
        $voucher_date     = $this->request->getVar('voucher_date', FILTER_SANITIZE_STRING);
        $account_name     = $this->request->getVar('account_name', FILTER_SANITIZE_STRING);
        $head_code        = $this->request->getVar('head_code', FILTER_SANITIZE_STRING);
        $reversehead_code = $this->request->getVar('reversehead_code', FILTER_SANITIZE_STRING);
        $debit            = $this->request->getVar('debit', FILTER_SANITIZE_STRING);
        $credit           = $this->request->getVar('credit', FILTER_SANITIZE_STRING);
        $sub_type         = $this->request->getVar('sub_type', FILTER_SANITIZE_STRING);
        $sub_code         = $this->request->getVar('sub_code', FILTER_SANITIZE_STRING);
        $ledger_comments  = $this->request->getVar('ledger_comments', FILTER_SANITIZE_STRING);
        $remarks          = $this->request->getVar('remarks', FILTER_SANITIZE_SPECIAL_CHARS);
        $fisyearid        = $this->bdtaskt1m8c9_01_vchrModel->getActiveFiscalyear();
        $MesTitle         = get_phrases(['journal', 'voucher']);
        if(!empty($account_name) && is_array($account_name)){
        
            if($action=='add'){
                $vno = 'JV';
               $getVouchern = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_07_getMaxvoucherno($vno);
                $aFile = $this->request->getFile('attach_file');
                if(!empty($aFile)){
                    $attachFile = $this->base_03_fileUpload->doUpload('/assets/attached/jv', $aFile);
                }else{
                    $attachFile = null;
                }

                     $list = array();
                    for ($i=0; $i < sizeof($account_name); $i++) { 
                        $subcodecount = ($sub_code?sizeof($sub_code):0);
                        $subcode      = ($i < $subcodecount?$sub_code[$i]:'');
                        $list[$i] = array(
                            'fyear'           => $fisyearid,
                            'Vtype'           => 'JV',
                            'VNo'             => $getVouchern,
                            'VDate'           => $voucher_date,
                            'COAID'           => $head_code[$i],
                            'Debit'           => $debit[$i],
                            'Credit'          => $credit[$i],
                            'RevCodde'        => $reversehead_code[$i],
                            'subType'         => ($sub_type[$i]?$sub_type[$i]:1),
                            'subCode'         => $subcode,
                            'ledgerComment'   => $ledger_comments[$i],
                            'Narration'       => $remarks,
                            'attachment'      => ($attachFile?$attachFile:''),
                            'CreateBy'        => session('id'),
                            'CreateDate'      => date('Y-m-d H:i:s'),
                        );
                    }
                    $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_01_Insert_Batch('acc_vaucher',$list);
                    // Store log data
                    $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_22_addActivityLog($MesTitle, get_phrases(['created']), 'created', 'journal_vouchers', 1);

                    $response = array(
                        'success'  => true,
                        'message'  => get_phrases(['created', 'successfully']),
                        'title'    => $MesTitle
                    );
                }else{
                    $response = array(
                        'success'  => false,
                        'message'  => get_phrases(['please_try_again']),
                        'title'    => $MesTitle
                    );
                }
           
            
        }else{
            $response = array(
                'success'  => false,
                'message'  => get_phrases(['please', 'select', 'account', 'name']),
                'title'    => $MesTitle
            );
        }
        echo json_encode($response);
    }


    /*--------------------------
    | Update Journal voucher
    *--------------------------*/
    public function bdtaskt1m8c9_11_updateJournalVoucher()
    { 
        $action           = $this->request->getVar('action', FILTER_SANITIZE_STRING);
        $voucher_date     = $this->request->getVar('voucher_date', FILTER_SANITIZE_STRING);
        $account_name     = $this->request->getVar('account_name', FILTER_SANITIZE_STRING);
        $head_code        = $this->request->getVar('head_code', FILTER_SANITIZE_STRING);
        $reversehead_code = $this->request->getVar('reversehead_code', FILTER_SANITIZE_STRING);
        $debit            = $this->request->getVar('debit', FILTER_SANITIZE_STRING);
        $credit           = $this->request->getVar('credit', FILTER_SANITIZE_STRING);
        $sub_type         = $this->request->getVar('sub_type', FILTER_SANITIZE_STRING);
        $sub_code         = $this->request->getVar('sub_code', FILTER_SANITIZE_STRING);
        $ledger_comments  = $this->request->getVar('ledger_comments', FILTER_SANITIZE_STRING);
        $remarks          = $this->request->getVar('remarks', FILTER_SANITIZE_SPECIAL_CHARS);
        $createdby        = $this->request->getVar('createdby', FILTER_SANITIZE_SPECIAL_CHARS);
        $createddate      = $this->request->getVar('createddate', FILTER_SANITIZE_SPECIAL_CHARS);
        $fisyearid        = $this->bdtaskt1m8c9_01_vchrModel->getActiveFiscalyear();
        $MesTitle         = get_phrases(['journal', 'voucher']);
        if(!empty($account_name) && is_array($account_name)){
            $getVouchern = $this->request->getVar('voucher_no', FILTER_SANITIZE_SPECIAL_CHARS);
            $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_06_Deleted('acc_vaucher', array('VNo' => $getVouchern));
                $vno = 'JV';
              
               
                $aFile = $this->request->getFile('attach_file');
                if(!empty($aFile)){
                    $attachFile = $this->base_03_fileUpload->doUpload('/assets/attached/jv', $aFile);
                }else{
                    $attachFile = $this->request->getFile('old_file');
                }

                     $list = array();
                    for ($i=0; $i < sizeof($account_name); $i++) { 
                        $subcodecount = ($sub_code?sizeof($sub_code):0);
                        $subcode      = ($i < $subcodecount?$sub_code[$i]:'');
                        $list[$i] = array(
                            'fyear'           => $fisyearid,
                            'Vtype'           => $vno,
                            'VNo'             => $getVouchern,
                            'VDate'           => $voucher_date,
                            'COAID'           => $head_code[$i],
                            'Debit'           => $debit[$i],
                            'Credit'          => $credit[$i],
                            'RevCodde'        => $reversehead_code[$i],
                            'subType'         => ($sub_type[$i]?$sub_type[$i]:1),
                            'subCode'         => $subcode,
                            'ledgerComment'   => $ledger_comments[$i],
                            'Narration'       => $remarks,
                            'attachment'      => ($attachFile?$attachFile:''),
                            'CreateBy'        => $createdby,
                            'CreateDate'      => $createddate,
                            'UpdateBy'        => session('id'),
                            'UpdateDate'      => date('Y-m-d H:i:s'),
                        );
                    }
                    $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_01_Insert_Batch('acc_vaucher',$list);
                    // Store log data
                    $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_22_addActivityLog($MesTitle, get_phrases(['updated']), 'created', 'journal_vouchers', 1);

                    $response = array(
                        'success'  => true,
                        'message'  => get_phrases(['updated', 'successfully']),
                        'title'    => $MesTitle
                    );
                }else{
                    $response = array(
                        'success'  => false,
                        'message'  => get_phrases(['please_try_again']),
                        'title'    => $MesTitle
                    );
                }
           
            
     
        echo json_encode($response);
    }

    /*--------------------------
    | Get transaction head
    *--------------------------*/
    public function bdtaskt1m8c9_10_searchTransactionAcc()
    {
        $text = $this->request->getVar('term');
        $data = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_04_getTransAccHead($text);
        echo json_encode($data);
    }

    /*--------------------------
    | Get transaction head without cash
    *--------------------------*/
    public function bdtaskt1m8c9_12_searchTransactionAccwithoutcash()
    {
        $text = $this->request->getVar('term');
        $data = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_04_getTransAccHeadwithoutcash($text);
        echo json_encode($data);
    }

    
    /*--------------------------
    | Get transaction head for credit voucher
    *--------------------------*/
    public function bdtaskt1m8c9_11_searchTransactionCreditvoucher()
    {
        $text = $this->request->getVar('term');
        $data = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_04_getTransAccHeadCreditvoucher($text);
        echo json_encode($data);
    }

    public function bdtaskt1m8c9_1Searchsubcode()
    {
        $headcode = $this->request->getVar('headcode');
        $data = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_05_getSubtypes($headcode);
        $result = array('list' => $data['data'],'subtype' => $data['subtype_id']);
        echo json_encode($result);
    }

    /*--------------------------
    | Voucher details by ID
    *--------------------------*/
    public function bdtaskt1m8c9_11_getTransByVoucher($vid)
    {
        $data = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_03_getVoucherDetails($vid);
        echo json_encode($data);
    }

    /*--------------------------
    | Delete Voucher
    *--------------------------*/
    public function bdtaskt1m8c9_12_deleteVoucher($VNo)
    { 

        $data = $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_06_Deleted('acc_transaction', array('VNo' => $VNo));
        $MesTitle = get_phrases(['voucher', 'record']);
        if($data){
            // Store log data
            $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['voucher', 'deleted']), get_phrases(['deleted']), $VNo, 'acc_transaction', 3);
            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['deleted', 'successfully']).'!',
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
    | Get type wise voucher list 
    *--------------------------*/
    public function bdtaskt1m8c9_13_getTypeWiseVoucher($type)
    {
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_06_getTypeWiseVoucher($postData, $type);
        echo json_encode($data); 
    }

    /*--------------------------
    | Voucher details by ID
    *--------------------------*/
    public function bdtaskt1m8c9_14_voucherDetailsById($vid)
    {
        $action = $this->request->getVar('action');
        $data['results']   = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_07_getVoucherDetailsById($vid);
        // echo "<pre";
        // print_r($data['results']);die();
        if($action=='view'){
            $details = view('App\Modules\Account\Views\voucher\details', $data);
            echo json_encode(array('data'=>$details));
        }else{
            echo json_encode($data['results']);
        }
        
    }

    /*--------------------------
    | Delete Voucher
    *--------------------------*/
    public function bdtaskt1m8c9_15_deleteVoucherById($id)
    { 
        $img = $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_03_getRow('journal_vouchers', array('id' => $id));
       
        $data = $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_06_Deleted('journal_vouchers', array('id' => $id));
        $MesTitle = get_phrases(['voucher', 'record']);
        if($data){
            if(!empty($img->attach_file)){
                if(file_exists('.'.$img->attach_file)){
                    unlink('.'.$img->attach_file);
                }
            }
            $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_06_Deleted('journal_voucher_details', array('voucher_id' => $id));
            // Store log data
            $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['voucher', 'deleted']), get_phrases(['deleted']), $id, 'journal_vouchers', 3);
            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['deleted', 'successfully']).'!',
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
    | Voucher approved
    *--------------------------*/
    public function bdtaskt1m8c9_16_approvedVoucherById($id)
    { 
        $status = $this->request->getVar('status');
        $type = $this->request->getVar('type');
    
        $MesTitle = get_phrases(['voucher', 'record']);
        if($status==1){
            $dataInfo = array(
                'approved_by'  => session('id'),
                'approved_date'=> date('Y-m-d H:i:s'),
                'status'       => $status
            );

            $data = $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_02_Update('journal_vouchers', $dataInfo, array('id' => $id));
            $trans = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_07_getVoucherDetailsById($id);
            if(!empty($trans)){
                $list = array();
                $i=0;
                foreach ($trans->details as  $value) {
                    if(substr($value->head_code, 0, 3)==='122'){
                        if($value->debit > 0){
                            $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_25_updatePatientBLDebit($value->debit, array('acc_head'=> $value->head_code));
                        }
                        if($value->credit > 0){
                            $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_26_updatePatientBLCredit($value->credit, array('acc_head'=> $value->head_code));
                        }
                    }
                    $list = array(
                        'VNo'         => $type.'-'.$id,
                        'Vtype'       => $type,
                        'VDate'       => $trans->voucher_date,
                        'COAID'       => $value->head_code,
                        'Narration'   => $trans->remarks,
                        'Debit'       => $value->debit,
                        'Credit'      => $value->credit,
                        'BranchID'    => $trans->branch_id,
                        'IsPosted'    => 1,
                        'CreateBy'    => $trans->created_by,
                        'CreateDate'  => $trans->created_date,
                        'UpdateBy'    => session('id'),
                        'UpdateDate'  => date('Y-m-d H:i:s'),
                        'IsAppove'    => 1
                    );
                    $i++;
                    $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$list);
                }
            }
            // Store log data
            $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['voucher', 'approved']), get_phrases(['updated']), 'JV-'.$id, 'journal_vouchers', 2);
            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['approved', 'successfully']),
                'title'    => $MesTitle
            );
        }else{
            $dataInfo = array(
                'updated_by'   => session('id'),
                'updated_date' => date('Y-m-d H:i:s'),
                'status'       => $status
            );

            $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_02_Update('journal_vouchers', $dataInfo, array('id' => $id));
             // Store log data
            $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['voucher', 'rejected']), get_phrases(['updated']), 'JV-'.$id, 'acc_transaction', 2);
            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['rejected', 'successfully']),
                'title'    => $MesTitle
            );
        }
        echo json_encode($response);
    }

     /*--------------------------
    | Get credit or debit account headCode
    *--------------------------*/
    public function bdtaskt1m8c1_07_getCreditOrDebitAcc()
    {
        $data = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_07_getCreditOrDebitAcc();
        echo json_encode($data);
    }

    

    public function bdtaskt1m8c9_09_editvouchers($vno,$vtype)
    {
        $data['results']        = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_03_getVoucherDetails($vno);
        $data['settings_info']  = $this->bdtaskt1m8c9_01_vchrModel->setting_info();
        $data['dbtcrthead']     = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_07_getCreditOrDebitAcc();
        $data['all_trhead_dbt'] = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_04_getTransAccHead_withoutwrite();
        $data['jvheads']        = $this->bdtaskt1m8c9_01_vchrModel->bdtaskt1m8_05_editgetTransAccHeadwithoutcash();
        if($vtype == 'DV'){
        $details = view('App\Modules\Account\Views\voucher\edit_debitvoucher', $data);
        }
        if($vtype == 'CV'){
            $details = view('App\Modules\Account\Views\voucher\edit_creditvoucher', $data);
        }
        if($vtype == 'JV'){
            $details = view('App\Modules\Account\Views\voucher\edit_journalvoucher', $data);
        }
        if($vtype == 'TV'){
            $details = view('App\Modules\Account\Views\voucher\edit_contravoucher', $data);
        }
        echo json_encode(array('data'=>$details));
    }
 
}
