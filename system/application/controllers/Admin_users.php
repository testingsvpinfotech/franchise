<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_users  extends CI_Controller {

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
           $data = [];     
           $data['all_users']=$this->basic_operation_m->getAllUsers();         
           $this->load->view('admin/user_management/view_users', $data);
        
	}
	public function add_user()
	{      
        
		$result = $this->db->query('select max(user_id) AS id from tbl_users')->row();  
		$id= $result->id+1;
		if(strlen($id)==2)
		{
		   $user_id='LU00'.$id;
		}else if(strlen($id)==3)
		{
		   $user_id='LU0'.$id;
		}else if(strlen($id)==1)
		{
		   $user_id='LU000'.$id;
		}else if(strlen($id)==4)
		{
		   $user_id='LU'.$id;
		}
       	$resAct=$this->basic_operation_m->getAll('tbl_branch','');
       	if($resAct->num_rows()>0)
       	{
       		$data['branch']=$resAct->result_array();
       	} 
    	$data['all_menu'] =$this->basic_operation_m->get_query_result('select * from all_menu order by menu_seq asc');
			
      	if(isset($_POST['submit']))
      	{
            $date=date('Y-m-d');
            
            $whr =array('username'=>$this->input->post('username'));
         	$check_available =$this->basic_operation_m->get_table_row('tbl_users',$whr);
         	if(!empty($check_available))
         	{
             	$msg			= 'User is already exist';
        		$class			= 'alert alert-danger alert-dismissible';	
         	}else{

             	$password = $this->input->post('password');
                $data=array('user_id'=>'',
        	        'username'=>$this->input->post('username'),
        	    	'full_name'=>$this->input->post('full_name'),
        			'email'=>$this->input->post('email'),
        			// 'password'=>$this->input->post('password'),
        			'phoneno'=>$this->input->post('phoneno'),
        			'registed_date'=>$date,
        			'user_type'=>$this->input->post('user_type'),
					'branch_id'=>$this->input->post('branch_code'),
    		   	);

                if (!empty($password)) {
			   		$key = $this->config->item('encryption_key');
		            $salt1 = hash('sha512', $key . $password);
					$salt2 = hash('sha512', $password . $key);
					$hashed_password = hash('sha512', $salt1 . $password . $salt2);
					$data['password'] = $hashed_password;
				}

                $result=$this->basic_operation_m->insert('tbl_users',$data);
				
				foreach($_POST['menu_access'] as $key => $values)
				{
					if($values !== 'multiselect-all')
					{
						$this->basic_operation_m->insert('menu_allotment',array('user_id'=>$result,'am_id'=>$values));
					}
				}
					
    			if(!empty($data))
    			{						
    				$msg			= 'User added successfully';
    				$class			= 'alert alert-success alert-dismissible';		
    			}else{
    				$msg			= 'User not added successfully';
    				$class			= 'alert alert-danger alert-dismissible';	
    			}	
         	}
			$this->session->set_flashdata('notify',$msg);
			$this->session->set_flashdata('class',$class);

            redirect('admin/list-user');

      	}
       	$data['uid']=$user_id;
       	$resAct= $this->basic_operation_m->getAll('tbl_user_types','');
       	$data['usertypes']=$resAct->result();	
       	$this->load->view('admin/user_management/add_user', $data);
                
      
	}
 	function add_partner()
	{      
            $result = $this->db->query('select max(user_id) AS id from tbl_users')->row();  
			$id= $result->id+1;
			if(strlen($id)==2)
			{
			   $user_id='LU00'.$id;
			}else if(strlen($id)==3)
			{
			   $user_id='LU0'.$id;
			}else if(strlen($id)==1)
			{
			   $user_id='LU000'.$id;
			}else if(strlen($id)==4)
			{
			   $user_id='LU'.$id;
			}

          if(isset($_POST['submit']))
          {
            $date=date('Y-m-d');
            $data=array('user_id'=>'',
            	        'username'=>$this->input->post('username'),
            	    	'full_name'=>$this->input->post('full_name'),
            			'email'=>$this->input->post('email'),
            			'password'=>$this->input->post('password'),
            			'phoneno'=>$this->input->post('phoneno'),
            			'registed_date'=>$date,
            			'user_type'=>$this->input->post('user_type'),
						'branch_id'=> 0,
            		   );

            $result=$this->basic_operation_m->insert('tbl_users',$data);
			
			if(!empty($data))
			{						
				$msg			= 'Partner added successfully';
				$class			= 'alert alert-success alert-dismissible';		
			}else{
				$msg			= 'Partner not added successfully';
				$class			= 'alert alert-danger alert-dismissible';	
			}	
			$this->session->set_flashdata('notify',$msg);
			$this->session->set_flashdata('class',$class);

            redirect('admin/list-user');

          }
           $data['uid']=$user_id;
           $resAct= $this->basic_operation_m->getAll('tbl_user_types',"user_type_name ='B2B'");
           $data['userType']=$resAct->row();	
           $this->load->view('admin/user_management/add_partner', $data);
	}
	public function update_user($id)
	{      
        
        $data['message']="";
		$data['branch']=$this->basic_operation_m->get_all_result('tbl_branch','');
		$data['selected_menu'] = array();
		if($id!="")
		{
			$data['singleuser']= $this->basic_operation_m->get_single_User($id);
			
		}
        $selected_menu =$this->basic_operation_m->get_table_result('menu_allotment',array('user_id'=>$id));
		foreach($selected_menu as $key => $values)
		{
			$data['selected_menu'][] = $values->am_id;
		}
       	 	$data['all_menu'] =$this->basic_operation_m->get_query_result('select * from all_menu order by menu_seq asc');
          	if(isset($_POST['submit']))
          	{
          		$password = $this->input->post('password');
            	$data = array(
	    	    	'full_name'	=>$this->input->post('full_name'),
	    			'email'	=>$this->input->post('email'),
	    			// 'password'=>$this->input->post('password'),
	    			'phoneno'=>$this->input->post('phoneno'),
					'branch_id'=>$this->input->post('branch_code'),
					'user_type'=>$this->input->post('user_type')
    		   	);


    		   	if (!empty($password)) {
			   		$key = $this->config->item('encryption_key');
		            $salt1 = hash('sha512', $key . $password);
					$salt2 = hash('sha512', $password . $key);
					$hashed_password = hash('sha512', $salt1 . $password . $salt2);
					$data['password'] = $hashed_password;
				}


            	$whr =array('user_id'=>$id);
            	$result=$this->basic_operation_m->update('tbl_users',$data, $whr );
		
				$res=$this->basic_operation_m->delete('menu_allotment',array('user_id'=>$id));
				foreach($_POST['menu_access'] as $key => $values)
				{
					if($values !== 'multiselect-all')
					{
						$this->basic_operation_m->insert('menu_allotment',array('user_id'=>$id,'am_id'=>$values));
					}
				}
				
			
				if(!empty($data))
				{						
					$msg			= 'User updated successfully';
					$class			= 'alert alert-success alert-dismissible';		
				}else{
					$msg			= 'User not updated successfully';
					$class			= 'alert alert-danger alert-dismissible';	
				}	
				$this->session->set_flashdata('notify',$msg);
				$this->session->set_flashdata('class',$class);

            	redirect('admin/list-user');

          	}
          
           $data['usertypes']= $this->basic_operation_m->get_all_result('tbl_user_types','');           
           $this->load->view('admin/user_management/edit_users', $data);
                
      
	}

	public function delete_user(){
	    
	    $id = $this->input->post('getid');
// 		$data['message']="";
//         $last = $this->uri->total_segments();
// 	    $id	= $this->uri->segment($last);
		if($id!=""){
		    
		    $whr =array('user_id'=>$id);
			$res=$this->basic_operation_m->delete('tbl_users',$whr);
			
			$output['status'] = 'success';
			$output['message'] = 'User deleted successfully';
		}
		else{
			$output['status'] = 'error';
			$output['message'] = 'Something went wrong in deleting the User';
		}
 
		echo json_encode($output);	
            
		}		
	  

	
// 	public function delete_user($id)
// 	{
// 		$data['message']="";
//         $last = $this->uri->total_segments();
// 	    $id	= $this->uri->segment($last);
// 		if($id!="")
// 		{
// 		    $whr =array('user_id'=>$id);
// 			$res=$this->basic_operation_m->delete('tbl_users',$whr);

// 			$msg			= 'User Deleted successfully';
// 			$class			= 'alert alert-success alert-dismissible';		
// 			$this->session->set_flashdata('notify',$msg);
// 			$this->session->set_flashdata('class',$class);	
			
//             redirect('admin/list-user');
// 		}		
	  
// 	}

	public function check_user_exist(){
		$username = trim($this->input->get('username'));

		$data =$this->basic_operation_m->get_query_result('select * from tbl_users WHERE username="'.$username.'" ');

		// echo $this->db->last_query();
		// print_r($data);

		if (!empty($data)) {
			echo "1";
		}else{
			echo "0";
		}
	}

}
?>
