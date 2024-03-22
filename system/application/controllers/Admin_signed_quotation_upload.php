<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_signed_quotation_upload extends CI_Controller {

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
	public function view_signed_quatation($customer_id,$c_courier_id,$applicable_from)
	{  
		$data 							= $this->data;
		$data['customer_id']			= $customer_id;
		$data['c_courier_id']			= $c_courier_id;
		$data['applicable_from']		= $applicable_from;
		
		$user_id						= $this->session->userdata("userId");
		$data['signed_quatation']			= $this->basic_operation_m->get_query_result("select * from signed_quatation where customer_id='$customer_id' and c_courier_id ='$c_courier_id' and applicable_from = '$applicable_from'");
        $this->load->view('admin/signed_quotation/view_signed_quatation',$data);
      
	}
	
	public function view_add_signed_quatation($customer_id,$c_courier_id,$applicable_from)
	{  
	   
		$data 						= $this->data;	
		$data['customer_id']			= $customer_id;
		$data['c_courier_id']			= $c_courier_id;
		$data['applicable_from']		= $applicable_from;
		$user_id					= $this->session->userdata("userId");
        $this->load->view('admin/signed_quotation/view_add_signed_quatation',$data);
      
	}
	
	public function insert_signed_quatation()
	{  
	   
		$alldata 							= $this->input->post();
		
		if(!empty($alldata))
		{
			$this->load->library('upload');
			$dataInfo = array();
			$files = $_FILES;
			$cpt = count($_FILES['attechment']['name']);
			for($i=0; $i<$cpt; $i++)
			{           
				$_FILES['attechment']['name']= $files['attechment']['name'][$i];
				$_FILES['attechment']['type']= $files['attechment']['type'][$i];
				$_FILES['attechment']['tmp_name']= $files['attechment']['tmp_name'][$i];
				$_FILES['attechment']['error']= $files['attechment']['error'][$i];
				$_FILES['attechment']['size']= $files['attechment']['size'][$i];    
				
				$uploadPath = 'assets/signed_qutation/';
				$config['upload_path'] = $uploadPath;
				$config['allowed_types'] = 'pdf';
				$config['encrypt_name'] = TRUE;

				// Load and initialize upload library
				$this->load->library('upload', $config);
				$this->upload->initialize($config);

				// Upload file to server
				if($this->upload->do_upload('attechment'))
				{
					// Uploaded file data
					$imageData = $this->upload->data();
					$alldata['attechment'] = $imageData['file_name'];
					
					$this->basic_operation_m->insert("signed_quatation",$alldata);
				}
				else
				{
					$error = array('error' => $this->upload->display_errors());
					
				}
				

			}
			 
			$msg					= 'Signed Qutation uploaded successfully';
			$class					= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg			= 'Signed Qutation not uploaded successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		redirect('admin/signed-quotation-upload/'.$alldata['customer_id'].'/'.$alldata['c_courier_id'].'/'.$alldata['applicable_from']);
	}
	
	public function delete_signed_quatation($sq_id)
	{
		$sq_id_info	= $this->basic_operation_m->get_query_row("select * from signed_quatation where sq_id = '$sq_id'");			
		$this->basic_operation_m->delete("signed_quatation","sq_id = '$sq_id'");
		$msg					= 'Signed Qutation deleted successfully';
		$class					= 'alert alert-success alert-dismissible';	
			
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		$attechent_url 	= 'assets/signed_qutation/'.$sq_id_info->attechment;
		unlink($attechent_url);
		
		redirect('admin/signed-quotation-upload/'.$sq_id_info->customer_id.'/'.$sq_id_info->c_courier_id.'/'.$sq_id_info->applicable_from);
	}
	
	public function edit_signed_quatation($sq_id)
	{  
		$data	= $this->data;
		if(!empty($sq_id))
		{	
			$data['sq_id_info']	= $this->basic_operation_m->get_query_row("select * from signed_quatation where sq_id = '$sq_id'");				
		}
		$this->load->view('admin/signed_quotation/view_edit_signed_quatation',$data);
	}
	
	public function update_signed_quatation($sq_id)
	{ 
		$sq_id_info	= $this->basic_operation_m->get_query_row("select * from signed_quatation where sq_id = '$sq_id'");			
		$alldata = $this->input->post();
		if(!empty($alldata))
		{
			$uploadPath = 'assets/signed_qutation/';
			$config['upload_path'] = $uploadPath;
			$config['allowed_types'] = 'pdf';
			$config['encrypt_name'] = TRUE;

			// Load and initialize upload library
			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			// Upload file to server
			if($this->upload->do_upload('attechment'))
			{
				// Uploaded file data
				$imageData = $this->upload->data();
				$alldata['attechment'] = $imageData['file_name'];
			}
			else
			{
				$error = array('error' => $this->upload->display_errors());
				
			}
			
			$status							= $this->basic_operation_m->update("signed_quatation",$alldata,"sq_id = '$sq_id'");			
			$msg							= 'Signed Qutation updated successfully';
			$class							= 'alert alert-success alert-dismissible';	
		}
		else
		{
			$msg	= 'Signed Qutation not updated successfully';
			$class	= 'alert alert-danger alert-dismissible';	
			
		}			
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);		
		redirect('admin/signed-quotation-upload/'.$sq_id_info->customer_id.'/'.$sq_id_info->c_courier_id.'/'.$sq_id_info->applicable_from);
	
	}
	
	
	
	###################### View All Airlines End ########################	
	
	
   
}
?>
