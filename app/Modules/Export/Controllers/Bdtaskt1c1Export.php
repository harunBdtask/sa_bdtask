<?php namespace App\Modules\Export\Controllers;
use App\Modules\Export\Views;
use CodeIgniter\Controller;
use App\Modules\Export\Models\Bdtaskt1m1ExportModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1c1Export extends BaseController
{
    private $bdtaskt1c1_01_exportModel;
    private $bdtaskt1c1_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1c1_01_exportModel = new Bdtaskt1m1ExportModel();
        $this->bdtaskt1c1_02_CmModel = new Bdtaskt1m1CommonModel();
        if(session('defaultLang')=='english'){
            $this->langColumn = 'nameE';
        }else{
            $this->langColumn = 'nameA';
        }
    }

    /*--------------------------
    | Export list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['partial','export']);
        $data['moduleTitle']= get_phrases(['data','export']);
        $data['isDTables']  = true;
        $data['module']     = "export";
        $data['page']       = "export/list";

        $data['permission']       = $this->permission;

        $data['branch_list']      = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['dept_list']      = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_05_getResultWhere('department', array('status'=>1));
        $data['sub_dept_list']      = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_05_getResultWhere('department', array('status'=>1, 'parent_id >'=>0 ));
        $data['sec_role_list']      = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_05_getResultWhere('sec_role', array('status'=>1));
        $data['job_title_list']      = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_05_getResultWhere('job_title', array('status'=>1));
        $data['categories']      = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_categories', array('status'=>1));
        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | Get User info
    *--------------------------*/
    public function bdtaskt1c1_01_get_user_data()
    { 
        //echo 1;exit;
        $postData = $this->request->getVar();
        $records = $this->bdtaskt1c1_01_exportModel->bdtaskt1m1_01_get_user_data($postData);
        
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=User_List_".date('d-m-Y').".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $out = fopen('php://output', 'w');
        
        $heading = array('id','emp_id','fullname','email','username','password','user_role','branch_id','department_id','store_id','store_id','mobile','date_of_birth','gender','pass_reset_token','auth_token','last_activity','created_by','created_date','updated_date','updated_by','status');
        fputcsv($out, $heading);

        foreach($records as $record ){ 
            fputcsv($out, $record);
        }

        fclose($out);
        exit;
    }

    /*--------------------------
    | Get User info
    *--------------------------*/
    public function bdtaskt1c1_02_get_employee_data()
    { 
        //echo 1;exit;
        $postData = $this->request->getVar();
        $records = $this->bdtaskt1c1_01_exportModel->bdtaskt1m1_02_get_employee_data($postData);
        
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=Employee_List_".date('d-m-Y').".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $out = fopen('php://output', 'w');
        
        $heading = array('emp_id', 'firstname', 'lastname', 'nameE', 'nameA', 'short_name', 'email', 'department_id', 'branch_id', 'job_title_id', 'mobile', 'photo', 'date_of_birth', 'gender', 'blood_group', 'designation', 'specialization', 'career_title', 'join_date', 'marital', 'emp_type', 'emp_status', 'job_nature', 'apnt_conf_value', 'over_book_cnt', 'd_slot_period', 'default_service', 'created_by', 'created_date', 'updated_date', 'updated_by', 'acc_head', 'status');
        fputcsv($out, $heading);

        foreach($records as $record ){ 
            fputcsv($out, $record);
        }

        fclose($out);
        exit;
    }

    /*--------------------------
    | Get User info
    *--------------------------*/
    public function bdtaskt1c1_03_get_service_data()
    { 
        //echo 1;exit;
        $postData = $this->request->getVar();
        $records = $this->bdtaskt1c1_01_exportModel->bdtaskt1m1_03_get_service_data($postData);
        
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=Service_List_".date('d-m-Y').".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $out = fopen('php://output', 'w');
        
        $heading = array('code_no', 'intl_code', 'service', 'branch','department', 'sub_department', 'service_type', 'duration', 'price', 'cost_amount', 'incentive', 'inventory item total cost','commission pay program');
        fputcsv($out, $heading);

        foreach($records as $row ){ 
            $record = array($row->code_no, $row->intl_code, $row->nameE, $row->branch_name, $row->dept_name, $row->sub_dept_name, $row->service_type_name, $row->duration, $row->price, $row->cost_amount, $row->incentive_percent, $row->item_cost, $row->commission_program );
            fputcsv($out, $record);
        }

        fclose($out);
        exit;
    }

    /*--------------------------
    | Get User info
    *--------------------------*/
    public function bdtaskt1c1_04_get_offer_data()
    { 
        //echo 1;exit;
        $postData = [];//$this->request->getVar();
        $records = $this->bdtaskt1c1_01_exportModel->bdtaskt1m1_04_get_offer_data($postData);
        
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=Offer_List_".date('d-m-Y').".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $out = fopen('php://output', 'w');
        
        $heading = array('id', 'nameE', 'nameA', 'start_date', 'end_date', 'start_time', 'end_time', 'doctor_commission', 'need_approval', 'discount', 'all_service', 'service_id', 'isApproved', 'status');
        fputcsv($out, $heading);

        foreach($records as $record ){ 
            fputcsv($out, $record);
        }

        fclose($out);
        exit;
    }

    /*--------------------------
    | Get User info
    *--------------------------*/
    public function bdtaskt1c1_05_get_package_data()
    { 
        //echo 1;exit;
        $postData = [];//$this->request->getVar();
        $records = $this->bdtaskt1c1_01_exportModel->bdtaskt1m1_05_get_package_data($postData);
        
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=Offer_Package_List_".date('d-m-Y').".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $out = fopen('php://output', 'w');
        
        $heading = array('id', 'nameE', 'nameA', 'start_date', 'end_date', 'active_id', 'status');
        fputcsv($out, $heading);

        foreach($records as $record ){ 
            fputcsv($out, $record);
        }

        fclose($out);
        exit;
    }

    /*--------------------------
    | Get User info
    *--------------------------*/
    public function bdtaskt1c1_06_get_item_data()
    { 
        //echo 1;exit;
        $postData = $this->request->getVar();
        $records = $this->bdtaskt1c1_01_exportModel->bdtaskt1m1_06_get_item_data($postData);
        
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=Item_List_".date('d-m-Y').".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $out = fopen('php://output', 'w');
        
        $heading = array('id', 'nameE', 'nameA', 'item_code', 'company_code', 'cat_id', 'unit_id', 'box_qty', 'carton_qty', 'item_type', 'item_image', 'consumed_by', 'vat_applicable', 'has_expiry', 'created_by', 'created_date', 'updated_by', 'updated_date', 'status');

        fputcsv($out, $heading);

        foreach($records as $record ){ 
            fputcsv($out, $record);
        }

        fclose($out);
        exit;
    }

    
}
