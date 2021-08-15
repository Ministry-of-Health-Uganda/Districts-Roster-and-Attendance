<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Areports extends MX_Controller {

		function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->load->model('areports_model');
        $this->departments=$this->areports_model->getDepartments();
      
    }

	

	Public function fetch_report($facility)
	{
	    


if(empty($facility)){          
    
    $facility=$this->session->userdata('facility'); 
    
}

else{
    
    $this->session->set_userdata('facility',$facility); 
    
}

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

		$data['duties']=$this->areports_model->fetch_report($date,$facility);
		$data['matches']=$this->areports_model->matches();
		$data['checks']=$this->areports_model->checks($facility);
	
		
		$this->load->view('mobile_duty_report',$data);
	}





Public function print_report($date,$facility)
	{	
if(empty($facility)){          
    
    $facility=$this->session->userdata('facility'); 
    
}

else{
    
    $this->session->set_userdata('facility',$facility); 
    
}
        $data['dates']=$date;
        
		$this->load->library('ML_pdf');

		$data['username']=$this->username;
		$data['checks']=$this->areports_model->checks($facility);

		$data['duties']=$this->areports_model->fetch_report($date);

		$data['matches']=$this->areports_model->matches();
		
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
	
	
	Public function summary($facility)
	{	
	    
if(empty($facility)){          
    
    $facility=$this->session->userdata('facility'); 
    
}

else{
    
    $this->session->set_userdata('facility',$facility); 
    
}

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
$data['sums']=$this->areports_model->fetch_summary($date,$facility);

$this->load->view('mobile_summary_report',$data);
	}
	
	


	Public function attendance_summary($facility)
	{	
	    
if(empty($facility)){          
    
    $facility=$this->session->userdata('facility'); 
    
}

else{
    
    $this->session->set_userdata('facility',$facility); 
    
}	    
	    
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
$data['sums']=$this->areports_model->attendance_summary($date,$facility);

$this->load->view('mobile_attendance_summary',$data);
	}
		
	




public function bundleCsv($valid_range,$facility){
    
  if(empty($facility)){          
    
    $facility=$this->session->userdata('facility'); 
    
}

else{
    
    $this->session->set_userdata('facility',$facility); 
    
}
 $sums=$this->areports_model->fetch_summary($valid_range,$facility);

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


Public function presence($facility)
	{	
if(empty($facility)){          
    
    $facility=$this->session->userdata('facility'); 
    
}

else{
    
    $this->session->set_userdata('facility',$facility); 
    
}

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
		

		$data['duties']=$this->areports_model->fetch_report($date,$facility);

			$nonworkables=$this->areports_model->nonworkables();

			$workeddays=$this->areports_model->workeddays($facility);

		$data['nonworkables']=$nonworkables;
		$data['workeddays']=$workeddays;

		$data['matches']=$this->areports_model->matches();
		$data['checks']=$this->areports_model->checks($facility);
		//$data['switches']=$this->switches();
		
		$this->load->view('mobile_presence_report',$data);

	

	}
	
	
	
	
	Public function print_presence($date,$facility)
	{	
	  if(empty($facility)){          
    
    $facility=$this->session->userdata('facility'); 
    
}

else{
    
    $this->session->set_userdata('facility',$facility); 
    
}

        $data['dates']=$date;
        
		$this->load->library('ML_pdf');

		$data['username']=$this->username;
		$data['checks']=$this->areports_model->checks($facility);
		
			$nonworkables=$this->areports_model->nonworkables();

			$workeddays=$this->areports_model->workeddays($facility);

		$data['nonworkables']=$nonworkables;
		$data['workeddays']=$workeddays;

		$data['duties']=$this->areports_model->fetch_report($date,$facility);

		$data['matches']=$this->areports_model->matches();
		
		$html=$this->load->view('printabletracker',$data,true);

$fac=$data['duties'][0]['facility'];
$date=date('F-Y',strtotime($data['duties'][0]['day1']));

$filename=$fac."_tracking_report_".$date.".pdf";


 ini_set('max_execution_time',0);
 $PDFContent = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
 

 ini_set('max_execution_time',0);
$this->ml_pdf->pdf->WriteHTML($PDFContent); //ml_pdf because we loaded the library ml_pdf for landscape format not m_pdf
 
//download it D save F.
$this->ml_pdf->pdf->Output($filename,'D');


	}
	

	
Public function actualsreport($facility)
	{	

if(empty($facility)){          
    
    $facility=$this->session->userdata('facility'); 
    
}

else{
    
    $this->session->set_userdata('facility',$facility); 
    
}

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

		$data['duties']=$this->areports_model->fetch_report($date,$facility);
    	$actualrows=$this->areports_model->getActuals();
			$actuals=array();
		
		foreach($actualrows as $actual){
		    
		    $entry=$actual['entry_id'];
		     $duty=$actual['actual'];
		     
		      
		     
		     $actuals[$entry]=$duty;
		}
		
		$data['actuals']=$actuals;

		$data['matches']=$this->areports_model->matches();
		$data['checks']=$this->areports_model->checks($facility);
		//$data['switches']=$this->switches();
		
		$this->load->view('mobile_actuals_report',$data);

	

	}
	

Public function print_actuals($date)
	{
	    if(empty($facility)){          
    
    $facility=$this->session->userdata('facility'); 
    
}

else{
    
    $this->session->set_userdata('facility',$facility); 
    
}
	    
        $data['dates']=$date;
        
		$this->load->library('ML_pdf');

		$data['username']=$this->username;
	

		$data['duties']=$this->areports_model->fetch_report($date);
		
		$actualrows=$this->areports_model->getActuals();
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
$this->ml_pdf->pdf->Output($filename,'D');


	}	


Public function print_summary($date,$facility)
	{	

if(empty($facility)){          
    
    $facility=$this->session->userdata('facility'); 
    
}

else{
    
    $this->session->set_userdata('facility',$facility); 
    
}
        $data['dates']=$date;
        
		$this->load->library('ML_pdf');

	$data['username']=$this->username;

$data['sums']=$this->areports_model->fetch_summary($date,$facility);
		
		$html=$this->load->view('printablesummary',$data,true);

$fac=$_SESSION['facility'];

$filename=$fac."_summary_report_".$date.".pdf";


 ini_set('max_execution_time',0);
 $PDFContent = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
 

 ini_set('max_execution_time',0);
$this->ml_pdf->pdf->WriteHTML($PDFContent); //ml_pdf because we loaded the library ml_pdf for landscape format not m_pdf
 
//download it D save F.
$this->ml_pdf->pdf->Output($filename,'D');


	}
	
	
Public function print_attsummary($date)
	{	
if(empty($facility)){          
    
    $facility=$this->session->userdata('facility'); 
    
}

else{
    
    $this->session->set_userdata('facility',$facility); 
    
}

        $data['dates']=$date;
        
		$this->load->library('ML_pdf');

	$data['username']=$this->username;

$data['sums']=$this->areports_model->attendance_summary($date,$facility);
		
		$html=$this->load->view('att_summary',$data,true);

$fac=$_SESSION['facility'];

$filename=$fac."att_summary_report_".$date.".pdf";


 ini_set('max_execution_time',0);
 $PDFContent = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
 

 ini_set('max_execution_time',0);
$this->ml_pdf->pdf->WriteHTML($PDFContent); //ml_pdf because we loaded the library ml_pdf for landscape format not m_pdf
 
//download it D save F.
$this->ml_pdf->pdf->Output($filename,'D');


	}
	
public function attCsv($valid_range,$facility){
    
 if(empty($facility)){          
    
    $facility=$this->session->userdata('facility'); 
    
}

else{
    
    $this->session->set_userdata('facility',$facility); 
    
}
 
 $sums=$this->areports_model->attendance_summary($valid_range);

 $fp = fopen('php://memory', 'w'); 

//add heading to data
$heading=array('person' =>"Staff Names" ,'R'=>"R", 'facility' =>' ', 'O' => "O", 'P' => "P", 'L' =>"L");

array_unshift($sums,$heading);

foreach ($sums as $sum) {
   
   $data=array(); 
$data['person']=$sum['person'];
$data['R']=$sum['R'];
$data['O']=$sum['O'];
$data['P']=$sum['P'];
$data['L']=$sum['L'];
    
   fputcsv($fp, $data);
    
}

//

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


	
	
	


}
