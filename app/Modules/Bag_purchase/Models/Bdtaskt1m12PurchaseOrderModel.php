<?php namespace App\Modules\Bag_purchase\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m12PurchaseOrderModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasCreateAccess = $this->permission->method('wh_bag_purchase', 'create')->access();
        $this->hasReadAccess   = $this->permission->method('wh_bag_purchase', 'read')->access();
        $this->hasUpdateAccess = $this->permission->method('wh_bag_purchase', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('wh_bag_purchase', 'delete')->access();

    }

    public function bdtaskt1m12_02_getAll($postData=null){
        $response = array();
        ## Read value
      
        @$store_id = $postData['store_id'];
        @$supplier_id = $postData['supplier_id'];
        @$item_id = $postData['item_id'];
        @$voucher_no = trim($postData['voucher_no']);
        @$spr_no = trim($postData['spr_no']);
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
           $searchQuery = " (wh_bag_purchase.voucher_no like '%".$searchValue."%' OR wh_bag_purchase.date like '%".$searchValue."%' ) ";
        }
        if($store_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_bag_purchase.store_id = '".$store_id."' ) ";
        }
        if($supplier_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_bag_purchase.supplier_id = '".$supplier_id."' ) ";
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
           $searchQuery .= " ( wh_bag_purchase.id = '".$voucher_no."' ) ";
        }
        if($spr_no != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wmr.id = '".$spr_no."' ) ";
        }
        if($date != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_bag_purchase.date = '".$date."' ) ";
        }
        ## Fetch records
        $builder3 = $this->db->table('wh_bag_purchase');
        $builder3->select("wh_bag_purchase.*, wmr.voucher_no as r_voucher, wq.id as q_id, wh_bag_supplier_information.nameE as supplier_name, GROUP_CONCAT(i.company_code) as item_codes, GROUP_CONCAT(i.nameE) as item_names");
        $builder3->join('wh_bag_purchase_details pd', 'pd.purchase_id=wh_bag_purchase.id','left');
        $builder3->join('wh_bag i', 'i.id=pd.item_id','left');
        $builder3->join('wh_bag_quatation wq', 'wq.id=wh_bag_purchase.quatation_id','left');
        $builder3->join('wh_bag_supplier_information', 'wh_bag_supplier_information.id=wq.supplier_id','left');
        $builder3->join('wh_bag_requisition wmr', 'wmr.id=wq.requisition_id','left');
        $builder3->where('wmr.type !=',2);
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }         
         

        $builder3->groupBy('wh_bag_purchase.id');
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
            $receive_status = '';

            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="'.$details.'" data-id="'.$record['id'].'"><i class="fa fa-eye"></i></a>';
            
            if($record['isApproved']==1){
                $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-violet-soft btnC actionPrintPreview mr-2 custool" title="Print" data-id="'.$record['id'].'"><i class="fa fa-print"></i></a>';
                
                $status .= '<div class="badge badge-success" >'.get_phrases(['purchased']).'</div>';
                
                
                if($record['received'] ==1 ){
                    $receive_status .= ' <div class="badge badge-primary" >'.get_phrases(['received']).'</div>';
                } elseif($record['received'] ==2 ){
                    $receive_status .= ' <div class="badge badge-warning text-white" >'.get_phrases(['partialy', 'received']).'</div>';
                } else {
                    $receive_status .= ' <div class="badge badge-info" >'.get_phrases(['not','received']).'</div>';
                }
            } else {
                $status .= '<div class="badge badge-info">'.get_phrases(['not','purchased']).'</div>';
                $receive_status .= ' <div class="badge badge-info" >'.get_phrases(['not','received']).'</div>';

                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC actionApprove mr-2 custool" title="'.$approve.'" data-id="'.$record['id'].'"><i class="fa fa-check"></i></a>';
                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
                $button .= (!$this->hasDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete mr-2 custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';
            }

            $data[] = array( 
                'id'                => $record['id'],
                'voucher_no'        => $record['voucher_no'],
                'r_voucher'         => $record['r_voucher'],
                'quatation_id'      => $record['q_id'],
                'date'              => $record['date'],
                'store_name'        => '',
                'supplier_name'     => $record['supplier_name'],
                'item_codes'        => ($record['id'] == 1)?'':'<span class="custool" title="'.$record['item_names'].'">'.$record['item_codes'].'</span>',
                'grand_total'       => $record['grand_total'],
                'receive_status'    => $receive_status,
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
        $data = $this->db->table('wh_bag_purchase')->select('wh_bag_purchase.*, wmr.voucher_no as r_voucher, wq.id as q_id, 
            DATE_FORMAT(wh_bag_purchase.date, "%d/%m/%Y") as date, wh_bag_store.nameE as store_name, wh_bag_supplier_information.nameE as supplier_name,
            wh_bag_supplier_information.address as supplier_address,wh_bag_receive.grand_total as receive_grand_total, a_user.fullname,emp.signature_file as signature,a_terms.terms_conditions')                        
            ->join('wh_bag_store', 'wh_bag_store.id=wh_bag_purchase.store_id','left')
            ->join('wh_bag_receive', 'wh_bag_receive.purchase_id=wh_bag_purchase.id','left')
            ->join('wh_bag_quatation wq', 'wq.id=wh_bag_purchase.quatation_id','left')
            ->join('wh_bag_supplier_information', 'wh_bag_supplier_information.id=wq.supplier_id','left')
            ->join('wh_bag_requisition wmr', 'wmr.id=wq.requisition_id','left')
            ->join('user a_user', 'a_user.id=wh_bag_purchase.approved_by','left')
            ->join('hrm_employees emp', 'emp.employee_id=a_user.emp_id','left')
            ->join('wh_material_terms_conditions a_terms', 'a_terms.id=wh_bag_purchase.terms_conditions_id','left')
            ->where( array('wh_bag_purchase.id'=>$id) )
            ->get()
            ->getRowArray();

        return $data;
    }

    public function bdtaskt1m12_04_updateStock($qty, $where){
        $result = $this->db->table('wh_bag_stock')->set('stock', 'stock+'.$qty, FALSE)->set('stock_in', 'stock_in+'.$qty, FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_05_get_item_list($supplier_id){
        $data = $this->db->table('wh_bag')->select('wh_bag.*')        
            ->get()
            ->getResult();

        return $data;
    }

    public function bdtaskt1m12_06_getSupplierItemDetailsById($item_id, $supplier_id){

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

    public function bdtaskt1m12_07_getPurchaseOrderItemDetailsById($purchase_id, $supplier_id){

        $data = $this->db->table('wh_bag_purchase_details')->select('wh_bag_purchase_details.*,list_data.nameE as unit_name,wh_bag.box_qty,wh_bag.carton_qty')       
            ->join('wh_bag', 'wh_bag.id=wh_bag_purchase_details.item_id','left')
            ->join('list_data', 'list_data.id=wh_bag.unit_id','left')
            ->where( array('wh_bag_purchase_details.purchase_id'=>$purchase_id) )
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