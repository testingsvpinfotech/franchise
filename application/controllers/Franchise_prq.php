<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Franchise_prq extends CI_Controller {

	function __construct()
	{
		
		 parent:: __construct();
		 $this->load->model('basic_operation_m');
		 if($this->session->userdata('customer_id') == '')
		{
			redirect('home');
		}
	}
	
	
	
	
	

	public function pickup_request()
	{
		$data= array();
		$data['message']="";
		

		if (isset($_POST['submit'])){

			    $customer_id	=	$this->session->userdata("customer_id");
                 $date = date('y-m-d');
                 $pickup_date = $this->input->post('pickup_date');
				  $pickup_time = $this->input->post('pickup_time');
				 $pickup_date_time = $pickup_date."".$pickup_time;
				 $pickup_pincode = $this->input->post('pickup_pincode');
		
			$r= array('id'=>'',
					  'customer_id'=>$customer_id,
					  'consigner_name'=>$this->input->post('consigner_name'),
					  'pickup_request_id'=>$this->input->post('pickup_request_id'),
					  'consigner_contact' => $this->input->post('consigner_contact'),
					  'consigner_address1'=>$this->input->post('consigner_address1'),
					  'consigner_address2'=>$this->input->post('consigner_address2'),
					  'consigner_address3'=>$this->input->post('consigner_address3'),
					  'consigner_email' => $this->input->post('consigner_email'),
					  'pickup_pincode' => $this->input->post('pickup_pincode'),
					  'pickup_location' => $this->input->post('pickup_location'),
					  'pickup_date' =>$pickup_date_time,
					  'create_date' =>$date,
					  'city' => $this->input->post('city'),
					  'instruction' => $this->input->post('instruction'),
					  'mode_id' => $this->input->post('mode_id'),
				 );
				 //print_r($r);exit;
			$result=$this->basic_operation_m->insert('tbl_pickup_request_data',$r);
        
            
			
			
			$destination_pincode = $this->input->post('destination_pincode[]');
			$count = count($this->input->post('destination_pincode[]'));
			//$destination_location = '';
			$actual_weight = $this->input->post('actual_weight[]');
			$type_of_package = $this->input->post('type_of_package[]');
			$no_of_pack = $this->input->post('no_of_pack[]');
			$lastid = $this->db->insert_id();
             
          //  print_r($count);exit;
			for($i=0; $i<$count; $i++ ){
			$r1 = array(
					  'pickup_id'=>$lastid,
					  'destination_pincode' =>$destination_pincode[$i],
					//  'destination_location' =>$destination_location[$i],
					  'actual_weight' =>$actual_weight[$i],
					  'type_of_package' =>$type_of_package[$i],
					  'no_of_pack' =>$no_of_pack[$i],
				 );
				//  print_r($r1);exit;
				 
				 $result = $this->db->insert('pickup_weight_tbl',$r1); 
				//  echo $this->db->last_query();exit;


				}

				if(!empty($pickup_pincode)){

					$pickup_pincode_data = $this->db->query("select pickup_pincode from tbl_pickup_request_data where id = '$lastid'")->row_array();
					$pin_code = $pickup_pincode_data['pickup_pincode'];
					$branch_pincode = $this->db->query("select * from tbl_branch_service where pincode = '$pin_code'")->row_array();
					$get_branch_id = $branch_pincode['branch_id'];
	
					$this->db->set('branch_id', $get_branch_id);
					$this->db->where('id', $lastid);
					 $this->db->update('tbl_pickup_request_data');
					 //echo $this->db->last_query();exit;
				}
		
			if  (!empty($result)) {
				$this->session->set_flashdata('flash_message',"Data Inserted Successfully!!");
			 }
			redirect('Franchise_prq/pickup_request');
		}

		$data			= array();
        $result 		= $this->db->query('select max(id) AS id from tbl_pickup_request_data')->row();
        $id 			= $result->id + 1;
        $date =date('ym');
		if (strlen($id) == 2) 
		{
            $id = 'BNF/'.$date.'/000'.$id;
        }
		elseif (strlen($id) == 3) 
		{
            $id = 'BNF/'.$date.'/000'.$id;
        }
		elseif (strlen($id) == 1) 
		{
            $id = 'BNF/'.$date.'/000'.$id;
        }
		elseif (strlen($id) == 4) 
		{
            $id = 'BNF/'.$date.'/000'.$id;
        }
		elseif (strlen($id) == 5) 
		{
            $id = 'BNF/'.$date.'/000'.$id;
        }
        $data['request_id'] = $id ;
		
		$todaydate = date('y-m-d');
		$customer_id					=	$this->session->userdata("customer_id");
	    $data['type_of_package'] = $this->db->query("select * from partial_type_tbl")->result();
		$data['time_data'] = $this->db->query("select * from pickup_time_slot_tbl")->result();
	    $data['transfer_mode'] = $this->db->query("select * from transfer_mode")->result();
		$data['today_booking'] = $this->db->query("SELECT COUNT(pickup_request_id) AS total_today_booking FROM tbl_pickup_request_data WHERE create_date = '$todaydate' AND customer_id ='$customer_id'")->result_array();
		//echo $this->db->last_query();exit;
		$data['total_pending'] = $this->db->query("SELECT COUNT(pickup_request_id) AS total_pending_shipment FROM tbl_pickup_request_data WHERE pickup_status = '0'AND customer_id ='$customer_id'")->result_array();
		$data['total_complete'] = $this->db->query("SELECT COUNT(pickup_request_id) AS total_complete_shipment FROM tbl_pickup_request_data WHERE pickup_status = '1'AND customer_id ='$customer_id'")->result_array();
	    $data['request_data'] = $this->db->query("select tbl_pickup_request_data.*,pickup_weight_tbl.destination_pincode from tbl_pickup_request_data left join pickup_weight_tbl ON pickup_weight_tbl.pickup_id = tbl_pickup_request_data.id where customer_id = '$customer_id' ORDER BY pickup_date DESC LIMIT 10")->result();
		// print_r($data['today_booking']);exit;
		$this->load->view('franchise/customer_prq/add_pickup_request',$data);
	
	}







	public function view_pickup_request()
	{
		// print_r($this->session->all_userdata());
		$customer_id =	$this->session->userdata("customer_id");		
		$data['all_request'] = $this->db->query("select tbl_pickup_request_data.*,transfer_mode.mode_name,pickup_weight_tbl.destination_pincode,pickup_weight_tbl.actual_weight,pickup_weight_tbl.type_of_package,pickup_weight_tbl.no_of_pack from  tbl_pickup_request_data left join pickup_weight_tbl on pickup_weight_tbl.pickup_id =  tbl_pickup_request_data.id left join transfer_mode on transfer_mode.transfer_mode_id=tbl_pickup_request_data.mode_id where customer_id = '$customer_id'")->result();
		// echo json_encode($data);			
        // $data['all_request']			= $this->basic_operation_m->get_table_result('tbl_pickup_request',array('customer_id'=>$customer_id));
		// echo $this->db->last_query();exit;
		$this->load->view('franchise/customer_prq/view_pickup_request',$data);
	
	}
	
}







