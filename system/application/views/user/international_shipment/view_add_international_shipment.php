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
.compulsory_fields {
  color: #ff0000;
  font-weight: bolder;
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
input,textarea { 
    text-transform: uppercase;
}
::-webkit-input-placeholder { /* WebKit browsers */
    text-transform: none;
}
:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
    text-transform: none;
}
::-moz-placeholder { /* Mozilla Firefox 19+ */
    text-transform: none;
}
:-ms-input-placeholder { /* Internet Explorer 10+ */
    text-transform: none;
}
::placeholder { /* Recent browsers */
    text-transform: none;
}
  </style>   
  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>-->
  <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script> -->
    <!-- START: Body-->
    <body id="main-container" class="default">
  
        <!-- END: Main Menu-->
   
    <?php include(dirname(__FILE__).'/../admin_shared/admin_sidebar.php'); ?>
        <!-- END: Main Menu-->
    
        <!-- START: Main Content-->
        <main>
        	<div class="container-fluid site-width">
                           
                    <!-- START: Card Data-->
                <form role="form" name="generatePOD" id="generatePOD" action="User_panel/insert_international_shipment" method="post" >
                <div class="row">
                    <div class="col-md-7 col-sm-12 mt-3">
                        <div class="card">
                            <div class="card-header">                               
                                <h4 class="card-title">Shipment Info</h4>       
                                 <span style="float: right;"><a href="User_panel/list_shipment/International" class="btn btn-primary">View International Shipment</a></span>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                	 <?php if(empty($gst_details) ){
                                 	echo "<div class='alert alert-danger'>Please Update GST</div>";
                                 }?>
                                	 <?php if($this->session->flashdata('notify') != '') {?>
  <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored"><?php echo $this->session->flashdata('notify'); ?></div>
  <?php  unset($_SESSION['class']); unset($_SESSION['notify']); } ?>  
                                    <div class="row">                                           
                                        <div class="col-12">                                           
                                                <div class="form-group row">
                                                	<label  class="col-sm-2 col-form-label">Date<span class="compulsory_fields">*</span></label>
													<div class="col-sm-4">
													<input type="date" name="booking_date" value="<?php echo date('Y-m-d');?>" id="booking_date" class="form-control">
													</div>
													<label class="col-sm-2 col-form-label">Type<span class="compulsory_fields">*</span></label>
													<div class="custom-control custom-radio custom-control-inline">
														<input type="radio"  name="export_import_type" class="custom-control-input" id="export_import_type1" value="Export">
														<label class="custom-control-label checkbox-primary outline" for="export_import_type1">Export</label>
													</div>	
													<div class="custom-control custom-radio custom-control-inline">
														<input type="radio" name="export_import_type" class="custom-control-input" id="export_import_type2" value="Import" >
														<label class="custom-control-label checkbox-primary outline" for="export_import_type2">Import</label>
													</div>
												</div>
												<input type="hidden" name="courier_company" id="courier_company" value='37'>
												<div class="form-group row"> 
														
														<label  class="col-sm-2 col-form-label">Airway No<span class="compulsory_fields">*</span></label>
														<div class="col-sm-4">
															<input type="text" name="awn" id="awn" class="form-control" value="<?php //echo $bid; ?>">
														</div>
													
													<input type="hidden" name="forwording_no" id="forwording_no" >
											<input type="hidden" name="forworder_name" id="forworder_name" value="SELF">
														
                                                </div>
                                                <!-- <div class="form-group row">
                                                   <label class="col-sm-2 col-form-label">Mode</label>
													<div class="col-sm-4">
															<input type="text" name="mode_dispatch"  class="form-control" value="Air" readonly>
													</div> -->
													 <!--<label  class="col-sm-2 col-form-label">Airway No</label>
													<div class="col-sm-4">
														<input type="text" name="awn" id="awn" class="form-control my-colorpicker1" value="<?php echo $bid; ?>">
													</div>
                                                </div> -->
                                                <div class="form-group row"> 
                                                	<label class="col-sm-2 col-form-label">Product<span class="compulsory_fields">*</span></label>
													<div class="col-sm-4">
														<select class="form-control" name="doc_type" id="doc_typee">
															<option value="">-Select-</option>
															<option value="1">Non-Doc</option>
															<option value="0">Doc</option>
														</select>
													</div>  
													<!--<label class="col-sm-2 col-form-label">Currency<span class="compulsory_fields">*</span></label>
													<div class="col-sm-4">
														<select class="form-control" required name="currency" id="currency" >
																<option value="">-Select Currency-</option>
				        										<?php	
																if(!empty($currency_list))	
																{
																	foreach ($currency_list as $cc)
																	{
																		?>
																		<option value='<?php echo $cc['id']; ?>'><?php echo $cc['code']; ?></option>
																		<?php 
																	}
																}
																?>
															</select>	
													</div>  -->													
                                                </div> 
                                                <div class="form-group row" id="div_inv_row" style="display: none;" >
                                                	<label class="col-sm-2 col-form-label">INV No.</label>
													<div class="col-sm-2">
													    
														<input type="text" name="invoice_no" id="invoice_no" class="form-control">
													</div>	
													
													<label class="col-sm-1 col-form-label">Inv. Value<span class="compulsory_fields">*</span></label>
													<div class="col-sm-2">
													    <select class="form-control" required name="currency" id="currency" >
													        <option value="106">INR</option>
				        										<?php	
																if(!empty($currency_list))	
																{
																	foreach ($currency_list as $cc)
																	{
																		?>
																		<option value='<?php echo $cc['id']; ?>'><?php echo $cc['code']; ?></option>
																		<?php 
																	}
																}
																?>
														</select>	
														
													</div>
													<div class="col-sm-2">
														<input type="number" name="invoice_value" id="invoice_value" class="form-control">
													</div>
													<label class="col-sm-1 col-form-label">Eway No</label>
													<div class="col-sm-2">
														<input type="text" name="eway_no" id="eway_no" class="form-control">
													</div>
												</div>
												<!-- <div class="form-group row">
													<label class="col-sm-2 col-form-label">Freight</label>
													<div class="col-sm-4">
														<input type="text" name="freight_val" id="freight_val" class="form-control">
													</div>
												</div> -->
                                                <div class="form-group row">
                                                    <label  class="col-sm-2 col-form-label">Desc.</label>
													<div class="col-sm-4">
														<textarea name="special_instruction" class="form-control my-colorpicker1"></textarea>
													</div>
													<label class="col-sm-2 col-form-label">Bill Type<span class="compulsory_fields">*</span></label>
													<div class="col-sm-4">
														<select class="form-control" name="dispatch_details" id="dispatch_details">
																<option value="">-Select-</option>
																<option value="Credit">Credit</option>
																<option value="Cash">Cash</option>
                                                                                                                                <option value="COD">COD</option>
                                                                                                                                <option value="ToPay">ToPay</option>
														</select>											
													</div>													
                                                </div>
                                             <!-- <div class="form-group row">
                                             	
                                             	<label class="col-sm-2 col-form-label">Bill Type</label>
												<div class="col-sm-4">
													<select class="form-control" name="dispatch_details" id="dispatch_details">
															<option value="">-Select-</option>
															<option value="Credit">Credit</option>
															<option value="Cash">Cash</option>
													</select>											
												</div>
                                             </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div class="card-header">                               
                                <h4 class="card-title"><span id="Consigner_div">Consigner</span> Detail</h4>                                
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
														<option value="<?php echo $rows['customer_id']; ?>">
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
												<label class="col-sm-2 col-form-label" id="credit_div_label">Name<span class="compulsory_fields">*</span></label>
												<div class="col-sm-4">
													<input type="text" name="sender_name" readonly id="sender_name" class="form-control my-colorpicker1">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-sm-2 col-form-label">Address</label>
												<div class="col-sm-4">
													<textarea name="sender_address" readonly id="sender_address" class="form-control"></textarea>		
												</div>
												<label class="col-sm-2 col-form-label">Pincode<span class="compulsory_fields">*</span></label>
												<div class="col-sm-4">
													<input type="text" name="sender_pincode" readonly id="sender_pincode" class="form-control my-colorpicker1">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-sm-2 col-form-label">State<span class="compulsory_fields">*</span></label>
												<div class="col-sm-4">
													<select class="form-control" id="sender_state" name="sender_state">
													<option value="">Select State</option>													
														<?php 
															if(count($states)) {
																foreach ($states as $st) {
																	?>
														<option value="<?php echo $st['id']; ?>">
															<?php echo $st['state']; ?> 
														</option>
														<?php }
															} else {
															echo "<p>No Data Found</p>";
															}
															?>
													</select>
												</div>
												<label class="col-sm-2 col-form-label">City<span class="compulsory_fields">*</span></label>
												<div class="col-sm-4">	
												    
													<select class="form-control" id="sender_city" name="sender_city">
														<option value="">Select City</option>
														<?php
															if (count($cities)) {
																foreach ($cities as $rows) {
																	?>
														<option value="<?php echo $rows['id']; ?>">
															<?php echo $rows['city']; ?> 
														</option>
														<?php }
															} else {
															echo "<p>No Data Found</p>";
															}
															?>
													</select>
												</div>												
											</div>
											<div class="form-group row">
													<label class="col-sm-2 col-form-label">ContactNo.</label>
													<div class="col-sm-4">
													<input type="text" name="sender_contactno" readonly id="sender_contactno" class="form-control my-colorpicker1">
													</div>
											
													
											</div>
											<div class="form-group row">
												<label class="col-sm-2 col-form-label">TypeOfDoc<span class="compulsory_fields">*</span></label>
													<div class="col-sm-4">
														<select name="type_of_doc" class="form-control">
															<option value="GSTIN">GSTIN</option>
															<option value="GSTIN(Govt.)">GSTIN(Govt.)</option>
															<option value="GSTIN(Diplomats)">GSTIN(Diplomats)</option>
															<option value="PAN">PAN</option>
															<option value="TAN">TAN</option>
															<option value="Passport">Passport</option>
															<option value="Aadhaar">Aadhaar</option>
															<option value="Voter Id">Voter Id</option>
															<option value="IEC">IEC</option></select>
														</select>
													</div>
													<div class="col-sm-4">
														<input type="text" name="sender_gstno" readonly id="sender_gstno" class="form-control">
													</div>
											</div>

										</div>
									</div>	
								</div>
							</div>
							<div class="card-header" style="margin-top: -25px;">                               
                                <h6 class="card-title"><span id="Consignee_div">Consignee</span> Detail</h6>                                
                            </div>
                            
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">                                           
                                        <div class="col-12">                                           
                                                <div class="form-group row">
                                                   <label class="col-sm-2 col-form-label">Name<span class="compulsory_fields">*</span></label>
													<div class="col-sm-4">
														<input type="text" name="reciever_name" id="reciever" class="form-control my-colorpicker1" required>
													</div>
													<label   class="col-sm-2 col-form-label">Company</label>
													<div class="col-sm-4">
														<input type="text" class="form-control" name="contactperson_name"  />
													</div>
											</div>
											<div class="form-group row">
												<label class="col-sm-2 col-form-label">Address</label>
												<div class="col-sm-4">
													<textarea name="reciever_address" id="reciever_address" class="form-control my-colorpicker1"></textarea>	
												</div>
												 <label class="col-sm-2 col-form-label">City<span class="compulsory_fields">*</span></label>
												<div class="col-sm-4">
													<input type="text" class="form-control" name="reciever_city"  id="reciever_city"  />
												</div>
											</div>
											 <div class="form-group row">
												<label class="col-sm-2 col-form-label">Zipcode<span class="compulsory_fields">*</span></label>
												<div class="col-sm-4">
													<input type="text" class="form-control" name="reciever_zipcode"  id="reciever_zipcode"  />
												</div>
												<label class="col-sm-2 col-form-label">Country<span class="compulsory_fields">*</span></label>
												<div class="col-sm-4">
													<select class="form-control" id="reciever_country_id" name="reciever_country_id" class="reciever_country_id">
														<option value="">Select Country</option>
													</select>
													<input type="hidden" name="reciever_zone_id" id="reciever_zone_id" value="">
												</div>
													
											</div>
											<div class="form-group row">
												<label class="col-sm-2 col-form-label">ContactNo.</label>
												<div class="col-sm-4">
													<input type="text" class="form-control" name="reciever_contact"/>
												</div>
												<label class="col-sm-2 col-form-label">Email Id<span class="compulsory_fields">*</span></label>
												<div class="col-sm-4">
													<input type="text" class="form-control" name="reciever_email" id="reciever_email"/>
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
														<input type="number" name="frieht" class="form-control" value="0" id="frieht"/>
													</div>													
													<label class="col-sm-3 col-form-label">Transport</label>
													<div class="col-sm-3">
														<input type="number" name="transportation_charges" class="form-control" value="0" id="transportation_charges">
													</div>
                                                </div>
                                                <div class="form-group row">
                                                    <label   class="col-sm-3 col-form-label">Destination</label>
													<div class="col-sm-3">
														<input type="number" name="destination_charges" class="form-control" value="0" id="destination_charges">
													</div>		
													<label class="col-sm-3 col-form-label">Clearance</label>
													<div class="col-sm-3">
														<input type="number" name="clearance_charges" class="form-control" value="0" id="clearance_charges">
													</div>
                                                </div>
                                                 <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">ESS</label>
													<div class="col-sm-3">
														<input type="number" name="ecs" class="form-control" value="0" id="ecs">
													</div>
													<label class="col-sm-3 col-form-label">OtherCh.</label>
													<div class="col-sm-3">
														<input type="number" name="other_charges" class="form-control" value="0" id="other_charges">
													</div>					
                                                </div>                    
                                                <div class="form-group row">
                                                   <label   class="col-sm-3 col-form-label">Total</label>
													<div class="col-sm-3">
														<input type="number" readonly name="amount" class="form-control" value="0" id="amount"/>
													</div>
													<label  class="col-sm-3 col-form-label">Fuel Surcharge</label>
													<div class="col-sm-3">
														<input type="number" readonly="readonly" class="form-control" name="fuel_subcharges" value="0" id="fuel_charges">
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
													<label class="col-sm-3 col-form-label">Pay By<span class="compulsory_fields">*</span></label>
													<div class="col-sm-6">
														<select class="form-control" name="payment_method" id="payment_method" required>
																<option value="">-Select-</option>
																<?php foreach($payment_method as $pm){ ?>
																<option value="<?php echo $pm['id'];?>" ><?php echo $pm['method'];?></option>
																<?php } ?>
														</select>											
													</div>													
                                                </div>
												<div class="form-group row" id="Refno" style="display:none;">
													<label class="col-sm-3 col-form-label">Ref No</label>
													<div class="col-sm-6">
														<input type="text" name="ref_no" class="form-control" />											
													</div>													
                                                </div>
                                               <div class="form-group row">
                                                    <label  class="col-sm-3 col-form-label">Sub Total</label>
			                                        <div class="col-sm-6">
														<input type="number" name="sub_total" readonly class="form-control" value="0" id="sub_total"/>
													</div>
												</div>
												<div class="form-group row">
													 <label class="col-sm-3 col-form-label">CGST Tax</label>
				                                     <div class="col-sm-6">
				                                       <div class="input-group mb-3">
				                                           <input class="form-control" type="number" id="cgst" step="any" value="0" name="cgst" readonly>
				                                        </div>
				                                     </div>
                                                </div>
                                                <div class="form-group row">
													 <label class="col-sm-3 col-form-label">SGST Tax</label>
				                                     <div class="col-sm-6">
				                                       <div class="input-group mb-3">
				                                            <input class="form-control" type="number" id="sgst" step="any" value="0" name="sgst" readonly>
				                                        </div>
				                                     </div>
                                                </div>
												<div class="form-group row">
													 <label class="col-sm-3 col-form-label">IGST Tax</label>
				                                     <div class="col-sm-6">
				                                       <div class="input-group mb-3">
				                                            <input  class="form-control" type="number" value="0" id="igst" step="any" name="igst" readonly>
				                                        </div>
				                                     </div>
                                                </div>
                                                <div class="form-group row">
                                                   <label class="col-sm-3 col-form-label">Grand Total</label>
													<div class="col-sm-6">
														<div class="input-group mb-3">
			                                        		<input type="text" class="form-control" readonly name="grand_total" value="0"  id="grand_total"/>
														</div>
													</div>
                                                </div>
                                                <div class="form-group row" >
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
												<table class="weight-table">
													<thead>
														<tr><input type="hidden" class="form-control" name="length_unit" id="length_unit" class="custom-control-input" value="cm">
															<th>PKT</th>
															<th>Per Box Weight</th>
															<th>Actual Weight</th>
															<th>Chargeable Weight</th>
															<th class="volumetric_weight_th">Valumetric Weight</th>
				                                            <th class="length_th">L</th>
				                                            <th class="breath_th">B</th>
				                                            <th class="height_th">H</th>
															<!-- <th>1CFT(kg)</th> -->
														</tr>
													<thead>
													<tbody>
														<tr>
															<td><input type="text" name="no_pack_detail[]" class="form-control no_of_pack"  data-attr="1" id="no_of_pack1" required="required"></td>
															<td><input type="text" name="per_box_weight_detail[]" class="form-control per_box_weight" data-attr="1" id="per_box_weight1" required="required"></td>
															<td><input type="text" name="actual_weight_detail[]" readonly class="form-control actual_weight"  data-attr="1" id="actual_weight1"></td>
															<td><input type="text" name="chargable_weight_detail[]" class="form-control chargable_weight" id="chargable_weight1"/></td>
															<td class="volumetic_weight_td"><input type="text" name="valumetric_weight_detail[]" readonly class="form-control my-colorpicker1 valumetric_weight" data-attr="1" id="valumetric_weight1"></td>
															<td class="length_td"><input type="text" name="length_detail[]" class="form-control length" data-attr="1" id="length1" ></td>
															<td class="breath_td"><input type="text" name="breath_detail[]" class="form-control breath" data-attr="1" id="breath1" ></td>
															<td class="height_td"><input type="text" name="height_detail[]" class="form-control height" data-attr="1" id="height1" ></td>
															<!-- <td><input type="text" name="one_cft_kg_detail[]" readonly class="form-control my-colorpicker1 one_cft_kg" data-attr="1" id="one_cft_kg1"></td> -->
														
														</tr>
													</tbody>
													<tfoot>
													<a href="javascript:void(0)" class="btn btn-sm btn-primary add-weight-row"><i class="icon-plus"></i></a>&nbsp;<a href="javascript:void(0)" class="btn btn-sm btn-danger remove-weight-row"><i class="icon-trash"></i></a>
													</tfoot>
												</table>
												 <table>
													<tr>
														<th><input type="text" name="no_of_pack" readonly="readonly" class="form-control my-colorpicker1 no_of_pack" id="no_of_pack" required="required"></th>
														<th><input type="text" name="per_box_weight" readonly="readonly" class="form-control per_box_weight" id="per_box_weight" required="required"></th>
														<th><input type="text" name="actual_weight" readonly="readonly" class="form-control actual_weight" id="actual_weight"></th>

														<th><input type="text" name="chargable_weight" class="form-control chargable_weight" id="chargable_weight"/></th>

														<th class="volumetic_weight_td"><input type="text" name="valumetric_weight" readonly="readonly" class="form-control my-colorpicker1 valumetric_weight" id="valumetric_weight"></th>

														<th class="length_td"><input type="text" name="length" readonly="readonly" class="form-control length"  id="length"></th>
														<th class="breath_td"><input type="text" name="breath" readonly="readonly" class="form-control breath"  id="breath"></th>
														<th class="height_td"><input type="text" name="height" readonly="readonly" class="form-control height" id="height"></th>
														<!-- <td><input type="text" name="one_cft_kg" readonly="readonly" class="form-control my-colorpicker1 one_cft_kg" id="one_cft_kg"></td> -->
													</tr>
												</table>
										</div>
                                    </div>
                                    <!-- <div class="row">                                           -->
                                    <!--    <div class="col-12"> -->
                                    <!--    	<div class="form-group row">-->
	                                   <!--     	<button type="submit"  class="btn btn-primary">Submit</button> &nbsp;-->
	                                   <!--     	<button type="button" onclick="return open_new_page()" class="btn btn-primary">New</button>-->
	                                   <!--     </div>-->
                                    <!--    </div>-->
                                    <!--</div>-->
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
<script>
	function open_new_page(){
		location.reload();
	}
	$(document).ready(function () {
	    $(function() {
			$('input:radio[name="export_import_type"]').change(function() {				
				if ($(this).val() == 'Export') {
				   $("#Consigner_div").html("Consigner");
				   $("#Consignee_div").html("Consignee");
				} else {
					$("#Consigner_div").html("Consignee");
					$("#Consignee_div").html("Consigner");
				} 
			});
		});
		$(function() {
		  $("form[name='generatePOD']").validate({  
		    rules: {    
		      booking_date: "required", 
		      forworder_name: "required",
		      courier_company:"required",
		      export_import_type:"required",
		      doc_type: "required", 
		      dispatch_details: "required",
		      sender_pincode:"required",
		      reciever_name:"required",
		      reciever_country_id: "required", 
		      frieht: "required",
		      transportation_charges:"required",
		      destination_charges:"required",
			  clearance_charges:"required",
		      ecs:"required",
		      other_charges:"required",
		      amount:"required",
		      sub_total:"required",
		      igst:"required",
		      grand_total:"required",
		      sender_gstno:"required",
		    },
		    // Specify validation error messages
		    messages: {
		    	 booking_date: "Required", 
			      forworder_name: "Required",
			      courier_company:"Required",
			      export_import_type:"Required",
			      doc_type: "Required", 
			      dispatch_details: "Required",
			      sender_pincode:"Required",
			      reciever_name:"Required",
			      reciever_country_id: "Required", 
			      frieht: "Required",
			      transportation_charges:"Required",
			      destination_charges:"Required",
				  clearance_charges:"Required",
			      ecs:"Required",
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
});
	$("#reciever_email").on('blur', function () 
	{
	  document.getElementById("no_of_pack1").focus();	
	});
	$("#no_of_pack").on('blur', function () 
	{
	  document.getElementById("frieht").focus();	
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
				success: function (data) {					
					$('#sender_city').html(data);					
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
					$('#sender_state').html(data);					
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
				$("#Refno").show();
				
			//	$("#credit_div").hide();
			//	$("#credit_div_label").hide();
			
				$("#payby").show();
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
				url: 'User_panel/getsenderdetails',
				data: 'customer_name=' + customer_name,
				success: function (data) {
					$("#sender_name").val(data.user.customer_name);
					$("#sender_address").val(data.user.address);
					$("#sender_pincode").val(data.user.pincode);
					$("#sender_contactno").val(data.user.phone);
					$("#sender_gstno").val(data.user.gstno);
					$("#gst_charges").val(data.user.gst_charges);
					$("#sender_city").val(data.user.city);
					$("#sender_state").val(data.user.state);					
					$("#customer_account_id").val(customer_name);	
					 document.getElementById("reciever").focus();				
				}
			});
		}
	});
	$("#forwording_no").blur(function () {
        var forwording_no = $(this).val();
        if (forwording_no != null || forwording_no != '') {
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: 'User_panel/check_duplicate_forwording_no',
                data: 'forwording_no=' + forwording_no,
                success: function (data) {
                    if(data.msg!=""){  
                        	 $('#forwording_no').focus();
                    		 $('#forwording_no').val("");
                             alert(data.msg); 
                    }else{
                    }
                    
                }
            });
        }
    });


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

		$(".no_of_pack, .per_box_weight, .actual_weight, .length, .breath, .height, .one_cft_kg").change(function () {
			
			var idNo = $(this).attr('data-attr');			
				calculateTotalWeight();
				//calulate_charges(idNo);
				//add_doc_rate();
			
		});
		$("#frieht,#transportation_charges,#destination_charges,#clearance_charges,#ecs,#other_charges").change(function () {
			
			var frieht = parseFloat(($('#frieht').val() != '') ? $('#frieht').val() : 0);
			var transportation_charges 	 = parseFloat(($('#transportation_charges').val() != '') ? $('#transportation_charges').val() : 0);
			var destination_charges = parseFloat(($('#destination_charges').val() != '') ? $('#destination_charges').val() : 0);
			var clearance_charges  = parseFloat(($('#clearance_charges').val() != '') ? $('#clearance_charges').val() : 0);
			var ecs		 = parseFloat(($('#ecs').val() != '') ? $('#ecs').val() : 0);
			var other_charges 	 = parseFloat(($('#other_charges').val() != '') ? $('#other_charges').val() : 0);

			var totalAmount = (frieht + transportation_charges + destination_charges + clearance_charges +ecs + other_charges);
			$('#amount').val(totalAmount);

			var courier_id = parseFloat(($('#courier_company').val() != '') ? $('#courier_company').val() : 0);
			var booking_date =$('#booking_date').val();
			var type_export_import = $('input[name="export_import_type"]:checked').val();
			var customer_id   = $('#customer_account_id').val();
			var dispatch_details =$('#dispatch_details').val();

			$.ajax({
					type: 'POST',
					url: 'User_panel/getFuelcharges',
					data: 'courier_id='+courier_id +'&sub_amount='+totalAmount+'&booking_date='+booking_date+'&type_export_import='+type_export_import+'&customer_id='+customer_id+'&dispatch_details='+dispatch_details,
					dataType: "json",
					success: function(data) {					
						$('#fuel_charges').val(data.final_fuel_charges);	
						$('#sub_total').val(data.sub_total);
						$('#cgst').val(data.cgst);
						$('#sgst').val(data.sgst);
						$('#igst').val(data.igst);
						$('#grand_total').val(data.grand_total);		
				
					}
				});		

		});
		$("#amount").blur(function () {
			var courier_id = parseFloat(($('#courier_company').val() != '') ? $('#courier_company').val() : 0);			
			var amount = parseFloat(($('#amount').val() != '') ? $('#amount').val() : 0);
				var booking_date =$('#booking_date').val();
				var type_export_import = $('input[name="export_import_type"]:checked').val();
				var customer_id   = $('#customer_account_id').val();
			var dispatch_details =$('#dispatch_details').val();
			
				$.ajax({
					type: 'POST',
					url: 'Admin_international_shipment_manager/getFuelcharges',
					data: 'courier_id='+courier_id +'&sub_amount='+amount+'&booking_date='+booking_date+'&type_export_import='+type_export_import+'&customer_id='+customer_id+'&dispatch_details='+dispatch_details,
					dataType: "json",
					success: function(data) {					
						$('#fuel_charges').val(data.final_fuel_charges);			
				
					}
				});		
			
		});
		
		$("#fuel_charges").blur(function () {			
			var amount = parseFloat(($('#amount').val() != '') ? $('#amount').val() : 0);	
			var fuel_charges = parseFloat(($('#fuel_charges').val() != '') ? $('#fuel_charges').val() : 0);				
			var sub_total =(amount + fuel_charges);		
			var customer_id   = $('#customer_account_id').val();
			var dispatch_details =$('#dispatch_details').val();

			var booking_date =$('#booking_date').val();
			var type_export_import = $('input[name="export_import_type"]:checked').val();

				$.ajax({
					type: 'POST',
					url: 'Admin_international_shipment_manager/getGstCharges',
					data: 'sub_amount='+sub_total+'&booking_date='+booking_date+'&type_export_import='+type_export_import+'&customer_id='+customer_id+'&dispatch_details='+dispatch_details,
					dataType: "json",
					success: function(data) {	
						$('#sub_total').val(sub_total.toFixed(2));				
						$('#cgst').val(data.cgst.toFixed(2));
						$('#sgst').val(data.sgst.toFixed(2));
						$('#igst').val(data.igst.toFixed(2));
						$('#grand_total').val(data.grand_total.toFixed(2));
					}
				});		

		});
			$("#sub_total").blur(function () {			
			var sub_total = parseFloat(($('#sub_total').val() != '') ? $('#sub_total').val() : 0);	
			var booking_date =$('#booking_date').val();
			var type_export_import = $('input[name="export_import_type"]:checked').val();
			var customer_id   = $('#customer_account_id').val();
			var dispatch_details =$('#dispatch_details').val();

				$.ajax({
					type: 'POST',
					url: 'Admin_international_shipment_manager/getGstCharges',
					data: 'sub_amount='+sub_total+'&booking_date='+booking_date+'&type_export_import='+type_export_import+'&customer_id='+customer_id+'&dispatch_details='+dispatch_details,
					dataType: "json",
					success: function(data) {	
						$('#sub_total').val(sub_total.toFixed(2));				
						$('#cgst').val(data.cgst.toFixed(2));
						$('#sgst').val(data.sgst.toFixed(2));
						$('#igst').val(data.igst.toFixed(2));
						$('#grand_total').val(data.grand_total.toFixed(2));
					}
				});		
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
	$("#reciever_country_id").blur(function () {
		var zone_id = $("#reciever_country_id option:selected").attr('data-id');
		$('#reciever_zone_id').val(zone_id);
	});
	$("#courier_company").change(function () {
			var courier_company_name = $("#courier_company option:selected").attr('data-id');
			$('#forworder_name').val(courier_company_name);
			
			var courier_id = parseFloat(($('#courier_company').val() != '') ? $('#courier_company').val() : 0);
			var booking_date =$('#booking_date').val();
		
			$.ajax({
					type: 'POST',
					url: 'Admin_international_shipment_manager/available_Fuelcharges',
					data: 'courier_id='+courier_id+'&booking_date='+booking_date,
					dataType: "json",
					success: function(data) {	
					    if(data.fuel_charges=="0")
					    {
								alert("Please entered fuel Percentage");
					    }
				
					}
			});	
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
	
		for (var i = 1; i <= totalRow; i++) {
			var noOfPackCurrent = parseFloat(($('#no_of_pack' + i).val() != '') ? $('#no_of_pack' + i).val() : 0);
			var perBoxWeightCurrent = parseFloat(($('#per_box_weight' + i).val() != '') ? $('#per_box_weight' + i).val() : 0);
			
			var currentActualWeight = noOfPackCurrent * perBoxWeightCurrent;
			if (currentActualWeight > 0) {
				$('#actual_weight' + i).val(currentActualWeight.toFixed(2));
				$("#chargable_weight" + i).val(currentActualWeight.toFixed(2));
			}

			var length = $("#length" + i).val();
			var breath = $("#breath" + i).val();
			var height = $("#height" + i).val();
			if (length != '' && breath != '' && height != '' && noOfPackCurrent != '') 
			{		
					valumetric_weight = ((length * breath * height) / 5000) * noOfPackCurrent;	
					//console.log('valumetric_weight' + valumetric_weight);	

					total_valumetric_weight = valumetric_weight.toFixed(2);

					$("#valumetric_weight" + i).val(total_valumetric_weight);

					if(total_valumetric_weight > currentActualWeight)
					{
						$("#chargable_weight" + i).val(total_valumetric_weight);
					}else{
						$("#chargable_weight" + i).val(currentActualWeight);
					}

					//calculateTotalWeight();
					//calulateTotal();
			} else {
					$("#valumetric_weight" + i).val('');
			}

			totalNoOfPack = parseFloat(totalNoOfPack) + parseFloat(($('#no_of_pack' + i).val() != '') ? $('#no_of_pack' + i).val() : 0);
			totalPerBoxWeight = parseFloat(totalPerBoxWeight) + parseFloat(($('#per_box_weight' + i).val() != '') ? $('#per_box_weight' + i).val() : 0);
			totalActualWeight = parseFloat(totalActualWeight) + parseFloat(($('#actual_weight' + i).val() != '') ? $('#actual_weight' + i).val() : 0);
			totalValumetricWeight = parseFloat(totalValumetricWeight) + parseFloat(($('#valumetric_weight' + i).val() != '') ? $('#valumetric_weight' + i).val() : 0);

			if(totalValumetricWeight > totalActualWeight)
			{
				$("#chargable_weight").val(totalValumetricWeight.toFixed(2));
			}else{
				$("#chargable_weight").val(totalActualWeight.toFixed(2));
			}

			totalLength = parseFloat(totalLength) + parseFloat(($('#length' + i).val() != '') ? $('#length' + i).val() : 0);
			totalBreath = parseFloat(totalBreath) + parseFloat(($('#breath' + i).val() != '') ? $('#breath' + i).val() : 0);
			totalHeight = parseFloat(totalHeight) + parseFloat(($('#height' + i).val() != '') ? $('#height' + i).val() : 0);
		}
		if (totalNoOfPack) {
			$('#no_of_pack').val(totalNoOfPack);
		}
		if (totalPerBoxWeight) {
			$('#per_box_weight').val(totalPerBoxWeight.toFixed(2));
		}
		if (totalActualWeight) {
			$('#actual_weight').val(totalActualWeight.toFixed(2));
		}
		if (totalValumetricWeight) {
			var roundoff_type = $("#roundoff_type").val();
			// $('#valumetric_weight').val(totalValumetricWeight); ttttttt
			if (roundoff_type == '1') {
				$('#valumetric_weight').val(totalValumetricWeight.toFixed(2));
			} else {
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
		var weight = $("#per_box_weight").val();
		var qty = $("#no_of_pack").val();
		var doc = $("#doc_typee").val();
		//var export_import_type = $("#export_import_type").val();
		var type_export_import = $('input[name="export_import_type"]:checked').val();
		var courier_company_id = $("#courier_company").val();
		var reciever_country_id = $("#reciever_country_id").val();
		var reciever_zone_id = $("#reciever_zone_id").val();
		var customer_id = $('#customer_account_id').val();			
		var customer_name = $('#customer_account_id').val();
		var dispatch_details =$('#dispatch_details').val();
		var actual_weight = (parseFloat($('#actual_weight').val()) > 0) ? $('#actual_weight').val() : 0;
		var valumetric_weight = parseFloat($('#valumetric_weight').val()) > 0 ? $('#valumetric_weight').val() : 0;
		var chargable_weight = parseFloat($('#chargable_weight').val()) > 0 ? $('#chargable_weight').val() : 0;		
		var no_of_pack = parseFloat($('#no_of_pack').val()) > 0 ? $('#no_of_pack').val() : 0;
		var booking_date =$('#booking_date').val();
		if(customer_id!=''   && weight != '' && reciever_country_id!='')
		{
			$.ajax({
				type: 'POST',
				url: 'Admin_international_shipment_manager/add_new_rate_international',
				data: 'courier_company_id=' + courier_company_id +'&reciever_country_id=' + reciever_country_id + '&reciever_zone_id=' + reciever_zone_id + '&weight=' + weight + '&customer_id=' + customer_id + '&qty=' + qty + '&doc=' + doc + '&no_of_pack=' + no_of_pack +'&chargable_weight='+chargable_weight+'&type_export_import='+type_export_import+'&booking_date='+booking_date+'&dispatch_details='+dispatch_details,
				dataType: "json",
				success: function (data) {	
					console.log("weight_range====="+data.db_weight);					
					$('#frieht').val(data.frieht);
					$('#transportation_charges').val(0);
					$('#destination_charges').val(0);
					$('#clearance_charges').val(0);
					$('#ecs').val(0);
					$('#ecs').val(0);
					$('#other_charges').val(0);
					$('#amount').val(data.amount);
					$('#fuel_charges').val(data.final_fuel_charges);
					$('#sub_total').val(data.sub_total);
					$('#cgst').val(data.cgst);
					$('#sgst').val(data.sgst);
					$('#igst').val(data.igst);
					$('#grand_total').val(data.grand_total);
				}
			});
		}else{
			$('#frieht').val();
		}



	});
	//this functin is use for gettting documant charges 
	// function add_doc_rate() 
	// {				
		
	// }
		
	// /this is use for getting receiver city info by pincode
	$("#courier_company").on('change', function () 
	{
		var courier_company = $(this).val();
		//if (courier_company != null || pincode != '') {

		
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
					option += '<option value="' + d[i].z_id + '" data-id="'+ d[i].zone_name +'" >' + d[i].country_name + '</option>';
				}
					$('#reciever_country_id').html(option);
				
			
				}
			});

		//}
	});

	$("#customer_account_id").select2();
	$("#reciever_country_id").select2();
	</script>
										
										