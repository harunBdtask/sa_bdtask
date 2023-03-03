<?php namespace App\Modules\Bag_purchase\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m12RequisitionModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasCreateAccess = $this->permission->method('wh_bag_requisition', 'create')->access();
        $this->hasReadAccess   = $this->permission->method('wh_bag_requisition', 'read')->access();
        $this->hasUpdateAccess = $this->permission->method('wh_bag_requisition', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('wh_bag_requisition', 'delete')->access();

    }

    public function bdtaskt1m12_02_getAll($postData=null){
        $response = array();
        ## Read value
      
        @$store_id = $postData['store_id'];
        @$supplier_id = $postData['supplier_id'];
        @$item_id = $postData['item_id'];
        @$voucher_no = trim($postData['voucher_no']);
        @$date = $postData['date'];

        @$draw = $postData['draw'];
        @$start = $postData['start'];
        @$rowperpage = $postData['length']; // Rows display per page
        @$columnIndex = $postData['order'][0]['column']; // Column index
        @$columnName = $postData['columns'][$columnIndex]['data']; // Column name
        @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        @$searchValue = $postData['search']['value']; // Search value
        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
           $searchQuery = " (wh_bag_requisition.voucher_no like '%".$searchValue."%' OR wh_bag_requisition.date like '%".$searchValue."%' ) ";
        }
        if($store_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_bag_requisition.store_id = '".$store_id."' ) ";
        }
        if($supplier_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_bag_requisition.supplier_id = '".$supplier_id."' ) ";
        }
        if($item_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( pd.item_id = '".$item_id."' ) ";
        }
        if($voucher_no != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_bag_requisition.id = '".$voucher_no."' ) ";
        }
        if($date != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_bag_requisition.date = '".$date."' ) ";
        }
        ## Fetch records
        $builder3 = $this->db->table('wh_bag_requisition');
        $builder3->select("wh_bag_requisition.*, wh_bag_supplier_information.nameE as supplier_name, GROUP_CONCAT(i.company_code) as item_codes, GROUP_CONCAT(i.nameE) as item_names");
        $builder3->join('wh_bag_supplier_information', 'wh_bag_supplier_information.id=wh_bag_requisition.supplier_id','left');
        $builder3->join('wh_bag_requisition_details pd', 'pd.purchase_id=wh_bag_requisition.id','left');
        $builder3->join('wh_bag i', 'i.id=pd.item_id','left');
        $builder3->where('wh_bag_requisition.type !=',2);
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        } 

        $builder3->groupBy('wh_bag_requisition.id');
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
        
        $details = get_phrases(['view', 'details']);
        $update = get_phrases(['update']);
        $delete = get_phrases(['delete']);
        $approve = get_phrases(['approve']);

        foreach($records as $record ){ 
            $button = '';
            $status = '';
            $purchase_status = '';

            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="'.$details.'" data-id="'.$record['id'].'"><i class="fa fa-eye"></i></a>';
            
            if($record['isApproved']==1){
                $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC quotationCSModal mr-2 custool '.$postData['csbtn'].'" title="Quotation CS" data-id="'.$record['id'].'"><i class="fa fa-eye"></i></a>';
                $status .= '<div class="badge badge-success" >'.get_phrases(['approved']).'</div>';
            } else {
                $status .= '<div class="badge badge-info">'.get_phrases(['not','approved']).'</div>';
                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC actionApprove mr-2 custool" title="'.$approve.'" data-id="'.$record['id'].'"><i class="fa fa-check"></i></a>';
                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
                $button .= (!$this->hasDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete mr-2 custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';
            }

            if($record['received'] ==1 ){
                $purchase_status .= ' <div class="badge badge-success" >'.get_phrases(['purchased']).'</div>';
            } else if($record['received']==2){
                $purchase_status .= '<div class="badge badge-warning text-white" >'.get_phrases(['partialy', 'purchased']).'</div> ';
            } else {
                $purchase_status .= ' <div class="badge badge-info" >'.get_phrases(['not','purchased']).'</div>';
            }

            $data[] = array( 
                'id'                => $record['id'],
                'voucher_no'        => $record['voucher_no'],
                'date'              => $record['date'],
                'grand_total'       => $record['grand_total'],
                'purchase_status'   => $purchase_status,
                'status'            => $status,
                'button'            => $button
            ); 
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
    
    public function bdtaskt1m12_03_getPurchaseOrderDetailsById($id){
        $data = $this->db->table('wh_bag_requisition')->select('wh_bag_requisition.*, DATE_FORMAT(wh_bag_requisition.date, "%d/%m/%Y") as date, wh_bag_supplier_information.nameE as supplier_name')                        
            ->join('wh_bag_supplier_information', 'wh_bag_supplier_information.id=wh_bag_requisition.supplier_id','left')
            ->where( array('wh_bag_requisition.id'=>$id) )
            ->get()
            ->getRowArray();

        return $data;
    }

    public function bdtaskt1m12_04_updateStock($qty, $where){
        $result = $this->db->table('wh_bag_stock')->set('stock', 'stock+'.$qty, FALSE)->set('stock_in', 'stock_in+'.$qty, FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_05_get_item_list(){
        $data = $this->db->table('wh_bag')->select('wh_bag.*, lbs.nameE as b_specification')  
            ->join('list_data lbs', 'lbs.id=wh_bag.specification','left')      
            ->get()
            ->getResult();

        return $data;
    }

    public function bdtaskt1m12_06_getSupplierItemDetailsById($item_id){

        // $data = $this->db->table('wh_bag')->select('list_data.nameE as unit_name, wh_bag.*')   
        //     ->join('list_data', 'list_data.id=wh_bag.unit_id','left')
        //     ->where( array('wh_bag.id'=>$item_id) )
        //     ->get()
        //     ->getRowArray();

        // return $data;


        $data = $this->db->table('wh_bag')->select('list_data.nameE as unit_name, ld.nameE as where_use_name, mr.date as last_purchase_date, mrd.qty as last_purchase_qty, wh_bag.*')    
            ->join('list_data', 'list_data.id=wh_bag.unit_id','left')
            ->join('list_data ld', 'ld.id=wh_bag.where_use','left')
            ->join('wh_bag_receive_details mrd', 'mrd.item_id=wh_bag.id','left')
            ->join('wh_bag_receive mr', 'mr.id=mrd.receive_id','left')
            ->where( array('wh_bag.id'=>$item_id) )
            ->orderBy('mrd.id', 'desc')
            ->limit(1)
            
            ->get()
            ->getRowArray();

        return $data;
    }

    public function bdtaskt1m12_07_getPurchaseOrderItemDetailsById($purchase_id, $supplier_id=null){

        $data = $this->db->table('wh_bag_requisition_details')->select('wh_bag_requisition_details.*,ld.nameE as where_use_name,list_data.nameE as unit_name,wh_bag.box_qty,wh_bag.carton_qty')       
            ->join('wh_bag', 'wh_bag.id=wh_bag_requisition_details.item_id','left')
            ->join('list_data', 'list_data.id=wh_bag.unit_id','left')
            ->join('list_data ld', 'ld.id=wh_bag.where_use','left')
            ->where( array('wh_bag_requisition_details.purchase_id'=>$purchase_id) )
            ->get()
            ->getResultArray();

        return $data;
    }

    public function bdtaskt1m12_08_getWarehouseItemStock($item_id, $store_id){
        $where = array('s.item_id'=>$item_id, 's.store_id'=>$store_id );
        $result = $this->db->table('wh_bag_stock s')->select('s.stock')->where($where)->get()->getRow();

        if( !empty($result) ){
            return floatval($result->stock);
        }
        return 0;
    }

    public function bdtaskt1m12_09_getSubWarehouseItemStock($item_id, $branch_id){
        $where = array('s.item_id'=>$item_id, 'w.branch_id'=>$branch_id );
        $result = $this->db->table('wh_machine_stock s')
        ->select('SUM(s.stock) as stock')
        ->join('wh_machine_store w', 'w.id=s.sub_store_id','left')
        ->where($where)
        ->get()
        ->getRow();

        if( !empty($result) ){
            return floatval($result->stock);
        }
        return 0;
    }

    public function bdtaskt1m12_10_getConsumed30($item_id, $branch_id){
        $where = array('DATEDIFF(CURDATE(), r.approved_date) <='=>30, 'r.status'=>1, 'r.isApproved'=>1, 'r.isCollected'=>1, 's.item_id'=>$item_id, 'w.branch_id'=>$branch_id );

        $result = $this->db->table('wh_order_details s')
        ->select('SUM(s.aqty-s.return_qty) as used')
        ->join('wh_order r', 'r.id=s.request_id','left')
        ->join('wh_machine_store w', 'w.id=r.sub_store_id','left')
        ->where($where)
        ->get()
        ->getRow();

        if( !empty($result) ){
            return floatval($result->used);
        }
        return 0;
    }

    public function bdtaskt1m12_11_getConsumed90($item_id, $branch_id){
        $where = array('DATEDIFF(CURDATE(), r.approved_date) <='=>90, 'r.status'=>1, 'r.isApproved'=>1, 'r.isCollected'=>1, 's.item_id'=>$item_id, 'w.branch_id'=>$branch_id );

        $result = $this->db->table('wh_order_details s')
        ->select('SUM(s.aqty-s.return_qty) as used')
        ->join('wh_order r', 'r.id=s.request_id','left')
        ->join('wh_machine_store w', 'w.id=r.sub_store_id','left')
        ->where($where)
        ->get()
        ->getRow();

        if( !empty($result) ){
            return floatval($result->used/3);
        }
        return 0;
    }

}
?>