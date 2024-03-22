<?php
class Customer_model extends CI_Model
{
	public function get_customer_details($where)
	{
		$this->db->select('tbl_customers.*,aw.customer_name as parent_name,state.state,city.city');
		$this->db->from('tbl_customers');
		$this->db->join('tbl_customers aw', 'aw.customer_id = tbl_customers.parent_cust_id','left');	
		$this->db->join('state', 'state.id = tbl_customers.state','left');	
		$this->db->join('city', 'city.id = tbl_customers.city','left');		
						
		if(!empty($where))
		{
			$this->db->where($where);
		}
		$this->db->order_by('customer_id','DESC');
		$query	=	$this->db->get();		
		return $query->result_array();
	}
	public function get_customer_rate_details($whr)
	{


		$this->db->select('*');
		$this->db->from('tbl_customers');
		$this->db->join('tbl_rate_master', 'tbl_rate_master.customer_id = tbl_customers.customer_id','left');		
		$this->db->join('region_master', 'region_master.region_id = tbl_rate_master.region_id','left');	
				
		if(!empty($whr))
		{
			$this->db->where($whr);
		}

		$query	=	$this->db->get();	

		//echo $this->db->last_query();exit;
		return $query->result_array();
	}


}
