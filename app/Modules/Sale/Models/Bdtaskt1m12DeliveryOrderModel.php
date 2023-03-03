<?php

namespace App\Modules\Sale\Models;

use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m12DeliveryOrderModel extends Model
{

    public function __construct()
    {
        $this->db         = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = 1; //$this->permission->method('zone_tbl', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('zone_tbl', 'create')->access();
        $this->hasUpdateAccess = 1; //$this->permission->method('zone_tbl', 'update')->access();
        $this->hasDeleteAccess = 1; // $this->permission->method('zone_tbl', 'delete')->access();
        $this->request = \Config\Services::request();
    }

    public function autocompletproductdata($product_name = null)
    {
        return  $query = $this->db->table('wh_items')
            ->select('*')
            ->like('nameE', $product_name, 'both')
            ->where('status', 1)
            ->orderBy('nameE', 'asc')
            ->limit(15)
            ->get()
            ->getResultArray();
    }

    public function bdtask_001_dealerlist()
    {
        $builder = $this->db->table('dealer_info');
        $builder->select('*');
        $query = $builder->get();
        $data = $query->getResult();

        $list = array('' => get_phrases(['select', 'dealer']));
        if (!empty($data)) {
            foreach ($data as $value) {
                $list[$value->id] = $value->name;
            }
        }
        return $list;
    }

    public function item_details($item_id = '')
    {

        $builder = $this->db->table('wh_receive_details');
        $builder->select('*');
        $builder->where('item_id', $item_id);
        $builder->where('avail_qty >', 0);
        $builder->groupBy('batch_no');
        $query = $builder->get();
        $data = $query->getResult();

        $html = "";
        if ($data) {
            $html .= "<select name=\"batch_id[]\"   class=\"batch_id_1 form-control\" id=\"batch_id_1\">";

            $html .= "<option selected>" . get_phrases(['select', 'batch']) . "</option>";
            foreach ($data as $batchinfo) {
                $html .= "<option value=" . $batchinfo->batch_no . ">" . $batchinfo->batch_no . "</option>";
            }


            $html .= "</select>";
        }

        $result['batch'] = $html;
        return $result;
    }

    public function item_batchstockdata($item_id = null)
    {
        $builder = $this->db->table('wh_receive_details');
        $builder->select('sum(avail_qty) as stock,price');
        $builder->where('item_id', $item_id);
        $query = $builder->get();
        $data = $query->getRow();

        $item_info         = $this->db->table('wh_items')->select('*')->where('id', $item_id)->get()->getRow();
        $item_currentprice = $this->db->table('goods_pricing')->select('*')->where('product_id', $item_id)->orderBy('date', 'desc')->get()->getRow();
        $pending_s = $this->db->table('do_details_draft a')
            ->select('sum(a.quantity) as pendinstock')
            ->join('do_main_draft b', 'b.do_id = a.do_id', 'left')
            ->where('a.item_id', $item_id)
            ->where('b.status', 1)
            ->get()
            ->getRow();


        $result['avail_stock'] = ($data ? $data->stock : 0) - ($pending_s ? $pending_s->pendinstock : 0);
        $result['price']       = ($item_currentprice ? $item_currentprice->price : 0);
        $result['com_rate']    = ($item_info->com_rate ? $item_info->com_rate : 0);
        $result['bag_weight']  = ($item_info ? $item_info->bag_weight : 0);
        return $result;
    }

    public function pending_stock($item_id = null)
    {
        $pstockqr = $this->db->table('do_details a');
        $pstockqr->select('sum(a.quantity) as pendinstock');
        $pstockqr->join('do_main b', 'a.do_id = b.do_id');
        $pstockqr->where('a.item_id', $item_id);
        $pstockqr->where('b.status', 1);
        $pstockqr->where('b.payment_status', 0);
        $pquery = $pstockqr->get();
        return $pdata = $pquery->getRow();
    }

    public function bdtaskt1m1_05_saveDo($data = [])
    {

        $product_id = $this->request->getVar('product_id');
        if (empty($product_id)) {
            return false;
        }

        $postdraftData = array(
            'do_id'         => $data['do_id'],
            'vouhcer_no'    => $data['vouhcer_no'],
            'do_date'       => $data['do_date'],
            'dealer_id'     => $data['dealer_id'],
            'grand_total'   => $data['grand_total'],
            'do_by'         => $data['do_by'],
            'status'        => 1,
            'created_by'    => $data['created_by'],
        );

        $this->db->table('do_main')->insert($data);
        $this->db->table('do_main_draft')->insert($postdraftData);
        $do_id             = $data['do_id'];
        $product           = $this->request->getVar('product_id');
        $quantity          = $this->request->getVar('quantity');
        $batch             = $this->request->getVar('batch_id');
        $price             = $this->request->getVar('price');
        $com_rate          = $this->request->getVar('commission_rate');
        $com_amount        = $this->request->getVar('commission_amount');
        $total             = $this->request->getVar('total_price');
        $total_kg          = $this->request->getVar('kg');
        $total_kg          = $this->request->getVar('kg');
        $total_kg          = $this->request->getVar('kg');
        $dealer_comm_amnt  = $this->request->getVar('dealer_commission');
        $dealer_commission = $this->request->getVar('dealer_commission_rate');
        $b_weight          = $this->request->getVar('bag_weight');
        for ($i = 0; $i < count($product); $i++) {
            $pro_id                 = $product[$i];
            $qty                    = $quantity[$i];
            $commis_rate            = $com_rate[$i];
            $commis_amount          = $com_amount[$i];
            $pr_price               = $price[$i];
            $total_amount           = $total[$i];
            $kg                     = $total_kg[$i];
            $bag_weight             = $b_weight[$i];
            $dealer_commission_amnt = $dealer_comm_amnt[$i];

            $details = array(
                'do_id'                  => $do_id,
                'item_id'                => $pro_id,
                'quantity'               => $qty,
                'bag_weight'             => ($bag_weight ? $bag_weight : 0),
                'batch_id'               => 0,
                'total_kg'               => ($kg ? $kg : 0),
                'commission_rate'        => $commis_rate,
                'commission_amount'      => $commis_amount,
                'dealer_commission_rate' => ($dealer_commission ? $dealer_commission : 0),
                'dealer_commission'      => ($dealer_commission_amnt ? $dealer_commission_amnt : 0),
                'unit_price'             => $pr_price,
                'total_price'            => $total_amount
            );



            if ($qty > 0) {
                $this->db->table('do_details')->insert($details);
                $this->db->table('do_details_draft')->insert($details);
            }
        }

        return true;
    }

    public function bdtaskt1m1_05_saveDelivery($data = [])
    {
        $postdraftData = array(
            'dl_s_approved'    => 1,
            'dl_s_approved_by' => session('id'),
            'dl_s_sig'         => session('signature'),
        );



        $result = $this->db->table('do_main')->where('do_id', $data['do_id'])->update($postdraftData);

        $this->db->table('do_delivery')->insert($data);
        $do_id      = $data['do_id'];
        $dealer_coa = $this->dealer_coa_info($data['dealer_id']);
        $product    = $this->request->getVar('product_id');
        $quantity   = $this->request->getVar('quantity');
        $batch      = $this->request->getVar('batch_id');
        $price      = $this->request->getVar('price');
        $com_rate   = $this->request->getVar('commission_rate');
        $com_amount = $this->request->getVar('commission_amount');
        $total      = $this->request->getVar('total_price');
        $store      = $this->request->getVar('store_id');
        $kg         = $this->request->getVar('kg');
        $b_weight   = $this->request->getVar('bag_weight');
        for ($i = 0; $i < count($product); $i++) {
            $pro_id            = $product[$i];
            $qty               = $quantity[$i];
            $commis_rate       = $com_rate[$i];
            $commis_amount     = $com_amount[$i];
            $pr_price          = $price[$i];
            $total_amount      = $total[$i];
            $store_id          = $store[$i];
            $total_kg          = $kg[$i];
            $bag_weight        = $b_weight[$i];
            $check_total_stock = $this->check_itemstock($pro_id, $store_id);
            $t_stock           = ($check_total_stock ? $check_total_stock->stock : 0) - ($qty ? $qty : 0);
            $o_stock           = ($check_total_stock ? $check_total_stock->stock_out : 0) + ($qty ? $qty : 0);
            $production_stock  = $this->db->table('wh_receive_details')->select('*')->having('avail_qty > 0')->where('item_id', $pro_id)->orderBy('prod_date', 'asc')->groupBy('prod_date')->get()->getResult();
            $used_stock        = 0;
            $store_head        = $this->db->table('acc_coa')->select('*')->where('goods_store_id', $store_id)->get()->getRow();
            $details = array(
                'do_id'             => $do_id,
                'delivery_id'       => $data['delivery_id'],
                'item_id'           => $pro_id,
                'quantity'          => $qty,
                'store_id'          => $store_id,
                'batch_id'          => 0,
                'commission_rate'   => $commis_rate,
                'bag_weight'        => $bag_weight,
                'commission_amount' => $commis_amount,
                'unit_price'        => $pr_price,
                'total_kg'          => ($total_kg ? $total_kg : 0),
                'total_price'       => $total_amount
            );

            // $store_inventory = array(
            //     'VNo'         => $data['vouhcer_no'],
            //     'Vtype'       => 'DO',
            //     'VDate'       => date('Y-m-d'),
            //     'COAID'       => ($store_head ? $store_head->HeadCode : ''),
            //     'Narration'   => 'Store Inventory from DO ID' . $do_id . ' for item id ' . $pro_id,
            //     'Debit'       => 0,
            //     'Credit'      => ($production_stock ? ($production_stock[0]->price) * $qty : 0),
            //     'FyID'        => 0,
            //     'IsPosted'    => 1,
            //     'CreateBy'    => session('id'),
            //     'CreateDate'  => date('Y-m-d H:i:s'),
            //     'IsAppove'    => 1
            // );
            // $cogs = array(
            //     'VNo'         => $data['vouhcer_no'],
            //     'Vtype'       => 'DO',
            //     'VDate'       => date('Y-m-d'),
            //     'COAID'       => 50,
            //     'Narration'   => 'Cost of Good sold from DO ID' . $do_id . ' for item id ' . $pro_id,
            //     'Debit'       => ($production_stock ? ($production_stock[0]->price) * $qty : 0),
            //     'Credit'      => 0,
            //     'FyID'        => 0,
            //     'IsPosted'    => 1,
            //     'CreateBy'    => session('id'),
            //     'CreateDate'  => date('Y-m-d H:i:s'),
            //     'IsAppove'    => 1
            // );
            // $this->db->table('acc_transaction')->insert($store_inventory);
            // $this->db->table('acc_transaction')->insert($cogs);
            // $dealer_debit = array(
            //     'VNo'         => $data['vouhcer_no'],
            //     'Vtype'       => 'DO',
            //     'VDate'       => date('Y-m-d'),
            //     'COAID'       => ($dealer_coa ? $dealer_coa->HeadCode : ''),
            //     'Narration'   => 'delivery order amount',
            //     'Debit'       => ($total_amount ? $total_amount : 0),
            //     'Credit'      => 0,
            //     'FyID'        => 0,
            //     'IsPosted'    => 1,
            //     'CreateBy'    => session('id'),
            //     'CreateDate'  => date('Y-m-d H:i:s'),
            //     'IsAppove'    => 1
            // );

            //     $salses_creadit = array(
            //     'VNo'         => $data['vouhcer_no'],
            //     'Vtype'       => 'DO',
            //     'VDate'       => date('Y-m-d'),
            //     'COAID'       => 31,
            //     'Narration'   => 'delivery order amount',
            //     'Debit'       => 0,
            //     'Credit'      => ($total_amount ? $total_amount : 0),
            //     'FyID'        => 0,
            //     'IsPosted'    => 1,
            //     'CreateBy'    => session('id'),
            //     'CreateDate'  => date('Y-m-d H:i:s'),
            //     'IsAppove'    => 1
            // );
            // $this->db->table('acc_transaction')->insert($salses_creadit);

            if ($production_stock) {
                if ($used_stock < $qty) {
                    foreach ($production_stock as $pstock) {
                        $red_qty = ($qty ? $qty : 0) - ($used_stock ? $used_stock : 0);
                        if ($pstock->avail_qty >= $red_qty) {
                            $used_in_stock = $red_qty;
                            $used_stock    += $red_qty;
                        } else {
                            $used_in_stock = $pstock->avail_qty;
                            $used_stock    += $pstock->avail_qty;
                        }
                        $used_qty      = ($pstock ? $pstock->used_qty : 0) + ($used_in_stock ? $used_in_stock : 0);
                        $avail_stock   = ($pstock ? $pstock->avail_qty : 0) - ($used_in_stock ? $used_in_stock : 0);

                        $stock_update      = array(
                            'used_qty'  => $used_qty,
                            'avail_qty' => $avail_stock
                        );

                        $this->db->table('wh_receive_details')->where('item_id', $pro_id)->where('prod_date', $pstock->prod_date)->update($stock_update);
                    }
                }
            }

            $nw_stock = array(
                'stock'     => $t_stock,
                'stock_out' => $o_stock
            );


            if ($qty > 0) {
                $this->db->table('do_delivery_details')->insert($details);
                $this->db->table('wh_production_stock')->where('item_id', $pro_id)->where('store_id', $store_id)->update($nw_stock);
            }
        }

        return true;
    }

    public function bdtaskt1m1_06_updateDo($data = [])
    {

        $product_id = $this->request->getVar('product_id');
        if (empty($product_id)) {
            return false;
        }

        $postdraftData = array(
            'do_id'         => $data['do_id'],
            'vouhcer_no'    => $data['vouhcer_no'],
            'do_date'       => $data['do_date'],
            'dealer_id'     => $data['dealer_id'],
            'grand_total'   => $data['grand_total']
        );

        $this->db->table('do_main')->where('do_id', $data['do_id'])->update($data);
        $this->db->table('do_main_draft')->where('do_id', $data['do_id'])->update($postdraftData);
        $do_id = $data['do_id'];

        $product           = $this->request->getVar('product_id');
        $quantity          = $this->request->getVar('quantity');
        $batch             = $this->request->getVar('batch_id');
        $price             = $this->request->getVar('price');
        $com_rate          = $this->request->getVar('commission_rate');
        $com_amount        = $this->request->getVar('commission_amount');
        $total             = $this->request->getVar('total_price');
        $total_kg          = $this->request->getVar('kg');
        $b_weight          = $this->request->getVar('bag_weight');
        $dealer_commission = $this->request->getVar('dealer_commission_rate');
        $this->db->table('do_details')->where('do_id', $data['do_id'])->delete();
        $this->db->table('do_details_draft')->where('do_id', $data['do_id'])->delete();
        for ($i = 0; $i < count($product); $i++) {
            $pro_id        = $product[$i];
            $qty           = $quantity[$i];
            $commis_rate   = $com_rate[$i];
            $commis_amount = $com_amount[$i];
            $pr_price      = $price[$i];
            $total_amount  = $total[$i];
            $kg            = $total_kg[$i];
            $bag_weight    = $b_weight[$i];

            $details = array(
                'do_id'                  => $do_id,
                'item_id'                => $pro_id,
                'quantity'               => $qty,
                'batch_id'               => 0,
                'total_kg'               => ($kg ? $kg : 0),
                'bag_weight'             => $bag_weight,
                'commission_rate'        => $commis_rate,
                'commission_amount'      => $commis_amount,
                'dealer_commission_rate' => ($dealer_commission ? $dealer_commission : 0),
                'unit_price'             => $pr_price,
                'total_price'            => $total_amount
            );



            if ($qty > 0) {
                $this->db->table('do_details')->insert($details);
                $this->db->table('do_details_draft')->insert($details);
            }
        }

        return true;
    }

    public function bdtaskt1m12_06_getSalesAdminDolist($postData = null)
    {
        $response = array();
        ## Read value
        @$draw            = $postData['draw'];
        @$start           = $postData['start'];
        @$rowperpage      = $postData['length']; // Rows display per page
        @$columnIndex     = $postData['order'][0]['column']; // Column index
        @$columnName      = $postData['columns'][$columnIndex]['data']; // Column name
        @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        @$searchValue     = $postData['search']['value']; // Search value
        ## Search 
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (b.name like '%" . $searchValue . "%' OR a.do_date like '%" . $searchValue . "%' OR a.vouhcer_no like '%" . $searchValue . "%' OR u.fullname like '%" . $searchValue . "%' ) ";
        }

        ## Fetch records
        $builder3 = $this->db->table('do_main a');
        $builder3->select("a.*,b.name as dealer_name,u.fullname");
        $builder3->join("dealer_info b", 'b.id = a.dealer_id', 'left');
        $builder3->join("user u", 'u.emp_id = a.do_by', 'left');
        if ($searchValue != '') {
            $builder3->where($searchQuery);
        }
        ## Total number of records with filtering
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy('a.id', 'desc');
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records  =   $query3->getResultArray();
        ## Total number of records without filtering
        $totalRecords = $builder3->countAll();
        if ($searchQuery == '') {
            $totalRecords = $totalRecordwithFilter;
        }


        $data = array();
        $sl = 1;
        $delv_status = '';
        foreach ($records as $record) {
            $check_delivery_status = $this->do_delivery_status($record['do_id']);
            $dstatu = 'Delivered';
            if ($check_delivery_status == 1) {
                $dstatu = 'Partial Delivered';
            }
            $button = '';
            if ($record['status'] == 0) {
                $button .= '<a href="javascript:void(0);" class="btn btn-success-soft btnC mr-2 custool" data-id="' . $record['do_id'] . '" onclick="edit_do(' . $record['do_id'] . ')" title="Edit">Edit</a>';
                $button .= '<a href="javascript:void(0);" class="btn btn-success-soft btnC mr-2 custool" data-id="' . $record['do_id'] . '" title="Approve DO" onclick="request_add_to_do(' . $record['do_id'] . ')">Approve</a>';
                $delv_status = '<a href="javascript:void(0)" class="badge badge-danger text-white">Pending</a>';
            } else {
                $button .= '<a href="javascript:void(0);" class="badge badge-success text-white mr-2 custool" data-id="' . $record['do_id'] . '">Approved</a>';
            }

            $button .= (!$this->hasReadAccess) ? '' : '<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="DO Details" data-id="' . $record['do_id'] . '"><i class="far fa-eye"></i></a>';
            if ($record['status'] == 1 && $record['dl_s_approved'] == 0) {
                $delv_status = '<a href="javascript:void(0)" class="badge badge-info text-white custool">In Progress</a>';
            }

            if ($record['status'] == 1 && $record['dl_s_approved'] == 1 && $record['str_s_approved'] == 0) {
                $delv_status = '<a href="javascript:void(0)" class="badge badge-primary text-white custool">In Delivery Store</a>';
            }
            if ($check_delivery_status == 1) {
                if ($record['status'] == 1 && $record['dl_s_approved'] == 1 && $record['str_s_approved'] == 1) {
                    $delv_status = '<a href="javascript:void(0)" class="badge btn-violet rounded-pill">Partial Delivered</a>';
                }
            } else {
                if ($record['status'] == 1 && $record['dl_s_approved'] == 1 && $record['str_s_approved'] == 1) {
                    $delv_status = '<a href="javascript:void(0)" class="badge badge-success text-white">Delivered</a>';
                }
            }




            $data[] = array(
                'id'             => ($postData['start'] ? $postData['start'] : 0) + $sl++,
                'dealer_name'    => $record['dealer_name'],
                'vouhcer_no'     => $record['vouhcer_no'],
                'do_date'        => $record['do_date'],
                'total_amount'   => $record['grand_total'],
                'do_by'          => $record['fullname'],
                'delivery_status' => $delv_status,
                'status'         => ($record['status'] == 1 ? '<a href="javascript:void(0)" class="badge badge-success text-white">Approved</a>' : '<a href="javascript:void(0)" class="badge badge-warning ">Pending</a>'),
                'payment_status' => ($record['payment_status'] == 0 ? 'Pending' : '') . ($record['payment_status'] == 1 ? '<a href="javascript:void(0)" class="badge badge-danger text-white">Unpaid</a>' : '') . ($record['payment_status'] == 2 ? 'Paid' : ''),
                'button'         => $button
            );
        }

        ## Response
        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData"               => $data
        );
        return $response;
    }

    public function do_main_byid($id = null)
    {
        $builder = $this->db->table('do_main a');
        $builder->select("a.*,b.name as dealer_name,b.reference_id,b.address,b.phone_no,b.commission_rate,u.fullname");
        $builder->join("dealer_info b", 'b.id = a.dealer_id','left');
        $builder->join("user u", 'u.emp_id = a.do_by','left');
        $builder->where('a.do_id', $id);
        $query   =  $builder->get();
        return $records =   $query->getRow();
    }


    public function do_delivery_main_byid($id = null)
    {
        $builder = $this->db->table('do_delivery');
        $builder->select("*");
        $builder->where('delivery_id', $id);
        $query   =  $builder->get();
        return $records =   $query->getRow();
    }

    public function do_details_byid($id = null)
    {
        $builder = $this->db->table('do_details a');
        $builder->select("a.*,b.nameE as item_name,a.bag_weight,company_code");
        $builder->join("wh_items b", 'b.id = a.item_id', 'left');
        $builder->where('a.do_id', $id);
        $builder->groupBy('a.item_id');
        $query   =  $builder->get();
        return $records =   $query->getResult();
    }

    public function confirm_delivery_order($do_id = null)
    {
        $do_data       = $this->do_main_byid($do_id);
        $sub_type      = 6;
        $pedefine_head = $this->getcoaPredefineHead();
        $subcodes      = $this->getReferSubcode($sub_type,$do_data->dealer_id);
        $vno           = 'JV';
        $getVouchern   = $this->bdtaskt1m8_07_getMaxvoucherno($vno);
        $fisyearid     = $this->getActiveFiscalyear();
        $voucherdata = array(
            'fyear'           => $fisyearid,
            'Vtype'           => $vno,
            'VNo'             => $getVouchern,
            'VDate'           => $do_data->do_date,
            'COAID'           => $pedefine_head->dealerCode,
            'Debit'           => $do_data->grand_total,
            'Credit'          => 0,
            'RevCodde'        => $pedefine_head->salesCode,
            'subType'         => $sub_type,
            'subCode'         => $subcodes,
            'ledgerComment'   => 'DO BY '.$do_data->vouhcer_no,
            'Narration'       => 'From DO ('.$do_data->vouhcer_no.')',
            'isApproved'      => 0,
            'CreateBy'        => session('id'),
            'CreateDate'      => date('Y-m-d H:i:s'),
        );
        $confirm_data = array(
            'status'              => 1,
            'sls_admin_signature' => session('signature'),
            // 'do_by'               => session('id'),
            'payment_status'      => 1,
        );

        $result = $this->db->table('do_main')->where('do_id', $do_id)->update($confirm_data);

        if ($result) {
            $this->db->table('acc_vaucher')->insert($voucherdata);
            $this->bdtaskt1m8_09_approveVoucher($getVouchern);
            $this->db->table('do_main_draft')->where('do_id', $do_id)->delete();
            $this->db->table('do_details_draft')->where('do_id', $do_id)->delete();
            return 'true';
        } else {
            return 'false';
        }
    }

    public function accountant_confirm_delivery_order($do_id = null)
    {
        $do_data = $this->do_main_byid($do_id);
        $confirm_data = array(
            'accounts_approve'    => 1,
            'accountant_sig'      => session('signature'),
            'ac_approved_by'      => session('id')
        );

        $mlm_data     = $this->mlm_setting();
        $do_details   = $this->do_details_byid($do_id);
        $dealer_coa   = $this->dealer_coa_info($do_data->dealer_id);
        $result = $this->db->table('do_main')->where('do_id', $do_id)->update($confirm_data);

        if ($result) {
            $mlm_firsthand = $this->dealer_referal_info($do_data->reference_id);
            if ($mlm_firsthand) {
                $fdealer_coa = $this->dealer_coa_info($mlm_firsthand->id);
                if ($mlm_data->commission_type == 1) {
                    $fshare = ($do_data ? $do_data->grand_total : 0) * (($mlm_data->f_h_percentage ? $mlm_data->f_h_percentage : 0) / 100);
                } else {
                    $fshare = ($mlm_data->f_h_percentage ? $mlm_data->f_h_percentage : 0);
                }

                // $fdealer_credit = array(
                //     'VNo'         => ($do_data ? $do_data->vouhcer_no : ''),
                //     'Vtype'       => 'COMMISSION',
                //     'VDate'       => ($do_data ? $do_data->do_date : ''),
                //     'COAID'       => ($fdealer_coa ? $fdealer_coa->HeadCode : ''),
                //     'Narration'   => 'Do reference commission from ' . $do_data->dealer_name,
                //     'Debit'       => 0,
                //     'Credit'      => ($fshare ? $fshare : 0),
                //     'FyID'        => 0,
                //     'IsPosted'    => 1,
                //     'CreateBy'    => session('id'),
                //     'CreateDate'  => date('Y-m-d H:i:s'),
                //     'IsAppove'    => 1
                // );

                // $this->db->table('acc_transaction')->insert($fdealer_credit);

                $mlm_shand = $this->dealer_referal_info($mlm_firsthand->reference_id);
                if ($mlm_shand) {
                    $sdealer_coa = $this->dealer_coa_info($mlm_shand->id);
                    if ($mlm_data->commission_type == 1) {
                        $sshare = ($do_data ? $do_data->grand_total : 0) * (($mlm_data->s_h_percentage ? $mlm_data->s_h_percentage : 0) / 100);
                    } else {
                        $sshare = ($mlm_data->s_h_percentage ? $mlm_data->s_h_percentage : 0);
                    }
                    // $sdealer_debit = array(
                    //     'VNo'         => ($do_data ? $do_data->vouhcer_no : ''),
                    //     'Vtype'       => 'COMMISSION',
                    //     'VDate'       => ($do_data ? $do_data->do_date : ''),
                    //     'COAID'       => ($sdealer_coa ? $sdealer_coa->HeadCode : ''),
                    //     'Narration'   => 'Do reference commission from ' . $do_data->dealer_name,
                    //     'Debit'       => 0,
                    //     'Credit'      => ($sshare ? $sshare : 0),
                    //     'FyID'        => 0,
                    //     'IsPosted'    => 1,
                    //     'CreateBy'    => session('id'),
                    //     'CreateDate'  => date('Y-m-d H:i:s'),
                    //     'IsAppove'    => 1
                    // );

                    // $this->db->table('acc_transaction')->insert($sdealer_debit);
                }
            }

            $total_commission = 0;
            $total_buy_kg = 0;
            $commission_rate = 0;
            if ($do_details) {
                foreach ($do_details as $details) {
                    $total = ($details ? $details->total_kg : 0) * ($details ? $details->dealer_commission_rate : 0);
                    $total_commission += $total;
                    $total_buy_kg     += ($details ? $details->total_kg : 0);
                    $commission_rate  = ($details ? $details->dealer_commission_rate : 0);
                }
            }

            $commissiondata = array(
                'dealer_id'         => ($do_data ? $do_data->dealer_id : ''),
                'generate_month'    => ($do_data ? $do_data->do_date : ''),
                'total_kg'          => $total_buy_kg,
                'commission_rate'   => $commission_rate,
                'commission_amount' => $total_commission,
                'voucher_no'        => ($do_data ? $do_data->vouhcer_no : ''),
                'created_by'        => session('id'),
                'created_date'      => date('Y-m-d H:i:s')
            );


            $dealer_sale_commission = array(
                'VNo'         => ($do_data ? $do_data->vouhcer_no : ''),
                'Vtype'       => 'COMMISSION',
                'VDate'       => ($do_data ? $do_data->do_date : ''),
                'COAID'       => ($dealer_coa ? $dealer_coa->HeadCode : ''),
                'Narration'   => 'Do Sales commission from ' . ($do_data ? $do_data->vouhcer_no : ''),
                'Debit'       => 0,
                'Credit'      => $total_commission,
                'FyID'        => $this->getActiveFiscalyear(),
                'IsPosted'    => 1,
                'CreateBy'    => session('id'),
                'CreateDate'  => date('Y-m-d H:i:s'),
                'IsAppove'    => 1
            );

            if ($total_commission > 0) {
                $this->db->table('monthly_sale_commission')->insert($commissiondata);
                // $this->db->table('acc_transaction')->insert($dealer_sale_commission);
            }

            return 'true';
        } else {
            return 'false';
        }
    }

    public function accountant_confirm_do_paid($do_id = null, $attachment)
    {

        $do_data           = $this->do_main_byid($do_id);
        $paid_amount       = $this->request->getVar('paid_amount');
        $do_no             = $this->request->getVar('challan_no');
        $date              = $this->request->getVar('date');
        $dealer_head       = $this->request->getVar('dealer_code');
        $dealer_id         = $this->request->getVar('dealer_id');
        $payment_type      = $this->request->getVar('payment_method');
        $due_paymentdate   = $this->request->getVar('due_payment_date');
        $due_amount        = $this->request->getVar('due_amount');
        $narration         = $this->request->getVar('description');
        $total_paid_amount = ($do_data ? $do_data->paid_amount : 0) + ($paid_amount ? $paid_amount : 0);
        $payment_type      = ($payment_type == 1 ? $this->request->getVar('bank_id') : $payment_type);
        $confirm_data = array(
            'payment_status' => 2,
            'paid_amount'    => $total_paid_amount,
            'paid_by'        => session('id')
        );

        $trans_update = array(
            'IsAppove' => 1
        );

        $dealer_credit = array(
            'VNo'         => ($do_data ? $do_data->vouhcer_no : ''),
            'Vtype'       => 'DO-RCB',
            'VDate'       => ($date ? $date : ''),
            'COAID'       => ($dealer_head ? $dealer_head : ''),
            'Narration'   => ($narration ? $narration : 'DO Receive'),
            'Debit'       => 0,
            'Credit'      => ($paid_amount ? $paid_amount : 0),
            'FyID'        => 0,
            'IsPosted'    => 1,
            'CreateBy'    => session('id'),
            'CreateDate'  => date('Y-m-d H:i:s'),
            'IsAppove'    => 1
        );

        $payment_debit = array(
            'VNo'         => ($do_data ? $do_data->vouhcer_no : ''),
            'Vtype'       => 'DO-RCB',
            'VDate'       => ($date ? $date : ''),
            'COAID'       => ($payment_type ? $payment_type : ''),
            'Narration'   => ($narration ? $narration : 'DO Receive from ' . ($do_data ? $do_data->dealer_name : '')),
            'Debit'       => ($paid_amount ? $paid_amount : 0),
            'Credit'      => 0,
            'FyID'        => 0,
            'IsPosted'    => 1,
            'CreateBy'    => session('id'),
            'CreateDate'  => date('Y-m-d H:i:s'),
            'IsAppove'    => 1
        );

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


        $result = $this->db->table('do_main')->where('do_id', $do_id)->update($confirm_data);

        if ($result) {
            $this->db->table('acc_transaction')->where('VNo', ($do_data ? $do_data->vouhcer_no : ''))->update($trans_update);
            $this->db->table('acc_transaction')->insert($dealer_credit);
            $this->db->table('dealer_due_info')->insert($payment_record);
            $this->db->table('acc_transaction')->insert($payment_debit);
            return 'true';
        } else {
            return 'false';
        }
    }

    public function bdtaskt1m12_06_getAccountDolist($postData = null)
    {
        $response = array();
        ## Read value      
        @$draw            = $postData['draw'];
        @$start           = $postData['start'];
        @$rowperpage      = $postData['length']; // Rows display per page
        @$columnIndex     = $postData['order'][0]['column']; // Column index
        @$columnName      = $postData['columns'][$columnIndex]['data']; // Column name
        @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        @$searchValue     = $postData['search']['value']; // Search value
        ## Search 
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (b.name like '%" . $searchValue . "%' OR a.do_date like '%" . $searchValue . "%' OR a.vouhcer_no like '%" . $searchValue . "%' OR u.fullname like '%" . $searchValue . "%' ) ";
        }
        ## Fetch records
        $builder3 = $this->db->table('do_main a');
        $builder3->select("a.*,b.name as dealer_name,u.fullname");
        $builder3->join("dealer_info b", 'b.id = a.dealer_id', 'left');
        $builder3->join("user u", 'a.ac_approved_by = u.emp_id', 'left');
        if ($searchValue != '') {
            $builder3->where($searchQuery);
        }
        $builder3->where('a.status', 1);
        ## Total number of records with filtering
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3  =  $builder3->get();
        $records =   $query3->getResultArray();
        ## Total number of records without filtering
        $totalRecords = $builder3->countAll();
        if ($searchQuery == '') {
            $totalRecords = $totalRecordwithFilter;
        }
        $data = array();
        $sl          = 1;
        $delv_status = '';
        foreach ($records as $record) {

            $check_delivery_status = $this->do_delivery_status($record['do_id']);
            $dstatu = 'Delivered';
            if ($check_delivery_status == 1) {
                $dstatu = 'Partial Delivered';
            }
            $button = '';
            if ($record['accounts_approve'] == 0) {
                $button .= '<a href="javascript:void(0);" class="btn btn-success-soft btnC mr-2 custool" data-id="' . $record['do_id'] . '" title="Approve" onclick="do_accounts_aprroval(' . $record['do_id'] . ')">Approve</a>';
            } else {
                $button .= '';
            }
            if ($record['payment_status'] == 1) {
                $button .= '<a href="javascript:void(0);" class="btn btn-success-soft btnC mr-2 custool" data-id="' . $record['do_id'] . '" title="Pay Now" onclick="do_accounts_paid(' . $record['do_id'] . ')">Pay Now</a>';
            }
            $button .= (!$this->hasReadAccess) ? '' : '<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="DO Details" data-id="' . $record['do_id'] . '"><i class="far fa-eye"></i></a>';
            if ($record['status'] == 1 && $record['dl_s_approved'] == 0) {
                $delv_status = '<a href="javascript:void(0)" class="badge badge-info text-white custool" title="In Progress">In Progress</a>';
            }

            if ($record['status'] == 1 && $record['dl_s_approved'] == 1 && $record['str_s_approved'] == 0) {
                $delv_status = '<a href="javascript:void(0)" class="badge badge-primary text-white">In Delivery Store</a>';
            }
            if ($check_delivery_status == 1) {
                if ($record['status'] == 1 && $record['dl_s_approved'] == 1 && $record['str_s_approved'] == 1) {
                    $delv_status = '<a href="javascript:void(0)" class="badge btn-violet rounded-pill">Partial Delivered</a>';
                }
            } else {
                if ($record['status'] == 1 && $record['dl_s_approved'] == 1 && $record['str_s_approved'] == 1) {
                    $delv_status = '<a href="javascript:void(0)" class="badge badge-success text-white">Delivered</a>';
                }
            }
            if ($record['payment_status'] == 2 && $record['grand_total'] == $record['paid_amount']) {
                $pstatus = '<a href="javascript:void(0)" class="badge badge-success text-white">Paid</a>';
            }

            if ($record['payment_status'] == 2 && $record['grand_total'] > $record['paid_amount']) {
                $button .= '<a href="javascript:void(0);" class="btn btn-success-soft btnC mr-2 custool" data-id="' . $record['do_id'] . '" title="Pay Now" onclick="do_accounts_paid(' . $record['do_id'] . ')">Pay Now</a>';
                $pstatus = '<a href="javascript:void(0)" class="badge btn-violet rounded-pill">Partial Paid</a>';
            }

            $data[] = array(
                'id'              => ($postData['start'] ? $postData['start'] : 0) + $sl++,
                'dealer_name'     => $record['dealer_name'],
                'vouhcer_no'      => $record['vouhcer_no'],
                'do_date'         => $record['do_date'],
                'total_amount'    => $record['grand_total'],
                'approved_by'     => $record['fullname'],
                'delivery_status' => $delv_status,
                'status'          => ($record['accounts_approve'] == 1 ? '<a href="javascript:void(0)" class="badge badge-success text-white">Approved</a>' : '<a href="javascript:void(0)" class="badge badge-warning ">Pending</a>'),
                'payment_status'  => ($record['payment_status'] == 0 ? '<a href="javascript:void(0)" class="badge badge-warning ">Pending</a>' : '') . ($record['payment_status'] == 1 ? '<a href="javascript:void(0)" class="badge badge-danger text-white">Unpaid</a>' : '') . ($record['payment_status'] == 2 ? $pstatus : ''),
                'button'          => $button
            );
        }

        ## Response
        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData"               => $data
        );
        return $response;
    }


    public function bdtaskt1m12_07_getFactoryManagerDolist($postData = null)
    {
        $response = array();
        ## Read value

        @$draw            = $postData['draw'];
        @$start           = $postData['start'];
        @$rowperpage      = $postData['length']; // Rows display per page
        @$columnIndex     = $postData['order'][0]['column']; // Column index
        @$columnName      = $postData['columns'][$columnIndex]['data']; // Column name
        @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        @$searchValue     = $postData['search']['value']; // Search value
        ## Search 
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (b.name like '%" . $searchValue . "%' OR a.do_date like '%" . $searchValue . "%' OR a.vouhcer_no like '%" . $searchValue . "%' OR a.challan_no like '%" . $searchValue . "%' OR u.fullname like '%" . $searchValue . "%') ";
        }

        ## Fetch records
        $builder3 = $this->db->table('do_delivery a');
        $builder3->select("a.*,b.name as dealer_name,u.fullname");
        $builder3->join("dealer_info b", 'b.id = a.dealer_id', 'left');
        $builder3->join("user u", 'u.emp_id = a.fc_m_approved_by', 'left');
        if ($searchValue != '') {
            $builder3->where($searchQuery);
        }
        $builder3->where('a.status', 1);
        $builder3->where('a.accounts_approve', 1);
        $builder3->where('a.dl_s_approved', 1);
        $builder3->where('a.str_s_approved', 1);
        ## Total number of records with filtering
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records  =  $query3->getResultArray();
        ## Total number of records without filtering
        $totalRecords = $builder3->countAll();
        if ($searchQuery == '') {
            $totalRecords = $totalRecordwithFilter;
        }


        $data = array();
        $sl = 1;
        $delv_status = '';
        foreach ($records as $record) {
            $button = '';
            if ($record['fc_m_approved'] == 0) {
                $button .= '<a href="javascript:void(0);" class="btn btn-success-soft btnC mr-2 custool" data-id="' . $record['delivery_id'] . '" title="Approve" onclick="do_FactoryManager_aprroval(' . $record['delivery_id'] . ')">Approve</a>';
            } else {
                $button .= '';
            }

            $button .= (!$this->hasReadAccess) ? '' : '<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="DO Details" data-id="' . $record['delivery_id'] . '"><i class="far fa-eye"></i></a>';
            if ($record['status'] == 1 && $record['dl_s_approved'] == 1 && $record['str_s_approved'] == 1 && $record['fc_m_approved'] == 0) {
                $delv_status = '<a href="javascript:void(0)" class="badge badge-info text-white custool" title="In Progress">In Progress</a>';
            }

            if ($record['status'] == 1 && $record['dl_s_approved'] == 1 && $record['str_s_approved'] == 1 && $record['fc_m_approved'] == 1) {
                $delv_status = '<a href="javascript:void(0)" class="badge badge-success text-white custool" title="Delivered">Delivered</a>';
            }

            $data[] = array(
                'id'              => ($postData['start'] ? $postData['start'] : 0) + $sl++,
                'dealer_name'     => $record['dealer_name'],
                'vouhcer_no'      => $record['vouhcer_no'],
                'do_date'         => $record['do_date'],
                'approved_by'     => $record['fullname'],
                'challan_no'      => $record['challan_no'],
                'delivery_status' => $delv_status,
                'status'          => ($record['fc_m_approved'] == 1 ? '<a href="javascript:void(0)" class="badge badge-success text-white">Approved</a>' : '<a href="javascript:void(0)" class="badge badge-warning ">Pending</a>'),
                'button'          => $button
            );
        }

        ## Response
        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData"               => $data
        );
        return $response;
    }

    public function factoryManager_confirm_delivery_order($delivery_id = null)
    {
        $do_delivery_main = $this->do_delivery_main_byid($delivery_id);
        $do_id        = ($do_delivery_main ? $do_delivery_main->do_id : '');
        $do_data      = $this->do_main_byid($do_id);
        $confirm_data = array(
            'fc_m_approved'   => 1,
            'fc_m_sig'        => session('signature'),
            'fc_m_approved_by' => session('id')
        );

        $result = $this->db->table('do_main')->where('do_id', $do_id)->update($confirm_data);

        if ($result) {
            $result = $this->db->table('do_delivery')->where('delivery_id', $delivery_id)->update($confirm_data);
            return 'true';
        } else {
            return 'false';
        }
    }


    public function deliverySection_confirm_delivery_order($do_id = null)
    {
        $do_data = $this->do_main_byid($do_id);
        $challan = $this->voucher_no_generator($do_data->id);
        $confirm_data = array(
            'dl_s_approved'   => 1,
            'dl_s_approved_by' => session('id'),
            'dl_s_sig'        => session('signature'),
            'challan_no'      => $challan,
        );

        $result = $this->db->table('do_main')->where('do_id', $do_id)->update($confirm_data);

        if ($result) {
            return 'true';
        } else {
            return 'false';
        }
    }


    public function bdtaskt1m12_08_getDeliverySectionDolist($postData = null)
    {
        $response = array();
        ## Read value

        @$draw            = $postData['draw'];
        @$start           = $postData['start'];
        @$rowperpage      = $postData['length']; // Rows display per page
        @$columnIndex     = $postData['order'][0]['column']; // Column index
        @$columnName      = $postData['columns'][$columnIndex]['data']; // Column name
        @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        @$searchValue     = $postData['search']['value']; // Search value
        ## Search 
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (b.name like '%" . $searchValue . "%' OR a.do_date like '%" . $searchValue . "%' OR a.vouhcer_no like '%" . $searchValue . "%' OR u.fullname like '%" . $searchValue . "%') ";
        }

        ## Fetch records
        $builder3 = $this->db->table('do_main a');
        $builder3->select("a.*,b.name as dealer_name,u.fullname");
        $builder3->join("dealer_info b", 'b.id = a.dealer_id', 'left');
        $builder3->join("user u", 'u.emp_id = a.dl_s_approved_by', 'left');
        if ($searchValue != '') {
            $builder3->where($searchQuery);
        }
        $builder3->where('a.status', 1);
        $builder3->where('a.accounts_approve', 1);
        // $builder3->where('a.fc_m_approved',1);
        ## Total number of records with filtering
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records  =   $query3->getResultArray();
        ## Total number of records without filtering
        $totalRecords = $builder3->countAll();
        if ($searchQuery == '') {
            $totalRecords = $totalRecordwithFilter;
        }


        $data        = array();
        $sl          = 1;
        $delv_status = '';
        foreach ($records as $record) {
            $check_delivery_status = $this->do_delivery_status($record['do_id']);
            $button = '';
            // if($record['dl_s_approved'] == 0){
            //   $button .='<a href="javascript:void(0);" class="btn btn-success-soft btnC mr-2" data-id="'.$record['do_id'].'" onclick="do_DeliverySection_aprroval('.$record['do_id'].')">Approve && Create Challan</a>';
            // }else{
            // $button .='';     
            // }
            $dstatu = 'Delivered';
            if ($check_delivery_status == 1) {
                $button .= '<a href="javascript:void(0);" class="btn btn-success-soft btnC mr-2 custool" data-id="' . $record['do_id'] . '" title="Deliver" onclick="delivery_order(' . $record['do_id'] . ')">Deliver</a>';
                $dstatu = 'Partial Delivered';
            }
            $button .= (!$this->hasReadAccess) ? '' : '<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="DO Details" data-id="' . $record['do_id'] . '"><i class="far fa-eye"></i></a>';
            if ($record['status'] == 1 && $record['dl_s_approved'] == 0) {
                $delv_status = '<a href="javascript:void(0)" class="badge badge-info text-white custool" title="In Progress">In Progress</a>';
            }

            if ($record['status'] == 1 && $record['dl_s_approved'] == 1 && $record['str_s_approved'] == 0) {
                $delv_status = '<a href="javascript:void(0)" class="badge badge-primary text-white custool" title="In Delivery Store">In Delivery Store</a>';
            }
            if ($check_delivery_status == 1) {
                if ($record['status'] == 1 && $record['dl_s_approved'] == 1) {
                    $delv_status = '<a href="javascript:void(0)" class="badge btn-violet rounded-pill custool" title="Partial Delivered">Partial Delivered</a>';
                }
            } else {
                if ($record['status'] == 1 && $record['dl_s_approved'] == 1) {
                    $delv_status = '<a href="javascript:void(0)" class="badge badge-success text-white custool" title="Delivered">Delivered</a>';
                }
            }

            $data[] = array(
                'id'              => ($postData['start'] ? $postData['start'] : 0) + $sl++,
                'dealer_name'     => $record['dealer_name'],
                'vouhcer_no'      => $record['vouhcer_no'],
                'do_date'         => $record['do_date'],
                'total_amount'    => $record['grand_total'],
                'approved_by'     => $record['fullname'],
                'delivery_status' => $delv_status,
                'status'          => ($record['dl_s_approved'] == 1 ? '<a href="javascript:void(0)" class="badge badge-success text-white">Approved</a>' : '<a href="javascript:void(0)" class="badge badge-warning ">Pending</a>'),
                'payment_status'  => ($record['payment_status'] == 0 ? 'Pending' : '') . ($record['payment_status'] == 1 ? 'Unpaid' : '') . ($record['payment_status'] == 2 ? 'Paid' : ''),
                'button'          => $button
            );
        }

        ## Response
        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData"               => $data
        );
        return $response;
    }

    public function voucher_no_generator($do_id)
    {
        $builder = $this->db->table('do_delivery');
        $builder->select('max(id) as voucher_no');
        $query = $builder->get();
        $data = $query->getRow();
        if ($data) {
            $invoice_no = $data->voucher_no + 1;
        } else {
            $invoice_no = 1;
        }
        return   'Challan-' . $invoice_no;
    }


    public function bdtaskt1m12_09_getStoreSectionDolist($postData = null)
    {
        $response = array();
        ## Read value

        @$draw            = $postData['draw'];
        @$start           = $postData['start'];
        @$rowperpage      = $postData['length']; // Rows display per page
        @$columnIndex     = $postData['order'][0]['column']; // Column index
        @$columnName      = $postData['columns'][$columnIndex]['data']; // Column name
        @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        @$searchValue     = $postData['search']['value']; // Search value
        ## Search 
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (b.name like '%" . $searchValue . "%' OR a.do_date like '%" . $searchValue . "%' OR a.vouhcer_no like '%" . $searchValue . "%' OR a.challan_no like '%" . $searchValue . "%' OR u.fullname like '%" . $searchValue . "%') ";
        }

        ## Fetch records
        $builder3 = $this->db->table('do_delivery a');
        $builder3->select("a.*,b.name as dealer_name,u.fullname");
        $builder3->join("dealer_info b", 'b.id = a.dealer_id', 'left');
        $builder3->join("user u", 'u.emp_id = a.str_s_approved_by', 'left');
        if ($searchValue != '') {
            $builder3->where($searchQuery);
        }
        $builder3->where('a.status', 1);
        $builder3->where('a.accounts_approve', 1);
        $builder3->where('a.dl_s_approved', 1);
        ## Total number of records with filtering
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records  =  $query3->getResultArray();
        ## Total number of records without filtering
        $totalRecords = $builder3->countAll();
        if ($searchQuery == '') {
            $totalRecords = $totalRecordwithFilter;
        }


        $data          = array();
        $sl            = 1;
        $delv_status   = '';
        foreach ($records as $record) {
            $button = '';
            if ($record['str_s_approved'] == 0) {
                $button .= '<a href="javascript:void(0);" class="btn btn-success-soft btnC mr-2 custool" data-id="' . $record['delivery_id'] . '" title="Approve" onclick="do_StoreSection_aprroval(' . $record['delivery_id'] . ')">Approve</a>';
            } else {
                $button .= '';
            }

            $button .= (!$this->hasReadAccess) ? '' : '<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="DO Details" data-id="' . $record['delivery_id'] . '"><i class="far fa-eye"></i></a>';
            if ($record['status'] == 1 && $record['dl_s_approved'] == 0) {
                $delv_status = '<a href="javascript:void(0)" class="badge badge-info text-white custool" title="In Progress">In Progress</a>';
            }

            if ($record['status'] == 1 && $record['dl_s_approved'] == 1 && $record['str_s_approved'] == 1 && $record['fc_m_approved'] == 0) {
                $delv_status = '<a href="javascript:void(0)" class="badge badge-primary text-white custool" title="In Factory Manager">In Factory Manager</a>';
            }
            if ($record['status'] == 1 && $record['dl_s_approved'] == 1 && $record['str_s_approved'] == 1 && $record['fc_m_approved'] == 1) {
                $delv_status = '<a href="javascript:void(0)" class="badge badge-success text-white custool" title="Delivered">Delivered</a>';
            }

            $data[] = array(
                'id'              => ($postData['start'] ? $postData['start'] : 0) + $sl++,
                'dealer_name'     => $record['dealer_name'],
                'vouhcer_no'      => $record['vouhcer_no'],
                'do_date'         => $record['do_date'],
                'approved_by'     => $record['fullname'],
                'delivery_status' => $delv_status,
                'challan_no'      => $record['challan_no'],
                'status'          => ($record['str_s_approved'] == 1 ? '<a href="javascript:void(0)" class="badge badge-success text-white">Approved</a>' : '<a href="javascript:void(0)" class="badge badge-warning ">Pending</a>'),
                'button'          => $button
            );
        }

        ## Response
        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData"               => $data
        );
        return $response;
    }

    public function storeSection_confirm_delivery_order($delivery_id = null)
    {
        $do_delivery_main = $this->do_delivery_main_byid($delivery_id);
        $do_id        = ($do_delivery_main ? $do_delivery_main->do_id : '');
        $do_data      = $this->do_main_byid($do_id);
        $do_details   = $this->do_details_byid($do_id);
        $mlm_data     = $this->mlm_setting();
        $dealer_coa   = $this->dealer_coa_info($do_data->dealer_id);

        $confirm_data = array(
            'str_s_approved'   => 1,
            'str_s_approved_by' => session('id'),
            'str_s_sig'        => session('signature')
        );

        $confirm_data = array(
            'str_s_approved'   => 1,
            'str_s_approved_by' => session('id'),
            'str_s_sig'        => session('signature')
        );


        $result = $this->db->table('do_main')->where('do_id', $do_id)->update($confirm_data);

        if ($result) {
            $this->db->table('do_delivery')->where('delivery_id', $delivery_id)->update($confirm_data);
            return 'true';
        } else {
            return 'false';
        }
    }

    public function mlm_setting()
    {
        $builder = $this->db->table('referal_commission_setting');
        $builder->select("*");
        $query   =  $builder->get();
        return $records =   $query->getRow();
    }

    public function dealer_coa_info($dealer_id = null)
    {
        $builder = $this->db->table('acc_coa');
        $builder->select("*");
        $builder->where("dealer_id", $dealer_id);
        $query   =  $builder->get();
        return $records =   $query->getRow();
    }

    public function dealer_referal_info($dealer_id = null)
    {
        $builder = $this->db->table('dealer_info');
        $builder->select("*");
        $builder->where("affiliat_id", $dealer_id);
        $query   =  $builder->get();
        return $records =   $query->getRow();
    }


    public function checkitem_batchstockdata($item_id = null, $batch_id = null)
    {
        $builder = $this->db->table('wh_receive_details');
        $builder->select('sum(avail_qty) as availabe_qty,sum(used_qty) as total_used');
        $builder->where('item_id', $item_id);
        $builder->where('batch_no', $batch_id);
        $query = $builder->get();
        $data = $query->getRow();
        return $data;
    }

    public function check_itemstock($item_id = null, $store_id = null)
    {
        $builder = $this->db->table('wh_production_stock');
        $builder->select('*');
        $builder->where('item_id', $item_id);
        $builder->where('store_id', $store_id);
        $query = $builder->get();
        $data = $query->getRow();
        return $data;
    }

    public function payment_methods()
    {
        $builder = $this->db->table('acc_coa');
        $builder->select('*');
        $builder->where('PHeadName', 'CashBoxes');
        $query = $builder->get();
        $data = $query->getResult();

        $list = array(' ' => get_phrases(['select', 'method']));
        if (!empty($data)) {
            $list[1] = 'Bank Payment';
            foreach ($data as $value) {
                $list[$value->HeadCode] = $value->HeadName;
            }
        }
        return $list;
    }


    public function bank_heads($value = '')
    {
        $builder = $this->db->table('acc_coa');
        $builder->select('*');
        $builder->where('isBankNature', '1');
        $query = $builder->get();
        $data = $query->getResult();

        $list = array(' ' => get_phrases(['select', 'bank']));
        if (!empty($data)) {
            foreach ($data as $value) {
                $list[$value->HeadCode] = $value->HeadName;
            }
        }
        return $list;
    }


    public function delivered_itmedata($do_id, $item_id)
    {
        $builder = $this->db->table('do_delivery_details');
        $builder->select('sum(quantity) as deliverdqty');
        $builder->where('item_id', $item_id);
        $builder->where('do_id', $do_id);
        $query = $builder->get();
        $data = $query->getRow();
        return $data;
    }


    public function delivery_main_byid($id = null)
    {
        $builder = $this->db->table('do_delivery a');
        $builder->select("a.*,b.name as dealer_name,b.reference_id,b.address,b.commission_rate");
        $builder->join("dealer_info b", 'b.id = a.dealer_id');
        $builder->where('a.do_id', $id);
        $query   =  $builder->get();
        return $records =   $query->getRow();
    }

    public function delivery_details_byid($id = null)
    {
        $builder = $this->db->table('do_delivery_details a');
        $builder->select("a.*,b.nameE as item_name,a.bag_weight");
        $builder->join("wh_items b", 'b.id = a.item_id', 'left');
        $builder->where('a.do_id', $id);
        $builder->groupBy('a.item_id');
        $query   =  $builder->get();
        return $records =   $query->getResult();
    }

    public function finish_goods_store()
    {
        $builder = $this->db->table('wh_production_store');
        $builder->select('*');
        $query = $builder->get();
        $data = $query->getResult();

        $list = array('' => get_phrases(['select', 'store']));
        if (!empty($data)) {
            foreach ($data as $value) {
                $list[$value->id] = $value->nameE;
            }
        }
        return $list;
    }


    public function do_delivery_status($do_id)
    {
        $request_order = $this->db->table('do_details')->select('sum(quantity) as total_request')->where('do_id', $do_id)->get()->getRow();
        $delivered_order = $this->db->table('do_delivery_details')->select('sum(quantity) as total_delivered')->where('do_id', $do_id)->get()->getRow();

        $req_qty = ($request_order ? $request_order->total_request : 0);
        $del_qty = ($delivered_order ? $delivered_order->total_delivered : 0);
        $response = ($req_qty ? $req_qty : 0) - ($del_qty ? $del_qty : 0);
        $status = 0;
        if ($response > 0) {
            $status = 1;
        }

        return $status;
    }


    public function do_deliveryinfo_byid($id = null)
    {
        $builder = $this->db->table('do_delivery a');
        $builder->select("a.*,b.name as dealer_name,b.reference_id,b.address,b.commission_rate");
        $builder->join("dealer_info b", 'b.id = a.dealer_id');
        $builder->where('a.delivery_id', $id);
        $query   =  $builder->get();
        return $records =   $query->getRow();
    }



    public function do_delivery_details_byid($id = null)
    {
        $builder = $this->db->table('do_delivery_details a');
        $builder->select("a.*,b.nameE as item_name,a.bag_weight,b.company_code");
        $builder->join("wh_items b", 'b.id = a.item_id', 'left');
        $builder->where('a.delivery_id', $id);
        $builder->groupBy('a.item_id');
        $query   =  $builder->get();
        return $records =   $query->getResult();
    }


    public function item_dropdown()
    {
        return  $query = $this->db->table('wh_items')
            ->select("CONCAT_WS(' ', nameE,'(',company_code,')') AS text,id")
            ->where('status', 1)
            ->orderBy('nameE', 'asc')
            // ->limit(15)
            ->get()
            ->getResult();
    }

    public function get_sales_man($dealer_id)
    {
        return  $query = $this->db->table('sales_officer_assign a')
            ->select("b.fullname AS text, b.emp_id AS id")
            ->join("user b", 'b.emp_id = a.sales_officer_id', 'left')
            ->where('a.dealer_id', $dealer_id)
            ->get()
            ->getResult();
    }

    public function sales_personList()
    {

        $users = $this->db->table('user')->select("*")
            ->where('status', 1)
            ->get()->getResult();



        $sub = $this->db->table('sub_module')->select("*")
            ->where('directory', 'add_do')
            ->where('status', 1)
            ->get()->getRow();

        $list = array('' => get_phrases(['select', 'Sales Man']));
        if (!empty($users)) {
            foreach ($users as $value) {
                $query = $this->db->table('sec_userrole')->select("roleid")
                    ->where('user_id', $value->emp_id)
                    ->get()->getResult();


                $roles = array();
                foreach ($query as $row) {
                    $roles[] = $row->roleid;
                }

                if (!empty($roles)) {
                    $create = $this->db->table('role_permission')->select("*")
                        ->where('fk_module_id', $sub->id)
                        ->whereIn('role_id', $roles)
                        ->where('create', 1)
                        ->get()->getRow();
                    if ($create) {
                        $list[$value->emp_id] = $value->fullname;
                    }
                }
            }
        }



        return $list;
    }


    public function bdtaskt1m12_07_gatePassList($postData = null)
    {
        $response = array();
        ## Read value

        @$draw            = $postData['draw'];
        @$start           = $postData['start'];
        @$rowperpage      = $postData['length']; // Rows display per page
        @$columnIndex     = $postData['order'][0]['column']; // Column index
        @$columnName      = $postData['columns'][$columnIndex]['data']; // Column name
        @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        @$searchValue     = $postData['search']['value']; // Search value
        ## Search 
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (b.name like '%" . $searchValue . "%' OR a.do_date like '%" . $searchValue . "%' OR a.vouhcer_no like '%" . $searchValue . "%' OR a.challan_no like '%" . $searchValue . "%' OR u.fullname like '%" . $searchValue . "%') ";
        }

        ## Fetch records
        $builder3 = $this->db->table('do_delivery a');
        $builder3->select("a.*,b.name as dealer_name,u.fullname");
        $builder3->join("dealer_info b", 'b.id = a.dealer_id', 'left');
        $builder3->join("user u", 'u.emp_id = a.fc_m_approved_by', 'left');
        if ($searchValue != '') {
            $builder3->where($searchQuery);
        }
        $builder3->where('a.status', 1);
        $builder3->where('a.accounts_approve', 1);
        $builder3->where('a.dl_s_approved', 1);
        $builder3->where('a.str_s_approved', 1);
        $builder3->where('a.fc_m_approved', 1);
        ## Total number of records with filtering
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records  =  $query3->getResultArray();
        ## Total number of records without filtering
        $totalRecords = $builder3->countAll();
        if ($searchQuery == '') {
            $totalRecords = $totalRecordwithFilter;
        }


        $data = array();
        $sl = 1;
        $delv_status = '';
        foreach ($records as $record) {
            $gate_pass = $this->db->table('scaler')->select('*')->where('challan_no', $record['challan_no'])->get()->getRow();
            $approve_status = ($gate_pass ? $gate_pass->approved_status : '');
            $truck_out = ($gate_pass ? $gate_pass->truck_weight_with_items : '');
            $button = '';
            $challan_no =  "gatePassApprove('" . $record['challan_no'] . "')";
            if ($approve_status != 1 && $truck_out > 0) {
                $button .= '<a href="javascript:void(0);" class="btn btn-success-soft btnC mr-2 custool" data-id="' . $record['delivery_id'] . '" title="Approve" onclick="' . $challan_no . '">Approve</a>';
            }
            if ($truck_out == '' || $truck_out == 0) {
                if ($this->permission->method('gate_pass', 'create')->access()) {
                    $button .= '<a href="javascript:void(0);" class="btn btn-success-soft btnC mr-2 custool" data-id="' . $record['delivery_id'] . '" onclick="gatePassInfo(' . $record['delivery_id'] . ')" title="Add Gate Pass Info">Add Gate Pass Info</a>';
                }
            }
            if ($approve_status == 1) {
                $button .= (!$this->hasReadAccess) ? '' : '<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="DO Details" data-id="' . $record['delivery_id'] . '"><i class="far fa-eye"></i></a>';
            }


            $data[] = array(
                'id'              => ($postData['start'] ? $postData['start'] : 0) + $sl++,
                'dealer_name'     => $record['dealer_name'],
                'vouhcer_no'      => $record['vouhcer_no'],
                'do_date'         => $record['do_date'],
                'approved_by'     => $record['fullname'],
                'challan_no'      => $record['challan_no'],
                'delivery_status' => '',
                'status'          => ($approve_status == 1 ? '<a href="javascript:void(0)" class="badge badge-success text-white">Approved</a>' : '<a href="javascript:void(0)" class="badge badge-warning ">Pending</a>'),
                'button'          => $button
            );
        }

        ## Response
        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData"               => $data
        );
        return $response;
    }

    public function save_gatepass($data = [])
    {
        $check_exist = $this->db->table('scaler')->where('do_no', $data['do_no'])->where('challan_no', $data['challan_no'])->countAllResults();
        if ($check_exist == 0) {
            $this->db->table('scaler')->insert($data);
            return 1;
        } else {
            $this->db->table('scaler')->where('challan_no', $data['challan_no'])->update($data);
            return 2;
        }
        return 0;
    }

    public function scalerInfo($id)
    {
        $deliverdata = $this->do_delivery_main_byid($id);
        $challan_no  = ($deliverdata ? $deliverdata->challan_no : '');
        return $data = $this->db->table('scaler a')
            ->select('a.*,b.nameE as store_name')
            ->join('wh_production_store b', 'b.id = a.store_id')
            ->where('a.challan_no', $challan_no)
            ->get()
            ->getRow();
    }

    public function gatepass_approve($challan)
    {
        $data = array(
            'approved_status'     => 1,
            'gate_pass_signature' => session('signature')
        );
        return $this->db->table('scaler')->where('challan_no', $challan)->update($data);
    }

    public function setting_info()
    {
        return $settingdata = $this->db->table('setting')->select('*')->get()->getRow();
    }

    public function bdtaskt1m12_03_getSalesPersonDetailsById($id)
    {
        $data = $this->db->table('hrm_employees')->select('*')
            ->where('employee_id', $id)
            ->get()
            ->getRow();

        return $data;
    }

    public function getActiveFiscalyear()
    {
         $fiscalyear = $this->db->table('financial_year')->select('*')->where('status',1)->get()->getRow();
         return ($fiscalyear?$fiscalyear->id:0);
    }

    public function getcoaPredefineHead()
    {
         $predefinehead = $this->db->table('acc_predefine_account')->select('*')->get()->getRow();
         return ($predefinehead?$predefinehead:false);
    }

    public function getReferSubcode($subType,$refcode)
    {
         $subcode = $this->db->table('acc_subcode')->select('*')->where('subTypeId',$subType)->where('referenceNo',$refcode)->get()->getRow();
         return ($subcode?$subcode->id:false);
    }

    public function bdtaskt1m8_07_getMaxvoucherno($type)
    {
         $result = $this->db->table('acc_vaucher')
                        ->select("VNo")
                        ->where('Vtype', $type)
                        ->orderBy('id','desc')
                        ->get()
                        ->getRow();

        $typed =  $this->db->table('acc_voucher_type')
        ->select("*")
        ->where('typen', $type)
        ->get()
        ->getRow();
        $vno = ($result?explode('-',$result->VNo):0);
        $vn = ($result?$vno[1]+1:1);
        return $voucher = $typed->prefix_code.($vn);                
    }

    public function bdtaskt1m8_09_approveVoucher($id)
    {
        $vaucherdata = $this->db->table('acc_vaucher')
        ->select('*')
        ->where('VNo',$id)
        ->get()->getResult();
        $action = '';
    $ApprovedBy=session('id');
    $approvedDate=date('Y-m-d H:i:s');
if($vaucherdata) {
foreach($vaucherdata as $row) {           
   $transationinsert = array(     
             'vid'            =>  $row->id,
             'FyID'           =>  $row->fyear,
             'VNo'            =>  $row->VNo,
             'Vtype'          =>  $row->Vtype,
             'referenceNo'    =>  $row->referenceNo,
             'VDate'          =>  $row->VDate,
             'COAID'          =>  $row->COAID,     
             'Narration'      =>  $row->Narration,     
             'ledgerComment'  =>  $row->ledgerComment,     
             'Debit'          =>  $row->Debit, 
             'Credit'         =>  $row->Credit,     
             'IsPosted'       =>  1,    
             'RevCodde'       =>  $row->RevCodde,    
             'subType'        =>  $row->subType,     
             'subCode'        =>  $row->subCode,     
             'IsAppove'       =>  1,                      
             'CreateBy'       => $ApprovedBy,
             'CreateDate'     => $approvedDate
           );          
   $this->db->table('acc_transaction')->insert($transationinsert); 
   $revercetransationinsert = array( 
             'vid'            =>  $row->id,    
             'FyID'           =>  $row->fyear,
             'VNo'            =>  $row->VNo,
             'Vtype'          =>  $row->Vtype,
             'referenceNo'    =>  $row->referenceNo,
             'VDate'          =>  $row->VDate,
             'COAID'          =>  $row->RevCodde,     
             'Narration'      =>  $row->Narration,     
             'ledgerComment'  =>  $row->ledgerComment,     
             'Debit'          =>  $row->Credit, 
             'Credit'         =>  $row->Debit,     
             'IsPosted'       =>  1,    
             'RevCodde'       =>  $row->COAID,    
             'subType'        =>  NULL,     
             'subCode'        =>  NULL,     
             'IsAppove'       =>  1,                      
             'CreateBy'       => $ApprovedBy,
             'CreateDate'     => $approvedDate
           ); 
           $this->db->table('acc_transaction')->insert($revercetransationinsert);  
}
}
$action = 1;
$upData = array(
         'VNo'          => $id,
         'isApproved'   => $action,
         'approvedBy'   => $ApprovedBy,
         'approvedDate' => $approvedDate,
         'status'       => $action
       );
return $this->db->table('acc_vaucher')->where('VNo',$id)->update($upData);
    }
}
