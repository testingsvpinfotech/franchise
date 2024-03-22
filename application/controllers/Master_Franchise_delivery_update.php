<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Franchise_delivery_update extends CI_Controller {

	function __construct()
	{
		 parent:: __construct();
		 $this->load->model('basic_operation_m');
		 if($this->session->userdata('customer_id') == '')
		{
			redirect('franchise');
		}
	}
	
	public function index()
	{	 
        $data= array();
		$awb = $this->input->post('awb_no');
		$submit = $this->input->post('shipment');
	    if($submit=='Domestic'){ 
			$where = array('pod_no'=>$awb);
			$franchise_id = $_SESSION['customer_id'];
			$status = $this->db->query("SELECT * FROM tbl_domestic_tracking where status = 'Out For Delivery' order by id desc limit 1")->row();
			if(!empty($status)){
			$resAct5 = $this->db->query("SELECT * FROM tbl_domestic_booking 
			JOIN tbl_domestic_bag ON  tbl_domestic_bag.pod_no = tbl_domestic_booking.pod_no 
			JOIN tbl_domestic_menifiest ON tbl_domestic_menifiest.bag_no = tbl_domestic_bag.bag_id 
			WHERE tbl_domestic_booking.pod_no='$awb' and tbl_domestic_booking.is_delhivery_complete = '0' AND tbl_domestic_menifiest.destination_franchise = '$franchise_id' AND tbl_domestic_bag.bag_recived = '1' and tbl_domestic_menifiest.reciving_status = '1' ORDER BY tbl_domestic_bag.id DESC LIMIT 1");
			 $data['result'] = $resAct5->result_array();
			//echo $this->db->last_query();die;
			}
		}else{
			$where = array('pod_no'=>$awb);
			//$data['result'] = $this->basic_operation_m->get_all_result('tbl_international_booking',$where);
			$resAct6 = $this->db->query("SELECT * FROM tbl_international_booking INNER JOIN tbl_domestic_deliverysheet ON tbl_international_booking.pod_no=tbl_domestic_deliverysheet.pod_no where tbl_international_booking.pod_no = '$awb' Group by tbl_international_booking.pod_no");
			$data['result'] = $resAct6->result_array();
		}
		$data['all_status']= $this->basic_operation_m->get_all_result("tbl_status_delivery","");
        $this->load->view('masterfranchise/franchise_delivery_update/single_delivery_update',$data);       
	}

	public function single_delivery_status()
	{
	 	$all_data= $this->input->post();
		 date_default_timezone_set('Asia/Kolkata'); 
		 $track = date("Y-m-d H:i:s");
		
	  	if($all_data!=""){
            $tracking_date = date("Y-m-d H:i:s",strtotime($track));
			//print_r($tracking_date);die();
            $selected_dockets = $this->input->post('selected_dockets');
            $company_type = $this->input->post('company_type');
            $status = $this->input->post('status');
        	$comment = $this->input->post('comment');
        	$remarks = $this->input->post('remarks');
        
	        $is_delhivery_complete = 0;
		
			
			//echo "<pre>";print_r($selected_dockets);die();
			
			// for($doc=0;$doc<count($selected_dockets);$doc++)
			// {   
				
				$branch_name= $_SESSION['branch_name']." ".$_SESSION['customer_name']." Franchise ";
				$pod_no=$this->input->post('pod_no');
				$status=$this->input->post('status');
				$comment = $this->input->post('comment');
				$remarks = $this->input->post('remark');
				$pod = $this->input->post('pod');
				$drs_date = $this->db->query("SELECT * FROM tbl_domestic_tracking WHERE status = 'Out For Delivery' AND pod_no ='$pod' ORDER BY id DESC LIMIT 1")->row('tracking_date');
				// echo $this->db->last_query();die;
				$predate = date('Y-m-d', strtotime($drs_date));
				$curret = date('Y-m-d');
				if (
					$predate <= date('Y-m-d', strtotime($this->input->post('datetime'))) &&
					$curret >= date('Y-m-d', strtotime($this->input->post('datetime')))
				) {
				$date=date('Y-m-d H:i:s');
				if($company_type=="Domestic")
				{
				    if($status == 'Delivered')
	    			{
	    				$is_delhivery_complete = 1;
	    				$where = array('booking_id' => $selected_dockets);
	    				$updateData = [
	    					'is_delhivery_complete' => $is_delhivery_complete,
	    				];
	    				$this->db->update('tbl_domestic_booking', $updateData, $where);
						$where1 = array('booking_id' => $selected_dockets);
	    				$updateData1 = [
	    					'is_delivered' => '1',
	    				];
	    				$this->db->update('tbl_domestic_stock_history', $updateData1, $where1);
						
	    			}
				    if($status == 'Undelivered')
	    			{
	    				$is_delhivery_complete = 0;
	    				$where = array('booking_id' => $selected_dockets);
	    				$updateData = [
	    					'is_delhivery_complete' => $is_delhivery_complete,
	    				];
	    				$this->db->update('tbl_domestic_booking', $updateData, $where);
						$where1 = array('pod_no' => $pod_no);
	    				$updateData1 = [
	    					'is_delivered' => '0',
	    				];
	    				$this->db->update('tbl_domestic_stock_history', $updateData1, $where1);
						
	    			}
					
				    
				    $this->db->select('pod_no, booking_id, forworder_name, forwording_no');
	    			$this->db->from('tbl_domestic_booking');
	    			$this->db->where('booking_id', $selected_dockets);
	    			$this->db->order_by('booking_id', 'DESC');
	    			$result = $this->db->get();
	    			$resultData = $result->row();
	               // echo $this->db->last_query();die();
					// print_r($resultData);die();
	    		    $pod_no = $resultData->pod_no;
	    			$forworder_name = $resultData->forworder_name;
	    			$forwording_no = $resultData->forwording_no;
					$user_id = $this->session->userdata("customer_id");
					$gat_area = $this->db->query("select cmp_area from tbl_franchise where fid = '$user_id'")->row();
					$area = $gat_area->cmp_area;
					// print_r($area);die;
					$branch = $_SESSION['branch_name'];
					$source_branch = $branch . "_" .$area ;
				    $data = [
				        'pod_no'=>$pod_no,
				        'branch_name'=>$source_branch,
				        'booking_id'=>$selected_dockets,
				        'forworder_name'=>$forworder_name,
				        'forwording_no'=>$forwording_no,
						'tracking_date' => $this->input->post('datetime'),
						'status' => $status,
						'comment' => $comment,
						'remarks' => $remarks,
						'is_delhivery_complete' => $is_delhivery_complete,
					];
					// echo "<pre>";
					// print_r($data);
					// exit;
					if($this->db->insert('tbl_domestic_tracking', $data)){
						$msg = 'Status Added  Successfully';
						$class	= 'alert alert-success alert-dismissible';
	
						$this->session->set_flashdata('notify', $msg);
						$this->session->set_flashdata('class', $class);
					}

				}
				
			}else
			{
				$msg = 'Please select date In between DRS date to Current date';
				$class = 'alert alert-danger alert-dismissible';
				$this->session->set_flashdata('notify', $msg);
				$this->session->set_flashdata('class', $class);
			}
			// }
			
	  	}

	    redirect("master-franchise/single-delivery-update");
	}
	

}
?>