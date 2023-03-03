<?php
namespace App\Libraries;
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  ======================================= 
 *  Author     : Bdtask
 *  License    : Protected 
 *  Email      : asrafrahmanb@gmail.com 
 * 
 *  ======================================= 
 */
require_once APPPATH . "/ThirdParty/PHPExcel.php";
class Excel extends PHPExcel {
    public function __construct() {
        parent::__construct();
    }
}
?>