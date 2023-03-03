<?php namespace App\Modules\Foreign_purchase\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class BdtaskttPurchaseOrderModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess   = $this->permission->method('wh_foreign_purchase_order', 'read')->access();
        $this->hasUpdateAccess = $this->permission->method('wh_foreign_purchase_order', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('wh_foreign_purchase_order', 'delete')->access();

    }

    public function bdtasktt_ahpo_getAll($postData=null){

        $response = array();
        ## Read value
      
        @$supplier_id = $postData['supplier_id'];
        @$po_code = trim($postData['po_code']);
        @$date = $postData['po_date'];
        
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
           $searchQuery = " (ah_po.po_code like '%".$searchValue."%' OR ah_po.po_date like '%".$searchValue."%' ) ";
        }
       
        if($supplier_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( ah_po.op_supplier_id = '".$supplier_id."' ) ";
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

        $print_return = get_phrases(['print','return','invoice']);
        $receive_invoice = get_phrases(['view','receive','invoice']);
        $sl = 1;
        foreach($records as $record ){ 
            
            $button = '';
            $status ='';


            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="'.$details.'" data-id="'.$record['row_id'].'"><i class="fa fa-eye"></i></a>';
            
            if($record['isApproved']==1){

                $status .= '<div class="badge badge-success" >'.get_phrases(['approved']).'</div>';
                
                if($record['received'] ==1 ){
                    $status .= ' <div class="badge badge-primary" >'.get_phrases(['received']).'</div>';
                } elseif($record['received'] ==2 ){
                    $status .= ' <div class="badge badge-warning text-white" >'.get_phrases(['partialy', 'received']).'</div>';
                } else {
                    $status .= ' <div class="badge badge-info" >'.get_phrases(['not','received']).'</div>';
                }
            } else {

                $status .= '<div class="badge badge-info">'.get_phrases(['not','approved']).'</div>';

                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC actionApprove mr-2 custool" title="'.$approve.'" data-id="'.$record['row_id'].'"><i class="fa fa-check"></i></a>';
                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['row_id'].'"><i class="far fa-edit"></i></a>';
                $button .= (!$this->hasDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete mr-2 custool" title="'.$delete.'" data-id="'.$record['row_id'].'"><i class="far fa-trash-alt"></i></a>';
            }




            $data[] = array( 
                'id'                => $record['po_code'],
                'supplier_name'     => $record['supplier_name'],
                'lc_number'     => $record['lc_number'],
                'po_subtotal'     => $record['po_subtotal'],
                'po_grand_total'     => $record['po_grand_total'],
                'po_vat'     => $record['po_vat'],
                'po_date'           => $record['po_date'],
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
    
    
    public function bdtaskt1m12_03_getPurchaseOrderDetailsById($id){

        $builder3 = $this->db->table('ah_po');
        $builder3->select("ah_po.*, ah_supplier_information.nameE as supplier_name");
        $builder3->join('ah_supplier_information', 'ah_supplier_information.id=ah_po.po_supplier_id');
        $builder3->where( array('ah_po.row_id'=>$id) );
        $query3   =  $builder3->get();
        $records =   $query3->getRowArray();
        return $records;

    }


        
    public function bdtaskt1m12_03_getPurchaseItemDetailsById($id){

        $builder3 = $this->db->table('ah_po_details');
        $builder3->select("ah_po_details.*, ah_material.nameE as item_name");
        $builder3->join('ah_material', 'ah_material.id=ah_po_details.po_item_id');
        $builder3->where( array('ah_po_details.po_id'=>$id) );
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        return $records;

    }


    public function bdtasktt_getPurchaseItemDetailsById($id){

        $builder3 = $this->db->table('ah_po_details');
        $builder3->select("ah_po_details.*, ah_material.nameE as item_name,ah_material.vat_applicable,ah_material.unit_id,list_data.nameE as unit_name");
        $builder3->join('ah_material', 'ah_material.id=ah_po_details.po_item_id');
        $builder3->join('list_data', 'list_data.id=ah_material.unit_id','left');
        $builder3->where( array('ah_po_details.po_id'=>$id) );
        $query3   =  $builder3->get();
        $records =   $query3->getResult();
        return $records;

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
        $data = $this->db->table('wh_material_receive mr')->select('mr.*,mr.voucher_no as r_voucher, wh_material_purchase.voucher_no as po_voucher, wq.id as q_id, DATE_FORMAT(mr.date, "%d/%m/%Y") date, wh_material_store.nameE as store_name, s.nameE as supplier_name, DATE_FORMAT(mr.date, "%d/%m/%Y") as receive_date, s.agree_type, s.credit_limit, s.used_credit, s.credit_period, mr.id as receive_id')                        
            ->join('wh_material_purchase', 'wh_material_purchase.id=mr.purchase_id','left')
            ->join('wh_material_store', 'wh_material_store.id=mr.store_id','left')
            ->join('wh_quatation wq', 'wq.id=wh_material_purchase.quatation_id','left')
            ->join('wh_supplier_information s', 's.id=wq.supplier_id','left')
            ->where( array('mr.id'=>$id) )
            ->get()
            ->getRowArray();

        return $data;
    }


    public function bdtasktt_get_itemdetails($id){

        $data = $this->db->table('ah_material')->select('ah_material.*,unit.nameE as unit_name')
            ->join('list_data unit','unit.id=ah_material.unit_id')                        
            ->where( array('ah_material.id'=>$id) )
            ->get()
            ->getRowArray();

        return $data;
    }


}
?>