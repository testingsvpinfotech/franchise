<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
class Admin_international_booking extends CI_Controller {

	function __construct() {
		parent:: __construct();
		$this->load->model('basic_operation_m');
		$this->load->model('booking_model');	
		$this->load->model('Invoice_model');
		
		if($this->session->userdata('userId') == '')
		{
			redirect('admin');
		}	
	}

	public function index() {
	
			$this->load->helper('form_helper');

			$data['getAllInvoices'] =array();
			$data['export_import_type']="";
			$data['customer_account_id'] = '';
			$data['company_id'] = '';
			if (isset($_POST['submit'])) 
			{
				$fromDate = date("Y-m-d",strtotime($this->input->post('from')));
				$toDate = date("Y-m-d",strtotime($this->input->post('to')));
				$data['customer_account_id'] = $this->input->post('customer_account_id');
				$data['company_id'] = $this->input->post('company_id');
				
				//$export_import_type = $this->input->post('export_import_type');
				
				$where = array('customer_id'=>$this->input->post('customer_account_id'),
					'invoice_generated_status'=>0,
					'booking_date >='=>$fromDate,
					'booking_date <='=>$toDate,
					'export_import_type'=>'Export',
				);
				
				$data['getAllInvoices'] = $this->booking_model->get_international_invoice_details($where);
				
				
			}
			 //if ($this->input->post('submit') == 'print') {
			if (isset($_POST['submit_print'])) 
			{
				$customer_id = $this->input->post('customer_id');
				$export_import_type = 'Export';

				$whr = array('customer_id'=>$customer_id);
				$data['customer_details'] = $this->basic_operation_m->get_table_row('tbl_customers',$whr);
				
				$whr_c = array('id'=>$data['customer_details']->city) ;
				$city_data = $this->basic_operation_m->get_table_row('city',$whr_c);

				//echo "<pre>";print_r($data['customer_details']);
				$fromDate = $this->input->post('from');
				$toDate = $this->input->post('to');
				
				 $max_number= $this->basic_operation_m->get_max_number('tbl_international_invoice','MAX(inc_num) AS id');
				 if(isset($max_number)){$inc_num=(($max_number->id )+1); }else{$inc_num=1;}
				 
				 $where =array('id'=>1);
				 $data['company_details'] = $this->basic_operation_m->get_table_row('tbl_company',$where);
				 $invoice_series = $data['company_details']->international_invoice_series;

				$invoice['invoice_number'] = $invoice_series."/".date('y')."-".(date('y')+1)."/".$inc_num;
				$invoice['performa_invoice_number']= "omcsPerforma_".$inc_num;
				$invoice['inc_num']  = $inc_num;
				$invoice['invoice_date'] = date("Y-m-d");
				$invoice['company_id'] = $this->input->post('company_id');
			   
				$invoice['customer_name'] = $data['customer_details']->customer_name;
				$invoice['address'] = $data['customer_details']->address;					  
				$invoice['city'] = $city_data->city;
				$invoice['gstno'] = $data['customer_details']->gstno;
				$invoice['cid'] = $data['customer_details']->cid;
				$invoice['customer_id'] = $data['customer_details']->customer_id;
								
				$invoice['export_import_type']=$export_import_type;
				$invoice['invoice_from_date'] = date('Y-m-d', strtotime($fromDate));
				$invoice['invoice_to_date'] = date('Y-m-d', strtotime($toDate));
				$invoice['booking_ids'] = json_encode($this->input->post('selected_dockets') );
				 $this->db->insert('tbl_international_invoice', $invoice);
				 $invoice_id = $this->db->insert_id();

				 $selected_dockets = $this->input->post('selected_dockets');

				 $whr1 = array('tbl_international_booking.customer_id'=>$customer_id);
				//echo "<pre>"; print_r($selected_dockets);
				 $data['allpoddata']= $this->booking_model->get_all_pod_data($whr1,$selected_dockets);
	
				$total_cgst = 0;
				$total_sgst = 0;
				$total_igst = 0;
				$total_sgst = 0;
				$total_cgst = 0;
				$amount = 0;
				$totalAmount = 0;
				$fuel_charges = 0;
				$sub_total =0;
				$grand_total=0;

				 foreach ($data['allpoddata'] as $pod)
				 {
						//echo "<pre>";    print_r($pod);    
						 $whr_c = array('z_id'=>$pod['reciever_country_id']) ;
						 $rec_country = $this->basic_operation_m->get_table_row('zone_master',$whr_c);      
					   
					   $d_update =array('invoice_generated_status'=>'1');
					   $d_whr=array('booking_id'=>$pod['booking_id']);
					   $this->basic_operation_m->update('tbl_international_booking',$d_update,$d_whr);

						 $invoice_detail['invoice_id']  = $invoice_id;
						 $invoice_detail['booking_id']  = $pod['booking_id'];

						$invoice_detail['booking_date']  = $pod['booking_date'];
					   // $invoice_detail['delivery_date']  = $pod['delivery_date'];
						$invoice_detail['pod_no']  = $pod['pod_no'];
						$invoice_detail['doc_type']  = $pod['doc_type'];
						$invoice_detail['doc_type']  = $pod['doc_type'];		                    
					
						$invoice_detail['reciever_name']  = $pod['reciever_name']; 
						$invoice_detail['reciever_country']  = $rec_country->country_name;
						
						$invoice_detail['mode_dispatch']     = $pod['mode_dispatch'];
						$invoice_detail['forwording_no']     = $pod['forwording_no'];
						 $invoice_detail['forworder_name']   = $pod['forworder_name'];
						$invoice_detail['no_of_pack'] 	     = $pod['no_of_pack'];		                   
						$invoice_detail['chargable_weight']  = $pod['chargable_weight'];
						$invoice_detail['transportation_charges']  = !empty($pod['transportation_charges']) ? $pod['transportation_charges'] : 0;
						$invoice_detail['destination_charges']  = $pod['destination_charges'];
						$invoice_detail['clearance_charges']  = $pod['clearance_charges'];
						$invoice_detail['ecs']  			= $pod['ecs'];
						$invoice_detail['other_charges']  	= $pod['other_charges'];
						$invoice_detail['frieht']  			= $pod['frieht'];
						$invoice_detail['amount']  			= $pod['total_amount'];
						$invoice_detail['fuel_subcharges']  = $pod['fuel_subcharges'];
						$invoice_detail['invoice_value']  	= $pod['invoice_value'];
						$invoice_detail['sub_total']		= $pod['sub_total'];

					   // echo "<pre>";print_r($invoice_detail);
						$this->db->insert('tbl_international_invoice_detail', $invoice_detail);
						//echo $this->db->last_query();
						//===========================================

						$fuel_charges = $fuel_charges + $pod['fuel_subcharges'];                 
						$totalAmount = $totalAmount + $pod['total_amount'];
						$sub_total = $sub_total + $pod['sub_total'];
						$grand_total = $grand_total + $pod['grand_total'];

						$branch_id = $pod['branch_id'];							
						$total_igst = $total_igst + $pod['igst'];
						$total_sgst = $total_sgst + $pod['sgst'];;
						$total_cgst = $total_cgst + $pod['cgst'];;						
					}	
					$invoiceData['branch_id'] = $branch_id;
					$invoiceData['sgst_amount'] = $total_sgst;
					$invoiceData['cgst_amount'] = $total_cgst;
					$invoiceData['igst_amount'] = $total_igst;
					  
				   $invoiceData['total_amount'] =$totalAmount;
				   $invoiceData['sub_total']=$sub_total;
				   $invoiceData['grand_total']=$grand_total;
					
				   $invoiceData['fuel_subcharges'] = $fuel_charges;	
				   $this->db->where('id', $invoice_id);
				   $this->db->update('tbl_international_invoice', $invoiceData);
				   unset($invoiceData);

				redirect("admin/list-invoice");

			}
	
		$data['customers']  = $this->basic_operation_m->get_query_result_array('SELECT * FROM tbl_customers WHERE 1 ORDER BY customer_name ASC');
		$data['company_list']  = $this->basic_operation_m->get_query_result_array('SELECT * FROM tbl_company WHERE 1 ORDER BY company_name ASC');
		$this->load->view('admin/booking_master/booking_invoice', $data);
	}

	//===========================================================================
	public function getCustomer()
	{
		$company_id = $this->input->post('company_id');
		$whr = array('company_id' => $company_id);
		$customer_details = $this->basic_operation_m->get_all_result('tbl_customers', $whr);
		$data['customer_details'] = $customer_details;
		echo json_encode($data);
	}
	public function get_city($city) {
        $resAct = $this->db->query("select * from city where id = {$city}");
		return $resAct->row()->city;
	}

	public function save() {
		$invoice = $this->input->post();
		$total = 0;
		foreach($invoice['list'] as $lv){
			$total = $total + $lv['total_amount'];
		}
        $gstCharges_query = $this->db->query("select * from setting");
    	$gstCharges = $gstCharges_query->result_array();
    	$invoice['total'] = $total;
		$cgst =  round(($total * $gstCharges[0]['value']) / 100);
		$sgst =  round(($total * $gstCharges[2]['value']) / 100);
		$grand_total = $total + $sgst + $cgst;
		$invoice['grand_total'] = $grand_total;
		$invoice['cgst_amount'] = $cgst;
		$invoice['sgst_amount'] = $sgst;

		unset($invoice['list']);
		if ($this->session->userdata("userType") != '1') {
			$username = $this->session->userdata("userName");
			$whr = array('username' => $username);
			$res = $this->basic_operation_m->getAll('tbl_users', $whr);
			$invoice['branch_id'] = $res->row()->branch_id;
		}
		$this->db->insert('tbl_invoice', $invoice);
		$invoice_id = $this->db->insert_id();

        //check minimu weight
		$customer_id = $this->input->post('customer_id');


		if ($invoice_id) {
			$list = $this->input->post('list');

			foreach ($list as $l) {
				$l['invoice_id'] = $invoice_id;

				$city = $l['reciever_city'];
				if($city){
                    //get  state id 
					$where3 = array('city_name' => $city);
					$this->db->select('*');				
				    $this->db->from('city as city');				
				    $this->db->where('city.id', $city);				
				    $this->db->join('state as state', 'city.state_id = state.id', 'LEFT');
					$this->db->join('region_master as region', 'state.region_id = region.region_id', 'LEFT');
					$query = $this->db->get();
					$state_arrs = $query->row();

					if($state_arrs){
						$region_id = $state_arrs->region_id;

						$whr2 = array('customer_id' => $customer_id, 'mode_of_transport'=> $l['mode_dispatch'], 'region_id'=>$region_id);
						$res_rate = $this->basic_operation_m->selectRecord('tbl_rate_master', $whr2);
						if ($res_rate->num_rows() > 0) {
							$rate_arr = $res_rate->result_array();

							$price = array();
							foreach ($rate_arr as $key => $row)
							{
								$price[$key] = $row['weight_range'];
							}
							array_multisort($price, SORT_ASC, $rate_arr);

							$one_cft_kg =  $l['one_cft_kg'];
							$rate_fuel_subcharges = '';
							$dod_doac_charges = '';
							$rate = '';
							$loading_unloading_charges = '';
							$packing_charges = '';
							$handling_charges = '';
							$insurance_charges = '';

							foreach ($rate_arr as $key => $rate_arr_val) {
								
                                //$one_cft_kg = !empty($rate_arr_val['cft']) ? $rate_arr_val['cft'] : $one_cft_kg;
                                $valumetric_weight = !empty($rate_arr_val['cft']) ? $l['valumetric_weight'] * $rate_arr_val['cft'] : $l['valumetric_weight'];
                                $one_cft_kg = $l['one_cft_kg'] = max($l['actual_weight'], $valumetric_weight);
                                if($rate_arr_val['weight_range'] >= $one_cft_kg){ //
                                	$weight_range = $rate_arr_val['weight_range'];
                                	$rate = $rate_arr_val['rate'];
                                	$rate_fuel_subcharges = $rate_arr_val['fuel_charges'];
                                	$dod_doac_charges = $rate_arr_val['dod_doac'];
                                	$loading_unloading_charges = $rate_arr_val['loading_unloading'];
                                	$packing_charges = $rate_arr_val['packing'];
                                	$handling_charges = $rate_arr_val['handling'];
                                	$insurance_charges = $rate_arr_val['insurance'];
                                	break;
                                }else if($rate_arr_val['weight_range'] <= $one_cft_kg){
                                	$rate = $rate_arr_val['rate'];
                                	$weight_range = $rate_arr_val['weight_range'];
                                	$rate_fuel_subcharges = $rate_arr_val['fuel_charges'];
                                	$dod_doac_charges = $rate_arr_val['dod_doac'];
                                	$loading_unloading_charges = $rate_arr_val['loading_unloading'];
                                	$packing_charges = $rate_arr_val['packing'];
                                	$handling_charges = $rate_arr_val['handling'];
                                	$insurance_charges = $rate_arr_val['insurance'];
                                }
                                
                            }
                            if($weight_range >= $one_cft_kg){
                                $one_cft_kg = $weight_range;
                            }
                            $l['one_cft_kg'] = $one_cft_kg;
                            $l['chargable_weight'] = $rate;

                            $fuel_subcharges = $l['fuel_subcharges'] = !empty($rate_fuel_subcharges) ? $rate_fuel_subcharges : $l['fuel_subcharges'];
                            $dod_doac_charges = $l['dod_doac'] = !empty($dod_doac_charges) ? $dod_doac_charges : $l['dod_doac'];
                            $loading_unloading_charges = $l['loading_unloading'] = !empty($loading_unloading_charges) ? $loading_unloading_charges : $l['loading_unloading'];
                            $packing_charges = $l['packing'] = !empty($packing_charges) ? $packing_charges : $l['packing'];
                            $handling_charges = $l['handling'] = !empty($handling_charges) ? $handling_charges : $l['handling'];
                            $insurance_charges = $l['insurance'] = !empty($insurance_charges) ? $insurance_charges : $l['insurance'];
                        }

                    }
                    
                }
                $one_cft_kg = $l['one_cft_kg'];
                $chargable_weight = $l['chargable_weight'];
                if($l['fuel_subcharges'] == '' || $l['fuel_subcharges'] == '-'){
                	$l['fuel_subcharges'] = 0;
                	$fuel_subcharges = 0;
                }

                if($l['dod_doac'] == '' || $l['dod_doac'] == '-'){
                	$l['dod_doac'] = 0;
                	$dod_doac_charges = 0;
                }

                if($l['loading_unloading'] == '' || $l['loading_unloading'] == '-'){
                	$l['loading_unloading'] = 0;
                	$loading_unloading_charges = 0;
                }

                if($l['packing'] == '' || $l['packing'] == '-'){
                	$l['packing'] = 0;
                	$packing_charges = 0;
                }

                if($l['handling'] == '' || $l['handling'] == '-'){
                	$l['handling'] = 0;
                	$handling_charges = 0;
                }

                if($l['insurance'] == '' || $l['insurance'] == '-'){
                	$l['insurance'] = 0;
                	$insurance_charges = 0;
                }

                $pick_charges = $l['pick_charges'];
                if($pick_charges == '' || $pick_charges == '-'){
                	$pick_charges = 0;
                	$l['pick_charges'] = 0;
                }
                $oda_charges = $l['oda_charges'];
                if($oda_charges == '' || $oda_charges =='-'){
                	$oda_charges = 0;
                	$l['oda_charges'] = 0;
                }
                

                $to_pay = $one_cft_kg * $chargable_weight;
                $l['to_pay'] = $to_pay;
                $l['fuel_subcharges'] = $fuel_subcharges = ($to_pay * $fuel_subcharges)/100;
                $total_amount = $to_pay + $fuel_subcharges + $pick_charges + $oda_charges + $dod_doac_charges + $loading_unloading_charges + $packing_charges + $handling_charges + $insurance_charges;
                $l['total_amount'] = $total_amount;

                //$l['to_pay'] = $l['one_cft_kg'] * $l['chargable_weight'];
                //$l['total_amount'] = $l['to_pay'];
                unset($l['actual_weight'],$l['valumetric_weight']);
                $this->db->insert('tbl_invoice_detail', $l);
            }
        }
        redirect('booking/invoice');
    }

    public function invoice_edit($id,$customer_id) 
	{
    	$data['id'] 		= $id;
		$data['customer'] 	= $this->basic_operation_m->get_table_row('tbl_international_invoice',array('id'=>$id));
		$data['allpoddata'] = $this->basic_operation_m->get_all_result('tbl_international_invoice_detail',array('invoice_id'=>$id));
		$data['gstCharges'] = $this->basic_operation_m->get_query_row('select * from tbl_gst_setting order by id desc limit 1');
		$this->load->view('admin/booking_master/edit_invoice', $data);
    }
    public function final_invoice_edit($id,$customer_id) 
	{
		$data['id'] 		= $id;
		$data['customer'] 	= $this->basic_operation_m->get_table_row('tbl_international_invoice',array('id'=>$id));
		$data['allpoddata'] = $this->basic_operation_m->get_all_result('tbl_international_invoice_detail',array('invoice_id'=>$id));
		$data['gstCharges'] = $this->basic_operation_m->get_query_row('select * from tbl_gst_setting order by id desc limit 1');
		$this->load->view('admin/booking_master/edit_invoice', $data);	
    }
	
    public function invoice_view($id,$company_id) 
	{
    	$data['id'] 	= $id;
    	$in 				= $this->db->query("select * from tbl_international_invoice where id = ". $id." ORDER BY inc_num DESC  ");
    	$data['customer'] 	= $in->row();
    	$in_de 				= $this->db->query("select * from tbl_international_invoice_detail where invoice_id = ". $id." Order by booking_date ASC" );
    	$data['allpoddata'] = $in_de->result_array();
    	$data['gstCharges'] = $this->basic_operation_m->get_query_row('select * from tbl_gst_setting order by id desc limit 1');
		$data['company_details'] = $this->basic_operation_m->get_table_row('tbl_company',array('id'=>$company_id));

    	$this->load->view('admin/booking_master/billing_invoice_view', $data);
    }

	public function pdf($id,$company_id) {
    	$data['id'] = $id;
    	$in = $this->db->query("select * from tbl_international_invoice where id = ". $id."  ");
    	$data['customer'] = $in->row();
    	$inc_num =$data['customer']->inc_num;
    	
    	$in_de = $this->db->query("select * from tbl_international_invoice_detail where invoice_id = ". $id." Order by booking_date ASC" );
    	$data['allpoddata'] = $in_de->result_array();
    	$data['gstCharges'] = $this->basic_operation_m->get_query_row('select * from tbl_gst_setting order by id desc limit 1');

    	$where =array('id'=>$company_id);
		$data['company_details'] = $this->basic_operation_m->get_table_row('tbl_company',$where);

		$invoice_series = $data['company_details']->international_invoice_series;
		$data['invoice_term_condition'] = $data['company_details']->invoice_term_condition;

    	$html = $this->load->view('admin/booking_master/booking_pdf', $data, true);
	
		// print_r($html); 
	
		$this->load->library('M_pdf');
        
        $this->m_pdf->pdf->setAutoTopMargin = 'stretch';
        $this->m_pdf->pdf->autoMarginPadding = 'pad';
        $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';

		// $this->m_pdf->pdf->SetHTMLFooter('<div style="text-align: right">Page {PAGENO} out of {nbpg}</div>');
	    $this->m_pdf->pdf->WriteHTML($html);
	    
	    $this->m_pdf->pdf->defaultheaderfontsize=14;
        $this->m_pdf->pdf->defaultheaderfontstyle='B';
        $this->m_pdf->pdf->defaultheaderline=1;
	 
        $this->mpdf->showImageErrors = true;
        $this->mpdf->debug = true;
        
		$type           = 'I';
		if($inc_num >= 100)
		{
            $filename = $invoice_series.'_'.$inc_num.'.pdf';
		}else{
		    $filename = $invoice_series.'_'.$id.'.pdf';
		}
        
		$filename = str_replace('/','_',$filename);
		$savefolderpath = 'assets/invoice/international/';
		
        $this->m_pdf->pdf->Output($savefolderpath.$filename, $type);

		// echo $html;
    }
	
	public function get_invoiceNumber($invoicePrefix){
		
		$invoiceNum = $invoicePrefix;
		
		$in = $this->db->query("select invoice_prefix from tbl_international_invoice where invoice_prefix = '$invoicePrefix'");
        $invoiceData = $in->result_array();
		
		foreach($invoiceData as $row)
		{
			$data[] = $row['invoice_prefix'];
		}
		
		$count = 1;
		if(in_array($invoicePrefix, $data))
		{
			
			while( in_array( ($invoiceNum . ++$count ), $data) );
			$invoiceNum = $invoiceNum . $count;
		}
		
		$number = sprintf("%03s", $count);
		
		return $invoicePrefix.'/'.$number;
	}
	
	public function save_edit($id) 
	{		
		$data 				= array();
        $invoice 			= $this->input->post();
    	$gstCharges 		= $this->basic_operation_m->get_query_row('select * from tbl_gst_setting order by id desc limit 1');
       	$cgst 				= round($this->input->post('cgst'), 2);    	
    	$sgst 				= round($this->input->post('sgst'), 2);
    	$igst 				= round($this->input->post('igst'), 2);
    	$grand_total		= $this->input->post('grand_total');
		$p_invoice_date	 	= $this->input->post('invoice_date');
		if($p_invoice_date!="")
		{
		    $invoice_date =date("Y-m-d",strtotime($p_invoice_date)); 
		}
		
		
		$in = $this->db->query("select invoice_number,company_id from tbl_international_invoice where id = '$id'");
        $invoiceData = $in->row_array();
		
		$company_id			= $invoiceData['company_id'];
		$company_setting 	= $this->basic_operation_m->get_query_row("select * from tbl_company where id ='$company_id'");
		
		$year = date('y');
		$invoicePrefix = $company_setting->domestic_invoice_series.'/'.$year.'-'.($year+1);
		$invoice['invoice_prefix'] = $invoicePrefix;
		
    	$invoice['invoice_date'] 	= $invoice_date;
    	$invoice['fuel_subcharges'] = $this->input->post('fuel_subcharges');
    	$invoice['grand_total'] 	= $this->input->post('grand_total');
    	$invoice['cgst_amount'] 	= $cgst;
    	$invoice['sgst_amount'] 	= $sgst;
    	$invoice['igst_amount'] 	= $igst;
    	$invoice['sub_total'] 		= $this->input->post('sub_total');
    	

    	
       	unset($invoice['list'],$invoice['total_booking'],$invoice['cgst'],$invoice['sgst'],$invoice['igst'], $invoice['sub_total']);
    	if ($this->session->userdata("userType") != '1') {
    		$username = $this->session->userdata("userName");
    		$whr = array('username' => $username);
    		
    		$res=$this->basic_operation_m->get_table_row('tbl_users',$whr);
		 	$branch_id = $res->branch_id;
    	}

    	
    	$this->db->where('id', $id);
    	$this->db->update('tbl_international_invoice', $invoice);
		$list = $this->input->post('list');
		
      
        $query = "select tbl_international_invoice.*, tbl_customers.pincode, tbl_customers.phone, tbl_customers.sac_code from tbl_international_invoice LEFT JOIN tbl_customers ON tbl_international_invoice .cid = tbl_customers.cid where tbl_international_invoice.id =".$id;
        
        $data['customer'] = $this->basic_operation_m->get_query_row($query);
        $inc_num =$data['customer']->inc_num;
        $company_id =$data['customer']->company_id;
       
        $in_de = "select tbl_international_invoice_detail.*, tbl_international_booking.invoice_no as booking_invoice_num from tbl_international_invoice_detail INNER JOIN tbl_international_booking ON tbl_international_invoice_detail.booking_id = tbl_international_booking.booking_id where tbl_international_invoice_detail.invoice_id =".$id;
        
        $data['allpoddata'] = $this->basic_operation_m->get_query_result_array($in_de);

     
    	$data['gstCharges'] 		= $gstCharges;
		$data['sgst_amount'] 		= $data['customer']->sgst_amount;
		$data['cgst_amount'] 		= $data['customer']->cgst_amount;
		$data['total_amount'] 		= $data['customer']->total_amount;
		$data['sub_total'] 			= $data['customer']->total_amount;
		$data['grand_total'] 		= $data['customer']->grand_total;

		$where =array('id'=>$company_id);
		$data['company_details'] = $this->basic_operation_m->get_table_row('tbl_company',$where);
		
		$invoice_series = $data['company_details']->international_invoice_series;
		
    	$html = $this->load->view('admin/booking_master/booking_pdf', $data, true);
	
		// print_r($html); 
	
		$this->load->library('M_pdf');
        
        $this->m_pdf->pdf->setAutoTopMargin = 'stretch';
        $this->m_pdf->pdf->autoMarginPadding = 'pad';
        $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';

		// $this->m_pdf->pdf->SetHTMLFooter('<div style="text-align: right">Page {PAGENO} out of {nbpg}</div>');
	    $this->m_pdf->pdf->WriteHTML($html);
	    
	    $this->m_pdf->pdf->defaultheaderfontsize=14;
        $this->m_pdf->pdf->defaultheaderfontstyle='B';
        $this->m_pdf->pdf->defaultheaderline=1;
	 
        $this->mpdf->showImageErrors = true;
        $this->mpdf->debug = true;
        
		$type           = 'F';
		if($inc_num >= 100)
		{
            $filename = $invoice_series.'_'.$inc_num.'.pdf';
		}else{
		    $filename = $invoice_series.'_'.$id.'.pdf';
		}
        
		$filename = str_replace('/','_',$filename);
        //echo "++++".$filename;exit;
		$savefolderpath = 'assets/invoice/international/';
		
        $this->m_pdf->pdf->Output($savefolderpath.$filename, $type);
		
		if(isset($invoice['final_invoice']) && $invoice['final_invoice'] == '1')
		{
			redirect('admin/list-finalInvoice'); 
		}
		else
		{
			redirect('admin/list-invoice'); 
		}
		
	}
	 public function show_pdf()
	 {
		require 'assets/dompdf/autoload.inc.php';
		$dompdf = new Dompdf();
		$dompdf->loadHtml('hello world');
		$dompdf->setPaper('A4', 'landscape');
		$dompdf->render();
		$dompdf->stream();
 	}
	

    public function invoice() {    	
    		$data = array();
    		$this->load->helper('form_helper');
    		if ($this->session->userdata("userType") == '1') {
    			$resAct = $this->db->query("select * from tbl_international_invoice where final_invoice = 0");
    			if ($resAct->num_rows() > 0) {
    				$data['allpoddata'] = $resAct->result_array();
    			}
    		} else {
    			$username = $this->session->userdata("userName");
    			$whr = array('username' => $username);

    			$res=$this->basic_operation_m->get_table_row('tbl_users',$whr);
		 		$branch_id = $res->branch_id;

    			$resAct = $this->db->query("select * from tbl_international_invoice_detail where branch_id='$branch_id' and final_invoice = 0 ORDER BY  tbl_international_invoice.id desc");
    			if ($resAct->num_rows() > 0) {
    				$data['allpoddata'] = $resAct->result_array();
    			}
    		}
    		$this->load->view('admin/booking_master/view_invoice', $data);
    	
    }
    
    public function finalInvoice() 
	{
    	
		$data = array();
		$this->load->helper('form_helper');
		if ($this->session->userdata("userType") == '1') 
		{
			$whrCon = array('final_invoice' => '1');    			
			$data['allpoddata'] = $this->booking_model->select_invoice_details($whrCon);
		}
		else 
		{

			$username = $this->session->userdata("userName");
			$whr = array('username' => $username);
			$res=$this->basic_operation_m->getAll('tbl_users',$whr);
			$branch_id = $res[0]['branch_id'];

			$whrCon = array('branch_id' => $branch_id,'final_invoice'=>'1');    		
			$data['allpoddata'] = $this->booking_model->select_invoice_details($whrCon);
			
		}    		
		
		$this->load->view('admin/booking_master/view_invoice_final_list', $data);
    	
    }
    
    public function numberTowords($num) {
    	error_reporting(0);
    	$ones = array(
    		1 => "one",
    		2 => "two",
    		3 => "three",
    		4 => "four",
    		5 => "five",
    		6 => "six",
    		7 => "seven",
    		8 => "eight",
    		9 => "nine",
    		10 => "ten",
    		11 => "eleven",
    		12 => "twelve",
    		13 => "thirteen",
    		14 => "fourteen",
    		15 => "fifteen",
    		16 => "sixteen",
    		17 => "seventeen",
    		18 => "eighteen",
    		19 => "nineteen"
    		);
    	$tens = array(
    		1 => "ten",
    		2 => "twenty",
    		3 => "thirty",
    		4 => "forty",
    		5 => "fifty",
    		6 => "sixty",
    		7 => "seventy",
    		8 => "eighty",
    		9 => "ninety"
    		);
    	$hundreds = array(
    		"hundred",
    		"thousand",
    		"million",
    		"billion",
    		"trillion",
    		"quadrillion"
        ); //limit t quadrillion 
    	$num = number_format($num, 2, ".", ",");
    	$num_arr = explode(".", $num);
    	$wholenum = $num_arr[0];
    	$decnum = $num_arr[1];
    	$whole_arr = array_reverse(explode(",", $wholenum));
    	krsort($whole_arr);
    	$rettxt = "";
    	foreach ($whole_arr as $key => $i) {
    		if ($i < 20) {
    			$rettxt .= $ones[$i];
    		} elseif ($i < 100) {
    			$rettxt .= $tens[substr($i, 0, 1)];
    			$rettxt .= " " . $ones[substr($i, 1, 1)];
    		} else {
    			$rettxt .= $ones[substr($i, 0, 1)] . " " . $hundreds[0];
    			$rettxt .= " " . $tens[substr($i, 1, 1)];
    			$rettxt .= " " . $ones[substr($i, 2, 1)];
    		}
    		if ($key > 0) {
    			$rettxt .= " " . $hundreds[$key] . " ";
    		}
    	}
    	if ($decnum > 0) {
    		$rettxt .= " and ";
    		if ($decnum < 20) {
    			$rettxt .= $ones[$decnum];
    		} elseif ($decnum < 100) {
    			$rettxt .= $tens[substr($decnum, 0, 1)];
    			$rettxt .= " " . $ones[substr($decnum, 1, 1)];
    		}
    	}
    	return $rettxt;
    }

	public function send_mail($invoice_id, $customer_id) 
	{
		error_reporting(E_ALL);
		$resAct = $this->db->query("select * from tbl_customers where customer_id = {$customer_id}");
		$customer = $resAct->row();
	
		$email = $customer->email;
		//$email="itsprachee08@gmail.com";
		
		$inv_details = $this->db->query("select invoice_number,inc_num,company_id from tbl_international_invoice WHERE id='$invoice_id'");
        $inv_number = $inv_details->row()->invoice_number;
        $inc_num = $inv_details->row()->inc_num;
        $company_id = $inv_details->row()->company_id;
		
	    $where =array('id'=>$company_id);
		$data['company_details'] = $this->basic_operation_m->get_table_row('tbl_company',$where);
	    $prefix = $data['company_details']->international_invoice_series;
	    $company_name = $data['company_details']->company_name;
		
	    $company_logo=$data['company_details']->logo;
        $img_path ="http://omcourier.net/assets/company/".$company_logo;
		
       // unset($resInoviceSetting);
          $config = array();
          $this->load->library('email');
          $config['mailtype'] = 'html';
          $config['charset'] = 'utf-8';
          $config['newline'] = "\r\n";
          $config['charset'] = 'iso-8859-1';
          $config['wordwrap'] = TRUE;
          $this->load->library('email');
          $this->email->initialize($config);
          $message='Dear Valued Customer, <br><br>
Please find herewith soft copy of the invoice.<br>
Please be inform that:-<br>
This is a computer generated bill & is valid even though not signed. <br>
In case of any discrepancy of whatsoever nature in the bill, please inform in writing within 10 days from the date of the bill. <br>
Falling which it would be deemed that this bill has been accepted by you in all respects and it is good for payment, <br>
as per the terms & conditions of The contract and no further claim will be accepted.<br><br><br><br>

<b>WITH BEST REGARDS,</b><br><br>';

$message.='<img src="'.$img_path.'" width="140" height="100">';

$message.='<br><br><i><b>Mausam  Nayak</b></i><br><br>
<span style="color:#f56421;">'.$company_name.'</span><br>
SHOP NO-1,OM DEEP SAI POOJA CHS,<br>                                                                      
NEAR ALMEIDA SIGNAL,<br>
CHARAI NAKA,THANE-400601<br>
GST IN:-27ADAPN6620J1ZJ<br>
Cell :-8169451549 ,www.omcourier.net<br>';

//echo $message;
          $this->email->from('billing@omcourier.net');
          $this->email->reply_to('billing@omcourier.net');
          $this->email->cc('opsomcourier@gmail.com');//opsomcourier@gmail.com
          $this->email->bcc('itsprachee08@gmail.com');
          $this->email->to($email);
          $this->email->subject('Invoice-'.$inv_number);
          $this->email->message($message);
          
          if($inc_num>=100)
          {
              $name = $prefix."_".$inc_num . '.pdf';
          }else{
            $name = $prefix."_".$invoice_id . '.pdf';
          }
          
          $this->email->attach(base_url('assets/invoice/international/' . $name));
         if($this->email->send())
         {
             $data=array('send_mail_status'=>1);
             $whr =array('id'=>$invoice_id,'customer_id'=>$customer_id);
             $this->basic_operation_m->update('tbl_international_invoice',$data,$whr);
         }
          redirect('admin/list-finalInvoice');
    }
      
      public function invoice_delete($id) {
      	$this->db->where('id', $id);
      	$this->db->delete('tbl_international_invoice');
      	
      	$whr =array('invoice_id'=> $id);
      	$booking_id_details = $this->basic_operation_m->get_all_result("tbl_international_invoice_detail",$whr);
      
		foreach ($booking_id_details as $value) {
			$d_update =array('invoice_generated_status'=>'0');
 			$d_whr=array('booking_id'=>$value['booking_id']);
			  	$this->basic_operation_m->update('tbl_international_booking',$d_update,$d_whr);
		}
   
        $this->db->where('id', $id);
      	$this->db->delete('tbl_international_invoice_detail');
      	redirect('admin/list-invoice');
      }

    public function international_excel($invoiceId,$company_id)
    {

		// load excel library 
		$this->load->library('excel'); 
		//$fileName = $invoiceId.".xls";
		$fileName = 'data-'.time().'.xls';  
		$fp = fopen('php://output', 'w');
		
		$company_details		 	= $this->basic_operation_m->get_table_row('tbl_company',array('id'=>$company_id));

		$invoice_data 				= $this->Invoice_model->getInvoice($invoiceId);
		$invoiceDeatil_data 		= $this->Invoice_model->getInvoiceDetail($invoiceId);
		
		$whr 						= array('customer_id'=>$invoice_data->customer_id);
		$customer_details		 	= $this->basic_operation_m->get_table_row('tbl_customers',$whr);
		
		$objPHPExcel = new PHPExcel();


        $sheet=$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->getRowDimension(4)->setRowHeight(45);


		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Logo');
		$objDrawing->setDescription('Logo');
		$logo = FCPATH . '/assets/company/'.$company_details->logo; // Provide path to your logo file
		$objDrawing->setPath($logo);
		$objPHPExcel->getActiveSheet()->mergeCells('B4:D4');
		$objPHPExcel->getActiveSheet()->getRowDimension('B4')->setRowHeight(300);
		$objPHPExcel->getActiveSheet()->getStyle('B4:H4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$objDrawing->setOffsetX(0);    // setOffsetX works properly
		$objDrawing->setOffsetY(300);  //setOffsetY has no effect
		$objDrawing->setCoordinates('B4');
		$objDrawing->setHeight(50); // logo height
		$objDrawing->setWidth(150);
		$objDrawing->setWorksheet($sheet);
		
		$objPHPExcel->getActiveSheet()->SetCellValue("E4", 'TAX INVOICE');
		$objPHPExcel->getActiveSheet()->getStyle('E4')->getFont()->setSize(20);
		$objPHPExcel->getActiveSheet()->getStyle('E4')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('E4:I4');
		$objPHPExcel->getActiveSheet()->getStyle('E4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("E4:I4")->applyFromArray(
		array(
		'borders' => array(
		'left' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb' => 'FFFFFF')
		)
		)
		)
		);

		$objPHPExcel->getActiveSheet()->SetCellValue('B5', $company_details->company_name);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('B5:F5');

		for($i=6;$i<11;$i++)
		{
			$objPHPExcel->getActiveSheet()->getStyle("B{$i}:F{$i}")->applyFromArray(
			array(
			'borders' => array(
			'top' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('rgb' => 'FFFFFF')
			)
			)
			)
			);
		}

		for($i=12;$i<22;$i++)
		{
			$objPHPExcel->getActiveSheet()->getStyle("B{$i}:F{$i}")->applyFromArray(
			array(
			'borders' => array(
			'top' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('rgb' => 'FFFFFF')
			)
			)
			)
			);
		}



		$objPHPExcel->getActiveSheet()->SetCellValue('G5', 'State Name - Maharashtra,Code -27');
		$objPHPExcel->getActiveSheet()->mergeCells('G5:I6');


		$objPHPExcel->getActiveSheet()->getStyle('G5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


		$objPHPExcel->getActiveSheet()->SetCellValue('B6', $company_details->address);
		$objPHPExcel->getActiveSheet()->mergeCells('B6:F6');

		$objPHPExcel->getActiveSheet()->SetCellValue('G7', 'Invoice No - '.$invoice_data->invoice_number);
		$objPHPExcel->getActiveSheet()->mergeCells('G7:I8');
		$objPHPExcel->getActiveSheet()->getStyle('G7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


		$objPHPExcel->getActiveSheet()->SetCellValue('B7', 'Telkush Wadi,Dombiwali, Thane,');
		$objPHPExcel->getActiveSheet()->mergeCells('B7:F7');

		$objPHPExcel->getActiveSheet()->SetCellValue('G9', 'Invoice Date - '.date('d-m-Y',strtotime($invoice_data->created_on)));
		$objPHPExcel->getActiveSheet()->mergeCells('G9:I10');
		$objPHPExcel->getActiveSheet()->getStyle('G9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);



		$objPHPExcel->getActiveSheet()->SetCellValue('B8', 'GSTIN NO - '.$company_details->gst_no);

		$objPHPExcel->getActiveSheet()->mergeCells('B8:F8');

		$objPHPExcel->getActiveSheet()->SetCellValue('B9', 'PAN NO - '.$company_details->pan);

		$objPHPExcel->getActiveSheet()->mergeCells('B9:F9');

		$objPHPExcel->getActiveSheet()->SetCellValue('B10', 'Email Id - '.$company_details->email);

		$objPHPExcel->getActiveSheet()->mergeCells('B10:F10');

		//====================================================
		$objPHPExcel->getActiveSheet()->SetCellValue('B11', 'Buyer (Bill to)');
		$objPHPExcel->getActiveSheet()->mergeCells('B11:F11');

		$objPHPExcel->getActiveSheet()->SetCellValue('G11', 'Period of Service :');
		$objPHPExcel->getActiveSheet()->mergeCells('G11:I11');




		$objPHPExcel->getActiveSheet()->SetCellValue('B12', $invoice_data->customer_name);
		$objPHPExcel->getActiveSheet()->getStyle('B12')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('B12:F12');


		$objPHPExcel->getActiveSheet()->SetCellValue('G12', $invoice_data->invoice_from_date.' to '.$invoice_data->invoice_to_date);
		$objPHPExcel->getActiveSheet()->mergeCells('G12:I12');


		$objPHPExcel->getActiveSheet()->SetCellValue('B13', $invoice_data->address);
		$objPHPExcel->getActiveSheet()->mergeCells('B13:F13');

		$objPHPExcel->getActiveSheet()->SetCellValue('G13', 'Customer Code : '.$customer_details->cid);
		$objPHPExcel->getActiveSheet()->mergeCells('G13:I14');


		$objPHPExcel->getActiveSheet()->SetCellValue('B14', '');
		$objPHPExcel->getActiveSheet()->mergeCells('B14:F14');

		$objPHPExcel->getActiveSheet()->SetCellValue('G15', 'Other References');
		$objPHPExcel->getActiveSheet()->getStyle('G15')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel->getActiveSheet()->mergeCells('G15:I17');

		$objPHPExcel->getActiveSheet()->SetCellValue('B15', 'Andheri-East, Mumbai');
		$objPHPExcel->getActiveSheet()->mergeCells('B15:F15');
		$objPHPExcel->getActiveSheet()->SetCellValue('G18', 'Mode / Terms of Payment');
		$objPHPExcel->getActiveSheet()->getStyle('G18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel->getActiveSheet()->mergeCells('G18:I21');

		$objPHPExcel->getActiveSheet()->SetCellValue('B16','Maharashtra, 400093');
		$objPHPExcel->getActiveSheet()->mergeCells('B16:F16');

		$objPHPExcel->getActiveSheet()->SetCellValue('B17','State Name – Maharashtra,Code-27');
		$objPHPExcel->getActiveSheet()->mergeCells('B17:F17');


		$objPHPExcel->getActiveSheet()->SetCellValue('B18','GSTIN – '.$invoice_data->gstno);
		$objPHPExcel->getActiveSheet()->mergeCells('B18:F18');

		$objPHPExcel->getActiveSheet()->SetCellValue('B19','PAN - AABCJ8820B');
		$objPHPExcel->getActiveSheet()->mergeCells('B19:F19');

		$objPHPExcel->getActiveSheet()->SetCellValue('B20','Email Id - '.$customer_details->email);
		$objPHPExcel->getActiveSheet()->mergeCells('B20:F20');

		$objPHPExcel->getActiveSheet()->SetCellValue('B21','Mobile No - '.$customer_details->phone);$objPHPExcel->getActiveSheet()->mergeCells('B21:F21');

		// set Header
				

		// set Header
		$objPHPExcel->getActiveSheet()->SetCellValue('B22', 'SR NO');
        $objPHPExcel->getActiveSheet()->SetCellValue('C22', 'DATE');
        $objPHPExcel->getActiveSheet()->SetCellValue('D22', 'AWB NO');
        $objPHPExcel->getActiveSheet()->SetCellValue('E22', 'PINCODE');
        $objPHPExcel->getActiveSheet()->SetCellValue('F22', 'DESTINATION'); 
		$objPHPExcel->getActiveSheet()->SetCellValue('G22', 'WEIGHT');
		$objPHPExcel->getActiveSheet()->SetCellValue('H22', 'RATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('I22', 'AMOUNT');
		
		
		for($i=66;$i<74;$i++)
		{
			$rr=chr($i);

			$objPHPExcel->getActiveSheet()->getStyle($rr.'22')->getFont()->setBold(true); 
		} 



		  
		// set Row
		$rowCount = 23;
		$ijk     = 1;
		$total_records 	= $rowCount +  count($invoiceDeatil_data);
		foreach($invoiceDeatil_data as $row) 
		{
			$whr=array('booking_id'=>$row->booking_id);
			$booking_details = $this->basic_operation_m->get_table_row('tbl_domestic_booking',$whr);
			 
			$chargable_weight		= ($row->chargable_weight/1000);
			$whr=array('booking_id'=>$row->booking_id);
			$weight_details = $this->basic_operation_m->get_table_row('tbl_domestic_weight_details',$whr);
			
			$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $ijk);
			$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row->booking_date);
			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row->pod_no);
			$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $booking_details->reciever_pincode);
			$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row->reciever_city); 
			$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $chargable_weight); 
			$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $row->frieht);
			$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $row->amount);	
			$ijk++;
			$rowCount++;
		}

		/* for ($i=1;$i<16;$i++) 
		{

			
		   
			$rowCount++;
		} */
		
		$new_row_count 				= $rowCount;

		$row38 = $rowCount;
		$row39 = $rowCount + 1;
		$row40 = $rowCount + 2;
		$row41 = $rowCount + 3;
		$row42 = $rowCount + 4;
		$row43 = $rowCount + 5;
		$row44 = $rowCount + 6;
		$row45 = $rowCount + 7;
		$row46 = $rowCount + 8;
		$row47 = $rowCount + 9;
		$row48 = $rowCount + 10;
		$row49 = $rowCount + 11;
		$row50 = $rowCount + 12;
		$row51 = $rowCount + 13;
		$row52 = $rowCount + 14;
		$row53 = $rowCount + 15;


		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row38, 'Bank Details :');
		$objPHPExcel->getActiveSheet()->getStyle('B'.$row38)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$row38)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$row38.':F'.$row38);

		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row38, 'TOTAL');
		$objPHPExcel->getActiveSheet()->getStyle('G'.$row38)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$row38)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->mergeCells('G'.$row38.':H'.$row38);


		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$row38, $invoice_data->sub_total);
		$objPHPExcel->getActiveSheet()->getStyle('I'.$row38)->getFont()->setBold(true);




		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row39, 'Beneficiary Name');
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$row39.':C'.$row39);

		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row39, $company_details->account_name);
		$objPHPExcel->getActiveSheet()->mergeCells('D'.$row39.':F'.$row39);


		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row39, 'Fuel surchage @ 0.00%');
		$objPHPExcel->getActiveSheet()->mergeCells('G'.$row39.':H'.$row39);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$row39, '0.00');




		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row40, 'Bank Name');
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$row40.':C'.$row40);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row40, $company_details->bank_name);
		$objPHPExcel->getActiveSheet()->mergeCells('D'.$row40.':F'.$row40);

		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row40, 'Total Taxable Value');
		$objPHPExcel->getActiveSheet()->mergeCells('G'.$row40.':H'.$row40);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$row40, $invoice_data->sub_total);



		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row41, 'A/C No');
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$row41.':C'.$row41);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row41, $company_details->account_number);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$row41)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->mergeCells('D'.$row41.':F'.$row41);

		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row41, 'CGST @ '.$invoice_data->cgst_per.' %');
		$objPHPExcel->getActiveSheet()->mergeCells('G'.$row41.':H'.$row41);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$row41, $invoice_data->cgst_amount);




		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row42, 'IFSC Code');
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$row42.':C'.$row42);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row42, $company_details->ifsc);
		$objPHPExcel->getActiveSheet()->mergeCells('D'.$row42.':F'.$row42);

		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row42, 'SGST @ '.$invoice_data->sgst_per.' %');
		$objPHPExcel->getActiveSheet()->mergeCells('G'.$row42.':H'.$row42);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$row42, $invoice_data->sgst_amount);



		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row43, 'Branch');
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$row43.':C'.$row43);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row43, $company_details->branch_name);
		$objPHPExcel->getActiveSheet()->mergeCells('D'.$row43.':F'.$row43);

		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row43, 'IGST @ '.$invoice_data->igst_per.' %');
		$objPHPExcel->getActiveSheet()->mergeCells('G'.$row43.':H'.$row43);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$row43, $invoice_data->igst_amount);

		//
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$row44.':F'.$row44);
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row44, 'Total Value Incuding Tax');
		$objPHPExcel->getActiveSheet()->getStyle('G'.$row44)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$row44)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objPHPExcel->getActiveSheet()->mergeCells('G'.$row44.':H'.$row44);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$row44, $invoice_data->grand_total);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$row44)->getFont()->setBold(true);

		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row45, 'In Words :');
		$objPHPExcel->getActiveSheet()->getStyle('B'.$row45)->getFont()->setBold(true);
		$amt_in_word = $this->numberTowords($invoice_data->grand_total);

		$amt_in_word = ucwords($amt_in_word." Rupees Only");
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row45, $amt_in_word);
		$objPHPExcel->getActiveSheet()->mergeCells('C'.$row45.':F'.$row45);

		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row45, 'Total');
		$objPHPExcel->getActiveSheet()->getStyle('G'.$row45)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$row45)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->mergeCells('G'.$row45.':H'.$row45);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$row45, $invoice_data->grand_total);
		$objPHPExcel->getActiveSheet()->getStyle('I'.$row45)->getFont()->setBold(true);


		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row46, 'Invoice Value in Figures : '.$invoice_data->grand_total);
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$row46.':F'.$row46);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$row46)->getFont()->setBold(true);


		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row46, 'For '.$company_details->company_name);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$row46)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$row46)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('G'.$row46.':I'.$row46);



		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row47, 'Terms & conditions');
		$objPHPExcel->getActiveSheet()->getStyle('B'.$row47)->getFont()->setUnderline(true);
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$row47.':F'.$row47);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$row47)->getFont()->setBold(true);

		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row48, $invoice_data->invoice_term_condition);
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$row48.':F'.$row48);

		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row49, ' 2. In case of any discrepancy, please bring to our notice within 7 days ');
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$row49.':F'.$row49);

		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row50, ' of receipt of this bill.');
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$row50.':F'.$row50);


		$objPHPExcel->getActiveSheet()->mergeCells('B'.$row51.':F'.$row51);


		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row51, 'Authorized Signatory');
		$objPHPExcel->getActiveSheet()->getStyle("G".$row51.":I".$row51)->applyFromArray(
		array(
		'borders' => array(
		'top' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb' => 'FFFFFF')
		)
		)
		)
		);
		$objPHPExcel->getActiveSheet()->mergeCells('G'.$row51.':I'.$row51);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$row51)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$row51)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objPHPExcel->getActiveSheet()->mergeCells('G'.$row47.':I'.$row50);

		   for($i=$row39;$i<$row44;$i++)
		{
		$objPHPExcel->getActiveSheet()->getStyle("B{$i}:F{$i}")->applyFromArray(
		array(
		'borders' => array(
		'top' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb' => 'FFFFFF')
		)
		)
		)
		);
		}
		for($i=$row39;$i<$row44;$i++)
		{
		$objPHPExcel->getActiveSheet()->getStyle("D{$i}:F{$i}")->applyFromArray(
		array(
		'borders' => array(
		'left' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb' => 'FFFFFF')
		)
		)
		)
		);
		}

		for($i=$row48;$i<$row52;$i++)
		{
		$objPHPExcel->getActiveSheet()->getStyle("B{$i}:F{$i}")->applyFromArray(
		array(
		'borders' => array(
		'top' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb' => 'FFFFFF')
		)
		)
		)
		);
		}

		$objPHPExcel->getActiveSheet()->getStyle("B4:I4")->applyFromArray(
		array(
		'borders' => array(
		'top' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb' => '000000')
		)
		)
		)
		);
		$objPHPExcel->getActiveSheet()->getStyle("B".$row51.":I".$row51)->applyFromArray(
		array(
		'borders' => array(
		'bottom' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb' => '000000')
		)
		)
		)
		);
		$objPHPExcel->getActiveSheet()->getStyle("B4:B".$row51)->applyFromArray(
		array(
		'borders' => array(
		'left' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb' => '000000')
		)
		)
		)
		);
		   $objPHPExcel->getActiveSheet()->getStyle("I4:I".$row51)->applyFromArray(
		array(
		'borders' => array(
		'right' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb' => '000000')
		)
		)
		)
		);

		$objPHPExcel->getActiveSheet()->getStyle("B22:I".($rowCount-1))->applyFromArray(
		array(
		'borders' => array(
		'allborders' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb' => '000000')
		)
		)
		)
		);

		$objPHPExcel->getActiveSheet()->getStyle("G".$row38.":I".$row46)->applyFromArray(
		array(
		'borders' => array(
		'allborders' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb' => '000000')
		)
		)
		)
		);

		$objPHPExcel->getActiveSheet()->getStyle("B".$row44.":F".$row46)->applyFromArray(
		array(
		'borders' => array(
		'allborders' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb' => '000000')
		)
		)
		)
		);

		$objPHPExcel->getActiveSheet()->getStyle("G5:I21")->applyFromArray(
		array(
		'borders' => array(
		'allborders' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb' => '000000')
		)
		)
		)
		);

		$objPHPExcel->getActiveSheet()->getStyle("G47:G".$row51)->applyFromArray(
		array(
		'borders' => array(
		'left' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb' => '000000')
		)
		)
		)
		);

		$objPHPExcel->getActiveSheet()->getStyle("B4:I4")->applyFromArray(
		array(
		'borders' => array(
		'bottom' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb' => '000000')
		)
		)
		)
		);
		$objPHPExcel->getActiveSheet()->getStyle("B10:I10")->applyFromArray(
		array(
		'borders' => array(
		'bottom' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb' => '000000')
		)
		)
		)
		);
		$objPHPExcel->getActiveSheet()
		->getStyle('D'.$row41.':F'.$row41)
		->getNumberFormat()
		->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);

		$objPHPExcel->getActiveSheet()->getStyle("A3:Z3")->applyFromArray(
		array(
		'borders' => array(
		'bottom' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb' => 'ffffff')
		)
		)
		)
		);


		$objPHPExcel->getActiveSheet()->getStyle("A".$row52.":Z100")->applyFromArray(
		array(
		'borders' => array(
		'bottom' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb' => 'ffffff')
		)
		)
		)
		);

		$objPHPExcel->getActiveSheet()->getStyle("A".$row52.":Z500")->applyFromArray(
		array(
		'borders' => array(
		'allborders' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb' => 'ffffff')
		)
		)
		)
		);

		$objPHPExcel->getActiveSheet()->getStyle("A1:A".$row51)->applyFromArray(
		array(
		'borders' => array(
		'allborders' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb' => 'ffffff')
		)
		)
		)
		);

		$objPHPExcel->getActiveSheet()->getStyle("B1:Z3")->applyFromArray(
		array(
		'borders' => array(
		'allborders' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb' => 'ffffff')
		)
		)
		)
		);


		$objPHPExcel->getActiveSheet()->getStyle("J4:Z".$row51)->applyFromArray(
		array(
		'borders' => array(
		'allborders' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb' => 'ffffff')
		)
		)
		)
		);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$fileName);
		header('Cache-Control: max-age=0');
		$object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$object_writer->save('php://output');

		exit;


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

	public function payment_history(){		
		$userid = $this->session->userdata('userId');

		$data['payment_history'] = $this->booking_model->show_payment_history($userid);
		$this->load->view('admin/booking_master/view_payment_history', $data);
	}

}

?>
