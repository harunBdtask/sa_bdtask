<?php namespace App\Modules\Reports\Controllers;
use CodeIgniter\Controller;
use App\Modules\Reports\Models\Bdtaskt1m17InventoryReportModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Bdtaskt1m17c2InventoryReports extends BaseController
{
    private $bdtaskt1m17c2_01_rptModel;
    private $bdtaskt1m17c2_02_CmModel;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m17c2_01_rptModel = new Bdtaskt1m17InventoryReportModel();
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
    public function bdtaskt1m17c2_01_itemStock()
    {

        $data['title']      = get_phrases(['item', 'stock','of','main','store']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "item_stock/bdtaskt1m17_item_stock";

        $data['branch_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c2_02_getItemStock()
    { 
        $store_id = $this->request->getVar('store_id');
        $item_id = $this->request->getVar('item_id');

        $data['hasPrintAccess']        = $this->permission->method('inventory_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('inventory_reports', 'export')->access();
        $data['setting']    = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_01_getItemStock($store_id, $item_id);
        $income = view('App\Modules\Reports\Views\item_stock\bdtaskt1m17_item_stock_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | item stock form
    *--------------------------*/
    public function bdtaskt1m17c2_19_itemStockAll()
    {
        $data['title']      = get_phrases(['item', 'stock','of','all','store']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "item_stock_all/bdtaskt1m17_item_stock";

        $data['supplier_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_supplier_information', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c2_20_getItemStockAll($pageNumber)
    { 
        //sleep(3);
        $page_size = $this->request->getVar('page_size');
        $store = $this->request->getVar('store');
        $supplier_id = $this->request->getVar('supplier_id');
        $item_id = $this->request->getVar('item_id');
        $sorting = $this->request->getVar('sorting');
        $direction = $this->request->getVar('direction');

        $data['hasPrintAccess'] = $this->permission->method('inventory_reports', 'print')->access();
        $data['hasExportAccess'] = $this->permission->method('inventory_reports', 'export')->access();
        $data['setting'] = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $result  = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_12_getItemStockAll($supplier_id, $item_id, $pageNumber, $page_size);

        $data['results'] = $result['data'];
        $data['store'] = $store;
        $data['sorting'] = $sorting;
        $data['direction'] = $direction;
        $income = view('App\Modules\Reports\Views\item_stock_all\bdtaskt1m17_item_stock_reports', $data);
        echo json_encode(array('data'=>$income,'pageNumber'=>$pageNumber,'page_size'=>$page_size,'total'=>$result['num_rows']));
    }

    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c2_21_getItemStockDetailsAllById()
    { 
        $item_id = $this->request->getVar('item_id');
        //$store_id = $this->request->getVar('store_id');
        $data['results']   = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_13_getItemStockDetailsAllById($item_id);
        $data['hasPrintAccess']        = $this->permission->method('inventory_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('inventory_reports', 'export')->access();
        $income = view('App\Modules\Reports\Views\item_stock_all\bdtaskt1m17_item_stock_details', $data);
        echo json_encode(array('html'=>$income));
    }
    
    /*--------------------------
    | item receive form
    *--------------------------*/
    public function bdtaskt1m17c2_09_itemReceive()
    {

        $data['title']      = get_phrases(['item', 'receive','from','supplier']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "item_receive/bdtaskt1m17_item_receive";

        $data['branch_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('status'=>1));
        $data['supplier_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_supplier_information', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item receive all
    *--------------------------*/
    public function bdtaskt1m17c2_10_getItemReceive()
    { 
        $store_id = $this->request->getVar('store_id');
        $supplier_id = $this->request->getVar('supplier_id');
        $item_id = $this->request->getVar('item_id');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';
        $data['setting']   = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_05_getItemReceive($store_id, $supplier_id, $item_id, $from, $to);
        $data['hasPrintAccess']        = $this->permission->method('inventory_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('inventory_reports', 'export')->access();
        $income = view('App\Modules\Reports\Views\item_receive\bdtaskt1m17_item_receive_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | supplier return form
    *--------------------------*/
    public function bdtaskt1m17c2_17_supplierReturn()
    {

        $data['title']      = get_phrases(['item', 'return','to','supplier']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "supplier_return/bdtaskt1m17_supplier_return";

        $data['branch_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('status'=>1));
        $data['supplier_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_supplier_information', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get supplier return all
    *--------------------------*/
    public function bdtaskt1m17c2_18_getSupplierReturn()
    { 
        $store_id = $this->request->getVar('store_id');
        $supplier_id = $this->request->getVar('supplier_id');
        $item_id = $this->request->getVar('item_id');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';
        $data['setting']   = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_10_getSupplierReturn($store_id, $supplier_id, $item_id, $from, $to);
        $data['hasPrintAccess']        = $this->permission->method('inventory_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('inventory_reports', 'export')->access();
        $income = view('App\Modules\Reports\Views\supplier_return\bdtaskt1m17_supplier_return_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | item sub stock form
    *--------------------------*/
    public function bdtaskt1m17c2_07_itemSubStock()
    {

        $data['title']      = get_phrases(['item', 'stock','of','sub','store']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "item_sub_stock/bdtaskt1m17_item_sub_stock";

        $data['branch_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item sub stock
    *--------------------------*/
    public function bdtaskt1m17c2_08_getItemSubStock()
    { 
        $sub_store_id = $this->request->getVar('sub_store_id');
        $item_id = $this->request->getVar('item_id');

        $data['hasPrintAccess']        = $this->permission->method('inventory_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('inventory_reports', 'export')->access();
        $data['setting']    = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_04_getItemSubStock($sub_store_id, $item_id);
        $income = view('App\Modules\Reports\Views\item_sub_stock\bdtaskt1m17_item_sub_stock_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | sub store item receive form
    *--------------------------*/
    public function bdtaskt1m17c2_03_itemSubReceive()
    {

        $data['title']      = get_phrases(['item', 'receive','from','store']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "item_sub_receive/bdtaskt1m17_item_sub_receive";

        $data['branch_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get sub store item receive all
    *--------------------------*/
    public function bdtaskt1m17c2_04_getItemSubReceive()
    { 
        $sub_store_id = $this->request->getVar('sub_store_id');
        $item_id = $this->request->getVar('item_id');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';
        $data['setting']   = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_02_getItemSubReceive($sub_store_id, $item_id, $from, $to);
        $data['hasPrintAccess']        = $this->permission->method('inventory_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('inventory_reports', 'export')->access();
        $income = view('App\Modules\Reports\Views\item_sub_receive\bdtaskt1m17_item_sub_receive_reports', $data);
        echo json_encode(array('data'=>$income));
    }
   
    /*--------------------------
    | item transfer form
    *--------------------------*/
    public function bdtaskt1m17c2_11_itemTransfer()
    {

        $data['title']      = get_phrases(['sub','store', 'transfer']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "item_transfer/bdtaskt1m17_item_transfer";

        $data['branch_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get all item transfer
    *--------------------------*/
    public function bdtaskt1m17c2_12_getItemTransfer()
    { 
        $store_id = $this->request->getVar('store_id');
        $item_id = $this->request->getVar('item_id');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';
        $data['setting']   = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_06_getItemTransfer($store_id, $item_id, $from, $to);
        $data['hasPrintAccess']        = $this->permission->method('inventory_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('inventory_reports', 'export')->access();
        $income = view('App\Modules\Reports\Views\item_transfer\bdtaskt1m17_item_transfer_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | item consumption form
    *--------------------------*/
    public function bdtaskt1m17c2_05_itemConsumption()
    {
        $data['title']      = get_phrases(['item', 'consumption']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "item_consumption/bdtaskt1m17_item_consumption";

        $data['branch_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('status'=>1));
        $data['doctor_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('employees', array('status'=>1, 'job_title_id'=>14));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item consumption
    *--------------------------*/
    public function bdtaskt1m17c2_06_getItemConsumption()
    { 
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
        $data['setting']   = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_03_getItemConsumption($sub_store_id, $item_id, $doctor_id, $from, $to, $invoice_from, $invoice_to);
        $data['hasPrintAccess'] = $this->permission->method('inventory_reports', 'print')->access();
        $data['hasExportAccess'] = $this->permission->method('inventory_reports', 'export')->access();
        $data['sorting'] = $sorting;
        $data['direction'] = $direction;

        $income = view('App\Modules\Reports\Views\item_consumption\bdtaskt1m17_item_consumption_reports', $data);
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

    /*--------------------------
    | item return form
    *--------------------------*/
    public function bdtaskt1m17c2_19_itemReturn()
    {

        $data['title']      = get_phrases(['item', 'return','to','sub','store']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "item_return/bdtaskt1m17_item_return";

        $data['branch_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item return all
    *--------------------------*/
    public function bdtaskt1m17c2_20_getItemReturn()
    { 
        $store_id = $this->request->getVar('store_id');
        $item_id = $this->request->getVar('item_id');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';
        $data['setting']   = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_11_getItemReturn($store_id, $item_id, $from, $to);
        $data['hasPrintAccess']        = $this->permission->method('inventory_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('inventory_reports', 'export')->access();
        $income = view('App\Modules\Reports\Views\item_return\bdtaskt1m17_item_return_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | supplier payment form
    *--------------------------*/
    public function bdtaskt1m17c2_13_supplierPayment()
    {

        $data['title']      = get_phrases(['supplier', 'payment']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "supplier_payment/bdtaskt1m17_supplier_payment";

        $data['supplier_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_supplier_information', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get all supplier payment
    *--------------------------*/
    public function bdtaskt1m17c2_14_getSupplierPayment()
    { 
        $supplier_id = $this->request->getVar('supplier_id');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';
        $data['setting']   = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_07_getSupplierPayment($supplier_id, $from, $to);
        $data['hasPrintAccess']        = $this->permission->method('inventory_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('inventory_reports', 'export')->access();
        $income = view('App\Modules\Reports\Views\supplier_payment\bdtaskt1m17_supplier_payment_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c2_15_getItemReceiveDetailsById()
    { 
        $item_id = $this->request->getVar('item_id');
        $store_id = $this->request->getVar('store_id');
        $data['results']   = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_08_getItemReceiveDetailsById($item_id, $store_id);
        $data['hasPrintAccess']        = $this->permission->method('inventory_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('inventory_reports', 'export')->access();
        $income = view('App\Modules\Reports\Views\item_stock\bdtaskt1m17_item_receive_details', $data);
        echo json_encode(array('html'=>$income));
    }
    
    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c2_16_getSubStoreItemReceiveDetails()
    { 
        $item_id = $this->request->getVar('item_id');
        $store_id = $this->request->getVar('store_id');
        $data['results']   = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_09_getSubStoreItemReceiveDetails($item_id, $store_id);
        $data['hasPrintAccess']        = $this->permission->method('inventory_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('inventory_reports', 'export')->access();
        $income = view('App\Modules\Reports\Views\item_sub_stock\bdtaskt1m17_item_receive_details', $data);
        echo json_encode(array('html'=>$income));
    }

    /*--------------------------
    | Get warehouse list
    *--------------------------*/
    public function bdtaskt1m17c2_17_getWarehouseListByBranchId()
    { 
        $branch_id = $this->request->getVar('branch_id');
        $data   = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_production_store', array('status'=>1, 'branch_id'=>$branch_id));
        $store = "<option value=''>Select</option>";
        foreach($data as $row){
            $store .= "<option value='".$row->id."'>".$row->nameE."</option>";
        }
        $data   = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_sale_store', array('status'=>1, 'branch_id'=>$branch_id));
        $sub_store = "<option value=''>Select</option>";
        foreach($data as $row){
            $sub_store .= "<option value='".$row->id."'>".$row->nameE."</option>";
        }

        echo json_encode(array('store'=>$store, 'sub_store'=>$sub_store));
    }

    /*--------------------------
    | Get warehouse list
    *--------------------------*/
    public function bdtaskt1m17c2_18_getSubWarehouseListByBranchId()
    { 
        $branch_id = $this->request->getVar('branch_id');
        $data   = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_sale_store', array('status'=>1, 'branch_id'=>$branch_id));
        $html = '<option value="">Select</option>';
        foreach($data as $row){
            $html .= '<option value="'.$row->id.'">'.$row->nameE.'</option>';
        }

        echo $html;
    }

    /*--------------------------
    | item stock form
    *--------------------------*/
    public function bdtaskt1m17c2_19_lowStock()
    {

        $data['title']      = get_phrases(['low', 'stock','item','list']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "low_stock/bdtaskt1m17_low_stock";

        $data['branch_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c2_20_getLowStock()
    { 
        $store_id = $this->request->getVar('store_id');
        $store_id = $this->request->getVar('store_id');

        $data['setting']    = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_12_getLowStock($store_id, $store_id);

        $data['hasPrintAccess']        = $this->permission->method('inventory_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('inventory_reports', 'export')->access();
        
        $income = view('App\Modules\Reports\Views\low_stock\bdtaskt1m17_low_stock_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | item stock form
    *--------------------------*/
    public function bdtaskt1m17c2_21_outOfStock()
    {

        $data['title']      = get_phrases(['out','of', 'stock','item','list']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "out_of_stock/bdtaskt1m17_out_of_stock";

        $data['branch_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c2_22_getOutofStock()
    { 
        $store_id = $this->request->getVar('store_id');
        $store_id = $this->request->getVar('store_id');

        $data['setting']    = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_13_getOutofStock($store_id, $store_id);

        $data['hasPrintAccess']        = $this->permission->method('inventory_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('inventory_reports', 'export')->access();
        
        $income = view('App\Modules\Reports\Views\out_of_stock\bdtaskt1m17_out_of_stock_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | item stock form
    *--------------------------*/
    public function bdtaskt1m17c2_25_expiredItem()
    {

        $data['title']      = get_phrases(['expired','item','list']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "expired_item/bdtaskt1m17_expired_item";

        $data['branch_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c2_26_getExpiredItem()
    { 
        $store_id = $this->request->getVar('store_id');
        $store_id = $this->request->getVar('store_id');

        $data['setting']    = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_15_getExpiredItem($store_id, $store_id);

        $data['hasPrintAccess']        = $this->permission->method('inventory_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('inventory_reports', 'export')->access();
        
        $income = view('App\Modules\Reports\Views\expired_item\bdtaskt1m17_expired_item_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | item stock form
    *--------------------------*/
    public function bdtaskt1m17c2_27_itemCloseExpiry()
    {

        $data['title']      = get_phrases(['item','list','close','to','expiry',]);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "item_close_to_expiry/bdtaskt1m17_item_close_to_expiry";

        $data['branch_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['supplier_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_supplier_information', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c2_28_getItemCloseExpiry()
    { 
        $store_id = $this->request->getVar('store_id');
        $store_id = $this->request->getVar('store_id');
        $supplier_id = $this->request->getVar('supplier_id');
        $period = $this->request->getVar('period');

        $data['setting']    = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_16_getItemCloseExpiry($store_id, $store_id, $supplier_id, $period);

        $data['hasPrintAccess']        = $this->permission->method('inventory_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('inventory_reports', 'export')->access();
        
        $income = view('App\Modules\Reports\Views\item_close_to_expiry\bdtaskt1m17_item_close_to_expiry_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | supplier payment form
    *--------------------------*/
    public function bdtaskt1m17c2_29_supplierAging()
    {

        $data['title']      = get_phrases(['supplier', 'aging']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "supplier_aging/bdtaskt1m17_supplier_aging";

        $data['supplier_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_supplier_information', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get all supplier payment
    *--------------------------*/
    public function bdtaskt1m17c2_30_getSupplierAging()
    { 
        $supplier_id = $this->request->getVar('supplier_id');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';
        $data['setting']   = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_17_getSupplierAging($supplier_id, $from, $to);

        $data['hasPrintAccess']        = $this->permission->method('pharmacy_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('pharmacy_reports', 'export')->access();
        
        $income = view('App\Modules\Reports\Views\supplier_aging\bdtaskt1m17_supplier_aging_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | item consumption form
    *--------------------------*/
    public function bdtaskt1m17c2_31_itemConsumption_vs()
    {
        $data['title']      = get_phrases(['actual','vs', 'default','consumption']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "item_consumption_vs/bdtaskt1m17_item_consumption";

        //$data['branch_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        //$data['item_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('status'=>1));
        $data['doctor_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('employees', array('status'=>1, 'job_title_id'=>14));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item consumption
    *--------------------------*/
    public function bdtaskt1m17c2_32_getItemConsumption_vs($pageNumber)
    { 
        $doctor_id = $this->request->getVar('doctor_id');
        $service_id = $this->request->getVar('service_id');
        $range  = $this->request->getVar('date_range');
        $status  = $this->request->getVar('status');
        $page_size = $this->request->getVar('page_size');

        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';
        $data['setting']   = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $result   = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_18_getItemConsumption_vs($doctor_id, $service_id, $status, $from, $to, $pageNumber, $page_size);
        $data['results'] = $result['data'];
        $data['hasPrintAccess'] = $this->permission->method('inventory_reports', 'print')->access();
        $data['hasExportAccess'] = $this->permission->method('inventory_reports', 'export')->access();
        //$data['sorting'] = $sorting;
        $data['status'] = $status;

        $income = view('App\Modules\Reports\Views\item_consumption_vs\bdtaskt1m17_item_consumption_reports', $data);
        echo json_encode(array('data'=>$income,'pageNumber'=>$pageNumber,'page_size'=>$page_size,'total'=>$result['num_rows']));
    }

    /*--------------------------
    | Get warehouse list
    *--------------------------*/
    public function bdtaskt1m17c2_33_getServiceListByDoctorId()
    { 
        $doctor_id = $this->request->getVar('doctor_id');
        $data   = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_19_getServiceListByDoctorId($doctor_id);
        $html = '<option value="">Select</option>';
        foreach($data as $row){
            $html .= '<option value="'.$row->id.'">'.$row->service_code.'-'.$row->service_name.'</option>';
        }

        echo $html;
    }
    /*--------------------------
    | item stock form
    *--------------------------*/
    public function bdtaskt1m17c2_34_itemStockHistory()
    {
        $data['title']      = get_phrases(['item', 'stock','reflection']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "item_stock_history/bdtaskt1m17_item_stock";

        $data['store_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_production_store', array('status'=>1, 'branch_id'=>session('branchId') ));
        $data['sub_store_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_sale_store', array('status'=>1, 'branch_id'=>session('branchId')));
        $data['item_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c2_35_getItemStockHistory($pageNumber)
    { 
        //sleep(3);
        $page_size = $this->request->getVar('page_size');
        $store = $this->request->getVar('store');
        $sub_store = $this->request->getVar('sub_store');
        $item_id = $this->request->getVar('item_id');

        $data['hasPrintAccess'] = $this->permission->method('inventory_reports', 'print')->access();
        $data['hasExportAccess'] = $this->permission->method('inventory_reports', 'export')->access();
        $data['setting'] = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $result  = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_20_getItemStockHistory($store, $sub_store, $item_id, $pageNumber, $page_size);

        $data['results'] = $result['data'];
        
        $income = view('App\Modules\Reports\Views\item_stock_history\bdtaskt1m17_item_stock_reports', $data);
        echo json_encode(array('data'=>$income,'pageNumber'=>$pageNumber,'page_size'=>$page_size,'total'=>$result['num_rows']));
    }

    /*--------------------------
    | supplier balance form
    *--------------------------*/
    public function bdtaskt1m17c2_36_supplierBalance()
    {

        $data['title']      = get_phrases(['supplier', 'balance']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "supplier_balance/bdtaskt1m17_supplier_balance";

        $data['supplier_list']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_supplier_information', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get all supplier balance
    *--------------------------*/
    public function bdtaskt1m17c2_37_getSupplierBalance()
    { 
        $supplier_id = $this->request->getVar('supplier_id');
        /*$range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';*/
        $data['setting']   = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c2_01_rptModel->bdtaskt1m17_21_getSupplierBalance($supplier_id);//, $from, $to
        $data['hasPrintAccess']        = $this->permission->method('inventory_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('inventory_reports', 'export')->access();
        $income = view('App\Modules\Reports\Views\supplier_balance\bdtaskt1m17_supplier_balance_reports', $data);
        echo json_encode(array('data'=>$income));
    }
}
