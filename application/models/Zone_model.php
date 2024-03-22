<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zone_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	
	public function get_international_zone_master_result($where)
	{
		$this->db->select('*');
		$this->db->from('zone_master');
		$this->db->join('courier_company', 'courier_company.c_id = zone_master.c_courier_id','left');	
		if(!empty($where) && $where != '')
		{
			$this->db->where($where);
		}
		$query	=	$this->db->get();
		//echo "==".$this->db->last_query();exit;
		return $query->result();
	}
	public function get_international_zone_master_row($id)
	{
		$this->db->select('*');
		$this->db->from('zone_master');
		$this->db->join('courier_company', 'courier_company.c_id = zone_master.c_courier_id','left');			
		$this->db->where('z_id',$id);
		
		$query	=	$this->db->get();
		//echo "==".$this->db->last_query();exit;
		return $query->row();
	}
	// public function get_domestic_zone_master_result()
	// {
	// 	$this->db->select('*');
	// 	$this->db->from('domestic_zone_master');
	// 	$this->db->join('courier_company', 'courier_company.c_id = domestic_zone_master.c_courier_id','left');	
	// 	if(!empty($where) && $where != '')
	// 	{
	// 		$this->db->where($where);
	// 	}
	// 	$query	=	$this->db->get();
	// 	//echo "==".$this->db->last_query();exit;
	// 	return $query->result();
	// }
	public function get_domestic_zone_master_row($id)
	{
		$this->db->select('*');
		$this->db->from('domestic_zone_master');
		$this->db->join('courier_company', 'courier_company.c_id = domestic_zone_master.c_courier_id','left');			
		$this->db->where('z_id',$id);
		
		$query	=	$this->db->get();
		//echo "==".$this->db->last_query();exit;
		return $query->row();

	}
	public function get_international_zone_row()
	{
		$this->db->select('*');
		$this->db->from('zone_master');
		$this->db->join('courier_company', 'courier_company.c_id = zone_master.c_courier_id','left');	
		$this->db->group_by('c_courier_id');
		$this->db->group_by('zone_type');
		$this->db->group_by('uploaded_date');
		$query	=	$this->db->get();	
		// echo $this->db->last_query();exit;
		return $query->result_array();
	}
	public function get_all_result_region($tablename,$where='')
	{
		if($where!='')
			$this->db->where($where); 
		$this->db->group_by('region_name','asc');
		$query = $this->db->get($tablename);

	 // echo $this->db->last_query();
	// exit(); 
		return $query->result_array();
	}
}
?>