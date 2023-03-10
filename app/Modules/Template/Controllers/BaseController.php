<?php
namespace App\Modules\Template\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use CodeIgniter\Controller;
use App\Libraries\Template;
use App\Libraries\Fileupload;
class BaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['form', 'url','lang_helper', 'common_helper','security'];
   
	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		$this->session                    = \Config\Services::session();
		$security                         = \Config\Services::security();
		$this->bdtaskt1c1_01_fileUpload   = new Fileupload();
		$this->bdtaskt1c1_02_template     = new Template(); 
		$this->bdtaskt1c1_03_validation   =  \Config\Services::validation();
		if (is_valid_logged()==false){
            header('Location: '.base_url().'/login');
            exit(); 
        }

	}

}
