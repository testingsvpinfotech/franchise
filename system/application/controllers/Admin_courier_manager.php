<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_courier_manager extends CI_Controller {

	var $data 			= array();
	function __construct()
	{
		 parent:: __construct();
		 $this->load->model('basic_operation_m');		 
		 if($this->session->userdata('userId') == '')
		{
			redirect('admin');
		}
	}
	
	
	###################### View All Airlines Start ########################
	public function all_courier()
	{  	   
		$data 						= $this->data;
		$user_id					= $this->session->userdata("userId");
		$data['courier_company']	= $this->basic_operation_m->get_table_result("courier_company","");
        $this->load->view('admin/courier_master/view_courier_company',$data);      
	}


	public function add_courier()
	{  	   
		$data 		= $this->data;
		$user_id	= $this->session->userdata("userId");
        $this->load->view('admin/courier_master/view_add_courier_company',$data);      
	}

	public function upload_pincode($c_id)
	{  	   
		$data 		= $this->data;
		$user_id	= $this->session->userdata("userId");
		$data['c_id'] = $c_id;

		$data['courier_company']	= $this->basic_operation_m->get_all_result("courier_company","c_id=".$c_id);
        $this->load->view('admin/courier_master/upload_pincode',$data);      
	}

	public function insert_pincode()
	{  	   
		$data 		= $this->data;
		$user_id	= $this->session->userdata("userId");
		// print_r($_POST);
		$inputFileName = $_FILES['csv_file']['tmp_name'];
		$extension = pathinfo($_FILES['csv_file']['name'], PATHINFO_EXTENSION);
		if($extension!="xlsx" && $extension!="xls" )
		{	
			$msg			= 'Please uploade csv file.';
			$class			= 'alert alert-danger alert-dismissible';	
			$this->session->set_flashdata('notify',$msg);
			$this->session->set_flashdata('class',$class);
			
			
		}else
		{

			require_once APPPATH . "/third_party/PHPExcel.php";


			$inputFileName = $_FILES['csv_file']['tmp_name'];
	            
            try {

            	// echo "<pre>111";exit();
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
                $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

                
                // print_r($allDataInSheet);exit();

                if (!empty($allDataInSheet)) {
                	$cnt = 0;

                	foreach ($allDataInSheet as $key => $value) {

                		if ($key==1) {
                			$cnt++;
                			continue;
                		}

                		$pincode = trim($value['A']);
                		$city = trim($value['B']);
                		$state = trim($value['C']);
                		$status = trim($value['D']);
                		// $service = trim($value['E']);
                		// $COD = trim($value['F']);

                		// if ($service=='N') {
                		// 	$service = 0;
                		// }else{
                		// 	$service = 1;
                		// }

                		if ($status=='N') {
                			$COD = 0;
                		}else{
                			$COD = 1;
                		}

                		$in1 = $this->db->query("select * from pincode where pin_code = '". $pincode."'");
                        $pincode_details = $in1->row_array();


                        $in2 = $this->db->query("select region_master_details.*,region_master.region_name from region_master_details JOIN region_master  where state = '". $pincode_details['state_id']."'");
                        $pincode_details2 = $in2->row_array();

                        // echo "<pre>";
                        // print_r($pincode_details2);

                       	$data2 = array(
	                       	"pincode" => $pincode_details['pin_code'], 
							"forweder_id" => $_POST['c_id'],
							"cityid" => $pincode_details['city_id'],
							"city_name" => $pincode_details['city'],
							"statid" => $pincode_details['state_id'],
							"state_name" => $pincode_details['state'],
							// "servicable" => $service,
							"oda" => $COD,
							"regionid" => $pincode_details2['regionid'] , 
							"datec" => date('Y-m-d H:i:s'),
						);

						$this->db->insert('service_pincode',$data2);
                        // print_r($data2);exit();

                	}
                	if ($cnt > 1) {
                		# code...
                	}

                	$msg			= 'File uploaded  Successfully.';
					$class			= 'alert alert-success alert-dismissible';	
					$this->session->set_flashdata('notify',$msg);
					$this->session->set_flashdata('class',$class);
					
                	redirect('admin/view-courier-master');
                }
            }catch (Exception $e) {
               die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                        . '": ' .$e->getMessage());
            }
        }   
	}
	
	public function insert_courier()
	{  	   
		$alldata 	= $this->input->post();
		if(!empty($alldata))
		{
			$c_company		= $this->basic_operation_m->insert("courier_company",$alldata);
			$msg					= 'Courier company added successfully';
			$class					= 'alert alert-success alert-dismissible';				
		}
		else
		{
			$msg			= 'Courier not added successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view-courier-master');
	}
	
	public function delete_courier_company(){ 
	    $id = $this->input->post('getid');
		$airlines_company		= $this->db->delete("courier_company",array('c_id' => $id));
		
		 if($airlines_company){
			$output['status'] = 'success';
			$output['message'] = 'Courier deleted successfully';
		}
		else{
			$output['status'] = 'error';
			$output['message'] = 'Something went wrong in deleting the Courier';
		}
 
		echo json_encode($output);	
	}
	
	
// 	public function delete_courier_company($id)
// 	{ 
// 		$airlines_company		= $this->basic_operation_m->delete("courier_company","c_id = '$id'");
// 		$msg					= 'Courier deleted successfully';
// 		$class					= 'alert alert-success alert-dismissible';	

// 		$this->session->set_flashdata('notify',$msg);
// 		$this->session->set_flashdata('class',$class);
		
// 		redirect('admin/view-courier-master');
// 	}
	
	public function edit_courier_company($id)
	{  
		$data = $this->data;
		if(!empty($id))
		{
			$data['courier_company']= $this->basic_operation_m->get_table_row("courier_company","c_id = '$id'");			
		}		
		$this->load->view('admin/courier_master/view_edit_courier_company',$data);
	}
	
	public function update_courier_company($id)
	{  
		$alldata 	= $this->input->post();
		if(!empty($alldata))
		{
			$status	= $this->basic_operation_m->update("courier_company",$alldata,"c_id = '$id'");
			
			$msg	= 'Courier updated successfully';
			$class	= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg	= 'Courier not updated successfully';
			$class	= 'alert alert-danger alert-dismissible';	
			
		}
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view-courier-master');
	
	}
	
	###################### View Courier wise fixed price ########################	

	public function all_courier_fixed_price($id)
	{  	   
		$data 					= $this->data;
		$user_id				= $this->session->userdata("userId");	
		$data['edit_country_id']= $id;
		$data['charge_master']	= $this->basic_operation_m->get_charge_master_result($id);
		
        $this->load->view('admin/courier_master/view_courier_fixed_price',$data);      
	}	
	public function view_add_courier_fixed_price()
	{  	   
		$data 		= $this->data;
		$user_id	= $this->session->userdata("userId");
		$data['courier_company']	= $this->basic_operation_m->get_all_result("courier_company","");			
        $this->load->view('admin/courier_master/view_add_courier_fixed_price',$data);      
	}
	public function insert_courier_fixed_charge()
	{  	   
		$alldata 	= $this->input->post();
		if(!empty($alldata))
		{
			$c_company		= $this->basic_operation_m->insert("courier_charge_master",$alldata);
			$msg					= 'Courier company uploaded successfully';
			$class					= 'alert alert-success alert-dismissible';				
		}
		else
		{
			$msg			= 'Courier not uploaded successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view_courier_fixed_price');
	}
	
	public function delete_courier_fixed_charge($id)
	{ 
		$airlines_company		= $this->basic_operation_m->delete("courier_charge_master","id = '$id'");
		$msg					= 'Courier deleted successfully';
		$class					= 'alert alert-success alert-dismissible';	
		
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view_courier_fixed_price');
	}
	public function view_edit_courier_fixed_price($id)
	{  
		$data = $this->data;
		if(!empty($id))
		{
			$data['courier_company']	= $this->basic_operation_m->get_all_result("courier_company","");
			$data['courier_charge_master']= $this->basic_operation_m->get_charge_master_row("id = '$id'");
			
		}
		
		$this->load->view('admin/courier_master/view_edit_courier_fixed_price',$data);
	}
	
	public function update_courier_fixed_price($id)
	{  
		$alldata 	= $this->input->post();
		if(!empty($alldata))
		{
			$status	= $this->basic_operation_m->update("courier_charge_master",$alldata,"id = '$id'");
			
			$msg	= 'Charges updated successfully';
			$class	= 'alert alert-success alert-dismissible';	
			
		}
		else
		{
			$msg			= 'Charges not updated successfully';
			$class			= 'alert alert-danger alert-dismissible';	
			
		}
		$this->session->set_flashdata('notify',$msg);
		$this->session->set_flashdata('class',$class);
		
		redirect('admin/view_courier_fixed_price');
	
	}
}
?>
