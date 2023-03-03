<?php namespace App\Modules\Account\Controllers;
use CodeIgniter\Controller;
use App\Modules\Account\Models\Bdtaskt1m15FinanYearModel;
use App\Models\Bdtaskt1m1CommonModel;

class Bdtaskt1m15c4FinancialYear extends BaseController
{
    private $bdtaskt1m15c4_01_fyModel;
    private $bdtaskt1m15c4_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bdtaskt1m15c4_01_fyModel = new Bdtaskt1m15FinanYearModel();
        $this->bdtaskt1m15c4_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Financial year list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['set','financial', 'year']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['isDTables']  = true;
        $data['module']     = "Account";
        $data['page']       = "financial_year_list";
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Get financial lists info
    *--------------------------*/
    public function bdtaskt1m15c4_01_getFinYearList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m15c4_01_fyModel->bdtaskt1m15_01_getFinancialYear($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | Add & update financial year
    *--------------------------*/
    public function bdtaskt1m15c4_02_addFinancialYear()
    { 
        $action    = $this->request->getVar('action');
        $startDate = $this->request->getVar('startDate');
        $endDate   = $this->request->getVar('endDate');
        $MesTitle = get_phrases(['financial', 'year']);
        $checkDate = $this->bdtaskt1m15c4_01_fyModel->bdtaskt1m15_02_checkFyDateExist($startDate, $endDate);
        if(!empty($checkDate)){
            $response = array(
                'success'  => false,
                'message'  => get_notify('start_or_end_date_has_already_existed'),
                'title'    => $MesTitle
            );
            echo json_encode($response);exit();
        }
        $data = array(
            'yearName'   => $this->request->getVar('yearName'),
            'startDate'  => $startDate,
            'endDate'    => $endDate,
        );

        if($action=='add'){
            $data['created_by']   = session('id');
            $results = $this->bdtaskt1m15c4_02_CmModel->bdtaskt1m1_01_Insert('financial_year', $data);
            if(!empty($results)){
                // Store log data
                $this->bdtaskt1m15c4_02_CmModel->bdtaskt1m1_22_addActivityLog('Add financial year', 'Created', $results, 'financial_year', 1);
                $response = array(
                    'success'  => true,
                    'message'  => get_phrases(['added', 'successfully']),
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
            $fy_id = $this->request->getVar('fy_id');
            $data['updated_by']   = session('id');
            $data['updated_date'] = date('Y-m-d H:i:s');
            $results = $this->bdtaskt1m15c4_02_CmModel->bdtaskt1m1_02_Update('financial_year', $data, array('id'=>$fy_id));
            if(!empty($results)){
                // Store log data
                $this->bdtaskt1m15c4_02_CmModel->bdtaskt1m1_22_addActivityLog('Financial Year', 'Updated',$fy_id, 'financial_year', 2);
                $response = array(
                    'success'  => true,
                    'message'  => get_phrases(['updated', 'successfully']),
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
    | Get financial year by ID
    *--------------------------*/
    public function bdtaskt1m15c4_03_getFyById()
    { 
        $fy_id = $this->request->getVar('fy_id');
        $data = $this->bdtaskt1m15c4_02_CmModel->bdtaskt1m1_03_getRow('financial_year', array('id'=>$fy_id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get active, close request and undo financial year
    *--------------------------*/
    public function bdtaskt1m15c4_04_activeFinancialYear()
    { 
        $fy_id = $this->request->getVar('fy_id');
        $action = $this->request->getVar('action');
        $MesTitle = get_phrases(['financial', 'year']);
        if($action=='activate'){
            $checkAtive = $this->bdtaskt1m15c4_02_CmModel->bdtaskt1m1_03_getRow('financial_year', array('status'=>1));
            if(!empty($checkAtive)){
                $response = array(
                    'success'  => false,
                    'message'  => get_notify('please_close_the_active_financial_year_first'),
                    'title'    => $MesTitle
                );
            }else{
                $results = $this->bdtaskt1m15c4_02_CmModel->bdtaskt1m1_02_Update('financial_year', array('status'=>1), array('id'=>$fy_id));
                if(!empty($results)){
                    // Store log data
                    $this->bdtaskt1m15c4_02_CmModel->bdtaskt1m1_22_addActivityLog('Activate financial year', 'Created', $fy_id, 'financial_year', 3);
                    $response = array(
                        'success'  => true,
                        'message'  => get_phrases(['activated', 'successfully']),
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
        }else{
            // Close request
            if($action=='undo'){
                $isCloseReq = 0;
                $msg = get_phrases(['undo', 'successfully']);
            }else{
                $isCloseReq = 1;
                $msg = get_phrases(['request', 'sent', 'successfully']);
            }
            $results = $this->bdtaskt1m15c4_02_CmModel->bdtaskt1m1_02_Update('financial_year', array('isCloseReq'=>$isCloseReq), array('id'=>$fy_id));
            if(!empty($results)){
                // Store log data
                $this->bdtaskt1m15c4_02_CmModel->bdtaskt1m1_22_addActivityLog('Closed financial year', 'Created', $fy_id, 'financial_year', 3);
                $response = array(
                    'success'  => true,
                    'message'  => $msg,
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
    | Get active financial year
    *--------------------------*/
    public function bdtaskt1m15c4_05_closeFinancialYear()
    { 
        $fy_id = $this->request->getVar('fy_id');
        $MesTitle = get_phrases(['financial', 'year']);
        $results = $this->bdtaskt1m15c4_02_CmModel->bdtaskt1m1_02_Update('financial_year', array('status'=>1), array('id'=>$fy_id));
        if(!empty($results)){
            // Store log data
            $this->bdtaskt1m15c4_02_CmModel->bdtaskt1m1_22_addActivityLog('Activate financial year', 'Created', $fy_id, 'financial_year', 3);
            $response = array(
                'success'  => true,
                'message'  => get_phrases(['activated', 'successfully']),
                'title'    => $MesTitle
            );
        }else{
            $response = array(
                'success'  => false,
                'message'  => get_phrases(['something', 'went', 'wrong']),
                'title'    => $MesTitle
            );
        }
        echo json_encode($response);
    }

   
}
