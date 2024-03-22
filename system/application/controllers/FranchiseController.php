<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FranchiseController extends CI_Controller
{

    var $data = array();
    function __construct()
    {
        parent::__construct();
         $this->load->helper(array('form', 'url'));
        $this->load->model('basic_operation_m');
        $this->load->model('Franchise_model');
        if ($this->session->userdata('userId') == '') {
            redirect('admin');
        }
    }

    public function index()
    {
       
         $data['allfranchise'] = $this->Franchise_model->get_franchise_details();
         $this->load->view('admin/franchise_master/view_franchise',$data);
    }


    public function add_franchise(){

            $query = "SELECT MAX(franchise_id) as id FROM tbl_franchise ";
            $result1 = $this->basic_operation_m->get_query_row($query);
            $id = $result1->id + 1;
            //print_r($id); exit;

            if (strlen($id) == 1) {
                $franchise_id = 'FI0000' . $id;
            } elseif (strlen($id) == 2) {
                $franchise_id = 'FI000' . $id;
            } elseif (strlen($id) == 3) {
                $franchise_id = 'FI00' . $id;
            } elseif (strlen($id) == 4) {
                $franchise_id = 'FI0' . $id;
            } elseif (strlen($id) == 5) {
                $franchise_id = 'FI' . $id;
            }

            $data['cities'] = $this->basic_operation_m->get_all_result('city', '');
            $data['states'] =  $this->basic_operation_m->get_all_result('state', '');
            $data['fid']    =   $franchise_id;

            $data['company_list'] = $this->basic_operation_m->get_query_result_array('SELECT * FROM tbl_company WHERE 1 ORDER BY company_name ASC');

            $this->load->view('admin/franchise_master/add_franchise', $data);
        }
        
        
        
        public function store_franchise_data(){
            
            
         if (isset($_POST['submit'])) {
             
          

         $this->load->library('form_validation');
         
         $this->form_validation->set_rules('username', 'Username','trim|required');
         $this->form_validation->set_rules('franchise_name', 'Name', 'trim|required');
         $this->form_validation->set_rules('pan_number', 'Pan Number', 'trim|required|is_unique[tbl_franchise.pan_number]');
         $this->form_validation->set_rules('aadhar_number', 'Aadhar Number', 'trim|required|is_unique[tbl_franchise.aadhar_number]');
         $this->form_validation->set_rules('cmp_gstno', 'GST Number', 'trim|required|is_unique[tbl_franchise.cmp_gstno]');
         $this->form_validation->set_rules('password', 'Password', 'trim|required');
         $this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required|matches[password]');
         $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[tbl_franchise.email]');

        if ($this->form_validation->run() == FALSE){
            
                $this->add_franchise();
                
          }else{
            // *********************************  pancard upload ****************
                // print_r(($_FILES['pancard_photo']));exit; 
               // var_dump($_FILES);exit;
            
               if (!empty($_FILES['pancard_photo']['name'])) {
                  $config['upload_path'] =   'franchise-documents/pancard_document/';
                  if (!is_dir($config['upload_path'])) {
                      mkdir($config['upload_path'], 0777, TRUE);
                  }
                  $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
                  $config['max_size'] = 10000000;
                  $config['file_name'] = $_FILES['pancard_photo']['name'];
                  $this->load->library('upload', $config);
                  $this->upload->initialize($config);
                  if ($this->upload->do_upload('pancard_photo')) {
                      $uploadData = $this->upload->data();
                      $pancard_photo = $uploadData['file_name'];
                  } else {
                      $pancard_photo = '';
                      $error = array('error' => $this->upload->display_errors());
                    }
                 } 
                 
                  // echo '<pre>';  print_r($_FILES['pancard_photo']);
                 
             // ********************************* AadharCard upload ****************     
              
              if (!empty($_FILES['aadharcard_photo']['name'])) {
                  $config['upload_path'] =   'franchise-documents/aadharcard_document/';
                  if (!is_dir($config['upload_path'])) {
                      mkdir($config['upload_path'], 0777, TRUE);
                  }
                  $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
                  $config['max_size'] = 10000000;
                  $config['file_name'] = $_FILES['aadharcard_photo']['name'];
                  $this->load->library('upload', $config);
                  $this->upload->initialize($config);
                  if ($this->upload->do_upload('aadharcard_photo')) {
                      $uploadData = $this->upload->data();
                      $aadharcard_photo = $uploadData['file_name'];
                  } else {
                      $aadharcard_photo = '';
                      $error = array('error' => $this->upload->display_errors());
                    }
                 } 
             // ********************************* Cancel Check upload ****************     
              
              if (!empty($_FILES['cancel_check']['name'])) {
                  $config['upload_path'] =   'franchise-documents/bank_document/';
                  if (!is_dir($config['upload_path'])) {
                      mkdir($config['upload_path'], 0777, TRUE);
                  }
                  $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
                  $config['max_size'] = 10000000;
                  $config['file_name'] = $_FILES['cancel_check']['name'];
                  $this->load->library('upload', $config);
                  $this->upload->initialize($config);
                  if ($this->upload->do_upload('cancel_check')) {
                      $uploadData = $this->upload->data();
                      $cancel_check = $uploadData['file_name'];
                  } else {
                      $cancel_check = '';
                      $error = array('error' => $this->upload->display_errors());
                    }
                 } 
              
             
            $date = date('Y-m-d');

            $data = array(
                'fid' => $this->input->post('fid'),
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
                'franchise_name' => $this->input->post('franchise_name'),//****Personal Info
                'franchise_relation' => $this->input->post('franchise_relation'),
                'age' => $this->input->post('age'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'pincode' => $this->input->post('pincode'),
                'state' => $this->input->post('franchaise_state_id'),
                'city' => $this->input->post('franchaise_city_id'),
                'contact_number' => $this->input->post('contact_number'),
                'alt_contact' => $this->input->post('alt_contact'),
                'companytype' => $this->input->post('companytype'),//******kyc
                'pan_name' => $this->input->post('pan_name'),
                'pan_number' => $this->input->post('pan_number'),
                'pancard_photo' => $pancard_photo,
                'register_date' => $date,
                'aadhar_number' => $this->input->post('aadhar_number'),
                'aadharin_name' => $this->input->post('aadharin_name'),
                'dob' => $this->input->post('dob'),
                'gender' => $this->input->post('gender'),
                'aadhar_address' => $this->input->post('aadhar_address'),
                'aadharcard_photo' => $aadharcard_photo,
                'company_name' => $this->input->post('company_name'),// ****company information
                'cmp_pan_number' => $this->input->post('cmp_pan_number'),
                'cmp_gstno' => $this->input->post('cmp_gstno'),
                'legal_name' => $this->input->post('legal_name'),
                'constitution_of_business' => $this->input->post('constitution_of_business'),
                'taxpayer_type' => $this->input->post('taxpayer_type'),
                'gstin_status' => $this->input->post('gstin_status'),
                'cmp_address' => $this->input->post('cmp_address'),
                'cmp_pincode' => $this->input->post('cmp_pincode'),
                'cmp_state' => $this->input->post('cmp_state'),
                'cmp_city' => $this->input->post('cmp_city'),
                'cmp_office_phone' => $this->input->post('cmp_office_phone'),
                'cmp_account_name' => $this->input->post('cmp_account_name'),//*****Bank Details
                'cmp_account_number' => $this->input->post('cmp_account_number'),
                'cancel_check' => $cancel_check,
                'cmp_bank_name' => $this->input->post('cmp_bank_name'),
                'cmp_bank_branch' => $this->input->post('cmp_bank_branch'),
                'cmp_acc_type' => $this->input->post('cmp_acc_type'),
                'cmp_ifsc_code' => $this->input->post('cmp_ifsc_code'),
                
            );
         // echo '<pre>';   print_r($data);
           
            $result = $this->db->insert('tbl_franchise', $data);
            // echo $this->db->last_query();exit;
            if (!empty($result)) {
                $msg            = 'Franchise added successfully';
                $class            = 'alert alert-success alert-dismissible';
            } else {
                $msg            = 'Franchise not added successfully';
                $class            = 'alert alert-danger alert-dismissible';
            }
            $this->session->set_flashdata('notify', $msg);
            $this->session->set_flashdata('class', $class);

            redirect('admin/franchise-list');
        
         }
       } 
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
         public function getCityList1(){
            $pincode = $this->input->post('cmppincode');
    		$whr1 = array('pin_code' => $pincode);
    		$res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);	
    		
    		$city_id = $res1->row()->city_id;
    		
    		$whr2 = array('id' => $city_id);
    		$res2 = $this->basic_operation_m->selectRecord('city', $whr2);
    		$result2 = $res2->row();

	     	echo json_encode($result2);
        }	
        
       public function getState1() {
    		$pincode = $this->input->post('cmppincode');
    		$whr1 = array('pin_code' => $pincode);
    		$res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);	
    		
    		$state_id = $res1->row()->state_id;
    		$whr3 = array('id' => $state_id);
    		$res3 = $this->basic_operation_m->selectRecord('state', $whr3);
    		$result3 = $res3->row();
    
    		echo json_encode($result3);
		
    	}
    

    
    function update_franchise_data($id)
    {
        $data['message']="";	
			if($id!=""){
				$whr =array('franchise_id'=>$id);
				$data['franchise_data'] = $this->basic_operation_m->get_table_row('tbl_franchise',$whr);				
			}
				if (isset($_POST['submit'])) 
				{
						$last = $this->uri->total_segments();
						$id= $this->uri->segment($last);
						$whr =array('customer_id'=>$id);
						$user_type = 'general';
						$user_id = $this->session->userdata("userId");
			            if($this->session->userdata("userType")=="5") {
						    $user_type = 'b2b';
						}

						$creditDays = $this->input->post('credit_days');
						if(!$this->input->post('credit_days')){
							$creditDays = '';
						}

						$r= array(
							'fid' => $this->input->post('fid'),
                            'username' => $this->input->post('username'),
                            'password' => $this->input->post('password'),
                            'franchise_name' => $this->input->post('franchise_name'),//****Personal Info
                            'franchise_relation' => $this->input->post('franchise_relation'),
                            'age' => $this->input->post('age'),
                            'email' => $this->input->post('email'),
                            'address' => $this->input->post('address'),
                            'pincode' => $this->input->post('pincode'),
                            'state' => $this->input->post('franchaise_state_id'),
                            'city' => $this->input->post('franchaise_city_id'),
                            'contact_number' => $this->input->post('contact_number'),
                            'alt_contact' => $this->input->post('alt_contact'),
                            'companytype' => $this->input->post('companytype'),//******kyc
                            'pan_name' => $this->input->post('pan_name'),
                            'pan_number' => $this->input->post('pan_number'),
                            'pancard_photo' => $pancard_photo,
                            'register_date' => $date,
                            'aadhar_number' => $this->input->post('aadhar_number'),
                            'aadharin_name' => $this->input->post('aadharin_name'),
                            'dob' => $this->input->post('dob'),
                            'gender' => $this->input->post('gender'),
                            'aadhar_address' => $this->input->post('aadhar_address'),
                            'aadharcard_photo' => $aadharcard_photo,
                            'company_name' => $this->input->post('company_name'),// ****company information
                            'cmp_pan_number' => $this->input->post('cmp_pan_number'),
                            'cmp_gstno' => $this->input->post('cmp_gstno'),
                            'legal_name' => $this->input->post('legal_name'),
                            'constitution_of_business' => $this->input->post('constitution_of_business'),
                            'taxpayer_type' => $this->input->post('taxpayer_type'),
                            'gstin_status' => $this->input->post('gstin_status'),
                            'cmp_address' => $this->input->post('cmp_address'),
                            'cmp_pincode' => $this->input->post('cmp_pincode'),
                            'cmp_state' => $this->input->post('cmp_state'),
                            'cmp_city' => $this->input->post('cmp_city'),
                            'cmp_office_phone' => $this->input->post('cmp_office_phone'),
                            'cmp_account_name' => $this->input->post('cmp_account_name'),//*****Bank Details
                            'cmp_account_number' => $this->input->post('cmp_account_number'),
                            'cancel_check' => $cancel_check,
                            'cmp_bank_name' => $this->input->post('cmp_bank_name'),
                            'cmp_bank_branch' => $this->input->post('cmp_bank_branch'),
                            'cmp_acc_type' => $this->input->post('cmp_acc_type'),
                            'cmp_ifsc_code' => $this->input->post('cmp_ifsc_code'),
                            
                            );
                            
						$result = $this->basic_operation_m->update('tbl_franchise',$r, $whr);
						
						if ($this->db->affected_rows() > 0) {
							$data['message']="data Updated Sucessfully";
						}else{
							$data['message']="Error in Query";
						}
						if(!empty($data))
						{						
							$msg			= 'Customer updated successfully';
							$class			= 'alert alert-success alert-dismissible';		
						}else{
							$msg			= 'Customer not updated successfully';
							$class			= 'alert alert-danger alert-dismissible';	
						}	
						$this->session->set_flashdata('notify',$msg);
						$this->session->set_flashdata('class',$class);

						redirect('admin/list-customer');
				}
		
		       $data['cities']= $this->basic_operation_m->get_all_result('city','');
         	   $data['states']= $this->basic_operation_m->get_all_result('state','');	
	   
              $this->load->view('admin/franchise_master/update_franchise_data',$data);  
            }
    
    
        public function deleteFranchiseData(){
          $getId = $this->input->post('getid');
          $data =  $this->db->delete('tbl_franchise',array('franchise_id'=>$getId));
         // echo $this->db->last_query();
          if($data){
			$output['status'] = 'success';
			$output['message'] = 'Member deleted successfully';
		}
		else{
			$output['status'] = 'error';
			$output['message'] = 'Something went wrong in deleting the member';
		}
 
		echo json_encode($output);
 
	}
        
}
