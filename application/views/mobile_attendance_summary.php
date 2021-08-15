
<?php
include_once("includes/mobile_css.php");
include_once("includes/responsive_table.php");

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



    <!-- Main content -->
        <section class="content">
      <!-- Small boxes (Stat box) -->
             <div class="row">





                		   <div class="col-md-8" style="padding-bottom:0.5em;">
		    <form class="form-horizontal" style="padding-bottom: 2em;" action="<?php echo base_url(); ?>areports/attendance_summary" method="post">
				<div class="col-md-3">

				<div class="control-group">

					<input type="hidden" id="month" value="<?php echo $month; ?>">

				<select style="margin-bottom:5px;" class="form-control" name="month" onchange="this.form.submit()" >

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

<option><?php echo date('Y'); ?></option>
<?php for($i=-10; $i<30; $i++){ ?>
<option><?php echo date('Y')+$i; ?></option>

<?php } ?>


				</select>

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
			    
			    //print_r($sums);
if(count($sums)>0)
{



?>
			<a href="<?php echo base_url() ?>areports/print_attsummary/<?php echo $year."-".$month; ?>/<?php echo $this->session->userdata('facility'); ?>" style="" class="print btn btn-success" target="_blank"><i class="fa fa-print"></i>Print</a>


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
			<a href="<?php echo base_url(); ?>areports/attCsv/<?php echo $year."-".$month; ?>/<?php echo $this->session->userdata('facility'); ?>" style="margin-top:1em; margin-left:1em;" class="btn btn-success btn-sm"><i class="fa fa-file"></i> Export CSV</a>


<?php } ?>
		</div>
		<div class="panel-body">

<?php

//print_r($sums);   //raw data

?>



<div class="col-md-3" style="border-right: 0; border-left: 0; border-top: 0;"><img src="<?php echo base_url(); ?>assets/images/MOH.png" width="100px"></div>

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

	</h4></div>


<div id="table">

<div class="header-row tbrow">
    <span class="cell tbprimary cnumber"># <b id="name"></b></span>
    <span class="cell  cname">Name</span>
    <span class="cell">Present</span>
	<span class="cell">Off Duty</span>
	<span class="cell">Official Request</span>
	<span class="cell">Leave</span>



</div>


<?php



foreach($sums as $sum) {?>

<div class="table-row tbrow">
    <input type="radio" name="expand" class="fa fa-angle-double-down trigger">
    <span class="cell tbprimary" style="cursor:pointer;" data-label="#"><?php echo $no;?>
	<b id="name">. &nbsp;<span onclick="$('.trigger').click();"><?php echo $sum['person'];?></span></b>
</span>
    <span class="cell cname" data-label="Name"><?php echo $sum['person'];?></span>
    <span class="cell" data-label="P"><?php echo $sum['P'];?></span>
    <span class="cell" data-label="O"><?php echo $sum['O'];?></span>
	<span class="cell" data-label="R"><?php echo $sum['R'];?></span>
	<span class="cell" data-label="L"><?php echo $sum['L'];?></span>





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
