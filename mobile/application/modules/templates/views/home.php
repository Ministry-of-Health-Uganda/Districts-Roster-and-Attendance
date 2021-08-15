<?php

include('includes/css_files.php');
include('includes/collapse_styles.php');
include('includes/top.php');
include('includes/menu.php');

?>



        <!-- Main Content -->
          
  <!-- contact us -->
  <div class="pages section" id="content">

   <center class="spin" style="margin-top:-70px; margin-bottom:40px;">
   <i class="fa fa-spinner fa-spin" style="font-size:24px;"></i>
   </center>
   
    <div class="container" style="margin-bottom: 3em;">

      <!--content here-->

      <?php

         $this->load->view($module."/".$view);

      ?>


        </div> <!-- End of Main Contents -->
      </div>

<!-- loader -->
  <div id="fakeLoader"></div>
  <!-- end loader -->
  

  
  <!-- scripts -->
  <script src="<?php echo base_url(); ?>assets/js/materialize.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/owl.carousel.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/fakeLoader.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/animatedModal.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/main.js"></script>
  



</body>

</html>