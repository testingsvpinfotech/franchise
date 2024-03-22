<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FTLController extends CI_Controller
{

    var $data = array();
    function __construct()
    {
        parent::__construct();
         $this->load->helper(array('form', 'url'));
        $this->load->model('basic_operation_m');
        $this->load->model('Franchise_model');
        if ($this->session->userdata('userId') == '') {
            redirect('admin');
        }
    }

    public function index()
    {
        $data['cities']	= $this->basic_operation_m->get_all_result('city', '');
      	$data['states'] =$this->basic_operation_m->get_all_result('state', '');
        $user_type 	= $this->session->userdata("userType");
        	if($user_type == 1)
		{
			$data['customers'] =$this->basic_operation_m->get_all_result('tbl_customers', array('customer_type'=>0));
		}else{
			$username = $this->session->userdata("userName");
			$whr = array('username' => $username);
			$res = $this->basic_operation_m->getAll('tbl_users', $whr);
			$branch_id = $res->row()->branch_id;				
			$where ="branch_id='$branch_id' ";
			$customer_type =0;
			$data['customers'] =$this->basic_operation_m->get_all_result('tbl_customers', "branch_id = '$branch_id',customer_type = $customer_type");
		}
      
        $data['vehicle_type'] = $this->db->query("SELECT * FROM `vehicle_type_master`")->result();
        $data['insurance_company'] = $this->db->query("SELECT * FROM `insurance_company_tbl`")->result();
        $data['product_unit_name'] = $this->db->query("SELECT * FROM `product_unit_tbl`")->result();
         $this->load->view('admin/ftl_master/add_lr',$data);
    }
    
    
    public function insert_lr_details(){
        
            $date = date('Y-m-d',strtotime($this->input->post('booking_date')));
			$this->session->unset_userdata("booking_date");
			$this->session->set_userdata("booking_date",$this->input->post('booking_date'));
		    $lrno =	$this->input->post('lr_number');
		  
			$dd = $this->db->query('SELECT lr_number FROM `lr_table` WHERE `lr_number` ='."'$lrno'")->row();
		//	 echo $this->db->last_query();die();
			$insurance_details = $this->input->post('insurance_details');
		
 			if($insurance_details == 1){
			    $insurance_number = $this->input->post('insurance_number');
			    $insurance_company_name = $this->input->post('insurance_company_name');
			    $insurance_charges = $this->input->post('insurance_charges');
			    $insurance_date = date('y-m-d',strtotime($this->input->post('insurance_date')));
			}
			
	
		if(!empty($dd->lr_number )){
		    $msg = "Already Exist ". $this->input->post('lr_number');
			$class	= 'alert alert-danger alert-dismissible';	
			$this->session->set_flashdata('notify',$msg);
			$this->session->set_flashdata('class',$class);
		   	redirect(base_url().'admin/add-lr');
		   	
		 }else{  	
		   	
        $data = array(
            
            'booking_date' =>$date,
            'lr_number' =>$lrno,
            'order_number' =>$this->input->post('order_number'),
            'lorry_number' =>$this->input->post('lorry_number'),
            'type_of_vehicle' =>$this->input->post('type_of_vehicle'),
            'dispatch_details' => $this->input->post('dispatch_details'),   
            'invoice_value' =>$this->input->post('invoice_value'),
            'invoice_number' =>$this->input->post('invoice_number'),
            'lr_sender_address' =>$this->input->post('lr_sender_address'),
            'lr_receiver_address' =>$this->input->post('lr_receiver_address'),
           	'customer_id' => $this->input->post('customer_account_id'),
			'sender_name' => $this->input->post('sender_name'),
			'sender_address' => $this->input->post('sender_address'),
			'sender_city' => $this->input->post('sender_city'),
			'sender_state'=> $this->input->post('sender_state'),
			'sender_pincode' => $this->input->post('sender_pincode'),
			'sender_contactno' => $this->input->post('sender_contactno'),
			'sender_gstno' => $this->input->post('sender_gstno'),
			'reciever_name' => $this->input->post('reciever_name'),
			'contactperson_name' => $this->input->post('contactperson_name'),				
			'reciever_address' => $this->input->post('reciever_address'),
			'reciever_contact' => $this->input->post('reciever_contact'),
			'reciever_pincode' => $this->input->post('reciever_pincode'),
			'reciever_city' => $this->input->post('reciever_city'),
			'reciever_state' => $this->input->post('reciever_state'),
			'receiver_gstno' => $this->input->post('receiver_gstno'),
			'gst_pay' => $this->input->post('gst_pay'),
			'insurance_details'=>$insurance_details,
			'insurance_number' =>$insurance_number,
			'insurance_company_name' =>$insurance_company_name,
			'insurance_charges' =>$insurance_charges,
			'insurance_date' =>$insurance_date,
			
			);
			
			 
			$this->db->insert('lr_table', $data);
			
         
	    	$lastid = $this->db->insert_id();
			$data2 = array(
			'lr_id'=>$lastid,
			'product_name'=>$this->input->post('product_name'),
			'product_weight'=>$this->input->post('product_weight'),
			'declare_weight'=>$this->input->post('declare_weight'),
			'chargable_weight'=>$this->input->post('chargable_weight'),
			'product_unit'=>$this->input->post('product_unit'),
			'product_qty'=>$this->input->post('product_qty'),
			
			'frieht_charge'=>$this->input->post('frieht_charge'),
			'aso_charge'=>$this->input->post('aso_charge'),
			'labour_charge'=>$this->input->post('labour_charge'),
			'st_charge'=>$this->input->post('st_charge'),
			'lc_charge'=>$this->input->post('lc_charge'),
			'misc_charge'=>$this->input->post('misc_charge'),
			'ch_post_charge'=>$this->input->post('ch_post_charge'),
			'total_charge'=>$this->input->post('total_charge'),
			'gst_charge'=>$this->input->post('gst_charge'),
			'grand_total'=>$this->input->post('grand_total'),

            );
          
          	 $this->db->insert('lr_product_tbl', $data2); 
          	// echo $this->db->last_query();exit;
		
          	
    	     $msg   =  'Data Inserted Successfully!!';
		   	$class	= 'alert alert-success alert-dismissible';	
		
			$this->session->set_flashdata('notify',$msg);
			$this->session->set_flashdata('class',$class);
			redirect(base_url().'admin/add-lr');
					
        }
    } 
    
    
    public function add_unit(){
          
          $this->load->view('admin/ftl_master/add_unit'); 
    }
    
    public function view_ftl_list(){
        $filterCond = "";
        $all_data = $this->input->post();	
       // print_r($all_data);
		if($all_data)
		{	
		     
			$filter_value = 	$_POST['filter_value'];
			
			foreach($all_data as $ke=> $vall)
			{
				if($ke == 'filter' && !empty($vall))
				{
					if($vall == 'sender')
						{
							$filterCond .= " AND lr_table.sender_name LIKE '%$filter_value%'";
						}
						if($vall == 'receiver')
						{
							$filterCond .= " AND lr_table.reciever_name LIKE '%$filter_value%'";
						}
						
						if($vall == 'bill_type')
						{
						$filterCond .= " AND lr_table.dispatch_details LIKE '%$filter_value%'";
						}
						if($vall == 'vehicle_type')
						{
						$filterCond .= " AND lr_table.type_of_vehicle LIKE '%$filter_value%'";
						}
						
						if($vall == 'lr_no')
						{
						$filterCond .= " AND lr_table.lr_no LIKE '%$filter_value%'";
						}
					
				}
				
			        elseif($ke == 'from_date' && !empty($vall))
					{
						$filterCond .= " AND lr_table.booking_date >= '$vall'";
					}
					elseif($ke == 'to_date' && !empty($vall))
					{
						$filterCond .= " AND lr_table.booking_date <= '$vall'";
					}
					elseif($ke == 'sender' && !empty($vall))
					{
						$filterCond .= " AND lr_table.sender_name = '$vall'";
					}
					elseif($ke == 'receiver' && !empty($vall))
					{
						$filterCond .= " AND lr_table.reciever_name = '$vall'";
					}
					elseif($ke == 'bill_type' && !empty($vall))
					{
						$filterCond .= " AND lr_table.dispatch_details >= '$vall'";
					}
					elseif($ke == 'vehicle_type' && !empty($vall))
					{
						$filterCond .= " AND lr_table.type_of_vehicle <= '$vall'";
					}
			}
			
		    $resActt1 = $this->db->query("SELECT lr_table.*,lr_product_tbl.product_name,lr_product_tbl.product_weight,rc.city as reciever_city,vehicle_type_master.vehicle_name,sc.city as sender_city FROM lr_table LEFT JOIN lr_product_tbl ON lr_table.lr_id = lr_product_tbl.lr_id LEFT JOIN city as rc ON lr_table.reciever_city = rc.id LEFT JOIN vehicle_type_master ON lr_table.type_of_vehicle = vehicle_type_master.id LEFT JOIN city as sc ON lr_table.sender_city = sc.id  $filterCond ");
	               //echo $this->db->last_query();die();
					$data['ftl_list']= $resActt->result();
			
		}
		else
		{
			
		  $resActt = $this->db->query("SELECT lr_table.*,lr_product_tbl.product_name,lr_product_tbl.product_weight,rc.city as reciever_city,vehicle_type_master.vehicle_name,sc.city as sender_city FROM lr_table LEFT JOIN lr_product_tbl ON lr_table.lr_id = lr_product_tbl.lr_id LEFT JOIN city as rc ON lr_table.reciever_city = rc.id LEFT JOIN vehicle_type_master ON lr_table.type_of_vehicle = vehicle_type_master.id LEFT JOIN city as sc ON lr_table.sender_city = sc.id  $filterCond ");
	          $this->load->library('pagination');
			
				$data['total_count']			= $resActt->num_rows();
				$config['total_rows'] 			= $resActt->num_rows();
				$config['base_url'] 			= 'admin/FTL-List/';
				//	$config['suffix'] 				= '/'.urlencode($filterCond);
				
				$config['per_page'] 			= 50;
				$config['full_tag_open'] 		= '<nav aria-label="..."><ul class="pagination">';
				$config['full_tag_close'] 		= '</ul></nav>';
				$config['first_link'] 			= '&laquo; First';
				$config['first_tag_open'] 		= '<li class="prev paginate_button page-item">';
				$config['first_tag_close'] 		= '</li>';
				$config['last_link'] 			= 'Last &raquo;';
				$config['last_tag_open'] 		= '<li class="next paginate_button page-item">';
				$config['last_tag_close'] 		= '</li>';
				$config['next_link'] 			= 'Next';
				$config['next_tag_open'] 		= '<li class="next paginate_button page-item">';
				$config['next_tag_close'] 		= '</li>';
				$config['prev_link'] 			= 'Previous';
				$config['prev_tag_open'] 		= '<li class="prev paginate_button page-item">';
				$config['prev_tag_close'] 		= '</li>';
				$config['cur_tag_open'] 		= '<li class="paginate_button page-item active"><a href="javascript:void(0);" class="page-link">';
				$config['cur_tag_close'] 		= '</a></li>';
				$config['num_tag_open'] 		= '<li class="paginate_button page-item">';
				$config['reuse_query_string'] 	= TRUE;
				$config['num_tag_close'] 		= '</li>';
				$config['attributes'] = array('class' => 'page-link');
				
				if($offset == '')
				{
					$config['uri_segment'] 			= 3;
					$data['serial_no']				= 1;
				}
				else
				{
					$config['uri_segment'] 			= 3;
					$data['serial_no']		= $offset + 1;
				}
				
				
				$this->pagination->initialize($config);
		        if($resActt->num_rows() > 0) 
				{
					$data['ftl_list']= $resActt->result();
                }else
				{
					$data['ftl_list']= array();
				}
	          
	          
				//	$data['ftl_list']= $resActt->result();
		    
		}
		
       $this->load->view('admin/ftl_master/ftl_list',$data);
    
    }
    public function view_lr_printlabel($id){
          $data['printlabel'] = $this->db->query("SELECT lr_table.*,rc.city as reciever_city,vehicle_type_master.vehicle_name,sc.city as sender_city FROM lr_table LEFT JOIN city as rc ON lr_table.reciever_city = rc.id LEFT JOIN vehicle_type_master ON lr_table.type_of_vehicle = vehicle_type_master.id LEFT JOIN city as sc ON lr_table.sender_city = sc.id WHERE `lr_id`=".$id)->result();
          $this->load->view('admin/ftl_master/lr_printlabel',$data);
    }
    
    
    public function edit_ftl($id){
            $data['message']="";
          
			if($id!=""){
				$whr =array('lr_id'=>$id);
				$data['lr'] = $this->basic_operation_m->get_table_row('lr_table',$whr);	
				$whr1 =array('lr_id'=>$id);
            $data['lr_product'] = $this->basic_operation_m->get_table_row('lr_product_tbl',$whr1);
			}
			
		
		
            				
	    $data['cities']	= $this->basic_operation_m->get_all_result('city', '');
      	$data['states'] =$this->basic_operation_m->get_all_result('state', '');
		$data['customers'] =$this->basic_operation_m->get_all_result('tbl_customers', array('customer_type'=>0));
		$data['vehicle_type'] = $this->db->query("SELECT * FROM `vehicle_type_master`")->result();
        $data['insurance_company'] = $this->db->query("SELECT * FROM `insurance_company_tbl`")->result();
        $data['product_unit_name'] = $this->db->query("SELECT * FROM `product_unit_tbl`")->result();
          $this->load->view('admin/ftl_master/view_edit_ftl',$data);
    }
    
    public function update_ftl($id){
			
			if($id!=""){
				$whr =array('lr_id'=>$id);
				$data['lr'] = $this->basic_operation_m->get_table_row('lr_table',$whr);	
				$whr1 =array('lr_id'=>$id);
            $data['lr_product'] = $this->basic_operation_m->get_table_row('lr_product_tbl',$whr1);
			}
			   
			      $data = array(
            
            'booking_date' => $this->input->post('booking_date'),
            'lr_number' =>$this->input->post('lr_number'),
            'lorry_number' =>$this->input->post('lorry_number'),
            'order_number' =>$this->input->post('order_number'),
            'type_of_vehicle' =>$this->input->post('type_of_vehicle'),
            'dispatch_details' => $this->input->post('dispatch_details'),   
            'invoice_value' =>$this->input->post('invoice_value'),
            'invoice_number' =>$this->input->post('invoice_number'),
            'lr_sender_address' =>$this->input->post('lr_sender_address'),
            'lr_receiver_address' =>$this->input->post('lr_receiver_address'),
           	'customer_id' => $this->input->post('customer_account_id'),
			'sender_name' => $this->input->post('sender_name'),
			'sender_address' => $this->input->post('sender_address'),
			'sender_city' => $this->input->post('sender_city'),
			'sender_state'=> $this->input->post('sender_state'),
			'sender_pincode' => $this->input->post('sender_pincode'),
			'sender_contactno' => $this->input->post('sender_contactno'),
			'sender_gstno' => $this->input->post('sender_gstno'),
			'reciever_name' => $this->input->post('reciever_name'),
			'contactperson_name' => $this->input->post('contactperson_name'),				
			'reciever_address' => $this->input->post('reciever_address'),
			'reciever_contact' => $this->input->post('reciever_contact'),
			'reciever_pincode' => $this->input->post('reciever_pincode'),
			'reciever_city' => $this->input->post('reciever_city'),
			'reciever_state' => $this->input->post('reciever_state'),
			'receiver_gstno' => $this->input->post('receiver_gstno'),
			'gst_pay' => $this->input->post('gst_pay'),
			'insurance_details'=>$this->input->post('insurance_details'),
			'insurance_number' =>$this->input->post('insurance_number'),
			'insurance_company_name' =>$this->input->post('insurance_company_name'),
			'insurance_charges' =>$this->input->post('insurance_charges'),
			'insurance_date' =>date('y-m-d',strtotime($this->input->post('insurance_date'))),
			);
			
			 $result = $this->basic_operation_m->update('lr_table',$data, $whr);
		
			$data2 = array(
			'lr_id'=>$id,
			'product_name'=>$this->input->post('product_name'),
			'product_weight'=>$this->input->post('product_weight'),
			'declare_weight'=>$this->input->post('declare_weight'),
			'chargable_weight'=>$this->input->post('chargable_weight'),
			'product_unit'=>$this->input->post('product_unit'),
			'product_qty'=>$this->input->post('product_qty'),
			
			'frieht_charge'=>$this->input->post('frieht_charge'),
			'aso_charge'=>$this->input->post('aso_charge'),
			'labour_charge'=>$this->input->post('labour_charge'),
			'st_charge'=>$this->input->post('st_charge'),
			'lc_charge'=>$this->input->post('lc_charge'),
			'misc_charge'=>$this->input->post('misc_charge'),
			'ch_post_charge'=>$this->input->post('ch_post_charge'),
			'total_charge'=>$this->input->post('total_charge'),
			'gst_charge'=>$this->input->post('gst_charge'),
			'grand_total'=>$this->input->post('grand_total'),

            );
			
			$priesh = $this->basic_operation_m->update('lr_product_tbl',$data2, $whr1);
			//	echo $this->db->last_query();die();
				if ($this->db->affected_rows() > 0) {
					$data['message']="data Updated Sucessfully";
				}else{
					$data['message']="Error in Query";
				}
				if(!empty($data))
				{						
					$msg			= ' updated successfully';
					$class			= 'alert alert-success alert-dismissible';		
				}else{
					$msg			= 'Customer not updated successfully';
					$class			= 'alert alert-danger alert-dismissible';	
				}	
				$this->session->set_flashdata('notify',$msg);
				$this->session->set_flashdata('class',$class);

				redirect('admin/FTL-List');
			}
    
    

              public function deleteftl(){
                  $getId = $this->input->post('getid');
                  $data =  $this->db->delete('lr_table',array('lr_id'=>$getId));
                  $data1 =  $this->db->delete('lr_product_tbl',array('lr_id'=>$getId));
                  if($data && $data1){
        			$output['status'] = 'success';
        			$output['message'] = 'Member deleted successfully';
        		}
        		else{
        			$output['status'] = 'error';
        			$output['message'] = 'Something went wrong in deleting the member';
        		}
         
        		echo json_encode($output);
         
        	}
    
    
    
    
    
}