<?php namespace App\Modules\Production\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1ProductionVoucherModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('production_voucher', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('production_voucher', 'create')->access();
        //$this->hasUpdateAccess = $this->permission->method('production_voucher', 'update')->access();
        //$this->hasDeleteAccess = $this->permission->method('production_voucher', 'delete')->access();

    }

    public function bdtaskt1m12_02_getAll($postData=null){
         $response = array();
         ## Read value
      
        @$store_id = $postData['store_id'];
        @$machine_id = $postData['machine_id'];
        @$voucher_no = trim($postData['voucher_no']);
        @$production_voucher_no = trim($postData['production_voucher_no']);
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
           $searchQuery = " (r.voucher_no like '%".$searchValue."%' OR r.date like '%".$searchValue."%' ) ";
        }
        if($store_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( r.store_id = '".$store_id."' ) ";
        }
        if($machine_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( r.machine_id = '".$machine_id."' ) ";
        }
        if($voucher_no != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( r.id = '".$voucher_no."' ) ";
        }
        if($production_voucher_no != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( p.id = '".$production_voucher_no."' ) ";
        }
        if($date != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( r.date = '".$date."' ) ";
        }
        
        ## Fetch records
        $builder3 = $this->db->table('wh_receive r');
        $builder3->select("r.*, ps.nameE as store_name, ms.nameE as machine_name,p.received,p.returned,p.voucher_no as plan_no");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }                 
        $builder3->join('wh_production_store ps', 'ps.id=r.store_id','left');
        $builder3->join('wh_machine_store ms', 'ms.id=r.machine_id','left');
        $builder3->join('wh_production p', 'p.id=r.production_id','left');
        $builder3->where('p.received',1);
                
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
        $print = get_phrases(['print','receive','invoice']);
        $print_return = get_phrases(['print','return','invoice']);
        $receive_invoice = get_phrases(['view','receive','invoice']);

        foreach($records as $record ){ 
            $button = '';
            $status = '';
            $received_status = '';

            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-violet-soft btnC actionPreview mr-2 custool" title="'.$print.'" data-id="'.$record['production_id'].'"><i class="fa fa-print"></i></a>';
            
            /*$button .='<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .='<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';*/
            
            if($record['received']==1){
                $received_status .= '<div class="badge badge-success" >'.get_phrases(['store','received']).'</div> ';
                if($record['isApproved']==1){
                    $status .= ' <div class="badge badge-primary" >'.get_phrases(['approved']).'</div>';
                    
                } else {
                    $status .= ' <div class="badge badge-info">'.get_phrases(['not','approved']).'</div>';
                }

            } else {
                $received_status .= '<div class="badge badge-info text-white">'.get_phrases(['not','received']).'</div>';
            }


            $data[] = array( 
                'id'                => $record['id'],
                'plan_no'           => $record['plan_no'],
                'voucher_no'        => $record['voucher_no'],
                'date'              => $record['date'],
                'store_name'        => $record['store_name'],
                'machine_name'      => $record['machine_name'],
                'status'            => $status,
                'received_status'   => $received_status,
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
    
    public function bdtaskt1m12_03_getProductionVoucherDetailsById($id){
        $data = $this->db->table('wh_production')->select('wh_production.*, DATE_FORMAT(wh_production.date, "%d/%m/%Y") as date, wh_production_store.nameE as store_name, wh_machine_store.nameE as machine_name,wh_receive.grand_total as receive_grand_total')                        
            ->join('wh_production_store', 'wh_production_store.id=wh_production.store_id','left')
            ->join('wh_machine_store', 'wh_machine_store.id=wh_production.machine_id','left')
            ->join('wh_receive', 'wh_receive.production_id=wh_production.id','left')
            ->where( array('wh_production.id'=>$id) )
            ->get()
            ->getRowArray();

        return $data;
    }

    public function bdtaskt1m12_04_updateStock($qty, $where){
        $result = $this->db->table('wh_production_stock')->set('stock', 'stock+'.$qty, FALSE)->set('stock_in', 'stock_in+'.$qty, FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_05_getItemReturnDetailsById($id){
        $data = $this->db->table('wh_production')->select('wh_production.*,wh_production.vat as po_vat, DATE_FORMAT(wh_production.date, "%d/%m/%Y") date, wh_production_store.nameE as store_name, s.nameE as machine_name, wh_return.voucher_no as return_voucher_no, wh_return.grand_total as return_grand_total, DATE_FORMAT(wh_return.date, "%d/%m/%Y") as return_date, wh_return.sub_total as return_sub_total, wh_return.vat as return_vat')                        
            ->join('wh_production_store', 'wh_production_store.id=wh_production.store_id','left')
            ->join('wh_machine_store s', 's.id=wh_production.machine_id','left')
            ->join('wh_return', 'wh_return.production_id=wh_production.id','left')
            ->where( array('wh_production.id'=>$id) )
            ->get()
            ->getRowArray();

        return $data;
    }


}
?>