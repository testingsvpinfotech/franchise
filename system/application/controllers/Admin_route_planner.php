<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_route_planner extends CI_Controller{
	
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
	
	public function all_route()
	{
        $data					= array();
		$data['allroutedata']	= $this->basic_operation_m->get_query_result_array('select * from route_master');  
        $this->load->view('admin/route_planner/view_route',$data);
	}
	
	public function add_route()
	{
		$data['message']="";	


		$data['route_detail']=$this->basic_operation_m->get_query_result('select * from route_master_details left join city on city.id=route_master_details.city ','');

		$data['city_array'] =array();
		foreach ($data['route_detail'] as $key => $value) 
		{				
			$data['city_array'][$value->id] = $value->id;
		}	

		if (isset($_POST['submit'])) 
		{

			$route_name = trim($this->input->post('route_name') );
			$whr_r= array('route_name'=>$route_name);

			$reg_details=$this->basic_operation_m->get_table_row('route_master',$whr_r);


			if(isset($reg_details))
			{
				$msg			= 'Route already exist';
				$class			= 'alert alert-danger alert-dismissible';

			}
			else
			{
					$state_id = $this->input->post('state_id');
					$city_id = $this->input->post('city_id');			
					
					$r= array('route_name' => $this->input->post('route_name'));

					$inserted_id=$this->basic_operation_m->insert('route_master',$r);
					for ($i=0; $i <count($city_id) ; $i++) { 

						//if($i!=0)
						{
							$fin_city = explode("_", $city_id[$i]);
							
							if($city_id[$i] <> 'multiselect-all')
							{
								 $r_detail= array(
									  'routeid' => $inserted_id,   
									  'state' => $fin_city[0],   
									  'city' => $fin_city[1],                   
								  );
								  $result=$this->basic_operation_m->insert('route_master_details',$r_detail);
							}

						}
					}
					
					if(!empty($r))
					{
						$msg					= 'Route added successfully';
						$class					= 'alert alert-success alert-dismissible';	
					}
					else
					{
						$msg			= 'Route not added successfully';
						$class			= 'alert alert-danger alert-dismissible';					
					}
				}
			$this->session->set_flashdata('notify',$msg);
			$this->session->set_flashdata('class',$class);
     //        redirect('admin/add-route'); 
		}
		 $data['city_list']= $this->basic_operation_m->get_all_result('city');
		 $data['state_list']= $this->basic_operation_m->get_all_result('state');
	    $this->load->view('admin/route_planner/add_route',$data);
	}
	
	
	public function update_route($id)
	{
		$data['message']="";      
		if($id!="")
		{
			
			$whr =array('route_id'=>$id);
			$data['rgn']=$this->basic_operation_m->get_table_row('route_master',$whr);	
			$whr1 =array('routeid'=>$id);
			$data['rgn_detail']=$this->basic_operation_m->get_query_result("select * from route_master_details left join city on city.id=route_master_details.city where routeid='$id'",$whr1);


			$data['state_array'] =array();
			$data['city_array'] =array();
			foreach ($data['rgn_detail'] as $key => $value) {

				$data['state_array'][$value->state] = $value->state;
				$data['city_array'][$value->state.'_'.$value->id] = $value->city;

			}
		}
		if (isset($_POST['submit'])) {
	       
	        $whr1 =array('routeid'=>$id);
	        $result=$this->basic_operation_m->delete('route_master_details',$whr1);
	        

			$state_id = $this->input->post('state_id');
			$city_id = $this->input->post('city_id');
			for ($i=0; $i <count($city_id) ; $i++) { 

				$fin_city = explode("_", $city_id[$i]);

				if(in_array($fin_city[0],$state_id))
				{
					$r_detail= array(  'routeid' => $id,   'state' => $fin_city[0],   'city' => $fin_city[1]);
				}

				$result=$this->basic_operation_m->insert('route_master_details',$r_detail);
			}

			 $whr =array('route_id'=>$id);
			 $r= array(
	             'route_name' => $this->input->post('route_name'),   		                          
             );
			$result=$this->basic_operation_m->update('route_master',$r, $whr);
			if(!empty($r))
			{				
				$msg	= 'Route updated successfully';
				$class	= 'alert alert-success alert-dismissible';	
			}
			else
			{
				$msg	= 'Route not updated successfully';
				$class	= 'alert alert-danger alert-dismissible';	
				
			}
			$this->session->set_flashdata('notify',$msg);
			$this->session->set_flashdata('class',$class);
            redirect('admin/all-route');
		}


		$data['city_list']= $this->basic_operation_m->get_all_result('city');
		$data['state_list']= $this->basic_operation_m->get_all_result('state');
	    $this->load->view('admin/route_planner/edit_route',$data);
	}
	
	public function delete_route($id)
	{
		$data['message']="";       
		if($id!="")
		{
		    $whr =array('route_id'=>$id);
			$this->basic_operation_m->delete('route_master',$whr);	

			$whr1=array('routeid'=>$id);
			$this->basic_operation_m->delete('route_master_details',$whr1);			
            redirect('admin/all-route');
		}		
	  
	}

	public function getStatewiseCity()
	{
		 $state_id = $this->input->post('state_id');
		
		 $html = '';

		 
		if($state_id!="")
		{
			
			$check_state_id   =  implode('","', $state_id);
			$whr ='state_id IN ("'.$check_state_id.'")';
			
			
		    $res=$this->basic_operation_m->get_query_result("select city.id,city.state_id,city.city from city where $whr");
		
			if(!empty($res))
			{
				foreach($res as $key => $values)
				{   
					$html  .= '<option value="'.$values->state_id.'_'.$values->id.'"   >'.$values->city.'</option>';
					 //option += '<option value="' +d[i].id + '">' + d[i].city + '</option>';
				}
			}
			
      	}		
      	 echo json_encode($html);
	  
	}

	public function getStatewiseCityedit()
	{
		 $state_id = $this->input->post('state_id');
		 $already_user_city = $this->input->post('already_user_city');
		
		 $html = '';

		 
		if($state_id!="")
		{
			if(strpos($state_id, ',')) 
			{
				
				 $check_state_id   =  str_replace(",",'","', $state_id);
			}
			else
			{
				 $check_state_id   = $state_id;
			}
			
			if(!empty($already_user_city))
			{
				 $already_user_city   = str_replace(",",'","', $already_user_city);
				 $whr ='state_id IN ("'.$check_state_id.'") and id not IN ("'.$already_user_city.'") ';
			}
			else
			{
				 $whr ='state_id IN ("'.$check_state_id.'")';
			}
		    		    
			 $res=$this->basic_operation_m->get_all_result('city',$whr);
			//echo $this->db->last_query();exit;
			if(!empty($res))
			{
				foreach($res as $key => $values)
				{
					$html  .= '<option value="'.$values['state_id'].'_'.$values['id'].'"   >'.$values['city'].'</option>';
					 //option += '<option value="' +d[i].id + '">' + d[i].city + '</option>';
				}
			}
			
      	}		
      	 echo json_encode($html);
	  
	}

	public function petty_report($id)
	{  
		$data['show_data']  = $this->db->query("SELECT * FROM tbl_domestic_booking  WHERE petty_cash_status= '$id'")->result_array();
		$this->load->view('admin/route_planner/report_branch',$data);
      
	}

	public function todaycoll()
	{  
        
		//$data['city']  = $this->db->query('select city from city')->result_array();
        $this->load->view('admin/route_planner/collection',$data);
      
	}

	public function pettycash()
	{  
        $username 			= $this->session->userdata("userName");
		$res				= $this->basic_operation_m->get_table_row('tbl_users',array('username' => $username));
		$data['branch_id'] 	= $res->branch_id;
		$data['all_branch']  	= $this->basic_operation_m->get_table_result('tbl_branch','');
		
		$where 					= '1';
		$all_data 				= $this->input->post();
		if(!empty($all_data))
		{
			if(!empty($all_data['petty_code']))
			{
				$where 		.= "and pc_serial = '".$all_data['petty_code']."'";
			}
			
			if(!empty($all_data['branch_id']))
			{
				$where 		.= "and petty_cash.branch_id = '".$all_data['branch_id']."'";
			}
			
			if(!empty($all_data['from_date']))
			{
				$where 		.= "and petty_date >= '".$all_data['from_date']." 00:00:00'";
			}
			
			if(!empty($all_data['to_date']))
			{
				$where 		.= "and petty_date <= '".$all_data['to_date']." 23:59:59'";
			}
			
		}
		$user_id = $this->session->userdata("userId");
		if($user_id == 1)
		{
			$data['all_petty_cash']	 = $this->basic_operation_m->get_query_result_array("select * from petty_cash left join tbl_branch on tbl_branch.branch_id = petty_cash.branch_id where $where");  
		}
		else{
			
			$data['all_petty_cash']	 = $this->basic_operation_m->get_query_result_array("select * from petty_cash left join tbl_branch on tbl_branch.branch_id = petty_cash.branch_id where petty_cash.branch_id = '$res->branch_id' $where");  
		}
        $this->load->view('admin/route_planner/pettycash',$data);
      
	}

	public function generte_pettycash($branch_id)
	{
		 $username 			= $this->session->userdata("userName");
		$res				= $this->basic_operation_m->get_table_row('tbl_users',array('username' => $username));
		$data['branch_id'] 	= $res->branch_id;
		$branch_info		= $this->basic_operation_m->get_table_row('tbl_branch',array('branch_id' => $res->branch_id));
		
		$total_cash  		=	$this->basic_operation_m->get_query_row("SELECT *,sum(if(payment_method = '1',grand_total,0))  AS UPI ,sum(if(payment_method = '2',grand_total,0))  AS Cheque
,sum(if(payment_method = '3',grand_total,0))  AS Cash ,sum(if(payment_method = '4',grand_total,0))  AS Neft ,sum(if(payment_method = '5',grand_total,0))  AS Pending 
,sum(grand_total)  AS Total,GROUP_CONCAT(booking_id) AS booking_ids FROM tbl_domestic_booking WHERE dispatch_details = 'Cash' AND branch_id = '$branch_id' and petty_cash_status ='0'  AND DATE(booking_date) <= CURDATE()");
		if(!empty($total_cash) && $total_cash->booking_id != '')
		{
			$petty_cash  		=	$this->basic_operation_m->get_query_row("SELECT count(pc_id) as total FROM petty_cash WHERE branch_id = '$branch_id'");
			if($petty_cash->total == 0)
			{
				$petty_id  = 'PTC/'.$branch_info->branch_code.'/1';
			}
			else
			{
				$petty_id  = 'PTC/'.$branch_info->branch_code.'/'.++$petty_cash->total;
			}
			
			$booking_ids 	= str_replace(",","','",$total_cash->booking_ids);
			
			$petty_array 	= array('pc_serial'=>$petty_id,'branch_id'=>$branch_id,'cash_amount'=>$total_cash->Cash,'pending_amount'=>$total_cash->Pending,'upi_amount'=>$total_cash->UPI,'cheque_amount'=>$total_cash->Cheque,'neft_amount'=>$total_cash->Neft,'total_amount'=>$total_cash->Total,'petty_date'=>date('Y-m-d H:i:s'));
			$this->db->insert('petty_cash', $petty_array);
			$pc_id	 = $this->db->insert_id();
			
			$this->db->query("UPDATE tbl_domestic_booking SET petty_cash_status = '$pc_id' WHERE booking_id IN ('$booking_ids')");
			
		}
		
		redirect('admin/petty-cash');

	}
	
	public function received_domestic_payment()
	{
		$all_data 			= $this->input->post();
		if(!empty($all_data))
		{
			$booking_id			= $all_data['booking_id'];
			$payment_method		= $all_data['payment_method'];
			$this->db->query("UPDATE tbl_domestic_booking SET payment_method = '$payment_method' WHERE booking_id = '$booking_id'");
			redirect('admin/due-report');
		}
			
	}

	public function recived_petty_cash()
	{
		$all_data 			= $this->input->post();
		if(!empty($all_data))
		{
			$pc_serial			= $all_data['pc_id'];
			$current_date 	= date('Y-m-d H:i:s');
			$this->db->query("UPDATE petty_cash SET approved_date = '$current_date',pc_status='1' WHERE pc_serial = '$pc_serial'");
			redirect('admin/petty-cash');
		}
			
	}
	
	public function pay_amount($booking_id)
	{
		$current_date 	= date('Y-m-d H:i:s');
		$this->db->query("UPDATE tbl_domestic_booking SET approved_date = '$current_date',pc_status='1' WHERE pc_id = '$booking_id'");
		redirect('admin/due-cash');
	}

	public function duereport()
	{  
        $username 				= $this->session->userdata("userName");
		$res					= $this->basic_operation_m->get_table_row('tbl_users',array('username' => $username));
		$data['branch_id'] 		= $res->branch_id;
		
		$where 					= '';
		$all_data 				= $this->input->post();
		if(!empty($all_data))
		{
			if(!empty($all_data['pod_no']))
			{
				$where 		.= "and pod_no = '".$all_data['pod_no']."'";
			}
			
			if(!empty($all_data['sender_contactno']))
			{
				$where 		.= "and sender_contactno = '".$all_data['sender_contactno']."'";
			}
			
			if(!empty($all_data['customer_id']))
			{
				$where 		.= "and customer_id = '".$all_data['customer_id']."'";
			}

			if(!empty($all_data['from_date']))
			{
				
				$where 		.= "and booking_date >= '".$all_data['from_date']."'";
			}
			
			if(!empty($all_data['to_date']))
			{
				$where 		.= "and booking_date <= '".$all_data['to_date']."'";
			}
			
		}
		
		$user_id = $this->session->userdata("userId");
		if($user_id == 1)
		{
			$data['all_due_report']	 = $this->basic_operation_m->get_query_result_array("select * from tbl_domestic_booking left join tbl_branch on tbl_branch.branch_id = tbl_domestic_booking.branch_id where payment_method = '5' $where");  
			$data['customer']   = $this->basic_operation_m->get_table_result('tbl_customers',array());
		}
		else
		{
			$data['all_due_report']	 = $this->basic_operation_m->get_query_result_array("select * from tbl_domestic_booking left join tbl_branch on tbl_branch.branch_id = tbl_domestic_booking.branch_id where tbl_domestic_booking.branch_id = '$res->branch_id' AND payment_method = '5' $where");  
			$data['customer']   = $this->basic_operation_m->get_table_result('tbl_customers',array('branch_id' => $data['branch_id']));
		}
		$data['branch']  	= $this->basic_operation_m->get_table_row('tbl_branch','');
        $this->load->view('admin/route_planner/duereport',$data);
      
	}

	public function branchcollection()
	{  
        
		//$data['city']  = $this->db->query('select city from city')->result_array();
        $this->load->view('admin/route_planner/branchcollection',$data);
      
	}
	
}

?>