<?php namespace App\Modules\Template\Controllers;
use CodeIgniter\Controller;
use App\Modules\Template\Models\Bdtaskt1m12TemplateModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1m12c14Template extends BaseController
{
    private $bdtaskt1m12c14_01_templateModel;
    private $bdtaskt1m12c14_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->parser = \Config\Services::parser();
        $this->permission = new Permission();
        $this->bdtaskt1m12c14_01_templateModel = new Bdtaskt1m12TemplateModel();
        $this->bdtaskt1m12c14_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Supplier list
    *--------------------------*/
    public function index()
    {
        $data['moduleTitle']= get_phrases(['template']);
        $data['title']      = get_phrases(['template', 'list']);
        $data['isDTables']  = true;
        $data['summernote'] = true;
        $data['module']     = "Template";
        $data['page']       = "template/list";

        $data['hasCreateAccess']        = $this->permission->method('wh_supplier', 'create')->access();
        $data['hasPrintAccess']        = $this->permission->method('wh_supplier', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('wh_supplier', 'export')->access();
        
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get all suppliers info
    *--------------------------*/
    public function bdtaskt1m12c14_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c14_01_templateModel->bdtaskt1m12_01_getAll($postData);
        echo json_encode($data);
    }

    public function bdtaskt1m12c14_02_approveAction(){

        $id = $this->request->getVar('approve_id');
        $info = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('wh_template', array('id'=>$id, 'isApproved'=>1));
        // echo get_last_query();exit;
        $MesTitle = get_phrases(['template', 'record']);
        if(!empty($info)){
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

        $result = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_02_Update('wh_template',$data, array('id'=>$id));
        // Store log data
        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['template','record']), get_phrases(['approved']), $id, 'wh_template');

        if($result){
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
    | Get supplier details by ID
    *--------------------------*/
    public function bdtaskt1m12c14_05_getDetailsById($id)
    { 
        $data = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('wh_template', array('id'=>$id));
        $list = [];
        if(!empty($data)){
            $withourbackpath = str_replace('/\/', '/', TEMPLATE_DIR.$data->template_name);
            $viewpath = str_replace('"', '', $withourbackpath);
            $list['template_details'] = $this->parser->render($viewpath);
            $list['template_name'] = $data->template_name;
            $list['template_header'] = $data->template_header;
            $list['template_footer'] = $data->template_footer;
        }
        echo json_encode($list);
    }

    public function bdtaskt1m12c14_06_getHtml()
    { 
        
        $data = [
            'supplier_name' => 'M/S Modina Trading Corporation',
            'supplier_mobile' => '0177985451',
        ];
        $data['item_name']  = "Fish Oil";
        $data['module']     = "Template";
        $data['page']       = "material_purchase/list";
        

        $path = 'App\Modules\"'.ucfirst($data['module']).'"\Views\"'.$data['page'].'"';
        $withourbackpath = str_replace('/\/', '/', $path);
        $viewpath = str_replace('"', '', $withourbackpath);
        // echo $this->parser->setData($data)->render($viewpath);
        echo $this->parser->setData($data)->render(TEMPLATE_DIR.'\bagPOprintView');
        exit;


        // $purchase_id = $this->request->getVar('purchase_id');

        // $html = '<table class="table table-bordered w-100"><tr><th>'.get_phrases(['SL']).'</th><th>'.get_phrases(['raw', 'material', 'name']).'</th><th class="text-right">'.get_phrases(['request','quantity']).'</th><th class="text-center">'.get_phrases(['unit']).'</th><th class="text-right">'.get_phrases(['purchase', 'quantity']).'</th><th  class="text-right">'.get_phrases(['purchase', 'price']).'</th><th  class="text-right">'.get_phrases(['subtotal']).'</th></tr>';

        // $purchases_details = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_material_purchase_details', array('purchase_id'=>$purchase_id));

        // $sl = 0;
        // foreach($purchases_details as $details)
        // {
        //     $sl++;
        //     $item_row = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('wh_material', array('id'=>$details->item_id));            
        //     $unit_row = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$item_row->unit_id));
            
        //     $html .= '<tr>
        //                 <td width="5%">'.$sl.'</td>
        //                 <td width="15%">'.$item_row->nameE.' ('.$item_row->item_code.')</td>
        //                 <td width="10%" align="right">'.number_format(($details)?$details->requested_qty:0, 2).'</td>
        //                 <td width="5%" align="center">'.(($unit_row)?$unit_row->nameE:'').'</td>
        //                 <td width="10%" align="right">'.number_format(($details)?$details->qty:0, 2).'</td>
        //                 <td width="10%" align="right">'.number_format(($details)?$details->price:0,2).'</td>
        //                 <td width="10%" align="right">'.number_format(($details)?($details->qty*$details->price):0,2).'</td>
        //                 <input type="hidden" name="item_id[]" id="item_id'.$sl.'" value="'.$details->item_id.'">
        //                 <input type="hidden" name="item_qty[]" id="item_qty'.$sl.'" value="'.$details->qty.'">
        //             </tr>';
        // }
        // $html .= '</table>';

        // echo $html;
    }

    public function bdtaskt1m12c14_07_addTemplate()
    {
        $action     = $this->request->getVar('action');
        $template_file_name  = $this->request->getVar('template_file_name');
        $template_details    = $this->request->getVar('template_details');
        
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'template_details'     => 'required',
                'template_file_name'   => 'required',
            ];
        }
        $MesTitle = get_phrases(['template', 'record']);
        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            
            if($action=='add'){
                $isExist = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('wh_template', array('template_name'=>$template_file_name) );
                if( !empty($isExist)  ){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    $tfile = TEMPLATE_DIR.$template_file_name;
                    $withourbackpath = str_replace('/\/', '/', $tfile);
                    $file_path = str_replace('"', '', $withourbackpath);
                    file_put_contents(CREATE_TEMPLATE_DIR.$template_file_name.'.php', $template_details);

                    $data['template_path']  = $file_path;
                    $data['template_name']  = $template_file_name;
                    $data['template_header']  = $this->request->getVar('template_header');
                    $data['template_footer']  = $this->request->getVar('template_footer');
                    $data['created_by']     = session('id');
                    $data['created_at']     = date('Y-m-d H:i:s');

                    $result = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert('wh_template',$data);
                    // Store log data
                    $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['template','record']), get_phrases(['created']), $result, 'wh_template');
                                        
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
                $isExist = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('wh_template', array('template_name'=>$template_file_name, 'id !='=> $id ));
                
                if( !empty($isExist) ){
                    $response = array(
                        'success'  =>'exist',
                        'message'  => get_phrases(['already', 'exists']),
                        'title'    => $MesTitle
                    );
                }else{
                    $record = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('wh_template', array('id'=>$id));
                    if(file_exists($record->template_path.'.php')){
                        unlink($record->template_path.'.php');
                    }
                    $tfile = TEMPLATE_DIR.$template_file_name;
                    $withourbackpath = str_replace('/\/', '/', $tfile);
                    $file_path = str_replace('"', '', $withourbackpath);
                    file_put_contents(CREATE_TEMPLATE_DIR.$template_file_name.'.php', $template_details);
    
                    $data['template_path']  = $file_path;
                    $data['template_name']  = $template_file_name;
                    $data['template_header']  = $this->request->getVar('template_header');
                    $data['template_footer']  = $this->request->getVar('template_footer');
                    $data['updated_by']          = session('id');
                    $data['updated_date']        = date('Y-m-d H:i:s');
    
                    $result = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_02_Update('wh_template',$data, array('id'=>$id));
                    // Store log data
                    $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['template','record']), get_phrases(['updated']), $id, 'wh_template');
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
        }
        echo json_encode($response);
        exit;
    }

    public function bdtaskt1m12c14_08_deleteRecord($id)
    { 
        $record = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('wh_template', array('id'=>$id));
        $MesTitle = get_phrases(['template', 'record']);
        if($record->isApproved == 1){
            $response = array(
                'success'  =>false,
                'message'  => "Approved Record Can't be Deleted",
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;
        }
        if(file_exists(CREATE_TEMPLATE_DIR.$record->template_name.'.php')){
            unlink(CREATE_TEMPLATE_DIR.$record->template_name.'.php');
        }
        $data = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_06_Deleted('wh_template', array('id'=>$id));
        // Store log data
        $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['template','record']), get_phrases(['deleted']), $id, 'wh_template');
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

    

   
}
