<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_menifiest extends CI_Controller {

	function __construct()
	{
		 parent:: __construct();
		 $this->load->model('basic_operation_m');
		 if($this->session->userdata('userId') == '')
		{
			redirect('admin');
		}
		 
	}

	public function index()
	{			
		 $username=$this->session->userdata("userName");
		 
		 $whr	 = array('username'=>$username);
		 $res	=	$this->basic_operation_m->getAll('tbl_users',$whr);
		 
		 $branch_id= $res->row()->branch_id;
	
		 $where = '';
		 $user_type = $this->session->userdata("userType");
	     $user_id = $this->session->userdata("userId");
         if($user_type == 5) {
            $where = ' AND user_id ='.$user_id;     
         }
         
		 $whr = array('branch_id'=>$branch_id);
		 
		 $res_branch=$this->basic_operation_m->getAll('tbl_branch',$whr);
		 $branch_name= $res_branch->row()->branch_name;

		 

		 $data= array();

		$resAct=$this->db->query("select *,sum(total_pcs) as total_pcs,sum(total_weight) as total_weight from tbl_domestic_menifiest where tbl_domestic_menifiest.source_branch='$branch_name' $where  group by `manifiest_id`");

		//echo $this->db->last_query();exit;
		 if($resAct->num_rows()>0)
		 {
			$data['allpod']=$resAct->result();	            
		 }
		 $this->load->view('admin/Menifiest_master/view_menifiest',$data);
	}	
		
	public function add_menifiest()
	{ 
		$result = $this->db->query('select max(id) AS id from tbl_domestic_menifiest')->row();  
		$id= $result->id+1;
		if(strlen($id)==2)
		{
		   $id='M00'.$id;
		}else if(strlen($id)==3)
		{
		   $id='M0'.$id;
		}else if(strlen($id)==1)
		{
		   $id='M000'.$id;
		}else if(strlen($id)==4)
		{
		   $id='M'.$id;
		}
		$data['message']="";
		
		//for pod_no
		$username			= $this->session->userdata("userName");
		$whr 				= array('username'=>$username);
		$res				= $this->basic_operation_m->getAll('tbl_users',$whr);
		$branch_id			= $res->row()->branch_id;
	
		$whr 				= 	array('branch_id'=>$branch_id);
		$res				=	$this->basic_operation_m->getAll('tbl_branch',$whr);
		$branch_name		= 	$res->row()->branch_name;
		$date				=	date('Y-m-d');
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
		
		$username		=	$this->session->userdata("userName");
		$whr 			= 	array('username'=>$username);
		$res			=	$this->basic_operation_m->getAll('tbl_users',$whr);
		$branch_id		= 	$res->row()->branch_id;
	
		$whr_c =array('branch_id!='=>$branch_id);
		$data['branches']=$this->basic_operation_m->get_all_result('tbl_branch',$whr_c);
		$whr_c =array('company_type'=>'Domestic');
		$data['courier_company']=$this->basic_operation_m->get_all_result('courier_company',$whr_c);
		$data['coloader_list']=$this->basic_operation_m->get_all_result('tbl_coloader',"");
		$data['mode_list'] = $this->basic_operation_m->get_all_result('transfer_mode',"");
		//print_r($data['coloader_list']);exit;
		$data['mid']=$id;
		$this->load->view('admin/Menifiest_master/addmenifiest', $data);	
	}

	public function insert_menifiest()
	{
		$user_type 			= 	$this->session->userdata("userType");
	    $user_id 			= 	$this->session->userdata("userId");
		
		$all_data 			= $this->input->post();



		if(!empty($all_data))
        {
			// if(isset($_FILES['csv_zip']['name']) && !empty($_FILES['csv_zip']['name']))
			// {			
			// 	$file = fopen($_FILES['csv_zip']['tmp_name'],"r");
			
			// 	$cnt = 0;
			// 	$pcs = 0;
			// 	$a_w = 0;
				
			// 	while(!feof($file))
			// 	{
			// 		$data = fgetcsv($file);
			// 		if(!empty($data[0]))
			// 		{					
						
			// 			$resAct			= $this->db->query("select booking_id from tbl_domestic_booking where  pod_no='$pod_no'");
			// 			$info			= $resAct->row();
			// 			if(!empty($info))
			// 			{
			// 				$booking_id		= 	$info->booking_id;
			// 				$resActt		=	$this->db->query("select no_of_pack,chargable_weight from tbl_domestic_weight_details where  booking_id='$booking_id'");							
			// 				$infoo			=	$resActt->row();
			// 				$pod[$pod_no]	= 	$pod_no.'|'.$infoo->chargable_weight.'|'.$infoo->no_of_pack;
			// 			}
			// 		}
					
			// 	}
			// }
			// else
			// {
			// 	$pod	=	$this->input->post('pod_no');
			// }
			
			$pod	=	$this->input->post('pod_no');
            $username	=	$this->session->userdata("userName");
			$whr 		= 	array('username'=>$username);
			$res		=	$this->basic_operation_m->getAll('tbl_users',$whr);
		    $branch_id	=	$res->row()->branch_id;
			$date		=	date('Y-m-d');
			 
			$whr 			= 	array('branch_id'=>$branch_id);
			$res			=	$this->basic_operation_m->getAll('tbl_branch',$whr);
			$branch_name	=	$res->row()->branch_name;
			$pod			= array_unique($pod);

			foreach ($pod as  $pdno) 
			{
				
				$arr 	= explode("|",$pdno);
				$pdno 	= $arr[0];
				$a_w  	= $arr[1];
				$pcs  	= $arr[2];
		
				$data=array('id'=>'',
	            	     'manifiest_id'=>$this->input->post('manifiest_id'),
	        	    	 'pod_no'=>$pdno,
						 'source_branch'=>$branch_name,
	        			 //'destination_branch'=>$this->input->post('branch_name'),
	        			 'user_id' => $user_id,
						 'date_added'=>$this->input->post('datetime'),
						 'lorry_no' => $this->input->post('lorry_no'),
						 'driver_name' => $this->input->post('driver_name'),
						 'coloader' => $this->input->post('coloader'),
						 'forwarder_name' => $this->input->post('forwarder_name'),
						 'forwarder_mode' => $this->input->post('forwarder_mode'),
						 'total_weight' => $a_w,
						 'total_pcs' => $pcs,
						 'contact_no' => $this->input->post('contact_no'),
						);
						
						// echo "<pre>";
						// print_r($data);
						// exit();
			
				$result=$this->basic_operation_m->insert('tbl_domestic_menifiest',$data);
				//echo $this->db->last_query();exit;					
				$whr 					=	array('pod_no'=>$pdno);
				$booking_info			=	$this->basic_operation_m->getAll('tbl_domestic_booking',$whr);
				$menifiest_branches		= 	$booking_info->row()->menifiest_branches;
				$booking_id				= 	$booking_info->row()->booking_id;
			
				$date=$this->input->post('datetime');
				$data1=array('id'=>'',
							 'pod_no'=>$pdno,
							 'status'=>'shifted',
							 'branch_name'=>$branch_name,
							 'booking_id'=>$booking_id,
							 'tracking_date'=>$date,
						 );
				$data2=array('id'=>'',
							 'pod_no'=>$pdno,
							 'status'=>'forworded',
							 'branch_name'=>$branch_name,
							  'booking_id'=>$booking_id,
							 'tracking_date'=>$date,
						 );
			
				
				$result2	=	$this->basic_operation_m->insert('tbl_domestic_tracking',$data2);
				$result1	=	$this->basic_operation_m->insert('tbl_domestic_tracking',$data1);
				
				$pod_no 	= 	$pdno;
			
				
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
				
				$queue_dataa = "update tbl_international_booking set menifiest_branches ='$menifiest_branches',menifiest_recived ='1' where booking_id = '$booking_id'";
				$status	= $this->db->query($queue_dataa);	
			
			}
				
			if ($this->db->affected_rows()>0) 
			{
				$data['message']="Menifiest Added Sucessfully";
				$msg	= 'Menifiest Added successfully';
				$class	= 'alert alert-success alert-dismissible';	
			}
			else
			{
	            $msg	= 'Menifiest not Added successfully';
				$class	= 'alert alert-danger alert-dismissible';
			}

			$this->session->set_flashdata('notify',$msg);
			$this->session->set_flashdata('class',$class);		
			redirect('admin/list-domestic-menifiest');
		}	
	
	}
	
	public function editmenifiest($menifist_id)
	{
			
		$username=$this->session->userdata("userName");
		 $whr = array('username'=>$username);
		 $res=$this->basic_operation_m->getAll('tbl_users',$whr);
		 $branch_id= $res->row()->branch_id;

		 $user_type = $this->session->userdata("userType");
		 $user_id = $this->session->userdata("userId");
		 $where= 	'';
		
		// if($user_type == 5) 
		// {
		//    $where = " AND user_id ='$user_id'";     
		// }
		// else if($user_type == 4) 
		// {
		// 	$where = " AND branch_id ='$branch_id'"; 
		// }
		// else
		// {
		// 	$where = " "; 
		// }		

		 $whr = array('branch_id'=>$branch_id);
		 $res=$this->basic_operation_m->getAll('tbl_branch',$whr);
		 $branch_name= $res->row()->branch_name;
		 $data['branch_name']=$branch_name;
		 $where='';
		
		if($user_type == 5) 
		{
		   $where = " AND user_id ='$user_id'";     
		}
		$resAct=$this->db->query("select *,sum(total_weight) as total_weight,sum(total_pcs) as total_pcs from tbl_domestic_menifiest where tbl_domestic_menifiest.source_branch='$branch_name' $where and id='$menifist_id'  group by `manifiest_id`");
		 if($resAct->num_rows()>0)
		 {
			$data['menifiest_info']=$resAct->row();	            
			$manifiest_id = $data['menifiest_info']->manifiest_id;

		 }
		 
		 $resAct = $this->db->query("SELECT * FROM tbl_domestic_menifiest left join tbl_domestic_booking on tbl_domestic_booking.pod_no = tbl_domestic_menifiest.pod_no where manifiest_id = '$manifiest_id' GROUP BY tbl_domestic_booking.booking_id ORDER BY booking_id DESC"); 
		
		
		if($resAct->num_rows()>0)
		{
			$data['pod']=$resAct->result();	            
		}
		
		 
		 $data['total_weight']		= 0;
		 $data['total_pcs']			= 0;
		 $data['selected_menifist']	= array();
		 $resActt		=	$this->db->query("select * from tbl_domestic_menifiest where tbl_domestic_menifiest.source_branch='$branch_name' $where and manifiest_id='$manifiest_id'");
		 if($resActt->num_rows()>0)
		 {
			$menifiest_infoo	=	$resActt->result();	            
			if(!empty($menifiest_infoo))
			{
				foreach($menifiest_infoo as $key => $values)
				{
					$data['selected_menifist'][]	= $values->pod_no;
					$data['total_weight']			= $data['total_weight'] + $values->total_weight;
					$data['total_pcs']				= $data['total_pcs'] + $values->total_pcs;
				}
			}
		 }
		
			 $resAct=$this->db->query("select * from tbl_branch where branch_id!='$branch_id'");
			 if($resAct->num_rows()>0)
			 {
				$data['branches']=$resAct->result();	            
			 }
		
			 $whr_c =array('company_type'=>'Domestic');
			 $data['courier_company']=$this->basic_operation_m->get_all_result('courier_company',$whr_c);

			//  print_r($data['courier_company'])exit;
			 $data['coloader_list']=$this->basic_operation_m->get_all_result('tbl_coloader',"");
			 $data['mode_list'] = $this->basic_operation_m->get_all_result('transfer_mode',"");
			 // echo "<pre>";
			 // print_r($data);exit;
			 $this->load->view('admin/Menifiest_master/editmenifiest',$data);
	}
		 
	public function updatemenifiest()
	{
		$all_data 		= $this->input->post();
		if(!empty($all_data))
        {
			$manifiest_id = $this->input->post('manifiest_id');
			
			$user_id = $this->session->userdata("userId");
            $username=$this->session->userdata("userName");
			$whr = array('username'=>$username);
			$res=$this->basic_operation_m->getAll('tbl_users',$whr);
		    $branch_id= $res->row()->branch_id;
			$date=date('Y-m-d');
			 
			$whr 				= 	array('branch_id'=>$branch_id);
			$res				=	$this->basic_operation_m->getAll('tbl_branch',$whr);
			$branch_name		= 	$res->row()->branch_name;
			$pod				=	$this->input->post('pod_no');
			
				$pod			= array_unique($pod);
			$resActs=$this->db->query("select * from tbl_domestic_menifiest where manifiest_id='$manifiest_id'");

			 if($resActs->num_rows()>0)
			 {
				$all_menifiest=$resActs->result();	            
			 }
			 
			 $old_pod 			= array();
			 if(!empty($all_menifiest))
			 {
				foreach($all_menifiest as $key => $valuess)
				{
					$old_pod[$valuess->pod_no] = $valuess->pod_no;
				}
			 }
			 
			 
			foreach($pod as  $row1) 
			{
				$arr 	= explode("|",$row1);
				$pdno 	= $arr[0];
				unset($old_pod[$pdno]);
			}
			
			if(!empty($old_pod))
			{
				foreach($old_pod as  $poddd) 
				{
					$whr 					=	array('pod_no'=>$poddd);
					$booking_info			=	$this->basic_operation_m->getAll('tbl_domestic_booking',$whr);
					$menifiest_branches		= 	$booking_info->row()->menifiest_branches;
					$booking_id				= 	$booking_info->row()->booking_id;
					
					if(!empty($menifiest_branches))
					{
						$braches_ids 		= explode(',',$menifiest_branches);
						$braches_ids		= array_unique($braches_ids);
					
						if (($key = array_search($branch_id, $braches_ids)) !== false) 
						{
							unset($braches_ids[$key]);
						}

						$menifiest_branches		= implode(',',$braches_ids);
					}
					
					$queue_dataa		= "update tbl_domestic_booking set menifiest_branches ='$menifiest_branches',menifiest_recived ='0' where booking_id = '$booking_id'";
					$status				= $this->db->query($queue_dataa);
				}
			}
			
			
			
			
			
			$resAct	=	$this->db->query("delete from tbl_domestic_menifiest where manifiest_id='$manifiest_id'");
			foreach ($pod as  $row1)   
			{
				$arr 	= explode("|",$row1);
				$pdno 	= $arr[0];
				$a_w  	= $arr[1];
				$pcs  	= $arr[2];
				$query = $resAct=$this->db->query("delete from tbl_tracking where pod_no='$pdno' and (status = 'shifted' or status = 'forworded')");
				$data=array('id'=>'',
							 'manifiest_id'=>$this->input->post('manifiest_id'),
							 'pod_no'=>$pdno,
							 'source_branch'=>$branch_name,
							// 'destination_branch'=>$this->input->post('branch_name'),
							 'user_id' => $user_id,
							 'date_added'=>$this->input->post('datetime'),
							 'lorry_no' => $this->input->post('lorry_no'),
							 'driver_name' => $this->input->post('driver_name'),
							 'coloader' => $this->input->post('coloader'),
							 'forwarder_name' => $this->input->post('forwarder_name'),
							 'forwarder_mode' => $this->input->post('forwarder_mode'),
							 'total_weight' => $a_w,
							 'actual_weight' => $a_w,
							 'total_pcs' =>  $pcs,
							 'contact_no' => $this->input->post('contact_no'),
							);
							
					$result=$this->basic_operation_m->insert('tbl_domestic_menifiest',$data);
					
					$pod_no = $pdno;
					
					$whr 					=	array('pod_no'=>$pdno);
					$booking_info			=	$this->basic_operation_m->getAll('tbl_domestic_booking',$whr);
					$menifiest_branches		= 	$booking_info->row()->menifiest_branches;
					$booking_id				= 	$booking_info->row()->booking_id;
			
				$date=$this->input->post('datetime');
			
				 $data1=array('id'=>'',
								 'pod_no'=>$pdno,
								 'status'=>'shifted',
								 'branch_name'=>$branch_name,
								  'booking_id'=>$booking_id,
								 'tracking_date'=>$date,
							 );
				 $data2=array('id'=>'',
								 'pod_no'=>$pdno,
								 'status'=>'forworded',
								  'booking_id'=>$booking_id,
								 'branch_name'=>$branch_name,
								 'tracking_date'=>$date,
							 );
				
				
					
				//	echo"<pre>";
				//	print_r($this->db);
			//		exit;
					
					$result1=$this->basic_operation_m->insert('tbl_tracking',$data1);
					$result2=$this->basic_operation_m->insert('tbl_tracking',$data2);
				
					$data = [];
					
					
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
					
					$queue_dataa		= "update tbl_domestic_booking set menifiest_branches ='$menifiest_branches',menifiest_recived ='1' where booking_id = '$booking_id'";
					$status				= $this->db->query($queue_dataa);		
					
					
			
			}
			if ($this->db->affected_rows()>0) {
				$data['message']="Menifiest Added Sucessfully";
			}else{
				$data['message']="Error in Query";
			}
		}	
		redirect('admin/list-domestic-menifiest');		
	}

	public function awbnodata()
	{
		$pod_no 		= trim($_REQUEST['awb_no']);
		$username			= $this->session->userdata("userName");
		$user_type = $this->session->userdata("userType");
	  	$user_id = $this->session->userdata("userId");
         $where = '';
        
		 $whr 				= array('username'=>$username);
		 $res				= $this->basic_operation_m->get_table_row('tbl_users',$whr);
		 $branch_id			= $res->branch_id;
	
		$whr 				= 	array('branch_id'=>$branch_id);
		$res				=	$this->basic_operation_m->get_table_row('tbl_branch',$whr);
		$branch_name		= 	$res->branch_name;
		
		$block_status				 = $this->basic_operation_m->get_query_row("select GROUP_CONCAT(customer_id) AS total from access_control where block_status = 'Menfiest' and current_status ='0'"); 
		if(!empty($block_status))
		{
			$block_statuss	= str_replace(",","','",$block_status->total);
			$where = "customer_id !IN ('$block_statuss') and menifiest_branches not like '%$branch_id%' and menifiest_recived ='0' "; 		
		}
		else
		{
			$where = "and menifiest_branches not like '%$branch_id%' and menifiest_recived ='0' "; 		
		}

		echo "SELECT * FROM tbl_domestic_booking where tbl_domestic_booking.pod_no='$pod_no' and is_delhivery_complete = '0'  $where limit 1";exit;
		$resAct5 = $this->db->query("SELECT * FROM tbl_domestic_booking where tbl_domestic_booking.pod_no='$pod_no' and is_delhivery_complete = '0'  $where limit 1");
		$data = "";
	
       if ($resAct5->num_rows() > 0) 
		 {
			
		 	$booking_row = $resAct5->row_array();
		 	//print_r($booking_row);
			$customer_id =  $booking_row['customer_id'];
			
			
				$pod =  $booking_row['pod_no'];
				$booking_id = $booking_row['booking_id'];
				
				$query_result= $this->db->query("select * from tbl_domestic_weight_details where booking_id = '$booking_id'")->row();
				
				$actual_weight = $query_result->actual_weight;
				//$no_of_pack	   = $booking_row['a_qty'];
				$no_of_pack = $query_result->no_of_pack;
				$podid 		   = "checkbox-".$pod;
				$dataid 	   = 'data-val-'.$booking_id;

				$pod_no 		= $booking_row['pod_no'];
				$data 			.='<tr><td>';
				$data 			.= "<input type='checkbox' class='cb'  name='pod_no[]'  data-tp='{$no_of_pack}' data-tw='{$actual_weight}' value='{$pod_no}|{$actual_weight}|{$no_of_pack}' checked><input type='hidden' name='actual_weight[]' value='".$actual_weight."'/><input type='hidden' name='pcs[]' value='".$no_of_pack."'/></td>";

				// $data .= "<input type='checkbox' class='cb'  name='pod_no[]'  data-tp='{$no_of_pack}' data-tw='{$actual_weight}' value='{$pod_no}' checked>";

				$data .= "<input type='checkbox' class='cb'  name='actual_weight[]' value='".$actual_weight."' checked>";
				$data .= "<input type='checkbox' class='cb'  name='pcs[]' value='".$no_of_pack."' checked>";

				$data .="<input type='hidden' name='rec_pincode' value=".$booking_row['reciever_pincode']."><td>";
				$data .= $booking_row['pod_no'];
				$data .="</td>";
				$data .="<td>";
				$data .= $booking_row['sender_name'];
				$data .="</td>";
				$data .="<td>";
				$data .= $booking_row['contactperson_name'];
				$data .="</td>";			
				$resAct6 = $this->db->query("select * from tbl_city where city_id ='".$booking_row['reciever_city']."'");
				if($resAct6->num_rows() > 0)
				{
					$citydata  		 = $resAct6->row();
					$data		 	.="<td>";
					$data		 	.= $citydata->city_name;
					$data	 		.="</td>";
					
				}

				$data .="<td><input type='hidden' readonly name='forwarder_name' id='forwarder_name'  class='form-control' value='".$booking_row['forworder_name']."'/>";
				$data .= $booking_row['forworder_name'];
				$data .="</td>";
				$data .="<td>";
				$data .= $no_of_pack;
				$data .="</td>";
				$data .= "<td>";			
				$data .= $query_result->actual_weight;
				$data .="</td>";
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

	public function deletemenifiest()
	{
		$data['message']="";
        $last = $this->uri->total_segments();
	    $id	= $this->uri->segment($last);
		if($id!="")
		{
		    $whr =array('id'=>$id);
			$res=$this->basic_operation_m->delete('tbl_domestic_menifiest',$whr);
			
            redirect(base_url().'menifiest');
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
