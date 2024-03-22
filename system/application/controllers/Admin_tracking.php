<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_tracking extends CI_Controller {

	function __construct()
	{
		 parent:: __construct();
		 $this->load->model('basic_operation_m');
		// $this->load->library('SpotonTracking');
		 if($this->session->userdata('userId') == '')
		{
			redirect('admin');
		}
	}
	
	public function index()
	{		
		$data= array();		 
		$data['message']="";
        
         if(isset($_POST['submit']))
          {
         	$pod_no=$this->input->post('pod_no');
			
			$resAct=$this->db->query("select * from tbl_booking,tbl_city where tbl_booking.sender_city=tbl_city.city_id and 
			pod_no='$pod_no'");
			 $data['info']=$resAct->row();
			 
			 $resAct=$this->db->query("select * from tbl_booking,tbl_city where tbl_booking.reciever_city=tbl_city.city_id and 
			pod_no='$pod_no'");
			 $data['info1']=$resAct->row();
			
			//$pod_no=$this->input->post('pod_no');
			$reAct=$this->db->query("select * from tbl_tracking where pod_no='$pod_no'");
          
			$data['pod']=$reAct->result_array();
			//print_r($data['pod']);
			
			foreach($data['pod'] as $podData)
			{
				if($podData['status'] == 'DELIVERED')
				{
					$lrNum = $podData['forwording_no'];
					$podData = $this->deliverypod($lrNum);
					$data['delivery_pod'] = json_decode($podData, true);
				}
			}
			
			$reAct=$this->db->query("select * from tbl_upload_pod where pod_no='$pod_no'");
			
			$data['podimg']=$reAct->row();
			
		  }	
				 
			$this->load->view('viewtracking',$data);
		
	}
	public function deliverypod($lrNum){
		
		$curl = curl_init();
		
		$apiUrl = base_url()."generatepod/get_delebrypod/".$lrNum;

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $apiUrl,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
			"Cookie: ci_session=hjmb3p0tgrvn9plsr3a6ji3qpdkpd15d"
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}
	public function international_menifiest($manifiest_id='')
	{
		// Load library
	    $this->load->library('zend');
		// Load in folder Zend
		$this->zend->load('Zend/Barcode');
		$data= array();
		$data['message']="";
		$total_pcs= 0;
		$total_weight= 0;
		$sender_address	= '';
		
		//$manifiest_id   = $this->uri->segment(3);

		if(!empty($manifiest_id) && $manifiest_id!='1')
		{
			$resAct=$this->db->query("select * from tbl_international_menifiest,tbl_international_booking,tbl_users,tbl_branch where 
			tbl_international_booking.pod_no=tbl_international_menifiest.pod_no and
			tbl_international_menifiest.user_id=tbl_users.user_id and
			tbl_users.branch_id=tbl_branch.branch_id and
			manifiest_id='$manifiest_id'");
			$data['manifiest']=$resAct->result_array();

			
			foreach($data['manifiest'] as $key =>$values)
			{
				$total_pcs			= $total_pcs + $values['total_pcs'];
				$total_weight		= $total_weight + $values['total_weight'];
				$sender_address		= $values['address'];
			}
		}
	    
		  if(isset($_POST['submit']))
          {
			
			$manifiest_id=$this->input->post('manifiest_id');
			
			$resAct=$this->db->query("select * from tbl_international_menifiest,tbl_international_booking,tbl_users,tbl_branch where 
			tbl_international_booking.pod_no=tbl_international_menifiest.pod_no and
			tbl_international_menifiest.user_id=tbl_users.user_id and
			tbl_users.branch_id=tbl_branch.branch_id and
			manifiest_id='$manifiest_id'");
			
			$data['manifiest']=$resAct->result_array();
			foreach($data['manifiest'] as $key =>$values)
			{
				$total_pcs			= $total_pcs + $values['total_pcs'];
				$total_weight		= $total_weight + $values['total_weight'];
				$sender_address		= $values['address'];
			}
		 }
		 
		 $data['total_pcs']					= $total_pcs;
		 $data['total_weightt']				= $total_weight;
		 $data['sender_address']			= $sender_address;
		 $where =array('id'=>1);
		$data['company_details'] = $this->basic_operation_m->get_table_row('tbl_company',$where);
		 
		$this->load->view('admin/Menifiest_master/international_manifiest_track',$data);
	}
	public function download_international_menifiest($manifiest_id)
	{
			$filename = "Manifiest_report_".$manifiest_id.".csv";
			$fp = fopen('php://output', 'w');
			$header =array('SR.NO.','AWB NO','FOWARDING NO','DESTINATION','NETWORK','CONSIGNEE','WEIGHT','PROD','DIM');

			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename='.$filename);
			
			fputcsv($fp, $header);
			$i =0;

			$resAct=$this->db->query("select * from tbl_international_menifiest,tbl_international_booking,tbl_users,tbl_branch where 
			tbl_international_booking.pod_no=tbl_international_menifiest.pod_no and
			tbl_international_menifiest.user_id=tbl_users.user_id and
			tbl_users.branch_id=tbl_branch.branch_id and
			manifiest_id='$manifiest_id'");
			$data['manifiest']=$resAct->result_array();

			foreach($data['manifiest'] as $key =>$values)
			{
			
				$i++;

				$whr =array("z_id"=>$values['reciever_country_id']);
                $country_details =$this->basic_operation_m->get_table_row("zone_master",$whr);

                 if($value['doc_type']=='0'){$doc="D";}else{$doc="ND";};
                   
				$booking_date =  date("d-m-Y",strtotime($values['booking_date']));
				$row=array($i,$values['pod_no'], $values['forwording_no'],$country_details->country_name,$values['forworder_name'],$values['reciever_name'],  $values['total_weight'],$doc, $values['dimension']);
				fputcsv($fp, $row);
		
			}
}
public function domestic_menifiest($manifiest_id='')
	{
		// Load library
	    $this->load->library('zend');
		// Load in folder Zend
		$this->zend->load('Zend/Barcode');
		$data= array();
		$data['message']="";
		$total_pcs= 0;
		$total_weight= 0;
		$sender_address	= '';

		$user_id = $_SESSION['userId'];
		$resAct2=$this->db->query("select tbl_branch.* from tbl_branch left JOIN city ON city.id=tbl_branch.city JOIN tbl_users on tbl_users.branch_id=tbl_branch. branch_id where tbl_users.user_id=".$user_id);
		// echo $this->db->last_query();exit();
			
	 	$data['branchAddress']=$resAct2->result_array();
		
		if(!empty($manifiest_id))
		{
			$resAct=$this->db->query("select * from tbl_domestic_menifiest,tbl_domestic_booking,tbl_users,tbl_branch where 
			tbl_domestic_booking.pod_no=tbl_domestic_menifiest.pod_no and
			tbl_domestic_menifiest.user_id=tbl_users.user_id and
			tbl_users.branch_id=tbl_branch.branch_id and
			manifiest_id='$manifiest_id'");
			$data['manifiest']=$resAct->result_array();

			
			foreach($data['manifiest'] as $key =>$values)
			{
				$total_pcs			= $total_pcs + $values['total_pcs'];
				$total_weight		= $total_weight + $values['total_weight'];
				$sender_address		= $values['address'];
			}
		}
	    
		  if(isset($_POST['submit']))
          {
			
			$manifiest_id=$this->input->post('manifiest_id');
			
			$resAct=$this->db->query("select * from tbl_domestic_menifiest,tbl_domestic_booking,tbl_users,tbl_branch where 
			tbl_domestic_booking.pod_no=tbl_domestic_menifiest.pod_no and
			tbl_domestic_menifiest.user_id=tbl_users.user_id and
			tbl_users.branch_id=tbl_branch.branch_id and
			manifiest_id='$manifiest_id'");
			
			$data['manifiest']=$resAct->result_array();
			foreach($data['manifiest'] as $key =>$values)
			{
				$total_pcs			= $total_pcs + $values['total_pcs'];
				$total_weight		= $total_weight + $values['total_weight'];
				$sender_address		= $values['address'];
			}
		 }
		 
		 $data['total_pcs']					= $total_pcs;
		 $data['total_weightt']				= $total_weight;
		 $data['sender_address']			= $sender_address;
		 
		 $where =array('id'=>1);
		$data['company_details'] = $this->basic_operation_m->get_table_row('tbl_company',$where);
		 
		$this->load->view('admin/Menifiest_master/domestic_manifiest_track',$data);
	}
	public function download_domestic_menifiest($manifiest_id)
	{
			$filename = "Manifiest_report_".$manifiest_id.".csv";
			$fp = fopen('php://output', 'w');
			$header =array('SR.NO.','AWB NO','FOWARDING NO','DESTINATION','NETWORK','CONSIGNEE','NOP','WEIGHT','PROD','DIM','DATE','PINCODE','MODE','DESC.','Inv. Value.');
		
		 		
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename='.$filename); 
			
			fputcsv($fp, $header);
			$i =0;
			
			$resAct=$this->db->query("select * from tbl_domestic_menifiest,tbl_domestic_booking,tbl_users,tbl_branch where 
			tbl_domestic_booking.pod_no=tbl_domestic_menifiest.pod_no and
			tbl_domestic_menifiest.user_id=tbl_users.user_id and
			tbl_users.branch_id=tbl_branch.branch_id and
			manifiest_id='$manifiest_id'");
			
			$data['manifiest']=$resAct->result_array();

			foreach($data['manifiest'] as $key =>$values)
			{
				$i++;
				$whr =array("id"=>$values['reciever_city']);
                $city_details =$this->basic_operation_m->get_table_row("city",$whr);
                $mode_details =$this->basic_operation_m->get_table_row("transfer_mode",array("transfer_mode_id"=>$values['mode_dispatch']));
                 if($values['doc_type']=='0'){$doc_type =  "D";}else{$doc_type =  "ND";}
				$booking_date =  date("d-m-Y",strtotime($values['booking_date']));
				$row=array($i, $values['pod_no'],$values['forwording_no'], $city_details->city, $values['forworder_name'], $values['reciever_name'], $values['total_pcs'], $values['total_weight'],$doc_type, $values['dimention'],$booking_date,$values['reciever_pincode'],$mode_details->mode_name,$values['special_instruction'],$values['invoice_value']);
				fputcsv($fp, $row);
		
			}
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
        
		if(!empty($deliverysheet_id))
		{
			
			
			$resAct=$this->db->query("select * from  tbl_deliverysheet,tbl_booking,tbl_users where
			tbl_deliverysheet.pod_no=tbl_booking.pod_no and
			tbl_deliverysheet.deliveryboy_name=tbl_users.username and
			deliverysheet_id='$deliverysheet_id'");
			
			 $data['deliverysheet']=$resAct->result_array();
			$data['branch_address']		=  $this->basic_operation_m->get_table_row('tbl_branch',"branch_id = ".$data['deliverysheet'][0]['branch_id']);
			$data['all_status']			=  $this->basic_operation_m->get_table_result('tbl_status',"");
		}
		elseif(isset($_POST['submit']))
          {
			$deliverysheet_id=$this->input->post('deliverysheet_id');
			
			$resAct=$this->db->query("select * from  tbl_deliverysheet,tbl_booking,tbl_users where
			tbl_deliverysheet.pod_no=tbl_booking.pod_no and
			tbl_deliverysheet.deliveryboy_name=tbl_users.username and
			deliverysheet_id='$deliverysheet_id'");
			
			 $data['deliverysheet']		=  $resAct->result_array();
			$data['branch_address']		=  $this->basic_operation_m->get_table_row('tbl_branch',"branch_id = ".$data['deliverysheet'][0]['branch_id']);
			$data['all_status']			=  $this->basic_operation_m->get_table_result('tbl_status',"");
		  }	
		  
		$this->load->view('printdelivery',$data);
	}
	
	public function print_deliverysheet($deliverysheet_id='')
	{
		
		$data= array();
		$data['message']="";
         // Load library
	    $this->load->library('zend');
		// Load in folder Zend
		$this->zend->load('Zend/Barcode');
        
		if(!empty($deliverysheet_id))
		{
			
			
			$resAct=$this->db->query("select * from  tbl_deliverysheet,tbl_booking,tbl_users where
			tbl_deliverysheet.pod_no=tbl_booking.pod_no and
			tbl_deliverysheet.deliveryboy_name=tbl_users.username and
			deliverysheet_id='$deliverysheet_id'");
			
			 $data['deliverysheet']=$resAct->result_array();
			$data['branch_address']		=  $this->basic_operation_m->get_table_row('tbl_branch',"branch_id = ".$data['deliverysheet'][0]['branch_id']);
			$data['all_status']			=  $this->basic_operation_m->get_table_result('tbl_status',"");
		}
		elseif(isset($_POST['submit']))
          {
			$deliverysheet_id=$this->input->post('deliverysheet_id');
			
			$resAct=$this->db->query("select * from  tbl_deliverysheet,tbl_booking,tbl_users where
			tbl_deliverysheet.pod_no=tbl_booking.pod_no and
			tbl_deliverysheet.deliveryboy_name=tbl_users.username and
			deliverysheet_id='$deliverysheet_id'");
			
			 $data['deliverysheet']		=  $resAct->result_array();
			$data['branch_address']		=  $this->basic_operation_m->get_table_row('tbl_branch',"branch_id = ".$data['deliverysheet'][0]['branch_id']);
			$data['all_status']			=  $this->basic_operation_m->get_table_result('tbl_status',"");
		  }	
		  
		$this->load->view('printprintdelivery',$data);
	}
	
	public function update_deliverysheet_status()
	{
		
		$data= array();
		
		$data['message']="";
		
		$all_data 				=	 $this->input->post();
		if(!empty($all_data))
		{
			if(!empty($all_data['deliverysheet_id']) && !empty($all_data['status']) && !empty($all_data['pod_id']))
			{
				foreach($all_data['pod_id'] as $key => $pod_no)
				{ 
					if(isset($all_data['status'][$key]) && !empty($all_data['status'][$key]))
					{
						$booking_info		=  $this->basic_operation_m->get_table_row('tbl_booking',"pod_no = '$pod_no'");
						$date 				= $this->input->post('datetime');
						$whr = array('branch_id'=>$booking_info->branch_id);
						$res=$this->basic_operation_m->getAll('tbl_branch',$whr);
						$branch_name= $res->row()->branch_name;
						
						 $data1	=array('id'=>'',
							 'pod_no'=>$pod_no,
							 'status'=>$all_data['status'][$key],
							 'branch_name'=>$branch_name,
							 'forworder_name'=>$booking_info->forworder_name,
							 'booking_id'=>$booking_info->booking_id,
							 'forwording_no'=>$booking_info->forwording_no,
							 'tracking_date'=>$date,
							 );
							 
							 
							 if($all_data['status'][$key] == 'Delivered')
							 {
								$queue_dataa		= "update tbl_booking set is_delhivery_complete ='1' where booking_id = '$booking_info->booking_id'";
								$status				= $this->db->query($queue_dataa);		 
							 }
						$this->basic_operation_m->insert('tbl_tracking',$data1);
					}
				}
			}
		}
		redirect('tracking/deliverysheet/'.$all_data['deliverysheet_id']);
	}
	
	
	public function changeshipmentstatus()
	{
		$this->load->helper('form_helper');
		$resAct = $this->basic_operation_m->getAll('tbl_customers', '');
		if ($resAct->num_rows() > 0) {
			$data['customers'] = $resAct->result_array();
		}
		
	    $username=$this->session->userdata("userName");
		     $whr = array('username'=>$username);
			 $res=$this->basic_operation_m->getAll('tbl_users',$whr);
			 $branch_id= $res->row()->branch_id;
			 
			 $whr = array('branch_id'=>$branch_id);
			 $res=$this->basic_operation_m->getAll('tbl_branch',$whr);
			 $branch_name= $res->row()->branch_name;
	    
	    $resAct	= $this->db->query("select * from tbl_inword where branch_code='$branch_name'");
		  if($resAct->num_rows()>0)
		 {
		 	$data['deliverysheet']=$resAct->result_array();	            
         }
		 $data['currentbooking'] = array();
        //  $resAct	= $this->db->query("select * from tbl_booking where branch_id='$branch_id'");
		/*   if($resAct->num_rows()>0)
		 {
		 	$data['currentbooking']=$resAct->result_array();	            
         } */
         
         if(isset($_POST['submit']))
         {
         	$all_data 		= $this->input->post();
           
			$username=$this->session->userdata("userName");
			$whr = array('username'=>$username);
			$res=$this->basic_operation_m->getAll('tbl_users',$whr);
		    $branch_id= $res->row()->branch_id;
			$date=date('y-m-d');
			 
			$whr = array('branch_id'=>$branch_id);
			$res=$this->basic_operation_m->getAll('tbl_branch',$whr);
			$branch_name= $res->row()->branch_name;
			$pod_no=$this->input->post('pod_no');
			$status=$this->input->post('status');
			$comment = $this->input->post('comment');
			$date=date('Y-m-d H:i:s');

			$this->db->select('pod_no, booking_id, forworder_name, forwording_no');
			$this->db->from('tbl_booking');
			$this->db->where('pod_no', $pod_no);
			$this->db->order_by('booking_id', 'DESC');
			$result = $this->db->get();
			$resultData = $result->row_array();
			echo $this->db->last_query();
print_r($resultData);exit;
			$bookingId = $resultData['booking_id'];
			$forworder_name = $resultData['forworder_name'];
			$forwording_no = $resultData['forwording_no'];

			$is_spoton = 0;	
			$is_delhivery_b2b = 0;	
			$is_delhivery_c2c = 0;	
			if($forworder_name)
			{
				if($forworder_name == 'delhivery_b2b')
				{
					$is_delhivery_b2b = 1;
				}
				if($forworder_name == 'delhivery_c2c')
				{
					$is_delhivery_c2c = 1;
				}
				if($forworder_name == 'spoton_service')
				{
					$is_spoton = 1;
				}
			}

			$is_delhivery_complete = 0;
			if($status == 'Delivered')
			{
				$is_delhivery_complete = 1;

				$where = array('booking_id' => $bookingId);
				$updateData = [
					'is_delhivery_complete' => $is_delhivery_complete,
				];
				$this->db->update('tbl_booking', $updateData, $where);
			}
			$remarks = $_POST['remarks'];
			$data1 = array('id' => '',
				'pod_no' => $pod_no,
				'status' => $status,
				'branch_name' => $branch_name,
				'tracking_date' => $this->input->post('currentdate'),
				'comment' => $comment,
				'is_spoton' => $is_spoton,
				'is_delhivery_b2b' => $is_delhivery_b2b,
				'is_delhivery_c2c' => $is_delhivery_c2c,
				'forworder_name' => $forworder_name,
				'booking_id' => $bookingId,
				'forwording_no' => $forwording_no,
				'is_delhivery_complete' => $is_delhivery_complete,
				'remarks' => $remarks
			);

			$result1=$this->basic_operation_m->insert('tbl_tracking',$data1);

			$pod_no=$this->input->post('pod_no');
			$data = [];
			$resAct=$this->db->query("select * from tbl_booking,tbl_city where tbl_booking.sender_city=tbl_city.city_id and 
			pod_no='$pod_no' and tbl_booking.booking_type = 1 ");
			 $data['info']=$resAct->row();
			 
			 	$resAct=$this->db->query("select * from tbl_booking,tbl_city where tbl_booking.reciever_city=tbl_city.city_id and 
			pod_no='$pod_no' and tbl_booking.booking_type = 1 ");
			 $data['info1']=$resAct->row();
			
			//$pod_no=$this->input->post('pod_no');
			$reAct=$this->db->query("select * from tbl_tracking where pod_no='$pod_no'");
          
			$data['pod']=$reAct->result_array();
			
			
			$reAct=$this->db->query("select * from tbl_upload_pod where pod_no='$pod_no'");
			
			$data['podimg']=$reAct->row();
			
            $whr = array('pod_no'=>$pod_no);
			$res=$this->basic_operation_m->getAll('tbl_booking',$whr);
			$customerid= $res->row()->customer_id;
			if($customerid != '') {
    		    $whr = array('customer_id'=>$customerid);
    			$res=$this->basic_operation_m->getAll('tbl_customers',$whr);
    				if (!empty($res->row())) {
        			$email= $res->row()->email;
         			$data['customer_name'] = $res->row()->customer_name;
        			
        			//$message = $this->load->view('senttracking',$data, true);
                    
        		//	$this->sendemail($email,$message);
    			}
                redirect('admin/change-shipment-status');
			}
         }
         
        // print_r($data['currentbooking']);
		
		if(isset($_GET['filter']) &&  $_GET['filter'] == 'customerwise')
		{

			$fromDate = $this->input->get('from');
			$toDate = $this->input->get('to');
			
			if($toDate)
			{
				$toDateArr = explode('/', $toDate);
				$toDate = $toDateArr[2].'-'.$toDateArr[1].'-'.$toDateArr[0];
			}
			
			if($fromDate)
			{
				$fromDateArr = explode('/', $fromDate);
				$fromDate = $fromDateArr[2].'-'.$fromDateArr[1].'-'.$fromDateArr[0];
			}
			
			$where = '';
			if ($this->input->get('customer_account_id')) {
				$where = " AND tb.customer_id = " . $this->input->get('customer_account_id');
			}
			$towhere = '';
			if ($this->input->get('to')) {
				$towhere = " AND UNIX_TIMESTAMP( CONVERT(tb.booking_date, DATE)) <= " . strtotime("+1 day", strtotime($toDate));
			}
			$fromwhere = '';
			if ($this->input->get('from')) {
				$fromwhere = " AND UNIX_TIMESTAMP( CONVERT(tb.booking_date, DATE)) >= " . strtotime($fromDate);
			}
			$query = "SELECT tt.id,tt.pod_no, tt.status as delivery_status, tt.comment,tb.booking_id, tb.pod_no,tb.sender_city,tb.sender_name,tb.reciever_name,tb.reciever_city,tb.booking_date,tb.forworder_name,tb.forwording_no, tb.delivery_date
		FROM tbl_booking AS tb
		INNER JOIN tbl_tracking AS tt ON tt.booking_id = tb.booking_id
		WHERE
		   tt.id = (
			  SELECT MAX(id)
			  FROM tbl_tracking
			  WHERE booking_id = tb.booking_id
		   ) $where $fromwhere $towhere ORDER By tt.tracking_date DESC";
		   

		$resAct5 = $this->db->query($query);
		//echo $this->db->last_query(); die;
        if ($resAct5->num_rows() > 0) {
           $data['allpoddata'] = $resAct5->result_array();
        }
		}
		else
		{
			 $data['allpoddata'] = array();
		}
		
		
		
		
		//echo $this->db->last_query();
        
        $resAct6 = $this->db->query("select * from tbl_city");
        if($resAct6->num_rows() > 0){
            $data['citydata'] = $resAct6->result_array();
        }
         
         $this->load->view('admin/tracking/view_change_shipment_status',$data);
	}
	
	public function awbnodata(){
		 $pod = $_REQUEST['awb_no'];
				$query = "SELECT tt.id, tt.status as delivery_status, tt.comment,tb.booking_id, tb.pod_no,tb.sender_city,tb.sender_name,tb.reciever_name,tb.reciever_city,tb.booking_date,tb.forworder_name,tb.forwording_no, tb.delivery_date
		FROM tbl_booking AS tb
		INNER JOIN tbl_tracking AS tt ON tt.booking_id = tb.booking_id
		WHERE tt.pod_no = '$pod' ORDER BY tt.id DESC";
		$data = "";
		$resAct5 = $this->db->query($query);
		//echo "<pre>"; print_r($resAct5->row_array());die;
        if ($resAct5->num_rows() > 0) {
           $booking_row = $resAct5->row_array();
          $pod =  $booking_row['pod_no'];
          $booking_id = $booking_row['booking_id'];
          $podid = "checkbox-".$pod;
          $dataid = 'data-val-'.$booking_id;

                $data .='<tr><td><div class="custom-checkbox custom-control">';
                $data .= "<input type='checkbox' data-checkboxes='mygroup' name='checkbox[]'
                                                        class='custom-control-input'
                                                        id='{$podid}' value='{$booking_id}' checked>";


                $data .="<label for='{$podid}'class='custom-control-label'>&nbsp;</label>
                                                </div>
                                            </td>";

                  $data .="<td>";
                  $data .= $booking_row['pod_no'];
              $data .="</td>";
                  $data .="<td>";
                  $data .= $booking_row['sender_name'];
                  $data .="</td>";
                  $resAct6 = $this->db->query("select * from tbl_city");
			        if($resAct6->num_rows() > 0){
			            $citydata  = $resAct6->result_array();
			        }
                  
                  foreach ($citydata as $sender_city) {
                      if($sender_city['city_id'] == $booking_row['sender_city']){
                  
                        $data .="<td>";

                        $data .= $sender_city['city_name'];
                        $data .="</td>";
                  
                        }
                    }

                 
                  $data .="<td>";
                 $data .= $booking_row['reciever_name'];

                 $data .="</td>";
                  
                  foreach ($citydata as $reciever_city) {
                      if($reciever_city['city_id'] == $booking_row['reciever_city']){
                 
                        $data .="<td>";
                        $data .= $reciever_city['city_name'];
                        $data .="</td>";
                  
                        }
                    }

				  
				  $data .="<td>";

				  $data .= $booking_row['forworder_name'];
				  $data .="</td>";
				  $data .="<td>";
				$data .= $booking_row['forwording_no'];
				$data .="</td>";
						$data .= "<td>";
						$data .= date('d-m-Y H:i:s',strtotime($booking_row['booking_date']));
						$data .="</td>";
						$data .= "<td>";
						$data .= date('d-m-Y', strtotime($booking_row['delivery_date'])); 
						$data .="</td>";

                  $data .= "<td>";
                 $data .= $booking_row['delivery_status'];
                 $data .="</td>";

				 $data .= "<td>";
				 $data .= $booking_row['comment'];
				 $data .="</td>";

				 $data .= "<td>";
				 $data .= "<a href='#' class='btn btn-danger remove-rec' id='{$dataid}'  >";
				 $data .= "Remove";
				 $data .="</a>";
				 $data .="</td>";

				  
                $data .= "</tr>";

        }
        echo  $data ;
        
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
        
        $this->email->from('info@shreelogistics.net', 'shreelogistics Admin');
        $this->email->to($to); 
        
        $this->email->subject('Shipment Update');
        $this->email->message($message);	
        
        $this->email->send();


	}
	
	public function showinfo()
	{
	    
	        $pod_no=$this->input->post('pod_no');
			
			$resAct=$this->db->query("select * from tbl_booking,tbl_city where tbl_booking.sender_city=tbl_city.city_id and 
			pod_no='$pod_no'");
			 $info=$resAct->row();
			 
			 	$resAct=$this->db->query("select * from tbl_booking,tbl_city where tbl_booking.reciever_city=tbl_city.city_id and 
			pod_no='$pod_no'");
			 $info1=$resAct->row();
			 
			 echo '<table id="example1" class="table table-bordered table-striped">
					<tr>
						<th>Airway Number</th>
						<th>Consigner Name</th>
						<th>Consigner Address</th>
						<th>Consignee Name</th>
						<th>Consignee Address</th>
						<th>Forwording No</th>
						<th>Forworder Name</th>
						<th>Booking Date</th>
					</tr>
					<tr>
						<td>'.$info->pod_no.'</td>
						<td>'.$info->sender_name.'</td>
						<td>'.$info->sender_address.'</td>
						<td>'.$info->reciever_name.'</td>
						<td>'.$info->reciever_address.'</td>
						<td>'.$info->forwording_no.'</td>
						<td>'.$info->forworder_name.'</td>
						<td>'.$info->booking_date.'</td>
					</tr>
				</table>';
			 
	}
	
	public function spotopnTracking()
	{
	    $query = $this->db->get_where('tbl_spoton_shipment', ['status' => 'Y']);
	    $result = $query->result_array();
	    if(!empty($result)) {
	        foreach($result as $result) {
	            $trackingData = $this->spotontracking->getTrackingData($result['conNo']);
	            $data = json_decode($trackingData);
	            $this->db->where('conNo', $result['conNo']);
                $query = $this->db->get('tbl_spotopn_tracking');
                if (!empty($query->result_array())){
                    $this->db->where('conNo', $result['conNo']);
                    $this->db->update('tbl_spotopn_tracking', ['conNo' => $result['conNo'], 'response' => $trackingData, 'status' =>$data->Status, 'podno' => $result['podno']]);
                }
                else{
	                $this->db->insert('tbl_spotopn_tracking',['conNo' => $result['conNo'], 'response' => $trackingData, 'status' =>$data->Status, 'podno' => $result['podno']]);
                }
	            
	        }
	    }
	    echo 'ok';
	    //exit;
	}
	
		public function changebulkstatus() 
	{
		//print_r($_POST); die;
        
		if(isset($_FILES['csv_zip']['name']) && !empty($_FILES['csv_zip']['name']))
		{			
			$file = fopen($_FILES['csv_zip']['tmp_name'],"r");
		
			$cnt = 0;
			$pcs = 0;
			$a_w = 0;
			
			while(!feof($file))
			{
				$data 				= fgetcsv($file);
				if(!empty($data[0]))
				{
					$booking_ids[]		= $data[0];
				}
				
			}
		}
		else
		{
			$booking_ids = $this->input->post('checkbox[]');
		
		}
	
		
        $currentdate = $this->input->post('currentdate');
        $status = $this->input->post('status');
        $comment = $this->input->post('comment');
        
        $username = $this->session->userdata("userName");
        $whr = array('username' => $username);
        $res = $this->basic_operation_m->getAll('tbl_users', $whr);
        $branch_id = $res->row()->branch_id;
        $date = date('y-m-d');

        $whr = array('branch_id' => $branch_id);
        $res = $this->basic_operation_m->getAll('tbl_branch', $whr);
		$branch_name = $res->row()->branch_name;
		
        $count = count($booking_ids);
        foreach($booking_ids as $values)
		{
			$booking_info		= $this->db->query("select pod_no, booking_id, forworder_name, forwording_no from tbl_booking where  pod_no='$values'");
			$booking_infoo		= $booking_info->row();
			if(!empty($booking_infoo))
			{
				$bookingId						= $booking_infoo->booking_id;
				$bookingId				 		= $booking_ids[$i];
				$pod_no 						= $booking_infoo->pod_no;
				$forworder_name 				= $booking_infoo->forworder_name;
				$forwording_no 					= $booking_infoo->forwording_no;

				$is_spoton = 0;	
				$is_delhivery_b2b = 0;	
				$is_delhivery_c2c = 0;	
				if($forworder_name)
				{
					if($forworder_name == 'delhivery_b2b')
					{
						$is_delhivery_b2b = 1;
					}
					if($forworder_name == 'delhivery_c2c')
					{
						$is_delhivery_c2c = 1;
					}
					if($forworder_name == 'spoton_service')
					{
						$is_spoton = 1;
					}
				}

				$is_delhivery_complete = 0;
				if($status == 'Delivered')
				{
					$is_delhivery_complete = 1;

					$where = array('booking_id' => $bookingId);
					$updateData = [
						'is_delhivery_complete' => $is_delhivery_complete,
					];
					$this->db->update('tbl_booking', $updateData, $where);
				}
				$remarks = $_POST['Remark'];
				//echo $pod_no[$i];
			   $data1 = array('id' => '',
					'pod_no' => $pod_no,
					'status' => $status,
					'branch_name' => $branch_name,
					'tracking_date' => $currentdate,
					'comment' => $comment, 
					'is_spoton' => $is_spoton,
					'is_delhivery_b2b' => $is_delhivery_b2b,
					'is_delhivery_c2c' => $is_delhivery_c2c,
					'forworder_name' => $forworder_name,
					'booking_id' => $bookingId,
					'forwording_no' => $forwording_no,
					'is_delhivery_complete' => $is_delhivery_complete,
					'remarks' => $remarks
				);
				$result1 = $this->basic_operation_m->insert('tbl_tracking', $data1);
			}
			else
			{
				$booking_info		= $this->db->query("select pod_no, booking_id, forworder_name, forwording_no from tbl_booking where  booking_id='$values'");
				$booking_infoo		= $booking_info->row();
				if(!empty($booking_infoo))
				{
					$bookingId						= $booking_infoo->booking_id;
					$pod_no 						= $booking_infoo->pod_no;
					$forworder_name 				= $booking_infoo->forworder_name;
					$forwording_no 					= $booking_infoo->forwording_no;

					$is_spoton = 0;	
					$is_delhivery_b2b = 0;	
					$is_delhivery_c2c = 0;	
					if($forworder_name)
					{
						if($forworder_name == 'delhivery_b2b')
						{
							$is_delhivery_b2b = 1;
						}
						if($forworder_name == 'delhivery_c2c')
						{
							$is_delhivery_c2c = 1;
						}
						if($forworder_name == 'spoton_service')
						{
							$is_spoton = 1;
						}
					}

					$is_delhivery_complete = 0;
					if($status == 'Delivered')
					{
						$is_delhivery_complete = 1;

						$where = array('booking_id' => $bookingId);
						$updateData = [
							'is_delhivery_complete' => $is_delhivery_complete,
						];
						$this->db->update('tbl_booking', $updateData, $where);
					}
					$remarks = $_POST['Remark'];
					//echo $pod_no[$i];
				   $data1 = array('id' => '',
						'pod_no' => $pod_no,
						'status' => $status,
						'branch_name' => $branch_name,
						'tracking_date' => $currentdate,
						'comment' => $comment, 
						'is_spoton' => $is_spoton,
						'is_delhivery_b2b' => $is_delhivery_b2b,
						'is_delhivery_c2c' => $is_delhivery_c2c,
						'forworder_name' => $forworder_name,
						'booking_id' => $bookingId,
						'forwording_no' => $forwording_no,
						'is_delhivery_complete' => $is_delhivery_complete,
						'remarks' => $remarks
					);
					$result1 = $this->basic_operation_m->insert('tbl_tracking', $data1);
				}
			}
        }
     	redirect('admin/change-shipment-status');
    }
	
	
}
?>
