<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_vehicle extends CI_Controller {

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
		$resAct	= $this->db->query("select * from vehicle_master");		
		if($resAct->num_rows()>0)
		{
		 	$data['allvehicle']=$resAct->result_array();	            
        }
        $this->load->view('admin/vehicle_master/view_vehicle',$data);       
	}
	
	public function add_vehicle()
	{      
       
		$data['message']				= "";
		$array['airway_no_from'] 		= array();
		$array['airway_no_to'] 			= array();
		$array['branch_code'] 			= array();
		
		if(isset($_POST['submit']))
        {
            $all_data = $this->input->post();
			unset($all_data['submit']);
			$result=$this->basic_operation_m->insert('vehicle_master',$all_data);
			if($this->db->affected_rows()>0) 
			{
				$data['message']="cnode Added Sucessfully";
			}
			else
			{
                $data['message']="Error in Query";
			}
				redirect('admin/list-vehicle');
		}
		 $this->load->view('admin/vehicle_master/add_vehicle',$data);
	}
	
	public function getPincodeInfo()
    {
       $pincode = $this->input->post('pincode');
		$whr1 = array('pin_code' => $pincode);
		$res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);	
		$pincode_info = $res1->row();
		echo json_encode($pincode_info);
    }
	
	public function edit_vehicle($vehicle_id)
	{
		$data['message']="";
		$resAct=$this->basic_operation_m->getAll('vehicle_master',"vm_id = '$vehicle_id'");
		if($resAct->num_rows()>0)
		{
			$data['vehicle_info']=$resAct->row();
		} 
	   
		if(isset($_POST['submit'])) 
		{
			$all_data = $this->input->post();
			unset($all_data['submit']);
			$whr =array('vm_id'=>$vehicle_id);
			$result=$this->basic_operation_m->update('vehicle_master',$all_data, $whr);
			if ($this->db->affected_rows() > 0) 
			{
				$data['message']="Cnode Updated Sucessfully";
			}
			else
			{
                $data['message']="Error in Query";
			}
            redirect('admin/list-vehicle');
		}
	    $this->load->view('admin/vehicle_master/edit_vehicle',$data);
	}
	
	public function delete_vehicle(){
	    
	    $id = $this->input->post('getid');
		if($id!="")
		{
		    $whr =array('vm_id'=>$id);
			$res=$this->basic_operation_m->delete('vehicle_master',$whr);
		
		   	$output['status'] = 'success';
			$output['message'] = 'Vehicle deleted successfully';
		}
		else{
			$output['status'] = 'error';
			$output['message'] = 'Something went wrong in deleting the Vehicle';
		}
 
		echo json_encode($output);	

	}
	
	
	
	
}
?>