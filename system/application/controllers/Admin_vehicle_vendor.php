<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_vehicle_vendor extends CI_Controller {

	function __construct()
	{
		 parent:: __construct();
		 $this->load->model('basic_operation_m');
		 if($this->session->userdata('userId') == '')
		{
			redirect('admin');
		}
	}
	
// 		public function index()
// 	{		
//         $data= array();		
// 		$resAct	= $this->db->query("select * from vehicle_type_master");		
// 		if($resAct->num_rows()>0)
// 		{
// 		 	$data['allvehicletype']=$resAct->result_array();             
//         }
//         $this->load->view('admin/vehicle_type_master/view_vehicle',$data);       
// 	}

	
	public function add_vehicle_vendor()
	{      
	    
		$data['message']				= "";
		$array['airway_no_from'] 		= array();
		$array['airway_no_to'] 			= array();
		$array['branch_code'] 			= array();
		
		if(isset($_POST['submit']))
        {
           	$all_data = array(
				'name'=>$this->input->post('name'),
				'mobile_no'=>$this->input->post('mobile_no'),
				'email'=>$this->input->post('email'),
				'r_address' => $this->input->post('r_address'),
				'current_business' => $this->input->post('current_business'),
				'city' => $this->input->post('city'),
				'state' => $this->input->post('state'),
				'country_id' => $this->input->post('country_id'),
				'pin_code' => $this->input->post('pin_code'),
				'service_provider'=> $this->input->post('service_provider'),
				'credit_days' => $this->input->post('credit_days'),
				'document_no' => $this->input->post('document_no'),
				'statutory' => $this->input->post('statutory'),
				'vts'=>$this->input->post('vts'),
				'type_vehicle'=>$this->input->post('type_vehicle'),
				'bank_name' => $this->input->post('bank_name'),
				'account_no' => $this->input->post('account_no'),
				'ifsc_code' => $this->input->post('ifsc_code'),
				
				);
			$path = 'assets/vehicle_vendor_upload/';
			$this->load->library("excel");
			$config['upload_path'] = $path;
			$config['allowed_types'] = 'png|jpeg';
			$config['remove_spaces'] = TRUE;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);            
			if (!$this->upload->do_upload('uploadFile')) {
				$error = array('error' => $this->upload->display_errors());
			} else {
				$all_data = array('document_file' => $this->upload->data());
			}
            
			unset($all_data['submit']);
			$result=$this->basic_operation_m->insert('tbl_vendor_branch',$all_data);
			if ($result) {

				$user_id = $this->db->insert_id();
				$branch = $this->input->post('branch');
				for ($i = 0; $i < count($branch); $i++) {
					$value = [
						'v_id' => $user_id,
						'branch' => $branch[$i]
					];
					$query = $this->basic_operation_m->insert('tbl_vendor_branch', $value);
				}
			
               }
			if($this->db->affected_rows()>0) 
			{
				$data['message']="cnode Added Sucessfully";
			}
			else
			{
                $data['message']="Error in Query";
			}
				redirect('admin/add-vehicle-vendor');
		}
		  $data['country']	= $this->basic_operation_m->get_all_result('tbl_country', '');
		  $data['vehicle']	= $this->basic_operation_m->get_all_result('vehicle_type_master', '');
		 $this->load->view('admin/vehicle_vendor_master/view_add_vehicle_vendor',$data);
	}
	
		public function edit_vehicle_type($vehicle_id)
	{
		$data['message']="";
		$resAct=$this->basic_operation_m->getAll('vehicle_type_master',"id = '$vehicle_id'");
		if($resAct->num_rows()>0)
		{
			$data['vehicle_info']=$resAct->row();
		} 
	   
		if(isset($_POST['submit'])) 
		{
			$all_data = $this->input->post();
			unset($all_data['submit']);
			$whr =array('id'=>$vehicle_id);
			$result=$this->basic_operation_m->update('vehicle_type_master',$all_data, $whr);
			if ($this->db->affected_rows() > 0) 
			{
				$data['message']="Cnode Updated Sucessfully";
			}
			else
			{
                $data['message']="Error in Query";
			}
            redirect('admin/view-vehicle-type');
		}
	    $this->load->view('admin/vehicle_type_master/edit_vehicle',$data);
	}

	public function delete_vehicle_type($id)
	{
		$data['message']="";
		if($id!="")
		{
		    $whr =array('id'=>$id);
			$res=$this->basic_operation_m->delete('vehicle_type_master',$whr);
				if ($this->db->affected_rows() > 0) 
			{
				$data['message']="Deleted Sucessfully";
			}
			else
			{
                $data['message']="Error in Query";
			}
            redirect('admin/view-vehicle-type');
		}		
	  
	}
	
	
	
	
}
?>