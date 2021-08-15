<style>
    .cell{
        min-width:20px;
    }
 
</style>
<?php 
include_once("includes/head.php");
include_once("includes/topbar.php");
include_once("includes/sidenav.php");
//include_once("");
$departs="";  //to store department options

foreach ($departments as $department) {
	
	if(!empty($department->department))
	{
	$departs.="<option value'".$department->department."''>".$department->department."</option>";
   }
}


?>

<style>

  
    @media only screen and (max-width: 980px)  {
    .field{
        
        height:2em;
        width:5em;
        margin-top:1em;
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
                 
                 
                 
                 

			
			<div class="col-md-4">
			    
			 
			    
			    <?php
			    
			    
			    	function dayState($day,$scheduled) {
			    	    
			    	    $user=$_SESSION['role'];
			    	    $thismonth=date('m');
//its today or day in the past

		if(strtotime($day) < strtotime(date('Y-m-d'))  && !empty($scheduled) && $user!=='sadmin'){
																		
					$state="disabled";


				}
				
	else if(strtotime($day) < strtotime(date('Y-m-d')) && empty($scheduled) && $user!=='sadmin'){
				    
				    $state="";
				}

				//if they are scheduled to work

		if(strtotime($day) > strtotime(date('Y-m-d')) && $user!='sadmin'){
																		
					$state="disabled";
				}


				echo $state;

			}//color
			
			    
			    
			    
			    
if(count($duties)>0)
{



?>
		


<?php } ?>
			    
		
	</div>






<div class="col-md-12">

	<div class="panel" style="overflow:auto;">

		<div class="panel-header">

	</div>
		<div class="panel-body">
		    	
			<span  style="margin-left:0.6em;">
			<b class="" style="font-weight:bold;font:1.2em;">Legend</b>
			<p class="legend" style="margin:4px;">
		
			<b class="ltab">P=Present </b> 
			<b class="ltab"> O=Off Duty </b>
			<b class="ltab"> R=Official Request </b>
			<b class="ltab"> L=Leave </b>
			<b class="ltab"> X=Absent </b>
		
			</p>
			</span>
			<hr>
			                 		   <div class="col-md-8" style="padding-bottom:0.5em;">
		    <form class="form-horizontal" style="padding-bottom: 2em;" action="<?php echo base_url(); ?>rosta/actuals" method="post">
				<div class="col-md-3">

				<div class="control-group">

					<input type="hidden" id="month" value="<?php echo $month; ?>">

				<select class="form-control" name="month" onchange="this.form.submit()" >

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

				<select class="form-control" name="year" onchange="this.form.submit()">
	<option><?php echo $year; ?></option>

<?php for($i=-5;$i<=25;$i++){  ?>

<option><?php echo 2017+$i; ?></option>

<?php }  ?>

				</select>

				</div>
				
				

</div>

<div class="col-md-3">

				<div class="control-group">

						
						<input type="hidden" id="department"  value="<?php echo $depart; ?>">

				<select class="form-control" name="department" onchange="this.form.submit()">
					
				
				<?php if($depart) { ?>
				<option><?php echo $depart; ?></option> 
				<?php } ?>
				<option value="">All</option>

				     <?php echo $departs ?>




				</select>
				</div>

</div>


			<div class="col-md-3">

				<div class="control-group">

		<input type="submit" name="" value="Load Month" class="btn btn-success">

				</div>

</div>

			</form>
			</div>

<?php 

//print_r($duties);   //carries report data

//print_r($matches);  //carries person's day's duty letter


?>
      
	<div class="col-md-3">
 <img src="<?php echo base_url(); ?>assets/images/MOH.png" width="100px">
 </div>

<div class="col-md-9" style="border-right: 0; border-left: 0; border-top: 0;">
		<h4>
<?php 
if(count($duties)<1)
{

echo "<font color='red'> No Schedule Data</font>";
}
else{

?>
	MONTHLY ATTENDANCE FORM FOR HEALTH PERSONNEL

		<?php
		 echo " - ".$duties[0]['facility']."<br>"; 

		echo "              ".date('F, Y',strtotime($year."-".$month));


//print_r($actuals);

		?>

<?php } ?>

	</h4></div>

<div id="table">   

<div class="header-row tbrow">
    <span class="cell tbprimary"><b id="name"></b>#</span>
    <span class="cell" style="width:30%;"  >Name</span>
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
    
     <input type="radio" name="expand" class="fa fa-angle-double-down trigger">
	<span class="cell tbprimary" style="cursor:pointer;" data-label=""><?php echo $no;?>
	<b id="name">. &nbsp;<span onclick="$('.trigger').click();"><?php echo $singleduty['fullname'];?></span></b>
</span>
   <span class="cell" data-label="Name" style="width:auto; text-align:left; padding-left:1em;" ><?php echo $singleduty['fullname'];?></span>
	<span class="cell" data-label="Position" >
	
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

<span class="cell" data-label="Day<?php echo $i; ?>" >

		<?php if($singleduty[$day]!='')
	{

		$d=$i;

if($d<10){

	$d="0".$d;
}
		

		$dayentry=$singleduty[$day].$singleduty['ihris_pid'];  //entry id

		$ddate=trim(date("Y-m")."-".$d);

//

				?>
				
				

		<input type="text" style="text-transform:uppercase; padding:0px; text-align: center;" class="actual field" did="<?php echo $ddate; ?>"  day="<?php echo $i; ?>" maxlength="1" size="1px" title="P,O,R,X and L only" 
		value="<?php echo $actuals[$dayentry]; ?>"
		pid="<?php echo $singleduty['ihris_pid']; ?>" 
		<?php dayState($ddate,$actuals[$dayentry]); ?> >





	<?php }
	
	
	else{
	    
	  
	   echo "";
	    
	    
	    
	}  ?>
	
	</span>
	
	<?php
	
	
	}//repeat days 
	?>

	

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
include_once("includes/footermain.php");
include_once("includes/rightsidebar.php");
include_once("includes/footer.php");



?>

<script type="text/javascript">
	

var url=window.location.href;

if(url=='<?php echo base_url(); ?>rosta/actuals'){


	$('.sidebar-mini').addClass('sidebar-collapse');
}



$('.actual').keyup(function(event){


	if (event.keyCode == 13) {
        textboxes = $("input.actual");
        currentBoxNumber = textboxes.index(this);
        if (textboxes[currentBoxNumber + 1] != null) {
            nextBox = textboxes[currentBoxNumber + 1];
            nextBox.focus();
            nextBox.select();
           
        }

 event.preventDefault();

            return false;
  
       
    } //if enter key is pressed



    else{ //if not enter key


 var hpid=$(this).attr('pid');
 var date=$(this).attr('did');

var letter=$(this).val(); //input letter

if(letter!==""){
//check if letter is a valid schedule

letter=letter.replace(/\s/g, '');//remove spaces

letter=letter.toUpperCase();//converte to upper case

if(letter!=="P" && letter!=="R" && letter!=="O" && letter!=="L" && letter!=="X" ){  // if letter is not defined as atual tracker

	

	$.notify("Warning: Letter not recognised ", "warn");

	$(this).val('');
}//letter!==p


else{

	console.log(hpid+date);

            $.post('<?php echo base_url(); ?>rosta/saveActual', {
                hpid: hpid,
                date: date,
                duty:letter
            },
                 function(result){
              

                 console.log(result);

                 $(this).val(letter);



$.notify("Data Saved", "success");



}
); //$.post

}//else for letter!==P

}//letter !=""


    


}//end if not enter key



        })





</script>
