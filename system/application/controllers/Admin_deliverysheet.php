<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_deliverysheet extends CI_Controller {

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
			 $whr = array('username'=>$username);
			 $res=$this->basic_operation_m->getAll('tbl_users',$whr);
			 $branch_id= $res->row()->branch_id;
		
			 $whr = array('branch_id'=>$branch_id);
			 $res=$this->basic_operation_m->getAll('tbl_branch',$whr);
			 $branch_name= $res->row()->branch_name;
			  $data= array();	
			
			//$resAct1=$this->basic_operation_m->getAll('tbl_domestic_deliverysheet',$whr);
			
			$resAct1=$this->db->query("SELECT *, COUNT(deliverysheet_id) AS total_count
FROM tbl_domestic_deliverysheet
LEFT JOIN tbl_branch ON tbl_branch.branch_id = tbl_domestic_deliverysheet.branch_id
LEFT JOIN tbl_users ON tbl_users.username = tbl_domestic_deliverysheet.deliveryboy_name
GROUP BY deliverysheet_id");
			
			 if($resAct1->num_rows()>0)
			 {
			 	$data['allpod']=$resAct1->result_array();	            
	         }
	        
			 $this->load->view('admin/deliverysheet/view_deliverysheet',$data);
				
    	}	
		public function adddelivery()
		{ 
			$result1 = $this->db->query('select max(id) AS id from tbl_domestic_deliverysheet')->row();  
			$id= $result1->id+1;
			if(strlen($id)==2)
			{
			   $id='D00'.$id;
			}else if(strlen($id)==3)
			{
			   $id='D0'.$id;
			}else if(strlen($id)==1)
			{
			   $id='D000'.$id;
			}else if(strlen($id)==4)
			{
			   $id='D'.$id;
			}
			$data['message']="";
		 
			 $username=$this->session->userdata("userName");
		     $whr = array('username'=>$username);
			 $res=$this->basic_operation_m->getAll('tbl_users',$whr);
			 $branch_id= $res->row()->branch_id;
			 
			 $whr = array('branch_id'=>$branch_id);
			 $res=$this->basic_operation_m->getAll('tbl_branch',$whr);
			 $branch_name= $res->row()->branch_name;
			 $data= array();

			 $resAct=$this->db->query("select * from  tbl_users where tbl_users.branch_id='$branch_id' and user_type='2 '");
			
			//echo $this->db->last_query();
			if($resAct->num_rows()>0)
			 {
			 	$data['users']=$resAct->result();	            
	         }

	         //echo "<pre>"; print_r($data);exit;
		
			 $resAct = $this->db->query("select * from  tbl_inword where branch_code='$branch_name' and status='recieved'");
		
			if($resAct->num_rows()>0)
			 {
				$data['pod']=$resAct->result();	            
			 }
			
			$data['did']=$id;
			$this->load->view('admin/deliverysheet/addeliverysheet', $data);
		}
	 
 public function getPODDetails()
    {

	   $pod_no=$this->input->post('podno');

		$whr =array('pod_no'=>$pod_no);
		$res=$this->basic_operation_m->selectRecord('tbl_booking',$whr);			
		$result = $res->row();

		$whr1 =array('booking_id'=>$result->booking_id);
		$res1=$this->basic_operation_m->selectRecord('tbl_weight_details',$whr1);	
		$result1 = $res1->row();

		$str= $result->reciever_name."-".$result->reciever_address."-".$result1->no_of_pack."-".$result1->actual_weight;

		echo $str;
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
			$where = "and customer_id not IN ('$block_statuss') and menifiest_branches not like '%$branch_id%' and menifiest_recived ='0' "; 					
		}
		else
		{
			$where = "and menifiest_branches not like '%$branch_id%' and menifiest_recived ='0' "; 		
		}
		
		
	
		$resAct5 = $this->db->query("SELECT * FROM tbl_domestic_booking where tbl_domestic_booking.pod_no='$pod_no' and is_delhivery_complete = '0'  $where limit 1");
		$data = "";
	
       if ($resAct5->num_rows() > 0) 
		 {
			
		 	$booking_row = $resAct5->row_array();
		 	//print_r($booking_row);
			$pod =  $booking_row['pod_no'];
			$booking_id = $booking_row['booking_id'];
			$customer_id =  $booking_row['customer_id'];
			
			
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

			$data .="<input type='hidden' name='rec_pincode' value=".$booking_row['reciever_pincode']."><td>";
			$data .= $booking_row['pod_no'];
			$data .="</td>";
			$data .="<td>";
			$data .= $booking_row['sender_name'];
			$data .="</td>";
			$data .="<td>";
			$data .= $booking_row['contactperson_name'];
			$data .="</td>";			
			$resAct6 = $this->db->query("select * from city where id ='".$booking_row['reciever_city']."'");
			if($resAct6->num_rows() > 0)
			{
				$citydata  		 = $resAct6->row();
				$data		 	.="<td>";
				$data		 	.= $citydata->city;
				$data	 		.="</td>";
				
			}

			$data .="<td>";
			$data .= $booking_row['forworder_name'];
			$data .="</td>";
			$data .="<td>";
			$data .= $query_result->actual_weight;
			$data .="</td>";
			$data .= "<td>";
			$data .= $no_of_pack;
			$data .="</td>";
			$data .= "</tr>";
        
		 }
        echo  $data ;
        
	}

	public function insert_deliverysheet()
	{
		$all_data 		= $this->input->post();

		 if(!empty($all_data))
          {
			 $username=$this->session->userdata("userName");
			 $usernamee=$this->input->post("username");
		     $whr = array('username'=>$username);
			 $res=$this->basic_operation_m->getAll('tbl_users',$whr);
			 $branch_id= $res->row()->branch_id;
			
			 
			$whr 			= 	array('branch_id'=>$branch_id);
			$res			=	$this->basic_operation_m->getAll('tbl_branch',$whr);
			$branch_name	=	$res->row()->branch_name;

			// print_r($data);
			$date=date("Y-m-d",strtotime($this->input->post('datetime') ) );
			// if(isset($_FILES['csv_zip']['name']) && !empty($_FILES['csv_zip']['name']))
			// {			
			// 	$file = fopen($_FILES['csv_zip']['tmp_name'],"r");
			
			// 	$cnt = 0;
			// 	$pcs = 0;
			// 	$a_w = 0;
				
			// 	while(!feof($file))
			// 	{
			// 		$data 				= fgetcsv($file);
			// 		if(!empty($data[0]))
			// 		{
			// 			$pod_no			= $data[0];
						
			// 			$resAct			= $this->db->query("select booking_id from tbl_booking where  pod_no='$pod_no'");
			// 			$info			= $resAct->row();
			// 			if(!empty($info))
			// 			{
			// 				$booking_id		= 	$info->booking_id;
			// 				$resActt		=	$this->db->query("select no_of_pack,chargable_weight from tbl_weight_details where  booking_id='$booking_id'");
			// 				$infoo			=	$resActt->row();
			// 				$pod[$pod_no]	= 	$pod_no;
			// 			}
			// 		}
					
			// 	}
			// }
			// else
			// {
				
			// 	$pod=$this->input->post('pod_no');
			// }

			$pod=$this->input->post('pod_no');
	
			$pod  = array_unique($pod);
			
			foreach ($pod as  $row) 
			{
				$rows = explode('|',$row);
				$pod_no = $rows[0];
				$data=array(
								'deliverysheet_id'=>$this->input->post('deliverysheet_id'),
								'deliveryboy_name'=>$usernamee,
								'branch_id'=>$branch_id,
								'pod_no'=>$pod_no,
								'status'=>'recieved',
								'delivery_date'=>$date,
							);
				$result=$this->basic_operation_m->insert('tbl_domestic_deliverysheet',$data);				
		
				$booking_id		=	$this->basic_operation_m->get_table_row('tbl_domestic_booking',"pod_no = '$pod_no'");
				$data1=array('id'=>'',
										 'booking_id'=>$booking_id->booking_id,
										 'pod_no'=>$pod_no,
										 'status'=>'Out For Delivery',
										 'branch_name'=>$branch_name,
										 'tracking_date'=>$this->input->post('datetime'),
											  );
							
				$result1	=	$this->basic_operation_m->insert('tbl_domestic_tracking',$data1);
				
			}
			redirect('admin/list-deilverysheet');
		}
	}
	
	public function deliverysheet_detail($id)
	 { 
		
	 $data= array();
	 
		
		
	 //$deliverysheet_id=$this->input->post('deliverysheet_id');
	$resAct=$this->db->query("select * from tbl_domestic_booking,tbl_domestic_deliverysheet
					where tbl_domestic_booking.pod_no=tbl_domestic_deliverysheet.pod_no and tbl_domestic_deliverysheet.deliverysheet_id='$id'");	

	 $data['info']=$resAct->result_array();
 
	
	$this->load->view('admin/deliverysheet/view_deliverysheet_detail', $data);
	}
	
	public function deliverysheet($deliverysheet_id='')
	{
		
		
		$data= array();
		$data['message']="";
		$data['deliverysheet_id']=$deliverysheet_id;
         // Load library
	    $this->load->library('zend');
		// Load in folder Zend
		$this->zend->load('Zend/Barcode');
        
		$data['company_setting']			=  $this->basic_operation_m->get_table_row('tbl_company',"id='1'");
	
		if(!empty($deliverysheet_id))
		{
			
			$resAct=$this->db->query("select *,tbl_domestic_deliverysheet.delivery_date from  tbl_domestic_deliverysheet,tbl_domestic_booking,tbl_users where
			tbl_domestic_deliverysheet.pod_no=tbl_domestic_booking.pod_no and
			tbl_domestic_deliverysheet.deliveryboy_name=tbl_users.username and
			deliverysheet_id='$deliverysheet_id'");
			
			 $data['deliverysheet']=$resAct->result_array();
			$data['branch_address']		=  $this->basic_operation_m->get_table_row('tbl_branch',"branch_id = ".$data['deliverysheet'][0]['branch_id']);
			$data['all_status']			=  $this->basic_operation_m->get_table_result('tbl_status',"");
			
		}
		elseif(isset($_POST['submit']))
          {
			$deliverysheet_id=$this->input->post('deliverysheet_id');
			
			$resAct=$this->db->query("select *,tbl_domestic_deliverysheet.delivery_date from  tbl_domestic_deliverysheet,tbl_domestic_booking,tbl_users where
			tbl_domestic_deliverysheet.pod_no=tbl_domestic_booking.pod_no and
			tbl_domestic_deliverysheet.deliveryboy_name=tbl_users.username and
			deliverysheet_id='$deliverysheet_id'");
			
			 $data['deliverysheet']		=  $resAct->result_array();
			$data['branch_address']		=  $this->basic_operation_m->get_table_row('tbl_branch',"branch_id = ".$data['deliverysheet'][0]['branch_id']);
			$data['all_status']			=  $this->basic_operation_m->get_table_result('tbl_status',"");
		  }	
		  
		$this->load->view('admin/deliverysheet/printdelivery',$data);
	}
	
	public function print_deliverysheet($deliverysheet_id='')
	{
		
		$data= array();
		$data['message']="";
         // Load library
	    $this->load->library('zend');
		// Load in folder Zend
		$this->zend->load('Zend/Barcode');
		$data['company_setting']			=  $this->basic_operation_m->get_table_row('tbl_company',"id='1'");

		$user_id = $_SESSION['userId'];

		// $resAct2=$this->db->query("select * from  tbl_branch,tbl_users,city where
		// 	tbl_branch.branch_id=tbl_users.branch_id and
		// 	city.id=tbl_branch.city and
		// 	tbl_users.user_id='$userId'");


		$resAct2=$this->db->query("select * from tbl_branch left JOIN city ON city.id=tbl_branch.city JOIN tbl_users on tbl_users.branch_id=tbl_branch. branch_id where tbl_users.user_id=".$user_id);
		// echo $this->db->last_query();exit();
			
	 	$data['branchAddress']=$resAct2->result_array();
        // echo "<pre>";
        // print_r($data['branchAddress']);exit();
		if(!empty($deliverysheet_id))
		{
			
			
			$resAct=$this->db->query("select *,tbl_domestic_deliverysheet.delivery_date from  tbl_domestic_deliverysheet,tbl_domestic_booking,tbl_users,city where
			tbl_domestic_deliverysheet.pod_no=tbl_domestic_booking.pod_no and
			city.id=reciever_city and
			tbl_domestic_deliverysheet.deliveryboy_name=tbl_users.username and
			deliverysheet_id='$deliverysheet_id'");
			
			 $data['deliverysheet']=$resAct->result_array();
			$data['branch_address']		=  $this->basic_operation_m->get_table_row('tbl_branch',"branch_id = ".$data['deliverysheet'][0]['branch_id']);
			$data['all_status']			=  $this->basic_operation_m->get_table_result('tbl_status',"");
		}
		elseif(isset($_POST['submit']))
          {
			$deliverysheet_id=$this->input->post('deliverysheet_id');
			
			$resAct=$this->db->query("select *,tbl_domestic_deliverysheet.delivery_date from  tbl_deliverysheet,tbl_booking,tbl_users,city where
			tbl_deliverysheet.pod_no=tbl_booking.pod_no and
			city.id=reciever_city and
			tbl_deliverysheet.deliveryboy_name=tbl_users.username and
			deliverysheet_id='$deliverysheet_id'");
			
			 $data['deliverysheet']		=  $resAct->result_array();
			$data['branch_address']		=  $this->basic_operation_m->get_table_row('tbl_branch',"branch_id = ".$data['deliverysheet'][0]['branch_id']);
			$data['all_status']			=  $this->basic_operation_m->get_table_result('tbl_status',"");
		  }	
		  
		$this->load->view('admin/deliverysheet/printprintdelivery',$data);
	}
}

