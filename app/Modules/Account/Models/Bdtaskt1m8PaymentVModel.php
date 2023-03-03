<?php namespace App\Modules\Account\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;
class Bdtaskt1m8PaymentVModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();
        $this->hasPayDelete= $this->permission->method('payment_voucher', 'delete')->access();
        $this->hasVDelApproval= $this->permission->method('vchr_del_req_appr', 'delete')->access();
        $this->hasJvCreate= $this->permission->method('journal_voucher', 'create')->access();
    }

    /*--------------------------
    | Get receipt voucher
    *--------------------------*/
    public function bdtaskt1m8_01_getAllPVoucher($postData=null){
         $response = array();
         ## Read value
        @$search_date = $postData['search_date'];
        @$doctor_id = $postData['doctor_id'];
        @$patient_id = $postData['patient_id'];
        @$file_no = trim($postData['file_no']);
        @$invoice_id = trim($postData['invoice_id']);
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
           $searchQuery = " (pv.id like '%".$searchValue."%'OR file.file_no like '%".$searchValue."%' OR p.nameE like '%".$searchValue."%' OR p.nameA like '%".$searchValue."%' OR pv.created_date like '%".$searchValue."%') ";
        }
        if($search_date != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
            $searchQuery .= "(pv.voucher_date = '".$search_date."' ) ";
        }

        if($doctor_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
            $searchQuery .= "(pv.doctor_id = '".$doctor_id."' ) ";
        }
        if($patient_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
            $searchQuery .= "(pv.patient_id = '".$patient_id."' ) ";
        }
        if($file_no != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
            $searchQuery .= "(file.file_no = '".$file_no."' ) ";
        }
        if($invoice_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
            $searchQuery .= "(pv.id = '".$invoice_id."' ) ";
        }
       
        ## Fetch records
        $builder3 = $this->db->table('payment_vouchers pv');
        $builder3->select("pv.*, file.file_no, p.nameE,  p.nameA, CONCAT_WS(' ', emp1.first_name, '-', emp1.last_name) as doctor_name, CONCAT_WS(' ', emp.first_name, emp.last_name) as created_by");
        $builder3->join("patient p", "p.patient_id=pv.patient_id", "left");
        $builder3->join("patient_file file", "file.patient_id=p.patient_id", "left");
        $builder3->join("hrm_employees emp", "emp.employee_id=pv.created_by", "left");
        $builder3->join("hrm_employees emp1", "emp1.employee_id=pv.doctor_id", "left");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }
        $builder3->where("pv.vtype", "PV");
        // Total number of record with filtering
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        $totalRecords = $builder3->countAll();
        if($searchQuery == ''){
           $totalRecords = $totalRecordwithFilter;
        }

        $delete = get_phrases(['deleted']);
        $view = get_phrases(['view']);
        $jv = get_phrases(['create', 'journal']);
        $app = get_phrases(['approved', 'delete', 'request']);
        $undo = get_phrases(['undo', 'request']);
        $req = get_phrases(['delete', 'requested']);
        $file = get_phrases(['file', 'preview']);

        $data = array();
        foreach($records as $record ){ 
            $button = '';
            $status = '';

            $button .='<a href="'.base_url('account/accounts/paymentVDetails/'.$record['id']).'" class="btn btn-success-soft btnC actionPreview mr-1 custool" title="'.$view.'"><i class="far fa-eye"></i></a>';
            if($this->hasJvCreate){
                $button .='<a href="javascript:void(0);" class="btn btn-warning-soft btnC mr-1 jvAction custool" title="'.$jv.'"><i class="fas fa-dollar-sign"></i></a>';
            }
            if($record['status']==0){
                $status .= '<span class="badge badge-danger-soft">'.$delete.'</span>';
            }else{
                if($record['isDelReq']==1){
                    $status .= '<span class="badge badge-warning-soft text-warning">'.$req.'</span>';
                }else{
                    $status .= '--';
                }
            }
            
            if($this->hasPayDelete){
                if($record['isDelReq']==0){
                    $button .='<a href="javascript:void(0);" data-id="'.$record['id'].'" data-date="'.$record['voucher_date'].'" class="btn btn-danger-soft btnC deleteAction custool  mr-1" title="'.$req.'"><i class="far fa-paper-plane"></i></a>';
                }else{
                    if($record['status']==1){
                        if($this->hasVDelApproval){
                            $button .='<a href="javascript:void(0);" data-id="'.$record['id'].'" data-date="'.date('Y-m-d', strtotime($record['del_req_date'])).'" data-p="'.$record['patient_id'].'" data-ref="'.$record['ref_voucher'].'" class="btn btn-success-soft btnC approvedAction mr-1 custool  mr-1" title="'.$app.'"><i class="fa fa-check"></i></a>';
                        }
                        $button .='<a href="javascript:void(0);" data-id="'.$record['id'].'" class="btn btn-purple-soft btnC undoAction custool  mr-1" title="'.$undo.'"><i class="fas fa-undo"></i></a>';
                    }
                }
            }

            if($record['attach_file'] !=null){
                $button .='<a href="'.base_url($record['attach_file']).'" class="btn btn-purple-soft btnC custool" title="'.$file.'" data-lity><i class="fas fa-file-image"></i></a>';
            }

            $data[] = array( 
                'id'           => $record['id'],
                'patient_id'   => $record['patient_id'],
                'file_no'      => $record['file_no'],
                'nameE'        => $record['nameE'],
                'nameA'        => $record['nameA'],
                'voucher_date' => $record['voucher_date'],
                'created_by'   => $record['created_by'],
                'doctor_name'  => $record['doctor_name'],
                'total'        => getPriceFormat($record['total']),
                'payment'      => getPriceFormat($record['payment']),
                'created_date' => $record['created_date'],
                'receipt_voucher' => '<a href="'.base_url('account/accounts/receiptVDetails/'.$record['ref_voucher']).'" class="btn btn-success-soft btnC actionPreview mr-2">RV-'.$record['ref_voucher'].'</a>',
                'status'       => $status,
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

    /*--------------------------
    | Get receipt voucher details by Id
    *--------------------------*/
    public function bdtaskt1m8_02_getPVDetailsId($id){
        $result = $this->bdtaskt1m8_03_getPVPntDetails($id);
        $result->pay = $this->bdtaskt1m8_05_getPVPayDetails($id);
        $result->services = $this->bdtaskt1m8_06_getPVServiceDetails($id);
        return $result;
    }

    /*--------------------------
    | Get payment voucher patient by Id
    *--------------------------*/
    public function bdtaskt1m8_03_getPVPntDetails($id){
        $result = $this->db->table('payment_vouchers pv')
                        ->select("pv.*,p.nameE, p.nameA, p.mobile, p.age, CONCAT_WS(' ', emp.first_name,  emp.last_name) as doctor, file.file_no, file.nid_no, file.gender, branch.vat_no, CONCAT_WS(' ', emp1.first_name, emp1.last_name) as created_by, IF(pv.package_id=0, pv.remarks, op.nameE) as pack_name, branch.nameE as branch_name")
                        ->join("patient p", "p.patient_id=pv.patient_id", "left")
                        ->join("patient_file file", "file.patient_id=p.patient_id", "left")
                        ->join("hrm_employees emp", "emp.employee_id=pv.doctor_id", "left")
                        ->join("hrm_employees emp1", "emp1.employee_id=pv.created_by", "left")
                        ->join("offer_packages op", "op.id=pv.package_id", "left")
                        ->join("branch", "branch.id=emp1.branch_id", "left")
                        ->where('pv.id', $id)
                        ->get()
                        ->getRow();
        return $result;
    }

    /*--------------------------
    | Get payment voucher payment by Id
    *--------------------------*/
    public function bdtaskt1m8_05_getPVPayDetails($id){
        $result = $this->db->table('payment_voucher_method pvm')
                        ->select("pvm.*, ld.nameE, ld.nameA")
                        ->join("list_data ld", "ld.id=pvm.pay_method_id", "left")
                        ->where('pvm.voucher_id', $id)
                        ->get()
                        ->getResult();
        return $result;
    }

    /*--------------------------
    | Get service details
    *--------------------------*/
    public function bdtaskt1m8_06_getPVServiceDetails($id){
        $result = $this->db->table('payment_voucher_details pvd')
                        ->select("pvd.*, s.code_no, s.nameE, s.nameA")
                        ->join("services s", "s.id=pvd.service_id", "left")
                        ->where('pvd.voucher_id', $id)
                        ->get()
                        ->getResult();
        return $result;
    }

    /*--------------------------
    | Get payment voucher payment by Id
    *--------------------------*/
    public function bdtaskt1m8_07_getTransferAmount($id){
        $result = $this->db->table('payment_voucher_method')
                        ->select("amount")
                        ->where('voucher_id', $id)
                        ->where('pay_method_id', 129)
                        ->get()
                        ->getRow();
        if(!empty($result)){
            return $result;
        }else{
            return false;
        }
    }
}
?>