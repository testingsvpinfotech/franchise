<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Mobileapp extends CI_Controller {

	public function __construct()
	{
	   	header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Credentials: true");
		header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
		header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
		header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
		header("Content-Type: application/json; charset=utf-8");
		parent:: __construct();
		$this->load->model('login_model');
		$this->load->model('basic_operation_m');

		

		// exit();



	}

	public function courier_partners(){
		$whr_d= array("company_type"=>"Domestic");
		$data['courier_company'] = $this->basic_operation_m->get_all_result("courier_company",$whr_d);
		echo json_encode($data);	 
	}
	public function transfer_modes(){
		$data['transfer_modes']= $this->basic_operation_m->get_query_result('SELECT * FROM transfer_mode');
		echo json_encode($data);	 
	}
	public function check_airways_num(){
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$awn_query = $this->db->query("SELECT `tbl_domestic_booking`.`pod_no` FROM `tbl_domestic_booking` WHERE pod_no='".$request->airways_number."'"); 
		// print_r($awn_query); die;
		if ($awn_query->num_rows() > 0) {
			$data['status'] = false;
			$data['result'] = 'fail';
			$data['message'] = 'Airways number already exists, please try another';
		} else {
			$data['status'] = true;
			$data['result'] = 'success';
			$data['message'] = 'Airways number not present';
		}
		echo json_encode($data);	 
	}
	public function bill_types(){
		$array = [
			['id'=>'Credit','name'=>'Credit'],
			['id'=>'Cash','name'=>'Cash'],
			['COD'=>'Cash','name'=>'COD'],
			['ToPay'=>'Cash','name'=>'ToPay'],
		];
		
		$data['bill_types']= $array;
		echo json_encode($data);	 
	}
	public function product_types(){
		$array = [
			['id'=>'1','name'=>'Non-Doc'],
			['id'=>'0','name'=>'Doc'],
		];
		
		$data['product_types']= $array;
		echo json_encode($data);	 
	}
		
	public function get_customers(){

		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata, true);
		// print_r($request); die;
		$user_id = $request['user_id'];
		
		$whr = array('user_id'=>$user_id);
		$res=$this->basic_operation_m->getAll('tbl_users',$whr);
		// echo $this->db->last_query();
		$branch_id= @$res->row()->branch_id;

		
		if (!empty($branch_id)) {
			$whr = array('branch_id'=>$branch_id);

			$data['customers'] =$this->basic_operation_m->get_all_result('tbl_customers',$whr);
		}else{
			$data['customers'] = array();
		}
		
		echo json_encode($data);
	}
	public function get_all_cities(){
		$data['cities']= $this->basic_operation_m->get_all_result('city', '');
		echo json_encode($data);
	}
	public function get_all_states(){
		$data['states']= $this->basic_operation_m->get_all_result('state', '');
		echo json_encode($data);
	}

	public function getPincodeInfo(){
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$pincode = $request->pincode;

		$res1 = $this->basic_operation_m->selectRecord('pincode', array('pin_code' => $pincode));

		$city_id = @$res1->row()->city_id;
		if ($city_id) {
			// $whr2 = array('id' => $city_id);
			// $res2 = $this->basic_operation_m->get_table_row('city', $whr2);
			// echo $pincode_city = $res2->id;

			$state_id = $res1->row()->state_id;
			// $whr3 = array('id' => $state_id);
			// $res3 = $this->basic_operation_m->get_table_row('state', $whr3);
			// $pincode_state = $res3->id;

			$reciever_state = $state_id;
			$reciever_city =  $city_id;

			$whr1 = array('state' => $reciever_state,'city' => $reciever_city);
			$res1 = $this->basic_operation_m->selectRecord('region_master_details', $whr1);
		
			$regionid = @$res1->row()->regionid;

			if (!$regionid) {
				$array['data']['result']="fail";	
				$array['data']['message']="Rate is not available in this pincode!";
				echo json_encode($array);exit();
			}

			$whr3 = array('region_id' => $regionid);
			$res3 = $this->basic_operation_m->selectRecord('region_master', $whr3);
			$result3 = $res3->row();

			$array['data'] = ['selected_city_id'=>$city_id, 'selected_state_id'=>$state_id, 'zone'=>$result3];
			$array['data']['result']="success";	
			$array['data']['message'] = 'Success';
		}
		else{
			$array['data']['result']="fail";	
			$array['data']['message']="Service is not available in this pincode";	
		}

		echo json_encode($array);

		die;
	}

	public function addShipment() {
		
		$postdata = file_get_contents("php://input");
		$postData = json_decode($postdata);

		$this->write_request_file();
		

		
		$settingData = [];
		$resAct = $this->db->query("select * from setting");
		$setting = $resAct->result();
		foreach ($setting as $value):
			$settingData[$value->key] = $value->value;
		endforeach;
	
		// $username =  $postData->user_name;//LU0001
		$user_id =  $postData->user_id;
		$whr = array('user_id' => $user_id);
		$res = $this->basic_operation_m->getAll('tbl_users', $whr);
		// echo $this->db->last_query();
		$uerdata = $res->row();
		// print_r($uerdata); 
		$username = $uerdata->username;
		$branch_id = $uerdata->branch_id;
		$user_id = $uerdata->user_id;
		$user_type = $uerdata->user_type;


		$date = date('Y-m-d H:i:s',strtotime($postData->booking_date));
		
		$rateData = $this->getRateMasterDetails($postData->customer_id, $postData->sender_city, $postData->reciever_city, $postData->mode_dispatch);
		
		if($postData->doc_type == 0)
		{
			$doc_nondoc			= 'Document';
		}
		else
		{
			$doc_nondoc			= 'Non Document';
		}	
		$result 		= $this->db->query('select max(booking_id) AS id from tbl_domestic_booking')->row();
		$id 			= $result->id + 1;
		
		if (strlen($id) == 2) 
		{
			$id = 'BK000'.$id;
		}
		elseif (strlen($id) == 3) 
		{
			$id = 'BK100'.$id;
		}
		elseif (strlen($id) == 1) 
		{
			$id = 'BK10000'.$id;
		}
		elseif (strlen($id) == 4) 
		{
			$id = 'BK10'.$id;
		}
		elseif (strlen($id) == 5) 
		{
			$id = 'BK1'.$id;
		}		
		$pod_no =trim($postData->awn);
		if($pod_no!="")
		{
			$awb_no = $pod_no;
		}
		else
		{
			$awb_no =$id;
		}
		
		$data = array(
			'doc_type'=>$postData->doc_type,
			'doc_nondoc'=>$doc_nondoc,
			'courier_company_id'=>$postData->courier_company_id,
			'company_type'=>'Domestic',
			'mode_dispatch' => $postData->mode_dispatch,
			'pod_no' => $awb_no,
			'forwording_no' => $postData->forwording_no,
			'forworder_name' => $postData->forworder_name,
			'customer_id' => $postData->customer_id,
			'sender_name' => $postData->sender_name,
			'sender_address' => $postData->sender_address,
			'sender_city' => $postData->sender_city,
			'sender_state'=> $postData->sender_state,
			'sender_pincode' => $postData->sender_pincode,
			'sender_contactno' => $postData->sender_contactno,
			'sender_gstno' => $postData->sender_gstno,
			'reciever_name' => $postData->reciever_name,
			'contactperson_name' => $postData->contactperson_name,				
			'reciever_address' => $postData->reciever_address,
			'reciever_contact' => $postData->reciever_contact,
			'reciever_pincode' => $postData->reciever_pincode,
			'reciever_city' => $postData->reciever_city,
			'reciever_state' => $postData->reciever_state,
			'receiver_zone' => $postData->receiver_zone,
			'receiver_zone_id' => $postData->receiver_zone_id,
			'receiver_gstno'=>$postData->receiver_gstno,
			'ref_no'=>$postData->ref_no,
			'invoice_no'=>$postData->invoice_no,
			'invoice_value' => $postData->invoice_value,
			'eway_no' => $postData->eway_no,
			'special_instruction' => $postData->special_instruction,
			//'type_of_pack' => $this->input->post('type_of_pack'),
			'booking_date' =>$date,				
			'dispatch_details' => $postData->dispatch_details,
			'payment_method' => $postData->payment_method,
			'frieht' => $postData->frieht,
			'transportation_charges' => $postData->transportation_charges,
			'pickup_charges' => $postData->pickup_charges,
			'delivery_charges' => $postData->delivery_charges,
			'courier_charges' => $postData->courier_charges,
			'awb_charges' => $postData->awb_charges,
			'other_charges' => $postData->other_charges,
			'total_amount' => $postData->amount,
			'fuel_subcharges' => $postData->fuel_subcharges,
			'sub_total' => $postData->sub_total,		
			'cgst' => $postData->cgst,			
			'sgst' => $postData->sgst,			
			'igst' => $postData->igst,			
			'grand_total' => $postData->grand_total,		
			'user_id' =>$user_id,
			'user_type' =>$user_type,				
			'branch_id' => $branch_id,
			'booking_type'=>1,
		);

		$whr = array('pod_no' => $awb_no);
		$res = $this->basic_operation_m->getAll('tbl_domestic_booking', $whr);
		if($res->num_rows()){
			echo json_encode([
				'status' => 'error',
				'message' => "Already Exist ". $awb_no.'<br>'
				]);
		
			exit;
		}
		else{
			// echo '<pre>'; print_r($data); die;
			$query = $this->basic_operation_m->insert('tbl_domestic_booking', $data);
		
			$lastid = $this->db->insert_id();

			if ($postData->valumetric_weight > $postData->chargable_weight) {
				$postData->chargable_weight = $postData->valumetric_weight;
			}
		
			$data2 = array(
				'booking_id' => $lastid,
				'actual_weight' => $postData->actual_weight,
				'valumetric_weight' => $postData->valumetric_weight,
				'length' => $postData->length,
				'breath' => $postData->breath,
				'height' => $postData->height,
				'chargable_weight' => $postData->chargable_weight,
				'per_box_weight' => $postData->per_box_weight,
				'no_of_pack' => $postData->no_of_pack,
				'actual_weight_detail' => $postData->actual_weight,
				'valumetric_weight_detail' => $postData->valumetric_weight_detail,
				'chargable_weight_detail' => $postData->chargable_weight,
				'length_detail' => $postData->length_detail,
				'breath_detail' => $postData->breath_detail,
				'height_detail' => $postData->height_detail,
				'no_pack_detail' => $postData->no_of_pack,
				'per_box_weight_detail' =>$postData->per_box_weight_detail,
			);
		
		
			// echo '<pre>'; print_r($data); die;
			$query2 = $this->basic_operation_m->insert('tbl_domestic_weight_details', $data2);
			// echo $lastidw = $this->db->insert_id();

			$whr = array('branch_id' => $branch_id);
			$res = $this->basic_operation_m->getAll('tbl_branch', $whr);
			$branch_name = $res->row()->branch_name;

			$whr = array('booking_id' => $lastid);
			$res = $this->basic_operation_m->getAll('tbl_domestic_booking', $whr);
			$podno = $res->row()->pod_no;
			$customerid= $res->row()->customer_id;
			$data3 = array('id' => '',
				'pod_no' => $podno,
				'status' => 'Booked',
				'branch_name' => $branch_name,
				'tracking_date' => $date,
				'booking_id' => $lastid,
				'forworder_name' => $data['forworder_name'],
				'forwording_no' => $data['forwording_no'],
				'is_spoton' => ($data['forworder_name'] == 'spoton_service') ? 1 : 0,
				'is_delhivery_b2b' => ($data['forworder_name'] == 'delhivery_b2b') ? 1 : 0,
				'is_delhivery_c2c' => ($data['forworder_name'] == 'delhivery_c2c') ? 1 : 0
			);

			$result3 = $this->basic_operation_m->insert('tbl_domestic_tracking', $data3);
			if ($postData->customer_id!="") {
				$whr = array('customer_id'=>$customerid);
				$res=$this->basic_operation_m->getAll('tbl_customers', $whr);
				$email= $res->row()->email;
			}

			$message='Your Shipment '.$podno.' status:Boked  At Location: '.$branch_name;
			if ($lastid) {
				echo json_encode([
					'status' => 'success',
					'booking_id' => $lastid,
					'message' => $message,
				]);
				exit;
			} else {
				echo json_encode([
				'status' => 'error',
				'message' => 'Booking not created successfully',
				]);
		
				exit;
			}
		}
	}
	public function getFuelCharges(){
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		
		$customer_id = $request->customer_id;
		$courier_id = $request->courier_id;
		$booking_date = $request->booking_date;
		$sub_amount = $request->sub_amount;
		$dispatch_details = $request->dispatch_details;
		
		$current_date = date("Y-m-d");
		
	    $current_date = date("Y-m-d",strtotime($booking_date));
		
		$whr1 = array('courier_id' => $courier_id,'fuel_from <=' => $current_date,'fuel_to >=' => $current_date,'customer_id =' => $customer_id);
		$res1 = $this->basic_operation_m->get_table_row('courier_fuel', $whr1);
		if(empty($res1))
		{
			$whr1 = array('courier_id' => $courier_id,'fuel_from <=' => $current_date,'fuel_to >=' => $current_date,'customer_id =' => '0');
			$res1 = $this->basic_operation_m->get_query_row("select * from courier_fuel where (courier_id = '$courier_id' or courier_id='0') and fuel_from <= '$current_date' and fuel_to >='$current_date' and (customer_id = '0' or customer_id = '$customer_id')");
		}
		
		//$whr1 = array('courier_id' => $courier_id,'fuel_from <=' => $current_date,'fuel_to >=' => $current_date);
		//$res1 = $this->basic_operation_m->get_table_row('courier_fuel', $whr1);
		if($res1){$fuel_per = $res1->fuel_price; }else{$fuel_per ='0';}

		$final_fuel_charges =($sub_amount * $fuel_per/100);
			
		$sub_total =($sub_amount + $final_fuel_charges);
		

        $whr2 = array('from <=' => $current_date,'to >=' => $current_date);
        $gst_details = $this->basic_operation_m->get_table_row('tbl_gst_setting', $whr2);

            //echo $this->db->last_query();

		if($gst_details){
			$cgst_per = $gst_details->cgst; 
			$sgst_per = $gst_details->sgst; 
			$igst_per = $gst_details->igst; 
		}else{
			$cgst_per = '0'; 
			$sgst_per = '0'; 
			$igst_per = '0'; 
		}    
		
	  
	   
	   $tbl_customers_info 		= $this->basic_operation_m->get_query_row("select gst_charges from tbl_customers where customer_id = '$customer_id'");
			
		if($tbl_customers_info->gst_charges == 1)
		{
			$cgst = ($sub_total*$cgst_per/100);
			$sgst = ($sub_total*$sgst_per/100);
			$igst = 0;	
		}
		else
		{
			$cgst = 0;
			$sgst = 0;
			$igst = 0;
		}
		
		if($dispatch_details == 'Cash')
		{	
			$cgst = 0;
			$sgst = 0;
			$igst = 0;
		}
	   
	   
	   $grand_total = $sub_total + $cgst + $sgst + $igst;
		

		$result2= array('final_fuel_charges'=>$final_fuel_charges,
			'sub_total'=>number_format($sub_total, 2, '.', ''),
            'cgst'=>number_format($cgst, 2, '.', ''),
            'sgst'=>number_format($sgst, 2, '.', ''),
			'igst'=>number_format($igst, 2, '.', ''),
			'grand_total'=>number_format($grand_total, 2, '.', ''),
		);
		echo json_encode($result2);
	}

	public function getMasterRates222(){
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$mode = $request->mode;
		$customer_id = $request->customer_id;
		$courier_id = $request->courier_id;
		$zone = $request->zone;
		$booking_date = $request->booking_date;
		$weight = $request->weight;
		$current_date = date("Y-m-d",strtotime($booking_date));

		$whr1 = array('c_courier_id' => $courier_id,'mode_id' => $mode,'zone_id' => $zone,'customer_id' => $customer_id, 'applicable_from <=' => $current_date,'`weight_range_from` >=' => $weight,'`weight_range_to` <=' => $weight);
		$whr1 = array('c_courier_id' => $courier_id,'mode_id' => $mode,'zone_id' => $zone,'customer_id' => $customer_id, 'applicable_from <=' => $current_date);

		$fixed_perkg_result = $this->db->query("select * from tbl_domestic_rate_master where customer_id='".$customer_id."' AND zone_id='".$zone."' AND c_courier_id='".$courier_id."' AND mode_id='".$mode."' AND DATE(`applicable_from`)<='".$current_date."' AND (".$weight." BETWEEN weight_range_from AND weight_range_to) and fixed_perkg = '0' ORDER BY applicable_from DESC LIMIT 1");
		
		// $res1 = $this->basic_operation_m->get_table_result('tbl_domestic_rate_master', $whr1);
		$res1 = $fixed_perkg_result->result_array();
		// echo $this->db->last_query();
		$array['data'] = $res1;

		echo json_encode($array);

		die;
	}

	public function getMasterRates(){
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		// $mode = $request->mode;
		// $customer_id = $request->customer_id;
		// $courier_id = $request->courier_id;
		// $zone = $request->zone;
		// $booking_date = $request->booking_date;
		// $weight = $request->weight;
		// $current_date = date("Y-m-d",strtotime($booking_date));

		$sub_total 	 = 0;		
		$customer_id = $request->customer_id;
		$c_courier_id= $request->c_courier_id;
		$mode_id  = $request->mode_id;
		
		$zone_id  = $request->receiver_zone_id;
		$chargable_weight  = $request->chargable_weight;
		$receiver_gstno =$request->receiver_gstno;
		$booking_date       = $request->booking_date;
		$invoice_value       = $request->invoice_value;
		$dispatch_details       = $request->dispatch_details;
		$current_date = date("Y-m-d",strtotime($booking_date));
		$chargable_weight	= $chargable_weight * 1000;
		$fixed_perkg		= 0;
		$addtional_250		= 0;
		$addtional_500		= 0;
		$addtional_1000		= 0;
		$fixed_per_kg_1000		= 0;
			
		// calculationg fixed per kg price 	
		// $fixed_perkg_result = $this->db->query("select * from tbl_domestic_rate_master where customer_id='".$customer_id."' AND zone_id='".$zone_id."' AND c_courier_id='".$c_courier_id."' AND mode_id='".$mode_id."' AND DATE(`applicable_from`)<='".$current_date."' AND (".$request->chargable_weight." BETWEEN weight_range_from AND weight_range_to) and fixed_perkg = '0' ORDER BY applicable_from DESC LIMIT 1");
		
		$fixed_perkg_result = $this->db->query("select * from tbl_domestic_rate_master where customer_id='".$customer_id."' AND to_zone_id='".$zone_id."' AND c_courier_id='".$c_courier_id."' AND mode_id='".$mode_id."' AND DATE(`applicable_from`)<='".$current_date."' AND (".$request->chargable_weight." BETWEEN weight_range_from AND weight_range_to) and fixed_perkg = '0' ORDER BY applicable_from DESC LIMIT 1");

		
		$frieht=0;
		if ($fixed_perkg_result->num_rows() > 0) 
		{
			$data['rate_master'] = $fixed_perkg_result->row();
			$rate	= $data['rate_master']->rate;
			$fixed_perkg = $rate;
		}
		else
		{
			$fixed_perkg_result = $this->db->query("select * from tbl_domestic_rate_master where customer_id='".$customer_id."' AND to_zone_id='".$zone_id."' AND c_courier_id='".$c_courier_id."' AND mode_id='".$mode_id."' AND DATE(`applicable_from`)<='".$current_date."' AND fixed_perkg = '0' ORDER BY applicable_from DESC,weight_range_to desc LIMIT 1");

			// echo $this->db->last_query();exit();
			if ($fixed_perkg_result->num_rows() > 0) 
			{
				$data['rate_master']    = $fixed_perkg_result->row();
				$rate               	= $data['rate_master']->rate;
				$weight_range_to	    = round($data['rate_master']->weight_range_to * 1000);
				$fixed_perkg            = $rate;
			}
			
			$fixed_perkg_result = $this->db->query("select * from tbl_domestic_rate_master where customer_id='".$customer_id."' AND to_zone_id='".$zone_id."' AND c_courier_id='".$c_courier_id."' AND mode_id='".$mode_id."' AND DATE(`applicable_from`)<='".$current_date."' AND fixed_perkg <> '0' ");
			if ($fixed_perkg_result->num_rows() > 0) 
			{
			    if($weight_range_to > 1000)
			    {
			        $weight_range_to = $weight_range_to;
			    }
			    else
			    {
			        $weight_range_to = 1000;
			    }
				$left_weight  = ($chargable_weight - $weight_range_to);
			
				$rate_master  = $fixed_perkg_result->result();
				
				foreach($rate_master as $key => $values)
				{
					
					if($values->fixed_perkg == 1) // 250 gm slab
					{
						
						$slab_weight = ($values->weight_slab < $left_weight)?$values->weight_slab:$left_weight;
						$total_slab = $slab_weight/250;
						$addtional_250 = $addtional_250 + $total_slab * $values->rate;
						$left_weight = $left_weight - $slab_weight;
					}
					
					if($values->fixed_perkg == 2)// 500 gm slab
					{
						$slab_weight = ($values->weight_slab < $left_weight)?$values->weight_slab:$left_weight;
					
						if($slab_weight < 1000)
						{
						    if($slab_weight <= 500)
						    {
						        $slab_weight = 500;
						    }
						    else
						    {
						        $slab_weight = 1000;
						    }
						    
						}
						else
						{
						    $diff_ceil = $slab_weight%1000;
						    $slab_weight = $slab_weight - $diff_ceil;
						
						    if($diff_ceil <= 500 && $diff_ceil != 0)
						    {
						       
						        $slab_weight = $slab_weight + 500;
						    }
						    elseif($diff_ceil <= 1000 && $diff_ceil != 0)
						    {
						       
						        $slab_weight = $slab_weight + 1000;
						    }
						    
						  
						}
				
						$total_slab = $slab_weight/500;
						$addtional_500 = $addtional_500 +$total_slab * $values->rate;
						$left_weight = $left_weight - $slab_weight;
					
					}
					
					if($values->fixed_perkg == 3) // 1000 gm slab
					{
						$slab_weight = ($values->weight_slab < $left_weight)?$values->weight_slab:$left_weight;	
						$total_slab = ceil($slab_weight/1000);
						
						$addtional_1000 = $addtional_1000+ $total_slab * $values->rate;
						$left_weight = $left_weight - $slab_weight;
					}
					
					if($values->fixed_perkg == 4 && ($request->chargable_weight >=  $values->weight_range_from && $request->chargable_weight <=  $values->weight_range_to)) // 1000 gm slab
					{
						//$slab_weight = ($values->weight_slab < $left_weight)?$values->weight_slab:$left_weight;	
						$slab_weight = ($values->weight_slab < $left_weight)?$values->weight_slab:$left_weight;	
						$total_slab = ceil($chargable_weight/1000);
						$fixed_perkg = 0;
						$addtional_250 = 0;
						$addtional_500 = 0;
						$addtional_1000 = 0;
						$rateeee= $values->rate;
						$fixed_per_kg_1000 = $total_slab * $values->rate;
						$left_weight = $left_weight - $slab_weight;
					}
				}
				
			}
		}
		
		
		$frieht = $fixed_perkg + $addtional_250 + $addtional_500 + $addtional_1000 + $fixed_per_kg_1000;
		$amount = $frieht;
		

		//	$whr1 = array('courier_id' => $c_courier_id);
		$whr1 = array('courier_id' => $c_courier_id,'fuel_from <=' => $current_date,'fuel_to >=' => $current_date,'customer_id =' => $customer_id);
		$res1 = $this->basic_operation_m->get_table_row('courier_fuel', $whr1);
		
		if(empty($res1))
		{
			$whr1 = array('courier_id' => $c_courier_id,'fuel_from <=' => $current_date,'fuel_to >=' => $current_date,'customer_id =' => '0');
			$res1 = $this->basic_operation_m->get_query_row("select * from courier_fuel where (courier_id = '$c_courier_id' or courier_id='0') and fuel_from <= '$current_date' and fuel_to >='$current_date' and (customer_id = '0' or customer_id = '$customer_id')");
		}
		
		if($res1)
		{
			$fuel_per = $res1->fuel_price;
			$fov = '0';
			$docket_charge =$res1->docket_charge;
			
			if($dispatch_details != 'Cash')
			{
				$res1->cod	= 0;
			}
			
			if($dispatch_details != 'ToPay')
			{
				$res1->to_pay_charges	= 0;
			}
			
			if($invoice_value >= $res1->fov_base )
			{
				$fov = (($invoice_value/100)* $res1->fov_above);
			}
			elseif($invoice_value < $res1->fov_base)
			{
				$fov = (($invoice_value/100)*$res1->fov_below);
			}
			
			if($dispatch_details == 'COD')
			{
			
				$cod_detail_Range  	= $this->basic_operation_m->get_query_row("select * from courier_fuel_detail  where cf_id = '$res1->cf_id' and ('$invoice_value' BETWEEN cod_range_from and cod_range_to)");
				if(!empty($cod_detail_Range))
				{
					$res1->cod 				=($invoice_value * $cod_detail_Range->cod_range_rate/100);
				}
				
			}
			else
			{
				$res1->cod				= 0;
			}
		
			if($dispatch_details == 'ToPay')
			{
				
				$to_pay_charges_Range  	= $this->basic_operation_m->get_query_row("select * from courier_fuel_detail  where cf_id = '$res1->cf_id' and ('$invoice_value' BETWEEN topay_range_from and topay_range_to)");
				if(!empty($to_pay_charges_Range))
				{
					$res1->to_pay_charges 				=($invoice_value * $to_pay_charges_Range->topay_range_rate/100);
				}
				
			}
			else
			{
				$res1->to_pay_charges				= 0;
			}
			
			if($res1->fc_type == 'freight')
			{
				$final_fuel_charges =($amount * $fuel_per/100);
				$amount	= $amount + $fov + $docket_charge + $res1->cod + $res1->to_pay_charges;
			}
			else
			{
				$amount	= $amount + $fov + $docket_charge + $res1->cod + $res1->to_pay_charges;
				$final_fuel_charges =($amount * $fuel_per/100);
			}
			$cft 			= $res1->cft;
			$cod			= $res1->cod;
			$to_pay_charges = $res1->to_pay_charges;
			
		}
		else
		{
			$cft = '5000';
			$cod = '0';
			$fov = '0';
			$to_pay_charges ='0';
			$fuel_per ='0';
			$docket_charge ='0';
			$amount	= $amount + $fov + $docket_charge + $cod + $to_pay_charges;
			$final_fuel_charges =($amount * $fuel_per/100);
		}
		
		//Cash
		
    
		$sub_total =($amount + $final_fuel_charges);

		$first_two_char = substr($receiver_gstno,0,2);
		
		if($receiver_gstno=="")
		{
		    $first_two_char=27;
		}
		
		$tbl_customers_info 		= $this->basic_operation_m->get_query_row("select gst_charges from tbl_customers where customer_id = '$customer_id'");
		
		if($tbl_customers_info->gst_charges == 1)
		{
			if($first_two_char==27)
			{
				$cgst = ($sub_total*9/100);
				$sgst = ($sub_total*9/100);
				$igst = 0;
				$grand_total = $sub_total + $cgst + $sgst + $igst;
			}else{
				$cgst = 0;
				$sgst = 0;
				$igst = ($sub_total*18/100);
				$grand_total = $sub_total + $igst;
			}		
		}
		else
		{
			$cgst = 0;
			$sgst = 0;
			$igst = 0;
			$grand_total = $sub_total + $igst;
		}
		
		if($dispatch_details == 'Cash')
		{	
			$cgst = 0;
			$sgst = 0;
			$igst = 0;
			$grand_total = $sub_total + $igst;
		}
			
			
		$query ="select * from tbl_domestic_rate_master where customer_id='".$customer_id."' AND zone_id='".$zone_id."' AND c_courier_id='".$c_courier_id."' AND mode_id='".$mode_id."' AND DATE(`applicable_from`)<='".$current_date."' AND (".$chargable_weight." BETWEEN weight_range_from AND weight_range_to)  ORDER BY applicable_from DESC LIMIT 1";


		// round(4.96754,2)
		$data = array(
			// 'query'=>$query,
			'Zone_id'=>$zone_id,			
			'chargable_weight'=>ceil($chargable_weight),			 	
			'frieht' => round($frieht,2),
			'fov'=>round($fov,2),
			'docket_charge'=>$docket_charge,
			'amount' => round($amount,2),
			'cod' => $cod,
			'cft' => $cft,
			'to_pay_charges' => $to_pay_charges,
			'final_fuel_charges'=>round($final_fuel_charges,2),
			'sub_total'=>number_format($sub_total, 2, '.', ''),
			'cgst'=>number_format($cgst, 2, '.', ''),
			'sgst'=>number_format($sgst, 2, '.', ''),
			'igst'=>number_format($igst, 2, '.', ''),
			'grand_total'=>number_format($grand_total, 2, '.', ''),
		);
		


		///////////**********GET FLUEL CHARGES*********** ///////////
		$sub_amount = number_format($sub_total, 2, '.', '');

		$courier_id = $c_courier_id;

		$whr1 = array('courier_id' => $courier_id,'fuel_from <=' => $current_date,'fuel_to >=' => $current_date,'customer_id =' => $customer_id);
		$res1 = $this->basic_operation_m->get_table_row('courier_fuel', $whr1);
		if(empty($res1))
		{
			$whr1 = array('courier_id' => $courier_id,'fuel_from <=' => $current_date,'fuel_to >=' => $current_date,'customer_id =' => '0');
			$res1 = $this->basic_operation_m->get_query_row("select * from courier_fuel where (courier_id = '$courier_id' or courier_id='0') and fuel_from <= '$current_date' and fuel_to >='$current_date' and (customer_id = '0' or customer_id = '$customer_id')");
		}
		
		//$whr1 = array('courier_id' => $courier_id,'fuel_from <=' => $current_date,'fuel_to >=' => $current_date);
		//$res1 = $this->basic_operation_m->get_table_row('courier_fuel', $whr1);
		if($res1){$fuel_per = $res1->fuel_price; }else{$fuel_per ='0';}

		$final_fuel_charges =($sub_amount * $fuel_per/100);
			
		$sub_total =($sub_amount + $final_fuel_charges);
		

        $whr2 = array('from <=' => $current_date,'to >=' => $current_date);
        $gst_details = $this->basic_operation_m->get_table_row('tbl_gst_setting', $whr2);

            //echo $this->db->last_query();

		if($gst_details){
			$cgst_per = $gst_details->cgst; 
			$sgst_per = $gst_details->sgst; 
			$igst_per = $gst_details->igst; 
		}else{
			$cgst_per = '0'; 
			$sgst_per = '0'; 
			$igst_per = '0'; 
		}    
		
	  
	   
	   $tbl_customers_info 		= $this->basic_operation_m->get_query_row("select gst_charges from tbl_customers where customer_id = '$customer_id'");
			
		if($tbl_customers_info->gst_charges == 1)
		{
			$cgst = ($sub_total*$cgst_per/100);
			$sgst = ($sub_total*$sgst_per/100);
			$igst = 0;	
		}
		else
		{
			$cgst = 0;
			$sgst = 0;
			$igst = 0;
		}
		
		if($dispatch_details == 'Cash')
		{	
			$cgst = 0;
			$sgst = 0;
			$igst = 0;
		}
	   
	   
	   $grand_total = $sub_total + $cgst + $sgst + $igst;
		

		$result2= array('final_fuel_charges'=>round($final_fuel_charges,2),
			'sub_total'=>number_format($sub_total, 2, '.', ''),
            'cgst'=>number_format($cgst, 2, '.', ''),
            'sgst'=>number_format($sgst, 2, '.', ''),
			'igst'=>number_format($igst, 2, '.', ''),
			'grand_total'=>number_format($grand_total, 2, '.', ''),
		);
		$data['fluel_charges'] = $result2;
		echo json_encode($data);
		exit;
	}

	public function getSliders(){
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$sliders = $this->db->get('tbl_homeslider')->result_array();
		$slidersArr = [];

		foreach($sliders as $slider){
			$url = base_url();

			$url = str_replace('https', 'http', $url);

			// echo $url;exit();
			$slider['slider_image'] = $url.'assets/homeslider/'.$slider['slider_image'];
			// $slider['slider_image'] = base_url('assets/homeslider/'.$slider['slider_image']);
			$slidersArr[] = $slider;
		}
		
		$array['data'] = $slidersArr;

		echo json_encode($array);

		die;
	}

	public function getNews(){
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$allnews=$this->basic_operation_m->get_all_result('tbl_news','');
		
		
		$array['data'] = $allnews;

		echo json_encode($array);

		die;
	}

	public function shipment_tracking(){
		$postdata = file_get_contents("php://input");

		// echo $postdata;
		//exit();
		$request = json_decode($postdata);
		// print_r($request);exit();
		$pod_no = $request->airway_number;
		
		$check_pod_international=$this->db->query("select pod_no from tbl_international_booking where pod_no = '$pod_no'");
		$check_result=$check_pod_international->row();
	
		if(isset($check_result)){ 
			$reAct=$this->db->query("select tbl_international_booking.*,tbl_international_weight_details.no_of_pack, sendercity.city AS sender_city_name, recievercity.country_name as reciever_country_name from tbl_international_booking left join tbl_international_weight_details on tbl_international_booking.booking_id=tbl_international_weight_details.booking_id INNER JOIN city sendercity ON sendercity.id = tbl_international_booking.sender_city INNER JOIN zone_master recievercity ON recievercity.z_id = tbl_international_booking.reciever_country_id where pod_no = '$pod_no'");
			$data['info']=$reAct->row();

			if (empty($data['info'])) {
				$data['result']="fail";	
				$data['message']="Airway number not found";	
				echo json_encode($data);

				die;
			}
			
			$courier_company_id = $data['info']->courier_company_id;
			
			$tracking_href_details=$this->db->query("select * from courier_company where c_id=".$courier_company_id);
			$data['forwording_track']	=	$tracking_href_details->row();

			$tracking_href_details2=$this->db->query("select * from tbl_upload_pod where pod_no = '$pod_no'");
			$data['pod_upload']	=	$tracking_href_details2->row();

			if (!empty($data['pod_upload'])) {
				$data['pod_upload'] = base_url('/assets/pod/').$data['pod_upload']->image;
			}else{
				$data['pod_upload'] = "";
			}
		
			
			$reAct=$this->db->query("select * from tbl_international_tracking where pod_no = '$pod_no' ORDER BY id DESC");
			$data['pod']	=	$reAct->result();  
			$data['del_status']	=	$reAct->row();
		}else{
			$reAct=$this->db->query("select tbl_domestic_booking.*,tbl_domestic_weight_details.no_of_pack, sendercity.city AS sender_city_name, recievercity.city as reciever_country_name from tbl_domestic_booking left join tbl_domestic_weight_details on tbl_domestic_booking.booking_id=tbl_domestic_weight_details.booking_id INNER JOIN city sendercity ON sendercity.id = tbl_domestic_booking.sender_city INNER JOIN city recievercity ON recievercity.id = tbl_domestic_booking.reciever_city where pod_no = '$pod_no'");
			$data['info'] = $reAct->row();
			if (empty($data['info'])) {
				$data['result']="fail";	
				$data['message']="Airway number not found";	
				echo json_encode($data);

				die;
			}
			$courier_company_id = $data['info']->courier_company_id;
			$tracking_href_details=$this->db->query("select * from courier_company where c_id=".$courier_company_id);
			$data['forwording_track']	=	$tracking_href_details->row();
			
			
			$tracking_href_details2=$this->db->query("select * from tbl_upload_pod where pod_no = '$pod_no'");
			$data['pod_upload']	=	$tracking_href_details2->row();

			if (!empty($data['pod_upload'])) {
				$data['pod_upload'] = base_url('/assets/pod/').$data['pod_upload']->image;
			}else{
				$data['pod_upload'] = "";
			}

			$reAct=$this->db->query("select * from tbl_domestic_tracking where pod_no = '$pod_no' ORDER BY id DESC");
			$data['pod']	=	$reAct->result();
			$data['del_status']	=	$reAct->row();
		}

		if(!empty($data['pod']))
		{
			foreach($data['pod'] as $k => $values)
			{
				if($values->status == 'DELIVERED' || $values->status == 'Delivered')
				{
					$data['delivery_date'] = $values->tracking_date;
				}
			}
		}

		if ($data['pod']) {
			$data['result']="success";	
		}
		else{
			$data['result']="fail";	
			$data['message']="Airway number not found";	
		}
		echo json_encode($data);

		die;
	}

	public function tracking(){
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$pod_no = $request->airway_number;
		$customer_id = $request->customer_id;
		$check_pod_international=$this->db->query("select pod_no from tbl_international_booking where pod_no = '$pod_no'");
		$check_result=$check_pod_international->row();
	
		if(isset($check_result)){ 
			$reAct=$this->db->query("select tbl_international_booking.*,tbl_international_weight_details.no_of_pack, sendercity.city AS sender_city_name, recievercity.country_name as reciever_country_name from tbl_international_booking left join tbl_international_weight_details on tbl_international_booking.booking_id=tbl_international_weight_details.booking_id INNER JOIN city sendercity ON sendercity.id = tbl_international_booking.sender_city INNER JOIN zone_master recievercity ON recievercity.z_id = tbl_international_booking.reciever_country_id where pod_no = '$pod_no'");
			$data['info']=$reAct->row();
			
			$courier_company_id = $data['info']->courier_company_id;
			
			$tracking_href_details=$this->db->query("select * from courier_company where c_id=".$courier_company_id);
			$data['forwording_track']	=	$tracking_href_details->row();
		
			
			$reAct=$this->db->query("select *,remarks as status,status as branch_name  from tbl_international_tracking where pod_no ='$pod_no' ORDER BY id asc");
			$data['pod']	=	$reAct->result();  
			$data['del_status']	=	$reAct->row();
		}else{
			$reAct=$this->db->query("select tbl_domestic_booking.*,tbl_domestic_weight_details.no_of_pack, sendercity.city AS sender_city_name, recievercity.city as reciever_country_name from tbl_domestic_booking left join tbl_domestic_weight_details on tbl_domestic_booking.booking_id=tbl_domestic_weight_details.booking_id INNER JOIN city sendercity ON sendercity.id = tbl_domestic_booking.sender_city INNER JOIN city recievercity ON recievercity.id = tbl_domestic_booking.reciever_city where pod_no = '$pod_no'");
			$data['info']=$reAct->row();
			
			$courier_company_id = $data['info']->courier_company_id;
			$tracking_href_details=$this->db->query("select * from courier_company where c_id=".$courier_company_id);
			$data['forwording_track']	=	$tracking_href_details->row();
			
			$reAct=$this->db->query("select * from tbl_domestic_tracking where pod_no = '$pod_no' ORDER BY id DESC");
			$data['pod']	=	$reAct->result();
			$data['del_status']	=	$reAct->row();
		}

		if(!empty($data['pod']))
		{
			foreach($data['pod'] as $k => $values)
			{
				if($values->status == 'DELIVERED' || $values->status == 'Delivered')
				{
					$data['delivery_date'] = $values->tracking_date;
				}
			}
		}

		$dataa = json_decode(json_encode($data), true);

		$modeDispach = 'Air';
		if($dataa['info']['mode_dispatch'] == '2'){
			$modeDispach = 'Train';
		}
		if($dataa['info']['mode_dispatch'] == '2'){
			$modeDispach = 'Surface';
		}

		// print_r($dataa);
		$trankingdata = [];
		foreach($dataa['pod'] as $trackinfo){
			$status = $trackinfo['status'];
			if($trackinfo['comment']){
				$status = $status.'-'.$trackinfo['comment'];
			}
			$trankingdata[] = array(
				'date'=>$trackinfo['tracking_date'],
				'status'=>$status,
				'location'=>$trackinfo['branch_name']
			);
		}

		$resultarr['tracking_data'] = array(
			'pod_no'=>$dataa['info']['pod_no'],
			'booking_date'=>$dataa['info']['booking_date'],
			'origin'=>$dataa['info']['sender_city_name'],
			'destination'=>$dataa['info']['reciever_country_name'],
			'mode'=>$modeDispach,
			'nop'=>$dataa['info']['no_of_pack'],
			'tracking_info'=>$trankingdata,
		);

		if ($resultarr) {
			$resultarr['result']="success";	
		}
		else{
			$resultarr['result']="fail";	
			$resultarr['message']="Airway number not found";	
		}
		echo json_encode($resultarr);

		die;
	}

	public function change_delivery_status(){
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$tracking_date = date('Y-m-d H:i:s',strtotime($request->tracking_date));
		$selected_docket = $request->selected_docket;
		$company_type = $request->company_type;
		$status = $request->status;
		$comment = $request->comment;
		$remarks = $request->remarks;
		$user_id = $request->user_id;
		$cust_sign = $request->image;

		$pod_no = $selected_docket;

		
		//  $pod_no=$this->input->post('pod_no');
		// 	$status=$this->input->post('status');
		// 	$comment = $this->input->post('comment');

		if($selected_docket){
			$is_delhivery_complete = 0;


			$whr = array('user_id'=>$user_id);
			$res=$this->basic_operation_m->getAll('tbl_users',$whr);
			$branch_id= $res->row()->branch_id;
			$date=date('y-m-d');

			$whr = array('branch_id'=>$branch_id);
			$res=$this->basic_operation_m->getAll('tbl_branch',$whr);
			$branch_name= $res->row()->branch_name;
			
			$date=date('Y-m-d H:i:s');



			if (!empty($cust_sign)) {
				
				if (base64_decode($cust_sign)) {
					// echo "valid";
				    $cust_sign = $this->save_base64_image($cust_sign, time()."_".$selected_docket."_sign", "assets/pod_sign/" );
				} else {
					$data['result']="faild";
					$data['message']="Not a valid signature file";
				    echo json_encode($data);exit();
				}
				
				
			}

			// exit();

			if($company_type=="Domestic")
			{
				if($status == 'Delivered')
				{
					$is_delhivery_complete = 1;
					$where = array('pod_no' => $selected_docket);
					$updateData = [
						'is_delhivery_complete' => $is_delhivery_complete,
					];
					$this->db->update('tbl_domestic_booking', $updateData, $where);
				}
				
				$this->db->select('pod_no, booking_id, forworder_name, forwording_no');
				$this->db->from('tbl_domestic_booking');
				$this->db->where('pod_no', $selected_docket);
				$this->db->order_by('booking_id', 'DESC');
				$result = $this->db->get();
				$resultData = $result->row();
	
				$pod_no = $resultData->pod_no;
				$forworder_name = $resultData->forworder_name;
				$forwording_no = $resultData->forwording_no;
				$booking_id = $resultData->booking_id;
			
				$data = [
					'pod_no'=>$pod_no,
					'branch_name'=>$branch_name,
					'booking_id'=>$booking_id,
					'forworder_name'=>$forworder_name,
					'forwording_no'=>$forwording_no,
					'tracking_date' => $tracking_date,
					'status' => $status,
					'comment' => $comment,
					'remarks' => $remarks,
					'is_delhivery_complete' => $is_delhivery_complete,
					'cust_sign' => $cust_sign,
				];
				// echo "<pre>";
				// print_r($data);
				// exit;
				$this->db->insert('tbl_domestic_tracking', $data);
			}else if($company_type=="International")
			{
				if($status == 'Delivered')
				{
					$is_delhivery_complete = 1;
					$where = array('pod_no' => $selected_docket);
					$updateData = [
						'is_delhivery_complete' => $is_delhivery_complete,
					];
					$this->db->update('tbl_international_booking', $updateData, $where);
				}
			
				$this->db->select('pod_no, booking_id, forworder_name, forwording_no');
				$this->db->from('tbl_international_booking');
				$this->db->where('pod_no', $selected_docket);
				$this->db->order_by('booking_id', 'DESC');
				$result = $this->db->get();
				$resultData = $result->row();
	
				$pod_no = $resultData->pod_no;
				$forworder_name = $resultData->forworder_name;
				$forwording_no = $resultData->forwording_no;
				$booking_id = $resultData->booking_id;
				
				$data = [
					'pod_no'=>$pod_no,
					'branch_name'=>$branch_name,
					'booking_id'=>$booking_id,
					'forworder_name'=>$forworder_name,
					'forwording_no'=>$forwording_no,
					'tracking_date' => $tracking_date,
					'status' => $status,
					'comment' => $comment,
					'remarks' => $remarks,
					'is_delhivery_complete' => $is_delhivery_complete,
					'cust_sign' => $cust_sign,
				];
				
				$this->db->insert('tbl_international_tracking', $data);
			}
			$data['result']="success";
			$data['message']="Delivery Status Changed successfully";
		}
		echo json_encode($data);

		die;
	}

	public function upload_pod(){
		$all_data 		= $this->input->post();
		if (!empty($all_data)) 
		{
			$user_id=$all_data['user_id'];
			$whr = array('user_id'=>$user_id);
			$res=$this->basic_operation_m->getAll('tbl_users',$whr);
			$branch_id= $res->row()->branch_id;
			$username= $res->row()->username;
			$date=date('y-m-d');
			
			$whr = array('branch_id'=>$branch_id);
			$res=$this->basic_operation_m->getAll('tbl_branch',$whr);
			$branch_name= $res->row()->branch_name;
			
			$date=date('y-m-d');
			$r= array('id'=>'',
				'deliveryboy_id'=>$username,
				'pod_no'=>$this->input->post('pod_no'),
				'image'=>'',
				'delivery_date'=>$date
			);
			
			$result=$this->basic_operation_m->insert('tbl_upload_pod',$r);
			$lastid=$this->db->insert_id();
				
			$config['upload_path'] = "assets/pod/";
			$config['allowed_types'] = 'gif|jpg|png';$config['file_name'] = 'pod_'.$lastid.'.jpg';
				
			$this->load->library('upload',$config);
			$this->upload->initialize($config);
			$this->upload->set_allowed_types('*');

			$data['upload_data'] = '';
			$url_path="";
			if (!$this->upload->do_upload('image'))
			{ 
				$data = array('msg' => $this->upload->display_errors());
			}
			else 
			{ 
				$image_path = $this->upload->data();
			}
				
			$data =array('image'=>$image_path['file_name']);
			$whr=array('id'=>$lastid);
			$this->basic_operation_m->update('tbl_upload_pod',$data,$whr);

			if ($this->db->affected_rows()>0) {
				$data['message']="Image Added Sucessfully";
			}else{
				$data['message']="Error in Query";
			}
				
			$data['result']="success";
			$data['message']="Delivery Status Changed successfully";
		}
		echo json_encode($data);
	}

	public function all_status(){
		// $array = [
		// 	['id'=>'Booked','name'=>'Booked'],
		// 	['id'=>'Delivered','name'=>'Delivered'],
		// 	['COD'=>'Out for delivery','name'=>'Out for delivery'],
		// 	['ToPay'=>'RTO','name'=>'RTO'],
		// 	['ToPay'=>'Intransit','name'=>'Intransit'],
		// ];

		$array = [];
		// $this->db->where_not_in('id',array(1,2,3,6,7,8,9,10,11,12));
		$this->db->where_not_in('id',array(1));
		$status = $this->db->get('tbl_status')->result_array();
		foreach($status as $sttus){
			$array[] = ['id'=>$sttus['status'],'name'=>$sttus['status']];
		}
		
		$data['all_status']= $array;
		echo json_encode($data);	 
	}


	public function all_coloader(){
		// $array = [
		// 	['id'=>'Booked','name'=>'Booked'],
		// 	['id'=>'Delivered','name'=>'Delivered'],
		// 	['COD'=>'Out for delivery','name'=>'Out for delivery'],
		// 	['ToPay'=>'RTO','name'=>'RTO'],
		// 	['ToPay'=>'Intransit','name'=>'Intransit'],
		// ];

		$array = [];
		$status = $this->db->get('tbl_coloader')->result_array();
		
		$data['all_coloader']= $status;
		echo json_encode($data);	 
	}

	public function all_vender(){
		// $array = [
		// 	['id'=>'Booked','name'=>'Booked'],
		// 	['id'=>'Delivered','name'=>'Delivered'],
		// 	['COD'=>'Out for delivery','name'=>'Out for delivery'],
		// 	['ToPay'=>'RTO','name'=>'RTO'],
		// 	['ToPay'=>'Intransit','name'=>'Intransit'],
		// ];



		$array = [];
		$status = $this->db->get('tbl_vendor')->result_array();
		
		$data['all_vendor']= $status;
		echo json_encode($data);	 
	}

	public function getShipmentStatus(){
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$pod_no = $request->awnno;
		$user_id = $request->user_id;

		
		$whr = array('user_id'=>$user_id);
		$res=$this->basic_operation_m->getAll('tbl_users',$whr);
		$branch_id= $res->row()->branch_id;
		$username= $res->row()->username;
		$date=date('y-m-d');
		
		$whr = array('branch_id'=>$branch_id);
		$res=$this->basic_operation_m->getAll('tbl_branch',$whr);
		$branch_name= $res->row()->branch_name;
	
		$check_pod_international=$this->db->query("select pod_no from tbl_international_booking where pod_no = '$pod_no'");
		$check_result=$check_pod_international->row();
	
		if(isset($check_result)){ 
			$reAct=$this->db->query("select tbl_international_booking.*,tbl_international_weight_details.no_of_pack,tbl_international_weight_details.actual_weight, sendercity.city AS sender_city_name, recievercity.country_name as reciever_country_name from tbl_international_booking left join tbl_international_weight_details on tbl_international_booking.booking_id=tbl_international_weight_details.booking_id INNER JOIN city sendercity ON sendercity.id = tbl_international_booking.sender_city INNER JOIN zone_master recievercity ON recievercity.z_id = tbl_international_booking.reciever_country_id where pod_no = '$pod_no'");
			$data['info']=$reAct->row();
			
			$courier_company_id = $data['info']->courier_company_id;
			
			$tracking_href_details=$this->db->query("select * from courier_company where c_id=".$courier_company_id);
			$data['forwording_track']	=	$tracking_href_details->row();
	
			
			$reAct=$this->db->query("select * from tbl_international_tracking where status='Out For Delivery' AND pod_no = '$pod_no' ORDER BY id DESC");
			$data['pod']	=	$reAct->row();  
			$data['del_status']	=	$reAct->row();
		}else{
			$reAct=$this->db->query("select tbl_domestic_booking.*,tbl_domestic_weight_details.no_of_pack,tbl_domestic_weight_details.actual_weight, sendercity.city AS sender_city_name, recievercity.city as reciever_country_name from tbl_domestic_booking left join tbl_domestic_weight_details on tbl_domestic_booking.booking_id=tbl_domestic_weight_details.booking_id INNER JOIN city sendercity ON sendercity.id = tbl_domestic_booking.sender_city INNER JOIN city recievercity ON recievercity.id = tbl_domestic_booking.reciever_city where  pod_no = '$pod_no' ");
			$data['info']=$reAct->row();
			
			if (!empty($data['info'])) {
				$courier_company_id = $data['info']->courier_company_id;
				$tracking_href_details=$this->db->query("select * from courier_company where c_id=".$courier_company_id);
				$data['forwording_track']	=	$tracking_href_details->row();
			}else{
				$data['forwording_track']	= array();
			}
			

			$resAct5=$this->db->query("select * from tbl_domestic_tracking where  pod_no = '$pod_no' ORDER BY id DESC");
	 		$dd = $resAct5->row();
	 		$data['pod'] = array();

	 		// print_r($dd);
	 		if (!empty($dd) && $dd->status=='Out For Delivery') {
	 			// echo $this->db->last_query();exit();
	 			$data['info'] = array();
 	 		}else{
	 			// $reAct=$this->db->query("select * from tbl_domestic_menifiest where destination_branch='$branch_name' AND pod_no = '$pod_no' ORDER BY id DESC");
	 			$reAct=$this->db->query("select * from tbl_domestic_menifiest where pod_no = '$pod_no' ORDER BY id DESC");

	 			$dd1 = $reAct->row();
	 			// echo $this->db->last_query();exit();
	 			// echo "<pre>";
	 			// print_r($dd);
	 			// print_r($dd1->reciving_status);exit();

	 			if (!empty($dd) && !empty($dd1)) {
	 				if ($dd1->reciving_status==1) {
	 					$data['pod'] = $resAct5->result();
	 				}else{
	 					$data['pod'] = array();
	 					$data['info'] = array();
	 				}
	 			}else{
	 				$data['pod'] = $resAct5->result();
	 			}
	 			
	 			
	 		}
			
			$data['del_status']	=	$resAct5->row();
		}

		// echo $this->db->last_query();exit();
	
		if(!empty($data['pod']))
		{
			foreach($data['pod'] as $k => $values)
			{
				// print_r($values);
				if($values->status == 'DELIVERED' || $values->status == 'Delivered')
				{
					$data['delivery_date'] = $values->tracking_date;
				}
			}
		}
	
		$dataa = json_decode(json_encode($data), true);
	
		$modeDispach = 'Air';
		if(@$dataa['info']['mode_dispatch'] == '2'){
			$modeDispach = 'Train';
		}
		if(@$dataa['info']['mode_dispatch'] == '2'){
			$modeDispach = 'Surface';
		}
	
		// print_r($dataa);
		$trankingdata = [];
		foreach($dataa['pod'] as $trackinfo){
			$status = $trackinfo['status'];
			if($trackinfo['comment']){
				$status = $status.'-'.$trackinfo['comment'];
			}
			$trankingdata[] = array(
				'date'=>$trackinfo['tracking_date'],
				'status'=>$status,
				'location'=>$trackinfo['branch_name']
			);
		}

		// echo "<pre>";
		// print_r($dataa['pod']);
		// no_of_pack
		// actual_weight
		
	
		$resultarr['tracking_data'] = array(
			'del_status'=>@$data['del_status'],
			'pod_no'=>@$dataa['info']['pod_no'],
			'booking_date'=>@$dataa['info']['booking_date'],
			'origin'=>@$dataa['info']['sender_city_name'],
			'destination'=>@$dataa['info']['reciever_country_name'],
			'mode'=>@$modeDispach,
			'nop'=>@$dataa['info']['no_of_pack'],
			'sender_name'=>@$dataa['info']['sender_name'],
			'sender_address'=>@$dataa['info']['sender_address'],
			'reciever_name'=>@$dataa['info']['reciever_name'],
			'reciever_address'=>@$dataa['info']['reciever_address'],
			'actual_weight'=>@$dataa['info']['actual_weight'],
			'status'=>@$dataa['del_status']['status'],
		);
	
		if (@$dataa['info']['pod_no']) {
			$resultarr['result']="success";	
		}
		else{
			$resultarr['result']="fail";	
			$resultarr['message']="Airway number not found";	
		}
		echo json_encode($resultarr);
	
		die;
	}


	public function check_shipment_for_update(){
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$pod_no = $request->awnno;
		$user_id = $request->user_id;

		
		$whr = array('user_id'=>$user_id);
		$res=$this->basic_operation_m->getAll('tbl_users',$whr);
		$branch_id= $res->row()->branch_id;
		$username= $res->row()->username;
		$date=date('y-m-d');
		
		$whr = array('branch_id'=>$branch_id);
		$res=$this->basic_operation_m->getAll('tbl_branch',$whr);
		$branch_name= $res->row()->branch_name;
	
		$check_pod_international=$this->db->query("select pod_no from tbl_international_booking where pod_no = '$pod_no'");
		$check_result=$check_pod_international->row();
	
		if(isset($check_result)){ 
			$reAct=$this->db->query("select tbl_international_booking.*,tbl_international_weight_details.no_of_pack,tbl_international_weight_details.actual_weight, sendercity.city AS sender_city_name, recievercity.country_name as reciever_country_name from tbl_international_booking left join tbl_international_weight_details on tbl_international_booking.booking_id=tbl_international_weight_details.booking_id INNER JOIN city sendercity ON sendercity.id = tbl_international_booking.sender_city INNER JOIN zone_master recievercity ON recievercity.z_id = tbl_international_booking.reciever_country_id where pod_no = '$pod_no' AND is_delhivery_complete=0");
			$data['info']=$reAct->row();
			
			$courier_company_id = $data['info']->courier_company_id;
			
			$tracking_href_details=$this->db->query("select * from courier_company where c_id=".$courier_company_id);
			$data['forwording_track']	=	$tracking_href_details->row();
	
			
			$reAct=$this->db->query("select * from tbl_international_tracking where status='Out For Delivery' AND pod_no = '$pod_no' AND branch_name='$branch_name' ORDER BY id DESC");
			$data['pod']	=	$reAct->row();  
			$data['del_status']	=	$reAct->row();
		}else{
			$reAct=$this->db->query("select tbl_domestic_booking.*,tbl_domestic_weight_details.no_of_pack,tbl_domestic_weight_details.actual_weight, sendercity.city AS sender_city_name, recievercity.city as reciever_country_name from tbl_domestic_booking left join tbl_domestic_weight_details on tbl_domestic_booking.booking_id=tbl_domestic_weight_details.booking_id INNER JOIN city sendercity ON sendercity.id = tbl_domestic_booking.sender_city INNER JOIN city recievercity ON recievercity.id = tbl_domestic_booking.reciever_city where  pod_no = '$pod_no' AND is_delhivery_complete=0");
			$data['info']=$reAct->row();
			
			if (!empty($data['info'])) {
				$courier_company_id = $data['info']->courier_company_id;
				$tracking_href_details=$this->db->query("select * from courier_company where c_id=".$courier_company_id);
				$data['forwording_track']	=	$tracking_href_details->row();
			}else{
				$data['forwording_track']	= array();
			}
			


			// $reAct=$this->db->query("select * from tbl_domestic_tracking where status='Delivered' AND pod_no = '$pod_no' ORDER BY id DESC limit 1");
			$reAct=$this->db->query("select * from tbl_domestic_tracking where status='Out for Delivery' AND pod_no = '$pod_no' AND branch_name='$branch_name' ORDER BY id DESC limit 1");
			$data['pod']	=	$reAct->result();

			if (!$data['pod']) {
				// $reAct=$this->db->query("select * from tbl_domestic_tracking where pod_no = '$pod_no' ORDER BY id DESC limit 1");
				// $data['pod']	=	$reAct->result();
				// $data['pod'] = array();
			}else{
				$data['pod'] = array();
			}
			
			
			$data['del_status']	=	$reAct->row();
		}

		// echo $this->db->last_query();exit();
	
		if(!empty($data['pod']))
		{
			foreach($data['pod'] as $k => $values)
			{
				// print_r($values);
				if($values->status == 'DELIVERED' || $values->status == 'Delivered')
				{
					$data['delivery_date'] = $values->tracking_date;
				}
			}
		}
	
		$dataa = json_decode(json_encode($data), true);
	
		$modeDispach = 'Air';
		if($dataa['info']['mode_dispatch'] == '2'){
			$modeDispach = 'Train';
		}
		if($dataa['info']['mode_dispatch'] == '2'){
			$modeDispach = 'Surface';
		}
	
		// print_r($dataa);
		$trankingdata = [];
		foreach($dataa['pod'] as $trackinfo){
			$status = $trackinfo['status'];
			if($trackinfo['comment']){
				$status = $status.'-'.$trackinfo['comment'];
			}
			$trankingdata[] = array(
				'date'=>$trackinfo['tracking_date'],
				'status'=>$status,
				'location'=>$trackinfo['branch_name']
			);
		}

		// echo "<pre>";
		// print_r($dataa['pod']);
		// no_of_pack
		// actual_weight
		
	
		$resultarr['tracking_data'] = array(
			'del_status'=>$data['del_status'],
			'pod_no'=>$dataa['info']['pod_no'],
			'booking_date'=>$dataa['info']['booking_date'],
			'origin'=>$dataa['info']['sender_city_name'],
			'destination'=>$dataa['info']['reciever_country_name'],
			'mode'=>$modeDispach,
			'nop'=>$dataa['info']['no_of_pack'],
			'sender_name'=>$dataa['info']['sender_name'],
			'sender_address'=>$dataa['info']['sender_address'],
			'reciever_name'=>$dataa['info']['reciever_name'],
			'reciever_address'=>$dataa['info']['reciever_address'],
			'actual_weight'=>$dataa['info']['actual_weight'],
			// 'status'=>@$dataa['pod'][0]['status'],
			'status'=>@$data['del_status']->status,
		);
	
		if ($dataa['info']['pod_no']) {
			$resultarr['result']="success";	
		}
		else{
			$resultarr['result']="fail";	
			$resultarr['message']="Airway number not found";	
		}
		echo json_encode($resultarr);
	
		die;
	}
	public function branchlocator()
	{
		$data= array();
		
		$reAct1=$this->db->query("select * from tbl_branch,state,city where tbl_branch.city=city.id and tbl_branch.state=state.id");
		
		if($reAct1->num_rows()>0)
		{
			$data['result']="success";	

			$data['branch']=$reAct1->result();
		}else{
			$data['result']="fail";
			$data['message']="No Branch Available";
		}
		echo json_encode($data);
	}




	

		


	///////////////////////////OLD APIS//////////////////////////////////

	public function index()
	{  
		if ($this->session->userdata("userName")!="") {

			$data= array();
			$whrAct=array('iseleted'=>0);
			$resAct		= $this->basic_operation_m->getAll('users','');

			if($resAct->num_rows()>0)
			{
				$data['alleventsdata']=$resAct->result_array();	            
			}
			$this->load->view('allusers',$data);
		}
		else{
			redirect(base_url().'login');
		}	

	}


	public function counterStatus() {
		$date = date('Y-m-d');
		$data['cnt_delivery'] = 0;
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		
		$username = !empty($request->user_name) ? $request->user_name : $this->input->post('user_name');
		$whr = array('username' => $username);
		$res = $this->basic_operation_m->getAll('tbl_users', $whr);
		$branch_id = $res->row()->branch_id;
			$data = array();
			$whrAct = array('isactive' => 1, 'isdeleted' => 0);

			$TodayresAct = $this->db->query("select count(*) as totalshipment from tbl_domestic_booking where tbl_domestic_booking.branch_id='$branch_id' and date_format(booking_date, '%Y-%m-%d') = '" . date('Y-m-d') . "'");
				$data['todayshipment'] = ($TodayresAct->num_rows() > 0) ? $TodayresAct->row()->totalshipment : 0;
			$resAct = $this->db->query("select count(*) as totalshipment from tbl_domestic_booking where tbl_domestic_booking.branch_id='$branch_id'");
			$data['totalshipment'] = ($resAct->num_rows() > 0) ? $resAct->row()->totalshipment : 0;
			$PendingresAct = $this->db->query("select count(*) as totalshipment from tbl_domestic_booking where tbl_domestic_booking.branch_id='$branch_id' and status = 0");
	
				$data['pendingshipment'] = ($PendingresAct->num_rows() > 0) ? $PendingresAct->row()->totalshipment : 0;
			
				echo json_encode($data);	 
		exit;
	}

	public function counterStatusByCustomer() {
		$date = date('Y-m-d');
		$data['cnt_delivery'] = 0;
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		
		$customer_id = !empty($request->customer_id) ? $request->customer_id : $this->input->post('customer_id');
			$data = array();
	
			$TodayresAct = $this->db->query("select count(*) as totalshipment from tbl_domestic_booking where tbl_domestic_booking.customer_id='$customer_id' and date_format(booking_date, '%Y-%m-%d') = '" . date('Y-m-d') . "'");
				$data['todayshipment'] = ($TodayresAct->num_rows() > 0) ? $TodayresAct->row()->totalshipment : 0;
			$resAct = $this->db->query("select count(*) as totalshipment from tbl_domestic_booking where tbl_domestic_booking.customer_id='".$customer_id."' ");

			$check_result=$resAct->row();

			// print_r($check_result);
			$data['totalshipment'] = ($check_result) ? $check_result->totalshipment : 0;
			$PendingresAct = $this->db->query("select count(*) as totalshipment from tbl_domestic_booking where tbl_domestic_booking.customer_id='$customer_id' and status = 0");
				$data['pendingshipment'] = ($PendingresAct->num_rows() > 0) ? $PendingresAct->row()->totalshipment : 0;
				echo json_encode($data);	 
		exit;
	}

	public function signup()
	{

		$data = array();	
		
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		if(!empty($request->uname) && !empty($request->email) && !empty($request->password))	
		{	

			$query = $this->db->query("insert into users values('','$request->uname','$request->email','','$request->password','$request->sname','','','$request->gyear','0','1','')");

			if($this->db->affected_rows()>0)
			{
				$insertId = $this->db->insert_id();
				$query = $this->db->query("insert into user_notification_setting values('','$insertId','1 Day','1','0' )");
				$data['result']="success";			 	

			}else{
				$data['result']="fail";	            
			}
		}else
		{
			$data['result'] ="fail" ;
		}
		echo json_encode($data);

	}

		/*public function addBooking() {
            $postdata = file_get_contents("php://input");
    	    $request = json_decode($postdata);
    	    
            $username = !empty($request->user_name) ? $request->user_name : $this->input->post('user_name');
            $whr = array('username' => $username);
            $res = $this->basic_operation_m->getAll('tbl_users', $whr);
            $branch_id = $res->row()->branch_id;
           
          $d = !empty($request->booking_date) ? $request->booking_date : $this->input->post('booking_date');
            $date = date('Y-m-d H:i:s',strtotime($d));
            //booking details//
            $data = array(
                'booking_id' => "",
                'sender_name' => !empty($request->sender_name) ? $request->sender_name : $this->input->post('sender_name'),
                'sender_address' => !empty($request->sender_address) ? $request->sender_address : $this->input->post('sender_address'),
                'sender_city' => !empty($request->sender_city) ? $request->sender_city : $this->input->post('sender_city'),
                'sender_pincode' => !empty($request->sender_pincode) ? $request->sender_pincode : $this->input->post('sender_pincode'),
                'sender_contactno' => !empty($request->sender_contactno) ? $request->sender_contactno : $this->input->post('sender_contactno'),
                'sender_gstno' => !empty($request->sender_contactno) ? $request->sender_contactno : $this->input->post('sender_gstno'),
                'reciever_name' => !empty($request->reciever_name) ? $request->reciever_name : $this->input->post('reciever_name'),
                'reciever_address' => !empty($request->reciever_address) ? $request->reciever_address : $this->input->post('reciever_address'),
                'reciever_city' => !empty($request->reciever_city) ? $request->reciever_city : $this->input->post('reciever_city'),
                'reciever_pincode' =>!empty($request->reciever_pincode) ? $request->reciever_pincode :  $this->input->post('reciever_pincode'),
                'reciever_contact' =>!empty($request->reciever_contact) ? $request->reciever_contact :  $this->input->post('reciever_contact'),
                'receiver_gstno' => !empty($request->receiver_gstno) ? $request->receiver_gstno : $this->input->post('receiver_gstno'),
                'booking_date' =>$date,
                'delivery_date' => !empty($request->delivery_date) ? $request->delivery_date : $this->input->post('delivery_date'),
                'mode_dispatch' => !empty($request-mode_dispatch) ? $request->mode_dispatch : $this->input->post('mode_dispatch'),
                'dispatch_details' => !empty($request->dispatch_details) ? $request->dispatch_details : $this->input->post('dispatch_details'),
                'insurace_policyno' => !empty($request->insurace_policyno) ? $request->insurace_policyno : $this->input->post('insurace_policyno'),
                'forwording_no' => !empty($request->forwording_no) ? $request->forwording_no : $this->input->post('forwording_no'),
                'forworder_name' => !empty($request->forworder_name) ? $request->forworder_name : $this->input->post('forworder_name'),
                'pod_no' => !empty($request->awn) ? $request->awn : $this->input->post('awn'),
                'branch_id' => $branch_id,
                'customer_id' =>!empty($request->customer_account_id) ? $request->customer_account_id :  $this->input->post('customer_account_id'),
                'gst_charges' => !empty($request->gst_charges) ? $request->gst_charges: $this->input->post('gst_charges'),
                'status' => !empty($request->status) ? $request->status : 0,
                'booking_type' => 2
                );
    
            $query = $this->basic_operation_m->insert('tbl_domestic_booking', $data);
            
            $lastid = $this->db->insert_id();
            
            $frieht = !empty($request->frieht) ? $request->frieht : $this->input->post('frieht');
            $awb = !empty($request->awb) ? $request->awb : $this->input->post('awb');
            $topay = !empty($request->to_pay) ? $request->to_pay : $this->input->post('to_pay');
            $daoc = !empty($request->dod_daoc) ? $request->dod_daoc : $this->input->post('dod_daoc');
            $loading = !empty($request->loading) ? $request->loading : $this->input->post('loading');
            $packing = !empty($request->packing) ? $request->packing : $this->input->post('packing');
            $handling = !empty($request->handling) ? $request->handling : $this->input->post('handling');
            $oda = !empty($request->oda) ? $request->oda : $this->input->post('oda');
            $insurance = !empty($request->insurance) ? $request->insurance : $this->input->post('insurance');
            $fuel_subcharges = !empty($request->fuel_subcharges) ? $request->fuel_subcharges :  $this->input->post('fuel_subcharges');
            $data1 = array(
                'payment_id' => '',
                'booking_id' => $lastid,
                'amount' => !empty($request->amount) ? $request->amount : $this->input->post('amount'),
                'frieht' => !empty($request->frieht) ? $request->frieht : $this->input->post('frieht'),
                'awb' => !empty($request->awb) ? $request->awb : $this->input->post('awb'),
                'to_pay' => !empty($request->to_pay) ? $request->to_pay :  $this->input->post('to_pay'),
                'dod_daoc' => !empty($request->dod_daoc) ? $request->dod_daoc :  $this->input->post('dod_daoc'),
                'loading' => !empty($request->loading) ? $request->loading : $this->input->post('loading'),
                'packing' => !empty($request->packing) ? $request->packing : $this->input->post('packing'),
                'handling' => !empty($request->handling) ? $request->handling : $this->input->post('handling'),
                'oda' => !empty($request->oda) ? $request->oda : $this->input->post('oda'),
                'insurance' => !empty($request->insurance) ? $request->insurance : $this->input->post('insurance'),
                'fuel_subcharges' => !empty($request->fuel_subcharges) ? $request->fuel_subcharges :  $this->input->post('fuel_subcharges'),
                'IGST' => !empty($request->igst) ? $request->igst :  $this->input->post('igst'),
                'CGST' => !empty($request->cgst) ? $request->cgst : $this->input->post('cgst'),
                'SGST' => !empty($request->sgst) ? $request->sgst :  $this->input->post('sgst'),
                'total_amount' => !empty($request->frieht) ? $request->frieht :  $this->input->post('frieht'),
                );
            $length = !empty($request->length) ? $request->length : $this->input->post('length');
            $breath = !empty($request->breath) ? $request->breath : $this->input->post('breath');
            $height = !empty($request->height) ? $request->height :  $this->input->post('height');
            $no_of_pack = !empty($request->no_of_pack) ? $request->no_of_pack : $this->input->post('no_of_pack');
            if($no_of_pack == ''){
                $no_of_pack = 1;
            }
            
            $one_cft_kg = !empty($request->one_cft_kg) ? $request->one_cft_kg :  $this->input->post('one_cft_kg');
           
            $data2 = array(
                'weight_details_id' => '',
                'booking_id' => $lastid,
                'actual_weight' => !empty($request->actual_weight) ? $request->actual_weight : $this->input->post('actual_weight'),
                'valumetric_weight' => !empty($request->valumetric_weight) ? $request->valumetric_weight : $this->input->post('valumetric_weight'),
                'length' => !empty($request->length) ? $request->length : $this->input->post('length'),
                'breath' => !empty($request->breath) ? $request->breath : $this->input->post('breath'),
                'height' => !empty($request->height) ? $request->height : $this->input->post('height'),
                'one_cft_kg' => !empty($request->one_cft_kg) ? $request->one_cft_kg : $this->input->post('one_cft_kg'),
                'chargable_weight' => !empty($request->chargable_weight) ? $request->chargable_weight : $this->input->post('chargable_weight'),
                'rate' => !empty($request->rate) ? $request->rate : $this->input->post('rate'),
                'rate_type' => !empty($request->rate_type) ? $request->rate_type : $this->input->post('rate_type'),
                'rate_pack' => !empty($request->rate_pack) ? $request->rate_pack : $this->input->post('rate_pack'),
                'no_of_pack' => !empty($request->no_of_pack) ? $request->no_of_pack : $this->input->post('no_of_pack'),
                'type_of_pack' => !empty($request->type_of_pack) ? $request->type_of_pack : $this->input->post('type_of_pack'),
                'special_instruction' => !empty($request->special_instruction) ? $request->special_instruction : $this->input->post('special_instruction'),
            );
    
            $query1 = $this->basic_operation_m->insert('tbl_charges', $data1);
            
            $query2 = $this->basic_operation_m->insert('tbl_weight_details', $data2);
        
            $whr = array('username' => $username);
            $res = $this->basic_operation_m->getAll('tbl_users', $whr);
            $branch_id = $res->row()->branch_id;
            
            $whr = array('branch_id' => $branch_id);
            $res = $this->basic_operation_m->getAll('tbl_branch', $whr);
            $branch_name = $res->row()->branch_name;
            
            
            $whr = array('booking_id' => $lastid);
            $res = $this->basic_operation_m->getAll('tbl_domestic_booking', $whr);
            $podno = $res->row()->pod_no;
            $customerid= $res->row()->customer_id;
            $data3 = array('id' => '',
                'pod_no' => $podno,
                'status' => 'booked',
                'branch_name' => $branch_name,
                'tracking_date' => $date,
                );
        
            $result3 = $this->basic_operation_m->insert('tbl_domestic_tracking', $data3);
        
        
            if ($this->db->affected_rows() > 0) {
                $data['message'] = "Booking added successfull";
            } else {
                $data['message'] = "Failed to Submit";
            }
            echo json_encode($data);	 
            exit;
       
        } */
        
     /*   public function counterStatus() {
            $date = date('Y-m-d');
            $data['cnt_delivery'] = 0;
            $postdata = file_get_contents("php://input");
    	    $request = json_decode($postdata);
    	    
            $username = !empty($request->user_name) ? $request->user_name : $this->input->post('user_name');
            $whr = array('username' => $username);
            $res = $this->basic_operation_m->getAll('tbl_users', $whr);
            $branch_id = $res->row()->branch_id;
                $data = array();
                $whrAct = array('isactive' => 1, 'isdeleted' => 0);

                $TodayresAct = $this->db->query("select count(*) as totalshipment from tbl_domestic_booking where tbl_domestic_booking.branch_id='$branch_id' and date_format(booking_date, '%Y-%m-%d') = '" . date('Y-m-d') . "'");
                    $data['todayshipment'] = ($TodayresAct->num_rows() > 0) ? $TodayresAct->row()->totalshipment : 0;
                $resAct = $this->db->query("select count(*) as totalshipment from tbl_domestic_booking where tbl_domestic_booking.branch_id='$branch_id'");
		       $data['totalshipment'] = ($resAct->num_rows() > 0) ? $resAct->row()->totalshipment : 0;
                $PendingresAct = $this->db->query("select count(*) as totalshipment from tbl_domestic_booking where tbl_domestic_booking.branch_id='$branch_id' and status = 0");
        
                    $data['pendingshipment'] = ($PendingresAct->num_rows() > 0) ? $PendingresAct->row()->totalshipment : 0;
		     
                 echo json_encode($data);	 
            exit;
        }

*/


	public function uploadpod()
	{
		$data1= array();
		$date=date('y-m-d');
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$pod_no=$request->awnno;
		$username=$request->username;
		$image=$request->imagedata;
		$image = str_replace('data:image/png;base64,', '', $image);
		$image = str_replace(' ', '+', $image);
		$data = base64_decode($image);
		$filename = 'pod_'.$pod_no.'.png';
		$filepath='admin/uploads/pod/'.$filename;
		$success = file_put_contents($filepath, $data);
		$r= array('id'=>'',
			'deliveryboy_id'=>$username,
			'pod_no'=>$pod_no,
			'image'=>$filename,
			'delivery_date'=>$date
			);
		
		$this->basic_operation_m->insert('tbl_upload_pod',$r);
		$lastid=$this->db->insert_id();
		
		if ($lastid>0) {
			$data1['result']="success";	
			$data1['message']="POD Uploaded Successfully";
		}else{
			$data1['result']="fail";	
			$data1['message']="Error Try Again";
		}
		echo json_encode($data1);
	}
		

	/*public function trackshipment()
		{
			$data= array();
			$postdata = file_get_contents("php://input");
			$request = json_decode($postdata);

			if($request->awnno==null)
			{

				$pod_no=$this->input->post('awnno');
			}else{
				$pod_no=$request->awnno;
			}

			$reAct=$this->db->query("select * from tbl_domestic_booking where pod_no='$pod_no'");
			
			if($reAct->num_rows()>0)
			{

				$reAct1=$this->db->query("select * from tbl_domestic_tracking where pod_no='$pod_no'");


				$reAct2=$this->db->query("select * from tbl_upload_pod where pod_no='$pod_no'");

				$data['result']="success";	
				$data['data'] =array("bookinginfo"=>$reAct->row(),"trackinfo"=>$reAct1->result(),"imageinfo"=>$reAct2->row());         
			}else{
				$data['result']="fail";
				$data['message']="No Tracking  Available";
			}
			echo json_encode($data);
		}
*/


	public function menifiestTracking() {
    	    $postdata = file_get_contents("php://input");
    	    $request = json_decode($postdata);
    		$menifiest_id = !empty($request->menifiest_id) ? $request->menifiest_id : $this->input->post('menifiest_id');
    	    $data= array();
			// $resAct=$this->db->query("select manifiest_id,tbl_domestic_booking.pod_no,source_branch,destination_branch,date_added,booking_id,sender_name,sender_address
			// 	,reciever_name,reciever_address,booking_date,branch_id,rec_pincode, forwarder_mode,method as payment_method , total_weight,total_pcs,mode_name
			// 	from tbl_domestic_menifiest
			// 	INNER JOIN tbl_domestic_booking ON tbl_domestic_booking.pod_no=tbl_domestic_menifiest.pod_no 
			// 	LEFT JOIN payment_method ON tbl_domestic_booking.payment_method=payment_method.id
			// 	JOIN transfer_mode ON transfer_mode.transfer_mode_id=tbl_domestic_menifiest.forwarder_mode
			// 	where manifiest_id='$menifiest_id'");

			$resAct=$this->db->query("select manifiest_id,tbl_domestic_booking.pod_no,source_branch,destination_branch,date_added,booking_id,sender_name,sender_address
				,reciever_name,reciever_address,booking_date,branch_id,rec_pincode, forwarder_mode,method as payment_method , total_weight,total_pcs,mode_name
				from tbl_domestic_menifiest
				INNER JOIN tbl_domestic_booking ON tbl_domestic_booking.pod_no=tbl_domestic_menifiest.pod_no 
				LEFT JOIN payment_method ON tbl_domestic_booking.payment_method=payment_method.id
				JOIN transfer_mode ON transfer_mode.transfer_mode_id=tbl_domestic_menifiest.forwarder_mode
				where manifiest_id='$menifiest_id' AND reciving_status=0");


			// add pincode, mode ,qty, weight, payment  parameters in 

			// echo $this->db->last_query();
			$data['manifiest']=$resAct->result_array();
			echo json_encode($data);	 
            exit;	
    		  	
    	}


 	public function getallbranchshipment()
	{
		$data= array();
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$branch_id = !empty($request->branch_id) ? $request->branch_id :$this->input->post('branch_id') ;
		// $resAct = $this->db->query("select * from tbl_charges,tbl_domestic_booking,tbl_weight_details 
			// where tbl_charges.booking_id =tbl_domestic_booking.booking_id 
			// and tbl_weight_details.booking_id=tbl_charges.booking_id AND tbl_domestic_booking.branch_id='$branch_id'");

		$resAct = $this->db->query("select * from tbl_domestic_booking,tbl_domestic_weight_details 
			where  tbl_domestic_weight_details.booking_id=tbl_domestic_booking.booking_id AND tbl_domestic_booking.branch_id='$branch_id'");
		if($resAct->num_rows()>0)
		{
			$bookingData = [];
			$i = 0;
			foreach($resAct->result_array() as $booking) {
			   	$region_query = $this->db->query("SELECT `state`.`region_id` FROM `state` join city ON `city`.`state_id` = `state`.`id` WHERE `city`.`id` = ".$booking['reciever_city']); 
				$regioData = $region_query->row();
				$region_id = $regioData->region_id;
				$bookingData[$i]['pod_no'] = $booking['pod_no'];
			// 	$bookingData[$i]['booking_id'] = $booking['booking_id'];
				$bookingData[$i]['sender_name'] = $booking['sender_name'];
			// 	$bookingData[$i]['sender_address'] = $booking['sender_address'];
				$bookingData[$i]['sender_city'] = $booking['sender_city'];
			// 	$bookingData[$i]['sender_pincode'] = $booking['sender_pincode'];
			// 	$bookingData[$i]['sender_contactno'] = $booking['sender_contactno'];
			// 	$bookingData[$i]['sender_gstno'] = $booking['sender_gstno'];
				$bookingData[$i]['reciever_name'] = $booking['reciever_name'];
			// 	$bookingData[$i]['reciever_address'] = $booking['reciever_address'];
				$bookingData[$i]['reciever_city'] = $booking['reciever_city'];
			// 	$bookingData[$i]['reciever_pincode'] = $booking['reciever_pincode'];
			// 	$bookingData[$i]['reciever_contact'] = $booking['reciever_contact'];
			// 	$bookingData[$i]['receiver_gstno'] = $booking['receiver_gstno'];
				$bookingData[$i]['booking_date'] = $booking['booking_date'];
				$bookingData[$i]['mode_dispatch'] = $booking['mode_dispatch'];
			// 	$bookingData[$i]['dispatch_details'] = $booking['dispatch_details'];
			// 	$bookingData[$i]['insurace_policyno'] = $booking['insurace_policyno'];
			// 	$bookingData[$i]['forwording_no'] = $booking['forwording_no'];
			// 	$bookingData[$i]['forworder_name'] = $booking['forworder_name'];
			// 	$bookingData[$i]['weight_details_id'] = $booking['weight_details_id'];
			// 	$bookingData[$i]['payment_details_id'] = $booking['payment_details_id'];
			// 	$bookingData[$i]['branch_id'] = $booking['branch_id'];
			// 	$bookingData[$i]['customer_id'] = $booking['customer_id'];
				$bookingData[$i]['status'] = $booking['status'];
			// 	$bookingData[$i]['Box'] = $booking['type_of_pack'];
			// 	$bookingData[$i]['kg'] = $booking['actual_weight'];
			// 	$bookingData[$i]['zone'] = $region_id;
			// 	$bookingData[$i]['EDD'] = $booking['delivery_date'];
			// 	$bookingData[$i]['frieht'] = $booking['frieht'];
				$i++;

			}
			$data['result']="success";	
			$data['data'] =$bookingData;         
		}else{
			$data['result']="fail";
			$data['message']="No Shipment Available";
		}
		echo json_encode($data);
	}


 	public function getallbranchshipmentbystatus()
	{
		$data= array();
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$branch_id = !empty($request->branch_id) ? $request->branch_id :$this->input->post('branch_id') ;
		$status = !empty($request->status) ? $request->status :$this->input->post('status') ;
		$resAct = $this->db->query("select * from tbl_domestic_booking,tbl_domestic_weight_details,tbl_domestic_tracking 
			where  
			 tbl_domestic_weight_details.booking_id=tbl_domestic_booking.booking_id AND tbl_domestic_tracking.pod_no = tbl_domestic_booking.pod_no and tbl_domestic_booking.branch_id='".$branch_id."' and tbl_domestic_tracking.status='".$status."' ORDER BY `tbl_domestic_tracking`.`id` DESC");

		// echo $this->db->last_query();exit();
		if($resAct->num_rows()>0)
		{
			$bookingData = [];
			$i = 0;
			foreach($resAct->result_array() as $booking) {
				$region_query = $this->db->query("SELECT `state`.`region_id` FROM `state` join city ON `city`.`state_id` = `state`.`id` WHERE `city`.`id` = ".$booking['reciever_city']); 
				$regioData = $region_query->row();
				$region_id = $regioData->region_id;
				$bookingData[$i]['pod_no'] = $booking['pod_no'];
			// 	$bookingData[$i]['booking_id'] = $booking['booking_id'];
				$bookingData[$i]['sender_name'] = $booking['sender_name'];
			// 	$bookingData[$i]['sender_address'] = $booking['sender_address'];
				$bookingData[$i]['sender_city'] = $booking['sender_city'];
			// 	$bookingData[$i]['sender_pincode'] = $booking['sender_pincode'];
			// 	$bookingData[$i]['sender_contactno'] = $booking['sender_contactno'];
			// 	$bookingData[$i]['sender_gstno'] = $booking['sender_gstno'];
				$bookingData[$i]['reciever_name'] = $booking['reciever_name'];
			// 	$bookingData[$i]['reciever_address'] = $booking['reciever_address'];
				$bookingData[$i]['reciever_city'] = $booking['reciever_city'];
			// 	$bookingData[$i]['reciever_pincode'] = $booking['reciever_pincode'];
			// 	$bookingData[$i]['reciever_contact'] = $booking['reciever_contact'];
			// 	$bookingData[$i]['receiver_gstno'] = $booking['receiver_gstno'];
				$bookingData[$i]['booking_date'] = $booking['booking_date'];
				$bookingData[$i]['mode_dispatch'] = $booking['mode_dispatch'];
			// 	$bookingData[$i]['dispatch_details'] = $booking['dispatch_details'];
			// 	$bookingData[$i]['insurace_policyno'] = $booking['insurace_policyno'];
			// 	$bookingData[$i]['forwording_no'] = $booking['forwording_no'];
			// 	$bookingData[$i]['forworder_name'] = $booking['forworder_name'];
			// 	$bookingData[$i]['weight_details_id'] = $booking['weight_details_id'];
			// 	$bookingData[$i]['payment_details_id'] = $booking['payment_details_id'];
			// 	$bookingData[$i]['branch_id'] = $booking['branch_id'];
			// 	$bookingData[$i]['customer_id'] = $booking['customer_id'];
				$bookingData[$i]['status'] = $booking['status'];
			// 	$bookingData[$i]['Box'] = $booking['type_of_pack'];
			// 	$bookingData[$i]['kg'] = $booking['actual_weight'];
			// 	$bookingData[$i]['zone'] = $region_id;
			// 	$bookingData[$i]['EDD'] = $booking['delivery_date'];
			// 	$bookingData[$i]['frieht'] = $booking['frieht'];
				$i++;

			}
			$data['result']="success";	
			$data['data'] =$bookingData;         
		}else{
			$data['result']="fail";
			$data['message']="No Shipment Available";
		}
		echo json_encode($data);
	}

	public function viewIncomingShipping() {
			$postdata = file_get_contents("php://input");
			$request = json_decode($postdata);
			
			$username= !empty($request->user_name) ? $request->user_name : $this->input->post('user_name');
	
			$whr = array('username'=>$username);
			$res=$this->basic_operation_m->getAll('tbl_users',$whr);
			$branch_id= $res->row()->branch_id;
			
			$whr = array('branch_id'=>$branch_id);
			$res=$this->basic_operation_m->getAll('tbl_branch',$whr);
			$branch_name= $res->row()->branch_name;
	
		
			$resAct=$this->db->query("select manifiest_id,date_added,total_weight as total_weight,
				total_pcs as total_pcs,forwarder_name,mode_name,coloader,destination_branch,source_branch from tbl_domestic_menifiest JOIN transfer_mode ON transfer_mode.transfer_mode_id=tbl_domestic_menifiest.forwarder_mode where destination_branch='$branch_name' AND reciving_status=0 group by manifiest_id");

			// select *,sum(total_pcs) as total_pcs,sum(total_weight) as total_weight,mode_name from tbl_domestic_menifiest JOIN transfer_mode ON transfer_mode.transfer_mode_id=tbl_domestic_menifiest.forwarder_mode where tbl_domestic_menifiest.source_branch='$branch_name' group by manifiest_id order by manifiest_id desc

			// echo $this->db->last_query();exit();
		if ($resAct->num_rows()>0) {
			$data['menifiest']=$resAct->result();
		} else {
			$data['menifiest']=array();
			
		}
		// $data['branch_name']=$branch_name;
		echo json_encode($data);	 
		exit;
	}

	public function getallnewshipment()
	{
		$data= array();
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$customer_id = $request->customer_id;
		$resAct = $this->db->query("select * from tbl_charges,tbl_domestic_booking,tbl_weight_details 
			where tbl_charges.booking_id =tbl_domestic_booking.booking_id 
			and tbl_weight_details.booking_id=tbl_charges.booking_id and tbl_domestic_booking.booking_type =2 ");
		if($resAct->num_rows()>0)
		{
			$bookingData = [];
			$i = 0;
			foreach($resAct->result_array() as $booking) {
				if(is_numeric($booking['reciever_city'])) {
				$region_query = $this->db->query("SELECT `state`.`region_id` FROM `state` join city ON `city`.`state_id` = `state`.`id` WHERE `city`.`id` = '".$booking['reciever_city']."'"); 
				$regioData = $region_query->row();
				$region_id = $regioData->region_id;
				$bookingData[$i]['pod_no'] = $booking['pod_no'];
				$bookingData[$i]['booking_id'] = $booking['booking_id'];
				$bookingData[$i]['sender_name'] = $booking['sender_name'];
				$bookingData[$i]['sender_address'] = $booking['sender_address'];
				$bookingData[$i]['sender_city'] = $booking['sender_city'];
				$bookingData[$i]['sender_pincode'] = $booking['sender_pincode'];
				$bookingData[$i]['sender_contactno'] = $booking['sender_contactno'];
				$bookingData[$i]['sender_gstno'] = $booking['sender_gstno'];
				$bookingData[$i]['reciever_name'] = $booking['reciever_name'];
				$bookingData[$i]['reciever_address'] = $booking['reciever_address'];
				$bookingData[$i]['reciever_city'] = $booking['reciever_city'];
				$bookingData[$i]['reciever_pincode'] = $booking['reciever_pincode'];
				$bookingData[$i]['reciever_contact'] = $booking['reciever_contact'];
				$bookingData[$i]['receiver_gstno'] = $booking['receiver_gstno'];
				$bookingData[$i]['booking_date'] = $booking['booking_date'];
				$bookingData[$i]['mode_dispatch'] = $booking['mode_dispatch'];
				$bookingData[$i]['dispatch_details'] = $booking['dispatch_details'];
				$bookingData[$i]['insurace_policyno'] = $booking['insurace_policyno'];
				$bookingData[$i]['forwording_no'] = $booking['forwording_no'];
				$bookingData[$i]['forworder_name'] = $booking['forworder_name'];
				$bookingData[$i]['weight_details_id'] = $booking['weight_details_id'];
				$bookingData[$i]['payment_details_id'] = $booking['payment_details_id'];
				$bookingData[$i]['branch_id'] = $booking['branch_id'];
				$bookingData[$i]['customer_id'] = $booking['customer_id'];
				$bookingData[$i]['status'] = $booking['status'];
				$bookingData[$i]['Box'] = $booking['type_of_pack'];
				$bookingData[$i]['kg'] = $booking['actual_weight'];
				$bookingData[$i]['zone'] = $region_id;
				$bookingData[$i]['EDD'] = $booking['delivery_date'];
				$bookingData[$i]['frieht'] = $booking['frieht'];
				$i++;
				}

			}
			$data['result']="success";	
			$data['data'] =$bookingData;         
		}else{
			$data['result']="fail";
			$data['message']="No Shipment Available";
		}
		echo json_encode($data);
	}

	public function getallshipment()
	{
		$data= array();
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$customer_id = $request->customer_id;
		$resAct = $this->db->query("select * from tbl_domestic_booking,tbl_domestic_weight_details 
			where tbl_domestic_weight_details.booking_id=tbl_domestic_booking.booking_id AND tbl_domestic_booking.customer_id='$customer_id'");
		if($resAct->num_rows()>0)
		{
			$bookingData = [];
			$i = 0;
			foreach($resAct->result_array() as $booking) {
				$region_query = $this->db->query("SELECT `state`.`region_id` FROM `state` join city ON `city`.`state_id` = `state`.`id` WHERE `city`.`id` = ".$booking['reciever_city']); 

				foreach ($booking as $key => $value) {
					if ($value==NULL || $value=='null') {
						$booking[$key] = '';
					}
				}
				if (!isset($booking['insurace_policyno'])) {
					$booking['insurace_policyno'] = '';
				}
				if (!isset($booking['payment_details_id'])) {
					$booking['payment_details_id'] = '';
				}
				$regioData = $region_query->row();
				$region_id = $regioData->region_id;
				$bookingData[$i]['pod_no'] = $booking['pod_no'];
				$bookingData[$i]['booking_id'] = $booking['booking_id'];
				$bookingData[$i]['sender_name'] = $booking['sender_name'];
				$bookingData[$i]['sender_address'] = $booking['sender_address'];
				$bookingData[$i]['sender_city'] = $booking['sender_city'];
				$bookingData[$i]['sender_pincode'] = $booking['sender_pincode'];
				$bookingData[$i]['sender_contactno'] = $booking['sender_contactno'];
				$bookingData[$i]['sender_gstno'] = $booking['sender_gstno'];
				$bookingData[$i]['reciever_name'] = $booking['reciever_name'];
				$bookingData[$i]['reciever_address'] = $booking['reciever_address'];
				$bookingData[$i]['reciever_city'] = $booking['reciever_city'];
				$bookingData[$i]['reciever_pincode'] = $booking['reciever_pincode'];
				$bookingData[$i]['reciever_contact'] = $booking['reciever_contact'];
				$bookingData[$i]['receiver_gstno'] = $booking['receiver_gstno'];
				$bookingData[$i]['booking_date'] = $booking['booking_date'];
				$bookingData[$i]['mode_dispatch'] = $booking['mode_dispatch'];
				$bookingData[$i]['dispatch_details'] = $booking['dispatch_details'];
				$bookingData[$i]['insurace_policyno'] = @$booking['insurace_policyno'];
				$bookingData[$i]['forwording_no'] = $booking['forwording_no'];
				$bookingData[$i]['forworder_name'] = $booking['forworder_name'];
				$bookingData[$i]['weight_details_id'] = $booking['weight_details_id'];
				$bookingData[$i]['payment_details_id'] = @$booking['payment_details_id'];
				$bookingData[$i]['branch_id'] = $booking['branch_id'];
				$bookingData[$i]['customer_id'] = $booking['customer_id'];
				$bookingData[$i]['status'] = $booking['status'];
				$bookingData[$i]['Box'] = $booking['type_of_pack'];
				$bookingData[$i]['kg'] = $booking['actual_weight'];
				$bookingData[$i]['zone'] = $region_id;
				$bookingData[$i]['EDD'] = $booking['delivery_date'];
				$bookingData[$i]['frieht'] = $booking['frieht'];
				$i++;

			}
			$data['result']="success";	
			$data['data'] =$bookingData;         
		}else{
			$data['result']="fail";
			$data['message']="No Shipment Available";
		}
		echo json_encode($data);
	}

	public function trackshipment()
	{
		$data= array();
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		if($request->awnno==null)
		{

			$pod_no=$this->input->post('awnno');
		}else{
			$pod_no=$request->awnno;
		}

		$reAct=$this->db->query("select * from tbl_domestic_booking where pod_no='$pod_no'");
		
		if($reAct->num_rows()>0)
		{

			$reAct1=$this->db->query("select * from tbl_domestic_tracking where pod_no='$pod_no'");


			$reAct2=$this->db->query("select * from tbl_upload_pod where pod_no='$pod_no'");

			$data['result']="success";	
			$data['data'] =array("bookinginfo"=>$reAct->row(),"trackinfo"=>$reAct1->result(),"imageinfo"=>$reAct2->row());         
		}else{
			$data['result']="fail";
			$data['message']="No Tracking  Available";
		}
		echo json_encode($data);
	}

	

	public function getTrackingStatus() {
		$data= array();
		
		$reAct=$this->db->query("select status FROM tbl_status");
		
		if($reAct->num_rows()>0)
		{
			$result = $reAct->result_array();
			
			$data['result']="success";	
			$data['data'] = array_column($result, 'status');
		} else {
			$data['result']="fail";
			$data['message']="No Status Available";
		}
		echo json_encode($data);
	}

	public function updateStatus() {

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$pod_no = !empty($request->awnno) ? $request->awnno : $this->input->post('awnno');
		$status = !empty($request->status) ? $request->status : $this->input->post('status');
		$branch_id = !empty($request->branch_name) ? $request->branch_name : $this->input->post('branch_name');
		if (!empty($branch_id)) {
			$query = $this->db->query("SELECT branch_name FROM tbl_branch WHERE branch_id=$branch_id");
			if($query->num_rows()>0)
			{
				$branch_data = $query->row();
				$branch_name = $branch_data->branch_name;
			}			
			unset($branch_data);
		}
		if(!empty($pod_no) && !empty($status))
		{	
			$tracking_date = !empty($request->date) ? date('Y-m-d H:i:s',strtotime($request->date)) : date('d-m-Y H:i:s'); 
			$query = $this->db->query("insert into tbl_domestic_tracking (pod_no,status,branch_name,tracking_date) VALUES ('".$pod_no."','".$status."','".$branch_name."','".$tracking_date."')");

			if($this->db->affected_rows()>0)
			{
				$insertId = $this->db->insert_id();
				$data['result']="success";			 	

			}else{
				$data['result']="fail";	            
			}
		} else {
			$data['result'] ="fail" ;
		}
		echo json_encode($data);
	}

	public function login()
	{
		$data= array();

		//  $whrAct=array('username'=>$this->input->post('username'),'password'=>$this->input->post('password'));

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$cust_id = !empty($request->cust_id) ? $request->cust_id : $this->input->post('cust_id');
		$password = !empty($request->password) ? $request->password : $this->input->post('password');

		$this->db->select('*');
		$this->db->from('tbl_customers');
		$this->db->where('cid',$cust_id);
		$this->db->where('password',$password);

		$query=$this->db->get();
		// echo $this->db->last_query();
		if($query->num_rows() == 1)
		{
			$data['result']="success";
			$data['data']=$query->row();	
		}	
		else
		{
			$data['result']="fail";
			$data['message']="Invalid Username or Password!";
		}
		echo json_encode($data);
	}

	public function deliveryboylogin()
	{
		$data= array();

		//  $whrAct=array('username'=>$this->input->post('username'),'password'=>$this->input->post('password'));
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		if($request->username==null)
		{

			$username=$this->input->post('username');
			$password=$this->input->post('password');
		}else{
			$username=$request->username;
			$password=$request->password;
		}


		$key = $this->config->item('encryption_key');
        $salt1 = hash('sha512', $key . $password);
		$salt2 = hash('sha512', $password . $key);
		$hashed_password = hash('sha512', $salt1 . $password . $salt2);


		$this->db->select('*');
		$this->db->from('tbl_users');

		$this->db->join('tbl_user_types','tbl_user_types.user_type_id=tbl_users.user_type');
		$this->db->where('username',$username);
		$this->db->where('password',$hashed_password);

		$query=$this->db->get();

		if($query->num_rows() == 1)
		{
			$data['result']="success";
			$data['data']=$query->row();	
		}	
		else
		{
			$data['result']="fail";
			$data['message']="Invalid Username or Password!";
		}
		echo json_encode($data);
	}
	public function changepassword()
	{
		$data= array();
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$query = $this->db->query("update users SET password='$request->password' where user_id='$request->user_id'");

		if($this->db->affected_rows()>0)
		{
			$data['result']="success";

		//$data['noti']=$res->result_array();	                
		}else{
			$data['result']="fail";

		}

		echo json_encode($data);
	}


	public function pickup_request()
	{
		$data= array();
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$date=date('y-m-d');
		$r= array('id'=>'',
			'consigner_name'=>$request->name,
			'consigner_address'=>$request->address,
			'consigner_city' =>'',
			'consigner_gstno' => '',
			'consigner_pincode' => $request->pincode,
			'consignee_name' => '',
			'consignee_address' => '',
			'consignee_city' =>'',
			'consignee_gstno' => '',
			'consignee_pincode' => '',
			'pickup_date'=>$date,
					//'isdeleted' =>'0',
			);
		$result=$this->basic_operation_m->insert('tbl_pickup_request',$r);

		if ($this->db->affected_rows()>0) {
			$data['result']="success";
			$data['message']="Pickup Request Added Sucessfully";
		}else{
			$data['message']="Error in Query";
			$data['result']="fail";
		}
		
		echo json_encode($data);

	}


	public function forgotpassword()
	{

		$data= array();
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$whrAct=array('email'=>$request->email,'cid'=>$request->customer_id);
			//  $whrAct=array('username'=>$this->input->post('username'),'password'=>$this->input->post('password'));
		$resAct	= $this->basic_operation_m->selectRecord('users',$whrAct);      
		if($resAct->num_rows()>0)
		{
			$data['result']="success";
			$data['data']=$resAct->result_array();
			$row = $resAct->row();

			$x="<h2>Your Event app Login Details</h2><br>Username :".$row->username."<br>Email: ".$row->email."<br> Password:".$row->password;
			$this->load->library('email');
			$config['protocol'] = 'smtp';
			$config['smtp_host'] = 'mail.rajcargo.net';
			$config['smtp_user'] = 'info@rajcargo.net';
			$config['smtp_pass'] = 'HW@UJZ!CV8#5';
			$config['smtp_port'] = 26;
			$config['mailtype'] = 'html';
			$config['charset'] = 'iso-8859-1';



			$this->email->initialize($config);

			$this->email->from('info@rajcargo.net', 'Event Admin');
			$this->email->to($request->email);
					// $this->email->cc('another@another-example.com');
					// $this->email->bcc('them@their-example.com');

			$this->email->subject(' Login Details');
			$this->email->message($x);

			$this->email->send();
			$data['result']="success";
			$data['message']="Password Send on Email Successfully!";
		}else{
			$data['result']="fail";
			$data['message']="Invalid Username or Password!";
		}
		echo json_encode($data);
	}


	public function checkservice()
	{
		$data= array();
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		if($request->pincode==null)
		{
			$pincode=$this->input->post('pincode');
		}else{
			$pincode=$request->pincode;
		}

		$reAct=$this->db->query("select * from 	pincode where 	pincode.pin_code='$pincode'");

		if($reAct->num_rows()>0)
		{
			$data['result']="success";	
			$data['pincode'] =$reAct->row()->pin_code;   
			$data['message']="Service Available";
		}else{
			$data['result']="fail";
			$data['message']="No Service Available";
		}
		echo json_encode($data);
		

	}

	public function podsearch()
	{
		$data= array();
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		if($request->awnno==null)
		{

			$pod_no=$this->input->post('awnno');
		}else{
			$pod_no=$request->awnno;
		}

		$reAct=$this->db->query("select * from tbl_domestic_booking where pod_no='$pod_no'");
		

		
		$reAct1=$this->db->query("select * from tbl_upload_pod where pod_no='$pod_no'");
		

		
		if($reAct1->num_rows()>0)
		{
			$data['result']="success";	
			$data['info']=$reAct->row();  
			$data['poddetails']=$reAct1->row();
		}else{
			$data['result']="fail";
			$data['message']="No Pod Available";
		}
		echo json_encode($data);
		
	}
	

		
	public function getMainFiestDetail() {
		    
		$result = $this->db->query('select max(id) AS id from tbl_domestic_menifiest')->row();  
		$id= $result->id+1;
		if(strlen($id)==2)
		{
		   $id='M00'.$id;
		}else if(strlen($id)==3)
		{
		   $id='M0'.$id;
		}else if(strlen($id)==1)
		{
		   $id='M000'.$id;
		}else if(strlen($id)==4)
		{
		   $id='M'.$id;
		}
		
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
			
		 $username= !empty($request->user_name) ? $request->user_name : $this->input->post('user_name');
		 $whr = array('username'=>$username);
		 $res=$this->basic_operation_m->getAll('tbl_users',$whr);
		 $branch_id= $res->row()->branch_id;
		 
		 $whr = array('branch_id'=>$branch_id);
		 $res=$this->basic_operation_m->getAll('tbl_branch',$whr);
		 $branch_name= $res->row()->branch_name;
		$date=date('Y-m-d');
		$data['branch_name']=$branch_name;
		$resAct = $this->db->query("SELECT * FROM tbl_domestic_booking where branch_id='$branch_id' order by booking_date DESC");
		if($resAct->num_rows()>0)
		 {
		 	$data['bookingData']=$resAct->result();	            
         }
		
		 if($resAct->num_rows()>0)
		 {
		 	$data['branches']=$resAct->result();	            
         }
		 $data['mid']=$id;
	     echo json_encode($data);	 
	     exit;
		}
		
		
		public function addIncomingShipping() {
		    $data1= [];
        	 $postdata = file_get_contents("php://input");
        	 $request = json_decode($postdata);
        	
        	 $username = !empty($request->user_name) ? $request->user_name : $this->input->post('user_name');
             $mid = !empty($request->manifiest_id) ? $request->manifiest_id : $this->input->post('manifiest_id');
             $status = !empty($request->status) ? $request->status : $this->input->post('status');
             $pod_nos = !empty($request->pod_no) ? $request->pod_no : $this->input->post('pod_no');

             // $mid = 'MD0031';
             //$mid = 'MD0031';

             // $username = 'bc0015';

             
        
			 $whr = array('username'=>$username);
			 $res=$this->basic_operation_m->getAll('tbl_users',$whr);
			 $branch_id= $res->row()->branch_id;
			 
			 $whr = array('branch_id'=>$branch_id);
			 $res=$this->basic_operation_m->getAll('tbl_branch',$whr);
			 $branch_name= $res->row()->branch_name;
			 $date=date('Y-m-d H:i:s');
			 
	    	 $res=$this->db->query("select * from tbl_domestic_menifiest where destination_branch='".$branch_name."' and manifiest_id='".$mid."'");
	    	 // echo $this->db->last_query();
			 $d=$res->result();




			foreach ( $d as $key => $row) {
			    $pod_no=@$pod_nos[$key];
			    $ids=$row->id;

			    if (empty($pod_no)) {
			    	continue;
			    }

			    $data = [];
				$resAct=$this->db->query("select * from tbl_domestic_booking,city where tbl_domestic_booking.sender_city=city.id and pod_no='$pod_no' and tbl_domestic_booking.booking_type = 1 ");
			 	
			 	$data['info']=$resAct->row();

			 	if (empty($data['info'])) {
			    	continue;
			    }

			    $r = array('id'=>'',
				  	'branch_code' =>$branch_name,
				  	'pod_no'=>$pod_no,
				  	'status' => $status,
	              	'manifiest_id'=>$mid,
				  	'date_added'=>$date,
             	);


			  	$data1=array(
						
				 	'pod_no'=>$pod_no,
			 		'status'=>'In-Scan',
				 	'branch_name'=>$branch_name,
				 	'booking_id'=>$resAct->row()->booking_id,
				 	'forworder_name'=>'',
				 	'comment'=>'Intransit at '.$branch_name,
				 	'tracking_date'=>$date,
			 	);


				$result=$this->basic_operation_m->insert('tbl_inword',$r);
				$result1=$this->basic_operation_m->insert('tbl_domestic_tracking',$data1);

				// echo $this->db->last_query();
				

			 	$resAct=$this->db->query("select * from tbl_domestic_booking,city where tbl_domestic_booking.reciever_city=city.id and pod_no='$pod_no' and tbl_domestic_booking.booking_type = 1 ");
			 	$data['info1']=$resAct->row();
				
				//$pod_no=$this->input->post('pod_no');
				$reAct=$this->db->query("select * from tbl_domestic_tracking where pod_no='$pod_no'");
	          
				$data['pod']=$reAct->result_array();
				
				
				$reAct=$this->db->query("select * from tbl_upload_pod where pod_no='$pod_no'");
				
				$data['podimg']=$reAct->row();
	            $whr = array('pod_no'=>$pod_no);
				$res=$this->basic_operation_m->getAll('tbl_domestic_booking',$whr);
				$customerid= $res->row()->customer_id;
				if($customerid != '') {
	    		    $whr = array('customer_id'=>$customerid);
	    			$res=$this->basic_operation_m->getAll('tbl_customers',$whr);
	    			if(!empty($res->row())){
	        			$email= $res->row()->email;
	         			$data['customer_name'] = $res->row()->customer_name;
	        			
	        			// $message = $this->load->view('senttracking',$data, true);
	        			// $this->sendemail($email,$message);
	                   
	    			}
	          	}

	          	$query = $this->db->query("update tbl_domestic_menifiest SET reciving_status=1 where id='$ids'");

				
			}

			// echo $this->db->last_query();
			if ($this->db->affected_rows()>0) {
				$data3['message']="Menifiest status updated sucessfully";
			}else{
                $data3['message']="Error in Query";
			}
			echo json_encode($data3);	 
	        exit;
		}
		
		
		public function addmenifiest_old()
		{ 
		 	$postdata = file_get_contents("php://input");
		 	$request = json_decode($postdata);

		 	$arr = array('pop','uhsh','hgsshg');

		 	// echo json_encode($arr);
		 	

	    		
		 	$username = !empty(@$request->user_name) ? @$request->user_name : $this->input->post('user_name');
		 	$destination_branch = !empty($request->destination_branch) ? $request->destination_branch : $this->input->post('destination_branch');
		 	$dateTime = !empty($request->datetime) ? $request->datetime : $this->input->post('datetime'); 
			 
	       	$whr = array('username'=>$username);
			$res=$this->basic_operation_m->getAll('tbl_users',$whr);

			// echo $this->db->last_query();
		    $branch_id= $res->row()->branch_id;
			$date=date('Y-m-d H:i:s');
			 
			$whr = array('branch_id'=>$branch_id);
			$res=$this->basic_operation_m->getAll('tbl_branch',$whr);
			$branch_name= $res->row()->branch_name;
			$pod= !empty($request->pod_no) ? $request->pod_no : $this->input->post('pod_no');



			$result = $this->db->query('select max(inc_id) AS id from tbl_domestic_menifiest')->row();  
			$manifiest_id= $result->id+1;
			if(strlen($manifiest_id)==2)
			{
			   $manifiest_id='MD00'.$manifiest_id;
			}else if(strlen($manifiest_id)==3)
			{
			   $manifiest_id='MD0'.$manifiest_id;
			}else if(strlen($manifiest_id)==1)
			{
			   $manifiest_id='MD000'.$manifiest_id;
			}else if(strlen($manifiest_id)==4)
			{
			   $manifiest_id='MD'.$manifiest_id;
			}

			foreach ($pod as  $row1) {
				
	         	$data=array('id'=>'',
	    	     	'manifiest_id'=>$this->input->post('manifiest_id'),
		    	 	'pod_no'=>$row1,
				 	'source_branch'=>$branch_name,
				 	'destination_branch'=>$destination_branch,
				 	'date_added'=>$dateTime
				);
				$date=date('Y-m-d H:i:s');	

			 	$data1=array('id'=>'',
				 	'pod_no'=>$row1,
				 	'status'=>'Intransit',
				 	'branch_name'=>$branch_name,
				 	'tracking_date'=>$date,
			 	);

			 	$data2=array('id'=>'',
				 	'pod_no'=>$row1,
				 	'status'=>'forworded',
				 	'branch_name'=>$destination_branch,
				 	'tracking_date'=>$date,
			 	);
			
				$result=$this->basic_operation_m->insert('tbl_domestic_menifiest',$data);
				$result1=$this->basic_operation_m->insert('tbl_domestic_tracking',$data1);
				$result2=$this->basic_operation_m->insert('tbl_domestic_tracking',$data2);
				$data = [];
				$pod_no = $row1;
				
				$resAct=$this->db->query("select * from tbl_domestic_booking,city where tbl_domestic_booking.sender_city=city.id and pod_no='$pod_no' and tbl_domestic_booking.booking_type = 1 ");
			 	
			 	$data['info']=$resAct->row();
			 
			 	$resAct=$this->db->query("select * from tbl_domestic_booking,city where tbl_domestic_booking.reciever_city=city.id and pod_no='$pod_no' and tbl_domestic_booking.booking_type = 1 ");
			 	
			 	$data['info1']=$resAct->row();
			
				//$pod_no=$this->input->post('pod_no');
				$reAct=$this->db->query("select * from tbl_domestic_tracking where pod_no='$pod_no'");
	      
				$data['pod']=$reAct->result_array();
			
			
				$reAct=$this->db->query("select * from tbl_upload_pod where pod_no='$pod_no'");
			
				$data['podimg']=$reAct->row();
	        	$whr = array('pod_no'=>$pod_no);
				$res=$this->basic_operation_m->getAll('tbl_domestic_booking',$whr);
				$customerid= $res->row()->customer_id;
				if($customerid != '') {
				    $whr = array('customer_id'=>$customerid);
					$res=$this->basic_operation_m->getAll('tbl_customers',$whr);
					if(!empty($res->row())){
		    			$email= $res->row()->email;
		     			$data['customer_name'] = $res->row()->customer_name;
		    			
		    			// $message = $this->load->view('senttracking',$data, true);
		    			// $this->sendemail($email, $message);
		               
					}
	      		}
			}
			if ($this->db->affected_rows()>0) {
				$data['message']="Menifiest Added Sucessfully";
			}else{
	            $data['message']="Error in Query";
			}
			echo json_encode($data);	 
	        exit;	
			
		}


		public function addmenifiest()
		{ 
		 	$postdata = file_get_contents("php://input");
		 	$request = json_decode($postdata);

		 	$arr = array('pop','uhsh','hgsshg');

		 	// echo json_encode($arr);

		 	

	    		
		 	$username = !empty(@$request->user_name) ? @$request->user_name : $this->input->post('user_name');
		 	$destination_branch = !empty($request->destination_branch) ? $request->destination_branch : $this->input->post('destination_branch');
		 	$dateTime = !empty($request->datetime) ? $request->datetime : $this->input->post('datetime'); 
			 
	       	$whr = array('username'=>$username);
			$res=$this->basic_operation_m->getAll('tbl_users',$whr);

			// echo $this->db->last_query();
		    $branch_id= $res->row()->branch_id;
			$date=date('Y-m-d H:i:s');
			 
			$whr = array('branch_id'=>$branch_id);
			$res=$this->basic_operation_m->getAll('tbl_branch',$whr);
			$branch_name= $res->row()->branch_name;
			$pod= !empty($request->pod_no) ? $request->pod_no : $this->input->post('pod_no');



			// $user_type 			= 	$this->session->userdata("userType");
		    $user_id 			= 	$request->user_id;
			$all_data 			= $pod;


			// print_r($pod);
			if(!empty($all_data))
	        {

				// echo "pod found!";
				$pod	=	$all_data;
	           
				$whr 		= 	array('username'=>$username);
				$res		=	$this->basic_operation_m->getAll('tbl_users',$whr);
			    $branch_id	=	$res->row()->branch_id;
				
				 
				$whr 			= 	array('branch_id'=>$branch_id);
				$res			=	$this->basic_operation_m->getAll('tbl_branch',$whr);
				$branch_name	=	$res->row()->branch_name;
				$pod			= array_unique($pod);

				$result_max = $this->db->query('select max(inc_id) AS id from tbl_domestic_menifiest')->row();  
				$inc_id= $result_max->id+1;

				$result = $this->db->query('select max(inc_id) AS id from tbl_domestic_menifiest')->row();  
				$manifiest_id= $result->id+1;
				if(strlen($manifiest_id)==2)
				{
				   $manifiest_id='MD00'.$manifiest_id;
				}else if(strlen($manifiest_id)==3)
				{
				   $manifiest_id='MD0'.$manifiest_id;
				}else if(strlen($manifiest_id)==1)
				{
				   $manifiest_id='MD000'.$manifiest_id;
				}else if(strlen($manifiest_id)==4)
				{
				   $manifiest_id='MD'.$manifiest_id;
				}

				// $this->input->post('manifiest_id') = $manifiest_id;

				if (!isset($request->destination_branch)) {
					$request->destination_branch = '';
				}
				foreach ($pod as  $pdno) 
				{	
					$arr 	= explode("*",$pdno);
					$pdno 	= $arr[0];
					
					$pcs  	= @$arr[1];
					$a_w  	= @$arr[2];

					if (!$a_w) { $a_w=0;}
					if (!$pcs) { $pcs=0;}
			

					$whr 					=	array('pod_no'=>$pdno);
					$booking_info			=	$this->basic_operation_m->getAll('tbl_domestic_booking',$whr);

					// if (empty($booking_info)) {
					// 	$data['message']="POD Invalid!";
					// 	$data['status']="Failed";

					// 	echo json_encode($data);	 
	    			// exit;	
					// }

					// print_r($booking_info);exit();
					$data=array(
						
	        	     	'manifiest_id'=>$manifiest_id,
	    	    	 	'pod_no'=>$pdno,
					 	'source_branch'=>$branch_name,
	    			 	'user_id' => $user_id,
					 	'date_added'=>date('Y-m-d H:i:s',strtotime($request->datetime)),
					 	'lorry_no' => $request->lorry_no,
					 	'driver_name' => $request->driver_name,
					 	'coloader' => $request->coloader,
					 	'forwarder_name' => $request->forwarder_name,
					 	'forwarder_mode' => $request->mode,
					 	'total_weight' => $a_w,
					 	'total_pcs' => $pcs,						 
					 	'contact_no' => $request->contact_no,
					 	'vendor_id' => @$request->vendor_id,
					 	'destination_branch' => @$request->destination_branch,
					 	'inc_id'=>$inc_id,
					 	'dimention'=>'',
					);
							
					
					$result=$this->basic_operation_m->insert('tbl_domestic_menifiest',$data);
					// echo $this->db->last_query();exit;					
					
					$menifiest_branches		= 	$booking_info->row()->menifiest_branches;
					$booking_id				= 	$booking_info->row()->booking_id;
				
					if (!empty($request->datetime)) {
						$date = date('Y-m-d H:i:s',strtotime($request->datetime));
					}
					

					// $date = str_replace(": ", ":", $date);
					// echo $date;
					// exit();
					

					// echo $this->db->last_query();exit();
					
					$pod_no 	= 	$pdno;
				
					
					if(!empty($menifiest_branches))
					{
						$braches_ids 		= explode(',',$menifiest_branches);
						$braches_ids[]		= $branch_id;
						$braches_ids		= array_unique($braches_ids);
						$menifiest_branches		= implode(',',$braches_ids);
					}
					else
					{
						$menifiest_branches			= $branch_id;
					}
					
					$queue_dataa = "update tbl_domestic_booking set menifiest_branches ='$menifiest_branches',menifiest_recived ='1' where booking_id = '$booking_id'";
					$status	= $this->db->query($queue_dataa);
					if ($this->db->affected_rows()>0) 
					{
						$data1=array(
						
						 	'pod_no'=>$pdno,
					 		'status'=>'In-transit',
						 	'branch_name'=>$request->destination_branch,
						 	'booking_id'=>$booking_id,
						 	'forworder_name'=>$request->forwarder_name,
						 	'comment'=>'',
						 	'tracking_date'=>$date,
					 	);
					
						
						//$result2	=	$this->basic_operation_m->insert('tbl_domestic_tracking',$data2);
						$result1	=	$this->basic_operation_m->insert('tbl_domestic_tracking',$data1);
					}	
				
				}
					
				if ($this->db->affected_rows()>0) 
				{
					$data['message']="Menifiest Added Sucessfully";
					$msg	= 'Menifiest Added successfully';
					$class	= 'alert alert-success alert-dismissible';	
				}
				else
				{
					$data['message']="Menifiest not Added successfully";
		            $msg	= 'Menifiest not Added successfully';
					$class	= 'alert alert-danger alert-dismissible';
				}

				// echo $this->db->last_query();
				
			}else{
				$data['message']="POD Not Selected!";
			}



			// if ($this->db->affected_rows()>0) {
			// 		$data['message']="Menifiest Added Sucessfully";
			// }else{
	        // 		$data['message']="Error in Query";
			// }
			echo json_encode($data);	 
	        exit;	
			
		}
		
		
		//CMS API Start
		public function getCityList() {
            $data = [];
            $cityQuery = $this->db->query("SELECT * FROM `city`");
            $data['city'] = $cityQuery->result();
            echo json_encode($data);
            exit;
        }
        
        public function terms() {
           $data = [];
           $terms_query = $this->db->query("select * from tbl_terms");
           $data = $terms_query->result();
           echo json_encode($data);
           exit;
          
        }
        public function privacypolicy() {
           $data = [];
           $privacy_query = $this->db->query("select * from tbl_privacy");
           $data = $privacy_query->result();
           echo json_encode($data);
           exit;
        }
        
        public function contact() {
          $data = [];
          $data['title'] = 'contact';
          $data['phone'] = '+ 91 22 26864605, +91 - 9820993343 / 9324859622 / 9820254259 /';
          $data['email'] = 'info@shrisailogistics.com';
          $data['address'] = 'Shop No.1, Ravi Estate, opp. Satguru building, Walbhat Road,Goregaon (E), Mumbai 400063';
          $data['content'] = '<p>test</p>';
          $data['image'] = 'test.png';
          
           echo json_encode($data);
           exit;
        }
		
		//CMS API End
		
		
		
		public function getCustomer()
		{
		    $data = array();
            $postdata = file_get_contents("php://input");
			$postdata = json_decode($postdata);
			$user_id = $postdata->user_id;
		
			$this->db->select('customer_id,customer_name,email,phone,city,state,address,pincode,gstno,gstno');
			$this->db->from('tbl_customers');
			$this->db->where('user_id',$user_id);
		
			$query = $this->db->get();

			if($query->num_rows() > 0)
			{
			    
				$result = $query->result_array();
			
				$data['result']="success";
				$data['data']= $result;	
			}	
			else
			{
				$data['result']="fail";
				$data['message']="Invalid Username or Password!";
			}
			echo json_encode($data);
		}
		
	public function getForwaorderList()
    {
     
        $postdata = file_get_contents("php://input");
	    $postdata = json_decode($postdata);
		$senderPincode = $postdata->senderPincode;
        $receiverPincode = $postdata->receiverPincode;
        $whr1 = array('pin_code' => $senderPincode);
        $res1 = $this->basic_operation_m->selectRecord('	pincode', $whr1);
        $result1 = $res1->row();
        
        $whr2 = array('pin_code' => $receiverPincode);
        $res2 = $this->basic_operation_m->selectRecord('	pincode', $whr1);
        $result2 = $res1->row();
		
		//print_r($result2);
		
		$whr3 = array('pin_code' => $receiverPincode);
		$res3 = $this->basic_operation_m->selectRecord('pincode', $whr3);
		$result3 = $res3->row();
		//print_r($result3);
        
        
        $forwarderList = [
            'shrisailogistics' => 'Mics Logistics',
        ];
		
        if ($result3->bluedart_surface == 1) {
            $forwarderList['bluedart_surface'] = 'Bludart Surface Service';
        }
        
        if ($result3->bluedart_air == 1) {
            $forwarderList['bluedart_air'] = 'Bludart Air Service';
        }
        
        if ($result3->fedex == 1) {
            $forwarderList['fedex_regular'] = 'Fedex Service';
        }
        
        if ($result3->spoton_service == 1) {
            $forwarderList['spoton_service'] = 'Spoton Service';
        }
		
		if ($result3->delex == 1) {
            $forwarderList['delex_cargo_india'] = 'DELEX CARGO INDIA PRIVATE LIMITED';
        }
		
		if ($result3->delhivery_c2c == 1) {
            $forwarderList['delhivery_c2c'] = 'DELHIVERY C2C';
        }
		if ($result3->delhivery_b2b == 1) {
            $forwarderList['delhivery_b2b'] = 'DELHIVERY B2B';
        }
		if ($result3->revigo == 1) {
            $forwarderList['revigo_regular'] = 'Revigo Service';
        }
        
        echo json_encode($forwarderList);
        exit;
        
    }
    
    
    
    public function getRateMasterDetails($customerId, $senderCity, $receiverCity, $modeDispatch) {
        $data = [];       
        $customer_name = $customerId;
        $sender_city = $senderCity;
        $receiver_city = $receiverCity;
        $mode_dispatch = ucfirst($modeDispatch);
        // $region_query = $this->db->query("SELECT `state`.`region_id`,`state`.`id`,`state`.`edd_train`,`state`.`edd_air`, `state`.`edd_air` FROM `state` join city ON `city`.`state_id` = `state`.`id` WHERE `city`.`id` = ".$receiver_city); 

        $region_query = $this->db->query("SELECT `tbl_state`.`region_id`,`tbl_state`.`state_id` as id,`tbl_state`.`edd_train`,`tbl_state`.`edd_air`, `tbl_state`.`edd_air` FROM `tbl_state` join city ON `city`.`state_id` = `tbl_state`.`state_id` WHERE `city`.`id` = ".$receiver_city); 
        // echo $this->db->last_query();exit();
        if ($region_query->num_rows() > 0) {
            $regionData = $region_query->row();
            $region_id = $regionData->region_id;
            $state_id = $regionData->id;
            $eod = ($mode_dispatch == 'air') ? $regionData->edd_air : $regionData->edd_air;
            $eod = $this->addBusinessDays(date("d-m-Y"),!empty($regionData->eod) ? $regionData->eod : 4);
        }
    
        if (!empty($region_id)) {
            $data['rate_master'] = new \stdClass();
            $res = $this->db->query("select * from tbl_rate_master where customer_id=".$customer_name." AND mode_of_transport='".$mode_dispatch."' AND region_id=".$region_id." LIMIT 1");
            if ($res->num_rows() > 0) {
               
                $data['rate_master'] = $res->row();
              
                // check rate available for state table
                $stateMasterRes = $this->db->query("select * from tbl_rate_state where rate_master_id=".$data['rate_master']->rate_master_id." AND state_id =".$state_id." LIMIT 1");
                if($stateMasterRes->num_rows() > 0){
                    $stateMasterData = $stateMasterRes->row();
                    $data['rate_master']->rate = $stateMasterData->rate;
                }
                
                //check rate available for city table
                $cityMasterRes = $this->db->query("select * from tbl_rate_city where rate_master_id=".$data['rate_master']->rate_master_id." AND city_id =".$receiver_city." LIMIT 1");
                if ($cityMasterRes->num_rows() > 0) {
                    $cityMasterData = $cityMasterRes->row();
                    $data['rate_master']->rate = $cityMasterData->rate;
                }
                
                if($this->input->post('no_of_pack') > 0 && $this->input->post('rate_type') == 'no_of_pack') {
                    $rate_master_id = $data['rate_master']->rate_master_id;
                    $no_of_pack = $this->input->post('no_of_pack');
                    $rate_master_query = $this->db->query("SELECT * FROM `tbl_rate_pack` WHERE rate_master_id = ".$rate_master_id." AND $no_of_pack BETWEEN `from` AND `to` LIMIT 1");
                
                if($rate_master_query->num_rows() > 0) {
                    $data['rate_master_pack'] = $rate_master_query->row();
                }
               
            }
            }
             $data['rate_master']->eod = $eod;
        }
        return $data;
        exit;
    }
    
    public function addBusinessDays($startDate, $businessDays, $holidays = []) {
        $date = strtotime($startDate);
        $i = 0;
    
        while($i < $businessDays)
        {
            //get number of week day (1-7)
            $day = date('N',$date);
            //get just Y-m-d date
            $dateYmd = date("d-m-Y H:i:s",$date);
    
            if($day < 6 && !in_array($dateYmd, $holidays)){
                $i++;
            }       
            $date = strtotime($dateYmd . ' +1 day');
        }       
    
        return date('d-m-Y H:i:s',$date);
    
    }
	
	public function get_city_by_pincode($pincode) {
		$pincode = $_GET['pincode'];
		
		
		$whr1 = array('pin_code' => $pincode);
		$res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);
	
		$city_id = $res1->row()->city_id;
		$whr2 = array('id' => $city_id);
		$res2 = $this->basic_operation_m->selectRecord('city', $whr2);
		$result2 = $res2->row();
	
		 if($result2) {
             echo json_encode([
                    'status' => 'success',
                    'city_id' => $result2->id,
                    'city_name' => $result2->city,
                    'state_id' => $result2->state_id
                ]);
            exit;
         } else {
             echo json_encode([
                 'status' => 'error',
                 'message' => 'There is info based on this pincode',
                 ]);
         
             exit;
         }
    }
	
	public function get_ratemaster_details() {
		
		$data = [];       
		$customer_name = $this->input->get('customer_id');
		$receiver_city = $this->input->get('receiver_city_id');
		$mode_dispatch = ucfirst($this->input->get('mode_dispatch'));
		// $region_query = $this->db->query("SELECT `tbl_state`.`region_id`,`tbl_state`.`state_id`,`tbl_state`.`edd_train`,`tbl_state`.`edd_air`, `tbl_state`.`edd_air` FROM `tbl_state` join tbl_city ON `tbl_city`.`state_id` = `tbl_state`.`state_id` WHERE `tbl_city`.`city_id` = ".$receiver_city);
		$region_query = $this->db->query("SELECT `state`.`region_id`,`state`.`id`,`state`.`edd_train`,`state`.`edd_air`, `state`.`edd_air` FROM `state` join city ON `city`.`state_id` = `state`.`id` WHERE `city`.`id` = ".$receiver_city); 
		
		if ($region_query->num_rows() > 0) {
			$regionData = $region_query->row();
			$region_id = $regionData->region_id;
			// $state_id = $regionData->state_id;
			$state_id = $regionData->id;
			$eod = ($mode_dispatch == 'air') ? $regionData->edd_air : $regionData->edd_air;
			$eod = $this->addBusinessDays(date("d-m-Y"),!empty($regionData->eod) ? $regionData->eod : 4);
		}

		if (!empty($region_id)) {
			$data['rate_master'] = new \stdClass();
			$res = $this->db->query("select * from tbl_rate_master where customer_id=".$customer_name." AND mode_of_transport='".$mode_dispatch."' AND region_id=".$region_id." LIMIT 1");
			// $res = $this->db->query("select * from tbl_rate_master,tbl_customers.gstno where customer_id=".$customer_name." AND mode_of_transport='".$mode_dispatch."' AND region_id=".$region_id." LIMIT 1");
			 
			if ($res->num_rows() > 0) {
			   
				$data['rate_master'] = $res->row();
			  
				// check rate available for state table
				$stateMasterRes = $this->db->query("select * from tbl_rate_state where rate_master_id=".$data['rate_master']->rate_master_id." AND state_id =".$state_id." LIMIT 1");
				if($stateMasterRes->num_rows() > 0){
					$stateMasterData = $stateMasterRes->row();
					$data['rate_master']->rate = $stateMasterData->rate;
				}
				
				//check rate available for city table
				$cityMasterRes = $this->db->query("select * from tbl_rate_city where rate_master_id=".$data['rate_master']->rate_master_id." AND city_id =".$receiver_city." LIMIT 1");
				if ($cityMasterRes->num_rows() > 0) {
					$cityMasterData = $cityMasterRes->row();
					$data['rate_master']->rate = $cityMasterData->rate;
				}
				
				if($this->input->post('no_of_pack') > 0 && $this->input->post('rate_type') == 'no_of_pack') {
					$rate_master_id = $data['rate_master']->rate_master_id;
					$no_of_pack = $this->input->post('no_of_pack');
					$rate_master_query = $this->db->query("SELECT * FROM `tbl_rate_pack` WHERE rate_master_id = ".$rate_master_id." AND $no_of_pack BETWEEN `from` AND `to` LIMIT 1");
				
				if($rate_master_query->num_rows() > 0) {
					$data['rate_master_pack'] = $rate_master_query->row();
				}
			   
			}
			}
			 $data['rate_master']->eod = $eod;
			 
		}
		//echo json_encode($data);
		
		 if($data['rate_master']) {
             echo json_encode([
                    'status' => 'success',
                    'rate_master' => $data['rate_master'],
                ]);
            exit;
         } else {
             echo json_encode([
                 'status' => 'error',
                 'message' => 'There is info rates available',
                 ]);
         
             exit;
         }
		
	}
    
    public function getDispatchDetail()
    {
        $data = [
            'cash' => 'Cash',
            'credit' => 'Credit',
            'To Pay' => 'To Pay',
            'daoc' => 'Daoc'
        ];
        
        echo json_encode($data);
        exit;
    }


    public function listShipment(){
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$user_id = $request->user_id;
		$usertype = @$request->usertype;
		

		$statusbyid = [];
		$status = $this->db->get('tbl_status')->result_array();
		foreach($status as $sttus){
			$statusbyid[$sttus['id']] = $sttus['status'];
		}

		if ($usertype!='partner') {
			$customer=  $this->basic_operation_m->get_query_result_array("SELECT * FROM tbl_customers WHERE customer_id = $user_id ORDER BY customer_name ASC");
			$filterCond = " AND tbl_domestic_booking.customer_id = '$user_id'";
		}else{
			$filterCond = " AND tbl_domestic_booking.user_id = '$user_id'";
		}

		
		// $whr = array('user_id'=>$user_id);
		// $res=$this->basic_operation_m->getAll('tbl_users',$whr);

		// print_r($customer); die;
    		
		// $user_type 					= $this->session->userdata("userType");			
		// $filterCond					= '';
		// $all_data 					= $this->input->post();

		
		$offset = 0;

		$resActt = $this->db->query("SELECT * FROM tbl_domestic_booking  WHERE booking_type = 1 $filterCond ");
		$resAct = $this->db->query("SELECT tbl_domestic_booking.*,city.city,tbl_domestic_weight_details.chargable_weight,payment_method  FROM tbl_domestic_booking LEFT JOIN city ON tbl_domestic_booking.reciever_city = city.id LEFT JOIN tbl_domestic_weight_details ON tbl_domestic_weight_details.booking_id = tbl_domestic_booking.booking_id WHERE booking_type = 1 AND company_type='Domestic' AND tbl_domestic_booking.user_type !=5 $filterCond order by tbl_domestic_booking.booking_id DESC limit ".$offset.",50");
		// $download_query 		= "SELECT tbl_domestic_booking.*,city.city,tbl_domestic_weight_details.chargable_weight,payment_method  FROM tbl_domestic_booking LEFT JOIN city ON tbl_domestic_booking.reciever_city = city.id LEFT JOIN tbl_domestic_weight_details ON tbl_domestic_weight_details.booking_id = tbl_domestic_booking.booking_id WHERE booking_type = 1 AND company_type='Domestic' AND tbl_domestic_booking.user_type !=5 $filterCond order by tbl_domestic_booking.booking_id DESC";

		// echo $this->db->last_query();

		$allpoddata = $resAct->result_array();

		// print_r($allpoddata);# die;

		$poddataarr = [];

		if (!empty($allpoddata)) {
			# code...
			
			foreach($allpoddata as $poddata){
				$status = $poddata['status'];
				// print_r($statusbyid);
				// print_r($poddata);
				$statusname = @$statusbyid[$poddata['status']];
				if(!$status){
					$statusname = 'Pending';
				}

				$mode_dispatch = 'Air';
				if($poddata['mode_dispatch'] == 2){
					$mode_dispatch = 'Train';
				}
				if($poddata['mode_dispatch'] == 3){
					$mode_dispatch = 'Surface';
				}




				$poddataarr[] = [
					'booking_id'=>$poddata['booking_id'],
					'awb_num'=>$poddata['pod_no'],
					'booking_date'=>$poddata['booking_date'],
					'current_status'=>$statusname,
					'booking_mode'=>$mode_dispatch,
					'destination'=>$poddata['reciever_address'],
					'sender_name'=>$poddata['sender_name'],
					'reciever_name'=>$poddata['reciever_name'],
					'reciever_city'=>$poddata['city'],
					'payment_method'=>$poddata['payment_method'],
					'total_payment'=>$poddata['grand_total'],
					'download'=>base_url('admin/download_pod/'.$poddata['booking_id'])
				];
			}
		}
		if ($poddataarr) {
			$resultarr['result']="success";	
			$resultarr['data']= $poddataarr;	
		}
		else{
			$resultarr['result']="fail";	
			$resultarr['message']="Shipment not found";	
		}
		echo json_encode($resultarr);
	
		die;
	}

    public function generateDeliverySheet()
    { 
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        // print_r($request); die;

        

    
        $user_id = $request['user_id'];
        $usernamee = $request['delivery_boy_username'];
        $date_time = trim($request['date_time']);
        $awb_nos = $request['awb_nos'];

        if (empty($date_time)) {
        	$date_time = date('Y-m-d H:i:s');
        }else{
        	$date_time = date('Y-m-d H:i:s',strtotime($date_time));
        }

        $whr                = array('user_id'=>$user_id);
        $res                = $this->basic_operation_m->get_table_row('tbl_users',$whr);
        $branch_id          = $res->branch_id;
        $branch_id          = $res->branch_id;

        $whr                =   array('branch_id'=>$branch_id);
        $res                =   $this->basic_operation_m->get_table_row('tbl_branch',$whr);
        $branch_name        =   $res->branch_name;


        $result1 = $this->db->query('select max(id) AS id from tbl_domestic_deliverysheet')->row();  
		$id= $result1->id+1;
		if(strlen($id)==2)
		{
		   $id='D00'.$id;
		}else if(strlen($id)==3)
		{
		   $id='D0'.$id;
		}else if(strlen($id)==1)
		{
		   $id='D000'.$id;
		}else if(strlen($id)==4)
		{
		   $id='D'.$id;
		}

        // print_r($awb_nos); die;
        // echo $this->db->database;
		$deliverysheetid = [];

        // $date=date("Y-m-d",strtotime($date_time ) );

		$pod  = array_unique($awb_nos);

		// print_r($pod);
		
		foreach ($pod as  $row) 
		{
			$row = trim($row);
			if (empty($row)) {
				# code...
				continue;
			}
			$rows = explode('|',$row);
			$pod_no = $rows[0];
			$data=array(
				'deliverysheet_id'=>$id,
				'deliveryboy_name'=>$usernamee,
				'branch_id'=>$branch_id,
				'pod_no'=>$pod_no,
				'status'=>'In-Scan',
				'delivery_date'=>$date_time,
			);
			$result=$this->basic_operation_m->insert('tbl_domestic_deliverysheet',$data);

			if ($result) {
				$deliverysheetid[] = $result;
			}			
	
			$booking_id		=	$this->basic_operation_m->get_table_row('tbl_domestic_booking',"pod_no = '$pod_no'");
			$data1=array('id'=>'',
				 'booking_id'=>$booking_id->booking_id,
				 'pod_no'=>$pod_no,
				 'status'=>'Out For Delivery',
				 'branch_name'=>$branch_name,
				 'tracking_date'=>$date_time,
		  	);
						
			$result1	=	$this->basic_operation_m->insert('tbl_domestic_tracking',$data1);

			// $query = $this->db->query("update users SET password='$request->password' where user_id='$request->user_id'");

			// if($this->db->affected_rows()>0)
			// {
			// 	$data['result']="success";

			// //$data['noti']=$res->result_array();	                
			// }else{
			// 	$data['result']="fail";

			// }
			
		}
        
        
        
        

        if ($deliverysheetid) {
            $resultarr['result']="success"; 
            $resultarr['data']['sheet_numbers']= $deliverysheetid;  
        }
        else{
            $resultarr['result']="fail";    
            $resultarr['message']="Something is went wrong";    
        }
        echo json_encode($resultarr);
    }

    public function listDeliverySheet()
    { 
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        // print_r($request); die;
        $delivery_boy_username = @$request['delivery_boy_username'];
        $username = $request['user_name'];
        
        $whr = array('username'=>$username);
        $res=$this->basic_operation_m->getAll('tbl_users',$whr);
        $branch_id= $res->row()->branch_id;
        $user_type= $res->row()->user_type;

        $where = "";
        switch ($user_type) {
        	case '1':
        		$where = "tbl_domestic_deliverysheet.branch_id=".$branch_id;
        		break;

        	case '2':
        		$where = "deliveryboy_name='".$username."'";
        		break;

        	case '3':
        		$where = "tbl_domestic_deliverysheet.branch_id=".$branch_id;
        		break;
        	
        	default:
        		# code...
        		break;
        }

        if (!empty($where)) {
        	$where =" WHERE ".$where;
        }

        // echo "<pre>";
        // print_r($res);exit();

        $resAct1=$this->db->query("SELECT *, COUNT(deliverysheet_id) AS total_count
                FROM tbl_domestic_deliverysheet
                LEFT JOIN tbl_branch ON tbl_branch.branch_id = tbl_domestic_deliverysheet.branch_id
                LEFT JOIN tbl_users ON tbl_users.username = tbl_domestic_deliverysheet.deliveryboy_name ".$where."
                GROUP BY deliverysheet_id");


        // echo $this->db->last_query();exit();
            
        if($resAct1->num_rows()>0)
        {
            $data['allpod']=$resAct1->result_array();               
        }

        if ($data['allpod']) {
            $resultarr['result']="success"; 
            $resultarr['data']['sheets']= $data['allpod'];  
        }
        else{
            $resultarr['result']="fail";    
            $resultarr['message']="No sheets found";    
        }
        echo json_encode($resultarr);
    }

    public function deliverySheetDetails()
    { 
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);
        // print_r($request); die;
        $delivery_boy_username = $request['delivery_boy_username'];
        $delivery_sheet_id = $request['delivery_sheet_id'];
        
        $whr = array('username'=>$delivery_boy_username);

        // print_r($whr);
        $res=$this->basic_operation_m->getAll('tbl_users',$whr);
        $branch_id= $res->row()->branch_id;

        $resAct1=$this->db->query("SELECT tbl_domestic_deliverysheet.*,tbl_branch.*,tbl_users.*,tbl_domestic_booking.sender_name as consigner,tbl_domestic_booking.reciever_name as consignee,tbl_domestic_booking.reciever_address as consignee_address,method as payment_method
                FROM tbl_domestic_deliverysheet
                LEFT JOIN tbl_branch ON tbl_branch.branch_id = tbl_domestic_deliverysheet.branch_id
                INNER JOIN tbl_domestic_booking ON tbl_domestic_booking.pod_no = tbl_domestic_deliverysheet.pod_no
                LEFT JOIN payment_method ON tbl_domestic_booking.payment_method=payment_method.id
                LEFT JOIN tbl_users ON tbl_users.username = tbl_domestic_deliverysheet.deliveryboy_name WHERE deliverysheet_id = '$delivery_sheet_id'
                ");

        // echo $this->db->last_query();
            
        if($resAct1->num_rows()>0)
        {
            $data['allpod']=$resAct1->result_array();              
        }

        if ($data['allpod']) {
            $resultarr['result']="success"; 
            $resultarr['data']['sheet_details']= $data['allpod'];   
        }
        else{
            $resultarr['result']="fail";    
            $resultarr['message']="No sheets found";    
        }
        echo json_encode($resultarr);
    }

    public function listAllDiliveryboy()
	{ 

		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata, true);
		// print_r($request); die;
		$user_id = $request['user_id'];
		// $user_id = 1;
		
		$whr = array('user_id'=>$user_id);
		$res=$this->basic_operation_m->getAll('tbl_users',$whr);
		// echo $this->db->last_query();
		$branch_id= @$res->row()->branch_id;

		$resAct1=$this->db->query("SELECT * FROM tbl_users WHERE user_type = 2 AND branch_id = $branch_id");
		// echo "<pre>";
		// print_r($res->row());
		// print_r($res);exit();
		// echo $this->db->last_query();


		if($resAct1->num_rows()>0)
		{
			$data['alldeliveryboys']=$resAct1->result_array();	            
		}

		if ($data['alldeliveryboys']) {
			$resultarr['result']="success";	
			$resultarr['data']['alldeliveryboys']= $data['alldeliveryboys'];	
		}
		else{
			$resultarr['result']="fail";	
			$resultarr['message']="No Delivery boy found";	
		}
		echo json_encode($resultarr);
	}

	public function manefistList()
	{ 

		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata, true);
		// print_r($request); die;
		$user_id = $request['user_id'];
		// $user_id = 1;
		
		$whr = array('user_id'=>$user_id);
		$res=$this->basic_operation_m->getAll('tbl_users',$whr);
		// echo $this->db->last_query();
		$branch_id= @$res->row()->branch_id;

		$resAct1=$this->db->query("SELECT * FROM tbl_users WHERE user_type = 2 AND branch_id = $branch_id");
		// echo "<pre>";


		$whr = array('branch_id'=>$branch_id);
		 
		$res_branch=$this->basic_operation_m->getAll('tbl_branch',$whr);


		$branch_name= $res_branch->row()->branch_name;
		 $data= array();

		$resAct=$this->db->query("select *,sum(total_pcs) as total_pcs,sum(total_weight) as total_weight,mode_name from tbl_domestic_menifiest JOIN transfer_mode ON transfer_mode.transfer_mode_id=tbl_domestic_menifiest.forwarder_mode where tbl_domestic_menifiest.source_branch='$branch_name' group by manifiest_id order by manifiest_id desc");

		 if($resAct->num_rows()>0)
		 {
			$data['allpod']=$resAct->result();	            
		 }


		

		if ($data['allpod']) {
			$resultarr['result']="success";	
			$resultarr['data']['allpod']= $data['allpod'];	
		}
		else{
			$resultarr['result']="fail";	
			$resultarr['message']="No manifiest list found";	
		}
		echo json_encode($resultarr);
	}


	public function manefistDetails()
	{ 

		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata, true);
		// print_r($request); die;
		$user_id = $request['user_id'];
		$manifiest_id = $request['manifiest_id'];
		// $user_id = 1;
		
		$whr = array('user_id'=>$user_id);
		$res=$this->basic_operation_m->getAll('tbl_users',$whr);
		// echo $this->db->last_query();
		$branch_id= @$res->row()->branch_id;

		$resAct1=$this->db->query("SELECT * FROM tbl_users WHERE user_type = 2 AND branch_id = $branch_id");
		// echo "<pre>";


		$whr = array('branch_id'=>$branch_id);
		$res_branch=$this->basic_operation_m->getAll('tbl_branch',$whr);
		$branch_name= $res_branch->row()->branch_name;

		$data= array();

		$resAct=$this->db->query("select *,sum(total_pcs) as total_pcs,sum(total_weight) as total_weight from tbl_domestic_menifiest where tbl_domestic_menifiest.source_branch='$branch_name' AND manifiest_id='$manifiest_id' group by manifiest_id order by manifiest_id desc");

		// echo $this->db->last_query();exit();
		
		 if($resAct->num_rows()>0)
		 {
			$data['allpod']=$resAct->result();	            
		 }


		

		if (@$data['allpod']) {
			$resultarr['result']="success";	
			$resultarr['data']['allpod']= $data['allpod'];	
		}
		else{
			$resultarr['result']="fail";	
			$resultarr['message']="No manifiest list found";	
		}
		echo json_encode($resultarr);
	}


	public function menuMaster(){

		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata, true);
		// print_r($request); die;
		$user_id = $request['user_id'];
		$usertype = $request['usertype'];


		$whr = array('user_type'=>$usertype);
		$res=$this->basic_operation_m->getAll('tbl_users',$whr);


		if ($usertype=='staff') {
			# code...
			$resAct=$this->db->query("select menu_master.* from menu_master JOIN user_menu on user_menu.menu_id=menu_master.menu_id")->result_array();
		}else{
			$resAct=$this->db->query("select menu_master.* from menu_master JOIN user_menu on user_menu.menu_id=menu_master.menu_id where user_menu.userType='$usertype'")->result_array();
		}

		

		// echo $this->db->last_query();



		$array = array(
			'Upload POD',
			'Update Shipment',
			'List Shipment',
			'List Branch Shipment',
			'Pending Branch Shipment',
			'Delivered Branch Shipment',
			'Upcoming Shipment',
			'Incoming Shipment',
			'Outgoing Shipment',
			'Print POD',
			'Create DRS',
			'DRS',
			'Delhivey Sheet',
		);

		$arr2 = array(
			'Staff',
			'Delhivey Boy',
			'List Shipment',
			'Customer',
			
		);

		$final = array(
			'menus' => $resAct,
			'usertype' => $usertype,
		);



		echo json_encode(array('status'=>true,'message'=>'Menu List','data'=>$final));
	}


	public function awbnodata()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata, true);
		// print_r($request); die;
		$user_id = $request['user_id'];
		$awb_no = $request['pod_no'];


		$pod_no 		= trim($awb_no);
		
		
        $where = '';
        
		$whr 				= array('user_id'=>$user_id);
		$res				= $this->basic_operation_m->get_table_row('tbl_users',$whr);
		$branch_id			= $res->branch_id;
	
		$whr 				= 	array('branch_id'=>$branch_id);
		$res				=	$this->basic_operation_m->get_table_row('tbl_branch',$whr);
		$branch_name		= 	$res->branch_name;
		
		// $where = "and menifiest_branches not like '%$branch_id%' and menifiest_recived ='0' "; 		
		$resAct5 = $this->db->query("SELECT * FROM tbl_domestic_booking where tbl_domestic_booking.pod_no='$pod_no' and is_delhivery_complete = '0'  $where limit 1");
		$data = array();
	
  	 	if ($resAct5->num_rows() > 0) 
	 	{
	 		$reAct=$this->db->query("select * from tbl_domestic_tracking where status='Out For Delivery' AND pod_no = '$pod_no' ORDER BY id DESC");
	 		$dd = $reAct->row();

	 		if (!empty($dd)) {
	 			# code...
	 		}else{
	 			$data = $resAct5->row_array();
	 		}
	 		
	 	}

	 	echo json_encode($data);
	}


	public function write_request_file(){
		
        $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");

        $postdata = file_get_contents("php://input");
        $posr_txt = json_encode($_POST);
        $get_txt = json_encode($_GET);
        $files_txt = json_encode($_FILES);

        $datec = date('Y-m-d H:i:s');

        $method = $this->router->fetch_method();
        
        $txt = "\n-----------------------------------Time :  ".$datec." : ".$method."-----------------------------------------------\n";
        $txt .= "\n-----------------------------------INPUT JSON-----------------------------------------------\n";
        $txt .= $postdata;
        $txt .= "\n-----------------------------------POST-----------------------------------------------\n";
        $txt .= $posr_txt;
        $txt .= "\n-----------------------------------GET-----------------------------------------------\n";
        $txt .= $get_txt;
        $txt .= "\n-----------------------------------FILES-----------------------------------------------\n";
        $txt .= $files_txt;

        // fwrite($myfile, $txt);
        fwrite($myfile, $txt);
        fclose($myfile);
    }


    public function reset_manifest(){

    	$postdata = file_get_contents("php://input");
	 	$request = json_decode($postdata);

	 	$resAct1=$this->db->query("UPDATE  `tbl_domestic_booking` set menifiest_recived=0 where pod_no='".$request->pod_no."'");
	 	// -- $resAct1=$this->db->query("DELETE  FROM `tbl_domestic_menifiest` where pod_no='".$request->pod_no."'");


    	// UPDATE  `tbl_domestic_booking` set menifiest_recived=0 where pod_no='awb'
    }

    public function dgdgd(){

    	$this->write_request_file();
    	echo "<br>";
    	echo $dd = date('Y-m-d H:i:s');
    }

    public function save_base64_image($base64_image_string, $output_file_without_extension, $path_with_end_slash="" ) {
	    //usage:  if( substr( $img_src, 0, 5 ) === "data:" ) {  $filename=save_base64_image($base64_image_string, $output_file_without_extentnion, getcwd() . "/application/assets/pins/$user_id/"); }      
	    //
	    //data is like:    data:image/png;base64,asdfasdfasdf
	    $splited = explode(',', substr( $base64_image_string , 5 ) , 2);
	    $mime=$splited[0];
	    $data=$splited[1];

	    $mime_split_without_base64=explode(';', $mime,2);
	    $mime_split=explode('/', $mime_split_without_base64[0],2);
	    if(count($mime_split)==2)
	    {
	        $extension=$mime_split[1];
	        if($extension=='jpeg')$extension='jpg';
	        if($extension=='PNG')$extension='png';
	        //if($extension=='text')$extension='txt';
	        $output_file_with_extension=$output_file_without_extension.'.'.$extension;
	    }
	    file_put_contents( $path_with_end_slash . $output_file_with_extension, base64_decode($data) );
	    return $output_file_with_extension;
	}

	public function awbnodata_for_drs_generate()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata, true);
		// print_r($request); die;
		$user_id = $request['user_id'];
		$awb_no = $request['pod_no'];


		$pod_no 		= trim($awb_no);
		
		
        $where = '';
        
		$whr 				= array('user_id'=>$user_id);
		$res				= $this->basic_operation_m->get_table_row('tbl_users',$whr);
		$branch_id			= $res->branch_id;
	
		$whr 				= 	array('branch_id'=>$branch_id);
		$res				=	$this->basic_operation_m->get_table_row('tbl_branch',$whr);
		$branch_name		= 	$res->branch_name;
		
		// $where = "and menifiest_branches not like '%$branch_id%' and menifiest_recived ='0' "; 		
		$resAct5 = $this->db->query("SELECT * FROM tbl_domestic_booking where tbl_domestic_booking.pod_no='$pod_no' and is_delhivery_complete = '0'  $where limit 1");
		$data = array();
	
  	 	if ($resAct5->num_rows() > 0) 
	 	{
	 		$reAct=$this->db->query("select * from tbl_domestic_tracking where status='Out For Delivery' AND pod_no = '$pod_no' ORDER BY id DESC");
	 		$dd = $reAct->row();

	 		if (!empty($dd)) {
	 			# code...
	 		}else{
	 			$reAct=$this->db->query("select * from tbl_domestic_menifiest where destination_branch='$branch_name' AND pod_no = '$pod_no' AND reciving_status=1 ORDER BY id DESC");

	 			$dd1 = $reAct->row();

	 			if (empty($dd1)) {
	 				# code...
	 			}else{
	 				$data = $resAct5->row_array();
	 			}
	 			
	 			
	 		}
	 		
	 	}

	 	echo json_encode($data);
	}

	public function awbnodata_for_menifest_generate()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata, true);
		// print_r($request); die;
		$user_id = $request['user_id'];
		$awb_no = $request['pod_no'];


		$pod_no 		= trim($awb_no);
		
		
        $where = '';
        
		$whr 				= array('user_id'=>$user_id);
		$res				= $this->basic_operation_m->get_table_row('tbl_users',$whr);
		$branch_id			= $res->branch_id;
	
		$whr 				= 	array('branch_id'=>$branch_id);
		$res				=	$this->basic_operation_m->get_table_row('tbl_branch',$whr);
		$branch_name		= 	$res->branch_name;
		
		// $where = "and menifiest_branches not like '%$branch_id%' and menifiest_recived ='0' "; 		
		$resAct5 = $this->db->query("SELECT * FROM tbl_domestic_booking where tbl_domestic_booking.pod_no='$pod_no' and is_delhivery_complete = '0'  $where limit 1");
		$data = array();
	
  	 	if ($resAct5->num_rows() > 0) 
	 	{
	 		$reAct=$this->db->query("select * from tbl_domestic_tracking where status='Out For Delivery' AND pod_no = '$pod_no' ORDER BY id DESC");
	 		$dd = $reAct->row();

	 		if (!empty($dd)) {
	 			# code...
	 		}else{
	 			$reAct=$this->db->query("select * from tbl_domestic_menifiest where destination_branch='$branch_name' AND pod_no = '$pod_no' ORDER BY id DESC");

	 			$dd1 = $reAct->row();

	 			if (!empty($dd)) {
	 				if ($dd1->reciving_status==1) {
	 					$data = $resAct5->row_array();
	 				}
	 			}else{
	 				$data = $resAct5->row_array();
	 			}
	 			
	 			
	 		}
	 		
	 	}

	 	echo json_encode($data);
	}
}
	?>