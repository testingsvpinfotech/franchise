<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_change_pincode extends CI_Controller {

	function __construct()
	{
		 parent:: __construct();
		 $this->load->model('basic_operation_m');
		 if($this->session->userdata('userId') == '')
		{
			redirect('admin');
		}
	}
	
	public function index()
	{
        $data= array();		
		$data['pincode_records']=$this->basic_operation_m->get_all_result('pincode','');
		$this->load->view('view_pincode_list',$data);
             
	}
	public function edit_pincode_city()
	{
		$data['message']="";
		if(isset($_POST['submit']))
        {
          	/* $last = $this->uri->total_segments();
			$id= $this->uri->segment($last); */
			
			$pincode = $this->input->post('pincode');
			           
			$city	= $this->input->post('city');
			$state_id	= $this->input->post('state_id');

            $whr_c=array("city"=>$city);
            $city_list= $this->basic_operation_m->get_table_row("city",$whr_c);			
						
			if(!empty($city_list))
			{
				$city_id = $city_list->id;
				$city = $city_list->city;			
				
			}else{
				$city_data= array('city' => $this->input->post('city'),'state_id' => $state_id);
				$city_list= $this->basic_operation_m->insert("city",$city_data);							
				
				$city_id = $this->db->insert_id();
				$city = $this->input->post('city');
			}
			if($city_id!="")
			{
				$whr_s=array("id"=>$state_id);
				$state_list= $this->basic_operation_m->get_table_row("state",$whr_s);				
				$state = $state_list->state;
			
				$whr =array('pin_code'=>$pincode);
				$data=array('city_id' => $city_id,'city' => $city,'state_id' => $state_id,'state' => $state);
				$result=$this->basic_operation_m->update('pincode',$data,$whr);
				
				$message 			= 'City & State Updated Sucessfully';
				$class 				= 'alert-success';
					
				$this->session->set_flashdata('notify',$message);
				$this->session->set_flashdata('class',$class);
			}
			else
			{
				$message 			= 'City & State not Updated successfully';
				$class 				= 'alert-danger';
					
				$this->session->set_flashdata('notify',$message);
				$this->session->set_flashdata('class',$class);
				
                $data['message']="Error in Query";
			}
				redirect('admin/change-pincode-city');
		}
		 $data['city_list']= $this->basic_operation_m->get_all_result("city","");
		 $data['state_list']= $this->basic_operation_m->get_all_result("state","");
		 $this->load->view('admin/city_master/edit_pincode_city',$data);
	}
	public function getCityList()
    {
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
}
?>