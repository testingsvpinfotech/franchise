<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Franchise_pod extends CI_Controller 
{

	function __construct()
	{
		 parent:: __construct();
		 $this->load->model('basic_operation_m');
		//  print_r($this->session->userdata('customer_id'));die;
		 if($this->session->userdata('customer_id') == '')
		{
			redirect('franchise');
		}
	}
	public function index()
	{
		
		$customer_name = $_SESSION['customer_name'];
		
		$data= array();

			$data['pod']	= $this->basic_operation_m->get_query_result("select * from tbl_upload_pod where deliveryboy_id = '$customer_name'");

        $this->load->view('franchise/pod/view_pod',$data);
      
	}
	public function addpod()
	{
		$customer_name = $_SESSION['customer_name'];
		$resAct	= $this->db->query("select * from tbl_domestic_booking join tbl_domestic_deliverysheet on tbl_domestic_deliverysheet.pod_no = tbl_domestic_booking.pod_no where tbl_domestic_booking.is_delhivery_complete ='1' AND tbl_domestic_deliverysheet.deliveryboy_name = '$customer_name'");

		  if($resAct->num_rows()>0)
		 {
		 	$data['deliverysheet']=$resAct->result_array();	            
         }
		//print_r($data['deliverysheet']);exit;
		$data['message']="";
	    $this->load->view('franchise/pod/addpod',$data);
	}
	public function insertpod()
	{
		$all_data 		= $this->input->post();
		
		if (!empty($all_data)) 
		{
			
			$username = $_SESSION['customer_name'];
			$all_data['user_id']= $username;

			
			// exit();
			$branch_name = $_SESSION['branch_name'];
			$lastid = $this->input->post('pod_no');
			$drs_date = $this->db->query("SELECT * FROM tbl_domestic_tracking WHERE status = 'Delivered' AND pod_no ='$lastid' ORDER BY id DESC LIMIT 1")->row('tracking_date');
			$predate = date('Y-m-d', strtotime($drs_date));
			$curret = date('Y-m-d');
			// echo '<pre>';print_r($this->input->post('booking_date'));die;
			if($predate <= date('Y-m-d', strtotime($this->input->post('booking_date'))) &&
			$curret >= date('Y-m-d', strtotime($this->input->post('booking_date'))))
		   {
			$date=date('y-m-d');
			    $r= array(
					  'deliveryboy_id'=>$username,
					  'pod_no'=>$this->input->post('pod_no'),
					  'booking_date'=>$this->input->post('booking_date'),
					  'remarks'=>$this->input->post('reamrk'),
	                  'image'=>''
				 );
				// print_r($r);die;
				$result=$this->basic_operation_m->insert('tbl_upload_pod',$r);
				// echo $this->db->last_query();die;
			
				$lastid=$this->db->insert_id();
				
				$this->uploloadPOD($all_data,$_FILES['image']);
				$config['upload_path'] = "assets/franchise_pod/";
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
				// $this->basic_operation_m->update('tbl_upload_pod',$data,$whr);

				if ($this->db->affected_rows()>0) {
					$msg = 'Pod Uploaded Successfully';
						$class = 'alert alert-danger alert-dismissible';
				}else{
					$data['message']="Error in Query";
				}
			}
			else
			{
				$msg = 'Please select date In between Delivered Date to Current date';
						$class = 'alert alert-danger alert-dismissible';
						$this->session->set_flashdata('notify', $msg);
						$this->session->set_flashdata('class', $class);
			}
			   $this->session->set_flashdata('notify', $msg);
			   $this->session->set_flashdata('class', $class);
                redirect('franchise/add-pod');
		}
	}

	public function uploloadPOD($data,$file){
		$curret = date('Y-m-d');
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'http://boxnfreight.in/mobileapp/upload_pod',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => array('pod_no' => $data['pod_no'],'image'=> new CURLFILE($file['tmp_name']),'user_id' => '1','date'=>$curret),
		  CURLOPT_HTTPHEADER => array(
		    'Cookie: ci_session=4lbcm9f3as3uksllpmscbl4vg0m6gm8t'
		  ),
		));

		$response = curl_exec($curl);
		// print_r($response);die;

		curl_close($curl);
		return $response;
	}
	
	public function update_uploaded_pod(){
		$all_data 		= $this->input->post();
		if(!empty($all_data)){
		
		$pod_no = $this->input->post('pod_no');
		$image = $this->input->post('image');
		$get_id  = $this->db->query("select id from tbl_upload_pod where pod_no ='$pod_no'")->row();
		$lastid = $get_id->id ;
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
				$whr=array('pod_no'=>$all_data['pod_no']);
				$this->basic_operation_m->update('tbl_upload_pod',$data,$whr);

		if ($this->db->affected_rows()>0) {
			$data['message']="Image Added Sucessfully";
		}else{
			$data['message']="Error in Query";
		}
			
		redirect('admin/upload-pod');
		}else{
			$this->load->view('admin/pod/update_pod');
		}
	}


	public function view_bulkpod()
	{
		$data['message']="";
	    $this->load->view('franchise/pod/uploadbulkpod',$data);
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

	function pending_pod()
	{
        // $this->db->query("SELECT *  from tbl_domestic_tracking where status = ''")

		$data['pod'] = $this->db->query("SELECT * , tbl_domestic_booking.pod_no as pod  FROM tbl_domestic_booking JOIN tbl_domestic_tracking ON tbl_domestic_booking.pod_no=tbl_domestic_tracking.pod_no LEFT JOIN tbl_domestic_deliverysheet ON tbl_domestic_booking.pod_no=tbl_domestic_deliverysheet.pod_no WHERE  tbl_domestic_tracking.status = 'Delivered' AND tbl_domestic_deliverysheet.pod_no IS NULL ORDER BY tbl_domestic_booking.booking_id DESC;")->result_array();
		// $data['pod'] = $this->db->query("SELECT * , tbl_domestic_booking.pod_no as pod  FROM tbl_domestic_booking LEFT JOIN tbl_domestic_deliverysheet ON tbl_domestic_booking.pod_no=tbl_domestic_deliverysheet.pod_no WHERE tbl_domestic_deliverysheet.pod_no IS NULL ORDER BY id DESC;")->result_array();
		$this->load->view('franchise/pod/view_pendingpod', $data);
	}

	
	
}