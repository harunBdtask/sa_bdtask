<?php namespace App\Modules\Section\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m3Dealer extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('dealer_list', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('dealer_list', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('dealer_list', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('dealer_list', 'delete')->access();

        if(session('defaultLang')=='english'){
            $this->langColumn = 'nameE';
        }else{
            $this->langColumn = 'nameA';
        }

    }

    public function bdtaskt1m1_02_getAll($postData=null){
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
           $searchQuery = " (wh_dealer_info.name like '%".$searchValue."%') ";
        }
       
        ## Fetch records
        $builder3 = $this->db->table('wh_dealer_info');
        $builder3->select("wh_dealer_info.*, p.name as reference");
        //$builder3->join("branch", "branch.id=wh_dealer_info.branch_id", "left");
        $builder3->join("wh_dealer_info p", "p.id=wh_dealer_info.reference_id", "left");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }
        /*if(session('branchId') >0 ){
            $builder3->where("wh_dealer_info.branch_id", session('branchId'));
        }*/
        ## Total number of record with filtering
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
        
        $update = get_phrases(['update']);
        $delete = get_phrases(['delete']);
        
        foreach($records as $record ){ 
            $button = '';

            $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .= (!$this->hasDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';
            $data[] = array( 
                'id'            => $record['id'],
                'name'          => $record['name'],
                'reference'     => $record['reference'],
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

    public function bdtaskt1m1_03_getDealerList(){
        $query = $this->db->table('wh_dealer_info')->select("id, name as text")->where('status', 1)->get()->getResult();
        if(!empty($query)){
            return $query;
        }else{
            return false;
        }
    }

    /*--------------------------
    | Get wh_dealer_info by branch
    *--------------------------*/
    public function bdtaskt1m1_04_getDealersByBranch($branch_id){
        $query = $this->db->table('wh_dealer_info')->select("id, name as text")->where('branch_id', $branch_id)->where('status', 1)->get()->getResult();
        if(!empty($query)){
            return $query;
        }else{
            return false;
        }
    }

    public function getParents($parentId){
        return $this->db->table('wh_dealer_info')->select('name')->where('id', $parentId)->get()->getRowArray();
    }
   
}
?>