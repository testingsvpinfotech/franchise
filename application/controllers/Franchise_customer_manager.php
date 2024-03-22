<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Franchise_customer_manager extends CI_Controller 
{
	var $data= array();
    function __construct() 
	{
        parent:: __construct();
		$this->load->model('login_model');
        $this->load->model('basic_operation_m');
        

		
		$this->data['company_info']	= $this->basic_operation_m->get_query_row("select * from tbl_company limit 1"); 
    }
    
     public function getCityList(){
            $pincode = $this->input->post('pincode');
    		$whr1 = array('pin_code' => $pincode);
    		$res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);	
    		
    		$city_id = $res1->row()->city_id;
    		
    		$whr2 = array('id' => $city_id);
    		$res2 = $this->basic_operation_m->selectRecord('city', $whr2);
    		$result2 = $res2->row();

	     	echo json_encode($result2);
        }	
        
       public function getState() {
    		$pincode = $this->input->post('pincode');
    		$whr1 = array('pin_code' => $pincode);
    		$res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);	
    		
    		$state_id = $res1->row()->state_id;
    		$whr3 = array('id' => $state_id);
    		$res3 = $this->basic_operation_m->selectRecord('state', $whr3);
    		$result3 = $res3->row();
    
    		echo json_encode($result3);
		
    	}
    
    

    public function index() {
        
        $data["message"]="";
		$data["result"]=false;
		$data['title']="Login";
		
		if(isset($_REQUEST['submit']))
		{
		   
			if($this->input->post('email')!='' && $this->input->post('password')!='')
			{
				$res = $this->login_model->checkfranchiseLogin($this->input->post('email'),$this->input->post('password'));
				
			
				// echo $this->db->last_query();die;
			// print_r($res);exit;

				if($res[0]['customer_type'] == 2)
				{ 
					$this->session->set_userdata("loggedin",true);
					redirect(base_url().'franchise/dashboard');
				}elseif($res[0]['customer_type'] == 1){
                    $this->session->set_userdata("loggedin",true);
					redirect(base_url().'master_franchise/dashboard');   
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
		$this->load->view('franchise_login',$data);    
        
    }
    
    public function add_customer(){
     if($this->session->userdata('customer_id') == ''){  
        redirect('franchise');
     }else{
         $parent_cust_id = $this->session->userdata('customer_id');
         
        if(isset($_POST['save'])){
         
            $data = array(
                'cid' => $this->input->post('CId'),
                'password' => $this->input->post('password'),
                'customer_name' => $this->input->post('customer_name'),//****Personal Info
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'pincode' => $this->input->post('pincode'),
                'state' => $this->input->post('customer_state_id'),
                'city' => $this->input->post('customer_city_id'),
                'phone' => $this->input->post('contact_number'),
                'contact_person' => $this->input->post('contact_person'),
                'gstno' => $this->input->post('gst_number'),
                'gst_charges' => $this->input->post('gst_charges'),
                'parent_cust_id' =>$parent_cust_id,
                'company_id' => $this->input->post('company_id'),
                'customer_type' =>1,
                'register_date' => $date,
                
            );
            
            // print_r($data); exit;
            $result = $this->db->insert('tbl_customers',$data);
            
               if (!empty($result)) {
                $msg            = 'Customer added successfully';
                $class            = 'alert alert-success alert-dismissible';
            } else {
                $msg            = 'Customer not added successfully';
                $class            = 'alert alert-danger alert-dismissible';
            }
            $this->session->set_flashdata('notify', $msg);
            $this->session->set_flashdata('class', $class);

            redirect('franchise/add-customer');
            
        }else{
            
         $query = "SELECT MAX(customer_id) as id FROM tbl_customers ";
            $result1 = $this->basic_operation_m->get_query_row($query);
            $id = $result1->id + 1;
            //print_r($id); exit;

            if (strlen($id) == 1) {
                $franchise_id = 'FC0000' . $id;
            } elseif (strlen($id) == 2) {
                $franchise_id = 'FC000' . $id;
            } elseif (strlen($id) == 3) {
                $franchise_id = 'FC00' . $id;
            } elseif (strlen($id) == 4) {
                $franchise_id = 'FC0' . $id;
            } elseif (strlen($id) == 5) {
                $franchise_id = 'FC' . $id;
            }
             
            $data['cities'] = $this->basic_operation_m->get_all_result('city', '');
            $data['states'] =  $this->basic_operation_m->get_all_result('state', '');
            $data['CId']    =   $franchise_id;

           
            $data['company_list'] = $this->db->query('SELECT * FROM tbl_company WHERE 1 ORDER BY company_name ASC')->result();  
            $data['show_customer_list'] = $this->db->query('SELECT tbl_customers.*,state.state,city.city FROM `tbl_customers` LEFT JOIN state ON tbl_customers.state = state.id LEFT JOIN city ON tbl_customers.city = city.id WHERE parent_cust_id ='.$parent_cust_id)->result();
            // print_r(  $data['show_customer_list']) ;exit;   
             $this->load->view('franchise/setting_master/add_customer',$data);
        }
     }  
    }
    
      public function franchise_logout(){
       
       $this->session->unset_userdata('customer_name');
       $this->session->unset_userdata('customer_type');
       $this->session->unset_userdata('customer_id');
       $this->session->sess_destroy();
       redirect('franchise','refresh'); 
        
    }

    
}    