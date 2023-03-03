<?php namespace App\Modules\Setting\Controllers;

use CodeIgniter\Controller;
use App\Modules\Setting\Models\Bdtaskt1m1Setting;
use App\Models\Bdtaskt1m1CommonModel;
class Bdtaskt1c1Setting extends BaseController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bdtaskt1c1_01_CmModel = new Bdtaskt1m1CommonModel();
        $this->bdtaskt1c1_02_settingmodel   = new Bdtaskt1m1Setting();
    }

    public function index()
    {
        $this->Bdtaskt1c1_02_checkSetting();
        $data['languageList'] = $this->bdtaskt1c1_02_settingmodel->bdtaskt1m1_01_languageList();
        $data['setting']      = $this->bdtaskt1c1_02_settingmodel->bdtaskt1m1_02_getSetting();
        $data['cardinfo']     = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('id_card_tbl');
        $data['reCaptcha']    = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('recaptcha');
        $data['title']        = get_phrases(['update']);
        $data['moduleTitle']  = get_phrases(['settings']);
        $data['module']       = "Setting";
        $data['page']         = "setting"; 
        return $this->base_02_template->layout($data);
    }

    public function bdtaskt1c1_01_update(){
        // echo '<pre>';
        $logo = $this->base_01_fileUpload->doUpload('/assets/dist/img/logo', $this->request->getFile('logo'));

        $admin_logo = $this->base_01_fileUpload->doUpload('/assets/dist/img/logo', $this->request->getFile('admin_logo'));

        $favicon = $this->base_01_fileUpload->doUpload('/assets/dist/img/favicon', $this->request->getFile('favicon'));
        //$favicon_path = ltrim($favicon, '.');

        $old_logo = $this->request->getVar('old_logo'); 
        $old_admin_logo = $this->request->getVar('old_admin_logo'); 
        $old_favicon = $this->request->getVar('old_favicon'); 
        $data['setting'] = (object)$postData = [
            'id'                => $this->request->getVar('id'),
            'title'             => $this->request->getVar('title'),
            'address'           => $this->request->getVar('address'),
            'email'             => $this->request->getVar('email'),
            'phone'             => $this->request->getVar('phone'),
            'logo'              => (!empty($logo)?$logo:$old_logo),
            'admin_logo'        => (!empty($admin_logo)?$admin_logo:$old_admin_logo),
            'favicon'           => (!empty($favicon)?$favicon:$old_favicon),
            'language'          => $this->request->getVar('language'), 
            'currency'          => $this->request->getVar('currency_name'), 
            'currency_symbol'   => $this->request->getVar('currency_symbol'), 
            'currency_position' => $this->request->getVar('currency_position'), 
            'time_zone'         => $this->request->getVar('time_zone'), 
            'voucher_notes'     => $this->request->getVar('voucher_notes'), 
            'default_vat'       => $this->request->getVar('default_vat'), 
            'site_align'        => $this->request->getVar('site_align'), 
            'footer_text'       => $this->request->getVar('footer_text') ,
            
        ]; 
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'address'   => 'required|max_length[250]',
                'phone'     => 'required',
                'email'     => 'required|min_length[6]|max_length[50]|valid_email',
                // 'language'  => 'required',
            ];
        }

        if (! $this->validate($rules)) {
            $this->session->setFlashdata('exception', $this->validator->listErrors());
        }else{
            if(empty($postData['id'])){
                $this->bdtaskt1c1_02_settingmodel->bdtaskt1m1_03_saveSetting($postData);
                // Store log data
                $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['setting']), get_phrases(['created']), $postData['id'], 'setting', 1);
                $this->session->setFlashdata('message', get_phrases(['Successfully', 'saved']));
                return redirect()->route('settings/application');
           
            }else{
                $this->bdtaskt1c1_02_settingmodel->bdtaskt1m1_04_updateSetting($postData);
                $postData1 = [
                    'title'       => $this->request->getVar('title'),
                    'address'     => $this->request->getVar('address'),
                    'defaultLang' => $this->request->getVar('language'),
                    'currency'    => $this->request->getVar('currency_symbol'),
                    'cposition'   => $this->request->getVar('currency_position')
                ]; 
                $this->session->set($postData1);
                 // Store log data
                $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['setting']), get_phrases(['updated']), $postData['id'], 'setting', 2);
                $this->session->setFlashdata('message', get_phrases(['Successfully', 'updated']));
                
            }
            
        }
        return redirect()->route('settings/application');
    }
    

    public function Bdtaskt1c1_02_checkSetting()
    {
          $this->db = db_connect();
            $counts = $this->db->table('setting')
                             ->get();
                              

        if (count($counts->getResult()) == 0) {
            $data = array(
                'title' => 'Dynamic Admin Panel',
                'address' => '123/A, Street, State-12345, Demo',
                'footer_text' => '2020&copy;Copyright',
            );
             
            $settingsdata = $this->db->table('setting');
            $settingsdata->insert($data);   
            
        }
    }

    public function bdtaskt1c1_03_basicList(){
        $gender = $this->request->getVar('gender');
        $blood = $this->request->getVar('blood');
        $marital = $this->request->getVar('marital');
        $arrGender[] = '';
        $arrBlood[] = '';
        $arrMarital[] = '';
        if(!empty($gender)){
            for ($i=0; $i < sizeof($gender); $i++) { 
                if(!empty($gender[$i]))
                    $arrGender[$i] = $gender[$i];
            }
        }

        if(!empty($blood)){
            for ($j=0; $j < sizeof($blood); $j++) { 
                if(!empty($blood[$j]))
                    $arrBlood[$j] = $blood[$j];
            }
        }

        if(!empty($marital)){
            for ($k=0; $k < sizeof($marital); $k++) { 
                if(!empty($marital[$k]))
                    $arrMarital[$k] = $marital[$k];
            }
        }

        $data['setting'] = (object)$postData = [
            'id'          => $this->request->getVar('id'),
            'gender'      => json_encode($arrGender),
            'blood'       => json_encode($arrBlood),
            'marital'     => json_encode($arrMarital),
        ]; 
        $title = get_phrases(['settings', 'record']);
        if($this->bdtaskt1c1_02_settingmodel->bdtaskt1m1_04_updateSetting($postData)){
            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['updated', 'successfully']),
                'title'    => $title
            );
        }else{
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['something', 'want', 'wrong']),
                'title'    => $title
            );
        }
        echo json_encode($response);
    }

    /*--------------------------
    | save ID card info
    *--------------------------*/
    public function bdtaskt1c1_04_idCard()
    { 
        $card_id  = $this->request->getVar('card_id'); 
        // base data
        $data = array(
            'emp_instruction'      => $this->request->getVar('emp_instruct'), 
            'pa_instruction'       => $this->request->getVar('pa_instruct'),  
        );

        $MesTitle = get_phrases(['id', 'card', 'record']);
        // employee card logo
        if(!empty($this->request->getFile('emp_logo'))){
            $empPath = $this->base_01_fileUpload->doUpload('./assets/dist/img/id_card_tbl', $this->request->getFile('emp_logo'));
        }
        // patient crd logo
        if(!empty($this->request->getFile('pa_logo'))){
            $paPath = $this->base_01_fileUpload->doUpload('./assets/dist/img/id_card_tbl', $this->request->getFile('pa_logo'));
        }
        // author signature photo
        if(!empty($this->request->getFile('signature'))){
            $signPath = $this->base_01_fileUpload->doUpload('./assets/dist/img/id_card_tbl', $this->request->getFile('signature'));
        }

        $data['emp_logo']    = !empty($empPath)?ltrim($empPath, '.'):$this->request->getVar('old_emp_logo');
        $data['pa_logo']  = !empty($paPath)?ltrim($paPath, '.'):$this->request->getVar('old_pa_logo');
        $data['signature']= !empty($signPath)?ltrim($signPath, '.'):$this->request->getVar('old_signature');
        
        $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_02_Update('id_card_tbl',$data, array('id'=>$card_id));
        
        if($result){
            // Store log data
            $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['id', 'card']), get_phrases(['updated']), $card_id, 'id_card_tbl', 2);
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
        echo json_encode($response);
    }

    /*--------------------------
    | update google recptcha info
    *--------------------------*/
    public function bdtaskt1c1_05_reCaptcha()
    { 
        $recaptch_id  = $this->request->getVar('recaptch_id'); 
        // store data
        $data = array(
            'site_key'     => $this->request->getVar('site_key'), 
            'secret_key'   => $this->request->getVar('secret_key'),
            'status'       => $this->request->getVar('status')
        );

        $MesTitle = get_phrases(['recaptcha', 'record']);
        
        $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_02_Update('recaptcha',$data, array('id'=>$recaptch_id));
        
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
        echo json_encode($response);
    }

    /*--------------------------
    | Get financial types
    *--------------------------*/
    public function bdtaskt1c1_11_financialTypeList()
    {
        $data['list'] = $this->bdtaskt1c1_02_settingmodel->bdtaskt1m1_06_getFinancialTypeList();
        $types = view('App\Modules\Setting\Views\financial_type_list', $data);
        echo json_encode(array('info'=> $types));
    }

    /*--------------------------
    | Save financial type
    *--------------------------*/
    public function bdtaskt1c1_12_addFinancialType()
    { 
        $type_id  = $this->request->getVar('financial_type_id'); 
        $action  = $this->request->getVar('action'); 
        // store data
        $data = array(
            'branch_id'   => $this->request->getVar('branch_id'), 
            'nameE'       => $this->request->getVar('type_english'),
            'nameA'       => $this->request->getVar('type_arabic'),
            'start_amount'=> $this->request->getVar('start_amount'),
            'end_amount'  => $this->request->getVar('end_amount'),
            'color'       => $this->request->getVar('type_color')
        );

        $MesTitle = get_phrases(['financial', 'type']);
        if($action=='add'){
            $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_01_Insert('financial_type',$data);
            if($result){
                 $response = array(
                    'success'  => true,
                    'message'  => get_phrases(['added', 'successfully']),
                    'title'    => $MesTitle
                );
            }else{
                $response = array(
                    'success'  => false,
                    'message'  => get_phrases(['something', 'went', 'wrong']),
                    'title'    => $MesTitle
                );
            }
        }else{
            $result = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_02_Update('financial_type',$data, array('id'=>$type_id));
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
        
        echo json_encode($response);
    }

    /*--------------------------
    | Get finanacial type by id
    *--------------------------*/
    public function bdtaskt1c1_13_getFinanTypeById()
    {
        $type_id = $this->request->getVar('type_id');
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('financial_type', array('id'=>$type_id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get finanacial type by id
    *--------------------------*/
    public function bdtaskt1c1_14_deleteFinanTypeById()
    {
        $MesTitle = get_phrases(['financial', 'type']);
        $type_id = $this->request->getVar('type_id');
        $data = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_06_Deleted('financial_type', array('id'=>$type_id));
        if($data){
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
    | update google recptcha info
    *--------------------------*/
    public function bdtaskt1c1_06_sendMail()
    { 
        // echo phpinfo();exit;
        $token  = 'gfdh@sda!';
        $email_id= 'asrafrahmanb@gmail.com';
        $message = "click here for change your password ".base_url('dashboard/home/').'?email='.$email_id.'&token='.$token;
        $email = \Config\Services::email();

        $email->setFrom('ashrafusa.edu@gmail.com', 'Password Recovery');
        $email->setTo($email_id);
        $email->setSubject('Recovery Password');
        $email->setMessage($message);
        $filename = 'http://161.35.213.113/assets/dist/img/avatar-1.jpg';
        $email->attach($filename);
       //$email->send();
       if ($email->send()) {
            echo 'Email successfully sent';
        } else {
            $data = $email->printDebugger(['headers']);
            print_r($data);
        }
      
    }

}
