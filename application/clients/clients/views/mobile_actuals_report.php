
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


    <!-- Main content -->
        <section class="content">
      <!-- Small boxes (Stat box) -->
             <div class="row">

<div class="col-md-12">

	<div class="panel">


		<div class="panel-header">


		</div>
		<div class="panel-body">

			 <div class="col-md-8" style="padding-bottom:0.5em;">
		    <form class="form-horizontal" style="padding-bottom: 2em;" action="<?php echo base_url(); ?>areports/actualsreport" method="post">
				<div class="col-md-3">

				<div class="control-group">

					<input type="hidden" id="month" value="<?php echo $month; ?>">
			<select style="margin-bottom:5px;" class="form-control" name="month" onchange="this.form.submit()">

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
<option><?php echo date('Y')-1; ?></option>
<option><?php echo date('Y')+1; ?></option>

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

		<input  type="submit" name="" value="Load Month" class="btn btn-success">


    			    <?php
if(count($duties)>0)
{

  $incomplete=0;


?>
			<a href="<?php echo base_url() ?>areports/print_actuals/<?php echo $year."-".$month; ?>/<?php echo $this->session->userdata('facility'); ?>" style="" class="done btn btn-success" target="_blank"><i class="fa fa-print"></i>Print</a>


				</div>

</div>

			</form>
			</div>

			<div class="col-md-4">




<?php    } ?>


	</div>

<?php




?>

	<div class="col-md-3"  style="border-right: 0; border-left: 0; border-top: 0;"><img src="<?php echo base_url(); ?>assets/images/MOH.png" width="100px"></div>

	<div class="col-md-9"  style="border-right: 0; border-left: 0; border-top: 0;">
		<h4>
<?php
if(count($duties)<1)
{

echo "<font color='red'> No Schedule Data</font>";
}
else{

?>
	MONTHLY ATTENDANCE REPORT FOR HEALTH PERSONNEL

		<?php
		 echo " - ".$duties[0]['facility']."<br>";

		echo "              ".date('F, Y',strtotime($year."-".$month));


		//print_r($nonworkables);

		?>

<?php } ?>

	</h4></div>


<div id="table">

<div class="header-row tbrow">
    <span class="cell tbprimary"># <b id="name"></b></span>
    <span class="cell">Name</span>
	<span class="cell">Position</span>

	<?php

	$month=date('m');

	$year=date('Y');

	$monthdays = cal_days_in_month(CAL_GREGORIAN, $month, $year); // get days in a month

	for($i=1;$i<($monthdays+1);$i++)
	{
		?>

<span class="cell" style="padding:0px; text-align: center; border: 1px solid;"><?php echo $i; ?></span>

	<?php } ?>


</div>



<?php






$no=0;

//$nonworkables contains non duty days
//$workeddays contains  worked days

foreach($duties as $singleduty) {

	$no++;

	?>
<div class="table-row tbrow">
    <input type="radio" name="expand" class="fa fa-angle-double-down">
	<span class="cell tbprimary" data-label="#"><?php echo $no;?>. <b id="name"><a href=""><?php echo $singleduty['fullname'];?></a></b>
</span>
<span style="text-align:left; padding-left:1em;" class="cell" data-label="NAME" ><a href=""><?php echo $singleduty['fullname'];?></a></span>
<span class="cell" data-label="POSITION" >

	<?php $words=explode(" ",$singleduty['job']);

	$letters="";

	foreach ($words as $word) {

		$letters.=$word[0];
	}

	echo $letters;

	?></span>

<?php

$month_days=date('t');//days in a month

for($i=1;$i<=$month_days;$i++){// repeating td

$day="day".$i;  //changing day




?>

<span class="cell" data-label="<?php echo ucwords($day); ?>">

		<?php if($singleduty[$day]!='')
	{

		$d=$i;

if($d<10){

	$d="0".$d;
}



		$dayentry=$singleduty[$day].$singleduty['ihris_pid'];  //entry id

		$ddate=trim(date("Y-m")."-".$d);

//

 echo $actuals[$dayentry];

?>


	<?php }


else{

    //some day wasn't scheduled

     echo ""; //show nothing

    $incomplete +=1;



}

echo "</span>";



	}//repeat days ?>



</div>

<?php } ?>



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

	var checkdone= <?php echo $incomplete; ?>;

if(checkdone>0){

    $('.done').hide();
}
else{

   $('.done').show();
}




</script>
