<?php namespace App\Modules\User_management\Models;
use CodeIgniter\Model;
class Bdtaskt1m1ActivitiesModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();

    }

    public function bdtaskt1m1_01_getAll($postData=null){
        $response = array();
        ## Read value
      
        @$draw = $postData['draw'];
        @$start = $postData['start'];
        @$rowperpage = $postData['length']; // Rows display per page
        @$columnIndex = $postData['order'][0]['column']; // Column index
        @$searchValue = $postData['search']['value']; // Search value
        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
           $searchQuery = " (a.table_name like '%".$searchValue."%' OR a.action like '%".$searchValue."%' OR a.type like '%".$searchValue."%') ";
        }
        ## Fetch records
        $builder3 = $this->db->table('activity_logs a');
        $builder3->select("a.*, b.fullname");
        $builder3->join("user b", "b.emp_id=a.emp_id", "left");
        if($searchValue != ''){
           $builder3->where($searchQuery);
        }
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy('a.id', 'desc');
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        $totalRecords = $builder3->countAll();
        $data = array();
        $sl = 1;
        foreach($records as $record ){ 
            $data[] = array( 
                'id'       => $sl,
                'type'    => $record['type'],
                'action'    => $record['action'],
                'table_name'    => $record['table_name'],
                'action_id'    => $record['action_id'],
                'slug'    => $record['slug'],
                'fullname'    => $record['fullname'],
                'created_date'    => $record['created_date'],
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

   
   
}
?>