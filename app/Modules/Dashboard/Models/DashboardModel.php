<?php

namespace App\Modules\Dashboard\Models;

class DashboardModel
{
    private $current_month;
    private $previous_month;
    public function __construct()
    {
        $this->db = db_connect();
        $this->current_month = date('m');
        $this->previous_month = date('m', strtotime(date('Y-m-d') . ' -1 months'));
    }

    public function store_alert()
    {

        $builder3 = $this->db->table('wh_material_stock bs');

        $builder3->select("bs.*, bw.nameE as store_name,
        wh_items.cat_id, 
        wh_items.nameE as item_name, 
        wh_items.item_code, 
        list_data.nameE as unit_name, 
        wh_items.alert_qty");

        $builder3->join('wh_material_store bw', 'bw.id=bs.store_id', 'left');
        $builder3->join('wh_material wh_items', 'wh_items.id=bs.item_id', 'left');
        $builder3->join('list_data', 'list_data.id=wh_items.unit_id', 'left');
        $query3   =  $builder3->get();
        $records  =   $query3->getResult();
        $returndata = [];

        if ($records) {
            $sl = 1;
            foreach ($records as $key => $value) {
                if ($value->alert_qty > $value->stock) {
                    $returndata[] = array(
                        'sl'            => $sl,
                        'item_code'     => $value->item_code,
                        'item_name'     => $value->item_name . ' - ' . $value->item_code,
                        'stock'         => ($value->stock?$value->stock:0) . ' ' . $value->unit_name,
                        'alert_qty'     => $value->alert_qty,
                        'store_name'    => $value->store_name,
                    );
                    $sl++;
                }
            }
        }
        return $returndata;
    }

    public function store_chart_getAll($postData=null){
        $response = array();
        ## Read value
        @$draw = $postData['draw'];
        @$start = $postData['start'];
        @$rowperpage = $postData['length']; // Rows display per page
        @$columnIndex = $postData['order'][0]['column']; // Column index
        @$columnName = $postData['columns'][$columnIndex]['data']; // Column name
        @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        @$searchValue = $postData['search']['value']; // Search value
        $alert_type = $postData['alert_type'];
        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
            $searchQuery = " (wh_items.nameE like '%".$searchValue."%' OR bs.stock like '%".$searchValue."%' ) ";
        }
        
        ## Fetch records
        $builder3 = $this->db->table('wh_material_stock bs');
        $builder3->select("bs.id,bs.stock,
        bw.nameE as store_name,
        wh_items.nameE as item_name, 
        wh_items.item_code, 
        list_data.nameE as unit_name, 
        wh_items.minor_alert_qty,
        wh_items.alert_qty");
        $builder3->join('wh_material_store bw', 'bw.id=bs.store_id', 'left');
        $builder3->join('wh_material wh_items', 'wh_items.id=bs.item_id', 'right');
        $builder3->join('list_data', 'list_data.id=wh_items.unit_id', 'left');
        if($searchValue != ''){
            $builder3->where($searchQuery);
        }
        ## Total number of records with filtering
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        ## Total number of records without filtering
        $totalRecords = $builder3->countAll();
        if($searchQuery == ''){
            $totalRecords = $totalRecordwithFilter;
        }
        $data = array();
        $alert_value = 0;
        $sl = 1;
        foreach($records as $record ){ 
            if ($alert_type == 'minimum') {
                $alert_value = $record['alert_qty'];
            }elseif ($alert_type == 'minor') {
                $alert_value = $record['minor_alert_qty'];
            }
            if ($alert_value > $record['stock']) {
                $data[] = array( 
                   'id'       => $sl,
                   'item_name'=> $record['item_name'] . ' - ' . $record['item_code'],
                   'store_name'=> $record['store_name'],
                   'stock'    => ($record['stock']?$record['stock']:0) . ' ' . $record['unit_name'],
                   'alert_qty'=> $alert_value,
                ); 
            }
            $sl++;
        }
        ## Response
        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData"               => $data
        );
        return $response; 
    }

    public function po_getAll($postData=null){
        $response = array();
        ## Read value
        @$draw = $postData['draw'];
        @$start = $postData['start'];
        @$rowperpage = $postData['length']; // Rows display per page
        @$columnIndex = $postData['order'][0]['column']; // Column index
        @$columnName = $postData['columns'][$columnIndex]['data']; // Column name
        @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        @$searchValue = $postData['search']['value']; // Search value
        $po_date = $postData['po_date'];
        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
            $searchQuery = " (a.voucher_no like '%".$searchValue."%' OR c.nameE like '%".$searchValue."%' ) ";
        }
        
        ## Fetch records
        $builder3 = $this->db->table('wh_material_purchase a');
        $builder3->select("a.id, a.voucher_no as po_no, b.qty as po_qty, 
            CONCAT(c.nameE, ' (', c.item_code, ')') as item_name, CONCAT(g.nameE, ' (', g.code_no, ')') as supplier_name");
        $builder3->join("wh_material_purchase_details b", "b.purchase_id=a.id", "left");
        $builder3->join("wh_material c", "c.id=b.item_id", "left");
        $builder3->join("wh_supplier_information g", "g.id=a.supplier_id", "left");
        $builder3->where("a.isApproved", 1);
        $builder3->where("a.received", 0);
        $builder3->where("a.date", $po_date);
        if($searchValue != ''){
            $builder3->where($searchQuery);
        }
        ## Total number of records with filtering
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        ## Total number of records without filtering
        $totalRecords = $builder3->countAll();
        if($searchQuery == ''){
            $totalRecords = $totalRecordwithFilter;
        }
        $data = array();
        $sl = 1;
        foreach($records as $record ){ 
            $data[] = array( 
               'id'       => $sl,
               'po_no'=> $record['po_no'],
               'supplier_name'=> $record['supplier_name'],
               'item_name'=> $record['item_name'],
               'po_qty'=> $record['po_qty'],
            ); 
            $sl++;
        }
        ## Response
        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData"               => $data
        );
        return $response; 
    }

    
    public function supplier_alert()
    {
        $builder3 = $this->db->table('wh_material_purchase bs');

        $builder3->select("bs.*, bw.item_id,bw.qty,bw.purchase_id,wh_supplier_information.nameE as supplier");
        $builder3->join('wh_material_purchase_details bw', 'bw.purchase_id=bs.id', 'left');
        $builder3->join('wh_supplier_information', 'wh_supplier_information.id=bs.supplier_id', 'left');
        $builder3->where('bs.received', 2);
        $builder3->orWhere('bs.received', 0);
        $query3   =  $builder3->get();
        $records  =   $query3->getResult();
        return $records;
    }
    public function production_stock_alert()
    {

        $builder3 = $this->db->table('wh_production_stock bs');

        $builder3->select("bs.*, bw.nameE as store_name,
        wh_items.cat_id, 
        wh_items.nameE as item_name, 
        wh_items.item_code, 
        list_data.nameE as unit_name, 
        wh_items.alert_qty");

        $builder3->join('wh_production_store bw', 'bw.id=bs.store_id', 'left');
        $builder3->join('wh_items', 'wh_items.id=bs.item_id', 'left');
        $builder3->join('list_data', 'list_data.id=wh_items.unit_id', 'left');
        $query3   =  $builder3->get();
        $records  =   $query3->getResult();
        $returndata = [];

        if ($records) {
            $sl = 1;
            foreach ($records as $key => $value) {
                if ($value->alert_qty > $value->stock) {
                    $returndata[] = array(
                        'sl'            => $sl,
                        'item_code'     => $value->item_code,
                        'item_name'     => $value->item_name . ' - ' . $value->item_code,
                        'stock'         => $value->stock . ' ' . $value->unit_name,
                        'store_name'    => $value->store_name,
                    );
                    $sl++;
                }
            }
        }
        return $returndata;
    }

    public function getShortCredit(){
        $builder = $this->db->table('do_main a');
        $builder->select("SUM(a.grand_total - a.paid_amount) as due_amount");

        $query1 = $builder->get()->getRow();    
        return $query1;
    }

    public function getDOquantity(){
        $builder = $this->db->table('do_main a');
        $builder->select("SUM(b.total_kg) as quantity");
        $builder->join("do_details b",'b.do_id = a.do_id', 'left');
        $builder->where('a.do_date', date("Y-m-d"));
        $query1 = $builder->get()->getRow();    
        return $query1;
    }

    public function getDOdeliveryQuantity(){
        $builder = $this->db->table('do_main a');
        $builder->select("SUM(b.total_kg) as quantity");
        $builder->join("do_delivery_details b",'b.do_id = a.do_id', 'left');
        $builder->where('a.do_date', date("Y-m-d"));
        $query1 = $builder->get()->getRow();    
        return $query1;
    }

    public function finished_goods_store_getAll($postData=null){
        $response = array();
        ## Read value
        @$draw = $postData['draw'];
        @$start = $postData['start'];
        @$rowperpage = $postData['length']; // Rows display per page
        @$columnIndex = $postData['order'][0]['column']; // Column index
        @$columnName = $postData['columns'][$columnIndex]['data']; // Column name
        @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        @$searchValue = $postData['search']['value']; // Search value
        $alert_type = $postData['alert_type'];
        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
            $searchQuery = " (wh_items.nameE like '%".$searchValue."%' OR bs.stock like '%".$searchValue."%' ) ";
        }
        
        ## Fetch records
        $builder3 = $this->db->table('wh_machine_stock bs');
        $builder3->select("bs.id,bs.stock,
        bw.nameE as store_name,
        wh_items.nameE as item_name, 
        wh_items.item_code, 
        list_data.nameE as unit_name, 
        wh_items.minor_alert_qty,
        wh_items.alert_qty");
        $builder3->join('wh_machine_store bw', 'bw.id=bs.sub_store_id', 'left');
        $builder3->join('wh_items', 'wh_items.id=bs.item_id', 'right');
        $builder3->join('list_data', 'list_data.id=wh_items.unit_id', 'left');
        if($searchValue != ''){
            $builder3->where($searchQuery);
        }
        ## Total number of records with filtering
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        ## Total number of records without filtering
        $totalRecords = $builder3->countAll();
        if($searchQuery == ''){
            $totalRecords = $totalRecordwithFilter;
        }
        $data = array();
        $alert_value = 0;
        $sl = 1;
        foreach($records as $record ){ 
            if ($alert_type == 'minimum') {
                $alert_value = $record['alert_qty'];
            }elseif ($alert_type == 'minor') {
                $alert_value = $record['minor_alert_qty'];
            }
            if ($alert_value > $record['stock']) {
                $data[] = array( 
                   'id'       => $sl,
                   'item_name'=> $record['item_name'] . ' - ' . $record['item_code'],
                   'store_name'=> $record['store_name'],
                   'stock'    => ($record['stock']?$record['stock']:0) . ' ' . $record['unit_name'],
                   'alert_qty'=> $alert_value,
                ); 
            }
            $sl++;
        }
        ## Response
        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData"               => $data
        );
        return $response; 
    }

    public function sales_getAll($postData=null){
        $response = array();
        ## Read value
        @$draw = $postData['draw'];
        @$start = $postData['start'];
        @$rowperpage = $postData['length']; // Rows display per page
        @$columnIndex = $postData['order'][0]['column']; // Column index
        @$columnName = $postData['columns'][$columnIndex]['data']; // Column name
        @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        @$searchValue = $postData['search']['value']; // Search value
        $from_date = $postData['from_date'];
        $to_date = $postData['to_date'];
        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
            $searchQuery = " (wh_items.nameE like '%".$searchValue."%' ) ";
        }
        
        ## Fetch records
        $builder3 = $this->db->table('do_main a');
        $builder3->select("a.id,sum(b.total_kg) as total_kg,,
        wh_items.nameE as item_name, 
        wh_items.item_code, 
        list_data.nameE as unit_name");
        $builder3->join("do_details b", "b.do_id=a.do_id", "left");;
        $builder3->join('wh_items', 'wh_items.id=b.item_id', 'right');
        $builder3->join('list_data', 'list_data.id=wh_items.unit_id', 'left');
        $builder3->where("DATE(a.do_date) >=", $from_date);
        $builder3->where("DATE(a.do_date) <=", $to_date);
        $builder3->groupBy('b.item_id');
        if($searchValue != ''){
            $builder3->where($searchQuery);
        }
        ## Total number of records with filtering
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        ## Total number of records without filtering
        $totalRecords = $builder3->countAll();
        if($searchQuery == ''){
            $totalRecords = $totalRecordwithFilter;
        }
        $data = array();
        $sl = 1;
        foreach($records as $record ){
            $data[] = array( 
               'id'       => $sl,
               'item_name'=> $record['item_name'] . ' - ' . $record['item_code'],
               'total'=> $record['total_kg']/1000,
               'stock'=>'',
               'alert_qty'=>'',
            ); 
            $sl++;
        }
        ## Response
        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData"               => $data
        );
        return $response; 
    }


    public function sales_alert()
    {
        $builder = $this->db->table('do_main a');
        $builder->select("a.*,b.name as dealer_name,c.total_kg,c.item_id,d.nameE as item_name");
        $builder->join("dealer_info b",'b.id = a.dealer_id', 'left');
        $builder->join("do_details c",'c.do_id = a.do_id', 'left');
        $builder->join("wh_items d", 'd.id = c.item_id', 'left');
        // $builder->where('a.dl_s_approved', null);
        // $builder->orWhere('a.str_s_approved', 0);
        //$builder->groupBy('c.item_id');
        $query3   =  $builder->get();
        $records  =   $query3->getResult();
        $returndata = [];

        if ($records) {
            $sl = 1;
            foreach ($records as $key => $value) {
                $check_delivery_status = $this->do_delivery_status($value->do_id);
                $itme_delivered = $this->delivered_itmedata($value->do_id, $value->item_id);
                if ($check_delivery_status == 1) {
                    $returndata[] = array(
                        'sl'            => $sl,
                        'dealer_name'     => $value->dealer_name,
                        'item_name'     => $value->item_name,
                        'qty'         => $value->total_kg,
                        'rcv_qty'    => ($itme_delivered->deliverdqty_mt? $itme_delivered->deliverdqty_mt:0),
                        'rm_qty'    => (($value->total_kg ? $value->total_kg : 0) - ($itme_delivered->deliverdqty_mt ? $itme_delivered->deliverdqty_mt : 0)),
                        'unit' =>'KG'
                    );
                    $sl++;
                }
            }
        }
        return $returndata;
    }
    public function delivered_itmedata($do_id, $item_id)
    {
        $builder = $this->db->table('do_delivery_details');
        $builder->select('sum(quantity) as deliverdqty,sum(total_kg) as deliverdqty_mt');
        $builder->where('item_id', $item_id);
        $builder->where('do_id', $do_id);
        $query = $builder->get();
        $data = $query->getRow();
        return $data;
    }
    public function do_delivery_status($do_id, $sale_type=null)
    {

        $request_order = $this->db->table('do_details')->select('sum(quantity) as total_bag_request, sum(total_kg) as total_mt_request')->where('do_id', $do_id)->get()->getRow();
        $delivered_order = $this->db->table('do_delivery_details')->select('sum(quantity) as total_bag_delivered, sum(total_kg) as total_mt_delivered')->where('do_id', $do_id)->get()->getRow();

       
        $bag_req_qty = ($request_order ? $request_order->total_bag_request : 0);
        $bag_del_qty = ($delivered_order ? $delivered_order->total_bag_delivered : 0);
        $response = ($bag_req_qty ? $bag_req_qty : 0) - ($bag_del_qty ? $bag_del_qty : 0);
        $status = 0;
        if ($response > 0) {
            $status = 1;
        }
        return $status;
    }
    public function daily_attendance_count()
    {
    
        $builder1 = $this->db->table('hrm_employees');
        $builder1->select('employee_id');
        $builder = $this->db->table('hrm_attendance_history');
        $builder->where('atten_date', date('Y-m-d'));
        $data=$builder->countAllResults();
        $absent= $builder1->countAllResults()-$data;
        $alldata=array(
            'total_emp' => $builder1->countAllResults(),
            'attend' => $data,
            'absent' => $absent
        );
        return $alldata;
    }


    public function total_production()
    {
        $builder = $this->db->table('wh_receive a');
        $builder->select('sum(b.qty) as qty, a.isApproved');
        $builder->join("wh_receive_details b", 'b.receive_id = a.id', 'left');
        $builder->where('a.isApproved', 1);
        $query = $builder->get();
        $data = $query->getRow();
        return $data;
    }
    public function total_production_chart()
    {
        $builder = $this->db->table('wh_receive a');
        $builder->select('sum(b.qty) as qty, a.isApproved');
        $builder->join("wh_receive_details b", 'b.receive_id = a.id', 'left');
        $builder->where('month(a.date)', $this->current_month);
        $builder->where('a.isApproved', 1);
        $query = $builder->get();
        $c = $query->getRow();

        $builder = $this->db->table('wh_receive a');
        $builder->select('sum(b.qty) as qty, a.isApproved');
        $builder->join("wh_receive_details b", 'b.receive_id = a.id', 'left');
        $builder->where('month(a.date)', $this->previous_month);
        $builder->where('a.isApproved', 1);
        $query = $builder->get();
        $p = $query->getRow();

        $data = array(
            'current_mon' => $c->qty,
            'prev_mon' => $p->qty
        );

        return $data;
    }
    public function today_production()
    {
        $builder = $this->db->table('wh_receive a');
        $builder->select('sum(b.qty) as qty, a.isApproved');
        $builder->join("wh_receive_details b", 'b.receive_id = a.id', 'left');
        $builder->where('a.date', date('Y-m-d'));
        $builder->where('a.isApproved', 1);
        $query = $builder->get();
        $data = $query->getRow();
        return $data;
    }
    public function total_consumtion()
    {
        $builder = $this->db->table('wh_machine_requests a');
        $builder->select('sum(b.qty) as qty, a.isApproved');
        $builder->join("wh_machine_request_details b", 'b.request_id = a.id', 'left');
        $builder->where('a.isApproved', 1);
        $query = $builder->get();
        $data = $query->getRow();
        return $data;
    }
    public function today_consumtion()
    {
        $builder = $this->db->table('wh_machine_requests a');
        $builder->select('sum(b.qty) as qty, a.isApproved');
        $builder->join("wh_machine_request_details b", 'b.request_id = a.id', 'left');
        $builder->where('a.date', date('Y-m-d'));
        $builder->where('a.isApproved', 1);
        $query = $builder->get();
        $data = $query->getRow();
        return $data;
    }
    public function delivery_chart_prev()
    {
        $builder = $this->db->table('do_main a');
        $builder->select("a.*,c.quantity,c.total_kg,c.item_id");
        $builder->join("do_details c", 'c.do_id = a.do_id', 'left');
        $builder->where('month(a.do_date)', ltrim($this->previous_month, "0"));
        $builder->where('a.dl_s_approved', 1);
        $query = $builder->get();
        $records = $query->getResult();

        $recv_qty = 0;

        if ($records) {

            foreach ($records as $key => $value) {
                $check_delivery_status = $this->do_delivery_status($value->do_id, null);
                $itme_delivered = $this->delivered_itmedata($value->do_id, $value->item_id);

                
                $bag_to_mt_delivery = (50 * $itme_delivered->deliverdqty) / 1000;
                $recv_qty = $recv_qty + ($bag_to_mt_delivery ? $bag_to_mt_delivery : 0);
            }
        }
        return $recv_qty;
    }
    public function delivery_chart_curr()
    {
        $builder = $this->db->table('do_main a');
        $builder->select("a.*,c.quantity,c.total_kg,c.item_id");
        $builder->join("do_details c", 'c.do_id = a.do_id', 'left');
        $builder->where('month(a.do_date)', ltrim($this->current_month, "0"));
        $builder->where('a.dl_s_approved', 1);
        $query = $builder->get();
        $records = $query->getResult();

        $recv_qty_c = 0;
        if ($records) {
            foreach ($records as $key => $value) {

                $itme_delivered = $this->delivered_itmedata($value->do_id, $value->item_id);

                $bag_to_mt_delivery = (50 * $itme_delivered->deliverdqty) / 1000;
                $recv_qty_c = $recv_qty_c + ($bag_to_mt_delivery ? $bag_to_mt_delivery : 0);
            }
        }

        return $recv_qty_c;
    }
    public function undelivery_chart_prev()
    {
        $builder = $this->db->table('do_main a');
        $builder->select("a.*,c.quantity,c.total_kg,c.item_id");
        $builder->join("do_details c", 'c.do_id = a.do_id', 'left');
        $builder->where('month(a.do_date)', ltrim($this->previous_month, "0"));
        $builder->where('a.dl_s_approved', Null);
        $query = $builder->get();
        $records = $query->getResult();
        $rm_qty = 0;

        if ($records) {

            foreach ($records as $key => $value) {
                $check_delivery_status = $this->do_delivery_status($value->do_id, null);
                $itme_delivered = $this->delivered_itmedata($value->do_id, $value->item_id);

                $bag_to_mt = (50 * $value->quantity) / 1000;
                $bag_to_mt_delivery = (50 * $itme_delivered->deliverdqty) / 1000;
                $rm_qty = $rm_qty + (($bag_to_mt ? $bag_to_mt : 0) - ($bag_to_mt_delivery ? $bag_to_mt_delivery : 0));
            }
        }
        return $rm_qty;
    }
    public function undelivery_chart_curr()
    {
        $builder = $this->db->table('do_main a');
        $builder->select("a.*,c.quantity,c.total_kg,c.item_id");
        $builder->join("do_details c", 'c.do_id = a.do_id', 'left');
        $builder->where('month(a.do_date)', ltrim($this->current_month, "0"));
        $builder->where('a.dl_s_approved', Null);
        $query = $builder->get();
        $records = $query->getResult();

        $rm_qty = 0;

        if ($records) {
            foreach ($records as $key => $value) {

                $itme_delivered = $this->delivered_itmedata($value->do_id, $value->item_id);

                $bag_to_mt = (50 * $value->quantity) / 1000;
                $bag_to_mt_delivery = (50 * $itme_delivered->deliverdqty) / 1000;
                $rm_qty = $rm_qty + (($bag_to_mt ? $bag_to_mt : 0) - ($bag_to_mt_delivery ? $bag_to_mt_delivery : 0));
            }
        }
        return $rm_qty;
    }
    public function current_process_loss()
    {
        $builder = $this->db->table('wh_machine_requests a');
        $builder->select('sum(b.wip_qty) as qty, a.isApproved');
        $builder->join("wh_machine_request_details b", 'b.request_id = a.id', 'left');
        $builder->where('month(a.date)', ltrim($this->current_month, "0"));
        $builder->where('a.isApproved', 1);
        $query = $builder->get();
        $data = $query->getRow();
        return $data;
    }
    public function previous_process_loss()
    {
        $builder = $this->db->table('wh_machine_requests a');
        $builder->select('sum(b.wip_qty) as qty, a.isApproved');
        $builder->join("wh_machine_request_details b", 'b.request_id = a.id', 'left');
        $builder->where('month(a.date)', ltrim($this->previous_month, "0"));
        $builder->where('a.isApproved', 1);
        $query = $builder->get();
        $data = $query->getRow();
        return $data;
    }

    public function accountpayableGraph()
    { 
        $active_fiscalyear = $this->getActiveFiscalyear();  
        $builder = $this->db->table('acc_subcode a');
        $builder->select("((select ifnull(sum(Credit),0) from acc_transaction where subCode= `a`.`id` AND subType = 4 AND FyID = ".$active_fiscalyear.")-(select ifnull(sum(Debit),0) from acc_transaction where subCode= `a`.`id` AND subType = 4 AND FyID = ".$active_fiscalyear.") + ((select ifnull(sum(Credit),0) from acc_opening_balance where subCode= `a`.`id` AND subType = 4 AND fyear = ".$active_fiscalyear.")-(select ifnull(sum(Debit),0) from acc_opening_balance where subCode= `a`.`id` AND subType = 4 AND fyear = ".$active_fiscalyear."))) as balance,a.name,a.id");
        $builder->where('a.subTypeId',4);
        $builder->orderBy('balance','DESC');
        $builder->limit(10);
        $query = $builder->get();
        $data = $query->getResult();
      
                      $chart_data = '';
    
                     foreach ($data as $cdata) {
                        
                          $chart_data .= (!empty($cdata) ? '{'.'"category":'.'"'."$cdata->name".'"'.','.'"value":'.($cdata->balance > 0 ?$cdata->balance:0).'}' . ',' : null);
                         
                     }
         
                     return $chart_data;
         
             
    }

    public function accountreceivableGraph()
    { 
        $active_fiscalyear = $this->getActiveFiscalyear();  
        $builder = $this->db->table('acc_subcode a');
        $builder->select("((select ifnull(sum(Debit),0) from acc_transaction where subCode= `a`.`id` AND subType = 6 AND FyID = ".$active_fiscalyear.")-(select ifnull(sum(Credit),0) from acc_transaction where subCode= `a`.`id` AND subType = 6 AND FyID = ".$active_fiscalyear.") + ((select ifnull(sum(Debit),0) from acc_opening_balance where subCode= `a`.`id` AND subType = 6 AND fyear = ".$active_fiscalyear.")-(select ifnull(sum(Credit),0) from acc_opening_balance where subCode= `a`.`id` AND subType = 6 AND fyear = ".$active_fiscalyear."))) as balance,a.name,a.id");
        $builder->where('a.subTypeId',6);
        $builder->orderBy('balance','DESC');
        $builder->limit(10);
        $query = $builder->get();
        $data = $query->getResult();
      
                      $chart_data = '';
    
                     foreach ($data as $cdata) {
                        
                          $chart_data .= (!empty($cdata) ? '{'.'"category":'.'"'."$cdata->name".'"'.','.'"value":'.($cdata->balance > 0 ?$cdata->balance:0).'}' . ',' : null);
                         
                     }
         
                     return $chart_data;
         
             
    }

    public function IncomeExpenseSummary()
    {
        $dtpFromDate = date('Y-m-1');
        $dtpToDate   = date('Y-m-t');
        $data['income'] = $this->income_expense_summery('I','Income',$dtpFromDate,$dtpToDate,0);
        $data['expenses'] = $this->income_expense_summery('E','Expenses',$dtpFromDate,$dtpToDate,0);
        return $data;
    }

    public function income_expense_summery($type,$phead,$dtpFromDate,$dtpToDate, $resultType) {
        $secondLevel = $this->get_charter_accounts_by_headNae($type,$phead);
        $mainHead= array();
        $sumTotal = 0; 
        $secondArray = array();
        if($secondLevel) {    
          
            foreach($secondLevel as $chac) {
                $subTotal = 0;
                $innerArray = array();
                $thirdLevel = $this->get_charter_accounts_by_headNae($type,$chac->HeadName);
                if($thirdLevel) {
                    $thirdLevelArray = array();
                    foreach($thirdLevel as $tdl) {
                        $balance= 0;                     
                        $transationLevel = $this->get_charter_accounts_by_headNae($type,$tdl->HeadName);
    
                        if($transationLevel){
                            $tDebit =0; $tCredit = 0;
                            foreach($transationLevel as $trans) {
                                $tval  = $this->get_general_ledger_report($trans->HeadCode,$dtpFromDate,$dtpToDate, 0, 0);
                                if($tval) {
                                    foreach($tval as $amounts){
                                        $tDebit += $amounts->debit;
                                        $tCredit += $amounts->credit;
                                    }
                                }                            
                            }
                            if($type == 'A' || $type == 'E') {
    
                                $balance = $tDebit - $tCredit;
                            } else {
                                $balance = $tCredit - $tDebit;
                            }
                           $sumTotal +=  $balance;
                           $subTotal += $balance;
                        }
                       
                    }
                }
                             
            }
        }
        return $sumTotal;
        
    }

    public function get_general_ledger_report($cmbCode,$dtpFromDate,$dtpToDate, $chkIsTransction, $isfyear=0){
        $yearid = $this->getActiveFiscalyear();
        if($chkIsTransction == 1) {
          $builder =  $this->db->table('acc_transaction');
           $builder->select('acc_transaction.VNo,acc_transaction.COAID,, acc_transaction.Vtype, acc_transaction.VDate, acc_transaction.Narration, acc_transaction.ledgerComment, acc_transaction.Debit as Debit, acc_transaction.Credit as Credit, acc_transaction.Debit as debit, acc_transaction.Credit as credit, acc_transaction.IsAppove, acc_transaction.COAID,acc_coa.HeadName, acc_coa.PHeadName, acc_coa.HeadType');
           $builder->join('acc_coa','acc_transaction.RevCodde = acc_coa.HeadCode', 'left');
           $builder->where('acc_transaction.IsAppove',1);
           $builder->where('VDate BETWEEN "'.$dtpFromDate. '" and "'.$dtpToDate.'"');
           $builder->where('acc_transaction.COAID',$cmbCode);
           if($isfyear!=0) {
             $builder->where('acc_transaction.fyear',$yearid); 
           }                  
           $builder->orderBy('acc_transaction.VDate','asc');
           $builder->orderBy('acc_transaction.Vtype','asc');
           $query = $builder->get();
           return $query->getResult();
    
        } else {
            $builder = $this->db->table('acc_transaction');   
           $builder->select('COAID, Vtype,Debit, Credit,Debit as debit,Credit as credit');             
           $builder->where('IsAppove',1);
           $builder->where('VDate BETWEEN "'.$dtpFromDate. '" and "'.$dtpToDate.'"');
           $builder->where('COAID',$cmbCode);
           if($isfyear!=0) {
             $builder->where('acc_transaction.fyear',$yearid); 
           }
           $query = $builder->get();             
           return $query->getResult();
        }       
    }

    public function get_charter_accounts_by_headNae($type,$phead, $except = null) {
        $builder = $this->db->table('acc_coa');
        $builder->select('HeadCode,HeadName,assetCode,DepreciationRate');
        $builder->where('HeadType', $type);
        if($except != null) {
         $builder->where("HeadCode !=",$except);
        }
        $builder->where('PHeadName',$phead);
        $CharterAccounts = $builder->get();
        if($builder->countAll() > 0) {
          return $CharterAccounts->getResult();
        } else {
         return false;
        }
     
    }

    public function getActiveFiscalyear()
    {
         $fiscalyear = $this->db->table('financial_year')->select('*')->where('status',1)->get()->getRow();
         return ($fiscalyear?$fiscalyear->id:0);
    }

    public function todaysVouchers()
    {
        $builder = $this->db->table('acc_vaucher');
        $builder->select('count(*) as totalvoucher');
        $builder->where('DATE(CreateDate)',date('Y-m-d'));
        $CharterAccounts = $builder->get()->getRow();
        if($builder->countAll() > 0) {
          return $CharterAccounts->totalvoucher;
        } else {
         return 0;
        }
    }

    public function totalpendingVouchers()
    {
        $builder = $this->db->table('acc_vaucher');
        $builder->select('count(*) as totalvoucher');
        $builder->where('isApproved',0);
        $CharterAccounts = $builder->get()->getRow();
        if($builder->countAll() > 0) {
          return $CharterAccounts->totalvoucher;
        } else {
         return 0;
        }
    }
}