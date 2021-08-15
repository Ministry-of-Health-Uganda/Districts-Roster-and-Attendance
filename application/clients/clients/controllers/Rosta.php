<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rosta extends CI_Controller {

		function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->load->model('rosta_model');
        $this->load->model('attendance_model');


        $this->username=$this->session->userdata['names'];
        
        $this->uid=$this->session->userdata['uid'];

        $this->checks=$this->rosta_model->checks();

        $this->departments=$this->rosta_model->getDepartments();
      
    }
    
    
    	
	public function switches(){
	    
	    
	    $facilities=$this->attendance_model->get_facility();		
		 
  $newArray=array();
  
  $switches="";
  
foreach($facilities as $val){
    
    $newKey=$val['district_id'];
    
    $newArray[$newKey][]=$val;
    
     $switches. "<optgroup label='".$newKey."'>";
    
    for($i=0;$i<count($newArray[$newKey]);$i++){
        
        $switches. "<option value='".$newArray[$newKey][$i]['facility_id']."'>".$newArray[$newKey][$i]['facility']."</option>";
    }
    
    $switches. "</optgroup>";
    
}

return $switches;
	    
	    
	}


	/*Home page Calendar view  */
	Public function index()
	{
		$data['schedules']=$this->attendance_model->get_schedules("r");
		$data['workers']=$this->attendance_model->get_employees();
		$data['username']=$this->username;
		$data['checks']=$this->checks;
		
		$data['facilities']=$this->attendance_model->get_facility();

     // $data['switches']=$this->switches();
		$this->load->view('rosta',$data);
	}

	/*Get all Events */

	Public function getEvents()
	{
		$result=$this->rosta_model->getEvents();
		echo json_encode($result);
	}
	/*Add new event */
	Public function addEvent()
	{


		$result=$this->rosta_model->addEvent();
		
		echo $result;
	}
	/*Update Event */
	Public function updateEvent()
	{
		
		$result=$this->rosta_model->updateEvent();
		echo $result;
	}
	/*Delete Event*/
	Public function deleteEvent()
	{
		$result=$this->rosta_model->deleteEvent();
		echo $result;
	}
	Public function dragUpdateEvent()
	{	

		$result=$this->rosta_model->dragUpdateEvent();
		echo $result;
	}

		Public function tabular()
	{	

$month=$this->input->post('month');
$year=$this->input->post('year');
$department=$this->input->post('department');



if($month!="")
{

$data['month']=$month;

$data['year']=$year;



}

else{

$data['month']=date('m');

$data['year']=date('Y');

}

if($department){

	$data['depart']=$department;
}


			$date=date('Y-m');
        $data['schedules']=$this->attendance_model->get_schedules("r");
		$data['username']=$this->username;
		$data['checks']=$this->checks;
		$data['departments']=$this->departments;

		$data['duties']=$this->rosta_model->fetch_tabs($date);

		$data['matches']=$this->rosta_model->matches();

		$data['tab_schedules']=$this->rosta_model->tab_matches();
		
	$data['facilities']=$this->attendance_model->get_facility();
		
		//$data['switches']=$this->switches();
		
		$this->load->view('tab_duty',$data);
	}



	Public function fetch_report()
	{	



$month=$this->input->post('month');
$year=$this->input->post('year');
$department=$this->input->post('department');


if($month!="")
{

$data['month']=$month;

$data['year']=$year;




}

else{

$data['month']=date('m');

$data['year']=date('Y');


}

if($department!=''){

$data['depart']=$department;

}
else{

	$data['depart']='';
}

$date=date('Y')."-".date('m');

		$data['username']=$this->username;
		$data['departments']=$this->departments;

		$data['duties']=$this->rosta_model->fetch_report($date);

		$data['matches']=$this->rosta_model->matches();
		$data['checks']=$this->checks;
		
		$data['facilities']=$this->attendance_model->get_facility();
		//$data['switches']=$this->switches();
		
		$this->load->view('duty_report',$data);
	}




Public function print_report($date)
	{	

        $data['dates']=$date;
        
		$this->load->library('ML_pdf');

		$data['username']=$this->username;
		$data['checks']=$this->checks;

		$data['duties']=$this->rosta_model->fetch_report($date);

		$data['matches']=$this->rosta_model->matches();
		
		$html=$this->load->view('printable',$data,true);

$fac=$data['duties'][0]['facility'];
$date=date('F-Y',strtotime($data['duties'][0]['day1']));

$filename=$fac."_rota_report_".$date.".pdf";


 ini_set('max_execution_time',0);
 $PDFContent = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
 

 ini_set('max_execution_time',0);
$this->ml_pdf->pdf->WriteHTML($PDFContent); //ml_pdf because we loaded the library ml_pdf for landscape format not m_pdf
 
//download it D save F.
$this->ml_pdf->pdf->Output($filename,'I');


	}
	
	
	Public function summary()
	{	
	    
	    
$month=$this->input->post('month');
$year=$this->input->post('year');
$department=$this->input->post('department');



//for a dynamic one

if($month!="")
{

$data['month']=$month;

$data['year']=$year;



}

else{

$data['month']=date('m');

$data['year']=date('Y');

}

if($department){

	$data['depart']=$department;

}

$date=$data['year']."-".$data['month'];
	    
$data['departments']=$this->departments;
$data['username']=$this->username;
$data['dates']=$this->$date;
$data['facilities']=$this->attendance_model->get_facility();
$data['sums']=$this->rosta_model->fetch_summary($date);

$this->load->view('summary_report',$data);
	}
	
	



	Public function attendance_summary()
	{	
	    
	    
$month=$this->input->post('month');
$year=$this->input->post('year');
$department=$this->input->post('department');



//for a dynamic one

if($month!="")
{

$data['month']=$month;

$data['year']=$year;


}

else{

$data['month']=date('m');

$data['year']=date('Y');

}

if($department){

$data['depart']=$department;

}

$date=$data['year']."-".$data['month'];
	    
$data['departments']=$this->departments;
$data['username']=$this->username;
$data['dates']=$date;
$data['facilities']=$this->attendance_model->get_facility();
$data['sums']=$this->rosta_model->attendance_summary($date);

$this->load->view('attendance_summary',$data);
	}
		
	




public function bundleCsv($valid_range){
    
 $sums=$this->rosta_model->fetch_summary($valid_range);

//$fp = fopen(FCPATH.'uploads/summary.csv', 'w');

 $fp = fopen('php://memory', 'w'); 

//add heading to data
$heading=array('person' =>"Names" ,'D'=>"Day Duty", 'facility' =>' ', 'E' => "Evening", 'N' => "Night", 'O' =>"Off Duty", 'A' => "Annual Leave", 'S' => "Study Leave", 'M' => "Maternity Leave", 'Z' =>"Other Leave", 'H' => "");

array_unshift($sums,$heading);

foreach ($sums as $sum) {
    
   fputcsv($fp, $sum);
    
}


$filename=$valid_range."_summary_report.csv";

// reset the file pointer to the start of the file
    fseek($fp, 0);
    // tell the browser it's going to be a csv file
    header('Content-Type: application/csv');
    // tell the browser we want to save it instead of displaying it
    header('Content-Disposition: attachment; filename="'.$filename.'";');
    // make php send the generated csv lines to the browser
    fpassthru($fp);


fclose($fp);

}


Public function presence()
	{	



$month=$this->input->post('month');
$year=$this->input->post('year');
$department=$this->input->post('department');


//for a dynamic one

if($month!="")
{

$data['month']=$month;

$data['year']=$year;
$data['department']=$department;

}

else{

$data['month']=date('m');

$data['year']=date('Y');


}

$date=date('Y')."-".date('m');

		$data['departments']=$this->departments;
		$data['username']=$this->username;
		
		$data['facilities']=$this->attendance_model->get_facility();

		$data['duties']=$this->rosta_model->fetch_report($date);

			$nonworkables=$this->rosta_model->nonworkables();

			$workeddays=$this->rosta_model->workeddays();

		$data['nonworkables']=$nonworkables;
		$data['workeddays']=$workeddays;

		$data['matches']=$this->rosta_model->matches();
		$data['checks']=$this->checks;
		//$data['switches']=$this->switches();
		
		$this->load->view('presence_report',$data);

	

	}
	
	
	
	
	
		Public function tracker()
	{	


$month=$this->input->post('month');
$year=$this->input->post('year');
$department=$this->input->post('department');


//for a dynamic one

if($month!="")
{

$data['month']=$month;

$data['year']=$year;
$data['department']=$department;


}

else{

$data['month']=date('m');

$data['year']=date('Y');

}

$date=date('Y')."-".date('m');

		$data['username']=$this->username;
		$data['departments']=$this->departments;

		$data['duties']=$this->rosta_model->fetch_report($date);

			$nonworkables=$this->rosta_model->nonworkables();
			
		$data['facilities']=$this->attendance_model->get_facility();

			$workeddays=$this->rosta_model->workeddays();

		$data['nonworkables']=$nonworkables;
		$data['workeddays']=$workeddays;

		$data['matches']=$this->rosta_model->matches();
		$data['checks']=$this->checks;
		//$data['switches']=$this->switches();
		
		$this->load->view('presence_fm',$data);

	

	}


	
	
//presense tracking

	public function saveTracker(){

		$pid=$_POST['hpid'];
		$date=$_POST['date'];



		$data= array('ihris_pid' => $pid,'day'=>$date );

		//print_r($data);

		$result=$this->rosta_model->saveTracker($data);
echo $result;
	}
	
	

	
	
	
	Public function print_presence($date)
	{	

        $data['dates']=$date;
        
		$this->load->library('ML_pdf');

		$data['username']=$this->username;
		$data['checks']=$this->checks;
		
			$nonworkables=$this->rosta_model->nonworkables();

			$workeddays=$this->rosta_model->workeddays();

		$data['nonworkables']=$nonworkables;
		$data['workeddays']=$workeddays;

		$data['duties']=$this->rosta_model->fetch_report($date);

		$data['matches']=$this->rosta_model->matches();
		
		$html=$this->load->view('printabletracker',$data,true);

$fac=$data['duties'][0]['facility'];
$date=date('F-Y',strtotime($data['duties'][0]['day1']));

$filename=$fac."_tracking_report_".$date.".pdf";


 ini_set('max_execution_time',0);
 $PDFContent = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
 

 ini_set('max_execution_time',0);
$this->ml_pdf->pdf->WriteHTML($PDFContent); //ml_pdf because we loaded the library ml_pdf for landscape format not m_pdf
 
//download it D save F.
$this->ml_pdf->pdf->Output($filename,'I');


	}
	
	
	
	
	
			Public function actuals()
	{	


$month=$this->input->post('month');
$year=$this->input->post('year');
$department=$this->input->post('department');


//for a dynamic one

if($month!="")
{

$data['month']=$month;

$data['year']=$year;
$data['department']=$department;

}

else{

$data['month']=date('m');

$data['year']=date('Y');


}

$date=date('Y')."-".date('m');

		$data['username']=$this->username;
		$data['departments']=$this->departments;

		$data['duties']=$this->rosta_model->fetch_report($date);

			$nonworkables=$this->rosta_model->nonworkables();
			
		$data['facilities']=$this->attendance_model->get_facility();

		$actualrows=$this->rosta_model->getActuals();
		
		$actuals=array();
		
		foreach($actualrows as $actual){
		    
		    $entry=$actual['entry_id'];
		     $duty=$actual['actual'];
		     
		      
		     
		     $actuals[$entry]=$duty;
		}
		
		$data['actuals']=$actuals;
	//	$data['switches']=$this->switches();
		
		$this->load->view('actuals',$data);

	

	}
	
	
	//presense tracking

	public function saveActual(){

		$pid=$_POST['hpid'];
		$date=$_POST['date'];
		$actual=$_POST['duty'];
		$facility=$this->session->userdata['facility'];
		
		$rowid=$_POST['date'].$_POST['hpid'];

        $entry=str_replace(' ','',$rowid);
        //'ihris_pid' => $pid
        
        $actualletters=$this->rosta_model->attendanceSchedules();
        
        $duty=$actualletters[$actual];

	$data= array('entry_id'=>$entry,'date'=>$date,'schedule_id'=>$duty,'ihris_pid'=>$pid,'facility_id'=>$facility);

		//print_r($data);

		$result=$this->rosta_model->saveActual($data);
//echo $result;

echo  $duty;
	}
	
	
	
	
	
	
	
		
	
	
	
Public function actualsreport()
	{	



$month=$this->input->post('month');
$year=$this->input->post('year');
$department=$this->input->post('department');


//for a dynamic one

if($month!="")
{

$data['month']=$month;

$data['year']=$year;


}

else{

$data['month']=date('m');

$data['year']=date('Y');



}

if($department!==''){

	$data['depart']=$department;
}

$date=date('Y')."-".date('m');


		$data['username']=$this->username;
		$data['departments']=$this->departments; 

		
		$data['facilities']=$this->attendance_model->get_facility();

		$data['duties']=$this->rosta_model->fetch_report($date);
    	$actualrows=$this->rosta_model->getActuals();
			$actuals=array();
		
		foreach($actualrows as $actual){
		    
		    $entry=$actual['entry_id'];
		     $duty=$actual['actual'];
		     
		      
		     
		     $actuals[$entry]=$duty;
		}
		
		$data['actuals']=$actuals;

		$data['matches']=$this->rosta_model->matches();
		$data['checks']=$this->checks;
		//$data['switches']=$this->switches();
		
		$this->load->view('actuals_report',$data);

	

	}
	

Public function print_actuals($date)
	{	

        $data['dates']=$date;
        
		$this->load->library('ML_pdf');

		$data['username']=$this->username;
	

		$data['duties']=$this->rosta_model->fetch_report($date);
		
		$actualrows=$this->rosta_model->getActuals();
			$actuals=array();
		
		foreach($actualrows as $actual){
		    
		    $entry=$actual['entry_id'];
		     $duty=$actual['actual'];
		     
		     $actuals[$entry]=$duty;
		}
		
		$data['actuals']=$actuals;
		
		$html=$this->load->view('actual_printable',$data,true);

$fac=$data['duties'][0]['facility'];
$date=date('F-Y',strtotime($data['duties'][0]['day1']));

$filename=$fac."_actuals_report_".$date.".pdf";


 ini_set('max_execution_time',0);
 $PDFContent = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
 

 ini_set('max_execution_time',0);
$this->ml_pdf->pdf->WriteHTML($PDFContent); //ml_pdf because we loaded the library ml_pdf for landscape format not m_pdf
 
//download it D save F.
$this->ml_pdf->pdf->Output($filename,'I');


	}	



	
		Public function print_summary($date)
	{	

        $data['dates']=$date;
        
		$this->load->library('ML_pdf');

	$data['username']=$this->username;

$data['sums']=$this->rosta_model->fetch_summary($date);
		
		$html=$this->load->view('printablesummary',$data,true);

$fac=$_SESSION['facility'];

$filename=$fac."_summary_report_".$date.".pdf";


 ini_set('max_execution_time',0);
 $PDFContent = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
 

 ini_set('max_execution_time',0);
$this->ml_pdf->pdf->WriteHTML($PDFContent); //ml_pdf because we loaded the library ml_pdf for landscape format not m_pdf
 
//download it D save F.
$this->ml_pdf->pdf->Output($filename,'I');


	}
	
	
	
		
	
	
	
	Public function print_attsummary($date)
	{	

        $data['dates']=$date;
        
		$this->load->library('ML_pdf');

	$data['username']=$this->username;

$data['sums']=$this->rosta_model->attendance_summary($date);
		
		$html=$this->load->view('att_summary',$data,true);

$fac=$_SESSION['facility'];

$filename=$fac."att_summary_report_".$date.".pdf";


 ini_set('max_execution_time',0);
 $PDFContent = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
 

 ini_set('max_execution_time',0);
$this->ml_pdf->pdf->WriteHTML($PDFContent); //ml_pdf because we loaded the library ml_pdf for landscape format not m_pdf
 
//download it D save F.
$this->ml_pdf->pdf->Output($filename,'I');


	}
	
	
public function attCsv($valid_range){
    
 $sums=$this->rosta_model->attendance_summary($valid_range);



 $fp = fopen('php://memory', 'w'); 

//add heading to data
$heading=array('person' =>"Staff Names" ,'R'=>"Official Request", 'facility' =>' ', 'O' => "Off Duty", 'P' => "Present", 'L'=>"Leave", 'X'=>"Absent");

array_unshift($sums,$heading);

foreach ($sums as $sum) {
   
   $data=array(); 
$data['person']=$sum['person'];
$data['R']=$sum['R'];
$data['O']=$sum['O'];
$data['P']=$sum['P'];
$data['L']=$sum['L'];
$data['X']=$sum['X'];
   fputcsv($fp, $data);
    
}


$filename=$valid_range."att_summary_report.csv";


// reset the file pointer to the start of the file
    fseek($fp, 0);
    // tell the browser it's going to be a csv file
    header('Content-Type: application/csv');
    // tell the browser we want to save it instead of displaying it
    header('Content-Disposition: attachment; filename="'.$filename.'";');
    // make php send the generated csv lines to the browser
    fpassthru($fp);


fclose($fp);

}


public function autoAttCsv(){
    
    $d = new DateTime('first day of this month');
     
    $valid_range=$d->format('Y-m');
    
 $sums=$this->rosta_model->attendance_summary($valid_range);

$filename="uploads/".$valid_range.time()."att_summary_report.csv";


 $fp = fopen($filename, 'w'); 

//add heading to data
$heading=array('person' =>"Staff Names" ,'R'=>"Official Request", 'facility' =>' ', 'O' => "Off Duty", 'P' => "Present", 'L'=>"Leave", 'X'=>"Absent");

array_unshift($sums,$heading);

foreach ($sums as $sum) {
   
   $data=array(); 
$data['person']=$sum['person'];
$data['R']=$sum['R'];
$data['O']=$sum['O'];
$data['P']=$sum['P'];
$data['L']=$sum['L'];
$data['X']=$sum['X'];
 
   fputcsv($fp, $data);
    
}



fclose($fp);

}

public function autoRotaCsv(){
    
       
    $d = new DateTime('first day of this month');
     
    $valid_range=$d->format('Y-m');
    
 $sums=$this->rosta_model->fetch_summary($valid_range);

$filename="uploads/".$valid_range.time()."rota_summary_report.csv";


 $fp = fopen($filename, 'w'); 

//add heading to data

$heading=array('person' =>"Name" ,'facility' =>' ','duty'=>"Duty", 'leave' => "Leaves", 'off' => "Offs", 'official' =>"Official Leave");

//array_unshift($sums,$heading);

foreach ($sums as $sum) {
   
    $data=array();
    $data['person']=$sum['person'];
    $data['facility']=$sum['facility'];
    $data['duty']=$sum['D']+$sum['N']+$sum['E'];
    $data['leave']=$sum['M']+$sum['A']+$sum['S'];
    $data['off']=$sum['O'];
    $data['official']=$sum['Z'];
   fputcsv($fp, $data);
    
}



fclose($fp);

}

	
	


}
