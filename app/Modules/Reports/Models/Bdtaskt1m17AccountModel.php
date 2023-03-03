<?php namespace App\Modules\Reports\Models;
use CodeIgniter\Model;
class Bdtaskt1m17AccountModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        if(session('defaultLang')=='english'){
            $this->langColumn = 'nameE';
        }else{
            $this->langColumn = 'nameA';
        }
    }

    /*--------------------------
    | Get statement data
    *--------------------------*/
    public function bdtaskt1m17_01_getStatementData($branch_id, $userId, $from, $to){
        $builder = $this->db->table('acc_transaction trans')
                ->select("trans.*, vt.nameE as type")
                ->join("voucher_type_list vt", "vt.type=trans.Vtype", "left")
                ->where("trans.COAID", $userId)
                ->where("trans.BranchID", $branch_id)
                ->where("trans.IsAppove", 1)
                ->where("trans.VDate >=", $from)
                ->where("trans.VDate <=", $to)
                ->orderBy("trans.VDate", "ASC")
                ->get()->getResult(); 
        return $builder;
    }

    /*--------------------------
    | search patient data
    *--------------------------*/
    public function bdtaskt1m17_02_getPatient($text, $langColumn)
    { 
      if(!empty($text)){
        return $this->db->table('patient_file file')
                        ->select("patient.acc_head as id, CONCAT_WS(' ', file.file_no, '-', patient.$langColumn) as text")
                        ->join('patient', 'patient.patient_id=file.patient_id', 'left')
                        ->like('file.file_no', $text)
                        ->orLike('patient.nameE', $text)
                        ->orLike('patient.nameA', $text)
                        ->orLike('patient.mobile', $text)
                        ->get()->getResult();
      }else{
        return false;
      }
    }

    /*--------------------------
    | search supplier data
    *--------------------------*/
    public function bdtaskt1m17_03_getSupplier($text, $langColumn)
    { 
      if(!empty($text)){
        //return $this->db->table('wh_supplier_information')->select("acc_head as id, CONCAT_WS(' ', acc_head, '-',code_no, $langColumn) as text")->like('nameE', $text)->orLike('nameA', $text)->orLike('code_no', $text)->orLike('acc_head', $text)->get()->getResult();
        return $this->db->table('acc_coa')
                            ->select("HeadCode as id, CONCAT_WS(' ', HeadCode, '-', HeadName) as text")
                            //->where('IsTransaction', 1)  
                            ->where('IsActive', 1)  
                            ->havingLike('HeadCode', '2211', 'after')  
                            ->groupStart()
                                ->like('HeadCode', $text)
                                ->orLike('HeadName', $text)
                            ->groupEnd()
                            ->orderBy('HeadName', 'ASC')
                            ->get()
                            ->getResult();
      }else{
        return false;
      }
    }

    /*--------------------------
    | Get patient data
    *--------------------------*/
    public function bdtaskt1m17_04_getPatientInfoById($pid)
    { 
        return $this->db->table('patient')
                        ->select("patient.acc_head, patient.nameE, patient.nameA, acc_coa.HeadName  as account_name")
                        ->join('acc_coa', 'acc_coa.HeadCode=patient.acc_head', 'left')
                        ->like('patient.acc_head', $pid)
                        ->get()->getRow();
    }

    /*--------------------------
    | Get supplier data
    *--------------------------*/
    public function bdtaskt1m17_05_getSupplierInfoById($sid)
    { 
        return $this->db->table('wh_supplier_information sup')
                        ->select("sup.acc_head, sup.nameE, sup.nameA, acc_coa.HeadName  as account_name")
                        ->join('acc_coa', 'acc_coa.HeadCode=sup.acc_head', 'left')
                        ->like('sup.acc_head', $sid)
                        ->get()->getRow();
    }

    /*--------------------------
    | Get all account headCode 
    *--------------------------*/
    public function bdtaskt1m17_06_getAccountList($text){
        if(!empty($text)){
            $result = $this->db->table('acc_coa')
                            ->select("HeadCode as id, CONCAT_WS(' ', HeadCode, '-', HeadName) as text")
                            ->where('IsTransaction', 1)  
                            ->where('IsActive', 1)  
                            ->notHavingLike('HeadCode', '122', 'after')  
                            ->notHavingLike('HeadCode', '2211', 'after')  
                            ->groupStart()
                                ->like('HeadCode', $text)
                                ->orLike('HeadName', $text)
                            ->groupEnd()
                            ->orderBy('HeadName', 'ASC')
                            ->get()
                            ->getResult();
            //echo get_last_query();exit();
            return $result;
        }else{
            return false;
        }
    }

    /*--------------------------
    | Get receive data
    *--------------------------*/
    public function bdtaskt1m17_07_getReceiveInfo($voucher_no)
    { 
        return $this->db->table('wh_receive')
                        ->select("purchase_id")
                        //->join('acc_coa', 'acc_coa.HeadCode=sup.acc_head', 'left')
                        ->where('voucher_no', $voucher_no)
                        ->get()
                        ->getRow();
    }
    /*--------------------------
    | Get return data
    *--------------------------*/
    public function bdtaskt1m17_08_getReturnInfo($voucher_no)
    { 
        return $this->db->table('wh_return')
                        ->select("purchase_id")
                        //->join('acc_coa', 'acc_coa.HeadCode=sup.acc_head', 'left')
                        ->where('voucher_no', $voucher_no)
                        ->get()
                        ->getRow();
    }
    /*--------------------------
    | Get payment data
    *--------------------------*/
    public function bdtaskt1m17_09_getPaymentInfo($voucher_no)
    { 
        return $this->db->table('wh_supplier_payment')
                        ->select("wh_supplier_payment.id, wh_receive.purchase_id")
                        ->join('wh_receive', 'wh_receive.id=wh_supplier_payment.receive_id', 'left')
                        ->where('wh_supplier_payment.voucher_no', $voucher_no)
                        ->get()
                        ->getRow();
    }

    /*--------------------------
    | Get journal data
    *--------------------------*/
    public function bdtaskt1m17_10_getJournalData($userId, $from=null, $to=null){
        $builder = $this->db->table('acc_transaction trans');
        $builder->select("trans.VNo, trans.VDate, trans.Narration, SUM(trans.Debit) as Debit, SUM(trans.Credit) as Credit, vt.nameE as type");
        $builder->join("voucher_type_list vt", "vt.type=trans.Vtype", "left");
        $builder->where("trans.CreateBy", $userId);
        $builder->where("trans.Vtype", 'JV');
        $builder->where("trans.IsAppove", 1);
        $builder->where("trans.VDate >=", $from);
        $builder->where("trans.VDate <=", $to);
        $builder->groupBy("trans.VNo");
        $builder->orderBy("trans.VDate", "DESC");
        return $builder->get()->getResult(); 
    }
    /*--------------------------
    | Get payment data
    *--------------------------*/
    public function bdtaskt1m17_11_getConsumptionInfo($voucher_no)
    { 
        return $this->db->table('wh_order')
                        ->select("wh_order.id")
                        //->join('wh_receive', 'wh_receive.id=wh_supplier_payment.receive_id', 'left')
                        ->where('wh_order.voucher_no', $voucher_no)
                        ->get()
                        ->getRow();
    }

    /*--------------------------
    | Get all account headCode 
    *--------------------------*/
    public function bdtaskt1m17_12_getSearchAccounts($text){
        if(!empty($text)){
            $result = $this->db->table('acc_coa')
                            ->select("HeadCode as id, CONCAT_WS(' ', HeadCode, '-', HeadName) as text")
                            ->where('IsActive', 1)  
                            ->groupStart()
                                ->like('HeadCode', $text)
                                ->orLike('HeadName', $text)
                            ->groupEnd()
                            ->limit(8, 1)
                            ->orderBy('HeadName', 'ASC')
                            ->get()
                            ->getResult();
            //echo get_last_query();exit();
            return $result;
        }else{
            return false;
        }
    }

}
?>