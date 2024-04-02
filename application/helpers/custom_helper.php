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

function TopayDeduct($booking_id,$status){
  $ci =& get_instance();
  $ci->load->database();
    if($status==1){
      // BNF customer Booking debit
      $result1 = $ci->db->query("SELECT MAX(topup_balance_id) as id FROM franchise_topup_balance_tbl")->row();
				$id = $result1->id + 1;
				$payment_mode = 'Debit';
				$bank_name = 'Current';
				if (strlen($id) == 1) {
					$franchise_id = 'BFT100000' . $id;
				} elseif (strlen($id) == 2) {
					$franchise_id = 'BFT10000' . $id;
				} elseif (strlen($id) == 3) {
					$franchise_id = 'BFT1000' . $id;
				} elseif (strlen($id) == 4) {
					$franchise_id = 'BFT100' . $id;
				} elseif (strlen($id) == 5) {
					$franchise_id = 'BFT1000' . $id;
				}
        $booking = $ci->db->query("SELECT * FROM tbl_domestic_booking WHERE booking_id ='$booking_id'")->row();
        if ($booking->grand_total != '') {
          //$value = $_SESSION['customer_id'];
          $value = $booking->customer_id;
          $g_total = $booking->grand_total;
          $balance = $ci->db->query("Select * from tbl_franchise where fid = '$value'")->row();
          $cust = $ci->db->query("Select * from tbl_customers where customer_id = '$value'")->row();
          $amount = $balance->credit_limit_utilize;
          $update_val = $amount + $booking->grand_total;
          $whr5 = array('fid' => $booking->customer_id);
          $data1 = array('credit_limit_utilize' => $update_val);
          $result = $ci->db->update('tbl_franchise', $data1, $whr5);      
          $date = date('Y-m-d');  
          $franchise_id1 = $cust->cid;
            $data9 = array(
            'franchise_id' => $franchise_id1,
            'customer_id' => $booking->customer_id,
            'transaction_id' => $franchise_id,
            'payment_date' => $date,
            'debit_amount' => $g_total,
            'balance_amount' => $update_val,
            'payment_mode' => $payment_mode,
            'bank_name' => $bank_name,
            'status' => 1,
            'franchise_type' =>$cust->franchise_booking_type,
            'refrence_no' => $booking->pod_no
          );
          $result = $ci->db->insert('franchise_topup_balance_tbl', $data9);
        }
    }else{
       // Normal Booking 
          $result1 = $ci->db->query("SELECT MAX(topup_balance_id) as id FROM franchise_topup_balance_tbl")->row();
          $id = $result1->id + 1;
          $payment_mode = 'Debit';
          $bank_name = 'Current';
          if (strlen($id) == 1) {
            $franchise_id = 'BFT100000' . $id;
          } elseif (strlen($id) == 2) {
            $franchise_id = 'BFT10000' . $id;
          } elseif (strlen($id) == 3) {
            $franchise_id = 'BFT1000' . $id;
          } elseif (strlen($id) == 4) {
            $franchise_id = 'BFT100' . $id;
          } elseif (strlen($id) == 5) {
            $franchise_id = 'BFT1000' . $id;
          }
          $booking = $ci->db->query("SELECT * FROM tbl_domestic_booking WHERE booking_id ='$booking_id'")->row();
          if ($booking->grand_total != '') {
            $value = $booking->customer_id;
            $g_total = $booking->grand_total;
            $balance = $ci->db->query("Select * from tbl_franchise where fid = '$value'")->row();
            $cust = $ci->db->query("Select * from tbl_customers where customer_id = '$value'")->row();
            $amount = $cust->wallet;
            $update_val = $amount - $booking->grand_total;
            $whr5 = array('customer_id' => $booking->customer_id);
            $data1 = array('wallet' => $update_val);
            $result = $ci->db->update('tbl_customers', $data1, $whr5);      

            $date = date('Y-m-d');  
            $franchise_id1 = $cust->cid;
              $data9 = array(
              'franchise_id' => $franchise_id1,
              'customer_id' => $booking->customer_id,
              'transaction_id' => $franchise_id,
              'payment_date' => $date,
              'debit_amount' => $g_total,
              'balance_amount' => $update_val,
              'payment_mode' => $payment_mode,
              'bank_name' => $bank_name,
              'status' => 1,
              'franchise_type' =>$cust->franchise_booking_type,
              'refrence_no' => $booking->pod_no
            );
            $result = $ci->db->insert('franchise_topup_balance_tbl', $data9);
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

function EmptyVal($val){
    if(!empty($val)){
       return $val;
    }else{
       return 0;
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


