<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_vendor extends CI_Controller {

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
		$resAct	= $this->db->query("select * from tbl_vendor");		
		if($resAct->num_rows()>0)
		{
		 	$data['allvendor']=$resAct->result_array();	            
        }
        $this->load->view('admin/vendor_master/view_vendor',$data);       
	}
	
	public function add_vendor()
	{      
       
		$data['message']				= "";
		$array['airway_no_from'] 		= array();
		$array['airway_no_to'] 			= array();
		$array['branch_code'] 			= array();
		
		if(isset($_POST['submit']))
        {
            $all_data = $this->input->post();
			unset($all_data['submit']);
			$result=$this->basic_operation_m->insert('tbl_vendor',$all_data);
			if($this->db->affected_rows()>0) 
			{
				$data['message']="cnode Added Sucessfully";
			}
			else
			{
                $data['message']="Error in Query";
			}
				redirect('admin/list-vendor');
		}
		 $this->load->view('admin/vendor_master/add_vendor',$data);
	}
	
	public function getPincodeInfo()
    {
       $pincode = $this->input->post('pincode');
		$whr1 = array('pin_code' => $pincode);
		$res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);	
		$pincode_info = $res1->row();
		echo json_encode($pincode_info);
    }
	
	public function edit_vendor($vendor_id)
	{
		$data['message']="";
		$resAct=$this->basic_operation_m->getAll('tbl_vendor',"tv_id = '$vendor_id'");
		if($resAct->num_rows()>0)
		{
			$data['vendor_info']=$resAct->row();
		} 
	   
		if(isset($_POST['submit'])) 
		{
			$all_data = $this->input->post();
			unset($all_data['submit']);
			$whr =array('tv_id'=>$vendor_id);
			$result=$this->basic_operation_m->update('tbl_vendor',$all_data, $whr);
			if ($this->db->affected_rows() > 0) 
			{
				$data['message']="Cnode Updated Sucessfully";
			}
			else
			{
                $data['message']="Error in Query";
			}
            redirect('admin/list-vendor');
		}
	    $this->load->view('admin/vendor_master/edit_vendor',$data);
	}
	
	public function delete_vendor()
	{
	    $id = $this->input->post('getid');
	//	$data['message']="";
		if($id!="")
		{
		    $whr =array('tv_id'=>$id);
			$res=$this->basic_operation_m->delete('tbl_vendor',$whr);
			
			$output['status'] = 'success';
	     	$output['message'] = 'Vendor deleted successfully';
			
		}else{ 
		    $output['status'] = 'error';
		    $output['message'] = 'Something went wrong in deleting the Vendor';
			
           // redirect('admin/list-vendor');
		}
			echo json_encode($output);
	  
	}
	
	
	
	
}
?>