<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_cnodemaster extends CI_Controller {

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
		$data['allcnode']=$this->basic_operation_m->get_all_result('tbl_cnode_master','');	
        $this->load->view('admin/cnode_master/view_cnode_master',$data);
      
	}
	public function add_cnode()
	{
		$data['message']="";
		if(isset($_POST['submit']))
          {
            
            $data	= $this->input->post();
            unset($data['submit']);
			
			$existance	=	$this->basic_operation_m->get_table_row('tbl_cnode_master',"airway_to >= ".$data['airway_no']);
					
			if(empty($existance))
			{				
				$result=$this->basic_operation_m->insert('tbl_cnode_master',$data);
				$data['message']="cnode Added Sucessfully";
				$message 			= 'Cnode Added Sucessfully';
				$class 				= 'alert-success';
					
				$this->session->set_flashdata('notify',$message);
				$this->session->set_flashdata('class',$class);
			}
			else
			{
				$message 			= 'Range Already Exist';
				$class 				= 'alert-danger';
					
				$this->session->set_flashdata('notify',$message);
				$this->session->set_flashdata('class',$class);
				
                $data['message']="Error in Query";
			}
				redirect('admin/list-cnodemaster');
		}
		 $this->load->view('admin/cnode_master/add_cnode_master',$data);
	}
}
?>