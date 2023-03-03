<?php

namespace App\Modules\Lc\Models;

use CodeIgniter\Model;
use App\Libraries\Permission;

class Lcmodel extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->permission = new Permission();

        $this->hasCreateAccess = $this->permission->method('wh_lc', 'create')->access();
        $this->hasReadAccess   = $this->permission->method('wh_lc', 'read')->access();
        $this->hasUpdateAccess = $this->permission->method('wh_lc', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('wh_lc', 'delete')->access();
    }



    public function bdtaskt1m12c1_01_getList($postData = null)
    {


        $response = array();
        ## Read value
        @$draw = $postData['draw'];
        @$start = $postData['start'];
        @$rowperpage = $postData['length']; // Rows display per page
        @$columnIndex = $postData['order'][0]['column']; // Column index
        @$columnName = $postData['columns'][$columnIndex]['data']; // Column name
        @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        @$searchValue = $postData['search']['value']; // Search value
        ## Search 
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (b.HeadName like '%" . $searchValue . "%' OR lc.lc_open_date like '%" . $searchValue . "%' OR lc.lc_number like '%" . $searchValue . "%' OR lc.lc_amount like '%" . $searchValue . "%') ";
        }
        ## Fetch records
        $builder3 = $this->db->table('ah_lc lc');
        $builder3->select("lc.*, b.HeadName as bank_name");
        $builder3->join('acc_coa b', 'b.HeadCode=lc.lc_bank_id');
        // $builder3->join('ah_bank', 'ah_bank.bank_id=lc.lc_bank_id');
        if ($searchValue != '') {
            $builder3->where($searchQuery);
        }

        ## Total number of records with filtering
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        // $builder3->orderBy('row_id', 'desc');
        $builder3->limit($rowperpage, $start);
        $query3 =  $builder3->get();
        $records =   $query3->getResultArray();


        ## Total number of records without filtering
        $totalRecords = $builder3->countAll();
        if ($searchQuery == '') {
            $totalRecords = $totalRecordwithFilter;
        }
        $data   = array();
        $view   = get_phrases(['view']);
        $edit   = get_phrases(['update']);
        $delete = get_phrases(['delete']);

        $sl = 1;

        foreach ($records as $record) {
            $button = '';
            $button .= (!$this->hasReadAccess) ? '' : '<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-1 custool" title="' . $view . '" data-id="' . $record['row_id'] . '"><i class="fas fa-eye"></i></a>';

            if ($record['isApproved'] != 1) {
                $button .= (!$this->hasUpdateAccess) ? '' : '<a href="javascript:void(0);" class="btn btn-success-soft btnC actionApprove mr-2 custool" title="Approve" data-id="'.$record['row_id'].'" data-purchase="'.$record['purchase_id'].'"><i class="fa fa-check"></i></a>';
                $button .= (!$this->hasUpdateAccess) ? '' : '<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-1 custool" title="' . $edit . '" data-id="' . $record['row_id'] . '"><i class="far fa-edit"></i></a>';
                $button .= (!$this->hasDeleteAccess) ? '' : '<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" title="' . $delete . '" data-id="' . $record['row_id'] . '" data-head="' . $record['row_id'] . '"><i class="far fa-trash-alt"></i></a>';
            }
            
            $data[] = array(
                'row_id'        => $sl,
                'lc_number'     => $record['lc_number'],
                'lc_open_date'  => $record['lc_open_date'],
                'lc_bank_id'    => $record['bank_name'],
                'lc_margin'     => $record['lc_margin'],
                'lc_amount'     => $record['lc_amount'],
                'lc_open_date'  => $record['lc_open_date'],
                'button'        => $button
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


    public function get_lc_by_id($id)
    {

        $builder3 = $this->db->table('ah_lc lc');
        $builder3->select("lc.*,  b.HeadName as bank_name");
        $builder3->join('acc_coa b', 'b.HeadCode=lc.lc_bank_id');
        // $builder3->join('ah_bank', 'ah_bank.bank_id=lc.lc_bank_id');
        $builder3->where('row_id', $id);
        $query3 =  $builder3->get();
        $records =   $query3->getRow();
        return $records;
    }

    public function get_lc_attach_by_id($id)
    {

        $builder3 = $this->db->table('ah_lc_attachment');
        $builder3->select("*");
        $builder3->where('lc_id', $id);
        $query3 =  $builder3->get();
        $records =   $query3->getResult();
        return $records;
    }

    public function get_lc_po_details($id)
    {

        $builder3 = $this->db->table('wh_material_purchase_details a');
        $builder3->select("a.*, b.nameE, b.item_code");
        $builder3->join('wh_material b', 'b.id=a.item_id');
        $builder3->where('a.lc_id', $id);
        $query3 =  $builder3->get();
        $records =   $query3->getResult();
        return $records;
    }

    public function get_lc_po_info($id)
    {

        $builder3 = $this->db->table('wh_material_purchase a');
        $builder3->select("a.*, b.nameE, b.code_no");
        $builder3->join('wh_supplier_information b', 'b.id=a.supplier_id');
        $builder3->where('a.lc_id', $id);
        $query3 =  $builder3->get();
        $records =   $query3->getRow();
        return $records;
    }
}
