<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_ratemaster extends CI_Controller {

	function __construct()
	{
		 parent:: __construct();
		 $this->load->model('basic_operation_m');
		 $this->load->model('customer_model');
		 $this->load->model('Rate_model');
		 if($this->session->userdata('userId') == '')
		{
			redirect('admin');
		}
	}
	
	public function index()
	{
		    $data= array();
		    $data['message']="";           
			$data['customer']=$this->basic_operation_m->get_all_result('tbl_customers','');		  
		
    	    if (isset($_POST['submit'])) {
    			$customer_id=$this->input->post('customer_id');
                $data['cust']=$this->Rate_model->select_rate_details($customer_id);	

                //echo "<pre>";print_r($data['cust']);exit;
    		}	
		 
		     $this->load->view('admin/rate_master/view_rate',$data);		
	}
	public function assign_regionwise_rate($id)
	{
			$data['message']="";
	
			$data= array();

		$data['transfer_mode']=$this->basic_operation_m->get_all_result('transfer_mode','');
		$data['region']=$this->basic_operation_m->get_all_result('region_master','');
        $data['state']=$this->basic_operation_m->get_all_result('tbl_state','');
		
		if($id!="")
		{
			$whr =array('customer_id'=>$id);
			
			$data['customer']=$this->basic_operation_m->get_table_row('tbl_customers',$whr);

			$whr1 =array('tbl_rate_master.customer_id'=>$id);
			$data['customer_rate_master']=$this->customer_model->get_customer_rate_details($whr1);
			$data['rate']=$this->customer_model->get_customer_rate_details($whr1);
			
			if(!isset($_POST['submit'])) {
			

			$rateMasterPack =$this->db->query('select *,tbl_rate_pack.rate as pack_rate from region_master,tbl_rate_master,tbl_rate_pack where region_master.region_id=tbl_rate_master.region_id and tbl_rate_master.rate_master_id = tbl_rate_pack.rate_master_id and tbl_rate_master.customer_id='.$id);
			if($rateMasterPack->num_rows() > 0) {
			    $data['rate_pack'] = $rateMasterPack->result_array();
			}
			} else {
			    $data['rate_pack'] = [];
			}
		}
					if(isset($_POST['submit']))
					{
					    $data=array('rate_master_id'=>'',
							'customer_id'=>$this->input->post('customer_id'),
							'weight_range'=>$this->input->post('weight_range'),
							'mode_of_transport'=>$this->input->post('mode_of_transport'),
							'local'=>$this->input->post('state_id'),
							'region_id'=>$this->input->post('region_id'),
							'rate'=>$this->input->post('rate'),
							'dod_doac' =>$this->input->post('dod_doac'),
							'price_type'=>$this->input->post('rate_type_sel'),
							'loading_unloading' => $this->input->post('loading_unloading'),
							'packing' => $this->input->post('packing'),
							'handling' => $this->input->post('handling'),
							'fov' => $this->input->post('fov'),
							'min_fov' => $this->input->post('minimum_fov'),
							'cft' => $this->input->post('cft'),
							'fuel_charges' => $this->input->post('fuel_charges'),
							//'min_freight' => $this->input->post('min_freight'),
							'min_amount' => $this->input->post('min_amt'),
							'roundoff_type' => $this->input->post('roundoff_type'),
							'fc_type' => $this->input->post('fc_type'),
							'gst_rate' => $this->input->post('gst_rate'),
							'hc_type' => $this->input->post('hc_type'),
							'to_pay_charges' => $this->input->post('to_pay_charges'),
							'cod' => $this->input->post('cod'),
							'edd' => $this->input->post('edd'),
							);

						$result=$this->basic_operation_m->insert('tbl_rate_master',$data);
						if ($this->db->affected_rows()>0) {
						    $rate_master_id = $this->db->insert_id();
						      $this->db->where('rate_master_id', $rate_master_id);
			                 $this->db->delete('tbl_rate_pack');
			                for($i=0;$i<count($this->input->post('nopackmorefrom'));$i++){
			                    if($this->input->post('nopackmorefrom')[$i] > 0 && $this->input->post('nopackmoreto')[$i] > 0 ) {
						        $data = [
						            'rate_master_id' => $rate_master_id,
						            'from' => $this->input->post('nopackmorefrom')[$i],
						            'to' => $this->input->post('nopackmoreto')[$i],
						            'rate' =>$this->input->post('nopackrate')[$i]
						        ];
						        $this->db->insert('tbl_rate_pack', $data);
			                    }
						    }
							$data['message']="customer Added Sucessfully";
						}else{
							$data['message']="Error in Query";
						}
						redirect('admin/list-rate');
					}	
					
		$this->load->view('admin/rate_master/add_regionwise_rate', $data);
	}

	public function regionwise_rate_edit($id)
	{
					$data['message']="";									
					$data['region']=$this->basic_operation_m->get_all_result('region_master','');
					
					if($id!="")
					{
					     $whr =array('rate_master_id'=>$id);
						 $data['rate']=$this->basic_operation_m->get_table_row('tbl_rate_master',$whr);
						 $c_id=$data['rate']->customer_id;

						$whr_pack =array('rate_master_id'=>$id);
						$data['rate_pack']=$this->basic_operation_m->get_all_result('tbl_rate_pack',$whr_pack);
						
						$whr =array('customer_id'=>$c_id);
						$data['customer']=$this->basic_operation_m->get_table_row('tbl_customers',$whr);

						$data['transfer_mode']=$this->basic_operation_m->get_all_result('transfer_mode','');
					}
					
		
		if (isset($_POST['submit'])){
		
			$all_data 		= $this->input->post();
			$rate_type_sel	= $all_data['rate_type_sel'];
			
			$last = $this->uri->total_segments();
	        $id = $this->uri->segment($last);
	        $whr = array('rate_master_id' => $id);
			
			if($rate_type_sel == 1 ) // doc rate
			{
				if(isset($_POST['addition'][0]) || empty($_POST['addition'][0]))
				{
					$this->db->delete('rate_master', array('rate_id' => $id));
					for($i=0;$i<count($_POST['addition']);$i++)
					{
					
						$data = array(  
								'rate_id'     => $id,
								'addition'     => $_POST['addition'][$i],  
								'lower'  		=> $_POST['lower'][$i],  
								'upper'  		 => $_POST['upper'][$i],  
								'rate_amt'		 => $_POST['rate_amt'][$i]  
								);  
						
						$this->db->insert('rate_master',$data);					
					}
					
				}
			
				$data=array('region_id'=>$this->input->post('region_id'),
							'weight_range'=>$this->input->post('weight_range'),
							'mode_of_transport'=>$this->input->post('mode_name'),
							'local'=>$this->input->post('state_id'),
							'rate'=>$this->input->post('rate'),
							'dod_doac' =>$this->input->post('dod_doac'),
							'price_type' => $this->input->post('rate_type_sel'),
							'loading_unloading' => $this->input->post('loading_unloading'),
							'packing' => $this->input->post('packing'),
							'handling' => $this->input->post('handling'),
							'fov' => $this->input->post('fov'),
							'cft' => $this->input->post('cft'),
							'fuel_charges' => $this->input->post('fuel_charges'),
					);
					//print_r($data);

				//	exit;
				$result=$this->basic_operation_m->update('tbl_rate_master',$data,$whr);
				//exit;
				
				if($this->db->affected_rows() > 0) 
				{
					$rate_master_id = $id;
					$this->db->where('rate_master_id', $rate_master_id);
					$this->db->delete('tbl_rate_pack');
					for($i=0;$i<count($this->input->post('nopackmorefrom'));$i++)
					{
						$data = [
							'rate_master_id' => $rate_master_id,
							'from' => $this->input->post('nopackmorefrom')[$i],
							'to' => $this->input->post('nopackmoreto')[$i],
							'rate' =>$this->input->post('nopackrate')[$i]
						];
						
						$this->db->insert('tbl_rate_pack', $data);
					}
					$data['message']="Rate Master Updated Sucessfully";
				}
				else
				{
					$data['message']="Error in Query";
				}
			}
			elseif($rate_type_sel == 0) // non doc rate
			{
				if(isset($_POST['addition'][0]) || empty($_POST['addition'][0]))
				{
					$this->db->delete('rate_master', array('rate_id' => $id));
					for($i=0;$i<count($_POST['addition']);$i++)
					{
					
						$data = array(  
								'rate_id'     => $id,
								'addition'     => $_POST['addition'][$i],  
								'lower'  		=> $_POST['lower'][$i],  
								'upper'  		 => $_POST['upper'][$i],  
								'rate_amt'		 => $_POST['rate_amt'][$i]  
								);  
						//insert data into database table.  
						$this->db->insert('rate_master',$data);
					
					}					
				}			
				$data=array('region_id'=>$this->input->post('region_id'),
							'weight_range'=>$this->input->post('weight_range'),
							'mode_of_transport'=>$this->input->post('mode_name'),
							'local'=>$this->input->post('state_id'),
							'rate'=>$this->input->post('rate'),
							'dod_doac' =>$this->input->post('dod_doac'),
							'price_type' => $this->input->post('rate_type_sel'),
							'loading_unloading' => $this->input->post('loading_unloading'),
							'packing' => $this->input->post('packing'),
							'handling' => $this->input->post('handling'),
							'fov' => $this->input->post('fov'),
							'cft' => $this->input->post('cft'),
							'fuel_charges' => $this->input->post('fuel_charges'),
					);
					
				$result=$this->basic_operation_m->update('tbl_rate_master',$data,$whr);
				
				if($this->db->affected_rows() > 0) 
				{
					$rate_master_id = $id;
					$this->db->where('rate_master_id', $rate_master_id);
					$this->db->delete('tbl_rate_pack');

					for($i=0;$i<count($this->input->post('nopackmorefrom'));$i++)
					{
						$data = [
							'rate_master_id' => $rate_master_id,
							'from' => $this->input->post('nopackmorefrom')[$i],
							'to' => $this->input->post('nopackmoreto')[$i],
							'rate' =>$this->input->post('nopackrate')[$i]
						];
						
						$this->db->insert('tbl_rate_pack', $data);
					}
					$data['message']="Rate Master Updated Sucessfully";
				}
				else
				{
					$data['message']="Error in Query";
				}
			}	
			
            redirect('admin/list-rate');
		}
		 $this->load->view('admin/rate_master/edit_regionwise_rate',$data);
		
	   
	}
	public function state_city_wise_rate_edit($id)
	{
			$data['message']="";
					
					if($id!="")
					{
						 $data['region']=$this->basic_operation_m->get_all_result('region_master','');

					     $whr =array('rate_master_id'=>$id);

						$data['rate']=$this->basic_operation_m->get_table_row('tbl_rate_master',$whr);
					    
						$c_id=$data['rate']->customer_id;

						$whr_pack =array('rate_master_id'=>$id);
						$data['rate_pack']=$this->basic_operation_m->get_all_result('tbl_rate_pack',$whr_pack);
						
						$whr =array('customer_id'=>$c_id);
						$data['customer']=$this->basic_operation_m->get_table_row('tbl_customers',$whr);

						$data['transfer_mode']=$this->basic_operation_m->get_all_result('transfer_mode','');
					}
					 $this->load->view('admin/rate_master/add_state_city_wise_rate',$data);
	}
	
	public function edit_state_city_wise_rate_edit($id,$tbl_rate_state_id)
	{
		$data['message']="";
				
		if($id!="")
		{
			 $data['region']=$this->basic_operation_m->get_all_result('region_master','');

			 $whr =array('rate_master_id'=>$id);
			
			$data['rate']=$this->basic_operation_m->get_table_row('tbl_rate_master',$whr);
			$customer_id=$data['rate']->customer_id;
			$doc_type=$data['rate']->price_type;

			 $data['state_rate']=$this->Rate_model->select_state_rate_row($customer_id,$id,$tbl_rate_state_id);
			 $data['city_rate']=$this->Rate_model->select_city_rate_row($customer_id,$id,$tbl_rate_state_id);

			 // echo "<pre>";
			 // print_r($data['city_rate']);exit;



			$whr1=array('rate_id'=>$id);
			 $data['doc_state_rate']=$this->basic_operation_m->get_all_result('doc_state_rate_master',$whr1);
			 $data['doc_city_rate']=$this->basic_operation_m->get_all_result('doc_city_rate_master',$whr1);

			$whr_pack =array('rate_master_id'=>$id);
			$data['rate_pack']=$this->basic_operation_m->get_all_result('tbl_rate_pack',$whr_pack);
			
			$whr =array('customer_id'=>$customer_id);
			$data['customer']=$this->basic_operation_m->get_table_row('tbl_customers',$whr);

			$data['transfer_mode']=$this->basic_operation_m->get_all_result('transfer_mode','');
		}
		 $this->load->view('admin/rate_master/edit_state_city_wise_rate',$data);
	}
	
	public function add_StateCityRateMaster($id)
    {
		$all_data 		= $this->input->post();
		$doc_type = $this->input->post('doc_type');
//State		
        $rateMasterId = $this->input->post('rate_master_id');
        $state = $this->input->post('mode_of_transport');
        $rate = $this->input->post('zoneRateState');
        $to_pay_charges = $this->input->post('state_to_pay_charges');
        $cod = $this->input->post('state_cod');
        $edd = $this->input->post('state_edd');
        $customerId = $this->input->post('customer_id');
// City 
        $rateMasterId = $this->input->post('rate_master_id');
        $city 			= $this->input->post('city');
		$rate			= (!empty($this->input->post('rate')))?$this->input->post('rate'):0;
		$to_pay_charges = (!empty($this->input->post('to_pay_charges')))?$this->input->post('to_pay_charges'):0;
        $cod 			= (!empty($this->input->post('cod')))?$this->input->post('cod'):0;
        $edd 			= (!empty($this->input->post('edd')))?$this->input->post('edd'):0;
        $customerId 	= $this->input->post('customer_id');

 		// Delete or insert state rate details in tbl_rate_state table
        $this->basic_operation_m->delete('tbl_rate_state',['rate_master_id' => $rateMasterId, 'state_id' =>$state]);

        $this->db->insert('tbl_rate_state', ['rate_master_id' =>$rateMasterId, 'rate' => $rate, 'state_id' => $state, 'customer_id' => $customerId, 'to_pay_charges' => $to_pay_charges, 'cod' => $cod, 'edd' => $edd]);
	
			
			if(isset($_POST['addition_state'][0]) || empty($_POST['addition_state'][0]))
			{
				$this->db->delete('doc_state_rate_master', array('rate_id' => $rateMasterId));
							
				for($i=0;$i<count($_POST['addition_state']);$i++)
				{						
						$data = array(  
							'rate_id'     => $rateMasterId,
							'state_id'     => $state,
							'customer_id'     => $customerId,
							'addition'    => $_POST['addition_state'][$i],  
							'lower'  => $_POST['lower_state'][$i],  
							'upper'   => $_POST['upper_state'][$i],  
							'rate_amt' => $_POST['rate_amt_state'][$i]  
							);  
					
					$this->db->insert('doc_state_rate_master',$data);					
				}
				
			}  

			// ====================       

			// Delete or insert city rate details in tbl_rate_state table
        	$this->basic_operation_m->delete('tbl_rate_city',['rate_master_id' => $rateMasterId, 'city_id' => $city]);
        	$this->db->insert('tbl_rate_city', ['rate_master_id' =>$rateMasterId, 'rate' => $rate, 'city_id' => $city, 'customer_id' => $customerId, 'to_pay_charges' => $to_pay_charges, 'cod' => $cod, 'edd' => $edd]);
		
			if(isset($_POST['addition_city'][0]) || empty($_POST['addition_city'][0]))
			{
			    
				$this->db->delete('doc_city_rate_master', array('rate_id' => $rateMasterId));
				for($i=0;$i<count($_POST['addition_city']);$i++)
				{				
				$data = array(  
							'customer_id'    => $_POST['customer_id'],
							'city_id'    => $city,
							'rate_id'    => $rateMasterId,
							'addition'   => $_POST['addition_city'][$i],  
							'lower'  => $_POST['lower_city'][$i],  
							'upper'   => $_POST['upper_city'][$i],  
							'rate_amt' => $_POST['rate_amt_city'][$i]  
							);  
					//insert data into database table.  
					$this->db->insert('doc_city_rate_master',$data);					
				}
			}        

			redirect('admin/list-rate');     
	}
	
	public function delete_rate($id)
	{
		$data['message']="";
       
		if($id!="")
		{
		    $whr =array('rate_master_id'=>$id);
			$res=$this->basic_operation_m->delete('tbl_rate_master',$whr);
			
            redirect('admin/list-rate');
		}		
	  
	}
	
	// public function getStateList()
 //    {
 //        $states = [];
 //        $zoneId = $this->input->post('zoneId');
 //        // $resAct = $this->basic_operation_m->getAll('tbl_state',['region_id' => $zoneId]);
 //        $resAct = $this->basic_operation_m->getAll('state',['region_id' => $zoneId]);
            		
	//     if ($resAct->num_rows()>0)
 //        {
 //        	$states = $resAct->result_array();
 //        }
        
 //        echo json_encode($states);
 //        exit;
 //    }
    
    
	
	// public function getStateRates(){		
	// 	$zoneId = $_POST['zoneId'];
	// 	$rateMasterId = $_POST['rateMasterId'];
	// 	$stateId = $_POST['stateId'];

	// 	$getRates = $this->db->where(array('rate_master_id'=>$rateMasterId, 'state_id'=>$stateId))->get('tbl_rate_state')->row();
	// 	echo json_encode($getRates);
	// }
    
    public function getCityList()
    {
        $cities = [];
        $stateId = $this->input->post('state');
        // $resAct = $this->basic_operation_m->getAll('tbl_city',['state_id' => $stateId]);
        $resAct = $this->basic_operation_m->getAll('city',['state_id' => $stateId]);    		
	    if ($resAct->num_rows()>0)
        {
        	$cities = $resAct->result_array();
        }
        
        echo json_encode($cities);
        exit;
    }
	
	// public function saveCityRateMaster()
	// {
	// 	$all_data 		= $this->input->post();		
	//     $rateMasterId = $this->input->post('rate_master_id');
 	//     $city 			= $this->input->post('city');
	// 	   $rate			= (!empty($this->input->post('rate')))?$this->input->post('rate'):0;
	// 	   $to_pay_charges = (!empty($this->input->post('to_pay_charges')))?$this->input->post('to_pay_charges'):0;
 	//	   $cod 			= (!empty($this->input->post('cod')))?$this->input->post('cod'):0;
 	//     $edd 			= (!empty($this->input->post('edd')))?$this->input->post('edd'):0;
 	//     $customerId 	= $this->input->post('customer_id');
        

 //        $this->basic_operation_m->delete('tbl_rate_city',['rate_master_id' => $rateMasterId, 'city_id' => $city]);
 //        $this->db->insert('tbl_rate_city', ['rate_master_id' =>$rateMasterId, 'rate' => $rate, 'city_id' => $city, 'customer_id' => $customerId, 'to_pay_charges' => $to_pay_charges, 'cod' => $cod, 'edd' => $edd]);
		
	// 		if(isset($_POST['addition_city'][0]) || empty($_POST['addition_city'][0]))
	// 		{
			    
	// 			$this->db->delete('doc_city_rate_master', array('rate_id' => $rateMasterId));
	// 			for($i=0;$i<count($_POST['addition_city']);$i++)
	// 			{
				
	// 			$data = array(  
	// 						'customer_id'    => $_POST['customer_id'],
	// 						'city_id'    => $city,
	// 						'rate_id'    => $rateMasterId,
	// 						'addition'   => $_POST['addition_city'][$i],  
	// 						'lower'  => $_POST['lower_city'][$i],  
	// 						'upper'   => $_POST['upper_city'][$i],  
	// 						'rate_amt' => $_POST['rate_amt_city'][$i]  
	// 						);  
	// 				//insert data into database table.  
	// 				$this->db->insert('doc_city_rate_master',$data);
					
	// 			}
	// 		}
        
 //        echo json_encode(['status' => 'success', 'message' => 'Rate added successfully']);
 //        exit;
	// }

	// public function getCityRates(){		
	// 	$zoneId = $_POST['zoneId'];
	// 	$rateMasterId = $_POST['rateMasterId'];
	// 	$cityId = $_POST['cityId'];

	// 	$getRates = $this->db->where(array('rate_master_id'=>$rateMasterId, 'city_id'=>$cityId))->get('tbl_rate_city')->row();
	// 	echo json_encode($getRates);
	// }

	
	}
?>
