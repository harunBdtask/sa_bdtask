<?php namespace App\Modules\Production\Controllers;
use App\Modules\Production\Views;
use CodeIgniter\Controller;
use App\Modules\Production\Models\Bdtaskt1ItemProductionModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1c1ItemProduction extends BaseController
{
    private $bdtaskt1m12c10_01_item_productionModel;
    private $bdtaskt1m12c10_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c10_01_item_productionModel = new Bdtaskt1ItemProductionModel();
        $this->bdtaskt1m12c10_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']              = get_phrases(['plan','list']);
        $data['moduleTitle']        = get_phrases(['production']);
        $data['isDTables']          = true;
        $data['module']             = "Production";
        $data['page']               = "item_production/list";
        $data['setting']            = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('setting');

        $data['hasCreateAccess']        = $this->permission->method('production_plan', 'create')->access();
        $data['hasPrintAccess']        = $this->permission->method('production_plan', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('production_plan', 'export')->access();
        
        $data['machine_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_machine_store', array('status'=>1));
        $data['item_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_items', array('status'=>1));
        $data['production_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_production', array('status'=>1));
       
        $data['store_list']       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_production_store', array('status'=>1));
        
        
        $data['vat']       = get_setting('default_vat');
        
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get item_production info
    *--------------------------*/
    public function bdtaskt1m12c10_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c10_01_item_productionModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete item_production by ID
    *--------------------------*/
    public function bdtaskt1m12c10_02_deleteItemProduction($id)
    { 
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_production', array('id'=>$id, 'isApproved'=>1));

        $MesTitle = get_phrases(['production', 'record']);
        if(!empty($data)){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['already', 'approved']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('wh_production', array('id'=>$id));
        $data2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('wh_production_details', array('production_id'=>$id));

        // Store log data
        $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['production','entry']), get_phrases(['deleted']), $id, 'wh_production');
        //$MesTitle = get_phrases(['production', 'record']);
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
    public function bdtaskt1m12c10_03_addItemProduction()
    { 

        //$old_image = $this->request->getVar('old_image'); 
        $action = $this->request->getVar('action');

        $data = array(
            'store_id'          => $this->request->getVar('store_id'), 
            'machine_id'        => $this->request->getVar('machine_id'), 
            //'sub_total'         => $this->request->getVar('sub_total'), 
            //'grand_total'       => $this->request->getVar('grand_total'), 
            //'vat'               => $this->request->getVar('vat'),
            
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'store_id'      => 'required',
                'machine_id'    => 'required',
            ];
        }
        $MesTitle = get_phrases(['production', 'record']);
        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            //$filePath = $this->bdtaskt1c1_01_fileUpload->doUpload('./assets/dist/img/item_production', $this->request->getFile('image'));
            if($action=='add'){
                $voucher_no     = 'PLAN-'.getMAXID('wh_production', 'id');
                
                $isExist = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_production', array('voucher_no'=>$voucher_no) );
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

                    $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert('wh_production',$data);

                    $item_id    = $this->request->getVar('item_id');
                    $recipe_id    = $this->request->getVar('recipe_id');
                    $bag_size     = $this->request->getVar('bag_size');
                    $act_bags        = $this->request->getVar('act_bags');
                    $qty        = $this->request->getVar('qty');

                    $data2 = array();
                    //$approve = 1;
                    foreach($item_id as $key => $item){
                        $data2[] = array('production_id'=> $result,'item_id'=> $item, 'recipe_id'=> $recipe_id[$key], 'bag_size'=> $bag_size[$key], 'act_bags'=> $act_bags[$key], 'qty'=> $qty[$key] );
                        
                        /*if($price[$key] > $org_price[$key]){
                            $approve = 0;
                        }*/
                    }

                    $result2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_production_details',$data2);

                    if($result){
                        // Store log data
                        $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['production','entry']), get_phrases(['created']), $result, 'wh_production');
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

                $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('wh_production',$data, array('id'=>$id));

                
                $item_id    = $this->request->getVar('item_id');
                $recipe_id    = $this->request->getVar('recipe_id');
                $bag_size     = $this->request->getVar('bag_size');
                $act_bags        = $this->request->getVar('act_bags');
                $qty        = $this->request->getVar('qty');

                $data2 = array();
                //$approve = 1;
                foreach($item_id as $key => $item){
                    $data2[] = array('production_id'=> $id,'item_id'=> $item, 'recipe_id'=> $recipe_id[$key], 'bag_size'=> $bag_size[$key], 'act_bags'=> $act_bags[$key], 'qty'=> $qty[$key] );
                    
                    /*if($price[$key] > $org_price[$key]){
                        $approve = 0;
                    }*/
                }

                $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_06_Deleted('wh_production_details', array('production_id'=>$id));
                $result2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_production_details',$data2);

                /*if($approve){
                    $data3 = array();
                    $data3['isApproved']       = 1;
                    $data3['approved_by']      = session('id');
                    $data3['approved_date']    = date('Y-m-d H:i:s');

                    $result3 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('wh_production',$data3, array('id'=>$id));
                }*/

                if($result){
                    // Store log data
                    $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['production','entry']), get_phrases(['updated']), $id, 'wh_production');
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
    public function bdtaskt1m12c10_07_approveItemProduction(){

        $id = $this->request->getVar('id');
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_production', array('id'=>$id, 'isApproved'=>1));

        $MesTitle = get_phrases(['production', 'record']);
        if(!empty($data)){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['already', 'approved']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }

        /*$sub_store_id = $this->request->getVar('sub_store_id');
        $recipe_id = $this->request->getVar('recipe_id');
        $qty = $this->request->getVar('qty');
        
        foreach($recipe_id as $key => $recipe){
            $recipe_details       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_recipe_details', array('recipe_id'=>$recipe));

            foreach($recipe_details as $row){
                $material_qty = $qty[$key] * ($row->qty/100);

                $where = array('sub_store_id'=> $sub_store_id, 'item_id'=> $row->material_id, 'stock < '=> $material_qty );
                $stock_check = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_machine_stock', $where);

                if( !empty($stock_check) ){
                    $item_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=>$row->material_id));      
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['plant','stock', 'is', 'less', 'than','required']),
                        'title'    => (empty($item_row))?$MesTitle:$item_row->nameE
                    );
                    echo json_encode($response);
                    exit;
                }
            }
        }

        foreach($recipe_id as $key => $recipe){
            $recipe_details       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_recipe_details', array('recipe_id'=>$recipe));

            foreach($recipe_details as $row){
                $material_qty = $qty[$key] * ($row->qty/100);

                $where = array('sub_store_id'=> $sub_store_id,'item_id'=> $row->material_id );
                $this->bdtaskt1m12c10_01_item_productionModel->bdtaskt1m12_04_updateStock($material_qty, $where);
            }
        }*/
        
        $data = array();
        $data['isApproved']       = 1;
        $data['approved_by']      = session('id');
        $data['approved_date']    = date('Y-m-d H:i:s');

        $result = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_02_Update('wh_production',$data, array('id'=>$id));


        $MesTitle = get_phrases(['production', 'record']);

        if($result){
            $production = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_production', array('id'=>$id));
            if( !empty($production) ){
                $voucher_no = 'CONS-'.getMAXID('wh_machine_requests', 'id');
                $mdata = array(
                    'sub_store_id'          => $production->machine_id, 
                    'production_id'         => $production->id, 
                    'voucher_no'            => $voucher_no, 
                    'date'                  => date('Y-m-d'), 
                    //'notes'                 => $this->request->getVar('notes'), 
                    'request_by'            => session('id'), 
                    'request_date'          => date('Y-m-d H:i:s')
                );
                $mresult = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert('wh_machine_requests', $mdata);

                if($mresult){
                    $mdata2 = array();

                    $recipe_details = $this->bdtaskt1m12c10_01_item_productionModel->bdtaskt1m12_10_getItemProductionItemDetailsById($id);
                    foreach($recipe_details as $recipe){

                        $wip_qty = ($recipe->wip >0 && $recipe->recipe_percent)?($recipe->wip * ($recipe->recipe_percent/100)):0;
                        $qty = ($wip_qty < $recipe->recipe_qty)?($recipe->recipe_qty - $wip_qty):0;

                        $mdata2[] = array('request_id'=> $mresult, 'item_id'=> $recipe->item_id, 'recipe_qty'=> $recipe->recipe_qty, 'wip_qty'=> $wip_qty, 'qty'=> $qty );
                    }
                    if(!empty($mdata2)){
                        $mresult2 = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_machine_request_details',$mdata2);
                    }
                }
            }
            // Store log data
            $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['production','entry']), get_phrases(['approved']), $id, 'wh_production');
             $response = array(
                'success'  =>true,
                'message'  => get_phrases(['approved', 'successfully']),
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
    public function bdtaskt1m12c10_04_getItemProductionById($id)
    { 
        $data = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_items', array('id'=>$id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get item details by ID
    *--------------------------*/
    public function bdtaskt1m12c10_05_getItemProductionDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c10_01_item_productionModel->bdtaskt1m12_03_getItemProductionDetailsById($id);
        echo json_encode($data);
    }

    /*--------------------------
    | Get supplier item details by ID
    *--------------------------*/
    public function bdtaskt1m12c10_09_getSupplierItemDetailsById()
    { 
        $item_id = $this->request->getVar('item_id');
        //$machine_id = $this->request->getVar('machine_id');

        $data = $this->bdtaskt1m12c10_01_item_productionModel->bdtaskt1m12_06_getSupplierItemDetailsById($item_id);

        $recipe_row       = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_recipe', array('status'=>1,'isActive'=>1,'item_id'=>$item_id));

        $recipe = '';
        $recipe_id = '';
        if( !empty($recipe_row) ){
            $recipe = '<a href="javascript:void(0);" class="recipePreview" data-id="'.$recipe_row->id.'">'.$recipe_row->voucher_no.'</a>';
            $recipe_id = $recipe_row->id;
        }
        $data['recipe'] = $recipe;
        $data['recipe_id'] = $recipe_id;

        echo json_encode($data);
    }

   
    /*--------------------------
    | Get item list by supplier ID
    *--------------------------*/
    public function bdtaskt1m12c10_08_get_item_list()
    { 
        $item_list = $this->bdtaskt1m12c10_01_item_productionModel->bdtaskt1m12_05_get_item_list();

        $html = '<option value="">Select</option>';
        foreach($item_list as $items){
            $html .='<option value="'.$items->id.'">'.$items->nameE.' ( '.$items->company_code.' )</option>';
        }
        echo $html;
   }
    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c10_06_getItemProductionPricingDetailsById()
    { 
        $production_id = $this->request->getVar('production_id');
        $machine_id = $this->request->getVar('machine_id');

        $html = '<table class="table table-bordered w-100"><tr><th class="text-center">'.get_phrases(['item']).'</th><th class="text-right">'.get_phrases(['bag','size']).' KG</th><th class="text-right">'.get_phrases(['bags']).'</th><th class="text-right">'.get_phrases(['output']).' KG</th><th class="text-center">'.get_phrases(['recipe']).'</th></tr>';

        $production = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_production', array('id'=>$production_id));
        $production_details = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_production_details', array('production_id'=>$production_id));

        $i = 0;
        foreach($production_details as $details)
        {
            $item_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_items', array('id'=>$details->item_id));            
            $recipe_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('wh_recipe', array('id'=>$details->recipe_id));            
            //$unit_row = $this->bdtaskt1m12c10_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
            
            $i++;
            $html .= '<tr>
                        <td width="40%" align="center">'.$item_row->nameE.' ( '.$item_row->company_code.' )<input type="hidden" name="recipe_id[]" id="recipe_id'.$i.'" value="'.$details->recipe_id.'"></td>
                        <td width="15%" align="right">'.(($details)?$details->bag_size:'').'</td>
                        <td width="15%" align="right">'.(($details)?$details->act_bags:'').'</td>
                        <td width="15%" align="right">'.(($details)?$details->qty:'').'<input type="hidden" name="qty[]" id="qty'.$i.'" value="'.$details->qty.'"></td>
                        <td width="15%" align="center">'.(($recipe_row)?'<a href="javascript:void(0);" class="recipePreview" data-id="'.$recipe_row->id.'" data-quantity="'.($details->bag_size * $details->act_bags).'">'.$recipe_row->voucher_no.'</a>':'').'</td>
                    </tr>';
        }
        $html .= '</table><input type="hidden" name="item_counter" id="approve_item_counter" value="'.$i.'"><input type="hidden" name="sub_store_id" id="sub_store_id" value="'.$machine_id.'">';

        echo $html;
    }

    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c10_07_getItemProductionItemDetailsById()
    { 
        $production_id = $this->request->getVar('production_id');
        $data = $this->bdtaskt1m12c10_01_item_productionModel->bdtaskt1m12_07_getItemProductionItemDetailsById($production_id);
        echo json_encode($data);
    }
   
}
