<?php namespace App\Modules\Reports\Controllers;
use CodeIgniter\Controller;
use App\Modules\Reports\Models\Bdtaskt1m17ManagementModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf;
class Bdtaskt1m17c3Managements extends BaseController
{
    private $bdtaskt1m17c3_01_mntModel;
    private $bdtaskt1m17c3_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bdtaskt1m17c3_01_mntModel = new Bdtaskt1m17ManagementModel();
        $this->bdtaskt1m17c3_02_CmModel = new Bdtaskt1m1CommonModel();
        if(session('defaultLang')=='english'){
            $this->langColumn = 'nameE';
        }else{
            $this->langColumn = 'nameA';
        }
        $this->permission = new Permission();
    }

    /*--------------------------
    | user income reports form
    *--------------------------*/
    public function bdtaskt1m17c3_01_userIncomes()
    {

        $data['title']      = get_phrases(['cashier', 'income']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "management/user_incomes";
        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get user incomes
    *--------------------------*/
    public function bdtaskt1m17c3_02_getIncomeResult()
    { 
        $userId = $this->request->getVar('user_id');
        $doctor_id = $this->request->getVar('doctor_id');
        $payM   = $this->request->getVar('payment_method');
        $vType  = $this->request->getVar('vtype');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = date('Y-m-d H:i:s', strtotime(trim($date[0])));
        $to     = date('Y-m-d H:i:s', strtotime(trim($date[1])));
        $data['setting']    = $this->bdtaskt1m17c3_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c3_01_mntModel->bdtaskt1m17_01_getUserIncomes11($userId, $payM, $vType, $from, $to, $doctor_id);
        $data['hasPrintAccess']   = $this->permission->method('cashier_income_report', 'print')->access();
        $data['hasExportAccess']  = $this->permission->method('cashier_income_report', 'export')->access();

        $income = view('App\Modules\Reports\Views\management\user_income_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | Services revenue reports form
    *--------------------------*/
    public function bdtaskt1m17c3_03_servicesRevenue()
    {

        $data['title']      = get_phrases(['doctor', 'revenue']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "management/services_revenue";
        return $this->base17_01_template->layout($data);
    }

     /*--------------------------
    | Get services revenue
    *--------------------------*/
    public function bdtaskt1m17c3_04_getSRevenue()
    { 
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = date('Y-m-d', strtotime(trim($date[0])));
        $to     = date('Y-m-d', strtotime(trim($date[1])));
        $data['setting']    = $this->bdtaskt1m17c3_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c3_01_mntModel->bdtaskt1m17_02_getServicesRevenue($from, $to);
        $data['hasPrintAccess']   = $this->permission->method('doctor_revenue_report', 'print')->access();
        $data['hasExportAccess']  = $this->permission->method('doctor_revenue_report', 'export')->access();
        // echo "<pre>";
        // print_r($data['results']);exit();
        $revenue = view('App\Modules\Reports\Views\management\services_revenue_reports', $data);
        echo json_encode(array('data'=>$revenue));
    }

    /*--------------------------
    | Doctor commission form
    *--------------------------*/
    public function bdtaskt1m17c3_09_doctorCommission()
    {
        $data['title']      = get_phrases(['doctor', 'commission']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "management/doc_commission_form";
        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get Doctor commission 
    *--------------------------*/
    public function bdtaskt1m17c3_10_getDoctorCom()
    { 
        $range      = $this->request->getVar('date_range');
        $doctor_id  = $this->request->getVar('doctor_id');
        $service_id = $this->request->getVar('service_id');
        $date       = explode('-', $range);
        $from       = date('Y-m-d', strtotime(trim($date[0])));
        $to         = date('Y-m-d', strtotime(trim($date[1])));
        $data['setting']    = $this->bdtaskt1m17c3_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c3_01_mntModel->bdtaskt1m17_06_getDoctorCom($doctor_id, $service_id, $from, $to);
        $data['hasPrintAccess']   = $this->permission->method('doctor_com_report', 'print')->access();
        $data['hasExportAccess']  = $this->permission->method('doctor_com_report', 'export')->access();
        // echo "<pre>";
        // print_r($data['results']);die();
        $statement = view('App\Modules\Reports\Views\management\doc_commission_reports', $data);
        echo json_encode(array('data'=>$statement));
    }

    /*--------------------------
    | Payment Method List
    *--------------------------*/
    public function bdtaskt1m17c3_11_paymentMethod()
    { 
        $data = $this->bdtaskt1m17c3_01_mntModel->bdtaskt1m17_07_getPaymentMethod();
        echo json_encode($data);
    }

    /*--------------------------
    | Get voucher details 
    *--------------------------*/
    public function bdtaskt1m17c3_12_getVoucherdetails()
    { 
        $voucher  = $this->request->getVar('voucherId');
        $info     = explode('-', $voucher);
        $type     = $info[0];
        $vId      = $info[1];
        $data['results']   = $this->bdtaskt1m17c3_01_mntModel->bdtaskt1m17_08_getVoucherData($vId, $type);

        if($type=='SINV'){
            $details = view('App\Modules\Reports\Views\management\service_voucher_details', $data);
        }else if($type=='RV'){
            $details = view('App\Modules\Reports\Views\management\receipt_voucher_details', $data);
        }else if($type=='PV' || $type=='RFV'){
            $details = view('App\Modules\Reports\Views\management\refund_voucher_details', $data);
        }else if($type=='DV' || $type=='CV' || $type=='JV' || $type=='CONTA' || $type=='OPBL'){
            $details = view('App\Modules\Reports\Views\management\DCJC_voucher_details', $data);
        }else if($type=='SUPI'){
             $details = view('App\Modules\Reports\Views\management\income_voucher_details', $data);
        }else if($type=='GRECI' || $type=='GRETI'){
            $details = view('App\Modules\Reports\Views\management\good_rr_voucher_details', $data);
        }else if($type=='SUPI'){
            $details = view('App\Modules\Reports\Views\management\supplier_payment_voucher_details', $data);
        }else if($type=='CONS'){
            $details = '';
        }
        // echo "<pre>";
        // print_r($data['results']);die();
        echo json_encode(array('data'=>$details));
    }

    /*--------------------------
    | Search services info
    *--------------------------*/
    public function bdtaskt1m17c3_13_searchServices()
    { 
        $text = $this->request->getVar('term');
        $data = $this->bdtaskt1m17c3_01_mntModel->bdtaskt1m17_09_searchServices($text, $this->langColumn);
        echo json_encode($data);
    }

    /*--------------------------
    | Income Calculator form 
    *--------------------------*/
    public function bdtaskt1m17c3_14_incomeCalculator()
    {
        $data['title']      = get_phrases(['income', 'calculator']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "management/income_calculator_form";
        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get Income Calculator
    *--------------------------*/
    public function bdtaskt1m17c3_15_getIncCalculator()
    { 
        $range      = $this->request->getVar('date_range');
        $doctor_id  = $this->request->getVar('doctor_id');
        $date       = explode('-', $range);
        $from       = date('Y-m-d H:i:s', strtotime(trim($date[0])));
        $to         = date('Y-m-d H:i:s', strtotime(trim($date[1])));
        $data['setting']    = $this->bdtaskt1m17c3_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c3_01_mntModel->bdtaskt1m17_10_getIncomeCalculator($doctor_id, $from, $to);
        $data['hasPrintAccess']   = $this->permission->method('income_calculator_report', 'print')->access();
        $data['hasExportAccess']  = $this->permission->method('income_calculator_report', 'export')->access();
        // echo "<pre>";
        // print_r($data['results']);die();
        $statement = view('App\Modules\Reports\Views\management\income_calculator_reports', $data);
        echo json_encode(array('data'=>$statement));
    }

    /*--------------------------
    | Get Income Calculator
    *--------------------------*/
    public function bdtaskt1m17c3_16_getReportInvoices()
    { 
        $doctor_id  = $this->request->getVar('doctor_id');
        $type       = $this->request->getVar('type');
        $range      = $this->request->getVar('date_range');
        $date       = explode('-', $range);
        $from       = date('Y-m-d', strtotime(trim($date[0])));
        $to         = date('Y-m-d', strtotime(trim($date[1])));
        $data['results']   = $this->bdtaskt1m17c3_01_mntModel->bdtaskt1m17_11_getReportInvoices($doctor_id, $type, $from, $to);
        $data['data'] = array('doctor_id'=> $doctor_id, 'type'=>$type, 'range'=>$range, 'from'=>$from, 'to'=>$to);
        // echo "<pre>";
        // print_r($data['results']);die();
        $invoices = view('App\Modules\Reports\Views\management\income_calculator_invoices', $data);
        echo json_encode(array('data'=>$invoices));
    }

    /*--------------------------
    | Get Income Calculator
    *--------------------------*/
    public function bdtaskt1m17c3_17_commissionInvoices()
    { 
        $ids  = $this->request->getVar('ids');
        $IDs       = explode('-', $ids);
        $data['results']   = $this->bdtaskt1m17c3_01_mntModel->bdtaskt1m17_12_getCommissionInvoices($IDs);
        // echo "<pre>";
        // print_r($data['results']);die();
        $invoices = view('App\Modules\Reports\Views\management\income_calculator_invoices', $data);
        echo json_encode(array('data'=>$invoices));
    }

    /*-----------------------------*
    | export doctor commission Data
    *------------------------------*/
    public function bdtaskt1m17c3_18_exportExcel() {
        $range      = $this->request->getVar('date_range');
        $doctor_id  = $this->request->getVar('doctor_id');
        $service_id = $this->request->getVar('service_id');
        $date       = explode('-', $range);
        $from       = date('Y-m-d', strtotime(trim($date[0])));
        $to         = date('Y-m-d', strtotime(trim($date[1])));

        $expData = $this->bdtaskt1m17c3_01_mntModel->bdtaskt1m17_06_getDoctorCom($doctor_id, $service_id, $from, $to);
        // create file name
        $fileName = 'Doctors-Commission-Reports-'.$from.'-to-'.$to.'.xlsx';  
        // load excel library
        $spreadsheet = new Spreadsheet();
 
        $sheet = $spreadsheet->getActiveSheet();
        // set Header
        $sheet->SetCellValue('A1', 'Doctor Code');
        $sheet->SetCellValue('B1', 'Service Code');
        $sheet->SetCellValue('C1', 'Service Name');
        $sheet->SetCellValue('D1', 'Quantity');
        $sheet->SetCellValue('E1', 'Total Service Amount');
        $sheet->SetCellValue('F1', 'Allowed Discount'); 
        $sheet->SetCellValue('G1', 'Over Limit Discount');
        $sheet->SetCellValue('H1', 'Offer Discount');
        $sheet->SetCellValue('I1', 'Refun');
        $sheet->SetCellValue('J1', 'Net Revenue');
        $sheet->SetCellValue('K1', 'Total Consumed');
        $sheet->SetCellValue('L1', 'No Commission');    
        $sheet->SetCellValue('M1', 'Fixed Cost');       
        $sheet->SetCellValue('N1', 'Total Deduction');       
        $sheet->SetCellValue('O1', 'Share Income');       
        $sheet->SetCellValue('P1', '%');       
        $sheet->SetCellValue('Q1', 'Share');       
        $sheet->SetCellValue('R1', 'Credit By Doctor');       
        $sheet->SetCellValue('S1', 'Credit By Patient');       

        // set Row 
        $rowCount = 2;
        foreach ($expData as $value) 
        {
            $total = $value->qty_price;
            $normalDis = $value->over_limit_discount + $value->doctor_discount + $value->offer_discount;
            $totalRef = !empty($value->totalPay)?$value->totalPay:0;
            
            $cost = 0;
            if($value->fixed_cost_deduct==1){
              $cost =$value->cost_amount*$value->qty;
            }
            if(!empty($value->commission)){
                $com = $value->commission;
            }else{
                $com = 0;
            }
            
            $totalDeduct  = $value->no_commission_amt + $cost + $value->consumed;
            $netRevenue = $total - ($normalDis + $totalRef);
            $sharedIncome = $netRevenue - $totalDeduct;
            if($com==0){
                $doctorShared = 0;
            }else{
                $doctorShared = ($sharedIncome * $value->commission)/100;
            }
              
            $sheet->SetCellValue('A' . $rowCount, $value->short_name);
            $sheet->SetCellValue('B' . $rowCount, $value->code_no);
            $sheet->SetCellValue('C' . $rowCount, $value->service_name);
            $sheet->SetCellValue('D' . $rowCount, $value->qty);
            $sheet->SetCellValue('E' . $rowCount, $total);
            $sheet->SetCellValue('F' . $rowCount, $value->doctor_discount);
            $sheet->SetCellValue('G' . $rowCount, $value->over_limit_discount);
            $sheet->SetCellValue('H' . $rowCount, $value->offer_discount);
            $sheet->SetCellValue('I' . $rowCount, $totalRef);
            $sheet->SetCellValue('J' . $rowCount, $netRevenue);
            $sheet->SetCellValue('K' . $rowCount, $value->consumed);
            $sheet->SetCellValue('L' . $rowCount, $value->no_commission_amt);
            $sheet->SetCellValue('M' . $rowCount, $cost);
            $sheet->SetCellValue('N' . $rowCount, $totalDeduct);
            $sheet->SetCellValue('O' . $rowCount, $sharedIncome);
            $sheet->SetCellValue('P' . $rowCount, $com);
            $sheet->SetCellValue('Q' . $rowCount, $doctorShared);
            $sheet->SetCellValue('R' . $rowCount, $value->creditedDoctor);
            $sheet->SetCellValue('S' . $rowCount, $value->creditedPatient);
            $rowCount++;
        }
        
        $writer = new Xlsx($spreadsheet);
        // push to the file
        $writer->save("assets/excel_data/".$fileName);
        header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
        //header("Content-Type: application/vnd.ms-excel");
        echo json_encode(array('url'=>base_url().'/assets/excel_data/'.$fileName));
    }

    /*-----------------------------*
    | export income calculator Data
    *------------------------------*/
    public function bdtaskt1m17c3_19_exportInCalculator() {
        $range      = $this->request->getVar('date_range');
        $doctor_id  = $this->request->getVar('doctor_id');
        $date       = explode('-', $range);
        $from       = date('Y-m-d H:i:s', strtotime(trim($date[0])));
        $to         = date('Y-m-d H:i:s', strtotime(trim($date[1])));

        $expData = $this->bdtaskt1m17c3_01_mntModel->bdtaskt1m17_10_getIncomeCalculator($doctor_id, $from, $to);
        // create file name
        $fileName = 'Income-Calculator-Reports-'.$from.'-to-'.$to.'.xlsx';  
        // load excel library
        $spreadsheet = new Spreadsheet();
 
        $sheet = $spreadsheet->getActiveSheet();
        // set Header
        $sheet->SetCellValue('A1', 'Doctor Name');
        $sheet->SetCellValue('B1', 'Credit Sales');
        $sheet->SetCellValue('C1', 'Paid Advance');
        $sheet->SetCellValue('D1', 'Bank Transfer');
        $sheet->SetCellValue('E1', 'Service Without Comm.'); 
        $sheet->SetCellValue('F1', 'Over Limit Discount');
        $sheet->SetCellValue('G1', 'Normal Discount');
        $sheet->SetCellValue('H1', 'Refund');
        $sheet->SetCellValue('I1', 'Net Revenue');        

        // set Row
        $rowCount = 2;
        foreach ($expData as $value) 
        {
            $total = $value->details[0]->price;
            $totalRef = !empty($value->refund)?$value->refund['pay']:0;
            $normalDis = $value->details[0]->offer + $value->details[0]->doctor_discount;
            $overLimit = $value->details[0]->over_limit;
            $netRevenue = $total - ($normalDis + $overLimit + $totalRef);
              
            $sheet->SetCellValue('A' . $rowCount, $value->doctor);
            $sheet->SetCellValue('B' . $rowCount, $value->creditSale);
            $sheet->SetCellValue('C' . $rowCount, $value->paidAdvance);
            $sheet->SetCellValue('D' . $rowCount, $value->bankTransfer);
            $sheet->SetCellValue('E' . $rowCount, $value->details[0]->no_commission);
            $sheet->SetCellValue('F' . $rowCount, $overLimit);
            $sheet->SetCellValue('G' . $rowCount, $normalDis);
            $sheet->SetCellValue('H' . $rowCount, $totalRef);
            $sheet->SetCellValue('I' . $rowCount, $netRevenue);
            $rowCount++;
        }
        
        $writer = new Xlsx($spreadsheet);
        // push to the file
        $writer->save("assets/excel_data/".$fileName);
        header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
        echo json_encode(array('url'=>base_url().'/assets/excel_data/'.$fileName));
    }

    /*-----------------------------*
    | export service revenue Data
    *------------------------------*/
    public function bdtaskt1m17c3_20_exportServiceRevenue() {
        $range      = $this->request->getVar('date_range');
        $date       = explode('-', $range);
        $from       = date('Y-m-d', strtotime(trim($date[0])));
        $to         = date('Y-m-d', strtotime(trim($date[1])));

        $expData = $this->bdtaskt1m17c3_01_mntModel->bdtaskt1m17_02_getServicesRevenue($from, $to);
        // create file name
        $fileName = 'Service-Revenue-Reports-'.$from.'-to-'.$to.'.xlsx';  
        // load excel library
        $spreadsheet = new Spreadsheet();
 
        $sheet = $spreadsheet->getActiveSheet();
        // set Header
        $sheet->SetCellValue('A1', 'Doctor Code');
        $sheet->SetCellValue('B1', 'Doctor Name');
        $sheet->SetCellValue('C1', 'Gross Total'); 
        $sheet->SetCellValue('D1', 'Offer Discount'); 
        $sheet->SetCellValue('E1', 'Normal Discount');
        $sheet->SetCellValue('F1', 'Over Limit Discount');
        $sheet->SetCellValue('G1', 'Refund');
        $sheet->SetCellValue('H1', 'Net Revenue');        

        // set Row
        $rowCount = 2;
        foreach ($expData as $value) 
        {
            $total = $value->price;
            $totalRef = !empty($value->totalPay)?$value->totalPay:0;
            $normalDis = $value->offer + $value->doctor_discount;
            $overLimit = $value->over_limit;
            $netRevenue = $total - ($normalDis + $overLimit + $totalRef);
              
            $sheet->SetCellValue('A' . $rowCount, $value->short_name);
            $sheet->SetCellValue('B' . $rowCount, $value->nameE);
            $sheet->SetCellValue('C' . $rowCount, $total);
            $sheet->SetCellValue('D' . $rowCount, $value->offer);
            $sheet->SetCellValue('E' . $rowCount, $value->doctor_discount);
            $sheet->SetCellValue('F' . $rowCount, $overLimit);
            $sheet->SetCellValue('G' . $rowCount, $totalRef);
            $sheet->SetCellValue('H' . $rowCount, $netRevenue);
            $rowCount++;
        }
        
        $writer = new Xlsx($spreadsheet);
        // push to the file
        $writer->save("assets/excel_data/".$fileName);
        header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
        echo json_encode(array('url'=>base_url().'/assets/excel_data/'.$fileName));
    }

    /*-----------------------------*
    | export service revenue Data
    *------------------------------*/
    public function bdtaskt1m17c3_21_exportCashierIncome() {
        $userId = $this->request->getVar('user_id');
        $doctor_id = $this->request->getVar('doctor_id');
        $payM   = $this->request->getVar('payment_method');
        $vType  = $this->request->getVar('vtype');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = date('Y-m-d H:i:s', strtotime(trim($date[0])));
        $to     = date('Y-m-d H:i:s', strtotime(trim($date[1])));
        
        $expData = $this->bdtaskt1m17c3_01_mntModel->bdtaskt1m17_01_getUserIncomes11($userId, $payM, $vType, $from, $to, $doctor_id);
        // create file name
        $fileName = 'Cashier-Income-Reports-'.date('Y-m-d', strtotime($from)).'-to-'.date('Y-m-d', strtotime($to)).'.xlsx';  
        // load excel library
        $spreadsheet = new Spreadsheet();
 
        $sheet = $spreadsheet->getActiveSheet();
        // set Header
        $sheet->SetCellValue('A1', 'user Name');
        $sheet->SetCellValue('B1', 'Payment');
        $sheet->SetCellValue('C1', 'Date'); 
        $sheet->SetCellValue('D1', 'Voucher Type');
        $sheet->SetCellValue('E1', 'Voucher Number');
        $sheet->SetCellValue('F1', 'Patient Name');
        $sheet->SetCellValue('G1', 'Doctor Name');        
        $sheet->SetCellValue('H1', 'Debit');        
        $sheet->SetCellValue('I1', 'Credit');        
        $sheet->SetCellValue('J1', 'Amount');        

        // set Row
        $rowCount = 2;
        $amount = 0;
        foreach ($expData[0] as $value) 
        {
            $amount += $value->Debit - $value->Credit;
            $sheet->SetCellValue('A' . $rowCount, $value->created_by);
            $sheet->SetCellValue('B' . $rowCount, $value->payment_method);
            $sheet->SetCellValue('C' . $rowCount, $value->VDate);
            $sheet->SetCellValue('D' . $rowCount, $value->vtype_name);
            $sheet->SetCellValue('E' . $rowCount, $value->VNo);
            $sheet->SetCellValue('F' . $rowCount, $value->patient_name);
            $sheet->SetCellValue('G' . $rowCount, $value->doctor_name);
            $sheet->SetCellValue('H' . $rowCount, $value->Debit);
            $sheet->SetCellValue('I' . $rowCount, $value->Credit);
            $sheet->SetCellValue('J' . $rowCount, $amount);
            $rowCount++;
        }
        // space && payment method row
        $new = $rowCount+3;
        $newName = $rowCount+2;
        for ($i=$rowCount; $i < $new ; $i++) { 
            if($i==$newName){
                $sheet->SetCellValue('A' . $i, 'Payment Method');
                $sheet->SetCellValue('B' . $i, 'Debit');
                $sheet->SetCellValue('C' . $i, 'Credit');
                $sheet->SetCellValue('D' . $i, 'Amount');
            }
        }
        // payment method
        if(!empty($expData[1])){
            $j = $new;
            foreach ($expData[1] as $pay) {
                $Total = $pay->Debit - $pay->Credit;
                $sheet->SetCellValue('A' . $j, $pay->payment_method);
                $sheet->SetCellValue('B' . $j, $pay->Debit);
                $sheet->SetCellValue('C' . $j, $pay->Credit);
                $sheet->SetCellValue('D' . $j, $Total);
                $j++;
            }
        }
        
        $writer = new Xlsx($spreadsheet);
        // push to the file Tcpdf
        $writer->save("assets/excel_data/".$fileName);
        header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
        echo json_encode(array('url'=>base_url().'/assets/excel_data/'.$fileName));
    }

    /*--------------------------
    | Doctor commission form
    *--------------------------*/
    public function bdtaskt1m17c3_22_activityLogForm()
    {
        $data['title']      = get_phrases(['user', 'activity', 'logs']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "management/activity_log_form";
        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get Doctor commission 
    *--------------------------*/
    public function bdtaskt1m17c3_23_activityLogs()
    { 
        $userId    = $this->request->getVar('user_id');
        $range     = $this->request->getVar('date_range');
        $pageNumber= $this->request->getVar('pageNumber');
        $page_size = !empty($this->request->getVar('page_size'))?$this->request->getVar('page_size'):20;

        $date       = explode('-', $range);
        $from       = date('Y-m-d', strtotime(trim($date[0])));
        $to         = date('Y-m-d', strtotime(trim($date[1])));
        $data['setting']    = $this->bdtaskt1m17c3_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $result  = $this->bdtaskt1m17c3_01_mntModel->bdtaskt1m17_13_getUserActivityLogs($userId, $from, $to, $pageNumber, $page_size);

        $data['hasPrintAccess']   = $this->permission->method('activity_log_report', 'print')->access();
        $data['hasExportAccess']  = $this->permission->method('activity_log_report', 'export')->access();
        $data['results']      = $result['data'];
        $data['user_id']      = $userId;
        $data['from']         = $from;
        $data['to']           = $to;
        // echo "<pre>";
        // print_r($data['results']);die();
        $statement = view('App\Modules\Reports\Views\management\activity_log_reports', $data);
         echo json_encode(array('info'=>$statement, 'pageNumber'=>$pageNumber,'page_size'=>$page_size,'total'=>$result['num_rows']));
    }

    /*-----------------------------*
    | Export user logs
    *------------------------------*/
    public function bdtaskt1m17c3_24_exportActivityLogs() {
        $user_id  = $this->request->getVar('user_id');
        $from       = $this->request->getVar('from');
        $to         = $this->request->getVar('to');

        $expData = $this->bdtaskt1m17c3_01_mntModel->bdtaskt1m17_13_getUserActivityLogs($user_id, $from, $to, 1, 1, true);
        // create file name
        $fileName = 'Activity-Logs-Report-'.$from.'-to-'.$to.'.xlsx';  
        // load excel library
        $spreadsheet = new Spreadsheet();
 
        $sheet = $spreadsheet->getActiveSheet();
        // set Header
        $sheet->SetCellValue('A1', 'Serial No');
        $sheet->SetCellValue('B1', 'Type');
        $sheet->SetCellValue('C1', 'Action Type');
        $sheet->SetCellValue('D1', 'Private ID');
        $sheet->SetCellValue('E1', 'Description');
        $sheet->SetCellValue('F1', 'Action By');
        $sheet->SetCellValue('G1', 'Action Date');
        
        // set Row
        $rowCount = 2;
        foreach ($expData['data'] as $value) 
        {
            $sheet->SetCellValue('A' . $rowCount, $value->id);
            $sheet->SetCellValue('B' . $rowCount, $value->type);
            $sheet->SetCellValue('C' . $rowCount, $value->action);
            $sheet->SetCellValue('D' . $rowCount, $value->action_id);
            $sheet->SetCellValue('E' . $rowCount, $value->slug);
            $sheet->SetCellValue('F' . $rowCount, $value->created_by);
            $sheet->SetCellValue('G' . $rowCount, date('d/m/Y h:i:s A', strtotime($value->created_date)));
            $rowCount++;
        }
        
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
        //header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment; filename="'. urlencode($fileName) .'"'); 
        header('Cache-Control: max-age=0');
        //ob_end_clean();
        $writer->save('php://output');  // download file 
        exit();
    }

    /*--------------------------
    | Doctor commission form
    *--------------------------*/
    public function bdtaskt1m17c3_25_doctorIncentive()
    {
        $data['title']      = get_phrases(['aldara', 'incentive']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "management/doctor_incentive_form";
        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get Doctor commission 
    *--------------------------*/
    public function bdtaskt1m17c3_26_getDoctorIncentives()
    { 
        $range      = $this->request->getVar('date_range');
        $doctor_id  = $this->request->getVar('doctor_id');
        $service_id = $this->request->getVar('service_id');
        $date       = explode('-', $range);
        $from       = date('Y-m-d', strtotime(trim($date[0])));
        $to         = date('Y-m-d', strtotime(trim($date[1])));
        $data['setting']    = $this->bdtaskt1m17c3_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c3_01_mntModel->bdtaskt1m17_14_getDoctorIncentive($doctor_id, $service_id, $from, $to);
        $data['hasPrintAccess']   = $this->permission->method('aldara_incentive_report', 'print')->access();
        $data['hasExportAccess']  = $this->permission->method('aldara_incentive_report', 'export')->access();
        // echo "<pre>";
        // print_r($data['results']);die();
        $statement = view('App\Modules\Reports\Views\management\doctor_incentive_reports', $data);
        echo json_encode(array('data'=>$statement));
    }

    /*-----------------------------*
    | export doctor commission Data
    *------------------------------*/
    public function bdtaskt1m17c3_27_incentiveExportExcel() {
        $range      = $this->request->getVar('date_range');
        $doctor_id  = $this->request->getVar('doctor_id');
        $service_id = $this->request->getVar('service_id');
        $date       = explode('-', $range);
        $from       = date('Y-m-d', strtotime(trim($date[0])));
        $to         = date('Y-m-d', strtotime(trim($date[1])));

        $expData = $this->bdtaskt1m17c3_01_mntModel->bdtaskt1m17_14_getDoctorIncentive($doctor_id, $service_id, $from, $to);
        // create file name
        $fileName = 'Doctors-Incentive-Reports-'.$from.'-to-'.$to.'.xlsx';  
        // load excel library
        $spreadsheet = new Spreadsheet();
 
        $sheet = $spreadsheet->getActiveSheet();
        // set Header
        $sheet->SetCellValue('A1', 'Doctor Code');
        $sheet->SetCellValue('B1', 'Service Code');
        $sheet->SetCellValue('C1', 'Service Name');
        $sheet->SetCellValue('D1', 'Quantity');
        $sheet->SetCellValue('E1', 'Total Service Amount');
        $sheet->SetCellValue('F1', 'Allowed Discount'); 
        $sheet->SetCellValue('G1', 'Over Limit Discount');
        $sheet->SetCellValue('H1', 'Offer Discount');
        $sheet->SetCellValue('I1', 'Refun');
        $sheet->SetCellValue('J1', 'Net Revenue');
        $sheet->SetCellValue('K1', 'Incentive %');
        $sheet->SetCellValue('L1', 'Total Incentive');    

        // set Row 
        $rowCount = 2;
        foreach ($expData as $value) 
        {
            $total = $value->qty_price;
            $normalDis = $value->over_limit_discount + $value->doctor_discount + $value->offer_discount;
            $totalRef = !empty($value->totalPay)?$value->totalPay:0;
            
            $netRevenue = $total - ($normalDis + $totalRef);
            $incentive = ($netRevenue*$value->incentive_percent)/100;
              
            $sheet->SetCellValue('A' . $rowCount, $value->short_name);
            $sheet->SetCellValue('B' . $rowCount, $value->code_no);
            $sheet->SetCellValue('C' . $rowCount, $value->service_name);
            $sheet->SetCellValue('D' . $rowCount, $value->qty);
            $sheet->SetCellValue('E' . $rowCount, $total);
            $sheet->SetCellValue('F' . $rowCount, $value->doctor_discount);
            $sheet->SetCellValue('G' . $rowCount, $value->over_limit_discount);
            $sheet->SetCellValue('H' . $rowCount, $value->offer_discount);
            $sheet->SetCellValue('I' . $rowCount, $totalRef);
            $sheet->SetCellValue('J' . $rowCount, $netRevenue);
            $sheet->SetCellValue('K' . $rowCount, $value->incentive_percent);
            $sheet->SetCellValue('L' . $rowCount, $incentive);
            $rowCount++;
        }
        
        $writer = new Xlsx($spreadsheet);
        // push to the file
        $writer->save("assets/excel_data/".$fileName);
        header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
        //header("Content-Type: application/vnd.ms-excel");
        echo json_encode(array('url'=>base_url().'/assets/excel_data/'.$fileName));
    }

    /*-----------------------------*
    | export doctor commission Data
    *------------------------------*/
    public function appointExcel() {

        $expData = $this->bdtaskt1m17c3_01_mntModel->excelData();
        // echo "<pre>";
        // print_r($expData);exit();
        // create file name
        $fileName = 'Doctors-Commission-Reports.xlsx';  
        // load excel library
        $spreadsheet = new Spreadsheet();
 
        $sheet = $spreadsheet->getActiveSheet();
        // set Header
        $sheet->SetCellValue('A1', 'Doctor Code');
        $sheet->SetCellValue('B1', 'Service Code');
        $sheet->SetCellValue('C1', 'Service Name');
        $sheet->SetCellValue('D1', 'Quantity');
        $sheet->SetCellValue('E1', 'Total Service Amount');
        $sheet->SetCellValue('F1', 'Allowed Discount'); 
        $sheet->SetCellValue('G1', 'Over Limit Discount');
        $sheet->SetCellValue('H1', 'Offer Discount');
        $sheet->SetCellValue('I1', 'Refun');
        $sheet->SetCellValue('J1', 'Net Revenue');
        $sheet->SetCellValue('K1', 'Total Consumed');
        $sheet->SetCellValue('L1', 'Consumed');    
        $sheet->SetCellValue('M1', 'Cost');       
        $sheet->SetCellValue('N1', 'Total Deduction');       
        $sheet->SetCellValue('O1', 'Share Income');       
        $sheet->SetCellValue('P1', '%');       
        $sheet->SetCellValue('Q1', 'Share');       
        $sheet->SetCellValue('R1', 'Credit');       

        // set Row
        $rowCount = 2;
        foreach ($expData['aaData'] as $value) 
        {
              
            $sheet->SetCellValue('A' . $rowCount, $value['appoint_id']);
            $sheet->SetCellValue('B' . $rowCount, $value['file_no']);
            $sheet->SetCellValue('C' . $rowCount, $value['patient_type']);
            $sheet->SetCellValue('D' . $rowCount, $value['appoint_type']);
            $sheet->SetCellValue('E' . $rowCount, $value['patient_name']);
            $sheet->SetCellValue('F' . $rowCount, $value['nameE']);
            $sheet->SetCellValue('G' . $rowCount, $value['mobile']);
            $sheet->SetCellValue('H' . $rowCount, $value['enter_time']);
            $sheet->SetCellValue('I' . $rowCount, $value['start_time']);
            $sheet->SetCellValue('J' . $rowCount, $value['out_time']);
            $sheet->SetCellValue('K' . $rowCount, $value['service_name']);
            $sheet->SetCellValue('L' . $rowCount, $value['doctor_name']);
            $sheet->SetCellValue('M' . $rowCount, $value['branch_name']);
            $sheet->SetCellValue('N' . $rowCount, $value['available_days']);
            $sheet->SetCellValue('O' . $rowCount, $value['remarks']);
            $sheet->SetCellValue('P' . $rowCount, $value['date']);
            $sheet->SetCellValue('Q' . $rowCount, $value['type']);
            $sheet->SetCellValue('R' . $rowCount, $value['booked_from_time']);
            $rowCount++;
        }
        
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment; filename="'. $fileName .'"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');  // download file 
    }
    
}
