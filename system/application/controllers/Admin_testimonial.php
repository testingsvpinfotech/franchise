<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_testimonial extends CI_Controller {

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
		$data['alltestimonial']=$this->basic_operation_m->get_all_result('tbl_testimonial','');   
        $this->load->view('admin/testimonial_master/view_testimonial',$data);
	}
	
	public function add_testimonial()
	{		 
		$data['testimonial']=$this->basic_operation_m->get_all_result('tbl_testimonial','');   
		$data['message']="";

		if (isset($_POST['submit'])) {			
			    $r= array('id'=>'',		            
		             'name'=>$this->input->post('name'),
		             'message'=>$this->input->post('message'),
					 'image'=>'',
					 'designation'=>$this->input->post('designation'),		                                  
                 );

					$result=$this->basic_operation_m->insert('tbl_testimonial',$r);
                    $lastid=$this->db->insert_id();
            
                    $config['upload_path'] = "assets/testimonial/";
					$config['allowed_types'] = 'gif|jpg|png';					
					$config['file_name'] = 'testimonial_'.$lastid.'.jpg';
					
					
					$this->load->library('upload',$config);
					$this->upload->initialize($config);
					$this->upload->set_allowed_types('*');

					
					$data['upload_data'] = '';
					$url_path="";
					if (!$this->upload->do_upload('image'))
					{ 
						$data = array('msg' => $this->upload->display_errors());
					}
					else 
					{ 
						$image_path = $this->upload->data();
					}
					
					$data =array('image'=>$image_path['file_name']);
					$whr=array('id'=>$lastid);
					$this->basic_operation_m->update('tbl_testimonial',$data,$whr);

				if ($this->db->affected_rows()>0) {
					$data['message']="testimonial Image Added Sucessfully";
				}else{
					$data['message']="Error in Query";
				}
				 redirect('admin/list-testimonial'); 
		}
	    $this->load->view('admin/testimonial_master/add_testimonial',$data);
	}
	
	public function update_testimonial($id)
	{
		$data['message']="";
		
		if($id!="")
		{
		    $whr =array('id'=>$id);
			$data['row']= $this->basic_operation_m->get_table_row('tbl_testimonial',$whr);			
		}
		
		$data['message']="";

		if (isset($_POST['submit'])) {
			$date=date('y-m-d');
			
			$whr=array('id'=>$id);
			    
                    $config['upload_path'] = "assets/testimonial/";
					$config['allowed_types'] = 'gif|jpg|png';					
					$config['file_name'] = 'news_'.$id.'.jpg';
					
					
					$this->load->library('upload',$config);
					$this->upload->initialize($config);
					$this->upload->set_allowed_types('*');

					$data['upload_data'] = '';
					$url_path="";
					if (!$this->upload->do_upload('image'))
					{ 
						$data = array('msg' => $this->upload->display_errors());
					}
					else 
					{ 
						$image_path = $this->upload->data();
					}
					$data =array(
					'name'=>$this->input->post('name'),
					'message'=>$this->input->post('message'),
					
					'designation'=>$this->input->post('designation'),
					'image'=>$image_path['file_name']);
					//$whr=array('id'=>$id);
					$result=$this->basic_operation_m->update('tbl_testimonial',$data,$whr);

				if ($this->db->affected_rows()>0) {
					$data['message']="testimonial Image Updated Sucessfully";
				}else{
					$data['message']="Error in Query";
				}
				 redirect('admin/list-testimonial'); 
		}
	    $this->load->view('admin/testimonial_master/edit_testimonial',$data);
	}
	
	public function delete_testimonial(){
	    
	    $id = $this->input->post('getid');
     
		if($id!="")
		{
		    $whr = array('id' => $id);
			$res=$this->basic_operation_m->delete('tbl_testimonial',$whr);
		    
		    $output['status'] = 'success';
			$output['message'] = 'Testimonial deleted successfully';
		}
		else{
			$output['status'] = 'error';
			$output['message'] = 'Something went wrong in deleting the Testimonial';
		}
 
		echo json_encode($output);	
		    
	}		
	  
	
}