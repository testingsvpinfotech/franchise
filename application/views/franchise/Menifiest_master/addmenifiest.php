<?php $this->load->view('franchise/franchise_shared/franchise_header'); ?>
<!-- END Head-->
<style>
	.input:focus {
		outline: outline: aliceblue !important;
		border: 2px solid red !important;
		box-shadow: 2px #719ECE;
	}
</style>
<!-- START: Body-->

<body id="main-container" class="default">


	<!-- END: Main Menu-->
	<?php $this->load->view('franchise/franchise_shared/franchise_sidebar');
	// include('admin_shared/admin_sidebar.php'); ?>
	<!-- END: Main Menu-->

	<!-- START: Main Content-->
	<main>
		<div class="container-fluid site-width">
			<!-- START: Listing-->
			<div class="row">
				<div class="col-12  align-self-center">
					<div class="col-12 col-sm-12 mt-3">
						<div class="card">
							<div class="card-header justify-content-between align-items-center">
								<h4 class="card-title">Add Menifiest</h4>
							</div>
							<div class="card-body">
								<?php if ($this->session->flashdata('notify') != '') { ?>
									<div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored">
										<?php echo $this->session->flashdata('notify'); ?>
									</div>
									<?php unset($_SESSION['class']);
									unset($_SESSION['notify']);
								} ?>
								<div class="row">
									<div class="col-12">
										<form role="form" action="<?= base_url('franchise/insert-menifiest'); ?>"
											method="post" enctype="multipart/form-data">

											<div class="form-group row">

												<label class="col-sm-2 col-form-label">Manifiest Date</label>
												<div class="col-sm-2">
													<!--<input type="datetime-local" class="form-control" name="datetime" id="col-sm-1 col-form-label" >-->

													<?php
													$datec = date('Y-m-d H:i');

													// $tracking_data[0]['tracking_date'] = date('Y-m-d H:i',strtotime($tracking_data[0]['tracking_date']));
													$datec = str_replace(" ", "T", $datec);


													// $datec = dateTimeValue($datec);
													// $datec = str_replace(' ', 'T', $datec);
													?>
													<input type="datetime-local" required class="form-control"
														name="datetime" value="<?php echo $datec; ?>"
														id="col-sm-1 col-form-label">
												</div>

												<label class="col-sm-2 col-form-label">Vehicle No</label>
												<div class="col-sm-2">
													<input type="text" name="lorry_no" class="form-control" />
												</div>
												<!-- <label  class="col-sm-2 col-form-label">Route Name</label>
									<div class="col-sm-2">
										<select name="route_id" class="form-control" id="route_id" required>
											<option>Select Route</option>
											<?php foreach ($allroute as $value) {
												?>
												<option value="<?php echo $value['route_id']; ?>"><?php echo $value['route_name']; ?></option>
												<?php
											} ?>
										</select>
									</div> -->
												<label class="col-sm-2 col-form-label">Destination Branch</label>
												<div class="col-sm-2">
													<?php $customer_id = $_SESSION['customer_id'];
													$franchise = $this->db->query("select * from tbl_customers where customer_id = '$customer_id'")->row();

													?>
													<select name="destination_branch" class="form-control"
														id="destination_branch" required>
														<option>Select Destination</option>
														<option value="<?= $_SESSION['branch_name'] ?>">To Branch
														</option>
														<option value="<?= $franchise->parent_cust_id; ?>">To Master
															Franchise</option>
													</select>
												</div>
											</div>
											<!-- <div class="form-group row">
															
									<label  class="col-sm-2 col-form-label">Driver Name</label>
									<div class="col-sm-2">
										<input type="text" name="driver_name" class="form-control" />
									</div>
									<label  class="col-sm-2 col-form-label">Contact No</label>
									<div class="col-sm-2">
										<input type="text" name="contact_no" class="form-control" />
									</div>
									
									
								</div> -->
											<div class="form-group row">
												<!-- <label  class="col-sm-2 col-form-label">Vendor</label>
									<div class="col-sm-2">
										<select name="vendor_id" class="form-control" id="vendor_id" required>
											<option>Select Vendor</option>
											<?php foreach ($all_vendor as $value) {
												?>
												<option value="<?php echo $value->tv_id; ?>"><?php echo $value->vendor_name; ?></option>
												<?php
											} ?>
										</select>
									</div> -->
												<label class="col-sm-2 col-form-label">Forworder Name</label>
												<div class="col-sm-2">
													<select name="forwarder_name" class="form-control"
														id="forwarderName" required>
														<option value="SELF">SELF</option>

													</select>
												</div>
												<!-- <label  class="col-sm-2 col-form-label">Coloader</label>
									<div class="col-sm-2">
										<select name="coloader" class="form-control" id="coloader" >
											<option value="">Select Coloader </option>
											<?php foreach ($coloader_list as $value) {
												?>
												<option value="<?php echo $value['coloader_name']; ?>"><?php echo $value['coloader_name']; ?></option>
												<?php
											} ?>
										</select>
									</div>	 -->
												<label class="col-sm-2 col-form-label">Mode</label>
												<div class="col-sm-2">
													<select name="forwarder_mode" class="form-control"
														id="forwarder_mode" required>
														<option value="All">All</option>
													</select>
												</div>

												<label class="col-sm-2 col-form-label">Supervisor</label>
												<div class="col-sm-2">
													<input type="text" readonly name="username" required
														value="<?= $username; ?>" class="form-control" />
												</div>
												<label class="col-sm-2 col-form-label">Total Pcs</label>
												<div class="col-sm-2">
													<input type="text" readonly name="total_pcs" required id="total_pcs"
														class="form-control" />
												</div>
												<label class="col-sm-2 col-form-label">Total Weight</label>
												<div class="col-sm-2">
													<input type="text" readonly name="total_weight" required
														id="total_weight" class="form-control" />
												</div>
												<label class="col-sm-2 col-form-label">Remark</label>
												<div class="col-sm-2">
													<textarea class="form-control" name="note"> </textarea>
												</div>
												<!-- 	
									<label  class="col-sm-2 col-form-label">Pod csv</label>
									<div class="col-sm-2">
										<input type="file" class="form-control" id="jq-validation-email" name="csv_zip" accept=".csv" placeholder="Slider Image">
									</div> -->
											</div>

											<div class="col-md-3">
												<div class="box-footer pull right">
													<button type="submit" name="submit" id="submit_menifiest"
														style="display: none;" class="btn btn-primary">Submit</button>
												</div>

											</div>
											<div class="col-md-12" id="search" style="display: none;">
												<input type="text" id="search_data" placeholder="Enter Bag No"
													style="float: right;">
												<input type="button" id="btn_search" style="float: right;"
													value="Search">
												<br>
											</div>
											<div class="col-md-12">

												<!--  col-sm-4-->
												<table class="table table-bordered table-striped">
													<thead>
														<tr>
															<th></th>
															<th>Bag No.</th>
															<th>Weight</th>
															<th>Mode</th>
															<th>NOP</th>

														</tr>
													</thead>
													<tbody id="change_status_id">
													</tbody>

												</table>
												<!--  box body-->
											</div>
										</form>
									</div>
								</div>

							</div>
						</div>
						<!-- END: Listing-->
					</div>
	</main>
	<!-- END: Content-->
	<?php ini_set('display_errors', '0');
	ini_set('display_startup_errors', '0');
	error_reporting(E_ALL); ?>
	<!-- START: Footer-->
	<?php $this->load->view('franchise/franchise_shared/franchise_footer');
	//include('admin_shared/admin_footer.php'); ?>
	<!-- START: Footer-->
</body>
<script type="text/javascript"
	src="<?php echo base_url(); ?>assets/jQueryScannerDetectionmaster/jquery.scannerdetection.js"></script>
<script type="text/javascript">
	$(document).scannerDetection({
		timeBeforeScanTest: 200, // wait for the next character for upto 200ms
		startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
		endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
		avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
		onComplete: function (barcode, qty) {
			var forwording_no = barcode;

			var forwarderName = $("#forwarderName").val();
			var forwarder_mode = $("#forwarder_mode").val();

			var message = '';

			$("input[name='bag_id[]']").map(function () {
				var numbers = $(this).val();

				var number = numbers.split("|");

				if (number[0] == forwording_no) {
					message = 'This Bag No Already Exist In The List!';
					// return false;
				}
			}).get();

			if (message != '') {
				alert(message);
				return false;
			}
			$.ajax({
				url: "<?php echo base_url() . 'Admin_domestic_menifiest/bagdata'; ?>",
				type: 'POST',
				dataType: "html",
				data: { forwording_no: forwording_no, forwarderName: forwarderName, forwarder_mode: forwarder_mode },
				error: function () {
					alert('Please Try Again Later');
				},
				success: function (data) {
					console.log(data);

					if (data != "") {
						$("#change_status_id").prepend(data);
						var array = [];

						tw = 0;
						tp = 0;

						$("input.cb[type=checkbox]:checked").each(function () {

							tw = tw + parseFloat($(this).attr("data-tw"));
							tp = tp + parseFloat($(this).attr("data-tp"));

						});

						document.getElementById('total_weight').value = tw;
						document.getElementById('total_pcs').value = tp;

					}
					else {
						$("#change_status_id").prepend('');
					}
					$("#search_data").val('');
					$("#search_data").focus();
					//alert("Record added successfully");  
				},
				error: function (response) {
					console.log(response);
				}
			});
		} // main callback function	
	});
</script>
<!-- END: Body-->
<script type="text/javascript">
	$(document).ready(function () {

		$(window).keydown(function (event) {
			if (event.keyCode == 13) {
				//var awb_no=$(this).val();
				var forwording_no = $("#search_data").val();
				var forwarderName = $("#forwarderName").val();
				var forwarder_mode = $("#forwarder_mode").val();

				if (forwording_no != "") {


					var message = '';

					$("input[name='bag_id[]']").map(function () {
						var numbers = $(this).val();

						var number = numbers.split("|");

						if (number[0] == forwording_no) {
							message = 'This Bag No Already Exist In The List!';
							// return false;
						}
					}).get();

					if (message != '') {
						alert(message);
						return false;
					}
					$.ajax({
						url: "<?= base_url(); ?>Admin_franchise_menifiest/bagdata",
						type: 'POST',
						dataType: "html",
						data: { forwording_no: forwording_no, forwarderName: forwarderName, forwarder_mode: forwarder_mode },
						success: function (data) {
							console.log(data);
							if (data != "") {
								$("#change_status_id").prepend(data);
								var array = [];

								tw = 0;
								tp = 0;

								$("input.cb[type=checkbox]:checked").each(function () {

									tw = tw + parseFloat($(this).attr("data-tw"));
									tp = tp + parseFloat($(this).attr("data-tp"));

								});

								document.getElementById('total_weight').value = tw.toFixed(2);
								document.getElementById('total_pcs').value = tp;
							}
							else {
								$("#change_status_id").prepend('');
							}
							$("#search_data").val('');
						}

					});

				} else {
					alert("Please enter Forwording no");
				}

			}
		});


		$("#btn_search").click(function () {
			//var awb_no=$(this).val();
			var forwording_no = $("#search_data").val();
			var forwarderName = $("#forwarderName").val();
			var forwarder_mode = $("#forwarder_mode").val();



			//  console.log(all);

			if (forwording_no != "") {

				forwording_no = forwording_no.trim();

				var message = '';

				$("input[name='bag_id[]']").map(function () {
					var numbers = $(this).val();

					var number = numbers.split("|");

					if (number[0] == forwording_no) {
						message = 'This Bag No Already Exist In The List!';
						// return false;
					}
				}).get();

				if (message != '') {
					alert(message);
					return false;
				}
				$.ajax({
					url: "<?= base_url(); ?>Admin_franchise_menifiest/bagdata",
					type: 'POST',
					dataType: "html",
					data: { forwording_no: forwording_no, forwarderName: forwarderName, forwarder_mode: forwarder_mode },
					success: function (data) {
						console.log(data);
						if (data != "") {
							$("#change_status_id").prepend(data);
							var array = [];

							tw = 0;
							tp = 0;

							$("input.cb[type=checkbox]:checked").each(function () {

								tw = tw + parseFloat($(this).attr("data-tw"));
								tp = tp + parseFloat($(this).attr("data-tp"));

							});

							document.getElementById('total_weight').value = tw.toFixed(2);
							document.getElementById('total_pcs').value = tp;
							$("#search_data").val('');
						}
						else {
							$("#change_status_id").prepend('');
						}
						$("#search_data").focus();

					}

				});

			} else {
				alert("Please enter Forwording no");
			}



		});

		$("#podbox").change(function () {

			var podno = $(this).val();
			if (podno != null || podno != '') {

				$.ajax({
					type: 'POST',
					url: '<?php echo base_url() ?>menifiest/getPODDetails',
					data: 'podno=' + podno,
					success: function (d) {
						//alert(d);
						var x = d.split("-");
						//alert(x);
						$(".consignername").val(x[0]);

						$(".pieces").val(x[2]);
						$(".weight").val(x[3]);
					}
				});
			} else {

			}

		});


		var tw;
		var tp;

		$(document).on("click", ".cb", function () {


			var array = [];

			tw = 0;
			tp = 0;

			$("input.cb[type=checkbox]:checked").each(function () {

				tw = tw + parseFloat($(this).attr("data-tw"));
				tp = tp + parseFloat($(this).attr("data-tp"));


			});

			document.getElementById('total_weight').value = tw;
			document.getElementById('total_pcs').value = tp;

		});



		$('#example1').DataTable({
			'paging': true,
			'lengthChange': true,
			'searching': true,
			'ordering': true,
			'info': true,
			'autoWidth': true

		});
	});
	$(document).keypress(
		function (event) {
			if (event.which == '13') {
				event.preventDefault();
			}
		});

	$("#destination_branch").change(function () {

		$('#search').show();
		$('#submit_menifiest').show();
	});

</script>

<?php

function dateTimeValue($timeStamp)
{
	$date = date('d-m-Y', $timeStamp);
	$time = date('H:i:s', $timeStamp);
	return $date . 'T' . $time;
}

?>