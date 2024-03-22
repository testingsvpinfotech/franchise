<?php include('master_franchise_shared/admin_header.php'); ?>
<!-- END Head-->

<!-- START: Body-->

<body id="main-container" class="default">


	<!-- END: Main Menu-->
	<?php include('master_franchise_shared/admin_sidebar.php'); ?>
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
								<h4 class="card-title">Add Incoming</h4>
							</div>
							<div class="card-body">

								<div class="row">
									<div class="col-12">
										<form role="form" action="master_franchise/addincoming" method="post">
											<div class="box-body">
												<div class="form-group row">
													<label class="col-sm-1 col-form-label">Menifiest Id:</label>
													<div class="col-sm-2">
														<select class="form-control" name="manifiest_id" required>
															<option value="">Select Menifiest Id</option>
															<?php
															if (!empty($menifiest)) {
																foreach ($menifiest as $row) {
																	$res		=	$this->db->query("select count(manifiest_id) as total from tbl_inword where manifiest_id='$row->manifiest_id' and status='recieved' and branch_code='$branch_name'");
																	$total		=	$res->row()->total;
																	// echo $total;
																	if ($total == 0) {
															?>
																		<option value="<?php echo $row->manifiest_id; ?>" <?php echo ($manifiest_id == $row->manifiest_id) ? 'selected' : ''; ?>><?php echo $row->manifiest_id; ?> --<?php echo $row->date_added; ?></option>
															<?php
																	}
																}
															} else {
																echo "<p>No Data Found</p>";
															}
															?>
														</select>
													</div>

												</div>
											</div>

											<div class="form-group">
												<label for="exampleInputEmail1"></label>
												<button type="submit" name="submit" class="btn btn-primary">View</button>
											</div>
									</div>
								</div>


								<input type="text" id="serach_data" value="" style="float: right;">
								</form>
								<br>
								<table class="table table-bordered table-striped">
									<form role="form" action="<?= base_url('master_franchise/addincoming'); ?>" method="post">
										<div class="form-group row">
											<label class="col-sm-1 col-form-label"> Date</label>
											<div class="col-sm-2">
												<?php
												$datec = date('Y-m-d H:i');

												// $tracking_data[0]['tracking_date'] = date('Y-m-d H:i',strtotime($tracking_data[0]['tracking_date']));
												$datec = str_replace(" ", "T", $datec);


												// $datec = dateTimeValue($datec);
												// $datec = str_replace(' ', 'T', $datec);
												?>
												<input type="datetime-local" class="form-control" name="datetime"
													id="exampleInputEmail1" required value="<?= $datec; ?>">
											</div>
										</div>
										<thead>
											<tr>
												<th>#</th>
												<th>Bag Number</th>
												<th>Destination</th>
												<th>NoP</th>
												<th>weight</th>
												<th>Recived NOP</th>
												<th>Balance NOP</th>
												<th>Status</th>
												<th>Remark</th>
												<th>Recived By</th>
												<th>Date & Time</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$recived = 0;
											if (!empty($menifiest_data)) {
												foreach ($menifiest_data as $value) {

													?>
													<tr>
														<td>
															<input type="checkbox" checked name="bag_no[]"
																value="<?= $value->bag_no ?>">
														</td>
														<td>
															<?= $value->bag_no ?>
														</td>
														<td><?= $value->destination_branch ?></td>
														<td><?= $value->total_pcs ?></td>
														<td><?= $value->total_weight ?></td>
														<td><textarea name="recived_nop" placeholder="Recived NOP"></textarea>
														</td>
														<td><textarea name="balance_nop" placeholder="Balance NOP"></textarea>
														</td>
														<?php
														if ($value->reciving_status == 0) { ?>
															<td id="<?php echo $value->bag_no; ?>">
																<?php echo ($value->reciving_status > 0) ? 'Recieved' : ''; ?>
															</td>
															<input type="hidden" name="value_<?php echo $value->bag_no; ?>"
																id="value_<?php echo $value->bag_no; ?>" value="">
														<?php
														} else {
															$recived++;
															?>

															<td>Recieved</td>
														<?php } ?>
														<td><textarea name="note[]" placeholder="remark"></textarea></td>
														<td><?= $value->username ?></td>
														<td><?= $value->date_added ?></td>

													</tr>
												<?php
												}
											} else {
												echo "<p>No Data Found</p>";
											}
											?>
										</tbody>
										<tr>
											<td></td>
											<td>Total :
												<?php echo (!empty($menifiest_data)) ? count($menifiest_data) : 0; ?>
											</td>
											<td id="total_recived">Recived :
												<?php echo $recived; ?>
											</td>
											<td id="total_pending">Pending :
												<?php echo (!empty($menifiest_data)) ? (count($menifiest_data) - -$recived) : 0; ?>
											</td>

											<input id="total_recivedd" type="hidden" value='0'>
											<input id="total_pendingg" type="hidden"
												value='<?php echo (count($menifiest_data) - $recived); ?>'>
											<input id="manifiest_id" name="manifiest_id" type="hidden"
												value='<?php echo $manifiest_id; ?>'>
										</tr>
								</table><br><br>
								<div class="form-group">
									<label class="col-sm-1 col-form-label"></label>
									<button type="submit" name="receving" class="btn btn-primary">Submit</button>
								</div>
								</form>
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
	<?php include('master_franchise_shared/admin_footer.php'); ?>
	<!-- START: Footer-->
</body>
<!-- END: Body-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/jQueryScannerDetectionmaster/jquery.scannerdetection.js"></script>
<script type="text/javascript">
	$(document).scannerDetection({
		timeBeforeScanTest: 200, // wait for the next character for upto 200ms
		startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
		endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
		avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
		onComplete: function(barcode, qty) {
			var pod_no = barcode;


			if ($("#" + pod_no).length !== 0) {
				if (!$("#value_" + pod_no).val()) {
					$('#' + pod_no).html('Recived');
					$('#value_' + pod_no).val('Recived');

					var total_recived = $('#total_recivedd').val();
					total_recived++;
					$('#total_recived').html('Recived : ' + total_recived);
					$('#total_recivedd').val(total_recived);

					var total_pending = $('#total_pendingg').val();
					total_pending--;
					$('#total_pending').html('Pending : ' + total_pending);
					$('#total_pendingg').val(total_pending);

				}
				$('#serach_data').val('');
				$('#serach_data').focus();
			}
		} // main callback function	
	});


	$(document).ready(function() {
		$(window).keydown(function(event) {
			if (event.keyCode == 13) {

				$("#podbox").change(function() {

					var podno = $(this).val();
					if (podno != null || podno != '') {

						$.ajax({
							type: 'POST',
							url: '<?php echo base_url() ?>menifiest/getPODDetails',
							data: 'podno=' + podno,
							success: function(d) {
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
			}
		});


		$("#serach_data").blur(function() {
			var pod_no = $(this).val();


			//console.log(pod_no);
			if (!$("#value_" + pod_no).val()) {
				$('#' + pod_no).html('Recived');
				$('#value_' + pod_no).val('Recived');

				var total_recived = $('#total_recivedd').val();
				total_recived++;
				$('#total_recived').html('Recived : ' + total_recived);
				$('#total_recivedd').val(total_recived);

				var total_pending = $('#total_pendingg').val();
				total_pending--;
				$('#total_pending').html('Pending : ' + total_pending);
				$('#total_pendingg').val(total_pending);

			}
			$('#serach_data').val('');
			$('#serach_data').focus();



			/*  var podno=$(this).val();
			 if (podno!=null || podno!='') {
			     
			     $.ajax({
			       type:'POST',
			       url:'<?php echo base_url() ?>menifiest/getPODDetails',
			       data:'podno='+podno,
			       success:function(d)
			       {
			         //alert(d);
			           var x=d.split("-");
			         //alert(x);
			            $(".consignername").val(x[0]);
			           
			            $(".pieces").val(x[2]);
			            $(".weight").val(x[3]);
			       }
			     });
			 }else{

			 } */

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
</script>