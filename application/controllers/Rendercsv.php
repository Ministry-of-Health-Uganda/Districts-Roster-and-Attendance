<?php 

class Rendercsv extends MX_Controller{
   //district data share
    public function renderdutyCsv($date=FALSE)
  
    {
	if(!empty($date)){
         $ddate=$date;
       }
       else{
       $ddate=date('Y-m', strtotime('-1 months'));
        }
	ini_set('max_execution_time',0);
		ignore_user_abort(true);
		$filename='dutydist_summary.csv';
        
        $this->db->select("person_id,dutydate,wdays,offs,mleave,other");
        $this->db->like("dutydate","$ddate","after");
        $this->db->from('dutysummary');
        $qry= $this->db->get();
        $tbdata=$qry->result_array();

		$file = fopen('/home/mohhr/districtshares/'.$filename, 'w+');  

		$i=0;

		foreach ($tbdata as $data) {
			fputcsv($file, $data);

			$i++;
		}
		fclose($file);
    echo $msg=  'CSV for rosta generated Successfully';
	//print_r($tbdata);
	return $tbdata;

	}
	public function renderattCsv($date=FALSE)
    { 
                   if(!empty($date)){
         $ddate=$date;
       }
             else{
              $ddate=date('Y-m', strtotime('-1 months'));
        }	
		ini_set('max_execution_time',0);
		ignore_user_abort(true);
		$filename='attdist_summary.csv';
        
        $this->db->select('ihris_pid,rdate,present,offduty,official,leaves');
        $this->db->like("rdate","$ddate","after");
        $this->db->from('att_summary');
        $qry= $this->db->get();
        $tbdata=$qry->result_array();

		$file = fopen('/home/mohhr/districtshares/'.$filename, 'w+');  

		$i=0;

		foreach ($tbdata as $data) {
			fputcsv($file, $data);

			$i++;
		}
		fclose($file);
        echo $msg = 'CSV for attendance generated Successfully';
   // print_r($tbdata);
   //return $tbdata;

	}


	///municiaplity data share
	Public function renderattmunCsv()
    {
		ini_set('max_execution_time',0);
		ignore_user_abort(true);
		$filename='attmun_summary.csv';
        
        $this->db->select('ihris_pid,rdate,present,offduty,official,leaves');
        $this->db->from('attmun_summary');
        $qry= $this->db->get();
        $tbdata=$qry->result_array();

		$file = fopen('/home/mohhr/munshares/'.$filename, 'w+');  

		$i=0;

		foreach ($tbdata as $data) {
			fputcsv($file, $data);

			$i++;
		}
		fclose($file);
        echo $msg = 'CSV for Municipal attendance generated Successfully';
   // print_r($tbdata);
   //return $tbdata;

	}
	Public function renderdutymunCsv()
    {
		ini_set('max_execution_time',0);
		ignore_user_abort(true);
		$filename='dutymun_summary.csv';
        
		$this->db->select('person_id,dutydate,wdays,offs,mleave,other');
        $this->db->from('dutymunsummary');
        $qry= $this->db->get();
        $tbdata=$qry->result_array();

		$file = fopen('/home/mohhr/munshares/'.$filename, 'w+');  

		$i=0;

		foreach ($tbdata as $data) {
			fputcsv($file, $data);

			$i++;
		}
		fclose($file);
        echo $msg = 'CSV for Municipal Rosta generated Successfully';
   // print_r($tbdata);
   //return $tbdata;

	}


	

}
?>
