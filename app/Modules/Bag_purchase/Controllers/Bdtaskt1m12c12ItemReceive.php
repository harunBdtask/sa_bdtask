<?php

namespace App\Modules\Bag_purchase\Controllers;

use App\Modules\Bag_purchase\Views;
use CodeIgniter\Controller;
use App\Modules\Bag_purchase\Models\Bdtaskt1m12ItemReceiveModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1m12c12ItemReceive extends BaseController
{
    private $bdtaskt1m12c12_01_item_receiveModel;
    private $bdtaskt1m12c12_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1m12c12_01_item_receiveModel = new Bdtaskt1m12ItemReceiveModel();
        $this->bdtaskt1m12c12_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['moduleTitle'] = get_phrases(['bag', 'purchase']);
        $data['title']      = get_phrases(['item', 'receive']);
        $data['isDTables']  = true;
        $data['module']     = "Bag_purchase";
        $data['page']       = "item_receive/list";
        $data['setting']    = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('setting');

        $data['hasCreateAccess']        = $this->permission->method('wh_bag_receive', 'create')->access();
        $data['hasPrintAccess']         = $this->permission->method('wh_bag_receive', 'print')->access();
        $data['hasExportAccess']        = $this->permission->method('wh_bag_receive', 'export')->access();

        $data['store_list']             = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_store', array('status' => 1));
        $data['supplier_list']          = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_supplier_information', array('status' => 1));
        $data['item_list']              = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag', array('status' => 1));
        $data['all_po']                 = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_purchase', array('status' => 1));
        $data['all_spr']                = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_requisition', array('status' => 1));
        $data['payment_method_list']    = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_05_getResultWhere('list_data', array('list_id' => 16, 'status' => 1));

        $data['vat']        = get_setting('default_vat');
        $store_list         = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_store', array('status' => 1));
        $data['store_id']   = ($store_list) ? $store_list->id : 0;

        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get item_receive info
    *--------------------------*/
    public function bdtaskt1m12c12_01_getList()
    {
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c12_01_item_receiveModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete item_receive by ID
    *--------------------------*/
    public function bdtaskt1m12c12_02_deleteItemReceive($id)
    {
        $data = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_06_Deleted('wh_bag_requisition', array('id' => $id));
        $data = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_06_Deleted('wh_bag_requisition_details', array('purchase_id' => $id));

        $MesTitle = get_phrases(['receive', 'record']);
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

    public function date_db_format($date)
    {
        if ($date == '') {
            return $date;
        }
        return implode("-", array_reverse(explode("/", $date)));
    }

    /*--------------------------
    | Add item_purchase info
    *--------------------------*/
    public function bdtaskt1m12c12_03_addItemPurchase()
    {

        $action = $this->request->getVar('action');
        $data = array(
            'store_id'      => $this->request->getVar('store_id'),
            'supplier_id'   => $this->request->getVar('supplier_id'),
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
        if (!$this->validate($rules)) {
            $response = array(
                'success'  => false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        } else {
            if ($action == 'add') {
                $isExist = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_requisition', array('voucher_no' => $this->request->getVar('voucher_no')));

                if (!empty($isExist)) {
                    $response = array(
                        'success'  => 'exist',
                        'message'  => get_phrases(['already', 'exists']),
                        'title'    => $MesTitle
                    );
                } else {
                    $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('wh_bag_requisition', $data);

                    $item_id    = $this->request->getVar('item_id');
                    $qty        = $this->request->getVar('qty');
                    $price      = $this->request->getVar('price');
                    $total      = $this->request->getVar('total');

                    $data2 = array();
                    foreach ($item_id as $key => $item) {
                        $data2[] = array('purchase_id' => $result, 'item_id' => $item, 'qty' => $qty[$key], 'price' => $price[$key], 'total' => $total[$key]);
                    }

                    $result2 = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_bag_requisition_details', $data2);

                    if ($result) {
                        $response = array(
                            'success'  => true,
                            'message'  => get_phrases(['added', 'successfully']),
                            'title'    => $MesTitle
                        );
                    } else {
                        $response = array(
                            'success'  => false,
                            'message'  => (ENVIRONMENT == 'production') ? get_phrases(['something', 'went', 'wrong']) : get_db_error(),
                            'title'    => $MesTitle
                        );
                    }
                }
            } else {
                $id = $this->request->getVar('id');
                $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_02_Update('wh_bag_requisition', $data, array('id' => $id));
                if ($result) {
                    $response = array(
                        'success'  => true,
                        'message'  => get_phrases(['updated', 'successfully']),
                        'title'    => $MesTitle
                    );
                } else {
                    $response = array(
                        'success'  => false,
                        'message'  => (ENVIRONMENT == 'production') ? get_phrases(['something', 'went', 'wrong']) : get_db_error(),
                        'title'    => $MesTitle
                    );
                }
            }
        }

        echo json_encode($response);
    }

    /*--------------------------
    | Add item_receive info
    *--------------------------*/
    public function bdtaskt1m12c12_03_addItemReceive()
    {
        $modal_show = $this->request->getVar('modal_show');
        $action = $this->request->getVar('action');
        $purchase_id = $this->request->getVar('id');
        $store_id = $this->request->getVar('store_id');


        if ($action == 'receive') {
            $voucher_no = 'MR-' . getMAXID('wh_bag_receive', 'id');
        } else {
            // $voucher_no = 'GRETI-'.getMAXID('wh_bag_return', 'id');
        }
        $supplier_id = $this->request->getVar('supplier_id');
        $data = array(
            'purchase_id'   => $this->request->getVar('id'),
            'supplier_id'   => $supplier_id,
            'store_id'      => $store_id,
            'voucher_no'    => $voucher_no,
            'date'          => $this->date_db_format($this->request->getVar('date')),
            'truck_no'      => $this->request->getVar('truck_no'),
            'chalan_no'     => $this->request->getVar('chalan_no'),
            'gr_no'         => $this->request->getVar('gr_no'),
            'driver_name'   => $this->request->getVar('driver_name'),
            'driver_mobile' => $this->request->getVar('driver_mobile'),
            'vat' => $this->request->getVar('po_vat'),
            'sub_total' => $this->request->getVar('po_subtotal_price'),
            'grand_total' => $this->request->getVar('po_grand_total'),
        );

        $MesTitle = get_phrases(['receive', 'record']);
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'voucher_no'    => 'required|min_length[4]|max_length[20]',
                'supplier_id'   => 'required|numeric',
                'store_id'      => 'required',
                'date'          => 'required',
                'truck_no'      => 'required',
                'chalan_no'     => 'required',
                'gr_no'         => 'required',
            ];
        }

        if (empty($_FILES['scale_attachment']['name'])) {
            $rules = [
                'scale_attachment'    => 'required',
            ];
            if (!$this->validate($rules)) {
                $response = array(
                    'success'  => false,
                    'message'  => $this->validator->listErrors(),
                    'title'    => $MesTitle
                );
            }
        } else {
            $rules = [
                'scale_attachment' => [
                    'uploaded[scale_attachment]',
                    'mime_in[scale_attachment,image/jpg,image/jpeg,image/gif,image/png,application/pdf]',
                    'max_size[scale_attachment,4096]',
                ],
            ];
            if (!$this->validate($rules)) {
                $response = array(
                    'success'  => false,
                    'message'  => $this->validator->listErrors(),
                    'title'    => $MesTitle
                );
            }
        }

        if (empty($_FILES['chalan_attachment']['name'])) {
            $rules = [
                'chalan_attachment'    => 'required',
            ];
        } else {
            $rules = [
                'chalan_attachment' => [
                    'uploaded[chalan_attachment]',
                    'mime_in[chalan_attachment,image/jpg,image/jpeg,image/gif,image/png,application/pdf]',
                    'max_size[chalan_attachment,4096]',
                ],
            ];
        }





        if (!$this->validate($rules)) {
            $response = array(
                'success'  => false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        } else {

            //Receive
            if ($action == 'receive') {

                $po_info = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_purchase', array('id' => $purchase_id));

                $MesTitle = get_phrases(['receive', 'record']);
                if ($po_info->received == 1) {
                    $response = array(
                        'success'  => false,
                        'message'  => get_phrases(['already', 'received']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);
                    exit;
                }

                $item_counter   = $this->request->getVar('item_counter');
                $sub_total_qty  = $this->request->getVar('sub_total_qty');
                $is_received    = $this->request->getVar('is_received');
                $qty_check = 1;
                if ((int)$sub_total_qty <= 0) {
                    $response = array(
                        'success'  => false,
                        'message'  => get_phrases(['invalid', 'quantity']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);
                    exit;
                }
                for ($i = 1; $i <= $item_counter; $i++) {

                    if (!empty($_FILES['qc_attachment' . $i]['name'])) {
                        $rules = [
                            'qc_attachment' . $i => [
                                'uploaded[qc_attachment' . $i . ']',
                                'mime_in[qc_attachment' . $i . ',image/jpg,image/jpeg,image/gif,image/png,application/pdf]',
                                'max_size[qc_attachment' . $i . ',4096]',
                            ],
                        ];
                        if (!$this->validate($rules)) {
                            $response = array(
                                'success'  => false,
                                'message'  => $this->validator->listErrors(),
                                'title'    => $MesTitle
                            );
                            echo json_encode($response);
                            exit;
                        }
                    }

                    $quantity = $this->request->getVar('qty' . $i);
                    $avail_qty = $this->request->getVar('avail_qty' . $i);
                    if ($quantity > $avail_qty || $quantity < 0) {
                        $qty_check = 0;
                    }
                }


                if ($qty_check == 0) {
                    $response = array(
                        'success'  => false,
                        'message'  => get_phrases(['invalid', 'quantity']),
                        'title'    => $MesTitle
                    );
                    echo json_encode($response);
                    exit;
                }


                $isExist = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_receive', array('voucher_no' => $voucher_no));
                if (!empty($isExist)) {
                    $response = array(
                        'success'  => 'exist',
                        'message'  => get_phrases(['already', 'exists']),
                        'title'    => $MesTitle
                    );
                } else {

                    $scale_attachment  = $this->bdtaskt1c1_01_fileUpload->doUpload('/assets/dist/material/item_receive', $this->request->getFile('scale_attachment'));
                    $chalan_attachment = $this->bdtaskt1c1_01_fileUpload->doUpload('/assets/dist/material/item_receive', $this->request->getFile('chalan_attachment'));


                    $data['scale_attachment']   = $scale_attachment;
                    $data['chalan_attachment']  = $chalan_attachment;
                    $data['created_by']         = session('id');
                    $data['created_at']         = date('Y-m-d H:i:s');

                    $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('wh_bag_receive', $data);


                    if ($result) {

                        if ($is_received == 1) {
                            $received = 1;
                        } else {
                            $received = 2;
                        }
                        // $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_02_Update('wh_bag_requisition',array('received'=>$received), array('id'=>$po_info->requisition_id));
                        $result4 = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_02_Update('wh_bag_purchase', array('received' => $received), array('id' => $purchase_id));
                    }



                    $data2 = array();
                    $need_approval = 0;
                    for ($i = 1; $i <= $item_counter; $i++) {

                        $item_id = $this->request->getVar('item_id' . $i);
                        $quantity = $this->request->getVar('qty' . $i);
                        $price = $this->request->getVar('po_price_' . $i);
                        $total = $this->request->getVar('po_total_price_' . $i);
                        $vat_applicable = $this->request->getVar('vat_applicable' . $i);
                        $vat_amount = $this->request->getVar('vat_amount' . $i);
                        if (empty($quantity)) {
                            $quantity = 0;
                        }
                        $remarks = $this->request->getVar('remarks' . $i);
                        if (empty($remarks)) {
                            $remarks = '';
                        }
                        $qc_attachment = $this->bdtaskt1c1_01_fileUpload->doUpload('/assets/dist/material/item_receive', $this->request->getFile('qc_attachment' . $i));
                        if (empty($qc_attachment)) {
                            $qc_attachment = null;
                        }


                        if ($quantity > 0) {
                            $data2[] = array('receive_id' => $result, 'store_id' => $store_id, 'purchase_id' => $purchase_id, 'item_id' => $item_id, 'qty' => $quantity, 'avail_qty' => $quantity, 'price' => $price, 'total' => $total, 'vat_applicable' => $vat_applicable, 'vat_amount' => $vat_amount, 'remarks' => $remarks, 'qc_attachment' => $qc_attachment, 'batch_no' => 'BTC-' . $item_id . $result);

                            // $po_details = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_purchase_details', array('purchase_id' => $purchase_id, 'item_id' => $item_id));
                            // $item_price = ($po_details->price + $po_details->vat_amount) * $quantity;
                            $where = array('store_id' => $store_id, 'item_id' => $item_id);
                            $wh_bag_stock = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_stock', $where);
                            if (!empty($wh_bag_stock)) {
                                $stock = $wh_bag_stock->stock;
                            } else {
                                $stock = 0;
                            }
                            $affectedRows = $this->bdtaskt1m12c12_01_item_receiveModel->bdtaskt1m12_04_updateStock($quantity, $where);
                            $data3 = array('receive_id' => $result, 'store_id' => $store_id, 'item_id' => $item_id, 'stock' => ($stock), 'stock_in' => $quantity);
                            $data3['created_by']         = session('id');
                            $data3['created_at']         = date('Y-m-d H:i:s');
                            $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('wh_bag_stock_entry', $data3);
                            if ($affectedRows == 0) {
                                $data4 = array('store_id' => $store_id, 'item_id' => $item_id, 'stock' => $quantity, 'stock_in' => $quantity);
                                $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('wh_bag_stock', $data4);
                            }

                            // $this->bdtaskt1m16_09_supplierTransaction($result, $supplier_id, $item_price, $store_id);
                        }
                    }
                    $this->bdtaskt1m16_09_supplierTransactionNew($result, $supplier_id, $data['grand_total'], $store_id);

                    $result2 = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert_Batch('wh_bag_receive_details', $data2);


                    if ($result) {
                        // Store log data
                        $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item', 'receive']), get_phrases(['received']), $result, 'wh_bag_receive');

                        $response = array(
                            'success'  => true,
                            'message'  => get_phrases(['added', 'successfully']),
                            'title'    => $MesTitle,
                            'data'     => $modal_show
                        );
                    } else {
                        $response = array(
                            'success'  => false,
                            'message'  => (ENVIRONMENT == 'production') ? get_phrases(['something', 'went', 'wrong']) : get_db_error(),
                            'title'    => $MesTitle
                        );
                    }
                }
            }



            //Approve
            if ($action == 'approve') {

                $exist = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_receive', array('purchase_id' => $purchase_id, 'isApproved' => 1));
                $existTitle = get_phrases(['receive', 'record']);

                if (!empty($exist)) {
                    $response = array(
                        'success'  => false,
                        'message'  => get_phrases(['already', 'approved']),
                        'title'    => $existTitle
                    );
                    echo json_encode($response);
                    exit;
                }

                $data = array();
                $data['isApproved']       = 1;
                $data['approved_by']      = session('id');
                $data['approved_date']    = date('Y-m-d H:i:s');

                $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_02_Update('wh_bag_receive', $data, array('purchase_id' => $purchase_id));

                $item_counter    = $this->request->getVar('item_counter');
                $store_id = $this->request->getVar('store_id');

                for ($i = 1; $i <= $item_counter; $i++) {
                    $where = array('store_id' => $store_id, 'item_id' => $this->request->getVar('item_id' . $i));
                    $affectedRows = $this->bdtaskt1m12c12_01_item_receiveModel->bdtaskt1m12_05_increaseStock($this->request->getVar('qty' . $i), $where);

                    if ($affectedRows == 0) {
                        $data3 = array('store_id' => $store_id, 'item_id' => $this->request->getVar('item_id' . $i), 'stock' => $this->request->getVar('qty' . $i), 'stock_in' => $this->request->getVar('qty' . $i));
                        $result3 = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('wh_bag_stock', $data3);
                    }
                }
                // Store log data
                $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['item', 'receive']), get_phrases(['approved']), $result, 'wh_bag_receive');

                if ($result) {
                    $response = array(
                        'success'  => true,
                        'message'  => get_phrases(['approved', 'successfully']),
                        'title'    => $MesTitle
                    );
                } else {
                    $response = array(
                        'success'  => false,
                        'message'  => (ENVIRONMENT == 'production') ? get_phrases(['something', 'went', 'wrong']) : get_db_error(),
                        'title'    => $MesTitle
                    );
                }
            }
        }

        echo json_encode($response);
    }

    public function bdtaskt1m16_09_supplierTransaction($payment_id, $supplier_id, $amount, $store_id)
    {
        $supplier_row = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_supplier_information', array('id' => $supplier_id));
        $store_row = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_store', array('id' => $store_id));

        // $payment_method = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id'=>$payment_method_id,'list_id'=>16));
        // $payment_method_name = $payment_method->nameE;
        $debit = 0;
        $credit = $amount;

        $data = array(
            'VNo'         => 'SUPI-' . $payment_id,
            'Vtype'       => 'SUPI',
            'VDate'       => date('Y-m-d'),
            'COAID'       => empty($supplier_row) ? '0' : $supplier_row->acc_head,
            'Narration'   => 'Payment to Supplier, Name: ' . empty($supplier_row) ? '' : $supplier_row->nameE,
            // 'Narration'   => $payment_method_name.' Payment to Supplier, Name: '.$supplier_row->nameE,
            'Debit'       => $debit,
            'Credit'      => $credit,
            'PatientID'   => 0,
            'BranchID'     => session('branchId'),
            'IsPosted'    => 1,
            'CreateBy'    => session('id'),
            'CreateDate'  => date('Y-m-d H:i:s'),
            'IsAppove'    => 1
        );
        $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction', $data);

        $debit = $amount;
        $credit = 0;

        // if($payment_method_id == '120'){//Cash
        //     $acc_head = '121100001';
        // } else if($payment_method_id == '121'){//Span
        //     $acc_head = '121100002';
        // } else if($payment_method_id == '122'){//Visa Card
        //     $acc_head = '121100004';
        // } else if($payment_method_id == '123'){//Master Card
        //     $acc_head = '121100003';
        // } else if($payment_method_id == '124'){//Amex Card
        //     $acc_head = '121100005';
        // } else if($payment_method_id == '125'){//Bank Transfer
        //     $acc_head = '121100006';
        // } else if($payment_method_id == '126'){//Credit Card
        //     $acc_head = '121100011';
        // }


        $data = array(
            'VNo'         => 'INVI-' . $payment_id,
            'Vtype'       => 'INVI',
            'VDate'       => date('Y-m-d'),
            'COAID'       => empty($store_row) ? '' : $store_row->acc_head,
            'Narration'   => 'Amount in Store, Name: ' . empty($store_row) ? '' : $store_row->nameE,
            'Debit'       => $debit,
            'Credit'      => $credit,
            'PatientID'   => 0,
            'BranchID'     => session('branchId'),
            'IsPosted'    => 1,
            'CreateBy'    => session('id'),
            'CreateDate'  => date('Y-m-d H:i:s'),
            'IsAppove'    => 1
        );
        $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction', $data);

        return $result;
    }

    #route: /bag_purchase/truncate_item_stock
    public function bdtaskt1m12c12_04_truncate_item_stock()
    {
        // exit;
        // db_connect()->table('wh_bag_purchase')->set('received', '0', FALSE)->update();
        db_connect()->table('acc_transaction')->where('Vtype', 'INVI')->where('VDate', '2022-02-13')->delete();
        db_connect()->table('acc_transaction')->where('Vtype', 'SUPI')->where('Vtype', '2022-02-13')->delete();
        // delete_files('./assets/dist/material/item_receive/', false, true);
        db_connect()->table('wh_bag_receive')->truncate();
        db_connect()->table('wh_bag_receive_details')->truncate();
        db_connect()->table('wh_bag_stock_entry')->truncate();
        db_connect()->table('wh_bag_stock')->truncate();
        db_connect()->table('wh_bag_purchase')->truncate();
        db_connect()->table('wh_bag_purchase_details')->truncate();
        db_connect()->table('wh_bag_quatation')->truncate();
        db_connect()->table('wh_bag_quatation_details')->truncate();
        db_connect()->table('wh_bag_requisition')->truncate();
        db_connect()->table('wh_bag_requisition_details')->truncate();
        $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['truncate', 'wh_bag', 'stock']), get_phrases(['deleted']), 1, 'wh_bag_receive');
        echo 200;
    }
    /*--------------------------
    | Get item_receive by ID
    *--------------------------*/
    public function bdtaskt1m12c12_04_getItemReceiveById($id)
    {
        $data = $this->bdtaskt1m12c12_01_item_receiveModel->bdtaskt1m12_03_getItemReceiveDetailsById($id);
        echo json_encode($data);
    }

    /*--------------------------
    | Get item details by ID
    *--------------------------*/
    public function bdtaskt1m12c12_05_getItemReceiveDetailsById($id)
    {
        $data = $this->bdtaskt1m12c12_01_item_receiveModel->bdtaskt1m12_03_getItemReceiveDetailsById($id);
        echo json_encode($data);
    }

    /*--------------------------
    | Get purchase details by ID
    *--------------------------*/
    public function bdtaskt1m12c12_07_getItemPurchaseDetailsById()
    {
        $purchase_id = $this->request->getVar('purchase_id');
        $action = $this->request->getVar('action');

        $html = '';

        $purchases_details = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_purchase_details', array('purchase_id' => $purchase_id));

        $item_counter = 1;
        foreach ($purchases_details as $details) {
            $item_row = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_bag', array('id' => $details->item_id));

            if (!empty($item_row)) {
                $specification = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id' => $item_row->specification));
                $unit_row = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id' => $item_row->unit_id));
                $receive_details = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_receive_details', array('purchase_id' => $purchase_id, 'item_id' => $details->item_id));
                $receive_sum = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getSumRow('wh_bag_receive_details', 'qty', array('purchase_id' => $purchase_id, 'item_id' => $details->item_id));

                if (!empty($receive_sum)) {
                    $receive_qty    = $receive_sum->qty;
                    $avail_qty      = $details->qty - $receive_sum->qty;
                } else {
                    $receive_qty    = 0;
                    $avail_qty      = $details->qty;
                }

                if ($avail_qty < 0) {
                    $avail_qty = 0;
                }

                if ($avail_qty != 0) {

                    $html .= '<tr>
                                <td>' . $item_row->nameE . ' ' . $specification->nameE . ' Bag Size:' . $item_row->bag_size . ' Liner Size:' . $item_row->liner_size . '</td>
                                <td class="valign-middle">' . (($unit_row) ? $unit_row->nameE : '') . '</td>
                                <td align="right">' . $details->qty . '</td>
                                <td align="right">' . $receive_qty . '</td>
                                <td align="right">' . $avail_qty . '</td>
                                <td align="right"><input type="text" name="qty' . $item_counter . '" id="qty' . $item_counter . '" onkeyup="po_calculation(' . $item_counter . ')" class="form-control text-right onlyNumber"/></td>
                                <td><input type="text" name="remarks' . $item_counter . '" id="remarks' . $item_counter . '" class="form-control"/></td>
                                <td><input type="file" name="qc_attachment' . $item_counter . '" id="qc_attachment' . $item_counter . '" class="form-control"/></td>
                                
                                <input type="hidden" name="store' . $item_counter . '" id="store' . $item_counter . '" value="' . $details->store_id . '" />
                                <input type="hidden" name="po_avail_qty[]' . $item_counter . '" id="po_avail_qty' . $item_counter . '" class="po_subtotal">
                                <input type="hidden" name="po_total_qty[]' . $item_counter . '" id="po_total_qty' . $item_counter . '" class="po_total_qty">
                                <input type="hidden" name="avail_qty' . $item_counter . '" id="avail_qty' . $item_counter . '" value="' . $avail_qty . '">
                                <input type="hidden" name="item_id' . $item_counter . '" id="item_id' . $item_counter . '" value="' . $details->item_id . '">
                                <input type="hidden" name="po_price_' . $item_counter . '" id="po_price_' . $item_counter . '" value="' . $details->price . '">
                                <input type="hidden" name="vat_applicable' . $item_counter . '" id="vat_applicable' . $item_counter . '" value="' . $details->vat_applicable . '">
                                <input type="hidden" name="po_total_price_' . $item_counter . '" id="po_total_price_' . $item_counter . '" class="po_subtotal_price">
                                <input type="hidden" name="vat_amount' . $item_counter . '" id="vat_amount' . $item_counter . '" class="vat_total">
                            </tr>';
                    $html .= '<script>po_calculation(' . $item_counter . ')</script>';
                }

                $item_counter++;
            } else {
                $html .= '<tr></tr>';
            }
        }
        $html .= '<input type="hidden" name="item_counter" id="item_counter" value="' . ($item_counter - 1) . '">';
        $html .= '<input type="hidden" name="is_received" id="is_received">';
        $html .= '<input type="hidden" name="sub_total" id="po_sub_total">';
        $html .= '<input type="hidden" name="sub_total_qty" id="po_sub_total_qty">';
        $html .= '<input type="hidden" name="po_subtotal_price" id="po_subtotal_price">';
        $html .= '<input type="hidden" name="po_vat" id="po_vat">';
        $html .= '<input type="hidden" name="po_grand_total" id="po_grand_total">';
        echo $html;
    }

    /*--------------------------
    | Get item pricing by ID
    *--------------------------*/
    public function bdtaskt1m12c12_08_getItemPurchasePricingDetailsById()
    {
        $purchase_id = $this->request->getVar('purchase_id');

        $html = '<table class="table table-bordered w-100"><tr><th>' . get_phrases(['SL']) . '</th><th>' . get_phrases(['item']) . '</th><th class="text-center">' . get_phrases(['unit']) . '</th><th class="text-right">' . get_phrases(['purchase', 'quantity']) . '</th><th  class="text-right">' . get_phrases(['purchase', 'price']) . '</th><th  class="text-right">' . get_phrases(['subtotal']) . '</th></tr>';

        $purchases_details = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_05_getResultWhere('wh_bag_purchase_details', array('purchase_id' => $purchase_id));

        $sl = 0;
        foreach ($purchases_details as $details) {
            $sl++;
            $item_row = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_bag', array('id' => $details->item_id));
            if (!empty($item_row)) {
                $specification = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id' => $item_row->specification));
                $unit_row = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id' => $item_row->unit_id));
                $html .= '<tr>
                        <td width="5%">' . $sl . '</td>
                        <td width="20%">' . $item_row->nameE . ' ' . $specification->nameE . ' Bag Size:' . $item_row->bag_size . ' Liner Size:' . $item_row->liner_size . '</td>
                        <td width="5%" align="center">' . (($unit_row) ? $unit_row->nameE : '') . '</td>
                        <td width="10%" align="right">' . number_format(($details) ? $details->qty : 0, 2) . '</td>
                        <td width="10%" align="right">' . number_format(($details) ? $details->price : 0, 2) . '</td>
                        <td width="10%" align="right">' . number_format(($details) ? ($details->qty * $details->price) : 0, 2) . '</td>
                    </tr>';
            } else {
                $html .= '<tr></tr>';
            }
        }
        $html .= '</table>';

        echo $html;
    }

    public function bdtaskt1m12_09_supplierTransaction($id, $supplier_id, $amount, $payment_method_id, $voucher_type, $vat_amount = 0)
    {

        $supplier_row = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('wh_bag_supplier_information', array('id' => $supplier_id));

        if ($voucher_type == 'MR' && $payment_method_id == '') {
            $particulars = 'Goods Received';

            $debit = $amount - $vat_amount;
            $credit = 0;

            $vat_debit = $vat_amount;
            $vat_credit = 0;

            $sup_debit = 0;
            $sup_credit = $amount;
        } else if ($voucher_type == 'GRETI' && $payment_method_id == '') {

            $particulars = 'Goods Returned';

            $debit = 0;
            $credit = $amount - $vat_amount;

            $vat_debit = 0;
            $vat_credit = $vat_amount;

            $sup_debit = $amount;
            $sup_credit = 0;
        } else if ($payment_method_id != '') {

            $particulars = 'Goods Received Payment';

            $debit = 0;
            $credit = 0;

            $vat_debit = 0;
            $vat_credit = 0;

            $sup_debit = 0;
            $sup_credit = $amount;
        }

        if ($debit > 0 || $credit > 0) {
            $data = array(
                'VNo'         => $voucher_type . '-' . $id,
                'Vtype'       => $voucher_type,
                'VDate'       => date('Y-m-d'),
                'COAID'       => '124000001', //Closing Stock
                'Narration'   => $particulars . ', Supplier Name: ' . $supplier_row->nameE,
                'Debit'       => $debit,
                'Credit'      => $credit,
                'PatientID'   => 0,
                'BranchID'     => session('branchId'),
                'IsPosted'    => 1,
                'CreateBy'    => session('id'),
                'CreateDate'  => date('Y-m-d H:i:s'),
                'IsAppove'    => 1
            );
            $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction', $data);
        }

        if ($vat_debit > 0 || $vat_credit > 0) {
            $data = array(
                'VNo'         => $voucher_type . '-' . $id,
                'Vtype'       => $voucher_type,
                'VDate'       => date('Y-m-d'),
                'COAID'       => '222000002', //VAT Output Final (VO)
                'Narration'   => $particulars . ' VAT, Supplier Name: ' . $supplier_row->nameE,
                'Debit'       => $vat_debit,
                'Credit'      => $vat_credit,
                'PatientID'   => 0,
                'BranchID'     => session('branchId'),
                'IsPosted'    => 1,
                'CreateBy'    => session('id'),
                'CreateDate'  => date('Y-m-d H:i:s'),
                'IsAppove'    => 1
            );
            $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction', $data);
        }

        if ($supplier_row->acc_head == '') {
            $maxCoa = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_18_getLikeMaxData('acc_coa', '2211', 'HeadCode');
            $Coa = ((int)$maxCoa + 1);
            $coaData = [
                'HeadCode'         => $Coa,
                'HeadName'         => $supplier_row->nameE,
                'PHeadName'        => 'Suppliers',
                'nameE'            => $supplier_row->nameE,
                'nameA'            => $supplier_row->nameA,
                'HeadLevel'        => '4',
                'IsActive'         => '1',
                'IsTransaction'    => '1',
                'IsGL'             => '0',
                'HeadType'         => 'L',
                'IsBudget'         => '0',
                'IsDepreciation'   => '0',
                'DepreciationRate' => '0',
                'CreateBy'         => session('id'),
                'CreateDate'       => date('Y-m-d H:i:s'),
            ];
            $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('acc_coa', $coaData);
            $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_02_Update('wh_bag_supplier_information', array('acc_head' => $Coa), array('id' => $supplier_id));
        }

        $data = array(
            'VNo'         => $voucher_type . '-' . $id,
            'Vtype'       => $voucher_type,
            'VDate'       => date('Y-m-d'),
            'COAID'       => ($supplier_row->acc_head == '') ? $Coa : $supplier_row->acc_head,
            'Narration'   => $particulars . ', Supplier Name: ' . $supplier_row->nameE,
            'Debit'       => $sup_debit,
            'Credit'      => $sup_credit,
            'PatientID'   => 0,
            'BranchID'     => session('branchId'),
            'IsPosted'    => 1,
            'CreateBy'    => session('id'),
            'CreateDate'  => date('Y-m-d H:i:s'),
            'IsAppove'    => 1
        );
        $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction', $data);

        if ($payment_method_id != '') {

            $payment_method = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_03_getRow('list_data', array('id' => $payment_method_id, 'list_id' => 16));
            $payment_method_name = (!empty($payment_method)) ? $payment_method->nameE : '';

            if ($payment_method_id == '120') { //Cash
                $acc_head = '121100001';
            } else if ($payment_method_id == '121') { //Span
                $acc_head = '121100002';
            } else if ($payment_method_id == '122') { //Visa Card
                $acc_head = '121100004';
            } else if ($payment_method_id == '123') { //Master Card
                $acc_head = '121100003';
            } else if ($payment_method_id == '124') { //Amex Card
                $acc_head = '121100005';
            } else if ($payment_method_id == '125') { //Bank Transfer
                $acc_head = '121100006';
            } else if ($payment_method_id == '126') { //Credit Card
                $acc_head = '121100011';
            }

            $data = array(
                'VNo'         => $voucher_type . '-' . $id,
                'Vtype'       => $voucher_type,
                'VDate'       => date('Y-m-d'),
                'COAID'       => $acc_head,
                'Narration'   => $payment_method_name . ' Payment to Supplier, Name: ' . $supplier_row->nameE,
                'Debit'       => $amount,
                'Credit'      => 0,
                'PatientID'   => 0,
                'BranchID'    => session('branchId'),
                'IsPosted'    => 1,
                'CreateBy'    => session('id'),
                'CreateDate'  => date('Y-m-d H:i:s'),
                'IsAppove'    => 1
            );
            $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction', $data);
        }
        return $result;
    }

    public function bdtaskt1m12c12_12_get_po_list()
    {
        $data = $this->bdtaskt1m12c12_01_item_receiveModel->bdtaskt1m12_09_getPOList();
        echo json_encode($data);
    }

    public function bdtaskt1m16_09_supplierTransactionNew($payment_id, $supplier_id, $amount, $store_id)
    {
        $referenceNo = $supplier_id;
        $sub_type = 7;
        $vno = 'JV';
        $pedefine_head = $this->bdtaskt1m12c12_02_CmModel->getcoaPredefineHead();
        $subcodes      = $this->bdtaskt1m12c12_02_CmModel->getReferSubcode($sub_type, $referenceNo);
        $getVouchern   = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m8_07_getMaxvoucherno($vno);
        $fisyearid     = $this->bdtaskt1m12c12_02_CmModel->getActiveFiscalyear();
        $voucherdata = array(
            'fyear'           => $fisyearid,
            'Vtype'           => $vno,
            'VNo'             => $getVouchern,
            'VDate'           => date('Y-m-d'),
            'COAID'           => $pedefine_head->supplierCode,
            'Debit'           => $amount,
            'Credit'          => 0,
            'RevCodde'        => $pedefine_head->purchaseCode,
            'subType'         => $sub_type,
            'subCode'         => $subcodes,
            'ledgerComment'   => 'Bag Purchase Receive ' . $payment_id,
            'Narration'       => 'Bag Purchase Receive ' . $payment_id,
            'isApproved'      => 1,
            'CreateBy'        => session('id'),
            'CreateDate'      => date('Y-m-d H:i:s'),
        );
        $result = $this->bdtaskt1m12c12_02_CmModel->bdtaskt1m1_01_Insert('acc_vaucher', $voucherdata);
        $this->bdtaskt1m12c12_01_item_receiveModel->bdtaskt1m8_09_approveVoucher($getVouchern);

        return $result;
    }

}
