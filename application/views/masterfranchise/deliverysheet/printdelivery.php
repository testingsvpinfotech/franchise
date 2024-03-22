<?php $this->load->view('masterfranchise/master_franchise_shared/admin_header.php'); ?>
<!-- END Head-->
<style>
    .input:focus {
        outline: outline: aliceblue !important;
        border: 2px solid red !important;
        box-shadow: 2px #719ECE;
    }
</style>

<style>
table { border-collapse: collapse;font-family:arial;}
td {padding: 8px;}
body { margin: 0;padding: 0;background-color: #FAFAFA;font: 12pt "Tahoma"; }
* {box-sizing: border-box;-moz-box-sizing: border-box; }
.page { width: 21cm;min-height: 29.7cm;padding: 1cm;margin: 0.5cm auto;border: 1px #D3D3D3 solid; border-radius: 5px;
background: white; box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); }
.subpage { padding: 0cm;border: 0px black solid;height: 256mm; }

@page { size: A4; margin: 0; }
@media print {.page {margin: 0;border: initial;border-radius: initial;width: initial;min-height: initial;box-shadow: initial;
background: initial;page-break-after: always;}}
</style>


    <!-- START: Body-->
    <body id="main-container" class="default">

        
        <!-- END: Main Menu-->
		
        <!-- END: Main Menu-->
    
        <!-- START: Main Content-->
        <main>







            <div class="container-fluid site-width">
                <!-- START: Listing-->
                <div class="row">                 
                  <div class="col-12  align-self-center">
                      <div class="col-12 col-sm-12 mt-3">
                      <div class="card" id='DivIdToPrint'>
                          <div class="card-header justify-content-between align-items-center">
                              <h4 class="card-title">Delivery Sheet</h4>  
 <span style="float: right;"><a onclick='printDiv();' id='btn'  class="btn btn-info">Print</a></span>							  
                          </div>
                          <div class="card-body">
						  
					<div>
							<p><b><center><?php echo $company_setting->company_name; ?></center></b></p>
							<?php  
							//  echo $deliverysheet[0]['branch_id'];die;
							$branch_id = $deliverysheet[0]['branch_id'];
							$whr = array('branch_id' => $branch_id);
							$res = $this->basic_operation_m->getAll('tbl_branch', $whr);
							$address = $res->row()->address;
							?>
							<p><center>ADDRESS : <?php echo $address; ?></center></p>
							<!-- <p><center>ADDRESS : <?php echo $company_setting->address; ?></center></p> -->
						</div>
						<div>
							<table style="width:100%;border:2px solid;">
								<tr>
									<td style="border:2px solid;padding: 5px;">Delivery Order NO</td>
									<td style="border:2px solid;padding: 5px;"><?php echo $deliverysheet[0]['deliverysheet_id']; ?></td>
									 <td style="border:2px solid;padding: 5px;">Delivery Date</td>
									<td style="border:2px solid;padding: 5px;"><?php echo $deliverysheet[0]['delivery_date']; ?></td>
								</tr>
								<tr>
									<td style="border:2px solid;padding: 5px;">Delivery Boy Name</td>
									<td style="border:2px solid;padding: 5px;"><?php echo $deliverysheet[0]['deliveryboy_name']; ?></td>
									 <td style="border:2px solid;padding: 5px;">Total No. of AWB </td>
									<td style="border:2px solid;padding: 5px;"><?php echo count($deliverysheet); ?></td>
								</tr>
								<tr>
									<td style="border:2px solid;padding: 5px;">Total Deliverd</td>
									<td style="border:2px solid;padding: 5px;"></td>
									<td style="border:2px solid;padding: 5px;">Total Return</td>
									<td style="border:2px solid;padding: 5px;"></td>
								</tr>
							</table>
						</div>
						<br>
						
                              <div class="table-responsive">
                                  <?php 
				  if (!empty ($deliverysheet)){
					  ?>
				<table class="table table-bordered table-striped">
                <thead>
                <tr>
                   <th>ID</th>
                  <th>Barcode</th>
				  <th>AWB Number</th>
				  <th>Consigner Name</th>
                  <th>Consignee Name</th>
                  <th>Consignee Address</th>
                  <th>Payment</th>
                  <th>No Of Packet</th>
                  <th>Weight</th>
                  <th>Signature & Date-Time</th>
             
                </tr>
                </thead>
                <tbody>
                <tr>
				
                  <?php //print_r($deliverysheet);
				   $cnt = 1;
				  foreach ($deliverysheet as  $row) {
                  ?>  
				  <td><?php echo $cnt;?></td>
                  <td>
						<?php 
						
						$file = Zend_Barcode::draw('code128', 'image', array('text' => $row['pod_no']), array());
						imagepng($file,FCPATH."assets/barcode/".$row['pod_no'].".png"); ?>
						<img src="<?php echo base_url(); ?>assets/barcode/<?php echo  $row['pod_no']; ?>.png" height="50%">
				  </td>
				  <td><?php echo $row['pod_no'];?></td>
				  <?php 
								$whr_u =array('pod_no'=>$row['pod_no']);
								$booking = $this->basic_operation_m->get_table_row('tbl_domestic_booking', $whr_u);
								?>
				  <td><?php echo $booking->sender_name;?></td>
				  <td><?php echo $booking->reciever_name;?></td>
                  <td><?php echo $booking->reciever_address;?></td>
                  <td><?php 
							 echo $booking->dispatch_details;
							 echo '<br>';
							if($booking->dispatch_details == 'COD')
							{
								echo $booking->invoice_value;
							}	
							elseif($booking->dispatch_details == 'ToPay')
							{
								echo $booking->grand_total;
							}
							
							?></td> 
							<?php 
								$whr_u =array('booking_id'=>$booking->booking_id);
								$menifest = $this->basic_operation_m->get_table_row('tbl_domestic_weight_details', $whr_u);
						//echo $this->db->last_query();die;	//print_r($menifest);	?>
					<td><?php echo $menifest->no_of_pack;?></td>
					<td><?php echo $menifest->chargable_weight;?></td>
				  <td></td>
					 
                </tr>
					<?php 
					$cnt++;
						}
                   ?>
				
                </tbody>
               
              </table>
			  <?php
			    }else{
					echo "<p>No Data Found</p>";
				 }
				?>
                          </div>
                      </div>
                    </div> 

                </div>
                </div>
            </div>
            <!-- END: Listing-->
        </div>
    </main>
    <!-- END: Content-->
    <!-- START: Footer-->
	<?php $this->load->view('masterfranchise/master_franchise_shared/admin_footer.php');
     //include('admin_shared/admin_footer.php'); ?>
	<script>
		function printDiv() 
{

  var divToPrint=document.getElementById('DivIdToPrint');

  var newWin=window.open('','Print-Window');

  newWin.document.open();

  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

  newWin.document.close();

  setTimeout(function(){newWin.close();},10);

}
// 		$(document).ready(function(){
// 			// alert('hello');
// 			$('#printMe').click(function(){
// 				alert('hello');
// 				window.print();
// });
// 		});
	
	</script>
    <!-- START: Footer-->
</body>
<!-- END: Body-->

