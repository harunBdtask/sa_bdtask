<?php namespace App\Modules\Commission\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Commissionmodel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();
    }

 public function bdtaskt1m12_01_getAllCommissionSetting($postData=null){
         $response = array();
         ## Read value
      
        @$reference_by    = trim($postData['reference_by']);
        @$draw            = $postData['draw'];
        @$start           = $postData['start'];
        @$rowperpage      = $postData['length']; // Rows display per page
        @$columnIndex     = $postData['order'][0]['column']; // Column index
        @$columnName      = $postData['columns'][$columnIndex]['data']; // Column name
        @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        @$searchValue     = $postData['search']['value']; // Search value
        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
           $searchQuery = " (instant_commission_setting.target_start like '%".$searchValue."%') ";
        }
   
        ## Fetch records
        $builder3 = $this->db->table('instant_commission_setting');
        $builder3->select("instant_commission_setting.*,u.fullname");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }                 
        $builder3->join('user u', 'u.emp_id=instant_commission_setting.created_by','left');
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3  =  $builder3->get();
        $records =  $query3->getResultArray();
        ## Total number of records without filtering
        $totalRecords = $builder3->countAll();
        if($searchQuery == ''){
        $totalRecords = $totalRecordwithFilter;
        }
        $data    = array();
        $details = get_phrases(['view', 'details']);
        $update  = get_phrases(['update']);
        $delete  = get_phrases(['delete']);
        $sl      = 1 ;
        foreach($records as $record ){ 
            $button = '';
            $button .= '<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="'.$update.'" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';
            $button .= '<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';
            $data[] = array( 
                'id'                 => ($postData['start']?$postData['start']:0) + $sl,
                'monthly_target'     => ($record['target_start']?$record['target_start']:0).' To '.($record['target_end']?$record['target_end']:0),
                'target_end'         => $record['target_end'],
                'instant_comm_tk_kg' => $record['instant_comm_tk_kg'],
                'yearly_comm_tk_kg'  => $record['yearly_comm_tk_kg'],
                'total_comm_tk_kg'   => $record['total_comm_tk_kg'],
                'another_addition'   => $record['another_addition'],
                'comments'           => $record['comments'],
                'create_by'          => $record['fullname'],
                'button'             => $button
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


    public function bdtaskt1m1_04_exitcommission($data=[])
    { 
     $start_amount = $data['target_start'];
     $end_amount   = $data['target_end'];
     $query1 = $this->db->table('instant_commission_setting')
                        ->select('*')
                        ->where('target_start <=',$start_amount)
                        ->where('target_end >=',$start_amount)
                        ->countAllResults();
                        

    $query2 = $this->db->table('instant_commission_setting')
                        ->select('*')
                        ->where('target_start <=',$end_amount)
                        ->where('target_end >=',$end_amount)
                        ->countAllResults();
                       
                        $result = $query1 + $query2;
                        return $result;
    }

    public function bdtaskt1m1_055_Insert($data=[])
    {
        $result =  $this->db->table('instant_commission_setting')->insert($data);
        if($result){
            return true;
        }else{
            return false;
        }
    }

        public function bdtaskt1m12_03_getCommissionDetailsById($id){
        $data = $this->db->table('instant_commission_setting')->select('*')        
                        ->where('id',$id )
                        ->get()
                        ->getRow();

        return $data;
    }

       public function bdtaskt1m1_04_exitcommission_update($data=[],$id)
    { 
     $start_amount = $data['target_start'];
     $end_amount   = $data['target_end'];
     $query1 = $this->db->table('instant_commission_setting')
                        ->select('*')
                        ->where('target_start <=',$start_amount)
                        ->where('target_end >=',$start_amount)
                        ->where('id !=',$id)
                        ->countAllResults();
                        

    $query2 = $this->db->table('instant_commission_setting')
                        ->select('*')
                        ->where('target_start <=',$end_amount)
                        ->where('target_end >=',$end_amount)
                        ->where('id !=',$id)
                        ->countAllResults();
                       
                        $result = $query1 + $query2;
                        return $result;
    }

    public function bdtaskt1m1_02_Update_commission($data=[],$id)
    {
        $update = $this->db->table('instant_commission_setting')->where('id',$id)->update($data);
        if($update){
            return true;
        }else{
            return false;
        }
    }

      public function bdtaskt1m1_06_Deleted_Commission($id)
    { 
        
        return $this->db->table('instant_commission_setting')->where('id',$id)->delete();
    
    }

    public function dealerList()
    {
        $builder = $this->db->table('dealer_info');
        $builder->select('*');
        $builder->where('type',1);
        $query=$builder->get();
        $data=$query->getResult();
        
       $list = array('' => get_phrases(['select','dealer']));
        if(!empty($data)){
            foreach ($data as $value){
                $list[$value->id]=$value->name;
            }
        }
        return $list;  
    }

    public function bdtaskt1m12_06_getDealerSalecommissioninfo($id)
    {
        $data = $this->db->table('dealer_info')->select('*')        
                        ->where('id',$id )
                        ->get()
                        ->getRow();

        return $data;
    }

    public function bdtaskt1m12_07_getDealerSaleamountinfo($id,$month)
    {
        $ldate =date($month);
        $time = strtotime($ldate);
        $year = date('Y',$time);
        $mon = date('m',$time);
        $data = $this->db->table('do_delivery a')
                        ->select('sum(b.total_kg) as total_sold') 
                        ->join('do_delivery_details b','b.do_id = a.do_id')       
                        ->where('a.dealer_id',$id)
                        ->where('YEAR(do_date)',$year)
                        ->where('MONTH(do_date)',$mon)
                        ->get()
                        ->getRow();
      
        return ($data?$data->total_sold:0);
    }

    public function bdtaskt1m12_08_getDealerTargetrange($weight)
    {
        $weight_ton = ($weight?$weight:0)/1000;
          $query = $this->db->table('instant_commission_setting')
                        ->select('*')
                        ->where('target_start <=',$weight_ton)
                        ->where('target_end >=',$weight_ton)
                        ->get()
                        ->getRow();
            return  $query;        
    }

    public function bdtaskt1m1_04_exit_dealer_monthly_commission($data=[])
    {
        $dealer_id = $data['dealer_id'];
        $month     = $data['generated_date'];
        $query = $this->db->table('monthly_generated_commission')
                        ->select('*')
                        ->where('dealer_id',$dealer_id)
                        ->where('generated_date',$month)
                        ->countAllResults();
                        return $query;
    }

    public function bdtaskt1m1_07_Insert_sale_commission($data=[])
    {
         $result =  $this->db->table('monthly_generated_commission')->insert($data);
        if($result){
            $dealer_coa = $this->db->table('acc_coa')->select('*')->where('dealer_id',$data['dealer_id'])->get()->getRow();
            $commissioncredit = array(
            'VNo'         => 'COMM-'.$data['generated_date'],
            'Vtype'       => 'Commission',
            'VDate'       => date('Y-m-d'),
            'COAID'       => ($dealer_coa?$dealer_coa->HeadCode:''),
            'Narration'   => 'Dealer target sale commission form month '.$data['generated_date'],
            'Debit'       => 0,
            'Credit'      => ($data['commission_amount']?$data['commission_amount']:0),
            'FyID'        => 0,
            'IsPosted'    => 1,
            'CreateBy'    => session('id'),
            'CreateDate'  => date('Y-m-d H:i:s'),
            'IsAppove'    => 1
        );

             $this->db->table('acc_transaction')->insert($commissioncredit);
            return true;
        }else{
            return false;
        }
    }


    public function bdtaskt1m12_03_getAllSalesCommission($postData=null){
         $response = array();
         ## Read value
        @$draw            = $postData['draw'];
        @$start           = $postData['start'];
        @$rowperpage      = $postData['length']; // Rows display per page
        @$columnIndex     = $postData['order'][0]['column']; // Column index
        @$columnName      = $postData['columns'][$columnIndex]['data']; // Column name
        @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        @$searchValue     = $postData['search']['value']; // Search value
        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
        $searchQuery = " (monthly_sale_commission.generate_month like '%".$searchValue."%' OR u.fullname like '%" . $searchValue . "%' OR d.name like '%" . $searchValue . "%' OR monthly_sale_commission.voucher_no like '%" . $searchValue . "%') ";
        }
   
        ## Fetch records
        $builder3 = $this->db->table('monthly_sale_commission');
        $builder3->select("monthly_sale_commission.*,u.fullname,d.name as dealer_name");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }
        $builder3->join('dealer_info d', 'd.id=monthly_sale_commission.dealer_id','left');                 
        $builder3->join('user u', 'u.emp_id=monthly_sale_commission.created_by');
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3  =  $builder3->get();
        $records =  $query3->getResultArray();
        ## Total number of records without filtering
        $totalRecords = $builder3->countAll();
        if($searchQuery == ''){
        $totalRecords = $totalRecordwithFilter;
        }
        $data    = array();
        $details = get_phrases(['view', 'details']);
        $update  = get_phrases(['update']);
        $delete  = get_phrases(['delete']);
        $sl      = 1 ;
        foreach($records as $record ){ 
            $button = '';
           
            $button .= '<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';
            $data[] = array( 
                'id'                 => ($postData['start']?$postData['start']:0) + $sl,
                'name'               => ($record['dealer_name']?$record['dealer_name']:''),
                'generate_month'     => $record['generate_month'],
                'total_kg'           => $record['total_kg'],
                'commission_rate'    => $record['commission_rate'],
                'commission_amount'  => $record['commission_amount'],
                'voucher_no'         => $record['voucher_no'],
                'created_by'         => $record['fullname'],
                'button'             => $button
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

    public function bdtaskt1m12_04_getAllTargetSalesCommission($postData=null)
    {
         $response = array();
         ## Read value
        @$draw            = $postData['draw'];
        @$start           = $postData['start'];
        @$rowperpage      = $postData['length']; // Rows display per page
        @$columnIndex     = $postData['order'][0]['column']; // Column index
        @$columnName      = $postData['columns'][$columnIndex]['data']; // Column name
        @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        @$searchValue     = $postData['search']['value']; // Search value
        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
           $searchQuery = " (monthly_generated_commission.generated_date like '%".$searchValue."%' OR u.fullname like '%" . $searchValue . "%' OR d.name like '%" . $searchValue . "%') ";
        }
   
        ## Fetch records
        $builder3 = $this->db->table('monthly_generated_commission');
        $builder3->select("monthly_generated_commission.*,u.fullname,d.name as dealer_name");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }
        $builder3->join('dealer_info d', 'd.id=monthly_generated_commission.dealer_id','left');                 
        $builder3->join('user u', 'u.emp_id=monthly_generated_commission.generate_by');
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3  =  $builder3->get();
        $records =  $query3->getResultArray();
        ## Total number of records without filtering
        $totalRecords = $builder3->countAll();
        if($searchQuery == ''){
        $totalRecords = $totalRecordwithFilter;
        }
        $data    = array();
        $details = get_phrases(['view', 'details']);
        $update  = get_phrases(['update']);
        $delete  = get_phrases(['delete']);
        $sl      = 1 ;
        foreach($records as $record ){ 
            $button = '';
           
            $button .= '<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';
            $data[] = array( 
                'id'                 => ($postData['start']?$postData['start']:0) + $sl,
                'name'               => ($record['dealer_name']?$record['dealer_name']:''),
                'generate_month'     => $record['generated_date'],
                'total_kg'           => $record['total_kg'],
                'kg_ton'             => ($record['total_kg']?$record['total_kg']:0)/1000,
                'reached_target'     => $record['target_range'],
                'commission_rate'    => $record['commission_rate'],
                'commission_amount'  => $record['commission_amount'],
                'created_by'         => $record['fullname'],
                'created_date'       => $record['created_date'],
                'button'             => $button
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

    public function bdtaskt1m1_06_Deleted_monthly_salescommission($id)
    {
        return $this->db->table('monthly_sale_commission')->where('id',$id)->delete();
    }
}
