<?php namespace App\Modules\Export\Controllers;
use App\Modules\Export\Views;
use CodeIgniter\Controller;
use App\Modules\Export\Models\Bdtaskt1m2BackupModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;

class Bdtaskt1c2Backup extends BaseController
{
    private $bdtaskt1c2_01_backupModel;
    private $bdtaskt1c2_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permission = new Permission();
        $this->bdtaskt1c2_01_backupModel = new Bdtaskt1m2BackupModel();
        $this->bdtaskt1c2_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['full', 'backup']);
        $data['moduleTitle']= get_phrases(['export']);
        $data['isDTables']  = true;
        $data['module']     = "Export";
        $data['page']       = "backup/list";

        $data['hasCreateAccess']        = $this->permission->method('full_backup', 'create')->access();
        
        //$data['branchs']       = $this->bdtaskt1c2_02_CmModel->bdtaskt1m1_05_getResultWhere('branch', array('status'=>1));
        //var_dump($data['branchs']);exit;
        return $this->base_02_template->layout($data);
    }

    public function create_backup(){
        $world_dumper = Shuttle_Dumper::create(array(
            'host' => '192.168.1.124',
            'username' => 'raihan',
            'password' => '12345',
            'db_name' => 'sa_agro',
        ));

        // dump the database to gzipped file
        $world_dumper->dump('sa_agro.sql.gz');

        // dump the database to plain text file
        $world_dumper->dump('sa_agro.sql');
    }

    public function sql_backup(){
        // $file_name = $this->bdtaskt1c2_01_backupModel->sql_backup();
        // //echo $file_name;
       

         $db_name = date('Y-m-d-H-i').'sa_agro' . '.sql';
        $this->dbutil =  (new \CodeIgniter\Database\Database())->loadUtils($this->db);
        $prefs = array(
            'format'   => 'sql',
            'filename' => $db_name . '.sql'
        );
        $b         = $this->dbutil->backup($prefs);
        $save      = 'assets/data/backup/' . $db_name;
        helper(['filesystem', 'form', 'url', 'database', 'text', 'download']);
        $username  = $this->db->username;
        $backup    =  $b;

        write_file($save, $backup);

        $data['file_name']  = $db_name;
        $data['created_by'] = session('id');
        $data['created_dt'] = date('Y-m-d H:i:s');
        $result = $this->bdtaskt1c2_02_CmModel->bdtaskt1m1_01_Insert('backup',$data);
        return redirect()->back();
    }

    /*--------------------------
    | Get branch info
    *--------------------------*/
    public function bdtaskt1c2_01_getList()
    { 
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1c2_01_backupModel->bdtaskt1m2_02_getAll($postData);
        echo json_encode($data);
    }

    /*--------------------------
    | delete branch by ID
    *--------------------------*/
    public function bdtaskt1c2_02_deleteBackup($id)
    { 
        $location = base_url('assets/data/backup').'/';
        $backup = $this->bdtaskt1c2_02_CmModel->bdtaskt1m1_03_getRow('backup', array('id'=>$id));
          $path = $location.$backup->file_name;
         // @unlink($path);
        if(file_exists($location.$backup->file_name)){
         
            unlink($location.$backup->file_name);
        }
        $data = $this->bdtaskt1c2_02_CmModel->bdtaskt1m1_06_Deleted('backup', array('id'=>$id));

        $MesTitle = get_phrases(['backup', 'record']);
        if(!empty($data)){
            // Store log data
            $this->bdtaskt1c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['backup']), get_phrases(['deleted']), $id, 'backup', 3);
            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['deleted', 'successfully']),
                'title'    => $MesTitle
            );
        }else{
            $response = array(
                'success'  =>false,
                'message'  => (ENVIRONMENT == 'production')?get_phrases(['something', 'went', 'wrong']):get_db_error(),
                'title'    => $MesTitle
            );
        }
        echo json_encode($response);
    }

    /*--------------------------
    | Add branch info
    *--------------------------*/
    public function bdtaskt1c2_03_addBranch()
    { 

        $old_image = $this->request->getVar('old_image'); 
        $action = $this->request->getVar('action');
        $data = array(
            'nameE'        => $this->request->getVar('branch_name'), 
            'nameA'        => $this->request->getVar('branch_nameA'), 
            //'parent_id'   => $this->request->getVar('parent_id'), 
            //'flaticon'    => $this->request->getVar('flaticon'), 
            //'description' => $this->request->getVar('description'), 
        );

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'branch_name'      => 'required|min_length[6]|max_length[150]',
                'branch_nameA'      => 'required|min_length[6]|max_length[150]',
            ];
        }
        $MesTitle = get_phrases(['branch', 'record']);
        if (! $this->validate($rules)) {
            $response = array(
                'success'  =>false,
                'message'  => $this->validator->listErrors(),
                'title'    => $MesTitle
            );
        }else{
            $filePath = '';//$this->bdtaskt1c1_01_fileUpload->doUpload('./assets/dist/img/branch', $this->request->getFile('image'));
            if($action=='add'){
                $isExist = $this->bdtaskt1c2_02_CmModel->bdtaskt1m1_03_getRow('branch', array('nameE'=>$this->request->getVar('branch_name')));
                $isExist2 = $this->bdtaskt1c2_02_CmModel->bdtaskt1m1_03_getRow('branch', array('nameA'=>$this->request->getVar('branch_nameA')));
                if(!empty($isExist) || !empty($isExist2)){
                    $response = array(
                            'success'  =>'exist',
                            'message'  => get_phrases(['name', 'already', 'exists']),
                            'title'    => $MesTitle
                        );
                }else{
                    $data['image'] = $filePath;
                    $result = $this->bdtaskt1c2_02_CmModel->bdtaskt1m1_01_Insert('branch',$data);
                    if($result){
                        // Store log data
                        $this->bdtaskt1c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['branch']), get_phrases(['created']), $result, 'branch', 1);
                        $response = array(
                            'success'  =>true,
                            'message'  => get_phrases(['added', 'successfully']),
                            'title'    => $MesTitle
                        );
                    }else{
                        $response = array(
                            'success'  =>false,
                            'message'  => get_phrases(['something', 'want', 'wrong']),
                            'title'    => $MesTitle
                        );
                    }
                }
            }else{
                $id = $this->request->getVar('id');
                $data['image'] = !empty($filePath)?$filePath:$old_image;
                $result = $this->bdtaskt1c2_02_CmModel->bdtaskt1m1_02_Update('branch',$data, array('id'=>$id));
                if($result){
                    // Store log data
                    $this->bdtaskt1c2_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['branch']), get_phrases(['updated']), $id, 'branch', 2);
                    $response = array(
                        'success'  =>true,
                        'message'  => get_phrases(['updated', 'successfully']),
                        'title'    => $MesTitle
                    );
                }else{
                    $response = array(
                        'success'  =>false,
                        'message'  => get_phrases(['something', 'want', 'wrong']),
                        'title'    => $MesTitle
                    );
                }
            }
            
        }
        
        echo json_encode($response);
    }

    /*--------------------------
    | Get branch by ID
    *--------------------------*/
    public function bdtaskt1c2_04_getDepartById($id)
    { 
        $data = $this->bdtaskt1c2_02_CmModel->bdtaskt1m1_03_getRow('branch', array('id'=>$id));
        echo json_encode($data);
    }

   
}
