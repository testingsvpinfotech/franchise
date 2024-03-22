<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Route_planner extends CI_Controller {

	var $data 			= array();
	function __construct()
	{
		 parent:: __construct();
		 $this->load->model('basic_operation_m');
		if($this->session->userdata('userId') == '')
		{
			redirect('admin');
		}
	}
	
	
	###################### View All Airlines Start ########################
	
	public function routelist()
	{  
		
		$data['route_list']	= $this->db->query("SELECT * ,tbl_route_planner.route_name FROM `route_planner_master` JOIN tbl_route_planner ON tbl_route_planner.route_id = route_planner_master.route_id")->result();
        $this->load->view('admin/route_planner/routelist',$data);
      
	}
	public function add_route()
	{  
	    // $data['message']="";	


		// 	$data['rgn_detail']=$this->basic_operation_m->get_query_result('select * from route_planner_master left join city on city.id=route_planner_master.city ','');
       
		// 	$data['city_array'] =array();
		// 	foreach ($data['rgn_detail'] as $key => $value) {				
		// 		$data['city_array'][$value->id] = $value->id;
		// 	}	
		if (isset($_POST['submit'])) 
		{

			// $route_name = trim($this->input->post('route_name') );
			// $whr_r= array('route_name'=>$route_name);

			// $reg_details=$this->basic_operation_m->get_table_row('route_planner_master',$whr_r);


			// if(isset($reg_details))
			// {
			// 	$msg			= 'Region already exist';
			// 	$class			= 'alert alert-danger alert-dismissible';

			// }else{

			//print_r($_POST);exit;	
					$state_id = $this->input->post('state_id');
					$city_id = $this->input->post('city_id');			
					
					$dd= array(
			             'route_name' => $this->input->post('route_name'),   		                          
		             );

					

					$inserted_id=$this->basic_operation_m->insert('tbl_route_planner',$dd);

					//print_r($inserted_id);exit;
					for ($i=0; $i <count($city_id) ; $i++) { 

						//if($i!=0)
						{
							$fin_city = explode("_", $city_id[$i]);
							//print_r($fin_city);exit;
							
							if($city_id[$i] <> 'multiselect-all')
							{
								 $r_detail= array(
									  'route_id' => $inserted_id,   
									  'state' => $fin_city[0],   
									  'city' => $fin_city[1],                   
								  );
								  $result=$this->basic_operation_m->insert('route_planner_master',$r_detail);
							}
						
						}
					}
					
					if(!empty($result))
					{
						$msg					= 'data added successfully';
						$class					= 'alert alert-success alert-dismissible';	
					}
					else
					{
						$msg			= 'data not added successfully';
						$class			= 'alert alert-danger alert-dismissible';					
					}

			$this->session->set_flashdata('notify',$msg);
			$this->session->set_flashdata('class',$class);
             redirect('admin/add_route'); 
		}
			
		






		 $data['city_list']= $this->basic_operation_m->get_all_result('city');
		 $data['state_list']= $this->basic_operation_m->get_all_result('state');
         $this->load->view('admin/route_planner/add_route',$data);
	}



	
	public function delete_routePlanner_data($id)
	{
		
		$this->db->delete('tbl_route_planner',array('id'=>$id));
		$this->session->set_flashdata('msg',"Data Deleted Successfully!");
		redirect (base_url() . 'admin/route-planner');
	}

	public function find_route_planner($id)
	{
		$data['show_data']  = $this->db->get_where('tbl_route_planner' , array('id' => $id))->row_array();
		//print_r($data);exit;
		$data['city']  = $this->db->query('select city from city')->result_array();
        $this->load->view('admin/route_planner/edit_route_planner',$data);
	}
	public function update_route_planner_data($id)
	{
		$city_id =  implode(',',$this->input->post('city_id'));
            $data = array(
			'route_name'=>$this->input->post('route_name'),
			'city_id'=>$city_id,
			);
            $this->db->where('id',$id);
         $check = $this->db->update('tbl_route_planner',$data);
		  if($check){
			$this->session->set_flashdata('msg',"Data updated Successfully!");
		}else{
			$this->session->set_flashdata('msg',"Somthing is wrong");
		}
		redirect (base_url() . 'admin/route-planner');
		
	}



	public function report_branch($id)
	{  
        // print_r($id);exit;
		$data['show_data']  = $this->db->query('SELECT `booking_date`,`pod_no`,`sender_name`,`branch_id`,`sender_city`,`reciever_city` FROM tbl_domestic_booking WHERE `branch_id`='.$id)->result_array();
    //    print_r($data['city']);die();
		$this->load->view('admin/route_planner/report_branch',$data);
      
	}
	public function todaycoll()
	{  
        
		//$data['city']  = $this->db->query('select city from city')->result_array();
        $this->load->view('admin/route_planner/collection',$data);
      
	}
	public function pettycash()
	{  
        
		//$data['city']  = $this->db->query('select city from city')->result_array();
        $this->load->view('admin/route_planner/pettycash',$data);
      
	}
	public function duereport()
	{  
        
		//$data['city']  = $this->db->query('select city from city')->result_array();
        $this->load->view('admin/route_planner/duereport',$data);
      
	}
	public function branchcollection()
	{  
        
		//$data['city']  = $this->db->query('select city from city')->result_array();
        $this->load->view('admin/route_planner/branchcollection',$data);
      
	}
	
	
	
	###################### View All Airlines End ########################	
	
	
   
}
?>
