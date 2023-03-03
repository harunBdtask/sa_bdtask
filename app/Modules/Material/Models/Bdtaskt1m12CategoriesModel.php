<?php namespace App\Modules\Material\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m12CategoriesModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('categories', 'read')->access();
        $this->hasCreateAccess = $this->permission->method('categories', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('categories', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('categories', 'delete')->access();

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
           $searchQuery = " (wh_material_categories.nameE like '%".$searchValue."%' OR wh_material_categories.nameA like '%".$searchValue."%' ) ";
        }
        
        ## Fetch records
        $builder3 = $this->db->table('wh_material_categories');
        $builder3->select("wh_material_categories.*");
        if($searchValue != ''){
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
        
        $sl = 1;
        foreach($records as $record ){ 
            $button = '';

            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2" data-id="'.$record['id'].'"><i class="far fa-eye"></i></a>';
            $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .= (!$this->hasDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';
            $data[] = array( 
                'id'       => $sl,
                'nameE'    => $record['nameE'],
                'button'   => $button
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
    
    public function bdtaskt1m12_03_getCategoryDetailsById($id){
        $data = $this->db->table('wh_material_categories')->select('wh_material_categories.*')                        
                        //->join('employees', 'employees.emp_id=patient.special_doctor','left')
                        ->where( array('wh_material_categories.id'=>$id) )
                        ->get()
                        ->getRowArray();

        return $data;
    }
}
?>