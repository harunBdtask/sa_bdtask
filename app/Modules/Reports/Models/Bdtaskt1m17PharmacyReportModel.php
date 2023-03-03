<?php namespace App\Modules\Reports\Models;
use CodeIgniter\Model;

class Bdtaskt1m17PharmacyReportModel extends Model
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
        $builder = $this->db->table('ph_main_stock ds');
        $builder->select("ds.*,ph_items.box_qty,ph_items.carton_qty,ph_items.$this->langColumn as item_name, ph_items.company_code, list_data.$this->langColumn as unit_name, (SELECT AVG(price) FROM ph_receive_details WHERE item_id=ds.item_id) as price, 
            (
                SELECT SUM(td.qty) FROM ph_receive_details td
                LEFT OUTER JOIN ph_receive t ON (t.id=td.receive_id)
                WHERE td.item_id=ds.item_id and t.isApproved=1
            ) as in_qty, 
            (
                SELECT SUM(td.aqty) FROM ph_item_request_details td
                LEFT OUTER JOIN ph_item_requests t ON (t.id=td.request_id)
                WHERE td.item_id=ds.item_id and t.isApproved=1
            ) as out_qty, 
            (
                SELECT SUM(td.qty) FROM ph_return_details td
                LEFT OUTER JOIN ph_return t ON (t.id=td.return_id)
                WHERE td.item_id=ds.item_id
            ) as return_qty
        ");

        $builder->join("ph_items", "ph_items.id=ds.item_id", "left");
        $builder->join("list_data", "list_data.id=ph_items.unit_id", "left");
        $builder->join("ph_main_store dw", "dw.id=ds.store_id", "left");
        if(!empty($store_id)){
            $builder->where("ds.store_id", $store_id);
        }
        if(!empty($item_id)){
            $builder->where("ds.item_id", $item_id);
        }
        if(session('branchId') >0 ){
            $builder->where("dw.branch_id", session('branchId'));
        }
        $query1 = $builder->get()->getResult();        

        return $query1;
    }

    /*--------------------------
    | Get all item transfer to sub stock
    *--------------------------*/
    public function bdtaskt1m17_02_getItemSubReceive($sub_store_id, $item_id, $from=null, $to=null){
        $builder = $this->db->table('ph_item_requests r');
        $builder->select("r.*,rd.aqty,i.$this->langColumn as item_name,u.$this->langColumn as unit_name, i.company_code,(SELECT AVG(price) FROM ph_receive_details WHERE item_id=rd.item_id) as price");
        $builder->join("ph_item_request_details rd", "rd.request_id=r.id", "left");
        $builder->join("ph_sub_store w", "w.id=r.sub_store_id", "left");
        $builder->join("ph_items i", "i.id=rd.item_id", "left");
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
    public function bdtaskt1m17_03_getItemConsumption($sub_store_id, $item_id, $from=null, $to=null){
        $builder = $this->db->table('pharmacy_item_requests r');
        $builder->select("r.*,rd.aqty,i.$this->langColumn as item_name,u.$this->langColumn as unit_name, i.company_code,rd.price,d.$this->langColumn as dept_name");
        $builder->join("pharmacy_item_request_details rd", "rd.request_id=r.id", "left");
        $builder->join("ph_sub_store w", "w.id=r.sub_store_id", "left");
        $builder->join("department d", "d.id=r.from_department_id", "left");
        $builder->join("ph_items i", "i.id=rd.item_id", "left");
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
    | Get all item sub stock
    *--------------------------*/
    public function bdtaskt1m17_04_getItemSubStock($sub_store_id, $item_id){
        $builder = $this->db->table('ph_sub_stock ds');
        $builder->select("ds.*,ph_items.box_qty,ph_items.carton_qty,ph_items.$this->langColumn as item_name, ph_items.company_code,list_data.$this->langColumn as unit_name,
            (
                SELECT AVG(td.price) FROM ph_receive_details td
                LEFT OUTER JOIN ph_receive t ON (t.id=td.receive_id)
                WHERE td.item_id=ds.item_id and t.isApproved=1
            ) as price, 
            (
                SELECT SUM(td.aqty) FROM ph_item_request_details td
                LEFT OUTER JOIN ph_item_requests t ON (t.id=td.request_id)
                WHERE td.item_id=ds.item_id and t.isApproved=1
            ) as in_qty, 
            (
                SELECT SUM(td.aqty) FROM ph_item_transfer_details td
                LEFT OUTER JOIN ph_item_transfer t ON (t.id=td.request_id)
                WHERE td.item_id=ds.item_id and t.from_store_id=".$sub_store_id." and t.isApproved=1
            ) as rec_qty, 
            (
                SELECT SUM(td.aqty) FROM ph_item_transfer_details td
                LEFT OUTER JOIN ph_item_transfer t ON (t.id=td.request_id)
                WHERE td.item_id=ds.item_id and t.to_store_id=".$sub_store_id." and t.isApproved=1
            ) as trans_qty
        ");
        $builder->join("ph_items", "ph_items.id=ds.item_id", "left");
        $builder->join("list_data", "list_data.id=ph_items.unit_id", "left");
        $builder->join("ph_sub_store dw", "dw.id=ds.sub_store_id", "left");
        if(!empty($sub_store_id)){
            $builder->where("ds.sub_store_id", $sub_store_id);
        }
        if(!empty($item_id)){
            $builder->where("ds.item_id", $item_id);
        }
        if(session('branchId') >0 ){
            $builder->where("dw.branch_id", session('branchId'));
        }
        $query1 = $builder->get()->getResult();        

        return $query1;
    }


    /*--------------------------
    | Get all item receive
    *--------------------------*/
    public function bdtaskt1m17_05_getItemReceive($store_id, $supplier_id, $item_id, $from=null, $to=null){
        $builder = $this->db->table('ph_receive r');
        $builder->select("r.*,rd.qty,rd.carton,rd.box,rd.box_qty,rd.price,i.$this->langColumn as item_name,u.$this->langColumn as unit_name, i.company_code,s.$this->langColumn as supplier_name");
        $builder->join("ph_receive_details rd", "rd.receive_id=r.id", "left");
        $builder->join("ph_main_store w", "w.id=r.store_id", "left");
        $builder->join("ph_supplier_information s", "s.id=r.supplier_id", "left");
        $builder->join("ph_items i", "i.id=rd.item_id", "left");
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
        $builder = $this->db->table('ph_item_transfer r');
        $builder->select("r.*,rd.aqty,i.$this->langColumn as item_name,u.$this->langColumn as unit_name,w.$this->langColumn as store_name, i.company_code,(SELECT AVG(price) FROM ph_receive_details WHERE item_id=rd.item_id) as price");
        $builder->join("ph_item_transfer_details rd", "rd.request_id=r.id", "left");
        $builder->join("ph_sub_store w", "w.id=r.from_store_id", "left");
        $builder->join("ph_items i", "i.id=rd.item_id", "left");
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
        $builder = $this->db->table('ph_supplier_payment c');
        $builder->select("c.*, s.$this->langColumn as supplier_name, r.purchase_id");
        $builder->join("ph_supplier_information s", "s.id=c.supplier_id", "left");
        $builder->join("ph_receive r", "r.id=c.receive_id", "left");
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
        $builder = $this->db->table('ph_receive r');
        $builder->select("r.*,rd.qty,rd.carton,rd.box,rd.box_qty,rd.price,i.$this->langColumn as item_name,u.$this->langColumn as unit_name, i.company_code,s.$this->langColumn as supplier_name, 'in' as type");
        $builder->join("ph_receive_details rd", "rd.receive_id=r.id", "left");
        $builder->join("ph_main_store w", "w.id=r.store_id", "left");
        $builder->join("ph_supplier_information s", "s.id=r.supplier_id", "left");
        $builder->join("ph_items i", "i.id=rd.item_id", "left");
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
        //$builder->where("r.approved_date >=", $from);
        //$builder->where("r.approved_date <=", $to);
        $query1 = $builder->get()->getResult();        

        $builder = $this->db->table('ph_item_requests r');
        $builder->select("r.*,rd.aqty as qty,rd.carton,rd.box,rd.box_qty,(SELECT AVG(price) FROM ph_receive_details WHERE item_id=rd.item_id) as price,i.$this->langColumn as item_name,u.$this->langColumn as unit_name, i.company_code,s.$this->langColumn as supplier_name, 'out' as type");
        $builder->join("ph_item_request_details rd", "rd.request_id=r.id", "left");
        $builder->join("ph_main_store w", "w.id=r.main_store_id", "left");
        $builder->join("ph_sub_store s", "s.id=r.sub_store_id", "left");
        $builder->join("ph_items i", "i.id=rd.item_id", "left");
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
        //$builder->where("r.approved_date >=", $from);
        //$builder->where("r.approved_date <=", $to);
        $query2 = $builder->get()->getResult();    

        return array_merge($query1, $query2);
    }
    
    /*--------------------------
    | Get item receive all
    *--------------------------*/
    public function bdtaskt1m17_09_getSubStoreItemReceiveDetails($item_id, $store_id){
        $builder = $this->db->table('ph_item_requests r');
        $builder->select("r.approved_date,rd.acarton,rd.carton_qty,rd.abox,rd.box_qty,rd.qty,rd.aqty,i.$this->langColumn as item_name,u.$this->langColumn as unit_name, i.company_code,s.$this->langColumn as store_name, 'in' as type");
        $builder->join("ph_item_request_details rd", "rd.request_id=r.id", "left");
        $builder->join("ph_sub_store w", "w.id=r.sub_store_id", "left");
        $builder->join("ph_main_store s", "s.id=r.main_store_id", "left");
        $builder->join("ph_items i", "i.id=rd.item_id", "left");
        $builder->join("list_data u", "u.id=i.unit_id", "left");
        if(!empty($item_id)){
            $builder->where("rd.item_id", $item_id);
        }
        if(!empty($store_id)){
            $builder->where("r.sub_store_id", $store_id);
        }
        if(session('branchId') >0 ){
            $builder->where("w.branch_id", session('branchId'));
        }
        $builder->where("r.isApproved", 1);
        //$builder->where("r.approved_date >=", $from);
        //$builder->where("r.approved_date <=", $to);
        $query1 = $builder->get()->getResult();       

        $builder = $this->db->table('ph_item_transfer r');
        $builder->select("r.approved_date,rd.acarton,rd.carton_qty,rd.abox,rd.box_qty,rd.qty,rd.aqty,i.$this->langColumn as item_name,u.$this->langColumn as unit_name, i.company_code,s.$this->langColumn as store_name, 'in' as type");
        $builder->join("ph_item_transfer_details rd", "rd.request_id=r.id", "left");
        $builder->join("ph_sub_store w", "w.id=r.from_store_id", "left");
        $builder->join("ph_sub_store s", "s.id=r.to_store_id", "left");
        $builder->join("ph_items i", "i.id=rd.item_id", "left");
        $builder->join("list_data u", "u.id=i.unit_id", "left");
        if(!empty($item_id)){
            $builder->where("rd.item_id", $item_id);
        }
        if(!empty($store_id)){
            $builder->where("r.from_store_id", $store_id);
        }
        $builder->where("r.isApproved", 1);
        
        if(session('branchId') >0 ){
            $builder->where("w.branch_id", session('branchId'));
        }
        $query2 = $builder->get()->getResult();     

        $builder = $this->db->table('ph_item_transfer r');
        $builder->select("r.approved_date,rd.acarton,rd.carton_qty,rd.abox,rd.box_qty,rd.qty,rd.aqty,i.$this->langColumn as item_name,u.$this->langColumn as unit_name, i.company_code,s.$this->langColumn as store_name, 'out' as type");
        $builder->join("ph_item_transfer_details rd", "rd.request_id=r.id", "left");
        $builder->join("ph_sub_store w", "w.id=r.from_store_id", "left");
        $builder->join("ph_sub_store s", "s.id=r.to_store_id", "left");
        $builder->join("ph_items i", "i.id=rd.item_id", "left");
        $builder->join("list_data u", "u.id=i.unit_id", "left");
        if(!empty($item_id)){
            $builder->where("rd.item_id", $item_id);
        }
        if(!empty($store_id)){
            $builder->where("r.to_store_id", $store_id);
        }
        $builder->where("r.isApproved", 1);
        
        if(session('branchId') >0 ){
            $builder->where("w.branch_id", session('branchId'));
        }
        $query3 = $builder->get()->getResult();    

        return array_merge($query1, $query2, $query3);
    }

    /*--------------------------
    | Get all item receive
    *--------------------------*/
    public function bdtaskt1m17_10_getSupplierReturn($store_id, $supplier_id, $item_id, $from=null, $to=null){
        $builder = $this->db->table('ph_return r');
        $builder->select("r.*,rd.qty,rd.carton,rd.box,rd.box_qty,rd.price,i.$this->langColumn as item_name,u.$this->langColumn as unit_name, i.company_code,s.$this->langColumn as supplier_name");
        $builder->join("ph_return_details rd", "rd.return_id=r.id", "left");
        $builder->join("ph_main_store w", "w.id=r.store_id", "left");
        $builder->join("ph_supplier_information s", "s.id=r.supplier_id", "left");
        $builder->join("ph_items i", "i.id=rd.item_id", "left");
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
        $builder = $this->db->table('pharmacy_item_requests r');
        $builder->select("r.*,rd.return_qty,i.$this->langColumn as item_name,u.$this->langColumn as unit_name, i.company_code,rd.price,d.$this->langColumn as dept_name");
        $builder->join("pharmacy_item_request_details rd", "rd.request_id=r.id", "left");
        $builder->join("ph_sub_store w", "w.id=r.sub_store_id", "left");
        $builder->join("department d", "d.id=r.from_department_id", "left");
        $builder->join("ph_items i", "i.id=rd.item_id", "left");
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
    | Get all low stock item
    *--------------------------*/
    public function bdtaskt1m17_12_getLowStock($store_id, $store_id){
        if(!empty($store_id)){
            $builder = $this->db->table('ph_main_stock ds');
            $builder->select("ds.stock,ph_items.box_qty,ph_items.carton_qty,ph_items.$this->langColumn as item_name, ph_items.company_code, list_data.$this->langColumn as unit_name, (SELECT AVG(price) FROM ph_receive_details WHERE item_id=ds.item_id) as price, 
             
            ");

            $builder->join("ph_items", "ph_items.id=ds.item_id", "left");
            $builder->join("list_data", "list_data.id=ph_items.unit_id", "left");
            $builder->join("ph_main_store dw", "dw.id=ds.store_id", "left");
            
            $builder->where("ds.store_id", $store_id);
            
            $builder->where("ds.stock <= ph_items.alert_qty");
            if(session('branchId') >0 ){
                $builder->where("dw.branch_id", session('branchId'));
            }
            $query1 = $builder->get()->getResult();        
            //echo get_last_query();exit;
        }
        if(!empty($store_id)){
            $builder = $this->db->table('ph_sub_stock ds');
            $builder->select("ds.stock,ph_items.box_qty,ph_items.carton_qty,ph_items.$this->langColumn as item_name, ph_items.company_code, list_data.$this->langColumn as unit_name, (SELECT AVG(price) FROM ph_receive_details WHERE item_id=ds.item_id) as price, 
             
            ");

            $builder->join("ph_items", "ph_items.id=ds.item_id", "left");
            $builder->join("list_data", "list_data.id=ph_items.unit_id", "left");
            $builder->join("ph_sub_store dw", "dw.id=ds.sub_store_id", "left");
            
            $builder->where("ds.sub_store_id", $store_id);
            
            $builder->where("ds.stock <= ph_items.alert_qty");
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
    public function bdtaskt1m17_13_getOutofStock($store_id, $store_id){
        if(!empty($store_id)){
            $builder = $this->db->table('ph_main_stock ds');
            $builder->select("ds.stock,ph_items.box_qty,ph_items.carton_qty,ph_items.$this->langColumn as item_name, ph_items.company_code, list_data.$this->langColumn as unit_name, (SELECT AVG(price) FROM ph_receive_details WHERE item_id=ds.item_id) as price, 
             
            ");

            $builder->join("ph_items", "ph_items.id=ds.item_id", "left");
            $builder->join("list_data", "list_data.id=ph_items.unit_id", "left");
            $builder->join("ph_main_store dw", "dw.id=ds.store_id", "left");
            
            $builder->where("ds.store_id", $store_id);
            
            $builder->where("ds.stock <= 0");
            if(session('branchId') >0 ){
                $builder->where("dw.branch_id", session('branchId'));
            }
            $query1 = $builder->get()->getResult();        
            //echo get_last_query();exit;
        }
        if(!empty($store_id)){
            $builder = $this->db->table('ph_sub_stock ds');
            $builder->select("ds.stock,ph_items.box_qty,ph_items.carton_qty,ph_items.$this->langColumn as item_name, ph_items.company_code, list_data.$this->langColumn as unit_name, (SELECT AVG(price) FROM ph_receive_details WHERE item_id=ds.item_id) as price, 
             
            ");

            $builder->join("ph_items", "ph_items.id=ds.item_id", "left");
            $builder->join("list_data", "list_data.id=ph_items.unit_id", "left");
            $builder->join("ph_sub_store dw", "dw.id=ds.sub_store_id", "left");
            
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
        } 
        if( !empty($query1) ){
            return $query1;
        } else {
            return array();
        }
    }

    /*--------------------------
    | Get incentives
    *--------------------------*/
    public function bdtaskt1m17_14_getIncentive($supplier_id, $doctor_id, $category_id, $from, $to){
        $builder = $this->db->table('ph_sale_details ds');
        $builder->select("ds.*,SUM(ds.price) as total_price, ph_categories.nameE as cat_name,sp.nameE as supplier_name,em.nameE as doctor_name, 
            (CASE 
            WHEN ph_items.cat_id=3 AND SUM(ds.price) <= 10000 THEN 0.05
            WHEN ph_items.cat_id=3 AND SUM(ds.price) < 40000 THEN 0.1
            WHEN ph_items.cat_id=3 AND SUM(ds.price) >= 40000 THEN 0.2
            WHEN ph_items.cat_id <3 AND SUM(ds.price) < 40000 THEN 0.05
            WHEN ph_items.cat_id <3 AND  SUM(ds.price) >= 40000 THEN 0.1
            ELSE 0
            END) as incentive");

        $builder->join("ph_sale", "ph_sale.id=ds.sale_id", "left");
        $builder->join("ph_items", "ph_items.id=ds.item_id", "left");
        $builder->join("ph_categories", "ph_categories.id=ph_items.cat_id", "left");
        $builder->join("ph_sub_store dw", "dw.id=ph_sale.sub_store_id", "left");
        $builder->join("ph_item_request_details rd", "rd.id=ds.request_details_id", "left");
        $builder->join("ph_receive_details rc", "rc.id=rd.receive_details_id", "left");
        $builder->join("ph_purchases pr", "pr.id=rc.purchase_id", "left");
        $builder->join("ph_supplier_information sp", "sp.id=pr.supplier_id", "left");
        $builder->join("employees em", "em.emp_id=ph_sale.doctor_id", "left");
        if(!empty($supplier_id)){
            $builder->where("pr.supplier_id", $supplier_id);
        }
        if(!empty($doctor_id)){
            $builder->where("ph_sale.doctor_id", $doctor_id);
        }
        if(!empty($category_id)){
            $builder->where("ph_items.cat_id", $category_id);
        }
        $builder->where("ds.prescribed >", 0);
        $builder->where("ph_sale.prescription_id >", 0);
        $builder->where("dw.branch_id", session('branchId'));
        $builder->where("ph_sale.date >=", $from);
        $builder->where("ph_sale.date <=", $to);

        if(session('branchId') >0 ){
            $builder->where("ph_sale.branch_id", session('branchId'));
        }
        $builder->groupBy("ph_sale.doctor_id, pr.supplier_id, ph_items.cat_id");

        $query1 = $builder->get()->getResult();        
        //echo get_last_query();exit;
        return $query1;
    }

    /*--------------------------
    | Get all low stock item
    *--------------------------*/
    public function bdtaskt1m17_15_getExpiredItem($store_id, $store_id){
        if(!empty($store_id)){
            $builder = $this->db->table('ph_receive_details ds');
            $builder->select("ds.avail_qty,ds.price,ds.batch_no,ds.expiry_date,ph_items.box_qty,ph_items.carton_qty,ph_items.$this->langColumn as item_name, ph_items.company_code, list_data.$this->langColumn as unit_name
             
            ");

            $builder->join("ph_items", "ph_items.id=ds.item_id", "left");
            $builder->join("list_data", "list_data.id=ph_items.unit_id", "left");
            $builder->join("ph_receive", "ph_receive.id=ds.receive_id", "left");
            $builder->join("ph_main_store dw", "dw.id=ph_receive.store_id", "left");
            
            $builder->where("ph_receive.store_id", $store_id);
            
            $builder->where("ds.expiry_date != ", "0000-00-00");
            $builder->where("ds.expiry_date <= ", date('Y-m-d'));
            if(session('branchId') >0 ){
                $builder->where("dw.branch_id", session('branchId'));
            }
            $query1 = $builder->get()->getResult();        
            //echo get_last_query();exit;
        }
        if(!empty($store_id)){
            $builder = $this->db->table('ph_item_request_details ds');
            $builder->select("ds.avail_qty,ds.price,ds.batch_no,ds.expiry_date,ph_items.box_qty,ph_items.carton_qty,ph_items.$this->langColumn as item_name, ph_items.company_code, list_data.$this->langColumn as unit_name
             
            ");

            $builder->join("ph_items", "ph_items.id=ds.item_id", "left");
            $builder->join("list_data", "list_data.id=ph_items.unit_id", "left");
            $builder->join("ph_item_requests", "ph_item_requests.id=ds.request_id", "left");
            $builder->join("ph_sub_store dw", "dw.id=ph_item_requests.sub_store_id", "left");
            
            $builder->where("ph_item_requests.sub_store_id", $store_id);
            
            $builder->where("ds.expiry_date != ", "0000-00-00");
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
        if(!empty($store_id)){
            $builder = $this->db->table('ph_receive_details ds');
            $builder->select("ds.avail_qty,ds.price,ds.batch_no,ds.expiry_date,ph_items.box_qty,ph_items.carton_qty,ph_items.$this->langColumn as item_name, ph_items.company_code, list_data.$this->langColumn as unit_name, sup.$this->langColumn as supplier_name 
             
            ");

            $builder->join("ph_items", "ph_items.id=ds.item_id", "left");
            $builder->join("list_data", "list_data.id=ph_items.unit_id", "left");
            $builder->join("ph_receive", "ph_receive.id=ds.receive_id", "left");
            $builder->join("ph_supplier_information sup", "sup.id=ph_receive.supplier_id", "left");
            $builder->join("ph_main_store dw", "dw.id=ph_receive.store_id", "left");
            
            $builder->where("ph_receive.store_id", $store_id);

            if(!empty($supplier_id)){
                $builder->where("ph_receive.supplier_id", $supplier_id);
            }
            
            if(!empty($period)){
                $builder->where("DATEDIFF(ds.expiry_date , CURDATE()) <= ".$period);
            }
            
            $builder->where("ds.expiry_date != ", "0000-00-00");
            $builder->where("ds.expiry_date > ", date('Y-m-d'));
            $builder->where("ds.avail_qty > ", 0);
            if(session('branchId') >0 ){
                $builder->where("dw.branch_id", session('branchId'));
            }
            $query1 = $builder->get()->getResult();        
            //echo get_last_query();exit;
        }
        if(!empty($store_id)){
            $builder = $this->db->table('ph_item_request_details ds');
            $builder->select("ds.avail_qty,ds.price,ds.batch_no,ds.expiry_date,ph_items.box_qty,ph_items.carton_qty,ph_items.$this->langColumn as item_name, ph_items.company_code, list_data.$this->langColumn as unit_name, sup.$this->langColumn as supplier_name 
             
            ");

            $builder->join("ph_items", "ph_items.id=ds.item_id", "left");
            $builder->join("list_data", "list_data.id=ph_items.unit_id", "left");
            $builder->join("ph_item_requests", "ph_item_requests.id=ds.request_id", "left");
            $builder->join("ph_receive_details", "ph_receive_details.id=ds.receive_details_id", "left");
            $builder->join("ph_receive", "ph_receive.id=ph_receive_details.receive_id", "left");
            $builder->join("ph_supplier_information sup", "sup.id=ph_receive.supplier_id", "left");
            $builder->join("ph_main_store dw", "dw.id=ph_receive.store_id", "left");
            
            $builder->where("ph_item_requests.sub_store_id", $store_id);
            
            if(!empty($supplier_id)){
                $builder->where("ph_receive.supplier_id", $supplier_id);
            }

            $builder->where("ds.avail_qty > ", 0);
            $builder->where("ds.expiry_date != ", "0000-00-00");
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
        $builder = $this->db->table('ph_receive c');
        $builder->select("c.*, SUM(r.paid_amount) as paid_amount,DATEDIFF(CURDATE(), c.date) as aging,s.$this->langColumn as supplier_name");
        $builder->join("ph_supplier_information s", "s.id=c.supplier_id", "left");
        $builder->join("ph_supplier_payment r", "r.receive_id=c.id", "left");
        if(!empty($supplier_id)){
            $builder->where("c.supplier_id", $supplier_id);
        }
        if(session('branchId') >0 ){
            $builder->where("s.branch_id", session('branchId'));
        }
        $builder->where("c.due >", 0);
        $builder->where("c.date >=", $from);
        $builder->where("c.date <=", $to);
        $builder->groupBy("c.id");
        $query1 = $builder->get()->getResult();        

        return $query1;
    }

    /*--------------------------
    | Get incentives
    *--------------------------*/
    public function bdtaskt1m17_18_getDoctorCredit($doctor_id, $from, $to){
        $builder = $this->db->table('ph_sale ds');
        $builder->select("ds.*,em.nameE as doctor_name,p.nameE as patient_name, SUM(IF(sd.payment_method=130, sd.amount, 0)) as credit_amount");

        $builder->join("employees em", "em.emp_id=ds.doctor_id", "left");
        $builder->join("ph_customer_information p", "p.id=ds.customer_id", "left");
        $builder->join("ph_sale_payment_details sd", "sd.sale_id=ds.id", "left");
        
        if(!empty($doctor_id)){
            $builder->where("ds.doctor_id", $doctor_id);
        }
        $builder->where("ds.prescription_id >", 0);
        if(session('branchId') >0 ){
            $builder->where("ds.branch_id", session('branchId'));
        }
        $builder->where("ds.date >=", $from);
        $builder->where("ds.date <=", $to);

        $builder->groupBy("ds.id");

        $query1 = $builder->get()->getResult();        
        //echo get_last_query();exit;
        return $query1;
    }

    /*--------------------------
    | Get incentives
    *--------------------------*/
    public function bdtaskt1m17_19_getPatientCredit($patient_id, $from, $to){
        $builder = $this->db->table('ph_sale ds');
        $builder->select("ds.*,em.nameE as doctor_name,p.nameE as patient_name, SUM(IF(sd.payment_method=150, sd.amount, 0)) as credit_amount");

        $builder->join("employees em", "em.emp_id=ds.doctor_id", "left");
        $builder->join("ph_customer_information p", "p.id=ds.customer_id", "left");
        $builder->join("ph_sale_payment_details sd", "sd.sale_id=ds.id", "left");
        
        if(!empty($patient_id)){
            $builder->where("p.patient_id", $patient_id);
        }
        $builder->where("ds.prescription_id >", 0);
        if(session('branchId') >0 ){
            $builder->where("ds.branch_id", session('branchId'));
        }
        $builder->where("ds.date >=", $from);
        $builder->where("ds.date <=", $to);

        $builder->groupBy("ds.id");

        $query1 = $builder->get()->getResult();        
        //echo get_last_query();exit;
        return $query1;
    }

    /*--------------------------
    | Get incentives
    *--------------------------*/
    public function bdtaskt1m17_20_getVAT($type, $from, $to){
        if($type == 'Sale'){
            $builder = $this->db->table('ph_sale ds');
            $builder->select("ds.voucher_no, ds.date, ds.net_total, ds.vat");

            //$builder->join("employees em", "em.emp_id=ds.doctor_id", "left");
            /*
            if(!empty($patient_id)){
                $builder->where("p.patient_id", $patient_id);
            }*/
            $builder->where("ds.status", 1);
            $builder->where("ds.vat >", 0);
            if(session('branchId') >0 ){
                $builder->where("ds.branch_id", session('branchId'));
            }
            $builder->where("ds.date >=", $from);
            $builder->where("ds.date <=", $to);

            //$builder->groupBy("ds.id");

            $query1 = $builder->get()->getResult();        
            //echo get_last_query();exit;
            return $query1;
        } else if($type == 'Purchase'){
            $builder = $this->db->table('ph_purchases ds');
            $builder->select("ds.voucher_no, ds.date, ds.grand_total as net_total, ds.vat");

            $builder->join("ph_main_store dw", "dw.id=ds.store_id", "left");
            /*
            if(!empty($patient_id)){
                $builder->where("p.patient_id", $patient_id);
            }*/
            $builder->where("ds.status", 1);
            $builder->where("ds.vat >", 0);
            if(session('branchId') >0 ){
                $builder->where("dw.branch_id", session('branchId'));
            }
            $builder->where("ds.date >=", $from);
            $builder->where("ds.date <=", $to);

            //$builder->groupBy("ds.id");

            $query1 = $builder->get()->getResult();        
            //echo get_last_query();exit;
            return $query1;
        } else if($type == 'JV'){
            $builder = $this->db->table('ph_journal_vouchers ds');
            $builder->select("CONCAT(ds.vtype,'-',ds.id) as voucher_no, ds.voucher_date as date, ds.totalDebit as net_total, ds.vat");

            //$builder->join("employees em", "em.emp_id=ds.doctor_id", "left");
            /*
            if(!empty($patient_id)){
                $builder->where("p.patient_id", $patient_id);
            }*/
            $builder->where("ds.status", 1);
            $builder->where("ds.vat >", 0);
            if(session('branchId') >0 ){
                $builder->where("ds.branch_id", session('branchId'));
            }
            $builder->where("ds.voucher_date >=", $from);
            $builder->where("ds.voucher_date <=", $to);

            //$builder->groupBy("ds.id");

            $query1 = $builder->get()->getResult();        
            //echo get_last_query();exit;
            return $query1;
        } else {
            return array();
        }
    }
}
?>