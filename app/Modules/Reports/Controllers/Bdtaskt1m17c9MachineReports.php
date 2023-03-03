<?php namespace App\Modules\Reports\Controllers;
use CodeIgniter\Controller;
use App\Modules\Reports\Models\Bdtaskt1m17MachineReportModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Bdtaskt1m17c9MachineReports extends BaseController
{
    private $bdtaskt1m17c2_01_rptModel;
    private $bdtaskt1m17c2_02_CmModel;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m17c2_01_rptModel = new Bdtaskt1m17MachineReportModel();
        $this->bdtaskt1m17c2_02_CmModel = new Bdtaskt1m1CommonModel();
        if(session('defaultLang')=='english'){
            $this->langColumn = 'nameE';
        }else{
            $this->langColumn = 'nameA';
        }
    }

    /*--------------------------
    | item stock form
    *--------------------------*/
    public function bdtaskt1m17c9_01_dailyConsumption()
    {

        $data['title']      = get_phrases(['daily','consumption']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "daily_consumption/form";

        //$data['machine_store_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_machine_store', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c9_02_getDailyConsumption()
    { 
        //$machine_id = $this->request->getVar('machine_id');
        $range = $this->request->getVar('date');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';

        $data['hasPrintAccess']        = $this->permission->method('daily_consumption', 'print')->access();
        $data['hasExportAccess']        = 0;//$this->permission->method('daily_consumption', 'export')->access();
        $data['setting']    = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_01_getDailyConsumption($from, $to);
        $income = view('App\Modules\Reports\Views\daily_consumption\reports', $data);
        echo json_encode(array('data'=>$income));
    }
    /*--------------------------
    | item stock form
    *--------------------------*/
    public function bdtaskt1m17c9_03_plantConsumption()
    {

        $data['title']      = get_phrases(['plant','consumption']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "plant_consumption/form";

        $data['plant_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_machine_store', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c9_04_getPlantConsumption()
    { 
        $machine_id = $this->request->getVar('machine_id');
        $range = $this->request->getVar('date');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';

        $data['hasPrintAccess']        = $this->permission->method('plant_consumption', 'print')->access();
        $data['hasExportAccess']        = 0;//$this->permission->method('plant_consumption', 'export')->access();
        $data['setting']    = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_02_getPlantConsumption($machine_id, $from, $to);
        $income = view('App\Modules\Reports\Views\plant_consumption\reports', $data);
        echo json_encode(array('data'=>$income));
    }


    public function bdtaskt1m17c2_31_getItemConsumptionExcel() {
        
        $sub_store_id = $this->request->getVar('sub_store_id');
        $item_id = $this->request->getVar('item_id');
        $doctor_id = $this->request->getVar('doctor_id');
        $range  = $this->request->getVar('date_range');
        $invoice_date  = $this->request->getVar('invoice_date');
        $sorting  = $this->request->getVar('sorting');
        $direction  = $this->request->getVar('direction');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';

        $invoice_date2   = explode('-', $invoice_date);
        $invoice_from   = (!empty($invoice_date2[0]))?date('Y-m-d', strtotime(trim($invoice_date2[0]))):'';
        $invoice_to     = (!empty($invoice_date2[1]))?date('Y-m-d', strtotime(trim($invoice_date2[1]))):'';
        
        $expData = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_03_getItemConsumption($sub_store_id, $item_id, $doctor_id, $from, $to, $invoice_from, $invoice_to);
        // create file name
        $fileName = 'Item-Consumption-Reports-'.$from.'-to-'.$to.'.xlsx';  
        // load excel library
        $spreadsheet = new Spreadsheet();
 
        $sheet = $spreadsheet->getActiveSheet();
        // set Header
        $sheet->SetCellValue('A1', get_phrases(['item', 'code']));
        $sheet->SetCellValue('B1', get_phrases(['item', 'name']));
        $sheet->SetCellValue('C1', get_phrases(['voucher','no']));
        $sheet->SetCellValue('D1', get_phrases(['approve','date']));
        $sheet->SetCellValue('E1', get_phrases(['store']));
        $sheet->SetCellValue('F1', get_phrases(['department'])); 
        $sheet->SetCellValue('G1', get_phrases(['doctor']));
        $sheet->SetCellValue('H1', get_phrases(['service','code']));
        $sheet->SetCellValue('I1', get_phrases(['patient','file']));
        $sheet->SetCellValue('J1', get_phrases(['invoice','no']));
        $sheet->SetCellValue('K1', get_phrases(['quantity']));
        $sheet->SetCellValue('L1', get_phrases(['base','unit']));    
        $sheet->SetCellValue('M1', get_phrases(['unit','cost']));       
        $sheet->SetCellValue('N1', get_phrases(['total','cost']));   

        // set Row
        $rowCount = 2;
        $total = 0;
        $total_qty = 0;
        $all_data = array();

        foreach ($expData as $value) 
        {
            
              $qty = $value->aqty - $value->return_qty;
              if($qty <0 ){
                $qty = 0;
              }

              $total += $qty * $value->price;
              $total_qty += $qty;

              $data['id'] = $value->id;
              $data['company_code'] = $value->company_code;
              $data['item_name'] = $value->item_name;
              $data['approved_date'] = $value->approved_date;
              $data['store_name'] = $value->store_name;
              $data['dept_name'] = $value->dept_name;
              $data['doctor_name'] = $value->doctor_name;
              $data['code_no'] = $value->code_no;
              $data['file_no'] = $value->file_no;
              $data['voucher_no'] = $value->voucher_no;
              $data['invoice_id'] = $value->invoice_id;
              $data['qty'] = $qty;
              $data['unit_name'] = $value->unit_name;
              $data['price'] = $value->price;
              $data['total'] = $qty * $value->price;
        
              if($sorting =='company_code'){
                $needle[] = $data['company_code'];
              } else if($sorting =='item_name'){
                $needle[] = $data['item_name'];
              } else if($sorting =='approved_date'){
                $needle[] = $data['approved_date'];
              } else if($sorting =='store_name'){
                $needle[] = $data['store_name'];
              } else if($sorting =='dept_name'){
                $needle[] = $data['dept_name'];
              } else if($sorting =='doctor_name'){
                $needle[] = $data['doctor_name'];
              } else if($sorting =='code_no'){
                $needle[] = $data['code_no'];
              } else if($sorting =='file_no'){
                $needle[] = $data['file_no'];
              } else if($sorting =='voucher_no'){
                $needle[] = $data['voucher_no'];
              } else if($sorting =='quantity'){
                $needle[] = $data['qty'];
              } else if($sorting =='price'){
                $needle[] = $data['price'];
              } else if($sorting =='total'){
                $needle[] = $data['total'];
              } else {
                $needle[] = $data['id'];
              }


            $all_data[] = $data;
        }

          if($sorting !='' && !empty($all_data)){
            if($direction =='DESC'){
              $direction = SORT_DESC;
            } else {              
              $direction = SORT_ASC;
            }
            array_multisort($needle, $direction, $all_data);
          }

        foreach ($all_data as $value) 
        {

            $sheet->SetCellValue('A' . $rowCount, $value['company_code']);
            $sheet->SetCellValue('B' . $rowCount, $value['item_name']);
            $sheet->SetCellValue('C' . $rowCount, $value['voucher_no']);
            $sheet->SetCellValue('D' . $rowCount, $value['approved_date']);
            $sheet->SetCellValue('E' . $rowCount, $value['store_name']);
            $sheet->SetCellValue('F' . $rowCount, $value['dept_name']);
            $sheet->SetCellValue('G' . $rowCount, $value['doctor_name']);
            $sheet->SetCellValue('H' . $rowCount, $value['code_no']);
            $sheet->SetCellValue('I' . $rowCount, $value['file_no']);
            $sheet->SetCellValue('J' . $rowCount, $value['invoice_id']);
            $sheet->SetCellValue('K' . $rowCount, $value['qty']);
            $sheet->SetCellValue('L' . $rowCount, $value['unit_name']);
            $sheet->SetCellValue('M' . $rowCount, $value['price']);
            $sheet->SetCellValue('N' . $rowCount, $value['total']);

            $rowCount++;
        }
        
        $writer = new Xlsx($spreadsheet);
        // push to the file
        $writer->save("assets/excel_data/".$fileName);
        //header("Content-Type: application/vnd.ms-excel");

        //header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="'. $fileName .'"'); 
        // header('Cache-Control: max-age=0');
        
        // $writer->save('php://output');  // download file 
        echo json_encode(array('url'=>base_url().'/assets/excel_data/'.$fileName));
    }

}
