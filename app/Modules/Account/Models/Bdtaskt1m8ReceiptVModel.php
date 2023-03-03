<?php namespace App\Modules\Account\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;
class Bdtaskt1m8ReceiptVModel extends Model
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
        $this->hasReadAccess = $this->permission->method('receipt_voucher', 'read')->access();
        $this->hasUpdateAccess = $this->permission->method('receipt_voucher', 'update')->access();
        $this->hasCreateAccess = $this->permission->method('receipt_voucher', 'create')->access();
        $this->hasDeleteAccess = $this->permission->method('receipt_voucher', 'delete')->access();
        $this->hasDelReqApp = $this->permission->method('vchr_del_req_appr', 'delete')->access();
    }

    /*--------------------------
    | Get receipt voucher
    *--------------------------*/
    public function bdtaskt1m8_01_getAllReceipt($postData=null){
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
           $searchQuery = " (vouchers.id like '%".$searchValue."%' OR p.nameE like '%".$searchValue."%' OR p.nameA like '%".$searchValue."%' OR emp.nameE like '%".$searchValue."%' OR emp.nameA like '%".$searchValue."%' OR emp.short_name like '%".$searchValue."%') ";
        }
        if($search_date != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
            $searchQuery .= "(vouchers.voucher_date = '".$search_date."' ) ";
        }

        if($doctor_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
            $searchQuery .= "(vouchers.doctor_id = '".$doctor_id."' ) ";
        }
        if($patient_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
            $searchQuery .= "(vouchers.patient_id = '".$patient_id."' ) ";
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
            $searchQuery .= "(vouchers.id = '".$invoice_id."' ) ";
        }
       
        ## Fetch records
        $builder = $this->db->table('vouchers');
        $builder->select("vouchers.*, p.$this->langColumn as patient_name, p.acc_head, file.file_no, p.balance, CONCAT_WS(' ', emp.first_name, emp.last_name) as created_by, CONCAT_WS(' ', emp1.first_name, emp1.last_name) as doctor_name, CONCAT_WS(' ', pack.id, '-', pack.$this->langColumn) as package_name");
        $builder->join("patient p", "p.patient_id=vouchers.patient_id", "left");
        $builder->join("patient_file file", "file.patient_id=vouchers.patient_id", "left");
        $builder->join("hrm_employees emp", "emp.employee_id=vouchers.created_by", "left");
        $builder->join("hrm_employees emp1", "emp1.employee_id=vouchers.doctor_id", "left");
        $builder->join("offer_packages pack", "pack.id=vouchers.package_id", "left");
        if($searchQuery != ''){
           $builder->where($searchQuery);
        }
        $builder->where("vouchers.vtype", "RV");
        $builder->where("vouchers.status", 1);
        // Total number of record with filtering
        $totalRecordwithFilter = $builder->countAllResults(false);
        $builder->orderBy($columnName, $columnSortOrder);
        $builder->limit($rowperpage, $start);
        $query   =  $builder->get();
        $records =   $query->getResultArray();
        $totalRecords = $builder->countAll();
        if($searchQuery == ''){
           $totalRecords = $totalRecordwithFilter;
        }

        $paid = get_phrases(['paid']);
        $view = get_phrases(['view']);
        $edit = get_phrases(['edit']);
        $app = get_phrases(['approved', 'delete', 'request']);
        $undo = get_phrases(['undo', 'request']);
        $req = get_phrases(['delete', 'requested']);
        $file = get_phrases(['file', 'preview']);

        $data = array();
        foreach($records as $record ){ 
            $button = '';
            if($this->hasReadAccess){
                $button .='<a href="'.base_url('account/accounts/receiptVDetails/'.$record['id']).'" class="btn btn-success-soft btnC actionPreview mr-1 custool" title="'.$view.'"><i class="far fa-eye"></i></a>';
            }
            if($this->hasUpdateAccess){
                $button .='<a href="javascript:void(0);" data-id="'.$record['id'].'" class="btn btn-primary-soft btnC mr-1 editAction custool" title="'.$edit.'"><i class="far fa-edit"></i></a>';
            }
            if($record['isDelReq']==1){
                if($this->hasDeleteAccess){
                    $button .='<a href="javascript:void(0);" data-id="'.$record['id'].'" data-date="'.date('Y-m-d', strtotime($record['del_req_date'])).'" data-p="'.$record['acc_head'].'" class="btn btn-success-soft btnC reqAppAction mr-1 custool" title="'.$app.'"><i class="fa fa-check"></i></a>';
                }
            }
            $status = '<a href="javascript:void(0);" class="badge badge-pill badge-success-soft mr-1">'.$paid.'</a>';
            
            if($record['isDelReq']==0){
                if($this->hasDeleteAccess){
                    $button .='<a href="javascript:void(0);" data-id="'.$record['id'].'" data-date="'.$record['voucher_date'].'" class="btn btn-danger-soft btnC deleteAction custool mr-1" title="'.$req.'"><i class="far fa-paper-plane"></i></a>';
                }
            }else{
                $button .='<a href="javascript:void(0);" data-id="'.$record['id'].'" class="btn btn-purple-soft btnC undoAction custool mr-1" title="'.$undo.'"><i class="fas fa-undo"></i></a>';
                $status .= '<a href="javascript:void(0);" class="badge badge-pill badge-warning-soft text-warning">'.$req.'</a>';
            }

            if($record['attach_file'] !=null){
                $button .='<a href="'.base_url($record['attach_file']).'" class="btn btn-purple-soft btnC custool" title="'.$file.'" data-lity><i class="fas fa-file-image"></i></a>';
            }

            if($record['balance'] >= 0){
                $balance = '<span class="text-success">'.$record['balance'].'</span>';
            }else{
                $balance = '<span class="text-danger">'.$record['balance'].'</span>';
            }

            $data[] = array( 
                'id'           => $record['id'],
                'patient_id'   => $record['patient_id'],
                'file_no'      => $record['file_no'],
                'balance'      => $balance,
                'patient_name' => $record['patient_name'],
                'package_name' => $record['package_name'],
                'created_by'   => $record['created_by'],
                'grand_total'  => $record['grand_total'],
                'doctor_name'  => $record['doctor_name'],
                'receipt'      => getPriceFormat($record['receipt']),
                'vat_percent'  => $record['vat_percent'],
                'vat'          => $record['vat'],
                'voucher_date' => date('d/m/Y', strtotime($record['voucher_date'])),
                'isPaid'       => $status,
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
    | Get receipt voucher
    *--------------------------*/
    public function bdtaskt1m8_03_getDeleteVoucher($postData=null){
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
           $searchQuery = " (vouchers.id like '%".$searchValue."%' OR p.nameE like '%".$searchValue."%' OR p.nameA like '%".$searchValue."%' OR vouchers.delete_ref_id like '%".$searchValue."%'  OR emp1.short_name like '%".$searchValue."%' OR emp1.nameE like '%".$searchValue."%' OR emp1.nameA like '%".$searchValue."%') ";
        }
    
        ## Fetch records
        $builder = $this->db->table('vouchers');
        $builder->select("vouchers.*, p.nameE,  p.nameA, CONCAT_WS(' ', emp.first_name, emp.last_name) as created_by, CONCAT_WS(' ', emp1.first_name, emp1.last_name) as doctor_name, CONCAT_WS(' ', emp2.first_name, emp2.last_name) as requested_by, CONCAT_WS(' ', emp3.first_name, emp3.last_name) as deleted_by");
        $builder->join("patient p", "p.patient_id=vouchers.patient_id", "left");
        $builder->join("hrm_employees emp", "emp.employee_id=vouchers.created_by", "left");
        $builder->join("hrm_employees emp1", "emp1.employee_id=vouchers.doctor_id", "left");
        $builder->join("hrm_employees emp2", "emp2.employee_id=vouchers.del_req_by", "left");
        $builder->join("hrm_employees emp3", "emp3.employee_id=vouchers.del_appr_by", "left");
        if($searchValue != ''){
           $builder->where($searchQuery);
        }
      
        $builder->where("vouchers.vtype", "RV");
        $builder->where("vouchers.status", 0);
        // Total number of record with filtering
        $totalRecordwithFilter = $builder->countAllResults(false);
        $builder->orderBy($columnName, $columnSortOrder);
        $builder->limit($rowperpage, $start);
        $query   =  $builder->get();
        $records =   $query->getResultArray();
        // Total number of record without filtering
        $totalRecords = $builder->countAll();
        if($searchQuery == ''){
           $totalRecords = $totalRecordwithFilter;
        }
        $data = array();
        
        foreach($records as $record ){ 
            $button = '';

            $data[] = array( 
                'id'           => $record['id'],
                'patient_id'   => $record['patient_id'],
                'nameE'        => $record['nameE'],
                'nameA'        => $record['nameA'],
                'created_by'   => $record['created_by'],
                'requested_by' => $record['requested_by'],
                'deleted_by'   => $record['deleted_by'],
                'doctor_name'  => $record['doctor_name'],
                'receipt'      => getPriceFormat($record['receipt']),
                'remarks'      => $record['remarks'],
                'vat'          => $record['vat'],
                'voucher_date' => date('d/m/Y', strtotime($record['voucher_date'])),
                'delete_ref_id'=> '<a href="'.base_url('account/accounts/receiptVDetails/'.$record['id']).'" class="btn btn-success-soft btnC">RV-'.$record['delete_ref_id'].'</a>'
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
    | Get receipt voucher details by Id
    *--------------------------*/
    public function bdtaskt1m8_02_getRVDetailsId($id){
        $result = $this->bdtaskt1m8_03_getRVPntDetails($id);
        $result->details = $this->bdtaskt1m8_04_getRVServDetails($id);
        $result->payment = $this->bdtaskt1m8_05_getRVPayDetails($id);
        return $result;
    }

    /*--------------------------
    | Get receipt voucher patient by Id
    *--------------------------*/
    public function bdtaskt1m8_03_getRVPntDetails($id){
        $result = $this->db->table('vouchers')
                        ->select("vouchers.*,p.nameE, p.nameA, p.mobile, p.age, p.acc_head, p.balance,  CONCAT_WS(' ', emp.first_name,  emp.last_name) as doctor, file.file_no, file.nid_no, file.gender, branch.vat_no, CONCAT_WS(' ', emp1.first_name, emp1.last_name) as created_by, branch.nameE as branch_name")
                        ->join("patient p", "p.patient_id=vouchers.patient_id", "left")
                        ->join("patient_file file", "file.patient_id=p.patient_id", "left")
                        ->join("hrm_employees emp", "emp.employee_id=vouchers.doctor_id", "left")
                        ->join("hrm_employees emp1", "emp1.employee_id=vouchers.created_by", "left")
                        ->join("branch", "branch.id=vouchers.branch_id", "left")
                        ->where('vouchers.id', $id)
                        ->get()
                        ->getRow();
        $result->payDetails = $this->bdtaskt1m8_06_getTotalPayById($id);
        return $result;
    }

    /*--------------------------
    | Get receipt voucher service details by Id
    *--------------------------*/
    public function bdtaskt1m8_04_getRVServDetails($id){
        $result = $this->db->table('voucher_details vd')
                        ->select("vd.*, s.code_no, s.nameE, s.nameA")
                        ->join("services s", "s.id=vd.service_id", "left")
                        ->where('vd.voucher_id', $id)
                        ->get()
                        ->getResult();
        return $result;
    }

    /*--------------------------
    | Get receipt voucher service details by Id
    *--------------------------*/
    public function bdtaskt1m8_05_getRVPayDetails($id){
        $result = $this->db->table('voucher_payment_method vpm')
                        ->select("vpm.*, ld.nameE, ld.nameA")
                        ->join("list_data ld", "ld.id=vpm.pay_method_id", "left")
                        ->where('vpm.voucher_id', $id)
                        ->get()
                        ->getResult();
        return $result;
    }

    /*--------------------------
    | Get total payment by receipt Id
    *--------------------------*/
    public function bdtaskt1m8_06_getTotalPayById($id){
        $result = $this->db->table('payment_vouchers')
                        ->select("SUM(payment) as TotalPayment, SUM(vat) as TotalVat")
                        ->where('ref_voucher', $id)
                        ->where('vtype', 'PV')
                        ->get()
                        ->getRowArray();
        return $result;
    }

    /*--------------------------
    | search credit invoice
    *--------------------------*/
    public function bdtaskt1m8_07_getCreditInvoices($text, $patient_id)
    { 
      if(!empty($text) && !empty($patient_id)){
        return $this->db->table('service_invoices si')
                    ->select("si.id as id, CONCAT(si.id, ' - ', date_format(si.invoice_date, '%d/%m/%Y'), ' Credit Value - ', SUM(sid.creditAmt))  as text")
                    ->join("service_invoice_details sid", "sid.invoice_id=si.id", "left")
                    ->where('si.patient_id', $patient_id)
                    ->where('si.isCreditPaid', 0)
                    ->where('si.isCredit', 1)
                    ->like('si.id', $text)
                    ->get()->getResult();
      }else{
        return false;
      }
    }

    /*--------------------------
    | Get unpaid services by App ID
    *--------------------------*/
    public function bdtaskt1m8_08_getCreditInvoiceById($id){
        $result = $this->db->table('service_invoices')
                        ->select("*")
                        ->where('id', $id)
                        ->get()
                        ->getRow();
        $result->details = $this->db->table('service_invoice_details sid')
                        ->select("sid.*, s.code_no, s.nameE, s.nameA")
                        ->join("services s", "s.id=sid.app_service_id", "left")
                        ->where('sid.invoice_id', $id)
                        ->get()
                        ->getResult();
        return $result;
    }

    /*--------------------------
    | Get delete amount
    *--------------------------*/
    public function bdtaskt1m8_09_getDeleteBalance($id){
        $result = $this->db->table('voucher_payment_method')
                    ->select("SUM(amount) as amount")
                    ->where('voucher_id', $id)
                    ->get()
                    ->getRow();
        if(!empty($result)){
            return $result;
        }else{
            return false;
        }
    }

    /*--------------------------
    | Get receipt voucher details for modify
    *--------------------------*/
    public function bdtaskt1m8_10_getRvData($id){
        $result = $this->db->table('vouchers')
                        ->select("*")
                        ->where('id', $id)
                        ->get()
                        ->getRow();
        $result->payments = $this->db->table('voucher_payment_method pay')
                        ->select("pay.*, ld.nameE, ld.nameA")
                        ->join("list_data ld", "ld.id=pay.pay_method_id ", "left")
                        ->where('pay.voucher_id', $id)
                        ->get()
                        ->getResult();
        return $result;
    }

    /*--------------------------
    | Delete payment methon
    *--------------------------*/
    public function bdtaskt1m8_11_deleteReceiptPayMethod($id, $methods=[]){
        return $this->db->table('voucher_payment_method')->where('voucher_id ', $id)->whereIn('pay_method_id', $methods)->delete();
    }

    /*--------------------------
    | Delete payment methon
    *--------------------------*/
    public function bdtaskt1m8_12_deleteTransMethod($id, $methods=[]){
        return $this->db->table('acc_transaction')->where('VNo', $id)->whereIn('COAID', $methods)->delete();

    }

    /*--------------------------
    | Get total payment by receipt Id
    *--------------------------*/
    public function bdtaskt1m8_13_getPackageByPid($pid){ 
        $result= $this->db->table('appoint_services aps')
                    ->select("CONCAT(aps.appoint_id,'_',aps.package_id) as id, CONCAT_WS(' ', op.id, '-', op.nameE) as text")
                    ->join("offer_packages op", "op.id=aps.package_id", "left")
                    ->where('aps.branch_id', session('branchId'))
                    ->where('aps.patient_id', $pid)
                    ->where('aps.voucher_id', 0)
                    ->where('aps.isPaid', 0)
                    ->whereNotIn('aps.package_id', [0])
                    ->groupBy('aps.appoint_id')
                    ->groupBy('aps.package_id')
                    ->get()
                    ->getResult();
        return $result;
    }
}
?>