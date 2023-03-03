<?php namespace App\Modules\Reports\Models;
use CodeIgniter\Model;

class Bdtaskt1m17BagReportModel extends Model
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
        $builder = $this->db->table('wh_bag_receive_details ds');
        $builder->select("SUM(ds.qty) as rec_qty, fg.nameE as item_name, fg.company_code, fg.bag_weight, l.nameE as unit_name,
                            (
                                SELECT SUM(rd.qty) FROM wh_bag_receive_details rd 
                                LEFT OUTER JOIN wh_material_receive r ON(rd.receive_id=r.id)
                                WHERE rd.item_id=ds.item_id AND r.date < ".$from." AND r.status=1 AND r.isApproved=1
                            ) as opening_stock,
                            (
                                SELECT SUM(rd.act_bags) FROM wh_receive_details rd 
                                LEFT OUTER JOIN wh_receive r ON(rd.receive_id=r.id)
                                WHERE rd.item_id=fg.finish_goods AND r.date < ".$from." AND r.status=1 AND r.isApproved=1
                            ) as prev_consume_qty,
                            (
                                SELECT SUM(rd.act_bags) FROM wh_receive_details rd 
                                LEFT OUTER JOIN wh_receive r ON(rd.receive_id=r.id)
                                WHERE rd.item_id=fg.finish_goods AND r.date = dw.date AND r.status=1 AND r.isApproved=1
                            ) as consume_qty
                            ");

        $builder->join("wh_bag_receive dw", "dw.id=ds.receive_id", "left");
        $builder->join("wh_bag fg", "fg.id=ds.item_id", "left");
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


}
?>