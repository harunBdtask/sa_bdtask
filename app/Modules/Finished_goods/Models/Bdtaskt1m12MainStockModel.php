<?php namespace App\Modules\Finished_goods\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m12MainStockModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('fg_stock', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('fg_stock', 'create')->access();
        //$this->hasUpdateAccess = $this->permission->method('fg_stock', 'update')->access();
        //$this->hasDeleteAccess = $this->permission->method('fg_stock', 'delete')->access();

    }

    public function bdtaskt1m12_02_getAll($postData=null){
         $response = array();
         ## Read value
      
        @$store_id = $postData['store_id'];
        @$company_code = trim($postData['company_code']);

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
           $searchQuery = " ( fg.nameE like '%".$searchValue."%' OR fg.company_code like '%".$searchValue."%' ) ";
        }
        if($store_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( bs.store_id = '".$store_id."' ) ";
        }
        if($company_code != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( fg.company_code = '".$company_code."' ) ";
        }
        
        ## Fetch records
        $builder3 = $this->db->table('wh_production_stock bs');
        $builder3->select("bs.*, bw.nameE as store_name, branch.nameE as branch_name, fg.nameE as item_name, list_data.nameE as unit_name, fg.company_code, IF(fg.bag_weight >0, (bs.stock * fg.bag_weight), 0 ) as stock_kg");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }                  
        if(session('branchId') >0){
            $builder3->where('bw.branch_id', session('branchId'));
        }             
        $builder3->join('wh_production_store bw', 'bw.id=bs.store_id','left');
        $builder3->join('branch', 'branch.id=bw.branch_id','left');
        $builder3->join('wh_items fg', 'fg.id=bs.item_id','left');
        $builder3->join('list_data', 'list_data.id=fg.unit_id','left');
        $builder3->where('bw.branch_id', session('branchId'));
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

        foreach($records as $record ){ 
            $button = '';

            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC actionPreview mr-2 custool" title="'.$details.'" data-id="'.$record['id'].'"><i class="far fa-eye"></i></a>';
            /*$button .='<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .='<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';*/
            $data[] = array( 
                'id'            => $record['id'],
                'company_code'  => $record['company_code'],
                'item_name'     => $record['item_name'],
                'stock'         => $record['stock'].' Bags',
                'stock_kg'      => $record['stock_kg'].' KG',
                'store_name'    => $record['store_name'],
                'button'        => $button
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
    
    public function bdtaskt1m12_03_getMainStockDetailsById($id){
        $data = $this->db->table('wh_production_stock')->select('wh_production_stock.*, wh_production_store.nameE as store_name, branch.nameE as branch_name, wh_items.nameE as item_nameE, wh_items.company_code as item_nameA, list_data.nameE as unit_name')    
                        ->join('wh_production_store', 'wh_production_store.id=wh_production_stock.store_id','left')       
                        ->join('branch', 'branch.id=wh_production_store.branch_id','left')
                        ->join('wh_items', 'wh_items.id=wh_production_stock.item_id','left')
                        ->join('list_data', 'list_data.id=wh_items.unit_id','left')
                        ->where( array('wh_production_stock.id'=>$id) )
                        ->get()
                        ->getRowArray();

        return $data;
    }
}
?>