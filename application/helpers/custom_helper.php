<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('booking_status'))
{
    function booking_status($podno)
    {
        //get main CodeIgniter object
       $ci =& get_instance();
       
       //load databse library
       $ci->load->database();
       
       //get data from database
       $query = $ci->db->from('tbl_international_tracking')->where('pod_no',$podno)->order_by('id', 'DESC')->get();
       
       if($query->num_rows() > 0){
           $result = $query->row();
           return $result;
       }else{
           return false;
       }
    }   
}
function franchise_id(){
  return $id = 'FBI';
}

function displaywords($number){
  $no = (int)floor($number);
  $point = (int)round(($number - $no) * 100);
  $hundred = null;
  $digits_1 = strlen($no);
  $i = 0;
  $str = array();
  $words = array('0' => '', '1' => 'one', '2' => 'two',
  '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
  '7' => 'seven', '8' => 'eight', '9' => 'nine',
  '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
  '13' => 'thirteen', '14' => 'fourteen',
  '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
  '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
  '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
  '60' => 'sixty', '70' => 'seventy',
  '80' => 'eighty', '90' => 'ninety');
  $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
  while ($i < $digits_1) {
   $divider = ($i == 2) ? 10 : 100;
   $number = floor($no % $divider);
   $no = floor($no / $divider);
   $i += ($divider == 10) ? 1 : 2;


   if ($number) {
    $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
    $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
    $str [] = ($number < 21) ? $words[$number] .
      " " . $digits[$counter] . $plural . " " . $hundred
      :
      $words[floor($number / 10) * 10]
      . " " . $words[$number % 10] . " "
      . $digits[$counter] . $plural . " " . $hundred;
   } else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);


  if ($point > 20) {
  $points = ($point) ?
    "" . $words[floor($point / 10) * 10] . " " . 
      $words[$point = $point % 10] : ''; 
  } else {
    $points = $words[$point];
  }
  if($points != ''){        
    echo ucfirst($result) . "rupees  " . $points . " paise only";
  } else {

    echo ucfirst($result) . "rupees only";
  }
}

function mis_formate_columns($type){
  
  if($type == '1')
  {
    $columsArr = array('SR.No', 'Date', 'Consigner', 'Consignee', 'Destination', 'Pincode', 'Invoice No', 'Invoice Value', 'Contact NO', 'DOC No', 'Shipment Type', 'Mode', 'Delivery Type', 'NOP', 'A.W.', 'C.W.', 'ODA', 'Delivery Date', 'TAT', 'EDD', 'Status', 'Status Description');
  }
  if($type == '2')
  {
    $columsArr = array('SR.No', 'Date', 'Consigner', 'Pincode', 'Pickup From', 'Consignee', 'Pincode','Contact No', 'Doc NO', 'Forwording NO', 'Forworder Name', 'Destination', 'No. of pcs', 'Invoice No', 'Invoice Value', 'Weight', 'Delivery Date', 'TAT', 'EDD', 'Status', 'Description');
  }
  if($type == '3')
  {
    $columsArr = array('SR.No', 'Date', 'Consigner', 'Pincode', 'Pickup From', 'Consignee', 'Pincode','Destination', 'Doc NO.', 'No. of Pcs', 'Invoice No', 'Invoice Value', 'Weight', 'EDD', 'Status', 'Description');
  }

  return $columsArr;
}


