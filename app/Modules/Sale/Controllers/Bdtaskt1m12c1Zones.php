<?php

namespace App\Modules\Sale\Controllers;

use App\Modules\Sale\Views;
use CodeIgniter\Controller;
use App\Modules\Sale\Models\Bdtaskt1m12ZonessModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1m12c1Zones extends BaseController
{
    private $bdtaskt1m12c1_01_zonesModel;
    private $bdtaskt1m12c1_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission                  = new Permission();
        $this->bdtaskt1m12c1_01_zonesModel = new Bdtaskt1m12ZonessModel();
        $this->bdtaskt1m12c1_02_CmModel    = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | zone list
    *--------------------------*/
    public function index()
    {
        $data['title']            = get_phrases(['region', 'list']);
        $data['moduleTitle']      = get_phrases(['sale', 'region']);
        $data['isDTables']        = true;
        $data['module']           = "Sale";
        $data['page']             = "zone/list";
        $data['hasCreateAccess']  = $this->permission->method('zone_list', 'create')->access();
        $data['hasPrintAccess']   = $this->permission->method('zone_list', 'print')->access();
        $data['hasExportAccess']  = $this->permission->method('zone_list', 'export')->access();

        return $this->bdtaskt1c1_02_template->layout($data);
    }

    /*--------------------------
    | Get zone info
    *--------------------------*/
    public function bdtaskt1m12c1_01_getList()
    {
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m12c1_01_zonesModel->bdtaskt1m12_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete Zones by ID
    *--------------------------*/
    public function bdtaskt1m12c1_02_deleteZones($id)
    {

        $MesTitle = get_phrases(['region', 'record']);

        $data = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_06_Deleted('zone_tbl', array('id' => $id));
        // Store log data
        $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['region']), get_phrases(['deleted']), $id, 'zone_tbl');

        $MesTitle = get_phrases(['region', 'record']);
        if (!empty($data)) {
            $response = array(
                'success'  => true,
                'message'  => get_phrases(['deleted', 'successfully']),
                'title'    => $MesTitle
            );
        } else {
            $response = array(
                'success'  => false,
                'message'  => (ENVIRONMENT == 'production') ? get_phrases(['something', 'went', 'wrong']) : get_db_error(),
                'title'    => $MesTitle
            );
        }
        echo json_encode($response);
    }

    /*--------------------------
    | Add Zones info
    *--------------------------*/
    public function bdtaskt1m12c1_03_addZones()
    {

        $action = $this->request->getVar('action');
        $zone_name = $this->request->getVar('zone_name');
        $districts = $this->request->getVar('districts');
        $division = $this->request->getVar('division');
        $data = array(
            'zone_name'        => $zone_name,
            'districts'        => $districts,
            'division'         => $division,
            'zone_map'         => $this->request->getVar('zone_map'),
            'zone_officer_id'  => $this->request->getVar('zone_officer_id'),
            'status'           => $this->request->getVar('status'),
            'created_by'       => session('id'),
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'zone_name'      => 'required',
                'districts'      => 'required',
                'status'         => 'required',
            ];
        }
        $MesTitle = get_phrases(['region', 'record']);
        if (!$this->validate($rules)) {
            $response = array(
                'success'  => false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        } else {
            if ($action == 'add') {
                // print_r('ddd');exit;
                $isExist = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('zone_tbl', array('zone_name'=>$zone_name, 'districts'=>$districts, 'division'=>$division));
                if (!empty($isExist)) {
                    $response = array(
                        'success'  => 'exist',
                        'message'  => get_phrases(['already', 'exists']),
                        'title'    => $MesTitle
                    );
                } else {
                    $data['created_by']          = session('id');
                    $data['created_date']        = date('Y-m-d H:i:s');


                    $result = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_01_Insert('zone_tbl', $data);
                    // Store log data
                    $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['region']), get_phrases(['created']), $result, 'zone_tbl');
                    if ($result) {
                        $response = array(
                            'success'  => true,
                            'message'  => get_phrases(['added', 'successfully']),
                            'title'    => $MesTitle
                        );
                    } else {
                        $response = array(
                            'success'  => false,
                            'message'  => (ENVIRONMENT == 'production') ? get_phrases(['something', 'went', 'wrong']) : get_db_error(),
                            'title'    => $MesTitle
                        );
                    }
                }
            } else {
                $id = $this->request->getVar('id');
                $data['updated_by']          = session('id');
                $data['updated_date']        = date('Y-m-d H:i:s');

                $result = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_02_Update('zone_tbl', $data, array('id' => $id));
                // Store log data
                $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['region']), get_phrases(['updated']), $id, 'zone_tbl');
                if ($result) {
                    $response = array(
                        'success'  => true,
                        'message'  => get_phrases(['updated', 'successfully']),
                        'title'    => $MesTitle
                    );
                } else {
                    $response = array(
                        'success'  => false,
                        'message'  => (ENVIRONMENT == 'production') ? get_phrases(['something', 'went', 'wrong']) : get_db_error(),
                        'title'    => $MesTitle
                    );
                }
            }
        }

        echo json_encode($response);
    }

    /*--------------------------
    | Get Zones by ID
    *--------------------------*/
    public function bdtaskt1m12c1_04_getZonesById($id)
    {
        $data = $this->bdtaskt1m12c1_02_CmModel->bdtaskt1m1_03_getRow('zone_tbl', array('id' => $id));
        echo json_encode($data);
    }

    /*--------------------------
    | Get Zone details by ID
    *--------------------------*/
    public function bdtaskt1m12c1_05_getZoneDetailsById($id)
    {
        $data = $this->bdtaskt1m12c1_01_zonesModel->bdtaskt1m12_03_getZoneDetailsById($id);
        echo json_encode($data);
    }
}
