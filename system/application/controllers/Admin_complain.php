<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_complain extends CI_Controller {

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

	// this funciton is use for complain managment 
	public function complain_view()
	{
		$data = array();
		$data['all_ticket']				= $this->basic_operation_m->get_admin_all_ticket();	
		$this->load->view('admin/complain/view_complain', $data);
		
	}
	
	
	// this function is use for getting all tickets 
	public function open_ticket()
	{
		
		$user_id 							= $this->session->userdata('userId');
		
		$data['user_id']					= $this->session->userdata('userId');
		$data['admin_info']					= $this->basic_operation_m->get_admin_info_by_id(1);	
		$data['domstic_pod']				= $this->basic_operation_m->get_domestic_pod($user_id);	
		
		$data['international_pod']			= $this->basic_operation_m->get_international_pod($user_id);	
		$data['total_open_ticket']			= $this->basic_operation_m->get_all_ticket_by_status(1);
		$data['total_replied_ticket']		= $this->basic_operation_m->get_all_ticket_by_status(3);
		$data['total_creplied_ticket']		= $this->basic_operation_m->get_all_ticket_by_status(2);
		$data['total_closed_ticket']		= $this->basic_operation_m->get_all_ticket_by_status(5);
		$data['all_ticket']					= $this->basic_operation_m->get_all_ticket();
	
		$this->load->view('admin/complain/open_ticket',$data);
		
	}
	
	// this function is use for getting all tickets 
	public function insert_ticket()
	{
		$data								= array();
		$user_id							= $this->session->userdata('userId');
		
		$data['user_info']					= $this->basic_operation_m->get_admin_info_by_id($user_id);
		$data['admin_info']					= $this->basic_operation_m->get_admin_info_by_id(1);
		
		$all_data 							= $this->input->post();
		$file								= '';
		if($all_data['subject'] != '' && $all_data['message'] != ''  && $all_data['pod_no'] != '' && $all_data['priorty'] != '')
		{
			if(isset($_FILES['file']['name']))
			{
				$config 					= array(
									'encrypt_name' => TRUE,
									'allowed_types' => '*',
									'upload_path'   => 'assets/upload');
				 $this->load->library('upload'); 
                $this->upload->initialize($config);
				if(!$this->upload->do_upload('file'))
				{
					$error = $this->upload->display_errors();
				
				}
				else 
				{
					$upload_info	 		= $this->upload->data();
					$file					= $upload_info['file_name'];
				}
			}
		
			

			$all_data['user_id']				= $user_id;
			$message							= $all_data['message'];
			unset($all_data['email']);
			unset($all_data['message']);	
			
			
			$ticket_id							= $this->basic_operation_m->insert('ticket',$all_data);	
			$all_dataa							= array('ticket_id'=>$ticket_id,'user_type'=>$user_id,'to_email'=>$data['admin_info']->email,'message'=>$message,'attechment'=>$file);
			//$otp_status 						= $this->send_aws_mail($data['admin_info']->email,'[Ticket '.$data['page_info']->name.' #'.$ticket_id.'] '.$all_data['subject'],$admin_message,$file);
			//$otp_status 						= $this->send_aws_mail($data['user_info']->email,'[Ticket '.$data['page_info']->name.' #'.$ticket_id.'] '.$all_data['subject'],$user_message,$file);
			
			$ticket_id							= $this->basic_operation_m->insert('ticket_msg',$all_dataa);	
			
		}
		redirect('User_panel/complain_view');
	}
	
	// this function is use for getting all tickets 
	public function insert_replay($ticket_id)
	{
		$data								= array();
		
		$user_id							= $this->session->userdata('userId');
		
		$ticket_info						= $this->basic_operation_m->get_ticket_info($ticket_id);
	
		$data['user_info']					= $this->basic_operation_m->get_user_info_by_id($ticket_info->user_id);
		
		$data['admin_info']					= $this->basic_operation_m->get_admin_info_by_id(1);
		
		$all_data 							= $this->input->post();
		$file								= '';
		if($all_data['message'] != '' && !empty($ticket_info))
		{
			$url = base_url().'assets/upload/';

			if(isset($_FILES['file']['name']))
			{
				$config 					= array(
									'encrypt_name' => TRUE,
									'allowed_types' => '*',
									'overwrite'     => FALSE,
									'upload_path'   => 'assets/upload');
				 $this->load->library('upload'); 
                $this->upload->initialize($config);
				if(!$this->upload->do_upload('file'))
				{
					$error = $this->upload->display_errors();
				
				}
				else 
				{
					$upload_info	 		= $this->upload->data();
					$file					= $upload_info['file_name'];
				}
			}
		
			
			$message				= $all_data['message'];
			if($user_id == 1)
			{
				//$otp_status 			= $this->send_aws_mail($data['user_info']->email,'[Ticket '.$data['page_info']->name.' #'.$ticket_id.'] '.$ticket_info->subject,$user_message,$file);
				$all_dataa				= array('ticket_id'=>$ticket_id,'user_type'=>$user_id,'to_email'=>$data['user_info']->email,'message'=>$message,'attechment'=>$file);
				$query_status			= $this->basic_operation_m->update("ticket",array("ticket_status"=> 3),array("ticket_id" => $ticket_id));
			}
			else
			{
				//$otp_status 			= $this->send_aws_mail($data['admin_info']->email,'[Ticket '.$data['page_info']->name.' #'.$ticket_id.'] '.$ticket_info->subject,$admin_message,$file);
				$all_dataa				= array('ticket_id'=>$ticket_id,'user_type'=>$user_id,'to_email'=>$data['user_info']->email,'message'=>$message,'attechment'=>$file);
				$query_status			= $this->basic_operation_m->update("ticket",array("ticket_status"=> 2),array("ticket_id" => $ticket_id));
			}
			$query_status				= $this->basic_operation_m->insert('ticket_msg',$all_dataa);
		}
		redirect('admin/ticket-detail/'.$ticket_id);
	}
	
	// this function is use for closing ticket
	public function ticket_close($ticket_id)
	{
		$data								= $this->data;
		$user_id							= $this->session->userdata('userId');
		
		$ticket_info						= $this->basic_operation_m->get_ticket_info($ticket_id);
		$data['user_info']					= $this->basic_operation_m->get_admin_info_by_id($ticket_info->user_id);
		if(!empty($ticket_info))
		{	
			
			//$otp_status 			= $this->send_aws_mail($data['user_info']->email,'[Ticket '.$data['page_info']->name.' #'.$ticket_id.'] '.$ticket_info->subject,$user_message,'');
			$query_status			= $this->basic_operation_m->update("ticket",array("ticket_status"=> 5),array("ticket_id" => $ticket_id));
		}
		redirect('admin/view-complain');
	}
	
	// this function is use for getting all tickets 
	public function ticket_detail($ticket_id)
	{
		
		$data								= array();
		$user_id 							= $this->session->userdata('userId');
		$uesr_info 							= $this->basic_operation_m->get_user_info_by_id($user_id);
	
		$data['user_id']					= $user_id;
		$data['ticket_info']				= $this->basic_operation_m->get_ticket_info($ticket_id);	
		$data['ticket_user_info']			= $this->basic_operation_m->get_user_info_by_id($data['ticket_info']->user_id);
		$data['ticket_chat']				= $this->basic_operation_m->get_ticket_chat($ticket_id);	
		$this->load->view('admin/complain/ticket_detail',$data);
		
	}
	
	// this function is use for getting all tickets 
	public function delete_ticket($ticket_id)
	{
		$query_status			= $this->basic_operation_m->run_query("update ticket set ticket_status = 5 where ticket_id = $ticket_id");
		/* $user_id						= $this->session->userdata('user_id');
		$update_payed					= "delete from ticket where ticket_id = '$ticket_id'";
		$query_status					=  $this->basic_operation_m->run_query($update_payed);
		
		$update_payed					= "delete from ticket_msg where ticket_id = '$ticket_id'";
		$query_status					=  $this->basic_operation_m->run_query($update_payed);
		redirect('ticket'); */
		redirect('ticket');
		
	}

}
