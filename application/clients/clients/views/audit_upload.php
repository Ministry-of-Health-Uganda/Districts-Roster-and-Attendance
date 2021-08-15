
<?php 
include_once("includes/head.php");
include_once("includes/topbar.php");
include_once("includes/sidenav.php");
//include_once("");


?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
 

    <!-- Main content -->
        <section class="content">
    

<div class="col-md-12">

	<div class="panel">

		<div class="panel-header">
    <h4  style="text-align:center;">Mobile Audit Supporting Data Upload</h4>
    <hr/>
	</div>
		<div class="panel-body" style="padding:3%;">
        <?php echo $this->session->flashdata("msg");   //import_machine  ?>
        
        <div class="col-md-6" >
    <h5  style="text-align:left; padding-bottom:1em; text-weight:bold;">Establishment data Upload</h5>
             
    <form method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>attendance/importEstabCSv" >
     
        <div class="form-group">
            <label>Select Establishment CSV file</label>
            <input type="file" name="esta_file" required>
        </div>
        
         <div class="form-group">
            <button type="submit" style="margin-top:20px;" class="btn btn-success"><i class="ion ion-ios-cloud-upload"></i> Upload</button>
        </div>
        
        </form>
        
        </div>
        
        
            <div class="col-md-6">
                
                 <h5  style="text-align:left; padding-bottom:1em; text-weight:bold;">Attendance data Upload</h5>
             
    <form method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>attendance/importAttCSv" >
     
        <div class="form-group">
            <label>Select Attendnace CSV file</label>
            <input type="file" name="att_file" required>
        </div>
        
         <div class="form-group">
            <button type="submit"  style="margin-top:20px;" class="btn btn-success"><i class="ion ion-ios-cloud-upload"></i> Upload</button>
        </div>
        </form>
        </div>
    

</div></div>


<!--confirmation modal-->
<div class="modal" id="catconfirm" >
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                
                <button type="button" data-dismiss="modal" class="btn btn-default pull-right">&times;</button>
                <h5 class="">Confirm Operation</h5> 

            </div>
            <div class="modal-body">
                Are you sure you want to upload data to this category?
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Yes, Proceed</button>
               
            </div>
            
        </div>
    </div>
</div>

</form>

</div><!--col 12-->

            </div>
  <!-- /.content-row -->
   </section>
    <!-- /.section-->
  </div>
  
  <!-- /.content-wrapper -->
 <?php 
include_once("includes/footermain.php");
include_once("includes/rightsidebar.php");
include_once("includes/footer.php");



?>