<?php namespace App\Modules\Reports\Models;
use CodeIgniter\Model;
class Bdtaskt1m17AppReportModel extends Model
{
    public function __construct()
    {
        $this->db = db_connect();
    }

    /*--------------------------
    | Get appointment summary report 
    *--------------------------*/
    public function bdtaskt1m17_01_getSummaryReport($doctor_id=[], $from, $to, $text){
        $ids = implode(',', $doctor_id);
        $builder = $this->db->table('appointment app');
        $builder->select("CONCAT_WS(' ', emp.short_name, emp.$text) as doctor_name, count(appoint_id) as total_appointment, 
            SUM(IF(app.type=1, app.duration, 0)) as real_duration, 
            SUM(IF(app.type=0, app.duration, 0)) as wait_duration, 
            SUM(IF(app.type=2, app.duration, 0)) as oncall_duration, 
            SUM(IF(app.visit_status='First Visit', 1, 0)) as first_visit, 
            SUM(IF(app.visit_status='Follow Up', 1, 0)) as follow, 
            SUM(IF(app.visit_status='Return', 1, 0)) as return1, 
            SUM(IF(app.visit_status='Procedure Room', 1, 0)) as pr_room, 
            SUM(IF(app.visit_status='Clinic Procedure', 1, 0)) as pr_clinic, 
            SUM(IF(app.isEnter=0, 1, 0)) as no_show, 
            SUM(IF(app.type=1, 1, 0)) as realApp, 
            SUM(IF(app.type=0, 1, 0)) as waiting, 
            SUM(IF(app.type=2, 1, 0)) as on_call,
            (SELECT SUM(IF(isCloseTime=0, TIME_TO_SEC(TIMEDIFF(end_time, start_time)), (TIME_TO_SEC(TIMEDIFF(end_time, start_time))-TIME_TO_SEC(TIMEDIFF(close_end, close_start))))) FROM schedule_details WHERE doctor_id=app.doctor_id AND date >='$from' AND date <='$to' AND status=1 GROUP BY doctor_id) as open_time
        ");
        $builder->join("employees emp", "emp.emp_id=app.doctor_id", "left");
        if(session('branchId') > 0){
            $builder->where('app.branch_id', session('branchId'));
        }
        $builder->whereIn("app.doctor_id", $doctor_id);
        $builder->where("app.date >=",  $from);
        $builder->where("app.date <=",  $to);
        $builder->where("app.status !=", 2);
        $builder->groupBy("app.doctor_id");
        return $builder->get()->getResult();   
    }

    /*--------------------------
    | Get appointment summary report
    *--------------------------*/
    public function bdtaskt1m17_02_getDetailsNoShowReport($doctor_id=[], $from, $to, $text){
        if(!empty($doctor_id)){
            $ids = implode(',', $doctor_id);
            $builder = $this->db->table('appointment app');
            $builder->select("CONCAT_WS(' ', emp.short_name, emp.$text) as doctor_name, count(appoint_id) as total_appointment, 
                SUM(IF(app.visit_status='First Visit', 1, 0)) as first_visit, 
                SUM(IF(app.visit_status='Follow Up', 1, 0)) as follow, 
                SUM(IF(app.visit_status='Return', 1, 0)) as return1, 
                SUM(IF(app.visit_status='Procedure Room', 1, 0)) as pr_room, 
                SUM(IF(app.visit_status='Clinic Procedure', 1, 0)) as pr_clinic, 
                SUM(IF(app.isEnter=0 AND app.visit_status='First Visit', 1, 0)) as no_show_first, 
                SUM(IF(app.isEnter=0 AND app.visit_status='Follow Up', 1, 0)) as no_show_follow, 
                SUM(IF(app.isEnter=0 AND app.visit_status='Return', 1, 0)) as no_show_return, 
                SUM(IF(app.isEnter=0 AND app.visit_status='Procedure Room', 1, 0)) as no_show_procedure, 
                SUM(IF(app.isEnter=0 AND app.visit_status='Clinic Procedure', 1, 0)) as no_show_clinic, 
                SUM(IF(app.isEnter=0, 1, 0)) as no_show, 
                SUM(IF(app.type=1, 1, 0)) as realApp, 
                SUM(IF(app.type=0, 1, 0)) as waiting,
                CASE
                WHEN app.type=1 THEN 'Real'
                WHEN app.type=0 THEN 'Waiting'
                END as type
            ");
            $builder->join("employees emp", "emp.emp_id=app.doctor_id", "left");
            if(session('branchId') > 0){
                $builder->where('app.branch_id', session('branchId'));
            }
            $builder->whereIn("app.doctor_id", $doctor_id);
            $builder->whereIn("app.type", [1,0]);
            $builder->where("app.date >=",  $from);
            $builder->where("app.date <=",  $to);
            $builder->where("app.status !=", 2);
            $builder->groupBy("app.doctor_id, app.type");
            return $builder->get()->getResult();   
        }else{
            return false;
        }
    }

    public function test(){
        $builder = $this->db->table('appointment');
        $builder->select("count(appoint_id) as total_appointment, 
            SUM(duration) as duration, 
            SUM(IF(visit_status='First Visit', 1, 0)) as first_visit, 
            SUM(IF(visit_status='Follow Up', 1, 0)) as follow, 
            SUM(IF(visit_status='Return', 1, 0)) as return1, 
            SUM(IF(visit_status='Procedure Room', 1, 0)) as pr_room, 
            SUM(IF(visit_status='Clinic Procedure', 1, 0)) as pr_clinic, 
            SUM(IF(isEnter=0, 1, 0)) as no_show, 
            SUM(IF(type=1, 1, 0)) as realApp, 
            SUM(IF(type=0, 1, 0)) as waiting, 
            SUM(IF(type=2, 1, 0)) as on_call, 
            (SELECT SUM(TIME_TO_SEC(TIMEDIFF(end_time, start_time))) FROM schedule_details WHERE doctor_id='$doctor_id' AND date >='$from' AND date <='$to' GROUP BY doctor_id) as open_time
        ");
        $builder->where("doctor_id", $doctor_id);
        $builder->where("date >=",  $from);
        $builder->where("date <=",  $to);
        $builder->where("status !=", 2);
        $builder->groupBy("doctor_id");
        return $builder->get()->getResult();  
    }

   
}
?>