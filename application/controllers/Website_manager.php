<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Website_manager extends CI_Controller 
{
	var $data= array();
    function __construct() 
	{
        parent:: __construct();
		$this->load->model('login_model');
        $this->load->model('basic_operation_m');
		$this->data['company_info']	= $this->basic_operation_m->get_query_row("select * from tbl_company limit 1"); 
    }

	public function index() 
	{
		$data					= $this->data;
		
		$data['homeslider']		= $this->basic_operation_m->get_query_result("select * from tbl_homeslider order by id asc"); 
		$data['newesdata']		= $this->basic_operation_m->get_query_result("select * from tbl_news limit 3"); 
		$data['homenews']		= $this->basic_operation_m->get_query_result("select * from tbl_news limit 9"); 
		$data['testimonial']	= $this->basic_operation_m->get_query_result("select * from tbl_testimonial"); 
		
		 $this->load->view('index', $data);
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
		
		$this->load->view('abouts',$data);
	} 

    public function vision() 
	{
		$data				= $this->data;
		
		$this->load->view('vision',$data);
	} 
	
	public function career() 
	{
		$data				= $this->data;
		
		$this->load->view('career',$data);
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
	public function service() 
	{
		$data				= $this->data;	
		         $resAct	= $this->basic_operation_m->getAll('tbl_testimonial','');

		if($resAct->num_rows()>0)
		 {
			$data['testimonial']=$resAct->result_array();	            
		 }else{
			 $data['testimonial']=array();
		 }
		
		$this->load->view('service',$data);
	} 
	
	public function contact()
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
	
	public function customerlogin()
	{
		$data				= $this->data;
		$this->load->view('customer_login',$data);
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
		$data				= $this->data;
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
		// print_r($_GET['submit']);die();
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
		$data				= $this->data;
		
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
		$this->session->unset_userdata("userName");
		$this->session->unset_userdata("userId");
		$this->session->unset_userdata("userType");
		$this->session->unset_userdata("userPic");
		$this->session->unset_userdata("customer_name");
		$this->session->unset_userdata("customer_id");
		$this->session->set_userdata("loggedin",false);
		
		$this->session->sess_destroy();
		redirect('admin');
	}
	
	public function admin_login()
	{    
		$data				= $this->data;
		
		$data["message"]="";
		$data["result"]=false;
		$data['title']="Login";

		if($this->session->userdata('userId') != '')
		{
			redirect(base_url().'admin/dashboard');
			exit();
		}
		 
		$data['company_details'] = $this->basic_operation_m->get_table_row('tbl_company',array('id'=>1));
		
		if(isset($_REQUEST['submit']))
		{
			if($this->input->post('username')!='' && $this->input->post('password')!='')
			{

				$password = $this->input->post('password');
				$key = $this->config->item('encryption_key');
	            $salt1 = hash('sha512', $key . $password);
				$salt2 = hash('sha512', $password . $key);
				$hashed_password = hash('sha512', $salt1 . $password . $salt2);
				$res = $this->login_model->checkAdminLogin($this->input->post('username'),$hashed_password);

				// echo $this->db->last_query();exit();
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

	
}
?>
