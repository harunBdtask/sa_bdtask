<?php namespace App\Modules\Foreign_purchase\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m12ItemReceiveModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('wh_foreign_purchase_receive', 'read')->access();
        $this->hasUpdateAccess = $this->permission->method('wh_foreign_purchase_receive', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('wh_foreign_purchase_receive', 'delete')->access();

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
           $searchQuery = " (ah_po.po_code like '%".$searchValue."%' OR ah_po.po_date like '%".$searchValue."%' ) ";
        }
       
        if($supplier_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( ah_po.po_supplier_id = '".$supplier_id."' ) ";
        }

        if($voucher_no != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( ah_po.po_code = '".$voucher_no."' ) ";
        }
        if($date != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( ah_po.po_date = '".$date."' ) ";
        }
       
        ## Fetch records
        $builder3 = $this->db->table('ah_po');
        $builder3->select("ah_po.*, ah_supplier_information.nameE as supplier_name");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }                 
        $builder3->join('ah_supplier_information', 'ah_supplier_information.id=ah_po.po_supplier_id','left');
        // $builder3->join('wh_material_receive', 'wh_material_receive.purchase_id=wh_material_purchase.id','left');
        $builder3->where('ah_po.isApproved', 1);
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
            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="'.$details.'" data-id="'.$record['row_id'].'"><i class="far fa-eye"></i></a>';

            if( $record['received'] != 1 ){
                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['row_id'].'"><i class="far fa-plus-square"></i></a>';
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
                'id'                => $record['row_id'],
                'po_code'        => $record['po_code'],
                'po_date'              => $record['po_date'],
                'supplier_name' => $record['supplier_name'],
                'grand_total'       => $record['po_grand_total'],
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

        $data = $this->db->table('ah_po')->select('ah_po.*, DATE_FORMAT(ah_po.po_date, "%d/%m/%Y") po_date, s.nameE as supplier_name')     
            ->join('ah_supplier_information s', 's.id=ah_po.po_supplier_id','left')
            ->where( array('ah_po.row_id'=>$id) )
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


}
?>