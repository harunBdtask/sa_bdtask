<?php

namespace App\Modules\FixedAssets\Controllers;

use CodeIgniter\Controller;
use App\Libraries\Permission;

use App\Modules\FixedAssets\Models\Fixedasset_model;
use App\Models\Bdtaskt1m1CommonModel;

class Fixedasset extends BaseController
{
  #------------------------------------    
  # Author: Bdtask Ltd
  # Author link: https://www.bdtask.com/
  # Dynamic style php file
  # Developed by :Isahaq
  #------------------------------------    
  private $fixedasset_model;
  private $bdtaskt1m12c14_02_CmModel;
  /**
   * Constructor.
   */
  public function __construct()
  {
    $this->permission = new Permission();
    $this->fixedasset_model = new Fixedasset_model();
    $this->bdtaskt1m12c14_02_CmModel = new Bdtaskt1m1CommonModel();
  }
  public function index()
  {
    //$this->permission->check_label_method('fixedasset_list','read')->redirect(); 
    $data['title']       = 'Fixed Asset List';
    $data['moduleTitle'] = get_phrases(['Fixed', 'assets']);
    $data['module']      = "FixedAssets";
    $data['assets']      = $this->fixedasset_model->findAll_assets();
    $data['page']        = "fixedasset/fixedasset_list";
    return $this->bdtaskt1c1_02_template->layout($data);
  }



  public function bdtask_0001_fixedasset_form($id = null)
  {
    $data = [];
    $data['assets'] = (object)$userLevelData = array(
      'HeadCode'      => ($this->request->getVar('id') ? $this->request->getVar('id') : null),
      'HeadName'      => $this->request->getVar('asset_name', FILTER_SANITIZE_STRING),
      'PHeadName'     => $this->request->getVar('parent_head', FILTER_SANITIZE_STRING),

    );
    if ($this->request->getMethod() == 'post') {

      $rules = [
        'asset_name' => ['label' => get_phrases(['asset', 'name']), 'rules' => 'required'],


      ];


      if (!$this->validate($rules)) {
        $data['validation'] = $this->validator;
      } else {
        if (empty($id)) {
          //$this->permission->check_label_method('add_fixedasset','create')->redirect(); 

          $isExist = $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_03_getRow('acc_coa', array('HeadName' => $this->request->getVar('asset_name')));
          if (empty($isExist)) {

            if ($this->fixedasset_model->save_assets($userLevelData)) {
              $this->bdtaskt1m12c14_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['fixed', 'assets']), get_phrases(['create']), $userLevelData['HeadName'], 'acc_coa', 2);
              $this->session->setFlashdata('message', get_phrases(['save', 'successfully']));
              return  redirect()->to(base_url('/fixedasset/fixedasset_list/'));
            } else {
              $this->session->setFlashdata('exception', get_phrases(['please', 'try', 'again']));
              return  redirect()->to(base_url('/fixedasset/fixedasset_list/'));
            }
          } else {
            $this->session->setFlashdata('exception', 'Already Added');
            return  redirect()->to(base_url('/fixedasset/add_fixedasset/'));
          }
        } else {
          $this->permission->check_label_method('fixedasset_list', 'update')->redirect();
          $this->fixedasset_model->update_assets($userLevelData);
          $accesslog = array(
            'action_page' => 'fixed assets',
            'action_done' => 'edit',
            'remarks'     => 'assets  ' . $userLevelData['HeadName'],
            'user_name'   => $this->session->get('fullname'),
            'entry_date'  =>  date('Y-m-d H:i:s')
          );

          $acclog = $this->db->table('accesslog');
          $acclog->insert($accesslog);
          $this->session->setFlashdata('message', get_phrases(['successfully', 'updated']));

          return  redirect()->to(base_url('/fixedasset/fixedasset_list/'));
        }
      }
    }

    $data['module']           = "FixedAssets";
    if (!empty($id)) {
      $data['assets']         = $this->fixedasset_model->singledata($id);
    }
    $data['title']            = 'Add Assets';
    $data['asset_list']       = $this->fixedasset_model->assets_drowdown();
    $data['moduleTitle']      = get_phrases(['Fixed', 'assets']);
    $data['page']             = "fixedasset/asset_form";
    return $this->bdtaskt1c1_02_template->layout($data);
  }

  public function delete_fixedasset($id = null)
  {
    $this->permission->check_label_method('fixedasset_list', 'delete')->redirect();
    if ($this->fixedasset_model->delete_assets($id)) {
      $accesslog = array(
        'action_page' => 'fixedasset',
        'action_done' => 'delete',
        'remarks'     => 'id ' . $id,
        'user_name'   => $this->session->get('fullname'),
        'entry_date'  =>  date('Y-m-d H:i:s')
      );

      $acclog = $this->db->table('accesslog');
      $acclog->insert($accesslog);
      $this->session->setFlashdata('message', get_phrases(['successfully', 'deleted']));
    } else {
      $this->session->setFlashdata('exception', get_phrases(['please', 'try', 'again']));
    }

    return redirect()->route('fixedasset/fixedasset_list');
  }


  public function bdtask_0001_assets_purchase()
  {
    //$this->permission->check_label_method('assets_purchase','create')->redirect();     
    $data = [];
    $data['assets'] = (object)$userLevelData = array(
      'asset_code'    => ($this->request->getVar('asset_name') ? $this->request->getVar('asset_name') : null),
      'date'          => $this->request->getVar('dtpDate', FILTER_SANITIZE_STRING),
      'amount'        => $this->request->getVar('amount', FILTER_SANITIZE_STRING),
      'payment_type'  => $this->request->getVar('paytype', FILTER_SANITIZE_STRING),
      'bank_id'       => $this->request->getVar('bank_id', FILTER_SANITIZE_STRING),
      'supplier_id'   => $this->request->getVar('supplier_id', FILTER_SANITIZE_STRING),
      'paid_amount'   => $this->request->getVar('paid_amount', FILTER_SANITIZE_STRING),
      'create_by'     => $this->session->get('fullname'),
    );
    if ($this->request->getMethod() == 'post') {

      $rules = [
        'asset_name'      => ['label' => get_phrases(['asset', 'name']), 'rules'   => 'required'],
        'dtpDate'         => ['label' => get_phrases(['date']), 'rules'           => 'required'],
        'amount'          => ['label' => get_phrases(['amount']), 'rules'         => 'required'],
        'paytype'         => ['label' => get_phrases(['payment', 'type']), 'rules' => 'required'],
        'supplier_id'     => ['label' => get_phrases(['supplier']), 'rules'       => 'required'],
      ];


      if (!$this->validate($rules)) {
        $data['validation'] = $this->validator;
      } else {
        $payment_type = $this->request->getVar('paytype', FILTER_SANITIZE_STRING);
        $bank_id      = $this->request->getVar('bank_id', FILTER_SANITIZE_STRING);
        if ($payment_type == 2 && empty($bank_id)) {
          $this->session->setFlashdata('exception', 'You Have Selected Bank Payment But did not Select Bank');
          return  redirect()->to(base_url('/fixedasset/assets_purchase/'));
          exit;
        }
        if ($this->fixedasset_model->save_purchase($userLevelData)) {
          $accesslog = array(
            'action_page' => 'fixed assets purchase',
            'action_done' => 'create',
            'remarks'     => 'purchase ' . $userLevelData['asset_code'],
            'user_name'   => $this->session->get('fullname'),
            'entry_date'  => date('Y-m-d H:i:s')
          );

          $acclog = $this->db->table('accesslog');
          $acclog->insert($accesslog);
          $this->session->setFlashdata('message', get_phrases(['save', 'successfully']));
          return  redirect()->to(base_url('/fixedasset/fixedasset_list/'));
        } else {
          $this->session->setFlashdata('exception', get_phrases(['please', 'try', 'again']));
          return  redirect()->to(base_url('/fixedasset/fixedasset_list/'));
        }
      }
    }

    $data['module']       = "FixedAssets";
    $data['asset_list']   = $this->fixedasset_model->coa_info();
    $data['bank_list']    = $this->fixedasset_model->bank_list();
    $data['supplier_list']= $this->fixedasset_model->supplier_list();
    $data['moduleTitle']  = get_phrases(['Fixed', 'assets']);
    $data['title']        = 'Add purchase';
    $data['page']         = "fixedasset/purchase_form";
    return $this->bdtaskt1c1_02_template->layout($data);
  }

  public function fixed_asset_purchase_list()
  {
    $this->permission->check_label_method('assets_purchase_list', 'read')->redirect();
    $data['title']           = 'Fixed Asset Purchase List';
    $data['module']          = "FixedAssets";
    $data['moduleTitle']     = get_phrases(['Fixed', 'assets']);
    $data['purchase_list']   = $this->fixedasset_model->purchase_list();
    $data['page']            = "fixedasset/fixedasset_purchase_list";
    return $this->bdtaskt1c1_02_template->layout($data);
  }
}
