<?php namespace App\Modules\Reports\Models;
use CodeIgniter\Model;
class Bdtaskt1m17VatModel extends Model
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
    | Get invoices or voucher with vat
    *--------------------------*/
    public function bdtaskt1m17_01_getInvOrReceiptVat11($from, $to, $type){
        $builder = $this->db->table('service_invoice_payment sip');
        $builder->select("si.*, 'Service Invoice' as type,  CONCAT_WS(' ', emp.short_name, emp.$this->langColumn) as createdBy, CONCAT_WS(' ', emp1.short_name, emp1.nameE) as doctor_name, CONCAT_WS(' ', file.file_no, '-', p.$this->langColumn) as patient_name, file.nationality, file.nid_no");
        $builder->join("service_invoices si", "si.id=sip.invoice_id", "left");
        $builder->join("employees emp", "emp.emp_id=si.created_by", "left");
        $builder->join("employees emp1", "emp1.emp_id=si.doctor_id", "left");
        $builder->join("patient_file file", "file.patient_id=si.patient_id", "left");
        $builder->join("patient p", "p.patient_id=file.patient_id", "left");
        $builder->where("si.invoice_date >=", $from);
        $builder->where("si.invoice_date <=", $to);
        if(!empty($type)){
            $builder->where("si.vat >", 0);
        }else{
            $builder->where("si.vat", 0);
        }
        $builder->whereNotIn("sip.payment_name", [127]);
        $builder->groupBy("sip.invoice_id");
        $builder->orderBy("si.invoice_date", "DESC");
        $query1 = $builder->get()->getResult();
        // Receipt Voucher
        $builder1 = $this->db->table('voucher_payment_method vpm');
        $builder1->select("v.*, 'Receipt Voucher' as type,  CONCAT_WS(' ', emp.short_name, emp.$this->langColumn) as createdBy, CONCAT_WS(' ', emp1.short_name, emp1.nameE) as doctor_name, CONCAT_WS(' ', file.file_no, '-', p.$this->langColumn) as patient_name, file.nationality, file.nid_no");
        $builder1->join("vouchers v", "v.id=vpm.voucher_id", "left");
        $builder1->join("employees emp", "emp.emp_id=v.created_by", "left");
        $builder1->join("employees emp1", "emp1.emp_id=v.doctor_id", "left");
        $builder1->join("patient_file file", "file.patient_id=v.patient_id", "left");
        $builder1->join("patient p", "p.patient_id=file.patient_id", "left");
        $builder1->where("v.voucher_date >=", $from);
        $builder1->where("v.voucher_date <=", $to);
        if(!empty($type)){
            $builder1->where("v.vat >", 0);
        }else{
            $builder1->where("v.vat", 0);
        }
        $builder1->whereNotIn("vpm.pay_method_id", [127]);
        $builder1->groupBy("vpm.voucher_id");
        $builder1->orderBy("v.voucher_date", "DESC");
        $query2 = $builder1->get()->getResult();

        return array('invoices'=>$query1, 'receipt'=>$query2);
    }

    /*--------------------------
    | Get invoices or voucher with vat
    *--------------------------*/
    public function bdtaskt1m17_01_getInvOrReceiptWithVat($branch_id, $from, $to){
        $builder1 = $this->db->table('service_invoice_payment sip');
        $builder1->select("si.id, si.total, si.receipt, si.vat, 'Service Invoice' as type, 'SINV' as vtype, SUM(sid.offer_discount + sid.doctor_discount + sid.over_limit_discount) as total_discount, CONCAT_WS(' ', emp.short_name, emp.$this->langColumn) as createdBy, CONCAT_WS(' ', emp1.short_name, emp1.nameE) as doctor_name, CONCAT_WS(' ', file.file_no, '-', p.$this->langColumn) as patient_name, file.nationality, file.nid_no, si.created_date");
        $builder1->join('service_invoices si', 'si.id = sip.invoice_id', 'left');
        $builder1->join('service_invoice_details sid', 'sid.invoice_id  = sip.invoice_id', 'left');
        $builder1->join('employees emp', 'emp.emp_id = si.created_by', 'left');
        $builder1->join('employees emp1', 'emp1.emp_id = si.doctor_id', 'left');
        $builder1->join('patient_file file', 'file.patient_id = si.patient_id', 'left');
        $builder1->join('patient p', 'p.patient_id = si.patient_id', 'left');
        if($branch_id > 0){
            $builder1->where('si.branch_id', $branch_id);
        }
        $builder1->where('si.invoice_date >=', $from);
        $builder1->where('si.invoice_date <=', $to);
        $builder1->where('si.vat >', 0);
        $builder1->where('si.status', 1);
        $builder1->whereNotIn('sip.payment_name', [127]);
        $builder1->groupBy('sip.invoice_id');
        $builder1->orderBy('si.invoice_date', 'DESC');
        $query1 = $builder1->getCompiledSelect();

        $builder2 = $this->db->table('voucher_payment_method vpm');
        $builder2->select("v.id, v.total, v.receipt, v.vat, 'Receipt Voucher' as type, 'RV' as vtype, SUM(vd.discount) as total_discount, CONCAT_WS(' ', emp.short_name, emp.$this->langColumn) as createdBy, CONCAT_WS(' ', emp1.short_name, emp1.nameE) as doctor_name, CONCAT_WS(' ', file.file_no, '-', p.$this->langColumn) as patient_name, file.nationality, file.nid_no, v.created_date");
        $builder2->join('vouchers v', 'v.id = vpm.voucher_id', 'left');
        $builder2->join('voucher_details vd', 'vd.voucher_id = vpm.voucher_id', 'left');
        $builder2->join('employees emp', 'emp.emp_id = v.created_by', 'left');
        $builder2->join('employees emp1', 'emp1.emp_id = v.doctor_id', 'left');
        $builder2->join('patient_file file', 'file.patient_id = v.patient_id', 'left');
        $builder2->join('patient p', 'p.patient_id = v.patient_id', 'left');
        if($branch_id > 0){
            $builder2->where('v.branch_id', $branch_id);
        }
        $builder2->where('v.voucher_date >=', $from);
        $builder2->where('v.voucher_date <=', $to);
        $builder2->where('v.vat >', 0);
        $builder2->where('v.status', 1);
        $builder2->whereNotIn('vpm.pay_method_id', [127]);
        $builder2->groupBy('vpm.voucher_id');
        $builder2->orderBy('v.voucher_date', 'DESC');
        $query2 = $builder2->getCompiledSelect();

        $builder3 = $this->db->table('payment_vouchers pv');
        $builder3->select("pv.id, pv.total, IF(pv.vtype='PV', CONCAT('-',pv.payment), CONCAT('-', pv.payment+pv.vat)) as receipt, CONCAT('-',pv.vat), IF(pv.vtype='PV', 'Payment Voucher', 'Refund Voucher') as type, pv.vtype, '0' as total_discount, CONCAT_WS(' ', emp.short_name, emp.$this->langColumn) as createdBy, CONCAT_WS(' ', emp1.short_name, emp1.nameE) as doctor_name, CONCAT_WS(' ', file.file_no, '-', p.$this->langColumn) as patient_name, file.nationality, file.nid_no, pv.created_date");
        $builder3->join('employees emp', 'emp.emp_id = pv.created_by', 'left');
        $builder3->join('employees emp1', 'emp1.emp_id = pv.doctor_id', 'left');
        $builder3->join('patient_file file', 'file.patient_id = pv.patient_id', 'left');
        $builder3->join('patient p', 'p.patient_id = pv.patient_id', 'left');
        if($branch_id > 0){
            $builder3->where('pv.branch_id', $branch_id);
        }
        $builder3->where('pv.voucher_date >=', $from);
        $builder3->where('pv.voucher_date <=', $to);
        $builder3->where('pv.vat >', 0);
        $builder3->where('pv.status', 1);
        $builder3->orderBy('pv.voucher_date', 'DESC');
        $query3 = $builder3->getCompiledSelect();
        $data = $this->db->query('('.$query1.') UNION ALL ('.$query2.') UNION ALL ('.$query3.')');
        
        return $data->getResult();
    }

    /*--------------------------
    | Get invoices or voucher with vat 
    *--------------------------*/
    public function bdtaskt1m17_02_getInvOrReceiptWithOutVat($branch_id, $from, $to){
        $service_inv = '';
        $receipt = '';
        $payment = '';
        if($branch_id > 0){
            $service_inv = ' AND si.branch_id="'.$branch_id.'"';
            $receipt = ' AND v.branch_id="'.$branch_id.'"';
            $payment = ' AND pv.branch_id="'.$branch_id.'"';
        }
        $data = $this->db->query("
            (SELECT si.id, si.total, si.receipt, si.vat, 'Service Invoice' as type, 'SINV' as vtype, CONCAT_WS(' ', emp.short_name, emp.$this->langColumn) as createdBy, CONCAT_WS(' ', emp1.short_name, emp1.nameE) as doctor_name, CONCAT_WS(' ', file.file_no, '-', p.$this->langColumn) as patient_name, file.nationality, file.nid_no, si.created_date
            FROM service_invoice_payment AS sip
            LEFT JOIN service_invoices AS si ON si.id = sip.invoice_id
            LEFT JOIN employees AS emp ON emp.emp_id = si.created_by
            LEFT JOIN employees AS emp1 ON emp1.emp_id = si.doctor_id
            LEFT JOIN patient_file AS file ON file.patient_id = si.patient_id
            LEFT JOIN patient AS p ON p.patient_id = file.patient_id
            WHERE si.invoice_date >= '$from' AND si.invoice_date <= '$to' $service_inv AND si.vat = 0 
            AND sip.payment_name NOT IN(127) AND si.status = 1
            GROUP BY sip.invoice_id
            ORDER BY si.invoice_date DESC)
            UNION ALL
            (SELECT v.id, v.total, v.receipt, v.vat, 'Receipt Voucher' as type, 'RV' as vtype, CONCAT_WS(' ', emp.short_name, emp.$this->langColumn) as createdBy, CONCAT_WS(' ', emp1.short_name, emp1.nameE) as doctor_name, CONCAT_WS(' ', file.file_no, '-', p.$this->langColumn) as patient_name, file.nationality, file.nid_no, v.created_date
            FROM voucher_payment_method AS vpm
            LEFT JOIN vouchers AS v ON v.id = vpm.voucher_id
            LEFT JOIN employees AS emp ON emp.emp_id = v.created_by
            LEFT JOIN employees AS emp1 ON emp1.emp_id = v.doctor_id
            LEFT JOIN patient_file AS file ON file.patient_id = v.patient_id
            LEFT JOIN patient AS p ON p.patient_id = file.patient_id
            WHERE v.voucher_date >= '$from' AND v.voucher_date <= '$to' $receipt AND v.vat = 0 
            AND vpm.pay_method_id NOT IN(127) AND v.status = 1
            GROUP BY vpm.voucher_id
            ORDER BY v.voucher_date DESC)
            UNION ALL
            (SELECT pv.id, pv.total, IF(pv.vtype='PV', CONCAT('-',pv.payment), CONCAT('-', pv.payment+pv.vat)) as receipt, CONCAT('-',pv.vat), IF(pv.vtype='PV', 'Payment Voucher', 'Refund Voucher') as type, pv.vtype, CONCAT_WS(' ', emp.short_name, emp.$this->langColumn) as createdBy, CONCAT_WS(' ', emp1.short_name, emp1.nameE) as doctor_name, CONCAT_WS(' ', file.file_no, '-', p.$this->langColumn) as patient_name, file.nationality, file.nid_no, pv.created_date
            FROM payment_vouchers AS pv
            LEFT JOIN employees AS emp ON emp.emp_id = pv.created_by
            LEFT JOIN employees AS emp1 ON emp1.emp_id = pv.doctor_id
            LEFT JOIN patient_file AS file ON file.patient_id = pv.patient_id
            LEFT JOIN patient AS p ON p.patient_id = file.patient_id
            WHERE pv.voucher_date >= '$from' AND pv.voucher_date <= '$to' $payment AND pv.vat = 0 AND pv.status = 1
            ORDER BY pv.voucher_date DESC)
        ");
                        
        return $data = $data->getResult();
    }

    /*--------------------------
    | Get purchase with vat 
    *--------------------------*/
    public function bdtaskt1m17_03_getInventoryStockWithVat($branch_id, $from, $to){
        $receive ='';
        if(!empty($branch_id)){
            $receive =' AND bwh.branch_id="'.$branch_id.'"';
        }
        $data = $this->db->query("
            (SELECT whr.purchase_id, whr.voucher_no, whr.date, whr.sub_total, whr.vat, whr.grand_total, whr.receipt, whr.created_at, 'RV' as type, CONCAT_WS(' ', emp.short_name, emp.$this->langColumn) as createdBy, CONCAT_WS(' ', sup.code_no, sup.$this->langColumn) as supplier_name, sup.vat_no, sup.cr_no, bwh.$this->langColumn as warehouse
            FROM wh_receive AS whr
            LEFT JOIN employees AS emp ON emp.emp_id = whr.created_by
            LEFT JOIN wh_supplier_information AS sup ON sup.id = whr.supplier_id
            LEFT JOIN wh_production_store AS bwh ON bwh.id = whr.store_id
            WHERE whr.date >= '$from' AND whr.date <= '$to' $receive AND whr.vat > 0 AND whr.isApproved=1 AND whr.status = 1
            ORDER BY whr.date DESC)
            UNION ALL
            (SELECT whret.purchase_id, whret.voucher_no, whret.date, whret.sub_total, CONCAT('-', whret.vat), CONCAT('-', whret.grand_total), CONCAT('-', whret.grand_total) as receipt, whret.created_at, 'RET' as type, CONCAT_WS(' ', emp.short_name, emp.$this->langColumn) as createdBy, CONCAT_WS(' ', sup.code_no, sup.$this->langColumn) as supplier_name, sup.vat_no, sup.cr_no, bwh.$this->langColumn as warehouse
            FROM wh_return AS whret
            LEFT JOIN employees AS emp ON emp.emp_id = whret.return_by
            LEFT JOIN wh_supplier_information AS sup ON sup.id = whret.supplier_id
            LEFT JOIN wh_production_store AS bwh ON bwh.id = whret.store_id
            WHERE whret.date >= '$from' AND whret.date <= '$to' $receive AND whret.vat > 0
            ORDER BY whret.date DESC)
            ");
        return $data->getResult();
    }

    /*--------------------------
    | Get purchase without vat 
    *--------------------------*/
    public function bdtaskt1m17_04_getInventoryStockWithoutVat($branch_id, $from, $to){
        $receive ='';
        if(!empty($branch_id)){
            $receive =' AND bwh.branch_id="'.$branch_id.'"';
        }
        $data = $this->db->query("
            (SELECT whr.purchase_id, whr.voucher_no, whr.date, whr.sub_total, whr.vat, whr.grand_total, whr.receipt, whr.created_at, 'RV' as type, CONCAT_WS(' ', emp.short_name, emp.$this->langColumn) as createdBy, CONCAT_WS(' ', sup.code_no, sup.$this->langColumn) as supplier_name, sup.vat_no, sup.cr_no, bwh.$this->langColumn as warehouse
            FROM wh_receive AS whr
            LEFT JOIN employees AS emp ON emp.emp_id = whr.created_by
            LEFT JOIN wh_supplier_information AS sup ON sup.id = whr.supplier_id
            LEFT JOIN wh_production_store AS bwh ON bwh.id = whr.store_id
            WHERE whr.date >= '$from' AND whr.date <= '$to' $receive AND whr.vat = 0 AND whr.isApproved=1 AND whr.status = 1
            ORDER BY whr.date DESC)
            UNION ALL
            (SELECT whret.purchase_id, whret.voucher_no, whret.date, whret.sub_total, CONCAT('-', whret.vat), CONCAT('-', whret.grand_total), CONCAT('-', whret.grand_total) as receipt, whret.created_at, 'RET' as type, CONCAT_WS(' ', emp.short_name, emp.$this->langColumn) as createdBy, CONCAT_WS(' ', sup.code_no, sup.$this->langColumn) as supplier_name, sup.vat_no, sup.cr_no, bwh.$this->langColumn as warehouse
            FROM wh_return AS whret
            LEFT JOIN employees AS emp ON emp.emp_id = whret.return_by
            LEFT JOIN wh_supplier_information AS sup ON sup.id = whret.supplier_id
            LEFT JOIN wh_production_store AS bwh ON bwh.id = whret.store_id
            WHERE whret.date >= '$from' AND whret.date <= '$to' $receive AND whret.vat = 0
            ORDER BY whret.date DESC)
            ");
        return $data->getResult();
    }

    /*--------------------------
    | Get invoices or voucher with vat 
    *--------------------------*/
    public function bdtaskt1m17_02_getInventoryStockVat($branch_id, $from, $to, $type){
        $builder = $this->db->table('wh_receive whr');
        $builder->select("whr.*,  CONCAT_WS(' ', emp.short_name, emp.$this->langColumn) as createdBy, CONCAT_WS(' ', sup.code_no, sup.$this->langColumn) as supplier_name, sup.vat_no, sup.cr_no, bwh.$this->langColumn as warehouse");
        $builder->join("employees emp", "emp.emp_id=whr.created_by", "left");
        $builder->join("wh_supplier_information sup", "sup.id=whr.supplier_id", "left");
        $builder->join("wh_production_store bwh", "bwh.id=whr.store_id", "left");
        $builder->join("wh_receive_payment_details pm", "pm.receive_id=whr.id", "left");
        if($branch_id > 0){
            $builder->where("bwh.branch_id", $branch_id);
        }
        $builder->where("whr.date >=", $from);
        $builder->where("whr.date <=", $to);
        if(!empty($type)){
            $builder->where("whr.vat >", 0);
        }else{
            $builder->where("whr.vat", 0);
        }
        $builder->where("whr.isApproved", 1);
        $builder->whereNotIn("pm.payment_method", [127]);
        $builder->groupBy("pm.receive_id");
        $builder->orderBy("whr.date", "DESC");
        $query1 = $builder->get()->getResult();

        return $query1;
    }

    /*--------------------------
    | Get purchase with vat 
    *--------------------------*/
    public function bdtaskt1m17_06_getJournalWithVat($branch_id, $from, $to, $type, $status){
        $builder = $this->db->table('journal_vouchers jv');
        $builder->select("jv.*,  CONCAT_WS(' ', emp.short_name, emp.$this->langColumn) as createdBy");
        $builder->join("employees emp", "emp.emp_id=jv.created_by", "left");
        $builder->where("jv.voucher_date >=", $from);
        $builder->where("jv.voucher_date <=", $to);
        if($branch_id > 0){
            $builder->where("jv.branch_id", $branch_id);
        }
        if(!empty($type)){
            $builder->where("jv.vat >", 0);
        }else{
            $builder->where("jv.vat", 0);
        }
        $builder->where("jv.status", $status);
        $builder->orderBy("jv.voucher_date", "DESC");
        $query1 = $builder->get()->getResult();

        return $query1;
    }
   
}
?>