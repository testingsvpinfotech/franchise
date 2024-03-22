<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
defined('BASEPATH') or exit('No direct script access allowed');

class Pickup_Request_Controller extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('basic_operation_m');
		if ($this->session->userdata('userId') == '') {
			redirect('admin');
		}
	}

	public function index()
	{
		$data = array();
		$data['message'] = "";
		//	$customer_id					=	$this->session->userdata("customer_id");
		$data['all_request']			= $this->db->query('select tbl_pickup_request_data.*,pickup_weight_tbl.destination_pincode,pickup_weight_tbl.actual_weight,pickup_weight_tbl.type_of_package,pickup_weight_tbl.no_of_pack from tbl_pickup_request_data LEFT JOIN pickup_weight_tbl ON pickup_weight_tbl.pickup_id = tbl_pickup_request_data.id')->result();
		// echo $this->db->last_query();exit;
		// echo '<pre>'; print_r($data['all_request']);exit;
		$this->load->view('admin/pickup/admin_view_pickup_request', $data);
	}

	// public function fetch_consigner(){
	// 	$pickup_request_no = $this->input->post('pickup_request_no');
	// 	$dd = $this->db->query("select tbl_pickup_request_data.*,P.city,P.state from tbl_pickup_request_data left join pincode as P on P.pin_code = tbl_pickup_request_data.pickup_pincode  where pickup_request_id ='$pickup_request_no'")->row();
	// //	echo $this->db->last_query();
	// 	echo  json_encode($dd);
	// }
	public function fetch_consigner()
	{
		$pickup_request_no = $this->input->post('pickup_request_no');
		$dd = $this->db->query("select tbl_pickup_request_data.*,tbl_customers.customer_name from tbl_pickup_request_data left join tbl_customers  ON tbl_customers.customer_id = tbl_pickup_request_data.customer_id  where pickup_request_id ='$pickup_request_no'")->row();
		//	echo $this->db->last_query();
		echo  json_encode($dd);
	}


	public function get_pickup_id()
	{
		$get_pickup_no = $this->input->get('id');
		$dd2 = $this->db->query("select pickup_request_id from  tbl_pickup_request_data where pickup_request_id = '$get_pickup_no '")->row();
		echo json_encode($dd2);
	}
	public function status_change_pickup()
	{
		$pickup_request_id = $this->input->post('pickup_request_id');
		$status_closed_by = $this->input->post('status_closed_by');
		$status = $this->input->post('status');
		$this->db->query("update tbl_pickup_request_data set pickup_status = '$status',status_closed_by  = '$status_closed_by' where pickup_request_id ='$pickup_request_id '");
		//	echo $this->db->last_query();exit;
		$this->session->set_flashdata('msg', 'PRQ Close Successfully!!');
		redirect(base_url() . 'admin/all-pickup-request-list');
	}





	public function add_prq_for_cs()
	{
		$userId	=	$this->session->userdata("userId");
		$data			= array();
		$result 		= $this->db->query('select max(id) AS id from tbl_pickup_request_data')->row();
		$id 			= $result->id + 1;
		$date = date('ym');
		if (strlen($id) == 2) {
			$id = 'BNF/' . $date . '/000' . $id;
		} elseif (strlen($id) == 3) {
			$id = 'BNF/' . $date . '/000' . $id;
		} elseif (strlen($id) == 1) {
			$id = 'BNF/' . $date . '/000' . $id;
		} elseif (strlen($id) == 4) {
			$id = 'BNF/' . $date . '/000' . $id;
		} elseif (strlen($id) == 5) {
			$id = 'BNF/' . $date . '/000' . $id;
		}
		$data['request_id'] = $id;

		$data['cs_prq_list'] = $this->db->query("select * from tbl_pickup_request_data where user_id ='$userId'order by pickup_request_id DESC limit 7")->result();
		$data['type_of_package'] = $this->db->query("select * from partial_type_tbl")->result();
		$data['time'] = $this->db->query("select * from pickup_time_slot_tbl")->result();
		$data['transfer_mode'] = $this->db->query("select * from transfer_mode")->result();
		$data['customers'] = $this->db->query("select * from tbl_customers")->result_array();
		$this->load->view('admin/pickup/add_prq', $data);
	}



	public function store_prq_for_cs()
	{
		if (isset($_POST['submit'])) {

			$r1 = array();
			$user_type =   $this->session->userdata('userType');
			$userId	=	$this->session->userdata("userId");
			$recurring_data1  = $_POST['recurring_data'];
			$recurring_data = implode(",", $recurring_data1);

			$pickup_date = $this->input->post('pickup_date');

			$pickup_time = $this->input->post('pickup_time');

			$pickup_date_time = $pickup_date . "  " . $pickup_time;

			//	print_r($pickup_date_time);exit;
			$r = array(
				'id' => '',
				'user_id' => $userId,
				'recurring_data' => $recurring_data,
				'customer_type' => $user_type,
				'customer_id' => $this->input->post('customer_id'),
				'consigner_name' => $this->input->post('consigner_name'),
				'pickup_request_id' => $this->input->post('pickup_request_id'),
				'consigner_contact' => $this->input->post('consigner_contact'),
				'consigner_address1' => $this->input->post('consigner_address1'),
				'consigner_address2' => $this->input->post('consigner_address2'),
				'consigner_address3' => $this->input->post('consigner_address3'),
				'consigner_email' => $this->input->post('consigner_email'),
				'pickup_pincode' => $this->input->post('pickup_pincode'),
				'pickup_location' => $this->input->post('pickup_location'),
				'pickup_date' => $pickup_date_time,
				'city' => $this->input->post('city'),
				'instruction' => $this->input->post('instruction'),
				'mode_id' => $this->input->post('mode_id'),


			);
			//print_r($r);exit;

			$result = $this->basic_operation_m->insert('tbl_pickup_request_data', $r);

			$destination_pincode = $this->input->post('destination_pincode[]');
			$count = count($this->input->post('destination_pincode[]'));
			//$destination_location = '';
			$actual_weight = $this->input->post('actual_weight[]');
			$type_of_package = $this->input->post('type_of_package[]');
			$no_of_pack = $this->input->post('no_of_pack[]');
			$lastid = $this->db->insert_id();

			//  print_r($count);exit;
			for ($i = 0; $i < $count; $i++) {
				$r1 = array(
					'pickup_id' => $lastid,
					'destination_pincode' => $destination_pincode[$i],
					//  'destination_location' =>$destination_location[$i],
					'actual_weight' => $actual_weight[$i],
					'type_of_package' => $type_of_package[$i],
					'no_of_pack' => $no_of_pack[$i],
				);
				// print_r($r1);

				$result = $this->db->insert('pickup_weight_tbl', $r1);
				//  echo $this->db->last_query();exit;
			}

			if (!empty($result)) {
				$this->session->set_flashdata('flash_message', "Data Inserted Successfully!!");
			}
			redirect('admin/add_prq_data');
		}
	}

	public function all_pickup_request_list()
	{
		$userType	=	$this->session->userdata("userType");
		$branch_id =	$this->session->userdata('branch_id');
		if (!empty($userType == '1')) {
			$data['pickup_boy'] = $this->db->query("SELECT * FROM tbl_users WHERE user_type = '3'")->result_array();
		} else {
			$data['pickup_boy'] = $this->db->query("SELECT * FROM tbl_users WHERE branch_id ='$branch_id' AND user_type = '3'")->result_array();
		}
		$data['branch_name'] = $this->db->query("select * from tbl_branch")->result_array();
		// $data['show_prq_list'] =  $this->db->query("select * from tbl_pickup_request_data where  branch_id ='0' AND customer_type ='10' OR customer_type ='0' order by pickup_request_id DESC")->result_array();
		if (!empty($userType == '1'  ||  $userType == '16')) {
			$data['show_prq_list'] =  $this->db->query("select * from tbl_pickup_request_data")->result_array();
		} else {
			$data['show_prq_list'] =  $this->db->query("select * from tbl_pickup_request_data where branch_id ='$branch_id'")->result_array();
		}
		$this->load->view('admin/pickup/all_pickup_request_list', $data);
	}


	//   public function request_quote(){
	// 	   $userType	=	$this->session->userdata("userType");
	// 	  // print_r($this->session->all_userdata());
	//        $data['show_prq_list'] = $this->db->query("select * from tbl_pickup_request_data where branch_id !='0' AND customer_type ='10' OR customer_type ='1'  order by pickup_request_id DESC ")->result_array();
	//        $this->load->view("admin/pickup/view_prq_list",$data);

	//    } 

	public function branch_assigned_prq_list()
	{
		$branch_id =	$this->session->userdata('branch_id');
		$userType	=	$this->session->userdata("userType");
		if (!empty($userType == '1')) {
			$data['pickup_boy'] = $this->db->query("SELECT * FROM tbl_users WHERE user_type = '3'")->result_array();
		} else {
			$data['pickup_boy'] = $this->db->query("SELECT * FROM tbl_users WHERE branch_id ='$branch_id' AND user_type = '3'")->result_array();
		}
		if (!empty($userType == '1'  ||  $userType == '16')) {
			$data['show_prq_list'] =  $this->db->query("select * from tbl_pickup_request_data where branch_id !='0' order by pickup_request_id DESC ")->result_array();
			$this->load->view("admin/pickup/branch_wise_prq_list", $data);
		} else {
			$data['show_prq_list'] =  $this->db->query("select * from tbl_pickup_request_data where branch_id ='$branch_id'order by pickup_request_id DESC ")->result_array();
			$this->load->view("admin/pickup/branch_wise_prq_list", $data);
		}
	}

	public function prq_assign_pickupboy()
	{
		$pickup_request_no = $this->input->post('pickup_request_no');
		$pickup_boy_assigned_date = $this->input->post('pickup_boy_assigned_date');
		$username = $this->input->post('username');


		$result = $this->db->query("update tbl_pickup_request_data set pickup_boy ='$username', pickup_status = '2', pickup_boy_date_assigned ='$pickup_boy_assigned_date' where pickup_request_id='$pickup_request_no' ");
		//echo $this->db->last_query();exit;
		$this->session->set_flashdata("'msg','PRQ Assigned Pickup Boy'.'$username'.");
		redirect(base_url() . 'admin/all-pickup-request-list');
	}

	public function prq_list()
	{
		$prq_id = $this->input->get('id');
		$data = $this->db->query("select tbl_pickup_request_data.*,transfer_mode.mode_name,pickup_weight_tbl.destination_pincode,pickup_weight_tbl.actual_weight,pickup_weight_tbl.type_of_package,pickup_weight_tbl.no_of_pack from  tbl_pickup_request_data left join pickup_weight_tbl on pickup_weight_tbl.pickup_id =  tbl_pickup_request_data.id left join transfer_mode on transfer_mode.transfer_mode_id=tbl_pickup_request_data.mode_id where id = '$prq_id'")->result_array();
		echo json_encode($data);
	}

	public function prq_booking_list($offset = 0, $searching = '')
	{
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		if ($this->session->userdata('userId') == '') {
			redirect('admin');
		} else {
			$data = [];

			if (isset($_POST['from_date'])) {
				$data['from_date'] = $_POST['from_date'];
				$from_date = $_POST['from_date'];
			}
			if (isset($_POST['to_date'])) {
				$data['to_date'] = $_POST['to_date'];
				$to_date = $_POST['to_date'];
			}
			if (isset($_POST['filter'])) {
				$filter = $_POST['filter'];
				$data['filter']  = $filter;
			}
			if (isset($_POST['courier_company'])) {
				$courier_company = $_POST['courier_company'];
				$data['courier_companyy']  = $courier_company;
			}
			if (isset($_POST['user_id'])) {
				$user_id = $_POST['user_id'];
				$data['user_id']  = $user_id;
			}
			if (isset($_POST['filter_value'])) {
				$filter_value = $_POST['filter_value'];
				$data['filter_value']  = $filter_value;
			}

			$user_id 	= $this->session->userdata("userId");
			$data['customer'] =  $this->basic_operation_m->get_query_result_array('SELECT * FROM tbl_customers WHERE 1 ORDER BY customer_name ASC');

			$user_type 					= $this->session->userdata("userType");
			$filterCond					= '';
			$all_data 					= $this->input->post();

			if ($all_data) {
				$filter_value = 	$_POST['filter_value'];

				foreach ($all_data as $ke => $vall) {
					if ($ke == 'filter' && !empty($vall)) {
						if ($vall == 'pod_no') {
							$filterCond .= " AND tbl_domestic_booking.pod_no = '$filter_value'";
						}
						if ($vall == 'forwording_no') {
							$filterCond .= " AND tbl_domestic_booking.forwording_no = '$filter_value'";
						}
						if ($vall == 'sender') {
							$filterCond .= " AND tbl_domestic_booking.sender_name LIKE '%$filter_value%'";
						}
						if ($vall == 'receiver') {
							$filterCond .= " AND tbl_domestic_booking.reciever_name LIKE '%$filter_value%'";
						}

						if ($vall == 'origin') {
							$city_info					 =  $this->basic_operation_m->get_table_row('city', "city='$filter_value'");
							$filterCond 				.= " AND tbl_domestic_booking.sender_city = '$city_info->id'";
						}
						if ($vall == 'destination') {
							$city_info					 =  $this->basic_operation_m->get_table_row('city', "city='$filter_value'");
							$filterCond 				.= " AND tbl_domestic_booking.reciever_city = '$city_info->id'";
						}
						if ($vall == 'pickup') {

							$filterCond 				.= " AND tbl_domestic_booking.pickup_pending = '1'";
						}
					} elseif ($ke == 'user_id' && !empty($vall)) {
						$filterCond .= " AND tbl_domestic_booking.customer_id = '$vall'";
					} elseif ($ke == 'from_date' && !empty($vall)) {
						$filterCond .= " AND tbl_domestic_booking.booking_date >= '$vall'";
					} elseif ($ke == 'to_date' && !empty($vall)) {
						$filterCond .= " AND tbl_domestic_booking.booking_date <= '$vall'";
					} elseif ($ke == 'courier_company' && !empty($vall) && $vall != "ALL") {
						$filterCond .= " AND tbl_domestic_booking.courier_company_id = '$vall'";
					} elseif ($ke == 'mode_name' && !empty($vall) && $vall != "ALL") {
						$filterCond .= " AND tbl_domestic_booking.mode_dispatch = '$vall'";
					}
				}
			}
			if (!empty($searching)) {
				$filterCond = urldecode($searching);
			}


			if ($this->session->userdata("userType") == '1') {
				$resActt = $this->db->query("SELECT count(tbl_domestic_booking.booking_id) as cnt FROM tbl_domestic_booking  INNER JOIN tbl_pickup_request_data ON tbl_pickup_request_data.pickup_request_id = tbl_domestic_booking.prq_no WHERE  booking_type = 1 $filterCond ");
				$dd = $resActt->row_array();

				$resAct = $this->db->query("SELECT tbl_domestic_booking.*,tbl_pickup_request_data.pickup_request_id,city.city,tbl_domestic_weight_details.chargable_weight,tbl_domestic_weight_details.no_of_pack,payment_method  FROM tbl_domestic_booking   INNER JOIN tbl_pickup_request_data ON tbl_pickup_request_data.pickup_request_id = tbl_domestic_booking.prq_no JOIN city ON tbl_domestic_booking.reciever_city = city.id  JOIN tbl_domestic_weight_details ON tbl_domestic_weight_details.booking_id = tbl_domestic_booking.booking_id WHERE booking_type = 1 AND company_type='Domestic' AND tbl_domestic_booking.user_type !=5 $filterCond GROUP BY tbl_domestic_booking.booking_id order by tbl_domestic_booking.booking_id DESC limit " . $offset . ",50");
				// echo $this->db->last_query();die();
				$download_query 		= "SELECT tbl_domestic_booking.*,tbl_pickup_request_data.pickup_request_id,city.city,tbl_domestic_weight_details.chargable_weight,tbl_domestic_weight_details.no_of_pack,payment_method  FROM tbl_domestic_booking   INNER JOIN tbl_pickup_request_data ON tbl_pickup_request_data.pickup_request_id = tbl_domestic_booking.prq_no JOIN city ON tbl_domestic_booking.reciever_city = city.id  JOIN tbl_domestic_weight_details ON tbl_domestic_weight_details.booking_id = tbl_domestic_booking.booking_id WHERE booking_type = 1 AND company_type='Domestic' AND tbl_domestic_booking.user_type !=5 $filterCond  GROUP BY tbl_domestic_booking.booking_id order by tbl_domestic_booking.booking_id DESC";

				$this->load->library('pagination');

				$data['total_count']			= $dd['cnt'];
				$config['total_rows'] 			= $dd['cnt'];
				$config['base_url'] 			= 'admin/prq-booking-list/';
				//	$config['suffix'] 				= '/'.urlencode($filterCond);

				$config['per_page'] 			= 50;
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

				if ($offset == '') {
					$config['uri_segment'] 			= 3;
					$data['serial_no']				= 1;
				} else {
					$config['uri_segment'] 			= 3;
					$data['serial_no']		= $offset + 1;
				}


				$this->pagination->initialize($config);
				if ($resAct->num_rows() > 0) {

					$data['allpoddata'] 			= $resAct->result_array();
				} else {
					$data['allpoddata'] 			= array();
				}
			} else {
				//print_r($this->session->all_userdata());
				$branch_id = $this->session->userdata("branch_id");
				$where 		= '';
				// if($this->session->userdata("userType") == '7') 
				if ($this->session->userdata("branch_id") == $branch_id) {

					$username = $this->session->userdata("userName");

					$whr = array('username' => $username);
					// $res = $this->basic_operation_m->getAll('tbl_users', $whr);
					// $branch_id = $res->row()->branch_id;				
					$where = "and tbl_domestic_booking.branch_id='$branch_id' ";
				}

				$resActt = $this->db->query("SELECT count(tbl_domestic_booking.booking_id) as cnt FROM tbl_domestic_booking  inner join tbl_pickup_request_data on tbl_pickup_request_data.pickup_request_id = tbl_domestic_booking.prq_no WHERE booking_type = 1 and tbl_domestic_booking.branch_id='$branch_id' $filterCond ");
				$dd = $resActt->row_array();


				$resAct = $this->db->query("SELECT tbl_domestic_booking.*,tbl_pickup_request_data.pickup_request_id,city.city,tbl_domestic_weight_details.chargable_weight,tbl_domestic_weight_details.no_of_pack,payment_method  FROM tbl_domestic_booking  inner join tbl_pickup_request_data on tbl_pickup_request_data.pickup_request_id = tbl_domestic_booking.prq_no JOIN  city ON tbl_domestic_booking.reciever_city = city.id   JOIN  tbl_domestic_weight_details ON tbl_domestic_weight_details.booking_id = tbl_domestic_booking.booking_id  WHERE booking_type = 1 $where $filterCond  order by tbl_domestic_booking.booking_id DESC limit " . $offset . ",50");
				//echo $this->db->last_query();exit;

				$download_query 		= "SELECT tbl_domestic_booking.*,tbl_pickup_request_data.pickup_request_id,city.city,tbl_domestic_weight_details.chargable_weight,tbl_domestic_weight_details.no_of_pack,payment_method  FROM tbl_domestic_booking JOIN city ON tbl_domestic_booking.reciever_city = city.id inner join tbl_pickup_request_data on tbl_pickup_request_data.pickup_request_id = tbl_domestic_booking.prq_no  JOIN  tbl_domestic_weight_details ON tbl_domestic_weight_details.booking_id = tbl_domestic_booking.booking_id  WHERE booking_type = 1 $where $filterCond  order by tbl_domestic_booking.booking_id DESC ";

				$this->load->library('pagination');

				$data['total_count']			= $dd['cnt'];
				$config['total_rows'] 			= $dd['cnt'];
				$config['base_url'] 			= 'admin/prq-booking-list/';
				//	$config['suffix'] 				= '/'.urlencode($filterCond);

				$config['per_page'] 			= 50;
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

				if ($offset == '') {
					$config['uri_segment'] 			= 3;
					$data['serial_no']				= 1;
				} else {
					$config['uri_segment'] 			= 3;
					$data['serial_no']		= $offset + 1;
				}


				$this->pagination->initialize($config);
				if ($resAct->num_rows() > 0) {
					$data['allpoddata'] = $resAct->result_array();
				} else {
					$data['allpoddata'] = array();
				}
			}

			if (isset($_POST['download_report']) && $_POST['download_report'] == 'Download Report') {
				$resActtt 			= $this->db->query($download_query);
				$shipment_data		= $resActtt->result_array();
				$this->domestic_shipment_report($shipment_data);
			}

			$data['viewVerified'] = 2;
			$whr_c = array('company_type' => 'Domestic');
			$data['courier_company'] = $this->basic_operation_m->get_all_result("courier_company", $whr_c);
			$data['mode_details'] = $this->basic_operation_m->get_all_result("transfer_mode", '');
			$this->load->view('admin/pickup/prq_booking_shipment_list', $data);
		}
	}

	public function domestic_shipment_report($shipment_data)
	{
		$date = date('d-m-Y');
		$filename = "SipmentDetails_" . $date . ".csv";
		$fp = fopen('php://output', 'w');

		$header = array("PRQ No.", "AWB No.", "Sender", "Receiver", "Receiver City", "Forwording No", "Forworder Name", "Booking date", "Mode", "Pay Mode", "Amount", "Weight", "NOP", "Invoice No", "Invoice Amount", "Branch Name", "User", "Eway No", "Eway Expiry date");


		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=' . $filename);

		fputcsv($fp, $header);
		$i = 0;
		foreach ($shipment_data as $row) {
			$i++;
			ini_set('display_errors', '0');
			ini_set('display_startup_errors', '0');
			error_reporting(E_ALL);
			$whr = array('transfer_mode_id' => $row['mode_dispatch']);
			$mode_details = $this->basic_operation_m->get_table_row('transfer_mode', $whr);

			$whr_u = array('branch_id' => $row['branch_id']);
			$branch_details = $this->basic_operation_m->get_table_row('tbl_branch', $whr_u);


			$whr_u = array('user_id' => $row['user_id']);
			$user_details = $this->basic_operation_m->get_table_row('tbl_users', $whr_u);
			$user_details->username = substr($user_details->username, 0, 20);



			$whr = array('id' => $row['sender_city']);
			$sender_city_details = $this->basic_operation_m->get_table_row("city", $whr);
			$sender_city = $sender_city_details->city;

			$whr_s = array('id' => $row['reciever_state']);
			$reciever_state_details = $this->basic_operation_m->get_table_row("state", $whr_s);
			$reciever_state = $reciever_state_details->state;

			$whr_p = array('id' => $row['payment_method']);
			$payment_method_details = $this->basic_operation_m->get_table_row("payment_method", $whr_p);
			$payment_method = $payment_method_details->method;


			$branch_details->branch_name = substr($branch_details->branch_name, 0, 20);
			$row = array(
				$row['pickup_request_id'],
				$row['pod_no'],
				$row['sender_name'],
				$row['reciever_name'],
				$row['city'],
				$row['forwording_no'],
				$row['forworder_name'],
				date('d-m-Y', strtotime($row['booking_date'])),
				$mode_details->mode_name,
				$row['dispatch_details'],
				$row['grand_total'],
				$row['chargable_weight'],
				$row['no_of_pack'],
				$row['invoice_no'],
				$row['invoice_value'],
				$branch_details->branch_name,
				$user_details->username
			);


			fputcsv($fp, $row);
		}
		exit;
	}



	public function booking_prq_data()
	{
		// $data			= $this->data;
		$result 		= $this->db->query('select max(booking_id) AS id from tbl_domestic_booking')->row();
		$id 			= $result->id + 1;

		if (strlen($id) == 2) {
			$id = 'B4L1000' . $id;
		} elseif (strlen($id) == 3) {
			$id = 'B4L100' . $id;
		} elseif (strlen($id) == 1) {
			$id = 'B4L10000' . $id;
		} elseif (strlen($id) == 4) {
			$id = 'B4L10' . $id;
		} elseif (strlen($id) == 5) {
			$id = 'B4L1' . $id;
		}
		$user_type 	= $this->session->userdata("userType");
		$branch_id 	= $this->session->userdata("branch_id");
		if ($user_type == '1') {
			$data['prq_ref_no'] = $this->db->query("Select tbl_pickup_request_data.pickup_request_id,tbl_customers.customer_name from  tbl_pickup_request_data left join tbl_customers on tbl_customers.customer_id = tbl_pickup_request_data.customer_id where pickup_status ='0' OR pickup_status ='2'")->result();
		} else {
			$data['prq_ref_no'] = $this->db->query("Select tbl_pickup_request_data.pickup_request_id,tbl_customers.customer_name from  tbl_pickup_request_data left join tbl_customers on tbl_customers.customer_id = tbl_pickup_request_data.customer_id where tbl_pickup_request_data.branch_id ='$branch_id' AND pickup_status !='1'")->result();
			//echo $this->db->last_query();exit;	
		}
		$username = $this->session->userdata("userName");
		$whr = array('username' => $username);
		$res = $this->basic_operation_m->getAll('tbl_users', $whr);
		$branch_id = $res->row()->branch_id;
		$data['branch_info']	= $this->basic_operation_m->get_query_row("select * from tbl_branch where branch_id = '$branch_id'");

		$data['transfer_mode']		 	= $this->basic_operation_m->get_query_result('select * from `transfer_mode`');

		$user_id 	= $this->session->userdata("userId");
		$data['cities']	= $this->basic_operation_m->get_all_result('city', '');
		$data['states'] = $this->basic_operation_m->get_all_result('state', '');

		$data['customers'] = $this->basic_operation_m->get_all_result('tbl_customers', "");
		$data['payment_method']  = $this->basic_operation_m->get_all_result('payment_method', '');
		$data['region_master'] = $this->basic_operation_m->get_all_result('region_master', '');
		$data['bid'] = $id;
		$whr_d = array("company_type" => "Domestic");
		$data['courier_company'] = $this->basic_operation_m->get_all_result("courier_company", $whr_d);
		// $data['content_master'] = $this->basic_operation_m->get_all_result("content_master", array());
		$data['partial_type_tbl'] = $this->basic_operation_m->get_all_result("partial_type_tbl", array());
		$this->load->view('admin/pickup/prq_booking', $data);
	}

	public function get_pickup_request()
	{

		$prq_id = $this->input->get('prq_no');
		$dd5 = $this->db->query("Select tbl_domestic_booking.*,tbl_domestic_weight_details.actual_weight from  tbl_domestic_booking left join tbl_domestic_weight_details ON tbl_domestic_weight_details.booking_id = tbl_domestic_booking.booking_id where prq_no = '$prq_id'")->result();
		echo json_encode($dd5);
	}



	public function assign_branch()
	{

		$result 		= $this->db->query('select max(id) AS id from tbl_pickup_request_data')->row();
		$id = $result->id + 1;

		if (strlen($id) == 2) {
			$id = 'PRQ00000' . $id;
		} elseif (strlen($id) == 3) {
			$id = 'PRQ0000' . $id;
		} elseif (strlen($id) == 1) {
			$id = 'PRQ000000' . $id;
		} elseif (strlen($id) == 4) {
			$id = 'PRQ000' . $id;
		} elseif (strlen($id) == 5) {
			$id = 'PRQ00' . $id;
		}
		$sub_docket = $id;

		$get_branch_id = $this->input->post('branch_id');
		$id = $this->input->post('id');
		// print_r($id);
		// print_r($get_branch_id );

		$this->db->set('branch_id', $get_branch_id, 'par_docket', $sub_docket);
		$this->db->where_in('id', explode(",", $id));
		$this->db->update('tbl_pickup_request_data');
		//echo $this->db->last_query();

		echo json_encode(['success' => "Item Update successfully."]);
	}

	public function add_pickup_time()
	{
		if (isset($_POST['submit'])) {
			$data = array(
				'time' => $this->input->post('time_sloat'),
			);
			$this->db->insert('pickup_time_slot_tbl', $data);
			$this->session->set_flashdata('msg', 'Time Added');
			redirect(base_url() . 'admin/add_pickup_time');
		}
		$data['time_data'] = $this->db->query("select * from pickup_time_slot_tbl")->result();
		$this->load->view('admin/pickup/add_sloat_time', $data);
	}

	public function delete_time($id)
	{
		$this->db->query("delete from pickup_time_slot_tbl where id='$id'");
		$this->session->set_flashdata('msg', 'Data Deleted Successfully!!');
		redirect(base_url() . 'admin/add_pickup_time');
	}
}
