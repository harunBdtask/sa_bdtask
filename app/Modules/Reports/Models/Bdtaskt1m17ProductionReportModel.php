<?php namespace App\Modules\Reports\Models;
use CodeIgniter\Model;

class Bdtaskt1m17ProductionReportModel extends Model
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
    public function bdtaskt1m17_01_getDailyProduction($cat_id, $from, $to){
        $builder = $this->db->table('wh_receive_details ds');
        $builder->select("SUM(ds.act_bags) as prod_qty, ds.bag_size, fg.nameE as item_name, fg.company_code, l.nameE as unit_name,
                            (
                                SELECT SUM(rd.act_bags) FROM wh_receive_details rd 
                                LEFT OUTER JOIN wh_receive r ON(rd.receive_id=r.id)
                                WHERE rd.item_id=ds.item_id AND r.date < ".$from." AND r.status=1 AND r.isApproved=1
                            ) as opening_stock,
                            (
                                SELECT SUM(rd.quantity) FROM do_delivery_details rd 
                                LEFT OUTER JOIN do_delivery r ON(rd.delivery_id=r.delivery_id)
                                WHERE rd.item_id=ds.item_id AND r.do_date < ".$from." AND r.status=1 AND r.fc_m_approved=1
                            ) as prev_delivery_qty,
                            (
                                SELECT SUM(rd.quantity) FROM do_delivery_details rd 
                                LEFT OUTER JOIN do_delivery r ON(rd.delivery_id=r.delivery_id)
                                WHERE rd.item_id=ds.item_id AND r.do_date = dw.date AND r.status=1
                            ) as delivery_order,
                            (
                                SELECT SUM(rd.quantity) FROM do_delivery_details rd 
                                LEFT OUTER JOIN do_delivery r ON(rd.delivery_id=r.delivery_id)
                                WHERE rd.item_id=ds.item_id AND r.do_date = dw.date AND r.status=1 AND r.fc_m_approved =1
                            ) as delivery_qty,
                            (
                                SELECT SUM(rd.quantity) FROM do_delivery_details rd 
                                LEFT OUTER JOIN do_delivery r ON(rd.delivery_id=r.delivery_id)
                                WHERE rd.item_id=ds.item_id AND r.do_date = dw.date AND r.status=1 AND r.fc_m_approved !=1
                            ) as undelivered_qty
                            ");

        $builder->join("wh_receive dw", "dw.id=ds.receive_id", "left");
        $builder->join("wh_items fg", "fg.id=ds.item_id", "left");
        $builder->join("list_data l", "l.id=fg.unit_id", "left");

        $builder->where("ds.receive_id IS NOT NULL");        
        $builder->where("dw.date >=", $from);
        $builder->where("dw.date <=", $to);

        if(!empty($cat_id)){
            $builder->where("fg.cat_id", $cat_id);
        }

        $builder->groupBy("ds.item_id");

        $query1 = $builder->get()->getResult();        

        return $query1;
    }


}
?>