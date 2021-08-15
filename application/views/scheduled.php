   

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
          
          
       <div class="row" style="margin:4px;">
          <div class="box box-default">
             <div class="box-header with-border">
             
   <div class="col-md-12">
			
             <h4><?php echo $title; ?></h4>
             </div>
  <div class="col-md-12">
  <span class="suc pull right"></span>

       
       <form method="post" id="scheduled_form" action="<?php echo base_url(); ?>admin/scheduled_report" class="form-horizontal">
       
    <div class="col-md-4">
       <div class="form-group">
           <label>District:</label>
           
           <select name="district" class="form-control select2">
               <option value="all">All</option>
               <?php foreach($districts as $district): ?>
               <?php if($district['district_id']!=""): ?>
               <option value="<?php echo $district['district_id']; ?>"><?php echo $district['district']; ?></option>
               
               <?php endif; endforeach; ?>
               
           </select>
           
       </div>
       </div>
       
       
       <div class="col-md-3">
       <div class="form-group">
           <label>Month:</label>
           
           <select name="month" class="form-control">
               <option value="<?php echo date("m"); ?>"><?php echo date("F"); ?></option>
                <option value="01">JANUARY</option>
               <option value="02">FEBRUARY</option>
               <option value="03">MARCH</option>
               <option value="04">APRIL</option>
               <option value="05">MAY</option>
               <option value="06">JUNE</option>
               <option value="07">JULY</option>
               <option value="08">AUGUST</option>
               <option value="09">SEPTEMBER</option>
               <option value="10">OCTOBER</option>
               <option value="11">NOVEMBER</option>
               <option value="12">DECEMBER</option>
               
               
           </select>
           
       </div>
       </div>
       
       
       <div class="col-md-2">
       <div class="form-group">
           <label>Year:</label>
           <?php $thisyear="2018"; ?> 
           
           
           <select name="year"  class="form-control">
               
               
           <option value="<?php echo date("Y"); ?>"><?php echo date("Y"); ?></option>
               
               <?php for($i=-1;$i<30; $i++): ?>
               
              <option value="<?php echo $thisyear+$i ?>"><?php echo $thisyear+$i ?></option>
              
              <?php endfor ?>
               
               
           </select>
           
       </div>
       </div>
       
      
       <div class="col-md-3">
    
           
           <button class="btn btn-success" type="submit" style="margin-top:1.7em;">Search</button>
           
           </div>

</form>
</div>
</div>
<div class="col-md-12"></div>
<div class="box-body" id="content" style="max-height:650px; width:100%; overflow:auto;">

      <table class="table table-striped">
       <thead>
          <th>#</th>
          <th>District</th>
          <th>Health Unit</th>
          <th></th>
          </thead>
     <?php  
      $no=1;
      
      foreach($schedules as $schedule){
        echo '<tr>';
        echo '<td>'.$no++.'</td>';
        echo  '<td>'.$schedule->district.'</td>';
        echo '<td>'.$schedule->facility.'</td>';
        echo '</tr>';
           
      }?>
      
    </table>
      

</div>
</div>

</div>

</div>
     
   
   
   
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
      
    

$('#altered').submit(function(e){

  e.preventDefault();

  var data=$(this).serialize();
  var url='<?php echo base_url(); ?>admin/scheduled'
  
  $alert="<font color='blue'>Fetching Data, Please Wait....</font>";
  
  $('.suc').html($alert).fadeIn('slow');

  $.ajax({url:url,
method:"post",
data:data,
success:function(res){
    
    console.log(res);
    
  $('.suc').fadeOut('slow');
    
$('#content').html(res).fadeIn('slow');


}//success

}); // ajax



});//form submit






  });//doc
  



</script>
