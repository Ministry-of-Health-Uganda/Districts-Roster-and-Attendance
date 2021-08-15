
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>

<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url(); ?>assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<script src="<?php echo base_url();?>assets/bower_components/moment/moment.js"></script>

<script src="<?php echo base_url(); ?>assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>


<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>



<!--script type="text/javascript" src="<?php echo base_url(); ?>assets/bower_components/jquery-slimscroll/libs/prettify/prettify.js"></script-->
<!-- Slimscroll -->
<script src="<?php echo base_url(); ?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>

<!-- FastClick -->
<script src="<?php echo base_url(); ?>assets/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js"></script>

<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url(); ?>assets/dist/js/demo.js"></script>

<script src="<?php echo base_url(); ?>assets/js/view/user_list.js"></script>

<script src="<?php echo base_url(); ?>assets/js/notify.min.js"></script>



  <script src="<?php echo base_url();?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
  
    <script src="<?php echo base_url();?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script>
    
    <script src="<?php echo base_url();?>assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>




<script type="text/javascript">

	$(document).ready(function(){
	    
	    //check whether user already changed their password
	    
	    var changed=<?php echo $pass_changed; ?>;
	    
	    if(changed!==1){
	        
	        $('.passchange').click();
	        
	    }
	    
	    
	    
	    

$('.fc-view').slimScroll({
        height: '250px'
    });



	   $('.thistbl').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": true,
		  "responsive": true,
		  "iDisplayLength": 20,
    	  "aLengthMenu": [[10, 20, 30, 50, 100, -1], [10, 20, 30, 50, 100, "All"]]
        });	

$('.wrapper').removeAttr('style');


	});
	
setInterval(function(){

$.ajax({
//	url:'https://jsonplaceholder.typicode.com',
//	method:'get',
//	timeout:1000,
//	error: 
	function(){
		
		$('.on_off').html('<i class="fa fa-circle text-danger"></i> Offline');
		
		},
	success:function(res){
		
		
		if(res)
		{

			//console.log(res);

		$('.on_off').html('<i class="fa fa-circle text-success"></i> Online');

	}

	}

});

},1000);



//changing Password

function checker(){
    $first=$('#new').val();
    $confirm=$('#confirm').val();
    
    if(($first!==$confirm) && $first!==""){
        
        $('.error').html('<font color="red">Passwords Do not Match</font>');
    }
    
    else{
        
        $('.error').html('<font color="green">Passwords Match</font>');
    }
    
}//checker


$('#change_form').submit(function(e){

  e.preventDefault();

  var data=$(this).serialize();
  var url='<?php echo base_url(); ?>authentication/change_password'


console.log(data);

  $.ajax({url:url,
method:"post",
data:data,
success:function(res){

  if(res=="OK"){
      
     $('.changed').html("<center><font color='green'>Password change effective</font></center>");
  }
  
  else{
      
      $('.changed').html("<center>"+res+"</center>");
  }



}//success

}); // ajax



});//form submit










</script>






</body>
</html>
