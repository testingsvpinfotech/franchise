<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_news extends CI_Controller {

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
		$data['allnews']=$this->basic_operation_m->get_all_result('tbl_news','');
        $this->load->view('admin/news_master/view_news',$data);
      
	}
	
	public function add_news()
	{		 
		$data['news']=$this->basic_operation_m->get_all_result('tbl_news','');       
		$data['message']="";

		if (isset($_POST['submit'])) {			
			    $r= array('id'=>'',		            
		             'news_title'=>$this->input->post('news_title'),
		             'news_date_from'=>date("Y-m-d",strtotime($this->input->post('news_date_from'))),
		             'news_date_to'=>date("Y-m-d",strtotime($this->input->post('news_date_to'))),
					 'news_details'=>$this->input->post('news_details'),
		             'news_image'=>'',		                                
                 );

					$result=$this->basic_operation_m->insert('tbl_news',$r);
                    $lastid=$this->db->insert_id();
            
                    $config['upload_path'] = "assets/news/";
					$config['allowed_types'] = 'gif|jpg|png';					
					$config['file_name'] = 'news_'.$lastid.'.jpg';
					
					
					$this->load->library('upload',$config);
					$this->upload->initialize($config);
					$this->upload->set_allowed_types('*');

					
					$data['upload_data'] = '';
					$url_path="";
					if (!$this->upload->do_upload('news_image'))
					{ 
						$data = array('msg' => $this->upload->display_errors());
					}
					else 
					{ 
						$image_path = $this->upload->data();
					}
					
					$data =array('news_image'=>$image_path['file_name']);
					$whr=array('id'=>$lastid);
					$this->basic_operation_m->update('tbl_news',$data,$whr);

				if ($this->db->affected_rows()>0) {
					$data['message']="slider Image Added Sucessfully";
				}else{
					$data['message']="Error in Query";
				}
				 redirect('admin/list-news'); 
		}
	    $this->load->view('admin/news_master/add_news',$data);
	}
	
	public function update_news($id)
	{
		$data['message']="";		
		if($id!="")
		{
		    $whr =array('id'=>$id);
			$res=$this->basic_operation_m->get_table_row('tbl_news',$whr);			
			$data['row']= $this->basic_operation_m->get_table_row('tbl_news',$whr);			
		}		
		$data['message']="";

				if (isset($_POST['submit'])) {
					$date=date('y-m-d');
					$last = $this->uri->total_segments();
			        $id= $this->uri->segment($last);
					$whr=array('id'=>$id);
			    
                    $config['upload_path'] = "assets/news/";
					$config['allowed_types'] = 'gif|jpg|png';					
					$config['file_name'] = 'news_'.$id.'.jpg';
					
					
					$this->load->library('upload',$config);
					$this->upload->initialize($config);
					$this->upload->set_allowed_types('*');

					
					$data['upload_data'] = '';
					$url_path="";
					if (!$this->upload->do_upload('news_image'))
					{ 
						$data = array('msg' => $this->upload->display_errors());
					}
					else 
					{ 
						$image_path = $this->upload->data();
					}
				
					$data =array(
					'news_title'=>$this->input->post('news_title'),
					'news_date_from'=>date("Y-m-d",strtotime($this->input->post('news_date_from'))),
		            'news_date_to'=>date("Y-m-d",strtotime($this->input->post('news_date_to'))),
					'news_details'=>$this->input->post('news_details'),
					'news_image'=>$image_path['file_name']);					
					$result=$this->basic_operation_m->update('tbl_news',$data,$whr);

				if ($this->db->affected_rows()>0) {
					$data['message']="News Image Updated Sucessfully";
				}else{
					$data['message']="Error in Query";
				}
				 redirect('admin/list-news'); 
				}
	    $this->load->view('admin/news_master/edit_news',$data);
	}
	
	public function delete_news(){
	    
	    $id = $this->input->post('getid');
     
		if($id!=""){
		    
		    $whr =array('id'=>$id);
			$res = $this->basic_operation_m->delete('tbl_news',$whr);
			
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