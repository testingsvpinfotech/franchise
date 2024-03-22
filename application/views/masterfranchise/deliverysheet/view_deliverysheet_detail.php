<?php $this->load->view('masterfranchise/master_franchise_shared/admin_header.php'); ?>
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
 
                          </div>
                          <div class="card-body">
                              <div class="table-responsive">
                                  <table class="table layout-primary bordered">
                                      <thead>
                                          <tr>      
                                             
												  <th scope="col">SR No.</th>
												  <th scope="col">Airway Number</th>
												  <th scope="col">Consigner Name</th>
												  <th scope="col">Consignee Address</th>
												  <th scope="col">Contact Number</th>
												  <th scope="col">Signature</th>
                                          </tr>
                                      </thead>
                                      <tbody>  

                          
                 <?php
				  foreach ($info as  $value) {
                  ?>  
				  <tr>
				  <td><?php echo $value['id'];?></td>
                  <td><?php echo $value['pod_no'];?></td>
				  <td><?php echo $value['sender_name'];?></td>
				  <td><?php echo $value['reciever_address'];?></td>
				  <td><?php echo $value['reciever_contact'];?></td>
                  <td></td>
				</tr>
				<?php 
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
    <?php $this->load->view('masterfranchise/master_franchise_shared/admin_footer.php');
     //include('admin_shared/admin_footer.php'); ?>
    <!-- START: Footer-->
</body>
<!-- END: Body-->

