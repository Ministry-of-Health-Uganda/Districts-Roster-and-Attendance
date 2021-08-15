<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Districts extends MX_Controller {

	
	public function __Construct(){

		parent::__Construct();

		$this->load->model('districts_mdl');

	}



	public function getDistricts()

	{

		$districts=$this->districts_mdl->getDistricts();

		return $districts;

		//print_r($districts);

		
 
	}

		public function getDistrict($id)
	{

		$district=$this->districts_mdl->getDistrict($id);

		return $district;

		//print_r($district);
 
	}
	
		public function getFacility($id)
	{

		$facility=$this->districts_mdl->getFacility($id);

		return $facility;
 
	}

	public function getFacilities($did)
	{
        $did = urldecode($did);
		$facilities=$this->districts_mdl->getFacilities($did);

		$faci_options.="<option disabled selected>--Select Facility--</option>";

		foreach ($facilities as $facility):

			$faci_options.='<option value="'.$facility->facility_id.'">'.$facility->facility.'</option>';
		
		endforeach;

		//$faci_options .="</select>";


		print_r($faci_options);
 
	}



	


}
