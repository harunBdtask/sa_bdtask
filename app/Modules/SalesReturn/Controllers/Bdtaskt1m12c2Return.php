<?php
//k
namespace App\Modules\SalesReturn\Controllers;

use App\Modules\SalesReturn\Views;
use CodeIgniter\Controller;
use App\Modules\SalesReturn\Models\Bdtaskt1m12ReturnModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;
use phpDocumentor\Reflection\Types\Integer;

class Bdtaskt1m12c2Return extends BaseController
{
    private $bdtask0124returnModel;
    private $bdtaskt1m12c2_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->db = db_connect();
        $this->bdtask0124returnModel  = new Bdtaskt1m12ReturnModel();
        $this->bdtaskt1c1_01_CmModel  = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Return form
    *--------------------------*/
    public function index()
    {
        $data['title']                = get_phrases(['find', 'return', 'information']);
        $data['moduleTitle']          = get_phrases(['delivery', 'return']);
        $data['dealer_list']          = $this->bdtask0124returnModel->bdtaskt1m12_01_dealerlist();
        $data['isDTables']            = true;
        $data['module']               = "SalesReturn";
        $data['page']                 = "form";
        return $this->bdtaskt1c1_02_template->layout($data);
    }


    public function return_form($id = null)
    {
        $challan_no = ($id?$id:$this->request->getVar('challan_no'));
       
        if ($challan_no) {
            if ($this->request->getMethod() == 'post') {
            $rules = [
                'challan_no'      => ['label' => get_phrases(['challan', 'no']), 'rules' => 'required'],
            ];
     
        $MesTitle = get_phrases(['region', 'record']);
        if (!$this->validate($rules)) {
            $this->session->setFlashdata('exception', $this->validator->listErrors());
            return redirect()->back()->withInput();
        } else {
            $isExist = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('do_delivery', array('challan_no' => $this->request->getPost('challan_no')));
            if (empty($isExist)) {
                $this->session->setFlashdata('exception', 'Invalid Challan No');
                return redirect()->back()->withInput();
            } 
        }
    }

        $data['title']                = get_phrases(['delivery', 'return', 'information']);
        $data['moduleTitle']          = get_phrases(['sales', 'return']);
        $data['delivery_main']        = $this->bdtask0124returnModel->delivery_main_data($challan_no);
        $data['delivery_details']     = $this->bdtask0124returnModel->do_delivery_details_byid($data['delivery_main']->delivery_id);
        $data['settings_info']        = $this->bdtask0124returnModel->setting_info();
        $data['module']               = "SalesReturn";
        $data['page']                 = "return_form";
        return $this->bdtaskt1c1_02_template->layout($data);
    }
    }

    public function bdtask002_save_sls_return()
    {
        $challan_no = $this->request->getVar('challan_no');
        $postData = array(
            'return_id'       => date('Ymdhis'),
            'do_id'           => $this->request->getVar('do_id'),
            'do_no'           => $this->request->getVar('do_no'),
            'challan_no'      => $this->request->getVar('challan_no'),
            'date'            => $this->request->getVar('date'),
            'dealer_id'       => $this->request->getVar('dealer_id'),
            'reason'          => $this->request->getVar('details'),
            'total_vat'       => $this->request->getVar('total_vat_amount'),
            'total_deduction' => $this->request->getVar('total_deduction'),
            'net_amount'      => $this->request->getVar('grand_total'),
            'return_type'     => $this->request->getVar('return_type'),
            'return_by'       => session('id')
        );
       
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'challan_no'      => ['label' => get_phrases(['challan', 'no']), 'rules' => 'required'],
                'date'            => ['label' => get_phrases(['date']), 'rules' => 'required'],
            ];
        
            if (!$this->validate($rules)) {
                
                $this->session->setFlashdata('exception', $this->validator->listErrors());

               // return redirect()->route('return/sales_return/return_form')->withInput(); 
                return redirect()->to('return_form/'.$challan_no)->withInput();
               
               
            }else{
                //print_r($postData);exit;
                $result = $this->bdtask0124returnModel->save_returnData($postData);
                if($result){
                    $coa_head = $this->db->table('acc_coa')->select('HeadCode')->where('dealer_id',$postData['dealer_id'])->get()->getRow();
                 $dealerledger = array(
                     'VNo'        => $postData['return_id'],
                     'Vtype'      => 'SRETURN',
                     'VDate'      => $postData['date'],
                     'COAID'      => ($coa_head?$coa_head->HeadCode:''),
                     'Narration'  => 'Sales Return for challan no '.$postData['challan_no'],
                     'Debit'      => 0,
                     'Credit'     => ($postData['net_amount']?$postData['net_amount']:0),
                     'do_no'      => $postData['do_no'],
                     'FyID'       => 0,
                     'CreateBy'   => session('id'),
                     'CreateDate' => date('Y-m-d H:i:s'),
                     'IsAppove'   => 1,
                 );

                 $this->db->table('acc_transaction')->insert($dealerledger);

                }

                $this->session->setFlashdata('message', get_phrases(['successfully', 'saved']));
                return redirect()->route('return/sales_return/sales_return_list');
            }
        }
    }

    /*--------------------------
    | Return list
    *--------------------------*/

    public function salesReturnList()
    {
        $data['title']                = get_phrases(['sales', 'return','list']);
        $data['moduleTitle']          = get_phrases(['sales', 'return']);
        $data['isDTables']            = true;
        $data['module']               = "SalesReturn";
        $data['page']                 = "list";
        $data['hasCreateAccess']      = 1;
        $data['hasPrintAccess']       = 1;
        $data['hasExportAccess']      = 1;
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    public function bdtaskt1m12c1_getReturnList()
    {
        $postData = $this->request->getVar();
        $data     = $this->bdtask0124returnModel->bdtaskt1m12_06_getSaleReturnlist($postData);
        echo json_encode($data);
    }

    public function bdtaskt1m12c1_getReturnDetailsById($return_id)
    {
        $data['main']           = $this->bdtask0124returnModel->return_main($return_id);
        $data['details']        = $this->bdtask0124returnModel->return_details($return_id);
        $data['settings_info']  = $this->bdtask0124returnModel->setting_info();
        echo view('App\Modules\SalesReturn\Views\return_details', $data);
    }
}
