<?php

namespace App\Modules\Finished_goods\Models;

use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m12StockTransferModel extends Model
{

      public function __construct()
      {
            $this->db = db_connect();
            $this->permission = new Permission();
            $this->request = \Config\Services::request();
            $this->hasReadAccess = $this->permission->method('fg_stock', 'read')->access();
            //$this->hasCreateAccess = $this->permission->method('fg_stock', 'create')->access();
            //$this->hasUpdateAccess = $this->permission->method('fg_stock', 'update')->access();
            //$this->hasDeleteAccess = $this->permission->method('fg_stock', 'delete')->access();

      }

      public function bdtaskt1m12_02_getAll($postData = null)
      {
            $response = array();
            ## Read value
            @$draw = $postData['draw'];
            @$start = $postData['start'];
            @$rowperpage = $postData['length']; // Rows display per page
            @$columnIndex = $postData['order'][0]['column']; // Column index
            @$columnName = $postData['columns'][$columnIndex]['data']; // Column name
            @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
            @$searchValue = $postData['search']['value']; // Search value
            ## Search 
            $searchQuery = "";
            if ($searchValue != '') {
                  $searchQuery = " ( st.date like '%" . $searchValue . "%' OR u.fullname like '%" . $searchValue . "%' OR s.nameE like '%" . $searchValue . "%') ";
            }
      

            ## Fetch records
            $builder3 = $this->db->table('store_transfer_main st');
            $builder3->select("st.*,u.fullname as create_by,s.nameE as storename");
            if ($searchQuery != '') {
                  $builder3->where($searchQuery);
            }
            $builder3->join('user u', 'u.emp_id=st.created_by', 'left');
            $builder3->join('wh_production_store s', 's.id=st.from_store', 'left');
            ## Total number of records with filtering
            $totalRecordwithFilter = $builder3->countAllResults(false);
            $builder3->orderBy($columnName, $columnSortOrder);
            $builder3->limit($rowperpage, $start);
            $query3   =  $builder3->get();
            $records =   $query3->getResultArray();
            ## Total number of records without filtering
            $totalRecords = $builder3->countAll();
            if ($searchQuery == '') {
                  $totalRecords = $totalRecordwithFilter;
            }
            $data = array();

            $details = get_phrases(['view', 'details']);
            $sl = 1;
            foreach ($records as $record) {
                  $button = '';

                  $button .= (!$this->hasReadAccess) ? '' : '<a href="javascript:void(0);" class="btn btn-success-soft btnC actionPreview mr-2 custool" title="' . $details . '" data-id="' . $record['transfer_id'] . '"><i class="far fa-eye"></i></a>';
                  /*$button .='<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .='<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';*/
                  $data[] = array(
                        'id'            =>  ($postData['start'] ? $postData['start'] : 0) + $sl++,
                        'store_name'    => $record['storename'],
                        'date'          => $record['date'],
                        'transfer_by'   => $record['create_by'],
                        'button'        => $button
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

      public function bdtaskt1m12_03_getMainStockDetailsById($id)
      {
            $data = $this->db->table('wh_production_stock')->select('wh_production_stock.*, wh_production_store.nameE as store_name, branch.nameE as branch_name, wh_items.nameE as item_nameE, wh_items.company_code as item_nameA, list_data.nameE as unit_name')
                  ->join('wh_production_store', 'wh_production_store.id=wh_production_stock.store_id', 'left')
                  ->join('branch', 'branch.id=wh_production_store.branch_id', 'left')
                  ->join('wh_items', 'wh_items.id=wh_production_stock.item_id', 'left')
                  ->join('list_data', 'list_data.id=wh_items.unit_id', 'left')
                  ->where(array('wh_production_stock.id' => $id))
                  ->get()
                  ->getRowArray();

            return $data;
      }

      public function finished_good_store()
      {
            $stores  = $this->db->table('wh_production_store')
                  ->select('*')
                  ->get()
                  ->getResult();
            $list = array('' => get_phrases(['select', 'store']));
            if ($stores) {
                  foreach ($stores as $value) {
                        $list[$value->id] = $value->nameE;
                  }
            }
            return  $list;
      }

      public function finished_good_list()
      {
            $stores  = $this->db->table('wh_items')
                  ->select('*')
                  ->get()
                  ->getResult();
            $list = array('' => get_phrases(['select', 'goods']));
            if ($stores) {
                  foreach ($stores as $value) {
                        $list[$value->id] = $value->nameE;
                  }
            }
            return  $list;
      }


      public function item_dropdown($pre_items)
      {
              $dbquery = $this->db->table('wh_items');
              $dbquery->select("CONCAT_WS(' ', nameE,'(',company_code,')') AS text,id");
              if($pre_items){
              $dbquery->whereNotIn('id', $pre_items);
              }
              $dbquery->where('status', 1);
              $dbquery->orderBy('nameE', 'asc');
              $query = $dbquery->get();
              return $result =  $query->getResult();
      }


      public function StocktransStoreList($from_store)
      {
              $dbquery = $this->db->table('wh_production_store');
              $dbquery->select("nameE AS text,id");
              if($from_store){
              $dbquery->where('id !=', $from_store);
              }
              $dbquery->where('status', 1);
              $dbquery->orderBy('nameE', 'asc');
              $query = $dbquery->get();
              return $result =  $query->getResult();
      }

      public function bdtaskt1m12_09_getItemBatches($product_id,$store_id)
      {
              $dbquery = $this->db->table('wh_receive_details a');
              $dbquery->select("a.batch_no AS text,a.batch_no AS id,sum(avail_qty) as avail_qty");
              $dbquery->where('a.item_id', $product_id);
              $dbquery->where('a.store_id', $store_id);
              $dbquery->having('avail_qty >', 0);
              $dbquery->orderBy('a.receive_id', 'asc');
              $dbquery->groupBy('a.batch_no');
              $query = $dbquery->get();
              return $result =  $query->getResult();
      }

      public function bdtaskt1m12_09_getBatchstock($product_id,$store_id,$batch_id)
      {
            $dbquery = $this->db->table('wh_receive_details a');
            $dbquery->select("a.batch_no AS text,a.batch_no AS id,sum(avail_qty) as avail_qty,bag_size");
            $dbquery->where('a.item_id', $product_id);
            $dbquery->where('a.store_id', $store_id);
            $dbquery->where('a.batch_no', $batch_id);
            $dbquery->orderBy('a.receive_id', 'asc');
            $dbquery->groupBy('a.batch_no');
            $query = $dbquery->get();
            return $result =  $query->getRow();
      }

      public function bdtaskt1m12_10_getSaveStocktransfer(array $data = [])
      {
            $main_transfer = $this->db->table('store_transfer_main')->insert($data);
            if($main_transfer){
            $product_id = $this->request->getVar('product_id');
            $to_store   = $this->request->getVar('to_store');
            $batch_id   = $this->request->getVar('batch_id');
            $trans_qty  = $this->request->getVar('trans_qty');
            $bag_size   = $this->request->getVar('bag_size');
            $total_kg   = $this->request->getVar('total_kg');
            for($i = 0;$i < count($product_id);$i++){
             $item_id       = $product_id[$i];
             $t_store       = $to_store[$i];
             $batch         = $batch_id[$i];
             $tr_qty        = $trans_qty[$i];
             $bag_weight    = $bag_size[$i];
             $total_weight  = $total_kg[$i];

             $pro_stock_from = $this->storewiseItemstock($item_id,$data['from_store']);
             $pro_stock_to   = $this->storewiseItemstock($item_id,$t_store);

             $batch_stock_from   = $this->batchwiseItemstock($item_id,$data['from_store'],$batch);
             $batch_stock_to     = $this->batchwiseItemstock($item_id,$t_store,$batch);
             $details  = array(
                  'transfer_id' => $data['transfer_id'],
                  'from_store'  => $data['from_store'],
                  'to_store'    => $t_store,
                  'product_id'  => $item_id,
                  'bag_weight'  => ($bag_weight?$bag_weight:0),
                  'batch_id'    => $batch,
                  'transfer_qty'=> ($tr_qty?$tr_qty:0),
                  'total_weight'=> ($total_weight?$total_weight:0)
             );

             $production_from_storeup = array(
                  'stock'    => ($pro_stock_from?$pro_stock_from->stock:0) - ($tr_qty?$tr_qty:0),
                  'stock_out'=> ($pro_stock_from?$pro_stock_from->stock_out:0) + ($tr_qty?$tr_qty:0),
             );

             $production_to_storeup = array(
                  'stock'    => ($pro_stock_to?$pro_stock_to->stock:0) + ($tr_qty?$tr_qty:0),
                  'stock_in' => ($pro_stock_to?$pro_stock_to->stock_in:0) + ($tr_qty?$tr_qty:0),
             );

             $production_to_storeinsert = array(
                  'store_id' => $t_store,
                  'item_id'  => $item_id,
                  'stock'    => ($pro_stock_to?$pro_stock_to->stock:0) + ($tr_qty?$tr_qty:0),
                  'stock_in' => ($pro_stock_to?$pro_stock_to->stock_in:0) + ($tr_qty?$tr_qty:0),
             );

             $receive_from_store = array(
                  'item_id'    => $item_id,
                  'avail_qty'  => ($batch_stock_from?$batch_stock_from->avail_qty:0) - ($tr_qty?$tr_qty:0),
                  'adjust_out' => ($batch_stock_from?$batch_stock_from->adjust_out:0) + ($tr_qty?$tr_qty:0),
             );

             $receive_to_storeup = array(
                  'store_id'   => $t_store,
                  'item_id'    => $item_id,
                  'avail_qty'  => ($batch_stock_to?$batch_stock_to->avail_qty:0) + ($tr_qty?$tr_qty:0),
                  'adjust_in'  => ($batch_stock_to?$batch_stock_to->adjust_in:0) + ($tr_qty?$tr_qty:0),
             );

             $receive_to_storeinsert = array(
                  'store_id'   => $t_store,
                  'item_id'    => $item_id,
                  'avail_qty'  => ($tr_qty?$tr_qty:0),
                  'adjust_in'  => ($tr_qty?$tr_qty:0),
                  'batch_no'   => $batch,
                  'prod_date'  => ($batch_stock_from?$batch_stock_from->prod_date:date('Y-m-d'))
             );


             if(!empty($item_id) && $tr_qty > 0){
              $this->db->table('store_transfer_details')->insert($details); 

               $this->db->table('wh_receive_details')->where('item_id', $item_id)->where('store_id', $data['from_store'])->where('batch_no',$batch)->update($receive_from_store);

               if(empty($batch_stock_to)){
                     $this->db->table('wh_receive_details')->insert($receive_to_storeinsert);
               }else{
                  $this->db->table('wh_receive_details')->where('item_id', $item_id)->where('store_id', $t_store)->where('batch_no',$batch)->update($receive_to_storeup);      
               }

              $this->db->table('wh_production_stock')->where('item_id', $item_id)->where('store_id', $data['from_store'])->update($production_from_storeup); 
              
              if(empty($pro_stock_to)){
                  $this->db->table('wh_production_stock')->insert($production_to_storeinsert);
              }else{
                  $this->db->table('wh_production_stock')->where('item_id', $item_id)->where('store_id', $t_store)->update($production_to_storeup);      
              }
             }

            }
           return true;
         }else{
          return false;
      }

            
      }

      public function storewiseItemstock($product_id,$store_id)
      {
            $builder = $this->db->table('wh_production_stock');
            $builder->select("*");
            $builder->where("item_id",$product_id);
            $builder->where("store_id",$store_id);
            $query   =  $builder->get();
            return $records =   $query->getRow();
      }

      public function batchwiseItemstock($product_id,$store_id,$batch_id)
      {
            $builder = $this->db->table('wh_receive_details');
            $builder->select("*");
            $builder->where("item_id",$product_id);
            $builder->where("store_id",$store_id);
            $builder->where("batch_no",$batch_id);
            $query   =  $builder->get();
            return $records =   $query->getRow();
      }

      public function setting_info()
      {
          return $settingdata = $this->db->table('setting')->select('*')->get()->getRow();
      }

      public function main_byid($id)
      {
            $queryb = $this->db->table('store_transfer_main st');
            $queryb->select("st.*,u.fullname as create_by,s.nameE as storename");
            $queryb->join('user u', 'u.emp_id=st.created_by', 'left');
            $queryb->join('wh_production_store s', 's.id=st.from_store', 'left');
            $queryb->where("st.transfer_id",$id);
            $query   =  $queryb->get();
            return $records =   $query->getRow();
      }
      
      public function details_byid($id)
      {
            $queryb = $this->db->table('store_transfer_details st');
            $queryb->select("st.*,CONCAT_WS(' ',u. nameE,'(',u.company_code,')') AS item_name,s.nameE as to_storename");
            $queryb->join('wh_items u', 'u.id=st.product_id', 'left');
            $queryb->join('wh_production_store s', 's.id=st.to_store', 'left');
            $queryb->where("st.transfer_id",$id);
            $query   =  $queryb->get();
            return $records =   $query->getResult();
      }

}
