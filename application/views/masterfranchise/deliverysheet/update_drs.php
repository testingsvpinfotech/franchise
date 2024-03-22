<?php $this->load->view('masterfranchise/master_franchise_shared/admin_header.php'); ?>
<!-- END Head-->


<style>
	table {
		border-collapse: collapse;
		font-family: arial;
	}

	td {
		padding: 8px;
	}

	body {
		margin: 0;
		padding: 0;
		background-color: #FAFAFA;
		font: 12pt "Tahoma";
	}

	* {
		box-sizing: border-box;
		-moz-box-sizing: border-box;
	}

	.page {
		width: 21cm;
		min-height: 29.7cm;
		padding: 1cm;
		margin: 0.5cm auto;
		border: 1px #D3D3D3 solid;
		border-radius: 5px;
		background: white;
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
	}

	.subpage {
		padding: 0cm;
		border: 0px black solid;
		height: 256mm;
	}

	@page {
		size: A4;
		margin: 0;
	}

	@media print {
		.page {
			margin: 0;
			border: initial;
			border-radius: initial;
			width: initial;
			min-height: initial;
			box-shadow: initial;
			background: initial;
			page-break-after: always;
		}
	}
</style>


<!-- START: Body-->

<body id="main-container" class="default">


	<!-- END: Main Menu-->
	<?php $this->load->view('masterfranchise/master_franchise_shared/admin_sidebar.php');
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
								<h4 class="card-title">Delivery Sheet</h4>
								<!-- <span style="float: right;"><a href="<?php echo base_url(); ?>admin/printdeliverysheet/<?php echo $deliverysheet_id; ?>" class="btn btn-info" target="_blank">Print</a></span> -->
							</div>
							<div class="card-body">
							<?php if($this->session->flashdata('notify') != '') {?>
  <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored"><?php echo $this->session->flashdata('notify'); ?></div>
  <?php  unset($_SESSION['class']); unset($_SESSION['notify']); } ?> 
							<form action="<?= base_url('master-franchise/update-drs'); ?>" method="post">
								<div class="row">
									
										<div class="col-md-2">
											<input type="text" class="form-control" name="deliverysheet_id" placeholder="Search DRS No">
										</div>
										<div class="col-md-2">
											<input type="submit" class="btn btn-primary btn-sm" name="submit" value="Search">
										</div>
									
								</div>
								</form>
							  <?php if(! empty($deliverysheet)){ ?>
								<div>
								<p><b><center><?php echo $company_setting->company_name; ?></center></b></p>
							<?php  
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
											<td style="border:2px solid;padding: 5px;"><?php echo $deliverysheet[0]['full_name']; ?></td>
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
									if (!empty($deliverysheet)) {
									?>
										<table class="table table-bordered table-striped">
											<thead>
												<tr>
													<th>ID</th>
													<th>AWB Number</th>
													<th>Consigner Name</th>
													<th>Consignee Name</th>
													<th>Status</th>
													<th>Comments</th>
													

												</tr>
											</thead>
											<tbody>
												<tr>

													<?php //print_r($deliverysheet);
													$cnt = 1;
													foreach ($deliverysheet as  $row) {
														$pod = $row['pod_no'];
														$resAct = $this->db->query("select * from  tbl_domestic_tracking where	pod_no='$pod' AND status='Delivered'")->row_array();
													//echo '<pre>';	print_r($resAct);
													if(empty($resAct)){
													?> 
                                                        

														<td><?php echo $cnt; ?></td>
														<td><?php echo $row['pod_no']; ?></td>
														<?php
														$whr_u = array('pod_no' => $row['pod_no']);
														$booking = $this->basic_operation_m->get_table_row('tbl_domestic_booking', $whr_u);
														?>
														<td><?php echo $booking->sender_name; ?></td>
														<td><?php echo $booking->reciever_name; ?></td>
														<form action="<?= base_url('admin/update-drs');  ?>" method="post">
														<td> <input type="hidden" name="pod_no[]" value="<?php echo $row['pod_no']; ?>">
															<select name="status[]"  class="form-control" id="">
															<option value="0"> Select Status</option>
																<?php 	$status = $this->db->query("select * from  tbl_status where status !='Booked' AND status !='In transit'")->result_array(); 
																foreach($status as $value){
																?>
																<option value="<?= $value['status'] ?>"><?= $value['status'] ?></option> 
																<?php } ?>
															</select>
														</td>
														<td> <textarea class="form-control" name="comments[]" placeholder="Comments"></textarea> </td>

												</tr>
											<?php
													}$cnt++;
													}
											?>
                                            <input type="submit" name="submit" class="btn btn-primary" value="Submit">
											</form>
											</tbody>
											
										</table>
									<?php
									} else {
										echo "<p>No Data Found</p>";
									}
									?>
								</div>
								<?php } ?>
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
	<!-- START: Footer-->
</body>
<!-- END: Body-->