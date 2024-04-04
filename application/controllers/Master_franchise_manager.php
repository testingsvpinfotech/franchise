<?php
ini_set ('display_errors', 1);  
 

defined('BASEPATH') OR exit('No direct script access allowed');

class Master_franchise_manager extends CI_Controller 
{
	var $data= array();
    function __construct() 
	{
        parent:: __construct();
		$this->load->model('login_model');
        $this->load->model('basic_operation_m');
		$this->data['company_info']	= $this->basic_operation_m->get_query_row("select * from tbl_company limit 1"); 
		if($this->session->userdata('customer_id') == '')
		{
			redirect('franchise');
		}
    }

 public function dashboard(){
	//echo 'hello';exit;
    $this->load->view('masterfranchise/dashboard');
 } 

 public function franchise_list(){
	$customer_id = $this->session->userdata('customer_id');
    $data['franchise_list'] = $this->db->query("select * from tbl_customers where parent_cust_id = $customer_id ")->result();
	//echo $this->db->last_query();exit;
	$this->load->view('masterfranchise/franchise_list',$data);
 }

 public function wallet_transaction()
	{
		$customer_id = $this->session->userdata('customer_id');
		$data['transaction_data'] = $this->db->query("select * from franchise_topup_balance_tbl where customer_id = '$customer_id' order by topup_balance_id desc")->result();

		$this->load->view('masterfranchise/billing_master/wallet-transaction',$data);
	}

 public function franchise_incoming()
	{  
     

        $data= array();
		
		$branch_name = $_SESSION['customer_id'];
		// print_r($_SESSION);die;
		$resAct=$this->db->query("select  *,SUM(CASE WHEN reciving_status=1 THEN 1 ELSE 0 END)  AS total_coming, COUNT(id) AS total, COUNT(total_pcs) AS total_pcs, COUNT(total_weight) AS total_weight from tbl_domestic_menifiest where tbl_domestic_menifiest.destination_franchise='$branch_name' group by manifiest_id order by date_added DESC");
		//$resAct=$this->basic_operation_m->getAll('tbl_inword','');
		 if($resAct->num_rows()>0)
		 {
		 	$data['allinword']=$resAct->result_array();	            
         }
         $this->load->view('masterfranchise/view_incoming',$data);
     
		
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
			if (!empty(is_numeric($stock_id))) {
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

	public function awb_available_stock($offset = 0, $searching = '')
	{
		$user_id = $this->session->userdata("customer_id");
		$stock = $this->db->query("select * from tbl_branch_assign_cnode where customer_id = '$user_id'")->result();
		
        foreach($stock as $key => $value){
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
				$config['base_url'] = base_url() . 'Ms-franchise/awb-available-stock';
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
			$config['base_url'] = base_url() . 'Ms-franchise/awb-available-stock';
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
		$this->load->view('masterfranchise/booking_master/awb_available_stock', $data);
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
				redirect('master-franchise/shipment-list');
			}
			}
			if($this->input->post('doc_type')=='1'){
			if (empty($this->input->post('invoice_value'))) {
				$msg = 'Invoice Value Must be required';
				$class = 'alert alert-danger alert-dismissible';
				$this->session->set_flashdata('notify', $msg);
				$this->session->set_flashdata('class', $class);
				redirect('master-franchise/shipment-list');
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
					redirect('master-franchise/shipment-list');
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
						redirect('master-franchise/shipment-list');
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
			redirect('master-franchise/shipment-list');
		} else {
			// Add shipment case 
			$data = array();
			$data['transfer_mode'] = $this->basic_operation_m->get_query_result('select * from `transfer_mode`'); // mode
			$customer_id = $this->session->userdata("customer_id");
			$data['cities'] = $this->basic_operation_m->get_all_result('city', '');
			$data['states'] = $this->basic_operation_m->get_all_result('state', '');
			$data['franchise'] = $this->basic_operation_m->get_all_result('tbl_customers', "customer_id = '$customer_id'");
			$data['customers'] = $this->db->query("SELECT * FROM tbl_customers WHERE franchise_id = '$customer_id' AND (customer_type !='1' OR customer_type !='2') ")->result_array();
			$this->load->view('masterfranchise/booking_master/add_shipment', $data);
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
				redirect('master-franchise/shipment-list');
			}
			}
			if($this->input->post('doc_type')=='1'){
			if (empty($this->input->post('invoice_value'))) {
				$msg = 'Invoice Value Must be required';
				$class = 'alert alert-danger alert-dismissible';
				$this->session->set_flashdata('notify', $msg);
				$this->session->set_flashdata('class', $class);
				redirect('master-franchise/shipment-list');
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
					redirect('master-franchise/shipment-list');
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
					$value = $_SESSION['customer_id'];
					// print_r($_SESSION['customer_id']);die;
					$g_total = $this->input->post('grand_total1');
					$balance = $this->db->query("select * from tbl_franchise where fid = '$value'")->row('credit_limit_utilize');
					// echo $this->db->last_query();die;
					$ShipmentDeducted = $balance + $g_total;
					if($ShipmentDeducted < -1001){
						$msg = "You Dont Have sufficient Balance!";
						$class = 'alert alert-danger alert-dismissible';  
						$this->session->set_flashdata('notify', $msg);
						$this->session->set_flashdata('class', $class);
						redirect('master-franchise/shipment-list');
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
			redirect('master-franchise/shipment-list');
		} else {
			$msg = 'Shipment not added successfully';
			$class = 'alert alert-danger alert-dismissible';
			$this->session->set_flashdata('notify', $msg);
			$this->session->set_flashdata('class', $class);
			redirect('master-franchise/shipment-list');
		}
	}
	public function edit_shipment($id =0){

		$whr = array('booking_id' => $id);
		if ($id != "") 
		{
			$data['booking'] = $this->basic_operation_m->get_table_row('tbl_domestic_booking', $whr);
			
			$data['weight'] = $this->basic_operation_m->get_table_row('tbl_domestic_weight_details', $whr);
			
		}
		// echo '<pre>';print_r($data);die;
		$data['transfer_mode']		 	= $this->basic_operation_m->get_query_result('select * from `transfer_mode`');

		$customer_id 	= $this->session->userdata("customer_id");
		$data['cities']	= $this->basic_operation_m->get_all_result('city', '');
		$data['states'] = $this->basic_operation_m->get_all_result('state', '');

		$data['customers'] = $this->basic_operation_m->get_all_result('tbl_customers', array('customer_id' => $customer_id));

		$data['payment_method']  = $this->basic_operation_m->get_all_result('payment_method', '');
		$data['region_master'] = $this->basic_operation_m->get_all_result('region_master', '');
		$data['bid'] = $id;
		$whr_d = array("company_type" => "Domestic");
		$data['courier_company'] = $this->basic_operation_m->get_all_result("courier_company", $whr_d);
		$data['bid'] 					= $id;
		$this->load->view('masterfranchise/booking_master/edit_shipment', $data);
	}

	public function cancel_shipment_list()
	{
		$user_id = $this->session->userdata("customer_id");
		$data['shipment_list'] = $this->db->query("SELECT tbl_domestic_booking.*,transfer_mode.mode_name as mode_dispatch  FROM `tbl_domestic_booking`LEFT JOIN transfer_mode ON tbl_domestic_booking.mode_dispatch =transfer_mode.transfer_mode_id  WHERE `customer_id`='$user_id' and pickup_in_scan = '2' and branch_in_scan = '2'")->result();
		$this->load->view('masterfranchise/booking_master/cancel_shipment_list', $data);
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
		$query =  $this->db->update('tbl_domestic_booking', $data);
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

					'franchise_id' =>$franchise_id1,
					'customer_id' =>$user_id,
					'transaction_id' =>$franchise_id,
					'payment_date' => $date,
					'credit_amount' =>$booking->grand_total,
				    'balance_amount' =>$update_val,
					'payment_mode' =>$payment_mode,
					'bank_name' =>$bank_name,
					'status' => 1,
					'refrence_no' =>$booking->pod_no
				);

				$whr5 = array('customer_id' => $_SESSION['customer_id']);
					$data1 = array('wallet' => $update_val);
					$result = $this->basic_operation_m->update('tbl_customers', $data1, $whr5);
				// echo '<pre>'; print_r($data9);exit;

				$result =  $this->db->insert('franchise_topup_balance_tbl', $data9);

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

	public function list_commision(){
		$data['shipment_info'] = $this->db->query("SELECT * FROM tbl_franchise_comission WHERE franchise_id = '".$_SESSION['customer_id']."'")->result_array();
		$this->load->view('masterfranchise/booking_master/view_commision',$data);
	}


	
	public function pickup_in_scan_status_insert()
	{
        $data = [];
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
			redirect('master-franchise/pickup-in-scan');
		}
		$this->load->view('masterfranchise/inscan/pickup_inscan_add', $data);
	}

	public function rate_franchise_calculator()
	{
		
			$this->load->view('masterfranchise/booking_master/rate_calculator');
		
	}

	public function pincode_tracking(){
		$data['pincode'] = array();
		if (isset($_GET['pincode']) && !empty($_GET['pincode'])) {
			$pin = $_GET['pincode'];
			$data['pincode'] = $this->db->query("SELECT bs.pincode, b.branch_name, p.* FROM tbl_branch_service bs
				LEFT JOIN tbl_branch b ON(b.branch_id = bs.branch_id)
				LEFT JOIN pincode p ON(p.pin_code = bs.pincode)
				WHERE bs.pincode = $pin
			")->result();
		}
		$this->load->view('masterfranchise/track_service_pincode', $data);
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

		$header = array("AWB No.", "Sender", "Sender City" ,"Receiver", "Receiver City", "Booking date",  "Pay Mode", "Amount","Type" , "NOP", "Invoice No", "Invoice Amount", "Eway No", "Eway Expiry date","Booking Branch");

 
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

	public function update_shipment($id)
		{
			$all_data 		= $this->input->post();
			$all_data2 		= $this->input->post();
        

			if (!empty($all_data)) 
			{
				$whr = array('booking_id' => $id);
				$date = date('Y-m-d',strtotime($this->input->post('booking_date')));
					//booking details//
					
					if($this->input->post('doc_type') == 0)
					{
						$doc_nondoc			= 'Document';
					}
					else
					{
						$doc_nondoc			= 'Non Document';
					}
					
				$username = $this->session->userdata("userName");
				$user_id = $this->session->userdata("userId");
				$user_type = $this->session->userdata("userType");
				$whr_u = array('username' => $username);
				$res = $this->basic_operation_m->getAll('tbl_users', $whr_u);
				$branch_id = $res->row()->branch_id;

				$date = date('Y-m-d',strtotime( $this->input->post('booking_date')));

				$reciever_pincode= $this->input->post('reciever_pincode');
				$reciever_city= $this->input->post('reciever_city');
				$reciever_state= $this->input->post('reciever_state');

				$whr_pincode = array('pin_code'=>$reciever_pincode,'city_id'=>$reciever_city,'state_id'=>$reciever_state); 
				$check_city =$this->basic_operation_m->get_table_row('pincode',$whr_pincode);
				//echo "++++".$this->db->last_query();
				if(empty($check_city) && !empty($reciever_city))
				{	$whr_C =array('id'=>$reciever_city);
					$city_details = $this->basic_operation_m->get_table_row('city',$whr_C);
					$whr_S =array('id'=>$reciever_state);
					$state_details = $this->basic_operation_m->get_table_row('state',$whr_S);
					// print_r($this->input->post('reciever_city')); die;

					$pincode_data = array(
						'pin_code'=>$reciever_pincode,
						'city'=>$city_details->city,
						'city_id'=>$reciever_city,
						'state'=>$state_details->state,
						'state_id'=>$reciever_state);
					
					$whr_p = array('pin_code'=>$reciever_pincode);
					$qry = $this->basic_operation_m->update('pincode', $pincode_data, $whr_p);				
				}
				$is_appointment = ($this->input->post('is_appointment') == 'ON')?1:0;


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
							'per_box_weight_detail' =>json_encode($this->input->post('per_box_weight_detail[]')),
							'weight_details' =>$weight_details,
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
					$customerid= $res->row()->customer_id;
					$data3 = array(
						'tracking_date' => date('Y-m-d H:i:s',strtotime($this->input->post('booking_date')))
					);
					
					// echo '<pre>';print_r($data3);die;
					$where2 = array('status'=>'Booked','pod_no'=>$this->input->post('awn'));
				$query2 = $this->basic_operation_m->update('tbl_domestic_tracking', $data3, $where2);
			
				if ($this->db->affected_rows() > 0) 	
				{
					$data['message'] = "Shipment Updated successfull";
				}
				else 
				{
					$data['message'] = "Failed to Submit";
				}
		
			redirect('master-franchise/shipment-list');
			}
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
		
		 $branch_name = $_SESSION['customer_id'];
		 $resAct=$this->db->query("select distinct manifiest_id,date_added from tbl_domestic_menifiest where destination_franchise ='$branch_name' and reciving_status = '0' ");
        if($resAct->num_rows()>0)
        {
			$data['menifiest']=$resAct->result();
        }
		
	
       	$data['branch_name']= $branch_name;
      // echo $this->db->last_query();
	  
	  if(!empty($id))
	  {
		  $form_data 		= $this->input->post();
			
		  $branch_name = $_SESSION['customer_id'];
			
			$mid=$id;
			 $data['manifiest_id']=$mid;
			 $res=$this->db->query("select * from tbl_domestic_menifiest where user_id ='$branch_name' and manifiest_id='$mid'");
			$data['menifiest_data']=$res->result();
	  }
		
		if (isset($_POST['submit']) ) 
		{
			$form_data 		= $this->input->post();		
			
			$mid=$this->input->post('manifiest_id');
			 $data['manifiest_id']=$mid;
			 $branch_name = $_SESSION['customer_id'];
			 $res=$this->db->query("select * from tbl_domestic_menifiest where user_id='$branch_name' and manifiest_id='$mid' ");
			$data['menifiest_data']=$res->result();
			// echo $this->db->last_query();die;
		}
	
		if(isset($_POST['receving'])) 
		{
			$all_data 		= $this->input->post();
			$date			= $this->input->post('datetime');
			$bag = $this->input->post('bag_no');
			$remark = $this->input->post('note');

			$username = $this->session->userdata("userName");
			$whr = array('username' => $username);
			$res = $this->basic_operation_m->getAll('tbl_users', $whr);
			$branch_id = $res->row()->branch_id;



			$whr = array('branch_id' => $branch_id);
			$res = $this->basic_operation_m->getAll('tbl_branch', $whr);
			$branch_name = $res->row()->branch_name;
			// print_r($all_data);die;
			$this->db->trans_start();
			if (!empty($all_data)) {
				for ($i = 0; $i < count($bag); $i++) {
					$bag_no = $bag[$i];
					$user_id = $this->session->userdata("customer_id");
					$gat_area = $this->db->query("select cmp_area from tbl_franchise where fid = '$user_id'")->row();
					$area = $gat_area->cmp_area;
					// print_r($area);die;
					$branch = $_SESSION['branch_name'];
					if(!empty($area)) {
					    $source_branch = $branch . "_" . $area;
					}else{
						$source_branch = $branch;
					}
					$resAct = $this->db->query("update tbl_domestic_menifiest set reciving_status = '1' where bag_no='$bag_no'");
					$awb_nos = $this->db->query("select pod_no from tbl_domestic_bag where bag_id = '$bag_no'")->result();

					foreach ($awb_nos as $key => $value) {
						date_default_timezone_set('Asia/Kolkata');
						$date = date("Y-m-d H:i:s"); // time in India
						$data = array(
							'pod_no' => $value->pod_no,
							'branch_name' => $source_branch,
							'added_branch' => $source_branch,
							'status' => 'Menifiest In-Scan',
							'remarks' => $remark[$i],
							'tracking_date' => $date,
						);
						$this->basic_operation_m->insert('tbl_domestic_tracking', $data);
					}
				}

			}
			$this->db->trans_complete();
			if ($this->db->trans_status() === TRUE) {
				$this->db->trans_commit();
				$msg = 'Manifest Inscaning successfully ';
				$class = 'alert alert-success alert-dismissible';

				$this->session->set_flashdata('notify', $msg);
				$this->session->set_flashdata('class', $class);
			} else {
				$this->db->trans_rollback();
				$msg = 'some thing went to wrong ';
				$class = 'alert alert-danger alert-dismissible';

				$this->session->set_flashdata('notify', $msg);
				$this->session->set_flashdata('class', $class);
			}
			
			redirect('master_franchise/franchise-incoming');
			
		}
		
		
		$this->load->view('masterfranchise/addincoming', $data);
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


		$resAct = $this->db->query("SELECT tbl_domestic_booking.*,transfer_mode.mode_name as mode_dispatch  FROM `tbl_domestic_booking`LEFT JOIN transfer_mode ON tbl_domestic_booking.mode_dispatch =transfer_mode.transfer_mode_id  WHERE booking_type = 1 $filterCond AND customer_id = '$user_id' GROUP BY tbl_domestic_booking.booking_id order by tbl_domestic_booking.booking_id DESC limit " . $offset . ",100");
		// 	echo $this->db->last_query();exit;
		// 	echo $this->db->last_query();exit;

		$download_query = "SELECT tbl_domestic_booking.*,transfer_mode.mode_name as mode_dispatch  FROM `tbl_domestic_booking`LEFT JOIN transfer_mode ON tbl_domestic_booking.mode_dispatch = transfer_mode.transfer_mode_id  WHERE booking_type = 1 $filterCond AND customer_id = '$user_id' GROUP BY tbl_domestic_booking.booking_id order by tbl_domestic_booking.booking_id DESC limit " . $offset . ",100";





		$this->load->library('pagination');

		$data['total_count']			= $resActt->num_rows();
		$config['total_rows'] 			= $resActt->num_rows();
		$config['base_url'] 			= base_url() . 'Franchise_manager/shipment_list';
		$config['per_page'] 			= 100;
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

		if (isset($_POST['download_report']) && $_POST['download_report'] == 'Excel') {


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

 
} 

