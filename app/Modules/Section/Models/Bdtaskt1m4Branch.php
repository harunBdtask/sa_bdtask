<?php namespace App\Modules\Section\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m4Branch extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        //$this->hasReadAccess = $this->permission->method('branch_list', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('branch_list', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('branch_list', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('branch_list', 'delete')->access();

    }

    public function bdtaskt1m4_02_getAll($postData=null){
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
           $searchQuery = " (nameE like '%".$searchValue."%' OR nameA like '%".$searchValue."%' OR vat_no like '%".$searchValue."%')";
        }
        
        ## Fetch records
        $builder3 = $this->db->table('branch');
        $builder3->select("*");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }
        if(session('branchId') >0 ){
            $builder3->where("id", session('branchId'));
        }
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
                'id'       => $record['id'],
                'nameE'    => $record['nameE'],
                'nameA'    => $record['nameA'],
                'vat_no'    => $record['vat_no'],
                'button'   => $button
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
   
}
?>