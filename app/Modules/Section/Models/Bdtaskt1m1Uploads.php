<?php namespace App\Modules\Section\Models;
use CodeIgniter\Model;
class Bdtaskt1m1Uploads extends Model
{

    public function __construct()
    {
        $this->db = db_connect();

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
           $searchQuery = " (department.nameE like '%".$searchValue."%' OR department.nameA like '%".$searchValue."%' OR branch.nameE like '%".$searchValue."%' OR branch.nameA like '%".$searchValue."%') ";
        }
        ## Total number of records without filtering
        $builder1 = $this->db->table('department');
        $builder1->select("count(*) as allcount");
        $query   =  $builder1->get();
        $records =   $query->getRow();
        $totalRecords = $records->allcount;
         
        ## Total number of record with filtering
        $builder2 = $this->db->table('department');
        $builder2->select("count(*) as allcount");
        $builder2->join("branch", "branch.id=department.branch_id", "left");
        if($searchValue != ''){
           $builder2->where($searchQuery);
        }
        $query2   =  $builder2->get();
        $records =   $query2->getRow();
        $totalRecordwithFilter = $records->allcount;
        ## Fetch records
        $builder3 = $this->db->table('department');
        $builder3->select("department.*, CONCAT_WS(' ', branch.nameE, branch.nameA) as branch_name");
        $builder3->join("branch", "branch.id=department.branch_id", "left");
        if($searchValue != ''){
           $builder3->where($searchQuery);
        }
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        $data = array();
        
        foreach($records as $record ){ 
            $button = '';

            $button .='<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .='<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';
            $data[] = array( 
                'id'         => $record['id'],
                'nameE'      => $record['nameE'],
                'nameA'      => $record['nameA'],
                'branch_name'=> $record['branch_name'],
                'button'     => $button
            ); 
        }

        ## Response
        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData"               => $data
        );
        return $response; 
    }
   
}
?>