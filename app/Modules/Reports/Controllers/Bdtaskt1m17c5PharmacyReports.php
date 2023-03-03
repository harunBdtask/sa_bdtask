<?php namespace App\Modules\Reports\Controllers;
use CodeIgniter\Controller;
use App\Modules\Reports\Models\Bdtaskt1m17PharmacyReportModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1m17c5PharmacyReports extends BaseController
{
    private $bdtaskt1m17c5_01_rptModel;
    private $bdtaskt1m17c5_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m17c5_01_rptModel = new Bdtaskt1m17PharmacyReportModel();
        $this->bdtaskt1m17c5_02_CmModel = new Bdtaskt1m1CommonModel();
        if(session('defaultLang')=='english'){
            $this->langColumn = 'nameE';
        }else{
            $this->langColumn = 'nameA';
        }
    }

    /*--------------------------
    | item stock form
    *--------------------------*/
    public function bdtaskt1m17c5_01_itemStock()
    {

        $data['title']      = get_phrases(['item', 'stock','of','main','store']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "pharmacy/item_stock/bdtaskt1m17_item_stock";

        $data['branch_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_items', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c5_02_getItemStock()
    { 
        $store_id = $this->request->getVar('store_id');
        $item_id = $this->request->getVar('item_id');

        $data['setting']    = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c5_01_rptModel->bdtaskt1m17_01_getItemStock($store_id, $item_id);

        $data['hasPrintAccess']        = $this->permission->method('pharmacy_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('pharmacy_reports', 'export')->access();

        $income = view('App\Modules\Reports\Views\pharmacy\item_stock\bdtaskt1m17_item_stock_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | item receive form
    *--------------------------*/
    public function bdtaskt1m17c5_09_itemReceive()
    {

        $data['title']      = get_phrases(['item', 'receive','from','supplier']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "pharmacy/item_receive/bdtaskt1m17_item_receive";

        $data['branch_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_items', array('status'=>1));
        $data['supplier_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_supplier_information', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item receive all
    *--------------------------*/
    public function bdtaskt1m17c5_10_getItemReceive()
    { 
        $store_id = $this->request->getVar('store_id');
        $supplier_id = $this->request->getVar('supplier_id');
        $item_id = $this->request->getVar('item_id');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';
        $data['setting']   = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c5_01_rptModel->bdtaskt1m17_05_getItemReceive($store_id, $supplier_id, $item_id, $from, $to);

        $data['hasPrintAccess']        = $this->permission->method('pharmacy_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('pharmacy_reports', 'export')->access();
        
        $income = view('App\Modules\Reports\Views\pharmacy\item_receive\bdtaskt1m17_item_receive_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | supplier return form
    *--------------------------*/
    public function bdtaskt1m17c5_17_supplierReturn()
    {

        $data['title']      = get_phrases(['item', 'return','to','supplier']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "pharmacy/supplier_return/bdtaskt1m17_supplier_return";

        $data['branch_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_items', array('status'=>1));
        $data['supplier_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_supplier_information', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get supplier return all
    *--------------------------*/
    public function bdtaskt1m17c5_18_getSupplierReturn()
    { 
        $store_id = $this->request->getVar('store_id');
        $supplier_id = $this->request->getVar('supplier_id');
        $item_id = $this->request->getVar('item_id');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';
        $data['setting']   = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c5_01_rptModel->bdtaskt1m17_10_getSupplierReturn($store_id, $supplier_id, $item_id, $from, $to);

        $data['hasPrintAccess']        = $this->permission->method('pharmacy_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('pharmacy_reports', 'export')->access();
        
        $income = view('App\Modules\Reports\Views\pharmacy\supplier_return\bdtaskt1m17_supplier_return_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | item sub stock form
    *--------------------------*/
    public function bdtaskt1m17c5_07_itemSubStock()
    {

        $data['title']      = get_phrases(['item', 'stock','of','sub','store']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "pharmacy/item_sub_stock/bdtaskt1m17_item_sub_stock";

        $data['branch_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_items', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item sub stock
    *--------------------------*/
    public function bdtaskt1m17c5_08_getItemSubStock()
    { 
        $sub_store_id = $this->request->getVar('sub_store_id');
        $item_id = $this->request->getVar('item_id');

        $data['setting']    = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c5_01_rptModel->bdtaskt1m17_04_getItemSubStock($sub_store_id, $item_id);

        $data['hasPrintAccess']        = $this->permission->method('pharmacy_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('pharmacy_reports', 'export')->access();
        
        $income = view('App\Modules\Reports\Views\pharmacy\item_sub_stock\bdtaskt1m17_item_sub_stock_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | sub store item receive form
    *--------------------------*/
    public function bdtaskt1m17c5_03_itemSubReceive()
    {

        $data['title']      = get_phrases(['item', 'receive','from','store']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "pharmacy/item_sub_receive/bdtaskt1m17_item_sub_receive";

        $data['branch_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_items', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get sub store item receive all
    *--------------------------*/
    public function bdtaskt1m17c5_04_getItemSubReceive()
    { 
        $sub_store_id = $this->request->getVar('sub_store_id');
        $item_id = $this->request->getVar('item_id');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';
        $data['setting']   = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c5_01_rptModel->bdtaskt1m17_02_getItemSubReceive($sub_store_id, $item_id, $from, $to);

        $data['hasPrintAccess']        = $this->permission->method('pharmacy_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('pharmacy_reports', 'export')->access();
        
        $income = view('App\Modules\Reports\Views\pharmacy\item_sub_receive\bdtaskt1m17_item_sub_receive_reports', $data);
        echo json_encode(array('data'=>$income));
    }
   
    /*--------------------------
    | item transfer form
    *--------------------------*/
    public function bdtaskt1m17c5_11_itemTransfer()
    {

        $data['title']      = get_phrases(['sub','store', 'transfer']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "pharmacy/item_transfer/bdtaskt1m17_item_transfer";

        $data['branch_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_items', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get all item transfer
    *--------------------------*/
    public function bdtaskt1m17c5_12_getItemTransfer()
    { 
        $store_id = $this->request->getVar('store_id');
        $item_id = $this->request->getVar('item_id');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';
        $data['setting']   = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c5_01_rptModel->bdtaskt1m17_06_getItemTransfer($store_id, $item_id, $from, $to);

        $data['hasPrintAccess']        = $this->permission->method('pharmacy_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('pharmacy_reports', 'export')->access();
        
        $income = view('App\Modules\Reports\Views\pharmacy\item_transfer\bdtaskt1m17_item_transfer_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | item consumption form
    *--------------------------*/
    public function bdtaskt1m17c5_05_itemConsumption()
    {
        $data['title']      = get_phrases(['item', 'consumption']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "pharmacy/item_consumption/bdtaskt1m17_item_consumption";

        $data['branch_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_items', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item consumption
    *--------------------------*/
    public function bdtaskt1m17c5_06_getItemConsumption()
    { 
        $sub_store_id = $this->request->getVar('sub_store_id');
        $item_id = $this->request->getVar('item_id');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';
        $data['setting']   = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c5_01_rptModel->bdtaskt1m17_03_getItemConsumption($sub_store_id, $item_id, $from, $to);

        $data['hasPrintAccess']        = $this->permission->method('pharmacy_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('pharmacy_reports', 'export')->access();
        
        $income = view('App\Modules\Reports\Views\pharmacy\item_consumption\bdtaskt1m17_item_consumption_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | item return form
    *--------------------------*/
    public function bdtaskt1m17c5_19_itemReturn()
    {

        $data['title']      = get_phrases(['item', 'return','to','sub','store']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "pharmacy/item_return/bdtaskt1m17_item_return";

        $data['branch_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_items', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item return all
    *--------------------------*/
    public function bdtaskt1m17c5_20_getItemReturn()
    { 
        $store_id = $this->request->getVar('store_id');
        $item_id = $this->request->getVar('item_id');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';
        $data['setting']   = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c5_01_rptModel->bdtaskt1m17_11_getItemReturn($store_id, $item_id, $from, $to);

        $data['hasPrintAccess']        = $this->permission->method('pharmacy_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('pharmacy_reports', 'export')->access();
        
        $income = view('App\Modules\Reports\Views\pharmacy\item_return\bdtaskt1m17_item_return_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | supplier payment form
    *--------------------------*/
    public function bdtaskt1m17c5_13_supplierPayment()
    {

        $data['title']      = get_phrases(['supplier', 'payment']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "pharmacy/supplier_payment/bdtaskt1m17_supplier_payment";

        $data['supplier_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_supplier_information', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get all supplier payment
    *--------------------------*/
    public function bdtaskt1m17c5_14_getSupplierPayment()
    { 
        $supplier_id = $this->request->getVar('supplier_id');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';
        $data['setting']   = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c5_01_rptModel->bdtaskt1m17_07_getSupplierPayment($supplier_id, $from, $to);

        $data['hasPrintAccess']        = $this->permission->method('pharmacy_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('pharmacy_reports', 'export')->access();
        
        $income = view('App\Modules\Reports\Views\pharmacy\supplier_payment\bdtaskt1m17_supplier_payment_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c5_15_getItemReceiveDetailsById()
    { 
        $item_id = $this->request->getVar('item_id');
        $store_id = $this->request->getVar('store_id');
        $data['results']   = $this->bdtaskt1m17c5_01_rptModel->bdtaskt1m17_08_getItemReceiveDetailsById($item_id, $store_id);

        $data['hasPrintAccess']        = $this->permission->method('pharmacy_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('pharmacy_reports', 'export')->access();
        
        $income = view('App\Modules\Reports\Views\pharmacy\item_stock\bdtaskt1m17_item_receive_details', $data);
        echo json_encode(array('html'=>$income));
    }
    
    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c5_16_getSubStoreItemReceiveDetails()
    { 
        $item_id = $this->request->getVar('item_id');
        $store_id = $this->request->getVar('store_id');
        $data['results']   = $this->bdtaskt1m17c5_01_rptModel->bdtaskt1m17_09_getSubStoreItemReceiveDetails($item_id, $store_id);
        $income = view('App\Modules\Reports\Views\pharmacy\item_sub_stock\bdtaskt1m17_item_receive_details', $data);
        echo json_encode(array('html'=>$income));
    }

    /*--------------------------
    | Get warehouse list
    *--------------------------*/
    public function bdtaskt1m17c5_17_getWarehouseListByBranchId()
    { 
        $branch_id = $this->request->getVar('branch_id');
        $data   = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_main_store', array('status'=>1, 'branch_id'=>$branch_id));
        $store = '<option value="">Select</option>';
        foreach($data as $row){
            $store .= '<option value="'.$row->id.'">'.$row->nameE.'</option>';
        }
        $data   = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_sub_store', array('status'=>1, 'branch_id'=>$branch_id));
        $sub_store = '<option value="">Select</option>';
        foreach($data as $row){
            $sub_store .= '<option value="'.$row->id.'">'.$row->nameE.'</option>';
        }

        echo json_encode(array('store'=>$store, 'sub_store'=>$sub_store));
    }

    /*--------------------------
    | Get warehouse list
    *--------------------------*/
    public function bdtaskt1m17c5_18_getSubWarehouseListByBranchId()
    { 
        $branch_id = $this->request->getVar('branch_id');
        $data   = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_sub_store', array('status'=>1, 'branch_id'=>$branch_id));
        $html = '<option value="">Select</option>';
        foreach($data as $row){
            $html .= '<option value="'.$row->id.'">'.$row->nameE.'</option>';
        }

        echo $html;
    }


    /*--------------------------
    | item stock form
    *--------------------------*/
    public function bdtaskt1m17c5_19_lowStock()
    {

        $data['title']      = get_phrases(['low', 'stock','item','list']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "pharmacy/low_stock/bdtaskt1m17_low_stock";

        $data['branch_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_items', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c5_20_getLowStock()
    { 
        $store_id = $this->request->getVar('store_id');
        $store_id = $this->request->getVar('store_id');

        $data['setting']    = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c5_01_rptModel->bdtaskt1m17_12_getLowStock($store_id, $store_id);

        $data['hasPrintAccess']        = $this->permission->method('pharmacy_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('pharmacy_reports', 'export')->access();
        
        $income = view('App\Modules\Reports\Views\pharmacy\low_stock\bdtaskt1m17_low_stock_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | item stock form
    *--------------------------*/
    public function bdtaskt1m17c5_21_outOfStock()
    {

        $data['title']      = get_phrases(['out','of', 'stock','item','list']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "pharmacy/out_of_stock/bdtaskt1m17_out_of_stock";

        $data['branch_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_items', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c5_22_getOutofStock()
    { 
        $store_id = $this->request->getVar('store_id');
        $store_id = $this->request->getVar('store_id');

        $data['setting']    = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c5_01_rptModel->bdtaskt1m17_13_getOutofStock($store_id, $store_id);

        $data['hasPrintAccess']        = $this->permission->method('pharmacy_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('pharmacy_reports', 'export')->access();
        
        $income = view('App\Modules\Reports\Views\pharmacy\out_of_stock\bdtaskt1m17_out_of_stock_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | item stock form
    *--------------------------*/
    public function bdtaskt1m17c5_23_incentive()
    {

        $data['title']      = get_phrases(['doctor','incentives']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "pharmacy/incentive/bdtaskt1m17_incentive";

        $data['supplier_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_supplier_information', array('status'=>1));
        $data['doctor_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('employees', array('status'=>1, 'job_title_id'=>14));
        //var_dump($data['doctor_list']);exit;
        $data['category_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_categories', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c5_24_getIncentive()
    { 
        $supplier_id = $this->request->getVar('supplier_id');
        $doctor_id = $this->request->getVar('doctor_id');
        $category_id = $this->request->getVar('category_id');

        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';
        $data['setting']    = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c5_01_rptModel->bdtaskt1m17_14_getIncentive($supplier_id, $doctor_id, $category_id, $from, $to);

        $data['hasPrintAccess']        = $this->permission->method('pharmacy_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('pharmacy_reports', 'export')->access();
        
        $income = view('App\Modules\Reports\Views\pharmacy\incentive\bdtaskt1m17_incentive_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | item stock form
    *--------------------------*/
    public function bdtaskt1m17c5_25_expiredItem()
    {

        $data['title']      = get_phrases(['expired','item','list']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "pharmacy/expired_item/bdtaskt1m17_expired_item";

        $data['branch_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_items', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c5_26_getExpiredItem()
    { 
        $store_id = $this->request->getVar('store_id');
        $store_id = $this->request->getVar('store_id');

        $data['setting']    = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c5_01_rptModel->bdtaskt1m17_15_getExpiredItem($store_id, $store_id);

        $data['hasPrintAccess']        = $this->permission->method('pharmacy_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('pharmacy_reports', 'export')->access();
        
        $income = view('App\Modules\Reports\Views\pharmacy\expired_item\bdtaskt1m17_expired_item_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | item stock form
    *--------------------------*/
    public function bdtaskt1m17c5_27_itemCloseExpiry()
    {

        $data['title']      = get_phrases(['item','list','close','to','expiry',]);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "pharmacy/item_close_to_expiry/bdtaskt1m17_item_close_to_expiry";

        $data['branch_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['supplier_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_supplier_information', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c5_28_getItemCloseExpiry()
    { 
        $store_id = $this->request->getVar('store_id');
        $store_id = $this->request->getVar('store_id');
        $supplier_id = $this->request->getVar('supplier_id');
        $period = $this->request->getVar('period');

        $data['setting']    = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c5_01_rptModel->bdtaskt1m17_16_getItemCloseExpiry($store_id, $store_id, $supplier_id, $period);

        $data['hasPrintAccess']        = $this->permission->method('pharmacy_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('pharmacy_reports', 'export')->access();
        
        $income = view('App\Modules\Reports\Views\pharmacy\item_close_to_expiry\bdtaskt1m17_item_close_to_expiry_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | supplier payment form
    *--------------------------*/
    public function bdtaskt1m17c5_29_supplierAging()
    {

        $data['title']      = get_phrases(['supplier', 'aging']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "pharmacy/supplier_aging/bdtaskt1m17_supplier_aging";

        $data['supplier_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_supplier_information', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get all supplier payment
    *--------------------------*/
    public function bdtaskt1m17c5_30_getSupplierAging()
    { 
        $supplier_id = $this->request->getVar('supplier_id');
        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';
        $data['setting']   = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c5_01_rptModel->bdtaskt1m17_17_getSupplierAging($supplier_id, $from, $to);

        $data['hasPrintAccess']        = $this->permission->method('pharmacy_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('pharmacy_reports', 'export')->access();
        
        $income = view('App\Modules\Reports\Views\pharmacy\supplier_aging\bdtaskt1m17_supplier_aging_reports', $data);
        echo json_encode(array('data'=>$income));
    }


    /*--------------------------
    | item stock form
    *--------------------------*/
    public function bdtaskt1m17c5_31_doctorCredit()
    {

        $data['title']      = get_phrases(['credit','by','doctor','list']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "pharmacy/doctor_credit/bdtaskt1m17_doctor_credit";

        //$data['supplier_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_supplier_information', array('status'=>1));
        $data['doctor_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('employees', array('status'=>1, 'job_title_id'=>14));
        //var_dump($data['doctor_list']);exit;
        //$data['category_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_categories', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c5_32_getDoctorCredit()
    { 
        $doctor_id = $this->request->getVar('doctor_id');

        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';
        $data['setting']    = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c5_01_rptModel->bdtaskt1m17_18_getDoctorCredit($doctor_id, $from, $to);

        $data['hasPrintAccess']        = $this->permission->method('pharmacy_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('pharmacy_reports', 'export')->access();
        
        $income = view('App\Modules\Reports\Views\pharmacy\doctor_credit\bdtaskt1m17_doctor_credit_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | item stock form
    *--------------------------*/
    public function bdtaskt1m17c5_33_patientCredit()
    {

        $data['title']      = get_phrases(['credit','by','patient','list']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "pharmacy/patient_credit/bdtaskt1m17_patient_credit";

        //$data['supplier_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_supplier_information', array('status'=>1));
        //$data['doctor_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('employees', array('status'=>1, 'job_title_id'=>14));
        //var_dump($data['doctor_list']);exit;
        //$data['category_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_categories', array('status'=>1));

        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c5_34_getPatientCredit()
    { 
        $patient_id = $this->request->getVar('patient_id');

        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';
        $data['setting']    = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c5_01_rptModel->bdtaskt1m17_19_getPatientCredit($patient_id, $from, $to);

        $data['hasPrintAccess']        = $this->permission->method('pharmacy_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('pharmacy_reports', 'export')->access();
        
        $income = view('App\Modules\Reports\Views\pharmacy\patient_credit\bdtaskt1m17_patient_credit_reports', $data);
        echo json_encode(array('data'=>$income));
    }

    /*--------------------------
    | item stock form
    *--------------------------*/
    public function bdtaskt1m17c5_35_VAT()
    {

        $data['title']      = get_phrases(['vat','report']);
        $data['moduleTitle']= get_phrases(['reports']);
        $data['isDateTimes']= true;
        $data['module']     = "Reports";
        $data['page']       = "pharmacy/vat/bdtaskt1m17_vat";

        //$data['supplier_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_supplier_information', array('status'=>1));
        //$data['doctor_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('employees', array('status'=>1, 'job_title_id'=>14));
        //var_dump($data['doctor_list']);exit;
        //$data['category_list']       = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_05_getResultWhere('ph_categories', array('status'=>1));
        $data['types'] = array('Sale','Purchase','JV');
 
        return $this->base17_01_template->layout($data);
    }

    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1m17c5_36_getVAT()
    { 
        $type = $this->request->getVar('type');

        $range  = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
        $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';
        $data['setting']    = $this->bdtaskt1m17c5_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['results']   = $this->bdtaskt1m17c5_01_rptModel->bdtaskt1m17_20_getVAT($type, $from, $to);

        $data['hasPrintAccess']        = $this->permission->method('pharmacy_reports', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('pharmacy_reports', 'export')->access();
        $data['type']        = $type;
        
        $income = view('App\Modules\Reports\Views\pharmacy\vat\bdtaskt1m17_vat_reports', $data);
        echo json_encode(array('data'=>$income));
    }
}
