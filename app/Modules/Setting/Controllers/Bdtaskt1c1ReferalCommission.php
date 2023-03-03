<?php namespace App\Modules\Setting\Controllers;

use CodeIgniter\Controller;
use App\Modules\Setting\Models\Bdtaskt2m2ReferalModel;
use App\Models\Bdtaskt1m1CommonModel;
class Bdtaskt1c1ReferalCommission extends BaseController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bdtaskt1c1_01_CmModel = new Bdtaskt1m1CommonModel();
        $this->bdtaskt1c1_03_referal_model   = new Bdtaskt2m2ReferalModel();
    }

    public function index()
    {
        $this->Bdtaskt1c1_02_checkReferal();
        $data['referal']      = $this->bdtaskt1c1_03_referal_model->bdtaskt1m1_01_getReferal();
        $data['title']        = get_phrases(['update']);
        $data['moduleTitle']  = get_phrases(['referral','commission']);
        $data['module']       = "Setting";
        $data['page']         = "referal_setting"; 
        return $this->base_02_template->layout($data);
    }

    public function Bdtaskt1c1_02_checkReferal()
    {
          $this->db = db_connect();
            $counts = $this->db->table('referal_commission_setting')
                             ->get();
                              

        if (count($counts->getResult()) == 0) {
            $data = array(
                'f_h_percentage' => 0,
                's_h_percentage' => 0,
                'commission_type'=> 1,
                'created_by'     => session('id'),
            );
             
            $settingsdata = $this->db->table('referal_commission_setting');
            $settingsdata->insert($data);   
            
        }
    }

    public function bdtaskt1c1_03_update()
    {
       
        $data['referal'] = (object)$postData = [
            'id'                => $this->request->getVar('id'),
            'f_h_percentage'    => $this->request->getVar('f_h_percentage'),
            's_h_percentage'    => $this->request->getVar('s_h_percentage'),
            'commission_type'   => $this->request->getVar('commission_type'),
        ]; 
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'f_h_percentage'   => ['label' => get_phrases(['first','hand','commission']),'rules'   => 'required|numeric'],
                's_h_percentage'   => ['label' => get_phrases(['second','hand','commission']),'rules'   => 'required|numeric'],
                'commission_type'  => ['label' => get_phrases(['commission','type']),'rules'   => 'required']
            ];
        }

        if (! $this->validate($rules)) {
            $this->session->setFlashdata('exception', $this->validator->listErrors());
        }else{
            if(empty($postData['id'])){
                $this->bdtaskt1c1_03_referal_model->bdtaskt1m1_03_saveSetting($postData);
                // Store log data
                $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['referal','commission']), get_phrases(['created']), $postData['id'], 'referal_commission_setting', 1);
                $this->session->setFlashdata('message', get_phrases(['Successfully', 'saved']));
                return redirect()->route('settings/referral_commissions');
           
            }else{
                $this->bdtaskt1c1_03_referal_model->bdtaskt1m1_04_updateSetting($postData);
                 // Store log data
                $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['referal','commission']), get_phrases(['updated']), $postData['id'], 'referal_commission_setting', 2);
                $this->session->setFlashdata('message', get_phrases(['Successfully', 'updated']));
                
            }
            
        }
        return redirect()->route('settings/referral_commissions');
    }

}