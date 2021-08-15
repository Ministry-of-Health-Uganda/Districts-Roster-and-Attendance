

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
            
         <?php    include("includes/dashcalendar.php");   ?>
      <!-- Small boxes (Stat box) -->
            <div class="row">

        <div class="modal fade calendarmodal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
					 <!-- Notification -->
                <div class="alert" style="z-index: 1000;"></div>
				
                        <div class="error"></div>
                        <form class="form-horizontal" id="crud-form">
                        <input type="hidden" id="start">
                        <input type="hidden" id="end">
                            <div class="form-group">
                                <label class="col-md-6 control-label" style="text-align: left;" for="title">Health Worker</label>
                                <div class="col-md-12">
                                    <select id="user" name="user" class="form-control" >
                                       
                                       <?php foreach($workers as $worker) { ?>
                                       
                                        <option value="<?php echo $worker['ihris_pid']; ?>"><?php echo $worker['surname']." ".$worker['firstname']; ?></option>
                                     
                                            <?php } ?>

                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-6 control-label" style="text-align: left;" for="duty">Schedule</label>
                                <div class="col-md-12">
                                    <select id="duty" name="duty" class="form-control" >
                                        
                                        <?php foreach($schedules as $duty) { ?>
                                       
                                        <option value="<?php echo $duty['schedule_id']; ?>"><?php echo $duty['schedule']; ?></option>
                                     
                                            <?php } ?>
                                    </select>
                                </div>
                            </div> 



                            <div class="form-group">
                                <label class="col-md-6 control-label" style="text-align: left;" for="color">Color</label>
                                <div class="col-md-12">
                                    <input id="color" name="color" type="text" class="form-control input-md" readonly="readonly"/>
                                    <span class="help-block">Duty Color</span>
                                </div>
                            </div>
                    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
			   </form>
                </div>
            </div>
        </div>
        
        </div>
  <!-- /box-body -->
  </div>
  <!-- /box- -->
 
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

	   <script src="<?php echo base_url();?>assets/js/bootstrapValidator.min.js"></script>
        <script src="<?php echo base_url();?>assets/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
        
		
        <script src="<?php echo base_url();?>assets/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
		<script src='<?php echo base_url();?>assets/js/rosta.js'></script>

     
<script type="text/javascript">
    
    $('#duty').change(function(){

        var duty=$('#duty').val();

//day
if(duty=='14'){

    var kala='#d1a110';
}

else
    if(duty=='15'){
//even
    var kala='#49b229';
}

else

//night
if(duty=='16'){

    var kala='#29b299';
}
else

//off
if(duty=='17'){

    var kala='#297bb2';
}

else
//annual leave
if(duty=='18'){

    var kala='#603e1f';
}


else

//study leave
if(duty=='19'){

    var kala='#052942';
}

else
//maternity leave
if(duty=='20'){

    var kala='#280542';
}

else
//other
if(duty=='21'){

    var kala='#420524';
}


$('#color').val(kala);
$('#color').css('background-color',kala);


    });


</script>

