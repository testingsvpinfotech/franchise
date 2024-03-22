<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rate_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	
	public function select_rate_details($customer_id)
	{
		$this->db->select('*');
		$this->db->from('tbl_rate_master');
		$this->db->join('tbl_customers', 'tbl_customers.customer_id = tbl_rate_master.customer_id','left');
		$this->db->join('region_master', 'region_master.region_id = tbl_rate_master.region_id','left');
		$this->db->join('state', 'state.id = tbl_customers.state','left');
		$this->db->where('tbl_rate_master.customer_id',$customer_id);

		$query=$this->db->get();		
		return $query->result_array();
	}
	public function select_state_rate($customer_id,$rate_master_id)
	{
		$this->db->select('*,tbl_rate_state.id as tbl_rate_state_id');
		$this->db->from('tbl_rate_state');
		$this->db->join('state', 'state.id = tbl_rate_state.state_id','left');		
		$this->db->where('tbl_rate_state.customer_id',$customer_id);
		$this->db->where('tbl_rate_state.rate_master_id',$rate_master_id);

		$query=$this->db->get();		

		//echo $this->db->last_query();
		//exit;
		return $query->result_array();
	}
	public function select_city_rate($customer_id,$rate_master_id)
	{
		$this->db->select('*,tbl_rate_city.id as tbl_rate_city_id');
		$this->db->from('tbl_rate_city');
		$this->db->join('city', 'city.id = tbl_rate_city.id','left');		
		$this->db->where('tbl_rate_city.customer_id',$customer_id);
		$this->db->where('tbl_rate_city.rate_master_id',$rate_master_id);

		$query=$this->db->get();		
		return $query->result_array();
	}
	//================Get row
	public function select_state_rate_row($customer_id,$rate_master_id,$tbl_rate_state_id)
	{
		$this->db->select('*');
		$this->db->from('tbl_rate_state');
		$this->db->join('state', 'state.id = tbl_rate_state.state_id','left');		
		$this->db->where('tbl_rate_state.customer_id',$customer_id);
		$this->db->where('tbl_rate_state.rate_master_id',$rate_master_id);
		$this->db->where('tbl_rate_state.id',$tbl_rate_state_id);

		$query=$this->db->get();
		return $query->row();
	}
	public function select_city_rate_row($customer_id,$rate_master_id,$tbl_rate_city_id)
	{
		$this->db->select('*');
		$this->db->from('tbl_rate_city');
		$this->db->join('city', 'city.id = tbl_rate_city.id','left');		
		$this->db->where('tbl_rate_city.customer_id',$customer_id);
		$this->db->where('tbl_rate_city.rate_master_id',$rate_master_id);
		$this->db->where('tbl_rate_city.id',$tbl_rate_city_id);

		$query=$this->db->get();

		//echo $this->db->last_query();exit;			
		return $query->row();
	}

	// =============get 

	public function get_table_row_state_rate($rate_master_id)
	{
		$this->db->select('*');
		$this->db->from('tbl_rate_master');
		$this->db->join('tbl_rate_state', 'tbl_rate_state.rate_master_id =  tbl_rate_master.rate_master_id','left');
		$this->db->join('state', 'state.id = tbl_rate_state.state_id','left');
		$this->db->where('tbl_rate_state.rate_master_id',$rate_master_id);

		$query=$this->db->get();
		return $query->row();
	}
	public function get_domestic_rate_result($customer_id,$courier_id,$applicable_from)
	{
		$this->db->select('*,from_regi.region_name as from_region_name,to_regi.region_name as to_region_name');
		$this->db->from('tbl_domestic_rate_master');
		$this->db->join('courier_company', 'courier_company.c_id = tbl_domestic_rate_master.c_courier_id','left');
		$this->db->join('tbl_customers', 'tbl_customers.customer_id = tbl_domestic_rate_master.customer_id','left');
		$this->db->join('transfer_mode', 'transfer_mode.transfer_mode_id = tbl_domestic_rate_master.mode_id','left');
		$this->db->join('region_master as from_regi', 'from_regi.region_id = tbl_domestic_rate_master.from_zone_id','left');	
		$this->db->join('region_master as to_regi', 'to_regi.region_id = tbl_domestic_rate_master.to_zone_id','left');	

		$this->db->where('tbl_domestic_rate_master.customer_id',$customer_id);
		$this->db->where('tbl_domestic_rate_master.c_courier_id',$courier_id);
		$this->db->where('tbl_domestic_rate_master.applicable_from',$applicable_from);
		$query=$this->db->get();		
		//echo $this->db->last_query();
		return $query->result_array();
	}
	public function select_ajax_country($val)
	{
		$this->db->select('country_name');
		$this->db->from('zone_master');
		$this->db->where('country_name LIKE "'.$val.'%" ');
		
		$query = $this->db->get();
		//echo "==".$this->db->last_query();exit;
		return $query->result_array();
	}
	public function get_domestic_rate_list($z_id)
	{
		$this->db->select('*');
		$this->db->from('tbl_domestic_rate_master');
		$this->db->join('region_master', 'region_master.region_id = tbl_domestic_rate_master.zone_id','left');	
		$this->db->where('zone_id',$z_id);	
		$query=$this->db->get();		
		return $query->result_array();
	}
	public function get_domestic_state_rate_list($z_id)
	{
		$this->db->select('*');
		$this->db->from('tbl_domestic_state_rate');
		$this->db->join('region_master', 'region_master.region_id = tbl_domestic_state_rate.zone_id','left');
		$this->db->join('state', 'state.id = tbl_domestic_state_rate.state_id','left');		
		$this->db->where('zone_id',$z_id);	
		$query=$this->db->get();		
		return $query->result_array();
	}
	public function get_domestic_city_rate_list($z_id)
	{
		$this->db->select('*');
		$this->db->from('tbl_domestic_city_rate');
		$this->db->join('region_master', 'region_master.region_id = tbl_domestic_city_rate.zone_id','left');
		$this->db->join('state', 'state.id = tbl_domestic_city_rate.state_id','left');	
		$this->db->join('city', 'city.id = state.id','left');		
		$this->db->where('zone_id',$z_id);	
		$query=$this->db->get();		
		return $query->result_array();
	}
	public function get_international_table_row($tablename,$where)
	{
		$this->db->select('*');
		$this->db->from($tablename);
		if(!empty($where))
		{
			$this->db->where($where);
		}
		$this->db->group_by('courier_company_id');
		$this->db->group_by('from_date');
		
		$query	=	$this->db->get();	
		echo $this->db->last_query();exit;
		return $query->row();
	}
		public function get_international_rate_result($id)
	{
		$this->db->select('*');
		$this->db->from('courier_company');
		$this->db->join('tbl_international_rate_master', "tbl_international_rate_master.courier_company_id=courier_company.c_id and customer_id=$id",'left');
		$this->db->where('courier_company.company_type','International');
		$this->db->group_by('c_id');
		$this->db->group_by('tbl_international_rate_master.from_date');
		$this->db->order_by('tbl_international_rate_master.from_date','DESC');
		
		$query	=	$this->db->get();	
	//	echo $this->db->last_query();exit;
		return $query->result_array();
	}
	public function get_added_international_customer()
	{
		$this->db->select('*');
		$this->db->from('tbl_international_rate_master');		
		$this->db->join('tbl_customers', 'tbl_customers.customer_id = tbl_international_rate_master.customer_id');
		$this->db->join('courier_company', 'courier_company.c_id = tbl_international_rate_master.courier_company_id','left');
		$this->db->group_by('courier_company_id');
		$this->db->group_by('type_export_import');
		$query	=	$this->db->get();	
		//echo $this->db->last_query();exit;
		return $query->result_array();
	}
	public function get_added_domestic_customer($whr)
	{
		$this->db->select('*');
		$this->db->from('tbl_domestic_rate_master');
		$this->db->join('tbl_customers', 'tbl_customers.customer_id = tbl_domestic_rate_master.customer_id');
		$this->db->join('courier_company', 'courier_company.c_id = tbl_domestic_rate_master.c_courier_id','left');
		$this->db->group_by('tbl_domestic_rate_master.customer_id');
		$this->db->group_by('tbl_domestic_rate_master.c_courier_id');
		$this->db->group_by('tbl_domestic_rate_master.applicable_from');
		$this->db->order_by('tbl_domestic_rate_master.rate_id','DESC');
		if(!empty($whr))
		{
			$this->db->where($whr);
		}
		$query=$this->db->get();
		//echo $this->db->last_query();exit;		
		return $query->result_array();
	}
		public function insert_rate($tablename,$data,$to_customer_id,$transfer_date) 
	{ 

		for($cust=0;$cust< count($to_customer_id);$cust++)
		{
					foreach ($data as $value) {
						  $insert_data = array(
						  	'courier_company_id' =>$value['courier_company_id'],
						  	'zone_id' =>$value['zone_id'],
						  	'country_id' =>$value['country_id'],
						  	'type_export_import' =>$value['type_export_import'],
						  	'from_date' =>$transfer_date,
						  	'customer_id' =>$to_customer_id[$cust],
						  	'weight_from' =>$value['weight_from'],
						  	'weight_to' =>$value['weight_to'],
						  	'doc_type' =>$value['doc_type'],
						  	'fixed_perkg' =>$value['fixed_perkg'],
						  	'rate' =>$value['rate'],
						  );

						$this->db->insert($tablename,$insert_data);
					}
			//echo $this->db->last_query();
			//  echo $this->db->last_query();
	 		//exit(); 
		}
	}
	public function insert_domestic_rate($tablename,$data,$to_customer_id,$transfer_date) 
	{ 
		for($cust=0;$cust< count($to_customer_id);$cust++)
		{
					foreach ($data as $value) {
						  $insert_data = array(
						  	'c_courier_id' =>$value['c_courier_id'],
						  	'mode_id' =>$value['mode_id'],
						  	'zone_id' =>$value['zone_id'],						  	
						  	'doc_type' =>$value['doc_type'],
						  	'applicable_from' =>$transfer_date,
						  	'customer_id' =>$to_customer_id[$cust],
						  	'weight_range_from' =>$value['weight_range_from'],
						  	'weight_range_to' =>$value['weight_range_to'],	
						  	'weight_slab'=>$value['weight_slab'],
						  	'fixed_perkg' =>$value['fixed_perkg'],
						  	'rate' =>$value['rate'],
						  );

						$this->db->insert($tablename,$insert_data);
					}
			//echo $this->db->last_query();
			//  echo $this->db->last_query();
	 		//exit(); 
		}
	}
}
?>