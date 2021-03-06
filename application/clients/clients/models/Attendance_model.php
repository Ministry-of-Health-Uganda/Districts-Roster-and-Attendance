<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class 	Attendance_model extends CI_Model {



	public  function __construct(){
		
		
		parent:: __construct();
		
		//if not logged in, go to login page
		 if(!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }
	
		
		$this->username=$this->session->userdata['names'];
		$this->facility=$this->session->userdata['facility'];
		
	}

	

	
	
	public function read_employee_csv($importdata)
	{
	
	ini_set('max_execution_time', 0);
	// Get employee data from the csv file from HRIS and upload it to the HRIS records table.
	
	$pid=$importdata['ihris_pid'];
	$district=$importdata['district'];
	
	$check=$this->db->query("Select * from ihrisdata where ihris_pid='$pid'");
	
	$rows=$this->db->affected_rows();
	
	if($rows<1){
	    if($district!==""){
	    
	$this->db->insert('ihrisdata',$importdata);
	    }
	
	}
	
	else{
	    
	    if($district!==""){
	        
	    $this->db->where('ihris_pid',$pid);
	    
	    $this->db->update("ihrisdata",$importdata);
	    }
	}
   
	}


//import rota data
		public function save_upload($importdata)
	{
	
	ini_set('max_execution_time', 0);
	// Get employee data from the csv file from HRIS and upload it to the HRIS records table.
	
	$this->db->insert('duty_rosta',$importdata);
   
	}
	

	
	public function get_facility()
	{
		$district=$_SESSION['district'];
		
		if($district!==""){
		$query=$this->db->query("select distinct facility_id,facility,district_id from ihrisdata where district_id='$district' order by facility asc");
		
		}
		
		else
		{
		  $query=$this->db->query("select distinct facility_id,facility,district_id from ihrisdata order by facility asc");  
		    
		}

		$res=$query->result_array();

		return $res;
   
	}
	
	
	
		public function get_districts()
	{

		  $query=$this->db->query("select distinct district_id,district from ihrisdata order by district_id asc");  
		    
	

		$res=$query->result_array();

		return $res;
   
	}
	
	
	

		public function add_schedules()
	{
		$data=array(
			'schedule'=>$this->input->post('schedule'),
			'letter'=>$this->input->post('letter'),
			'starts'=>$this->input->post('starts'),
			'ends'=>$this->input->post('ends'),
			'purpose'=>$this->input->post('purpose')

	);
	
	$done=$this->db->insert('schedules',$data);

if($done){

$message="Schedule Added";
} else{


$message=$data['purpose'];
	//$message="Operation Failed";
}  

return $message;
	}



	
		public function get_schedules($flag=FALSE)
	{
	    
	    if(empty($flag)){
	        
	        $flag="r";
	        
	    }
		
		$this->db->where("purpose",$flag);
		$query= $this->db->get('schedules');

		return $query->result_array();
   
	}


		public function delete_schedules($id)
	{
		
		$this->db->where('schedule_id',$id);
		$query= $this->db->delete('schedules');
if($query){

	return "Schedule Deleted";
}
		
   
	}
	
	
		public function get_employees()
	{
		$facility=$this->facility;

		$query=$this->db->query("select ihris_pid,surname,firstname,othername,job,telephone,mobile,department,facility,nin,ipps,facility_id from  ihrisdata where facility_id='$facility'");
		
		$result=$query->result_array();
		
		return $result;
		
		
   
	}
	
	


	public function template_data(){

		$facility=$this->facility;


		$query=$this->db->query("select ihrisdata.ihris_pid,concat(ihrisdata.surname,' ',ihrisdata.surname) as names from ihrisdata where (ihrisdata.facility_id='$facility') group by ihrisdata.ihris_pid ");

		$result=$query->result_array();

		return $result;
	}
	
	


	public function widget_data(){

		$facility=$this->facility;

if($_SESSION['role']!=='sadmin'){


		$query1=$this->db->query("select count(schedules.schedule_id) as schedules from schedules ");

		$result1=$query1->result_array();

		$schedules=$result1[0]['schedules'];

	$query2=$this->db->query("select count(user.username) as users from user ");


		$result2=$query2->result_array();

		$users=$result2[0]['users'];


	$query3=$this->db->query("select count(ihrisdata.ihris_pid) as staff from ihrisdata where facility_id='$facility' ");


		$result3=$query3->result_array();

		$staff=$result3[0]['staff'];

		$date=date('Y-m');  // this month for gettting schedules

	$query4=$this->db->query("select count(distinct dutyreport.ihris_pid) as duty from dutyreport where facility_id='$facility' and duty_date like '$date%'");


		$result4=$query4->result_array();

		$scheduled=$result4[0]['duty'];

$result=array("schedules"=>$schedules,"users"=>$users,"staff"=>$staff,"duty"=>$scheduled);
}

else{
    
    
    	$query1=$this->db->query("select count(distinct facility_id) as facilities from ihrisdata ");

		$result1=$query1->result_array();

		$facilities=$result1[0]['facilities'];

	$query2=$this->db->query("select count(user.username) as users from user ");


		$result2=$query2->result_array();

		$users=$result2[0]['users'];


	$query3=$this->db->query("select count(ihrisdata.ihris_pid) as staff from ihrisdata  ");


		$result3=$query3->result_array();

		$staff=$result3[0]['staff'];

		$date=date('Y-m');  // this month for gettting schedules

	$query4=$this->db->query("select count(distinct dutyreport.facility_id) as duty from dutyreport where duty_date like '$date%'");


		$result4=$query4->result_array();

		$scheduled=$result4[0]['duty'];

$result=array("facilities"=>$facilities,"users"=>$users,"staff"=>$staff,"duty"=>$scheduled);
    
}


		return $result;
	}


    function get_vars(){
            
    $this->db->from("variables");
   $this->db->order_by("variable", "desc");
   $this->db->group_by('rowid');
   $query = $this->db->get(); 
   return $query->result_array();
   
    }
    
    public function machine_facilities(){
        
        	$query=$this->db->query("select distinct(facility_id),facility from ihrisdata ");
        	
        	$results=$query->result();
        	
        	$facilities=array();
        	
        	foreach($results as $facility){
        	    
        	    $facilities[$facility->facility]=$facility->facility_id;
        	    
        	}
        	
        	return $facilities;
        	
    }
    
    
    
    	public function read_machine_csv($importdata)
	{
	
	ini_set('max_execution_time', 0);
	// Get employee data from the csv file from HRIS and upload it to the HRIS records table.
	
	    
	$import=$this->db->insert('time_log',$importdata);
	
	if($import){
	    
	    return "ok";
	}
	
	else{
	    
	    return "failed";
	}

	
	}
	
	
	//get total rows to use in pagination of timelogs 
	public function count_timelogs(){
	    
	   return  $this->db->count_all('time_log');
	}
  
  public function fetchTimeLogs($limit,$start,$search_data=FALSE){
      
        $facility=$this->facility; //current facility
      
      
      if($search_data){


          $date_from=$search_data['date_from'];
          $date_to=$search_data['date_to'];
          $name=$search_data['name'];
          
          if($name){
              
              $ids=$this->getIds($name);
              
              if(count($ids)>0){
              
              $this->db->where_in('time_log.ihris_pid',$ids);
              }
          }
          
          $this->db->where("date >= '$date_from' AND date <= '$date_to'");
      }
      
         $this->db->limit($limit,$start);
        //$this->db->where('time_log.facility_id',$facility);
	    $this->db->join("ihrisdata","ihrisdata.ihris_pid=time_log.ihris_pid");
	    $query=$this->db->get("time_log");
	    return $query->result();
	}


public function getIds($name){
    $this->db->select('ihris_pid');
    $this->db->where("firstname like '%$name%'");
    $this->db->or_where("othername like '%$name' ");
    $query=$this->db->get('ihrisdata');
    $result=$query->result();
    $ids=array();
    foreach($result as $row){
        
        array_push($ids,$row->ihris_pid);
    }
    
    return $ids;
    
    
}



  
  public function getMachineCsvData($date_from=FALSE,$date_to=FALSE){
      
        $facility=$this->facility; //current facility
      
      
      
        $this->db->select("concat(ihrisdata.firstname,' ',ihrisdata.surname) as names,ihrisdata.facility,time_log.time_in,time_log.time_out,TIMEDIFF(time_log.time_out,time_log.time_in) as hours,time_log.date");
      
      if($date_from){

   
          $this->db->where("date >= '$date_from' AND date <= '$date_to'");
      }
      
	    $this->db->join("ihrisdata","ihrisdata.ihris_pid=time_log.ihris_pid");
	    $query=$this->db->get("time_log");
	    $rows=$query->result_array();
	   
	    return $rows;
	}

    
  
    


	
	
}
