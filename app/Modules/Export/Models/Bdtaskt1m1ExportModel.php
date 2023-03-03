<?php namespace App\Modules\Export\Models;
use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m1ExportModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        //$this->hasReadAccess = $this->permission->method('partial_export', 'read')->access();
        //$this->hasCreateAccess = $this->permission->method('partial_export', 'create')->access();
        //$this->hasUpdateAccess = $this->permission->method('partial_export', 'update')->access();
        //$this->hasDeleteAccess = $this->permission->method('partial_export', 'delete')->access();

        if( session('defaultLang') == 'english' ){
            $this->langColumn = 'nameE';
        }else{
            $this->langColumn = 'nameA';
        }

    }

    public function bdtaskt1m1_01_get_user_data($postData){
        
        @$branch_id = trim($postData['user_branch_id']);
        @$department = trim($postData['user_department']);
        @$user_role = trim($postData['user_role']);

        $searchQuery = "";
        if($branch_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " (user.branch_id = '".$branch_id."' ) ";
        }
        if($department != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " (user.department_id = '".$department."' ) ";
        }
        if($user_role != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " (user.user_role = '".$user_role."' ) ";
        }

        $builder3 = $this->db->table('user');
                            //->join("department as d", "d.id=user.department_id", "left");
        $builder3->select("user.*");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }
        //$builder3->orderBy($columnName, $columnSortOrder);
        //$builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();

        //$heading = array('id','emp_id','fullname','email','username','password','user_role','branch_id','department_id','store_id','store_id','mobile','date_of_birth','gender','pass_reset_token','auth_token','last_activity','created_by','created_date','updated_date','updated_by','status');

        //$data = array_merge($heading, $records);

        return $records;
    }

    public function bdtaskt1m1_02_get_employee_data($postData){
        
        @$branch_id = trim($postData['emp_branch_id']);
        @$department = trim($postData['emp_department']);
        @$job_title = trim($postData['emp_job_title']);

        $searchQuery = "";
        if($branch_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " (employees.branch_id = '".$branch_id."' ) ";
        }
        if($department != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " (employees.department_id = '".$department."' ) ";
        }
        if($job_title != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " (employees.job_title_id = '".$job_title."' ) ";
        }

        $builder3 = $this->db->table('employees');
                            //->join("department as d", "d.id=user.department_id", "left");
        $builder3->select("employees.*");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }
        //$builder3->orderBy($columnName, $columnSortOrder);
        //$builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();

        return $records;
    }

    public function bdtaskt1m1_03_get_service_data($postData){
        
        @$branch_id = trim($postData['ser_branch_id']);
        @$department = trim($postData['ser_department']);
        @$sub_dept = trim($postData['ser_sub_dept']);

        $searchQuery = "";
        if($branch_id >0 ){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " FIND_IN_SET(".$branch_id.", a.branch_id ) ";
        }
        if($department != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " (a.department = '".$department."' ) ";
        }
        if($sub_dept != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " (a.sub_department = '".$sub_dept."' ) ";
        }

        $builder3 = $this->db->table('services a')
                            ->join("branch as b", "FIND_IN_SET(b.id, a.branch_id )", "left")
                            ->join("department as d", "d.id=a.department", "left")
                            ->join("department as s", "s.id=a.sub_department", "left")
                            ->join("list_data as t", "t.id=a.service_type", "left");
        $builder3->select("a.*, GROUP_CONCAT(b.nameE) as branch_name, d.nameE as dept_name, s.nameE as sub_dept_name, t.nameE as service_type_name,
            (
                SELECT SUM(i.total_price) FROM service_item i WHERE i.service_id=a.id GROUP BY i.service_id
            ) as item_cost,
            (
                SELECT GROUP_CONCAT(CONCAT(p.nameE,'_',c.commission,'%_Consume_Cost_',IF(c.consume_cost_deduct=1,'Yes','No'),'_Fixed_Cost_',IF(c.fixed_cost_deduct=1,'Yes','No'))) FROM service_commission c
                LEFT OUTER JOIN commission_program p ON(c.program_id=p.id)
                WHERE c.service_id=a.id GROUP BY c.service_id
            ) as commission_program");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }
        $builder3->groupBy('a.id');
        //$builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResult();
        //echo get_last_query();exit;
        return $records;
    }

    public function bdtaskt1m1_04_get_offer_data($postData){
        
        $searchQuery = "";
        /*@$branch_id = trim($postData['ser_branch_id']);
        @$department = trim($postData['ser_department']);
        @$sub_dept = trim($postData['ser_sub_dept']);

        if($branch_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " (services.branch_id = '".$branch_id."' ) ";
        }
        if($department != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " (services.department = '".$department."' ) ";
        }
        if($sub_dept != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " (services.sub_department = '".$sub_dept."' ) ";
        }*/

        $builder3 = $this->db->table('offers');
                            //->join("department as d", "d.id=user.department_id", "left");
        $builder3->select("offers.*");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }
        //$builder3->orderBy($columnName, $columnSortOrder);
        //$builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();

        return $records;
    }

    public function bdtaskt1m1_05_get_package_data($postData){
        
        $searchQuery = "";
        /*@$branch_id = trim($postData['ser_branch_id']);
        @$department = trim($postData['ser_department']);
        @$sub_dept = trim($postData['ser_sub_dept']);

        if($branch_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " (services.branch_id = '".$branch_id."' ) ";
        }
        if($department != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " (services.department = '".$department."' ) ";
        }
        if($sub_dept != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " (services.sub_department = '".$sub_dept."' ) ";
        }*/

        $builder3 = $this->db->table('offer_packages');
                            //->join("department as d", "d.id=user.department_id", "left");
        $builder3->select("offer_packages.*");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }
        //$builder3->orderBy($columnName, $columnSortOrder);
        //$builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();

        return $records;
    }

    public function bdtaskt1m1_06_get_item_data($postData){
        
        $searchQuery = "";
        @$item_category = trim($postData['item_category']);
        /*@$department = trim($postData['ser_department']);
        @$sub_dept = trim($postData['ser_sub_dept']);

        if($branch_id != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " (services.branch_id = '".$branch_id."' ) ";
        }
        if($department != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " (services.department = '".$department."' ) ";
        }*/
        if($item_category != ''){
            if($searchQuery != ''){
                $searchQuery .= " AND ";
            }
           $searchQuery .= " (wh_items.cat_id = '".$item_category."' ) ";
        }

        $builder3 = $this->db->table('wh_items');
                            //->join("department as d", "d.id=user.department_id", "left");
        $builder3->select("wh_items.*");
        if($searchQuery != ''){
           $builder3->where($searchQuery);
        }
        //$builder3->orderBy($columnName, $columnSortOrder);
        //$builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records =   $query3->getResultArray();

        return $records;
    }


   
}
?>