<?php namespace App\Modules\Account\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;
class Bdtaskt1m8BlncTransModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();
        $this->hasCreateAccess = $this->permission->method('patient_balance_transfer', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('patient_balance_transfer', 'update')->access();
        $this->hasBranchCreate = $this->permission->method('branch_balance_transfer', 'create')->access();
        $this->hasBranchUpdate = $this->permission->method('branch_balance_transfer', 'update')->access();
    }

    /*--------------------------
    | Get receipt voucher
    *--------------------------*/
    public function bdtaskt1m8_01_getPntBTransfer($postData=null){
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
           $searchQuery = " (pbt.trans_id like '%".$searchValue."%' OR file.file_no like '%".$searchValue."%' OR p.nameE like '%".$searchValue."%' OR emp.short_name like '%".$searchValue."%' OR file1.file_no like '%".$searchValue."%' OR p1.nameE like '%".$searchValue."%')";
        }
       
        ## Fetch records
        $builder3 = $this->db->table('patient_balance_transfer as pbt');
        $builder3->select("pbt.*, CONCAT_WS(' ', file.file_no, '-', p.nameE) as from_patient, CONCAT_WS(' ', file1.file_no, '-', p1.nameE) as to_patient, CONCAT_WS(' ', emp.first_name, '-', emp.last_name) as created_by");
        $builder3->join("patient p", "p.patient_id=pbt.from_patient", "left");
        $builder3->join("patient_file file", "file.patient_id=p.patient_id", "left");
        $builder3->join("patient p1", "p1.patient_id=pbt.to_patient", "left");
        $builder3->join("patient_file file1", "file1.patient_id=p1.patient_id", "left");
        $builder3->join("hrm_employees emp", "emp.employee_id=pbt.created_by", "left");
        if($searchValue != ''){
           $builder3->where($searchQuery);
        }
        ## Total number of records with filtering
        $totalRecordwithFilter= $builder3->countAllResults(false);
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
        $approved = get_phrases(['approved']);
        $pending = get_phrases(['pending']);
        $rejeted = get_phrases(['rejected']);
        foreach($records as $record ){ 
            $button = '';
            $action = '';
            $action .='<a href="javascript:void(0);" data-id="'.$record['id'].'" class="btn btn-info-soft btnC viewAction mr-1"><i class="fa fa-eye"></i></a>';
            
            if($record['isApproved']==1){
                $button .='<a href="javascript:void(0);" class="badge badge-success">'.$approved.'</a>';
            }else if($record['isApproved']==0){
                if($this->hasCreateAccess || $this->hasUpdateAccess){
                    $action .='<a href="javascript:void(0);" data-id="'.$record['id'].'" class="btn btn-success-soft btnC statusAction"><i class="fa fa-check"></i></a>';
                }
                $button .='<a href="javascript:void(0);" class="badge badge-warning text-white">'.$pending.'</a>';
            }else{
                $button .='<a href="javascript:void(0);" class="badge badge-danger">'.$rejeted.'</a>';
            }

            $data[] = array( 
                'id'           => $record['id'],
                'trans_id'     => $record['trans_id'],
                'from_patient' => $record['from_patient'],
                'to_patient'   => $record['to_patient'],
                'amount'       => $record['amount'],
                'remarks'      => $record['remarks'],
                'created_by'   => $record['created_by'],
                'trans_date'   => date('d/m/Y', strtotime($record['trans_date'])),
                'created_date' => date('d/m/Y h:i A', strtotime($record['created_date'])),
                'action'       => $action,
                'button'       => $button
            ); 
        }

        ## Response
        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData"               => $data
        );
        return $response; 
    }

    /*--------------------------
    | Get pnt current balance 
    *--------------------------*/
    public function bdtaskt1m8_02_getPntBalance($pntId, $branch_id){
        return $this->db->table('patient p')
                        ->select('p.nameE, p.acc_head, acc.balance')
                        ->join('acc_coa_balance acc', 'acc.headCode=p.acc_head', 'left')
                        ->where('p.patient_id', $pntId)
                        ->get()->getRow();
    }

    /*--------------------------
    | Get patient transaction info
    *--------------------------*/
    public function bdtaskt1m8_03_getPntTransById($transId){
        return $this->db->table('patient_balance_transfer pbt')
                        ->select("pbt.*, CONCAT_WS(' ', file.file_no, '-', p.nameE) as fromPatient, CONCAT_WS(' ', file1.file_no, '-', p1.nameE) as toPatient, p.balance, p1.balance as toBalance, p.acc_head, p1.acc_head as toAccHead")
                        ->join("patient p", "p.patient_id=pbt.from_patient", "left")
                        ->join("patient_file file", "file.patient_id=p.patient_id", "left")
                        ->join("patient p1", "p1.patient_id=pbt.to_patient", "left")
                        ->join("patient_file file1", "file1.patient_id=p1.patient_id", "left")
                        ->where('pbt.id', $transId)
                        ->get()->getRow();
    }

    /*--------------------------
    | Get pnt current balance 
    *--------------------------*/
    public function bdtaskt1m8_04_getBalanceByCode($pntId, $branch_id){
        return $this->db->table('acc_coa_balance')
                        ->select('headCode, balance')
                        ->where('headCode', $pntId)
                        ->get()->getRow();
    }

    /*--------------------------
    | Get branch transfer list
    *--------------------------*/
    public function bdtaskt1m8_05_getBranchTransfer($postData=null){
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
           $searchQuery = " (bbt.trans_id like '%".$searchValue."%' OR file.file_no like '%".$searchValue."%' OR p.nameE like '%".$searchValue."%' OR user.username like '%".$searchValue."%')";
        }
       
        ## Fetch records
        $builder3 = $this->db->table('branch_balance_transfer as bbt');
        $builder3->select("bbt.*, CONCAT_WS(' ', file.file_no, '-', p.nameE) as patient_name, user.username, branch.nameE as from_branch, b1.nameE as tobranch");
        $builder3->join("patient p", "p.acc_head=bbt.head_code", "left");
        $builder3->join("patient_file file", "file.patient_id=p.patient_id", "left");
        $builder3->join("user", "user.emp_id=bbt.created_by", "left");
        $builder3->join("branch", "branch.id=bbt.branch_id", "left");
        $builder3->join("branch b1", "b1.id=bbt.to_branch", "left");
        if($searchValue != ''){
           $builder3->where($searchQuery);
        }
        ## Total number of records with filtering
        $totalRecordwithFilter= $builder3->countAllResults(false);
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
        $approved = get_phrases(['approved']);
        $pending = get_phrases(['pending']);
        $rejeted = get_phrases(['rejected']);
        foreach($records as $record ){ 
            $button = '';
            $action = '';
            $action .='<a href="javascript:void(0);" data-id="'.$record['id'].'" class="btn btn-info-soft btnC viewAction mr-1"><i class="fa fa-eye"></i></a>';
            
            if($record['isApproved']==1){
                $button .='<a href="javascript:void(0);" class="badge badge-success">'.$approved.'</a>';
            }else if($record['isApproved']==0){
                if($this->hasCreateAccess || $this->hasUpdateAccess){
                    $action .='<a href="javascript:void(0);" data-id="'.$record['id'].'" class="btn btn-success-soft btnC statusAction"><i class="fa fa-check"></i></a>';
                }
                $button .='<a href="javascript:void(0);" class="badge badge-warning text-white">'.$pending.'</a>';
            }else{
                $button .='<a href="javascript:void(0);" class="badge badge-danger">'.$rejeted.'</a>';
            }

            $data[] = array( 
                'id'           => $record['id'],
                'trans_id'     => $record['trans_id'],
                'patient_name' => $record['patient_name'],
                'from_branch'  => $record['from_branch'],
                'to_branch'    => $record['tobranch'],
                'amount'       => $record['amount'],
                'remarks'      => $record['remarks'],
                'username'     => $record['username'],
                'trans_date'   => date('d/m/Y', strtotime($record['trans_date'])),
                'created_date' => date('d/m/Y h:i A', strtotime($record['created_date'])),
                'action'       => $action,
                'button'       => $button
            ); 
        }

        ## Response
        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData"               => $data
        );
        return $response; 
    }

    /*--------------------------
    | Get branch transaction info
    *--------------------------*/
    public function bdtaskt1m8_06_getBranchTransById($transId){
        return $this->db->table('branch_balance_transfer bbt')
                        ->select("bbt.*, p.patient_id, CONCAT_WS(' ', file.file_no, '-', p.nameE) as patient_name, accb.balance, branch.nameE as from_branch_name, b1.nameE as to_branch_name")
                        ->join("patient p", "p.acc_head=bbt.head_code", "left")
                        ->join("patient_file file", "file.patient_id=p.patient_id", "left")
                        ->join("acc_coa_balance accb", "accb.headCode=bbt.head_code AND accb.branch_id=bbt.branch_id", "left")
                        ->join("branch", "branch.id=bbt.branch_id", "left")
                        ->join("branch b1", "b1.id=bbt.to_branch", "left")
                        ->where('bbt.id', $transId)
                        ->get()->getRow();
    }

}
?>