<?php namespace App\Modules\Setting\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m15ListsModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();

        $this->permission = new Permission();
        //$this->hasReadAccess = $this->permission->method('parameter_list', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('parameter_list', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('parameter_list', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('parameter_list', 'delete')->access();
    }

    public function bdtaskt1m15_01_getResultWhere($table,$where){
        $builder3 = $this->db->table($table);
        $builder3->select("$table.*, lists.table_name");       
        $builder3->where($where);
        $builder3->join('lists', 'lists.id = $table.list_id','left');
        $query3   =  $builder3->get();
        $records =   $query3->getResult();

        return $records;
    }

    public function bdtaskt1m15_02_getAll($postData=null, $list_tables){
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
        $data = array();
        $totalRecords = 0;
        //foreach($list_tables as $list_table){
            //$table = $list_table->table_name;

            $searchQuery = "";
            if($searchValue != ''){
               $searchQuery = " (list_data.nameE like '%".$searchValue."%' OR lists.table_titleE like '%".$searchValue."%' ) ";
            }
            ## Total number of records without filtering
            /*$builder1 = $this->db->table('list_data');
            $builder1->select("count(*) as allcount");
            $query   =  $builder1->get();
            $records =   $query->getRow();
            $totalRecords += $records->allcount;
             
            ## Total number of record with filtering
            $builder2 = $this->db->table('list_data');
            $builder2->join("lists", "lists.id = list_data.list_id","left");
            $builder2->select("count(*) as allcount");
            if($searchValue != ''){
               $builder2->where($searchQuery);
            }
            $query2   =  $builder2->get();
            $records =   $query2->getRow();
            $totalRecordwithFilter = $records->allcount;*/
            ## Fetch records
            $builder3 = $this->db->table('list_data');
            $builder3->select("list_data.*, lists.table_titleE");
            if($searchValue != ''){
               $builder3->where($searchQuery);
            }
            $builder3->join("lists", "lists.id = list_data.list_id","left");
            //$builder3->groupBy("doctor_services.id");
            //$builder3->orderBy($columnName, $columnSortOrder);
            $totalRecordwithFilter = $builder3->countAllResults(false);
            $builder3->orderBy('list_data.list_id', 'DESC');
            //$builder3->limit($rowperpage, $start);
            $query3   =  $builder3->get();
            $records =   $query3->getResultArray();
            $totalRecords = $builder3->countAll();
            $sl = 1;
            foreach($records as $record ){ 
                $button = '';

                $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2" title="Update" data-id="'.$record['id'].'"><i class="far fa-edit"></i></a>';

                $button .= (!$this->hasDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete" title="Delete" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';

                $data[] = array( 
                    'id'       => $sl,
                    'table_titleE'    => $record['table_titleE'],
                    'nameE'    => $record['nameE'],
                    'nameA'    => $record['nameA'],
                    'button'   => $button
                );
                
                $sl++;
            }
        //}
        ## Response
        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData"               => $data
        );
        return $response; 

    }
   
}
?>