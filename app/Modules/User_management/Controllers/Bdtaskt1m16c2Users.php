<?php namespace App\Modules\User_management\Controllers;
use CodeIgniter\Controller;
use App\Modules\User_management\Models\Bdtaskt1m16User;
use App\Models\Bdtaskt1m1CommonModel;
class Bdtaskt1m16c2Users extends BaseController
{
    private $bdtaskt1m16c2_01_mModel;
    private $bdtaskt1m16c2_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bdtaskt1m16c2_01_usrModel = new Bdtaskt1m16User();
        $this->bdtaskt1m16c2_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | role list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['user', 'list']);
        $data['moduleTitle']= get_phrases(['permission']);
        $data['isDTables']  = true;
        $data['isDateTimes']= true;
        $data['roles']      = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_05_getResultWhere('sec_role', array('status'=>1));
        $data['branch_list']      = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        $data['dept_list']      = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_05_getResultWhere('hrm_employee_types');
        $data['sec_role_list']      = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_05_getResultWhere('sec_role', array('status'=>1));
        $data['wh_store_list']      = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_sale_store', array('status'=>1,'branch_id'=>session('branchId')));
        $data['ph_store_list']      = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_sale_store', array('status'=>1,'branch_id'=>session('branchId')));
        $data['module']     = "User_management";
        $data['page']       = "bdtaskt1m16v5_userList";
        return $this->base16_02_template->layout($data);
    }

    /*--------------------------
    | Get all users info
    *--------------------------*/
    public function bdtaskt1m16c2_01_list()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m16c2_01_usrModel->bdtaskt1m16_01_getUserList($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | Get module by ID
    *--------------------------*/
    public function bdtaskt1m16c2_02_userById($id)
    { 
        $data = $this->bdtaskt1m16c2_01_usrModel->bdtaskt1m16_05_getUserById($id);
        echo json_encode($data);
    }

    /*--------------------------
    | search employee info
    *--------------------------*/
    public function bdtaskt1m16c2_03_searchEmployee()
    { 
        $text = $this->request->getVar('term');
        $response = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_09_searchEmployee($text);
        echo json_encode($response);
    }

    /*--------------------------
    | Get role info
    *--------------------------*/
    public function bdtaskt1m16c2_04_getRoles()
    { 
        $column = ["id", "CONCAT_WS(' ', type, '-', title) as text"];
        $where = array('status'=>1);
        $data = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_07_getSelect2Data('sec_role', $where, $column);
        echo json_encode($data);
    }

    /*--------------------------
    | Add user info
    *--------------------------*/
    public function bdtaskt1m16c2_05_addUser()
    { 
        $roles = $this->request->getVar('role_id');
        $existRole = $this->request->getVar('exist_role_id');
        $action = $this->request->getVar('action');
        // store data

        $role_array = explode(",", $roles);
        if( in_array(23, $role_array) || in_array(24, $role_array) ){
            $store_id = $this->request->getVar('store_id');
        } else {
            $store_id = 0;
        }
        if( in_array(17, $role_array) ){
            $store_id = $this->request->getVar('wh_store_id');
        } else if( in_array(18, $role_array) ){
            $store_id = $this->request->getVar('ph_store_id');
        } else {
            $store_id = 0;
        }
        

        $data = array(
            'emp_id'        => $this->request->getVar('emp_id'), 
            'fullname'      => $this->request->getVar('fullname'),  
            'username'      => $this->request->getVar('username'), 
            //'email'       => $this->request->getVar('email'), 
            'mobile'        => $this->request->getVar('mobile'),
            'branch_id'     => '',
            'department_id' => $this->request->getVar('department_id'),
            'store_id'  => $store_id,
            'store_id'      => $store_id,
            'user_role'     => $roles, 
            //'date_of_birth'=> date('Y-m-d', strtotime($this->request->getVar('dob'))), 
            //'gender'       => $this->request->getVar('gender'), 
            'created_by'   => session('id'), 
            'status'       => $this->request->getVar('status')
        );
        if(!empty($this->request->getVar('password'))){
            $data['password'] = md5($this->request->getVar('password'));
        }

        $MesTitle = get_phrases(['user', 'record']);
        if($action=='add'){
            $check_emp_id = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_03_getRow('user', array('emp_id'=>$data['emp_id']));
            if(!empty($check_emp_id)){
                $response = array(
                    'success'  => false,
                    'message'  => get_phrases(['employee', 'id', 'already', 'exists']).'!',
                    'title'    => $MesTitle
                );
                echo json_encode($response);exit;
            }
            $checkUserName = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_03_getRow('user', array('username'=>$data['username']));
            if(!empty($checkUserName)){
                $response = array(
                    'success'  => false,
                    'message'  => get_phrases(['user', 'name', 'already', 'token']).'!',
                    'title'    => $MesTitle
                );
            }else{
                $result = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_01_Insert('user',$data);
                if($result){
                    // Store log data
                    $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['user']), get_phrases(['created']), $result, 'user', 1);
                    if(!empty($roles)){
                        $aData = array(
                            'user_id'    => $this->request->getVar('emp_id'),
                            'roleid'     => $roles,
                            'created_by' => session('id')
                        );
                        $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_01_Insert('sec_userrole',$aData);
                    }
                    $response = array(
                        'success'  =>true,
                        'message'  => get_phrases(['added', 'successfully']),
                        'title'    => $MesTitle
                    );
                }else{
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['something', 'went', 'wrong']),
                        'title'    => $MesTitle
                    );
                }
            }
        }else{
            $data['id'] = $this->request->getVar('user_id');
            $checkUserName = $this->bdtaskt1m16c2_01_usrModel->bdtaskt1m16_09_get_exist_username($data['emp_id'], $data['username']);
            if($checkUserName){
                $response = array(
                    'success'  => false,
                    'message'  => get_phrases(['user','name', 'already', 'token']).'!',
                    'title'    => $MesTitle
                );
            }else{
                $result = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_02_Update('user',$data, array('id'=>$data['id']));
                if($result){
                    // Store log data
                    $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['user']), get_phrases(['updated']), $data['id'], 'user', 2);
                    if($roles != $existRole){
                        $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_06_Deleted('sec_userrole', array('user_id'=>$data['emp_id'], 'roleid'=>$existRole));
                        $aData = array(
                            'user_id'    => $this->request->getVar('emp_id'),
                            'roleid'     => $roles,
                            'created_by' => session('id')
                        );
                        $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_01_Insert('sec_userrole',$aData);
                    }

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
        }
        
        echo json_encode($response);
    }

    /*--------------------------
    | Get role info
    *--------------------------*/
    public function bdtaskt1m16c2_06_empList()
    { 
        $column = ["employee_id as id", "CONCAT_WS(' ', first_name, last_name) as text"];
        $where = array('status'=>1);
        $find_in_set = "";
        $data = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_07_getSelect2Data('hrm_employees', $where, $column, $find_in_set);
        echo json_encode($data);
    }

    /*--------------------------
    | Check user activities info
    *--------------------------*/
    public function bdtaskt1m16c2_07_isUserActive()
    { 
        $activities = $this->session->getTempdata('isUserActivity');
        $isLogin    = $this->session->get('isLogIn');
        if($isLogin == true && $activities==false){
            $val = false;
        }else if($isLogin == true && $activities==true){
            $this->session->setTempdata('isUserActivity', true, 900);
            $val = true;
        }else{
            $val = 3;
        }
        echo json_encode(array('success'=>$val));
    }

    /*--------------------------
    | Get role info
    *--------------------------*/
    public function bdtaskt1m16c2_08_empRoles($empId)
    { 
        $data = $this->bdtaskt1m16c2_01_usrModel->bdtaskt1m16_06_getRolesByEmpId($empId);
        echo json_encode($data);
    }

    /*--------------------------
    | Add more user role
    *--------------------------*/
    public function bdtaskt1m16c2_09_addMoreRole()
    { 
        $empId    = $this->request->getVar('employee_id');
        $mainRole = $this->request->getVar('main_role');
        $roles    = $this->request->getVar('role_ids');

        $MesTitle = get_phrases(['user', 'record']);
        if(!empty($roles) && is_array($roles)){
            $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_06_Deleted('sec_userrole', array('user_id'=>$empId));

            $allRoles = array();
            $allRoles[] = array('user_id'=>$empId, 'roleid'=>$mainRole, 'created_by'=>session('id'));
            for ($i=0; $i < sizeof($roles); $i++) { 
               $allRoles[] = array('user_id'=>$empId, 'roleid'=>$roles[$i], 'created_by'=>session('id'));
            }

            $result = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_01_Insert_Batch('sec_userrole',$allRoles);
            if($result){
                // Store log data
                $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['extra ','role']), get_phrases(['created']), $mainRole, 'sec_userrole', 1);
                $response = array(
                    'success'  => true,
                    'message'  => get_phrases(['role', 'updated', 'successfully']),
                    'title'    => $MesTitle
                );
            }else{
                $response = array(
                    'success'  => false,
                    'message'  => get_phrases(['something', 'went', 'wrong']),
                    'title'    => $MesTitle
                );
            }
        }else{
            $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_06_Deleted('sec_userrole', array('user_id'=>$empId));
            $aData = array('user_id'=>$empId, 'roleid'=>$mainRole, 'created_by'=>session('id'));
            $result = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_01_Insert('sec_userrole',$aData);
            if($result){
                // Store log data
                $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['extra ','role']), get_phrases(['created']), $mainRole, 'sec_userrole', 1);
                $response = array(
                    'success'  => true,
                    'message'  => get_phrases(['role', 'updated', 'successfully']),
                    'title'    => $MesTitle
                );
            }else{
                $response = array(
                    'success'  => false,
                    'message'  => get_phrases(['something', 'went', 'wrong']),
                    'title'    => $MesTitle
                );
            }
        }
        echo json_encode($response);
    }

    /*--------------------------
    | role list
    *--------------------------*/
    public function bdtaskt1m16c2_10_activityLogs()
    {
        $data['title']      = get_phrases(['user', 'activity']);
        $data['moduleTitle']= get_phrases(['profile']);
        $data['isDateTimes']= true;
        $data['module']     = "User_management";
        $data['page']       = "bdtaskt1m16v6_userActivity";
        return $this->base16_02_template->layout($data);
    }

    /*--------------------------
    | Get module by ID
    *--------------------------*/
    public function bdtaskt1m16c2_11_getEmployeeById($id)
    { 
        $data = $this->bdtaskt1m16c2_01_usrModel->bdtaskt1m16_07_getEmployeeById($id);
        echo json_encode($data);
    }


    /*--------------------------
    | Get departments info
    *--------------------------*/
    public function bdtaskt1c1_05_getDepartments()
    { 
        $column = ['id', 'type as text'];
        $where = '';
        $data = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_07_getSelect2Data('hrm_employee_types', $where, $column);
        echo json_encode($data);
    }

    /*--------------------------
    | Get module by ID
    *--------------------------*/
    public function bdtaskt1m16c2_12_get_store_name($id=0)
    { 
        $type = $this->request->getVar('type');
        $data = $this->bdtaskt1m16c2_01_usrModel->bdtaskt1m16_08_get_store_name($id, $type);
        echo json_encode($data);
    }

    /*--------------------------
    | Get user by ID for update
    *--------------------------*/
    public function bdtaskt1m16c2_13_getUserForEdit($id)
    { 
        $data = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_03_getRow('user', array('emp_id'=>$id));
        echo json_encode($data);
    }

    /*--------------------------
    | Add user info
    *--------------------------*/
    public function bdtaskt1m16c2_14_updateUser()
    { 
        // store data
        $data = array(
            'id'            => $this->request->getVar('user_id'),
            'emp_id'        => $this->request->getVar('emp_id'), 
            'fullname'      => $this->request->getVar('fullname'),  
            'username'      => $this->request->getVar('username'), 
        );
        if(!empty($this->request->getVar('password'))){
            $data['password'] = md5($this->request->getVar('password'));
        }

        $MesTitle = get_phrases(['user', 'record']);
        $checkUserName = $this->bdtaskt1m16c2_01_usrModel->bdtaskt1m16_09_get_exist_username($data['emp_id'], $data['username']);
        if($checkUserName==false){
            $result = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_02_Update('user',$data, array('id'=>$data['id']));
    
            if($result){
                $this->session->set('fullname', $data['fullname']);
                $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_22_addActivityLog('User', 'Updated', $data['id'], 'user', 2);

                $response = array(
                    'success'  =>true,
                    'message'  => get_phrases(['profile', 'updated', 'successfully']).'!',
                    'title'    => $MesTitle
                );
            }else{
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['something', 'went', 'wrong']),
                    'title'    => $MesTitle
                );
            }
        }else{
            $response = array(
                'success'  => false,
                'message'  => get_phrases(['user','name', 'already', 'token']).'!',
                'title'    => $MesTitle
            );
        }
        
        echo json_encode($response);
    }

    /*--------------------------
    | delete
    *--------------------------*/
    public function bdtaskt1m16c2_16_delete($id)
    {
        $emp_id = $this->request->getVar('emp');
        $activity_logs_info = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_05_getResultWhere('activity_logs', array('emp_id'=>$emp_id));
        $MesTitle = get_phrases(['user', 'record']);
        if( !empty($activity_logs_info) ){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['user','exists','activity','logs']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }
        $delete_res = $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_06_Deleted('user', array('id'=>$id));
        if ($delete_res) {
            // Store log data
            $this->bdtaskt1m16c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['user', 'record']), get_phrases(['deleted']), $id, 'user');
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
