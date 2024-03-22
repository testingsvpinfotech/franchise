<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Franchise_bag_genrate extends CI_Controller {

	function __construct()
	{
		 parent:: __construct();
		 $this->load->model('basic_operation_m');
		  $this->load->model('generate_pod_model');
		  if ($this->session->userdata('customer_id') == '') {
			redirect('franchise');
		}
		 
	}

	public function index()
	{			
	
	     $branch_name = $_SESSION['customer_id'];

		 $data['allpod'] =$this->db->query("select *,sum(total_pcs) as total_pcs,sum(total_weight) as total_weight from tbl_domestic_bag where tbl_domestic_bag.user_id='$branch_name'  group by bag_id order by bag_id desc")->result();
		//print_r($data['allpod']);die;
		//echo $this->db->last_query();exit;
		//  if($resAct->num_rows()>0)
		//  {
		// 	$data['allpod']=$resAct->result();	            
		//  }
		 $this->load->view('masterfranchise/bag_master/view_bag',$data);
	}	
		
	public function add_bag()
	{ 
		$result = $this->db->query('select max(inc_id) AS id from tbl_domestic_bag')->row();  
		$id= $result->id+1;
		if(strlen($id)==2)
		{
		   $id='MD00'.$id;
		}else if(strlen($id)==3)
		{
		   $id='MD0'.$id;
		}else if(strlen($id)==1)
		{
		   $id='MD000'.$id;
		}else
		{
		   $id='MD'.$id;
		}
		$data['message']="";
		
		//for pod_no
		$branch_name = $_SESSION['branch_name'];
		$branch_id = $_SESSION['branch_id'];
		$username = $_SESSION['customer_name'];
		$data['branch_name']=	$branch_name;
	    $user_type 			= 	$this->session->userdata("userType");
	    $user_id 			= 	$this->session->userdata("userId");
       
		 $where 				= 	'';
		
        if($user_type == 5) 
		{
           $where = " AND user_id ='$user_id'";     
        }
		else if($user_type == 4) 
		{
			$where = " AND branch_id ='$branch_id' and  menifiest_branches not like '%$branch_id%' and menifiest_recived ='0' "; 
		}
		else
		{
			$where = "and menifiest_branches not like '%$branch_id%' and menifiest_recived ='0' "; 
		}			
		
		
		
		$data['pod']					= array();
		
		
        
		$wheresuper = array('user_type'=>9,'branch_id'=>$branch_id);
		$data['supervisor']			=	$this->basic_operation_m->get_all_result('tbl_users',$wheresuper);
        //echo $this->db->last_query();die();
		//print_r($data['supervisor']);die();
		$data['allroute']=$this->basic_operation_m->get_all_result('route_master','');  
		$whr_c =array('branch_id!='=>$branch_id);
		$data['branches']=$this->basic_operation_m->get_all_result('tbl_branch',$whr_c);
		$whr_c =array('company_type'=>'Domestic');
		$data['courier_company']=$this->basic_operation_m->get_all_result('courier_company',$whr_c);
		$data['coloader_list']=$this->basic_operation_m->get_all_result('tbl_coloader',"");
		$data['mode_list'] = $this->basic_operation_m->get_all_result('transfer_mode',"");
		//print_r($data['coloader_list']);exit;
		$data['mid']=$id;
		
		$ress					=	$this->basic_operation_m->getAll('tbl_branch','');
		$data['all_branch']		= 	$ress->result();
		
		$ress					=	$this->basic_operation_m->getAll('tbl_vendor','');
		$data['all_vendor']		= 	$ress->result();
		$data['username'] = $username;
		
		
		$this->load->view('masterfranchise/bag_master/addbag', $data);	
	}

	public function insert_bag()
	{
		$user_type 			= 	$this->session->userdata("userType");
	    $user_id 			= 	$this->session->userdata("userId");
		$all_data 			= $this->input->post();



		if(!empty($all_data))
        {	
			$franchise_id = $_SESSION['customer_id'];
			$pod	=	$this->input->post('pod_no');
			$branch_name = $_SESSION['branch_name'];
			$branch_id = $_SESSION['branch_id'];
			$username = $_SESSION['customer_name'];
			$user_id = $_SESSION['customer_id'];
			$pod			= array_unique($pod);

		
			$result = $this->db->query('select max(inc_id) AS id from tbl_domestic_bag')->row();  
			$inc_id= $result->id+1;
			$id= $result->id+1;
			if(strlen($id)==2)
			{
			   $id='BG00'.$id;
			}else if(strlen($id)==3)
			{
			   $id='BG0'.$id;
			}else if(strlen($id)==1)
			{
			   $id='BG000'.$id;
			}else
			{
			   $id='BG'.$id;
			}
			$this->db->trans_start();
			foreach ($pod as  $pdno) 
			{	
				$arr 	= explode("|",$pdno);
				$pdno 	= $arr[0];
				$a_w  	= $arr[1];
				$pcs  	= $arr[2];
				$user_id = $_SESSION['customer_id'];
				$cust = $this->db->query("select * from tbl_customers where customer_id = '$user_id'")->row();
				$branchp = $cust->branch_id;
				
				$branchf = $this->db->query("select * from tbl_branch where branch_id = '$branchp'")->row();
				$user_id = $this->session->userdata("customer_id");
			$gat_area = $this->db->query("select cmp_area from tbl_franchise where fid = '$user_id'")->row();
            $area = $gat_area->cmp_area;
            $branch = $_SESSION['branch_name'];
			if(!empty($area)){
				$branch_name = $branch . "_" .$area ;
			}else
			{
				$branch_name = $branch;
			}
				//$branch_name = ." Maser Franchise";

				// $data=array('bag_recived'=>'2');
				// $whr = array('pod_no'=>$pdno);
				// $update = $this->basic_operation_m->update('tbl_domestic_invoice',$data,$whr);
				$queue_dataa1 = "update tbl_domestic_bag set bag_recived ='2' where pod_no = '$pdno'";
				$status1	= $this->db->query($queue_dataa1);
				$data=array('id'=>'',
	            	'bag_id'=>$id,
	        	    'pod_no'=>$pdno,
				    'source_branch'=>$branch_name,
	        	    'user_id' => $user_id,
				    'date_added'=>date('Y-m-d H:i:s',strtotime($this->input->post('datetime'))),
					'forwarder_name' => $this->input->post('forwarder_name'),
					'forwarder_mode' => $this->input->post('forwarder_mode'),
					'note' => $this->input->post('note'),
					'total_weight' => $a_w,
					'total_pcs' => $pcs,						 
					'inc_id'=>$inc_id,
					'franchise_id'=>$franchise_id,
					'bag_recived'=>0,
				);
						
					
				$result=$this->basic_operation_m->insert('tbl_domestic_bag',$data);
				//echo $this->db->last_query();die;
				$whr 					=	array('pod_no'=>$pdno);
				$booking_info			=	$this->basic_operation_m->getAll('tbl_domestic_booking',$whr);
				$menifiest_branches		= 	$booking_info->row()->menifiest_branches;
				$booking_id				= 	$booking_info->row()->booking_id;
			
				$date=$this->input->post('datetime');
				$data1=array('id'=>'',
					'pod_no'=>$pdno,
					'status'=>'Bag genrated',
					'branch_name'=>$branch_name,
					'forworder_name'=>$this->input->post('forwarder_name'),
					'remarks'=>$this->input->post('note'),
					'booking_id'=>$booking_id,
					'shipment_info'=>$id,
					'added_branch'=>$branch_name,
					'tracking_date'=>date('Y-m-d H:i:s',strtotime($this->input->post('datetime'))),
				);
				$result1	=	$this->basic_operation_m->insert('tbl_domestic_tracking',$data1);
				
				if(!empty($menifiest_branches))
				{
					$braches_ids 		= explode(',',$menifiest_branches);
					$braches_ids[]		= $branch_id;
					$braches_ids		= array_unique($braches_ids);
					$menifiest_branches		= implode(',',$braches_ids);
				}
				else
				{
					$menifiest_branches			= $branch_id;
				}
				
				$queue_dataa = "update tbl_domestic_booking set menifiest_branches ='$menifiest_branches',menifiest_recived ='1' where booking_id = '$booking_id'";
				$status	= $this->db->query($queue_dataa);	
			
			}
				
			$this->db->trans_complete();
			if ($this->db->trans_status() === TRUE)
			{
				$this->db->trans_commit();
				$msg	= 'Bag Generated Successfully Bag No : '.$id;
				$class	= 'alert alert-success alert-dismissible';
			}
			else
			{
				$this->db->trans_rollback();
				$msg	= 'Some thing went wrong';
				$class	= 'alert alert-danger alert-dismissible';
			}

			$this->session->set_flashdata('notify',$msg);
			$this->session->set_flashdata('class',$class);		
			redirect('master_franchise/list-domestic-bag');
		}	
	
	}
	
	

	public function awbnodata()
	{
		$forwording_no 		= trim($_REQUEST['forwording_no']);
		$forwarderName 		= trim($_REQUEST['forwarderName']);
		$mode_dispatch	 	= trim($_REQUEST['forwarder_mode']);
		
		
		$mode_info			= $this->basic_operation_m->get_table_row('transfer_mode',array('mode_name'=>$mode_dispatch));
		
		$username			= $this->session->userdata("userName");
		$user_type = $this->session->userdata("userType");
	  	$user_id = $this->session->userdata("userId");
         $where = '';
        
		
		$branch_name		= 	$_SESSION['branch_name'];
		$branch_id		= 	$_SESSION['branch_id'];
		
		$block_status				 = $this->basic_operation_m->get_query_row("select GROUP_CONCAT(customer_id) AS total from access_control where block_status = 'Menfiest' and current_status ='0'"); 
		// if(!empty($block_status) && !empty($block_status->total))
		// {
		// 	$block_statuss	= str_replace(",","','",$block_status->total);
		// 	if($mode_dispatch == 'All')
		// 	{
		// 		$where = "and customer_id not IN ('$block_statuss') and menifiest_branches not like '%$branch_id%' and menifiest_recived ='0' and forworder_name = '$forwarderName'"; 		
		// 	}
		// 	else
		// 	{
		// 		$where = "and customer_id not IN ('$block_statuss') and menifiest_branches not like '%$branch_id%' and menifiest_recived ='0' and forworder_name = '$forwarderName' and mode_dispatch = '$mode_info->transfer_mode_id'"; 		
		// 	}			
		// }
		// else
		// {
		// 	if($mode_dispatch == 'All')
		// 	{
		// 		$where = "and menifiest_branches not like '%$branch_id%' and menifiest_recived ='0' and forworder_name = '$forwarderName'"; 		
		// 	}
		// 	else
		// 	{
		// 		$where = "and menifiest_branches not like '%$branch_id%' and menifiest_recived ='0' and forworder_name = '$forwarderName' and mode_dispatch = '$mode_info->transfer_mode_id'"; 		
		// 	}
		// }
	
		$resAct5 = $this->db->query("SELECT * FROM tbl_domestic_booking where pod_no='$forwording_no' and is_delhivery_complete = '0' $where limit 1");	
		
		if ($resAct5->num_rows() == 0)
		{
			$resAct5 = $this->db->query("SELECT * FROM tbl_domestic_booking where pod_no = '$forwording_no' and is_delhivery_complete = '0'  $where limit 1");
		}

		$data = "";
	
       if ($resAct5->num_rows() > 0) 
		 {
			ini_set('display_errors', '0');
        ini_set('display_startup_errors', '0');
        error_reporting(E_ALL);
		 	$booking_row = $resAct5->row_array();
		 	//print_r($booking_row);
			$pod =  $booking_row['pod_no'];
			$booking_id = $booking_row['booking_id'];
			
			$query_result= $this->db->query("select * from tbl_domestic_weight_details where booking_id = '$booking_id'")->row();
			
			$actual_weight = $query_result->actual_weight;
			//$no_of_pack	   = $booking_row['a_qty'];
			$no_of_pack = $query_result->no_of_pack;
			$podid 		   = "checkbox-".$pod;
			$dataid 	   = 'data-val-'.$booking_id;

			$pod_no = $booking_row['pod_no'];
			$data .='<tr><td>';
			$data .= "<input type='checkbox' class='cb'  name='pod_no[]'  data-tp='{$no_of_pack}' data-tw='{$actual_weight}' value='{$pod_no}|{$actual_weight}|{$no_of_pack}' checked><input type='hidden' name='actual_weight[]' value='".$actual_weight."'/><input type='hidden' name='pcs[]' value='".$no_of_pack."'/></td>";

			// $data .= "<input type='checkbox' class='cb'  name='pod_no[]'  data-tp='{$no_of_pack}' data-tw='{$actual_weight}' value='{$pod_no}' checked>";

			$data .= "<input type='checkbox' class='cb'  name='actual_weight[]' value='".$actual_weight."' checked>";
			$data .= "<input type='checkbox' class='cb'  name='pcs[]' value='".$no_of_pack."' checked>";

			$data .="<input type='hidden' name='rec_pincode' value=".$booking_row['reciever_pincode'].">";
			$data .= "<td>".$booking_row['pod_no']."</td>";
			$data .= "<td>".$booking_row['sender_name']."</td>";
			$data .= "<td>".$booking_row['reciever_name']."</td>";
			$data .= "<td>".$mode_dispatch."</td>";
			$resAct66 = $this->db->query("select * from city where id ='".$booking_row['sender_city']."'");
			if($resAct66->num_rows() > 0)
			{
				$citydata  		 = $resAct66->row();
				$data		 	.="<td>".$citydata->city."</td>";
			}
		
						
			$resAct6 = $this->db->query("select * from city where id ='".$booking_row['reciever_city']."'");
			if($resAct6->num_rows() > 0)
			{
				$citydata  		 = $resAct6->row();
				$data		 	.="<td>".$citydata->city."</td>";
				
			}
			
			if($booking_row['dispatch_details'] == 'ToPay')
			{
				$data .= "<td>".$booking_row['grand_total']."</td>";
			}
			else
			{
				$data .= "<td>0</td>";
			}
			
			$data .="<input type='hidden' readonly name='forwarder_name' id='forwarder_name'  class='form-control' value='".$booking_row['forworder_name']."'/><input type='hidden' readonly name='branch_name' id='branch_name'  class='form-control' value='".$branch_name."'/>";
			$data .= "<td>".$no_of_pack."</td>";
			$data .= "<td>".$query_result->actual_weight."</td>";
			$data .= "<td>".$query_result->chargable_weight."</td>";
			$data .= "</tr>";
         }
        echo  $data ;
        
	}
    public function getPODDetails()
    {

	   $pod_no=$this->input->post('podno');

		$whr =array('pod_no'=>$pod_no);
		$res=$this->basic_operation_m->selectRecord('tbl_domestic_booking',$whr);			
		$result = $res->row();

		$whr1 =array('booking_id'=>$result->booking_id);
		$res1=$this->basic_operation_m->selectRecord('tbl_domestic_weight_details',$whr1);	
		$result1 = $res1->row();

		$str= $result->reciever_name."-".$result->reciever_address."-".$result1->no_of_pack."-".$result1->actual_weight;

		echo $str;
    }

	public function deletebag()
	{
		$data['message']="";
        $last = $this->uri->total_segments();
	    $id	= $this->uri->segment($last);
		if($id!="")
		{
		    $whr =array('id'=>$id);
			$res=$this->basic_operation_m->delete('tbl_domestic_bag',$whr);
			
            redirect(base_url().'bag');
		}		
	  
	}
	
	public function sendemail($to,$message)
	{
	    $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
	    $this->load->library('email');
	    $this->email->initialize($config);
        
        $this->email->from('info@grandspeednetwork.com', 'Grand Speed Network Admin');
        $this->email->to($to); 
        
        
        $this->email->subject('Shipment Update');
        $this->email->message($message);	
        
        $this->email->send();


	}
	
	
	

	




}
