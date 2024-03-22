<?php  $this->load->view('masterfranchise/master_franchise_shared/admin_header.php');?>
    <!-- END Head-->

    <!-- START: Body-->
    <body id="main-container" class="default">

        
        <!-- END: Main Menu-->
   
        <?php $this->load->view('masterfranchise/master_franchise_shared/admin_sidebar.php');?>
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
                              <h4 class="card-title">Franchise Booking List</h4>
                              
                          </div>
                          <div class="card-body">
                              <div class="table-responsive">
                                  <table id="example" class="table layout-primary bordered">
                                      <thead>
                                          <tr>
											   <th>Sr No</th>
											   <th>AWB No</th>
											   <th>Franchise Name</th>
											   <th>Franchise Code</th>
											   <th>Booking Date</th>
											   <th>Amount</th>
                                          </tr>
                                      </thead>
                                     <tbody>
							
								<?php 
									if(!empty($franchise_list)){ $count = 1;
										foreach($franchise_list as $value)
										{
                                          $val =  $this->db->query("select * from tbl_customers where customer_id = '$value->customer_id'")->row();
									?>
										<td><?php echo  $count++; ?></td>
										<td><?php echo  $value->pod_no; ?></td>
                                        <td><?php echo  $val->customer_name; ?></td>
                                        <td><?php echo  $val->cid; ?></td> 
                                        <td><?php echo  $value->booking_date; ?></td> 
                                        <td><?php echo  $value->sub_total; ?></td> 
                                       
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
        
        <?php  $this->load->view('masterfranchise/master_franchise_shared/admin_footer.php');?>
        <!-- START: Footer-->
    </body>
    <!-- END: Body-->
</html>

		