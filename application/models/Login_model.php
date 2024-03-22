<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model {
    
    public function checkLogin($email,$password)
	{ 
		$this->db->select('*');
		$this->db->from('tbl_customers');
		$this->db->where('cid',$email);
		$this->db->where('password',$password);
		
		$query=$this->db->get();
		if($query->num_rows() == 1)
		{
		    $email = $query->row()->customer_name;
			$customer_id = $query->row()->customer_id;
			$this->session->set_userdata("customer_name",$email);
			$this->session->set_userdata("customer_id",$customer_id);
			return true;
		}	
		else
		{
			return false;
		}
		
	}
    public function checkfranchiseLogin($email,$password)
	{ 
		$this->db->select('*');
		$this->db->from('tbl_customers');
		$this->db->where('email',$email);
		$this->db->where_in('customer_type', ['1','2']);
		$this->db->where('password',$password);
		$this->db->where('isdeleted',0);
		
		$query=$this->db->get();
			// echo $this->db->last_query();exit;
		if($query->num_rows() == 1)
		{
		    $email = $query->row()->email;
		    $customer_name = $query->row()->customer_name;
		    $customer_type = $query->row()->customer_type;
			$customer_id = $query->row()->customer_id;
			$branch_id = $query->row()->branch_id;
			$branch = $this->db->query("select branch_name from tbl_branch where branch_id = '$branch_id'")->row();
			$branch_name = $branch->branch_name;
			$this->session->set_userdata("customer_name",$customer_name);
			$this->session->set_userdata("email",$email);
			$this->session->set_userdata("customer_type",$customer_type);
			$this->session->set_userdata("customer_id",$customer_id);
			$this->session->set_userdata("branch_name",$branch_name);
			$this->session->set_userdata("branch_id",$branch_id);
			return $query->result_array();
		}	
		else
		{
			return false;
		}
		
	}
	
	 public function checkAdminLogin($username,$password)
	{ 
		$this->session->unset_userdata("userName");
		$this->session->unset_userdata("userId");
		$this->session->unset_userdata("userPic");
		$this->session->unset_userdata("loggedin");
		$this->session->unset_userdata("userType");
		
		$this->db->select('*');
		$this->db->from('tbl_users');
		$this->db->where('username',$username);
		$this->db->where('password',$password);
		
		$query=$this->db->get();
		
		if($query->num_rows() == 1)
		{
		    $username = $query->row()->username;
			$userId = $query->row()->user_id;
			$userType = $query->row()->user_type;
			$userPic = $query->row()->profilepic_url;
			$branch_id = $query->row()->branch_id;
			$this->session->set_userdata("userName",$username);
			$this->session->set_userdata("userId",$userId);
			$this->session->set_userdata("userType",$userType);
			$this->session->set_userdata("userPic",$userPic);
			$this->session->set_userdata("branch_id",$branch_id);
			$this->session->set_userdata("loggedin",true);
			return true;
		}	
		else
		{
			return false;
		}
	}
	public function get_count_international_pod()
	{
		$this->db->select('COUNT(*) AS int_cnt');
		$this->db->from('tbl_international_booking');	
		$query=$this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
	}
	public function get_count_domestic_pod()
	{
		$this->db->select('COUNT(*) AS int_cnt');
		$this->db->from('tbl_domestic_booking');	
		$query=$this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
	}
	public function get_count_delivered_international_pod()
	{
		$this->db->select('COUNT(*) AS int_cnt');
		$this->db->from('tbl_international_tracking');	
		$this->db->where('status','Delivered');
		$query=$this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
	}
	public function get_count_delivered_domestic_pod()
	{
		$this->db->select('COUNT(*) AS int_cnt');
		$this->db->from('tbl_domestic_tracking');	
		$this->db->where('status','Delivered');
		$query=$this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
	}
	public function total_cash($table_name,$whr)
	{
		$this->db->select('SUM(`sub_total`) AS total_cash');
		$this->db->from($table_name);
		if(!empty($whr)){	
			$this->db->where($whr);
		}
		$query=$this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
	}

	
}
?>