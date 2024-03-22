<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Franchise_incoming_bag extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('basic_operation_m');
		if ($this->session->userdata('customer_id') == '') {
			redirect('franchise');
		}
	}

	public function incomingbag()
	{
		//print_r($this->session->userdata());

		$data = array();
		
		$branch_name = $_SESSION['customer_id'];
		$resAct = $this->db->query("SELECT *, SUM(CASE WHEN tbl_domestic_bag.bag_recived=1 THEN 1 ELSE 0 END) AS total_coming, COUNT(tbl_domestic_bag.id) AS total,
		COUNT(tbl_domestic_bag.total_pcs) AS total_pcs, COUNT(tbl_domestic_bag.total_weight) AS total_weight
		FROM tbl_domestic_menifiest
		LEFT JOIN tbl_domestic_bag ON tbl_domestic_bag.bag_id = tbl_domestic_menifiest.bag_no
		WHERE tbl_domestic_menifiest.user_id='$branch_name' AND reciving_status ='1'
		GROUP BY tbl_domestic_bag.bag_id
		ORDER BY tbl_domestic_bag.date_added DESC");
		//echo $this->db->last_query();exit;
		//$resAct=$this->basic_operation_m->getAll('tbl_inword','');
		if ($resAct->num_rows() > 0) {
			$data['allinword'] = $resAct->result_array();
		}

		$this->load->view('franchise/bag_incoming/view_incoming', $data);
	}

	public function addincomingbag($mid = '')
	{
		$data['message'] = ""; //for branch code
		$data['menifiest_data'] = ""; //for branch code
		$data['manifiest_id'] = ""; //for branch code

		
		$branch_name = $_SESSION['customer_id'];
		$resAct = $this->db->query("select distinct tbl_domestic_bag.bag_id AS bag_no,tbl_domestic_bag.date_added,tbl_domestic_bag.bag_recived from tbl_domestic_menifiest
		LEFT JOIN tbl_domestic_bag ON tbl_domestic_bag.bag_id = tbl_domestic_menifiest.bag_no
		where tbl_domestic_menifiest.destination_franchise='$branch_name' AND tbl_domestic_bag.bag_recived = '0' and tbl_domestic_menifiest.reciving_status = '1' GROUP BY tbl_domestic_bag.bag_id");

		if ($resAct->num_rows() > 0) {
			$data['menifiest'] = $resAct->result();
		}

		$data['branch_name'] = $branch_name;
		// echo $this->db->last_query();

		if (!empty($mid)) {
			$date = date('y-m-d');


			$data['manifiest_id'] = $mid;
			$res = $this->db->query("select * from tbl_domestic_bag where  bag_id='$mid' ");
			//echo $this->db->last_query();exit;
			$data['menifiest_data'] = $res->result();
			//$data['menifiest'] = $res->result();
		}

		if (isset($_POST['submit'])) {


			$date = date('y-m-d');

			$mid = $this->input->post('manifiest_id');
			$data['manifiest_id'] = $mid;
			$res = $this->db->query("select * from tbl_domestic_bag where   bag_id='$mid' ");
			$data['menifiest_data'] = $res->result();
		}
		//echo $_POST['receving'];exit;
		if (isset($_POST['receving'])) {
			$all_data 		= $this->input->post();
			$date			= $this->input->post('datetime');
			$pod			= $this->input->post('pod_no');
			$remark			= $this->input->post('note');

			$username	=	$this->session->userdata("userName");
			$whr 		= 	array('username' => $username);
			$res		=	$this->basic_operation_m->getAll('tbl_users', $whr);
			$branch_id	= 	$res->row()->branch_id;



			$whr		= 	array('branch_id' => $branch_id);
			$res		=	$this->basic_operation_m->getAll('tbl_branch', $whr);
			$branch_name = 	$res->row()->branch_name;
            
			// print_r($all_data);die;
			if (!empty($all_data)) {		
				$this->db->trans_start();
				for( $i = 0; $i < count($pod); $i++ ) {
					$pod_no = $pod[$i];
					$booking_id		=	$this->basic_operation_m->get_table_row('tbl_domestic_booking', "pod_no = '$pod_no'");

						
							$user_id = $this->session->userdata("customer_id");
							$gat_area = $this->db->query("select cmp_area from tbl_franchise where fid = '$user_id'")->row();
							$area = $gat_area->cmp_area;
							// print_r($area);die;
							$branch = $_SESSION['branch_name'];
							if(!empty($area)){
								$source_branch = $branch . "_" .$area ;
							}
							else
							{
								$source_branch = $branch;
							}
							
							$data1 = array(
								'booking_id' => $booking_id->booking_id,
								'pod_no' => $pod_no,
								'status' => 'Bag In-Scan',
								'branch_name' => $source_branch,
								'remarks' => $remark[$i],
								'tracking_date' => $date,
							);
							$this->basic_operation_m->insert('tbl_domestic_tracking', $data1);
							// echo $this->db->last_query();exit; 
							$resAct		=	$this->db->query("update tbl_domestic_bag set bag_recived = '1' where pod_no='$pod_no'");
							$resAct		=	$this->db->query("update tbl_domestic_booking set menifiest_recived = '0' where pod_no='$pod_no'");
				}

				$this->db->trans_complete();
				if ($this->db->trans_status() === TRUE)
				{
					$this->db->trans_commit();
					$msg = 'Bag In-scaning successfully';
					$class = 'alert alert-success alert-dismissible';

					$this->session->set_flashdata('notify', $msg);
					$this->session->set_flashdata('class', $class);
				}
				else
				{
					$this->db->trans_rollback();	
					$msg = 'Some thing went to wrong ';
					$class = 'alert alert-danger alert-dismissible';

					$this->session->set_flashdata('notify', $msg);
					$this->session->set_flashdata('class', $class);
				}
			}
			redirect('franchise_bag/list-incoming-bag');
		}


		$this->load->view('franchise/bag_incoming/addincoming', $data);
	}

	public function viewBagIncoming($mid)
	{
		if (!empty($mid)) {
			$date = date('y-m-d');


			$data['manifiest_id'] = $mid;
			$res = $this->db->query("select * from tbl_domestic_bag where  bag_id='$mid' ");
			//echo $this->db->last_query();exit;
			//$data['menifiest_data'] = $res->result();
			$data['menifiest_data'] = $res->result();
		}
		$this->load->view('franchise/bag_incoming/viewincoming', $data);
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
