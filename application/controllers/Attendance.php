<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends MX_Controller {
	
	
	public  function __construct(){
		
		
		parent:: __construct();
		
		//if not logged in, go to login page
		 if(!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }
	
		$this->load->model('login_model');
		$this->load->model('attendance_model');
		$this->load->model('rosta_model');
		$this->load->library('pagination');
	
		$this->username=$this->session->userdata['names'];
		$this->facility=$this->session->userdata['facility'];
		$this->schedules=$this->attendance_model->get_schedules();
		$this->staff=$this->attendance_model->get_employees();
		 $this->checks=$this->rosta_model->checks();
		 
		 
		 $this->uid=$this->session->userdata['uid'];
		
	}


	public function index()
	{
		
		$data['users']=$this->login_model->getusers();
		$data['checks']=$this->checks;
		
		$data['widgets']=$this->attendance_model->widget_data();
		
		$data['facilities']=$this->attendance_model->get_facility();
		$data['username']=$this->username;
	
		$this->load->view('home',$data);
	}
	
	

	//data importing
	
	public function import_csv()
	{
	 
$postdata=$this->input->post(); 
		
$day=$_SESSION['fetch_date'];
$year=date("m_Y");


  
	    if(empty($day)){
	        
	        $day=20;
	    }
	

		
$file_name="Biometric_Users_.$day._".$date.".csv";

	
		$this->load->library('excel');
		
		$type = PHPExcel_IOFactory::identify('uploads/'.$file_name);
		
		$objReader=PHPExcel_IOFactory::createReader($type);     //For excel 2003 
		
          //Set to read only
          $objReader->setReadDataOnly(true); 		  
        //Load excel file
		$objPHPExcel=$objReader->load(strip_tags(FCPATH.'uploads/'.$file_name));	
		
		 
         $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
         $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);  
         
         
         
         //truncate table if data file contains data
         if($totalrows>0){
             
             $this->db->query("truncate ihrisdata");
             
         }
        

		
		$rowsnow=0;
		 //loop from first data untill last data
         for($i=2;$i<=$totalrows;$i++)
          {
              
			  
			  $person_id= $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();			
              $district_id= $objWorksheet->getCellByColumnAndRow(1,$i)->getValue(); //Excel Column 1
			  $district= $objWorksheet->getCellByColumnAndRow(2,$i)->getValue(); //Excel Column 2
			  $nin=$objWorksheet->getCellByColumnAndRow(3,$i)->getValue(); //Excel Column 3 parent fname
			  $ipps=$objWorksheet->getCellByColumnAndRow(4,$i)->getValue(); //Excel Column 4 
			  $facility_type_id=$objWorksheet->getCellByColumnAndRow(5,$i)->getValue(); //Excel Column 5 
			  $facility_id=$objWorksheet->getCellByColumnAndRow(6,$i)->getValue(); //Excel Column 6
			  $facility=$objWorksheet->getCellByColumnAndRow(7,$i)->getValue(); //Excel Column 7 
			  $department=$objWorksheet->getCellByColumnAndRow(8,$i)->getValue(); //Excel Column 8 
			  $job_id=$objWorksheet->getCellByColumnAndRow(9,$i)->getValue(); //Excel Column 9 
			  $job_title=$objWorksheet->getCellByColumnAndRow(10,$i)->getValue(); //Excel Column 11 
			  $surname=$objWorksheet->getCellByColumnAndRow(11,$i)->getValue(); //Excel Column 12 
			  $firstname=$objWorksheet->getCellByColumnAndRow(12,$i)->getValue(); //Excel Column 13 
			  $othername=$objWorksheet->getCellByColumnAndRow(13,$i)->getValue(); //Excel Column 14 
			  $mobile_phone=$objWorksheet->getCellByColumnAndRow(14,$i)->getValue(); //Excel Column 15 
			  $telephone=$objWorksheet->getCellByColumnAndRow(15,$i)->getValue(); //Excel Column 16 
			  

			    
			 $excel_data=array('ihris_pid'=>$person_id,'district_id'=>$district, 'district'=>$district ,'nin'=>$nin ,'ipps'=>$ipps , 'facility_type_id'=>$facility_type_id
			 , 'facility_id'=>$facility_id,'facility'=>$facility,'department'=>$department, 'job_id'=>$job_id, 'job'=>$job_title, 'surname'=>$surname
			 , 'firstname'=>$firstname, 'othername'=>$othername, 'mobile'=>$mobile_phone, 'telephone'=>$telephone);
			 
			 
			 
			 	 $this->attendance_model->read_employee_csv($excel_data);
			 
			 $rowsnow+=1;
		 
			 
			 //print_r($excel_data);
			 
		
			  
			   //echo $rowsnow;
			  
		
	  
 }
      
      

			//unlink('././uploads/'.$file_name); //File Deleted After uploading in database .			 
             //redirect(base_url() . "put link were you want to redirect");
			 
			
	           
			// }	
			
			
			echo $rowsnow;
		
		
		
	}


public function schedules(){

		$data['schedules']=$this->schedules;
		$data['username']=$this->username;
		$data['checks']=$this->checks;
		$data['facilities']=$this->attendance_model->get_facility();
		$this->load->view('schedules',$data);

}


	
public function add_schedule(){


$result=$this->attendance_model->add_schedules();

echo $result;


}

	
public function delete_schedule($id){


$result=$this->attendance_model->delete_schedules($id);

echo $result;


}	
	
	

public function staff(){

		$data['staffs']=$this->staff;
		$data['username']=$this->username;
		$data['checks']=$this->checks;
		$data['facilities']=$this->attendance_model->get_facility();
		$this->load->view('staff',$data);

}



public function upload_rosta(){

		$data['username']=$this->username;
		$data['checks']=$this->checks;
		$data['facilities']=$this->attendance_model->get_facility();
		$this->load->view('upload_rosta',$data);

}


public function machinedata(){

		$data['username']=$this->username;
		$data['checks']=$this->checks;
		$data['facilities']=$this->attendance_model->get_facility();
		//$this->load->view('machine_upload',$data);

}
		
	

	



	//generating a rota upload template for download




public function excel_template()
 {
					$this->load->library('excel');

	                $this->excel->setActiveSheetIndex(0);

	                //name the worksheet

	                $this->excel->getActiveSheet()->setTitle('Rota_template');

	                //set cell A1 content with some text



$this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);




$this->excel->getActiveSheet()->getStyle("A1:D1")->applyFromArray(array("font" => array("bold" => true)));


	              

	                $this->excel->getActiveSheet()->setCellValue('A1', 'Person ID');

	                $this->excel->getActiveSheet()->setCellValue('B1', 'Names');

	                $this->excel->getActiveSheet()->setCellValue('C1', 'Duty Date');

	                $this->excel->getActiveSheet()->setCellValue('D1', 'Duty');

	       
	                //retrive contries table data

	                $rs = $this->attendance_model->template_data();
	                
	                

	                $exceldata="";
	                
	                $month_days=date('t');

	        foreach ($rs as $row){

for($y=0;$y<$month_days;$y++){  //repeat each person for the no. of days in a month
    
	                $exceldata[] = $row;
}
	                

	        }

	                //Fill data


	 $this->excel->getActiveSheet()->fromArray($exceldata, null,"A2");
	 

	                

	                
  

	                $filename='rosta_template.xls'; //save our workbook as this file name

	                header('Content-Type: application/vnd.ms-excel'); //mime type
	                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

	                header('Cache-Control: max-age=0'); //no cache

	  

	                //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)

	                //if you want to save it as .XLSX Excel 2007 format

	                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 

	                //force user to download the Excel file without writing it to server's HD

	                $objWriter->save('php://output');

	                  

	    }



public function upload_rota()
	{


	
				$facility=$this->facility;
		
 				$config['upload_path']          = FCPATH.'uploads/';

                $config['allowed_types']        = 'csv|xls|xlsx';
                $config['max_size']             = 20000;
                $config['file_name']            ="rota_file";

                //$file_name=$config['file_name'];

                $this->load->library('upload', $config);

               $this->upload->initialize($config);

                if ( ! $this->upload->do_upload('rota'))
                {
                        $error = array('error' => $this->upload->display_errors());

                        echo $error['error'];
                }
                else
                {
                        
                       $file_name= $this->upload->data('file_name');  

                        
                
		$this->load->library('excel');
		
		$type = PHPExcel_IOFactory::identify('uploads/'.$file_name);
		
		$objReader=PHPExcel_IOFactory::createReader($type);     //For excel 2003 
		
          //Set to read only
          $objReader->setReadDataOnly(true); 		  
        //Load excel file
		 $objPHPExcel=$objReader->load(FCPATH.'uploads/'.$file_name);	
		 
         $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
         $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);                
        
	$people=$this->attendance_model->get_employees();

	$schedulez=$this->attendance_model->get_schedules();

$schedules=array();//holds sched id, letter pair

foreach ($schedulez as $schedule)
{

$schedules["'".$schedule['letter']."'"]=$schedule['schedule_id'];

}
		
		$person_ids=array();
		$facility_ids=array();
		
		foreach($people as $person)
		{
			array_push($person_ids,$person['ihris_pid']);
			
		}
		
		
		$rowsnow=0;
		 //loop from first data untill last data
         for($i=2;$i<=$totalrows;$i++)
          {
              
			  
			  $person_id= $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();	//col 1		
         	  $name= $objWorksheet->getCellByColumnAndRow(1,$i)->getValue(); //Excel Column 2
         	  
         	 
			  $fro = $objWorksheet->getCellByColumnAndRow(2,$i);
			  
$from= $fro->getValue();

$fromy = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($from)); 

$oneday="+1 day";//1 day

$sdate = strtotime($fromy);

$too = date('Y-m-d',strtotime($oneday, $sdate)); //add one to from to get end of duty
			  
			  
 $duty_letter=$objWorksheet->getCellByColumnAndRow(3,$i)->getValue(); //Excel Column 5 


$duty=$schedules["'".$duty_letter."'"];// schedule_id depending on duty letter

$entry=$fromy.$person_id;

	
	if((in_array($person_id,$person_ids)) and ($fromy!="" and $fromy!="1970")){
		
		 $excel_data=array('entry_id'=>$entry,'facility_id'=>$facility,'ihris_pid'=>$person_id,'schedule_id'=>$duty,'color'=>'#000','duty_date'=>$fromy,'end'=>$too,'allDay'=>'true');
			 
			 
		 $this->attendance_model->save_upload($excel_data);//insertion
			 
			 $rowsnow+=1;
		 
			 //print_r($excel_data);
		  
			  
			  }
	  
	  
 }
      
      

			//unlink('././uploads/'.$file_name); //File Deleted After uploading in database .			 
             //redirect(base_url() . "put link were you want to redirect");
			 
				
		
		



}//else for upload



}



function getDistricts(){
    
    $districts=$this->attendance_model->get_districts();
    
    $districts_array=array();
    
    foreach($districts as $district):
        
        $districts_array[$district['district']]=$district['district_id'];
        
        
        endforeach;
    
    //print_r($districts_array);
    
    return $districts_array;
    
}


public function manualUpload(){
    
    
    	        $config['upload_path'] = FCPATH.'uploads/';

                $config['allowed_types']        = 'csv|xls|xlsx';
                $config['max_size']             = 2000000;
                $config['file_name']            ="hrisdata_file";

                //$file_name=$config['file_name'];
                
                $districts=$this->attendance_model->get_districts();
                
                $prefix=$this->input->post('category');
                

                $this->load->library('upload', $config);

               $this->upload->initialize($config);

                if (!$this->upload->do_upload('ihrisdata'))
                {
                        $error =$this->upload->display_errors();


						$this->session->set_flashdata('alert',$error);
			
                        redirect(base_url() . "admin/settings");
                }
                else
                {
                        
        
        $file_name= $this->upload->data('file_name'); 

       
       	$this->load->library('excel');
		
		$type = PHPExcel_IOFactory::identify('uploads/'.$file_name);
		
		$objReader=PHPExcel_IOFactory::createReader($type);     //For excel 2003 
		
          //Set to read only
          $objReader->setReadDataOnly(true); 		  
        //Load excel file
		$objPHPExcel=$objReader->load(strip_tags(FCPATH.'uploads/'.$file_name));	
		
		 
         $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
         $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);  
         
         
         
         //truncate table if data file contains data
         if($totalrows>0){
             
             $this->db->query("delete from ihrisdata where ihris_pid like '%$prefix%'");
            // $this->db->query("truncate ihrisdata");
             
         }
        

    $districts=$this->getDistricts();
		
		$rowsnow=0;
		 //loop from first data untill last data
         for($i=2;$i<=$totalrows;$i++)
          {
              
			  
			  $person_id= $prefix.$objWorksheet->getCellByColumnAndRow(0,$i)->getValue();
			  $district= $objWorksheet->getCellByColumnAndRow(2,$i)->getValue(); //Excel Column 2
			  			
			  			
              $district_id= $districts[$district]; // district name from built array
              
			  
			  $nin=$objWorksheet->getCellByColumnAndRow(3,$i)->getValue(); //Excel Column 3 parent fname
			  $ipps=$objWorksheet->getCellByColumnAndRow(4,$i)->getValue(); //Excel Column 4 
			  $facility_type_id=$objWorksheet->getCellByColumnAndRow(5,$i)->getValue(); //Excel Column 5 
			  $facility_id=$prefix.$objWorksheet->getCellByColumnAndRow(6,$i)->getValue(); //Excel Column 6
			  $facility=$objWorksheet->getCellByColumnAndRow(7,$i)->getValue(); //Excel Column 7 
			  $department=$objWorksheet->getCellByColumnAndRow(8,$i)->getValue(); //Excel Column 8 
			  $job_id=$objWorksheet->getCellByColumnAndRow(9,$i)->getValue(); //Excel Column 9 
			  $job_title=$objWorksheet->getCellByColumnAndRow(10,$i)->getValue(); //Excel Column 11 
			  $surname=$objWorksheet->getCellByColumnAndRow(11,$i)->getValue(); //Excel Column 12 
			  $firstname=$objWorksheet->getCellByColumnAndRow(12,$i)->getValue(); //Excel Column 13 
			  $othername=$objWorksheet->getCellByColumnAndRow(13,$i)->getValue(); //Excel Column 14 
			  $mobile_phone=$objWorksheet->getCellByColumnAndRow(14,$i)->getValue(); //Excel Column 15 
			  $telephone=$objWorksheet->getCellByColumnAndRow(15,$i)->getValue(); //Excel Column 16 
			  
//
			    
			 $excel_data=array('ihris_pid'=>$person_id,'district_id'=>$district, 'district'=>$district ,'nin'=>$nin ,'ipps'=>$ipps , 'facility_type_id'=>$facility_type_id
			 , 'facility_id'=>$facility_id,'facility'=>$facility,'department'=>$department, 'job_id'=>$job_id, 'job'=>$job_title, 'surname'=>$surname
			 , 'firstname'=>$firstname, 'othername'=>$othername, 'mobile'=>$mobile_phone, 'telephone'=>$telephone);
			 
			 
			 
			 	 $this->attendance_model->read_employee_csv($excel_data);
			 
			 $rowsnow+=1;
		 
			 
			 //print_r($excel_data);
			 
		
			  
			   //echo $rowsnow;
			  
		
	  
 }
      
      

			unlink(FCPATH.'uploads/'.$file_name); //File Deleted After uploading
			
			$alert='<div class="alert alert-info alert-dismissable"><a href="" class="pull-right" data-dismiss="modal">&times;</a><h5>'.$rowsnow.'</h5> records have been imported into the database</div>';
			
			$this->session->set_flashdata('alert',$alert);
			
             redirect(base_url() . "admin/settings");
		
		         
                       
                }//else for upload error
                       
}//end of manualUpload
    
       
	public function fingerprints(){
		
	    
	    $search_data=$this->input->post();
	    
	    	if($search_data){
		$data['name']=$search_data['name'];
		}
		
		else{
		    
	
		$data['name']="";
		    
		}
	    
	    $config=array();
	    $config['base_url']=base_url()."attendance/fingerprints";
	    $config['total_rows']=$this->attendance_model->count_fingerprints();
	    $config['per_page']=20; //records per page
	    $config['uri_segment']=3; //segment in url
	    
	    //pagination links styling
	    $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
    
    
    
        $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
    
    
        $config['next_link'] = '<i class="fa fa-long-arrow-right"></i>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        
	    $this->pagination->initialize($config);
	    
	    $page=($this->uri->segment(3))? $this->uri->segment(3):0; //default starting point for limits
	    
	    $data['fingerprints']=$this->attendance_model->fingerprints($config['per_page'],$page,$search_data);
	    
	  
		$data['facilities']=$this->attendance_model->get_facility();
		
	
		
		$data['links']=$this->pagination->create_links();
		
	    $this->load->view('fingerprints',$data);
	    
	}
    public function deleteFinger() {
		$id = $this->uri->segment(3);
		$id = urldecode($id);
		$this->attendance_model->deleteFinger($id);
		redirect('/attendance/fingerprints');
	}
    
	public function timeLogReport(){
		
	    
	    $search_data=$this->input->post();
	    
	    	if($search_data){
				
		$data['from']=$search_data['date_from'];
		$data['to']=$search_data['date_to'];
		$data['name']=$search_data['name'];
		}
		
		else{
		    
		$data['from']=date('Y-m-').'01';
		$data['to']=date('Y-m-d');
		$data['name']="";
		    
		}
	    
	    $config=array();
	    $config['base_url']=base_url()."attendance/timeLogReport";
	    $config['total_rows']=$this->attendance_model->count_timelogs();
	    $config['per_page']=20; //records per page
	    $config['uri_segment']=3; //segment in url
	    
	    //pagination links styling
	    $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
    
    
    
        $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
    
    
        $config['next_link'] = '<i class="fa fa-long-arrow-right"></i>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        
	    $this->pagination->initialize($config);
	    
	    $page=($this->uri->segment(3))? $this->uri->segment(3):0; //default starting point for limits
	    
	    $data['timelogs']=$this->attendance_model->fetchTimeLogs($config['per_page'],$page,$search_data);
	    
	   	$data['username']=$this->username;
		$data['facilities']=$this->attendance_model->get_facility();
		
	
		
		$data['links']=$this->pagination->create_links();
		
	    $this->load->view('timelogs_report',$data);
	    
	}
	
 public function importMachineCSv(){
     
      $prefix=$this->input->post('category');
      
      
      $filename=$_FILES["machine_file"]["tmp_name"];
 
if($_FILES["machine_file"]["size"] > 0)  //check whether file has data
    {

$file = fopen($filename, "r");


$count = 0;  

$allData=array();

$rowsnow=0;

while (($machineData = fgetcsv($file, 10000, ",")) !== FALSE)
{
  
    $count++;                                      // add this line
if($count>1){
    
    
$person_id= $prefix."person|".$machineData[0];

///////start processing data

$clockin='';         
$clockout='';  
	 
	 //date_default_timezone_set("Africa/Kampala");

$time_in=$machineData[5]; //rawtimein

$time_out=$machineData[6]; //raw timeout

if(!empty($time_in)){
    
$time_in=date_create($machineData[5]); //rawtimein

$clockin= date_format($time_in,"H:i"); //converted clock in time

}
else{
    $clockin=NULL;
    
}



if(!empty($time_out)){
$time_out=date_create($machineData[6]);
$clockout=date_format($time_out,"H:i"); //converted clock out time

}
else{
$clockout=NULL;
}
//1527552000

	 if($prefix!=='person|'){ 
	     
$facility_id=$prefix.$machineData[4]; //Excel Column 4 ;
	 }
	 
	 else{
$facility_id=$machineData[4]; //Excel Column 4 ;
	 }
	     

$mydate=date_create($machineData[7]);

$date=date_format($mydate,"Y-m-d"); //Excel Column 7

	  
	  $entry_id=$date.$person_id;


			    
			 $excel_data=array('ihris_pid'=>$person_id,'time_in'=>$clockin, 'time_out'=>$clockout ,'date'=>$date ,'entry_id'=>$entry_id, 'facility_id'=>$facility_id);
			 
			 
			 
			 	//disabled $insert=$this->attendance_model->read_machine_csv($excel_data);
			
			 if($insert){
			     
			     	 $rowsnow+=1;
			     	 }
		


////////
    
// array_push($allData,$machineData);
 
}

}//end while



	if($rowsnow>0){
			$msg="<font color='green'>".$rowsnow. "records Imported successfully</font>";
		   	}
			else{
			    
			    $msg="<font color='red'>Import unsuccessful</font>";
			    
			}
               
    

}//end file size check

else{
    
    
    $msg="<font color='red'>No Data in FIle</font>";
    
}


   
                
                $this->session->set_flashdata("msg",$msg);
                
                redirect(base_url() . "attendance/machinedata");

}




public function machineCsv($from,$to)
 {
					$this->load->library('excel');

	                $this->excel->setActiveSheetIndex(0);

	                //name the worksheet

	                $this->excel->getActiveSheet()->setTitle('MachineCsv');

	                //set cell A1 content with some text



$this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);


//bold header
$this->excel->getActiveSheet()->getStyle("A1:F1")->applyFromArray(array("font" => array("bold" => true)));


	              

    $this->excel->getActiveSheet()->setCellValue('A1', 'NAME');

    $this->excel->getActiveSheet()->setCellValue('B1', 'FACILITY');

    $this->excel->getActiveSheet()->setCellValue('C1', 'TIME IN');

    $this->excel->getActiveSheet()->setCellValue('D1', 'TIME OUT');
   
    $this->excel->getActiveSheet()->setCellValue('E1', 'HOURS WORKED');
    
    $this->excel->getActiveSheet()->setCellValue('F1', 'DATE');

	       
	                //retrive contries table data

	                $rs = $this->attendance_model->getMachineCsvData($from,$to);
	                
	                
	   if(count($rs)<1){
	                    //no data from db
	                    echo "<h1 style='margin-top:20%; color:red;'><center> NO DATA IN THIS RANGE</center></h1>";
	                    
	                }
	                
else{ //we have some excel data from db
	                $exceldata="";
	        

	        foreach ($rs as $row){
	    
	                
	                $exceldata[] = $row;
             

	        }


//print_r( $rs);
	                //Fill data


	 $this->excel->getActiveSheet()->fromArray($exceldata, null,"A2");
	 

	                

	                
  

	                $filename='Biometric_report_data.csv'; //save our workbook as this file name

	                header('Content-Type: text/csv'); //mime type
	                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

	                header('Cache-Control: max-age=0'); //no cache
	                

	  
	                //if you want to save it as .XLSX Excel 2007 format

	                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'CSV'); 

	                //force user to download the Excel file without writing it to server's HD

	                $objWriter->save('php://output');
	                
}
    

	    }




// Audit/Reports data for Mobile app

public function auditupload(){
    
    		$data['username']=$this->username;
    		$data['checks']=$this->checks;
    		$data['facilities']=$this->attendance_model->get_facility();
    		$this->load->view('audit_upload',$data);
    
    }
	

	    
public function importEstabCSv(){

$filename=$_FILES["esta_file"]["tmp_name"];
 
if($_FILES["esta_file"]["size"] > 0)  //check whether file has data
    {

$file = fopen($filename, "r");


$count = 0;  

$allData=array();

$rowsnow=0;

while (($machineData = fgetcsv($file, 10000, ",")) !== FALSE)
{
  
  $count++;                                      // add this line
if($count>1){

 //print_r($machineData[0]);
  
///////start processing data


$districtid=$machineData[0]; 
$district=$machineData[1];
$region=$machineData[2];
$facility_id=$machineData[3];
$facility=$machineData[4];
$facility_type_id=$machineData[5];
$facility_type=$machineData[6];
$job_id=$machineData[7];
$job=$machineData[8];
$approved=$machineData[9];
$salary_grade=$machineData[10];
$filled=$machineData[11];
$excess_vacant=$machineData[12];

			    
			 $excel_data=array(
			 'district_id'=>$districtid,
			 'district'=>$district, 'region'=>$region ,
			 'facility_id'=>$facility_id ,
			 'facility'=>$facility,
			 'facility_type_id'=>$facility_type_id,
			 'facility_type'=>$facility_type,
			 'job_id'=>$job_id,
			 'job'=>$job,
			 'approved'=>$approved,
			 'salary_grade'=>$salary_grade,
			 'filled'=>$filled,
			 'excess_vacant'=>$excess_vacant
			 );
			 

			 	 $insert=$this->attendance_model->read_establishment_csv($excel_data);
			
			 if($insert){
			     
			     	 $rowsnow+=1;
			     	 }


////////
    
// array_push($allData,$machineData);
 
}

}//end while



	if($rowsnow>0){
			$msg="<font color='green'>".$rowsnow. "records Imported successfully</font>";
		   	}
			else{
			    
			    $msg="<font color='red'>Import unsuccessful</font>";
			    
			}
               
    

}//end file size check

else{
    
    
    $msg="<font color='red'>No Data in FIle</font>";
    
}


   
                
                $this->session->set_flashdata("msg",$msg);
                
                redirect(base_url() . "attendance/auditupload");

}


public function importAttCSv(){

      $filename=$_FILES["att_file"]["tmp_name"];
 
if($_FILES["att_file"]["size"] > 0)  //check whether file has data
    {

$file = fopen($filename, "r");


$count = 0;  

$allData=array();

$rowsnow=0;

while (($machineData = fgetcsv($file, 10000, ",")) !== FALSE)
{
  
    $count++;                                      // add this line
if($count>1){


///////start processing data



$districtid=$machineData[0]; 
$district=$machineData[1];
$personid=$machineData[2];
$job_id=$machineData[10];
$job=$machineData[11];
$surname=$machineData[3];
$firstname=$machineData[4];
$salary=$machineData[5];
$facility_type_id=$machineData[6];
$facility_type=$machineData[7];
$facility_id=$machineData[8];
$facility=$machineData[9];
$absent=$machineData[12];
$leavedays=$machineData[13];
$offduty=$machineData[14];
$request=$machineData[15];
$present=$machineData[16];
$month=$machineData[17];
$year=$machineData[18];

			    
			 $excel_data=array(
			 'district_id'=>$districtid,
			 'district'=>$district,
			 'person_id'=>$personid ,
			 'job_id'=>$job_id,
			 'job'=>$job,
			 'surname'=>$surname,
			 'firstname'=>$firstname,
			 'salary'=>$salary,
			 'facility_type_id'=>$facility_type_id,
			 'facility_type'=>$facility_type,
			 'facility'=>$facility,
			 'absent'=>$absent,
			 'leavedays'=>$leavedays,
			 'offduty'=>$offduty,
			 'request'=>$request,
			 'present'=>$present,
			 'month'=>$month,
			 'year'=>$year
			 );

			 //print_r($excel_data);
			 
			 
			 	 $insert=$this->attendance_model->read_attendance_csv($excel_data);
			
			 if($insert){
			     
			     	 $rowsnow+=1;
			     	 }


////////
    
// array_push($allData,$machineData);
 
}

}//end while



	if($rowsnow>0){
			$msg="<font color='green'>".$rowsnow. "records Imported successfully</font>";
		   	}
			else{
			    
			    $msg="<font color='red'>Import unsuccessful</font>";
			    
			}
               
    

}//end file size check

else{
    
    
    $msg="<font color='red'>No Data in FIle</font>";
    
}


   
                
                $this->session->set_flashdata("msg",$msg);
                
                redirect(base_url() . "attendance/auditupload");

}







}//end of class

