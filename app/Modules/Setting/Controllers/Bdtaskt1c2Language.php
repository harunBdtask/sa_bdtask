<?php namespace App\Modules\Setting\Controllers;

use CodeIgniter\Controller;
use App\Modules\Setting\Models\Bdtaskt1m2Language;
use App\Modules\Setting\Models\Bdtaskt1m3Phrase;
class Bdtaskt1c2Language extends BaseController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bdtaskt1c2_01_langModel   = new Bdtaskt1m2Language();
        $this->bdtaskt1c2_02_phraseModel = new Bdtaskt1m3Phrase();
    }

    /*--------------------------
    | Language list
    *--------------------------*/
    public function index()
    {
        $dir = APPPATH.'Language';
        $files = scandir($dir);
        $language_files = array();
        foreach ($files as $file) {
            $info = pathinfo($file);
            if( isset($info['extension']) && strtolower($info['extension']) == 'json') {
                $file_name = explode('.json', $info['basename']);
                array_push($language_files, $file_name[0]);
            }
        }
        
        $data['title']     = get_phrases(['language', 'list']);
        $data['moduleTitle']= get_phrases(['settings']);
        $data['module']    = "Setting";
        $data['page']      = "language/main";
        $data['languages'] = $language_files; //$this->bdtaskt1c2_01_langModel->bdtaskt1m2_01_Languages();
        return $this->base_02_template->layout($data);
    }

    /*--------------------------
    | Language phrases edit form
    *--------------------------*/
    public function bdtaskt1c2_03_editPhrase($language = null)
    { 
        $filePath = APPPATH.'Language/'.$language.'.json';
        //$languages  = openJsonFile($filePath);
        //echo "<pre>";
        //print_r($languages);exit();
        $data['title']     = get_phrases(['edit', 'phrase']);
        $data['moduleTitle']  = get_phrases(['settings']);
        $data['isDTables']  = true;
        $data['module']    = "Setting";
        $data['page']      = "language/phrase_edit";
        $data['language']  = $language;
        $data['filePath']  = $filePath;
        $data['phrases']   = openJsonFile($filePath);
        return $this->base_02_template->layout($data);

    }


    /*--------------------------
    | Save Json data from language table
    *--------------------------*/
    public function bdtaskt1c2_09_langData($code){ 
        $data = $this->bdtaskt1c2_01_langModel->bdtaskt1m2_04_getLangData($code);
        $jsonArray = [];
        foreach ($data as $value) {
            $jsonArray[$value->phrase] = $value->$code;
        }
        $jsonData = json_encode($jsonArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $filePath = APPPATH.'Language/'.$code.'.json';
        if(file_exists($filePath)){
            file_put_contents($filePath, stripslashes($jsonData));
        }
    }

    /*--------------------------
    | Add language phrases
    *--------------------------*/
    public function bdtaskt1c2_10_updatePhrase() {  
        //print_r($this->request->getVar());exit();
        $phrase = $this->request->getVar('key'); 
        $updatedValue = $this->request->getVar('updatedValue'); 
        $language = $this->request->getVar('language'); 
        $filePath = APPPATH.'Language/'.$language.'.json';
        //print_r($filePath);exit();
        if(file_exists($filePath)){
            saveJsonFile($filePath, $phrase, $updatedValue);
            $response = array(
                'success'  => true,
                'message'  => get_phrases(['updated', 'successfully']),
                'title'    => get_phrases(['language', 'record'])
            );
        }else{
            $response = array(
                'success'  => false,
                'message'  => get_phrases(['path', 'invalid']).'!',
                'title'    => get_phrases(['language', 'record'])
            );
        }
        echo json_encode($response);
    }

    /*--------------------------
    | Language phrases edit form
    *--------------------------*/
    public function bdtaskt1c2_06_editNotifyMessage($label, $language)
    { 
        $filePath = APPPATH.'Language/'.$label.'/'.$language.'.json';
        $data['title']     = get_phrases(['edit', 'message', 'phrase']);
        $data['moduleTitle']  = get_phrases(['settings']);
        $data['isDTables']  = true;
        $data['module']    = "Setting";
        $data['page']      = "language/message_phrase_edit";
        $data['language']  = $language;
        $data['filePath']  = $filePath;
        $data['label']     = $label;
        $data['phrases']   = openJsonFile($filePath);
        return $this->base_02_template->layout($data);
    }

     /*--------------------------
    | Add language phrases
    *--------------------------*/
    public function bdtaskt1c2_07_updateMsgPhrase() {  
        $label = $this->request->getVar('label'); 
        $phrase = $this->request->getVar('key'); 
        $updatedValue = $this->request->getVar('updatedValue'); 
        $language = $this->request->getVar('language'); 
        $filePath = APPPATH.'Language/'.$label.'/'.$language.'.json';
        //print_r($filePath);exit();
        if(file_exists($filePath)){
            saveJsonFile($filePath, $phrase, $updatedValue);
            $response = array(
                'success'  => true,
                'message'  => get_phrases(['updated', 'successfully']),
                'title'    => get_phrases(['language', 'record'])
            );
        }else{
            $response = array(
                'success'  => false,
                'message'  => get_phrases(['path', 'invalid']).'!',
                'title'    => get_phrases(['language', 'record'])
            );
        }
        echo json_encode($response);
    }

   
}



 