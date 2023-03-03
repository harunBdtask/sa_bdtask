<?php namespace App\Modules\Production\Controllers;
use App\Modules\Production\Views;
use CodeIgniter\Controller;
use App\Modules\Production\Models\Bdtaskt1ProductionVoucherModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1c3ProductionVoucher extends BaseController
{
    private $bdtaskt1m12c7_01_item_purchaseModel;
    private $bdtaskt1m12c7_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c7_01_item_purchaseModel = new Bdtaskt1ProductionVoucherModel();
        $this->bdtaskt1m12c7_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']              = get_phrases(['production', 'voucher','list']);
        $data['moduleTitle']        = get_phrases(['production']);
        $data['isDTables']          = true;
        $data['module']             = "Production";
        $data['page']               = "production_voucher/list";
        
        $data['hasPrintAccess']        = $this->permission->method('production_voucher', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('production_voucher', 'export')->access();
        
        $data['store_list']     = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_production_store', array('status'=>1));
        $data['machine_list']  = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_machine_store', array('status'=>1));
        $data['production_list']  = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_production', array('status'=>1));
        $data['receive_list']  = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_receive', array('status'=>1));
               
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get item_purchase info
    *--------------------------*/
    public function bdtaskt1m12c7_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c7_01_item_purchaseModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete item_purchase by ID
    *--------------------------*/
    public function bdtaskt1m12c7_02_deleteProductionVoucher($id)
    { 
        $data = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_06_Deleted('wh_items', array('id'=>$id));
        $MesTitle = get_phrases(['purchase', 'record']);
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
    | Add item_purchase info
    *--------------------------*/
    public function bdtaskt1m12c7_03_addProductionVoucher()
    { 

        //$old_image = $this->request->getVar('old_image'); 
        $action = $this->request->getVar('action');
        $data = array(
            'store_id'      => $this->request->getVar('store_id'), 
            'machine_id'   => $this->request->getVar('machine_id'), 
            'voucher_no'        => $this->request->getVar('voucher_no'), 
            'date'              => $this->date_db_format($this->request->getVar('date')), 
            'grand_total'       => $this->request->getVar('grand_total'), 
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'voucher_no'    => 'required|min_length[4]|max_length[20]',
                'grand_total'   => 'required|numeric',
            ];
        }
        $MesTitle = get_phrases(['purchase', 'record']);
        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            //$filePath = $this->bdtaskt1c1_01_fileUpload->doUpload('./assets/dist/img/item_purchase', $this->request->getFile('image'));
            if($action=='add'){
                $isExist = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_03_getRow('wh_production', array('voucher_no'=>$this->request->getVar('voucher_no')));
                //$isExist2 = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_03_getRow('wh_items', array('nameA'=>$this->request->getVar('nameA')));
                if( !empty($isExist)  ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    //$data['image'] = $filePath;
                    $result = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_01_Insert('wh_production',$data);

                    $item_id    = $this->request->getVar('item_id');
                    $qty        = $this->request->getVar('qty');
                    $price      = $this->request->getVar('price');
                    $total      = $this->request->getVar('total');

                    $data2 = array();
                    foreach($item_id as $key => $item){
                        $data2[] = array('production_id'=> $result,'item_id'=> $item, 'qty'=> $qty[$key], 'price'=> $price[$key], 'total'=> $total[$key] );
                    }

                    $result2 = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_production_details',$data2);

                    /*$store_id = $this->request->getVar('store_id');
                    foreach($item_id as $key => $item){
                        $where = array('store_id'=> $store_id,'item_id'=> $item );
                        $affectedRows = $this->bdtaskt1m12c7_01_item_purchaseModel->bdtaskt1m12_04_updateStock($qty[$key], $where);
                        if( $affectedRows ==0 ){
                            $data3 = array('store_id'=> $store_id,'item_id'=> $item, 'stock'=> $qty[$key] );
                            $result = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_01_Insert('wh_production_stock',$data3);
                        }
                    }*/

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
                $result = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_02_Update('wh_production',$data, array('id'=>$id));
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
    | Get item_purchase by ID
    *--------------------------*/
    public function bdtaskt1m12c7_04_getProductionVoucherById($id)
    { 
        $data = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_03_getRow('wh_items', array('id'=>$id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get item details by ID
    *--------------------------*/
    public function bdtaskt1m12c7_05_getProductionVoucherDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c7_01_item_purchaseModel->bdtaskt1m12_03_getProductionVoucherDetailsById($id);
        echo json_encode($data);
    }

    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c7_06_getItemPricingDetailsById()
    { 
        $production_id = $this->request->getVar('production_id');

        $html = '<table class="table table-bordered w-100"><tr><th class="text-center">'.get_phrases(['finished','goods']).'</th><th class="text-right">'.get_phrases(['bag','size']).'</th><th class="text-right">'.get_phrases(['bags']).'</th><th class="text-right">'.get_phrases(['output']).' KG</th><th class="text-right">WIP KG</th><th class="text-right">'.get_phrases(['process','loss']).' KG</th><th class="text-center">'.get_phrases(['batch','no']).'</th><th class="text-center">'.get_phrases(['expiry','date']).'</th></tr>';

        $purchases_details = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_production_details', array('production_id'=>$production_id));

        foreach($purchases_details as $details)
        {
            $item_row = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_03_getRow('wh_items', array('id'=>$details->item_id));            
            //$unit_row = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
            
            $receive_details = $this->bdtaskt1m12c7_02_CmModel->bdtaskt1m1_03_getRow('wh_receive_details', array('production_id'=>$production_id,'item_id'=>$details->item_id));

            $html .= '<tr>
                        <td width="30%">'.$item_row->nameE.' ('.$item_row->company_code.')</td>
                        <td width="10%" align="right">'.(($receive_details)?$receive_details->bag_size:0).'</td>
                        <td width="10%" align="right">'.(($receive_details)?number_format($receive_details->act_bags,0):0).'</td>
                        <td width="10%" align="right">'.(($receive_details)?$receive_details->qty:0).'</td>
                        <td width="10%" align="right">'.(($receive_details)?$receive_details->wip_kg:0).'</td>
                        <td width="10%" align="right">'.(($receive_details)?$receive_details->loss_kg:0).'</td>
                        <td width="10%" >'.(($receive_details)?$receive_details->batch_no:0).'</td>
                        <td width="10%" >'.(($receive_details)?$receive_details->expiry_date:0).'</td>
                    </tr>';
        }
        $html .= '</table>';

        echo $html;
    }


    /*--------------------------
    | Get return details by ID
    *--------------------------*/
    public function bdtaskt1m12c7_07_getReturnDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c7_01_item_purchaseModel->bdtaskt1m12_05_getItemReturnDetailsById($id);
        echo json_encode($data);
    }
   
   
   
}
