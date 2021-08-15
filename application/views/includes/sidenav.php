  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
            
          

          <img src="<?php echo base_url(); ?>assets/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image" />
        </div>
        <div class="pull-left info">
          <p><?php echo $username; ?></p>
          <a href="#" class="on_off"><i class="fa fa-circle text-success"></i>Online</a>
        </div>
      </div>

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header" style="color:rgb(123, 159, 14);">
            
            <?php
            
            $myfacility=$_SESSION['facility'];
            
            foreach($facilities as $facility){
                
                if($facility['facility_id']==$myfacility){
                    
                    echo $facility['facility']; 
                }
                
            }
            
            
            ?>
    
        </li>

        <li>
          <a href="<?php echo base_url(); ?>attendance">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        
         

<?php 



if($this->aauth->is_group_allowed("viewstaff",$mygroup))
{
    
   

?>
         <li>
          <a href="<?php echo base_url(); ?>attendance/staff">
            <i class="fa fa-users"></i> <span>Staff</span>
          </a>
        </li>
        
        <?php }



if($this->aauth->is_group_allowed("makerota",$mygroup))
{
    
   

?>
   
       
        <li class="treeview">
          <a href="#">
            <i class="fa fa-calendar"></i>
            <span>Duty Roster</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
  
            <li><a href="<?php echo base_url(); ?>rosta"><i class="fa fa-calendar-o"></i> Calendar Format</a></li>
            <li><a href="<?php echo base_url(); ?>rosta/tabular"><i class="fa fa-th"></i> Tabular Format</a></li>
            
            </ul></li>
            
            
             
            <?php 


?>
        <li><a href="<?php echo base_url(); ?>rosta/actuals"><i class="fa fa-clock-o"></i> <span>Daily Attendance</span></a></li>
        
        
             
        
<?php } ?>

<?php 

if($this->aauth->is_group_allowed("viewstaff",$mygroup))
{
    
?>
         <li>
          <a href="<?php echo base_url(); ?>attendance/fingerprints">
            <i class="fa fa-mobile-phone"></i> <span>Enrolled Fingerprints</span>
          </a>
        </li>
        
<?php } ?>
           
 <!--<li><a href="<?php echo base_url(); ?>attendance/machinedata"><i class="fa fa-calculator"></i><span>Upload Machine Data</span></a></li>-->
            
            <?php 


if($this->aauth->is_group_allowed("manageusers",$mygroup)  or $this->aauth->is_group_allowed("manageschedule",$mygroup))
{
    
   

?>
        

        <li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Settings</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
              
              
                      <?php 



if($this->aauth->is_group_allowed("manageusers",$mygroup))
{
    
   

?>
            <li><a href="<?php echo base_url();?>admin/settings"><i class="fa fa-cog"></i>System Variables</a></li>


             <li><a href="<?php echo base_url()?>attendance/auditupload"><i class="fa fa-circle-o"></i>Audit Data</a></li>
            
            
            <li class="treeview">
                <a href="#"><i class="fa fa-circle-o"></i>User Management</a>
          
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url()?>admin/groups"><i class="fa fa-user"></i>User Groups</a></li>
            
     
            
            <li><a href="<?php echo base_url()?>admin/user_list"><i class="fa fa-circle-o"></i>Manage Users</a></li>
            
             <li><a href="<?php echo base_url()?>admin/showLogs"><i class="fa fa-circle-o"></i>Activity Logs</a></li>

            
                   
          </ul>
       
            </li>
      
                    <?php 
//
}

if($this->aauth->is_group_allowed("manageschedule",$mygroup))
{
    
   

?>

            <li><a href="<?php echo base_url()?>attendance/schedules"><i class="fa fa-circle-o"></i> Manage Schedules</a></li>
            
            <?php } ?>
            
            
          </ul>
        </li>

         <?php 

}//settings




if($this->aauth->is_group_allowed("viewreports",$mygroup))
{
    
   

?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-files-o"></i> <span>Monthly Reports</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">

        <li>
          <a href="<?php echo base_url()?>rosta/fetch_report">
            <i class="fa fa-circle-o"></i> <span>Duty Roster Report</span>
          </a>
        </li>
        
       
        
         <li><a href="<?php echo base_url(); ?>rosta/summary"><i class="fa fa-circle-o"></i>Duty Roster Summary</a></li>
         
         
         <li><a href="<?php echo base_url(); ?>rosta/actualsreport"><i class="fa fa-circle-o"></i>Daily Attendance Report</a></li>
         
          <li><a href="<?php echo base_url(); ?>rosta/attendance_summary"><i class="fa fa-circle-o"></i>Attendance Summary</a></li>
         <li>
            <a href="<?php echo base_url()?>attendance/timeLogReport">
                <i class="fa fa-circle-o"></i> <span>Time Log Report</span>
            </a>
        </li>
     
        </ul>
        </li>
        
        
        <?php } ?>
        
        
        
       <li>
          <a href="" class="passchange" data-toggle="modal" data-target="#pass" >
            <i class="fa fa-lock"></i> <span>Change Password</span>
          </a>
        </li>
      
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
  

