<?php
//echo $prntext;

list($pod_no, $city_sender, $city_reciever, $mode_dispatch, $no_of_pack,$reciever_address) = explode('#|#',$prntext);

if(!empty($pod_no)) {
    $file = Zend_Barcode::draw('code128', 'image', array('text' => $pod_no), array());
    $store_image = imagepng($file,FCPATH."assets/barcode/label/".$pod_no.".png");
	
		$company_details = $this->basic_operation_m->get_table_row('tbl_company',array('id'=>1));
	$mode_details = $this->basic_operation_m->get_table_row('transfer_mode',array('transfer_mode_id'=>$mode_dispatch));
}
?><!DOCTYPE html>
<html lang="en" >

<head>

  <meta charset="UTF-8">

  <title>Print Label</title>

  <style>
	html {margin:0;padding:0;}
	body {font-family:Calibri;}
	@media print {
	.page-break { display: block; page-break-before: always; }
	.btn-print {display:none;}
	}
#invoice-POS {
  box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
  padding: 2mm;
  margin: 0 auto;
  width: 3in;
  background: #FFF;
}

.page-break  {
        clear: left;
        margin: 0;
        padding: 0;
        border: 0;
        outline: 0;
        display:block;
        page-break-after:always;
        }

 .label-field-head{
	font-size: 0.9em;
}
 .label-field-val{
	font-size: 0.6em;
}

.main-head{
font-size: 0.7em;letter-spacing: 2px;
}

 .sub-head1{
	font-size: 0.6em;
	width:2.5in;padding-left:0.4in
}

 .sub-head2{
	font-size: 0.6em;letter-spacing: 2px;
}

    </style>

  <script>
  window.console = window.console || function(t) {};
</script>



  <script>
  if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage("resize", "*");
  }
</script>


</head>

<body translate="no" class="text-center">
<div class="" style="width:3in; text-align:center !important; margin-top:0; padding-top:0;">
<center style="padding-bottom:20px"><input type="button" value="Print" onclick="window.print();return false;" class="btn-print" style="font-size: 20px; "></center>


<?php
    $no_of_pack =  $this->db->query("SELECT * FROM `tbl_domestic_booking` WHERE `pod_no` LIKE '$pod_no'")->row();
   $booking_id = $no_of_pack->booking_id;
    $pack =  $this->db->query("SELECT * FROM `tbl_domestic_weight_details` WHERE `booking_id` LIKE '$booking_id'")->row();
   $no = $pack->no_of_pack;
for($i=1;$i<=$no;$i++){
?>
<h4 style="margin-top: 0;" class="main-head "><center><strong><?php echo $company_details->company_name; ?></strong></center></h4>
<?php if(!empty($pod_no)) {?>
<div style="width:3in">
<div class="img">
<img src="<?php echo base_url()?>assets/barcode/label/<?php echo $pod_no ?>.png" style="width:70%; height:50px; text-center:center;" />
</div>
<h4 class="text-center" style="text-align:center; margin:0;">AWB No : <?php echo $pod_no;?></h4>
<h4 class="text-center" style="text-align:center; margin:0;">From : <?php echo $city_sender;?></h4>
<h4 class="text-center " style="text-align:center; margin:0;">Destination : <?php echo $city_reciever;?></h4>
<h4 class="text-center " style="text-align:center; margin:0;">Pkts : <?php echo $i .'/'.$no;?></h4>
</div>
<span class="page-break"></span>
<?php }}?>

</div>


<!--<center style="padding-top:20px"><input type="button" value="Print" onclick="window.print();return false;" class="btn-print" style="font-size: 20px; "></center>-->

</body>

</html>