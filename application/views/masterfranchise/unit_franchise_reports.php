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
                              <h4 class="card-title">Unit Franchise Reports</h4>
                              
                          </div>
                          <div class="card-body">
                              <div class="table-responsive">
                                  <table id="example" class="table layout-primary bordered">
                                      <thead>
                                          <tr>
											   
											   <th>Sr No</th>
											   <th>Franchise ID</th>
											   <th>Franchise Name</th>
											   <th>Email</th>
											   <th>Phone</th>
											   <th>pincode</th>
											   <th>Address</th>
											   <th>Action</th>
                                          </tr>
                                      </thead>
                                     <tbody>
							
								<?php 
									if(!empty($franchise_list)){ $count = 1;
										foreach($franchise_list as $value)
										{
									?>
										<td><?php echo  $count++; ?></td>
										<td><?php echo  $value->cid; ?></td>
                                        <td><?php echo  $value->customer_name; ?></td>
                                        <td><?php echo  $value->email; ?></td> 
                                        <td><?php echo  $value->phone; ?></td> 
                                        <td><?php echo  $value->pincode; ?></td>
                                        <td><?php echo  $value->address; ?></td>
                                        <td><a href="<?= base_url('master_franchise/franchise_booking/'.$value->customer_id); ?>" style="color:#fff;">Reports</a></td>
								</tr>
								<?php
						        	}
							}
							?>
							</tbody>
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
        
        <?php include('master_franchise_shared/admin_footer.php'); ?>
        <!-- START: Footer-->
    </body>
    <!-- END: Body-->
</html>

		