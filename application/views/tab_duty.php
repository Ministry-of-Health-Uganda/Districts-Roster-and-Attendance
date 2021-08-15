
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

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
 

    <!-- Main content -->
        <section class="content">
      <!-- Small boxes (Stat box) -->
             <div class="row">






<div class="col-md-12">

	<div class="panel">
        
		<div class="panel-heading">
			<span id="warn" style="">
			<p class="lead">Legend</p>
			<p class="legend" style="margin:4px;">
			    
			    <?php foreach($schedules as $schedule): ?>
			
			<b class="ltab"><?php echo $schedule['letter']."=".$schedule['schedule']; ?></b> 
			
			<?php endforeach; ?>
			</p>
			</span>
			<hr>

			<form class="form-horizontal" style="padding-bottom: 2em;" action="<?php echo base_url(); ?>rosta/tabular" method="post">
				<div class="col-md-3">

				<div class="control-group">

					<input type="hidden" id="month" value="<?php echo $month; ?>">

				<select class="form-control" name="month"  onchange="this.form.submit()">

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
		<div class="panel-body">

<?php 

//print_r($duties);   //carries report data

//print_r($matches);  //carries person's day's duty letter


?>


               
<div id="table">   

<div class="header-row tbrow">
    <span class="cell tbprimary"># <b id="name"></b></span>
    <span class="cell name">Name</span>
     <span class="cell">Position</span>


	<?php 

	$monthdays = cal_days_in_month(CAL_GREGORIAN, $month, $year); // get days in a month

	for($i=1;$i<($monthdays+1);$i++)
	{
		?>

	<span class="cell" style="padding:0px; text-align: center; border: 1px solid;"><?php echo $i; ?></span>
	
	<?php }?>

</div>





	
<?php 

// if beyond tenth disable editing or for other month for non system admins
$today=date("d");

$thisyear=date("Y");

$thismonth=date("m");
$nextmonth=date("m")+1;

if($nextmonth==13){
    $nextmonth=01;
}

else{
    
    $nextmonth=$nextmonth;
}

// or  or ($today>$startdate  && $month==$thismonth)


if((($today<$startdate && $today>$deadline && $month<$nextmonth or $month>$nextmonth or $month<$thismonth) or ( (($month==$thismonth or $month>$nextmonth) and ($today<$startdate && $today>$deadline))  or $year>=$thisyear+1 or $year<$thisyear) or ($today>$startdate && $month==$thismonth))  && $_SESSION['role']!=='sadmin'){
    
    
    
  
    //deadline is from system vars
    
   // $state="disabled";
}
else{
    
    //$state="";
    
}

$no=0;

foreach($duties as $singleduty) { 

	$no++;

	?>


<div class="table-row tbrow">
    <input type="radio" name="expand" class="fa fa-angle-double-down trigger">
	<span class="cell tbprimary" style="cursor:pointer;" data-label="#"><?php echo $no;?>
	<b id="name">. &nbsp;<span onclick="$('.trigger').click();"><?php echo $singleduty['fullname'];?></span></b>
</span>
<span class="cell  name" data-label="Name" ><?php echo $singleduty['fullname'];?></span>
	<span class="cell" data-label="Position" ><?php $words=explode(" ",$singleduty['job']);

	$letters="";

	foreach ($words as $word) {

		$letters.=$word[0];
	}

	echo $letters;

	?></span>
	
	


	<?php 

	

	for($i=1;$i<($monthdays+1);$i++)
	{
		?>

	<span class="cell" data-label="Day<?php echo $i; ?>" >
		<?php if(!empty($singleduty['day'.$i]))
	{

		$d=$i;

if($d<10){

	$d="0".$d;
}



		?>
		

<input type="text" style="padding:0px; text-align: center;" class="update duty" id="<?php echo $year."-".$month."-".$d.$singleduty['ihris_pid']; ?>"  day="<?php echo $i; ?>" pid="<?php echo $singleduty['ihris_pid']; ?>"
pattern="[A-Za-z]+" size="1px" maxlength="1" title="Letters only for Duty" value="<?php echo $matches[$singleduty['day'.$i].$singleduty['ihris_pid']]; ?>" <?php echo $state; ?>>

	
<?php }

	else{

		?>
<input type="text" size="1px"  class="new duty" id="<?php echo $singleduty['ihris_pid']; ?>"  day="<?php echo $i; ?>" style="padding:0px; text-align: center;"  pattern="[A-Za-z]+" maxlength="1" pid="<?php echo $singleduty['ihris_pid']; ?>" name='day<?php echo $no; ?>' title="Letters only for Duty" <?php echo $state; ?>>

<?php 
	}

	;?>
		

	</span>

	<?php } // end for , one that loops tds ?>


	

</div>

<?php } ?>



</div>



<?php  if($state!="" && $_SESSION['role']!=="sadmin")
{

echo "<center><h4><font color='red'>  Editing is locked , please contact the Admin</font></h4></center>";
}

?>


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

if(url=='<?php echo base_url(); ?>rosta/tabular'){


	$('.sidebar-mini').addClass('sidebar-collapse');
}


$('.new').keyup(function(event){


	if (event.keyCode == 13) {
        textboxes = $("input.duty");
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

var letter=$(this).val(); //input letter

if(letter!==""){


var hpid=$(this).attr('id');// person_id
var day=$(this).attr('day');//day date e.g 3rd; 3

var schedules=<?php echo json_encode($tab_schedules); ?>;





//check if letter is a valid schedule

letter=letter.replace(/\s/g, '');//remove spaces

letter=letter.toUpperCase();//converte to upper case

var duty=schedules["'"+letter+"'"];  // get the schedule id


if( typeof duty=="undefined"){  // if letter is not defined as shift lettter

	

	$.notify("Warning: That letter doesn't represent any schedule", "warn");

	$(this).val('');



}

else{




var color=pickColor(duty);  // get corresponding color for calendar rota


if(day<10){

	day="0"+day;
}

var month=$('#month').val();
var year=$('#year').val();

var start=year+"-"+month+"-"+day;  //full duty date

var entry=start+hpid;//entry id


var startDate = new Date(start);

// seconds * minutes * hours * milliseconds = 1 day 
var day = 60 * 60 * 24 * 1000;

var end = new Date(startDate.getTime() + day);

end="";


   $(this).val(letter);



            $.post('<?php echo base_url(); ?>rosta/addEvent', {
                hpid: hpid,
                duty: duty,
                color: color,
                start: start,
                end:end
            }, function(result){
              

                 console.log(result);


$(this).prop('id',entry);
$(this).addClass('update');
$(this).removeClass('new');

$.notify("Scheduled Saved", "success");



                 })


        }// else for letter undefined

    }//if letter is not empty

}//end if not enter key

        })



$('.update').keyup(function(event){

		if (event.keyCode == 13) {
        textboxes = $("input.duty");
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


var letter=$(this).val(); //input letter

if(letter!==""){


var id=$(this).attr('id');// entry_id
var hpid=$(this).attr('pid');// person_id


var schedules=<?php echo json_encode($tab_schedules); ?>;






//check if letter is a valid schedule

letter=letter.replace(/\s/g, '');//remove spaces

letter=letter.toUpperCase();//converte to upper case

var duty=schedules["'"+letter+"'"];  // get the schedule id



if( typeof duty=="undefined"){  // if letter is not defined as shift lettter

$.notify("Warning: That letter doesn't represent any schedule", "warn");

	$(this).val('');

}

else{




var color=pickColor(duty);  // get corresponding color for calendar rota



   $(this).val(letter);
          

            $.post('<?php echo base_url(); ?>rosta/updateEvent', {
                id:id,
                hpid: hpid,
                duty:duty,
                color: color
            }, function(result){
                console.log(result);

                $.notify("Update Finished", "info");
              
                
            });//end post


        }// else for letter undefined


    }//if letter is not empty

}//if not enter key

        })


//color picking function

function pickColor(duty){


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

return kala;
}



</script>
