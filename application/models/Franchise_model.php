<?php
class Franchise_model extends CI_Model
{
	public function get_franchise_details()
	{
		$this->db->select('*');
		$this->db->from('tbl_customers');
		$this->db->join('state', 'state.id = tbl_customers.state','left');	
		$this->db->join('city', 'city.id = tbl_customers.city','left');	
		$this->db->join('tbl_franchise','tbl_customers.customer_id = tbl_franchise.fid','inner');
		$query	=	$this->db->get();
		//echo $this->db->last_query();
		return $query->result_array();
	}

}