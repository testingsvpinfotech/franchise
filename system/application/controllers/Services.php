<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Services extends CI_Controller{
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('includes/basic_operation_m');
	}
	
public function index()
	{
		$data= array();
		$whrAct=array('isactive'=>1,'isdeleted'=>0);
		$resAct	= $this->basic_operation_m->getAll('tbl_homeslider','');

		if($resAct->num_rows()>0)
		 {
		 	$data['homeslider']=$resAct->result_array();	            
         }else{
         	 $data['homeslider']=array();
         }
		 $resAct = $this->db->query("select * from tbl_news limit 3 ");

		if($resAct->num_rows()>0)
		 {
		 	$data['newesdata']=$resAct->result_array();	            
         }else{
         	 $data['newesdata']=array();
         }
		$resAct	= $this->basic_operation_m->getAll('tbl_testimonial','');

		if($resAct->num_rows()>0)
		 {
			$data['testimonial']=$resAct->result_array();	            
		 }else{
			 $data['testimonial']=array();
		 }
    	$resAct1 = $this->db->query("select * from tbl_news limit 9 ");

		if($resAct1->num_rows()>0)
		 {
		 	$data['homenews']=$resAct1->result_array();	            
         }else{
         	 $data['homenews']=array();
         }
		$this->load->view('services',$data);

	}

public function air()
	{
		$data= array();
		$resAct1 = $this->db->query("select * from tbl_news limit 9 ");

		if($resAct1->num_rows()>0)
		 {
		 	$data['homenews']=$resAct1->result_array();	            
         }else{
         	 $data['homenews']=array();
         }
		
		 
		$this->load->view('airservice',$data);
	}
	public function train()
	{
		$data= array();
		$resAct1 = $this->db->query("select * from tbl_news limit 9 ");

		if($resAct1->num_rows()>0)
		 {
		 	$data['homenews']=$resAct1->result_array();	            
         }else{
         	 $data['homenews']=array();
         }
		
		 
		$this->load->view('train',$data);
	}
	public function surface()
	{
		$data= array();
		$resAct1 = $this->db->query("select * from tbl_news limit 9 ");

		if($resAct1->num_rows()>0)
		 {
		 	$data['homenews']=$resAct1->result_array();	            
         }else{
         	 $data['homenews']=array();
         }
		
		 
		$this->load->view('surface',$data);
	}
}