<?php namespace App\Modules\Human_resource\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;
class Bdtaskt1m2Setup extends Model
{
    protected $emp_table = 'employees';

    public function __construct()
    {
        $this->db = db_connect();
        // if(session('defaultLang')=='english'){
        //     $this->langColumn = 'nameE';
        // }else{
        //     $this->langColumn = 'nameA';
        // }

        $this->permission = new Permission();
        $this->hasBasicSalarySetupReadAccess = $this->permission->method('basic_salary_setup', 'read')->access();
        $this->hasBasicSalarySetupUpdateAccess = $this->permission->method('basic_salary_setup', 'update')->access();
        $this->hasBasicSalarySetupDeleteAccess = $this->permission->method('basic_salary_setup', 'delete')->access();

        $this->hasAllowanceSetupReadAccess = $this->permission->method('allowance_setup', 'read')->access();
        $this->hasAllowanceSetupUpdateAccess = $this->permission->method('allowance_setup', 'update')->access();
        $this->hasAllowanceSetupDeleteAccess = $this->permission->method('allowance_setup', 'delete')->access();
    }

    /*--------------------------
    | getBasicSalaryList
    *--------------------------*/

    public function bdtaskt1m1_01_getBasicSalaryList($postData=null){

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

            $searchQuery = " (hrm_basic_salary_setup.percent like '%".$searchValue."%' or hrm_employee_types.type like '%".$searchValue."%') ";
        }

        ## Fetch records
        $builder3 = $this->db->table('hrm_basic_salary_setup');
        $builder3->select("hrm_basic_salary_setup.*,hrm_employee_types.type");
        $builder3->join('hrm_employee_types','hrm_basic_salary_setup.employee_type = hrm_employee_types.id','left');

        if(!session('isAdmin')){
            $builder3->where('hrm_basic_salary_setup.CreateBy',session('id'));
        }

        $totalRecords = $builder3->countAllResults(false);

        if($searchValue != ''){
           $builder3->where($searchQuery);
        }
        $totalRecordwithFilter = $builder3->countAllResults(false);

        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        $data = array();

        $i = 1;
        
        foreach($records as $record ){

            $button = '';

            $button .= (!$this->hasBasicSalarySetupUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.get_phrases(['update']).'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .= (!$this->hasBasicSalarySetupDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.get_phrases(['delete']).'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';


            $data[] = array( 
                'sl'               => $i,
                'type'             => $record['type'],
                'percent'          => $record['percent'],
                'CreateDate'       => date("Y-m-d",strtotime($record['CreateDate'])),
                'button'           => $button
            );

            $i++;
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


   /*--------------------------
    | getBasicSalaryList
    *--------------------------*/

    public function bdtaskt1m1_02_getallowanceList($postData=null){

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

            $searchQuery = " (hrm_allowance_setup.title like '%".$searchValue."%' or hrm_employee_types.type like '%".$searchValue."%') ";
        }

        ## Fetch records
        $builder3 = $this->db->table('hrm_allowance_setup');
        $builder3->select("hrm_allowance_setup.*,hrm_employee_types.type");
        $builder3->join('hrm_employee_types','hrm_allowance_setup.employee_type = hrm_employee_types.id','left');

        if(!session('isAdmin')){
            $builder3->where('hrm_allowance_setup.CreateBy',session('id'));
        }

        $totalRecords = $builder3->countAllResults(false);

        if($searchValue != ''){
           $builder3->where($searchQuery);
        }
        $totalRecordwithFilter = $builder3->countAllResults(false);

        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        $data = array();

        $i = 1;
        
        foreach($records as $record ){

            $button = '';

            $button .= (!$this->hasAllowanceSetupUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.get_phrases(['update']).'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .= (!$this->hasAllowanceSetupDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.get_phrases(['delete']).'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';


            $data[] = array( 
                'sl'               => $i,
                'title'            => $record['title'],
                'amount'           => $record['amount'],
                'type'             => $record['type'],
                'CreateDate'       => date("Y-m-d",strtotime($record['CreateDate'])),
                'button'           => $button
            );

            $i++;
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


    public function employee_types_for_basic_salaries()
    {
        $builder = $this->db->table('hrm_employee_types');
        $builder->select('*');
        $query=$builder->get();
        $data=$query->getResult();

        $list = array('' => 'Select Employee Type');
        if(!empty($data)){
            foreach ($data as $value){

                //Get basic salry list to exclude it from the dropdown , when creating any basic salary setup
                $response = $this->db->table('hrm_basic_salary_setup')->select('employee_type')->where('employee_type',$value->id)->get()->getRow();

                if(empty($response)){
                    $list[$value->id]=$value->type;
                }
            }
        }
        return $list;  
    }

    public function employee_types_for_allowance_types()
    {
        $builder = $this->db->table('hrm_employee_types');
        $builder->select('*');
        $query=$builder->get();
        $data=$query->getResult();

        $list = array('' => 'Select Employee Type');
        if(!empty($data)){
            foreach ($data as $value){

                //Get basic salry list to exclude it from the dropdown , when creating any basic salary setup
                // $response = $this->db->table('hrm_allowance_setup')->select('employee_type')->where('employee_type',$value->id)->get()->getRow();

                // if(empty($response)){
                //     $list[$value->id]=$value->type;
                // }

                $list[$value->id]=$value->type;
            }
        }
        return $list;  
    }


}
?>