<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_customer extends CI_Controller {

	function __construct()
	{
		parent:: __construct();
		$this->load->model('basic_operation_m');
		$this->load->model('Customer_model');
		if($this->session->userdata('userId') == '')
		{
			redirect('admin');
		}
	}

	public function index()
	{
			 $data= array();
			if($this->session->userdata("userType")!="1") 
			{
         
			    $userId = $this->session->userdata("userId");
				$username = $this->session->userdata("userName");
                 $whr = array('username' => $username);
                 $res = $this->basic_operation_m->getAll('tbl_users', $whr);
                 $branch_id = $res->row()->branch_id;				
			    $where ="tbl_customers.branch_id='$branch_id' ";
				$data['allcustomer']= $this->Customer_model->get_customer_details($where);
			 
			} else {
					$where ="";
					$data['allcustomer']= $this->Customer_model->get_customer_details($where);
			}
					
			$this->load->view('admin/customer_management/view_customer',$data);
	}
	
	
	public function add_customer()
	{
		$query ="select max(customer_id) AS id from  tbl_customers"; 
		$result1= $this->basic_operation_m->get_query_row($query);
		$data['allcustomer']= $this->Customer_model->get_customer_details('');
		$id= $result1->id+1;
		if(strlen($id)==2)
		{
			$customer_id='API00'.$id;
		}else if(strlen($id)==3)
		{
			$customer_id='API0'.$id;
		}else if(strlen($id)==1)
		{
			$customer_id='API000'.$id;
		}else if(strlen($id)==4)
		{
			$customer_id='API'.$id;
		}

		$data['message']="";

         $data['all_staff']= $this->basic_operation_m->get_all_result('tbl_users');
         $data['all_sales_person']= $this->basic_operation_m->get_all_result('tbl_users',array('user_type'=>6));
		 $data['cities']= $this->basic_operation_m->get_all_result('city','');
         $data['states']=$this->basic_operation_m->get_all_result('state','');		

		if(isset($_POST['submit']))
		{
			//print_r($_POST); DIE;

			$date=date('Y-m-d');
            $user_type = 'general';
		    $user_id = $this->session->userdata("userId");
            if($this->session->userdata("userType")=="5") {
			    $user_type = 'b2b';
			} 

			$creditDays = $this->input->post('credit_days');
			if(!$this->input->post('credit_days')){
				$creditDays = '';
			}
			
			$username = $this->session->userdata("userName");
			 $whr = array('username' => $username);
			 $res = $this->basic_operation_m->getAll('tbl_users', $whr);
			 $branch_id = $res->row()->branch_id;	

			$data=array('customer_id'=>'',
				'cid'=>$this->input->post('cid'),
				'customer_name'=>$this->input->post('customer_name'),
				'contact_person'=>$this->input->post('contact_person'),
				'phone'=>$this->input->post('phone'),
				'email'=>$this->input->post('email'),
				'password'=>$this->input->post('password'),
				'address'=>$this->input->post('address'),							
				'state'=>$this->input->post('state_id'),
				'city'=>$this->input->post('city'),
				'company_id'=>$this->input->post('company_id'),
				'pincode'=>$this->input->post('pincode'),
				'gstno'=>$this->input->post('gstno'),
				'gst_charges'=>$this->input->post('gst_charges'),
				'policy_no'=>$this->input->post('policy_no'),
				'register_date'=>$date,
				'api_access'=>$this->input->post('api_access'),
				'mis_emailids'=>$this->input->post('mis_emailids'),
				'sac_code'=>$this->input->post('sac_code'),
				'mis_formate'=>$this->input->post('mis_formate'),
				'customer_type' => $user_type,
				'user_id' => $this->input->post('user_id'),
				'credit_days'=>$creditDays,
				'parent_cust_id' => $this->input->post('parent_cust'),
				'sales_person_id' => $this->input->post('sales_person_id'),
				'branch_id'=>$branch_id
				);


			$result=$this->basic_operation_m->insert('tbl_customers',$data);
			
			if(!empty($data))
			{						
				$msg			= 'Customer added successfully';
				$class			= 'alert alert-success alert-dismissible';		
			}else{
				$msg			= 'Customer not added successfully';
				$class			= 'alert alert-danger alert-dismissible';	
			}	
			$this->session->set_flashdata('notify',$msg);
			$this->session->set_flashdata('class',$class);

			redirect('admin/list-customer');
		}	
		$data['cid']=$customer_id;

		$data['company_list'] = $this->basic_operation_m->get_query_result_array('SELECT * FROM tbl_company WHERE 1 ORDER BY company_name ASC');
		$this->load->view('admin/customer_management/add_customer', $data);
	}

	public function update_customer($id)
	{
			$data['message']="";	
			if($id!="")
			{
				$whr =array('customer_id'=>$id);
				$data['customer']=$this->basic_operation_m->get_table_row('tbl_customers',$whr);				
			}


			 $data['all_staff']= $this->basic_operation_m->get_all_result('tbl_users','');
	         $data['cities']= $this->basic_operation_m->get_all_result('city','');
         	 $data['states']= $this->basic_operation_m->get_all_result('state','');	
			$data['allcustomer']= $this->Customer_model->get_customer_details('');
			$data['all_sales_person']= $this->basic_operation_m->get_all_result('tbl_users',array('user_type'=>6));
         
				if (isset($_POST['submit'])) 
				{
						$last = $this->uri->total_segments();
						$id= $this->uri->segment($last);
						$whr =array('customer_id'=>$id);
						$user_type = 'general';
						$user_id = $this->session->userdata("userId");
			            if($this->session->userdata("userType")=="5") {
						    $user_type = 'b2b';
						}

						$creditDays = $this->input->post('credit_days');
						if(!$this->input->post('credit_days')){
							$creditDays = '';
						}

						$r= array(
							'customer_name'=>$this->input->post('customer_name'),
							'contact_person'=>$this->input->post('contact_person'),
							
							'phone'=>$this->input->post('phone'),
							'email'=>$this->input->post('email'),
							'password'=>$this->input->post('password'),
							'address'=>$this->input->post('address'),							
							'state'=>$this->input->post('state_id'),
							'city'=>$this->input->post('city'),							 
							'company_id'=>$this->input->post('company_id'),							
							'pincode'=>$this->input->post('pincode'),
							'gstno'=>$this->input->post('gstno'),
							'gst_charges'=>$this->input->post('gst_charges'),
							'api_access'=>$this->input->post('api_access'),
							'mis_emailids'=>$this->input->post('mis_emailids'),
							'sac_code'=>$this->input->post('sac_code'),
							'mis_formate'=>$this->input->post('mis_formate'),
							'customer_type' => $user_type,
							'user_id' => $this->input->post('user_id'),
							'parent_cust_id' => $this->input->post('parent_cust'),
							'sales_person_id' => $this->input->post('sales_person_id'),
							'credit_days'=>$creditDays
							);
						$result=$this->basic_operation_m->update('tbl_customers',$r, $whr);
						if ($this->db->affected_rows() > 0) {
							$data['message']="data Updated Sucessfully";
						}else{
							$data['message']="Error in Query";
						}
						if(!empty($data))
						{						
							$msg			= 'Customer updated successfully';
							$class			= 'alert alert-success alert-dismissible';		
						}else{
							$msg			= 'Customer not updated successfully';
							$class			= 'alert alert-danger alert-dismissible';	
						}	
						$this->session->set_flashdata('notify',$msg);
						$this->session->set_flashdata('class',$class);

						redirect('admin/list-customer');
				}
		
		$data['company_list'] = $this->basic_operation_m->get_query_result_array('SELECT * FROM tbl_company WHERE 1 ORDER BY company_name ASC');		
		$this->load->view('admin/customer_management/edit_customer',$data);
	}

// 	public function delete_customer()
// 	{
// 		$data['message']="";
// 		$last = $this->uri->total_segments();
// 		$id	= $this->uri->segment($last);
// 		if($id!="")
// 		{
// 			$whr =array('customer_id'=>$id);
// 			$res=$this->basic_operation_m->delete('tbl_customers',$whr);

// 			$msg	= 'Customer Deleted successfully';
// 			$class	= 'alert alert-success alert-dismissible';		
// 			$this->session->set_flashdata('notify',$msg);
// 			$this->session->set_flashdata('class',$class);	
			
// 			redirect('admin/list-customer');
// 		}		

// 	}


   public function delete_customer()
	{
          $getId = $this->input->post('getid');
          $data =  $this->db->delete('tbl_customers',array('customer_id'=>$getId));
         // echo $this->db->last_query();
          if($data){
			$output['status'] = 'success';
			$output['message'] = 'Member deleted successfully';
		}
		else{
			$output['status'] = 'error';
			$output['message'] = 'Something went wrong in deleting the member';
		}
 
		echo json_encode($output);	

	}
	
	public function change_customer_status($customer_id,$status)
	{
		$status  = ($status == '0')?'1':'0';
		$r= array('access_status'=>$status);
		$result=$this->basic_operation_m->update('tbl_customers',$r, array('customer_id'=>$customer_id));
		redirect('admin/list-customer');	

	}
	public function getcity()
	{
		$pincode=$this->input->post('pincode');
		
		$whr1 =array('POSTCODE'=>$pincode);
		$res1=$this->basic_operation_m->selectRecord('tbl_pincode',$whr1);	
		$result1 = $res1->row();

		$str= $result1->TOWN."-".$result1->PROVINCE;

		echo $str;
	}
	 public function getCityList()
    {
       $pincode = $this->input->post('pincode');
		$whr1 = array('pin_code' => $pincode);
		$res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);	
		
		$city_id = $res1->row()->city_id;
		
		$whr2 = array('id' => $city_id);
		$res2 = $this->basic_operation_m->selectRecord('city', $whr2);
		$result2 = $res2->row();

		echo json_encode($result2);
    }	
    public function getState() {
		$pincode = $this->input->post('pincode');
		$whr1 = array('pin_code' => $pincode);
		$res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);	
		
		$state_id = $res1->row()->state_id;
		$whr3 = array('id' => $state_id);
		$res3 = $this->basic_operation_m->selectRecord('state', $whr3);
		$result3 = $res3->row();

		echo json_encode($result3);
		
	}

}
