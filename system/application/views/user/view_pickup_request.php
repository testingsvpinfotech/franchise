     <?php $this->load->view('user/admin_shared/admin_header'); ?>
    <!-- END Head-->
<style>
  .buttons-copy{display: none;}
  .buttons-csv{display: none;}
  /*.buttons-excel{display: none;}*/
  .buttons-pdf{display: none;}
  .buttons-print{display: none;}
  /*#example_filter{display: none;}*/
  .input-group{
    width: 60%!important;
  }
</style>
    <!-- START: Body-->
    <body id="main-container" class="default">

        
        <!-- END: Main Menu-->
   
     <?php $this->load->view('user/admin_shared/admin_sidebar'); ?>
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
                              <h4 class="card-title">Pickup Request</h4>
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
											    <th  scope="col">Consigner Name</th>
											    <th  scope="col">Consigner Add</th>
											    <th  scope="col">Consigner city</th>
											    <th  scope="col">Consigner GST</th>
											    <th  scope="col">Consigner Pincode</th>
											    <th  scope="col">Consigner Contact</th>
											    <th  scope="col">Consigner Email</th>
												<th  scope="col">Consignee Name</th>
											    <th  scope="col">Consignee Add</th>
											    <th  scope="col">Consignee city</th>
											    <th  scope="col">Consignee GST</th>
											    <th  scope="col">Consignee Pincode</th>
											    <th  scope="col">Consignee Contact</th>
											    <th  scope="col">Consignee Email</th>
											    <th  scope="col">Weight</th>
											    <th  scope="col">Qty</th>
											    <th  scope="col">Pickup Date</th>
											   
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
												<td><?php echo $value->consigner_name; ?></td>
												<td><?php echo $value->consigner_address; ?></td>
												<td><?php echo $value->consigner_city; ?></td>
												<td><?php echo $value->consigner_gstno; ?></td>
												<td><?php echo $value->consigner_pincode; ?></td>
												<td><?php echo $value->consigner_contact; ?></td>
												<td><?php echo $value->consigner_email; ?></td>
												<td><?php echo $value->consignee_name; ?></td>
												<td><?php echo $value->consignee_address; ?></td>
												<td><?php echo $value->consignee_city; ?></td>
												<td><?php echo $value->consignee_gstno; ?></td>
												<td><?php echo $value->consignee_pincode; ?></td>
												<td><?php echo $value->consignee_contact; ?></td>
												<td><?php echo $value->consignee_email; ?></td>
												<td><?php echo $value->weight; ?></td>
												<td><?php echo $value->qty; ?></td>
												<td><?php echo $value->pickup_date; ?></td>
												
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
        
         <?php $this->load->view('user/admin_shared/admin_footer'); ?>
        <!-- START: Footer-->
    </body>
    <!-- END: Body-->
</html>
