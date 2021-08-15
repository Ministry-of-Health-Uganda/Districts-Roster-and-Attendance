   

<?php 
include_once("includes/head.php");
include_once("includes/topbar.php");
include_once("includes/sidenav.php");
include_once("includes/responsive_table.php");


?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" />

<style>
    .datepicker,.userin {
        max-width:250px;
        background-color:#fff;
    }
</style>
 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
 

    <!-- Main content -->
        <section class="content">
 
             <div class="row">






<div class="col-md-12">
   <!-- general form elements -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Time Logs </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" method="post" action="<?php echo base_url(); ?>attendance/timeLogReport">
            <div class="form-group col-md-2" style="margin-left:0.5em; margin-right:0.2em;">
            <div class="form-group">
                            <div class='input-group date datepicker'>
                            <input type='text' class="form-control from"  name="date_from" value="<?php echo $from; ?>" />
                            <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
                    <labe>Date (from)</labe>
            </div>
                
                 <div class="form-group col-md-2" style="margin-left:0.5em; margin-right:0.2em;"> <div class="form-group">
                            <div class='input-group date datepicker'>
                            <input type='text' class="to form-control"  name="date_to" value="<?php echo $to; ?>" />
                            <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
                    <labe>Date (to)</labe>
                    
                </div>
                
                  <div class="form-group col-md-3" style="margin-left:0.5em; margin-top:0.3em;">
                      <div class="form-group">
                    <div class='input-group userin'>
                     <input type="text" name="name" class="name form-control" placeholder="Search By Name" value="<?php echo $name; ?>">
                       <span class="input-group-addon">
                        <span class="glyphicon glyphicon-user"></span>
                     </span>
                    </div>
                    </div>
                
                <label>Name</label>
                </div>
                
                 <div class="form-group col-md-2" style="margin-left:0.5em; margin-top:0.3em;">
                    <button type="submit" class="btn btn-success"><i class="fa fa-search"></i>Apply</button>
                </div>
                
                <div class="form-group col-md-2" style="margin-left:0.5em; margin-top:0.3em;">
                    <input type="button" class="btn btn-success csvbtn" value="Get CSV" />
                </div>
            </form>
           
              <div class="box-body" id='scheduletb'>
                <table class="table table-striped table-bordered mytbl ">
                  <thead>
                         <th style="width:50px;">#</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Hours Worked</th>
                    
                    <!--<th width="13%"></th>-->
                  </thead>

                  <tbody>
                     

                     <?php 
                      $i=1;
                     foreach($timelogs as $timelog):  ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td data-label="Name"><?php echo $timelog->surname." ".$timelog->firstname; ?></td>
            <td data-label="Date"><?php echo $timelog->date; ?></td>
            <td data-label="Time In"><?php echo $timelog->time_in; ?></td>
            <td data-label="Time Out"><?php echo $timelog->time_out; ?></td>
            <td data-label="Hours Worked">
            <?php 
            
            if($timelog->time_in && $timelog->time_out){
            $timeOut=new DateTime($timelog->time_out); 
            $timeIn=new DateTime($timelog->time_in); 
            
            $diff=$timeOut->diff($timeIn);
            
            echo $diff->format("%h hours");
                
            }
            
            else {
                
                echo "0 hours";
            }
            
            ?>
            </td>
        </tr>
        
        <?php endforeach; ?>
                   
                  </tbody>


                </table>
               
<p class="pull-right"><?php echo $links; ?></p>
            
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                
              </div>
         


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

  $('.mytbl').DataTable({
          "paging": false,
          "lengthChange": true,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": true,
		  "responsive": true,
		  "iDisplayLength": 20,
    	  "aLengthMenu": [[10, 20, 30, 50, 100, -1], [10, 20, 30, 50, 100, "All"]]
        });	
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>

<script>
    $('.datepicker').datepicker({
        format:"yyyy-mm-dd"
    });
    
    
    //fetch csv
    $('.csvbtn').click(function(e){
        
        e.preventDefault();
        
        var from=$('.from').val();
        var to=$('.to').val();
      
                
     window.open('<?php echo base_url(); ?>attendance/machineCsv/'+from+"/"+to,'_blank');
                
       
              
        
        
    });
    
</script>