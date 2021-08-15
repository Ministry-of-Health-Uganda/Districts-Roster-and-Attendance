<?php 
include_once("includes/head.php");
include_once("includes/topbar.php");
include_once("includes/sidenav.php");
//include_once("");


?>
<!-- Content Wrapper. Contains page content -->


  <style type="text/css">
  
  .fc-view{
      
      max-height:300px;
      overflow-y:scroll;
     
  }
  


  </style>





  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
 

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
      
      <?php if($_SESSION['role']!=='sadmin') {
          
      //Its facility handler logged in , show facility dashboard data
      
      ?>
     <div class="row" style="padding-left:15px; padding-right:15px;">
       <div class="col-md-4 col-sm-6 col-xs-12">

        <div class="info-box">
            <span class="info-box-icon bg-orange"><i class="ion ion-ios-clock-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Schedule Types</span>
              <span class="info-box-number"><?php echo $widgets['schedules']; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
        </div>
        <!-- ./col -->
       
       <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">My Staff</span>
             <a href="<?php echo base_url(); ?>attendance/staff"></a>  <span class="info-box-number"><?php echo $widgets['staff']; ?></span></a>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
       <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="ion ion-ios-timer-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Scheduled H/Ws this Month</span>
             <a href="<?php echo base_url(); ?>rosta/tabular"><span class="info-box-number"><?php echo $widgets['duty']; ?></span></a>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
    </div>
        
      
        
        <?php 
        
        include("includes/dashcalendar.php");   
        
        } 
        
        else {
        
        
        //Its super admin logged in , show generic dashboard data
        ?>
        <div class="row" style="padding-left:15px; padding-right:15px;">
            <div class="col-md-4 col-sm-6 col-xs-12">

        <div class="info-box">
            <span class="info-box-icon bg-orange"><i class="ion ion-ios-clock-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Facilities</span>
              <span class="info-box-number"><?php echo $widgets['facilities']; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
        </div>
        <!-- ./col -->
       
       <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Staff</span>
             <a href="<?php echo base_url(); ?>attendance/staff"><span class="info-box-number"><?php echo $widgets['staff']; ?></span></a>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

       <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="ion ion-ios-timer-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Scheduled Facilities this Month</span>
             <a href="<?php echo base_url(); ?>admin/scheduled_report"><span class="info-box-number"><?php echo $widgets['duty']; ?></span></a>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
     </div>
        
        
   
        
        
        <?php  include("includes/dashcalendar.php");   } ?>
      
    
		</div>
		</div>
      <!-- /.row -->
      <!-- Main row -->
    </section>
    <!-- /.content -->

  <!-- /.content-wrapper -->
 <?php 
include_once("includes/footermain.php");
include_once("includes/rightsidebar.php");
include_once("includes/footer.php");

?>

 
 
	   <script src="<?php echo base_url();?>assets/js/bootstrapValidator.min.js"></script>
        <script src="<?php echo base_url();?>assets/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
        
		
        <script src="<?php echo base_url();?>assets/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
		<script src='<?php echo base_url();?>assets/js/dashcalendar.js'></script>


