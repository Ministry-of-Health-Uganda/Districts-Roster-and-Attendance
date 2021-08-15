
<?php 
$systemvars=$this->attendance_model->get_vars();


foreach($systemvars as $vars){

 if($vars['variable']=="System Name"){
 
 $system_name=$vars['content'];
 
 $wording=explode(" ", $system_name);

	$abbrev="";

	foreach ($wording as $word) {

		$abbrev.=$word[0];
	}
 
 }
	else if($vars['variable']=="Duty Rosta Start Date"){
	$startdate=$vars['content'];
	}
	
 else if($vars['variable']=="Duty Rosta End Date"){
	$deadline=$vars['content'];
	}

else if($vars['variable']=="Date to Fetch IHRIS Data"){
	$_SESSION['fetch_date']=$vars['content'];
	}
	
	
	else if($vars['variable']=="Default_password"){
	$_SESSION['defaultpass']=$vars['content'];
	}



}

?>



<body class="hold-transition skin-blue sidebar-mini fixed">
    
    
 <span class="baseurl" style="display:none;"><?php echo base_url(); ?></span>   
    

<!-- Modal -->
<div id="pass" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Change Your Password</h4>
        
        <span class="changed"></span>
      </div>
      <div class="modal-body">
        <form  method="post" id="change_form">
            
            
            <?php 
            if($pass_changed==1){ ?>
            
            <div class="form-group">
                
                <label>Old Password</label>
                <input type="password" class="form-control" name="old" id="old">
            </div>
            
          <?php } ?>
                
            
            
            <div class="form-group">
                <label>New Password</label>
                <input type="password" class="form-control" name="new" id="new" onkeyup="checker();">
                <p class="help-block error"></p>
            </div>
            
              <div class="form-group">
                  <label>Confirm Password</label>
                <input type="password" class="form-control" name="confirm" id="confirm" onkeyup="checker();">
                <p class="help-block error"></p>
            </div>
            
            
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        
        <button type="submit" class="btn btn-success" >Change Password</button>
      </div>
      
      </form>
    </div>

  </div>
</div>

<!--kk-->

    
    
  
<div class="wrapper" style="max-height: 100%; overflow: hidden;">

  <header class="main-header static-top">
    <!-- Logo -->
    <a href="" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b><?php echo $abbrev; ?></b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b> <?php echo $system_name; ?> </span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <p class="" style="float:left; margin-left:2px;">
           <button type="button" class="btn btn-success btn-sm btn-outline" style="margin-top:0.8em; margin-right:7px;">Last iHRIS SYNC: <?php echo $this->attendance_model->last_sync();?></button>   
              
            </p>
          <?php if($_SESSION['role']=='District-Officer' or $_SESSION['role']=='sadmin' ){  ?>
          
          <li>
           <button type="button" class="btn btn-success btn-sm btn-outline" data-toggle="modal" data-target="#myModal" style="margin-top:0.8em;">Switch Facility</button>   
              
          </li>
          
          <?php } ?>
       
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">1</span>
            </a>
            <ul class="dropdown-menu">

              <?php if($checks['workedon']==0) { ?>

              <li class="header"><b>Month's Schedule not made</b></li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> Make this month's rota...
                    </a>
                  </li>
                  </ul>
                </li>

                  <?php } else ?>


                    <?php if($checks['workedon']<$checks['staffs'] and $checks['workedon']>0 ) { ?>

              <li class="header"><b>Month's Schedule Incomplete</b></li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> You should complete this month's rota...
                    </a>
                  </li>
                  </ul>
                </li>

                  <?php } else  ?>


                  <?php if($checks['workedon']==$checks['staffs']) { ?>

              <li class="header"><b>This Month's rota Completed</b></li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> Congs upon completing this month's rota!
                    </a>
                  </li>

                </ul>
                </li>

                  <?php } ?>
                 
                
              
           
            </ul>
          </li>



          <!-- User Account: style can be found in dropdown.less -->
          <li class="user user-menu" style=''>
            <a href="<?=base_url()?>authentication/logout" class="dropdown-toggle">
              <img src="<?php echo base_url(); ?>assets/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs">Sign Out <?php echo $username; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo base_url(); ?>assets/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  <?php echo $username; ?>
                  <small></small>
                </p>
              </li>
         
              <!-- Menu Footer-->
              <li class="user-footer">
               
               
              </li>
            </ul>
          </li>
        
        </ul>
      </div>
    </nav>
  </header>
  
  <?php 


 $mydistrict=$this->session->userdata('district');



$topdistricts=Modules::run('districts/getDistricts');

$distoptions="";

$disabled="";

//if district admin
if(!empty($mydistrict) && $_SESSION['role']!='sadmin'){

  $disabled="readonly";


  foreach ($topdistricts as $dists) {
  $distoptions=$mydistrict;

   if($dists->district==$mydistrict){

    $distoptions .="<option selected value='".$dists->district_id."'>".$dists->district."</option>";

   }

}

}


else{

  $distoptions ="<option selected value=''>--Select District--</option>";
    $districts_ops="";
    foreach ($topdistricts as $dist) {


      $distoptions .="<option value='".$dist->district_id."'>".$dist->district."</option>";
      $districts_ops.="<option value='".$dist->district_id."'>".$dist->district."</option>";
    
    }

  }


    ?>
  
  <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Switch Facility</h4>
      </div>
      <div class="modal-body">
       
        
 <form action="<?php echo base_url('admin/selector'); ?>" method="post">

  <div class="container" style="max-width:100%;">
   

   <div class="form-group col-md-12">

             <select class="form-control district"  style="width: 100%;"  name="district" <?php echo $disabled; ?> >
                <option disabled selected value="<?php echo $mydistrict; ?>"><?php echo Modules::run('districts/getDistrict',$mydistrict); ?></option>

                <?php echo $distoptions; ?>

              </select>
     </div>

   
   <div class="form-group col-md-12">
    <select name="facility" style="width: 100%;" class="form-control facilitys" required>
        
        <option>---SELECT FACILITY---</option>

        <?php echo Modules::run('districts/getFacilities',$mydistrict); ?>
        
    </select>

    </div>
 
  </div>

  

       
       
      </div>
      <div class="modal-footer">
       
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
           
          <button type="submit" class="btn btn-success">Switch</button>
      </div>
    </div>
    
    </form> 


  </div>
</div>



  <script type="text/javascript">

  $('.district').change(function(e){

    e.preventDefault();

var districtId=$(this).val();

console.log(districtId);

var url="<?php echo base_url(); ?>districts/getFacilities/"+districtId;

$.ajax({
  url:url,
  success:function(response){

 $('.facilitys').html(response);


    console.log(response);
  }
});

});


 $('.district').select2();
 $('.facilitys').select2();
 


</script>
