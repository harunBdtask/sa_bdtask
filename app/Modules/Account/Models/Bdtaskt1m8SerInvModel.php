<?php namespace App\Modules\Account\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;
class Bdtaskt1m8SerInvModel extends Model
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
        $this->hasDeleteAccess = $this->permission->method('service_invoices', 'delete')->access();
        $this->hasUpdateAccess = $this->permission->method('service_invoices', 'update')->access();
        $this->hasReadAccess = $this->permission->method('service_invoices', 'read')->access();
    }

    public function bdtaskt1m8_01_getAll($postData=null){
         $response = array();
         ## Read value
        @$paid = $postData['paid'];
        @$search_date = $postData['search_date'];
        @$branch_id = $postData['branch_id'];
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
           $searchQuery = " (patient.nameE like '%".$searchValue."%' OR patient.nameA like '%".$searchValue."%' OR si.invoice_date like '%".$searchValue."%' OR user.username like '%".$searchValue."%') ";
        }
        if($paid != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
            if($paid==3){
                $searchQuery .= "(si.isDelReq = '1' )";
            }else{
                $searchQuery .= "(si.isPaid = '".$paid."' ) ";
            }
        }

        if($search_date != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
            $searchQuery .= "(si.invoice_date = '".$search_date."' ) ";
        }

        if($branch_id >0){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
            $searchQuery .= "(si.branch_id = '".$branch_id."' ) ";
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
            $searchQuery .= "(si.id = '".$invoice_id."' ) ";
        }
       
        ## Fetch records
        $builder = $this->db->table('service_invoices si');
        $builder->select("si.*, file.file_no, patient.acc_head,  patient.$this->langColumn as patient_name, CONCAT_WS(' ', emp.first_name, '-', emp.last_name) as doctor_name, user.username, sid.creditedOn, branch.$this->langColumn as branch_name");
        
        $builder->join('service_invoice_details sid', 'sid.invoice_id = si.id','left');
        $builder->join('patient', 'patient.patient_id = si.patient_id','left');
        $builder->join('patient_file file', 'file.patient_id = si.patient_id','left');
        $builder->join('user', 'user.emp_id = si.created_by','left');
        $builder->join('hrm_employees emp', 'emp.employee_id = si.doctor_id','left');
        $builder->join('branch', 'branch.id = si.branch_id','left');
        if($searchQuery != ''){
           $builder->where($searchQuery);
        }
        if(session('branchId') > 0){
            $builder->where('si.branch_id', session('branchId'));
        }
        $builder->where('si.status', 1);
        // Total number of record with filtering
        $totalRecordwithFilter = $builder->countAllResults(false);
        $builder->groupBy('sid.invoice_id');
        $builder->orderBy($columnName, $columnSortOrder);
        $builder->limit($rowperpage, $start);
        $query3   =  $builder->get();
        $records =   $query3->getResultArray();
        // Total number of records without filtering
        $totalRecords = $builder->countAll();
        if($searchQuery == ''){
           $totalRecords = $totalRecordwithFilter ;
        }
        $data = array();

        $paid = get_phrases(['paid']);
        $view = get_phrases(['view']);
        $edit = get_phrases(['edit']);
        $app = get_phrases(['approved', 'delete', 'request']);
        $undo = get_phrases(['undo', 'request']);
        $unpaid = get_phrases(['unpaid']);
        $credited = get_phrases(['credited']);
        $doctor = get_phrases(['credit', 'by', 'doctor']);
        $patient = get_phrases(['credit', 'by', 'patient']);
        $cash = get_phrases(['cash', 'or', 'card']);
        $req = get_phrases(['delete', 'requested']);
        $file = get_phrases(['file', 'preview']);
        foreach($records as $record ){ 
            $button = '';
            $status = '';
            if($record['isPaid']==1){
                $status .= '<a href="javascript:void(0);" class="badge badge-pill badge-success-soft mr-1">'.$paid.'</a>';
            }elseif($record['isPaid']==2){
                $status .= '<a href="javascript:void(0);" class="badge badge-pill badge-primary-soft mr-1">'.$credited.'</a>';
                if($record['isCreditPaid']==1){
                    $status .= '<a href="javascript:void(0);" class="badge badge-pill badge-success-soft mr-1">'.$paid.'</a>';
                }else{
                    $status .= '<a href="javascript:void(0);" class="badge badge-pill badge-danger-soft">'.$unpaid.'</a>';
                }
            }else{
                $status .= '<a href="javascript:void(0);" class="badge badge-pill badge-danger-soft">'.$unpaid.'</a>';
            }
            if($this->hasReadAccess){
                $button .='<a href="'.base_url('account/services/detailsInvoice/'.$record['id']).'" class="btn btn-success-soft btnC actionPreview mr-1 custool" title="'.$view.'"><i class="far fa-eye"></i></a>';
            }
            if($this->hasUpdateAccess){
                $button .='<a href="javascript:void(0);" data-id="'.$record['id'].'" class="btn btn-primary-soft btnC mr-1 editAction custool" title="'.$edit.'"><i class="far fa-edit"></i></a>';
            }
            
            if($record['isDelReq']==0){
                if($this->hasUpdateAccess){
                    $button .='<a href="javascript:void(0);" data-id="'.$record['id'].'" data-date="'.$record['invoice_date'].'" class="btn btn-danger-soft btnC deleteAction custool mr-1" title="'.$req.'"><i class="far fa-paper-plane"></i></a>';
                }
            }else{
                 if($this->hasDeleteAccess){
                    $button .='<a href="javascript:void(0);" data-id="'.$record['id'].'" data-date="'.date('Y-m-d', strtotime($record['del_req_date'])).'" data-p="'.$record['acc_head'].'" class="btn btn-success-soft btnC approvedAction mr-1 custool" title="'.$app.'"><i class="fa fa-check"></i></a>';
                }
                $button .='<a href="javascript:void(0);" data-id="'.$record['id'].'" class="btn btn-purple-soft btnC undoAction custool mr-1" title="'.$undo.'"><i class="fas fa-undo"></i></a>';
                $status .= '<a href="javascript:void(0);" class="badge badge-pill badge-warning-soft text-warning">'.$req.'</a>';
            }

            if($record['creditedOn']==120){
                $credited1 = '<span class="text-success">'.$cash.'</span>';
            }else if($record['creditedOn']==130){
                $credited1 = '<span class="text-warning">'.$doctor.'</span>';
            }else{
                $credited1 = '<span class="text-danger">'.$patient.'</span>';
            }

            if($record['attach_file'] !=null){
                $button .='<a href="'.base_url($record['attach_file']).'" class="btn btn-info-soft btnC custool" title="'.$file.'" data-lity><i class="fas fa-file-image"></i></a>';
            }

            $data[] = array( 
                'id'           => $record['id'],
                'patient_name' => $record['patient_name'],
                'file_no'      => $record['file_no'],
                'doctor_name'  => $record['doctor_name'],
                'branch_name'  => $record['branch_name'],
                'username'     => $record['username'],
                'payment_by'   => $credited1,
                'grand_total'  => $record['grand_total'],
                'invoice_date' => date('d/m/Y', strtotime($record['invoice_date'])),
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
    | Get unpaid services by App ID
    *--------------------------*/
    public function bdtaskt1m8_02_getServByAppId($orderId){
        $result = $this->db->table('appoint_services apps')
                        ->select("apps.payment_by, apps.created_by, CONCAT_WS(' ', emp.first_name, emp.last_name) as doctor_name")
                        ->join("hrm_employees emp", "emp.employee_id=apps.created_by", "left")
                        ->where('apps.order_id', $orderId)
                        ->get()
                        ->getRow();
        $result->services = $this->db->table('appoint_services aps')
                    ->select("aps.*, s.code_no, s.nameE, s.nameA, IF(apso.need_approval=1 AND apso.isApproved=1, offers.discount, 0) as discount, IF(offers.doctor_commission=1, 1, 0) as doctor_commission, s.isPoint, CONCAT(s.min_spend_amount,'_',s.redeem_per_point_value,'_',s.min_redeem_point,'_',s.max_redeem_point,'_',s.min_gain_point,'_',s.max_gain_point) as point_info")
                    ->join("services s", "s.id=aps.service_id", "left")
                    ->join("appoint_service_offers apso", "apso.assign_id=aps.id", "left")
                    ->join("offers", "offers.id=apso.offer_id", "left")
                    ->where('aps.order_id', $orderId)
                    ->where('aps.isPaid', 0)
                    ->whereNotIn('aps.status', [3])
                    ->whereIn('aps.package_id', [0])
                    ->groupStart()
                        ->where('aps.isOffer', 0)
                        ->orGroupStart()
                            ->groupStart()
                                ->where('aps.isOffer', 1)
                                ->where('apso.need_approval', 0)
                            ->groupEnd()
                            ->orGroupStart()
                                ->where('aps.isOffer', 1)
                                ->where('apso.need_approval', 1)
                                ->where('apso.isApproved', 1)
                            ->groupEnd()
                        ->groupEnd()
                    ->groupEnd()
                    ->get()
                    ->getResult();
        return $result;
    }

    /*--------------------------
    | Get invoice details by Id 
    *--------------------------*/
    public function bdtaskt1m8_03_getInvDetailsId($id){
        $result = $this->db->table('service_invoices inv')
                        ->select("inv.*, patient.$this->langColumn as patient_name, patient.mobile, patient.age, file.email, file.gender, file.file_no, file.nid_no, branch.vat_no, CONCAT_WS(' ', emp.first_name, '-', emp.last_name) as createdBy, CONCAT_WS(' ', emp1.first_name, '-', emp1.last_name) as doctor, branch.$this->langColumn as branch_name")
                        ->join("patient", "patient.patient_id=inv.patient_id", "left")
                        ->join("patient_file file", "file.patient_id=patient.patient_id", "left")
                        ->join("hrm_employees emp", "emp.employee_id=inv.created_by", "left")
                        ->join("hrm_employees emp1", "emp1.employee_id=inv.doctor_id", "left")
                        ->join("branch", "branch.id=inv.branch_id", "left")
                        ->where('inv.id', $id)
                        ->groupBy('inv.id')
                        ->get()
                        ->getRow();
        $result->items = $this->db->table('service_invoice_details sid')
                        ->select("sid.*, s.code_no, s.nameE, s.nameA")
                        ->join("services s", "s.id=sid.app_service_id", "left")
                        ->where('sid.invoice_id', $id)
                        ->get()
                        ->getResult();
        $result->payments = $this->db->table('service_invoice_payment pay')
                        ->select("pay.*, ld.nameE, ld.nameA")
                        ->join("list_data ld", "ld.id=pay.payment_name", "left")
                        ->where('pay.invoice_id', $id)
                        ->get()
                        ->getResult();
        return $result;
    }

    /*--------------------------
    | Get unpaid services by patilent ID
    *--------------------------*/
    public function bdtaskt1m8_04_getServByPId($pid){
        $result = $this->db->table('patient')
                        ->select("patient.patient_id, patient.nameE, patient.nameA, patient.acc_head, acc.balance, pf.file_no, pf.nationality, pf.nid_no, patient.total_points")
                        ->join("patient_file pf", "pf.patient_id=patient.patient_id", "left")
                        ->join("acc_coa_balance acc", "acc.headCode=patient.acc_head AND acc.branch_id=".session('branchId')."", "left")
                        ->where('patient.patient_id', $pid)
                        ->get()
                        ->getRow();
        $result->orders = $this->db->table('appoint_services aps')
                    ->select("aps.order_id as id, CONCAT_WS(' ', aps.order_id, 'Order By', emp.first_name as short_name, emp.last_name, '-', date_format(aps.order_date, '%b %d, %Y')) as text")
                    ->join("hrm_employees emp", "emp.employee_id=aps.created_by", "left")
                    ->where('aps.branch_id', session('branchId'))
                    ->where('aps.patient_id', $pid)
                    ->where('aps.isPaid', 0)
                    ->whereIn('aps.package_id', [0])
                    ->whereIn('aps.status', [0, 1, 2])
                    ->groupBy('aps.order_id')
                    ->get()
                    ->getResult();
        return $result;
    }

    // public function bdtaskt1m8_04_getServByPId($pid){
    //     $result = $this->db->table('patient')
    //                     ->select("patient.patient_id, patient.nameE, patient.nameA, patient.acc_head, patient.balance, pf.file_no, pf.nationality, pf.nid_no, patient.special_doctor, CONCAT_WS(' ', emp.short_name, emp.nameE) as doctor_name")
    //                     ->join("patient_file pf", "pf.patient_id=patient.patient_id", "left")
    //                     ->join("employees emp", "emp.emp_id=patient.special_doctor", "left")
    //                     ->where('patient.patient_id', $pid)
    //                     ->get()
    //                     ->getRow();
    //     $result->services = $this->db->table('appoint_services aps')
    //                     ->select("aps.*, s.code_no, s.nameE, s.nameA, offers.discount, opd.discount as packDis, offers.isApproved")
    //                     ->join("services s", "s.id=aps.service_id", "left")
    //                     ->join("appoint_service_offers apso", "apso.assign_id=aps.id", "left")
    //                     ->join("offers", "offers.id=apso.offer_id", "left")
    //                     ->join("offer_package_details opd", "opd.offer_package_id=aps.package_id AND opd.service_id=aps.service_id", "left")
    //                     ->where('aps.patient_id', $pid)
    //                     ->where('aps.isPaid', 0)
    //                     ->get()
    //                     ->getResult();
    //     return $result;
    // }

    public function bdtaskt1m8_05_getAllDeletes($postData=null){
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
           $searchQuery = "(si.id like '%".$searchValue."%' OR patient.nameE like '%".$searchValue."%' OR patient.nameA like '%".$searchValue."%' OR file.file_no like '%".$searchValue."%' OR si.invoice_date like '%".$searchValue."%') ";
        }
       
        ## Fetch records
        $builder3 = $this->db->table('service_invoices si');
        $builder3->select("si.*, CONCAT_WS(' ', file.file_no, '-', patient.nameE) as patient_name, user.username as created_by, sid.creditedOn, u.username as requested_user, u2.username as approved_user, branch.$this->langColumn as branch_name");
        $builder3->join('service_invoice_details sid', 'sid.invoice_id = si.id','left');
        $builder3->join('patient', 'patient.patient_id = si.patient_id','left');
        $builder3->join('patient_file file', 'file.patient_id = si.patient_id','left');
        $builder3->join('user', 'user.emp_id = si.created_by','left');
        $builder3->join('user u', 'u.emp_id = si.del_req_by','left');
        $builder3->join('user u2', 'u2.emp_id = si.del_appr_by','left');
        $builder3->join('branch', 'branch.id = si.branch_id','left');
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }
        if(session('branchId') > 0){
            $builder3->where('si.branch_id', session('branchId'));
        }
        $builder3->where('si.status', 0);
        // Total number of record with filtering
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->groupBy('sid.invoice_id');
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3  =  $builder3->get();
        $records =   $query3->getResultArray();
         ## Total number of records without filtering
        $totalRecords = $builder3->countAll();
        if($searchQuery == ''){
           $totalRecords = $totalRecordwithFilter;
        }
        $data    = array();
        
        $doctor = get_phrases(['credit', 'by', 'doctor']);
        $patient = get_phrases(['credit', 'by', 'patient']);
        $cash = get_phrases(['cash', 'or', 'card']);
        foreach($records as $record ){ 
            $button = '';

            $button .='<a href="'.base_url('account/services/detailsInvoice/'.$record['id']).'" class="btn btn-success-soft btnC actionPreview mr-1"><i class="far fa-eye"></i></a>';
            if($record['creditedOn']==120){
                $credited = '<span class="text-success">'.$cash.'</span>';
            }else if($record['creditedOn']==130){
                $credited = '<span class="text-warning">'.$doctor.'</span>';
            }else{
                $credited = '<span class="text-danger">'.$patient.'</span>';
            }

            $data[] = array( 
                'id'           => $record['id'],
                'patient_name' => $record['patient_name'],
                'branch_name'  => $record['branch_name'],
                'created_by'   => $record['created_by'],
                'grand_total'  => $record['grand_total'],
                'delete_reason'=> $record['delete_reason'],
                'requested_user'=> $record['requested_user'],
                'approved_user'=> $record['approved_user'],
                'payment_by'   => $credited,
                'invoice_date' => date('d/m/Y', strtotime($record['invoice_date'])),
                'ref_no'       => !empty($record['ref_no'])?'<a href="'.base_url('account/services/detailsInvoice/'.$record['ref_no']).'" class="btn btn-sm btn-success-soft btnC">SINV-'.$record['ref_no'].'</a>':'--',
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
    | Get unpaid packages by patilent ID
    *--------------------------*/
    public function bdtaskt1m8_06_getPacksByPId($pid){
        $result= $this->db->table('appoint_services aps')
                    ->select("CONCAT(aps.appoint_id,'_',aps.package_id) as id, CONCAT_WS(' ', op.id, '-', op.nameE) as text")
                    ->join("offer_packages op", "op.id=aps.package_id", "left")
                    ->where('aps.branch_id', session('branchId'))
                    ->where('aps.patient_id', $pid)
                    ->where('aps.isPaid', 0)
                    ->whereNotIn('aps.package_id', [0])
                    ->groupBy('aps.appoint_id')
                    ->groupBy('aps.package_id')
                    ->get()
                    ->getResult();
        return $result;
    }

    /*--------------------------
    | Get services by pack ID
    *--------------------------*/
    public function bdtaskt1m8_07_getServicesByPackId($pack, $pid, $appoint_id=null){
        $query = $this->db->table('appoint_packages app');
        $query->select("appointment.doctor_id, CONCAT_WS(' ', emp.first_name, emp.last_name) as doctor_name");
        $query->join("appointment", "appointment.appoint_id=app.appoint_id", "left");
        $query->join("hrm_employees emp", "emp.employee_id=appointment.doctor_id", "left");
        $query->where('app.patient_id', $pid);
        $query->where('app.package_id', $pack);
        if(!empty($appoint_id)){
            $query->where('app.appoint_id', $appoint_id);
        }
        $result = $query->get()->getRow();

        $result->advance = array();
        $result->advance = $this->db->table('vouchers')
                    ->select("remaining_balance")
                    ->where('patient_id', $pid)
                    ->where('package_id', $pack)
                    ->get()
                    ->getRow();
        $result->services = $this->db->table('appoint_services aps')
                    ->select("aps.*, s.code_no, s.nameE, s.nameA, opd.discount as packDis")
                    ->join("services s", "s.id=aps.service_id", "left")
                    ->join("offer_package_details opd", "opd.offer_package_id=aps.package_id AND opd.service_id=aps.service_id", "left")
                    ->where('aps.patient_id', $pid)
                    ->where('aps.appoint_id', $appoint_id)
                    ->where('aps.package_id', $pack)
                    ->where('aps.isPaid', 0)
                    ->whereNotIn('aps.package_id', [0])
                    ->get()
                    ->getResult();
        return $result;
    }

    /*--------------------------
    | Get services by doctor ID
    *--------------------------*/
    public function bdtaskt1m8_08_getServicesByDocId($docId, $pid){
        $result = $this->db->table('appoint_services aps')
                    ->select("aps.*, s.code_no, s.nameE, s.nameA, offers.discount, offers.isApproved")
                    ->join("services s", "s.id=aps.service_id", "left")
                    ->join("appoint_service_offers apso", "apso.assign_id=aps.id", "left")
                    ->join("offers", "offers.id=apso.offer_id", "left")
                    ->where('aps.created_by', $docId)
                    ->where('aps.patient_id', $pid)
                    ->where('aps.isPaid', 0)
                    ->whereIn('aps.package_id', [0])
                    ->get()
                    ->getResult();
        return $result;
    }

    /*--------------------------
    | Get delete invoice amount 
    *--------------------------*/
    public function bdtaskt1m8_09_getDelInvCredit($id){
        $result = $this->db->table('service_invoice_payment')
                    ->select("SUM(amount) as amount")
                    ->where('invoice_id', $id)
                    ->whereIn('payment_name', [127, 130, 150])
                    ->get()
                    ->getRow();
        if(!empty($result)){
            return $result;
        }else{
            return false;
        }
    }

    /*--------------------------
    | Get invoice consumption info 
    *--------------------------*/
    public function bdtaskt1m8_10_getInvoiceConsumption($id){
        $result = $this->db->table('wh_order')
                    ->select("*")
                    ->where('invoice_id', $id)
                    ->where('status', 1)
                    ->where('isApproved', 1)
                    ->where('isReturned', 0)
                    ->get()
                    ->getRow();

        if(!empty($result)){
            return $result;
        }else{
            return false;
        }
    }

    /*--------------------------
    | Get services by doctor ID
    *--------------------------*/
    public function bdtaskt1m8_10_invoiceInfoByServiceOrder($id){
        $result = $this->db->table('appoint_services aps')
                    ->select("aps.appoint_id, aps.service_id, aps.package_id, aps.order_id, IF(aps.package_id=0, 1, 2) as type, aps.patient_id, CONCAT_WS
                        (' ', file.file_no, '-', p.nameE) as patient_name")
                    ->join("patient p", "p.patient_id=aps.patient_id", "left")
                    ->join("patient_file file", "file.patient_id=p.patient_id", "left")
                    ->where('aps.branch_id', session('branchId'))
                    ->where('aps.id', $id)
                    ->get()
                    ->getRow();
        return $result;
    }

    /*--------------------------
    | Get invoice details for modify
    *--------------------------*/
    public function bdtaskt1m8_11_getInvDetailsForModify($id){
        $result = $this->db->table('service_invoices')
                        ->select("*")
                        ->where('id', $id)
                        ->get()
                        ->getRow();
        $result->payments = $this->db->table('service_invoice_payment pay')
                        ->select("pay.*, ld.nameE, ld.nameA")
                        ->join("list_data ld", "ld.id=pay.payment_name", "left")
                        ->where('pay.invoice_id', $id)
                        ->get()
                        ->getResult();
        return $result;
    }

    /*--------------------------
    | Delete payment methon
    *--------------------------*/
    public function bdtaskt1m8_12_deleteInvPayMethod($id, $methods=[]){
        return $this->db->table('service_invoice_payment')->where('invoice_id ', $id)->whereIn('payment_name', $methods)->delete();
    }

    /*--------------------------
    | Delete payment methon 
    *--------------------------*/
    public function bdtaskt1m8_13_deleteTransMethod($id, $methods=[]){
        return $this->db->table('acc_transaction')->where('VNo', $id)->whereIn('COAID', $methods)->delete();

    }

    /*--------------------------
    | Delete payment methon 
    *--------------------------*/
    public function bdtaskt1m8_14_getInvoiceConsumption($id, $approved, $service_id=null){
        $invoice_id = explode('-', $id);
        $query = $this->db->table('wh_order cons');
        $query->select("cons.*, hrm_departments.name as department_name, whw.$this->langColumn as sub_store_name, e.first_name as request_by_name, CONCAT_WS(' ', f.first_name, f.last_name) as doctor_name, p.file_no");
        $query->join('hrm_departments', 'hrm_departments.id=cons.from_department_id','left');
        $query->join('wh_sale_store whw', 'whw.id=cons.sub_store_id','left');
        $query->join('hrm_employees e', 'e.employee_id=cons.request_by','left');
        $query->join('hrm_employees f', 'f.employee_id=cons.doctor_id','left');
        $query->join('patient_file p', 'p.patient_id=cons.patient_id','left');
        if(!empty($approved)){
            $query->where('cons.isApproved', $approved);
            $query->where('cons.isCollected', 1);
        }
        if(!empty($service_id)){
            $query->where('cons.service_id', $service_id);
        }
        $query->whereIn('cons.invoice_id', $invoice_id);
        $query->orderBy('cons.date', 'DESC');
        return $query->get()->getResult();
    }

    /*--------------------------
    | Get patient available points
    *--------------------------*/
    public function bdtaskt1m8_14_getPatientPoints($patient_id, $date){
        $query = $this->db->table('service_invoice_details sid');
        $query->select('SUM(remaining_points) as gain_points');
        $query->join('service_invoices si', 'si.id=sid.invoice_id');
        if(session('branchId') > 0){
            $query->where('si.branch_id', session('branchId'));
        }
        $query->where('si.patient_id', $patient_id);
        $query->where('si.invoice_date >=', $date);
        $result = $query->get()->getRow();
        return $result;
    }

    /*--------------------------
    | Get patient available points info
    *--------------------------*/
    public function bdtaskt1m8_15_getPatientAvailablePoints($patient_id, $date){
        $query = $this->db->table('service_invoice_details sid');
        $query->select('sid.id, sid.remaining_points');
        $query->join('service_invoices si', 'si.id=sid.invoice_id');
        if(session('branchId') > 0){
            $query->where('si.branch_id', session('branchId'));
        }
        $query->where('si.patient_id', $patient_id);
        $query->where('si.invoice_date >=', $date);
        $result = $query->get()->getResult();
        return $result;
    }

    /*--------------------------
    | Get patient available points 
    *--------------------------*/
    public function bdtaskt1m8_16_updateInvService($where, $paid, $invoiceId, $remain_qty, $invQty)
    {
       return $this->db->table('appoint_services')->set("isPaid", $paid, FALSE)
                       ->set("invoiceID", "IF(invoiceID IS NULL, '$invoiceId', CONCAT(invoiceID, ',', '$invoiceId'))", FALSE)
                       ->set("remain_qty", $remain_qty, FALSE)
                       ->set("invoice_qty", "invoice_qty+".$invQty, FALSE)
                       ->where($where)->update();
    }

}
?>