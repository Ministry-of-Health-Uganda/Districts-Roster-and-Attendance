<?php 

class Rostatoattendance extends CI_Controller{
   //Get duty rosta records to attendance information
   //runs montly to generate past months data
public function rostatoAttend(){
    //auth
    $last = $this->uri->total_segments();
    $record_num = $this->uri->segment($last);
   // print_r($record_num);
    $authkey=array('uyey8187167817','787167188yuehd');
   
    //To set custom month uncomment below and set  ymonth of choice
    //$ymonth="2019-08"."-";
    // comment  the file below on line 145 if custom ymonth is set.
  //  $ymonth=date('Y-m-', strtotime('last month'));
    $ymonth=date('Y-m');
   
   //$ymonth= "2019-07";
   // print_r($ymonth);
  
    
   if(!empty($ymonth)){ 
  
    //poplulate actuals
    $query=$this->db->query("INSERT INTO actuals( entry_id, schedule_id, ihris_pid, actuals.date, facility_id ) 
    SELECT entry_id,schedule_id,duty_rosta.ihris_pid,duty_rosta.duty_date,facility_id from duty_rosta WHERE schedule_id 
    IN(17,18,19,20,21) and duty_rosta.duty_date like '$ymonth%' AND duty_rosta.entry_id NOT IN(SELECT entry_id from actuals)");
    $rowsnow=$this->db->affected_rows();
    if($query){
      echo  $msg="<font color='green'>".$rowsnow. "  Attendance Records Marked</font><br>";
          }
       else{
           
      echo   $msg="<font color='red'>Failed to Mark</font><br>";
           
  }
  }
    
    $query=$this->db->query("Update actuals set schedule_id='25' WHERE schedule_id IN(18,19,20,21)");
    
      $rowsnow=$this->db->affected_rows();
      if($query){
             echo  $msg="<font color='green'>".$rowsnow. "  Leave records recognised by attendance </font><br>";
                 }
              else{
                  
             echo   $msg="<font color='red'>No leave records found</font><br>";
                  
    }
  
    $query=$this->db->query("Update actuals set schedule_id='24' WHERE schedule_id='17'");
    
      $rowsnow=$this->db->affected_rows();
      if($query){
             echo  $msg="<font color='green'>".$rowsnow. "  Offduty records recognised by attendance </font><br>";
                 }
              else{
                  
             echo   $msg="<font color='red'>No Off duty records found</font><br>";
                  
    }
     
    
  
  }
  public function markAttendance(){

    //poplulate actuals
    // $query=$this->db->query("INSERT INTO actuals( entry_id, facility_id, ihris_pid, schedule_id,
    //  actuals.date) SELECT DISTINCT CONCAT( time_log.date, ihrisdata.ihris_pid ) AS entry_id, ihrisdata.facility_id, 
    //  ihrisdata.department_id, ihrisdata.ihris_pid, schedules.schedule_id, schedules.color, time_log.date, DATE_ADD(date, INTERVAL 01 DAY) FROM ihrisdata, 
    //  time_log, schedules WHERE ihrisdata.ipps = time_log.ipps AND schedules.schedule_id =22 AND CONCAT( time_log.date, ihrisdata.ihris_pid )
    //   NOT IN (SELECT entry_id from actuals)");   
      $query=$this->db->query("INSERT INTO actuals (date, entry_id, facility_id, ihris_pid, schedule_id)
      SELECT clk_log.date, clk_log.entry_id, clk_log.facility_id, clk_log.ihris_pid, schedules.schedule_id
      FROM clk_log,schedules where clk_log.entry_id NOT IN (select entry_id from actuals) and schedules.schedule_id=22");
           
      
      $rowsnow=$this->db->affected_rows();
      if($query){
             echo  $msg="<font color='green'>".$rowsnow. "  Attendance Records Marked</font><br>";
                 }
              else{
                  
             echo   $msg="<font color='red'>Failed to Mark</font><br>";
                  
    }
    }

	

}

?>
