<?php namespace App\Modules\Human_resource\Controllers;

use CodeIgniter\Controller;
use App\Modules\Human_resource\Models\Bdtaskt1m9Reports;
use App\Modules\Human_resource\Models\Bdtaskt1m1Employee;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

use CodeIgniter\I18n\Time;

class Bdtaskt1m9c8Reports extends BaseController
{
    private $bdtaskt1c1_09_Reports;
    private $bdtaskt1c1_01_CmModel;
    private $bdtaskt1c1_01_EmpModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->db = db_connect();

        $this->permission = new Permission();
        $this->bdtaskt1c1_09_Reports = new Bdtaskt1m9Reports();
        $this->bdtaskt1c1_01_CmModel = new Bdtaskt1m1CommonModel();
        $this->bdtaskt1c1_01_EmpModel = new Bdtaskt1m1Employee();

        $this->time = new Time();

        $setting = $this->db->table('setting')->select('*')->get()->getRow();

        $timeZone = (!empty($setting->time_zone)?$setting->time_zone:'Asia/Dhaka');
        @date_default_timezone_set($timeZone);
    }

    /*--------------------------
    | item stock form
    *--------------------------*/
    public function bdtaskt1c8_01_attendance_sheet()
    {

    $data['title']      = get_phrases(['attendance', 'sheet']);
    $data['moduleTitle']= get_phrases(['human','resource']);
    // $data['isDTables']  = true;
    $data['module']     = "Human_resource";
    $data['page']       = "reports/attendance_sheet";

    $data['employee_types'] = $this->bdtaskt1c1_01_EmpModel->employee_types();

    // echo "<pre>";
    // print_r($data);
    // exit;

    return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | Get item stock
    *--------------------------*/
    public function bdtaskt1c8_02_get_attendnace_sheet()
    {

        $employee_type = $this->request->getVar('employee_type');
        $date = $this->request->getVar('date');

        $data['hasPrintAccess']   = $this->permission->method('attendance_sheet', 'print')->access();
        $data['hasExportAccess']  = $this->permission->method('attendance_sheet', 'export')->access();

        $data['date']          = $date;
        $data['employee_type'] = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('hrm_employee_types', array('id'=>$employee_type)); 
        $data['setting']       = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('setting');

        $data['results']       = $this->bdtaskt1c1_09_Reports->bdtaskt1m9_01_getAttendanceSheetReport($employee_type, $date);

        // echo "<pre>";
        // print_r($data);
        // exit;

        $data_view = view('App\Modules\Human_resource\Views\reports\attendnace_sheet_reports', $data);
        echo json_encode(array('data' => $data_view));

    }


}