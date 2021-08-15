<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Rosta_model extends CI_Model
{

	/*
/*Read the data from DB */
	public function getEvents()
	{
		//current facility
		$facility = $this->session->userdata['facility'];


		$sql = "SELECT entry_id as id,duty_rosta.end,duty_rosta.ihris_pid as person_id,schedules.schedule as duty,concat(ihrisdata.surname,' ',ihrisdata.firstname) as title,duty_rosta.color,duty_rosta.duty_date as start,duty_rosta.schedule_id as schedule FROM duty_rosta,ihrisdata,schedules WHERE (duty_rosta.duty_date BETWEEN ? AND ? AND ihrisdata.ihris_pid=duty_rosta.ihris_pid AND duty_rosta.schedule_id=schedules.schedule_id and duty_rosta.facility_id='$facility') ORDER BY duty_rosta.duty_date ASC";

		return $this->db->query($sql, array($_GET['start'], $_GET['end']))->result();
	}

	/*Create new events */

	public function addEvent()
	{




		$start = strtotime($_POST['start']); // or your date as well
		$end = strtotime($_POST['end']);

		$datediff = $end - $start;

		$days = floor($datediff / (60 * 60 * 24));


		if ($days > 1) {

			for ($i = 0; $i < $days; $i++) {

				$oneday = "+" . $i . " day"; //1 day
				$twodays = "+" . ($i + 1) . " day"; //1 other day for end date

				$sdate = $start;

				$newstartdate = date('Y-m-d', strtotime($oneday, $sdate)); //add one to prev date 2017-11-02

				$newenddate = date('Y-m-d', strtotime($twodays, $sdate)); //add one to prev date

				$entry = $newstartdate . $_POST['hpid'];

				$facility = $this->session->userdata['facility'];


				$sql = "INSERT INTO duty_rosta (entry_id,facility_id,ihris_pid,schedule_id,color,duty_date,duty_rosta.end) VALUES (?,?,?,?,?,?,?)";



				$done = $this->db->query($sql, array($entry, $facility, $_POST['hpid'], $_POST['duty'], $_POST['color'], $newstartdate, $newenddate));
			} //for

			if ($done) {

				$rows = $this->db->affected_rows();
			} else if (!$done) {

				$rows = 0;
			}


			return $rows;
		} //if DAYS >1

		else {



			$sql = "INSERT INTO duty_rosta (entry_id,facility_id,ihris_pid,schedule_id,color,duty_date,duty_rosta.end) VALUES (?,?,?,?,?,?,?)";

			$entry = date("Y-m-d", strtotime($_POST['start'])) . $_POST['hpid'];

			$facility = $this->session->userdata['facility'];



			$done = $this->db->query($sql, array($entry, $facility, $_POST['hpid'], $_POST['duty'], $_POST['color'], $_POST['start'], $_POST['end']));



			if ($done) {
				$rows = $this->db->affected_rows();
			} else if (!$done) {

				$rows = 0;
			}

			return $rows;
		} //end else
	} // end add event

	/*Update  event */

	public function updateEvent()
	{

		 // $sql = "UPDATE duty_rosta SET ihris_pid = ?, schedule_id = ?, color = ? WHERE entry_id = ?";
		 $facility = $this->session->userdata['facility'];
		 $str=$_POST['id'];
		 $str = substr($str, 0, strpos($str, 'p'));
         $date = date($str);
		 $end = date('Y-m-d', strtotime( $date . " +1 days"));
         $data = array('ihris_pid'=>$_POST['hpid'], 'schedule_id'=>$_POST['duty'], 'color'=>$_POST['color'], 'entry_id'=>str_replace(" ","",$_POST['id']),'facility_id'=>$facility,'allDay'=>'true','duty_date'=>$date,'end'=>$end);
		 $query=$this->db->replace('duty_rosta',$data);

		// return ($this->db->affected_rows() != 1) ? false : true;
		print_r($data);
	}


	/*Delete event */

	public function deleteEvent()
	{

		$sql = "DELETE FROM duty_rosta WHERE entry_id = ?";

		$this->db->query($sql, array($_GET['id']));
		return ($this->db->affected_rows() != 1) ? false : true;
	}

	/*Update  event */

	public function dragUpdateEvent()
	{
		//$date=date('Y-m-d h:i:s',strtotime($_POST['date']));



		$start = strtotime($_POST['start']); // or your date as well
		$end = strtotime($_POST['end']);

		$datediff = $end - $start;

		$days = floor($datediff / (60 * 60 * 24));


		if ($days > 1) {

			for ($i = 0; $i < $days; $i++) {

				$oneday = "+" . $i . " day"; //1 day
				$twodays = "+" . ($i + 1) . " day"; //1 other day for end date

				$sdate = $start;

				$newstartdate = date('Y-m-d', strtotime($oneday, $sdate)); //add one to prev date 2017-11-02

				$newenddate = date('Y-m-d', strtotime($twodays, $sdate)); //add one to prev date

				$sql = "UPDATE duty_rosta SET  duty_date = ?, end = ?   WHERE entry_id= ?";
				$this->db->query($sql, array($newstartdate, $newenddate, $_POST['id']));
			} //for


		} //if


		else {

			$sql = "UPDATE duty_rosta SET  duty_date = ?, end = ?   WHERE entry_id= ?";

			$this->db->query($sql, array($_POST['start'], $_POST['end'], $_POST['id']));
		}

		return ($this->db->affected_rows() != 1) ? false : true;
	}





	public function fetch_report($date_range)
	{

		$facility = $this->session->userdata['facility'];

		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$department = $this->input->post('department');

		$date = $year . "-" . $month;

		if ($month != "") {

			$valid_range = $date;
		} else {

			$valid_range = $date_range;
		}


		$all = $this->db->query("select distinct ihris_pid from dutyreport where dutyreport.facility_id='$facility' and dutyreport.duty_date like '$valid_range-%'");

		$rows = $all->result_array();

		$data = array();

		foreach ($rows as $row) {

			$id = $row['ihris_pid'];


			if ($department) {  //if department has been defined

				$query = $this->db->query("select dutyreport.ihris_pid,dutyreport.duty_date,max(dutyreport.day1) as day1,max(dutyreport.day2)as day2,max(dutyreport.day3)as day3,max(dutyreport.day4)as day4,max(dutyreport.day5)as day5,max(dutyreport.day6)as day6,max(dutyreport.day7)as day7,max(dutyreport.day8)as day8,max(dutyreport.day9)as day9,max(dutyreport.day10)as day10,
max(dutyreport.day11)as day11,max(dutyreport.day12)as day12,max(dutyreport.day13)as day13,max(dutyreport.day14)as day14,max(dutyreport.day15)as day15,max(dutyreport.day16)as day16,max(dutyreport.day17)as day17,max(dutyreport.day18)as day18,max(dutyreport.day19)as day19,
max(dutyreport.day20)as day20,max(dutyreport.day21)as day21,max(dutyreport.day22)as day22,max(dutyreport.day23)as day23,max(dutyreport.day24)as day24,max(dutyreport.day25)as day25,max(dutyreport.day26)as day26,max(dutyreport.day27)as day27,max(dutyreport.day28)as day28,max(dutyreport.day29)as day29,max(dutyreport.day30)as day30,max(dutyreport.day31)as day31,schedules.letter,schedules.schedule,ihrisdata.job,ihrisdata.facility,concat(ihrisdata.surname,' ',ihrisdata.firstname) as fullname from dutyreport,schedules,ihrisdata WHERE( dutyreport.duty_date like '$valid_range-%' and dutyreport.schedule_id=schedules.schedule_id and dutyreport.ihris_pid=ihrisdata.ihris_pid and dutyreport.facility_id='$facility' and dutyreport.ihris_pid='$id' and ihrisdata.department='$department')");
			} else {

				$query = $this->db->query("select dutyreport.ihris_pid,dutyreport.duty_date,max(dutyreport.day1) as day1,max(dutyreport.day2)as day2,max(dutyreport.day3)as day3,max(dutyreport.day4)as day4,max(dutyreport.day5)as day5,max(dutyreport.day6)as day6,max(dutyreport.day7)as day7,max(dutyreport.day8)as day8,max(dutyreport.day9)as day9,max(dutyreport.day10)as day10,
max(dutyreport.day11)as day11,max(dutyreport.day12)as day12,max(dutyreport.day13)as day13,max(dutyreport.day14)as day14,max(dutyreport.day15)as day15,max(dutyreport.day16)as day16,max(dutyreport.day17)as day17,max(dutyreport.day18)as day18,max(dutyreport.day19)as day19,
max(dutyreport.day20)as day20,max(dutyreport.day21)as day21,max(dutyreport.day22)as day22,max(dutyreport.day23)as day23,max(dutyreport.day24)as day24,max(dutyreport.day25)as day25,max(dutyreport.day26)as day26,max(dutyreport.day27)as day27,max(dutyreport.day28)as day28,max(dutyreport.day29)as day29,max(dutyreport.day30)as day30,max(dutyreport.day31)as day31,schedules.letter,schedules.schedule,ihrisdata.job,ihrisdata.facility,concat(ihrisdata.surname,' ',ihrisdata.firstname) as fullname from dutyreport,schedules,ihrisdata WHERE( dutyreport.duty_date like '$valid_range-%' and dutyreport.schedule_id=schedules.schedule_id and dutyreport.ihris_pid=ihrisdata.ihris_pid and dutyreport.facility_id='$facility' and dutyreport.ihris_pid='$id')");
			}

			$rowdata = $query->result_array();

			array_push($data, $rowdata[0]);
		}

		return $data;
	}


	public function matches()
	{
		$facility = $_SESSION['facility'];

		$query = $this->db->query("Select dutyreport.ihris_pid,dutyreport.schedule_id,dutyreport.duty_date,schedules.letter from dutyreport,schedules where schedules.schedule_id=dutyreport.schedule_id and dutyreport.facility_id='$facility'");

		$results = $query->result_array();

		$ro = $query->num_rows();

		$matches = array();

		for ($i = 0; $i < $ro; $i++) {

			$matches[$results[$i]['duty_date'] . $results[$i]['ihris_pid']] = $results[$i]['letter'];
		}

		return $matches;
	}



	public function tab_matches()
	{

		$query = $this->db->query("Select schedule_id,letter from schedules where purpose='r'");

		$results = $query->result_array();

		$ro = $query->num_rows();

		$schedules = array();

		for ($i = 0; $i < $ro; $i++) {

			$schedules["'" . $results[$i]['letter'] . "'"] = $results[$i]['schedule_id'];
		}



		return $schedules;
	}



	public function fetch_tabs($date_range)
	{

		$facility = $_SESSION['facility'];

		$month = $this->input->post('month');
		$year = $this->input->post('year');

		$date = $year . "-" . $month;

		if ($month != "") {

			$valid_range = $date;
		} else {

			$valid_range = $date_range;
		}





		$this->db->where('facility_id', $facility);

		$this->db->like('duty_date', $valid_range);

		$rowno = $this->db->count_all_results('duty_rosta');

		if ($rowno < 1) {

			$query = $this->db->query("select distinct ihrisdata.ihris_pid,concat(ihrisdata.surname,' ',ihrisdata.firstname) as fullname,ihrisdata.job from schedules,ihrisdata where ihrisdata.facility_id='$facility'");


			$data = $query->result_array();
		} // if There are no $schedules yet



		else  // if there are schedules

		{

			$all = $this->db->query("select distinct ihris_pid from ihrisdata where facility_id='$facility'");

			$rows = $all->result_array();

			$data = array();

			foreach ($rows as $row) {

				$id = str_replace(" ","",$row['ihris_pid']);



				// $query = $this->db->query("select ihrisdata.ihris_pid,dutyreport.duty_date, schedules.letter,dutyreport.entry_id,schedules.schedule,ihrisdata.job,ihrisdata.facility,concat(ihrisdata.surname,' ',ihrisdata.firstname) as fullname,max(dutyreport.day1) as day1,max(dutyreport.day2)as day2,max(dutyreport.day3)as day3,max(dutyreport.day4)as day4,max(dutyreport.day5)as day5,max(dutyreport.day6)as day6,max(dutyreport.day7)as day7,max(dutyreport.day8)as day8,max(dutyreport.day9)as day9,max(dutyreport.day10)as day10,
				// max(dutyreport.day11)as day11,max(dutyreport.day12)as day12,max(dutyreport.day13)as day13,max(dutyreport.day14)as day14,max(dutyreport.day15)as day15,max(dutyreport.day16)as day16,max(dutyreport.day17)as day17,max(dutyreport.day18)as day18,max(dutyreport.day19)as day19,
				// max(dutyreport.day20)as day20,max(dutyreport.day21)as day21,max(dutyreport.day22)as day22,max(dutyreport.day23)as day23,max(dutyreport.day24)as day24,max(dutyreport.day25)as day25,max(dutyreport.day26)as day26,max(dutyreport.day27)as day27,max(dutyreport.day28)as day28,max(dutyreport.day29)as day29,max(dutyreport.day30)as day30,max(dutyreport.day31)as day31 from dutyreport,schedules,ihrisdata WHERE dutyreport.duty_date like '$valid_range-%' and dutyreport.schedule_id=schedules.schedule_id and dutyreport.facility_id='$facility' and dutyreport.ihris_pid=ihrisdata.ihris_pid and ihrisdata.ihris_pid='$id'");


				// $query = $this->db->query("select ihrisdata.ihris_pid,dutyreport.duty_date, schedules.letter,dutyreport.entry_id,schedules.schedule,ihrisdata.job,ihrisdata.facility,concat(ihrisdata.surname,' ',ihrisdata.firstname)  as fullname,dutyreport.day1  as day1,dutyreport.day2 as day2,dutyreport.day3 as day3,dutyreport.day4 as day4,dutyreport.day5 as day5,dutyreport.day6 as day6,dutyreport.day7 as day7,dutyreport.day8 as day8,dutyreport.day9 as day9,dutyreport.day10 as day10,
				// dutyreport.day11 as day11,dutyreport.day12 as day12,dutyreport.day13 as day13,dutyreport.day14 as day14,dutyreport.day15 as day15,dutyreport.day16 as day16,dutyreport.day17 as day17,dutyreport.day18 as day18,dutyreport.day19 as day19,
				// dutyreport.day20 as day20,dutyreport.day21 as day21,dutyreport.day22 as day22,dutyreport.day23 as day23,dutyreport.day24 as day24,dutyreport.day25 as day25,dutyreport.day26 as day26,dutyreport.day27 as day27,dutyreport.day28 as day28,dutyreport.day29 as day29,dutyreport.day30 as day30,dutyreport.day31 as day31 from dutyreport,schedules,ihrisdata WHERE( dutyreport.duty_date like '$valid_range-%' and dutyreport.schedule_id=schedules.schedule_id and dutyreport.facility_id='$facility' and dutyreport.ihris_pid=ihrisdata.ihris_pid and ihrisdata.ihris_pid='$id')");

				$query = $this->db->query("select distinct ihrisdata.ihris_pid,dutyreport.duty_date, dutyreport.entry_id,ihrisdata.job,ihrisdata.facility,concat(ihrisdata.surname,' ',ihrisdata.firstname) as fullname,max(dutyreport.day1) as day1,max(dutyreport.day2)as day2,max(dutyreport.day3)as day3,max(dutyreport.day4)as day4,max(dutyreport.day5)as day5,max(dutyreport.day6)as day6,max(dutyreport.day7)as day7,max(dutyreport.day8)as day8,max(dutyreport.day9)as day9,max(dutyreport.day10)as day10,
				max(dutyreport.day11)as day11,max(dutyreport.day12)as day12,max(dutyreport.day13)as day13,max(dutyreport.day14)as day14,max(dutyreport.day15)as day15,max(dutyreport.day16)as day16,max(dutyreport.day17)as day17,max(dutyreport.day18)as day18,max(dutyreport.day19)as day19,
				max(dutyreport.day20)as day20,max(dutyreport.day21)as day21,max(dutyreport.day22)as day22,max(dutyreport.day23)as day23,max(dutyreport.day24)as day24,max(dutyreport.day25)as day25,max(dutyreport.day26)as day26,max(dutyreport.day27)as day27,max(dutyreport.day28)as day28,max(dutyreport.day29)as day29,max(dutyreport.day30)as day30,max(dutyreport.day31)as day31 from dutyreport JOIN ihrisdata on dutyreport.duty_date like '$valid_range%'   and dutyreport.facility_id='$facility' and trim(ihrisdata.ihris_pid)='$id'");
				$rows = $this->db->affected_rows();


				$rowdata = $query->result_array();


				array_push($data, $rowdata[0]);
			}
		}

		//cache data
		return $data;
	}



	//dashboard data checks
	public function checks()
	{

		$facility = $_SESSION['facility'];

		$date = date('Y-m');

		$this->db->where("facility_id='$facility' and duty_date like '$date%'");


		$rowno = $this->db->count_all_results('duty_rosta');

		$this->db->where('facility_id', $facility);

		$staffs = $this->db->count_all_results('ihrisdata');

		$data = array('workedon' => $rowno, 'staffs' => $staffs);
		return $data;
	}


	public function fetch_summary($valid_range)
	{

		$facility = $this->session->userdata['facility'];

		$department = $this->input->post('department');

		if (empty($valid_range)) {

			$valid_range = date('Y-m');
		}


		$s = $this->db->query("select letter,schedule_id from schedules where letter!='H' and purpose='r'");

		$schs = $s->result_array();

		if ($department) {
			$all = $this->db->query("select distinct ihris_pid,concat(ihrisdata.surname,' ',ihrisdata.firstname) as fullname from ihrisdata where facility_id='$facility' and department='$department'");
		} else {

			$all = $this->db->query("select distinct ihris_pid,concat(ihrisdata.surname,' ',ihrisdata.firstname) as fullname from ihrisdata where facility_id='$facility'");
		}
		$rows = $all->result_array();

		$data = array();

		$mydata = array();

		$i = 0;




		foreach ($rows as $row) {

			$id = $row['ihris_pid'];

			$mydata["person"] = $row['fullname'];

			foreach ($schs as $sc) {
				$i++;

				$s_id = $sc['schedule_id'];



				$query = $this->db->query("select ihrisdata.ihris_pid,dutyreport.duty_date, schedules.letter,dutyreport.entry_id,schedules.schedule,ihrisdata.job,ihrisdata.facility,concat(ihrisdata.surname,' ',ihrisdata.firstname) as fullname,count(dutyreport.schedule_id) as days from dutyreport,schedules,ihrisdata WHERE( dutyreport.duty_date like '$valid_range-%' and dutyreport.schedule_id=schedules.schedule_id and dutyreport.ihris_pid=ihrisdata.ihris_pid and dutyreport.facility_id='$facility' and dutyreport.ihris_pid='$id' and schedules.schedule_id='$s_id' and dutyreport.duty_date like '$valid_range%')");

				$rows = $this->db->affected_rows();

				$rowdata = $query->result_array();


				//$mydata=array('person'.$i=>$rowdata[0]['fullname'],'shift'=>$rowdata[0]['schedule'],'days'=>$rowdata[0]['days']);

				$mydata[$rowdata[0]['letter']] = $rowdata[0]['days'];
				$mydata['facility'] = $rowdata[0]['facility'];
			}

			array_push($data, $mydata);
		}



		return $data;
	} //summary


	public function full_summary($limit, $start, $valid_range, $district, $facility)
	{


		if (empty($valid_range)) {

			$valid_range = date('Y-m');
		}


		$s = $this->db->query("select letter,schedule_id from schedules where letter!='H' and purpose='r'");

		$schs = $s->result_array();

		if ($district && !$facility) {

			$all = $this->db->query("select distinct ihris_pid,concat(ihrisdata.surname,' ',ihrisdata.firstname) as fullname from ihrisdata where ihrisdata.district_id='$district'   and   ihrisdata.ihris_pid IN(select dutyreport.ihris_pid from dutyreport where duty_date like '$valid_range%')  LIMIT $start,$limit ");
		} else if ($facility) {

			$all = $this->db->query("select distinct ihris_pid,concat(ihrisdata.surname,' ',ihrisdata.firstname) as fullname from ihrisdata where  ihrisdata.district_id='$district' and  ihrisdata.facility_id='$facility'   and   ihrisdata.ihris_pid IN(select dutyreport.ihris_pid from dutyreport where duty_date like '$valid_range%')  LIMIT $start,$limit");
		} else {

			$all = $this->db->query("select distinct ihris_pid,concat(ihrisdata.surname,' ',ihrisdata.firstname) as fullname from ihrisdata where  ihrisdata.ihris_pid IN(select dutyreport.ihris_pid from dutyreport where duty_date like '$valid_range%')  LIMIT $start,$limit");
		}
		$rows = $all->result_array();

		$data = array();

		$mydata = array();

		$i = 0;




		foreach ($rows as $row) {

			$id = $row['ihris_pid'];

			$mydata["person"] = $row['fullname'];

			foreach ($schs as $sc) {
				$i++;

				$s_id = $sc['schedule_id'];



				$query = $this->db->query("select ihrisdata.ihris_pid,dutyreport.duty_date, schedules.letter,dutyreport.entry_id,schedules.schedule,ihrisdata.job,ihrisdata.facility,concat(ihrisdata.surname,' ',ihrisdata.firstname) as fullname,count(dutyreport.schedule_id) as days from dutyreport,schedules,ihrisdata WHERE( dutyreport.duty_date like '$valid_range-%' and dutyreport.schedule_id=schedules.schedule_id and dutyreport.ihris_pid=ihrisdata.ihris_pid  and dutyreport.ihris_pid='$id' and schedules.schedule_id='$s_id' and dutyreport.duty_date like '$valid_range%')");

				$rows = $this->db->affected_rows();

				$rowdata = $query->result_array();


				//$mydata=array('person'.$i=>$rowdata[0]['fullname'],'shift'=>$rowdata[0]['schedule'],'days'=>$rowdata[0]['days']);

				$mydata[$rowdata[0]['letter']] = $rowdata[0]['days'];
				$mydata['facility'] = $rowdata[0]['facility'];
			}

			array_push($data, $mydata);
		}



		return $data;
	} //summary



	public function nonworkables()
	{

		$query = $this->db->query("select letter from schedules where letter NOT IN('D','E','N') and schedules.purpose='r' "); //get non working days; leave days

		$results = $query->result_array();

		$ro = $query->num_rows();

		$leaves = array();

		foreach ($results as $leave) {

			$leaves[] = $leave['letter'];
		}

		return $leaves;
	}


	public function workeddays()
	{
		$facility = $this->session->userdata['facility'];

		$query = $this->db->query("select day,ihris_pid from presence where facility_id='$facility'");

		$results = $query->result_array();

		$ro = $query->num_rows();

		$worked = array();

		foreach ($results as $work) {

			$comb = $work['day'] . $work['ihris_pid'];

			$worked[] = $comb;
		}

		return $worked;
	}



	public function saveTracker($data)
	{

		$facility = $this->session->userdata['facility'];

		$entry_id = $data['day'] . $data['ihris_pid'];

		$rowdata = array('day' => $data['day'], 'ihris_pid' => $data['ihris_pid'], 'entry_id' => $entry_id, 'facility_id' => $facility);


		$saved = $this->db->insert('presence', $rowdata);

		if ($saved) {

			return "Tracker Saved";
		} else
			return "Failed";
	}




	public function saveActual($data)
	{

		$facility = $this->session->userdata['facility'];

		//	$entry_id =$data['day'].$data['ihris_pid'];



		$saved = $this->db->insert('actuals', $data);

		if ($saved) {

			return "Actual Saved";
		} else
			return "Failed";
	}


	public function getActuals()
	{

		$facility = $_SESSION['facility'];

		$query = $this->db->query("select actuals.*, schedules.letter as actual from actuals join schedules on actuals.schedule_id=schedules.schedule_id and schedules.purpose='a' and actuals.facility_id='$facility' ");


		$result = $query->result_array();

		return $result;
	}



	public function attendance_summary($valid_range)
	{

		$department = $this->input->post('department');

		$facility = $this->session->userdata['facility'];



		$s = $this->db->query("select letter,schedule_id from schedules where letter!='H' and purpose='a'");

		$schs = $s->result_array();

		if ($department) {

			$all = $this->db->query("select distinct ihris_pid,facility,concat(ihrisdata.surname,' ',ihrisdata.firstname) as fullname from ihrisdata where facility_id='$facility' and department='$department'");
		} else {
			$all = $this->db->query("select distinct ihris_pid,facility,concat(ihrisdata.surname,' ',ihrisdata.firstname) as fullname from ihrisdata where facility_id='$facility'");
		}

		$rows = $all->result_array();

		$data = array();

		$mydata = array();

		$i = 0;




		foreach ($rows as $row) {

			$id = $row['ihris_pid'];

			$mydata["person"] = $row['fullname'];
			$mydata["person_id"] = $id;

			foreach ($schs as $sc) {
				$i++;

				$s_id = $sc['schedule_id'];




				$qry = $this->db->query("select schedules.letter,count(actuals.schedule_id) as days from actuals,schedules where actuals.ihris_pid='$id' and actuals.schedule_id='$s_id' and schedules.schedule_id=actuals.schedule_id and actuals.date like '$valid_range%'");

				$rowdata = $qry->result_array();



				if ($rowdata[0]['letter']) {

					$mydata[$rowdata[0]['letter']] = $rowdata[0]['days'];
				} else {

					$mydata[$sc['letter']] = '0';
				}

				$mydata['facility'] = $rows[0]['facility'];
			}

			array_push($data, $mydata);
		}



		return $data;
	} //summary


	public function count_summary($valid_range, $district, $facility)
	{


		if ($district && !$facility) {

			$all = $this->db->query("select count(ihrisdata.ihris_pid) as rows from ihrisdata where ihrisdata.district_id='$district'   and   ihrisdata.ihris_pid IN(select dutyreport.ihris_pid from dutyreport where duty_date like '$valid_range%')");
		} else if ($facility) {

			$all = $this->db->query("select count(ihrisdata.ihris_pid) as rows from ihrisdata where  ihrisdata.district_id='$district' and  ihrisdata.facility_id='$facility'   and   ihrisdata.ihris_pid IN(select dutyreport.ihris_pid from dutyreport where duty_date like '$valid_range%')");
		} else {

			$all = $this->db->query("select count(ihrisdata.ihris_pid) as rows from ihrisdata where  ihrisdata.ihris_pid IN(select dutyreport.ihris_pid from dutyreport where duty_date like '$valid_range%')");
		}
		$rows = $all->row();

		return $rows->rows;
	}


	public function full_attendance_summary($limit, $start, $valid_range, $district, $facility)
	{



		$s = $this->db->query("select letter,schedule_id from schedules where letter!='H' and purpose='a'");

		$schs = $s->result_array();

		if ($district && !$facility) {

			$all = $this->db->query("select distinct ihris_pid,concat(ihrisdata.surname,' ',ihrisdata.firstname) as fullname from ihrisdata where ihrisdata.district_id='$district'   and   ihrisdata.ihris_pid IN(select dutyreport.ihris_pid from dutyreport where duty_date like '$valid_range%') LIMIT 10");
		} else if ($facility) {

			$all = $this->db->query("select distinct ihris_pid,concat(ihrisdata.surname,' ',ihrisdata.firstname) as fullname from ihrisdata where  ihrisdata.district_id='$district' and  ihrisdata.facility_id='$facility'   and   ihrisdata.ihris_pid IN(select dutyreport.ihris_pid from dutyreport where duty_date like '$valid_range%') LIMIT 10");
		} else {

			$all = $this->db->query("select distinct ihris_pid,concat(ihrisdata.surname,' ',ihrisdata.firstname) as fullname from ihrisdata where  ihrisdata.ihris_pid IN(select dutyreport.ihris_pid from dutyreport where duty_date like '$valid_range%') LIMIT 10");
		}

		$rows = $all->result_array();

		$data = array();

		$mydata = array();

		$i = 0;




		foreach ($rows as $row) {

			$id = $row['ihris_pid'];

			$mydata["person"] = $row['fullname'];
			$mydata["person_id"] = $id;

			foreach ($schs as $sc) {
				$i++;

				$s_id = $sc['schedule_id'];




				$qry = $this->db->query("select schedules.letter,count(actuals.schedule_id) as days from actuals,schedules where actuals.ihris_pid='$id' and actuals.schedule_id='$s_id' and schedules.schedule_id=actuals.schedule_id and actuals.date like '$valid_range%' LIMIT 10");

				$rowdata = $qry->result_array();



				if ($rowdata[0]['letter']) {

					$mydata[$rowdata[0]['letter']] = $rowdata[0]['days'];
				} else {

					$mydata[$sc['letter']] = '0';
				}

				//$mydata['facility']=$rows[0]['facility'];

			}

			array_push($data, $mydata);
		}



		return $data;
	} //summary

	public function attendanceSchedules()
	{

		$this->db->where('purpose', 'a');
		$query = $this->db->get('schedules');

		$rows = $query->result_array();

		$letters = array();

		foreach ($rows as $row) {

			$sid = $row['schedule_id'];
			$letter = $row['letter'];

			$letters[$letter] = $sid;
		}

		return $letters;
	}


	public function getDepartments()
	{

		$this->db->select('department');
		$this->db->group_by('department');
		$qry = $this->db->get('ihrisdata');

		$results = $qry->result();

		return $results;
	}

	//data share rosta
	public function rosterShare1($valid_range = FALSE)
	{

		$facility = $this->session->userdata['facility'];

		if (empty($valid_range)) {

			$valid_range = date('Y-m');
		}


		$s = $this->db->query("select letter,schedule_id from schedules where  purpose='r'");

		$schs = $s->result_array();

		$all = $this->db->query("select distinct ihris_pid,facility_id,concat(ihrisdata.surname,' ',ihrisdata.firstname) as fullname from ihrisdata where facility_id='$facility'");

		$rows = $all->result_array();

		$data = array();

		$mydata = array();

		$i = 0;




		foreach ($rows as $row) {

			$id = $row['ihris_pid'];
			$mydata["ihris_pid"] = $id;
			$facid = $row['facility_id'];
			$mydata["facility"] = $facid;

			$date = $valid_range . "-" . "01";
			//$date=date(YYYY-mm-dd,$date]);
			$mydata['duty_date'] = $date;


			$mydata["person"] = $row['fullname'];

			foreach ($schs as $sc) {
				$i++;

				$s_id = $sc['schedule_id'];



				$query = $this->db->query("select ihrisdata.ihris_pid,dutyreport.duty_date, schedules.letter,dutyreport.entry_id,schedules.schedule,ihrisdata.facility_id, concat(ihrisdata.surname,' ',ihrisdata.firstname) as fullname,count(dutyreport.schedule_id) as days from dutyreport,schedules,ihrisdata WHERE(dutyreport.schedule_id=schedules.schedule_id and dutyreport.ihris_pid=ihrisdata.ihris_pid and dutyreport.ihris_pid='$id' and schedules.schedule_id='$s_id' and dutyreport.duty_date like '$valid_range%')");

				$rows = $this->db->affected_rows();

				$rowdata = $query->result_array();
				$mydata[$rowdata[0]['letter']] = $rowdata[0]['days'];
			}

			array_push($data, $mydata);
		}

		return $data;
	}
}