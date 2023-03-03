<?php namespace App\Modules\User_management\Controllers;
use CodeIgniter\Controller;
use App\Modules\User_management\Models\Bdtaskt1m16Module;
use App\Models\Bdtaskt1m1CommonModel;
class Bdtaskt1m16c3Modules extends BaseController
{
    private $bdtaskt1m16c3_01_mModel;
    private $bdtaskt1m16c3_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bdtaskt1m16c3_01_mModel = new Bdtaskt1m16Module();
        $this->bdtaskt1m16c3_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | role list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['module', 'list']);
        $data['moduleTitle']= get_phrases(['permission']);
        $data['isDTables']  = true;
        $data['module']     = "User_management";
        $data['page']       = "bdtaskt1m16v4_moduleList";
        return $this->base16_02_template->layout($data);
    }

    /*--------------------------
    | Get all module info
    *--------------------------*/
    public function bdtaskt1m16c3_01_getModules()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m16c3_01_mModel->bdtaskt1m16_01_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | Get module by ID
    *--------------------------*/
    public function bdtaskt1m16c3_02_moduleById($id)
    { 
        $data = $this->bdtaskt1m16c3_02_CmModel->bdtaskt1m1_03_getRow('module', array('id'=>$id));
        echo json_encode($data);
    }

    /*--------------------------
    | Update module info
    *--------------------------*/
    public function bdtaskt1m16c3_03_updateModule()
    { 
        $module_id  = $this->request->getVar('module_id'); 
        // base data
        $data = array(
            'nameE'      => $this->request->getVar('nameE'), 
            'nameA'       => $this->request->getVar('nameA'),  
        );

        $MesTitle = get_phrases(['Module', 'record']);
        
        $result = $this->bdtaskt1m16c3_02_CmModel->bdtaskt1m1_02_Update('module',$data, array('id'=>$module_id));
        
        if($result){
             $response = array(
                'success'  =>true,
                'message'  => get_phrases(['updated', 'successfully']),
                'title'    => $MesTitle
            );
        }else{
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['something', 'want', 'wrong']),
                'title'    => $MesTitle
            );
        }
        echo json_encode($response);
    }

     /*--------------------------
    | Get module by ID
    *--------------------------*/
    public function bdtaskt1m16c3_04_allModules()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m16c3_01_mModel->bdtaskt1m16_02_getAllModules($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | Get module by ID
    *--------------------------*/
    public function bdtaskt1m16c3_05_moduleMenuById($id)
    { 
        $data = $this->bdtaskt1m16c3_01_mModel->bdtaskt1m16_03_getModuleMenu($id);
        echo json_encode($data);
    }

    /*--------------------------
    | Update module info
    *--------------------------*/
    public function bdtaskt1m16c3_06_updateModuleMenu()
    { 
        $action  = $this->request->getVar('action'); 
        // base data
        $data = array(
            'nameE'      => $this->request->getVar('menuE'), 
            'nameA'       => $this->request->getVar('menuA'),  
        );

        $MesTitle = get_phrases(['Module', 'menu', 'record']);
        if($action=='add'){
            $directory = preg_replace('/[^a-zA-Z0-9_]/', '_', $this->request->getVar('directory'));
            $directory = strtolower($directory);
            $data['mid'] = $this->request->getVar('moduleId');
            $data['directory'] = $directory;
            $result = $this->bdtaskt1m16c3_02_CmModel->bdtaskt1m1_01_Insert('sub_module', $data);
            if($result){
                 $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['added', 'successfully']),
                    'title'    => $MesTitle
                );
            }else{
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['something', 'want', 'wrong']),
                    'title'    => $MesTitle
                );
            }
        }else{
            $menu_id  = $this->request->getVar('sub_id'); 
            $result = $this->bdtaskt1m16c3_02_CmModel->bdtaskt1m1_02_Update('sub_module',$data, array('id'=>$menu_id));
        
            if($result){
                 $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['updated', 'successfully']),
                    'title'    => $MesTitle
                );
            }else{
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['something', 'want', 'wrong']),
                    'title'    => $MesTitle
                );
            }
        }
        
        echo json_encode($response);
    }

    /*--------------------------
    | Get module List
    *--------------------------*/
    public function bdtaskt1m16c3_07_moduleList()
    { 
        $column = ["id", "CONCAT_WS(' ', nameE, '-', nameA) as text"];
        $where = array('status'=>1);
        $data = $this->bdtaskt1m16c3_02_CmModel->bdtaskt1m1_07_getSelect2Data('module', $where, $column);
        echo json_encode($data);
    }

}
