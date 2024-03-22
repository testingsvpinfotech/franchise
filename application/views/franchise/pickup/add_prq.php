<?php include(dirname(__FILE__) . '/../admin_shared/admin_header.php'); ?>
<!-- END Head-->
<style>
	.form-control {
		color: black !important;
		border: 1px solid var(--sidebarcolor) !important;
		height: 27px;
		font-size: 10px;
	}

	.cash_mode_div {
		display: none;
	}

	.select2-container--default .select2-selection--single {
		background: lavender !important;
	}

	/*.frmSearch {border: 1px solid #A8D4B1;background-color: #C6F7D0;margin: 2px 0px;padding:40px;border-radius:4px;}*/
	/*#city-list{float:left;list-style:none;margin-top:-3px;padding:0;width:190px;position: absolute;z-index: 7;}*/
	/*#city-list li{padding: 10px; background: #F0F0F0; border-bottom: #BBB9B9 1px solid;}*/
	/*#city-list li:hover{background:#ece3d2;cursor: pointer;}*/
	/*#reciever_city{padding: 10px;border: #A8D4B1 1px solid;border-radius:4px;}*/
	form .error {
		color: #ff0000;
	}

	h6,
	h4 {
		color: #e1383b;
	}

	.compulsory_fields {
		color: #ff0000;
		font-weight: bolder;
	}

	.select2-container *:focus {
		border: 1px solid #3c8dbc !important;
		border-radius: 8px 8px !important;
		background: #ffff8f !important;
	}

	input:focus {
		background-color: #ffff8f !important;
	}

	select:focus {
		background-color: #ffff8f !important;
	}

	textarea:focus {
		background-color: #ffff8f !important;
	}

	.btn:focus {
		color: red;
		background-color: #ffff8f !important;
	}


	input,
	textarea {
		text-transform: uppercase;
	}

	::-webkit-input-placeholder {
		/* WebKit browsers */
		text-transform: none;
	}

	:-moz-placeholder {
		/* Mozilla Firefox 4 to 18 */
		text-transform: none;
	}

	::-moz-placeholder {
		/* Mozilla Firefox 19+ */
		text-transform: none;
	}

	:-ms-input-placeholder {
		/* Internet Explorer 10+ */
		text-transform: none;
	}

	::placeholder {
		/* Recent browsers */
		text-transform: none;
	}

	.form-group {
		margin-bottom: 5px !important;
		margin-top: -7px;
	}
</style>
<!-- START: Body-->

<body id="main-container" class="default">

	<!-- END: Main Menu-->

	<?php include(dirname(__FILE__) . '/../admin_shared/admin_sidebar.php'); ?>
	<!-- END: Main Menu-->

	<!-- START: Main Content-->
	<main>
		<div class="container-fluid site-width">
			<?php // print_r($this->session->all_userdata());
			?>
			<!-- START: Card Data-->
			<form action="<?php echo base_url(); ?>Pickup_Request_Controller/store_prq_for_cs" method="post">
				<div class="row">
					<div class="col-md-3 col-sm-12 mt-3">
						<!-- Shipment Info -->
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Consignor Details</h4>
								<!-- <span style="float: right;"><a href="admin/view-domestic-shipment" class="btn btn-primary">View Domestic Shipment</a></span> -->
							</div>
							<div class="card-content">
								<div class="card-body">
									<div class="row">
										<div class="col-sm-10 form-group ">
											<label>Client Name</label>
											<div class="form-group">
												<input name="pickup_request_id" type="hidden" value="<?php echo $request_id; ?>" class="form-control">
												<select class="form-control" name="customer_id" id="customer_account_id" required>
													<option value="">Select Customer</option>
													<?php
													if (!empty($customers)) { ?>
														<?php foreach ($customers as $rows) : ?>
															<option value="<?php echo $rows['customer_id']; ?>">
																<?php echo $rows['customer_name']; ?>--<?php echo $rows['cid']; ?>
															</option>
														<?php endforeach; ?>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="col-sm-12">
											<label>Pickup Pincode<span class="compulsory_fields">*</span></label>
											<div class="form-group">
												<input type="text" name="pickup_pincode" class="form-control" id="pickup_picode" pattern="[0-9]{6}" maxlength="6" required>
											</div>
										</div>
										<div class="col-sm-12">
											<label>Pickup Location<span class="compulsory_fields">*</span></label>
											<div class="form-group">
												<input type="text" name="pickup_location" class="form-control"required>
											</div>
										</div>

										<div class="col-sm-12">
											<label>Mode<span class="compulsory_fields">*</span></label>
											<div class="form-group">
												<select class="form-control" name="mode_id" required>
													<option value="">-Select Mode-</option>
													<?php
													if (!empty($transfer_mode)) {
														foreach ($transfer_mode as $row) {
													?>
															<option value='<?php echo $row->transfer_mode_id; ?>'><?php echo $row->mode_name; ?></option>
													<?php
														}
													}
													?>

												</select>
											</div>
										</div>

										<div class="col-sm-12">
											<label>Pickup Date & Time<span class="compulsory_fields">*</span></label>
											<div class="form-group">
												<input type="date" name="pickup_date" class="form-control" required>
											</div>
										</div>
										<div class="col-sm-12">
											<label>Time <span class="compulsory_fields">*</span></label>
											<div class="form-group">
												<select class="form-control" name="pickup_time" required>
													<option value="">-Select Time-</option>
													<?php if (!empty($time)) { ?>
														<?php foreach ($time as  $t) : ?>
															<option value="<?php echo $t->time; ?>"><?php echo $t->time; ?></option>
														<?php endforeach; ?>
													<?php } ?>
												</select>
											</div>
										</div>
										<!-- <div class="col-sm-12">
											<label>From Pickup Time</label>
											<div class="form-group">
												<input type="time" name="from_pickup_time" class="form-control">
											</div>
										</div>
										<div class="col-sm-12">
											<label>To Pickup Time</label>
											<div class="form-group">
												<input type="time" name="to_pickup_time" class="form-control">
											</div>
										</div> -->

										<div class="col-sm-12">
											<label>Contact Person's Name <span class="compulsory_fields">*</span></label>
											<div class="form-group">
												<input type="text" name="consigner_name" class="form-control"required>
											</div>
										</div>

										<div class="col-sm-12">
											<label class="">Contact Person Email</label>
											<div class="form-group">
												<input type="email" name="consigner_email" class="form-control">
											</div>
										</div>

										<div class="col-sm-12">
											<label>Contact Person Number<span class="compulsory_fields">*</span></label>
											<div class="form-group">
												<input type="text" name="consigner_contact" class="form-control" required>
											</div>
										</div>
										<div class="col-sm-12">
											<label>Address<span class="compulsory_fields">*</span></label>
											<div class="form-group">
												<input type="text" name="consigner_address1" class="form-control"required>
											</div>
										</div>
										<div class="col-sm-12">
											<label>City<span class="compulsory_fields">*</span></label>
											<div class="form-group">
												<input type="text" name="city" class="form-control"required>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Shipment Info -->

					<div class="col-md-5 col-sm-12 mt-3">
						<!-- Consigner Detail -->
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Package Details</h4>
							</div>
							<div class="card-content">
								<div class="card-body">
									<div class="row">
										<div class="col-sm-3">
											<label>Drop Location<span class="compulsory_fields">*</span></label>
											<div class="form-group">
												<input type="text" name="destination_pincode[]" placeholder="Enter Pincode" class="form-control" pattern="[0-9]{6}" maxlength="6" required>
											</div>
										</div>
										<div class="col-sm-2">
											<label>Weight<span class="compulsory_fields">*</span></label>
											<div class="form-group">
												<input type="text" placeholder="WT(Kgs)" name="actual_weight[]" class="form-control" required>
											</div>
										</div>
										<div class="col-sm-3">
											<label>Pkgs<span class="compulsory_fields">*</span></label>
											<select class="form-control" name="type_of_package[]" required>
												<option value="">Select Type Of the Package</option>
												<?php if (!empty('$type_of_package')) { ?>
													<?php foreach ($type_of_package as $value) : ?>
														<option value="<?php echo $value->partial_type; ?>"><?php echo $value->partial_type; ?> </option>
													<?php endforeach; ?>
												<?php } ?>
											</select>
										</div>
										<div class="col-sm-2">
											<label>NOP<span class="compulsory_fields">*</span></label>
											<div class="form-group">
												<input type="number" name="no_of_pack[]" placeholder="nop" class="form-control" required>
											</div>
										</div>
										<div class="col-sm-1">
											<button class="btn btn-success addrow mt-3" type="button" style="border-radius:100%;"><i class="fa fa-plus" aria-hidden="true"></i>
											</button>
										</div>
									</div>
									<div id="append_data"></div>


									<div class="row">
										<div class="col-sm-12">
											<label> Special Instruction<span class="text-danger">*</span></label>
											<div class="form-group">
												<input type="text" name="instruction" class="form-control" required>
											</div>
										</div>
										<div class="col-sm-12">
											<div style="margin-top:10px;">
												<input type="checkbox" name="reciever_name" class="check_click" data-name="show_reccuring"><span>Reccuring</span>
											</div>
										</div>

										<div class="col-sm-12" id="show_reccuring" style="display:none;">
											<div style="margin-top:10px;">
												<input type="checkbox" name="recurring_data[]" value="Monday"><span>Monday</span>
												<input type="checkbox" name="recurring_data[]" value="Tuesday"><span>Tuesday</span>
												<input type="checkbox" name="recurring_data[]" value="wednesday"><span>wednesday</span>
												<input type="checkbox" name="recurring_data[]" value="Thursday"><span>Thursday</span>
												<input type="checkbox" name="recurring_data[]" value="Friday"><span>Friday</span>
												<input type="checkbox" name="recurring_data[]" value="Saturday"><span>Saturday</span>
												<input type="checkbox" name="recurring_data[]" value="Sunday"><span>Sunday</span>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-12 col-sm-12">
								<div style="margin-top:10px;">
									<input type="submit" name="submit" value="Create Pickup Request" class="btn mt-2 btn-danger">
								</div>
							</div>

						</div>
					</div>


					<!-- Consigner Detail -->

					<div class="col-md-4 col-sm-12 mt-3">
						<!-- Consignee Detail -->
						<div class="card">
							<div class="card-header">
								<h6 class="card-title">Last 5 Pickups List</h6>
							</div>
							<div class="card-content">
								<div class="card-body">
									<?php if (!empty($cs_prq_list)) { ?>
										<?php foreach ($cs_prq_list as $value) : ?>
											<div class="row" style="margin-top: 10px;">
												<div class="col-md-6">
												   <div>Pickup Request No</div>
													<div><?php echo $value->pickup_request_id; ?></div>
													<div>Client</div>
													<?php $customer_id = $value->customer_id;
													$dd = $this->db->query("select * from tbl_customers where customer_id ='$customer_id'")->row(); ?>
													<div><?php echo $dd->customer_name; ?></div>
													
												</div>
												<div class="col-md-6">
												   <div>Pickup date</div>
													<div><?php echo $value->pickup_date; ?></div>
													<div>Pickup Location</div>
													<div><?php echo $value->pickup_location; ?></div>
												</div>
											</div>
										<?php endforeach; ?>
									<?php } else { ?>
										<div>
											<h6 style="color:#ff0000;">No Data Found</h6>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
						<!-- Consignee Detail -->
					</div>
				</div>

			</form>
		</div>
		</div>
		</div>
		<!-- </form> -->

		</div>
	</main>
	<!-- END: Content-->
	<!-- START: Footer-->


	<?php include(dirname(__FILE__) . '/../admin_shared/admin_footer.php'); ?>
	<!-- START: Footer-->

	<script>
		$(document).ready(function() {
			var d = "<div class='row' id='remove_row'><div class='col-sm-3'>" +
				"<label>Drop Location<span class='compulsory_fields'>*</span></label>" +
				"<div class='form-group'>" +
				"<input type='text' name='destination_pincode[]' placeholder='Enter Pincode' class='form-control' pattern='[0-9]{6}' maxlength='6'>" +
				"</div>" +
				"</div>" +
				"<div class='col-sm-2'>" +
				"<label>Weight<span class='compulsory_fields'>*</span></label>" +
				"<div class='form-group'>" +
				"<input type='text' placeholder='WT(Kgs)' name='actual_weight[]' class='form-control'>" +
				"</div>" +
				"</div>" +
				"<div class='col-sm-3'>" +
				"<label>Pkgs<span class='compulsory_fields'>*</span></label>" +
				"<select class='form-control' name='type_of_package[]'>" +
				"<option value=''>Select Type Of the Package</option>" +
				<?php if (!empty('$type_of_package')) { ?>
			<?php foreach ($type_of_package as $value) : ?>
					"<option value='<?php echo $value->partial_type; ?>'><?php echo $value->partial_type; ?> </option>" +
				<?php endforeach; ?>
			<?php } ?>
				"</select>" +
				"</div>" +
				"<div class='col-sm-2'>" +
				"<label>NOP<span class='compulsory_fields'>*</span></label>" +
				"<div class='form-group'>" +
				"<input type='number' name='no_of_pack[]' placeholder='nop' class='form-control'>" +
				"</div>" +
				"</div>" +
				"<div class='col-sm-1'>" +
				"<button type='button'id ='remove_row' class='btn btn-danger mt-3' style='border-radius:100%;'><i class='fa fa-minus' aria-hidden='true'></i>" +
				"</button>" +
				"</div>" +
				"</div>";
			$(".addrow").click(function() {
				$("#append_data").append(d);
			});


			$(document).on('click', 'button#remove_row', function() {
				$(".remove_row").remove();
				return false;
			});

			$(document).on('click', '#remove_row', function() {
				$(this).parents('#remove_row').remove();
			});

		});
		$(function() {
			$('.check_click').on("change", function() {
				$('#' + $(this).attr('data-name')).toggle(this.checked); // toggle instead
			}).change(); // trigger the change
		});
	</script>

	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
	<?php if ($this->session->flashdata('flash_message')) : ?>
		<script>
			swal({
				title: "Done",
				text: "<?php echo $this->session->flashdata('flash_message'); ?>",
				timer: 1500,
				showConfirmButton: false,
				type: 'success'
			});
		</script>
	<?php endif; ?>

	<script>

		
	</script>
</body>
<!-- END: Body-->


</html>