<?php

namespace App\Modules\Sale\Models;

use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m12DealerModel extends Model
{

    public function __construct()
    {
        $this->db              = db_connect();
        $this->permission      = new Permission();
        $this->hasReadAccess   = $this->permission->method('dealer_list', 'read')->access();
        $this->hasCreateAccess = $this->permission->method('dealer_list', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('dealer_list', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('dealer_list', 'delete')->access();
    }

    public function bdtaskt1m12_02_getAll($postData = null)
    {
        $response = array();
        ## Read value

        @$reference_by    = trim($postData['reference_by']);
        @$draw            = $postData['draw'];
        @$start           = $postData['start'];
        @$rowperpage      = $postData['length']; // Rows display per page
        @$columnIndex     = $postData['order'][0]['column']; // Column index
        @$columnName      = $postData['columns'][$columnIndex]['data']; // Column name
        @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        @$searchValue     = $postData['search']['value']; // Search value
        ## Search 
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (dealer_info.name like '%" . $searchValue . "%' OR dealer_info.phone_no like '%" . $searchValue . "%' OR dealer_info.email like '%" . $searchValue . "%') ";
        }
        if ($reference_by != '') {
            if ($searchQuery != '') {
                $searchQuery .= " AND ";
            }
            $searchQuery .= " ( dealer_info.reference_id = '" . $reference_by . "' ) ";
        }
        ## Fetch records
        $builder3 = $this->db->table('dealer_info');
        $builder3->select("dealer_info.*, CONCAT_WS(' ', zone.zone_name,' - ',zone.districts,' - ',zone.division) as zone_name,c.name as reference_by,u.fullname as sales_officer");
        if ($searchQuery != '') {
            $builder3->where($searchQuery);
        }
        $builder3->join('zone_tbl zone', 'zone.id=dealer_info.zone_id', 'left');
        $builder3->join('dealer_info c', 'c.affiliat_id=dealer_info.reference_id', 'left');
        $builder3->join('user u', 'u.emp_id=dealer_info.sales_officer_id', 'left');
        $builder3->groupBy('dealer_info.id');
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3  =  $builder3->get();
        $records =  $query3->getResultArray();
        ## Total number of records without filtering
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $totalRecords = $builder3->countAll();
        if ($searchQuery == '') {
            $totalRecords = $totalRecordwithFilter;
        }
        $data    = array();
        $details = get_phrases(['view', 'details']);
        $update  = get_phrases(['update']);
        $delete  = get_phrases(['delete']);
        $sl      = 1;
        foreach ($records as $record) {
            $button = '';
            $button .= (!$this->hasReadAccess) ? '' : '<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="' . $details . '" data-id="' . $record['id'] . '"><i class="far fa-eye"></i></a>';
            $button .= (!$this->hasUpdateAccess) ? '' : '<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" title="' . $update . '" data-id="' . $record['id'] . '"><i class="far fa-edit"></i></a>';
            $button .= (!$this->hasUpdateAccess)?'':'<a href="javascript:void(0);" class="btn btn-info-soft btnC roleAction mr-1 custool" title="Assign Sales Officer" data-id="'.$record['id'].'"><i class="fas fa-user-lock"></i></a>';
            $button .= (!$this->hasDeleteAccess) ? '' : '<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="' . $delete . '" data-id="' . $record['id'] . '"><i class="far fa-trash-alt"></i></a>';

            $data[] = array(
                'id'              => ($postData['start'] ? $postData['start'] : 0) + $sl,
                'name'            => $record['name'],
                'dealer_code'     => $record['dealer_code'],
                'email'           => $record['email'],
                'phone_no'        => $record['phone_no'],
                'affiliat_id'     => $record['affiliat_id'],
                'zone_name'       => $record['zone_name'],
                'reference_by'    => $record['reference_by'],
                'commission_rate' => ($record['commission_rate'] ? $record['commission_rate'] : 0),
                // 'sales_officer'   => $record['sales_officer'],
                'sales_officer'   => $this->getAssignedOfficers($record['id']),
                'dealer_type'     => ($record['type'] == 1 ? 'Cash Dealer' : 'Credit Dealer'),
                'button'          => $button
            );
            $sl++;
        }

        ## Response
        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData"               => $data
        );
        return $response;
    }

    public function bdtaskt1m12_03_getDealerDetailsById($id)
    {
        $data = $this->db->table('dealer_info')->select('dealer_info.*, zone.zone_name,c.name as reference_by,u.fullname as sales_officer')
            ->join('zone_tbl as zone', 'zone.id=dealer_info.zone_id', 'left')
            ->join('dealer_info c', 'c.affiliat_id=dealer_info.reference_id', 'left')
            ->join('user u', 'u.emp_id=dealer_info.sales_officer_id', 'left')
            ->where('dealer_info.id', $id)
            ->get()
            ->getRow();

        return $data;
    }

    public function getAssignedOfficers($id=null){
        $query = $this->db->table('sales_officer_assign a')->select("b.fullname")
                    ->join('user b', 'b.emp_id=a.sales_officer_id', 'left')
                    ->where('a.dealer_id', $id)
                    ->get()->getResult();
        $list = '';
        if(!empty($query)){
            foreach ($query as $value) {
                $list .= '<span class="badge badge-success-soft mr-1">'.$value->fullname.'</span>';
            }
        }
        return $list;
    }

    /*--------------------------
    | Get Sales officers by dealer Id
    *--------------------------*/
    public function getSalesOfficersByDealerId($dealer_id=null){
        $query = $this->db->table('dealer_info a')->select("a.id,a.name,a.dealer_code")
                        ->where('a.id', $dealer_id)
                        ->get()->getRow();
        $roles = $this->db->table('sales_officer_assign')->select("sales_officer_id")
                        ->where('dealer_id', $dealer_id)
                        ->get()->getResult();
        $i=0;
        $list[] = '';
        if(!empty($roles)){
            foreach ($roles as $key => $value) {
                $list[$i]=$value->sales_officer_id;
                $i++;
            }
        }

        $query->assigned_officer_ids = $list;
        return $query;
    }

    public function bdtaskt1m12_03_getDealerPreviousBalance($id)
    {
        $dealer_coa = $this->db->table('acc_transaction a')
            ->select('SUM(a.Debit) as total_debit,SUM(a.Credit) as total_credit,b.HeadName')
            ->join('acc_coa b', 'a.COAID = b.HeadCode', 'left')
            ->where('b.dealer_id', $id)
            ->get()
            ->getRow();
        $debit = ($dealer_coa ? $dealer_coa->total_debit : 0);
        $credit = ($dealer_coa ? $dealer_coa->total_credit : 0);
        $balance = ($debit ? $debit : 0) - ($credit ? $credit : 0);
        return ($balance > 0 ? '<span class="text-success">' . $balance . '</span>' : '<span class="text-danger">' . $balance . '</span>');
    }



    public function bdtask_002_zonelist()
    {
        $builder = $this->db->table('zone_tbl');
        $builder->select('*');
        $query  = $builder->get();
        $data = $query->getResult();

        $list = array('' => get_phrases(['select', 'zone']));
        if (!empty($data)) {
            foreach ($data as $value) {
                $list[$value->id] = $value->zone_name.' - '.$value->districts.' - '.$value->division;
            }
        }
        return $list;
    }

    public function bdtask_003_referarlist()
    {
        $builder = $this->db->table('dealer_info');
        $builder->select('*');
        $query = $builder->get();
        $data = $query->getResult();

        $list = array('' => get_phrases(['select', 'referar']));
        if (!empty($data)) {
            foreach ($data as $value) {
                $list[$value->affiliat_id] = $value->name;
            }
        }
        return $list;
    }


    public function bdtaskt1m1_055_Insert($table, $data = [])
    {
        if (!empty($table) && !empty($data)) {
            $coa = $this->headcode();
            if ($coa->HeadCode != NULL) {
                $headcode = $coa->HeadCode + 1;
            } else {
                $headcode = "12900000001";
            }

            $query = $this->db->table($table)->insert($data);


            if ($this->db->affectedRows() > 0) {
                $id = $this->db->insertId();
                $c_acc = $id . '-' . $data['name'];
                $createby = session('id');
                $createdate = date('Y-m-d H:i:s');
                $coa = [
                    'HeadCode'         => $headcode,
                    'HeadName'         => $c_acc,
                    'PHeadName'        => 'Dealers',
                    'HeadLevel'        => '3',
                    'IsActive'         => '1',
                    'IsTransaction'    => '1',
                    'IsGL'             => '0',
                    'HeadType'         => 'A',
                    'branch_id'        => session('branchId'),
                    'dealer_id'        => $id,
                    'IsBudget'         => '0',
                    'IsDepreciation'   => '0',
                    'DepreciationRate' => '0',
                    'CreateBy'         => $createby,
                    'CreateDate'       => $createdate,
                ];
                $this->db->table('acc_coa')->insert($coa);
                return $id;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    public function bdtaskt1m1_02_Update($table, $data = [], $id)
    {
        if (!empty($table) && !empty($data)) {
            $coa = array(
                'HeadName' => $id . '-' . $data['name']
            );
            $this->db->table('acc_coa')->where('dealer_id', $id)->update($coa);
            return $this->db->table($table)->where('id', $id)->update($data);
        } else {
            return false;
        }
    }




    public function headcode()
    {
        $query = $this->db->query("SELECT MAX(HeadCode) as HeadCode FROM acc_coa WHERE HeadLevel='3' And HeadCode LIKE '12900%'");
        return $query->getRow();
    }


    public function bdtaskt1m1_05_getexist($id)
    {
        $coa = $this->db->table('acc_coa')
            ->select('*')
            ->where('dealer_id', $id)
            ->get()
            ->getRow();
        $headcode = ($coa ? $coa->HeadCode : '2sfd');
        return  $exist_data  = $this->db->table('acc_transaction')
            ->select('*')
            ->where('COAID', $headcode)
            ->countAllResults();
    }

    public function bdtaskt1m1_07_Deleted($id)
    {
        $this->db->table('acc_coa')->where('dealer_id', $id)->delete();
        return $this->db->table('dealer_info')->where('id', $id)->delete();
    }

    public function max_delarinfo()
    {
        return $afficode = $this->db->table('dealer_info')->select('*')->orderBy('id', 'desc')->get()->getRow();
    }

    public function sales_manList()
    {
        $users = $this->db->table('user')->select("*")
            ->where('status', 1)
            ->get()->getResult();



        $sub = $this->db->table('sub_module')->select("*")
            ->where('directory', 'add_do')
            ->where('status', 1)
            ->get()->getRow();

        $list = array('' => get_phrases(['select', 'Sales Man']));
        if (!empty($users)) {
            foreach ($users as $value) {
                $query = $this->db->table('sec_userrole')->select("roleid")
                    ->where('user_id', $value->emp_id)
                    ->get()->getResult();


                $roles = array();
                foreach ($query as $row) {
                    $roles[] = $row->roleid;
                }

                if (!empty($roles)) {
                    $create = $this->db->table('role_permission')->select("*")
                        ->where('fk_module_id', $sub->id)
                        ->whereIn('role_id', $roles)
                        ->where('create', 1)
                        ->get()->getRow();
                    if ($create) {
                        $list[$value->emp_id] = $value->fullname;
                    }
                }
            }
        }



        return $list;
    }
}
