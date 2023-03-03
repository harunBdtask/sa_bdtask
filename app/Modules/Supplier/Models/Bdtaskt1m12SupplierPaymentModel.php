<?php namespace App\Modules\Supplier\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m12SupplierPaymentModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('wh_supplier_payment', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('wh_supplier_payment', 'create')->access();
        //$this->hasUpdateAccess = $this->permission->method('wh_supplier_payment', 'update')->access();
        //$this->hasDeleteAccess = $this->permission->method('wh_supplier_payment', 'delete')->access();

    }

    public function bdtaskt1m12_02_getAll($postData=null){
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
        if($searchValue != ''){
           $searchQuery = " (wh_material_receive.voucher_no like '%".$searchValue."%' OR wh_material_receive.date like '%".$searchValue."%' ) ";
        }
        ## Fetch records
        $builder3 = $this->db->table('wh_supplier_payment');
        $builder3->select("wh_supplier_payment.*, wh_material_store.nameE as store_name, wh_supplier_information.nameE as supplier_name, wh_material_receive.purchase_id");
        if($searchValue != ''){
           $builder3->where($searchQuery);
        }                 
        $builder3->join('wh_material_receive', 'wh_material_receive.id=wh_supplier_payment.receive_id','left');
        $builder3->join('wh_material_store', 'wh_material_store.id=wh_material_receive.store_id','left');
        $builder3->join('wh_supplier_information', 'wh_supplier_information.id=wh_material_receive.supplier_id','left');
        $builder3->join('wh_production', 'wh_production.id=wh_material_receive.purchase_id','left');
        if(session('branchId') >0 ){
            $builder3->where("wh_material_store.branch_id", session('branchId'));
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
        
        $payment = get_phrases(['payment', 'to','supplier']);
        //$update = get_phrases(['update']);
        //$delete = get_phrases(['delete']);
        $print = get_phrases(['print','payment','invoice']);
        $preview = get_phrases(['View','details']);

        foreach($records as $record ){ 
            $button = '';

            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="'.$preview.'" data-id="'.$record['id'].'" purchase-id="'.$record['purchase_id'].'" receive-id="'.$record['receive_id'].'"><i class="fa fa-eye"></i></a>';
            
            /*$button .='<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .='<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';*/
            
            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-purple-soft btnC printPreview mr-2 custool" title="'.$print.'" data-id="'.$record['id'].'" purchase-id="'.$record['purchase_id'].'" receive-id="'.$record['receive_id'].'"><i class="fa fa-print"></i></a>';
            /*if($record['paid_status']==1){
                $status = '<div class="badge badge-success" >'.get_phrases(['paid']).'</div> '; 
                $button .='<a href="javascript:void(0);" class="btn btn-purple-soft btnC printPreview mr-2 custool" title="'.$print.'" data-id="'.$record['purchase_id'].'" receive-id="'.$record['id'].'"><i class="fa fa-print"></i></a>';
            } else {
                $status = '<div class="badge badge-info text-white">'.get_phrases(['unpaid']).'</div>';
                $button .='<a href="javascript:void(0);" class="btn btn-success-soft btnC paymentPreview mr-2 custool" title="'.$payment.'" data-id="'.$record['purchase_id'].'"><i class="fa fa-check"></i></a>';
            }*/

            $data[] = array( 
                'id'                => $record['id'],
                'voucher_no'        => $record['voucher_no'],
                'paid_amount'       => $record['paid_amount'],
                'paid_date'         => $record['paid_date'],
                'supplier_name' => $record['supplier_name'],
                'store_name'    => $record['store_name'],
                //'status'            => $status,
                'button'            => $button
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
    
    public function bdtaskt1m12_03_getSupplierPaymentDetailsById($id){
        $data = $this->db->table('wh_production')->select('wh_production.*, DATE_FORMAT(wh_production.date, "%d/%m/%Y") as date, wh_material_store.nameE as store_name, wh_supplier_information.nameE as supplier_name,wh_material_receive.grand_total as receive_grand_total')                        
            ->join('wh_material_store', 'wh_material_store.id=wh_production.store_id','left')
            ->join('wh_supplier_information', 'wh_supplier_information.id=wh_production.supplier_id','left')
            ->join('wh_material_receive', 'wh_material_receive.purchase_id=wh_production.id','left')
            ->where( array('wh_production.id'=>$id) )
            ->get()
            ->getRowArray();

        return $data;
    }

    public function bdtaskt1m12_04_updateAmount($amount, $where){
        $result = $this->db->table('wh_supplier_credit')->set('paid_amount', 'paid_amount+'.$amount, FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_05_getReceiveVoucherAll(){
        $data = $this->db->table('wh_material_receive')->select('wh_material_receive.*')                        
            ->join('wh_supplier_credit', 'wh_supplier_credit.receive_id=wh_material_receive.id','left')
            ->where( array('wh_material_receive.status'=>1, 'wh_supplier_credit.paid_status'=>0, 'wh_supplier_credit.credit_amount >'=>0) )
            ->get()
            ->getResult();

        return $data;
    }
}
?>