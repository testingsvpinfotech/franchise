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
                              <h4 class="card-title">Menifiest Tracking</h4>     
                               <span style="float: right;"><a href="<?php base_url();?>admin/add-news" class="btn btn-warning">Print</a></span>       
         
         <span style="float: right;margin-right: 10px;"><a href="<?php base_url();?>admin/add-news" class="btn btn-primary">Track</a></span>                          
                          </div>
                          <div class="card-body">
                             <div class="row">                                           
                                  <div class="col-12">
                                     
                                     <div class="form-row">
                                            <div class="col-3 mb-3">
                                               <label for="username">Menifiest Number</label>
                                               <input type="text" class="form-control" name="manifiest_id" placeholder=" Menifiest Number" />
                                            </div>
                                      </div>
                                       <div class="form-row">
                                            <div class="col-12 mb-3">
                                               <center><h4>Express Freight System (I) Pvt. Ltd.</h4></center>
                                               <center><b>Address:</b></center>
                                              
                                            </div>
                                      </div>
                                       <div class="form-row">
                                            <div class="col-12 mb-3">

                                                <div class="table-responsive">
                                                <table class="table layout-primary bordered">
                                                    <tbody>
                                                        <tr>
                                                            <th>MANIFEST NO</th><td><?php //echo $manifiest[0]['manifiest_id']; ?></td>
                                                            <th>FLIGHT /Lorry NO</th><td><?php //echo $manifiest[0]['lorry_no']; ?></td>

                                                          </tr>
                                                          <tr>
                                                            <th>MANIFEST DATE</th><td><?php //echo $manifiest[0]['date_added']; ?></td>
                                                            <th>CO LOADER</th><td><?php //echo $manifiest[0]['coloader']; ?></td>
                                                          </tr>

                                                          <tr>
                                                            <th>ORIGIN</th><td><?php //echo $manifiest[0]['source_branch']; ?></td>
                                                            <th>MODE</th><td><?php //echo $manifiest[0]['forwarder_mode']; ?></td>
                                                          </tr>

                                                            <tr>
                                                            <th>DESTINATION HUB</th><td><?php //echo $manifiest[0]['destination_branch']; ?></td>
                                                            <th>No. Of Pcs</th><td><?php //echo $total_pcs; ?></td>
                                                          </tr>

                                                            <tr>
                                                            <th align="right;">Total Weight</th><td colspan="3"><?php echo $total_weightt; ?></td>
                                                            
                                                          </tr>
                                                            
                                                        </tr>
                                                    </tbody>
                                                  </table>
                                                </div>

                                              </div>
                                      </div>
                                 
                
                                  </div>
                                </div>
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

