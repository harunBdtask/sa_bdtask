<?php namespace App\Modules\Reports\Controllers;
use CodeIgniter\Controller;
use App\Modules\Reports\Models\Bdtaskt1m17AccountModel;
use App\Modules\Account\Models\Bdtaskt1m8ReportModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf;
class Bdtaskt1m17c1Accounts extends BaseController
{
    private $bdtaskt1m17c1_01_accModel;
    private $bdtaskt1m17c1_02_CmModel;
    /**
     * Constructor. 
     */
    public function __construct()
    {
        $this->bdtaskt1m17c1_01_accModel = new Bdtaskt1m17AccountModel();
        $this->bdtaskt1m17c1_02_CmModel = new Bdtaskt1m1CommonModel();
        $this->bdtaskt1m17c1_03_reportModel = new Bdtaskt1m8ReportModel();
        if(session('defaultLang')=='english'){
            $this->langColumn = 'nameE';
        }else{
            $this->langColumn = 'nameA';
        }
        $this->permission = new Permission();
    }

    /*--------------------------
    | Statement reports form
    *--------------------------*/
    public function bdtaskt1m17c1_01_accStatements()
    {
        $data['title']      = get_phrases(['account', 'statement']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "account/statement_form";
        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get Statements
    *--------------------------*/
    public function bdtaskt1m17c1_02_getAccStatements()
    { 
        $branch_id = $this->request->getVar('branch_id');
        $userId = $this->request->getVar('user_id');
        $type   = $this->request->getVar('type');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = date('Y-m-d', strtotime(trim($date[0])));
        $to     = date('Y-m-d', strtotime(trim($date[1])));
        $data['setting']    = $this->bdtaskt1m17c1_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c1_01_accModel->bdtaskt1m17_01_getStatementData($branch_id, $userId, $from, $to);
        $data['prebalance']   = $this->bdtaskt1m17c1_03_reportModel->general_led_report_prebalance($userId, $from, $branch_id);
        $data['hasPrintAccess']   = $this->permission->method('statement_reports', 'print')->access();
        $data['hasExportAccess']  = $this->permission->method('statement_reports', 'export')->access();
        // echo "<pre>";
        // print_r($this->request->getVar());die(); 
        if($type=='supplier'){
            $statement = view('App\Modules\Reports\Views\account\supplier_statements', $data);
        }else{
            $statement = view('App\Modules\Reports\Views\account\statement_reports', $data);
        }
        echo json_encode(array('data'=>$statement));
        exit();
    }

    /*--------------------------
    | Search patient
    *--------------------------*/
    public function bdtaskt1m17c1_03_searchPatientAcc()
    { 
        $text = $this->request->getVar('term');
        $data = $this->bdtaskt1m17c1_01_accModel->bdtaskt1m17_02_getPatient($text, $this->langColumn);
        echo json_encode($data);
        exit();
    }

    /*--------------------------
    | Search supplier
    *--------------------------*/
    public function bdtaskt1m17c1_04_searchSupplierAcc()
    { 
        $text = $this->request->getVar('term');
        $data = $this->bdtaskt1m17c1_01_accModel->bdtaskt1m17_03_getSupplier($text, $this->langColumn);
        echo json_encode($data);
        exit();
    }

    /*--------------------------
    | Get voucher details 
    *--------------------------*/
    public function bdtaskt1m17c1_05_getVoucherdetails()
    { 
        $voucher  = $this->request->getVar('voucherId');
        $info     = explode('-', $voucher);
        $type     = $info[0];
        $vId      = $info[1];
        $data['results']   = $this->bdtaskt1m17c1_01_accModel->bdtaskt1m17_06_getDoctorCom(getTNameByVType($type), $vId);
    
        $details = view('App\Modules\Reports\Views\bdtaskt1m17v8_doc_commission_reports', $data);
        echo json_encode(array('data'=>$details));
        exit();
    }

    /*--------------------------
    | Get supplier by head
    *--------------------------*/
    public function bdtaskt1m17c1_06_getPatientInfoById()
    { 
        $user_id = $this->request->getVar('user_id');
        $data = $this->bdtaskt1m17c1_01_accModel->bdtaskt1m17_04_getPatientInfoById($user_id);
        echo json_encode($data);
        exit();
    }

    /*--------------------------
    | Get patient by head
    *--------------------------*/
    public function bdtaskt1m17c1_07_getSupplierInfoById()
    { 
        $user_id = $this->request->getVar('user_id');
        $data = $this->bdtaskt1m17c1_01_accModel->bdtaskt1m17_05_getSupplierInfoById($user_id);
        echo json_encode($data);
        exit();
    }

    /*--------------------------
    | Get all account headCode
    *--------------------------*/
    public function bdtaskt1m17c1_08_getAccountList(){
        $text = $this->request->getVar('term');
        $data = $this->bdtaskt1m17c1_01_accModel->bdtaskt1m17_06_getAccountList($text);
        echo json_encode($data);
        exit();
    }

    /*--------------------------
    | Get receive info
    *--------------------------*/
    public function bdtaskt1m17c1_09_getReceiveInfo(){
        $voucher_no = $this->request->getVar('voucher_no');
        $data = $this->bdtaskt1m17c1_01_accModel->bdtaskt1m17_07_getReceiveInfo($voucher_no);
        echo json_encode($data);
        exit();
    }

    /*--------------------------
    | Get return info
    *--------------------------*/
    public function bdtaskt1m17c1_10_getReturnInfo(){
        $voucher_no = $this->request->getVar('voucher_no');
        $data = $this->bdtaskt1m17c1_01_accModel->bdtaskt1m17_08_getReturnInfo($voucher_no);
        echo json_encode($data);
        exit();
    }
    /*--------------------------
    | Get payment info 
    *--------------------------*/
    public function bdtaskt1m17c1_11_getPaymentInfo(){
        $voucher_no = $this->request->getVar('voucher_no');
        $data = $this->bdtaskt1m17c1_01_accModel->bdtaskt1m17_09_getPaymentInfo($voucher_no);
        echo json_encode($data);
        exit();
    }

    /*--------------------------
    | Get payment info 
    *--------------------------*/
    public function bdtaskt1m17c1_15_getConsumptionInfo(){
        $voucher_no = $this->request->getVar('voucher_no');
        $data = $this->bdtaskt1m17c1_01_accModel->bdtaskt1m17_11_getConsumptionInfo($voucher_no);
        echo json_encode($data);
        exit();
    }

    /*--------------------------
    | Journal reports form
    *--------------------------*/
    public function bdtaskt1m17c1_12_journalReports()
    {
        $data['title']      = get_phrases(['journal', 'voucher']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "account/journal_form";
        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get voucher details 
    *--------------------------*/
    public function bdtaskt1m17c1_13_getJournalReports()
    { 
        $user_id  = $this->request->getVar('user_id');
        $range    = $this->request->getVar('date_range');
        $date     = explode('-', $range);
        $from     = date('Y-m-d', strtotime(trim($date[0])));
        $to       = date('Y-m-d', strtotime(trim($date[1])));
        $data['userInfo']    = $this->bdtaskt1m17c1_02_CmModel->bdtaskt1m1_03_getRow('employees', array('emp_id'=>$user_id));
        $data['setting']    = $this->bdtaskt1m17c1_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c1_01_accModel->bdtaskt1m17_10_getJournalData($user_id, $from, $to);
        $data['hasPrintAccess']   = $this->permission->method('journal_report', 'print')->access();
        $data['hasExportAccess']  = $this->permission->method('journal_report', 'export')->access();
    
        $details = view('App\Modules\Reports\Views\account\journal_reports', $data);
        echo json_encode(array('data'=>$details));
        exit();
    }

    /*-----------------------------*
    | export Journal voucher Data
    *------------------------------*/
    public function bdtaskt1m17c1_14_exportJournalReports() {
        $user_id  = $this->request->getVar('user_id');
        $range      = $this->request->getVar('date_range');
        $date       = explode('-', $range);
        $from       = date('Y-m-d', strtotime(trim($date[0])));
        $to         = date('Y-m-d', strtotime(trim($date[1])));

        $expData = $this->bdtaskt1m17c1_01_accModel->bdtaskt1m17_10_getJournalData($user_id, $from, $to);
        // create file name
        $fileName = 'User-Journal-Voucher-Reports-'.$from.'-to-'.$to.'.xlsx';  
        // load excel library
        $spreadsheet = new Spreadsheet();
 
        $sheet = $spreadsheet->getActiveSheet();
        // set Header
        $sheet->SetCellValue('A1', 'Voucher No');
        $sheet->SetCellValue('B1', 'Voucher Type');
        $sheet->SetCellValue('C1', 'Transaction Date'); 
        $sheet->SetCellValue('D1', 'Narration'); 
        $sheet->SetCellValue('E1', 'Debit');
        $sheet->SetCellValue('F1', 'Credit');
        $sheet->SetCellValue('G1', 'Amount');

        $amount = 0;
        // set Row
        $rowCount = 2;
        foreach ($expData as $value) 
        {
            $amount += $value->Debit - $value->Credit;
              
            $sheet->SetCellValue('A' . $rowCount, $value->VNo);
            $sheet->SetCellValue('B' . $rowCount, $value->type);
            $sheet->SetCellValue('C' . $rowCount, $value->VDate);
            $sheet->SetCellValue('D' . $rowCount, $value->Narration);
            $sheet->SetCellValue('E' . $rowCount, $value->Debit);
            $sheet->SetCellValue('F' . $rowCount, $value->Credit);
            $sheet->SetCellValue('G' . $rowCount, $amount);
            $rowCount++;
        }
        
        $writer = new Xlsx($spreadsheet);
        // push to the file
        $writer->save("assets/excel_data/".$fileName);
        header("Content-Type: application/vnd.ms-excel");
        echo json_encode(array('url'=>base_url().'/assets/excel_data/'.$fileName));
        exit();
    }

    /*--------------------------
    | Patient Statement reports form
    *--------------------------*/
    public function bdtaskt1m17c1_15_patientStatements()
    {
        $data['title']      = get_phrases(['patient', 'statement']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "account/patient_statement_form";
        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Patient Statement reports form
    *--------------------------*/
    public function bdtaskt1m17c1_16_supplierStatements()
    {
        $data['title']      = get_phrases(['supplier', 'statement']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "account/supplier_statement_form";
        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get all account headCode
    *--------------------------*/
    public function bdtaskt1m17c1_17_searchAccounts(){
        $text = $this->request->getVar('term');
        $data = $this->bdtaskt1m17c1_01_accModel->bdtaskt1m17_12_getSearchAccounts($text);
        echo json_encode($data);
        exit();
    }
    
}
