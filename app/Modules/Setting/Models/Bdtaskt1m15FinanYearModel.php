<?php namespace App\Modules\Setting\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m15FinanYearModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();
        $this->hasCreateAccess = $this->permission->method('financial_year', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('financial_year', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('financial_year', 'delete')->access();
    }

    public function bdtaskt1m15_01_getFinancialYear($postData=null){
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
        $data = array();
        $totalRecords = 0;
        //foreach($list_tables as $list_table){
            //$table = $list_table->table_name;

            $searchQuery = "";
            if($searchValue != ''){
               $searchQuery = " (yearName like '%".$searchValue."%' OR startDate like '%".$searchValue."%' OR endDate like '%".$searchValue."%') ";
            }
          
            ## Fetch records
            $builder3 = $this->db->table('financial_year');
            $builder3->select("*");
            if($searchValue != ''){
               $builder3->where($searchQuery);
            }
            $totalRecordwithFilter = $builder3->countAllResults(false);
            $builder3->orderBy($columnName, $columnSortOrder);
            $builder3->limit($rowperpage, $start);
            $query3   =  $builder3->get();
            $records =   $query3->getResultArray();
            $totalRecords = $builder3->countAll();
            if($searchValue == ''){
                $totalRecords = $totalRecordwithFilter;
            }
            $data = array();
            $active = get_phrases(['active']);
            $inactive = get_phrases(['closed']);
            $close = get_phrases(['close']);
            $update = get_phrases(['update']);
            $pending = get_phrases(['pending']);
            $close_req = get_phrases(['close', 'request']);
            $undo = get_phrases(['undo']);
            $app_close_req = get_phrases(['approve', 'close', 'request']);
            
            foreach($records as $record ){ 
                $button = '';
                $status = '';
                if($record['status']==2 && session('user_role')==1){
                    $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionEdit custool mr-2" data-id="'.$record['id'].'" title="'.$update.'"><i class="far fa-edit"></i></a>';
                    $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC actionActive custool" data-id="'.$record['id'].'" title="'.$active.'"><i class="fa fa-check"></i></a>';
                    $status .= '<span class="badge btn-warning-soft text-warning">'.$pending.'</span>';
                }else if($record['status']==1 && session('user_role')==1){
                    if($record['isCloseReq']==0){
                         $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionClose custool" data-id="'.$record['id'].'" title="'.$close.'"><i class="far fa-times-circle"></i></a>';
                    }
                    $status .= '<span class="badge btn-success-soft mr-1">'.$active.'</span>';
                }else{
                    $status .= '<span class="badge btn-danger-soft">'.$inactive.'</span>';
                }
                if($record['isCloseReq']==1){
                    $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-purple-soft btnC actionCloseUndo custool mr-2" data-id="'.$record['id'].'" title="'.$undo.'"><i class="fa fa-undo"></i></a>';
                    $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC actionAppClReq custool" data-id="'.$record['id'].'" title="'.$app_close_req.'"><i class="far fa-times-circle"></i></a>';
                    $status .= '<span class="badge btn-danger-soft">'.$close_req.'</span>';
                }

                $data[] = array( 
                    'id'       => $record['id'],
                    'yearName' => $record['yearName'],
                    'startDate'=> $record['startDate'],
                    'endDate'  => $record['endDate'],
                    'status'   => $status,
                    'button'   => $button
                ); 
            }
        //}
        ## Response
        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData"               => $data
        );
        return $response; 
    }

    public function bdtaskt1m15_02_checkFyDateExist($startDate, $endDate){
        $query = $this->db->table('financial_year')->select('*')
                            ->groupStart()
                                ->orGroupStart()
                                    ->where('startDate >', $startDate)//input start date less than db date
                                    ->where('startDate <=', $endDate) // input end date equal or more than db date
                                    ->where('endDate >=', $endDate) // input end date equal or less than db date
                                  ->groupEnd()
                                ->orGroupStart()
                                    ->where('startDate <=', $startDate)//input start date equal or more than db date
                                    ->where('endDate >=', $startDate) //input start date equal or less than db date
                                    ->where('endDate <', $endDate)//input end date more than db date
                                  ->groupEnd()
                                ->orGroupStart()
                                    ->where('startDate <=', $startDate)//input start date equal or more than db date
                                    ->where('endDate >=', $endDate)// input end date equal or less than db date
                                  ->groupEnd()
                            ->groupEnd()
                            ->get()->getRow();
        //echo get_last_query();exit;
        if(!empty($query)){
            return true;
        }else{
            return false;
        }
    }
   
}
?>