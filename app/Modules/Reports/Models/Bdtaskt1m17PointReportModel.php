<?php namespace App\Modules\Reports\Models;
use CodeIgniter\Model;
class Bdtaskt1m17PointReportModel extends Model
{
    public function __construct()
    {
        $this->db = db_connect();
    }

  	/*--------------------------
    | Get gain points
    *--------------------------*/
    public function bdtaskt1m17_01_getPointReports($data=[], $from, $to, $type, $export=false, $lang='english')
    { 
        $offset = ($data['pageNumber']-1) * $data['page_size'];
        $builder = $this->db->table('service_invoice_details sid');
        $builder->select("sid.*, si.invoice_date, file.file_no, patient.$lang as patient_name, CONCAT_WS(' ', employees.short_name, '-', employees.$lang) as doctor_name, s.code_no, s.$lang as service_name, s.redeem_per_point_value");
        $builder->join("service_invoices si", "si.id=sid.invoice_id", "left");
        $builder->join("patient", "patient.patient_id=si.patient_id", "left");
        $builder->join("patient_file file", "file.patient_id=si.patient_id", "left");
        $builder->join("employees", "employees.emp_id=si.doctor_id", "left");
        $builder->join("services s", "s.id=sid.app_service_id", "left");
        if($data['branch_id'] > 0){
            $builder->where('si.branch_id', $data['branch_id']);
        }
        $builder->where("si.invoice_date >=",  $from);
        $builder->where("si.invoice_date <=",  $to);
        if(!empty($service_id)){
        	$builder->where('sid.app_service_id', $data['service_id']);
        }
        if(!empty($doctor_id)){
        	$builder->where("si.doctor_id", $data['doctor_id']);
        }
        if(!empty($patient_id)){
        	$builder->where("si.patient_id", $data['patient_id']);
        }
        if($type=='gain'){
        	$builder->where("sid.gain_points >", 0);
        }else{
        	$builder->where("sid.redeem_points >", 0);
        }
        
        $rows = $builder->countAllResults(false);
        if(!empty($data['page_size'])){
            if(!$export){
                $builder->limit($data['page_size'], $offset);
            }
        }
        if(!empty($data['column_name']) && !empty($data['sorting'])){
            $builder->orderBy($data['column_name'], $data['sorting']);
        }
        $query1 = $builder->get()->getResult();   
        return array('data' => $query1, 'num_rows'=>$rows );
    }

   
}
?>