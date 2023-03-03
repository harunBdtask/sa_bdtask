<?php namespace App\Modules\Production\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1ProductionRecipeModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('recipe_list', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('recipe_list', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('recipe_list', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('recipe_list', 'delete')->access();

    }

    public function bdtaskt1m12_02_getAll($postData=null){
         $response = array();
         ## Read value
      
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
           $searchQuery = " (wh_recipe.voucher_no like '%".$searchValue."%' OR wh_recipe.date like '%".$searchValue."%' OR wh_items.nameE like '%".$searchValue."%'  OR wh_items.company_code like '%".$searchValue."%' ) ";
        }
       
        if($item_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_recipe.item_id = '".$item_id."' ) ";
        }
        if($voucher_no != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_recipe.id = '".$voucher_no."' ) ";
        }
        if($date != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " ( wh_recipe.date = '".$date."' ) ";
        }
        ## Fetch records
        $builder3 = $this->db->table('wh_recipe');
        $builder3->select("wh_recipe.*, wh_items.company_code, wh_items.nameE as item_name, GROUP_CONCAT(i.item_code) as item_codes, GROUP_CONCAT(i.nameE) as item_names");
        $builder3->join('wh_items', 'wh_items.id=wh_recipe.item_id','left');
        $builder3->join('wh_recipe_details pd', 'pd.recipe_id=wh_recipe.id','left');
        $builder3->join('wh_material i', 'i.id=pd.material_id','left');
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }   

        $builder3->groupBy('wh_recipe.id');
        ## Total number of records with filtering
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        // $builder3->orderBy('wh_recipe.id', 'desc');
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
        $active = get_phrases(['active']);
        $sl = 1;
        foreach($records as $record ){ 
            $button = '';
            $status = '';
            $approval_status = '';

            $button .= (!$this->hasReadAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="'.$details.'" data-id="'.$record['id'].'"><i class="fa fa-eye"></i></a>';
            
            /*$button .='<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .='<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';*/

            if($record['isApproved']==1){
                $approval_status .= '<div class="badge badge-success mr-2" >'.get_phrases(['approved']).'</div>';

                if($record['isActive']==1){
                    $status .= '<div class="badge badge-primary mr-2" >'.get_phrases(['active']).'</div>';                    
                } else {
                    $status .= '<div class="badge badge-secondary mr-2">'.get_phrases(['inactive']).'</div>';

                    $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionActive mr-2 custool" title="'.$active.'" data-id="'.$record['id'].'"><i class="fa fa-check"></i></a>';
                }
            } else {
                $approval_status .= '<div class="badge badge-info mr-2">'.get_phrases(['approval','needed']).'</div>';

                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-success-soft btnC actionApprove mr-2 custool" title="'.$approve.'" data-id="'.$record['id'].'"><i class="fa fa-check"></i></a>';
                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
                $button .= (!$this->hasDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete mr-2 custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';
            }

            $data[] = array( 
                'id'                => $sl,
                'voucher_no'        => $record['voucher_no'],
                'date'              => $record['date'],
                'item_name'        => $record['item_name'].' ( '.$record['company_code'].' )',
                'status'            => $status,
                'approval_status'            => $approval_status,
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
    
    public function bdtaskt1m12_03_getProductionRecipeDetailsById($id){
        $data = $this->db->table('wh_recipe')->select('wh_recipe.*, DATE_FORMAT(wh_recipe.date, "%d/%m/%Y") as date, wh_items.nameE as item_name')                        
            ->join('wh_items', 'wh_items.id=wh_recipe.item_id','left')
            ->where( array('wh_recipe.id'=>$id) )
            ->get()
            ->getRowArray();

        return $data;
    }

    public function bdtaskt1m12_04_updateStock($qty, $where){
        $result = $this->db->table('wh_production_stock')->set('stock', 'stock+'.$qty, FALSE)->set('stock_in', 'stock_in+'.$qty, FALSE)->where($where)->update();

        return $this->db->affectedRows();
    }

    public function bdtaskt1m12_05_get_item_list(){
        $data = $this->db->table('wh_material')->select('wh_material.*')      
            ->where( array('wh_material.status'=>1) )
            ->orderBy('wh_material.nameE','ASC')
            ->get()
            ->getResult();

        return $data;
    }

    public function bdtaskt1m12_06_getSupplierItemDetailsById($material_id){

        $data = $this->db->table('wh_material')->select('list_data.nameE as unit_name,wh_material.*')       
            //->join('wh_items', 'wh_items.id=wh_items_supplier.material_id','left')
            ->join('list_data', 'list_data.id=wh_material.unit_id','left')
            ->where( array('wh_material.id'=>$material_id) )
            ->get()
            ->getRowArray();

        return $data;
    }

    public function bdtaskt1m12_07_getProductionRecipeItemDetailsById($recipe_id){

        $data = $this->db->table('wh_recipe_details')->select('wh_recipe_details.*,list_data.nameE as unit_name,wh_items.box_qty,wh_items.carton_qty')       
            ->join('wh_items', 'wh_items.id=wh_recipe_details.material_id','left')
            ->join('list_data', 'list_data.id=wh_items.unit_id','left')
            ->where( array('wh_recipe_details.recipe_id'=>$recipe_id) )
            ->get()
            ->getResultArray();

        return $data;
    }


}
?>