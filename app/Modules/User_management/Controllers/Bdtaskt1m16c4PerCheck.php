<?php namespace App\Modules\User_management\Controllers;
use CodeIgniter\Controller;
use App\Modules\User_management\Models\Bdtaskt1m16PerCheckModel;
use App\Models\Bdtaskt1m1CommonModel;
class Bdtaskt1m16c4PerCheck extends BaseController
{
    private $bdtaskt1m16c2_01_mModel;
    private $bdtaskt1m16c2_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bdtaskt1m16c2_01_usrModel = new Bdtaskt1m16PerCheckModel();
        $this->bdtaskt1m16c2_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | role list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['user', 'permission', 'check']);
        $data['moduleTitle']= get_phrases(['permission']);
        $data['isDTables']  = true;
        $data['isDateTimes']= true;
        $data['module']     = "User_management";
        $data['page']       = "bdtaskt1m16v7_perChecker";

        $where = array('status'=>1);
        if( session('isAdmin') !=true ){
            $where['emp_id'] = session('id');
        }
        $data['users']      = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_05_getResultWhere('user', $where);
        $data['module_list']      = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_05_getResultWhere('module', array('status'=>1));
        $data['sub_module_list']      = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_05_getResultWhere('sub_module', array('status'=>1));
        
        return $this->base16_02_template->layout($data);
    }

    /*--------------------------
    | Get all users info
    *--------------------------*/
    public function bdtaskt1m16c2_01_get_check_result()
    { 
        $emp_id = $this->request->getVar('emp_id');
        $module = $this->request->getVar('module');
        $sub_module = $this->request->getVar('sub_module');
        $data = $this->bdtaskt1m16c2_01_usrModel->bdtaskt1m16_01_get_check_result($emp_id,$module,$sub_module);
        $response = array(
                        'success'  => true,
                        'message'  => get_phrases(['success']),
                        'title'    => get_phrases(['permission','checker']),
                        'data'      => $data,
                    );
        echo json_encode($response);
    }

    /*--------------------------
    | Get module by ID
    *--------------------------*/
    public function bdtaskt1m16c2_02_getSubModule($id)
    { 
        $data = $this->bdtaskt1m16c2_01_usrModel->bdtaskt1m16_02_getSubModule($id);
        echo json_encode($data);
    }


}
