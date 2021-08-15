
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
    <h4  style="text-align:center;">Upload Biometric Machine CSV file</h4>
    <hr/>
	</div>
		<div class="panel-body" style="padding:5em;">
        <?php echo $this->session->flashdata("msg");   //import_machine  ?>
    <form method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>attendance/importMachineCSv" >
        <div class="form-group col-md-4">
            <label>Select Facility Category</label>
            <select name="category" class="form-control" required>
                <option value="">--SELECT--</option>
                <option value="">National Database</option>
                <option value="RRH|">Regional Referral</option>
                <option value="MOH|">Ministry of Health</option>
                <option value="MUN|">Municipality</option>
                <option value="MUL|">Mulago</option>
                <option value="BUT|">Butabika</option>
            </select>
        </div>
        <div class="form-group col-md-4">
            <label>Select Machine CSV file</label>
            <input type="file" name="machine_file" required>
        </div>
        
         <div class="form-group col-md-4">
            <button type="button" style="margin-top:20px;" data-toggle="modal" data-target="#catconfirm" class="btn btn-success"><i class="ion ion-ios-cloud-upload"></i>Upload Logs</button>
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
