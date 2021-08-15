<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class 	Mobile_attendance_model extends CI_Model {



	public  function __construct(){
		
		
		parent:: __construct();
		
}

	public function get_facility()
	{
		
		$query=$this->db->query("select distinct facility_id,facility from ihrisdata");

		$res=$query->result_array();

		return $res;
   
	}
	

	
		public function get_schedules()
	{
		$query= $this->db->get('schedules');

		return $query->result_array();
   
	}




	
		public function get_employees($facility)
	{
	
		$query=$this->db->query("select ihris_pid,surname,firstname,othername,job,telephone,mobile,department,facility,nin,ipps,facility_id from  ihrisdata where facility_id='$facility'");
		
		$result=$query->result_array();
		
		return $result;
		
		
   
	}
	



	

		public function add_schedules()
	{
		$data=array(
			'schedule'=>$this->input->post('schedule'),
			'letter'=>$this->input->post('letter'),
			'starts'=>$this->input->post('starts'),
			'ends'=>$this->input->post('ends')

	);
	
	$done=$this->db->insert('schedules',$data);

if($done){

$message="Schedule Added";
} else{

	$message="Operation Failed";
}  

return $message;
	}




	
	
	
}
