<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	function __construct()
	{
		 parent:: __construct();
		 $this->load->model('basic_operation_m');
		 $this->data['company_info']	= $this->basic_operation_m->get_query_row("select * from tbl_company limit 1"); 
	}
	
	public function index()
	{ 
	     $customer_id=$this->session->userdata("customer_id");
    	$data= array();
		$resAct1 = $this->db->query("select * from tbl_news limit 9 ");

		if($resAct1->num_rows()>0)
		 {
		 	$data['homenews']=$resAct1->result_array();	            
         }else{
         	 $data['homenews']=array();
         }
          $resAct	= $this->basic_operation_m->getAll('tbl_testimonial','');

		if($resAct->num_rows()>0)
		 {
			$data['testimonial']=$resAct->result_array();	            
		 }else{
			 $data['testimonial']=array();
		 }
         $query1 = $this->db->query("SELECT tbl_international_booking.pod_no FROM tbl_international_booking where customer_id='$customer_id'");
         $count1=$query1->num_rows();
         
         $query2 = $this->db->query("SELECT tbl_international_booking.pod_no FROM tbl_international_booking,tbl_international_tracking where tbl_international_tracking.pod_no= tbl_international_booking.pod_no and  tbl_international_tracking.status='delivered' and tbl_international_booking.customer_id='$customer_id'");
         $count2=$query2->num_rows();
         
         $query3= $this->db->query("SELECT tbl_international_booking.pod_no FROM tbl_international_booking,tbl_international_tracking where tbl_international_tracking.pod_no= tbl_international_booking.pod_no and  tbl_international_tracking.status!='delivered' and tbl_international_booking.customer_id='$customer_id'");
         $count3=$query3->num_rows();
         
         $query4 = $this->db->query("SELECT tbl_domestic_booking.pod_no FROM tbl_domestic_booking where customer_id='$customer_id'");
         $count4=$query4->num_rows();
         
         $query5 = $this->db->query("SELECT tbl_domestic_booking.pod_no FROM tbl_domestic_booking,tbl_domestic_tracking where tbl_domestic_tracking.pod_no= tbl_domestic_booking.pod_no and  tbl_domestic_tracking.status='delivered' and tbl_domestic_booking.customer_id='$customer_id'");
         $count5=$query5->num_rows();
      
         $query6= $this->db->query("SELECT tbl_domestic_booking.pod_no FROM tbl_domestic_booking,tbl_domestic_tracking where tbl_domestic_tracking.pod_no= tbl_domestic_booking.pod_no and  tbl_domestic_tracking.status!='delivered' and tbl_domestic_booking.customer_id='$customer_id'");
         $count6=$query6->num_rows();
         
         $data['count1']=$count1 +$count4;
         $data['count2']=$count2 +$count5;
         $data['count3']=$count3 +$count6;
         
         
		$this->load->view('viewdetails',$data);
	}  

	public function report()
	{
		$data= array('from_date'=>'','to_date'=>'','status'=>'','company_type'=>'');
		$resAct1 = $this->db->query("select * from tbl_news limit 9 ");

		if($resAct1->num_rows()>0)
		 {
		 	$data['homenews']=$resAct1->result_array();	            
         }else{
         	 $data['homenews']=array();
         }
          $resAct	= $this->basic_operation_m->getAll('tbl_testimonial','');

		if($resAct->num_rows()>0)
		 {
			$data['testimonial']=$resAct->result_array();	            
		 }else{
			 $data['testimonial']=array();
		 }

         $cities=$this->basic_operation_m->getAll('city','');
       	if($cities->num_rows()>0)
       {
       	$data['cities']=$cities->result_array();
       } 

       if(isset($_POST['submit']))
       {
            $data['alldata']=array();
            $data['alldata_d']=array();
            $customer_id=$this->session->userdata("customer_id");
           
       		$from_date = $this->input->post('fromdate');
       		$to_date = $this->input->post('todate');
       		$status = $this->input->post('status');
       		
       		if($status=='1')
       		{
       		 $display_status = "Delivered";   
       		}else{
       		    $display_status = "Pending";
       		}
       		$city = $this->input->post('city');
       		
       		$company_type = $this->input->post('company_type');
       		
       		if($company_type!="ALL")
       		{
       		    if($company_type=="International")
				{
        			$status_fileter = '1=1';
        			if($status !="ALL"){
        				$status_fileter = "tbl_international_booking.is_delhivery_complete='$status'";
        			}
        			$resAct1 = $this->db->query("select * from tbl_international_booking LEFT JOIN `tbl_customers` ON `tbl_customers`.`customer_id` = `tbl_international_booking`.`customer_id` LEFT JOIN `zone_master` ON `zone_master`.`z_id` = `tbl_international_booking`.`reciever_country_id` WHERE tbl_international_booking.customer_id='$customer_id' and booking_date between '$from_date' and '$to_date' AND $status_fileter ORDER BY booking_date DESC");
               	
               		if($resAct1->num_rows()>0)
               		{
               			$temp= $resAct1->result();
               			
               			foreach($temp as $t)
               			{
               			    $resAct2 = $this->db->query("select * from tbl_international_tracking where tbl_international_tracking.pod_no='$t->pod_no' order by tracking_date DESC ");
        					if($resAct2->num_rows()>0){
        						$status=@$resAct2->row()->status;
        						//$row=array('booking_date'=>$t->booking_date,'pod_no'=>$t->pod_no,'sender_name'=>$t->sender_name,'reciever_name'=>$t->reciever_name,'status'=>$status);
        					    $row=array('booking_date'=>$t->booking_date,'pod_no'=>$t->pod_no,'sender_name'=>$t->sender_name,'doc_nondoc'=>$t->doc_nondoc,'country_name'=>$t->country_name,'forwording_no'=>$t->forwording_no,'forworder_name'=>$t->forworder_name,'reciever_name'=>$t->reciever_name,'invoice_no'=>$t->invoice_no,'invoice_value'=>$t->invoice_value,'status'=>$status);
        						array_push( $data['alldata'],$row);
        					}
               			}
               		}
               		 $data['from_date'] =$from_date;
               		$data['to_date']=$to_date;
               		$data['status']=$this->input->post('status');
               		
       		    }
				else if($company_type=="Domestic")
			    {	
               		//==domestic
               		$d_status_fileter = '1=1';
               		if($status !="ALL"){
        				$d_status_fileter = "tbl_domestic_booking.is_delhivery_complete='$status'";
        			}
               		$resAct2 = $this->db->query("select * from tbl_domestic_booking LEFT JOIN `tbl_customers` ON `tbl_customers`.`customer_id` = `tbl_domestic_booking`.`customer_id` LEFT JOIN `city` ON `city`.`id` = `tbl_domestic_booking`.`reciever_city` WHERE tbl_domestic_booking.customer_id='$customer_id' and booking_date between '$from_date' and '$to_date' AND $d_status_fileter ORDER BY booking_date DESC");
               	
               		if($resAct2->num_rows()>0)
               		{
               			$temp2= $resAct2->result();
               			
               			foreach($temp2 as $t2)
               			{
               			    $resAct2 = $this->db->query("select * from tbl_domestic_tracking where tbl_domestic_tracking.pod_no='$t2->pod_no' order by tracking_date DESC ");
        					if($resAct2->num_rows()>0){
        						$status=@$resAct2->row()->status;
        					    $row_d=array('booking_date'=>$t2->booking_date,'pod_no'=>$t2->pod_no,'sender_name'=>$t2->sender_name,'doc_nondoc'=>$t2->doc_nondoc,'city'=>$t2->city,'forwording_no'=>$t2->forwording_no,'forworder_name'=>$t2->forworder_name,'reciever_name'=>$t2->reciever_name,'invoice_no'=>$t2->invoice_no,'invoice_value'=>$t2->invoice_value,'status'=>$status);
        						array_push($data['alldata_d'],$row_d);
        					}
               			}
               		}
               		 $data['from_date'] =$from_date;
               		$data['to_date']=$to_date;
               		$data['status']=$this->input->post('status');
			    }
       		}
			else
			{
       		        $status_fileter = '1=1';
        			if($status !="ALL")
					{
        				$status_fileter = "tbl_international_booking.is_delhivery_complete='$status'";
        			}
        			$resAct1 = $this->db->query("select * from tbl_international_booking LEFT JOIN `tbl_customers` ON `tbl_customers`.`customer_id` = `tbl_international_booking`.`customer_id` LEFT JOIN `zone_master` ON `zone_master`.`z_id` = `tbl_international_booking`.`reciever_country_id` WHERE tbl_international_booking.customer_id='$customer_id' and booking_date between '$from_date' and '$to_date' AND $status_fileter ORDER BY booking_date DESC");
               	
               		if($resAct1->num_rows()>0)
               		{
               			$temp= $resAct1->result();
               			
               			foreach($temp as $t)
               			{
               			    $resAct2 = $this->db->query("select * from tbl_international_tracking where tbl_international_tracking.pod_no='$t->pod_no' AND $status_fileter order by tracking_date DESC ");
        					if($resAct2->num_rows()>0){
        						$status=@$resAct2->row()->status;
        						//$row=array('booking_date'=>$t->booking_date,'pod_no'=>$t->pod_no,'sender_name'=>$t->sender_name,'reciever_name'=>$t->reciever_name,'status'=>$status);
        					    $row=array('booking_date'=>$t->booking_date,'pod_no'=>$t->pod_no,'sender_name'=>$t->sender_name,'doc_nondoc'=>$t->doc_nondoc,'country_name'=>$t->country_name,'forwording_no'=>$t->forwording_no,'forworder_name'=>$t->forworder_name,'reciever_name'=>$t->reciever_name,'invoice_no'=>$t->invoice_no,'invoice_value'=>$t->invoice_value,'status'=>$status);
        						array_push($data['alldata'],$row);
        					}
               			}
               		}
               		//echo "<pre>";print_r($data['alldata']);
               		//==domestic
               		
               		$d_status_fileter = '1=1';
               		if($status !="ALL")
					{
        				$d_status_fileter = "tbl_domestic_booking.is_delhivery_complete='$status'";
        			}
               	
               		$resAct_d = $this->db->query("select * from tbl_domestic_booking LEFT JOIN `tbl_customers` ON `tbl_customers`.`customer_id` = `tbl_domestic_booking`.`customer_id` LEFT JOIN `city` ON `city`.`id` = `tbl_domestic_booking`.`reciever_city` WHERE tbl_domestic_booking.customer_id='$customer_id' and booking_date between '$from_date' and '$to_date' AND $d_status_fileter ORDER BY booking_date DESC");
              
                
               		if($resAct_d->num_rows()>0)
               		{
               			$temp2= $resAct_d->result();
               			
               			foreach($temp2 as $t2)
               			{
               			    $resAct_d = $this->db->query("select * from tbl_domestic_tracking where tbl_domestic_tracking.pod_no='$t2->pod_no'  order by tracking_date DESC ");
        					if($resAct_d->num_rows()>0){
        						$status=@$resAct_d->row()->status;
        					    $row_d=array('booking_date'=>$t2->booking_date,'pod_no'=>$t2->pod_no,'sender_name'=>$t2->sender_name,'doc_nondoc'=>$t2->doc_nondoc,'city'=>$t2->city,'forwording_no'=>$t2->forwording_no,'forworder_name'=>$t2->forworder_name,'reciever_name'=>$t2->reciever_name,'invoice_no'=>$t2->invoice_no,'invoice_value'=>$t2->invoice_value,'status'=>$status);
        						array_push($data['alldata_d'],$row_d);
        					}
               			}
               		}
               		//echo "<pre>";print_r($data['alldata_d']);
       		}
       		 $data['from_date'] =$from_date;
       		$data['to_date']=$to_date;
       		$data['status']=$this->input->post('status');
       		$data['company_type']=$company_type;
           
       }
        
     //echo "<pre>";print_r($data['alldata_d']);
		$this->load->view('report',$data);
	}
	
	
	public function mis_report_excel($from_date,$to_date,$status,$company_type)
	{       
	
		//echo $from_date.$to_date.$status.$company_type;
	        $data['alldata']=array();
            $data['alldata_d']=array();
            $customer_id=$this->session->userdata("customer_id");
	        $status_fileter = '1=1';
			if($status !="ALL")
			{
				$status_fileter = "tbl_international_booking.is_delhivery_complete='$status'";
			}
			$resAct1 = $this->db->query("select * from tbl_international_booking LEFT JOIN `tbl_customers` ON `tbl_customers`.`customer_id` = `tbl_international_booking`.`customer_id` LEFT JOIN `zone_master` ON `zone_master`.`z_id` = `tbl_international_booking`.`reciever_country_id` WHERE tbl_international_booking.customer_id='$customer_id' and booking_date between '$from_date' and '$to_date' AND $status_fileter ORDER BY booking_date DESC");
       	
       	
       		if($resAct1->num_rows()>0)
       		{
       			$temp= $resAct1->result();
       			
       			foreach($temp as $t)
       			{
       			    $resAct2 = $this->db->query("select * from tbl_international_tracking where tbl_international_tracking.pod_no='$t->pod_no'  order by tracking_date DESC ");
					if($resAct2->num_rows()>0){
						$status=@$resAct2->row()->status;
						//$row=array('booking_date'=>$t->booking_date,'pod_no'=>$t->pod_no,'sender_name'=>$t->sender_name,'reciever_name'=>$t->reciever_name,'status'=>$status);
					    $row=array('booking_date'=>$t->booking_date,'pod_no'=>$t->pod_no,'sender_name'=>$t->sender_name,'doc_nondoc'=>$t->doc_nondoc,'country_name'=>$t->country_name,'forwording_no'=>$t->forwording_no,'forworder_name'=>$t->forworder_name,'reciever_name'=>$t->reciever_name,'invoice_no'=>$t->invoice_no,'invoice_value'=>$t->invoice_value,'status'=>$status);
						array_push( $data['alldata'],$row);
					}
       			}
       		}
       		//==domestic
       		$d_status_fileter = '1=1';
       		if($status!="ALL"){
				$d_status_fileter = "tbl_domestic_booking.is_delhivery_complete='$status'";
			}
       		$resAct2 = $this->db->query("select * from tbl_domestic_booking LEFT JOIN `tbl_customers` ON `tbl_customers`.`customer_id` = `tbl_domestic_booking`.`customer_id` LEFT JOIN `city` ON `city`.`id` = `tbl_domestic_booking`.`reciever_city` WHERE tbl_domestic_booking.customer_id='$customer_id' and booking_date between '$from_date' and '$to_date'  AND $d_status_fileter ORDER BY booking_date DESC ");
 
       		if($resAct2->num_rows()>0)
       		{
       			$temp2= $resAct2->result();
       			
       			foreach($temp2 as $t2)
       			{
       			    $resAct2 = $this->db->query("select * from tbl_domestic_tracking where tbl_domestic_tracking.pod_no='$t2->pod_no'order by tracking_date DESC ");
					if($resAct2->num_rows()>0){
						$status=@$resAct2->row()->status;
						//$row=array('booking_date'=>$t->booking_date,'pod_no'=>$t->pod_no,'sender_name'=>$t->sender_name,'reciever_name'=>$t->reciever_name,'status'=>$status);
					    $row=array('booking_date'=>$t2->booking_date,'pod_no'=>$t2->pod_no,'sender_name'=>$t2->sender_name,'doc_nondoc'=>$t2->doc_nondoc,'city'=>$t2->city,'forwording_no'=>$t2->forwording_no,'forworder_name'=>$t2->forworder_name,'reciever_name'=>$t2->reciever_name,'invoice_no'=>$t2->invoice_no,'invoice_value'=>$t2->invoice_value,'status'=>$status);
						array_push($data['alldata_d'],$row);
					}
       			}
       		}
       		//echo $this->db->last_query();exit;
       	 	//echo "<pre>";print_r($data['alldata']);exit;
       	 	//echo "<pre>";print_r($data['alldata_d']);exit;
       		
       		$filename = "omcs_".$customer_id.".csv";
			$fp = fopen('php://output', 'w');
			$header =array(
				"SrNo.","Date","AWB.","Network","ForwordingNo","Destination","Receiver","Doc/Non-doc","Invoice No","Invoice Value","Status");
		
				
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename='.$filename);
			
			fputcsv($fp, $header);
			$i =0;
			foreach($data['alldata'] as $value) {
			
				$i++;
				$booking_date =  date("d-m-Y",strtotime($value['booking_date']));
				$row=array($i,$booking_date,$value['pod_no'],$value['forworder_name'],$value['forwording_no'],$value['country_name'],$value['reciever_name'],$value['doc_nondoc'],$value['invoice_no'],$value['invoice_value'],$value['status']);
				fputcsv($fp, $row);
			}
				foreach($data['alldata_d'] as $value_d) {
			
				$i++;
				$booking_date =  date("d-m-Y",strtotime($value_d['booking_date']));
				$row=array($i,$booking_date,$value_d['pod_no'],$value_d['forworder_name'],$value_d['forwording_no'],$value_d['city'],$value_d['reciever_name'],$value_d['doc_nondoc'],$value_d['invoice_no'],$value_d['invoice_value'],$value_d['status']);
				fputcsv($fp, $row);
			}
       		
       		
       		
	}
	
	
	public function search_shipment()
	{
		$data= array();
		$resAct1 = $this->db->query("select * from tbl_news limit 9 ");

		if($resAct1->num_rows()>0)
		 {
		 	$data['homenews']=$resAct1->result_array();	            
         }else{
         	 $data['homenews']=array();
         }
		if(isset($_POST['submit']))
        {
			$pod_no = $this->input->post('pod_no');
			$reAct = $this->db->query("select * from tbl_booking where pod_no='$pod_no'");
			$data['pod'] = $reAct->result();
		}
		$this->load->view('search_shipment',$data);
	}
	
	public function list_shipment($type)
	{
		$customer_id=$this->session->userdata("customer_id");
		$data= array();
		$resAct1 = $this->db->query("select * from tbl_news limit 9 ");
		if($resAct1->num_rows()>0)
		 {
		 	$data['homenews']=$resAct1->result_array();	            
         }else{
			$data['homenews']=array();
         }
         $resAct	= $this->basic_operation_m->getAll('tbl_testimonial','');

		if($resAct->num_rows()>0)
		 {
			$data['testimonial']=$resAct->result_array();	            
		 }else{
			 $data['testimonial']=array();
		 }
		 
        if($type=="International")
        {
        		$resAct = $this->db->query("select * from tbl_international_booking LEFT JOIN `zone_master` ON `zone_master`.`z_id` = `tbl_international_booking`.`reciever_country_id` where tbl_international_booking.customer_id='$customer_id' ORDER BY booking_date DESC");
                $data['list'] =$resAct->result();
                $data['heading']='International';
        }else if($type=="Domestic"){
                $resAct_d = $this->db->query("select * from tbl_domestic_booking LEFT JOIN `city` ON `city`.`id` = `tbl_domestic_booking`.`reciever_city` LEFT JOIN `transfer_mode` ON `transfer_mode`.`transfer_mode_id` = `tbl_domestic_booking`.`mode_dispatch` where tbl_domestic_booking.customer_id='$customer_id' ORDER BY booking_date DESC");
                $data['list_d'] =$resAct_d->result();
                $data['heading']='Domestic';
        }
		$this->load->view('list_shipment',$data);
	}
	public function pickup_request()
	{
		$data= array();
		$data['message']="";
			if (isset($_POST['submit'])) {
				 $date=date('y-m-d');
				$r= array('id'=>'',
						  'consigner_name'=>$this->input->post('consigner_name'),
						  'consigner_address'=>$this->input->post('consigner_address'),
						  'consigner_city' => $this->input->post('consigner_city'),
						  'consigner_gstno' => $this->input->post('consigner_gstno'),
						  'consigner_pincode' => $this->input->post('consigner_pincode'),
						  'consignee_name' => $this->input->post('consignee_name'),
						  'consignee_address' => $this->input->post('consignee_address'),
						  'consignee_city' => $this->input->post('consignee_city'),
						  'consignee_gstno' => $this->input->post('consignee_gstno'),
						  'consignee_pincode' => $this->input->post('consignee_pincode'),
						  'pickup_date'=>$date,
						  //'isdeleted' =>'0',
					 );
				$result=$this->basic_operation_m->insert('tbl_pickup_request',$r);
				 
				if ($this->db->affected_rows()>0) {
					$data['message']="Pickup Request Added Sucessfully";
				}else{
					$data['message']="Error in Query";
				}
			}
             
              $resAct	= $this->basic_operation_m->getAll('tbl_testimonial','');

		if($resAct->num_rows()>0)
		 {
			$data['testimonial']=$resAct->result_array();	            
		 }else{
			 $data['testimonial']=array();
		 }
			$this->load->view('pickup_request',$data);
	
	}
	
	public function company_profile()
	{
		$data= array();
		$resAct1 = $this->db->query("select * from tbl_news limit 9 ");

		if($resAct1->num_rows()>0)
		 {
		 	$data['homenews']=$resAct1->result_array();	            
         }else{
         	 $data['homenews']=array();
         }
		 if (isset($_POST['submit'])) {
			$user_id= $this->session->userdata("customer_id");
			$r= array('id'=>'',
					  'user_id'=>$user_id,
				      'name'=>$this->input->post('name'),
		              'address' => $this->input->post('address'),
					  'gst_no' => $this->input->post('gst_no'),
					  'contact_person' => $this->input->post('contact_person'),
					  'contact_no' => $this->input->post('contact_no'),
                      //'isdeleted' =>'0',
                 );
			$result=$this->basic_operation_m->insert('tbl_company_profile',$r);
			 
			if ($this->db->affected_rows()>0) {
				$data['message']="Company Profile Added Sucessfully";
			}else{
                $data['message']="Error in Query";
			}
             redirect(base_url().'home');
		 }
		$this->load->view('company_profile',$data);
	}
	 public function track_shipment_fromlist()
	{
	    $last = $this->uri->total_segments();
	    $id= $this->uri->segment($last);
	   
		$data= array();
		$resAct1 = $this->db->query("select * from tbl_news limit 9 ");

		if($resAct1->num_rows()>0)
		 {
		 	$data['homenews']=$resAct1->result_array();	            
         }else{
         	 $data['homenews']=array();
         }
         
         
	        
		if ($id!="")
           {
			$pod_no=$id;
			$reAct=$this->db->query("select * from tbl_booking where pod_no='$pod_no'");
			
			$data['info']=$reAct->row();
			
			$reAct=$this->db->query("select * from tbl_tracking where pod_no='$pod_no'");
			
			$data['pod']=$reAct->result();
			
			$reAct=$this->db->query("select * from tbl_upload_pod where pod_no='$pod_no'");
			
			$data['podimg']=$reAct->row();
			//echo $this->db->last_query($data);
		   }
		
		$this->load->view('track_shipment_new',$data);
	}
	public function track_shipment()
	{
		$data= $this->data;
		
		$data['delivery_pod']		= array();
		if (isset($_GET['submit']))
        {
			$pod_no							=	$this->input->get('pod_no');
			$check_pod_international		=	$this->db->query("select pod_no from tbl_international_booking where pod_no = '$pod_no'");
			$check_result					=	$check_pod_international->row();
			$check_pod_domestic		=	$this->db->query("select pod_no from tbl_domestic_booking where pod_no = '$pod_no'");
			$check_result1					=	$check_pod_domestic->row();
			$check_pod_lr		=	$this->db->query("select * from lr_table where lr_number = '$pod_no'");
			$check_result2					=	$check_pod_lr->row();
		    // print_r($check_result);die();
			if(isset($check_result))
			{
				$reAct=$this->db->query("select tbl_international_booking.*,tbl_international_weight_details.no_of_pack, sendercity.city AS sender_city_name, recievercity.country_name as reciever_country_name from tbl_international_booking left join tbl_international_weight_details on tbl_international_booking.booking_id=tbl_international_weight_details.booking_id INNER JOIN city sendercity ON sendercity.id = tbl_international_booking.sender_city INNER JOIN zone_master recievercity ON recievercity.z_id = tbl_international_booking.reciever_country_id where pod_no = '$pod_no'");
				$data['info']= $reAct->row();
				
				if(!empty($data['info']))
				{
					$courier_company_id 		= $data['info']->courier_company_id;
					
					$tracking_href_details		=	$this->db->query("select * from courier_company where c_id=".$courier_company_id);
					$data['forwording_track']	=	$tracking_href_details->row();
				
					
					$reAct=$this->db->query("select * from tbl_international_tracking where pod_no = '$pod_no' ORDER BY id asc");
					$data['pod']			=	$reAct->result();  
					$data['del_status']		=	$reAct->row();
				}
        			
        		
			}
		if(isset($check_result1))
			{
			  
				$reAct=$this->db->query("select tbl_domestic_booking.*,tbl_domestic_weight_details.no_of_pack, sendercity.city AS sender_city_name, recievercity.city as reciever_country_name from tbl_domestic_booking left join tbl_domestic_weight_details on tbl_domestic_booking.booking_id=tbl_domestic_weight_details.booking_id INNER JOIN city sendercity ON sendercity.id = tbl_domestic_booking.sender_city INNER JOIN city recievercity ON recievercity.id = tbl_domestic_booking.reciever_city where pod_no = '$pod_no'");
				$data['info']					=	$reAct->row(); 
				if(!empty($data['info']))
				{
					$courier_company_id 		= 	$data['info']->courier_company_id;
					$tracking_href_details		=	$this->db->query("select * from courier_company where c_id=".$courier_company_id);
					$data['forwording_track']	=	$tracking_href_details->row();
					// if()
					// $reAct=$this->db->query("select * from tbl_domestic_tracking,tbl_branch,city where tbl_branch.`branch_name`=tbl_domestic_tracking.branch_name AND city.id=tbl_branch.city AND pod_no = '$pod_no' ORDER BY tbl_domestic_tracking.id DESC;");
					$reAct=$this->db->query("select * from tbl_domestic_tracking where pod_no = '$pod_no' Group By 'status' ORDER BY tbl_domestic_tracking.id DESC;");
					//echo $this->db->last_query();die;
					$data['pod']		=	$reAct->result();//print_r($data['pod']);die;
					$data['del_status']	=	$reAct->row();
				}
			}
		if(isset($check_result2))
			{
				$check_pod_lr1		=	$this->db->query("select * from lr_table where lr_number = '$pod_no'");
			    $data['ftl']			=	$check_pod_lr1->row_array();
			    $product =  $data['ftl']['lr_id']; 
				$check_pod_lr1		=	$this->db->query("select * from lr_product_tbl where lr_id = '$product'");
			    $data['lr']			=	$check_pod_lr1->result_array();
			}
	
			if(!empty($data['pod']))
			{
				foreach($data['pod'] as $k => $values)
				{
					if($values->status == 'DELIVERED' || $values->status == 'Delivered')
					{
						$data['delivery_date'] = $values->tracking_date;
					}
				}
			}
			
			$reAct=$this->db->query("select * from tbl_upload_pod where pod_no='$pod_no'");
			$data['podimg']=$reAct->row();
			
		}
		$this->load->view('track_shipment',$data);
	}
	
	public function deliverypod($lrNum){
		
		$curl = curl_init();
		
		$apiUrl = base_url()."admin/generatepod/get_delebrypod/".$lrNum;

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
	
	public function pod()
	{
		$data= array();
		$resAct1 = $this->db->query("select * from tbl_news limit 9 ");

		if($resAct1->num_rows()>0)
		 {
		 	$data['homenews']=$resAct1->result_array();	            
         }else{
         	 $data['homenews']=array();
         }
          $resAct	= $this->basic_operation_m->getAll('tbl_testimonial','');

		if($resAct->num_rows()>0)
		 {
			$data['testimonial']=$resAct->result_array();	            
		 }else{
			 $data['testimonial']=array();
		 }
		if (isset($_POST['submit']))
           {
			$pod_no=$this->input->post('pod_no');
		
			$reAct=$this->db->query("select * from tbl_international_booking where pod_no='$pod_no'");
			
			$data['info']=$reAct->row();
			
			if(!empty($data['info']))
			{
			
				$reAct=$this->db->query("select * from tbl_upload_pod where pod_no='$pod_no'");
			
				$data['pod']=$reAct->result_array();
			}
			else
			{
				
				$reAct=$this->db->query("select * from tbl_domestic_booking where pod_no='$pod_no'");
			
				$data['info']=$reAct->row();
				
				$reAct=$this->db->query("select * from tbl_upload_pod where pod_no='$pod_no'");
			
				$data['pod']=$reAct->result_array();
			}
		
			
		   }
		 //print_r($data['pod']);exit;
		$this->load->view('pod',$data);
	}
	public function updatepassword()
    {
    	$data['message']="";
     
           if (isset($_POST['submit']))
           {
           $id=$this->session->userdata("customer_id");
           	$whr=array('customer_id'=>$id);
            $r= array('password'=> $this->input->post('new_pass'),);
			
           	$result=$this->basic_operation_m->update('tbl_customers',$r,$whr);
           /*	echo $this->db->last_query();exit;  ----------code is to check the code is working or not and show the input content*/
           	if($this->db->affected_rows() > 0)
           	 {
           		$data['message']="Password Change Successfully";
           	 }
           	 else
             {
           		$data['message']="Error is Query";
             }
           	 redirect(base_url().'login');
           }
              $this->load->view('company_profile',$data);

    }
	
	
	public function domestic_printpod($id) 
	{
	    // Load library
	    $this->load->library('zend');
		// Load in folder Zend
		$this->zend->load('Zend/Barcode');
		$whr = array('booking_id' => $id);
		$user_id = $this->session->userdata("userId");
		$user_type = $this->session->userdata("userType");
		if ($id != "") {	
			$data['booking'] = $this->basic_operation_m->get_all_result('tbl_domestic_booking', $whr);
			$where =array('id'=>1);
		    $data['company_details'] = $this->basic_operation_m->get_table_row('tbl_company',$where);
			// echo '<pre>'; print_r($data['booking']); die;
			$this->load->view('admin/domestic_shipment/print_shipment', $data);
		}
	}
	
	public function international_printpod($id) 
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
	
}