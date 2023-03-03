<?php namespace App\Modules\User_management\Models;
use CodeIgniter\Model;
class Bdtaskt1m16Module extends Model
{

    public function __construct()
    {
        $this->db = db_connect();

    }

    public function bdtaskt1m16_01_getAll($postData=null){
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
           $searchQuery = " (nameE like '%".$searchValue."%' OR nameA like '%".$searchValue."%') ";
        }
        ## Total number of records without filtering
        /*$builder1 = $this->db->table('module');
        $builder1->select("count(*) as allcount");
        $query   =  $builder1->get();
        $records =   $query->getRow();
        $totalRecords = $records->allcount;
         
        ## Total number of record with filtering
        $builder2 = $this->db->table('module');
        $builder2->select("count(*) as allcount");
        if($searchValue != ''){
           $builder2->where($searchQuery);
        }
        $query2   =  $builder2->get();
        $records =   $query2->getRow();
        $totalRecordwithFilter = $records->allcount;*/
        ## Fetch records
        $builder3 = $this->db->table('module');
        $builder3->select("*");
        if($searchValue != ''){
           $builder3->where($searchQuery);
        }
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        $totalRecords = $builder3->countAll();
        $data = array();
        
        foreach($records as $record ){ 
            $button = '';

            $button .='<a href="javascript:void(0);" class="btn btn-info-soft btnC editAction mr-2" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $data[] = array( 
                'id'       => $record['id'],
                'nameE'    => $record['nameE'],
                'nameA'    => $record['nameA'],
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

    public function bdtaskt1m16_02_getAllModules($postData=null){
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
           $searchQuery = " (sub_module.nameE like '%".$searchValue."%' OR sub_module.nameA like '%".$searchValue."%') ";
        }
        ## Total number of records without filtering
        $builder1 = $this->db->table('sub_module');
        $builder1->select("count(*) as allcount");
        $query   =  $builder1->get();
        $records =   $query->getRow();
        $totalRecords = $records->allcount;
         
        ## Total number of record with filtering
        $builder2 = $this->db->table('sub_module');
        $builder2->select("count(*) as allcount")->join("module", "module.id=sub_module.mid", "left");
        if($searchValue != ''){
           $builder2->where($searchQuery);
        }
        $query2   =  $builder2->get();
        $records =   $query2->getRow();
        $totalRecordwithFilter = $records->allcount;
        ## Fetch records
        $builder3 = $this->db->table('sub_module')->select("sub_module.*, CONCAT_WS(' ', module.nameE, '-', module.nameA) as mName")->join("module", "module.id=sub_module.mid", "left");
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

            $button .='<a href="javascript:void(0);" class="btn btn-info-soft btnC editAction mr-2" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $data[] = array( 
                'mid'      => $record['mid'],
                'mName'    => $record['mName'],
                'nameE'    => $record['nameE'],
                'nameA'    => $record['nameA'],
                'button'   => $button
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

    /*--------------------------
    | Get module Menu by ID
    *--------------------------*/
    public function bdtaskt1m16_03_getModuleMenu($id=null){
        return $this->db->table('sub_module')->select("sub_module.*, module.nameE as mnameE, module.nameA as mnameA")
                        ->join('module', 'module.id=sub_module.mid', 'left')
                        ->where('sub_module.id', $id)
                        ->get()->getRow();
    }
   
}
?>