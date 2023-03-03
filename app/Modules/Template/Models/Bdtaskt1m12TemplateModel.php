<?php namespace App\Modules\Template\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m12TemplateModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess   = $this->permission->method('wh_template', 'read')->access();
        $this->hasCreateAccess = $this->permission->method('wh_template', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('wh_template', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('wh_template', 'delete')->access();

    }

    public function bdtaskt1m12_01_getAll($postData=null){
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
           $searchQuery = " (spl.template_name like '%".$searchValue."%') ";
        }
        ## Fetch records
        $builder3 = $this->db->table('wh_template spl');
        $builder3->select("spl.*");
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
        $preview = get_phrases(['preview']);
        $view   = get_phrases(['view']);
        $edit   = get_phrases(['update']);
        $delete = get_phrases(['delete']);
        foreach($records as $record ){ 
            if ($record['status'] == 1) {
                $status = get_phrases(['active']);
            }else{
                $status = get_phrases(['inactive']);
            }
            $button = '';
            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-1 custool" title="'.$view.'" data-id="'.$record['id'].'"><i class="fas fa-book"></i></a>';
            if ($record['isApproved'] != 1) {
                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC actionApprove mr-1 custool" title="Approve" data-id="'.$record['id'].'"><i class="fas fa-check"></i></a>';
                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-1 custool" title="'.$edit.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
                $button .= (!$this->hasDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';
            }

            $data[] = array( 
                'id'            => $record['id'],
                'template_name' => $record['template_name'],
                'status'      => $status,
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