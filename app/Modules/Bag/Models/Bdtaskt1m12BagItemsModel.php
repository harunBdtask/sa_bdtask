<?php namespace App\Modules\Bag\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m12BagItemsModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasCreateAccess = $this->permission->method('wh_bag_items', 'create')->access();
        $this->hasReadAccess   = $this->permission->method('wh_bag_items', 'read')->access();
        $this->hasUpdateAccess = $this->permission->method('wh_bag_items', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('wh_bag_items', 'delete')->access();

    }

    public function bdtaskt1m12_02_getAll($postData=null){
        $response = array();
        ## Read value
      
        @$finish_goods = trim($postData['finish_goods']);

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
           $searchQuery = " (wh_bag.nameE like '%".$searchValue."%' OR wh_bag.nameA like '%".$searchValue."%' ) ";
        }
        if($finish_goods != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_bag.finish_goods = '".$finish_goods."' ) ";
        }
        ## Fetch records
        $builder3 = $this->db->table('wh_bag');
        $builder3->select("wh_bag.*, cat.nameE as cat_name, list_data.nameE as unit_name, bs.nameE as b_specification");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }                 
        $builder3->join('wh_items cat', 'cat.id=wh_bag.finish_goods','left');
        $builder3->join('list_data', 'list_data.id=wh_bag.unit_id','left');
        $builder3->join('list_data bs', 'bs.id=wh_bag.specification','left');
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
                'nameE'         => $record['nameE'].' '.$record['b_specification'].' Bag Size:'.$record['bag_size'].' Liner Size:'.$record['liner_size'],
                'aprox_monthly_consumption' => $record['aprox_monthly_consumption'],
                'unit_name'     => $record['unit_name'],
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
    
    public function bdtaskt1m12_03_getItemDetailsById($id){
        $data = $this->db->table('wh_bag')->select('wh_bag.*, ld.nameE as w_use, bs.nameE as specification, c.nameE as c_name, list_data.nameE as unit_name')                        
                        ->join('wh_items c', 'c.id=wh_bag.finish_goods','left')
                        ->join('list_data', 'list_data.id=wh_bag.unit_id','left')
                        ->join('list_data ld', 'ld.id=wh_bag.where_use','left')
                        ->join('list_data bs', 'bs.id=wh_bag.specification','left')
                        ->where( array('wh_bag.id'=>$id) )
                        ->get()
                        ->getRowArray();

        return $data;
    }

}
?>