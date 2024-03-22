<?php $this->load->view('admin/admin_shared/admin_header'); ?>
    <!-- END Head-->

    <!-- START: Body-->
    <body id="main-container" class="default">
        
        <!-- END: Main Menu-->
    <?php $this->load->view('admin/admin_shared/admin_sidebar');
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
                              <h4 class="card-title">Pickup Request Data</h4>
                          </div>

						   <div class="card-header justify-content-between align-items-center">                             
							  <span>
									
							  </span>
                          </div>
                          <div class="card-body">
                          	<?php if($this->session->flashdata('notify') != '') {?>
  <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored"><?php echo $this->session->flashdata('notify'); ?></div>
  <?php  unset($_SESSION['class']); unset($_SESSION['notify']); } ?> 
                              <div class="table-responsive">
                                 <table class="display table dataTable table-striped table-bordered layout-primary" data-sorting="true"><!-- id="example"-->
                                      <thead>
                                          <tr>
											    <th  scope="col">Sr.no</th>
											    <th  scope="col">Customer Name</th>
											    <th  scope="col">Consigner Name</th>
											    <th  scope="col">Pickup Request_id</th>
											    <th  scope="col">Consigner Contact</th>
											    <th  scope="col">Consigner Address1</th>
											    <th  scope="col">Consigner Address2</th>
											    <th  scope="col">Consigner Address3</th>
											    <th  scope="col">Consigner Email</th>
												<th  scope="col">Pickup Pincode</th>
											    <th  scope="col">Destination Pincode</th>
											    <th  scope="col">Destination Location</th>
											    <th  scope="col">Pickup Date</th>
											    <th  scope="col">City</th>
											    <th  scope="col">Instruction</th>
											    <th  scope="col">Product</th>
											    <th  scope="col">Weight</th>
											    <th  scope="col">Type Of Package</th>
											    <th  scope="col">Volume data </th>
											    <th  scope="col">NOP </th>
											   
                                          </tr>
                                      </thead>
                                      <tbody>
                                 <?php 
                                    if (!empty($all_request))
									{
										$cnt = 1;
										foreach ($all_request as $value) 
										{
                   
                                    ?>
											<tr class="odd gradeX">
												<td><?php echo $cnt; ?></td>
												<?php $customer_id = $value->customer_id; $dd = $this->db->query("select customer_name from tbl_customers where customer_id = '$customer_id'")->row();?>
												
												<td><?php echo $dd->customer_name; ?></td>
												<td><?php echo $value->consigner_name; ?></td>
												<td><?php echo $value->pickup_request_id; ?></td>
												<td><?php echo $value->consigner_contact; ?></td>
												<td><?php echo $value->consigner_address1; ?></td>
												<td><?php echo $value->consigner_address2; ?></td>
												<td><?php echo $value->consigner_address3; ?></td>
												<td><?php echo $value->consigner_email; ?></td>
												<td><?php echo $value->pickup_pincode; ?></td>
												<td><?php echo $value->destination_pincode; ?></td>
												<td><?php echo $value->destination_location; ?></td>
												<td><?php echo $value->pickup_date; ?></td>
												<td><?php echo $value->city; ?></td>
												<td><?php echo $value->instruction; ?></td>
												<td><?php echo $value->product; ?></td>
												<td><?php echo $value->actual_weight; ?></td>
												<td><?php echo $value->type_of_package; ?></td>
												<td><?php echo $value->volume_weight; ?></td>
												<td><?php echo $value->no_of_pack; ?></td>
												
											 </tr>
									<?php 
										$cnt++;
										}
									}
										?>
                                 </tbody>
                                 <input type="hidden" name="selected_campaing" id="selected_campaingss" value="">
                                 </table> 
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
    <?php $this->load->view('admin/admin_shared/admin_footer');
     //include('admin_shared/admin_footer.php'); ?>
    <!-- START: Footer-->
</body>
<!-- END: Body-->
    </body>
    <!-- END: Body-->
</html>
