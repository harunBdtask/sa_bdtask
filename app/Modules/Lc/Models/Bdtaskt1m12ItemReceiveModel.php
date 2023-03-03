<?php namespace App\Modules\Lc\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m12ItemReceiveModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('wh_material_receive', 'read')->access();
        $this->hasUpdateAccess = $this->permission->method('wh_material_receive', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('wh_material_receive', 'delete')->access();

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
           $searchQuery = " (wh_material_purchase.voucher_no like '%".$searchValue."%' OR wh_material_purchase.date like '%".$searchValue."%' ) ";
        }
        if($store_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_material_purchase.store_id = '".$store_id."' ) ";
        }
        if($supplier_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_material_purchase.supplier_id = '".$supplier_id."' ) ";
        }
        if($voucher_no != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_material_purchase.voucher_no = '".$voucher_no."' ) ";
        }
        if($date != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_material_purchase.date = '".$date."' ) ";
        }
       
        ## Fetch records
        $builder3 = $this->db->table('wh_material_purchase');
        $builder3->select("wh_material_purchase.*, wh_supplier_information.nameE as supplier_name, lc.row_id as lc_id, lc.lc_number as spr");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }
        $builder3->join('wh_supplier_information', 'wh_supplier_information.id=wh_material_purchase.supplier_id','left');
        $builder3->join('ah_lc lc', 'lc.purchase_id=wh_material_purchase.id','left');
        $builder3->where('wh_material_purchase.type', 'lc');
        $builder3->where('wh_material_purchase.isApproved', '1');
        
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
                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'" ><i class="far fa-plus-square"></i></a>';
                
            }
            if($record['received']==1){
                $status = '<div class="badge badge-success" >'.get_phrases(['received']).'</div> ';
                
            } else if($record['received']==2){
                $status = '<div class="badge badge-warning" >'.get_phrases(['partialy', 'received']).'</div> ';
            } else {
                $status = '<div class="badge badge-info" >'.get_phrases(['not','received']).'</div>';
            }

            $data[] = array( 
                'id'                => $record['id'],
                'voucher_no'        => $record['voucher_no'],
                'date'              => $record['date'],
                'spr'               => $record['spr'],
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
        $data = $this->db->table('wh_material_purchase')->select('wh_material_purchase.*, lc.row_id as lc_id, wh_material_purchase.vat as po_vat, DATE_FORMAT(wh_material_purchase.date, "%d/%m/%Y") date, wh_material_store.nameE as store_name, s.nameE as supplier_name, s.code_no as supplier_code, wh_material_receive.grand_total as receive_grand_total, wh_material_receive.sub_total as receive_sub_total, wh_material_receive.voucher_no as receive_voucher_no, wh_material_receive.receipt, wh_material_receive.due, wh_material_receive.vat, DATE_FORMAT(wh_material_receive.date, "%d/%m/%Y") as receive_date, s.agree_type, s.credit_limit, s.used_credit, s.credit_period, wh_material_receive.id as receive_id')                        
            ->join('wh_material_store', 'wh_material_store.id=wh_material_purchase.store_id','left')
            ->join('wh_material_receive', 'wh_material_receive.purchase_id=wh_material_purchase.id','left')
            ->join('ah_lc lc', 'lc.purchase_id=wh_material_purchase.id','left')
            ->join('wh_supplier_information s', 's.id=wh_material_purchase.supplier_id','left')
            ->where( array('wh_material_purchase.id'=>$id) )
            ->get()
            ->getRowArray();

        return $data;
    }

    public function bdtaskt1m12_04_updateStock($qty, $where){
        $result = $this->db->table('wh_material_stock')->set('stock', 'stock+'.$qty, FALSE)->set('stock_in', 'stock_in+'.$qty, FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_04_updateReturn($qty, $where){
        $result = $this->db->table('wh_material_receive_details')->set('return_qty', 'return_qty+'.$qty, FALSE)->set('avail_qty', 'avail_qty-'.$qty, FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_05_increaseStock($qty, $where){
        $result = $this->db->table('wh_material_stock')->set('stock', 'stock+'.$qty, FALSE)->set('stock_in', 'stock_in+'.$qty, FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_06_decreaseStock($qty, $where){
        $result = $this->db->table('wh_material_stock')->set('stock', 'stock-'.$qty, FALSE)->set('stock_out', 'stock_out+'.$qty, FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_07_updateSupplierCredit($amount, $where){
        $result = $this->db->table('wh_supplier_information')->set('used_credit', 'used_credit+'.$amount, FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_08_checkAvailQty($purchase_id, $item_id, $return_qty){
        $where = array('purchase_id'=> $purchase_id, 'item_id'=> $item_id);
        $result = $this->db->table('wh_material_receive_details')->where($where)->where(" (avail_qty - adjust_in) < ".$return_qty)->get()->getRow();

        return $result;
    }

    public function bdtaskt1m12_03_getPOList(){
        $data = $this->db->table('wh_material_purchase a')->select('a.id, a.voucher_no as text')        
            ->where(array('a.received != '=>1,'a.isApproved'=>1,'a.type'=>'lc'))
            ->get()
            ->getResult();

        return $data;
    }



}
?>