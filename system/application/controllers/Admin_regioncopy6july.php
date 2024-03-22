<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_region extends CI_Controller{
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('basic_operation_m');
		$this->load->model('Zone_model');
		if($this->session->userdata('userId') == '')
		{
			redirect('admin');
		}
	}
	
	public function index()
	{
        $data= array();
		$data['allregiondata']=$this->Zone_model->get_all_result_region('region_master','');  
        $this->load->view('admin/region_master/view_region',$data);
	}
	
	public function add_region()
	{
		$data['message']="";	


			$data['rgn_detail']=$this->basic_operation_m->get_query_result('select * from region_master_details left join city on city.id=region_master_details.city ','');

			$data['city_array'] =array();
			foreach ($data['rgn_detail'] as $key => $value) {				
				$data['city_array'][$value->id] = $value->id;
			}	
		if (isset($_POST['submit'])) 
		{

			$region_name = trim($this->input->post('region_name') );
			$whr_r= array('region_name'=>$region_name);

			$reg_details=$this->basic_operation_m->get_table_row('region_master',$whr_r);


			if(isset($reg_details))
			{
				$msg			= 'Region already exist';
				$class			= 'alert alert-danger alert-dismissible';

			}else{
					$state_id = $this->input->post('state_id');
					$city_id = $this->input->post('city_id');			
					
					$r= array(
			             'region_name' => $this->input->post('region_name'),   		                          
		             );

					$inserted_id=$this->basic_operation_m->insert('region_master',$r);
					for ($i=0; $i <count($city_id) ; $i++) { 

						//if($i!=0)
						{
							$fin_city = explode("_", $city_id[$i]);
							
							if($city_id[$i] <> 'multiselect-all')
							{
								 $r_detail= array(
									  'regionid' => $inserted_id,   
									  'state' => $fin_city[0],   
									  'city' => $fin_city[1],                   
								  );
								  $result=$this->basic_operation_m->insert('region_master_details',$r_detail);
							}

						
						}
					}
					
					if(!empty($r))
					{
						$msg					= 'Region added successfully';
						$class					= 'alert alert-success alert-dismissible';	
					}
					else
					{
						$msg			= 'Region not added successfully';
						$class			= 'alert alert-danger alert-dismissible';					
					}
				}
			$this->session->set_flashdata('notify',$msg);
			$this->session->set_flashdata('class',$class);
             redirect('admin/add-region'); 
		}
		 $data['city_list']= $this->basic_operation_m->get_all_result('city');
		 $data['state_list']= $this->basic_operation_m->get_all_result('state');
	    $this->load->view('admin/region_master/add_region',$data);
	}
	
	
	public function update_region($id)
	{
		$data['message']="";      
		if($id!="")
		{
			
			$whr =array('region_id'=>$id);
			$data['rgn']=$this->basic_operation_m->get_table_row('region_master',$whr);	
			$whr1 =array('regionid'=>$id);
			$data['rgn_detail']=$this->basic_operation_m->get_query_result("select * from region_master_details left join city on city.id=region_master_details.city where regionid='$id'",$whr1);


			$data['state_array'] =array();
			$data['city_array'] =array();
			foreach ($data['rgn_detail'] as $key => $value) {

				$data['state_array'][$value->state] = $value->state;
				$data['city_array'][$value->state.'_'.$value->id] = $value->city;

			}	

			

		}
		if (isset($_POST['submit'])) {
	       
	        $whr1 =array('regionid'=>$id);
	        $result=$this->basic_operation_m->delete('region_master_details',$whr1);
	        

			$state_id = $this->input->post('state_id');
			$city_id = $this->input->post('city_id');
			for ($i=0; $i <count($city_id) ; $i++) { 

				$fin_city = explode("_", $city_id[$i]);

				if(in_array($fin_city[0],$state_id))
				{
					$r_detail= array(  'regionid' => $id,   'state' => $fin_city[0],   'city' => $fin_city[1]);
				}

				$result=$this->basic_operation_m->insert('region_master_details',$r_detail);
			}

			 $whr =array('region_id'=>$id);
			 $r= array(
	             'region_name' => $this->input->post('region_name'),   		                          
             );
			$result=$this->basic_operation_m->update('region_master',$r, $whr);
			if(!empty($r))
			{				
				$msg	= 'Region updated successfully';
				$class	= 'alert alert-success alert-dismissible';	
			}
			else
			{
				$msg	= 'Region not updated successfully';
				$class	= 'alert alert-danger alert-dismissible';	
				
			}
			$this->session->set_flashdata('notify',$msg);
			$this->session->set_flashdata('class',$class);
            redirect('admin/list-region');
		}


		$data['city_list']= $this->basic_operation_m->get_all_result('city');
		$data['state_list']= $this->basic_operation_m->get_all_result('state');
	    $this->load->view('admin/region_master/edit_region',$data);
	}
	public function delete_region($id)
	{
		$data['message']="";       
		if($id!="")
		{
		    $whr =array('region_id'=>$id);
			$this->basic_operation_m->delete('region_master',$whr);	

			$whr1=array('regionid'=>$id);
			$this->basic_operation_m->delete('region_master_details',$whr1);			
            redirect('admin/list-region');
		}		
	  
	}
	public function getStatewiseCity()
	{
		 $state_id = $this->input->post('state_id');
		 $already_user_city = $this->input->post('already_user_city');
		
		 $html = '';

		 
		if($state_id!="")
		{
			
				 $check_state_id   =  implode('","', $state_id);
			
			
			if(!empty($already_user_city))
			{
				 $already_user_city   = str_replace(",",'","', $already_user_city);
				 $whr ='state_id IN ("'.$check_state_id.'") and id not IN ("'.$already_user_city.'") ';
			}
			else
			{
				 $whr ='state_id IN ("'.$check_state_id.'")';
			}
		    		    
			 $res=$this->basic_operation_m->get_all_result('city',$whr);
			//echo $this->db->last_query();exit;
			if(!empty($res))
			{
				foreach($res as $key => $values)
				{
					$html  .= '<option value="'.$values['state_id'].'_'.$values['id'].'"   >'.$values['city'].'</option>';
					 //option += '<option value="' +d[i].id + '">' + d[i].city + '</option>';
				}
			}
			
      	}		
      	 echo json_encode($html);
	  
	}
	public function getStatewiseCityedit()
	{
		 $state_id = $this->input->post('state_id');
		 $already_user_city = $this->input->post('already_user_city');
		
		 $html = '';

		 
		if($state_id!="")
		{
			if(strpos($state_id, ',')) 
			{
				
				 $check_state_id   =  str_replace(",",'","', $state_id);
			}
			else
			{
				 $check_state_id   = $state_id;
			}
			
			if(!empty($already_user_city))
			{
				 $already_user_city   = str_replace(",",'","', $already_user_city);
				 $whr ='state_id IN ("'.$check_state_id.'") and id not IN ("'.$already_user_city.'") ';
			}
			else
			{
				 $whr ='state_id IN ("'.$check_state_id.'")';
			}
		    		    
			 $res=$this->basic_operation_m->get_all_result('city',$whr);
			//echo $this->db->last_query();exit;
			if(!empty($res))
			{
				foreach($res as $key => $values)
				{
					$html  .= '<option value="'.$values['state_id'].'_'.$values['id'].'"   >'.$values['city'].'</option>';
					 //option += '<option value="' +d[i].id + '">' + d[i].city + '</option>';
				}
			}
			
      	}		
      	 echo json_encode($html);
	  
	}
	
}

?>