<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_contact extends CI_Controller {

	function __construct()
	{
		 parent:: __construct();
		 $this->load->model('basic_operation_m');
		 if($this->session->userdata('userId') == '')
		{
			redirect('admin');
		}
	}
	public function index()
	{
        $data= array();
		$data['allcontact']=$this->basic_operation_m->get_all_result('tbl_contact_enquiry','');   
        $this->load->view('admin/contact_master/view_contact',$data);
	}
	
	
	public function request_quote()
	{
		$data= array();
		$data['allrequest']= $this->basic_operation_m->get_all_result('tbl_request','');
	    $this->load->view('admin/contact_master/view_request_quote',$data);
	}
	
	
}

?>