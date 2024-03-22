<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Branch_manager extends CI_Controller {

	function __construct()
	{
		parent:: __construct();
		$this->load->model('basic_operation_m');
		if($this->session->userdata('userId') == '')
		{
			redirect('admin');
		}
	}

	public function view_branch()
	{
        $data= array();		
        $query ="select * from tbl_branch, city, state where tbl_branch.state=state.id and tbl_branch.city=city.id";
        $data['allbranchdata'] =$this->basic_operation_m->get_query_result($query);
        $this->load->view('admin/branch/view_branch',$data);
	}

	public function addbranch()
	{    
		$query ="select max(branch_id) AS id from tbl_branch";  
        $result = $this->basic_operation_m->get_query_row($query);  
			$id= $result->id+1;
			if(strlen($id)==2)
			{
			   $branch_id='BC00'.$id;
			}else if(strlen($id)==3)
			{
			   $branch_id='BC0'.$id;
			}else if(strlen($id)==1)
			{
			   $branch_id='BC000'.$id;
			}else if(strlen($id)==4)
			{
			   $branch_id='BC'.$id;
			}
		
		$data['message']="";
        
        $cities=$this->basic_operation_m->getAll('city','');
       if($cities->num_rows()>0)
       {
       	$data['cities']=$cities->result_array();
       } 
       $states=$this->basic_operation_m->getAll('state','');
       if($states->num_rows()>0)
       {
       	$data['states']=$states->result_array();
       }
		
			$data['uid']=$branch_id;
	    $this->load->view('admin/branch/view_add_branch', $data);
		}
		
		
		public function insertbranch()
		{
			$all_data 		= $this->input->post();
			if(!empty($all_data))
			{
				$date=date('Y-m-d');
				$data=array('branch_id'=>'',
							'branch_name'=>$this->input->post('branch_name'),
							'email'=>$this->input->post('email'),
							'address'=>$this->input->post('address'),
							'city'=>$this->input->post('city_id'),
							'state'=>$this->input->post('state_id'),
							'pincode'=>$this->input->post('pincode'),
							'phoneno'=>$this->input->post('phoneno'),
							'contact_person'=>$this->input->post('contact_person'),
							'branch_code'=>$this->input->post('branch_code'),
							'gst_number'=>$this->input->post('gst_number'),
							'account_name'=>$this->input->post('account_name'),
							'account_number'=>$this->input->post('account_number'),
							'ifsc'=>$this->input->post('ifsc'),
							'acc_branch_name'=>$this->input->post('acc_branch_name'),
							'bank_name'=>$this->input->post('bank_name'),
							'domestic_invoice_series'=>$this->input->post('domestic_invoice_series'),
							'international_invoice_series'=>$this->input->post('international_invoice_series'),
							'import_international_invoice_series'=>$this->input->post('import_international_invoice_series'),
							'pan'=>$this->input->post('pan'),
							'isdeleted' =>'0',

						   );

				$result=$this->basic_operation_m->insert('tbl_branch',$data);
				if ($this->db->affected_rows()>0) 
				{
					$data['message']="branch Added Sucessfully";
				}
				else
				{
					$data['message']="Error in Query";
				}
			}
			redirect('admin/view-branch');
		}
		
	public function editbranch($id)
	{
				
		$data['message']="";

		if(strlen($id)==2)
			{
			   $branch_id='BC00'.$id;
			}else if(strlen($id)==3)
			{
			   $branch_id='BC0'.$id;
			}else if(strlen($id)==1)
			{
			   $branch_id='BC000'.$id;
			}else if(strlen($id)==4)
			{
			   $branch_id='BC'.$id;
			}
		$data['uid'] =$branch_id;
		if($id!="")
		{
		    $whr =array('branch_id'=>$id);
			$res=$this->basic_operation_m->selectRecord('tbl_branch',$whr);
			if($res->num_rows() > 0)
			{
				$data['singlebranch']= $res->row();
			}
		}
		 $cities=$this->basic_operation_m->getAll('city','');
       if($cities->num_rows()>0)
       {
       	$data['cities']=$cities->result_array();
       } 
       $states=$this->basic_operation_m->getAll('state','');
       if($states->num_rows()>0)
       {
       	$data['states']=$states->result_array();
       }
		
	    $this->load->view('admin/branch/view_edit_branch',$data);
	}
	
	public function updatebranch($id)
	{
			$all_data 		= $this->input->post();
			if(!empty($all_data))
			{
				$whr =array('branch_id'=>$id);
				$r= array('branch_name' => $this->input->post('branch_name'),
					'email'=>$this->input->post('email'),
							'address'=>$this->input->post('address'),
							'city'=>$this->input->post('city_id'),
							'state'=>$this->input->post('state_id'),
							'pincode'=>$this->input->post('pincode'),
							'gst_number'=>$this->input->post('gst_number'),
							'phoneno'=>$this->input->post('phoneno'),
							'account_name'=>$this->input->post('account_name'),
							'account_number'=>$this->input->post('account_number'),
							'ifsc'=>$this->input->post('ifsc'),
							'acc_branch_name'=>$this->input->post('acc_branch_name'),
							'bank_name'=>$this->input->post('bank_name'),
							'domestic_invoice_series'=>$this->input->post('domestic_invoice_series'),
							'international_invoice_series'=>$this->input->post('international_invoice_series'),
							'import_international_invoice_series'=>$this->input->post('import_international_invoice_series'),
							'pan'=>$this->input->post('pan'),
							'contact_person'=>$this->input->post('contact_person')  		
										   
					 );
				$result=$this->basic_operation_m->update('tbl_branch',$r, $whr);
				if ($this->db->affected_rows() > 0) 
				{
					$data['message']="Branch Updated Sucessfully";
				}
				else
				{
					$data['message']="Error in Query";
				}
			}
			redirect('admin/view-branch');
		}

	public function deletebranch()
	{
		//$data['message']="";
        $id = $this->input->post('getid');
	    
		if($id!="")
		{
		    $whr =array('branch_id'=>$id);
			$res=$this->basic_operation_m->delete('tbl_branch',$whr);
			
				$output['status'] = 'success';
			    $output['message'] = 'Branch deleted successfully';
		}
		else{
			$output['status'] = 'error';
			$output['message'] = 'Something went wrong in deleting the Branch';
		}
 
		echo json_encode($output);	
			
            // redirect('admin/view-branch');
		}		
	  


	
}





		 	
		
