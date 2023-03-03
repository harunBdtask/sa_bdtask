<?php namespace App\Modules\Dashboard\Controllers;
use CodeIgniter\Controller;
use App\Models\Bdtaskt1m1CommonModel;
class Bdtaskt1m1c3ActivitiesLog extends BaseController
{
    private $Bdtaskt1m1c3_01_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->Bdtaskt1m1c3_01_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | is user qctive
    *--------------------------*/
    public function Bdtaskt1m1c3_01_isUserActive()
    { 
        $activities = $this->session->getTempdata('isUserActivity');
        $isAdmin = session('isAdmin');
        // $isLogin    = $this->session->get('isLogIn');
        // if($isLogin == true && $activities==false){
        //     $val = false;
        // }else if($isLogin == true && $activities==true){
        //     $val = true;
        // }else{
        //     $val = 3;
        // }
        if($isAdmin==false){
            if($activities){
                $val = 1;
            }else{
                $val = 0;
            }
        }else{
            $val = 1;
        }
        echo json_encode(array('success'=>$val, 'data'=>$activities));
    }

    /*--------------------------
    | Check user activities info
    *--------------------------*/
    public function Bdtaskt1m1c3_02_checkActivities()
    { 
        $activities = $this->session->getTempdata('isUserActivity');
        $isAdmin = session('isAdmin');
        // $isLogin    = $this->session->get('isLogIn');
        // if($isLogin == true && $activities==false){
        //     $val = false;
        // }else if($isLogin == true && $activities==true){
        //     $this->session->setTempdata('isUserActivity', true, 900);//expire 15 minutes
        //     $val = true;
        // }else{
        //     $val = 3;
        // }
        if($isAdmin==false){
            if($activities){
                $this->session->setTempdata('isUserActivity', true, 300);//expire 5 minutes
                $val = 1;
            }else{
                $val = 0;
            }
        }else{
            $val = 1;
        }
        echo json_encode(array('success'=>$val));
    }

    /*--------------------------
    | Log access validate
    *--------------------------*/
    public function Bdtaskt1m1c3_03_accessValidation()
    { 
        $username  = session('username');
        $password   = md5($this->request->getVar('password'));
        $isValid = $this->Bdtaskt1m1c3_01_CmModel->bdtaskt1m1_03_getRow('user', array('username'=>$username, 'password'=>$password));
        $MesTitle = get_phrases(['user', 'record']);
        if($isValid){
            $this->session->setTempdata('isUserActivity', true, 900);//expire 15 minutes
            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['login', 'successfully']),
                'title'    => $MesTitle
            );
        }else{
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['wrong', 'password']),
                'title'    => $MesTitle
            );
        }
        echo json_encode($response);
    }

}
