<?php
//Author:Pawan Kumar Saini
class Admin extends BD_Controller {

    function __construct()
    {
      // Construct the parent class
      parent::__construct();
      $this->auth();
      $this->load->model('M_main');
      $this->load->helper(array('form', 'url'));
      $this->load->library('form_validation');
      $this->load->library("pagination");
      //$this->write_request_file();
    }

    //api for screen d_01
    public function admin_dashboard_get()
    {
    	$assign_pick = $this->M_main->count_data('order',['status'=>4]);
    	$process_order = $this->M_main->count_data('order',['status'=>3]);
    	$pending_order = $this->M_main->count_data('order',['status'=>1]);
    	$today_delivery = $this->M_main->count_data('order',['status !='=>2,'DATE(delivery_date)' =>date("Y-m-d")]);
    	$data['process_order'] = $process_order;
    	$data['pending_order'] = $pending_order;
    	$data['today_delivery'] = $today_delivery;
    	$data['assign_pickupboy'] = $assign_pick;
    	$data['accept_order'] = 0;
    	$data['track_order'] = 0;
    	$data['ready_for_delivery'] = 0;
    	$data['extra'] = 0;
    	$data['late_order'] = 0;
    	$data['today_query'] = 0;
    	$this->response(['data'=>$data,'status'=>true,'message'=>'Order get successfully.']);
    }


    public function pickup_list_post()
    {

      $_POST = json_decode(file_get_contents("php://input"), true);
      $date = !empty($this->post('date')) ? $this->post('date') : date("Y-m-d");
      $query =$this->db->query("SELECT o.order_no,o.status,o.user_id,o.pickupboy_id,u.full_name as pickupboy FROM `order` as o LEFT JOIN tbl_users as u on o.pickupboy_id=u.user_id  where o.status IN(3,4) and DATE(o.created_at)='".$date."'");
      
     
      $q= $this->db->query("SELECT o.order_no,o.status,o.user_id,o.pickupboy_id,u.full_name as pickupboy FROM `order` as o LEFT JOIN tbl_users as u on o.pickupboy_id=u.user_id where o.status IN(3,4) and DATE(o.created_at)='".$date."' ");
      foreach ($q->result() as $key => $value)
      {
        $r = $this->db->query("SELECT s.name as store from shop as s join tbl_users as u ON s.shop_id=u.shop_id where u.user_id='$value->user_id' ");
        $q->result()[$key]->store_name = $r->result()[0]->store;
      }

      $data['data'] = $q->result();
     
      if($data['data']){
        $this->response(['data'=>$data,'path'=>base_url().'uploads/orders/','status'=>true,'message'=>'Order get successfully.']);
      }else{
        $this->response(['data'=>[],'status'=>false,'message'=>'No Results founds.']);
      }
    }
    public function pickup_order_detail_post()
    {
      $_POST = json_decode(file_get_contents("php://input"), true);
      $this->form_validation->set_rules('order_no','Bill no.','trim|required');
      if($this->form_validation->run() == false)
      {
        $error= $this->form_validation->error_array();
        $this->response(['data'=>[],'status'=>false,'message'=>array_values($error)[0]]);

      }else{
        $order_no = $this->post('order_no');
        $query = $query =$this->db->query("SELECT o.order_no,o.status,o.user_id,o.pickupboy_id,o.qr_code,o.pickup_date,u.full_name as pickupboy,s.name as store FROM `order` as o LEFT JOIN tbl_users as u on o.pickupboy_id=u.user_id LEFT JOIN shop as s on u.shop_id=s.shop_id where o.order_no='".$order_no."'");

        $order_data = $query->result();
        if(!empty($order_data)) {
            $data = [];
            $data['order'] = $order_data;
            $user_type = 16;
            $this->db->select('user_id as id,full_name as pickupboy');
            $this->db->where('find_in_set("'.$user_type.'", user_type) <> 0');
            $this->db->from('tbl_users');
            $query = $this->db->get();
            $data['pickupboy']=$query->result();
            $this->response(['data'=>$data,'status'=>true,'message'=>'Order get successfully.']);
        }else{
          $this->response(['data'=>[],'status'=>false,'message'=>'Something goes wrong.']);
        }
      }

    }


    public function assign_pickupboy_post()
    {
      $_POST = json_decode(file_get_contents("php://input"), true);
      $this->form_validation->set_rules('order_no','Bill no.','trim|required');
      $this->form_validation->set_rules('store_name','Store Name','trim|required');
      $this->form_validation->set_rules('qr_code','Qr Code','trim|required');
      $this->form_validation->set_rules('pickupboy_id','Pickup Boy','trim|required');
      $this->form_validation->set_rules('pickup_date','Pickup Date','trim|required');
      if($this->form_validation->run() == false)
      {
        $error= $this->form_validation->error_array();
        $this->response(['data'=>[],'status'=>false,'message'=>array_values($error)[0]]);
      }else{
        $qr_code = $this->post('qr_code');
        $order_no = $this->post('order_no');
        $pickupboy_id = $this->post('pickupboy_id');
        $pickup_date = $this->post('pickup_date');
        $order_data = $this->M_main->getdata('order',['qr_code'=>$qr_code,'order_no'=>$order_no,'status'=>3]);
        if($order_data)
        {
          $this->M_main->update_data(['status'=>4,'pickupboy_id'=>$pickupboy_id,'pickup_date'=>$pickup_date],'order',$order_data[0]->id);
          $this->response(['data'=>[],'status'=>true,'message'=>'Pickup Boy assigned successfully.']);
        }else{
          $this->response(['data'=>[],'status'=>false,'message'=>'Something goes wrong.']);
        }
      }

    }

    //api for screen d_02
  	public function pending_order_list_get()
  	{
      $query = $this->db->query("SELECT o.*,u.full_name FROM `order` as o JOIN tbl_users as u on o.user_id = u.user_id and o.status=1 group by o.order_no order by o.id DESC");
      $config = array();
      $config["base_url"] = base_url() . "api/admin/pending_order_list";
      $config["total_rows"] = count($query->result());
      $config["per_page"] = 20;
      $config['enable_query_strings'] = TRUE;
      $config['page_query_string'] = TRUE;
      $config['use_page_numbers'] = TRUE;
      $config['reuse_query_string'] = TRUE;
      $config['query_string_segment'] = 'page';
      $this->pagination->initialize($config);
      $offset = ($this->input->get('page')) ? ( ( $this->input->get('page') - 1 ) * $config["per_page"] ) : 0;
      $q = $this->db->query("SELECT o.*,u.full_name FROM `order` as o JOIN tbl_users as u on o.user_id = u.user_id and o.status=1 group by o.order_no LIMIT $offset,".$config['per_page']."");
      $data['data'] = $q->result();
      $this->pagination->create_links();
      $num_pages = ceil($config["total_rows"] /$config["per_page"]);
      $next = ($num_pages>$this->pagination->cur_page) ? $this->pagination->cur_page + 1:0;
      $prev = ($num_pages<=$this->pagination->cur_page) ? $this->pagination->cur_page - 1:0;
      $data['pagination']=["current_page"=>$this->pagination->cur_page,"prev_page"=>$prev,"next_page"=>$next,"total_page"=>$num_pages];
      $this->response(['data'=>$data,'status'=>true,'message'=>'Order get successfully.']);
  	}

    //api for screen d_02
    public function process_order_list_get()
    {
      $query = $this->db->query("SELECT o.order_no as Bill_no,o.created_at as order_date,delivery_date as due_date,o.user_id FROM `order` as o where o.status=3");
      $config = array();
      $config["base_url"] = base_url() . "api/admin/pending_order_list";
      $config["total_rows"] = count($query->result());
      $config["per_page"] = 20;
      $config['enable_query_strings'] = TRUE;
      $config['page_query_string'] = TRUE;
      $config['use_page_numbers'] = TRUE;
      $config['reuse_query_string'] = TRUE;
      $config['query_string_segment'] = 'page';
      $this->pagination->initialize($config);
      $offset = ($this->input->get('page')) ? ( ( $this->input->get('page') - 1 ) * $config["per_page"] ) : 0;
      $q= $this->db->query("SELECT o.order_no as Bill_no,o.created_at as order_date,delivery_date as due_date,o.user_id FROM `order` as o where o.status=3 LIMIT $offset,".$config['per_page']."");
      foreach($q->result() as $k=>$val)
      {
        $q1 = $this->db->query("SELECT s.name FROM `tbl_users` u LEFT JOIN shop s ON s.shop_id=u.shop_id where u.user_id='".$val->user_id."' limit 1");
        $q->result()[$k]->sails_id =  $q1->result()[0]->name;
      }
      $data['data'] = $q->result();
      $this->pagination->create_links();
      $num_pages = ceil($config["total_rows"] /$config["per_page"]);
      $next = ($num_pages>$this->pagination->cur_page) ? $this->pagination->cur_page + 1:0;
      $prev = ($num_pages<=$this->pagination->cur_page) ? $this->pagination->cur_page - 1:0;
      $data['pagination']=["current_page"=>$this->pagination->cur_page,"prev_page"=>$prev,"next_page"=>$next,"total_page"=>$num_pages];
      $this->response(['data'=>$data,'status'=>true,'message'=>'Order get successfully.']);
    }


    public function scan_qrcode_post()
    {
        $_POST = json_decode(file_get_contents("php://input"), true);
        //print_r($_POST);
        $this->form_validation->set_rules('qr_code', 'Qr Code', 'required');
        if($this->form_validation->run() == false)
        {
            $error= $this->form_validation->error_array();
            $this->response(['data'=>[],'status'=>false,'message'=>array_values($error)[0]]);
        }else{
            
            //   echo "sss";
            $qr_code = $this->post('qr_code');
            if(empty($qr_code)){
                $qr_code = $_POST['qr_code'];
            }
            //$order_data = $this->M_main->getdata('order',['qr_code'=>$qr_code,'status'=>1]);
            $query = $this->db->query("select id as order_id,customer_id,user_id,order_no as Bill_no,created_at as Order_Date,delivery_date as Delivery_Date, balance as Balance_Amount,advance,total,reason,qr_code,bill_photo FROM `order` WHERE qr_code='".$qr_code."' and status=3 limit 1");
            $data=[];
            //echo $this->db->last_query();
            foreach ($query->result() as $key => $value)
            {
               
                $data[$key] = $value;
                $r = $this->db->query("SELECT customer_name,phone as customer_phone,delivery_place,address from `tbl_customers` WHERE customer_id='".$value->customer_id."'");
                $query->result()[$key]->bill_photo =(!empty($value->bill_photo)) ? base_url().'uploads/orders/'.$value->bill_photo: '';
                $query->result()[$key]->customer_name = $r->result()[0]->customer_name;
                $query->result()[$key]->customer_phone = $r->result()[0]->customer_phone;
                $query->result()[$key]->delivery_place = $r->result()[0]->delivery_place;
                $query->result()[$key]->address = $r->result()[0]->address;
                $d = $this->db->query("SELECT audio_file FROM `order_audio` where order_audio.orderid='$value->order_id'");
                $audio_list = array();
                
                //$data[$key]->audio = $d->result_array();
                
                
                $d1 = $this->db->query('SELECT image_type,CONCAT("'.base_url().'uploads/orders/'.'", image_name) as image_name FROM `order_images` where order_images.order_id='.$value->order_id);
                $data[$key]->images = $d1->result_array();
                
                
                
                $p = $this->db->query("SELECT id,product.product_id,rate,qty,amount,name,type_name,file_name as audiofile,qrCode FROM `order_detail` join product on product.product_id=order_detail.product_id where order_detail.order_id='".$value->order_id."'");
                
                //echo $this->db->last_query();exit();
                foreach ($p->result() as $k => $val)
                {
                    //echo "<pre>";
                    //print_r($val);exit();
                    if(!empty($val->audiofile)){
                        $val->audiofile = base_url('/uploads/orders/'.$val->audiofile);
                    }
                    $product = $this->db->query("SELECT * from `order_add_on` where ord_product_id='".$val->id."'");
                    
                    $data[$key]->products[$k] = $val;
                    $data[$key]->products[$k]->addons = $product->result_array();
                    
                    if(!empty($data[$key]->products[$k]->addons)){
                       foreach ($data[$key]->products[$k]->addons as $l => $vall)
                        {
                            //echo "<pre>";
                            $addOnsList = $vall['addOnsList'];
                            $someArray = array();
                            if(!empty($addOnsList)){
                                $someArray = json_decode($addOnsList, true);
                            }
                            $data[$key]->products[$k]->addons[$l]['addOnsList'] = $someArray;
                            // print_r($vall);exit();
                        } 
                    }
                    
                    $product_image = $this->db->query('SELECT CONCAT("'.base_url().'uploads/orders/'.'", file_nname) as image_name from `order_detail_product_image` where order_detail_id='.$val->id);
                    // order_detail_product_image
                    $data[$key]->products[$k]->images = $product_image->result_array();
                    //echo "<pre>";
                    //print_r($data);exit();
                }
    
            }
    
            if($data)
            {
                $this->response(['data'=>$data,'status'=>true,'message'=>'Order Get Successfully.']);
            }else{
                $this->response(['data'=>[],'status'=>false,'message'=>'Order Not exits.']);
            }
            
        }
    }



    public function cancel_order_detail_post()
    {

        $_POST = json_decode(file_get_contents("php://input"), true);
        $this->form_validation->set_rules('order_no', 'Bill No.', 'required');
        $this->form_validation->set_rules('id', 'Bill No.', 'required');
        $this->form_validation->set_rules('product_id', 'Bill No.', 'required');
        if($this->form_validation->run() == false)
        {
            $error= $this->form_validation->error_array();
            $this->response(['data'=>[],'status'=>false,'message'=>array_values($error)[0]]);
        }else{

            $order_no = $this->post('order_no');
            $query = $this->db->query("SELECT order_no as Bill_no,created_at as order_date,delivery_date,reason,total as Total_amount,advance as Advance_amount,balance as Balance_amount,id as order_id,customer_id from `order` WHERE order_no='$order_no' and status=1");
            if(!empty($query->result()))
            {
                $customer_id = $query->result()[0]->customer_id;
                $order_id = $query->result()[0]->id;
                
                $data = array(
                    'isCancelled'=>1,
                    'cancelled_by'=>$this->user_data->id,
                    'cancelled_date'=>date('Y-m-d H:i:s'),
                );
                $this->db->where(array('order_id'=>$order_id,'product_id'=>$product_id));
                $this->db->update('order_detail',$data);
                // $q=$this->db->query("update delivery_place FROM `tbl_customers` where customer_id='$customer_id'");
                // $query->result()[0]->delivery_place = $q->result()[0]->delivery_place;
                $this->response(['data'=>$query->result(),'status'=>true,'message'=>'Order product deleted successfully.']);
            }else{
                $this->response(['data'=>[],'status'=>false,'message'=>'Order not exists.']);
            }
        }
    }

    public function accept_order_list_post()
    {
        
        $_POST = json_decode(file_get_contents("php://input"), true);
        $date = !empty($this->post('date')) ? $this->post('date') : date("Y-m-d");
        $config = array();
        $config["base_url"] = base_url() . "api/admin/accept_order_list";
        $config["total_rows"] =$this->M_main->count_data('order',['status' => 3,'DATE(created_at)' =>$date]);
        $config["per_page"] = 20;
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['use_page_numbers'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $this->pagination->initialize($config);
        $offset = ($this->input->get('page')) ? ( ( $this->input->get('page') - 1 ) * $config["per_page"] ) : 0;
        $data['data'] = $this->M_main->getdataPagination('order',['status' => 3,'DATE(created_at)' =>$date],$config["per_page"],$offset);
        $this->pagination->create_links();
        $num_pages = ceil($config["total_rows"] /$config["per_page"]);
        $next = ($num_pages>$this->pagination->cur_page) ? $this->pagination->cur_page + 1:0;
        $prev = ($num_pages<=$this->pagination->cur_page) ? $this->pagination->cur_page - 1:0;
        $data['pagination']=["current_page"=>$this->pagination->cur_page,"prev_page"=>$prev,"next_page"=>$next,"total_page"=>$num_pages];
        if($data['data']){
            $this->response(['data'=>$data,'path'=>base_url().'uploads/orders/','status'=>true,'message'=>'Order get successfully.']);
        }else{
            $this->response(['data'=>[],'status'=>false,'message'=>'Something goes wrong.']);
        }
    }

    public function accept_order_detail_post()
    {
        $this->form_validation->set_rules('order_no','Bill no.','trim|required');
        if($this->form_validation->run() == false)
        {
            $error= $this->form_validation->error_array();
            $this->response(['data'=>[],'status'=>false,'message'=>array_values($error)[0]]);

        }else{
            $order_no = $this->post('order_no');
            $order_data = $this->M_main->getdata('order',['order_no'=>$order_no]);
            if(!empty($order_data)) {
                $data = [];
                $order_details = $this->M_main->getdata('order_detail', ['order_id ' => $order_data[0]->id]);
                $order_images = $this->M_main->getdata('order_images', ['order_id ' => $order_data[0]->id]);
                $order_audio = $this->M_main->getdata('order_audio', ['orderid ' => $order_data[0]->id]);
                $process = $this->M_main->getdata('process', []);
                $path = base_url().'uploads/orders/';
                foreach($order_details as $key=>$val)
                {
                    $query = $this->db->query("SELECT name as product_name,type_name as product_type FROM `product` WHERE product_id='$val->product_id' limit 1");
                    $order_details[$key]->product_name = !empty($query->result()[0]->product_name) ? $query->result()[0]->product_name : '';
                    $order_details[$key]->product_type = !empty($query->result()[0]->product_type) ? $query->result()[0]->product_type : '';
                 

                }
                
                foreach($order_images as $k=>$order_image)
                {
                  $order_images[$k]->image_name =   $path.$order_image->image_name;
                 

                }
                
                foreach($order_audio as $i=>$o)
                {
                  $order_audio[$i]->audio_file =   $path.$o->audio_file;
                 

                }
                $data['order'] = $order_data;
                $data['order_images'] = $order_images;
                $data['order_audio'] = $order_audio;
                $data['process'] = $process;
                $data['product'] = $order_details;
               
                $this->response(['data'=>$data,'status'=>true,'message'=>'Order get successfully.']);
            }else{
                $this->response(['data'=>[],'status'=>false,'message'=>'Something goes wrong.']);
            }
        }
    }


    public function cancel_order_post()
    {
        $this->write_request_file();
	    $this->form_validation->set_rules('order_no','Bill no.','trim|required');
	    $this->form_validation->set_rules('product_id','product_id.','trim|required');
	    $this->form_validation->set_rules('id','id.','trim|required');
	    $this->form_validation->set_rules('reason','Reason','trim|required');
	    if($this->form_validation->run() == false)
        {
            $error= $this->form_validation->error_array();
            $this->response(['data'=>[],'status'=>false,'message'=>array_values($error)[0]]);

        }else{
		    $order_no = $this->post('order_no');
		    $order_data = $this->M_main->getdata('order',['user_id '=>$this->user_data->id,'order_no'=>$order_no,'status'=>3]);
		    if(!empty($order_data))
		    {
		        $order_id = $order_data[0]->id;
		        $product_id = $this->post('product_id');
		        $id = $this->post('id');
			    
			    $audio_file = '';
			    
                if (!empty($this->post('audio_file'))) {
                    $path = '/uploads/orders/';
                    $img_res = $this->savemp($this->post('audio_file'),$path);
                    //$img_res = $this->upload_file($path, $this->post('audio_file'));
                    if ($img_res) {
                        $audio_file = $img_res;
                    }

		            $this->M_main->save_data('order_audio',['orderid'=>$order_data[0]->id,'audio_file'=>$audio_file,'date'=>date("Y-m-d h:i:s")]);
		        }
		        
		        $data = array(
                    'action_name'=>'Cancelled',
                    'action_audio'=>$audio_file,
                    'action_notes'=>$this->post('reason'),
                    'action_by'=>$this->user_data->id,
                    'action_date'=>date('Y-m-d H:i:s'),
                );
                $this->db->where(array('order_id'=>$order_id,'product_id'=>$product_id,'id'=>$id));
                $this->db->update('order_detail',$data);
			    
			    
		        $this->response(['data'=>[],'status'=>true,'message'=>'Order cancelled successfully.']);
		    }else{
		        $this->response(['data'=>[],'status'=>false,'message'=>'Not A valid Order No.']);
            }
	    }
    }

    public function process_order_post()
    {
	    $input_data = json_decode(trim(file_get_contents('php://input')), true);
	    $order_no = $input_data['order_no'];
	    $id = $input_data['id'];
	    $process_id = @$input_data['process_id'];
	    $product_id = $input_data['product_id'];
	    $note = $input_data['note'];
	    
	    $this->write_request_file();
	    
	    $user_id = $this->user_data->id;
		 
        // print_r($order_no);die;
        // 'user_id '=>$this->user_data->id,
	    $order_data = $this->M_main->getdata('order',['order_no'=>$order_no,'status'=>3]);
	    //echo $this->db->last_query();exit();
	    if(!empty($order_data))
	    {
			
            $order_id = $order_data[0]->id;
            
            $audio_file = "";
            
		    if(!empty($input_data['audio_file'] ))
		    {
				$path = 'uploads/orders/';
				$audio_file =  $this->savemp($input_data['audio_file'],$path);
				//$audio_file = $this->upload_file($path, $input_data['audio_file'] );
		    }
		    
		    $data = array(
                'action_name'=>'Process',
                'action_audio'=>$audio_file,
                'action_notes'=>$note,
                'action_by'=>$this->user_data->id,
                'action_date'=>date('Y-m-d H:i:s'),
            );
            
            //exit();
            $this->db->where(array('order_id'=>$order_id,'product_id'=>$product_id,'id'=>$id));
            $this->db->update('order_detail',$data);
		    
		    if(empty($process_id)){
		        $process_id =0;
		    }
		    $data_arr = array(
		        'order_id' => $order_id,   
		        'user_id' => $user_id,   
		        'process_id' => $process_id,   
		        'assign_to' => 0,   
		        'remark' =>'Order Accepted',   
		        'datec' =>date('Y-m-d H:i:s'),   
		    );
			
            // assign_to
            
            $this->db->insert('order_tracking',$data_arr);
			
		    $this->response(['data'=>[],'status'=>true,'message'=>'Order product assigned proceed successfully.']);
	    }else{
		    $this->response(['data'=>[],'status'=>false,'message'=>'No pending order found with this bill number.']);
	    }
    }
      

    public function process_define_post()
    {
        $_POST = json_decode(file_get_contents("php://input"), true);
        $this->form_validation->set_rules('order_no', 'Order No.', 'required');
        if($this->form_validation->run() == false)
        {
            $error= $this->form_validation->error_array();
            $this->response(['data'=>[],'status'=>false,'message'=>array_values($error)[0]]);
        }else{

            $order_no = $this->post('order_no');
            $order_data = $this->M_main->getdata('order',['order_no'=>$order_no,'status'=>3]);
            if($order_data) {
                if (!empty($this->post('process'))) {
                    foreach ($this->post('process') as $key => $process) {
                        $d = ['order_id' => $order_data[0]->id, 'process_id' => $process['process_id'],'user_id'=>$this->user_data->id, 'created_at' => date("Y-m-d h:i:s")];
                        $this->M_main->save_data('process_define', $d);
                    }
                    $this->M_main->update_data(['status'=>4],'order',$order_data[0]->id);
                }
                $this->response(['data'=>[],'status'=>true,'message'=>'Process Define Successfully']);
            }else{
                $this->response(['data'=>[],'status'=>false,'message'=>'Something goes wrong.']);
            }
        }

    }


    public function define_process_detail_post()
    {
      $_POST = json_decode(file_get_contents("php://input"), true);
      $this->form_validation->set_rules('order_no', 'Order No.', 'required');
      if($this->form_validation->run() == false)
      {
          $error= $this->form_validation->error_array();
          $this->response(['data'=>[],'status'=>false,'message'=>array_values($error)[0]]);
      }else{

          $order_no = $this->post('order_no');
          $order_data = $this->M_main->getdata('order',['order_no'=>$order_no,'status'=>4]);

          if($order_data)
          {

              $data = [];
              $data['order'] = $order_data;
              $order_details = $this->M_main->getdata('order_detail', ['order_id ' => $order_data[0]->id]);
              $process_data = $this->M_main->getdata('process_define',['order_id'=>$order_data[0]->id]);
              foreach ($order_details as $order_detail) {

                  $product =$this->M_main->getdata('product', ['product_id ' => $order_detail->product_id]);
                  if(!empty($product)){
                    $data['product'][] = array_filter($product);
                  }
                      
              }

              foreach($process_data as $val)
              {
                  $process =$this->M_main->getdata('process', ['id ' => $val->process_id]);
                  if(!empty($process))
                      $data['process'][] = array_filter($process);
              }
              $user_type = 5;
              $this->db->select('user_id as id,full_name as karigar');
              $this->db->where('find_in_set("'.$user_type.'", user_type) <> 0');
              $this->db->from('tbl_users');
              $query = $this->db->get();
              $data['karigar']=$query->result();
              $this->response(['data'=>$data,'status'=>true,'message'=>'Order get successfully.']);
          }else{
              $this->response(['data'=>[],'status'=>false,'message'=>'Something goes wrong.']);
          }
      }
    }


    public function define_job_post()
    {
        $_POST = json_decode(file_get_contents("php://input"), true);
        // print_r($_POST);exit();
        $this->form_validation->set_rules('order_no', 'Order No.', 'required');
        $this->form_validation->set_rules('opp_id[]', 'opp_id.', 'required');
        $this->form_validation->set_rules('user_id[]', 'user_id.', 'required');
        if($this->form_validation->run() == false)
        {
            $error= $this->form_validation->error_array();
            $this->response(['data'=>[],'status'=>false,'message'=>array_values($error)[0]]);
        }else{
            $order_no = $_POST['order_no'];
            // print_r($_POST['user_id']);
            $order_data = $this->M_main->getdata('order',['order_no'=>$order_no]);
            // echo $this->db->last_query();
            if($order_data) {
                if(!empty($_POST['user_id'])){
                    foreach($_POST['user_id'] as $key=>$value){
                        
                        $data2 = array(
                            'assigned_to' => $value,
                            'work_sequence' => ($key+1),
                            'assign_date' => date('Y-m-d H:i:s'),
                            'status' => '0',
                        );
                        
                        $this->db->where(array('opp_id'=>$_POST['opp_id'][$key]));
                        $this->db->update('order_product_process',$data2);
                        // $_POST['user_id']
                    }
                }
                $this->response(['data'=>[],'status'=>true,'message'=>'Worker assigned Successfully']);
            }else{
                $this->response(['data'=>[],'status'=>false,'message'=>'Order Not found!.']);
            }
        }
    }

    public function on_process_post()
    {
        $_POST = json_decode(file_get_contents("php://input"), true);
        $date = !empty($this->post('date')) ? $this->post('date') : date("Y-m-d");
        $config = array();
        $config["base_url"] = base_url() . "api/admin/on_process";
        
        $config["per_page"] = 20;
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['use_page_numbers'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $this->pagination->initialize($config);
        $offset = ($this->input->get('page')) ? ( ( $this->input->get('page') - 1 ) * $config["per_page"] ) : 0;
        $q = $this->db->query("SELECT order_detail_id,order_no,`order`.qr_code,delivery_datec   FROM `order_product_process` JOIN `order_detail` on `order_detail`.id = order_product_process.order_detail_id JOIN `order` on `order`.id=order_detail.order_id   where  DATE(datec)='".$date."' group by order_detail_id");
     
        $config["total_rows"] = count($q->result());

        $data['data'] = $q->result_array();
        
        if(!empty($data['data'])){
            foreach($data['data'] as $key=>$value){
                $status = $this->M_main->getdata('order_product_process',['order_detail_id'=>$value['order_detail_id'],'status!='=>'3']);
                //echo $this->db->last_query();
                if(empty($status)){
                    $data['data'][$key]['status'] = '2';
                }else{
                    $data['data'][$key]['status'] = '1';
                }
            }
        }
          
        $this->pagination->create_links();
        $num_pages = ceil($config["total_rows"] /$config["per_page"]);
        $next = ($num_pages>$this->pagination->cur_page) ? $this->pagination->cur_page + 1:0;
        $prev = ($num_pages<=$this->pagination->cur_page) ? $this->pagination->cur_page - 1:0;
        $data['pagination']=["current_page"=>$this->pagination->cur_page,"prev_page"=>$prev,"next_page"=>$next,"total_page"=>$num_pages];
        if($data['data']){
          $this->response(['data'=>$data,'path'=>base_url().'uploads/orders/','status'=>true,'message'=>'Order get successfully.']);
        }else{
          $this->response(['data'=>[],'status'=>false,'message'=>'No order assigned.']);
        }
    
  	}



      	  //file upload
  	private function upload_file($target_dir,$encoded_string){
        //$target_dir = ''; // add the specific path to save the file
        $decoded_file = base64_decode($encoded_string); // decode the file
        $mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE); // extract mime type
        if(strpos($encoded_string,'data:image/') !== false){
            $extension = explode('/', mime_content_type($encoded_string))[1];
    
        } else{
            $extension = $this->mime2ext($mime_type);// extract extension from mime type
    
        }
    
        $file = uniqid() .'.'. $extension; // rename file as a unique name
        $file_dir = $target_dir .$file;
        try {
            file_put_contents($file_dir, $decoded_file); // save
            return $file;
            //header('Content-Type: application/json');
            //echo json_encode("File Uploaded Successfully");
        } catch (Exception $e) {
            //header('Content-Type: application/json');
            //echo json_encode($e->getMessage());
            return false;
        }
    
    }
    /*
    to take mime type as a parameter and return the equivalent extension
    */
    private function mime2ext($mime){
        $all_mimes = '{"png":["image\/png","image\/x-png"],"bmp":["image\/bmp","image\/x-bmp",
        "image\/x-bitmap","image\/x-xbitmap","image\/x-win-bitmap","image\/x-windows-bmp",
        "image\/ms-bmp","image\/x-ms-bmp","application\/bmp","application\/x-bmp",
        "application\/x-win-bitmap"],"gif":["image\/gif"],"jpeg":["image\/jpeg",
        "image\/pjpeg"],"xspf":["application\/xspf+xml"],"vlc":["application\/videolan"],
        "wmv":["video\/x-ms-wmv","video\/x-ms-asf"],"au":["audio\/x-au"],
        "ac3":["audio\/ac3"],"flac":["audio\/x-flac"],"ogg":["audio\/ogg",
        "video\/ogg","application\/ogg"],"kmz":["application\/vnd.google-earth.kmz"],
        "kml":["application\/vnd.google-earth.kml+xml"],"rtx":["text\/richtext"],
        "rtf":["text\/rtf"],"jar":["application\/java-archive","application\/x-java-application",
        "application\/x-jar"],"zip":["application\/x-zip","application\/zip",
        "application\/x-zip-compressed","application\/s-compressed","multipart\/x-zip"],
        "7zip":["application\/x-compressed"],"xml":["application\/xml","text\/xml"],
        "svg":["image\/svg+xml"],"3g2":["video\/3gpp2"],"3gp":["video\/3gp","video\/3gpp"],
        "mp4":["video\/mp4"],"m4a":["audio\/x-m4a"],"f4v":["video\/x-f4v"],"flv":["video\/x-flv"],
        "webm":["video\/webm"],"aac":["audio\/x-acc"],"m4u":["application\/vnd.mpegurl"],
        "pdf":["application\/pdf","application\/octet-stream"],
        "pptx":["application\/vnd.openxmlformats-officedocument.presentationml.presentation"],
        "ppt":["application\/powerpoint","application\/vnd.ms-powerpoint","application\/vnd.ms-office",
        "application\/msword"],"docx":["application\/vnd.openxmlformats-officedocument.wordprocessingml.document"],
        "xlsx":["application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application\/vnd.ms-excel"],
        "xl":["application\/excel"],"xls":["application\/msexcel","application\/x-msexcel","application\/x-ms-excel",
        "application\/x-excel","application\/x-dos_ms_excel","application\/xls","application\/x-xls"],
        "xsl":["text\/xsl"],"mpeg":["video\/mpeg"],"mov":["video\/quicktime"],"avi":["video\/x-msvideo",
        "video\/msvideo","video\/avi","application\/x-troff-msvideo"],"movie":["video\/x-sgi-movie"],
        "log":["text\/x-log"],"txt":["text\/plain"],"css":["text\/css"],"html":["text\/html"],
        "wav":["audio\/x-wav","audio\/wave","audio\/wav"],"xhtml":["application\/xhtml+xml"],
        "tar":["application\/x-tar"],"tgz":["application\/x-gzip-compressed"],"psd":["application\/x-photoshop",
        "image\/vnd.adobe.photoshop"],"exe":["application\/x-msdownload"],"js":["application\/x-javascript"],
        "mp3":["audio\/mpeg","audio\/mpg","audio\/mpeg3","audio\/mp3"],"rar":["application\/x-rar","application\/rar",
        "application\/x-rar-compressed"],"gzip":["application\/x-gzip"],"hqx":["application\/mac-binhex40",
        "application\/mac-binhex","application\/x-binhex40","application\/x-mac-binhex40"],
        "cpt":["application\/mac-compactpro"],"bin":["application\/macbinary","application\/mac-binary",
        "application\/x-binary","application\/x-macbinary"],"oda":["application\/oda"],
        "ai":["application\/postscript"],"smil":["application\/smil"],"mif":["application\/vnd.mif"],
        "wbxml":["application\/wbxml"],"wmlc":["application\/wmlc"],"dcr":["application\/x-director"],
        "dvi":["application\/x-dvi"],"gtar":["application\/x-gtar"],"php":["application\/x-httpd-php",
        "application\/php","application\/x-php","text\/php","text\/x-php","application\/x-httpd-php-source"],
        "swf":["application\/x-shockwave-flash"],"sit":["application\/x-stuffit"],"z":["application\/x-compress"],
        "mid":["audio\/midi"],"aif":["audio\/x-aiff","audio\/aiff"],"ram":["audio\/x-pn-realaudio"],
        "rpm":["audio\/x-pn-realaudio-plugin"],"ra":["audio\/x-realaudio"],"rv":["video\/vnd.rn-realvideo"],
        "jp2":["image\/jp2","video\/mj2","image\/jpx","image\/jpm"],"tiff":["image\/tiff"],scan_qrcode
        "eml":["message\/rfc822"],"pem":["application\/x-x509-user-cert","application\/x-pem-file"],
        "p10":["application\/x-pkcs10","application\/pkcs10"],"p12":["application\/x-pkcs12"],
        "p7a":["application\/x-pkcs7-signature"],"p7c":["application\/pkcs7-mime","application\/x-pkcs7-mime"],"p7r":["application\/x-pkcs7-certreqresp"],"p7s":["application\/pkcs7-signature"],"crt":["application\/x-x509-ca-cert","application\/pkix-cert"],"crl":["application\/pkix-crl","application\/pkcs-crl"],"pgp":["application\/pgp"],"gpg":["application\/gpg-keys"],"rsa":["application\/x-pkcs7"],"ics":["text\/calendar"],"zsh":["text\/x-scriptzsh"],"cdr":["application\/cdr","application\/coreldraw","application\/x-cdr","application\/x-coreldraw","image\/cdr","image\/x-cdr","zz-application\/zz-winassoc-cdr"],"wma":["audio\/x-ms-wma"],"vcf":["text\/x-vcard"],"srt":["text\/srt"],"vtt":["text\/vtt"],"ico":["image\/x-icon","image\/x-ico","image\/vnd.microsoft.icon"],"csv":["text\/x-comma-separated-values","text\/comma-separated-values","application\/vnd.msexcel"],"json":["application\/json","text\/json"]}';
        $all_mimes = json_decode($all_mimes,true);
        foreach ($all_mimes as $key => $value) {
            if(array_search($mime,$value) !== false) return $key;
        }
        return false;
    }
    
    public function process_list_get()
    {
        $input_data     = json_decode(trim(file_get_contents('php://input')), true);
        $user_id        = trim($input_data['user_id']);
        
        $query = $this->db->query("select * FROM `process` ");
        $data = $query->result_array();
        
        $this->response(['data'=>$data,'status'=>true,'message'=>'Process List.']);  
    }
    
    public function process_details_get()
    {
        $input_data     = json_decode(trim(file_get_contents('php://input')), true);
        //$user_id        = trim($input_data['user_id']);
        $user_id = $this->input->get('user_id');
        $id = trim($this->input->get('id'));
       // $id        = trim($input_data['id']);
        
        if(empty($id)){
            $query = $this->db->query("select * FROM `process_details`");
        }else{
            $query = $this->db->query("select * FROM `process_details` where process_id=".$id);
        }
        
        $data = $query->result_array();
        
        $this->response(['data'=>$data,'status'=>true,'message'=>'Process List.']);  
    }
    
    public function manual_process_details_get()
    {
        $input_data     = json_decode(trim(file_get_contents('php://input')), true);
        //$user_id        = trim($input_data['user_id']);
        $user_id = $this->input->get('user_id');
        
        $query = $this->db->query("select * FROM `process_details` where process_id=0");
    
        
        $data = $query->result_array();
        
        $this->response(['data'=>$data,'status'=>true,'message'=>'Manual Process List.']);  
    }
    
    public function karigar_list_get()
    {
        $input_data     = json_decode(trim(file_get_contents('php://input')), true);
        $user_id        = trim($input_data['user_id']);
        
        $query = $this->db->query("select * FROM `tbl_users` where (user_type ='5' OR user_type LIKE '%5,%' OR user_type LIKE '%,5%' )");
        $data = $query->result_array();
        
        $this->response(['data'=>$data,'status'=>true,'message'=>'user List.']);  
    }
    
    
    public function write_request_file(){
		
        $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");

        $postdata = file_get_contents("php://input");
        $posr_txt = json_encode($_POST);
        $get_txt = json_encode($_GET);
        $files_txt = json_encode($_FILES);

        $datec = date('Y-m-d H:i:s');

        $method = $this->router->fetch_method();
        
        $txt = "\n-----------------------------------Time :  ".$datec." : ".$method."-----------------------------------------------\n";
        $txt .= "\n-----------------------------------INPUT JSON-----------------------------------------------\n";
        $txt .= $postdata;
        $txt .= "\n-----------------------------------POST-----------------------------------------------\n";
        $txt .= $posr_txt;
        $txt .= "\n-----------------------------------GET-----------------------------------------------\n";
        $txt .= $get_txt;
        $txt .= "\n-----------------------------------FILES-----------------------------------------------\n";
        $txt .= $files_txt;

        // fwrite($myfile, $txt);
        fwrite($myfile, $txt);
        fclose($myfile);
    }
    
    public function savemp($base64_image_string,$path){
        $filename = $path.uniqid().".mp3";
    	file_put_contents( $filename, base64_decode($base64_image_string) );
    	return $filename;
    }
    
    public function set_order_product_process_post(){
        $input_data = json_decode(trim(file_get_contents('php://input')), true);
        $_POST = $input_data;
        //$this->write_request_file();exit();
        $this->form_validation->set_rules('order_no', 'Order No.', 'required');
        $this->form_validation->set_rules('order_detail_id', 'Order Product Id.', 'required');
        $this->form_validation->set_rules('product_id', 'product_id.', 'required');
        $this->form_validation->set_rules('process_id', 'process_id.', 'required');
        // $this->form_validation->set_rules('prc_detail_id', 'prc_detail_id.', 'required');
        
        if($this->form_validation->run() == false)
        {
            $error= $this->form_validation->error_array();
            $this->response(['data'=>[],'status'=>false,'message'=>array_values($error)[0]]);
    
        }else{
    	    $order_no = $input_data['order_no'];
    	    $order_detail_id = $input_data['order_detail_id'];
    	    $product_id = $input_data['product_id'];
    	    $process_id = $input_data['process_id'];
    	    $prc_detail_id = $input_data['prc_detail_id[]'];
    	    
    	   // echo "<pre>";
    	   // print_r($prc_detail_id);exit();
    	    if(!empty($prc_detail_id)){
    	        foreach($prc_detail_id as $value){
    	        
        	        $data = array(
            	        //'order_no'=>$order_no,
            	        'order_detail_id'=>$order_detail_id,
            	        'product_id'=>$product_id,
            	        'process_id'=>$process_id,
            	        'prc_detail_id'=>$value,
            	        'added_by'=>$this->user_data->id,
            	        'datec'=>date('Y-m-d H:i:s'),
            	    );
            	    
            	    $this->db->insert('order_product_process',$data);
            	    //echo $this->db->last_query();
        	        
        	    }
    	    }
    	    
    	    
    	    
    	    
    	    
    	    $this->response(['data'=>array(),'status'=>true,'message'=>'Order Product Process Assign Successfully.']);  
        }
	    
	    
    }
    
    public function get_order_product_process_get(){
        $input_data = json_decode(trim(file_get_contents('php://input')), true);
        $input_data = $_GET;
        $_GET['order_detail_id'] = trim($_GET['order_detail_id']);
        
        //print_r($_POST);exit();
       
        $this->form_validation->set_rules('order_detail_id', 'Order Product Id.', 'required');
        if(empty($_GET['order_detail_id']))
        {
            
            $this->response(['data'=>[],'status'=>false,'message'=>'Please Pass order_detail_id!']);
    
        }else{
    	    $order_detail_id = $input_data['order_detail_id'];
    	    $query1 = $this->db->query("select assigned_to,status,order_product_process.opp_id, work_sequence  ,order_product_process.process_id, order_product_process.prc_detail_id, process.name as process_name, process_details.process_detail_name,process_details_time FROM `order_product_process` JOIN process on process.id=order_product_process.process_id LEFT JOIN process_details on process_details.prc_detail_id=order_product_process.prc_detail_id  where order_detail_id=".$order_detail_id);
    	    
    	    $data['process'] = $query1->result_array();
    	    $data['user'] = array();
    	    if(!empty($data['process'])){
    	        $query = $this->db->query("select user_id,username,full_name FROM `tbl_users` where (all_process_id ='".$data['process'][0]['process_id']."' OR all_process_id LIKE '%".$data['process'][0]['process_id'].",%' OR all_process_id LIKE '%,".$data['process'][0]['process_id']."%' )");
                $data['user'] = $query->result_array();
                $message = 'Order Product Process Assign list.'; 
    	    }else{
    	        $message = 'Order Product Process Not Assign at.'; 
    	    }
    	    
    	    
    	    
    	    $this->response(['data'=>$data,'status'=>true,'message'=>$message]);  
        }
    }
    
    
    public function get_order_product_tracking_get(){
        $input_data = json_decode(trim(file_get_contents('php://input')), true);
        $input_data = $_GET;
        $_GET['order_detail_id'] = trim($_GET['order_detail_id']);
        
        //print_r($_POST);exit();
       
        $this->form_validation->set_rules('order_detail_id', 'Order Product Id.', 'required');
        if(empty($_GET['order_detail_id']))
        {
            
            $this->response(['data'=>[],'status'=>false,'message'=>'Please Pass order_detail_id!']);
    
        }else{
    	    $order_detail_id = $input_data['order_detail_id'];
    	    $query1 = $this->db->query("select assigned_to,status,order_product_process.opp_id, work_sequence  ,order_product_process.process_id, order_product_process.prc_detail_id, process.name as process_name, process_details.process_detail_name,process_details_time FROM `order_product_process` JOIN process on process.id=order_product_process.process_id LEFT JOIN process_details on process_details.prc_detail_id=order_product_process.prc_detail_id  where order_detail_id=".$order_detail_id);
    	    
    	    $data['process'] = $query1->result_array();
    	    $data['user'] = array();
    	    if(!empty($data['process'])){
    	        $query = $this->db->query("select user_id,username,full_name FROM `tbl_users` where (all_process_id ='".$data['process'][0]['process_id']."' OR all_process_id LIKE '%".$data['process'][0]['process_id'].",%' OR all_process_id LIKE '%,".$data['process'][0]['process_id']."%' )");
                $data['user'] = $query->result_array();
                $message = 'Order Product Process Assign list.'; 
    	    }else{
    	        $message = 'Order Product Process Not Assign at.'; 
    	    }
    	    
    	    
    	    
    	    $this->response(['data'=>$data,'status'=>true,'message'=>$message]);  
        }
    }
}
