<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_smtp_config extends CI_Controller{
	
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
		$data['allsmtp']=$this->basic_operation_m->get_all_result('tbl_smtp_config','');		 
        $this->load->view('admin/compnay_setting/viewsmtp',$data);      
	}	
	public function add_smtp()
	{
		$data['message']="";
		if (isset($_POST['submit'])) 
		{
			$r= array('id'=>'',
					 'port_no' => $this->input->post('port_no'),
		             'host' => $this->input->post('host'),
					 'username' => $this->input->post('username'),
					 'password' => $this->input->post('password'),
                  );
			$result=$this->basic_operation_m->insert('tbl_smtp_config',$r);
			$lastid=$this->db->insert_id();
			 
			if ($this->db->affected_rows()>0) {
				$data['message']="Event Added Sucessfully";
			}else{
                $data['message']="Error in Query";
			}
				if(!empty($lastid))
				{						
					$msg			= 'SMTP added successfully';
					$class			= 'alert alert-success alert-dismissible';		
				}else{
					$msg			= 'SMTP not added successfully';
					$class			= 'alert alert-danger alert-dismissible';	
				}	

				$this->session->set_flashdata('notify',$msg);
				$this->session->set_flashdata('class',$class);

            redirect('admin/list-smtp'); 
		}
	    $this->load->view('admin/compnay_setting/addsmtp',$data);
	}
	
	
	public function update_smtp($id)
	{
		$data['message']="";
        $last = $this->uri->total_segments();
	    $id	= $this->uri->segment($last);
		if($id!="")
		{
		    $whr =array('id'=>$id);
			$data['smtp']=$this->basic_operation_m->get_table_row('tbl_smtp_config',$whr);			
		}
		if (isset($_POST['submit'])) {
			$last = $this->uri->total_segments();
	        $id= $this->uri->segment($last);
	        $whr =array('id'=>$id);
			$r= array('port_no' => $this->input->post('port_no'),
					'host' => $this->input->post('host'),
					'username' => $this->input->post('username'),
					'password' => $this->input->post('password'),
		         );
			$result=$this->basic_operation_m->update('tbl_smtp_config',$r, $whr);
			if ($this->db->affected_rows() > 0) {
				$data['message']="Region Updated Sucessfully";
			}else{
                $data['message']="Error in Query";
			}
					if(!empty($r))
					{						
						$msg			= 'SMTP updated successfully';
						$class			= 'alert alert-success alert-dismissible';		
					}else{
						$msg			= 'SMTP not updated successfully';
						$class			= 'alert alert-danger alert-dismissible';	
					}	
					$this->session->set_flashdata('notify',$msg);
					$this->session->set_flashdata('class',$class);

            redirect('admin/list-smtp');
		}
	    $this->load->view('admin/compnay_setting/editsmtp',$data);
	}
	public function delete_smtp($id)
	{
		$data['message']="";
        $last = $this->uri->total_segments();
	    $id	= $this->uri->segment($last);
		if($id!="")
		{
		    $whr =array('id'=>$id);
			$res=$this->basic_operation_m->delete('tbl_smtp_config',$whr);
			
			$msg			= 'SMTP Deleted successfully';
			$class			= 'alert alert-success alert-dismissible';	
			$this->session->set_flashdata('notify',$msg);
			$this->session->set_flashdata('class',$class);		

            redirect('admin/list-smtp');
		}		
	  
	}
}

?>