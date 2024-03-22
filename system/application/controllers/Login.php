<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller{
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('login_model');
		$this->load->model('basic_operation_m');
		
	}
	
	public function index()
	{
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
					redirect(base_url().'User_panel/dashboard');
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
		$this->load->view('customer_login');
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

}
?>