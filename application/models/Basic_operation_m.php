<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Basic_operation_m extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function getAll($tablename,$where='')
	{
		if($where!='')
			$this->db->where($where); 
		$query = $this->db->get($tablename);
		return $query;
	}
	public function get_all_result($tablename,$where='',$orderby='')
	{
		if($where!='')
			$this->db->where($where); 
		
		if($orderby!='')
			$this->db->order_by($orderby); 
		$query = $this->db->get($tablename);
	 // echo $this->db->last_query();
	// exit(); 
		return $query->result_array();
	}
	public function insert_ignore($tablename,$data) 
	{ 
		$insert_query = $this->db->insert_string($tablename, $data);
		$insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
		$this->db->query($insert_query);
		return $this->db->insert_id();
		 // echo $this->db->last_query();
	 	// exit(); 
		//return 
	} 
	
	public function insert($tablename,$data) 
	{ 
		$this->db->insert($tablename,$data);
		return $this->db->insert_id();
		 // echo $this->db->last_query();
	 	// exit(); 
		//return 
	} 

	public function update($tablename,$data,$where) 
	{ 
		$this->db->where($where); 
		$this->db->update($tablename,$data);
	  //echo $this->db->last_query();
	  //exit(); 
	} 

	public function delete($tablename,$where) 
	{ 
		$this->db->where($where); 
		$this->db->delete($tablename); 
	}
	
	public function truncateAll($tablename) 
	{ 
		$this->db->truncate($tablename); 
	}
	
	public function insert_batch($table,$data) 
	{
		$this->db->insert_batch($table, $data); 
	}
	
	public function getColumn($table)
	{
		$res = '';
		// Query database to get column names  
		$query = $this->db->query("show columns from $table");
		if($query->num_rows() > 0)
		{
			$res =	$query->result_array();
			/*foreach($res as $row)
			{
				$outstr.= $row['Field'].',';	
			}*/
		}
		return $res;
		//$outstr = substr($outstr, 0, -1)."\n";
	}
	
	public function selectRecord($tablename,$where)
	{
		$this->db->select('*');
		$this->db->from($tablename);
		$this->db->where($where);
		$query=$this->db->get();
		return $query;
	}
	
	public function get_table_row($tablename,$where)
	{
		$this->db->select('*');
		$this->db->from($tablename);
		if(!empty($where))
		{
			$this->db->where($where);
		}
		$query	=	$this->db->get();	
		//echo $this->db->last_query();exit;
		return $query->row();
	}
	
	public function get_table_result($tablename,$where)
	{
		$this->db->select('*');
		$this->db->from($tablename);
		if(!empty($where) && $where != '')
		{
			$this->db->where($where);
		}
		$query	=	$this->db->get();
		//echo "==".$this->db->last_query();exit;
		return $query->result();
	}
	
	// this function is use for inserting the data into subscriber event 
	public function get_query_row($all_querys)
	{
		$query = $this->db->query($all_querys);	
		return $query->row();
		
	}
	
	// this function is use for inserting the data into subscriber event 
	public function get_query_result($all_querys)
	{
		$query = $this->db->query($all_querys);	
		return $query->result();
		
	}
public function get_query_result_array($all_querys)
	{
		$query = $this->db->query($all_querys);	
		return $query->result_array();
		
	}
	
	public function getAllUsers()
	{
		$this->db->select('*,tbl_users.email as user_email_id,tbl_users.phoneno as user_phoneno');
		$this->db->from('tbl_users');
		$this->db->join('tbl_user_types', 'tbl_user_types.user_type_id = tbl_users.user_type','left');	
		$this->db->join('tbl_branch', 'tbl_users.branch_id = tbl_branch.branch_id','left');		
		$query = $this->db->get();
		return $query->result_array();			
	}
	public function get_single_User($id)
	{
		$this->db->select('*');
		$this->db->from('tbl_users');
		$this->db->join('tbl_user_types', 'tbl_user_types.user_type_id = tbl_users.user_type','left');			
		$this->db->where('tbl_users.user_id',$id);
		$query = $this->db->get();
		return $query->row();	
		
	}
	public function select_pod_details($branch_name)
	{
		$this->db->select('*,sum(total_pcs) as total_pcs,sum(total_weight) as total_weight');
		$this->db->from('tbl_menifiest');	
		$this->db->where('tbl_menifiest.source_branch',$branch_name);
		$this->db->group_by('manifiest_id');
		$query=$this->db->get();			
		return $query->result_array();
	}
	
	public function selectCityRecord()
	{
		$this->db->select('*');
		$this->db->from('city');
		$this->db->join('state', 'state.id = city.state_id','left');
		$query=$this->db->get();		
		return $query->result_array();
	}
	public function selectStateRecord()
	{
		$this->db->select('*');
		$this->db->from('state');
		$this->db->join('tbl_country', 'tbl_country.country_id = state.country_id','left');
		$this->db->join('region_master', 'region_master.region_id = state.region_id','left');
		$query=$this->db->get();		
		return $query->result_array();
	}
	public function get_charge_master_result($id)
	{
		$this->db->select('*');
		$this->db->from('courier_charge_master');
		$this->db->join('courier_company', 'courier_company.c_id = courier_charge_master.country_id','left');
		$this->db->where('country_id',$id);
		$query	=	$this->db->get();
		return $query->result();
	}
	public function get_charge_master_row($whr)
	{
		$this->db->select('*');
		$this->db->from('courier_charge_master');
		$this->db->join('courier_company', 'courier_company.c_id = courier_charge_master.country_id','left');
		$this->db->where($whr);
		$query	=	$this->db->get();
		//echo $this->db->last_query();
		return $query->row();
	}

	public function insert_domestic_rate($tablename,$data) 
	{ 
		$data1 = array();
		
		for($cust=0;$cust< count($data['customer_id']);$cust++)
		{			
				for($di=0;$di<count($data['weight_range_from']);$di++)
				{		
					$weight_slab=0;
					if($data['fixed_perkg'][$di]>0)
					{
						$weight_slab = ((round($data['weight_range_to'][$di]) *1000) - (round($data['weight_range_from'][$di]) *1000));
					}
					if (!empty($data['city_id'])) {
						foreach ($data['city_id'] as $key => $value) {

							// city

							$this->db->select('*');
							$this->db->from('city');
							
							$this->db->where('id',$value);
							$query	=	$this->db->get();

							$city_d = $query->row_array();
							// echo "<pre>";
							// print_r($city_d);
							if (!empty($city_d)) {
					$data1 = array('customer_id'=>$data['customer_id'][$cust],
						'c_courier_id'=>$data['c_courier_id'],
						'state_id'=>$city_d['state_id'],
						'city_id'=>$value,
						'from_zone_id'=>$data['from_zone_id'],
						'to_zone_id'=>$data['to_zone_id'],
						'mode_id'=>$data['mode_id'],
						'applicable_from'=>date("Y-m-d",strtotime($data['applicable_from']) ),				
						'weight_range_from'=>$data['weight_range_from'][$di],
						'weight_range_to'=>$data['weight_range_to'][$di],
						'weight_slab'=>$weight_slab,
						'rate'=>$data['rate'][$di],
						'fixed_perkg'=>$data['fixed_perkg'][$di]

					);
					$this->db->insert('tbl_domestic_rate_master',$data1);
							}
						}
					}
					elseif (!empty($data['state_id'])) {
						foreach ($data['state_id'] as $key => $state_d) {

							if (!empty($state_d)) {
					$data1 = array('customer_id'=>$data['customer_id'][$cust],
						'c_courier_id'=>$data['c_courier_id'],
						'state_id'=>$state_d,
						'city_id'=>'',
						'from_zone_id'=>$data['from_zone_id'],
						'to_zone_id'=>$data['to_zone_id'],
						'mode_id'=>$data['mode_id'],
						'applicable_from'=>date("Y-m-d",strtotime($data['applicable_from']) ),				
						'weight_range_from'=>$data['weight_range_from'][$di],
						'weight_range_to'=>$data['weight_range_to'][$di],
						'weight_slab'=>$weight_slab,
						'rate'=>$data['rate'][$di],
						'fixed_perkg'=>$data['fixed_perkg'][$di]

					);
					$this->db->insert('tbl_domestic_rate_master',$data1);
							}
						}
					}
					else
					{
						$data1 = array('customer_id'=>$data['customer_id'][$cust],
						'c_courier_id'=>$data['c_courier_id'],
						'from_zone_id'=>$data['from_zone_id'],
						'to_zone_id'=>$data['to_zone_id'],
						'mode_id'=>$data['mode_id'],
						'applicable_from'=>date("Y-m-d",strtotime($data['applicable_from']) ),				
						'weight_range_from'=>$data['weight_range_from'][$di],
						'weight_range_to'=>$data['weight_range_to'][$di],
						'weight_slab'=>$weight_slab,
						'rate'=>$data['rate'][$di],
						'fixed_perkg'=>$data['fixed_perkg'][$di]

						);
						$this->db->insert('tbl_domestic_rate_master',$data1);
					}

				}
	  }
	}
	public function get_rate_report_header($where)
	{
		$this->db->select('*');
		$this->db->from('tbl_international_rate_master');
		$this->db->group_by('zone_id');
		// $this->db->join('courier_company', 'courier_company.c_id = tbl_internatial_rate_master.courier_company_id','left');	
		// $this->db->join('zone_master', 'zone_master.z_id = tbl_internatial_rate_master.zone_id','left');
		// $this->db->join('tbl_country', 'tbl_country.country_id = tbl_internatial_rate_master.country_id','left');
		// $this->db->join('tbl_customers', 'tbl_customers.customer_id = tbl_internatial_rate_master.customer_id','left');

		if(!empty($where))
		{
			$this->db->where($where);
		}
		$query	=	$this->db->get();	
		//echo $this->db->last_query();exit;
		return $query->result_array();
	}	
	public function get_rate_report_weight($where)
	{
		$this->db->select('*');
		$this->db->from('tbl_international_rate_master');
		$this->db->group_by('weight_from');
		$this->db->group_by('doc_type');
		// $this->db->join('courier_company', 'courier_company.c_id = tbl_internatial_rate_master.courier_company_id','left');	
		// $this->db->join('zone_master', 'zone_master.z_id = tbl_internatial_rate_master.zone_id','left');
		// $this->db->join('tbl_country', 'tbl_country.country_id = tbl_internatial_rate_master.country_id','left');
		// $this->db->join('tbl_customers', 'tbl_customers.customer_id = tbl_internatial_rate_master.customer_id','left');

		if(!empty($where))
		{
			$this->db->where($where);
		}
		$query	=	$this->db->get();	
		//echo $this->db->last_query();exit;
		return $query->result_array();
	}	
	public function get_rate_report_body($where)
	{
		$this->db->select('*');
		$this->db->from('tbl_international_rate_master');
		
		if(!empty($where))
		{
			$this->db->where($where);
		}
		
		$query	=	$this->db->get();	
		//echo $this->db->last_query();exit;
		return $query->result_array();
	}	

	public function get_max_number($tablename,$field)
	{
		$this->db->select($field);
		$this->db->from($tablename);				
		$query=$this->db->get();	
		return $query->row();
	}
	public function get_customer_details($whr)
	{
		$this->db->select('*,city.city AS city_name,city.id AS city_id,state.state AS state_name,state.id AS state_id');
		$this->db->from('tbl_customers');
		$this->db->join('city', 'city.id = tbl_customers.city','left');	
		$this->db->join('state', 'state.id = tbl_customers.state','left');	
		if($whr!='')
		{
			$this->db->where($whr); 
		}
		
		$query = $this->db->get();
		return $query->row();			
	}
	
	// getting user 
	public function get_all_ticket()
	{
		$user_id 							= $this->session->userdata('customer_id');
		if($user_id != 1)
		{
			$this->db->where('ticket.user_id',$user_id);
		}
		$this->db->select('*');
		$this->db->from('ticket');
		$this->db->join('tbl_customers','tbl_customers.customer_id = ticket.user_id');
		$this->db->join('ticket_status','ticket_status.status_id = ticket.ticket_status');
		$this->db->order_by('ticket_id','desc');
		$this->db->group_by('ticket_id');
		$query = $this->db->get();
		return $query->result();
	}
	
	// getting user 
	public function get_admin_all_ticket()
	{
		
		$this->db->select('*');
		$this->db->from('ticket');
		$this->db->join('tbl_customers','tbl_customers.customer_id = ticket.user_id');
		$this->db->join('ticket_status','ticket_status.status_id = ticket.ticket_status');
		$this->db->order_by('ticket_id','desc');
		$this->db->group_by('ticket_id');
		$query = $this->db->get();
		return $query->result();
	}
	
	// getting user 
	public function get_all_ticket_by_status($status)
	{
		$user_id 							= $this->session->userdata('user_id');
		if($user_id != 1)
		{
			$this->db->where('ticket.user_id',$user_id);
		}
		$this->db->select('count(ticket_id) as total_ticket');
		$this->db->from('ticket');
		$this->db->join('tbl_customers','tbl_customers.customer_id = ticket.user_id');
		$this->db->join('ticket_status','ticket_status.status_id = ticket.ticket_status');
		$this->db->where('ticket_status',$status);
		$this->db->order_by('ticket_id','desc');
		$query = $this->db->get();
		return $query->row();
	}
	
	// this function is use for getting admin info 
	public function get_admin_info_by_id($admin_id)
	{
		$this->db->select('*');
		$this->db->from('tbl_users');
		$this->db->where('user_id',$admin_id);
		$query = $this->db->get();
		return $query->row();
	}

	// this function is use for getting admin info 
	public function get_user_info_by_id($admin_id)
	{
		$this->db->select('*');
		$this->db->from('tbl_customers');
		$this->db->where('customer_id',$admin_id);
		$query = $this->db->get();
		return $query->row();
	}
	
	// this function is use for getting admin info 
	public function get_domestic_pod($user_id)
	{
		$this->db->select('*');
		$this->db->from('tbl_domestic_booking');
		$this->db->where('customer_id',$user_id);
		$query = $this->db->get();
		return $query->result();
	}
	
	// this function is use for getting admin info 
	public function get_international_pod($user_id)
	{
		$this->db->select('*');
		$this->db->from('tbl_international_booking');
		$this->db->where('customer_id',$user_id);
		$query = $this->db->get();
		return $query->result();
	}
	
		// getting user 
	public function get_ticket_info($ticket_id)
	{
		$this->db->select('*,ticket.user_id');
		$this->db->from('ticket');
		$this->db->join('tbl_customers','tbl_customers.customer_id = ticket.user_id');
		$this->db->join('ticket_status','ticket_status.status_id = ticket.ticket_status');
		$this->db->where('ticket_id',$ticket_id);
		$query = $this->db->get();
		return $query->row();
	}
	
	// getting user 
	public function get_ticket_chat($ticket_id)
	{
		$this->db->select('*');
		$this->db->from('ticket_msg');
		$this->db->join('tbl_customers','tbl_customers.customer_id = ticket_msg.user_type','left');
		$this->db->where('ticket_id',$ticket_id);
		$this->db->order_by('ticket_msg.msg_id','desc');
		$this->db->group_by('ticket_msg.msg_id');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_all_pod_data_dashboard($where)
	{
		$this->db->select('*');
		$this->db->from('tbl_international_booking');	
		$this->db->join('tbl_international_weight_details', 'tbl_international_weight_details.booking_id = tbl_international_booking.booking_id','left');
		$this->db->join('tbl_customers', 'tbl_customers.customer_id = tbl_international_booking.customer_id','left');		
		$this->db->join('zone_master', 'zone_master.z_id = tbl_international_booking.reciever_country_id','left');
		$this->db->where($where);
		$this->db->order_by('tbl_international_booking.booking_id','Desc');	
		$this->db->limit(5);		
		$query=$this->db->get();
		//echo $this->db->last_query();exit;
		return $query->result_array();
	}
	
	public function get_all_pod_data_domestic_dashboard($where)
	{
		$this->db->select('*');
		$this->db->from('tbl_domestic_booking');	
		$this->db->join('tbl_domestic_weight_details', 'tbl_domestic_weight_details.booking_id = tbl_domestic_booking.booking_id','left');
		$this->db->join('tbl_customers', 'tbl_customers.customer_id = tbl_domestic_booking.customer_id','left');
		$this->db->join('city', 'city.id = tbl_domestic_booking.reciever_city','left');				
		$this->db->order_by('tbl_domestic_booking.booking_id','Desc');		
		$this->db->where($where);		
		$this->db->limit(5);	
		$query=$this->db->get();

		//echo "++++++".$this->db->last_query();exit;
		return $query->result_array();
	}
	
	public function get_count_international_pod($where)
	{
		$this->db->select('COUNT(*) AS int_cnt');
		$this->db->from('tbl_international_booking');	
		$this->db->where($where);		
		$query=$this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
	}
	public function get_count_domestic_pod($where)
	{
		$this->db->select('COUNT(*) AS int_cnt');
		$this->db->from('tbl_domestic_booking');	
		$this->db->where($where);		
		$query=$this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
	}
	
// 	add by pritesh 
	    function fileUpload($data,$path='assets/images/',$cstm=false,$creat_thumb=false)
    {
        if(!is_dir($path))
        {
            mkdir($path);
        }
        $ret = false;
        $arr = array();
        //set actual path for image
        $target_dir = '';
        $image_type = strtolower(pathinfo($data['name'],PATHINFO_EXTENSION));
        $image_name = rand(99,10).date('Ymdhis').'.'.$image_type;
        if($image_type =='jpg'|| $image_type =='jpeg' || $image_type =='png' || $image_type =='gif' || $image_type =='bmp' || $image_type =='mp4'||$image_type =='pdf'||$image_type =='docx'||$image_type =='dot')
          $ret =true;

        if($cstm)
            $ret = true;
        /*if($data['size']> 2000000)
          $ret = false;*/
        if($ret)
          if(move_uploaded_file($data['tmp_name'],$path.$image_name))
          {
            //check wheather the person ask for watermark or not if do below
            if($creat_thumb)
            {
                $arr['image_name'] = $this->create_watermark($path.$image_name,$image_type,$path);
            }
            else
                $arr['image_name'] = $image_name;
            $success = " New record added successfully.";
          }
          else
            $success = "Can not upload file.";
        else
          $success = "Unable to add record.";
        $arr['status'] = $ret;
        $arr['message'] = $success;
        return $arr;
    }

    function unlinkImage($file)
  {
      if(file_exists($file))
          return unlink($file);
      else
          return true;
  }
  
   
	
}
?>