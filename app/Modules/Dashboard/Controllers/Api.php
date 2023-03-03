<?php namespace App\Modules\Dashboard\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Modules\Dashboard\Models\ApiModel;
use Firebase\JWT\JWT;
use App\Libraries\Authorization_Token;
use App\Models\Bdtaskt1m1CommonModel;
use CodeIgniter\I18n\Time;


class Api extends BaseController
{
    // use ResponseTrait;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->db = db_connect();
        $this->authorization_token = new Authorization_Token();
        $this->bdtaskt1m1CommonModel = new Bdtaskt1m1CommonModel();
        //header
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        Header('Access-Control-Allow-Headers: *');
        Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
           
    }

    public function JSONSuccessOutput($response=NULL, $msg='')
    {
        header('Content-Type: application/json');
        $data['response_status'] = 200;
        $data['message'] = $msg;
        $data['status'] = 'success';
        $data['data'] = $response;
        
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function JSONErrorOutput($errorMessage = 'Unknown Error')
    {
        header('Content-Type: application/json');
        $data['response_status'] = 0;
        $data['message'] = $errorMessage;
        $data['status'] = 'failed';
        $data['data'] = null;
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function JSONNoOutput($errorMessage = "No data found")
    {
        header('Content-Type: application/json');
        $data['response_status'] = 204;
        $data['message'] = $errorMessage;
        $data['status'] = 'failed';
        $data['data'] = null;
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function filter_input_data($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function tokenVerify($emp_id)
    {
        $authorization = $this->request->getServer('HTTP_AUTHORIZATION');
        if (empty($authorization)) {
            $this->JSONErrorOutput("Empty Authorization Request!");
        }
        
        $decodedToken = $this->authorization_token->validateToken($authorization);
        if (empty($decodedToken['status'])) {
            $this->JSONErrorOutput("Invalid Authorization Request!");
        }
        if ($emp_id == $decodedToken['data']->emp_id) {
            return 'ok';
        }else{
            header('Content-Type: application/json');
            $response['response_status'] = 401;
            $response['message'] = 'Invalid Token';
            $response['status'] = 'failed';
            $response['data'] = null;
            echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit;
        }
    }

    public function index()
    {
        echo 200;
    }

    public function login()
    {
        $username    = $this->filter_input_data($this->request->getVar('username'));
        $password = $this->filter_input_data($this->request->getVar('password'));
          
        $user = $this->db->table('user')->where('username', $username)->get()->getRow();
        if(is_null($user)) {
            return $this->JSONErrorOutput('Invalid username or password.');
        }
  
        $verify = $this->password_verify(md5($password), $user->password);
        if(!$verify) return $this->JSONErrorOutput('Wrong Password');
 
        $token_data['username'] = $username;
        $token_data['emp_id'] = $user->emp_id;

        $tokenData = $this->authorization_token->generateToken($token_data);

        $final = array();
        $final['emp_id'] = $user->emp_id;
        $final['fullname'] = $user->fullname;
        $final['avatar'] = base_url('/assets/dist/img/employee/1644920694_99dac440a9e34b6253f7.png');
        $final['token'] = $tokenData;

         
        return $this->JSONSuccessOutput($final, 'Successfully Loged in');
    }

    public function password_verify($input_password,$exist_password)
    {
        return ($input_password == $exist_password?true:false);
    }


    public function emp_attendence()
    {
        date_default_timezone_set('Asia/Dhaka');
        $timestamp = time();
        $start_timestamp = mktime(10,0,0,date('m'),date('d'),date('Y'));
        $end_timestamp = mktime(23,30,0,date('m'),date('d'),date('Y'));
        $time = date('Y-m-d H:i:s');
        
        $emp_id = $this->filter_input_data($this->request->getVar('emp_id'));
        $address = $this->filter_input_data($this->request->getVar('address'));
        $latitude = $this->filter_input_data($this->request->getVar('latitude'));
        $longitude = $this->filter_input_data($this->request->getVar('longitude'));
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'emp_id'       => 'required',
            ];
        }
        if (! $this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $errors_data = 'Invalid Request!';
            if (!empty($errors['emp_id'])) {
                $errors_data = $errors['emp_id'];
            }
            $this->JSONErrorOutput($errors_data);
        }
        $tokenVerify = $this->tokenVerify($emp_id);
        if ($tokenVerify != 'ok') {
            $this->JSONErrorOutput("Invalid Token!");
        }
        
        $result = $this->check_attendence($emp_id);

        if ($result) {
            $this->JSONSuccessOutput(array('status'=>false), 'Already Attendence Completed');
        }else{
            if ($start_timestamp <= $timestamp & $timestamp <= $end_timestamp) {
                if ($address) {
                    $data = array(
                        'emp_id' => $emp_id, 
                        'address' => $address, 
                        'latitude' => $latitude, 
                        'longitude' => $longitude, 
                        'date' => date('Y-m-d'), 
                        'date_time' => date('Y-m-d H:i:s'), 
                    );
                    $this->set_emp_attendence($data);
                    $this->JSONSuccessOutput(null, 'Successfully Saved');
                }else{
                    $this->JSONSuccessOutput(array('status'=>true), 'Ready for Attendence');
                }
            }else{
                $this->JSONSuccessOutput(array('status'=>false), 'Attendence Time Over');
            }
        }
    }

    public function set_emp_attendence($data)
    {
        $postData = array(
            'uid'           => $data['emp_id'],
            'atten_date'    => $data['date'],
            'time'          => $data['date_time'],
        );
        $hrm_attendance = $this->bdtaskt1m1CommonModel->bdtaskt1m1_01_Insert('hrm_attendance_history',$postData);
        $tour_planData = array(
            'hrm_attendance' => $hrm_attendance,
            'emp_id'    => $data['emp_id'],
            'date'      => $data['date'], 
            'address'   => $data['address'],
            'latitude'  => $data['latitude'],
            'longitude' => $data['longitude'],
            'revised'   => 0,
        );
        $this->bdtaskt1m1CommonModel->bdtaskt1m1_01_Insert('tour_plan',$tour_planData);
        return true;

    }



    public function check_attendence($id)
    {
        $data = $this->db->table('hrm_attendance_history a')->select('a.*')
            ->where(array('a.uid'=>$id,'a.atten_date'=>date('Y-m-d')))
            ->get()
            ->getRowArray();

        return $data;
    }


    public function products()
    {
        $search = $this->filter_input_data($this->request->getVar('search'));
        $per_page = $this->filter_input_data($this->request->getVar('per_page'));
        $start = $this->filter_input_data($this->request->getVar('start'));
        $pagination = array(
            'per_page' => ($per_page?$per_page:100), 
            'start' => ($start?$start:0), 
        );
        if($search){
            $where = array(
                'nameE' => $search, 
            );
            $data = $this->items_list($pagination, $where);
        }else{
            $data = $this->items_list($pagination);
        }
        return $this->JSONSuccessOutput($data);
    }

    public function items_list($pagination=null, $data=null)
    {
        $builder = $this->db->table('wh_items');
        $builder->select("id, CONCAT_WS(' ', nameE,'(',company_code,')') AS text, price");
        $builder->where('status', 1);
        if ($data) {
            $builder->like($data);
        }
        $builder->limit($pagination['per_page'], $pagination['start']);
        $builder->orderBy('nameE', 'asc');
        $query   =  $builder->get();
        $records =   $query->getResult();
        return $records;
    }

    public function tour_plan_list()
    {
        $emp_id = $this->filter_input_data($this->request->getVar('emp_id'));
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'emp_id'       => 'required',
            ];
        }
        if (! $this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $errors_data = 'Invalid Request!';
            if (!empty($errors['emp_id'])) {
                $errors_data = $errors['emp_id'];
            }
            $this->JSONErrorOutput($errors_data);
        }
        $tokenVerify = $this->tokenVerify($emp_id);
        if ($tokenVerify != 'ok') {
            $this->JSONErrorOutput("Invalid Token!");
        }
        $result = $this->tour_plan($emp_id);

        if ($result) {
            $this->JSONSuccessOutput($result);
        }else{
            $this->JSONNoOutput();
        }
    }

    public function revised_tour_plan()
    {
        $emp_id = $this->filter_input_data($this->request->getVar('emp_id'));
        $tour_id = $this->filter_input_data($this->request->getVar('tour_id'));
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'emp_id'       => 'required',
                'tour_id'       => 'required',
            ];
        }
        if (! $this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $errors_data = 'Invalid Request!';
            if (!empty($errors['emp_id'])) {
                $errors_data = $errors['emp_id'];
            }
            if (!empty($errors['tour_id'])) {
                $errors_data = $errors['tour_id'];
            }
            $this->JSONErrorOutput($errors_data);
        }
        $tokenVerify = $this->tokenVerify($emp_id);
        if ($tokenVerify != 'ok') {
            $this->JSONErrorOutput("Invalid Token!");
        }
        $result = $this->check_tour_plan($tour_id);

        if ($result) {
            $this->bdtaskt1m1CommonModel->bdtaskt1m1_02_Update('tour_plan',array('revised'=>1), array('id'=>$tour_id));
            $this->JSONSuccessOutput(null, 'Saved Successfully');
        }else{
            $this->JSONErrorOutput('Date expired or already revised');
        }
    }

    public function check_tour_plan($id = null)
    {
        $builder = $this->db->table('tour_plan a');
        $builder->select("a.id,a.date,a.address,a.revised");
        $builder->where(array('a.id'=>$id,'a.revised'=>0,'a.date'=>date('Y-m-d')));
        $query   =  $builder->get();
        $records =  $query->getResult();
        return $records;
    }

    public function tour_plan($id = null)
    {
        $builder = $this->db->table('tour_plan a');
        $builder->select("a.id,a.date,a.address,a.revised");
        // $builder->join("wh_items b", 'b.id = a.item_id', 'left');
        $builder->where('a.emp_id', $id);
        $query   =  $builder->get();
        $records =   $query->getResult();
        return $records;
    }

    public function doList()
    {
        $emp_id = $this->filter_input_data($this->request->getVar('emp_id'));
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'emp_id'       => 'required',
            ];
        }
        if (! $this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $errors_data = 'Invalid Request!';
            if (!empty($errors['emp_id'])) {
                $errors_data = $errors['emp_id'];
            }
            $this->JSONErrorOutput($errors_data);
        }
        $tokenVerify = $this->tokenVerify($emp_id);
        if ($tokenVerify != 'ok') {
            $this->JSONErrorOutput("Invalid Token!");
        }
        $postData = array(
            'emp_id' => $emp_id, 
        );
        //
        $enddate = $this->filter_input_data($this->request->getVar('enddate'));
        $startdate = $this->filter_input_data($this->request->getVar('startdate'));
        $dealer_id = $this->filter_input_data($this->request->getVar('dealer_id'));
        $vouhcer_no = $this->filter_input_data($this->request->getVar('vouhcer_no'));
        $per_page = $this->filter_input_data($this->request->getVar('per_page'));
        $start = $this->filter_input_data($this->request->getVar('start'));
        $postData = array(
            'emp_id' => $emp_id, 
            'per_page' => ($per_page?$per_page:100), 
            'start' => ($start?$start:0), 
            'dealer_id' => $dealer_id, 
            'vouhcer_no' => $vouhcer_no, 
            'startdate' => $startdate, 
            'enddate' => $enddate, 
        );
        $result = $this->salesList($postData);

        if ($result) {
            $this->JSONSuccessOutput($result);
        }else{
            $this->JSONNoOutput();
        }
    }

    public function employee_sales()
    {
        $emp_id = $this->filter_input_data($this->request->getVar('emp_id'));
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'emp_id'       => 'required',
            ];
        }
        if (! $this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $errors_data = 'Invalid Request!';
            if (!empty($errors['emp_id'])) {
                $errors_data = $errors['emp_id'];
            }
            $this->JSONErrorOutput($errors_data);
        }
        $tokenVerify = $this->tokenVerify($emp_id);
        if ($tokenVerify != 'ok') {
            $this->JSONErrorOutput("Invalid Token!");
        }
        $result = $this->do_main_by_emp($emp_id);
        
        if ($result) {
            $total = $this->do_total_by_emp($emp_id);
            $data['response_status'] = 200;
            $data['message'] = '';
            $data['status'] = 'success';
            $data['total_amount'] = $total->total/1000;
            $data['unit_name'] = "MT";
            $data['data'] = $result;
            
            echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit;
        }else{
            $this->JSONNoOutput();
        }
    }

    public function salesList($postData = null)
    {
        ## Read value
        @$searchValue     = $postData['search']['value']; //Search value
        $emp_id          = $postData['emp_id'];
        ## Search 
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (b.name like '%" . $searchValue . "%' OR a.do_date like '%" . $searchValue . "%' ) ";
        }
        ## Fetch records
        $builder3 = $this->db->table('do_main a');
        $builder3->select("a.do_id,a.vouhcer_no,a.do_date,a.status,a.dl_s_approved,a.str_s_approved,a.grand_total,b.name as dealer_name");
        $builder3->join("dealer_info b", 'b.id = a.dealer_id', 'left');
        $builder3->where('do_by', $emp_id);
        if ($postData['dealer_id']) {
            $builder3->where('a.dealer_id', $postData['dealer_id']);
        }
        if ($postData['vouhcer_no']) {
            $builder3->like('a.vouhcer_no', $postData['vouhcer_no']);
            // $builder3->where('a.vouhcer_no', $postData['vouhcer_no']);
        }
        if ($postData['startdate'] AND $postData['enddate']) {
            $builder3->where('a.do_date >=', $postData['startdate']);
            $builder3->where('a.do_date <=', $postData['enddate']);
            // $builder3->where("a.do_date BETWEEN ". $postData['startdate']." AND ".$postData['enddate']);
        }
        if ($searchValue != '') {
            $builder3->where($searchQuery);
        }
        $builder3->orderBy('a.id', 'desc');
        $builder3->limit($postData['per_page'], $postData['start']);
        $query3   =  $builder3->get();
        if ($builder3->countAll() > 0) {
            $data = $query3->getResultArray();
            foreach ($data as $key => $value) {
                $check_delivery_status = $this->do_delivery_status($value['do_id']);
                if ($value['status'] == 0) {
                    $delv_status = 'Pending';
                }

                if ($value['status'] == 1 && $value['dl_s_approved'] == 0) {
                    $delv_status = 'In Progress';
                }

                if ($value['status'] == 1 && $value['dl_s_approved'] == 1 && $value['str_s_approved'] == 0) {
                    $delv_status = 'In Delivery Store';
                }
                if ($check_delivery_status == 1) {
                    if ($value['status'] == 1 && $value['dl_s_approved'] == 1 && $value['str_s_approved'] == 1) {
                        $delv_status = 'Partial Delivered';
                    }
                } else {
                    if ($value['status'] == 1 && $value['dl_s_approved'] == 1 && $value['str_s_approved'] == 1) {
                        $delv_status = 'Delivered';
                    }
                }

                
                $data[$key]['do_status'] = $delv_status;
                
                $data[$key]['details'] = $this->do_details_byid($value['do_id']);
            }
            return $data;
        } else {
            $data = $query3->getResultArray();
            return $data;
        }

        
    }

    public function getDoDetailsById($id)
    {
        return $this->do_details_byid($id);
    }

    public function do_main_by_emp($id = null)
    {
        $builder = $this->db->table('do_main a');
        $builder->select("a.do_date, sum(a.total_kg) as grand_total");
        $builder->where('a.do_by', $id);
        $builder->groupBy('a.do_date');
        $builder->orderBy('a.id', 'desc');
        $builder->limit(5, 0);
        $query   =  $builder->get();
        $records =  $query->getResultArray();
        return $records;
    }

    public function do_total_by_emp($id = null)
    {
        $builder = $this->db->table('do_main a');
        $builder->select("sum(a.total_kg) as total");
        $builder->where('a.do_by', $id);
        $query   =  $builder->get();
        $records =  $query->getRow();
        return $records;
    }

    public function do_main_byid($id = null)
    {
        $builder = $this->db->table('do_main a');
        $builder->select("a.*,b.name as dealer_name,b.reference_id,b.address,b.phone_no,(b.commission_rate / 100) as b.commission_rate,u.fullname");
        $builder->join("dealer_info b", 'b.id = a.dealer_id');
        $builder->join("user u", 'u.emp_id = a.do_by');
        $builder->where('a.do_id', $id);
        $query   =  $builder->get();
        $records =   $query->getRow();
        return $records;
    }

    public function do_details_byid($id = null)
    {
        $builder = $this->db->table('do_details a');
        $builder->select("CONCAT_WS(' ', b.nameE,'(',b.company_code,')') AS item_name,a.quantity");
        $builder->join("wh_items b", 'b.id = a.item_id', 'left');
        $builder->where('a.do_id', $id);
        $builder->groupBy('a.item_id');
        $query   =  $builder->get();
        $records =   $query->getResult();
        return $records;
    }

    public function bdtaskt1m12_03_getSalesPersonDetailsById($id)
    {
        $data = $this->db->table('hrm_employees')->select('*')
            ->where('employee_id', $id)
            ->get()
            ->getRow();

        return $data;
    }

    public function setting_info()
    {
        $settingdata = $this->db->table('setting')->select('*')->get()->getRow();
        return $settingdata ;
    }


    public function dealerByEmployee()
    {
        $emp_id = $this->filter_input_data($this->request->getVar('emp_id'));
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'emp_id'       => 'required',
            ];
        }
        if (! $this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $errors_data = 'Invalid Request!';
            if (!empty($errors['emp_id'])) {
                $errors_data = $errors['emp_id'];
            }
            $this->JSONErrorOutput($errors_data);
        }
        $tokenVerify = $this->tokenVerify($emp_id);
        if ($tokenVerify != 'ok') {
            $this->JSONErrorOutput("Invalid Token!");
        }
        $result  = $this->employeeWiseDealer($emp_id);


        $res = array(
            'product_name' => '', 
        );
        if ($result) {
            $this->JSONSuccessOutput($result);
        }else{
            $this->JSONNoOutput();
        }
    }

    public function products_batchstockdata()
    {
        $item_id = $this->filter_input_data($this->request->getVar('item_id'));
        $quantity = $this->filter_input_data($this->request->getVar('quantity'));
        $emp_id = $this->filter_input_data($this->request->getVar('emp_id'));
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'item_id'       => 'required',
                'emp_id'       => 'required',
            ];
        }
        if (! $this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $errors_data = 'Invalid Request!';
            if (!empty($errors['item_id'])) {
                $errors_data = $errors['item_id'];
            }
            if (!empty($errors['quantity'])) {
                $errors_data = $errors['quantity'];
            }
            if (!empty($errors['emp_id'])) {
                $errors_data = $errors['emp_id'];
            }
            $this->JSONErrorOutput($errors_data);
        }
        $tokenVerify = $this->tokenVerify($emp_id);
        if ($tokenVerify != 'ok') {
            $this->JSONErrorOutput("Invalid Token!");
        }
        $result  = $this->item_batchstockdata($item_id);

        $total_weight = $result['bag_weight']*$quantity;
        $total_price = $result['price']*$quantity;
        $res = array(
            'product_id' => $item_id, 
            'product_name' => $result['item_info']->nameE, 
            'product_code' => $result['item_info']->item_code, 
            'quantity' => "$quantity", 
            'bag_size' => $result['bag_weight'], 
            'total_weight' => "$total_weight", 
            'unit_price' => $result['price'], 
            'total_price' => "$total_price", 
        );
        return $this->JSONSuccessOutput($res);
    }

    public function item_batchstockdata($item_id)
    {
        // $builder = $this->db->table('wh_receive_details');
        // $builder->select('sum(avail_qty) as stock,price');
        // $builder->where('item_id', $item_id);
        // $query = $builder->get();
        // $data = $query->getRow();
        // $pending_s = $this->db->table('do_details_draft a')
        //     ->select('sum(a.quantity) as pendinstock')
        //     ->join('do_main_draft b', 'b.do_id = a.do_id', 'left')
        //     ->where('a.item_id', $item_id)
        //     ->where('b.status', 1)
        //     ->get()
        //     ->getRow();
        // $result['avail_stock'] = ($data ? $data->stock : 0) - ($pending_s ? $pending_s->pendinstock : 0);
        $item_info         = $this->db->table('wh_items')->select('*')->where('id', $item_id)->get()->getRow();
        $item_currentprice = $this->db->table('goods_pricing')->select('*')->where('product_id', $item_id)->orderBy('date', 'desc')->get()->getRow();
        $result['price']       = ($item_currentprice ? $item_currentprice->price : 0);
        $result['commission_rate']    = ($item_info->com_rate ? $item_info->com_rate : 0);
        $result['bag_weight']  = ($item_info ? $item_info->bag_weight : 0);
        $result['item_info']  = $item_info;
        if ($result['price'] < 1) {
            $this->JSONErrorOutput("Price not set for this item, please set price");
        }
        if ($result['bag_weight'] < 1) {
            $this->JSONErrorOutput("Bag weight not set for this item, please set bag weight");
        }
        return $result;
    }

    public function save_dodata()
    {
        $do_id = date('Ymdhis');
        $vouhcer_no = $this->voucher_no_generator();
        $postData = array(
            'do_id'         => $do_id,
            'vouhcer_no'    => $vouhcer_no,
            'do_date'       => $this->request->getVar('date'),
            'dealer_id'     => $this->request->getVar('dealer_id'),
            'do_by'         => $this->request->getVar('emp_id'),
            'status'        => 0,
            'created_by'    => $this->request->getVar('emp_id'),
        );

        $tokenVerify = $this->tokenVerify($this->request->getVar('emp_id'));
        if ($tokenVerify != 'ok') {
            $this->JSONErrorOutput("Invalid Token!");
        }

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'dealer_id'   => ['label' => get_phrases(['dealer']), 'rules' => 'required'],
                'date'        => ['label' => get_phrases(['date']), 'rules'   => 'required'],
                'order_details' => ['label' => get_phrases(['order','details']), 'rules'   => 'required'],
            ];

            if (! $this->validate($rules)) {
                $errors = $this->validator->getErrors();
                $errors_data = 'Invalid Request!';
                if (!empty($errors['dealer_id'])) {
                    $errors_data = $errors['dealer_id'];
                }
                if (!empty($errors['date'])) {
                    $errors_data = $errors['date'];
                }
                $this->JSONErrorOutput($errors_data);
            } else {
                $result = $this->bdtaskt1m1_05_saveDo($postData);
                // Store log data
                if ($result) {
                    // $this->bdtaskt1m1CommonModel->bdtaskt1m1_22_addActivityLog(get_phrases(['do', 'request']), get_phrases(['created']), $postData['vouhcer_no'], 'do_main', 1);
                    $this->JSONSuccessOutput(array('do_id'=> $do_id, 'vouhcer_no'=> $vouhcer_no),'Successfully saved');
                } else {
                    $this->JSONErrorOutput('Please Try again');
                }
            }
        }
    }

    public function bdtaskt1m1_05_saveDo($data = [])
    {

        $dealer_info = $this->bdtaskt1m12c2_04_getdealersById($data['dealer_id']);
        $dealer_commission_rate = $dealer_info['dealer']->commission_rate ? $dealer_info['dealer']->commission_rate : 0;
        $data2 = array();
        $order_details_info = $this->request->getVar('order_details');
        $order_details = json_decode($order_details_info);
        $grand_total = 0;
        $grand_total_kg = 0;
        foreach($order_details as $key => $item){
            $item_info = $this->item_batchstockdata($item->item_id);
            $total_kg = $item_info['bag_weight']*$item->quantity;
            $grand_total_kg += $item_info['bag_weight']*$item->quantity;
            $dealer_commission = $total_kg*$dealer_commission_rate;
            $item_price = $item_info['price']*$item->quantity;
            $total_price = $item_price - $dealer_commission;
            $grand_total += $total_price;
            $data2[] = array(
                'do_id'                  => $data['do_id'],
                'item_id'                => $item->item_id,
                'quantity'               => $item->quantity,
                'bag_weight'             => $item_info['bag_weight'],
                'batch_id'               => 0,
                'total_kg'               => $total_kg,
                'commission_rate'        => $item_info['commission_rate'],
                'commission_amount'      => $total_kg*$item_info['commission_rate'],
                'dealer_commission_rate' => $dealer_commission_rate,
                'dealer_commission'      => $dealer_commission,
                'unit_price'             => $item_info['price'],
                'total_price'            => $total_price,
            );
        }
        
        $this->bdtaskt1m1CommonModel->bdtaskt1m1_01_Insert_Batch('do_details',$data2);
        $this->bdtaskt1m1CommonModel->bdtaskt1m1_01_Insert_Batch('do_details_draft',$data2);

        $data['grand_total'] = $grand_total;
        $data['total_kg'] = $grand_total_kg;
        $postdraftData = array(
            'do_id'         => $data['do_id'],
            'vouhcer_no'    => $data['vouhcer_no'],
            'do_date'       => $data['do_date'],
            'dealer_id'     => $data['dealer_id'],
            'grand_total'   => $data['grand_total'],
            'do_by'         => $data['do_by'],
            'status'        => 1,
            'created_by'    => $data['created_by'],
        );

        $this->bdtaskt1m1CommonModel->bdtaskt1m1_01_Insert('do_main',$data);
        $this->bdtaskt1m1CommonModel->bdtaskt1m1_01_Insert('do_main_draft',$postdraftData);
        return true;
    }

    public function voucher_no_generator()
    {
        $builder = $this->db->table('do_main');
        $builder->select('max(id) as voucher_no');
        $query = $builder->get();
        $data = $query->getRow();
        if (!empty($data->voucher_no)) {
            $invoice_no = $data->voucher_no + 1000;
        } else {
            $invoice_no = 1000;
        }
        return   'DO-' . $invoice_no;
    }

    public function bdtaskt1m12c2_04_getdealersById($id)
    {
        $data = $this->bdtaskt1m12_03_getDealerDetailsById($id);
        $previous = $this->bdtaskt1m12_03_getDealerPreviousBalance($id);
        $result = array('dealer' => $data,'previous_balance' => $previous);
        return $result;
    }

    public function employeeWiseDealer($id)
    {
        $data = $this->db->table('dealer_info')->select('dealer_info.id, name, dealer_code, address, email, phone_no, (commission_rate / 100) as commission_rate')  
            ->join('sales_officer_assign soa', 'soa.dealer_id=dealer_info.id','left')   
            ->where('soa.sales_officer_id',$id )
            ->get()
            ->getResult();

        return $data;
    }

    public function bdtaskt1m12_03_getDealerDetailsById($id)
    {
        $data = $this->db->table('dealer_info')->select('dealer_info.*, (dealer_info.commission_rate / 100) as commission_rate, zone.zone_name,c.name as reference_by')        
            ->join('zone_tbl as zone', 'zone.id=dealer_info.zone_id','left')
            ->join('dealer_info c', 'c.affiliat_id=dealer_info.reference_id','left')
            ->where('dealer_info.id',$id )
            ->get()
            ->getRow();

        return $data;
    }

    public function bdtaskt1m12_03_getDealerPreviousBalance($id)
    {
        $dealer_coa = $this->db->table('acc_transaction a')
            ->select('SUM(a.Debit) as total_debit,SUM(a.Credit) as total_credit,b.HeadName')
            ->join('acc_coa b','a.COAID = b.HeadCode','left')
            ->where('b.dealer_id',$id)
            ->get()
            ->getRow();
            $debit = ($dealer_coa?$dealer_coa->total_debit:0);
            $credit = ($dealer_coa?$dealer_coa->total_credit:0);
            $balance = ($debit?$debit:0) - ($credit?$credit:0);
        return $balance;
    }

    public function do_delivery_status($do_id)
    {
        $request_order = $this->db->table('do_details')->select('sum(quantity) as total_request')->where('do_id', $do_id)->get()->getRow();
        $delivered_order = $this->db->table('do_delivery_details')->select('sum(quantity) as total_delivered')->where('do_id', $do_id)->get()->getRow();

        $req_qty = ($request_order ? $request_order->total_request : 0);
        $del_qty = ($delivered_order ? $delivered_order->total_delivered : 0);
        $response = ($req_qty ? $req_qty : 0) - ($del_qty ? $del_qty : 0);
        $status = 0;
        if ($response > 0) {
            $status = 1;
        }

        return $status;
    }
    

}