<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_cnode extends CI_Controller {

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
		$resAct	= $this->db->query("select * from tbl_assign_cnode,tbl_branch where tbl_assign_cnode.branch_code=tbl_branch.branch_code
							");		
		if($resAct->num_rows()>0)
		 {
		 	$data['allcnode']=$resAct->result_array();	            
         }
         $this->load->view('admin/cnode_master/view_cnode',$data);       
	}
	public function add_cnode()
	{      
       
		$data['message']				= "";
		$array['airway_no_from'] 		= array();
		$array['airway_no_to'] 			= array();
		$array['branch_code'] 			= array();
		$all_code			=	$this->basic_operation_m->get_table_result('tbl_assign_cnode',"");
		
		if(!empty($all_code))
		{
			foreach($all_code as $key => $values)
			{
				$array['airway_no_from'][] 		= $values->airway_no_from;
				$array['airway_no_to'][] 			= $values->airway_no_to;
				$array['branch_code'][] 			= $values->branch_code;
				
			}
			$branch_code	 			= 	implode("','",array_filter($array['branch_code'])); 
			$resAct						=	$this->basic_operation_m->getAll('tbl_branch',"branch_code NOT IN('$branch_code')");
	
			if($resAct->num_rows()>0)
			{
				$data['branch']	=	$resAct->result_array();
			} 

			$airway_no_from			= 	implode("','",array_filter($array['airway_no_from']));
			$airway_no_to 			= 	implode("','",array_filter($array['airway_no_to']));
			$reAct					=	$this->basic_operation_m->getAll('tbl_cnode_master',"airway_to NOT IN('$airway_no_to') and airway_no NOT IN('$airway_no_from')");
			
			if($reAct->num_rows()>0)
			{
				$data['cnode']	=	$reAct->result_array();
			} 
		}
		else
		{
			$resAct				=	$this->basic_operation_m->getAll('tbl_branch','');
			if($resAct->num_rows()>0)
			{
				$data['branch']	=	$resAct->result_array();
			} 

			$reAct		=	$this->basic_operation_m->getAll('tbl_cnode_master','');
			if($reAct->num_rows()>0)
			{
				$data['cnode']	=	$reAct->result_array();
			} 

			$resAct		=	$this->basic_operation_m->getAll('tbl_assign_cnode','');
			if($resAct->num_rows()>0)
			{
				$data['assign']	=	$resAct->result_array();
			}
		}
		
		
		
		
		if(isset($_POST['submit']))
        {
            
            $data	=	array('id'=>'',
            	        'airway_no_from'=>$this->input->post('airway_no_from'),
            	    	'airway_no_to'=>$this->input->post('airway_no_to'),
            			'branch_code'=>$this->input->post('branch_code'),
            			'date'=>date('d-m-Y H:i:s A'),
            		);

			$result=$this->basic_operation_m->insert('tbl_assign_cnode',$data);
			if($this->db->affected_rows()>0) 
			{
				$data['message']="cnode Added Sucessfully";
			}
			else
			{
                $data['message']="Error in Query";
			}
				redirect('admin/list-cnode');
		}
		 $this->load->view('admin/cnode_master/add_cnode',$data);
	}
	public function updatecnode()
	{
		$data['message']="";
		$resAct=$this->basic_operation_m->getAll('tbl_branch','');
       if($resAct->num_rows()>0)
       {
       	$data['branch']=$resAct->result_array();
       } 
	    $reAct=$this->basic_operation_m->getAll('tbl_cnode_master','');
       if($reAct->num_rows()>0)
       {
       	$data['cnode']=$reAct->result_array();
       } 
		$last = $this->uri->total_segments();
	    $id	= $this->uri->segment($last);
		if($id!="")
		{
		    $whr =array('id'=>$id);
			$res=$this->basic_operation_m->selectRecord('tbl_assign_cnode',$whr);
			if($res->num_rows() > 0)
			{
				$data['master']= $res->result_array();
			}
		}
		if (isset($_POST['submit'])) {
			$last = $this->uri->total_segments();
	        $id= $this->uri->segment($last);
	        $whr =array('id'=>$id);
			$r= array('branch_code'=>$this->input->post('branch_code'),
			'airway_no_from' => $this->input->post('airway_no_from'),
					'airway_no_to'=>$this->input->post('airway_no_to'),
		           
					);
					
			$result=$this->basic_operation_m->update('tbl_assign_cnode',$r, $whr);
			if ($this->db->affected_rows() > 0) {
				$data['message']="Cnode Updated Sucessfully";
			}else{
                $data['message']="Error in Query";
			}
            redirect('admin/list-cnode');
		}
	    $this->load->view('admin/cnode_master/editcnode',$data);
	}
	public function delete_cnode($id)
	{
		$data['message']="";
       if($id!="")
		{
		    $whr =array('id'=>$id);
			$res=$this->basic_operation_m->delete('tbl_assign_cnode',$whr);
			
            redirect('admin/list-cnode');
		}		
	  
	}
	
	
	
	
}
?>