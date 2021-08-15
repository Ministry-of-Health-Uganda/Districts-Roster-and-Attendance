<!-- menu -->
  <div class="menus" id="animatedModal2">
    <div class="close-animatedModal2 close-icon">
      <i class="fa fa-close"></i>
    </div>
    <div class="modal-content">
      <div class="container">
        <div class="row">

          
          <div class="col s6">
            <a href="<?=base_url()?>" onclick="$('.load').show();"  class="button-link">
              <div class="menu-link">
                <div class="icon">
                  <i class="fa fa-home"></i>
                </div>
                Home
              </div>
            </a>
          </div>


          <div class="col s6">
            <a href="<?=base_url()?>jobs/showEstablishments" onclick="$('.load').show();"  class="button-link">
              <div class="menu-link">
                <div class="icon">
                  <i class="fa fa-file"></i>
                </div>
                Audit Report
              </div>
            </a>
          </div>

        

        </div>

        <div class="row">

          <div class="col s12">
            <a href="<?=base_url()?>jobs/showAttendance" onclick="$('.load').show();"  class="button-link">
              <div class="menu-link">
                <div class="icon">
                  <i class="fa fa-table"></i>
                </div>
                Att Summary
              </div>
            </a>
          </div>


        </div>


         <div class="row load" style="display: none; padding-top: 1em;">

          <center>Loading...</center>

         </div>

    

      
   
      </div>
    </div>
  </div>
  <!-- end menu -->
