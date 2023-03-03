<?php namespace App\Modules\Machine\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1ItemRequestModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('wh_machine_request', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('wh_machine_request', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('wh_machine_request', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('wh_machine_request', 'delete')->access();

    }

    public function bdtaskt1m12_02_getAll($postData=null){
         $response = array();
         ## Read value
      
        @$store_id = $postData['store_id'];
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
           $searchQuery = " (wh_machine_requests.voucher_no like '%".$searchValue."%' OR wh_machine_requests.date like '%".$searchValue."%' ) ";
        }
        if($store_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_machine_requests.main_store_id = '".$store_id."' ) ";
        }
        if($sub_store_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_machine_requests.sub_store_id = '".$sub_store_id."' ) ";
        }
        if($voucher_no != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_machine_requests.voucher_no = '".$voucher_no."' ) ";
        }
        if($date != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_machine_requests.date = '".$date."' ) ";
        }
        
        ## Fetch records
        $builder3 = $this->db->table('wh_machine_requests');
        $builder3->select("wh_machine_requests.*, wh_material_store.nameE as main_store_name, wh_machine_store.nameE as sub_store_name,wh_machine_return.isCollected as returnCollected");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }
        if(session('store_id') !=''  && session('store_id') !='0'){
            $builder3->where('wh_machine_store.id', session('store_id') );
        }      
        if(session('branchId') >0 ){
            $builder3->where("wh_material_store.branch_id", session('branchId'));
        }           
        $builder3->join('wh_machine_return', 'wh_machine_return.request_id=wh_machine_requests.id','left');
        $builder3->join('wh_material_store', 'wh_material_store.id=wh_machine_requests.main_store_id','left');
        $builder3->join('wh_machine_store', 'wh_machine_store.id=wh_machine_requests.sub_store_id','left');
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
                if($record['isCollected']==1){
                    $status .= ' <div class="badge badge-primary" >'.get_phrases(['collected']).'</div>';
                    $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-violet-soft btnC actionPrint custool mr-2" title="'.$print.'" data-id="'.$record['id'].'"><i class="fa fa-print"></i></a>';
                    if($record['isReturned']==1){
                        $status .= ' <div class="badge badge-secondary" >'.get_phrases(['returned']).'</div>';
                        if($record['returnCollected']==1){
                            $status .= ' <div class="badge badge-primary" >'.get_phrases(['received']).'</div>';
                        } else {
                            $status .= ' <div class="badge badge-primary" >'.get_phrases(['not','received']).'</div>';
                        }
                    } else if($record['request_by'] == session('id')){
                        $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionReturn custool mr-2" title="'.$return.'" data-id="'.$record['id'].'"><i class="fa fa-undo"></i></a>';
                    }
                } else {
                    $status .= ' <div class="badge badge-info">'.get_phrases(['not','collected']).'</div>';
                    if($record['request_by'] == session('id')){
                        $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionCollect custool mr-2" title="'.$collect.'" data-id="'.$record['id'].'"><i class="fa fa-cart-arrow-down"></i></a>';
                        $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-warning-soft btnC actionResend custool mr-2" title="'.$resend.'" data-id="'.$record['id'].'"><i class="fa fa-paper-plane"></i></a>';
                    }
                }
            } else if($record['isApproved']==2){
                $status = '<div class="badge badge-danger text-white">'.get_phrases(['rejected']).'</div>';
                
                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-warning-soft btnC actionResend custool mr-2" title="'.$resend.'" data-id="'.$record['id'].'"><i class="fa fa-paper-plane"></i></a>';
            } else {
                $status = '<div class="badge badge-info text-white">'.get_phrases(['not','approved']).'</div>';
                if($record['isResend']==1){
                    $status .= ' <div class="badge badge-warning" >'.get_phrases(['resend']).'</div>';
                }
                $button .= (!$this->hasDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool mr-2" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';
            }

            $data[] = array( 
                'id'                    => $record['id'],
                'voucher_no'            => $record['voucher_no'],
                'date'                  => $record['date'],
                'main_store_name' => $record['main_store_name'],
                'sub_store_name'   => $record['sub_store_name'],
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
        $data = $this->db->table('wh_machine_requests')->select('wh_machine_requests.*, DATE_FORMAT(wh_machine_requests.date, "%d/%m/%Y") date, wh_material_store.nameE as main_store_name, wh_machine_store.nameE as sub_store_name, IF(wh_machine_requests.isApproved=1,"Approved",IF(wh_machine_requests.isApproved=2,"Rejected","Not Approved")) as status_text, IF(wh_machine_requests.isCollected=1,"Collected","") as status_collected')
            ->join('wh_material_store', 'wh_material_store.id=wh_machine_requests.main_store_id','left')
            ->join('wh_machine_store', 'wh_machine_store.id=wh_machine_requests.sub_store_id','left')
            ->where( array('wh_machine_requests.id'=>$id) )
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
        $result = $this->db->table('wh_machine_request_details')->where($where)->where(" (avail_qty - adjust_in) < ".$return_qty)->get()->getRow();

        return $result;
    }

    public function bdtaskt1m12_09_getItemDetailsById($item_id){
        $data = $this->db->table('wh_material')->select('wh_material.*, list_data.nameE as unit_name')
            ->join('list_data', 'list_data.id=wh_material.unit_id','left')
            ->where( array('wh_material.id'=>$item_id) )
            ->get()
            ->getRowArray();

        return $data;
    }
}
?>