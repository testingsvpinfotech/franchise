<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_manager extends CI_Controller {

	function __construct()
	{
		 parent:: __construct();
		 $this->load->model('basic_operation_m');
		 if($this->session->userdata('userId') == '')
		{
			redirect('admin');
		}
	}
	
	public function customer_detail()
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
         $query = $this->db->query("SELECT * FROM tbl_booking where customer_id='$customer_id'");
         $data['count1']=$query->num_rows();
         $query1 = $this->db->query("SELECT * FROM tbl_booking,tbl_tracking where tbl_tracking.pod_no= tbl_booking.pod_no and  tbl_tracking.status='delivered' and tbl_booking.customer_id='$customer_id'");
         $data['count2']=$query1->num_rows();
         $query2= $this->db->query("SELECT * FROM tbl_booking,tbl_tracking where tbl_tracking.pod_no= tbl_booking.pod_no and  tbl_tracking.status!='delivered' and tbl_booking.customer_id='$customer_id'");
         $data['count3']=$query2->num_rows();
         
		$this->load->view('viewdetails',$data);
	}  

	public function view_report()
	{
		$data= array();
		$resAct1 = $this->db->query("select * from tbl_news limit 9 ");

		if($resAct1->num_rows()>0)
		 {
		 	$data['homenews']=$resAct1->result_array();	            
         }else{
         	 $data['homenews']=array();
         }

         $cities=$this->basic_operation_m->getAll('tbl_city','');
       	if($cities->num_rows()>0)
       {
       	$data['cities']=$cities->result_array();
       } 

       if(isset($_POST['submit']))
       {
            $data['alldata']=array();
            $customer_id=$this->session->userdata("customer_id");
           
       		$from_date = $this->input->post('fromdate');
       		$to_date = $this->input->post('todate');
       		$status = $this->input->post('status');
       		$city = $this->input->post('city');
			$status_fileter = '1=1';
			if($status){
				$status_fileter = "tbl_tracking.status='$status'";
			}
			
						
			$resAct1 = $this->db->query("select * from tbl_booking where tbl_booking.customer_id='$customer_id' and booking_date between '$from_date' and '$to_date'");
       		
       		if($resAct1->num_rows()>0)
       		{
       			$temp= $resAct1->result();
       			
       			foreach($temp as $t)
       			{
       			    $resAct2 = $this->db->query("select * from tbl_tracking where tbl_tracking.pod_no='$t->pod_no' AND $status_fileter order by tracking_date DESC ");
					if($resAct2->num_rows()>0){
						$status=@$resAct2->row()->status;
						$row=array('booking_date'=>$t->booking_date,'pod_no'=>$t->pod_no,'sender_name'=>$t->sender_name,'reciever_name'=>$t->reciever_name,'status'=>$status);
						array_push( $data['alldata'],$row);
					}
       			}
       		}
       	
       }
		$this->load->view('report',$data);
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
	
	public function list_shipment()
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
		$resAct = $this->db->query("select * from tbl_booking where tbl_booking.customer_id='$customer_id'");

        $data['list'] =$resAct->result();
        
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
		if (isset($_POST['submit']))
           {
			$pod_no=$this->input->post('pod_no');
		
			$reAct=$this->db->query("select * from tbl_booking where pod_no='$pod_no'");
			
			$data['info']=$reAct->row();
			
			$reAct=$this->db->query("select * from tbl_upload_pod where pod_no='$pod_no'");
			
			$data['pod']=$reAct->result_array();
			//echo $this->db->last_query($data);
			
		   }
		 #print_r($data['pod']);exit;
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
	
}