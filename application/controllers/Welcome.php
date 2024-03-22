<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class welcome extends CI_Controller {

	public function __construct()
	{

      parent::__construct();
       // load model pages
      $this->load->model('User_model');
	    $this->load->model('basic_operation_m');
    }


	


   //  public function index()
   // {
   //   $this->load->view('main_menu/home');    
   // }

    
    public function login_u()
   {
         if($_POST) 
         {
              $result = $this->User_model->login_user($_POST);
              if(!empty($result)) {
                  $data = [
                      'user_name' => $result->user_name,
                      'password' => $result->password
                  ];

                   $this->session->set_userdata($data);

                   //redirect('http://www.tutorialspoint.com/computer_graphics/index.htm');
                  redirect('Welcome/home');
             
              } else {

                  echo "Username or password is wrong!";
              }
          }
           
    }
    public function login_view()
			   {
			       // load login page
			      // $this->load->view("header.php");
			       $this->load->view("login");
			       //$this->load->view("footer.php");
			   }
     

   

	//slider not work
	public function home()
	{   //$this->load->view('home');
		$this->load->view('home');
	}
	public function about()
	{
		//$this->load->view('home');
		$this->load->view('abouts');
		//$this->load->view('footer');
	}


	public function contact()
	{
		$data= array();
		
		
		$email = trim($this->input->post('email'));
		$name = trim($this->input->post('name'));
		$message = trim($this->input->post('message'));
		$data['feedback_msg'] = '';
		if($this->input->post('submit') == 'Submit'){
			/* $this->load->library('email');
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
			$this->email->message($message); */
			$data['feedback_msg'] = 'Message has been sent!!!';
		}
		
		$this->load->view('contacts');
	}
	public function service()
	{
		$this->load->view('service');
	}
public function privacy()
	{
		$this->load->view('privacy');
	}
	public function document()
	{
		$this->load->view('document');
	}
	public function helpdesk()
	{
		$this->load->view('helpdesk');
	}
	public function customerlogin()
	{
		$this->load->view('customerlogin');
	}

	//tacking page error css
	public function tracking()
	{
		$this->load->view('tracking');
	}
	public function trackingOLD()
	{
		$this->load->view('TrackingOLD');
	}
	
	public  function pincode () {
		$data = array();
		$resAct	= $this->basic_operation_m->getAll('tbl_testimonial','');

		if($resAct->num_rows()>0)
		 {
			$data['testimonial']=$resAct->result_array();	            
		 }else{
			 $data['testimonial']=array();
		 }
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

					$available1 = '<span style="color:red;">Opps , Sorry Service Not Available!</span>';
					//$res = $this->db->query("SELECT PinCode, `City Name` as loc,`State`,`ODA-OPA / Regular Classification (Dom +Intl)` as oda  FROM  fedex_regular WHERE PinCode = ".$this->db->escape($val));
					$res = $this->db->query("SELECT *, `city` as loc,`state` FROM  pincode WHERE pin_code = ".$this->db->escape($val));
					if ($res->num_rows() > 0) {
						$tmp_dat = $res->row_array();
					}
					
				
					$tbl_head		= '';
					$tbl_body		= '';
					$res_dom_company = $this->db->query("SELECT * FROM  courier_company WHERE company_type='Domestic' ");
					if ($res_dom_company->num_rows() > 0) {
						$res_dom_company_heading = $res_dom_company->result_array();
						foreach($res_dom_company_heading AS $value)
						{
						    $tbl_head .="<td>".$value['c_company_name']."</td>"; 
						}
					}
					
				
					$fedex = $tmp_dat['fedex'];
					$trackon = $tmp_dat['trackon'];
					$bluedart_air = $tmp_dat['bluedart_air'];
					
					if($fedex=='1')
					{
					    $tbl_body .="<td>Y</td>";
					    $available1 = '<span style="color:green;">Service Available!</span>';
					}else{
					     $tbl_body.="<td><span style='color:red'>N</span></td>";
					}
					if($trackon=='1')
					{
					    $tbl_body .="<td>Y</td>";
					    $available1 = '<span style="color:green;">Service Available!</span>';
					}else{
					     $tbl_body .="<td><span style='color:red'>N</span></td>";
					}
					
					 $tbl_body .="<td><span style='color:red'>N</span></td>";
					  $tbl_body .="<td><span style='color:red'>N</span></td>";
					
					if($bluedart_air=='1')
					{
					    $tbl_body .="<td>Y</td>";
					    $available1 = '<span style="color:green;">Service Available!</span>';
					}else{
					     $tbl_body .="<td><span style='color:red'>N</span></td>";
					}
					
					 $tbl_body .="<td><span style='color:red'>N</span></td>";
					 $tbl_body .="<td><span style='color:red'>N</span></td>";
					 $tbl_body .="<td><span style='color:red'>N</span></td>";
					 $tbl_body .="<td><span style='color:red'>N</span></td>";
					
					$service_available ="<table border='1' class='table'><tr><td style='width: 15px;'>PIN</td>$tbl_head</tr>
					                    <tr><td style='width: 15px;'>$val (".$tmp_dat['loc'].")</td>$tbl_body</tr>
					                    </table>";
					
					$data['results'].=$service_available;
					$data['results'] ="<br>".$available1;
				}
			//}
		}

        $this->load->view('pincodetracking', $data);
 	}
}
