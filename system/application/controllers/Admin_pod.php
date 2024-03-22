<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_pod extends CI_Controller 
{

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
		
		$username=$this->session->userdata("userName");			
		$userId=$this->session->userdata("userId");			
		
		$whr = array('username'=>$username);
		$res=$this->basic_operation_m->getAll('tbl_users',$whr);
		$branch_id= $res->row()->branch_id;
		
		$data= array();
		
		
		
		if($userId == '1')
		{
			$data['pod']	= $this->basic_operation_m->get_query_result("select * from tbl_upload_pod");
		}
		else
		{
			
			$where = '';
			$all_user	= $this->basic_operation_m->get_query_result("select * from tbl_users where branch_id = '$branch_id'");
			foreach($all_user as $key => $values)
			{
				$where .= "'".$values->username."'";
			}
			
			$where 	= str_replace("''","','",$where);
			$data['pod']	= $this->basic_operation_m->get_query_result("select * from tbl_upload_pod where deliveryboy_id IN($where)");
			
		}
        $this->load->view('admin/pod/view_pod',$data);
      
	}
	public function addpod()
	{
		$resAct	= $this->basic_operation_m->getAll('tbl_domestic_booking','');
		  if($resAct->num_rows()>0)
		 {
		 	$data['deliverysheet']=$resAct->result_array();	            
         }
		
		$data['message']="";
	    $this->load->view('admin/pod/addpod',$data);
	}
	public function insertpod()
	{
		$all_data 		= $this->input->post();
		if (!empty($all_data)) 
		{
			$username=$this->session->userdata("userName");
			$whr = array('username'=>$username);
			$res=$this->basic_operation_m->getAll('tbl_users',$whr);
		    $branch_id= $res->row()->branch_id;
			$date=date('y-m-d');
			 
			$whr = array('branch_id'=>$branch_id);
			$res=$this->basic_operation_m->getAll('tbl_branch',$whr);
			$branch_name= $res->row()->branch_name;
			
			$date=date('y-m-d');
			    $r= array('id'=>'',
						  'deliveryboy_id'=>$username,
						  'pod_no'=>$this->input->post('pod_no'),
		                  'image'=>'',
						  'delivery_date'=>$date
						 );
			
			$result=$this->basic_operation_m->insert('tbl_upload_pod',$r);
				$lastid=$this->db->insert_id();
					
				$config['upload_path'] = "assets/pod/";
				$config['allowed_types'] = 'gif|jpg|png';$config['file_name'] = 'pod_'.$lastid.'.jpg';
					
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
				$this->basic_operation_m->update('tbl_upload_pod',$data,$whr);

				if ($this->db->affected_rows()>0) {
					$data['message']="Image Added Sucessfully";
				}else{
					$data['message']="Error in Query";
				}
					
                redirect('admin/upload-pod');
		}
	}
	
	
	public function view_bulkpod()
	{
		$data['message']="";
	    $this->load->view('admin/pod/uploadbulkpod',$data);
	}
	
	
	public function insert_bulkupload()
	{
		$all_data 		= $this->input->post();
		if (!empty($all_data)) 
		{
			$username=$this->session->userdata("userName");
			
			if(isset($_FILES['csv_zip']))
		    {
    		 	$ext = pathinfo($_FILES['csv_zip']['name'], PATHINFO_EXTENSION);
    		 	$date=date('y-m-d');
    		
    			$file				= $_FILES["csv_zip"];
    			$filename 			= $file["name"];
    			$tmp_name 	 		= $file["tmp_name"];
    			$type 		 		= $file["type"];
    			$name 				= explode(".", $filename);
    		
    			$continue 			= strtolower($name[1]) == 'zip' ? true : false; //Checking the file Extension
    			if(!$continue)
    			{
    				$message 		= "The file you are trying to upload is not a .zip file. Please try again.";
    			}       
    			$targetdir 			= "assets/pod/";
    			$targetzip 			= "assets/pod/".$filename;
    			
    			if(move_uploaded_file($tmp_name, $targetzip))
    			{
    				$zip 	= new ZipArchive();
    				$x 		= $zip->open($targetzip);  // open the zip file to extract
    				if($x === true)
    				{
    					
    					for ($i = 0; $i < $zip->numFiles; $i++)
    					{
    						$filename = $zip->getNameIndex($i);
    						$filenamee = explode('.',$filename);
    						
    						
            			    $r= array('id'=>'',
            						  'deliveryboy_id'=>$username,
            						  'pod_no'=>$filenamee[0],
            		                  'image'=>$filename,
            						  'delivery_date'=>$date
            						 );
            			
            		    	$result=$this->basic_operation_m->insert('tbl_upload_pod',$r);
            			
            				
    					//	echo $filename;
    					//	echo '<br>';
    				
    					}
    			 
    					$zip->extractTo($targetdir); // place in the directory with same name  
    					$zip->close();
    					unlink($targetzip); 
    				}
    				$data['message'] = "Your <strong>zip</strong> file was uploaded and unpacked.";
    			}
    			else
    			{    
    				$data['message'] = "There was a problem with the upload. Please try again.";
    			}
		
			
		     } 
		  
               redirect('admin/upload-pod');
		}
	}
	
}