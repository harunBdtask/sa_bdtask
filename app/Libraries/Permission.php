<?php
namespace App\Libraries;
if ( ! defined('APPPATH')) exit('No direct script access allowed');
class Permission
{    
	protected $permission;
	protected $module; 
	protected $label; 
	protected $redirect = "login";
	protected $session;

	public function __construct()
	{
		$this->module = '';
		$this->label = '';
		$this->session = session();
	}

 
	public function access()
	{ 
		return $this->permission;
	}

	public function redirect()
	{  
		
		if ($this->permission) { 
			return $this->permission;
		} else {
			$this->session->setFlashdata('exception', "You do not have permission to access. Please contact with administrator.");
			  echo  header('Location:'.base_url());
         exit();
		}
	}


	public function module($module = null)
	{
		$module = (($module!=null)?strtolower($module):$this->uri->segment(1));
		$this->module = $module;
		if ($this->checkModule($module)) {
			$this->permission = true;
		} else {
			$this->permission = false;
		} 
		return $this; 
	}



	public function check_label($label = null)
	{
		$label = (($label!=null)?strtolower($label):$this->uri->segment(1));
		$this->label = $label;
		if ($this->check_label_to_Permission($label)) {
			$this->permission = true;
		} else {
			$this->permission = false;
		} 
		return $this; 
	}


	public function method($module = null, $method = null)
	{
     
   
		$module = (($module!=null)?strtolower($module):$this->uri->segment(1));
		$method = strtolower($method);

		if ($this->checkMethod($module, $method) ) {
			if( $method == 'read' || (session('branchId') > 0 && session('branchId') !='') ){
				$this->permission = true;
			} else {
				$this->permission = false;
			}
		} else {
			$this->permission = false;
		} 
		return $this;
	}	


	public function create()
	{   
		if ($this->checkLebel_permission_type($this->label, 'create')) {
			$this->permission = true;
		} else {
			$this->permission = false;
		} 
		return $this;
	}
 

	public function read()
	{   
		if ($this->checkLebel_permission_type($this->label, 'read')) {
			$this->permission = true;
		} else {
			$this->permission = false;
		} 
		return $this;
	}

	public function update()
	{   
		if ($this->checkLebel_permission_type($this->label, 'update')) {
			$this->permission = true;
		} else {
			$this->permission = false;
		} 
		return $this;
	}
 
	public function delete()
	{   
		if ($this->checkLebel_permission_type($this->label, 'delete')) {
			$this->permission = true;
		} else {
			$this->permission = false;
		} 
		return $this;
	}

	public function check_label_method($label = null)
	{
		$uri                        = current_url(true);
        $wihouturl                  =  str_replace(base_url().'/',"",base_url(uri_string()));
        $uri_links                  = (explode("/",$wihouturl));
        $total_segment              = $uri->getTotalSegments();
        $segment_2                  = ($uri_links[1]?$uri_links[1]:'');
        $segment_3                  = ($uri_links[2]?$uri_links[2]:'');
		$label = (($label!=null)?strtolower($label):$segment_2);
		$this->label = $label;
		if ($this->check_label_to_methodPermission($label)) {
			$this->permission = true;
		} else {
			$this->permission = false;
		} 
		return $this; 
	}

	protected function checkModule($module = NULL)
	{ 
		$permission = $this->session->get('permission');

		$isAdmin    = $this->session->get('isAdmin');
		$isLogIn    = $this->session->get('isLogIn');

		if ($isLogIn && $isAdmin) { 
			return true;
		} else if($isLogIn) { 

			if ($permission!=NULL) {

				$permission = json_decode($permission, true);
				//module list
				@$modules = array_keys(@$permission);

				if($modules!=NULL){
					//check current module permission
					if (in_array(@$module, @$modules) ) {
						return true;  
					} else {
						return false;
					} 
				}else{
						return false;
					}
			} else {
				return false;
			} 
		} else {
			return false;
		} 
	}


	protected function check_label_to_Permission($label = null)
		{ 

		$permission = $this->session->get('label_permission');
	
		//print_r(expression)
		$isAdmin    = $this->session->get('isAdmin');
		$isLogIn    = $this->session->get('isLogIn');

		if ($isLogIn && $isAdmin) { 
			return true;
		} else if($isLogIn) { 
			if (($permission!=null)) {
				$permission = json_decode($permission, true);
				//module list
				$labels = array_keys(@$permission);
				//check current module permission
				if ( in_array($label, $labels) ) {
					return true;  
				} else {
					return false;
				} 
			} else {
				return false;
			} 
		} else {
			return false;
		} 
	}

		protected function check_label_to_methodPermission($label = null)
		{ 

		$permission = $this->session->get('permission');
		//print_r(expression)
		$isAdmin    = $this->session->get('isAdmin');
		$isLogIn    = $this->session->get('isLogIn');

		if ($isLogIn && $isAdmin) { 
			return true;
		} else if($isLogIn) { 
			if (($permission!=null)) {
				$permission = json_decode($permission, true);
				//module list
				$labels = array_keys(@$permission);
				//check current module permission
				if ( in_array($label, $labels) ) {
					return true;  
				} else {
					return false;
				} 
			} else {
				return false;
			} 
		} else {
			return false;
		} 
	}


	protected function checkMethod($module = null, $method = null)
	{  
		$permission = $this->session->get('permission');
		$isAdmin    = $this->session->get('isAdmin');
		$isLogIn    = $this->session->get('isLogIn');

		if ($isLogIn && $isAdmin) {
			//action of administrator
			return true;
		} else if($isLogIn) {

			if (($permission!=null)) {
				$permission = json_decode($permission, true);

				//module list
				$modules = array_keys($permission);

				//check current module permission
				if ( in_array($module, $modules) ) {

					//convert method to asoc
					$methodList = $permission[$module]; 

					$methods = array_keys($permission[$module]);

					//check for each input
					if (in_array(strtolower($method), $methods)) {
						if ($methodList[$method] == 1) {
							return true;
						} else {
							return false;
						}	

					} else {
						return false;
					} 

				} else {
					return false;
				} 
			} else {
				return false;
			}

		} else {
			return false;
		} 
	}


	protected function checkLebel_permission_type($label = null, $method = null)
	{ 
		$permission = $this->session->get('label_permission');
		$isAdmin    = $this->session->get('isAdmin');
		$isLogIn    = $this->session->get('isLogIn');

		if ($isLogIn && $isAdmin) {
			//action of administrator
			return true;
		} else if($isLogIn) {

			if (($permission!=null)) {

			$permission = json_decode($permission, true);
				//module list
				$labels = array_keys($permission);

				//check current module permission
				if (in_array($label, $labels) ) {
					//convert method to asoc
					$labelList = $permission[$label]; 

					$methods = array_keys($permission[$label]);

					//check for each input
					if (in_array(strtolower($method), $methods)) {
						if ($labelList[$method] == 1) {
							return true;
						} else {
							return false;
						}	

					} else {
						return false;
					} 

				} else {
					return false;
				} 
			} else {
				return false;
			}

		} else {
			return false;
		} 
	}


}


