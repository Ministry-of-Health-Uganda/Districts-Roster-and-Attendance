
<?php 
include_once("includes/head.php");
include_once("includes/topbar.php");
include_once("includes/sidenav.php");
//include_once("");







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

.print{
    
    display:none;
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





            <div class="col-md-12" style="padding-bottom:0.5em;">
		    <form class="form-horizontal" style="padding-bottom: 2em;" action="<?php echo base_url(); ?>rosta/fullSummary" method="post">
				<div class="col-md-3">

				<div class="control-group">

					<input type="hidden" id="month" value="<?php echo $month; ?>">

				<select class="form-control selector" name="month" >

					<option value="<?php echo  $month; ?>"><?php echo  ucfirst(date('F', mktime(0, 0, 0, $month, 10)))."(Showing below)"; ?></option>
					
<option value="<?php echo "01";?>">JANUARY</option>
<option value="<?php echo  "02";?>">FEBRUARY</option>
<option value="<?php echo  "03";?>">MARCH</option>
<option value="<?php echo "04";?>">APRIL</option>
<option value="<?php echo "05";?>">MAY</option>
<option value="<?php echo "06";?>">JUNE</option>
<option value="<?php echo "07";?>">JULY</option>
<option value="<?php echo "08";?>">AUGUST</option>
<option value="<?php echo  "09";?>">SEPTEMBER</option>
<option value="<?php echo "10";?>">OCTOBER</option>
<option value="<?php echo "11";?>">NOVEMBER</option>
<option value="<?php echo "12";?>">DECEMBER</option>
				</select>

				</div>

</div>


			<div class="col-md-2">

				<div class="control-group">

						<input type="hidden" id="year" value="<?php echo $year; ?>">

				<select class="form-control selector" name="year" >
				<option><?php echo $year; ?></option>

<?php for($i=-5;$i<=25;$i++){  ?>

<option><?php echo 2017+$i; ?></option>

<?php }  ?>

				</select>

				</div>

</div>


	<div class="col-md-3">
<div class="form-group">

             <select class="form-control district2"  style="width: 100%;"  name="district" <?php echo $disabled; ?> >
                
                <?php if($thisdistrict){ ?>
                
                <option  selected value="<?php echo $thisdistrict; ?>"><?php echo Modules::run('districts/getDistrict',$thisdistrict); ?></option>
                
                <?php } else{ ?>
                
                <option  selected value="">---Select District---</option>
                
                <?php  } ?>
                
                 <option   value="">All Districts</option>

                <?php echo $districts_ops; ?>

              </select>
     </div>

</div>

		<div class="col-md-4">	
			<div class="form-group col-md-12">
    <select name="facilit" style="width: 100%;" class="form-control facility2">
        
        <?php if($currentfacility){ ?>
                
                <option  selected value="<?php echo $currentfacility; ?>"><?php echo Modules::run('districts/getDistrict',$currentfacility); ?></option>
                
                <?php } else{ ?>
                
                <option  selected value="0">---Select Facility---</option>
                
                <?php  } ?>
                
                <option   value="">All Facilities</option>

        <?php echo Modules::run('districts/getFacilities',$thisdistrict); ?>
        
    </select>

    </div>
    
    </div>


			<div class="col-md-2">

				<div class="control-group">

		<input type="submit" name="" value="Apply Filters" class="btn btn-success">

				</div>

</div>

			</div>

 <div class="col-md-12">
			
  <div class="col-md-6 col-xs-12">
			    
			 
			    
			    <?php 
if(count($sums)>0)
{

   
    $link="";

if($thisdistrict){
    
    $link.="/".$thisdistrict;
}
if($thisfacility){
    
    $link.="/".$currentfacility;
}



?>
			<a href="<?php echo base_url() ?>rosta/print_full_summary/<?php echo $year."-".$month.$link; ?>" style="" class="print btn btn-success btn-sm" target="_blank"><i class="fa fa-print"></i>Print</a>


<?php } ?>
			    
		

	    
	    
<?php 
if(count($sums)>0)
{
 
?>
<a href="<?php echo base_url(); ?>rosta/fullSumCsv/<?php echo $year."-".$month.$link; ?>" style=" margin-left:1em;" class="btn btn-success btn-sm"><i class="fa fa-file"></i> Export CSV</a>


<?php } ?>
	</div>


</div>

			</form>

<div class="col-md-12">

	<div class="panel">

		<div class="panel-header">

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
	MONTHLY COMBINED SUMMARY

		<?php
		
		if($thisdistrict){
		 echo  Modules::run("districts/getDistrict",$thisdistrict)."<br>"; 
		 
		}
		
		if($currentfacility){
		 echo Modules::run("districts/getFacility",$currentfacility)."<br>"; 
		 
		}

		echo "              ".date('F, Y',strtotime($year."-".$month));

		?>

<?php } 

?>

	</h4></div>


<div id="table">

<div class="header-row tbrow">
   
    <span class="cell  cname">Name</span>
    <span class="cell">DSD</span>
	<span class="cell">DSO</span>
	<span class="cell">DSL</span>
	<span class="cell">DSZ</span>

    <span class="cell">Present</span>
	<span class="cell">Off Duty</span>
	<span class="cell">Official Request</span>
	<span class="cell">Leave</span>

	

</div>


<?php 

$no=1;



foreach($sums as $sum) {?>

<div class="table-row tbrow">
    <input type="radio" name="expand" class="fa fa-angle-double-down trigger">
	<b id="name">. &nbsp;<span onclick="$('.trigger').click();"><?php echo $sum['person'];?></span></b>
</span>
    <span class="cell cname" data-label="Name"><?php echo $sum['person'];?></span>
    <span class="cell" data-label="P"><?php echo $sum['D']+$sum['E']+$sum['N'];?></span>
    <span class="cell" data-label="O"><?php echo $sum['OR'];?></span>
	<span class="cell" data-label="R"><?php echo $sum['M']+$sum['A']+$sum['S'];?></span>
	<span class="cell" data-label="L"><?php echo $sum['Z'];?></span>

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


<center><?php echo $links; ?></center>


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

if(url=='<?php echo base_url(); ?>rosta/fetch_report'){


	$('.sidebar-mini').addClass('sidebar-collapse');
}


$('.csv').click(function(e){
    
    e.preventDefault();
    
    $.ajax({
        url:'<?php echo base_url(); ?>rosta/bundleCsv',
        success:function(res){
            
            console.log(res);
        }
    })
    
})


  $('.district2').change(function(e){

    e.preventDefault();

var districtId=$(this).val();

//console.log(districtId);

var url="<?php echo base_url(); ?>districts/getFacilities/"+districtId;

$.ajax({
  url:url,
  success:function(response){

 $('.facility2').html(response);


    //console.log(response);
  }
});

});


 $('.district2').select2();
 $('.facility2').select2();
  $('.selector').select2();


</script>

