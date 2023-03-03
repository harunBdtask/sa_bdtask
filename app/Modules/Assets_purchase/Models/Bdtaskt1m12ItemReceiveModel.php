<?php namespace App\Modules\Assets_purchase\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m12ItemReceiveModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('wh_bag_receive', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('wh_bag_receive', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('wh_bag_receive', 'update')->access();
        //$this->hasDeleteAccess = $this->permission->method('wh_bag_receive', 'delete')->access();

    }

    public function bdtaskt1m12_02_getAll($postData=null){
        $response = array();
        ## Read value
      
        @$store_id = $postData['store_id'];
        @$supplier_id = $postData['supplier_id'];
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
        if($voucher_no != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_bag_purchase.voucher_no = '".$voucher_no."' ) ";
        }
        if($date != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_bag_purchase.date = '".$date."' ) ";
        }
       
        ## Fetch records
        $builder3 = $this->db->table('wh_bag_purchase');
        $builder3->select("wh_bag_purchase.*, wh_bag_supplier_information.nameE as supplier_name, requisition.voucher_no as spr");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }                 
        // $builder3->join('wh_bag_store', 'wh_bag_store.id=wh_bag_purchase.store_id','left');
        $builder3->join('wh_bag_supplier_information', 'wh_bag_supplier_information.id=wh_bag_purchase.supplier_id','left');
        $builder3->join('wh_bag_requisition requisition', 'requisition.id=wh_bag_purchase.requisition_id','left');
        $builder3->where( array('wh_bag_purchase.isApproved'=>1,'requisition.type'=>2) );
        
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
        $update = get_phrases(['receive','item']);
        $return = get_phrases(['return','item']);
        $approve = get_phrases(['approve']);
        $delete = get_phrases(['delete']);
        $print = get_phrases(['print','preview']);

        foreach($records as $record ){ 
            $button = '';

            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="'.$details.'" data-id="'.$record['id'].'"><i class="far fa-eye"></i></a>';

            if( $record['received'] != 1 ){
                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-plus-square"></i></a>';
                //$button .='<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete mr-2 custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';
            }elseif( !$record['returned'] ){
                // $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionReturn custool" title="'.$return.'" data-id="'.$record['id'].'"><i class="fa fa-undo"></i></a>';
            }elseif ($record['received'] == 1) {
                // $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC actionApprove mr-2 custool" title="'.$approve.'" data-id="'.$record['id'].'"><i class="fa fa-check"></i></a>';
            }
            if($record['received']==1){
                $status = '<div class="badge badge-success" >'.get_phrases(['received']).'</div> ';
                if($record['isApproved']==1){
                    $status .= '<div class="badge badge-primary" >'.get_phrases(['approved']).'</div> ';
                    if($record['returned']==1){
                        $status .= '<div class="badge badge-secondary" >'.get_phrases(['returned']).'</div>';
                    }
                } else {
                    $status .= '<div class="badge badge-info">'.get_phrases(['not','approved']).'</div> ';
                }
            } else if($record['received']==2){
                $status = '<div class="badge badge-warning" >'.get_phrases(['partialy', 'received']).'</div> ';
            } else {
                $status = '<div class="badge badge-info">'.get_phrases(['not','received']).'</div>';
            }

            $data[] = array( 
                'id'                => $record['id'],
                'voucher_no'        => $record['voucher_no'],
                'date'              => $record['date'],
                'spr'        => $record['spr'],
                'supplier_name'     => $record['supplier_name'],
                'grand_total'       => $record['grand_total'],
                'status'            => $status,
                'button'            => $button,
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

    
    
    public function bdtaskt1m12_03_getItemReceiveDetailsById($id){
        $data = $this->db->table('wh_bag_purchase')->select('wh_bag_purchase.*,wmr.voucher_no as r_voucher, wq.id as q_id,wh_bag_purchase.vat as po_vat, DATE_FORMAT(wh_bag_purchase.date, "%d/%m/%Y") date, wh_bag_store.nameE as store_name, s.nameE as supplier_name, wh_bag_receive.grand_total as receive_grand_total, wh_bag_receive.sub_total as receive_sub_total, wh_bag_receive.voucher_no as receive_voucher_no, wh_bag_receive.receipt, wh_bag_receive.due, wh_bag_receive.vat, DATE_FORMAT(wh_bag_receive.date, "%d/%m/%Y") as receive_date, s.agree_type, s.credit_limit, s.used_credit, s.credit_period, wh_bag_receive.id as receive_id')                        
            ->join('wh_bag_store', 'wh_bag_store.id=wh_bag_purchase.store_id','left')
            ->join('wh_bag_receive', 'wh_bag_receive.purchase_id=wh_bag_purchase.id','left')
            ->join('wh_bag_quatation wq', 'wq.id=wh_bag_purchase.quatation_id','left')
            ->join('wh_bag_supplier_information s', 's.id=wq.supplier_id','left')
            ->join('wh_bag_requisition wmr', 'wmr.id=wq.requisition_id','left')
            ->where( array('wh_bag_purchase.id'=>$id,'wmr.type'=>2) )
            ->get()
            ->getRowArray();

        return $data;
    }

    public function bdtaskt1m12_04_updateStock($qty, $where){
        $result = $this->db->table('wh_bag_stock')->set('stock', 'stock+'.$qty, FALSE)->set('stock_in', 'stock_in+'.$qty, FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_04_updateReturn($qty, $where){
        $result = $this->db->table('wh_bag_receive_details')->set('return_qty', 'return_qty+'.$qty, FALSE)->set('avail_qty', 'avail_qty-'.$qty, FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_05_increaseStock($qty, $where){
        $result = $this->db->table('wh_bag_stock')->set('stock', 'stock+'.$qty, FALSE)->set('stock_in', 'stock_in+'.$qty, FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_06_decreaseStock($qty, $where){
        $result = $this->db->table('wh_bag_stock')->set('stock', 'stock-'.$qty, FALSE)->set('stock_out', 'stock_out+'.$qty, FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_07_updateSupplierCredit($amount, $where){
        $result = $this->db->table('wh_bag_supplier_information')->set('used_credit', 'used_credit+'.$amount, FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_08_checkAvailQty($purchase_id, $item_id, $return_qty){
        $where = array('purchase_id'=> $purchase_id, 'item_id'=> $item_id);
        $result = $this->db->table('wh_bag_receive_details')->where($where)->where(" (avail_qty - adjust_in) < ".$return_qty)->get()->getRow();

        return $result;
    }

    public function bdtaskt1m12_09_getPOList(){
        $data = $this->db->table('wh_bag_purchase a')->select('a.id, a.voucher_no as text')  
            ->join('wh_bag_requisition spr', 'spr.id=a.requisition_id','left')      
            ->where(array('a.received != '=>1,'a.isApproved'=>1,'spr.type'=>2))
            ->get()
            ->getResult();

        return $data;
    }


}
?>