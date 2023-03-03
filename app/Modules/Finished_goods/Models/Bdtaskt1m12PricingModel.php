<?php namespace App\Modules\Finished_goods\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m12PricingModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('goods_pricing', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('goods_pricing', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('goods_pricing', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('goods_pricing', 'delete')->access();

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
           $searchQuery = " (b.nameE like '%".$searchValue."%' OR b.item_code like '%".$searchValue."%' OR a.date like '%".$searchValue."%' OR c.fullname like '%".$searchValue."%' ) ";
        }
        
        ## Fetch records
        $builder3 = $this->db->table('goods_pricing a');
        $builder3->select("a.*,CONCAT_WS(' ',b.nameE,'(',item_code,')') AS item_name,c.fullname");
        $builder3->join("wh_items b","b.id = a.product_id","left");
        $builder3->join("user c","c.emp_id = a.created_by");
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

            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" data-id="'.$record['id'].'" title="Details"><i class="far fa-eye"></i></a>';
            $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" data-id="'.$record['id'].'" title="Edit"><i class="far fa-edit"></i></a>';
            $button .= (!$this->hasDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" data-id="'.$record['id'].'" title="Delete"><i class="far fa-trash-alt"></i></a>';
            $data[] = array( 
                'id'                   => ($postData['start'] ? $postData['start'] : 0) + $sl++,
                'nameE'                => $record['item_name'],
                'date'                 => $record['date'],
                'price'                => $record['price'],
                'create_by'            => $record['fullname'],
                'increase_percentagte' => $record['increase_percentagte'],
                'button'               => $button
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
    
    public function bdtaskt1m12_03_getPricingDetailsById($id){
        $data = $this->db->table('goods_pricing')->select("goods_pricing.*,CONCAT_WS(' ',wh_items.nameE,'(',wh_items.item_code,')') AS item_name,c.fullname")                        
                        ->join('wh_items', 'wh_items.id=goods_pricing.product_id','left')
                        ->join("user c","c.emp_id = goods_pricing.created_by")
                        ->where( array('goods_pricing.id'=>$id) )
                        ->get()
                        ->getRowArray();

        return $data;
    }

    public function itemlist()
    {
        $builder = $this->db->table('wh_items');
        $builder->select('*');
        $query = $builder->get();
        $data = $query->getResult();
        $list = array(' ' => get_phrases(['select', 'item']));
        if (!empty($data)) {
            foreach ($data as $value) {
                $list[$value->id] = $value->nameE;
            }
        }
        return $list;
    }

        public function bdtaskt1m12_03_getLastPrice($id){
        $data = $this->db->table('goods_pricing')->select('*')                        
                        ->where( array('product_id'=>$id) )
                        ->orderBy('date','desc')
                        ->get()
                        ->getRowArray();

        return $data;
    }
}
?>