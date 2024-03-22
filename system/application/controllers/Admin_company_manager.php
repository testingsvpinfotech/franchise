<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_company_manager extends CI_Controller {

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
		$data['allcompany']=$this->basic_operation_m->get_all_result('tbl_company','');  
	    $this->load->view('admin/compnay_setting/viewcompany',$data);
	}
	
	public function add_company()
	{		
		 
		$data['company']= $this->basic_operation_m->get_all_result('tbl_company','');     		
		$data['message']="";

		if (isset($_POST['submit'])) {		
			    $r= array(
			    	 'branch_wise'=>$this->input->post('branch_wise'),		            
		             'company_name'=>$this->input->post('company_name'),
		             'gst_no'=>$this->input->post('gst_no'),
		             'email'=>$this->input->post('email'),
		             'address'=>$this->input->post('address'),
		             'pan'=>$this->input->post('pan'),
		             'international_invoice_series'=>$this->input->post('international_invoice_series'),
		             'import_international_invoice_series'=>$this->input->post('import_international_invoice_series'),
		             'domestic_invoice_series'=>$this->input->post('domestic_invoice_series'),
		             'address'=>$this->input->post('address'),
					 'phone_no'=>$this->input->post('phone_no'),
					 'website'=>$this->input->post('website'),
					 'invoice_term_condition'=>$this->input->post('invoice_term_condition'),
					 'account_name'=>$this->input->post('account_name'),
					 'account_number'=>$this->input->post('account_number'),
					 'ifsc'=>$this->input->post('ifsc'),
					 'branch_name'=>$this->input->post('branch_name'),
					 'bank_name'=>$this->input->post('bank_name'),
					 'term_condition'=>$this->input->post('term_condition'),
					 'taxable_service'=>$this->input->post('taxable_service'),
					 'udhyam_no'=>$this->input->post('udhyam_no')
                 );
			  

					$result=$this->basic_operation_m->insert('tbl_company',$r);
					//echo $this->db->last_query();exit;
                    $lastid=$this->db->insert_id();
					
					
           
                    $config['upload_path'] = "assets/company/";
					$config['allowed_types'] = 'gif|jpg|png';					
					$config['file_name'] = 'company_'.$lastid.'.jpg';					
					
					$this->load->library('upload',$config);
					$this->upload->initialize($config);
					$this->upload->set_allowed_types('*');
					
					$data['upload_data'] = '';
					$url_path="";
					if (!$this->upload->do_upload('logo'))
					{ 
						$data = array('msg' => $this->upload->display_errors());
					}
					else 
					{ 
						$image_path = $this->upload->data();
					}

					if (!$this->upload->do_upload('stamp'))
					{ 
						$data = array('msg' => $this->upload->display_errors());
					}
					else 
					{ 
						$image_stamp = $this->upload->data();
					}
					
					$data =array('logo'=>$image_path['file_name'],'stamp'=>$image_stamp['file_name']);
					$whr=array('id'=>$lastid);
					$this->basic_operation_m->update('tbl_company',$data,$whr);

				if ($this->db->affected_rows()>0) {
					$data['message']="slider Image Added Sucessfully";
				}else{
					$data['message']="Error in Query";
				}

					if(!empty($lastid))
					{						
						$msg			= 'Company added successfully';
						$class			= 'alert alert-success alert-dismissible';		
					}else{
						$msg			= 'Company not added successfully';
						$class			= 'alert alert-danger alert-dismissible';	
					}	
					$this->session->set_flashdata('notify',$msg);
					$this->session->set_flashdata('class',$class);
				
				 redirect('admin/list-company'); 
		}

	    $this->load->view('admin/compnay_setting/addcompany',$data);
	}
	
	public function update_company($id)
	{
		$data['message']="";
       
		if($id!="")
		{
		    $whr =array('id'=>$id);			
			$data['row']=$this->basic_operation_m->get_table_row('tbl_company',$whr);
			
		}
		
		$data['message']="";

		if (isset($_POST['submit'])) {			
			$last = $this->uri->total_segments();
	        $id= $this->uri->segment($last);
			$whr=array('id'=>$id);
			    
                    $config['upload_path'] = "assets/company/";
					$config['allowed_types'] = 'gif|jpg|png';					
					$config['file_name'] = 'company_'.$id.'.jpg';					
					
					$this->load->library('upload',$config);
					$this->upload->initialize($config);
					$this->upload->set_allowed_types('*');
					
					
					$data['upload_data'] = '';
					$url_path="";

					if($_FILES['logo']['name']!="")
					{
								if (!$this->upload->do_upload('logo'))
								{ 
									$data = array('msg' => $this->upload->display_errors());
								}
								else 
								{ 
									$image_path = $this->upload->data();
								}
					}else{
						$image_path['file_name'] = $this->input->post("logo_name");
					}

					if($_FILES['stamp']['name']!="")
					{
								if (!$this->upload->do_upload('stamp'))
								{ 
									$data = array('msg' => $this->upload->display_errors());
								}
								else 
								{ 
									$image_stamp = $this->upload->data();
								}
					}else{
						$image_stamp['file_name'] = $this->input->post("stamp_name");
					}

				$data =array(//'id'='',
					'branch_wise'=>$this->input->post('branch_wise'),		            
		             'company_name'=>$this->input->post('company_name'),
		             'gst_no'=>$this->input->post('gst_no'),
		             'email'=>$this->input->post('email'),
		             'address'=>$this->input->post('address'),
		             'pan'=>$this->input->post('pan'),
		             'international_invoice_series'=>$this->input->post('international_invoice_series'),
		             'import_international_invoice_series'=>$this->input->post('import_international_invoice_series'),
		             'domestic_invoice_series'=>$this->input->post('domestic_invoice_series'),
		             'address'=>$this->input->post('address'),
					 'phone_no'=>$this->input->post('phone_no'),
					 'website'=>$this->input->post('website'),
					 'invoice_term_condition'=>$this->input->post('invoice_term_condition'),
					 'account_name'=>$this->input->post('account_name'),
					 'account_number'=>$this->input->post('account_number'),
					 'ifsc'=>$this->input->post('ifsc'),
					 'branch_name'=>$this->input->post('branch_name'),
					 'bank_name'=>$this->input->post('bank_name'),
					 'term_condition'=>$this->input->post('term_condition'),
					  'company_code'=>$this->input->post('company_code'),
					  'taxable_service'=>$this->input->post('taxable_service'),
					 'udhyam_no'=>$this->input->post('udhyam_no'),
					'logo'=>$image_path['file_name'],
					'stamp'=>$image_stamp['file_name']

				);
					//$whr=array('id'=>$id);
					$result=$this->basic_operation_m->update('tbl_company',$data,$whr);

				if ($this->db->affected_rows()>0) {
					$data['message']="News Image Updated Sucessfully";
				}else{
					$data['message']="Error in Query";
				}

					if(!empty($data))
					{						
						$msg			= 'Company updated successfully';
						$class			= 'alert alert-success alert-dismissible';		
					}else{
						$msg			= 'Company not updated successfully';
						$class			= 'alert alert-danger alert-dismissible';	
					}	
					$this->session->set_flashdata('notify',$msg);
					$this->session->set_flashdata('class',$class);

				 redirect('admin/list-company'); 
		}
	    $this->load->view('admin/compnay_setting/editcompany',$data);
	}
	
	public function delete_company($id)
	{
		$data['message']="";
        $last = $this->uri->total_segments();
	    $id	= $this->uri->segment($last);
		if($id!="")
		{
		    $whr =array('id'=>$id);
			$res=$this->basic_operation_m->delete('tbl_company',$whr);

			$msg			= 'Company Deleted successfully';
			$class			= 'alert alert-success alert-dismissible';		
			$this->session->set_flashdata('notify',$msg);
			$this->session->set_flashdata('class',$class);		
            redirect('admin/list-company');
		}		
	  
	}
}