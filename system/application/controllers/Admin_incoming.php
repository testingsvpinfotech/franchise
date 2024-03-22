<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_incoming extends CI_Controller{
	
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
		$username=$this->session->userdata("userName");
		     $whr = array('username'=>$username);
			 $res=$this->basic_operation_m->getAll('tbl_users',$whr);
			 $branch_id= $res->row()->branch_id;
			 
			 $whr = array('branch_id'=>$branch_id);
			 $res=$this->basic_operation_m->getAll('tbl_branch',$whr);
			 $branch_name= $res->row()->branch_name;
		
		$resAct=$this->db->query("select  *,SUM(CASE WHEN reciving_status=1 THEN 1 ELSE 0 END)  AS total_coming, COUNT(id) AS total, COUNT(total_pcs) AS total_pcs, COUNT(total_weight) AS total_weight from tbl_domestic_menifiest where tbl_domestic_menifiest.destination_branch='$branch_name' group by manifiest_id order by date_added DESC");
		//$resAct=$this->basic_operation_m->getAll('tbl_inword','');
		 if($resAct->num_rows()>0)
		 {
		 	$data['allinword']=$resAct->result_array();	            
         }
         $this->load->view('admin/incoming/view_incoming',$data);
     
		
	}
	
	public function addincoming($id='')
	{
		$data['message']="";//for branch code
		$data['menifiest_data']="";//for branch code
		$data['manifiest_id']="";//for branch code
		
		$username=$this->session->userdata("userName");
		     $whr = array('username'=>$username);
			 $res=$this->basic_operation_m->getAll('tbl_users',$whr);
			 $branch_id= $res->row()->branch_id;
			 
			 $whr = array('branch_id'=>$branch_id);
			 $res=$this->basic_operation_m->getAll('tbl_branch',$whr);
			 $branch_name= $res->row()->branch_name;
	
		
		//for pod_no
		/* $resAct	= $this->basic_operation_m->getAll('tbl_booking','');

		if($resAct->num_rows()>0)
		 {
		 	$data['pod']=$resAct->result();	            
         } */
		
	
		 $resAct=$this->db->query("select distinct manifiest_id,date_added from tbl_domestic_menifiest where destination_branch='$branch_name'");
        if($resAct->num_rows()>0)
        {
			$data['menifiest']=$resAct->result();
        }
		
	
       	$data['branch_name']=$branch_name;
      // echo $this->db->last_query();
	  
	  if(!empty($id))
	  {
		  $form_data 		= $this->input->post();
			
			
			 $username=$this->session->userdata("userName");
		     $whr = array('username'=>$username);
			 $res=$this->basic_operation_m->getAll('tbl_users',$whr);
			 $branch_id= $res->row()->branch_id;
			 
			 $whr = array('branch_id'=>$branch_id);
			 $res=$this->basic_operation_m->getAll('tbl_branch',$whr);
			 $branch_name= $res->row()->branch_name;
			 $date=date('y-m-d');
			 
		
			
			$mid=$id;
			 $data['manifiest_id']=$mid;
			 $res=$this->db->query("select * from tbl_domestic_menifiest where destination_branch='$branch_name' and manifiest_id='$mid' ");
			$data['menifiest_data']=$res->result();
	  }
		
		if (isset($_POST['submit']) ) 
		{
			$form_data 		= $this->input->post();
			
			
			 $username=$this->session->userdata("userName");
		     $whr = array('username'=>$username);
			 $res=$this->basic_operation_m->getAll('tbl_users',$whr);
			 $branch_id= $res->row()->branch_id;
			 
			 $whr = array('branch_id'=>$branch_id);
			 $res=$this->basic_operation_m->getAll('tbl_branch',$whr);
			 $branch_name= $res->row()->branch_name;
			 $date=date('y-m-d');
			 
		
			
			$mid=$this->input->post('manifiest_id');
			 $data['manifiest_id']=$mid;
			 $res=$this->db->query("select * from tbl_domestic_menifiest where destination_branch='$branch_name' and manifiest_id='$mid' ");
			$data['menifiest_data']=$res->result();
			
		}
	
		if(isset($_POST['receving'])) 
		{
			$all_data 		= $this->input->post();
			$date			= $this->input->post('datetime');
		
			$username	=	$this->session->userdata("userName");
			$whr 		= 	array('username'=>$username);
			$res		=	$this->basic_operation_m->getAll('tbl_users',$whr);
			$branch_id	= 	$res->row()->branch_id;
			


			$whr		= 	array('branch_id'=>$branch_id);
			$res		=	$this->basic_operation_m->getAll('tbl_branch',$whr);
			$branch_name= 	$res->row()->branch_name;
	
			if(!empty($all_data))
			{
				$manifiest_id			= $all_data['manifiest_id'];
				foreach($all_data as $key => $values)
				{
					if($key !== 'manifiest_id' && $key !== 'receving'	 && $key !== 'datetime'	)
					{
						
						if(!empty($values))
						{
						
							$key_data	= 	explode('_',$key);
							$pod_no		= 	$key_data[1];
							//$date		=	date('y-m-d');

							$mid			=	$manifiest_id;
							$booking_id		=	$this->basic_operation_m->get_table_row('tbl_domestic_booking',"pod_no = '$pod_no'");
				
							$r				= array('id'=>'',
										'branch_code' =>$branch_name,
										'pod_no'=>$pod_no,
										'status' => 'recieved',
										'manifiest_id'=>$mid,
										'date_added'=>$date,
							);
							
							$data1=array('id'=>'',
										 'booking_id'=>$booking_id->booking_id,
										 'pod_no'=>$pod_no,
										 'status'=>'recieved',
										 'branch_name'=>$branch_name,
										 'tracking_date'=>$date,
											  );
							$result		=	$this->basic_operation_m->insert('tbl_inword',$r);
							
							$result1	=	$this->basic_operation_m->insert('tbl_domestic_tracking',$data1);
							$resAct		=	$this->db->query("update tbl_domestic_menifiest set reciving_status = '1' where pod_no='$pod_no'");
							$resAct		=	$this->db->query("update tbl_domestic_booking set menifiest_recived = '0' where pod_no='$pod_no'");
							
						}
					}	
				}
			}
			redirect('admin/view-incoming');
			
		}
		
		
		$this->load->view('admin/incoming/addincoming', $data);
	}
	
	public function sendemail($to,$message)
	{
	    $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
	    $this->load->library('email');
	    $this->email->initialize($config);
        
        $this->email->from('info@shreelogistics.net', 'shreelogistics Admin');
        $this->email->to($to); 
        
        
        $this->email->subject('Shipment Update');
        $this->email->message($message);	
        
        $this->email->send();


	}
	
	
}




?>