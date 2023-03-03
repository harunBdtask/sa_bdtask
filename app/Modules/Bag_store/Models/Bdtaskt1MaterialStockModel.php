<?php namespace App\Modules\Bag_store\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1MaterialStockModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('wh_bag_stock', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('wh_bag_stock', 'create')->access();
        //$this->hasUpdateAccess = $this->permission->method('wh_bag_stock', 'update')->access();
        //$this->hasDeleteAccess = $this->permission->method('wh_bag_stock', 'delete')->access();

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
        if (!empty($columnName) && $columnName=='sl') {
            $columnName='id';
        }
        @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        @$searchValue = $postData['search']['value']; // Search value
        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
           $searchQuery = " ( wh_items.nameE like '%".$searchValue."%' OR wh_items.nameA like '%".$searchValue."%' ) ";
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
           $searchQuery .= " ( wh_items.company_code = '".$company_code."' ) ";
        }
        
        ## Fetch records
        $builder3 = $this->db->table('wh_bag_stock bs');
        $builder3->select("bs.*, bw.nameE as store_name, branch.nameE as branch_name, wh_items.bag_size, wh_items.liner_size, wh_items.item_code, wh_items.nameE as item_name, lbs.nameE as b_specification, list_data.nameE as unit_name, wh_items.company_code");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }                  
        if(session('branchId') >0){
            $builder3->where('bw.branch_id', session('branchId'));
        }             
        $builder3->join('wh_bag_store bw', 'bw.id=bs.store_id','left');
        $builder3->join('branch', 'branch.id=bw.branch_id','left');
        $builder3->join('wh_bag wh_items', 'wh_items.id=bs.item_id','left');
        $builder3->join('list_data', 'list_data.id=wh_items.unit_id','left');
        $builder3->join('list_data lbs', 'lbs.id=wh_items.specification','left');
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
        $sl = 1;

        foreach($records as $record ){ 
            $button = '';

            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC actionPreview mr-2 custool" title="'.$details.'" data-id="'.$record['id'].'"><i class="far fa-eye"></i></a>';
            /*$button .='<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .='<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';*/
            $data[] = array( 
                'id'            => $record['id'],
                'sl'            => $sl,
                'company_code'  => $record['company_code'],
                'item_name'     => $record['item_name'].' '.$record['b_specification'].' Bag Size:'.$record['bag_size'].' Liner Size:'.$record['liner_size'].' - '.$record['item_code'],
                'stock'         => $record['stock'].' '.$record['unit_name'],
                'store_name'    => $record['store_name'],
                'button'        => $button
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
    
    public function bdtaskt1m12_03_getMainStockDetailsById($id){
        $data = $this->db->table('wh_bag_stock')->select('wh_bag_stock.*, wh_bag_store.nameE as store_name, branch.nameE as branch_name, wh_items.nameE as item_nameE, wh_items.nameA as item_nameA, list_data.nameE as unit_name')    
                        ->join('wh_bag_store', 'wh_bag_store.id=wh_bag_stock.store_id','left')       
                        ->join('branch', 'branch.id=wh_bag_store.branch_id','left')
                        ->join('wh_bag wh_items', 'wh_items.id=wh_bag_stock.item_id','left')
                        ->join('list_data', 'list_data.id=wh_items.unit_id','left')
                        ->where( array('wh_bag_stock.id'=>$id) )
                        ->get()
                        ->getRowArray();

        return $data;
    }
}
?>