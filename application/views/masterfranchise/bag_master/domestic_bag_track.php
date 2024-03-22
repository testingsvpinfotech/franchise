     <?php $this->load->view('admin/admin_shared/admin_header'); ?>
    <!-- END Head-->

    <!-- START: Body-->
    <body id="main-container" class="default">
<!-- <style>
  .card .card-body {
  font-weight: bold;
  font-size: 20px;  
}
</style> -->
       
        <!-- END: Main Menu-->
    <?php $this->load->view('admin/admin_shared/admin_sidebar');
   // include('admin_shared/admin_sidebar.php'); ?>
        <!-- END: Main Menu-->
    
        <!-- START: Main Content-->
		
<main>
<div class="container-fluid site-width" >
  <!-- START: Listing-->
  <div class="row">                 
    <div class="col-12  align-self-center">
        <div class="col-12 col-sm-12 mt-3">
        <div class="card">
            <div class="card-header justify-content-between align-items-center">
                <h4 class="card-title">Domestic Bag Tracking</h4>  
							<span style="float: right;">
							 <form method='post' action="admin/tracking-domestic-bag">
										<input type="text" name="bag_id" placeholder="Bag Number" style="height: 31px;"/>
										 <button type="submit" name="submit" class="btn btn-primary">Track </button>
										<input type="button" onclick="printDiv('printableArea')" class="btn btn-info" value="print" />
										<?php if(!empty($manifiest)){ ?>
										<a href="admin/download-domestic-tracking-bag/<?php echo $manifiest[0]['bag_id']; ?>"  class="btn btn-info">Download </a>	
										<?php } ?>
																				
							  </form>  
							</span>
        </div>
						  <div id="printableArea" >
                          <div class="card-body" style="font-size: 14px;"> 
                            <div class="row" >    
                                <div class="col-12"> 
                                      <center><strong style="font-size: 20px;" >Bag</strong></center>
                                 </div>
                            </div>
                            <div class="row">                                           
                              <div class="col-12">
                                 <div class="card border-0 border-top1px table-responsive" style="margin-bottom:0px;">
                                            <div class="card-body table-responsive" style="border:1px solid;">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td >
															<center>
                                                              <strong style="font-size: 35px;width: 150px;"> <img src="<?php echo base_url('assets/company/').'/'.$company_details->logo; ?>" class="portfolioImage img-fluid" > </strong><br><br>
                                                                    <p class="fontm">ADDRESS : <?php echo $branchAddress[0]['address']; ?> <?php echo ($branchAddress[0]['city']!='') ? ", ".$branchAddress[0]['city'] : '' ; ?>, <?php echo $branchAddress[0]['pincode']; ?></p> 
                                                                <p style="font-size: 19px;color: black;">  
                                                                <b>Telephone: </b><?php echo $branchAddress[0]['phoneno'];?><br>
                                                                <!-- <b>E-Mail:</b><?php //echo $company_details->email;?><br>                       
                                                                <b>Web Site:</b> www.omcourier.net<br> -->
                                                               </p>
															   </center>
                                                              </td>
                                                            <!--<td><img class="logo-css" src="<?php echo base_url();?>./assets/company/<?php echo $company_details->logo; ?>" alt="" style="height: 160px;width: 270px;margin-right: 91px;">
                                                            </td>-->
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                              </div>
                            </div>
                           <div class="row">                                           
                              <div class="col-12"> 
                              <div class="table-responsive">
                                  <table style="width:100%;border: 1px solid;">
                                       <thead>
                                          <tr>  
								   <th>DATE &nbsp;:&nbsp;<?php if(!empty($manifiest)){ echo date("d-m-Y",strtotime($manifiest[0]['date_added']));} ?><br>                       
									BAG NO &nbsp;:&nbsp; <?php if(!empty($manifiest)){ echo $manifiest[0]['bag_id'];} ?><br></th>                                               
				
				<th>TOTAL NO OF SHIPMENT &nbsp;:&nbsp;<?php if(!empty($manifiest)){echo $total_pcs;} ?><br>                       
								TOTAL WEIGHT &nbsp;:&nbsp;<?php if(!empty($manifiest)){echo $total_weightt;} ?><br>
								
							  
              </th>
              <th>Bag By &nbsp;:&nbsp; <?= $manifiest[0]['username']; ?><br>
			
							   
							  </th>
                                                                                               
                                          </tr>
                                      </thead>
                                  </table>
                            </div>
                        </div>
                      </div>             
							       <div class="row">                                           
                              <div class="col-12"> 
                              <div class="table-responsive">
                                  <table style="width:100%;border:1px solid;">
                                       <thead>
                                          <tr>  
										  
                                                <th style="border:1px solid;text-align: center!important;">SR.NO.</th>                                               
											    <th style="border:1px solid;text-align: center!important;">AWB No.</th>
												<th style="border:1px solid;text-align: center!important;">Shipper Name </th> 
											
												<th style="border:1px solid;text-align: center!important;">Consignee</th>
												<th style="border:1px solid;text-align: center!important;">Mode</th>
												<th style="border:1px solid;text-align: center!important;">Booking From</th>
												<th style="border:1px solid;text-align: center!important;">Destination</th>
												<th style="border:1px solid;text-align: center!important;">To Pay</th>
												<th style="border:1px solid;text-align: center!important;">Qty/Pcs</th>
												<th style="border:1px solid;text-align: center!important;">A Wt</th>
												<th style="border:1px solid;text-align: center!important;">C Wt</th>
                                             
                                          </tr>
                                      </thead>
                                      <tbody>  
                                          <?php
										                          if(!empty($manifiest)){ 
                                              $j=1;
                                                foreach ($manifiest as  $value) 
												{
													 $mode_details =$this->basic_operation_m->get_table_row("transfer_mode",array("transfer_mode_id"=>$value['mode_dispatch']));
													?> 
                                                  <tr>           
                                                    <td align="center" style="border:1px solid;"><?php echo $j; $j++; ?></td>
                                                    <td align="center" style="border:1px solid;"><?php echo $value['pod_no'];?></td>
                                                   <td align="center" style="border:1px solid;text-transform: uppercase;"><?php echo $value['sender_name'];?></td> 
                                                    <td align="center" style="border:1px solid;text-transform: uppercase;"><?php echo $value['reciever_name'];?></td>
													<td align="center" style="border:1px solid;text-transform: uppercase;"><?php echo $mode_details->mode_name;?></td>
													<td align="center" style="border:1px solid;text-transform: uppercase;"><?php 
                                                     $whr =array("id"=>$value['sender_city']);
                                                    $city_details =$this->basic_operation_m->get_table_row("city",$whr);
                                                    echo $city_details->city;                                                    
													
                                                    ?></td>
													<td align="center" style="border:1px solid;text-transform: uppercase;"><?php 
                                                     $whr =array("id"=>$value['reciever_city']);
                                                    $city_details =$this->basic_operation_m->get_table_row("city",$whr);
                                                    echo $city_details->city;                                                    
                                                    ?></td>
													 <td align="center" style="border:1px solid;"><?php 	if($value['dispatch_details'] == 'ToPay')
			{
				echo $value['grand_total'];
			}
			else
			{
				echo  "0";
			}
			$query_result= $this->db->query("select * from tbl_domestic_weight_details where booking_id = '".$value['booking_id']."'")->row();
			?></td>
													  <td align="center" style="border:1px solid;"><?php echo $value['total_pcs'];?></td> 
													   <td align="center" style="border:1px solid;"><?php echo $query_result->actual_weight;?></td>
													   <td align="center" style="border:1px solid;"><?php echo $query_result->chargable_weight;?></td>
													  											
                                                    </tr>
                                              <?php                                                 
                                                }
          										  }

                             ?>
                             <tr class="border-bottom">
                                 <td colspan="15" style="border:1px solid;" >
                                    <b>Remark:-</b> <?= $manifiest[0]['note']; ?>
									<br>
									<br>
									<br>
									<br>
(1)whose reliability we do not doubt.<br>
(2)These shipments have been protected from the time it  connected and accepted by at our acceptance location.<br>
(3)we further declae that these goods do not contain hazardous commodity as per current edtion of the IATA Manual.<br>
(4)All these shipments are subject to 100% phisical check at the time of acceptance.<br><br><br><br><br><br>


FOR <?php echo $company_details->company_name; ?>.<br>

                                </td>                             
                              </tr>
          										</tbody>
          										</table>
                          </div>
                        </div>
                      </div>

							</div><!-- end card body==================-->
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
    <?php $this->load->view('admin/admin_shared/admin_footer');
     //include('admin_shared/admin_footer.php'); ?>
     <script type="text/javascript">  
       function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
    </script>  
    <!-- START: Footer-->
</body>
<!-- END: Body-->

