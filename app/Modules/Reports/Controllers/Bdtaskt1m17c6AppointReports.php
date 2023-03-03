<?php namespace App\Modules\Reports\Controllers;
use CodeIgniter\Controller;
use App\Modules\Reports\Models\Bdtaskt1m17AppReportModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Bdtaskt1m17c6AppointReports extends BaseController
{
    private $bdtaskt1m17c6_01appRModel;
    private $bdtaskt1m17c6_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bdtaskt1m17c6_01appRModel = new Bdtaskt1m17AppReportModel();
        $this->bdtaskt1m17c6_02_CmModel = new Bdtaskt1m1CommonModel();
        if(session('defaultLang')=='english'){
            $this->langColumn = 'nameE';
        }else{
            $this->langColumn = 'nameA';
        }
        $this->permission = new Permission();
    }

    /*--------------------------
    | Appointment summary report form
    *--------------------------*/
    public function bdtaskt1m17c6_01_summaryReport()
    {

        $data['title']      = get_phrases(['appointment', 'summary']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "appointment/summary_form";
        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get appointment summary report
    *--------------------------*/
    public function bdtaskt1m17c6_02_getSummaryReport()
    {
        if(session('user_role')==14){
            $doctorId  = [session('id')];
        }else{
            $doctorId  = $this->request->getVar('doctor_id');
        }
        $range     = $this->request->getVar('date_range');

        $date      = explode('-', $range);
        $from      = date('Y-m-d', strtotime(trim(!empty($date[0])?$date[0]:'')));
        $to        = date('Y-m-d', strtotime(trim(!empty($date[1])?$date[1]:'')));
        $data['range']      = $range;
        $data['setting']    = $this->bdtaskt1m17c6_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $reports    = $this->bdtaskt1m17c6_01appRModel->bdtaskt1m17_01_getSummaryReport($doctorId, $from, $to, $this->langColumn);
        // echo "<pre>";
        // print_r($reports);exit();
        $array = [];
        foreach ($reports as $i => $value) {  
            $opened = abs($value->open_time/60);
            if($opened >0){
                $radio = ($value->real_duration*100)/$opened; 
                $no_show = ($value->no_show*100)/$value->total_appointment;  
                $array[$i]['radio'] = $radio;
                $array[$i]['opened'] = $opened;
                $array[$i]['doctor_name'] = $value->doctor_name;
                $array[$i]['real_duration'] = $value->real_duration;
                $array[$i]['total_appointment'] = $value->total_appointment;
                $array[$i]['first_visit'] = $value->first_visit;
                $array[$i]['follow'] = $value->follow;
                $array[$i]['return1'] = $value->return1;
                $array[$i]['pr_clinic'] = $value->pr_clinic;
                $array[$i]['pr_room'] = $value->pr_room;
                $array[$i]['no_show'] = $value->no_show;
                $array[$i]['no_show_per'] = $no_show;
                $array[$i]['waiting'] = $value->waiting;
            }
            
        }
        sort($array);
        $data['reports'] = $array;
        // echo "<pre>";
        // print_r($data['reports']);exit();

        $data['print_access'] = $this->permission->method('appointment_summary_report', 'print')->access();
        $data['export_access']= $this->permission->method('appointment_summary_report', 'export')->access();
        $data['doctor_id']    = $doctorId;
        $data['from']         = $from;
        $data['to']           = $to;

        $details = view('App\Modules\Reports\Views\appointment\summary_report', $data);
        echo json_encode(array('info'=>$details));
    }

    /*-----------------------------*
    | Export appointment summary
    *------------------------------*/
    public function bdtaskt1m17c6_03_exportSummaryReport() {
        $doctorId  = explode('-', $this->request->getVar('doctor_id'));
        $from       = $this->request->getVar('from');
        $to         = $this->request->getVar('to');

        $expData = $this->bdtaskt1m17c6_01appRModel->bdtaskt1m17_01_getSummaryReport($doctorId, $from, $to, $this->langColumn);
        // create file name
        $fileName = 'Appointment-Summary-Report-'.$from.'-to-'.$to.'.xlsx';  
        // load excel library
        $spreadsheet = new Spreadsheet();
 
        $sheet = $spreadsheet->getActiveSheet();
        // set Header
        $sheet->SetCellValue('A1', 'Doctor Name');
        $sheet->SetCellValue('B1', 'Appointment Opened');
        $sheet->SetCellValue('C1', 'Appointment Booked');
        $sheet->SetCellValue('D1', 'Booking Radio');
        $sheet->SetCellValue('E1', 'Appointment Booked No');
        $sheet->SetCellValue('F1', 'First Visit');
        $sheet->SetCellValue('G1', 'Follow Up');
        $sheet->SetCellValue('H1', 'Return');
        $sheet->SetCellValue('I1', 'Clinic Procedure');
        $sheet->SetCellValue('J1', 'Room Procedure');
        $sheet->SetCellValue('K1', 'No Show');
        $sheet->SetCellValue('L1', 'No Show %');
        $sheet->SetCellValue('M1', 'Appointment Waiting');
        
        // set Row
        $rowCount = 2;
        foreach ($expData as $value) 
        {
            $opened = abs($value->open_time/60);
            $radio = ($value->real_duration*100)/$opened;
            $no_show = ($value->no_show*100)/$value->total_appointment;

            $sheet->SetCellValue('A' . $rowCount, $value->doctor_name);
            $sheet->SetCellValue('B' . $rowCount, $opened);
            $sheet->SetCellValue('C' . $rowCount, $value->real_duration);
            $sheet->SetCellValue('D' . $rowCount, number_format($radio, 2).'%');
            $sheet->SetCellValue('E' . $rowCount, $value->total_appointment);
            $sheet->SetCellValue('F' . $rowCount, $value->first_visit);
            $sheet->SetCellValue('G' . $rowCount, $value->follow);
            $sheet->SetCellValue('H' . $rowCount, $value->return1);
            $sheet->SetCellValue('I' . $rowCount, $value->pr_clinic);
            $sheet->SetCellValue('J' . $rowCount, $value->pr_room);
            $sheet->SetCellValue('K' . $rowCount, $value->no_show);
            $sheet->SetCellValue('L' . $rowCount, number_format($no_show, 2).'%');
            $sheet->SetCellValue('M' . $rowCount, $value->waiting);
            $rowCount++;
        }
        
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. $fileName .'"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');  // download file 
    }

    /*--------------------------
    | Appointment no show report form
    *--------------------------*/
    public function bdtaskt1m17c6_04_detailsNoShow()
    {

        $data['title']      = get_phrases(['details', 'no', 'show']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "appointment/no_show_form";
        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get details no show report
    *--------------------------*/
    public function bdtaskt1m17c6_05_getDetailsNoShow()
    {
        if(session('user_role')==14){
            $doctorId  = [session('id')];
        }else{
            $doctorId  = $this->request->getVar('doctor_id');
        }
        $range     = $this->request->getVar('date_range');

        $date      = explode('-', $range);
        $from      = date('Y-m-d', strtotime(trim(!empty($date[0])?$date[0]:'')));
        $to        = date('Y-m-d', strtotime(trim(!empty($date[1])?$date[1]:'')));
        $data['range']      = $range;
        $data['setting']    = $this->bdtaskt1m17c6_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['reports']    = $this->bdtaskt1m17c6_01appRModel->bdtaskt1m17_02_getDetailsNoShowReport($doctorId, $from, $to, $this->langColumn);
        // echo "<pre>";
        // print_r($data['reports']);exit();
       
        $data['print_access'] = $this->permission->method('appoint_no_show_report', 'print')->access();
        $data['export_access']= $this->permission->method('appoint_no_show_report', 'export')->access();
        $data['doctor_id']    = !empty($doctorId)?$doctorId:[0];
        $data['from']         = $from;
        $data['to']           = $to;

        $details = view('App\Modules\Reports\Views\appointment\no_show_report', $data);
        echo json_encode(array('info'=>$details));
    }

    /*-----------------------------*
    | Export appointment summary
    *------------------------------*/
    public function bdtaskt1m17c6_06_exportNoShowReport() {
        $doctorId  = explode('-', $this->request->getVar('doctor_id'));
        $from       = $this->request->getVar('from');
        $to         = $this->request->getVar('to');

        $expData = $this->bdtaskt1m17c6_01appRModel->bdtaskt1m17_02_getDetailsNoShowReport($doctorId, $from, $to, $this->langColumn);
        // create file name
        $fileName = 'Appointment-No-Show-Report-'.$from.'-to-'.$to.'.xlsx';  
        // load excel library
        $spreadsheet = new Spreadsheet();
 
        $sheet = $spreadsheet->getActiveSheet();
        // set Header
        $sheet->SetCellValue('A1', 'Doctor Name');
        $sheet->SetCellValue('B1', 'Appointment Type');
        $sheet->SetCellValue('C1', 'Appointment Booked No');
        $sheet->SetCellValue('D1', 'No Show %');
        $sheet->SetCellValue('E1', 'First Visit');
        $sheet->SetCellValue('F1', 'No Show %');
        $sheet->SetCellValue('G1', 'Follow Up');
        $sheet->SetCellValue('H1', 'No Show %');
        $sheet->SetCellValue('I1', 'Return');
        $sheet->SetCellValue('J1', 'No Show %');
        $sheet->SetCellValue('K1', 'Clinic Procedure');
        $sheet->SetCellValue('L1', 'No Show %');
        $sheet->SetCellValue('M1', 'Room Procedure');
        $sheet->SetCellValue('N1', 'No Show %');
        
        // set Row
        $rowCount = 2;
        foreach ($expData as $value) 
        {
            if($value->total_appointment > 0){
                $no_show = ($value->no_show*100)/$value->total_appointment;
            }else{
                $no_show = 0;
            }
            if($value->first_visit > 0){
                $no_show_first = ($value->no_show_first*100)/$value->first_visit;
            }else{
                $no_show_first = 0;
            }
            if($value->follow > 0){
                $no_show_follow = ($value->no_show_follow*100)/$value->follow;
            }else{
                $no_show_follow = 0;
            }
            if($value->return1 > 0){
                $no_show_return = ($value->no_show_return*100)/$value->return1;
            }else{
                $no_show_return = 0;
            }
            if($value->pr_room > 0){
                $no_show_procedure = ($value->no_show_procedure*100)/$value->pr_room;
            }else{
                $no_show_procedure = 0;
            }
            if($value->total_appointment > 0){
                
            }
            if($value->pr_clinic > 0){
                $no_show_clinic = ($value->no_show_clinic*100)/$value->pr_clinic;
            }else{
                $no_show_clinic = 0;
            }

            $sheet->SetCellValue('A' . $rowCount, $value->doctor_name);
            $sheet->SetCellValue('B' . $rowCount, $value->type);
            $sheet->SetCellValue('C' . $rowCount, $value->total_appointment);
            $sheet->SetCellValue('D' . $rowCount, number_format($no_show, 2));
            $sheet->SetCellValue('E' . $rowCount, $value->first_visit);
            $sheet->SetCellValue('F' . $rowCount, number_format($no_show_first, 2));
            $sheet->SetCellValue('G' . $rowCount, $value->follow);
            $sheet->SetCellValue('H' . $rowCount, number_format($no_show_follow, 2));
            $sheet->SetCellValue('I' . $rowCount, $value->return1);
            $sheet->SetCellValue('J' . $rowCount, number_format($no_show_return, 2));
            $sheet->SetCellValue('K' . $rowCount, $value->pr_clinic);
            $sheet->SetCellValue('L' . $rowCount, number_format($no_show_clinic, 2));
            $sheet->SetCellValue('M' . $rowCount, $value->pr_room);
            $sheet->SetCellValue('N' . $rowCount, number_format($no_show_procedure, 2));
            $rowCount++;
        }
        
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. $fileName .'"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');  // download file 
    }
    
}
