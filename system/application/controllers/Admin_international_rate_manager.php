<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_international_rate_manager extends CI_Controller {

	var $data 			= array();
	function __construct()
	{
		 parent:: __construct();
		 $this->load->model('basic_operation_m');	
		  $this->load->model('Rate_model');		 
		 if($this->session->userdata('userId') == '')
		{
			redirect('admin');
		}
	}
	
	
	###################### View All Airlines Start ########################
	public function view_rate_upload($customer_id,$courier_id)
	{  	   
		$data 					 = $this->data;
		$user_id				 = $this->session->userdata("userId");			
		
		$data['zone_list']		 = $this->basic_operation_m->get_all_result("zone_master","");
		$data['country_list']	 = $this->basic_operation_m->get_all_result("tbl_country","");
		
		$whr_c = array('customer_id'=>$customer_id);
		$data['customer_list']	 = $this->basic_operation_m->get_table_row("tbl_customers",$whr_c);

		$whr_cc = array('c_id'=>$courier_id);
		$data['courier_company'] = $this->basic_operation_m->get_table_row("courier_company",$whr_cc);
		
        $this->load->view('admin/international_rate_master/view_rate_upload',$data);      
	}
	
	public function upload_rate()
	{  	   
		 $file 			= fopen($_FILES['upload_rate']['tmp_name'],"r");
		// $alldata 	= $this->input->post();

		 $courier_company_name = $this->input->post("courier_company_name");
		// //$zone_name = $this->input->post("zone_name");
		 //$country_name = $this->input->post("country_name");
		 $from_date =date("Y-m-d",strtotime($this->input->post("from_date")));
		 $customer_name = $this->input->post("customer_name");
		 $doc_type = $this->input->post("doc_type");	
		 $type_export_import = $this->input->post("type_export_import");	
		
		$file 			= fopen($_FILES['upload_rate']['tmp_name'],"r");
		
		$heading_array = array();
		$cnt = 0;
		$weight 		= 0;
		while(! feof($file))
		{
			$data	= fgetcsv($file);
			if($cnt == 0)
			{
				$heading_array = $data;
			}
			else
			{
				if(!empty($data))
				{				
				for($i= 0;$i<count($data);$i++)
				{						
					
					if($i == 0)
					{
						$fixed_perkg= $data[0];
					}
					else if($i == 1)
					{
						$weight_from	= $data[1];
					}
					else if($i == 2)
					{
						$weight_to		= $data[2];
					}
					else
					{
					    if(!empty($data[$i]) && !empty($weight_to))
						{
							$memData = array(
										'courier_company_id' => $courier_company_name,  
										'from_date'=>$from_date,
										'customer_id' => $customer_name,
										'doc_type' => $doc_type, 
										'type_export_import'=>$type_export_import, 
										'zone_id' => $heading_array[$i],                               
										'weight_from' =>$weight_from,
										'weight_to' =>$weight_to,                         
										'rate' => $data[$i],
										'fixed_perkg' => $fixed_perkg,
									);	
						
								$this->basic_operation_m->insert('tbl_international_rate_master',$memData);
						}
					}
					 
				}
			}
				
			}
			
			$cnt++;
		}

		fclose($file);	
		redirect('admin/view-international-customer');   		
	}
	// public function upload_rate()
	// {  	   
	// 	 $file 			= fopen($_FILES['upload_rate']['tmp_name'],"r");
	// 	// $alldata 	= $this->input->post();

	// 	 $courier_company_name = $this->input->post("courier_company_name");
	// 	// //$zone_name = $this->input->post("zone_name");
	// 	 //$country_name = $this->input->post("country_name");
	// 	 $from_date =date("Y-m-d",strtotime($this->input->post("from_date")));
	// 	 $customer_name = $this->input->post("customer_name");
	// 	 $doc_type = $this->input->post("doc_type");		
		
	// 	$file 			= fopen($_FILES['upload_rate']['tmp_name'],"r");
		
	// 	$heading_array = array();
	// 	$cnt = 0;
	// 	$weight 		= 0;
	// 	while(! feof($file))
	// 	{
	// 		$data	= fgetcsv($file);
	// 		if($cnt == 0)
	// 		{
	// 			$heading_array = $data;
	// 		}
	// 		else
	// 		{
	// 			if(!empty($data))
	// 			{
				
	// 			for($i= 0;$i<count($data);$i++)
	// 			{
	// 				if($i == 0)
	// 				{
	// 					$weight		= $data[0];
	// 				}
	// 				else
	// 				{
	// 					if(strpos($weight, "-"))
	// 					{
	// 						$range = explode('-',$weight);
	// 						$j 		= $range[0];
	// 						for($j;$j<=$range[1];$j++)
	// 						{
	// 							$memData = array(
	// 									'courier_company_id' => $courier_company_name,  
	// 									'from_date'=>$from_date,
	// 									'customer_id' => $customer_name,
	// 									'doc_type' => $doc_type,  
	// 									'zone_id' => $heading_array[$i],                               
	// 									'weight' =>$j,
	// 									'weight_range' =>$weight,                         
	// 									'rate' => $data[$i],
	// 								);	
	// 							$this->basic_operation_m->insert('tbl_international_rate_master',$memData);	
	// 						}
	// 					}
	// 					else
	// 					{
	// 						$memData = array(
	// 									'courier_company_id' => $courier_company_name,  
	// 									'from_date'=>$from_date,
	// 									'customer_id' => $customer_name,
	// 									'doc_type' => $doc_type,  
	// 									'zone_id' => $heading_array[$i],                               
	// 									'weight' =>$weight,                     
	// 									'rate' => $data[$i],
	// 								);	
	// 						 $this->basic_operation_m->insert('tbl_international_rate_master',$memData);		
	// 					}
	// 				}
					 
	// 			}
	// 		}
				
	// 		}
			
	// 		$cnt++;
	// 	}

	// 	fclose($file);	
	// 	redirect('admin/view-rate-upload');   		
	// }
	
	
	public function view_uploded_rate_data($customer_id,$courier_id,$from_date)
	{  
	    $sel_from_date =date("Y-m-d",strtotime($from_date));
		$whr=array('customer_id'=>$customer_id,'courier_company_id'=>$courier_id,'from_date'=>$sel_from_date);

		$data['csv_file_header']	= $this->basic_operation_m->get_rate_report_header($whr);
		$data['csv_file_weight']	= $this->basic_operation_m->get_rate_report_weight($whr);
		$data['csv_file_body']	= $this->basic_operation_m->get_rate_report_body($whr);
       
		$whr_c = array('customer_id'=>$customer_id);
		$data['customer_list']	 = $this->basic_operation_m->get_table_row("tbl_customers",$whr_c);

		$whr_cc = array('c_id'=>$courier_id);
		$data['courier_company'] = $this->basic_operation_m->get_table_row("courier_company",$whr_cc);

		$this->load->view('admin/international_rate_master/view_uploded_rate_data',$data);      
	}
	public function view_international_customer()
	{  
	   
		$data 		= $this->data;
		$user_id	= $this->session->userdata("userId");	
		$data['customer_list']	 = $this->basic_operation_m->get_all_result("tbl_customers","");	
		$data['added_customer_list']= $this->Rate_model->get_added_international_customer();
        $this->load->view('admin/international_rate_master/view_international_customer',$data);
	}
	public function show_international_courier()
	{  
		$data['edit_customer_id'] = $this->input->post('customer_name');
		$whr_c = array('customer_id'=>$data['edit_customer_id']); 
		$data['cust_data']	 = $this->basic_operation_m->get_table_row("tbl_customers",$whr_c);	

		$data['customer_list']	 = $this->basic_operation_m->get_all_result("tbl_customers","");	
	//	$whr =array('company_type'=>'International');
	//	$data['courier_list'] =  $this->basic_operation_m->get_international_rate_result("courier_company",$whr);
	    $data['courierwise_rate_data'] =  $this->Rate_model->get_international_rate_result($data['edit_customer_id']);
		
		//$courierwise_rate_data = $this->Rate_model->get_international_table_row('tbl_international_rate_master',$whr);
		$data['added_customer_list']= $this->Rate_model->get_added_international_customer();

		$this->load->view('admin/international_rate_master/view_international_customer',$data);

	}	
	public function insert_transfer_rate()
	{  
		$courier_id = $this->input->post('courier_id');
		$customer_id = $this->input->post('transfer_customer_id');
	    $to_customer_id = $this->input->post('to_customer_id');
	    $user_id	= $this->session->userdata("userId");

	    $transfer_date = date("Y-m-d",strtotime($this->input->post('transfer_date') ) );
		$whr = array('courier_company_id'=>$courier_id,'customer_id'=>$customer_id);
		//$whr = 'rate_master_id IN (1,2)';
		$rate_master = $this->basic_operation_m->get_all_result("tbl_international_rate_master",$whr);

		$this->Rate_model->insert_rate("tbl_international_rate_master",$rate_master,$to_customer_id,$transfer_date);
				
		$alldata = array('courier_id'=>$courier_id,
			'customer_id'=>$customer_id,
			'to_customer_id'=>implode(",",$to_customer_id),
			'transfer_date'=>$transfer_date,
			'transfer_by'=>$user_id,

		);
		//echo "<pre>";print_r($alldata);
	    $this->basic_operation_m->insert("tbl_international_transfer_rate_history",$alldata);

		$data['customer_list']	 = $this->basic_operation_m->get_all_result("tbl_customers","");
		$data['edit_customer_id'] = $customer_id;

		if(!empty($alldata))
		{		
			$msg			= 'Rate Transfer successfully';
			$class			= 'alert alert-success alert-dismissible';		
		}else{
			$msg			= 'Rate not Transfer successfully';
			$class			= 'alert alert-danger alert-dismissible';	
		}	
		$this->load->view('admin/international_rate_master/view_international_customer',$data);

	}
		public function delete_international_rate($customer_id,$courier_company_id)
	{  
		
		$where =array('customer_id'=>$customer_id,'courier_company_id'=>$courier_company_id); 
		$airlines_company		= $this->basic_operation_m->delete("tbl_international_rate_master",$where);
		$msg					= 'Rate deleted successfully';
		$class					= 'alert alert-success alert-dismissible';				
			
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		$data['edit_customer_id'] = $customer_id;
		redirect('admin/view-international-customer');
		//$this->load->view('admin/international_rate_master/view_international_customer',$data);
	}
	
}
?>
