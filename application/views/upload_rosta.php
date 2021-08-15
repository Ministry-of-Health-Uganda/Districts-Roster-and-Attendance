   

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
      <!-- Small boxes (Stat box) -->
             <div class="row">






<div class="col-md-12">
   <!-- general form elements -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Upload Duty Rota</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
           
              <div class="box-body" id='scheduletbl'>
                

                <div class="col-md-6">

                  <form class="form-inline" method="post" action="<?php echo base_url(); ?>attendance/upload_rota" enctype="multipart/form-data" />

                    <div class="form-group">

                      <label>Select Rota File</label>
                      
                      

                        <input  type="file" name="rota">
                      
                        
                 
                      </div>

                      <div class="form-group">

                        <button class="btn btn-info btn-sm" type="submit"><i class="glyphicon glyphicon-upload"></i> Upload Rota</button>

                      </div>

                       </form>

                       <h4>File Preparation Rules:</h4>

                       <ul>
                        <li>Always use the template the system gives you</li>
                        <li>Use Letters to indicate duty state e.g Day duty; D</li>
                        <li>Avoid altering the given person ID to minimise errors</li>
                        <li>Only submit a one month schedule for upload</li>
                      

                       </ul>


                    </div>




                  <div class="col-md-6">

               <center><a href="<?php echo base_url(); ?>attendance/excel_template" class="btn btn-success"><i class="glyphicon glyphicon-file"></i> Download Rota Template</a></center>

                </div>






                </div>




            
              </div>
              <!-- /.box-body -->

           


          </div>
          <!-- /.box -->

        </div><!--col-md-12-->







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

<script type="text/javascript">

  $(document).ready(function(){

$('#scheduletbl').slimscroll({
  height: '400px',
  size: '5px'
});


$('.timepicker').timepicker({showInputs:false});



  });//doc
  



</script>