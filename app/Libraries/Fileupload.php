<?php namespace App\Libraries;
if ( ! defined('APPPATH')) exit('No direct script access allowed');
class Fileupload {

	public function __construct()
    {
        
    }

	function doUpload($upload_path = null, $file = null) {
	    if(!empty($file)){
	        if ($file->isValid() && ! $file->hasMoved())
	        {
	           $newName = $file->getRandomName();
	           $file->move((strpos($upload_path, '.') > -1 )?$upload_path:'.'.$upload_path, $newName);
	           return $upload_path.'/'.$newName;
	        }else{
	        	return null;
	        }
	    }else{
	    	return null;
	    }
    }   

    function doUploadResize($upload_path = null, $file = null, $height=null, $width=null) {
	    if(!empty($file)){
	        if ($file->isValid() && ! $file->hasMoved())
	        {
	        	$fSize = $file->getSizeByUnit('mb');
	            $newName = $file->getRandomName();
	            $file->move((strpos($upload_path, '.') > -1 )?$upload_path:'.'.$upload_path, $newName);
	            return $upload_path.'/'.$newName;
	        }else{
	        	return null;
	        }
	    }else{
	    	return null;
	    }
    }   
}
