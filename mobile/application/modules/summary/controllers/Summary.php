<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Summary extends MX_Controller {

	
	public function __Construct(){

		parent::__Construct();

	}



	public function index()
	{

		$data['module']="summary";
		$data['view']="widgets";

		echo Modules::run('templates/main',$data);
	}
	
	



	


}
