<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Booking_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	
	public function show_payment_history($user_id)
	{
		$this->db->select('tbl_international_invoice_payments.*, tbl_international_invoice.invoice_number, tbl_international_invoice.customer_name');
		$this->db->from('tbl_international_invoice_payments');
		$this->db->join('tbl_international_invoice', 'tbl_international_invoice.id = tbl_international_invoice_payments.invoice_id','inner');			
		$this->db->where('user_id',$user_id);

		$query=$this->db->get();	
		return $query->result_array();
	}
	public function select_invoice_details($whrCon)
	{
		$this->db->select('*');
		$this->db->from('tbl_international_invoice');			
		$this->db->where($whrCon);
		$this->db->order_by('inc_num','DESC');

		$query=$this->db->get();	
		return $query->result_array();
	}
	public function select_import_invoice_details($whrCon)
	{
		$this->db->select('*');
		$this->db->from('tbl_international_invoice_import');			
		$this->db->where($whrCon);
		$this->db->order_by('inc_num','DESC');

		$query=$this->db->get();	
		return $query->result_array();
	}
	public function select_invoice_setting_details()
	{
		$this->db->select('*');
		$this->db->from('tbl_invoice_setting');				
		$query=$this->db->get();	
		return $query->row();
	}
	public function get_all_result_domestic()
	{
		$this->db->select('*');
		$this->db->from('tbl_domestic_booking');		
		$this->db->join('city', 'city.id = tbl_domestic_booking.reciever_city','left');		
		$query = $this->db->get();
		 // echo $this->db->last_query();
	// exit(); 
		return $query->result_array();
	}

	public function get_international_invoice_details($where)
	{
		$this->db->select('*');
		$this->db->from('tbl_international_booking');	
		$this->db->join('zone_master', 'zone_master.z_id = tbl_international_booking.reciever_country_id','left');		
		if($where!='')
			$this->db->where($where); 
		$this->db->order_by('booking_date','Desc');			
		$query=$this->db->get();	

		//echo $this->db->last_query();exit;
		return $query->result_array();
	}
	public function get_all_pod_data($whr,$selected_dockets)
	{
		$this->db->select('*');
		$this->db->from('tbl_international_booking');	
		$this->db->join('tbl_international_weight_details', 'tbl_international_weight_details.booking_id = tbl_international_booking.booking_id','left');
		$this->db->join('tbl_customers', 'tbl_customers.customer_id = tbl_international_booking.customer_id','left');		
		$this->db->join('zone_master', 'zone_master.z_id = tbl_international_booking.reciever_country_id','left');		
		if($whr!='')
		{
			$this->db->where($whr); 
		}
		if($selected_dockets!='')
		{
			$this->db->where_in('tbl_international_booking.booking_id',$selected_dockets);
		}
		$this->db->order_by('booking_date','Desc');			
		$query=$this->db->get();
		return $query->result_array();
	}	
	
	public function get_domestic_all_pod_data($whr,$selected_dockets)
	{
		$this->db->select('*');
		$this->db->from('tbl_domestic_booking');	
		$this->db->join('tbl_domestic_weight_details', 'tbl_domestic_weight_details.booking_id = tbl_domestic_booking.booking_id','left');
		$this->db->join('tbl_customers', 'tbl_customers.customer_id = tbl_domestic_booking.customer_id','left');		
			
		if($whr!='')
		{
			$this->db->where($whr); 
		}
		if($selected_dockets!='')
		{
			$this->db->where_in('tbl_domestic_booking.booking_id',$selected_dockets);
		}
		$this->db->order_by('booking_date','Desc');			
		$query=$this->db->get();
		return $query->result_array();
	}
	public function get_all_pod_data_dashboard()
	{
		$this->db->select('*');
		$this->db->from('tbl_international_booking');	
		$this->db->join('tbl_international_weight_details', 'tbl_international_weight_details.booking_id = tbl_international_booking.booking_id','left');
		$this->db->join('tbl_customers', 'tbl_customers.customer_id = tbl_international_booking.customer_id','left');		
		$this->db->join('zone_master', 'zone_master.z_id = tbl_international_booking.reciever_country_id','left');
		
		$this->db->order_by('tbl_international_booking.booking_id','Desc');	
		$this->db->limit(5);		
		$query=$this->db->get();
		//echo $this->db->last_query();exit;
		return $query->result_array();
	}

	//====================Domestic Billing
	public function get_domestic_invoice_details($where)
	{
		$this->db->select('*');
		$this->db->from('tbl_domestic_booking');	
		$this->db->join('city', 'city.id = tbl_domestic_booking.reciever_city','left');
		$this->db->join('transfer_mode', 'transfer_mode.transfer_mode_id = tbl_domestic_booking.mode_dispatch','left');	
		if($where!='')
			$this->db->where($where); 
		$this->db->order_by('booking_date','Desc');			
		$query=$this->db->get();	

		//echo $this->db->last_query();exit;
		return $query->result_array();
	}
	public function get_all_pod_data_domestic($whr,$selected_dockets)
	{
		$this->db->select('*');
		$this->db->from('tbl_domestic_booking');	
		$this->db->join('tbl_domestic_weight_details', 'tbl_domestic_weight_details.booking_id = tbl_domestic_booking.booking_id','left');
		$this->db->join('tbl_customers', 'tbl_customers.customer_id = tbl_domestic_booking.customer_id','left');
		$this->db->join('city', 'city.id = tbl_domestic_booking.reciever_city','left');	
		$this->db->join('transfer_mode', 'transfer_mode.transfer_mode_id = tbl_domestic_booking.mode_dispatch','left');	
		if($whr!='')
		{
			$this->db->where($whr);
		}
		if($selected_dockets!='')
		{ 
		$this->db->where_in('tbl_domestic_booking.booking_id',$selected_dockets);
		}
		$this->db->order_by('booking_date','Desc');			
		$query=$this->db->get();

		//echo "++++++".$this->db->last_query();exit;
		return $query->result_array();
	}
	public function get_all_pod_data_domestic_dashboard()
	{
		$this->db->select('*');
		$this->db->from('tbl_domestic_booking');	
		$this->db->join('tbl_domestic_weight_details', 'tbl_domestic_weight_details.booking_id = tbl_domestic_booking.booking_id','left');
		$this->db->join('tbl_customers', 'tbl_customers.customer_id = tbl_domestic_booking.customer_id','left');
		$this->db->join('city', 'city.id = tbl_domestic_booking.reciever_city','left');				
		$this->db->order_by('tbl_domestic_booking.booking_id','Desc');			
		$this->db->limit(5);	
		$query=$this->db->get();

		//echo "++++++".$this->db->last_query();exit;
		return $query->result_array();
	}
	public function select_domestic_invoice_details($whrCon)
	{
		$this->db->select('*');
		$this->db->from('tbl_domestic_invoice');			
		$this->db->where($whrCon);
		$this->db->order_by('inc_num','DESC');

		$query=$this->db->get();	
		return $query->result_array();
	}
	public function show_domestic_payment_history($user_id)
	{
		$this->db->select('tbl_domestic_invoice_payments.*, tbl_domestic_invoice.invoice_number, tbl_domestic_invoice.customer_name');
		$this->db->from('tbl_domestic_invoice_payments');
		$this->db->join('tbl_domestic_invoice', 'tbl_domestic_invoice.id = tbl_domestic_invoice_payments.invoice_id','inner');			
		$this->db->where('user_id',$user_id);

		$query=$this->db->get();	
		//echo $this->db->last_query();exit;
		return $query->result_array();
	}
	 public function get_daily_international_sales($whr)
    {
        $this->db->select("tbl_international_booking.booking_id,dispatch_details,tbl_international_booking.booking_date,tbl_international_booking.sender_name,tbl_international_booking.company_type,tbl_international_booking.grand_total,reciever_name,pod_no,forwording_no,forworder_name,no_of_pack,chargable_weight,mode_dispatch,doc_nondoc,country_name,c_company_name,customer_name,payment_method.method,tbl_branch.branch_name");
        $this->db->from("tbl_international_booking");       
        $this->db->join("tbl_international_weight_details","tbl_international_weight_details.booking_id = tbl_international_booking.booking_id","left"); 
        $this->db->join("zone_master","zone_master.z_id = tbl_international_booking.reciever_country_id","left"); 
        $this->db->join("courier_company","courier_company.c_id = tbl_international_booking.courier_company_id","left"); 
        $this->db->join("tbl_customers","tbl_customers.customer_id = tbl_international_booking.customer_id","left"); 
        $this->db->join("payment_method","payment_method.id = tbl_international_booking.payment_method","left");
        $this->db->join('tbl_branch', 'tbl_branch.branch_id = tbl_international_booking.branch_id','left');
      
        
        if(!empty($whr)){
           $this->db->where($whr);
        }
      
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result_array();  
    }
    public function get_daily_domestic_sales($whr)
    {
        $this->db->select("tbl_domestic_booking.booking_id,tbl_domestic_booking.booking_date,dispatch_details,tbl_domestic_booking.sender_name,tbl_domestic_booking.company_type,tbl_domestic_booking.booking_date,tbl_domestic_booking.grand_total,reciever_name,pod_no,forwording_no,forworder_name,no_of_pack,chargable_weight,mode_dispatch,doc_nondoc,city.city,c_company_name,customer_name,mode_name,payment_method.method,tbl_branch.branch_name");
        $this->db->from("tbl_domestic_booking");       
        $this->db->join("tbl_domestic_weight_details","tbl_domestic_weight_details.booking_id = tbl_domestic_booking.booking_id","left"); 
        $this->db->join("transfer_mode","transfer_mode.transfer_mode_id = tbl_domestic_booking.mode_dispatch","left"); 
        $this->db->join("city","city.id = tbl_domestic_booking.reciever_city","left"); 
        $this->db->join("courier_company","courier_company.c_id = tbl_domestic_booking.courier_company_id","left");
        $this->db->join('tbl_branch', 'tbl_branch.branch_id = tbl_domestic_booking.branch_id','left');

        $this->db->join("tbl_customers","tbl_customers.customer_id = tbl_domestic_booking.customer_id","left");
        $this->db->join("payment_method","payment_method.id = tbl_domestic_booking.payment_method","left");
        
       
        if(!empty($whr)){
           $this->db->where($whr);
        }
      
        $query = $this->db->get();
       // echo $this->db->last_query();exit;
        return $query->result_array();  
    }
    public function get_international_gst_details($whr)
    {
        $this->db->select("*");
        $this->db->from("tbl_international_invoice");  
        if(!empty($whr)){
           $this->db->where($whr);
        }
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result_array();  
    }
    public function get_domestic_gst_details($whr)
    {
        $this->db->select("*");
        $this->db->from("tbl_domestic_invoice");  
        if(!empty($whr)){
           $this->db->where($whr);
        }
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result_array();  
    }
	
}
?>