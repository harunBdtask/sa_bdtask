<?php namespace App\Modules\Lc\Controllers;
use CodeIgniter\Controller;
use App\Libraries\Permission;

use App\Modules\Lc\Models\Lcmodel;
use App\Models\Bdtaskt1m1CommonModel;


class Bdtasktt_ahlc extends BaseController
{
    private $bdtasktt_lcmodel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtasktt_lcmodel = new Lcmodel();
        $this->bdtaskt1m12c14_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Lc list
    *--------------------------*/
    public function index()
    {

        $data['title']      = 'LC List';
        $data['moduleTitle']= 'Manage LC';
        $data['hasCreateAccess']    = $this->permission->method('wh_lc', 'create')->access();
        $data['hasPrintAccess']     = $this->permission->method('wh_lc', 'print')->access();
        $data['hasExportAccess']    = $this->permission->method('wh_lc', 'export')->access();
        $data['isDTables']  = true;
        $data['module']     = "Lc";
        $data['page']       = "lc/__list";

        $data['banks'] = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_04_getResult('ah_bank');
        return $this->bdtaskt1c1_02_template->layout($data);

    }


    /*--------------------------
    | Get all LC info
    *--------------------------*/
    public function bdtasktt_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtasktt_lcmodel->bdtasktt_getList($postData);

        echo json_encode($data);
    }


    
        /*--------------------------
        | Add new LC
        *--------------------------*/
        public function bdtasktt_ahossain_lc()
        { 
    
            $action     = $this->request->getVar('action');
            $lc_attc    = $this->request->getFileMultiple('lc_attc');
            $id         = $this->request->getVar('id');
            
            $data = array(

                'lc_number'        => $this->request->getVar('lc_number'), 
                'lc_open_date'     => $this->request->getVar('lc_open_date'),
                'lc_bank_id'       => $this->request->getVar('lc_bank_id'),
                'lc_margin'        => $this->request->getVar('lc_margin'),
                'country_code'     => $this->request->getVar('country_code'),
                'lc_amount'        => $this->request->getVar('lc_amount'),
                'bdt_rate'        => $this->request->getVar('bdt_rate'),
                'bdt_amount'        => $this->request->getVar('lc_amount_bdt')

            );
    
    
            $rules=array();
            if ($this->request->getMethod() == 'post') {
                $rules = [
                    'lc_number'             => 'required',
                    'lc_open_date'          => 'required',
                    'lc_bank_id'          => 'required',
                    'lc_margin'             => 'required',
                    'lc_amount'             => 'required',
                    'bdt_rate'             => 'required',
                    'lc_amount_bdt'             => 'required'
                ];
            }

            $MesTitle = 'LC record';
    
            if (! $this->validate($rules)) {
    
                $response = array(
                    'success'  =>false,
                    'message'  => $this->validator->listErrors(),
                    'title'    => $MesTitle
                );
                
            }else{
    
                if($action=='add'){
    
                    $lc_number = $this->request->getVar('lc_number');
                
                    $isExist2 = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('ah_lc', array('lc_number'=>$lc_number));
    
                    if(!empty($isExist2) ){
                        $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['already', 'exists']),
                            'title'    => $MesTitle
                        );
                    }else{
                        
                        $data['create_by'] = session('id');
                        $data['create_date'] = date('H:m:d');

                        $result = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert('ah_lc',$data);
    
                        if($result){

                            $lcname = $this->request->getVar('name');
                            $file=[];
                            if ($lc_attc) {
                                foreach($this->request->getFileMultiple('lc_attc') as $key=> $fil)
                                { 
                                    $qc_attachment = $this->bdtaskt1c1_01_fileUpload->doUpload('/assets/dist/lc', $fil);
                                    if (empty($qc_attachment)) {
                                        $qc_attachment = null;
                                    }
                                    $data2 = array(
                                        'attachment' => $qc_attachment,
                                        'name' => $lcname[$key],
                                        'lc_id' => $result
                                    ); 
                                    $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert('ah_lc_attachment',$data2);  
                                }
                            }

                            $response = array(
                                'success'  => true,
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

                    
                    $info = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('ah_lc', array('row_id'=>$id));
                    $lcname = $this->request->getVar('name');
                    $old_attach = $this->request->getVar('old_attach');

                    $file=[];
                    if ($lc_attc) {
                        
                        $unlink = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_06_Deleted('ah_lc_attachment', array('lc_id'=>$id));
                        //$unlink = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_05_getResultWhere('ah_lc_attachment', array('lc_id'=>$id));
                       

                        foreach($this->request->getFileMultiple('lc_attc') as $key=> $fil)
                        { 

                            $qc_attachment = $this->bdtaskt1c1_01_fileUpload->doUpload('/assets/dist/lc', $fil);
                            if (empty($qc_attachment)) {
                                $qc_attachment = @$old_attach[$key];
                            }

                            if($qc_attachment){
                                $data2 = array(
                                    'attachment' => $qc_attachment,
                                    'name' => $lcname[$key],
                                    'lc_id' => $id
                                ); 
                                $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_01_Insert('ah_lc_attachment',$data2);  
                            }
                            
                        }

                    }

                    $result = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_02_Update('ah_lc',$data, array('row_id'=>$id));
                    
                    // Store log data
                    $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item','Lc']), get_phrases(['updated']), $id, 'ah_lc');
                    
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
    | delete  by ID
    *--------------------------*/
    public function bdtasktt_delete_lc()
    { 

        $id = $this->request->getVar('lc_number');
        $uselc = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_05_getResultWhere('ah_po', array('lc_number'=>$id));
        $MesTitle = get_phrases(['Lc', 'record']);

        if(!empty($uselc)){
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['Lc', 'record', 'exists']),
                'title'    => $MesTitle
            );
            echo json_encode($response);
            exit;

        }

        $data = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_06_Deleted('ah_lc', array('lc_number'=>$id));

        if(!empty($data)){
            
            $response = array(
                'success'  => true,
                'message'  => get_phrases(['deleted', 'successfully']),
                'title'    => $MesTitle
            );

        }else{

            $response = array(
                'success'  => false,
                'message'  => get_phrases(['something', 'went', 'wrong']),
                'title'    => $MesTitle
            );

        }
        echo json_encode($response);
    }



    /*--------------------------
    | Get Lc by ID
    *--------------------------*/
    public function bdtasktt_get_lc($id)
    { 
        $data = $this->bdtasktt_lcmodel->get_lc_by_id($id);
        echo json_encode($data);
    }



    /*--------------------------
    | Get supplier details by ID
    *--------------------------*/
    public function bdtasktt_lc_view($id)
    { 

        $data = $this->bdtasktt_lcmodel->get_lc_by_id($id);
        $attach = $this->bdtasktt_lcmodel->get_lc_attach_by_id($id);

        $list = '';
        if(!empty($data)){
            $country_name = ($data->country_code)?countries($data->country_code):'';
            
            // if($data->status==1){
            //     $status = get_phrases(['active']);
            // }else{
            //     $status = get_phrases(['inactive']);
            // }
            $list .= '<tr>
                        <th class="text-right">'.get_phrases(['LC Number']).'</th>
                        <td class="text-left">'.$data->lc_number.'</td>
                        <th class="text-right">'.get_phrases(['LC Bank']).'</th>
                        <td class="text-left">'.$data->bank_name.'</td>
                    </tr>

                    <tr>
                        <th class="text-right">'.get_phrases(['LC', 'Amount']).'</th>
                        <td class="text-left">'.$data->lc_amount.'</td>
                        <th class="text-right">'.get_phrases(['LC Margin']).'</th>
                        <td class="text-left">'.$data->lc_margin.'</td>
                    </tr>
                    <tr>
                        <th class="text-right">'.get_phrases(['BDT Rate']).'</th>
                        <td class="text-left">'.$data->bdt_rate.'</td>
                        <th class="text-right">'.get_phrases(['BDT Amount']).'</th>
                        <td class="text-left">'.$data->bdt_amount.'</td>
                    </tr>

                    <tr>
                        <th class="text-right">'.get_phrases(['LC', 'Open Date']).'</th>
                        <td class="text-left">'.$data->lc_open_date.'</td>
                        <th class="text-right">'.get_phrases(['Country']).'</th>
                        <td class="text-left">'.$country_name.'</td>
                    </tr>';

                    if(!empty($attach)){
                        foreach ($attach as $key => $value) {
                            $list.='<tr>
                                <th class="text-right" colspan="2">'.$value->name.'</th>
                                <td class="text-left" colspan="2"><a href="'.base_url().$value->attachment.'" target="_blank" rel="noopener noreferrer" class="btn btn-success"><i class="fa fa-download"></i> </a></td>
                            </tr>';
                        }
                    }
                   
        }
        echo json_encode(array('data'=>$list));
    }


    
    /*--------------------------
    | Get 
    *--------------------------*/
    public function bdtasktt_lc_get_attachment($id)
    { 

        $attach = $this->bdtasktt_lcmodel->get_lc_attach_by_id($id);
        $list = '';
        if(!empty($attach)){
           
            if(!empty($attach)){
                foreach ($attach as $key => $value) {

                    $list .= '<div class="input-group" style="margin-top:5px;"><a href="'.base_url().$value->attachment.'" target="_blank" rel="noopener noreferrer" class="btn btn-success"><i class="fa fa-download"></i> </a>
                                    <input type="text" name="name[]" class="form-control" value="'.$value->name.'" >
                                    <input type="hidden" name="lc_id" value="'.$value->lc_id.'" class="form-control" >
                                    <input type="hidden" name="old_attach[]" value="'.$value->attachment.'" class="form-control" >
                                    <input type="file" name="lc_attc[]" class="form-control">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-danger removeRow" ><i class="fa fa-minus"></i></button>
                                    </div>
                                </div>';

                   
                }
            }
                   
        }
        echo json_encode(array('data'=>$list));
    }





}
