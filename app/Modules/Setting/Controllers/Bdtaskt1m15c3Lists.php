<?php namespace App\Modules\Setting\Controllers;
use App\Modules\Setting\Views;
use CodeIgniter\Controller;
use App\Modules\Setting\Models\Bdtaskt1m15ListsModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1m15c3Lists extends BaseController
{
    private $bdtaskt1m15c3_01_listsModel;
    private $bdtaskt1m15c3_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m15c3_01_listsModel = new Bdtaskt1m15ListsModel();
        $this->bdtaskt1m15c3_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['parameter','list']);
        $data['moduleTitle']= get_phrases(['settings']);
        $data['isDTables']  = true;
        $data['module']     = "Setting";
        $data['page']       = "lists/list";

        $data['hasCreateAccess'] = $this->permission->method('parameter_list', 'create')->access();
        $data['hasPrintAccess'] = $this->permission->method('parameter_list', 'print')->access();
        $data['hasExportAccess'] = $this->permission->method('parameter_list', 'export')->access();

        $data['list_tables']   = $this->bdtaskt1m15c3_02_CmModel->bdtaskt1m1_05_getResultWhere('lists', array('status'=>1)); 
       
        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | Get lists info
    *--------------------------*/
    public function bdtaskt1m15c3_01_getList()
    { 
        $postData = $this->request->getVar();
        $list_tables   = $this->bdtaskt1m15c3_02_CmModel->bdtaskt1m1_05_getResultWhere('lists', array('status'=>1));
        //var_dump($list_tables);exit;
        $data = $this->bdtaskt1m15c3_01_listsModel->bdtaskt1m15_02_getAll($postData, $list_tables);
        echo json_encode($data);
    }

    /*--------------------------
    | delete lists by ID
    *--------------------------*/
    public function bdtaskt1m15c3_02_deleteLists($id)
    { 
        $data = $this->bdtaskt1m15c3_02_CmModel->bdtaskt1m1_06_Deleted('list_data', array('id'=>$id));
        $MesTitle = get_phrases(['list', 'record']);
        if(!empty($data)){
            // Store log data
            $this->bdtaskt1m15c3_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['list', 'data']), get_phrases(['deleted']), $id, 'list_data', 3);
            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['deleted', 'successfully']),
                'title'    => $MesTitle
            );
        }else{
            $response = array(
                'success'  =>false,
                'message'  => (ENVIRONMENT == 'production')?get_phrases(['something', 'went', 'wrong']):get_db_error(),
                'title'    => $MesTitle
            );
        }
        echo json_encode($response);
    }

    /*--------------------------
    | Add lists info
    *--------------------------*/
    public function bdtaskt1m15c3_03_addLists()
    { 

        //$old_image = $this->request->getVar('old_image'); 
        $action = $this->request->getVar('action');
        $data = array(
            'list_id'           => $this->request->getVar('list_id'), 
            'nameE'             => $this->request->getVar('nameE'), 
            'nameA'             => $this->request->getVar('nameA'), 
        );
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'list_id'       => 'required',
                'nameE'         => 'required',
                // 'nameA'         => 'required',
            ];
        }
        $MesTitle = get_phrases(['list', 'record']);
        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            //$filePath = $this->bdtaskt1c1_01_fileUpload->doUpload('./assets/dist/img/lists', $this->request->getFile('image'));
            //$lists = $this->bdtaskt1m15c3_02_CmModel->bdtaskt1m1_03_getRow('lists', array('id'=>$this->request->getVar('list_id')) );
            //$table = $lists->table_name;

            if($action=='add'){
                $isExist = $this->bdtaskt1m15c3_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('list_id'           => $this->request->getVar('list_id'),'nameE'=>$this->request->getVar('nameE')) );
                $isExist2 = $this->bdtaskt1m15c3_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('list_id'           => $this->request->getVar('list_id'),'nameA'=>$this->request->getVar('nameA')) );

                if(!empty($isExist) ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['name','already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    //$data['image'] = $filePath;
                    $result = $this->bdtaskt1m15c3_02_CmModel->bdtaskt1m1_01_Insert('list_data',$data);
                    if($result){
                        // Store log data
                        $this->bdtaskt1m15c3_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['list', 'data']), get_phrases(['created']), $result, 'list_data', 1);
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
                }
            }else{
                $id = $this->request->getVar('id');
                //$data['image'] = !empty($filePath)?$filePath:$old_image;
                $result = $this->bdtaskt1m15c3_02_CmModel->bdtaskt1m1_02_Update('list_data',$data, array('id'=>$id));
                if($result){
                    // Store log data
                    $this->bdtaskt1m15c3_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['list', 'data']), get_phrases(['updated']), $id, 'list_data', 2);
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
    | Get lists by ID
    *--------------------------*/
    public function bdtaskt1m15c3_04_getListsById($id)
    { 
        $data = $this->bdtaskt1m15c3_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$id));
        echo json_encode($data);
    }

   
}
