<?php
namespace App\Modules\Sale\Controllers;

use App\Modules\Sale\Views;
use CodeIgniter\Controller;
use App\Modules\Sale\Models\Bdtaskt1m12DealerModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1m12c2Dealers extends BaseController
{
    private $bdtaskt1m12c2_01_dealerModel;
    private $bdtaskt1m12c2_02_CmModel;
    private $subTypeId;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->subTypeId = 6;
        $this->permission                   = new Permission();
        $this->bdtaskt1m12c2_01_dealerModel = new Bdtaskt1m12DealerModel();
        $this->bdtaskt1m12c2_02_CmModel     = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']                = get_phrases(['dealer', 'list']);
        $data['moduleTitle']          = get_phrases(['sale', 'dealer']);
        $data['isDTables']            = true;
        $data['module']               = "Sale";
        $data['g_affiliate_code']     = $this->generator();
        $data['zone_list']            = $this->bdtaskt1m12c2_01_dealerModel->bdtask_002_zonelist();
        $data['referar_list']         = $this->bdtaskt1m12c2_01_dealerModel->bdtask_003_referarlist();
        $data['dealer_code']          = $this->generator_dealer_code();
        $data['page']                 = "dealer/list";
        $data['sales_officer']        = $this->bdtaskt1m12c2_01_dealerModel->sales_manList();
        $data['hasCreateAccess']      = $this->permission->method('dealer_list', 'create')->access();
        $data['hasPrintAccess']       = $this->permission->method('dealer_list', 'print')->access();
        $data['hasExportAccess']      = $this->permission->method('dealer_list', 'export')->access();
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get dealers info
    *--------------------------*/
    public function bdtaskt1m13c2_01_getList()
    {
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c2_01_dealerModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete dealers by ID
    *--------------------------*/
    public function bdtaskt1m12c2_02_deleteDealers($id)
    {
        // $requisition = $this->bdtaskt1m12c2_01_dealerModel->bdtaskt1m1_05_getexist($id);
        $items = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_05_getResultWhere('do_main', array('dealer_id'=>$id));
        $MesTitle = get_phrases(['dealer', 'record']);
        if (!empty($items)) {
            $response = array(
                'success'  => false,
                'message'  => get_phrases(['dealer', 'record', 'exists', 'in', 'DO']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }

        // $data = $this->bdtaskt1m12c2_01_dealerModel->bdtaskt1m1_07_Deleted($id);
        $data = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_06_Deleted('dealer_info', array('id'=>$id));
        $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_06_Deleted('acc_subcode', array('referenceNo'=>$id, 'subTypeId' => $this->subTypeId));
        // Store log data
        $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['dealer', 'list']), get_phrases(['deleted']), $id, 'dealer_info');
        if (!empty($data)) {
            $response = array(
                'success'  => true,
                'message'  => get_phrases(['deleted', 'successfully']),
                'title'    => $MesTitle
            );
        } else {
            $response = array(
                'success'  => false,
                'message'  => (ENVIRONMENT == 'production') ? get_phrases(['something', 'went', 'wrong']) : get_db_error(),
                'title'    => $MesTitle
            );
        }
        echo json_encode($response);
    }

    /*--------------------------
    | Add dealers info
    *--------------------------*/
    public function bdtaskt1m12c2_03_adddealers()
    {

        $action = $this->request->getVar('action');

        $data = array(
            'name'              => $this->request->getVar('name'),
            'address'           => $this->request->getVar('address'),
            'email'             => $this->request->getVar('email'),
            'phone_no'          => $this->request->getVar('phone_no'),
            // 'dealer_code'       => $this->generator_dealer_code(),
            'dealer_code'       => $this->request->getVar('dealer_code'),
            'address'           => $this->request->getVar('address'),
            'type'              => $this->request->getVar('type'),
            'commission_rate'   => ($this->request->getVar('commission_rate') ? $this->request->getVar('commission_rate') : 0),
            'closing_date'      => ($this->request->getVar('closing_date') ? $this->request->getVar('closing_date') : null),
            'agrement_date'     => ($this->request->getVar('agrement_date') ? $this->request->getVar('agrement_date') : null),
            'credit_amount'     => ($this->request->getVar('credit_amount') ? $this->request->getVar('credit_amount') : 0),
            'sales_officer_id ' => $this->request->getVar('sales_officer'),
            // 'affiliat_id'       => $this->generator(),
            'affiliat_id'       => $this->request->getVar('affiliat_id'),
            'reference_id'      => $this->request->getVar('reference_id'),
            'zone_id'           => $this->request->getVar('zone_id'),
            'created_by'        => session('id'),
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'name'            => 'required',
                'phone_no'        => 'required',
                'affiliat_id'     => 'required',
                'sales_officer'   => 'required',
                'zone_id'         => 'required',
                'commission_rate' => 'numeric',
                'address'         => 'required',

            ];

            $MesTitle = get_phrases(['dealer', 'record']);
            if (!$this->validate($rules)) {
                $response = array(
                    'success'  => false,
                    'message'  => $this->validator->listErrors(),
                    'title'    => $MesTitle
                );
            } else {

                if ($action == 'add') {
                    $isExist = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_03_getRow('dealer_info', array('phone_no' => $this->request->getVar('phone_no')));

                    if (!empty($isExist)) {
                        $response = array(
                            'success'  => 'exist',
                            'message'  => get_phrases(['dealer', 'already', 'exists']),
                            'title'    => $MesTitle
                        );
                    } else {
                        $data['created_by']     = session('id');
                        $data['created_date']   = date('Y-m-d H:i:s');

                        // $result = $this->bdtaskt1m12c2_01_dealerModel->bdtaskt1m1_055_Insert('dealer_info', $data);
                        $result = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_01_Insert('dealer_info',$data);
                        //acc_subcode entry
                        $subcode_data = array(
                            'subTypeId' => $this->subTypeId,
                            'name' => $data['name'],
                            'referenceNo' => $result,
                            'status' => 1,
                            'created_date' => date('Y-m-d'),
                        );
                        $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_01_Insert('acc_subcode', $subcode_data);

                        // Store log data
                        $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['dealer', 'list']), get_phrases(['created']), $result, 'dealer_info');

                        if ($result) {
                            $response = array(
                                'success'  => true,
                                'message'  => get_phrases(['added', 'successfully']),
                                'title'    => $MesTitle
                            );
                        } else {
                            $response = array(
                                'success'  => false,
                                'message'  => (ENVIRONMENT == 'production') ? get_phrases(['something', 'went', 'wrong']) : get_db_error(),
                                'title'    => $MesTitle
                            );
                        }
                    }
                } else {
                    $id = $this->request->getVar('id');

                    $isExist = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_03_getRow('dealer_info', array('phone_no' => $this->request->getVar('phone_no'), 'id !=' => $id));
                    $isExist2 = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_03_getRow('acc_subcode', array('subTypeId' => $this->subTypeId, 'referenceNo' => $id));

                    if (!empty($isExist)) {
                        $response = array(
                            'success'  => 'exist',
                            'message'  => get_phrases(['dealer', 'already', 'exists']),
                            'title'    => $MesTitle
                        );
                    } else {
                        $data['updated_by']          = session('id');
                        $data['updated_date']        = date('Y-m-d H:i:s');

                        // $result = $this->bdtaskt1m12c2_01_dealerModel->bdtaskt1m1_02_Update('dealer_info', $data, $id);
                        if (empty($isExist2)) {
                            //acc_subcode entry
                            $subcode_data = array(
                                'subTypeId' => $this->subTypeId,
                                'name' => $data['name'],
                                'referenceNo' => $id,
                                'status' => 1,
                                'created_date' => date('Y-m-d'),
                            );
                            $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_01_Insert('acc_subcode', $subcode_data);
                        }else{
                            $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_02_Update('acc_subcode', array('name'=>$data['name']), array('referenceNo'=>$id));
                        }
                        $result = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_02_Update('dealer_info',$data, array('id'=>$id));

                        // Store log data
                        $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['dealer', 'list']), get_phrases(['updated']), $id, 'dealer_info');

                        if ($result) {
                            $response = array(
                                'success'  => true,
                                'message'  => get_phrases(['updated', 'successfully']),
                                'title'    => $MesTitle
                            );
                        } else {
                            $response = array(
                                'success'  => false,
                                'message'  => (ENVIRONMENT == 'production') ? get_phrases(['something', 'went', 'wrong']) : get_db_error(),
                                'title'    => $MesTitle
                            );
                        }
                    }
                }
            }

            echo json_encode($response);
        }
    }

    /*--------------------------
    | Get dealers by ID
    *--------------------------*/
    public function bdtaskt1m12c2_04_getdealersById($id)
    {
        $data = $this->bdtaskt1m12c2_01_dealerModel->bdtaskt1m12_03_getDealerDetailsById($id);
        $previous = $this->bdtaskt1m12c2_01_dealerModel->bdtaskt1m12_03_getDealerPreviousBalance($id);
        $result = array('dealer' => $data,'previous_balance' => $previous);
        echo json_encode($result);
    }

    /*--------------------------
    | Get dealer details by ID
    *--------------------------*/
    public function bdtaskt1m12c2_05_getdealerDetailsById($id)
    {
        $data = $this->bdtaskt1m12c2_01_dealerModel->bdtaskt1m12_03_getDealerDetailsById($id);

        $result = array('dealer' => $data);
        echo json_encode($result);
    }

    public function generator()
    {
        $afficode = $this->bdtaskt1m12c2_01_dealerModel->max_delarinfo();
        
        // $laffilcode = ($afficode ? $afficode->affiliat_id : '');
        // $affiliat_id = ($laffilcode ? explode('.', $laffilcode) : '');
        // $af_code = ($laffilcode ? $affiliat_id[2] + 1 : 1);
        // return 'AFFi-' . date('y.m') . '.' . ($af_code >= 10 ? '00' : '000') . $af_code;

        $affcode = ($afficode ? $afficode->id : '');
        $laffilcode = (int)$affcode + 1;
        return 'AFFi-' . date('y.m') . '.' . ($laffilcode >= 10 ? '00' : '000') . $laffilcode;
    }

    public function generator_dealer_code()
    {
        $afficode = $this->bdtaskt1m12c2_01_dealerModel->max_delarinfo();

        $affcode = ($afficode ? $afficode->id : '');
        $laffilcode = (int)$affcode + 1;
        return 'DLR-' . date('y.m') . '.' . ($laffilcode >= 10 ? '00' : '000') . $laffilcode;
    }

    /*--------------------------
    | Get dealerdropdown info
    *--------------------------*/
    public function bdtaskt1c1_05_getDealersDropdown()
    {
        $column = ['affiliat_id as id', 'name as text'];
        $where  = array('status' => 1);
        $data   = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_07_getSelect2Data('dealer_info', $where, $column);
        echo json_encode($data);
    }

    public function bdtaskt1c1_07_checkAssignedSalesofficer()
    {
        $officer_id  = $this->request->getVar('officer_id');
        $id          = $this->request->getVar('id');
        $action      = $this->request->getVar('action');
        if ($action == 'add') {
            $isExist = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_03_getRow('dealer_info', array('sales_officer_id' => $officer_id));
        } else {
            $isExist = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_03_getRow('dealer_info', array('sales_officer_id' => $officer_id, 'id !=' => $id));
        }

        if (!empty($isExist)) {
            $response = array(
                'status'   => 1,
                'message'  => get_phrases(['sales', 'officer', 'already', 'assigned', 'another', 'please', 'choose', 'another']),

            );
        } else {
            $response = array(
                'status'   => 0,
                'message'  => get_phrases(['you', 'can', 'choose']),

            );
        }

        echo json_encode($response);
    }

    /*--------------------------
    | Get assigned officers info
    *--------------------------*/
    public function bdtaskt1m1c1_08_assignedOfficers($id)
    { 
        $data = $this->bdtaskt1m12c2_01_dealerModel->getSalesOfficersByDealerId($id);
        echo json_encode($data);
    }

    public function bdtaskt1m1c1_09_getRoles()
    { 
        $column = ["emp_id as id", "fullname as text"];
        $where = array('status'=>1);
        $data = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_07_getSelect2Data('user', $where, $column);
        echo json_encode($data);
    }

    /*--------------------------
    | Add more user role
    *--------------------------*/
    public function bdtaskt1m1c1_10_addMoreRole()
    { 
        $dealer_id    = $this->request->getVar('dealer_id');
        $roles    = $this->request->getVar('role_ids');
        $MesTitle = get_phrases(['user', 'record']);
        if(!empty($roles) && is_array($roles)){
            $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_06_Deleted('sales_officer_assign', array('dealer_id'=>$dealer_id));

            $allRoles = array();
            for ($i=0; $i < sizeof($roles); $i++) { 
               $allRoles[] = array('dealer_id'=>$dealer_id, 'sales_officer_id'=>$roles[$i], 'created_by'=>session('id'));
            }
            $result = $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_01_Insert_Batch('sales_officer_assign',$allRoles);
            if($result){
                $this->bdtaskt1m12c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['officer ','assigned']), get_phrases(['created']), $result, 'sales_officer_assign', 1);
                $response = array(
                    'success'  => true,
                    'message'  => get_phrases(['officer', 'assigned', 'successfully']),
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
            $response = array(
                'success'  => false,
                'message'  => get_phrases(['please', 'assign', 'sales', 'officer']),
                'title'    => $MesTitle
            );
        }
        echo json_encode($response);
    }

}
