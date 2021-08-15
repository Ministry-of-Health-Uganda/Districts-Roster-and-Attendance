<?php



if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Admin extends MX_Controller {

    public function __Construct() {
        
       parent::__Construct();
        
        if(!$this->session->userdata('logged_in')) {
            redirect(base_url());

            
        }

    
        $this->load->library('pagination');
        $this->load->model('admin_model');
         $this->load->model('attendance_model');
        

        $this->username=$this->session->userdata['names'];
        $this->uid=$this->session->userdata['uid'];
        
    }
    

    private function ajax_checking(){
        if (!$this->input->is_ajax_request()) {
            redirect(base_url());
        }
    }

    public function user_list(){

        $key=$this->input->post('search_key');
        if(empty($key))
        $key=FALSE;

        $config=array();
	    $config['base_url']=base_url()."admin/user_list";
	    $config['total_rows']=$this->admin_model->countuserList($key);
	    $config['per_page']=15; //records per page
        $config['uri_segment']=3; //segment in url
        $data['key']=$key;
	    
	    //pagination links styling
	    $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
    
        $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
    
        $config['next_link'] = '<i class="fa fa-long-arrow-right"></i>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        
	    $this->pagination->initialize($config);
	    
	    $page=($this->uri->segment(3))? $this->uri->segment(3):0; //default starting point for limits
	    
        $data = array(
            'title' => 'User Management',
            'users' => $this->admin_model->get_user_list($page,$config["per_page"],$key),
            'facilities' => $this->attendance_model->get_facility(),
            'districts' => $this->attendance_model->get_districts(),
            'links'=>$this->pagination->create_links(),
            'username'=>$this->username
            //'switches'=>$this->switches()
        );
        

        $this->load->view('users', $data);

    }


    public function settings(){

        $data = array(
            'title' => 'System Variables',
            'vars' => $this->admin_model->get_vars(),
            'facilities' => $this->attendance_model->get_facility(),
            'districts' => $this->attendance_model->get_districts(),
            'username'=>$this->username
        );



        

        $this->load->view('config', $data);

    }


public function configure(){
    
    $postData=$this->input->post();
   
   $res= $this->admin_model->save_config($postData);
   
   print_r($res);
   
    
    
}



    public function showLogs(){

        $data = array(
            'title' => 'User Activity Logs',
            'logs' => $this->admin_model->get_logs(),
            'facilities' => $this->attendance_model->get_facility(),
            'districts' => $this->attendance_model->get_districts(),
            'username'=>$this->username
        );



        

        $this->load->view('user_logs', $data);

    }
    
    
    public function clearLogs(){
        
        $this->admin_model->clearLogs();
        
        $this->showLogs();

    }



	
	public function switches(){
	    
	    
	    $facilities=$this->attendance_model->get_facility();		
		 
  $newArray=array();
  
  $switches="";
  
foreach($facilities as $val){
    
    $newKey=$val['district_id'];
    
    $newArray[$newKey][]=$val;
    
     $switches. "<optgroup label='".$newKey."'>";
    
    for($i=0;$i<count($newArray[$newKey]);$i++){
        
        $switches. "<option value='".$newArray[$newKey][$i]['facility_id']."'>".$newArray[$newKey][$i]['facility']."</option>";
    }
    
    $switches. "</optgroup>";
    
}

return $switches;
	    
	    
	}

    public function select(){


        $data = array(
            'facilities' => $this->attendance_model->get_facility(),
            'username'=>$this->username
        );


        $this->load->view('select',$data);

    }


    public function selector(){
        
        $facility=$this->input->post('facility');
        
    // modify session facility id
$this->session->set_userdata('facility',$facility);
        
        redirect('attendance');

    }




    function add_user(){
        $this->ajax_checking();

        $postData = $this->input->post();

	if($postData['email']==""){

	$postData['email']=$postData['username']."_noemail@hris.com";
        }
    
       $user= $this->aauth->create_user($postData['email'],$postData['password'],$postData['name']);
        
       $users=$this->aauth->list_users();
       
       foreach($users as $user){
           
           if($user->email==$postData['email']){
               
               $uid=$user->id;
           }
       }
        
       $this->aauth->add_member($uid, $postData['role']);
       
       
         $insert = $this->admin_model->insert_user($postData,$uid);
        
    
        echo json_encode($insert);
       
       
    }


    function edit_user(){
        $this->ajax_checking();

        $postData = $this->input->post();
        $update = $this->admin_model->update_user_details($postData);
        
        if($update['status'] == 'success')

        echo json_encode($update);
    }


    function deactivate_user($username,$id){

       $update = $this->admin_model->deactivate_user($username,$id);
        
        if($update['status'] == 'success')

        echo "User successfully deactivated";
    }


 function activate_user($username,$id){

       
       $update = $this->admin_model->activate_user($username,$id);
        
        if($update['status'] == 'success')

        echo "User successfully activated";
    }


    public function scheduled_report(){
      $year=$this->input->post('year');
      $month=$this->input->post('month');
      $district=$this->input->post('district');

        $data = array(
            'title' => 'Facilities that Scheduled',
            'facilities' => $this->attendance_model->get_facility(),
            'districts' => $this->attendance_model->get_districts(),
            'schedules' => $this->admin_model->list_scheduled($month,$year,$district),
            'username'=>$this->username
        );



       // print_r($data);

        $this->load->view('scheduled', $data);

    }






    
public function groups(){
    
    $data = array(
            'title' => 'Groups Management',
            'username'=>$this->username
            //'switches'=>$this->switches()
        );

        

        $this->load->view('groups', $data);
    
    
}

public function resetpass($user){
    $variables=$this->admin_model->get_vars();
   
   $pass="";
	foreach($variables as $vars){
	
	
	if($vars['variable']=="Default_password"){
	$pass=$vars['content'];
	}
}

    $data = array(
            'password' => md5($pass)
        );

    $this->db->where('user_id',$user);
    $this->db->update('user',$data);
    
    $this->session->set_flashdata('msg','<div class="alert alert-info alert-dismissable col-md-12"style="width:90%; margin-left:3em;">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
   Password has been reset to default. </div>');
    
                  redirect("admin/user_list");
    
}

///user management


    function add_member() {
        
        $group=$this->input->post('group');
        
        $id=$this->input->post('member');


        $a = $this->aauth->add_member($id, $group);
    }




//create a permission
    function create_perm() {

        $a = $this->aauth->create_perm("deneme","def");
    }



  
//allow group to do some thing
    function groupAllow() {


$data=$this->input->post();
$group=$this->input->post('group');


$permissions=$_POST['permissions']; 

$this->db->where('group_id',$group);
$this->db->delete('aauth_perm_to_group');

foreach($permissions as $permission)

{
   
$this->aauth->create_perm($permission);

$a=$this->aauth->allow_group($group,$permission);
        
}


if($a){
    
echo 'OK';
}

    }


//

    function deny_group() {

        $a = $this->aauth->deny_group("deneme","deneme");
    }




    function allow_user() {


//allow user id =9 to do something=deneme
        $a = $this->aauth->allow_user(9,"deneme");
    }




    function deny_user() {

        $a = $this->aauth->deny_user(9,"deneme");
    }
    
    
  

function addgrp(){
    
    $group=$this->input->post('group');
    $this->aauth->create_group($group);
    
    $this->session->set_flashdata("msg","<font color='green'>Group Added</font>");
    
    redirect('admin/groups');
}








}

/* End of file */

