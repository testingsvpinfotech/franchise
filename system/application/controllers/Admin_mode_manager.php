<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_mode_manager extends CI_Controller {

	var $data = array();
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
	public function all_mode()
	{  
	   
		$data 		= $this->data;
		$user_id	= $this->session->userdata("userId");
		$data['mode_list']	= $this->basic_operation_m->get_all_result('transfer_mode');		
        $this->load->view('admin/mode_master/view_mode',$data);
      
	}	
	public function add_mode()
	{  	   
		$data 		= $this->data;
		$user_id	= $this->session->userdata("userId");
		$data['allcountry']=$this->basic_operation_m->get_all_result('transfer_mode');
        $this->load->view('admin/mode_master/view_add_mode',$data);      
	}
	
	public function insert_mode()
	{  	   
		$alldata 	= $this->input->post();		
		
		if(!empty($alldata))
		{
			$c_company		= $this->basic_operation_m->insert("transfer_mode",$alldata);
			$msg					= 'Mode uploaded successfully';
			$class					= 'alert alert-success alert-dismissible';				
		}
		else
		{
			$msg			= 'Mode not uploaded successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view-mode');
	}
	
	public function delete_mode(){
	    
	    $id = $this->input->post('getid');
		if(!empty($id))	{
		    
			$airlines_company	= $this->db->delete("transfer_mode" , array('transfer_mode_id' => $id));

			$output['status'] = 'success';
			$output['message'] = 'Mode deleted successfully';
		}
		else{
			$output['status'] = 'error';
			$output['message'] = 'Something went wrong in deleting the Mode';
		}
 
	    	echo json_encode($output);	

    }
   
	
// 	public function delete_mode($id)
// 	{  
// 		if(!empty($id))
// 		{
// 			$airlines_company	= $this->basic_operation_m->delete("transfer_mode","transfer_mode_id = '$id'");
// 			$msg				= 'Mode deleted successfully';
// 			$class				= 'alert alert-success alert-dismissible';	
			
// 		}
// 		else
// 		{
// 			$msg	= 'Mode not deleted successfully';
// 			$class	= 'alert alert-danger alert-dismissible';	
			
// 		}
		
// 		$this->session->set_flashdata('notify',$msg);
// 		$this->session->set_flashdata('class',$class);
		
// 		redirect('admin/view-mode');
// 	}
	
	public function edit_mode($id)
	{  
		$data = $this->data;
				
		if(!empty($id))
		{
			$whr =array('transfer_mode_id'=>$id);
			$data['mode']=$this->basic_operation_m->get_table_row('transfer_mode',$whr);
		}
		
		$this->load->view('admin/mode_master/view_edit_mode',$data);
	}
	
	public function update_mode($id)
	{  
		$alldata 	= $this->input->post();	

		if(!empty($alldata))
		{
			$status	= $this->basic_operation_m->update("transfer_mode",$alldata,"transfer_mode_id = '$id'");
			
			$msg	= 'Mode updated successfully';
			$class	= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg	= 'Mode not updated successfully';
			$class	= 'alert alert-danger alert-dismissible';	
			
		}
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view-mode');
	
	}	
	###################### View All Airlines Flight End ########################	
   
}
?>
