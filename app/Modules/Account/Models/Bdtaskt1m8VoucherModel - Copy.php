<?php namespace App\Modules\Account\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;
class Bdtaskt1m8VoucherModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();
        $this->hasJvUpdate = $this->permission->method('journal_voucher', 'update')->access();
        $this->hasJvCreate = $this->permission->method('journal_voucher', 'create')->access();
        $this->hasJvDelete = $this->permission->method('journal_voucher', 'delete')->access();
        $this->hasJvApprCreate = $this->permission->method('voucher_approval', 'create')->access();
        $this->hasJvApprUpdate = $this->permission->method('voucher_approval', 'update')->access();
    }

    /*--------------------------
    | Get type wise voucher list 
    *--------------------------*/
    public function bdtaskt1m8_01_getAllVouchers($postData=null, $type=null){
         $response = array();
         ## Read value
        @$search_date = $postData['search_date'];
        @$branch_id  = $postData['branch_id'];
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
           $searchQuery = " (trans.VNo like '%".$searchValue."%' OR emp1.short_name like '%".$searchValue."%')";
        }
        if($search_date != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
            $searchQuery .= "(trans.VDate = '".$search_date."' ) ";
        }
       
        ## Fetch records
        $builder3 = $this->db->table('acc_vaucher as trans');
        $builder3->select("trans.*, SUM(Debit) as totaldebit, SUM(Credit) as totalcredit, coa.HeadName as dbtcrdhead, '' as branch_name, CONCAT_WS(' ', emp1.first_name, '-', emp1.last_name) as create_by");
        $builder3->join("acc_coa coa", "coa.HeadCode=trans.RevCodde", "left");
        $builder3->join("hrm_employees emp1", "emp1.employee_id=trans.CreateBy", "left");
        // $builder3->join("branch", "branch.id=trans.BranchID", "left");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }

        $builder3->whereIn('trans.Vtype',  $type);
        // Total number of record with filtering
        $builder3->groupBy("trans.VNo");
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        $builder3->groupBy("trans.VNo");
        $totalRecords = $builder3->countAll();
        if($searchQuery == ''){
           $totalRecords = $totalRecordwithFilter;
        }

        $data = array();
        $approved = get_phrases(['approved']);
        $pending = get_phrases(['pending']);
        $rejeted = get_phrases(['rejected']);
        if(in_array('JV', $type)){
            $jv = 1;
        }else{
            $jv = 0;
        }
        $sl = 1;
        foreach($records as $record ){ 
            $button = '';
            $action = '';
            $action .='<a href="javascript:void(0);" data-id="'.$record['VNo'].'" class="btn btn-info-soft btnC viewAction mr-1"><i class="fa fa-eye"></i></a>';
            
            if($record['isApproved']==1){
                $button .='<a href="javascript:void(0);" class="badge badge-success">'.$approved.'</a>';
                $action .='<a href="javascript:void(0);" data-id="'.$record['VNo'].'" class="btn btn-success-soft btnC ReverseAction mr-1" title="Reverse"><i class="hvr-buzz-out fas fa-retweet"></i></a>';
            }else if($record['isApproved']==0){
                if($this->hasJvApprUpdate || $this->hasJvApprCreate){
                    $action .='<a href="javascript:void(0);" data-id="'.$record['VNo'].'" class="btn btn-success-soft btnC statusAction mr-1"><i class="fa fa-check"></i></a>';
                }
                if($this->hasJvUpdate){
                    $action .='<a href="javascript:void(0);" data-id="'.$record['VNo'].'" data-type="'.$record['Vtype'].'" class="btn btn-primary-soft btnC editAction mr-1"><i class="fa fa-edit"></i></a>';
                }
                if($this->hasJvDelete){
                    $action .='<a href="javascript:void(0);" data-id="'.$record['VNo'].'" class="btn btn-danger-soft btnC deleteAction"><i class="far fa-trash-alt"></i></a>';
                }
                $button .='<a href="javascript:void(0);" class="badge badge-warning text-white">'.$pending.'</a>';
            }else{
                $button .='<a href="javascript:void(0);" class="badge badge-danger">'.$rejeted.'</a>';
            }

            $data[] = array( 
                'ID'          => ($postData['start'] ? $postData['start'] : 0) + $sl++,
                'VNo'         => $record['VNo'],
                'Vtype'       => $record['Vtype'],
                'VDate'       => date('d/m/Y', strtotime($record['VDate'])),
                'Narration'   => $record['Narration'],
                'totaldebit'  => $record['totaldebit'],
                'totalcredit' => $record['totalcredit'],
                'branch_name' => $record['dbtcrdhead'],
                'created_by'  => $record['create_by'],
                'updated_by'  => '',
                'action'      => $action,
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

    /*--------------------------
    | Get all account head code
    *--------------------------*/
    public function bdtaskt1m8_02_getAccHead(){
        return $this->db->table('acc_coa')->select('HeadCode, HeadName, nameE, nameA')->where('IsTransaction', 1)->where('IsActive', 1)->get()->getResult();
    }

    /*--------------------------
    | Get voucher details info
    *--------------------------*/
    public function bdtaskt1m8_03_getVoucherDetails($VNo){
        $query = $this->db->table('acc_vaucher trans')
                          ->select("trans.*, coa.HeadName as dbtcrdHead, CONCAT_WS(' ', emp1.first_name, emp1.last_name) as created_by, '' as typeName")
                          ->join('acc_coa coa', 'coa.HeadCode=trans.RevCodde', 'left')
                          ->join('hrm_employees emp1', 'emp1.employee_id=trans.CreateBy', 'left')
                        //   ->join('voucher_type_list vtp', 'vtp.type=trans.Vtype', 'left')
                          ->where('trans.VNo', $VNo)
                          ->get()->getRow();
        if(!empty($query)) {
            $query->details = $this->db->table('acc_vaucher trans1')
                          ->select('trans1.*, acc_coa.HeadName,acc_subcode.name as subname,(rv.HeadName) as revhead,trans1.subType')
                          ->join('acc_coa', 'acc_coa.HeadCode=trans1.COAID', 'left')
                          ->join('acc_coa rv', 'rv.HeadCode=trans1.RevCodde', 'left')
                          ->join('acc_subcode', 'acc_subcode.id=trans1.subCode', 'left')
                          ->where('trans1.VNo', $VNo)
                          ->get()->getResult();
            return  $query;
        }else{
            return false;
        }      
    }

    /*--------------------------
    | Get all account transaction head code
    *--------------------------*/
    public function bdtaskt1m8_04_getTransAccHead($text){
        $exceptheads = [];
        $cashheads = $this->bdtaskt1m8_07_getCreditOrDebitAcc();
            if($cashheads){
                foreach($cashheads as $cashids){
                $exceptheads[] = $cashids->id;   
                }
            } 
            
        $inphead = $this->db->table('acc_coa')->select('HeadCode as id,HeadName as text')->where('HeadType','I')->where('HeadLevel',4)->get()->getResult();    
           
        

        if($inphead){
            foreach($inphead as $itrfexchilds){
                $exceptheads[] = $itrfexchilds->id;
            }
        }

       

        if($exceptheads){

        return $this->db->table('acc_coa')->select("HeadCode as id, CONCAT_WS(' ', HeadCode, '-', HeadName) as text")
                        ->where('HeadLevel', 4)
                        ->where('IsActive', 1)
                    ->whereNotIn('HeadCode',$exceptheads)
                        ->groupStart()
                            ->like('HeadCode', $text)
                            ->orLike('HeadName',$text)
                        ->groupEnd()
                        ->get()->getResult();
        }else{
            return [];
        }
    }

    /*--------------------------
    | Get all account transaction head code direct
    *--------------------------*/
    public function bdtaskt1m8_04_getTransAccHead_withoutwrite(){
        $exceptheads = [];
        $cashheads = $this->bdtaskt1m8_07_getCreditOrDebitAcc();
            if($cashheads){
                foreach($cashheads as $cashids){
                $exceptheads[] = $cashids->id;   
                }
            } 
            
        $inphead = $this->db->table('acc_coa')->select('HeadCode as id,HeadName as text')->where('HeadType','I')->where('HeadLevel',4)->get()->getResult();    
           
        

        if($inphead){
            foreach($inphead as $itrfexchilds){
                $exceptheads[] = $itrfexchilds->id;
            }
        }

       

        if($exceptheads){

        return $this->db->table('acc_coa')->select("HeadCode as id, CONCAT_WS(' ', HeadCode, '-', HeadName) as text")
                        ->where('HeadLevel', 4)
                        ->where('IsActive', 1)
                    ->whereNotIn('HeadCode',$exceptheads)
                        ->get()->getResult();
        }else{
            return [];
        }
    }

    /*--------------------------
    | Get all account transaction head code without cash
    *--------------------------*/
    public function bdtaskt1m8_04_getTransAccHeadwithoutcash($text){
        $exceptheads = [];
        $cashheads = $this->bdtaskt1m8_07_getCreditOrDebitAcc();
            if($cashheads){
                foreach($cashheads as $cashids){
                $exceptheads[] = $cashids->id;   
                }
            } 
            
        return $this->db->table('acc_coa')->select("HeadCode as id,HeadName as text")
                        ->where('IsActive', 1)
                        ->where('HeadLevel', 4)
                        ->whereNotIn('HeadCode',$exceptheads)
                        ->groupStart()
                            ->like('HeadCode', $text)
                            ->orLike('HeadName',$text)
                        ->groupEnd()
                        ->get()->getResult();
    }

    /*--------------------------
    | Get all account transaction head code without cash
    *--------------------------*/
    public function bdtaskt1m8_05_editgetTransAccHeadwithoutcash(){
        $exceptheads = [];
        $cashheads = $this->bdtaskt1m8_07_getCreditOrDebitAcc();
            if($cashheads){
                foreach($cashheads as $cashids){
                $exceptheads[] = $cashids->id;   
                }
            } 
            
        return $this->db->table('acc_coa')->select("HeadCode as id,HeadName as text")
                        ->where('IsActive', 1)
                        ->where('HeadLevel', 4)
                        ->whereNotIn('HeadCode',$exceptheads)
                        ->get()->getResult();
    }

     /*--------------------------
    | Get all account transaction head code
    *--------------------------*/
    public function bdtaskt1m8_04_getTransAccHeadCreditvoucher($text){
        $exceptheads = [];
        $cashheads = $this->bdtaskt1m8_07_getCreditOrDebitAcc();
            if($cashheads){
                foreach($cashheads as $cashids){
                $exceptheads[] = $cashids->id;   
                }
            }  
            
                
        $exphead = $this->db->table('acc_coa')->select('Headcode as id,HeadName as text')->where('HeadType','E')->where('HeadLevel', 4)->get()->getResult();
     

        if($exphead){
            foreach($exphead as $itrfexchilds){
                $exceptheads[] = $itrfexchilds->id;
            }
        }

       
        return $this->db->table('acc_coa')->select("HeadCode as id, CONCAT_WS(' ', HeadCode, '-', HeadName) as text")
                        ->where('HeadLevel', 4)
                        ->where('IsActive', 1)
                        ->whereNotIn('HeadCode',$exceptheads)
                        ->groupStart()
                            ->like('HeadCode', $text)
                            ->orLike('HeadName',$text)
                        ->groupEnd()
                        ->get()->getResult();
    }

   

    public function bdtaskt1m8_05_getSubtypes($headcode)
    {
        $result = $this->db->table('acc_coa')
        ->select("*")
        ->where('HeadCode', $headcode)
        ->get()
        ->getRow();
      
        $data = [];
        if($result->subType != 1){
            $data = $this->db->table('acc_subcode')
            ->select("id, name as text")
            ->where('subTypeId', $result->subType)
            ->get()
            ->getResult();   
        }
        
        $out_put = array(
            'data'       => $data,
            'subtype_id' => ($result->subType?$result->subType:1)
        );
return $out_put;
    }

    /*--------------------------
    | Get max voucher number
    *--------------------------*/
    public function bdtaskt1m8_05_getMaxVoucher($vtype){
        $result = $this->db->table('acc_transaction')
                        ->select("VNo")
                        ->where('Vtype', $vtype)
                        ->orderBy('ID', 'DESC')
                        ->get()
                        ->getRow();
        return $result;
    }

    /*--------------------------
    | Get type wise voucher list 
    *--------------------------*/
    public function bdtaskt1m8_06_getTypeWiseVoucher($postData=null, $type=null){
         $response = array();
         ## Read value
        @$search_date = $postData['search_date'];
        @$branch_id  = $postData['branch_id'];
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
           $searchQuery = " (jv.id like '%".$searchValue."%' OR emp.short_name like '%".$searchValue."%')";
        }
        if($search_date != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
            $searchQuery .= "(jv.VDate = '".$search_date."' ) ";
        }
    
      
        ## Fetch records
        $builder3 = $this->db->table('acc_vaucher as jv');
        $builder3->select("jv.*,sum(Credit) as total_credit,sum(Debit) as total_debit, CONCAT_WS(' ', emp.first_name, '-', emp.last_name) as created_by, '' as branch_name, '' as updated_by");
        $builder3->join("hrm_employees emp", "emp.employee_id=jv.CreateBy", "left");
        // $builder3->join("hrm_employees emp1", "emp1.employee_id=jv.updated_by", "left");
        $builder3->where('jv.vtype',  $type);
        $builder3->groupBy('jv.VNo');
        $totalRecords = $builder3->countAllResults(false);
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }
        $builder3->groupBy('jv.VNo');
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->groupBy('jv.VNo');
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        $data = array();
        $approved = get_phrases(['approved']);
        $pending = get_phrases(['pending']);
        $rejeted = get_phrases(['rejected']);
        $sl = 1;
        foreach($records as $record ){ 
            $button = '';
            $action = '';
            $action .='<a href="javascript:void(0);" data-id="'.$record['VNo'].'" class="btn btn-info-soft btnC viewAction mr-1"><i class="fa fa-eye"></i></a>';
            
            if($record['isApproved']==1){
                $button .='<a href="javascript:void(0);" class="badge badge-success">'.$approved.'</a>';
                $action .='<a href="javascript:void(0);" data-id="'.$record['VNo'].'" class="btn btn-success-soft btnC ReverseAction mr-1" title="Reverse"><i class="hvr-buzz-out fas fa-retweet"></i></a>';
            }else if($record['isApproved']==0){
                if($this->hasJvApprUpdate || $this->hasJvApprCreate){
                    $action .='<a href="javascript:void(0);" data-id="'.$record['VNo'].'" class="btn btn-success-soft btnC statusAction mr-1"><i class="fa fa-check"></i></a>';
                }

                if($this->hasJvUpdate){
                    $action .='<a href="javascript:void(0);" data-id="'.$record['VNo'].'" data-type="'.$record['Vtype'].'" class="btn btn-primary-soft btnC editAction mr-1"><i class="fa fa-edit"></i></a>';
                }
               
                if($this->hasJvDelete){
                    $action .='<a href="javascript:void(0);" data-id="'.$record['VNo'].'" class="btn btn-danger-soft btnC deleteAction"><i class="far fa-trash-alt"></i></a>';
                }
                $button .='<a href="javascript:void(0);" class="badge badge-warning text-white">'.$pending.'</a>';
            }else{
                $button .='<a href="javascript:void(0);" class="badge badge-danger">'.$rejeted.'</a>';
            }
            $data[] = array( 
                'id'          => ($postData['start'] ? $postData['start'] : 0) + $sl++,
                'vtype'       => $record['VNo'],
                'voucher_date'=> date('d/m/Y', strtotime($record['VDate'])),
                'remarks'     => $record['Narration'],
                'totaldebit'  => $record['total_credit'],
                'totalcredit' => $record['total_debit'],
                'vat'         => 0,
                'branch_name' => $record['branch_name'],
                'created_by'  => $record['created_by'],
                'updated_by'  => $record['updated_by'],
                'action'      => $action,
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

    /*--------------------------
    | Get voucher details info
    *--------------------------*/
    public function bdtaskt1m8_07_getVoucherDetailsById($id){
        $query = $this->db->table('journal_vouchers jv')
                          ->select("jv.*, CONCAT_WS(' ', emp.first_name, emp.last_name) as created_by, CONCAT_WS(' ', emp1.first_name, emp1.last_name) as updated_by, vtp.nameE as typeName")
                          ->join('hrm_employees emp', 'emp.employee_id=jv.created_by', 'left')
                          ->join('hrm_employees emp1', 'emp1.employee_id=jv.updated_by', 'left')
                          ->join('voucher_type_list vtp', 'vtp.type=jv.vtype', 'left')
                          ->where('jv.id', $id)
                          ->get()->getRow();
        if(!empty($query)) {
            $query->details = $this->db->table('journal_voucher_details jvd')
                          ->select('jvd.*,acc_coa.HeadName,(rv.HeadName) as revHead')
                          ->join('acc_coa', 'acc_coa.HeadCode=jvd.head_code', 'left')
                          ->join('acc_coa rv', 'rv.HeadCode=jvd.RevCodde')
                          ->where('jvd.voucher_id', $id)
                          ->get()->getResult();
            return  $query;
        }else{
            return false;
        }      
    }

     /*--------------------------
    | Get voucher details info
    *--------------------------*/
    public function bdtaskt1m8_09_jv(){
        $query= $this->db->table('acc_transaction')
                          ->select('*, SUM(Debit) as debit, SUM(Credit) as credit')
                          ->where('Vtype', 'JV')
                          ->groupBy('VNo')
                          ->get()->getResult();
            return  $query;
    }

    /*--------------------------
    | Get voucher details info
    *--------------------------*/
    public function bdtaskt1m8_09_jvd(){
        $query= $this->db->table('acc_transaction')
                          ->select('*')
                          ->where('Vtype', 'JV')
                          ->get()->getResult();
            return  $query;
    }

        /*--------------------------
    | Get credit or debit account headCode
    *--------------------------*/
    public function bdtaskt1m8_07_getCreditOrDebitAcc(){
        
        $tran_head = [];
        $predefine_acc = $this->predefinedAccounts();
        $cash_equva = $this->db->table('acc_coa')->select('PHeadCode')->where('HeadCode',$predefine_acc->cashCode)->get()->getRow();
        $ids = ["$predefine_acc->bankCode","$cash_equva->PHeadCode"];
        $firstchild = $this->db->table('acc_coa')
        ->select("HeadCode as id, HeadName as text")
        ->whereIn('PHeadCode', $ids)
        ->where('HeadType', 'A')
        ->where('HeadLevel', 4)
        ->where('IsActive',1)
        ->orderBy('HeadName', 'asc')
        ->get()
        ->getResult();
       
     
        
        return $firstchild;
    }

    public function bdtaskt1m8_07_getMaxvoucherno($type)
    {
         $result = $this->db->table('acc_vaucher')
                        ->select("VNo")
                        ->where('Vtype', $type)
                        ->orderBy('id','desc')
                        ->get()
                        ->getRow();

        $typed =  $this->db->table('acc_voucher_type')
        ->select("*")
        ->where('typen', $type)
        ->get()
        ->getRow();
        $vno = ($result?explode('-',$result->VNo):0);
        $vn = ($result?$vno[1]+1:1);
        return $voucher = $typed->prefix_code.($vn);                
    }

    public function setting_info()
    {
        return $settingdata = $this->db->table('setting')->select('*')->get()->getRow();
    }

    public function bdtaskt1m8_09_approveVoucher($id)
    {
        $vaucherdata = $this->db->table('acc_vaucher')
        ->select('*')
        ->where('VNo',$id)
        ->get()->getResult();
        $action = '';
    $ApprovedBy=session('id');
    $approvedDate=date('Y-m-d H:i:s');
if($vaucherdata) {
foreach($vaucherdata as $row) {           
   $transationinsert = array(     
             'vid'            =>  $row->id,
             'FyID'           =>  $row->fyear,
             'VNo'            =>  $row->VNo,
             'Vtype'          =>  $row->Vtype,
             'referenceNo'    =>  $row->referenceNo,
             'VDate'          =>  $row->VDate,
             'COAID'          =>  $row->COAID,     
             'Narration'      =>  $row->Narration,     
             'ledgerComment'  =>  $row->ledgerComment,     
             'Debit'          =>  $row->Debit, 
             'Credit'         =>  $row->Credit,     
             'IsPosted'       =>  1,    
             'RevCodde'       =>  $row->RevCodde,    
             'subType'        =>  $row->subType,     
             'subCode'        =>  $row->subCode,     
             'IsAppove'       =>  1,                      
             'CreateBy'       => $ApprovedBy,
             'CreateDate'     => $approvedDate
           );          
   $this->db->table('acc_transaction')->insert($transationinsert); 
   $revercetransationinsert = array( 
             'vid'            =>  $row->id,    
             'FyID'           =>  $row->fyear,
             'VNo'            =>  $row->VNo,
             'Vtype'          =>  $row->Vtype,
             'referenceNo'    =>  $row->referenceNo,
             'VDate'          =>  $row->VDate,
             'COAID'          =>  $row->RevCodde,     
             'Narration'      =>  $row->Narration,     
             'ledgerComment'  =>  $row->ledgerComment,     
             'Debit'          =>  $row->Credit, 
             'Credit'         =>  $row->Debit,     
             'IsPosted'       =>  1,    
             'RevCodde'       =>  $row->COAID,    
             'subType'        =>  $row->subType,     
             'subCode'        =>  $row->subCode,     
             'IsAppove'       =>  1,                      
             'CreateBy'       => $ApprovedBy,
             'CreateDate'     => $approvedDate
           ); 
           $this->db->table('acc_transaction')->insert($revercetransationinsert);  
}
}
$action = 1;
$upData = array(
         'VNo'          => $id,
         'isApproved'   => $action,
         'approvedBy'   => $ApprovedBy,
         'approvedDate' => $approvedDate,
         'status'       => $action
       );
return $this->db->table('acc_vaucher')->where('VNo',$id)->update($upData);
    }

    public function bdtaskt1m8_09_reverseVoucher($id)
    {
    $this->db->table('acc_transaction')->where('VNo',$id)->delete();
    $action = 1;
    $approvedDate=date('Y-m-d H:i:s');

            $upData = array(
                    'VNo'          => $id,
                    'isApproved'   => 0,
                    'approvedBy'   => '',
                    'approvedDate' => $approvedDate,
                    'status'       => $action
                );
return $this->db->table('acc_vaucher')->where('VNo',$id)->update($upData);
    }


    public function bdtaskt1m8_10_deleteVoucher($vno)
    {
        $this->db->table('acc_transaction')->where('VNo',$vno)->delete();
        return $this->db->table('acc_vaucher')->where('VNo',$vno)->delete();
    }

    public function getActiveFiscalyear()
    {
         $fiscalyear = $this->db->table('financial_year')->select('*')->where('status',1)->get()->getRow();
         return ($fiscalyear?$fiscalyear->id:0);
    }

    public function predefinedAccounts()
    {
        return $data = $this->db->table('acc_predefine_account')->select('*')->get()->getRow();
    }

    public function bdtaskt1m8_04_getTransAccHead_subtypes($subtypes)
    {
        $query= $this->db->table('acc_subcode')
        ->select('*')
        ->where('subTypeId', $subtypes)
        ->get()->getResult();
       return  $query;
    }

}
?>