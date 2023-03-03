<?php namespace App\Modules\Account\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;
class Bdtaskt1m8VApprovalModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();
        $this->hasCreateAccess = $this->permission->method('voucher_approval', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('voucher_approval', 'update')->access();
    }

    /*--------------------------
    | Get receipt voucher
    *--------------------------*/
    public function bdtaskt1m8_01_getAppVoucher($postData=null){
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
           $searchQuery = " (trans.VNo like '%".$searchValue."%'OR trans.COAID like '%".$searchValue."%' OR emp.nameE like '%".$searchValue."%' OR emp.short_name like '%".$searchValue."%' OR emp.nameA like '%".$searchValue."%' OR trans.VDate like '%".$searchValue."%')";
        }
        if($search_date != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
            $searchQuery .= "(trans.VDate = '".$search_date."' ) ";
        }
        
       
        ## Fetch records
        $builder3 = $this->db->table('acc_vaucher trans');
        $builder3->select("trans.ID, trans.VNo,trans.Vtype, trans.COAID, trans.Narration, SUM(trans.Debit) as Debit, SUM(trans.Credit) as Credit, trans.CreateDate, trans.isApproved, vt.name as typeName, CONCAT_WS(' ', emp.first_name, '-', emp.last_name) as created_by, '' as branch_name, CONCAT_WS(' ', emp1.first_name, '-', emp1.last_name) as updated_by,");
        $builder3->join("hrm_employees emp", "emp.employee_id=trans.CreateBy", "left");
        $builder3->join("hrm_employees emp1", "emp1.employee_id=trans.UpdateBy", "left");
        $builder3->join("acc_voucher_type vt", "vt.typen=trans.Vtype", "left");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }
      
        $builder3->whereIn("trans.isApproved", array(0));
        $builder3->whereNotIn("trans.Vtype", array('SINV'));
        $builder3->groupBy("trans.VNo");
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $totalRecords = $builder3->countAllResults(false);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        $data = array();
        
        $pending = get_phrases(['pending']);
        $approved = get_phrases(['approved']);
        $rejected = get_phrases(['rejected']);
        foreach($records as $record ){ 
            $action = '';
            $action .='<a href="javascript:void(0);" data-id="'.$record['VNo'].'" class="btn btn-info-soft btnC viewAction mr-1"><i class="fa fa-eye"></i></a>';
            if($record['isApproved']==0){
                if($this->hasCreateAccess || $this->hasUpdateAccess){
                    $action .='<a href="javascript:void(0);" data-id="'.$record['VNo'].'" class="btn btn-success-soft btnC statusAction"><i class="fa fa-check"></i></a>';
                }
                $button ='<a href="javascript:void(0);" class="badge badge-warning text-white">'.$pending.'</a>';
            }else if($record['isApproved']==1){
                $button ='<a href="javascript:void(0);" class="badge badge-success text-white">'.$approved.'</a>';
            }else{
                $button ='<a href="javascript:void(0);" class="badge badge-danger text-white">'.$rejected.'</a>';
            }
            $checkBtn = '<input type="checkbox" name="checkrow[]" class="all" id="checkchild" value="'.$record['VNo'].'"> ';
            $data[] = array( 
                'checkBtn'     => $checkBtn,
                'VNo'          => $record['VNo'],
                'typeName'     => $record['Vtype'],
                'COAID'        => $record['COAID'],
                'Narration'    => $record['Narration'],
                'created_by'   => $record['created_by'],
                'updated_by'   => $record['updated_by'],
                'Debit'        => $record['Debit'],
                'Credit'       => $record['Credit'],
                'branch_name'  => $record['branch_name'],
                'CreateDate'   => date('d/m/Y h:i A', strtotime($record['CreateDate'])),
                'action'       => $action,
                'button'       => $button
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
             'subType'        =>  NULL,     
             'subCode'        =>  NULL,     
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

}
?>