<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Franchise_payment_history extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('basic_operation_m');
		if ($this->session->userdata('customer_id') == '') {
			redirect('franchise');
		}
	}

    public function payment_history()
    {
        $this->load->view('franchise/payment_details');
    }


}    