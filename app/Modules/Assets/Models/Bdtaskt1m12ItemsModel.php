<?php namespace App\Modules\Assets\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m12ItemsModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess   = $this->permission->method('wh_assets_items', 'read')->access();
        $this->hasCreateAccess = $this->permission->method('wh_assets_items', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('wh_assets_items', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('wh_assets_items', 'delete')->access();

    }

    public function bdtaskt1m12_02_getAll($postData=null){
        $response = array();
        ## Read value
      
        @$cat_id = trim($postData['cat_id']);

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
           $searchQuery = " (wh_assets.nameE like '%".$searchValue."%' ) ";
        }
        if($cat_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_assets.id = '".$cat_id."' ) ";
        }
        ## Fetch records
        $builder3 = $this->db->table('wh_assets');
        $builder3->select("wh_assets.*, list_data.nameE as unit_name");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }                 
        $builder3->join('list_data', 'list_data.id=wh_assets.unit_id','left');
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
                'item_code'     => $record['item_code'],
                'nameE'         => $record['nameE'],
                'unit_name'     => $record['unit_name'],
                'button'        => $button
            ); 
        }

        ## Response
        $response = array(
            "draw"                 => intval($draw),
            // "asdf"        => 1,
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData"               => $data
        );
        return $response; 
    }
    
    public function bdtaskt1m12_03_getItemDetailsById($id){
        $data = $this->db->table('wh_assets')->select('wh_assets.*, wh_assets_categories.nameE as cat_name, list_data.nameE as unit_name')  
                        ->join('wh_assets_categories', 'wh_assets_categories.id=wh_assets.cat_id','left')
                        ->join('list_data', 'list_data.id=wh_assets.unit_id','left')
                        ->where( array('wh_assets.id'=>$id) )
                        ->get()
                        ->getRowArray();

        return $data;
    }

    public function bdtaskt1m12_04_getItemSupplierDetailsById($id){
        $data = $this->db->table('wh_material_supplier')->select('wh_material_supplier.*, wh_supplier_information.nameE as supplier_name')                        
                        ->join('wh_supplier_information', 'wh_supplier_information.id=wh_material_supplier.supplier_id','left')
                        ->where( array('wh_material_supplier.item_id'=>$id) )
                        ->get()
                        ->getResultArray();

        return $data;
    }
}
?>