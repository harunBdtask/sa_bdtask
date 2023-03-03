<?php namespace App\Modules\Reports\Controllers;
use CodeIgniter\Controller;
use App\Modules\Reports\Models\Bdtaskt1m17PointReportModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Bdtaskt1m17c7PointingReports extends BaseController
{
    private $bdtaskt1m17c7_01appRModel;
    private $bdtaskt1m17c7_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bdtaskt1m17c7_01_pointRModel = new Bdtaskt1m17PointReportModel();
        $this->bdtaskt1m17c7_02_CmModel = new Bdtaskt1m1CommonModel();
        if(session('defaultLang')=='english'){
            $this->langColumn = 'nameE';
        }else{
            $this->langColumn = 'nameA';
        }
        $this->permission = new Permission();
    }

    /*--------------------------
    | Gain points report form
    *--------------------------*/
    public function bdtaskt1m17c7_01_gainReports()
    {

        $data['title']      = get_phrases(['gain', 'points']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "point/gain_form";
        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get gain points report
    *--------------------------*/
    public function bdtaskt1m17c7_02_getGainReports()
    {
        $doctorId   = $this->request->getVar('doctor_id');
        $branch_id  = $this->request->getVar('branch_id');
        $patient_id = $this->request->getVar('patient_id');
        $range      = $this->request->getVar('date_range');
        $service_id = $this->request->getVar('service_id');
        $pageNumber = $this->request->getVar('pageNumber');
        $page_size  = $this->request->getVar('page_size');
        $column_name= $this->request->getVar('column_name');
        $sorting    = $this->request->getVar('sorting');
        $info = array(
            'branch_id'  => $branch_id,
            'doctor_id'  => $doctorId,
            'patient_id' => $patient_id,
            'service_id' => $service_id,
            'pageNumber' => $pageNumber,
            'page_size'  => $page_size,
            'column_name'=> $column_name,
            'sorting'    => $sorting
        );

        $date      = explode('-', $range);
        $from      = date('Y-m-d', strtotime(trim(!empty($date[0])?$date[0]:'')));
        $to        = date('Y-m-d', strtotime(trim(!empty($date[1])?$date[1]:'')));
        $data['range']      = $range;
        $data['setting']    = $this->bdtaskt1m17c7_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $result    = $this->bdtaskt1m17c7_01_pointRModel->bdtaskt1m17_01_getPointReports($info, $from,  $to, 'gain', false, $this->langColumn);
        // echo "<pre>";
        // print_r($result);die();
        $data['reports']      = $result['data'];
        $data['print_access'] = $this->permission->method('gain_points_report', 'print')->access();
        $data['export_access']= $this->permission->method('gain_points_report', 'export')->access();
        $data['doctor_id']    = $doctorId;
        $data['service_id']   = $service_id;
        $data['patient_id']   = $patient_id;
        $data['column_name']  = $column_name;
        $data['sorting']      = $sorting;
        $data['from']         = $from;
        $data['to']           = $to;

        $details = view('App\Modules\Reports\Views\point\gain_reports', $data);
        echo json_encode(array('info'=>$details, 'pageNumber'=>$pageNumber,'page_size'=>$page_size,'total'=>$result['num_rows']));
    }

    /*-----------------------------*
    | Export gain points
    *------------------------------*/
    public function bdtaskt1m17c7_03_exportGainReports() {
        $branch_id  = $this->request->getVar('branch_id');
        $doctor_id  = $this->request->getVar('doctor_id');
        $service_id  = $this->request->getVar('service_id');
        $patient_id  = $this->request->getVar('patient_id');
        $column_name= $this->request->getVar('column_name');
        $sorting    = $this->request->getVar('sorting');
         $info = array(
            'branch_id'  => $branch_id,
            'doctor_id'  => $doctor_id,
            'patient_id' => $patient_id,
            'service_id' => $service_id,
            'pageNumber' => 1,
            'page_size'  => 0,
            'column_name'=> $column_name,
            'sorting'    => $sorting
        );

        $from       = $this->request->getVar('from');
        $to         = $this->request->getVar('to');

        $expData = $this->bdtaskt1m17c7_01_pointRModel->bdtaskt1m17_01_getPointReports($info, $from,  $to, 'gain', true, $this->langColumn);
        // create file name
        $fileName = 'Gain_Points-Report-'.$from.'-to-'.$to.'.xlsx';  
        // load excel library
        $spreadsheet = new Spreadsheet();
 
        $sheet = $spreadsheet->getActiveSheet();
        // set Header
        $sheet->SetCellValue('A1', 'Patient Name');
        $sheet->SetCellValue('B1', 'File No');
        $sheet->SetCellValue('C1', 'Invoice No');
        $sheet->SetCellValue('D1', 'Service Name');
        $sheet->SetCellValue('E1', 'Code No');
        $sheet->SetCellValue('F1', 'Invoice Date');
        $sheet->SetCellValue('G1', 'Gain Points');
        $sheet->SetCellValue('H1', 'Value of amount');
        $sheet->SetCellValue('I1', 'Expiry date of this points');
        
        // set Row
        $rowCount = 2;
        foreach ($expData['data'] as $value) 
        {
            $amount = $value->redeem_per_point_value*$value->gain_points;
            $sheet->SetCellValue('A' . $rowCount, $value->patient_name);
            $sheet->SetCellValue('B' . $rowCount, $value->file_no);
            $sheet->SetCellValue('C' . $rowCount, $value->invoice_id);
            $sheet->SetCellValue('D' . $rowCount, $value->service_name);
            $sheet->SetCellValue('E' . $rowCount, $value->code_no);
            $sheet->SetCellValue('F' . $rowCount, $value->invoice_date);
            $sheet->SetCellValue('G' . $rowCount, $value->gain_points);
            $sheet->SetCellValue('H' . $rowCount, $amount);
            $sheet->SetCellValue('I' . $rowCount, date('F j, Y', strtotime('-29 day', strtotime($value->invoice_date))));
            $rowCount++;
        }
        
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. $fileName .'"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');  // download file 
        exit();
    }

    /*--------------------------
    | Redeem points report form
    *--------------------------*/
    public function bdtaskt1m17c7_04_redeemReports()
    {

        $data['title']      = get_phrases(['redeem', 'points']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "point/redeem_form";
        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get redeem points report
    *--------------------------*/
    public function bdtaskt1m17c7_05_getRedeemReports()
    {
        $branch_id  = $this->request->getVar('branch_id');
        $doctor_id  = $this->request->getVar('doctor_id');
        $patient_id = $this->request->getVar('patient_id');
        $range      = $this->request->getVar('date_range');
        $service_id = $this->request->getVar('service_id');
        $pageNumber = $this->request->getVar('pageNumber');
        $page_size  = $this->request->getVar('page_size');
        $column_name= $this->request->getVar('column_name');
        $sorting    = $this->request->getVar('sorting');
         $info = array(
            'branch_id'  => $branch_id,
            'doctor_id'  => $doctor_id,
            'patient_id' => $patient_id,
            'service_id' => $service_id,
            'pageNumber' => $pageNumber,
            'page_size'  => $page_size,
            'column_name'=> $column_name,
            'sorting'    => $sorting
        );

        $date      = explode('-', $range);
        $from      = date('Y-m-d', strtotime(trim(!empty($date[0])?$date[0]:'')));
        $to        = date('Y-m-d', strtotime(trim(!empty($date[1])?$date[1]:'')));
        $data['range']      = $range;
        $data['setting']    = $this->bdtaskt1m17c7_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $result    = $this->bdtaskt1m17c7_01_pointRModel->bdtaskt1m17_01_getPointReports($info, $from,  $to, 'redeem', false, $this->langColumn);
        // echo "<pre>";
        // print_r($result);die();
        $data['reports']      = $result['data'];
        $data['print_access'] = $this->permission->method('redeem_points_report', 'print')->access();
        $data['export_access']= $this->permission->method('redeem_points_report', 'export')->access();
        $data['doctor_id']    = $doctor_id;
        $data['service_id']   = $service_id;
        $data['patient_id']   = $patient_id;
        $data['column_name']  = $column_name;
        $data['sorting']      = $sorting;
        $data['from']         = $from;
        $data['to']           = $to;

        $details = view('App\Modules\Reports\Views\point\redeem_reports', $data);
        echo json_encode(array('info'=>$details, 'pageNumber'=>$pageNumber,'page_size'=>$page_size,'total'=>$result['num_rows']));
    }

     /*-----------------------------*
    | Export gain points
    *------------------------------*/
    public function bdtaskt1m17c7_06_exportRedeemReports() {
        $branch_id  = $this->request->getVar('branch_id');
        $doctor_id  = $this->request->getVar('doctor_id');
        $service_id  = $this->request->getVar('service_id');
        $patient_id  = $this->request->getVar('patient_id');
        $from       = $this->request->getVar('from');
        $to         = $this->request->getVar('to');
        $column_name= $this->request->getVar('column_name');
        $sorting    = $this->request->getVar('sorting');
         $info = array(
            'branch_id'  => $branch_id,
            'doctor_id'  => $doctor_id,
            'patient_id' => $patient_id,
            'service_id' => $service_id,
            'pageNumber' => 1,
            'page_size'  => 0,
            'column_name'=> $column_name,
            'sorting'    => $sorting
        );

        $expData = $this->bdtaskt1m17c7_01_pointRModel->bdtaskt1m17_01_getPointReports($info, $from,  $to, 'redeem', true, $this->langColumn);
        // create file name
        $fileName = 'Redeem_Points-Report-'.$from.'-to-'.$to.'.xlsx';  
        // load excel library
        $spreadsheet = new Spreadsheet();
 
        $sheet = $spreadsheet->getActiveSheet();
        // set Header
        $sheet->SetCellValue('A1', 'Patient Name');
        $sheet->SetCellValue('B1', 'File No');
        $sheet->SetCellValue('C1', 'Invoice No');
        $sheet->SetCellValue('D1', 'Service Name');
        $sheet->SetCellValue('E1', 'Code No');
        $sheet->SetCellValue('F1', 'Invoice Date');
        $sheet->SetCellValue('G1', 'Redeem Points');
        $sheet->SetCellValue('H1', 'Value of amount');
        $sheet->SetCellValue('I1', 'Remaining points');
        
        // set Row
        $rowCount = 2;
        foreach ($expData['data'] as $value) 
        {
            $sheet->SetCellValue('A' . $rowCount, $value->patient_name);
            $sheet->SetCellValue('B' . $rowCount, $value->file_no);
            $sheet->SetCellValue('C' . $rowCount, $value->invoice_id);
            $sheet->SetCellValue('D' . $rowCount, $value->service_name);
            $sheet->SetCellValue('E' . $rowCount, $value->code_no);
            $sheet->SetCellValue('F' . $rowCount, $value->invoice_date);
            $sheet->SetCellValue('G' . $rowCount, $value->redeem_points);
            $sheet->SetCellValue('H' . $rowCount, $value->redeem_discount);
            $sheet->SetCellValue('I' . $rowCount, $value->remaining_points);
            $rowCount++;
        }
        
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. $fileName .'"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');  // download file 
        exit();
    }
    
}
