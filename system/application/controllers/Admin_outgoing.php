<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_outgoing extends CI_Controller{
	
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
		$whrAct=array('isactive'=>1,'iseleted'=>0);
		$resAct=$this->db->query('select * from tbl_outword,tbl_domestic_menifiest where
									tbl_outword.manifiest_id	=tbl_domestic_menifiest.manifiest_id');
		//$resAct=$this->basic_operation_m->getAll('tbl_outword','');
		 if($resAct->num_rows()>0)
		 {
		 	$data['alloutword']=$resAct->result_array();	            
         }
         $this->load->view('admin/outgoing/view_outgoing',$data);
     
		
	}
	
	public function add_outgoing()
	{
		$data['message']="";//for branch code
		$reAct	= $this->basic_operation_m->getAll('tbl_domestic_menifiest','');

		if($reAct->num_rows()>0)
		{
		 	$data['menifiest']=$reAct->result_array();	            
        }
		//for pod_no
		// $resAct	= $this->basic_operation_m->getAll('tbl_customers','');

		// if($resAct->num_rows()>0)
		 // {
		 	// $data['customer']=$resAct->result_array();	            
         // }
		
		 $this->load->view('admin/outgoing/addoutgoing', $data);
	}
	
	public function insert_outgoing()
	{
		$all_data 		= $this->input->post();
		if (!empty($all_data)) 
		{
			$date=date('y-m-d');
			$r= array('id'=>'',
		             'manifiest_id' => $this->input->post('manifiest_id'),
					 'airway_no' => $this->input->post('airway_no'),
					 //'customer_id' => $this->input->post('customer_id'),
					 'status' => $this->input->post('status'),
					 'date' => $date,
                 );
			$data1=array('id'=>'',
						 'pod_no'=>$this->input->post('airway_no'),
						 'status'=>'forworded',
						 'branch_name'=>$this->input->post('branch_name'),
						 'tracking_date'=>$date,
							  );
			$result=$this->basic_operation_m->insert('tbl_outword',$r);
			 
			if ($this->db->affected_rows()>0) {
				$data['message']="Event Added Sucessfully";
			}else{
                $data['message']="Error in Query";
			}
             redirect('admin/view-outgoing'); 
		}
	}
	
	public function editoutgoing($id)
	{
		$data['message']="";//for branch code
		 $resAct= $this->basic_operation_m->getAll('tbl_customers','');
			if($resAct->num_rows()>0)
			 {
				$data['customer']=$resAct->result_array();	            
			 }
		 
		//for pod_no
		
		$last = $this->uri->total_segments();
	    $id	= $this->uri->segment($last);
		if($id!="")
		{
		    $whr =array('manifiest_id'=>$id);
			$res=$this->basic_operation_m->selectRecord('tbl_outword',$whr);
			if($res->num_rows() > 0)
			{
				$data['outword']= $res->result_array();
			}
			$reAct	= $this->basic_operation_m->getAll('tbl_domestic_menifiest',$whr);

			if($reAct->num_rows()>0)
			 {
				$data['menifiest']=$reAct->result_array();	            
			 }
			
		}
	  
		 $this->load->view('admin/outgoing/editoutgoing', $data);
	}
	
	public function updateoutgoing($id)
	{
		$all_data = $this->input->post();
		if (!empty($all_data)) 
		{
			
	        $whr =array('id'=>$id);
			
			$r= array(//'id'=>'',
		             //'menifiest_id' =>$id, //$this->input->post('manifiest_id'),
					 'airway_no' => $this->input->post('airway_no'),
					 'customer_id' => $this->input->post('customer_id'),
					 'status' => $this->input->post('status'),
					 'date' => $this->input->post('date'),
                 );
			$result=$this->basic_operation_m->update('tbl_outword',$r,$whr);
			 
			if ($this->db->affected_rows()>0) {
				$data['message']="Event Added Sucessfully";
			}else{
                $data['message']="Error in Query";
			}
		}
             redirect('admin/view-outgoing'); 
		
	}
	
	public function deleteoutgoing()
	{
		$id = $this->input->post('getid');
		if($id!="")
		{
		    $whr =array('manifiest_id'=>$id);
			$res=$this->basic_operation_m->delete('tbl_outword',$whr);
			
			
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