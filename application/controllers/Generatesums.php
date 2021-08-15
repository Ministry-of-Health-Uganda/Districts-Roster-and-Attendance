<?php

class Generatesums extends MX_Controller
{
    //district data share
    public function distdutySums()
    {

        $ymonth = $this->uri->segment(3);
        //$ymonth=$_GET['month'];
        if (!empty($ymonth)) {
            $ymonth;
        } else {
            //last month
            $ymonth = date('Y-m', strtotime('-1 months'));
        }


        if (!empty($ymonth)) {

            ini_set('max_execution_time', 0);
            ignore_user_abort(true);
            $sql = $this->db->query("SET @p0='$ymonth'");
            $sql = $this->db->query("TRUNCATE table dutysummary");
            $sql = $this->db->query("CALL `dutsums`(@p0)");
            if ($sql) {
                echo "Procedure Executed Succesfully";
            } else {
                echo "Procedure Execution Failed";
            }
        }
    }
    public function distattSums()
    {

        $ymonth = $this->uri->segment(3);
        //$ymonth=$_GET['month'];
        if (!empty($ymonth)) {
            $ymonth;
        } else {
            //last month
            $ymonth = date('Y-m', strtotime('-1 months'));
        }

        if (!empty($ymonth)) {
            ini_set('max_execution_time', 0);
            ignore_user_abort(true);
            $sql = $this->db->query("SET @p0='$ymonth'");
            $sql = $this->db->query("TRUNCATE table att_summary");
            $sql = $this->db->query("CALL `att_sums`(@p0)");
            if ($sql) {
                echo "Procedure Executed Succesfully";
            } else {
                echo "Procedure Execution Failed";
            }
        }
    }
    public function mundutySums()
    {

        $ymonth = $this->uri->segment(3);
        //$ymonth=$_GET['month'];
        if (!empty($ymonth)) {
            $ymonth;
        } else {
            //last month
            $ymonth = date('Y-m', strtotime('-1 months'));
        }


        if (!empty($ymonth)) {
            ini_set('max_execution_time', 0);
            ignore_user_abort(true);
            $sql = $this->db->query("SET @p0='$ymonth'");
            $sql = $this->db->query("TRUNCATE table dutymunsummary");
            $sql = $this->db->query("CALL `dutysumsmun`(@p0)");
            if ($sql) {
                echo "Procedure Executed Succesfully";
            } else {
                echo "Procedure Execution Failed";
            }
        }
    }
    public function munattSums()
    {

        $ymonth = $this->uri->segment(3);
        //$ymonth=$_GET['month'];
        if (!empty($ymonth)) {
            $ymonth;
        } else {
            //last month
            $ymonth = date('Y-m', strtotime('-1 months'));
        }

        if (!empty($ymonth)) {
            ini_set('max_execution_time', 0);
            ignore_user_abort(true);
            $sql = $this->db->query("SET @p0='$ymonth'");
            $sql = $this->db->query("TRUNCATE table attmun_summary");
            $sql = $this->db->query("CALL `attmun_sums`(@p0)");
            if ($sql) {
                echo "Procedure Executed Succesfully";
            } else {
                echo "Procedure Execution Failed";
            }
        }
    }
}