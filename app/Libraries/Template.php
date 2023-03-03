<?php namespace App\Libraries;
use App\Libraries\Permission;
class Template {
	public function __construct()
    {
        $this->session = session();
        $this->permission = new Permission();
        $this->db = db_connect();
        date_default_timezone_set('Asia/Dhaka');     
           
    }
	public function layout($data){
		$data['permission']    = $this->permission;
		$data['settings_info'] = $this->setting_data();
		$data['top_branch_list'] = $this->branch_list();
		$data['top_branch_name'] = '';//$data['top_branch_list'][session('branchId')];
		//$data['count_pending_ticket'] = $this->count_pending_ticket();
		return view('template/layout', $data);
	}

	public function getLangData(){
		$file = file_get_contents(base_url('assets/data/lang.json'));
        $data = json_decode($file, true);
        return $data;
	}

	public function setting_data(){
		$builder = $this->db->table('setting')
                             ->get()
                             ->getRow(); 
		return $builder;
	}

	public function branch_list(){
        $data = array(''=>'Select');

		$bulider = $this->db->table('branch');
		$bulider->select('id, nameE as text');
		$bulider->where('status',1);

        if(session('isAdmin')===false){
			$branch_op = session('branch_op');
			$branch_list = explode(",", $branch_op);
			$bulider->whereIn('id', $branch_list); 
		} else {
			$data['0'] =  'All Branch';
		}

		$result = $bulider->get()->getResult();

        foreach ($result as $row) {
            $data[$row->id] = $row->text;
        }
		return $data;
	}

	public function count_pending_ticket(){

		$bulider = $this->db->table('patient_ticket');
		$bulider->select('COUNT(*) as pending');
		$bulider->where('status',1);
		$bulider->where('assigned_user',session('id'));
		$bulider->where('ticket_status','Pending');

		$result = $bulider->get()->getRow();

		return $result->pending;
	}
}
