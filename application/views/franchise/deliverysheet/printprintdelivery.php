
    <?php $this->load->view('franchise/franchise_shared/franchise_header'); ?>
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
.site-footer,#settings{
	display: none;
}
.main{
	margin-top: 0px;
}

<?php 
// echo "<pre>";
// // session_start();
// print_r($branchAddress);

// exit();
?>
</style>

    <body id="main-container" class="default">

        
        <!-- END: Main Menu-->

		<?php $this->load->view('franchise/franchise_shared/franchise_sidebar');
   // include('admin_shared/admin_sidebar.php'); ?>
        <!-- END: Main Menu-->
    
        <!-- START: Main Content-->
        <main >
        	<!-- <img src="https://picsum.photos/id/237/200/300"> -->
            <div class="container-fluid site-width">
                <!-- START: Listing-->
                <div class="row">                 
                  <div class="col-12  align-self-center">
                      <div class="col-12 col-sm-12 mt-3">
                      <div class="card">
                          <div class="card-header justify-content-between align-items-center">
                              <h4 class="card-title">Delivery Sheet</h4>  
 
                          </div>
                          <div class="card-body">
						  
					<div>
						
							<!-- <p><b><center><?php echo $company_setting->company_name; ?></center></b></p> -->
							<p><b><center><img src="http://35.154.99.18/assets/franchise_assets/dist/images/Final Logo.png" class="portfolioImage img-fluid"></center></b></p>
							<!-- <p><center>ADDRESS : <?php //echo $company_setting->address; ?></center></p> -->
							<p><center>ADDRESS : <?php echo $branchAddress[0]['address']; ?> <?php echo ($branchAddress[0]['city']!='') ? ", ".$branchAddress[0]['city'] : '' ; ?>, <?php echo $branchAddress[0]['pincode']; ?></center></p>
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
									<td style="border:2px solid;padding: 5px;"><?php echo $deliverysheet[0]['full_name']; ?></td>
									 <td style="border:2px solid;padding: 5px;">Total No. of Pcs </td>
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
				  <th>Orgin</th>
				  <th>Consigner Name</th>
                  <th>Consignee Name</th>
                  <!-- <th>Consignee Address</th> -->
				   <th>Payment</th>
                  <th>Signature</th>
             
                </tr>
                </thead>
                <tbody>
                <tr>
				
                  <?php
				  $cnt = 1;
				  foreach ($deliverysheet as  $row) {

				  	// echo "<pre>";
				  	// print_r($row);exit();
                  ?>  
				  <td><?php echo $cnt;?></td>
                  <td>
						<?php 
						
						$file = Zend_Barcode::draw('code128', 'image', array('text' => $row['pod_no']), array());
						imagepng($file,FCPATH."assets/barcode/".$row['pod_no'].".png"); ?>
						<img src="<?php echo base_url(); ?>assets/barcode/<?php echo  $row['pod_no']; ?>.png"  style="height: 75px;">
				  </td>
				 <td><?php echo $row['pod_no'];?></td>
				  <td><?php echo $row['sender_name'];?></td>
				  <td><?php 
				  echo $row['reciever_name'];
				  echo "<br>".$row['reciever_pincode'];
                  echo "<br>".@$row['city'];
				  ?></td>
                  <!-- <td><?php 
                  	// echo $row['reciever_address'];
                  	// echo "<br>".$row['reciever_pincode'];
                  	// echo "<br>".@$row['city'];
                  ?></td> -->
				   <td><?php 
							 echo $row['dispatch_details'];
							 echo '<br>';
							if($row['dispatch_details'] == 'COD')
							{
								echo $row['invoice_value'];
							}	
							elseif($row['dispatch_details'] == 'ToPay')
							{
								echo $row['grand_total'];
							}
							
							?></td>
				  <td style="width: 200px;"></td>
					 
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
	<?php $this->load->view('franchise/franchise_shared/franchise_footer');
    //include('admin_shared/admin_footer.php'); 
    ?>
    <!-- START: Footer-->
</body>
<!-- END: Body-->
</html>

