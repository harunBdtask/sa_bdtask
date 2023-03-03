<?php

namespace App\Modules\Sale\Models;

use CodeIgniter\Model;
use App\Libraries\Permission;

class Bdtaskt1m12ZonessModel extends Model
{

    public function __construct()
    {
        $this->db              = db_connect();
        $this->permission      = new Permission();
        $this->hasReadAccess   = $this->permission->method('zone_list', 'read')->access();
        $this->hasCreateAccess = $this->permission->method('zone_list', 'create')->access();
        $this->hasUpdateAccess = $this->permission->method('zone_list', 'update')->access();
        $this->hasDeleteAccess = $this->permission->method('zone_list', 'delete')->access();
    }

    public function bdtaskt1m12_02_getAll($postData = null)
    {
        $response = array();
        ## Read value

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
            $searchQuery = " (zone_name like '%" . $searchValue . "%' OR districts like '%" . $searchValue . "%' ) ";
        }

        ## Fetch records
        $builder3 = $this->db->table('zone_tbl');
        $builder3->select("*");
        if ($searchValue != '') {
            $builder3->where($searchQuery);
        }
        ## Total number of records with filtering
        $totalRecordwithFilter = $builder3->countAllResults(false);
        $builder3->orderBy($columnName, $columnSortOrder);
        $builder3->limit($rowperpage, $start);
        $query3   =  $builder3->get();
        $records  =   $query3->getResultArray();
        ## Total number of records without filtering
        $totalRecords = $builder3->countAll();
        if ($searchQuery == '') {
            $totalRecords = $totalRecordwithFilter;
        }


        $data = array();

        foreach ($records as $record) {
            $button = '';

            $button .= (!$this->hasReadAccess) ? '' : '<a href="javascript:void(0);" class="btn btn-info-soft btnC actionPreview mr-2 custool" title="Details" data-id="' . $record['id'] . '"><i class="far fa-eye"></i></a>';
            $button .= (!$this->hasUpdateAccess) ? '' : '<a href="javascript:void(0);" class="btn btn-primary-soft btnC actionEdit mr-2 custool" data-id="' . $record['id'] . '" title="Edit"><i class="far fa-edit"></i></a>';
            $button .= (!$this->hasDeleteAccess) ? '' : '<a href="javascript:void(0);" class="btn btn-danger-soft btnC actionDelete custool" data-id="' . $record['id'] . '" title="Delete"><i class="far fa-trash-alt"></i></a>';
            $data[] = array(
                'id'           => $record['id'],
                'zone_name'    => $record['zone_name'],
                'districts'    => $record['districts'],
                'division'     => $record['division'],
                'zone_map'     => $record['zone_map'],
                'status'       => ($record['status'] == 1 ? 'Active' : 'Inactive'),
                'button'       => $button
            );
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

    public function bdtaskt1m12_03_getZoneDetailsById($id)
    {
        $data = $this->db->table('zone_tbl')->select('*')
            ->where(array('id' => $id))
            ->get()
            ->getRowArray();

        return $data;
    }
}
