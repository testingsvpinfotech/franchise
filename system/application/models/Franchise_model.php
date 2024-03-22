<?php
class Franchise_model extends CI_Model
{
	public function get_franchise_details()
	{
		$this->db->select('*');
		$this->db->from('tbl_franchise');
		$this->db->join('state', 'state.id = tbl_franchise.state','left');	
		$this->db->join('city', 'city.id = tbl_franchise.city','left');		
		$this->db->order_by('franchise_id','DESC');
		$query	=	$this->db->get();
		//echo $this->db->last_query();
		return $query->result_array();
	}

}