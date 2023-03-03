<?php namespace App\Modules\Reports\Models;
use CodeIgniter\Model;
class Bdtaskt1m17ManagementModel extends Model
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
    | Get all user income by user Id
    *--------------------------*/
    public function bdtaskt1m17_01_getUserIncomes($userId, $from=null, $to=null){
        $builder = $this->db->table('service_invoice_payment sip');
        $builder->select("si.*,list_data.$this->langColumn as payment_method, sip.amount, CONCAT_WS(' ', emp.short_name, emp.$this->langColumn) as created_by, CONCAT_WS(' ', emp1.short_name, emp1.nameE) as doctor_name, CONCAT_WS(' ', file.file_no, '-', p.$this->langColumn) as patient_name");
        $builder->join("list_data", "list_data.id=sip.payment_name", "left");
        $builder->join("service_invoices si", "si.id=sip.invoice_id", "left");
        $builder->join("employees emp", "emp.emp_id=si.created_by", "left");
        $builder->join("employees emp1", "emp1.emp_id=si.doctor_id", "left");
        $builder->join("patient_file file", "file.patient_id=si.patient_id", "left");
        $builder->join("patient p", "p.patient_id=file.patient_id", "left");
        if(!empty($userId)){
            $builder->where("si.created_by", $userId);
        }
        $builder->where("si.invoice_date >=", $from);
        $builder->where("si.invoice_date <=", $to);
        $query1 = $builder->get()->getResult();
        // groupBy payment
        $builder1 = $this->db->table('service_invoice_payment sip');
        $builder1->select("list_data.$this->langColumn as payment_method, SUM(sip.amount) as amount");
        $builder1->join("list_data", "list_data.id=sip.payment_name", "left");
        $builder1->join("service_invoices si", "si.id=sip.invoice_id", "left");
        if(!empty($userId)){
            $builder1->where("si.created_by", $userId);
        }
        $builder1->where("si.invoice_date >=", $from);
        $builder1->where("si.invoice_date <=", $to);
        $builder1->groupBy("sip.payment_name");
        $builder1->orderBy("si.invoice_date", "DESC");
        $query2 = $builder1->get()->getResult();

        return [$query1, $query2];
    }

    /*--------------------------
    | Get all user income by user Id 
    *--------------------------*/
    public function bdtaskt1m17_01_getUserIncomes11($userId=null, $payM=null, $vType=null, $from, $to, $doctor_id=null){
        $method = array(121100005, 121100006, 121100001, 121100011, 121100003, 121100002, 121100004, 310000003, 310000002);
        $builder = $this->db->table('acc_transaction trans');
        $builder->select("trans.*, vtl.nameE as vtype_name, acc_coa.HeadName as payment_method, CONCAT_WS(' ', emp.short_name, emp.$this->langColumn) as created_by, CONCAT_WS(' ', emp1.short_name, emp1.nameE) as doctor_name, CONCAT_WS(' ', file.file_no, '-', p.$this->langColumn) as patient_name");
        $builder->join("acc_coa", "acc_coa.HeadCode=trans.COAID", "left");
        $builder->join("employees emp", "emp.emp_id=trans.CreateBy", "left");
        $builder->join("employees emp1", "emp1.emp_id=trans.DoctorId", "left");
        $builder->join("patient_file file", "file.patient_id=trans.PatientID", "left");
        $builder->join("patient p", "p.patient_id=trans.PatientID", "left");
        $builder->join("voucher_type_list vtl", "vtl.type=trans.Vtype", "left");
        if(!empty($doctor_id)){
            $builder->where("trans.DoctorId", $doctor_id);
        }
        if(!empty($userId)){
            $builder->where("trans.CreateBy", $userId);
        }
        if(!empty($vType)){
            $builder->where("trans.Vtype", $vType);
        }
        if(session('branchId') > 0){
            $builder->where('trans.BranchID', session('branchId'));
        }
        $builder->whereIn("trans.COAID", !empty($payM)?[$payM]:$method);
        $builder->whereIn("trans.IsAppove", [1, 0]);
        $builder->where("trans.CreateDate >=", $from);
        $builder->where("trans.CreateDate <=", $to);
        $builder->orderBy("trans.CreateDate", "DESC");
        $query1 = $builder->get()->getResult();
        // groupBy payment
        $builder1 = $this->db->table('acc_transaction trans');
        $builder1->select("acc_coa.HeadName as payment_method, SUM(trans.Debit) as Debit, SUM(trans.Credit) as Credit");
        $builder1->join("acc_coa", "acc_coa.HeadCode=trans.COAID", "left");
        if(!empty($userId)){
            $builder1->where("trans.CreateBy", $userId);
        }
        if(!empty($doctor_id)){
            $builder1->where("trans.DoctorId", $doctor_id);
        }
        if(!empty($vType)){
            $builder1->where("trans.Vtype", $vType);
        }
        if(session('branchId') > 0){
            $builder1->where('trans.BranchID', session('branchId'));
        }
        //$builder1->where("trans.IsAppove", 1);
        $builder1->whereIn("trans.COAID", !empty($payM)?[$payM]:$method);
        $builder1->whereIn("trans.IsAppove", [1, 0]);
        $builder1->where("trans.CreateDate >=", $from);
        $builder1->where("trans.CreateDate <=", $to);
        $builder1->groupBy("trans.COAID");
        $builder1->orderBy("trans.CreateDate", "DESC");
        $query2 = $builder1->get()->getResult();

        return [$query1, $query2];
    }

    /*--------------------------
    | Get services revenue by date range
    *--------------------------*/
    public function bdtaskt1m17_02_getServicesRevenue($from=null,  $to=null){
        $builder = $this->db->table('service_invoice_details sid');
        $builder->select("GROUP_CONCAT(DISTINCT(si.id)) as ids, 
            si.id, si.doctor_id, emp.short_name, 
            emp.nameE, SUM(sid.qty*sid.price) as price, 
            SUM(sid.offer_discount) as offer, 
            SUM(sid.doctor_discount) as doctor_discount, 
            SUM(sid.over_limit_discount) as over_limit, 
            (SELECT GROUP_CONCAT(ref_voucher) FROM payment_vouchers WHERE FIND_IN_SET(ref_voucher,GROUP_CONCAT(si.id)) AND vtype='RFV' AND status=1) as ref_voucher, 
            (SELECT SUM(payment+vat) FROM payment_vouchers WHERE FIND_IN_SET(ref_voucher,GROUP_CONCAT(si.id)) AND vtype='RFV' AND status=1) as totalPay");
        $builder->join("service_invoices si", "si.id=sid.invoice_id", "left");
        $builder->join("employees emp", "emp.emp_id=si.doctor_id", "left");
        if(session('branchId') > 0){
            $builder->where('si.branch_id', session('branchId'));
        }
        $builder->where("si.status", 1);
        $builder->where("si.invoice_date >=", $from);
        $builder->where("si.invoice_date <=", $to);
        $builder->groupStart()
                ->where('si.isCredit', 0)
                    ->orGroupStart()
                        ->where('si.isCredit', 1)
                        ->where('si.isCreditApproved', 1)
                    ->groupEnd()
                ->groupEnd();
        $builder->groupBy("si.doctor_id");
        $builder->orderBy("emp.short_name");
        $query = $builder->get()->getResult();

        $i=0;
        if(!empty($query)){
            return $query;
        }else{
           return false;
        }
    }

    /*--------------------------
    | Get services revenue for doctor commission
    *--------------------------*/
    public function bdtaskt1m17_06_getDoctorCom($doctorId=null, $serviceId=[], $from,  $to){
        $builder = $this->db->table('service_invoice_details sid');
        $builder->select("sid.app_service_id, 
            GROUP_CONCAT(DISTINCT(sid.invoice_id)) as invoiceIds, 
            sid.invoice_id, COUNT(*) as sids, sid.price, SUM(sid.qty*sid.price) as qty_price, 
            SUM(sid.qty) as qty, SUM(sid.offer_discount) as offer_discount, 
            SUM(sid.doctor_discount) as doctor_discount, 
            SUM(sid.over_limit_discount) as over_limit_discount, 
            SUM(sid.no_commission_amt) as no_commission_amt, 
            SUM(sid.vat) as vat, SUM(sid.amount) as amount, 
            GROUP_CONCAT(IF(si.isCredit=1 AND si.isCreditApproved=1, sid.invoice_id, 0)) as creditIds, 
            SUM(IF(si.isCredit=1 AND si.isCreditApproved=1, sid.amount, 0)) as creditAmount, 
            SUM(IF(sid.no_commission_amt=0, 0, sid.offer_discount)) as offDiscount,  
            SUM(IF(sid.creditedOn=130 AND si.isCreditApproved=1, sid.creditAmt, 0)) as creditedDoctor, 
            SUM(IF(sid.creditedOn=150 AND si.isCreditApproved=1, sid.creditAmt, 0)) as creditedPatient, 
            (SELECT GROUP_CONCAT(ref_voucher) FROM payment_vouchers WHERE FIND_IN_SET(ref_voucher,GROUP_CONCAT(DISTINCT(sid.invoice_id))) AND vtype='RFV' AND status=1) as ref_voucher, 
            (SELECT SUM(pvd.vat+pvd.amount) FROM payment_voucher_details pvd 
            LEFT JOIN payment_vouchers pv ON pv.id=pvd.voucher_id 
            WHERE FIND_IN_SET(pv.ref_voucher,GROUP_CONCAT(sid.invoice_id)) AND pvd.service_id=sid.app_service_id AND pv.vtype='RFV' AND pv.status=1) as totalPay,
            IF(cp.consume_cost_deduct=1,(SELECT SUM((consd.aqty-consd.return_qty)*consd.price) FROM wh_order cons 
            LEFT JOIN wh_order_details consd ON consd.request_id=cons.id 
            WHERE FIND_IN_SET(cons.invoice_id,GROUP_CONCAT(sid.invoice_id)) AND cons.service_id=sid.app_service_id AND cons.doctor_id=si.doctor_id AND cons.isApproved=1 AND cons.isCollected=1 AND cons.status=1),0) as consumed,
            si.patient_id, 
            si.doctor_id, 
            s.code_no, 
            s.nameE as service_name, 
            IF(cp.fixed_cost_deduct=1,s.cost_amount,0) as cost_amount, 
            emp.short_name,
            cp.commission, cp.consume_cost_deduct, cp.fixed_cost_deduct");
        $builder->join("service_invoices si", "si.id=sid.invoice_id", "left");
        $builder->join("services s", "s.id=sid.app_service_id", "left");
        $builder->join("employees emp", "emp.emp_id=si.doctor_id", "left");
        $builder->join("doctor_services ds", "ds.doctor_id=si.doctor_id AND ds.service_id=sid.app_service_id", "left");
        $builder->join("service_commission cp", "cp.service_id=sid.app_service_id AND cp.program_id=ds.program_id", "left");
        if(session('branchId') > 0){
            $builder->where('si.branch_id', session('branchId'));
        }
        $builder->where("si.invoice_date >=", $from);
        $builder->where("si.invoice_date <=", $to);
        $builder->where("si.status", 1);
        if(!empty($doctorId)){
            $builder->where("si.doctor_id", $doctorId);
        }
        if(!empty($serviceId)){
            $builder->whereIn("sid.app_service_id", $serviceId);
        }
        $builder->groupStart()
                ->where('si.isCredit', 0)
                    ->orGroupStart()
                        ->where('si.isCredit', 1)
                        ->where('si.isCreditApproved', 1)
                    ->groupEnd()
                ->groupEnd();
        $builder->groupBy("si.doctor_id");
        $builder->groupBy("sid.app_service_id");
        $builder->orderBy("emp.short_name");
        $query = $builder->get()->getResult();

        $i=0;
        if(!empty($query)){
            //foreach ($query as $value) {
                // $programId = $this->db->table('doctor_services')->select('program_id')->where('doctor_id', $value->doctor_id)->where('service_id', $value->app_service_id)->get()->getRowArray();
                // if(!empty($programId)){
                //     $query[$i]->commission =$this->db->table('service_commission')->select('commission, consume_cost_deduct, fixed_cost_deduct')->where('service_id', $value->app_service_id)->where('program_id', $programId['program_id'])->get()->getRowArray();
                // }else{
                //     $query[$i]->commission = array();
                // }
                // if(!empty($query[$i]->commission)){ 
                //     if($query[$i]->commission['consume_cost_deduct']==1){
                //         $query[$i]->isConsumable = $this->db->table('wh_order iir')
                //                                         ->select('iir.id, SUM((iird.aqty - iird.return_qty)*iird.price) as total_cost')
                //                                         ->join('wh_order_details iird', 'iird.request_id=iir.id', 'left')
                //                                         ->where('iir.patient_id', $value->patient_id)
                //                                         ->where('iir.doctor_id', $value->doctor_id)
                //                                         ->where('iir.service_id', $value->app_service_id)
                //                                         ->groupBy('iird.request_id')
                //                                         ->get()->getRowArray();
                //     }
                // }else{
                //     $query[$i]->isConsumable = array();
                // }
                //$i++;
            //}
            return $query;
        }else{
           return false;
        }
    }

    /*--------------------------
    | payment method list data
    *--------------------------*/
    public function bdtaskt1m17_07_getPaymentMethod()
    { 
        $method = array(121100005, 121100006, 121100001, 121100011, 121100003, 121100002, 121100004, 310000003, 310000002);
        return $this->db->table('acc_coa')->select("HeadCode as id, CONCAT_WS(' ', HeadCode, '-',HeadName) as text")
                        ->whereIn('HeadCode', $method)->get()->getResult();
    }

    /*--------------------------
    | Get voucher data 
    *--------------------------*/
    public function bdtaskt1m17_08_getVoucherData($id, $type)
    { 
        if($type=='SINV'){
            $query = $this->db->table('service_invoices si')
                                ->select("si.*, 'Service Invoice' as type, CONCAT_WS(' ', patient.nameE, patient.nameA) as patient_name, patient.mobile, patient.age, file.gender, file.file_no, CONCAT_WS(' ', emp.short_name, '-', emp.nameE) as createdBy, CONCAT_WS(' ', emp1.short_name, '-', emp1.nameE) as doctor")
                                ->join("patient", "patient.patient_id=si.patient_id", "left")
                                ->join("patient_file file", "file.patient_id=patient.patient_id", "left")
                                ->join("employees emp", "emp.emp_id=si.created_by", "left")
                                ->join("employees emp1", "emp1.emp_id=si.doctor_id", "left")
                                ->where('si.id', $id)->get()->getRow();
            $query->details = $this->db->table('service_invoice_details sid')
                                    ->select("sid.*, s.code_no, s.nameE")
                                    ->join('services s', 's.id=sid.app_service_id', 'left')
                                    ->where('invoice_id', $id)->get()->getResult();
            $query->payments = $this->db->table('service_invoice_payment sip')
                                    ->select("sip.amount, ld.nameE")
                                    ->join('list_data ld', 'ld.id=sip.payment_name', 'left')
                                    ->where('invoice_id', $id)->get()->getResult();
        }else if($type=='RV'){
            $query = $this->db->table('vouchers v')
                                ->select("v.*, 'Receipt Voucher' as type, CONCAT_WS(' ', patient.nameE, patient.nameA) as patient_name, patient.mobile, patient.age, file.gender, file.file_no, CONCAT_WS(' ', emp.short_name, '-', emp.nameE) as createdBy, CONCAT_WS(' ', emp1.short_name, '-', emp1.nameE) as doctor")
                                ->join("patient", "patient.patient_id=v.patient_id", "left")
                                ->join("patient_file file", "file.patient_id=patient.patient_id", "left")
                                ->join("employees emp", "emp.emp_id=v.created_by", "left")
                                ->join("employees emp1", "emp1.emp_id=v.doctor_id", "left")
                                ->where('v.id', $id)->get()->getRow();
            $query->details = $this->db->table('voucher_details vd')
                                    ->select("vd.*, s.code_no, s.nameE, s.nameA")
                                    ->join("services s", "s.id=vd.service_id", "left")
                                    ->where('vd.voucher_id', $id)
                                    ->get()
                                    ->getResult();
            $query->payments = $this->db->table('voucher_payment_method vpm')
                                    ->select("vpm.amount, ld.nameE, ld.nameA")
                                    ->join("list_data ld", "ld.id=vpm.pay_method_id", "left")
                                    ->where('vpm.voucher_id', $id)
                                    ->get()
                                    ->getResult();
        }else if($type=='PV' || $type=='RFV'){ 
            $query = $this->db->table('payment_vouchers pv')
                                ->select("pv.*, CONCAT_WS(' ', patient.nameE, patient.nameA) as patient_name, patient.mobile, patient.age, file.gender, file.file_no, CONCAT_WS(' ', emp.short_name, '-', emp.nameE) as createdBy, CONCAT_WS(' ', emp1.short_name, '-', emp1.nameE) as doctor")
                                ->join("patient", "patient.patient_id=pv.patient_id", "left")
                                ->join("patient_file file", "file.patient_id=patient.patient_id", "left")
                                ->join("employees emp", "emp.emp_id=pv.created_by", "left")
                                ->join("employees emp1", "emp1.emp_id=pv.doctor_id", "left")
                                ->where('pv.id', $id)->get()->getRow();
            $query->details = $this->db->table('payment_voucher_details pvd')
                                ->select("pvd.*, s.code_no, s.nameE, s.nameA")
                                ->join("services s", "s.id=pvd.service_id", "left")
                                ->where('pvd.voucher_id', $id)
                                ->get()
                                ->getResult();
            $query->payments = $this->db->table('payment_voucher_method pvm')
                                   ->select("pvm.amount, ld.nameE")
                                   ->join('list_data ld', 'ld.id=pvm.pay_method_id', 'left')
                                   ->where('pvm.voucher_id', $id)->get()->getResult();

        }else if($type=='DV' || $type=='CV' || $type=='JV' || $type=='CONTA' || $type=='OPBL'){
            $VNo = $type.'-'.$id;
            $query = $this->db->table('acc_transaction trans')
                          ->select("trans.*, CASE Vtype WHEN 'DV' THEN 'Debit Voucher' WHEN 'CV' THEN 'Credit Voucher' WHEN 'JV' THEN 'Journal Voucher' WHEN 'CONTA' THEN 'Contra Voucher' END as type, CONCAT_WS(' ', emp.short_name, emp.nameE) as created_by, CONCAT_WS(' ', emp1.short_name, emp1.nameE) as updated_by")
                          ->join('employees emp', 'emp.emp_id=trans.CreateBy', 'left')
                          ->join('employees emp1', 'emp1.emp_id=trans.UpdateBy', 'left')
                          ->where('trans.VNo', $VNo)
                          ->get()->getRow();
            $query->details = $this->db->table('acc_transaction trans1')
                              ->select('trans1.COAID, trans1.Debit, trans1.Credit, acc_coa.HeadName')
                              ->join('acc_coa', 'acc_coa.HeadCode=trans1.COAID', 'left')
                              ->where('trans1.VNo', $VNo)
                              ->get()->getResult();
        }else if($type=='CONS'){
            $query = array();
        }else if($type=='GRECI' || $type=='GRETI'){
           $query = array();
        }else if($type=='SUPI'){
            $query = array();
        }
        return $query;
    }

    /*--------------------------
    | search services data
    *--------------------------*/
    public function bdtaskt1m17_09_searchServices($text, $langColumn)
    { 
      if(!empty($text)){
        return $this->db->table('services')
                        ->select("id, CONCAT(code_no, ' - ', $langColumn) as text")
                        ->like('code_no', $text)
                        ->orLike('nameE', $text)
                        ->orLike('nameA', $text)
                        ->get()->getResult();
      }else{
        return false;
      }
    }

    /*--------------------------
    | Get services revenue by date range
    *--------------------------*/
    public function bdtaskt1m17_10_getIncomeCalculator($doctor_id=null, $from,  $to){
        $builder = $this->db->table('service_invoice_details sid');
        $builder->select("GROUP_CONCAT(DISTINCT(si.id)) as ids, 
            si.doctor_id,
            SUM(IF(y.payment_name=125, y.amount, 0)) as bankTransfer, 
            SUM(IF(y.payment_name=127, y.amount, 0)) as paidAdvance, 
            GROUP_CONCAT(IF(y.payment_name=127, si.id, 0)) as advIds, 
            SUM(IF(y.payment_name=130, y.amount, 0)) as creditSale, 
            GROUP_CONCAT(IF(y.payment_name=130, si.id, 0)) as creditIds,
            SUM(sid.qty*sid.price) as price, 
            SUM(sid.offer_discount) as offer, 
            SUM(sid.doctor_discount) as doctor_discount, 
            SUM(sid.over_limit_discount) as over_limit, 
            SUM(sid.no_commission_amt) as no_commission,
            CONCAT_WS(' ', emp.short_name, emp.nameE) as doctor,
            (SELECT GROUP_CONCAT(ref_voucher) FROM payment_vouchers WHERE FIND_IN_SET(ref_voucher,GROUP_CONCAT(si.id)) AND vtype='RFV' AND status=1) as ref_voucher, 
            (SELECT SUM(payment+vat) FROM payment_vouchers WHERE FIND_IN_SET(ref_voucher,GROUP_CONCAT(si.id)) AND vtype='RFV' AND status=1) as totalPay");
        $builder->join("service_invoices si", "si.id=sid.invoice_id", "left");
        $builder->join("employees emp", "emp.emp_id=si.doctor_id", "left");
        $builder->join("service_invoice_payment y", "y.invoice_id=si.id", "left");
        if(session('branchId') > 0){
            $builder->where('si.branch_id', session('branchId'));
        }
        if(!empty($doctor_id)){
            $builder->where("si.doctor_id", $doctor_id);
        }
        $builder->where("si.created_date >=", $from);
        $builder->where("si.created_date <=", $to);
        $builder->groupStart()
                ->where('si.isCredit', 0)
                    ->orGroupStart()
                        ->where('si.isCredit', 1)
                        ->where('si.isCreditApproved', 1)
                    ->groupEnd()
                ->groupEnd();
        $builder->where("si.status", 1);
        $builder->groupBy("si.doctor_id");
        $builder->orderBy("si.created_date", "DESC");
        $query = $builder->get()->getResult();

        if(!empty($query)){
            return $query;
        }else{
           return false;
        }
    }

    /*--------------------------
    | Get services revenue by date range
    *--------------------------*/
    public function bdtaskt1m17_11_getReportInvoices($doctor_id, $type, $from,  $to){
        if($type=='noCom'){
            $query = $this->db->table('service_invoice_details sid')
                        ->select("si.*, 'SINV' as vtype, CONCAT_WS(' ', file.file_no, p.nameE) as patient, CONCAT_WS(' ', emp.short_name, emp.nameE) as createdBy, SUM(apps.offer_discount + apps.doctor_discount + apps.over_limit_discount) as totalDis, SUM(sid.offer_discount + sid.doctor_discount + sid.over_limit_discount) as totalDisAmt, SUM(sid.creditAmt) as totalCredit, SUM(sid.amount) as totalAmount")
                        ->join("service_invoices si", "si.id=sid.invoice_id", "left")
                        ->join("appoint_services apps", "apps.service_id =sid.app_service_id AND FIND_IN_SET(sid.invoice_id,apps.invoiceID)", "left")
                        ->join("patient p", "p.patient_id=si.patient_id", "left")
                        ->join("patient_file file", "file.patient_id=p.patient_id", "left")
                        ->join("employees emp", "emp.emp_id=si.created_by", "left")
                        ->where("si.status", 1)
                        ->where("si.invoice_date >=", $from)
                        ->where("si.invoice_date <=", $to)
                        ->where("si.doctor_id", $doctor_id)
                        ->where("sid.no_commission_amt >", 0)
                        ->groupStart()
                            ->where('si.isCredit', 0)
                            ->orGroupStart()
                                ->where('si.isCredit', 1)
                                ->where('si.isCreditApproved', 1)
                            ->groupEnd()
                        ->groupEnd()
                        ->groupBy("sid.invoice_id")
                        ->orderBy("si.invoice_date", "DESC")
                        ->get()->getResult();
        }else if($type=='overLimit'){
            $query = $this->db->table('service_invoice_details sid')
                        ->select("si.*, 'SINV' as vtype, CONCAT_WS(' ', file.file_no, p.nameE) as patient, CONCAT_WS(' ', emp.short_name, emp.nameE) as createdBy, SUM(apps.over_limit_discount) as totalDis, SUM(sid.over_limit_discount) as totalDisAmt, SUM(sid.creditAmt) as totalCredit, SUM(sid.amount) as totalAmount")
                        ->join("service_invoices si", "si.id=sid.invoice_id", "left")
                        ->join("appoint_services apps", "apps.service_id =sid.app_service_id AND FIND_IN_SET(sid.invoice_id,apps.invoiceID)", "left")
                        ->join("patient p", "p.patient_id=si.patient_id", "left")
                        ->join("patient_file file", "file.patient_id=p.patient_id", "left")
                        ->join("employees emp", "emp.emp_id=si.created_by", "left")
                        ->where("si.status", 1)
                        ->where("si.invoice_date >=", $from)
                        ->where("si.invoice_date <=", $to)
                        ->where("si.doctor_id", $doctor_id)
                        ->where("sid.over_limit_discount >", 0)
                        ->groupStart()
                            ->where('si.isCredit', 0)
                            ->orGroupStart()
                                ->where('si.isCredit', 1)
                                ->where('si.isCreditApproved', 1)
                            ->groupEnd()
                        ->groupEnd()
                        ->groupBy("sid.invoice_id")
                        ->orderBy("si.invoice_date", "DESC")
                        ->get()->getResult();
        }else if($type=='discount'){
            $query = $this->db->table('service_invoice_details sid')
                        ->select("si.*, 'SINV' as vtype, CONCAT_WS(' ', file.file_no, p.nameE) as patient, CONCAT_WS(' ', emp.short_name, emp.nameE) as createdBy, SUM(apps.doctor_discount) as totalDis, SUM(sid.doctor_discount) as totalDisAmt, SUM(sid.creditAmt) as totalCredit, SUM(sid.amount) as totalAmount")
                        ->join("service_invoices si", "si.id=sid.invoice_id", "left")
                        ->join("appoint_services apps", "apps.service_id =sid.app_service_id AND FIND_IN_SET(sid.invoice_id,apps.invoiceID)", "left")
                        ->join("patient p", "p.patient_id=si.patient_id", "left")
                        ->join("patient_file file", "file.patient_id=p.patient_id", "left")
                        ->join("employees emp", "emp.emp_id=si.created_by", "left")
                        ->where("si.status", 1)
                        ->where("si.invoice_date >=", $from)
                        ->where("si.invoice_date <=", $to)
                        ->where("si.doctor_id", $doctor_id)
                        ->where("sid.doctor_discount >", 0)
                        ->groupStart()
                            ->where('si.isCredit', 0)
                            ->orGroupStart()
                                ->where('si.isCredit', 1)
                                ->where('si.isCreditApproved', 1)
                            ->groupEnd()
                        ->groupEnd()
                        ->groupBy("sid.invoice_id")
                        ->orderBy("si.invoice_date", "DESC")
                        ->get()->getResult();
        }else if($type=='offer'){
            $query = $this->db->table('service_invoice_details sid')
                        ->select("si.*, 'SINV' as vtype, CONCAT_WS(' ', file.file_no, p.nameE) as patient, CONCAT_WS(' ', emp.short_name, emp.nameE) as createdBy, SUM(apps.offer_discount) as totalDis, SUM(sid.offer_discount) as totalDisAmt, SUM(sid.creditAmt) as totalCredit, SUM(sid.amount) as totalAmount")
                        ->join("service_invoices si", "si.id=sid.invoice_id", "left")
                        ->join("appoint_services apps", "apps.service_id =sid.app_service_id AND FIND_IN_SET(sid.invoice_id,apps.invoiceID)", "left")
                        ->join("patient p", "p.patient_id=si.patient_id", "left")
                        ->join("patient_file file", "file.patient_id=p.patient_id", "left")
                        ->join("employees emp", "emp.emp_id=si.created_by", "left")
                        ->where("si.status", 1)
                        ->where("si.invoice_date >=", $from)
                        ->where("si.invoice_date <=", $to)
                        ->where("si.doctor_id", $doctor_id)
                        ->where("sid.offer_discount >", 0)
                        ->groupStart()
                            ->where('si.isCredit', 0)
                            ->orGroupStart()
                                ->where('si.isCredit', 1)
                                ->where('si.isCreditApproved', 1)
                            ->groupEnd()
                        ->groupEnd()
                        ->groupBy("sid.invoice_id")
                        ->orderBy("si.invoice_date", "DESC")
                        ->get()->getResult();
        }else if($type=='refund'){
            $ids = explode('-', $doctor_id);
            
            $query = $this->db->table('payment_vouchers pv')
                                ->select("pv.*, pv.payment as receipt, '0.00' as due, CONCAT_WS(' ', file.file_no, p.nameE) as patient, CONCAT_WS(' ', emp.short_name, emp.nameE) as createdBy, '0' as totalDis, '0' as totalDisAmt , '0' as totalCredit")
                                ->join("patient p", "p.patient_id=pv.patient_id", "left")
                                ->join("patient_file file", "file.patient_id=p.patient_id", "left")
                                ->join("employees emp", "emp.emp_id=pv.created_by", "left")
                                ->where("pv.status", 1)
                                ->where('pv.vtype', 'RFV')
                                //->where("pv.voucher_date >=", $from)
                                //->where("pv.voucher_date <=", $to)
                                ->whereIn('pv.ref_voucher', $ids)
                                ->get()->getResult();
        }else if($type=='paidAdv'){
            $ids = explode('-', $doctor_id);
            
            $query = $this->db->table('service_invoice_details sid')
                        ->select("si.*, 'SINV' as vtype, CONCAT_WS(' ', file.file_no, p.nameE) as patient, CONCAT_WS(' ', emp.short_name, emp.nameE) as createdBy, SUM(apps.offer_discount + apps.doctor_discount + apps.over_limit_discount) as totalDis, SUM(sid.offer_discount + sid.doctor_discount + sid.over_limit_discount) as totalDisAmt, SUM(sid.creditAmt) as totalCredit, SUM(sid.amount) as totalAmount")
                        ->join("service_invoices si", "si.id=sid.invoice_id", "left")
                        ->join("appoint_services apps", "apps.service_id =sid.app_service_id AND FIND_IN_SET(sid.invoice_id,apps.invoiceID)", "left")
                        ->join("patient p", "p.patient_id=si.patient_id", "left")
                        ->join("patient_file file", "file.patient_id=p.patient_id", "left")
                        ->join("employees emp", "emp.emp_id=si.created_by", "left")
                        ->where("si.status", 1)
                        ->where("si.invoice_date >=", $from)
                        ->where("si.invoice_date <=", $to)
                        ->whereIn("si.id", $ids)
                        ->groupStart()
                            ->where('si.isCredit', 0)
                            ->orGroupStart()
                                ->where('si.isCredit', 1)
                                ->where('si.isCreditApproved', 1)
                            ->groupEnd()
                        ->groupEnd()
                        ->groupBy("sid.invoice_id")
                        ->orderBy("si.invoice_date", "DESC")
                        ->get()->getResult();
        }else{
            $query = $this->db->table('service_invoice_details sid')
                        ->select("si.*, 'SINV' as vtype, CONCAT_WS(' ', file.file_no, p.nameE) as patient, CONCAT_WS(' ', emp.short_name, emp.nameE) as createdBy, SUM(apps.offer_discount + apps.doctor_discount + apps.over_limit_discount) as totalDis, SUM(sid.offer_discount + sid.doctor_discount + sid.over_limit_discount) as totalDisAmt, SUM(sid.creditAmt) as totalCredit, SUM(sid.amount) as totalAmount")
                        ->join("service_invoices si", "si.id=sid.invoice_id", "left")
                        ->join("appoint_services apps", "apps.service_id =sid.app_service_id AND FIND_IN_SET(sid.invoice_id,apps.invoiceID)", "left")
                        ->join("patient p", "p.patient_id=si.patient_id", "left")
                        ->join("patient_file file", "file.patient_id=p.patient_id", "left")
                        ->join("employees emp", "emp.emp_id=si.created_by", "left")
                        ->where("si.status", 1)
                        ->where("si.invoice_date >=", $from)
                        ->where("si.invoice_date <=", $to)
                        ->where("si.doctor_id", $doctor_id)
                        ->groupStart()
                            ->where('si.isCredit', 0)
                            ->orGroupStart()
                                ->where('si.isCredit', 1)
                                ->where('si.isCreditApproved', 1)
                            ->groupEnd()
                        ->groupEnd()
                        ->groupBy("sid.invoice_id")
                        ->orderBy("si.invoice_date", "DESC")
                        ->get()->getResult();
        }
        return $query;
    }

    /*--------------------------
    | Get services revenue by date range
    *--------------------------*/
    public function bdtaskt1m17_12_getCommissionInvoices($ids){
        $query = $this->db->table('service_invoices si')
                    ->select("si.*, 'SINV' as vtype, CONCAT_WS(' ', file.file_no, p.nameE) as patient, CONCAT_WS(' ', emp.short_name, emp.nameE) as createdBy, SUM(apps.offer_discount + apps.doctor_discount + apps.over_limit_discount) as totalDis, SUM(sid.offer_discount + sid.doctor_discount + sid.over_limit_discount) as totalDisAmt")
                    ->join('service_invoice_details sid', 'sid.invoice_id=si.id', 'left')
                    ->join("appoint_services apps", "apps.service_id =sid.app_service_id AND FIND_IN_SET(sid.invoice_id,apps.invoiceID)", "left")
                    ->join("patient p", "p.patient_id=si.patient_id", "left")
                    ->join("patient_file file", "file.patient_id=p.patient_id", "left")
                    ->join("employees emp", "emp.emp_id=si.created_by", "left")
                    ->whereIn("si.id", $ids)
                    ->groupBy("si.id")
                    ->get()->getResult();
        return $query;
    }

    /*--------------------------
    | Get services revenue by date range
    *--------------------------*/
    public function bdtaskt1m17_13_getUserActivityLogs($userId=null, $from, $to, $pageNumber, $page_size, $export=false){
        $offset = ($pageNumber-1) * $page_size;
        $query = $this->db->table('activity_logs log');
        $query->select("log.*, CONCAT_WS(' ', emp.short_name, emp.nameE) as created_by");
        $query->join("employees emp", "emp.emp_id=log.emp_id", "left");
        if(session('branchId') > 0){
            $query->where('log.branch_id', session('branchId'));
        }
        $query->where("log.created_date >=", $from);
        $query->where("log.created_date <=", $to);
        if(!empty($userId)){
            $query->where("log.emp_id", $userId);
        }
        $query->orderBy("log.id", "DESC");
        $rows = $query->countAllResults(false);
        if(!$export){
            $query->limit($page_size, $offset);
        }
        $query1 = $query->get()->getResult();
        return array('data' => $query1, 'num_rows'=>$rows );
    }

     /*--------------------------
    | Get services revenue for doctor commission 
    *--------------------------*/
    public function bdtaskt1m17_14_getDoctorIncentive($doctorId=null, $serviceId=[], $from,  $to){
        $builder = $this->db->table('service_invoice_details sid');
        $builder->select("sid.app_service_id, 
            GROUP_CONCAT(DISTINCT(sid.invoice_id)) as invoiceIds, 
            sid.invoice_id, COUNT(*) as sids, sid.price, SUM(sid.qty*sid.price) as qty_price, 
            SUM(sid.qty) as qty, SUM(sid.offer_discount) as offer_discount, 
            SUM(sid.doctor_discount) as doctor_discount, 
            SUM(sid.over_limit_discount) as over_limit_discount, 
            SUM(sid.no_commission_amt) as no_commission_amt, 
            SUM(sid.vat) as vat, SUM(sid.amount) as amount, 
            GROUP_CONCAT(IF(si.isCredit=1 AND si.isCreditApproved=1, sid.invoice_id, 0)) as creditIds, 
            SUM(IF(si.isCredit=1 AND si.isCreditApproved=1, sid.amount, 0)) as creditAmount, 
            SUM(IF(sid.no_commission_amt=0, 0, sid.offer_discount)) as offDiscount,  
            (SELECT GROUP_CONCAT(ref_voucher) FROM payment_vouchers WHERE FIND_IN_SET(ref_voucher,GROUP_CONCAT(DISTINCT(sid.invoice_id))) AND vtype='RFV' AND status=1) as ref_voucher, 
            (SELECT SUM(pvd.vat+pvd.amount) FROM payment_voucher_details pvd 
            LEFT JOIN payment_vouchers pv ON pv.id=pvd.voucher_id 
            WHERE FIND_IN_SET(pv.ref_voucher,GROUP_CONCAT(sid.invoice_id)) AND pvd.service_id=sid.app_service_id AND pv.vtype='RFV' AND pv.status=1) as totalPay,
            si.patient_id, 
            si.doctor_id, 
            s.code_no, 
            s.incentive_percent, 
            s.nameE as service_name, 
            s.cost_amount, 
            emp.short_name");
        $builder->join("service_invoices si", "si.id=sid.invoice_id", "left");
        $builder->join("services s", "s.id=sid.app_service_id", "left");
        $builder->join("employees emp", "emp.emp_id=si.doctor_id", "left");
        if(session('branchId') > 0){
            $builder->where('si.branch_id', session('branchId'));
        }
        $builder->where("si.invoice_date >=", $from);
        $builder->where("si.invoice_date <=", $to);
        $builder->where("si.status", 1);
        if(!empty($doctorId)){
            $builder->where("si.doctor_id", $doctorId);
        }
        if(!empty($serviceId)){
            $builder->whereIn("sid.app_service_id", $serviceId);
        }
        $builder->groupStart()
                ->where('si.isCredit', 0)
                    ->orGroupStart()
                        ->where('si.isCredit', 1)
                        ->where('si.isCreditApproved', 1)
                    ->groupEnd()
                ->groupEnd();
        $builder->groupBy("si.doctor_id");
        $builder->groupBy("sid.app_service_id");
        $builder->orderBy("emp.short_name");
        $query = $builder->get()->getResult();

        return $query;
    }
   

    public function getInvoicesByType(){
        $query = $this->db->table('service_invoices si');
        $query->select("si.*, CONCAT_WS(' ', emp.short_name, emp.nameE) as createdBy");
        $query->join("employees emp", "emp.emp_id=si.created_by", "left");
        $query->where("si.invoice_date >=", $from);
        $query->where("si.invoice_date <=", $to);
        $query->where("si.doctor_id", $doctor_id);
        $query->orderBy("si.invoice_date", "DESC");
        $query->get()->getResult();
    }

    public function excelData(){
        
        $builder3 = $this->db->table('appointment');
        $builder3->select("appointment.*, file.file_no, CONCAT_WS(' ', patient.nameE, patient.nameA) as patient_name, patient.mobile, CONCAT_WS(' ', employees.nameE, employees.nameA) as doctor_name, schedule.available_days, department.nameE, branch.nameE as branch_name, services.code_no, services.nameE as service_name, opr.nameE as procedure, '-' as package, CONCAT_WS(' ', p1.patient_id, '-', p1.nameE) as know_us_by, aprm.startTime, aprm.endTime");
        $builder3->join("patient", "patient.patient_id=appointment.patient_id", "left");
        $builder3->join("patient p1", "p1.patient_id=patient.know_us", "left");
        $builder3->join("patient_file file", "file.patient_id=patient.patient_id", "left");
        $builder3->join("employees", "employees.emp_id=appointment.doctor_id", "left");
        $builder3->join("schedule", "schedule.schedule_id =appointment.schedule_id", "left");
        $builder3->join("department", "department.id=appointment.department_id", "left");
        $builder3->join("services", "services.id=appointment.default_service", "left");
        $builder3->join("operating_room opr", "opr.id=appointment.default_op_room    ", "left");
        $builder3->join("branch", "branch.id=appointment.branch_id", "left");
        $builder3->join("appoint_procedure_room aprm", "aprm.appoint_id=appointment.appoint_id", "left");
        $query3  = $builder3->get();
        $records = $query3->getResultArray();

        $data     = array();
        foreach($records as $record ){ 

            if($record['isConvert']==1){
                $type = '<span class="badge badge-success-soft">Real</span>';
            }else if($record['isConvert']==0){
                $type = '<span class="badge badge-info-soft">Waitng</span>';
            }else{
                $type = '<span class="badge badge-warning-soft text-warning">On Call</span>';
            }

            $data[] = array( 
                'appoint_id'    => $record['appoint_id'],
                'file_no'       => $record['file_no'],
                'patient_type'  => $record['patient_type'],
                'appoint_type'  => $record['appoint_type'],
                'patient_name'  => $record['patient_name'],
                'know_us_by'    => $record['know_us_by'],
                'nameE'         => $record['nameE'],
                'mobile'        => $record['mobile'],
                'duration'      => $record['duration'],
                'visit_status'  => $record['visit_status'],
                'enter_time'    => date('h:i A', strtotime($record['enter_time'])),
                'start_time'    => date('h:i A', strtotime($record['start_time'])),
                'out_time'      => date('h:i A', strtotime($record['out_time'])),
                'code_no'       => $record['code_no'],
                'package'       => $record['package'],
                'service_name'  => $record['service_name'],
                'doctor_name'   => $record['doctor_name'],
                'branch_name'   => $record['branch_name'],
                'available_days'=> date('l', strtotime($record['date'])),
                'remarks'       => $record['remarks'],
                'date'          => date('d/m/Y', strtotime($record['date'])),
                'type'          => $type,
                'booked_from_time'=> date('h:i A', strtotime($record['booked_from_time'])),
                'booked_to_time'=> date('h:i A', strtotime($record['booked_to_time'])),
                'procedure'     => !empty($record['procedure'])?$record['procedure'].'('.date('h:i A', strtotime($record['startTime'])).' - '.date('h:i A', strtotime($record['endTime'])).')':'--',
            ); 
        }

        ## Response
        $response = array(
            "aaData"               => $data
        );
        return $response; 
    }
   
}
?>