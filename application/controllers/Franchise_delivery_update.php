<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Franchise_delivery_update extends CI_Controller {

	function __construct()
	{
		 parent:: __construct();
		 $this->load->model('basic_operation_m');
		 $this->load->model('booking_model');
		 $this->load->model('Invoice_model');
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
			$status = $this->db->query("SELECT * FROM tbl_domestic_tracking where status = 'Out For Delivery' AND pod_no ='$awb' order by id desc limit 1")->row();
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
        $this->load->view('franchise/franchise_delivery_update/single_delivery_update',$data);       
	}

	public function single_delivery_status()
	{
		error_reporting(E_ALL);
ini_set('display_errors', 1);
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
				// print_r($_POST);die;
				$branch_name= $_SESSION['branch_name']." ".$_SESSION['customer_name']." Franchise ";
				$pod_no=$this->input->post('pod_no');
				$status=$this->input->post('status');
				$comment = $this->input->post('comment');
				$remarks = $this->input->post('remark');
				$date=date('Y-m-d H:i:s');
				$pod = $this->input->post('pod');
				$drs_date = $this->db->query("SELECT * FROM tbl_domestic_tracking WHERE status = 'Out For Delivery' AND pod_no ='$pod' ORDER BY id DESC LIMIT 1")->row('tracking_date');
				// echo $this->db->last_query();die;
				$predate = date('Y-m-d', strtotime($drs_date));
				$curret = date('Y-m-d');
				if (
					$predate <= date('Y-m-d', strtotime($this->input->post('datetime'))) &&
					$curret >= date('Y-m-d', strtotime($this->input->post('datetime')))
				) {
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
						$booking_data = $this->db->get_where('tbl_domestic_booking', ['booking_id' => $this->input->post('selected_dockets')])->row();				
						$cust = $this->db->get_where('tbl_customers', ['customer_id' => $booking_data->customer_id])->row();				
						
						if($booking_data->bnf_customer_id !=0){ 
							
							CommssionDeduct($booking_data,$_SESSION['customer_id']);
						
						}
						if ($booking_data->dispatch_details == "TOPAY" || $booking_data->dispatch_details == "ToPay") {
							// if($booking_data->bnf_customer_id !=0){ $status1 = 1;}else{$status1 =0;}
							// print_r($status1);die;
							  TopayDeduct($selected_dockets,0);
							$branch_info = $this->basic_operation_m->getAll('tbl_branch', array('branch_id' => 1))->row();
							$code = $this->booking_model->get_invoice_max_id('tbl_domestic_invoice', 'invoice_no', substr($branch_info->branch_code, -2), $booking_data->dispatch_details);
							$date = date('Y-m-d');
							if (date('m', strtotime($date)) <= 3) {
								$year = (date('Y') - 1) . '-' . (date('Y'));
							} else {
								$year = (date('Y')) . '-' . (date('Y') + 1);
							}
							$max_number = $this->basic_operation_m->get_max_number('tbl_domestic_invoice', 'MAX(inc_num) AS id');
							if (!empty($max_number) && !empty($max_number->id)) {
								$inc_num = (($max_number->id) + 1);
							} else {
								$inc_num = 52;
							}
							$invoice['invoice_no'] = $code;
							$data['company_details'] = $this->basic_operation_m->get_table_row('tbl_company', array('id' => 1));
							$invoice_series = $branch_info->domestic_invoice_series;
							$invoice['inc_num'] = $inc_num;
							$invoice['invoice_number'] = $code;
							$invoice['invoice_date'] = date("Y-m-d");
							$invoice['consigner_name'] = $booking_data->reciever_name;
							$invoice['consigner_address'] = $booking_data->reciever_address;
							$invoice['consigner_city'] = $booking_data->reciever_city;
							$invoice['consigner_gstno'] = $booking_data->receiver_gstno;
							$invoice['consigner_phone'] = $booking_data->reciever_contact;
							$invoice['address'] = $branch_info->address;
							$invoice['city'] = isset($city_data->city) ? $city_data->city : "";
							$invoice['gstno'] = $branch_info->gst_number;
							// $invoice['customer_id'] = $booking_data->customer_id;
							$invoice['invoice_from_date'] = date('Y-m-d');
							$invoice['invoice_to_date'] = date('Y-m-d');
							$invoice['booking_ids'] = json_encode($booking_data->booking_id);
							// $invoice['payment_type'] = $this->input->post('pay_mode');
							$invoice['branch_id'] = $booking_data->branch_id;
							$invoice['createId'] = 1;
							$invoice['createDtm'] = date('Y-m-d H:i:s');
							$invoice['payment_type'] = 'TOPAY';
							$invoice['final_invoice'] = 1;
							$invoice['fin_year'] = '2023-2024';
							$invoice['cgst_amount'] = $booking_data->cgst;
							$invoice['sgst_amount'] = $booking_data->sgst;
							$invoice['igst_amount'] = $booking_data->igst;
							$invoice['total_amount'] = $booking_data->grand_total;
							$invoice['sub_total'] = $booking_data->sub_total;
							$invoice['grand_total'] = $booking_data->grand_total;
							$invoice['franchise_id'] = $_SESSION['customer_id'];
							$whr_c = array('id' => $booking_data->reciever_city);
							$rec_city = $this->basic_operation_m->get_table_row('city', $whr_c);
							// echo "<pre>"; print_r($invoice); die;
							$this->db->insert('tbl_domestic_invoice', $invoice);
							// echo $this->db->last_query();die;
							$invoice_id = $this->db->insert_id();
							if (!empty($invoice_id)) {
								$weight = $this->db->get_where('tbl_domestic_weight_details', ['booking_id' => $booking_data->booking_id])->row();
								$invoice_detail['invoice_id'] = $invoice_id;
								$invoice_detail['booking_id'] = $booking_data->booking_id;
								$invoice_detail['booking_date'] = $booking_data->booking_date;
								$invoice_detail['pod_no'] = $booking_data->pod_no;
								$invoice_detail['doc_type'] = $booking_data->doc_type;
								$invoice_detail['reciever_name'] = $booking_data->reciever_name;
								$invoice_detail['reciever_city'] = $rec_city->city;
								$invoice_detail['mode_dispatch'] = $booking_data->mode_dispatch;
								$invoice_detail['forwording_no'] = !empty($booking_data->forwording_no) ? $booking_data->forwording_no : "";
								$invoice_detail['forworder_name'] = $booking_data->forworder_name;
								$invoice_detail['no_of_pack'] = !empty($weight) ? $weight->no_of_pack : '';
								$invoice_detail['chargable_weight'] = isset($weight) ? $weight->chargable_weight : "";
								$invoice_detail['transportation_charges'] = $booking_data->transportation_charges;
								$invoice_detail['pickup_charges'] = $booking_data->pickup_charges;
								$invoice_detail['delivery_charges'] = $booking_data->delivery_charges;
								$invoice_detail['courier_charges'] = $booking_data->courier_charges;
								$invoice_detail['awb_charges'] = $booking_data->awb_charges;
								$invoice_detail['other_charges'] = $booking_data->other_charges;
								$invoice_detail['frieht'] = $booking_data->frieht;
								$invoice_detail['amount'] = $booking_data->total_amount;
								$invoice_detail['fuel_subcharges'] = $booking_data->fuel_subcharges;
								$invoice_detail['invoice_value'] = $booking_data->invoice_value;
								$invoice_detail['sub_total'] = $booking_data->sub_total;

								$this->db->insert('tbl_domestic_invoice_detail', $invoice_detail);
							}
						}



						
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
						$msg = 'Status Change Successfully';
						$class	= 'alert alert-success alert-dismissible';
	
						$this->session->set_flashdata('notify', $msg);
						$this->session->set_flashdata('class', $class);
					}

				}
				
			} else {
				$msg = 'Please select date In between DRS date to Current date';
				$class = 'alert alert-danger alert-dismissible';
				$this->session->set_flashdata('notify', $msg);
				$this->session->set_flashdata('class', $class);
			}
			// }
			
	  	}

	    redirect("franchise/single-delivery-update");
	}
	

}
?>