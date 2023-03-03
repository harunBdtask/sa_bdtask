<?php namespace App\Modules\Order\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1ItemOrderModel extends Model
{
    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('item_order_request', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('item_order_request', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('item_order_request', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('item_order_request', 'delete')->access();
    }

    public function bdtaskt1m12_02_getAll($postData=null){
         $response = array();
         ## Read value
      
        @$store_id = $postData['store_id'];
        @$dealer_id = $postData['dealer_id'];
        @$doctor_id = $postData['doctor_id'];
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
           $searchQuery = " (r.voucher_no like '%".$searchValue."%' OR r.date like '%".$searchValue."%' ) ";
        }
        if($store_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( r.sub_store_id = '".$store_id."' ) ";
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
        $builder3->join('wh_dealer_info d', 'd.id=r.dealer_id','left');
        $builder3->join('wh_sale_store w', 'w.id=r.sub_store_id','left');
        $builder3->join('employees e', 'e.emp_id=r.request_by','left');
        $builder3->join('employees f', 'f.emp_id=r.doctor_id','left');
        //$builder3->join('patient_file p', 'p.patient_id=r.patient_id','left');
        if(session('user_role') != 1){
            $builder3->where('r.request_by', session('id'));
        }
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
        
        $details = get_phrases(['view', 'details']);
        $update = get_phrases(['update']);
        $delete = get_phrases(['delete']);
        $print = get_phrases(['print']);
        $collect = get_phrases(['collect']);
        $return = get_phrases(['return','item']);
        $resend = get_phrases(['resend','request']);

        foreach($records as $record ){ 
            $button = '';
            $status = '';

            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="'.$details.'" data-id="'.$record['id'].'"><i class="far fa-eye"></i></a>';
            /*$button .='<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';*/

            if($record['isApproved']==1){
                $status .= '<div class="badge badge-success" >'.get_phrases(['approved']).'</div>';
                
                if($record['isCollected'] ==1){
                    $status .= ' <div class="badge badge-primary" >'.get_phrases(['collected']).'</div>';
                    
                    $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-violet-soft btnC actionPrint custool mr-2" title="'.$print.'" data-id="'.$record['id'].'"><i class="fa fa-print"></i></a>';
                    
                    if($record['isReturned'] !=1){
                        if($record['request_by'] == session('id') || session('isAdmin')==true){
                            $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionReturn custool" title="'.$return.'" data-id="'.$record['id'].'"><i class="fa fa-undo"></i></a>';
                        }
                    } else {
                        $status .= ' <div class="badge badge-secondary" >'.get_phrases(['returned']).'</div>';
                        
                        if($record['isReceived'] ==1){
                            $status .= ' <div class="badge badge-primary" >'.get_phrases(['received']).'</div>';
                            
                            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-purple-soft btnC actionPrintReturn custool" title="'.$print.'" data-id="'.$record['id'].'"><i class="fa fa-print"></i></a>';
                        } else {
                            $status .= ' <div class="badge badge-info" >'.get_phrases(['not','received']).'</div>';
                        }
                    }
                } else {
                    if($record['request_by'] == session('id') || session('isAdmin')==true){
                        $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionCollect custool mr-2" title="'.$collect.'" data-id="'.$record['id'].'"><i class="fa fa-cart-arrow-down"></i></a>';
                        $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-warning-soft btnC actionResend custool" title="'.$resend.'" data-id="'.$record['id'].'"><i class="fa fa-paper-plane"></i></a>';
                    }
                    
                    $status .= ' <div class="badge badge-info text-white">'.get_phrases(['not','collected']).'</div>';
                }
            } else if($record['isApproved']==2){
                $status .= '<div class="badge badge-danger" >'.get_phrases(['rejected']).'</div>';

                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-warning-soft btnC actionResend custool" title="'.$resend.'" data-id="'.$record['id'].'"><i class="fa fa-paper-plane"></i></a>';
            } else {
                $status .= '<div class="badge badge-info text-white">'.get_phrases(['not','approved']).'</div>';
                if($record['isResend']==1){
                    $status .= ' <div class="badge badge-warning" >'.get_phrases(['resend']).'</div>';
                }
                if($record['request_by'] == session('id') || session('isAdmin')==true){
                    $button .= (!$this->hasDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';
                }
            }

            $data[] = array( 
                'id'                    => $record['id'],
                'voucher_no'            => $record['voucher_no'],
                'date'                  => $record['date'],
                'dealer_name'           => $record['dealer_name'],
                'sub_store_name'        => $record['sub_store_name'],
                'request_by_name'       => $record['request_by_name'],
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
        $data = $this->db->table('wh_order i')->select('i.*, DATE_FORMAT(i.date, "%d/%m/%Y") as date, wh_dealer_info.name as dealer_name, wh_sale_store.nameE as sub_store_name, IF(i.isApproved=1,"Approved",IF(i.isApproved=2,"Rejected","Not Approved")) as status_text, e.nameE as request_by_name, DATE_FORMAT(i.return_date, "%d/%m/%Y") as return_date,i.return_voucher_no, IF(i.isCollected=1,"Collected","") as status_collected, IF(i.isReceived=1,"Returned","") as status_returned')                        
            ->join('wh_dealer_info', 'wh_dealer_info.id=i.dealer_id','left')
            ->join('wh_sale_store', 'wh_sale_store.id=i.sub_store_id','left')
            ->join('employees e', 'e.emp_id=i.request_by','left')
            ->where( array('i.id'=>$id) )
            ->get()
            ->getRowArray();

        return $data;
    }

    public function bdtaskt1m12_04_updateStock($qty, $where){
        $result = $this->db->table('wh_sale_stock')->set('stock', 'stock-'.$qty, FALSE)->set('stock_out', 'stock_out+'.$qty, FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_08_decreaseStock($qty, $where, $request_type){
        if($request_type == 'req'){
            $result = $this->db->table('wh_item_request_details')->set('used_qty', 'used_qty+'.(($qty)?$qty:0), FALSE)->set('avail_qty', 'avail_qty-'.(($qty)?$qty:0), FALSE)->where($where)->update();
        } else {
            $result = $this->db->table('wh_item_transfer_details')->set('used_qty', 'used_qty+'.(($qty)?$qty:0), FALSE)->set('avail_qty', 'avail_qty-'.(($qty)?$qty:0), FALSE)->where($where)->update();
        }

        return $this->db->affectedRows();
    }

    public function bdtaskt1m4_05_getItemDetailsById($id){
        $data = $this->db->table('wh_items')->select('wh_items.*, wh_categories.nameE as cat_name, list_data.nameE as unit_name')                        
                        ->join('wh_categories', 'wh_categories.id=wh_items.cat_id','left')
                        ->join('list_data', 'list_data.id=wh_items.unit_id','left')
                        ->where( array('wh_items.id'=>$id) )
                        ->get()
                        ->getRowArray();

        return $data;
    }


    public function bdtaskt1m1_08_getConsumptionAmount($request_id){
        $data = $this->db->table('wh_order_details rd')->select('SUM(rd.aqty*rd.price) as total_amount')      
                        ->where( array('rd.request_id'=>$request_id) )
                        ->get()
                        ->getRow();

        return $data;
    }

    public function bdtaskt1m1_09_getConsumptionReturnAmount($request_id){
        $data = $this->db->table('wh_order_details rd')->select('SUM(rd.return_qty*rd.price) as total_amount')      
                        ->where( array('rd.request_id'=>$request_id) )
                        ->get()
                        ->getRow();

        return $data;
    }

}
?>