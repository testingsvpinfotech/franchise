<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_coloader extends CI_Controller {

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
	public function all_coloader()
	{  
	   
		$data 							= $this->data;
		$user_id						= $this->session->userdata("userId");
		$data['fule_company']			= $this->basic_operation_m->get_query_result("select * from tbl_coloader");
        $this->load->view('admin/coloader_master/view_coloader',$data);
      
	}
	
	public function add_coloader()
	{  
	   
		$data 						= $this->data;	
		$user_id					= $this->session->userdata("userId");

		$data['city']			= $this->basic_operation_m->get_query_result("select * from city");
        $this->load->view('admin/coloader_master/view_add_coloader',$data);
      
	}
	
	public function insert_coloader()
	{  
	   
		$alldata 							= $this->input->post();

		// echo "<pre>";
		// print_r($alldata);exit();
		if(!empty($alldata))
		{
			$alldata2 = array(
				'coloader_name' => $alldata['coloader_name'],
				'company_name' => $alldata['company_name'],
				'company_add' => $alldata['company_add'],
				'company_contact' => $alldata['company_contact'],
				'contact_person' => $alldata['contact_person'],
				'gst_no' => $alldata['gst_no'],
				// 'min_rate' => $alldata['min_rate'],
				// 'location' => $alldata['location'],
				// 'per_kg_rate' => $alldata['per_kg_rate'],
			);
			$coloader_list		= $this->basic_operation_m->insert("tbl_coloader",$alldata2);

			$coloader_id = $this->db->insert_id();

			$datec = date('Y-m-d h:i:s');

			if (!empty(@$alldata['from_city'])) {
				foreach ($alldata['from_city'] as $key => $value) {
					$data = array(
						'coloader_id' => $coloader_id,
						'from_city' => $value,
						'to_city' => $alldata['to_city'][$key],
						'min_amt' => $alldata['min_amount'][$key],
						'per_kg_rate' => $alldata['per_kg'][$key],
						'applicable_date' => date('Y-m-d',strtotime($alldata['applicable_date'][$key])),
						'datec' => $datec,
					);

					$this->basic_operation_m->insert("coloader_rate",$data);
				}
			}

			$msg					= 'Coloader uploaded successfully';
			$class					= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg			= 'Coloader not uploaded successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/all-coloader');
	}

	public function add_rate($coloader_id)
	{  
	   
		$alldata 							= $this->input->post();

		echo "<pre>";
		// print_r($alldata);exit();
		if(!empty($alldata))
		{
		
			$datec = date('Y-m-d h:i:s');
			if (!empty(@$alldata['from_city'])) {
				foreach ($alldata['from_city'] as $key => $value) {
					$data = array(
						'coloader_id' => $coloader_id,
						'from_city' => $value,
						'to_city' => $alldata['to_city'][$key],
						'min_amt' => $alldata['min_amount'][$key],
						'per_kg_rate' => $alldata['per_kg'][$key],
						'applicable_date' => date('Y-m-d',strtotime($alldata['applicable_date'][$key])),
						'datec' => $datec,
					);

					$this->basic_operation_m->insert("coloader_rate",$data);

					print_r($data);
				}
			}

			$msg					= 'Coloader uploaded successfully';
			$class					= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg			= 'Coloader not uploaded successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/all-coloader');
	}
	
	public function delete_coloader()
	{
	    $id = $this->input->post('getid');
		$deleteColoader		= $this->db->delete("tbl_coloader", array('id' => $id));
		
		if($deleteColoader){
		    
    		$output['status'] = 'success';
			$output['message'] = 'coloader deleted successfully';
		}
		else{
			$output['status'] = 'error';
			$output['message'] = 'Something went wrong in deleting the coloader';
		}
 
		echo json_encode($output);	
	}
	
	
// 	public function delete_coloader($id)
// 	{
// 		$airlines_company		= $this->basic_operation_m->delete("tbl_coloader","id = '$id'");
// 		$msg					= 'Coloader deleted successfully';
// 		$class					= 'alert alert-success alert-dismissible';	
			
// 		$this->session->set_flashdata('notify',$msg);
// 		$this->session->set_flashdata('class',$class);
		
// 		redirect('admin/all-coloader');
// 	}
	
	public function edit_coloader($id)
	{  
		$data	= $this->data;
		if(!empty($id))
		{		
			$data['coloader_list']		= $this->basic_operation_m->get_query_row("select * from tbl_coloader where id = '$id'");			
			$data['city_rate_list']		= $this->basic_operation_m->get_table_result("coloader_rate","coloader_id = '$id'");

			$data['city'] = $this->basic_operation_m->get_query_result("select * from city");
		}
		$this->load->view('admin/coloader_master/view_edit_coloader',$data);
	}

	public function detail_coloader($id)
	{  
		$data	= $this->data;
		if(!empty($id))
		{		
			$data['coloader_list']		= $this->basic_operation_m->get_query_row("select * from tbl_coloader where id = '$id'");			
			$data['city_rate_list']		= $this->basic_operation_m->get_table_result("coloader_rate","coloader_id = '$id'");

			$data['city'] = $this->basic_operation_m->get_query_result("select * from city");
		}
		$this->load->view('admin/coloader_master/view_detail_coloader',$data);
	}
	
	public function update_coloader($id)
	{ 
		$alldata = $this->input->post();
		if(!empty($alldata))
		{
			$alldata2 = array(
				'coloader_name' => $alldata['coloader_name'],
				'company_name' => $alldata['company_name'],
				'company_add' => $alldata['company_add'],
				'company_contact' => $alldata['company_contact'],
				'contact_person' => $alldata['contact_person'],
				'gst_no' => $alldata['gst_no'],
				// 'min_rate' => $alldata['min_rate'],
				// 'location' => $alldata['location'],
				// 'per_kg_rate' => $alldata['per_kg_rate'],
			);
			$status= $this->basic_operation_m->update("tbl_coloader",$alldata2,"id = '$id'");


			$datec = date('Y-m-d h:i:s');

			$this->db->delete('coloader_rate',array('coloader_id'=>$id));

			if (!empty(@$alldata['from_city'])) {
				foreach ($alldata['from_city'] as $key => $value) {
					$data = array(
						'coloader_id' => $id,
						'from_city' => $value,
						'to_city' => $alldata['to_city'][$key],
						'min_amt' => $alldata['min_amount'][$key],
						'per_kg_rate' => $alldata['per_kg'][$key],
						'applicable_date' => date('Y-m-d',strtotime($alldata['applicable_date'][$key])),
						'datec' => $datec,
					);

					// $this->basic_operation_m->insert("coloader_rate",$data);
				}
			}			
			$msg							= 'Coloader updated successfully';
			$class							= 'alert alert-success alert-dismissible';	
		}
		else
		{
			$msg	= 'Coloader not updated successfully';
			$class	= 'alert alert-danger alert-dismissible';	
			
		}			
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);		
		redirect('admin/all-coloader');
	
	}



	public function update_city_rate()
	{ 
		$alldata = $this->input->post();
		if(!empty($alldata))
		{
			$datec = date('Y-m-d h:i:s');
			$clr_id = $alldata['clr_id'];
			$data = array(
				// 'coloader_id' => $id,
				'from_city' => $alldata['from_city'],
				'to_city' => $alldata['to_city'],
				'min_amt' => $alldata['min_amount'],
				'per_kg_rate' => $alldata['per_kg'],
				'applicable_date' => date('Y-m-d',strtotime($alldata['applicable_date'])),
				'last_update' => $datec,
			);
			$status= $this->basic_operation_m->update("coloader_rate",$data,"clr_id = '$clr_id'");


			

						
			$msg							= 'Coloader updated successfully';
			$class							= 'alert alert-success alert-dismissible';

			echo "1";	
		}
		else
		{
			$msg	= 'Coloader not updated successfully';
			$class	= 'alert alert-danger alert-dismissible';	
			echo "0";
			
		}			
		
				
		// redirect('admin/all-coloader');
	
	}
	
	
	
	###################### View All Airlines End ########################	
	
	
   
}
?>
