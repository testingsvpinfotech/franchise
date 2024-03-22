<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_fuel extends CI_Controller {

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
	public function all_fuel()
	{  
	   
		$data 							= $this->data;
		$user_id						= $this->session->userdata("userId");
		$data['fule_company']			= $this->basic_operation_m->get_query_result("select *,courier_fuel.company_type from courier_fuel left join courier_company on courier_company.c_id = courier_fuel.courier_id left join tbl_customers on tbl_customers.customer_id = courier_fuel.customer_id");
        $this->load->view('admin/fuel_master/view_fuel',$data);
      
	}
	
	public function addfuel()
	{  
	   
		$data 						= $this->data;
		$data['courier_company']	= $this->basic_operation_m->get_all_result("courier_company","","c_company_name asc");
		
		$data['all_customer']		= $this->basic_operation_m->get_all_result("tbl_customers","","customer_name asc");
		$user_id					= $this->session->userdata("userId");
        $this->load->view('admin/fuel_master/view_add_fuel',$data);
      
	}
	
	public function insertfuel()
	{  
	   
		$alldata 							= $this->input->post();
		$n_data 							= $alldata;
		unset($n_data['cod_range_from']);
		unset($n_data['cod_range_to']);
		unset($n_data['cod_range_rate']);
		unset($n_data['topay_range_from']);
		unset($n_data['topay_range_to']);
		unset($n_data['topay_range_rate']);
		
		$cf_id				= $this->basic_operation_m->insert("courier_fuel",$n_data);
		if(!empty($alldata))
		{
			if(!empty($alldata['cod_range_from']) )
			{
				foreach($alldata['cod_range_from'] as $key => $values)
				{
					$this->basic_operation_m->insert("courier_fuel_detail",array('cf_id'=>$cf_id,'cod_range_from'=>$alldata['cod_range_from'][$key],'cod_range_to'=>$alldata['cod_range_to'][$key],'cod_range_rate'=>$alldata['cod_range_rate'][$key]));
				}
			}
			
			if(!empty($alldata['topay_range_from']))
			{
				foreach($alldata['topay_range_from'] as $key => $values)
				{
					$this->basic_operation_m->insert("courier_fuel_detail",array('cf_id'=>$cf_id,'topay_range_from'=>$alldata['topay_range_from'][$key],'topay_range_to'=>$alldata['topay_range_to'][$key],'topay_range_rate'=>$alldata['topay_range_rate'][$key]));
				}
			}
			
			$msg					= 'Fuel uploaded successfully';
			$class					= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg			= 'Fuel not uploaded successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/all-fuel');
	}
	
	public function deletefuel()
	{  
	     $id = $this->input->post('getid');
		if(!empty($id))
		{
			$airlines_company		= $this->basic_operation_m->delete("courier_fuel","cf_id = '$id'");
			$airlines_company		= $this->basic_operation_m->delete("courier_fuel_detail","cf_id = '$id'");

			$output['status'] = 'success';
			$output['message'] = 'Fule deleted successfully';
		}
		else{
			$output['status'] = 'error';
			$output['message'] = 'Something went wrong in deleting the Fule';
		}
 
		echo json_encode($output);	
	}
	
// 	public function deletefuel()
// 	{  
// 	     $id = $this->input->post('getid');
// 		if(!empty($id))
// 		{
// 			$airlines_company		= $this->basic_operation_m->delete("courier_fuel","cf_id = '$id'");
// 			$airlines_company		= $this->basic_operation_m->delete("courier_fuel_detail","cf_id = '$id'");
// 			$msg					= 'Fuel deleted successfully';
// 			$class					= 'alert alert-success alert-dismissible';	
			
// 		}
// 		else
// 		{
// 			$msg			= 'Fuel not deleted successfully';
// 			$class			= 'alert alert-danger alert-dismissible';	
			
// 		}
		
// 		$this->session->set_flashdata('notify',$msg);
// 		$this->session->set_flashdata('class',$class);
		
// 		redirect('admin/all-fuel');
// 	}

	
	public function editfuel($id)
	{  
		$data				 				= $this->data;
		if(!empty($id))
		{
			$data['courier_company']	= $this->basic_operation_m->get_all_result("courier_company","");
			$data['fuel_list']		= $this->basic_operation_m->get_query_row("select * from courier_fuel  where courier_fuel.cf_id = '$id'");
			$data['fuel_detail']		= $this->basic_operation_m->get_query_result("select * from courier_fuel_detail  where cf_id = '$id'");
			$data['all_customer']		= $this->basic_operation_m->get_all_result("tbl_customers","");
			
		}
		$this->load->view('admin/fuel_master/view_edit_fuel',$data);
	}
	
	public function updatefuel($id)
	{  
		$alldata 							= $this->input->post();
		$n_data 							= $alldata;
		unset($n_data['cod_range_from']);
		unset($n_data['cod_range_to']);
		unset($n_data['cod_range_rate']);
		unset($n_data['topay_range_from']);
		unset($n_data['topay_range_to']);
		unset($n_data['topay_range_rate']);
		
		if(!empty($alldata))
		{
			$status							= $this->basic_operation_m->update("courier_fuel",$n_data,"cf_id = '$id'");
		
			$airlines_company				= $this->basic_operation_m->delete("courier_fuel_detail","cf_id = '$id'");
			if(!empty($alldata['cod_range_from']) )
			{
				foreach($alldata['cod_range_from'] as $key => $values)
				{
					$this->basic_operation_m->insert("courier_fuel_detail",array('cf_id'=>$id,'cod_range_from'=>$alldata['cod_range_from'][$key],'cod_range_to'=>$alldata['cod_range_to'][$key],'cod_range_rate'=>$alldata['cod_range_rate'][$key]));
				}
			}
			
			if(!empty($alldata['topay_range_from']))
			{
				foreach($alldata['topay_range_from'] as $key => $values)
				{
					$this->basic_operation_m->insert("courier_fuel_detail",array('cf_id'=>$id,'topay_range_from'=>$alldata['topay_range_from'][$key],'topay_range_to'=>$alldata['topay_range_to'][$key],'topay_range_rate'=>$alldata['topay_range_rate'][$key]));
				}
			}
			
			$msg							= 'Fuel updated successfully';
			$class							= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg			= 'Fuel not updated successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/all-fuel');
	
	}
	
	
	
	###################### View All Airlines End ########################	
	
	
   
}
?>
