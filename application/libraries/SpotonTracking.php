<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class SpotonTracking {
    
    public $userName;
    public $password;
    public $postUrl;
    public $trackUrl;
    protected $CI;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->userName = '000109684';
        $this->password = 'ShriSai@2019';
        $this->postUrl = 'http://www.startreklogistics.com/wstapi/getcon';
        $this->trackUrl = 'http://www.startreklogistics.com/wstapi/GetTrackData';
    }
    
    public function postSpotonApiData($bookingId) {
    $resAct = $this->CI->db->query(
                    "select * from tbl_charges,tbl_booking,tbl_weight_details 
                    where tbl_charges.booking_id =tbl_booking.booking_id 
                    and tbl_weight_details.booking_id=tbl_charges.booking_id and booking_type = 1 and tbl_booking.booking_id = $bookingId order by booking_date DESC ");
    if ($resAct->num_rows() > 0) {
        $data = $resAct->result_array();
    } 

    foreach ($data as $data)
    {
        $i = 0;
        $dimensionData = [];
        $lengthData = json_decode($data['length_detail'],true);
        $breathData = json_decode($data['breath_detail'],true);
        $heightData = json_decode($data['height_detail'],true);
        
        foreach($lengthData as $length) {
            $dimensionData[$i] =   [
                'Length' => $lengthData[$i],
                'Breath' => $breathData[$i],
                'height' => $heightData[$i],
                'Pieces' => "1"
            ];  
            $i++;
        }
       
        $spotonApidata = [
            'UniqueHashValue' => md5($data['pod_no']),
            'CustomerCode' => $this->userName,
            'PickupPincode' => $data['sender_pincode'],
            'PickupDateTime' => $data['booking_date'],
            'ReceiverPincode' => (int) $data['reciever_pincode'],
            'PaymentMode' => 'credit',
            'PickupLocationName' => $data['sender_address'],
            'PickupAddress' => $data['sender_address'],
            'PickupCity'=> $this->getCityName($data['sender_city']),
            'TotalPackages' => $data['no_of_pack'],
            'TotalActualWeight' => $data['actual_weight'],
            'ReceiverName' => $data['reciever_name'],
            'ReceiverAddress' => $data['reciever_address'],
            'ReceiverCity' => $this->getCityName($data['reciever_city']),
            'ReceiverContactPerson' => $data['reciever_name'],
            'ReceiverContactPhone' => $data['reciever_contact'],
            'SpecialInstruction' => $data['special_instruction'],
            'PickupContactPhone' => $data['sender_contactno'],
            'PickupContactMail' => '',
            'Dimensions' => $dimensionData,
            'ReceiverMail' => '',
            'ReferenceNumber' => $data['forwording_no'],
            'AWBConsignmentValue' => '1000',
            'GstIn' => $data['sender_gstno'],
        ];    
    }
    
    $ch = curl_init($this->postUrl);   
    curl_setopt($ch, CURLOPT_USERPWD, $this->userName. ":" . $this->password);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($spotonApidata));   
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));                                                                                                                   
    $result = curl_exec($ch);
    $staus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $resultData = json_decode($result);
    $conNo = '';
    if($resultData->Status == 'Y') {
        $conNo = $resultData->ConNo;
    }
    $this->CI->db->insert('tbl_spoton_shipment',['booking_id' => $data['booking_id'], 'shipment_request' => json_encode($spotonApidata), 'shipment_response' => $result, 'status' =>$resultData->Status, 'conNo' => $conNo, 'podno' =>$data['pod_no']]);
    exit;
}

    public function getTrackingData($awnNo) {
        $data = [
            'ConNo' => $awnNo,
            'CustomerCode' => $this->userName,
        ];
        $ch = curl_init($this->trackUrl);   
        curl_setopt($ch, CURLOPT_USERPWD, $this->userName. ":" . $this->password);  
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));   
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
        $staus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result = curl_exec($ch);
        return $result;
    }
    
    public function getCityName($cityId)
    {
        $query = $this->CI->db->get_where('tbl_city', ['city_id' => $cityId]);
        $row = $query->row();
        if (!empty($row)) {
            return $row->city_name;            
        }

    }

}

?>
