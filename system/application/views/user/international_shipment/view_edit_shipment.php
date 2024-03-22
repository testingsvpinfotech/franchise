<?php include(dirname(__FILE__).'/../admin_shared/admin_header.php'); ?>
    <!-- END Head-->

    <!-- START: Body-->
    <body id="main-container" class="default">

        
        <!-- END: Main Menu-->
   
    <?php include(dirname(__FILE__).'/../admin_shared/admin_sidebar.php'); ?>
        <!-- END: Main Menu-->
    <?php 
		$user_type = $this->session->userdata("userType"); 
		
		?>
        <!-- START: Main Content-->
        <main>
            <div class="container-fluid site-width">
                <!-- START: Listing-->
                <div class="row">                 
                  <div class="col-12">
                      <div class="col-12 col-sm-12 mt-3">
                      <div class="card">
					  
                          <div class="card-header">                               
                              <h4 class="card-title">Edit Shipment</h4><span style="float: right;"></span>
                          </div>
						    <div class="card-content">
                          <div class="card-body">
						   <div class="row">                                           
                            <div class="col-12">
                             <form role="form" id="generatePOD" action="admin/update-shipment/<?php echo $booking[0]['booking_id']; ?>" method="post" >
								<div class="box-body">
								 
									<input type="hidden" name="rate" id="rate" value="<?php echo $weight[0]['rate']; ?>" />
									<input type="hidden" name="rate_pack" value="<?php $weight[0]['rate_pack']; ?>" id="rate_pack" />
									<input type="hidden" name="amount_manual" id="amount_manual" value="0" />
									<input type="hidden" name="gst_rate" id="gst_rate" value="<?php echo $booking[0]['gst_rate']; ?>" />
									<input type="hidden" name="roundoff_type" id="roundoff_type" value="<?php echo $weight[0]['roundoff_type'];?>" /> 
									
									<div class="form-group row">
									<h5 class="col-sm-12 card-title">Shipment Info</h5>
										<label   class="col-sm-1 col-form-label">Shipment Type:</label>
										<div class="col-sm-2">
											<div class="custom-control custom-radio custom-control-inline">
												<input type="radio" onchange="tgl(this.value)"   name="doc_type" class="custom-control-input" <?php echo ($booking[0]['doc_nondoc'] == 'Non Document')?'checked':'';?>  id="doc_typee" value="1" required>
												<label class="custom-control-label checkbox-primary outline" for="doc_typee">Non-Doc</label>
											</div>	
											<div class="custom-control custom-radio custom-control-inline">
												<input type="radio" onchange="tgl(this.value)"  name="doc_type" class="custom-control-input" <?php echo ($booking[0]['doc_nondoc'] == 'Document')?'checked':'';?> id="doc_type" value="0" required>
												<label class="custom-control-label checkbox-primary outline" for="doc_type">Doc</label>
											</div>
										</div>
										
										<label   class="col-sm-1 col-form-label">Mode Of Dispatch: </label>
										<div class="col-sm-2">
											<select class="form-control mode_dispatch" name="mode_dispatch">
        										<?php	
													if(!empty($transfer_mode))												
													{
														foreach ($transfer_mode as $row)
														{
															if($row->mode_name==$booking[0]['mode_dispatch'])
															{
															  echo "<option selected='selected' value='".$row->mode_name."'>".$row->mode_name."</option>";  
															}
															else
															{
															echo "<option value='".$row->mode_name."'>".$row->mode_name."</option>";
															}
														}
													}
													?>
												
											</select>
										
										</div>
										<label  class="col-sm-1 col-form-label">Airway No:</label>
										<div class="col-sm-2">
											<input type="text" name="awn" id="awn" class="form-control my-colorpicker1" value="<?php echo $booking[0]['pod_no']; ?>">
										</div>
											<label  class="col-sm-1 col-form-label">Forwording No.:</label>
										<div class="col-sm-2">
											<input type="text" name="forwording_no" value="<?php echo $booking[0]['forwording_no']; ?>" class="form-control my-colorpicker1">
										</div>
										
											<label  class="col-sm-1 col-form-label">Forworder Name:</label>
										<div class="col-sm-2">
										   <select name="forworder_name" class="form-control" id="forworderName">
												<option value="">Select Forworder Name</option>
												<option value="prabhatairexpress" <?php echo ($booking[0]['forworder_name'] == 'prabhatairexpress')?'selected':'';?>>Prabhat Air Express</option>
											</select>
										</div>
										
										</div>
										<hr>
									<div class="form-group row">
										<h5 class="col-sm-12 card-title">Consigner Detail</h5>
										<label  class="col-sm-1 col-form-label">Customer Name:</label>
										<div class="col-sm-2">
											 <select class="form-control"  name="customer_account_id" id="customer_account_id">
												<option value="">Select Customer</option>
												<?php
													if (count($customers)) {
														foreach ($customers as $rows) {
															?>
												<option value="<?php echo $rows['customer_id']; ?>" <?php echo ($booking[0]['sender_name'] == $rows['customer_name'])?'selected':'';?>><?php echo $rows['customer_name']; ?>--<?php echo $rows['cid']; ?> </option>
												<?php
													}
													} else {
													echo "<p>No Data Found</p>";
													}
													?>
											</select>
										</div>
											<label class="col-sm-1 col-form-label">Name:</label>
											<div class="col-sm-2">
												<input type="text" name="sender_name" value="<?php echo $booking[0]['sender_name']; ?>"  readonly id="sender" class="form-control my-colorpicker1">
											</div>
											<label class="col-sm-1 col-form-label">Address:</label>
											<div class="col-sm-2">
												<input type="text" name="sender_address" alue="<?php echo $booking[0]['sender_address']; ?>"  readonly id="sender_address" class="form-control my-colorpicker1">
											</div>
											<label class="col-sm-1 col-form-label">City:</label>
											<div class="col-sm-2">
												<select class="form-control"  name="sender_city" readonly id="sender_city" required>
													<option value="">Select City</option>
													<?php
														if (count($cities)) {
															foreach ($cities as $rows) {
																?>
													<option value="<?php echo $rows['id']; ?>" <?php echo ($booking[0]['sender_city'] == $rows['id'])?'selected':'';?>>
														<?php echo $rows['city']; ?> 
													</option>
													<?php }
														} else {
														echo "<p>No Data Found</p>";
														}
														?>
												</select>
											</div>
											<label class="col-sm-1 col-form-label">Pincode No.:</label>
											<div class="col-sm-2">
												<input type="text" name="sender_pincode" value="<?php echo $booking[0]['sender_pincode']; ?>" readonly id="sender_pincode" class="form-control my-colorpicker1">
											</div>
												<label class="col-sm-1 col-form-label">Contact No.:</label>
											<div class="col-sm-2">
												<input type="text" name="sender_contactno" value="<?php echo $booking[0]['sender_contactno']; ?>" readonly id="sender_contactno" class="form-control my-colorpicker1">
											</div>
												<label class="col-sm-1 col-form-label">Sender GST NO.:</label>
											<div class="col-sm-2">
												<span><input type="text" name="sender_gstno" value="<?php echo $booking[0]['sender_gstno']; ?>" readonly id="sender_gstno" class="form-control my-colorpicker1" readonly></span>
											</div>
											</div>
											<hr>
									<div class="form-group row">
											<h5 class="col-sm-12 card-title">Consignee Detail</h5>
												<label class="col-sm-1 col-form-label">Name:</label>
											<div class="col-sm-2">
												<input type="text" name="reciever_name" id="reciever" value="<?php echo $booking[0]['reciever_name']; ?>" class="form-control my-colorpicker1" required>
											</div>
												<label class="col-sm-1 col-form-label">Address:</label>
											<div class="col-sm-2">
												<input type="text" name="reciever_address" id="reciever_address" value="<?php echo $booking[0]['reciever_address']; ?>" class="form-control my-colorpicker1">
											</div>
										
										<label   class="col-sm-1 col-form-label">Pincode: </label>
										<div class="col-sm-2">
											<input type="text" class="form-control" name="reciever_pincode" value="<?php echo $booking[0]['reciever_pincode']; ?>" id="reciever_pincode" placeholder="Enter Pincode" />
										</div>
										<label   class="col-sm-1 col-form-label">City Name:</label>
										<div class="col-sm-2">
											<select class="form-control" id="reciever_city" name="reciever_city">
												<option value="">Select City</option>
												<?php 
												foreach ($cities as $rows ) { 
												?>
												<option value="<?php echo $rows['id'];?>" <?php echo ($booking[0]['reciever_city'] == $rows['id'])?'selected':'';?>>
												<?php
												echo $rows['city'];?> 
												</option>
												<?php 
												}
												?>
											</select>
										</div>
										
										
										</div>
										<div class="form-group row">
											<label   class="col-sm-1 col-form-label">Company Name:</label>
											<div class="col-sm-2">
												<input type="text" class="form-control" name="contactperson_name"  placeholder="Enter Contact Person" />
											</div>
											<label   class="col-sm-1 col-form-label">Contact No.:</label>
											<div class="col-sm-2">
												<input type="text" class="form-control" name="reciever_contact" value="<?php echo $booking[0]['reciever_contact']; ?>"   placeholder="Enter Contact No" />
											</div>
												<label class="col-sm-1 col-form-label">Reciever GST NO.:</label>
											<div class="col-sm-2">
												<input type="text" name="receiver_gstno" id="reciever_gstno"  value="<?php echo $booking[0]['receiver_gstno']; ?>"   class="form-control my-colorpicker1">
											</div>
													<label class="col-sm-1 col-form-label">Ref No:</label>
											 <div class="col-sm-2">
													<input type="text" name="ref_no" id="ref_no"  value="<?php echo $booking[0]['ref_no']; ?>"  class="form-control">
												</div>
										</div>
										<div class="form-group row"  id="panel1" style="display:none;" >
													<label class="col-sm-1 col-form-label">INV No.:</label>
												 <div class="col-sm-2">
													<input type="text" name="invoice_no" id="invoice_no"  value="<?php echo $booking[0]['invoice_no']; ?>" class="form-control my-colorpicker1">
												</div>
													<label class="col-sm-1 col-form-label">Inv. Value:</label>
												 <div class="col-sm-2">
													<input type="text" name="insurance_value" id="insurance_value"  value="<?php echo $booking[0]['insurance_value']; ?>" class="form-control my-colorpicker1">
												</div>
													<label class="col-sm-1 col-form-label">Eway No:</label>
												<div class="col-sm-2">
													<input type="text" name="eway_no" id="eway_no"  value="<?php echo $booking[0]['eway_no']; ?>" class="form-control">
												</div>
											
										</div>
										
										
										<hr>
									<div class="form-group row">
										<h5 class="col-sm-12 card-title">Measurement Units</h5>
									
												<input type="hidden"  name="length_unit" id="length_unit" class="custom-control-input" value="cm">
												
										
											
											<div class="col-sm-12">
												<table class="weight-table">
													<thead>
														<tr>
															<th>PKT:</th>
															<th>Per Box Weight :</th>
															<th>Actual Weight:</th>
															<th>Chargeable Weight:</th>
															<th>Valumetric Weight</th>
															<th>L</th>
															<th>B</th>
															<th>H</th>
															<th>1CFT(kg)</th>
														</tr>
													<thead>
													<tbody>
													<?php
															$no_pack_detail = json_decode($weight[0]['no_pack_detail']);
															$per_box_weight_detail = json_decode($weight[0]['per_box_weight_detail']);
															$actual_weight_detail = json_decode($weight[0]['actual_weight_detail']);
															$valumetric_weight_detail = json_decode($weight[0]['valumetric_weight_detail']);
															$length_detail = json_decode($weight[0]['length_detail']);
															$breath_detail = json_decode($weight[0]['breath_detail']);
															$height_detail = json_decode($weight[0]['height_detail']);
															$one_cft_kg_detail = json_decode($weight[0]['one_cft_kg_detail']);
															for ($i = 0; $i < count($actual_weight_detail); $i++) {
															?>
																<tr>
																		<td><input type="<?php echo ($user_type > 1)?'hidden':'text';?>" name="no_pack_detail[]"   value="<?php echo $no_pack_detail[$i]; ?>" class="form-control my-colorpicker1 no_of_pack"  data-attr="1" id="no_of_pack1" required="required"></td>
																		<td><input type="<?php echo ($user_type > 1)?'hidden':'text';?>" name="per_box_weight_detail[]"  value="<?php echo $actual_weight_detail[$i]; ?>" class="form-control my-colorpicker1 per_box_weight"  data-attr="1" id="per_box_weight1" required="required"></td>
																		<td><input type="<?php echo ($user_type > 1)?'hidden':'text';?>" name="actual_weight_detail[]"   value="<?php echo $actual_weight_detail[$i]; ?>" readonly class="form-control my-colorpicker1 actual_weight"  data-attr="1" id="actual_weight1"></td>
																		<td><input type="<?php echo ($user_type > 1)?'hidden':'text';?>" name="chargable_weight"  value="<?php echo $weight[0]['chargable_weight']; ?>" class="form-control" id="chargable_weight"/></td>
																		<td><input type="<?php echo ($user_type > 1)?'hidden':'text';?>" name="valumetric_weight_detail[]"  value="<?php echo $valumetric_weight_detail[$i]; ?>"  readonly class="form-control my-colorpicker1 valumetric_weight" data-attr="1" id="valumetric_weight1"></td>
																		<td><input type="<?php echo ($user_type > 1)?'hidden':'text';?>" name="length_detail[]"  value="<?php echo $length_detail[$i]; ?>" class="form-control my-colorpicker1 length" data-attr="1" id="length1" ></td>
																		<td><input type="<?php echo ($user_type > 1)?'hidden':'text';?>" name="breath_detail[]"  value="<?php echo $breath_detail[$i]; ?>" class="form-control my-colorpicker1 breath" data-attr="1" id="breath1" ></td>
																		<td><input type="<?php echo ($user_type > 1)?'hidden':'text';?>" name="height_detail[]"  value="<?php echo $height_detail[$i]; ?>" class="form-control my-colorpicker1 height" data-attr="1" id="height1" ></td>
																		<td><input type="<?php echo ($user_type > 1)?'hidden':'text';?>" name="one_cft_kg_detail[]"  value="<?php echo $one_cft_kg_detail[$i]; ?>" readonly class="form-control my-colorpicker1 one_cft_kg" data-attr="1" id="one_cft_kg1"></td>
																		
																	</tr>
															<?php
															}
															?>
														
														
													</tbody>
													<tfoot>
													<a href="javascript:void(0)" class="btn btn-sm btn-primary add-weight-row"><i class="icon-plus"></i></a>&nbsp;<a href="javascript:void(0)" class="btn btn-sm btn-danger remove-weight-row"><i class="icon-trash"></i></a>
													</tfoot>
												</table>
												<div>
													<table style="border-collapse: separate;border-spacing: 13px;margin-bottom:15px;">
														<tr>
															<td><input type="text" name="no_of_pack" readonly="readonly" class="form-control my-colorpicker1 no_of_pack" id="no_of_pack" value="<?php echo $weight[0]['no_of_pack']; ?>" required="required"></td>
															<td><input type="text" name="per_box_weight" readonly="readonly" class="form-control my-colorpicker1 per_box_weight" id="per_box_weight" required="required"></td>
															<td><input type="text" name="actual_weight" readonly="readonly" class="form-control my-colorpicker1 actual_weight" id="actual_weight" value="<?php echo $weight[0]['actual_weight']; ?>"></td>
															<td><input type="text" name="valumetric_weight" readonly="readonly" class="form-control my-colorpicker1 valumetric_weight" id="valumetric_weight"></td>
															<td ><input type="text" name="length" readonly="readonly" class="form-control my-colorpicker1 length"  id="length" required="required"></td>
															<td><input type="text" name="breath" readonly="readonly" class="form-control my-colorpicker1 breath"  id="breath" required="required"></td>
															<td><input type="text" name="height" readonly="readonly" class="form-control my-colorpicker1 height" id="height" required="required"></td>
															<td><input type="text" name="one_cft_kg" readonly="readonly" class="form-control my-colorpicker1 one_cft_kg" id="one_cft_kg"></td>
														</tr>
													</table>
													<a href="javascript:void(0)" class="add-weight-row">Add Row</a>
													<a href="javascript:void(0)" class="remove-weight-row">Remove</a>
												</div>
												
											</div>
										</div>
										<hr>
										<div class="form-group row">
										<h5 class="col-sm-12 card-title">Other Info</h5>
												<label  class="col-sm-1 col-form-label">Special Instruction:</label>
										<div class="col-sm-2">
												<textarea name="special_instruction" class="form-control my-colorpicker1"><?php echo $weight[0]['special_instruction']; ?></textarea>
										</div>
												<label  class="col-sm-1 col-form-label">Type of Pack:</label>
										<div class="col-sm-2">
												<input type="text" name="type_of_pack" value="<?php echo $weight[0]['type_of_pack']; ?>"  class="form-control my-colorpicker1">
										</div>
												<label  class="col-sm-1 col-form-label">Booking Date:</label>
										<div class="col-sm-2">
												<input type="text" name="booking_date" value="<?php echo $booking[0]['booking_date'];?>" id="booking_date" class="form-control my-colorpicker1 datepicker">
										</div>
												<label  class="col-sm-1 col-form-label">Estimate Delivery Date:</label>
										<div class="col-sm-2">
												<input type="text" id="delivery_date" value="<?php echo $booking[0]['delivery_date']; ?>"  name="delivery_date" id="eod" class="form-control my-colorpicker1 datepicker">
										</div>
										</div>
										<div class="form-group row">
											<label   class="col-sm-1 col-form-label">Dispatch Details:</label>
											<div class="col-sm-3">
												<div class="custom-control custom-radio custom-control-inline">
													<input type="radio"  name="dispatch_details" class="custom-control-input" <?php echo ($booking[0]['dispatch_details'] =="cash")?'checked':''; ?> id="cash" value="cash">
													<label class="custom-control-label checkbox-primary outline" for="cash">Cash</label>
												</div>	
												<div class="custom-control custom-radio custom-control-inline">
													<input type="radio" name="dispatch_details" class="custom-control-input" <?php echo ($booking[0]['dispatch_details'] =="credit")?'checked':''; ?>  id="credit" value="credit" checked>
													<label class="custom-control-label checkbox-primary outline" for="credit">Credit</label>
												</div>
												<div class="custom-control custom-radio custom-control-inline">
													<input type="radio"  name="dispatch_details" class="custom-control-input" <?php echo ($booking[0]['dispatch_details'] =="To Pay")?'checked':''; ?>  id="topay" value="To Pay">
													<label class="custom-control-label checkbox-primary outline" for="topay">To Pay</label>
												</div>
											</div>
											<input type="hidden" name="rate_type" checked="checked" id="no_of_pack" value="weight" class="rate_type" >
											
											
										</div>
										<hr>
										<div id="cont" style="display:none">
										<div class="form-group row">
											<h5 class="col-sm-12 card-title">Charges</h5>
													<label class="col-sm-1 col-form-label">Freight</label>
													<div class="col-sm-2">
													<input type="<?php echo ($user_type > 1)?'hidden':'text';?>" value="<?php echo $charges[0]['frieht']; ?>"  name="frieht" id="frieht"/>
													</div>
													<label   class="col-sm-1 col-form-label">Fov</label>
													<div class="col-sm-2">
													<input type="<?php echo ($user_type > 1)?'hidden':'text';?>" name="fov" value="<?php echo $charges[0]['fov']; ?>"  id="fov">
													</div>
													<label   class="col-sm-1 col-form-label">Pickup Charges</label>
													<div class="col-sm-2">
													<input type="<?php echo ($user_type > 1)?'hidden':'number';?>" name="oda" id="oda"  value="<?php echo $charges[0]['oda']; ?>">
													
													</div>
													<label   class="col-sm-1 col-form-label"> Delivery Charges</label>
													<div class="col-sm-2">
													<input type="<?php echo ($user_type > 1)?'hidden':'number';?>" name="to_pay"  value="<?php echo $charges[0]['to_pay']; ?>" id="to_pat">
													</div>
										</div>
										<div class="form-group row">
													<label   class="col-sm-1 col-form-label"> Handling Charges</label>
													<div class="col-sm-2">
													<input type="<?php echo ($user_type > 1)?'hidden':'text';?>" name="apmt_delivery"   value="<?php echo $charges[0]['apmt']; ?>" id="apmt_delivery">
													</div>
													<label   class="col-sm-1 col-form-label"> AWB Charges</label>
													<div class="col-sm-2">
													<input type="<?php echo ($user_type > 1)?'hidden':'text';?>" name="dod_daoc"   value="<?php echo $charges[0]['dod_daoc']; ?>" id="dod_doac">
													</div>
													
													<label   class="col-sm-1 col-form-label">Other charges</label>
													<div class="col-sm-2">
													<input type="<?php echo ($user_type > 1)?'hidden':'text';?>" name="other_charges"   value="<?php echo $charges[0]['other_charges']; ?>" id="other_charges">
													</div>
													<label   class="col-sm-1 col-form-label">Total</label>
													<div class="col-sm-2">
														<input type="<?php echo ($user_type > 1)?'hidden':'text';?>"  name="amount"  value="<?php echo $charges[0]['amount']; ?>" id="amount"/>
													</div>
													<label   class="col-sm-1 col-form-label">Fuel Surcharge</label>
													<div class="col-sm-2">
														<input type="<?php echo ($user_type > 1)?'hidden':'text';?>" readonly="readonly" name="fuel_subcharges"  value="<?php echo $charges[0]['fuel_subcharges']; ?>"  id="fuel_charges">
													</div>	
												
										</div>    
										</div>    
                                    <hr>
								<div class="form-group row">
                                    
                                    <h5 class="col-sm-12 card-title">Final Charge</h5>
                                        <label  class="col-sm-1 col-form-label">Sub Total</label>
                                        <div class="col-sm-2">
										
										<input type="<?php echo ($user_type > 1)?'hidden':'text';?>" name="sub_total"  value="<?php echo $charges[0]['sub_total']; ?>"  id="sub_total"/>
										</div>
                                    
                                    <input type="hidden" name="gst_charges" id="gst_charges" value="1">
                                        <label class="col-sm-1 col-form-label">SGST Tax</label>
                                    <div class="col-sm-2">
										<div class="input-group mb-3 gst_charges">
											
											<input type="<?php echo ($user_type > 1)?'hidden':'text';?>" id="sgst" step="any"  value="<?php echo $charges[0]['SGST']; ?>"  name="sgst" readonly>
											<div class="input-group-append ">
												<span class="input-group-text bg-transparent border-right-0" id="basic-email1"><i class="fa fa-percent"></i></span>
											</div>
										</div>
									</div>
                                        <label class="col-sm-1 col-form-label">IGST Tax</label>
                                     <div class="col-sm-2 gst_charges">
                                       <div class="input-group mb-3">
											<input  type="<?php echo ($user_type > 1)?'hidden':'text';?>" id="igst" value="<?php echo $charges[0]['IGST']; ?>" step="any" name="igst" readonly>
											<div class="input-group-append">
													<span class="input-group-text bg-transparent border-right-0" id="basic-email1"><i class="fa fa-percent"></i></span>
												</div>
                                        </div>
                                        </div>
                                    
                                        <label class="col-sm-1 col-form-label">CGST Tax</label>
                                    <div class="col-sm-2 gst_charges">
											<div class="input-group mb-3">
											<input  type="<?php echo ($user_type > 1)?'hidden':'text';?>" value="<?php echo $charges[0]['CGST']; ?>" id="cgst" step="any" name="cgst" readonly><span class="input-group-addon">
												<div class="input-group-append">
													<span class="input-group-text bg-transparent border-right-0" id="basic-email1"><i class="fa fa-percent"></i></span>
												</div>
											</div>
                                    </div>
                                 </div>
                                     </div>
                                    <div class="form-group row">
                                        <label class="col-sm-1 col-form-label">Grand Total</label>
										<div class="col-sm-2">
										<div class="input-group mb-3">
                                       
										<input type="<?php echo ($user_type > 1)?'hidden':'text';?>" readonly name="grand_total"  id="grand_total"  value="<?php echo $charges[0]['total_amount']; ?>"/>
										<div class="input-group-append">
													<span class="input-group-text bg-transparent border-right-0" id="basic-email1"><i class="fa fa-usd" aria-hidden="true"></i>
</span>
												</div>
										</div>
										</div>
										</div>
                             <input type="hidden" name="minimum_amount" id="minimum_amount" value="">				
									<div class="form-group row">
										<div class="col-md-2">
											<div class="box-footer">
												<button type="submit"  class="btn btn-primary">Update Shipment</button>
											</div>
										</div>
										<!-- /.box-body -->
									</div>
									
									</div>
								</div>
							</form>
                        </div> 
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
        
        <?php  include(dirname(__FILE__).'/../admin_shared/admin_footer.php'); ?>
        <!-- START: Footer-->
    </body>
    <!-- END: Body-->
</html>
<script>
var rateMasterGet = true;
weightChangeEvent();
 // this is use for getting the customer information 
	$("#customer_account_id").change(function () 
	{
		var customer_name = $(this).val();
		if (customer_name != null || customer_name != '') {
			$.ajax({
				type: 'POST',
				dataType: "json",
				url: 'Admin_shipment_manager/getsenderdetails',
				data: 'customer_name=' + customer_name,
				success: function (data) {
					$("#sender").val(data.user.customer_name);
					$("#sender_address").val(data.user.address);
					$("#sender_pincode").val(data.user.pincode);
					$("#sender_contactno").val(data.user.phone);
					$("#sender_gstno").val(data.user.gstno);
					$("#gst_charges").val(data.user.gst_charges);
					$("#sender_city").val(data.user.city);
					$("#sender_city").select2();
					$("#customer_account_id").val(customer_name);
					if (data.user.gst_charges == 0) {
						$('.gst_charges').hide();
					} else {
						$('.gst_charges').show();
					}
				}
			});
		}
	});

	
	// this is use for show hide the invoice value input box according doc and non-doc 
	function tgl(val) 
	{
		var cont = document.getElementById('cont');
		var panel1 = document.getElementById('panel1');

		if(val==1) 
		{
			$('#cont').show();
			$('#panel1').show();
		}
		else if(val==0) 
		{
			$('#cont').hide();
			$('#panel1').hide();
		}
	}
	
	// this is use for adding the new row for in Measurement Units
	$(".add-weight-row").on('click', function () 
	{
		var allTrs = $('table.weight-table tbody').find('tr');
		var lastTr = allTrs[allTrs.length - 1];
		var $clone = $(lastTr).clone();

		var countrows = $(".one_cft_kg").length;
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
				
				console.log('prefix:::' + prefix + '::::' + id + '::::' + removeChar);
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
	
	// this is use for getting sender city info by pincode
	$("#sender_pincode").blur(function () 
	{
		var pincode = $(this).val();
		if (pincode != null || pincode != '') {
			$.ajax({
				type: 'POST',
				url: 'Admin_shipment_manager/getcity',
				data: 'pincode=' + pincode + '&mode_dispatch=' + $('.mode_dispatch').val(),
				success: function (d) {
					var v = d.toLowerCase();
					var uppercaseFirstLetter = v.charAt(0).toUpperCase() + v.slice(1);
					console.log(uppercaseFirstLetter);
					selectByText($.trim(uppercaseFirstLetter));
				
				}
			});
		}
	});
	
	// this is use for getting receiver city info by pincode
	$("#reciever_pincode").on('change', function () 
	{
		var pincode = $(this).val();
		if (pincode != null || pincode != '') {

		
			$.ajax({
				type: 'POST',
				url: 'Admin_shipment_manager/getcity',
				data: 'pincode=' + pincode,
				dataType: "json",
				success: function (d) {
					var option;
					option += '<option value="' + d.id + '">' + d.city + '</option>';
					$('#reciever_city').html(option);
				
			
				}
			});

		}
	});
	
	
	// this function is use for calling the function for charges accrding doc and non doc 
	$("#sender_city, #reciever_city, .mode_dispatch, #actual_weight, #valumetric_weight, .per_box_weight, #no_of_pack, .rate_type, #insurance_value").change(function () 
	{

		
		//	calulateTotal();
		if ($("#insurance_value").val() == '') 
		{
			if ($("input[name='doc_type']:checked").val() == '0') 
			{
				add_doc_rate();
			}
		}
		else 
		{
			//calulateTotal();

			if ($("input[name='doc_type']:checked").val() == '0') {
			
				add_doc_rate();
			}
			else
			{
				calulateTotal();
			
			}
			

		}

	});

	// this function is use for getting charges acording chargeble weight input field 
	$("#chargable_weight").change(function () 
	{
		if ($("#insurance_value").val() == '') 
		{
			if ($("input[name='doc_type']:checked").val() == '0') {
				add_doc_rate();
			}
		}
		else 
		{
			calulateTotal_chargeble();
			if ($("input[name='doc_type']:checked").val() == '0') 
			{
				add_doc_rate();
			}
		}
	});
	
	
	// this functin is use for gettting documant charges 
	function add_doc_rate() 
	{

		if ($("input[name='doc_type']:checked").val() == '0') {
			var doc = '1';
		} else {
			var doc = '0';
		}

		var weight = $("#per_box_weight1").val();
		var qty = $("#no_of_pack1").val();
		var receiverPincode = $("#reciever_pincode").val();
		var senderPincode = $("#sender_pincode").val();
		var customer_id = $('#customer_account_id').val();
		var sender_city = $('#sender_city').val();
		var receiver_city = $('#reciever_city').val();
		var mode_dispatch = $('.mode_dispatch').val();
		var customer_name = $('#customer_account_id').val();
		var actual_weight = (parseFloat($('#actual_weight').val()) > 0) ? $('#actual_weight').val() : 0;
		var valumetric_weight = parseFloat($('#valumetric_weight').val()) > 0 ? $('#valumetric_weight').val() : 0;
		var rate_type = $('.rate_type').val();
		var no_of_pack = parseFloat($('#no_of_pack').val()) > 0 ? $('#no_of_pack').val() : 0;


		// alert(weight);
		// alert(receiverPincode);
		//+ '&mode_dispatch=' + mode_dispatch + '&rate_type=' + rate_type + '&no_of_pack=' + no_of_pack

		if (weight != '' && receiverPincode != '' && qty != '') {
			$.ajax({
				type: 'POST',
				url: 'Admin_shipment_manager/add_new_rate',
				data: 'weight=' + weight + '&senderPincode=' + senderPincode + '&receiverPincode=' + receiverPincode + '&customer_id=' + customer_id + '&qty=' + qty + '&doc=' + doc + '&mode_dispatch=' + mode_dispatch + '&rate_type=' + rate_type + '&no_of_pack=' + no_of_pack,
				dataType: "json",
				success: function (data) {

					// alert("nn");
					$('#sgst').val(data.sgst);
					$('#cgst').val(data.cgst);
					$('#igst').val(data.igst);
					$('#sub_total').val(data.sub_total);
					$('#grand_total').val(data.grand_total);
				}
			});
		}

	}
	
	// this function is useing for getting non document charges 
	function calulateTotal() 
	{
		var sender_city			= $('#sender_city').val();
		var receiver_city 		= $('#reciever_city').val();
		var mode_dispatch 		= $('.mode_dispatch').val();
		var customer_name 		= $('#customer_account_id').val();
		var actual_weight 		= (parseFloat($('#actual_weight').val()) > 0) ? $('#actual_weight').val() : 0;
		var valumetric_weight 	= parseFloat($('#valumetric_weight').val()) > 0 ? $('#valumetric_weight').val() : 0;
		var rate_type 			= $('.rate_type').val();
		var no_of_pack 			= parseFloat($('#no_of_pack').val()) > 0 ? $('#no_of_pack').val() : 0;
 
 /* 
	 	alert(sender_city); // 337
		alert(receiver_city); // 337
		alert(mode_dispatch); // air
		alert(customer_name); // 1
		alert(actual_weight); //0	
		alert(valumetric_weight); //0
		alert(rate_type); // weight
		alert(no_of_pack); //0 */
		
		if ((((actual_weight > 0 || valumetric_weight > 0 || rateMasterGet) && rate_type == 'weight') || (rate_type == 'no_of_pack' && no_of_pack)) && customer_name != null && customer_name != '' && sender_city != '' && receiver_city != '' && receiver_city != 'undefined' && mode_dispatch != '') 
		{
		
			$.ajax({
				type: 'POST',
				dataType: "json",
				url: 'Admin_shipment_manager/getRateMasterDetails',
				data: 'customer_name=' + customer_name + '&sender_city=' + sender_city + '&receiver_city=' + receiver_city + '&mode_dispatch=' + mode_dispatch + '&rate_type=' + rate_type + '&no_of_pack=' + no_of_pack,
				success: function (data) {


					var dod_doac = data.rate_master !== undefined && parseFloat(data.rate_master.dod_doac) > 0 ? parseFloat(data.rate_master.dod_doac) : 0;
					var weight_range = data.rate_master !== undefined && parseFloat(data.rate_master.weight_range) > 0 ? parseFloat(data.rate_master.weight_range) : 0;
					var packing = data.rate_master !== undefined && parseFloat(data.rate_master.packing) > 0 ? parseFloat(data.rate_master.packing) : 0;
					var handling = data.rate_master !== undefined && parseFloat(data.rate_master.handling) > 0 ? parseFloat(data.rate_master.handling) : 0;
					var fov = data.rate_master !== undefined && parseFloat(data.rate_master.fov) > 0 ? parseFloat(data.rate_master.fov) : 0;
					var min_fov = data.rate_master !== undefined && parseFloat(data.rate_master.min_fov) > 0 ? parseFloat(data.rate_master.min_fov) : 0;
					var fuel_charges = data.rate_master !== undefined && parseFloat(data.rate_master.fuel_charges) > 0 ? parseFloat(data.rate_master.fuel_charges) : 0;
					var rate = data.rate_master !== undefined && parseFloat(data.rate_master.rate) > 0 ? parseFloat(data.rate_master.rate) : 0;
					var insuranceValue = parseFloat($('#insurance_value').val()) > 0 ? parseFloat($('#insurance_value').val()) : 0;
					var rate_pack = data.rate_master !== undefined && data.rate_master_pack != undefined && parseFloat(data.rate_master_pack.rate) > 0 ? parseFloat(data.rate_master_pack.rate) : 0;
					var oda = parseFloat($('#oda').val()) > 0 ? parseFloat($('#oda').val()) : 0;
					var cft = data.rate_master !== undefined && parseFloat(data.rate_master.cft) > 0 ? parseFloat(data.rate_master.cft) : 1;
					var actual_weight = parseFloat($('#actual_weight').val()) > 0 ? parseFloat($('#actual_weight').val()) : 0;
					var chargable_weight = actual_weight;
					var eod = data.rate_master !== undefined ? data.rate_master.eod : '';
					var min_freight = data.rate_master.min_freight !== undefined && parseFloat(data.rate_master.min_freight) > 0 ? parseFloat(data.rate_master.min_freight) : 0;
					var min_amount = data.rate_master.min_amount !== undefined && parseFloat(data.rate_master.min_amount) > 0 ? parseFloat(data.rate_master.min_amount) : 0;
					//alert(min_amount);
					var fc_type = data.rate_master !== undefined && data.rate_master.fc_type !== undefined ? data.rate_master.fc_type : '';
					var gst_rate = data.rate_master !== undefined && data.rate_master.gst_rate !== undefined ? data.rate_master.gst_rate : 0;
					var roundoff_type = data.rate_master !== undefined && data.rate_master.roundoff_type !== undefined ? data.rate_master.roundoff_type : '2';
					$("#roundoff_type").val(roundoff_type);

					var cod = data.rate_master !== undefined && data.rate_master.cod !== undefined ? data.rate_master.cod : '';
					var edd = data.rate_master !== undefined && data.rate_master.edd !== undefined ? data.rate_master.edd : '';
					var to_pay_charges = data.rate_master !== undefined && data.rate_master.to_pay_charges !== undefined ? data.rate_master.to_pay_charges : '';
					//$("#roundoff_type").val(cod);
					if (edd != '') {
						var now = new Date();
						now.setDate(now.getDate() + parseInt(edd));

						var month = now.getMonth() + 1;
						var day = now.getDate();
						var year = now.getFullYear();
						var formatedDate = day + "-" + month + "-" + year;
						$("#delivery_date").val(formatedDate);
					}

					if (to_pay_charges != '') {
						$("#to_pat").val(to_pay_charges);
					}
					if (cod != '') {
						$("#cod").val(cod);
					}

					//var chargable_weight;
					$('#eod').val(eod);
				
					//console.log('ac Weight' + chargable_weight);
					if (parseInt(valumetric_weight) > parseInt(chargable_weight)) {
						chargable_weight = valumetric_weight;
					}
					
					if (parseInt(weight_range) > parseInt(chargable_weight)) {
						chargable_weight = weight_range;
					}

				

					if (fov > 0 && insuranceValue > 0) {
						fov = ((fov * insuranceValue) / 100).toFixed(2);
						// $('#fov').val(fov);
						if (min_fov > fov) {
							$('#fov').val(min_fov);
						} else {
							$('#fov').val(fov);
						}

					}
					$('#chargable_weight').val(chargable_weight);
					$('#rate').val(rate);
					$('#one_cft_kg').val(cft);
					$('#dod_doac').val(dod_doac);
					$('#rate_pack').val(rate_pack);
					$('#gst_rate').val(gst_rate);
					finalRate(fuel_charges, min_freight, min_amount, fc_type);
				}
			});
		}
		else
		{
		//	alert('step2222');
		}
	}

	// this function is useing for getting non document charges 
	function finalRate(fuel_charges, min_freight, min_amount, fc_type) 
	{

		var total_charge = 0;
		var total_pay = 0;
		var total = 0;
		

		var chargable_weight = parseFloat($('#chargable_weight').val()) > 0 ? parseFloat($('#chargable_weight').val()) : 0;
		var rate = parseFloat($('#rate').val()) > 0 ? parseFloat($('#rate').val()) : 0;
		var dod_doac = parseFloat($('#dod_doac').val()) > 0 ? parseFloat($('#dod_doac').val()) : 0;
		var apmt_delivery = parseFloat($('#apmt_delivery').val()) > 0 ? parseFloat($('#apmt_delivery').val()) : 0;
		var fov = parseFloat($('#fov').val()) > 0 ? parseFloat($('#fov').val()) : 0;
		var gst_charges = parseFloat($('#gst_charges').val()) > 0 ? parseFloat($('#gst_charges').val()) : 0;
		var oda = parseFloat($('#oda').val()) > 0 ? parseFloat($('#oda').val()) : 0;
		var to_pat = parseFloat($('#to_pat').val()) > 0 ? parseFloat($('#to_pat').val()) : 0;
		var rate_type = $('.rate_type').val();
		var no_of_pack = parseFloat($('#no_of_pack').val()) > 0 ? parseFloat($('#no_of_pack').val()) : 0;
		var cod = parseFloat($('#cod').val()) > 0 ? parseFloat($('#cod').val()) : 0;
		var demurrage = parseFloat($('#demurrage').val()) > 0 ? parseFloat($('#demurrage').val()) : 0;
		var other_charges = parseFloat($('#other_charges').val()) > 0 ? parseFloat($('#other_charges').val()) : 0;
		var gst_rate = $("#gst_rate").val() > 0 ? $("#gst_rate").val() : 0;
		var gst = gst_rate / 2;
		if (rate_type == 'weight' || rate_type == 'no_of_pack') 
		{
			if ($('#amount_manual').val() == 1 && $('#frieht').val() > 0) {
				total_pay = $('#frieht').val() ? $('#frieht').val() : 0;
			} else {
				total_pay = (rate_type == 'weight') ? (parseFloat(rate) * parseFloat(chargable_weight)) : $('#rate_pack').val();
				//lert(total_pay+':::'+rate_type+':::'+rate+'::::'+chargable_weight+':::::'+$('#rate_pack').val())
				var frieght = total_pay;
				
				$('#frieht').val(frieght.toFixed(2));
				
			}
			console.log('Total Pay' + total_pay);
			console.log('chargable Weight' + chargable_weight);
			
			total = (total_pay + dod_doac + fov + oda + to_pat + apmt_delivery + cod + demurrage + other_charges).toFixed(2);
			$('#amount').val(total);
			if (fuel_charges != 'undefind' && fuel_charges > 0) {
				if (fc_type == 'freight') {
					
					fuel_charges = parseFloat(($('#frieht').val() * fuel_charges) / 100).toFixed(2);
				} else if (fc_type == 'total') {
					
					fuel_charges = parseFloat((total * fuel_charges) / 100).toFixed(2);
				} else {
					fuel_charges = 0;
				}
				$('#fuel_charges').val(fuel_charges);
			} else 
			{
				fuel_charges = parseFloat($('#fuel_charges').val() > 0 ? $('#fuel_charges').val() : 0);
			}

			var total_charges = parseFloat(total) + parseFloat(fuel_charges);
			if (min_freight > total_charges || total_charges == '') {
				total_charge = min_freight;
			} else {
				total_charge = Math.round(total) + Math.round(fuel_charges);
			}
			var totgst = 0;
			
			var sender_gstno = $('#sender_gstno').val();
			var first_two_gst_no = sender_gstno.substring(0, 2);
			if (first_two_gst_no == '27') {
				console.log("gst_charges::::" + gst_charges);
				var igst = 0;
				var sgst = 0;
				var cgst = 0;
				if (gst_charges == 1) {
					
					var sgst = total_charge * gst / 100;
					var cgst = total_charge * gst / 100;
					$('#sgst').val(sgst.toFixed(2));
					$('#cgst').val(cgst.toFixed(2));
					$('#igst').val(igst.toFixed(2));
					
				}
				totgst = sgst + cgst;
			
			} else {
				var igst = 0;
				var sgst = 0;
				var cgst = 0;
				if (gst_charges == 1) {
				
					igst = total_charge * gst / 100;
					$('#sgst').val(sgst.toFixed(2));
					$('#cgst').val(cgst.toFixed(2));
					$('#igst').val(igst.toFixed(2));
					

				}
				//   totgst =  Math.round(igst);
				totgst = igst;
			}
			console.log('Total Chargeable Pay' + total_charge);

			if (total_charge < min_amount) {
				total_charge = min_amount;
			}

			var subtototal = parseFloat(total_charge).toFixed(2);
			$('#sub_total').val(subtototal);
		
			console.log('Gst:::' + totgst);
			var grand_total = parseFloat(total_charge) + parseFloat(totgst);
			$('#grand_total').val(grand_total.toFixed(2));
			$('#minimum_amount').val(min_amount);
			console.log('grand_total' + grand_total);
			
		} else {
			return false;
		}
	}
	
	
	// this function is useing for getting non document charges  on behalf of chargeble weight
	function calulateTotal_chargeble() 
	{
	var sender_city = $('#sender_city').val();
	var receiver_city = $('#reciever_city').val();
	var mode_dispatch = $('.mode_dispatch').val();
	var customer_name = $('#customer_account_id').val();
	var actual_weight = (parseFloat($('#actual_weight').val()) > 0) ? $('#actual_weight').val() : 0;
	var valumetric_weight = parseFloat($('#valumetric_weight').val()) > 0 ? $('#valumetric_weight').val() : 0;
	var rate_type = $('.rate_type').val();
	var no_of_pack = parseFloat($('#no_of_pack').val()) > 0 ? $('#no_of_pack').val() : 0;


	//alert(mode_dispatch);

	if ((((actual_weight > 0 || valumetric_weight > 0 ) && rate_type == 'weight') || (rate_type == 'no_of_pack' && no_of_pack)) && customer_name != null && customer_name != '' && sender_city != '' && receiver_city != '' && receiver_city != 'undefined' && mode_dispatch != '') {

		//alert('step2');

		$.ajax({
			type: 'POST',
			dataType: "json",
			url: 'Admin_shipment_manager/getRateMasterDetails',
			data: 'customer_name=' + customer_name + '&sender_city=' + sender_city + '&receiver_city=' + receiver_city + '&mode_dispatch=' + mode_dispatch + '&rate_type=' + rate_type + '&no_of_pack=' + no_of_pack,
			success: function (data) {


				var dod_doac = data.rate_master !== undefined && parseFloat(data.rate_master.dod_doac) > 0 ? parseFloat(data.rate_master.dod_doac) : 0;
				var weight_range = data.rate_master !== undefined && parseFloat(data.rate_master.weight_range) > 0 ? parseFloat(data.rate_master.weight_range) : 0;
				var packing = data.rate_master !== undefined && parseFloat(data.rate_master.packing) > 0 ? parseFloat(data.rate_master.packing) : 0;
				var handling = data.rate_master !== undefined && parseFloat(data.rate_master.handling) > 0 ? parseFloat(data.rate_master.handling) : 0;
				var fov = data.rate_master !== undefined && parseFloat(data.rate_master.fov) > 0 ? parseFloat(data.rate_master.fov) : 0;
				var min_fov = data.rate_master !== undefined && parseFloat(data.rate_master.min_fov) > 0 ? parseFloat(data.rate_master.min_fov) : 0;
				var fuel_charges = data.rate_master !== undefined && parseFloat(data.rate_master.fuel_charges) > 0 ? parseFloat(data.rate_master.fuel_charges) : 0;
				var rate = data.rate_master !== undefined && parseFloat(data.rate_master.rate) > 0 ? parseFloat(data.rate_master.rate) : 0;
				var insuranceValue = parseFloat($('#insurance_value').val()) > 0 ? parseFloat($('#insurance_value').val()) : 0;
				var rate_pack = data.rate_master !== undefined && data.rate_master_pack != undefined && parseFloat(data.rate_master_pack.rate) > 0 ? parseFloat(data.rate_master_pack.rate) : 0;
				var oda = parseFloat($('#oda').val()) > 0 ? parseFloat($('#oda').val()) : 0;
				var cft = data.rate_master !== undefined && parseFloat(data.rate_master.cft) > 0 ? parseFloat(data.rate_master.cft) : 1;
				var actual_weight = parseFloat($('#actual_weight').val()) > 0 ? parseFloat($('#actual_weight').val()) : 0;
				var chargable_weight = actual_weight;
				var eod = data.rate_master !== undefined ? data.rate_master.eod : '';
				var min_freight = data.rate_master.min_freight !== undefined && parseFloat(data.rate_master.min_freight) > 0 ? parseFloat(data.rate_master.min_freight) : 0;
				var min_amount = data.rate_master.min_amount !== undefined && parseFloat(data.rate_master.min_amount) > 0 ? parseFloat(data.rate_master.min_amount) : 0;
				//alert(min_amount);
				var fc_type = data.rate_master !== undefined && data.rate_master.fc_type !== undefined ? data.rate_master.fc_type : '';
				var gst_rate = data.rate_master !== undefined && data.rate_master.gst_rate !== undefined ? data.rate_master.gst_rate : 0;
				var roundoff_type = data.rate_master !== undefined && data.rate_master.roundoff_type !== undefined ? data.rate_master.roundoff_type : '2';
				$("#roundoff_type").val(roundoff_type);

				var cod = data.rate_master !== undefined && data.rate_master.cod !== undefined ? data.rate_master.cod : '';
				var edd = data.rate_master !== undefined && data.rate_master.edd !== undefined ? data.rate_master.edd : '';
				var to_pay_charges = data.rate_master !== undefined && data.rate_master.to_pay_charges !== undefined ? data.rate_master.to_pay_charges : '';
				//$("#roundoff_type").val(cod);
				if (edd != '') {
					var now = new Date();
					now.setDate(now.getDate() + parseInt(edd));

					var month = now.getMonth() + 1;
					var day = now.getDate();
					var year = now.getFullYear();
					var formatedDate = day + "-" + month + "-" + year;
					$("#delivery_date").val(formatedDate);
				}

				if (to_pay_charges != '') {
					$("#to_pat").val(to_pay_charges);
				}
				if (cod != '') {
					$("#cod").val(cod);
				}

				//var chargable_weight;
				$('#eod').val(eod);
		
				//console.log('ac Weight' + chargable_weight);
				if (parseInt(valumetric_weight) > parseInt(chargable_weight)) {
					chargable_weight = valumetric_weight;
				}
			
				if (parseInt(weight_range) > parseInt(chargable_weight)) {
					chargable_weight = weight_range;
				}

				if (fov > 0 && insuranceValue > 0) {
					fov = ((fov * insuranceValue) / 100).toFixed(2);
					// $('#fov').val(fov);
					if (min_fov > fov) {
						$('#fov').val(min_fov);
					} else {
						$('#fov').val(fov);
					}

				}
				//  $('#chargable_weight').val(chargable_weight);
				$('#rate').val(rate);
				$('#one_cft_kg').val(cft);
				$('#dod_doac').val(dod_doac);
				$('#rate_pack').val(rate_pack);
				$('#gst_rate').val(gst_rate);
				finalRate_chargeble(fuel_charges, min_freight, min_amount, fc_type);
			}
		});
	}
}

	// this function is useing for getting non document charges  on behalf of chargeble weight
	function finalRate_chargeble(fuel_charges, min_freight, min_amount, fc_type) 
	{

	var total_charge = 0;
	var total_pay = 0;
	var total = 0;

	var chargable_weight = parseFloat($('#chargable_weight').val()) > 0 ? parseFloat($('#chargable_weight').val()) : 0;
	var rate = parseFloat($('#rate').val()) > 0 ? parseFloat($('#rate').val()) : 0;
	var dod_doac = parseFloat($('#dod_doac').val()) > 0 ? parseFloat($('#dod_doac').val()) : 0;
	var apmt_delivery = parseFloat($('#apmt_delivery').val()) > 0 ? parseFloat($('#apmt_delivery').val()) : 0;
	var fov = parseFloat($('#fov').val()) > 0 ? parseFloat($('#fov').val()) : 0;
	var gst_charges = parseFloat($('#gst_charges').val()) > 0 ? parseFloat($('#gst_charges').val()) : 0;
	var oda = parseFloat($('#oda').val()) > 0 ? parseFloat($('#oda').val()) : 0;
	var to_pat = parseFloat($('#to_pat').val()) > 0 ? parseFloat($('#to_pat').val()) : 0;
	var rate_type = $('.rate_type').val();
	var no_of_pack = parseFloat($('#no_of_pack').val()) > 0 ? parseFloat($('#no_of_pack').val()) : 0;
	var cod = parseFloat($('#cod').val()) > 0 ? parseFloat($('#cod').val()) : 0;
	var demurrage = parseFloat($('#demurrage').val()) > 0 ? parseFloat($('#demurrage').val()) : 0;
	var other_charges = parseFloat($('#other_charges').val()) > 0 ? parseFloat($('#other_charges').val()) : 0;
	var gst_rate = $("#gst_rate").val() > 0 ? $("#gst_rate").val() : 0;
	var gst = gst_rate / 2;

	if (rate_type == 'weight' || rate_type == 'no_of_pack') {
		if ($('#amount_manual').val() == 1 && $('#frieht').val() > 0) {
			total_pay = $('#frieht').val() ? $('#frieht').val() : 0;
		} else {
			total_pay = (rate_type == 'weight') ? (parseFloat(rate) * parseFloat(chargable_weight)) : $('#rate_pack').val();
			var frieght = total_pay;
			$('#frieht').val(frieght.toFixed(2));
		}

		console.log('Total Pay' + total_pay);
		console.log('chargable Weight' + chargable_weight);

		total = (total_pay + dod_doac + fov + oda + to_pat + apmt_delivery + cod + demurrage + other_charges).toFixed(2);
		$('#amount').val(total);
		if (fuel_charges != 'undefind' && fuel_charges > 0) {
			if (fc_type == 'freight') {

				fuel_charges = parseFloat(($('#frieht').val() * fuel_charges) / 100).toFixed(2);
			} else if (fc_type == 'total') {

				fuel_charges = parseFloat((total * fuel_charges) / 100).toFixed(2);
			} else {
				fuel_charges = 0;
			}
			$('#fuel_charges').val(fuel_charges);
		} else {
			fuel_charges = parseFloat($('#fuel_charges').val() > 0 ? $('#fuel_charges').val() : 0);
		}


		var total_charges = parseFloat(total) + parseFloat(fuel_charges);
		if (min_freight > total_charges || total_charges == '') {
			total_charge = min_freight;
		} else {
			total_charge = Math.round(total) + Math.round(fuel_charges);
		}
		var totgst = 0;

		var sender_gstno = $('#sender_gstno').val();
		var first_two_gst_no = sender_gstno.substring(0, 2);
		if (first_two_gst_no == '27') {
			console.log("gst_charges::::" + gst_charges);
			var igst = 0;
			var sgst = 0;
			var cgst = 0;
			if (gst_charges == 1) {

				var sgst = total_charge * gst / 100;
				var cgst = total_charge * gst / 100;
				$('#sgst').val(sgst.toFixed(2));
				$('#cgst').val(cgst.toFixed(2));
				$('#igst').val(igst.toFixed(2));

			}
			totgst = sgst + cgst;

		} else {
			var igst = 0;
			var sgst = 0;
			var cgst = 0;
			if (gst_charges == 1) {
				igst = total_charge * gst / 100;
				$('#sgst').val(sgst.toFixed(2));
				$('#cgst').val(cgst.toFixed(2));
				$('#igst').val(igst.toFixed(2));
			}
			totgst = igst;
		}
		console.log('Total Chargeable Pay' + total_charge);

		if (total_charge < min_amount) {
			total_charge = min_amount;
		}

		var subtototal = parseFloat(total_charge).toFixed(2);
		$('#sub_total').val(subtototal);

		console.log('Gst:::' + totgst);
		var grand_total = parseFloat(total_charge) + parseFloat(totgst);
		$('#grand_total').val(grand_total.toFixed(2));
		$('#minimum_amount').val(min_amount);
		console.log('grand_total' + grand_total);

	} else {
		return false;
	}
}	

	// this function is use for refresh the chrages when weight is changed in input box 
	function weightChangeEvent() 
	{
		$(".no_of_pack, .per_box_weight, .actual_weight, .length, .breath, .height, .no_of_pack, .one_cft_kg").change(function () {
			var idNo = $(this).attr('data-attr');
			calculateTotalWeight();
			calulate_weight(idNo);

			if ($("input[name='doc_type']:checked").val() == '0') {
				add_doc_rate();
			}

		});

		$("#length_unit1, #length_unit2").change(function () {
			var totalRow = $('table.weight-table tbody').find('tr').length;
			for (var i = 1; i <= totalRow; i++) {
				calculateTotalWeight();
				calulate_weight(i);
			}
		});
	}

	
	// this functin is use for calculating total weight 
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
		//one_cft_kg
		for (var i = 1; i <= totalRow; i++) {
			var noOfPackCurrent = parseFloat(($('#no_of_pack' + i).val() != '') ? $('#no_of_pack' + i).val() : 0);
			var perBoxWeightCurrent = parseFloat(($('#per_box_weight' + i).val() != '') ? $('#per_box_weight' + i).val() : 0);
			
			var currentActualWeight = noOfPackCurrent * perBoxWeightCurrent;
			console.log('currentActualWeight' + currentActualWeight);
			if (currentActualWeight > 0) {
				$('#actual_weight' + i).val(currentActualWeight);
			}
			totalNoOfPack = parseFloat(totalNoOfPack) + parseFloat(($('#no_of_pack' + i).val() != '') ? $('#no_of_pack' + i).val() : 0);
			totalPerBoxWeight = parseFloat(totalPerBoxWeight) + parseFloat(($('#per_box_weight' + i).val() != '') ? $('#per_box_weight' + i).val() : 0);
			totalActualWeight = parseFloat(totalActualWeight) + parseFloat(($('#actual_weight' + i).val() != '') ? $('#actual_weight' + i).val() : 0);
			totalValumetricWeight = parseFloat(totalValumetricWeight) + parseFloat(($('#valumetric_weight' + i).val() != '') ? $('#valumetric_weight' + i).val() : 0);
			// totalValumetricWeight = totalValumetricWeight + ($('#valumetric_weight'+i).val() != '') ? $('#valumetric_weight'+i).val() : 0;
			totalLength = parseFloat(totalLength) + parseFloat(($('#length' + i).val() != '') ? $('#length' + i).val() : 0);
			totalBreath = parseFloat(totalBreath) + parseFloat(($('#breath' + i).val() != '') ? $('#breath' + i).val() : 0);
			totalHeight = parseFloat(totalHeight) + parseFloat(($('#height' + i).val() != '') ? $('#height' + i).val() : 0);
			totalOneCftKg = parseFloat(totalOneCftKg) + parseFloat(($('#one_cft_kg' + i).val() != '') ? $('#one_cft_kg' + parseInt(i)).val() : 0);
		
		}

		if (totalNoOfPack) {
			$('#no_of_pack').val(totalNoOfPack);
		}
		if (totalPerBoxWeight) {
			$('#per_box_weight').val(totalPerBoxWeight);
		}
		if (totalActualWeight) {
			$('#actual_weight').val(Math.ceil(totalActualWeight));
		}
		if (totalValumetricWeight) {
			var roundoff_type = $("#roundoff_type").val();
			// $('#valumetric_weight').val(totalValumetricWeight); ttttttt
			if (roundoff_type == '1') {
				$('#valumetric_weight').val(Math.round(totalValumetricWeight));
			} else {
				$('#valumetric_weight').val(Math.ceil(totalValumetricWeight));
			}

		}
		if (totalLength) {
			$('#length').val(totalLength);
		}
		if (totalBreath) {
			$('#breath').val(totalBreath);

		}
		if (totalHeight) {
			$('#height').val(totalHeight);
		}


	}

	
	// this functin is use for calculating total weight 
	function calulate_weight(idNo) 
	{
		console.log('idNo:::::' + idNo)
		var idNo = (typeof idNo == 'undefined') ? 1 : idNo;
		var length_unit = $("#length_unit").val();

		if (length_unit != 'cm' && length_unit != 'inch') {
			alert('Invalid measuremnet');
		}

		var actual_weight = $("#actual_weight" + idNo).val();
		var length = $("#length" + idNo).val();
		var breath = $("#breath" + idNo).val();
		var height = $("#height" + idNo).val();
		var one_cft_kg = $("#one_cft_kg" + idNo).val();
		if (one_cft_kg == '') {
			one_cft_kg = $("#one_cft_kg").val() ? $("#one_cft_kg").val() : '';
		}

		var no_of_pack = $("#no_of_pack" + idNo).val();
		if (no_of_pack == '') {
			no_of_pack = '';
		}
		console.log('check:::#one_cft_kg' + idNo + '####' + one_cft_kg);
		$("#one_cft_kg" + idNo).val(one_cft_kg);
		var valumetric_weight = '';
		var total_valumetric_weight = '';

		if (length != '' && breath != '' && height != '' && no_of_pack != '' && one_cft_kg != '') {
			console.log('length' + length);
			console.log('breath' + breath);
			console.log('height' + height);
			console.log('no_of_pack' + no_of_pack);
			console.log('one_cft_kg' + one_cft_kg);
			if (length_unit == 'cm') {
				valumetric_weight = ((length * breath * height) / 27000) * one_cft_kg * no_of_pack;
			} else {
				valumetric_weight = ((length * breath * height) / 5000) * no_of_pack;
			}

			console.log('valumetric_weight' + valumetric_weight);
		
			total_valumetric_weight = valumetric_weight.toFixed(2);
			$("#valumetric_weight" + idNo).val(total_valumetric_weight);
			calculateTotalWeight();
			calulateTotal();
		} else {
			$("#valumetric_weight" + idNo).val('');
		}
	}


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
		calulateTotal();

	});
										</script>
										
										