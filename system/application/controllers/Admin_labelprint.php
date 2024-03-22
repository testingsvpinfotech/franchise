<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_labelprint extends CI_Controller  {

    function __construct() {
        parent:: __construct();
        $this->load->model('basic_operation_m');
        $this->load->model('booking_model');
        if($this->session->userdata('userId') == '')
        {
            redirect('admin');
        }
    }

    public function index() {      
        $data = [];
        $user_id = $this->session->userdata("userId");
        $user_type = $this->session->userdata("userType");
        
            if ($this->session->userdata("userType") == '1') {
                // $resAct = $this->db->query("select * from tbl_charges,tbl_booking,tbl_weight_details 
                //   where tbl_charges.booking_id =tbl_booking.booking_id 
                //   and tbl_weight_details.booking_id=tbl_charges.booking_id and booking_type = 1 AND tbl_booking.user_type !=5 order by tbl_booking.booking_date DESC ");


                //if ($resAct->num_rows() > 0) {
                    $whr = array('booking_type'=>1,'user_type!='=>5);
                    $data['allpoddata'] = $this->booking_model->get_all_pod_data($whr,"");
                //}
            } else {
                $where = '';
                if($this->session->userdata("userType") == '5') {
                    $where = ' AND tbl_booking.user_id = '.$user_id;
                }
                $username = $this->session->userdata("userName");
                $whr = array('username' => $username);
                $res = $this->basic_operation_m->getAll('tbl_users', $whr);
                $branch_id = $res->row()->branch_id;

                $data = array();
               
                $resAct = $this->db->query("select * from tbl_charges,tbl_booking,tbl_weight_details 
                  where tbl_charges.booking_id =tbl_booking.booking_id 
                  and tbl_weight_details.booking_id=tbl_charges.booking_id and tbl_booking.branch_id='$branch_id' and booking_type = 1 $where order by `tbl_booking`.`booking_date` DESC");
                if ($resAct->num_rows() > 0) {
                    $data['allpoddata'] = $resAct->result_array();
                }
            }
            $data['viewVerified'] = 2;
            $this->load->view('admin/Label_print/view_label_print', $data);
       
    }

    public function mics_printlabel($prntext){
        // Load library
	    $this->load->library('zend');
		// Load in folder Zend
		$this->zend->load('Zend/Barcode'); 
		$prntext = $prntext . str_repeat('=', strlen($prntext) % 4);
		$prntext = base64_decode($prntext);
		$data['prntext'] = $prntext;        
		$this->load->view('admin/Label_print/mics_print', $data);
	}
	
	public function forwarder_printlabel($prntext){
		// Load library
	    $this->load->library('zend');
		// Load in folder Zend
		$this->zend->load('Zend/Barcode');

		$last = $this->uri->total_segments();
		//print_r($last);
        $prntext = $this->uri->segment($last);

		$prntext = $prntext . str_repeat('=', strlen($prntext) % 5);
		
		$prntext = base64_decode($prntext);
		
		$data['prntext'] = $prntext;
		$this->load->view('admin/Label_print/forwarder_print', $data);
	}
}
?>
