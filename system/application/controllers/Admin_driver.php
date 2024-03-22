<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_driver extends CI_Controller {

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
		$resAct	= $this->db->query("select * from driver_master");		
		if($resAct->num_rows()>0)
		{
		 	$data['alldriver']=$resAct->result_array();	            
        }
        $this->load->view('admin/driver_master/view_driver',$data);       
	}
	
	public function add_driver()
	{      
       
		$data['message']				= "";
	
		
		if(isset($_POST['submit']))
        {
            $all_data = $this->input->post();
			unset($all_data['submit']);
			
			
			$result=$this->basic_operation_m->insert('driver_master',$all_data);
			if($this->db->affected_rows()>0) 
			{
				$data['message']="Driver Added Sucessfully";
			}
			else
			{
                $data['message']="Error in Query";
			}
		
				redirect('admin/list-driver');
		}
		 $this->load->view('admin/driver_master/add_driver',$data);
	}
	
	public function getPincodeInfo()
    {
       $pincode = $this->input->post('pincode');
		$whr1 = array('pin_code' => $pincode);
		$res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);	
		$pincode_info = $res1->row();
		echo json_encode($pincode_info);
    }
	
	public function edit_driver($driver_id)
	{
		$data['message']="";
		$resAct=$this->basic_operation_m->getAll('driver_master',"dm_id = '$driver_id'");
		if($resAct->num_rows()>0)
		{
			$data['driver_info']=$resAct->row();
		} 
	   
		if(isset($_POST['submit'])) 
		{
			$all_data = $this->input->post();
			unset($all_data['submit']);
			$whr =array('dm_id'=>$driver_id);
			$result=$this->basic_operation_m->update('driver_master',$all_data, $whr);
			if ($this->db->affected_rows() > 0) 
			{
				$data['message']="Cnode Updated Sucessfully";
			}
			else
			{
                $data['message']="Error in Query";
			}
            redirect('admin/list-driver');
		}
	    $this->load->view('admin/driver_master/edit_driver',$data);
	}
	
	public function delete_driver()
	{
	    $id = $this->input->post('getid');
	
		if($id!="")
		{
		    $whr =array('dm_id'=>$id);
			$res=$this->basic_operation_m->delete('driver_master',$whr);
			
			$output['status'] = 'success';
			$output['message'] = 'Driver Data deleted successfully';
		}
		else{
			$output['status'] = 'error';
			$output['message'] = 'Something went wrong in deleting the Driver Data';
		}
 
		echo json_encode($output);	
           
	  
	}
	
	
	
	
}
?>