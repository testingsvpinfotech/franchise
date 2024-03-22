<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_country extends CI_Controller{
	
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
		$data['allcountrydata']=$this->basic_operation_m->get_all_result('tbl_country','');  
         $this->load->view('admin/country_master/view_country',$data);
	}
	
	public function add_country()
	{
		$data['message']="";
		if (isset($_POST['submit'])) 
		{
			$r= array('country_name' => $this->input->post('country_name'),                    
                 );
			$result=$this->basic_operation_m->insert('tbl_country',$r);
			 
			if ($this->db->affected_rows()>0) {
				$data['message']="Event Added Sucessfully";
			}else{
                $data['message']="Error in Query";
			}
             redirect('admin/list-country'); 
		}
	    $this->load->view('admin/country_master/add_country',$data);
	}
	
	
	public function update_country($id)
	{
		$data['message']="";       
		if($id!="")
		{
		    $whr =array('country_id'=>$id);
			$data['cntry']= $this->basic_operation_m->get_table_row('tbl_country',$whr);
			
		}
		if (isset($_POST['submit'])) {
			$last = $this->uri->total_segments();
	        $id= $this->uri->segment($last);
	        $whr =array('country_id'=>$id);
			$r= array('country_name' => $this->input->post('country_name')
		                               
                 );
			$result=$this->basic_operation_m->update('tbl_country',$r, $whr);
			if ($this->db->affected_rows() > 0) {
				$data['message']="Country Updated Sucessfully";
			}else{
                $data['message']="Error in Query";
			}
            redirect('admin/list-country');
		}
	    $this->load->view('admin/country_master/edit_country',$data);
	}
	public function delete_country()
	{
	  $id = $this->input->post('getid');
	  
		if($id!="")
		{
		    $whr =array('country_id'=>$id);
			$res=$this->basic_operation_m->delete('tbl_country',$whr);
			
           	$output['status'] = 'success';
			$output['message'] = 'Data deleted successfully';
		}
		else{
			$output['status'] = 'error';
			$output['message'] = 'Something went wrong in deleting the Data';
		}
 
		echo json_encode($output);	
	  
	}
}

?>