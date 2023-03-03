<?php namespace App\Modules\Account\Controllers;
use CodeIgniter\Controller;
use App\Modules\Account\Models\Bdtaskt1m8VApprovalModel;
use App\Models\Bdtaskt1m1CommonModel;
class Bdtaskt1m8c7VoucherApproval extends BaseController
{
    private $bdtaskt1m8c7_01_rvModel;
    private $bdtaskt1m8c7_02_CmModel;
    /**
     * Constructor. 
     */
    public function __construct()
    {
        $this->bdtaskt1m8c7_01_vaModel = new Bdtaskt1m8VApprovalModel();
        $this->bdtaskt1m8c7_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Voucher Approval List
    *--------------------------*/
    public function bdtaskt1m8c7_01_VoucherApproval()
    {
        $data['title']      = get_phrases(['pending', 'vouchers']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['isDTables']= true;
        $data['module']     = "Account";
        $data['page']       = "voucher_approval";
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Get all voucher approval list
    *--------------------------*/
    public function bdtaskt1m8c7_02_getVApprovalList()
    {
        $postData = $this->request->getVar();
        $data     = $this->bdtaskt1m8c7_01_vaModel->bdtaskt1m8_01_getAppVoucher($postData);
        echo json_encode($data); 
    }

     /*--------------------------
    | Voucher approved multiple
    *--------------------------*/
    public function bdtaskt1m8c9_04_approvedmultipleVoucher()
    { 
        $status = $this->request->getVar('status');
        $checked_value = $this->request->getVar('checkrow');
        $MesTitle = get_phrases(['voucher', 'record']);
        $vouchers = '';
      // print_r($checked_value);exit;
        if(!empty($checked_value)){
        $data = array(
            'UpdateBy'  => session('id'),
            'UpdateDate'=> date('Y-m-d H:i:s'),
            'IsAppove'  => $status
        );
        for($i = 0;$i < sizeof($checked_value);$i++){
        $VNo = $checked_value[$i];
        //$data = $this->bdtaskt1m8c9_02_CmModel->bdtaskt1m1_02_Update('acc_transaction', $data, array('VNo' => $VNo));

        $data = $this->bdtaskt1m8c7_01_vaModel->bdtaskt1m8_09_approveVoucher($VNo);
        $vouchers .=$VNo; 
   
        
    }

    $this->bdtaskt1m8c7_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['voucher', 'approved']), get_phrases(['updated']), $vouchers, 'acc_transaction', 2);

    $this->session->setFlashdata('message', get_phrases(['successfully','approved']));    
    return redirect()->to($_SERVER['HTTP_REFERER']);
    }else{
        $this->session->setFlashdata('exception', get_phrases(['please','select','atleast one voucher']));    
        return redirect()->to($_SERVER['HTTP_REFERER']);
    }

 

    }

    /*--------------------------
    | Change voucher approval 
    *--------------------------*/
    public function bdtaskt1m8c7_03_changeApproval()
    {
        $transId = $this->request->getVar('voucher_id');
        $status = $this->request->getVar('status');
        $info = array(
            'UpdateBy'   => session('id'),
            'UpdateDate'=> date('Y-m-d H:i:s'),
            'IsAppove'  =>$status
        );
        $data     = $this->bdtaskt1m8c7_02_CmModel->bdtaskt1m1_02_Update('acc_transaction', $info, array('ID'=>$transId));
        if($status==1){
            $success = get_phrases(['approved', 'successfully']);
        }else{
            $success = get_phrases(['rejected', 'successfully']);
        }
        $MesTitle = get_phrases(['voucher', 'approval']);
        if($data){
            // Store log data
            $this->bdtaskt1m8c7_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['account', 'voucher', 'approval']), get_phrases(['updated']), $transId, 'acc_transaction');
            $response = array(
                'success'  =>true,
                'message'  => $success,
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


}
