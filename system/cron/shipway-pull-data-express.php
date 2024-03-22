

<?php 
error_reporting(E_ALL);
    $servername = "localhost";
        $username 	= "root";
	$password 	= 'swe6Eyogu@4';
	$dbname 	= "newspeed"; 
    
    $conn = new mysqli($servername, $username, $password, $dbname); 
    // Check connection
    if ($conn->connect_error) 
    {
        die("Connection failed: " . $conn->connect_error);
        echo $conn->connect_error;
    }
    else
    {
        //$headers = array('Content-Type:  application/json');
        $job_data    = "SELECT * from tbl_domestic_booking where forworder_name NOT IN ('SHREE MARUTI','Bluedart') and is_shipway_push = '1'  and forwording_no  is not null";
        $job_data_results = $conn->query($job_data);
        if($job_data_results->num_rows != 0)
        {
            while($job_info = $job_data_results->fetch_assoc())
            {
                $forworder_id  		= $job_info['forwording_no'];
                $sender_name		= $job_info['sender_name'];
                $pod_no      		= $job_info['pod_no'];
                $sender_contactno  	= $job_info['sender_contactno'];
                $forworder_name  	= $job_info['forworder_name'];
                $booking_id			= $job_info['booking_id'];
                $branch_id			= $job_info['branch_id'];

                $branch_data    	 = "SELECT * from tbl_branch where  branch_id =$branch_id";
                $branch_data_results = $conn->query($branch_data);
                $branch_info 		 = $branch_data_results->fetch_assoc();
                $branch_name		 = $branch_info['branch_name'];
echo $pod_no;
                $fields = array(
                    "username" => "Coshifter Logistics",
                    "password" => "c87a705346ad2fe9619d5d14d037ff56",
                    // "order_id" => "529492",
                    "order_id" => $forworder_id,
                );
                        
				$headers 	= array('Content-Type:  application/json');
				$ch 		= curl_init();
				curl_setopt( $ch,CURLOPT_URL, 'https://shipway.in/api/getOrderShipmentDetails' );
				curl_setopt( $ch,CURLOPT_POST, true );
				curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
				curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
				$result 	= curl_exec($ch );
				if(curl_exec($ch) === false)
				{
					echo 'Curl error: ' . curl_error($curl);
				}
				else
				{
				    echo 'Operation completed without any errors';
				}
				curl_close( $ch );

				if(!empty($result))
				{
				   $data			=	(json_decode($result));
echo '<pre>';
//print_R($data);
				   if ($data->status=='Success') 
				   {  
					   $response_data=$data->response;
					   
					   if(isset($response_data->current_status) AND $response_data->current_status == "Delivered ")
					   {		
							$update_delivered="UPDATE tbl_domestic_booking set is_delhivery_complete=1 where forwording_no='".$forworder_id."'";
							$conn->query($update_delivered);
							echo "Delivery Done";
					   }
					   else
					   {
							$scan = $response_data->scan;

							foreach ($scan as $key => $value) 
							{
								
								/* echo '<pre>';
							   print_R($scan);exit; */
								$timed = $value->time;
								$location = $value->location;
								$status_detail = $value->status_detail;


								$job_data2    = "select * from tbl_domestic_tracking where pod_no='$pod_no' and status = '$status_detail' AND branch_name='$location'";
								
								$job_data2_results = $conn->query($job_data2);
								
								if($job_data2_results->num_rows != 0)
								{
								
								}
								else
								{
									$update_delivered="INSERT INTO tbl_domestic_tracking (pod_no,status,branch_name,comment,booking_id,forworder_name, forwording_no,delivery_tracking_data,tracking_date,remarks) VALUES ('$pod_no','$status_detail','$location','$location','$booking_id','$forworder_name','$forworder_id', '$status_detail', '$timed','$status_detail')";
									
									$conn->query($update_delivered);

								}
							}
						   
							//     $update_delivered="UPDATE tbl_domestic_tracking set status=$response_data->current_status,tracking_date=$response_data->time,comment=response_data->location where forwording_no='".$forworder_id."'";
							//     $conn->query($update_delivered);
							// echo "updated";
					   }
					}
				}       
			}
		}
    }
?>