<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_city extends CI_Controller{
	
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
        $data['allcitydata']=$this->basic_operation_m->selectCityRecord();
         $this->load->view('admin/city_master/view_city',$data); 
	}
	
	public function add_city()
	{
		$data['message']="";
		$data['allstate']=$this->basic_operation_m->get_all_result('state','');
		if (isset($_POST['submit'])) {
			$r= array('id'=>'',
				      'state_id'=>$this->input->post('state_id'),
		             'city' => $this->input->post('city_name'),	                 
                     //'isdeleted' =>'0',
                 );
			$result=$this->basic_operation_m->insert('city',$r);
			 
			if ($this->db->affected_rows()>0) {
				$data['message']="Event Added Sucessfully";
			}else{
                $data['message']="Error in Query";
			}
             redirect('admin/list-city'); 
		}
	    $this->load->view('admin/city_master/add_city',$data);
	}
	
	
	public function update_city($city_id)
	{
		$data['message']="";		
		$data['allstate']=$this->basic_operation_m->get_all_result('state');
       
	
		if($city_id!="")
		{
		    $whr =array('id'=>$city_id);
			$data['c']=$this->basic_operation_m->get_table_row('city',$whr);
			
		}
		if (isset($_POST['submit'])) {
			
	        $whr =array('id'=>$city_id);
			$r= array('state_id' => $this->input->post('state_id'),
						'city' => $this->input->post('city_name')						
                 );
			$result=$this->basic_operation_m->update('city',$r, $whr);
			if ($this->db->affected_rows() > 0) {
				$data['message']="City Updated Sucessfully";
			}else{
                $data['message']="Error in Query";
			}
            redirect('admin/list-city');
		}
	    $this->load->view('admin/city_master/edit_city',$data);
	}
	public function delete_city() {
	    $city_id = $this->input->post('getid');
	    
		if($city_id!="")
		{
		    $whr =array('id'=>$city_id);
			$res=$this->basic_operation_m->delete('city',$whr);
			
          	$output['status'] = 'success';
			$output['message'] = 'City deleted successfully';
		}
		else{
			$output['status'] = 'error';
			$output['message'] = 'Something went wrong in deleting the City';
		}
 
		echo json_encode($output);	
	  
	}
	
// 	public function delete_city($city_id)
// 	{
// 		$data['message']="";
       
// 		if($city_id!="")
// 		{
// 		    $whr =array('id'=>$city_id);
// 			$res=$this->basic_operation_m->delete('city',$whr);
			
//             redirect('admin/list-city');
// 		}		
	  
// 	}
}

?>