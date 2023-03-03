<?php namespace App\Modules\Bank\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bankmodel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();
        $this->hasReadAccess   = $this->permission->method('wh_bag_supplier', 'read')->access();
        $this->hasUpdateAccess = $this->permission->method('wh_bag_supplier', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('wh_bag_supplier', 'delete')->access();
    }

    public function get_list(){
        
        ## Fetch records
        $builder3 = $this->db->table('ah_bank');
        $builder3->select("ah_bank.*");
        $query3 =  $builder3->get();
        $response=   $query3->getResult();
        return $response; 
    }

    public function getAllBank($postData=null){
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
           $searchQuery = " (spl.bank_name like '%".$searchValue."%') ";
        }
        ## Fetch records
        $builder3 = $this->db->table('ah_bank spl');
        $builder3->select("spl.*, spl.bank_id as id");
        if($searchValue != ''){
           $builder3->where($searchQuery);
        }
        
        ## Total number of records with filtering
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3 =  $builder3->get();
        $records=   $query3->getResultArray();
        
        ## Total number of records without filtering
        $totalRecords = $builder3->countAll();
        if($searchQuery == ''){
           $totalRecords = $totalRecordwithFilter;
        }
        $data   = array();
        $view   = get_phrases(['view']);
        $edit   = get_phrases(['update']);
        $delete = get_phrases(['delete']);
        foreach($records as $record ){ 
            $button = '';
            // $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-1 custool" title="'.$view.'" data-id="'.$record['id'].'"><i class="fas fa-truck"></i></a>';

            $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-1 custool" title="'.$edit.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';

            $button .= (!$this->hasDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.$delete.'" data-id="'.$record['id'].'" data-head="'.$record['acc_head'].'"><i class="far fa-trash-alt"></i></a>';

            $data[] = array( 
                'id'            => $record['id'],
                'bank_name'     => $record['bank_name'],
                'account_name'      => $record['account_name'],
                'account_number'    => $record['account_number'],
                'branch_name'       => $record['branch_name'],
                'address'     => $record['address'],
                'button'      => $button
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