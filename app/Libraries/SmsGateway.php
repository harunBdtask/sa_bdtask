<?php namespace App\Libraries;
if ( ! defined('APPPATH')) exit('No direct script access allowed');
    class SmsGateway
    {
        public function send($config = [])
        {            
            //$config['to'] = $this->validMobile($config['to']);
            //if (sizeof($config) < 6) exit('Sms Gateway configuration missing'); 
            // echo "<pre>";
            // print_r($config);exit();
            switch (strtolower($config['apiProvider'])) {

                case 'nexmo':
                        return $this->nexmo($config);
                    break; 
                 case 'clickatell':
                        return $this->send_clickatell_message($config);
                    break;
                case 'bdtask':
                        return $this->bdtask($config);
                    break;
                case 'hisms':
                        return $this->hisms($config);
                    break;
                                       
                default:
                        return json_encode(['exception' => 'No api found']);
                    break;
            }
        } 

 
        #--------------------------------------------   
        # For nexmo provider
        public function nexmo($config = [])
        {                       
            $url = "https://rest.nexmo.com/sms/json?api_key=".urlencode($config['username'])."&api_secret=".urlencode($config['password'])."&to=".urlencode($config['to'])."&from=".urlencode($config['from'])."&text=".urlencode($config['message'])."";                       
            try {
                return @file_get_contents($url);
            }
            catch (Exception $e) {
                echo "Nexmo error : ".$e->getMessage();
            }

        }


        #--------------------------------------------       
        public function send_clickatell_message($config = [])
        {
            echo file_get_contents("https://api.clickatell.com/http/sendmsg"
 . "?user=".urlencode($config['username'])."&password=".urlencode($config['password'])."&api_id=".urlencode($config['from'])."&to=".urlencode($config['to'])."&text=".urlencode($config['message'])."");  
        }

        #--------------------------------------------   
        # For bdtask provider
        public function bdtask($config = [])
        {                       
            $url = "http://sms.bdtask.com/smsapi?api_key=".urlencode($config['username'])."&type=text&contacts=".urlencode($config['to'])."&senderid=".urlencode($config['from'])."&msg=".urlencode($config['message'])."";                       
            try {
                return @file_get_contents($url);
            }
            catch (Exception $e) {
                echo "BDTask error : ".$e->getMessage();
            }

        }

        #--------------------------------------------   
        # For hisms provider
        public function hisms($config = [])
        {                       
            $url = "https://www.hisms.ws/api.php?send_sms&username=".$config['username']."&password=".$config['password']."&numbers=".$config['numbers']."&sender=".$config['sender_name']."&message=".$config['message']."&date=".$config['date']."&time=".$config['time']."";
            return $url;
        }

        private function _do_api_call($url)
        {
            $result = file($url);      
            return $result;
        }

        #---------------------------------------
        private $operator = array('11','12','13','14','15','16','17','18','19'); 

        public function validMobile($mobile = null)
        {    
           $mobile = trim($mobile); 
            if ($this->checkValidMobileOperator($mobile) != false) { 
                $countryCode = substr($mobile, 0, 2);
                if (in_array($countryCode, $this->operator)) {
                    $newMobileNo = substr_replace($mobile,"880",0,0);
                } elseif ($countryCode == "01") {
                    $newMobileNo = substr_replace($mobile,"88",0,0);
                } elseif ($countryCode == "80") {
                    $newMobileNo = substr_replace($mobile,"8",0,0);
                } elseif ($countryCode == "+8") {
                    $newMobileNo = substr_replace($mobile,"",0,1);
                } else {
                    $newMobileNo = $mobile;
                } 
                return $newMobileNo; 
            }
        }


        protected function checkValidMobileOperator($mobile = null)
        {
            if(10 <= strlen($mobile) && strlen($mobile) <= 15){

                if(strlen($mobile) == 10){ /*for 10 digits*/
                    return in_array(substr($mobile,0,2), $this->operator);
                } elseif (strlen($mobile) == 11) { /*for 11 digits*/
                    return in_array(substr($mobile,1,2), $this->operator);
                } elseif (strlen($mobile) == 12) { /*for 12 digits*/ 
                    return in_array(substr($mobile,2,2), $this->operator);
                } elseif(strlen($mobile) == 13){ /*for 13 digits*/  
                    return in_array(substr($mobile,3,2), $this->operator);
                } elseif(strlen($mobile) == 14){ /*for 14 digits*/ 
                    return in_array(substr($mobile,4,2), $this->operator);
                } elseif(strlen($mobile) == 15){ /*for 15 digits*/
                    return in_array(substr($mobile,5,2), $this->operator);
                }

            } else {
                return false;
            }
        } 


        public function template($config = null)
        {
            $newStr = $config['message'];
            foreach ($config as $key => $value) {
                $newStr = str_replace("%$key%", $value, $newStr);
            }  
            return $newStr; 
        }

    } 
