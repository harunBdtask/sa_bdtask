<?php

namespace App\Modules\Dashboard\Controllers;

use CodeIgniter\Controller;
use App\Libraries\Template;

use App\Modules\Human_resource\Models\Bdtaskt1m9Reports;
use App\Modules\Human_resource\Models\Bdtaskt1m6LeaveManagement;
use App\Models\Bdtaskt1m1CommonModel;
use App\Modules\Dashboard\Models\DashboardModel;
use App\Libraries\Permission;

class Bdtaskt1c2Dashboard extends BaseController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->dashboardModel = new DashboardModel();
        $this->bdtaskt1c1_09_Reports = new Bdtaskt1m9Reports();
        $this->bdtaskt1c1_06_LeaveManagement = new Bdtaskt1m6LeaveManagement();
        $this->bdtaskt1c1_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    public function index()
    {

        if (!$this->session->get('isLogIn')) {
            return redirect()->route('login');
        }

        $data['title']      = get_phrases(['home']);
        $data['moduleTitle'] = get_phrases(['dashboard']);
        $data['module']     = "dashboard";
        $data['page']       = "bdtaskt1v1dashboard";

        $data['is_hrm'] = false;
        $data['best_employees']       = $this->bdtaskt1c1_09_Reports->best_employees_for_current_month();
        $data['departments']       = $this->bdtaskt1c1_09_Reports->get_all_departments();
        $data['alertdata'] = $this->dashboardModel->store_alert();
        $data['supplier_ad'] = $this->supplier_deliver();
        $data['prodcudtion_ad'] = $this->dashboardModel->production_stock_alert();
        $data['sales_ad'] = $this->dashboardModel->sales_alert();
        $data['attendance']  = $this->dashboardModel->daily_attendance_count();

        $employee_info = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_employees', array('employee_id' => session('id')));
        if ($employee_info && $employee_info->designation) {
            // dd($employee_info);
            $designation_info = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('hrm_designation', array('id' => $employee_info->designation));

            // Check if the logged in employee's designation is HRM, then get all the data for hrm dashboard
            if ($designation_info->id == 4) { // Hrm department

                $data['is_hrm'] = true;

                $data['total_employees']         = $this->bdtaskt1c1_09_Reports->count_all_employees();
                $data['hired_employees']         = $this->bdtaskt1c1_09_Reports->curent_month_hired_employees();

                $data['total_present_employees'] = $this->bdtaskt1c1_09_Reports->totalPresentEmployees(date('Y'), date('m'), date('d'));
                $data['total_absent_employees']  = $data['total_employees'] - $data['total_present_employees'];

                $data['birth_dates']             = $this->bdtaskt1c1_09_Reports->curent_month_birth_dates_employees();

                // echo "<pre>";
                // print_r($data);
                // exit;

                return $this->bdtaskt1c1_01_template->layout($data);
            }
        }

        // Get all the leave for the selected employee
        $total_leave = 0;
        $leave_from_leave_types  = $this->bdtaskt1c1_06_LeaveManagement->get_leave_from_leave_types();
        $leave_from_earned_leave = $this->bdtaskt1c1_06_LeaveManagement->get_leave_from_earned_leave(session('id'), date('Y-m-d'));

        $total_leave = (int)$leave_from_leave_types + (int)$leave_from_earned_leave;
        //Ends

        // Now get that, if the employee already has taken any leave for the running year.. then compare with the total leave
        $leave_remaining = 0;
        $already_used_leave  = $this->bdtaskt1c1_06_LeaveManagement->get_already_used_leave(session('id'), date('Y-m-d'));

        $data['total_leave_taken']    = $already_used_leave;
        if ($total_leave > 0) {
            $leave_remaining = $total_leave - $already_used_leave;
        }

        $data['total_leave_remaining']       = $leave_remaining;

        $data['total_present']       = $this->bdtaskt1c1_09_Reports->totalDaysWorkedForTheMonth(session('id'), date('m'), date('Y'));
        $data['total_absent']        = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')) - $data['total_present'];

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $this->bdtaskt1c1_01_template->layout($data);
    }

    public function supplier_deliver()
    {
        $purchases_details = $this->dashboardModel->supplier_alert();
        $item_counter = 1;
        $returndata = [];
        foreach ($purchases_details as $details) {
            $item_row = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id' => $details->item_id));

            if (!empty($item_row)) {
                $unit_row = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id' => $item_row->unit_id));

                $receive_sum = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getSumRow('wh_material_receive_details', 'qty', array('purchase_id' => $details->purchase_id, 'item_id' => $details->item_id));

                if (!empty($receive_sum)) {
                    $receive_qty    = $receive_sum->qty;
                    $avail_qty      = $details->qty - $receive_sum->qty;
                } else {
                    $receive_qty    = 0;
                    $avail_qty      = $details->qty;
                }

                if ($avail_qty < 0) {
                    $avail_qty = 0;
                }

                if ($avail_qty != 0) {
                    $returndata[] = array(
                        'sl'            => $item_counter,
                        'po'     => $details->voucher_no,
                        'o_date'     => $details->date,
                        'supplier'     => $details->supplier,
                        'item_name'     => $item_row->nameE . ' (' . $item_row->item_code . ')',
                        'total_qty'     => $details->qty,
                        'receive_qty'     => $receive_qty,
                        'avail_qty'     => $avail_qty,
                        'unit'         => (($unit_row) ? $unit_row->nameE : ''),
                    );
                    $item_counter++;
                }
            }
        }
        return $returndata;
    }

    public function accounts_chart()
    {
        $data['title']                = get_phrases(['home']);
        $data['moduleTitle']          = get_phrases(['dashboard']);
        $data['module']               = "dashboard";
        $data['payablegraph']         = $this->dashboardModel->accountpayableGraph();
        $data['receivablegraph']      = $this->dashboardModel->accountreceivableGraph();
        $data['piedata']              = $this->dashboardModel->IncomeExpenseSummary();
        $data['todaysvoucher']        = $this->dashboardModel->todaysVouchers();
        $data['total_pendingvoucher'] = $this->dashboardModel->totalpendingVouchers();
        $data['isAmChart']            = true;
        $data['page']                 = "bdtaskt1v1dashboardchart"; 
        return $this->bdtaskt1c1_01_template->layout($data);
    }

    public function hrm_chart()
    {
        $data['title']      = get_phrases(['home']);
        $data['isDTables']  = true;
        // $data['isAmChart']  = true;

        $data['total_employees']    = (int)$total_employees_count = $this->bdtaskt1c1_09_Reports->count_all_employees();
        $data['active_employees']   = (int)$active_employees = $this->bdtaskt1c1_09_Reports->count_all_active_employees();
        $data['inactive_employees'] = $total_employees_count - $active_employees;

        $data['best_employees']               = $this->bdtaskt1c1_09_Reports->best_employees();
        $data['designation_wise_strgth_data'] = $this->bdtaskt1c1_09_Reports->get_designation_wise_strength_data();
        // get_employee_on_leave_data for the current month...
        $data['employee_on_leave']            = $this->bdtaskt1c1_09_Reports->get_employee_on_leave_data();

        $data['moduleTitle'] = get_phrases(['dashboard']);
        $data['module']      = "dashboard";
        $data['page']        = "bdtaskt1v1dashboardhrmchart";
        return $this->bdtaskt1c1_01_template->layout($data);
    }

    public function procurement_chart()
    {
        $data['title']      = get_phrases(['home']);
        $data['moduleTitle'] = get_phrases(['dashboard']);
        $data['module']     = "dashboard";
        $data['page']       = "bdtaskt1v1dashboard_procurement_chart";
        $data['isDTables']  = true;
        $data['setting']    = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('setting');

        $data['hasPrintAccess']     = $this->permission->method('categories', 'print')->access();
        $data['hasExportAccess']    = $this->permission->method('categories', 'export')->access();

        $data['suppliers']  = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_04_getResult('wh_supplier_information');
        $data['purchase_grand_total'] = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getSumRow('wh_material_purchase', 'grand_total', array('grand_total !=' => 0));
        $data['purchase_qty'] = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getSumRow('wh_material_purchase_details', 'qty', array('qty !=' => 0));
        $data['data']       = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_04_getResult('wh_material');
        $data['data2']      = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getSumRow('wh_material_stock', 'stock', array('stock !=' => 0));
        $data['alertdata']  = $this->dashboardModel->store_alert();
        return $this->bdtaskt1c1_01_template->layout($data);
    }

    public function production_chart()
    {
        $data['title']      = get_phrases(['home']);
        $data['moduleTitle'] = get_phrases(['dashboard']);
        $data['module']     = "dashboard";
        $data['page']       = "bdtaskt1v1dashboard_production_chart";

        $data['total_pro']  = $this->dashboardModel->total_production();
        $data['today_pro']  = $this->dashboardModel->today_production();
        $data['total_con']  = $this->dashboardModel->total_consumtion();
        $data['today_con']  = $this->dashboardModel->today_consumtion();
        $pro_chart = $this->dashboardModel->total_production_chart();
        $data['currnt_month_pro']  = $pro_chart['current_mon'];
        $data['prev_month_pro']  = $pro_chart['prev_mon'];
        $data['ontime_delivery_prev']  = $this->dashboardModel->delivery_chart_prev();
        $data['ontime_delivery_curr']  = $this->dashboardModel->delivery_chart_curr();
        $data['undelivery_prev']  = $this->dashboardModel->undelivery_chart_prev();
        $data['undelivery_curr']  = $this->dashboardModel->undelivery_chart_curr();
        $data['current_process_loss']  = $this->dashboardModel->current_process_loss();
        $data['previous_process_loss']  = $this->dashboardModel->previous_process_loss();

        return $this->bdtaskt1c1_01_template->layout($data);
    }

    public function production2_chart()
    {
        $data['title']      = get_phrases(['home']);
        $data['moduleTitle'] = get_phrases(['dashboard']);
        $data['module']     = "dashboard";
        $data['page']       = "bdtaskt1v1dashboard_production2_chart";
        return $this->bdtaskt1c1_01_template->layout($data);
    }

    public function sale_chart()
    {
        $data['title']      = get_phrases(['home']);
        $data['moduleTitle'] = get_phrases(['dashboard']);
        $data['module']     = "dashboard";
        $data['page']       = "bdtaskt1v1dashboard_sale_chart";
        $data['isDTables']  = true;
        $data['setting']    = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('setting');

        $data['hasPrintAccess']     = $this->permission->method('categories', 'print')->access();
        $data['hasExportAccess']    = $this->permission->method('categories', 'export')->access();

        $data['dealers']       = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_05_getResultWhere('dealer_info', array('status' => 1));
        $data['dealer_credit'] = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getSumRow('dealer_info', 'credit_amount', array('credit_amount !=' => 0));
        $data['short_credit']  = $this->dashboardModel->getShortCredit();
        $data['do_qnty']  = $this->dashboardModel->getDOquantity();
        $data['delivery_qnty']  = $this->dashboardModel->getDOdeliveryQuantity();
        // $data['do_qnty'] = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getSumRow('do_details', 'quantity', array('quantity !='=>0));
        // $data['delivery_qnty'] = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getSumRow('do_delivery_details', 'quantity', array('quantity !='=>0));

        return $this->bdtaskt1c1_01_template->layout($data);
    }

    public function finished_goods_store_getList()
    {
        $postData = $this->request->getVar();
        $data = $this->dashboardModel->finished_goods_store_getAll($postData);
        echo json_encode($data);
    }

    public function getSalesList()
    {
        $postData = $this->request->getVar();
        $data = $this->dashboardModel->sales_getAll($postData);
        echo json_encode($data);
    }

    public function store_chart()
    {
        $data['title']      = get_phrases(['home']);
        $data['moduleTitle'] = get_phrases(['dashboard']);
        $data['module']     = "dashboard";
        $data['page']       = "bdtaskt1v1dashboard_store_chart";
        $data['isDTables']  = true;
        $data['setting']    = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getRow('setting');

        $data['hasPrintAccess']     = $this->permission->method('categories', 'print')->access();
        $data['hasExportAccess']    = $this->permission->method('categories', 'export')->access();

        $data['data']       = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_04_getResult('wh_material');
        $data['data2']      = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_03_getSumRow('wh_material_stock', 'stock', array('stock !=' => 0));
        $data['alertdata']  = $this->dashboardModel->store_alert();
        return $this->bdtaskt1c1_01_template->layout($data);
    }

    public function store_chart_getList()
    {
        $postData = $this->request->getVar();
        $data = $this->dashboardModel->store_chart_getAll($postData);
        echo json_encode($data);
    }

    public function po_getList()
    {
        $postData = $this->request->getVar();
        $data = $this->dashboardModel->po_getAll($postData);
        echo json_encode($data);
    }

    // Start of functionalities for HRM Reports....from 22nd May 2022 ////

    //daily_attendnace_employee for current month for HRM dashboard graph reports
    public function daily_attendnace_employee()
    {
        $current_date = date("Y-m-d h:i:sa");
        $date = date("Y-m-d");
        $date_y = date('Y');
        $date_m = date('m');
        $time = date('H:i:s');

        for ($i = 1; $i <=  date('t'); $i++) {
            // add the date to the dates array
            $dates[] = date('Y') . "-" . date('m') . "-" . str_pad($i, 2, '0', STR_PAD_LEFT);
            $days[] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $employee_attendance_respo = $this->bdtaskt1c1_09_Reports->employee_attendance_daily();
        $active_employees = (int)$this->bdtaskt1c1_09_Reports->count_all_active_employees();

        $respo_present = array();
        $respo_absent  = array();
        // create days and points based array
        foreach ($days as $key => $value) {

            $respo_days[] = (string)$value;
            $y_m_d = $date_y . '-' . $date_m . '-' . $value;
            $flag = 0;

            foreach ($employee_attendance_respo as $key => $employee_attendance_res) {

                if ($employee_attendance_res['atten_date'] === $y_m_d) {

                    $respo_present[] = $employee_attendance_res['total_present'];
                    $total_absent = $active_employees - (int)$employee_attendance_res['total_present'];
                    $respo_absent[] = (string)$total_absent;

                    $flag = 1;
                }
            }

            if ($flag == 0) {

                $respo_present[] = '0';
                $respo_absent[] = (string)$active_employees;
            }
        }

        $respo_data['days']          = $respo_days;
        $respo_data['respo_present'] = $respo_present;
        $respo_data['respo_absent']  = $respo_absent;

        echo json_encode($respo_data);
    }

    //department_wise_strength for HRM dashboard graph reports
    public function department_wise_strength()
    {

        $dept_wise_strength = $this->bdtaskt1c1_09_Reports->get_department_wise_strength();

        echo json_encode($dept_wise_strength);
    }

    //department_wise_strength for HRM dashboard graph reports
    public function employee_type_wise_data()
    {

        $emp_type_wise_data = $this->bdtaskt1c1_09_Reports->get_employee_type_wise_data();

        echo json_encode($emp_type_wise_data);
    }

    //department_wise_strength for HRM dashboard graph reports
    public function employee_status_wise_data()
    {

        $emp_status_wise_data = $this->bdtaskt1c1_09_Reports->get_employee_status_wise_data();

        echo json_encode($emp_status_wise_data);
    }

    // End of functionalities for HRM Reports....////

}
