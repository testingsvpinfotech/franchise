<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_international_zone_manager extends CI_Controller {

	var $data = array();
	function __construct()
	{
		 parent:: __construct();
		 $this->load->model('basic_operation_m');
		 $this->load->model('Zone_model');

		 if($this->session->userdata('userId') == '')
		{
			redirect('admin');
		}
	}
	
	
	###################### View All Airlines Start ########################
	public function all_courier()
	{  
	   
		$data 		= $this->data;
		$whr = array('company_type'=>'International');
		$data['courier_company']	= $this->basic_operation_m->get_all_result("courier_company",$whr);				
        $this->load->view('admin/international_zone_master/view_courier_list',$data);
      
	}	
	public function all_zone($courier_id,$zone_type,$date)
	{  
	   
		$data 		= $this->data;
		$user_id	= $this->session->userdata("userId");
		$where=array('c_courier_id'=>$courier_id,'zone_type'=>$zone_type ,'uploaded_date'=>$date);
		$data['zone']	= $this->Zone_model->get_international_zone_master_result($where);	
        $this->load->view('admin/international_zone_master/view_zone',$data);
      
	}	
	public function add_zone()
	{  	   
		$data 		= $this->data;
		$user_id	= $this->session->userdata("userId");
		$whr = array('company_type'=>'International');
		$data['courier_company']	= $this->basic_operation_m->get_all_result("courier_company",$whr);	


		$data['courier_zone_list']	= $this->Zone_model->get_international_zone_row();
		// echo "<pre>";print_r($data['courier_zone_list']);
		// exit;
		//print_r($data['courier_company']);exit;
        $this->load->view('admin/international_zone_master/view_add_zone',$data);      
	}
	
	public function insert_zone()
	{  	   
		$c_courier_id 	= $this->input->post('c_courier_id');	
		$zone_type 		= $this->input->post('zone_type');	
		$file 			= fopen($_FILES['upload_zone']['tmp_name'],"r");
		$cnt = 1;
		while(!feof($file))
		{
			$data	= fgetcsv($file);	
			if($cnt ==1)
			{
				$cnt++;
				continue;
			}
			
			if(!empty($data))
			{
			//echo "<pre>";print_r($data);	
				$date = date('Y-m-d H:i:s');
				$cur_date = date('Y-m-d H:i:s');
				$memData = array(
							'c_courier_id' => $c_courier_id,
							'country_name' => $data[0],  
							'zone_name' => $data[1],  
							'zone_type'=>$zone_type,
							'uploaded_date'=>$date,
							'uploaded_date_time'=>$cur_date,
						);

					if(!empty($memData))
					{
						$c_company		= $this->basic_operation_m->insert("zone_master",$memData);
						$msg			= 'Zone uploaded successfully';
						$class			= 'alert alert-success alert-dismissible';		
					}else{
						$msg			= 'Zone not uploaded successfully';
						$class			= 'alert alert-danger alert-dismissible';	
					}	
			}		
			$cnt++;

		}
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		redirect('admin/view-add-zone');
	}
	
	public function delete_zone($id,$courier_id)
	{  
		if(!empty($id))
		{
			$airlines_company	= $this->basic_operation_m->delete("zone_master","z_id = '$id'");
			$msg				= 'Zone deleted successfully';
			$class				= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg	= 'Zone not deleted successfully';
			$class	= 'alert alert-danger alert-dismissible';	
			
		}
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view-international-zone');
	}
	
	public function edit_zone($id)
	{  
		$data = $this->data;
		$data['allcountry']=$this->basic_operation_m->get_all_result('tbl_country');		
		if(!empty($id))
		{
			$data['courier_company']	= $this->basic_operation_m->get_all_result("courier_company","");
			$data['country_list']	 = $this->basic_operation_m->get_all_result("zone_master","");
			$data['zone']	= $this->Zone_model->get_international_zone_master_row($id);	
			
		}
		
		$this->load->view('admin/international_zone_master/view_edit_zone',$data);
	}
	
	public function update_zone($id)
	{  
		$alldata 	= $this->input->post();	

		if(!empty($alldata))
		{
			$status	= $this->basic_operation_m->update("zone_master",$alldata,"z_id = '$id'");
			
			$msg	= 'Zone updated successfully';
			$class	= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg	= 'Zone not updated successfully';
			$class	= 'alert alert-danger alert-dismissible';	
			
		}

		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view-international-zone');
	
	}	
	###################### View All Airlines Flight End ########################	
   
}
?>
