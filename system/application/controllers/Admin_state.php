<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_state extends CI_Controller {

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
        $data['allstatedata']=$this->basic_operation_m->selectStateRecord();
        $this->load->view('admin/state_master/view_state',$data);       
	}

	public function add_state()
	{
		$data['message']="";       
       	$data['allcountry']=$this->basic_operation_m->get_all_result('tbl_country');      
	   	$data['allregion']=$this->basic_operation_m->get_all_result('region_master','');
	   	
		if (isset($_POST['submit'])) {
			
			$r= array('id'=>'',
				      'country_id'=>$this->input->post('country_id'),
					  'region_id'=>$this->input->post('region_id'),
		             'state' => $this->input->post('state_name'),
		             'edd_train' => $this->input->post('edd_train'),
		             'edd_air' => $this->input->post('edd_air'),
		             
                     //'isdeleted' =>'0',
                 );
			$result=$this->basic_operation_m->insert('state',$r);
			if ($this->db->affected_rows()>0) {
				$data['message']="Event Added Sucessfully";
			}else{
                $data['message']="Error in Query";
			}
             redirect('admin/list-state'); /*-------when u add a city and save it, thn it will be go to all city*/
		}
	    $this->load->view('admin/state_master/add_state',$data);
	}

	public function update_state($id)
	{
		$data['message']="";
       	$data['allcountry']=$this->basic_operation_m->get_all_result('tbl_country'); 
	    $data['allregion']= $this->basic_operation_m->get_all_result('region_master','');
      
		if($id!="")
		{
		    $whr =array('id'=>$id);
			$data['s']=$this->basic_operation_m->get_table_row('state',$whr);
		}
		if (isset($_POST['submit'])) {
			$last = $this->uri->total_segments();
	        $id= $this->uri->segment($last);
	        $whr =array('id'=>$id);
			$r= array('country_id' => $this->input->post('country_id'),
					  'region_id'=>$this->input->post('region_id'),
					  'state' => $this->input->post('state_name'),
					  'edd_train' => $this->input->post('edd_train'),
		              'edd_air' => $this->input->post('edd_air'),
		                               
                 );
			$result=$this->basic_operation_m->update('state',$r, $whr);
			if ($this->db->affected_rows() > 0) {
				$data['message']="Region Updated Sucessfully";
			}else{
                $data['message']="Error in Query";
			}
            redirect('admin/list-state');
		}
	    $this->load->view('admin/state_master/edit_state',$data);
	}
	public function deletestate(){
	     $id = $this->input->post('getid'); 
		if($id!="")
		{
		    $whr =array('id'=>$id);
			$res=$this->basic_operation_m->delete('state',$whr);
			
            	$airlines_company		= $this->basic_operation_m->delete("courier_fuel","cf_id = '$id'");
			$airlines_company		= $this->basic_operation_m->delete("courier_fuel_detail","cf_id = '$id'");

			$output['status'] = 'success';
			$output['message'] = 'State deleted successfully';
		}
		else{
			$output['status'] = 'error';
			$output['message'] = 'Something went wrong in deleting the State';
		}
 
		echo json_encode($output);	
	}		
	  
	
}
?>
