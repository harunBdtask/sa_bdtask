<?php namespace App\Modules\Account\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;
class Bdtaskt1m8RefundVModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();
        if(session('defaultLang')=='english'){
            $this->langColumn = 'nameE';
        }else{
            $this->langColumn = 'nameA';
        }
        $this->hasDeleteAccess = $this->permission->method('payment_voucher', 'delete')->access();
    }

    /*--------------------------
    | Get rfund voucher
    *--------------------------*/
    public function bdtaskt1m8_01_getRefundVoucher($postData=null){
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
           $searchQuery = " (pv.id like '%".$searchValue."%' OR file.file_no like '%".$searchValue."%' OR p.nameE like '%".$searchValue."%' OR p.nameA like '%".$searchValue."%' OR pv.created_date like '%".$searchValue."%') ";
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
        $builder3->select("pv.*, file.file_no, p.$this->langColumn as patient_name, CONCAT_WS(' ', emp1.first_name, '-', emp1.last_name) as doctor_name, CONCAT_WS(' ', emp.first_name, emp.last_name) as created_by");
        $builder3->join("patient p", "p.patient_id=pv.patient_id", "left");
        $builder3->join("patient_file file", "file.patient_id=p.patient_id", "left");
        $builder3->join("hrm_employees emp", "emp.employee_id=pv.created_by", "left");
        $builder3->join("hrm_employees emp1", "emp1.employee_id=pv.doctor_id", "left");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }
        $builder3->where("pv.vtype", "RFV");
        if(session('branchId') > 0){
            $builder3->where('pv.branch_id', session('branchId'));
        }
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
        $app = get_phrases(['approved', 'delete', 'request']);
        $undo = get_phrases(['undo', 'request']);
        $req = get_phrases(['delete', 'requested']);
        $file = get_phrases(['file', 'preview']);

        $data = array();
        foreach($records as $record ){ 
            $button = '';
            $status = '';

            $button .='<a href="'.base_url('account/accounts/refundVDetails/'.$record['id']).'" class="btn btn-success-soft btnC actionPreview mr-1 custool" title="'.$view.'"><i class="far fa-eye"></i></a>';
            // if($this->permission->method('refund_voucher', 'update')->access()){
            //     $button .='<a href="javascript:void(0);" class="btn btn-primary-soft btnC mr-2"><i class="far fa-edit"></i></a>';
            // }
            if($record['status']==0){
                $status .= '<span class="badge badge-danger-soft">'.$delete.'</span>';
            }else{
                if($record['isDelReq']==1){
                    $status .= '<span class="badge badge-warning-soft text-warning">'.$req.'</span>';
                }else{
                    $status .= '--';
                }
            }
            
            if($record['isDelReq']==0){
                $button .='<a href="javascript:void(0);" data-id="'.$record['id'].'" data-date="'.$record['voucher_date'].'" class="btn btn-danger-soft btnC deleteAction custool mr-1" title="'.$req.'"><i class="far fa-paper-plane"></i></a>';
            }else{
                if($record['status']==1){
                    if($this->hasDeleteAccess){
                        $button .='<a href="javascript:void(0);" data-id="'.$record['id'].'" data-date="'.date('Y-m-d', strtotime($record['del_req_date'])).'" data-p="'.$record['patient_id'].'" class="btn btn-success-soft btnC approvedAction mr-1 custool mr-1" title="'.$app.'"><i class="fa fa-check"></i></a>';
                    }
                    $button .='<a href="javascript:void(0);" data-id="'.$record['id'].'" class="btn btn-purple-soft btnC undoAction custool mr-1" title="'.$undo.'"><i class="fas fa-undo"></i></a>';
                }
            }
            if($record['attach_file'] !=null){
                $button .='<a href="'.base_url($record['attach_file']).'" class="btn btn-purple-soft btnC custool" title="'.$file.'" data-lity><i class="fas fa-file-image"></i></a>';
            }

            $data[] = array( 
                'id'           => $record['id'],
                'file_no'      => $record['file_no'],
                'patient_name' => $record['patient_name'],
                'created_by'   => $record['created_by'],
                'doctor_name'   => $record['doctor_name'],
                'total'        => getPriceFormat($record['total']),
                'payment'      => getPriceFormat($record['payment']),
                'created_date' => $record['created_date'],
                'receipt_voucher' => '<a href="'.base_url('account/services/detailsInvoice/'.$record['ref_voucher']).'" class="btn btn-success-soft btnC actionPreview mr-2">RFV-'.$record['ref_voucher'].'</a>',
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
    | Get invoice details by Id
    *--------------------------*/
    public function bdtaskt1m8_02_getInvDetailsById($id){
        $result = $this->bdtaskt1m8_03_getInvById($id);
        $result->services = $this->bdtaskt1m8_04_getInvServById($id);
        return $result;
    }

    /*--------------------------
    | Get invoice details by Id
    *--------------------------*/
    public function bdtaskt1m8_03_getInvById($id){
        $result = $this->db->table('service_invoices si')
                        ->select("si.*,p.nameE, p.nameA, p.mobile, p.age, file.file_no, file.nid_no, file.gender, p.acc_head, p.balance")
                        ->join("patient p", "p.patient_id=si.patient_id", "left")
                        ->join("patient_file file", "file.patient_id=p.patient_id", "left")
                        ->where('si.id', $id)
                        ->get()
                        ->getRow();
        return $result;
    }

    /*--------------------------
    | Get services by inovice Id
    *--------------------------*/
    public function bdtaskt1m8_04_getInvServById($id){
        $result = $this->db->table('service_invoice_details sid')
                        ->select("sid.*, s.code_no, s.nameE, s.nameA")
                        ->join("services s", "s.id=sid.app_service_id", "left")
                        ->where('sid.invoice_id', $id)
                        ->get()
                        ->getResult();
        return $result;
    }
}
?>