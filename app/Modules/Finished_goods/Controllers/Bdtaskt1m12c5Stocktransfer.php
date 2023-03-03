<?php

namespace App\Modules\Finished_goods\Controllers;

use App\Modules\Finished_goods\Views;
use CodeIgniter\Controller;
use App\Modules\Finished_goods\Models\Bdtaskt1m12StockTransferModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1m12c5Stocktransfer extends BaseController
{
      private $bdtaskt1m12c5_01_stockTransferModel;
      private $bdtaskt1m12c5_02_CmModel;
      /**
       * Constructor.
       */
      public function __construct()
      {
            $this->permission = new Permission();
            $this->bdtaskt1m12c5_01_stockTransferModel = new Bdtaskt1m12StockTransferModel();
            $this->bdtaskt1m12c5_02_CmModel = new Bdtaskt1m1CommonModel();
      }

      /*--------------------------
    | Transfer list
    *--------------------------*/
      public function index()
      {
            $data['title']           = get_phrases(['store', 'transfer', 'list']);
            $data['moduleTitle']     = get_phrases(['finished', 'goods']);
            $data['isDTables']       = true;
            $data['module']          = "Finished_goods";
            $data['page']            = "stock_transfer/list";
            $data['hasPrintAccess']  = $this->permission->method('store_transfer_list', 'print')->access();
            $data['hasExportAccess'] = $this->permission->method('store_transfer_list', 'export')->access();
            return $this->bdtaskt1c1_02_template->layout($data);
      }

      /*--------------------------
    | Get main_stock info
    *--------------------------*/
      public function bdtaskt1m12c5_01_getList()
      {
            $postData = $this->request->getVar();
            $data = $this->bdtaskt1m12c5_01_stockTransferModel->bdtaskt1m12_02_getAll($postData);
            echo json_encode($data);
      }

      /*--------------------------
    | delete main_stock by ID
    *--------------------------*/
      public function bdtaskt1m12c5_02_deleteMainStock($id)
      {
            $data = $this->bdtaskt1m12c5_02_CmModel->bdtaskt1m1_06_Deleted('wh_production_stock', array('id' => $id));
            $MesTitle = get_phrases(['stock', 'record']);
            if (!empty($data)) {
                  $response = array(
                        'success'  => true,
                        'message'  => get_phrases(['deleted', 'successfully']),
                        'title'    => $MesTitle
                  );
            } else {
                  $response = array(
                        'success'  => false,
                        'message'  => (ENVIRONMENT == 'production') ? get_phrases(['something', 'went', 'wrong']) : get_db_error(),
                        'title'    => $MesTitle
                  );
            }
            echo json_encode($response);
      }

      /*--------------------------
    | Add main_stock info
    *--------------------------*/
      public function bdtaskt1m12c5_03_addTransfer()
      {

            $data['title']          = get_phrases(['store', 'transfer', 'form']);
            $data['moduleTitle']    = get_phrases(['finished', 'goods']);
            $data['module']         = "Finished_goods";
            $data['goods_store']    = $this->bdtaskt1m12c5_01_stockTransferModel->finished_good_store();
            $data['goods_list']     = $this->bdtaskt1m12c5_01_stockTransferModel->finished_good_list();
            $data['page']           = "stock_transfer/form";
            return $this->bdtaskt1c1_02_template->layout($data);
      }

      /*--------------------------
    | Get main_stock by ID
    *--------------------------*/
      public function bdtaskt1m12c5_12_transferDetails($id)
      {
            $data['main']           = $this->bdtaskt1m12c5_01_stockTransferModel->main_byid($id);
            $data['details']        = $this->bdtaskt1m12c5_01_stockTransferModel->details_byid($id);
            $data['settings_info']  = $this->bdtaskt1m12c5_01_stockTransferModel->setting_info();
            echo view('App\Modules\Finished_goods\Views\stock_transfer\details', $data);
      }

      /*--------------------------
    | Get stock details by ID
    *--------------------------*/
      public function bdtaskt1m12c5_05_getMainStockDetailsById($id)
      {
            $data = $this->bdtaskt1m12c5_01_stockTransferModel->bdtaskt1m12_03_getMainStockDetailsById($id);
            echo json_encode($data);
      }

      /*--------------------------
    | Get dealerdropdown info
    *--------------------------*/
      public function bdtaskt1c1_06_getItemDropdown()
      {
            $pre_items = $this->request->getVar('pre_items');
            $data      = $this->bdtaskt1m12c5_01_stockTransferModel->item_dropdown($pre_items);
            echo json_encode($data);
      }

      /* --------------------------------------
    | Get store dropdown
     * ---------------------------------*/
      public function bdtaskt1c1_07_getStoreDropdown()
      {
            $from_store = $this->request->getVar('from_store');
            $data       = $this->bdtaskt1m12c5_01_stockTransferModel->StocktransStoreList($from_store);
            echo json_encode($data);
      }

      public function bdtaskt1c1_08_getItemBatchstock()
      {
            $product_id = $this->request->getVar('product_id');
            $store_id   = $this->request->getVar('store_id');
            $data       = $this->bdtaskt1m12c5_01_stockTransferModel->bdtaskt1m12_09_getItemBatches($product_id, $store_id);
            echo json_encode($data);
      }

      public function bdtaskt1c1_09_getBatchstock()
      {
            $product_id = $this->request->getVar('product_id');
            $store_id   = $this->request->getVar('store_id');
            $batch_id   = $this->request->getVar('batch_id');
            $data       = $this->bdtaskt1m12c5_01_stockTransferModel->bdtaskt1m12_09_getBatchstock($product_id, $store_id, $batch_id);
            $result = array('stock' => ($data ? $data->avail_qty : 0), 'bag_size' => ($data ? $data->bag_size : 0));
            echo json_encode($result);
      }

      public function bdtaskt1c1_10_saveStockTransfer()
      {
            $data = array(
                  'transfer_id' => date('Ymdhis'),
                  'from_store'  => $this->request->getVar('from_store'),
                  'date'        => $this->request->getVar('date'),
                  'created_by'  => session('id'),
            );

            if ($this->request->getMethod() == 'post') {
                  $rules = [
                        'from_store'      => ['label' => get_phrases(['from', 'store']), 'rules' => 'required'],
                  ];

                  if (!$this->validate($rules)) {

                        $this->session->setFlashdata('exception', $this->validator->listErrors());
                        return redirect()->back()->withInput();
                  } else {
                        $result  = $this->bdtaskt1m12c5_01_stockTransferModel->bdtaskt1m12_10_getSaveStocktransfer($data);
                        if ($result) {
                              $this->session->setFlashdata('message', 'Successfully Saved');
                              return redirect()->route('finished_goods/store_transfer/add_store_transfer');
                        } else {
                              $this->session->setFlashdata('exception', 'Please Try again');
                              return redirect()->route('finished_goods/store_transfer/add_store_transfer');
                        }
                  }
            }
      }
}
