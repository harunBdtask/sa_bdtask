<?php namespace App\Modules\Reports\Models;
use CodeIgniter\Model;

class Bdtaskt1m17InventoryReportModel extends Model
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
    | Get all item stock
    *--------------------------*/
    public function bdtaskt1m17_01_getItemStock($store_id, $item_id=null){
        $builder = $this->db->table('wh_production_stock ds');
        $builder->select("ds.*,wh_items.box_qty,wh_items.carton_qty,wh_items.$this->langColumn as item_name, wh_items.company_code, list_data.$this->langColumn as unit_name, (SELECT AVG(price) FROM wh_items_supplier WHERE item_id=ds.item_id) as price,             
            (
                SELECT SUM(s.aqty-s.return_qty) as used FROM wh_order_details s
                LEFT OUTER JOIN wh_order r ON (r.id=s.request_id)
                LEFT OUTER JOIN wh_sale_store w ON (w.id=r.sub_store_id)
                WHERE DATEDIFF(CURDATE(), r.approved_date) <=30 AND r.status=1 AND r.isApproved=1 AND r.isCollected=1 AND s.item_id=ds.item_id AND w.branch_id=dw.branch_id
            ) as used_30,
            (
                SELECT SUM(s.aqty-s.return_qty) as used FROM wh_order_details s
                LEFT OUTER JOIN wh_order r ON (r.id=s.request_id)
                LEFT OUTER JOIN wh_sale_store w ON (w.id=r.sub_store_id)
                WHERE DATEDIFF(CURDATE(), r.approved_date) <=90 AND r.status=1 AND r.isApproved=1 AND r.isCollected=1 AND s.item_id=ds.item_id AND w.branch_id=dw.branch_id
            ) as used_90
        ");

        $builder->join("wh_items", "wh_items.id=ds.item_id", "left");
        $builder->join("list_data", "list_data.id=wh_items.unit_id", "left");
        $builder->join("wh_production_store dw", "dw.id=ds.store_id", "left");
        if(!empty($store_id)){
            $builder->where("ds.store_id", $store_id);
        }
        if(!empty($item_id)){
            $builder->where("ds.item_id", $item_id);
        }
        if(session('branchId') >0 ){
            $builder->where("dw.branch_id", session('branchId'));
        }
        //$builder->where("ds.invoice_date >=", $from);
        //$builder->where("ds.invoice_date <=", $to);
        $query1 = $builder->get()->getResult();        

        return $query1;
    }

    /*--------------------------
    | Get all item transfer to sub stock
    *--------------------------*/
    public function bdtaskt1m17_02_getItemSubReceive($sub_store_id, $item_id, $from=null, $to=null){
        $builder = $this->db->table('wh_item_requests r');
        $builder->select("r.*,rd.aqty,i.$this->langColumn as item_name,u.$this->langColumn as unit_name, i.company_code,(SELECT AVG(price) FROM wh_items_supplier WHERE item_id=rd.item_id) as price");
        $builder->join("wh_item_request_details rd", "rd.request_id=r.id", "left");
        $builder->join("wh_sale_store w", "w.id=r.sub_store_id", "left");
        $builder->join("wh_items i", "i.id=rd.item_id", "left");
        $builder->join("list_data u", "u.id=i.unit_id", "left");
        if(!empty($sub_store_id)){
            $builder->where("r.sub_store_id", $sub_store_id);
        }
        if(!empty($item_id)){
            $builder->where("rd.item_id", $item_id);
        }
        if(session('branchId') >0 ){
            $builder->where("w.branch_id", session('branchId'));
        }
        $builder->where("r.isApproved", 1);
        $builder->where("r.approved_date >=", $from);
        $builder->where("r.approved_date <=", $to);
        $builder->orderBy("r.approved_date", "DESC");
        $query1 = $builder->get()->getResult();        

        return $query1;
    }

    /*--------------------------
    | Get all item consumption
    *--------------------------*/
    public function bdtaskt1m17_03_getItemConsumption($sub_store_id, $item_id, $doctor_id, $from=null, $to=null, $invoice_from=null, $invoice_to=null){
        $builder = $this->db->table('wh_order r');
        $builder->select("r.*,rd.aqty,rd.return_qty,i.$this->langColumn as item_name,u.$this->langColumn as unit_name, i.company_code,rd.price,w.$this->langColumn as store_name,d.name as dept_name, e.$this->langColumn as doctor_name, f.file_no, s.code_no");
        $builder->join("wh_order_details rd", "rd.request_id=r.id", "left");
        $builder->join("wh_sale_store w", "w.id=r.sub_store_id", "left");
        $builder->join("hrm_departments d", "d.id=r.from_department_id", "left");
        $builder->join("patient_file f", "f.patient_id=r.patient_id", "left");
        $builder->join("employees e", "e.emp_id=r.doctor_id", "left");
        $builder->join("wh_items i", "i.id=rd.item_id", "left");
        $builder->join("list_data u", "u.id=i.unit_id", "left");
        $builder->join("services s", "s.id=r.service_id", "left");
        if($invoice_from !='' && $invoice_to !=''){
            $builder->join("service_invoices inv", "inv.id=r.invoice_id", "left");
        }
        if(!empty($sub_store_id)){
            $builder->whereIn("r.sub_store_id", $sub_store_id);
        }
        if(!empty($item_id)){
            $builder->where("rd.item_id", $item_id);
        }
        if(!empty($doctor_id)){
            $builder->where("r.doctor_id", $doctor_id);
        }
        if(session('branchId') >0 ){
            $builder->where("w.branch_id", session('branchId'));
        }
        $builder->where("r.isApproved", 1);
        $builder->where("r.isCollected", 1);
        if($from !='' && $to !=''){
            $builder->where("r.approved_date >=", $from);
            $builder->where("r.approved_date <=", $to);
        }
        if($invoice_from !='' && $invoice_to !=''){
            $builder->where("inv.invoice_date >=", $invoice_from);
            $builder->where("inv.invoice_date <=", $invoice_to);
        }
        $builder->orderBy("r.approved_date", "DESC");
        $query1 = $builder->get()->getResult();        

        return $query1;
    }

    /*--------------------------
    | Get all item sub stock
    *--------------------------*/
    public function bdtaskt1m17_04_getItemSubStock($sub_store_id, $item_id){
        $builder = $this->db->table('wh_sale_stock ds');
        $builder->select("ds.*,wh_items.box_qty,wh_items.carton_qty,wh_items.$this->langColumn as item_name, wh_items.company_code,list_data.$this->langColumn as unit_name,
            (
                SELECT AVG(td.price) FROM wh_items_supplier td WHERE td.item_id=ds.item_id 
            ) as price,                       
            (
                SELECT SUM(s.aqty-s.return_qty) as used FROM wh_order_details s
                LEFT OUTER JOIN wh_order r ON (r.id=s.request_id)
                LEFT OUTER JOIN wh_sale_store w ON (w.id=r.sub_store_id)
                WHERE DATEDIFF(CURDATE(), r.approved_date) <=30 AND r.status=1 AND r.isApproved=1 AND r.isCollected=1 AND s.item_id=ds.item_id AND w.branch_id=dw.branch_id AND r.sub_store_id=".$sub_store_id."
            ) as used_30,
            (
                SELECT SUM(s.aqty-s.return_qty) as used FROM wh_order_details s
                LEFT OUTER JOIN wh_order r ON (r.id=s.request_id)
                LEFT OUTER JOIN wh_sale_store w ON (w.id=r.sub_store_id)
                WHERE DATEDIFF(CURDATE(), r.approved_date) <=90 AND r.status=1 AND r.isApproved=1 AND r.isCollected=1 AND s.item_id=ds.item_id AND w.branch_id=dw.branch_id AND r.sub_store_id=".$sub_store_id."
            ) as used_90
        ");
        $builder->join("wh_items", "wh_items.id=ds.item_id", "left");
        $builder->join("list_data", "list_data.id=wh_items.unit_id", "left");
        $builder->join("wh_sale_store dw", "dw.id=ds.sub_store_id", "left");
        if(!empty($sub_store_id)){
            $builder->where("ds.sub_store_id", $sub_store_id);
        }
        if(!empty($item_id)){
            $builder->where("ds.item_id", $item_id);
        }
        if(session('branchId') >0 ){
            $builder->where("dw.branch_id", session('branchId'));
        }
        //$builder->where("ds.invoice_date >=", $from);
        //$builder->where("ds.invoice_date <=", $to);
        $query1 = $builder->get()->getResult();        

        return $query1;
    }


    /*--------------------------
    | Get all item receive
    *--------------------------*/
    public function bdtaskt1m17_05_getItemReceive($store_id, $supplier_id, $item_id, $from=null, $to=null){
        $builder = $this->db->table('wh_receive r');
        $builder->select("r.*,rd.qty,rd.carton,rd.box,rd.box_qty,rd.price,i.$this->langColumn as item_name,u.$this->langColumn as unit_name, i.company_code,s.$this->langColumn as supplier_name");
        $builder->join("wh_receive_details rd", "rd.receive_id=r.id", "left");
        $builder->join("wh_production_store w", "w.id=r.store_id", "left");
        $builder->join("wh_supplier_information s", "s.id=r.supplier_id", "left");
        $builder->join("wh_items i", "i.id=rd.item_id", "left");
        $builder->join("list_data u", "u.id=i.unit_id", "left");
        if(!empty($store_id)){
            $builder->where("r.store_id", $store_id);
        }
        if(!empty($supplier_id)){
            $builder->where("r.supplier_id", $supplier_id);
        }
        if(!empty($item_id)){
            $builder->where("rd.item_id", $item_id);
        }
        if(session('branchId') >0 ){
            $builder->where("w.branch_id", session('branchId'));
        }
        $builder->where("r.isApproved", 1);
        $builder->where("r.approved_date >=", $from);
        $builder->where("r.approved_date <=", $to);
        $builder->orderBy("r.approved_date", "DESC");
        $query1 = $builder->get()->getResult();        

        return $query1;
    }

    /*--------------------------
    | Get all item transfer
    *--------------------------*/
    public function bdtaskt1m17_06_getItemTransfer($to_store_id, $item_id, $from=null, $to=null){
        $builder = $this->db->table('wh_item_transfer r');
        $builder->select("r.*,rd.aqty,i.$this->langColumn as item_name,u.$this->langColumn as unit_name,w.$this->langColumn as store_name, i.company_code,(SELECT AVG(price) FROM wh_items_supplier WHERE item_id=rd.item_id) as price");
        $builder->join("wh_item_transfer_details rd", "rd.request_id=r.id", "left");
        $builder->join("wh_sale_store w", "w.id=r.from_store_id", "left");
        $builder->join("wh_items i", "i.id=rd.item_id", "left");
        $builder->join("list_data u", "u.id=i.unit_id", "left");
        if(!empty($to_store_id)){
            $builder->where("r.to_store_id", $to_store_id);
        }
        if(!empty($item_id)){
            $builder->where("rd.item_id", $item_id);
        }
        if(session('branchId') >0 ){
            $builder->where("w.branch_id", session('branchId'));
        }
        $builder->where("r.isApproved", 1);
        $builder->where("r.approved_date >=", $from);
        $builder->where("r.approved_date <=", $to);
        $builder->orderBy("r.approved_date", "DESC");
        $query1 = $builder->get()->getResult();        

        return $query1;
    }

    /*--------------------------
    | Get all supplier payment
    *--------------------------*/
    public function bdtaskt1m17_07_getSupplierPayment($supplier_id, $from=null, $to=null){
        $builder = $this->db->table('wh_supplier_payment c');
        $builder->select("c.*, s.$this->langColumn as supplier_name, r.purchase_id");
        $builder->join("wh_supplier_information s", "s.id=c.supplier_id", "left");
        $builder->join("wh_receive r", "r.id=c.receive_id", "left");
        if(!empty($supplier_id)){
            $builder->where("c.supplier_id", $supplier_id);
        }
        if(session('branchId') >0 ){
            $builder->where("s.branch_id", session('branchId'));
        }
        //$builder->where("c.paid_status", 1);
        $builder->where("c.paid_date >=", $from);
        $builder->where("c.paid_date <=", $to);
        $builder->orderBy("c.paid_date", "DESC");
        $query1 = $builder->get()->getResult();        

        return $query1;
    }

    /*--------------------------
    | Get item receive all
    *--------------------------*/
    public function bdtaskt1m17_08_getItemReceiveDetailsById($item_id, $store_id){
        $builder = $this->db->table('wh_receive r');
        $builder->select("r.*,ROUND(rd.qty-(
                            SELECT IF(SUM(b.qty) IS NULL,0,SUM(b.qty)) FROM wh_return a
                            LEFT OUTER JOIN wh_return_details b ON (a.id=b.return_id)
                            WHERE a.purchase_id=r.purchase_id AND b.item_id=rd.item_id AND a.store_id=r.store_id
                        ),2) as qty,i.$this->langColumn as item_name,u.$this->langColumn as unit_name, s.$this->langColumn as supplier_name, 'in' as type");
        $builder->join("wh_receive_details rd", "rd.receive_id=r.id", "left");
        $builder->join("wh_production_store w", "w.id=r.store_id", "left");
        $builder->join("wh_supplier_information s", "s.id=r.supplier_id", "left");
        $builder->join("wh_items i", "i.id=rd.item_id", "left");
        $builder->join("list_data u", "u.id=i.unit_id", "left");
        if(!empty($item_id)){
            $builder->where("rd.item_id", $item_id);
        }
        if(!empty($store_id)){
            $builder->where("r.store_id", $store_id);
        }
        if(session('branchId') >0 ){
            $builder->where("w.branch_id", session('branchId'));
        }
        $builder->where("r.isApproved", 1);
        $query1 = $builder->get()->getResult();        
        //echo get_last_query();exit;
        $builder = $this->db->table('wh_item_requests r');
        $builder->select("r.*, ROUND(rd.aqty-(
                            SELECT IF(SUM(b.qty) IS NULL,0,SUM(b.qty)) FROM wh_item_return a
                            LEFT OUTER JOIN wh_item_return_details b ON (a.id=b.return_id)
                            WHERE a.request_id=r.id AND b.item_id=rd.item_id AND a.sub_store_id=r.sub_store_id AND a.isCollected=1 AND a.status=1
                        ),2) as qty,i.$this->langColumn as item_name,u.$this->langColumn as unit_name, s.$this->langColumn as supplier_name, 'out' as type");
        $builder->join("wh_item_request_details rd", "rd.request_id=r.id", "left");
        $builder->join("wh_production_store w", "w.id=r.main_store_id", "left");
        $builder->join("wh_sale_store s", "s.id=r.sub_store_id", "left");
        $builder->join("wh_items i", "i.id=rd.item_id", "left");
        $builder->join("list_data u", "u.id=i.unit_id", "left");
        if(!empty($item_id)){
            $builder->where("rd.item_id", $item_id);
        }
        if(!empty($store_id)){
            $builder->where("r.main_store_id", $store_id);
        }
        if(session('branchId') >0 ){
            $builder->where("w.branch_id", session('branchId'));
        }
        $builder->where("r.isApproved", 1);
        $builder->where("r.isCollected", 1);
        $builder->where("rd.receive_details_id >", 0);
        $query2 = $builder->get()->getResult();    

        return array_merge($query1, $query2);
    }
    
    
    /*--------------------------
    | Get item receive all
    *--------------------------*/
    public function bdtaskt1m17_09_getSubStoreItemReceiveDetails($item_id, $store_id){

        $builder = $this->db->table('wh_item_requests r');
        $builder->select("'in' as type, r.approved_date,ROUND(rd.aqty-(
                            SELECT IF(SUM(b.return_qty) IS NULL,0,SUM(b.return_qty)) FROM wh_item_return a
                            LEFT OUTER JOIN wh_item_return_details b ON (a.id=b.return_id)
                            WHERE a.request_id=r.id AND b.item_id=rd.item_id AND a.sub_store_id=r.sub_store_id AND a.isCollected=1 AND a.status=1
                        ),2) as aqty,u.$this->langColumn as unit_name, s.$this->langColumn as store_name");
        $builder->join("wh_item_request_details rd", "rd.request_id=r.id", "left");
        //$builder->join("wh_sale_store w", "w.id=r.sub_store_id", "left");
        $builder->join("wh_production_store s", "s.id=r.main_store_id", "left");
        $builder->join("wh_items i", "i.id=rd.item_id", "left");
        $builder->join("list_data u", "u.id=i.unit_id", "left");
        if(!empty($item_id)){
            $builder->where("rd.item_id", $item_id);
        }
        if(!empty($store_id)){
            $builder->where("r.sub_store_id", $store_id);
        }
        if(session('branchId') >0 ){
            $builder->where("s.branch_id", session('branchId'));
        }
        $builder->where("r.isApproved", 1);
        $builder->where("r.isCollected", 1);
        $builder->where("r.status", 1);
        $query1 = $builder->get()->getResult();  
        

        $builder = $this->db->table('wh_item_transfer r');
        $builder->select("'in' as type, GROUP_CONCAT(r.approved_date) as approved_date,ROUND(SUM(rd.aqty)-(
                            SELECT IF(SUM(b.aqty) IS NULL,0,SUM(b.aqty)) FROM wh_item_transfer a
                            LEFT OUTER JOIN wh_item_transfer_details b ON (a.id=b.request_id)
                            WHERE a.to_store_id=r.from_store_id AND b.item_id=rd.item_id AND a.isApproved=1 AND a.isCollected=1 AND a.status=1                            
                        ),2) as aqty,u.$this->langColumn as unit_name,GROUP_CONCAT(DISTINCT s.$this->langColumn) as store_name");
        $builder->join("wh_item_transfer_details rd", "rd.request_id=r.id", "left");
        $builder->join("wh_sale_store w", "w.id=r.from_store_id", "left");
        $builder->join("wh_sale_store s", "s.id=r.to_store_id", "left");
        $builder->join("wh_items i", "i.id=rd.item_id", "left");
        $builder->join("list_data u", "u.id=i.unit_id", "left");
        if(!empty($item_id)){
            $builder->where("rd.item_id", $item_id);
        }
        if(!empty($store_id)){
            $builder->where("r.from_store_id", $store_id);
        }
        if(session('branchId') >0 ){
            $builder->where("w.branch_id", session('branchId'));
        }
        $builder->where("r.isApproved", 1);
        $builder->where("r.isCollected", 1);
        $builder->where("r.status", 1);
        $builder->groupBy(array("r.from_store_id", "rd.item_id"));
        $query2 = $builder->get()->getResult();


        $builder = $this->db->table('wh_item_transfer r');
        $builder->select("'out' as type, GROUP_CONCAT(r.approved_date) as approved_date,ROUND(SUM(rd.aqty)-(
                            SELECT IF(SUM(b.aqty) IS NULL,0,SUM(b.aqty)) FROM wh_item_transfer a
                            LEFT OUTER JOIN wh_item_transfer_details b ON (a.id=b.request_id)
                            WHERE a.from_store_id=r.to_store_id AND b.item_id=rd.item_id AND a.isApproved=1 AND a.isCollected=1 AND a.status=1                            
                        ),2) as aqty,u.$this->langColumn as unit_name,GROUP_CONCAT(DISTINCT w.$this->langColumn) as store_name");
        $builder->join("wh_item_transfer_details rd", "rd.request_id=r.id", "left");
        $builder->join("wh_sale_store w", "w.id=r.from_store_id", "left");
        $builder->join("wh_sale_store s", "s.id=r.to_store_id", "left");
        $builder->join("wh_items i", "i.id=rd.item_id", "left");
        $builder->join("list_data u", "u.id=i.unit_id", "left");
        if(!empty($item_id)){
            $builder->where("rd.item_id", $item_id);
        }
        if(!empty($store_id)){
            $builder->where("r.to_store_id", $store_id);
        }
        if(session('branchId') >0 ){
            $builder->where("w.branch_id", session('branchId'));
        }
        $builder->where("r.isApproved", 1);
        $builder->where("r.isCollected", 1);
        $builder->where("r.status", 1);
        $builder->groupBy(array("r.to_store_id", "rd.item_id"));
        $query3 = $builder->get()->getResult();     
        //echo get_last_query();exit;
 

        $builder = $this->db->table('wh_order r');
        $builder->select("'out' as type, r.approved_date, IF(r.isReturned=1 AND r.isReceived=1, (rd.aqty - rd.return_qty), rd.aqty) as aqty,i.$this->langColumn as item_name,u.$this->langColumn as unit_name, i.company_code,CONCAT(d.name,' ( by ', e.$this->langColumn,' ) ') as store_name");
        $builder->join("wh_order_details rd", "rd.request_id=r.id", "left");
        $builder->join("employees e", "e.emp_id=r.request_by", "left");
        $builder->join("hrm_departments d", "d.id=r.from_department_id", "left");
        $builder->join("wh_items i", "i.id=rd.item_id", "left");
        $builder->join("list_data u", "u.id=i.unit_id", "left");
        if(!empty($item_id)){
            $builder->where("rd.item_id", $item_id);
        }
        if(!empty($store_id)){
            $builder->where("r.sub_store_id", $store_id);
        }
        if(session('branchId') >0 ){
            $builder->where("d.branch_id", session('branchId'));
        }
        $builder->where("r.isApproved", 1);
        $builder->where("r.isCollected", 1);
        $query4 = $builder->get()->getResult();   


        return array_merge($query1, $query2, $query3, $query4);
    }

    /*--------------------------
    | Get all item receive
    *--------------------------*/
    public function bdtaskt1m17_10_getSupplierReturn($store_id, $supplier_id, $item_id, $from=null, $to=null){
        $builder = $this->db->table('wh_return r');
        $builder->select("r.*,rd.qty,rd.carton,rd.box,rd.box_qty,rd.price,i.$this->langColumn as item_name,u.$this->langColumn as unit_name, i.company_code,s.$this->langColumn as supplier_name");
        $builder->join("wh_return_details rd", "rd.return_id=r.id", "left");
        $builder->join("wh_production_store w", "w.id=r.store_id", "left");
        $builder->join("wh_supplier_information s", "s.id=r.supplier_id", "left");
        $builder->join("wh_items i", "i.id=rd.item_id", "left");
        $builder->join("list_data u", "u.id=i.unit_id", "left");
        if(!empty($store_id)){
            $builder->where("r.store_id", $store_id);
        }
        if(!empty($supplier_id)){
            $builder->where("r.supplier_id", $supplier_id);
        }
        if(!empty($item_id)){
            $builder->where("rd.item_id", $item_id);
        }
        if(session('branchId') >0 ){
            $builder->where("w.branch_id", session('branchId'));
        }
        $builder->where("r.date >=", $from);
        $builder->where("r.date <=", $to);
        $builder->orderBy("r.date", "DESC");
        $query1 = $builder->get()->getResult();        

        return $query1;
    }

    /*--------------------------
    | Get all item return
    *--------------------------*/
    public function bdtaskt1m17_11_getItemReturn($sub_store_id, $item_id, $from=null, $to=null){
        $builder = $this->db->table('wh_order r');
        $builder->select("r.*,rd.return_qty,i.$this->langColumn as item_name,u.$this->langColumn as unit_name, i.company_code,rd.price,d.name as dept_name,w.$this->langColumn as store_name, f.file_no");
        $builder->join("wh_order_details rd", "rd.request_id=r.id", "left");
        $builder->join("wh_sale_store w", "w.id=r.sub_store_id", "left");
        $builder->join("hrm_departments d", "d.id=r.from_department_id", "left");
        $builder->join("patient_file f", "f.patient_id=r.patient_id", "left");
        $builder->join("wh_items i", "i.id=rd.item_id", "left");
        $builder->join("list_data u", "u.id=i.unit_id", "left");
        if(!empty($sub_store_id)){
            $builder->where("r.sub_store_id", $sub_store_id);
        }
        if(!empty($item_id)){
            $builder->where("rd.item_id", $item_id);
        }
        if(session('branchId') >0 ){
            $builder->where("w.branch_id", session('branchId'));
        }
        $builder->where("r.isReturned", 1);
        $builder->where("r.returned_date >=", $from);
        $builder->where("r.returned_date <=", $to);
        $builder->orderBy("r.returned_date", "DESC");
        $query1 = $builder->get()->getResult();        

        return $query1;
    }

    /*--------------------------
    | Get all item stock
    *--------------------------*/
    public function bdtaskt1m17_12_getItemStockAll($supplier_id, $item_id, $pageNumber, $page_size){
        $offset = ($pageNumber-1) * $page_size;

        $branch = '';
        if(session('branchId') >0 ){
            $branch = ' AND w.branch_id= '.session('branchId');
        }

        $builder = $this->db->table('wh_items ds');
        $builder->select("ds.id, ds.box_qty, ds.carton_qty, ds.$this->langColumn as item_name, ds.company_code, list_data.$this->langColumn as unit_name, 
            (SELECT AVG(price) FROM wh_items_supplier WHERE item_id=ds.id) as price,             
            (
                SELECT SUM(s.stock_in + s.stock_adjust_in) as stock_in 
                FROM wh_production_stock s
                LEFT OUTER JOIN wh_production_store w ON (w.id=s.store_id)               
                WHERE s.item_id=ds.id ".$branch."
            ) as main_stock_in,           
            (
                SELECT SUM(s.stock_out + s.stock_adjust_out) as stock_out 
                FROM wh_production_stock s
                LEFT OUTER JOIN wh_production_store w ON (w.id=s.store_id)  
                WHERE s.item_id=ds.id ".$branch." 
            ) as main_stock_out,             
            (
                SELECT SUM(s.stock_in + s.stock_adjust_in) as stock_in 
                FROM wh_sale_stock s
                LEFT OUTER JOIN wh_sale_store w ON (w.id=s.sub_store_id)               
                WHERE s.item_id=ds.id ".$branch." 
            ) as sub_stock_in,            
            (
                SELECT SUM(s.stock_out + s.stock_adjust_out) as stock_out 
                FROM wh_sale_stock s
                LEFT OUTER JOIN wh_sale_store w ON (w.id=s.sub_store_id)               
                WHERE s.item_id=ds.id ".$branch." 
            ) as sub_stock_out,        
            (
                SELECT SUM(s.aqty-s.return_qty) as used FROM wh_order_details s
                LEFT OUTER JOIN wh_order r ON (r.id=s.request_id)
                LEFT OUTER JOIN wh_sale_store w ON (w.id=r.sub_store_id)
                WHERE DATEDIFF(CURDATE(), r.approved_date) <=30 AND r.status=1 AND r.isApproved=1 AND r.isCollected=1 AND s.item_id=ds.id ".$branch."
            ) as used_30,
            (
                SELECT SUM(s.aqty-s.return_qty) as used FROM wh_order_details s
                LEFT OUTER JOIN wh_order r ON (r.id=s.request_id)
                LEFT OUTER JOIN wh_sale_store w ON (w.id=r.sub_store_id)
                WHERE DATEDIFF(CURDATE(), r.approved_date) <=90 AND r.status=1 AND r.isApproved=1 AND r.isCollected=1 AND s.item_id=ds.id ".$branch."
            ) as used_90,
            (
                SELECT GROUP_CONCAT(r.nameE) as used FROM wh_items_supplier s
                LEFT OUTER JOIN wh_supplier_information r ON (r.id=s.supplier_id)
                WHERE s.item_id=ds.id
                GROUP BY s.item_id
            ) as supplier_name 
            
        ");

        //$builder->join("wh_items", "wh_items.id=ds.id", "left");
        $builder->join("list_data", "list_data.id=ds.unit_id", "left");
        //$builder->join("wh_production_store dw", "dw.id=ds.store_id", "left");
        /*if(!empty($store_id)){
            $builder->where("ds.store_id", $store_id);
        }*/
        if(!empty($item_id)){
            $builder->whereIn("ds.id", $item_id);
        } else if(!empty($supplier_id)){
            $builder2 = $this->db->table('wh_items_supplier ds');

            $builder2->select("ds.item_id");
            $builder2->where("ds.supplier_id", $supplier_id);
            $query2 = $builder2->get()->getResult();

            $supplier_items = array();
            foreach ($query2 as $key => $value) {
                $supplier_items[] = $value->item_id;                
            }
            if(!empty($supplier_items)){
                $builder->whereIn("ds.id", $supplier_items);
            }
        }
        //$builder->where("dw.branch_id", session('branchId'));
        $builder->groupBy("ds.id");

        $builder->limit($page_size, $offset);
        //$builder->where("ds.invoice_date >=", $from);
        //$builder->where("ds.invoice_date <=", $to);
        $query1 = $builder->get()->getResult();   

        $num_rows = $builder->countAllResults();   

        return array('data' => $query1, 'num_rows'=>$num_rows );
    }

    /*--------------------------
    | Get item receive all
    *--------------------------*/
    public function bdtaskt1m17_13_getItemStockDetailsAllById($item_id){
        $builder = $this->db->table('wh_production_stock r');
        $builder->select("(r.stock_in+r.stock_adjust_in) as stock_in, (r.stock_out+r.stock_adjust_out) as stock_out, r.stock,i.$this->langColumn as item_name,w.$this->langColumn as store_name,u.$this->langColumn as unit_name, 'main' as type");
        $builder->join("wh_production_store w", "w.id=r.store_id", "left");
        $builder->join("wh_items i", "i.id=r.item_id", "left");
        $builder->join("list_data u", "u.id=i.unit_id", "left");
        if(!empty($item_id)){
            $builder->where("r.item_id", $item_id);
        }
        if(session('branchId') >0 ){
            $builder->where("w.branch_id", session('branchId'));
        }
        $builder->orderBy("w.branch_id");
        $query1 = $builder->get()->getResult();        

        $builder = $this->db->table('wh_sale_stock r');
        $builder->select("(r.stock_in+r.stock_adjust_in) as stock_in, (r.stock_out+r.stock_adjust_out) as stock_out, r.stock,i.$this->langColumn as item_name,s.$this->langColumn as store_name,u.$this->langColumn as unit_name, 'sub' as type");
        $builder->join("wh_sale_store s", "s.id=r.sub_store_id", "left");
        $builder->join("wh_items i", "i.id=r.item_id", "left");
        $builder->join("list_data u", "u.id=i.unit_id", "left");
        if(!empty($item_id)){
            $builder->where("r.item_id", $item_id);
        }
        if(session('branchId') >0 ){
            $builder->where("s.branch_id", session('branchId'));
        }
        $builder->orderBy("s.branch_id");
        $query2 = $builder->get()->getResult();    

        return array_merge($query1, $query2);
    }

    /*--------------------------
    | Get all low stock item
    *--------------------------*/
    public function bdtaskt1m17_12_getLowStock($store_id, $store_id){
        if( !empty($store_id) ){
            $builder = $this->db->table('wh_production_stock ds');
            $builder->select("ds.stock,wh_items.box_qty,wh_items.carton_qty,wh_items.$this->langColumn as item_name, wh_items.company_code, list_data.$this->langColumn as unit_name, (SELECT AVG(price) FROM wh_receive_details WHERE item_id=ds.item_id) as price, 
             
            ");

            $builder->join("wh_items", "wh_items.id=ds.item_id", "left");
            $builder->join("list_data", "list_data.id=wh_items.unit_id", "left");
            $builder->join("wh_production_store dw", "dw.id=ds.store_id", "left");
            
            $builder->where("ds.store_id", $store_id);
            
            $builder->where("ds.stock <= wh_items.alert_qty");
            if(session('branchId') >0 ){
                $builder->where("dw.branch_id", session('branchId'));
            }
            $query1 = $builder->get()->getResult();        
            //echo get_last_query();exit;
        }
        if( !empty($store_id) ){
            $builder = $this->db->table('wh_sale_stock ds');
            $builder->select("ds.stock,wh_items.box_qty,wh_items.carton_qty,wh_items.$this->langColumn as item_name, wh_items.company_code, list_data.$this->langColumn as unit_name, (SELECT AVG(price) FROM wh_receive_details WHERE item_id=ds.item_id) as price, 
             
            ");

            $builder->join("wh_items", "wh_items.id=ds.item_id", "left");
            $builder->join("list_data", "list_data.id=wh_items.unit_id", "left");
            $builder->join("wh_sale_store dw", "dw.id=ds.sub_store_id", "left");
            
            $builder->where("ds.sub_store_id", $store_id);
            
            $builder->where("ds.stock <= wh_items.alert_qty");
            if(session('branchId') >0 ){
                $builder->where("dw.branch_id", session('branchId'));
            }
            $query2 = $builder->get()->getResult();
            if( !empty($query1) ){
                return array_merge($query1, $query2);
            } else {
                return $query2;
            }
        } if( !empty($query1) ){
            return $query1;
        } else {
            return array();
        }
    }

    /*--------------------------
    | Get all low stock item
    *--------------------------*/
    public function bdtaskt1m17_13_getOutofStock($store_id, $store_id){
        if(!empty($store_id)){
            $builder = $this->db->table('wh_production_stock ds');
            $builder->select("ds.stock,wh_items.box_qty,wh_items.carton_qty,wh_items.$this->langColumn as item_name, wh_items.company_code, list_data.$this->langColumn as unit_name, (SELECT AVG(price) FROM wh_receive_details WHERE item_id=ds.item_id) as price, 
             
            ");

            $builder->join("wh_items", "wh_items.id=ds.item_id", "left");
            $builder->join("list_data", "list_data.id=wh_items.unit_id", "left");
            $builder->join("wh_production_store dw", "dw.id=ds.store_id", "left");
            
            $builder->where("ds.store_id", $store_id);
            
            $builder->where("ds.stock <= 0");
            if(session('branchId') >0 ){
                $builder->where("dw.branch_id", session('branchId'));
            }
            $query1 = $builder->get()->getResult();        
            //echo get_last_query();exit;
            
        }

        if(!empty($store_id)){
            $builder = $this->db->table('wh_sale_stock ds');
            $builder->select("ds.stock,wh_items.box_qty,wh_items.carton_qty,wh_items.$this->langColumn as item_name, wh_items.company_code, list_data.$this->langColumn as unit_name, (SELECT AVG(price) FROM wh_receive_details WHERE item_id=ds.item_id) as price, 
             
            ");

            $builder->join("wh_items", "wh_items.id=ds.item_id", "left");
            $builder->join("list_data", "list_data.id=wh_items.unit_id", "left");
            $builder->join("wh_sale_store dw", "dw.id=ds.sub_store_id", "left");
            
            $builder->where("ds.sub_store_id", $store_id);
            
            $builder->where("ds.stock <= 0");
            if(session('branchId') >0 ){
                $builder->where("dw.branch_id", session('branchId'));
            }
            $query2 = $builder->get()->getResult();        
            //echo get_last_query();exit;
            if( !empty($query1) ){
                return array_merge($query1, $query2);
            } else {
                return $query2;
            }
        } if( !empty($query1) ){
            return $query1;
        } else {
            return array();
        }
    }

    /*--------------------------
    | Get all low stock item
    *--------------------------*/
    public function bdtaskt1m17_15_getExpiredItem($store_id, $store_id){
        if(!empty($store_id)){
            $builder = $this->db->table('wh_receive_details ds');
            $builder->select("ds.avail_qty,ds.price,ds.batch_no,ds.expiry_date,wh_items.box_qty,wh_items.carton_qty,wh_items.$this->langColumn as item_name, wh_items.company_code, list_data.$this->langColumn as unit_name
             
            ");

            $builder->join("wh_items", "wh_items.id=ds.item_id", "left");
            $builder->join("list_data", "list_data.id=wh_items.unit_id", "left");
            $builder->join("wh_receive", "wh_receive.id=ds.receive_id", "left");
            $builder->join("wh_production_store dw", "dw.id=wh_receive.store_id", "left");
            
            $builder->where("wh_receive.store_id", $store_id);
            
            $builder->where("ds.expiry_date != '0000-00-00' ");
            $builder->where("ds.expiry_date <= ", date('Y-m-d'));
            if(session('branchId') >0 ){
                $builder->where("dw.branch_id", session('branchId'));
            }
            $query1 = $builder->get()->getResult();        
            //echo get_last_query();exit;
        }
        if(!empty($store_id)){
            $builder = $this->db->table('wh_item_request_details ds');
            $builder->select("ds.avail_qty,ds.price,ds.batch_no,ds.expiry_date,wh_items.box_qty,wh_items.carton_qty,wh_items.$this->langColumn as item_name, wh_items.company_code, list_data.$this->langColumn as unit_name
             
            ");

            $builder->join("wh_items", "wh_items.id=ds.item_id", "left");
            $builder->join("list_data", "list_data.id=wh_items.unit_id", "left");
            $builder->join("wh_item_requests", "wh_item_requests.id=ds.request_id", "left");
            $builder->join("wh_sale_store dw", "dw.id=wh_item_requests.sub_store_id", "left");
            
            $builder->where("wh_item_requests.sub_store_id", $store_id);
            
            $builder->where("ds.expiry_date != '0000-00-00' ");
            $builder->where("ds.expiry_date <= ", date('Y-m-d'));
            if(session('branchId') >0 ){
                $builder->where("dw.branch_id", session('branchId'));
            }
            $query2 = $builder->get()->getResult();        
            //echo get_last_query();exit;
            if( !empty($query1) ){
                return array_merge($query1, $query2);
            } else {
                return $query2;
            }
        } 
        if( !empty($query1) ){
            return $query1;
        } else {
            return array();
        }
    }

    /*--------------------------
    | Get all low stock item
    *--------------------------*/
    public function bdtaskt1m17_16_getItemCloseExpiry($store_id, $store_id, $supplier_id, $period){
        $branch = '';
        if(session('branchId') >0 ){
            $branch = ' AND w.branch_id= '.session('branchId');
        }
        if(!empty($store_id)){
            $builder = $this->db->table('wh_receive_details ds');
            $builder->select("ds.avail_qty,ds.price,ds.batch_no,ds.expiry_date,wh_items.box_qty,wh_items.carton_qty,wh_items.$this->langColumn as item_name, wh_items.company_code, list_data.$this->langColumn as unit_name,        
            (
                SELECT SUM(s.aqty-s.return_qty) as used FROM wh_order_details s
                LEFT OUTER JOIN wh_order r ON (r.id=s.request_id)
                LEFT OUTER JOIN wh_sale_store w ON (w.id=r.sub_store_id)
                WHERE DATEDIFF(CURDATE(), r.approved_date) <=30 AND r.status=1 AND r.isApproved=1 AND r.isCollected=1 AND s.item_id=ds.item_id ".$branch."
            ) as used_30,
             sup.$this->langColumn as supplier_name             
            ");

            $builder->join("wh_items", "wh_items.id=ds.item_id", "left");
            $builder->join("list_data", "list_data.id=wh_items.unit_id", "left");
            $builder->join("wh_receive", "wh_receive.id=ds.receive_id", "left");
            $builder->join("wh_production_store dw", "dw.id=wh_receive.store_id", "left");
            $builder->join("wh_supplier_information sup", "sup.id=wh_receive.supplier_id", "left");
            
            $builder->where("wh_receive.store_id", $store_id);

            if(!empty($supplier_id)){
                $builder->where("wh_receive.supplier_id", $supplier_id);
            }
            
            $builder->where("ds.avail_qty > ", 0);
            $builder->where("ds.expiry_date != '0000-00-00' ");
            $builder->where("ds.expiry_date > ", date('Y-m-d'));

            if(!empty($period)){
                $builder->where("DATEDIFF(ds.expiry_date , CURDATE()) <= ".$period);
            }
            if(session('branchId') >0 ){
                $builder->where("dw.branch_id", session('branchId'));
            }
            $query1 = $builder->get()->getResult();        
            //echo get_last_query();exit;
        }
        if(!empty($store_id)){
            $builder = $this->db->table('wh_item_request_details ds');
            $builder->select("ds.avail_qty,ds.price,ds.batch_no,ds.expiry_date,wh_items.box_qty,wh_items.carton_qty,wh_items.$this->langColumn as item_name, wh_items.company_code, list_data.$this->langColumn as unit_name,
            (
                SELECT SUM(s.aqty-s.return_qty) as used FROM wh_order_details s
                LEFT OUTER JOIN wh_order r ON (r.id=s.request_id)
                LEFT OUTER JOIN wh_sale_store w ON (w.id=r.sub_store_id)
                WHERE DATEDIFF(CURDATE(), r.approved_date) <=30 AND r.status=1 AND r.isApproved=1 AND r.isCollected=1 AND s.item_id=ds.item_id ".$branch."
            ) as used_30,
             sup.$this->langColumn as supplier_name
            ");

            $builder->join("wh_items", "wh_items.id=ds.item_id", "left");
            $builder->join("list_data", "list_data.id=wh_items.unit_id", "left");
            $builder->join("wh_item_requests", "wh_item_requests.id=ds.request_id", "left");
            $builder->join("wh_sale_store dw", "dw.id=wh_item_requests.sub_store_id", "left");
            $builder->join("wh_receive_details", "wh_receive_details.id=ds.receive_details_id", "left");
            $builder->join("wh_receive", "wh_receive.id=wh_receive_details.receive_id", "left");
            $builder->join("wh_supplier_information sup", "sup.id=wh_receive.supplier_id", "left");
            
            $builder->where("wh_item_requests.sub_store_id", $store_id);
            
            if(!empty($supplier_id)){
                $builder->where("wh_receive.supplier_id", $supplier_id);
            }

            $builder->where("ds.avail_qty > ", 0);
            $builder->where("ds.expiry_date != '0000-00-00' ");
            $builder->where("ds.expiry_date > ", date('Y-m-d'));
            
            if(!empty($period)){
                $builder->where("DATEDIFF(ds.expiry_date , CURDATE()) <= ".$period);
            }
            if(session('branchId') >0 ){
                $builder->where("dw.branch_id", session('branchId'));
            }
            $query2 = $builder->get()->getResult();        
            //echo get_last_query();exit;
            if( !empty($query1) ){
                return array_merge($query1, $query2);
            } else {
                return $query2;
            }
        } 
        if( !empty($query1) ){
            return $query1;
        } else {
            return array();
        }
    }

    /*--------------------------
    | Get all supplier payment
    *--------------------------*/
    public function bdtaskt1m17_17_getSupplierAging($supplier_id, $from=null, $to=null){
        $builder = $this->db->table('wh_receive c');
        $builder->select("c.*, SUM(r.paid_amount) as paid_amount,DATEDIFF(CURDATE(), c.date) as aging,s.$this->langColumn as supplier_name");
        $builder->join("wh_production_store dw", "dw.id=c.store_id", "left");
        $builder->join("wh_supplier_information s", "s.id=c.supplier_id", "left");
        $builder->join("wh_supplier_payment r", "r.receive_id=c.id", "left");
        if(!empty($supplier_id)){
            $builder->where("c.supplier_id", $supplier_id);
        }
        if(session('branchId') >0 ){
            $builder->where("dw.branch_id", session('branchId'));
        }
        $builder->where("c.due >", 0);
        $builder->where("c.date >=", $from);
        $builder->where("c.date <=", $to);
        $builder->groupBy("c.id");
        $query1 = $builder->get()->getResult();        

        return $query1;
    }
    /*--------------------------
    | Get all item consumption
    *--------------------------*/
    public function bdtaskt1m17_18_getItemConsumption_vs($doctor_id, $service_id, $status, $from=null, $to=null, $pageNumber, $page_size){
        $offset = ($pageNumber-1) * $page_size;

        $builder = $this->db->table('wh_order r');
        $builder->select("r.id,r.consumed_by, e.$this->langColumn as doctor_name, r.invoice_id, s.code_no, s.nameE as service_name,f.file_no, p.nameE as patient_name, r.voucher_no, ROUND(SUM((rd.aqty-rd.return_qty)*rd.price),2) as act_consumed, 
            (
                SELECT ROUND(SUM(a.quantity*a.price), 2) 
                FROM service_item a 
                WHERE a.service_id=r.service_id 
                GROUP BY a.service_id 
            ) as def_consumed
            ");
        $builder->join("wh_order_details rd", "rd.request_id=r.id", "left");
        $builder->join("wh_sale_store dw", "dw.id=r.sub_store_id", "left");
        //$builder->join("department d", "d.id=r.from_department_id", "left");
        $builder->join("patient_file f", "f.patient_id=r.patient_id", "left");
        $builder->join("patient p", "p.patient_id=r.patient_id", "left");
        $builder->join("employees e", "e.emp_id=r.doctor_id", "left");
        //$builder->join("wh_items i", "i.id=rd.item_id", "left");
        //$builder->join("list_data u", "u.id=i.unit_id", "left");
        $builder->join("services s", "s.id=r.service_id", "left");
       
        if(!empty($doctor_id)){
            $builder->whereIn("r.doctor_id", $doctor_id);
        }
        if(!empty($service_id)){
            $builder->whereIn("r.service_id", $service_id);
        }
        if(session('branchId') >0 ){
            $builder->where("dw.branch_id", session('branchId'));
        }
        $builder->where("r.isApproved", 1);
        $builder->where("r.isCollected", 1);

        if(!empty($from)){
            $builder->where("r.approved_date >=", $from);
        }        
        if(!empty($to)){
            $builder->where("r.approved_date <=", $to);
        }
        $builder->groupBy("r.id");
        $builder->orderBy("r.approved_date", "DESC");

        $num_rows = $builder->countAllResults(false);   
        
        $builder->limit($page_size, $offset);
        $query1 = $builder->get()->getResult();        
        //echo get_last_query();exit;
        //return $query1;

        $num_rows = $builder->countAllResults();   

        return array('data' => $query1, 'num_rows'=>$num_rows );
    }

    public function bdtaskt1m17_19_getServiceListByDoctorId($doctor_id){
        $builder = $this->db->table('doctor_services r');
        $builder->select("rd.id, rd.code_no as service_code, rd.nameE as service_name");
        $builder->join("services rd", "rd.id=r.service_id", "left");
        if(!empty($doctor_id)){
            $builder->whereIn("r.doctor_id", $doctor_id);
        }
        $builder->where("rd.branch_id", session('branchId'));
        $builder->where("rd.status", 1);
        $query1 = $builder->get()->getResult();        

        return $query1;
    }

    /*--------------------------
    | Get all item stock
    *--------------------------*/
    public function bdtaskt1m17_20_getItemStockHistory($store, $sub_store, $item_id, $pageNumber, $page_size){
        $offset = ($pageNumber-1) * $page_size;

        $builder = $this->db->table('wh_items ds');
        $builder->select("bs.stock, dw.*, list_data.$this->langColumn as unit_name,  
        ");

        $builder->join("list_data", "list_data.id=ds.unit_id", "left");
        if(!empty($store)){
            $builder->join("wh_production_stock bs", "bs.store_id=$store AND bs.item_id=$item_id", "left");
            $builder->join("(SELECT 'out' as trans_type, '' as receive_from,d.nameE as transfer_to, r.collected_date as transfer_date, ABS(s.aqty-s.return_qty) as quantity, s.item_id,r.voucher_no,s.batch_no, s.receive_avail_qty as ref_qty, 
                                IF(s.receive_type='rec',
                                (SELECT b.voucher_no FROM wh_receive_details a
                                LEFT OUTER JOIN wh_receive b ON(a.receive_id=b.id)
                                WHERE a.id=s.receive_details_id),
                                (SELECT b.voucher_no FROM wh_stock_transfer_details a
                                LEFT OUTER JOIN wh_stock_transfer b ON(a.request_id=b.id)
                                WHERE a.id=s.receive_details_id)
                            ) as ref_no FROM wh_item_request_details s
                            LEFT OUTER JOIN wh_item_requests r ON (r.id=s.request_id)
                            LEFT OUTER JOIN wh_sale_store d ON (d.id=r.sub_store_id)
                            WHERE r.status=1 AND r.isApproved=1 AND r.isCollected=1 AND r.main_store_id=$store AND s.item_id=$item_id

                            UNION ALL 

                            SELECT 'in' as trans_type, d.nameE as receive_from,'' as transfer_to, r.approved_date as transfer_date, ABS(s.qty-s.return_qty) as quantity, s.item_id,r.voucher_no,s.batch_no, s.qty as ref_qty, r.voucher_no as ref_no FROM wh_receive_details s
                            LEFT OUTER JOIN wh_receive r ON (r.id=s.receive_id)
                            LEFT OUTER JOIN wh_supplier_information d ON (d.id=r.supplier_id)
                            WHERE r.status=1 AND r.isApproved=1 AND r.store_id=$store AND s.item_id=$item_id

                            UNION ALL 

                            SELECT 'in' as trans_type, d.nameE as receive_from,'' as transfer_to, r.collected_date as transfer_date, ABS(s.aqty-s.return_qty) as quantity, s.item_id,r.voucher_no,s.batch_no, s.receive_details_qty as ref_qty, 
                                IF(s.receive_type='rec',
                                (SELECT b.voucher_no FROM wh_receive_details a
                                LEFT OUTER JOIN wh_receive b ON(a.receive_id=b.id)
                                WHERE a.id=s.receive_details_id),
                                (SELECT b.voucher_no FROM wh_stock_transfer_details a
                                LEFT OUTER JOIN wh_stock_transfer b ON(a.request_id=b.id)
                                WHERE a.id=s.receive_details_id)
                            ) as ref_no FROM wh_stock_transfer_details s
                            LEFT OUTER JOIN wh_stock_transfer r ON (r.id=s.request_id)
                            LEFT OUTER JOIN wh_production_store d ON (d.id=r.to_store_id)
                            WHERE r.status=1 AND r.isApproved=1 AND r.isCollected=1 AND r.from_store_id=$store AND s.item_id=$item_id

                            UNION ALL 

                            SELECT 'out' as trans_type, '' as receive_from, d.nameE as transfer_to, r.collected_date as transfer_date, ABS(s.aqty-s.return_qty) as quantity, s.item_id,r.voucher_no,s.batch_no, s.receive_details_qty as ref_qty, 
                                IF(s.receive_type='rec',
                                (SELECT b.voucher_no FROM wh_receive_details a
                                LEFT OUTER JOIN wh_receive b ON(a.receive_id=b.id)
                                WHERE a.id=s.receive_details_id),
                                (SELECT b.voucher_no FROM wh_stock_transfer_details a
                                LEFT OUTER JOIN wh_stock_transfer b ON(a.request_id=b.id)
                                WHERE a.id=s.receive_details_id)
                            ) as ref_no FROM wh_stock_transfer_details s
                            LEFT OUTER JOIN wh_stock_transfer r ON (r.id=s.request_id)
                            LEFT OUTER JOIN wh_production_store d ON (d.id=r.from_store_id)
                            WHERE r.status=1 AND r.isApproved=1 AND r.isCollected=1 AND r.to_store_id=$store AND s.item_id=$item_id
                        ) dw", "dw.item_id=ds.id", "left");
        }
        if(!empty($sub_store)){
            $builder->join("wh_sale_stock bs", "bs.sub_store_id=$sub_store AND bs.item_id=$item_id", "left");
            $builder->join("(SELECT 'out' as trans_type, '' as receive_from,d.name as transfer_to, r.collected_date as transfer_date, ABS(s.aqty-s.return_qty) as quantity, s.item_id,r.voucher_no,s.batch_no, s.request_details_qty as ref_qty, 
                                IF(s.request_type='req',
                                (SELECT b.voucher_no FROM wh_item_request_details a
                                LEFT OUTER JOIN wh_item_requests b ON(a.request_id=b.id)
                                WHERE a.id=s.request_details_id),
                                (SELECT b.voucher_no FROM wh_item_transfer_details a
                                LEFT OUTER JOIN wh_item_transfer b ON(a.request_id=b.id)
                                WHERE a.id=s.request_details_id)
                            ) as ref_no FROM wh_order_details s
                            LEFT OUTER JOIN wh_order r ON (r.id=s.request_id)
                            LEFT OUTER JOIN hrm_departments d ON (d.id=r.from_department_id)
                            WHERE r.status=1 AND r.isApproved=1 AND r.isCollected=1 AND r.sub_store_id=$sub_store AND s.item_id=$item_id

                            UNION ALL 

                            SELECT 'in' as trans_type, d.name as receive_from,'' as transfer_to, r.collected_date as transfer_date, ABS(s.aqty-s.return_qty) as quantity, s.item_id,r.voucher_no,s.batch_no, s.receive_avail_qty as ref_qty, 
                                IF(s.receive_type='rec',
                                (SELECT b.voucher_no FROM wh_receive_details a
                                LEFT OUTER JOIN wh_receive b ON(a.receive_id=b.id)
                                WHERE a.id=s.receive_details_id),
                                (SELECT b.voucher_no FROM wh_stock_transfer_details a
                                LEFT OUTER JOIN wh_stock_transfer b ON(a.request_id=b.id)
                                WHERE a.id=s.receive_details_id)
                            ) as ref_no FROM wh_item_request_details s
                            LEFT OUTER JOIN wh_item_requests r ON (r.id=s.request_id)
                            LEFT OUTER JOIN wh_production_store d ON (d.id=r.main_store_id)
                            WHERE r.status=1 AND r.isApproved=1 AND r.isCollected=1 AND r.sub_store_id=$sub_store AND s.item_id=$item_id

                            UNION ALL 

                            SELECT 'in' as trans_type, d.nameE as receive_from,'' as transfer_to, r.collected_date as transfer_date, ABS(s.aqty-s.return_qty) as quantity, s.item_id,r.voucher_no,s.batch_no, s.request_details_qty as ref_qty, 
                                IF(s.request_type='req',
                                (SELECT b.voucher_no FROM wh_item_request_details a
                                LEFT OUTER JOIN wh_item_requests b ON(a.request_id=b.id)
                                WHERE a.id=s.request_details_id),
                                (SELECT b.voucher_no FROM wh_item_transfer_details a
                                LEFT OUTER JOIN wh_item_transfer b ON(a.request_id=b.id)
                                WHERE a.id=s.request_details_id)
                            ) as ref_no FROM wh_item_transfer_details s
                            LEFT OUTER JOIN wh_item_transfer r ON (r.id=s.request_id)
                            LEFT OUTER JOIN wh_sale_store d ON (d.id=r.to_store_id)
                            WHERE r.status=1 AND r.isApproved=1 AND r.isCollected=1 AND r.from_store_id=$sub_store AND s.item_id=$item_id

                            UNION ALL 

                            SELECT 'out' as trans_type, '' as receive_from, d.nameE as transfer_to, r.collected_date as transfer_date, ABS(s.aqty-s.return_qty) as quantity, s.item_id,r.voucher_no,s.batch_no, s.request_details_qty as ref_qty, 
                                IF(s.request_type='req',
                                (SELECT b.voucher_no FROM wh_item_request_details a
                                LEFT OUTER JOIN wh_item_requests b ON(a.request_id=b.id)
                                WHERE a.id=s.request_details_id),
                                (SELECT b.voucher_no FROM wh_item_transfer_details a
                                LEFT OUTER JOIN wh_item_transfer b ON(a.request_id=b.id)
                                WHERE a.id=s.request_details_id)
                            ) as ref_no FROM wh_item_transfer_details s
                            LEFT OUTER JOIN wh_item_transfer r ON (r.id=s.request_id)
                            LEFT OUTER JOIN wh_sale_store d ON (d.id=r.from_store_id)
                            WHERE r.status=1 AND r.isApproved=1 AND r.isCollected=1 AND r.to_store_id=$sub_store AND s.item_id=$item_id
                        ) dw", "dw.item_id=ds.id", "left");
        }
        if(!empty($item_id)){
            $builder->where("ds.id", $item_id);
        } 
        $builder->orderBy("dw.transfer_date DESC");

        $builder->limit($page_size, $offset);
        //$builder->where("ds.invoice_date >=", $from);
        //$builder->where("ds.invoice_date <=", $to);
        $num_rows = $builder->countAllResults(false);   

        $query1 = $builder->get()->getResult();   

        return array('data' => $query1, 'num_rows'=>$num_rows );
    }
    /*--------------------------
    | Get all supplier payment
    *--------------------------*/
    public function bdtaskt1m17_21_getSupplierBalance($supplier_id, $from=null, $to=null){
        $where = '';
        $where2 = '';
        if(session('branchId') >0 ){
            $where = " AND b.branch_id = ".session('branchId');
            $where2 = " AND a.BranchID = ".session('branchId');
        }
        $builder = $this->db->table('wh_supplier_information s');
        $builder->select("r.*,c.*, s.acc_head, s.$this->langColumn as supplier_name, t.stock_amount,j.jv_amount");
        $builder->join("(
                        SELECT a.supplier_id, SUM(a.grand_total) as purchase_total, SUM(a.due) as due_total, SUM(a.receipt) as paid_total 
                        FROM wh_receive a
                        LEFT OUTER JOIN wh_production_store b ON(a.store_id = b.id) 
                        WHERE a.isApproved=1 AND a.status=1 ".$where."
                        GROUP BY a.supplier_id
                    ) r", "r.supplier_id=s.id", "left");
        $builder->join("(
                        SELECT p.supplier_id, SUM(p.paid_amount) as credit_paid_total 
                        FROM wh_supplier_payment p 
                        LEFT OUTER JOIN wh_receive a ON(a.id = p.receive_id) 
                        LEFT OUTER JOIN wh_production_store b ON(a.store_id = b.id) 
                        WHERE a.isApproved=1 AND p.status=1 ".$where."
                        GROUP BY p.supplier_id
                    ) c", "s.id=c.supplier_id", "left");
        $builder->join("(
                        SELECT a.supplier_id,SUM(((p.avail_qty+p.adjust_out)-(p.return_qty+p.adjust_in))*p.price) as stock_amount 
                        FROM wh_receive_details p 
                        LEFT OUTER JOIN wh_receive a ON(a.id = p.receive_id) 
                        LEFT OUTER JOIN wh_production_store b ON(a.store_id = b.id) 
                        WHERE a.isApproved=1 AND a.status=1 ".$where."
                        GROUP BY a.supplier_id
                    ) t", "s.id=t.supplier_id", "left");
        $builder->join("(
                        SELECT a.COAID,SUM(a.Credit) as jv_amount 
                        FROM acc_transaction a
                        WHERE a.IsAppove=1 AND a.Credit >0 ".$where2." AND a.COAID LIKE '2211%' AND a.Vtype NOT IN('SUPI','GRECI','GRETI')
                        GROUP BY a.COAID
                    ) j", "s.acc_head=j.COAID", "left");
        
        if(!empty($supplier_id)){
            $builder->where("s.id", $supplier_id);
        }
        //$builder->where("c.paid_status", 1);
        //$builder->where("c.paid_date >=", $from);
        //$builder->where("c.paid_date <=", $to);
        //$builder->orderBy("c.paid_date", "DESC");
        $query1 = $builder->get()->getResult();        

        return $query1;
    }

}
?>