<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Templates extends MX_Controller {

	
	public function __Construct(){

		parent::__Construct();

	}



	public function main($data)
	{

		$this->load->view('home',$data);
	}



	


}
