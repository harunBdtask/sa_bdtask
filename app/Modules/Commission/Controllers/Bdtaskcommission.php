<?php

namespace App\Modules\Commission\Controllers;

use CodeIgniter\Controller;
use App\Libraries\Permission;

use App\Modules\Commission\Models\Commissionmodel;
use App\Models\Bdtaskt1m1CommonModel;

class Bdtaskcommission extends BaseController
{
    private $commission_model;
    private $bdtaskt1m12c14_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->commission_model = new Commissionmodel();
        $this->bdtaskt1m12c14_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Instant commission setting
    *--------------------------*/
    public function index()
    {

        $data['title']      = get_phrases(['commission', 'policy']);
        $data['moduleTitle']= get_phrases(['commission']);
        $data['isDTables']  = true;
        $data['module']     = "Commission";
        $data['page']       = "commission_setting";
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    public function bdtaskt1m13c2_01_getSettingList()
    {
        $postData = $this->request->getVar();
        $data = $this->commission_model->bdtaskt1m12_01_getAllCommissionSetting($postData);
        echo json_encode($data);
    }

    public function bdtaskt1m13c2_02_addnewSetting()
    {
        $action = $this->request->getVar('action');

        $data = array(
            'target_start'      => $this->request->getVar('target_start'),
            'target_end'        => $this->request->getVar('target_end'),
            'instant_comm_tk_kg'=> $this->request->getVar('monthly_comm_tk_per_kg'),
            'yearly_comm_tk_kg' => $this->request->getVar('yearly_comm_tk_per_kg'),
            'total_comm_tk_kg'  => $this->request->getVar('total_commission_tk_kg'),
            'another_addition'  => ($this->request->getVar('another_addition') ? $this->request->getVar('another_addition') : ''),
            'comments'          => $this->request->getVar('comments'),
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'target_start'            => 'required',
                'target_end'              => 'required',
                'monthly_comm_tk_per_kg'  => 'required',
                'total_commission_tk_kg'  => 'required',

            ];

            $MesTitle = get_phrases(['commission', 'record']);
            if (!$this->validate($rules)) {
                $response = array(
                    'success'  => false,
                    'message'  => $this->validator->listErrors(),
                    'title'    => $MesTitle
                );
            } else {

                if ($action == 'add') {
                    $isExist = $this->commission_model->bdtaskt1m1_04_exitcommission($data);

                    if ($isExist > 0) {
                        $response = array(
                            'success'  => 'exist',
                            'message'  => get_phrases(['commission', 'range', 'already', 'exists']),
                            'title'    => $MesTitle
                        );
                    } else {
                        $data['created_by']     = session('id');
                        $data['created_date']   = date('Y-m-d H:i:s');

                        $result = $this->commission_model->bdtaskt1m1_055_Insert($data);

                        // Store log data
                        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['instant', 'commission']), get_phrases(['created']), $result, 'instant_commission_setting');

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

                    $isExist = $this->commission_model->bdtaskt1m1_04_exitcommission_update($data, $id);

                    if ($isExist > 0) {
                        $response = array(
                            'success'  => 'exist',
                            'message'  => get_phrases(['commission', 'range', 'already', 'exists']),
                            'title'    => $MesTitle
                        );
                    } else {
                        $data['updated_by']          = session('id');
                        $data['updated_date']        = date('Y-m-d H:i:s');

                        $result = $this->commission_model->bdtaskt1m1_02_Update_commission($data, $id);

                        // Store log data
                        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['commission', 'list']), get_phrases(['updated']), $id, 'instant_commission_setting');

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

    public function bdtaskt1m12c2_04_getcommissionById($id)
    {
        $data = $this->commission_model->bdtaskt1m12_03_getCommissionDetailsById($id);
        $result = array('commission' => $data);
        echo json_encode($result);
    }

    /*delete commission*/

    public function bdtaskt1m12c2_05_deleteCommissionSetting($id)
    {

        $MesTitle = get_phrases(['commission', 'setting', 'record']);
        $data = $this->commission_model->bdtaskt1m1_06_Deleted_Commission($id);
        // Store log data
        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['commission', 'setting']), get_phrases(['deleted']), $id, 'instant_commission_setting');
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

    /*monthly dealer commission generate*/
    public function bdtask003_monthly_dealer_targetRange_commission()
    {
        $data['title']       = get_phrases(['target', 'sale', 'commission']);
        $data['moduleTitle'] = get_phrases(['commission']);
        $data['isDTables']   = true;
        $data['dealer_list'] = $this->commission_model->dealerList();
        $data['module']      = "Commission";
        $data['page']        = "monthly_commission_generate";
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*monthly dealer sales commission generate*/
    public function bdtask004_monthly_dealer_sales_commission()
    {
        $data['title']       = get_phrases(['sales', 'commission']);
        $data['moduleTitle'] = get_phrases(['commission']);
        $data['isDTables']   = true;
        $data['dealer_list'] = $this->commission_model->dealerList();
        $data['module']      = "Commission";
        $data['page']        = "monthly_sales_commission_generate";
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    public function bdtaskt1m12c2_06_getdealercommissionInfo($id)
    {
        $month                  = $this->request->getVar('month');
        $data['dealer_id']      = $id;
        $data['generated_date'] = ($month ? $month : date('m'));
        $isExist                = $this->commission_model->bdtaskt1m1_04_exit_dealer_monthly_commission($data);
        $data                   = $this->commission_model->bdtaskt1m12_06_getDealerSalecommissioninfo($id);
        $salesdata              = $this->commission_model->bdtaskt1m12_07_getDealerSaleamountinfo($id, $month);
        $target_range           = $this->commission_model->bdtaskt1m12_08_getDealerTargetrange($salesdata);
        $range                  = ($target_range ? $target_range->target_start . ' To ' . $target_range->target_end : 'Not Eligible for commission');
        $wight_ton              = ($salesdata ? $salesdata : 0) / 1000;
        $commission_rate        = ($target_range ? $target_range->instant_comm_tk_kg : 0);
        if ($isExist == 0) {
        $result = array('commission_rate' => $commission_rate, 'sold' => $salesdata, 'target_range' => $range, 'wight_ton' => $wight_ton, 'status' => 1);
        } else {
        $result = array('commission_rate' => $commission_rate, 'sold' => $salesdata, 'target_range' => $range, 'wight_ton' => $wight_ton, 'status' => 0);
        }

        echo json_encode($result);
    }

    public function bdtask005_save_monthly_dealer_commission()
    {
        $action = $this->request->getVar('action');

        $data = array(
            'dealer_id'         => $this->request->getVar('dealer_id'),
            'generated_date'    => $this->request->getVar('commission_month'),
            'total_kg'          => $this->request->getVar('sold_qty'),
            'commission_rate'   => $this->request->getVar('commission_rate'),
            'commission_amount' => $this->request->getVar('commission_amount'),
            'target_range'      => $this->request->getVar('reached_target'),
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'dealer_id'               => 'required',
                'commission_month'        => 'required',
                'sold_qty'                => 'required',
                'commission_amount'       => 'required',

            ];

            $MesTitle = get_phrases(['dealer', 'target', 'commission']);
            if (!$this->validate($rules)) {
                $response = array(
                    'success'  => false,
                    'message'  => $this->validator->listErrors(),
                    'title'    => $MesTitle
                );
            } else {

                $isExist = $this->commission_model->bdtaskt1m1_04_exit_dealer_monthly_commission($data);

                if ($isExist > 0) {
                    $response = array(
                        'success'  => 'exist',
                        'message'  => get_phrases(['commission', 'already', 'generated']),
                        'title'    => $MesTitle
                    );
                } else {
                    $data['generate_by']     = session('id');
                    $data['created_date']   = date('Y-m-d H:i:s');

                    $result = $this->commission_model->bdtaskt1m1_07_Insert_sale_commission($data);

                    // Store log data
                    $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['target', 'commission']), get_phrases(['created']), $result, 'monthly_generated_commission');

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
            }

            echo json_encode($response);
        }
    }

    public function bdtaskt1m13c2_02_getSaleCommission()
    {
        $postData = $this->request->getVar();
        $data = $this->commission_model->bdtaskt1m12_03_getAllSalesCommission($postData);
        echo json_encode($data);
    }

    public function bdtaskt1m12c2_08_deletemonthly_saleCommission($id)
    {
        $MesTitle = get_phrases(['commission', 'setting', 'record']);
        $data = $this->commission_model->bdtaskt1m1_06_Deleted_monthly_salescommission($id);
        // Store log data
        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['sales', 'commission']), get_phrases(['deleted']), $id, 'monthly_sale_commission');
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

    public function bdtaskt1m13c2_04_getTargetSaleCommission()
    {
        $postData = $this->request->getVar();
        $data = $this->commission_model->bdtaskt1m12_04_getAllTargetSalesCommission($postData);
        echo json_encode($data);
    }
}
