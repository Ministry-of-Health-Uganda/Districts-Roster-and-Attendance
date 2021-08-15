   

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
  <div class="panel-heading"><h4><?php echo $title; ?></h4>
  <span class="suc"></span></div>

       
       <form method="post" id="scheduled_form" class="form-horizontal">
       
    <div class="col-md-4"
       <div class="form-group">
           <label>District</label>
           
           <select name="district">
               
               
           </select>
           
       </div>
       
       
       <div class="col-md-3">
       <div class="form-group">
           <label>Month</label>
           
           <select name="month">
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
           <label>Year</label>
           <?php $thisyear="2018"; ?> 
           
           <select name="year">
               
               <?php for($i=0;$i<30; $i++): ?>
               
              <option value="<?php echo $thisyear+$i ?>"><?php echo $thisyear+$i ?></option>
              
              <?php endfor ?>
               
               
           </select>
           
       </div>
       </div>
       
      
       <div class="col-md-3">
       <div class="form-group">
           
           <button class="btn btn-success" type="submit">Search</button>
           
           </div>
           </div>

</form>


<div class="panel-body" id="content">

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


$('#schedule_form').submit(function(e){

  e.preventDefault();

  var data=$(this).serialize();
  var url='<?php echo base_url(); ?>admin/scheduled_report'
  
  console.log(data);

  $.ajax({url:url,
method:"post",
data:data,
success:function(res){
    
    console.log(res);
    
  
    
$('#content').html(res).fadeIn('slow');


}//success

}); // ajax



});//form submit






  });//doc
  



</script>