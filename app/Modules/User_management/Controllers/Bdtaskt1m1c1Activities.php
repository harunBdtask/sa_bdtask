<?php namespace App\Modules\User_management\Controllers;
use CodeIgniter\Controller;
use App\Modules\User_management\Models\Bdtaskt1m1ActivitiesModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1m1c1Activities extends BaseController
{
    private $bdtaskt1m1c1_01_mModel;
    private $bdtaskt1m1c1_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bdtaskt1m1c1_01_mModel = new Bdtaskt1m1ActivitiesModel();
        $this->bdtaskt1m1c1_02_CmModel = new Bdtaskt1m1CommonModel();
        $this->permission = new Permission();
    }

    /*--------------------------
    | role list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['activities', 'list']);
        $data['moduleTitle']= get_phrases(['permission']);
        $data['isDTables']  = true;
        $data['module']     = "User_management";
        $data['page']       = "bdtaskt1m16v8_activitiesList";
        $data['hasPrintAccess']     = $this->permission->method('activities', 'print')->access();
        $data['hasExportAccess']    = $this->permission->method('activities', 'export')->access();
        $data['setting']    = $this->bdtaskt1m1c1_02_CmModel->bdtaskt1m1_03_getRow('setting');
        return $this->base16_02_template->layout($data);
    }

    /*--------------------------
    | Get all module info
    *--------------------------*/
    public function bdtaskt1m1c1_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m1c1_01_mModel->bdtaskt1m1_01_getAll($postData);
        echo json_encode($data);
    }

    

}
