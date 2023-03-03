<?php namespace App\Modules\Purchase\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m12RequisitionModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasCreateAccess = $this->permission->method('wh_material_requisition', 'create')->access();
        $this->hasReadAccess   = $this->permission->method('wh_material_requisition', 'read')->access();
        $this->hasUpdateAccess = $this->permission->method('wh_material_requisition', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('wh_material_requisition', 'delete')->access();

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
           $searchQuery = " (wh_material_requisition.voucher_no like '%".$searchValue."%' OR wh_material_requisition.date like '%".$searchValue."%' ) ";
        }
        if($store_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_material_requisition.store_id = '".$store_id."' ) ";
        }
        if($supplier_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_material_requisition.supplier_id = '".$supplier_id."' ) ";
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
           $searchQuery .= " ( wh_material_requisition.id = '".$voucher_no."' ) ";
        }
        if($date != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_material_requisition.date = '".$date."' ) ";
        }
        ## Fetch records
        $builder3 = $this->db->table('wh_material_requisition');
        $builder3->select("wh_material_requisition.*, wh_material_store.nameE as store_name, wh_supplier_information.nameE as supplier_name, GROUP_CONCAT(i.company_code) as item_codes, GROUP_CONCAT(i.nameE) as item_names");
        $builder3->join('wh_material_store', 'wh_material_store.id=wh_material_requisition.store_id','left');
        $builder3->join('wh_supplier_information', 'wh_supplier_information.id=wh_material_requisition.supplier_id','left');
        $builder3->join('wh_material_requisition_details pd', 'pd.purchase_id=wh_material_requisition.id','left');
        $builder3->join('wh_material i', 'i.id=pd.item_id','left');
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }         
        // if(session('branchId') >0 ){
        //     $builder3->where("wh_material_store.branch_id", session('branchId'));
        // }   

        $builder3->groupBy('wh_material_requisition.id');
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
        $sl = 1;

        foreach($records as $record ){ 
            $button = '';
            $status = '';
            $purchase_status = '';

            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="'.$details.'" data-id="'.$record['id'].'"><i class="fa fa-eye"></i></a>';
            
            if($record['isApproved']==1){
                $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC quotationCSModal mr-2 custool '.$postData['csbtn'].'" title="Quotation CS" data-id="'.$record['id'].'"><i class="fa fa-eye"></i></a>';
                $status .= '<div class="badge badge-success" >'.get_phrases(['approved']).'</div>';
                
                if($record['received'] ==1 ){
                    $purchase_status .= ' <div class="badge badge-success" >'.get_phrases(['purchased']).'</div>';
                } else if($record['received']==2){
                    $purchase_status .= '<div class="badge badge-warning text-white" >'.get_phrases(['partialy', 'purchased']).'</div> ';
                } else {
                    $purchase_status .= ' <div class="badge badge-info" >'.get_phrases(['not','purchased']).'</div>';
                }
            } else if($record['isApproved']==2){
                $status .= '<div class="badge badge-warning text-white" >'.get_phrases(['partialy', 'approved']).'</div> ';
                if($record['received'] ==1 ){
                    $purchase_status .= ' <div class="badge badge-success" >'.get_phrases(['purchased']).'</div>';
                } else if($record['received']==2){
                    $purchase_status .= '<div class="badge badge-warning text-white" >'.get_phrases(['partialy', 'purchased']).'</div> ';
                } else {
                    $purchase_status .= ' <div class="badge badge-info" >'.get_phrases(['not','purchased']).'</div>';
                }
            } else {
                $status .= '<div class="badge badge-info">'.get_phrases(['not','approved']).'</div>';
                $purchase_status .= ' <div class="badge badge-info" >'.get_phrases(['not','purchased']).'</div>';
                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC actionApprove mr-2 custool" title="'.$approve.'" data-id="'.$record['id'].'"><i class="fa fa-check"></i></a>';
                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
                $button .= (!$this->hasDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete mr-2 custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';
            }

            $data[] = array( 
                'id'                => $sl,
                'voucher_no'        => $record['voucher_no'],
                'date'              => $record['date'],
                'grand_total'       => $record['grand_total'],
                'status'            => $status,
                'purchase_status'   => $purchase_status,
                'button'            => $button
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
    
    public function bdtaskt1m12_03_getPurchaseOrderDetailsById($id){
        $data = $this->db->table('wh_material_requisition')->select('wh_material_requisition.*, DATE_FORMAT(wh_material_requisition.date, "%d/%m/%Y") as date, wh_material_store.nameE as store_name, wh_supplier_information.nameE as supplier_name,wh_material_receive.grand_total as receive_grand_total')                        
            ->join('wh_material_store', 'wh_material_store.id=wh_material_requisition.store_id','left')
            ->join('wh_supplier_information', 'wh_supplier_information.id=wh_material_requisition.supplier_id','left')
            ->join('wh_material_receive', 'wh_material_receive.purchase_id=wh_material_requisition.id','left')
            ->where( array('wh_material_requisition.id'=>$id) )
            ->get()
            ->getRowArray();

        return $data;
    }

    public function bdtaskt1m12_04_updateStock($qty, $where){
        $result = $this->db->table('wh_material_stock')->set('stock', 'stock+'.$qty, FALSE)->set('stock_in', 'stock_in+'.$qty, FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_05_get_item_list($supplier_id){
        $data = $this->db->table('wh_material')->select('wh_material.*')        
            // ->join('wh_material', 'wh_material.id=wh_material_supplier.item_id','left')
            // ->where( array('wh_material_supplier.supplier_id'=>$supplier_id) )
            ->get()
            ->getResult();

        return $data;
    }

    public function bdtaskt1m12_06_getSupplierItemDetailsById($item_id, $supplier_id){

        $data = $this->db->table('wh_material')->select('list_data.nameE as unit_name, ld.nameE as where_use_name, mr.date as last_purchase_date, mrd.qty as last_purchase_qty, wh_material.*, store.nameE as store_name')    
            ->join('wh_material_store  store', 'store.id=wh_material.store_id','left')
            ->join('list_data', 'list_data.id=wh_material.unit_id','left')
            ->join('list_data ld', 'ld.id=wh_material.where_use','left')
            ->join('wh_material_receive_details mrd', 'mrd.item_id=wh_material.id','left')
            ->join('wh_material_receive mr', 'mr.id=mrd.receive_id','left')
            ->where( array('wh_material.id'=>$item_id) )
            ->orderBy('mrd.id', 'desc')
            ->limit(1)
            
            ->get()
            ->getRowArray();

        return $data;
    }

    public function bdtaskt1m12_07_getPurchaseOrderItemDetailsById($purchase_id, $supplier_id=null){

        $data = $this->db->table('wh_material_requisition_details spr_details')->select('spr_details.*,ld.nameE as where_use_name,list_data.nameE as unit_name,wh_material.box_qty,wh_material.carton_qty, store.nameE as store_name')       
            ->join('wh_material', 'wh_material.id=spr_details.item_id','left')
            ->join('wh_material_store  store', 'store.id=wh_material.store_id','left')
            ->join('list_data', 'list_data.id=wh_material.unit_id','left')
            ->join('list_data ld', 'ld.id=wh_material.where_use','left')
            ->where( array('spr_details.purchase_id'=>$purchase_id) )
            ->get()
            ->getResultArray();

        return $data;
        // print_r($data);exit;
    }

    public function bdtaskt1m12_08_getWarehouseItemStock($item_id, $store_id){
        $where = array('s.item_id'=>$item_id, 's.store_id'=>$store_id );
        $result = $this->db->table('wh_material_stock s')->select('s.stock')->where($where)->get()->getRow();

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