<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
class Admin_payment extends CI_Controller {

	function __construct() {
		parent:: __construct();
		$this->load->model('basic_operation_m');
		$this->load->model('booking_model');	
		$this->load->model('Invoice_model');
		//echo __DIR__;exit;
		
		if($this->session->userdata('userId') == '')
		{
			redirect('admin');
		}	
	}

	public function index() {			
		 $data['customers']  = $this->basic_operation_m->get_query_result_array('SELECT * FROM tbl_customers WHERE 1 ORDER BY customer_name ASC');
		 $this->load->view('admin/payment_module/payment_invoice', $data);
	}
	public function view_receipts()
	{
		  $data=array();		  
		   $max_number= $this->basic_operation_m->get_max_number('tbl_invoice_receipt','MAX(inc_num) AS id');		 
		   if(isset($max_number)){$inc_num=(($max_number->id )+1); }else{$inc_num=1;}					 
		  $data['customers']  = $this->basic_operation_m->get_query_result_array('SELECT * FROM tbl_customers WHERE 1 ORDER BY customer_name ASC');
		  $data['payment_method']  = $this->basic_operation_m->get_all_result('payment_method', '');
		  $query ='SELECT * FROM tbl_invoice_receipt LEFT JOIN tbl_customers ON tbl_invoice_receipt.customer_id=tbl_customers.customer_id';
		  $data['receipt_details']  = $this->basic_operation_m->get_query_result_array($query);
		  $data['entry_no'] = "Receipt-".$inc_num;
		  $data['inc_num'] = $inc_num;
		  $this->load->view('admin/payment_module/view_receipts', $data);
	}
	public function get_receipt(){
		
		$edit_id= $this->input->post('edit_id');
		$whr= array('id'=>$edit_id);
		$data['receipt_details']  = $this->basic_operation_m->get_table_row("tbl_invoice_receipt",$whr);		
		echo json_encode($data);
		
	}
	public function add_receipts(){
	  if (isset($_POST['submit'])) 
	   {			
			$all_data = $_POST;
			$all_data['available_amount'] = $_POST['amount_recieved'];
			unset($all_data['submit']);
			$edit_id = $_POST['edit_id'];
			if($edit_id!="")
			{
				$whr=array('id'=>$edit_id);
				unset($all_data['edit_id']);
				$this->basic_operation_m->update('tbl_invoice_receipt',$all_data,$whr);
				$msg   = 'Receipt updated successfully';
				$class = 'alert alert-success alert-dismissible';
			}
			else{
				unset($all_data['edit_id']);
				$this->basic_operation_m->insert('tbl_invoice_receipt',$all_data);
				$msg   = 'Receipt added successfully';
				$class = 'alert alert-success alert-dismissible';
			}
				
			$this->session->set_flashdata('notify',$msg);
		    $this->session->set_flashdata('class',$class);
			
			redirect('admin/list-invoice-receipt');
			//print_R($all_data);exit;
	   }
		
	}
	public function add_adjust_payment(){
	
	  if (isset($_POST['submit'])) 
	   {			
			$all_data = $_POST;
			$username = $this->session->userdata("userName");
			$invoice_id =$_POST['invoice_id']; 
			$customer_id =$_POST['customer_id']; 
			$invoice_number =$_POST['invoice_number']; 
			$invoice_date =$_POST['invoice_date']; 
			$invoice_amount =$_POST['invoice_amount']; 
			$reference_no =$_POST['reference_no']; 
			$reference_date =$_POST['reference_date']; 
			$reference_amt =$_POST['reference_amount'];
		
			$reference_available_amt =$_POST['available_amount']; 
			$db_aval_reference_amount =$_POST['db_aval_reference_amount']; 
			
			//echo "<pre>";
			//print_r($reference_available_amt);
			//exit;
			$amount_recieved =$_POST['amount_recieved']; 
			$discount =$_POST['discount']; 
			$tds_amt =$_POST['tds_amt']; 			
			$balance_amt =$_POST['balance_amt']; 
			$edit_id =$_POST['edit_id']; 
			//echo "<pre>";
			//print_r($edit_id);exit;
			$edit_invoice_number =$_POST['edit_invoice_number'];			
			$status=0;
			
			$reference_mapped_amt=array();
			for($i=0;$i<count($invoice_number);$i++)
			{
				$reference_mapped_amt[$i] = ((float)($amount_recieved[$i]) + (float)($discount[$i]) ); //+ (float)($tds_amt[$i])
				if((float)($invoice_amount[$i])==((float)($amount_recieved[$i]) + (float)($discount[$i]) + (float)($tds_amt[$i]) ))   //+ (float)($tds_amt[$i])
				{
					$status=1;
				}else{
					$status=0;
				}
				$data=array(
					//'edit_id'=>$edit_id[$i],
					'invoice_id'=>$invoice_id[$i],
					'customer_id'=>$customer_id,
					'invoice_number'=>$invoice_number[$i],
					'invoice_date'=>$invoice_date[$i],
					'reference_no'=>$reference_no[$i],
					'reference_date'=>$reference_date[$i],
					'reference_amt'=>$reference_amt[$i],		
					'reference_available_amt'=>$reference_available_amt[$i],		
					'invoice_amount'=>$invoice_amount[$i],	
					'reference_mapped_amt'=>$reference_mapped_amt[$i],							
					'amount_recieved'=>$amount_recieved[$i],
					'discount'=>$discount[$i],
					'tds_amt'=>$tds_amt[$i],
					'balance_amt'=>$balance_amt[$i],
					'applied_by'=>$username,
					'applied_at'=>date("Y-m-d"),
					'status'=>$status,
					);
				//	echo "<pre>";
				//	print_r($data);
					//exit;
					
					 $whr = array('amount_recieved'=>$amount_recieved[$i],'discount'=>$discount[$i],'tds_amt'=>$tds_amt[$i]);
					 $exits_val = $this->basic_operation_m->get_table_row('tbl_invoice_payments',$whr);
					 $received_amt = $exits_val->amount_recieved;
					 
					
					if($received_amt !="0.00"){
						$this->basic_operation_m->insert('tbl_invoice_payments',$data);	
					}
						
						
			
			   /*if($edit_id[$i]!="") // && $edit_invoice_number[$i]==$invoice_number[$i]
			   {
				   $whr_up = array('id'=>$edit_id[$i],'customer_id'=>$customer_id);
				   $this->basic_operation_m->update('tbl_invoice_payments',$data,$whr_up);	
			   }else{ */
			   
				  //	$this->basic_operation_m->insert('tbl_invoice_payments',$data);	
				  	
			   /* } */
			   				
				$available_amount = (float)$reference_available_amt[$i] - (float)$reference_mapped_amt[$i];
			  
			   $query= "SELECT SUM(`reference_mapped_amt`) AS mapped_payment,tds_amt FROM tbl_invoice_payments WHERE reference_no='$reference_no[$i]'";
			   
			   $mapped_payment_details = $this->basic_operation_m->get_query_row($query);
			   $mapped_payment = $mapped_payment_details->mapped_payment;
			   $tds_amt =  $mapped_payment_details->mapped_payment;
			   
			   if((float)$reference_amt[$i]==((float)$mapped_payment))
				{
					$receipt_status=1;
				}else{
					$receipt_status=0;
				}
			if($mapped_payment > 0.00){
			   $data_receipt = array('available_amount'=>$available_amount,'mapped_payment'=>$mapped_payment,'receipt_status'=>$receipt_status);
			   $whr_r =array('customer_id'=>$customer_id,'id'=>$reference_no[$i]);
			   $this->basic_operation_m->update('tbl_invoice_receipt',$data_receipt,$whr_r);
			}
		 }
			//exit;
			redirect("admin/list-payments-invoice");
	   }
		
	}
   public function show_payment_invoice_list()
   {
	   if (isset($_POST['submit'])) 
	   {
			$customer_id =$this->input->post('customer_account_id');		   
			//$where = array('customer_id'=>$this->input->post('customer_account_id'),);
			
			$query_alerdy_map_invoice= "SELECT * FROM  tbl_invoice_payments  WHERE customer_id='$customer_id' AND status='1'";
			$data['alerdy_map_invoice'] = $this->basic_operation_m->get_query_result_array($query_alerdy_map_invoice);
			
			$whr_con="";
			if(!empty($data['alerdy_map_invoice']))
			{
					$exit_inv_id=array();
					foreach($data['alerdy_map_invoice'] as $exit_val)
					{
						$exit_inv_id[] =$exit_val['invoice_number'];
					}
					$exit_inv_id_arr ="'".implode("','",$exit_inv_id)."'";   
					$whr_con = "AND invoice_number NOT IN($exit_inv_id_arr) ";
			}
			$query_int = "SELECT id,invoice_number,invoice_date,cgst_amount,sgst_amount,igst_amount,grand_total,customer_name,customer_id,gstno FROM tbl_international_invoice  WHERE customer_id='$customer_id' $whr_con ";
			$data['inv_list_int'] = $this->basic_operation_m->get_query_result_array($query_int);
			
			$query_dom = "SELECT id,invoice_number,invoice_date,cgst_amount,sgst_amount,igst_amount,grand_total,customer_name,customer_id,gstno FROM tbl_domestic_invoice  WHERE customer_id='$customer_id' $whr_con ";
			$data['inv_list_dom'] = $this->basic_operation_m->get_query_result_array($query_dom);
			
			if(!empty($query_int))
			{
				$data['inv_list'] = $data['inv_list_int'];
			}
			if(!empty($query_dom))
			{
				$data['inv_list'] = $data['inv_list_dom'];
			}
			if(!empty($query_int) && !empty($query_dom))
			{
				$data['inv_list'] = array_merge($data['inv_list_int'],$data['inv_list_dom']);
			}
						
			$rec_query_total = "SELECT SUM(`amount_recieved`) AS total_amount FROM tbl_invoice_receipt  WHERE customer_id='$customer_id' AND receipt_status='0'"; // status=pending
			$data['total_recivable_amt'] = $this->basic_operation_m->get_query_row($rec_query_total);
			
			$rec_query = "SELECT * FROM tbl_invoice_receipt  WHERE customer_id='$customer_id' AND receipt_status='0'"; // status=pending
			$data['receipt_list'] = $this->basic_operation_m->get_query_result_array($rec_query);
			
			$data['customers']  = $this->basic_operation_m->get_query_result_array('SELECT * FROM tbl_customers WHERE 1 ORDER BY customer_name ASC');		
		    $this->load->view('admin/payment_module/payment_invoice', $data);
		}
		
   }
   public function invoicePayment()
   {
        $data = [];
      	$this->load->view('invoicePayment', $data);
   }

   public function payments(){	
		$data = array();
		$this->load->helper('form_helper');
		if ($this->session->userdata("userType") == '1') {
			$resAct = $this->db->query("select * from tbl_international_invoice where final_invoice = 1");
			if ($resAct->num_rows() > 0) {
				$data['allpoddata'] = $resAct->result_array();
			}
		} else {
			$username = $this->session->userdata("userName");
			$whr = array('username' => $username);
			
			$res=$this->basic_operation_m->getAll('tbl_users',$whr);
		 	$branch_id = $res[0]['branch_id'];

 
			$resAct = $this->db->query("select * from tbl_international_invoice where branch_id='$branch_id' and final_invoice = 1");
			if ($resAct->num_rows() > 0) {
				$data['allpoddata'] = $resAct->result_array();
			}
		}
		$resInoviceSetting = $this->db->query("select * from tbl_invoice_setting");
		$invoiceSettings = $resInoviceSetting->row();
		$prefix = $invoiceSettings->prefix.'_';
		unset($resInoviceSetting);
		$data['prefix'] = $prefix;
		$this->load->view('admin/booking_master/view_payments', $data);
	
   }

   	public function view_payment($invId){
		if ($this->session->userdata("userName") != "") {
		$data = array();
		$data['invoice_id'] = $invId;
		$this->load->helper('form_helper');
		if ($this->session->userdata("userType") == '1') {
			$resAct = $this->db->query("select * from tbl_invoice where final_invoice = 1");
			if ($resAct->num_rows() > 0) {
				$data['allpoddata'] = $resAct->result_array();
			}
		} else {
			$username = $this->session->userdata("userName");
			$whr = array('username' => $username);

			$res=$this->basic_operation_m->getAll('tbl_users',$whr);
			 $branch_id = $res[0]['branch_id'];

//                $data = array(); 
			$resAct = $this->db->query("select * from tbl_invoice where branch_id='$branch_id' and final_invoice = 1");
			if ($resAct->num_rows() > 0) {
				$data['allpoddata'] = $resAct->result_array();
			}
		}
		$resInoviceSetting = $this->db->query("select * from tbl_invoice_setting");
		$invoiceSettings = $resInoviceSetting->row();
		$prefix = $invoiceSettings->prefix.'_';
		unset($resInoviceSetting);
		$data['prefix'] = $prefix;
			$this->load->view('view_payments', $data);
		} else {
			redirect(base_url() . 'login');
		}
   	}

   public function save_payment(){
	   $invoice_id = $this->input->post('invoice_id');
	   $amount_recieved = $this->input->post('amount_recieved');
	   $payment_date = $this->input->post('payment_date');
	   $payment_mode = $this->input->post('payment_mode');
	   $note = $this->input->post('note');

	   $resAct = $this->db->query("select * from tbl_international_invoice where id = $invoice_id");
	   $invoiceData = $resAct->row_array();
	   //print_r($invoiceData); die;
	   $invoiceAmt = $invoiceData['amount'];

	   $paymenttype = 'Full';
	   if($invoiceAmt > $amount_recieved)
	   {
			$paymenttype = 'Partial';
	   }

	   $customerId = $this->session->userdata('userId');

	   $savedata['user_id'] = $customerId;
	   $savedata['invoice_id'] = $invoice_id;
	   $savedata['invoice_amount'] = $invoiceAmt;
	   $savedata['amount_recieved'] = $amount_recieved;
	   $savedata['payment_type'] = $paymenttype;
	   $savedata['payment_date'] = $payment_date;
	   $savedata['payment_mode'] = $payment_mode;
	   $savedata['note'] = $note;

	   $this->db->insert('tbl_international_invoice_payments', $savedata);
	   $insertId = $this->db->insert_id();

	   if($insertId)
	   {
		$this->session->set_flashdata('success', 'Payment saved successfully');
	   }
	   else{
		$this->session->set_flashdata('error', 'Something is went wrong! please try after some time');
	   }

	   redirect('admin/list-international-payments');
	}
	/*public function payment_history(){		
		$userid = $this->session->userdata('userId');
		$query_his = "SELECT tbl_invoice_payments.*,tbl_customers.customer_name,entry_no,tbl_invoice_receipt.reference_no,tbl_invoice_receipt.payment_method,tbl_invoice_receipt.id AS r_del_id,tbl_invoice_payments.id AS p_del_id  FROM tbl_invoice_payments LEFT JOIN tbl_customers ON tbl_invoice_payments.customer_id=tbl_customers.customer_id  LEFT JOIN tbl_invoice_receipt ON tbl_invoice_payments.reference_no=tbl_invoice_receipt.id WHERE tbl_invoice_payments.reference_mapped_amt >0 ";
		$data['payment_history'] = $this->basic_operation_m->get_query_result_array($query_his);

		$this->load->view('admin/payment_module/view_payment_history', $data);
	}*/
	public function payment_history(){		
		$userid = $this->session->userdata('userId');
		$data=array();
		$all_data = $this->input->post();		
		if(!empty($all_data)){
			
			//$whr ="1=1";
			$whr ="";	
					
			$customer_id = $this->input->post('customer_id');		
		    if($customer_id!="ALL"){
    				$whr.=" AND tbl_invoice_payments.customer_id='$customer_id'";
    				
            }
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');	
			if($from_date!="" && $to_date!="")
			{
			    $from_date = date("Y-m-d",strtotime($this->input->post('from_date')));
			    $to_date = date("Y-m-d",strtotime($this->input->post('to_date')));	
				$whr.=" AND  invoice_date >='$from_date' AND invoice_date <='$to_date'";
			}	
			$query_his = "SELECT tbl_invoice_payments.*,tbl_customers.customer_name,entry_no,tbl_invoice_receipt.reference_no,tbl_invoice_receipt.payment_method,tbl_invoice_receipt.id AS r_del_id,tbl_invoice_payments.id AS p_del_id  FROM tbl_invoice_payments LEFT JOIN tbl_customers ON tbl_invoice_payments.customer_id=tbl_customers.customer_id  LEFT JOIN tbl_invoice_receipt ON tbl_invoice_payments.reference_no=tbl_invoice_receipt.id WHERE tbl_invoice_payments.reference_mapped_amt >0 $whr ";
		$data['payment_history'] = $this->basic_operation_m->get_query_result_array($query_his);
			
		}
		$qury = "SELECT * FROM tbl_customers WHERE 1 ORDER BY customer_name ASC";
		$data['customers_list']= $this->basic_operation_m->get_query_result_array($qury);
		
	//	$data['customers_list']= $this->basic_operation_m->get_all_result("tbl_customers","");
		$this->load->view('admin/payment_module/view_payment_history', $data);
	}
	
// 	public function delete_receipts($id){
// 		if(!empty($id))
// 		{
// 			$whr1 =array('reference_no'=>$id);
// 			$del_rec = $this->basic_operation_m->delete("tbl_invoice_payments",$whr1);
			
// 			$whr =array('id'=>$id);
// 			$del_rec = $this->basic_operation_m->delete("tbl_invoice_receipt",$whr);
						
// 			$msg = 'Receipt deleted successfully';
// 			$class = 'alert alert-success alert-dismissible';	
// 			$this->session->set_flashdata('notify',$msg);
// 			$this->session->set_flashdata('class',$class);
			
// 			redirect('admin/list-invoice-receipt');
// 		}
		
// 	}
	
	public function delete_receipts(){
	    $id = $this->input->post('getid');
		if(!empty($id))
		{
			$whr1 =array('reference_no'=>$id);
			$del_rec = $this->basic_operation_m->delete("tbl_invoice_payments",$whr1);
			
			$whr =array('id'=>$id);
			$del_rec = $this->basic_operation_m->delete("tbl_invoice_receipt",$whr);
			
			$output['status'] = 'success';
			$output['message'] = 'Receipts deleted successfully';
		}
		else{
			$output['status'] = 'error';
			$output['message'] = 'Something went wrong in deleting the Receipts';
		}
 
		echo json_encode($output);				
	}		
		
		
	
	public function delete_payement($r_del_id,$p_del_id){
		if(!empty($r_del_id))
		{			
			$whr_p =array('id'=>$p_del_id);
			$tbl_invoice_payments_details = $this->basic_operation_m->get_table_row('tbl_invoice_payments',$whr_p);
			$reference_mapped_amt =$tbl_invoice_payments_details->reference_mapped_amt;
			
			$whr =array('id'=>$r_del_id);
			$tbl_invoice_receipt_details = $this->basic_operation_m->get_table_row('tbl_invoice_receipt',$whr);
			$available_amount =$tbl_invoice_receipt_details->available_amount;
			$mapped_payment =$tbl_invoice_receipt_details->mapped_payment;
			
			$available_amount1 = (float)$available_amount + (float)$reference_mapped_amt;
			$mapped_payment1 = (float)$mapped_payment - (float)$reference_mapped_amt;
			
			$data=array('available_amount'=>$available_amount1,'mapped_payment'=>$mapped_payment1,'receipt_status'=>'0');
			$this->basic_operation_m ->update('tbl_invoice_receipt',$data,$whr);			
			
			$whr_d =array('id'=>$p_del_id);
			$del_rec = $this->basic_operation_m->delete("tbl_invoice_payments",$whr_d);
						
			$msg = 'Receipt deleted successfully';
			$class = 'alert alert-success alert-dismissible';	
			$this->session->set_flashdata('notify',$msg);
			$this->session->set_flashdata('class',$class);
			
		    redirect('admin/list-history');
		}
		
	}

}

?>
