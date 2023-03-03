<?php namespace App\Modules\Dashboard\Controllers;
use CodeIgniter\Controller;
use App\Modules\Dashboard\Models\Bdtaskt1m1AuthModel;
use App\Modules\Setting\Models\Bdtaskt1m1Setting;
//use App\Modules\Pharmacy\Models\Bdtaskt1m11SaleModel;
//use App\Modules\Account\Models\Bdtaskt1m8SerInvModel;
use App\Models\Bdtaskt1m1CommonModel;

class Bdtaskt1c1Auth extends BaseController
{
    private $bdtaskt1c1_01_authModel;
    private $bdtaskt1c1_02_settingModel;
    private $bdtaskt1c1_03_CmModel;
    private $bdtaskt1c1_04_saleModel;
    private $bdtaskt1c1_05_serInvModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bdtaskt1c1_01_authModel = new Bdtaskt1m1AuthModel();
        $this->bdtaskt1c1_02_settingModel = new Bdtaskt1m1Setting();
        $this->bdtaskt1c1_03_CmModel = new Bdtaskt1m1CommonModel();
        //$this->bdtaskt1c1_04_saleModel = new Bdtaskt1m11SaleModel();
        //$this->bdtaskt1c1_05_serInvModel = new Bdtaskt1m8SerInvModel();
           
    }

    public function index()
    {  
        if (is_valid_logged()==true){
            return redirect()->route('dashboard/home');
        }
        $data = [];
        $data['title']      = get_phrases(['login']);
        $data['moduleTitle']= get_phrases(['login']);
        $data['setting']    = $this->bdtaskt1c1_02_settingModel->bdtaskt1m1_02_getSetting();
        $data['branch_list']    = $this->bdtaskt1c1_01_authModel->bdtaslt1m1_10_getBranchs();
        if ($this->request->getMethod() == 'post') {
            //let's do the validation here
            $rules = [
                'username' => 'required|min_length[6]|max_length[50]',
                'password' => 'required|min_length[6]|max_length[50]'
            ];
            $errors = [
                'password' => [
                    'validateUser' => 'Username or Password don\'t match'
                ]
            ];

            if (!$this->validate($rules,$errors)) {
                $data['validation'] = $this->validator;
                return view('template/login', $data);
            }else{
                $data['user'] = (object)$userData = array(
                    'username'      => $this->request->getVar('username'),
                    'password'   => $this->request->getVar('password')
                );
                $user = $this->bdtaskt1c1_01_authModel->bdtaslt1m1_02_checkUser($userData);


                if(count($user->getResult()) > 0) {
                    if($user->getRow()->status == 1){
                        if($user->getRow()->user_role == 1){
                            $checkPermission = $this->bdtaskt1c1_01_authModel->bdtaslt1m1_03_userPermissionadmin(1);
                        }else{
                            $checkPermission = $this->bdtaskt1c1_01_authModel->bdtaslt1m1_04_userPermission($user->getRow()->emp_id);
                        }
                        
                        $permission1 = array();
                        $permission = array();
                        if(!empty($checkPermission)){
                            foreach ($checkPermission as $value) {
                                if (in_array($value->mdir, $permission1) ) { 
                                } else {
                                    $permission1[$value->mdir]=1;
                                } 

                                $permission[$value->directory]['create'] = !empty($permission[$value->directory]['create'])?1:$value->create;
                                $permission[$value->directory]['read'] = !empty($permission[$value->directory]['read'])?1:$value->read;
                                $permission[$value->directory]['update'] = !empty($permission[$value->directory]['update'])?1:$value->update;
                                $permission[$value->directory]['delete'] = !empty($permission[$value->directory]['delete'])?1:$value->delete;
                                $permission[$value->directory]['print'] = !empty($permission[$value->directory]['print'])?1:$value->print;
                                $permission[$value->directory]['export'] = !empty($permission[$value->directory]['export'])?1:$value->export;
                                /*array(
                                    'create' => $value->create,
                                    'read'   => $value->read,
                                    'update' => $value->update,
                                    'delete' => $value->delete,
                                    'print' => $value->print,
                                    'export' => $value->export
                                );*/
                            }
                        }
                        $sid_web = uniqid('bd_', true);
                        // codeigniter session stored data          
                        $sData = array(
                            'isLogIn'     => true,
                            'isAdmin'     => (($user->getRow()->user_role == 1)?true:false),
                            'id'          => $user->getRow()->emp_id,
                            'fullname'    => $user->getRow()->fullname,
                            'user_role'   => $user->getRow()->user_role,
                            'user_level'  => $user->getRow()->type,
                            'username'    => $user->getRow()->username,
                            'branchId'    => $this->request->getVar('branch_id'),
                            'branch_op'   => $user->getRow()->branch_id,
                            'departmentId'=> $user->getRow()->department_id,
                            'store_id'    => ($user->getRow()->user_role == 1)?'':$user->getRow()->store_id,
                            'image'       => $user->getRow()->photo,
                            'signature'   => $user->getRow()->signature,
                            'currency'    => $data['setting']->currency_symbol,
                            'cposition'   => $data['setting']->currency_position,
                            'title'       => $data['setting']->title,
                            'address'     => $data['setting']->address,
                            'defaultLang' => $data['setting']->language,
                            'site_align'  => $data['setting']->site_align,
                            'auth_token'  => $sid_web,
                            'permission'  => json_encode($permission),
                            'label_permission'  => json_encode($permission1) 
                        );  

                        //store date +- session 
                        $this->session->set($sData);
                        $this->session->setTempdata('isUserActivity', true, 300);//expire 1 minutes
                        //$this->session->setTempdata('isUserActivity', true, 900);//expire 15 minutes
                        // echo "<pre>";
                        // print_r(json_decode($this->session->get('permission'), true));die();
                        $ipadd = $this->request->getIPAddress();
                        $this->bdtaskt1c1_01_authModel->bdtaslt1m1_05_last_login($ipadd);
                        $this->bdtaskt1c1_01_authModel->bdtaslt1m1_08_updateUser($user->getRow()->emp_id, $sid_web);
                        // delete all export excel
                        $this->bdtaskt1c1_03_deleteExport();

                        $domainName = $_SERVER['HTTP_HOST'];
                        if($domainName !='localhost'){
                            $this->db->simpleQuery("SET session time_zone = '+03:00'");
                        }

                        $this->session->setFlashdata('message', get_phrases(['welcome', 'back']).' '.$user->getRow()->fullname);
                        return redirect()->route('dashboard/home');
                    }else{
                        $data['exception']  =  get_notify('your_credentials_are_deactivated!_please_contact_with_administrator').'.';
                        return view('template/login', $data);
                    }

                } else {
                    $data['exception']  =  get_notify('user_credentials_or_branch_is_not_correct');
                    return view('template/login', $data);
                } 
      
            }
        }else{
            return view('template/login', $data);
        }
    }
  
    public function bdtaskt1c1_01_logout()
    { 
        //destroy session
        $ipadd = $this->request->getIPAddress();
        $actionData = array(
            'emp_id'   => session('id'), 
            'type'     => 'Credential', 
            'action'   => 'Logout', 
            'action_id'=> 0, 
            'slug'     => uri_string()
        );
        $this->bdtaskt1c1_01_authModel->bdtaslt1m1_07_last_logout($ipadd, $actionData);
        $this->session->destroy();
        return redirect()->route('login');
    }

    // change  language
    public function bdtaskt1c1_02_changelangauge()
    { 
        $language = $this->request->getVar('language');
        if($language=='english'){
            $site_align = 'left-to-right';
        }else{
            $site_align = 'right-to-left';
        }
        $data = array(
            'defaultLang'=> $language,
            'site_align'=> $site_align
        );
        $this->session->set($data);
        $res = array(
            'success' => true,
        );  
        
        // $response = $this->bdtaskt1c1_01_authModel->bdtaslt1m1_09_updateLanguage($data);
        // if($response){
        //     $this->session->set();
        //     $res = array(
        //         'success' => true,
        //     );
        // }else{
        //     $res = array(
        //         'success' => false,
        //     );
        // }
        echo json_encode($res);
        exit();
    }

    public function bdtaskt1c1_03_deleteExport()
    { 
        helper('filesystem');
        delete_files('./assets/excel_data/');
        return true;
    }

    // change  language
    public function bdtaskt1c1_04_changeBranch()
    { 
        $top_branch = $this->request->getVar('top_branch');

        $branch_op = session('branch_op');
        $branch_list = explode(",", $branch_op);
        if( in_array($top_branch, $branch_list) || session('isAdmin')===true){
            $data = array(
                'branchId'=> $top_branch
            );
            $this->session->set($data);
            $res = array(
                'success' => true,
                'message' => 'Switched to new branch',
                'title' => 'Success!',
            );  
            
            echo json_encode($res);
            exit();
        }

        $res = array(
            'success' => false,
            'message' => 'User can not access to this branch',
            'title' => 'Failed!',
        );  
        
        echo json_encode($res);
        exit();
    }
  
    function invoicePDF($sale_id){

        $data['settings_info'] = $this->bdtaskt1c1_03_CmModel->bdtaskt1m1_03_getRow('ph_setting', array('id'=>1));
        $data['sale'] = $this->bdtaskt1c1_03_CmModel->bdtaskt1m1_03_getRow('ph_sale', array('id'=>$sale_id));

        $data['store'] = '';
        $data['customer'] = '';
        $data['employees'] = '';
        if(!empty($data['sale'])){
            $data['store'] = $this->bdtaskt1c1_03_CmModel->bdtaskt1m1_03_getRow('ph_sub_store', array('id'=>$data['sale']->store_id));
            $data['customer'] = $this->bdtaskt1c1_03_CmModel->bdtaskt1m1_03_getRow('ph_customer_information', array('id'=>$data['sale']->customer_id));
            $data['employees'] = $this->bdtaskt1c1_03_CmModel->bdtaskt1m1_03_getRow('employees', array('emp_id'=>$data['sale']->doctor_id));
        }

        $data['sales_details'] = $this->bdtaskt1c1_04_saleModel->bdtaskt1m11_20_getSaleItemDetailsById($sale_id);
        $data['payment_details'] = $this->bdtaskt1c1_03_CmModel->bdtaskt1m1_05_getResultWhere('ph_sale_payment_details', array('sale_id'=>$sale_id));

        //echo view('App\Modules\Pharmacy\Views\sale\invoice_pdf', $data);exit;
        //echo FCPATH.'assets/plugins/bootstrap/css/';exit;

        $dompdf = new \Dompdf\Dompdf(); 

        //$data['website'] = $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_03_getRow('setting');
        //$data['details'] = $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_03_getInvDetailsId($id);
        // Sending data to view file
        //$dompdf->setBasePath(FCPATH.'assets/plugins/bootstrap/css/');
        $dompdf->loadHtml(view('App\Modules\Pharmacy\Views\sale\invoice_pdf', $data));
        // setting paper to portrait, also we have landscape
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        // Download pdf
        $dompdf->stream('invoice_'.time().'.pdf'); 
        // to give pdf file name
        // $dompdf->stream("myfile");
        //return redirect()->to('detailsInvoice/'.$id);
    }
    
    function invoicePOS($sale_id){

        //$sale_id = $this->request->getVar('id');

        $data['setting_info'] = $this->bdtaskt1c1_03_CmModel->bdtaskt1m1_03_getRow('ph_setting', array('id'=>1));
        $data['sale'] = $this->bdtaskt1c1_03_CmModel->bdtaskt1m1_03_getRow('ph_sale', array('id'=>$sale_id));
        $data['customer'] = $this->bdtaskt1c1_03_CmModel->bdtaskt1m1_03_getRow('ph_customer_information', array('id'=>$data['sale']->customer_id));
        $data['employees'] = $this->bdtaskt1c1_03_CmModel->bdtaskt1m1_03_getRow('employees', array('emp_id'=>$data['sale']->doctor_id));
        $data['sales_details'] = $this->bdtaskt1c1_03_CmModel->bdtaskt1m1_05_getResultWhere('ph_sale_details', array('sale_id'=>$sale_id));
        $data['payment_details'] = $this->bdtaskt1c1_03_CmModel->bdtaskt1m1_05_getResultWhere('ph_sale_payment_details', array('sale_id'=>$sale_id));

        $ph_language = $this->bdtaskt1c1_03_CmModel->bdtaskt1m1_05_getResultWhere('ph_language');
        $language = array();
        foreach($ph_language as $row){
            $language[$row->phrase]['ar'] = $row->arabic;
            $language[$row->phrase]['en'] = $row->english;
        }
        $data['language']=$language;
        //echo view('App\Modules\Pharmacy\Views\sale\invoice_pdf', $data);exit;
        //echo FCPATH.'assets/plugins/bootstrap/css/';exit;

        $dompdf = new \Dompdf\Dompdf(); 

        //$data['website'] = $this->bdtaskt1m8c2_02_CmModel->bdtaskt1m1_03_getRow('ph_setting');
        //$data['details'] = $this->bdtaskt1m8c2_01_servInModel->bdtaskt1m8_03_getInvDetailsId($id);
        // Sending data to view file
        //$dompdf->setBasePath(FCPATH.'assets/plugins/bootstrap/css/');
        $dompdf->loadHtml(view('App\Modules\Pharmacy\Views\sale\pos_invoice_pdf', $data));
        // setting paper to portrait, also we have landscape
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        // Download pdf
        $dompdf->stream('pos_invoice_'.time().'.pdf'); 
        // to give pdf file name
        // $dompdf->stream("myfile");
        //return redirect()->to('detailsInvoice/'.$id);
    }


    function generatePDF($id){

        $dompdf = new \Dompdf\Dompdf(); 

        $data['website'] = $this->bdtaskt1c1_03_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['details'] = $this->bdtaskt1c1_05_serInvModel->bdtaskt1m8_03_getInvDetailsId($id);
        $data['phrases'] = $this->bdtaskt1c1_03_CmModel->bdtaskt1m1_03_getRow('setting_vouchers', array('type'=>'service_invoice'));
        $data['lang']    = 'english';
        // Sending data to view file
        $dompdf->loadHtml(view('App\Modules\Account\Views\invoices\invoice_pdf', $data));
        // setting paper to portrait, also we have landscape
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        // Download pdf
        $dompdf->stream('invoice_'.$id.'.pdf'); 
        // to give pdf file name
        // $dompdf->stream("myfile");
        return redirect()->to('detailsInvoice/'.$id);
    }

    public function isLogIn()
    {
        if ($this->session->get('isLogIn') && session('auth_token') !='' ){
            echo 'OK';
        } else {
            echo 'NOTOK';
        }
    }

    public function clearLog()
    {
       $this->bdtaskt1c1_01_authModel->bdtaslt1m1_11_clearLog();
    }

}
