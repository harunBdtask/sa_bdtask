<?php namespace App\Modules\Reports\Models;
use CodeIgniter\Model;

class Bdtaskt1m17MachineReportModel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        if(session('defaultLang')=='english'){
            $this->langColumn = 'nameE';
        }else{
            $this->langColumn = 'nameA';
        }
    }

    /*--------------------------
    | Get all item stock
    *--------------------------*/
    public function bdtaskt1m17_01_getDailyConsumption($from, $to){
        $builder = $this->db->table('wh_material_receive_details ds');
        $builder->select("SUM(ds.qty) as rec_qty, fg.nameE as item_name, fg.item_code as company_code, l.nameE as unit_name,
                            (
                                SELECT SUM(rd.qty) FROM wh_material_receive_details rd 
                                LEFT OUTER JOIN wh_material_receive r ON(rd.receive_id=r.id)
                                WHERE rd.item_id=ds.item_id AND r.date < ".$from." AND r.status=1 AND r.isApproved=1
                            ) as opening_stock,
                            (
                                SELECT SUM(rd.aqty) FROM wh_machine_request_details rd 
                                LEFT OUTER JOIN wh_machine_requests r ON(rd.request_id=r.id)
                                WHERE rd.item_id=ds.item_id AND r.date <  ".$from." AND r.status=1 AND r.isApproved=1
                            ) as prev_consume_qty,
                            (
                                SELECT SUM(rd.aqty) FROM wh_machine_request_details rd 
                                LEFT OUTER JOIN wh_machine_requests r ON(rd.request_id=r.id)
                                WHERE rd.item_id=ds.item_id AND r.date = dw.date AND r.status=1 AND r.isApproved=1
                            ) as consume_qty
                            ");

        $builder->join("wh_material_receive dw", "dw.id=ds.receive_id", "left");
        $builder->join("wh_material fg", "fg.id=ds.item_id", "left");
        $builder->join("list_data l", "l.id=fg.unit_id", "left");

        $builder->where("ds.receive_id IS NOT NULL");        
        $builder->where("dw.date >=", $from);
        $builder->where("dw.date <=", $to);

        if(!empty($machine_id)){
            $builder->where("dw.machine_id", $machine_id);
        }

        $builder->groupBy("ds.item_id");

        $query1 = $builder->get()->getResult();        
        //echo get_last_query();exit;
        return $query1;
    }

    /*--------------------------
    | Get all item stock
    *--------------------------*/
    public function bdtaskt1m17_02_getPlantConsumption($machine_id, $from, $to){
        $builder = $this->db->table('wh_machine_request_details ds');
        $builder->select("SUM(ds.aqty) as consume_qty, m.nameE as plant_name, fg.nameE as item_name, l.nameE as unit_name");

        $builder->join("wh_machine_requests dw", "dw.id=ds.request_id", "left");
        $builder->join("wh_machine_store m", "m.id=dw.sub_store_id", "left");
        $builder->join("wh_material fg", "fg.id=ds.item_id", "left");
        $builder->join("list_data l", "l.id=fg.unit_id", "left");

        $builder->where("dw.date >=", $from);
        $builder->where("dw.date <=", $to);
        $builder->where("dw.status", 1);
        $builder->where("dw.isApproved", 1);
        $builder->where("dw.sub_store_id >", 0);

        if(!empty($machine_id)){
            $builder->where("dw.sub_store_id", $machine_id);
        }

        $builder->groupBy("dw.sub_store_id, ds.item_id");
        $builder->orderBy("dw.sub_store_id, ds.item_id");

        $query1 = $builder->get()->getResult();        
        //echo get_last_query();exit;
        return $query1;
    }


}
?>