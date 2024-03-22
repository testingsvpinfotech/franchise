<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
class Admin_access_control extends CI_Controller {

	function __construct() {
		parent:: __construct();
		$this->load->model('basic_operation_m');
		$this->load->model('booking_model');	
		$this->load->model('Invoice_model');
		//echo __DIR__;exit;
		if($this->session->userdata('userId') == '')
		{
			redirect('admin');
		}	
	}
	
	public function view_access_control($customer_id) 
	{
		$data[] 					= array();
		$data['customer_info']   	= $this->basic_operation_m->get_query_row("select * from tbl_customers where customer_id = '$customer_id'");
		$data['block_info'] 	  	= $this->basic_operation_m->get_query_result("select * from access_control where customer_id = '$customer_id'");
		
		$all_Data 					= $this->input->post();
		if(!empty($all_Data))
		{
			foreach($all_Data['block_status'] as $key => $values)
			{
				$d_data		= $this->basic_operation_m->insert("access_control",array('customer_id'=>$customer_id,'block_status'=>$values,'reasone'=>$all_Data['reasone']));				
			}
	
			redirect('admin/access-control/'.$customer_id);
		}
		$this->load->view('admin/access_control/view_access_control', $data);	
	}
	
	public function delete_access_block($access_id,$customer_id) 
	{
		if(!empty($access_id) && !empty($customer_id))
		{
		
			$this->basic_operation_m->update('access_control',array('current_status'=>'1'),"ac_id = '$access_id'");
		
			redirect('admin/access-control/'.$customer_id);
		}

	}
}

?>
