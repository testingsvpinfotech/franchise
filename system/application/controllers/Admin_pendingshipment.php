<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_pendingshipment extends CI_Controller {

    function __construct() {
        parent:: __construct();
        $this->load->model('basic_operation_m');
        if($this->session->userdata('userId') == '')
        {
            redirect('admin');
        }
    } 

    public function index() {
        $data = array();        
            if ($this->session->userdata("userType") == '1') {
                $resAct = $this->db->query("select * from tbl_charges,tbl_booking,tbl_weight_details 
                  where tbl_charges.booking_id =tbl_booking.booking_id 
                  and tbl_weight_details.booking_id=tbl_charges.booking_id and tbl_booking.status!=1  order by booking_date DESC ");
                if ($resAct->num_rows() > 0) {
                    $data['allpoddata'] = $resAct->result_array();
                }
            } else {
                $username = $this->session->userdata("userName");
                $whr = array('username' => $username);
                $res = $this->basic_operation_m->getAll('tbl_users', $whr);

                $branch_id = $res[0]['branch_id'];

                $data = array();
                $whrAct = array('isactive' => 1, 'isdeleted' => 0);

                $resAct = $this->db->query("select * from tbl_charges,tbl_booking,tbl_weight_details 
                  where tbl_charges.booking_id =tbl_booking.booking_id 
                  and tbl_weight_details.booking_id=tbl_charges.booking_id and tbl_booking.branch_id='$branch_id' and tbl_booking.status!=1  order by `tbl_booking`.`booking_id` DESC");
                if ($resAct->num_rows() > 0) {
                    $data['allpoddata'] = $resAct->result_array();
                }
            }
            $this->load->view('admin/urgent_shippment/viewpendingship', $data);
        
    }
    
    public function newbooking() {
        $data = array();        
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
            }
            //  print_r($data);
            $this->load->view('viewpandingship', $data);
            //  $this->load->view('printpod',$data);
     
    }

    public function addpod() {
        $result = $this->db->query('select max(booking_id) AS id from tbl_booking')->row();
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
                'insurace_policyno' => $this->input->post('insurace_policyno'),
                'forwording_no' => $this->input->post('forwording_no'),
                'forworder_name' => $this->input->post('forworder_name'),
                'pod_no' => $this->input->post('awn'),
                'branch_id' => $branch_id,
                'customer_id' => $this->input->post('customer_account_id'),
                'gst_charges' => $this->input->post('gst_charges'),
                'status' => ($this->input->post('status')) ? 1 : 0
                );

    $query = $this->basic_operation_m->insert('tbl_booking', $data);
    
    $lastid = $this->db->insert_id();
    
                //charges Details/
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
        'payment_id' => '',
        'booking_id' => $lastid,
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
        );
    
    $result3 = $this->basic_operation_m->insert('tbl_tracking', $data3);
    
    $whr = array('customer_id'=>$customerid);
    $res=$this->basic_operation_m->getAll('tbl_customers',$whr);
    $email= $res->row()->email;
    $message='Your Shipment '.$podno.' status:Boked  At Location: '.$branch_name;
   // $this->sendemail($email,$message);
    
    
    if ($this->db->affected_rows() > 0) {
        $data['message'] = "Data added successfull";
    } else {
        $data['message'] = "Failed to Submit";
    }
    redirect(base_url() . 'generatepod');
    }
    $data['bid'] = $id;
    $this->load->view('addpod', $data);
}

public function addpodnew() {
        $result = $this->db->query('select max(booking_id) AS id from tbl_booking')->row();
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
                'insurace_policyno' => $this->input->post('insurace_policyno'),
                'forwording_no' => $this->input->post('forwording_no'),
                'forworder_name' => $this->input->post('forworder_name'),
                'pod_no' => $this->input->post('awn'),
                'branch_id' => $branch_id,
                'customer_id' => $this->input->post('customer_account_id'),
                'gst_charges' => $this->input->post('gst_charges'),
                'status' => ($this->input->post('status')) ? 1 : 0,
                'booking_type' => 2
                );

    $query = $this->basic_operation_m->insert('tbl_booking', $data);
    
    $lastid = $this->db->insert_id();
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
        'payment_id' => '',
        'booking_id' => $lastid,
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
        );
    
    $result3 = $this->basic_operation_m->insert('tbl_tracking', $data3);

    if ($this->db->affected_rows() > 0) {
        $data['message'] = "Data added successfull";
    } else {
        $data['message'] = "Failed to Submit";
    }
    redirect(base_url() . 'generatepod/newbooking');
    }
    $data['bid'] = $id;
    $this->load->view('addpodnew', $data);
}

public function sendemail($to,$message)
{

   $this->load->library('email');
   $config['protocol'] = 'smtp';
   $config['smtp_host'] = 'mail.rajcargo.net';
   $config['smtp_user'] = 'info@rajcargo.net';
   $config['smtp_pass'] = '41]wHpOZBnq}';
   $config['smtp_port'] = 25;
   $config['charset'] = 'iso-8859-1';
   $config['wordwrap'] = TRUE;
}
public function updatepod() {
    $data['message'] = "";
    $resAct = $this->basic_operation_m->getAll('tbl_city', '');

    if ($resAct->num_rows() > 0) {
        $data['cities'] = $resAct->result_array();
    }

    $last = $this->uri->total_segments();
    $id = $this->uri->segment($last);
    $whr = array('booking_id' => $id);
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
                // 'booking_id'=>$id,
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
            'insurace_policyno' => $this->input->post('insurace_policyno'),
            'forwording_no' => $this->input->post('forwording_no'),
            'forworder_name' => $this->input->post('forworder_name'),
            'gst_charges' => $this->input->post('gst_charges'),
            'status' => ($this->input->post('status')) ? 1 : 0
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
    
                //$valumetric = ($length * $breath * $height) / 28000;
    
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
    redirect(base_url() . 'generatepod');
    }
    $this->load->view('editpod', $data);
}

public function updatepodnew() {
    $data['message'] = "";
    $resAct = $this->basic_operation_m->getAll('tbl_city', '');

    if ($resAct->num_rows() > 0) {
        $data['cities'] = $resAct->result_array();
    }

    $last = $this->uri->total_segments();
    $id = $this->uri->segment($last);
    $whr = array('booking_id' => $id);
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
                // 'booking_id'=>$id,
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
            'insurace_policyno' => $this->input->post('insurace_policyno'),
            'forwording_no' => $this->input->post('forwording_no'),
            'forworder_name' => $this->input->post('forworder_name'),
            'gst_charges' => $this->input->post('gst_charges'),
            'status' => ($this->input->post('status')) ? 1 : 0,
            'booking_type' => 2
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
    
                //$valumetric = ($length * $breath * $height) / 28000;
    
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
    $this->load->view('editpodnew', $data);
}

public function deletepod() {
    $data['message'] = "";
    $last = $this->uri->total_segments();
    $id = $this->uri->segment($last);
    if ($id != "") {
        $whr = array('booking_id' => $id);
        $res = $this->basic_operation_m->delete('tbl_booking', $whr);

        redirect(base_url() . 'generatepod');
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

public function getRateMasterDetails() {
    $data = [];       
    $customer_name = $this->input->post('customer_name');
    $sender_city = $this->input->post('sender_city');
    $receiver_city = $this->input->post('receiver_city');
    $mode_dispatch = ucfirst($this->input->post('mode_dispatch'));
    $region_query = $this->db->query("SELECT `state`.`region_id`,`state`.`edd_train`,`state`.`edd_air`, `state`.`edd_air` FROM `state` join city ON `city`.`state_id` = `state`.`id` WHERE `city`.`id` = ".$receiver_city); 
    if ($region_query->num_rows() > 0) {
        $regionData = $region_query->row();
        $region_id = $regionData->region_id;
        $eod = ($mode_dispatch == 'air') ? $regionData->edd_air : $regionData->edd_air;
        $eod = $this->addBusinessDays(date("d-m-Y"),!empty($regionData->eod) ?$regionData->eod : 4);
    }

    if (!empty($region_id)) {
        $data['rate_master'] = new \stdClass();
        $res = $this->db->query("select * from tbl_rate_master where customer_id=".$customer_name." AND mode_of_transport='".$mode_dispatch."' AND region_id=".$region_id." LIMIT 1");
        if ($res->num_rows() > 0) {
           
            $data['rate_master'] = $res->row();
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
    echo json_encode($data);
    exit;
}

public function getcity() {
    $pincode = $this->input->post('pincode');

    $whr1 = array('POSTCODE' => $pincode);
    $res1 = $this->basic_operation_m->selectRecord('tbl_pincode', $whr1);
    $result1 = $res1->row();

    $str = isset($result1->TOWN) ? $result1->TOWN : '';

    echo $str;
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

public function delivered()
{
     $data = array();
        if ($this->session->userdata("userName") != "") {
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
            $this->load->view('viewdeliverdpod', $data);
            //  $this->load->view('printpod',$data);
        } else {
            redirect(base_url() . 'login');
        }
}

}

?>