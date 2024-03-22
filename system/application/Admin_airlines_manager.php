<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_airlines_manager extends CI_Controller {

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
	public function all_airlines()
	{  
	   
		$data 							= $this->data;
		$user_id						= $this->session->userdata("userId");
		$data['airlines_company']		= $this->basic_operation_m->get_table_result("air_aircompany","");
        $this->load->view('admin/airlines/view_airlines_company',$data);
      
	}
	
	public function addairlines()
	{  
	   
		$data 							= $this->data;
		$user_id						= $this->session->userdata("userId");
        $this->load->view('admin/airlines/view_add_airlines_company',$data);
      
	}
	
	public function insertairlines()
	{  
	   
		$alldata 							= $this->input->post();
		if(!empty($alldata))
		{
			$airlines_company		= $this->basic_operation_m->insert("air_aircompany",$alldata);
			$msg					= 'Airlines uploaded successfully';
			$class					= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg			= 'Airlines not uploaded successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view-airlines');
	}
	
	public function deleteairlines($id)
	{  
		if(!empty($id))
		{
			$airlines_company		= $this->basic_operation_m->delete("air_aircompany","a_id = '$id'");
			$msg					= 'Airlines deleted successfully';
			$class					= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg			= 'Airlines not deleted successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view-airlines');
	}
	
	public function editairlines($id)
	{  
		$data				 				= $this->data;
		if(!empty($id))
		{
			$data['airlines_company']		= $this->basic_operation_m->get_table_row("air_aircompany","a_id = '$id'");
			
		}
		
		$this->load->view('admin/airlines/view_edit_airlines_company',$data);
	}
	
	public function updateairlines($id)
	{  
		$alldata 							= $this->input->post();
		if(!empty($alldata))
		{
			$status							= $this->basic_operation_m->update("air_aircompany",$alldata,"a_id = '$id'");
			
			$msg							= 'Airlines updated successfully';
			$class							= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg			= 'Airlines not updated successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view-airlines');
	
	}
	
	public function getairportcode()
	{  
		$html 								= '';
		$alldata 							= $this->input->post();
		if(!empty($alldata))
		{
			$all_airport					= $this->basic_operation_m->get_query_result("SELECT * FROM air_airport WHERE (airport_name like '%" . $alldata["keyword"] . "%' or airport_code like '%" . $alldata["keyword"] . "%' or airport_city like '%" . $alldata["keyword"] . "%' or airport_country like '%" . $alldata["keyword"] . "%') LIMIT 5");
			if(!empty($all_airport)) 
			{
			 
				$html		.='<ul id="country-list" >';
				
					foreach($all_airport as $airport_list) 
					{
				
						$airport_code		= "'".$airport_list->airport_code."'";
						if($alldata['box'] == 1)
						{
							$html		.='<li onClick="selectCountry('.$airport_code.');">'.$airport_list->airport_code.'</li>';
						}
						else
						{
							$html		.='<li onClick="selectCountryy('.$airport_code.');">'.$airport_list->airport_code.'</li>';
						}
					}
				
				$html		.='</ul>';
			}
			
		}
		echo $html;
	}
	
	
	
	###################### View All Airlines End ########################	
	
	###################### View All Airport Start ########################
	public function all_airport()
	{  
	   
		$data 							= $this->data;
		$user_id						= $this->session->userdata("userId");
		$data['all_airport']			= $this->basic_operation_m->get_table_result("air_airport","");
        $this->load->view('admin/airlines/view_airport',$data);
      
	}
	
	public function addairport()
	{  
	   
		$data 							= $this->data;
		$user_id						= $this->session->userdata("userId");
        $this->load->view('admin/airlines/view_add_airport',$data);
      
	}
	
	public function insertairport()
	{  
	   
		$alldata 							= $this->input->post();
		if(!empty($alldata))
		{
			$airlines_company		= $this->basic_operation_m->insert("air_airport",$alldata);
			$msg					= 'Airport uploaded successfully';
			$class					= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg			= 'Airport not uploaded successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view-airport');
	}
	
	public function deleteairport($aa_id)
	{  
		if(!empty($aa_id))
		{
			$airlines_company		= $this->basic_operation_m->delete("air_airport","aa_id = '$aa_id'");
			$msg					= 'Airport deleted successfully';
			$class					= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg			= 'Airport not deleted successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view-airport');
	}
	
	public function editairport($aa_id)
	{  
		$data				 				= $this->data;
		if(!empty($aa_id))
		{
			$data['airport_info']		= $this->basic_operation_m->get_table_row("air_airport","aa_id = '$aa_id'");
			
		}
		
		$this->load->view('admin/airlines/view_edit_airport',$data);
	}
	
	public function updateairport($aa_id)
	{  
		$alldata 							= $this->input->post();
		if(!empty($alldata))
		{
			$status							= $this->basic_operation_m->update("air_airport",$alldata,"aa_id = '$aa_id'");
			
			$msg							= 'Airport updated successfully';
			$class							= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg			= 'Airport not updated successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view-airport');
	
	}
	
	###################### View All Airport End ########################	
	
	
	###################### View All Airlines Flight Start ########################
	public function airlines_Flight()
	{  
	   
		$data 							= $this->data;
		$user_id						= $this->session->userdata("userId");
		$data['airlines_company']		= $this->basic_operation_m->get_query_result("select * from air_flight left join air_aircompany on air_aircompany.a_id = air_flight.aid");
        $this->load->view('admin/airlines/view_airlines_flight',$data);
      
	}
	
	public function addairlinesFlight()
	{  
	   
		$data 							= $this->data;
		$user_id						= $this->session->userdata("userId");
		$data['airlines_company']		= $this->basic_operation_m->get_table_result("air_aircompany","");
        $this->load->view('admin/airlines/view_add_airlines_flight',$data);
      
	}
	
	public function insertairlinesFlight()
	{  
	   
		$alldata 							= $this->input->post();
		if(!empty($alldata))
		{
			$airlines_company		= $this->basic_operation_m->insert("air_flight",$alldata);
			$msg					= 'Airlines Flight uploaded successfully';
			$class					= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg			= 'Airlines Flight not uploaded successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		 redirect('admin/view-airlines-flight');
	}
	
	public function deleteFlight($af_id)
	{  
		if(!empty($af_id))
		{
			$airlines_company		= $this->basic_operation_m->delete("air_flight","af_id = '$af_id'");
			$msg					= 'Airlines Flight deleted successfully';
			$class					= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg			= 'Airlines Flight not deleted successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view-airlines-flight');
	}
	
	public function editFlight($af_id)
	{  
		$data				 				= $this->data;
		if(!empty($af_id))
		{
			$data['airlines_company']		= $this->basic_operation_m->get_table_result("air_aircompany","");
			$data['Flight_info']			= $this->basic_operation_m->get_table_row("air_flight","af_id = '$af_id'");
			
		}
		
		$this->load->view('admin/airlines/view_edit_airlines_flight',$data);
	}
	
	public function updateairlinesFlight($af_id)
	{  
		$alldata 							= $this->input->post();
		if(!empty($alldata))
		{
			$status							= $this->basic_operation_m->update("air_flight",$alldata,"af_id = '$af_id'");
			
			$msg							= 'Airlines Flight updated successfully';
			$class							= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg			= 'Airlines Flight not updated successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view-airlines-flight');
	
	}
	
	###################### View All Airlines Flight End ########################
	
	###################### View All Airlines Flight TypeStart ########################
	public function airlines_flight_type()
	{  
	   
		$data 							= $this->data;
		$user_id						= $this->session->userdata("userId");
		$data['airlines_company']		= $this->basic_operation_m->get_query_result("select * from air_flight_type");
        $this->load->view('admin/airlines/view_airlines_flight_type',$data);
      
	}
	
	public function addairlinesFlighttype()
	{  
	   
		$data 							= $this->data;
		$user_id						= $this->session->userdata("userId");
        $this->load->view('admin/airlines/view_add_airlines_flight_type',$data);
      
	}
	
	public function insertairlinesFlighttype()
	{  
	   
		$alldata 							= $this->input->post();
		if(!empty($alldata))
		{
			$airlines_company		= $this->basic_operation_m->insert("air_flight_type",$alldata);
			if(!empty($airlines_company))
			{
				$msg					= 'Airlines Flight Type Already Exist';
				$class					= 'alert alert-success alert-dismissible';	
			}
			else
			{
				$msg					= 'Airlines Flight Type Already Exist';
				$class					= 'alert alert-success alert-dismissible';	
			}
			
			
		}
		else
		{
			$msg			= 'Airlines Flight not uploaded successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		 redirect('admin/view-airlines-flight-type');
	}
	
	public function deleteFlighttype($aft_id)
	{  
		if(!empty($aft_id))
		{
			$airlines_company		= $this->basic_operation_m->delete("air_flight_type","aft_id = '$aft_id'");
			$msg					= 'Airlines Flight Type deleted successfully';
			$class					= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg			= 'Airlines Flight Type not deleted successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view-airlines-flight-type');
	}
	
	public function editFlighttype($aft_id)
	{  
		$data				 				= $this->data;
		if(!empty($aft_id))
		{
		
			$data['Flight_info']			= $this->basic_operation_m->get_table_row("air_flight_type","aft_id = '$aft_id'");
			
		}
		
		$this->load->view('admin/airlines/view_edit_airlines_flight_type',$data);
	}
	
	public function updateairlinesFlighttype($aft_id)
	{  
		$alldata 							= $this->input->post();
		if(!empty($alldata))
		{
			$status							= $this->basic_operation_m->update("air_flight_type",$alldata,"aft_id = '$aft_id'");
			if(!empty($status) && $status > 0)
			{
				$msg							= 'Airlines Flight updated successfully';
				$class							= 'alert alert-success alert-dismissible';	
			}
			else
			{
				$msg							= 'Airlines Flight Type Already Exist';
				$class							= 'alert alert-success alert-dismissible';	
			}
			
			
			
			
		}
		else
		{
			$msg			= 'Airlines Flight not updated successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view-airlines-flight-type');
	
	}
	
	###################### View All Airlines Flight End ########################
	
	###################### View Airlines Charges Start ########################
	public function airlinescharges($airline_id)
	{  
	   
		$data 							= $this->data;
		$user_id						= $this->session->userdata("userId");
		$data['airline_id']				= $airline_id;
		$data['airlines_company']		= $this->basic_operation_m->get_query_result("select * from air_airline_fix_charges left join air_aircompany on air_aircompany.a_id = air_airline_fix_charges.airline_id");
        $this->load->view('admin/airlines/view_airlines_charges',$data);
      
	}
	
	public function addairlinescharges($airline_id)
	{  
	   
		$data 							= $this->data;
		$data['airline_id']				= $airline_id;
		$user_id						= $this->session->userdata("userId");
		$data['flight_type']			= $this->basic_operation_m->get_table_result("air_flight_type","");
		$data['airlines_company']		= $this->basic_operation_m->get_table_result("air_aircompany","");
        $this->load->view('admin/airlines/view_add_airlines_charges',$data);
      
	}
	
	public function insertairlinescharges()
	{  
	   
		$alldata 							= $this->input->post();
		if(!empty($alldata))
		{
			
			$airlines_company		= $this->basic_operation_m->insert("air_airline_fix_charges",$alldata);
			$msg					= 'Airlines Charges uploaded successfully';
			$class					= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg			= 'Airlines Charges not uploaded successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		 redirect('admin/airlines-charges/'.$alldata['airline_id']);
	}
	
	public function deleteairlinescharges($airline_id,$afc_id)
	{  
		if(!empty($afc_id))
		{
			$airlines_company		= $this->basic_operation_m->delete("air_airline_fix_charges","afc_id = '$afc_id'");
			$msg					= 'Airlines Charges deleted successfully';
			$class					= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg			= 'Airlines Charges not deleted successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/airlines-charges/'.$airline_id);
	}
	
	public function editairlinescharges($afc_id)
	{  
		$data				 				= $this->data;
		if(!empty($afc_id))
		{
			$data['flight_type']			= $this->basic_operation_m->get_table_result("air_flight_type","");
			$data['afc_id']					= $afc_id;
			$data['charge_info']			= $this->basic_operation_m->get_table_row("air_airline_fix_charges","afc_id = '$afc_id'");
			
		}
		
		$this->load->view('admin/airlines/view_edit_airlines_charges',$data);
	}
	
	public function updateairlinescharges($airline_id,$afc_id)
	{  
		$alldata 							= $this->input->post();
		if(!empty($alldata))
		{
			if(!isset($alldata['commodities']))
			{
				$alldata['commodities'] = '';
			}
			$status							= $this->basic_operation_m->update("air_airline_fix_charges",$alldata,"afc_id = '$afc_id'");
			
			$msg							= 'Airlines Charges updated successfully';
			$class							= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg			= 'Airlines Charges not updated successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/airlines-charges/'.$airline_id);
	
	}
	
	###################### View All Airlines Flight End ########################	
   
}
?>
