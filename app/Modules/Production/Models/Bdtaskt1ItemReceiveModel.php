<?php namespace App\Modules\Production\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1ItemReceiveModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('production_entry', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('production_entry', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('production_entry', 'update')->access();
        //$this->hasDeleteAccess = $this->permission->method('production_entry', 'delete')->access();

    }

    public function bdtaskt1m12_02_getAll($postData=null){
         $response = array();
         ## Read value
      
        @$store_id = $postData['store_id'];
        @$machine_id = $postData['machine_id'];
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
           $searchQuery = " (p.voucher_no like '%".$searchValue."%' OR p.date like '%".$searchValue."%' OR ms.nameE like '%".$searchValue."%' ) ";
        }
        if($store_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( p.store_id = '".$store_id."' ) ";
        }
        if($machine_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( p.machine_id = '".$machine_id."' ) ";
        }
        if($voucher_no != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( p.id = '".$voucher_no."' ) ";
        }
        if($date != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( p.date = '".$date."' ) ";
        }
       
        ## Fetch records
        $builder3 = $this->db->table('wh_production p');
        $builder3->select("p.*, ps.nameE as store_name, ms.nameE as machine_name, r.isApproved");//, mr.isApproved as rmApproved, br.isApproved as bagApproved
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }                 
        $builder3->join('wh_production_store ps', 'ps.id=p.store_id','left');
        $builder3->join('wh_machine_store ms', 'ms.id=p.machine_id','left');
        $builder3->join('wh_receive r', 'r.production_id=p.id','left');
        //$builder3->join('wh_machine_requests mr', 'mr.production_id=p.id','left');
        //$builder3->join('wh_bag_requests br', 'br.production_id=p.id','left');
        $builder3->where('p.isApproved', 1);
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
        $update = get_phrases(['receive','item']);
        $return = get_phrases(['return','item']);
        $approve = get_phrases(['approve']);
        $delete = get_phrases(['delete']);
        $print = get_phrases(['print','preview']);

        foreach($records as $record ){ 
            $button = '';
            $status = '';
            $received_status = '';

            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="'.$details.'" data-id="'.$record['id'].'"><i class="far fa-eye"></i></a>';

            if($record['received']==1){
                $received_status .= '<div class="badge badge-primary" >'.get_phrases(['goods','received']).'</div> ';
                if($record['isApproved']==1){
                    $status .= '<div class="badge badge-success" >'.get_phrases(['approved']).'</div> ';
                    /*if($record['returned']==1){
                        $status .= '<div class="badge badge-secondary" >'.get_phrases(['returned']).'</div>';
                    }*/
                } else {
                    $status .= '<div class="badge badge-info">'.get_phrases(['not','approved']).'</div> ';
                    $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC actionApprove mr-2 custool" title="'.$approve.'" data-id="'.$record['id'].'"><i class="fa fa-check"></i></a>';
                }
                
            } else {
                $received_status .= '<div class="badge badge-info">'.get_phrases(['not','received']).'</div>';
                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-plus-square"></i></a>';
            }

            $data[] = array( 
                'id'                => $record['id'],
                'voucher_no'        => $record['voucher_no'],
                'date'              => $record['date'],
                'store_name'        => $record['store_name'],
                'machine_name'      => $record['machine_name'],
                //'grand_total'       => $record['grand_total'],
                'status'            => $status,
                'received_status'   => $received_status,
                'button'            => $button,
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
    
    public function bdtaskt1m12_03_getItemReceiveDetailsById($id){
        $data = $this->db->table('wh_production p')->select('p.*, DATE_FORMAT(p.date, "%d/%m/%Y") date, ps.nameE as store_name, s.nameE as machine_name, r.voucher_no as receive_voucher_no, DATE_FORMAT(r.date, "%d/%m/%Y") as receive_date, r.id as receive_id')                        
            ->join('wh_production_store ps', 'ps.id=p.store_id','left')
            ->join('wh_machine_store s', 's.id=p.machine_id','left')
            ->join('wh_receive r', 'r.production_id=p.id','left')
            ->where( array('p.id'=>$id) )
            ->get()
            ->getRowArray();

        return $data;
    }

    public function bdtaskt1m12_04_updateReturn($qty, $bag_size,$where){
        $result = $this->db->table('wh_receive_details')->set('return_qty', 'return_qty+'.$qty, FALSE)->set('avail_qty', 'avail_qty-'.($bag_size >0)?($qty/$bag_size):0, FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_05_increaseStock($qty, $where){
        $result = $this->db->table('wh_production_stock')->set('stock', 'stock+'.$qty, FALSE)->set('stock_in', 'stock_in+'.$qty, FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_06_decreaseStock($qty, $where){
        $result = $this->db->table('wh_production_stock')->set('stock', 'stock-'.$qty, FALSE)->set('stock_out', 'stock_out+'.$qty, FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_08_checkAvailQty($production_id, $item_id, $return_qty){
        $where = array('production_id'=> $production_id, 'item_id'=> $item_id);
        $result = $this->db->table('wh_receive_details')->where($where)->where(" ((avail_qty - adjust_in)*bag_size) < ", $return_qty)->get()->getRow();

        return $result;
    }

    public function bdtaskt1m12_10_getItemProductionItemDetailsById($production_id, $item_id =0 ){

        $where = array('pd.production_id'=>$production_id);
        if( !empty($item_id) ){
            $where['pd.item_id'] = $item_id;
        }

        $data = $this->db->table('wh_production_details pd')->select('(rd.qty*rcpd.qty/100) as qty, rcpd.material_id as item_id')       
            ->join('wh_receive_details rd', '(rd.production_id=pd.production_id AND rd.item_id=pd.item_id)','left')
            ->join('wh_recipe_details rcpd', 'rcpd.recipe_id=pd.recipe_id','left')
            ->join('wh_material m', 'm.id=rcpd.material_id','left')
            ->join('list_data l', 'l.id=m.unit_id','left')
            ->where( $where )
            ->get()
            ->getResult();

        return $data;
    }

    public function bdtaskt1m12_10_getBagItemDetailsById($production_id, $item_id){

        $data = $this->db->table('wh_production_details pd')->select('b.id as item_id,u.nameE as unit_name')
            ->join('wh_bag b', 'b.finish_goods=pd.item_id','left')
            ->join('list_data u', 'u.id=b.unit_id','left')
            ->where( array('pd.production_id'=>$production_id, 'pd.item_id'=>$item_id) )
            ->get()
            ->getRow();

        return $data;
    }

}
?>