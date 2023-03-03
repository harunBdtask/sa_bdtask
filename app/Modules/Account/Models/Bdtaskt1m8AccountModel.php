<?php namespace App\Modules\Account\Models;
use CodeIgniter\Model;
class Bdtaskt1m8AccountModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        if(session('defaultLang')=='english'){
            $this->langColumn = 'nameE';
        }else{
            $this->langColumn = 'nameA';
        }
    }

    public function get_userlist(){
        $query = $this->db->table('acc_coa')->select('*')->orderBy('HeadName')->get()->getResult();
        if (!empty($query)) {
            return $query;
        } else {
            return false;
        }
    }

    public function dfs($HeadName,$HeadCode,$oResult,$visit,$d)
    {
        // if($d==0) echo "<li class=\"jstree-open\">$HeadName";
        // else if($d==1) echo "<li class=\"jstree-open\"><a href='javascript:' onclick=\"loadData('".$HeadCode."')\">$HeadName</a>";
        // else echo "<li><a href='javascript:' onclick=\"loadData('".$HeadCode."')\">$HeadName</a>";
        $sbalance = 0;//$this->coa_balance($HeadCode);
        $sopening = 0;//$this->opening_coa_balance($HeadCode);
        $balance  = ($sbalance ? number_format($sbalance, 2) : number_format(0, 2));
        $opening  = ($sopening ? number_format($sopening, 2) : number_format(0, 2));

        if ($d == 0) echo "<li class=\"jstree-open\">$HeadName <a href=/'javascript:void(0)/' class=\"form-control headanchor\"><span class=\"coa_hd\"><b>Head Name</b></span><span class=\"bal_opening\"><b></b></span><span class=\"bal_span\"><b> </b></span></a>";
        else if ($d == 1) echo "<li class=\"jstree-open\" id='" . $HeadCode . "'><a href='javascript:' class='form-control jstreelip' onclick=\"loadData('" . $HeadCode . "')\">$HeadName <span class=\"bal_opening\"> </span><span class=\"bal_span_pre\"></span></a>";
        else echo "<li id='" . $HeadCode . "'><a href='javascript:' class='form-control'  onclick=\"loadData('" . $HeadCode . "')\">$HeadName <span class=\"bal_opening\"> </span> <span class=\"bal_span_pre\"></span></a>";
        $p=0;
        for($i=1;$i< count($oResult);$i++)
        {

            if (!$visit[$i])
            {
                if ($HeadCode==$oResult[$i]->PHeadCode)
                {
                    $visit[$i]=true;
                    if($p==0) echo "<ul>";
                    $p++;
                    $this->dfs($oResult[$i]->HeadName,$oResult[$i]->HeadCode,$oResult,$visit,$d+1);
                }
            }
        }
        if($p==0)
            echo "</li>";
        else
            echo "</ul>";
    }
   
    /*--------------------------
    | Count total child account by code
    *--------------------------*/
    public function bdtaskt1m8_03_getParentCById($code, $head){
        $result = $this->db->table('acc_coa')->select('MAX(HeadCode) as hc')
                        ->where('PHeadName',$head)
                        ->like('HeadCode',$code, 'after')
                        ->get()
                        ->getRow();
                       
        return $result;
    }

    /*--------------------------
    | Get invoice details by Id
    *--------------------------*/
    public function bdtaskt1m8_03_getInvDetailsId($id){
        $result = $this->db->table('service_invoices inv')
                        ->select("inv.*, patient.$this->langColumn as patient_name, patient.mobile, file.gender, file.file_no")
                        ->join("patient", "patient.patient_id=inv.patient_id", "left")
                        ->join("patient_file file", "file.patient_id=patient.patient_id", "left")
                        ->where('inv.id', $id)
                        ->get()
                        ->getRow();
        $result->items = $this->db->table('service_invoice_details sid')
                        ->select("sid.*, s.code_no, s.nameE, s.nameA")
                        ->join("services s", "s.id=sid.app_service_id", "left")
                        ->where('sid.invoice_id', $id)
                        ->get()
                        ->getResult();
        return $result;
    }

    /*--------------------------
    | Get unpaid services by App ID
    *--------------------------*/
    public function bdtaskt1m8_04_getPackServById($pid){
        $result = $this->db->table('offer_package_details opd')
                        ->select("opd.offer_package_id as package_id, opd.discount, opd.qty, s.id as service_id, s.code_no, s.nameE, s.nameA, s.price")
                        ->join("services s", "s.id=opd.service_id", "left")
                        ->where('opd.offer_package_id', $pid)
                        ->get()
                        ->getResult();
        return $result;
    }

    /*--------------------------
    | Get unpaid services by App ID
    *--------------------------*/
    public function bdtaskt1m8_05_getPntById($pid){
        $result = $this->db->table('patient_file file')
                        ->select("file.*, patient.*")
                        ->join("patient", "patient.patient_id=file.patient_id", "left")
                        ->where('file.patient_id', $pid)
                        ->get()
                        ->getRow();
        return $result;
    }

    /*--------------------------
    | Get unpaid services by App ID
    *--------------------------*/
    public function bdtaskt1m8_06_getMaxVoucher($like){
        $result = $this->db->table('acc_transaction')
                        ->select("VNo")
                        ->where('Vtype', $like)
                        ->orderBy('ID', 'DESC')
                        ->get()
                        ->getRow();
        return $result;
    }

    /*--------------------------
    | Get credit or debit account headCode
    *--------------------------*/
    public function bdtaskt1m8_07_getCreditOrDebitAcc(){
        $result = $this->db->table('acc_coa')
                        ->select("HeadCode as id, HeadName as text")
                        ->where('IsTransaction', 1)  
                        ->like('HeadCode', 1211, 'after')
                        ->orderBy('HeadName', 'ASC')
                        ->get()
                        ->getResult();
        return $result;
    }

    /*--------------------------
    | Get all account headCode
    *--------------------------*/
    public function bdtaskt1m8_08_getAllAccount(){
        $result = $this->db->table('acc_coa')
                        ->select("HeadCode as id, CONCAT_WS(' ', HeadCode, '-', HeadName) as text")
                        ->where('IsActive', 1)  
                        ->orderBy('HeadName', 'ASC')
                        ->get()
                        ->getResult();
        return $result;
    }

    /*--------------------------
    | Get all account headCode
    *--------------------------*/
    public function bdtaskt1m8_09_updateBalance($head, $amount){
        if($amount >= 0){
            $bl = '+'.$amount;
        }else{
            $bl = '-'.abs($amount);
        }
        $this->db->table('patient')->set('balance', 'balance'.$bl, FALSE)->where('acc_head', $head)->update();
        return $this->db->affectedRows();
    }

    public function bdtaskt1m8_10_checkhead($pid){
        $result = $this->db->table('acc_coa')
                        ->select("*")
                        ->where('HeadCode', $pid)
                        ->countAllResults();
                    
        return $result;
    }

    public function bdtaskt1m8_10_checkheadcode($pid){
        $result = $this->db->table('acc_coa')
                        ->select("*")
                        ->where('HeadCode', $pid)
                        ->countAllResults();
                    
        return $result;
    } 

    public function dealer_list()
   {
       $data = $this->db->table('dealer_info a')
                        ->select('a.*,b.HeadCode')
                        ->join('acc_coa b','b.dealer_id = a.id')
                        ->where('a.status',1)
                        ->get()
                        ->getResult();

    $list = array('' => get_phrases(['select','dealer']));
    if($data){
        foreach($data as $row){
            $list[$row->HeadCode] = $row->name;
        }
    }
    return $list;                   
   }   
   
   public function bdtaskt1m8_09_geAllopeningHeads()
   {
    return  $data = $this->db->table("acc_coa")
    ->select('HeadCode as id,HeadName as text')
    ->where('HeadLevel >=', 3)  
    ->where('IsActive', 1)
    ->whereIn('HeadType', array('A','L'))          
    ->orderBy('HeadType')
    ->get()
    ->getResult();
   }

    /*--------------------------
    | Get type wise voucher list 
    *--------------------------*/
    public function bdtaskt1m8_09_getOpeningBalanceList($postData=null){
        $response = array();
        ## Read value
       @$search_date     = $postData['search_date'];
       @$draw            = $postData['draw'];
       @$start           = $postData['start'];
       @$rowperpage      = $postData['length']; // Rows display per page 
       @$columnIndex     = $postData['order'][0]['column']; // Column index
       @$columnName      = $postData['columns'][$columnIndex]['data']; // Column name
       @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
       @$searchValue     = $postData['search']['value']; // Search value
       ## Search 
       $searchQuery = "";
       if($searchValue != ''){
          $searchQuery = " (ob.openDate like '%".$searchValue."%' OR (ob.id like '%".$searchValue."%' OR coa.HeadName like '%".$searchValue."%' OR subc.name like '%".$searchValue."%' OR emp.short_name like '%".$searchValue."%')";
       }
       if($search_date != ''){
        if($searchQuery != ''){
          $searchQuery .= " AND ";
        }
        $searchQuery .= "(ob.openDate = '".$search_date."' ) ";
    }
     
       ## Fetch records
       $builder3 = $this->db->table('acc_opening_balance as ob');
       $builder3->select("ob.*,coa.HeadName,subc.name as Subhead,CONCAT_WS(' ', emp.first_name, '-', emp.last_name) as created_by");
       $builder3->join("acc_coa coa", "coa.HeadCode=ob.COAID", "left");
       $builder3->join("acc_subcode subc", "subc.id=ob.subCode", "left");
       $builder3->join("hrm_employees emp", "emp.employee_id=ob.CreateBy", "left");
       $totalRecords = $builder3->countAllResults(false);
       if($searchQuery != ''){
          $builder3->where($searchQuery);
       }
       $totalRecordwithFilter = $builder3->countAllResults(false);
       $builder3->orderBy($columnName, $columnSortOrder);
       $builder3->limit($rowperpage, $start);
       $query3   =  $builder3->get();
       $records  =  $query3->getResultArray();
       $data     = array();

       $sl = 1;
       foreach($records as $record ){ 
           $button = '';
           $action = '';
           $action .='<a href="javascript:void(0);" data-id="'.$record['id'].'" class="btn btn-info-soft btnC viewAction mr-1"><i class="fa fa-eye"></i></a>';
           
           
           $data[] = array( 
               'id'          => ($postData['start'] ? $postData['start'] : 0) + $sl++,
               'Headname'    => $record['HeadName'],
               'date'        => date('d/m/Y', strtotime($record['openDate'])),
               'subhead'     => $record['Subhead'],
               'debit'       => $record['Debit'],
               'credit'      => $record['Credit'],
               'fyear'       => $record['fyear'],
               'created_by'  => $record['created_by'],
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

   public function getActiveFiscalyear()
    {
         $fiscalyear = $this->db->table('financial_year')->select('*')->where('status',1)->get()->getRow();
         return ($fiscalyear?$fiscalyear->id:0);
    }

    public function getPredefineCode()
    {
        return $fields = $this->db->getFieldNames('acc_predefine_account');
    }

    public function getPredefineCodeValues()
    {
        return $result = $this->db->table('acc_predefine_account')->select('*')->get()->getRow();
    }

    public function getCoaHeads()
    {
         $result = $this->db->table('acc_coa')->select('*')->where('isActive',1)->get()->getResult();
         $list = array('' => get_phrases(['select', 'Head']));
         if (!empty($result)) {
             foreach ($result as $value) {
                 $list[$value->HeadCode] = $value->HeadName;
             }
         }
         return $list;
    }

        public function getCoaHeadsNew()
    {
         $result = $this->db->table('acc_coa')->select('*')->where('isActive',1)->where('HeadLevel',4)->get()->getResult();
         $list = array('' => get_phrases(['select', 'Head']));
         if (!empty($result)) {
             foreach ($result as $value) {
                 $list[$value->HeadCode] = $value->HeadName;
             }
         }
         return $list;
    }

    public function getsubTypeData($id=null) {
        $builder = $this->db->table('acc_subtype');
        $builder->select('*');
        if($id != null) {
            $builder->where('id',$id);
        }        
        $builder->orderBy('id', 'asc');
        $query = $builder->get();
        if($builder->countAllResults() > 0) {
           return $query->getResult();
        }
        return false;
    }
    public function setting_info()
    {
        return $settingdata = $this->db->table('setting')->select('*')->get()->getRow();
    }

    public function bdtaskt1m8_04_getOpenigDetails($id)
    {
        $query = $this->db->table('acc_opening_balance trans')
        ->select("trans.*,CONCAT_WS(' ', emp1.first_name, emp1.last_name) as created_by, '' as typeName")
        ->join('hrm_employees emp1', 'emp1.employee_id=trans.CreateBy', 'left')
        ->where('trans.id', $id)
        ->get()->getRow();
if(!empty($query)) {
$query->details = $this->db->table('acc_opening_balance trans1')
        ->select('trans1.*, acc_coa.HeadName,acc_subcode.name as subname,trans1.subType')
        ->join('acc_coa', 'acc_coa.HeadCode=trans1.COAID', 'left')
        ->join('acc_subcode', 'acc_subcode.id=trans1.subCode', 'left')
        ->where('trans1.id', $id)
        ->get()->getResult();
return  $query;
}else{
return false;
}      
    }

    public function bdtaskt1m8_10_financialyearList()
    {
        $result = $this->db->table('financial_year')->select('*')->where('status !=',2)->get()->getResult();
        $list = array('' => get_phrases(['select', 'Year']));
        if (!empty($result)) {
            foreach ($result as $value) {
                $list[$value->id] = $value->yearName	;
            }
        }
        return $list;
    }

}
