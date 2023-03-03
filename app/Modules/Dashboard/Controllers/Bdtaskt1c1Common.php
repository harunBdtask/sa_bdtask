<?php namespace App\Modules\Dashboard\Controllers;
use CodeIgniter\Controller;
use App\Models\Bdtaskt1m1CommonModel;
class Bdtaskt1c1Common extends BaseController
{
	private $bdtaskt1c1_01_CmModel;
    private $langColumn;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bdtaskt1c1_01_CmModel = new Bdtaskt1m1CommonModel();
        if(session('defaultLang')=='english'){
            $this->langColumn = 'nameE';
        }else{
            $this->langColumn = 'nameA';
        }
    }

    /*--------------------------
    | Basic table list for select2 ID
    *--------------------------*/
    public function bdtaskt1c1_01_select2List($table, $notIds=null)
    { 
        if(!empty($notIds)){
            $ids = explode('-', $notIds);
        }else{
            $ids = array();
        }
        $column = ["id", "CONCAT_WS(' ', $this->langColumn) as text"];
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_13_getListData($table, $column, $ids);
        echo json_encode($data);
    }

    /*--------------------------
    | Basic table list for select2 NAME
    *--------------------------*/
    public function bdtaskt1c1_02_select2Name($table, $notIds=null)
    { 
        if(!empty($notIds)){
            $ids = explode('-', $notIds);
        }else{
            $ids = array();
        }
    	$column = ["nameE as id", "CONCAT_WS(' ', $this->langColumn) as text"];
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_13_getListData($table, $column, $ids);
        echo json_encode($data);
    }

    /*--------------------------
    | Search patient info
    *--------------------------*/
    public function bdtaskt1c1_03_searchPatient()
    { 
        $text = $this->request->getVar('term');
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_10_searchPatient($text, $this->langColumn);
        echo json_encode($data);
    }

    /*--------------------------
    | Search doctor info
    *--------------------------*/
    public function bdtaskt1c1_04_searchDoctor()
    { 
        $text = $this->request->getVar('term');
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_08_searchDoctor($text, $this->langColumn);
        echo json_encode($data);
    }

    /*--------------------------
    | Get doctors by Department ID
    *--------------------------*/
    public function bdtaskt1c1_05_deptDoctors($did)
    { 
        $column = ["emp_id as id, CONCAT_WS(' ', short_name, '-', $this->langColumn) as text"];
        $where = array('department_id'=>$did, 'job_title_id'=>14, 'status'=>1);
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_33_getDoctorList('hrm_employees', $where, $column);
        echo json_encode($data);
    }

    /*--------------------------
    | Search doctor info
    *--------------------------*/
    public function bdtaskt1c1_06_searchDocServices()
    { 
        $text = $this->request->getVar('term');
        $docId = $this->request->getVar('doctor_id');
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_11_searchDocServices($text, $docId, $this->langColumn);
        echo json_encode($data);
    }

    /*--------------------------
    | diagnosis list
    *--------------------------*/
    public function bdtaskt1c1_07_getDiagnosis()
    { 
        $column = ["id", "CONCAT(code_no, ' - ', $this->langColumn) as text"];
        if(session('branchId') > 0){
            $where = array('status'=>1, 'branch_id'=>session('branchId'));
        }else{
            $where = array('status'=>1);
        }
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_07_getSelect2Data('diagnosis', $where, $column);
        echo json_encode($data);
    }

    /*--------------------------
    | Get packages info
    *--------------------------*/
    public function bdtaskt1c1_08_searchPackages()
    { 
        $column = ["id", "CONCAT_WS(' ', $this->langColumn, start_date, 'To', end_date) as text"];
        $where = array('end_date>='=>date('Y-m-d'), 'status'=>1, 'branch_id'=>session('branchId'));
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_07_getSelect2Data('offer_packages', $where, $column);
        echo json_encode($data);
    }

    /*--------------------------
    | Search medicine info
    *--------------------------*/
    public function bdtaskt1c1_09_searchMedicines()
    { 
        $text = $this->request->getVar('term');
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_12_searchMedicine($text, $this->langColumn);
        echo json_encode($data);
    }

    /*--------------------------
    | Get active service offer list
    *--------------------------*/
    public function bdtaskt1c1_10_getActiveOffers()
    { 
        $column = ["id", "CONCAT_WS(' ', $this->langColumn, start_date, 'To', end_date) as text"];
        $where = array('end_date>='=>date('Y-m-d'), 'branch_id'=>session('branchId'), 'status'=>1);
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_07_getSelect2Data('offers', $where, $column);
        echo json_encode($data);
    }

    /*--------------------------
    | Search appoint info
    *--------------------------*/
    public function bdtaskt1c1_11_searchAppoint()
    { 
        $text = $this->request->getVar('term');
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_12_searchAppoint($text, $this->langColumn);
        echo json_encode($data);
    }

    /*--------------------------
    | Get doctors list
    *--------------------------*/
    public function bdtaskt1c1_12_getDoctorList()
    { 
        $column = ["emp_id as id, CONCAT_WS(' ', short_name, '-', $this->langColumn) as text"];        
        $where = array('job_title_id'=>14, 'status'=>1);        
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_33_getDoctorList('employees', $where, $column);
        echo json_encode($data);
    }
 
    /*--------------------------
    | Get doctors list
    *--------------------------*/
    public function bdtaskt1c1_13_getDepartList()
    { 
        $column = ["id, CONCAT(id, ' - ', $this->langColumn) as text"];
        $where = array('status'=>1);
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_07_getSelect2Data('hrm_departments', $where, $column);
        echo json_encode($data);
    }

    /*--------------------------
    | Get medical note template list
    *--------------------------*/
    public function bdtaskt1c1_14_getMNTempList()
    { 
        $column = ["id, CONCAT($this->langColumn) as text"];
        $where = array('status'=>1);
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_07_getSelect2Data('medical_notes_template', $where, $column);
        echo json_encode($data);
    }

    /*--------------------------
    | Search patient info
    *--------------------------*/
    public function bdtaskt1c1_15_searchPntWithFile()
    { 
        $text = $this->request->getVar('term');
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_14_searchPntWithFile($text, $this->langColumn);
        echo json_encode($data);
    }

    /*--------------------------
    | Search receipt voucher
    *--------------------------*/
    public function bdtaskt1c1_16_searchRVoucher()
    { 
        $text = $this->request->getVar('term');
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_15_searchRV($text);
        echo json_encode($data);
    }

    /*--------------------------
    | Get packages info
    *--------------------------*/
    public function bdtaskt1c1_17_packageList()
    { 
        $column = ["id", "CONCAT_WS(' ', id, '-', $this->langColumn) as text"];
        $where = array('status'=>1, 'branch_id'=>session('branchId'));
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_07_getSelect2Data('offer_packages', $where, $column);
        echo json_encode($data);
    }

    /*--------------------------
    | Search invoice no
    *--------------------------*/
    public function bdtaskt1c1_18_searchInvoiceNo()
    { 
        $text = $this->request->getVar('term');
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_16_searchInvoiceNo($text);
        echo json_encode($data);
    }

    /*--------------------------
    | Search employee
    *--------------------------*/
    public function bdtaskt1c1_19_searchEmployee()
    { 
        $last_name = 'last_name';
        $text = $this->request->getVar('term');
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_09_searchEmployee($text, $last_name);
        echo json_encode($data);
    }

    /*--------------------------
    | Patient list
    *--------------------------*/
    public function bdtaskt1c1_20_patientList()
    { 
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_19_patientList($this->langColumn);
        echo json_encode($data);
    }

    /*--------------------------
    | Get table max ID
    *--------------------------*/
    public function bdtaskt1c1_21_getTableMaxId($table, $column)
    { 
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_20_getTableMaxId($table, $column);
        echo json_encode(array('ID'=>$data));
    }

    /*--------------------------
    | branch list
    *--------------------------*/
    public function bdtaskt1c1_22_getBranchList()
    { 
        $branchs = explode(',', session('branch_op'));
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_32_getAssignBranchList($branchs, $this->langColumn);
        echo json_encode($data);
    }

    /*--------------------------
    | diagnosis list
    *--------------------------*/
    public function bdtaskt1c1_23_procedureRoom()
    { 
        $column = ["id", "CONCAT(id, ' - ', $this->langColumn) as text"];
        $where = array('branch_id'=>session('branchId'), 'status'=>1);
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_07_getSelect2Data('operating_room', $where, $column);
        echo json_encode($data);
    }

    /*--------------------------
    | diagnosis list
    *--------------------------*/
    public function bdtaskt1c1_24_countryPhoneCode()
    { 
        $filePath = FCPATH.'assets/data/phone_codes.json';
        $data = openJsonFile($filePath);
        $ArrayData = [];
        $i=0;
        foreach ($data as  $value) {
             $ArrayData[$i]['id'] = $value['dial_code'];
             $ArrayData[$i]['text'] = $value['name'].'('.$value['dial_code'].')';
             $i++;
        }
        echo json_encode($ArrayData);
        exit();
    }

    /*--------------------------
    | Search user name
    *--------------------------*/
    public function bdtaskt1c1_25_searchUserName()
    { 
        $text = $this->request->getVar('term');
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_23_searchUserName($text);
        echo json_encode($data);
    }

    /*--------------------------
    | Search pharmacy customer
    *--------------------------*/
    public function bdtaskt1c1_26_searchPharmacyCustomer()
    { 
        $text = $this->request->getVar('term');
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_25_searchPharmacyCustomer($text, $this->langColumn);
        echo json_encode($data);
    }

    /*--------------------------
    | pharmacy item list
    *--------------------------*/
    public function bdtaskt1c1_27_getPharmacyItem()
    { 
        $column = ["id", "$this->langColumn as text"];
        $where = array('status'=>1);
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_07_getSelect2Data('ph_items', $where, $column);
        echo json_encode($data);
    }

    /*--------------------------
    | Search user name
    *--------------------------*/
    public function bdtaskt1c1_28_searchAllPatient()
    { 
        $text = $this->request->getVar('term');
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_26_searchAllPatient($text, $this->langColumn);
        echo json_encode($data);
    }

    /*--------------------------
    | Delete exist file
    *--------------------------*/
    public function bdtaskt1c1_29_deleteExportExFile()
    { 
        helper('filesystem');
        delete_files('./assets/excel_data/');
        echo json_encode(array('succes'=>true));
    }

    /*--------------------------
    | pharmacy item list
    *--------------------------*/
    public function bdtaskt1c1_30_getAssignDoctorList()
    { 
        $type = $this->request->getVar('type');
        $user_id = $this->request->getVar('user_id');
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_27_getAssignDoctorList($type, $user_id, $this->langColumn);
        echo json_encode($data);
    }

    /*--------------------------
    | pharmacy invoice list
    *--------------------------*/
    public function bdtaskt1c1_31_getPharmacyInvoice()
    { 
        $text = $this->request->getVar('term');
        $column = ["id", "voucher_no as text"];
        $where = array('status'=>1);
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_31_getSelect2DataLike('ph_sale', $column, $where, 'voucher_no', $text);
        echo json_encode($data);
    }
}
