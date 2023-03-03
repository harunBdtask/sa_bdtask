<?php namespace App\Modules\Human_resource\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;
class Bdtaskt1m5Holidays extends Model
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
        $this->hasWeekendsReadAccess = $this->permission->method('weekends', 'read')->access();
        $this->hasWeekendsUpdateAccess = $this->permission->method('weekends', 'update')->access();
        $this->hasWeekendsDeleteAccess = $this->permission->method('weekends', 'delete')->access();

        $this->hasGovtHolidaysReadAccess = $this->permission->method('govt_holidays', 'read')->access();
        $this->hasGovtHolidaysUpdateAccess = $this->permission->method('govt_holidays', 'update')->access();
        $this->hasGovtHolidaysDeleteAccess = $this->permission->method('govt_holidays', 'delete')->access();
    }

    /*--------------------------
    | getBasicSalaryList
    *--------------------------*/

    public function bdtaskt1m1_01_getWeekendsList($postData=null){

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

            $searchQuery = " (weekend_days like '%".$searchValue."%') ";
        }

        ## Fetch records
        $builder3 = $this->db->table('hrm_weekends');
        $builder3->select("*");

        if(!session('isAdmin')){
            $builder3->where('CreateBy',session('id'));
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

            $button .= (!$this->hasWeekendsUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.get_phrases(['update']).'" data-id="'.$record['weekend_id'].'"><i class="far fa-edit"></i></a>';
            //$button .= (!$this->hasWeekendsDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.get_phrases(['delete']).'" data-id="'.$record['weekend_id'].'"><i class="far fa-trash-alt"></i></a>';


            $data[] = array( 
                'sl'               => $i,
                'weekend_days'     => $record['weekend_days'],
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

    public function bdtaskt1m1_02_govt_holidays_list($postData=null){

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

            $searchQuery = " (holiday_name like '%".$searchValue."%') ";
        }

        ## Fetch records
        $builder3 = $this->db->table('hrm_holidays');
        $builder3->select("*");

        if(!session('isAdmin')){
            $builder3->where('CreateBy',session('id'));
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

            $button .= (!$this->hasGovtHolidaysUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.get_phrases(['update']).'" data-id="'.$record['holiday_id'].'"><i class="far fa-edit"></i></a>';
            $button .= (!$this->hasGovtHolidaysDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.get_phrases(['delete']).'" data-id="'.$record['holiday_id'].'"><i class="far fa-trash-alt"></i></a>';


            $data[] = array( 
                'sl'               => $i,
                'holiday_name'     => $record['holiday_name'],
                'start_date'       => date("Y-m-d",strtotime($record['start_date'])),
                'end_date'         => date("Y-m-d",strtotime($record['end_date'])),
                'no_of_days'       => $record['no_of_days'],
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


    public function get_weekends()
    {
        return $this->db->table('hrm_weekends')
         ->select('weekend_days')
         ->get()
         ->getLastRow();
    }

    public function duplicate_start_date_for_holiday($start_date)
    {
        return $this->db->table('hrm_holidays')
         ->select('*')
         ->where('start_date <=',$start_date)
         ->where('end_date >=',$start_date)
         ->get()
         ->getRow();
    }

    public function duplicate_end_date_for_holiday($end_date)
    {
        return $this->db->table('hrm_holidays')
         ->select('*')
         ->where('start_date <=',$end_date)
         ->where('end_date >=',$end_date)
         ->get()
         ->getRow();
    }

    public function invalid_start_end_date_for_holiday($start_date,$end_date)
    {
        return $this->db->table('hrm_holidays')
         ->select('*')
         ->where('start_date >',$start_date)
         ->where('end_date <',$end_date)
         ->get()
         ->getRow();
    }

    public function duplicate_end_date_for_existing_holiday($holiday_id,$end_date)
    {
        return $this->db->table('hrm_holidays')
         ->select('*')
         ->where('start_date <=',$end_date)
         ->where('end_date >=',$end_date)
         ->where('holiday_id !=',$holiday_id)
         ->get()
         ->getRow();
    }

    public function duplicate_start_date_for_existing_holiday($holiday_id,$start_date)
    {
        return $this->db->table('hrm_holidays')
         ->select('*')
         ->where('start_date <=',$start_date)
         ->where('end_date >=',$start_date)
         ->where('holiday_id !=',$holiday_id)
         ->get()
         ->getRow();
    }

    public function invalid_start_end_date_for_existing_holiday($holiday_id,$start_date,$end_date)
    {
        return $this->db->table('hrm_holidays')
         ->select('*')
         ->where('start_date >',$start_date)
         ->where('end_date <',$end_date)
         ->where('holiday_id !=',$holiday_id)
         ->get()
         ->getResult();
    }


}
?>