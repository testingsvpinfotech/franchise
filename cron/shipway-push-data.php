<?php 
error_reporting(E_ALL);
   

$servername = "localhost";
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
        $headers = array('Content-Type:  application/json');
        // $job_data    = "SELECT * from tbl_domestic_booking where 
        // forworder_name NOT IN ('SHREE MARUTI','Delhivery') and is_shipway_push = '0'";

        $job_data    = "SELECT * from tbl_domestic_booking where is_shipway_push = '0' and forwording_no  is not null";
        $job_data_results = $conn->query($job_data);
        if($job_data_results->num_rows != 0)
        {
            while($job_info = $job_data_results->fetch_assoc())
            {
                $sender_name= $job_info['sender_name'];
                $pod_no      = $job_info['pod_no'];
                $sender_contactno  = $job_info['sender_contactno'];
                $forworder_name  = $job_info['forworder_name'];
                $booking_id=$job_info['booking_id'];
                $forwording_no=$job_info['forwording_no'];
                $fields = array
                (
                    "username" => "Coshifter Logistics",
					"password" => "c87a705346ad2fe9619d5d14d037ff56",
					"carrier_id" => get_carrier_id_from_name($forworder_name),
					"awb" => $forwording_no,//pod_no
					"order_id" => $pod_no,//pod_no
					"first_name" => $sender_name,//sender_name
					"last_name" => '',
					"email" => "N/A",
					"phone" => $sender_contactno,//sender_contactno
					"products" => "N/A",
					"company" => "SVP Infotech",
					"shipment_type" => "1"
                );
                $ch = curl_init();
                curl_setopt( $ch,CURLOPT_URL, 'https://shipway.in/api/PushOrderData' );
                curl_setopt( $ch,CURLOPT_POST, true );
                curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
                $response = curl_exec($ch);
                echo "<pre>";
                print_r(json_decode($response));
                // die;
                if(curl_exec($ch) === false){
                    echo 'Curl error: ' . curl_error($curl);
                }
                else{
                    echo 'Operation completed without any errors';
                }
                curl_close($ch);
                if(!empty(json_decode($response))){
                    $queue_dataa = "update tbl_domestic_booking set is_shipway_push='1' where booking_id='$booking_id'";
                    $status = $conn->query($queue_dataa);
                    }
                }
            }
    }
    

    function get_carrier_id_from_name($carrier_name){

        echo $carrier_name;
        $carrier_id=0;
        $res=file_get_contents('https://shipway.in/api/carriers');
        $aa=json_decode($res);
        foreach ($aa->couriers as $val) {
        if( strtolower(trim($val->courier_name))==strtolower(trim($carrier_name))) {
                $carrier_id=$val->id;
                break;
            }
        }
        echo $carrier_id;
        return $carrier_id;
    }
?>