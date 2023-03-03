<?php namespace App\Modules\Material\Controllers;
use App\Modules\Material\Views;
use CodeIgniter\Controller;
use App\Modules\Material\Models\Bdtaskt1m12CategoriesModel;
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
        $data['title']      = get_phrases(['material','category', 'list']);
        $data['moduleTitle']= get_phrases(['raw','material']);
        $data['isDTables']  = true;
        $data['module']     = "Material";
        $data['page']       = "categories/list";
        $data['setting']    = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('setting');

        $data['hasCreateAccess']        = $this->permission->method('categories', 'create')->access();
        $data['hasPrintAccess']        = $this->permission->method('categories', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('categories', 'export')->access();
        
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
        $items = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material', array('cat_id'=>$id, 'status'=>1 ));
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
        $data = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_06_Deleted('wh_material_categories', array('id'=>$id));
        // Store log data
        $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','category']), get_phrases(['deleted']), $id, 'wh_material_categories');

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

        $action = $this->request->getVar('action');
        $data = array(
            'nameE'        => $this->request->getVar('nameE'), 
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'nameE'      => 'required|min_length[2]|max_length[150]',
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
            if($action=='add'){
                $isExist = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('wh_material_categories', array('nameE'=>$this->request->getVar('nameE')));
                if(!empty($isExist)){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    $data['created_by']          = session('id');
                    $data['created_date']        = date('Y-m-d H:i:s');

                    $result = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_01_Insert('wh_material_categories',$data);
                    // Store log data
                    $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','category']), get_phrases(['created']), $result, 'wh_material_categories');
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
                $data['updated_by']          = session('id');
                $data['updated_date']        = date('Y-m-d H:i:s');

                $result = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_02_Update('wh_material_categories',$data, array('id'=>$id));
                // Store log data
                $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','category']), get_phrases(['updated']), $id, 'wh_material_categories');
                if($result){
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
        
        echo json_encode($response);
    }

    /*--------------------------
    | Get categories by ID
    *--------------------------*/
    public function bdtaskt1m12c1_04_getCategoriesById($id)
    { 
        $data = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('wh_material_categories', array('id'=>$id));
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
