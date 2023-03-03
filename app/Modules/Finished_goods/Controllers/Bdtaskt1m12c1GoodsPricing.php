<?php namespace App\Modules\Finished_goods\Controllers;
use App\Modules\Finished_goods\Views;
use CodeIgniter\Controller;
use App\Modules\Finished_goods\Models\Bdtaskt1m12PricingModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1m12c1GoodsPricing extends BaseController
{
    private $bdtaskt1m12c1_01_pricingModel;
    private $bdtaskt1m12c1_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c1_01_pricingModel = new Bdtaskt1m12PricingModel();
        $this->bdtaskt1m12c1_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Pricing list
    *--------------------------*/
    public function index()
    {
        $data['title']              = get_phrases(['goods', 'pricing']);
        $data['moduleTitle']        = get_phrases(['finished','goods']);
        $data['isDTables']          = true;
        $data['module']             = "Finished_goods";
        $data['page']               = "pricing/list";
        $data['hasCreateAccess']    = $this->permission->method('goods_pricing', 'create')->access();
        $data['item_list']          = $this->bdtaskt1m12c1_01_pricingModel->itemlist();
        $data['hasPrintAccess']     = $this->permission->method('goods_pricing', 'print')->access();
        $data['hasExportAccess']    = $this->permission->method('goods_pricing', 'export')->access();
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get categories info
    *--------------------------*/
    public function bdtaskt1m12c1_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c1_01_pricingModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete categories by ID
    *--------------------------*/
    public function bdtaskt1m12c1_02_deletePricing($id)
    { 
    
        $data = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_06_Deleted('goods_pricing', array('id'=>$id));
        // Store log data
        $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['goods','pricing']), get_phrases(['deleted']), $id, 'goods_pricing');

        $MesTitle = get_phrases(['pricing', 'record']);
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
    public function bdtaskt1m12c1_03_addPricing()
    { 

        //$old_image = $this->request->getVar('old_image'); 
        $action = $this->request->getVar('action');
        $data = array(
            'product_id'          => $this->request->getVar('product_id'), 
            'previous_price'      => $this->request->getVar('latest_price'),
            'price'               => $this->request->getVar('new_price'), 
            'date'                => $this->request->getVar('date'), 
            'increase_percentagte'=> $this->request->getVar('increase_percentage'), 

        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'product_id'  => ['label' => get_phrases(['goods','name']), 'rules' => 'required'],
                'new_price'       => ['label' => get_phrases(['new','price']), 'rules' => 'required'],
            ];
        }
        $MesTitle = get_phrases(['pricing', 'record']);
        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            //$filePath = $this->bdtaskt1c1_01_fileUpload->doUpload('./assets/dist/img/categories', $this->request->getFile('image'));
            if($action=='add'){
                $isExist = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('goods_pricing', array('date'=>$this->request->getVar('date'),'product_id'=>$this->request->getVar('product_id')));
               
                if(!empty($isExist) ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases([!empty($isExist)?'goods':'price','already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    //$data['image'] = $filePath;
                    $data['created_by']          = session('id');
                    $data['created_date']        = date('Y-m-d H:i:s');

                    $result = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_01_Insert('goods_pricing',$data);
                    // Store log data
                    $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['goods','price']), get_phrases(['created']), $result, 'goods_pricing');
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
                $isExist = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('goods_pricing', array('id !='=>$id,'date'=>$this->request->getVar('date'),'product_id'=>$this->request->getVar('product_id')));

                if(!empty($isExist)){
                    $response = array(
                            'success'  => false,
                            'message'  => get_phrases([!empty($isExist)?'goods':'price','already', 'exists']),
                            'title'    => $MesTitle
                        );
                    echo json_encode($response);exit;
                }else{
                    $result = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_02_Update('goods_pricing',$data, array('id'=>$id));
                }
                if(!empty($result)){
                    // Store log data
                    $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['goods','pricing']), get_phrases(['updated']), $id, 'goods_pricing');
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
    public function bdtaskt1m12c1_04_getPricingById($id)
    { 
        $data = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('goods_pricing', array('id'=>$id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get category details by ID
    *--------------------------*/
    public function bdtaskt1m12c1_05_getPricingDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c1_01_pricingModel->bdtaskt1m12_03_getPricingDetailsById($id);
        echo json_encode($data);
    }

    public function bdtaskt1m12c1_03_goodspreviousPrice($id)
    {
        $data = $this->bdtaskt1m12c1_01_pricingModel->bdtaskt1m12_03_getLastPrice($id);
        echo json_encode($data);
    }

   
}
