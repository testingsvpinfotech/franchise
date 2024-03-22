<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_domestic_rate_manager extends CI_Controller {

	var $data = array();
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
	public function view_domestic_rate($customer_id,$courier_id,$applicable_from)
	{ 
		$data 		= $this->data;
		$user_id	= $this->session->userdata("userId");		
		$data['domestic_rate_list']	= $this->Rate_model->get_domestic_rate_result($customer_id,$courier_id,$applicable_from);	

		//$data['edit_cust_id']	=$customer_id;
        $this->load->view('admin/domestic_rate_master/view_domestic_rate',$data);
      
	}	
	public function view_domestic_customer()
	{  
	   
		$data 		= $this->data;
		$user_id	= $this->session->userdata("userId");	
		$data['customer_list']	 = $this->basic_operation_m->get_all_result("tbl_customers","");	
        $this->load->view('admin/domestic_rate_master/view_domestic_customer',$data);
      
	}	
	public function add_domestic_rate()
	{   
		$data 		= $this->data;
		$user_id	= $this->session->userdata("userId");

		//$whr = array('customer_id'=>$customer_id);
		$data['customer_list']	 = $this->basic_operation_m->get_all_result('tbl_customers','');
		


		$whr_c = array('company_type'=>'Domestic');
		$data['courier_company'] = $this->basic_operation_m->get_all_result("courier_company",$whr_c);
		$data['mode_list']		 = $this->basic_operation_m->get_all_result("transfer_mode","");
		$data['zone_list']		 = $this->basic_operation_m->get_all_result("region_master","");
		$data['states']=$this->basic_operation_m->get_all_result('state','');	

		$data['added_customer_list']= $this->Rate_model->get_added_domestic_customer("");

		
		$this->load->view('admin/domestic_rate_master/view_add_domestic_rate',$data);      
	}
	public function getStatewiseCity()
	{
		$state_id 	= $this->input->post('state_id');

		// print_r($state_id);exit();

		$state_id = array_filter($state_id);
		$html 		= '<option>Select City</option>';
		if(!empty($state_id))
		{
			// print_r($state_id);exit();
			$whr 	=	'state_id IN ('.implode(',', $state_id).')';
			$res	=	$this->basic_operation_m->get_all_result('city',$whr);
			if(!empty($res))
			{
				foreach($res as $key => $values)
				{
					if(isset($_POST['city_id']) && !empty($_POST['city_id']) && $_POST['city_id'] == $values['id'] )
					{
						$html  .= '<option value="'.$values['id'].'" selected  >'.$values['city'].'</option>';
					}
					else					
					{
						$html  .= '<option value="'.$values['id'].'"   >'.$values['city'].'</option>';
					}
				}
			}
      	}		
      	echo json_encode($html);
	}
	
	public function getStatewiseCity_foredit()
	{
		$state_id 	= $this->input->post('state_id');

		// print_r($state_id);exit();

		
		$html 		= '<option>Select City</option>';
		if(!empty($state_id))
		{
			// print_r($state_id);exit();
			$whr 	=	"state_id = '$state_id'";
			$res	=	$this->basic_operation_m->get_all_result('city',$whr);
		
			if(!empty($res))
			{
				foreach($res as $key => $values)
				{
					if(isset($_POST['city_id']) && !empty($_POST['city_id']) && $_POST['city_id'] == $values['id'] )
					{
						$html  .= '<option value="'.$values['id'].'" selected  >'.$values['city'].'</option>';
					}
					else					
					{
						$html  .= '<option value="'.$values['id'].'"   >'.$values['city'].'</option>';
					}
				}
			}
      	}		
      	echo json_encode($html);
	}
	public function get_inserted_courier() {
		$customer_id = $this->input->post('customer_id');
		$whr = array('tbl_domestic_rate_master.customer_id' => $customer_id);
		$res = $this->Rate_model->get_added_domestic_customer($whr);
		echo json_encode($res);
	}	
	public function insert_domestic_rate()
	{  	   
		$alldata 	= $this->input->post();	
		if($alldata['fixed_perkg']>0)
		{
			//$alldata['weight_slab'] = ((round($alldata['weight_range_to']) *1000) - (round($alldata['weight_range_from']) *1000));
			$alldata['weight_slab'] = ((round((float)$alldata['weight_range_to']) *1000) - (round((float)$alldata['weight_range_from']) *1000));
		}
	
		if(!empty($alldata))
		{
			$d_data		= $this->basic_operation_m->insert_domestic_rate("tbl_domestic_rate_master",$alldata);			
			$msg			= 'Rate Inserted successfully';
			$class			= 'alert alert-success alert-dismissible';				
		}
		else
		{
			$msg			= 'Rate not Inserted';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view-add-domestic-rate');
	}
	
	
	
	public function view_edit_domestic_rate($id,$customer_id)
	{  
		$data = $this->data;
			//	echo "++++++++".$id;exit;
		if(!empty($id))
		{
			$data['customer_list']	 = $this->basic_operation_m->get_all_result("tbl_customers","");
			$whr_c = array('company_type'=>'Domestic');
		    $data['courier_company'] = $this->basic_operation_m->get_all_result("courier_company",$whr_c);
			$data['mode_list']	= $this->basic_operation_m->get_all_result("transfer_mode","");
			$data['zone_list']	 = $this->basic_operation_m->get_all_result("region_master","");
			$data['states']=$this->basic_operation_m->get_all_result('state','');	
			$data['edit_cust_id']	=$customer_id;
			$whr =array('rate_id'=>$id);
			$data['domestic_rate']=$this->basic_operation_m->get_table_row('tbl_domestic_rate_master',$whr);
		}
		
		$this->load->view('admin/domestic_rate_master/view_edit_domestic_rate',$data);
	}
	
	public function update_domestic_rate($id,$customer_id)
	{  
		$alldata 	= $this->input->post();	
		$c_courier_id =$this->input->post('c_courier_id');
		$alldata['applicable_from']=date("Y-m-d",strtotime($alldata['applicable_from']) );	
		$applicable_from=date("Y-m-d",strtotime($alldata['applicable_from']) );
		
		if($alldata['fixed_perkg']>0)
		{
			$alldata['weight_slab'] = ((round($alldata['weight_range_to']) *1000) - (round($alldata['weight_range_from']) *1000));
		}

		//echo "<pre>";print_r($alldata);exit;
		if(!empty($alldata))
		{
			$status	= $this->basic_operation_m->update("tbl_domestic_rate_master",$alldata,"rate_id = '$id'");
			
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
		
		redirect('admin/view-domestic-rate/'.$customer_id.'/'.$c_courier_id.'/'.$applicable_from);
	
	}	
	public function delete_domestic_rate($customer_id,$courier_company_id,$db_applicable_from)
	{  
		$where =array('customer_id'=>$customer_id,'c_courier_id'=>$courier_company_id,'applicable_from'=>$db_applicable_from); 
		$this->basic_operation_m->delete("tbl_domestic_rate_master",$where);
		$msg  = 'Mode deleted successfully';
		$class = 'alert alert-success alert-dismissible';	
			
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view-add-domestic-rate');
	}
	//==========================state wise rate
	public function view_domestic_state_rate($z_id)
	{  
	   
		$data 		= $this->data;
		$user_id	= $this->session->userdata("userId");		
		$data['states']=$this->basic_operation_m->get_all_result('state','');
		$data['domestic_rate_list']	= $this->Rate_model->get_domestic_rate_list($z_id);	
		$data['domestic_state_rate_list'] = $this->Rate_model->get_domestic_state_rate_list($z_id);	
		$data['domestic_city_rate_list'] = $this->Rate_model->get_domestic_city_rate_list($z_id);	
			
		$data['z_id'] = $z_id;
        $this->load->view('admin/domestic_rate_master/view_domestic_state_rate',$data);
      
	}
	public function insert_domestic_state_rate()
	{  	   
		$alldata 	= $this->input->post();
		if(!empty($alldata))
		{
			$d_data		= $this->basic_operation_m->insert("tbl_domestic_state_rate",$alldata);			
			$msg		= 'Rate Inserted successfully';
			$class		= 'alert alert-success alert-dismissible';				
		}
		else
		{
			$msg			= 'Rate not Inserted';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view-domestic-rate');
	}	
	public function insert_domestic_city_rate()
	{  	   
		$alldata 	= $this->input->post();	
		if(!empty($alldata))
		{
			$d_data		= $this->basic_operation_m->insert("tbl_domestic_city_rate",$alldata);			
			$msg			= 'Rate Inserted successfully';
			$class			= 'alert alert-success alert-dismissible';				
		}
		else
		{
			$msg			= 'Rate not Inserted';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view-domestic-rate');
	}	
	public function getDomesticStateDetails()
	{ 
		$edit_id 	= $this->input->post('edit_id');	
		$data['d_state_rate_row'] = $this->basic_operation_m->get_all_result('tbl_domestic_state_rate',$edit_id);	

	}
	public function insert_transfer_rate()
	{  
		$sel_courier_id = $this->input->post('sel_courier_id');
		$db_applicable_from = $this->input->post('db_applicable_from');
		
		$customer_id = $this->input->post('transfer_customer_id');
	    $to_customer_id = $this->input->post('to_customer_id');
	    $user_id	= $this->session->userdata("userId");

	    $transfer_date = date("Y-m-d",strtotime($this->input->post('transfer_date') ) );
		//$whr = array('c_courier_id'=>$courier_id,'customer_id'=>$customer_id);
		//$whr = 'rate_master_id IN (1,2)';
		
	    if($sel_courier_id[0]!="ALL"){

			$courier_id ="'".implode("','", $sel_courier_id)."'";
			//$whr = array('customer_id'=>$customer_id,'c_courier_id IN'=>$courier_id);
			$whr =" customer_id='$customer_id' AND c_courier_id IN ($courier_id) AND DATE(`applicable_from`)='$db_applicable_from' ";
		}else{
			//$whr = array('customer_id'=>$customer_id);
			$whr =" customer_id='$customer_id' ";
		}
		$rate_master = $this->basic_operation_m->get_all_result("tbl_domestic_rate_master",$whr);
		// echo $this->db->last_query();
		// echo "<pre>";print_r($rate_master);exit;

		$this->Rate_model->insert_domestic_rate("tbl_domestic_rate_master",$rate_master,$to_customer_id,$transfer_date);
				
		$alldata = array(
			'customer_id'=>$customer_id,
			'to_customer_id'=>implode(",",$to_customer_id),
			'transfer_date'=>$transfer_date,
			'transfer_by'=>$user_id,
		);
		//echo "<pre>";print_r($alldata);
	    $this->basic_operation_m->insert("tbl_domestic_transfer_rate_history",$alldata);

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
		redirect('admin/view-add-domestic-rate');

	}
	/*public function show_my_weight_slab()
	{  
		//http://localhost/omcourier/Admin_domestic_rate_manager/show_my_weight_slab 
		
		$alldata 	= $this->basic_operation_m->get_all_result('tbl_domestic_rate_master','');			
		//echo "<pre>";print_r($alldata);		
		foreach($alldata AS $w)
		{
			if($w['fixed_perkg'] >0){
				echo "<br>----".$w['rate_id']."==".$w['fixed_perkg']."==".$weight_range_to = round((float)$w['weight_range_to']);
				echo "+++".$weight_range_from = round((float)$w['weight_range_from']);
				echo "***".$weight_slab = $w['weight_slab'];
				
				echo "#####".$calculated_weight_slab = ($weight_range_to - $weight_range_from)* 1000;
				
				$data=array('weight_slab'=>$calculated_weight_slab);
				$whr=array('rate_id'=>$w['rate_id']);
				$quer = $this->basic_operation_m->update('tbl_domestic_rate_master',$data,$whr);
				echo $this->db->last_query();
				//exit;
			}
		}
		//if($alldata['fixed_perkg']>0)
		//{
		//	$alldata['weight_slab'] = ((round((float)$alldata['weight_range_to']) *1000) - (round((float)$alldata['weight_range_from']) *1000));
		//}
	} */
	
   
}
?>
