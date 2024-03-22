<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

    function __construct() {
        parent:: __construct();
        $this->load->model('includes/basic_operation_m');
    }

    public function index() {
        echo $date = date('Y-m-d');
	} 
 

	public  function pincodetracking () {
		$data = array();
		$data['results'] = "";
		$pincodes = trim($this->input->post('pincodes'));
		$data['pincodes'] = $pincodes;
		if($this->input->post('submit') == 'Submit'){
				$tmp_array_pincodes = preg_split('/\r\n|[\r\n]/', $pincodes);
				$array_pincodes = array();
				#Check bluedart_air
				foreach($tmp_array_pincodes as $inx => $val){


					
					$fedex_regular_str = '';
					$res = $this->db->query("SELECT PinCode, `City Name` as loc,`State`,`ODA-OPA / Regular Classification (Dom +Intl)` as oda  FROM   fedex_regular WHERE PinCode = ".$this->db->escape($val));
					if ($res->num_rows() > 0) {
						$tmp_dat = $res->row_array();
						$oda_str = '';
						if(trim($tmp_dat['oda']) == 'ODA/OPA'){
							$oda_str =  'ODA';
						}

						#$fedex_regular_str = "Service available $oda_str". '(' . $tmp_dat['loc'] . ')' ;
						$service_available = "Service available $oda_str". '(' . $tmp_dat['loc'] . ')' ;
					}else{
						$fedex_regular_str = '<span style="color:red">NOT Available</span>';
					}
					 

					if(!$service_available){
						$revigo_regular_str = '';
						$res = $this->db->query("SELECT PinCode,  `Serviceability` as oda FROM   revigo_regular  WHERE PinCode = ".$this->db->escape($val));
						if ($res->num_rows() > 0) {
							$tmp_dat = $res->row_array();
							$oda_str = '';
							if(trim($tmp_dat['oda']) == 'ODA'){
								$oda_str =  'ODA';
							}

							$revigo_regular_str = "Service available $oda_str" ;
							$service_available = "Service available $oda_str" ;
						}else{
							$revigo_regular_str =  '<span style="color:red">NOT Available</span>';
						}
					}



					if(!$service_available){
						$bluedart_air_str = '';
						$service_available = '';
						$res = $this->db->query("SELECT PinCode, `State Description` as loc, `S/C Description` as loc2  FROM  bluedart_air WHERE PinCode = ".$this->db->escape($val));
						if ($res->num_rows() > 0) {
							$tmp_dat = $res->row_array();
							#$bluedart_air_str = 'Service available'. '(' . $tmp_dat['loc']." - ".$tmp_dat['loc2'] . ')' ;
							$service_available = 'Service available'. '(' . $tmp_dat['loc']." - ".$tmp_dat['loc2'] . ')' ;
						}else{
							$bluedart_air_str = '<span style="color:red">NOT Available</span>';
						}
						
						if(!$service_available){
							$bluedart_surface_str = '';
							$res = $this->db->query("SELECT PinCode, `State Description` as loc, `S/C Description` as loc2 FROM  bluedart_surface WHERE PinCode = ".$this->db->escape($val));
							if ($res->num_rows() > 0) {
								$tmp_dat = $res->row_array();
								#$bluedart_surface_str = 'Service available'. '(' . $tmp_dat['loc']." - ".$tmp_dat['loc2'] . ')' ;
								$service_available = 'Service available'. '(' . $tmp_dat['loc']." - ".$tmp_dat['loc2'] . ')' ;
							}else{
								$bluedart_surface_str = '<span style="color:red">NOT Available</span>';
							}
						}
					}
					
					if(!$service_available){
						$spoton_str = '';
						$res = $this->db->query("SELECT PinCode,  `BRNM` as loc,  `AreaName` as loc2 FROM    spoton  WHERE PinCode = ".$this->db->escape($val));
						if ($res->num_rows() > 0) {
							$tmp_dat = $res->row_array();
							#print_r($tmp_dat);
							$spoton_str = 'Service available'. '(' . $tmp_dat['loc']." - ".$tmp_dat['loc2'] . ')' ;
							$service_available = 'Service available'. '(' . $tmp_dat['loc']." - ".$tmp_dat['loc2'] . ')' ;
						}else{
							$spoton_str = '<span style="color:red">NOT Available</span>';
						}
					}

					if(!$service_available){
						$service_available = '<span style="color:red">Not Available</span>';
					}

					$data['results'] .= "<strong>PIN:$val</strong>:$service_available <br>";
					//$data['results'] .= "<strong>PIN:$val</strong>, Bluedart air: $bluedart_air_str ,Bluedart surface: $bluedart_surface_str, Fedex regular:$fedex_regular_str, Revigo regular:$revigo_regular_str, Spoton:$spoton_str <br>";
				}
				#
	
		}

        $this->load->view('pincodetracking', $data);
 	}
	public  function test () {
		$this->load->view('test', array());
	}
}
?>
