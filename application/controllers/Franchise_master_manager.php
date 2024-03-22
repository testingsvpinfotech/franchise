<?php
ini_set('display_errors', 1);


defined('BASEPATH') or exit('No direct script access allowed');

class Franchise_master_manager extends CI_Controller
{
	var $data = array();
	function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
		$this->load->model('basic_operation_m');
		$this->data['company_info']	= $this->basic_operation_m->get_query_row("select * from tbl_company limit 1");
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
			$this->load->view('masterfranchise/booking_master/print_shipment_franchise', $data);
		}
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
		$this->load->view('masterfranchise/booking_master/domestic_printlabel', $data);
	}

	public  function franchise_dashboard()
	{  
		$data = $this->data;
		$date =  date('y-m-d');
		$customer_id = $this->session->userdata("customer_id");
		$dd2 =	$this->db->query("SELECT tbl_domestic_booking.*,transfer_mode.mode_name as mode_dispatch  FROM `tbl_domestic_booking`LEFT JOIN transfer_mode ON tbl_domestic_booking.mode_dispatch =transfer_mode.transfer_mode_id  WHERE booking_type = 0 AND `customer_id`= $customer_id")->result();
		$data['total_booking_shipment'] = $this->db->query("SELECT COUNT(booking_id) AS total_shipment_booking FROM tbl_domestic_booking WHERE customer_id = '$customer_id'")->row();
		$data['total_delivered_shipment'] = $this->db->query("SELECT COUNT(booking_id) AS total_delivered_shipment FROM `tbl_domestic_booking` WHERE is_delhivery_complete = '1' AND customer_id = '$customer_id' AND MONTH(booking_date) = MONTH(CURRENT_DATE())")->row();
	    $data['total_pending_shipment'] = $this->db->query("SELECT COUNT(booking_id) AS total_pending_booking FROM `tbl_domestic_booking` join tbl_domestic_deliverysheet ON tbl_domestic_deliverysheet.pod_no = tbl_domestic_booking.pod_no WHERE tbl_domestic_booking.booking_type = '0' AND tbl_domestic_booking.user_id = '$customer_id' AND is_delhivery_complete = '0' AND MONTH(booking_date) = MONTH(CURRENT_DATE())")->row();
	    $data['today_pending_shipment'] = $this->db->query("SELECT COUNT(booking_id) AS today_pending_shipment FROM `tbl_domestic_booking` join tbl_domestic_deliverysheet ON tbl_domestic_deliverysheet.pod_no = tbl_domestic_booking.pod_no WHERE tbl_domestic_booking.booking_type = '0' AND tbl_domestic_booking.user_id = '$customer_id' AND is_delhivery_complete = '0' AND booking_date = '$date'")->row();
		$data['today_total_booking_shipment'] = $this->db->query("SELECT COUNT(booking_id) AS today_total_shipment_booking FROM `tbl_domestic_booking` WHERE  customer_id = '$customer_id' AND booking_date = '$date'")->row();

		$this->load->view('masterfranchise/dashboard', $data);
	}

	public function today_shipment_list(){
		$data = $this->data;
		$date =  date('y-m-d');
		$customer_id = $this->session->userdata("customer_id");
		$data['shipment_list'] = $this->db->query("SELECT * FROM `tbl_domestic_booking` WHERE  customer_id = '$customer_id' AND booking_date = '$date'")->result();
		$this->load->view('masterfranchise/booking_master/today_shipment_list', $data);
	}
	public function month_pending_list(){
		$data = $this->data;
		$date =  date('y-m-d');
		$customer_id = $this->session->userdata("customer_id");
		$data['shipment_list'] = $this->db->query("SELECT * FROM `tbl_domestic_booking` join tbl_domestic_deliverysheet ON tbl_domestic_deliverysheet.pod_no = tbl_domestic_booking.pod_no WHERE tbl_domestic_booking.booking_type = '0' AND tbl_domestic_booking.user_id = '$customer_id' AND is_delhivery_complete = '0' AND MONTH(booking_date) = MONTH(CURRENT_DATE())")->result();
		$this->load->view('masterfranchise/booking_master/month_pending_list', $data);
	}
	public function today_pending_list(){
		$data = $this->data;
		$date =  date('y-m-d');
		$customer_id = $this->session->userdata("customer_id");
		$data['shipment_list'] = $this->db->query("SELECT * FROM `tbl_domestic_booking` join tbl_domestic_deliverysheet ON tbl_domestic_deliverysheet.pod_no = tbl_domestic_booking.pod_no WHERE tbl_domestic_booking.booking_type = '0' AND tbl_domestic_booking.user_id = '$customer_id' AND is_delhivery_complete = '0' AND booking_date = '$date'")->result();
		$this->load->view('masterfranchise/booking_master/today_pending_list', $data);
	}
	public function delivered_shipment_list(){
		$data = $this->data;
		$date =  date('y-m-d');
		$customer_id = $this->session->userdata("customer_id");
		$data['shipment_list'] = $this->db->query(" SELECT * FROM `tbl_domestic_booking` WHERE is_delhivery_complete = '1' AND customer_id = '$customer_id' AND MONTH(booking_date) = MONTH(CURRENT_DATE())")->result();
		// echo $this->db->last_query();die;
		$this->load->view('masterfranchise/booking_master/delivered_shipment_list', $data);
	}



	public function getCityList()
	{
		$pincode = $this->input->post('pincode');
		$whr1 = array('pin_code' => $pincode);
		$res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);

		$city_id = $res1->row()->city_id;
		$city_id = $res1->row()->city_id;

		$whr2 = array('id' => $city_id);
		$res2 = $this->basic_operation_m->selectRecord('city', $whr2);
		$result2 = $res2->row();
		$state_id = $res2->row()->state_id;


		$whr1 = array('state' => $state_id, 'city' => $city_id);
		$res1 = $this->basic_operation_m->selectRecord('region_master_details', $whr1);

		$regionid = @$res1->row()->regionid;
		$result2->regionid = $regionid;
		

		echo json_encode($result2);
	}

	public function getState()
	{
		$pincode = $this->input->post('pincode');
		$whr1 = array('pin_code' => $pincode);
		$res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);

		$state_id = $res1->row()->state_id;
		$whr3 = array('id' => $state_id);
		$res3 = $this->basic_operation_m->selectRecord('state', $whr3);
		$result3 = $res3->row();

		echo json_encode($result3);
	}


	public function getZone()
	{
		$reciever_state = $this->input->post('reciever_state');
		$reciever_city =  $this->input->post('reciever_city');

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

		$pod_no = $result->pod_no;
		if ($pod_no != "") {
			$data['msg'] = "Forwording number is duplicate ";
		} else {
			$data['msg'] = "";
		}

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

		//   print_r($_POST);die;
		//print
		//print_r($customer_id);
		$get_fuel_id = $this->db->query("select * from franchise_delivery_tbl where delivery_franchise_id = '$customer_id'")->row();
		$dd =   $get_fuel_id->fule_group;
		$get_fuel_details = $this->db->query("select * from franchise_fule_tbl where group_id = '$dd'")->row();

		//print_r($get_fuel_details);exit; from_date

		$current_date = date("Y-m-d", strtotime($booking_date));

		$whr1 = array('from_date <=' => $current_date, 'to_date >=' => $current_date, 'group_id' => $dd);
		$res1 = $this->db->query("select * from franchise_fule_tbl where from_date <='$current_date' AND to_date >='$current_date' AND group_id = '$dd' ")->row();
		//echo $this->db->last_query();exit;

		//print_r($res1);


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



		$tbl_customers_info 		= $this->basic_operation_m->get_query_row("select gst_charges from tbl_customers where customer_id = '$customer_id'");

		if ($tbl_customers_info->gst_charges == 1) {
			$cgst = ($sub_total * $cgst_per / 100);
			$sgst = ($sub_total * $sgst_per / 100);
			$igst = 0;
		} else {
			$cgst = 0;
			$sgst = 0;
			$igst = 0;
		}

		if ($dispatch_details == 'Cash') {
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

	public function add_shipment()
	{

		$all_Data 	= $this->input->post();
		// echo "<pre>";
		// print_r($all_Data);exit();


		if (!empty($all_Data)) {


			$user_id = $this->session->userdata("customer_id");
			$gat_area = $this->db->query("select cmp_area from tbl_franchise where fid = '$user_id'")->row();
			$area = $gat_area->cmp_area;
			$cutomer = $this->session->userdata("customer_name");
			$branch = $this->session->userdata("branch_name");

			// $branch_name = $branch . " " .$area. "Franchise";
			$branch_name = $branch . "_" . $area;


			//print_r($branch_name);
			//print_r($this->session->all_userdata());
			//exit;




			$user_type = $this->session->userdata("customer_type");

			$balance = $this->db->query("Select * from tbl_customers where customer_id = '$user_id'")->row();
			$amount = $balance->wallet;
			$update_val = $amount - $this->input->post('grand_total');

			if ($update_val < 0) {
				$msg            = 'You Dont Have sufficient Balance!';
				$class            = 'alert alert-danger alert-dismissible';
				$this->session->set_flashdata('notify', $msg);
				$this->session->set_flashdata('class', $class);


				redirect('franchise/shipment-list');
			}

			$date = date('Y-m-d', strtotime($this->input->post('booking_date')));
			$this->session->unset_userdata("booking_date");
			$this->session->set_userdata("booking_date", $this->input->post('booking_date'));

			$whr = array('customer_id' => $user_id);
			$res = $this->basic_operation_m->getAll('tbl_customers', $whr);
			$branch_id = $res->row()->branch_id;
            $awb = $this->input->post('awn');


			// $branch_id =23;

			if ($all_Data['doc_type'] == 0) {
				$doc_nondoc			= 'Document';
			} else {
				$doc_nondoc			= 'Non Document';
			}


			$pickup_charges =  $this->input->post('pickup_charges');
			if (empty($pickup_charges)) {
				$pickup_charges = 0;
			}
			$green_tax =  $this->input->post('green_tax');
			if (empty($green_tax)) {
				$green_tax = 0;
			}
			$appt_charges =  $this->input->post('appt_charges');
			if (empty($appt_charges)) {
				$appt_charges = 0;
			}
			$insurance_charges =  $this->input->post('insurance_charges');
			if (empty($insurance_charges)) {
				$insurance_charges = 0;
			}
			$transportation_charges =  $this->input->post('transportation_charges');
			if (empty($transportation_charges)) {
				$transportation_charges = 0;
			}
			$other_charges =  $this->input->post('other_charges');
			if (empty($other_charges)) {
				$other_charges = 0;
			}

			$data = array(
				'doc_type' => $this->input->post('doc_type'),
				'doc_nondoc' => $doc_nondoc,
				'courier_company_id' => $this->input->post('courier_company'),
				'company_type' => 'Domestic',
				'mode_dispatch' => $this->input->post('mode_dispatch'),
				'pod_no' => $this->input->post('awn'),
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
				'transportation_charges' => $transportation_charges,
				'insurance_charges' => $insurance_charges,
				'pickup_charges' => $pickup_charges,
				'delivery_charges' => $this->input->post('delivery_charges'),
				'courier_charges' => $this->input->post('courier_charges'),
				'awb_charges' => $this->input->post('awb_charges'),
				'other_charges' => $other_charges,
				'total_amount' => $this->input->post('amount'),
				'fuel_subcharges' => $this->input->post('fuel_subcharges'),
				'sub_total' => $this->input->post('sub_total'),
				'cgst' => $this->input->post('cgst'),
				'sgst' => $this->input->post('sgst'),
				'igst' => $this->input->post('igst'),
				'green_tax' => $green_tax,
				'appt_charges' => $appt_charges,
				'grand_total' => $this->input->post('grand_total'),
				'user_id' => $user_id,
				'user_type' => $user_type,
				'branch_id' => $branch_id,
				'booking_type' => 1


			);



		  //echo '<pre>'; print_r($data);exit;

			$result = $this->db->insert('tbl_domestic_booking', $data);
			 
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

				 	// echo "<pre>";print_r($data2);
				// 	exit();

				$query2 = $this->basic_operation_m->insert('tbl_domestic_weight_details', $data2);
				// echo $this->db->last_query();exit;
				$username = $this->session->userdata("customer_id");
				// $whr = array('customer_id' => $username);
				// $res = $this->basic_operation_m->getAll('tbl_customers', $whr);
				// $branch_id = $res->row()->city;

				// $whr = array('id' => $branch_id);
				// $res = $this->basic_operation_m->getAll('city', $whr);
				// $branch_name = $res->row()->city;
				//	print_r($branch_id);die;



				$whr = array('booking_id' => $lastid);
				$res = $this->basic_operation_m->getAll('tbl_domestic_booking', $whr);
				$podno = $res->row()->pod_no;
				$customerid = $res->row()->customer_id;
				$data3 = array(
					'id' => '',
					'pod_no' => $podno,
					'status' => 'Booked',
					'branch_name' => $branch_name,
					'tracking_date' => $this->input->post('booking_date'),
					'booking_id' => $lastid,
					'forworder_name' => $data['forworder_name'],
					'forwording_no' => $data['forwording_no'],
					'is_spoton' => ($data['forworder_name'] == 'spoton_service') ? 1 : 0,
					'is_delhivery_b2b' => ($data['forworder_name'] == 'delhivery_b2b') ? 1 : 0,
					'is_delhivery_c2c' => ($data['forworder_name'] == 'delhivery_c2c') ? 1 : 0
				);
				// print_r($data3);
				// exit;

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
                $payment_mode = 'Credit';
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
				//print_r($update_val);exit;

				$data9 = array(

					'franchise_id' =>$franchise_id1,
					'customer_id' =>$user_id,
					'transaction_id' =>$franchise_id,
					'payment_date' => $date,
					'debit_amount' =>$g_total,
				    'balance_amount' =>$update_val,
					'payment_mode' =>$payment_mode,
					'bank_name' =>$bank_name,
					'status' => 1,
					'refrence_no' =>$awb
				);

				//echo '<pre>'; print_r($data9);exit;

				$result =  $this->db->insert('franchise_topup_balance_tbl', $data9);

				}

				
				$msg = 'Your Shipment ' . $podno . ' status:Boked  At Location: ' . $branch_name;
				$class            = 'alert alert-success alert-dismissible';
			} else {
				$msg            = 'Shipment not added successfully';
				$class            = 'alert alert-danger alert-dismissible';
			}
			$this->session->set_flashdata('notify', $msg);
			$this->session->set_flashdata('class', $class);


			redirect('franchise/shipment-list');
		} else {




			$data			= array();

			$result 		= $this->db->query('select max(booking_id) AS id from tbl_domestic_booking')->row();
			$id 			= $result->id + 1;
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


			$data['transfer_mode']		 	= $this->basic_operation_m->get_query_result('select * from `transfer_mode`');

			$customer_id 	= $this->session->userdata("customer_id");
			$data['cities']	= $this->basic_operation_m->get_all_result('city', '');
			$data['states'] = $this->basic_operation_m->get_all_result('state', '');

			$data['customers'] = $this->basic_operation_m->get_all_result('tbl_customers', "parent_cust_id = '$customer_id'");

			$data['payment_method']  = $this->basic_operation_m->get_all_result('payment_method', '');
			$data['region_master'] = $this->basic_operation_m->get_all_result('region_master', '');
			$data['bid'] = $id;
			$whr_d = array("company_type" => "Domestic");
			$data['courier_company'] = $this->basic_operation_m->get_all_result("courier_company", $whr_d);
			$data['bid'] 					= $id;
			$this->load->view('masterfranchise/booking_master/add_shipment', $data);
		}
	}

	public function cancel_shipment()
	{

		$booking_id = $this->input->post('booking_id');
		$cancel_msg = $this->input->post('cancel_msg');

		$data = array(

			'booking_type' => 0,
			'booking_cancel_msg' => $cancel_msg
		);

		$this->db->where('booking_id', $booking_id);
		$query =  $this->db->update('tbl_domestic_booking', $data);


		if (!empty($query)) {
			$output['status'] = 'success';
			$output['message'] = 'Data Cancel successfully';
		} else {
			$output['status'] = 'error';
			$output['message'] = 'Something went wrong in Cancel the Data';
		}

		echo json_encode($output);
	}
	


	public function cancel_shipment_list()
	{
		$user_id = $this->session->userdata("customer_id");
		$data['shipment_list'] = $this->db->query("SELECT tbl_domestic_booking.*,transfer_mode.mode_name as mode_dispatch  FROM `tbl_domestic_booking`LEFT JOIN transfer_mode ON tbl_domestic_booking.mode_dispatch =transfer_mode.transfer_mode_id  WHERE `customer_id`='$user_id'")->result();
		$this->load->view('masterfranchise/booking_master/cancel_shipment_list', $data);
	}

	public function shipment_list($offset = 0, $searching = '')
	{


		$filterCond					= '';
		$all_data 					= $this->input->post();

		if ($all_data) {
			// print_r($all_data);	
			$filter_value = 	$_POST['filter_value'];

			foreach ($all_data as $key => $val) {
				if ($key == 'filter' && !empty($val)) {

					if ($val == 'pod_no') {
						$filterCond .= " AND tbl_domestic_booking.pod_no = '$filter_value'";
					}
					if ($val == 'sender_name') {
						$filterCond .= " AND tbl_domestic_booking.sender_name = '$filter_value'";
					}
					if ($val == 'receiver_name') {
						$filterCond .= " AND tbl_domestic_booking.receiver_name = '$filter_value'";
					}
					if ($val == 'origin') {
						$city_info					 =  $this->basic_operation_m->get_table_row('city', "city='$filter_value'");
						$filterCond 				.= " AND tbl_domestic_booking.sender_city = '$city_info->id'";
					}
					if ($val == 'destination') {
						$city_info					 =  $this->basic_operation_m->get_table_row('city', "city='$filter_value'");
						$filterCond 				.= " AND tbl_domestic_booking.reciever_city = '$city_info->id'";
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
				} elseif ($key == 'mode_name' && !empty($val)  && $val != "ALL") {
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


		$resAct = $this->db->query("SELECT tbl_domestic_booking.*,transfer_mode.mode_name as mode_dispatch  FROM `tbl_domestic_booking`LEFT JOIN transfer_mode ON tbl_domestic_booking.mode_dispatch =transfer_mode.transfer_mode_id  WHERE booking_type = 1 $filterCond AND customer_id = '$user_id' GROUP BY tbl_domestic_booking.booking_id order by tbl_domestic_booking.booking_id DESC limit " . $offset . ",5");
		// 	echo $this->db->last_query();exit;
		// 	echo $this->db->last_query();exit;

		$download_query = "SELECT tbl_domestic_booking.*,transfer_mode.mode_name as mode_dispatch  FROM `tbl_domestic_booking`LEFT JOIN transfer_mode ON tbl_domestic_booking.mode_dispatch = transfer_mode.transfer_mode_id  WHERE booking_type = 1 $filterCond AND customer_id = '$user_id' GROUP BY tbl_domestic_booking.booking_id order by tbl_domestic_booking.booking_id DESC limit " . $offset . ",5";





		$this->load->library('pagination');

		$data['total_count']			= $resActt->num_rows();
		$config['total_rows'] 			= $resActt->num_rows();
		$config['base_url'] 			= base_url() . 'Franchise_manager/shipment_list';
		$config['per_page'] 			= 5;
		$config['full_tag_open'] 		= '<nav aria-label="..."><ul class="pagination">';
		$config['full_tag_close'] 		= '</ul></nav>';
		$config['first_link'] 			= '&laquo; First';
		$config['first_tag_open'] 		= '<li class="prev paginate_button page-item">';
		$config['first_tag_close'] 		= '</li>';
		$config['last_link'] 			= 'Last &raquo;';
		$config['last_tag_open'] 		= '<li class="next paginate_button page-item">';
		$config['last_tag_close'] 		= '</li>';
		$config['next_link'] 			= 'Next';
		$config['next_tag_open'] 		= '<li class="next paginate_button page-item">';
		$config['next_tag_close'] 		= '</li>';
		$config['prev_link'] 			= 'Previous';
		$config['prev_tag_open'] 		= '<li class="prev paginate_button page-item">';
		$config['prev_tag_close'] 		= '</li>';
		$config['cur_tag_open'] 		= '<li class="paginate_button page-item active"><a href="javascript:void(0);" class="page-link">';
		$config['cur_tag_close'] 		= '</a></li>';
		$config['num_tag_open'] 		= '<li class="paginate_button page-item">';
		$config['reuse_query_string'] 	= TRUE;
		$config['num_tag_close'] 		= '</li>';

		$config['attributes'] = array('class' => 'page-link');
		$this->pagination->initialize($config);
		if ($offset == '') {
			$config['uri_segment'] 			= 3;
			$data['serial_no']				= 1;
		} else {
			$config['uri_segment'] 			= 3;
			$data['serial_no']		= $offset + 1;
		}

		if ($resAct->num_rows() > 0) {

			$data['shipment_list'] 			= $resAct->result();
		} else {
			$data['shipment_list'] 			= array();
		}

		if (isset($_POST['download_report']) && $_POST['download_report'] == 'Export') {


			$resActtt 			= $this->db->query($download_query);
			$shipment_data		= $resActtt->result_array();
			$this->download_domestic_shipmentreport($shipment_data);
		}


		$whr_c = array('company_type' => 'Domestic');
		$data['courier_company'] = $this->basic_operation_m->get_all_result("courier_company", $whr_c);
		$data['mode_details'] = $this->basic_operation_m->get_all_result("transfer_mode", '');
		$data['customer'] =  $this->basic_operation_m->get_query_result_array("SELECT * FROM tbl_customers WHERE parent_cust_id = '$user_id'  ORDER BY customer_name ASC");

		$this->load->view('masterfranchise/booking_master/shipment_list', $data);
	}




	public function download_domestic_shipmentreport($shipment_data)
	{


		$date = date('d-m-Y');
		$filename = "SipmentDetails_" . $date . ".csv";

		header("Content-Description: File Transfer");


		/* get data */
		// 		$usersData = $this->Crud_model->getUserDetails();
		/* file creation */

		//	print_r($download_report_query);


		$date = date('d-m-Y');
		$filename = "SipmentDetails_" . $date . ".csv";
		$fp = fopen('php://output', 'w');

		$header = array("AWB No.", "Sender", "Receiver", "Receiver City", "Forwording No", "Forworder Name", "Booking date",  "Pay Mode", "Amount",  "NOP", "Invoice No", "Invoice Amount", "Eway No", "Eway Expiry date");

 
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=' . $filename);
        $file = fopen('php:/* output','w'); 
		fputcsv($fp, $header);

		foreach ($shipment_data as $row) {
		//	print_r($shipment_data);
			$data = array(

				$row['pod_no'],
				$row['sender_name'],
				$row['reciever_name'],
				$row['city'],
				$row['forwording_no'],
				$row['forwording_name'],
				$row['booking_date'],
				$row['dispatch_details'],
				$row['total_amount'],
				$row['type_of_pack'],
				$row['invoice_no'],
				$row['invoice_value'],
				$row['eway_no'],
				$row['eway_expiry'],

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
		$this->load->view('masterfranchise/booking_list/b2b_booking_list', $data);
	}
	public function not_shippted_booking_list()
	{
		$data = $this->data;
		$this->load->view('masterfranchise/booking_list/notshippted_booking_list', $data);
	}

	public function booked_order_list()
	{
		$data = $this->data;
		$this->load->view('masterfranchise/booking_list/booked_order_list', $data);
	}

	public function view_order()
	{
		$data = $this->data;
		$this->load->view('masterfranchise/booking_list/view_order', $data);
	}


	//tracking

	public function b2b_track_list()
	{
		$data = $this->data;
		$this->load->view('masterfranchise/tracking_master/b2b_tracking_list', $data);
	}

	public function b2b_track_bookedList()
	{   
		$customer_id = $this->session->userdata('customer_id');
		$data = $this->data;
		$data['booked_list'] = $this->db->query("select tbl_domestic_booking.* ,tbl_domestic_tracking.status,tbl_customers.customer_name from tbl_domestic_booking  LEFT JOIN tbl_customers ON tbl_customers.customer_id = tbl_domestic_booking.customer_id inner join tbl_domestic_tracking ON tbl_domestic_booking.pod_no = tbl_domestic_tracking.pod_no  where tbl_domestic_booking.customer_id = '$customer_id'AND tbl_domestic_tracking.status = 'booked'")->result_array();
		$this->load->view('masterfranchise/tracking_master/b2b_tracking_bookedList', $data);
	}

	public function b2b_track_pendingpickupList()
	{
		$data = $this->data;
		$customer_id = $this->session->userdata('customer_id');
		$data['pending_list'] = $this->db->query("select tbl_domestic_booking.* ,tbl_domestic_tracking.status,tbl_customers.customer_name from tbl_domestic_booking  LEFT JOIN tbl_customers ON tbl_customers.customer_id = tbl_domestic_booking.customer_id inner join tbl_domestic_tracking ON tbl_domestic_booking.pod_no = tbl_domestic_tracking.pod_no  where tbl_domestic_booking.customer_id = '$customer_id'AND tbl_domestic_tracking.status = 'pending'")->result_array();
		$this->load->view('masterfranchise/tracking_master/b2b_tracking_pendingpickupList', $data);
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
		$this->load->view('franchise/tracking_master/b2b_tracking_deliverylist',$data);
	}

	public function b2b_track_deliveredList()
	{
		$customer_id = $this->session->userdata('customer_id');
        $data['delivered_list'] = $this->db->query("select tbl_domestic_booking.* ,tbl_domestic_tracking.status,tbl_customers.customer_name from tbl_domestic_booking  LEFT JOIN tbl_customers ON tbl_customers.customer_id = tbl_domestic_booking.customer_id inner join tbl_domestic_tracking ON tbl_domestic_booking.pod_no = tbl_domestic_tracking.pod_no  where tbl_domestic_booking.customer_id = '$customer_id'AND tbl_domestic_tracking.status = 'delivered'")->result_array();
		$this->load->view('franchise/tracking_master/b2b_track_deliveredList',$data);
	}



	// NDR List

	public function ndr_list()
	{
		$data = $this->data;
		$this->load->view('franchise/ndr_master/ndr_list', $data);
	}



	//****************** billing ********************

	public function rate_calculator()
	{

		$this->load->view('franchise/billing_master/rate-calculator');
	}


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
		$data['transaction_data'] = $this->db->query("select * from franchise_topup_balance_tbl where customer_id = '$customer_id' ")->result();

		$this->load->view('masterfranchise/billing_master/wallet-transaction',$data);
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
		$sub_total 	 = 0;
		$customer_id = $this->session->userdata('customer_id');
		$c_courier_id = $this->input->post('c_courier_id');
		$mode_id  = $this->input->post('mode_id');
		$reciver_city	= $this->input->post('city');
		$reciver_state 	= $this->input->post('state');
		$sender_state 	= $this->input->post('sender_state');
		$sender_city 	= $this->input->post('sender_city');
		$is_appointment = $this->input->post('is_appointment');
		// $invoice_value = $this->input->post('invoice_value');

		$groupId			= $this->basic_operation_m->selectRecord('franchise_delivery_tbl', array('delivery_franchise_id' => $customer_id))->row();

		// echo $this->db->last_query();exit();
		// print_r($groupId);


		$whr1 			= array('state' => $sender_state, 'city' => $sender_city);
		$res1			= $this->basic_operation_m->selectRecord('region_master_details', $whr1);

		$sender_zone_id 		= $res1->row()->regionid;
		$reciver_zone_id  		= $this->input->post('receiver_zone_id');

		$doc_type 		= $this->input->post('doc_type');
		$chargable_weight  = $this->input->post('chargable_weight');
		$receiver_gstno = $this->input->post('receiver_gstno');
		$booking_date       = $this->input->post('booking_date');
		$invoice_value       = $this->input->post('invoice_value');
		$dispatch_details       = $this->input->post('dispatch_details');
		$current_date = date("Y-m-d", strtotime($booking_date));
		$chargable_weight	= $chargable_weight * 1000;
		$fixed_perkg		= 0;
		$addtional_250		= 0;
		$addtional_500		= 0;
		$addtional_1000		= 0;
		$fixed_per_kg_1000		= 0;
		$tat					= 0;


		$where					= "from_zone_id='" . $sender_zone_id . "' AND to_zone_id='" . $reciver_zone_id . "'";

		// checking city and state rate 
		$fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "' AND city_id='" . $reciver_city . "'  AND mode_id='" . $mode_id . "' AND DATE(`applicable_from`)<='" . $current_date . "' AND (" . $this->input->post('chargable_weight') . " BETWEEN weight_range_from AND weight_range_to) and fixed_perkg = '0' ORDER BY applicable_from DESC LIMIT 1");
		if ($fixed_perkg_result->num_rows() > 0) {
			$where					= "city_id='" . $reciver_city . "'";
		} else {
			$fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "' AND city_id='" . $reciver_city . "'  AND mode_id='" . $mode_id . "' AND DATE(`applicable_from`)<='" . $current_date . "' AND fixed_perkg = '0' ORDER BY applicable_from DESC,weight_range_to desc LIMIT 1");
			if ($fixed_perkg_result->num_rows() > 0) {
				$where					= "city_id='" . $reciver_city . "'";
			}
		}


		// checking city and state rate 
		$fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "' AND state_id='" . $reciver_state . "' and city_id=''  AND mode_id='" . $mode_id . "' AND DATE(`applicable_from`)<='" . $current_date . "' AND (" . $this->input->post('chargable_weight') . " BETWEEN weight_range_from AND weight_range_to) and fixed_perkg = '0' ORDER BY applicable_from DESC LIMIT 1");
		if ($fixed_perkg_result->num_rows() > 0) {
			$where					= "state_id='" . $reciver_state . "'";
		} else {
			$fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "' AND state_id='" . $reciver_state . "' and city_id=''  AND mode_id='" . $mode_id . "' AND DATE(`applicable_from`)<='" . $current_date . "' AND fixed_perkg = '0' ORDER BY applicable_from DESC,weight_range_to desc LIMIT 1");
			if ($fixed_perkg_result->num_rows() > 0) {
				$where					= "state_id='" . $reciver_state . "'";
			}
		}

		// calculationg fixed per kg price 	
		$fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "'  AND $where  AND mode_id='" . $mode_id . "' AND DATE(`applicable_from`)<='" . $current_date . "' AND (" . $this->input->post('chargable_weight') . " BETWEEN weight_range_from AND weight_range_to) and fixed_perkg = '0' ORDER BY applicable_from DESC LIMIT 1");
		$frieht = 0;
		if ($fixed_perkg_result->num_rows() > 0) {
			$data['rate_master'] = $fixed_perkg_result->row();
			$rate	= $data['rate_master']->rate;
			$tat	= $data['rate_master']->tat;
			$fixed_perkg = $rate;
		} else {
			$fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "' AND $where  AND mode_id='" . $mode_id . "' AND DATE(`applicable_from`)<='" . $current_date . "' AND fixed_perkg = '0' ORDER BY applicable_from DESC,weight_range_to desc LIMIT 1");
			if ($fixed_perkg_result->num_rows() > 0) {
				$data['rate_master']    = $fixed_perkg_result->row();
				$rate               	= $data['rate_master']->rate;
				$tat          	     	= $data['rate_master']->tat;
				$weight_range_to	    = round($data['rate_master']->weight_range_to * 1000);
				$fixed_perkg            = $rate;
			}

			$fixed_perkg_result = $this->db->query("select * from tbl_franchise_rate_master where group_id='" . @$groupId->rate_group . "'  AND $where  AND mode_id='" . $mode_id . "' AND DATE(`applicable_from`)<='" . $current_date . "' AND fixed_perkg <> '0' ");
			if ($fixed_perkg_result->num_rows() > 0) {
				if ($weight_range_to > 1000) {
					$weight_range_to = $weight_range_to;
				} else {
					$weight_range_to = 1000;
				}
				$left_weight  = ($chargable_weight - $weight_range_to);

				$rate_master  = $fixed_perkg_result->result();

				foreach ($rate_master as $key => $values) {
					$tat	= $values->tat;
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

					if ($values->fixed_perkg == 4 && ($this->input->post('chargable_weight') >=  $values->weight_range_from && $this->input->post('chargable_weight') <=  $values->weight_range_to)) // 1000 gm slab
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

		$whr1 = array('group_id' => @$groupId->rate_group);
		$res1 = $this->basic_operation_m->get_table_row('franchise_fule_tbl', $whr1);
		// echo "kddjh";
		// echo $this->db->last_query();
		// print_r($res1);


		if ($res1) {

			$cft = 8;
			$cod = '0';
			if ($doc_type == 1) {
				$fov = ($amount * $res1->fov_rate / 100);
			} else {
				$fov = 0;
			}

			$to_pay_charges = '0';
			$appt_charges = '0';
			$fuel_per = '0';
			$docket_charge = $res1->awb_rate;
			$amount	= $amount + $fov + $docket_charge + $cod + $to_pay_charges + $appt_charges;
			$final_fuel_charges = ($amount * $res1->fule_percentage / 100);
		} else {
			$cft = 8;
			$cod = '0';
			$fov = '0';
			$to_pay_charges = '0';
			$appt_charges = '0';
			$fuel_per = '0';
			$docket_charge = '0';
			$amount	= $amount + $fov + $docket_charge + $cod + $to_pay_charges + $appt_charges;
			$final_fuel_charges = ($amount * $fuel_per / 100);
		}

		//Cash



		$sub_total = ($amount + $final_fuel_charges);

		$first_two_char = substr($receiver_gstno, 0, 2);

		if ($receiver_gstno == "") {
			$first_two_char = 27;
		}

		$tbl_customers_info 		= $this->basic_operation_m->get_query_row("select gst_charges from tbl_customers where customer_id = '$customer_id'");

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
			$tat_date 		=  date('Y-m-d', strtotime($booking_date . " + $tat days"));
		} else {
			$tat_date 		=  date('Y-m-d', strtotime($booking_date . " + 5 days"));
		}

		$cft = 8;

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
}
