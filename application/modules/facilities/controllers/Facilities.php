<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities extends MX_Controller {

	
	public function __Construct(){

		parent::__Construct();

		$this->load->model('facilities_mdl');

	}


	public function getFacilities($district=FALSE){
        $district=urlencode($district);

		$facilities=@$this->jobs_mdl->getFacilities($district);

   
		$faci_options.="<option disabled selected>--Select Facility--</option>";

		foreach ($facilities as $facility):

			$faci_options.='<option value="'.htmlspecialchars($facility->facility_id).'">'.$facility->facility.'</option>';
		
		endforeach;

		//$faci_options .="</select>";


		//print_r($faci_options);


	}




	


}
