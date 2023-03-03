<?php namespace App\Modules\Machine\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1MachineStockModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('wh_machine_stock', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('wh_machine_stock', 'create')->access();
        //$this->hasUpdateAccess = $this->permission->method('wh_machine_stock', 'update')->access();
        //$this->hasDeleteAccess = $this->permission->method('wh_machine_stock', 'delete')->access();

    }

    public function bdtaskt1m12_02_getAll($postData=null){
         $response = array();
         ## Read value
      
        @$sub_store_id = $postData['sub_store_id'];
        @$company_code = trim($postData['company_code']);

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
           $searchQuery = " ( wh_material.nameE like '%".$searchValue."%' OR wh_material.nameA like '%".$searchValue."%' ) ";
        }
        if($sub_store_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( ds.sub_store_id = '".$sub_store_id."' ) ";
        }
        if($company_code != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_material.company_code = '".$company_code."' ) ";
        }
        
        ## Fetch records
        $builder3 = $this->db->table('wh_machine_stock ds');
        $builder3->select("ds.*, dw.nameE as store_name, branch.nameE as branch_name, hrm_departments.name as dept_name, wh_material.nameE as item_name, list_data.nameE as unit_name, wh_material.company_code");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }    
        if(session('store_id') !='' && session('store_id') !='0' ){
            $builder3->where('dw.id', session('store_id') );
        }                 
        if(session('branchId') >0){
            $builder3->where('dw.branch_id', session('branchId'));
        }       
        $builder3->join('wh_machine_store dw', 'dw.id=ds.sub_store_id','left');
        $builder3->join('branch', 'branch.id=dw.branch_id','left');
        $builder3->join('hrm_departments', 'hrm_departments.id=dw.dept_id','left');
        $builder3->join('wh_material', 'wh_material.id=ds.item_id','left');
        $builder3->join('list_data', 'list_data.id=wh_material.unit_id','left');
        $builder3->where('dw.branch_id', session('branchId'));
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

            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC actionPreview mr-2 custool" title="'.$details.'" data-id="'.$record['id'].'"><i class="far fa-eye"></i></a>';
            /*$button .='<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .='<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';*/
            $data[] = array( 
                'id'            => $record['id'],
                'company_code'  => $record['company_code'],
                'item_name'     => $record['item_name'],
                'stock'         => $record['stock'].' '.$record['unit_name'],
                'dept_name'     => $record['store_name'],
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
    
    public function bdtaskt1m12_03_getSubStockDetailsById($id){
        $data = $this->db->table('wh_machine_stock')->select('wh_machine_stock.*, wh_machine_store.nameE as store_name, branch.nameE as branch_name, hrm_departments.name as dept_name, wh_material.nameE as item_nameE, wh_material.nameA as item_nameA, list_data.nameE as unit_name')           
                        ->join('wh_machine_store', 'wh_machine_store.id=wh_machine_stock.sub_store_id','left')
                        ->join('branch', 'branch.id=wh_machine_store.branch_id','left')
                        ->join('hrm_departments', 'hrm_departments.id=wh_machine_store.dept_id','left')
                        ->join('wh_material', 'wh_material.id=wh_machine_stock.item_id','left')
                        ->join('list_data', 'list_data.id=wh_material.unit_id','left')
                        ->where( array('wh_machine_stock.id'=>$id) )
                        ->get()
                        ->getRowArray();

        return $data;
    }
}
?>