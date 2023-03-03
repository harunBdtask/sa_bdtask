<?php namespace App\Modules\Production\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1MaterialApproveModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('wh_request_approve', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('wh_request_approve', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('wh_request_approve', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('wh_request_approve', 'delete')->access();

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
           $searchQuery = " (wh_machine_requests.voucher_no like '%".$searchValue."%' OR wh_machine_requests.date like '%".$searchValue."%' ) ";
        }
        if($production_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_machine_requests.production_id = '".$production_id."' ) ";
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
        $builder3->select("wh_machine_requests.*, wh_production.voucher_no as production_plan, wh_machine_store.nameE as sub_store_name,wh_machine_return.isCollected as returnCollected");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }    
        if(session('store_id') !='' && session('store_id') !='0' ){
            $builder3->where('wh_machine_store.id', session('store_id') );
        }                   
        $builder3->join('wh_machine_return', 'wh_machine_return.request_id=wh_machine_requests.id','left');
        $builder3->join('wh_production', 'wh_production.id=wh_machine_requests.production_id','left');
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
        
        $details = get_phrases(['request', 'approve']);
        $update = get_phrases(['update']);
        $delete = get_phrases(['delete']);
        $collect = get_phrases(['collect']);

        foreach($records as $record ){ 
            $button = '';
            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="'.$details.'" data-id="'.$record['id'].'"><i class="fa fa-eye"></i></a>';
            
            /*$button .='<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .='<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';*/
            if($record['isApproved']==1){
                $status = '<div class="badge badge-success" >'.get_phrases(['approved']).'</div>';
                /*if($record['isCollected']==1){
                    $status .= ' <div class="badge badge-primary" >'.get_phrases(['collected']).'</div>';

                    if($record['isReturned']==1){
                        $status .= ' <div class="badge badge-secondary" >'.get_phrases(['returned']).'</div>';
                        if($record['returnCollected']==1){
                            $status .= ' <div class="badge badge-primary" >'.get_phrases(['received']).'</div>';
                        } else {
                            $status .= ' <div class="badge badge-primary" >'.get_phrases(['not','received']).'</div>';
                            $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionCollect mr-2 custool" title="'.$collect.'" data-id="'.$record['id'].'"><i class="fa fa-cart-arrow-down"></i></a>';
                        }
                    }
                } else {
                    $status .= ' <div class="badge badge-info">'.get_phrases(['not','collected']).'</div>';
                }*/
            } else if($record['isApproved']==2){
                $status = '<div class="badge badge-danger text-white">'.get_phrases(['rejected']).'</div>';
            } else {
                $status = '<div class="badge badge-info text-white">'.get_phrases(['not','approved']).'</div>';
                /*if($record['isResend']==1){
                    $status .= ' <div class="badge badge-warning" >'.get_phrases(['resend']).'</div>';
                }*/
                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC actionApprove mr-2 custool" title="'.$details.'" data-id="'.$record['id'].'"><i class="fa fa-check"></i></a>';
            }

            $data[] = array( 
                'id'                    => $record['id'],
                'voucher_no'            => $record['voucher_no'],
                'date'                  => $record['date'],
                'production_plan'       => $record['production_plan'],
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
        $data = $this->db->table('wh_machine_requests')->select('wh_machine_requests.*, DATE_FORMAT(wh_machine_requests.date, "%d/%m/%Y") date, wh_production.voucher_no as production_plan, wh_machine_store.nameE as sub_store_name, e.first_name as request_by_name')                        
            ->join('wh_production', 'wh_production.id=wh_machine_requests.production_id','left')
            ->join('wh_machine_store', 'wh_machine_store.id=wh_machine_requests.sub_store_id','left')
            ->join('hrm_employees e', 'e.employee_id=wh_machine_requests.request_by','left')
            ->where( array('wh_machine_requests.id'=>$id) )
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
        $result = $this->db->table('wh_machine_request_details')->set('return_qty', (($qty)?$qty:0), FALSE)->set('avail_qty', 'avail_qty-'.(($qty)?$qty:0), FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }
    public function bdtaskt1m12_08_decreaseSubStock($qty, $where){
        $result = $this->db->table('wh_machine_stock')->set('stock', 'stock-'.(($qty)?$qty:0), FALSE)->set('stock_in', 'stock_in-'.(($qty)?$qty:0), FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_06_getItemStockDetailsById($item_id){
        $data = $this->db->table('wh_material_receive_details d')->select('d.id, r.voucher_no, d.batch_no, d.expiry_date, ROUND(d.avail_qty,2) as avail_qty')                        
            ->join('wh_material_receive r', 'r.id=d.receive_id','left')
            ->where( array('d.item_id'=>$item_id ) )
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
        $result = $this->db->table('wh_material_stock')->set('stock', 'stock-'.(($qty)?$qty:0), FALSE)->set('stock_out', 'stock_out+'.(($qty)?$qty:0), FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }
    public function bdtaskt1m12_07_decreaseReceiveStock($qty, $where, $receive_type){
        $table = 'wh_material_receive_details';
        if($receive_type == 'tran'){
            $table = 'wh_material_transfer_details';
        }
        $result = $this->db->table($table)->set('used_qty', 'used_qty+'.(($qty)?$qty:0), FALSE)->set('avail_qty', 'avail_qty-'.(($qty)?$qty:0), FALSE)->where($where)->update(); 

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_03_getItemRequestDetailsById($id){
        $data = $this->db->table('wh_machine_requests')->select('wh_machine_requests.*, DATE_FORMAT(wh_machine_requests.date, "%d/%m/%Y") date, wh_production.voucher_no as production_plan, wh_machine_store.nameE as sub_store_name, IF(wh_machine_requests.isApproved=1,"Approved",IF(wh_machine_requests.isApproved=2,"Rejected","Not Approved")) as status_text, IF(wh_machine_requests.isCollected=1,"Collected","") as status_collected')
            ->join('wh_production', 'wh_production.id=wh_machine_requests.production_id','left')
            ->join('wh_machine_store', 'wh_machine_store.id=wh_machine_requests.sub_store_id','left')
            ->where( array('wh_machine_requests.id'=>$id) )
            ->get()
            ->getRowArray();

        return $data;
    }
}
?>