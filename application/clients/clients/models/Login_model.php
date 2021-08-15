<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class 	Login_model extends CI_Model {

	
	public function getusers()
	{
     $myquery=$this->db->get('user');
	 $myusers=$myquery->result_array();
	 return $myusers;
   
	}
	
}
