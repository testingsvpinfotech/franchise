<!DOCTYPE html>
<html>
<head>
<meta name="author" content="Harrison Weir"/>
<meta name="keywords" content="cats,feline"/>
<meta name="date" content="2021-05-05"/>
<style>

@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,700;1,400&display=swap');
body{ margin: 0px; padding: 0px; font-size: 12px; font-family: 'Poppins', sans-serif;  }

table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 5px;
  text-align: left;    
}

.borderless{ border: none; }

p {
    padding: 0px;
    margin: 0px;
    min-height: 18px;
    clear: left;
}

.padding-bott10 {
    padding-bottom: 10px;
}
.padding-bott20 {
    padding-bottom: 20px;
}

.border-topnone{ border-top: 0px!important}
table.border-bottomnone{ border-bottom: 0px!important}
.border-none{ border: 0px!important}
input {
    width: 90%;
    margin-bottom: 5px;
    background: #f4f4f4;
    border: 0px;
}

table {
    margin-bottom: 0px;
}
.td-nospace{padding:0px;}
td.td-nospace p {
    padding-left: 5px;
}
.main-page {
    margin: 10px auto;
    max-width: 1200px;
}

h1 {
    margin: 0px;
    font-size: 26px;
    padding: 0px;
}

span.two-cal {
    display: inline-block;
    width: 50%;
    float: left;
    min-height: 19px;
}
span.two-cal input {
    width: 80%;
}
table.extra-border, .extra-border th, .extra-border td {
    border-left: 0px !important;
    border-right: 0px;
    border-bottom: 0px;
}
.right-border1 {
    border-right: 1px solid #000;
    width: 46% !important;
    margin-right: 2%;
}

</style>
</head>
<body>
<?php
      foreach ($booking as $value) 
      {
        $weight_info    = $this->db->query("select * from tbl_international_weight_details where booking_id=".$value['booking_id']);
        $weightt_info     = $weight_info->row();        
                    
      ?>
<div class="main-page">
<table style="width:100%" class="border-bottomnone">
<tr>
  <td style="min-width: 100px; text-align:center;"><img src="<?php echo base_url();?>/assets/company/<?php echo $company_details->logo; ?>" width="100px"></td>
  <td>
    <h1><?php echo $company_details->company_name;?></h1>
    <p><?php echo $company_details->address;?></p>
    
    <p>PH : <?php echo $company_details->phone_no;?>&nbsp;&nbsp;&nbsp;EMAIL ID : <?php echo $company_details->email;?></p>
    <!--<p>EMAIL ID : <?php echo $company_details->email;?></p>-->
    <p>WEBSITE : <a target="_blank" href="<?php echo $company_details->website;?>"><?php echo $company_details->website;?></a>&nbsp;&nbsp;&nbsp;GST : <?php echo $company_details->gst_no;?></p>
  </td>
  <td class="td-nospace">
    <table style="width:100%; min-width: 250px " border="0" class="border-none" >
      <tr>
        <td class="border-none td-nospace" style="border-bottom: 2px solid black!important; text-align:center"><p>AWB NO</p></td>
      </tr>
      <tr>
        <td class="border-none td-nospace" style="border-bottom: 2px solid black!important; text-align:center"><b>
            <!--<img src="<?php echo base_url();?>/assets/barcode/barcode.png" >-->
            <?php 
            
            $file = Zend_Barcode::draw('code128', 'image', array('text' => $value['pod_no']), array());
            imagepng($file,FCPATH."assets/barcode/label/".$value['pod_no'].".png"); ?>
            <img src="<?php echo base_url(); ?>assets/barcode/label/<?php echo  $value['pod_no']; ?>.png" height="50%">
        
        
        
        </b><!--<p><?php //echo $value['pod_no'];?></p>--></td>
      </tr>
      <tr>
        <td class="border-none td-nospace" style="border-bottom: 2px solid black!important;"><p><span class="two-cal"><b>FWD NO :</b></span><span class="two-cal"><?php echo $value['forwording_no'];?></span> </p></td>
      </tr>
      <tr>
        <td class="border-none td-nospace"><p><span class="two-cal"><b>NETWORK :</b></span><span class="two-cal"><?php echo $value['forworder_name'];?></span></p></td>
      </tr>
    </table>
  </td>
</tr>
</table>


<table style="width:100%">
 <tr>
    <th>SHIPPER/CONSIGNOR : <?php echo $value['sender_name'];?></th>
    <th>RECIEVER/CONSIGNEE : <?php echo $value['reciever_name'];?></th>
    <th>Date : <?php echo date("d-m-Y",strtotime($value['booking_date']));?></th>
</tr>

<tr>
  <td>
    <p><?php echo $value['sender_address'];?></p>
    <!-- <p>ADDRESS 2</p> -->
    <p><span class="two-cal">CITY : <?php 
        $whr_c = array("id"=>$value['sender_city']);
        $city_details = $this->basic_operation_m->get_table_row("city",$whr_c);
    echo $city_details->city;?></span> <span class="two-cal">ZIP CODE : <?php echo $value['sender_pincode'];?></span></p>
    <p><span class="two-cal">COUNTRY : <?php echo "India";?></span> <span class="two-cal">TEL : <?php echo $value['sender_contactno'];?></span></p>
    <p>EMAIL : <?php if($value['dispatch_details']!="Cash"){$whr_cus = array("customer_id"=>$value['customer_id']);
        $cust_details = $this->basic_operation_m->get_table_row("tbl_customers",$whr_cus);
    echo $cust_details->email;} ?>
    </p>
  </td>
  <td>
    <p><?php echo $value['reciever_address'];?></p>
    <!-- <p>ADDRESS 2</p> -->
    <p><span class="two-cal">CITY : <?php echo $value['reciever_city'];?></span> <span class="two-cal">ZIP CODE : <?php echo $value['reciever_zipcode'];?></span></p>
    <p><span class="two-cal">COUNTRY : <?php 
        $whr_cn = array("z_id"=>$value['reciever_country_id']);
        $country_details = $this->basic_operation_m->get_table_row("zone_master",$whr_cn);
    echo $country_details->country_name;?></span> <span class="two-cal">TEL : <?php echo $value['reciever_contact'];?></span></p>
    <p>EMAIL : <?php echo $value['reciever_email'];?></p>
  </td>

  <td class="td-nospace">
    <p>CREDIT : <b><?php if($value['dispatch_details']=="Credit"){echo "YES";}?></b></p>
   
    <p  style="border-bottom: 2px solid black!important;">CASH : <b><?php if($value['dispatch_details']=="Cash"){echo "YES";}?></b></p>
    <p>DOC : <b><?php if($value['doc_type']=="0"){echo "YES";}?></b></p>
    
    <p>NON DOC : <b><?php if($value['doc_type']=="1"){echo "YES";}?></b></p>
    <!-- <p>FREIGHT</p> -->
  </td>
</tr>
</table>

<table style="width:100%">
 <tr>
    <th>DESCRIPTION OF GOODS</th>
    <th>VALUE OF GOODS</th>
    <th>NO OF PKGS</th>
    <th>TOTAL WEIGHT</th>
    <th>DIMENSIONS (IN CM)</th>
</tr>

<tr>
  <td><?php echo $value['special_instruction'];?></td>
  <td><b><?php if($value['doc_type']=="1" && $value['invoice_value']!="0.00"){echo $value['invoice_value'];}?></td>
  <td><?php echo $weightt_info->no_of_pack;?></td>
  <td><?php echo $weightt_info->actual_weight;?></td>
  <td><?php if($value['doc_type']=="1" && $weightt_info->length!="0.00"){
  
  
  $length_detail = json_decode($weightt_info->length_detail);
  $breath_detail = json_decode($weightt_info->breath_detail);
  $height_detail = json_decode($weightt_info->height_detail);
  //print_r($length_detail);
  for($de=0;$de<count($length_detail);$de++){
  echo '"'.$length_detail[$de]."*".$breath_detail[$de]."*".$height_detail[$de]."', ";
  }
  
  }
  ?></td>
</tr>

</table>

<table style="width:100%" >
 <tr>
    <th>SHIPPER'S AUTHORIZATION</th>
    <!--<th>SPECIAL INSTRUCTIONS</th>-->
    <th>CHARGES</th>
</tr>

<tr>
  <td class="td-nospace">
      <p style="font-size:9px;">I/we hereby authorize M/S. Om courier service, its associate/principals and its affiliates to act as our agent and do all acts necessary on our behalf including to file all documents declaration for customers for export on courier or freight mode.I/We agree to the terms of M/S. Om courier service as follows:
   <br> 1. We do not accept Cash,Gold,Silver,Dimonds.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    2. If your shipment is not insured and our liability is limited to maximum Rs.250/-only.<br>
    3. No complaints are accepted after 30 days from date of booking.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    4. Delivery time is not fix as depends on many factors.</p>
    <table style="width:100%" class="extra-border">
        <tr>
            <td class="td-nospace">
            <p><span class="two-cal padding-bott10 right-border1">Shipper's signature</span> <span class="two-cal padding-bott10">Received in good condition</span></p>
                <p>
                <span class="two-cal  right-border1"><b>Name : </b></span>
                <span class="two-cal"><b>Name : </b></span>
           </p>
           <p>
                <span class="two-cal right-border1"><b>Date : <?php echo date("d-m-Y",strtotime($value['booking_date']));?></b></span>
                <span class="two-cal"><b>Date : </b></span>
            </p>
            </td>
        </tr>
    </table>
    

  </td>
  <!--<td>    -->
  <!--  <p></p>-->
  <!--  <p></p>-->
  <!--  <p></p>-->
  <!--  <p></p>-->
  <!--  <p></p>-->
  <!--  <p><span class="two-cal">COUNTRY</span><span class="two-cal">TEL</span></p>-->
  <!--</td>-->
  <td style="vertical-align: top; min-width:170px ">
      <?php //if($value['dispatch_details']=='Cash'){ ?>
          <table class="borderless" style="min-width:170px">
              <tr>
                  <td> SUB TOTAL :<?php if($value['dispatch_details']=='Cash'){ echo $value['sub_total'];}else{echo "0";} ?></td>
              </tr>
              <tr>
                 
                  <td>CGST :<?php echo "0";?></td>
              </tr>
              <tr>
                  
                  <td>SGST : <?php echo "0";?></td>
              </tr>
              <tr>
                  
                  <td>IGST :<?php if($value['dispatch_details']=='Cash'){echo $value['igst'];}else{echo "0";} ?></td>
              </tr>
              <tr>
                  
                  <td> TOTAL AMT :<?php if($value['dispatch_details']=='Cash'){echo round($value['grand_total']);}else{echo "0";} ?></td>
              </tr>
          </table>
    <?php // } ?>
  </td>
</tr>
</table>
</div>
<?php } ?>
</body>
</html>
