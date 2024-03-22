<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FranchiseController extends CI_Controller
{

    var $data = array();
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('basic_operation_m');
        $this->load->model('Franchise_model');
        if ($this->session->userdata('userId') == '') {
            redirect('admin');
        }
    }

    public function index()
    {

        $data['allfranchise'] = $this->Franchise_model->get_franchise_details();
        $this->load->view('admin/franchise_master/view_franchise', $data);
    }


    public function add_franchise()
    {

        $query = "SELECT MAX(customer_id) as id FROM tbl_customers ";
        $result1 = $this->basic_operation_m->get_query_row($query);
        $id = $result1->id + 1;
        //print_r($id); exit;

        if (strlen($id) == 1) {
            $franchise_id = 'FI0000' . $id;
        } elseif (strlen($id) == 2) {
            $franchise_id = 'FI000' . $id;
        } elseif (strlen($id) == 3) {
            $franchise_id = 'FI00' . $id;
        } elseif (strlen($id) == 4) {
            $franchise_id = 'FI0' . $id;
        } elseif (strlen($id) == 5) {
            $franchise_id = 'FI' . $id;
        }

        $data['cities'] = $this->basic_operation_m->get_all_result('city', '');
        $data['states'] =  $this->basic_operation_m->get_all_result('state', '');
        $data['fid']    =   $franchise_id;

        $data['company_list'] = $this->basic_operation_m->get_query_result_array('SELECT * FROM tbl_company WHERE 1 ORDER BY company_name ASC');

        $this->load->view('admin/franchise_master/add_franchise', $data);
    }



    public function store_franchise_data(){

        if (isset($_POST['submit'])) {

            $this->load->library('form_validation');
            $this->form_validation->set_rules('franchise_name', 'Name', 'trim|required');
            $this->form_validation->set_rules('pan_number', 'Pan Number', 'trim|required|is_unique[tbl_franchise.pan_number]');
            $this->form_validation->set_rules('aadhar_number', 'Aadhar Number', 'trim|required|is_unique[tbl_franchise.aadhar_number]');
            $this->form_validation->set_rules('cmp_gstno', 'GST Number', 'trim|required|is_unique[tbl_franchise.cmp_gstno]');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            $this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required|matches[password]');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[tbl_customers.email]');

            if ($this->form_validation->run() == FALSE) {

                $this->add_franchise();
            } else {
                // *********************************  pancard upload ****************



                $v = $this->input->post('pancard_photo');
                if (isset($_FILES) && !empty($_FILES['pancard_photo']['name'])) {
                    $ret = $this->basic_operation_m->fileUpload($_FILES['pancard_photo'], 'assets/franchise-documents/pancard_document/');
                    //file is uploaded successfully then do on thing add entry to table
                    if ($ret['status'] && isset($ret['image_name'])) {
                        $pancard_photo = $ret['image_name'];
                    }
                }


                // ********************************* AadharCard upload ****************     




                $v = $this->input->post('aadharcard_photo');
                if (isset($_FILES) && !empty($_FILES['aadharcard_photo']['name'])) {
                    $ret = $this->basic_operation_m->fileUpload($_FILES['aadharcard_photo'], 'assets/franchise-documents/aadharcard_document/');
                    //file is uploaded successfully then do on thing add entry to table
                    if ($ret['status'] && isset($ret['image_name'])) {
                        $aadharcard_photo = $ret['image_name'];
                    }
                }
                // ********************************* Cancel Check upload ****************     


                $v = $this->input->post('cancel_check');
                if (isset($_FILES) && !empty($_FILES['cancel_check']['name'])) {
                    $ret = $this->basic_operation_m->fileUpload($_FILES['cancel_check'], 'assets/franchise-documents/bank_document/');
                    //file is uploaded successfully then do on thing add entry to table
                    if ($ret['status'] && isset($ret['image_name'])) {
                        $cancel_check = $ret['image_name'];
                    }
                }



                $date = date('Y-m-d');
            
                $data = array(
                    'cid' => $this->input->post('fid'),
                    'password' => $this->input->post('password'),
                    'customer_name' => $this->input->post('franchise_name'), //****Personal Info
                    'email' => $this->input->post('email'),
                    'address' => $this->input->post('address'),
                    'pincode' => $this->input->post('pincode'),
                    'state' => $this->input->post('franchaise_state_id'),
                    'city' => $this->input->post('franchaise_city_id'),
                    'phone' => $this->input->post('contact_number'),
                    'contact_person' => $this->input->post('alt_contact'),
                    'company_id' => $this->input->post('companytype'),
                    'parent_cust_id' => $this->input->post('customer_id'),
                    'customer_type' => 2,
                    'register_date' => $date,
                );
                   
                $result = $this->db->insert('tbl_customers', $data);

                $customer_last_id = $this->db->insert_id();

                    $data1 = array(

                        'fid' => $customer_last_id,
                        'franchise_relation' => $this->input->post('franchise_relation'),
                        'age' => $this->input->post('age'),
                        'pan_name' => $this->input->post('pan_name'),
                        'pan_number' => $this->input->post('pan_number'),
                        'pancard_photo' => $pancard_photo,
                        'aadhar_number' => $this->input->post('aadhar_number'),
                        'aadharin_name' => $this->input->post('aadharin_name'),
                        'dob' => $this->input->post('dob'),
                        'gender' => $this->input->post('gender'),
                        'aadhar_address' => $this->input->post('aadhar_address'),
                        'aadharcard_photo' => $aadharcard_photo,
                        'company_name' => $this->input->post('company_name'), // ****company information
                        'cmp_pan_number' => $this->input->post('cmp_pan_number'),
                        'cmp_gstno' => $this->input->post('cmp_gstno'),
                        'legal_name' => $this->input->post('legal_name'),
                        'constitution_of_business' => $this->input->post('constitution_of_business'),
                        'taxpayer_type' => $this->input->post('taxpayer_type'),
                        'gstin_status' => $this->input->post('gstin_status'),
                        'cmp_address' => $this->input->post('cmp_address'),
                        'cmp_pincode' => $this->input->post('cmp_pincode'),
                        'cmp_state' => $this->input->post('cmp_state'),
                        'cmp_city' => $this->input->post('cmp_city'),
                        'cmp_office_phone' => $this->input->post('cmp_office_phone'),
                        'cmp_account_name' => $this->input->post('cmp_account_name'), //*****Bank Details
                        'cmp_account_number' => $this->input->post('cmp_account_number'),
                        'cancel_check' => $cancel_check,
                        'cmp_bank_name' => $this->input->post('cmp_bank_name'),
                        'cmp_bank_branch' => $this->input->post('cmp_bank_branch'),
                        'cmp_acc_type' => $this->input->post('cmp_acc_type'),
                        'cmp_ifsc_code' => $this->input->post('cmp_ifsc_code'),

                    );
               
                    $result = $this->db->insert('tbl_franchise', $data1);
            
                    $delivery_pincode1 = $this->input->post('delivery_pincode');
                    $delivery_pincode = implode(',', $delivery_pincode1);
                    $delivery_city1 = $this->input->post('delivery_city');
                    $delivery_city = implode(',', $delivery_city1);

                    $d = array(

                        'delivery_franchise_id' =>$customer_last_id,
                        'master_franchise_name' => $this->input->post('master_franchise_name'),
                        'delivery_status' => $this->input->post('delivery_status'),
                     //   'delivery_pincode' =>  $delivery_pincode,
                        //'delivery_city' => $delivery_city,
                        'rate_group' => $this->input->post('rate_group'),
                        'fule_group' => $this->input->post('fule_group'),
                        'delivery_rate_group' => $this->input->post('delivery_rate_group')
                    );
                            echo '<pre>'; print_r($d);exit;
                  
                    $result = $this->basic_operation_m->insert('franchise_delivery_tbl', $d);
                    print_r($result);exit;
                
                if (!empty($result)) {
                    $msg            = 'Franchise added successfully';
                    $class            = 'alert alert-success alert-dismissible';
                } else {
                    $msg            = 'Franchise not added successfully';
                    $class            = 'alert alert-danger alert-dismissible';
                }
                $this->session->set_flashdata('notify', $msg);
                $this->session->set_flashdata('class', $class);

                redirect('admin/franchise-list');
            }
        }
        redirect('admin/franchise-list');
    }


    public function getCityList()
    {
        $pincode = $this->input->post('pincode');
        $whr1 = array('pin_code' => $pincode);
        $res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);

        $city_id = $res1->row()->city_id;

        $whr2 = array('id' => $city_id);
        $res2 = $this->basic_operation_m->selectRecord('city', $whr2);
        $result2 = $res2->row();

        echo json_encode($result2);
    }

    public function getState()
    {
        $pincode = $this->input->post('pincode');
        $whr1 = array('pin_code' => $pincode);
        $res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);

        $state_id = $res1->row()->state_id;
        $whr3 = array('id' => $state_id);
        $res3 = $this->basic_operation_m->selectRecord('state', $whr3);
        $result3 = $res3->row();

        echo json_encode($result3);
    }
    public function getCityList1()
    {
        $pincode = $this->input->post('cmppincode');
        $whr1 = array('pin_code' => $pincode);
        $res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);

        $city_id = $res1->row()->city_id;

        $whr2 = array('id' => $city_id);
        $res2 = $this->basic_operation_m->selectRecord('city', $whr2);
        $result2 = $res2->row();

        echo json_encode($result2);
    }

    public function getState1()
    {
        $pincode = $this->input->post('cmppincode');
        $whr1 = array('pin_code' => $pincode);
        $res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);

        $state_id = $res1->row()->state_id;
        $whr3 = array('id' => $state_id);
        $res3 = $this->basic_operation_m->selectRecord('state', $whr3);
        $result3 = $res3->row();

        echo json_encode($result3);
    }




    function update_franchise_data($id, $fid)
    {
        $date = date('Y-m-d');
        $data['message'] = "";
        if ($id != "") {
            $whr = array('franchise_id' => $id);
            $data['franchise_data'] = $this->basic_operation_m->get_table_row('tbl_franchise', $whr);
        }
        if ($fid != "") {
            $whr1 = array('customer_id' => $fid);
            $data['customer'] = $this->basic_operation_m->get_table_row('tbl_customers', $whr1);
        }
        if (isset($_POST['submit'])) {
            $last = $this->uri->total_segments();
            $id = $this->uri->segment($last);
            $whr2 = array('customer_id' => $fid);
            $user_type = 'general';
            $user_id = $this->session->userdata("userId");
            if ($this->session->userdata("userType") == "5") {
                $user_type = 'b2b';
            }

            $creditDays = $this->input->post('credit_days');
            if (!$this->input->post('credit_days')) {
                $creditDays = '';
            }

            $data = array(
                'cid' => $this->input->post('fid'),
                'password' => $this->input->post('password'),
                'customer_name' => $this->input->post('franchise_name'), //****Personal Info
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'pincode' => $this->input->post('pincode'),
                'state' => $this->input->post('franchaise_state_id'),
                'city' => $this->input->post('franchaise_city_id'),
                'phone' => $this->input->post('contact_number'),
                'contact_person' => $this->input->post('alt_contact'),
                'company_id' => $this->input->post('companytype'),
                'parent_cust_id' => $this->input->post('customer_id'),
                'customer_type' => 1,
                'register_date' => $date,
            );
            //   print_r($data);
            $result = $this->basic_operation_m->update('tbl_customers', $data, $whr2);


            $v = $this->input->post('pancard_photo');
            if ($v) {
                if (isset($_FILES) && !empty($_FILES['pancard_photo']['name'])) {
                    $ret = $this->basic_operation_m->fileUpload($_FILES['pancard_photo'], 'assets/franchise-documents/pancard_document/');
                    //file is uploaded successfully then do on thing add entry to table
                    if ($ret['status'] && isset($ret['image_name'])) {
                        $r['pancard_photo'] = $ret['image_name'];
                    }
                }
            }

            // ********************************* AadharCard upload ****************     




            $v = $this->input->post('aadharcard_photo');
            if ($v) {
                if (isset($_FILES) && !empty($_FILES['aadharcard_photo']['name'])) {
                    $ret = $this->basic_operation_m->fileUpload($_FILES['aadharcard_photo'], 'assets/franchise-documents/aadharcard_document/');
                    if ($ret['status'] && isset($ret['image_name'])) {
                        $r['aadharcard_photo'] = $ret['image_name'];
                    }
                }
            }

            // ********************************* Cancel Check upload ****************     


            $v = $this->input->post('cancel_check');
            if ($v) {
                if (isset($_FILES) && !empty($_FILES['cancel_check']['name'])) {
                    $ret = $this->basic_operation_m->fileUpload($_FILES['cancel_check'], 'assets/franchise-documents/bank_document/');
                    if ($ret['status'] && isset($ret['image_name'])) {
                        $r['cancel_check'] = $ret['image_name'];
                    }
                }
            }

            $whr3 = array('franchise_id' => $id);
            $r = array(

                'fid' => $id,
                //'username' => $this->input->post('username'),
                'franchise_relation' => $this->input->post('franchise_relation'),
                'age' => $this->input->post('age'),
                // 'alt_contact' => $this->input->post('alt_contact'),
                'pan_name' => $this->input->post('pan_name'),
                'pan_number' => $this->input->post('pan_number'),
                'aadhar_number' => $this->input->post('aadhar_number'),
                'aadharin_name' => $this->input->post('aadharin_name'),
                'dob' => $this->input->post('dob'),
                'gender' => $this->input->post('gender'),
                'aadhar_address' => $this->input->post('aadhar_address'),
                'company_name' => $this->input->post('company_name'), // ****company information
                'cmp_pan_number' => $this->input->post('cmp_pan_number'),
                'cmp_gstno' => $this->input->post('cmp_gstno'),
                'legal_name' => $this->input->post('legal_name'),
                'constitution_of_business' => $this->input->post('constitution_of_business'),
                'taxpayer_type' => $this->input->post('taxpayer_type'),
                'gstin_status' => $this->input->post('gstin_status'),
                'cmp_address' => $this->input->post('cmp_address'),
                'cmp_pincode' => $this->input->post('cmp_pincode'),
                'cmp_state' => $this->input->post('cmp_state'),
                'cmp_city' => $this->input->post('cmp_city'),
                'cmp_office_phone' => $this->input->post('cmp_office_phone'),
                'cmp_account_name' => $this->input->post('cmp_account_name'), //*****Bank Details
                'cmp_account_number' => $this->input->post('cmp_account_number'),
                'cmp_bank_name' => $this->input->post('cmp_bank_name'),
                'cmp_bank_branch' => $this->input->post('cmp_bank_branch'),
                'cmp_acc_type' => $this->input->post('cmp_acc_type'),
                'cmp_ifsc_code' => $this->input->post('cmp_ifsc_code'),

            );

            $franchise = $this->basic_operation_m->update('tbl_franchise', $r, $whr3);

            if ($franchise && $result) {
                //   print_r($result);die();
                if ($this->db->affected_rows() > 0) {
                    $data['message'] = "data Updated Sucessfully";
                } else {
                    $data['message'] = "Error in Query";
                }
                if (!empty($data)) {
                    $msg            = 'Customer updated successfully';
                    $class            = 'alert alert-success alert-dismissible';
                } else {
                    $msg            = 'Customer not updated successfully';
                    $class            = 'alert alert-danger alert-dismissible';
                }
                $this->session->set_flashdata('notify', $msg);
                $this->session->set_flashdata('class', $class);

                redirect('admin/franchise-list');
            }
        }

        $data['cities'] = $this->basic_operation_m->get_all_result('city', '');
        $data['states'] = $this->basic_operation_m->get_all_result('state', '');

        $this->load->view('admin/franchise_master/update_franchise_data', $data);
    }


    public function deleteFranchiseData()
    {
        $getId = $this->input->post('getid');
        $data =  $this->db->delete('tbl_franchise', array('franchise_id' => $getId));
        // echo $this->db->last_query();
        if ($data) {
            $output['status'] = 'success';
            $output['message'] = 'Member deleted successfully';
        } else {
            $output['status'] = 'error';
            $output['message'] = 'Something went wrong in deleting the member';
        }

        echo json_encode($output);
    }

//****************************************GetFranchise MasterName */

public function getFranchiseMaster(){

    $pincode = $this->input->post('pincode');
    $data = $this->db->query("SELECT customer_id,customer_name FROM `tbl_customers` WHERE pincode = '$pincode'")->row_array();
   
    echo json_encode($data);
    
}

public function get_delivery_pincode_city(){

       $pincode = $this->input->post('delivery_pincode');
        $whr1 = array('pin_code' => $pincode);
        $res1 = $this->basic_operation_m->selectRecord('pincode', $whr1);

        $city_id = $res1->row()->city_id;

        $whr2 = array('id' => $city_id);
        $res2 = $this->basic_operation_m->selectRecord('city', $whr2);
        $result2 = $res2->row();

        echo json_encode($result2);
}


    // ********************************* Franchise Topup Balance *********************************************

    public function franchise_topup_balance()
    {
        $date = date('Y-m-d');
        if (isset($_POST['submit'])) {

            $query = "SELECT MAX(topup_balance_id) as id FROM franchise_topup_balance_tbl ";
            $result1 = $this->basic_operation_m->get_query_row($query);
            $id = $result1->id + 1;
            //print_r($id); exit;

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




            $data = array(

                'franchise_id' => $this->input->post('franchise_id'),
                'transaction_id' => $franchise_id,
                'payment_date' => $date,
                'amount' => $this->input->post('amount'),
                'payment_mode' => $this->input->post('payment_mode'),
                'bank_name' => $this->input->post('bank_name'),
                'status'=>1,
                'refrence_no' => $this->input->post('Refrence_number')
            );

            $result =  $this->db->insert('franchise_topup_balance_tbl', $data);

            if (!empty($result)) {
                $msg              = 'Topup added successfully';
                $class            = 'alert alert-success alert-dismissible';
                redirect(base_url() . 'admin/view-franchise-topup-data');
            } else {
                $msg              = 'Topup not added successfully';
                $class            = 'alert alert-danger alert-dismissible';
                redirect(base_url() . 'admin/view-franchise-topup-data');
            }
            $this->session->set_flashdata('notify', $msg);
            $this->session->set_flashdata('class', $class);
        } else {

            $this->load->view('admin/franchise_master/add_franchise_topup_balance');
        }
    }

    public function getfranchise_details()
    {
        $franchise_id =  $this->input->post('franchise_id');
        $data = $this->db->query("SELECT tbl_customers.*,state.state, city.city FROM `tbl_customers` LEFT join state ON tbl_customers.state = state.id LEFT join city ON tbl_customers.city = city.id WHERE `cid` = '$franchise_id'")->row();
        echo json_encode($data);
    }

    public function view_franchise_topup_data()
    {

        $data['topup_details'] = $this->db->query("SELECT * FROM `franchise_topup_balance_tbl`")->result_array();
        $this->load->view('admin/franchise_master/view_franchise_topup_balance', $data);
    }

    public function update_franchise_topup($topup_balance_id)
    {
        if (isset($_POST['submit'])) {

            $data = array(
                'amount' => $this->input->post('amount'),
                'payment_mode' => $this->input->post('payment_mode'),
                'bank_name' => $this->input->post('bank_name'),
                'refrence_no' => $this->input->post('Refrence_number')
            );

            $this->db->where('topup_balance_id', $topup_balance_id);
            $result =  $this->db->update('franchise_topup_balance_tbl', $data);

            if (!empty($result)) {
                $msg              = 'Topup Update successfully';
                $class            = 'alert alert-success alert-dismissible';
                redirect(base_url() . 'admin/view-franchise-topup-data');
            } else {
                $msg              = 'Topup not Update successfully';
                $class            = 'alert alert-danger alert-dismissible';
                redirect(base_url() . 'admin/view-franchise-topup-data');
            }
            $this->session->set_flashdata('notify', $msg);
            $this->session->set_flashdata('class', $class);
        } else {
            $data = $this->db->query("SELECT * FROM `franchise_topup_balance_tbl` WHERE `topup_balance_id` = '$topup_balance_id'")->row();
            $this->load->view('admin/franchise_master/update_franchise_topup_balance', $data);
        }
    }

    public function delete_franchise_topup()
    {
        $getId = $this->input->post('getid');
        $data =  $this->db->delete('franchise_topup_balance_tbl', array('topup_balance_id' => $getId));
        if ($data) {
            $output['status'] = 'success';
            $output['message'] = 'Member deleted successfully';
        } else {
            $output['status'] = 'error';
            $output['message'] = 'Something went wrong in deleting the member';
        }

        echo json_encode($output);
    }

    public function filter_franchise_topup($offset=0,$searching='')
    {

        if (isset($_POST['submit'])) {
         
            $filterCond                    = '';
            $all_data                     = $this->input->post();

            if ($all_data) {


                foreach ($all_data as $ke => $filter_value) {

                    if ($filter_value == 'UPI') {
                        $filterCond .= " where franchise_topup_balance_tbl.payment_mode = '$filter_value'";
                    }
                    if ($filter_value == 'NEFT') {
                        $filterCond .= " where franchise_topup_balance_tbl.payment_mode = '$filter_value'";
                    }
                    if ($filter_value == 'CASH') {
                        $filterCond .= " where franchise_topup_balance_tbl.payment_mode = '$filter_value'";
                    }
                    if ($filter_value == 'Cheque') {
                        $filterCond .= " where franchise_topup_balance_tbl.payment_mode = '$filter_value'";
                    }
                    if ($filter_value == 'RTGS') {
                        $filterCond .= " where franchise_topup_balance_tbl.payment_mode = '$filter_value'";
                    }

                    if ($ke == 'franchise_id' && !empty($filter_value)) {
                        $filterCond .= "where franchise_topup_balance_tbl.franchise_id = '$filter_value'";
                    }


                    if ($ke == 'from_date' && !empty($filter_value)) {
                        $filterCond .= "where franchise_topup_balance_tbl.payment_date >= '$filter_value'";
                    }
                    if ($ke == 'to_date' && !empty($filter_value)) {
                        $filterCond .= " AND franchise_topup_balance_tbl.payment_date <= '$filter_value'";
                    }
                    if ($ke == 'to_date' && !empty($filter_value)) {
                        $filterCond .= " AND franchise_topup_balance_tbl.payment_date <= '$filter_value'";
                    }
                    if ($ke == 'ALL' && !empty($filter_value)) {
                        $filterCond .= " where franchise_topup_balance_tbl.status = '$filter_value'";
                    }

                }
            }

            // if(!empty($searching))
			// {
			// 	$filterCond = urldecode($searching);
			// }

            // echo $this->db->last_query();exit;
            $data['topup_details1'] = $this->db->query("select sum(amount) as total_amt From franchise_topup_balance_tbl  $filterCond ")->result_array();
            $data['topup_details'] = $this->db->query("select franchise_topup_balance_tbl.* From franchise_topup_balance_tbl  $filterCond ")->result_array();

            // $resActt1 = $this->db->query("select sum(amount) as total_amt From franchise_topup_balance_tbl  $filterCond ");
            // $resActt = $this->db->query("select franchise_topup_balance_tbl.* From franchise_topup_balance_tbl  $filterCond limit ".$offset.",2");


            // $this->load->library('pagination');
			
            // $data['total_count']			= $resActt->num_rows();
            // $config['total_rows'] 			= $resActt->num_rows();
            // $config['base_url'] 			= 'admin/view-domestic-shipment';
            // $config['suffix'] 				= '/'.urlencode($filterCond);
            
            // $config['per_page'] 			= 50;
            // $config['full_tag_open'] 		= '<nav aria-label="..."><ul class="pagination">';
            // $config['full_tag_close'] 		= '</ul></nav>';
            // $config['first_link'] 			= '&laquo; First';
            // $config['first_tag_open'] 		= '<li class="prev paginate_button page-item">';
            // $config['first_tag_close'] 		= '</li>';
            // $config['last_link'] 			= 'Last &raquo;';
            // $config['last_tag_open'] 		= '<li class="next paginate_button page-item">';
            // $config['last_tag_close'] 		= '</li>';
            // $config['next_link'] 			= 'Next';
            // $config['next_tag_open'] 		= '<li class="next paginate_button page-item">';
            // $config['next_tag_close'] 		= '</li>';
            // $config['prev_link'] 			= 'Previous';
            // $config['prev_tag_open'] 		= '<li class="prev paginate_button page-item">';
            // $config['prev_tag_close'] 		= '</li>';
            // $config['cur_tag_open'] 		= '<li class="paginate_button page-item active"><a href="javascript:void(0);" class="page-link">';
            // $config['cur_tag_close'] 		= '</a></li>';
            // $config['num_tag_open'] 		= '<li class="paginate_button page-item">';
            // $config['reuse_query_string'] 	= TRUE;
            // $config['num_tag_close'] 		= '</li>';
            // $config['attributes'] = array('class' => 'page-link');
            
            // if($offset == '')
            // {
            //     $config['uri_segment'] 			= 3;
            //     $data['serial_no']				= 1;
            // }
            // else
            // {
            //     $config['uri_segment'] 			= 3;
            //     $data['serial_no']		= $offset + 1;
            // }
            
            
            // $this->pagination->initialize($config);
            // if($resActt->num_rows() > 0) 
            // {
            //     $data['topup_details']= $resActt->result_array();
            //     $data['topup_details1']= $resActt1->result_array();
            // }
          


            $this->load->view('admin/franchise_master/filter_franchise_topup_data', $data);
        } else {
            $this->load->view('admin/franchise_master/filter_franchise_topup_data');
        }
    }
}
