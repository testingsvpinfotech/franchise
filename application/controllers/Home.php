<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct() {
        parent:: __construct();
        $this->load->model('basic_operation_m');
    }

    public function index() {
		$data= array();
		$whrAct=array('isactive'=>1,'isdeleted'=>0);
		$resAct	= $this->db->query("select * from tbl_homeslider order by id asc"); 

		if($resAct->num_rows()>0)
		 {
			$data['homeslider']=$resAct->result_array();	            
		 }else{
			 $data['homeslider']=array();
		 }
		 $resAct = $this->db->query("select * from tbl_news limit 3 ");

		if($resAct->num_rows()>0)
		 {
			$data['newesdata']=$resAct->result_array();	            
		 }else{
			 $data['newesdata']=array();
		 }
		$resAct	= $this->basic_operation_m->getAll('tbl_testimonial','');

		if($resAct->num_rows()>0)
		 {
			$data['testimonial']=$resAct->result_array();	            
		 }else{
			 $data['testimonial']=array();
		 }
		$resAct1 = $this->db->query("select * from tbl_news limit 9 ");

		if($resAct1->num_rows()>0)
		 {
			$data['homenews']=$resAct1->result_array();	            
		 }else{
			 $data['homenews']=array();
		 }
		$this->load->view('index', $data);
	} 
 
     public function about() {
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
		
		$this->load->view('about',$data);
	} 

     public function vision() {
		$data= array();
		$resAct	= $this->basic_operation_m->getAll('tbl_testimonial','');

		if($resAct->num_rows()>0)
		 {
			$data['testimonial']=$resAct->result_array();	            
		 }else{
			 $data['testimonial']=array();
		 }
		$this->load->view('vision',$data);
	} 

     public function boardmembers() {
		$data= array();
		
		$this->load->view('boardmembers',$data);
	} 

     public function ournetwork() {
		$data= array();
		
		$this->load->view('ournetwork',$data);
	} 
	 public function ourclient() {
		$data= array();
		
		$this->load->view('ourclient',$data);
	}
	 public function itservices() {
		$data= array();
		$resAct	= $this->basic_operation_m->getAll('tbl_testimonial','');

		if($resAct->num_rows()>0)
		 {
			$data['testimonial']=$resAct->result_array();	            
		 }else{
			 $data['testimonial']=array();
		 }
		
		$this->load->view('itservices',$data);
	} 
	
	public function contact()
	{
		$data= array();
		$resAct	= $this->basic_operation_m->getAll('tbl_testimonial','');

		if($resAct->num_rows()>0)
		 {
			$data['testimonial']=$resAct->result_array();	            
		 }else{
			 $data['testimonial']=array();
		 }
		
		
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
		$data= array();
		$resAct1 = $this->db->query("select * from tbl_news limit 9 ");

		if($resAct1->num_rows()>0)
		 {
		 	$data['homenews']=$resAct1->result_array();	            
         }else{
         	 $data['homenews']=array();
         }
		
		$this->load->view('privacy',$data);

	}

	public  function pincodetracking () {
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
					//$res = $this->db->query("SELECT PinCode, `City Name` as loc,`State`,`ODA-OPA / Regular Classification (Dom +Intl)` as oda  FROM  fedex_regular WHERE PinCode = ".$this->db->escape($val));
					$res = $this->db->query("SELECT *, `city` as loc,`state` FROM  pincode WHERE pin_code = ".$this->db->escape($val));
					if ($res->num_rows() > 0) {
						$tmp_dat = $res->row_array();
					}
					
				
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
				}else{
				     $tbl_body.="<td><span style='color:red'>N</span></td>";
				}
				if($trackon=='1')
				{
				    $tbl_body .="<td>Y</td>";
				}else{
				     $tbl_body .="<td><span style='color:red'>N</span></td>";
				}
				
				 $tbl_body .="<td><span style='color:red'>N</span></td>";
				  $tbl_body .="<td><span style='color:red'>N</span></td>";
				
				if($bluedart_air=='1')
				{
				    $tbl_body .="<td>Y</td>";
				}else{
				     $tbl_body .="<td><span style='color:red'>N</span></td>";
				}
				
				 $tbl_body .="<td><span style='color:red'>N</span></td>";
				 $tbl_body .="<td><span style='color:red'>N</span></td>";
				 $tbl_body .="<td><span style='color:red'>N</span></td>";
				 $tbl_body .="<td><span style='color:red'>N</span></td>";
				
				$service_available ="<table border='1'><tr><td style='width: 15px;'>PIN</td>$tbl_head</tr>
				                    <tr><td style='width: 15px;'>$val (".$tmp_dat['loc'].")</td>$tbl_body</tr>
				                    </table>";
				
				$data['results'].=$service_available;
				}
			//}
		}

        $this->load->view('pincodetracking', $data);
 	}
}
?>
