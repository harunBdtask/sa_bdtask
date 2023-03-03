<?php namespace App\Modules\Reports\Models;
use CodeIgniter\Model;

class Bdtaskt1m1m1SalesReportModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        // if(session('defaultLang')=='english'){
        //     $this->langColumn = 'nameE';
        // }else{
        //     $this->langColumn = 'nameA';
        // }
    }

    public function bdtaskt1m1_01_getDailySales($date, $category_id){
        $builder = $this->db->table('do_main a');
        $builder->select("a.vouhcer_no, b.quantity, b.total_kg, c.nameE as item_name, d.nameE as cat_name, 
            e.nameE as item_type, f.name as dealer_name, f.address as dealer_address");
        $builder->join("do_details b", "b.do_id=a.do_id", "left");
        $builder->join("wh_items c", "c.id=b.item_id", "left");
        $builder->join("wh_categories d", "d.id=c.cat_id", "left");
        $builder->join("list_data e", "e.id=c.type_id", "left");
        $builder->join("dealer_info f", "f.id=a.dealer_id", "left");
        $builder->where("d.id", $category_id);
        $builder->where("DATE(a.do_date) >=", $date);
        $builder->where("DATE(a.do_date) <=", $date);

        $query1 = $builder->get()->getResult();    
        return $query1;
    }

    public function bdtaskt1m1_02_getDoSummary($date){
        $builder = $this->db->table('do_main a');
        $builder->select("a.do_id, a.vouhcer_no, a.total_kg, a.grand_total, a.paid_amount, a.transport_cost, f.name as dealer_name, 
            f.address as dealer_address, u.fullname");
        $builder->join("dealer_info f", "f.id=a.dealer_id", "left");
        $builder->join("user u", 'u.emp_id = a.do_by');
        $builder->where("DATE(a.do_date) >=", $date);
        $builder->where("DATE(a.do_date) <=", $date);

        $query1 = $builder->get()->getResult();    
        return $query1;
    }

    public function bdtaskt1m1_03_getLongCredit($date){
        $builder = $this->db->table('do_main a');
        $builder->select("a.do_id, a.vouhcer_no, a.do_date, a.dealer_id, a.total_kg, a.grand_total, a.paid_amount, a.transport_cost, f.name as dealer_name, 
            f.address as dealer_address, f.agrement_date, f.closing_date, f.credit_amount, u.fullname");
        $builder->join("dealer_info f", "f.id=a.dealer_id", "left");
        $builder->join("user u", 'u.emp_id = a.do_by');
        $builder->where("DATE(a.do_date) >=", $date);
        $builder->where("DATE(a.do_date) <=", $date);

        $query1 = $builder->get()->getResult();    
        return $query1;
    }

    public function bdtaskt1m1_04_getShortCredit($date){
        $builder = $this->db->table('do_main a');
        $builder->select("a.do_id, a.vouhcer_no, a.do_date, a.dealer_id, a.total_kg, a.grand_total, a.paid_amount, a.transport_cost, f.name as dealer_name, 
            f.address as dealer_address, f.agrement_date, f.closing_date, f.credit_amount, u.fullname");
        $builder->join("dealer_info f", "f.id=a.dealer_id", "left");
        $builder->join("user u", 'u.emp_id = a.do_by');
        $builder->where("DATE(a.do_date) >=", $date);
        $builder->where("DATE(a.do_date) <=", $date);

        $query1 = $builder->get()->getResult();    
        return $query1;
    }

    public function bdtaskt1m1_05_getPurchaseDepartment($item_id){
        $builder = $this->db->table('wh_material_purchase a');
        $builder->select("e.voucher_no as spr_no, e.date as spr_date, f.qty as spr_qty, a.voucher_no as po_no, a.date as po_date, 
            b.qty as po_qty, c.truck_no, CONCAT(g.nameE, ' (', g.code_no, ')') as supplier_name, d.qty as received_qty");
        $builder->join("wh_material_purchase_details b", "b.purchase_id=a.id", "left");
        $builder->join("wh_material_receive c", "c.purchase_id=a.id", "left");
        $builder->join("wh_material_receive_details d", "d.purchase_id=a.id", "left");
        $builder->join("wh_material_requisition e", "e.id=a.requisition_id", "left");
        $builder->join("wh_material_requisition_details f", "f.purchase_id=a.requisition_id", "left");
        $builder->join("wh_supplier_information g", "g.id=a.supplier_id", "left");
        $builder->where("a.isApproved", 1);
        $builder->where("b.item_id", $item_id);
        $builder->where("f.item_id", $item_id);
        // $builder->where("DATE(a.date) >=", $date);
        // $builder->where("DATE(a.date) <=", $date);

        $query1 = $builder->get()->getResult();    
        return $query1;
    }

    public function bdtaskt1m1_06_getDistributorWiseSales($emp_id){
        $builder = $this->db->table('do_main a');
        $builder->select("a.do_id, a.vouhcer_no, a.do_date, a.dealer_id, a.total_kg, f.name as dealer_name, 
            f.address as dealer_address, c.nameE as item_name, d.nameE as cat_name, 
            e.nameE as item_type,");
        $builder->join("do_details b", "b.do_id=a.do_id", "left");
        $builder->join("wh_items c", "c.id=b.item_id", "left");
        $builder->join("wh_categories d", "d.id=c.cat_id", "left");
        $builder->join("list_data e", "e.id=c.type_id", "left");
        $builder->join("dealer_info f", "f.id=a.dealer_id", "left");
        $builder->join("hrm_employees emp", 'emp.employee_id = a.do_by');
        $builder->join("user u", 'u.emp_id = a.do_by');
        $builder->where("a.do_by", $emp_id);
        // $builder->where("DATE(a.do_date) >=", $date);
        // $builder->where("DATE(a.do_date) <=", $date);

        $query1 = $builder->get()->getResult();    
        return $query1;
    }

    public function bdtaskt1m1_07_getOfficerWiseSales($from, $to){
        $builder = $this->db->table('do_main a');
        $builder->select("a.do_id, a.vouhcer_no, a.do_date, a.dealer_id, a.total_kg, z.zone_name as teritory, 
            u.fullname, dsg.name as designation");
        $builder->join("dealer_info f", "f.id=a.dealer_id", "left");
        $builder->join("zone_tbl z", "z.id=f.zone_id", "left");
        $builder->join("hrm_employees emp", 'emp.employee_id = a.do_by');
        $builder->join("hrm_designation dsg", 'dsg.id = emp.designation');
        $builder->join("user u", 'u.emp_id = a.do_by');
        $builder->where("DATE(a.do_date) >=", $from);
        $builder->where("DATE(a.do_date) <=", $to);
        $query1 = $builder->get()->getResult();    
        return $query1;
    }

}
?>