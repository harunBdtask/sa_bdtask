<?php

namespace App\Modules\SalesReturn\Models;

use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m12ReturnModel extends Model
{

    public function __construct()
    {
        $this->db              = db_connect();
        $this->permission      = new Permission();
        $this->hasReadAccess   = 1;
        $this->hasUpdateAccess = 1;
        $this->hasDeleteAccess = 1;
        $this->request = \Config\Services::request();
    }

    public function delivery_main_data($challan_no)
    {
        $builder = $this->db->table('do_delivery a');
        $builder->select("a.*,b.name as dealer_name,b.reference_id,b.address,b.commission_rate,b.address as dealer_address");
        $builder->join("dealer_info b", 'b.id = a.dealer_id');
        $builder->where('a.challan_no', $challan_no);
        $query   =  $builder->get();
        return $records =   $query->getRow();
    }

    public function do_delivery_details_byid($id = null)
    {
        $builder = $this->db->table('do_delivery_details a');
        $builder->select("a.*,b.nameE as item_name,a.bag_weight,b.company_code,c.nameE as store_name");
        $builder->join("wh_items b", 'b.id = a.item_id', 'left');
        $builder->join("wh_production_store c", 'c.id = a.store_id', 'left');
        $builder->where('a.delivery_id', $id);
        $builder->groupBy('a.item_id');
        $query   =  $builder->get();
        return $records =   $query->getResult();
    }

    public function bdtaskt1m12_01_dealerlist()
    {
        $builder = $this->db->table('dealer_info');
        $builder->select("*");
        $query   =  $builder->get();
        return $records =   $query->getResult();
    }

     public function setting_info()
      {
          return $settingdata = $this->db->table('setting')->select('*')->get()->getRow();
      }


      public function save_returnData(array $data = [])
      {
         $main_data = $this->db->table('sales_return_main')->insert($data);

         if($main_data){
             $product_id           = $this->request->getVar('product_id');
             $store_id             = $this->request->getVar('store_id');
             $quantity             = $this->request->getVar('quantity');
             $bag_weight           = $this->request->getVar('bag_weight');
             $unit_price           = $this->request->getVar('unit_price');
             $vat_percentage       = $this->request->getVar('vat_percentage');
             $deduction_percentage = $this->request->getVar('deduction_percentage');
             $total_amount         = $this->request->getVar('total_amount');

             for($i = 0;$i < count($product_id);$i++){
             $item_id    = $product_id[$i];
             $str_id     = $store_id[$i];
             $qty        = $quantity[$i];
             $price      = $unit_price[$i];
             $row_toral  = $total_amount[$i];
             $pro_stock  = $this->db->table('wh_production_stock')->select('*')->where('item_id', $item_id)->where('store_id',$str_id)->get()->getRow();
             $btch_stock  = $this->db->table('wh_receive_details')->select('*')->where('item_id', $item_id)->where('store_id',$str_id)->orderBy('prod_date', 'desc')->groupBy('prod_date')->get()->getRow();

             $return_details = array(
                 'return_id'  => $data['return_id'],
                 'product_id' => $item_id,
                 'delivery_id'=> $this->request->getVar('delivery_id'),
                 'store_id'   => $str_id,
                 'batch_id'   => ($btch_stock?$btch_stock->batch_no:''),
                 'return_qty' => ($qty?$qty:0),
                 'bag_weight' => ($bag_weight?$bag_weight[$i]:0),
                 'price'      => ($price?$price:0),
                 'vat_per'    => ($vat_percentage?$vat_percentage[$i]:0),
                 'deduct_per' => ($deduction_percentage?$deduction_percentage[$i]:0),
                 'row_total'  => ($row_toral?$row_toral:0)
             );

             $production_stock = array(
                 'stock'     => ($pro_stock?$pro_stock->stock:0) + ($qty?$qty:0),
                 'stock_in'  => ($pro_stock?$pro_stock->stock_in:0) + ($qty?$qty:0),

             );

             $batch_stock = array(
               'return_qty' => ($btch_stock?$btch_stock->return_qty:0) + ($qty?$qty:0),
               'avail_qty'  => ($btch_stock?$btch_stock->avail_qty:0) + ($qty?$qty:0),  
            );

            if($qty > 0){

                $this->db->table('sales_return_details')->insert($return_details);

            if($data['return_type'] == 1){
             $this->db->table('wh_production_stock')->where('item_id',$item_id)->where('store_id',$str_id)->update($production_stock);
             $this->db->table('wh_receive_details')->where('item_id',$item_id)->where('store_id',$str_id)->where('batch_no',($btch_stock?$btch_stock->batch_no:''))->update($batch_stock);
            }
        }
             
             }

             return true;
         }
         return false;
      }


    public function bdtaskt1m12_06_getSaleReturnlist($postData = null)
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
            $searchQuery = " (b.name like '%" . $searchValue . "%' OR a.date like '%" . $searchValue . "%' OR u.fullname like '%" . $searchValue . "%' OR a.do_no like '%" . $searchValue . "%' OR a.challan_no like '%" . $searchValue . "%') ";
        }

        ## Fetch records
        $builder3 = $this->db->table('sales_return_main a');
        $builder3->select("a.*,b.name as dealer_name,u.fullname");
        $builder3->join("dealer_info b", 'b.id = a.dealer_id', 'left');
        $builder3->join("user u", 'u.emp_id = a.return_by', 'left');
        if ($searchValue != '') {
            $builder3->where($searchQuery);
        }
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


        $data = array();
        $sl = 1;
        $delv_status = '';
        foreach ($records as $record) {

            $button = '';
         
                $button .= '<a href="javascript:void(0);" class="badge badge-success actionPreview text-white mr-2" data-id="' . $record['return_id'] . '"><i class="fas fa-eye"></i></a>';
          



            $data[] = array(
                'id'             => ($postData['start'] ? $postData['start'] : 0) + $sl++,
                'dealer_name'    => $record['dealer_name'],
                'challan_no'     => $record['challan_no'],
                'date'           => $record['date'],
                'do_no'          => $record['do_no'],
                'return_by'      => $record['fullname'],
                'total_amount'   => $record['net_amount'],
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

    public function check_previous_return($item_id,$delivery_id)
    {
        $builder = $this->db->table('sales_return_details');
        $builder->select("sum(return_qty) as totalreturn");
        $builder->where('product_id', $item_id);
        $builder->where('delivery_id', $delivery_id);
        $query   =  $builder->get();
        // print_r($delivery_id);exit;
        return $records =   $query->getRow();
    }

    public function return_main($return_id)
    {
        $builder3 = $this->db->table('sales_return_main a');
        $builder3->select("a.*,b.name as dealer_name,u.fullname as return_by");
        $builder3->join("dealer_info b", 'b.id = a.dealer_id', 'left');
        $builder3->join("user u", 'u.emp_id = a.return_by', 'left');
        $builder3->where("a.return_id", $return_id);
        $query3   =  $builder3->get();
       return $records  =   $query3->getRow();
    }

    public function return_details($return_id)
    {
        $builder = $this->db->table('sales_return_details a');
        $builder->select("a.*,b.nameE as item_name,c.nameE as store_name");
        $builder->join("wh_items b", 'b.id = a.product_id', 'left');
        $builder->join("wh_production_store c", 'c.id = a.store_id', 'left');
        $builder->where('a.return_id', $return_id);
        $query   =  $builder->get();
        return $records =   $query->getResult();
    }
}
