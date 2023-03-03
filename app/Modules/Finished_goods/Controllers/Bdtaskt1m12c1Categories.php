<?php namespace App\Modules\Finished_goods\Controllers;
use App\Modules\Finished_goods\Views;
use CodeIgniter\Controller;
use App\Modules\Finished_goods\Models\Bdtaskt1m12CategoriesModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1m12c1Categories extends BaseController
{
    private $bdtaskt1m12c1_01_categoriesModel;
    private $bdtaskt1m12c1_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c1_01_categoriesModel = new Bdtaskt1m12CategoriesModel();
        $this->bdtaskt1m12c1_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['item','category', 'list']);
        $data['moduleTitle']= get_phrases(['finished','goods']);
        $data['isDTables']  = true;
        $data['module']     = "Finished_goods";
        $data['page']       = "categories/list";

        $data['hasCreateAccess']        = $this->permission->method('fg_categories', 'create')->access();
        $data['hasPrintAccess']        = $this->permission->method('fg_categories', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('fg_categories', 'export')->access();
        
        //$data['categories']       = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_05_getResultWhere('patient', array('status'=>1));
        //var_dump($data['categories']);exit;
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get categories info
    *--------------------------*/
    public function bdtaskt1m12c1_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c1_01_categoriesModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete categories by ID
    *--------------------------*/
    public function bdtaskt1m12c1_02_deleteCategories($id)
    { 
        $items = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('cat_id'=>$id, 'status'=>1 ));
        $MesTitle = get_phrases(['category', 'record']);
        if( !empty($items) ){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['item', 'exists','in','this','category']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }
        $data = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_06_Deleted('wh_categories', array('id'=>$id));
        // Store log data
        $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','category']), get_phrases(['deleted']), $id, 'wh_categories');

        $MesTitle = get_phrases(['category', 'record']);
        if(!empty($data)){
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
    | Add categories info
    *--------------------------*/
    public function bdtaskt1m12c1_03_addCategories()
    { 

        //$old_image = $this->request->getVar('old_image'); 
        $action = $this->request->getVar('action');
        $data = array(
            'nameE'        => $this->request->getVar('nameE'), 
            'code_no'        => $this->request->getVar('code_no'), 
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'nameE'      => 'required|min_length[4]|max_length[150]',
                'code_no'      => 'required|min_length[2]|max_length[20]',
            ];
        }
        $MesTitle = get_phrases(['category', 'record']);
        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            //$filePath = $this->bdtaskt1c1_01_fileUpload->doUpload('./assets/dist/img/categories', $this->request->getFile('image'));
            if($action=='add'){
                $isExist = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('wh_categories', array('nameE'=>$this->request->getVar('nameE')));
                $isExist2 = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('wh_categories', array('code_no'=>$this->request->getVar('code_no')));
                if(!empty($isExist) || !empty($isExist2) ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases([!empty($isExist)?'name':'code','already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    //$data['image'] = $filePath;
                    $data['created_by']          = session('id');
                    $data['created_date']        = date('Y-m-d H:i:s');

                    $result = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_01_Insert('wh_categories',$data);
                    // Store log data
                    $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','category']), get_phrases(['created']), $result, 'wh_categories');
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
                //$data['image'] = !empty($filePath)?$filePath:$old_image;
                $data['updated_by']          = session('id');
                $data['updated_date']        = date('Y-m-d H:i:s');

                $isExist = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('wh_categories', array('id !='=>$id,'nameE'=>$this->request->getVar('nameE')));
                $isExist2 = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('wh_categories', array('id !='=>$id,'code_no'=>$this->request->getVar('code_no')));
                if(!empty($isExist) || !empty($isExist2) ){
                    $response = array(
                            'success'  => false,
                            'message'  => get_phrases([!empty($isExist)?'name':'code','already', 'exists']),
                            'title'    => $MesTitle
                        );
                    echo json_encode($response);exit;
                }else{
                    $result = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_02_Update('wh_categories',$data, array('id'=>$id));
                }
                if(!empty($result)){
                    // Store log data
                    $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','category']), get_phrases(['updated']), $id, 'wh_categories');
                     $response = array(
                        'success'  =>true,
                        'message'  => get_phrases(['updated', 'successfully']),
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
            
        }
        //var_dump($response);exit;
        echo json_encode($response);
    }

    /*--------------------------
    | Get categories by ID
    *--------------------------*/
    public function bdtaskt1m12c1_04_getCategoriesById($id)
    { 
        $data = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('wh_categories', array('id'=>$id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get category details by ID
    *--------------------------*/
    public function bdtaskt1m12c1_05_getCategoryDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c1_01_categoriesModel->bdtaskt1m12_03_getCategoryDetailsById($id);
        echo json_encode($data);
    }

   
}
