<?php
class Cm_model extends CI_Model{
	private $_table = 'bank_check_reminder';
    private $bank = 'bank_names';

	public function __construct(){
		parent::__construct();
	}

    /*-----------------------------*
    | get all check by ajax call  
    *------------------------------*/
    public function getCheckList($postData=null){

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
            $searchQuery = "($this->bank.name like '%".$searchValue."%' or $this->_table.check_no like '%".$searchValue."%' or $this->_table.holder_name like'%".$searchValue."%' or $this->_table.due_date like'%".$searchValue."%') ";
         }

         ## Total number of records without filtering
         $this->db->select('count(*) as allcount');
         $this->db->join($this->bank, "$this->bank.id=$this->_table.bank_name", "left");
         if($searchValue != '')
            $this->db->where($searchQuery);
         $records = $this->db->get($this->_table)->result();
         $totalRecords = $records[0]->allcount;

         ## Total number of record with filtering
         $this->db->select('count(*) as allcount');
         $this->db->from($this->_table);
         $this->db->join($this->bank, "$this->bank.id=$this->_table.bank_name", "left");
         if($searchValue != '')
            $this->db->where($searchQuery);
         $records = $this->db->get()->result();
         $totalRecordwithFilter = $records[0]->allcount;

         ## Fetch records
         $this->db->select("$this->_table.*, $this->bank.name");
         $this->db->from($this->_table);
         $this->db->join($this->bank, "$this->bank.id=$this->_table.bank_name", "left");
         if($searchValue != '')
            $this->db->where($searchQuery);
         $this->db->order_by($columnName, $columnSortOrder);
         $this->db->limit($rowperpage, $start);
         $records = $this->db->get()->result();

         $data = array();
         foreach($records as $record ){
            $status = '';
            $button = '';
            if($record->isApproved==1){
                $status .= '<label class="label label-success">'.display('completed').'</label>';
            }elseif($record->isApproved==0){
                $status .= '<label class="label label-warning">'.display('pending').'</label>';
            }elseif($record->isApproved==2){
                $status .= '<label class="label label-warning">'.display('progress').'</label>';
            }else{
                $status .= '<label class="label label-danger">'.display('rejected').'</label>';
            }

            $button .= '<div class="btn-group m-r-2">
                            <button type="button" data-toggle="dropdown" class="btn btn-xs dropdown-toggle btn-success">'.display('status').'
                                <span class="caret"></span>
                            </button>
                            <ul role="menu" class="dropdown-menu">
                                <li><a href="#" data-id="'.$record->id.'" data-data="2" class="approveAction"><i class="fa fa-angle-double-right text-warning"></i> '.display('progress').'</a></li>
                                <li><a href="#" data-id="'.$record->id.'" data-data="1" class="approveAction"><i class="fa fa-check text-success"></i> '.display('completed').'</a></li>
                                <li><a href="#" data-id="'.$record->id.'" data-data="3" class="approveAction"><i class="fa fa-times text-danger"></i> '.display('rejected').'</a></li>
                            </ul>
                        </div>';
            $button .= '<a href="#" class="btn btn-xs btn-primary m-r-2 editAction" data-id="'.$record->id.'" data-toggle="modal" data-target="#addCheck"><i class="fa fa-edit text-white"></i></a>';
            $button .= '<a href="#" class="btn btn-xs btn-info viewAction" data-id="'.$record->id.'" data-toggle="modal" data-target="#checkInfo"><i class="fa fa-eye text-white"></i></a>';
            
            $data[] = array( 
               "bank_name"    =>$record->name,
               "check_no"     =>$record->check_no,
               "holder_name"  =>$record->holder_name,
               "type"         =>$record->type,
               "issue_date"   =>$record->issue_date,
               "due_date"     =>$record->due_date,
               "status"       =>$status,
               "button"       =>$button,
            ); 
         }

         ## Response
         $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
         );

         return $response; 
    }

    /*-----------------------------*
    | update check status 
    *------------------------------*/
    public function checkCompleted($id, $value){
        return $this->db->where('id', $id)->update('bank_check_reminder', array('isApproved'=>$value));
    }

    /*-----------------------------*
    | get check by id 
    *------------------------------*/
    public function getCheckById($id){
        return $this->db->select('bcr.*, bank_names.name as bankName')->from('bank_check_reminder bcr')
                        ->join('bank_names', 'bank_names.id=bcr.bank_name', 'left')
                        ->where('bcr.id', $id)->get()->row();
    }

    /*-----------------------------*
    | daily seen check update
    *------------------------------*/
    public function getLatestDueDate(){
        //$result =  $this->db->query("SELECT * FROM bank_check_reminder WHERE `due_date` >= NOW() AND `due_date`  < NOW() + INTERVAL 5 DAY AND `isApproved`=0;")->result();
        $result =  $this->db->select("$this->_table.*, $this->bank.name")
                            ->from("$this->_table")
                            ->join("$this->bank", "$this->bank.id=$this->_table.bank_name", "left")
                            ->where("$this->_table.due_date >=", date('Y-m-d'))
                            ->where("$this->_table.due_date <", date('Y-m-d', strtotime(date('Y-m-d'). ' + 5 days')))
                            ->where("$this->_table.isApproved",0)
                            ->or_where("$this->_table.isApproved",2)
                            ->get()->result();
        return $result;
    }

    /*-----------------------------*
    | get all bank list
    *------------------------------*/
    public function getBankList(){
        $result =  $this->db->select("*")->from("bank_names")->get()->result();

        $list[''] = makeString(['select', 'bank', 'name']);
        if(!empty($result)){
            foreach ($result as $key => $value) {
                $list[$value->id] = $value->name;
            }
        }
        
        return $list;
    }

}