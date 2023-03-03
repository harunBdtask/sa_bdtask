<?php namespace App\Modules\Human_resource\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;
class Bdtaskt1m7DutyRoster extends Model
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

        $this->hasshiftUpdateAccess = $this->permission->method('shift', 'update')->access();
        $this->hasshiftDeleteAccess = $this->permission->method('shift', 'delete')->access();

        $this->hasRosterUpdateAccess = $this->permission->method('roster', 'update')->access();
        $this->hasRosterDeleteAccess = $this->permission->method('roster', 'delete')->access();
    }

    public function bdtaskt1m4_01_shiftDataList($postData=null){

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
           $searchQuery = " (hrm_empwork_shift.shift_name like '%".$searchValue."%' OR hrm_departments.name like '%".$searchValue."%') ";
        }

        ## Fetch records
        $builder3 = $this->db->table('hrm_empwork_shift');
        $builder3->select("hrm_empwork_shift.*,hrm_departments.name as department_name");
        $builder3->join("hrm_departments", "hrm_departments.id = hrm_empwork_shift.department_id ", "left");

        if(!session('isAdmin')){
            $builder3->where('hrm_empwork_shift.CreateBy',session('id'));
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

        // echo json_encode($records);exit;

        $i = 1;
        
        foreach($records as $record ){

            $button = '';

            $button .= (!$this->hasshiftUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.get_phrases(['update']).'" data-id="'.$record['shiftid'].'"><i class="far fa-edit"></i></a>';
            $button .= (!$this->hasshiftDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.get_phrases(['delete']).'" data-id="'.$record['shiftid'].'"><i class="far fa-trash-alt"></i></a>';


            $data[] = array( 
                'sl'             => $i,
                'shiftid'        => $record['shiftid'],
                'shift_name'     => $record['shift_name'],
                'department_name'=> $record['department_name'],
                'shift_start'    => $record['shift_start'],
                'shift_end'      => $record['shift_end'],
                'shift_duration' => $record['shift_duration'],
                'button'         => $button
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

    public function bdtaskt1m8_02_rosterDataList($postData=null){

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
           $searchQuery = " (hrm_empwork_shift.shift_name like '%".$searchValue."%' OR hrm_departments.name like '%".$searchValue."%') ";
        }

        ## Fetch records
        $builder3 = $this->db->table('hrm_duty_roster');
        $builder3->select("hrm_duty_roster.*,hrm_empwork_shift.shift_name,hrm_departments.name as department_name");
        $builder3->join("hrm_empwork_shift", "hrm_empwork_shift.shiftid = hrm_duty_roster.shift_id ", "left");
        $builder3->join("hrm_departments", "hrm_empwork_shift.department_id = hrm_departments.id ", "left");
        $builder3->orderBy('hrm_duty_roster.roster_id', 'desc');

        if(!session('isAdmin')){
            $builder3->where('hrm_duty_roster.CreateBy',session('id'));
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

        // echo json_encode($records);exit;

        $i = 1;
        
        foreach($records as $record ){

            $button = '';

            //End og getting shift name for roster..

            $button .= (!$this->hasRosterUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.get_phrases(['update']).'" data-id="'.$record['roster_id'].'"><i class="far fa-eye"></i></a>';
            $button .= (!$this->hasRosterDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.get_phrases(['delete']).'" data-id="'.$record['roster_id'].'" data-startdate="'.$record['roster_start'].'" data-enddate="'.$record['roster_end'].'"><i class="far fa-trash-alt"></i></a>';


            $data[] = array( 
                'sl'             => $i,
                'roster_id'      => $record['roster_id'],
                'shift_name'     => $record['shift_name'],
                'department_name'=> $record['department_name'],
                'roster_start'   => $record['roster_start'],
                'roster_end'     => $record['roster_end'],
                'roster_dsys'    => $record['roster_dsys'],
                'button'         => $button
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
    | Get Departments data
    *--------------------------*/
    public function bdtaskt1m8_2_getDepartments()
    {

      $query = $this->db->table('hrm_departments');
      $data[] = array('id'=>'','text'=>'Select Shift');

      $result = $query->get()->getResult();
      foreach ($result as $row) {
          $data[] = array(
                        'id'=>$row->id,
                        'text'=>$row->name
                      );
      }
      return $data;
    }

    /*--------------------------
    | Get ShiftList data
    *--------------------------*/
    public function bdtaskt1m8_2_getShiftList()
    {

      $query = $this->db->table('hrm_empwork_shift');
      $data[] = array('id'=>'','text'=>'Select Shift');

      $result = $query->get()->getResult();
      foreach ($result as $row) {
          $data[] = array(
                        'id'=>$row->shiftid,
                        'text'=>$row->shift_name
                      );
      }
      return $data;
    }

    public function department_shift_list($department_id)
    {
        $builder = $this->db->table('hrm_empwork_shift');
        $builder->select('*');
        $builder->where("department_id", $department_id);
        $query=$builder->get();
        $data=$query->getResult();

        return $data;  
    }

    public function bdtaskt1m8_05_shiftassignData(){

        $builder3 = $this->db->table('hrm_emproster_assign');
        $builder3->select("hrm_emproster_assign.*,hrm_employees.first_name,hrm_employees.last_name,hrm_duty_roster.*,hrm_empwork_shift.shift_name");

        $builder3->join('hrm_duty_roster','hrm_duty_roster.roster_id=hrm_emproster_assign.roster_id','left');
        $builder3->join('hrm_employees','hrm_employees.employee_id=hrm_emproster_assign.emp_id','left');
        $builder3->join('hrm_empwork_shift','hrm_empwork_shift.shiftid=hrm_duty_roster.shift_id','left');

        $builder3->orderBy('hrm_emproster_assign.sftasnid', 'desc');

        //$builder3->whereNotIn('hrm_emproster_assign.is_edited',['1']); // Check if it's required keep it .. else remove it

        $builder3->groupBy('hrm_emproster_assign.roster_id');
        $builder3->groupBy('hrm_emproster_assign.emp_id');

        $data = $builder3->get()->getResult();

        return $data;

     }

    public function bdtaskt1m8_04_rosterShiftAssignAdd(){
            
        $tdate =  date("Y-m-d");
        $rost_ids = array();

        $builder3 = $this->db->table('hrm_emproster_assign');
        $builder3->select("roster_id");
        $builder3->distinct();
        $assigned_rosters = $builder3->get()->getResult();

        $i=0;
        foreach ($assigned_rosters as $assigned_roster) 
        {
            $rost_ids[$i] = $assigned_roster->roster_id;
            $i++;
        }

        //return $rost_ids;

        $builder4 = $this->db->table('hrm_duty_roster');
        $builder4->select("hrm_duty_roster.*,hrm_empwork_shift.shift_name,hrm_departments.name as department_name");
        $builder4->join('hrm_empwork_shift','hrm_empwork_shift.shiftid = hrm_duty_roster.shift_id','left');
        $builder4->join('hrm_departments','hrm_departments.id = hrm_duty_roster.department_id','left');
        if($rost_ids){
            $builder4->whereNotIn('roster_id', $rost_ids);
        }

        $builder4->where('hrm_duty_roster.roster_end >', $tdate);

        $data = $builder4->get()->getResult();

        $list[''] = get_phrases(['select','roster']);
        if (!empty($data)) 
        {
            foreach($data as $value)
                $list[$value->roster_id] = '('.$value->roster_start.' - '.$value->roster_end.') '.$value->shift_name.'-'.$value->department_name.' department';
            return $list;

        }else{

            return false; 
        }

    }

     public function bdtaskt1m8_05_empData($department_id){

        $builder3 = $this->db->table('hrm_employees');
        $builder3->join("hrm_employee_types", "hrm_employee_types.id=hrm_employees.employee_type", "left");
        $builder3->select("hrm_employees.employee_id,hrm_employees.first_name,hrm_employees.last_name,hrm_employee_types.type");

        $builder3->where('hrm_employees.department',$department_id);
        $builder3->where('hrm_employees.in_duty_roster',1);

        $data = $builder3->get()->getResult();

        return $data;

     }

     public function bdtaskt1m8_08_roster_viewdata($roster_id){

        $builder3 = $this->db->table('hrm_duty_roster');
        $builder3->select("hrm_duty_roster.*,hrm_empwork_shift.shift_name,hrm_departments.name as department_name");
        $builder3->join('hrm_empwork_shift','hrm_empwork_shift.shiftid = hrm_duty_roster.shift_id','left');
        $builder3->join('hrm_departments','hrm_departments.id = hrm_empwork_shift.department_id','left');
        $builder3->where('hrm_duty_roster.roster_id',$roster_id);

        return $builder3->get()->getRow();

     }

    public function bdtaskt1m8_09_roster_emp_data($roster_id){

        $builder3 = $this->db->table('hrm_emproster_assign');
        $builder3->select("hrm_emproster_assign.*,hrm_employees.first_name,hrm_employees.last_name");
        $builder3->join('hrm_employees','hrm_employees.employee_id = hrm_emproster_assign.emp_id','left');
        $builder3->where('hrm_emproster_assign.roster_id',$roster_id);

        $builder3->whereNotIn('hrm_emproster_assign.is_edited',['1']);
        $builder3->groupBy('hrm_emproster_assign.emp_id');

        return $builder3->get()->getResult();

    }

    public function bdtaskt1m8_10_current_shift_list()
    {
        $today = date('Y-m-d');

        $builder = $this->db->table('hrm_emproster_assign');
        $builder->select("roster_id");
        $builder->where("emp_startroster_date",$today);
        $idfromdate = $builder->get()->getRow();

        // return $idfromdate;

        if($idfromdate){

            $builder2 = $this->db->table('hrm_duty_roster');
            $builder2->select("rostentry_id");
            $builder2->where('roster_id', $idfromdate->roster_id);
            $rostentry_id = $builder2->get()->getRow();
        }

        // return $rostentry_id;

        if($idfromdate){

            $builder3 = $this->db->table('hrm_duty_roster');
            $builder3->select("shift_id");
            $builder3->where('rostentry_id', $rostentry_id->rostentry_id);
            $curshift_id = $builder3->get()->getResult();

            return $curshift_id;
        }
    }

    public function  bdtaskt1m8_11_current_date_emps()
    {
        $today = date('Y-m-d');
        $cr_time =  date("Y-m-d H:i");

        $builder3 = $this->db->table('hrm_emproster_assign');
        $builder3->select("hrm_emproster_assign.*,hrm_employees.first_name,hrm_employees.last_name, hrm_employees.image ,hrm_employee_types.type");
        $builder3->join('hrm_employees','hrm_employees.employee_id = hrm_emproster_assign.emp_id','left');
        $builder3->join("hrm_employee_types", "hrm_employee_types.id = hrm_employees.employee_type", "left");
        $builder3->where('cast(concat(hrm_emproster_assign.emp_startroster_date, " ", hrm_emproster_assign.emp_startroster_time) as datetime) <= ',$cr_time);
        $builder3->where('cast(concat(hrm_emproster_assign.emp_endroster_date," ", hrm_emproster_assign.emp_endroster_time) as datetime) >=',$cr_time);
        $data=$builder3->get()->getResult();

        return $data;

    }

    public function bdtaskt1m8_12_click_shift_data($id, $clickdate)
    {

        $cr_date =  $clickdate;

        $builder3 = $this->db->table('hrm_duty_roster');
        $builder3->select('roster_id');
        $builder3->where('shift_id', $id);
        $builder3->where('roster_start<= ', $cr_date);
        $builder3->where('roster_end >=', $cr_date);
        $data = $builder3->get()->getRow();

        $builder4 = $this->db->table('hrm_emproster_assign');
        $builder4->select('emp_id');
        $builder4->where('roster_id', $data->roster_id);
        $builder4->where('emp_startroster_date', $cr_date);
        $emproster_assign = $builder4->get()->getResult();

        return $emproster_assign;
        
    }

    public function bdtaskt1m8_13_cndate_shift_list($cndate)
    {

        $builder3 = $this->db->table('hrm_emproster_assign');
        $builder3->select('DISTINCT(roster_id)');
        $builder3->where('emp_startroster_date', $cndate);
        $cndaterstrid = $builder3->get()->getResult();

        return $cndaterstrid;        
    }

    public function bdtaskt1m8_14_cuuentshiftid()
    {
        $datetime = date("Y-m-d H:i");

        $builder3 = $this->db->table('hrm_emproster_assign');
        $builder3->select('hrm_duty_roster.shift_id');
        $builder3->join('hrm_duty_roster','hrm_duty_roster.roster_id = hrm_emproster_assign.roster_id','left');
        $builder3->where('cast(concat(hrm_emproster_assign.emp_startroster_date, " ", hrm_emproster_assign.emp_startroster_time) as datetime) <= ',$datetime);
        $builder3->where('cast(concat(hrm_emproster_assign.emp_endroster_date," ", hrm_emproster_assign.emp_endroster_time) as datetime) >=',$datetime);

        $data = $builder3->get()->getRow();

        return $data;
    }

    public function bdtaskt1m8_15_cng_date_currstr_emps($cndate)
    {
        $crtime = date("H:i");

        $builder3 = $this->db->table('hrm_emproster_assign');
        $builder3->select('roster_id');
        $builder3->where('emp_startroster_date', $cndate);
        $builder3->where('cast(emp_startroster_time AS time) <= ', $crtime);
        $builder3->where('cast(emp_endroster_time AS time) >= ', $crtime);

        $data = $builder3->get()->getRow();

        if ($data) {

            $builder4 = $this->db->table('hrm_emproster_assign');
            $builder4->select('emp_id');
            $builder4->where('roster_id', $data->roster_id);
            $builder4->where('emp_startroster_date ', $cndate);

            $builder4->groupBy('emp_id');
            $builder4->orderBy('emp_id','desc');
            
            $data2 = $builder4->get()->getResult();

            return $data2;

        }else{
            return false;
        }

    }

    public function bdtaskt1m8_16_cng_date_emps($cndate)
    {

        $builder3 = $this->db->table('hrm_emproster_assign');
        $builder3->select('roster_id');
        $builder3->where('emp_startroster_date', $cndate);

        $data = $builder3->get()->getRow();

        if ($data) {

            $builder4 = $this->db->table('hrm_emproster_assign');
            $builder4->select('emp_id');
            $builder4->where('roster_id', $data->roster_id);
            $builder4->where('emp_startroster_date ', $cndate);

            $builder4->groupBy('emp_id');
            $builder4->orderBy('emp_id','desc');
            
            $data2 = $builder4->get()->getResult();

            return $data2;

        }else{
            return false;
        }

    }

    public function bdtaskt1m8_17_emp_data_shift_update($data = array())
    {
        return $this->db->table('hrm_emproster_assign')->where('sftasnid',$data["sftasnid"])->update($data);
    }

    public function bdtaskt1m8_06_shiftRosterData(){

        $builder3 = $this->db->table('hrm_emproster_assign');
        $builder3->select("hrm_emproster_assign.*,hrm_duty_roster.*,hrm_empwork_shift.shift_name,hrm_departments.name as department_name");
        $builder3->join('hrm_duty_roster','hrm_duty_roster.roster_id = hrm_emproster_assign.roster_id','left');
        $builder3->join('hrm_empwork_shift','hrm_empwork_shift.shiftid = hrm_duty_roster.shift_id','left');
        $builder3->join('hrm_departments','hrm_departments.id = hrm_duty_roster.department_id','left');

        $builder3->orderBy('hrm_emproster_assign.sftasnid', 'desc');
        $builder3->groupBy('hrm_emproster_assign.roster_id');

        $data = $builder3->get()->getResult();

        return $data;

    }

    public function bdtaskt1m8_07_rstasnInfoDataById($roster_id){

        $builder3 = $this->db->table('hrm_duty_roster');
        $builder3->select("hrm_duty_roster.*,hrm_empwork_shift.*,hrm_empwork_shift.shift_name,hrm_departments.name as department_name");
        $builder3->join('hrm_empwork_shift','hrm_empwork_shift.shiftid = hrm_duty_roster.shift_id','left');
        $builder3->where('hrm_duty_roster.roster_id',$roster_id);
        $builder3->join('hrm_departments','hrm_departments.id = hrm_duty_roster.department_id','left');

        return $builder3->get()->getRow();

    }


}