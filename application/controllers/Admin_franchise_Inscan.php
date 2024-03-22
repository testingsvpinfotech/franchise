<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_franchise_Inscan extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('basic_operation_m');
		
	}

	
	public function in_scan()
	{
		if ($_POST) {
			$awb =  $this->input->post('pod_no');

			$username = $this->session->userdata("userName");
			$branch_name= $_SESSION['branch_name'];
			$branch_id = $_SESSION['branch_id'];
			$customer_id = $this->session->userdata('customer_id');
			$franchise1 = $this->db->query("select * from tbl_customers where customer_id = $customer_id ")->row();
			$branch = $franchise1->branch_id;
			$franchise2 = $this->db->query("select * from tbl_branch where branch_id = $branch ")->row();
			$gat_area = $this->db->query("select cmp_area from tbl_franchise where fid = '$customer_id'")->row();
		    $area = $gat_area->cmp_area;
		   // $branch = $_SESSION['branch_name'];
		   $source_branch = $franchise2->branch_name. "_" .$area ;
			// $source_branch		= 	$franchise2->branch_name;
			date_default_timezone_set('Asia/Kolkata');
			$timestamp = date("Y-m-d H:i:s");

			foreach ($awb as $value) {
				$where = array('pod_no' => $value);
				$data['result'] = $this->basic_operation_m->get_all_result('tbl_domestic_booking', $where);
				$all_data['pod_no'] = $value;
				$all_data['booking_id'] = $data['result'][0]['booking_id'];
				$all_data['forwording_no'] = $data['result'][0]['forwording_no'];
				$all_data['forworder_name'] = $data['result'][0]['forworder_name'];
				$all_data['branch_name'] = $source_branch;
				$all_data['status'] = 'In-scan';
				$all_data['status'] = 'In-scan';
				$all_data['tracking_date'] = $timestamp;
				$this->basic_operation_m->insert('tbl_domestic_tracking', $all_data);

				//echo $this->db->last_query();die();
			}
			if ($data) {

				$msg = 'Branch In Scanning successfully';
				$class	= 'alert alert-success alert-dismissible';

				$this->session->set_flashdata('notify', $msg);
				$this->session->set_flashdata('class', $class);
			} else {
				$msg = 'Something went wrong in deleting the Fule';
				$class	= 'alert alert-success alert-dismissible';

				$this->session->set_flashdata('notify', $msg);
				$this->session->set_flashdata('class', $class);
			}
			redirect('franchise/inscan');
		}

		$this->load->view('franchise/inscan/inscan_add', $data);
	}


	


	public function miss_route_awb()
	{
		$awb = $this->input->post('forwording_no');
		$resAct5 = $this->db->query("select * from tbl_domestic_booking where pod_no = '$awb'");
		// echo  $this->db->last_query();die;
		$booking_row = $resAct5->row_array();
		// print_r($booking_row);die();
		$pod =  $booking_row['pod_no'];
		$booking_id = $booking_row['booking_id'];

		$query_result = $this->db->query("select * from tbl_domestic_weight_details where booking_id = '$booking_id'")->row_array();

		$actual_weight = $query_result['actual_weight'];
		//$no_of_pack	   = $booking_row['a_qty'];
		$no_of_pack = $query_result['no_of_pack'];
		$podid 		   = "checkbox-" . $pod;
		$dataid 	   = 'data-val-' . $booking_id;
		$data = "";
		$pod_no = $booking_row['pod_no'];
		$data .= '<tr><td>';
		$data .= "<input type='checkbox' class='cb'  name='pod_no[]'  data-tp='{$no_of_pack}' data-tw='{$actual_weight}' value='{$pod_no}' checked><input type='hidden' name='actual_weight[]' value='" . $actual_weight . "'/><input type='hidden' name='pcs[]' value='" . $no_of_pack . "'/></td>";

		// $data .= "<input type='checkbox' class='cb'  name='pod_no[]'  data-tp='{$no_of_pack}' data-tw='{$actual_weight}' value='{$pod_no}' checked>";

		$data .= "<input type='checkbox' class='cb'  name='actual_weight[]' value='" . $actual_weight . "' checked>";
		$data .= "<input type='checkbox' class='cb'  name='pcs[]' value='" . $no_of_pack . "' checked>";

		$data .= "<input type='hidden' name='rec_pincode' value=" . $booking_row['reciever_pincode'] . "><td>";
		$data .= $booking_row['pod_no'];
		$data .= "</td>";
		$data .= "<td>";
		$data .= $booking_row['sender_name'];
		$data .= "</td>";
		$data .= "<td>";
		$data .= $booking_row['reciever_name'];
		$data .= "</td>";
		$data .= "<td><input type='hidden' readonly name='forwarder_name' id='forwarder_name'  class='form-control' value='" . $booking_row['forworder_name'] . "'/><input type='hidden' readonly name='branch_name' id='branch_name'  class='form-control' value='" . $branch_name . "'/>";
		$data .= $booking_row['forworder_name'];
		$data .= "</td>";
		$resAct6 = $this->db->query("select * from city where id ='" . $booking_row['sender_city'] . "'");
		if ($resAct6->num_rows() > 0) {
			$citydata  		 = $resAct6->row();
			$data		 	.= "<td>";
			$data		 	.= $citydata->city;
			$data	 		.= "</td>";
		}
		$resAct6 = $this->db->query("select * from city where id ='" . $booking_row['reciever_city'] . "'");
		if ($resAct6->num_rows() > 0) {
			$citydata  		 = $resAct6->row();
			$data		 	.= "<td>";
			$data		 	.= $citydata->city;
			$data	 		.= "</td>";
		}
		$data .= "<td>";
		$data .= $booking_row['dispatch_details'];
		$data .= "</td>";
		$data .= "<td>";
		$data .= $no_of_pack;
		$data .= "</td>";
		$data .= "<td>";
		$data .= $query_result['actual_weight'];
		$data .= "</td>";
		$data .= "<td>";
		$data .= $query_result['chargable_weight'];
		$data .= "</td>";
		$data .= "</tr>";
		if (empty($booking_row)) {
			$val = '<script type="text/javascript">
			$(document).ready(function(e) {
			alert("AWB Not Exists");
			});
			</script>';
           echo $val;
		} else {
			echo  $data;
		}
	}
	public function franchise_awb_scan()
	{
		$awb = $this->input->post('forwording_no');
		$resAct5 = $this->db->query("select * from tbl_domestic_booking where pod_no = '$awb'");
		// echo  $this->db->last_query();die;
		$booking_row = $resAct5->row_array();
		// print_r($booking_row);die();
		$pod =  $booking_row['pod_no'];
		$booking_id = $booking_row['booking_id'];

		$query_result = $this->db->query("select * from tbl_domestic_weight_details where booking_id = '$booking_id'")->row_array();

		$actual_weight = $query_result['actual_weight'];
		//$no_of_pack	   = $booking_row['a_qty'];
		$no_of_pack = $query_result['no_of_pack'];
		$podid 		   = "checkbox-" . $pod;
		$dataid 	   = 'data-val-' . $booking_id;
		$data = "";
		$pod_no = $booking_row['pod_no'];
		$data .= '<tr><td>';
		$data .= "<input type='checkbox' class='cb'  name='pod_no[]'  data-tp='{$no_of_pack}' data-tw='{$actual_weight}' value='{$pod_no}' checked><input type='hidden' name='actual_weight[]' value='" . $actual_weight . "'/><input type='hidden' name='pcs[]' value='" . $no_of_pack . "'/></td>";

		// $data .= "<input type='checkbox' class='cb'  name='pod_no[]'  data-tp='{$no_of_pack}' data-tw='{$actual_weight}' value='{$pod_no}' checked>";

		$data .= "<input type='checkbox' class='cb'  name='actual_weight[]' value='" . $actual_weight . "' checked>";
		$data .= "<input type='checkbox' class='cb'  name='pcs[]' value='" . $no_of_pack . "' checked>";

		$data .= "<input type='hidden' name='rec_pincode' value=" . $booking_row['reciever_pincode'] . "><td>";
		$data .= $booking_row['pod_no'];
		$data .= "</td>";
		$data .= "<td>";
		$data .= $booking_row['sender_name'];
		$data .= "</td>";
		$data .= "<td>";
		$data .= $booking_row['reciever_name'];
		$data .= "</td>";
		$data .= "<td><input type='hidden' readonly name='forwarder_name' id='forwarder_name'  class='form-control' value='" . $booking_row['forworder_name'] . "'/><input type='hidden' readonly name='branch_name' id='branch_name'  class='form-control' value='" . $branch_name . "'/>";
		$data .= $booking_row['forworder_name'];
		$data .= "</td>";
		$resAct6 = $this->db->query("select * from city where id ='" . $booking_row['sender_city'] . "'");
		if ($resAct6->num_rows() > 0) {
			$citydata  		 = $resAct6->row();
			$data		 	.= "<td>";
			$data		 	.= $citydata->city;
			$data	 		.= "</td>";
		}
		$resAct6 = $this->db->query("select * from city where id ='" . $booking_row['reciever_city'] . "'");
		if ($resAct6->num_rows() > 0) {
			$citydata  		 = $resAct6->row();
			$data		 	.= "<td>";
			$data		 	.= $citydata->city;
			$data	 		.= "</td>";
		}
		$data .= "<td>";
		$data .= $booking_row['dispatch_details'];
		$data .= "</td>";
		$data .= "<td>";
		$data .= $no_of_pack;
		$data .= "</td>";
		$data .= "<td>";
		$data .= $query_result['actual_weight'];
		$data .= "</td>";
		$data .= "<td>";
		$data .= $query_result['chargable_weight'];
		$data .= "</td>";
		$data .= "</tr>";
		if (empty($booking_row)) {
			$val = '<script type="text/javascript">
			$(document).ready(function(e) {
			alert("AWB Not Exists");
			});
			</script>';
           echo $val;
		} else {
			echo  $data;
		}
	}

	public function in_scan_awb_scan()
	{

		$awb = $this->input->post('forwording_no');
		$resAct5 = $this->db->query("select * from tbl_domestic_booking where pod_no = '$awb'");
		// echo  $this->db->last_query();die;
		$booking_row = $resAct5->row_array();
		// print_r($booking_row);die();
		$pod =  $booking_row['pod_no'];
		$booking_id = $booking_row['booking_id'];

		$query_result = $this->db->query("select * from tbl_domestic_weight_details where booking_id = '$booking_id'")->row_array();

		$actual_weight = $query_result['actual_weight'];
		//$no_of_pack	   = $booking_row['a_qty'];
		$no_of_pack = $query_result['no_of_pack'];
		$podid 		   = "checkbox-" . $pod;
		$dataid 	   = 'data-val-' . $booking_id;
		$data = "";
		$pod_no = $booking_row['pod_no'];
		$data .= '<tr><td>';
		$data .= "<input type='checkbox' class='cb'  name='pod_no[]'  data-tp='{$no_of_pack}' data-tw='{$actual_weight}' value='{$pod_no}' checked><input type='hidden' name='actual_weight[]' value='" . $actual_weight . "'/><input type='hidden' name='pcs[]' value='" . $no_of_pack . "'/></td>";

		// $data .= "<input type='checkbox' class='cb'  name='pod_no[]'  data-tp='{$no_of_pack}' data-tw='{$actual_weight}' value='{$pod_no}' checked>";

		$data .= "<input type='checkbox' class='cb'  name='actual_weight[]' value='" . $actual_weight . "' checked>";
		$data .= "<input type='checkbox' class='cb'  name='pcs[]' value='" . $no_of_pack . "' checked>";

		$data .= "<input type='hidden' name='rec_pincode' value=" . $booking_row['reciever_pincode'] . "><td>";
		$data .= $booking_row['pod_no'];
		$data .= "</td>";
		$data .= "<td>";
		$data .= $booking_row['sender_name'];
		$data .= "</td>";
		$data .= "<td>";
		$data .= $booking_row['reciever_name'];
		$data .= "</td>";
		$data .= "<td><input type='hidden' readonly name='forwarder_name' id='forwarder_name'  class='form-control' value='" . $booking_row['forworder_name'] . "'/><input type='hidden' readonly name='branch_name' id='branch_name'  class='form-control' value='" . $branch_name . "'/>";
		$data .= $booking_row['forworder_name'];
		$data .= "</td>";
		$resAct6 = $this->db->query("select * from city where id ='" . $booking_row['sender_city'] . "'");
		if ($resAct6->num_rows() > 0) {
			$citydata  		 = $resAct6->row();
			$data		 	.= "<td>";
			$data		 	.= $citydata->city;
			$data	 		.= "</td>";
		}
		$resAct6 = $this->db->query("select * from city where id ='" . $booking_row['reciever_city'] . "'");
		if ($resAct6->num_rows() > 0) {
			$citydata  		 = $resAct6->row();
			$data		 	.= "<td>";
			$data		 	.= $citydata->city;
			$data	 		.= "</td>";
		}
		$data .= "<td>";
		$data .= $booking_row['dispatch_details'];
		$data .= "</td>";
		$data .= "<td>";
		$data .= $no_of_pack;
		$data .= "</td>";
		$data .= "<td>";
		$data .= $query_result['actual_weight'];
		$data .= "</td>";
		$data .= "<td>";
		$data .= $query_result['chargable_weight'];
		$data .= "</td>";
		$data .= "</tr>";
		if (empty($booking_row)) {
			$val = '<script type="text/javascript">
			$(document).ready(function(e) {
			alert("AWB Not Exists");
			});
			</script>';
echo $val;
		} else {
			echo  $data;
		}
	}
	
	public function pickup_awb_scan()
	{

		$awb = $this->input->post('forwording_no');
		$customer_id = $_SESSION['customer_id'];
		$resAct5 = $this->db->query("select * from tbl_domestic_booking where pod_no = '$awb'  AND pickup_in_scan = '0' AND branch_in_scan = '0' and customer_id = '$customer_id'");
		// echo  $this->db->last_query();die;
		$booking_row = $resAct5->row_array();
		// print_r($booking_row);die();
		$pod =  $booking_row['pod_no'];
		$booking_id = $booking_row['booking_id'];

		$query_result = $this->db->query("select * from tbl_domestic_weight_details where booking_id = '$booking_id'")->row_array();

		$actual_weight = $query_result['actual_weight'];
		//$no_of_pack	   = $booking_row['a_qty'];
		$no_of_pack = $query_result['no_of_pack'];
		$podid 		   = "checkbox-" . $pod;
		$dataid 	   = 'data-val-' . $booking_id;
		$data = "";
		$pod_no = $booking_row['pod_no'];
		$data .= '<tr><td>';
		$data .= "<input type='checkbox' class='cb'  name='pod_no[]'  data-tp='{$no_of_pack}' data-tw='{$actual_weight}' value='{$pod_no}' checked><input type='hidden' name='actual_weight[]' value='" . $actual_weight . "'/><input type='hidden' name='pcs[]' value='" . $no_of_pack . "'/></td>";

		// $data .= "<input type='checkbox' class='cb'  name='pod_no[]'  data-tp='{$no_of_pack}' data-tw='{$actual_weight}' value='{$pod_no}' checked>";

		$data .= "<input type='checkbox' class='cb'  name='actual_weight[]' value='" . $actual_weight . "' checked>";
		$data .= "<input type='checkbox' class='cb'  name='pcs[]' value='" . $no_of_pack . "' checked>";

		$data .= "<input type='hidden' name='rec_pincode' value=" . $booking_row['reciever_pincode'] . "><td>";
		$data .= $booking_row['pod_no'];
		$data .= "</td>";
		$data .= "<td>";
		$data .= $booking_row['sender_name'];
		$data .= "</td>";
		$data .= "<td>";
		$data .= $booking_row['reciever_name'];
		$data .= "</td>";
		$data .= "<td><input type='hidden' readonly name='forwarder_name' id='forwarder_name'  class='form-control' value='" . $booking_row['forworder_name'] . "'/><input type='hidden' readonly name='branch_name' id='branch_name'  class='form-control' value='" . $branch_name . "'/>";
		$data .= $booking_row['forworder_name'];
		$data .= "</td>";
		$resAct6 = $this->db->query("select * from city where id ='" . $booking_row['sender_city'] . "'");
		if ($resAct6->num_rows() > 0) {
			$citydata  		 = $resAct6->row();
			$data		 	.= "<td>";
			$data		 	.= $citydata->city;
			$data	 		.= "</td>";
		}
		$resAct6 = $this->db->query("select * from city where id ='" . $booking_row['reciever_city'] . "'");
		if ($resAct6->num_rows() > 0) {
			$citydata  		 = $resAct6->row();
			$data		 	.= "<td>";
			$data		 	.= $citydata->city;
			$data	 		.= "</td>";
		}
		$data .= "<td>";
		$data .= $booking_row['dispatch_details'];
		$data .= "</td>";
		$data .= "<td>";
		$data .= $no_of_pack;
		$data .= "</td>";
		$data .= "<td>";
		$data .= $query_result['actual_weight'];
		$data .= "</td>";
		$data .= "<td>";
		$data .= $query_result['chargable_weight'];
		$data .= "</td>";
		$data .= "</tr>";
		if (empty($booking_row)) {
			$val = '<script type="text/javascript">
			$(document).ready(function(e) {
			alert("AWB Not Exists");
			});
			</script>';
echo $val;
		} else {
			echo  $data;
		}
	}






	public function pickup_in_scan_status_insert()
	{

		if ($_POST) {
			$awb =  $this->input->post('pod_no');

			$user_id = $this->session->userdata("customer_id");
			$gat_area = $this->db->query("select cmp_area from tbl_franchise where fid = '$user_id'")->row();
            $area = $gat_area->cmp_area;
			// print_r($area);die;
            $branch = $_SESSION['branch_name'];
			
			if(!empty($area)){
				$source_branch = $branch . "_" .$area ;
			}else
			{
				$source_branch = $branch;
			}
			date_default_timezone_set('Asia/Kolkata');
			$timestamp = date("Y-m-d H:i:s");
			$this->db->trans_start();
			foreach ($awb as $value) {
				$where = array('pod_no' => $value);
				$data['result'] = $this->basic_operation_m->get_all_result('tbl_domestic_booking', $where);
				$all_data['pod_no'] = $value;
				$all_data['booking_id'] = $data['result'][0]['booking_id'];
				$all_data['forwording_no'] = $data['result'][0]['forwording_no'];
				$all_data['forworder_name'] = $data['result'][0]['forworder_name'];
				$all_data['branch_name'] = $source_branch;
				$all_data['status'] = 'Pickup-In-scan';
				$all_data['remarks'] = $this->input->post('remark');
				$all_data['tracking_date'] = $timestamp;
				$this->basic_operation_m->insert('tbl_domestic_tracking', $all_data);

				$queue_dataa		= "update tbl_domestic_booking set pickup_in_scan ='1', branch_in_scan = '1' where pod_no = '$value'";
					$status				= $this->db->query($queue_dataa);
			}
			$this->db->trans_complete();
			if ($this->db->trans_status() === TRUE)
			{
				$this->db->trans_commit();
				$msg = 'Pickup Scanning successfully';
				$class	= 'alert alert-success alert-dismissible';
			}
			else
			{
				$this->db->trans_rollback();	
				$msg = 'Something went wrong';
				$class	= 'alert alert-danger alert-dismissible';
			}		
				$this->session->set_flashdata('notify', $msg);
				$this->session->set_flashdata('class', $class);
			redirect('franchise/pickup-in-scan');
		}
		$this->load->view('franchise/inscan/pickup_inscan_add', $data);
	}

	public function mis_route()
	{

		if ($_POST) {
			$awb =  $this->input->post('pod_no');

			$branch_name= $_SESSION['branch_name'];
			$branch_id = $_SESSION['branch_id'];
			$source_branch		= 	$branch_name;
			date_default_timezone_set('Asia/Kolkata');
			$timestamp = date("Y-m-d H:i:s");
			foreach ($awb as $value) {
				$where = array('pod_no' => $value);
				$data['result'] = $this->basic_operation_m->get_all_result('tbl_domestic_booking', $where);
				$all_data['pod_no'] = $value;
				$all_data['booking_id'] = $data['result'][0]['booking_id'];
				$all_data['forwording_no'] = $data['result'][0]['forwording_no'];
				$all_data['forworder_name'] = $data['result'][0]['forworder_name'];
				$all_data['branch_name'] = $source_branch;
				$all_data['status'] = 'Miss Route';
				$all_data['status'] = 'Miss Route';
				$all_data['tracking_date'] = $timestamp;

				$this->basic_operation_m->insert('tbl_domestic_tracking', $all_data);
				// echo $this->db->last_query();die();
			}
			if ($data) {

				$msg = 'Miss Route Scanning successfully';
				$class	= 'alert alert-success alert-dismissible';

				$this->session->set_flashdata('notify', $msg);
				$this->session->set_flashdata('class', $class);
			} else {
				$msg = 'Something went wrong in deleting the Fule';
				$class	= 'alert alert-success alert-dismissible';

				$this->session->set_flashdata('notify', $msg);
				$this->session->set_flashdata('class', $class);
			}
			redirect('franchise/miss-route');
		}
		$this->load->view('franchise/inscan/miss_route', $data);
	}

	public function franchise_in_scan()
	{

		if ($_POST) {
			$awb =  $this->input->post('pod_no');

			$branch_name= $_SESSION['branch_name'];
			$branch_id = $_SESSION['branch_id'];
			$source_branch		= 	$branch_name;
			date_default_timezone_set('Asia/Kolkata');
			$timestamp = date("Y-m-d H:i:s");
			foreach ($awb as $value) {
				$where = array('pod_no' => $value);
				$data['result'] = $this->basic_operation_m->get_all_result('tbl_domestic_booking', $where);
				$all_data['pod_no'] = $value;
				$all_data['booking_id'] = $data['result'][0]['booking_id'];
				$all_data['forwording_no'] = $data['result'][0]['forwording_no'];
				$all_data['forworder_name'] = $data['result'][0]['forworder_name'];
				$all_data['branch_name'] = $source_branch;
				$all_data['status'] = 'Franchise-In-scan';
				$all_data['status'] = 'Franchise-In-scan';
				$all_data['tracking_date'] = $timestamp;

				$this->basic_operation_m->insert('tbl_domestic_tracking', $all_data);
				// echo $this->db->last_query();die();
			}
			if ($data) {

				$msg = 'Franchise In-scan Scanning successfully';
				$class	= 'alert alert-success alert-dismissible';

				$this->session->set_flashdata('notify', $msg);
				$this->session->set_flashdata('class', $class);
			} else {
				$msg = 'Something went wrong in deleting the Fule';
				$class	= 'alert alert-success alert-dismissible';

				$this->session->set_flashdata('notify', $msg);
				$this->session->set_flashdata('class', $class);
			}
			redirect('franchise/franchise-in-scan');
		}
		$this->load->view('franchise/inscan/franchise_in_scan', $data);
	}




	

	

	
}
