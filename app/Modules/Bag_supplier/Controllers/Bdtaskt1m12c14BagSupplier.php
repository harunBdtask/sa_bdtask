<?php namespace App\Modules\Bag_supplier\Controllers;
use CodeIgniter\Controller;
use App\Modules\Bag_supplier\Models\Bdtaskt1m12BagSupplierModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1m12c14BagSupplier extends BaseController
{
    private $bdtaskt1m12c14_01_supModel;
    private $bdtaskt1m12c14_02_CmModel;
    private $subTypeId;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->subTypeId = 7;
        $this->permission = new Permission();
        $this->bdtaskt1m12c14_01_supModel = new Bdtaskt1m12BagSupplierModel();
        $this->bdtaskt1m12c14_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Supplier list
    *--------------------------*/
    public function index()
    {
        $data['moduleTitle']= get_phrases(['bag', 'supplier']);
        $data['title']      = get_phrases(['bag', 'supplier', 'list']);
        $data['isDTables']  = true;
        $data['module']     = "Bag_supplier";
        $data['page']       = "supplier/list";
        $data['setting']    = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('setting');

        $data['hasCreateAccess']        = $this->permission->method('wh_bag_supplier', 'create')->access();
        $data['hasPrintAccess']         = $this->permission->method('wh_bag_supplier', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('wh_bag_supplier', 'export')->access();
        
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get all suppliers info
    *--------------------------*/
    public function bdtaskt1m12c14_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c14_01_supModel->bdtaskt1m12_01_getAllSupplier($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | Add supplier info
    *--------------------------*/
    public function bdtaskt1m12c14_02_addSupplier()
    {

        $action = $this->request->getVar('action');
        
        $data = array(
            'nameE'        => $this->request->getVar('nameE'), 
            'phone'        => $this->request->getVar('phone'), 
            'email'        => $this->request->getVar('email'), 
            // 'credit_limit' => $this->request->getVar('credit_limit'), 
            'address'      => $this->request->getVar('address'),
            'ssn'          => $this->request->getVar('ssn'),
            'country'      => $this->request->getVar('country'),
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'nameE'             => 'required|min_length[4]|max_length[150]',
            ];
        }
        $MesTitle = get_phrases(['supplier', 'record']);
        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            if($action=='add'){
                $code_no = 'SUP-'.getMAXID('wh_bag_supplier_information', 'id');
                $data['code_no'] = $code_no;

                $isExist = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_supplier_information', array('nameE'=>$this->request->getVar('nameE')));
                $isExist2 = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_supplier_information', array('code_no'=>$code_no));
                if(!empty($isExist) || !empty($isExist2) ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    $file = $this->request->getFile('logo');
                    $filePath = $this->bdtaskt1c1_01_fileUpload->doUpload('./assets/dist/img/supplier', $file);
                    if ($filePath) {
                        $data['logo'] = ltrim($filePath, '.');
                    }else{
                        $data['logo'] = '/assets/dist/default.jpg';
                    }
                    $data['created_by'] = session('id');
                    $result = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert('wh_bag_supplier_information',$data);
                    if($result){
                        // $maxCoa = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_18_getLikeMaxData('acc_coa', '2211','HeadCode');
                        // $Coa = ((int)$maxCoa + 1);
                        // $coaData = [
                        //     'HeadCode'         => $Coa,
                        //     'HeadName'         => $data['nameE'],
                        //     'PHeadName'        => 'Suppliers',
                        //     'nameE'            => $data['nameE'],
                        //     'HeadLevel'        => '4',
                        //     'IsActive'         => '1',
                        //     'IsTransaction'    => '1',
                        //     'IsGL'             => '0',
                        //     'HeadType'         => 'L',
                        //     'IsBudget'         => '0',
                        //     'IsDepreciation'   => '0',
                        //     'DepreciationRate' => '0',
                        //     'CreateBy'         => session('id'),
                        //     'CreateDate'       => date('Y-m-d H:i:s'),
                        // ]; 
                        // $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert('acc_coa',$coaData);
                        // $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_02_Update('wh_bag_supplier_information',array('acc_head'=>$Coa), array('id'=>$result));

                        //acc_subcode entry
                        $subcode_data = array(
                            'subTypeId' => $this->subTypeId,
                            'name' => $data['nameE'],
                            'referenceNo' => $result,
                            'status' => 1,
                            'created_date' => date('Y-m-d'),
                        );
                        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert('acc_subcode', $subcode_data);
                        // Store log data
                        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','supplier']), get_phrases(['created']), $result, 'wh_bag_supplier_information');

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
                $info = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_supplier_information', array('id'=>$id));
                $isExist2 = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('acc_subcode', array('subTypeId' => $this->subTypeId, 'referenceNo' => $id));

                $file = $this->request->getFile('logo');
                $filePath = $this->bdtaskt1c1_01_fileUpload->doUpload('./assets/dist/img/supplier', $file);
                if ($filePath) {
                    if(file_exists('.'.$info->logo)){
                        unlink('.'.$info->logo);
                    }
                    $data['logo'] = ltrim($filePath, '.');
                }
                $result = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_02_Update('wh_bag_supplier_information',$data, array('id'=>$id));
                
                if($result){
                    // $maxCoa = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_18_getLikeMaxData('acc_coa', '2211','HeadCode');
                    // $Coa = ((int)$maxCoa + 1);
                    // $coaData = [
                    //     'HeadCode'         => $Coa,
                    //     'HeadName'         => $data['nameE'],
                    //     'PHeadName'        => 'Suppliers',
                    //     'nameE'            => $data['nameE'],
                    //     'HeadLevel'        => '4',
                    //     'IsActive'         => '1',
                    //     'IsTransaction'    => '1',
                    //     'IsGL'             => '0',
                    //     'HeadType'         => 'L',
                    //     'IsBudget'         => '0',
                    //     'IsDepreciation'   => '0',
                    //     'DepreciationRate' => '0',
                    //     'CreateBy'         => session('id'),
                    //     'CreateDate'       => date('Y-m-d H:i:s'),
                    // ]; 
                    
                    // if (empty($info->acc_head)) {
                    //     $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert('acc_coa',$coaData);
                    //     $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_02_Update('wh_bag_supplier_information',array('acc_head'=>$Coa), array('id'=>$id));

                    // }else{
                    //     $acc_coa_data['HeadName']          = $data['nameE'];
                    //     $acc_coa_data['nameE']             = $data['nameE'];
                    //     $acc_coa_data['UpdateBy']          = session('id');
                    //     $acc_coa_data['UpdateDate']        = date('Y-m-d H:i:s');

                    //     $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_02_Update('acc_coa',$acc_coa_data, array('HeadCode'=>$info->acc_head));
                    // }

                    if (empty($isExist2)) {
                        //acc_subcode entry
                        $subcode_data = array(
                            'subTypeId' => $this->subTypeId,
                            'name' => $data['nameE'],
                            'referenceNo' => $id,
                            'status' => 1,
                            'created_date' => date('Y-m-d'),
                        );
                        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert('acc_subcode', $subcode_data);
                    }else{
                        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_02_Update('acc_subcode', array('name'=>$data['name']), array('referenceNo'=>$id));
                    }
                    // Store log data
                    $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','supplier']), get_phrases(['updated']), $id, 'wh_bag_supplier_information');
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
    | delete categories by ID
    *--------------------------*/
    public function bdtaskt1m12c14_03_deleteSupplier($id, $accCode)
    { 
        $quatation = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_quatation', array('supplier_id'=>$id, 'status'=>1 ));
        $MesTitle = get_phrases(['supplier', 'record']);

        if(!empty($quatation)){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['purchase', 'record', 'exists']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }

        $data = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_06_Deleted('wh_bag_supplier_information', array('id'=>$id));
        if(!empty($data)){
            $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_06_Deleted('acc_subcode', array('referenceNo'=>$id, 'subTypeId' => $this->subTypeId));
            // $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_06_Deleted('acc_coa', array('HeadCode'=>$accCode));
            // $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_06_Deleted('acc_transaction', array('COAID'=>$accCode));
            // Store log data
            $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','supplier']), get_phrases(['deleted']), $id, 'wh_bag_supplier_information');
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

    /*--------------------------
    | Get supplier by ID
    *--------------------------*/
    public function bdtaskt1m12c14_04_getSupplierById($id)
    { 
        $data = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_supplier_information', array('id'=>$id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get supplier details by ID
    *--------------------------*/
    public function bdtaskt1m12c14_05_getDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_supplier_information', array('id'=>$id));
        $list = '';
        if(!empty($data)){
            $country_name = ($data->country)?countries($data->country):'';
            if($data->status==1){
                $status = get_phrases(['active']);
            }else{
                $status = get_phrases(['inactive']);
            }
            $list .= '<tr>
                        <th class="text-right">'.get_phrases(['supplier', 'logo']).'</th>
                        <td class="text-left"><img class="img-fluid" src="'.base_url().$data->logo.'" height="80" width="120" /></td>                    
                        <th class="text-right">'.get_phrases(['code', 'no']).'</th>
                        <td class="text-left">'.$data->code_no.'</td>
                    </tr>
                    <tr>
                        <th class="text-right">'.get_phrases(['name']).'</th>
                        <td class="text-left">'.$data->nameE.'</td>
                        <th class="text-right">'.get_phrases(['country']).'</th>
                        <td class="text-left">'.$country_name.'</td>
                    </tr>
                    <tr>
                        <th class="text-right">'.get_phrases(['phone', 'number']).'</th>
                        <td class="text-left">'.$data->phone.'</td>
                        <th class="text-right">'.get_phrases(['email']).'</th>
                        <td class="text-left">'.$data->email.'</td>
                    </tr>
                    <tr>
                        <th class="text-right">'.get_phrases(['status']).'</th>
                        <td class="text-left">'.$status.'</td>
                        <th class="text-right">'.get_phrases(['address']).'</th>
                        <td class="text-left">'.$data->address.'</td>
                    </tr>';
        }
        echo json_encode(array('data'=>$list));
    }

   
}
