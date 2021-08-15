
<?php
include_once("includes/mobile_css.php");
include_once("responsive_table.php");

$departs="";  //to store department options

foreach ($departments as $department) {
	
	if(!empty($department->department))
	{
	$departs.="<option value'".$department->department."''>".$department->department."</option>";
   }
}



?>


<style>

.cnumber{
    
    width:3%;
}

.cname{
    text-align: left;
    padding-left:1.5em;
    width:30%;
    
}
    	
@media only screen and (max-width: 980px)  {
    
    .cnumber{
    
    width:100%;
}
    
    .cname{
    padding-left:0em;
    text-align: left;
    width:100%;
    
}

	    
    	}
    
    
</style>




  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
 

    <!-- Main content -->
        <section class="content">
      <!-- Small boxes (Stat box) -->
             <div class="row">





                		   <div class="col-md-8" style="padding-bottom:0.5em;">
		    <form class="form-horizontal" style="padding-bottom: 2em;" action="<?php echo base_url(); ?>areports/summary" method="post">
				<div class="col-md-3">

				<div class="control-group">

					<input type="hidden" id="month" value="<?php echo $month; ?>">

				<select style="margin-bottom:5px;" style="margin-bottom:5px;" class="form-control" name="month" onchange="this.form.submit()" >

					<option value="<?php echo $month; ?>"><?php echo strtoupper(date('F', mktime(0, 0, 0, $month, 10)))."(Showing below)"; ?></option>
					
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


			<div class="col-md-3">

				<div class="control-group">

						<input type="hidden" id="year" value="<?php echo $year; ?>">

				<select style="margin-bottom:5px;" class="form-control" name="year" onchange="this.form.submit()">
					
<option><?php echo $year; ?></option>

<?php for($i=-5;$i<=25;$i++){  ?>

<option><?php echo 2017+$i; ?></option>

<?php }  ?>				</select>

				</div>

</div>


<div class="col-md-3">

				<div class="control-group">

		
				<select style="margin-bottom:5px;" class="form-control" name="department" onchange="this.form.submit()" title="Department">
					
				<?php if($depart){  ?>
				<option><?php  echo $depart; ?></option>

		<?php	} ?>
				<option value="">All</option>

				     <?php echo $departs ?>


				</select>
				</div>

</div>

			<div class="col-md-3">

				<div class="btn-group">

		<input type="submit" name="" value="Load Month" class="btn btn-success">

        
        
			    <?php 
if(count($sums)>0)
{



?>
			<a href="<?php echo base_url() ?>areports/print_summary/<?php echo $year."-".$month; ?>/<?php echo $this->session->userdata('facility'); ?>" style="" class="print btn btn-success" target="_blank"><i class="fa fa-print"></i>Print</a>


<?php } ?>
			    
				</div>

</div>

			</form>
			</div>
			
			<div class="col-md-4">
			    
			 
			    
		
	</div>





<div class="col-md-12">

	<div class="panel">

		<div class="panel-header">

<?php 
if(count($sums)>0)
{



?>
			<a href="<?php echo base_url(); ?>areports/bundleCsv/<?php echo $year."-".$month; ?>/<?php echo $this->session->userdata('facility'); ?>" style="margin-top:1em; margin-left:1em;" class="btn btn-success"><i class="fa fa-file"></i> Export CSV</a>


<?php } ?>
		</div>
		<div class="panel-body">

<?php 

//print_r($sums);   //raw data

?>


               
<div class="col-md-3" style="border-right: 0; border-left: 0; border-top: 0;"><img src="<?php echo base_url(); ?>assets/images/MOH.png" width="100px">
</div>

<div class="col-md-9" style="border-right: 0; border-left: 0; border-top: 0;">
		<h4>
<?php 
if(count($sums)<1)
{

echo "<font color='red'> No Schedule Data</font>";
}
else{

?>
	MONTHLY DUTY ROTA SUMMARY

		<?php
		 echo " - ".$sums[0]['facility']."<br>"; 

		echo "              ".date('F, Y',strtotime($year."-".$month));

		?>

<?php } ?>

	</h4>
</div>
           


<div id="table">

<div class="header-row tbrow">
    <span class="cell tbprimary cnumber"># <b id="name"></b></span>
    <span class="cell  cname">Name</span>
	<span class="cell">Day</span>
	<span class="cell">Evening</span>
	<span class="cell">Night</span>
	<span class="cell">Off</span>
	<span class="cell">Annual</span>
	<span class="cell">Study</span>
	<span class="cell">Maternity</span>
   <span class="cell">Other</span>
	

</div>



<?php 

$no=1;

foreach($sums as $sum) {?>

<div class="table-row tbrow content">
    <input type="radio" name="expand" class="fa fa-angle-double-down trigger">
    <span class="cell tbprimary" style="cursor:pointer;" data-label="#"><?php echo $no;?>
	<b id="name">. &nbsp;<span onclick="$('.trigger').click();"><?php echo $sum['person'];?></span></b>
</span>
    <span class="cell cname" data-label="Name"><?php echo $sum['person'];?></span>

	
	<span class="cell" data-label="D"><?php echo $sum['D'];?></span>
	<span class="cell" data-label="E"><?php echo $sum['E'];?></span>
	<span class="cell" data-label="N"><?php echo $sum['N'];?></span>
	<span class="cell" data-label="O"><?php echo $sum['O'];?></span>
	<span class="cell" data-label="A"><?php echo $sum['A'];?></span>
	<span class="cell" data-label="S"><?php echo $sum['S'];?></span>
	<span class="cell" data-label="M"><?php echo $sum['M'];?></span>
	<span class="cell" data-label="Z"><?php echo $sum['Z'];?></span>



	

</div>

<?php

$no++; 

} ?>



</div>


</div></div>




</div><!--col 12-->

            </div>
  <!-- /.content-row -->
   </section>
    <!-- /.section-->
  </div>
  
  <!-- /.content-wrapper -->
 <?php 

include_once("includes/footer.php");

?>

<script type="text/javascript">
	


$('.csv').click(function(e){
    
    e.preventDefault();
    
    $.ajax({
        url:'<?php echo base_url(); ?>rosta/bundleCsv',
        success:function(res){
            
            console.log(res);
        }
    })
    
})

</script>
