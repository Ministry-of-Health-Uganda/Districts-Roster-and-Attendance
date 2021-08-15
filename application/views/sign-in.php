<?php

include('includes/head.php');
?>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->


<body class=" login-page">
<div class="login-box">
  <div class="login-logo">
    <a href=""><b>Attendance</b>Tracking</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>

    <small id="login-empty-input" class="error">email or password cannot be empty <br>&nbsp;</small>
                    <?php if($alert): ?>
                      <small id="login-invalid-input" class="error">invalid email or password<br>&nbsp;</small>
                    <?php endif; ?>

    <form method="post" onsubmit="return checkEmptyInput();" action="<?=base_url()?>authentication/login/">
      <div class="form-group has-feedback">
        <input id="email"  class="form-control" placeholder="Username" name="username" type="text" autofocus>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="password"  placeholder="Password" id="password" value="">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
      
        <!-- /.col -->
        <div class="col-xs-4">
         <button id="login-submit" type="submit" value="Login" class="btn btn-success btn-rounded">Sign in</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <!-- /.social-auth-links -->

    <a href="#" onclick="alert('Please contact the administrator to reset your password!')">I forgot my password</a><br>
  

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<?php

include('includes/footer.php')

?>

 <script>
      window.onload = hideLoginErrors();
      function hideLoginErrors(){
        $("#login-empty-input").hide();
      }

    function checkEmptyInput(){
    
      hideLoginErrors();
      
      $("#login-invalid-input").hide();
      if( $("#email").val() == '' || $("#password").val() == '' ){
        $("#login-empty-input").show();
        return false;
      }
    }
  </script>

