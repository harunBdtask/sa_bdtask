<?php

namespace App\Modules\Reports\Controllers;

use CodeIgniter\Controller;
use App\Modules\Reports\Models\Bdtaskt1m1m1SalesReportModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

// use \PhpOffice\PhpSpreadsheet\Spreadsheet;
// use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Bdtaskt1m1c1SalesReports extends BaseController
{
  private $bdtaskt1m1m1_sales_rptModel;
  private $bdtaskt1m17c2_02_CmModel;

  /**
   * Constructor.
   */
  public function __construct()
  {
    $this->permission = new Permission();
    $this->bdtaskt1m1m1_sales_rptModel = new Bdtaskt1m1m1SalesReportModel();
    $this->bdtaskt1m17c2_02_CmModel = new Bdtaskt1m1CommonModel();
    // if (session('defaultLang') == 'english') {
    //   $this->langColumn = 'nameE';
    // } else {
    //   $this->langColumn = 'nameA';
    // }
  }

  /*--------------------------
    | item stock form
    *--------------------------*/
  public function index()
  {

    $data['title']        = get_phrases(['daily', 'sales', 'report']);
    $data['moduleTitle']  = get_phrases(['reports']);
    // $data['isDateTimes']  = true;
    $data['module']       = "Reports";
    $data['page']         = "sales/daily_sales";
    $data['item_categories'] = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_categories', array('status'=>1));

    return $this->base17_01_template->layout($data);
  }

  public function bdtaskt1m1c1_01_getDailySales()
  {
    $category_id  = $this->request->getVar('category_id');
    $date         = $this->request->getVar('date');

    $data['title']          = get_phrases(['daily', 'sales', 'report']);
    $data['hasPrintAccess'] = $this->permission->method('daily_sales', 'print')->access();
    $data['setting']        = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
    $data['results']        = $this->bdtaskt1m1m1_sales_rptModel->bdtaskt1m1_01_getDailySales($date, $category_id);
    $data['cat_name']       = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('wh_categories', array('id'=>$category_id));
    $data['date']           = $date;

    $income = view('App\Modules\Reports\Views\sales\daily_sales_table', $data);
    echo json_encode(array('data' => $income));
  }

  public function bdtaskt1m1c1_02_indexDoSummary()
  {
    $data['title']        = get_phrases(['DO', 'summary', 'report']);
    $data['moduleTitle']  = get_phrases(['reports']);
    $data['module']       = "Reports";
    $data['page']         = "sales/do_summary";
    $data['item_categories'] = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_categories', array('status'=>1));

    return $this->base17_01_template->layout($data);
  }

  public function bdtaskt1m1c1_03_getDoSummary()
  {
    $date         = $this->request->getVar('date');

    $data['title']          = get_phrases(['DO', 'summary', 'report']);
    $data['hasPrintAccess'] = $this->permission->method('do_summary', 'print')->access();
    $data['setting']        = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
    $data['results']        = $this->bdtaskt1m1m1_sales_rptModel->bdtaskt1m1_02_getDoSummary($date);
    $data['date']           = $date;

    $income = view('App\Modules\Reports\Views\sales\do_summary_table', $data);
    echo json_encode(array('data' => $income));
  }

  public function bdtaskt1m1c1_04_indexLongCredit()
  {
    $data['title']        = 'Distributor Wise Due Taka List (Long Credit)';
    $data['moduleTitle']  = get_phrases(['reports']);
    $data['module']       = "Reports";
    $data['page']         = "sales/long_credit";

    return $this->base17_01_template->layout($data);
  }

  public function bdtaskt1m1c1_05_getLongCredit()
  {
    $date         = $this->request->getVar('date');

    $data['title']          = 'Distributor Wise Due Taka List (Long Credit)';
    $data['hasPrintAccess'] = $this->permission->method('do_summary', 'print')->access();
    $data['setting']        = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
    $data['results']        = $this->bdtaskt1m1m1_sales_rptModel->bdtaskt1m1_03_getLongCredit($date);
    $data['date']           = $date;

    $income = view('App\Modules\Reports\Views\sales\long_credit_table', $data);
    echo json_encode(array('data' => $income));
  }

  public function bdtaskt1m1c1_06_indexShortCredit()
  {
    $data['title']        = 'Distributor Wise Due Taka List (Short Credit)';
    $data['moduleTitle']  = get_phrases(['reports']);
    $data['module']       = "Reports";
    $data['page']         = "sales/short_credit";

    return $this->base17_01_template->layout($data);
  }

  public function bdtaskt1m1c1_07_getShortCredit()
  {
    $date         = $this->request->getVar('date');

    $data['title']          = 'Distributor Wise Due Taka List (Short Credit)';
    $data['hasPrintAccess'] = $this->permission->method('do_summary', 'print')->access();
    $data['setting']        = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
    $data['results']        = $this->bdtaskt1m1m1_sales_rptModel->bdtaskt1m1_04_getShortCredit($date);
    $data['date']           = $date;

    $income = view('App\Modules\Reports\Views\sales\short_credit_table', $data);
    echo json_encode(array('data' => $income));
  }


  public function bdtaskt1m1c1_08_indexPurchaseDepartment()
  {
    $data['title']        = get_phrases(['purchase', 'department', 'report']);
    $data['moduleTitle']  = get_phrases(['reports']);
    $data['module']       = "Reports";
    $data['page']         = "sales/purchase_dept";
    
    $data['item_categories'] = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_categories', array('status'=>1));
    $data['items'] = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material', array('status'=>1));
    return $this->base17_01_template->layout($data);
  }

  public function bdtaskt1m1c1_09_getPurchaseDepartment()
  {
    $date         = date('Y-m-d');
    $item_id      = $this->request->getVar('item_id');

    $data['title']          = get_phrases(['purchase', 'department', 'report']);
    $data['hasPrintAccess'] = $this->permission->method('purchase_dept', 'print')->access();
    $data['setting']        = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
    $data['results']        = $this->bdtaskt1m1m1_sales_rptModel->bdtaskt1m1_05_getPurchaseDepartment($item_id);
    $data['date']           = $date;

    $income = view('App\Modules\Reports\Views\sales\purchase_dept_table', $data);
    echo json_encode(array('data' => $income));
  }

  public function bdtaskt1m1c1_10_get_item_list()
  {
      $category_id = $this->request->getVar('category_id');
      $column = ["id, CONCAT(nameE, ' (', item_code, ')') as text"];
      $data     = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_07_getSelect2Data('wh_material', array('cat_id'=>$category_id), $column);
      echo json_encode($data);
  }

  public function bdtaskt1m1c1_11_indexDistributorWiseSales()
  {
    $data['title']        = get_phrases(['distributor', 'wise', 'sales', 'report']);
    $data['moduleTitle']  = get_phrases(['reports']);
    $data['module']       = "Reports";
    $data['page']         = "sales/distributor_wise_sales";
    
    $data['user_list'] = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_05_getResultWhere('user', array('status'=>1));
    return $this->base17_01_template->layout($data);
  }

  public function bdtaskt1m1c1_12_getDistributorWiseSales()
  {
    $date         = date('Y-m-d');
    $category_id      = $this->request->getVar('category_id');

    $data['title']          = get_phrases(['distributor', 'wise', 'sales', 'report']);
    $data['hasPrintAccess'] = $this->permission->method('distributor_wise_sales', 'print')->access();
    $data['setting']        = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
    $data['results']        = $this->bdtaskt1m1m1_sales_rptModel->bdtaskt1m1_06_getDistributorWiseSales($category_id);
    $data['date']           = $date;

    $income = view('App\Modules\Reports\Views\sales\distributor_wise_sales_table', $data);
    echo json_encode(array('data' => $income));
  }

  public function bdtaskt1m1c1_13_indexOfficerWiseSales()
  {
    $data['title']        = get_phrases(['officer', 'wise', 'sales', 'report']);
    $data['moduleTitle']  = get_phrases(['reports']);
    $data['isDateTimes']  = true;
    $data['module']       = "Reports";
    $data['page']         = "sales/officer_wise_sales";
    
    return $this->base17_01_template->layout($data);
  }

  public function bdtaskt1m1c1_14_getOfficerWiseSales()
  {
    $range  = $this->request->getVar('date');
    $date   = explode('-', $range);
    $from   = (!empty($date[0]))?date('Y-m-d', strtotime(trim($date[0]))):'';
    $to     = (!empty($date[1]))?date('Y-m-d', strtotime(trim($date[1]))):'';

    $date         = date('Y-m-d');
    $category_id      = $this->request->getVar('category_id');

    $data['title']          = get_phrases(['officer', 'wise', 'sales', 'report']);
    $data['hasPrintAccess'] = $this->permission->method('officer_wise_sales', 'print')->access();
    $data['setting']        = $this->bdtaskt1m17c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
    $data['results']        = $this->bdtaskt1m1m1_sales_rptModel->bdtaskt1m1_07_getOfficerWiseSales($from, $to);
    $data['date']           = $date;

    $income = view('App\Modules\Reports\Views\sales\officer_wise_sales_table', $data);
    echo json_encode(array('data' => $income));
  }



}
