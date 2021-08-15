
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




    <!-- Main content -->
        <section class="content">
      <!-- Small boxes (Stat box) -->
	<div class="panel">

		<div class="panel-body">
             <div class="row">





		   <div class="col-md-8" style="padding-bottom:0.5em;">
		    <form class="form-horizontal" style="padding-bottom: 2em;" action="<?php echo base_url(); ?>areports/fetch_report" method="post">
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

<option><?php echo $year; ?></option>

<?php for($i=-5;$i<=25;$i++){  ?>

<option><?php echo 2017+$i; ?></option>

<?php }  ?>


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
if(count($duties)>0)
{



?>
			<a href="<?php echo base_url() ?>areports/print_report/<?php echo $year."-".$month; ?>/<?php echo $this->session->userdata('facility'); ?>" style="display:none;" class="done btn btn-success" target="_blank" ><i class="fa fa-print"></i>Print</a>


<?php   } ?>
        
				</div>

</div>

			</form>
			</div>

			<div class="col-md-4">





	</div>



<div class="col-md-12">



<?php

//print_r($duties);   //carries report data

//print_r($matches);  //carries person's day's duty letter


?>




  	<div class="col-md-3">
	    <img src="<?php echo base_url(); ?>assets/images/MOH.png" width="100px"></div>

	<div class="col-md-9" style="border-right: 0; border-left: 0; border-top: 0;">
		<h4>
<?php
if(count($duties)<1)
{

echo "<font color='red'> No Schedule Data</font>";
}
else{

?>
	MONTLY DUTY ROTA FOR HEALTH PERSONNEL

		<?php
		 echo " - ".$duties[0]['facility']."<br>";

		 $dates=$year."-".$month;

		echo "              ".date('F, Y',strtotime($dates));

		?>

<?php } ?>

	</h4></div>

<div id="table">

<div class="header-row tbrow">
    <span class="cell tbprimary"># <b id="name"></b></span>
    <span class="cell">Name</span>
    <span class="cell">Position</span>

	<?php

$incomplete=0; //checks whether scheduling for this month has been fully done

	$monthdays = cal_days_in_month(CAL_GREGORIAN, $month, $year); // get days in a month

	for($i=1;$i<($monthdays+1);$i++)
	{
		?>

<span class="cell" style="padding:0px; text-align: center; border: 1px solid;"><?php echo $i; ?></span>

	<?php }?>

  </div>




<?php

$no=0;

foreach($duties as $singleduty) {

	$no++;

	?>

<div class="table-row tbrow">
    <input type="radio" name="expand" class="fa fa-angle-double-down">
	<span class="cell tbprimary" data-label="#"><?php echo $no;?>. <b id="name"><a href=""><?php echo $singleduty['fullname'];?></a></b>
</span>
<span class="cell" data-label="NAME" ><a href=""><?php echo $singleduty['fullname'];?></a></span>
	<span class="cell" data-label="POSITION" ><?php $words=explode(" ",$singleduty['job']);

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

		echo $matches[$singleduty[$day].$singleduty['ihris_pid']]; //$matches['key'=>value] e.g $matches[2017-11-01person|005=>N] and pulling out for N
	}

	else{

    //some day wasn't scheduled

    $incomplete +=1;


}

	;?></span>


<?php } ?>




</div>

<?php } ?>






</div>


</div>
</div>




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


var url=window.location.href;

if(url=='<?php echo base_url(); ?>rosta/fetch_report'){


	$('.sidebar-mini').addClass('sidebar-collapse');
}



</script>
