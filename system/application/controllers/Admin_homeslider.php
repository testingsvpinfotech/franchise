<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_homeslider extends CI_Controller {

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
		$data['allhomeslider']=$this->basic_operation_m->get_all_result('tbl_homeslider',''); 
        $this->load->view('admin/homeslider_master/view_homeslider',$data);
      
	}
	public function add_homeslider()
	{ 
		$data['homeslider']=$this->basic_operation_m->get_all_result('tbl_homeslider','');  		
		$data['message']="";

		if (isset($_POST['submit'])) {
			    $r= array('id'=>'',		            
		             'slider_title'=>$this->input->post('slider_title'),
		             'slider_caption'=>$this->input->post('slider_caption'),
		             'slider_image'=>'',
		                                      
                 );
					$result=$this->basic_operation_m->insert('tbl_homeslider',$r);
                    $lastid=$this->db->insert_id();
            
                    $config['upload_path'] = "assets/homeslider/";
					$config['allowed_types'] = 'gif|jpg|png';					
					$config['file_name'] = 'homeslider_'.$lastid.'.jpg';
					
					$this->load->library('upload',$config);
					$this->upload->initialize($config);
					$this->upload->set_allowed_types('*');

					$data['upload_data'] = '';
					$url_path="";
					if (!$this->upload->do_upload('slider_image'))
					{ 
						$data = array('msg' => $this->upload->display_errors());
					}
					else 
					{ 
						$image_path = $this->upload->data();
					}
					
					$data =array('slider_image'=>$image_path['file_name']);
					$whr=array('id'=>$lastid);
					$this->basic_operation_m->update('tbl_homeslider',$data,$whr);

				if ($this->db->affected_rows()>0) {
					$data['message']="slider Image Added Sucessfully";
				}else{
					$data['message']="Error in Query";
				}
				 redirect('admin/list-homeslider'); 
		}
	    $this->load->view('admin/homeslider_master/add_homeslider',$data);
	}
	
	public function update_homeslider($id)
	{
		 $data['message']="";
		
		if($id!="")
		{
		    $whr =array('id'=>$id);			
			$data['row']= $this->basic_operation_m->get_table_row('tbl_homeslider',$whr);			
		}
		
		$data['message']="";

		if (isset($_POST['submit'])) {
			$last = $this->uri->total_segments();
	        $id= $this->uri->segment($last);
			$whr=array('id'=>$id);
			    
                    $config['upload_path'] = "assets/homeslider/";
					$config['allowed_types'] = 'gif|jpg|png';					
					$config['file_name'] = 'homeslider_'.$id.'.jpg';
					
					$this->load->library('upload',$config);
					$this->upload->initialize($config);
					$this->upload->set_allowed_types('*');

					$data['upload_data'] = '';
					$url_path="";
					if (!$this->upload->do_upload('slider_image'))
					{ 
						$data = array('msg' => $this->upload->display_errors());
					}
					else 
					{ 
						$image_path = $this->upload->data();
					}
				   $data =array(
					'slider_title'=>$this->input->post('slider_title'),
					'slider_caption'=>$this->input->post('slider_caption'),
					'slider_image'=>$image_path['file_name']);

					$result=$this->basic_operation_m->update('tbl_homeslider',$data,$whr);

				if ($this->db->affected_rows()>0) {
					$data['message']="slider Image Updated Sucessfully";
				}else{
					$data['message']="Error in Query";
				}
				 redirect('admin/list-homeslider'); /*-------when u add a city and save it, thn it will be go to all city*/
		}
	    $this->load->view('admin/homeslider_master/edit_homeslider',$data);
	}
	
	public function delete_homeslider(){
	 //	$data['message']=""; 
	 $id = $this->input->post('getid');
		if($id!="")
		{
		   
		    $whr =array('id'=>$id);
			$res=$this->basic_operation_m->delete('tbl_homeslider',$whr);
		    
		    
			$output['status'] = 'success';
			$output['message'] = 'Slider deleted successfully';
		}
		else{
			$output['status'] = 'error';
			$output['message'] = 'Something went wrong in deleting the Slider';
		}
 
		echo json_encode($output);		
          
		}		

}