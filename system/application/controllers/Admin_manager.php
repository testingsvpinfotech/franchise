<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_manager extends CI_Controller 
{
	var $data= array();
    function __construct() 
	{
        parent:: __construct();
		$this->load->model('login_model');
        $this->load->model('basic_operation_m');
        $this->load->model('Customer_model');
        $this->load->model('Booking_model');
        if($this->session->userdata('userId') == '')
		{
			redirect('admin');
		}
    }

    private function get_report_monthly(){
    	$y = date('Y');
    	$data = array();
		$monthly = array();

		for ($i=1; $i <= 12; $i++) { 
			$query ="select CAST(count(distinct tbl_domestic_tracking.pod_no) as UNSIGNED) as total,tbl_domestic_tracking.status 
			from tbl_domestic_booking 
			
            join tbl_domestic_tracking on tbl_domestic_tracking.pod_no = tbl_domestic_booking.pod_no
            			WHERE
            tbl_domestic_tracking.status=(SELECT status from tbl_domestic_tracking as ss where ss.pod_no=tbl_domestic_booking.pod_no order by id desc limit 1) AND
            	 MONTH(booking_date)=$i AND YEAR(booking_date)=$y
			group by tbl_domestic_tracking.status";
			$resAct1 = $this->db->query($query);
			$data = $resAct1->result_array();

			if (!empty($data)) {
				$query1 ="select CAST(count(distinct tbl_upload_pod.pod_no) as UNSIGNED)  as total,'POD Uploaded' as status
				from tbl_upload_pod 
	            join tbl_domestic_booking on tbl_domestic_booking.pod_no = tbl_upload_pod.pod_no
	            			WHERE
	            	 MONTH(booking_date)=$i AND YEAR(booking_date)=$y";
				$resAct2 = $this->db->query($query1);
				$data[] = $resAct2->row_array();
				
			}

			$monthly[] = $data;
			
			
		}

		$status = $this->db->query("select status, CAST(0 as UNSIGNED) as total from tbl_domestic_tracking group by status")->result_array();

		$status[] = array('total' => 0 , 'status'=>'POD Uploaded');

		$data2 = array();
		$month_wise = array();
		$short = array(
		  'Jan', 
		  'Feb', 
		  'Mar', 
		  'Apr', 
		  'May', 
		  'Jun', 
		  'Jul', 
		  'Aug', 
		  'Sep', 
		  'Oct', 
		  'Nov', 
		  'Dec'
		);
		if (!empty($monthly)) {
			foreach ($monthly as $key => $value) {
				
				$data2[$key] = $status;

				foreach ($value as $key2 => $value2) {

					foreach ($data2[$key] as $key3 => $value3) {
						if ($value2['status']==$value3['status'])
			  			{
							$data2[$key][$key3]['total'] = (int)$value2['total'];
						}
					}
					
				}
				$dd = array_column($data2[$key], 'total');
				array_unshift($dd, $short[$key]);
				$month_wise[$key] = $dd;

			}
		}


		$data['status'][0]=array_column($status, 'status');
		array_unshift($data['status'][0], 'Month');
		$data['status'] = array_merge($data['status'],$month_wise);

		if (!empty($data['status'])) {
			foreach ($data['status'] as $key => $value) {
				if ($key==0) {continue;}

				foreach ($value as $key2 => $value2) {
					if ($key2==0) {continue;}
					$data['status'][$key][$key2] = (int)$value2;

				}
			}
			
		}

		$data2 = array(
			'report_type' => 'monthly',
			'report_data' => json_encode($data['status']),
			'datec' => date('Y-m-d H:i:s'),
		);
		$this->db->insert('report_table',$data2);
		return $data;
    }

    private function get_report_weekly(){
    	$y = date('Y');
    	$data = array();
		$monthly = array();

		
		$query ="select CAST(count(distinct tbl_domestic_tracking.pod_no) as UNSIGNED) as total,tbl_domestic_tracking.status ,booking_date
			from tbl_domestic_booking 
            join tbl_domestic_tracking on tbl_domestic_tracking.pod_no = tbl_domestic_booking.pod_no
            WHERE
            tbl_domestic_tracking.status=(SELECT status from tbl_domestic_tracking as ss where ss.pod_no=tbl_domestic_booking.pod_no order by id desc limit 1) AND
            	 week(booking_date)=week(now()) 
			group by tbl_domestic_tracking.status,tbl_domestic_booking.booking_date";
		$resAct1 = $this->db->query($query);
		$data = $resAct1->result_array();
		$monthly = $data;
			
			
		
		$status = $this->db->query("select status, CAST(0 as UNSIGNED) as total from tbl_domestic_tracking group by status")->result_array();

		$data2 = array();
		$month_wise = array();
		$short = array(
		  'Mon', 
		  'Tue', 
		  'Wed', 
		  'Thu', 
		  'Fri', 
		  'Sat', 
		  'Sun',
		);

		$day=0;
		$date_check='';
		$first_date = date('Y-m-d', strtotime("this week"));
		

		$temp_arr = array();

		foreach ($short as $key => $value) {
			if($day==0){
				$temp_arr[$first_date] = $status;
			}else{
				$stop_date = date('Y-m-d', strtotime($first_date . ' +'.$day.' day'));
				$temp_arr[$stop_date] = $status;
			}
			$day++;
		}

		if (!empty($monthly)) {
			foreach ($monthly as $key => $value) {

				foreach ($temp_arr[$value['booking_date']] as $key2 => $value2) {

					if ($temp_arr[$value['booking_date']][$key2]['status']==$value['status']) {

						$temp_arr[$value['booking_date']][$key2]['total']=(int)$value['total'];
					}
				}
			}
		}
		$day = 0;
		foreach ($temp_arr as $key => $value) {
			$dd = array_column($value, 'total');
			array_unshift($dd, $short[$day]);
			$month_wise[$day] = $dd;
			$day++;
		}

		$data['status'][0]=array_column($status, 'status');
		array_unshift($data['status'][0], 'Week');
		$data['status'] = array_merge($data['status'],$month_wise);

		if (!empty($data['status'])) {
			foreach ($data['status'] as $key => $value) {
				if ($key==0) {continue;}

				foreach ($value as $key2 => $value2) {
					if ($key2==0) {continue;}
					$data['status'][$key][$key2] = (int)$value2;

				}
			}
			
		}
		

		$data2 = array(
			'report_type' => 'weekly',
			'report_data' => json_encode($data['status']),
			'datec' => date('Y-m-d H:i:s'),
		);
		$this->db->insert('report_table',$data2);
		return $data['status'];
    }

    public function get_report_complaint(){
    	$y = date('Y');
    	$data = array();
		$monthly = array();

		
		$query ="select count(ticket_status) as total,action as status from ticket_status LEFT JOIN ticket ON ticket_status.status_id=ticket.ticket_status group by action";
		$resAct1 = $this->db->query($query);
		$data = $resAct1->result_array();
		

		$data['status'][0] = array_column($data, 'status');
		array_unshift($data['status'][0], 'Status');
		$data['status'][1] = array_column($data, 'total');
		array_unshift($data['status'][1], 'Status');

		foreach ($data['status'][1] as $key => $value) {
			if ($key==0) {
				# code...
			}else{
				$data['status'][1][$key] = (int)$value;
			}
			
		}
		// array_unshift($data['status'][0], 'Status');
		// $data['status']= array_merge($total,$status);

				

		$data2 = array(
			'report_type' => 'Complaint',
			'report_data' => json_encode($data['status']),
			'datec' => date('Y-m-d H:i:s'),
		);
		$this->db->insert('report_table',$data2);
		echo "<pre>";
		print_r($data['status']);
		return $data['status'];
    }

    public function generate_reports(){
    	$data['status'] = $this->get_report_monthly();
		$data['weekly'] = $this->get_report_weekly();
		$data['complaint'] = $this->get_report_complaint();
    }

	public function view_dashboard() 
	{
		$data = $this->data;

		$monthly = $this->db->query("select * from report_table WHERE report_type='monthly' ORDER BY report_id DESC")->row_array();
		$weekly = $this->db->query("select * from report_table WHERE report_type='weekly' ORDER BY report_id DESC")->row_array();
		$complaint = $this->db->query("select * from report_table WHERE report_type='Complaint' ORDER BY report_id DESC")->row_array();

		$data['status'] = $monthly['report_data'];
		$data['weekly'] = $weekly['report_data'];
		$data['complaint'] = $complaint['report_data'];

		if($this->session->userdata("userType") == '1')
		{
			$data['allcompany']=$this->basic_operation_m->get_all_result('tbl_company','');  
			$data['all_users']=$this->basic_operation_m->getAllUsers();
			$where ="";
			$data['allcustomer']= $this->Customer_model->get_customer_details($where);  
			$query ="select * from tbl_branch, city, state where tbl_branch.state=state.id and tbl_branch.city=city.id";
			$data['allbranchdata'] =$this->basic_operation_m->get_query_result($query);

			$data['latest_international_shippment']=$this->Booking_model->get_all_pod_data_dashboard(); 
			$data['latest_domestic_shippment']=$this->Booking_model->get_all_pod_data_domestic_dashboard();  

			$data['count_international_pod']=$this->login_model->get_count_international_pod(); 
			$data['count_delivered_international_pod']=$this->login_model->get_count_delivered_international_pod(); 

			$data['count_domestic_pod']=$this->login_model->get_count_domestic_pod();  
			$data['count_delivered_domestic_pod']=$this->login_model->get_count_delivered_domestic_pod();  

			$first_date= date('Y-m-01');
			$last_date= date('Y-m-t');

			$current_first_date= date('Y-m-d');
			$current_last_date= date('Y-m-d');

			$whr=" booking_date >= '".$first_date."' AND booking_date <= '".$last_date."'";       
			$data['inter_current_month_cash']=$this->login_model->total_cash('tbl_international_booking',$whr);     

			$whr=" booking_date >= '".$current_first_date."' AND booking_date <= '".$current_last_date."'";    
			$data['inter_current_date_cash']=$this->login_model->total_cash('tbl_international_booking',$whr); 

		   
			$whr=" booking_date >= '".$first_date."' AND booking_date <= '".$last_date."'";
			$data['domestic_current_month_cash']=$this->login_model->total_cash('tbl_domestic_booking',$whr); 

			
			$whr=" booking_date >= '".$current_first_date."' AND booking_date <= '".$current_last_date."'";           
			$data['domestic_current_date_cash']=$this->login_model->total_cash('tbl_domestic_booking',$whr);  
		}
		else
		{
			$data['allcompany']= array();
			$data['all_users']= array();
			$data['allcustomer']= array();
			$data['allbranchdata']= array();
			$data['latest_international_shippment']= array();
			$data['latest_domestic_shippment']= array();
			$data['count_international_pod']= array();
			$data['count_delivered_international_pod']= array();
			$data['count_domestic_pod']= array();
			$data['count_delivered_domestic_pod']= array();
			$data['inter_current_month_cash']= array();
			$data['inter_current_date_cash']= array();
			$data['domestic_current_month_cash']= array();
			$data['domestic_current_date_cash']= array();
		}

		$this->load->view('admin/view_dashboard', $data);
	} 
 
    public function about() 
	{
		$data				= $this->data;
		$resAct1 = $this->db->query("select * from tbl_news limit 9 ");

		if($resAct1->num_rows()>0)
		 {
		 	$data['homenews']=$resAct1->result_array();	            
         }else{
         	 $data['homenews']=array();
         }
		
		$this->load->view('about',$data);
	} 

    public function vision() 
	{
		$data				= $this->data;
		
		$this->load->view('vision',$data);
	} 

     public function boardmembers() 
	 {
		$data				= $this->data;
		$this->load->view('boardmembers',$data);
	} 

     public function ournetwork() {
		$data				= $this->data;
		
		$this->load->view('ournetwork',$data);
	} 
	 public function ourclient() {
		$data				= $this->data;
		$this->load->view('ourclient',$data);
	}
	public function services() 
	{
		$data				= $this->data;	
		
		$this->load->view('services',$data);
	} 
	


	public function contact_us()
	{
		$data				= $this->data;
		$email = trim($this->input->post('email'));
		$name = trim($this->input->post('name'));
		$message = trim($this->input->post('message'));
		$data['feedback_msg'] = '';
		if($this->input->post('submit') == 'Submit'){
			$this->load->library('email');
			$config['protocol'] = 'smtp';
			$config['smtp_host'] = 'mail.micslogistics.com';
			$config['smtp_user'] = 'noreply@micslogistics.com';
			$config['smtp_pass'] = 'Rakesh@123#';
			$config['smtp_port'] = 26;
			$config['mailtype'] = 'html';
			$config['charset'] = 'iso-8859-1';

			$message = "<strong>FROM: </strong>$name ($email)  <br><br> <strong>Message: </strong>$message";
			$this->email->initialize($config);

			$this->email->from('info@micslogistics.com', 'Micslogistics Admin');
			$this->email->to('info@micslogistics.com');					 
		   // $this->email->cc('another@another-example.com');
		   // $this->email->bcc('them@their-example.com');

			$this->email->subject('Micslogistics - Contact Form Enquiry');
			$this->email->message($message);
			$data['feedback_msg'] = 'Message has been sent!!!';
		}

		$this->load->view('contact',$data);
	}

	public function privacy()
	{
		$data				= $this->data;
		$resAct1 = $this->db->query("select * from tbl_news limit 9 ");

		if($resAct1->num_rows()>0)
		 {
		 	$data['homenews']=$resAct1->result_array();	            
         }else{
         	 $data['homenews']=array();
         }
		
		$this->load->view('privacy',$data);

	}

	public  function pincode_tracking () 
	{
		$data				= $this->data;
		$data['results'] = "";
		$pincodes = trim($this->input->post('pincodes'));
		$data['pincodes'] = $pincodes;
		$service_available = "";
		if($this->input->post('submit') == 'Submit'){
		 		$tmp_array_pincodes = preg_split('/\r\n|[\r\n]/', $pincodes);
				$array_pincodes = array();
				#Check bluedart_air
				foreach($tmp_array_pincodes as $inx => $val){

					$fedex_regular_str = '';
					$res = $this->db->query("SELECT PinCode, `City Name` as loc,`State`,`ODA-OPA / Regular Classification (Dom +Intl)` as oda  FROM  fedex_regular WHERE PinCode = ".$this->db->escape($val));
					if ($res->num_rows() > 0) {
						$tmp_dat = $res->row_array();
						$oda_str = '';
						if(trim($tmp_dat['oda']) == 'ODA/OPA'){
							$oda_str =  'ODA';
						}

						#$fedex_regular_str = "Service available $oda_str". '(' . $tmp_dat['loc'] . ')' ;
						$service_available = "Service available $oda_str". '(' . $tmp_dat['loc'] . ')' ;
					}else{
						$fedex_regular_str = '<span style="color:red">NOT Available</span>';
					}
					 

					if(!$service_available){
						$revigo_regular_str = '';
						$res = $this->db->query("SELECT PinCode,  `Serviceability` as oda FROM   revigo_regular  WHERE PinCode = ".$this->db->escape($val));
						if ($res->num_rows() > 0) {
							$tmp_dat = $res->row_array();
							$oda_str = '';
							if(trim($tmp_dat['oda']) == 'ODA'){
								$oda_str =  'ODA';
							}

							$revigo_regular_str = "Service available $oda_str" ;
							$service_available = "Service available $oda_str" ;
						}else{
							$revigo_regular_str =  '<span style="color:red">NOT Available</span>';
						}
					}



					if(!$service_available){
						$bluedart_air_str = '';
						$service_available = '';
						$res = $this->db->query("SELECT PinCode, `State Description` as loc, `S/C Description` as loc2  FROM  bluedart_air WHERE PinCode = ".$this->db->escape($val));
						if ($res->num_rows() > 0) {
							$tmp_dat = $res->row_array();
							#$bluedart_air_str = 'Service available'. '(' . $tmp_dat['loc']." - ".$tmp_dat['loc2'] . ')' ;
							$service_available = 'Service available'. '(' . $tmp_dat['loc']." - ".$tmp_dat['loc2'] . ')' ;
						}else{
							$bluedart_air_str = '<span style="color:red">NOT Available</span>';
						}
						
						if(!$service_available){
							$bluedart_surface_str = '';
							$res = $this->db->query("SELECT PinCode, `State Description` as loc, `S/C Description` as loc2 FROM  bluedart_surface WHERE PinCode = ".$this->db->escape($val));
							if ($res->num_rows() > 0) {
								$tmp_dat = $res->row_array();
								#$bluedart_surface_str = 'Service available'. '(' . $tmp_dat['loc']." - ".$tmp_dat['loc2'] . ')' ;
								$service_available = 'Service available'. '(' . $tmp_dat['loc']." - ".$tmp_dat['loc2'] . ')' ;
							}else{
								$bluedart_surface_str = '<span style="color:red">NOT Available</span>';
							}
						}
					}
					
					if(!$service_available){
						$spoton_str = '';
						$res = $this->db->query("SELECT PinCode,  `BRNM` as loc,  `AreaName` as loc2 FROM  spoton  WHERE PinCode = ".$this->db->escape($val));
						if ($res->num_rows() > 0) {
							$tmp_dat = $res->row_array();
							#print_r($tmp_dat);
							$spoton_str = 'Service available'. '(' . $tmp_dat['loc']." - ".$tmp_dat['loc2'] . ')' ;
							$service_available = 'Service available'. '(' . $tmp_dat['loc']." - ".$tmp_dat['loc2'] . ')' ;
						}else{
							$spoton_str = '<span style="color:red">NOT Available</span>';
						}
					}

					if(!$service_available){
						$service_available = '<span style="color:red">Not Available</span>';
					}

					$data['results'] .= "<strong>PIN:$val</strong>:$service_available <br>";
					//$data['results'] .= "<strong>PIN:$val</strong>, Bluedart air: $bluedart_air_str ,Bluedart surface: $bluedart_surface_str, Fedex regular:$fedex_regular_str, Revigo regular:$revigo_regular_str, Spoton:$spoton_str <br>";
				}
				#
	
		}

        $this->load->view('pincodetracking', $data);
 	}
	
	public function track_shipment()
	{
		$data= array();
		$resAct1 = $this->db->query("select * from tbl_news limit 9 ");

		if($resAct1->num_rows()>0)
		 {
		 	$data['homenews']=$resAct1->result_array();	            
         }else{
         	 $data['homenews']=array();
         }
		 $data['delivery_pod']		= array();
		if (isset($_GET['submit']))
        {
			$pod_no=$this->input->get('pod_no');
			$reAct=$this->db->query("select tbl_booking.*,tbl_weight_details.no_of_pack, sendercity.city AS sender_city_name, recievercity.city as reciever_city_name from tbl_booking left join tbl_weight_details on tbl_booking.booking_id=tbl_weight_details.booking_id INNER JOIN city sendercity ON sendercity.id = tbl_booking.sender_city INNER JOIN city recievercity ON recievercity.id = tbl_booking.reciever_city where pod_no='$pod_no'");
			$data['info']=$reAct->row();
			
			$reAct=$this->db->query("select * from tbl_tracking where pod_no='$pod_no' ORDER BY tracking_date DESC");
			
			$data['pod']	=	$reAct->result();
			
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
	
	//customer_login
	public function customer_login()
	{
		
		
		$resAct1 = $this->db->query("select * from tbl_news limit 9 ");

		if($resAct1->num_rows()>0)
		 {
		 	$data['homenews']=$resAct1->result_array();	            
         }else{
         	 $data['homenews']=array();
         }
		$data["message"]="";
		$data["result"]=false;
		$data['title']="Login";
		
		if(isset($_REQUEST['submit']))
		{
			if($this->input->post('email')!='' && $this->input->post('password')!='')
			{
				$res = $this->login_model->checkLogin($this->input->post('email'),$this->input->post('password'));
				
				if($res == true)
				{ 
					$this->session->set_userdata("loggedin",true);
					redirect(base_url().'customer-detail');
				}
				else
				{
					$data["message"] = "Invalid Login";
				}
			}
			else
			{
				$data["message"] = "Please Enter Username & Password";
			}
		}
		$this->load->view('login',$data);
	}
	
	public function logout()
	{
		$this->session->unset_userdata("customer_name");
		$this->session->unset_userdata("customer_id");
		$this->session->set_userdata("loggedin",false);
		//$this->session->unset_userdata("userType");
		$this->session->sess_destroy();

		redirect(base_url());
	}
	
	public function admin_login()
	{    
		$data				= $this->data;
		
		$data["message"]="";
		$data["result"]=false;
		$data['title']="Login";
		if(isset($_REQUEST['submit']))
		{
			if($this->input->post('username')!='' && $this->input->post('password')!='')
			{
				$res = $this->login_model->checkAdminLogin($this->input->post('username'),$this->input->post('password'));
				if($res == true)
				{ 
					$this->session->set_userdata("loggedin",true);
					redirect(base_url().'admin/dashboard');
				}
				else
				{
					$data["message"] = "Invalid Login";
				}
			}
			else
			{
				$data["message"] = "Please Enter Username & Password";
			}
		}
		$this->load->view('admin_login',$data);
	  
	}


	public function manage_user_menu()
	{  

		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);


		$data				= array();
		$reAct=$this->db->query("select * from menu_master");
		$data['menus']=$reAct->result();


		$reAct=$this->db->query("select user_menu.*,menu from user_menu join menu_master on menu_master.menu_id=user_menu.menu_id ORDER By userType");
		$data['usermenus']=$reAct->result();
		
		$data["message"]="";
		$data["result"]=false;
		$data['title']="Login";
		if(isset($_REQUEST['submit']))
		{
			$userType = $_REQUEST['userType'];
			$menu = $_REQUEST['menu'];

			$reAct=$this->db->query("select * from user_menu where userType='$userType' AND menu_id=".$menu);
			$check =$reAct->result();
			// echo $this->db->last_query();

			if (empty($check)) {
				$this->db->insert('user_menu',array('userType'=>$userType,'menu_id'=>$menu));
			}
			$reAct=$this->db->query("select * from menu_master");
			$data['menus']=$reAct->result();

			// echo $this->db->last_query();
			// user_menu
			// user_type

			// exit();
			// menu
		}
		$this->load->view('admin/user_management/user_menu_manage',$data);
	  
	}


	public function delete_user_menu($user_menu_id)
	{  

		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);


		$reAct=$this->db->query("DELETE from user_menu WHERE user_menu_id=".$user_menu_id);
		

		redirect('/admin_manager/manage_user_menu');

	  
	}

	
}
?>
