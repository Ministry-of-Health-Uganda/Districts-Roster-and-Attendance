<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rosta extends MX_Controller
{

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();

		$this->load->model('rosta_model');
		$this->load->model('attendance_model');


		$this->username = $this->session->userdata['names'];

		$this->uid = $this->session->userdata['uid'];

		$this->checks = $this->rosta_model->checks();

		$this->departments = $this->rosta_model->getDepartments();

		$this->watermark = FCPATH . "assets/images/watermark.png";
	}



	public function switches()
	{


		$facilities = $this->attendance_model->get_facility();

		$newArray = array();

		$switches = "";

		foreach ($facilities as $val) {

			$newKey = $val['district_id'];

			$newArray[$newKey][] = $val;

			$switches . "<optgroup label='" . $newKey . "'>";

			for ($i = 0; $i < count($newArray[$newKey]); $i++) {

				$switches . "<option value='" . $newArray[$newKey][$i]['facility_id'] . "'>" . $newArray[$newKey][$i]['facility'] . "</option>";
			}

			$switches . "</optgroup>";
		}

		return $switches;
	}


	/*Home page Calendar view  */
	public function index()
	{
		$data['schedules'] = $this->attendance_model->get_schedules("r");
		$data['workers'] = $this->attendance_model->get_employees();
		$data['username'] = $this->username;
		$data['checks'] = $this->checks;

		$data['facilities'] = $this->attendance_model->get_facility();

		// $data['switches']=$this->switches();
		$this->load->view('rosta', $data);
	}

	/*Get all Events */

	public function getEvents()
	{
		$result = $this->rosta_model->getEvents();
		echo json_encode($result);
	}
	/*Add new event */
	public function addEvent()
	{


		$result = $this->rosta_model->addEvent();

		echo $result;
	}
	/*Update Event */
	public function updateEvent()
	{

		$result = $this->rosta_model->updateEvent();
		echo $result;
	}
	/*Delete Event*/
	public function deleteEvent()
	{
		$result = $this->rosta_model->deleteEvent();
		echo $result;
	}
	public function dragUpdateEvent()
	{

		$result = $this->rosta_model->dragUpdateEvent();
		echo $result;
	}

	public function tabular()
	{

		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$department = $this->input->post('department');
		$facility = $_SESSION['facility'];



		if ($month != "") {

			$data['month'] = $month;

			$data['year'] = $year;
		} else {

			$data['month'] = date('m');

			$data['year'] = date('Y');
		}

		if ($department) {

			$data['depart'] = $department;
		}


		$date = $data['year'] . '-' . $data['month'];
		$data['schedules'] = $this->attendance_model->get_schedules("r");
		$data['username'] = $this->username;
		$data['checks'] = $this->checks;
		$data['departments'] = $this->departments;
		//refresh cache for new months


		// $query = $this->db->query("SELECT result from cached_result where form like'dutyroster%' and date like'$date%' and facility='$facility'");
		// $count = $query->num_rows();
		//print_r($count);
		// if ($count == 0) {
		 $data['duties'] = $this->rosta_model->fetch_tabs($date);

		// 	$insert = array(
		// 		'form' => 'dutyroster' . $date . $_SESSION['facility'],
		// 		'facility' => $_SESSION['facility'],
		// 		'date' => $date,

		// 		'result' => json_encode($data['duties'])

		// 	);

		// 	$this->db->insert('cached_result', $insert);
		// } else {

		// 	$output = $query->result();
		// 	$final = $output[0]->result;
		// 	$data['duties'] = json_decode($final, true);
		// 	$this->updateCache($date);
		// }


		$data['matches'] = $this->rosta_model->matches();

		$data['tab_schedules'] = $this->rosta_model->tab_matches();

		//$data['facilities'] = $this->attendance_model->get_facility();

		//$data['switches'] = $this->switches();
		//print_r($data);






		$this->load->view('tab_duty', $data);
	}
	//update cache for rosta
	public function updateCache($date)
	{
		$prevmonth = date('Y-m', strtotime('-1 month'));
		$current = date('Y-m');
		if (($date >= $current) || ($date == $prevmonth)) {
			//$new_data = $this->rosta_model->fetch_tabs($date);
			$form = 'dutyroster' . $date . $_SESSION['facility'];

			// $insert = array(
			// 	'form' => $form,
			// 	'facility' => $_SESSION['facility'],
			// 	'date' => $date,

			// 	'result' => json_encode($new_data)

			// );
			$this->db->query("DELETE FROM `cached_result` WHERE `cached_result`.`form` = '$form'");
			//$this->db->where('form', $form);
		}
	}

	public function objectToArray($object)
	{
		if (!is_object($object) && !is_array($object)) {
			return $object;
		}
		return array_map($this->objectToArray, (array) $object);
	}



	public function fetch_report()
	{



		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$department = $this->input->post('department');


		if ($month != "") {

			$data['month'] = $month;

			$data['year'] = $year;
		} else {

			$data['month'] = date('m');

			$data['year'] = date('Y');
		}

		if ($department != '') {

			$data['depart'] = $department;
		} else {

			$data['depart'] = '';
		}

		$date = date('Y') . "-" . date('m');

		$data['username'] = $this->username;
		$data['departments'] = $this->departments;

		$data['duties'] = $this->rosta_model->fetch_report($date);

		$data['matches'] = $this->rosta_model->matches();
		$data['checks'] = $this->checks;

		$data['facilities'] = $this->attendance_model->get_facility();
		//$data['switches']=$this->switches();

		$this->load->view('duty_report', $data);
	}




	public function print_report($date)
	{

		$data['dates'] = $date;

		$this->load->library('ML_pdf');

		$data['username'] = $this->username;
		$data['checks'] = $this->checks;

		$data['duties'] = $this->rosta_model->fetch_report($date);

		$data['matches'] = $this->rosta_model->matches();

		$html = $this->load->view('printable', $data, true);

		$fac = $data['duties'][0]['facility'];
		$date = date('F-Y', strtotime($data['duties'][0]['day1']));

		$filename = $fac . "_rota_report_" . $date . ".pdf";


		ini_set('max_execution_time', 0);
		$PDFContent = mb_convert_encoding($html, 'UTF-8', 'UTF-8');

		$this->ml_pdf->pdf->SetWatermarkImage($this->watermark);
		$this->ml_pdf->pdf->showWatermarkImage = true;

		date_default_timezone_set("Africa/Kampala");
		$this->ml_pdf->pdf->SetHTMLFooter("Printed / Accessed on: <b>" . date('d F,Y h:i A') . "</b>");

		$this->ml_pdf->pdf->SetWatermarkImage($this->watermark);
		$this->ml_pdf->showWatermarkImage = true;



		ini_set('max_execution_time', 0);

		$this->ml_pdf->pdf->WriteHTML($PDFContent); //ml_pdf because we loaded the library ml_pdf for landscape format not m_pdf

		//download it D save F.
		$this->ml_pdf->pdf->Output($filename, 'I');
	}


	public function summary()
	{


		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$department = $this->input->post('department');



		//for a dynamic one

		if ($month != "") {

			$data['month'] = $month;

			$data['year'] = $year;
		} else {

			$data['month'] = date('m');

			$data['year'] = date('Y');
		}

		if ($department) {

			$data['depart'] = $department;
		}

		$date = $data['year'] . "-" . $data['month'];

		$data['departments'] = $this->departments;
		$data['username'] = $this->username;
		$data['dates'] = $this->$date;
		$data['facilities'] = $this->attendance_model->get_facility();
		$data['sums'] = $this->rosta_model->fetch_summary($date);

		$this->load->view('summary_report', $data);
	}





	public function attendance_summary()
	{


		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$department = $this->input->post('department');



		//for a dynamic one

		if ($month != "") {

			$data['month'] = $month;

			$data['year'] = $year;
		} else {

			$data['month'] = date('m');

			$data['year'] = date('Y');
		}

		if ($department) {

			$data['depart'] = $department;
		}

		$date = $data['year'] . "-" . $data['month'];

		$data['departments'] = $this->departments;
		$data['username'] = $this->username;
		$data['dates'] = $date;
		$data['facilities'] = $this->attendance_model->get_facility();
		$data['sums'] = $this->rosta_model->attendance_summary($date);

		$this->load->view('attendance_summary', $data);
	}

	public function fully()
	{
		//This function was for testing only
		/*   $month=FALSE;
	    $year=FALSE;
	    $facility=FALSE;*/

		if ($this->input->post()) {

			$month = $this->input->post('month');
			$year = $this->input->post('year');
			$district = $this->input->post('district');
			$facility = $this->input->post('facility');
		}

		$postdata = $this->input->post();
		$date = $year . "-" . $month;

		print_r($postdata);

		$att_sums = $this->rosta_model->full_attendance_summary($date, $district, $facility);

		$ro_sums = $this->rosta_model->full_summary($date, $district, $facility);


		$merged = $this->mergeData($att_sums, $ro_sums);

		print_r($att_sums);

		echo "<hr>";
		print_r($ro_sums);

		echo "<hr>";
		print_r($merged);
	}

	public function fullSummary()
	{




		if ($this->input->post()) {

			$month = $this->input->post('month');
			$year = $this->input->post('year');
			$district = $this->input->post('district');
			$facility = $this->input->post('facilit');
		} else {
			$month = FALSE;
			$year = FALSE;
			$facility = FALSE;
		}


		if (!$month) {
			$m = date('m');

			$month = $m;

			$year = date('Y');
		}

		$postdata = $this->input->post();

		$date = $year . "-" . $month;

		$this->load->library('pagination');
		$config = array();
		$config['base_url'] = base_url() . "rosta/fullSummary";
		$config['total_rows'] = 20;
		//$this->rosta_model->count_summary($date,$district,$facility);
		$config['per_page'] = 10; //records per page
		$config['uri_segment'] = 3; //segment in url

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

		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0; //default starting point for limits



		$data['links'] = $this->pagination->create_links();
		$data['username'] = $this->username;
		$data['dates'] = $date;
		$data['month'] = $month;

		$data['year'] = $year;

		$data['currentfacility'] = $facility;
		$data['thisdistrict'] = $district;


		ini_set('max_execution_time', 0);
		$att_sums = $this->rosta_model->full_attendance_summary(20, 0, $date, $district, $facility);

		ini_set('max_execution_time', 0);
		$ro_sums = $this->rosta_model->full_summary(20, 0, $date, $district, $facility);


		$merged = $this->mergeData($att_sums, $ro_sums);

		$data['sums'] = $merged;

		$this->load->view('full_summary', $data);
	}


	//function below merges attendance summary with rota summary
	public function mergeData($att_sums, $ro_sums)
	{

		for ($i = 0; $i < count($att_sums); $i++) {

			$att_sums[$i]['N'] = $ro_sums[$i]['N'];
			$att_sums[$i]['OR'] = $ro_sums[$i]['O'];
			$att_sums[$i]['S'] = $ro_sums[$i]['S'];
			$att_sums[$i]['A'] = $ro_sums[$i]['A'];
			$att_sums[$i]['M'] = $ro_sums[$i]['M'];
			$att_sums[$i]['Z'] = $ro_sums[$i]['Z'];
			$att_sums[$i]['D'] = $ro_sums[$i]['D'];
			$att_sums[$i]['E'] = $ro_sums[$i]['E'];
		}

		return $att_sums;
	}

	//full summary csv
	public function fullSumCsv($valid_range, $district = FALSE, $facility = FALSE)
	{

		$att_sums = $this->rosta_model->full_attendance_summary($valid_range, $district, $facility);
		$ro_sums = $this->rosta_model->full_summary($valid_range, $district, $facility);

		$merged = $this->mergeData($att_sums, $ro_sums);

		$sums = $merged;

		$fp = fopen('php://memory', 'w');


		array_unshift($sums, $heading);
		$i = 0;

		$heading = array(
			'PERSON ID', 'DATE', 'DSD', 'DSO', 'DSL', 'DSZ', 'R', 'O', 'P', 'L'
		);

		foreach ($sums as $sum) {
			//if we're not on first row
			if ($i > 0) {
				$data = array();
				$data['IHRIS'] = $sum['person_id'];
				$data['PERIOD'] = date("d-m-Y", strtotime($valid_range . "-01"));
				$data['DSD'] = $sum['D'] + $sum['E'] + $sum['N'];
				$data['DSO'] = $sum['OR'];
				$data['DSL'] = $sum['M'] + $sum['A'] + $sum['S'];
				$data['DSZ'] = $sum['Z'];
				$data['R'] = $sum['R'];
				$data['O'] = $sum['O'];
				$data['P'] = $sum['P'];
				$data['L'] = $sum['L'];
			}

			//if we're on first row
			else {

				$data = $heading;
			}

			fputcsv($fp, $data);


			$i++;
		}


		$filename = $valid_range . "full_summary_report.csv";

		// reset the file pointer to the start of the file
		fseek($fp, 0);
		// tell the browser it's going to be a csv file
		header('Content-Type: application/csv');
		// tell the browser we want to save it instead of displaying it
		header('Content-Disposition: attachment; filename="' . $filename . '";');
		// make php send the generated csv lines to the browser
		fpassthru($fp);


		fclose($fp);
	}

	//full summary pdf

	public function print_full_summary($valid_range, $district = FALSE, $facility = FALSE)
	{

		$data['dates'] = $valid_range;

		$this->load->library('ML_pdf');

		$data['username'] = $this->username;

		$data['thisfacility'] = $facility;
		$data['thisdistrict'] = $district;

		ini_set('max_execution_time', 0);
		$att_sums = $this->rosta_model->full_attendance_summary($valid_range, $district, $facility);

		ini_set('max_execution_time', 0);
		$ro_sums = $this->rosta_model->full_summary($valid_range, $district, $facility);

		$merged = $this->mergeData($att_sums, $ro_sums);

		$data['sums'] = $merged;



		$html = $this->load->view('print_full_summary', $data, true);

		$filename = $fac . "comparative_summary_report_" . $date . ".pdf";


		ini_set('max_execution_time', 0);
		$PDFContent = mb_convert_encoding($html, 'UTF-8', 'UTF-8');

		$this->ml_pdf->pdf->SetWatermarkImage($this->watermark);
		$this->ml_pdf->pdf->showWatermarkImage = true;

		date_default_timezone_set("Africa/Kampala");
		$this->ml_pdf->pdf->SetHTMLFooter("Printed / Accessed on: <b>" . date('d F,Y h:i A') . "</b>  Accessed By: <b>" . ucfirst($this->username) . "</b>");

		$this->ml_pdf->pdf->SetWatermarkImage($this->watermark);
		$this->ml_pdf->showWatermarkImage = true;



		ini_set('max_execution_time', 0);
		$this->ml_pdf->pdf->WriteHTML($PDFContent); //ml_pdf because we loaded the library ml_pdf for landscape format not m_pdf

		//download it D save F.
		$this->ml_pdf->pdf->Output($filename, 'I');
	}




	//rosta summary csv
	public function bundleCsv($valid_range)
	{

		$sums = $this->rosta_model->fetch_summary($valid_range);

		//$fp = fopen(FCPATH.'uploads/summary.csv', 'w');

		$fp = fopen('php://memory', 'w');

		//add heading to data
		$heading = array('person' => "Names", 'D' => "Day Duty", 'facility' => ' ', 'E' => "Evening", 'N' => "Night", 'O' => "Off Duty", 'A' => "Annual Leave", 'S' => "Study Leave", 'M' => "Maternity Leave", 'Z' => "Other Leave", 'H' => "");

		array_unshift($sums, $heading);

		foreach ($sums as $sum) {

			fputcsv($fp, $sum);
		}


		$filename = $valid_range . "_summary_report.csv";

		// reset the file pointer to the start of the file
		fseek($fp, 0);
		// tell the browser it's going to be a csv file
		header('Content-Type: application/csv');
		// tell the browser we want to save it instead of displaying it
		header('Content-Disposition: attachment; filename="' . $filename . '";');
		// make php send the generated csv lines to the browser
		fpassthru($fp);


		fclose($fp);
	}


	public function presence()
	{



		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$department = $this->input->post('department');


		//for a dynamic one

		if ($month != "") {

			$data['month'] = $month;

			$data['year'] = $year;
			$data['department'] = $department;
		} else {

			$data['month'] = date('m');

			$data['year'] = date('Y');
		}

		$date = date('Y') . "-" . date('m');

		$data['departments'] = $this->departments;
		$data['username'] = $this->username;

		$data['facilities'] = $this->attendance_model->get_facility();

		$data['duties'] = $this->rosta_model->fetch_report($date);

		$nonworkables = $this->rosta_model->nonworkables();

		$workeddays = $this->rosta_model->workeddays();

		$data['nonworkables'] = $nonworkables;
		$data['workeddays'] = $workeddays;

		$data['matches'] = $this->rosta_model->matches();
		$data['checks'] = $this->checks;
		//$data['switches']=$this->switches();

		$this->load->view('presence_report', $data);
	}





	public function tracker()
	{


		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$department = $this->input->post('department');


		//for a dynamic one

		if ($month != "") {

			$data['month'] = $month;

			$data['year'] = $year;
			$data['department'] = $department;
		} else {

			$data['month'] = date('m');

			$data['year'] = date('Y');
		}

		$date = date('Y') . "-" . date('m');

		$data['username'] = $this->username;
		$data['departments'] = $this->departments;

		$data['duties'] = $this->rosta_model->fetch_report($date);

		$nonworkables = $this->rosta_model->nonworkables();

		$data['facilities'] = $this->attendance_model->get_facility();

		$workeddays = $this->rosta_model->workeddays();

		$data['nonworkables'] = $nonworkables;
		$data['workeddays'] = $workeddays;

		$data['matches'] = $this->rosta_model->matches();
		$data['checks'] = $this->checks;
		//$data['switches']=$this->switches();

		$this->load->view('presence_fm', $data);
	}




	//presense tracking

	public function saveTracker()
	{

		$pid = $_POST['hpid'];
		$date = $_POST['date'];



		$data = array('ihris_pid' => $pid, 'day' => $date);

		//print_r($data);

		$result = $this->rosta_model->saveTracker($data);
		echo $result;
	}






	public function print_presence($date)
	{

		$data['dates'] = $date;

		$this->load->library('ML_pdf');

		$data['username'] = $this->username;
		$data['checks'] = $this->checks;

		$nonworkables = $this->rosta_model->nonworkables();

		$workeddays = $this->rosta_model->workeddays();

		$data['nonworkables'] = $nonworkables;
		$data['workeddays'] = $workeddays;

		$data['duties'] = $this->rosta_model->fetch_report($date);

		$data['matches'] = $this->rosta_model->matches();

		$html = $this->load->view('printabletracker', $data, true);

		$fac = $data['duties'][0]['facility'];
		$date = date('F-Y', strtotime($data['duties'][0]['day1']));

		$filename = $fac . "_tracking_report_" . $date . ".pdf";




		ini_set('max_execution_time', 0);
		$PDFContent = mb_convert_encoding($html, 'UTF-8', 'UTF-8');

		$this->ml_pdf->pdf->SetWatermarkImage($this->watermark);
		$this->ml_pdf->pdf->showWatermarkImage = true;

		date_default_timezone_set("Africa/Kampala");
		$this->ml_pdf->pdf->SetHTMLFooter("Printed / Accessed on: <b>" . date('d F,Y h:i A') . "</b>");

		$this->ml_pdf->pdf->SetWatermarkImage($this->watermark);
		$this->ml_pdf->showWatermarkImage = true;


		ini_set('max_execution_time', 0);
		$this->ml_pdf->pdf->WriteHTML($PDFContent); //ml_pdf because we loaded the library ml_pdf for landscape format not m_pdf

		//download it D save F.
		$this->ml_pdf->pdf->Output($filename, 'I');
	}





	public function actuals()
	{


		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$department = $this->input->post('department');


		//for a dynamic one

		if ($month != "") {

			$data['month'] = $month;

			$data['year'] = $year;
			$data['department'] = $department;
		} else {

			$data['month'] = date('m');

			$data['year'] = date('Y');
		}

		$date = $data['year'] . "-" . $data['month'];

		$data['username'] = $this->username;
		$data['departments'] = $this->departments;

		$data['duties'] = $this->rosta_model->fetch_report($date);

		$nonworkables = $this->rosta_model->nonworkables();

		$data['facilities'] = $this->attendance_model->get_facility();

		$actualrows = $this->rosta_model->getActuals();

		$actuals = array();

		foreach ($actualrows as $actual) {

			$entry = $actual['entry_id'];
			$duty = $actual['actual'];
			$device = $actual['stream'];
			$actuals[$entry] = $duty;
		}

		$data['actuals'] = $actuals;
		$data['switches'] = $this->switches();
		//print_r($data);

		$this->load->view('actuals', $data);
	}


	//presense tracking

	public function saveActual()
	{

		$pid = $_POST['hpid'];
		$date = $_POST['date'];
		$actual = $_POST['duty'];
		$facility = $this->session->userdata['facility'];

		$rowid = $_POST['date'] . $_POST['hpid'];

		$entry = str_replace(' ', '', $rowid);
		//'ihris_pid' => $pid

		$actualletters = $this->rosta_model->attendanceSchedules();

		$duty = $actualletters[$actual];

		$data = array('entry_id' => $entry, 'date' => $date, 'schedule_id' => $duty, 'ihris_pid' => $pid, 'facility_id' => $facility, 'stream' => 'manual-form');

		//print_r($data);

		$result = $this->rosta_model->saveActual($data);
		//echo $result;

		echo  $duty;
	}




	public function actualsreport()
	{


		///this is 
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$department = $this->input->post('department');


		//for a dynamic one

		if ($month != "") {

			$data['month'] = $month;

			$data['year'] = $year;
			$date = $year . "-" . $month;
		} else {

			$data['month'] = date('m');

			$data['year'] = date('Y');

			$date = date('Y') . "-" . date('m');
		}

		if ($department !== '') {

			$data['depart'] = $department;
		}




		$data['username'] = $this->username;
		$data['departments'] = $this->departments;


		$data['facilities'] = $this->attendance_model->get_facility();

		$data['duties'] = $this->rosta_model->fetch_report($date);
		$actualrows = $this->rosta_model->getActuals();
		$actuals = array();

		foreach ($actualrows as $actual) {

			$entry = $actual['entry_id'];
			$duty = $actual['actual'];



			$actuals[$entry] = $duty;
		}

		$data['actuals'] = $actuals;

		$data['matches'] = $this->rosta_model->matches();
		$data['checks'] = $this->checks;
		//$data['switches']=$this->switches();

		$this->load->view('actuals_report', $data);
	}


	public function print_actuals($date)
	{

		$data['dates'] = $date;

		$this->load->library('ML_pdf');

		$data['username'] = $this->username;


		$data['duties'] = $this->rosta_model->fetch_report($date);

		$actualrows = $this->rosta_model->getActuals();
		$actuals = array();

		foreach ($actualrows as $actual) {

			$entry = $actual['entry_id'];
			$duty = $actual['actual'];

			$actuals[$entry] = $duty;
		}

		$data['actuals'] = $actuals;

		$html = $this->load->view('actual_printable', $data, true);

		$fac = $data['duties'][0]['facility'];
		$date = date('F-Y', strtotime($data['duties'][0]['day1']));

		$filename = $fac . "_actuals_report_" . $date . ".pdf";




		ini_set('max_execution_time', 0);
		$PDFContent = mb_convert_encoding($html, 'UTF-8', 'UTF-8');

		$this->ml_pdf->pdf->SetWatermarkImage($this->watermark);
		$this->ml_pdf->pdf->showWatermarkImage = true;

		date_default_timezone_set("Africa/Kampala");
		$this->ml_pdf->pdf->SetHTMLFooter("Printed / Accessed on: <b>" . date('d F,Y h:i A') . "</b>");

		$this->ml_pdf->pdf->SetWatermarkImage($this->watermark);
		$this->ml_pdf->showWatermarkImage = true;


		ini_set('max_execution_time', 0);
		$this->ml_pdf->pdf->WriteHTML($PDFContent); //ml_pdf because we loaded the library ml_pdf for landscape format not m_pdf

		//download it D save F.
		$this->ml_pdf->pdf->Output($filename, 'I');
	}




	public function print_summary($date)
	{

		$data['dates'] = $date;

		$this->load->library('ML_pdf');

		$data['username'] = $this->username;

		$data['sums'] = $this->rosta_model->fetch_summary($date);

		$html = $this->load->view('printablesummary', $data, true);

		$fac = $_SESSION['facility'];

		$filename = $fac . "_summary_report_" . $date . ".pdf";


		ini_set('max_execution_time', 0);
		$PDFContent = mb_convert_encoding($html, 'UTF-8', 'UTF-8');

		$this->ml_pdf->pdf->SetWatermarkImage($this->watermark);
		$this->ml_pdf->pdf->showWatermarkImage = true;

		date_default_timezone_set("Africa/Kampala");
		$this->ml_pdf->pdf->SetHTMLFooter("Printed / Accessed on: <b>" . date('d F,Y h:i A') . "</b>");

		$this->ml_pdf->pdf->SetWatermarkImage($this->watermark);
		$this->ml_pdf->showWatermarkImage = true;




		ini_set('max_execution_time', 0);
		$this->ml_pdf->pdf->WriteHTML($PDFContent); //ml_pdf because we loaded the library ml_pdf for landscape format not m_pdf

		//download it D save F.
		$this->ml_pdf->pdf->Output($filename, 'I');
	}







	public function print_attsummary($date)
	{

		$data['dates'] = $date;

		$this->load->library('ML_pdf');

		$data['username'] = $this->username;

		$data['sums'] = $this->rosta_model->attendance_summary($date);

		$html = $this->load->view('att_summary', $data, true);

		$fac = $_SESSION['facility'];

		$filename = $fac . "att_summary_report_" . $date . ".pdf";


		ini_set('max_execution_time', 0);
		$PDFContent = mb_convert_encoding($html, 'UTF-8', 'UTF-8');

		$this->ml_pdf->pdf->SetWatermarkImage($this->watermark);
		$this->ml_pdf->pdf->showWatermarkImage = true;

		date_default_timezone_set("Africa/Kampala");
		$this->ml_pdf->pdf->SetHTMLFooter("Printed / Accessed on: <b>" . date('d F,Y h:i A') . "</b>");



		ini_set('max_execution_time', 0);
		$this->ml_pdf->pdf->WriteHTML($PDFContent); //ml_pdf because we loaded the library ml_pdf for landscape format not m_pdf

		//download it D save F.
		$this->ml_pdf->pdf->Output($filename, 'I');
	}



	public function attCsv($valid_range)
	{

		$sums = $this->rosta_model->attendance_summary($valid_range);



		$fp = fopen('php://memory', 'w');

		//add heading to data
		$heading = array('person' => "Staff Names", 'R' => "R", 'facility' => ' ', 'O' => "O", 'P' => "P", 'L' => "L");

		array_unshift($sums, $heading);

		foreach ($sums as $sum) {

			$data = array();
			$data['person'] = $sum['person'];
			$data['R'] = $sum['R'];
			$data['O'] = $sum['O'];
			$data['P'] = $sum['P'];
			$data['L'] = $sum['L'];

			fputcsv($fp, $data);
		}


		$filename = $valid_range . "att_summary_report.csv";

		// reset the file pointer to the start of the file
		fseek($fp, 0);
		// tell the browser it's going to be a csv file
		header('Content-Type: application/csv');
		// tell the browser we want to save it instead of displaying it
		header('Content-Disposition: attachment; filename="' . $filename . '";');
		// make php send the generated csv lines to the browser
		fpassthru($fp);


		fclose($fp);
	}
	//Data Share Districts Data
	public function SharedCsv1($valid_range)
	{


		$sums = $this->rosta_model->rosterShare1($valid_range);

		//$fp = fopen(FCPATH.'uploads/summary.csv', 'w');

		$fp = fopen('php://memory', 'w');

		//add heading to data
		$heading = array('ihris_pid' => "Person ID", 'facility' => 'facility', 'duty_date' => 'Duty Date', 'person' => "Names", 'D' => "Day Duty", 'E' => "Evening", 'N' => "Night", 'O' => "Off Duty", 'A' => "Annual Leave", 'S' => "Study Leave", 'M' => "Maternity Leave", 'Z' => "Other Leave");

		array_unshift($sums, $heading);

		foreach ($sums as $sum) {


			fputcsv($fp, $sum);
		}
		ini_set('max_execution_time', 0);

		$filename = "districts_rosta.csv";

		// reset the file pointer to the start of the file
		fseek($fp, 0);
		// tell the browser it's going to be a csv file
		header('Content-Type: application/csv');
		// tell the browser we want to save it instead of displaying it
		header('Content-Disposition: attachment; filename="' . $filename . '";');
		// make php send the generated csv lines to the browser
		fpassthru($fp);


		fclose($fp);
	}
}