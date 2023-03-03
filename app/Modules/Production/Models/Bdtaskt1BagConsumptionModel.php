<?php namespace App\Modules\Production\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1BagConsumptionModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('approve_bag_request', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('approve_bag_request', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('approve_bag_request', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('approve_bag_request', 'delete')->access();

    }

    public function bdtaskt1m12_02_getAll($postData=null){
         $response = array();
         ## Read value
      
        @$production_id = $postData['production_id'];
        @$sub_store_id = $postData['sub_store_id'];
        @$voucher_no = trim($postData['voucher_no']);
        @$date = $postData['date'];
        
        @$draw = $postData['draw'];
        @$start = $postData['start'];
        @$rowperpage = $postData['length']; // Rows display per page
        @$columnIndex = $postData['order'][0]['column']; // Column index
        @$columnName = $postData['columns'][$columnIndex]['data']; // Column name
        @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        @$searchValue = $postData['search']['value']; // Search value
        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
           $searchQuery = " (br.voucher_no like '%".$searchValue."%' OR br.date like '%".$searchValue."%' ) ";
        }
        if($production_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( br.production_id = '".$production_id."' ) ";
        }
        if($sub_store_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( br.sub_store_id = '".$sub_store_id."' ) ";
        }
        if($voucher_no != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( br.id = '".$voucher_no."' ) ";
        }
        if($date != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( br.date = '".$date."' ) ";
        }
        
        ## Fetch records
        $builder3 = $this->db->table('wh_bag_requests br');
        $builder3->select("br.*, r.voucher_no as production_no, s.nameE as sub_store_name");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }    
        if(session('store_id') !='' && session('store_id') !='0' ){
            $builder3->where('s.id', session('store_id') );
        }                   
        $builder3->join('wh_receive r', 'r.id=br.production_id','left');
        $builder3->join('wh_machine_store s', 's.id=br.sub_store_id','left');
        ## Total number of records with filtering
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        ## Total number of records without filtering
        $totalRecords = $builder3->countAll();
        if($searchQuery == ''){
           $totalRecords = $totalRecordwithFilter;
        }
        $data = array();
        
        $details = get_phrases(['request', 'approve']);
        $update = get_phrases(['update']);
        $delete = get_phrases(['delete']);
        $collect = get_phrases(['collect']);

        foreach($records as $record ){ 
            $button = '';
            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="'.$details.'" data-id="'.$record['id'].'"><i class="fa fa-eye"></i></a>';
            
            if($record['isApproved']==1){
                $status = '<div class="badge badge-success" >'.get_phrases(['approved']).'</div>';
                
            } else if($record['isApproved']==2){
                $status = '<div class="badge badge-danger text-white">'.get_phrases(['rejected']).'</div>';
            } else {
                $status = '<div class="badge badge-info text-white">'.get_phrases(['not','approved']).'</div>';
                
                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC actionApprove mr-2 custool" title="'.$details.'" data-id="'.$record['id'].'"><i class="fa fa-check"></i></a>';
            }

            $data[] = array( 
                'id'                    => $record['id'],
                'voucher_no'            => $record['voucher_no'],
                'date'                  => $record['date'],
                'production_no'         => $record['production_no'],
                'sub_store_name'        => $record['sub_store_name'],
                'status'                => $status,
                'button'                => $button
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
    
    public function bdtaskt1m12_03_getItemApproveDetailsById($id){
        $data = $this->db->table('wh_bag_requests br')->select('br.*, DATE_FORMAT(br.date, "%d/%m/%Y") date, r.voucher_no as production_no, s.nameE as sub_store_name, e.first_name as request_by_name, 
            ( SELECT GROUP_CONCAT(fg.nameE) FROM wh_receive_details rd
              LEFT OUTER JOIN wh_items fg ON(fg.id=rd.item_id)
              WHERE rd.receive_id = r.id ) as finished_goods')                        
            ->join('wh_receive r', 'r.id=br.production_id','left')
            ->join('wh_machine_store s', 's.id=br.sub_store_id','left')
            ->join('hrm_employees e', 'e.employee_id=br.request_by','left')
            ->where( array('br.id'=>$id) )
            ->get()
            ->getRowArray();

        return $data;
    }

    public function bdtaskt1m12_04_increaseStock($qty, $where){
        $result = $this->db->table('wh_material_stock')->set('stock', 'stock+'.(($qty)?$qty:0), FALSE)->set('stock_out', 'stock_out-'.(($qty)?$qty:0), FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }
    public function bdtaskt1m12_05_updateStock($qty, $where){
        $result = $this->db->table('wh_material_receive_details')->set('used_qty', 'used_qty-'.(($qty)?$qty:0), FALSE)->set('avail_qty', 'avail_qty+'.(($qty)?$qty:0), FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }
    public function bdtaskt1m12_07_decreaseStock($qty, $where){
        $result = $this->db->table('wh_bag_request_details')->set('return_qty', (($qty)?$qty:0), FALSE)->set('avail_qty', 'avail_qty-'.(($qty)?$qty:0), FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }
    public function bdtaskt1m12_08_decreaseSubStock($qty, $where){
        $result = $this->db->table('wh_machine_stock')->set('stock', 'stock-'.(($qty)?$qty:0), FALSE)->set('stock_in', 'stock_in-'.(($qty)?$qty:0), FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_06_getItemStockDetailsById($item_id){
        $data = $this->db->table('wh_bag_receive_details')->select('*')  
            ->where( array('item_id'=>$item_id, 'avail_qty >'=>0 ) )
            ->get()
            ->getResult(); //'ROUND(d.avail_qty, 2) >'=>0, 
        //echo get_last_query();exit;
        return $data;
    }

    public function bdtaskt1m12_05_increaseStock($qty, $where){
        $result = $this->db->table('wh_machine_stock')->set('stock', 'stock+'.(($qty)?$qty:0), FALSE)->set('stock_in', 'stock_in+'.(($qty)?$qty:0), FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_06_decreaseStock($qty, $where){
        $result = $this->db->table('wh_bag_stock')->set('stock', 'stock-'.(($qty)?$qty:0), FALSE)->set('stock_out', 'stock_out+'.(($qty)?$qty:0), FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_07_decreaseReceiveStock($item_id, $req_qty){
        $price = 0;
        $batch_no = '';
        $expiry_date = '';
        $barcode = '';
        $receive_details_id = '';
        $receive_avail_qty = '';

        $rem_qty = $req_qty;

        $batch_info = $this->bdtaskt1m12_06_getItemStockDetailsById($item_id);

        foreach($batch_info as $receive_details){
            if( $rem_qty >0 ){
                $qty = ( $receive_details->avail_qty >= $rem_qty)?$rem_qty:$receive_details->avail_qty;

                $where = array('id'=> $receive_details->id );
                $this->db->table('wh_bag_receive_details')->set('used_qty', 'used_qty+'.(($qty)?$qty:0), FALSE)->set('avail_qty', 'avail_qty-'.(($qty)?$qty:0), FALSE)->where($where)->update();

                $where2 = array('store_id'=> $receive_details->store_id, 'item_id'=> $item_id );
                $this->db->table('wh_bag_stock')->set('stock', 'stock-'.(($qty)?$qty:0), FALSE)->set('stock_out', 'stock_out+'.(($qty)?$qty:0), FALSE)->where($where2)->update();
                
                $rem_qty = $rem_qty - $qty;

                $price = ($receive_details->price >0 && $price >0)?(($price+$receive_details->price)/2):$receive_details->price;
                $batch_no .= empty($batch_no)?$receive_details->batch_no:','.$receive_details->batch_no;
                $expiry_date .= empty($expiry_date)?$receive_details->expiry_date:','.$receive_details->expiry_date;
                $barcode .= empty($barcode)?$receive_details->barcode:','.$receive_details->barcode;
                $receive_details_id .= empty($receive_details_id)?$receive_details->id:','.$receive_details->id;
                $receive_avail_qty .= empty($receive_avail_qty)?$receive_details->avail_qty:','.$receive_details->avail_qty;
                                    
            } else {
                break;
            }
        } 
       
        $data = array('price'=>$price,'batch_no'=>$batch_no,'expiry_date'=>$expiry_date,'barcode'=>$barcode,'receive_details_id'=>$receive_details_id,'receive_avail_qty'=>$receive_avail_qty);

        return $data;
    }

    /*public function bdtaskt1m12_07_decreaseReceiveStock($qty, $where, $receive_type){
        $table = 'wh_bag_receive_details';
        if($receive_type == 'tran'){
            $table = 'wh_bag_receive_details';
        }
        $result = $this->db->table($table)->set('used_qty', 'used_qty+'.(($qty)?$qty:0), FALSE)->set('avail_qty', 'avail_qty-'.(($qty)?$qty:0), FALSE)->where($where)->update(); 

        return $this->db->affectedRows();
    }*/

}
?>