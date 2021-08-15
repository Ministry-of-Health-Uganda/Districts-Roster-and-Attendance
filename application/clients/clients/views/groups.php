   

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
       <div class="nav-tabs-custom">
             <ul class="nav nav-tabs">
			      <li class="active"><a href="<?php echo base_url()?>admin/user_list">User Groups</a></li>
			      <!--li class=""><a href="#">User Logs</a></li-->
				  
                 </ul>
		  </div>
             <div class="row">

<div class="col-md-6">

  <div class="panel">
  <div class="panel-heading"><h4>SELECT GROUP</h4> <span class="suc">
      
      <a href="" class="btn btn-info btn-sm" data-toggle="modal" data-target="#newgrp">Create a group</a>
      
      <?php echo $this->session->flashdata("msg"); ?>
      
  </span></div>

   <div class="panel-body">

<form id="group_form">
  
<div class="form-group">

  <div class="input-group">

<select  class="form-control" name="group" style="min-width:300px; text-transform:capitalize;" >
<?php
$groups=$this->aauth->list_groups();  

foreach($groups as $group){  ?>

<option value="<?php echo $group->id; ?>"><?php echo $group->name; ?></option>

<?php } ?>
</select>
</div>

</div>

<table border="0" class="table">
    <tr>
    <td>View staff</td><td><input style="display: block; "  name="permissions[]" value="viewstaff" type="checkbox"></td>
    <td>Make rota</td><td><input style="display: block; " name="permissions[]" value="makerota" type="checkbox"></td>
    </tr>
     <tr>
    <td>Manage users</td><td><input style="display: block; "  name="permissions[]"  value="manageusers" type="checkbox"></td>
    <td>Manage schedules</td><td><input style="display: block; "  name="permissions[]"  value="manageschedule" type="checkbox"></td>
    </tr>
     <tr>
   <td>View reports</td> <td><input style="display: block; "  name="permissions[]" value="viewreports" type="checkbox"></td>
    <td>Download reports</td><td><input style="display: block; "  name="permissions[]" value="downloadreports" type="checkbox"></td>
    </tr>
    
    
</table>



<div class="form-group">

  <div class="input-group">

<input type="submit" class="btn btn-success" value="Save Group">
  

</div>

</div>








</form>

</div>
</div>


</div>



<div class="col-md-6">
   <!-- general form elements -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $title; ?></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
           
              <div class="box-body">
 

<table class="table">

<?php 


foreach($groups as $group){  ?>

<tr><td><?php echo ucwords($group->name); ?></td>
<td>

<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal<?php echo $group->id; ?>">Permissions</button></td>
</tr>



<!-- Modal -->
<div id="myModal<?php echo $group->id; ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Permissions for <?php echo ucwords($group->name); ?></h4>
      </div>
      <div class="modal-body" style="padding-left:3em;">
       <?php  $perms=$this->aauth->list_perms($group->id);
       
       foreach($perms as $perm){
           
           if($this->aauth->is_group_allowed($perm->id,$group->id))
           
           echo "<li>".ucwords($perm->name)."</li>";
           
       }
       
       ?>
       
       
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>




<?php } ?>

</table>


            
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                
              </div>
         


          </div>
          <!-- /.box -->

        </div><!--col-md-12-->





<!-- new grp Modal -->
<div id="newgrp" class="modal fade" role="dialog">
  <div class="modal-dialog">
<form method="post" action="<?php echo base_url('admin/addgrp'); ?>">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add group</h4>
      </div>
      <div class="modal-body" style="padding-left:3em;">
    
    <div class="form-group">
        <input type="text" placeholder="Group Name" name="group" class="form-control">
    </div>
      
      </div>
      <div class="modal-footer">
          <button type="submit" class="btn btn-default" >Save Group</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>

  </div>
</div>




<?php print_r($newArray) ; ?>







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

  $(document).ready(function(){

$('#scheduletbl').slimscroll({
  height: '400px',
  size: '5px'
});


$('.timepicker').timepicker({showInputs:false});




$('#group_form').submit(function(e){

  e.preventDefault();

  var data=$(this).serialize();
  var url='<?php echo base_url(); ?>admin/groupAllow'
  
  /*
   var allperms = [];

     $('input[type="checkbox"]:checked').each(function () {
     
       allperms.push($(this).val());
     });
     
   
     
     var group=  $('input[name="group"]').val();
    
    
      alert(allperms+group);
      */
      

  $.ajax({url:url,
method:"post",
data:data,
success:function(res){

  if(res=="OK"){
      
     $('.suc').html("<center><font color='green'>Group Permissions configured</font></center>");
  }
  
  else{
      
      $('.suc').html("<center><font color='red'>Error Occured, Failed</font></center>");
  }



}//success

}); // ajax



});//form submit





$('.user_edit').submit(function(e){

  e.preventDefault();

 var fac=$('#facility').val();

 if(fac==''){

$('.suc2').html("<center><font color='red'> Select New Facility</font></center>");

 }

 else

 {

  var data=$(this).serialize();
  var url='<?php echo base_url(); ?>admin/edit_user'

  $.ajax({url:url,
method:"post",
data:data,
dataType:'json',
success:function(res){

  console.log(res.status);

  if(res.status==="success")
  {

  $('.suc2').html("<center><font color='green'>User Details Updated</font></center>");

  $('#reset2').click();

}
else  if(res.status==="fail"){

$('.suc2').html("<center><font color='red'> Update Failed</font></center>");

}

}//success

}); // ajax


}//check


});//form submit


$('.block').click(function(e){

  e.preventDefault();

  var id=$(this).attr('id');
  var url='<?php echo base_url(); ?>admin/deactivate_user/'+id;

  console.log(id);
console.log(url);

  $.ajax({url:url,
success:function(res){

  console.log(res);

  $(".suc").html("<font color='green'>"+res+"</font>");


 setTimeout(function(){

    window.location.reload();


  },3000);


}//success

}); // ajax



});//btn click




//activate

$('.activate').click(function(e){

  e.preventDefault();

  var id=$(this).attr('id');
  var url='<?php echo base_url(); ?>admin/activate_user/'+id;

  console.log(id);
console.log(url);

  $.ajax({url:url,
success:function(res){

  console.log(res);

  $(".suc").html("<font color='green'>"+res+"</font>");

  setTimeout(function(){

    window.location.reload();


  },3000);



}//success

}); // ajax



});//btn click





  });//doc
  



</script>
