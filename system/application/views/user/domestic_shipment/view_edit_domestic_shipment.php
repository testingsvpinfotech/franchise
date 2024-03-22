<?php include(dirname(__FILE__).'/../admin_shared/admin_header.php'); ?>
    <!-- END Head-->
 <style>
	.form-control{
	color:black!important;
	border: 1px solid var(--sidebarcolor)!important;
	height: 27px;
	font-size: 10px;
}
.select2-container--default .select2-selection--single {
background: lavender!important;
}
 form .error {
  color: #ff0000;
}
.select2-container *:focus {
    border: 1px solid #3c8dbc !important;
    border-radius: 8px 8px !important;
  	background:#ffff8f!important;
}
 input:focus {
  background-color: #ffff8f!important;
}
select:focus {
  background-color: #ffff8f!important;
}
textarea:focus {
  background-color: #ffff8f!important;
}
.btn:focus {
  color:red;    
  background-color: #ffff8f!important;
}
/*.frmSearch {border: 1px solid #A8D4B1;background-color: #C6F7D0;margin: 2px 0px;padding:40px;border-radius:4px;}
#city-list{float:left;list-style:none;margin-top:-3px;padding:0;width:190px;position: absolute;z-index: 7;}
#city-list li{padding: 10px; background: #F0F0F0; border-bottom: #BBB9B9 1px solid;}
#city-list li:hover{background:#ece3d2;cursor: pointer;}
#reciever_city{padding: 10px;border: #A8D4B1 1px solid;border-radius:4px;}*/

</style>  
    <!-- START: Body-->
    <body id="main-container" class="default">
  
        <!-- END: Main Menu-->
   
    <?php include(dirname(__FILE__).'/../admin_shared/admin_sidebar.php'); ?>
        <!-- END: Main Menu-->
    
        <!-- START: Main Content-->
        <main>
        	<div class="container-fluid site-width">
                           
                    <!-- START: Card Data-->
                <form role="form" name="generatePOD" id="generatePOD" action="admin/edit-domestic-shipment/<?php echo $booking_id;?>" method="post" >
                <div class="row">
                    <div class="col-md-7 col-sm-12 mt-3">
                        <div class="card">
                            <div class="card-header">                               
                                <h4 class="card-title">Shipment Info </h4>       
                                 <span style="float: right;"><a href="admin/view-domestic-shipment" class="btn btn-primary">View Domestic Shipment</a></span>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                	 <?php if($this->session->flashdata('notify') != '') {?>
  <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored"><?php echo $this->session->flashdata('notify'); ?></div>
  <?php  unset($_SESSION['class']); unset($_SESSION['notify']); } ?>  
                                    <div class="row">                                           
                                        <div class="col-12">                                           
                                                <div class="form-group row">
                                                	<label  class="col-sm-2 col-form-label">Date</label>
													<div class="col-sm-4">
													 <input type="date" name="booking_date" value="<?php echo $booking->booking_date;?>" id="booking_date" class="form-control">
													</div>
												</div>
												<div class="form-group row"> 
														<label class="col-sm-2 col-form-label">Courier</label>
														<div class="col-sm-4">
															<select class="form-control" required name="courier_company" id="courier_company" >
																<option value="">-Select Courier Company-</option>
																 <option value="0" data-id="<?php echo "All" ?>" >All</option>
				        										<?php	
																if(!empty($courier_company))	
																{
																	foreach ($courier_company as $cc)
																	{
																		?>
																		<option value='<?php echo $cc['c_id']; ?>' data-id="<?php echo $cc['c_company_name']; ?>" <?php if($booking->courier_company_id==$cc['c_id']){echo "selected";} ?>><?php echo $cc['c_company_name']; ?></option>
																		<?php 
																	}
																}
																?>
															</select>	
														</div>
														<label  class="col-sm-2 col-form-label">Airway No</label>
														<div class="col-sm-4">
															<input type="text" name="awn" id="awn" class="form-control my-colorpicker1" value="<?php echo $booking->pod_no; ?>">
														</div>
													</div>
												  <div class="form-group row"> 
													 <label  class="col-sm-2 col-form-label">Mode</label>
													<div class="col-sm-4">
														<select class="form-control mode_dispatch" name="mode_dispatch" id="mode_dispatch">
														<option value="">-Select Mode-</option>
		        										<?php	
														if(!empty($transfer_mode))		
														{
															foreach ($transfer_mode as $row)
															{
															?>	
																<option value='<?php echo $row->transfer_mode_id; ?>' <?php if($booking->mode_dispatch== $row->transfer_mode_id){echo "selected";} ?>><?php echo $row->mode_name; ?></option>
															<?php 	
															}
														}
															?>
														
													</select>	
													</div>	
													<!-- <label  class="col-sm-2 col-form-label">EDD</label>
													<div class="col-sm-4">
														<input type="date" id="delivery_date" name="delivery_date" value="<?php echo $booking->delivery_date;?>" id="eod" class="form-control">
												</div> -->
													<label  class="col-sm-2 col-form-label">ForwordNo</label>
													<div class="col-sm-4">
														<input type="text" name="forwording_no" class="form-control" value="<?php echo $booking->forwording_no;?>">
													</div>	
                                                </div>
												  <div class="form-group row"> 													 
													<label  class="col-sm-2 col-form-label">Forworder</label>
													<div class="col-sm-4">
													  <input type="text" name="forworder_name" class="form-control" value="<?php echo $booking->forworder_name;?>" id="forworder_name" >
													</div>
													<label  class="col-sm-2 col-form-label">Desc.</label>
													<div class="col-sm-4">
														<textarea name="special_instruction" class="form-control my-colorpicker1"><?php echo $booking->special_instruction;?></textarea>
													</div>
                                                </div>
                                                <div class="form-group row"> 
                                                	<label class="col-sm-2 col-form-label">Bill Type</label>
													<div class="col-sm-4">
														<select class="form-control" name="dispatch_details" id="dispatch_details">
																<option value="">-Select-</option>
																<option value="Credit" <?php echo ($booking->dispatch_details == 'CREDIT')?"selected":''; ?>>Credit</option>
																<option value="Cash" <?php echo ($booking->dispatch_details == 'CASH')?"selected":''; ?>>Cash</option>
																<option value="COD" <?php echo ($booking->dispatch_details == 'COD')?"selected":''; ?>>COD</option>
																<option value="ToPay" <?php echo ($booking->dispatch_details == 'TOPAY')?"selected":''; ?>>ToPay</option>
														</select>											
													</div>	
                                                	<label class="col-sm-2 col-form-label">Product</label>
													<div class="col-sm-4">
														<select class="form-control" name="doc_type" id="doc_typee">
															<option value="">-Select-</option>
															<option value="1" <?php if($booking->doc_type==1){echo "selected";} ?>>Non-Doc</option>
															<option value="0" <?php if($booking->doc_type==0){echo "selected";} ?>>Doc</option>
														</select>
													</div>  													
                                                </div> 
                                                <div class="form-group row" id="div_inv_row" style="display: none;" >
                                                	<label class="col-sm-2 col-form-label">INV No.</label>
													<div class="col-sm-2">
														<input type="text" name="invoice_no" id="invoice_no" class="form-control" value="<?php echo $booking->invoice_no;?>">
													</div>	
													<label class="col-sm-2 col-form-label">Inv. Value</label>
													<div class="col-sm-2">
														<input type="number" name="invoice_value" id="invoice_value" class="form-control" value="<?php echo $booking->invoice_value;?>">
													</div>
													<label class="col-sm-2 col-form-label">Eway No</label>
													<div class="col-sm-2">
														<input type="text" name="eway_no" id="eway_no" class="form-control" value="<?php echo $booking->eway_no;?>">
													</div>
												</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div class="card-header">                               
                                <h4 class="card-title">Consigner Detail</h4>                                
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">                                           
                                        <div class="col-12">                                           
                                                <div class="form-group row">
                                                    <label  class="col-sm-2 col-form-label">Customer</label>
													<div class="col-sm-4" id="credit_div">
													 <select class="form-control"  name="customer_account_id" id="customer_account_id">
														<option value="">Select Customer</option>
														<?php
															if (count($customers)) {
																foreach ($customers as $rows) {
																	?>
														<option value="<?php echo $rows['customer_id']; ?>" <?php if($booking->customer_id==$rows['customer_id']){echo "selected";} ?> >
															<?php echo $rows['customer_name']; ?>--<?php echo $rows['cid']; ?> 
														</option>
														<?php
															}
															} else {
															echo "<p>No Data Found</p>";
															}
															?>
													</select>
												</div>
												<label class="col-sm-2 col-form-label" id="credit_div_label">Name</label>
												<div class="col-sm-4">
													<input type="text" name="sender_name"  id="sender_name" class="form-control"  value="<?php echo $booking->sender_name; ?>">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-sm-2 col-form-label">Address</label>
												<div class="col-sm-4">
													<textarea name="sender_address"  id="sender_address" class="form-control"><?php echo $booking->sender_address; ?></textarea>		
												</div>
												<label class="col-sm-2 col-form-label">Pincode</label>
												<div class="col-sm-4">
													<input type="text" name="sender_pincode"  id="sender_pincode" class="form-control" value="<?php echo $booking->sender_pincode; ?>">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-sm-2 col-form-label">State</label>
												<div class="col-sm-4">
													<select class="form-control" id="sender_state" name="sender_state">
													<?php 
															if(count($states)) {
																foreach ($states as $st) {
																	?>
														<option value="<?php echo $st['id']; ?>" <?php if($booking->sender_state==$st['id']){echo "selected";} ?>>
															<?php echo $st['state']; ?> 
														</option>
														<?php }
															} 
															?>
													</select>
												</div>
												<label class="col-sm-2 col-form-label">City</label>
												<div class="col-sm-4">												
													<select class="form-control" id="sender_city" name="sender_city">
														<option value="">Select City</option>
														<?php
															if (count($cities)) {
																foreach ($cities as $rows) {
																	?>
														<option value="<?php echo $rows['id']; ?>" <?php if($booking->sender_city==$rows['id']){echo "selected";} ?>>
															<?php echo $rows['city']; ?> 
														</option>
														<?php }
															}
															?>
													</select>
												</div>												
											</div>
											<div class="form-group row">
													<label class="col-sm-2 col-form-label">ContactNo.</label>
													<div class="col-sm-4">
													<input type="text" name="sender_contactno"  id="sender_contactno" class="form-control" value="<?php echo $booking->sender_contactno; ?>">
													</div>
											
													
											</div>
											<div class="form-group row">
												<label class="col-sm-2 col-form-label">TypeOfDoc</label>
													<div class="col-sm-4">
														<select name="type_of_doc" class="form-control">
															<option value="GSTIN" <?php if($booking->type_of_doc=="GSTIN"){echo "selected"; } ?>>GSTIN</option>
															<option value="GSTIN(Govt.)" <?php if($booking->type_of_doc=="GSTIN(Govt.)"){echo "selected"; } ?>>GSTIN(Govt.)</option>
															<option value="GSTIN(Diplomats)" <?php if($booking->type_of_doc=="GSTIN(Diplomats)"){echo "selected"; } ?>>GSTIN(Diplomats)</option>
															<option value="PAN" <?php if($booking->type_of_doc=="PAN"){echo "selected"; } ?>>PAN</option>
															<option value="TAN" <?php if($booking->type_of_doc=="TAN"){echo "selected"; } ?>>TAN</option>
															<option value="Passport" <?php if($booking->type_of_doc=="Passport"){echo "selected"; } ?>>Passport</option>
															<option value="Aadhaar" <?php if($booking->type_of_doc=="Aadhaar"){echo "selected"; } ?>>Aadhaar</option>
															<option value="Voter Id" <?php if($booking->type_of_doc=="Voter Id"){echo "selected"; } ?>>Voter Id</option>
															<option value="IEC" <?php if($booking->type_of_doc=="IEC"){echo "selected"; } ?>>IEC</option>
														</select>
													</div>
													<div class="col-sm-4">
														<input type="text" name="sender_gstno"  id="sender_gstno" class="form-control" value="<?php echo $booking->sender_gstno;?>">
													</div>
											</div>

										</div>
									</div>	
								</div>
							</div>
							<div class="card-header" style="margin-top: -25px;">                               
                                <h6 class="card-title">Consignee Detail</h6>                                
                            </div>
                            
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">                                           
                                        <div class="col-12">                                           
                                                <div class="form-group row">
                                                   <label class="col-sm-2 col-form-label">Name</label>
													<div class="col-sm-4">
														<input type="text" name="reciever_name" id="reciever" class="form-control" value="<?php echo $booking->reciever_name;?>"  required>
													</div>
													<label class="col-sm-2 col-form-label">Company</label></label>
													<div class="col-sm-4">
														<input type="text" class="form-control" name="contactperson_name" id="contactperson_name" value="<?php echo $booking->contactperson_name;?>" required />
													</div>
											</div>
											<div class="form-group row">
												<label class="col-sm-2 col-form-label">Address</label>
												<div class="col-sm-4">
													<textarea name="reciever_address" id="reciever_address" class="form-control"><?php echo $booking->reciever_address;?></textarea>	
												</div>
												<!--  -->
												 <label class="col-sm-2 col-form-label">Pincode</label>
												 <div class="col-sm-4">
				                                    <input type="number" class="form-control" name="reciever_pincode" id="reciever_pincode" placeholder="Enter Pincode" value="<?php echo $booking->reciever_pincode;?>">
				                                </div>
											</div>
											<div class="form-group row">
											
			                                 <label class="col-sm-2 col-form-label">state</label>
												<div class="col-sm-4">
													 <select class="form-control" id="reciever_state" name="reciever_state">
														<option value="">Select State</option>
														<?php 
															if(count($states)) {
																foreach ($states as $st) {
																	?>
														<option value="<?php echo $st['id']; ?>" <?php if($booking->reciever_state==$st['id']){echo "selected";} ?>>
															<?php echo $st['state']; ?> 
														</option>
														<?php }
															} 
															?>
													</select>													
												</div>	
												<label class="col-sm-2 col-form-label">City</label>

												<div class="col-sm-4">
													 <select class="form-control" name="reciever_city" id="reciever_city">
														<option value="">Select City</option>
														<option value="">Select City</option>
														<?php
															if (count($cities)) {
																foreach ($cities as $rows) {
																	?>
														<option value="<?php echo $rows['id']; ?>" <?php if($booking->reciever_city==$rows['id']){echo "selected";} ?>>
															<?php echo $rows['city']; ?> 
														</option>
														<?php }
															}
															?>
													</select>													
												</div>			
                               				</div>
											<div class="form-group row">												
												<label class="col-sm-2 col-form-label">Zone</label>
												<div class="col-sm-4">
													<input type="text" name="receiver_zone" id="receiver_zone" class="form-control" value="<?php echo $booking->receiver_zone;?>" >
													<input type="hidden" name="receiver_zone_id" id="receiver_zone_id" value="<?php echo $booking->receiver_zone_id;?>" class="form-control">
													<input type="hidden"  id="gst_charges" class="form-control">
													<input type="hidden"  id="cft" class="form-control">
												</div>
												<label class="col-sm-2 col-form-label">ContactNo.</label>
												<div class="col-sm-4">
													<input type="text" class="form-control" name="reciever_contact" value="<?php echo $booking->reciever_contact;?>"/>
												</div>
											</div>
											<div class="form-group row">
												
												<label class="col-sm-2 col-form-label">GST NO.</label>
												<div class="col-sm-4">
													<input type="text" name="receiver_gstno" id="receiver_gstno" class="form-control" value="<?php echo $booking->receiver_gstno;?>">
												</div>
											</div>
										</div>
									</div>	
								</div>
							</div>

                        </div>
                    </div>
                    <div class="col-md-5 col-sm-12 mt-3">
                        <div class="card">
                            <div class="card-header">                               
                                <h4 class="card-title">Charges</h4>                                
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">                                           
                                        <div class="col-12">
                                               <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Freight</label>
													<div class="col-sm-3">
														<input type="number" name="frieht" class="form-control" id="frieht" value="<?php echo $booking->frieht; ?>" >
													</div>													
													<label class="col-sm-3 col-form-label">Transport</label>
													<div class="col-sm-3">
														<input type="number" name="transportation_charges" class="form-control" id="transportation_charges" value="<?php echo $booking->transportation_charges; ?>">
													</div>
													</div>
                                                    <div class="form-group row">
													<label class="col-sm-3 col-form-label">Pickup</label>
													<div class="col-sm-3">
														<input type="number" name="pickup_charges" class="form-control" id="pickup_charges" value="<?php echo $booking->pickup_charges; ?>">
													</div>                                                
                                                    <label   class="col-sm-3 col-form-label">RemoteArea</label>
													<div class="col-sm-3">
														<input type="number" name="delivery_charges" class="form-control" id="delivery_charges" value="<?php echo $booking->delivery_charges; ?>">
													</div>	
													</div>
                                                 <div class="form-group row">	
													<label class="col-sm-3 col-form-label">COD</label>
													<div class="col-sm-3">
														<input type="number" name="courier_charges" class="form-control" id="courier_charges" value="<?php echo $booking->courier_charges; ?>">
													</div>
                                                
                                               <!--  <div class="form-group row">
                                                    <label   class="col-sm-3 col-form-label">Destination</label>
													<div class="col-sm-3">
														<input type="number" name="destination_charges" class="form-control" id="destination_charges">
													</div>		
													<label class="col-sm-3 col-form-label">Clearance</label>
													<div class="col-sm-3">
														<input type="number" name="clearance_charges" class="form-control" id="clearance_charges">
													</div>
                                                </div> -->
                                                
                                                    <!-- <label class="col-sm-3 col-form-label">ECS</label>
													<div class="col-sm-3">
														<input type="number" name="ecs" class="form-control" id="ecs">
													</div> -->
													<label class="col-sm-3 col-form-label">AWB Ch.</label>
													<div class="col-sm-3">
													<input type="number" name="awb_charges" class="form-control" id="awb_charges" value="<?php echo $booking->awb_charges; ?>">
													</div>
												</div>
                                                 <div class="form-group row">
													<label class="col-sm-3 col-form-label">Other Ch.</label>
													<div class="col-sm-3">
														<input type="number" name="other_charges" class="form-control" id="other_charges" value="<?php echo $booking->other_charges; ?>">
													</div>
													<label  class="col-sm-3 col-form-label">Fov Charges</label>
													<div class="col-sm-3">
														<input type="number" readonly="readonly" class="form-control" name="fov_charges" id="fov_charges" value="<?php echo $booking->fov_charges; ?>">
													</div>
													
                                                  
												</div>                    
                                                <div class="form-group row">
												 <label   class="col-sm-3 col-form-label">Total</label>
													<div class="col-sm-3">
														<input type="number" readonly name="amount" class="form-control" id="amount" value="<?php echo $booking->total_amount; ?>"/>
													</div>
													<label  class="col-sm-3 col-form-label">Fuel Surcharge</label>
													<div class="col-sm-3">
														<input type="number" readonly="readonly" class="form-control" name="fuel_subcharges" id="fuel_charges" value="<?php echo $booking->fuel_subcharges; ?>">
													</div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div class="card-header">                               
                                <h4 class="card-title">Final Charge</h4>                                
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">                                           
                                        <div class="col-12">
                                             <div class="form-group row" id="payby" style="display:none;">
													<label class="col-sm-3 col-form-label">Pay By</label>
													<div class="col-sm-6">
														<select class="form-control" name="payment_method" id="payment_method">
																<option value="">-Select-</option>
																<?php foreach($payment_method as $pm){ ?>
																<option value="<?php echo $pm['id'];?>" <?php if($booking->payment_method==$pm['id']){echo "selected";} ?>><?php echo $pm['method'];?></option>
																<?php } ?>
														</select>											
													</div>													
                                                </div>
												<div class="form-group row" id="Refno" style="display:none;">
													<label class="col-sm-3 col-form-label">Ref No</label>
													<div class="col-sm-6">
														<input type="text" name="ref_no" class="form-control"  value="<?php echo $booking->ref_no; ?>" />											
													</div>													
                                                </div>
                                               <div class="form-group row">
                                                    <label  class="col-sm-3 col-form-label">Sub Total</label>
			                                        <div class="col-sm-6">
														<input type="number" readonly name="sub_total" class="form-control" id="sub_total"  value="<?php echo $booking->sub_total; ?>"/>
													</div>
												</div>
												<div class="form-group row">
													 <label class="col-sm-3 col-form-label">CGST Tax</label>
				                                     <div class="col-sm-6">
				                                       <div class="input-group mb-3">
				                                           <input class="form-control" type="number" id="cgst" name="cgst"  value="<?php echo $booking->cgst; ?>" readonly>
				                                        </div>
				                                     </div>
                                                </div>
                                                <div class="form-group row">
													 <label class="col-sm-3 col-form-label">SGST Tax</label>
				                                     <div class="col-sm-6">
				                                       <div class="input-group mb-3">
				                                            <input class="form-control" type="number" id="sgst" name="sgst"  value="<?php echo $booking->sgst; ?>" readonly>
				                                        </div>
				                                     </div>
                                                </div>
                                                  <div class="form-group row">
													 <label class="col-sm-3 col-form-label">IGST Tax</label>
				                                     <div class="col-sm-6">
				                                       <div class="input-group mb-3">
				                                            <input class="form-control" type="number" id="igst" step="any" name="igst" value="<?php echo $booking->igst; ?>" readonly>
				                                        </div>
				                                     </div>
                                                </div>
                                                <div class="form-group row">
                                                   <label class="col-sm-3 col-form-label">Grand Total</label>
													<div class="col-sm-6">
														<div class="input-group mb-3">
			                                        		<input type="text" readonly class="form-control" name="grand_total"  id="grand_total" value="<?php echo $booking->grand_total; ?>"/>
														</div>
													</div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-6">
        	                                        	<button type="submit"  class="btn btn-primary">Submit</button> &nbsp;
        	                                        	<button type="button" onclick="return open_new_page()" class="btn btn-primary">New</button>
    	                                        	</div>
    	                                        </div>
                                                       
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
              </div>
               <div class="row">
                    <div class="col-md-12 col-sm-12 mt-3">
                        <div class="card">
                            <div class="card-header">                               
                                <h4 class="card-title">Measurement Units</h4>                                
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">                                           
                                        <div class="col-12">   
											<div class="form-group row">
												<label class="col-sm-1 col-form-label">PKT</label>
												<div class="col-sm-2">
													<input type="text" name="no_of_pack" class="form-control my-colorpicker1 no_of_pack" value="<?php echo $weight->no_of_pack; ?>" data-attr="1" id="no_of_pack1" required="required">
												</div>
												<label class="col-sm-1 col-form-label">Actual Weight</label>
												<div class="col-sm-2">
													<input type="text" name="actual_weight" class="form-control my-colorpicker1 actual_weight" value="<?php echo $weight->actual_weight; ?>"  data-attr="1" id="actual_weight" required="required">
												</div>
												<label class="col-sm-1 col-form-label">Chargeable Weight</label>
												<div class="col-sm-2">
													<input type="text" name="chargable_weight" class="form-control my-colorpicker1 chargable_weight" value="<?php echo $weight->chargable_weight; ?>"  data-attr="1" id="chargable_weight" required="required">
												</div>
												<label class="col-sm-1 col-form-label">Is Volumetric</label>
												<div class="col-sm-2">
	
													<input type="checkbox" id="is_volumetric" name="fav_language" <?php echo (!empty($weight->length_detail))?'checked':''; ?> >

												</div>
											</div>	
											<div id="volumetric_table"> 											
												<table class="weight-table">
													<thead>
														<tr><input type="hidden" class="form-control" name="length_unit" id="length_unit" class="custom-control-input" value="cm">
															<th>Per Box Pack</th>
				                                            <th class="length_th">L</th>
				                                            <th class="breath_th">B</th>
				                                            <th class="height_th">H</th>
															<th class="volumetric_weight_th">Valumetric Weight</th>
														</tr>
													<thead>
													<tbody  id="volumetric_table_row">
														<?php 
														
															$length_detail =  json_decode($weight->length_detail);
															$breath_detail =  json_decode($weight->breath_detail);
															$height_detail =  json_decode($weight->height_detail);
															$valumetric_weight_detail =  json_decode($weight->valumetric_weight_detail);
															$per_box_weight_detail =  json_decode($weight->per_box_weight_detail);

															for($jd=0;$jd<count($valumetric_weight_detail);$jd++){ 
														?>
										<tr>
											<td><input type="text" name="per_box_weight_detail[]" class="form-control per_box_weight valid" data-attr="<?php echo ($jd+1); ?>" id="per_box_weight<?php echo ($jd+1); ?>" required="required" aria-invalid="false"  value="<?php echo $per_box_weight_detail[$jd];?>"></td>
											<td class="length_td"><input type="text" name="length_detail[]" class="form-control length" data-attr="<?php echo ($jd+1); ?>" id="length<?php echo ($jd+1); ?>" value="<?php echo $length_detail[$jd];?>" ></td>
											<td class="breath_td"><input type="text" name="breath_detail[]" class="form-control breath" data-attr="<?php echo ($jd+1); ?>" id="breath<?php echo ($jd+1); ?>" value="<?php echo $breath_detail[$jd];?>" ></td>
											<td class="height_td"><input type="text" name="height_detail[]" class="form-control height" data-attr="<?php echo ($jd+1); ?>" id="height<?php echo ($jd+1); ?>" value="<?php echo $height_detail[$jd];?>"></td>
											<td class="volumetic_weight_td"><input type="text" name="valumetric_weight_detail[]" readonly class="form-control valumetric_weight" data-attr="<?php echo ($jd+1); ?>" id="valumetric_weight<?php echo ($jd+1); ?>" value="<?php echo $valumetric_weight_detail[$jd];?>"></td>
										</tr>
													<?php } ?>
													</tbody>
													<tfoot>
													
													</tfoot>
												</table>
												 <table>
													<tr>
														<th><input type="text" name="per_box_weight" readonly="readonly" class="form-control  per_box_weight" id="per_box_weight" required="required" value="<?php echo $weight->per_box_weight; ?>"></th>
														<th class="length_td"><input type="text" name="length" readonly="readonly" class="form-control length"  id="length"  value="<?php echo $weight->length; ?>"></th>
														<th class="breath_td"><input type="text" name="breath" readonly="readonly" class="form-control breath"  id="breath" value="<?php echo $weight->breath; ?>"></th>
														<th  class="height_td"><input type="text" name="height" readonly="readonly" class="form-control height" id="height" value="<?php echo $weight->height; ?>" ></th>
														<th class="volumetic_weight_td"><input type="text" name="valumetric_weight" readonly="readonly" class="form-control valumetric_weight" id="valumetric_weight" value="<?php echo $weight->valumetric_weight; ?>"></th>

													</tr>
												</table>
											</div>
										</div>
                                    </div>
                                     
                                </div>
                             </div>
                         </div>
                   </div>
             </div>
            
         </div>
     </div>
 </form>
         
            </div>
        </main>
        <!-- END: Content-->
        <!-- START: Footer-->
        
        <?php  include(dirname(__FILE__).'/../admin_shared/admin_footer.php'); ?>
        <!-- START: Footer-->
    </body>
    <!-- END: Body-->
</html>
<script type="text/javascript">
	$(document).ready(function () {
	    	var dispatch_details ='<?php echo $booking->dispatch_details; ?>';
			//alert(dispatch_details);
			if(dispatch_details=="Credit")
			{
				$("#credit_div").show();
				$("#credit_div_label").show();
				$("#payby").hide();
				$("#Refno").hide();
				$("#sub_total").attr("readonly", true);
				$("#grand_total").attr("readonly", true);
			}else if(dispatch_details=="Cash")
			{
			    $("#credit_div").show();
				$("#credit_div_label").show();
				
			//	$("#credit_div").hide();
			//	$("#credit_div_label").hide();	
			
				$("#payby").show();
				$("#Refno").show();
				$("#sender_name").attr("readonly", false); 
				$("#sender_address").attr("readonly", false); 
				$("#sender_city").attr("readonly", false); 
				$("#sender_pincode").attr("readonly", false);
				$("#sender_contactno").attr("readonly", false);
				$("#sender_gstno").attr("readonly", false);
				$("#sub_total").attr("readonly", false);
				$("#grand_total").attr("readonly", false);
				
			}
			
			var customer_name = $('#customer_account_id').val();
		if (customer_name != null || customer_name != '') 
		{
			$.ajax({
				type: 'POST',
				dataType: "json",
				url: 'Admin_domestic_shipment_manager/getsenderdetails',
				data: 'customer_name=' + customer_name,
				success: function (data) {
					$("#gst_charges").val(data.user.gst_charges);				
				}
			});
	    
		$(function() {
		  $("form[name='generatePOD']").validate({  
		    rules: {    
		      booking_date: "required", 
		      forworder_name: "required",
		      courier_company:"required",
		      mode_dispatch:"required",
		      doc_type: "required", 
		      dispatch_details: "required",
		      sender_pincode:"required",
		      reciever_name:"required",
		      reciever_pincode: "required", 
		      sender_name:"required",
		      contactperson_name:"required",
		      frieht: "required",
		      transportation_charges:"required",
		      pickup_charges:"required",
			  delivery_charges:"required",
		      courier_charges:"required",
		      awb_charges:"required",
		      other_charges:"required",
		      amount:"required",
		      sub_total:"required",		     
		      grand_total:"required",
		      sender_gstno:"required",
		    },
		    // Specify validation error messages
		    messages: {
		    	 booking_date: "Required", 
			      forworder_name: "Required",
			      courier_company:"Required",
			      mode_dispatch:"Required",
			      doc_type: "Required", 
			      dispatch_details: "Required",
			      sender_pincode:"Required",
			      reciever_name:"Required",
			      reciever_pincode: "Required", 
			      sender_name:"Required",
		      	  contactperson_name:"Required",
			      sender_pincode: "Required", 
			      frieht: "Required",
			      transportation_charges:"Required",
			      pickup_charges:"Required",
				  delivery_charges:"Required",
			      courier_charges:"Required",
			      awb_charges:"Required",
			      other_charges:"Required",
			      amount:"Required",
			      sub_total:"Required",
			      igst:"Required",
			      grand_total:"Required",
			      sender_gstno:"Required",
		    },
		     errorPlacement: function(error, element) {
		            if (element.attr("type") == "radio") {
		                error.insertBefore(element);
		            } else {
		                error.insertAfter(element);
		            }
		        },		   
		    submitHandler: function(form) {
		      form.submit();
		    }
		  });
		});
		}
});
	var shipment =$("#doc_typee").val();
	if(shipment==1)
	{
		$('#div_inv_row').show();
		$(".length_td").show();
        $(".height_td").show();
        $(".breath_td").show();
        $(".volumetic_weight_td").show();
        $(".cft_th").show();                                                    
        $(".volumetric_weight_th").show();
        $(".length_th").show();
        $(".breath_th").show();
        $(".height_th").show();        
	}else{
		$('#div_inv_row').hide();
		$('#invoice_no').val("");
		$('#invoice_value').val("");
		$('#eway_no').val("");

		 $(".length_td").hide();
        $(".height_td").hide();
        $(".breath_td").hide();
        $(".volumetic_weight_td").hide();
        $(".cft_th").hide();                                                    
        $(".volumetric_weight_th").hide();
        $(".length_th").hide();
        $(".breath_th").hide();
        $(".height_th").hide();               
	}
// $("#reciever_city").keyup(function(){
// 			$.ajax({
// 			type: "POST",
// 			url:"Admin_domestic_shipment_manager/getcity",
// 			data: {'keyword':$(this).val(),'box':'1'},
// 			beforeSend: function(){
// 				$("#reciever_city").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
// 			},
// 			success: function(data){
// 				$("#suggesstion-box").show();
// 				$("#suggesstion-box").html(data);
// 				$("#reciever_city").css("background","#FFF");
// 			}
// 			});
// 		});
		
		//To select country name
	// function selectCountry(val) 
	// {
	// 	$("#reciever_city").val(val);
	// 	$("#suggesstion-box").hide();
	// }
/*			$.ajax({
			type: "POST",
			url: "Admin_airlines_manager/getairportcode",
			data: {'keyword':$(this).val(),'box':'1'},
			beforeSend: function(){
				$("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
			},
			success: function(data){
				$("#suggesstion-box").show();
				$("#suggesstion-box").html(data);
				$("#search-box").css("background","#FFF");
			}
			});
		});
*/

	function open_new_page(){
		location.reload();
	}
	$("#receiver_gstno").on('blur', function () 
	{
	  document.getElementById("no_of_pack1").focus();	
	});
	
	
	$("#valumetric_weight").on('blur', function () 
	{
	  document.getElementById("frieht").focus();	
	});
	
	$("#is_volumetric").on('blur', function () 
	{
		if ($('#is_volumetric').is(':checked')) 
		{
		}
		else
		{
			document.getElementById("frieht").focus();	
		}
	});
	
	$("#sender_pincode").on('blur', function () 
	{
		var pincode = $(this).val();
		if (pincode != null || pincode != '') {

		
			$.ajax({
				type: 'POST',
				url: 'Admin_domestic_shipment_manager/getCityList',
				data: 'pincode=' + pincode,
				dataType: "json",
				success: function (d) {					
					// var option;					
					// option += '<option value="' + d.id + '">' + d.city + '</option>';
					$('#sender_city').html(d);
					
				}
			});
			$.ajax({
				type: 'POST',
				url: 'Admin_domestic_shipment_manager/getState',
				data: 'pincode=' + pincode,
				dataType: "json",
				success: function (d) {					
					// var option;					
					// option += '<option value="' + d.id + '">' + d.state + '</option>';
					$('#sender_state').html(d);					
				}
			});
		}
	});	
	$("#reciever_pincode").on('blur', function () 
	{
		var pincode = $(this).val();
		if (pincode != null || pincode != '') {

		
			$.ajax({
				type: 'POST',
				url: 'Admin_domestic_shipment_manager/getCityList',
				data: 'pincode=' + pincode,
				dataType: "json",
				success: function (data) {					
					// var option;					
					// option += '<option value="' + d.id + '">' + d.city + '</option>';
					$('#reciever_city').html(data);
					
				}
			});
			$.ajax({
				type: 'POST',
				url: 'Admin_domestic_shipment_manager/getState',
				data: 'pincode=' + pincode,
				dataType: "json",
				success: function (data) {					
					// var option;					
					// option += '<option value="' + d.id + '">' + d.state + '</option>';
					$('#reciever_state').html(data);					
				}
			});
		}
	});	
	$("#dispatch_details").change(function (){
			var dispatch_details =$("#dispatch_details").val();
			//alert(dispatch_details);
			if(dispatch_details=="Credit")
			{
				$("#credit_div").show();
				$("#credit_div_label").show();	
				$("#payby").hide();
				$("#Refno").hide();
				$("#sub_total").attr("readonly", true);
				$("#grand_total").attr("readonly", true);
			}else if(dispatch_details=="Cash")
			{
			    $("#credit_div").show();
				$("#credit_div_label").show();
				
				//$("#credit_div").hide();
				//$("#credit_div_label").hide();
				
				$("#payby").show();
				$("#Refno").show();
				$("#sender_name").attr("readonly", false); 
				$("#sender_address").attr("readonly", false); 
				$("#sender_city").attr("readonly", false); 
				$("#sender_pincode").attr("readonly", false);
				$("#sender_contactno").attr("readonly", false);
				$("#sender_gstno").attr("readonly", false);
				$("#sub_total").attr("readonly", false);
				$("#grand_total").attr("readonly", false);
				
			}
	});

 weightChangeEvent();
//  // this is use for getting the customer information 
	$("#customer_account_id").change(function () 
	{
		var customer_name = $(this).val();
		if (customer_name != null || customer_name != '') {
			$.ajax({
				type: 'POST',
				dataType: "json",
				url: 'Admin_domestic_shipment_manager/getsenderdetails',
				data: 'customer_name=' + customer_name,
				success: function (data) {
					console.log(data);
					$("#sender_name").val(data.user.customer_name);
					$("#sender_address").val(data.user.address);
					$("#sender_pincode").val(data.user.pincode);
					$("#sender_contactno").val(data.user.phone);
					$("#sender_gstno").val(data.user.gstno);
					$("#gst_charges").val(data.user.gst_charges);
					// $("#sender_city").val(data.user.city);
					// $("#sender_state").val(data.user.state);					
					$("#customer_account_id").val(customer_name);	

					 var option;					
					 option += '<option value="' + data.user.city_id + '">' + data.user.city_name + '</option>';
					$('#sender_city').html(option);

					 var option1;					
					 option1 += '<option value="' + data.user.state_id + '">' + data.user.state_name + '</option>';
					$('#sender_state').html(option1);

					document.getElementById("reciever").focus();				
				}
			});
		}
	});
	
	/* $("#reciever").blur(function () 
	{
        var reciever = $(this).val();
		$('#contactperson_name').val(reciever);
        
    }); */

// 	// this is use for adding the new row for in Measurement Units
	$(".add-weight-row").on('click', function () 
	{
		var allTrs = $('table.weight-table tbody').find('tr');
		var lastTr = allTrs[allTrs.length - 1];
		var $clone = $(lastTr).clone();

		var countrows = $(".height").length;
		$clone.find('td').each(function () 
		{
			var el = $(this).find(':first-child');
			var id = el.attr('id') || null;
			if (id) 
			{
				var i = id.substr(id.length - 1);

				var nextElament = countrows; //parseInt(i)+1;
				var remove = 1;
				if (countrows > 10) 
				{
					var remove = 2;
				}
				var removeChar = (id.length - remove);
				var prefix = id.substr(0, removeChar);

				
				//console.log('prefix:::' + prefix + '::::' + id + '::::' + removeChar);
				el.attr('id', prefix + (nextElament));
				el.attr('data-attr', (nextElament));
			}
		});
		$clone.find('input:text').val('');
		$('table.weight-table tbody').append($clone);
		var totalRow = $('table.weight-table tbody').find('tr').length;
		
		if (totalRow > 1) 
		{
			$('.remove-weight-row').show();			
		} else {
			$('.remove-weight-row').hide();
		}
		weightChangeEvent();
	});
		// this fucntion is use for removing the row from table 
	$(".remove-weight-row").on('click', function () 
	{
		var totalRow = $('table.weight-table tbody').find('tr').length;
		if (totalRow > 1) {
			$('table.weight-table tbody').find('tr:last').remove();
			totalRow--;

		}
		if (totalRow == 1) {
			$(this).hide();
		}
		calculateTotalWeight();			
	});
	// this function is use for refresh the chrages when weight is changed in input box 
	function weightChangeEvent() 
	{
		$("#reciever_state, #reciever_city").blur(function () {
			var reciever_state =$("#reciever_state").val();
			var reciever_city =$("#reciever_city").val();
			$.ajax({
				type: 'POST',
				url: 'Admin_domestic_shipment_manager/getZone',			
				data:{reciever_state:reciever_state,reciever_city:reciever_city},
				dataType: "json",
				success: function (d) {	
					$("#receiver_zone_id").val(d.region_id);						
					$("#receiver_zone").val(d.region_name);			
				}
			});
			
		});

		$(".no_of_pack, .per_box_weight, .actual_weight, .length, .breath, .height").change(function () {
			
			var idNo = $(this).attr('data-attr');			
				calculateTotalWeight();
		});
		// $("#frieht,#transportation_charges,#destination_charges,#clearance_charges,#ecs,#other_charges").change(function () {
			
		// 	var frieht = parseFloat(($('#frieht').val() != '') ? $('#frieht').val() : 0);
		// 	var transportation_charges 	 = parseFloat(($('#transportation_charges').val() != '') ? $('#transportation_charges').val() : 0);
		// 	var destination_charges = parseFloat(($('#destination_charges').val() != '') ? $('#destination_charges').val() : 0);
		// 	var clearance_charges  = parseFloat(($('#clearance_charges').val() != '') ? $('#clearance_charges').val() : 0);
		// 	var ecs		 = parseFloat(($('#ecs').val() != '') ? $('#ecs').val() : 0);
		// 	var other_charges 	 = parseFloat(($('#other_charges').val() != '') ? $('#other_charges').val() : 0);

		// 	var totalAmount = (frieht + transportation_charges + destination_charges + clearance_charges +ecs + other_charges);
		// 	$('#amount').val(totalAmount);
		// });

		$("#frieht,#transportation_charges,#pickup_charges,#delivery_charges,#courier_charges,#awb_charges,#other_charges").change(function () {
			
			var frieht = parseFloat(($('#frieht').val() != '') ? $('#frieht').val() : 0);
			var transportation_charges = parseFloat(($('#transportation_charges').val() != '') ? $('#transportation_charges').val() : 0);
			var pickup_charges 	 = parseFloat(($('#pickup_charges').val() != '') ? $('#pickup_charges').val() : 0);
			var delivery_charges = parseFloat(($('#delivery_charges').val() != '') ? $('#delivery_charges').val() : 0);
			var courier_charges  = parseFloat(($('#courier_charges').val() != '') ? $('#courier_charges').val() : 0);
			var awb_charges		 = parseFloat(($('#awb_charges').val() != '') ? $('#awb_charges').val() : 0);
			var other_charges 	 = parseFloat(($('#other_charges').val() != '') ? $('#other_charges').val() : 0);
			var fov_charges 	 = parseFloat(($('#fov_charges').val() != '') ? $('#fov_charges').val() : 0);
			

			var totalAmount = (frieht + transportation_charges + pickup_charges + delivery_charges + courier_charges +awb_charges + other_charges + fov_charges);
			$('#amount').val(totalAmount);
			var courier_id = parseFloat(($('#courier_company').val() != '') ? $('#courier_company').val() : 0);
			var booking_date =$('#booking_date').val();
			var customer_id   = $('#customer_account_id').val();
			var dispatch_details =$('#dispatch_details').val();
			$.ajax({
					type: 'POST',
					url: 'Admin_domestic_shipment_manager/getFuelcharges',
					data: 'courier_id='+courier_id +'&sub_amount='+totalAmount+'&booking_date='+booking_date+'&customer_id='+customer_id+'&dispatch_details='+dispatch_details,
					dataType: "json",
					success: function(data) 
					{	
											
						$('#fuel_charges').val(data.final_fuel_charges);	
						$('#sub_total').val(data.sub_total);
						$('#igst').val(data.igst);
						$('#grand_total').val(data.grand_total);		
				
					}
				});	
			
		});
		$("#amount").blur(function () {		
			var customer_id   = $('#customer_account_id').val();
			var dispatch_details =$('#dispatch_details').val();
			var courier_id = parseFloat(($('#courier_company').val() != '') ? $('#courier_company').val() : 0);			
			var amount = parseFloat(($('#amount').val() != '') ? $('#amount').val() : 0);	
			var booking_date =$('#booking_date').val();
				$.ajax({
					type: 'POST',
					url: 'Admin_domestic_shipment_manager/getFuelcharges',
					data: 'courier_id='+courier_id +'&sub_amount='+amount+'&booking_date='+booking_date+'&customer_id='+customer_id+'&dispatch_details='+dispatch_details,
					dataType: "json",
					success: function(data) {					
						$('#fuel_charges').val(data.final_fuel_charges);	
										
				
					}
				});		
		});
		$("#amount,#fuel_charges").blur(function () {			
			var amount = parseFloat(($('#amount').val() != '') ? $('#amount').val() : 0);	
			var fuel_charges = parseFloat(($('#fuel_charges').val() != '') ? $('#fuel_charges').val() : 0);				
			var sub_total =(amount + fuel_charges);

			var receiver_gstno = $("#receiver_gstno").val();
			var first_two_char = receiver_gstno.substring(0, 2);
			
			if(first_two_char=="")
			{
			    first_two_char ='27';
			}
			
			var dispatch_details =$('#dispatch_details').val();
			var gst_charges =$('#gst_charges').val();
			
			if(gst_charges == 1)
			{
				if(first_two_char==27)
				{
					var cgst = (sub_total*9/100);
					var sgst = (sub_total*9/100);
					var igst = 0;
					var grand_total = sub_total + igst;
				}else{
					var cgst = 0;
					var sgst = 0;
					var igst = (sub_total*18/100);
					var grand_total = sub_total + igst;
				}
			}
			if(dispatch_details == 'Cash')
			{
				var cgst = 0;
				var sgst = 0;
				var igst = 0;
				var grand_total = sub_total + igst;
			}
			
			
			$('#sub_total').val(sub_total.toFixed(2));
			$('#cgst').val(cgst.toFixed(2));
			$('#sgst').val(sgst.toFixed(2));
			$('#igst').val(igst.toFixed(2));
			$('#grand_total').val(grand_total.toFixed(2));
		});
		$("#sub_total").blur(function () {
			var sub_total = parseFloat(($('#sub_total').val() != '') ? $('#sub_total').val() : 0);		
			var receiver_gstno = $("#receiver_gstno").val();
			var first_two_char = receiver_gstno.substring(0, 2);
            
            if(first_two_char=="")
			{
			    first_two_char ='27';
			}
			
			var dispatch_details =$('#dispatch_details').val();
			var gst_charges =$('#gst_charges').val();
			
			if(gst_charges == 1)
			{
			
				if(first_two_char==27)
				{
					var cgst = (sub_total*9/100);
					var sgst = (sub_total*9/100);
					var igst = 0;
					var grand_total = sub_total + cgst + sgst + igst;
				}else{
					var cgst = 0;
					var sgst = 0;
					var igst = (sub_total*18/100);
					var grand_total = sub_total + igst;
				}
			}
			
			if(dispatch_details == 'Cash')
			{
				var cgst = 0;
				var sgst = 0;
				var igst = 0;
				var grand_total = sub_total + igst;
			}
			
			
			$('#sub_total').val(sub_total.toFixed(2));
			$('#cgst').val(cgst.toFixed(2));
			$('#sgst').val(sgst.toFixed(2));
			$('#igst').val(igst.toFixed(2));
			$('#grand_total').val(grand_total.toFixed(2));
		});

	}
	// ========
	$("#doc_typee").change(function (){
			var shipment =$("#doc_typee").val();
			if(shipment==1)
			{
				$('#div_inv_row').show();
				$(".length_td").show();
                $(".height_td").show();
                $(".breath_td").show();
                $(".volumetic_weight_td").show();
                $(".cft_th").show();                                                    
                $(".volumetric_weight_th").show();
                $(".length_th").show();
                $(".breath_th").show();
                $(".height_th").show();
			}else{
				$('#div_inv_row').hide();
				$('#invoice_no').val("");
				$('#invoice_value').val("");
				$('#eway_no').val("");

				$(".length_td").hide();
                $(".height_td").hide();
                $(".breath_td").hide();
                $(".volumetic_weight_td").hide();
                $(".cft_th").hide();                                                    
                $(".volumetric_weight_th").hide();
                $(".length_th").hide();
                $(".breath_th").hide();
                $(".height_th").hide();    
			}
	});
	
	$("#courier_company").change(function () {
			var courier_company_name = $("#courier_company option:selected").attr('data-id');
			$('#forworder_name').val(courier_company_name);
	});
	
// 	// this functin is use for calculating total weight 
	function calculateTotalWeight() 
	{
		
		var totalRow = $('table.weight-table tbody').find('tr').length;	
		var totalActualWeight = 0;
		var totalValumetricWeight = 0;
		var totalLength = 0;
		var totalBreath = 0;
		var totalHeight = 0;
		var totalOneCftKg = 0;
		var totalNoOfPack = 0;
		var totalPerBoxWeight = 0;
	
		var mode_dispatch = $('#mode_dispatch').val();
		var currentActualWeight = $('#actual_weight').val();
		
	
		for (var i = 1; i <= totalRow; i++) 
		{
			
			if (currentActualWeight > 0) 
			{
				$("#chargable_weight").val(currentActualWeight);
			}

			var perBoxWeightCurrent = $('#per_box_weight' + i).val();
			var length = $("#length" + i).val();
			var breath = $("#breath" + i).val();
			var height = $("#height" + i).val();
			if (length != '' && breath != '' && height != '' ) 
			{		
		
				if(mode_dispatch == 3)
				{
					valumetric_weight = (((length * breath * height) / 27000)  * 8) * perBoxWeightCurrent  ;	
				}
				else
				{
					valumetric_weight = ((length * breath * height) / 5000) * perBoxWeightCurrent ;	
				}
				total_valumetric_weight = valumetric_weight.toFixed(2);
				console.log(length + breath + height + perBoxWeightCurrent +'/'+total_valumetric_weight);
				$("#valumetric_weight" + i).val(total_valumetric_weight);
					
			}
			else 
			{
				$("#valumetric_weight" + i).val('');
			}

			
			totalValumetricWeight 	= parseFloat(totalValumetricWeight) + parseFloat(($('#valumetric_weight' + i).val() != '') ? $('#valumetric_weight' + i).val() : 0);
			totalPerBoxWeight 		= parseFloat(totalPerBoxWeight) + parseFloat(($('#per_box_weight' + i).val() != '') ? $('#per_box_weight' + i).val() : 0);
			totalLength 			= parseFloat(totalLength) + parseFloat(($('#length' + i).val() != '') ? $('#length' + i).val() : 0);
			totalBreath 			= parseFloat(totalBreath) + parseFloat(($('#breath' + i).val() != '') ? $('#breath' + i).val() : 0);
			totalHeight 			= parseFloat(totalHeight) + parseFloat(($('#height' + i).val() != '') ? $('#height' + i).val() : 0);
		}
		
		var totalActualWeight = $('#actual_weight').val();
		
		if (totalValumetricWeight) 
		{
			var roundoff_type = $("#roundoff_type").val();
			// $('#valumetric_weight').val(totalValumetricWeight); ttttttt
			if (roundoff_type == '1') 
			{
				$('#valumetric_weight').val(totalValumetricWeight);
			}
			else 
			{
				$('#valumetric_weight').val(totalValumetricWeight);
			}
		}
		
	
		if(totalValumetricWeight > totalActualWeight)
		{
			$("#chargable_weight").val(totalValumetricWeight);
		}
		else
		{
			$("#chargable_weight").val(totalActualWeight);
		}
		
		if (totalNoOfPack) {
			$('#no_of_pack').val(totalNoOfPack);
		}
		if (totalPerBoxWeight) {
			$('#per_box_weight').val(totalPerBoxWeight);
		}
		if (totalActualWeight) {
			$('#actual_weight').val(totalActualWeight);
		}
		if (totalValumetricWeight) 
		{
			var roundoff_type = $("#roundoff_type").val();
			if (roundoff_type == '1') 
			{
				$('#valumetric_weight').val(totalValumetricWeight.toFixed(2));
			}
			else 
			{
				$('#valumetric_weight').val(totalValumetricWeight.toFixed(2));
			}
		}
		$('#length').val(totalLength.toFixed(2));
		$('#breath').val(totalBreath.toFixed(2));	
		$('#height').val(totalHeight.toFixed(2));
	}	
	// this function is use for getting charges acording chargeble weight input field 
	$(".chargable_weight").blur(function () 
	{
		var customer_id   = $('#customer_account_id').val();
		var c_courier_id  = $('#courier_company').val();
		var mode_id   = $('#mode_dispatch').val();
		var state  = $("#reciever_state").val();		
		var city  = $("#reciever_city").val();		
		var doc_type = $("#doc_typee").val();
		var receiver_zone_id = $("#receiver_zone_id").val();
		var invoice_value 	 = parseFloat(($('#invoice_value').val() != '') ? $('#invoice_value').val() : 0);
		var booking_date =$('#booking_date').val();
		var dispatch_details =$('#dispatch_details').val();
		
		var chargable_weight = parseFloat($('#chargable_weight').val()) > 0 ? $('#chargable_weight').val() : 0;
		
		if(customer_id != '' && mode_id != '')
		{
				
			$.ajax({
				type: 'POST',
				url: 'Admin_domestic_shipment_manager/add_new_rate_domestic',
				data: 'customer_id=' + customer_id  +'&c_courier_id=' + c_courier_id+'&mode_id=' + mode_id+'&state=' + state +'&city=' + city +'&chargable_weight='+chargable_weight+'&receiver_zone_id='+receiver_zone_id+'&booking_date='+booking_date+'&invoice_value='+invoice_value+'&dispatch_details='+dispatch_details,
				dataType: "json",
				success: function (data) 
				{	
					console.log("rakesh");
					$('#frieht').val(data.frieht);
					$('#transportation_charges').val(0);
					$('#pickup_charges').val(0);
					$('#delivery_charges').val(0);
					$('#courier_charges').val(0);
					$('#other_charges').val(data.to_pay_charges);
					$('#amount').val(data.amount);
					$('#cft').val(data.cft);
					$('#fuel_charges').val(data.final_fuel_charges);
					$('#sub_total').val(data.sub_total);
					$('#courier_charges').val(data.cod);
					$('#cgst').val(data.cgst);
					$('#sgst').val(data.sgst);
					$('#igst').val(data.igst);
					$('#awb_charges').val(data.docket_charge);
					$('#fov_charges').val(data.fov);
					$('#grand_total').val(data.grand_total);
				}
			});
		}else{
			$('#frieht').val();
		}
	});
	
	// /this is use for getting receiver city info by pincode
	$("#no_of_pack1").on('change', function () 
	{
		if ($('#is_volumetric').is(':checked')) 
		{
			var  no_of_pack1 =  $("#no_of_pack1").val(); 
			$("#volumetric_table").show(); 
			
			
			 var totalRow = $('#volumetric_table_row').find('tr').length;
			console.log(totalRow);
			if (totalRow > 1) 
			{
				for (var jk = 1; jk < totalRow; jk++) 
				{
					console.log(jk);
					$('#volumetric_table_row').find('tr:last').remove();
					//totalRow--;
				} 
			} 
			
			//$('table.weight-table tbody').find('tr').remove();
			for (var i = 2; i <= no_of_pack1; i++) 
			{
				var allTrs = $('table.weight-table tbody').find('tr');
				var lastTr = allTrs[allTrs.length - 1];
				var $clone = $(lastTr).clone();

				var countrows = $(".height").length;
				$clone.find('td').each(function () 
				{
					var el = $(this).find(':first-child');
					var id = el.attr('id') || null;
					if (id) 
					{
						var i = id.substr(id.length - 1);

						var nextElament = countrows; //parseInt(i)+1;
						var remove = 1;
						if (countrows > 10) 
						{
							var remove = 2;
						}
						var removeChar = (id.length - remove);
						var prefix = id.substr(0, removeChar);

						
						//console.log('prefix:::' + prefix + '::::' + id + '::::' + removeChar);
						el.attr('id', prefix + (nextElament));
						el.attr('data-attr', (nextElament));
					}
				});
				$clone.find('input:text').val('');
				$('table.weight-table tbody').append($clone);
				var totalRow = $('table.weight-table tbody').find('tr').length;
				
				if (totalRow > 1) 
				{
					$('.remove-weight-row').show();			
				} else {
					$('.remove-weight-row').hide();
				}
			}
			weightChangeEvent();
		}
		
	});
	
	// /this is use for getting receiver city info by pincode
	$("#is_volumetric").on('change', function () 
	{
		if ($(this).is(':checked')) 
		{
			var  no_of_pack1 =  $("#no_of_pack1").val(); 
			$("#volumetric_table").show(); 
			
			//$('table.weight-table tbody').find('tr').remove();
			for (var i = 2; i <= no_of_pack1; i++) 
			{
				var allTrs = $('table.weight-table tbody').find('tr');
				var lastTr = allTrs[allTrs.length - 1];
				var $clone = $(lastTr).clone();

				var countrows = $(".height").length;
				$clone.find('td').each(function () 
				{
					var el = $(this).find(':first-child');
					var id = el.attr('id') || null;
					if (id) 
					{
						var i = id.substr(id.length - 1);

						var nextElament = countrows; //parseInt(i)+1;
						var remove = 1;
						if (countrows > 10) 
						{
							var remove = 2;
						}
						var removeChar = (id.length - remove);
						var prefix = id.substr(0, removeChar);

						
						//console.log('prefix:::' + prefix + '::::' + id + '::::' + removeChar);
						el.attr('id', prefix + (nextElament));
						el.attr('data-attr', (nextElament));
					}
				});
				$clone.find('input:text').val('');
				$('table.weight-table tbody').append($clone);
				var totalRow = $('table.weight-table tbody').find('tr').length;
				
				if (totalRow > 1) 
				{
					$('.remove-weight-row').show();			
				} else {
					$('.remove-weight-row').hide();
				}
			}
			weightChangeEvent();
		}
		else
		{
			$("#volumetric_table").hide(); 
		}
		
	
	});	
	
	// /this is use for getting receiver city info by pincode
	$("#courier_company").on('change', function () 
	{
		var courier_company = $(this).val();
			$.ajax({
				type: 'POST',
				url: 'Admin_international_shipment_manager/getCountry',
				data: 'courier_company=' + courier_company,
				dataType: "json",
				success: function (d) {
					console.log(d);
					var option;
					option ='<option value="">-Select-</option>';
					for(var i=0;i < d.length;i++)
					{
					option += '<option value="' + d[i].c_courier_id + '" data-id="'+ d[i].z_id +'" >' + d[i].country_name + '</option>';
				}
					$('#reciever_country_id').html(option);
				
			
				}
			});
	});
	$("#customer_account_id").select2();
	$("#sender_city").select2();
	// $("#reciever_state").select2();
	// $("#reciever_city").select2();
	
</script>