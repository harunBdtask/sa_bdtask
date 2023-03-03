<?php namespace App\Modules\Reports\Controllers;
use CodeIgniter\Controller;
use App\Modules\Reports\Models\Bdtaskt1m17VatModel;
use App\Models\Bdtaskt1m1CommonModel;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Libraries\Permission;
class Bdtaskt1m17c4Vat extends BaseController
{
    private $bdtaskt1m17c4_01_vatModel;
    private $bdtaskt1m17c4_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bdtaskt1m17c4_01_vatModel = new Bdtaskt1m17VatModel();
        $this->bdtaskt1m17c4_02_CmModel = new Bdtaskt1m1CommonModel();
        if(session('defaultLang')=='english'){
            $this->langColumn = 'nameE';
        }else{
            $this->langColumn = 'nameA';
        }
        $this->permission = new Permission();
        $this->hasPrintAccess = $this->permission->method('vat_reports', 'print')->access();
        $this->hasExportAccess = $this->permission->method('vat_reports', 'export')->access();
    }

    /*--------------------------
    | invoices or receipt voucher with vat reports form 
    *--------------------------*/
    public function bdtaskt1m17c4_01_invoiceOrReceptsWithVat()
    {

        $data['title']      = get_phrases(['income', 'with', 'vat']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "vat/invoice_or_receipt_with_vat_form";
        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get invoices or receipt voucher with vat reports
    *--------------------------*/
    public function bdtaskt1m17c4_02_getInvOrReceiptWithVat()
    { 
        $branch_id  = $this->request->getVar('branch_id');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = date('Y-m-d', strtotime(trim($date[0])));
        $to     = date('Y-m-d', strtotime(trim($date[1])));
        $data['setting']    = $this->bdtaskt1m17c4_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c4_01_vatModel->bdtaskt1m17_01_getInvOrReceiptWithVat($branch_id, $from, $to);
        $data['hasPrintAccess']   = $this->hasPrintAccess;
        $data['hasExportAccess']  = $this->hasExportAccess;
        // echo "<pre>";
        // print_r($data['results']);die();
        $revenue = view('App\Modules\Reports\Views\vat\invoice_or_receipt_with_vat_reports', $data);
        echo json_encode(array('data'=>$revenue));
    }

    /*--------------------------
    | invoices or receipt voucher without vat reports form
    *--------------------------*/
    public function bdtaskt1m17c4_03_invoiceOrReceptsWithOutVat()
    {

        $data['title']      = get_phrases(['income', 'without', 'vat']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "vat/invoice_or_receipt_without_vat_form";
        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get invoices or receipt voucher without vat reports
    *--------------------------*/
    public function bdtaskt1m17c4_04_getInvOrReceiptWithOutVat()
    { 
        $branch_id  = $this->request->getVar('branch_id');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = date('Y-m-d', strtotime(trim($date[0])));
        $to     = date('Y-m-d', strtotime(trim($date[1])));
        $data['setting']    = $this->bdtaskt1m17c4_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c4_01_vatModel->bdtaskt1m17_02_getInvOrReceiptWithOutVat($branch_id, $from, $to);
        $data['hasPrintAccess']   = $this->hasPrintAccess;
        $data['hasExportAccess']  = $this->hasExportAccess;
        // echo "<pre>";
        // print_r($data['results']);die();
        $revenue = view('App\Modules\Reports\Views\vat\invoice_or_receipt_without_vat_reports', $data);
        echo json_encode(array('data'=>$revenue));
    }

    /*--------------------------
    | Inventory stock with vat reports form
    *--------------------------*/
    public function bdtaskt1m17c4_05_inventoryStockWithVat()
    {

        $data['title']      = get_phrases(['purchase', 'with', 'vat']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "vat/inventory_stock_with_vat_form";
        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get Inventory stock with vat reports
    *--------------------------*/
    public function bdtaskt1m17c4_06_getInventoryStockVat()
    { 
        $branch_id  = $this->request->getVar('branch_id');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = date('Y-m-d', strtotime(trim($date[0])));
        $to     = date('Y-m-d', strtotime(trim($date[1])));
        $data['setting']    = $this->bdtaskt1m17c4_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c4_01_vatModel->bdtaskt1m17_03_getInventoryStockWithVat($branch_id, $from, $to);
        $data['hasPrintAccess']   = $this->hasPrintAccess;
        $data['hasExportAccess']  = $this->hasExportAccess;
        // echo "<pre>";
        // print_r($data['results']);die();
        $revenue = view('App\Modules\Reports\Views\vat\inventory_stock_with_vat_reports', $data);
        echo json_encode(array('data'=>$revenue));
    }

    /*--------------------------
    | Inventory stock without vat reports form
    *--------------------------*/
    public function bdtaskt1m17c4_07_inventoryStockWithOutVat()
    {

        $data['title']      = get_phrases(['purchase', 'without', 'vat']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "vat/inventory_stock_without_vat_form";
        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get Inventory stock without vat reports
    *--------------------------*/
    public function bdtaskt1m17c4_08_getInventoryStockOutVat()
    { 
        $branch_id  = $this->request->getVar('branch_id');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = date('Y-m-d', strtotime(trim($date[0])));
        $to     = date('Y-m-d', strtotime(trim($date[1])));
        $data['setting']    = $this->bdtaskt1m17c4_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c4_01_vatModel->bdtaskt1m17_04_getInventoryStockWithoutVat($branch_id, $from, $to);
        $data['hasPrintAccess']   = $this->hasPrintAccess;
        $data['hasExportAccess']  = $this->hasExportAccess;
        // echo "<pre>";
        // print_r($data['results']);die();
        $revenue = view('App\Modules\Reports\Views\vat\inventory_stock_without_vat_reports', $data);
        echo json_encode(array('data'=>$revenue));
    }

    /*-----------------------------*
    | export purchase Data
    *------------------------------*/
    public function bdtaskt1m17c4_09_exportPurchaseVat() {
        $branch_id  = $this->request->getVar('branch_id');
        $type  = $this->request->getVar('type');
        $range = $this->request->getVar('date_range');
        $date  = explode('-', $range);
        $from  = date('Y-m-d', strtotime(trim($date[0])));
        $to    = date('Y-m-d', strtotime(trim($date[1])));

        // load excel library
        $spreadsheet = new Spreadsheet();
 
        $sheet = $spreadsheet->getActiveSheet();
        if($type==1){
            $expData = $this->bdtaskt1m17c4_01_vatModel->bdtaskt1m17_03_getInventoryStockWithVat($branch_id, $from, $to);
            // create file name
            $fileName = 'Purchase-Vat-Reports-'.$from.'-to-'.$to.'.xlsx';  
            // set Header
            $sheet->SetCellValue('A1', 'Voucher No');
            $sheet->SetCellValue('B1', 'Supplier Name');
            $sheet->SetCellValue('C1', 'Vat No');
            $sheet->SetCellValue('D1', 'CR No');
            $sheet->SetCellValue('E1', 'To Warhouse');
            $sheet->SetCellValue('F1', 'Price Without Vat'); 
            $sheet->SetCellValue('G1', 'Vat');
            $sheet->SetCellValue('H1', 'Price With Vat');
            $sheet->SetCellValue('I1', 'Paid Amount With Vat');
            $sheet->SetCellValue('J1', 'Created By');        
            $sheet->SetCellValue('K1', 'Created Date');   
        }else{
            $expData = $this->bdtaskt1m17c4_01_vatModel->bdtaskt1m17_04_getInventoryStockWithoutVat($branch_id, $from, $to);
            // create file name
            $fileName = 'Purchase-Without-Vat-Reports-'.$from.'-to-'.$to.'.xlsx';  
            // set Header
            $sheet->SetCellValue('A1', 'Voucher No');
            $sheet->SetCellValue('B1', 'Supplier Name');
            $sheet->SetCellValue('C1', 'Vat No');
            $sheet->SetCellValue('D1', 'CR No');
            $sheet->SetCellValue('E1', 'To Warhouse');
            $sheet->SetCellValue('F1', 'Price'); 
            $sheet->SetCellValue('G1', 'Vat');
            $sheet->SetCellValue('H1', 'Amount');
            $sheet->SetCellValue('I1', 'Paid');
            $sheet->SetCellValue('J1', 'Created By');        
            $sheet->SetCellValue('K1', 'Created Date');   
        }     

        // set Row
        $rowCount = 2;
        foreach ($expData as $value) 
        {
              
            $sheet->SetCellValue('A' . $rowCount, $value->voucher_no);
            $sheet->SetCellValue('B' . $rowCount, $value->supplier_name);
            $sheet->SetCellValue('C' . $rowCount, $value->vat_no);
            $sheet->SetCellValue('D' . $rowCount, $value->cr_no);
            $sheet->SetCellValue('E' . $rowCount, $value->warehouse);
            $sheet->SetCellValue('F' . $rowCount, $value->sub_total);
            $sheet->SetCellValue('G' . $rowCount, $value->vat);
            $sheet->SetCellValue('H' . $rowCount, $value->grand_total);
            $sheet->SetCellValue('I' . $rowCount, $value->receipt);
            $sheet->SetCellValue('J' . $rowCount, $value->createdBy);
            $sheet->SetCellValue('K' . $rowCount, date('d/m/Y H:i:s', strtotime($value->created_at)));
            $rowCount++;
        }
        
        $writer = new Xlsx($spreadsheet);
        // push to the file
        $writer->save("assets/excel_data/".$fileName);
        header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
        echo json_encode(array('url'=>base_url().'/assets/excel_data/'.$fileName));
    }
    
    /*-----------------------------*
    | export Income vat Data
    *------------------------------*/
    public function bdtaskt1m17c4_10_exportIncomeVat() {
        $branch_id  = $this->request->getVar('branch_id');
        $type  = $this->request->getVar('type');
        $range = $this->request->getVar('date_range');
        $date  = explode('-', $range);
        $from  = date('Y-m-d', strtotime(trim($date[0])));
        $to    = date('Y-m-d', strtotime(trim($date[1])));

        // create file name
        if($type==1){
            $expData = $this->bdtaskt1m17c4_01_vatModel->bdtaskt1m17_01_getInvOrReceiptWithVat($branch_id, $from, $to);
            $fileName = 'Income-Vat-Reports-'.$from.'-to-'.$to.'.xlsx';  
        }else{
            $expData = $this->bdtaskt1m17c4_01_vatModel->bdtaskt1m17_02_getInvOrReceiptWithOutVat($branch_id, $from, $to);
            $fileName = 'Income-Without-Vat-Reports-'.$from.'-to-'.$to.'.xlsx';  
        }
        
        // load excel library
        $spreadsheet = new Spreadsheet();
 
        $sheet = $spreadsheet->getActiveSheet();
        // set Header
        $sheet->SetCellValue('A1', 'Voucher No');
        $sheet->SetCellValue('B1', 'Type');
        $sheet->SetCellValue('C1', 'Patient Name');
        $sheet->SetCellValue('D1', 'Nationality');
        $sheet->SetCellValue('E1', 'ID No');
        $sheet->SetCellValue('F1', 'Doctor Name'); 
        $sheet->SetCellValue('G1', 'Amount');
        $sheet->SetCellValue('H1', 'Vat');
        $sheet->SetCellValue('I1', 'Paid');
        $sheet->SetCellValue('J1', 'Created By');        
        $sheet->SetCellValue('K1', 'Created Date');        

        // set Row
        $rowCount = 2;
        foreach ($expData as $value) 
        {
              
            $sheet->SetCellValue('A' . $rowCount, $value->id);
            $sheet->SetCellValue('B' . $rowCount, $value->type);
            $sheet->SetCellValue('C' . $rowCount, $value->patient_name);
            $sheet->SetCellValue('D' . $rowCount, $value->nationality);
            $sheet->SetCellValue('E' . $rowCount, $value->nid_no);
            $sheet->SetCellValue('F' . $rowCount, $value->doctor_name);
            $sheet->SetCellValue('G' . $rowCount, $value->total);
            $sheet->SetCellValue('H' . $rowCount, $value->vat);
            $sheet->SetCellValue('I' . $rowCount, $value->receipt);
            $sheet->SetCellValue('J' . $rowCount, $value->createdBy);
            $sheet->SetCellValue('K' . $rowCount, date('d/m/Y H:i:s', strtotime($value->created_date)));
            $rowCount++;
        }
        
        $writer = new Xlsx($spreadsheet);
        // push to the file
        $writer->save("assets/excel_data/".$fileName);
        header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
        echo json_encode(array('url'=>base_url().'/assets/excel_data/'.$fileName));
    }

    /*--------------------------
    | Journal with vat reports form
    *--------------------------*/
    public function bdtaskt1m17c4_11_journalWithVat()
    {

        $data['title']      = get_phrases(['Journal', 'with', 'vat']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "vat/journal_with_vat_form";
        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get Inventory stock with vat reports
    *--------------------------*/
    public function bdtaskt1m17c4_12_getJournalWithVat()
    { 
        $branch_id  = $this->request->getVar('branch_id');
        $status = $this->request->getVar('status');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = date('Y-m-d', strtotime(trim($date[0])));
        $to     = date('Y-m-d', strtotime(trim($date[1])));
        $data['setting']    = $this->bdtaskt1m17c4_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c4_01_vatModel->bdtaskt1m17_06_getJournalWithVat($branch_id, $from, $to, 1, $status);
        $data['hasPrintAccess']   = $this->hasPrintAccess;
        $data['hasExportAccess']  = $this->hasExportAccess;
        // echo "<pre>";
        // print_r($data['results']);die();
        $data['type'] = 1;
        $data['status'] = $status;
        $data['from'] = $from;
        $data['to'] = $to;

        $revenue = view('App\Modules\Reports\Views\vat\journal_with_vat_reports', $data);
        echo json_encode(array('data'=>$revenue));
    }

    /*-----------------------------*
    | export journal vat Data
    *------------------------------*/
    public function bdtaskt1m17c4_13_exportJournalVat() {
        $branch_id  = $this->request->getVar('branch_id');
        $type  = $this->request->getVar('type');
        $status= $this->request->getVar('status');
        $from  = $this->request->getVar('from');
        $to    = $this->request->getVar('to');

        // create file name
        if($type==1){
            $expData = $this->bdtaskt1m17c4_01_vatModel->bdtaskt1m17_06_getJournalWithVat($branch_id, $from, $to, 1, $status);
            $fileName = 'Journal-Vat-Reports-'.$from.'-to-'.$to.'.xlsx';  
        }else{
            $expData = $this->bdtaskt1m17c4_01_vatModel->bdtaskt1m17_06_getJournalWithVat($branch_id, $from, $to, 0, $status);
            $fileName = 'Journal-Without-Vat-Reports-'.$from.'-to-'.$to.'.xlsx';  
        }
        
        // load excel library
        $spreadsheet = new Spreadsheet();
 
        $sheet = $spreadsheet->getActiveSheet();
        // set Header
        $sheet->SetCellValue('A1', 'Voucher No');
        $sheet->SetCellValue('B1', 'Voucher Date');
        $sheet->SetCellValue('C1', 'Total Debit');
        $sheet->SetCellValue('D1', 'Total Credit');
        $sheet->SetCellValue('E1', 'Vat');
        $sheet->SetCellValue('F1', 'Status'); 
        $sheet->SetCellValue('G1', 'Created By');
        $sheet->SetCellValue('H1', 'Created Date');     

        // set Row
        $rowCount = 2;
        foreach ($expData as $value) 
        {
            if($value->status==1){
                $status = 'Approved';
            }else if($value->status==0){
                $status = 'Pending';
            }else{
                $status = 'Rejected';
            }
            $sheet->SetCellValue('A' . $rowCount, $value->vtype.'-'.$value->id);
            $sheet->SetCellValue('B' . $rowCount, $value->voucher_date);
            $sheet->SetCellValue('C' . $rowCount, $value->totalDebit);
            $sheet->SetCellValue('D' . $rowCount, $value->totalCredit);
            $sheet->SetCellValue('E' . $rowCount, $value->vat);
            $sheet->SetCellValue('F' . $rowCount, $status);
            $sheet->SetCellValue('G' . $rowCount, $value->createdBy);
            $sheet->SetCellValue('H' . $rowCount, date('d/m/Y H:i:s', strtotime($value->created_date)));
             $rowCount++;
        }
        
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. $fileName .'"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');  // download file 
    }
}
