<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_gst_setting extends CI_Controller {

	var $data 			= array();
	function __construct()
	{
		 parent:: __construct();
		 $this->load->model('basic_operation_m');
		 if($this->session->userdata('userId') == '')
		 {
			 redirect('admin');
		 }
	}
	
	
	###################### View All Airlines Start ########################
	public function index()
	{  
	   
		$data 							= $this->data;
		$user_id						= $this->session->userdata("userId");
		$data['fule_company']			= $this->basic_operation_m->get_query_result("select * from tbl_gst_setting");
        $this->load->view('admin/gst_master/view_gst_setting',$data);
      
	}
	
	public function add_gst()
	{  
	   
		$data 						= $this->data;		
		$user_id					= $this->session->userdata("userId");
        $this->load->view('admin/gst_master/view_add_gst_setting',$data);
      
	}
	
	public function insert_gst()
	{  
	   
		$alldata= $this->input->post();
		if(!empty($alldata))
		{
			$courier_company		= $this->basic_operation_m->insert("tbl_gst_setting",$alldata);
			$msg					= 'GST uploaded successfully';
			$class					= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg= 'GST not uploaded successfully';
			$class= 'alert alert-danger alert-dismissible';	
			
		}
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/list-gst');
	}
	
	public function delete_gst(){  
	    
	    $id = $this->input->post('getid');
	    
		if(!empty($id))
		{
			$airlines_company		= $this->basic_operation_m->delete("tbl_gst_setting","id = '$id'");
			$msg					= 'GST deleted successfully';
			$class					= 'alert alert-success alert-dismissible';	
			
		
			$output['status'] = 'success';
			$output['message'] = 'GST deleted successfully';
		}
		else{
			$output['status'] = 'error';
			$output['message'] = 'Something went wrong in deleting the GST';
		}
 
		echo json_encode($output);	
	
	}
	
	
// 	public function delete_gst($id)
// 	{  
// 		if(!empty($id))
// 		{
// 			$airlines_company		= $this->basic_operation_m->delete("tbl_gst_setting","id = '$id'");
// 			$msg					= 'GST deleted successfully';
// 			$class					= 'alert alert-success alert-dismissible';	
			
// 		}
		
// 		$this->session->set_flashdata('notify',$msg);
// 		$this->session->set_flashdata('class',$class);
		
// 		redirect('admin/list-gst');
// 	}
	
	public function edit_gst($id)
	{  
		$data= $this->data;
		if(!empty($id))
		{
			$data['gst_list']	= $this->basic_operation_m->get_table_row("tbl_gst_setting","");
		}
		$this->load->view('admin/gst_master/view_edit_gst_setting',$data);
	}
	
	public function update_gst($id)
	{  
		$alldata= $this->input->post();
		if(!empty($alldata))
		{
			$status = $this->basic_operation_m->update("tbl_gst_setting",$alldata,"id = '$id'");			
			$msg	= 'GST updated successfully';
			$class  = 'alert alert-success alert-dismissible';	
		}
		else
		{
			$msg= 'GST not updated successfully';
			$class= 'alert alert-danger alert-dismissible';	
		}
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/list-gst');
	
	}
	
	
	
	###################### View All Airlines End ########################	
	
	
   
}
?>
