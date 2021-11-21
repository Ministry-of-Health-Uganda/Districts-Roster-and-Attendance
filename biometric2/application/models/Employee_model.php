<?php defined('BASEPATH') OR exit('No direct script access allowed');

Class Employee_model extends CI_Model
{
    public function get_employees($facility,$limit, $offset)
    {
        $this->db->select('ihris_pid, firstname, surname, job');
        $this->db->where('ihrisdata.facility_id', urldecode($facility));
        $this->db->order_by('ihris_pid', 'asc');
        $this->db->limit(10, $offset);
        $this->db->from('ihrisdata');
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_employee($facility, $staffId) {
        $this->db->select('ihris_pid, firstname, surname, job');
        $this->db->where('ihrisdata.ihris_pid', urldecode($staffId));
        $this->db->where('ihrisdata.facility_id', urldecode($facility));
        $this->db->from('ihrisdata');
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function get_employee_clock($facility, $staffId) {
        $this->db->select('status');
        $this->db->from('clk_log');
        $this->db->where('facility_id', urldecode($facility));
        $this->db->where('ihris_pid', urldecode($staffId));
        $this->db->where('date', date("Y-m-d"));
        $query = $this->db->get();
        if($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function search_employees($facility, $search_term)
    {
        $this->db->select('ihris_pid, firstname, surname, job, facility_id');

        $this->db->where('ihrisdata.facility_id', urldecode($facility))
        ->like('ihris_pid', urldecode($search_term));

        $this->db->or_where('ihrisdata.facility_id', urldecode($facility))
        ->like('firstname', urldecode($search_term));

        $this->db->or_where('ihrisdata.facility_id', urldecode($facility))
        ->like('surname', urldecode($search_term));

        $this->db->order_by('ihris_pid', 'asc');


        $this->db->from('ihrisdata');
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function count_employees($facility)
    {
        // return $this->db->count_all_results('ihrisdata');
        $this->db->where('facility_id', urldecode($facility));
        $this->db->from('ihrisdata');
        return $this->db->count_all_results();
    }

    public function get_schedules($employeeId)
    {
        $this->db->select('duty_date, end, allDay, starts, ends, letter');
        $this->db->from('ihrisdata');
        $this->db->join('duty_rosta', 'duty_rosta.ihris_pid = ihrisdata.ihris_pid');
        $this->db->join('schedules', 'schedules.schedule_id = duty_rosta.schedule_id');
        $this->db->where('duty_rosta.ihris_pid', urldecode($employeeId));
        $query = $this->db->get()->result();
        return $query;
    }

    public function count_employees_present($facility)
    {
        $this->db->select('*');
        $this->db->from('actuals');
        $this->db->where('actuals.facility_id', urldecode($facility));
        $this->db->where('actuals.date', date("Y-m-d"));
        $this->db->where('actuals.schedule_id', '22');
        return $this->db->count_all_results();
    }

    public function count_employees_off($facility)
    {
        $this->db->select('*');
        $this->db->from('duty_rosta');
        $this->db->where('duty_rosta.facility_id', urldecode($facility));
        $this->db->where('duty_rosta.schedule_id', '17');
        $this->db->where('duty_rosta.duty_date', date("Y-m-d"));
        return $this->db->count_all_results();
    }

    //Staff On Duty Today
    public function count_employees_working($facility)
    {
        $work_days = array('14','15','16');

        $this->db->select('*');
        $this->db->from('duty_rosta');
        $this->db->where('duty_date', date("Y-m-d"));
        $this->db->where('duty_rosta.facility_id', urldecode($facility));
        $this->db->join('schedules', 'schedules.schedule_id = duty_rosta.schedule_id');
        $this->db->where_in('schedules.schedule_id', $work_days);
        return $this->db->count_all_results();

    }

    public function staff_working_today($facility)
    {

        $work_days = array('14','15','16');

        $this->db->select('*');
        $this->db->from('duty_rosta');
        $this->db->where('duty_date', date("Y-m-d"));
        $this->db->where('duty_rosta.facility_id', urldecode($facility));
        $this->db->join('ihrisdata', 'ihrisdata.ihris_pid = duty_rosta.ihris_pid');
        $this->db->join('schedules', 'schedules.schedule_id = duty_rosta.schedule_id');
        $this->db->where_in('schedules.schedule_id', $work_days);
        return $this->db->get()->result_array();
    }

    public function get_schedule_types($type)
    {
        if($type == 'attendance')
        {
            $purpose = 'a';
        } else if($type == 'roster'){
            $purpose = 'r';
        } else {

            die();
        }


        $this->db->select('schedule_id, schedule, letter');
        $this->db->from('schedules');
        $this->db->where('purpose', $purpose);
        return $this->db->get()->result_array();
    }

    public function insert_schedule($data) {

        $response = array();

        foreach ($data->workdates as $key => $value) {
            // echo $value;
            $duty = array(
                'entry_id'=> $value . '' . $data->ihris_pid,
                'facility_id'=>$data->facility_id,
                'ihris_pid'=>$data->ihris_pid,
                'schedule_id'=>$data->schedule_id,
                'duty_date'=>$value,
                'end'=> strftime("%Y-%m-%d", strtotime("$value +1 day")),
                'allDay'=> 'true',
                'color'=> $this->setColor($data->schedule_id)
            );

            $entry_id = $value . '' . $data->ihris_pid;
            $duty_date = $value;
            $end_date = strftime("%Y-%m-%d", strtotime("$value +1 day"));

            $this->db->select('entry_id');
            $this->db->where('entry_id', $entry_id);
            $query = $this->db->get('duty_rosta');
            if($query->num_rows() == 1) {
                $this->db->query("UPDATE duty_rosta SET duty_date='".$duty_date."', end='".$end_date."' WHERE entry_id = '".$entry_id."' ");
                // $response[] = "$entry_id Updated";

                $response[] = true;

            } else {
                if($this->db->insert('duty_rosta',$duty)){
                    $response[] = true;
                }else {
                    $response[] = false;
                }


            }

        }

        return $response;

    }

    public function insert_attendance($data) {

        $response = array();

        foreach ($data->workdates as $key => $value) {
            // echo $value;
            $duty = array(
                'entry_id'=> $value . '' . $data->ihris_pid,
                'facility_id'=>$data->facility_id,
                'ihris_pid'=>$data->ihris_pid,
                'schedule_id'=>$data->schedule_id,
                'date'=>$value
            );

            $entry_id = $value . '' . $data->ihris_pid;
            $duty_date = $value;


            $this->db->select('entry_id');
            $this->db->where('entry_id', $entry_id);
            $query = $this->db->get('actuals');
            if($query->num_rows() == 1) {
                $this->db->query("UPDATE actuals SET `date`='".$duty_date."' WHERE entry_id = '".$entry_id."' ");
                // $response[] = "$entry_id Updated";

                $response[] = array('status'=>'updated','entry'=>$entry_id);

            } else {
                $this->db->insert('actuals',$duty);
                // $response[] = "$entry_id Created";

                $response[] = array('status'=>'created','entry'=>$entry_id);
            }

        }

        return $response;
    }

    public function insert_timelog($data) {
        $duty = array(
            'entry_id'=> $value . '' . $data->ihris_pid,
            'facility_id'=>$data->facility_id,
            'ihris_pid'=>$data->ihris_pid,
            'schedule_id'=>22,
            'date'=>$value
        );
        $this->db->insert('actuals', $duty);

        if($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }


    }

    public function setColor($schedule) {

        switch ($schedule) {
            case '14':
                # Day
                $color = '#d1a110';
                break;

                case '15':
                # Evening
                $color = '#49b229';
                break;

                case '16':
                # Night
                $color = '#29b229';
                break;

                case '17':
                # Off Duty
                $color = '#297bb2';
                break;

                case '18':
                # Annual Leave
                $color = '#603E1F';
                break;

                case '19':
                # Study Leave
                $color = '#0592942';
                break;

                case '20':
                # Maternity Leave
                $color = '#280542';
                break;

                case '21':
                # Other Leave
                $color = '#420524';
                break;

            default:
                # code...
                break;
        }

        return $color;

    }

    public function get_employee_schedule($facility, $person) {
        $this->db->select('duty_date');
        $this->db->from('duty_rosta');
        $this->db->where('facility_id', urldecode($facility));
        $this->db->where('ihris_pid', urldecode($person));

        $this->db->order_by('duty_date','DESC');

        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_employee_attendance($facility, $person) {
        $this->db->select('date');
        $this->db->from('actuals');
        $this->db->where('facility_id', urldecode($facility));
        $this->db->where('ihris_pid', urldecode($person));

        $this->db->order_by('date','DESC');

        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            return $query->result();
        } else {
            return false;
        }
    }

    public function clock_user($data) {

        $response = array();
        $fingerprint = isset($data['fingerprint']) ? $data['fingerprint'] : NULL;
        $pincode = isset($data['pin']) ? $data['pin'] : NULL;
        $mydate= $data['clockin_time'];
        $date = date("Y-m-d", strtotime($mydate));

        $entry_id = $date. 'person|' . $data['userId'];

        if($fingerprint !== NULL) {
            $fpt_query = $this->db->get_where('fingerprints', array(
                'fingerprint' => $fingerprint,
                'facilityId' => $data['facilityId']
            ));

            if ($fpt_query->num_rows() > 0){
                $result = $fpt_query->row();

                $data = array(
                    'entry_id' => $entry_id,
                    'ihris_pid' => "person|".$data['userId'],
                    'facility_id' => $data['facilityId'],
                    'time_in' => $data['clockin_time'],
                    'date' => $date,
                    'location' => $data['location'],
                    'source' => $data['source']
                );

                $query=$this->db->query("SELECT entry_id from clk_log where entry_id='$entry_id'");
                $rows=$query->num_rows();
                if($rows>0){
                $entry_id=$query->result();
                $timelog=$data['clockin_time'];
                foreach($entry_id as $entry)
                {
                    $this->db->set('time_out', "$timelog");
                    $this->db->where("time_in <","$timelog");
                    $this->db->where('entry_id', "$entry->entry_id");
                    $query=$this->db->update('clk_log');

   
                }
               }
               else{

                $this->db->insert('clk_log', $data);
               }


               
                

                if($this->db->affected_rows() > 0) {
                    $response['status'] = 'SUCCESS';
                    $response['message'] = 'User clocked in';
                    $response['error'] = null;
                } else {
                    $response['status'] = 'FAILED';
                    $response['message'] = 'User not clocked in';
                    $response['error'] = null;
                }
            }


      } 
        
    //     else if($pincode !==  NULL) {
    //         $pin_query = $this->db->get_where('fingerprints', array(
    //             'pin' => $pincode,
    //             'facilityId' => $data['facilityId']
    //         ));

    //         if ($pin_query->num_rows() > 0) {
    //             $result = $pin_query->row();

    //             $data = array(
    //                 'entry_id' => $entry_id,
    //                 'ihris_pid' => "person|".$data['userId'],
    //                 'facility_id' => $data['facilityId'],
    //                 'date' => $data['clockin_time'],
    //                 'time_in' => $data['clockin_time'],
    //                 'source' => $data['source']
    //             );

    //             $this->db->insert('clk_log', $data);
                
    //             $query=$this->db->query("SELECT entry_id from clk_log where entry_id='$entry_id'");
    //             $entry_id=$query->result();
                
    //             foreach($entry_id as $entry){
    //                 $this->db->set('time_out', "$data['clockin_time']");
    //                 $this->db->where("time_in <","$data['clockin_time']");
    //                 $this->db->where('entry_id', "$entry->entry_id");
    //                 $query=$this->db->update('clk_log');

   
    //             }


    //             if($this->db->affected_rows() > 0) {
    //                 $response['status'] = 'SUCCESS';
    //                 $response['message'] = 'User clocked in';
    //                 $response['error'] = null;
    //             } else {
    //                 $response['status'] = 'FAILED';
    //                 $response['message'] = 'User not clocked in';
    //                 $response['error'] = null;
    //             }
    //         }
    //     }

        return $response;
    }

    public function enroll_user($data) {

        $response = array();
        $fingerprint = isset($data['fingerprint']) ? $data['fingerprint'] : NULL;
        $pincode = isset($data['pin']) ? $data['pin'] : NULL;

        $entry_id = $data['facilityId'] . 'person|' . $data['userId'].'b'.$fingerprint.'|fpt';
        $facilityId = $data['facilityId'];
        $queryc=$this->db->query("delete from fingerprints where facilityId='$facilityId'  and fingerprint='$fingerprint'"); 


            if($queryc){
            $this->db->insert('fingerprints', array(
                'entry_id' => $entry_id,
                'fingerprint' => $fingerprint,
                'pin' => $pincode,
                'location' => $data['location'],
                'ihris_pid' => 'person|' . $data['userId'],
                'facilityId' => $data['facilityId'],
                'source' => $data['source']
            ));

            if($this->db->affected_rows() > 0) {
                $response['status'] = 'SUCCESS';
                $response['message'] = 'User record inserted';
                $response['error'] = null;
            } else {
                $response['status'] = 'FAILED';
                $response['message'] = 'User record not inserted';
                $response['error'] = null;
            }
        }

        return $response;
    }

    public function get_enrolled_employees($facilityId) {
        $this->db->select("surname, firstname, fingerprint, othername, job, fingerprints.ihris_pid");
        $this->db->from('fingerprints');
        $this->db->join('ihrisdata', 'ihrisdata.ihris_pid = fingerprints.ihris_pid');
        $this->db->where('fingerprints.facilityId', $facilityId);
        return $this->db->get()->result();

    }
  public function get_clocked_employees($facilityId,$fingerprint) {
        $this->db->select("surname, firstname, othername, job, fingerprints.ihris_pid");
        $this->db->from('fingerprints');
        $this->db->join('ihrisdata', 'ihrisdata.ihris_pid = fingerprints.ihris_pid');
        $this->db->where('fingerprints.facilityId', $facilityId);
        $this->db->where('fingerprints.fingerprint', $fingerprint);
        return $this->db->get()->result();

    }

}
