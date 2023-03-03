<?php namespace App\Modules\Account\Models;
use CodeIgniter\Model;
class Bdtaskt1m8ReportModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->lanColumn = session('defaultLang')=='english'?'nameE':'nameA';
    }

    /*--------------------------
    | Get transaction voucher list 
    *--------------------------*/
    public function bdtaskt1m8_01_getAllVoucherList(){
        $result = $this->db->table('acc_transaction')
                        ->select("VNo as id, VNo as text")
                        ->groupBy('VNo')
                        ->get()
                        ->getResult();
        return $result;
    }

    /*--------------------------
    | Get transation details by voucher Id
    *--------------------------*/
    public function bdtaskt1m8_02_getTransByVId($id){
        $result = $this->db->table('acc_transaction acc')
                        ->select("acc.*, emp.first_name as short_name, emp.last_name as nameE, acc_coa.HeadName")
                        ->join('acc_coa', 'acc_coa.HeadCode=acc.COAID', 'left')
                        ->join('hrm_employees emp', 'emp.employee_id=acc.CreateBy', 'left')
                        ->where('acc.VNo', $id)
                        ->get()
                        ->getResult();
        return $result;
    }

    /*--------------------------
    | Get all transation by user Id
    *--------------------------*/
    public function bdtaskt1m8_03_getUserWiseReports($branch_id, $userId, $from=null,  $to=null){
        $builder = $this->db->table('acc_transaction trans');
        $builder->select("trans.*, acc_coa.HeadName , CONCAT_WS(' ', emp.first_name, emp.last_name) as created_by");
        $builder->join("hrm_employees emp", "emp.employee_id=trans.CreateBy", "left");
        $builder->join("acc_coa", "acc_coa.HeadCode=trans.COAID", "left");
        $builder->where("trans.CreateBy", $userId);
        if($from !=null && $to !=null){
            $builder->where("trans.VDate >=", $from);
            $builder->where("trans.VDate <=", $to);
        }
        return $builder->get()->getResult();
    }

    /*--------------------------
    | Get all transation by patient Id
    *--------------------------*/
    public function bdtaskt1m8_04_getPntByReports($branch_id, $pId, $from=null,  $to=null){
        $builder = $this->db->table('acc_transaction trans');
        $builder->select("trans.*, acc_coa.HeadName , CONCAT_WS(' ', emp.first_name, emp.last_name) as created_by");
        $builder->join("hrm_employees emp", "emp.employee_id=trans.CreateBy", "left");
        $builder->join("acc_coa", "acc_coa.HeadCode=trans.COAID", "left");
        $builder->where("trans.PatientID", $pId);
        if($from !=null && $to !=null){
            $builder->where("trans.VDate >=", $from);
            $builder->where("trans.VDate <=", $to);
        }
        return $builder->get()->getResult();
    }

    /*--------------------------
    | Get all account with active
    *--------------------------*/
    public function bdtaskt1m8_05_trial_balance_report($FromDate,$ToDate,$WithOpening){
        $values1 = array("A", "L");
        $values2 = array("I", "E");
        if($WithOpening)
            $WithOpening=true;
        else
            $WithOpening=false;

        $oResultTr = $this->db->table('acc_coa')
                             ->select('HeadCode, HeadName')
                             //->where('IsGL',1)
                             ->whereIn('HeadType',$values1)
                             ->where('IsActive',1)
                             ->orderBy('HeadCode')
                             ->get()
                             ->getResultArray();

        $oResultInEx =  $this->db->table('acc_coa')
                             ->select('HeadCode, HeadName')
                             //->where('IsGL',1)
                             ->whereIn('HeadType',$values2)
                             ->where('IsActive',1)
                             ->orderBy('HeadCode')
                             ->get()
                             ->getResultArray();
        $data = array(
            'oResultTr'   => $oResultTr,
            'oResultInEx' => $oResultInEx,
            'WithOpening' => $WithOpening
        );
        return $data;
    }

    /*--------------------------
    | Get Fixed assets
    *--------------------------*/
    public function bdtaskt1m8_06_fixed_assets()
    {
        return $result = $this->db->table('acc_coa')
                              ->select('*')
                              ->where('PHeadName','Assets')
                              ->where('PHeadName !=','Patients')
                              ->get()
                              ->getResultArray();
    }

    /*--------------------------
    | Get liabilities
    *--------------------------*/
    public function bdtaskt1m8_07_liabilities_data(){
        return $result = $this->db->table('acc_coa')
                    ->select('*')
                    ->where('PHeadName','Liabilities')
                    ->get()
                    ->getResultArray();
    }

    /*--------------------------
    | Get incomes accounts 
    *--------------------------*/
    public function bdtaskt1m8_08_income_fields(){
        return $result = $this->db->table('acc_coa')
            ->select('*')
            ->where('PHeadName','Incoms')
            ->get()
            ->getResultArray();
    }

    /*--------------------------
    | Get expense coa
    *--------------------------*/
    public function bdtaskt1m8_09_expense_fields(){
         return $result = $this->db->table('acc_coa')
            ->select('*')
            ->where('PHeadName','Expenses')
            ->get()
            ->getResultArray();
    }

    /*--------------------------
    | Get liabilities info
    *--------------------------*/
    public function bdtaskt1m8_10_liabilities_info($head_name){
        return $result = $this->db->table('acc_coa')
            ->select('*')
            ->where('PHeadName',$head_name)
            ->get()
            ->getResultArray();   

    }

    /*--------------------------
    | Get liabilities balance
    *--------------------------*/
    public function bdtaskt1m8_11_liabilities_balance($head_code,$from_date,$to_date){
        return  $result = $this->db->table('acc_transaction')
              ->select('(sum(Credit)-sum(Debit)) as balance,COAID')
              ->where('COAID',$head_code)
              ->where('VDate >=',$from_date)
              ->where('VDate <=',$to_date)
              ->where('IsAppove',1)
              ->get()
              ->getResultArray();
    }

    /*--------------------------
    | Get income balance
    *--------------------------*/
    public function bdtaskt1m8_12_income_balance($head_code,$from_date,$to_date){
       return $result = $this->db->table('acc_transaction')
                           ->select("(sum(Debit)-sum(Credit)) as balance,COAID")
                           ->where('COAID',$head_code)
                           ->where('VDate >=',$from_date)
                           ->where('VDate <=',$to_date)
                           ->where('IsAppove',1)
                           ->get()
                           ->getResultArray();
      
   }

    /*--------------------------
    | Get liabilities 
    *--------------------------*/
    public function bdtaskt1m8_13_liabilities_info_tax($head_name){
        $records = $this->db->table('acc_coa')
                  ->select("*")
                  ->where('HeadName',$head_name)
                  ->get()
                  ->getResultArray();
        return  $records ;   

    }

    /*--------------------------
    | Get assets info
    *--------------------------*/
    public function bdtaskt1m8_14_assets_info($head_name){
         return $this->db->table('acc_coa')
                ->select("*")
                ->where('PHeadName',$head_name)
                ->groupBy('HeadCode')
                ->get()->getResultArray();     

    } 

    /*--------------------------
    | Get assets balance
    *--------------------------*/
    public function bdtaskt1m8_15_assets_balance($head_code,$from_date,$to_date){
         return $this->db->table('acc_transaction')
                         ->select("(sum(Debit)-sum(Credit)) as balance")
                         ->where('COAID',$head_code)
                         ->where('VDate >=',$from_date)
                         ->where('VDate <=',$to_date)
                         ->where('IsAppove',1)
                         ->get()->getResultArray(); 
    }

    /*--------------------------
    | Get account childs by head name
    *--------------------------*/
    public function bdtaskt1m8_16_asset_childs($head_name){
        return $this->db->table('acc_coa')
                  ->select("*")
                  ->where('PHeadName',$head_name)
                  ->groupBy('HeadCode')
                  ->get()->getResultArray();    
    }

    /*--------------------------
    | Get transactions by ids 
    *--------------------------*/
    public function bdtaskt1m8_17_getTransByIds($ids){
        return $this->db->table('acc_transaction as trans')
                  ->select("trans.*, acc_coa.HeadName, vt.$this->lanColumn as typeName, emp.first_name as employeeName")
                  ->join('acc_coa', 'acc_coa.HeadCode=trans.COAID', 'left')
                  ->join('hrm_employees emp', 'emp.employee_id=trans.CreateBy', 'left')
                  ->join('voucher_type_list vt', 'vt.type=trans.Vtype', 'left')
                  ->whereIn('trans.ID',$ids)
                  ->orderBy('trans.VNo')
                  ->get()->getResult();    
    }

    public function bdtaskt1m8_18_getTransHead()
    {
        $result = $this->db->table('acc_coa')
                     ->select("HeadCode as id, CONCAT_WS(' ',HeadCode, '-', HeadName) as text")
                     ->where('IsTransaction',1)
                     ->orderBy('HeadName','asc') 
                     ->get()
                     ->getResult();
        return $result;
    }

    /*--------------------------
    | Get child accounts
    *--------------------------*/
    public function bdtaskt1m8_19_getChilds($head_code){
        return $this->db->table('acc_coa')
                  ->select("*")
                  ->where('IsActive', 1)
                  ->whereNotIn('HeadCode', [$head_code])
                  ->like('HeadCode',$head_code, 'after')
                  ->get()->getResultArray();    
    }

    // prebalance calculation
    public function bdtaskt1m8_19_getTrialDetails($parent_head,$from, $to, $branchId=null)
    {
        $query = $this->db->table('acc_transaction t');
        $query->select('a.HeadCode, a.HeadName, SUM(t.Debit) as Debit, SUM(t.Credit) as Credit');
        $query->join('acc_coa a','a.HeadCode=t.COAID');
        $query->where('t.IsAppove',1);
        $query->where('t.VDate >=',$from);
        $query->where('t.VDate <=',$to);
        $query->where('a.PHeadName',$parent_head);
        $query->groupBy('t.COAID');
        $query1 = $query->get();
        $result = $query1->getResult();
        return $result;
    }

    /*--------------------------
    | Get all account with active
    *--------------------------*/
    public function bdtaskt1m8_20_exportTrialBalance($branch_id, $FromDate, $ToDate){
        $values1 = array("A", "L");
        $values2 = array("I", "E");

        $oResultTr = $this->db->table('acc_coa')
                             ->select('HeadCode, HeadName')
                             ->whereIn('HeadType',$values1)
                             ->where('IsActive',1)
                             ->orderBy('HeadCode')
                             ->get()
                             ->getResult();
        $i = 0;
        foreach ($oResultTr as $value) {
            $oResultTr[$i]->resultAL = $this->db->table('acc_transaction')->select('SUM(Debit) as Debit, SUM(Credit) as Credit')
                                                 ->where('IsAppove', 1)
                                                 ->where("VDate >=", $FromDate)
                                                 ->where("VDate <=", $ToDate)
                                                 ->where('COAID', $value->HeadCode)
                                                 ->get()->getRow();
            $oResultTr[$i]->balance = $this->db->table('acc_transaction')
                                             ->select('(sum(Debit) - sum(Credit)) as total')
                                             ->where('IsAppove',1)
                                             ->where('VDate < ',$FromDate)
                                             ->where('COAID',$value->HeadCode)
                                             ->get()
                                             ->getRow();
            $i++;
        }

        $oResultInEx =  $this->db->table('acc_coa')
                             ->select('HeadCode, HeadName')
                             ->whereIn('HeadType',$values2)
                             ->where('IsActive',1)
                             ->orderBy('HeadCode')
                             ->get()
                             ->getResult();
        $j = 0;
        foreach ($oResultInEx as $value) {
            $oResultInEx[$j]->resultAL = $this->db->table('acc_transaction')->select('SUM(Debit) as Debit, SUM(Credit) as Credit')
                                                 ->where('IsAppove', 1)
                                                 ->where("VDate >=", $FromDate)
                                                 ->where("VDate <=", $ToDate)
                                                 ->where('COAID', $value->HeadCode)
                                                 ->get()->getRow();
            $oResultInEx[$j]->balance = $this->db->table('acc_transaction')
                                             ->select('(sum(Debit) - sum(Credit)) as total')
                                             ->where('IsAppove',1)
                                             ->where('VDate < ',$FromDate)
                                             ->where('COAID',$value->HeadCode)
                                             ->get()
                                             ->getRow();
            $j++;
        }

        $data = array(
            'AL'=> $oResultTr,
            'IE'=> $oResultInEx
        );
        return $data;
    }

    /*--------------------------
    | Get type wise voucher list
    *--------------------------*/
    public function bdtaskt1m8_21_getTrialBalanceData($postData=null){
        $response = array();
        ## Read value
        @$from_date = !empty($postData['from_date'])?$postData['from_date']:date('Y-m-d');
        @$to_date = !empty($postData['to_date'])?$postData['to_date']:date('Y-m-d');
        @$branch_id = !empty($postData['branch_id'])?$postData['branch_id']:session('branchId');
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
           $searchQuery = " (HeadCode like '%".$searchValue."%' OR HeadName like '%".$searchValue."%')";
        }
        
        ## Fetch records
        $builder3 = $this->db->table('acc_coa');
        $builder3->select("*");
        //$builder3->join("acc_transaction trans", "trans.COAID=coa.HeadCode", "left");
        if($searchValue != ''){
           $builder3->where($searchQuery);
        }
        // if($branch_id > 0){
        //     $builder3->where('trans.BranchID',  $branch_id);
        // }
        // $builder3->where('trans.VDate >=',  $from_date);
        // $builder3->where('trans.VDate <=',  $to_date);
        $builder3->where('IsActive', 1);
        $builder3->where('IsTransaction', 1);
        //$builder3->groupBy('coa.HeadCode');
         // Total number of record with filtering
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        //echo get_last_query();exit();
        $totalRecords = $builder3->countAll();
        if($searchQuery == ''){
           $totalRecords = $totalRecordwithFilter;
        }
        $data = array();
        
        $totalOpenDebit=0;
        $totalOpenCredit=0;
        $totalCurentDebit=0;
        $totalCurentCredit=0;
        $totalCloseDebit=0;
        $totalCloseCredit=0;
        $totalbalanceDebit=0;
        $totalbalanceCredit=0;
        foreach($records as $record ){ 

            $opening =   $this->get_opening_balance($record['HeadCode'],$from_date,$to_date); 
            $transsummery  = $this->get_general_ledger_report($record['HeadCode'],$from_date,$to_date,0,0);

            $totalbalanceDebit=0;
            $totalbalanceCredit=0;
            $copenDebit=0;
            $copenCredit=0;
        //    print_r($transsummery);
           
            if($record['HeadType'] == 'A' || $record['HeadType'] == 'E') { 
                if($opening !=0) {
                    $totalOpenDebit += $opening;
                    $copenDebit     += $opening;                                       
                }                                  

            } else { 
                if($opening !=0) {
                    $totalOpenCredit += $opening;
                    $copenCredit     += $opening;
                } 
            }
            
            $sumdebit = (!empty($transsummery)?$transsummery[0]->Debit:null);
            $sumcredit = (!empty($transsummery)?$transsummery[0]->Credit:null);
            if($sumdebit != null) {
             $totalCurentDebit   += $sumdebit;                                
           } 
           if($sumcredit != null) {
             $totalCurentCredit  += $sumcredit; 
            }  
            
            $totalbalanceDebit   +=  $copenDebit + ($sumdebit != null? $sumdebit : '0');
            $totalbalanceCredit  +=  $copenCredit + ($sumcredit != null ? $sumcredit : '0') ;                             
             $totalCloseDebit   += $totalbalanceDebit;
             $totalCloseCredit  += $totalbalanceCredit; 

            $data[] = array( 
                'HeadCode'    => '<a href="'.base_url('account/reports/GeneralLForm').'">'.$record['HeadCode'].'</a>',
                'HeadName'    => $record['HeadName'],
                'opening_dbt' => (($record['HeadType'] == 'A' || $record['HeadType'] == 'E')?$opening:0),
                'opening_crt' => (($record['HeadType'] == 'L' || $record['HeadType'] == 'I')?$opening:0),
                'trdebit'     => ($transsummery?$transsummery[0]->debit:0),
                'trcredit'    => ($transsummery?$transsummery[0]->credit:0),
                'cldebit'     => $totalbalanceDebit,
                'clcredit'    => $totalbalanceCredit,
               
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
    | Get expense heads balance
    *--------------------------*/
    public function bdtaskt1m8_21_getExpenseChildInfo($branch_id, $from, $to){
        $branch = !empty($branch_id)?' AND BranchID="'.$branch_id.'"':' AND BranchID="'.session('branchId').'"';
        return $this->db->table('acc_coa')
                        ->select("HeadCode, HeadName, HeadLevel, (SELECT SUM(Debit)-SUM(Credit) FROM acc_transaction WHERE COAID=HeadCode AND VDate >='$from' AND VDate <='$to' AND IsAppove=1) as balance")
                        ->where('IsActive', 1)
                        ->where('HeadCode !=', '4')
                        ->like('HeadCode', '4', 'after')
                        ->groupBy('HeadCode')
                        ->orderBy('HeadCode', 'asc')
                        ->get()->getResult();
    }

    /*--------------------------
    | Get expense heads balance
    *--------------------------*/
    public function bdtaskt1m8_22_getIncomeChildInfo($branch_id, $from, $to){
        $branch = !empty($branch_id)?' AND BranchID="'.$branch_id.'"':' AND BranchID="'.session('branchId').'"';
        return $this->db->table('acc_coa')
                    ->select("HeadCode, HeadName, HeadLevel, (SELECT SUM(Debit)-SUM(Credit) FROM acc_transaction WHERE COAID=HeadCode AND VDate >='$from' AND VDate <='$to' AND IsAppove=1) as balance")
                    ->where('IsActive', 1)
                    ->where('HeadCode !=', '3')
                    ->like('HeadCode', '3', 'after')
                    ->groupBy('HeadCode')
                    ->orderBy('HeadCode', 'asc')
                    ->get()->getResult();

    }

    /*--------------------------
    | Get Fixed assets
    *--------------------------*/
    public function bdtaskt1m8_23_getChildBalance($branch_id, $code, $head, $from, $to, $all=false)
    {
        $query = $this->db->table('acc_coa')->select("HeadCode, HeadName, HeadLevel, (SELECT SUM(Debit)-SUM(Credit) FROM acc_transaction WHERE COAID like CONCAT(HeadCode,'%') AND VDate >='$from' AND VDate <='$to'  AND IsAppove=1) as balance");
        $query->where('IsActive', 1);
        if($all){
             $query->where('HeadLevel',4);
             $query->like('HeadCode',$code, 'after');
        }else{
             $query->where('PHeadName',$head);
        }
       
        $query->orderBy('HeadCode', 'asc');
        return $query->get()->getResult();
    }

    /*--------------------------
    | Get Fixed assets
    *--------------------------*/
    public function bdtaskt1m8_2322_getChildAccount($head, $from, $to, $column=null, $whereNotIn=null)
    {
        $query = $this->db->table('acc_coa')->select("HeadCode, HeadName, HeadLevel, (SELECT SUM(Debit)-SUM(Credit) FROM acc_transaction WHERE COAID=HeadCode AND VDate >='$from' AND VDate <='$to' AND IsAppove=1) as balance");
        $query->where('IsActive', 1);
        //$query->where('HeadCode !=', $head);
        if(!empty($column) && !empty($whereNot)){
            $query->whereNotIn($column, $whereNot);
        }
        $query->like('HeadCode',$head, 'after');
        $query->groupBy('HeadCode');
        $query->orderBy('HeadCode', 'asc');
        return $query->get()->getResult();
    }


    public function getOpeningBalance($code, $date){
        $query = $this->db->table('acc_transaction')
                         ->select('(sum(Debit) - sum(Credit)) as total')
                         ->where('IsAppove',1)
                         ->where('VDate < ',$date)
                         ->where('COAID',$code)
                         ->get()
                         ->getRow();
        if(!empty($query)){
            return $query->total;
        }else{
            return 0;
        }
    }

    public function cashflow_firstquery(){
       $sql = "SELECT * FROM acc_coa WHERE acc_coa.IsTransaction=1 AND acc_coa.HeadType='A' AND acc_coa.IsActive=1 AND acc_coa.HeadCode LIKE '1211%'";
       return $sql;
    }

    public function cashflow_secondquery($branch_id,$dtpFromDate,$dtpToDate,$COAID){
        $sql = "SELECT SUM(acc_transaction.Debit)- SUM(acc_transaction.Credit) AS Amount, GROUP_CONCAT(acc_transaction.ID) as Ids FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE acc_transaction.IsAppove =1 AND VDate BETWEEN '".$dtpFromDate."' AND '".$dtpToDate."' AND COAID LIKE '$COAID%'";
      
       return $sql;
    }

    public function cashflow_thirdquery(){
        $sql = "SELECT * FROM acc_coa WHERE IsGL=1 AND HeadCode LIKE '12%' AND IsActive=1 AND HeadCode NOT LIKE '1211%' AND HeadCode!='12' ";
      
       return $sql;
    }

    public function cashflow_forthquery($branch_id,$dtpFromDate,$dtpToDate,$COAID){
       $sql = "SELECT  SUM(acc_transaction.Credit) - SUM(acc_transaction.Debit) AS Amount, GROUP_CONCAT(acc_transaction.ID) as Ids FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE acc_transaction.IsAppove = 1 AND VDate BETWEEN '".$dtpFromDate."' AND '".$dtpToDate."' AND COAID LIKE '$COAID%' AND VNo in (SELECT VNo FROM acc_transaction WHERE COAID LIKE '1211%') ";
      
       return $sql;
    }


    public function cashflow_fifthquery($branch_id,$dtpFromDate,$dtpToDate,$COAID){
       $sql = "SELECT  SUM(acc_transaction.Credit) - SUM(acc_transaction.Debit) AS Amount, GROUP_CONCAT(acc_transaction.ID) as Ids FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE acc_transaction.IsAppove = 1 AND VDate BETWEEN '".$dtpFromDate."' AND '".$dtpToDate."' AND COAID LIKE '4%' AND VNo in (SELECT VNo FROM acc_transaction WHERE COAID LIKE '1211%') ";
      
       return $sql;
    }


    public function cashflow_sixthquery(){
       $sql = "SELECT * FROM acc_coa WHERE IsGL=1 AND HeadCode LIKE '3%' AND IsActive=1 ";
       return $sql;
    }

    public function cashflow_seventhquery($branch_id,$dtpFromDate,$dtpToDate,$COAID){
         $sql = "SELECT  SUM(acc_transaction.Credit) - SUM(acc_transaction.Debit) AS Amount, GROUP_CONCAT(acc_transaction.ID) as Ids FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE acc_transaction.IsAppove = 1 AND VDate BETWEEN '".$dtpFromDate."' AND '".$dtpToDate."' AND COAID LIKE '$COAID%' AND VNo in (SELECT VNo FROM acc_transaction WHERE COAID LIKE '1211%') ";
       return $sql;
    }

    public  function get_general_ledger_head()
    {
        return  $result =   $this->db->table('acc_coa')
                     ->select("HeadCode as id, CONCAT(HeadCode, '-', HeadName) as text")
                     ->where('IsGL',1)
                     ->orderBy('HeadName','asc') 
                     ->get()
                     ->getResult();
    }

    public function get_gl_trans_head($Headid)
    {
        $rs= $this->db->table('acc_coa')
                     ->select('*')
                     ->where('HeadCode',$Headid) 
                     ->get()
                     ->getRow();


        $result = $this->db->table('acc_coa')
                     ->select("HeadCode as id, CONCAT_WS(' ', HeadCode, '-', HeadName) as text")
                     ->where('IsTransaction',1)
                     ->where('PHeadName',$rs->HeadName)
                     ->orderBy('HeadName','asc') 
                     ->get()
                     ->getResult();
        return $result;
    }

    public function general_led_report_headname($cmbGLCode)
    {
      return $result = $this->db->table('acc_coa')
                   ->select('*')
                   ->where('HeadCode',$cmbGLCode)
                   ->get()
                   ->getRow();
    }

    public function general_led_report_headname2($HeadCode,$dtpFromDate,$dtpToDate,$chkIsTransction, $branchId=null)
    {
        if($chkIsTransction){
            $result = $this->db->table('acc_transaction');
            $result->select('acc_transaction.VNo,acc_transaction.VDate, acc_transaction.Vtype, acc_transaction.VDate, acc_transaction.Narration, acc_transaction.Debit, acc_transaction.Credit, acc_transaction.IsAppove, acc_transaction.COAID,acc_coa.HeadName, acc_coa.PHeadName, acc_coa.HeadType');
            $result->join('acc_coa','acc_coa.HeadCode = acc_transaction.COAID', 'left');
           
            $result->where('acc_transaction.IsAppove',1);
            $result->where('acc_transaction.VDate >=', $dtpFromDate);
            $result->where('acc_transaction.VDate <=', $dtpToDate);
            $result->where('acc_transaction.COAID',$HeadCode);
            return $result->get()->getResult();
        }else{
            $result = $this->db->table('acc_transaction');
            $result->select('acc_transaction.COAID,acc_transaction.VDate,acc_transaction.Debit, acc_transaction.Credit,acc_coa.HeadName,acc_transaction.IsAppove, acc_coa.PHeadName, acc_coa.HeadType');
            $result->join('acc_coa','acc_coa.HeadCode = acc_transaction.COAID', 'left');
           
            $result->where('acc_transaction.IsAppove',1);
            $result->where('acc_transaction.VDate >=', $dtpFromDate);
            $result->where('acc_transaction.VDate <=', $dtpToDate);
            $result->where('acc_transaction.COAID',$HeadCode);
            return $result->get()->getResult();
        }
    }

    // prebalance calculation
    public function general_led_report_prebalance($HeadCode,$dtpFromDate, $branchId=null)
    {
        $query = $this->db->table('acc_transaction');
        $query->select('sum(Debit) as predebit, sum(Credit) as precredit');
        
        $query->where('IsAppove',1);
        $query->where('VDate < ',$dtpFromDate);
        $query->where('COAID',$HeadCode);
        $query1 = $query->get();
        $result = $query1->getRow();
 
        return $balance=$result->predebit - $result->precredit;
    }

    // prebalance calculation
    public function get_prebalance($cmbCode,$dtpFromDate, $branchId=null)
    {
        $query = $this->db->table('acc_transaction');
        $query->select('sum(Debit) as predebit, sum(Credit) as precredit');
       
        $query->where('IsAppove',1);
        $query->where('VDate < ',$dtpFromDate);
        $query->where('COAID',$cmbCode);
        $query1 = $query->get();
        $result = $query1->getRow();
 
        return $balance=$result->precredit - $result->predebit;
    }

    public function profit_loss_serach(){
        $sql1 =$this->db->table('acc_coa')
                             ->select('*')
                             ->where('HeadType','I')
                             ->get()
                             ->getResult();

        $sql2 = $this->db->table('acc_coa')
                             ->select('*')
                             ->where('HeadType','E')
                             ->get()
                             ->getResult();
        
        $data = array(
          'oResultAsset'     => $sql1,
          'oResultLiability' => $sql2,
        );
        return $data;
    } 

    public function profit_loss_serach_date($dtpFromDate,$dtpToDate){

                return  $query = $this->db->table('acc_transaction')
                             ->select('acc_transaction.VDate, acc_transaction.COAID, acc_coa.HeadName')
                             ->join('acc_coa','acc_transaction.COAID = acc_coa.HeadCode')
                             ->where('acc_transaction.VDate >=',$dtpFromDate)
                             ->where('acc_transaction.VDate <=',$dtpToDate)
                             ->like('acc_transaction.COAID','301','after')
                             ->where('IsAppove',1)
                             ->orderBy('HeadCode')
                             ->get()
                             ->getResult();
    }

    public function profitloss_firstquery($dtpFromDate,$dtpToDate,$COAID){

       $sql ="SELECT SUM(acc_transaction.Debit)-SUM(acc_transaction.Credit) AS Amount FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE VDate BETWEEN '$dtpFromDate' AND '$dtpToDate' AND COAID LIKE '$COAID%'";
        return $sql;
    }

    public function profitloss_secondquery($dtpFromDate,$dtpToDate,$COAID){
      $sql = "SELECT SUM(acc_transaction.Credit)-SUM(acc_transaction.Debit) AS Amount FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE acc_transaction.IsAppove = 1 AND VDate BETWEEN '$dtpFromDate' AND '$dtpToDate' AND COAID LIKE '$COAID%'";
       return $sql;
    }


        public function general_led_report_headname2_nontransactional($cmbGLCode, $dtpFromDate, $dtpToDate)
    {
        $childs = $this->non_transaction_childs($cmbGLCode);

        $result = $this
            ->db
            ->table('acc_transaction')
            ->select('acc_transaction.VNo,acc_transaction.VDate, acc_transaction.Vtype, acc_transaction.VDate, acc_transaction.Narration, acc_transaction.Debit, acc_transaction.Credit, acc_transaction.IsAppove, acc_transaction.COAID,acc_coa.HeadName, acc_coa.PHeadName, acc_coa.HeadType')
            ->join('acc_coa', 'acc_transaction.COAID = acc_coa.HeadCode', 'left')
            ->where('acc_transaction.IsAppove', 1)
            ->where('VDate BETWEEN "' . $dtpFromDate . '" and "' . $dtpToDate . '"')->wherein('acc_transaction.COAID', $childs)->get()
            ->getResult();

        return $result;

    }

     public function general_led_report_prebalance_nontransactional($cmbGLCode, $dtpFromDate)
    {
        $childs = $this->non_transaction_childs($cmbGLCode);
        $fiscal_year = $this->check_fiscal_yearwithcurrentyear();
        $results = $this
            ->db
            ->table('acc_coa')
            ->select('*')
            ->wherein('HeadCode', $childs)->get()
            ->getResult();

        $total_pre = 0;
        $balance = 0;
        $total = 0;
        $total_p = 0;
        foreach ($results as $rdata)
        {
            $transdata = $this
                ->db
                ->table('acc_transaction')
                ->select('COAID as COAID,sum(Debit) as predebit, sum(Credit) as precredit,VDate')
                ->where('IsAppove', 1)
                ->where('VDate > ', $fiscal_year->startDate)
                ->where('VDate < ', $dtpFromDate)->where('COAID', $rdata->HeadCode)
                ->groupBy('acc_transaction.COAID')
                ->get()
                ->getRow();
            $opening_value = $this->openig_value($rdata->HeadCode, $fiscal_year->startDate, $fiscal_year->endDate);
            if ($transdata)
            {
                $balance = ($transdata ? $transdata->predebit : 0) - ($transdata ? $transdata->precredit : 0);
                $total_pre += ($balance ? $balance : 0);

            }

            $total_pre += ($opening_value ? $opening_value->amount : 0);

        }

        return $total_pre;

    }

        public function non_transactional_head()
    {

        return $result = $this
            ->db
            ->table('acc_coa')
            ->select('*')
            ->where('IsTransaction', 0)
            ->orderBy('HeadName', 'asc')
            ->get()
            ->getResult();

    }

        public function openig_value($headcodes, $start, $end)
    {
        return $obinfo = $this
            ->db
            ->table('ob_table')
            ->where('headcode', $headcodes)->where('fiscal_year_start', $start)->where('fiscal_year_end', $end)->get()
            ->getRow();
    }

        public function check_fiscal_yearwithcurrentyear()
    {
        $curdate = date('Y-m-d');
        return $fsyear = $this
            ->db
            ->table('financial_year')
            ->where('startDate <=', $curdate)->where('endDate >=', $curdate)->get()
            ->getRow();
    }

        public function non_transaction_childs($code)
    {
        $mainhead = $this
            ->db
            ->table('acc_coa')
            ->select('*')
            ->where('HeadCode', $code)->get()
            ->getRow();
        $headcodes = array(
            "$code"
        );

        $child_head = $this
            ->db
            ->table('acc_coa')
            ->select('*')
            ->where('PHeadName', ($mainhead ? $mainhead->HeadName : ''))
            ->get()
            ->getResult();
        if ($child_head)
        {
            foreach ($child_head as $schild)
            {
                $nchild = $this->nchild($schild->HeadName);
                $child = $schild->HeadCode;
                array_push($headcodes, $child);
                if ($nchild)
                {
                    foreach ($nchild as $newchild)
                    {
                        $newchild = $schild->HeadCode;
                        array_push($headcodes, $newchild);
                        $nstchild2 = $this->nchild($schild->HeadName);
                        if ($nstchild2)
                        {
                            foreach ($nstchild2 as $child2)
                            {
                                $newchild2 = $child2->HeadCode;
                                array_push($headcodes, $newchild2);
                                $nstchild3 = $this->nchild($child2->HeadName);
                                if ($nstchild3)
                                {
                                    foreach ($nstchild3 as $child3)
                                    {
                                        $newchild3 = $child3->HeadCode;
                                        array_push($headcodes, $newchild3);
                                        $nstchild4 = $this->nchild($child3->HeadName);
                                        if ($nstchild4)
                                        {
                                            foreach ($nstchild4 as $child4)
                                            {
                                                $newchild4 = $child4->HeadCode;
                                                array_push($headcodes, $newchild4);
                                                $nstchild5 = $this->nchild($child4->HeadName);

                                                if ($nstchild5)
                                                {
                                                    foreach ($nstchild5 as $child5)
                                                    {
                                                        $newchild5 = $child5->HeadCode;
                                                        array_push($headcodes, $newchild5);

                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $headcodes;

    }

        public function nchild($name)
    {
        return $child_head = $this
            ->db
            ->table('acc_coa')
            ->select('*')
            ->where('PHeadName', $name)->get()
            ->getResult();
    }

  

   public function get_predefined_head($field){

    
    $dbquery = $this->db->table('acc_predefine_account');
    $dbquery->select($field);
    $dbquery->limit(1);       
    $query = $dbquery->get()->getRow();
    return $query->$field;
}


public function general_ledger_report_headname2($cmbCode,$dtpFromDate,$dtpToDate,$chkIsTransction=null){

    if($chkIsTransction){
         $this->db->table('acc_transaction');
         $this->db->select('acc_transaction.VNo, acc_transaction.Vtype, acc_transaction.VDate, acc_transaction.Narration, acc_transaction.Debit, acc_transaction.Credit, acc_transaction.IsAppove, acc_transaction.COAID,acc_coa.HeadName, acc_coa.PHeadName, acc_coa.HeadType');
         $this->db->join('acc_coa','acc_transaction.COAID = acc_coa.HeadCode', 'left');
         $this->db->where('acc_transaction.IsAppove',1);
         $this->db->where('VDate BETWEEN "'.$dtpFromDate. '" and "'.$dtpToDate.'"');
         $this->db->where('acc_transaction.COAID',$cmbCode);
         //$this->db->where('acc_transaction.fyear',session('fyear'));       
         $this->db->orderBy('acc_transaction.VDate','Asc');
         $this->db->orderBy('acc_transaction.ID','Asc');
         $query = $this->db->get();
         return $query->getResult();
     }
     else{
        // $cmbCode1=$cmbCode; 
        $this->db->table('acc_transaction');
         $this->db->select('acc_transaction.COAID, acc_transaction.VNo, acc_transaction.Debit, acc_transaction.Credit, acc_coa.HeadName, acc_transaction.IsAppove, acc_transaction.VDate, acc_coa.PHeadName, acc_coa.HeadType');
         $this->db->join('acc_coa','acc_transaction.COAID = acc_coa.HeadCode', 'left');
         $this->db->where('acc_transaction.IsAppove',1);
         $this->db->where('VDate BETWEEN "'.$dtpFromDate. '" and "'.$dtpToDate.'"');
         $this->db->where('acc_transaction.COAID',$cmbCode);
        // $this->db->where('acc_transaction.fyear',$this->session->userdata('fyear'));  
         $this->db->orderBy('acc_transaction.VDate','Asc');
         $this->db->orderBy('acc_transaction.ID','Asc');
         $query = $this->db->get();
         return $query->getResult();
     }

}

public function get_opening_balance($hCode,$dtpFromDate,$dtpToDate){  
    $coaHead = $this->general_led_report_headname($hCode); 
        $fny = $this->getActiveFinancialyear();
         $fyearStartDate = ($fny?$fny->startDate:'');
         $fyearEndDate   = ($fny?$fny->endDate:'');
         $oldDate        = date('Y-m-d',strtotime($dtpFromDate. ' -1 year'));
         $prevDate       = date('Y-m-d', strtotime($dtpFromDate .' - 1day'));
   
     if($coaHead->HeadType == 'L' || $coaHead->HeadType =='A') {                 
         if($dtpFromDate >= $fyearStartDate && $dtpFromDate <= $fyearEndDate) { 
             $fyear = $this->db->table('financial_year')->select('*')->where('startDate <=',$oldDate)->where('endDate >=',$oldDate)->get()->getRow();
            
         } else {                   
             $fyear = $this->db->table('financial_year')->select('*')->where('startDate <=',$oldDate)->where('endDate >=',$oldDate)->get()->getRow();
         }          
         $oldBalance = $this->get_old_year_closingBalance($hCode,$fny->id,$coaHead->HeadType,$coaHead->subType);
     } else {
         $oldBalance =0;
    } 

   $opening =  $this->get_general_ledger_report($hCode,$fyearStartDate,$prevDate,0,0);
   if($opening) {
      foreach($opening as $open) {
          if($coaHead->HeadType == 'A' || $coaHead->HeadType == 'E') {
              $balance= ($open->debit - $open->credit);
           } else {
              $balance=($open->credit - $open->debit);
           }
      }
      
   } else {
     $balance= 0;
   }             

return $newBalance = $oldBalance + $balance ; 
              
}

//Get old year clossing balance
public function get_old_year_closingBalance($hCode,$year,$hType=null,$subtype=1,$subcode=null) {   
    $builder = $this->db->table('acc_opening_balance');   
    $builder->select('SUM(Debit) as Debit,SUM(Credit) as Credit');
    $builder->where('COAID',$hCode);
    $builder->where('fyear',$year); 
    if($subtype != 1 & $subcode != null) {
          $builder->where('subCode',$subcode);
          $builder->where('subType',$subtype);
       }            
    $closing =  $builder ->get();
     $closingvalue = $closing->getRow();
     if($closingvalue){
     if($hType == 'A') {
        return ($closingvalue->Debit -  $closingvalue->Credit);
     } else {
       return ($closingvalue->Credit -  $closingvalue->Debit);
     }   
    }else{
        return false;
    }     
   
   
}



public function get_general_ledger_report($cmbCode,$dtpFromDate,$dtpToDate, $chkIsTransction, $isfyear=0){
    $fisyear = $this->getActiveFinancialyear();
    $yearid = $fisyear->id;
    if($chkIsTransction == 1) {
      $builder =  $this->db->table('acc_transaction');
       $builder->select('acc_transaction.VNo,acc_transaction.COAID,, acc_transaction.Vtype, acc_transaction.VDate, acc_transaction.Narration, acc_transaction.ledgerComment, acc_transaction.Debit as Debit, acc_transaction.Credit as Credit, acc_transaction.Debit as debit, acc_transaction.Credit as credit, acc_transaction.IsAppove, acc_transaction.COAID,acc_coa.HeadName, acc_coa.PHeadName, acc_coa.HeadType');
       $builder->join('acc_coa','acc_transaction.RevCodde = acc_coa.HeadCode', 'left');
       $builder->where('acc_transaction.IsAppove',1);
       $builder->where('VDate BETWEEN "'.$dtpFromDate. '" and "'.$dtpToDate.'"');
       $builder->where('acc_transaction.COAID',$cmbCode);
       if($isfyear!=0) {
         $builder->where('acc_transaction.fyear',$yearid); 
       }                  
       $builder->orderBy('acc_transaction.VDate','asc');
       $builder->orderBy('acc_transaction.Vtype','asc');
       $query = $builder->get();
       return $query->getResult();

    } else {
        $builder = $this->db->table('acc_transaction');   
       $builder->select('COAID, Vtype,Debit, Credit,Debit as debit,Credit as credit');             
       $builder->where('IsAppove',1);
       $builder->where('VDate BETWEEN "'.$dtpFromDate. '" and "'.$dtpToDate.'"');
       $builder->where('COAID',$cmbCode);
       if($isfyear!=0) {
         $builder->where('acc_transaction.fyear',$yearid); 
       }
       $query = $builder->get();             
       return $query->getResult();
    }       
}

public function get_general_ledger_report_subhead($cmbCode,$dtpFromDate,$dtpToDate, $chkIsTransction, $isfyear=0,$subtype=1, $subcod=null){
    $fisyear = $this->getActiveFinancialyear();
    $yearid = $fisyear->id;
    if($chkIsTransction == 1) {
       $builder = $this->db->table('acc_transaction');
      $builder->select('acc_transaction.VNo,acc_transaction.COAID, acc_transaction.Vtype, acc_transaction.VDate, acc_transaction.Narration, acc_transaction.ledgerComment, acc_transaction.Debit, acc_transaction.Credit, acc_transaction.IsAppove, acc_transaction.COAID,acc_coa.HeadName, acc_coa.PHeadName,acc_coa.pheadcode, acc_coa.HeadType');
      $builder->join('acc_coa','acc_transaction.RevCodde = acc_coa.HeadCode', 'left');                
       if($subtype!=1 && $subcod != null ) {
       $builder->join('acc_subtype st','acc_transaction.subType = st.id', 'left');
       $builder->join('acc_subcode sc','acc_transaction.subCode = sc.id', 'left');
       $builder->where('acc_transaction.subType',$subtype);
       $builder->where('acc_transaction.subCode',$subcod);
       } 
      $builder->where('acc_transaction.COAID',$cmbCode);                
      $builder->where('acc_transaction.IsAppove',1);
      $builder->where('VDate BETWEEN "'.$dtpFromDate. '" and "'.$dtpToDate.'"');               
       if($isfyear!=0) {
        $builder->where('acc_transaction.FyID',$yearid); 
       }                
       //$builder->where_in('acc_transaction.COAID',$cmbCode);    
      $builder->orderBy('acc_transaction.VDate','Asc');
      $builder->orderBy('acc_transaction.Vtype','Asc');
       $query =$builder->get();
    //   echo $this->db->getLastQuery();exit();
       return $query->getResult();
    } else {         
       $builder = $this->db->table('acc_transaction');        
       $builder->select('COAID, Vtype, sum(Debit) as debit, sum(Credit) as credit ');              
       $builder->where('IsAppove',1);
       $builder->where('VDate BETWEEN "'.$dtpFromDate. '" and "'.$dtpToDate.'"');
       $builder->where('COAID',$cmbCode);
       if($isfyear!=0) {
         $builder->where('acc_transaction.FyID',$yearid); 
       }
       $query = $builder->get(); 
       //echo $this->db->last_query(); exit();             
       return $query->getResult();
    }       
}


public function get_general_ledger_report_subheadSummary($cmbCode,$dtpFromDate,$dtpToDate, $chkIsTransction, $isfyear=0,$subtype=null, $subcod=null){
    $fisyear = $this->getActiveFinancialyear();
    $yearid = $fisyear->id;

    $builder = $this->db->table('acc_transaction');
      $builder->select('acc_transaction.VNo,acc_transaction.COAID, acc_transaction.Vtype, acc_transaction.VDate, acc_transaction.Narration, acc_transaction.ledgerComment, sum(acc_transaction.Debit) as total_debit, sum(acc_transaction.Credit) as total_credit, acc_transaction.IsAppove, acc_transaction.COAID,acc_coa.HeadName, acc_coa.PHeadName,acc_coa.pheadcode, acc_coa.HeadType');
      $builder->join('acc_coa','acc_transaction.RevCodde = acc_coa.HeadCode', 'left');                
       $builder->join('acc_subtype st','acc_transaction.subType = st.id', 'left');
       $builder->join('acc_subcode sc','acc_transaction.subCode = sc.id', 'left');
       $builder->where('acc_transaction.subType',$subtype);
       $builder->where('acc_transaction.subCode',$subcod);
      $builder->where('acc_transaction.COAID',$cmbCode);                
      $builder->where('acc_transaction.IsAppove',1);
      $builder->where('VDate BETWEEN "'.$dtpFromDate. '" and "'.$dtpToDate.'"');               
       if($isfyear!=0) {
        $builder->where('acc_transaction.FyID',$yearid); 
       }                  
      $builder->orderBy('acc_transaction.VDate','Asc');
      $builder->orderBy('acc_transaction.Vtype','Asc');
       $query =$builder->get();
       return $query->getRow();
      
}

public function getActiveFinancialyear()
{
  return  $result = $this->db->table('financial_year')->where('status',1)->get()->getRow();
}

public function settings_data()
{
    return $result = $this->db->table('setting')->get()->getRow();
}

public function get_all_bank(){

    $builder = $this->db->table('acc_coa');
    $builder->select('*');
    $builder->where('isBankNature',1);       
    $builder->orderBy('HeadName', 'asc');
    $query = $builder->get();
    return $query->getResult();
}
public  function get_general_ledger(){

    $builder = $this->db->table('acc_coa');
    $builder->select('*');
    $builder->where('HeadLevel',4);
    $builder->where('isCashNature',0);
    $builder->where('isBankNature',0);
    $builder->orderBy('HeadName', 'asc');
    $query = $builder->get();
    return $query->getResult();

}

public function get_head_summery($type,$phead,$dtpFromDate,$dtpToDate, $resultType) {
    $secondLevel = $this->get_charter_accounts_by_headNae($type,$phead);
    $mainHead= array();
    $sumTotal = 0; 
    $secondArray = array();
    if($secondLevel) {    
      
        foreach($secondLevel as $chac) {
            $subTotal = 0;
            $innerArray = array();
            $thirdLevel = $this->get_charter_accounts_by_headNae($type,$chac->HeadName);
            if($thirdLevel) {
                $thirdLevelArray = array();
                foreach($thirdLevel as $tdl) {
                    $balance= 0;                     
                    $transationLevel = $this->get_charter_accounts_by_headNae($type,$tdl->HeadName);

                    if($transationLevel){
                        $tDebit =0; $tCredit = 0;
                        foreach($transationLevel as $trans) {
                            $tval  = $this->get_general_ledger_report($trans->HeadCode,$dtpFromDate,$dtpToDate, 0, 0);
                            if($tval) {
                                foreach($tval as $amounts){
                                    $tDebit += $amounts->debit;
                                    $tCredit += $amounts->credit;
                                }
                            }                            
                        }
                        if($type == 'A' || $type == 'E') {

                            $balance = $tDebit - $tCredit;
                        } else {
                            $balance = $tCredit - $tDebit;
                        }
                       $sumTotal +=  $balance;
                       $subTotal += $balance;
                    }
                    $cdata = array( 'headCode'=>$tdl->HeadCode,
                          'headName'=>$tdl->HeadName,
                          'amount' => $balance
                      );
                   array_push( $innerArray,  $cdata);
                }
            }
            $data = array( 'headCode'=>$chac->HeadCode,
                          'headName' =>$chac->HeadName,
                          'subtotal' => $subTotal,
                          'innerHead'=> $innerArray
                          
                      );  
                      array_push($secondArray,  $data);              
        }
    }
     $maina = array('head'=>$phead,
                    'gtotal'    =>  $sumTotal,
                    'nextlevel' =>$secondArray);
    
     array_push($mainHead,  $maina); 
     
     if($resultType==0) {
        return $mainHead;
     }   else if($resultType==1) {
       return $sumTotal;
     }   
    
}
// get monthly transation summery
 private function get_monthly_summery($head, $fyear) {
    $statements = $this->db->table('acc_monthly_balance')
                           ->select('*')
                           ->where('COAID',$head)
                           ->where('fyear',$fyear)->get()->getRow();
       if( $statements) {
         return  $statements;
       } else {
        return false;
       }

 }

 public function get_charter_accounts_by_headNae2($type,$phead) {
    $CharterAccounts = $this->db->table('acc_coa')
                       ->select('HeadCode,HeadName')
                       ->where('HeadType',$type)
                       ->where('PHeadName',$phead)->get()->getResult();
    return $CharterAccounts;
}


//get monthly Income summery
public function get_monthly_income($type,$phead,$fyear) {
    $secondLevel = $this->get_charter_accounts_by_headNae($type,$phead, 401);
    $mainHead= array();
    $sumTotal1  = 0; 
    $sumTotal2  = 0; 
    $sumTotal3  = 0; 
    $sumTotal4  = 0; 
    $sumTotal5  = 0; 
    $sumTotal6  = 0; 
    $sumTotal7  = 0; 
    $sumTotal8  = 0; 
    $sumTotal9  = 0; 
    $sumTotal10 = 0; 
    $sumTotal11 = 0; 
    $sumTotal12 = 0; 
    if($secondLevel) {    
       $secondArray = array();
        foreach($secondLevel as $chac) {
             
            $subTotal1  = 0;
            $subTotal2  = 0;
            $subTotal3  = 0;
            $subTotal4  = 0;
            $subTotal5  = 0;
            $subTotal6  = 0;
            $subTotal7  = 0;
            $subTotal8  = 0;
            $subTotal9  = 0;
            $subTotal10 = 0;
            $subTotal11 = 0;
            $subTotal12 = 0;
            $innerArray = array();
            $thirdLevel = $this->get_charter_accounts_by_headNae($type,$chac->HeadName);
            if($thirdLevel) {
                $thirdLevelArray = array();
                foreach($thirdLevel as $tdl) {
                    $balance1  = 0;                     
                    $balance2  = 0;                     
                    $balance3  = 0;                     
                    $balance4  = 0;                     
                    $balance5  = 0;                     
                    $balance6  = 0;                     
                    $balance7  = 0;                     
                    $balance8  = 0;                     
                    $balance9  = 0;                     
                    $balance10 = 0;                     
                    $balance11 = 0;                     
                    $balance12 = 0;                     
                    $transationLevel = $this->get_charter_accounts_by_headNae($type,$tdl->HeadName);

                    if($transationLevel){
                       
                        foreach($transationLevel as $trans) {
                            $tval  = $this->get_monthly_summery($trans->HeadCode,$fyear);
                            if($tval) {
                                $balance1  += $tval->balance1 ;                     
                                $balance2  += $tval->balance2 ;                     
                                $balance3  += $tval->balance3 ;                     
                                $balance4  += $tval->balance4 ;                     
                                $balance5  += $tval->balance5 ;                     
                                $balance6  += $tval->balance6 ;                     
                                $balance7  += $tval->balance7 ;                     
                                $balance8  += $tval->balance8 ;                     
                                $balance9  += $tval->balance9 ;                     
                                $balance10 += $tval->balance10 ;                     
                                $balance11 += $tval->balance11 ;                     
                                $balance12 += $tval->balance12 ; 
                            }                            
                        }
                       
                       $sumTotal1  +=  $balance1;
                       $sumTotal2  +=  $balance2;
                       $sumTotal3  +=  $balance3;
                       $sumTotal4  +=  $balance4;
                       $sumTotal5  +=  $balance5;
                       $sumTotal6  +=  $balance6;
                       $sumTotal7  +=  $balance7;
                       $sumTotal8  +=  $balance8;
                       $sumTotal9  +=  $balance9;
                       $sumTotal10 +=  $balance10;
                       $sumTotal11 +=  $balance11;
                       $sumTotal12 +=  $balance12;
                       $subTotal1  += $balance1;
                       $subTotal2  += $balance2;
                       $subTotal3  += $balance3;
                       $subTotal4  += $balance4;
                       $subTotal5  += $balance5;
                       $subTotal6  += $balance6;
                       $subTotal7  += $balance7;
                       $subTotal8  += $balance8;
                       $subTotal9  += $balance9;
                       $subTotal10 += $balance10;
                       $subTotal11 += $balance11;
                       $subTotal12 += $balance12;
                    }
                    $cdata = array( 'headCode'=>$tdl->HeadCode,
                          'headName' =>$tdl->HeadName,
                          'amount1'  => $balance1,
                          'amount2'  => $balance2,
                          'amount3'  => $balance3,
                          'amount4'  => $balance4,
                          'amount5'  => $balance5,
                          'amount6'  => $balance6,
                          'amount7'  => $balance7,
                          'amount8'  => $balance8,
                          'amount9'  => $balance9,
                          'amount10' => $balance10,
                          'amount11' => $balance11,
                          'amount12' => $balance12,
                      );
                   array_push( $innerArray,  $cdata);
                }
            }
            $data = array( 'headCode' =>$chac->HeadCode,
                          'headName'  =>$chac->HeadName,
                          'subtotal'  => $subTotal1,
                          'subtota2'  => $subTotal2,
                          'subtota3'  => $subTotal3,
                          'subtota4'  => $subTotal4,
                          'subtota5'  => $subTotal5,
                          'subtota6'  => $subTotal6,
                          'subtota7'  => $subTotal7,
                          'subtota8'  => $subTotal8,
                          'subtota9'  => $subTotal9,
                          'subtotal0' => $subTotal10,
                          'subtotal1' => $subTotal11,
                          'subtotal2' => $subTotal12,
                          'innerHead' => $innerArray                          
                      );  
                      array_push($secondArray,  $data);              
        }
    }
     $maina = array('head'         =>  $phead,
                    'gtotal1'      =>  $sumTotal1,
                    'gtotal2'      =>  $sumTotal2,
                    'gtotal3'      =>  $sumTotal3,
                    'gtotal4'      =>  $sumTotal4,
                    'gtotal5'      =>  $sumTotal5,
                    'gtotal6'      =>  $sumTotal6,
                    'gtotal7'      =>  $sumTotal7,
                    'gtotal8'      =>  $sumTotal8,
                    'gtotal9'      =>  $sumTotal9,
                    'gtotal10'     =>  $sumTotal10,
                    'gtotal11'     =>  $sumTotal11,
                    'gtotal12'     =>  $sumTotal12,
                    'nextlevel'    =>  $secondArray);
    
     array_push($mainHead,  $maina);     
     //exit();
        return $mainHead;       
    
}

//get monthly Income summery
public function get_from_secondlevel_expenses($type,$hCode,$fyear) {
        $phead = $this->get_gl_headname($hCode);      
            $secondArray = array(); 
            $subTotal1  = 0;
            $subTotal2  = 0;
            $subTotal3  = 0;
            $subTotal4  = 0;
            $subTotal5  = 0;
            $subTotal6  = 0;
            $subTotal7  = 0;
            $subTotal8  = 0;
            $subTotal9  = 0;
            $subTotal10 = 0;
            $subTotal11 = 0;
            $subTotal12 = 0;
            $thirdLevel = $this->get_charter_accounts_by_headNae($type,$phead->HeadName);
            if($thirdLevel) {
                $innerArray = array();
                foreach($thirdLevel as $tdl) {
                    $balance1  = 0;                     
                    $balance2  = 0;                     
                    $balance3  = 0;                     
                    $balance4  = 0;                     
                    $balance5  = 0;                     
                    $balance6  = 0;                     
                    $balance7  = 0;                     
                    $balance8  = 0;                     
                    $balance9  = 0;                     
                    $balance10 = 0;                     
                    $balance11 = 0;                     
                    $balance12 = 0;                     
                    $transationLevel = $this->get_charter_accounts_by_headNae($type,$tdl->HeadName);

                    if($transationLevel){
                       
                        foreach($transationLevel as $trans) {
                            $tval  = $this->get_monthly_summery($trans->HeadCode,$fyear);
                            if($tval) {
                                $balance1  += $tval->balance1 ;                     
                                $balance2  += $tval->balance2 ;                     
                                $balance3  += $tval->balance3 ;                     
                                $balance4  += $tval->balance4 ;                     
                                $balance5  += $tval->balance5 ;                     
                                $balance6  += $tval->balance6 ;                     
                                $balance7  += $tval->balance7 ;                     
                                $balance8  += $tval->balance8 ;                     
                                $balance9  += $tval->balance9 ;                     
                                $balance10 += $tval->balance10 ;                     
                                $balance11 += $tval->balance11 ;                     
                                $balance12 += $tval->balance12 ; 
                            }                            
                        }
                       
                       $subTotal1  += $balance1;
                       $subTotal2  += $balance2;
                       $subTotal3  += $balance3;
                       $subTotal4  += $balance4;
                       $subTotal5  += $balance5;
                       $subTotal6  += $balance6;
                       $subTotal7  += $balance7;
                       $subTotal8  += $balance8;
                       $subTotal9  += $balance9;
                       $subTotal10 += $balance10;
                       $subTotal11 += $balance11;
                       $subTotal12 += $balance12;
                    }
                    $cdata = array( 'headCode'=>$tdl->HeadCode,
                          'headName' =>$tdl->HeadName,
                          'amount1'  => $balance1,
                          'amount2'  => $balance2,
                          'amount3'  => $balance3,
                          'amount4'  => $balance4,
                          'amount5'  => $balance5,
                          'amount6'  => $balance6,
                          'amount7'  => $balance7,
                          'amount8'  => $balance8,
                          'amount9'  => $balance9,
                          'amount10' => $balance10,
                          'amount11' => $balance11,
                          'amount12' => $balance12,
                      );
                   array_push( $innerArray,  $cdata);
                }
            }
            $data = array( 'headCode' =>$hCode,
                          'headName'  =>$phead->HeadName,
                          'subtota1'  => $subTotal1,
                          'subtota2'  => $subTotal2,
                          'subtota3'  => $subTotal3,
                          'subtota4'  => $subTotal4,
                          'subtota5'  => $subTotal5,
                          'subtota6'  => $subTotal6,
                          'subtota7'  => $subTotal7,
                          'subtota8'  => $subTotal8,
                          'subtota9'  => $subTotal9,
                          'subtota10' => $subTotal10,
                          'subtota11' => $subTotal11,
                          'subtota12' => $subTotal12,
                          'innerHead' => $innerArray                          
                      );  
                      array_push($secondArray,  $data);              
              
     //exit();
        return $secondArray;       
    
}

public function get_charter_accounts_by_headNae($type,$phead, $except = null) {
    $builder = $this->db->table('acc_coa');
    $builder->select('HeadCode,HeadName,assetCode,DepreciationRate');
    $builder->where('HeadType', $type);
    if($except != null) {
     $builder->where("HeadCode !=",$except);
    }
    $builder->where('PHeadName',$phead);
    $CharterAccounts = $builder->get();
    if($builder->countAll() > 0) {
      return $CharterAccounts->getResult();
    } else {
     return false;
    }
 
}

// get previoue financial year name
public function get_previous_financial_year($numYear){
    $activefyear = $this->getActiveFinancialyear();
    $fyearStartDate = ($activefyear?$activefyear->startDate:'');
    $fyearEndDate = ($activefyear?$activefyear->endDate:'');
    $yearArray = array();
    for($i=1; $i <= $numYear; $i++ ) {
      $previousStartDate = date('Y-m-d',strtotime($fyearStartDate. ' -'.$i.' year'));
      $previousEnddate   = date('Y-m-d',strtotime($fyearEndDate. ' -'.$i.' year'));
      $yr =  date('Y',strtotime($fyearStartDate. ' -'.$i.' year'));
      $fyear =  $this->db->table('financial_year')->select('yearName')->where('YEAR(startDate)',$yr)->get()->getRow();
       if($fyear) {
          array_push($yearArray,$fyear->yearName );
       }     
    }
    return $yearArray; 
  }

  public function getnumPreviousFyear()
  {
    $activefyear    = $this->getActiveFinancialyear();
    $fyearStartDate = ($activefyear?$activefyear->startDate:'');
    
     return $result         = $this->db->table('financial_year')->select('*')->where('startDate <',$fyearStartDate)->countAllResults();

  }


private function get_last_year_balance($type,$HeadCode,$fstartDate , $fendDate)
{
    $previousStartDate = date('Y-m-d',strtotime($fstartDate. ' -1 year'));
    $previousEnddate = date('Y-m-d',strtotime($fendDate. ' -1 year'));
    $fyear =  $this->db->table('financial_year')->select('id')->where('startDate',$previousStartDate)->where('endDate',$previousEnddate)->get()->getRow();
    $oldbalanced = $this->db->table('acc_opening_balance')->select('Debit, Credit')->where('COAID',$HeadCode)->where('fyear',$fyear->id)->get()->getRow();
    if($oldbalanced) {
          if($type == 'A') {
            if($oldbalanced->Debit > $oldbalanced->Credit) {
              return $oldbalanced->Debit;
            } else if($oldbalanced->Credit > $oldbalanced->Debit) {
               return '-'.$oldbalanced->Credit;
            } else {
              return 0 ;
            }

         } else {
           if($oldbalanced->Debit > $oldbalanced->Credit) {
              return '-'.$oldbalanced->Debit;
            } else if($oldbalanced->Credit > $oldbalanced->Debit) {
               return $oldbalanced->Credit;
            } else {
              return 0 ;
            }                  
          } 
    }  else {
       return 0;        
   } 
}

// get previoue year balanc sheet amount by account head
private function get_previous_year_balance_sheet($cid,$type, $numYear){
    $acfyear             =  $this->db->table('financial_year')->select('*')->where('status',1)->get()->getRow();
    $fyearStartDate    = $acfyear->startDate;
    $fyearEndDate      = $acfyear->endDate;
    $previousStartDate = date('Y-m-d',strtotime($fyearStartDate. ' -'.$numYear.' year'));
    $previousEnddate   = date('Y-m-d',strtotime($fyearEndDate. ' -'.$numYear.' year'));

    $fyear =  $this->db->table('financial_year')->select('id')->where('startDate',$previousStartDate)->where('endDate',$previousEnddate)->get()->getRow();
    $oldbalanced = $this->db->table('acc_opening_balance')->select('Debit, Credit')->where('COAID',($cid?$cid:''))->where('fyear',$fyear->id)->get()->getRow();
    
    if($oldbalanced) {
          if($type == 'A') {
            if($oldbalanced->Debit > $oldbalanced->Credit) {
              return $oldbalanced->Debit;
            } else if($oldbalanced->Credit > $oldbalanced->Debit) {
               return '-'.$oldbalanced->Credit;
            } else {
              return 0 ;
            }

         } else {
           if($oldbalanced->Debit > $oldbalanced->Credit) {
              return '-'.$oldbalanced->Debit;
            } else if($oldbalanced->Credit > $oldbalanced->Debit) {
               return $oldbalanced->Credit;
            } else {
              return 0 ;
            }                  
          }   

    } else {
      return 0;
    } 


  }

// Get balance sheet report
public function get_balancedheet_summery($type,$phead,$dtpFromDate,$dtpToDate,$displaynumber) {
    //print_r($displaynumber);exit;
  $returnarning = $this->db->table('acc_predefine_account')->select('CPLCode')->get()->getRow(); 
  //echo $returnarning->CPLCode; exit();      
  $secondLevel = $this->get_charter_accounts_by_headNae($type,$phead);
  $mainHead= array();
  $sumTotal = 0; 
  $sumTotal1 = 0; 
  $sumTotal2 = 0; 
  $sumTotal3 = 0; 
  $secondArray = array();
  if($secondLevel) {    
    
      foreach($secondLevel as $chac) {
          $subTotal = 0;
          $subTotal1 = 0;
          $subTotal2 = 0;
          $subTotal3 = 0;
          $innerArray = array();
          $thirdLevel = $this->get_charter_accounts_by_headNae($type,$chac->HeadName);
          if($thirdLevel) {
              $thirdLevelArray = array();
              foreach($thirdLevel as $tdl) {
                  $balance= 0; 
                  $returnern = 0;
                  $secondbalance =0; 
                  $thirdbalance=0;  
                  $fourthbalance=0;                 
                  $transationLevel = $this->get_charter_accounts_by_headNae($type,$tdl->HeadName);

                  if($transationLevel){
                      $tbalence =0;$tDebit =0; $tCredit = 0; $scyear=0; $tdyear=0;$fryear=0;
                      foreach($transationLevel as $trans) {
                         // $tval  = $this->get_general_ledger_report($trans->HeadCode,$dtpFromDate,$dtpToDate, 0, 0);
                          $tval  = $this->get_clossing_balance($trans->HeadCode,$dtpToDate);
                          if($tval) {
                              $tbalence += $tval;                               
                          } 
                          if($displaynumber == 3){
                            $scyear += $this->get_previous_year_balance_sheet($trans->HeadCode,$type, 1); 
                            $tdyear += $this->get_previous_year_balance_sheet($trans->HeadCode,$type, 2);
                            $fryear += $this->get_previous_year_balance_sheet($trans->HeadCode,$type, 3);
                          }
                          if($displaynumber == 2){
                            $scyear += $this->get_previous_year_balance_sheet($trans->HeadCode,$type, 1); 
                            $tdyear += $this->get_previous_year_balance_sheet($trans->HeadCode,$type, 2);
                            
                          }
                          if($displaynumber == 1){
                            $scyear += $this->get_previous_year_balance_sheet($trans->HeadCode,$type, 1); 
                           
                            
                          }
                         
                          if($returnarning->CPLCode == $trans->HeadCode) {
                              $income = $this->get_head_summery('I','Income',$dtpFromDate,$dtpToDate,1); 
                              $expense = $this->get_head_summery('E','Expenses',$dtpFromDate,$dtpToDate,1);
                              $returnern += ($income - $expense);
                           } 
                      }                        
                      if($returnern != 0) {
                          $balance = $tbalence + $returnern;
                      } else {
                         $balance = $tbalence; 
                      }

                      // if($type == 'A' || $type == 'E') {

                      //     $balance = $tDebit - $tCredit;
                      // } else {
                      //     $balance = $tCredit - $tDebit;
                      // }
                      $secondbalance = $scyear; 
                      $thirdbalance  = $tdyear;  
                      $fourthbalance = $fryear;
                      $sumTotal   +=  $balance;
                      $sumTotal1  +=  $secondbalance;
                      $sumTotal2  +=  $thirdbalance;
                      $sumTotal3  +=  $fourthbalance;
                      $subTotal   +=  $balance;
                      $subTotal1  += $secondbalance;
                      $subTotal2  += $thirdbalance;
                      $subTotal3  += $fourthbalance;
                  }
              
                    $cdata['headCode']   = $tdl->HeadCode; 
                    $cdata['headName']   = $tdl->HeadName;
                    $cdata['amount']     = $balance;
                    if($displaynumber == 3){
                    $cdata['secondyear'] = $secondbalance;
                    $cdata['thirdyear']  = $thirdbalance;
                    $cdata['fourthyear'] = $fourthbalance;
                    }else if($displaynumber == 2){
                        $cdata['secondyear'] = $secondbalance;
                        $cdata['thirdyear']  = $thirdbalance;
                    }else if($displaynumber == 1){
                        $cdata['secondyear'] = $secondbalance;
                    }


                    
                 array_push( $innerArray,  $cdata);
              }
          }
   
            $data['headCode']  = $chac->HeadCode;
            $data['headName']  = $chac->HeadName;         
            $data['subtotal']  = $subTotal;
            if($displaynumber == 3){         
            $data['ssubtotal']  = $subTotal1;         
            $data['tsubtotal']  = $subTotal2;         
            $data['fsubtotal']  = $subTotal3;
            }else if($displaynumber == 2){
                $data['ssubtotal']  = $subTotal1;         
                $data['tsubtotal']  = $subTotal2;
            }else if($displaynumber == 1){
                $data['ssubtotal']  = $subTotal1;   
            }         
            $data['innerHead']  = $innerArray;         

                    array_push($secondArray,  $data);
            
      }
  }
   $maina = array('head'      =>  $phead,
                  'gtotal'    =>  $sumTotal,
                  'sgtotal'   =>  $sumTotal1,
                  'tgtotal'   =>  $sumTotal2,
                  'fgtotal'   =>  $sumTotal3,
                  'nextlevel' =>  $secondArray);
  
   array_push($mainHead,  $maina);      
  return $mainHead;
}


public function get_clossing_balance($hCode,$dtpFromDate,$dtpToDate=null,$subtype=1,$subcode=null,$hType=null) {
    if($dtpToDate!=null) {
     $toDate = $dtpToDate;
    } else {
     $toDate = $dtpFromDate;
    }
    $coaHead = $this->general_led_report_headname($hCode);
    $opening = $this->get_opening_balance($hCode,$dtpFromDate,$toDate);
    $current =  $this->get_general_ledger_report($hCode,$toDate,$toDate,0,0); 
       if($current) {
          foreach($current as $cur) {
              if($coaHead->HeadType == 'A' || $coaHead->HeadType == 'E') {
                  $balance= ($cur->debit - $cur->credit);
               } else {
                  $balance=($cur->credit - $cur->debit);
               }
          }
          
       } else {
         $balance= 0;
       }            
   return  $closingbalance = $opening +  $balance;

}

public function getsubTypeDatahasSubcode() {
    $builder = $this->db->table('acc_subtype st');
    $builder->distinct();
    $builder->select('st.id, st.subtypeName'); 
    $builder->join('acc_subcode sc', 'sc.subTypeId = st.id');           
    $builder->orderBy('st.id', 'asc');
    $query = $builder->get();       
       return $query->getResult();
    
    
}

public function getSubcode($id){          
    $subcodes = $this->db->table('acc_subcode')
               ->select('*')
               ->where('subTypeId',$id)
               ->get(); 
                 return $subcodes->getResult();
              
}

public function get_account_head_by_subtype($id) {
    $result =  $this->db->table('acc_coa')
     ->select('HeadCode,HeadName')
     ->where('subType',$id)
     ->get(); 
       return $result->getResult();
   
}

public function get_subcode_byid($id) {       
    
    $builder = $this->db->table('acc_subcode'); 
    $builder->select('id,name');
    $builder->where('id',$id);      
    $builder->limit(1);
    $query = $builder->get();       
       return $query->getRow();
   
}

public function get_opening_balance_subtype($hCode,$dtpFromDate,$dtpToDate,$subtype=1,$subcode=null){  
    $coaHead = $this->general_led_report_headname($hCode); 
    //  echo $this->db->getLastQuery();exit;
    // $fyearStartDate = $this->session->userdata('fyearStartDate');
    // $fyearEndDate = $this->session->userdata('fyearEndDate');

    $acfyear             =  $this->db->table('financial_year')->select('*')->where('status',1)->get()->getRow();
    $fyearStartDate    = $acfyear->startDate;
    $fyearEndDate      = $acfyear->endDate;
    $oldDate           = date('Y-m-d',strtotime($dtpFromDate. ' -1 year'));
    $prevDate          = date('Y-m-d', strtotime($dtpFromDate .' - 1day'));

if($coaHead->HeadType == 'L' || $coaHead->HeadType =='A') {                 
    if($dtpFromDate >= $fyearStartDate && $dtpFromDate <= $fyearEndDate) { 
        $fyear = $this->db->table('financial_year')->select('*')->where('startDate <=',$oldDate)->where('endDate >=',$oldDate)->get()->getRow();
       
    } else {                   
        $fyear = $this->db->table('financial_year')->select('*')->where('startDate <=',$oldDate)->where('endDate >=',$oldDate)->get()->getRow();
    }  
   
        $oldBalance = $this->get_old_year_closingBalanceNew($hCode,$fyear->id,$coaHead->HeadType,$subtype, $subcode);
        //  echo $this->db->getLastQuery();
        
} else {
    $oldBalance =0;
} 
$opening =  $this->get_general_ledger_report_subhead($hCode,$fyearStartDate,$prevDate,1,0,$subtype,$subcode);

if($opening) {
 foreach($opening as $open) {
     if($coaHead->HeadType == 'A' || $coaHead->HeadType == 'E') {
         $balance= (($open?$open->Debit:0) - ($open?$open->Credit:0));
      } else {
         $balance=(($open?$open->Credit:0) - ($open?$open->Debit:0));
      }
  }
 
} else {
$balance= 0;
}   
return $newBalance = $oldBalance + $balance ; 
         
}

public function get_opening_balance_subtypenew($hCode,$dtpFromDate,$dtpToDate,$subtype=1,$subcode=null){  
    $coaHead = $this->general_led_report_headname($hCode); 
    //  echo $this->db->getLastQuery();exit;
    // $fyearStartDate = $this->session->userdata('fyearStartDate');
    // $fyearEndDate = $this->session->userdata('fyearEndDate');

    $acfyear             =  $this->db->table('financial_year')->select('*')->where('status',1)->get()->getRow();
    $fyearStartDate    = $acfyear->startDate;
    $fyearEndDate      = $acfyear->endDate;
    $oldDate           = date('Y-m-d',strtotime($dtpFromDate. ' -1 year'));
    $prevDate          = date('Y-m-d', strtotime($dtpFromDate .' - 1day'));

if($coaHead->HeadType == 'L' || $coaHead->HeadType =='A') {                 
    if($dtpFromDate >= $fyearStartDate && $dtpFromDate <= $fyearEndDate) { 
        $fyear = $this->db->table('financial_year')->select('*')->where('startDate <=',$oldDate)->where('endDate >=',$oldDate)->get()->getRow();
       
    } else {                   
        $fyear = $this->db->table('financial_year')->select('*')->where('startDate <=',$oldDate)->where('endDate >=',$oldDate)->get()->getRow();
    }  
   
        $oldBalance = $this->get_old_year_closingBalanceNew($hCode,$fyear->id,$coaHead->HeadType,$subtype, $subcode);
        //  echo $this->db->getLastQuery();
        
} else {
    $oldBalance =0;
} 
$opening =  $this->get_general_ledger_report_subhead($hCode,$fyearStartDate,$prevDate,1,0,$subtype,$subcode);

if($opening) {
 foreach($opening as $open) {
     if($coaHead->HeadType == 'A' || $coaHead->HeadType == 'E') {
         $balance= ($open->Debit - $open->Credit);
      } else {
         $balance=(($open?$open->Credit:0) - ($open?$open->Debit:0));
      }
  }
 
} else {
$balance= 0;
}   
return $newBalance = $oldBalance + $balance ; 
         
}

public function get_old_year_closingBalanceNew($hCode,$year,$hType=null,$subtype=null,$subcode= null) {   
    
    $builder = $this->db->table('acc_opening_balance');
    $builder->select('*');
    if($subtype != 1) {
       $builder->where('subCode',$subcode);
       $builder->where('subType',$subtype);
    } 
     $builder->where('COAID',$hCode);      
    
    $builder->where('fyear',$year);            
   $closing =  $builder->get();

     $closingvalue = $closing->getRow();
     if($hType == 'A') {
        return (($closingvalue?$closingvalue->Debit:0) - ($closingvalue?$closingvalue->Credit:0));
     } else {
       return (($closingvalue?$closingvalue->Credit:0) - ($closingvalue?$closingvalue->Debit:0));
     }        
  
   
   
}

public function get_subTypeItems($id)
{
    $data = $this->db->table('acc_subcode')
         ->select("id,name")
        ->where('subTypeId', $id)                   
        ->orderBy('id', 'ASC')            
        ->get();
        return $data->getResult();    
   
}

}
?>