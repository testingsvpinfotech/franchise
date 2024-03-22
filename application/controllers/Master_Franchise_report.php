<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Franchise_report extends CI_Controller {

    public function __construct() {
        parent:: __construct();
        $this->load->model('basic_operation_m');
        $this->load->model('Booking_model');
        $this->load->model('Generate_pod_model');
		if ($this->session->userdata('customer_id') == '') {
			redirect('franchise');
		}
    }
    public function mis_report($offset=0,$searching='')
	{
		
		
		$branch_name	= 	$_SESSION['branch_name'];
		$customer_id	= 	$_SESSION['customer_id'];
		$filterCond ='';
		$total_domestic_allpoddata		 	 = 0;
		$all_data 							 = $this->input->post();		
		$data['post_data']					 = $all_data;
			
		if(!empty($all_data))
		{
			
			
				$whr_d 	=	'1';
						
			$awb_no = $all_data['awb_no'];
			if($awb_no!="")
			{
				$whr_d	.=	" AND  tbl_domestic_booking.pod_no='$awb_no'";  				
			}
			
			$bill_type = $all_data['bill_type'];
			if($bill_type!="ALL")
			{
				$whr_d	.=" AND  dispatch_details='$bill_type'";
			}
			
		    $doc_type = $all_data['doc_type'];
		    if($doc_type=='1')
			{
		    	$sel_doc_type ="Non Document";
		    }
			else if($doc_type=='0')
			{
		    	$sel_doc_type ="Document";
		    }
			else
			{
		    	$sel_doc_type ="ALL";
		    }
		    
		    $courier_company = $all_data['courier_company'];		
		    if($courier_company!="ALL")
			{
    			$whr_d	.=" AND tbl_domestic_booking.courier_company_id='$courier_company'";
            }
			
            $status 	= $all_data['status'];
			
		    if(($status == 1 || $status == 0) && $status != 'ALL')
			{
			
				$whr_d	.=" AND tbl_domestic_booking.is_delhivery_complete='$status'";
			}
			elseif($status == 2)
			{
				
				
				$whr_d	.=" AND (
					tbl_domestic_tracking.status = 'RTO' 
					OR tbl_domestic_tracking.status LIKE '%Return%'
					OR tbl_domestic_tracking.status = 'Return to Orgin' 
					OR tbl_domestic_tracking.status = 'Door Close' 
					OR tbl_domestic_tracking.status = 'Address ok no search person' 
					OR tbl_domestic_tracking.status = 'Address not found' 
					OR tbl_domestic_tracking.status = 'No service' 
					OR tbl_domestic_tracking.status = 'Refuse'  
					OR tbl_domestic_tracking.status = 'Wrong address' 
					OR tbl_domestic_tracking.status = 'Person expired' 
					OR tbl_domestic_tracking.status = 'Lost Intransit' 
					OR tbl_domestic_tracking.status = 'Not collected by consignee' 
					OR tbl_domestic_tracking.status = 'Delivery not attempted'

				)";	
			}
		    
			
    			$whr_d	.=" AND tbl_domestic_booking.customer_id='$customer_id'";
          
			
			if($sel_doc_type!="ALL")
			{
				$whr_d	.=" AND doc_nondoc='$sel_doc_type'";
			}
			
			$from_date 	= $all_data['from_date'];
			$to_date 	= $all_data['to_date'];	
			if($from_date!="" && $to_date!="")
			{
			    $from_date	 	 = date("Y-m-d",strtotime($all_data['from_date']));
			    $to_date 		 = date("Y-m-d",strtotime($all_data['to_date']));	
				$whr_d			.=" AND  date(booking_date) >='$from_date' AND date(booking_date) <='$to_date'";
			}
			
	
		  
			
			$filterCond = urldecode($whr_d);
			$data['domestic_allpoddata'] 			= $this->Generate_pod_model->get_domestic_tracking_data($whr_d,"100",$offset);
		}
		else
		{    
	
	
			if(!empty($searching))
			{
				$filterCond 	= urldecode($searching);
				$whr			= str_replace('domestic',$filterCond);
				$whr_d			= $filterCond;
			}
			else
			{
				$from_date 	 = date("Y-m-d");
				$to_date	 = date("Y-m-d");
				$whr		 = "";
				 $whr_d		 = "";
				$whr_d	.=" tbl_domestic_booking.customer_id='$customer_id'";
				
			}
		
		
	  
			$data['domestic_allpoddata'] 			= $this->Generate_pod_model->get_domestic_tracking_data($whr_d,"100",$offset); 
			
		}
		$total_domestic_allpoddata 				= $this->Generate_pod_model->get_domestic_tracking_data($whr_d,"",""); 
		
			
		$this->load->library('pagination');
		$total_count1 							= count($total_domestic_allpoddata); 
		
			
		$data['total_count']			= $total_count1;
		$config['total_rows'] 			= $total_count1;
		$config['base_url'] 			= 'franchise/list-mis-report/';
		$config['suffix'] 				= '/'.urlencode($filterCond);
		
		$config['per_page'] 			= 100;
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
			$data['serial_no']				= 0;
		}
		else
		{
			$config['uri_segment'] 			= 3;
			$data['serial_no']				= $offset + 1;
		}
		
		
		if(isset($_POST['submit']) && $_POST['submit'] == 'Download Excel')
		{
			$this->download_mis_report($whr_d);
		}
		
		$this->pagination->initialize($config);
		
		$data['courier_company']	= $this->basic_operation_m->get_all_result("courier_company","");
		$data['mode_list'] 			= $this->basic_operation_m->get_all_result('transfer_mode','');
		
		$this->load->view('masterfranchise/report_master/view_mis_report',$data);
	}



		public function download_mis_report($where_d)
	{    
		
		$date=date('d-m-Y');
		$filename = "Mis_report_".$date.".csv";
		$fp = fopen('php://output', 'w');
		$tat = '';

		
		$header =array(
			"SrNo",
	       	"Booking Date",
	       	"AWB",
	       	"Mode",
	       	"Booking Branch",
	       	"Destination",
	       	"Customer code",
	       	"Customer Name",
			"Consignor Origin",
	       	"Consignor",
	       	"Consignee",
	       	"Consignee Pincode",
	       	"NOP",
	       	"AW",
	       	"CW",
	       	"Sub Total",
	       	"Consignor Invoice No",
	       	"Consignor Invoice Value",
	       	"Bill Type",
	       	"Current Status",
	       	"Last Scan Branch",
	       	"Last scan Date & time",
	       	"TAT",
			"EDD Date",
			"Delivery Date",
			"Deliverd TO",
			"RTO Date",
			"RTO Reason",
			"Total Freight",
			"POD Status", 
			"Franchise Code", 
	       	"Franchise Name",
		   	"Master Franchise Name",
	       "eWay no"
		);
		
			
		// $header =array("SrNo","Date","franchise","Master Franchise","Booking Branch","AWB","Network","Type","ForwordingNo","Destination","Customer","Receiver","Receiver Addr","Receiver Pincode","Doc/Non-doc","Weight","Bill Type","Status","Delivery Date","EDD","TAT","Deliverd TO","RTO Date","RTO Reason");
		
		// $international_allpoddata 		= $this->Generate_pod_model->get_international_tracking_data($where_i,"","");
		$domestic_allpoddata 				= $this->Generate_pod_model->get_domestic_tracking_data($where_d,"",""); 
			
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
         
		fputcsv($fp, $header);
		$i =1;
		// print_r($domestic_allpoddata);die;
		foreach($domestic_allpoddata as $value_d) 
		{
		 	$tat 			= '';
		    $rto_reason 	= '';
			$rto_date 		= '';
			$delivery_date 	= '';
			if($value_d['status'] == 'RTO' || $value_d['status']=='Return to Orgin' || $value_d['status']=='Door Close' || $value_d['status']=='Address ok no search person' || $value_d['status']=='Address not found' || $value_d['status']=='No service' || $value_d['status']=='Refuse' || $value_d['status']=='Shifted' || $value_d['status']=='Wrong address' || $value_d['status']=='Person expired' || $value_d['status']=='Lost Intransit' || $value_d['status']=='Not collected by consignee' || $value_d['status']=='Delivery not attempted')
			{
                $rto_reason     = $value_d['comment'];
                $rto_date       = $value_d['tracking_date'];
                $value_d['status']  = $value_d['status'];
          	}else if($value_d['is_delhivery_complete'] == '1')
			{
				$delivery_date 		=  date('d-m-Y',strtotime($value_d['tracking_date']));
				$value_d['status'] 	= 'Delivered';
														
				$booking_date 		= $start = date('d-m-Y', strtotime($value_d['booking_date']));
				$start 				= date('d-m-Y', strtotime($value_d['booking_date']));
				$end 				= date('d-m-Y', strtotime($value_d['tracking_date']));
				$delivery_date 				= date('d-m-Y', strtotime($value_d['delivery_date']));
				$tat 				= ceil(abs(strtotime($start)-strtotime($end))/86400);
														
			}else{
               
                if ($value_d['status']=='shifted') {
                  	$value_d['status'] = 'Intransit';
                }
														
			}
          	if ($value_d['company_type']=='Domestic') {
                $value_d['company_type'] = 'DOM';
          	}else{
                $value_d['company_type'] = 'INT';
          	}

          	if (!empty($value_d['delivery_date'])) {
                $value_d['delivery_date'] = date('d-m-Y',strtotime($value_d['delivery_date']));
          	}

          	$pod = $value_d['pod_no'];
			$customer_id = $value_d['customer_id'];
			ini_set('display_errors', '0');
			ini_set('display_startup_errors', '0');
			error_reporting(E_ALL);
			$getfranchise= array();
			$getMasterfranchise= array();
			 if($value_d['user_type']==2){
			 	$getfranchise = $this->db->query("select tbl_customers.customer_name from tbl_domestic_booking left join tbl_customers ON tbl_customers.customer_id = tbl_domestic_booking.customer_id where customer_type = 2 AND pod_no ='$pod'")->result_array(); 
			 	$getMasterfranchise = $this->db->query("select tbl_customers.customer_name from tbl_domestic_booking left join tbl_customers ON tbl_customers.parent_cust_id = tbl_domestic_booking.customer_id where parent_cust_id = '$customer_id' AND pod_no ='$pod'")->result_array();
			 }


			//  mode get 
			$mode_d = $value_d['mode_dispatch'];
			$mode_d_name = $this->db->query("select * from transfer_mode where transfer_mode_id = '$mode_d'")->row();
			//  booking branch 
			$booking_d = $value_d['pod_no'];
			$booking_d_name = $this->db->query("select * from tbl_domestic_tracking where pod_no = '$booking_d' order by id DESC limit 1")->row();
			//  customer code 
			$customer = $this->db->query("select * from tbl_customers where customer_id = '$customer_id'")->row();
			// actual_weight 
			$booking_id = $value_d['booking_id'];
			$actual_weight  = $this->db->query("select * from tbl_domestic_weight_details where booking_id = '$booking_id'")->row();
			// booking 
			$booking_id = $value_d['booking_id'];
			$booking_table  = $this->db->query("select * from tbl_domestic_booking where booking_id = '$booking_id'")->row();
		
			$cid = $booking_table->sender_city;;
			$sender_c  = $this->db->query("select * from city where id = '$cid'")->row();
			// pod 
			$pod_no = $value_d['pod_no'];
			$pod_check  = $this->db->query("select * from tbl_upload_pod where pod_no = '$pod_no'")->row();
			 if(empty($pod_check)){
				$pod_status = 'NO';
			 }else{
				$pod_status = 'Yes';
			 }
			 $delivery_date1 		=  date('d-m-Y',strtotime($booking_d_name->tracking_date));
			
			if($value_d['is_delhivery_complete'] == '1'){
				$val= $delivery_date1;
			}else{
				$val='';
			}
           if($value_d['branch_name'] == '0' OR $value_d['branch_name'] !==''){
			$pod_no = $value_d['pod_no'];
				$booking_name = $this->db->query("select * from tbl_domestic_tracking where pod_no = '$pod_no' order by id asc limit 1")->row();
				// print_r($booking_name);
				// die;
		   }
		    // echo '<pre>';print_r($value_d);
			$row = array(
				$i,
				date('d-m-Y', strtotime($value_d['booking_date'])),
				$value_d['pod_no'],
				$mode_d_name->mode_name,
				$booking_name->branch_name,
				$value_d['city'],
				$customer->cid,
				$customer->customer_name,
				$sender_c->city,
				$value_d['sender_name'],
	            $value_d['reciever_name'],
	            $value_d['reciever_pincode'],
				$value_d['no_of_pack'],
				($actual_weight->actual_weight),
				($value_d['chargable_weight']),
				$booking_table->sub_total,
				$value_d['invoice_no'],
				$value_d['invoice_value'],
				$value_d['dispatch_details'],
				$value_d['status'],
				$booking_d_name->branch_name,
				$booking_d_name->tracking_date,
				$tat,
				$delivery_date,
				$val,
				$value_d['comment'],
				$rto_date,
				$rto_reason,
				$booking_table->frieht,
				$pod_status,
				$customer->cid,
				@$getfranchise[0]['customer_name'] ,
				@$getMasterfranchise[0]['customer_name'] ,
			    $booking_table->eway_no
			);

		
			$i++;
			fputcsv($fp, $row);
		}
		exit;
   	}

	public function download_mis_report03may2022($where_d,$where_i)
	{    
		
		$date=date('d-m-Y');
		$filename = "Mis_report_".$date.".csv";
		$fp = fopen('php://output', 'w');
		$tat = '';
	
			
		$header =array("SrNo","Date","AWB","Network","Type","ForwordingNo","Destination","Customer","Receiver","Receiver Addr","Receiver Pincode","Weight","Bill Type","NOP","Status","Delivery Date","EDD","TAT","Deliverd TO","RTO Date","RTO Reason","Branch");
		
		$international_allpoddata 		= $this->Generate_pod_model->get_international_tracking_data($where_i,"","");
		$domestic_allpoddata 				= $this->Generate_pod_model->get_domestic_tracking_data($where_d,"",""); 
			
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);

		fputcsv($fp, $header);
		$i =0;
		foreach($domestic_allpoddata as $value_d) 
		{
			$tat 	= 0;
			$rto_reason 	= '';
			$rto_date 	= '';
			$delivery_date 	= '';
			if($value_d['status'] == 'RTO')
			{
				$rto_reason 	= $value_d['comment'];
				$rto_date 		= $value_d['tracking_date'];
				$value_d['status'] = $value_d['status'];
			}
	
			if($value_d['is_delhivery_complete'] == '1')
			{
				$delivery_date 		= $value_d['tracking_date'];
				$value_d['status'] = 'Delivered';
				
				$booking_date 		= $start = date('d-m-Y', strtotime($value_d['booking_date']));
				$start 				= date('d-m-Y', strtotime($value_d['booking_date']));
				$end 				= date('d-m-Y', strtotime($value_d['tracking_date']));
				$tat 				= ceil(abs(strtotime($start)-strtotime($end))/86400);
			}

			if ($value_d['status']=='shifted') {
              $value_d['status'] = 'Intransit';
            }

			$row=array($i,date('d-m-Y', strtotime($value_d['booking_date'])),$value_d['pod_no'],$value_d['forworder_name'],$value_d['company_type'],$value_d['forwording_no'],$value_d['city'],$value_d['sender_name'],$value_d['reciever_name'],$value_d['reciever_address'],$value_d['reciever_pincode'],($value_d['chargable_weight']),$value_d['dispatch_details'],$value_d['no_of_pack'],$value_d['status'],$delivery_date,$value_d['delivery_date'],$tat,$value_d['comment'],$rto_date,$rto_reason,$value_d['branch_name']);
			
			$i++;
			fputcsv($fp, $row);
		}
		
		foreach($international_allpoddata as $value_d) 
		{
			$rto_reason 	= '';
			$rto_date 	= '';
			$delivery_date 	= '';
			if($value_d['status'] == 'RTO')
			{
				$rto_reason 	= $value_d['comment'];
				$rto_date 		= $value_d['tracking_date'];
				$value_d['status'] = $value_d['status'];
			}
			
			if($value_d['is_delhivery_complete'] == '1')
			{
				$delivery_date 		= $value_d['tracking_date'];
				$value_d['status'] = 'Delivered';
			}

			if ($value_d['status']=='shifted') {
              $value_d['status'] = 'Intransit';
            }
			
			$row=array($i,date('d-m-Y', strtotime($value_d['booking_date'])),$value_d['pod_no'],$value_d['forworder_name'],$value_d['company_type'],$value_d['forwording_no'],$value_d['country_name'],$value_d['sender_name'],$value_d['reciever_name'],$value_d['reciever_address'],$value_d['reciever_zipcode'],($value_d['chargable_weight']),$value_d['dispatch_details'],$value_d['no_of_pack'],$value_d['status'],$delivery_date,"","",$value_d['comment'],$rto_date,$rto_reason,$value_d['branch_name']);
			
			$i++;
			fputcsv($fp, $row);
		}
		exit;
   	}
	
   	public function daily_sales_report()
	{
		$all_data = $this->input->post();		
		if(!empty($all_data)){
	    $whr ="1=1";
			$whr_d ="1=1";
			
			$awb_no = $this->input->post('awb_no');
			if($awb_no!="")
			{
			     $whr.=" AND tbl_international_booking.pod_no='$awb_no'";
				$whr_d.=" AND tbl_domestic_booking.pod_no='$awb_no'";   
				
			}
			$courier_company = $this->input->post('courier_company');		
		    if($courier_company!="ALL"){
    				$whr.=" AND tbl_international_booking.courier_company_id='$courier_company'";
    				$whr_d.=" AND tbl_domestic_booking.courier_company_id='$courier_company'";
            }
			$bill_type = $this->input->post('bill_type');
				if($bill_type!="ALL"){
				$whr.=" AND dispatch_details='$bill_type'";
				$whr_d.=" AND dispatch_details='$bill_type'";
			}
		    $doc_type = $this->input->post('doc_type');
		    if($doc_type=='1'){
		    	$sel_doc_type ="Non Document";
		    }else if($doc_type=='0'){
		    	$sel_doc_type ="Document";
		    }else{
		    	$sel_doc_type ="ALL";
		    }
		    
			$customer_id = $this->input->post('customer_id');		
		    if($customer_id!="ALL"){
    				$whr.=" AND tbl_international_booking.customer_id='$customer_id'";
    				$whr_d.=" AND tbl_domestic_booking.customer_id='$customer_id'";
            }
			
			if($sel_doc_type!="ALL")
			{
				$whr.=" AND doc_nondoc='$sel_doc_type'";
				$whr_d.=" AND doc_nondoc='$sel_doc_type'";
			}
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');	
			if($from_date!="" && $to_date!="")
			{
			    $from_date = date("Y-m-d",strtotime($this->input->post('from_date')));
			    $to_date = date("Y-m-d",strtotime($this->input->post('to_date')));	
				$whr.=" AND booking_date >='$from_date' AND booking_date <='$to_date'";
				$whr_d.=" AND booking_date >='$from_date' AND booking_date <='$to_date'";
			}
			
			$company_type = $this->input->post('company_type');
			if($company_type!="ALL")
			{
			    if($company_type=="International"){
			        
			    	$data['international_daily_sales_report'] = $this->Booking_model->get_daily_international_sales($whr);
			    		$data['total_no_of_pack']=0;
            			$data['total_chargable_weight']=0;
            			$data['total_grand_total']=0;
            			foreach ($data['international_daily_sales_report'] as $value) {
            				$data['total_no_of_pack']+=$value['no_of_pack'];
            				$data['total_chargable_weight']+=$value['chargable_weight'];
            				$data['total_grand_total']+=$value['grand_total'];
            			}	
			
			    }else if($company_type=="Domestic")
			    {
			    	$data['domestic_daily_sales_report'] = $this->Booking_model->get_daily_domestic_sales($whr_d);
			    		$data['total_no_of_pack']=0;
            			$data['total_chargable_weight']=0;
            			$data['total_grand_total']=0;
            			
            			foreach ($data['domestic_daily_sales_report'] as $value_d) {
            				$data['total_no_of_pack']+=$value_d['no_of_pack'];
            				$data['total_chargable_weight']+=$value_d['chargable_weight'];
            				$data['total_grand_total']+=$value_d['grand_total'];
            			}	
			    }
			}else{
			    $data['international_daily_sales_report'] = $this->Booking_model->get_daily_international_sales($whr);
			    $data['domestic_daily_sales_report'] = $this->Booking_model->get_daily_domestic_sales($whr_d);
			 //   echo $this->db->last_query();
			 //   exit;
			    	$data['total_no_of_pack']=0;
        			$data['total_chargable_weight']=0;
        			$data['total_grand_total']=0;
        			foreach ($data['international_daily_sales_report'] as $value) {
        				$data['total_no_of_pack']+=$value['no_of_pack'];
        				$data['total_chargable_weight']+=$value['chargable_weight'];
        				$data['total_grand_total']+=$value['grand_total'];
        			}	
        			foreach ($data['domestic_daily_sales_report'] as $value_d) {
        				$data['total_no_of_pack']+=$value_d['no_of_pack'];
        				$data['total_chargable_weight']+=$value_d['chargable_weight'];
        				$data['total_grand_total']+=$value_d['grand_total'];
        			}				    
			}			
		}else{
            //$from_date = "2021-05-05";
			$from_date = date("Y-m-d");
			$to_date = date("Y-m-d");

			$whr = array('booking_date >='=>$from_date,'booking_date <='=>$to_date);
			$data['international_daily_sales_report'] = $this->Booking_model->get_daily_international_sales($whr);
			$data['domestic_daily_sales_report'] = $this->Booking_model->get_daily_domestic_sales($whr);

			//echo "<pre>";print_r($data['daily_sales_report']);exit;
			$data['total_no_of_pack']=0;
			$data['total_chargable_weight']=0;
			$data['total_grand_total']=0;
			foreach ($data['international_daily_sales_report'] as $key => $value) {
				$data['total_no_of_pack']+=$value['no_of_pack'];
				$data['total_chargable_weight']+=$value['chargable_weight'];
				$data['total_grand_total']+=$value['grand_total'];
			}
			foreach ($data['domestic_daily_sales_report'] as $value_d) {
    				$data['total_no_of_pack']+=$value_d['no_of_pack'];
    				$data['total_chargable_weight']+=$value_d['chargable_weight'];
    				$data['total_grand_total']+=$value_d['grand_total'];
    			}
		}
		$data['courier_company']= $this->basic_operation_m->get_all_result("courier_company","");
		$data['customers_list']= $this->basic_operation_m->get_all_result("tbl_customers","");
		$data['mode_list'] = $this->basic_operation_m->get_all_result('transfer_mode','');
		$this->load->view('admin/report_master/view_daily_sales_report',$data);
	}
	public function international_gst_report()
	{
		$all_data = $this->input->post();		
		if(!empty($all_data)){
			$whr ="";
		 			
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');	
			if($from_date!="" && $to_date!="")
			{
			    $from_date = date("Y-m-d",strtotime($this->input->post('from_date')));
			    $to_date = date("Y-m-d",strtotime($this->input->post('to_date')));	
				$whr=" invoice_date >='$from_date' AND invoice_date <='$to_date'";
				//$whr_d.=" booking_date >='$from_date' AND booking_date <='$to_date'";
			}
		// 	$company_type = $this->input->post('company_type');
		// 	if($company_type!="ALL")
		// 	{
		// 	    if($company_type=="International"){
		// 	    	$data['international_gst_data'] = $this->Booking_model->get_international_gst_details($whr);	
		// 	    }else if($company_type=="Domestic")
		// 	    {
		// 	    	$data['domestic_gst_data'] = $this->Booking_model->get_domestic_gst_details($whr);	
		// 	    }

		// 	}else{
				$data['international_gst_data'] = $this->Booking_model->get_international_gst_details($whr);			
				$data['domestic_gst_data'] = $this->Booking_model->get_domestic_gst_details($whr);		
  //   		}
			
		 }else{
			$from_date = date('Y-m-01');
			$to_date =date('Y-m-t');
			$whr=" invoice_date >='$from_date' AND invoice_date <='$to_date'";
		    $data['international_gst_data'] = $this->Booking_model->get_international_gst_details($whr);
			//$data['domestic_gst_data'] = $this->Booking_model->get_domestic_gst_details($whr);		
		}
		$data['customers_list']= $this->basic_operation_m->get_all_result("tbl_customers","");
		$data['mode_list'] = $this->basic_operation_m->get_all_result('transfer_mode','');
		$this->load->view('admin/report_master/view_international_gst_report',$data);
	}
	public function domestic_gst_report()
	{
		$all_data = $this->input->post();		
		if(!empty($all_data)){
			$whr ="";
		 
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');	
			if($from_date!="" && $to_date!="")
			{
			    $from_date = date("Y-m-d",strtotime($this->input->post('from_date')));
			    $to_date = date("Y-m-d",strtotime($this->input->post('to_date')));	
				$whr=" invoice_date >='$from_date' AND invoice_date <='$to_date'";
				//$whr_d.=" booking_date >='$from_date' AND booking_date <='$to_date'";
			}
				//$data['international_gst_data'] = $this->Booking_model->get_international_gst_details($whr);			
				$data['domestic_gst_data'] = $this->Booking_model->get_domestic_gst_details($whr);		
    		
			
		}else{
			$from_date = date('Y-m-01');
			$to_date =date('Y-m-t');
			$whr=" invoice_date >='$from_date' AND invoice_date <='$to_date'";
		    $data['international_gst_data'] = $this->Booking_model->get_international_gst_details($whr);
			$data['domestic_gst_data'] = $this->Booking_model->get_domestic_gst_details($whr);		
		}
		$data['customers_list']= $this->basic_operation_m->get_all_result("tbl_customers","");
		$data['mode_list'] = $this->basic_operation_m->get_all_result('transfer_mode','');
		$this->load->view('admin/report_master/view_domestic_gst_report',$data);
	}
	
	
      public function international_shipment_report()
      {    
            $date=date('d-m-Y');
			$filename = "SipmentDetails_".$date.".csv";
			$fp = fopen('php://output', 'w');
			$header =array(
				"SrNo.","Booking Date","ABW No","Sender name","Return Address","Return Pin","Receiver Name","Address","Country","Zipcode","Receiver Contact","Mode","Waybill","ForwordingNo","Forworder","Payment Mode","Package Amount","Product to be Shipped",
				"Chargable Weight","Freight","Transport","Destination","Clearance","ESS","OtherCh","Total","Fuel Surcharge","Sub Total","CGST Tax","SGST Tax","IGST Tax","Grand Total");
				
			$all_data = $this->input->post();	
			if(!empty($all_data)){
				$whr ="1=1";	 			
				$from_date = $this->input->post('from_date');
				$to_date = $this->input->post('to_date');	
				if($from_date!="" && $to_date!="")
				{
					$from_date = date("Y-m-d",strtotime($this->input->post('from_date')));
					$to_date = date("Y-m-d",strtotime($this->input->post('to_date')));	
					$whr.=" AND booking_date >='$from_date' AND booking_date <='$to_date'";	
				}
				 $courier_company = $this->input->post('courier_company');		
    		    if($courier_company!="ALL"){
        				$whr.=" AND tbl_international_booking.courier_company_id='$courier_company'";
        				
                }
			}else{
				$whr="";				
			}
			$shipment_data = $this->Booking_model->get_all_pod_data($whr,'');
				
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
   public function domestic_shipment_report()
   {    
            $date=date('d-m-Y');
			$filename = "SipmentDetails_".$date.".csv";
			$fp = fopen('php://output', 'w');
			/*$header =array(
				"SrNo.","Booking Date","ABW No","Sender name","Return Address","Return Pin","Receiver Name","Address","City","State","Country","Pincode","Receiver Contact","Mode","Waybill","ForwordingNo","Forworder","Payment Mode","Package Amount","Product to be Shipped",
				"Chargable Weight","Freight","Transport","Pickup","RemoteArea","COD","AWB Ch.","OtherCh","Total","Fuel Surcharge","Sub Total","CGST Tax","SGST Tax","IGST Tax","Grand Total"); */
				
				$header =array("Waybill","SHIPPER NAME","Consignee Name","City","State","Country","Address","Pincode","Phone","Mobile","Weight","Payment Mode","Package Amount","Cod Amount","Product to be Shipped","Shipping Mode","Return Address","Return Pin","fragile_shipment");

			$all_data = $this->input->post();	
			if(!empty($all_data)){
				$whr ="1=1";	 			
				$from_date = $this->input->post('from_date');
				$to_date = $this->input->post('to_date');	
				if($from_date!="" && $to_date!="")
				{
					$from_date = date("Y-m-d",strtotime($this->input->post('from_date')));
					$to_date = date("Y-m-d",strtotime($this->input->post('to_date')));	
					$whr.=" AND booking_date >='$from_date' AND booking_date <='$to_date'";	
				}
				$courier_company = $this->input->post('courier_company');		
    		    if($courier_company!="ALL"){
        				$whr.=" AND tbl_domestic_booking.courier_company_id='$courier_company'";
                }
                $mode_name = $this->input->post('mode_name');		
    		    if($mode_name!="ALL"){
        				$whr.=" AND tbl_domestic_booking.mode_dispatch='$mode_name'";
                }
			}else{
				$whr="";				
			}
			$shipment_data = $this->Booking_model->get_all_pod_data_domestic($whr,'');
				
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename='.$filename);

			fputcsv($fp, $header);
			$i =0;
			foreach($shipment_data as $row) {
				$i++;
				
				$whr=array('id'=>$row['sender_city']);
				$sender_city_details = $this->basic_operation_m->get_table_row("city",$whr);
				$sender_city = $sender_city_details->city;
				
				$whr_s=array('id'=>$row['reciever_state']);
				$reciever_state_details = $this->basic_operation_m->get_table_row("state",$whr_s);
				$reciever_state = $reciever_state_details->state;
				
				$whr_p=array('id'=>$row['payment_method']);
				$payment_method_details = $this->basic_operation_m->get_table_row("payment_method",$whr_p);
				$payment_method = $payment_method_details->method;
			/*$row=array($i,$row['booking_date'],$row['pod_no'],$row['sender_name'],$row['sender_address'],$row['sender_pincode'],$row['reciever_name'],$row['reciever_address'],$row['city'],$reciever_state,'India',$row['reciever_pincode'],$row['reciever_contact'],$row['mode_name'],$row['eway_no'],$row['forwording_no'],$row['forworder_name'],$payment_method,$row['invoice_value'],$row['special_instruction'],$row['chargable_weight'],$row['frieht'],$row['transportation_charges'],$row['pickup_charges'],$row['delivery_charges'],$row['courier_charges'],$row['awb_charges'],$row['other_charges'],$row['total_amount'],$row['fuel_subcharges'],$row['sub_total'],$row['cgst'],$row['sgst'],$row['igst'],$row['grand_total']); */
				
				$row=array($row['forwording_no'],$row['sender_name'],$row['reciever_name'],$row['city'],$reciever_state,'India',$row['reciever_address'],$row['reciever_pincode']," ",$row['reciever_contact'],$row['chargable_weight'],$payment_method,$row['invoice_value'],$row['courier_charges'],$row['special_instruction'],$row['mode_name'],$row['sender_address'],$row['sender_pincode']," ",$row['pod_no']);
				
				
				fputcsv($fp, $row);
			}
			exit;
   }
     public function outstanding_report()
	{
		$all_data = $this->input->post();		
		if(!empty($all_data)){
			
			$whr ="1=1";
			$whr_d ="1=1";
			
				    
			$customer_id = $this->input->post('customer_id');		
		    if($customer_id!="ALL"){
    				$whr.=" AND tbl_international_invoice.customer_id='$customer_id'";
    				$whr_d.=" AND tbl_domestic_invoice.customer_id='$customer_id'";
            }			
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');	
			if($from_date!="" && $to_date!="")
			{
			    $from_date = date("Y-m-d",strtotime($this->input->post('from_date')));
			    $to_date = date("Y-m-d",strtotime($this->input->post('to_date')));	
				$whr.=" AND  tbl_international_invoice.invoice_date >='$from_date' AND tbl_international_invoice.invoice_date <='$to_date'";
				$whr_d.=" AND  tbl_domestic_invoice.invoice_date >='$from_date' AND tbl_domestic_invoice.invoice_date <='$to_date'";
			}
	
			$query_int = "SELECT tbl_international_invoice.invoice_number,tbl_international_invoice.invoice_date,tbl_international_invoice.grand_total,customer_name,gstno,tbl_invoice_receipt.entry_no,tbl_invoice_receipt.reference_no,tbl_invoice_receipt.reference_date,tbl_invoice_receipt.payment_method,reference_amt,tbl_invoice_payments.amount_recieved,discount,tds_amt,reference_mapped_amt FROM tbl_international_invoice LEFT JOIN  tbl_invoice_payments ON  tbl_international_invoice.invoice_number=tbl_invoice_payments.invoice_number LEFT JOIN tbl_invoice_receipt ON tbl_invoice_payments.reference_no=tbl_invoice_receipt.id  WHERE $whr ";
		  
			$data['inv_list_int'] = $this->basic_operation_m->get_query_result_array($query_int);
			
			$query_dom = "SELECT tbl_domestic_invoice.invoice_number,tbl_domestic_invoice.invoice_date,grand_total,customer_name,gstno,tbl_invoice_receipt.entry_no,tbl_invoice_receipt.reference_no,tbl_invoice_receipt.reference_date,tbl_invoice_receipt.payment_method,reference_amt,tbl_invoice_payments.amount_recieved,discount,tds_amt,reference_mapped_amt FROM tbl_domestic_invoice LEFT JOIN  tbl_invoice_payments ON  tbl_domestic_invoice.invoice_number=tbl_invoice_payments.invoice_number LEFT JOIN tbl_invoice_receipt ON tbl_invoice_payments.reference_no=tbl_invoice_receipt.id  WHERE $whr_d ";
			$data['inv_list_dom'] = $this->basic_operation_m->get_query_result_array($query_dom);
			
			if(!empty($query_int))
			{
				$data['inv_list'] = $data['inv_list_int'];
			}
			if(!empty($query_dom))
			{
				$data['inv_list'] = $data['inv_list_dom'];
			}
			if(!empty($query_int) && !empty($query_dom))
			{
				$data['inv_list'] = array_merge($data['inv_list_int'],$data['inv_list_dom']);
			}
		    //echo "<pre>";
			//print_R($data['inv_list']);
			//exit;
		}else{
		
		}
		$data['customers_list']= $this->basic_operation_m->get_all_result("tbl_customers","");		
		$this->load->view('admin/report_master/view_outstanding_report',$data);
	}
	
	
}



?>
