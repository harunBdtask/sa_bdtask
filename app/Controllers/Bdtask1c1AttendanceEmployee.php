<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Bdtaskt1m2AttendanceEmployee;
use App\Models\Bdtaskt1m1CommonModel;

class Bdtask1c1AttendanceEmployee extends BaseController
{
    private $bdtaskt1c1_01_AttenEmpModel;
    private $bdtaskt1c1_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Content-Type: application/json");
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

        $this->bdtaskt1c1_02_AttenEmpModel = new Bdtaskt1m2AttendanceEmployee();
        $this->bdtaskt1c1_01_CmModel = new Bdtaskt1m1CommonModel();
    }

    public function bdtaskt1c1_01_check_status(){
        
        $request_data =  json_decode(file_get_contents("php://input"));

        $status = $request_data->status;

        if($status == 1){
            $data['status'] = "ok";
        }else{
            $data['status'] = "fail";
        }

        echo json_encode($data);
    }

    public function bdtaskt1c1_02_insert_purchase_info(){

        $request_data =  json_decode(file_get_contents("php://input"));

        $data['domain'] = $request_data->domain;
        $data['purchase_key'] = $request_data->purchase_key;

         // Check, if purchase_key and domain already inserted into databse
        $query_string = "purchase_key is NOT NULL AND domain is NOT NULL";

        $result = $this->db->table('hrm_schdule_purchse_info')
            ->select('*')
            ->where($query_string)
            ->countAllResults();

        if($result > 0){

            $data['status'] = false;
            $data['msg'] = "Purchase info already exists !";

        }else{

            if($request_data->domain == null || $request_data->purchase_key == null){

                $data['status'] = false;
                $data['msg'] = "Purchase key or domain is empty !";

            }else{

                $respo_schdule_purchse_info = $this->bdtaskt1c1_02_AttenEmpModel->bdtaskt1m2_01_Insert('hrm_schdule_purchse_info', $data);

                if($respo_schdule_purchse_info){

                    $data['status'] = true;
                    $data['msg'] = "Successfully inserted into database !";

                }else{

                    $data['status'] = false;
                    $data['msg'] = "Unable to insert data ,Please try again.";
                }
            }  
        }

        echo json_encode($data);

    }

    public function bdtaskt1c1_03_get_purchase_info(){

        // Check, if purchase_key and domain already inserted into databse
        $query_string = "purchase_key is NOT NULL AND domain is NOT NULL";

        $result = $this->db->table('hrm_schdule_purchse_info')
            ->select('*')
            ->where($query_string)
            ->get()->getResult();    

        if(count($result) > 0){

            $data['status'] = true;
            $data['data'] = $result;
        }else{

            $data['status'] = false;
            $data['msg'] = "Data not available ,Please try again.";
        }

        echo json_encode($data);
    }

    // when request form hrm scheduler to insert ip_address and port
    public function bdtaskt1c1_04_insert_zkt_ip(){

        $request_data =  json_decode(file_get_contents("php://input"));

        $data['ip_address'] = $request_data->ip_address;
        $data['port'] = $request_data->port;

        if($request_data->ip_address == null || $request_data->port == null){

            $data['status'] = false;
            $data['msg'] = "IP adderess or Port is null !";

        }else{

            $available_ip_ports = $this->db->table('hrm_schdule_purchse_info')
                ->select('*')
                ->where('ip_address',$request_data->ip_address)
                ->countAllResults();

            if($available_ip_ports > 0){

                $data['status'] = false;
                $data['msg'] =  "The IP ".$request_data->ip_address." already exists in database !";

            }else{

                $respo_schdule_purchse_info = $this->bdtaskt1c1_02_AttenEmpModel->bdtaskt1m2_01_Insert('hrm_schdule_purchse_info', $data);

                if($respo_schdule_purchse_info){

                    $data['status'] = true;
                    $data['msg'] = "Successfully inserted into database !";

                }else{

                    $data['status'] = false;
                    $data['msg'] = "Unable to insert data ,Please try again.";
                }
            }
        }

        echo json_encode($data);
    }

    //Getting ip_address for Hrm scheduler

   public function bdtaskt1c1_05_get_all_ip_address(){

        // Check, if purchase_key and domain already inserted into databse
        $query_string = "ip_address is NOT NULL AND port is NOT NULL";

        $result = $this->db->table('hrm_schdule_purchse_info')
            ->select('ip_address,port,created_at')
            ->where($query_string)
            ->get()->getResult();

        if(count($result) > 0){

            $data['status'] = true;
            $data['data'] = $result;
        }else{

            $data['status'] = false;
            $data['msg'] = "Data not available ,Please try again.";
        }

        echo json_encode($data);        
   }

    // when request form hrm scheduler to delete zkt ip_address
    public function bdtaskt1c1_06_delete_zkt_ip(){

        $request_data =  json_decode(file_get_contents("php://input"));

        $this->db->table('hrm_schdule_purchse_info')->where('ip_address',$request_data->ip_address)
            ->delete();

        if($this->db->affectedRows()){

            $data['status'] = true;
            $data['msg'] = "ZKT IP deleted successfully.";

        }else{

            $data['status'] = false;
            $data['msg'] = "Can not delete ZKT IP.";
        }

        echo json_encode($data); 
    }

   // For uploading/creating attendence history
   public function bdtaskt1c1_07_create_attendence(){
       
       $request_data =  json_decode(file_get_contents("php://input"));
    
       $attendance_history['uid'] = $request_data->uid;
       $attendance_history['id'] = $request_data->id;
       $attendance_history['state'] = $request_data->state;
       $attendance_history['time'] = $request_data->time;

       // Check if attendance data already inserted or not
        $dulicate_attendance_check = $this->db->table('hrm_attendance_history')
            ->select('*')
            ->where('time',$request_data->time)
            ->where('uid',$request_data->uid)
            ->countAllResults();

       if($dulicate_attendance_check > 0){

            $data = [
                'status' => 'fail',
                'msg' => 'Duplicate entry !'
            ];

       }else{
       
           $respo_attendance_history = $this->db->table('hrm_attendance_history')->insert($attendance_history);
           $atten_his_last_id = $this->db->insertId();
           
           /*request to attendence point system through rewardpoints_model*/
           
           if($respo_attendance_history){
               
                $data = [
                        'status' => 'ok',
                        'msg' => 'successfully inserted to attendance_history !'
                    ];
                
           }else{

                $data = [
                    'status' => 'fail',
                    'msg' => 'can not insert attendence history'
                ];
           }
       
       }
       /*end of request to attendence point system through rewardpoints_model*/
    
      echo json_encode($data);
       
   }

   // For uploading/creating bulk attendence history from hrm_scheduler
   public function bdtaskt1c1_08_bulk_attendance_push(){
       
       //get bulk data
       $request_data =  json_decode(file_get_contents("php://input"));

       $data = array();

       $total_records            = count($request_data);
       $total_inserted           = 0;
       $total_dulicate           = 0;
       $total_fail               = 0; 
      
       if($total_records > 0){

           foreach ($request_data as $attn_data) {
        
               $attendance_history['uid']   = $attn_data->uid;
               $attendance_history['id']    = $attn_data->id;
               $attendance_history['state'] = $attn_data->state;
               $attendance_history['time']  = $attn_data->time;

               // Check if attendance data already inserted or not
                $dulicate_attendance_check = $this->db->table('hrm_attendance_history')
                    ->select('*')
                    ->where('time',$attn_data->time)
                    ->where('uid',$attn_data->uid)
                    ->countAllResults();

               if($dulicate_attendance_check > 0){

                    $total_dulicate = $total_dulicate + 1;

               }else{

                   $respo_attendance_history = $this->db->table('hrm_attendance_history')->insert($attendance_history);
                   $atten_his_last_id = $this->db->insertId();
                   
                   /*request to attendence point system through rewardpoints_model*/
                   
                   if($respo_attendance_history){

                        $total_inserted = $total_inserted + 1;
                        
                   }else{

                        $total_fail = $total_fail + 1;
                   }
               }
            }

            $data['status']                   = true;
            $data['total_records']            = $total_records;
            $data['total_inserted']           = $total_inserted;
            $data['total_dulicate']           = $total_dulicate;
            $data['total_fail']               = $total_fail;

        }else{

            $data['status']         = false;
            $data['total_records']  = 0;
            $data['msg']            = "You have no attendance records to push !";
        }

       /*end of request to attendence point system through rewardpoints_model*/
    
      echo json_encode($data);
       
   }

   //getting employee by employee_id
   public function bdtaskt1c1_09_get_employee_by_id(){
       
        $request_data =  json_decode(file_get_contents("php://input"));
       
        $employee_id = $request_data->employee_id;
        
        $emp_data = $this->db->table('hrm_employees')
                ->select('employee_id as emp_id,first_name as nameE,email,mobile')
                ->where('employee_id',$employee_id)
                ->get()
                ->getRow();
        
        if($emp_data){
            
            $arr_data = [
                'status' => "ok",
                'msg' => "successful",
                'data' => $emp_data
            ];
        
        }else{
            $arr_data = [
                'status' => "fail",
                'msg' => "Employee doesn't exists"
            ];
        }
        
        echo json_encode($arr_data);
   }

   // Get settings data
   public function bdtaskt1c1_10_setting_info(){

        $setting_data = $this->db->table('setting')
                ->select('*')
                ->get()
                ->getRow();

        echo json_encode($setting_data);   
   }
   
}
