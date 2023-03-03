<?php namespace App\Modules\Foreign_purchase\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m12ItemPurchaseModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('wh_foreign_purchase_received_voucher', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('wh_print_invoice', 'create')->access();
        //$this->hasUpdateAccess = $this->permission->method('wh_print_invoice', 'update')->access();
        //$this->hasDeleteAccess = $this->permission->method('wh_print_invoice', 'delete')->access();

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
        if (!empty($columnName) && $columnName=='sl') {
            $columnName='id';
        }
        @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        @$searchValue = $postData['search']['value']; // Search value
        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
           $searchQuery = " (ah_material_receive.voucher_no like '%".$searchValue."%' OR ah_material_receive.date like '%".$searchValue."%' ) ";
        }
        if($store_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( ah_material_receive.store_id = '".$store_id."' ) ";
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
           $searchQuery .= " ( ah_material_receive.voucher_no = '".$voucher_no."' ) ";
        }
        if($date != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( ah_material_receive.date = '".$date."' ) ";
        }
        
        ## Fetch records
        $builder3 = $this->db->table('ah_material_receive');
        $builder3->select("ah_material_receive.*, ah_supplier_information.nameE as supplier_name,ah_po.received,ah_po.returned,ah_po.po_code as po");
        
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }                 
        // $builder3->join('wh_material_store', 'wh_material_store.id=ah_material_receive.store_id','left');
        $builder3->join('ah_po', 'ah_po.row_id=ah_material_receive.po_id','left');
        $builder3->join('ah_supplier_information', 'ah_supplier_information.id=ah_po.po_supplier_id','left');
        // $builder3->where('wh_material_purchase.received',1);
              

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
        
        //$details = get_phrases(['view', 'details']);
        //$update = get_phrases(['update']);
        //$delete = get_phrases(['delete']);
        $print = get_phrases(['print','purchase','invoice']);
        $print_return = get_phrases(['print','return','invoice']);
        $receive_invoice = get_phrases(['view','receive','invoice']);
        $sl = 1;
        foreach($records as $record ){ 
            
            $button = '';

            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-violet-soft btnC actionPreview mr-2 custool" title="'.$print.'" data-id="'.$record['rv_id'].'"><i class="fa fa-print"></i></a>';
            
            /*$button .='<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .='<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';*/
            
            if($record['status']==1){
                $status = '<div class="badge badge-success" >'.get_phrases(['received']).'</div> ';
                // $status .= ' <div class="badge badge-primary" >'.get_phrases(['approved']).'</div>';

            } else {
                $status = '<div class="badge badge-info text-white">'.get_phrases(['not','received']).'</div>';
            }

            if( $record['receive_invoice'] !='' ){
                $button .= (!$this->hasReadAccess)?'':'<a href="'.base_url().$record['receive_invoice'].'" class="btn btn-info-soft btnC mr-2 custool" title="'.$receive_invoice.'" target="_blank"><i class="fa fa-file"></i></a>';
            }

            $data[] = array( 
                'id'                => $record['rv_id'],
                'sl'                => $sl,
                'voucher_no'        => $record['voucher_no'],
                'date'              => $record['date'],
                'po'                => $record['po'],
                'supplier_name'     => $record['supplier_name'],
                'status'            => $status,
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
    

    public function bdtaskt1m12_03_getItemPurchaseDetailsById($id){
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

    public function bdtaskt1m12_05_getItemReturnDetailsById($id){
        $data = $this->db->table('wh_material_requisition')->select('wh_material_requisition.*,wh_material_requisition.vat as po_vat, DATE_FORMAT(wh_material_requisition.date, "%d/%m/%Y") date, wh_material_store.nameE as store_name, s.nameE as supplier_name, wh_material_return.voucher_no as return_voucher_no, wh_material_return.grand_total as return_grand_total, DATE_FORMAT(wh_material_return.date, "%d/%m/%Y") as return_date, wh_material_return.sub_total as return_sub_total, wh_material_return.vat as return_vat')                        
            ->join('wh_material_store', 'wh_material_store.id=wh_material_requisition.store_id','left')
            ->join('wh_supplier_information s', 's.id=wh_material_requisition.supplier_id','left')
            ->join('wh_material_return', 'wh_material_return.purchase_id=wh_material_requisition.id','left')
            ->where( array('wh_material_requisition.id'=>$id) )
            ->get()
            ->getRowArray();

        return $data;
    }

    public function bdtaskt1m12_03_getItemReceiveDetailsById($id){

        $data = $this->db->table('ah_material_receive mr')->select('mr.*,mr.voucher_no as r_voucher, ah_po.po_code as po_voucher, ah_material_store.nameE as store_name, s.nameE as supplier_name, DATE_FORMAT(mr.date, "%d/%m/%Y") as receive_date, mr.rv_id as receive_id')                        
            ->join('ah_po', 'ah_po.row_id=mr.po_id','left')
            ->join('ah_material_store', 'ah_material_store.id=mr.store_id','left')
            ->join('ah_supplier_information s', 's.id=ah_po.po_supplier_id','left')
            ->where( array('mr.rv_id'=>$id) )
            ->get()
            ->getRowArray();
        return $data;
    }


}
?>