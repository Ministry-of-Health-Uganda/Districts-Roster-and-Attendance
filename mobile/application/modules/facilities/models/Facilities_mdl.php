<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities_mdl extends CI_Model {

	
	public function __Construct(){

		parent::__Construct();

	}



	public function getFacilities($district_id)
	{

		$this->db->select('distinct(facility),facility_id');
		$this->db->where('district_id',"district|".$district_id);
		$query=$this->db->get('establishment');

		return $query->result();
 
	}


    public function getAttFacilities($district_id)
	{

		$this->db->select('distinct(facility),facility_id');
		$this->db->where('district_id',"district|".$district_id);
		$query=$this->db->get('ihris_att');

		return $query->result();
 
	}


	public function getTypes($table)
	{

		$this->db->select('distinct(facility_type) as type,facility_type_id as type_id');
		$query=$this->db->get($table);

		return $query->result();
 
	}

	



	


}
