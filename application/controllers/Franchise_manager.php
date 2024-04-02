<?php
ini_set('display_errors', 0);
defined('BASEPATH') or exit('No direct script access allowed');

class Franchise_manager extends CI_Controller
{
	var $data = array();
	function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
		$this->load->model('basic_operation_m');
		$this->data['company_info'] = $this->basic_operation_m->get_query_row("select * from tbl_company limit 1");

		if ($this->session->userdata('customer_id') == '') {
			redirect('franchise');
		}
	}



	public function print_label_franchise($id)
	{
		// Load library
		$this->load->library('zend');
		// Load in folder Zend
		$this->zend->load('Zend/Barcode');
		$whr = array('booking_id' => $id);
		$user_id = $this->session->userdata("userId");
		$user_type = $this->session->userdata("userType");
		if ($id != "") {
			$data['booking'] = $this->basic_operation_m->get_all_result('tbl_domestic_booking', $whr);
			$where = array('id' => 1);
			$data['company_details'] = $this->basic_operation_m->get_table_row('tbl_company', $where);
			// 			echo '<pre>'; print_r($data['booking']); die;
			$this->load->view('franchise/booking_master/print_shipment_franchise', $data);
		}
	}
	public function rate_franchise_calculator()
	{

		$this->load->view('franchise/booking_master/rate_calculator');

	}


	public function Profile($id)
	{   $data =[];
		// Fetch profile info from tbl_customers
		$data['profile_info'] = $this->db->query("SELECT * FROM tbl_customers WHERE customer_id ='$id'")->row();
		$data['franchise_info'] = $this->db->query("SELECT * FROM tbl_franchise")->row();
		$data['state_data']   = $this->db->query("SELECT * FROM `state` where id")->result_array();
		$data['city_data']   = $this->db->query("SELECT * FROM city where id")->result_array();
        $this->load->view('franchise/change_pass/profile_edit',$data);
	}

	public function update_profile($id)
{
    if ($this->input->post()) {
        $data = [
            'customer_name' => $this->input->post('customer_name'),
            'email' => $this->input->post('email'),
            'address' => $this->input->post('address'),
            'city' => $this->input->post('city'),
            'state' => $this->input->post('state'),
            'phone' => $this->input->post('phone')
        ];

        $this->db->where('customer_id', $id);
        $this->db->update('tbl_customers', $data);

        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('notify', 'Data updated successfully.');
            $this->session->set_flashdata('class', 'alert alert-success alert-dismissible');
        } else {
            $this->session->set_flashdata('notify', 'Failed to update data.');
            $this->session->set_flashdata('class', 'alert alert-danger alert-dismissible');
        }
        redirect('franchise/edit_profile/' . $id);
    }
}

	public function change_pass()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$customerId = $this->input->post('customer_id');
			$oldPassword = $this->input->post('oldPassword');
			$newPassword = $this->input->post('newPassword');
			$confirmedPassword = $this->input->post('confirmPassword');
	
			if ($newPassword != $confirmedPassword) {
				$msg = 'New password and confirm password do not match.';
				$class = 'alert alert-danger alert-dismissible';
			} else {
				// Hash passwords
				$hashedNewPassword = md5($newPassword);
				$hashedOldPassword = md5($oldPassword);
	
				// Check old password from the database
				$this->db->select('customer_id');
				$this->db->where('customer_id', $customerId);
				$this->db->where('password', $hashedOldPassword);
				$query = $this->db->get('tbl_customers'); 
	
				if ($query->num_rows() == 1) {
					// Password correct, update it
					$data = array('password' => $hashedNewPassword);
					$this->db->where('customer_id', $customerId);
					$this->db->update('tbl_customers', $data); 
	
					$msg = 'Password updated successfully.';
					$class = 'alert alert-success alert-dismissible';
				} else {
					// Incorrect old password
					$msg = 'Incorrect old password.';
					$class = 'alert alert-danger alert-dismissible';
				}
			}
			// Set flashdata and redirect
			$this->session->set_flashdata('notify', $msg);
			$this->session->set_flashdata('class', $class);
			redirect('franchise/change_password');
		}
	
		$this->load->view('franchise/change_pass/change_password');
	}

	public function franchise_printlabel($prntext)
	{
		// Load library
		$this->load->library('zend');
		// Load in folder Zend
		$this->zend->load('Zend/Barcode');
		$prntext = $prntext . str_repeat('=', strlen($prntext) % 4);
		$prntext = base64_decode($prntext);
		$data['prntext'] = $prntext;
		$this->load->view('franchise/booking_master/domestic_printlabel', $data);
	}

	public function franchise_dashboard()
	{
		$data = $this->data;
		$date = date('y-m-d');
		$customer_id = $this->session->userdata("customer_id");
		$dd2 = $this->db->query("SELECT tbl_domestic_booking.*,transfer_mode.mode_name as mode_dispatch  FROM `tbl_domestic_booking`LEFT JOIN transfer_mode ON tbl_domestic_booking.mode_dispatch =transfer_mode.transfer_mode_id  WHERE booking_type = 0 AND `customer_id`= $customer_id")->result();
		$data['total_booking_shipment'] = $this->db->query("SELECT COUNT(booking_id) AS total_shipment_booking FROM tbl_domestic_booking WHERE customer_id = '$customer_id'")->row();
		$data['total_delivered_shipment'] = $this->db->query("SELECT COUNT(booking_id) AS total_delivered_shipment FROM `tbl_domestic_booking` WHERE is_delhivery_complete = '1' AND customer_id = '$customer_id' AND MONTH(booking_date) = MONTH(CURRENT_DATE())")->row();
		$data['total_pending_shipment'] = $this->db->query("SELECT COUNT(booking_id) AS total_pending_booking FROM `tbl_domestic_booking` join tbl_domestic_deliverysheet ON tbl_domestic_deliverysheet.pod_no = tbl_domestic_booking.pod_no WHERE tbl_domestic_booking.booking_type = '0' AND tbl_domestic_booking.user_id = '$customer_id' AND is_delhivery_complete = '0' AND MONTH(booking_date) = MONTH(CURRENT_DATE())")->row();
		$data['today_pending_shipment'] = $this->db->query("SELECT COUNT(booking_id) AS today_pending_shipment FROM `tbl_domestic_booking` join tbl_domestic_deliverysheet ON tbl_domestic_deliverysheet.pod_no = tbl_domestic_booking.pod_no WHERE tbl_domestic_booking.booking_type = '0' AND tbl_domestic_booking.user_id = '$customer_id' AND is_delhivery_complete = '0' AND booking_date = '$date'")->row();
		$data['today_total_booking_shipment'] = $this->db->query("SELECT COUNT(booking_id) AS today_total_shipment_booking FROM `tbl_domestic_booking` WHERE  customer_id = '$customer_id' AND booking_date = '$date'")->row();

		$this->load->view('franchise/dashboard', $data);
	}

	public function available_cft() {
		$courier_id = $this->input->post('courier_id');
		$booking_date = trim($this->input->post('booking_date'));
		$customer_id = trim($this->input->post('customer_id'));

		if (!empty($booking_date)) {
			$current_date = date("Y-m-d",strtotime($booking_date));
		}else{
			$current_date = date('Y-m-d');
		}
		
		
		$whr1 = array('fuel_from <=' => $current_date,'fuel_to >=' => $current_date);
		$where = '(courier_id="'.$courier_id.'" or courier_id = "0") AND (customer_id="'.$customer_id.'" or customer_id = "0")';
		$this->db->select('*');
		$this->db->from('courier_fuel');
		$this->db->where($whr1);
		$this->db->where($where);
		$this->db->order_by('customer_id','DESC');
		// $this->db->where('customer_id',$customer_id);
		
		$query	=	$this->db->get();
		$res1 = $query->row();
		// $res1 = $this->basic_operation_m->get_table_row('courier_fuel', $whr1);

		if($res1){$fuel_per = $res1->cft; }else{$fuel_per ='0';}
		if($res1){$fuel_per2 = $res1->air_cft; }else{$fuel_per2 ='0';}

		// echo $this->db->last_query();

		$result2= array('cft_charges'=>7,'air_cft'=>$fuel_per2);
		echo json_encode($result2);
	}
	public function today_shipment_list()
	{
		$data = $this->data;
		$date = date('y-m-d');
		$customer_id = $this->session->userdata("customer_id");
		$data['shipment_list'] = $this->db->query("SELECT * FROM `tbl_domestic_booking` WHERE  customer_id = '$customer_id' AND booking_date = '$date'")->result();
		$this->load->view('franchise/booking_master/today_shipment_list', $data);
	}
	public function month_pending_list()
	{
		$data = $this->data;
		$date = date('y-m-d');
		$customer_id = $this->session->userdata("customer_id");
		$data['shipment_list'] = $this->db->query("SELECT * FROM `tbl_domestic_booking` join tbl_domestic_deliverysheet ON tbl_domestic_deliverysheet.pod_no = tbl_domestic_booking.pod_no WHERE tbl_domestic_booking.booking_type = '0' AND tbl_domestic_booking.user_id = '$customer_id' AND is_delhivery_complete = '0' AND MONTH(booking_date) = MONTH(CURRENT_DATE())")->result();
		$this->load->view('franchise/booking_master/month_pending_list', $data);
	}
	public function today_pending_list()
	{
		$data = $this->data;
		$date = date('y-m-d');
		$customer_id = $this->session->userdata("customer_id");
		$data['shipment_list'] = $this->db->query("SELECT * FROM `tbl_domestic_booking` join tbl_domestic_deliverysheet ON tbl_domestic_deliverysheet.pod_no = tbl_domestic_booking.pod_no WHERE tbl_domestic_booking.booking_type = '0' AND tbl_domestic_booking.user_id = '$customer_id' AND is_delhivery_complete = '0' AND booking_date = '$date'")->result();
		$this->load->view('franchise/booking_master/today_pending_list', $data);
	}
	public function delivered_shipment_list()
	{
		$data = $this->data;
		$date = date('y-m-d');
		$customer_id = $this->session->userdata("customer_id");
		$data['shipment_list'] = $this->db->query(" SELECT * FROM `tbl_domestic_booking` WHERE is_delhivery_complete = '1' AND customer_id = '$customer_id' AND MONTH(booking_date) = MONTH(CURRENT_DATE())")->result();
		// echo $this->db->last_query();die;
		$this->load->view('franchise/booking_master/delivered_shipment_list', $data);
	}



	public function getCityList()
	{
		$data = array();
		$pincode = $this->input->post('pincode');
		$whr1 = array('pin_code' => $pincode,'isdeleted'=>0);
		$res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);

		$pin_code = @$res1->row()->pin_code;
		$city_id = @$res1->row()->city_id;
		$isODA = @service_type[$res1->row()->isODA];

		if (!$pin_code) {
			$data['status'] = "failed";
			$data['message'] = " The pin code <b> ".$pincode." </b> is NSS (No Service Station).\n To add this pin code in system, please contact your Admin/Manager.";
			echo json_encode($data);
			exit();
		}

		$whr2 = array('id' => $city_id);
		$res2 = $this->basic_operation_m->selectRecord('city', $whr2);
		$result2 = $res2->row();
		$state_id = $res2->row()->state_id;


		$whr1 = array('state' => $state_id, 'city' => $city_id);
		$res1 = $this->basic_operation_m->selectRecord('region_master_details', $whr1);

		$regionid = @$res1->row()->regionid;
		$result2->regionid = $regionid;
		$result2->regionid = $regionid;

		$data['status'] = "success";
		echo json_encode($result2);
	}


	public function getState()
	{
		$pincode = $this->input->post('pincode');
		$whr1 = array('pin_code' => $pincode,'isdeleted'=>0);
		$res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);
		
		$state_id = $res1->row()->state_id;
		$whr3 = array('id' => $state_id);
		$res3 = $this->basic_operation_m->selectRecord('state', $whr3);
		$data['result3'] = $res3->row();
		$data['oda'] = service_type[$res1->row('isODA')];
		// print_r($data);die;
		echo json_encode($data);
	}


	public function getZone()
	{
		$reciever_state = $this->input->post('reciever_state');
		$reciever_city = $this->input->post('reciever_city');
		//    print_r($_POST);die;
		$whr1 = array('state' => $reciever_state, 'city' => $reciever_city);
		$res1 = $this->basic_operation_m->selectRecord('region_master_details', $whr1);

		$regionid = $res1->row()->regionid;

		$whr3 = array('region_id' => $regionid);
		$res3 = $this->basic_operation_m->selectRecord('region_master', $whr3);
		$result3 = $res3->row();

		echo json_encode($result3);
	}

	public function getsenderdetails()
	{
		$data = [];
		$customer_name = $this->input->post('customer_name');
		//	print_r($customer_name);exit;
		$whr1 = array('customer_id' => $customer_name);
		//$res1 = $this->basic_operation_m->selectRecord('tbl_customers', $whr1);

		$res1 = $this->basic_operation_m->get_customer_details($whr1);
		//	echo $this->db->last_query();exit;
		//$result1 = $res1->row();
		$data['user'] = $res1;
		echo json_encode($data);
		exit;
	}


	public function check_duplicate_awb_no()
	{
		$data = [];
		$pod_no = $this->input->post('pod_no');
		$whr = array('pod_no' => $pod_no);
		$result = $this->basic_operation_m->get_table_row('tbl_domestic_booking', $whr);
		$pod_no1 = $result->pod_no;
		if ($pod_no1 != "") {
			$data['msg'] = "Forwording number is duplicate ";
		} else {
			$val = explode(franchise_id(), $pod_no);
			$stock_id = $val[1];

			$user_id = $this->session->userdata("customer_id");

			$seriess_from = $this->db->query("select * from tbl_branch_assign_cnode where customer_id = '$user_id'")->row('seriess_from');
			$seriess_to = $this->db->query("select * from tbl_branch_assign_cnode where customer_id = '$user_id'")->row('seriess_to');
			if (!empty($stock_id)) {
				$stock_data = $this->db->query('SELECT * FROM tbl_branch_assign_cnode branch WHERE  customer_id = ' . $user_id . ' AND (' . $stock_id . ' BETWEEN seriess_from AND seriess_to)')->row();
				$mode_name = $this->db->get_where('transfer_mode', ['transfer_mode_id' => $stock_data->mode])->row();
				$whr = array('pod_no' => $pod_no);
				$result = $this->basic_operation_m->get_table_row('tbl_domestic_booking', $whr);
				if (empty($stock_data)) {
					$data['msg'] = "This AWB No. is not in allocated range " . franchise_id() . $seriess_from . " to " . franchise_id() . $seriess_to;
					$data['mode'] = "";
					$data['from'] = franchise_id() . $seriess_from;
					$data['to'] = franchise_id() . $seriess_to;
				} else {
					$data['msg'] = "";
					$data['mode'] = $mode_name;
				}
			} else {
				$data['msg'] = "";
				$data['mode'] = "";
				$data['from'] = franchise_id() . $seriess_from;
				$data['to'] = franchise_id() . $seriess_to;
			}
		}
		// print_r($data);die;
		echo json_encode($data);
		exit;
	}

	public function getFuelcharges()
	{
		$customer_id = $this->input->post('customer_id');
		$dispatch_details = $this->input->post('dispatch_details');
		$courier_id = $this->input->post('courier_id');
		$sub_amount = $this->input->post('sub_amount');
		$booking_date = $this->input->post('booking_date');
        // print_r($_POST['customer_id']);die;
		$get_fuel_id = $this->db->query("select * from franchise_delivery_tbl where delivery_franchise_id = '$customer_id'")->row();
		$dd = $get_fuel_id->fule_group;
		$get_fuel_details = $this->db->query("select * from franchise_fule_tbl where group_id = '$dd'")->row();
		$current_date = date("Y-m-d", strtotime($booking_date));

		$whr1 = array('from_date <=' => $current_date, 'to_date >=' => $current_date, 'group_id' => $dd);
		$res1 = $this->db->query("select * from franchise_fule_tbl where from_date <='$current_date' AND to_date >='$current_date' AND group_id = '$dd' ")->row();
		
		if ($res1) {

			$fov_rate = $res1->fov_rate;
			$awb_rate = $res1->awb_rate;
			$topay_rate = $res1->topay_rate;
			$cod_min = $res1->cod_min;
			$cod_percentage = $res1->cod_percentage;
			$fuel_per = $res1->fule_percentage;
		} else {
			$fuel_per = '0';
		}

		$final_fuel_charges = ($sub_amount * $fuel_per / 100);

		$sub_total = ($sub_amount + $final_fuel_charges);

		//print_r($final_fuel_charges);
		$gst_details = $this->basic_operation_m->get_query_row('select * from tbl_gst_setting order by id desc limit 1');



		if ($gst_details) {
			$cgst_per = $gst_details->cgst;
			$sgst_per = $gst_details->sgst;
			$igst_per = $gst_details->igst;
		} else {
			$cgst_per = '0';
			$sgst_per = '0';
			$igst_per = '0';
		}



		$tbl_customers_info = $this->basic_operation_m->get_query_row("select gst_charges from tbl_customers where customer_id = '$customer_id'");

		if ($tbl_customers_info->gst_charges == 1) {
			$cgst = ($sub_total * $cgst_per / 100);
			$sgst = ($sub_total * $sgst_per / 100);
			$igst = 0;
		} else {
			$cgst = 0;
			$sgst = 0;
			$igst = 0;
		}

		


		$grand_total = $sub_total + $cgst + $sgst + $igst;


		$result2 = array(
			'final_fuel_charges' => $final_fuel_charges,
			'sub_total' => number_format($sub_total, 2, '.', ''),
			'cgst' => number_format($cgst, 2, '.', ''),
			'sgst' => number_format($sgst, 2, '.', ''),
			'igst' => number_format($igst, 2, '.', ''),
			'grand_total' => number_format($grand_total, 2, '.', ''),
			'fov_rate' => $fov_rate,
			'awb_rate' => $awb_rate,
			'cod_percentage' => $cod_percentage,
			'cod_min' => $cod_min,
			'fule_percentage' => $fuel_per,

		);

		echo json_encode($result2);
	}

	public function get_booking_info()
	{
		$pod_no = $this->input->post('pod_no');
		$booking_info = $this->db->query("SELECT *  FROM `tbl_domestic_booking` where pod_no = '$pod_no'")->row();
		if (!empty($booking_info)) {
			$option = '<table id="myTable" class="display table table-bordered text-center">
		 <thead>
		 <tr>                 
			 <th>SR.No</th>
			 <th>Booking Date</th>
			 <th>AWB.No</th>
			 <th>Sender Name</th>
			 <th>Receiver Name</th>
			 <th>Receiver Pincode</th>
		 </tr>
		 </thead>
		 <tbody>
            <tr>
			<td>1</td>
			<th>' . $booking_info->booking_date . '</th>
			<th>' . $booking_info->pod_no . '</th>
			<th>' . $booking_info->sender_name . '</th>
			<th>' . $booking_info->reciever_name . '</th>
			<th>' . $booking_info->reciever_pincode . '</th>
            </tr>
			</tbody>
			</table>
			';
			echo $option;
		}
	}
	public function add_shipment()
	{
		$all_Data = $this->input->post();
		if (!empty($all_Data)) {
			$user_id = $this->session->userdata("customer_id");
			$gat_area = $this->db->query("select cmp_area from tbl_franchise where fid = '$user_id'")->row();
			$area = $gat_area->cmp_area;
			$cutomer = $this->session->userdata("customer_name");
			$branch = $this->session->userdata("branch_name");
			if(!empty($area)){
				$branch_name = $branch . "_" . $area;
			}else
			{
				$branch_name = $branch;
			}
			if($_SESSION['franchise_type']== 1 || $_SESSION['franchise_type'] ==3){
                 if(!empty($all_Data['customer_id']) && $all_Data['company_customer']==1){
					$this->CustomerBNF_insert($all_Data);
					exit();
				 }
			}
		    // print_r($_POST);die;
		 
			$user_type = $this->session->userdata("customer_type");
			$balance = $this->db->query("Select * from tbl_customers where customer_id = '$user_id'")->row();
			$amount = $balance->wallet;
			$update_val = $amount - $this->input->post('grand_total');			
			if($this->input->post('dispatch_details')!='TOPAY'){
			if ($update_val < 0) {
				$msg = 'You Dont Have sufficient Balance!';
				$class = 'alert alert-danger alert-dismissible';
				$this->session->set_flashdata('notify', $msg);
				$this->session->set_flashdata('class', $class);
				redirect('franchise/shipment-list');
			}
			}
			if($this->input->post('doc_type')=='1'){
			if (empty($this->input->post('invoice_value'))) {
				$msg = 'Invoice Value Must be required';
				$class = 'alert alert-danger alert-dismissible';
				$this->session->set_flashdata('notify', $msg);
				$this->session->set_flashdata('class', $class);
				redirect('franchise/shipment-list');
			}
		   } 
			$date = date('Y-m-d', strtotime($this->input->post('booking_date')));
			$this->session->unset_userdata("booking_date");
			$this->session->set_userdata("booking_date", $this->input->post('booking_date'));
			$whr = array('customer_id' => $user_id);
			$res = $this->basic_operation_m->getAll('tbl_customers', $whr);
			$branch_id = $res->row()->branch_id;
			$awb = $this->input->post('awn');
			if ($all_Data['doc_type'] == 0) {
				$doc_nondoc = 'Document';
			} else {
				$doc_nondoc = 'Non Document';
			}
			if (empty(strtoupper($this->input->post('awn')))) {
				$customer_id = $_SESSION['customer_id'];
				$readonly = $this->db->query("SELECT * FROM tbl_branch_assign_cnode WHERE customer_id = '$customer_id'")->row();
				if (!empty($readonly)) {
					$msg = 'Booking Not Allowed AWB No. is not allocated range';
					$class = 'alert alert-danger alert-dismissible';
					$this->session->set_flashdata('notify', $msg);
					$this->session->set_flashdata('class', $class);
					redirect('franchise/shipment-list');
				} else {
					$result = $this->db->query('select max(booking_id) AS id from tbl_domestic_booking')->row();
					$id = $result->id + 1;
					//  print_r($id);exit;
					if (strlen($id) == 2) {
						$id = 'FBI1000' . $id;
					} elseif (strlen($id) == 3) {
						$id = 'FBI100' . $id;
					} elseif (strlen($id) == 1) {
						$id = 'FBI10000' . $id;
					} elseif (strlen($id) == 4) {
						$id = 'FBI10' . $id;
					} elseif (strlen($id) == 5) {
						$id = 'FBI1' . $id;
					}
					$pod_no = $id;
				}
			} else {
				$pod_no = strtoupper($this->input->post('awn'));
			}
			$data = array(
				'doc_type' => $this->input->post('doc_type'),
				'doc_nondoc' => $doc_nondoc,
				'courier_company_id' => $this->input->post('courier_company'),
				'company_type' => 'Domestic',
				'mode_dispatch' => $this->input->post('mode_dispatch'),
				'pod_no' => $pod_no,
				'forworder_name' => "SELF",
				'risk_type' => $this->input->post('risk_type'),
				//'customer_id' => $this->input->post('customer_account_id'),
				'customer_id' => $user_id,
				'sender_name' => $this->input->post('sender_name'),
				'sender_address' => $this->input->post('sender_address'),
				'sender_city' => $this->input->post('sender_city'),
				'sender_state' => $this->input->post('sender_state'),
				'sender_pincode' => $this->input->post('sender_pincode'),
				'sender_contactno' => $this->input->post('sender_contactno'),
				'sender_gstno' => $this->input->post('sender_gstno'),
				'reciever_name' => $this->input->post('reciever_name'),
				'contactperson_name' => $this->input->post('contactperson_name'),
				'reciever_address' => $this->input->post('reciever_address'),
				'reciever_contact' => $this->input->post('reciever_contact'),
				'reciever_pincode' => $this->input->post('reciever_pincode'),
				'reciever_city' => $this->input->post('reciever_city'),
				'reciever_state' => $this->input->post('reciever_state'),
				'receiver_zone' => $this->input->post('receiver_zone'),
				'receiver_zone_id' => $this->input->post('receiver_zone_id'),
				'receiver_gstno' => $this->input->post('receiver_gstno'),
				'invoice_no' => $this->input->post('invoice_no'),
				'invoice_value' => $this->input->post('invoice_value'),
				'eway_no' => $this->input->post('eway_no'),
				'eway_expiry_date' => $this->input->post('eway_expiry_date'),
				'special_instruction' => $this->input->post('special_instruction'),
				'booking_date' => $date,
				'booking_time' => date('H:i:s', strtotime($this->input->post('booking_date'))),
				'dispatch_details' => $this->input->post('dispatch_details'),
				// 'delivery_date' => $this->input->post('delivery_date'),
				'frieht' => EmptyVal($this->input->post('frieht')),
				'transportation_charges' => EmptyVal($this->input->post('transportation_charges')),
				'insurance_charges' => EmptyVal($this->input->post('insurance_charges')),
				'pickup_charges' => EmptyVal($this->input->post('pickup_charges')),
				'delivery_charges' => EmptyVal($this->input->post('delivery_charges')),
				'courier_charges' => EmptyVal($this->input->post('courier_charges')),
				'awb_charges' => EmptyVal($this->input->post('awb_charges')),
				'other_charges' => EmptyVal($this->input->post('other_charges')),
				'total_amount' => EmptyVal($this->input->post('amount')),
				'fuel_subcharges' => EmptyVal($this->input->post('fuel_subcharges')),
				'sub_total' => EmptyVal($this->input->post('sub_total')),
				'cgst' => EmptyVal($this->input->post('cgst')),
				'sgst' => EmptyVal($this->input->post('sgst')),
				'igst' => EmptyVal($this->input->post('igst')),
				'green_tax' => EmptyVal($this->input->post('green_tax')),
				'appt_charges' => EmptyVal($this->input->post('appt_charges')),
				'grand_total' => EmptyVal($this->input->post('grand_total')),
				'user_id' => $user_id,
				'user_type' => $user_type,
				'branch_id' => 0,
				'booking_type' => 1
			);
			//echo '<pre>'; print_r($data);exit;
			$this->db->trans_start();
			$result = $this->db->insert('tbl_domestic_booking', $data);
            // echo $this->db->last_query();die;
			$all_Data = $this->input->post();


			$lastid = $this->db->insert_id();
			if (empty($lastid)) {

				$data['error'][] = "Already Exist " . $this->input->post('awn') . '<br>';
			} else {
				$lastid = $this->db->insert_id();
				// echo "<pre>";
				$weight_data = array(
					'per_box_weight_detail' => $all_Data['per_box_weight_detail'],
					'length_detail' => $all_Data['length_detail'],
					'breath_detail' => $all_Data['breath_detail'],
					'height_detail' => $all_Data['height_detail'],
					'valumetric_weight_detail' => $all_Data['valumetric_weight_detail'],
					'valumetric_actual_detail' => $all_Data['valumetric_actual_detail'],
					'valumetric_chageable_detail' => $all_Data['valumetric_chageable_detail'],
					'per_box_weight' => $all_Data['per_box_weight'],
					'length' => $all_Data['length'],
					'breath' => $all_Data['breath'],
					'height' => $all_Data['height'],
					'valumetric_weight' => $all_Data['valumetric_weight'],
					'valumetric_actual' => $all_Data['valumetric_actual'],
					'valumetric_chageable' => $all_Data['valumetric_chageable'],
				);
				$weight_details = json_encode($weight_data);
				$data2 = array(
					'booking_id' => $lastid,
					'actual_weight' => $this->input->post('actual_weight'),
					'valumetric_weight' => $this->input->post('valumetric_weight'),
					'length' => $this->input->post('length'),
					'breath' => $this->input->post('breath'),
					'height' => $this->input->post('height'),
					'chargable_weight' => $this->input->post('chargable_weight'),
					'per_box_weight' => $this->input->post('per_box_weight'),
					'no_of_pack' => $this->input->post('no_of_pack'),
					'actual_weight_detail' => json_encode($this->input->post('actual_weight')),
					'valumetric_weight_detail' => json_encode($this->input->post('valumetric_weight_detail[]')),
					'chargable_weight_detail' => json_encode($this->input->post('chargable_weight')),
					'length_detail' => json_encode($this->input->post('length_detail[]')),
					'breath_detail' => json_encode($this->input->post('breath_detail[]')),
					'height_detail' => json_encode($this->input->post('height_detail[]')),
					'no_pack_detail' => json_encode($this->input->post('no_of_pack')),
					'per_box_weight_detail' => json_encode($this->input->post('per_box_weight_detail[]')),
					'weight_details' => $weight_details,
				);
				$query2 = $this->basic_operation_m->insert('tbl_domestic_weight_details', $data2);
				// echo $this->db->last_query();exit;
				$username = $this->session->userdata("customer_id");		
				$whr = array('booking_id' => $lastid);
				$res = $this->basic_operation_m->getAll('tbl_domestic_booking', $whr);
				$podno = $res->row()->pod_no;
				$customerid = $res->row()->customer_id;
				$data3 = array(
					'id' => '',
					'pod_no' => $pod_no,
					'status' => 'Booked',
					'branch_name' => $branch_name,
					'tracking_date' => $this->input->post('booking_date'),
					'remarks' => $this->input->post('special_instruction'),
					'booking_id' => $lastid,
					'forworder_name' => $data['forworder_name'],
					'forwording_no' => $data['forwording_no'],
					'is_spoton' => ($data['forworder_name'] == 'spoton_service') ? 1 : 0,
					'is_delhivery_b2b' => ($data['forworder_name'] == 'delhivery_b2b') ? 1 : 0,
					'is_delhivery_c2c' => ($data['forworder_name'] == 'delhivery_c2c') ? 1 : 0
				);
				$result3 = $this->basic_operation_m->insert('tbl_domestic_tracking', $data3);
				// echo $this->db->last_query();die;
				if ($this->input->post('customer_account_id') != "") {
					$whr = array('customer_id' => $customerid);
					$res = $this->basic_operation_m->getAll('tbl_customers', $whr);
					$email = $res->row()->email;
				}
			}
			if (!empty($result)) {
				$query = "SELECT MAX(topup_balance_id) as id FROM franchise_topup_balance_tbl ";
				$result1 = $this->basic_operation_m->get_query_row($query);
				$id = $result1->id + 1;
				//print_r($id); exit;

				$franchise_id1 = $balance->franchise_id;
				$payment_mode = 'Debit';
				$bank_name = 'Current';

				if (strlen($id) == 1) {
					$franchise_id = 'BFT100000' . $id;
				} elseif (strlen($id) == 2) {
					$franchise_id = 'BFT10000' . $id;
				} elseif (strlen($id) == 3) {
					$franchise_id = 'BFT1000' . $id;
				} elseif (strlen($id) == 4) {
					$franchise_id = 'BFT100' . $id;
				} elseif (strlen($id) == 5) {
					$franchise_id = 'BFT1000' . $id;
				}
 
				if($this->input->post('dispatch_details')!='TOPAY'){
					if ($this->input->post('grand_total') != '') {
						//$value = $_SESSION['customer_id'];
						$value = $this->session->userdata('customer_id');
						$g_total = $this->input->post('grand_total');
						$balance = $this->db->query("Select * from tbl_customers where customer_id = '$value'")->row();
						$amount = $balance->wallet;
						$update_val = $amount - $this->input->post('grand_total');
						$whr5 = array('customer_id' => $_SESSION['customer_id']);
						$data1 = array('wallet' => $update_val);
						$result = $this->basic_operation_m->update('tbl_customers', $data1, $whr5);
						$franchise_id1 = $balance->cid;
							$data9 = array(
							'franchise_id' => $franchise_id1,
							'customer_id' => $user_id,
							'transaction_id' => $franchise_id,
							'payment_date' => $date,
							'debit_amount' => $g_total,
							'balance_amount' => $update_val,
							'payment_mode' => $payment_mode,
							'bank_name' => $bank_name,
							'status' => 1,
							'refrence_no' => $pod_no
						);
						$result = $this->db->insert('franchise_topup_balance_tbl', $data9);
					}
			   }else{
					$value = $this->session->userdata('customer_id');
					$g_total = $this->input->post('grand_total');
					$balance = $this->db->query("Select * from tbl_customers where customer_id = '$value'")->row('wallet');
					$ShipmentDeducted = $balance - $g_total;
					if($ShipmentDeducted < -1001){
						$msg = "You Dont Have sufficient Balance!";
						$class = 'alert alert-danger alert-dismissible';  
						$this->session->set_flashdata('notify', $msg);
						$this->session->set_flashdata('class', $class);
						redirect('franchise/shipment-list');
					}
			   }
			}
			$this->db->trans_complete();
			if ($this->db->trans_status() === TRUE)
			{
				$this->db->trans_commit();
				$msg = 'Your Shipment ' . $podno . ' status:Boked  At Location: ' . $branch_name;
				$class = 'alert alert-success alert-dismissible';
			}
			else
			{
				$this->db->trans_rollback();	
				$msg = 'Shipment not added successfully';
				$class = 'alert alert-danger alert-dismissible';
			}
			$this->session->set_flashdata('notify', $msg);
			$this->session->set_flashdata('class', $class);
			redirect('franchise/shipment-list');
		} else {
			// Add shipment case 
			$data = array();
			$data['transfer_mode'] = $this->basic_operation_m->get_query_result('select * from `transfer_mode`'); // mode
			$customer_id = $this->session->userdata("customer_id");
			$data['cities'] = $this->basic_operation_m->get_all_result('city', '');
			$data['states'] = $this->basic_operation_m->get_all_result('state', '');
			$data['franchise'] = $this->basic_operation_m->get_all_result('tbl_customers', "customer_id = '$customer_id'");
			$data['customers'] = $this->db->query("SELECT * FROM tbl_customers WHERE franchise_id = '$customer_id' AND (customer_type !='1' OR customer_type !='2') ")->result_array();
			$this->load->view('franchise/booking_master/add_shipment', $data);
		}
	}
	public function CustomerBNF_insert($all_Data)
	{
		if (!empty($all_Data)) {			
			$user_id = $this->session->userdata("customer_id");
			$gat_area = $this->db->query("select cmp_area from tbl_franchise where fid = '$user_id'")->row();
			$area = $gat_area->cmp_area;
			$cutomer = $this->session->userdata("customer_name");
			$branch = $this->session->userdata("branch_name");
			if(!empty($area)){
				$branch_name = $branch . "_" . $area;
			}else
			{
				$branch_name = $branch;
			}         
			$user_type = $this->session->userdata("customer_type");
			$balance = $this->db->query("Select * from tbl_franchise where fid = '$user_id'")->row();
			$credit_limit = $balance->credit_limit;
			$amount = $balance->credit_limit_utilize;
			$update_val = $amount + $this->input->post('grand_total1');
			if($this->input->post('dispatch_details')!='TOPAY'){
			if ($credit_limit < $update_val) {
				$msg = 'You Dont Have sufficient Credit Limit Balance!';
				$class = 'alert alert-danger alert-dismissible';
				$this->session->set_flashdata('notify', $msg);
				$this->session->set_flashdata('class', $class);
				redirect('franchise/shipment-list');
			}
			}
			if($this->input->post('doc_type')=='1'){
			if (empty($this->input->post('invoice_value'))) {
				$msg = 'Invoice Value Must be required';
				$class = 'alert alert-danger alert-dismissible';
				$this->session->set_flashdata('notify', $msg);
				$this->session->set_flashdata('class', $class);
				redirect('franchise/shipment-list');
			}
		   } 
			$date = date('Y-m-d', strtotime($this->input->post('booking_date')));
			$this->session->unset_userdata("booking_date");
			$this->session->set_userdata("booking_date", $this->input->post('booking_date'));
			$whr = array('customer_id' => $user_id);
			$res = $this->basic_operation_m->getAll('tbl_customers', $whr);
			$branch_id = $res->row()->branch_id;
			$awb = $this->input->post('awn');
			if ($all_Data['doc_type'] == 0) {
				$doc_nondoc = 'Document';
			} else {
				$doc_nondoc = 'Non Document';
			}
	
			if (empty(strtoupper($this->input->post('awn')))) {
				$customer_id = $_SESSION['customer_id'];
				$readonly = $this->db->query("SELECT * FROM tbl_branch_assign_cnode WHERE customer_id = '$customer_id'")->row();
				if (!empty($readonly)) {
					$msg = 'Booking Not Allowed AWB No. is not allocated range';
					$class = 'alert alert-danger alert-dismissible';
					$this->session->set_flashdata('notify', $msg);
					$this->session->set_flashdata('class', $class);
					redirect('franchise/shipment-list');
				} else {
					$result = $this->db->query('select max(booking_id) AS id from tbl_domestic_booking')->row();
					$id = $result->id + 1;
					//  print_r($id);exit;
					if (strlen($id) == 2) {
						$id = 'FBI1000' . $id;
					} elseif (strlen($id) == 3) {
						$id = 'FBI100' . $id;
					} elseif (strlen($id) == 1) {
						$id = 'FBI10000' . $id;
					} elseif (strlen($id) == 4) {
						$id = 'FBI10' . $id;
					} elseif (strlen($id) == 5) {
						$id = 'FBI1' . $id;
					}
					$pod_no = $id;
				}
			} else {
				$pod_no = strtoupper($this->input->post('awn'));
			}

			// echo '<pre>';print_r($_POST);die;
			$data = array(
				'doc_type' => $this->input->post('doc_type'),
				'doc_nondoc' => $doc_nondoc,
				'courier_company_id' => $this->input->post('courier_company'),
				'company_type' => 'Domestic',
				'mode_dispatch' => $this->input->post('mode_dispatch'),
				'pod_no' => $pod_no,
				'forworder_name' => "SELF",
				'risk_type' => $this->input->post('risk_type'),
				'bnf_customer_id' => EmptyVal($this->input->post('customer_id')),
				'customer_id' => $user_id,
				'sender_name' => $this->input->post('sender_name'),
				'sender_address' => $this->input->post('sender_address'),
				'sender_city' => $this->input->post('sender_city'),
				'sender_state' => $this->input->post('sender_state'),
				'sender_pincode' => $this->input->post('sender_pincode'),
				'sender_contactno' => $this->input->post('sender_contactno'),
				'sender_gstno' => $this->input->post('sender_gstno'),
				'reciever_name' => $this->input->post('reciever_name'),
				'contactperson_name' => $this->input->post('contactperson_name'),
				'reciever_address' => $this->input->post('reciever_address'),
				'reciever_contact' => $this->input->post('reciever_contact'),
				'reciever_pincode' => $this->input->post('reciever_pincode'),
				'reciever_city' => $this->input->post('reciever_city'),
				'reciever_state' => $this->input->post('reciever_state'),
				'receiver_zone' => $this->input->post('receiver_zone'),
				'receiver_zone_id' => $this->input->post('receiver_zone_id'),
				'receiver_gstno' => $this->input->post('receiver_gstno'),
				'is_appointment' => EmptyVal($this->input->post('is_appointment')),
				'invoice_no' => $this->input->post('invoice_no'),
				'invoice_value' => $this->input->post('invoice_value'),
				'eway_no' => $this->input->post('eway_no'),
				'eway_expiry_date' => $this->input->post('eway_expiry_date'),
				'special_instruction' => $this->input->post('special_instruction'),
				'booking_date' => $date,
				'booking_time' => date('H:i:s', strtotime($this->input->post('booking_date'))),
				'dispatch_details' => $this->input->post('dispatch_details'),
				'rate' => EmptyVal($this->input->post('rate')),
				'frieht' => EmptyVal($this->input->post('frieht')),
				'transportation_charges' => EmptyVal($this->input->post('transportation_charges1')),
				'insurance_charges' => EmptyVal($this->input->post('insurance_charges1')),
				'pickup_charges' => EmptyVal($this->input->post('pickup_charges1')),
				'delivery_charges' => EmptyVal($this->input->post('delivery_charges1')),
				'courier_charges' => EmptyVal($this->input->post('courier_charges1')),
				'awb_charges' => EmptyVal($this->input->post('awb_charges1')),
				'other_charges' => EmptyVal($this->input->post('other_charges1')),
				'total_amount' => EmptyVal($this->input->post('amount1')),
				'fuel_subcharges' => EmptyVal($this->input->post('fuel_charges1')),
				'fov_charges' => EmptyVal($this->input->post('fov_charges1')),
				'sub_total' => EmptyVal($this->input->post('sub_total1')),
				'cgst' => EmptyVal($this->input->post('cgst1')),
				'sgst' => EmptyVal($this->input->post('sgst1')),
				'igst' => EmptyVal($this->input->post('igst1')),
				'green_tax' => EmptyVal($this->input->post('topay1')),
				'appt_charges' => EmptyVal($this->input->post('appt_charges1')),
				'grand_total' => EmptyVal($this->input->post('grand_total1')),
				'user_id' => $user_id,
				'user_type' => $user_type,
				'branch_id' => 0,
				'booking_type' => 1
			);
			// echo '<pre>'; print_r($_POST);
			// echo '<pre>'; print_r($data);exit;
			$this->db->trans_start();
			$result = $this->db->insert('tbl_domestic_booking', $data);
          
			$all_Data = $this->input->post();
			$lastid = $this->db->insert_id();
			if (empty($lastid)) {

				$data['error'][] = "Already Exist " . $this->input->post('awn') . '<br>';
			} else {
				$lastid = $this->db->insert_id();
				// echo "<pre>";
				$total_charges = EmptyVal($this->input->post('booking_charges')) + EmptyVal($this->input->post('delivery_c_charges')) + EmptyVal($this->input->post('door_delivery_charges'));
				$commision = [
					'booking_id'=>$lastid,
					'franchise_id'=>$_SESSION['customer_id'],
					'customer_id'=>EmptyVal($this->input->post('customer_id')),
					'pod_no'=>$pod_no,
					'booking_commision'=>EmptyVal($this->input->post('booking_comission')),
					'delivery_commision'=>EmptyVal($this->input->post('delivery_commission')),
					'door_delivery_share'=>EmptyVal($this->input->post('door_delivery_share')),
					'booking_commision_charges'=>EmptyVal($this->input->post('booking_charges')),
					'delivery_commision_charges'=>EmptyVal($this->input->post('delivery_c_charges')),
					'door_delivery_charges'=>EmptyVal($this->input->post('door_delivery_charges')),
					'total_charges'=>$total_charges,
					'booking_date'=>$date
				];
				if(!empty($this->input->post('booking_charges'))){
					$commision['booking_commision_access']= 1;
				}
				if(!empty($this->input->post('delivery_c_charges'))){
					$commision['delivery_commision_access']= 1;
				}
				if(!empty($this->input->post('door_delivery_charges'))){
					$commision['door_delivery_access']= 1;
				}	
				if($this->input->post('dispatch_details')=='TOPAY'){
					$commision['booking_type']= 1;
				}			
				$this->basic_operation_m->insert('tbl_franchise_comission', $commision);
				
				$weight_data = array(
					'per_box_weight_detail' => $all_Data['per_box_weight_detail'],
					'length_detail' => $all_Data['length_detail'],
					'breath_detail' => $all_Data['breath_detail'],
					'height_detail' => $all_Data['height_detail'],
					'valumetric_weight_detail' => $all_Data['valumetric_weight_detail'],
					'valumetric_actual_detail' => $all_Data['valumetric_actual_detail'],
					'valumetric_chageable_detail' => $all_Data['valumetric_chageable_detail'],
					'per_box_weight' => $all_Data['per_box_weight'],
					'length' => $all_Data['length'],
					'breath' => $all_Data['breath'],
					'height' => $all_Data['height'],
					'valumetric_weight' => $all_Data['valumetric_weight'],
					'valumetric_actual' => $all_Data['valumetric_actual'],
					'valumetric_chageable' => $all_Data['valumetric_chageable'],
				);
				$weight_details = json_encode($weight_data);
				$data2 = array(
					'booking_id' => $lastid,
					'actual_weight' => $this->input->post('actual_weight'),
					'valumetric_weight' => $this->input->post('valumetric_weight'),
					'length' => $this->input->post('length'),
					'breath' => $this->input->post('breath'),
					'height' => $this->input->post('height'),
					'chargable_weight' => $this->input->post('chargable_weight'),
					'per_box_weight' => $this->input->post('per_box_weight'),
					'no_of_pack' => $this->input->post('no_of_pack'),
					'actual_weight_detail' => json_encode($this->input->post('actual_weight')),
					'valumetric_weight_detail' => json_encode($this->input->post('valumetric_weight_detail[]')),
					'chargable_weight_detail' => json_encode($this->input->post('chargable_weight')),
					'length_detail' => json_encode($this->input->post('length_detail[]')),
					'breath_detail' => json_encode($this->input->post('breath_detail[]')),
					'height_detail' => json_encode($this->input->post('height_detail[]')),
					'no_pack_detail' => json_encode($this->input->post('no_of_pack')),
					'per_box_weight_detail' => json_encode($this->input->post('per_box_weight_detail[]')),
					'weight_details' => $weight_details,
				);
				$query2 = $this->basic_operation_m->insert('tbl_domestic_weight_details', $data2);
				// echo $this->db->last_query();exit;
			
				$username = $this->session->userdata("customer_id");		
				$whr = array('booking_id' => $lastid);
				$res = $this->basic_operation_m->getAll('tbl_domestic_booking', $whr);
				$podno = $res->row()->pod_no;
				$customerid = $res->row()->customer_id;
				$data3 = array(
					'pod_no' => $pod_no,
					'status' => 'Booked',
					'branch_name' => $branch_name,
					'tracking_date' => $this->input->post('booking_date'),
					'remarks' => $this->input->post('special_instruction'),
					'booking_id' => $lastid,
					'is_spoton' => ($data['forworder_name'] == 'spoton_service') ? 1 : 0,
					'is_delhivery_b2b' => ($data['forworder_name'] == 'delhivery_b2b') ? 1 : 0,
					'is_delhivery_c2c' => ($data['forworder_name'] == 'delhivery_c2c') ? 1 : 0
				);
				$result3 = $this->basic_operation_m->insert('tbl_domestic_tracking', $data3);
				// echo $this->db->last_query();die;
				
				if ($this->input->post('customer_account_id') != "") {
					$whr = array('customer_id' => $customerid);
					$res = $this->basic_operation_m->getAll('tbl_customers', $whr);
					$email = $res->row()->email;
				}
			}
			if (!empty($result)) {
			    $query = "SELECT MAX(topup_balance_id) as id FROM franchise_topup_balance_tbl ";
				$result1 = $this->basic_operation_m->get_query_row($query);
				$id = $result1->id + 1;
				//print_r($id); exit;

				$franchise_id1 = $balance->franchise_id;
				$payment_mode = 'Debit';
				$bank_name = 'Current';

				if (strlen($id) == 1) {
					$franchise_id = 'BFT100000' . $id;
				} elseif (strlen($id) == 2) {
					$franchise_id = 'BFT10000' . $id;
				} elseif (strlen($id) == 3) {
					$franchise_id = 'BFT1000' . $id;
				} elseif (strlen($id) == 4) {
					$franchise_id = 'BFT100' . $id;
				} elseif (strlen($id) == 5) {
					$franchise_id = 'BFT1000' . $id;
				}
				if($this->input->post('dispatch_details')!='TOPAY'){
					if ($this->input->post('grand_total1') != '') {
						//$value = $_SESSION['customer_id'];
						$value = $this->session->userdata('customer_id');
						$g_total = $this->input->post('grand_total1');
						$balance = $this->db->query("Select * from tbl_franchise where fid = '$value'")->row();
						$cust = $this->db->query("Select * from tbl_customers where customer_id = '$value'")->row();
						$amount = $balance->credit_limit_utilize;
						$update_val = $amount + $this->input->post('grand_total1');
						$whr5 = array('fid' => $_SESSION['customer_id']);
						$data1 = array('credit_limit_utilize' => $update_val);
						$result = $this->basic_operation_m->update('tbl_franchise', $data1, $whr5);
					
						$franchise_id1 = $cust->cid;
							$data9 = array(
							'franchise_id' => $franchise_id1,
							'customer_id' => $user_id,
							'transaction_id' => $franchise_id,
							'payment_date' => $date,
							'debit_amount' => $g_total,
							'balance_amount' => $update_val,
							'payment_mode' => $payment_mode,
							'bank_name' => $bank_name,
							'status' => 1,
							'franchise_type' =>$_SESSION['franchise_type'] ,
							'refrence_no' => $pod_no
						);
						$result = $this->db->insert('franchise_topup_balance_tbl', $data9);
						// echo $this->db->last_query();die;
					}
			    }else{
					$value = $this->session->userdata('customer_id');
					$g_total = $this->input->post('grand_total1');
					$balance = $this->db->query("Select * from tbl_franchise where fid = '$value'")->row('credit_limit_utilize');
					$ShipmentDeducted = $balance + $g_total;
					if($ShipmentDeducted < -1001){
						$msg = "You Dont Have sufficient Balance!";
						$class = 'alert alert-danger alert-dismissible';  
						$this->session->set_flashdata('notify', $msg);
						$this->session->set_flashdata('class', $class);
						redirect('franchise/shipment-list');
					}
			   }
			}
			$this->db->trans_complete();
			if ($this->db->trans_status() === TRUE)
			{
				$this->db->trans_commit();
				$msg = 'Your Shipment ' . $podno . ' status:Boked  At Location: ' . $branch_name;
				$class = 'alert alert-success alert-dismissible';
			}
			else
			{
				$this->db->trans_rollback();	
				$msg = 'Shipment not added successfully';
				$class = 'alert alert-danger alert-dismissible';
			}
			$this->session->set_flashdata('notify', $msg);
			$this->session->set_flashdata('class', $class);
			redirect('franchise/shipment-list');
		} else {
			$msg = 'Shipment not added successfully';
			$class = 'alert alert-danger alert-dismissible';
			$this->session->set_flashdata('notify', $msg);
			$this->session->set_flashdata('class', $class);
			redirect('franchise/shipment-list');
		}
	}

	public function edit_shipment($id = 0)
	{

		$whr = array('booking_id' => $id);
		if ($id != "") {
			$data['booking'] = $this->basic_operation_m->get_table_row('tbl_domestic_booking', $whr);

			$data['weight'] = $this->basic_operation_m->get_table_row('tbl_domestic_weight_details', $whr);

		}
		// echo '<pre>';print_r($data);die;
		$data['transfer_mode'] = $this->basic_operation_m->get_query_result('select * from `transfer_mode`');

		$customer_id = $this->session->userdata("customer_id");
		$data['cities'] = $this->basic_operation_m->get_all_result('city', '');
		$data['states'] = $this->basic_operation_m->get_all_result('state', '');

		$data['customers'] = $this->basic_operation_m->get_all_result('tbl_customers', "parent_cust_id = '$customer_id'");

		$data['payment_method'] = $this->basic_operation_m->get_all_result('payment_method', '');
		$data['region_master'] = $this->basic_operation_m->get_all_result('region_master', '');
		$data['bid'] = $id;
		$whr_d = array("company_type" => "Domestic");
		$data['courier_company'] = $this->basic_operation_m->get_all_result("courier_company", $whr_d);
		$data['bid'] = $id;
		$this->load->view('franchise/booking_master/edit_shipment', $data);
	}

	public function update_shipment($id)
	{
		$all_data = $this->input->post();
		$all_data2 = $this->input->post();


		if (!empty($all_data)) {
			$whr = array('booking_id' => $id);
			$date = date('Y-m-d', strtotime($this->input->post('booking_date')));
			//booking details//

			if ($this->input->post('doc_type') == 0) {
				$doc_nondoc = 'Document';
			} else {
				$doc_nondoc = 'Non Document';
			}

			$username = $this->session->userdata("userName");
			$user_id = $this->session->userdata("userId");
			$user_type = $this->session->userdata("userType");
			$whr_u = array('username' => $username);
			$res = $this->basic_operation_m->getAll('tbl_users', $whr_u);
			$branch_id = $res->row()->branch_id;

			$date = date('Y-m-d', strtotime($this->input->post('booking_date')));

			$reciever_pincode = $this->input->post('reciever_pincode');
			$reciever_city = $this->input->post('reciever_city');
			$reciever_state = $this->input->post('reciever_state');

			$whr_pincode = array('pin_code' => $reciever_pincode, 'city_id' => $reciever_city, 'state_id' => $reciever_state,'isdeleted'=>0);
			$check_city = $this->basic_operation_m->get_table_row('pincode', $whr_pincode);
			//echo "++++".$this->db->last_query();
			if (empty($check_city) && !empty($reciever_city)) {
				$whr_C = array('id' => $reciever_city);
				$city_details = $this->basic_operation_m->get_table_row('city', $whr_C);
				$whr_S = array('id' => $reciever_state);
				$state_details = $this->basic_operation_m->get_table_row('state', $whr_S);
				// print_r($this->input->post('reciever_city')); die;

				$pincode_data = array(
					'pin_code' => $reciever_pincode,
					'city' => $city_details->city,
					'city_id' => $reciever_city,
					'state' => $state_details->state,
					'state_id' => $reciever_state
				);

				$whr_p = array('pin_code' => $reciever_pincode);
				$qry = $this->basic_operation_m->update('pincode', $pincode_data, $whr_p);
			}
			$is_appointment = ($this->input->post('is_appointment') == 'ON') ? 1 : 0;


			$data = array(
				'doc_type' => $this->input->post('doc_type'),
				'doc_nondoc' => $doc_nondoc,
				'courier_company_id' => $this->input->post('courier_company'),
				'company_type' => 'Domestic',
				'mode_dispatch' => $this->input->post('mode_dispatch'),
				'pod_no' => $this->input->post('awn'),
				'forworder_name' => "SELF",
				'risk_type' => $this->input->post('risk_type'),
				// 'customer_id' => $this->input->post('customer_account_id'),
				// 'customer_id' => $user_id,
				'sender_name' => $this->input->post('sender_name'),
				'sender_address' => $this->input->post('sender_address'),
				// 'sender_city' => $this->input->post('sender_city'),
				// 'sender_state' => $this->input->post('sender_state'),
				'sender_pincode' => $this->input->post('sender_pincode'),
				'sender_contactno' => $this->input->post('sender_contactno'),
				'sender_gstno' => $this->input->post('sender_gstno'),
				'reciever_name' => $this->input->post('reciever_name'),
				'contactperson_name' => $this->input->post('contactperson_name'),
				'reciever_address' => $this->input->post('reciever_address'),
				'reciever_contact' => $this->input->post('reciever_contact'),
				'reciever_pincode' => $this->input->post('reciever_pincode'),
				// 'reciever_city' => $this->input->post('reciever_city'),
				// 'reciever_state' => $this->input->post('reciever_state'),
				'receiver_zone' => $this->input->post('receiver_zone'),
				'receiver_zone_id' => $this->input->post('receiver_zone_id'),
				'receiver_gstno' => $this->input->post('receiver_gstno'),
				'ref_no' => $this->input->post('ref_no'),
				'invoice_no' => $this->input->post('invoice_no'),
				'invoice_value' => $this->input->post('invoice_value'),
				'eway_no' => $this->input->post('eway_no'),
				'eway_expiry_date' => $this->input->post('eway_expiry_date'),
				'special_instruction' => $this->input->post('special_instruction'),
				'booking_date' => $date,
				'booking_time' => date('H:i:s', strtotime($this->input->post('booking_date'))),
				'dispatch_details' => $this->input->post('dispatch_details'),
				// 'delivery_date' => $this->input->post('delivery_date'),
				'payment_method' => $this->input->post('payment_method'),
				'frieht' => $this->input->post('frieht'),
				'transportation_charges' => $this->input->post('transportation_charges'),
				'insurance_charges' => $this->input->post('insurance_charges'),
				'pickup_charges' => $this->input->post('pickup_charges'),
				'delivery_charges' => $this->input->post('delivery_charges'),
				'courier_charges' => $this->input->post('courier_charges'),
				'awb_charges' => $this->input->post('awb_charges'),
				'other_charges' => $this->input->post('other_charges'),
				'total_amount' => $this->input->post('amount'),
				'fuel_subcharges' => $this->input->post('fuel_subcharges'),
				'sub_total' => $this->input->post('sub_total'),
				'cgst' => $this->input->post('cgst'),
				'sgst' => $this->input->post('sgst'),
				'igst' => $this->input->post('igst'),
				'green_tax' => $this->input->post('green_tax'),
				'appt_charges' => $this->input->post('appt_charges'),
				'grand_total' => $this->input->post('grand_total')
				// 'user_id' => $user_id,
				// 'user_type' => $user_type,
				// 'branch_id' => 0,
				// 'booking_type' => 1
			);
			// echo '<pre>';print_r($data);die;
			$query = $this->basic_operation_m->update('tbl_domestic_booking', $data, $whr);


			$weight_data = array(
				'per_box_weight_detail' => $all_data2['per_box_weight_detail'],
				'length_detail' => $all_data2['length_detail'],
				'breath_detail' => $all_data2['breath_detail'],
				'height_detail' => $all_data2['height_detail'],
				'valumetric_weight_detail' => $all_data2['valumetric_weight_detail'],
				'valumetric_actual_detail' => $all_data2['valumetric_actual_detail'],
				'valumetric_chageable_detail' => $all_data2['valumetric_chageable_detail'],
				'per_box_weight' => $all_data2['per_box_weight'],
				'length' => $all_data2['length'],
				'breath' => $all_data2['breath'],
				'height' => $all_data2['height'],
				'valumetric_weight' => $all_data2['valumetric_weight'],
				'valumetric_actual' => $all_data2['valumetric_actual'],
				'valumetric_chageable' => $all_data2['valumetric_chageable'],
			);

			$weight_details = json_encode($weight_data);

			$data2 = array(
				'actual_weight' => $this->input->post('actual_weight'),
				'valumetric_weight' => $this->input->post('valumetric_weight'),
				'length' => $this->input->post('length'),
				'breath' => $this->input->post('breath'),
				'height' => $this->input->post('height'),
				'chargable_weight' => $this->input->post('chargable_weight'),
				'per_box_weight' => $this->input->post('per_box_weight'),
				'no_of_pack' => $this->input->post('no_of_pack'),
				'actual_weight_detail' => json_encode($this->input->post('actual_weight')),
				'valumetric_weight_detail' => json_encode($this->input->post('valumetric_weight_detail[]')),
				'chargable_weight_detail' => json_encode($this->input->post('chargable_weight')),
				'length_detail' => json_encode($this->input->post('length_detail[]')),
				'breath_detail' => json_encode($this->input->post('breath_detail[]')),
				'height_detail' => json_encode($this->input->post('height_detail[]')),
				'no_pack_detail' => json_encode($this->input->post('no_of_pack')),
				'per_box_weight_detail' => json_encode($this->input->post('per_box_weight_detail[]')),
				'weight_details' => $weight_details,
			);
			// echo '<pre>'; print_r($data2);
			$query2 = $this->basic_operation_m->update('tbl_domestic_weight_details', $data2, $whr);



			$username = $this->session->userdata("userName");
			$whr = array('username' => $username);
			$res = $this->basic_operation_m->getAll('tbl_users', $whr);
			$branch_id = $res->row()->branch_id;

			$whr = array('branch_id' => $branch_id);
			$res = $this->basic_operation_m->getAll('tbl_branch', $whr);
			$branch_name = $res->row()->branch_name;

			$whr = array('booking_id' => $id);
			$res = $this->basic_operation_m->getAll('tbl_domestic_booking', $whr);
			$podno = $res->row()->pod_no;
			$customerid = $res->row()->customer_id;
			$data3 = array(
				'tracking_date' => date('Y-m-d H:i:s', strtotime($this->input->post('booking_date')))
			);

			// echo '<pre>';print_r($data3);die;
			$where2 = array('status' => 'Booked', 'pod_no' => $this->input->post('awn'));
			$query2 = $this->basic_operation_m->update('tbl_domestic_tracking', $data3, $where2);

			if ($this->db->affected_rows() > 0) {
				$data['message'] = "Shipment Updated successfull";
			} else {
				$data['message'] = "Failed to Submit";
			}

			redirect('franchise/shipment-list');
		}
	}


	public function cancel_shipment()
	{
		$booking_id = $this->input->post('booking_id');
		$booking = $this->db->query("select * from tbl_domestic_booking where booking_id = '$booking_id'")->row();
		$cancel_msg = $this->input->post('cancel_msg');
		$user_id = $this->session->userdata("customer_id");
		$gat_area = $this->db->query("select cmp_area from tbl_franchise where fid = '$user_id'")->row();
		$area = $gat_area->cmp_area;
		$cutomer = $this->session->userdata("customer_name");
		$branch = $this->session->userdata("branch_name");

		// $branch_name = $branch . " " .$area. "Franchise";
		$branch_name = $branch . "_" . $area;
		$data = array(

			'booking_type' => 0,
			'pickup_in_scan' => 2,
			'branch_in_scan' => 2,
			'booking_cancel_reason' => $cancel_msg
		);

		$this->db->where('booking_id', $booking_id);
		$query = $this->db->update('tbl_domestic_booking', $data);
		// echo $this->db->last_query();die;
		$query = "SELECT MAX(topup_balance_id) as id FROM franchise_topup_balance_tbl ";
		$result1 = $this->basic_operation_m->get_query_row($query);
		$id = $result1->id + 1;
		$balance = $this->db->query("Select * from tbl_customers where customer_id = '$user_id'")->row();
		// echo $this->db->last_query();die;
		$amount = $balance->wallet;
		$update_val = $amount + $booking->grand_total;
		$franchise_id1 = $balance->cid;
		$payment_mode = 'Credit';
		$bank_name = 'Current';
		$date = date('Y-m-d');
		if (strlen($id) == 1) {
			$franchise_id = 'BFT100000' . $id;
		} elseif (strlen($id) == 2) {
			$franchise_id = 'BFT10000' . $id;
		} elseif (strlen($id) == 3) {
			$franchise_id = 'BFT1000' . $id;
		} elseif (strlen($id) == 4) {
			$franchise_id = 'BFT100' . $id;
		} elseif (strlen($id) == 5) {
			$franchise_id = 'BFT1000' . $id;
		}

		$data9 = array(

			'franchise_id' => $franchise_id1,
			'customer_id' => $user_id,
			'transaction_id' => $franchise_id,
			'payment_date' => $date,
			'credit_amount' => $booking->grand_total,
			'balance_amount' => $update_val,
			'payment_mode' => $payment_mode,
			'bank_name' => $bank_name,
			'status' => 1,
			'refrence_no' => $booking->pod_no
		);

		$whr5 = array('customer_id' => $_SESSION['customer_id']);
		$data1 = array('wallet' => $update_val);
		$result = $this->basic_operation_m->update('tbl_customers', $data1, $whr5);
		// echo '<pre>'; print_r($data9);exit;

		$result = $this->db->insert('franchise_topup_balance_tbl', $data9);

		date_default_timezone_set('Asia/Kolkata');
		$datetime = date('Y-m-d H:i:s');
		$data3 = array(
			'id' => '',
			'pod_no' => $booking->pod_no,
			'status' => 'Shipment Cancel',
			'branch_name' => $branch_name,
			'tracking_date' => $datetime,
			'comment' => $cancel_msg,
			'booking_id' => $booking_id
		);
		// print_r($data3);
		// exit;

		$result3 = $this->basic_operation_m->insert('tbl_domestic_tracking', $data3);




		if (!empty($query)) {
			$output['status'] = 'success';
			$output['message'] = 'Shipment Cancel successfully';
		} else {
			$output['status'] = 'error';
			$output['message'] = 'Something went wrong in Cancel the Data';
		}

		echo json_encode($output);
	}



	public function cancel_shipment_list()
	{
		$user_id = $this->session->userdata("customer_id");
		$data['shipment_list'] = $this->db->query("SELECT tbl_domestic_booking.*,transfer_mode.mode_name as mode_dispatch  FROM `tbl_domestic_booking`LEFT JOIN transfer_mode ON tbl_domestic_booking.mode_dispatch =transfer_mode.transfer_mode_id  WHERE `customer_id`='$user_id' and pickup_in_scan = '2' and branch_in_scan = '2'")->result();
		$this->load->view('franchise/booking_master/cancel_shipment_list', $data);
	}

	public function shipment_list($offset = 0, $searching = '')
	{


		$filterCond = '';
		$all_data = $this->input->post();

		if ($all_data) {
			// print_r($all_data);	
			$filter_value = $_POST['filter_value'];

			foreach ($all_data as $key => $val) {
				if ($key == 'filter' && !empty($val)) {

					if ($val == 'pod_no') {
						$filterCond .= " AND tbl_domestic_booking.pod_no = '$filter_value'";
					}
					if ($val == 'sender_name' || $val == 'waking_customer') {
						$filterCond .= " AND tbl_domestic_booking.sender_name = '$filter_value' AND tbl_domestic_booking.bnf_customer_id ='0'";
					}
					if ( $val == 'company_customer') {
						$filterCond .= " AND tbl_domestic_booking.sender_name = '$filter_value' AND tbl_domestic_booking.bnf_customer_id !='0'";
					}
					if ($val == 'receiver_name') {
						$filterCond .= " AND tbl_domestic_booking.receiver_name = '$filter_value'";
					}
					if ($val == 'origin') {
						$city_info = $this->basic_operation_m->get_table_row('city', "city='$filter_value'");
						$filterCond .= " AND tbl_domestic_booking.sender_city = '$city_info->id'";
					}
					if ($val == 'destination') {
						$city_info = $this->basic_operation_m->get_table_row('city', "city='$filter_value'");
						$filterCond .= " AND tbl_domestic_booking.reciever_city = '$city_info->id'";
					}
					if ($val == 'pickup') {
						$filterCond .= " AND tbl_domestic_booking.pickup = '$filter_value'";
					}
				} elseif ($key == 'user_id' && !empty($val)) {
					$filterCond .= " AND tbl_domestic_booking.customer_id = '$val'";
				} elseif ($key == 'from_date' && !empty($val)) {
					$filterCond .= " AND tbl_domestic_booking.booking_date >= '$val' ";
				} elseif ($key == 'to_date' && !empty($val)) {
					$filterCond .= " AND tbl_domestic_booking.booking_date <= '$val'";
				} elseif ($key == 'courier_company' && !empty($val) && $val != "ALL") {
					$filterCond .= " AND tbl_domestic_booking.courier_company_id = '$val'";
				} elseif ($key == 'mode_name' && !empty($val) && $val != "ALL") {
					$filterCond .= " AND tbl_domestic_booking.mode_dispatch = '$val'";
				}
			}
		}

		if (!empty($searching)) {
			$filterCond = urldecode($searching);
		}

		$user_id = $this->session->userdata("customer_id");
		//print_r($user_id);exit;
		$where = "AND customer_id = '$user_id' ";
		$resActt = $this->db->query("SELECT * FROM tbl_domestic_booking  WHERE booking_type = 1  $where $filterCond ");


		$resAct = $this->db->query("SELECT tbl_domestic_booking.*,transfer_mode.mode_name as mode_dispatch  FROM `tbl_domestic_booking` JOIN transfer_mode ON tbl_domestic_booking.mode_dispatch =transfer_mode.transfer_mode_id  WHERE booking_type = 1 $filterCond AND customer_id = '$user_id' GROUP BY tbl_domestic_booking.booking_id order by tbl_domestic_booking.booking_id DESC limit " . $offset . ",100");
			// echo $this->db->last_query();exit;
		// 	echo $this->db->last_query();exit;

		$download_query = "SELECT tbl_domestic_booking.*,transfer_mode.mode_name as mode_dispatch  FROM `tbl_domestic_booking` JOIN transfer_mode ON tbl_domestic_booking.mode_dispatch = transfer_mode.transfer_mode_id  WHERE booking_type = 1 $filterCond AND customer_id = '$user_id' GROUP BY tbl_domestic_booking.booking_id order by tbl_domestic_booking.booking_id DESC limit " . $offset . ",100";





		$this->load->library('pagination');

		$data['total_count'] = $resActt->num_rows();
		$config['total_rows'] = $resActt->num_rows();
		$config['base_url'] = base_url() . 'Franchise_manager/shipment_list';
		$config['per_page'] = 100;
		$config['full_tag_open'] = '<nav aria-label="..."><ul class="pagination">';
		$config['full_tag_close'] = '</ul></nav>';
		$config['first_link'] = '&laquo; First';
		$config['first_tag_open'] = '<li class="prev paginate_button page-item">';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = 'Last &raquo;';
		$config['last_tag_open'] = '<li class="next paginate_button page-item">';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = 'Next';
		$config['next_tag_open'] = '<li class="next paginate_button page-item">';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = 'Previous';
		$config['prev_tag_open'] = '<li class="prev paginate_button page-item">';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="paginate_button page-item active"><a href="javascript:void(0);" class="page-link">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="paginate_button page-item">';
		$config['reuse_query_string'] = TRUE;
		$config['num_tag_close'] = '</li>';

		$config['attributes'] = array('class' => 'page-link');
		$this->pagination->initialize($config);
		if ($offset == '') {
			$config['uri_segment'] = 3;
			$data['serial_no'] = 1;
		} else {
			$config['uri_segment'] = 3;
			$data['serial_no'] = $offset + 1;
		}

		if ($resAct->num_rows() > 0) {

			$data['shipment_list'] = $resAct->result();
		} else {
			$data['shipment_list'] = array();
		}

		if (isset($_POST['download_report']) && $_POST['download_report'] == 'Excel') {


			$resActtt = $this->db->query($download_query);
			$shipment_data = $resActtt->result_array();
			$this->download_domestic_shipmentreport($shipment_data);
		}


		$whr_c = array('company_type' => 'Domestic');
		$data['courier_company'] = $this->basic_operation_m->get_all_result("courier_company", $whr_c);
		$data['mode_details'] = $this->basic_operation_m->get_all_result("transfer_mode", '');
		$data['customer'] = $this->basic_operation_m->get_query_result_array("SELECT * FROM tbl_customers WHERE parent_cust_id = '$user_id'  ORDER BY customer_name ASC");

		$this->load->view('franchise/booking_master/shipment_list', $data);
	}




	public function download_domestic_shipmentreport($shipment_data)
	{


		$date = date('d-m-Y');
		$filename = "SipmentDetails_" . $date . ".csv";

		header("Content-Description: File Transfer");


		/* get data */
		// 		$usersData = $this->Crud_model->getUserDetails();
		/* file creation */

		//print_r($download_report_query);
		// print_r($shipment_data);die;


		$date = date('d-m-Y');
		$filename = "SipmentDetails_" . $date . ".csv";
		$fp = fopen('php://output', 'w');

		$header = array("AWB No.", "Sender", "Sender City", "Receiver", "Receiver City", "Booking date", "Pay Mode", "Amount", "Type", "NOP", "Invoice No", "Invoice Amount", "Eway No", "Eway Expiry date", "Booking Branch");


		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=' . $filename);
		$file = fopen('php://output', 'w');
		// $file = fopen('php:/* output','w'); 
		fputcsv($fp, $header);

		foreach ($shipment_data as $row) {
			//	print_r($shipment_data);
			$reciever_city = $row['reciever_city'];
			$sender_city = $row['sender_city'];
			$booking_id = $row['booking_id'];
			$dd1 = $this->db->query("SELECT * FROM `city` WHERE id ='$reciever_city'")->row_array();
			$dd2 = $this->db->query("SELECT * FROM `city` WHERE id ='$sender_city'")->row_array();

			$domestic = $this->db->query("SELECT * FROM `tbl_domestic_tracking` where booking_id = '$booking_id' limit 1")->row();
			$tbl_domestic_weight_details = $this->db->query("SELECT * FROM `tbl_domestic_weight_details` where booking_id = '$booking_id' limit 1")->row();
			$data = array(

				$row['pod_no'],
				$row['sender_name'],
				$dd2['city'],
				$row['reciever_name'],
				$dd1['city'],
				$row['booking_date'],
				$row['dispatch_details'],
				$row['total_amount'],
				$row['doc_nondoc'],
				$tbl_domestic_weight_details->no_of_pack,
				$row['invoice_no'],
				$row['invoice_value'],
				$row['eway_no'],
				$row['eway_expiry'],
				$domestic->branch_name,

			);

			fputcsv($file, $data);
		}
		fclose($file);

		exit;
	}





	//         $date=date('d-m-Y');
	// 		$filename = "SipmentDetails_".$date.".csv";
	// 		$fp = fopen('php://output', 'w');

	// 		$header =array("AWB No.","Sender","Receiver","Receiver City","Booking date","Mode","Pay Mode","Amount","Weight","NOP","Invoice No","Invoice Amount","Branch Name","User","Eway No","Eway Expiry date");


	// 		header('Content-type: application/csv');
	// 		header('Content-Disposition: attachment; filename='.$filename);

	// 		fputcsv($fp, $header);
	// 		$i =0;

	// 		foreach($download_report_query as $row) 
	// 		{
	// 		    $whr=array('id'=>$row['sender_city']);
	// 			$sender_city_details = $this->basic_operation_m->get_table_row("city",$whr);
	// 			$sender_city = $sender_city_details->city;

	// 			$whr_s=array('id'=>$row['reciever_state']);
	// 			$reciever_state_details = $this->basic_operation_m->get_table_row("state",$whr_s);
	// 			$reciever_state = $reciever_state_details->state;

	// 			$whr_p=array('id'=>$row['payment_method']);
	// 			$payment_method_details = $this->basic_operation_m->get_table_row("payment_method",$whr_p);
	// 			$payment_method = $payment_method_details->method;


	// 		   	$row=array(
	// 		        $row['pod_no'],
	// 				$row['sender_name'],
	// 				$row['reciever_name'],
	// 				$row['city'],
	// 				$row['booking_date'],
	// 				$mode_details->mode_name,
	// 				$row['dispatch_details'],
	// 				//$row['grand_total'],
	// 			//	$row['chargable_weight'],
	// 			//	$row['no_of_pack'],
	// 				$row['invoice_no'],
	// 				$row['invoice_value'],
	// 			//	$branch_details->branch_name,
	// 				//$user_details->username  
	// 		    );
	// 		   	fputcsv($fp, $row); 
	// 		}
	//     }

	public function view_booking_shipment()
	{
		$booking_id = $this->input->post('booking_id');
		$data = $this->db->query("SELECT tbl_domestic_booking.* ,transfer_mode.mode_name as mode_dispatch, state.state as sender_state, rs.state as reciever_state, city.city as sender_city, rc.city as reciever_city FROM `tbl_domestic_booking` LEFT JOIN state ON tbl_domestic_booking.sender_state = state.id LEFT JOIN state as rs ON tbl_domestic_booking.reciever_state = rs.id LEFT JOIN city ON tbl_domestic_booking.sender_city = city.id LEFT JOIN city as rc ON tbl_domestic_booking.reciever_city = rc.id LEFT JOIN transfer_mode ON tbl_domestic_booking.mode_dispatch =transfer_mode.transfer_mode_id WHERE  `booking_id` =" . $booking_id)->result();
		// echo $this->db->last_query();
		echo json_encode($data);
	}


	//booking 

	public function b2b_booking_list()
	{
		$data = $this->data;
		$this->load->view('franchise/booking_list/b2b_booking_list', $data);
	}
	public function not_shippted_booking_list()
	{
		$data = $this->data;
		$this->load->view('franchise/booking_list/notshippted_booking_list', $data);
	}

	public function booked_order_list()
	{
		$data = $this->data;
		$this->load->view('franchise/booking_list/booked_order_list', $data);
	}

	public function view_order()
	{
		$data = $this->data;
		$this->load->view('franchise/booking_list/view_order', $data);
	}


	//tracking

	public function b2b_track_list()
	{
		$data = $this->data;
		$this->load->view('franchise/tracking_master/b2b_tracking_list', $data);
	}

	public function b2b_track_bookedList()
	{
		$customer_id = $this->session->userdata('customer_id');
		$data = $this->data;
		$data['booked_list'] = $this->db->query("select tbl_domestic_booking.* ,tbl_domestic_tracking.status,tbl_customers.customer_name from tbl_domestic_booking  LEFT JOIN tbl_customers ON tbl_customers.customer_id = tbl_domestic_booking.customer_id inner join tbl_domestic_tracking ON tbl_domestic_booking.pod_no = tbl_domestic_tracking.pod_no  where tbl_domestic_booking.customer_id = '$customer_id'AND tbl_domestic_tracking.status = 'booked'")->result_array();
		$this->load->view('franchise/tracking_master/b2b_tracking_bookedList', $data);
	}

	public function b2b_track_pendingpickupList()
	{
		$data = $this->data;
		$customer_id = $this->session->userdata('customer_id');
		$data['pending_list'] = $this->db->query("select tbl_domestic_booking.* ,tbl_domestic_tracking.status,tbl_customers.customer_name from tbl_domestic_booking  LEFT JOIN tbl_customers ON tbl_customers.customer_id = tbl_domestic_booking.customer_id inner join tbl_domestic_tracking ON tbl_domestic_booking.pod_no = tbl_domestic_tracking.pod_no  where tbl_domestic_booking.customer_id = '$customer_id'AND tbl_domestic_tracking.status = 'pending'")->result_array();
		$this->load->view('franchise/tracking_master/b2b_tracking_pendingpickupList', $data);
	}

	public function b2b_track_intransite()
	{
		$data = $this->data;
		$customer_id = $this->session->userdata('customer_id');
		$data['intransite_list'] = $this->db->query("select tbl_domestic_booking.* ,tbl_domestic_tracking.status,tbl_customers.customer_name from tbl_domestic_booking  LEFT JOIN tbl_customers ON tbl_customers.customer_id = tbl_domestic_booking.customer_id inner join tbl_domestic_tracking ON tbl_domestic_booking.pod_no = tbl_domestic_tracking.pod_no  where tbl_domestic_booking.customer_id = '$customer_id'AND tbl_domestic_tracking.status = 'shifted'")->result_array();
		$this->load->view('franchise/tracking_master/b2b_tracking_intransitList', $data);
	}

	public function b2b_track_deliveryList()
	{
		$customer_id = $this->session->userdata('customer_id');
		$data['delivery_list'] = $this->db->query("select tbl_domestic_booking.* ,tbl_domestic_tracking.status,tbl_customers.customer_name from tbl_domestic_booking  LEFT JOIN tbl_customers ON tbl_customers.customer_id = tbl_domestic_booking.customer_id inner join tbl_domestic_tracking ON tbl_domestic_booking.pod_no = tbl_domestic_tracking.pod_no  where tbl_domestic_booking.customer_id = '$customer_id'AND tbl_domestic_tracking.status = 'Out for Delivery'")->result_array();
		$this->load->view('franchise/tracking_master/b2b_tracking_deliverylist', $data);
	}

	public function b2b_track_deliveredList()
	{
		$customer_id = $this->session->userdata('customer_id');
		$data['delivered_list'] = $this->db->query("select tbl_domestic_booking.* ,tbl_domestic_tracking.status,tbl_customers.customer_name from tbl_domestic_booking  LEFT JOIN tbl_customers ON tbl_customers.customer_id = tbl_domestic_booking.customer_id inner join tbl_domestic_tracking ON tbl_domestic_booking.pod_no = tbl_domestic_tracking.pod_no  where tbl_domestic_booking.customer_id = '$customer_id'AND tbl_domestic_tracking.status = 'delivered'")->result_array();
		$this->load->view('franchise/tracking_master/b2b_track_deliveredList', $data);
	}



	// NDR List

	public function ndr_list()
	{
		$data = $this->data;
		$this->load->view('franchise/ndr_master/ndr_list', $data);
	}



	//****************** billing ********************




	public function b2c_pricing()
	{

		$this->load->view('franchise/billing_master/b2c_pricing');
	}

	public function b2b_pricing()
	{

		$this->load->view('franchise/billing_master/b2b_pricing');
	}

	public function cod_remittance()
	{

		$this->load->view('franchise/billing_master/cod-remittance');
	}

	public function credit_note()
	{

		$this->load->view('franchise/billing_master/credit-note');
	}

	public function wallet_transaction()
	{
		$customer_id = $this->session->userdata('customer_id');
		$data['transaction_data'] = $this->db->query("select * from franchise_topup_balance_tbl where customer_id = '$customer_id' order by topup_balance_id desc")->result();

		$this->load->view('franchise/billing_master/wallet-transaction', $data);
	}

	public function shipping_charges()
	{

		$this->load->view('franchise/billing_master/shipping_charges');
	}

	public function invoice()
	{

		$this->load->view('franchise/billing_master/invoice');
	}

	public function weight_reconciliation()
	{

		$this->load->view('franchise/billing_master/weight-reconciliation');
	}

	//****************** Addon ********************

	public function pincode_services()
	{

		$this->load->view('franchise/addon/pincode-services');
	}

	public function getCityList_rate()
	{
		$pincode = $this->input->post('pincode');
		$whr1 = array('pin_code' => $pincode,'isdeleted'=>0);
		$res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);

		$city_id = $res1->row()->city_id;

		$whr2 = array('id' => $city_id);
		$res2 = $this->basic_operation_m->get_table_row('city', $whr2);
		$pincode_city = $res2->id;

		$city_list = $this->basic_operation_m->get_all_result('city', '');

		$resAct = $this->db->query("select service_pincode.*,courier_company.c_id,courier_company.c_company_name from service_pincode JOIN courier_company on courier_company.c_id=service_pincode.forweder_id where pincode='" . $pincode . "' order by serv_pin_id DESC ");

		$data = array();
		$data['forwarder'] = array();
		if ($resAct->num_rows() > 0) {
			$data['forwarder'] = $resAct->result_array();
		}

		$option = "";
		$forwarder = "";
		foreach ($city_list as $value) {
			if ($value["id"] == $pincode_city) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			$option .= '<option value="' . $value["id"] . '" ' . $selected . ' >' . $value["city"] . '</option>';
		}

		if (!empty($data['forwarder'])) {
			foreach ($data['forwarder'] as $key => $value) {
				$servicable = '';
				// if ($value['servicable']==0) {
				// 	//$servicable = 'no service';
				// }else{
				// 	$servicable = 'service';
				// }

				if ($value['oda'] == 1) {

					$servicable = ' - ODA Available';

				} else {
					// $servicable = ' ODA Available';
				}
				$forwarder .= "<option value='" . $value["c_company_name"] . "'>" . $value["c_company_name"] . "" . $servicable . "</option>";
			}
		}

		$forwarder .= "<option value='SELF'>SELF</option>";
		unset($data['forwarder']);
		$data['option'] = $option;
		$data['forwarder2'] = $forwarder;

		echo json_encode($data);
	}

	public function getState_rate()
	{
		$pincode = $this->input->post('pincode');
		$whr1 = array('pin_code' => $pincode,'isdeleted'=>0);
		$res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);

		$state_id = $res1->row()->state_id;

		if (!empty($state_id)) {
			$whr3 = array('id' => $state_id);
			$res3 = $this->basic_operation_m->get_table_row('state', $whr3);
			$pincode_state = $res3->id;


			$state_list = $this->basic_operation_m->get_all_result('state', '');
			$option = "";
			foreach ($state_list as $value) {
				if ($value["id"] == $pincode_state) {
					$selected = "selected";
				} else {
					$selected = "";
				}
				$option .= '<option value="' . $value["id"] . '" ' . $selected . ' >' . $value["state"] . '</option>';
			}
			//  print_r($option);die;
		} else {
			$option = array();
		}


		echo json_encode($option);

	}


	public function main_setting()
	{
		$data = $this->data;
		$this->load->view('franchise/setting_master/main-setting', $data);
	}

	public function report()
	{
		$data = $this->data;
		$this->load->view('franchise/report_master/report', $data);
	}

	public function escalations()
	{
		$data = $this->data;
		$this->load->view('franchise/support/escalations', $data);
	}

	public function add_new_rate_domestic()
	{
		$sub_total = 0;
		$customer_id = $this->session->userdata('customer_id');
		$c_courier_id = $this->input->post('c_courier_id');
		$mode_id = $this->input->post('mode_id');
		$reciver_city = $this->input->post('city');
		$reciver_state = $this->input->post('state');
		$sender_state = $this->input->post('sender_state');
		$sender_city = $this->input->post('sender_city');
		$is_appointment = $this->input->post('is_appointment');
		// $invoice_value = $this->input->post('invoice_value');

		$groupId = $this->basic_operation_m->selectRecord('franchise_delivery_tbl', array('delivery_franchise_id' => $customer_id))->row();

		// echo $this->db->last_query();exit();
		// print_r($groupId);


		$whr1 = array('state' => $sender_state, 'city' => $sender_city);
		$res1 = $this->basic_operation_m->selectRecord('region_master_details', $whr1);

		$sender_zone_id = $res1->row()->regionid;
		$reciver_zone_id = $this->input->post('receiver_zone_id');

		$doc_type = $this->input->post('doc_type');
		$chargable_weight = $this->input->post('chargable_weight');
		$receiver_gstno = $this->input->post('receiver_gstno');
		$booking_date = $this->input->post('booking_date');
		$invoice_value = $this->input->post('invoice_value');
		$dispatch_details = $this->input->post('dispatch_details');
		$current_date = date("Y-m-d", strtotime($booking_date));
		$chargable_weight = $chargable_weight * 1000;
		$fixed_perkg = 0;
		$addtional_250 = 0;
		$addtional_500 = 0;
		$addtional_1000 = 0;
		$fixed_per_kg_1000 = 0;
		$tat = 0;
		$rate = 0;

		// $where					= "from_zone_id='" . $sender_zone_id . "' AND to_zone_id='" . $reciver_zone_id . "'";

		// checking city and state rate 
		$fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "' AND city_id='" . $reciver_city . "'  AND mode_id='" . $mode_id . "' AND DATE(`applicable_from`)<='" . $current_date . "' AND (" . $this->input->post('chargable_weight') . " BETWEEN weight_range_from AND weight_range_to) and fixed_perkg = '0' ORDER BY applicable_from DESC LIMIT 1");
		// echo $this->db->last_query();die;
		if ($fixed_perkg_result->num_rows() > 0) {
			$where = "city_id='" . $reciver_city . "'";
		} else {
			$fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "' AND city_id='" . $reciver_city . "'  AND mode_id='" . $mode_id . "' AND DATE(`applicable_from`)<='" . $current_date . "' AND fixed_perkg = '0' ORDER BY applicable_from DESC,weight_range_to desc LIMIT 1");
			if ($fixed_perkg_result->num_rows() > 0) {
				$where = "city_id='" . $reciver_city . "'";
			}
		}

		// checking city and state rate 
		$fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "' AND state_id='" . $reciver_state . "' and city_id=''  AND mode_id='" . $mode_id . "' AND DATE(`applicable_from`)<='" . $current_date . "' AND (" . $this->input->post('chargable_weight') . " BETWEEN weight_range_from AND weight_range_to) and fixed_perkg = '0' ORDER BY applicable_from DESC LIMIT 1");
		if ($fixed_perkg_result->num_rows() > 0) {
			$where = "state_id='" . $reciver_state . "'";
		} else {
			$fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "' AND state_id='" . $reciver_state . "' and city_id=''  AND mode_id='" . $mode_id . "' AND DATE(`applicable_from`)<='" . $current_date . "' AND fixed_perkg = '0' ORDER BY applicable_from DESC,weight_range_to desc LIMIT 1");
			if ($fixed_perkg_result->num_rows() > 0) {
				$where = "state_id='" . $reciver_state . "'";
			}
		}
		// print_r($where);die;
		// calculationg fixed per kg price 	
		if (empty($where)) {
			$where = "state_id = '0' AND city_id ='0'";
			// $where = "1";
		}
		$fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "' AND from_zone_id='" . $sender_zone_id . "' AND to_zone_id='" . $reciver_zone_id . "' AND $where 	and mode_id='" . $mode_id . "' AND DATE(`applicable_from`)<='" . $current_date . "' AND (" . $this->input->post('chargable_weight') . " BETWEEN weight_range_from AND weight_range_to) and fixed_perkg = '0' ORDER BY applicable_from DESC LIMIT 1");
		$frieht = 0;
		// echo $this->db->last_query();die;
		if ($fixed_perkg_result->num_rows() > 0) {
			$data['rate_master'] = $fixed_perkg_result->row();
			$rate = $data['rate_master']->rate;
			$tat = $data['rate_master']->tat;
			$fixed_perkg = $rate;
		} else {
			$fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "' AND from_zone_id='" . $sender_zone_id . "' AND to_zone_id='" . $reciver_zone_id . "' AND $where  AND  mode_id='" . $mode_id . "' AND DATE(`applicable_from`)<='" . $current_date . "' AND fixed_perkg = '0' ORDER BY applicable_from DESC,weight_range_to desc LIMIT 1");

			$fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where 
			group_id='" . @$groupId->rate_group . "' 
			AND from_zone_id=" . $sender_zone_id . " AND to_zone_id=" . $reciver_zone_id . "			
			AND (city_id=" . $reciver_city . " OR  city_id=0)		
			AND (state_id=" . $reciver_state . " || state_id=0)
			AND (mode_id=" . $mode_id . " || mode_id=0)
			AND DATE(`applicable_from`)<='" . $current_date . "'
			AND (" . $this->input->post('chargable_weight') . " BETWEEN weight_range_from AND weight_range_to)  
			ORDER BY state_id DESC,city_id DESC,applicable_from DESC LIMIT 1");
			// echo $this->db->last_query();die;
			if ($fixed_perkg_result->num_rows() > 0) {
				$data['rate_master'] = $fixed_perkg_result->row();
				$rate = $data['rate_master']->rate;
				$tat = $data['rate_master']->tat;
				$weight_range_to = round($data['rate_master']->weight_range_to * 1000);
				$fixed_perkg = $rate;
				// echo '<pre>';print_r($rate);die;
			}

			// $fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "'  AND $where  AND mode_id='" . $mode_id . "' AND DATE(`applicable_from`)<='" . $current_date . "' AND fixed_perkg <> '0' ");

			if ($fixed_perkg_result->num_rows() > 0) {

				// echo "4444uuuu<pre>";
				$rate_master = $fixed_perkg_result->result();
				// print_r($rate_master[0]->weight_range_to);exit();
				$weight_range_to = round($rate_master[0]->weight_range_to * 1000);
				$left_weight = ($chargable_weight - $weight_range_to);

				foreach ($rate_master as $key => $values) {
					$tat = $values->tat;
					if ($values->fixed_perkg == 1) // 250 gm slab
					{

						$slab_weight = ($values->weight_slab < $left_weight) ? $values->weight_slab : $left_weight;
						$total_slab = $slab_weight / 250;
						$addtional_250 = $addtional_250 + $total_slab * $values->rate;
						$left_weight = $left_weight - $slab_weight;
					}

					if ($values->fixed_perkg == 2) // 500 gm slab
					{
						$slab_weight = ($values->weight_slab < $left_weight) ? $values->weight_slab : $left_weight;

						if ($slab_weight < 1000) {
							if ($slab_weight <= 500) {
								$slab_weight = 500;
							} else {
								$slab_weight = 1000;
							}

						} else {
							$diff_ceil = $slab_weight % 1000;
							$slab_weight = $slab_weight - $diff_ceil;

							if ($diff_ceil <= 500 && $diff_ceil != 0) {

								$slab_weight = $slab_weight + 500;
							} elseif ($diff_ceil <= 1000 && $diff_ceil != 0) {

								$slab_weight = $slab_weight + 1000;
							}


						}

						$total_slab = $slab_weight / 500;
						$addtional_500 = $addtional_500 + $total_slab * $values->rate;
						$left_weight = $left_weight - $slab_weight;

					}

					if ($values->fixed_perkg == 3) // 1000 gm slab
					{
						$slab_weight = ($values->weight_slab < $left_weight) ? $values->weight_slab : $left_weight;
						$total_slab = ceil($slab_weight / 1000);

						$addtional_1000 = $addtional_1000 + $total_slab * $values->rate;
						$left_weight = $left_weight - $slab_weight;
					}
					// echo "hsdskjdhaskjda";exit();
					if ($values->fixed_perkg == 4 && ($this->input->post('chargable_weight') >= $values->weight_range_from && $this->input->post('chargable_weight') <= $values->weight_range_to)) // 1000 gm slab
					{
						// echo "hsdskjdhaskjda";exit();
						//$slab_weight = ($values->weight_slab < $left_weight)?$values->weight_slab:$left_weight;	
						$slab_weight = ($values->weight_slab < $left_weight) ? $values->weight_slab : $left_weight;
						$total_slab = ceil($chargable_weight / 1000);

						$fixed_perkg = 0;
						$addtional_250 = 0;
						$addtional_500 = 0;
						$addtional_1000 = 0;
						$rate = $values->rate;
						// $frieht= $values->rate;
						$fixed_per_kg_1000 = $total_slab * $values->rate;
						$left_weight = $left_weight - $slab_weight;

					}

					// print_r('$fixed_perkg ='.$fixed_perkg.' $addtional_250='.$addtional_250.' $addtional_500='.$addtional_500.' $addtional_1000='.$addtional_1000.' $fixed_per_kg_1000='.$fixed_per_kg_1000);
				}

			}

		}
		$frieht = $fixed_perkg + $addtional_250 + $addtional_500 + $addtional_1000 + $fixed_per_kg_1000;
		$amount = $frieht;
		$res1 = $this->db->query("SELECT * FROM franchise_fule_tbl WHERE group_id ='$groupId->fule_group' ORDER BY fuel_id DESC LIMIT 1")->row();
        // echo $this->db->last_query();die;
		if ($res1) {

			$cft = 7;
			$cod = '0';
			$fov = $res1->fov_min;
			$docket_charge = $res1->awb_rate;
			$fov_base = $res1->fov_base;
			$fov_min = $res1->fov_min;

			if ($invoice_value >= $fov_base) {
				$fov = (($invoice_value / 100) * $res1->fov_above);
			} elseif ($invoice_value < $res1->fov_base) {
				$fov = (($invoice_value / 100) * $res1->fov_below);
			}

			if ($fov < $fov_min) {
				$fov = $fov_min;
			}

			$to_pay_charges = '0';
			$appt_charges = '0';
			$fuel_per = '0';
			$docket_charge = $res1->awb_rate;
			$amount = $amount + $fov + $docket_charge + $cod + $to_pay_charges + $appt_charges;
			$final_fuel_charges = ($amount * $res1->fule_percentage / 100);
		} else {
			$cft = 7;
			$cod = '0';
			$fov = '0';
			$to_pay_charges = '0';
			$appt_charges = '0';
			$fuel_per = '0';
			$docket_charge = '0';
			$amount = $amount + $fov + $docket_charge + $cod + $to_pay_charges + $appt_charges;
			$final_fuel_charges = ($amount * $fuel_per / 100);
		}

		//Cash
		$sub_total = ($amount + $final_fuel_charges);
		$first_two_char = substr($receiver_gstno, 0, 2);

		if ($receiver_gstno == "") {
			$first_two_char = 27;
		}

		$tbl_customers_info = $this->basic_operation_m->get_query_row("select gst_charges from tbl_customers where customer_id = '$customer_id'");

		if ($tbl_customers_info->gst_charges == 1) {
			if ($first_two_char == 27) {
				$cgst = ($sub_total * 9 / 100);
				$sgst = ($sub_total * 9 / 100);
				$igst = 0;
				$grand_total = $sub_total + $cgst + $sgst + $igst;
			} else {
				$cgst = 0;
				$sgst = 0;
				$igst = ($sub_total * 18 / 100);
				$grand_total = $sub_total + $igst;
			}
		} else {
			$cgst = 0;
			$sgst = 0;
			$igst = 0;
			$grand_total = $sub_total + $igst;
		}

		if ($dispatch_details == 'Cash') {
			$cgst = 0;
			$sgst = 0;
			$igst = 0;
			$grand_total = $sub_total + $igst;
		}


		$query = "select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "' AND from_zone_id=" . $sender_zone_id . " AND to_zone_id=" . $reciver_zone_id . " AND (city_id=" . $reciver_city . " OR  city_id=0)AND (state_id=" . $reciver_state . " || state_id=0) AND (mode_id=" . $mode_id . " || mode_id=0) AND DATE(`applicable_from`)<='" . $current_date . "'AND (" . $this->input->post('chargable_weight') . " BETWEEN weight_range_from AND weight_range_to) ORDER BY state_id DESC,city_id DESC,applicable_from DESC LIMIT 1";
		if ($tat > 0) {
			$tat_date = date('Y-m-d', strtotime($booking_date . " + $tat days"));
		} else {
			$tat_date = date('Y-m-d', strtotime($booking_date . " + 5 days"));
		}

		$cft = 7;

		$data = array(
			'query' => $query,
			'Rate Value' => $rate,
			'sender_zone_id' => $sender_zone_id,
			'tat_date' => $tat_date,
			'reciver_zone_id' => $reciver_zone_id,
			'chargable_weight' => ceil($chargable_weight),
			'frieht' => round($frieht, 2),
			'fov' => round($fov, 2),
			'appt_charges' => round($appt_charges, 2),
			'docket_charge' => round($docket_charge, 2),
			'amount' => round($amount, 2),
			'cod' => round($cod, 2),
			'cft' => round($cft, 2),
			'to_pay_charges' => round($to_pay_charges, 2),
			'final_fuel_charges' => round($final_fuel_charges, 2),
			'sub_total' => number_format($sub_total, 2, '.', ''),
			'cgst' => number_format($cgst, 2, '.', ''),
			'sgst' => number_format($sgst, 2, '.', ''),
			'igst' => number_format($igst, 2, '.', ''),
			'grand_total' => number_format($grand_total, 2, '.', ''),
		);
		echo json_encode($data);
		exit;
	}

	public function getFuelprice()
	{
		$customer_id = $this->input->post('customer_id');
		$courier_id = $this->input->post('courier_id');
		$booking_date = $this->input->post('booking_date');
		$current_date = date("Y-m-d", strtotime($booking_date));
		$whr1 = array('courier_id' => $courier_id, 'fuel_from <=' => $current_date, 'fuel_to >=' => $current_date, 'customer_id =' => $customer_id);
		$res1 = $this->basic_operation_m->get_table_row('courier_fuel', $whr1);
		if (empty($res1)) {
			$whr1 = array('courier_id' => $courier_id, 'fuel_from <=' => $current_date, 'fuel_to >=' => $current_date, 'customer_id =' => '0');
			$res1 = $this->basic_operation_m->get_query_row("select * from courier_fuel where (courier_id = '$courier_id' or courier_id='0') and fuel_from <= '$current_date' and fuel_to >='$current_date' and (customer_id = '0' or customer_id = '$customer_id') ORDER BY customer_id DESC");
		}
		$gst_details = $this->basic_operation_m->get_query_row('select * from tbl_gst_setting order by id desc limit 1');
		if ($res1) {
			$fuel_per = $res1->fuel_price;
		} else {
			$fuel_per = '0';
		}
		$fuel_charge = $res1->fc_type;
		$tbl_customers_info = $this->basic_operation_m->get_query_row("select gst_charges from tbl_customers where customer_id = '$customer_id'");
		if ($tbl_customers_info->gst_charges == 1) {
			     $gst_check = 1;
				 $cgst_per = $gst_details->cgst;
				 $sgst_per = $gst_details->sgst;
				 $igst_per = 0;
			}else{
				$gst_check = 2;
				$cgst_per = 0;
				$sgst_per = 0;
				$igst_per = $gst_details->igst;

			}
			$data = [
				'custAccess'=> $gst_check,
				'fuelPrice'=>$fuel_per,
				'fuel_charge'=>$fuel_charge,
				'cgst'=>$cgst_per,
				'sgst'=>$sgst_per,
				'igst'=>$igst_per
			];
		echo json_encode($data);

	}
	public function calculate_rate()
	{


		$sub_total = 0;
		$customer_id = $this->input->post('customer_id');
		$c_courier_id = $this->input->post('c_courier_id');
		$mode_id = $this->input->post('mode_id');
		$reciver_city = $this->input->post('city');
		$reciver_state = $this->input->post('state');
		$sender_state = $this->input->post('sender_state');
		$sender_city = $this->input->post('sender_city');
		$is_appointment = $this->input->post('is_appointment');

		// $invoice_value = $this->input->post('invoice_value');
		// print_r($_POST);die;
		$groupId = $this->basic_operation_m->selectRecord('franchise_delivery_tbl', array('delivery_franchise_id' => $customer_id))->row();

		// echo $this->db->last_query();exit();
		// print_r($groupId);


		$whr1 = array('state' => $sender_state, 'city' => $sender_city);
		$res1 = $this->basic_operation_m->selectRecord('region_master_details', $whr1);

		$sender_zone_id = $res1->row()->regionid;
		$reciver_zone_id = $this->input->post('receiver_zone_id');

		$doc_type = $this->input->post('doc_type');
		$chargable_weight = $this->input->post('chargable_weight');
		$receiver_gstno = $this->input->post('receiver_gstno');
		$booking_date = $this->input->post('booking_date');
		$invoice_value = $this->input->post('invoice_value');
		$dispatch_details = $this->input->post('dispatch_details');
		$current_date = date("Y-m-d", strtotime($booking_date));
		$chargable_weight = $chargable_weight * 1000;
		$fixed_perkg = 0;
		$addtional_250 = 0;
		$addtional_500 = 0;
		$addtional_1000 = 0;
		$fixed_per_kg_1000 = 0;
		$tat = 0;


		$where = "from_zone_id='" . $sender_zone_id . "' AND to_zone_id='" . $reciver_zone_id . "'";

		// checking city and state rate 
		$fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "' AND city_id='" . $reciver_city . "'  AND mode_id='" . $mode_id . "' AND DATE(`applicable_from`)<='" . $current_date . "' AND (" . $this->input->post('chargable_weight') . " BETWEEN weight_range_from AND weight_range_to) and fixed_perkg = '0' ORDER BY applicable_from DESC LIMIT 1");
		if ($fixed_perkg_result->num_rows() > 0) {
			$where = "city_id='" . $reciver_city . "'";
		} else {
			$fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "' AND city_id='" . $reciver_city . "'  AND mode_id='" . $mode_id . "' AND DATE(`applicable_from`)<='" . $current_date . "' AND fixed_perkg = '0' ORDER BY applicable_from DESC,weight_range_to desc LIMIT 1");
			if ($fixed_perkg_result->num_rows() > 0) {
				$where = "city_id='" . $reciver_city . "'";
			}
		}


		// checking city and state rate 
		$fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "' AND state_id='" . $reciver_state . "' and city_id=''  AND mode_id='" . $mode_id . "' AND DATE(`applicable_from`)<='" . $current_date . "' AND (" . $this->input->post('chargable_weight') . " BETWEEN weight_range_from AND weight_range_to) and fixed_perkg = '0' ORDER BY applicable_from DESC LIMIT 1");
		if ($fixed_perkg_result->num_rows() > 0) {
			$where = "state_id='" . $reciver_state . "'";
		} else {
			$fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "' AND state_id='" . $reciver_state . "' and city_id=''  AND mode_id='" . $mode_id . "' AND DATE(`applicable_from`)<='" . $current_date . "' AND fixed_perkg = '0' ORDER BY applicable_from DESC,weight_range_to desc LIMIT 1");
			if ($fixed_perkg_result->num_rows() > 0) {
				$where = "state_id='" . $reciver_state . "'";
			}
		}

		// calculationg fixed per kg price 	
		$fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "'  AND $where  AND mode_id='" . $mode_id . "' AND DATE(`applicable_from`)<='" . $current_date . "' AND (" . $this->input->post('chargable_weight') . " BETWEEN weight_range_from AND weight_range_to) and fixed_perkg = '0' ORDER BY applicable_from DESC LIMIT 1");
		$frieht = 0;
		// echo $this->db->last_query();die;
		if ($fixed_perkg_result->num_rows() > 0) {
			$data['rate_master'] = $fixed_perkg_result->row();
			$rate = $data['rate_master']->rate;
			$tat = $data['rate_master']->tat;
			$fixed_perkg = $rate;
		} else {
			$fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "' AND $where  AND mode_id='" . $mode_id . "' AND DATE(`applicable_from`)<='" . $current_date . "' AND fixed_perkg = '0' ORDER BY applicable_from DESC,weight_range_to desc LIMIT 1");

			// $fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where 
			// group_id='" . @$groupId->rate_group . "' 
			// AND from_zone_id=" . $sender_zone_id . " AND to_zone_id=" . @$groupId->rate_group . "

			// AND (city_id=" . $reciver_city . " OR  city_id=0)

			// AND (state_id=" . $reciver_state . " || state_id=0)
			// AND (mode_id=" . $mode_id . " || mode_id=0)
			// AND DATE(`applicable_from`)<='" . $current_date . "'
			// AND (" . $this->input->post('chargable_weight') . " BETWEEN weight_range_from AND weight_range_to)  
			// ORDER BY state_id DESC,city_id DESC,applicable_from DESC LIMIT 1");
			if ($fixed_perkg_result->num_rows() > 0) {
				$data['rate_master'] = $fixed_perkg_result->row();
				$rate = $data['rate_master']->rate;
				$tat = $data['rate_master']->tat;
				$weight_range_to = round($data['rate_master']->weight_range_to * 1000);
				$fixed_perkg = $rate;
			}

			$fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "'  AND $where  AND mode_id='" . $mode_id . "' AND DATE(`applicable_from`)<='" . $current_date . "' AND fixed_perkg <> '0' ");
			if ($fixed_perkg_result->num_rows() > 0) {
				if ($weight_range_to > 1000) {
					$weight_range_to = $weight_range_to;
				} else {
					$weight_range_to = 1000;
				}
				$left_weight = ($chargable_weight - $weight_range_to);

				$rate_master = $fixed_perkg_result->result();

				foreach ($rate_master as $key => $values) {
					$tat = $values->tat;
					if ($values->fixed_perkg == 1) // 250 gm slab
					{

						$slab_weight = ($values->weight_slab < $left_weight) ? $values->weight_slab : $left_weight;
						$total_slab = $slab_weight / 250;
						$addtional_250 = $addtional_250 + $total_slab * $values->rate;
						$left_weight = $left_weight - $slab_weight;
					}

					if ($values->fixed_perkg == 2) // 500 gm slab
					{
						$slab_weight = ($values->weight_slab < $left_weight) ? $values->weight_slab : $left_weight;

						if ($slab_weight < 1000) {
							if ($slab_weight <= 500) {
								$slab_weight = 500;
							} else {
								$slab_weight = 1000;
							}
						} else {
							$diff_ceil = $slab_weight % 1000;
							$slab_weight = $slab_weight - $diff_ceil;

							if ($diff_ceil <= 500 && $diff_ceil != 0) {

								$slab_weight = $slab_weight + 500;
							} elseif ($diff_ceil <= 1000 && $diff_ceil != 0) {

								$slab_weight = $slab_weight + 1000;
							}
						}

						$total_slab = $slab_weight / 500;
						$addtional_500 = $addtional_500 + $total_slab * $values->rate;
						$left_weight = $left_weight - $slab_weight;
					}

					if ($values->fixed_perkg == 3) // 1000 gm slab
					{
						$slab_weight = ($values->weight_slab < $left_weight) ? $values->weight_slab : $left_weight;
						$total_slab = ceil($slab_weight / 1000);

						$addtional_1000 = $addtional_1000 + $total_slab * $values->rate;
						$left_weight = $left_weight - $slab_weight;
					}

					if ($values->fixed_perkg == 4 && ($this->input->post('chargable_weight') >= $values->weight_range_from && $this->input->post('chargable_weight') <= $values->weight_range_to)) // 1000 gm slab
					{
						//$slab_weight = ($values->weight_slab < $left_weight)?$values->weight_slab:$left_weight;	
						$slab_weight = ($values->weight_slab < $left_weight) ? $values->weight_slab : $left_weight;
						$total_slab = ceil($chargable_weight / 1000);
						$fixed_perkg = 0;
						$addtional_250 = 0;
						$addtional_500 = 0;
						$addtional_1000 = 0;
						$rateeee = $values->rate;
						$fixed_per_kg_1000 = $total_slab * $values->rate;
						$left_weight = $left_weight - $slab_weight;
					}
				}
			}
		}

		$frieht = $fixed_perkg + $addtional_250 + $addtional_500 + $addtional_1000 + $fixed_per_kg_1000;
		$amount = $frieht;

		$whr1 = array('group_id' => @$groupId->fule_group);
		$res1 = $this->basic_operation_m->get_table_row('franchise_fule_tbl', $whr1);
		// echo "kddjh";
		// echo $this->db->last_query();
		// print_r($res1);


		if ($res1) {

			$cft = 7;
			$cod = '0';
			if ($doc_type == 1) {
				$fov = ($invoice_value * $res1->fov_rate / 100);
			} else {
				$fov = 0;
			}

			$to_pay_charges = '0';
			$appt_charges = '0';
			$fuel_per = '0';
			$docket_charge = $res1->awb_rate;
			$amount = $amount + $fov + $docket_charge + $cod + $to_pay_charges + $appt_charges;
			$final_fuel_charges = ($amount * $res1->fule_percentage / 100);
		} else {
			$cft = 7;
			$cod = '0';
			$fov = '0';
			$to_pay_charges = '0';
			$appt_charges = '0';
			$fuel_per = '0';
			$docket_charge = '0';
			$amount = $amount + $fov + $docket_charge + $cod + $to_pay_charges + $appt_charges;
			$final_fuel_charges = ($amount * $fuel_per / 100);
		}

		//Cash
		$sub_total = ($amount + $final_fuel_charges);
		$first_two_char = substr($receiver_gstno, 0, 2);

		if ($receiver_gstno == "") {
			$first_two_char = 27;
		}

		$tbl_customers_info = $this->basic_operation_m->get_query_row("select gst_charges from tbl_customers where customer_id = '$customer_id'");

		if ($tbl_customers_info->gst_charges == 1) {
			if ($first_two_char == 27) {
				$cgst = ($sub_total * 9 / 100);
				$sgst = ($sub_total * 9 / 100);
				$igst = 0;
				$grand_total = $sub_total + $cgst + $sgst + $igst;
			} else {
				$cgst = 0;
				$sgst = 0;
				$igst = ($sub_total * 18 / 100);
				$grand_total = $sub_total + $igst;
			}
		} else {
			$cgst = 0;
			$sgst = 0;
			$igst = 0;
			$grand_total = $sub_total + $igst;
		}

		if ($dispatch_details == 'Cash') {
			$cgst = 0;
			$sgst = 0;
			$igst = 0;
			$grand_total = $sub_total + $igst;
		}


		$query = "select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "' AND $where  AND mode_id='" . $mode_id . "' AND DATE(`applicable_from`)<='" . $current_date . "' AND (" . $chargable_weight . " BETWEEN weight_range_from AND weight_range_to)  ORDER BY applicable_from DESC LIMIT 1";

		if ($tat > 0) {
			$tat_date = date('Y-m-d', strtotime($booking_date . " + $tat days"));
		} else {
			$tat_date = date('Y-m-d', strtotime($booking_date . " + 5 days"));
		}

		$cft = 7;

		$data = array(
			'query' => $query,
			'sender_zone_id' => $sender_zone_id,
			'tat_date' => $tat_date,
			'reciver_zone_id' => $reciver_zone_id,
			'chargable_weight' => ceil($chargable_weight),
			'frieht' => round($frieht, 2),
			'fov' => round($fov, 2),
			'appt_charges' => round($appt_charges, 2),
			'docket_charge' => round($docket_charge, 2),
			'amount' => round($amount, 2),
			'cod' => round($cod, 2),
			'cft' => round($cft, 2),
			'to_pay_charges' => round($to_pay_charges, 2),
			'final_fuel_charges' => round($final_fuel_charges, 2),
			'sub_total' => number_format($sub_total, 2, '.', ''),
			'cgst' => number_format($cgst, 2, '.', ''),
			'sgst' => number_format($sgst, 2, '.', ''),
			'igst' => number_format($igst, 2, '.', ''),
			'grand_total' => number_format($grand_total, 2, '.', ''),
		);
		echo json_encode($data);
		exit;
	}





	public function pincode_tracking()
	{
		$data['pincode'] = array();
		if (isset($_GET['pincode']) && !empty($_GET['pincode'])) {
			$pin = $_GET['pincode'];
			$data['pincode'] = $this->db->query("SELECT bs.pincode, b.branch_name, p.* FROM tbl_branch_service bs
				LEFT JOIN tbl_branch b ON(b.branch_id = bs.branch_id)
				LEFT JOIN pincode p ON(p.pin_code = bs.pincode)
				WHERE bs.pincode = $pin 
			")->result();
		}
		$this->load->view('franchise/track_service_pincode', $data);
	}

	// awb stock 

	public function awb_available_stock($offset = 0, $searching = '')
	{
		$user_id = $this->session->userdata("customer_id");
		$stock = $this->db->query("select * from tbl_branch_assign_cnode where customer_id = '$user_id'")->result();

		foreach ($stock as $key => $value) {
			$number1 = range($value->seriess_from, $value->seriess_to);
			$available_stock = [];
			foreach ($number1 as $number) {
				$pod_no = franchise_id() . $number;
				$booking_info = $this->db->query("select * from tbl_domestic_booking where pod_no = '$pod_no' order by booking_id desc limit 1")->row();
				if ($pod_no != $booking_info->pod_no) {
					$available_stock1[] = [$pod_no, 'u' => '0'];
				} else {
					$Utilize[] = [$booking_info->pod_no, 'u' => '1'];
				}
			}
		}
		if (!empty($stock)) {

			if (!empty($Utilize)) {
				$available_stock = array_merge($Utilize, $available_stock1);
			} else {
				$available_stock = $available_stock1;
			}
			if (isset($_GET['search'])) {
				if (in_array(strtoupper($_GET['search']), array_column($available_stock, '0'))) {
					$data['search_data'] = strtoupper($_GET['search']);
				} else {
					$data['available_stock'] = array();
				}
			} else {

				$stock_result = array_slice($available_stock, $offset, 300);

				$this->load->library('pagination');
				$data['total_count'] = count($available_stock);
				$config['total_rows'] = count($available_stock);
				$config['base_url'] = base_url() . 'franchise/awb-available-stock';
				$config['per_page'] = 300;
				$config['full_tag_open'] = '<nav aria-label="..."><ul class="pagination">';
				$config['full_tag_close'] = '</ul></nav>';
				$config['first_link'] = '&laquo; First';
				$config['first_tag_open'] = '<li class="prev paginate_button page-item">';
				$config['first_tag_close'] = '</li>';
				$config['last_link'] = 'Last &raquo;';
				$config['last_tag_open'] = '<li class="next paginate_button page-item">';
				$config['last_tag_close'] = '</li>';
				$config['next_link'] = 'Next';
				$config['next_tag_open'] = '<li class="next paginate_button page-item">';
				$config['next_tag_close'] = '</li>';
				$config['prev_link'] = 'Previous';
				$config['prev_tag_open'] = '<li class="prev paginate_button page-item">';
				$config['prev_tag_close'] = '</li>';
				$config['cur_tag_open'] = '<li class="paginate_button page-item active"><a href="javascript:void(0);" class="page-link">';
				$config['cur_tag_close'] = '</a></li>';
				$config['num_tag_open'] = '<li class="paginate_button page-item">';
				$config['reuse_query_string'] = TRUE;
				$config['num_tag_close'] = '</li>';

				$config['attributes'] = array('class' => 'page-link');
				$this->pagination->initialize($config);
				if ($offset == '') {
					$config['uri_segment'] = 3;
					$data['serial_no'] = 1;
				} else {
					$config['uri_segment'] = 3;
					$data['serial_no'] = $offset + 1;
				}


				if (!empty($stock_result)) {
					if (!empty($Utilize)) {
						$data['available_stock_count'] = count($available_stock) - count($Utilize);
						$data['Utilize'] = $Utilize;
						$data['Utilize_count'] = count($Utilize);
					} else {
						$data['available_stock_count'] = count($available_stock);
						$data['Utilize'] = 0;
						$data['Utilize_count'] = 0;
					}
					$data['available_stock'] = $stock_result;
				} else {
					$data['available_stock'] = array();
					$data['Utilize'] = '0';
				}
			}
		} else {
			$this->load->library('pagination');
			$data['total_count'] = 0;
			$config['total_rows'] = 0;
			$config['base_url'] = base_url() . 'franchise/awb-available-stock';
			$config['per_page'] = 100;
			$config['full_tag_open'] = '<nav aria-label="..."><ul class="pagination">';
			$config['full_tag_close'] = '</ul></nav>';
			$config['first_link'] = '&laquo; First';
			$config['first_tag_open'] = '<li class="prev paginate_button page-item">';
			$config['first_tag_close'] = '</li>';
			$config['last_link'] = 'Last &raquo;';
			$config['last_tag_open'] = '<li class="next paginate_button page-item">';
			$config['last_tag_close'] = '</li>';
			$config['next_link'] = 'Next';
			$config['next_tag_open'] = '<li class="next paginate_button page-item">';
			$config['next_tag_close'] = '</li>';
			$config['prev_link'] = 'Previous';
			$config['prev_tag_open'] = '<li class="prev paginate_button page-item">';
			$config['prev_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="paginate_button page-item active"><a href="javascript:void(0);" class="page-link">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li class="paginate_button page-item">';
			$config['reuse_query_string'] = TRUE;
			$config['num_tag_close'] = '</li>';

			$config['attributes'] = array('class' => 'page-link');
			$this->pagination->initialize($config);
			if ($offset == '') {
				$config['uri_segment'] = 3;
				$data['serial_no'] = 1;
			} else {
				$config['uri_segment'] = 3;
				$data['serial_no'] = $offset + 1;
			}
			$data['available_stock'] = array();
			$data['Utilize'] = '0';
		}
		//print_r($data);//die;
		$this->load->view('franchise/booking_master/awb_available_stock', $data);
	}

	public function getBNFCustomerRate()
	{
		ini_set('display_errors', 0);
		ini_set('display_startup_errors', 0);
		error_reporting(E_ALL);
		$sub_total = 0;
		$customer_id = $this->input->post('customer_id');
		$c_courier_id = $this->input->post('c_courier_id');
		$mode_id = $this->input->post('mode_id');
		$reciver_city = $this->input->post('city');
		$reciver_state = $this->input->post('state');
		$sender_state = $this->input->post('sender_state');
		$sender_city = $this->input->post('sender_city');
		$is_appointment = $this->input->post('is_appointment');
		$packet = $this->input->post('packet');
		$door_delivery = $this->input->post('door_delivery');
		// print_r($_POST);		die;

		$whr1 = array('state' => $sender_state, 'city' => $sender_city);
		$res1 = $this->basic_operation_m->selectRecord('region_master_details', $whr1);

		$sender_zone_id = $res1->row()->regionid;
		$reciver_zone_id = $this->input->post('receiver_zone_id');

		$doc_type = $this->input->post('doc_type');
		$actual_weight = $this->input->post('actual_weight');
		$chargable_weight = $this->input->post('chargable_weight');
		$receiver_gstno = $this->input->post('receiver_gstno');
		$booking_date = $this->input->post('booking_date');
		$invoice_value = $this->input->post('invoice_value');
		$dispatch_details = $this->input->post('dispatch_details');
		$franchise_id = $this->input->post('franchise_id');
		$current_date = date("Y-m-d", strtotime($booking_date));
		$chargable_weight = $chargable_weight * 1000;
		$fixed_perkg = 0;
		$addtional_250 = 0;
		$addtional_500 = 0;
		$addtional_1000 = 0;
		$fixed_per_kg_1000 = 0;
		$tat = 0;
		$drum_perkg = 0;
		$pickup_charges = 0;

		$where = "from_zone_id='" . $sender_zone_id . "' AND to_zone_id='" . $reciver_zone_id . "'";

		$fixed_perkg_result = $this->db->query("select * from tbl_domestic_rate_master where 
			(customer_id=" . $customer_id . " OR  customer_id=0)
			AND from_zone_id=" . $sender_zone_id . " AND to_zone_id=" . $reciver_zone_id . "
			AND (from_state_id=" . $sender_state . " OR from_state_id=0)
			AND (from_city_id=" . $sender_city . " OR  from_city_id=0)
			AND (city_id=" . $reciver_city . " OR  city_id=0)
			AND (state_id=" . $reciver_state . " OR state_id=0)
			AND (mode_id=" . $mode_id . " OR mode_id=0)
			AND DATE(`applicable_from`)<='" . $current_date . "'
			AND DATE(`applicable_to`)>='" . $current_date . "'
			AND fixed_perkg <> '6'
			AND (" . $this->input->post('actual_weight') . "
			BETWEEN weight_range_from AND weight_range_to)  
			ORDER BY state_id DESC,city_id DESC,customer_id DESC,applicable_from DESC LIMIT 1");

		$frieht = 0;
		$minimum_rate = 0;
		$query = $this->db->last_query(); //]die;
		// echo $this->db->last_query(); die;
		// echo "<pre>"; print_r($fixed_perkg_result->num_rows()); die;

		if ($fixed_perkg_result->num_rows() > 0) {

			// echo "4444uuuu<pre>";
			$rate_master = $fixed_perkg_result->result();

			// print_r($rate_master);exit();
			$minimum_rate = $rate_master[0]->minimum_rate;

			$weight_range_to = round($rate_master[0]->weight_range_to * 1000);
			$left_weight = ($chargable_weight - $weight_range_to);

			foreach ($rate_master as $key => $values) {
				$tat = $values->tat;
				$rate = $values->rate;
				$minimum_weight1 = $values->minimum_weight;
				if ($values->fixed_perkg == 0) // 250 gm slab
				{

					// $fixed_perkg = 0;
					// $addtional_250 = 0;
					// $addtional_500 = 0;
					// $addtional_1000 = 0;
					// $rate = $values->rate;
					$fixed_perkg = $values->rate;
				}

				if ($values->fixed_perkg == 1) // 250 gm slab
				{

					$slab_weight = ($values->weight_slab < $left_weight) ? $values->weight_slab : $left_weight;
					$total_slab = $slab_weight / 250;
					$addtional_250 = $addtional_250 + $total_slab * $values->rate;
					$left_weight = $left_weight - $slab_weight;
				}

				if ($values->fixed_perkg == 2) // 500 gm slab
				{
					$slab_weight = ($values->weight_slab < $left_weight) ? $values->weight_slab : $left_weight;

					if ($slab_weight < 1000) {
						if ($slab_weight <= 500) {
							$slab_weight = 500;
						} else {
							$slab_weight = 1000;
						}

					} else {
						$diff_ceil = $slab_weight % 1000;
						$slab_weight = $slab_weight - $diff_ceil;

						if ($diff_ceil <= 500 && $diff_ceil != 0) {

							$slab_weight = $slab_weight + 500;
						} elseif ($diff_ceil <= 1000 && $diff_ceil != 0) {

							$slab_weight = $slab_weight + 1000;
						}


					}

					$total_slab = $slab_weight / 500;
					$addtional_500 = $addtional_500 + $total_slab * $values->rate;
					$left_weight = $left_weight - $slab_weight;

				}

				if ($values->fixed_perkg == 3) // 1000 gm slab
				{
					$slab_weight = ($values->weight_slab < $left_weight) ? $values->weight_slab : $left_weight;
					$total_slab = ceil($slab_weight / 1000);

					$addtional_1000 = $addtional_1000 + $total_slab * $values->rate;
					$left_weight = $left_weight - $slab_weight;
				}
				// echo "hsdskjdhaskjda";exit();
				if ($values->fixed_perkg == 4 && ($this->input->post('actual_weight') >= $values->weight_range_from && $this->input->post('actual_weight') <= $values->weight_range_to)) // 1000 gm slab
				{
					// echo "hsdskjdhaskjda";exit();
					//$slab_weight = ($values->weight_slab < $left_weight)?$values->weight_slab:$left_weight;	
					$slab_weight = ($values->weight_slab < $left_weight) ? $values->weight_slab : $left_weight;
					$total_slab = ceil($chargable_weight / 1000);

					$fixed_perkg = 0;
					$addtional_250 = 0;
					$addtional_500 = 0;
					$addtional_1000 = 0;
					$rate = $values->rate;
					
					$fixed_per_kg_1000 = floatval($total_slab) * floatval($values->rate);

					$left_weight = $left_weight - $slab_weight;
				}
				
				if ($values->fixed_perkg == 5) // Box Fixed slab
				{

					$slab_weight = ($values->weight_slab < $left_weight) ? $values->weight_slab : $left_weight;
					$total_slab = $slab_weight / 250;
					$addtional_250 = $addtional_250 + $total_slab * $values->rate;
					$left_weight = $left_weight - $slab_weight;
				}

				if ($values->fixed_perkg == 6) // Per box
				{
					$slab_weight = ($values->weight_slab < $left_weight) ? $values->weight_slab : $left_weight;
					$total_slab = ceil($chargable_weight / 1000);

					$fixed_perkg = 0;
					$addtional_250 = 0;
					$addtional_500 = 0;
					$addtional_1000 = 0;
					$rate = $values->rate;
					
					$fixed_per_kg_1000 = 0;
					$drum_perkg = $packet * $values->rate;
					$left_weight = $left_weight - $slab_weight;
				}

				if ($values->fixed_perkg == 7) // Drum fixed slab
				{

					$slab_weight = ($values->weight_slab < $left_weight) ? $values->weight_slab : $left_weight;
					$total_slab = $slab_weight / 250;
					$addtional_250 = $addtional_250 + $total_slab * $values->rate;
					$left_weight = $left_weight - $slab_weight;
				}

				if ($values->fixed_perkg == 8) // 1000 gm slab
				{
					$slab_weight = ($values->weight_slab < $left_weight) ? $values->weight_slab : $left_weight;
					$total_slab = ceil($chargable_weight / 1000);

					$fixed_perkg = 0;
					$addtional_250 = 0;
					$addtional_500 = 0;
					$addtional_1000 = 0;
					$rate = $values->rate;
					$fixed_per_kg_1000 = 0;
					$drum_perkg = $packet * $values->rate;
					$left_weight = $left_weight - $slab_weight;
				}
			}

		}
		// print_r($drum_perkg);die;

		$frieht1 = $fixed_perkg + $addtional_250 + $addtional_500 + $addtional_1000 + $fixed_per_kg_1000 + $drum_perkg;
		if($minimum_rate>$frieht1)
		{
			$frieht = $minimum_rate;
		}else{
			$frieht = $frieht1;
		}
		$amount = $frieht;
		
	$commision_id = $this->db->query("SELECT * FROM tbl_franchise WHERE fid ='$franchise_id'")->row('commision_id');		
			if($commision_id !=0){
				$commision_master = $this->basic_operation_m->get_table_row('tbl_comission_master', ['is_deleted'=>0,'group_id'=>$commision_id]);
				
				if(!empty($commision_master)){
					 $booking_commsion = $commision_master->booking_commission;
					 $delivery_commission = $commision_master->delivery_commission;
					 $booking_charges =  ($frieht * $booking_commsion / 100);
					 $delivery_charges =  ($frieht * $delivery_commission / 100);
					
					 if($door_delivery=='1'){
						$door_delivery_share = $commision_master->door_delivery_share;
						$door_delivery_charges =  ($frieht * $door_delivery_share / 100);
					 }else{
						$door_delivery_share =0;
						$door_delivery_charges =  0.00;
					 }
				}
			}

		//	$whr1 = array('courier_id' => $c_courier_id);
		$whr1 = array('courier_id' => $c_courier_id, 'fuel_from <=' => $current_date, 'fuel_to >=' => $current_date, 'customer_id =' => $customer_id);
		$res1 = $this->basic_operation_m->get_table_row('courier_fuel', $whr1);
        // echo $this->db->last_query();die;
	
		$fovExpiry = "";
		if ($res1) {
			$fuel_per = $res1->fuel_price;
			$fov = $res1->fov_min;
			$docket_charge = $res1->docket_charge;
			$fov_base = $res1->fov_base;
			$fov_min = $res1->fov_min;

			// echo "<pre>";
			// print_r($res1);exit();

			if ($dispatch_details != 'Cash' && $dispatch_details != 'COD') {
				$res1->cod = 0;
			}
			$appt_charges = 0;
			if ($is_appointment == 1) {
				// $res1->appointment_perkg 
				$appt_charges = ($res1->appointment_perkg * $this->input->post('chargable_weight'));

				if ($res1->appointment_min > $appt_charges) {
					$appt_charges = $res1->appointment_min;
				}
			}
			// print_r($appt_charges);die;

			if ($dispatch_details != 'ToPay') {
				$res1->to_pay_charges = 0;
			}

		

			if ($invoice_value >= $fov_base) {
				$fov = (($invoice_value / 100) * $res1->fov_above);
			} elseif ($invoice_value < $res1->fov_base) {
				$fov = (($invoice_value / 100) * $res1->fov_below);
			}

			if ($fov < $fov_min) {
				$fov = $fov_min;
			}

			if ($dispatch_details == 'COD') {
				if ($res1->cod != 0) {
					$cod_detail_Range = $this->basic_operation_m->get_query_row("select * from courier_fuel_detail  where cf_id = '$res1->cf_id' and ('$invoice_value' BETWEEN cod_range_from and cod_range_to)");
					if (!empty($cod_detail_Range)) {
						$res1->cod = ($invoice_value * $cod_detail_Range->cod_range_rate / 100);
					}
				}

			} else {
				$res1->cod = 0;
			}

			if ($dispatch_details == 'ToPay') {

				$to_pay_charges_Range = $this->basic_operation_m->get_query_row("select * from courier_fuel_detail  where cf_id = '$res1->cf_id' and ('$invoice_value' BETWEEN topay_range_from and topay_range_to)");
				// echo $this->db->last_query();die;
				if (!empty($to_pay_charges_Range)) {
					$res1->to_pay_charges = ($invoice_value * $to_pay_charges_Range->topay_range_rate / 100);
				}
				// print_r($res1->to_pay_charges);die;
			} else {
				$res1->to_pay_charges = 0;
			}


			$to_pay_charges = $res1->to_pay_charges;

			if ($res1->fc_type == 'freight') {
				$amount = $amount + $fov + $docket_charge + $res1->cod + $res1->to_pay_charges + $appt_charges;
				$final_fuel_charges = ($amount * $fuel_per / 100);
			
			} else {
				$amount = $amount + $fov + $docket_charge + $res1->cod + $res1->to_pay_charges + $appt_charges;
				$final_fuel_charges = ($amount * $fuel_per / 100);
			}
			$cft = $res1->cft;
			$cod = $res1->cod;
		} else {
			$fovExpiry = "VAS expired or not defined!";
			$cft = '0';
			$cod = '0';
			$fov = '0';
			$to_pay_charges = '0';
			$appt_charges = '0';
			$fuel_per = '0';
			$docket_charge = '0';
			$amount = $amount + $fov + $docket_charge + $cod + $to_pay_charges + $appt_charges;
			$final_fuel_charges = ($amount * $fuel_per / 100);
		}

		//Cash


		$sub_total = ($amount + $final_fuel_charges);
		$isMinimumValue = "";
		if ($minimum_rate > $sub_total) {
			$sub_total = $minimum_rate;
			$isMinimumValue = "minimum value apply";
		}

		if ($dispatch_details == 'Cash') {
			$username = $this->session->userdata("userName");
			$whr11 = array('username' => $username);
			$res11 = $this->basic_operation_m->getAll('tbl_users', $whr11);
			$branch_id = $res11->row()->branch_id;

			$branch_info = $this->db->get_where('tbl_branch', ['branch_id' => $branch_id])->row();

			$state_info = $this->db->get_where('state', ['id' => $sender_state])->row();

			$first_two_char_branch = substr(trim($branch_info->gst_number), 0, 2);
			// print_r($first_two_char_branch);die;
			if ($first_two_char_branch == $state_info->statecode) {
				$cgst = ($sub_total * 9 / 100);
				$sgst = ($sub_total * 9 / 100);
				$igst = 0;
				$grand_total = $sub_total + $cgst + $sgst + $igst;
			} else {
				$cgst = 0;
				$sgst = 0;
				$igst = ($sub_total * 18 / 100);
				$grand_total = $sub_total + $igst;
			}
		} else {
			$first_two_char = substr($receiver_gstno, 0, 2);

			if ($receiver_gstno == "") {
				$first_two_char = 27;
			}

			$tbl_customers_info = $this->basic_operation_m->get_query_row("select gst_charges from tbl_customers where customer_id = '$customer_id' and isdeleted  = '0'");

			if ($tbl_customers_info->gst_charges == 1) {
				if ($first_two_char == 27) {
					$cgst = ($sub_total * 9 / 100);
					$sgst = ($sub_total * 9 / 100);
					$igst = 0;
					$grand_total = $sub_total + $cgst + $sgst + $igst;
				} else {
					$cgst = 0;
					$sgst = 0;
					$igst = ($sub_total * 18 / 100);
					$grand_total = $sub_total + $igst;
				}
			} else {
				$cgst = 0;
				$sgst = 0;
				$igst = 0;
				$grand_total = $sub_total + $igst;
			}
		}
		if ($tat > 0) {
			$tat_date = date('Y-m-d', strtotime($booking_date . " + $tat days"));
		} else {
			$tat_date = date('Y-m-d', strtotime($booking_date . " + 5 days"));
		}
		$data = array(
			//'query' => $query,
			'sender_zone_id' => $sender_zone_id,
			'rate' => $rate,
			'reciver_zone_id' => $reciver_zone_id,
			'min_weight' => $minimum_weight1,
			'chargable_weight' => $chargable_weight,
			'booking_commsion' => $booking_commsion,
			'delivery_commission' => $delivery_commission,
			'door_delivery_share' => $door_delivery_share,
			'door_delivery_charges' => round($door_delivery_charges, 2),
			'delivery_charges' => round($delivery_charges, 2),
			'booking_charges' => round($booking_charges, 2),
			'frieht' => round($frieht, 2),
			'fov' => round($fov, 2),
			'appt_charges' => round($appt_charges, 2),
			'docket_charge' => round($docket_charge, 2),
			'amount' => round($amount, 2),
			'cod' => round($cod, 2),
			'cft' => round($cft, 2),
			'to_pay_charges' => round($to_pay_charges, 2),
			'final_fuel_charges' => round($final_fuel_charges, 2),
			'sub_total' => number_format($sub_total, 2, '.', ''),
			'cgst' => number_format($cgst, 2, '.', ''),
			'sgst' => number_format($sgst, 2, '.', ''),
			'igst' => number_format($igst, 2, '.', ''),
			'grand_total' => number_format($grand_total, 2, '.', ''),
			'isMinimumValue' => $isMinimumValue,
			'fovExpiry' => $fovExpiry,
		);
		echo json_encode($data);
		exit;
	}

	public function get_perbox_rate()
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);

		$sub_total = 0;
		$customer_id = $this->input->post('customer_id');
		$c_courier_id = $this->input->post('c_courier_id');
		$mode_id = $this->input->post('mode_id');
		$reciver_city = $this->input->post('city');
		$reciver_state = $this->input->post('state');
		$sender_state = $this->input->post('sender_state');
		$sender_city = $this->input->post('sender_city');
		$is_appointment = $this->input->post('is_appointment');
		$packet = $this->input->post('packet');
		$door_delivery = $this->input->post('door_delivery');
		$actual_weight = $this->input->post('actual_weight');
		$whr1 = array('state' => $sender_state, 'city' => $sender_city);
		$res1 = $this->basic_operation_m->selectRecord('region_master_details', $whr1);
		$sender_zone_id = $res1->row()->regionid;
		$reciver_zone_id = $this->input->post('receiver_zone_id');
		$doc_type = $this->input->post('doc_type');
		$chargable_weight = $this->input->post('chargable_weight');
		$chargable_weight1 = $this->input->post('chargable_weight');
		$receiver_gstno = $this->input->post('receiver_gstno');
		$booking_date = $this->input->post('booking_date');
		$invoice_value = $this->input->post('invoice_value');
		$dispatch_details = $this->input->post('dispatch_details');
		$per_box = $this->input->post('per_box');
		$perBox_actual = $this->input->post('perBox_actual');
		$franchise_id = $this->input->post('franchise_id');
	
		$current_date = date("Y-m-d", strtotime($booking_date));
		$chargable_weight = $chargable_weight * 1000;
		$fixed_perkg = 0;
		$addtional_250 = 0;
		$addtional_500 = 0;
		$addtional_1000 = 0;
		$fixed_per_kg_1000 = 0;
		$tat = 0;
		$pickup = 0;
		$drum_perkg = 0;
		$actual_weight_exp = explode(',', $perBox_actual);
		$per_box_exp = explode(',', $per_box);
		$rate_all = [];
		$not_d_rate = [];
		for ($i = 0; $i <= count($actual_weight_exp); $i++) {
			if (!empty($actual_weight_exp[$i]) && !empty($per_box_exp[$i])) {
				$weight = $actual_weight_exp[$i] / $per_box_exp[$i];
				$where = "from_zone_id='" . $sender_zone_id . "' AND to_zone_id='" . $reciver_zone_id . "'";
				$fixed_perkg_result = $this->db->query("select * from tbl_domestic_rate_master where 
						(customer_id='$customer_id' OR  customer_id=0)
						AND from_zone_id='$sender_zone_id' AND to_zone_id='$reciver_zone_id'
						AND (from_state_id='$sender_state' OR from_state_id=0)
						AND (from_city_id='$sender_city' OR  from_city_id=0)
						AND (city_id='$reciver_city' OR  city_id=0)
						AND (state_id='$reciver_state' || state_id=0)
						AND (mode_id='$mode_id' || mode_id=0)
						AND DATE(`applicable_from`)<='$current_date'
						AND DATE(`applicable_to`)>='$current_date'
						AND fixed_perkg = '6'
						AND ($weight
						BETWEEN weight_range_from AND weight_range_to)  
						ORDER BY state_id DESC,city_id DESC,customer_id DESC,applicable_from DESC LIMIT 1");
				$values = $fixed_perkg_result->row();
				// echo $this->db->last_query();die;
				if ($fixed_perkg_result->num_rows() == 0) {
					$not_d_rate[] = +$weight;
				}
				if (!empty($values->rate)) {
					$rate_all[] = +$values->rate;
					$min_fright[] = +$values->minimum_rate;
					$minimum_rate = $values->minimum_rate;
					$pickup = $values->pickup_charges;
				}
			}
		}
		$fright = [];
		$pack = array_values(array_filter($per_box_exp));
		foreach ($pack as $key1 => $weight) {
			foreach ($rate_all as $key => $rate_val) {
				if ($key1 == $key) {
					$fright2[] = +$rate_all[$key] * $pack[$key];
				}
			}
		}
		
		$frieht1 = array_sum($fright2);
		$value = max($min_fright);
        if($frieht1>$value)
		{
			$frieht = $frieht1;
			$amount =$frieht1;
		    $rate = $frieht1;
		}
		else
		{
			$frieht = $value;
			$amount =$value;
		    $rate = $value;
		}
		$commision_id = $this->db->query("SELECT * FROM tbl_franchise WHERE fid ='$franchise_id'")->row('commision_id');		
		if($commision_id !=0){
			$commision_master = $this->basic_operation_m->get_table_row('tbl_comission_master', ['is_deleted'=>0,'group_id'=>$commision_id]);
			
			if(!empty($commision_master)){
				 $booking_commsion = $commision_master->booking_commission;
				 $delivery_commission = $commision_master->delivery_commission;
				 $booking_charges =  ($frieht * $booking_commsion / 100);
				 $delivery_charges =  ($frieht * $delivery_commission / 100);
				
				 if($door_delivery=='1'){
					$door_delivery_share = $commision_master->door_delivery_share;
					$door_delivery_charges =  ($frieht * $door_delivery_share / 100);
				 }else{
					$door_delivery_share =0;
					$door_delivery_charges =  0.00;
				 }
			}
		}
		$whr1 = array('courier_id' => $c_courier_id, 'fuel_from <=' => $current_date, 'fuel_to >=' => $current_date, 'customer_id =' => $customer_id);
		$res1 = $this->basic_operation_m->get_table_row('courier_fuel', $whr1);	
		$fovExpiry = "";
		if ($res1) {
			$fuel_per = $res1->fuel_price;
			$fov = $res1->fov_min;
			$docket_charge = $res1->docket_charge;
			$fov_base = $res1->fov_base;
			$fov_min = $res1->fov_min;

			if ($dispatch_details != 'Cash' && $dispatch_details != 'COD') {
				$res1->cod = 0;
			}
			$appt_charges = 0;
			if ($is_appointment == 1) {
				// $res1->appointment_perkg 
				$appt_charges = ($res1->appointment_perkg * $this->input->post('chargable_weight'));

				if ($res1->appointment_min > $appt_charges) {
					$appt_charges = $res1->appointment_min;
				}
			}
			

			if ($dispatch_details != 'ToPay') {
				$res1->to_pay_charges = 0;
			}

			if ($invoice_value >= $fov_base) {
				$fov = (($invoice_value / 100) * $res1->fov_above);
			} elseif ($invoice_value < $res1->fov_base) {
				$fov = (($invoice_value / 100) * $res1->fov_below);
			}

			if ($fov < $fov_min) {
				$fov = $fov_min;
			}

			if ($dispatch_details == 'COD') {
				if ($res1->cod != 0) {
					$cod_detail_Range = $this->basic_operation_m->get_query_row("select * from courier_fuel_detail  where cf_id = '$res1->cf_id' and ('$invoice_value' BETWEEN cod_range_from and cod_range_to)");
					if (!empty($cod_detail_Range)) {
						$res1->cod = ($invoice_value * $cod_detail_Range->cod_range_rate / 100);
					}
				}

			} else {
				$res1->cod = 0;
			}

			if ($dispatch_details == 'ToPay') {

				$to_pay_charges_Range = $this->basic_operation_m->get_query_row("select * from courier_fuel_detail  where cf_id = '$res1->cf_id' and ('$invoice_value' BETWEEN topay_range_from and topay_range_to)");
				if (!empty($to_pay_charges_Range)) {
					$res1->to_pay_charges = ($invoice_value * $to_pay_charges_Range->topay_range_rate / 100);
				}
			} else {
				$res1->to_pay_charges = 0;
			}
			$to_pay_charges = $res1->to_pay_charges;
			if ($res1->fc_type == 'freight') {
				$final_fuel_charges = ($amount * $fuel_per / 100);
				$amount = $amount + $fov + $docket_charge + $res1->cod + $res1->to_pay_charges + $appt_charges;
			} else {
				$amount = $amount + $fov + $docket_charge + $res1->cod + $res1->to_pay_charges + $appt_charges;
				$final_fuel_charges = ($amount * $fuel_per / 100);
			}
			$cft = $res1->cft;
			$cod = $res1->cod;
		} else {
			$fovExpiry = "VAS expired or not defined!";
			$cft = '0';
			$cod = '0';
			$fov = '0';
			$to_pay_charges = '0';
			$appt_charges = '0';
			$fuel_per = '0';
			$docket_charge = '0';
			$amount = $amount + $fov + $docket_charge + $cod + $to_pay_charges + $appt_charges;
			$final_fuel_charges = ($amount * $fuel_per / 100);
		}
		$sub_total = ($amount + $final_fuel_charges);
		$isMinimumValue = "";
		if ($minimum_rate > $sub_total) {
			$sub_total = $minimum_rate;
			$isMinimumValue = "minimum value apply";
		}
		if ($dispatch_details == 'Cash') {
			$username = $this->session->userdata("userName");
			$whr11 = array('username' => $username);
			$res11 = $this->basic_operation_m->getAll('tbl_users', $whr11);
			$branch_id = $res11->row()->branch_id;

			$branch_info = $this->db->get_where('tbl_branch', ['branch_id' => $branch_id])->row();

			$state_info = $this->db->get_where('state', ['id' => $sender_state])->row();

			$first_two_char_branch = substr(trim($branch_info->gst_number), 0, 2);
			if ($first_two_char_branch == $state_info->statecode) {
				$cgst = ($sub_total * 9 / 100);
				$sgst = ($sub_total * 9 / 100);
				$igst = 0;
				$grand_total = $sub_total + $cgst + $sgst + $igst;
			} else {
				$cgst = 0;
				$sgst = 0;
				$igst = ($sub_total * 18 / 100);
				$grand_total = $sub_total + $igst;
			}
		} else {
			$first_two_char = substr($receiver_gstno, 0, 2);

			if ($receiver_gstno == "") {
				$first_two_char = 27;
			}
			$tbl_customers_info = $this->basic_operation_m->get_query_row("select gst_charges from tbl_customers where customer_id = '$customer_id' and isdeleted  = '0' ");
			if ($tbl_customers_info->gst_charges == 1) {
				if ($first_two_char == 27) {
					$cgst = ($sub_total * 9 / 100);
					$sgst = ($sub_total * 9 / 100);
					$igst = 0;
					$grand_total = $sub_total + $cgst + $sgst + $igst;
				} else {
					$cgst = 0;
					$sgst = 0;
					$igst = ($sub_total * 18 / 100);
					$grand_total = $sub_total + $igst;
				}
			} else {
				$cgst = 0;
				$sgst = 0;
				$igst = 0;
				$grand_total = $sub_total + $igst;
			}
		}
		if ($tat > 0) {
			$tat_date = date('Y-m-d', strtotime($booking_date . " + $tat days"));
		} else {
			$tat_date = date('Y-m-d', strtotime($booking_date . " + 5 days"));
		}
		if (!empty($rate)) {
			$data = array(
				//'query' => $query,
				'sender_zone_id' => $sender_zone_id,
				'rate' => $rate,
				'reciver_zone_id' => $reciver_zone_id,
				'min_weight' => $minimum_rate,
				'chargable_weight' => $chargable_weight,
				'booking_commsion' => $booking_commsion,
				'delivery_commission' => $delivery_commission,
				'door_delivery_share' => $door_delivery_share,
				'door_delivery_charges' => round($door_delivery_charges, 2),
				'delivery_charges' => round($delivery_charges, 2),
				'booking_charges' => round($booking_charges, 2),
				'frieht' => round($frieht, 2),
				'fov' => round($fov, 2),
				'appt_charges' => round($appt_charges, 2),
				'docket_charge' => round($docket_charge, 2),
				'amount' => round($amount, 2),
				'cod' => round($cod, 2),
				'cft' => round($cft, 2),
				'to_pay_charges' => round($to_pay_charges, 2),
				'final_fuel_charges' => round($final_fuel_charges, 2),
				'sub_total' => number_format($sub_total, 2, '.', ''),
				'cgst' => number_format($cgst, 2, '.', ''),
				'sgst' => number_format($sgst, 2, '.', ''),
				'igst' => number_format($igst, 2, '.', ''),
				'grand_total' => number_format($grand_total, 2, '.', ''),
				'isMinimumValue' => $isMinimumValue,
				'fovExpiry' => $fovExpiry,
				'Message' => '',
			);

			if (!empty($not_d_rate)) {
				$rate = implode(" ", $not_d_rate);
				$data['rate_message'] = 'This Weight detials are rate not defined ' . $rate;
			} else {
				$data['rate_message'] = '';
			}
		}		
		echo json_encode($data);
		exit;
	}

}