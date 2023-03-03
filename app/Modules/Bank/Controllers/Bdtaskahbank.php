<?php namespace App\Modules\Bank\Controllers;
use CodeIgniter\Controller;
use App\Libraries\Permission;

use App\Modules\Bank\Models\Bankmodel;
use App\Models\Bdtaskt1m1CommonModel;

class Bdtaskahbank extends BaseController
{
    private $bankmodel;
    private $bdtaskt1m12c14_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bankmodel = new Bankmodel();
        $this->bdtaskt1m12c14_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Supplier list
    *--------------------------*/
    public function index()
    {

        $data['title']      = get_phrases(['Bank', 'list']);
        $data['moduleTitle']= get_phrases(['Bank','management']);
        $data['hasCreateAccess']        = $this->permission->method('wh_bag_supplier', 'create')->access();
        $data['hasPrintAccess']         = $this->permission->method('wh_bag_supplier', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('wh_bag_supplier', 'export')->access();
        $data['isDTables']  = true;
        $data['module']     = "Bank";
        $data['page']       = "bank/list";

        $data['lists'] = $this->bankmodel->get_list();

        return $this->bdtaskt1c1_02_template->layout($data);
    }


    /*--------------------------
    | Get all info
    *--------------------------*/
    public function getAllBank()
    { 
        $postData = $this->request->getVar();
        $data = $this->bankmodel->getAllBank($postData);
        echo json_encode($data);
    }



    /*--------------------------
    | Add bank info
    *--------------------------*/
    public function bdtasktt_add_bank()
    { 

        $action = $this->request->getVar('action');
        
        $data = array(
            'bank_name'       => $this->request->getVar('bank_name'), 
            'account_name'    => $this->request->getVar('account_name'), 
            'account_number'  => $this->request->getVar('account_number'),
            'branch_name'     => $this->request->getVar('branch_name'),
            'address'         => $this->request->getVar('address')
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'bank_name'             => 'required',
                'account_name'          => 'required',
                'account_number'        => 'required',
            ];
        }

        $MesTitle = 'Bank record';

        if (! $this->validate($rules)) {

            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{

            if($action=='add'){

                $isExist = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('ah_bank', array('bank_name'=>$this->request->getVar('bank_name')));

                if(!empty($isExist) ){
                    $response = array(
                        'success'  =>'exist',
                        'message'  => get_phrases(['already', 'exists']),
                        'title'    => $MesTitle
                    );
                }else{

                    $data['created_by'] = session('id');
                    $result = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert('ah_bank',$data);

                    $maxCoa = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_18_getLikeMaxData('acc_coa', '1212','HeadCode');
                    $Coa = ((int)$maxCoa + 1);
                    $coaData = [
                        'HeadCode'         => $Coa,
                        'HeadName'         => $data['bank_name'],
                        'PHeadName'        => 'Cash At Bank',
                        'nameE'            => $data['bank_name'],
                        'HeadLevel'        => '4',
                        'IsActive'         => '1',
                        'IsTransaction'    => '1',
                        'IsGL'             => '0',
                        'HeadType'         => 'A',
                        'IsBudget'         => '0',
                        'IsDepreciation'   => '0',
                        'DepreciationRate' => '0',
                        'CreateBy'         => session('id'),
                        'CreateDate'       => date('Y-m-d H:i:s'),
                    ]; 
                    $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert('acc_coa',$coaData);
                    //acc_head_liabilities
                    $maxCoa2 = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_18_getLikeMaxData('acc_coa', '200002','HeadCode');
                    $Coa2 = ((int)$maxCoa2 + 1);
                    $coaData2 = [
                        'HeadCode'         => $Coa2,
                        'HeadName'         => 'LC-'.$data['bank_name'],
                        'PHeadName'        => 'LC-LOAN',
                        'nameE'            => 'LC-'.$data['bank_name'],
                        'HeadLevel'        => '2',
                        'IsActive'         => '1',
                        'IsTransaction'    => '1',
                        'IsGL'             => '1',
                        'HeadType'         => 'L',
                        'IsBudget'         => '0',
                        'IsDepreciation'   => '0',
                        'DepreciationRate' => '0',
                        'CreateBy'         => session('id'),
                        'CreateDate'       => date('Y-m-d H:i:s'),
                    ]; 
                    $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert('acc_coa',$coaData2);
                    //update acc_head
                    $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_02_Update('ah_bank',array('acc_head'=>$Coa, 'acc_head_liabilities'=>$Coa2), array('bank_id'=>$result));

                    // Store log data
                    $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['bank']), get_phrases(['created']), $result, 'ah_bank');
                    
                    if($result){
                        $response = array(
                            'success'  =>true,
                            'message'  => get_phrases(['added', 'successfully']),
                            'title'    => $MesTitle
                        );
                    }else{
                        $response = array(
                            'success'  =>false,
                            'message'  => (ENVIRONMENT == 'production')?get_phrases(['something', 'went', 'wrong']):get_db_error(),
                            'title'    => $MesTitle
                        );
                    }

                }

            }else{

                $id = $this->request->getVar('id');
                $info = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('ah_bank', array('bank_id'=>$id));
                $result = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_02_Update('ah_bank',$data, array('bank_id'=>$id));
                
                $maxCoa = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_18_getLikeMaxData('acc_coa', '1212','HeadCode');
                $Coa = ((int)$maxCoa + 1);
                $coaData = [
                    'HeadCode'         => $Coa,
                    'HeadName'         => $data['bank_name'],
                    'PHeadName'        => 'Cash At Bank',
                    'nameE'            => $data['bank_name'],
                    'HeadLevel'        => '4',
                    'IsActive'         => '1',
                    'IsTransaction'    => '1',
                    'IsGL'             => '0',
                    'HeadType'         => 'A',
                    'IsBudget'         => '0',
                    'IsDepreciation'   => '0',
                    'DepreciationRate' => '0',
                    'CreateBy'         => session('id'),
                    'CreateDate'       => date('Y-m-d H:i:s'),
                ]; 
                
                if (empty($info->acc_head)) {
                    $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert('acc_coa',$coaData);
                    $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_02_Update('ah_bank',array('acc_head'=>$Coa), array('bank_id'=>$id));
                }else{
                    $acc_coa_data['HeadName']          = $data['bank_name'];
                    $acc_coa_data['nameE']             = $data['bank_name'];
                    $acc_coa_data['UpdateBy']          = session('id');
                    $acc_coa_data['UpdateDate']        = date('Y-m-d H:i:s');

                    $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_02_Update('acc_coa',$acc_coa_data, array('HeadCode'=>$info->acc_head));
                }
                //acc_head_liabilities
                $maxCoa2 = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_18_getLikeMaxData('acc_coa', '200002','HeadCode');
                $Coa2 = ((int)$maxCoa2 + 1);
                $coaData2 = [
                    'HeadCode'         => $Coa2,
                    'HeadName'         => 'LC-'.$data['bank_name'],
                    'PHeadName'        => 'LC-LOAN',
                    'nameE'            => 'LC-'.$data['bank_name'],
                    'HeadLevel'        => '2',
                    'IsActive'         => '1',
                    'IsTransaction'    => '1',
                    'IsGL'             => '1',
                    'HeadType'         => 'L',
                    'IsBudget'         => '0',
                    'IsDepreciation'   => '0',
                    'DepreciationRate' => '0',
                    'CreateBy'         => session('id'),
                    'CreateDate'       => date('Y-m-d H:i:s'),
                ]; 
                
                if (empty($info->acc_head_liabilities)) {
                    $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert('acc_coa',$coaData2);
                    $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_02_Update('ah_bank',array('acc_head_liabilities'=>$Coa2), array('bank_id'=>$id));
                }else{
                    $acc_coa_data['HeadName']          = $coaData2['HeadName'];
                    $acc_coa_data['nameE']             = $coaData2['HeadName'];
                    $acc_coa_data['UpdateBy']          = session('id');
                    $acc_coa_data['UpdateDate']        = date('Y-m-d H:i:s');

                    $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_02_Update('acc_coa',$acc_coa_data, array('HeadCode'=>$info->acc_head_liabilities));
                }
                // Store log data
                $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['bank']), get_phrases(['updated']), $id, 'ah_bank');
                    
                
                if($result){
                    $response = array(
                        'success'  =>true,
                        'message'  => get_phrases(['updated', 'successfully']),
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
            
        }
        
        echo json_encode($response);
    }



    /*--------------------------
    | Get bank by ID
    *--------------------------*/
    public function bdtasktt_get_bank($id)
    { 
        $data = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('ah_bank', array('bank_id'=>$id));
        echo json_encode($data);
    }


    
    
    
    /*--------------------------
    | delete bank by ID
    *--------------------------*/
    public function bdtasktt_delete_bank($id, $accCode)
    {
        $MesTitle = get_phrases(['bank', 'record']);
        $isExist = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_05_getResultWhere('ah_lc', array('lc_bank_id'=>$id ));
        if(!empty($isExist)){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['bank', 'record', 'exists']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }

        $info = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('ah_bank', array('bank_id'=>$id));

        if(!empty($info)){
            $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_06_Deleted('acc_coa', array('HeadCode'=>$info->acc_head_liabilities));
            $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_06_Deleted('acc_coa', array('HeadCode'=>$accCode));
            $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_06_Deleted('ah_bank', array('bank_id'=>$id));
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
