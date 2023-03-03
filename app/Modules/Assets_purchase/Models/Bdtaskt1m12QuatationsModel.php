<?php namespace App\Modules\Assets_purchase\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m12QuatationsModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('wh_bag_quatation', 'read')->access();
        $this->hasCreateAccess = $this->permission->method('wh_bag_quatation', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('wh_bag_quatation', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('wh_bag_quatation', 'delete')->access();

    }

    public function bdtaskt1m12_03_getPurchaseOrderDetailsById($id){
        $data = $this->db->table('wh_bag_quatation')->select('wh_bag_quatation.*, DATE_FORMAT(wh_bag_quatation.date, "%d/%m/%Y") as date, spr.id as spr_id, spr.voucher_no as spr_no, 
            s.nameE as s_name, s.code_no as s_code')                        
            ->join('wh_bag_requisition spr', 'spr.id=wh_bag_quatation.requisition_id','left')    
            ->join('wh_bag_supplier_information s', 's.id=wh_bag_quatation.supplier_id','left')
            ->where( array('wh_bag_quatation.id'=>$id,'spr.type'=>2) )
            ->get()
            ->getRowArray();
        return $data;
    }

    public function bdtaskt1m12_03_get_item_list($spr_id){
        $data = $this->db->table('wh_assets')->select('wh_assets.*')        
            ->join('wh_bag_requisition_details spr_details', 'wh_assets.id=spr_details.item_id','left')
            ->where( array('spr_details.purchase_id'=>$spr_id) )
            ->get()
            ->getResult();

        return $data;
    }


    public function bdtaskt1m12_02_getAll($postData=null){
        $response = array();
        ## Read value
      
        @$draw = $postData['draw'];
        @$start = $postData['start'];
        @$rowperpage = $postData['length']; // Rows display per page
        @$columnIndex = $postData['order'][0]['column']; // Column index
        @$columnName = $postData['columns'][$columnIndex]['data']; // Column name
        @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        @$searchValue = $postData['search']['value']; // Search value
        ## Search 
        @$date = $postData['date'];
        @$spr = $postData['spr'];
        $searchQuery = "";
        if($searchValue != ''){
           $searchQuery = " (mr.voucher_no like '%".$searchValue."%' OR si.nameE like '%".$searchValue."%' ) ";
        }

        if($date != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_bag_quatation.date = '".$date."' ) ";
        }

        if($spr != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_bag_quatation.requisition_id = '".$spr."' ) ";
        }
        
        ## Fetch records
        $builder3 = $this->db->table('wh_bag_quatation');
        $builder3->select("wh_bag_quatation.*, mr.voucher_no, mr.isApproved as spr_isApproved, si.nameE as s_nameE, si.code_no");
        $builder3->join('wh_bag_requisition mr', 'mr.id=wh_bag_quatation.requisition_id','left');
        $builder3->join('wh_bag_supplier_information si', 'si.id=wh_bag_quatation.supplier_id','left');
        $builder3->where( array('mr.type'=>2) );
        if($searchQuery != ''){
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
        $approve = get_phrases(['approve']);
        $details = get_phrases(['view', 'details']);
        foreach($records as $record ){ 
            $button = '';

            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2" data-id="'.$record['id'].'"><i class="far fa-eye"></i></a>';
            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-violet-soft btnC actionPrintPreview mr-2 custool" title="Print" data-id="'.$record['id'].'"><i class="fa fa-print"></i></a>';
                
            if ($record['isApproved'] == 0) {
                // $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC actionApprove mr-2 custool" title="'.$approve.'" data-id="'.$record['id'].'"><i class="fa fa-check"></i></a>';
                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
                $button .= (!$this->hasDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';
                $status = ' <div class="badge badge-info" >'.get_phrases(['pending']).'</div>';
            }elseif ($record['isApproved'] == 1) {
                $status = ' <div class="badge badge-success" >'.get_phrases(['approved']).'</div>';
                
                // $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview2 mr-2 custool" title="'.$details.'" data-id="'.$record['mp_id'].'"><i class="fa fa-check"></i></a>';

            }elseif ($record['isApproved'] == 2) {
                $status = ' <div class="badge badge-warning" >'.get_phrases(['partialy', 'approved']).'</div>';
            }else{
                $status = ' <div class="badge badge-danger" >'.get_phrases(['expired']).'</div>';
            }
            $data[] = array( 
                'id'            => $record['id'],
                'voucher_no'    => $record['voucher_no'],
                'code_no'       => $record['s_nameE'] .' ( '. $record['code_no'] .' )' ,
                'date'          => $record['date'],
                'status'        => $status,
                'button'        => $button
            ); 
        }

        // $data = [];

        ## Response
        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData"               => $data
        );
        return $response; 
    }
    
    public function bdtaskt1m12_03_getCategoryDetailsById($id){
        $data = $this->db->table('wh_bag_quatation')
                        ->select("wh_bag_quatation.*, mr.voucher_no, si.nameE as s_nameE, si.code_no")
                        ->join('wh_bag_requisition mr', 'mr.id=wh_bag_quatation.requisition_id','left')
                        ->join('wh_bag_supplier_information si', 'si.id=wh_bag_quatation.supplier_id','left')
                        ->where( array('wh_bag_quatation.id'=>$id,'mr.type'=>2) )
                        ->get()
                        ->getRowArray();

        return $data;
    }
}
?>