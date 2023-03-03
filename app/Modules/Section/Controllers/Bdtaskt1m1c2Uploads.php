<?php namespace App\Modules\Section\Controllers;
use App\Modules\Section\Views;
use CodeIgniter\Controller;
use App\Modules\Section\Models\Bdtaskt1m1Uploads;
use App\Models\Bdtaskt1m1CommonModel;
class Bdtaskt1m1c2Uploads extends BaseController
{
    private $bdtaskt1c1_01_uploadModel;
    private $bdtaskt1c1_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bdtaskt1c1_01_uploadModel = new Bdtaskt1m1Uploads();
        $this->bdtaskt1c1_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['upload', 'account']);
        $data['moduleTitle']= get_phrases(['document', 'management']);
        $data['isDTables']  = true;
        $data['module']     = "Section";
        $data['page']       = "upload";
        return $this->base_02_template->layout($data);
    }

     /*--------------------------
    | save employee info
    *--------------------------*/
    public function bdtaskt1c1_03_fileUpload()
    { 
        $db = db_connect();
        $otherPath = '';
        if(!empty($this->request->getFile('xlxFile'))){
            $photoPath = $this->base_01_fileUpload->doUpload('./assets/dist/data', $this->request->getFile('xlxFile'));
        }
        try {
             $inputFileType = PHPExcel_IOFactory::identify($photoPath);
             $objReader = PHPExcel_IOFactory::createReader($inputFileType);
             $objPHPExcel = $objReader->load($photoPath);
             $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
             $flag = true;
             $i=0;
             foreach ($allDataInSheet as $value) {
               if($flag){
             $flag =false;
             continue;
               }
               $inserdata[$i]['HeadCode'] = $value['A'];
               $inserdata[$i]['HeadName '] = $value['B'];
               $i++;
             }               
             $result = $this->bdtaskt1c1_02_CmModel->bdtaskt1m1_01_Insert_Batch('acc_coa',$inserdata);   
             if($result){
               echo "Imported successfully";
             }else{
               echo "ERROR !";
             }             
             
        } catch (Exception $e) {
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
             . '": ' .$e->getMessage());
        }

    }
}
