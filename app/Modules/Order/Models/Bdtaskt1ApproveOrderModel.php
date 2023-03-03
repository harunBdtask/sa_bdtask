<?php namespace App\Modules\Order\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1ApproveOrderModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('wh_order_approve', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('wh_order_approve', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('wh_order_approve', 'update')->access();
        //$this->hasDeleteAccess = $this->permission->method('wh_order_approve', 'delete')->access();

    }

    public function bdtaskt1m12_02_getAll($postData=null){
         $response = array();
         ## Read value
      
        @$dealer_id = $postData['dealer_id'];
        @$doctor_id = $postData['doctor_id'];
        @$store_id = $postData['store_id'];
        @$voucher_no = $postData['voucher_no'];
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
           $searchQuery = " (r.voucher_no like '%".$searchValue."%' OR r.date like '%".$searchValue."%' ) ";
        }
        if($dealer_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( r.dealer_id = '".$dealer_id."' ) ";
        }
        if($doctor_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( r.doctor_id = '".$doctor_id."' ) ";
        }
        if($store_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( r.sub_store_id = '".$store_id."' ) ";
        }
        if($voucher_no != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( r.voucher_no = '".$voucher_no."' ) ";
        }
        if($date != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( r.date = '".$date."' ) ";
        }
        ## Fetch records
        $builder3 = $this->db->table('wh_order r');
        $builder3->select("r.*, d.name as dealer_name, w.nameE as sub_store_name, e.nameE as request_by_name, f.nameE as doctor_name");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }   
        if(session('store_id') !='' && session('store_id') !='0'){
            $builder3->where('w.id', session('store_id') );
        }                   
        $builder3->join('wh_dealer_info d', 'd.id=r.dealer_id','left');
        $builder3->join('wh_sale_store w', 'w.id=r.sub_store_id','left');
        $builder3->join('employees e', 'e.emp_id=r.request_by','left');
        $builder3->join('employees f', 'f.emp_id=r.doctor_id','left');
        //$builder3->join('patient_file p', 'p.patient_id=r.patient_id','left');
        if(session('branchId') >0 ){
            $builder3->where("w.branch_id", session('branchId'));
        }
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
        $collect = get_phrases(['receive']);
        $view = get_phrases(['view','details']);

        foreach($records as $record ){ 
            $button = '';
            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionView custool mr-1" title="'.$view.'" data-id="'.$record['id'].'"><i class="fa fa-eye"></i></a>';
            
            /*$button .='<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .='<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';*/
            if($record['isApproved']==1){
                $status = '<div class="badge badge-success" >'.get_phrases(['approved']).'</div>';
                if($record['isCollected'] ==1){
                    $status .= ' <div class="badge badge-primary" >'.get_phrases(['collected']).'</div>';
                } else {
                    $status .= ' <div class="badge badge-info ">'.get_phrases(['not','collected']).'</div>';
                }
                if($record['isReturned'] ==1){
                    $status .= ' <div class="badge badge-secondary" >'.get_phrases(['returned']).'</div>';
                    if($record['isReceived'] ==1){
                        $status .= ' <div class="badge badge-primary" >'.get_phrases(['received']).'</div>';
                    } else {
                        $status .= ' <div class="badge badge-info" >'.get_phrases(['not','received']).'</div>';

                        $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionCollect custool" title="'.$collect.'" data-id="'.$record['id'].'"><i class="fa fa-cart-arrow-down"></i></a>';
                    }

                }
            } else if($record['isApproved']==2){
                $status = '<div class="badge badge-danger text-white">'.get_phrases(['rejected']).'</div>';
            } else {
                $status = '<div class="badge badge-info text-white">'.get_phrases(['not','approved']).'</div>';
                if($record['isResend']==1){
                    $status .= ' <div class="badge badge-warning" >'.get_phrases(['resend']).'</div>';
                }
                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC actionPreview mr-2 custool" title="'.$details.'" data-id="'.$record['id'].'"><i class="fa fa-check"></i></a>';
            }

            $data[] = array( 
                'id'                    => $record['id'],
                'voucher_no'            => $record['voucher_no'],
                'date'                  => $record['date'],
                'dealer_name'           => $record['dealer_name'],
                'sub_store_name'        => $record['sub_store_name'],
                'request_by_name'       => $record['request_by_name'],
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
    
    public function bdtaskt1m12_03_getRequestApproveDetailsById($id){
        $data = $this->db->table('wh_order')->select('wh_order.*, DATE_FORMAT(wh_order.date, "%d/%m/%Y") date, wh_dealer_info.name as dealer_name, wh_sale_store.nameE as sub_store_name, e.nameE as request_by_name')                        
            ->join('wh_dealer_info', 'wh_dealer_info.id=wh_order.dealer_id','left')
            ->join('wh_sale_store', 'wh_sale_store.id=wh_order.sub_store_id','left')
            ->join('employees e', 'e.emp_id=wh_order.request_by','left')
            ->where( array('wh_order.id'=>$id) )
            ->get()
            ->getRowArray();

        return $data;
    }

    public function bdtaskt1m12_04_increaseStock($qty, $where){
        $result = $this->db->table('wh_sale_stock')->set('stock', 'stock+'.(($qty)?$qty:0), FALSE)->set('stock_out', 'stock_out-'.(($qty)?$qty:0), FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_08_increaseQty($qty, $where, $request_type){
        if($request_type == 'req'){
            $result = $this->db->table('wh_item_request_details')->set('used_qty', 'used_qty-'.(($qty)?$qty:0), FALSE)->set('avail_qty', 'avail_qty+'.(($qty)?$qty:0), FALSE)->where($where)->update();
        } else {
            $result = $this->db->table('wh_item_transfer_details')->set('used_qty', 'used_qty-'.(($qty)?$qty:0), FALSE)->set('avail_qty', 'avail_qty+'.(($qty)?$qty:0), FALSE)->where($where)->update();
        }

        return $this->db->affectedRows();
    }


    public function bdtaskt1m12_06_getItemReceiveDetails($item_id, $sub_store_id){
        $data = $this->db->table('wh_item_request_details d')->select('d.id, r.voucher_no, d.batch_no, d.expiry_date, ROUND(d.avail_qty,2) as avail_qty')                        
            ->join('wh_item_requests r', 'r.id=d.request_id','left')
            ->where( array('r.isApproved'=> 1, 'r.isCollected'=> 1, 'r.sub_store_id'=>$sub_store_id, 'd.item_id'=>$item_id ) )
            ->get()
            ->getResult();//, 'ROUND(d.avail_qty,2) >'=>0

        return $data;
    }

    public function bdtaskt1m12_07_getItemTransferDetails($item_id, $sub_store_id){
        $data = $this->db->table('wh_item_transfer_details d')->select('d.id, r.voucher_no, d.batch_no, d.expiry_date, ROUND(d.avail_qty,2) as avail_qty')                        
            ->join('wh_item_transfer r', 'r.id=d.request_id','left')
            ->where( array('r.isApproved'=> 1, 'r.isCollected'=> 1, 'r.from_store_id'=>$sub_store_id, 'd.item_id'=>$item_id) )
            ->get()
            ->getResult();//, 'ROUND(d.avail_qty,2) >'=>0

        return $data;
    }


}
?>