<?php namespace App\Modules\Production\Controllers;
use App\Modules\Production\Views;
use CodeIgniter\Controller;
use App\Modules\Production\Models\Bdtaskt1ProductionRecipeModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1c4ProductionRecipe extends BaseController
{
    private $bdtaskt1m12c10_01_production_recipeModel;
    private $bdtaskt1m12c10_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c10_01_production_recipeModel = new Bdtaskt1ProductionRecipeModel();
        $this->bdtaskt1m12c10_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']              = get_phrases(['recipe','list']);
        $data['moduleTitle']        = get_phrases(['production']);
        $data['isDTables']          = true;
        $data['module']             = "Production";
        $data['page']               = "production_recipe/list";
        $data['setting']            = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('setting');

        $data['hasCreateAccess']        = $this->permission->method('recipe_list', 'create')->access();
        $data['hasPrintAccess']        = $this->permission->method('recipe_list', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('recipe_list', 'export')->access();
        
        $data['item_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('status'=>1));
        $data['recipe_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_recipe', array('status'=>1));
               
        $data['vat']       = get_setting('default_vat');
        
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get item_production info
    *--------------------------*/
    public function bdtaskt1m12c10_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c10_01_production_recipeModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete item_production by ID
    *--------------------------*/
    public function bdtaskt1m12c10_02_deleteProductionRecipe($id)
    { 
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_recipe', array('id'=>$id, 'isApproved'=>1));

        $MesTitle = get_phrases(['production', 'recipe']);
        if(!empty($data)){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['already', 'approved']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('wh_recipe', array('id'=>$id));
        $data2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('wh_recipe_details', array('recipe_id'=>$id));

        // Store log data
        $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['production','order']), get_phrases(['deleted']), $id, 'wh_recipe');
        //$MesTitle = get_phrases(['production', 'recipe']);
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

    public function date_db_format($date){
        if($date==''){
            return $date;
        }
        return implode("-", array_reverse(explode("/", $date)));
    }

    /*--------------------------
    | Add item_production info
    *--------------------------*/
    public function bdtaskt1m12c10_03_addProductionRecipe()
    { 

        //$old_image = $this->request->getVar('old_image'); 
        $action = $this->request->getVar('action');

        $data = array(
            'item_id'          => $this->request->getVar('item_id')
            
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'item_id'      => 'required',
            ];
        }
        $MesTitle = get_phrases(['production', 'recipe']);
        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            //$filePath = $this->bdtaskt1c1_01_fileUpload->doUpload('./assets/dist/img/item_production', $this->request->getFile('image'));
            if($action=='add'){
                $voucher_no     = 'RCP-'.getMAXID('wh_recipe', 'id');
                
                $isExist = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_recipe', array('voucher_no'=>$voucher_no) );
                //$isExist2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_items', array('nameA'=>$this->request->getVar('nameA')));
                if( !empty($isExist)  ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    $data['voucher_no']     = $voucher_no;
                    $data['date']           = $this->date_db_format($this->request->getVar('date'));
                    $data['created_by']     = session('id');
                    $data['created_at']     = date('Y-m-d H:i:s');

                    $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert('wh_recipe',$data);

                    $material_id    = $this->request->getVar('material_id');
                    //$wastage    = $this->request->getVar('wastage');
                    $qty        = $this->request->getVar('qty');

                    $data2 = array();
                    //$approve = 1;
                    foreach($material_id as $key => $item){
                        $data2[] = array('recipe_id'=> $result,'material_id'=> $item, 'qty'=> $qty[$key] );
                        
                        /*if($price[$key] > $org_price[$key]){
                            $approve = 0;
                        }*/
                    }

                    $result2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_recipe_details',$data2);

                    if($result){
                        // Store log data
                        $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['production','order']), get_phrases(['created']), $result, 'wh_recipe');
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

                $data['updated_by']     = session('id');
                $data['updated_at']     = date('Y-m-d H:i:s');

                $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('wh_recipe',$data, array('id'=>$id));

                
                $material_id    = $this->request->getVar('material_id');
                //$wastage  = $this->request->getVar('wastage');
                $qty        = $this->request->getVar('qty');

                $data2 = array();
                //$approve = 1;
                foreach($material_id as $key => $item){
                    $data2[] = array('recipe_id'=> $id,'material_id'=> $item, 'qty'=> $qty[$key] );
                    
                    /*if($price[$key] > $org_price[$key]){
                        $approve = 0;
                    }*/
                }

                $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('wh_recipe_details', array('recipe_id'=>$id));
                $result2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_recipe_details',$data2);

                /*if($approve){
                    $data3 = array();
                    $data3['isApproved']       = 1;
                    $data3['approved_by']      = session('id');
                    $data3['approved_date']    = date('Y-m-d H:i:s');

                    $result3 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('wh_recipe',$data3, array('id'=>$id));
                }*/

                if($result){
                    // Store log data
                    $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['production','order']), get_phrases(['updated']), $id, 'wh_recipe');
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

    /* Approve production order*/
    public function bdtaskt1m12c10_07_approveProductionRecipe(){

        $id = $this->request->getVar('id');
        $action = $this->request->getVar('action');

        if( $action == 'approve' ){
            $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_recipe', array('id'=>$id, 'isApproved'=>1));

            $MesTitle = get_phrases(['production', 'recipe']);
            if(!empty($data)){
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['already', 'approved']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);
                exit;
            }
            
            $data = array();
            $data['isApproved']       = 1;
            $data['approved_by']      = session('id');
            $data['approved_date']    = date('Y-m-d H:i:s');

            $recipe = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_recipe', array('id'=>$id));
            
            if( !empty($recipe) ){
                $exists = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_recipe', array('item_id'=>$recipe->item_id, 'id !='=>$id));
                if( empty($exists) ){
                    $data['isActive']      = 1;
                }
            }
            $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('wh_recipe',$data, array('id'=>$id));

        }
        if( $action == 'active' ){
            $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_recipe', array('id'=>$id, 'isActive'=>1));

            $MesTitle = get_phrases(['production', 'recipe']);
            if(!empty($data)){
                $response = array(
                    'success'  =>false,
                    'message'  => get_phrases(['already', 'active']),
                    'title'    => $MesTitle
                );
                echo json_encode($response);
                exit;
            }
            
            $data = array();
            $data['isActive']      = 1;
            $data['updated_by']    = session('id');
            $data['updated_at']    = date('Y-m-d H:i:s');

            $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('wh_recipe',$data, array('id'=>$id));

            $recipe = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_recipe', array('id'=>$id));
            
            if( !empty($recipe) ){
                $data = array();
                $data['isActive']       = 0;
                $data['updated_by']     = session('id');
                $data['updated_at']     = date('Y-m-d H:i:s');

                $result2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('wh_recipe',$data, array('item_id'=>$recipe->item_id, 'isApproved'=>1, 'id !='=>$id));
            }
        }
        $MesTitle = get_phrases(['production', 'recipe']);

        if($result){
            // Store log data
            $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['production','recipe']), get_phrases(['updated']), $id, 'wh_recipe');
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
        echo json_encode($response);
    }

    /*--------------------------
    | Get item_production by ID
    *--------------------------*/
    public function bdtaskt1m12c10_04_getProductionRecipeById($id)
    { 
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_items', array('id'=>$id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get item details by ID
    *--------------------------*/
    public function bdtaskt1m12c10_05_getProductionRecipeDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c10_01_production_recipeModel->bdtaskt1m12_03_getProductionRecipeDetailsById($id);
        echo json_encode($data);
    }

    /*--------------------------
    | Get supplier item details by ID
    *--------------------------*/
    public function bdtaskt1m12c10_09_getSupplierItemDetailsById()
    { 
        $material_id = $this->request->getVar('material_id');

        $data = $this->bdtaskt1m12c10_01_production_recipeModel->bdtaskt1m12_06_getSupplierItemDetailsById($material_id);

        echo json_encode($data);
    }

   
    /*--------------------------
    | Get item list by supplier ID
    *--------------------------*/
    public function bdtaskt1m12c10_08_get_item_list()
    { 
        $item_list = $this->bdtaskt1m12c10_01_production_recipeModel->bdtaskt1m12_05_get_item_list();

        $html = '<option value="">Select</option>';
        foreach($item_list as $items){
            $html .='<option value="'.$items->id.'">'.$items->nameE.' ( '.$items->item_code.' )</option>';
        }
        echo $html;
   }
    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c10_06_getProductionRecipePricingDetailsById()
    { 
        $recipe_id = $this->request->getVar('recipe_id');
        $quantity = $this->request->getVar('quantity');

        $html = '<table class="table table-bordered w-100"><tr><th class="text-center">'.get_phrases(['sl']).'</th><th class="text-center">'.get_phrases(['raw','material']).'</th>';
        if( $quantity >0 ){
            $html .= '<th class="text-right">'.get_phrases(['quantity']).' (KG)</th>';
        } else {
            $html .= '<th class="text-right">'.get_phrases(['percentage']).'</th>';
        }
        $html .= '</tr>';

        $production = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_recipe', array('id'=>$recipe_id));
        $production_details = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_recipe_details', array('recipe_id'=>$recipe_id));
        $total=0;
        $total_quantity=0;
        $i=1;
        foreach($production_details as $details)
        {
            $item_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=>$details->material_id));            
            //$unit_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
            
            $html .= '<tr>
                        <td width="10%" align="center">'.$i.'</td>
                        <td width="50%">'.$item_row->nameE.' ( '.$item_row->item_code.' )</td>';

            if( $quantity >0 ){
                $html .= '<td width="20%" align="right">'.(empty($quantity)?0:($quantity * ($details->qty/100))).' KG</td>';
            } else {
                $html .= '<td width="20%" align="right">'.(($details)?$details->qty:'').' %</td>';
            }

            $html .= '</tr>';

            $total += ($details)?$details->qty:0;
            $total_quantity += empty($quantity)?0:($quantity * ($details->qty/100));
            $i++;
        }
        $html .= '<tr>
                    <td colspan="2" align="right" class="font-weight-bold">'.get_phrases(['total']).'</td>';
        if( $quantity >0 ){            
            $html .= '<td align="right">'.$total_quantity.' KG</td>';
        } else {            
            $html .= '<td align="right">'.$total.' %</td>';
        }
        $html .= '</tr></table>';

        echo $html;
    }

    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c10_07_getProductionRecipeItemDetailsById()
    { 
        $recipe_id = $this->request->getVar('recipe_id');
        $data = $this->bdtaskt1m12c10_01_production_recipeModel->bdtaskt1m12_07_getProductionRecipeItemDetailsById($recipe_id);
        echo json_encode($data);
    }
   
}
