<?php namespace App\Modules\Production\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1BagRequestModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('bag_request', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('bag_request', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('bag_request', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('bag_request', 'delete')->access();

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
           $searchQuery = " (wh_bag_requests.voucher_no like '%".$searchValue."%' OR wh_bag_requests.date like '%".$searchValue."%' ) ";
        }
        if($production_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_bag_requests.production_id = '".$production_id."' ) ";
        }
        if($sub_store_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_bag_requests.sub_store_id = '".$sub_store_id."' ) ";
        }
        if($voucher_no != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_bag_requests.voucher_no = '".$voucher_no."' ) ";
        }
        if($date != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_bag_requests.date = '".$date."' ) ";
        }
        
        ## Fetch records
        $builder3 = $this->db->table('wh_bag_requests');
        $builder3->select("wh_bag_requests.*, wh_receive.voucher_no as production_no, wh_machine_store.nameE as sub_store_name,wh_machine_return.isCollected as returnCollected");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }
        if(session('store_id') !=''  && session('store_id') !='0'){
            $builder3->where('wh_machine_store.id', session('store_id') );
        }         
        $builder3->join('wh_machine_return', 'wh_machine_return.request_id=wh_bag_requests.id','left');
        $builder3->join('wh_receive', 'wh_receive.id=wh_bag_requests.production_id','left');
        $builder3->join('wh_machine_store', 'wh_machine_store.id=wh_bag_requests.sub_store_id','left');
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
        
        $details = get_phrases(['view', 'details']);
        $update = get_phrases(['update','request']);
        $delete = get_phrases(['delete','request']);
        $print = get_phrases(['print']);
        $collect = get_phrases(['collect','item']);
        $return = get_phrases(['return','item']);
        $resend = get_phrases(['resend','request']);

        foreach($records as $record ){ 
            $button = '';

            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="'.$details.'" data-id="'.$record['id'].'"><i class="far fa-eye"></i></a>';
            /*$button .='<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';*/
            if($record['isApproved']==1){
                $status = '<div class="badge badge-success" >'.get_phrases(['approved']).'</div>';
                
            } else if($record['isApproved']==2){
                $status = '<div class="badge badge-danger text-white">'.get_phrases(['rejected']).'</div>';
                
                /*$button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-warning-soft btnC actionResend custool mr-2" title="'.$resend.'" data-id="'.$record['id'].'"><i class="fa fa-paper-plane"></i></a>';*/
            } else {
                $status = '<div class="badge badge-info text-white">'.get_phrases(['not','approved']).'</div>';
                /*if($record['isResend']==1){
                    $status .= ' <div class="badge badge-warning" >'.get_phrases(['resend']).'</div>';
                }*/
                $button .= (!$this->hasDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool mr-2" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';
            }

            $data[] = array( 
                'id'                    => $record['id'],
                'voucher_no'            => $record['voucher_no'],
                'date'                  => $record['date'],
                'production_no'         => $record['production_no'],
                'sub_store_name'        => $record['sub_store_name'],
                'status'                => $status,
                'button'                => $button,
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
    
    public function bdtaskt1m12_03_getItemRequestDetailsById($id){
        $data = $this->db->table('wh_bag_requests br')->select('br.*, DATE_FORMAT(br.date, "%d/%m/%Y") date, r.voucher_no as production_no, s.nameE as sub_store_name, IF(br.isApproved=1,"Approved",IF(br.isApproved=2,"Rejected","Not Approved")) as status_text, IF(br.isCollected=1,"Collected","") as status_collected, 
            ( SELECT GROUP_CONCAT(fg.nameE) FROM wh_receive_details rd
              LEFT OUTER JOIN wh_items fg ON(fg.id=rd.item_id)
              WHERE rd.receive_id = r.id ) as finished_goods')
            ->join('wh_receive r', 'r.id=br.production_id','left')
            ->join('wh_machine_store s', 's.id=br.sub_store_id','left')
            ->where( array('br.id'=>$id) )
            ->get()
            ->getRowArray();

        return $data;
    }

    public function bdtaskt1m12_04_updateStock($qty, $where){
        $result = $this->db->table('wh_material_stock')->set('stock', 'stock+'.$qty, FALSE)->set('stock_in', 'stock_in+'.$qty, FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_05_increaseStock($qty, $where){
        $result = $this->db->table('wh_machine_stock')->set('stock', 'stock+'.(($qty)?$qty:0), FALSE)->set('stock_in', 'stock_in+'.(($qty)?$qty:0), FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_06_decreaseStock($qty, $where){
        $result = $this->db->table('wh_material_stock')->set('stock', 'stock-'.(($qty)?$qty:0), FALSE)->set('stock_out', 'stock_out+'.(($qty)?$qty:0), FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }
    public function bdtaskt1m12_07_decreaseReceiveStock($qty, $where, $receive_type){
        if($receive_type == 'rec'){
            $table = 'wh_material_receive_details';
        } else {
            $table = 'wh_material_transfer_details';
        }
        $result = $this->db->table($table)->set('used_qty', 'used_qty+'.(($qty)?$qty:0), FALSE)->set('avail_qty', 'avail_qty-'.(($qty)?$qty:0), FALSE)->where($where)->update(); 

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_08_checkAvailQty($request_id, $item_id, $return_qty){
        $where = array('request_id'=> $request_id, 'item_id'=> $item_id);
        $result = $this->db->table('wh_bag_request_details')->where($where)->where(" (avail_qty - adjust_in) < ".$return_qty)->get()->getRow();

        return $result;
    }

    public function bdtaskt1m12_09_getItemDetailsById($item_id){
        $data = $this->db->table('wh_bag')->select('wh_bag.*, list_data.nameE as unit_name')
            ->join('list_data', 'list_data.id=wh_bag.unit_id','left')
            ->where( array('wh_bag.finish_goods'=>$item_id) )
            ->get()
            ->getRowArray();

        return $data;
    }

    public function bdtaskt1m12_10_getItemProductionItemDetailsById($production_id){

        $data = $this->db->table('wh_receive_details')->select('wh_receive_details.item_id, wh_receive_details.act_bags as qty,list_data.nameE as unit_name, wh_bag.id as bag_id, wh_items.id, wh_items.nameE as item_name')
            ->join('wh_bag', 'wh_bag.finish_goods=wh_receive_details.item_id','left')
            ->join('wh_items', 'wh_items.id=wh_receive_details.item_id','left')
            ->join('list_data', 'list_data.id=wh_bag.unit_id','left')
            ->where( array('wh_receive_details.receive_id'=>$production_id) )
            ->get()
            ->getResultArray();

        return $data;
    }

}
?>