
  <?php 
            
            $uid=$this->session->userdata['uid'];//auth id
            
            
            //whether user has already changed default password
            $pass_date=$this->session->userdata['pass_changed'];
            
            if(!empty($pass_date)){
                
                // user has ever changed password
                
                $secs=strtotime(date("Y-m-d"))-strtotime($pass_date);
                
                
                if($secs>=7889238){  // six months since last password change
                    
                    $pass_changed=0;
                }
                
                
                // less than a month
                else{
                    
                    $pass_changed=1;
                }
            }
            
            else{
                
                //has never changed password
                
                $pass_changed=0;
            }
                
        
        $hello=$this->aauth->get_user_groups($uid);
        
        
        
        foreach($hello as $h){
        
        $mygroup= $h->id;
        
        }
        
 

 

?>
  

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Attendance Tracking System</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  

  <!-- fullCalendar -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/fullcalendar/dist/fullcalendar.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/fullcalendar/dist/fullcalendar.print.min.css" media="print">
  <link href="<?php echo base_url();?>assets/css/bootstrapValidator.min.css" rel="stylesheet" />        

  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">



   <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/datatables/dataTables.bootstrap.css">
 
        <!-- Custom css  -->
   <link href="<?php echo base_url();?>assets/css/custom.css" rel="stylesheet" />
   
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
   <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/skins/intra.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <!--link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.min.css"-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/select2/dist/css/select2.min.css">
  

  <!-- Date Picker -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  
  <!-- bootstrap wysihtml5 - text editor -->

  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

       <!-- bootstrap  time picker -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/timepicker/bootstrap-timepicker.min.css">

 
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/select2.css">

        

  <!--link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"-->

<!-- Responsive table -->  
  
<link href="<?php echo base_url(); ?>assets/css/responsive.css" rel="stylesheet"/>

<style>
    .name{
        
     min-width:11em;
     text-align:left;
     padding-left:0.5em;
     
    }
</style>


<!-- jQuery 3 -->
<script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->


<script src="<?php echo base_url(); ?>assets/js/select2.js"></script>


  
</head>
