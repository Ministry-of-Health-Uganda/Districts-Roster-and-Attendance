<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Districts_mdl extends CI_Model {

	
	public function __Construct(){

		parent::__Construct();

	}



	public function getDistricts()
	{

		$this->db->select('distinct(district_id),district');
		$this->db->where("district_id!=''");
		$query=$this->db->get('ihrisdata');

		return $query->result();
 
	}

		public function getDistrict($id)
	{

		$this->db->select('district');
		$this->db->where('district_id',$id);
		$query=$this->db->get('ihrisdata');

		$result=$query->row();

		return $result->district;
 
	}
	
	
		public function getFacility($id)
	{

		$this->db->select('facility');
		$this->db->where('facility_id',$id);
		$query=$this->db->get('ihrisdata');

		$result=$query->row();

		return $result->facility;
 
	}


		public function getFacilities($districtid)
	{

		$this->db->select('distinct(facility_id),facility');
		$this->db->where('district_id',$districtid);
		$query=$this->db->get('ihrisdata');

		$result=$query->result();

		return $result;
 
	}




	


}
