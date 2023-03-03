<?php namespace App\Modules\Production\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1ItemProductionModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('production_plan', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('production_plan', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('production_plan', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('production_plan', 'delete')->access();

    }

    public function bdtaskt1m12_02_getAll($postData=null){
         $response = array();
         ## Read value
      
        //@$store_id = $postData['store_id'];
        @$machine_id = $postData['machine_id'];
        @$item_id = $postData['item_id'];
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
           $searchQuery = " (p.voucher_no like '%".$searchValue."%' OR p.date like '%".$searchValue."%' ) ";
        }
        /*if($store_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( p.store_id = '".$store_id."' ) ";
        }*/
        if($machine_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( p.machine_id = '".$machine_id."' ) ";
        }
        if($item_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( pd.item_id = '".$item_id."' ) ";
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
        $builder3->select("p.*, i.nameE as item_name, ms.nameE as machine_name, GROUP_CONCAT(i.company_code) as item_codes, GROUP_CONCAT(i.nameE) as item_names");
        $builder3->join('wh_production_store ps', 'ps.id=p.store_id','left');
        $builder3->join('wh_machine_store ms', 'ms.id=p.machine_id','left');
        $builder3->join('wh_production_details pd', 'pd.production_id=p.id','left');
        $builder3->join('wh_items i', 'i.id=pd.item_id','left');
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }         

        $builder3->groupBy('p.id');
        ## Total number of records with filtering
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy('p.id', 'desc');
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
        $approve = get_phrases(['approve']);
        $sl = 1;
        foreach($records as $record ){ 
            $button = '';
            $status = '';
            $received_status = '';

            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="'.$details.'" data-id="'.$record['id'].'"><i class="fa fa-eye"></i></a>';
            
            /*$button .='<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .='<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';*/

            if($record['isApproved']==1){
                $status .= '<div class="badge badge-success" >'.get_phrases(['approved']).'</div>';
                
                if($record['received'] ==1 ){
                    $received_status .= ' <div class="badge badge-primary" >'.get_phrases(['store','received']).'</div>';
                } else {
                    $received_status .= ' <div class="badge badge-info" >'.get_phrases(['not','received']).'</div>';
                }
            } else {
                $status .= '<div class="badge badge-info">'.get_phrases(['not','approved']).'</div>';

                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC actionApprove mr-2 custool" title="'.$approve.'" data-id="'.$record['id'].'"><i class="fa fa-check"></i></a>';
                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
                $button .= (!$this->hasDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete mr-2 custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';
            }
            /*if($record['received']==1){
                $status .= ' <div class="badge badge-success" >'.get_phrases(['received']).'</div> ';
            }*/

            $data[] = array( 
                'id'                => $sl,
                'voucher_no'        => $record['voucher_no'],
                'date'              => $record['date'],
                'machine_name'      => $record['machine_name'],
                'item_name'         => $record['item_name'],
                'item_codes'        => ($record['id'] == 1)?'':'<span class="custool" title="'.$record['item_names'].'">'.$record['item_codes'].'</span>',
                //'grand_total'       => $record['grand_total'],
                'status'            => $status,
                'received_status'   => $received_status,
                'button'            => $button
            ); 
            $sl++;
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
    
    public function bdtaskt1m12_03_getItemProductionDetailsById($id){
        $data = $this->db->table('wh_production p')->select('p.*, DATE_FORMAT(p.date, "%d/%m/%Y") as date, ps.nameE as store_name, ms.nameE as machine_name')                        
            ->join('wh_production_store ps', 'ps.id=p.store_id','left')
            ->join('wh_machine_store ms', 'ms.id=p.machine_id','left')
            ->join('wh_receive r', 'r.production_id=p.id','left')
            ->where( array('p.id'=>$id) )
            ->get()
            ->getRowArray();

        return $data;
    }

    public function bdtaskt1m12_04_updateStock($qty, $where){
        $result = $this->db->table('wh_machine_stock')->set('stock', 'stock-'.$qty, FALSE)->set('stock_out', 'stock_out+'.$qty, FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_05_get_item_list(){
        $data = $this->db->table('wh_items')->select('wh_items.*')        
            //->join('wh_items', 'wh_items.id=wh_items_supplier.item_id','left')
            ->where( array('wh_items.status'=>1) )
            ->get()
            ->getResult();

        return $data;
    }

    public function bdtaskt1m12_06_getSupplierItemDetailsById($item_id){

        $data = $this->db->table('wh_items')->select('list_data.nameE as unit_name,wh_items.*')       
            //->join('wh_items', 'wh_items.id=wh_items_supplier.item_id','left')
            ->join('list_data', 'list_data.id=wh_items.unit_id','left')
            ->where( array('wh_items.id'=>$item_id) )
            ->get()
            ->getRowArray();

        return $data;
    }

    public function bdtaskt1m12_07_getItemProductionItemDetailsById($production_id){

        $data = $this->db->table('wh_production_details pd')->select('pd.*,u.nameE as unit_name,i.box_qty,i.carton_qty')       
            ->join('wh_items i', 'i.id=pd.item_id','left')
            ->join('list_data u', 'u.id=i.unit_id','left')
            ->where( array('pd.production_id'=>$production_id) )
            ->get()
            ->getResultArray();

        return $data;
    }

    public function bdtaskt1m12_10_getItemProductionItemDetailsById($production_id){

        $data = $this->db->table('wh_production_details pd')->select('(pd.qty*(rd.qty/100)) as recipe_qty, rd.qty as recipe_percent, rd.material_id as item_id,
            (
                SELECT rcd.wip_kg FROM wh_receive_details rcd
                LEFT OUTER JOIN wh_receive r ON(rcd.receive_id=r.id)
                LEFT OUTER JOIN wh_production_details prd ON(rcd.production_id=prd.production_id AND rcd.item_id=prd.item_id)
                WHERE rcd.item_id=pd.item_id AND prd.recipe_id=pd.recipe_id AND r.machine_id=p.machine_id AND r.isApproved=1
                ORDER BY rcd.id DESC LIMIT 1
            ) as wip')       
            ->join('wh_production p', 'p.id=pd.production_id','left')
            ->join('wh_recipe_details rd', 'rd.recipe_id=pd.recipe_id','left')
            ->join('wh_material m', 'm.id=rd.material_id','left')
            ->join('list_data l', 'l.id=m.unit_id','left')
            ->where( array('pd.production_id'=>$production_id) )
            ->get()
            ->getResult();

        return $data;
    }

}
?>