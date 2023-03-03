<?php namespace App\Modules\Finished_goods\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m12MainStoreModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('fg_store', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('fg_store', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('fg_store', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('fg_store', 'delete')->access();

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
        $searchQuery = "";
        if($searchValue != ''){
           $searchQuery = " (wh_production_store.nameE like '%".$searchValue."%' OR wh_production_store.nameA like '%".$searchValue."%' ) ";
        }
        
        ## Fetch records
        $builder3 = $this->db->table('wh_production_store');
        $builder3->select("wh_production_store.*, branch.nameE as branch_name");
        if($searchValue != ''){
           $builder3->where($searchQuery);
        }                 
        if(session('branchId') >0){
            $builder3->where('wh_production_store.branch_id', session('branchId'));
        }
        $builder3->join('branch', 'branch.id=wh_production_store.branch_id','left');
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

            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="'.$details.'" data-id="'.$record['id'].'"><i class="far fa-eye"></i></a>';
            $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .= (!$this->hasDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';
            $data[] = array( 
                'id'            => $record['id'],
                'nameE'         => $record['nameE'],
                'nameA'         => $record['nameA'],
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
    
    public function bdtaskt1m12_03_getMainStoreDetailsById($id){
        $data = $this->db->table('wh_production_store')->select('wh_production_store.*, branch.nameE as branch_name')           
                        ->join('branch', 'branch.id=wh_production_store.branch_id','left')
                        ->where( array('wh_production_store.id'=>$id) )
                        ->get()
                        ->getRowArray();

        return $data;
    }
}
?>