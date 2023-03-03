<?php namespace App\Modules\Export\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m2BackupModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasReadAccess = $this->permission->method('full_backup', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('full_backup', 'create')->access();
        //$this->hasUpdateAccess = $this->permission->method('full_backup', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('full_backup', 'delete')->access();

    }

    public function sql_backup(){
        
        $dbhost = 'localhost';
        $dbuser = 'root';
        $dbpass = '';
        $dbname = 'sa_agro';
        $location = base_url('assets/data/backup/');
       
       $file_name = $dbname . date("Y-m-d-H-i-s") . '.gz';
       $backup_file = $location.$file_name;
       $command = "mysqldump --opt -h $dbhost -u $dbuser -p$dbpass $dbname | gzip > $backup_file";
       //exit;
       system($command);

       return $file_name;
    }

    public function bdtaskt1m2_02_getAll($postData=null){
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
           $searchQuery = " (file_name like '%".$searchValue."%' OR created_dt like '%".$searchValue."%' ) ";
        }
        ## Total number of records without filtering
        $builder1 = $this->db->table('backup');
        $builder1->select("count(*) as allcount");
        $query   =  $builder1->get();
        $records =   $query->getRow();
        $totalRecords = $records->allcount;
         
        ## Total number of record with filtering
        $builder2 = $this->db->table('backup');
        $builder2->select("count(*) as allcount");
        if($searchValue != ''){
           $builder2->where($searchQuery);
        }
        $query2   =  $builder2->get();
        $records =   $query2->getRow();
        $totalRecordwithFilter = $records->allcount;
        ## Fetch records
        $builder3 = $this->db->table('backup');
        $builder3->select("*");
        if($searchValue != ''){
           $builder3->where($searchQuery);
        }
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();
        $data = array();
        
        $download = get_phrases(['download']);
        $delete = get_phrases(['delete']);
        
        foreach($records as $record ){ 
            $button = '';

            $button .= (!$this->hasReadAccess)?'':'<a href="'.base_url().'/assets/data/backup/'.$record['file_name'].'" class="btn btn-primary-soft btnC mr-2 custool" title="'.$download.'" data-id="'.$record['id'].'"><i class="fa fa-download"></i></a>';
            $button .= (!$this->hasDeleteAccess)?'':'<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="'.$delete.'" data-id="'.$record['id'].'"><i class="far fa-trash-alt"></i></a>';
            $data[] = array( 
                'id'       => $record['id'],
                'file_name'=> $record['file_name'],
                'created_dt'=> $record['created_dt'],
                'button'   => $button
            ); 
        }

        ## Response
        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData"               => $data
        );
        return $response; 
    }
   
}
?>