<?php
defined('BASEPATH') OR exit('No direct script access allowed');
Rosta_model extends CI_Model {


/*Read the data from DB */
	Public function getEvents()
	{
		
	$sql = "SELECT * FROM duty_rosta WHERE duty_rosta.duty_date BETWEEN ? AND ? ORDER BY duty_rosta.duty_date ASC";

	return $this->db->query($sql, array($_GET['start'], $_GET['end']))->result();

	}

/*Create new events */

	Public function addEvent()
	{

	$sql = "INSERT INTO duty_rosta (entry_id,facility_id,ihris_pid,schedule_id,color,duty_date) VALUES (?,?,?,?,?,?)";

	$entry=$_POST['start'].$POST['hpid'];
	$facility="12";

	$this->db->query($sql, array($entry,$facility, $_POST['hpid'],$_POST['duty'],$_POST['color'],$_POST['start']));
		return ($this->db->affected_rows()!=1)?false:true;
	}

	/*Update  event */

	Public function updateEvent()
	{

	$sql = "UPDATE events SET title = ?, description = ?, color = ? WHERE id = ?";

	$this->db->query($sql, array($_POST['title'],$_POST['description'], $_POST['color'], $_POST['id']));
		return ($this->db->affected_rows()!=1)?false:true;
	}


	/*Delete event */

	Public function deleteEvent()
	{

	$sql = "DELETE FROM events WHERE id = ?";
	$this->db->query($sql, array($_GET['id']));
		return ($this->db->affected_rows()!=1)?false:true;
	}

	/*Update  event */

	Public function dragUpdateEvent()
	{
			//$date=date('Y-m-d h:i:s',strtotime($_POST['date']));

			$sql = "UPDATE events SET  events.start = ?   WHERE id = ?";
			$this->db->query($sql, array($_POST['start'], $_POST['id']));

		return ($this->db->affected_rows()!=1)?false:true;


	}






}