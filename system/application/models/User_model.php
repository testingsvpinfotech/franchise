<?php
class User_model extends CI_Model
{
   public function register_users($data)
	{ 
		$this->db->insert('user', $data);
		
	}
   public function login_user($data)
	{
		$this->db->where('user_name', $data['user_name']);
	    $this->db->where('password', ($data['password']));
	    return $this->db->get('user')->row();
	}
}
?>