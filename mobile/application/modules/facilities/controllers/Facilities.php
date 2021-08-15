<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities extends MX_Controller {

	
	public function __Construct(){

		parent::__Construct();

		$this->load->model('facilities_mdl');

	}



	

	public function getFacilities($district_id)
	{
		$facilities=$this->facilities_mdl->getFacilities($district_id);


		return $facilities;

		//print_r($facilities);


	}
	
	
		public function getAttFacilities($district_id)
	{
		$facilities=$this->facilities_mdl->getAttFacilities($district_id);


		return $facilities;

		//print_r($facilities);


	}


	public function getTypes($tb)
	{
		

		$types=$this->facilities_mdl->getTypes($tb);


		return $types;

		//print_r($types);


	}



	


}
