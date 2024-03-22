<?php
error_reporting(E_ALL);
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_shipment_manager extends CI_Controller  {

	var $data = array();
    function __construct() 
	{
        parent :: __construct();
        $this->load->model('basic_operation_m');    
        if($this->session->userdata('userId') == '')
		{
			redirect('admin');
		}
    }
	
    public function view_shipment($offset=0,$searching='') 
	{	
	
		$data 		= [];
		$from_date 	= (isset($_GET['from_date']))?$_GET['from_date']:'';
		$to_date 	= (isset($_GET['to_date']))?$_GET['to_date']:'';
		$filter 	= (isset($_GET['filter']))?$_GET['filter']:'';

		$user_id 	= $this->session->userdata("userId");
		if ($this->session->userdata("userType") == 5) 
		{
			$data['customer']			=  $this->basic_operation_m->get_table_result('tbl_customers', "customer_type = 'b2b' AND user_id=$user_id");
		} 
		else if($this->session->userdata("userType") == 4)
		{
			$data['customer']			=  $this->basic_operation_m->get_table_result('tbl_customers', "user_id=$user_id");
		}
		else 
		{
			$data['customer']			=  $this->basic_operation_m->get_table_result('tbl_customers', "");
		}
		
		$user_type 					= $this->session->userdata("userType");			
		$filterCond					= '';
		$all_data 					= $this->input->post();

		if($all_data)
		{
			foreach($all_data as $ke=> $vall)
			{
				if($ke == 'pod_no' && !empty($vall))
				{
					$filterCond .= " AND tbl_booking.pod_no = '$vall'";
				}
				elseif($ke == 'user_id' && !empty($vall))
				{
					$filterCond .= " AND tbl_booking.customer_id = '$vall'";
				}
				elseif($ke == 'from_date' && !empty($vall))
				{
					$filterCond .= " AND tbl_booking.booking_date >= '$vall'";
				}
				elseif($ke == 'to_date' && !empty($vall))
				{
					$filterCond .= " AND tbl_booking.booking_date <= '$vall'";
				}
			}
		}

		if(!empty($searching))
		{
			$filterCond = urldecode($searching);
		}


		if ($this->session->userdata("userType") == '1') 
		{
			$resActt = $this->db->query("SELECT * FROM tbl_booking   WHERE booking_type = 1 $filterCond ");

			$resAct = $this->db->query("SELECT tbl_booking.pod_no,tbl_booking.sender_name,tbl_booking.reciever_name,tbl_booking.reciever_city,tbl_booking.forworder_name,tbl_booking.booking_id,tbl_booking.booking_date,tbl_booking.isVerified,tbl_booking.mode_dispatch,tbl_booking.verifier_name,tbl_booking.user_type,tbl_booking.forwording_no,tbl_charges.booking_id  FROM tbl_booking  join tbl_charges ON tbl_booking.booking_id = tbl_charges.booking_id WHERE booking_type = 1 AND tbl_booking.user_type !=5 $filterCond order by tbl_booking.booking_id DESC LIMIT $offset , 100");

			if ($resAct->num_rows() > 0) 
			{
				$this->load->library('pagination');

				$data['total_count']			= $resActt->num_rows();
				$config['total_rows'] 			= $resActt->num_rows();
				$config['base_url'] 			= 'generatepod/index/';
				$config['suffix'] 				= '/'.urlencode($filterCond);



				$config['per_page'] 			= 100;
				$config['full_tag_open'] 		= '<nav aria-label="..."><ul class="pagination">';
				$config['full_tag_close'] 		= '</ul></nav>';
				$config['first_link'] 			= '&laquo; First';
				$config['first_tag_open'] 		= '<li class="prev page">';
				$config['first_tag_close'] 		= '</li>';
				$config['last_link'] 			= 'Last &raquo;';
				$config['last_tag_open'] 		= '<li class="next page">';
				$config['last_tag_close'] 		= '</li>';
				$config['next_link'] 			= 'Next';
				$config['next_tag_open'] 		= '<li class="next page">';
				$config['next_tag_close'] 		= '</li>';
				$config['prev_link'] 			= 'Previous';
				$config['prev_tag_open'] 		= '<li class="prev page">';
				$config['prev_tag_close'] 		= '</li>';
				$config['cur_tag_open'] 		= '<li class="page active"><a href="javascript:void(0);" class="pagiantion_class">';
				$config['cur_tag_close'] 		= '</a></li>';
				$config['num_tag_open'] 		= '<li class="page">';
				$config['reuse_query_string'] 	= TRUE;
				$config['num_tag_close'] 		= '</li>';
				if($offset == '')
				{
					$config['uri_segment'] 			= 3;
					$data['serial_no']				= 1;
				}
				else
				{
					$config['uri_segment'] 			= 3;
					$data['serial_no']		= $offset + 1;
				}
				$data['allpoddata'] 			= $resAct->result_array();
				$this->pagination->initialize($config);

			}
			else
			{
				$data['allpoddata'] 			= array();
			}
		}
		else
		{
			$where 		= '';
			if($this->session->userdata("userType") == '5') 
			{
				$where 	= ' AND tbl_booking.user_id = '.$user_id;
			}

			$whrAct 	= array('isactive' => 1, 'isdeleted' => 0);



			$resActt = $this->db->query("SELECT * FROM tbl_booking   WHERE booking_type = 1 $where $filterCond ");
			$resAct = $this->db->query("SELECT *,tbl_booking.pod_no,tbl_booking.sender_name,tbl_booking.reciever_name,tbl_booking.reciever_city,tbl_booking.forworder_name,tbl_booking.booking_id,tbl_booking.booking_date,tbl_booking.isVerified,tbl_booking.mode_dispatch,tbl_booking.verifier_name,tbl_booking.user_type,tbl_charges.booking_id  FROM tbl_booking  join tbl_charges ON tbl_booking.booking_id = tbl_charges.booking_id    WHERE booking_type = 1 $where $filterCond order by tbl_booking.booking_date DESC LIMIT $offset , 100");

			if($resAct->num_rows() > 0) 
			{
				$data['total_count']				= $resActt->num_rows();
				$this->load->library('pagination');

				$config['total_rows'] 			= $resActt->num_rows();
				$config['suffix'] 				= '/'.urlencode($filterCond);
				$config['per_page'] 			= 100;
				$config['base_url'] 			= 'generatepod/index/';
				$config['full_tag_open'] 		= '<nav aria-label="..."><ul class="pagination">';
				$config['full_tag_close'] 		= '</ul></nav>';
				$config['first_link'] 			= '&laquo; First';
				$config['reuse_query_string'] = TRUE;
				$config['first_tag_open'] 		= '<li class="prev page">';
				$config['first_tag_close'] 		= '</li>';
				$config['last_link'] 			= 'Last &raquo;';
				$config['last_tag_open'] 		= '<li class="next page">';
				$config['last_tag_close'] 		= '</li>';
				$config['next_link'] 			= 'Next';
				$config['next_tag_open'] 		= '<li class="next page">';
				$config['next_tag_close'] 		= '</li>';
				$config['prev_link'] 			= 'Previous';
				$config['prev_tag_open'] 		= '<li class="prev page">';
				$config['prev_tag_close'] 		= '</li>';
				$config['cur_tag_open'] 		= '<li class="page active"><a href="javascript:void(0);" class="pagiantion_class">';
				$config['cur_tag_close'] 		= '</a></li>';
				$config['num_tag_open'] 		= '<li class="page">';
				$config['num_tag_close'] 		= '</li>';

				if($offset == '')
				{
					$config['uri_segment'] 			= 3;
					$data['serial_no']				= 1;
				}
				else
				{
					$config['uri_segment'] 			= 3;
					$data['serial_no']		= $offset + 1;
				}

				$data['allpoddata'] 			= $resAct->result_array();
				$this->pagination->initialize($config);
			}
			else
			{
				$data['allpoddata'] 			= array();
			}
		}
		$data['viewVerified'] = 2;
		$this->load->view('admin/shipment/view_shipment', $data);
       
	}
	
	
    public function add_new_rate()
    {

		$doc				= $_REQUEST['doc'];
		$upper_chargee 		= array();
        $sub_total 			= 0;
        $doc_sub_total 		= 0;
        $addition 			= 0;
        $addtional_kg 		= 0;
		
		
		$all_data 			= $this->input->post();
		
		
        $qry11 				= "SELECT * FROM `pincode` WHERE `pin_code` = '".$_REQUEST['receiverPincode']."' ORDER BY `id` DESC";
        $query 				= $this->db->query($qry11);
        $result 			= $query->result();
        $city_id 			= $result[0]->city_id;
		$state_id			= $result[0]->state_id;
		
		$customer_id 		= $this->input->post('customer_id');
		$weight		 		= $this->input->post('weight');
		$qty		 		= $this->input->post('qty');
		$doc		 		= $this->input->post('doc');
	
	
		$senderPincode 		= $this->input->post('senderPincode');
		$receiverPincode 	= $this->input->post('receiverPincode');
		$mode_dispatch 		= ucfirst($this->input->post('mode_dispatch'));
		
		
		$qry111 = "SELECT * FROM `pincode` WHERE `pin_code` = '".$_REQUEST['receiverPincode']."' ORDER BY `id` DESC";
        $query = $this->db->query($qry111);
        $result = $query->result();
        $rstate_id = $result[0]->state_id;
        
        
        $qry222 = "SELECT * FROM `pincode` WHERE `pin_code` = '".$_REQUEST['senderPincode']."' ORDER BY `id` DESC";
        $query = $this->db->query($qry222);
        $result = $query->result();
        $sstate_id = $result[0]->state_id;
		
		
		$region_query 	= $this->db->query("SELECT `state`.`region_id`,`state`.`id` as state_id,`state`.`edd_train`,`state`.`edd_air`, `state`.`edd_air` FROM `state` join city ON `city`.`state_id` = `state`.`id` WHERE `city`.`id` = ".$city_id);
		
		if ($region_query->num_rows() > 0) 
		{
			$regionData 	= $region_query->row();
			$region_id 		= $regionData->region_id;
			$state_id 		= $regionData->state_id;
			$eod 			= ($mode_dispatch == 'air') ? $regionData->edd_air : $regionData->edd_air;
			$eod 			= $this->addBusinessDays(date("d-m-Y"),!empty($regionData->eod) ? $regionData->eod : 4);
		}
		
		
		$data['rate_master'] = new \stdClass();
			
	
		$res = $this->db->query("select * from tbl_rate_master where customer_id=".$customer_id." AND mode_of_transport='".$mode_dispatch."' AND region_id=".$region_id." LIMIT 1");
		if ($res->num_rows() > 0) 
		{
			$data['rate_master'] = $res->row();
			$rate_id			= $data['rate_master']->rate_master_id;
			$ress 				= $this->db->query("select * from rate_master where rate_id=".$rate_id."");
			if($ress->num_rows() > 0) 
			{
				$data['rate_master']	= $ress->result();
			
				// check rate available for state table
				$stateMasterRes = $this->db->query("select * from doc_state_rate_master where rate_id=".$rate_id." AND state_id =".$state_id." ");
				
				if($stateMasterRes->num_rows() > 0)
				{
					$data['rate_master']	= $stateMasterRes->result();
				}
				
				
				//check rate available for city table
				$cityMasterRes = $this->db->query("select * from doc_city_rate_master where rate_id=".$rate_id." AND city_id =".$city_id." ");
				if ($cityMasterRes->num_rows() > 0) 
				{
					$data['rate_master']	= $cityMasterRes->result();	
				}
				
			}
		}
		
		if(!empty($data['rate_master']))
		{
        
			foreach ($data['rate_master'] as $row)
			{
				if($row->lower != 999999  )
				{
					$upper_chargee[] =  $row->upper;  
				}
				
				if($_REQUEST['weight'] >= $row->lower && $_REQUEST['weight'] <= $row->upper)
				{
					$sub_total =  $sub_total + $row->rate_amt; 
				}
				else
				{
					if($row->lower == 999999 )
					{
						$addtional_kg =  $row->rate_amt;  
						$addition =  $row->upper;  
					}
					else
					{
						$doc_sub_total =  $doc_sub_total + $row->rate_amt;
					}
					
					if($_REQUEST['weight'] > $row->upper)
					{
						$sub_total =  $sub_total + $row->rate_amt; 
					}
				}
			}
		}
		
		
		if($_REQUEST['weight'] > $addition && $addition != 0)
		{
			$weight  		= ceil($_REQUEST['weight']-$addition);
			$left_weight    = $weight;
			$amount		    = $left_weight * $addtional_kg;
			 
			$sub_total		= $doc_sub_total + $amount;
			$sub_total		= $sub_total * $_REQUEST['qty'];
 		}
		else
		{
			
			if($_REQUEST['weight'] > max($upper_chargee) )
			{
				$sub_total = $sub_total + $addtional_kg;
				$sub_total = $sub_total * $_REQUEST['qty'];
			}
			else
			{
				$sub_total = $sub_total * $_REQUEST['qty'];
			}
        }

		
		if($sstate_id == $rstate_id)
		{
			$cgst = ($sub_total *9/100);
			$sgst = ($sub_total *9/100);
			$igst = 0;
			
		}
		else
		{
			$cgst = 0;
			$sgst = 0;
			$igst = ($sub_total *18/100);
	
		}
                
        
		$data = array(
		'cgst' => $cgst,
		'sgst' => $sgst,
		'igst' => $igst,
		'sub_total' => $sub_total,
		'grand_total' => number_format($sub_total + $cgst + $sgst + $igst,2) 
		); 
        
		echo json_encode($data);
		exit;
    }
	
	public function getpod(){
		//print_r($_GET);
		$page = $_GET['draw'];
		$start = $_GET['start'];
		$length = $_GET['length'];
		$search = $_GET['search']['value'];

		$viewVerified = 2;

		$filterDataArr = $this->session->userdata("filter");

		$data = [];
        $user_id = $this->session->userdata("userId");
        $user_type = $this->session->userdata("userType");
		
		if($filterDataArr)
		{
			$from_date = $filterDataArr['from_date'];
			$to_date = $filterDataArr['to_date'];
			//$filter = $filterDataArr['filter'];

			$filterCond = " AND (tbl_booking.booking_date >= '$from_date' AND tbl_booking.booking_date <= '$to_date')";
			//$filterCond = " AND tbl_booking.booking_date BETWEEN '$from_date' AND '$to_date'";
		}
		
        if ($this->session->userdata("userName") != "") {
            if ($this->session->userdata("userType") == '1') {
                
                
                
                // $resAct = $this->db->query("select * from tbl_charges,tbl_booking,tbl_weight_details
                //   where tbl_charges.booking_id =tbl_booking.booking_id
				//   and tbl_weight_details.booking_id=tbl_charges.booking_id and booking_type = 1 AND tbl_booking.user_type !=5 order by tbl_booking.booking_date DESC ");

				if($search){
					$searchCond = " AND (tbl_booking.sender_name LIKE '%$search%' OR tbl_booking.reciever_name LIKE '%$search%' OR tbl_booking.reciever_city LIKE '%$search%' OR tbl_booking.forworder_name LIKE '%$search%' OR tbl_booking.mode_dispatch LIKE '%$search%' OR tbl_booking.verifier_name LIKE '%$search%' OR tbl_booking.user_type LIKE '%$search%' OR city.city LIKE '%$search%' )";
				}

                
				$qry1 = "SELECT tbl_booking.pod_no,tbl_booking.sender_name,tbl_booking.reciever_name,tbl_booking.reciever_city,tbl_booking.forworder_name,tbl_booking.booking_id,tbl_booking.booking_date,tbl_booking.isVerified,tbl_booking.mode_dispatch,tbl_booking.verifier_name,tbl_booking.user_type,tbl_booking.forwording_no  FROM tbl_booking  join tbl_charges ON tbl_booking.booking_id = tbl_charges.booking_id WHERE booking_type = 1 AND tbl_booking.user_type !=5 $filterCond $searchCond group by `pod_no` order by tbl_booking.booking_date DESC";
				
				//echo "qry1".$qry1;
				$queryttl = $this->db->query($qry1);
				$ttlresults = $queryttl->num_rows();

				$qry2 = "SELECT tbl_booking.pod_no,tbl_booking.sender_name,tbl_booking.reciever_name,tbl_booking.reciever_city,tbl_booking.forworder_name,tbl_booking.booking_id,tbl_booking.booking_date,tbl_booking.isVerified,tbl_booking.mode_dispatch,tbl_booking.verifier_name,tbl_booking.user_type, city.city,tbl_booking.forwording_no  FROM tbl_booking INNER join tbl_charges ON tbl_booking.booking_id = tbl_charges.booking_id left join city ON city.id = tbl_booking.reciever_city WHERE booking_type = 1 AND tbl_booking.user_type !=5 $filterCond $searchCond group by `pod_no` order by tbl_booking.booking_date DESC LIMIT $start, $length"; 
                
                //echo "qry2".$qry2;
                $resAct = $this->db->query($qry2);
				
                if ($resAct->num_rows() > 0) {
                    $allpoddata = $resAct->result_array();
                }
            } else {
                
                $where = '';
                if ($this->session->userdata("userType") == '4') {
                    $where = ' AND tbl_booking.user_id = '.$user_id;
                }
                

                
                $username = $this->session->userdata("userName");
                $whr = array('username' => $username);
                $res = $this->basic_operation_m->getAll('tbl_users', $whr);
                $branch_id = $res->row()->branch_id;

                $data = array();
                $whrAct = array('isactive' => 1, 'isdeleted' => 0);

                // $resAct = $this->db->query("select * from tbl_charges,tbl_booking,tbl_weight_details
                //   where tbl_charges.booking_id =tbl_booking.booking_id
				//   and tbl_weight_details.booking_id=tbl_charges.booking_id and tbl_booking.branch_id='$branch_id' and booking_type = 1 $where order by `tbl_booking`.`booking_date` DESC");

				if($search){
					$searchCond = " AND (tbl_booking.sender_name LIKE '%$search%' OR tbl_booking.reciever_name LIKE '%$search%' OR tbl_booking.reciever_city LIKE '%$search%' OR tbl_booking.forworder_name LIKE '%$search%' OR tbl_booking.mode_dispatch LIKE '%$search%' OR tbl_booking.verifier_name LIKE '%$search%' OR tbl_booking.user_type LIKE '%$search%' OR tbl_booking.pod_no LIKE '%$search%' OR city.city LIKE '%$search%' )";
				}
				
				$qry3 = "SELECT tbl_booking.pod_no,tbl_booking.sender_name,tbl_booking.reciever_name,tbl_booking.reciever_city,tbl_booking.forworder_name,tbl_booking.booking_id,tbl_booking.booking_date,tbl_booking.isVerified,tbl_booking.mode_dispatch,tbl_booking.verifier_name,tbl_booking.user_type, city.city,tbl_booking.forwording_no  FROM tbl_booking left join tbl_charges ON tbl_booking.booking_id = tbl_charges.booking_id left join city ON city.id = tbl_booking.reciever_city
								   WHERE booking_type = 1 $where $filterCond $searchCond group by `pod_no` order by tbl_booking.booking_date DESC";
				//echo "qry3=".$qry3;
				$queryttl = $this->db->query($qry3);
				$ttlresults = $queryttl->num_rows();
				
				
				$qry4 = "SELECT tbl_booking.pod_no,tbl_booking.sender_name,tbl_booking.reciever_name,tbl_booking.reciever_city,tbl_booking.forworder_name,tbl_booking.booking_id,tbl_booking.booking_date,tbl_booking.isVerified,tbl_booking.mode_dispatch,tbl_booking.verifier_name,tbl_booking.user_type,city.city,tbl_booking.forwording_no  FROM tbl_booking left join tbl_charges ON tbl_booking.booking_id = tbl_charges.booking_id left join city ON city.id = tbl_booking.reciever_city
                                   WHERE booking_type = 1 $where $filterCond $searchCond group by `pod_no` order by tbl_booking.booking_date DESC  LIMIT $start, $length";

				//echo "qry4=".$qry4;			   
                $resAct = $this->db->query($qry4);
                if ($resAct->num_rows() > 0) {
                    $allpoddata = $resAct->result_array();
                }
            }
		}
		
		
		// echo $this->db->last_query();

		// print_r($allpoddata); die;
		
		$dataArr = array();
		foreach($allpoddata as $value){
			$city_id= $value['reciever_city'];
			$resAct=$this->db->query("select * from city where id='$city_id'");
			$city_reciever= $resAct->row()->city;
			
		
			/* if($value['forworder_name'] == 'delhivery_c2c')
			{
				$serviceType = 'b2c';
				$number = $value['forwording_no'];
			}
			else
			{
				$serviceType = 'b2b';
				//echo '<br/>';
				$number = $value['booking_id'];
			} */
			$verifier_name = ' ';
			if ($value['isVerified'] == 1 && $viewVerified !=2) {
				$verifier_name = $value['verifier_name'];
			}

			$booking_id = $value['booking_id'];

			$btn1data = '';
			if ($value['isVerified'] == 0 && $viewVerified !=2) {
				$btn1data = '| <a href="'.base_url().'generatepod/verifyPod/'.$booking_id.'" class="btn btn-primary"><i class="glyphicon glyphicon-check"></i></a>';
			}
			if( $this->session->userdata("userType") == 1){
				$btn1data .= '|
				<a href="'.base_url().'generatepod/deletepod/'.$booking_id.'" class="btn btn-danger"><i class="glyphicon glyphicon-trash"></i></a>';
			}

			$actionbtn = '';
			if ($value['isVerified'] == 1 && $viewVerified !=2) {
				$actionbtn = $verifier_name.',';
			}

			$actionbtn .= '<a href="'.base_url().'generatepod/updatepod/'.$booking_id.'" class="btn btn-primary"><i class="glyphicon glyphicon-pencil"></i></a><br><br>
			<a href="'.base_url().'generatepod/printpod/'.$booking_id.'" class="btn btn-info"><i class="glyphicon glyphicon-print"></i></a>'.$btn1data;
					
			$dataArr[] = array(
				$value['pod_no'],
				$value['sender_name'],
				$value['reciever_name'],
				$city_reciever,
				$value['forwording_no'],
				ucfirst($value['forworder_name']),
				date('d-m-Y',strtotime($value['booking_date'])),
				$value['mode_dispatch'],
				$actionbtn

			);
		}

		$maindataArr = array(
			'draw'=>$page,
			'recordsTotal'=>$ttlresults,
			'recordsFiltered'=>$ttlresults,
			'data'=>$dataArr,
		);

        
        //print_r($this->db);
        //exit;
		echo json_encode($maindataArr);
	}
    
    public function unverified_shipment() 
	{
        $data = array();
        $user_id = $this->session->userdata("userId");
        $user_type = $this->session->userdata("userType");
        
		
            if ($this->session->userdata("userType") == '1') {
                $resAct = $this->db->query("select * from tbl_charges,tbl_booking,tbl_weight_details 
                  where tbl_charges.booking_id =tbl_booking.booking_id 
                  and tbl_weight_details.booking_id=tbl_charges.booking_id and tbl_booking.isVerified = 0 and booking_type = 1 AND tbl_booking.user_type !=5 order by booking_date DESC ");
                if ($resAct->num_rows() > 0) {
                    $data['allpoddata'] = $resAct->result_array();
                }
            } else {
                $where = '';
                if($this->session->userdata("userType") == '5') {
                    $where = ' AND tbl_booking.user_id = '.$user_id;
                }
                $username = $this->session->userdata("userName");
				
                $whr = array('username' => $username);
                $res = $this->basic_operation_m->getAll('tbl_users', $whr);
                $branch_id = $res->row()->branch_id;

                $data = array();
                $whrAct = array('isactive' => 1, 'isdeleted' => 0);

                $resAct = $this->db->query("select * from tbl_charges,tbl_booking,tbl_weight_details 
                  where tbl_charges.booking_id =tbl_booking.booking_id 
                  and tbl_weight_details.booking_id=tbl_charges.booking_id and tbl_booking.isVerified = 0 and tbl_booking.branch_id='$branch_id' and booking_type = 1 $where order by `tbl_booking`.`booking_id` DESC");
                if ($resAct->num_rows() > 0) {
                    $data['allpoddata'] = $resAct->result_array();
                }
            }
            $data['viewVerified'] = 0;
        
       $this->load->view('admin/shipment/view_shipment', $data);
    }
    
    public function verified_shipment() 
	{
        $data = array();
        $user_id = $this->session->userdata("userId");
        $user_type = $this->session->userdata("userType");
        
            if ($this->session->userdata("userType") == '1') {
                $resAct = $this->db->query("select * from tbl_charges,tbl_booking,tbl_weight_details 
                  where tbl_charges.booking_id =tbl_booking.booking_id 
                  and tbl_weight_details.booking_id=tbl_charges.booking_id and tbl_booking.isVerified = 1 and booking_type = 1 AND tbl_booking.user_type !=5 order by booking_date DESC ");
                if ($resAct->num_rows() > 0) {
                    $data['allpoddata'] = $resAct->result_array();
                }
            } else {
                $where = '';
                if($this->session->userdata("userType") == '5') {
                    $where = ' AND tbl_booking.user_id = '.$user_id;
                }
                $username = $this->session->userdata("userName");
                $whr = array('username' => $username);
                $res = $this->basic_operation_m->getAll('tbl_users', $whr);
                $branch_id = $res->row()->branch_id;

                $data = array();
                $whrAct = array('isactive' => 1, 'isdeleted' => 0);

                $resAct = $this->db->query("select * from tbl_charges,tbl_booking,tbl_weight_details 
                  where tbl_charges.booking_id =tbl_booking.booking_id 
                  and tbl_weight_details.booking_id=tbl_charges.booking_id and tbl_booking.isVerified = 1 and tbl_booking.branch_id='$branch_id' and booking_type = 1 $where order by `tbl_booking`.`booking_id` DESC");
                if ($resAct->num_rows() > 0) {
                    $data['allpoddata'] = $resAct->result_array();
                }
            }
            $data['viewVerified'] = 1;
         
       $this->load->view('admin/shipment/view_shipment', $data);
    }
    
    public function verifyPod(){
        $last = $this->uri->total_segments();
        $id = $this->uri->segment($last);
        if ($id != "") {
            $where = array('booking_id' => $id);
            $data = [
                'isVerified' => 1,
                'verifier_user_id' => $this->session->userdata("userId"),
                'verifier_name' => $this->session->userdata("userName"),
            ];
            $this->basic_operation_m->update('tbl_booking', $data, $where);
            $this->session->set_flashdata('success', 'Booking verified successfully');
        }
        
        redirect(base_url() . 'generatepod');
    }
    
    public function newbooking() 
	{
        $data = array();
        if ($this->session->userdata("userName") != "") {
			//echo $this->session->userdata("userType"); die;
            if ($this->session->userdata("userType") == '1') {
                $resAct = $this->db->query("select * from tbl_charges,tbl_booking,tbl_weight_details 
                  where tbl_charges.booking_id =tbl_booking.booking_id 
                  and tbl_weight_details.booking_id=tbl_charges.booking_id and booking_type = 2 order by booking_date DESC ");
                if ($resAct->num_rows() > 0) {
                    $data['allpoddata'] = $resAct->result_array();
                }
            } else {
                $username = $this->session->userdata("userName");
                $whr = array('username' => $username);
                $res = $this->basic_operation_m->getAll('tbl_users', $whr);
                $branch_id = $res->row()->branch_id;

                $data = array();
                $whrAct = array('isactive' => 1, 'isdeleted' => 0);

                $resAct = $this->db->query("select * from tbl_charges,tbl_booking,tbl_weight_details 
                  where tbl_charges.booking_id =tbl_booking.booking_id 
                  and tbl_weight_details.booking_id=tbl_charges.booking_id and tbl_booking.branch_id='$branch_id' and tbl_booking.booking_type = 2 order by `tbl_booking`.`booking_id` DESC");
                if ($resAct->num_rows() > 0) {
                    $data['allpoddata'] = $resAct->result_array();
				}
				//echo $this->db->last_query(); die;
            }
            //  print_r($data);
            $this->load->view('viewpodnew', $data);
            //  $this->load->view('printpod',$data);
        } else {
            redirect(base_url() . 'login');
        }
    }

    public function add_shipment() 
	{
		$data			= $this->data;
        $result 		= $this->db->query('select max(booking_id) AS id from tbl_booking')->row();
        $id 			= $result->id + 1;
        
		if (strlen($id) == 2) 
		{
            $id = 'EFS1000' . $id;
        }
		elseif (strlen($id) == 3) 
		{
            $id = 'EFS100' . $id;
        }
		elseif (strlen($id) == 1) 
		{
            $id = 'EFS10000' . $id;
        }
		elseif (strlen($id) == 4) 
		{
            $id = 'EFS10' . $id;
        }
		elseif (strlen($id) == 5) 
		{
            $id = 'EFS1' . $id;
        }
        
        $resAct 	= $this->db->query("select * from setting");
        $setting 	= $resAct->result();
		
        foreach ($setting as $value)
		{
            $data[$value->key] = $value->value;
		}
       
		
        $data['transfer_mode']		 	= $this->basic_operation_m->get_query_result('SELECT * FROM transfer_mode');
        $resAct 						= $this->basic_operation_m->getAll('city', '');
        if($resAct->num_rows() > 0) 
		{
            $data['cities'] 			= $resAct->result_array();
        }
        $user_id 						= $this->session->userdata("userId");
        $user_type 						= $this->session->userdata("userType");
		
		
        if($this->session->userdata("userType") == 5) 
		{
            $resAct 					= $this->basic_operation_m->getAll('tbl_customers', "customer_type = 'b2b' AND user_id=$user_id");
        } 
        elseif($this->session->userdata("userType") == 4)
        {
            $resAct 					= $this->basic_operation_m->getAll('tbl_customers', "user_id=$user_id");

        }
        else 
		{
            $resAct 					= $this->basic_operation_m->getAll('tbl_customers', '');
        }
        

        if($resAct->num_rows() > 0) 
		{
            $data['customers'] 			= $resAct->result_array();
        }
		$data['bid'] 					= $id;
		$this->load->view('admin/shipment/view_add_shipment', $data);
	}


	public function insert_shipment()
	{
		
		$all_Data 	= $this->input->post();
		if(!empty($all_Data)) 
		{
			if($all_Data['doc_type'] == 0)
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
		
			$whr = array('username' => $username);
			$res = $this->basic_operation_m->getAll('tbl_users', $whr);
			$branch_id = $res->row()->branch_id;

			$date = date('Y-m-d H:i:s',strtotime( $this->input->post('booking_date')));
			//booking details//
			$data = array(
				'booking_id' => "",
				'sender_name' => $this->input->post('sender_name'),
				'sender_address' => $this->input->post('sender_address'),
				'sender_city' => $this->input->post('sender_city'),
				'sender_pincode' => $this->input->post('sender_pincode'),
				'sender_contactno' => $this->input->post('sender_contactno'),
				'sender_gstno' => $this->input->post('sender_gstno'),
				'reciever_name' => $this->input->post('reciever_name'),
				'reciever_address' => $this->input->post('reciever_address'),
				'reciever_city' => $this->input->post('reciever_city'),
				'reciever_pincode' => $this->input->post('reciever_pincode'),
				'reciever_contact' => $this->input->post('reciever_contact'),
				'receiver_gstno' => $this->input->post('receiver_gstno'),
				'booking_date' =>$date,
				'delivery_date' => $this->input->post('delivery_date'),
				'mode_dispatch' => $this->input->post('mode_dispatch'),
				'dispatch_details' => $this->input->post('dispatch_details'),
				'insurance_value' => $this->input->post('insurance_value'),
				'eway_no'         => $this->input->post('eway_no'),
				'ref_no'         => $this->input->post('ref_no'),
				'contactperson_name' => $this->input->post('contactperson_name'),
				'forwording_no' => $this->input->post('forwording_no'),
				'forworder_name' => $this->input->post('forworder_name'),
				'pod_no' => $this->input->post('awn'),
				'branch_id' => $branch_id,
				'customer_id' => $this->input->post('customer_account_id'),
				'gst_charges' => $this->input->post('gst_charges'),
				'invoice_no' => $this->input->post('invoice_no'),
				'status' => ($this->input->post('status')) ? 1 : 0,
				'create_shipment' => ($this->input->post('create_shipment')) ? 1 : 0,
				'user_id' =>$user_id,
				'user_type' =>$user_type,
				'length_unit' =>$this->input->post('length_unit'),
				'rate' =>$this->input->post('rate'),
				'gst_rate' =>$this->input->post('gst_rate'),
				'delivery_type' =>$this->input->post('delivery_type'),
				'new_type' => $this->input->post('new_type'),
				'a_weight' => $this->input->post('chargable_weight'),
				'a_qty' => $this->input->post('no_of_pack'),
				'minimum_amount' =>$this->input->post('minimum_amount'),
				'doc_nondoc' =>$doc_nondoc,
				);

			$query = $this->basic_operation_m->insert('tbl_booking', $data);
			
				$lastid = $this->db->insert_id();
				if(empty($lastid))
				{
					
					$data['error'][] = "Pod Already Exist ". $this->input->post('awn').'<br>';	
				}
				else
					{			
			
				// include 'https://micslogistics.com/sendsms_php/trigger.php';
				$lastid = $this->db->insert_id();
				
				//charges Details/
				//total amount 
				$frieht = $this->input->post('frieht');
				$awb = $this->input->post('awn');
				$topay = $this->input->post('to_pay');
				$daoc = $this->input->post('dod_daoc');
				$loading = $this->input->post('loading');
				$packing = $this->input->post('packing');
				$handling = $this->input->post('handling');
				$oda = $this->input->post('oda');
				$fov = $this->input->post('fov');
				$fuel_subcharges = $this->input->post('fuel_subcharges');
				

				if($all_Data['doc_type'] == 0)
				{
					$data1 = array(
									'payment_id' => '',
									'booking_id' => $lastid,
									'minimum_amount' => $this->input->post('minimum_amount'),
									'amount' => $this->input->post('sub_total'),
									'frieht' => $this->input->post('sub_total'),
									'awb' => $this->input->post('awn'),
									'to_pay' => $this->input->post('to_pay'),
									'dod_daoc' => $this->input->post('dod_daoc'),
									'loading' => $this->input->post('loading'),
									'packing' => $this->input->post('packing'),
									'handling' => $this->input->post('handling'),
									'oda' => $this->input->post('oda'),
									'fov' => $this->input->post('fov'),
									'sub_total' => $this->input->post('sub_total'),
									'fuel_subcharges' => $this->input->post('fuel_subcharges'),
									'apmt' => $this->input->post('apmt_delivery'),
									'IGST' => $this->input->post('igst'),
									'CGST' => $this->input->post('cgst'),
									'SGST' => $this->input->post('sgst'),
									'total_amount' => $this->input->post('grand_total'),
									'demurrage' => '0',
									'cod' => 0,
									'other_charges' => $this->input->post('other_charges')
									);
				}
				else
				{
				
					$data1 = array(
									'payment_id' => '',
									'booking_id' => $lastid,
									'minimum_amount' => $this->input->post('minimum_amount'),
									'amount' => $this->input->post('amount'),
									'frieht' => $this->input->post('frieht'),
									'awb' => $this->input->post('awn'),
									'to_pay' => $this->input->post('to_pay'),
									'dod_daoc' => $this->input->post('dod_daoc'),
									'loading' => $this->input->post('loading'),
									'packing' => $this->input->post('packing'),
									'handling' => $this->input->post('handling'),
									'oda' => $this->input->post('oda'),
									'fov' => $this->input->post('fov'),
									'sub_total' => $this->input->post('sub_total'),
									'fuel_subcharges' => $this->input->post('fuel_subcharges'),
									'apmt' => $this->input->post('apmt_delivery'),
									'IGST' => $this->input->post('igst'),
									'CGST' => $this->input->post('cgst'),
									'SGST' => $this->input->post('sgst'),
									'total_amount' => $this->input->post('grand_total'),
									'demurrage' => 0,
									'cod' => 0,
									'other_charges' => $this->input->post('other_charges')
									);
				}
			   
				
				$data2 = array(
					'weight_details_id' => '',
					'booking_id' => $lastid,
					'actual_weight' => $this->input->post('actual_weight'),
					'valumetric_weight' => $this->input->post('valumetric_weight'),
					'length' => $this->input->post('length'),
					'breath' => $this->input->post('breath'),
					'height' => $this->input->post('height'),
					'one_cft_kg' => $this->input->post('one_cft_kg'),
					'chargable_weight' => $this->input->post('chargable_weight'),
					'per_box_weight' => $this->input->post('per_box_weight'),
					'rate' => $this->input->post('rate'),
					'rate_type' => $this->input->post('rate_type'),
					'rate_pack' => $this->input->post('rate_pack'),
					'no_of_pack' => $this->input->post('no_of_pack'),
					'type_of_pack' => $this->input->post('type_of_pack'),
					'special_instruction' => $this->input->post('special_instruction'),
					'actual_weight_detail' => json_encode($this->input->post('actual_weight_detail[]')),
					'valumetric_weight_detail' => json_encode($this->input->post('valumetric_weight_detail[]')),
					'length_detail' => json_encode($this->input->post('length_detail[]')),
					'breath_detail' => json_encode($this->input->post('breath_detail[]')),
					'height_detail' => json_encode($this->input->post('height_detail[]')),
					'one_cft_kg_detail' => json_encode($this->input->post('one_cft_kg_detail[]')),
					'no_pack_detail' => json_encode($this->input->post('no_pack_detail[]')),
					'per_box_weight_detail' =>json_encode($this->input->post('per_box_weight_detail[]')),
					'roundoff_type' =>$this->input->post('roundoff_type')
					);
				
				$query1 = $this->basic_operation_m->insert('tbl_charges', $data1);
				
				$query2 = $this->basic_operation_m->insert('tbl_weight_details', $data2);
				
				
				$username = $this->session->userdata("userName");
				$whr = array('username' => $username);
				$res = $this->basic_operation_m->getAll('tbl_users', $whr);
				$branch_id = $res->row()->branch_id;
				
				$whr = array('branch_id' => $branch_id);
				$res = $this->basic_operation_m->getAll('tbl_branch', $whr);
				$branch_name = $res->row()->branch_name;
				
				
				
				
				$whr = array('booking_id' => $lastid);
				$res = $this->basic_operation_m->getAll('tbl_booking', $whr);
				$podno = $res->row()->pod_no;
				$customerid= $res->row()->customer_id;
				$data3 = array('id' => '',
					'pod_no' => $podno,
					'status' => 'booked',
					'branch_name' => $branch_name,
					'tracking_date' => $date,
					'booking_id' => $lastid,
					'forworder_name' => $data['forworder_name'],
					'forwording_no' => $data['forwording_no'],
					'is_spoton' => ($data['forworder_name'] == 'spoton_service') ? 1 : 0,
					'is_delhivery_b2b' => ($data['forworder_name'] == 'delhivery_b2b') ? 1 : 0,
					'is_delhivery_c2c' => ($data['forworder_name'] == 'delhivery_c2c') ? 1 : 0
				);
				
				$result3 = $this->basic_operation_m->insert('tbl_tracking', $data3);
				
				$whr = array('customer_id'=>$customerid);
				$res=$this->basic_operation_m->getAll('tbl_customers',$whr);
				$email= $res->row()->email;
				$message='Your Shipment '.$podno.' status:Boked  At Location: '.$branch_name;
			   // $this->sendemail($email,$message);
				
				// sendsms( $this->input->post('reciever_contact'), 'Dear '.$this->input->post('reciever_name').' your shipments is booked in MICS Logistics, AWB No. is '.$this->input->post('awn').' from '.$this->input->post('sender_city').' to '.$this->input->post('reciever_city').' .You can track  www.micslogistics.com' );
				// Pass api key and senderid of your account
			// $sendsms = new Sendsms("https://micslogistics.com/", "Ad00583e500340cf93d5f4538197f3bd3", "XXXXXX");
				
				
				if ($this->db->affected_rows() > 0) 
				{
					$whr1 = array('id' => $this->input->post('sender_city'));
					$res1 = $this->basic_operation_m->getAll('city', $whr1);
					$sendercity1 = $res1->row()->city;
					$this->db->last_query();
					
					$whr2 = array('id' => $this->input->post('reciever_city'));
					$res2 = $this->basic_operation_m->getAll('city', $whr2);
					$reciever_city2 = $res2->row()->city;
					$this->db->last_query();
					
					$message1 = 'Dear '.$this->input->post('reciever_name').' your shipments is booked in MICS Logistics, AWB No. is '.$this->input->post('awn').' from '.$sendercity1.' to '.$reciever_city2.' .You can track  www.micslogistics.com';
					$mobile_no = $this->input->post('reciever_contact');
					
					
					//$main = new MainSms();
					//$main->call($mobile_no,$message1);
					$data['message'] = "Data added successfull";
				}
				else 
				{
					$data['message'] = "Failed to Submit";
				}
				
				if ($data['create_shipment'] == 1 && $data['forworder_name'] == 'spoton_service') 
				{
					$this->spotontracking->postSpotonApiData($lastid);
				}
				
				if ($data['create_shipment'] == 1 && $data['forworder_name'] == 'delhivery_b2b') 
				{
					$this->delhivery->postApiData($lastid);
				}
				
				/*************Delhivery C2C**************************/
				
				if($data['create_shipment'] == 1 && $data['forworder_name'] == 'delhivery_c2c')
				{
					$createShipmentResponse = $this->delhivery->create_package_order($lastid);
				}
				/**************************Delhivery C2C*************************/
			}
			
			redirect('admin/view-shipment');
		}
	}

	public function bulk_upload() 
	{
		
		$data = [];
		$user_id = $this->session->userdata("userId");
		$user_type = $this->session->userdata("userType");
		
		 if ($this->session->userdata("userType") == 5) {
				$resAct = $this->basic_operation_m->getAll('tbl_customers', "customer_type = 'b2b' AND user_id=$user_id");
			} 
			else if($this->session->userdata("userType") == 4)
			{
				$resAct = $this->basic_operation_m->getAll('tbl_customers', "user_id=$user_id");

			}
			else {
				$resAct = $this->basic_operation_m->getAll('tbl_customers', '');
			}
			
	  
		if ($resAct->num_rows() > 0) {
			$data['customers'] = $resAct->result_array();
		}
		
		if ($this->input->post('submit')) {
			$path = 'uploads/';
			$this->load->library("excel");
			$config['upload_path'] = $path;
			$config['allowed_types'] = 'xlsx|xls|csv';
			$config['remove_spaces'] = TRUE;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);            
			if (!$this->upload->do_upload('uploadFile')) {
				$error = array('error' => $this->upload->display_errors());
			} else {
				$data = array('upload_data' => $this->upload->data());
			}
			if(empty($error)) {
			  if (!empty($data['upload_data']['file_name'])) {
				$import_xls_file = $data['upload_data']['file_name'];
			} else {
				$import_xls_file = 0;
			}
			$inputFileName = $path . $import_xls_file;
			
			$username = $this->session->userdata("userName");
			$whr = array('username' => $username);
			$res = $this->basic_operation_m->getAll('tbl_users', $whr);
			$branch_id = $res->row()->branch_id;
			
			$whr = array('branch_id' => $branch_id);
			$res = $this->basic_operation_m->getAll('tbl_branch', $whr);
			$branch_name = $res->row()->branch_name;
			
			try {
				
				$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objPHPExcel = $objReader->load($inputFileName);
				$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
				$flag = true;
				$i = 0;
				foreach ($allDataInSheet as $value) 
				{
					$i++;
					if ($i == 1) continue;
				 
					$result 	= $this->db->query('select max(booking_id) AS id from tbl_booking')->row();
					$id 		= $result->id + 1;
					   
					if (strlen($id) == 2) {
						$id = 'EFS1000' . $id;
					} else if (strlen($id) == 3) {
						$id = 'EFS100' . $id;
					} else if (strlen($id) == 1) {
						$id = 'EFS10000' . $id;
					} else if (strlen($id) == 4) {
						$id = 'EFS10' . $id;
					} else if (strlen($id) == 5) {
						$id = 'EFS1' . $id;
					}
					
				  
		
			    	$date = date('Y-m-d H:i:s');
					//$customerId = $this->input->post('customer_account_id');
					$customerId = $value['U'];
					
					$customerCondtion = array('cid' => $customerId);
					$customerRes = $this->basic_operation_m->getAll('tbl_customers', $customerCondtion);
					$customerId			= $customerRes->row()->customer_id;
					
					$pincode 					=  $this->basic_operation_m->get_table_row('pincode',"pin_code = '".$value['C']."'");
				
					$receiverCityDataCondition  = ['city' => $pincode->city]; 
				   
					$receiverCityRes = $this->basic_operation_m->getAll('city', $receiverCityDataCondition);
					
				
					//print_r($receiverCityRes->row());exit;
					
					if(empty($receiverCityRes->row())) 
					{
						$data['error'][] = "Invalid city ".$value['C']."<br/>";
						//echo $this->db->last_query();
						echo "Invalid city ".$value['C']."<br/>";
						//$query = $this->basic_operation_m->insert('temp_pincode', array('pincode'=>$value['C']));
						
				
						continue;
					}
					
					$chargeble_weight 			= $value['M'];
					$value['M']					= ceil($value['M']);
					$chargeAbleWeight 			= ($value['L'] > $value['M']) ? $value['L'] : $value['M'];
					
				
					
					
					$rateMaster = $this->getBulkUploadRateMasterDetails($customerId, $customerRes->row()->state,$customerRes->row()->city, $receiverCityRes->row()->state_id,$receiverCityRes->row()->id, $value['N'],$value['K'],$chargeAbleWeight,$value['T']);
					
		
				  
					if(empty($rateMaster['rate_master']->rate_master_id)) {
						$data['error'][] = "Rate master not set  for city ".$value['C']."<br/>";
						continue;
					}
					
				
					
					$cft 				= $rateMaster['rate_master']->cft;
					$rate 				= $rateMaster['rate_master']->rate;
					$dod_doac 			= $rateMaster['rate_master']->dod_doac;
					$loading_unloading 	= $rateMaster['rate_master']->loading_unloading;
					$fov 				= $rateMaster['rate_master']->fov;
					$fuel_charges 		= $rateMaster['rate_master']->fuel_charges;
					$min_freight 		= $rateMaster['rate_master']->min_freight;
					$min_freight 		= $rateMaster['rate_master']->min_freight;
					$gst_rate 			= $rateMaster['rate_master']->gst_rate;
					$weight_range 		= $rateMaster['rate_master']->weight_range;
					$fc_type			= $rateMaster['rate_master']->fc_type;
					
					
					
					if($value['T'] == 'doc')
					{
						$doc_nondoc			= 'Document';
					}
					else
					{
						$doc_nondoc			= 'Non Document';
					}
			
					//booking details//
					
					$bookingData = array(
						'booking_id' => "",
						'sender_name' => $customerRes->row()->customer_name,
						'sender_address' => $customerRes->row()->address,
						'sender_city' => $customerRes->row()->city,
						'sender_pincode' => $customerRes->row()->pincode,
						'sender_contactno' => $customerRes->row()->phone,
						'sender_gstno' => $customerRes->row()->gstno,
						'reciever_name' => $value['A'],
						'reciever_address' => $value['B'],
						'reciever_city' => $receiverCityRes->row()->id,
						'reciever_pincode' => $value['C'],
						'reciever_contact' => $value['D'],
						'receiver_gstno' => ($value['E'])?$value['E']:'',
						'booking_date' => !empty($value['S']) ? date('Y-m-d H:i:s', strtotime($value['S'])) : $date,
						'delivery_date' => $rateMaster['rate_master']->eod,
						'mode_dispatch' => ucfirst($value['N']),
						'dispatch_details' => strtolower($value['O']),
						'insurance_value' => $value['J'],
						'eway_no'         => !empty($value['F']) ? $value['F'] : '',
						'ref_no'         => !empty($value['G']) ? $value['G'] : '',
						'contactperson_name' => !empty($value['H']) ? $value['H'] : '',
						'forwording_no' => !empty($value['P']) ? $value['P'] : '',
						'forworder_name' => !empty($value['Q']) ? $value['Q'] : '',
						'pod_no' => !empty($value['R']) ? $value['R'] : $id,
						'branch_id' => $branch_id,
						'customer_id' => $customerId,
						'gst_charges' => 1,
						'invoice_no' => $value['I'],
						'status' => 1,
						'create_shipment' => 1,
						'user_id' =>$user_id,
						'user_type' =>$user_type,
						'length_unit' =>'cm',
						'rate' => $rate,
						'gst_rate' =>$gst_rate,
						'delivery_type' =>$this->input->post('delivery_type'),
						'minimum_amount' =>$this->input->post('minimum_amount'), 
						'a_weight' => $chargeAbleWeight,
						'a_qty' => $value['K'],
						'doc_nondoc' =>$doc_nondoc, 
						);
					

				$query = $this->basic_operation_m->insert('tbl_booking', $bookingData);
				$lastid = $this->db->insert_id();
				if(empty($lastid))
				{
					$podd = !empty($value['R']) ? $value['R'] : $id;
					$data['error'][] = "Pod Already Exist ".$podd.'<br>';	
				}
				else
				{
					$data['success'] = "Pod Successfully Inserted";	
					$tracking = array(
						'id' => "",
						'pod_no' => !empty($value['R']) ? $value['R'] : $id,
						'status' =>'booked',
						'branch_name' => $branch_name,
						'booking_id' => $lastid,
						'forwording_no' => !empty($value['P']) ? $value['P'] : '',
						'forworder_name' => !empty($value['Q']) ? $value['Q'] : '',
						'tracking_date' => !empty($value['S']) ? date('Y-m-d H:i:s', strtotime($value['S'])) : $date,
						);
						
					$query = $this->basic_operation_m->insert('tbl_tracking', $tracking);
				
				
				
				   // echo ">>".$last_id;
					//print_r($this->db); exit;
					
					
					
					
					//print_r($this->db); exit;
					
					$dbError = $this->db->error();
					if(!empty($dbError['message'])) {
						$data['error'][] = $dbError['message'].' <br/>'; 
						continue;
					}
					
					$chargeble_weight 			= $value['M'];
					$value['M']					= ceil($value['M']);
					$chargeAbleWeight 			= ($value['L'] > $value['M']) ? $value['L'] : $value['M'];
					$chargeAbleWight 			= $weight_range > $chargeAbleWeight ? $weight_range : $chargeAbleWeight;
					$frieght 					= $chargeAbleWight * $rate;
					
					$frieght 					= $frieght < $min_freight ? $min_freight : $frieght;
					
					if($fov > '0' && $value['J'] > '0' ) 
					{
						$fov = (($fov * $value['J'])/100);
					}
					else 
					{
						$fov = 0;
					}
					
					$amount =  $frieght + $dod_doac + $fov; 
					
					if (!empty($fc_type)) 
					{
							if($fc_type == 'freight')
							{
							   $fuel_charges = (($frieght * $fuel_charges) / 100);
							}
							else if($fc_type == 'total')
							{
								$fuel_charges = (($amount * $fuel_charges) / 100);
							}    
						}
						else
						{
							$fuel_charges = 0;
						}
					
					$total_charges = $amount + $fuel_charges;
					if($min_freight > $total_charges || $total_charges == '')
					{
						$total_charge = $min_freight;
					}
					else 
					{
						$total_charge = ($amount) + $fuel_charges;
					}
					$gst = $gst_rate / 2;
					$cgst = $sgst = $igst = 0;
					$finalAmount = 0; 
					if (!empty($customerRes->row()->gstno)) 
					{
						$stateCode = substr($customerRes->row()->gstno ,0, 2);
						if($stateCode == '13')
						{
							$sgst = $total_charge * $gst / 100;
							$cgst = $total_charge * $gst / 100;
							$finalAmount = $total_charge + $sgst + $cgst;
							
						}
						else 
						{
							$igst = ($total_charge * $gst_rate) / 100;
							$finalAmount = $total_charge + $igst;
						}
					}
					$totgst =  $sgst + $cgst;
				 
					$parcel_type		= $value['T'];
					if($parcel_type == 'doc')  // this is for document rate 
					{
					
						//charges Details/
						$data1 = array(
							'payment_id' => '',
							'booking_id' => $lastid,
							'amount' => $rateMaster['rate_master']->rate,
							'frieht' => $rateMaster['rate_master']->rate,
							'awb' => !empty($value['R']) ? $value['R'] : $id,
							'to_pay' => 0,
							'dod_daoc' => $rateMaster['rate_master']->dod_doac,
							'loading' => 0,
							'packing' => 0,
							'handling' => 0,
							'oda' => 0,
							'fov' => 0,
							'sub_total' => $rateMaster['rate_master']->rate,
							'fuel_subcharges' => 0,
							'apmt' => '',
							'IGST' => $rateMaster['rate_master']->igst,
							'CGST' => $rateMaster['rate_master']->cgst,
							'SGST' => $rateMaster['rate_master']->sgst,
							'total_amount' => number_format($rateMaster['rate_master']->rate + $rateMaster['rate_master']->cgst + $rateMaster['rate_master']->sgst + $rateMaster['rate_master']->igst,2),
							'demurrage' => 0,
							'cod' => '',
							'other_charges' => 0
							);
					}
					else
					{
						//charges Details/
						$data1 = array(
							'payment_id' => '',
							'booking_id' => $lastid,
							'amount' => $amount,
							'frieht' => $frieght,
							'awb' => !empty($value['R']) ? $value['R'] : $id,
							'to_pay' => 0,
							'dod_daoc' => $dod_doac,
							'loading' => $loading_unloading,
							'packing' => 0,
							'handling' => 0,
							'oda' => 0,
							'fov' => $fov,
							'sub_total' => $total_charge,
							'fuel_subcharges' => $fuel_charges,
							'apmt' => '',
							'IGST' => $igst,
							'CGST' => $cgst,
							'SGST' => $sgst,
							'total_amount' => $finalAmount,
							'demurrage' => 0,
							'cod' => '',
							'other_charges' => 0
							);
					}
					
					   
					$data2 = array(
						'weight_details_id' => '',
						'booking_id' => $lastid,
						'actual_weight' => $value['L'],
						'valumetric_weight' => $chargeble_weight,
						'length' => $value['O'],
						'breath' => $value['P'],
						'height' => $value['Q'],
						'one_cft_kg' => ($cft)?$cft:1,
						'chargable_weight' => $chargeAbleWight,
						'per_box_weight' => $value['L'],
						'rate' => $rate,
						'rate_type' => 'weight',
						'rate_pack' => '',
						'no_of_pack' => $value['K'],
						'type_of_pack' => '',
						'special_instruction' => '',
						'actual_weight_detail' => json_encode([$value['L']]),
						'valumetric_weight_detail' => json_encode([$value['M']]),
						'no_pack_detail' => json_encode([$value['K']]),
						'per_box_weight_detail' =>json_encode([$value['L']]),
						);
						
						
					   

					$query1 = $this->basic_operation_m->insert('tbl_charges', $data1);
					
					$query2 = $this->basic_operation_m->insert('tbl_weight_details', $data2);
					
				
					$whr = array('booking_id' => $lastid);
					$res = $this->basic_operation_m->getAll('tbl_booking', $whr);
					$podno = $res->row()->pod_no;
					$customerid= $res->row()->customer_id;
				   /*  $data3 = array('id' => '',
						'pod_no' => $podno,
						'status' => 'booked',
						'branch_name' => $branch_name,
						'tracking_date' => date('Y-m-d H:i:s'),
						'booking_id' => $lastid,
						'is_spoton' =>  0
						);
						 $result3 = $this->basic_operation_m->insert('tbl_tracking', $data3); */
						
				}
				  $i++;
					if($lastid > 0) 
					{
						//$data['success'][] = 'Data uploaded successfully for booking Id '.$lastid.'<br/>';
					}    
				} 
		  } catch (Exception $e) {
			   die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
						. '": ' .$e->getMessage());
			}
		  } else {
			 $data['error'][] = $error['error'];
			}
		}
		
		
		
		$this->load->view('admin/shipment/view_bluck_uploads_hipment', $data);
	}

	public function addpodnew() 
	{
		   
			$result = $this->db->query('select max(booking_id) AS id from tbl_booking')->row();
			//print_r($result);die;
			$id = $result->id + 1;
			if (strlen($id) == 2) {
				$id = 'AWN00' . $id;
			} else if (strlen($id) == 3) {
				$id = 'AWN0' . $id;
			} else if (strlen($id) == 1) {
				$id = 'AWN000' . $id;
			} else if (strlen($id) == 4) {
				$id = 'AWN' . $id;
			}
			$data['message'] = "";
			
			$resAct = $this->db->query("select * from setting");
			$setting = $resAct->result();
			foreach ($setting as $value):
				$data[$value->key] = $value->value;
			endforeach;
			$resAct = $this->basic_operation_m->getAll('tbl_city', '');
			if ($resAct->num_rows() > 0) {
				$data['cities'] = $resAct->result_array();
			}
			$resAct = $this->basic_operation_m->getAll('tbl_customers', '');

			if ($resAct->num_rows() > 0) {
				$data['customers'] = $resAct->result_array();
			}
			
			$user_id = $this->session->userdata("userId");
			$user_type = $this->session->userdata("userType");

			if (isset($_POST['submit'])) {
				$username = $this->session->userdata("userName");
				$whr = array('username' => $username);
				$res = $this->basic_operation_m->getAll('tbl_users', $whr);
				$branch_id = $res->row()->branch_id;

				$date = date('Y-m-d H:i:s',strtotime( $this->input->post('booking_date')));
				//booking details//
				$data = array(
					'booking_id' => "",
					'sender_name' => $this->input->post('sender_name'),
					'sender_address' => $this->input->post('sender_address'),
					'sender_city' => $this->input->post('sender_city'),
					'sender_pincode' => $this->input->post('sender_pincode'),
					'sender_contactno' => $this->input->post('sender_contactno'),
					'sender_gstno' => $this->input->post('sender_gstno'),
					'reciever_name' => $this->input->post('reciever_name'),
					'reciever_address' => $this->input->post('reciever_address'),
					'reciever_city' => $this->input->post('reciever_city'),
					'reciever_pincode' => $this->input->post('reciever_pincode'),
					'reciever_contact' => $this->input->post('reciever_contact'),
					'receiver_gstno' => $this->input->post('receiver_gstno'),
					'booking_date' =>$date,
					'delivery_date' => $this->input->post('delivery_date'),
					'mode_dispatch' => $this->input->post('mode_dispatch'),
					'dispatch_details' => $this->input->post('dispatch_details'),
					'insurance_value' => $this->input->post('insurace_value'),
					'forwording_no' => $this->input->post('forwording_no'),
					'forworder_name' => $this->input->post('forworder_name'),
					'pod_no' => $this->input->post('awn'),
					'branch_id' => $branch_id,
					'customer_id' => $this->input->post('customer_account_id'),
					'gst_charges' => $this->input->post('gst_charges'),
					'status' => ($this->input->post('status')) ? 1 : 0,
					'booking_type' => 2,
					'user_id' =>$user_id,
					'user_type' =>$user_type,
					);
			   // print_r($data);die;
		$query = $this->basic_operation_m->insert('tbl_booking', $data);
		// echo $this->db->last_query();die;
		$lastid = $this->db->insert_id();
		
					//charges Details/
					//total amount 
		$frieht = $this->input->post('frieht');
		$awb = $this->input->post('awn');
		$topay = $this->input->post('to_pay');
		$daoc = $this->input->post('dod_daoc');
		$loading = $this->input->post('loading');
		$packing = $this->input->post('packing');
		$handling = $this->input->post('handling');
		$oda = $this->input->post('oda');
		$insurance = $this->input->post('insurance');
		$fuel_subcharges = $this->input->post('fuel_subcharges');
		$data1 = array(
			'payment_id' => '',
			'booking_id' => $lastid,
			'amount' => $this->input->post('amount'),
			'frieht' => $this->input->post('frieht'),
			'awb' => $this->input->post('awn'),
			'to_pay' => $this->input->post('to_pay'),
			'dod_daoc' => $this->input->post('dod_daoc'),
			'loading' => $this->input->post('loading'),
			'packing' => $this->input->post('packing'),
			'handling' => $this->input->post('handling'),
			'oda' => $this->input->post('oda'),
			'insurance' => $this->input->post('insurance'),
			'fuel_subcharges' => $this->input->post('fuel_subcharges'),
						//'service_tax'=>$this->input->post('service_tax'),
			'IGST' => $this->input->post('igst'),
			'CGST' => $this->input->post('cgst'),
			'SGST' => $this->input->post('sgst'),
			'total_amount' => $frieht,
			);
					//print_r($data1[2]);
					//  weight details
		$length = $this->input->post('length');
		$breath = $this->input->post('breath');
		$height = $this->input->post('height');
		$no_of_pack = $this->input->post('no_of_pack');
		if($no_of_pack == ''){
			$no_of_pack = 1;
		}
		
		$one_cft_kg = $this->input->post('one_cft_kg');
		if($one_cft_kg == ''){
			$one_cft_kg = 1;
		}
		
		
		$valumetric = round( ( ($length * $breath * $height) / 28000 ) * $one_cft_kg * $no_of_pack);
		$chargable_weight = 0;
		if($valumetric > 0){
			$chargable_weight = $valumetric;
		}
		
		
		$data2 = array(
			'weight_details_id' => '',
			'booking_id' => $lastid,
			'actual_weight' => $this->input->post('actual_weight'),
			'valumetric_weight' => $this->input->post('valumetric_weight'),
			'length' => $this->input->post('length'),
			'breath' => $this->input->post('breath'),
			'height' => $this->input->post('height'),
			'one_cft_kg' => $this->input->post('one_cft_kg'),
			'chargable_weight' => $this->input->post('chargable_weight'),
			'rate' => $this->input->post('rate'),
			'rate_type' => $this->input->post('rate_type'),
			'rate_pack' => $this->input->post('rate_pack'),
			'no_of_pack' => $this->input->post('no_of_pack'),
			'type_of_pack' => $this->input->post('type_of_pack'),
			'special_instruction' => $this->input->post('special_instruction'),
			);
		
		$query1 = $this->basic_operation_m->insert('tbl_charges', $data1);
		//echo $this->db->last_query(); die;
		
		$query2 = $this->basic_operation_m->insert('tbl_weight_details', $data2);
		
		
		$username = $this->session->userdata("userName");
		$whr = array('username' => $username);
		$res = $this->basic_operation_m->getAll('tbl_users', $whr);
		$branch_id = $res->row()->branch_id;
		
		$whr = array('branch_id' => $branch_id);
		$res = $this->basic_operation_m->getAll('tbl_branch', $whr);
		$branch_name = $res->row()->branch_name;
		
		//	$query2=$this->basic_operation_m->insert('tbl_weight_details',$data2);
		
		
		$whr = array('booking_id' => $lastid);
		$res = $this->basic_operation_m->getAll('tbl_booking', $whr);
		$podno = $res->row()->pod_no;
		$customerid= $res->row()->customer_id;
		$data3 = array('id' => '',
			'pod_no' => $podno,
			'status' => 'booked',
			'branch_name' => $branch_name,
			'tracking_date' => $date,
			);
		
		$result3 = $this->basic_operation_m->insert('tbl_tracking', $data3);
		
		
		
		// $whr = array('customer_id'=>$customerid);
		// $res=$this->basic_operation_m->getAll('tbl_customers',$whr);
		// $email= $res->row()->email;
		// $message='Your Shipment '.$podno.' status:Boked  At Location: '.$branch_name;
		// $this->sendemail($email,$message);
		
		
		if ($this->db->affected_rows() > 0) {
			$data['message'] = "Data added successfull";
		} else {
			$data['message'] = "Failed to Submit";
		}
		redirect(base_url() . 'generatepod/newbooking');
		}
		$data['bid'] = $id;
		$this->load->view('admin/urgent_shippment/addpodnew', $data);
	}

	public function sendemail($to,$message)
	{

	   $this->load->library('email');
	   $config['protocol'] = 'smtp';
		   // $config['mailpath'] = '/usr/sbin/sendmail';

	   $config['smtp_host'] = 'mail.rajcargo.net';
	   $config['smtp_user'] = 'info@rajcargo.net';
	   $config['smtp_pass'] = '41]wHpOZBnq}';
	   $config['smtp_port'] = 25;
	   $config['charset'] = 'iso-8859-1';
	   $config['wordwrap'] = TRUE;

	//   $this->email->initialize($config);

	  // $this->email->from('info@rajcargo.net', 'Rajcargo Admin');
	   //$this->email->to($to); 


	   //$this->email->subject('Shipment Update');
	   //$this->email->message($message);	

	  // $this->email->send();


	}

	public function edit_shipment($id) 
	{
		$data['message'] = "";
		 $data['transfer_mode']		 	= $this->basic_operation_m->get_query_result('SELECT * FROM transfer_mode');
		$resAct = $this->basic_operation_m->getAll('city', '');

		if ($resAct->num_rows() > 0) {
			$data['cities'] = $resAct->result_array();
		}
		
		$whr = array('booking_id' => $id);
		$user_id = $this->session->userdata("userId");
		$user_type = $this->session->userdata("userType");
		if ($id != "") {
			$res = $this->basic_operation_m->getAll('tbl_booking', $whr);

			if ($res->num_rows() > 0) {
				$data['booking'] = $res->result_array();
			}
			$resAct = $this->basic_operation_m->getAll('tbl_charges', $whr);
			if ($resAct->num_rows() > 0) {
				$data['charges'] = $resAct->result_array();
			}

			$resAct = $this->basic_operation_m->getAll('tbl_weight_details', $whr);
			if ($resAct->num_rows() > 0) {
				$data['weight'] = $resAct->result_array();
			}
			
			 
				$resAct = $this->basic_operation_m->getAll('tbl_customers', '');
			
			

			if ($resAct->num_rows() > 0) {
				$data['customers'] = $resAct->result_array();
			}
		}
	   
		
	   $this->load->view('admin/shipment/view_edit_shipment', $data);
	}
	
	public function update_shipment($id)
	{
		$all_data 		= $this->input->post();
		if (!empty($all_data)) 
		{
			$whr = array('booking_id' => $id);
			$date = date('Y-m-d H:i:s',strtotime($this->input->post('booking_date')));
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
			
			$data = array(
				'sender_name' => $this->input->post('sender_name'),
				'sender_address' => $this->input->post('sender_address'),
				'sender_city' => $this->input->post('sender_city'),
				'sender_pincode' => $this->input->post('sender_pincode'),
				'sender_contactno' => $this->input->post('sender_contactno'),
				'sender_gstno' => $this->input->post('sender_gstno'),
				'reciever_name' => $this->input->post('reciever_name'),
				'reciever_address' => $this->input->post('reciever_address'),
				'reciever_city' => $this->input->post('reciever_city'),
				'reciever_pincode' => $this->input->post('reciever_pincode'),
				'reciever_contact' => $this->input->post('reciever_contact'),
				'receiver_gstno' => $this->input->post('receiver_gstno'),
				'booking_date' => $date,
				'delivery_date' => $this->input->post('delivery_date'),
				'mode_dispatch' => $this->input->post('mode_dispatch'),
				'dispatch_details' => $this->input->post('dispatch_details'),
				'insurance_value' => $this->input->post('insurance_value'),
				'eway_no'         => $this->input->post('eway_no'),
				'ref_no'         => $this->input->post('ref_no'),
				'contactperson_name' => $this->input->post('contactperson_name'),
				'forwording_no' => $this->input->post('forwording_no'),
				'forworder_name' => $this->input->post('forworder_name'),
				'gst_charges' => $this->input->post('gst_charges'),
				'invoice_no' => $this->input->post('invoice_no'),
				 'create_shipment' => ($this->input->post('create_shipment')) ? 1 : 0,
				'status' => ($this->input->post('status')) ? 1 : 0,
				'user_id' =>$user_id,
				'user_type' =>$user_type,
				'length_unit' =>$this->input->post('length_unit'),
				'rate' => $this->input->post('rate'),
				'gst_rate' => $this->input->post('gst_rate'),
				'delivery_type' => $this->input->post('delivery_type'),
				'minimum_amount' => $this->input->post('minimum_amount'), 
				'doc_nondoc' =>$doc_nondoc,
				);

		$query = $this->basic_operation_m->update('tbl_booking', $data, $whr);
		$lastid = $this->db->insert_id();
					//charges Details
					//total amount 
		$frieht = $this->input->post('frieht');
		$awb = $this->input->post('awn');
		$topay = $this->input->post('to_pay');
		$daoc = $this->input->post('dod_daoc');
		$loading = $this->input->post('loading');
		$packing = $this->input->post('packing');
		$handling = $this->input->post('handling');
		$oda = $this->input->post('oda');
		$insurance = $this->input->post('insurance');
		$fuel_subcharges = $this->input->post('fuel_subcharges');
		$data1 = array(
						//'payment_id'=>'',
			'booking_id' => $id,
			'amount' => $this->input->post('amount'),
			'frieht' => $this->input->post('frieht'),
			'awb' => $this->input->post('awn'),
			'to_pay' => $this->input->post('to_pay'),
			'dod_daoc' => $this->input->post('dod_daoc'),
			'loading' => $this->input->post('loading'),
			'packing' => $this->input->post('packing'),
			'handling' => $this->input->post('handling'),
			'oda' => $this->input->post('oda'),
			'fuel_subcharges' => $this->input->post('fuel_subcharges'),
			'fov' => $this->input->post('fov'),
			'apmt' => $this->input->post('apmt_delivery'),
			'sub_total' => $this->input->post('sub_total'),
			'IGST' => $this->input->post('igst'),
			'CGST' => $this->input->post('cgst'),
			'SGST' => $this->input->post('sgst'),
			'total_amount' => $this->input->post('grand_total'),
			'demurrage' => $this->input->post('demurrage'),
			'cod' => $this->input->post('cod'),
			'other_charges' => $this->input->post('other_charges'),
			'minimum_amount' => $this->input->post('minimum_amount'), 
			);
		
	   
		$length = $this->input->post('length');
		$breath = $this->input->post('breath');
		$height = $this->input->post('height');
		
		$no_of_pack = $this->input->post('no_of_pack');
		if($no_of_pack == ''){
			$no_of_pack = 1;
		}
		
		$one_cft_kg = $this->input->post('one_cft_kg');
		if($one_cft_kg == ''){
			$one_cft_kg = 1;
		}
		
		
		$valumetric = round( ( ($length * $breath * $height) / 28000 ) * $one_cft_kg * $no_of_pack);
		
					//$valumetric = ($length * $breath * $height) / 28000;
		
		$data2 = array(
			'booking_id' => $id,
			'actual_weight' => $this->input->post('actual_weight'),
			'valumetric_weight' => $this->input->post('valumetric_weight'),
			'length' => $this->input->post('length'),
			'breath' => $this->input->post('breath'),
			'height' => $this->input->post('height'),
			'one_cft_kg' => $this->input->post('one_cft_kg'),
			'chargable_weight' => $this->input->post('chargable_weight'),
			'per_box_weight' => $this->input->post('per_box_weight'),
			'rate' => $this->input->post('rate'),
			'rate_type' => $this->input->post('rate_type'),
			'rate_pack' => $this->input->post('rate_pack'),
			'no_of_pack' => $this->input->post('no_of_pack'),
			'type_of_pack' => $this->input->post('type_of_pack'),
			'special_instruction' => $this->input->post('special_instruction'),
			'actual_weight_detail' => json_encode($this->input->post('actual_weight_detail[]')),
			'valumetric_weight_detail' => json_encode($this->input->post('valumetric_weight_detail[]')),
			'length_detail' => json_encode($this->input->post('length_detail[]')),
			'breath_detail' => json_encode($this->input->post('breath_detail[]')),
			'height_detail' => json_encode($this->input->post('height_detail[]')),
			'one_cft_kg_detail' => json_encode($this->input->post('one_cft_kg_detail[]')),
			'no_pack_detail' => json_encode($this->input->post('no_pack_detail[]')),
			'per_box_weight_detail' =>json_encode($this->input->post('per_box_weight_detail[]')),
			'roundoff_type' =>$this->input->post('roundoff_type')
			);
		

			$query1 = $this->basic_operation_m->update('tbl_charges', $data1, $whr);
			$query2 = $this->basic_operation_m->update('tbl_weight_details', $data2, $whr);
		
			if ($this->db->affected_rows() > 0) 
			{
				$data['message'] = "Data added successfull";
			}
			else 
			{
				$data['message'] = "Failed to Submit";
			}
		}
		redirect('admin/view-shipment');
	}

	public function updatepodnew() 
	{

		$data['message'] = "";
		$resAct = $this->basic_operation_m->getAll('tbl_city', '');

		if ($resAct->num_rows() > 0) {
			$data['cities'] = $resAct->result_array();
		}

		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		$whr = array('booking_id' => $id);
		$user_id = $this->session->userdata("userId");
		$user_type = $this->session->userdata("userType");
		if ($id != "") {
			$res = $this->basic_operation_m->getAll('tbl_booking', $whr);

			if ($res->num_rows() > 0) {
				$data['booking'] = $res->result_array();
			}

			$resAct = $this->basic_operation_m->getAll('tbl_charges', $whr);
			if ($resAct->num_rows() > 0) {
				$data['charges'] = $resAct->result_array();
			}

			$resAct = $this->basic_operation_m->getAll('tbl_weight_details', $whr);
			if ($resAct->num_rows() > 0) {
				$data['weight'] = $resAct->result_array();
			}
		}
		if (isset($_POST['submit'])) {
			$last = $this->uri->total_segments();
			$id = $this->uri->segment($last);
			$whr = array('booking_id' => $id);
			$date = date('Y-m-d H:i:s',strtotime($this->input->post('booking_date')));
				//booking details//
			$data = array(
					// 'booking_id'=>$id, gst_rate
				'sender_name' => $this->input->post('sender_name'),
				'sender_address' => $this->input->post('sender_address'),
				'sender_city' => $this->input->post('sender_city'),
				'sender_pincode' => $this->input->post('sender_pincode'),
				'sender_contactno' => $this->input->post('sender_contactno'),
				'sender_gstno' => $this->input->post('sender_gstno'),
				'reciever_name' => $this->input->post('reciever_name'),
				'reciever_address' => $this->input->post('reciever_address'),
				'reciever_city' => $this->input->post('reciever_city'),
				'reciever_pincode' => $this->input->post('reciever_pincode'),
				'reciever_contact' => $this->input->post('reciever_contact'),
				'receiver_gstno' => $this->input->post('receiver_gstno'),
				'booking_date' => $date,
				'delivery_date' => $this->input->post('delivery_date'),
				'mode_dispatch' => $this->input->post('mode_dispatch'),
				'dispatch_details' => $this->input->post('dispatch_details'),
				'insurance_value' => $this->input->post('insurance_value'),
				'forwording_no' => $this->input->post('forwording_no'),
				'forworder_name' => $this->input->post('forworder_name'),
				'gst_charges' => $this->input->post('gst_charges'),
				'status' => ($this->input->post('status')) ? 1 : 0,
				'booking_type' => 2,
				'user_id' =>$user_id,
				'user_type' =>$user_type,
				);

		$query = $this->basic_operation_m->update('tbl_booking', $data, $whr);
		$lastid = $this->db->insert_id();
					//charges Details
					//total amount 
		$frieht = $this->input->post('frieht');
		$awb = $this->input->post('awb');
		$topay = $this->input->post('to_pay');
		$daoc = $this->input->post('dod_daoc');
		$loading = $this->input->post('loading');
		$packing = $this->input->post('packing');
		$handling = $this->input->post('handling');
		$oda = $this->input->post('oda');
		$insurance = $this->input->post('insurance');
		$fuel_subcharges = $this->input->post('fuel_subcharges');
		$data1 = array(
						//'payment_id'=>'',
			'booking_id' => $id,
			'amount' => $this->input->post('amount'),
			'frieht' => $this->input->post('frieht'),
			'awb' => $this->input->post('awb'),
			'to_pay' => $this->input->post('to_pay'),
			'dod_daoc' => $this->input->post('dod_daoc'),
			'loading' => $this->input->post('loading'),
			'packing' => $this->input->post('packing'),
			'handling' => $this->input->post('handling'),
			'oda' => $this->input->post('oda'),
			'insurance' => $this->input->post('insurance'),
			'fuel_subcharges' => $this->input->post('fuel_subcharges'),
						//'service_tax' => $this->input->post('service_tax'),
			'IGST' => $this->input->post('igst'),
			'CGST' => $this->input->post('cgst'),
			'SGST' => $this->input->post('sgst'),
			'total_amount' => $frieht,
			);
		
					//  weight details
					// $last = $this->uri->total_segments();
					// $id= $this->uri->segment($last);
					// $whr =array('booking_id'=>$id);
		
		$length = $this->input->post('length');
		$breath = $this->input->post('breath');
		$height = $this->input->post('height');
		
		$no_of_pack = $this->input->post('no_of_pack');
		if($no_of_pack == ''){
			$no_of_pack = 1;
		}
		
		$one_cft_kg = $this->input->post('one_cft_kg');
		if($one_cft_kg == ''){
			$one_cft_kg = 1;
		}
		
		$valumetric = round( ( ($length * $breath * $height) / 28000 ) * $one_cft_kg * $no_of_pack);
	 
		
		$data2 = array(
						//'weight_details_id'=>'',
			'booking_id' => $id,
			'actual_weight' => $this->input->post('actual_weight'),
			'valumetric_weight' => $this->input->post('valumetric_weight'),
			'length' => $this->input->post('length'),
			'breath' => $this->input->post('breath'),
			'height' => $this->input->post('height'),
			'one_cft_kg' => $this->input->post('one_cft_kg'),
			'chargable_weight' => $this->input->post('chargable_weight'),
			'rate' => $this->input->post('rate'),
			'rate_type' => $this->input->post('rate_type'),
			'rate_pack' => $this->input->post('rate_pack'),
			'no_of_pack' => $this->input->post('no_of_pack'),
			'type_of_pack' => $this->input->post('type_of_pack'),
			'special_instruction' => $this->input->post('special_instruction'),
			);
		
		$query1 = $this->basic_operation_m->update('tbl_charges', $data1, $whr);
		
		$query2 = $this->basic_operation_m->update('tbl_weight_details', $data2, $whr);
		
		if ($this->db->affected_rows() > 0) {
			$data['message'] = "Data added successfull";
		} else {
			$data['message'] = "Failed to Submit";
		}
		redirect(base_url() . 'generatepod/newbooking');
		}
		$this->load->view('admin/urgent_shippment/edit_pod', $data);
	}

	public function delete_shipment($id) 
	{
		
		if ($id != "") {
			$whr = array('booking_id' => $id);
			$res = $this->basic_operation_m->delete('tbl_booking', $whr);

			redirect('admin/view-shipment');
		}
	}

	public function allpod() {
		if ($this->session->userdata("userName") != "") {
			$data = array();
			$last = $this->uri->total_segments();
			$id = $this->uri->segment($last);

			$whr = array('booking_id' => $id);
			if ($id != "") {
				$res = $this->db->query("select * from tbl_booking,tbl_city where tbl_booking.sender_city=tbl_city.city_id and tbl_booking.booking_id='$id' ");

				if ($res->num_rows() > 0) {
					$data['basicdetails'] = $res->row();
				}

				$res = $this->db->query("select * from tbl_booking,tbl_city where tbl_booking.reciever_city=tbl_city.city_id and tbl_booking.booking_id='$id' ");

				if ($res->num_rows() > 0) {
					$data['basicdetails1'] = $res->row();
				}

				$resAct = $this->basic_operation_m->getAll('tbl_charges', $whr);
				if ($resAct->num_rows() > 0) {
					$data['paymentdetails'] = $resAct->row();
				}

				$resAct = $this->basic_operation_m->getAll('tbl_weight_details', $whr);
				if ($resAct->num_rows() > 0) {
					$data['weightdetails'] = $resAct->row();
				}
			}

			$this->load->view('printpod', $data);
		} else {
			redirect(base_url() . 'login');
		}
	}

	public function getsenderdetails() {
		$data = [];  
		$customer_name = $this->input->post('customer_name');

		$whr1 = array('customer_id' => $customer_name);
		$res1 = $this->basic_operation_m->selectRecord('tbl_customers', $whr1);
		$result1 = $res1->row();
		$data['user'] = $result1;
		echo json_encode($data);
		exit;
	}

	public function getRateMasterDetails() 
	{
    
		$data			= [];       
		
		$customer_name 	= $this->input->post('customer_name');
		$sender_city 	= $this->input->post('sender_city');
		$receiver_city 	= $this->input->post('receiver_city');
		$mode_dispatch 	= ucfirst($this->input->post('mode_dispatch'));
		
		$region_query 	= $this->db->query("SELECT `state`.`region_id`,`state`.`id` as state_id,`state`.`edd_train`,`state`.`edd_air`, `state`.`edd_air` FROM `state` join city ON `city`.`state_id` = `state`.`id` WHERE `city`.`id` = ".$receiver_city);
		
		if ($region_query->num_rows() > 0) 
		{
			$regionData 	= $region_query->row();
			$region_id 		= $regionData->region_id;
			$state_id 		= $regionData->state_id;
			$eod 			= ($mode_dispatch == 'air') ? $regionData->edd_air : $regionData->edd_air;
			$eod 			= $this->addBusinessDays(date("d-m-Y"),!empty($regionData->eod) ? $regionData->eod : 4);
		}

		if (!empty($region_id)) 
		{
			$data['rate_master'] = new \stdClass();
			
			$res = $this->db->query("select * from tbl_rate_master where customer_id=".$customer_name." AND mode_of_transport='".$mode_dispatch."' AND region_id=".$region_id." LIMIT 1");
			if ($res->num_rows() > 0) 
			{
				$data['rate_master'] = $res->row();
			  
				// check rate available for state table
				$stateMasterRes = $this->db->query("select * from tbl_rate_state where rate_master_id=".$data['rate_master']->rate_master_id." AND state_id =".$state_id." LIMIT 1");
				
				if($stateMasterRes->num_rows() > 0){
					$stateMasterData = $stateMasterRes->row();
					$data['rate_master']->rate = $stateMasterData->rate;
					$data['rate_master']->to_pay_charges = $stateMasterData->to_pay_charges;
					$data['rate_master']->cod = $stateMasterData->cod;
					$data['rate_master']->edd = $stateMasterData->edd;
				}
				
				
				//check rate available for city table
				$cityMasterRes = $this->db->query("select * from tbl_rate_city where rate_master_id=".$data['rate_master']->rate_master_id." AND city_id =".$receiver_city." LIMIT 1");
				if ($cityMasterRes->num_rows() > 0) {
					$cityMasterData = $cityMasterRes->row();
					$data['rate_master']->rate = $cityMasterData->rate;
					$data['rate_master']->to_pay_charges = $cityMasterData->to_pay_charges;
					$data['rate_master']->cod = $cityMasterData->cod;
					$data['rate_master']->edd = $cityMasterData->edd;
				}
				
				if($this->input->post('no_of_pack') > 0 && $this->input->post('rate_type') == 'no_of_pack') 
				{
					$rate_master_id 	= $data['rate_master']->rate_master_id;
					$no_of_pack 		= $this->input->post('no_of_pack');
					$rate_master_query 	= $this->db->query("SELECT * FROM `tbl_rate_pack` WHERE rate_master_id = ".$rate_master_id." AND $no_of_pack BETWEEN `from` AND `to` LIMIT 1");
				
					if($rate_master_query->num_rows() > 0) 
					{
						$data['rate_master_pack'] = $rate_master_query->row();
					}
				   
				}
			}
         $data['rate_master']->eod = $eod;
      //echo '<pre>';  print_r($data);exit;
		}
		echo json_encode($data);
		exit;
	}

	public function getBulkUploadRateMasterDetails($customer_name,$sender_state,$sender_city,$receiver_state,$receiver_city,$mode_dispatch,$qty,$chargeAbleWight,$parcel_type) 
	{
	   
		$data = [];
		$sql = "SELECT `state`.`region_id`,`state`.`id`,`state`.`edd_train`,`state`.`edd_air`, `state`.`edd_air` FROM `state` join city ON `city`.`state_id` = `state`.`id` WHERE `city`.`id` = ".$receiver_city;
		$region_query = $this->db->query($sql);
		
		if ($region_query->num_rows() > 0) 
		{
			$regionData 	= $region_query->row();
			$region_id 		= $regionData->region_id;
			$state_id 		= $regionData->id;
			$eod 			= ($mode_dispatch == 'air') ? $regionData->edd_air : $regionData->edd_air;
			$eod 			= $this->addBusinessDays(date("d-m-Y"),!empty($regionData->eod) ? $regionData->eod : 4);
		}
		
		if($parcel_type != 'doc')  // this is for non document rate 
		{
		
			if(!empty($region_id)) 
			{
				$data['rate_master'] = new \stdClass();
				
				$res = $this->db->query("select * from tbl_rate_master where customer_id=".$customer_name." AND mode_of_transport='".$mode_dispatch."' AND region_id=".$region_id." LIMIT 1");
				if ($res->num_rows() > 0) 
				{
					$data['rate_master'] = $res->row();
				  
					// check rate available for state table
					$stateMasterRes = $this->db->query("select * from tbl_rate_state where rate_master_id=".$data['rate_master']->rate_master_id." AND state_id =".$state_id." LIMIT 1");
					
					if($stateMasterRes->num_rows() > 0){
						$stateMasterData = $stateMasterRes->row();
						$data['rate_master']->rate = $stateMasterData->rate;
						$data['rate_master']->to_pay_charges = $stateMasterData->to_pay_charges;
						$data['rate_master']->cod = $stateMasterData->cod;
						$data['rate_master']->edd = $stateMasterData->edd;
					}
					
					
					//check rate available for city table
					$cityMasterRes = $this->db->query("select * from tbl_rate_city where rate_master_id=".$data['rate_master']->rate_master_id." AND city_id =".$receiver_city." LIMIT 1");
					if ($cityMasterRes->num_rows() > 0) 
					{
						$cityMasterData 						= $cityMasterRes->row();
						$data['rate_master']->rate 				= $cityMasterData->rate;
						$data['rate_master']->to_pay_charges 	= $cityMasterData->to_pay_charges;
						$data['rate_master']->cod 				= $cityMasterData->cod;
						$data['rate_master']->edd 				= $cityMasterData->edd;
					}
					
					if($this->input->post('no_of_pack') > 0 && $this->input->post('rate_type') == 'no_of_pack') 
					{
						$rate_master_id 	= $data['rate_master']->rate_master_id;
						$no_of_pack 		= $this->input->post('no_of_pack');
						$rate_master_query 	= $this->db->query("SELECT * FROM `tbl_rate_pack` WHERE rate_master_id = ".$rate_master_id." AND $no_of_pack BETWEEN `from` AND `to` LIMIT 1");
					
						if($rate_master_query->num_rows() > 0) 
						{
							$data['rate_master_pack'] = $rate_master_query->row();
						}
					   
					}
				}
				$data['rate_master']->eod = $eod;
				return $data;
			}
		}
		else // this for document rate
		{
			$data['rate_master'] = new \stdClass();
			
			$upper_chargee 		= array();
			$sub_total 			= 0;
			$doc_sub_total 		= 0;
			$addition 			= 0;
			$addtional_kg 		= 0;
			
			
			$res = $this->db->query("select * from tbl_rate_master where customer_id=".$customer_name." AND mode_of_transport='".$mode_dispatch."' AND region_id=".$region_id." LIMIT 1");
			if ($res->num_rows() > 0) 
			{
				$data['rate_master'] = $res->row();
				$rate_id			= $data['rate_master']->rate_master_id;
				$ress 				= $this->db->query("select * from rate_master where rate_id=".$rate_id."");
				if($ress->num_rows() > 0) 
				{
					$data['rate_masters']	= $ress->result();
				
					// check rate available for state table
					$stateMasterRes = $this->db->query("select * from doc_state_rate_master where rate_id=".$rate_id." AND state_id =".$state_id." ");
					
					if($stateMasterRes->num_rows() > 0)
					{
						$data['rate_masters']	= $stateMasterRes->result();
					}
					
					
					//check rate available for city table
					$cityMasterRes = $this->db->query("select * from doc_city_rate_master where rate_id=".$rate_id." AND city_id =".$receiver_city." ");
					if ($cityMasterRes->num_rows() > 0) 
					{
						$data['rate_masters']	= $cityMasterRes->result();	
					}
					
				}
			}
			if(!empty($data['rate_masters']))
			{
			
				foreach ($data['rate_masters'] as $row)
				{
					if($row->lower != 999999  )
					{
						$upper_chargee[] =  $row->upper;  
					}
					
					if($chargeAbleWight >= $row->lower && $chargeAbleWight <= $row->upper)
					{
						$sub_total =  $sub_total + $row->rate_amt; 
					}
					else
					{
						if($row->lower == 999999 )
						{
							$addtional_kg =  $row->rate_amt;  
							$addition =  $row->upper;  
						}
						else
						{
							$doc_sub_total =  $doc_sub_total + $row->rate_amt;
						}
						
						if($chargeAbleWight > $row->upper)
						{
							$sub_total =  $sub_total + $row->rate_amt; 
						}
					}
				}
			}
			
			
			if($chargeAbleWight > $addition && $addition != 0)
			{
				$weight  		= ceil($chargeAbleWight - $addition);
				$left_weight    = $weight;
				$amount		    = $left_weight * $addtional_kg;
				 
				$sub_total		= $doc_sub_total + $amount;
				$sub_total		= $sub_total * $qty;
			}
			else
			{
				
				if($chargeAbleWight > max($upper_chargee) )
				{
					$sub_total = $sub_total + $addtional_kg;
					$sub_total = $sub_total * $qty;
				}
				else
				{
					$sub_total = $sub_total * $qty;
				}
			}
			
			
			
			if($sender_state == $receiver_state)
			{
				$cgst = ($sub_total *2.5/100);
				$sgst = ($sub_total *2.5/100);
				$igst = 0;
				
			}
			else
			{
				$cgst = 0;
				$sgst = 0;
				$igst = ($sub_total *5/100);
		
			}
			
			$data['rate_master']->rate 				= $sub_total;
			$data['rate_master']->to_pay_charges 	= '0';
			$data['rate_master']->cod 				= '0';
			$data['rate_master']->edd 				= '2';
			$data['rate_master']->eod 				= $eod;
			$data['rate_master']->cgst 				= $cgst;
			$data['rate_master']->sgst 				= $sgst;
			$data['rate_master']->igst 				= $igst;
			
			return $data;
		}
	   exit;
	}

	public function getForwaorderList()
	{
		$senderPincode = $this->input->post('senderPincode');
		$receiverPincode = $this->input->post('receiverPincode');
		//$whr1 = array('POSTCODE' => $senderPincode);
		// $whr1 = array('POSTCODE' => $receiverPincode);
		// $res1 = $this->basic_operation_m->selectRecord('tbl_pincode', $whr1);
		$whr1 = array('pin_code' => $receiverPincode);
		$res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);
		$result = $res1->row();

		echo json_encode($result);
		exit;
	}
	public function getcity() {
		// $pincode = $this->input->post('pincode');
		// $mode_dispatch = $this->input->post('mode_dispatch');
		// $whr1 = array('POSTCODE' => $pincode);
		// $res1 = $this->basic_operation_m->selectRecord('tbl_pincode', $whr1);
		// $result1 = $res1->row();

		// $str = isset($result1->TOWN) ? $result1->TOWN : '';
		// echo $str;
		$pincode = $this->input->post('pincode');
	//    $mode_dispatch = $this->input->post('mode_dispatch');
		$whr1 = array('pin_code' => $pincode);
		$res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);
	//    $result1 = $res1->row();
		$city_id = $res1->row()->city_id;
		$whr2 = array('id' => $city_id);
		$res2 = $this->basic_operation_m->selectRecord('city', $whr2);
		$result2 = $res2->row();
		echo json_encode($result2);
		
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
	public function checkawn() {
		$data = [];       
		$awn = $this->input->post('awn');
		$awn_query = $this->db->query("SELECT `tbl_booking`.`pod_no` FROM `tbl_booking` WHERE pod_no='".$awn."'"); 
		if ($awn_query->num_rows() > 0) {
			$data['status'] = false;
		} else {
			$data['status'] = true;
		}
		echo json_encode($data);
		exit;    
	}

	public function view_delivered()
	{
		 $data = array();
			
		if ($this->session->userdata("userType") == '1') {
			$resAct = $this->db->query("select *,`tbl_tracking`.`status` AS deliver_status from tbl_booking,tbl_tracking
			  where tbl_tracking.pod_no = tbl_booking.pod_no and tbl_tracking.status='delivered' order by booking_date DESC ");
			if ($resAct->num_rows() > 0) {
				$data['allpoddata'] = $resAct->result_array();
			}
		} else {
			$username = $this->session->userdata("userName");
			$whr = array('username' => $username);
			$res = $this->basic_operation_m->getAll('tbl_users', $whr);
			$branch_id = $res->row()->branch_id;

			$data = array();
			$whrAct = array('isactive' => 1, 'isdeleted' => 0);

			$resAct = $this->db->query("select *,`tbl_tracking`.`status` AS deliver_status from tbl_booking,tbl_tracking
			  where tbl_tracking.pod_no = tbl_booking.pod_no and tbl_tracking.status='delivered' and tbl_booking.branch_id='$branch_id' order by `tbl_booking`.`booking_id` DESC");
			if ($resAct->num_rows() > 0) {
				$data['allpoddata'] = $resAct->result_array();
			}
		}
		//  print_r($data);
		$this->load->view('admin/shipment/view_deliverd', $data);
				//  $this->load->view('printpod',$data);
			
	}

	public function print_label($id) 
	{
		$whr = array('booking_id' => $id);
		$user_id = $this->session->userdata("userId");
		$user_type = $this->session->userdata("userType");
		if ($id != "") {
			
			$res = $this->basic_operation_m->getAll('tbl_domestic_booking', $whr);

			if ($res->num_rows() > 0) {
				$data['booking'] = $res->result_array();
			}

// 			$resAct = $this->basic_operation_m->getAll('tbl_domestic_charges', $whr);
// 			if ($resAct->num_rows() > 0) {
// 				$data['charges'] = $resAct->result_array();
// 			}

			$resAct = $this->basic_operation_m->getAll('tbl_domestic_weight_details', $whr);
			if ($resAct->num_rows() > 0) {
				$data['weight'] = $resAct->result_array();
			}
			$this->load->view('admin/shipment/print_shipment', $data);
		}
	}

	public function all_printpod($booking_id='') 
	{
	   
		$post_Data 			= $this->input->post();
		if(!empty($post_Data))
		{
			$data			= array();
			$where = "customer_id = '".$post_Data['user_id']."' AND (tbl_booking.booking_date >= '".$post_Data['from_date']."' AND tbl_booking.booking_date <= '".$post_Data['to_date']."')";
			
			$user_id = $this->session->userdata("userId");
			$user_type = $this->session->userdata("userType");
		   
				
			$resAct = $this->db->query("select * from tbl_booking,tbl_charges,tbl_weight_details 
					  where tbl_charges.booking_id =tbl_booking.booking_id 
					  and tbl_weight_details.booking_id=tbl_charges.booking_id and $where order by booking_date DESC ");
		
			if ($resAct->num_rows() > 0) 
			{
				$data['booking'] = $resAct->result_array();
			}		
		
			$this->load->view('mi/index_all', $data);
		}
		elseif($booking_id)
		{
			$data['selected_lists']	= explode('-',$booking_id);
			$booking_ids			= array_unique(array_filter($data['selected_lists']));
			
			$booking_idsa			= implode("','",$booking_ids);
			
		
			
			$where 					= "tbl_booking.booking_id IN ('$booking_idsa')";
			
			$user_id = $this->session->userdata("userId");
			$user_type = $this->session->userdata("userType");
		   
				
			$resAct = $this->db->query("select * from tbl_booking,tbl_charges,tbl_weight_details 
					  where tbl_charges.booking_id =tbl_booking.booking_id 
					  and tbl_weight_details.booking_id=tbl_charges.booking_id and $where GROUP BY tbl_booking.booking_id order by booking_date DESC ");
		
			
			if ($resAct->num_rows() > 0) 
			{
				$data['booking'] = $resAct->result_array();
			}		
		
			$this->load->view('mi/index_all', $data);
		}
	}

	public function print_slip($forwarderNum, $delveryserviceType) {
		
		if($delveryserviceType == 'b2c')
		{
			$packageSlip = $this->delhivery->packageSlipC2c($forwarderNum);
			/* echo '<pre>';
			print_r($packageSlip); */
			
			$data['slip_data'] = $packageSlip->packages;
			$res = $this->db->query("select * from tbl_booking,city where tbl_booking.sender_city=city.id and tbl_booking.forwording_no='$forwarderNum' ");

			if ($res->num_rows() > 0) {
				$data['basicdetails'] = $res->row();
			}
			$booking_id = $data['basicdetails']->booking_id;
			$whr = array('booking_id' => $booking_id);
			$resAct = $this->basic_operation_m->getAll('tbl_charges', $whr);
			if ($resAct->num_rows() > 0) {
				$data['charges'] = $resAct->result_array();
			}
			
			$this->load->view('package_slip', $data);
		}
		else
		{
			$packageSlip = $this->delhivery->packageSlipB2B($forwarderNum);
			/* print_r($packageSlip);
			echo 'provide b2b api doc'; */
			$data['slip_data'] = $packageSlip;
			$this->load->view('b2b_package_slip', $data);
		}
	}

}

?>
