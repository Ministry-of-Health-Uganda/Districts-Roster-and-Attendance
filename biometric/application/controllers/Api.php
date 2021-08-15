<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

Class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
        $this->load->model('admin_model');
        $this->load->model('employee_model');
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
    }

    public function index()
    {
        echo 'WELCOME TO THE IHRIS ATTENDANCE AND BIOMETRIC API';
    }
    
    public function clock()
    {
        $data = $this->input->post('clockJSON');
        $userdata = json_decode($data);
        
        $a=array();
        $b=array();
        
        for($i=0; $i<count($userdata) ; $i++) {
            
            $res = $this->employee_model->clock_user($userdata[$i]->fingerprint, $userdata[$i]->timestamp, $userdata[$i]->facilityId);
            if($res){
        		$b["fingerprint"] = $userdata[$i]->fingerprint;
        		$b["status"] = 'yes';
        		array_push($a,$b);
        	}else{
        		$b["fingerprint"] = $userdata[$i]->fingerprint;
        		$b["status"] = 'no';
        		array_push($a,$b);
        	}
            
        }
        
        echo json_encode($a);
    }

    public function insert_timelog()
    {
        $data = $this->input->raw_input_stream;
        $userdata = json_encode($data);

        $model_response = $this->employee_model->insert_timelog($userdata);
        if($model_response) {
            $response = json_encode(array('error'=> FALSE, 'Time log created'));
        } else {
            $response = json_encode(array('error'=> TRUE, 'Error logging time'));
        }

        echo $response;
    }

    public function enroll()
    {
        $data = $this->input->post('enrollJSON');
        $userdata = json_decode($data);
        
        $a=array();
        $b=array();
        
        for($i=0; $i<count($userdata) ; $i++) {
            
            $res = $this->employee_model->enroll_user($userdata[$i]->fingerprint, $userdata[$i]->ihrispid, $userdata[$i]->facilityId);
            if($res){
        		$b["fingerprint"] = $userdata[$i]->fingerprint;
        		$b["status"] = 'yes';
        		array_push($a,$b);
        	}else{
        		$b["fingerprint"] = $userdata[$i]->fingerprint;
        		$b["status"] = 'no';
        		array_push($a,$b);
        	}
            
        }
        
        echo json_encode($a);
    }
    
    public function enroll_user()
    {
        $userdata['ihris_pid'] = $this->input->post('userId');
        $userdata['facilityId'] = $this->input->post('facilityId');
        
        $model_response = $this->employee_model->enroll_user($userdata);
        if($model_response) {
            $response = array('error'=>FALSE, 'message'=>$model_response);
            echo json_encode($response);
        } else {
            $response = array('error'=>FALSE, 'message'=>$model_response);
            echo json_encode($response);
        }
    }

    public function enrolled($facilityId)
    {
        $model_response = $this->employee_model->get_enrolled_employees(urldecode($facilityId));
        if($model_response) {
            $response = array('error'=> FALSE, 'employees'=> $model_response);
            echo json_encode($response);
        } else {
            $response = array('error'=> TRUE, 'employees'=> '');
            echo json_encode($response);
        }
    }

    public function login()
    {
        $userdata['username'] = $this->input->post('username');
        $userdata['password'] = $this->input->post('password');
        
        $model_response = $this->auth_model->validate_login($userdata);
        
        if($model_response) {

            $response = array('error'=> FALSE, 'message'=>'Successfully Authenticated','status'=>'USER_FOUND', 'user'=>$model_response);
            echo json_encode($response);
        } else {
            $response = array('error'=> TRUE, 'message'=>'Invalid username or password','status'=>'USER_NOT_FOUND');
            echo json_encode($response);
        }
        
    }
    public function login_ion()
    {
        $data = $this->input->raw_input_stream;
        $userdata = json_decode($data,true);
        $model_response = $this->auth_model->validate_login($userdata);
        
        if($model_response) {

            $response = array('error'=> FALSE, 'message'=>'Successfully Authenticated','status'=>'USER_FOUND', 'user'=>$model_response);
            echo json_encode($response);
        } else {
            $response = array('error'=> TRUE, 'message'=>'Invalid username or password','status'=>'USER_NOT_FOUND');
            echo json_encode($response);
        }
        
    }

    public function register()
    {
        $data = $this->input->raw_input_stream;

        $userdata = json_decode($data);
        
        $model_response = $this->auth_model->validate_register($userdata);

        switch ($model_response) {
            case '1':
                echo json_encode(array('error'=>TRUE, 'message'=>'Account Created Successfully'));
                break;
            
            case '0':
                echo json_encode(array('error'=>TRUE, 'message'=>'Unable to create user account'));
                break;
        }
    }

    public function forgot_password()
    {
        $data = $this->input->raw_input_stream;
        $userdata = json_decode($data);

        $model_response = $this->auth_model->password_request_reset($userdata);

        switch ($model_response) {
            case '1':
                echo json_encode(array('error'=>TRUE, 'message'=>'Account reset code sent successfully'));
                break;
            
            case '0':
                echo json_encode(array('error'=>TRUE, 'message'=>'Unable to send reset cde'));
                break;
        }
        
    }

    public function reset_password()
    {
        $data = $this->input->raw_input_stream;
        $userdata = json_decode($data);

        $model_response = $this->auth_model->password_reset($userdata);
    }

    public function get_employees($facility,$page)
    {
        $limit = 10;
        $offset = ($page -1) * 10;
        $employees = $this->employee_model->get_employees($facility,$limit, $offset);
        // echo json_encode($employees);
        $data['employees'] = $employees;
        $data['perPage'] = $limit;
        $data['totalData'] = $this->employee_model->count_employees($facility);
        $data['totalPage'] = ceil($data['totalData'] / $limit);

        echo json_encode(array('data'=> $data));
    }

    public function get_employee($facility,$staffId)
    {
        $employee = $this->employee_model->get_employee($facility,$staffId);
        $clock = $this->employee_model->get_employee_clock($facility, $staffId);

        if($employee) {

            if($clock) {
                $response = array('status'=>'SUCCESS','employee'=>$employee,'clock_status'=>$clock->status);
            } else {
                $response = array('status'=>'SUCCESS','employee'=>$employee,'clock_status'=>false);
            }
           
        } else {
            $response = array('status'=>'FAILED', 'employee'=> false,'clock_status'=>false);
        }

        echo json_encode( $response );
             
    }

    public function search_employees($facility, $search_term)
    {
        
        $employees = $this->employee_model->search_employees($facility, $search_term);
        // echo json_encode($employees);
        $data['employees'] = $employees;

        echo json_encode(array('data'=> $data));
    }

    public function get_schedules($employeeId)
    {
        $schedules = $this->employee_model->get_schedules($employeeId);
        echo json_encode($schedules);
    }

    public function get_summaries($facility)
    {
        $data['staff_total'] = $this->employee_model->count_employees($facility);
        $data['staff_working'] = $this->employee_model->count_employees_working($facility);
        $data['staff_present'] = $this->employee_model->count_employees_present($facility);
        $data['staff_off'] = $this->employee_model->count_employees_off($facility);

        echo json_encode($data);

    }

    public function get_working_today($facility)
    {
        $staff = $this->employee_model->staff_working_today($facility);
        echo json_encode(array('staff'=>$staff));
    }


    public function get_schedule_types($type)
    {
        $data = $this->employee_model->get_schedule_types($type);
        echo json_encode($data);
    }



    public function insert_schedule() {

        $data = $this->input->raw_input_stream;
        $userdata = json_decode( $data );
        
        
        // $date_status=$this->check_deadlines($userdata);
        
        // if($date_status) {
            
        //     $response = 'Scheduling disabled for this date ranges';
            
        // } else {
        //     $response = $this->employee_model->insert_schedule($userdata);
        // }
        
        $response = $this->employee_model->insert_schedule($userdata);

        $final_response = array('status'=>'SUCCESS', 'message'=> $response);

        echo json_encode($final_response);

    }

    public function insert_attendance() 
    {
        $data = $this->input->raw_input_stream;
        $userdata = json_decode( $data );

        if($this->employee_model->insert_attendance($userdata)) {
            $response = array('status'=> 'SUCCESS','message'=>'Success', 'errors'=>false);
        } else {
            $response = array('status'=> 'FAILED','message'=>'Failed', 'errors'=>true);
        }

        echo json_encode($response);

       
    }

    public function get_employee_schedule($facility, $staff_id) {
        $data = $this->employee_model->get_employee_schedule($facility, $staff_id);
        if($data) {
           
            $duty_dates = array();
            foreach($data as $dates) {
                array_push($duty_dates, $dates->duty_date);
            }

            $response = array('status'=> 'SUCCESS','message'=>'Success', 'errors'=>false, 'duty_dates'=> $duty_dates);
            echo json_encode($response);
        } else {
            $response = array('status'=> 'FAILED','message'=>'Failed', 'errors'=>true, 'duty_dates'=> false);
            echo json_encode($response);
        }
    }

    public function get_employee_attendance($facility, $staff_id) {
        $data = $this->employee_model->get_employee_attendance($facility, $staff_id);
        if($data) {
           
            $duty_dates = array();
            foreach($data as $dates) {
                array_push($duty_dates, $dates->date);
            }

            $response = array('status'=> 'SUCCESS','message'=>'Success', 'errors'=>false, 'duty_dates'=> $duty_dates);
            echo json_encode($response);
        } else {
            $response = array('status'=> 'FAILED','message'=>'Failed', 'errors'=>true, 'duty_dates'=> false);
            echo json_encode($response);
        }
    }

   public function clock_in_employee() {
    $data = $this->input->raw_input_stream;
    
    $userdata = json_decode($data);
    if($this->employee_model->clock_in_employee($userdata)) {
        $response = array('status'=>'SUCCESS','message'=>'Employee Clocked In');
    } else {
        $response = array('status'=>'FAILED','message'=>'Unable to clock in Employee');
    }

    echo json_encode($response);
   }

   public function clock_out_employee() {
    $data = $this->input->raw_input_stream;
    
    $userdata = json_decode($data);
    if($this->employee_model->clock_out_employee($userdata)) {
        $response = array('status'=>'SUCCESS','message'=>'Employee Clocked Out');
    } else {
        $response = array('status'=>'FAILED','message'=>'Unable to clock out Employee');
    }

    echo json_encode($response);
   }

   public function reports($report_type) {
    echo $report_type;
   
   }
   


  
}
