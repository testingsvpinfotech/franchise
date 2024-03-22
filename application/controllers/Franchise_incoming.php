<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Franchise_incoming extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('basic_operation_m');
		if ($this->session->userdata('customer_id') == '') {
			redirect('franchise');
		}
	}

	public function index()
	{


		$data = array();
		$customer_id = $_SESSION['customer_id'];
		// print_r($customer_id);die;

		$resAct = $this->db->query("select  *,SUM(CASE WHEN reciving_status=1 THEN 1 ELSE 0 END)  AS total_coming, COUNT(id) AS total, COUNT(total_pcs) AS total_pcs, COUNT(total_weight) AS total_weight from tbl_domestic_menifiest where tbl_domestic_menifiest.user_id ='$customer_id' group by manifiest_id order by date_added DESC");
		//$resAct=$this->basic_operation_m->getAll('tbl_inword','');
		if ($resAct->num_rows() > 0) {
			$data['allinword'] = $resAct->result_array();
		}
		$this->load->view('franchise/incoming/view_incoming', $data);


	}

	public function addincoming($id = '')
	{
		$data['message'] = ""; //for branch code
		$data['menifiest_data'] = ""; //for branch code
		$data['manifiest_id'] = ""; //for branch code

		$username = $this->session->userdata("userName");
		$whr = array('username' => $username);
		$res = $this->basic_operation_m->getAll('tbl_users', $whr);
		$branch_id = $res->row()->branch_id;

		$whr = array('branch_id' => $branch_id);
		$res = $this->basic_operation_m->getAll('tbl_branch', $whr);
		$branch_name = $res->row()->branch_name;


		//for pod_no
		/* $resAct	= $this->basic_operation_m->getAll('tbl_booking','');

			  if($resAct->num_rows()>0)
			   {
				   $data['pod']=$resAct->result();	            
			   } */

		$customer_id = $_SESSION['customer_id'];
		
		$resAct = $this->db->query("select distinct manifiest_id,date_added from tbl_domestic_menifiest where user_id='$customer_id' and reciving_status = '0'");
		// echo $this->db->last_query();die;
		if ($resAct->num_rows() > 0) {
			$data['menifiest'] = $resAct->result();
		}


		$data['branch_name'] = $branch_name;
		// echo $this->db->last_query();

		if (!empty($id)) {
			$form_data = $this->input->post();


			$customer_id = $_SESSION['customer_id'];

			$date = date('y-m-d');



			$mid = $id;
			$data['manifiest_id'] = $mid;
			$res = $this->db->query("select * from tbl_domestic_menifiest where user_id ='$customer_id' and manifiest_id='$mid' ");
			$data['menifiest_data'] = $res->result();
		}

		if (isset($_POST['submit'])) {
			$form_data = $this->input->post();


			$customer_id = $_SESSION['customer_id'];
			$date = date('y-m-d');



			$mid = $this->input->post('manifiest_id');
			$data['manifiest_id'] = $mid;
			$res = $this->db->query("select * from tbl_domestic_menifiest where user_id ='$customer_id' and manifiest_id='$mid' ");
			$data['menifiest_data'] = $res->result();

		}

		if (isset($_POST['receving'])) {
			$all_data = $this->input->post();
			$date = $this->input->post('datetime');
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
				for ($i = 0; $i <= count($bag); $i++) {
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
			redirect('franchise/view-incoming');

		}


		$this->load->view('franchise/incoming/addincoming', $data);
	}

	public function sendemail($to, $message)
	{
		$config['mailtype'] = 'html';
		$config['charset'] = 'utf-8';
		$config['newline'] = "\r\n";
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		$this->load->library('email');
		$this->email->initialize($config);

		$this->email->from('info@shreelogistics.net', 'shreelogistics Admin');
		$this->email->to($to);


		$this->email->subject('Shipment Update');
		$this->email->message($message);

		$this->email->send();


	}


}




?>