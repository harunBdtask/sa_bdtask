<?php namespace App\Modules\User_management\Controllers;
use CodeIgniter\Controller;
use App\Modules\User_management\Models\Bdtaskt1m16Permission;
use App\Models\Bdtaskt1m1CommonModel;
class Bdtaskt1m16c1Permissions extends BaseController
{
    private $bdtaskt1m16c1_01_PermModel;
    private $bdtaskt1m16c1_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bdtaskt1m16c1_01_PermModel = new Bdtaskt1m16Permission();
        $this->bdtaskt1m16c1_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | role list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['role', 'list']);
        $data['moduleTitle']= get_phrases(['permission']);
        $data['roles'] = $this->bdtaskt1m16c1_02_CmModel->bdtaskt1m1_04_getResult('sec_role');
        
        $data['module']     = "User_management";
        $data['page']       = "bdtaskt1m16v1_roleList";
        return $this->base16_02_template->layout($data);
    }

    /*--------------------------
    | add role form
    *--------------------------*/
    public function bdtaskt1m16c1_01_addRole()
    {
        $data['title']      = get_phrases(['add', 'role']);
        $data['moduleTitle']= get_phrases(['permission']);
        $data['accounts'] = $this->bdtaskt1m16c1_01_PermModel->bdtaskt1m16_01_moduleList();
        
        $data['module']     = "User_management";
        $data['page']       = "bdtaskt1m16v2_roleForm";
        return $this->base16_02_template->layout($data);
    }

    /*--------------------------
    | insert new role
    *--------------------------*/
    public function bdtaskt1m16c1_02_createRole()
    {
        $data = array(
            'type' => $this->request->getVar('role_id'),
            'title'=> $this->request->getVar('title'),
        );
        $insert_id    = $this->bdtaskt1m16c1_01_PermModel->bdtaskt1m16_02_insertNewRole($data);
        $fk_module_id = $this->request->getVar('fk_module_id');
        $create       = $this->request->getVar('create');
        $read         = $this->request->getVar('read');
        $update       = $this->request->getVar('update');
        $delete       = $this->request->getVar('delete');
        $print       = $this->request->getVar('print');
        $export       = $this->request->getVar('export');


        $new_array = array();
        for ($m = 0; $m < sizeof($fk_module_id); $m++) {
            for ($i = 0; $i < sizeof($fk_module_id[$m]); $i++) {
                for ($j = 0; $j < sizeof($fk_module_id[$m][$i]); $j++) {
                    $dataStore = array(
                        'role_id' => $insert_id,
                        'fk_module_id' => $fk_module_id[$m][$i][$j],
                        'create' => (!empty($create[$m][$i][$j]) ? $create[$m][$i][$j] : 0),
                        'read'   => (!empty($read[$m][$i][$j]) ? $read[$m][$i][$j] : 0),
                        'update' => (!empty($update[$m][$i][$j]) ? $update[$m][$i][$j] : 0),
                        'delete' => (!empty($delete[$m][$i][$j]) ? $delete[$m][$i][$j] : 0),
                        'print' => (!empty($print[$m][$i][$j]) ? $print[$m][$i][$j] : 0),
                        'export' => (!empty($export[$m][$i][$j]) ? $export[$m][$i][$j] : 0),
                    );
                    array_push($new_array, $dataStore);
                }
            }
        }

        /*-----------------------------------*/
        $MesTitle = get_phrases(['role', 'permission', 'record']);
        if ($this->bdtaskt1m16c1_01_PermModel->bdtaskt1m16_03_insertRolePermission($insert_id, $new_array)) {
            // Store log data
            $this->bdtaskt1m16c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['role', 'permission']), get_phrases(['created']), $insert_id, 'role_permission', 1);
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
        echo json_encode($response);
    }

    /*--------------------------
    | edit role form
    *--------------------------*/
    public function bdtaskt1m16c1_03_editRole($id)
    {
        $data['title']      = get_phrases(['update', 'role']);
        $data['moduleTitle']= get_phrases(['permission']);
        $data['accounts'] = $this->bdtaskt1m16c1_01_PermModel->bdtaskt1m16_04_allPermission($id);
        $data['role'] = $this->bdtaskt1m16c1_02_CmModel->bdtaskt1m1_03_getRow('sec_role', array('id'=>$id));
        $data['module']     = "User_management";
        $data['page']       = "bdtaskt1m16v3_roleEdit";
        return $this->base16_02_template->layout($data);
    }

    /*--------------------------
    | update role assign
    *--------------------------*/
    public function bdtaskt1m16c1_04_updateRole()
    {
        $role_id = $this->request->getVar('role_id');
        $data = array(
            'type' => $this->request->getVar('role_name'),
            'title'=> $this->request->getVar('title'),
        );
        $this->bdtaskt1m16c1_02_CmModel->bdtaskt1m1_02_Update('sec_role', $data, array('id'=>$role_id));
        $fk_module_id = $this->request->getVar('fk_module_id');
        $create       = $this->request->getVar('create');
        $read         = $this->request->getVar('read');
        $update       = $this->request->getVar('update');
        $delete       = $this->request->getVar('delete');
        $print       = $this->request->getVar('print');
        $export       = $this->request->getVar('export');


        $new_array = array();
        for ($m = 0; $m < sizeof($fk_module_id); $m++) {
            for ($i = 0; $i < sizeof($fk_module_id[$m]); $i++) {
                for ($j = 0; $j < sizeof($fk_module_id[$m][$i]); $j++) {
                    $dataStore = array(
                        'role_id' => $role_id,
                        'fk_module_id' => $fk_module_id[$m][$i][$j],
                        'create' => (!empty($create[$m][$i][$j]) ? $create[$m][$i][$j] : 0),
                        'read' =>   (!empty($read[$m][$i][$j]) ? $read[$m][$i][$j] : 0),
                        'update' => (!empty($update[$m][$i][$j]) ? $update[$m][$i][$j] : 0),
                        'delete' => (!empty($delete[$m][$i][$j]) ? $delete[$m][$i][$j] : 0),
                        'print' => (!empty($print[$m][$i][$j]) ? $print[$m][$i][$j] : 0),
                        'export' => (!empty($export[$m][$i][$j]) ? $export[$m][$i][$j] : 0),
                    );
                    array_push($new_array, $dataStore);
                }
            }
        }

        /*-----------------------------------*/
        $MesTitle = get_phrases(['role', 'permission', 'record']);
        if ($this->bdtaskt1m16c1_01_PermModel->bdtaskt1m16_03_insertRolePermission($role_id, $new_array)) {
            // Store log data
            $this->bdtaskt1m16c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['role', 'permission']), get_phrases(['updated']), $role_id, 'role_permission', 2);
            $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['update', 'successfully']),
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
    | delete role assign
    *--------------------------*/
    public function bdtaskt1m16c1_05_deleteRole($id)
    {
        $user_info = $this->bdtaskt1m16c1_02_CmModel->bdtaskt1m1_05_getResultWhere('user', array('user_role'=>$id));
        $sec_userrole_info = $this->bdtaskt1m16c1_02_CmModel->bdtaskt1m1_05_getResultWhere('sec_userrole', array('roleid'=>$id));
        $MesTitle = get_phrases(['role', 'permission', 'record']);
        if( !empty($user_info) || !empty($sec_userrole_info) ){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['role','exists','in','user']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }
        if ($this->bdtaskt1m16c1_01_PermModel->bdtaskt1m16_07_deleteRole($id)) {
             // Store log data
            $this->bdtaskt1m16c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['role', 'permission']), get_phrases(['deleted']), $id, 'role_permission', 3);
            $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['deleted', 'successfully']),
                    'title'    => $MesTitle
            );
        }else{
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['something', 'went', 'wrong']),
                'title'    => $MesTitle
            );
        }
        echo json_encode($response);
    }
   
}
