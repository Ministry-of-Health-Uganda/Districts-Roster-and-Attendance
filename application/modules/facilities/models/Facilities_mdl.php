<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities_mdl extends CI_Model {

	
	public function __Construct(){

		parent::__Construct();

	}



	public function getFacilities($district_id)
	{

		$this->db->select('distinct(facility),facility_id');
		$this->db->where('district_id',$district_id);
		//$this->db->order_by('facility','asc');
		$query=$this->db->get('ihrisdata');

		return $query->result();
 
	}



	



	


}
