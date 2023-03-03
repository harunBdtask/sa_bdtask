<?php
// k
namespace App\Modules\Sale\Controllers;

use App\Modules\Sale\Views;
use CodeIgniter\Controller;
use App\Modules\Sale\Models\Bdtaskt1m12DeliveryOrderModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;
use phpDocumentor\Reflection\Types\Null_;

class Bdtaskt1m12c2DeliveryOrder extends BaseController
{
    private $bdtaskt1m12c2_01_dealerOrderModel;
    private $bdtaskt1c1_01_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->db = db_connect();
        $this->bdtaskt1m12c2_01_dealerOrderModel = new Bdtaskt1m12DeliveryOrderModel();
        $this->bdtaskt1c1_01_CmModel  = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | do list
    *--------------------------*/
    public function index()
    {
        $data['moduleTitle']    = get_phrases(['do', 'list']);
        $data['title']          = get_phrases(['sales', 'admin']);
        $data['isDTables']            = true;
        $data['module']               = "Sale";
        $data['page']                 = "invoice/list";
        $data['setting']              = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['hasCreateAccess']      = $this->permission->method('do_list', 'create')->access();
        $data['hasPrintAccess']       = $this->permission->method('do_list', 'print')->access();
        $data['hasExportAccess']      = $this->permission->method('do_list', 'export')->access();
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    public function bdtaskt1m12c1_getDoList()
    {
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c2_01_dealerOrderModel->bdtaskt1m12_06_getSalesAdminDolist($postData);
        echo json_encode($data);
    }



    public function bdtask_sale_001DoForm()
    {
        $this->permission->check_label_method('add_do', 'create')->redirect();
        $person_id                    = session('id');
        $data['title']                = get_phrases(['delivery', 'order']);
        $data['moduleTitle']          = get_phrases(['sale']);
        $data['voucher_no']           = $this->voucher_no_generator();
        $data['dealer_list']          = $this->bdtaskt1m12c2_01_dealerOrderModel->bdtask_001_dealerlist();
        $data['sales_persons']        = $this->bdtaskt1m12c2_01_dealerOrderModel->sales_personList();
        $data['persons_info']         = $this->bdtaskt1m12c2_01_dealerOrderModel->bdtaskt1m12_03_getSalesPersonDetailsById($person_id);
        $data['page']                 = "invoice/form";
        $data['module']               = "Sale";
        return $this->bdtaskt1c1_02_template->layout($data);
    }


    public function bdtask_save_dodata()
    {
        $this->permission->check_label_method('add_do', 'create')->redirect();
        $do_id = date('Ymdhis');
        $postData = array(
            'do_id'         => $do_id,
            'vouhcer_no'    => $this->voucher_no_generator(),
            'do_date'       => $this->request->getVar('date'),
            'dealer_id'     => $this->request->getVar('dealer_id'),
            'grand_total'   => $this->request->getVar('grnadtotal'),
            'total_kg'      => $this->request->getVar('grand_kg'),
            'do_by'         => $this->request->getVar('sales_man'),
            'status'        => 0,
            'created_by'    => session('id'),
        );



        if ($this->request->getMethod() == 'post') {
            $rules = [
                'dealer_id'   => ['label' => get_phrases(['dealer']), 'rules' => 'required'],
                'date'        => ['label' => get_phrases(['date']), 'rules'   => 'required'],
            ];

            if (!$this->validate($rules)) {
                $this->session->setFlashdata('exception', $this->validator->listErrors());
                return redirect()->route('sale/deliver_order/add_do');
            } else {
                $result = $this->bdtaskt1m12c2_01_dealerOrderModel->bdtaskt1m1_05_saveDo($postData);
                // Store log data
                if ($result) {
                    $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['do', 'request']), get_phrases(['created']), $postData['vouhcer_no'], 'do_main', 1);
                    $this->session->setFlashdata('message', get_phrases(['Successfully', 'saved']));
                    return redirect()->route('sale/deliver_order/add_do');
                } else {
                    $this->session->setFlashdata('exception', 'Please Try again');
                    return redirect()->route('sale/deliver_order/add_do');
                }
            }
        }
    }

    public function bdtask_update_dodata()
    {
        $this->permission->check_label_method('add_do', 'create')->redirect();
        $do_id      = $this->request->getVar('do_id');
        $vouhcer_no = $this->request->getVar('voucher_no');
        $postData = array(
            'do_id'         => $do_id,
            'vouhcer_no'    => $vouhcer_no,
            'do_date'       => $this->request->getVar('date'),
            'dealer_id'     => $this->request->getVar('dealer_id'),
            'transport_cost'=> $this->request->getVar('shippingcost'),
            'total_kg'      => $this->request->getVar('grand_kg'),
            'grand_total'   => $this->request->getVar('grnadtotal')
        );



        if ($this->request->getMethod() == 'post') {
            $rules = [
                'dealer_id'   => ['label' => get_phrases(['dealer']), 'rules' => 'required'],
                'date'        => ['label' => get_phrases(['date']), 'rules'   => 'required'],
            ];

            if (!$this->validate($rules)) {
                $response = array(
                    'success'  => false,
                    'message'  => $this->validator->listErrors(),
                );
            } else {
                $result = $this->bdtaskt1m12c2_01_dealerOrderModel->bdtaskt1m1_06_updateDo($postData);
                // Store log data
                if ($result) {
                    $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['dO', 'request']), get_phrases(['updated']), $postData['vouhcer_no'], 'do_main', 2);
                    $response = array(
                        'success'  => true,
                        'message'  => get_phrases(['Successfully', 'updated']),
                    );
                } else {

                    $response = array(
                        'success'  => true,
                        'message'  => get_phrases(['please', 'try', 'again']),
                    );
                }
            }
        }
        echo json_encode($response);
    }

    public function bdtask_002item_search()
    {
        $product_name   = $this->request->getVar('product_name');
        $product_info   = $this->bdtaskt1m12c2_01_dealerOrderModel->autocompletproductdata($product_name);

        if (!empty($product_info)) {
            $json_product[''] = '';
            foreach ($product_info as $value) {
                $json_product[] = array('label' => $value['nameE'], 'value' => $value['id']);
            }
        } else {
            $json_product[] = 'No Product Found';
        }
        echo json_encode($json_product);
    }

    public function bdtask_003item_detailsdata()
    {
        $item_id = $this->request->getVar('product_id');
        $result = $this->bdtaskt1m12c2_01_dealerOrderModel->item_details($item_id);
        echo json_encode($result);
    }

    public function voucher_no_generator()
    {
        $builder = $this->db->table('do_main');
        $builder->select('max(id) as voucher_no');
        $query = $builder->get();
        $data = $query->getRow();
        if (!empty($data->voucher_no)) {
            $invoice_no = $data->voucher_no + 1000;
        } else {
            $invoice_no = 1000;
        }
        return   'DO-' . $invoice_no;
    }

    public function bdtask_004item_stockdata()
    {
        $product_id   = $this->request->getVar('product_id');
        $result = $this->bdtaskt1m12c2_01_dealerOrderModel->item_batchstockdata($product_id);
        echo json_encode($result);
    }

    public function bdtaskt1m12c2_07_getdodealersById($id)
    {

        $data['do_main']          = $this->bdtaskt1m12c2_01_dealerOrderModel->do_main_byid($id);
        $data['do_details']       = $this->bdtaskt1m12c2_01_dealerOrderModel->do_details_byid($id);
        $data['salepersoninfo']   = $this->bdtaskt1m12c2_01_dealerOrderModel->bdtaskt1m12_03_getSalesPersonDetailsById($data['do_main']->do_by);
        $data['settings_info']    = $this->bdtaskt1m12c2_01_dealerOrderModel->setting_info();
        echo view('App\Modules\Sale\Views\invoice\invoice_details', $data);
    }

    public function bdtaskt1m12c2_08_getaccountdodealersById($id)
    {

        $data['do_main']          = $this->bdtaskt1m12c2_01_dealerOrderModel->do_main_byid($id);
        $data['do_details']       = $this->bdtaskt1m12c2_01_dealerOrderModel->do_details_byid($id);
        $data['salepersoninfo']   = $this->bdtaskt1m12c2_01_dealerOrderModel->bdtaskt1m12_03_getSalesPersonDetailsById($data['do_main']->do_by);
        $data['settings_info']    = $this->bdtaskt1m12c2_01_dealerOrderModel->setting_info();
        echo view('App\Modules\Sale\Views\invoice\accounts_do_details', $data);
    }

    public function bdtaskt1m12c_confirm_do()
    {
        $do_id  = $this->request->getVar('do_id');
        $result = $this->bdtaskt1m12c2_01_dealerOrderModel->confirm_delivery_order($do_id);

        if ($result == 'true') {
            $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['dO']), get_phrases(['added']), $do_id, 'do_main', 2);
            $data['status']  = 1;
            $data['message'] = 'Do Request Added to Do Successfully';
        } else {
            $data['status']   = 0;
            $data['message']  = 'Please Try Again';
        }

        echo json_encode($data);
    }


    public function bdtaskt1m13c_do_edit()
    {
        $do_id                    = $this->request->getVar('do_id');
        $dodata['dealer_list']    = $this->bdtaskt1m12c2_01_dealerOrderModel->bdtask_001_dealerlist();
        $dodata['do_main']        = $this->bdtaskt1m12c2_01_dealerOrderModel->do_main_byid($do_id);
        $dodata['salepersoninfo'] = $this->bdtaskt1m12c2_01_dealerOrderModel->bdtaskt1m12_03_getSalesPersonDetailsById($dodata['do_main']->do_by);
        $dodata['do_details']     = $this->bdtaskt1m12c2_01_dealerOrderModel->do_details_byid($do_id);
        echo view('App\Modules\Sale\Views\invoice\edit_do', $dodata);
    }

    public function bdtaskt1m13c_do_delivery()
    {
        $do_id                 = $this->request->getVar('do_id');
        $dodata['dealer_list'] = $this->bdtaskt1m12c2_01_dealerOrderModel->bdtask_001_dealerlist();
        $dodata['do_main']     = $this->bdtaskt1m12c2_01_dealerOrderModel->do_main_byid($do_id);
        $dodata['store_list']  = $this->bdtaskt1m12c2_01_dealerOrderModel->finish_goods_store();
        $dodata['do_details']  = $this->bdtaskt1m12c2_01_dealerOrderModel->do_details_byid($do_id);
        echo view('App\Modules\Sale\Views\invoice\do_delivery_form', $dodata);
    }


    public function account_do_list()
    {
        $data['moduleTitle']    = get_phrases(['do', 'list']);
        $data['title']          = get_phrases(['accounts']);
        $data['isDTables']            = true;
        $data['module']               = "Sale";
        $data['page']                 = "invoice/account_dolist";
        $data['setting']              = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['hasCreateAccess']      = 1; //$this->permission->method('dealer_info', 'create')->access();
        $data['hasPrintAccess']       = 1; //$this->permission->method('dealer_info', 'print')->access();
        $data['hasExportAccess']      = 1; //$this->permission->method('dealer_info', 'export')->access();
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    public function bdtaskt1m12c1_getAccountDoList()
    {
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c2_01_dealerOrderModel->bdtaskt1m12_06_getAccountDolist($postData);
        echo json_encode($data);
    }

    public function bdtaskt1m12c_do_accounts_approval()
    {
        $do_id      = $this->request->getVar('do_id');
        $do_info    = $this->bdtaskt1m12c2_01_dealerOrderModel->do_main_byid($do_id);
        $pay_status = ($do_info ? $do_info->payment_status : 0);

        if ($pay_status == 2) {

            $result = $this->bdtaskt1m12c2_01_dealerOrderModel->accountant_confirm_delivery_order($do_id);

            if ($result == 'true') {
                $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['dO']), get_phrases(['accounts', 'approved']), $do_id, 'do_main', 2);
                $data['status']  = 1;
                $data['message'] = 'Approved Successfully';
            } else {
                $data['status']  = 0;
                $data['message'] = 'Please Try Again';
            }
        } else {
               $data['status']  = 0;
               $data['message'] = 'Did not paid Yet';
        }

        echo json_encode($data);
    }

    public function bdtaskt1m13c_accounts_do_paid()
    {
        $do_id            = $this->request->getVar('do_id');
        $attachment       = '';
        helper(['form', 'url']);
        if (!empty($this->request->getFile('attachment'))) {
            $attachment = $this->bdtaskt1c1_01_fileUpload->doUpload('./assets/dist/img/receive_invoice/', $this->request->getFile('attachment'));
        }
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'payment_method'   => ['label' => get_phrases(['payment', 'method']), 'rules' => 'required'],
                'paid_amount'      => ['label' => get_phrases(['paid', 'amount']), 'rules'    => 'required'],
            ];
            if (!$this->validate($rules)) {
                $this->session->setFlashdata('exception', $this->validator->listErrors());
                return redirect()->route('sale/deliver_order/account_do_list');
            } else {
                // $result   = $this->bdtaskt1m12c2_01_dealerOrderModel->accountant_confirm_do_paid($do_id, $attachment);
                $result   = $this->do_payments($do_id, $attachment);
                
                if ($result == 1) {
                    $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['dO']), get_phrases(['paid']), $do_id, 'do_main', 2);
                    $this->session->setFlashdata('message', get_phrases(['Successfully', 'saved']));
                    return redirect()->route('sale/deliver_order/account_do_list');
                } else {
                    $this->session->setFlashdata('exception', get_phrases(['please', 'try', 'again']));
                    return redirect()->route('sale/deliver_order/account_do_list');
                }
            }
        }
    }
    
    public function do_payments($do_id,$attachment)
    {
        $payment_type      = $this->request->getVar('payment_method');
        $payment_type      = ($payment_type == 1 ? $this->request->getVar('bank_id') : $payment_type);
        $paid_amount       = $this->request->getVar('paid_amount');
        $grand_total       = $this->request->getVar('grand_total');
        $due_amount        = $this->request->getVar('due_amount');
        $date              = $this->request->getVar('date');
        $do_no             = $this->request->getVar('challan_no');
        $dealer_id         = $this->request->getVar('dealer_id');
        $due_paymentdate   = $this->request->getVar('due_payment_date');
        $do_payments_data = array(
            'do_id'          => $do_id,
            'payment_type'   => $payment_type,
            'do_amount'      => $grand_total,
            'payment_amount' => $paid_amount,
            // 'paid_by' => null,
            'created_by'     => session('id'),
            'created_date'   => date('Y-m-d H:i:s'),
        );
        $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_01_Insert('do_payments', $do_payments_data);
        $confirm_data = array(
            'payment_status' => 2,
            'paid_by'        => session('id')
        );
        $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_02_UpdateSet('do_main', 'paid_amount', $paid_amount, array('do_id' => $do_id));
        $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_02_Update('do_main', $confirm_data, array('do_id' => $do_id));
        $payment_record = array(
            'date'             => ($date ? $date : ''),
            'do_no'            => $do_no,
            'dealer_id'        => $dealer_id,
            'paid_amount'      => ($paid_amount ? $paid_amount : 0),
            'due_amount'       => ($due_amount ? $due_amount : 0),
            'due_payment_date' => ($due_amount > 0 ? $due_paymentdate : NULL),
            'attachment'       => $attachment,
            'create_by'        => session('id'),
            'status'           => ($due_amount > 0 ? 2 : 1),
        );

        
        $sub_type      = 6;
        $pedefine_head = $this->bdtaskt1m12c2_01_dealerOrderModel->getcoaPredefineHead();
        $subcodes      = $this->bdtaskt1m12c2_01_dealerOrderModel->getReferSubcode($sub_type,$dealer_id);
        $vno           = 'CV';
        $getVouchern   = $this->bdtaskt1m12c2_01_dealerOrderModel->bdtaskt1m8_07_getMaxvoucherno($vno);
        $fisyearid     = $this->bdtaskt1m12c2_01_dealerOrderModel->getActiveFiscalyear();
        $voucherdata = array(
            'fyear'           => $fisyearid,
            'Vtype'           => $vno,
            'VNo'             => $getVouchern,
            'VDate'           => ($date ? $date : ''),
            'COAID'           => ($payment_type == 1 ? $this->request->getVar('bank_id') : $pedefine_head->cashCode),
            'Debit'           => ($paid_amount ? $paid_amount : 0),
            'Credit'          => 0,
            'RevCodde'        => $pedefine_head->dealerCode,
            'subType'         => $sub_type,
            'subCode'         => $subcodes,
            'ledgerComment'   => 'DO Payment  For '.$do_no,
            'Narration'       => 'From DO Payement ('.$do_no.')',
            'isApproved'      => 0,
            'CreateBy'        => session('id'),
            'CreateDate'      => date('Y-m-d H:i:s'),
        );
        $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_01_Insert('acc_vaucher', $voucherdata);
        $this->bdtaskt1m12c2_01_dealerOrderModel->bdtaskt1m8_09_approveVoucher($getVouchern);
        $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_01_Insert('dealer_due_info', $payment_record);
        return 1;
    }

    public function factory_manager_do_list()
    {
        $data['moduleTitle']    = get_phrases(['do', 'list']);
        $data['title']          = get_phrases(['factory', 'manager']);
        $data['isDTables']            = true;
        $data['module']               = "Sale";
        $data['page']                 = "invoice/factory_manager_dolist";
        $data['setting']    = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['hasCreateAccess']      = 1; //$this->permission->method('dealer_info', 'create')->access();
        $data['hasPrintAccess']       = 1; //$this->permission->method('dealer_info', 'print')->access();
        $data['hasExportAccess']      = 1; //$this->permission->method('dealer_info', 'export')->access();
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    public function bdtaskt1m12c2_09_getdodealersFactorySection($id)
    {

        $data['do_main']       = $this->bdtaskt1m12c2_01_dealerOrderModel->do_main_byid($id);
        $data['do_details']    = $this->bdtaskt1m12c2_01_dealerOrderModel->do_details_byid($id);
        $data['settings_info'] = $this->bdtaskt1m12c2_01_dealerOrderModel->setting_info();
        echo view('App\Modules\Sale\Views\invoice\invoice_details_factory_section', $data);
    }

    public function bdtaskt1m12c2_09_getdodealersdeliverySection($id)
    {

        $data['do_main']       = $this->bdtaskt1m12c2_01_dealerOrderModel->do_deliveryinfo_byid($id);
        $data['do_details']    = $this->bdtaskt1m12c2_01_dealerOrderModel->do_delivery_details_byid($id);
        $data['settings_info'] = $this->bdtaskt1m12c2_01_dealerOrderModel->setting_info();
        echo view('App\Modules\Sale\Views\invoice\invoice_details_factory_section', $data);
    }

    public function bdtaskt1m12c1_getFactoryManagerDoList()
    {
        $postData = $this->request->getVar();
        $data     = $this->bdtaskt1m12c2_01_dealerOrderModel->bdtaskt1m12_07_getFactoryManagerDolist($postData);
        echo json_encode($data);
    }

    public function bdtaskt1m13c_do_FactoryManager_approval()
    {
        $do_id           = $this->request->getVar('do_id');
        $result          = $this->bdtaskt1m12c2_01_dealerOrderModel->factoryManager_confirm_delivery_order($do_id);
        if ($result == 'true') {
            $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['dO']), get_phrases(['factory', 'manager', 'approved']), $do_id, 'do_main', 2);
            $data['status']  = 1;
            $data['message'] = 'Successfully Approved';
        } else {
            $data['status']  = 0;
            $data['message'] = 'Please Try Again';
        }

        echo json_encode($data);
    }

    public function deliverySection_do_list()
    {
        $data['moduleTitle']    = get_phrases(['do', 'list']);
        $data['title']          = get_phrases(['delivery', 'section']);
        $data['isDTables']            = true;
        $data['module']               = "Sale";
        $data['page']                 = "invoice/delivery_section_dolist";
        $data['setting']              = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['hasCreateAccess']      = 1; //$this->permission->method('dealer_info', 'create')->access();
        $data['hasPrintAccess']       = 1; //$this->permission->method('dealer_info', 'print')->access();
        $data['hasExportAccess']      = 1; //$this->permission->method('dealer_info', 'export')->access();
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    public function bdtaskt1m12c1_getDeliverySectionDoList()
    {
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c2_01_dealerOrderModel->bdtaskt1m12_08_getDeliverySectionDolist($postData);
        echo json_encode($data);
    }

    public function bdtaskt1m14c_do_DeliverySection_approval()
    {
        $do_id                        = $this->request->getVar('do_id');
        $result                       = $this->bdtaskt1m12c2_01_dealerOrderModel->deliverySection_confirm_delivery_order($do_id);
        $printdata['do_main']         = $this->bdtaskt1m12c2_01_dealerOrderModel->do_main_byid($do_id);
        $printdata['do_details']      = $this->bdtaskt1m12c2_01_dealerOrderModel->do_details_byid($do_id);
        $printdata['settings_info']   = $this->bdtaskt1m12c2_01_dealerOrderModel->setting_info();
        if ($result == 'true') {
        $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['dO']), get_phrases(['delivery', 'section', 'approved']), $do_id, 'do_main', 2);
        $data['status']               = 1;
        $data['message']              = 'Approved Successfully';
        $data['details']              = view('App\Modules\Sale\Views\invoice\challan_print', $printdata);
        } else {
        $data['status']               = 0;
        $data['message']              = 'Please Try Again';
        }

        echo json_encode($data);
    }

    public function storeSection_do_list()
    {
        $data['moduleTitle']    = get_phrases(['do', 'list']);
        $data['title']          = get_phrases(['store', 'section']);
        $data['isDTables']            = true;
        $data['module']               = "Sale";
        $data['page']                 = "invoice/store_section_dolist";
        $data['setting']              = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['hasCreateAccess']      = 1; //$this->permission->method('dealer_info', 'create')->access();
        $data['hasPrintAccess']       = 1; //$this->permission->method('dealer_info', 'print')->access();
        $data['hasExportAccess']      = 1; //$this->permission->method('dealer_info', 'export')->access();
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    public function bdtaskt1m12c1_getStoreSectionDoList()
    {
        $postData = $this->request->getVar();
        $data    = $this->bdtaskt1m12c2_01_dealerOrderModel->bdtaskt1m12_09_getStoreSectionDolist($postData);
        echo json_encode($data);
    }


    public function bdtaskt1m15c_do_StoreSection_approval()
    {
        $do_id   = $this->request->getVar('do_id');
        $result  = $this->bdtaskt1m12c2_01_dealerOrderModel->storeSection_confirm_delivery_order($do_id);

        if ($result == 'true') {
            $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['dO']), get_phrases(['store', 'section', 'approved']), $do_id, 'do_main', 2);
            $data['status']  = 1;
            $data['message'] = 'Successfully Approved';
        } else {
            $data['status']  = 0;
            $data['message'] = 'Please Try Again';
        }

        echo json_encode($data);
    }

    public function bdtaskt1m15c_dealer_payment($id)
    {
        $data['do_main']         = $this->bdtaskt1m12c2_01_dealerOrderModel->do_main_byid($id);
        $data['do_details']      = $this->bdtaskt1m12c2_01_dealerOrderModel->do_details_byid($id);
        $data['dealer_coa']      = $this->bdtaskt1m12c2_01_dealerOrderModel->dealer_coa_info($data['do_main']->dealer_id);
        $data['predhead']        = $this->bdtaskt1m12c2_01_dealerOrderModel->getcoaPredefineHead();
        $data['bank_list']       = $this->bdtaskt1m12c2_01_dealerOrderModel->bank_heads();
        $data['payment_methods'] = $this->bdtaskt1m12c2_01_dealerOrderModel->payment_methods();
        echo view('App\Modules\Sale\Views\invoice\dealer_payment', $data);
    }


    /*--------------------------
    | Get dealerdropdown info
    *--------------------------*/
    public function bdtaskt1c1_06_getItemDropdown()
    {
        $column = ['id', 'nameE as text'];
        $where  = array('status' => 1);
        $data   = $this->bdtaskt1m12c2_01_dealerOrderModel->item_dropdown();
        echo json_encode($data);
    }

    /*****************************************
    | Save DO Delivery Partial or Full
     *****************************************/
    public function bdtaskt1m13c_save_deliver()
    {
        $do_id      = $this->request->getVar('do_id');
        $challan    = $this->bdtaskt1m12c2_01_dealerOrderModel->voucher_no_generator($do_id);
        $main       = $this->bdtaskt1m12c2_01_dealerOrderModel->do_main_byid($do_id);
        $deliver_id = date('Ymdhis');
        $postData = array(
            'delivery_id'          => $deliver_id,
            'do_id'                => $do_id,
            'vouhcer_no'           => ($main ? $main->vouhcer_no : ''),
            'do_date'              => $this->request->getVar('date'),
            'dealer_id'            => ($main ? $main->dealer_id : ''),
            'do_by'                => ($main ? $main->do_by : ''),
            'status'               => ($main ? $main->status : ''),
            'challan_no'           => $challan,
            'sls_admin_signature'  => ($main ? $main->sls_admin_signature : ''),
            'accounts_approve'     => ($main ? $main->accounts_approve : ''),
            'ac_approved_by'       => ($main ? $main->ac_approved_by : ''),
            'accountant_sig'       => ($main ? $main->accountant_sig : ''),
            'dl_s_approved'        => 1,
            'dl_s_approved_by'     => session('id'),
            'dl_s_sig'             => session('signature'),
            'created_by'           => session('id'),
        );



        if ($this->request->getMethod() == 'post') {
            $rules = [
                'date'        => ['label' => get_phrases(['date']), 'rules'   => 'required'],
            ];

            if (!$this->validate($rules)) {
                $response = array(
                    'success'  => false,
                    'message'  => $this->validator->listErrors(),
                );
            } else {
                $result = $this->bdtaskt1m12c2_01_dealerOrderModel->bdtaskt1m1_05_saveDelivery($postData);
                // Store log data
                if ($result) {
                    $printdata['do_main']       = $this->bdtaskt1m12c2_01_dealerOrderModel->delivery_main_byid($do_id);
                    $printdata['do_details']    = $this->bdtaskt1m12c2_01_dealerOrderModel->delivery_details_byid($do_id);
                    $printdata['settings_info'] = $this->bdtaskt1m12c2_01_dealerOrderModel->setting_info();
                    $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['delivery', 'request']), get_phrases(['created']), $postData['vouhcer_no'], 'do_delivery', 1);
                    $response = array(
                        'success'  => true,
                        'message'  => get_phrases(['Successfully', 'saved']),
                        'details'  => view('App\Modules\Sale\Views\invoice\challan_print', $printdata),
                    );
                } else {
                    $this->session->setFlashdata('exception', 'Please Try again');
                    $response = array(
                        'success'  => true,
                        'message'  => get_phrases(['please', 'try', 'again']),
                    );
                }
            }

            echo json_encode($response);
        }
    }

    /******************************************************************
    | Generate Gate Pass From  Factory Manager Approved Delivery
     ******************************************************************/
    public function bdtaskt1m15c_do_gate_pass()
    {
        $data['moduleTitle']    = get_phrases(['gate', 'Pass']);
        $data['title']          = get_phrases(['gate', 'pass', 'section']);
        $data['isDTables']            = true;
        $data['module']               = "Sale";
        $data['page']                 = "invoice/gatepass_dolist";
        $data['setting']              = $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['hasCreateAccess']      = 1; //$this->permission->method('dealer_info', 'create')->access();
        $data['hasPrintAccess']       = 1; //$this->permission->method('dealer_info', 'print')->access();
        $data['hasExportAccess']      = 1; //$this->permission->method('dealer_info', 'export')->access();
        return $this->bdtaskt1c1_02_template->layout($data);
    }

    public function bdtaskt1m12c1_getGatePassList()
    {
        $postData = $this->request->getVar();
        $data     = $this->bdtaskt1m12c2_01_dealerOrderModel->bdtaskt1m12_07_gatePassList($postData);
        echo json_encode($data);
    }

    /******************************************************************
    | Generate Gate Pass Form
     ******************************************************************/
    public function bdtaskt1m12c1_gatepass_form()
    {
        $id                            = $this->request->getVar('delivery_id');
        $data['delivery_main']         = $this->bdtaskt1m12c2_01_dealerOrderModel->do_deliveryinfo_byid($id);
        $data['delivery_details']      = $this->bdtaskt1m12c2_01_dealerOrderModel->do_delivery_details_byid($id);
        $data['scaler']                = $this->bdtaskt1m12c2_01_dealerOrderModel->scalerInfo($id);
        $data['store_list']            = $this->bdtaskt1m12c2_01_dealerOrderModel->finish_goods_store();
        echo view('App\Modules\Sale\Views\invoice\gate_passform', $data);
    }

    /******************************************************************
    | Save Gate Pass 
     ******************************************************************/
    public function bdtaskt1m12c1_save_gatepass()
    {
        $store_id               = $this->request->getVar('store_id');
        $do_no                  = $this->request->getVar('do_no');
        $challan_no             = $this->request->getVar('challan_no');
        $driver_name            = $this->request->getVar('driver_name');
        $driver_mobile          = $this->request->getVar('driver_mobile');
        $truck_no               = $this->request->getVar('truck_no');
        $truck_weight           = $this->request->getVar('truck_weight');
        $truck_weight_withitem  = $this->request->getVar('truck_weight_withitem');

        $data = array(
            'driver_name'             => $driver_name,
            'truck_no'                => $truck_no,
            'store_id'                => $store_id,
            'do_no'                   => $do_no,
            'challan_no'              => $challan_no,
            'driver_mobile_no'        => $driver_mobile,
            'truck_weight'            => $truck_weight,
            'truck_weight_with_items' => $truck_weight_withitem,
            'created_by'              => session('id'),
        );
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'driver_name'           => ['label' => get_phrases(['driver', 'name']), 'rules'   => 'required'],
                'store_id'              => ['label' => get_phrases(['store', 'name']), 'rules'    => 'required'],
                'date'                  => ['label' => get_phrases(['date']), 'rules'             => 'required'],
                'do_no'                 => ['label' => get_phrases(['do', 'no']), 'rules'         => 'required'],
                'driver_mobile'         => ['label' => get_phrases(['driver', 'mobile']), 'rules' => 'required'],
                'truck_no'              => ['label' => get_phrases(['truck', 'no']), 'rules'      => 'required'],
                'truck_weight'          => ['label' => get_phrases(['truck', 'weight']), 'rules'  => 'required'],
                'truck_weight_withitem' => ['label' => get_phrases(['truck', 'weight','withitem']), 'rules'   => 'required'],

            ];

            if (!$this->validate($rules)) {
                $this->session->setFlashdata('exception', $this->validator->listErrors());
                return redirect()->route('sale/deliver_order/gate_pass');
            } else {
                $result = $this->bdtaskt1m12c2_01_dealerOrderModel->save_gatepass($data);
                if ($result == 1) {
                    $this->session->setFlashdata('message', get_phrases(['Successfully', 'saved']));
                    return redirect()->route('sale/deliver_order/gate_pass');
                } elseif ($result == 2) {
                    $this->session->setFlashdata('message', get_phrases(['Successfully', 'updated']));
                    return redirect()->route('sale/deliver_order/gate_pass');
                } else {
                    $this->session->setFlashdata('exception', 'Please Try Again');
                    return redirect()->route('sale/deliver_order/gate_pass');
                }
            }
        }
    }

    /******************************************************************
    | Gate Pass Approved
     ******************************************************************/
    public function bdtaskt1m12c1_approve_gatepass()
    {
        $challan  = $this->request->getVar('challan');
        $result = $this->bdtaskt1m12c2_01_dealerOrderModel->gatepass_approve($challan);
        if ($result == 'true') {
        $this->bdtaskt1c1_01_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['sale']), get_phrases(['gatepass', 'approved']), $challan, 'scaler', 2);
        $data['status']  = 1;
        $data['message'] = 'Approved Successfully';
        } else {
        $data['status']  = 0;
        $data['message'] = 'Please Try Again';
        }
        echo json_encode($data);
    }
    /******************************************************************
    | Gate Pass Details
     ******************************************************************/
    public function bdtaskt1m12c2_09_getGatepassinfo($id)
    {
        $data['delivery_main']         = $this->bdtaskt1m12c2_01_dealerOrderModel->do_deliveryinfo_byid($id);
        $data['delivery_details']      = $this->bdtaskt1m12c2_01_dealerOrderModel->do_delivery_details_byid($id);
        $data['scaler']                = $this->bdtaskt1m12c2_01_dealerOrderModel->scalerInfo($id);
        $data['settings_info']         = $this->bdtaskt1m12c2_01_dealerOrderModel->setting_info();
        $data['store_list']            = $this->bdtaskt1m12c2_01_dealerOrderModel->finish_goods_store();
        echo view('App\Modules\Sale\Views\invoice\gate_passview', $data);
    }

    public function bdtaskt1m12c2_09_getSalespersonDetails($id)
    {
       $data = $this->bdtaskt1m12c2_01_dealerOrderModel->bdtaskt1m12_03_getSalesPersonDetailsById($id);
        $result = array('persons' => $data);
        echo json_encode($result);
    }

    public function get_sales_man()
    {
        $dealer_id = $this->request->getVar('dealer_id');
        $data = $this->bdtaskt1m12c2_01_dealerOrderModel->get_sales_man($dealer_id);
        echo json_encode($data);
    }
}
