<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

		function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('rosta_model');

      
    }
    public function get_ihrisdata(){
        $endpoint='apiv1/index.php/api/allihrisdata';
        
       $response=$this->curlgetHttp($endpoint,$headers=[],$body=[]);
     // print_r($response);
        if($response){
         $message= $this->add_ihrisdata($response);
        
        }
   
        if(count($response)>0){
         $status="successful";
        }
        else{
         $status="failed";
        }
        
       

    }
   //employees all enrolled users before creating new ones.
      
public function add_ihrisdata($data){
    if(count($data)>1){
    $this->db->query("TRUNCATE `ihrisdata`");
    }
    $query = $this->db->insert_batch('ihrisdata',$data);

    if($query){
        $n=$this->db->query("select ihris_pid from ihrisdata");
        
        
        $message="get_ihrisdata() add_ihrisdata()  IHRIS HRH ".$n->num_rows();



    }
    else{
        $message=" get_ihrisdata() add_ihrisdata()  IHRIS HRH FAILED ";

    }

return $message;

}

public function curlgetHttp($endpoint,$headers,$body){
    $url=iHRIS_URL.$endpoint;
    $ch = curl_init($url);

     //post values
    // curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($body));
    // Option to Return the Result, rather than just true/false
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    // Set Request Headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers
    );
    //time to wait while waiting for connection...indefinite
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);

    // curl_setopt($ch,CURLOPT_POST,1);
    //set curl time..processing time out
    curl_setopt($ch, CURLOPT_TIMEOUT, 200);
    // Perform the request, and save content to $result
    $result = curl_exec($ch);
      //curl error handling
      $curl_errno = curl_errno($ch);
              $curl_error = curl_error($ch);
              if ($curl_errno > 0) {
                     curl_close($ch);
                    return  "CURL Error ($curl_errno): $curl_error\n";
                  }
        $info = curl_getinfo($ch);
       curl_close($ch);
       $decodedResponse =json_decode($result);
       return $decodedResponse;
}

    public function person_attend($from,$to) 
    {
       echo json_encode($this->rosta_model->get_attendance($from,$to));
    }
    public function person_roster($from,$to) 
    {
        echo json_encode($this->rosta_model->get_roster($from,$to));
    }
  

}






	
	
	
