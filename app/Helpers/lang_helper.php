<?php if(!defined('APPPATH')) exit('No direct script access allowed');
/*-----------------------------
*  @author   : Bdtask
*  date      : November, 2020
*  Hospital Management System
*  Developer : Ashraf Rahman Babul
*  asrafrahmanb@gmail.com
*/

// Translated the language phrases
 if(!function_exists('get_phrases')) {
     
    function get_phrases($text = [])
    {
         ucwords(implode(" ", $text));
        $language = session('defaultLang') !=''?session('defaultLang'):'english';
        $filePath = APPPATH.'Language/'.$language.'.json';
        $jsonString = openJsonFile($filePath); //Json file to Array
        
        if(!empty($text)){
            if($language=='arabic'){
                $text = array_reverse($text);
            }
            $key = implode('_', $text);
            if (array_key_exists($key, $jsonString)) {
            } else {
                $jsonString[$key] = ucfirst(str_replace('_', ' ', $key));
                $jsonData = json_encode($jsonString, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                file_put_contents($filePath, stripslashes($jsonData));
            }
            // for ($i=0; $i < sizeof($text); $i++) { 
            //     $txt = strtolower(trim($text[$i]));
            //     $dataString .= ($i>0?" ":"");
            //     if (array_key_exists($txt, $jsonString)) {
            //         $dataString .= $jsonString[$txt];
            //     } else {
            //         $jsonString[$txt] = ucfirst(str_replace('_', ' ', $txt));
            //         $jsonData = json_encode($jsonString, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            //         file_put_contents($filePath, stripslashes($jsonData));
            //     }
            // }
        }

        // return $jsonString[$key];
        return ucwords($jsonString[$key]);
    }
 
}

// Translated the phrase from this function. If it does not exist this function will save the phrase and by default it will have the same form as given
if ( ! function_exists('get_notify'))
{
    function get_notify($key = '') {
        $language = session('defaultLang') !=''?session('defaultLang'):'english';
        $filePath = APPPATH.'Language/notify/'.$language.'.json';
        $langArray = openJsonFile($filePath);
        if (array_key_exists($key, $langArray)) {
        } else {
            $langArray[$key] = ucfirst(str_replace('_', ' ', $key));
            $jsonData = json_encode($langArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            file_put_contents($filePath, stripslashes($jsonData));
        }

        return $langArray[$key];
    }
}

// Translated the phrase from this function. If it does not exist this function will save the phrase and by default it will have the same form as given
if ( ! function_exists('api_lang'))
{
    function api_lang($key = '') {
        $language = session('defaultLang') !=''?session('defaultLang'):'english';
        $filePath = APPPATH.'Language/api/'.$language.'.json';
        $langArray = openJsonFile($filePath);
        if (array_key_exists($key, $langArray)) {
        } else {
            $langArray[$key] = ucfirst(str_replace('_', ' ', $key));
            $jsonData = json_encode($langArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            file_put_contents($filePath, stripslashes($jsonData));
        }

        return $langArray[$key];
    }
}

// This function helps us to decode the language json and return that array to us 
if ( ! function_exists('openJsonFile'))
{
    function openJsonFile($filePath)
    {
        $jsonString = [];
        if (file_exists($filePath)) {
            $jsonString = file_get_contents($filePath);
            $jsonString = json_decode($jsonString, true);
        }
        return $jsonString;
    }
}

// This function helps us to create a new json file for new language
if ( ! function_exists('saveDefaultJsonFile'))
{
    function saveDefaultJsonFile($filePath, $defaultPath){
        if(file_exists($filePath)){
            $newLangFile    = $filePath;
            $enLangFile   = $defaultPath;
            copy($enLangFile, $newLangFile);
        }else {
            $fp = fopen($filePath, 'w');
            $newLangFile = $filePath;
            $enLangFile   = $defaultPath;
            copy($enLangFile, $newLangFile);
            fclose($fp);
        }
    }
}

// This function helps us to update a notify inside the language file.
if ( ! function_exists('saveJsonFile'))
{
    function saveJsonFile($filePath, $updating_key, $updating_value){
        $jsonString = [];
        if(file_exists($filePath)){
            $jsonString = file_get_contents($filePath);
            $jsonString = json_decode($jsonString, true);
            $jsonString[$updating_key] = $updating_value;
        }else {
            $jsonString[$updating_key] = $updating_value;
        }
        $jsonData = json_encode($jsonString, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        file_put_contents($filePath, stripslashes($jsonData));
    }
}

// Store and Get api access token
if ( ! function_exists('api_access_token'))
{
    // $array = array('name' => $name,'id' => $id,'url' => $url);
    // $fp = fopen('results.json', 'w');
    // fwrite($fp, json_encode($array, JSON_PRETTY_PRINT));   // here it will print the array pretty
    // fclose($fp);
    function api_access_token($key = '') {
        $filePath = APPPATH.'Language/api/access_toekn.json';
        $langArray = openJsonFile($filePath);
        if (array_key_exists($key, $langArray)) {
        } else {
            $langArray[$key] = ucfirst(str_replace('_', ' ', $key));
            $jsonData = json_encode($langArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            file_put_contents($filePath, stripslashes($jsonData));
        }

        return $langArray[$key];
    }
}