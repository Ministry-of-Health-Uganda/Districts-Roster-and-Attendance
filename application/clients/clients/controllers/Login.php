<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	
	protected $name;
	
	public  function __construct(){
		
		
		parent:: __construct();
		
		
		$this->load->model('login_model');
		
		$this->name="Andrew";
		
		
		
	}
/*
	public function index()
	{
		
		$data['users']=$this->login_model->getusers();
		//$data['students']=$this->login->students();
		
		$data['name']=$this->name;
		
		$this->load->view('welcome_message',$data);
	}
	*/


	public function index()
	{
		
		$data['users']=$this->login_model->getusers();
		//$data['students']=$this->login->students();
		
		$data['name']=$this->name;
		
		$this->load->view('home',$data);
	}	
	
	
	
	
}
