<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

echo 'rer';exit;
if ( ! function_exists('booking_status1'))
{
    function booking_status1($podno)
    {
         //get main CodeIgniter object
       $ci =& get_instance();
       
       //load databse library
       $ci->load->database();
       
         $resAct = $ci->db->query("SELECT * from tbl_tracking WHERE pod_no ='".$podno."' ORDER BY tracking_date DESC LIMIT 1");
                   
        if ($resAct->num_rows() > 0) {
            $tracking = $resAct->row();

        }
       
        return 'abxc';
    }   
}