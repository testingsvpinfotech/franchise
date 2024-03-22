<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_international_menifiest extends CI_Controller {

	function __construct()
	{
		 parent:: __construct();
		 $this->load->model('basic_operation_m');
		 $this->load->model('generate_pod_model');
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

		$resAct=$this->db->query("select *,sum(total_pcs) as total_pcs,sum(total_weight) as total_weight from tbl_international_menifiest where tbl_international_menifiest.source_branch='$branch_name' $where  group by `manifiest_id`");

		//echo $this->db->last_query();exit;
		 if($resAct->num_rows()>0)
		 {
			$data['allpod']=$resAct->result();	            
		 }
		 $this->load->view('admin/Menifiest_master/view_international_menifiest',$data);
	}	
		
	public function add_menifiest()
	{ 
		$result = $this->db->query('select max(inc_id) AS id from tbl_international_menifiest')->row();  
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

		
		$data['pod']= array();
		
		$username		=	$this->session->userdata("userName");
		$whr 			= 	array('username'=>$username);
		$res			=	$this->basic_operation_m->getAll('tbl_users',$whr);
		$branch_id		= 	$res->row()->branch_id;
	
		$whr_c =array('branch_id!='=>$branch_id);
		$data['branches']=$this->basic_operation_m->get_all_result('tbl_branch',$whr_c);
		$whr_c =array('company_type'=>'International');
		$data['courier_company']=$this->basic_operation_m->get_all_result('courier_company',$whr_c);
		$data['coloader_list']=$this->basic_operation_m->get_all_result('tbl_coloader',"");
		$data['mode_list'] = $this->basic_operation_m->get_all_result('transfer_mode',"");
		//print_r($data['coloader_list']);exit;
		$data['mid']=$id;
		$this->load->view('admin/Menifiest_master/add_international_menifiest', $data);	
	}

	public function insert_menifiest()
	{
		$user_type 			= 	$this->session->userdata("userType");
	    $user_id 			= 	$this->session->userdata("userId");
		$all_data 			= $this->input->post();
		//echo "<pre>";print_r($all_data);exit;
		if(!empty($all_data))
        {
			
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

			$result_max = $this->db->query('select max(inc_id) AS id from tbl_international_menifiest')->row();  
			$inc_id= $result_max->id+1;

			foreach ($pod as  $pdno) 
			{				
				$arr 	= explode("|",$pdno);
				//print_r($arr);
				$pdno 	= $arr[0];
				$a_w  	= $arr[1];
				$pcs  	= $arr[2];
				$forwarder_name = $arr[3];
				$dimension 	= $arr[4];
		        $rec_pincode = $arr[5];
		        
				$data=array('id'=>'',
	            	     'manifiest_id'=>$this->input->post('manifiest_id'),
	        	    	 'pod_no'=>$pdno,
						 'source_branch'=>$branch_name,
	        			 'destination_branch'=>$this->input->post('branch_name'),
	        			 'user_id' => $user_id,
						 'date_added'=>$this->input->post('datetime'),
						 'lorry_no' => $this->input->post('lorry_no'),
						 'driver_name' => $this->input->post('driver_name'),
						 'coloader' => $this->input->post('coloader'),
						 //'forwarder_name' => $this->input->post('forwarder_name'),
						 'forwarder_name' =>$forwarder_name,
						 'forwarder_mode' => $this->input->post('forwarder_mode'),
						 'total_weight' => $a_w,
						 'total_pcs' => $pcs,
						 //'dimension'=>$this->input->post('dimension'),
						 'dimension'=>$dimension,
						 'contact_no' => $this->input->post('contact_no'),
						 'rec_pincode'=>$rec_pincode,
						 'inc_id'=>$inc_id,
						);
						
			
				$result=$this->basic_operation_m->insert('tbl_international_menifiest',$data);
				//echo $this->db->last_query();exit;					
				$whr 					=	array('pod_no'=>$pdno);
				$booking_info			=	$this->basic_operation_m->getAll('tbl_international_booking',$whr);
				$menifiest_branches		= 	$booking_info->row()->menifiest_branches;
				$booking_id				= 	$booking_info->row()->booking_id;
			
				$date=$this->input->post('datetime');
				$data1=array('id'=>'',
							 'pod_no'=>$pdno,
							 'status'=>'shifted',
							 'branch_name'=>$branch_name,
							 'forworder_name'=>$this->input->post('forwarder_name'),
							 'booking_id'=>$booking_id,
							 'tracking_date'=>$date,
						 );
				
				$result1	=	$this->basic_operation_m->insert('tbl_international_tracking',$data1);
				
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
			redirect('admin/list-international-menifiest');
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
		$resAct=$this->db->query("select *,sum(total_weight) as total_weight,sum(total_pcs) as total_pcs from tbl_international_menifiest where tbl_international_menifiest.source_branch='$branch_name' $where and id='$menifist_id'  group by `manifiest_id` ORDER BY id ASC");
		 if($resAct->num_rows()>0)
		 {
			$data['menifiest_info']=$resAct->row();	            
			$manifiest_id = $data['menifiest_info']->manifiest_id;

		 }
		 
		 $resAct = $this->db->query("SELECT * FROM tbl_international_menifiest left join tbl_international_booking on tbl_international_booking.pod_no = tbl_international_menifiest.pod_no where manifiest_id = '$manifiest_id' GROUP BY tbl_international_booking.booking_id ORDER BY booking_id DESC"); 
		
		
		if($resAct->num_rows()>0)
		{
			$data['pod']=$resAct->result();	            
		}
		//echo "<pre>";print_r($data['pod']);exit;
		 
		 $data['total_weight']		= 0;
		 $data['total_pcs']			= 0;
		 $data['selected_menifist']	= array();
		 $resActt=	$this->db->query("select * from tbl_international_menifiest where tbl_international_menifiest.source_branch='$branch_name' $where and manifiest_id='$manifiest_id'");
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
		
			 $whr_c =array('company_type'=>'International');
			 $data['courier_company']=$this->basic_operation_m->get_all_result('courier_company',$whr_c);

			//  print_r($data['courier_company'])exit;
			 $data['coloader_list']=$this->basic_operation_m->get_all_result('tbl_coloader',"");
			 $data['mode_list'] = $this->basic_operation_m->get_all_result('transfer_mode',"");
			 // echo "<pre>";
			 // print_r($data);exit;
			 $this->load->view('admin/Menifiest_master/edit_international_menifiest',$data);
	}
		 
	public function updatemenifiest()
	{
		$all_data= $this->input->post();
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
			$resActs=$this->db->query("select * from tbl_international_menifiest where manifiest_id='$manifiest_id'");

			 if($resActs->num_rows()>0)
			 {
				$all_menifiest=$resActs->result();	            
			 }
			 
			 $old_pod= array();
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
			 		$booking_info			=	$this->basic_operation_m->getAll('tbl_international_booking',$whr);
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
					
			 		$queue_dataa = "update tbl_international_booking set menifiest_branches ='$menifiest_branches',menifiest_recived ='0' where booking_id = '$booking_id'";
			 		$status= $this->db->query($queue_dataa);
					
					$this->db->query("delete from tbl_international_menifiest where pod_no='$poddd'");
			 	}
			 }
			//$resAct	=	$this->db->query("delete from tbl_international_menifiest where manifiest_id='$manifiest_id'");
			foreach ($pod as  $row1)   
			{				
				$arr 	= explode("|",$row1);
				$pdno 	= $arr[0];
				$a_w  	= $arr[1];
				$pcs  	= $arr[2];
				$forwarder_name = $arr[3];
				$dimension 	= $arr[4];
				$rec_pincode = $arr[5];

				$data=array( 'manifiest_id'=>$this->input->post('manifiest_id'),
								 'pod_no'=>$pdno,
								 'source_branch'=>$branch_name,
								 'destination_branch'=>$this->input->post('branch_name'),
								 'user_id' => $user_id,
								 'date_added'=>$this->input->post('datetime'),
								 'lorry_no' => $this->input->post('lorry_no'),
								 'driver_name' => $this->input->post('driver_name'),
								 'coloader' => $this->input->post('coloader'),
								 'forwarder_name' =>$forwarder_name,
								 'forwarder_mode' => $this->input->post('forwarder_mode'),
								 'total_weight' => $a_w,
								 'actual_weight' => $a_w,
								 'total_pcs' =>  $pcs,
								 'contact_no' => $this->input->post('contact_no'),
								 'dimension'=>$dimension,
								 'rec_pincode'=>$rec_pincode,
								 'inc_id'=>$this->input->post('inc_id'),
								);
								
					// 			echo "<pre>";
					// 			print_r($data);
					// 			exit;
						$whr = array('pod_no'=>$pdno,'manifiest_id'=>$manifiest_id);
						$exit_pod = $this->basic_operation_m->get_table_row('tbl_international_menifiest',$whr);
						if(isset($exit_pod))
						{
							$result=$this->basic_operation_m->update('tbl_international_menifiest',$data,$whr);
						}else{
							$result=$this->basic_operation_m->insert('tbl_international_menifiest',$data);
						}

					$pod_no = $pdno;
					
					$whr 					=	array('pod_no'=>$pdno);
					$booking_info			=	$this->basic_operation_m->getAll('tbl_international_booking',$whr);
					$menifiest_branches		= 	$booking_info->row()->menifiest_branches;
					$booking_id				= 	$booking_info->row()->booking_id;
			
				$date=$this->input->post('datetime');
			
				 // $data1=array('id'=>'',
					// 			 'pod_no'=>$pdno,
					// 			 'status'=>'shifted',
					// 			 'branch_name'=>$branch_name,
					// 			  'booking_id'=>$booking_id,
					// 			 'tracking_date'=>$date,
					// 		 );
				 // $data2=array('id'=>'',
					// 			 'pod_no'=>$pdno,
					// 			 'status'=>'forworded',
					// 			  'booking_id'=>$booking_id,
					// 			 'branch_name'=>$branch_name,
					// 			 'tracking_date'=>$date,
					// 		 );
					
					// $result1=$this->basic_operation_m->insert('tbl_tracking',$data1);
					// $result2=$this->basic_operation_m->insert('tbl_tracking',$data2);				
					// $data = [];					
					
					if(!empty($menifiest_branches))
					{
						$braches_ids 		= explode(',',$menifiest_branches);
						$braches_ids[]		= $branch_id;
						$braches_ids		= array_unique($braches_ids);
						$menifiest_branches		= implode(',',$braches_ids);
					}
					else
					{
						$menifiest_branches = $branch_id;
					}
					
					$queue_dataa= "update tbl_international_booking set menifiest_branches ='$menifiest_branches',menifiest_recived ='1' where booking_id = '$booking_id'";
					$status = $this->db->query($queue_dataa);
			
			}
			//exit;
			if ($this->db->affected_rows()>0) {
				$data['message']="Menifiest Added Sucessfully";
			}else{
				$data['message']="Error in Query";
			}
		}	
		redirect('admin/list-international-menifiest');		
	}

	public function awbnodata()
	{
		$pod_no = trim($_REQUEST['awb_no']);
		$forwarderName 		= trim($_REQUEST['forwarderName']);
		$mode_dispatch	 	= trim($_REQUEST['forwarder_mode']);
		$username 	= $this->session->userdata("userName");
		$user_type 	= $this->session->userdata("userType");
	  	$user_id 	= $this->session->userdata("userId");
        $where 		= '';
        
		$whr 				= array('username'=>$username);
		$res				= $this->basic_operation_m->get_table_row('tbl_users',$whr);
		$branch_id			= $res->branch_id;
	
		$whr 				= 	array('branch_id'=>$branch_id);
		$res				=	$this->basic_operation_m->get_table_row('tbl_branch',$whr);
		$branch_name		= 	$res->branch_name;
		
		$where = "and menifiest_branches not like '%$branch_id%' and menifiest_recived ='0'  and forworder_name = '$forwarderName' and mode_dispatch = '$mode_dispatch'"; 	

		$resAct5 = $this->db->query("SELECT * FROM tbl_international_booking where pod_no LIKE '%$pod_no%' and is_delhivery_complete = '0'  $where limit 1");
		
		if ($resAct5->num_rows() == 0)
		{
			$resAct5 = $this->db->query("SELECT * FROM tbl_international_booking where forwording_no='$pod_no' and is_delhivery_complete = '0'  $where limit 1");	
		}
		
		//echo $this->db->last_query();
		
		$data = "";
	
       if ($resAct5->num_rows() > 0) 
		 {
			
		 	$booking_row = $resAct5->row_array();
		 	$dimension_arr =array();
		 	//print_r($booking_row);
			$pod =  $booking_row['pod_no'];
			$booking_id = $booking_row['booking_id'];
			
			$query_result= $this->db->query("select * from tbl_international_weight_details where booking_id = '$booking_id'")->row();

		if($query_result->length_detail!="") {

			  $length_detail = json_decode($query_result->length_detail);
              $breath_detail = json_decode($query_result->breath_detail);
              $height_detail = json_decode($query_result->height_detail);              

              for($de=0;$de<count($length_detail);$de++){
              	if($length_detail[$de]!="")
              	{
             	 	$dimension_arr[]= $length_detail[$de]."*".$breath_detail[$de]."*".$height_detail[$de];
             	 	$dimension = implode(",",$dimension_arr);
              	}else{
              		$dimension="";
              	}
              }
          }else{
          		$dimension="";
          }
          
          //echo "<pre>";print_r($dimension_arr);
          
          //echo $dimension ='"'. implode(",",$dimension_arr).'"';
          
         // exit();
			
			$actual_weight = round($query_result->actual_weight,2);
			//$no_of_pack	   = $booking_row['a_qty'];
			$no_of_pack = $query_result->no_of_pack;
			$forworder_name = $booking_row['forworder_name'];
			
			
			$podid 		   = "checkbox-".$pod;
			$dataid 	   = 'data-val-'.$booking_id;

			$pod_no = $booking_row['pod_no'];
			$rec_pincode = $booking_row['reciever_zipcode'];
			$data .='<tr><td>';
			$data .= "<input type='checkbox' class='cb'  name='pod_no[]'  data-tp='{$no_of_pack}' data-tw='{$actual_weight}' value='{$pod_no}|{$actual_weight}|{$no_of_pack}|{$forworder_name}|{$dimension}|{$rec_pincode}' checked><input type='hidden' name='actual_weight[]' value='".$actual_weight."'/><input type='hidden' name='pcs[]' value='".$no_of_pack."'/></td>";

			// $data .= "<input type='checkbox' class='cb'  name='pod_no[]'  data-tp='{$no_of_pack}' data-tw='{$actual_weight}' value='{$pod_no}' checked>";

			$data .= "<input type='checkbox' class='cb'  name='actual_weight[]' value='".$actual_weight."' checked>";
			$data .= "<input type='checkbox' class='cb'  name='pcs[]' value='".$no_of_pack."' checked>";

			$data .="<td>";
			$data .= $booking_row['pod_no'];
			$data .="</td>";
			$data .="<td>";
			$data .= $booking_row['reciever_name'];
			$data .="</td>";
			$data .="<td>";
			$data .= $booking_row['forwording_no'];
			$data .="</td>";				
			$resAct6 = $this->db->query("select * from zone_master where z_id ='".$booking_row['reciever_country_id']."'");
			if($resAct6->num_rows() > 0)
			{
				$citydata  		 = $resAct6->row();
				$data		 	.="<td>";
				$data		 	.= $citydata->country_name;
				$data	 		.="</td>";
				
			}

			$data .="<td><input type='hidden' readonly name='forwarder_name' id='forwarder_name'  class='form-control' value='".$booking_row['forworder_name']."'/>
			<input type='hidden' readonly name='branch_name' id='branch_name'  class='form-control' value='".$branch_name."'/>";
			$data .= $booking_row['forworder_name'];
			$data .="</td>";
			$data .="<td>";
			$data .= $no_of_pack;
			$data .="</td>";
			$data .= "<td>";			
			$data .= round($query_result->actual_weight,2);
			$data .="</td>";
			$data .= "<td><input type='hidden' readonly name='dimension' id='dimension'  class='form-control' value=".$dimension."/>";			
			$data .= $dimension;
			$data .="</td>";
			$data .= "</tr>";


			
         }
        echo  $data ;
        
	}
    public function getPODDetails()
    {

	   $pod_no=$this->input->post('podno');

		$whr =array('pod_no'=>$pod_no);
		$res=$this->basic_operation_m->selectRecord('tbl_international_booking',$whr);			
		$result = $res->row();

		$whr1 =array('booking_id'=>$result->booking_id);
		$res1=$this->basic_operation_m->selectRecord('tbl_internaional_weight_details',$whr1);	
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
			$res=$this->basic_operation_m->delete('tbl_international_menifiest',$whr);
			
            redirect('admin/list-international-menifiest');
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



	public function view_international_delivery_status()
	{
		
		$username = $this->session->userdata("userName");
		$user_type = $this->session->userdata("userType");
		$whr = array('username' => $username);
		$res = $this->basic_operation_m->getAll('tbl_users', $whr);
		$branch_id = $res->row()->branch_id;
		
		$whr = array('branch_id'=>$branch_id);
		$res=$this->basic_operation_m->getAll('tbl_branch',$whr);
		$branch_name= $res->row()->branch_name;
				
		if($user_type != 1)
		{
			
			
			$resAct2 		= $this->basic_operation_m->get_query_result("select pod_no from tbl_international_menifiest where destination_branch='$branch_name' group by pod_no");
			
			if(!empty($resAct2))
			{
				$n_Array = array();

				foreach($resAct2 as $key =>  $values)
				{
					$n_Array[$values->pod_no]		= $values->pod_no;
				}
				$pods = implode("','",$n_Array);
				
			}
			else
			{
				$pods = '';
			}
			
			
			$filterCond		= "(tbl_international_booking.branch_id = '$branch_id' or tbl_international_booking.menifiest_branches = '$branch_id') or pod_no IN('$pods') and is_delhivery_complete = '0'";
		}
		else
		{
			$filterCond		= "is_delhivery_complete = '0'";
		}
		
	    $all_data = $this->input->post();		
		if($all_data)
		{	
			$filter_value = 	$_POST['filter_value'];
			
			foreach($all_data as $ke=> $vall)
			{
				if($ke == 'filter' && !empty($vall))
				{
					if($vall == 'pod_no')
					{
						$filterCond .= " AND tbl_international_booking.pod_no = '$filter_value'";
					}
					if($vall == 'forwording_no')
					{
						$filterCond .= " AND tbl_international_booking.forwording_no = '$filter_value'";
					}
					if($vall == 'sender')
					{
						$filterCond .= " AND tbl_international_booking.sender_name LIKE '%$filter_value%'";
					}
					if($vall == 'receiver')
					{
						$filterCond .= " AND tbl_international_booking.reciever_name LIKE '%$filter_value%'";
					}
					if($vall == 'receiver_city')
					{
						$city_info					 =  $this->basic_operation_m->get_table_row('city', "city='$filter_value'");
						$filterCond 				.= " AND tbl_international_booking.reciever_city = '$city_info->id'";
					}
					/*if($vall == 'mode')
					{
						$transfer_mode_info			=  $this->basic_operation_m->get_table_row('transfer_mode', "mode_name='$filter_value'");
						if(!empty($transfer_mode_info))
						{
							$filterCond .= " AND tbl_international_booking.mode_dispatch = '$transfer_mode_info->transfer_mode_id'";
						}							 
						
					} */
					if($vall == 'origin')
					{
						$city_info					 =  $this->basic_operation_m->get_table_row('city', "city='$filter_value'");
						$filterCond 				.= " AND tbl_international_booking.sender_city = '$city_info->id'";
					}
					if($vall == 'destination')
					{
						$city_info					 =  $this->basic_operation_m->get_table_row('city', "city='$filter_value'");
						$filterCond 				.= " AND tbl_international_booking.reciever_city = '$city_info->id'";
					}
					
				}
				elseif($ke == 'user_id' && !empty($vall))
				{
					$filterCond .= " AND tbl_international_booking.customer_id = '$vall'";
				}
				elseif($ke == 'from_date' && !empty($vall))
				{
					$filterCond .= " AND tbl_international_booking.booking_date >= '$vall'";
				}
				elseif($ke == 'to_date' && !empty($vall))
				{
					$filterCond .= " AND tbl_international_booking.booking_date <= '$vall'";
				}
		  	}
			 $data['international_booking'] = $this->generate_pod_model->get_international_tracking_data($filterCond);
		    // $data['domestic_booking'] = $this->generate_pod_model->get_domestic_tracking_data($filterCond);
		}
		else
		{
			
			
		   $data['international_booking'] = $this->generate_pod_model->get_international_tracking_data($filterCond);
		    // $data['domestic_booking'] = $this->generate_pod_model->get_domestic_tracking_data($filterCond);
			
		    
		}
		$data['all_status']= $this->basic_operation_m->get_all_result("tbl_status","");
		$data['courier_company']= $this->basic_operation_m->get_all_result("courier_company","");
	    $data['customers_list']= $this->basic_operation_m->get_all_result("tbl_customers","");
	    $this->load->view('admin/Menifiest_master/change_international_delivery_status',$data);
	}



	public function change_international_delivery_status()
	{
	 	$all_data= $this->input->post();
	  	if($all_data!=""){
            $tracking_date = date("Y-m-d H:i:s",strtotime($this->input->post('tracking_date')));
            $selected_dockets = $this->input->post('selected_dockets');
            $company_type = $this->input->post('company_type');
            $status = $this->input->post('status');
        	$comment = $this->input->post('comment');
        	$remarks = $this->input->post('remarks');
        	$branch_name = $this->input->post('branch_name');



        	// print_r($_REQUEST);exit();
        
	        $is_delhivery_complete = 0;
		
			
			//echo "<pre>";print_r($selected_dockets);
			
			for($doc=0;$doc<count($selected_dockets);$doc++)
			{
				$username=$this->session->userdata("userName");
				$whr = array('username'=>$username);
				$res=$this->basic_operation_m->getAll('tbl_users',$whr);
			    $branch_id= $res->row()->branch_id;
				$date=date('y-m-d');
				 
				$whr = array('branch_id'=>$branch_id);
				$res=$this->basic_operation_m->getAll('tbl_branch',$whr);
				// $branch_name= $res->row()->branch_name;
				$pod_no=$this->input->post('pod_no');
				$status=$this->input->post('status');
				$comment = $this->input->post('comment');
				$date=date('Y-m-d H:i:s');
				
				if($company_type[$doc]=="Domestic")
				{
				    if($status == 'Delivered')
	    			{
	    				$is_delhivery_complete = 1;
	    				$where = array('booking_id' => $selected_dockets[$doc]);
	    				$updateData = [
	    					'is_delhivery_complete' => $is_delhivery_complete,
	    				];
	    				$this->db->update('tbl_domestic_booking', $updateData, $where);
	    			}
				    
				    $this->db->select('pod_no, booking_id, forworder_name, forwording_no');
	    			$this->db->from('tbl_domestic_booking');
	    			$this->db->where('booking_id', $selected_dockets[$doc]);
	    			$this->db->order_by('booking_id', 'DESC');
	    			$result = $this->db->get();
	    			$resultData = $result->row();
	    
	    		    $pod_no = $resultData->pod_no;
	    			$forworder_name = $resultData->forworder_name;
	    			$forwording_no = $resultData->forwording_no;
				
				    $data = [
				        'pod_no'=>$pod_no,
				        'branch_name'=>$branch_name,
				        'booking_id'=>$selected_dockets[$doc],
				        'forworder_name'=>$forworder_name,
				        'forwording_no'=>$forwording_no,
						'tracking_date' => $tracking_date,
						'status' => $status,
						'comment' => $comment,
						'remarks' => $remarks,
						'is_delhivery_complete' => $is_delhivery_complete,
					];
					// echo "<pre>";
					// print_r($data);
					// exit;
					$this->db->insert('tbl_domestic_tracking', $data);
				}else if($company_type[$doc]=="International")
				{
				    if($status == 'Delivered')
	    			{
	    				$is_delhivery_complete = 1;
	    				$where = array('booking_id' => $selected_dockets[$doc]);
	    				$updateData = [
	    					'is_delhivery_complete' => $is_delhivery_complete,
	    				];
	    				$this->db->update('tbl_international_booking', $updateData, $where);
	    			}
				
				    $this->db->select('pod_no, booking_id, forworder_name, forwording_no');
	    			$this->db->from('tbl_international_booking');
	    			$this->db->where('booking_id', $selected_dockets[$doc]);
	    			$this->db->order_by('booking_id', 'DESC');
	    			$result = $this->db->get();
	    			$resultData = $result->row();
	    
	    		    $pod_no = $resultData->pod_no;
	    			$forworder_name = $resultData->forworder_name;
	    			$forwording_no = $resultData->forwording_no;
	    			
	    			$data = [
	    			        'pod_no'=>$pod_no,
	    			        'branch_name'=>$branch_name,
	    			        'booking_id'=>$selected_dockets[$doc],
	    			        'forworder_name'=>$forworder_name,
	    			        'forwording_no'=>$forwording_no,
	    					'tracking_date' => $tracking_date,
	    					'status' => $status,
	    					'comment' => $comment,
	    					'remarks' => $remarks,
	    					'is_delhivery_complete' => $is_delhivery_complete,
	    				];
	    				
	    				$this->db->insert('tbl_international_tracking', $data);
				}
				
				
			}
			
	  	}
	    redirect("admin/view-international-delivery-status");
	}



}
