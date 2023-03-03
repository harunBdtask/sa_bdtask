<?php namespace App\Modules\Supplier\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m12SupplierModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('wh_material_supplier', 'read')->access();
        $this->hasCreateAccess = $this->permission->method('wh_material_supplier', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('wh_material_supplier', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('wh_material_supplier', 'delete')->access();

    }

    public function bdtaskt1m12_01_getAllSupplier($postData=null){
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
           $searchQuery = " (spl.nameE like '%".$searchValue."%' OR spl.nameA like '%".$searchValue."%' OR spl.phone like '%".$searchValue."%' OR spl.acc_head like '%".$searchValue."%') ";
        }
        ## Fetch records
        $builder3 = $this->db->table('wh_supplier_information spl');
        $builder3->select("spl.*");
        if($searchValue != ''){
           $builder3->where($searchQuery);
        }
        if(session('branchId') >0 ){
            $builder3->where("spl.branch_id", session('branchId'));
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
        $sold   = get_phrases(['credit', 'consignment']);
        $full   = get_phrases(['full', 'payment']);
        $credit = get_phrases(['full', 'credit']);
        $view   = get_phrases(['view']);
        $edit   = get_phrases(['update']);
        $delete = get_phrases(['delete']);
        foreach($records as $record ){ 
            $country_name = ($record['country'])?countries($record['country']):'';
            if ($record['status'] == 1) {
                $status = get_phrases(['active']);
            }else{
                $status = get_phrases(['inactive']);
            }
            $button = '';
            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-1 custool" title="'.$view.'" data-id="'.$record['id'].'"><i class="fas fa-truck"></i></a>';

            $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-1 custool" title="'.$edit.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';

            $button .= (!$this->hasDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.$delete.'" data-id="'.$record['id'].'" data-head="'.$record['acc_head'].'"><i class="far fa-trash-alt"></i></a>';

            $data[] = array( 
                'id'          => $record['id'],
                'code_no'     => $record['code_no'],
                'nameE'       => $record['nameE'],
                'phone'       => $record['phone'],
                'email'       => $record['email'],
                'country'     => $country_name,
                'address'     => $record['address'],
                'ssn'         => $record['ssn'],
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
    
    public function bdtaskt1m12_03_getCategoryDetailsById($id){
        $data = $this->db->table('wh_categories')->select('wh_categories.*')                        
                        //->join('employees', 'employees.emp_id=patient.special_doctor','left')
                        ->where( array('wh_categories.id'=>$id) )
                        ->get()
                        ->getRowArray();

        return $data;
    }
}
?>