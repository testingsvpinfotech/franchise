<?php
error_reporting(E_ALL);
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_international_shipment_manager extends CI_Controller  {

	var $data = array();
    function __construct() 
	{
        parent :: __construct();
        $this->load->model('basic_operation_m');    
         if($this->session->userdata('userId') == '')
 		{
 			redirect('admin');
 		}
    }
	
  public function view_international_shipment($offset=0,$searching='') 
	{	
	   		
		$data= [];
		if(isset($_POST['from_date']))
		{
			$data['from_date'] = $_POST['from_date'];
			$from_date = $_POST['from_date'];
		}
		if(isset($_POST['to_date']))
		{
			$data['to_date'] = $_POST['to_date'];
			$to_date = $_POST['to_date'];
		}
		if(isset($_POST['filter']))
		{
			$filter = $_POST['filter'];
			$data['filter']  = $filter;
		}
		if(isset($_POST['courier_company']))
		{
			$courier_company = $_POST['courier_company'];
			$data['courier_companyy']  = $courier_company;
		}
		if(isset($_POST['user_id']))
		{
			$user_id = $_POST['user_id'];
			$data['user_id']  = $user_id;
		}
		if(isset($_POST['filter_value']))
		{
			$filter_value = $_POST['filter_value'];
			$data['filter_value']  = $filter_value;
		}
		

		$user_id 	= $this->session->userdata("userId");
		$data['customer']=  $this->basic_operation_m->get_query_result_array('SELECT * FROM tbl_customers WHERE 1 ORDER BY customer_name ASC');
		$user_type 					= $this->session->userdata("userType");			
		$filterCond					= '';
		$all_data 					= $this->input->post();

			
		if($all_data)
		{	
			$filter_value					= 	$_POST['filter_value'];
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
				elseif($ke == 'courier_company' && !empty($vall) && $vall !="ALL")
				{
					$filterCond .= " AND tbl_international_booking.courier_company_id = '$vall'";
				}
			}
				
		}

		if(!empty($searching))
		{
			$filterCond = urldecode($searching);
		}


		if ($this->session->userdata("userType") == '1') 
		{
			$resActt = $this->db->query("SELECT * FROM tbl_international_booking  WHERE booking_type = 1 $filterCond ");

			$resAct = $this->db->query("SELECT tbl_international_booking.*,zone_master.country_name,tbl_international_weight_details.chargable_weight  FROM tbl_international_booking LEFT JOIN zone_master ON tbl_international_booking.reciever_country_id = zone_master.z_id LEFT JOIN tbl_international_weight_details ON tbl_international_weight_details.booking_id = tbl_international_booking.booking_id   WHERE booking_type = 1 AND company_type='International' AND tbl_international_booking.user_type !=5 $filterCond order by tbl_international_booking.booking_id DESC limit ".$offset.",50");
			
			$download_query 		= "SELECT tbl_international_booking.*,zone_master.country_name,tbl_international_weight_details.chargable_weight  FROM tbl_international_booking LEFT JOIN zone_master ON tbl_international_booking.reciever_country_id = zone_master.z_id LEFT JOIN tbl_international_weight_details ON tbl_international_weight_details.booking_id = tbl_international_booking.booking_id   WHERE booking_type = 1 AND company_type='International' AND tbl_international_booking.user_type !=5 $filterCond order by tbl_international_booking.booking_id DESC";
			
			$this->load->library('pagination');
		
			$data['total_count']			= $resActt->num_rows();
			$config['total_rows'] 			= $resActt->num_rows();
			$config['base_url'] 			= 'admin/view-international-shipment';
			$config['suffix'] 				= '/'.urlencode($filterCond);
			
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
			if ($resAct->num_rows() > 0) 
			{
			
				$data['allpoddata'] 			= $resAct->result_array();
			}
			else
			{
				$data['allpoddata'] 			= array();
			}
		}
		else
		{
			$where 		= '';
			if($this->session->userdata("userType") == '5') 
			{
				$where 	= ' AND tbl_international_booking.user_id = '.$user_id;
			}

			$resActt = $this->db->query("SELECT * FROM tbl_international_booking  WHERE booking_type = 1 $where $filterCond ");
			$resAct = $this->db->query("SELECT tbl_international_booking.*,zone_master.country_name,tbl_international_weight_details.chargable_weight  FROM tbl_international_booking LEFT JOIN zone_master ON tbl_international_booking.reciever_country_id = zone_master.z_id LEFT JOIN tbl_international_weight_details ON tbl_international_weight_details.booking_id = tbl_international_booking.booking_id   WHERE booking_type = 1 $where $filterCond order by tbl_international_booking.booking_id DESC limit ".$offset.",50");
			$download_query 		= "SELECT tbl_international_booking.*,zone_master.country_name,tbl_international_weight_details.chargable_weight  FROM tbl_international_booking LEFT JOIN zone_master ON tbl_international_booking.reciever_country_id = zone_master.z_id LEFT JOIN tbl_international_weight_details ON tbl_international_weight_details.booking_id = tbl_international_booking.booking_id   WHERE booking_type = 1 $where $filterCond order by tbl_international_booking.booking_id DESC";
			
			$this->load->library('pagination');
		
			$data['total_count']			= $resActt->num_rows();
			$config['total_rows'] 			= $resActt->num_rows();
			$config['base_url'] 			= 'admin/view-international-shipment/';
			$config['suffix'] 				= '/'.urlencode($filterCond);
			
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
			if($resAct->num_rows() > 0) 
			{
				$data['allpoddata']= $resAct->result_array();
			}
			else
			{
				$data['allpoddata']= array();
			}
		}
		
		
		if(isset($_POST['download_report']) &&  $_POST['download_report'] == 'Download Report')
		{
			$resActtt = $this->db->query($download_query);
			$shipment_data= $resActtt->result_array();
			$this->download_international_shipment_report($shipment_data);
		}
		$data['viewVerified'] = 2;	
		$whr_c =array('company_type'=>'International');
		$data['courier_company']= $this->basic_operation_m->get_all_result("courier_company",$whr_c);
		$this->load->view('admin/international_shipment/view_international_shipment', $data);
		
       
	}
	
	public function download_international_shipment_report($shipment_data)
	{    

		$date=date('d-m-Y');
		$filename = "SipmentDetails_".$date.".csv";
		$fp = fopen('php://output', 'w');
		$header =array(
			"SrNo.","Booking Date","ABW No","Sender name","Return Address","Return Pin","Receiver Name","Address","Country","Zipcode","Receiver Contact","Mode","Waybill","ForwordingNo","Forworder","Payment Mode","Package Amount","Product to be Shipped",
			"Chargable Weight","Freight","Transport","Destination","Clearance","ESS","OtherCh","Total","Fuel Surcharge","Sub Total","CGST Tax","SGST Tax","IGST Tax","Grand Total");
			
			
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);

		fputcsv($fp, $header);
		$i =0;
			
		foreach($shipment_data as $row) {
			$i++;
			
			$whr=array('id'=>$row['sender_city']);
			$sender_city_details = $this->basic_operation_m->get_table_row("city",$whr);
			$sender_city = $sender_city_details->city;
			
			$whr_p=array('id'=>$row['payment_method']);
			$payment_method_details = $this->basic_operation_m->get_table_row("payment_method",$whr_p);
			$payment_method = $payment_method_details->method;
		
		
			$row=array($i,$row['booking_date'],$row['pod_no'],$row['sender_name'],$row['sender_address'],$row['sender_pincode'],$row['reciever_name'],$row['reciever_address'],$row['country_name'],$row['reciever_zipcode'],$row['reciever_contact'],$row['mode_dispatch'],$row['eway_no'],$row['forwording_no'],$row['forworder_name'],$payment_method,$row['invoice_value'],$row['special_instruction'],$row['chargable_weight'],$row['frieht'],$row['transportation_charges'],$row['destination_charges'],$row['clearance_charges'],$row['ecs'],$row['other_charges'],$row['total_amount'],$row['fuel_subcharges'],$row['sub_total'],$row['cgst'],$row['sgst'],$row['igst'],$row['grand_total']);
			fputcsv($fp, $row);
		}
		exit;
	}

	 public function view_pending_international_forworder()
    {
    	$whr =array("forwording_no"=>"");
    	$data['all_pending_forworder'] = $this->basic_operation_m->get_all_result("tbl_international_booking",$whr);	
		$this->load->view('admin/international_shipment/view_international_pending_forworder', $data);
    }
    public function view_international_unbill_shipment()
    {
        $whr =array("invoice_generated_status"=>"0");
        $data['all_unbill_shippment'] = $this->basic_operation_m->get_all_result("tbl_international_booking",$whr);    
        $this->load->view('admin/international_shipment/view_international_unbill_shipment', $data);
    }
     public function add_new_rate_international()
    {
        $sub_total 				= 0;        	
        $fixed_perkg 			= 0;        	
        $rate 					= 0;        	
		$courier_company_id	 = $this->input->post('courier_company_id');
		$reciever_country_id = $this->input->post('reciever_country_id');
		$reciever_zone_id 	 = $this->input->post('reciever_zone_id');
		$customer_id 		 = $this->input->post('customer_id');
		$weight		 		 = $this->input->post('weight');
		$qty		 		 = $this->input->post('qty');
		$doc 				 = $this->input->post('doc'); // non-doc =1 , doc =0
		$type_export_import  = $this->input->post('type_export_import');
		$dispatch_details       = $this->input->post('dispatch_details');
		$actual_weight 		= $this->input->post('actual_weight');
		$valumetric_weight	= $this->input->post('valumetric_weight');
		$chargable_weight   = $this->input->post('chargable_weight');
		$booking_date       = $this->input->post('booking_date');
		$current_date = date("Y-m-d",strtotime($booking_date));

		$data['rate_master'] = new \stdClass();

		$a 		= $chargable_weight; // input value 
		$int 	= (int)$a;
		$result = ($a - $int);
		
		if( ($result <= 0.50) && !empty($result))
		{
			$fin_chargable_weight = $int + 0.50;

		}
		else if( ($result >= 0.50) && !empty($result) )
		{
			$fin_chargable_weight = $int + 1;
		}
		else
		{
			$fin_chargable_weight = round($a);
		}
		
		$fin_chargable_weight1 =$fin_chargable_weight;
		
		$res = $this->db->query("select * from tbl_international_rate_master where courier_company_id='".$courier_company_id."' AND zone_id='".$reciever_zone_id."' AND customer_id='".$customer_id."' AND DATE(`from_date`)<='".$current_date."' AND type_export_import='".$type_export_import."'  AND doc_type='".$doc."' AND ('".$fin_chargable_weight1."' BETWEEN `weight_from` AND `weight_to`) ORDER BY from_date DESC LIMIT 1 ");
		
		if ($res->num_rows() > 0) 
		{
			$data['rate_master'] = $res->row();
			
			$fixed_perkg = $data['rate_master']->fixed_perkg;
			$rate		 = $data['rate_master']->rate;
		}
		
		if($fixed_perkg=="0")
		{
			$frieht = $rate ;
		}else if($fixed_perkg=="1")
		{
			$frieht = $fin_chargable_weight1 * $rate;
		}

		$amount =$frieht;
		
		$whr1 = array('courier_id' => $courier_company_id,'fuel_from <=' => $current_date,'fuel_to >=' => $current_date);
		$res1 = $this->basic_operation_m->get_table_row('courier_fuel', $whr1);
		if($res1){$fuel_per = $res1->fuel_price; }else{$fuel_per ='0';}
		$final_fuel_charges =($amount * $fuel_per/100);

		$sub_total =($amount + $final_fuel_charges);
		
		$gst_details =$this->basic_operation_m->get_query_row('select * from tbl_gst_setting order by id desc limit 1');
		
		if($gst_details){
			$cgst_per = $gst_details->cgst; 
			$sgst_per = $gst_details->sgst; 
			$igst_per = $gst_details->igst; 
		}else{
			$cgst_per = '0'; 
			$sgst_per = '0'; 
			$igst_per = '0'; 
		}            


		if($type_export_import=="Export")
		{
			$cgst = 0;
			$sgst = 0;
			$igst = ($sub_total*$igst_per/100);
			$grand_total = $sub_total + $igst;

		}else if($type_export_import=="Import")
		{
			$cgst = ($sub_total*$cgst_per/100);
			$sgst = ($sub_total*$sgst_per/100);
			$igst = 0;
			$grand_total = $sub_total + $cgst + $sgst + $igst;
		}
		
		$tbl_customers_info 		= $this->basic_operation_m->get_query_row("select gst_charges,gstno from tbl_customers where customer_id = '$customer_id'");
		
		if($tbl_customers_info->gst_charges == 1)
		{
			$first_two_char = substr($tbl_customers_info->gstno,0,2);
			if($first_two_char==27)
			{
				$cgst = ($sub_total*$cgst_per/100);
				$sgst = ($sub_total*$sgst_per/100);
				$igst = 0;
				$grand_total = $sub_total + $cgst + $sgst + $igst;
			}
			else
			{
				$cgst = 0;
				$sgst = 0;
				$igst = ($sub_total*$igst_per/100);
				$grand_total = $sub_total + $igst;
			}		
		}
		else
		{
			$cgst = 0;
			$sgst = 0;
			$igst = 0;
			$grand_total = $sub_total + $igst;
		}
		
		if($dispatch_details == 'Cash')
		{	
			$cgst = 0;
			$sgst = 0;
			$igst = 0;
			$grand_total = $sub_total + $igst;
		}

		$query ="select * from tbl_international_rate_master where courier_company_id='".$courier_company_id."' AND zone_id='".$reciever_zone_id."' AND customer_id='".$customer_id."' AND DATE(`from_date`)<='".$current_date."' AND type_export_import='".$type_export_import."'  AND doc_type='".$doc."' AND ('".$fin_chargable_weight1."' BETWEEN `weight_from` AND `weight_to`) ORDER BY from_date DESC LIMIT 1 ";

		$data = array(
			'query'=>$query,
			'result'=>$result,			
			'chargable_weight'=>ceil($chargable_weight),		 	
			'frieht' => $frieht,
			'amount' => $amount,
			'final_fuel_charges'=>$final_fuel_charges,
			'sub_total'=>number_format($sub_total, 2, '.', ''),
            'cgst'=>number_format($cgst, 2, '.', ''),
            'sgst'=>number_format($sgst, 2, '.', ''),
			'igst'=>number_format($igst, 2, '.', ''),
			'grand_total'=>number_format($grand_total, 2, '.', ''),
		);
		
		echo json_encode($data);
		exit;
    }
	
    public function add_international_shipment() 
	{
		$data			= $this->data;
        $result 		= $this->db->query('select max(booking_id) AS id from tbl_international_booking')->row();
        $id 			= $result->id + 1;

        $id = 10000 + $id;

        $id = "SD".$id;
        
		
        $data['transfer_mode']		 	= $this->basic_operation_m->get_query_result('select * from `transfer_mode`');
        $data['cities']	= $this->basic_operation_m->get_all_result('city', '');        
        $data['states']	= $this->basic_operation_m->get_all_result('state', '');
       
        $user_id 						= $this->session->userdata("userId");
        $user_type 						= $this->session->userdata("userType");
		  $user_type 	= $this->session->userdata("userType");
		if($user_type == 1)
		{
			$data['customers'] =$this->basic_operation_m->get_all_result('tbl_customers', "");
		}
		else
		{
			$username = $this->session->userdata("userName");
			 $whr = array('username' => $username);
			 $res = $this->basic_operation_m->getAll('tbl_users', $whr);
			 $branch_id = $res->row()->branch_id;				
			$where ="branch_id='$branch_id' ";
			$data['customers'] =$this->basic_operation_m->get_all_result('tbl_customers', "branch_id = '$branch_id'");
		}
		
        
        
        $data['payment_method']  = $this->basic_operation_m->get_all_result('payment_method', '');
      
		$data['bid']= $id;

        $current_date = date("Y-m-d");       

        $whr2 = array('from <=' => $current_date,'to >=' => $current_date);
        $data['gst_details'] = $this->basic_operation_m->get_table_row('tbl_gst_setting', $whr2);


		$whr_d= array("company_type"=>"International");
		$data['courier_company'] = $this->basic_operation_m->get_all_result("courier_company",$whr_d);		
		//$data['zone_list']		 = $this->basic_operation_m->get_all_result("zone_master","");
		$data['currency_list']		 = $this->basic_operation_m->get_all_result("tbl_currency","");
		$this->load->view('admin/international_shipment/view_add_international_shipment', $data);
	}


	public function insert_internatioanl_shipment()
	{
		
		$all_Data 	= $this->input->post();	
		if(!empty($all_Data)) 
		{
			if($all_Data['doc_type'] == 0)
			{
				$doc_nondoc			= 'Document';
			}
			else
			{
				$doc_nondoc			= 'Non Document';
			}		
			
			$username  = $this->session->userdata("userName");
			$user_id   = $this->session->userdata("userId");
			$user_type = $this->session->userdata("userType");
			
			$result 		= $this->db->query('select max(booking_id) AS id from tbl_international_booking')->row();
	        $id = $result->id + 1;
	        
			if (strlen($id) == 2) 
			{
	            $id = 'BS1000'.$id;
	        }
			elseif (strlen($id) == 3) 
			{
	            $id = 'BS100'.$id;
	        }
			elseif (strlen($id) == 1) 
			{
	            $id = 'BS10000'.$id;
	        }
			elseif (strlen($id) == 4) 
			{
	            $id = 'BS10'.$id;
	        }
			elseif (strlen($id) == 5) 
			{
	            $id = 'BS1'.$id;
	        }

	        $pod_no =trim($this->input->post('awn'));
	        if($pod_no!="")
	        {
	        	$awb_no = $pod_no;
	        }else{
	        	$awb_no =$id;
	        }
		
		
			$whr = array('username' => $username);
			$res = $this->basic_operation_m->getAll('tbl_users', $whr);
			$branch_id = $res->row()->branch_id;

			$date = date('Y-m-d',strtotime( $this->input->post('booking_date')));
			//$date = $this->input->post('booking_date');
			//booking details//
			$data = array(
				'doc_type'=>$this->input->post('doc_type'),
				//'freight_val'=>$this->input->post('freight_val'),
				'export_import_type'=>$this->input->post('export_import_type'),
				'doc_nondoc'=>$doc_nondoc,
				'courier_company_id'=>$this->input->post('courier_company'),
				'company_type'=>'International',
				'mode_dispatch' => 'Air',
				'pod_no' => $awb_no,
				'forwording_no' => $this->input->post('forwording_no'),
				'forworder_name' => $this->input->post('forworder_name'),
				'customer_id' => $this->input->post('customer_account_id'),
				'sender_name' => $this->input->post('sender_name'),
				'sender_address' => $this->input->post('sender_address'),
				'sender_city' => $this->input->post('sender_city'),
				'sender_state'=> $this->input->post('sender_state'),
				'sender_pincode' => $this->input->post('sender_pincode'),
				'sender_contactno' => $this->input->post('sender_contactno'),
				'type_of_doc'=>$this->input->post('type_of_doc'),
				'sender_gstno' => $this->input->post('sender_gstno'),
				'currency'=>$this->input->post('currency'),
				'invoice_no'=>$this->input->post('invoice_no'),
				'invoice_value' => $this->input->post('invoice_value'),
				'eway_no' => $this->input->post('eway_no'),

				'reciever_name' => $this->input->post('reciever_name'),
				'contactperson_name' => $this->input->post('contactperson_name'),				
				'reciever_address' => $this->input->post('reciever_address'),
				'reciever_contact' => $this->input->post('reciever_contact'),
				'reciever_email' => $this->input->post('reciever_email'),
				'reciever_country_id' => $this->input->post('reciever_country_id'),
				'reciever_city' => $this->input->post('reciever_city'),
				'reciever_zone_id' => $this->input->post('reciever_zone_id'),				
				'reciever_zipcode' => $this->input->post('reciever_zipcode'),								
				'special_instruction' => $this->input->post('special_instruction'),
				//'type_of_pack' => $this->input->post('type_of_pack'),
				'booking_date' =>$date,
				'dispatch_details' => $this->input->post('dispatch_details'),
                'payment_method' => $this->input->post('payment_method'),
				'frieht' => $this->input->post('frieht'),
				'transportation_charges' => $this->input->post('transportation_charges'),
				'destination_charges' => $this->input->post('destination_charges'),
				'clearance_charges' => $this->input->post('clearance_charges'),
				'ecs' => $this->input->post('ecs'),
				'other_charges' => $this->input->post('other_charges'),
				
				'total_amount' => $this->input->post('amount'),
				'fuel_subcharges' => $this->input->post('fuel_subcharges'),	
				'sub_total' => $this->input->post('sub_total'),
                'cgst' => $this->input->post('cgst'),
                'sgst' => $this->input->post('sgst'),
				'igst' => $this->input->post('igst'),			
				'grand_total' => $this->input->post('grand_total'),	
				'user_id' =>$user_id,
				'user_type' =>$user_type,				
				'branch_id' => $branch_id,
				'booking_type'=>1,
				
				);
			
			$query = $this->basic_operation_m->insert('tbl_international_booking', $data);
		
				$lastid = $this->db->insert_id();
				if(empty($lastid))
				{
					
					$data['error'][] = "Already Exist ". $this->input->post('awn').'<br>';	
				}
				else
				{	
					$lastid = $this->db->insert_id();
						
						$data2 = array(
						'booking_id' => $lastid,
						'actual_weight' => $this->input->post('actual_weight'),
						'valumetric_weight' => $this->input->post('valumetric_weight'),
						'length' => $this->input->post('length'),
						'breath' => $this->input->post('breath'),
						'height' => $this->input->post('height'),					
						'chargable_weight' => $this->input->post('chargable_weight'),
						'per_box_weight' => $this->input->post('per_box_weight'),						
						'no_of_pack' => $this->input->post('no_of_pack'),
						'actual_weight_detail' => json_encode($this->input->post('actual_weight_detail[]')),
						'valumetric_weight_detail' => json_encode($this->input->post('valumetric_weight_detail[]')),
						'chargable_weight_detail' => json_encode($this->input->post('chargable_weight_detail[]')),	
						'length_detail' => json_encode($this->input->post('length_detail[]')),
						'breath_detail' => json_encode($this->input->post('breath_detail[]')),
						'height_detail' => json_encode($this->input->post('height_detail[]')),						
						'no_pack_detail' => json_encode($this->input->post('no_pack_detail[]')),
						'per_box_weight_detail' =>json_encode($this->input->post('per_box_weight_detail[]')),						
						);

				$query2 = $this->basic_operation_m->insert('tbl_international_weight_details', $data2);
							
				$username = $this->session->userdata("userName");
				$whr = array('username' => $username);
				$res = $this->basic_operation_m->getAll('tbl_users', $whr);
				$branch_id = $res->row()->branch_id;
				
				$whr = array('branch_id' => $branch_id);
				$res = $this->basic_operation_m->getAll('tbl_branch', $whr);
				$branch_name = $res->row()->branch_name;
							
				$whr = array('booking_id' => $lastid);
				$res = $this->basic_operation_m->getAll('tbl_international_booking', $whr);
				$podno = $res->row()->pod_no;
				$customerid= $res->row()->customer_id;
				$data3 = array('id' => '',
					'pod_no' => $podno,
					'status' => 'Booked',
					'branch_name' => $branch_name,
					'tracking_date' => $date,
					'booking_id' => $lastid,
					'forworder_name' => $data['forworder_name'],
					'forwording_no' => $data['forwording_no'],
					'is_spoton' => ($data['forworder_name'] == 'spoton_service') ? 1 : 0,
					'is_delhivery_b2b' => ($data['forworder_name'] == 'delhivery_b2b') ? 1 : 0,
					'is_delhivery_c2c' => ($data['forworder_name'] == 'delhivery_c2c') ? 1 : 0
				);
				
				$result3 = $this->basic_operation_m->insert('tbl_international_tracking', $data3);
				
				if($this->input->post('customer_account_id')!="")
				{
					$whr = array('customer_id'=>$customerid);
					$res=$this->basic_operation_m->getAll('tbl_customers',$whr);
					$email= $res->row()->email;
				}
				$message='Your Shipment '.$podno.' status:Boked  At Location: '.$branch_name;
			}


					if(!empty($data))
					{						
						$msg			= 'Internal Shipment inserted successfully';
						$class			= 'alert alert-success alert-dismissible';		
					}else{
						$msg			= 'Internal Shipment not inserted successfully';
						$class			= 'alert alert-danger alert-dismissible';	
					}	
					$this->session->set_flashdata('notify',$msg);
					$this->session->set_flashdata('class',$class);
			
			redirect('admin/view-international-shipment');
		}
	}
	public function edit_international_shipment($id) 
	{
		$data['message'] = "";
		 $data['transfer_mode']		 	= $this->basic_operation_m->get_query_result('SELECT * FROM transfer_mode');
		$resAct = $this->basic_operation_m->getAll('city', '');
		if ($resAct->num_rows() > 0) {
			$data['cities'] = $resAct->result_array();
		}
		 $resAct_st						= $this->basic_operation_m->getAll('state', '');
		if($resAct_st->num_rows() > 0) 
		{
            $data['states'] 			= $resAct_st->result_array();
        }
		$whr = array('booking_id' => $id);
		$user_id = $this->session->userdata("userId");
		$user_type = $this->session->userdata("userType");
		if ($id != "") {
			$data['booking'] = $this->basic_operation_m->get_table_row('tbl_international_booking', $whr);
			$data['weight'] = $this->basic_operation_m->get_table_row('tbl_international_weight_details', $whr);
			$user_type 	= $this->session->userdata("userType");
			if($user_type == 1)
			{
				$data['customers'] =$this->basic_operation_m->get_all_result('tbl_customers', "");
			}
			else
			{
				$username = $this->session->userdata("userName");
				 $whr = array('username' => $username);
				 $res = $this->basic_operation_m->getAll('tbl_users', $whr);
				 $branch_id = $res->row()->branch_id;			
				$data['customers'] =$this->basic_operation_m->get_all_result('tbl_customers', "branch_id = '$branch_id'");
			}
		}	
		 $data['payment_method']  = $this->basic_operation_m->get_all_result('payment_method', '');
		$whr_d= array("company_type"=>"International");
		$data['courier_company'] = $this->basic_operation_m->get_all_result("courier_company",$whr_d);	
		$data['country_list'] = $this->basic_operation_m->get_all_result('zone_master');		   
		$data['booking_id']=$id;
		$data['currency_list']= $this->basic_operation_m->get_all_result("tbl_currency","");
	   $this->load->view('admin/international_shipment/view_edit_international_shipment', $data);
	}
	
	public function update_international_shipment($id)
	{
		$all_data 		= $this->input->post();
		if (!empty($all_data)) 
		{
			$whr = array('booking_id' => $id);
			$date = date('Y-m-d',strtotime($this->input->post('booking_date')));
	
				//booking details//
				
				if($this->input->post('doc_type') == 0)
				{
					$doc_nondoc			= 'Document';
				}
				else
				{
					$doc_nondoc			= 'Non Document';
				}
				
			$username = $this->session->userdata("userName");
			$user_id = $this->session->userdata("userId");
			$user_type = $this->session->userdata("userType");
			$whr_u = array('username' => $username);
			$res = $this->basic_operation_m->getAll('tbl_users', $whr_u);
			$branch_id = $res->row()->branch_id;

			$date = date('Y-m-d H:i:s',strtotime( $this->input->post('booking_date')));
			//booking details//
			$data = array(
				'doc_type'=>$this->input->post('doc_type'),
				'doc_nondoc'=>$doc_nondoc,
				'courier_company_id'=>$this->input->post('courier_company'),
				'company_type'=>'International',
				'mode_dispatch' => 'Air',
				'pod_no' => $this->input->post('awn'),
				'forwording_no' => $this->input->post('forwording_no'),
				'forworder_name' => $this->input->post('forworder_name'),
				'customer_id' => $this->input->post('customer_account_id'),
				'sender_name' => $this->input->post('sender_name'),
				'sender_address' => $this->input->post('sender_address'),
				'sender_city' => $this->input->post('sender_city'),
				'sender_state'=> $this->input->post('sender_state'),
				'sender_pincode' => $this->input->post('sender_pincode'),
				'sender_contactno' => $this->input->post('sender_contactno'),
				'type_of_doc'=>$this->input->post('type_of_doc'),
				'sender_gstno' => $this->input->post('sender_gstno'),
				'currency'=>$this->input->post('currency'),
				'invoice_no'=>$this->input->post('invoice_no'),
				'invoice_value' => $this->input->post('invoice_value'),
				'eway_no' => $this->input->post('eway_no'),

				'reciever_name' => $this->input->post('reciever_name'),
				'contactperson_name' => $this->input->post('contactperson_name'),				
				'reciever_address' => $this->input->post('reciever_address'),
				'reciever_contact' => $this->input->post('reciever_contact'),
				'reciever_email' => $this->input->post('reciever_email'),
				'reciever_country_id' => $this->input->post('reciever_country_id'),
				'reciever_city' => $this->input->post('reciever_city'),
				'reciever_zone_id' => $this->input->post('reciever_zone_id'),
				'reciever_zipcode' => $this->input->post('reciever_zipcode'),
								
				'special_instruction' => $this->input->post('special_instruction'),				
				'booking_date' =>$date,
				'dispatch_details' => $this->input->post('dispatch_details'),
				'payment_method' => $this->input->post('payment_method'),
				
				'frieht' => $this->input->post('frieht'),
				'transportation_charges' => $this->input->post('transportation_charges'),
				'destination_charges' => $this->input->post('destination_charges'),
				'clearance_charges' => $this->input->post('clearance_charges'),
				'ecs' => $this->input->post('ecs'),
				'other_charges' => $this->input->post('other_charges'),
				
				'total_amount' => $this->input->post('amount'),
				'fuel_subcharges' => $this->input->post('fuel_subcharges'),	
				'sub_total' => $this->input->post('sub_total'),
                'cgst' => $this->input->post('cgst'),
                'sgst' => $this->input->post('sgst'),
				'igst' => $this->input->post('igst'),			
				'grand_total' => $this->input->post('grand_total'),	
				'user_id' =>$user_id,
				'user_type' =>$user_type,				
				'branch_id' => $branch_id,
				'booking_type'=>1,
				
				);
                $query = $this->basic_operation_m->update('tbl_international_booking', $data, $whr);           

            $check_invoice_pod = $this->basic_operation_m->get_table_row('tbl_international_invoice_detail', $whr);
           // echo $this->db->last_query();
             if(!empty($check_invoice_pod) ){
                    $data_invoice_details = array(                
                        'frieht' => $this->input->post('frieht'),
                        'transportation_charges' => $this->input->post('transportation_charges'),
                        'destination_charges' => $this->input->post('destination_charges'),
                        'clearance_charges' => $this->input->post('clearance_charges'),
                        'ecs' => $this->input->post('ecs'),
                        'other_charges' => $this->input->post('other_charges'),
                        'amount' => $this->input->post('amount'),
                        'fuel_subcharges' => $this->input->post('fuel_subcharges'), 
                        'sub_total' => $this->input->post('sub_total'),                
                        );
                    $query = $this->basic_operation_m->update('tbl_international_invoice_detail', $data_invoice_details, $whr);

           }
           //echo "<br>==".$this->db->last_query();
           //exit;
             			
//========

				$data2 = array(
				'actual_weight' => $this->input->post('actual_weight'),
				'valumetric_weight' => $this->input->post('valumetric_weight'),
				'length' => $this->input->post('length'),
				'breath' => $this->input->post('breath'),
				'height' => $this->input->post('height'),					
				'chargable_weight' => $this->input->post('chargable_weight'),
				'per_box_weight' => $this->input->post('per_box_weight'),
				// 'rate' => $this->input->post('rate'),
				// 'rate_pack' => $this->input->post('rate_pack'),
				'no_of_pack' => $this->input->post('no_of_pack'),
				'actual_weight_detail' => json_encode($this->input->post('actual_weight_detail[]')),
				'valumetric_weight_detail' => json_encode($this->input->post('valumetric_weight_detail[]')),
				'chargable_weight_detail' => json_encode($this->input->post('chargable_weight_detail[]')),
				'length_detail' => json_encode($this->input->post('length_detail[]')),
				'breath_detail' => json_encode($this->input->post('breath_detail[]')),
				'height_detail' => json_encode($this->input->post('height_detail[]')),
				'no_pack_detail' => json_encode($this->input->post('no_pack_detail[]')),
				'per_box_weight_detail' =>json_encode($this->input->post('per_box_weight_detail[]')),
				//'roundoff_type' =>$this->input->post('roundoff_type')
				);

				$query2 = $this->basic_operation_m->update('tbl_international_weight_details', $data2, $whr);
			
				$username = $this->session->userdata("userName");
				$whr = array('username' => $username);
				$res = $this->basic_operation_m->getAll('tbl_users', $whr);
				$branch_id = $res->row()->branch_id;
				
				$whr = array('branch_id' => $branch_id);
				$res = $this->basic_operation_m->getAll('tbl_branch', $whr);
				$branch_name = $res->row()->branch_name;
							
				$whr = array('booking_id' => $id);
				$res = $this->basic_operation_m->getAll('tbl_international_booking', $whr);
				$podno = $res->row()->pod_no;
				$customerid= $res->row()->customer_id;
				// $data3 = array('id' => '',
				// 	'pod_no' => $podno,
				// 	'status' => 'Booked',
				// 	'branch_name' => $branch_name,
				// 	'tracking_date' => $date,
				// 	'booking_id' => $id,
				// 	'forworder_name' => $data['forworder_name'],
				// 	'forwording_no' => $data['forwording_no'],
				// 	'is_spoton' => ($data['forworder_name'] == 'spoton_service') ? 1 : 0,
				// 	'is_delhivery_b2b' => ($data['forworder_name'] == 'delhivery_b2b') ? 1 : 0,
				// 	'is_delhivery_c2c' => ($data['forworder_name'] == 'delhivery_c2c') ? 1 : 0
				// );
				
				// $result3 = $this->basic_operation_m->insert('tbl_international_tracking', $data3);
			
			//$query2 = $this->basic_operation_m->update('tbl_weight_details', $data2, $whr);
		
			if ($this->db->affected_rows() > 0) 
			{
				$data['message'] = "Data added successfull";
			}
			else 
			{
				$data['message'] = "Failed to Submit";
			}
	
	    	redirect('admin/view-international-shipment');
		}
	}


	// public function bulk_upload() 
	// {
		
	// 	$data = [];
	// 	$user_id = $this->session->userdata("userId");
	// 	$user_type = $this->session->userdata("userType");
		
	// 	 if ($this->session->userdata("userType") == 5) {
	// 			$resAct = $this->basic_operation_m->getAll('tbl_customers', "customer_type = 'b2b' AND user_id=$user_id");
	// 		} 
	// 		else if($this->session->userdata("userType") == 4)
	// 		{
	// 			$resAct = $this->basic_operation_m->getAll('tbl_customers', "user_id=$user_id");

	// 		}
	// 		else {
	// 			$resAct = $this->basic_operation_m->getAll('tbl_customers', '');
	// 		}
			
	  
	// 	if ($resAct->num_rows() > 0) {
	// 		$data['customers'] = $resAct->result_array();
	// 	}
		
	// 	if ($this->input->post('submit')) {
	// 		$path = 'uploads/';
	// 		$this->load->library("excel");
	// 		$config['upload_path'] = $path;
	// 		$config['allowed_types'] = 'xlsx|xls|csv';
	// 		$config['remove_spaces'] = TRUE;
	// 		$this->load->library('upload', $config);
	// 		$this->upload->initialize($config);            
	// 		if (!$this->upload->do_upload('uploadFile')) {
	// 			$error = array('error' => $this->upload->display_errors());
	// 		} else {
	// 			$data = array('upload_data' => $this->upload->data());
	// 		}
	// 		if(empty($error)) {
	// 		  if (!empty($data['upload_data']['file_name'])) {
	// 			$import_xls_file = $data['upload_data']['file_name'];
	// 		} else {
	// 			$import_xls_file = 0;
	// 		}
	// 		$inputFileName = $path . $import_xls_file;
			
	// 		$username = $this->session->userdata("userName");
	// 		$whr = array('username' => $username);
	// 		$res = $this->basic_operation_m->getAll('tbl_users', $whr);
	// 		$branch_id = $res->row()->branch_id;
			
	// 		$whr = array('branch_id' => $branch_id);
	// 		$res = $this->basic_operation_m->getAll('tbl_branch', $whr);
	// 		$branch_name = $res->row()->branch_name;
			
	// 		try {
				
	// 			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
	// 			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
	// 			$objPHPExcel = $objReader->load($inputFileName);
	// 			$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
	// 			$flag = true;
	// 			$i = 0;
	// 			foreach ($allDataInSheet as $value) 
	// 			{
	// 				$i++;
	// 				if ($i == 1) continue;
				 
	// 				$result 	= $this->db->query('select max(booking_id) AS id from tbl_booking')->row();
	// 				$id 		= $result->id + 1;
					   
	// 				if (strlen($id) == 2) {
	// 					$id = 'EFS1000' . $id;
	// 				} else if (strlen($id) == 3) {
	// 					$id = 'EFS100' . $id;
	// 				} else if (strlen($id) == 1) {
	// 					$id = 'EFS10000' . $id;
	// 				} else if (strlen($id) == 4) {
	// 					$id = 'EFS10' . $id;
	// 				} else if (strlen($id) == 5) {
	// 					$id = 'EFS1' . $id;
	// 				}
					
				  
		
	// 		    	$date = date('Y-m-d H:i:s');
	// 				//$customerId = $this->input->post('customer_account_id');
	// 				$customerId = $value['U'];
					
	// 				$customerCondtion = array('cid' => $customerId);
	// 				$customerRes = $this->basic_operation_m->getAll('tbl_customers', $customerCondtion);
	// 				$customerId			= $customerRes->row()->customer_id;
					
	// 				$pincode 					=  $this->basic_operation_m->get_table_row('pincode',"pin_code = '".$value['C']."'");
				
	// 				$receiverCityDataCondition  = ['city' => $pincode->city]; 
				   
	// 				$receiverCityRes = $this->basic_operation_m->getAll('city', $receiverCityDataCondition);
					
				
	// 				//print_r($receiverCityRes->row());exit;
					
	// 				if(empty($receiverCityRes->row())) 
	// 				{
	// 					$data['error'][] = "Invalid city ".$value['C']."<br/>";
	// 					//echo $this->db->last_query();
	// 					echo "Invalid city ".$value['C']."<br/>";
	// 					//$query = $this->basic_operation_m->insert('temp_pincode', array('pincode'=>$value['C']));
						
				
	// 					continue;
	// 				}
					
	// 				$chargeble_weight 			= $value['M'];
	// 				$value['M']					= ceil($value['M']);
	// 				$chargeAbleWeight 			= ($value['L'] > $value['M']) ? $value['L'] : $value['M'];
					
				
					
					
	// 				$rateMaster = $this->getBulkUploadRateMasterDetails($customerId, $customerRes->row()->state,$customerRes->row()->city, $receiverCityRes->row()->state_id,$receiverCityRes->row()->id, $value['N'],$value['K'],$chargeAbleWeight,$value['T']);
					
		
				  
	// 				if(empty($rateMaster['rate_master']->rate_master_id)) {
	// 					$data['error'][] = "Rate master not set  for city ".$value['C']."<br/>";
	// 					continue;
	// 				}
					
				
					
	// 				$cft 				= $rateMaster['rate_master']->cft;
	// 				$rate 				= $rateMaster['rate_master']->rate;
	// 				$dod_doac 			= $rateMaster['rate_master']->dod_doac;
	// 				$loading_unloading 	= $rateMaster['rate_master']->loading_unloading;
	// 				$fov 				= $rateMaster['rate_master']->fov;
	// 				$fuel_charges 		= $rateMaster['rate_master']->fuel_charges;
	// 				$min_freight 		= $rateMaster['rate_master']->min_freight;
	// 				$min_freight 		= $rateMaster['rate_master']->min_freight;
	// 				$gst_rate 			= $rateMaster['rate_master']->gst_rate;
	// 				$weight_range 		= $rateMaster['rate_master']->weight_range;
	// 				$fc_type			= $rateMaster['rate_master']->fc_type;
					
					
					
	// 				if($value['T'] == 'doc')
	// 				{
	// 					$doc_nondoc			= 'Document';
	// 				}
	// 				else
	// 				{
	// 					$doc_nondoc			= 'Non Document';
	// 				}
			
	// 				//booking details//
					
	// 				$bookingData = array(
	// 					'booking_id' => "",
	// 					'sender_name' => $customerRes->row()->customer_name,
	// 					'sender_address' => $customerRes->row()->address,
	// 					'sender_city' => $customerRes->row()->city,
	// 					'sender_pincode' => $customerRes->row()->pincode,
	// 					'sender_contactno' => $customerRes->row()->phone,
	// 					'sender_gstno' => $customerRes->row()->gstno,
	// 					'reciever_name' => $value['A'],
	// 					'reciever_address' => $value['B'],
	// 					'reciever_city' => $receiverCityRes->row()->id,
	// 					'reciever_pincode' => $value['C'],
	// 					'reciever_contact' => $value['D'],
	// 					'receiver_gstno' => ($value['E'])?$value['E']:'',
	// 					'booking_date' => !empty($value['S']) ? date('Y-m-d H:i:s', strtotime($value['S'])) : $date,
	// 					'delivery_date' => $rateMaster['rate_master']->eod,
	// 					'mode_dispatch' => ucfirst($value['N']),
	// 					'dispatch_details' => strtolower($value['O']),
	// 					'insurance_value' => $value['J'],
	// 					'eway_no'         => !empty($value['F']) ? $value['F'] : '',
	// 					'ref_no'         => !empty($value['G']) ? $value['G'] : '',
	// 					'contactperson_name' => !empty($value['H']) ? $value['H'] : '',
	// 					'forwording_no' => !empty($value['P']) ? $value['P'] : '',
	// 					'forworder_name' => !empty($value['Q']) ? $value['Q'] : '',
	// 					'pod_no' => !empty($value['R']) ? $value['R'] : $id,
	// 					'branch_id' => $branch_id,
	// 					'customer_id' => $customerId,
	// 					'gst_charges' => 1,
	// 					'invoice_no' => $value['I'],
	// 					'status' => 1,
	// 					'create_shipment' => 1,
	// 					'user_id' =>$user_id,
	// 					'user_type' =>$user_type,
	// 					'length_unit' =>'cm',
	// 					'rate' => $rate,
	// 					'gst_rate' =>$gst_rate,
	// 					'delivery_type' =>$this->input->post('delivery_type'),
	// 					'minimum_amount' =>$this->input->post('minimum_amount'), 
	// 					'a_weight' => $chargeAbleWeight,
	// 					'a_qty' => $value['K'],
	// 					'doc_nondoc' =>$doc_nondoc, 
	// 					);
					

	// 			$query = $this->basic_operation_m->insert('tbl_booking', $bookingData);
	// 			$lastid = $this->db->insert_id();
	// 			if(empty($lastid))
	// 			{
	// 				$podd = !empty($value['R']) ? $value['R'] : $id;
	// 				$data['error'][] = "Pod Already Exist ".$podd.'<br>';	
	// 			}
	// 			else
	// 			{
	// 				$data['success'] = "Pod Successfully Inserted";	
	// 				$tracking = array(
	// 					'id' => "",
	// 					'pod_no' => !empty($value['R']) ? $value['R'] : $id,
	// 					'status' =>'booked',
	// 					'branch_name' => $branch_name,
	// 					'booking_id' => $lastid,
	// 					'forwording_no' => !empty($value['P']) ? $value['P'] : '',
	// 					'forworder_name' => !empty($value['Q']) ? $value['Q'] : '',
	// 					'tracking_date' => !empty($value['S']) ? date('Y-m-d H:i:s', strtotime($value['S'])) : $date,
	// 					);
						
	// 				$query = $this->basic_operation_m->insert('tbl_tracking', $tracking);
				
				
				
	// 			   // echo ">>".$last_id;
	// 				//print_r($this->db); exit;
					
					
					
					
	// 				//print_r($this->db); exit;
					
	// 				$dbError = $this->db->error();
	// 				if(!empty($dbError['message'])) {
	// 					$data['error'][] = $dbError['message'].' <br/>'; 
	// 					continue;
	// 				}
					
	// 				$chargeble_weight 			= $value['M'];
	// 				$value['M']					= ceil($value['M']);
	// 				$chargeAbleWeight 			= ($value['L'] > $value['M']) ? $value['L'] : $value['M'];
	// 				$chargeAbleWight 			= $weight_range > $chargeAbleWeight ? $weight_range : $chargeAbleWeight;
	// 				$frieght 					= $chargeAbleWight * $rate;
					
	// 				$frieght 					= $frieght < $min_freight ? $min_freight : $frieght;
					
	// 				if($fov > '0' && $value['J'] > '0' ) 
	// 				{
	// 					$fov = (($fov * $value['J'])/100);
	// 				}
	// 				else 
	// 				{
	// 					$fov = 0;
	// 				}
					
	// 				$amount =  $frieght + $dod_doac + $fov; 
					
	// 				if (!empty($fc_type)) 
	// 				{
	// 						if($fc_type == 'freight')
	// 						{
	// 						   $fuel_charges = (($frieght * $fuel_charges) / 100);
	// 						}
	// 						else if($fc_type == 'total')
	// 						{
	// 							$fuel_charges = (($amount * $fuel_charges) / 100);
	// 						}    
	// 					}
	// 					else
	// 					{
	// 						$fuel_charges = 0;
	// 					}
					
	// 				$total_charges = $amount + $fuel_charges;
	// 				if($min_freight > $total_charges || $total_charges == '')
	// 				{
	// 					$total_charge = $min_freight;
	// 				}
	// 				else 
	// 				{
	// 					$total_charge = ($amount) + $fuel_charges;
	// 				}
	// 				$gst = $gst_rate / 2;
	// 				$cgst = $sgst = $igst = 0;
	// 				$finalAmount = 0; 
	// 				if (!empty($customerRes->row()->gstno)) 
	// 				{
	// 					$stateCode = substr($customerRes->row()->gstno ,0, 2);
	// 					if($stateCode == '13')
	// 					{
	// 						$sgst = $total_charge * $gst / 100;
	// 						$cgst = $total_charge * $gst / 100;
	// 						$finalAmount = $total_charge + $sgst + $cgst;
							
	// 					}
	// 					else 
	// 					{
	// 						$igst = ($total_charge * $gst_rate) / 100;
	// 						$finalAmount = $total_charge + $igst;
	// 					}
	// 				}
	// 				$totgst =  $sgst + $cgst;
				 
	// 				$parcel_type		= $value['T'];
	// 				if($parcel_type == 'doc')  // this is for document rate 
	// 				{
					
	// 					//charges Details/
	// 					$data1 = array(
	// 						'payment_id' => '',
	// 						'booking_id' => $lastid,
	// 						'amount' => $rateMaster['rate_master']->rate,
	// 						'frieht' => $rateMaster['rate_master']->rate,
	// 						'awb' => !empty($value['R']) ? $value['R'] : $id,
	// 						'to_pay' => 0,
	// 						'dod_daoc' => $rateMaster['rate_master']->dod_doac,
	// 						'loading' => 0,
	// 						'packing' => 0,
	// 						'handling' => 0,
	// 						'oda' => 0,
	// 						'fov' => 0,
	// 						'sub_total' => $rateMaster['rate_master']->rate,
	// 						'fuel_subcharges' => 0,
	// 						'apmt' => '',
	// 						'IGST' => $rateMaster['rate_master']->igst,
	// 						'CGST' => $rateMaster['rate_master']->cgst,
	// 						'SGST' => $rateMaster['rate_master']->sgst,
	// 						'total_amount' => number_format($rateMaster['rate_master']->rate + $rateMaster['rate_master']->cgst + $rateMaster['rate_master']->sgst + $rateMaster['rate_master']->igst,2),
	// 						'demurrage' => 0,
	// 						'cod' => '',
	// 						'other_charges' => 0
	// 						);
	// 				}
	// 				else
	// 				{
	// 					//charges Details/
	// 					$data1 = array(
	// 						'payment_id' => '',
	// 						'booking_id' => $lastid,
	// 						'amount' => $amount,
	// 						'frieht' => $frieght,
	// 						'awb' => !empty($value['R']) ? $value['R'] : $id,
	// 						'to_pay' => 0,
	// 						'dod_daoc' => $dod_doac,
	// 						'loading' => $loading_unloading,
	// 						'packing' => 0,
	// 						'handling' => 0,
	// 						'oda' => 0,
	// 						'fov' => $fov,
	// 						'sub_total' => $total_charge,
	// 						'fuel_subcharges' => $fuel_charges,
	// 						'apmt' => '',
	// 						'IGST' => $igst,
	// 						'CGST' => $cgst,
	// 						'SGST' => $sgst,
	// 						'total_amount' => $finalAmount,
	// 						'demurrage' => 0,
	// 						'cod' => '',
	// 						'other_charges' => 0
	// 						);
	// 				}
					
					   
	// 				$data2 = array(
	// 					'weight_details_id' => '',
	// 					'booking_id' => $lastid,
	// 					'actual_weight' => $value['L'],
	// 					'valumetric_weight' => $chargeble_weight,
	// 					'length' => $value['O'],
	// 					'breath' => $value['P'],
	// 					'height' => $value['Q'],
	// 					'one_cft_kg' => ($cft)?$cft:1,
	// 					'chargable_weight' => $chargeAbleWight,
	// 					'per_box_weight' => $value['L'],
	// 					'rate' => $rate,
	// 					'rate_type' => 'weight',
	// 					'rate_pack' => '',
	// 					'no_of_pack' => $value['K'],
	// 					'type_of_pack' => '',
	// 					'special_instruction' => '',
	// 					'actual_weight_detail' => json_encode([$value['L']]),
	// 					'valumetric_weight_detail' => json_encode([$value['M']]),
	// 					'no_pack_detail' => json_encode([$value['K']]),
	// 					'per_box_weight_detail' =>json_encode([$value['L']]),
	// 					);
						
						
					   

	// 				$query1 = $this->basic_operation_m->insert('tbl_charges', $data1);
					
	// 				$query2 = $this->basic_operation_m->insert('tbl_weight_details', $data2);
					
				
	// 				$whr = array('booking_id' => $lastid);
	// 				$res = $this->basic_operation_m->getAll('tbl_booking', $whr);
	// 				$podno = $res->row()->pod_no;
	// 				$customerid= $res->row()->customer_id;
	// 			   /*  $data3 = array('id' => '',
	// 					'pod_no' => $podno,
	// 					'status' => 'booked',
	// 					'branch_name' => $branch_name,
	// 					'tracking_date' => date('Y-m-d H:i:s'),
	// 					'booking_id' => $lastid,
	// 					'is_spoton' =>  0
	// 					);
	// 					 $result3 = $this->basic_operation_m->insert('tbl_tracking', $data3); */
						
	// 			}
	// 			  $i++;
	// 				if($lastid > 0) 
	// 				{
	// 					//$data['success'][] = 'Data uploaded successfully for booking Id '.$lastid.'<br/>';
	// 				}    
	// 			} 
	// 	  } catch (Exception $e) {
	// 		   die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
	// 					. '": ' .$e->getMessage());
	// 		}
	// 	  } else {
	// 		 $data['error'][] = $error['error'];
	// 		}
	// 	}
		
		
		
	// 	$this->load->view('admin/shipment/view_bluck_uploads_hipment', $data);
	// }

	// public function addpodnew() 
	// {
		   
	// 		$result = $this->db->query('select max(booking_id) AS id from tbl_booking')->row();
	// 		//print_r($result);die;
	// 		$id = $result->id + 1;
	// 		if (strlen($id) == 2) {
	// 			$id = 'AWN00' . $id;
	// 		} else if (strlen($id) == 3) {
	// 			$id = 'AWN0' . $id;
	// 		} else if (strlen($id) == 1) {
	// 			$id = 'AWN000' . $id;
	// 		} else if (strlen($id) == 4) {
	// 			$id = 'AWN' . $id;
	// 		}
	// 		$data['message'] = "";
			
	// 		$resAct = $this->db->query("select * from setting");
	// 		$setting = $resAct->result();
	// 		foreach ($setting as $value):
	// 			$data[$value->key] = $value->value;
	// 		endforeach;
	// 		$resAct = $this->basic_operation_m->getAll('tbl_city', '');
	// 		if ($resAct->num_rows() > 0) {
	// 			$data['cities'] = $resAct->result_array();
	// 		}
	// 		$resAct = $this->basic_operation_m->getAll('tbl_customers', '');

	// 		if ($resAct->num_rows() > 0) {
	// 			$data['customers'] = $resAct->result_array();
	// 		}
			
	// 		$user_id = $this->session->userdata("userId");
	// 		$user_type = $this->session->userdata("userType");

	// 		if (isset($_POST['submit'])) {
	// 			$username = $this->session->userdata("userName");
	// 			$whr = array('username' => $username);
	// 			$res = $this->basic_operation_m->getAll('tbl_users', $whr);
	// 			$branch_id = $res->row()->branch_id;

	// 			$date = date('Y-m-d H:i:s',strtotime( $this->input->post('booking_date')));
	// 			//booking details//
	// 			$data = array(
	// 				'booking_id' => "",
	// 				'sender_name' => $this->input->post('sender_name'),
	// 				'sender_address' => $this->input->post('sender_address'),
	// 				'sender_city' => $this->input->post('sender_city'),
	// 				'sender_pincode' => $this->input->post('sender_pincode'),
	// 				'sender_contactno' => $this->input->post('sender_contactno'),
	// 				'sender_gstno' => $this->input->post('sender_gstno'),
	// 				'reciever_name' => $this->input->post('reciever_name'),
	// 				'reciever_address' => $this->input->post('reciever_address'),
	// 				'reciever_city' => $this->input->post('reciever_city'),
	// 				'reciever_pincode' => $this->input->post('reciever_pincode'),
	// 				'reciever_contact' => $this->input->post('reciever_contact'),
	// 				'receiver_gstno' => $this->input->post('receiver_gstno'),
	// 				'booking_date' =>$date,
	// 				'delivery_date' => $this->input->post('delivery_date'),
	// 				'mode_dispatch' => $this->input->post('mode_dispatch'),
	// 				'dispatch_details' => $this->input->post('dispatch_details'),
	// 				'insurance_value' => $this->input->post('insurace_value'),
	// 				'forwording_no' => $this->input->post('forwording_no'),
	// 				'forworder_name' => $this->input->post('forworder_name'),
	// 				'pod_no' => $this->input->post('awn'),
	// 				'branch_id' => $branch_id,
	// 				'customer_id' => $this->input->post('customer_account_id'),
	// 				'gst_charges' => $this->input->post('gst_charges'),
	// 				'status' => ($this->input->post('status')) ? 1 : 0,
	// 				'booking_type' => 2,
	// 				'user_id' =>$user_id,
	// 				'user_type' =>$user_type,
	// 				);
	// 		   // print_r($data);die;
	// 	$query = $this->basic_operation_m->insert('tbl_booking', $data);
	// 	// echo $this->db->last_query();die;
	// 	$lastid = $this->db->insert_id();
		
	// 				//charges Details/
	// 				//total amount 
	// 	$frieht = $this->input->post('frieht');
	// 	$awb = $this->input->post('awn');
	// 	$topay = $this->input->post('to_pay');
	// 	$daoc = $this->input->post('dod_daoc');
	// 	$loading = $this->input->post('loading');
	// 	$packing = $this->input->post('packing');
	// 	$handling = $this->input->post('handling');
	// 	$oda = $this->input->post('oda');
	// 	$insurance = $this->input->post('insurance');
	// 	$fuel_subcharges = $this->input->post('fuel_subcharges');
	// 	$data1 = array(
	// 		'payment_id' => '',
	// 		'booking_id' => $lastid,
	// 		'amount' => $this->input->post('amount'),
	// 		'frieht' => $this->input->post('frieht'),
	// 		'awb' => $this->input->post('awn'),
	// 		'to_pay' => $this->input->post('to_pay'),
	// 		'dod_daoc' => $this->input->post('dod_daoc'),
	// 		'loading' => $this->input->post('loading'),
	// 		'packing' => $this->input->post('packing'),
	// 		'handling' => $this->input->post('handling'),
	// 		'oda' => $this->input->post('oda'),
	// 		'insurance' => $this->input->post('insurance'),
	// 		'fuel_subcharges' => $this->input->post('fuel_subcharges'),
	// 					//'service_tax'=>$this->input->post('service_tax'),
	// 		'IGST' => $this->input->post('igst'),
	// 		'CGST' => $this->input->post('cgst'),
	// 		'SGST' => $this->input->post('sgst'),
	// 		'total_amount' => $frieht,
	// 		);
	// 				//print_r($data1[2]);
	// 				//  weight details
	// 	$length = $this->input->post('length');
	// 	$breath = $this->input->post('breath');
	// 	$height = $this->input->post('height');
	// 	$no_of_pack = $this->input->post('no_of_pack');
	// 	if($no_of_pack == ''){
	// 		$no_of_pack = 1;
	// 	}
		
	// 	$one_cft_kg = $this->input->post('one_cft_kg');
	// 	if($one_cft_kg == ''){
	// 		$one_cft_kg = 1;
	// 	}
		
		
	// 	$valumetric = round( ( ($length * $breath * $height) / 28000 ) * $one_cft_kg * $no_of_pack);
	// 	$chargable_weight = 0;
	// 	if($valumetric > 0){
	// 		$chargable_weight = $valumetric;
	// 	}
		
		
	// 	$data2 = array(
	// 		'weight_details_id' => '',
	// 		'booking_id' => $lastid,
	// 		'actual_weight' => $this->input->post('actual_weight'),
	// 		'valumetric_weight' => $this->input->post('valumetric_weight'),
	// 		'length' => $this->input->post('length'),
	// 		'breath' => $this->input->post('breath'),
	// 		'height' => $this->input->post('height'),
	// 		'one_cft_kg' => $this->input->post('one_cft_kg'),
	// 		'chargable_weight' => $this->input->post('chargable_weight'),
	// 		'rate' => $this->input->post('rate'),
	// 		'rate_type' => $this->input->post('rate_type'),
	// 		'rate_pack' => $this->input->post('rate_pack'),
	// 		'no_of_pack' => $this->input->post('no_of_pack'),
	// 		'type_of_pack' => $this->input->post('type_of_pack'),
	// 		'special_instruction' => $this->input->post('special_instruction'),
	// 		);
		
	// 	$query1 = $this->basic_operation_m->insert('tbl_charges', $data1);
	// 	//echo $this->db->last_query(); die;
		
	// 	$query2 = $this->basic_operation_m->insert('tbl_weight_details', $data2);
		
		
	// 	$username = $this->session->userdata("userName");
	// 	$whr = array('username' => $username);
	// 	$res = $this->basic_operation_m->getAll('tbl_users', $whr);
	// 	$branch_id = $res->row()->branch_id;
		
	// 	$whr = array('branch_id' => $branch_id);
	// 	$res = $this->basic_operation_m->getAll('tbl_branch', $whr);
	// 	$branch_name = $res->row()->branch_name;
		
	// 	//	$query2=$this->basic_operation_m->insert('tbl_weight_details',$data2);
		
		
	// 	$whr = array('booking_id' => $lastid);
	// 	$res = $this->basic_operation_m->getAll('tbl_booking', $whr);
	// 	$podno = $res->row()->pod_no;
	// 	$customerid= $res->row()->customer_id;
	// 	$data3 = array('id' => '',
	// 		'pod_no' => $podno,
	// 		'status' => 'booked',
	// 		'branch_name' => $branch_name,
	// 		'tracking_date' => $date,
	// 		);
		
	// 	$result3 = $this->basic_operation_m->insert('tbl_tracking', $data3);
		
		
		
	// 	// $whr = array('customer_id'=>$customerid);
	// 	// $res=$this->basic_operation_m->getAll('tbl_customers',$whr);
	// 	// $email= $res->row()->email;
	// 	// $message='Your Shipment '.$podno.' status:Boked  At Location: '.$branch_name;
	// 	// $this->sendemail($email,$message);
		
		
	// 	if ($this->db->affected_rows() > 0) {
	// 		$data['message'] = "Data added successfull";
	// 	} else {
	// 		$data['message'] = "Failed to Submit";
	// 	}
	// 	redirect(base_url() . 'generatepod/newbooking');
	// 	}
	// 	$data['bid'] = $id;
	// 	$this->load->view('admin/urgent_shippment/addpodnew', $data);
	// }

	// public function sendemail($to,$message)
	// {

	//    $this->load->library('email');
	//    $config['protocol'] = 'smtp';
	// 	   // $config['mailpath'] = '/usr/sbin/sendmail';

	//    $config['smtp_host'] = 'mail.rajcargo.net';
	//    $config['smtp_user'] = 'info@rajcargo.net';
	//    $config['smtp_pass'] = '41]wHpOZBnq}';
	//    $config['smtp_port'] = 25;
	//    $config['charset'] = 'iso-8859-1';
	//    $config['wordwrap'] = TRUE;

	// //   $this->email->initialize($config);

	//   // $this->email->from('info@rajcargo.net', 'Rajcargo Admin');
	//    //$this->email->to($to); 


	//    //$this->email->subject('Shipment Update');
	//    //$this->email->message($message);	

	//   // $this->email->send();


	// }

	
	// public function updatepodnew() 
	// {

	// 	$data['message'] = "";
	// 	$resAct = $this->basic_operation_m->getAll('tbl_city', '');

	// 	if ($resAct->num_rows() > 0) {
	// 		$data['cities'] = $resAct->result_array();
	// 	}

	// 	$last = $this->uri->total_segments();
	// 	$id = $this->uri->segment($last);
	// 	$whr = array('booking_id' => $id);
	// 	$user_id = $this->session->userdata("userId");
	// 	$user_type = $this->session->userdata("userType");
	// 	if ($id != "") {
	// 		$res = $this->basic_operation_m->getAll('tbl_booking', $whr);

	// 		if ($res->num_rows() > 0) {
	// 			$data['booking'] = $res->result_array();
	// 		}

	// 		$resAct = $this->basic_operation_m->getAll('tbl_charges', $whr);
	// 		if ($resAct->num_rows() > 0) {
	// 			$data['charges'] = $resAct->result_array();
	// 		}

	// 		$resAct = $this->basic_operation_m->getAll('tbl_weight_details', $whr);
	// 		if ($resAct->num_rows() > 0) {
	// 			$data['weight'] = $resAct->result_array();
	// 		}
	// 	}
	// 	if (isset($_POST['submit'])) {
	// 		$last = $this->uri->total_segments();
	// 		$id = $this->uri->segment($last);
	// 		$whr = array('booking_id' => $id);
	// 		$date = date('Y-m-d H:i:s',strtotime($this->input->post('booking_date')));
	// 			//booking details//
	// 		$data = array(
	// 				// 'booking_id'=>$id, gst_rate
	// 			'sender_name' => $this->input->post('sender_name'),
	// 			'sender_address' => $this->input->post('sender_address'),
	// 			'sender_city' => $this->input->post('sender_city'),
	// 			'sender_pincode' => $this->input->post('sender_pincode'),
	// 			'sender_contactno' => $this->input->post('sender_contactno'),
	// 			'sender_gstno' => $this->input->post('sender_gstno'),
	// 			'reciever_name' => $this->input->post('reciever_name'),
	// 			'reciever_address' => $this->input->post('reciever_address'),
	// 			'reciever_city' => $this->input->post('reciever_city'),
	// 			'reciever_pincode' => $this->input->post('reciever_pincode'),
	// 			'reciever_contact' => $this->input->post('reciever_contact'),
	// 			'receiver_gstno' => $this->input->post('receiver_gstno'),
	// 			'booking_date' => $date,
	// 			'delivery_date' => $this->input->post('delivery_date'),
	// 			'mode_dispatch' => $this->input->post('mode_dispatch'),
	// 			'dispatch_details' => $this->input->post('dispatch_details'),
	// 			'insurance_value' => $this->input->post('insurance_value'),
	// 			'forwording_no' => $this->input->post('forwording_no'),
	// 			'forworder_name' => $this->input->post('forworder_name'),
	// 			'gst_charges' => $this->input->post('gst_charges'),
	// 			'status' => ($this->input->post('status')) ? 1 : 0,
	// 			'booking_type' => 2,
	// 			'user_id' =>$user_id,
	// 			'user_type' =>$user_type,
	// 			);

	// 	$query = $this->basic_operation_m->update('tbl_booking', $data, $whr);
	// 	$lastid = $this->db->insert_id();
	// 				//charges Details
	// 				//total amount 
	// 	$frieht = $this->input->post('frieht');
	// 	$awb = $this->input->post('awb');
	// 	$topay = $this->input->post('to_pay');
	// 	$daoc = $this->input->post('dod_daoc');
	// 	$loading = $this->input->post('loading');
	// 	$packing = $this->input->post('packing');
	// 	$handling = $this->input->post('handling');
	// 	$oda = $this->input->post('oda');
	// 	$insurance = $this->input->post('insurance');
	// 	$fuel_subcharges = $this->input->post('fuel_subcharges');
	// 	$data1 = array(
	// 					//'payment_id'=>'',
	// 		'booking_id' => $id,
	// 		'amount' => $this->input->post('amount'),
	// 		'frieht' => $this->input->post('frieht'),
	// 		'awb' => $this->input->post('awb'),
	// 		'to_pay' => $this->input->post('to_pay'),
	// 		'dod_daoc' => $this->input->post('dod_daoc'),
	// 		'loading' => $this->input->post('loading'),
	// 		'packing' => $this->input->post('packing'),
	// 		'handling' => $this->input->post('handling'),
	// 		'oda' => $this->input->post('oda'),
	// 		'insurance' => $this->input->post('insurance'),
	// 		'fuel_subcharges' => $this->input->post('fuel_subcharges'),
	// 					//'service_tax' => $this->input->post('service_tax'),
	// 		'IGST' => $this->input->post('igst'),
	// 		'CGST' => $this->input->post('cgst'),
	// 		'SGST' => $this->input->post('sgst'),
	// 		'total_amount' => $frieht,
	// 		);
		
	// 				//  weight details
	// 				// $last = $this->uri->total_segments();
	// 				// $id= $this->uri->segment($last);
	// 				// $whr =array('booking_id'=>$id);
		
	// 	$length = $this->input->post('length');
	// 	$breath = $this->input->post('breath');
	// 	$height = $this->input->post('height');
		
	// 	$no_of_pack = $this->input->post('no_of_pack');
	// 	if($no_of_pack == ''){
	// 		$no_of_pack = 1;
	// 	}
		
	// 	$one_cft_kg = $this->input->post('one_cft_kg');
	// 	if($one_cft_kg == ''){
	// 		$one_cft_kg = 1;
	// 	}
		
	// 	$valumetric = round( ( ($length * $breath * $height) / 28000 ) * $one_cft_kg * $no_of_pack);
	 
		
	// 	$data2 = array(
	// 					//'weight_details_id'=>'',
	// 		'booking_id' => $id,
	// 		'actual_weight' => $this->input->post('actual_weight'),
	// 		'valumetric_weight' => $this->input->post('valumetric_weight'),
	// 		'length' => $this->input->post('length'),
	// 		'breath' => $this->input->post('breath'),
	// 		'height' => $this->input->post('height'),
	// 		'one_cft_kg' => $this->input->post('one_cft_kg'),
	// 		'chargable_weight' => $this->input->post('chargable_weight'),
	// 		'rate' => $this->input->post('rate'),
	// 		'rate_type' => $this->input->post('rate_type'),
	// 		'rate_pack' => $this->input->post('rate_pack'),
	// 		'no_of_pack' => $this->input->post('no_of_pack'),
	// 		'type_of_pack' => $this->input->post('type_of_pack'),
	// 		'special_instruction' => $this->input->post('special_instruction'),
	// 		);
		
	// 	$query1 = $this->basic_operation_m->update('tbl_charges', $data1, $whr);
		
	// 	$query2 = $this->basic_operation_m->update('tbl_weight_details', $data2, $whr);
		
	// 	if ($this->db->affected_rows() > 0) {
	// 		$data['message'] = "Data added successfull";
	// 	} else {
	// 		$data['message'] = "Failed to Submit";
	// 	}
	// 	redirect(base_url() . 'generatepod/newbooking');
	// 	}
	// 	$this->load->view('admin/urgent_shippment/edit_pod', $data);
	// }

	public function delete_international_shipment() 
	{
		$id = $this->input->post('getid');
		if ($id != "") {
			$whr = array('booking_id' => $id);
			$res = $this->basic_operation_m->delete('tbl_international_booking', $whr);
			$res1 = $this->basic_operation_m->delete('tbl_international_weight_details', $whr);
			$res2= $this->basic_operation_m->delete('tbl_international_tracking', $whr);
		   
		   	$output['status'] = 'success';
			$output['message'] = 'Shipment deleted successfully';
		}
		else{
			$output['status'] = 'error';
			$output['message'] = 'Something went wrong in deleting the Shipment';
		}
 
		echo json_encode($output);	
	}
		
	
	
	
// 	public function delete_international_shipment($id) 
// 	{
		
// 		if ($id != "") {
// 			$whr = array('booking_id' => $id);
// 			$res = $this->basic_operation_m->delete('tbl_international_booking', $whr);
// 			$res1 = $this->basic_operation_m->delete('tbl_international_weight_details', $whr);
// 			$res2= $this->basic_operation_m->delete('tbl_international_tracking', $whr);
// 			$msg= 'Entry deleted successfully';
// 			$class= 'alert alert-danger alert-dismissible';	
// 			$this->session->set_flashdata('notify',$msg);
// 		    $this->session->set_flashdata('class',$class);

// 			redirect('admin/view-international-shipment');
// 		}
// 	}
	
	
	

	// public function allpod() {
	// 	if ($this->session->userdata("userName") != "") {
	// 		$data = array();
	// 		$last = $this->uri->total_segments();
	// 		$id = $this->uri->segment($last);

	// 		$whr = array('booking_id' => $id);
	// 		if ($id != "") {
	// 			$res = $this->db->query("select * from tbl_booking,tbl_city where tbl_booking.sender_city=tbl_city.city_id and tbl_booking.booking_id='$id' ");

	// 			if ($res->num_rows() > 0) {
	// 				$data['basicdetails'] = $res->row();
	// 			}

	// 			$res = $this->db->query("select * from tbl_booking,tbl_city where tbl_booking.reciever_city=tbl_city.city_id and tbl_booking.booking_id='$id' ");

	// 			if ($res->num_rows() > 0) {
	// 				$data['basicdetails1'] = $res->row();
	// 			}

	// 			$resAct = $this->basic_operation_m->getAll('tbl_charges', $whr);
	// 			if ($resAct->num_rows() > 0) {
	// 				$data['paymentdetails'] = $resAct->row();
	// 			}

	// 			$resAct = $this->basic_operation_m->getAll('tbl_weight_details', $whr);
	// 			if ($resAct->num_rows() > 0) {
	// 				$data['weightdetails'] = $resAct->row();
	// 			}
	// 		}

	// 		$this->load->view('printpod', $data);
	// 	} else {
	// 		redirect(base_url() . 'login');
	// 	}
	// }

	public function getsenderdetails() {
		$data = [];  
		$customer_name = $this->input->post('customer_name');

		$whr1 = array('customer_id' => $customer_name);
		$res1 = $this->basic_operation_m->selectRecord('tbl_customers', $whr1);
		$result1 = $res1->row();
		$data['user'] = $result1;
		echo json_encode($data);
		exit;
	}
	public function check_duplicate_forwording_no() {
        $data = [];  
        $forwording_no = $this->input->post('forwording_no');
        $whr = array('forwording_no' => $forwording_no);
        $result = $this->basic_operation_m->get_table_row('tbl_international_booking', $whr);

        $forwording_no = $result->forwording_no;
        if($forwording_no!="")
        {
            $data['msg'] = "Forwording number is duplicate ";
        }else{
            $data['msg'] = "";
        }

        echo json_encode($data);
        exit;
    }

	public function getRateMasterDetails() 
	{
    
		$data			= [];       
		
		$customer_name 	= $this->input->post('customer_name');
		$sender_city 	= $this->input->post('sender_city');
		$receiver_city 	= $this->input->post('receiver_city');
		$mode_dispatch 	= ucfirst($this->input->post('mode_dispatch'));
		
		$region_query 	= $this->db->query("SELECT `state`.`region_id`,`state`.`id` as state_id,`state`.`edd_train`,`state`.`edd_air`, `state`.`edd_air` FROM `state` join city ON `city`.`state_id` = `state`.`id` WHERE `city`.`id` = ".$receiver_city);
		
		if ($region_query->num_rows() > 0) 
		{
			$regionData 	= $region_query->row();
			$region_id 		= $regionData->region_id;
			$state_id 		= $regionData->state_id;
			$eod 			= ($mode_dispatch == 'air') ? $regionData->edd_air : $regionData->edd_air;
			$eod 			= $this->addBusinessDays(date("d-m-Y"),!empty($regionData->eod) ? $regionData->eod : 4);
		}

		if (!empty($region_id)) 
		{
			$data['rate_master'] = new \stdClass();
			
			$res = $this->db->query("select * from tbl_rate_master where customer_id=".$customer_name." AND mode_of_transport='".$mode_dispatch."' AND region_id=".$region_id." LIMIT 1");
			if ($res->num_rows() > 0) 
			{
				$data['rate_master'] = $res->row();
			  
				// check rate available for state table
				$stateMasterRes = $this->db->query("select * from tbl_rate_state where rate_master_id=".$data['rate_master']->rate_master_id." AND state_id =".$state_id." LIMIT 1");
				
				if($stateMasterRes->num_rows() > 0){
					$stateMasterData = $stateMasterRes->row();
					$data['rate_master']->rate = $stateMasterData->rate;
					$data['rate_master']->to_pay_charges = $stateMasterData->to_pay_charges;
					$data['rate_master']->cod = $stateMasterData->cod;
					$data['rate_master']->edd = $stateMasterData->edd;
				}
				
				
				//check rate available for city table
				$cityMasterRes = $this->db->query("select * from tbl_rate_city where rate_master_id=".$data['rate_master']->rate_master_id." AND city_id =".$receiver_city." LIMIT 1");
				if ($cityMasterRes->num_rows() > 0) {
					$cityMasterData = $cityMasterRes->row();
					$data['rate_master']->rate = $cityMasterData->rate;
					$data['rate_master']->to_pay_charges = $cityMasterData->to_pay_charges;
					$data['rate_master']->cod = $cityMasterData->cod;
					$data['rate_master']->edd = $cityMasterData->edd;
				}
				
				if($this->input->post('no_of_pack') > 0 && $this->input->post('rate_type') == 'no_of_pack') 
				{
					$rate_master_id 	= $data['rate_master']->rate_master_id;
					$no_of_pack 		= $this->input->post('no_of_pack');
					$rate_master_query 	= $this->db->query("SELECT * FROM `tbl_rate_pack` WHERE rate_master_id = ".$rate_master_id." AND $no_of_pack BETWEEN `from` AND `to` LIMIT 1");
				
					if($rate_master_query->num_rows() > 0) 
					{
						$data['rate_master_pack'] = $rate_master_query->row();
					}
				   
				}
			}
         $data['rate_master']->eod = $eod;
      //echo '<pre>';  print_r($data);exit;
		}
		echo json_encode($data);
		exit;
	}

	public function getBulkUploadRateMasterDetails($customer_name,$sender_state,$sender_city,$receiver_state,$receiver_city,$mode_dispatch,$qty,$chargeAbleWight,$parcel_type) 
	{
	   
		$data = [];
		$sql = "SELECT `state`.`region_id`,`state`.`id`,`state`.`edd_train`,`state`.`edd_air`, `state`.`edd_air` FROM `state` join city ON `city`.`state_id` = `state`.`id` WHERE `city`.`id` = ".$receiver_city;
		$region_query = $this->db->query($sql);
		
		if ($region_query->num_rows() > 0) 
		{
			$regionData 	= $region_query->row();
			$region_id 		= $regionData->region_id;
			$state_id 		= $regionData->id;
			$eod 			= ($mode_dispatch == 'air') ? $regionData->edd_air : $regionData->edd_air;
			$eod 			= $this->addBusinessDays(date("d-m-Y"),!empty($regionData->eod) ? $regionData->eod : 4);
		}
		
		if($parcel_type != 'doc')  // this is for non document rate 
		{
		
			if(!empty($region_id)) 
			{
				$data['rate_master'] = new \stdClass();
				
				$res = $this->db->query("select * from tbl_rate_master where customer_id=".$customer_name." AND mode_of_transport='".$mode_dispatch."' AND region_id=".$region_id." LIMIT 1");
				if ($res->num_rows() > 0) 
				{
					$data['rate_master'] = $res->row();
				  
					// check rate available for state table
					$stateMasterRes = $this->db->query("select * from tbl_rate_state where rate_master_id=".$data['rate_master']->rate_master_id." AND state_id =".$state_id." LIMIT 1");
					
					if($stateMasterRes->num_rows() > 0){
						$stateMasterData = $stateMasterRes->row();
						$data['rate_master']->rate = $stateMasterData->rate;
						$data['rate_master']->to_pay_charges = $stateMasterData->to_pay_charges;
						$data['rate_master']->cod = $stateMasterData->cod;
						$data['rate_master']->edd = $stateMasterData->edd;
					}
					
					
					//check rate available for city table
					$cityMasterRes = $this->db->query("select * from tbl_rate_city where rate_master_id=".$data['rate_master']->rate_master_id." AND city_id =".$receiver_city." LIMIT 1");
					if ($cityMasterRes->num_rows() > 0) 
					{
						$cityMasterData 						= $cityMasterRes->row();
						$data['rate_master']->rate 				= $cityMasterData->rate;
						$data['rate_master']->to_pay_charges 	= $cityMasterData->to_pay_charges;
						$data['rate_master']->cod 				= $cityMasterData->cod;
						$data['rate_master']->edd 				= $cityMasterData->edd;
					}
					
					if($this->input->post('no_of_pack') > 0 && $this->input->post('rate_type') == 'no_of_pack') 
					{
						$rate_master_id 	= $data['rate_master']->rate_master_id;
						$no_of_pack 		= $this->input->post('no_of_pack');
						$rate_master_query 	= $this->db->query("SELECT * FROM `tbl_rate_pack` WHERE rate_master_id = ".$rate_master_id." AND $no_of_pack BETWEEN `from` AND `to` LIMIT 1");
					
						if($rate_master_query->num_rows() > 0) 
						{
							$data['rate_master_pack'] = $rate_master_query->row();
						}
					   
					}
				}
				$data['rate_master']->eod = $eod;
				return $data;
			}
		}
		else // this for document rate
		{
			$data['rate_master'] = new \stdClass();
			
			$upper_chargee 		= array();
			$sub_total 			= 0;
			$doc_sub_total 		= 0;
			$addition 			= 0;
			$addtional_kg 		= 0;
			
			
			$res = $this->db->query("select * from tbl_rate_master where customer_id=".$customer_name." AND mode_of_transport='".$mode_dispatch."' AND region_id=".$region_id." LIMIT 1");
			if ($res->num_rows() > 0) 
			{
				$data['rate_master'] = $res->row();
				$rate_id			= $data['rate_master']->rate_master_id;
				$ress 				= $this->db->query("select * from rate_master where rate_id=".$rate_id."");
				if($ress->num_rows() > 0) 
				{
					$data['rate_masters']	= $ress->result();
				
					// check rate available for state table
					$stateMasterRes = $this->db->query("select * from doc_state_rate_master where rate_id=".$rate_id." AND state_id =".$state_id." ");
					
					if($stateMasterRes->num_rows() > 0)
					{
						$data['rate_masters']	= $stateMasterRes->result();
					}
					
					
					//check rate available for city table
					$cityMasterRes = $this->db->query("select * from doc_city_rate_master where rate_id=".$rate_id." AND city_id =".$receiver_city." ");
					if ($cityMasterRes->num_rows() > 0) 
					{
						$data['rate_masters']	= $cityMasterRes->result();	
					}
					
				}
			}
			if(!empty($data['rate_masters']))
			{
			
				foreach ($data['rate_masters'] as $row)
				{
					if($row->lower != 999999  )
					{
						$upper_chargee[] =  $row->upper;  
					}
					
					if($chargeAbleWight >= $row->lower && $chargeAbleWight <= $row->upper)
					{
						$sub_total =  $sub_total + $row->rate_amt; 
					}
					else
					{
						if($row->lower == 999999 )
						{
							$addtional_kg =  $row->rate_amt;  
							$addition =  $row->upper;  
						}
						else
						{
							$doc_sub_total =  $doc_sub_total + $row->rate_amt;
						}
						
						if($chargeAbleWight > $row->upper)
						{
							$sub_total =  $sub_total + $row->rate_amt; 
						}
					}
				}
			}
			
			
			if($chargeAbleWight > $addition && $addition != 0)
			{
				$weight  		= ceil($chargeAbleWight - $addition);
				$left_weight    = $weight;
				$amount		    = $left_weight * $addtional_kg;
				 
				$sub_total		= $doc_sub_total + $amount;
				$sub_total		= $sub_total * $qty;
			}
			else
			{
				
				if($chargeAbleWight > max($upper_chargee) )
				{
					$sub_total = $sub_total + $addtional_kg;
					$sub_total = $sub_total * $qty;
				}
				else
				{
					$sub_total = $sub_total * $qty;
				}
			}
			
			
			
			if($sender_state == $receiver_state)
			{
				$cgst = ($sub_total *2.5/100);
				$sgst = ($sub_total *2.5/100);
				$igst = 0;
				
			}
			else
			{
				$cgst = 0;
				$sgst = 0;
				$igst = ($sub_total *5/100);
		
			}
			
			$data['rate_master']->rate 				= $sub_total;
			$data['rate_master']->to_pay_charges 	= '0';
			$data['rate_master']->cod 				= '0';
			$data['rate_master']->edd 				= '2';
			$data['rate_master']->eod 				= $eod;
			$data['rate_master']->cgst 				= $cgst;
			$data['rate_master']->sgst 				= $sgst;
			$data['rate_master']->igst 				= $igst;
			
			return $data;
		}
	   exit;
	}

	public function getForwaorderList()
	{
		$senderPincode = $this->input->post('senderPincode');
		$receiverPincode = $this->input->post('receiverPincode');
		//$whr1 = array('POSTCODE' => $senderPincode);
		// $whr1 = array('POSTCODE' => $receiverPincode);
		// $res1 = $this->basic_operation_m->selectRecord('tbl_pincode', $whr1);
		$whr1 = array('pin_code' => $receiverPincode);
		$res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);
		$result = $res1->row();

		echo json_encode($result);
		exit;
	}
	public function getFuelcharges() {
		$customer_id = $this->input->post('customer_id');
		$dispatch_details = $this->input->post('dispatch_details');
		$courier_id = $this->input->post('courier_id');
		$sub_amount = $this->input->post('sub_amount');
		$booking_date = $this->input->post('booking_date');
        $type_export_import = $this->input->post('type_export_import');

	    $current_date = date("Y-m-d",strtotime($booking_date));
		$whr1 = array('courier_id' => $courier_id,'fuel_from <=' => $current_date,'fuel_to >=' => $current_date);
		$res1 = $this->basic_operation_m->get_table_row('courier_fuel', $whr1);
		if($res1){$fuel_per = $res1->fuel_price; }else{$fuel_per ='0';}

		$final_fuel_charges =($sub_amount * $fuel_per/100);
			
		$sub_total =($sub_amount + $final_fuel_charges);
		

        $whr2 = array('from <=' => $current_date,'to >=' => $current_date);
        $gst_details = $this->basic_operation_m->get_table_row('tbl_gst_setting', $whr2);

            //echo $this->db->last_query();

           
			 $tbl_customers_info 		= $this->basic_operation_m->get_query_row("select gst_charges from tbl_customers where customer_id = '$customer_id'");
			
			if($tbl_customers_info->gst_charges == 1)
			{
				 if($gst_details){
                $cgst_per = $gst_details->cgst; 
                $sgst_per = $gst_details->sgst; 
                $igst_per = $gst_details->igst; 
            }else{
                $cgst_per = '0'; 
                $sgst_per = '0'; 
                $igst_per = '0'; 
            }    
            if($type_export_import=="Export")
            {
                $cgst = 0;
                $sgst = 0;
                $igst = ($sub_total*$igst_per/100);
                $grand_total = $sub_total + $igst;

            }else if($type_export_import=="Import")
            {
               $cgst = ($sub_total*$cgst_per/100);
               $sgst = ($sub_total*$sgst_per/100);
               $igst = 0;
               $grand_total = $sub_total + $cgst + $sgst + $igst;
            }
			
				
			}
			else
			{
				$cgst = 0;
			   $sgst = 0;
			   $igst = 0;
			   $grand_total = $sub_amount + $cgst + $sgst + $igst;
			}
			
			if($dispatch_details == 'Cash')
			{	
				$cgst = 0;
			   $sgst = 0;
			   $igst = 0;
			   $grand_total = $sub_amount + $cgst + $sgst + $igst;
			}

		$result2= array('final_fuel_charges'=>$final_fuel_charges,
						'sub_total'=>number_format($sub_total, 2, '.', ''),
                        'cgst'=>number_format($cgst, 2, '.', ''),
                        'sgst'=>number_format($sgst, 2, '.', ''),
						'igst'=>number_format($igst, 2, '.', ''),
						'grand_total'=>number_format($grand_total, 2, '.', ''),
					);
		echo json_encode($result2);
		
	}
    public function getGstCharges() {
        $sub_amount = $this->input->post('sub_amount');
        $booking_date = $this->input->post('booking_date');
        $type_export_import = $this->input->post('type_export_import');
		$customer_id = $this->input->post('customer_id');
		$dispatch_details = $this->input->post('dispatch_details');

        $current_date = date("Y-m-d",strtotime($booking_date));       

        $whr2 = array('from <=' => $current_date,'to >=' => $current_date);
        $gst_details = $this->basic_operation_m->get_table_row('tbl_gst_setting', $whr2);

           
			
			
			 $tbl_customers_info 		= $this->basic_operation_m->get_query_row("select gst_charges from tbl_customers where customer_id = '$customer_id'");
			
			if($tbl_customers_info->gst_charges == 1)
			{
				 if($gst_details)
				 {
					$cgst_per = $gst_details->cgst; 
					$sgst_per = $gst_details->sgst; 
					$igst_per = $gst_details->igst; 
				}else{
					$cgst_per = '0'; 
					$sgst_per = '0'; 
					$igst_per = '0'; 
				}    
				if($type_export_import=="Export")
				{
					$cgst = 0;
					$sgst = 0;
					$igst = ($sub_amount*$igst_per/100);
					$grand_total = $sub_total + $igst;

				}else if($type_export_import=="Import")
				{
				   $cgst = ($sub_amount*$cgst_per/100);
				   $sgst = ($sub_amount*$sgst_per/100);
				   $igst = 0;
				   $grand_total = $sub_amount + $cgst + $sgst + $igst;
				}
				
			}
			else
			{
				$cgst = 0;
			   $sgst = 0;
			   $igst = 0;
			   $grand_total = $sub_amount + $cgst + $sgst + $igst;
			}
			
			if($dispatch_details == 'Cash')
			{	
				$cgst = 0;
			   $sgst = 0;
			   $igst = 0;
			   $grand_total = $sub_amount + $cgst + $sgst + $igst;
			}
		   

        $result2= array(
                        'sub_total'=>number_format($sub_amount, 2, '.', ''),
                        'cgst'=>number_format($cgst, 2, '.', ''),
                        'sgst'=>number_format($sgst, 2, '.', ''),
                        'igst'=>number_format($igst, 2, '.', ''),
                        'grand_total'=>number_format($grand_total, 2, '.', ''),
                    );
        echo json_encode($result2);
        
    }
	public function available_Fuelcharges() {
		$courier_id = $this->input->post('courier_id');
		$booking_date = $this->input->post('booking_date');
		
		$current_date = date("Y-m-d",strtotime($booking_date));
		$whr1 = array('courier_id' => $courier_id,'fuel_from <=' => $current_date,'fuel_to >=' => $current_date);
		$res1 = $this->basic_operation_m->get_table_row('courier_fuel', $whr1);

		if($res1){$fuel_per = $res1->fuel_price; }else{$fuel_per ='0';}

		$result2= array('fuel_charges'=>$fuel_per,);
		echo json_encode($result2);
	}    
	public function addBusinessDays($startDate, $businessDays, $holidays = []) {
		$date = strtotime($startDate);
		$i = 0;

		while($i < $businessDays)
		{
			//get number of week day (1-7)
			$day = date('N',$date);
			//get just Y-m-d date
			$dateYmd = date("d-m-Y H:i:s",$date);

			if($day < 6 && !in_array($dateYmd, $holidays)){
				$i++;
			}       
			$date = strtotime($dateYmd . ' +1 day');
		}       

		return date('d-m-Y H:i:s',$date);

	}
	public function checkawn() {
		$data = [];       
		$awn = $this->input->post('awn');
		$awn_query = $this->db->query("SELECT `tbl_international_booking`.`pod_no` FROM `tbl_international_booking` WHERE pod_no='".$awn."'"); 
		if ($awn_query->num_rows() > 0) {
			$data['status'] = false;
		} else {
			$data['status'] = true;
		}
		echo json_encode($data);
		exit;    
	}

	public function view_delivered()
	{
		 $data = array();
			
		if ($this->session->userdata("userType") == '1') {
			$resAct = $this->db->query("select *,`tbl_tracking`.`status` AS deliver_status from tbl_booking,tbl_tracking
			  where tbl_tracking.pod_no = tbl_booking.pod_no and tbl_tracking.status='delivered' order by booking_date DESC ");
			if ($resAct->num_rows() > 0) {
				$data['allpoddata'] = $resAct->result_array();
			}
		} else {
			$username = $this->session->userdata("userName");
			$whr = array('username' => $username);
			$res = $this->basic_operation_m->getAll('tbl_users', $whr);
			$branch_id = $res->row()->branch_id;

			$data = array();
			$whrAct = array('isactive' => 1, 'isdeleted' => 0);

			$resAct = $this->db->query("select *,`tbl_tracking`.`status` AS deliver_status from tbl_booking,tbl_tracking
			  where tbl_tracking.pod_no = tbl_booking.pod_no and tbl_tracking.status='delivered' and tbl_booking.branch_id='$branch_id' order by `tbl_booking`.`booking_id` DESC");
			if ($resAct->num_rows() > 0) {
				$data['allpoddata'] = $resAct->result_array();
			}
		}
		//  print_r($data);
		$this->load->view('admin/shipment/view_deliverd', $data);
				//  $this->load->view('printpod',$data);
			
	}

	public function print_label($id) 
	{
	    // Load library
	    $this->load->library('zend');
		// Load in folder Zend
		$this->zend->load('Zend/Barcode');
		$whr = array('booking_id' => $id);
		$user_id = $this->session->userdata("userId");
		$user_type = $this->session->userdata("userType");
		if ($id != "") {			
			$data['booking'] = $this->basic_operation_m->get_all_result('tbl_international_booking', $whr);
			$where =array('id'=>1);
		    $data['company_details'] = $this->basic_operation_m->get_table_row('tbl_company',$where);
			$this->load->view('admin/international_shipment/print_shipment', $data);
		}
	}

	public function all_printpod($booking_id='') 
	{  
        // Load library
        $this->load->library('zend');
        // Load in folder Zend
        $this->zend->load('Zend/Barcode');
		$post_Data= $this->input->post();
		if(!empty($post_Data))
		{
			$data= array();
			$where = "customer_id = '".$post_Data['user_id']."' AND (tbl_international_booking.booking_date >= '".$post_Data['from_date']."' AND tbl_international_booking.booking_date <= '".$post_Data['to_date']."')";
			
			$user_id = $this->session->userdata("userId");
			$user_type = $this->session->userdata("userType");
			
           $resAct = $this->db->query("select * from tbl_international_booking where $whr GROUP BY booking_id order by booking_date DESC ");

            if ($resAct->num_rows() > 0) 
            {
                $data['booking'] = $resAct->result_array();
            }   
            
            $where =array('id'=>1);
            $data['company_details'] = $this->basic_operation_m->get_table_row('tbl_company',$where);	
		
			$this->load->view('admin/international_shipment/print_all_shipment', $data);
		}
		elseif($booking_id)
		{
			$data['selected_lists']	= explode('-',$booking_id);
			$booking_ids			= array_unique(array_filter($data['selected_lists']));
			
			$booking_idsa			= implode("','",$booking_ids);
			$whr 					= "tbl_international_booking.booking_id IN ('$booking_idsa')";
			
			$user_id = $this->session->userdata("userId");
			$user_type = $this->session->userdata("userType");		   
				
			$resAct = $this->db->query("select * from tbl_international_booking where $whr GROUP BY booking_id order by booking_date DESC ");

			if ($resAct->num_rows() > 0) 
			{
				$data['booking'] = $resAct->result_array();
			}	

           
            $where =array('id'=>1);
            $data['company_details'] = $this->basic_operation_m->get_table_row('tbl_company',$where);	
		
			$this->load->view('admin/international_shipment/print_all_shipment', $data);
		}
	}

	public function print_slip($forwarderNum, $delveryserviceType) {
		
		if($delveryserviceType == 'b2c')
		{
			$packageSlip = $this->delhivery->packageSlipC2c($forwarderNum);
			/* echo '<pre>';
			print_r($packageSlip); */
			
			$data['slip_data'] = $packageSlip->packages;
			$res = $this->db->query("select * from tbl_booking,city where tbl_booking.sender_city=city.id and tbl_booking.forwording_no='$forwarderNum' ");

			if ($res->num_rows() > 0) {
				$data['basicdetails'] = $res->row();
			}
			$booking_id = $data['basicdetails']->booking_id;
			$whr = array('booking_id' => $booking_id);
			$resAct = $this->basic_operation_m->getAll('tbl_charges', $whr);
			if ($resAct->num_rows() > 0) {
				$data['charges'] = $resAct->result_array();
			}
			
			$this->load->view('package_slip', $data);
		}
		else
		{
			$packageSlip = $this->delhivery->packageSlipB2B($forwarderNum);
			/* print_r($packageSlip);
			echo 'provide b2b api doc'; */
			$data['slip_data'] = $packageSlip;
			$this->load->view('b2b_package_slip', $data);
		}
	}
	public function getCountry()
	{
		$courier_company = $this->input->post('courier_company');
		//$whr1 = array('c_courier_id' => $courier_company);
		//$courier_company = $this->basic_operation_m->get_all_result('zone_master', $whr1);
		$courier_company = $this->basic_operation_m->get_query_result_array('SELECT * FROM zone_master WHERE c_courier_id="'.$courier_company.'" ORDER BY country_name ASC  ');

		// echo $this->db->last_query();
		
		// echo "";
		echo json_encode($courier_company);
	}
	//============

	
	public function view_international_frieght_list()
	{
		$data['f_invoice_details'] = $this->basic_operation_m->get_all_result('frieght_invoice','');
		$this->load->view('admin/international_shipment/view_international_frieght_list',$data);
	}
	
	public function international_frieght_invoice()
	{
		$result= $this->db->query('select max(inc_id) AS id from frieght_invoice')->row();
		if(!empty($result))
			{   $inc_num = $result->id + 1; }else{ $inc_num='1';}
	    $data['fid'] ="FRT/".date('y')."-".(date('y')+1)."/".$inc_num;

	    // $whr = array('booking_id'=>$booking_id);
	    // $data['f_invoice_details'] = $this->basic_operation_m->get_table_row('frieght_invoice',$whr);

	    $data['customers']  = $this->basic_operation_m->get_all_result('tbl_customers', '');
	    $data['cities']	= $this->basic_operation_m->get_all_result('city', '');        
        $data['states']	= $this->basic_operation_m->get_all_result('state', '');
        $data['country_list'] = $this->basic_operation_m->get_all_result('zone_master');
		//exit;
	       
		$this->load->view('admin/international_shipment/view_international_frieght_invoice',$data);
	}
	public function show_international_frieght_invoice($edit_id)
	{
		$result= $this->db->query('select max(inc_id) AS id from frieght_invoice')->row();
		if(!empty($result))
			{   $inc_num = $result->id + 1; }else{ $inc_num='1';}
		
		$data['fid'] ="FRT/".date('y')."-".(date('y')+1)."/".$inc_num;
	   	    
	    $whr = array('id'=>$edit_id);
	    $data['f_invoice_details'] = $this->basic_operation_m->get_table_row('frieght_invoice',$whr);
	   
	    $customer_account_id=$data['f_invoice_details']->customer_account_id;

	     $where =array('id'=>1);
		$data['company_details'] = $this->basic_operation_m->get_table_row('tbl_company',$where);

		$whr_c= array("customer_id"=>$customer_account_id); 

		$data['customers']  = $this->basic_operation_m->get_table_row('tbl_customers', $whr_c);	  
		$this->load->view('admin/international_shipment/international_freight_invoice',$data);
	}

	public function insert_international_frieght_invoice()
	{	
		$all_data 	= $this->input->post();			
		if(!empty($all_data)) 
		{	
			$username  = $this->session->userdata("userName");
			$result 		= $this->db->query('select max(inc_id) AS id from frieght_invoice')->row();
			if(!empty($result))
			{$id = $result->id + 1; }else{ $id='1';}
			$all_data['inc_id']	=$id;
			$all_data['created_by']	=$username;
			$all_data['heading']=json_encode($all_data['heading']);
			$all_data['hsn_code']=json_encode($all_data['hsn_code']);
			$all_data['amount']=json_encode($all_data['amount']);
			$all_data['cgst']=json_encode($all_data['cgst']);
			$all_data['sgst']=json_encode($all_data['sgst']);
			$all_data['igst']=json_encode($all_data['igst']);		
			if($all_data['edit_id']!="")
			{
				$whr=array('id'=>$all_data['edit_id']);
				unset($all_data['submit']);
				unset($all_data['edit_id']);
				$result3 = $this->basic_operation_m->update('frieght_invoice', $all_data,$whr);
			}else{

				unset($all_data['submit']);
				unset($all_data['edit_id']);
				$result3 = $this->basic_operation_m->insert('frieght_invoice', $all_data);
			}
				
			if(!empty($all_data))
			{						
				$msg			= 'Invoice inserted successfully';
				$class			= 'alert alert-success alert-dismissible';		
			}else{
				$msg			= 'Invoice not inserted successfully';
				$class			= 'alert alert-danger alert-dismissible';	
			}	
			$this->session->set_flashdata('notify',$msg);
			$this->session->set_flashdata('class',$class);
			
			redirect('admin/view_international_frieght_list');
		}
	}
	public function edit_international_frieght_invoice($id)
	{	
	    $data['id']=$id;
	    $data['fid']="";
	    $whr = array('id'=>$id);
	    $data['f_invoice_details'] = $this->basic_operation_m->get_table_row('frieght_invoice',$whr);

	    $data['customers']  = $this->basic_operation_m->get_all_result('tbl_customers', '');
	    $data['cities']	= $this->basic_operation_m->get_all_result('city', '');        
        $data['states']	= $this->basic_operation_m->get_all_result('state', '');
        $data['country_list'] = $this->basic_operation_m->get_all_result('zone_master');
	       
		$this->load->view('admin/international_shipment/view_international_frieght_invoice',$data);
	}



	public function available_cft() {
		$courier_id = $this->input->post('courier_id');
		$booking_date = trim($this->input->post('booking_date'));
		$customer_id = trim($this->input->post('customer_id'));

		if (!empty($booking_date)) {
			$current_date = date("Y-m-d",strtotime($booking_date));
		}else{
			$current_date = date('Y-m-d');
		}
		
		
		$whr1 = array('fuel_from <=' => $current_date,'fuel_to >=' => $current_date);
		$where = '(courier_id="'.$courier_id.'" or courier_id = "0") AND (customer_id="'.$customer_id.'" or customer_id = "0")';
		$this->db->select('*');
		$this->db->from('courier_fuel');
		$this->db->where($whr1);
		$this->db->where($where);
		$this->db->order_by('customer_id','DESC');
		// $this->db->where('customer_id',$customer_id);
		
		$query	=	$this->db->get();
		$res1 = $query->row();
		// $res1 = $this->basic_operation_m->get_table_row('courier_fuel', $whr1);

		if($res1){$fuel_per = $res1->cft; }else{$fuel_per ='0';}
		if($res1){$fuel_per2 = $res1->air_cft; }else{$fuel_per2 ='0';}

		// echo $this->db->last_query();

		$result2= array('cft_charges'=>$fuel_per,'air_cft'=>$fuel_per2);
		echo json_encode($result2);
	}

}

?>
