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
                              <h4 class="card-title">International Menifiest</h4>  
                <span style="float: right;"><a href="admin/menifiest-international-add" class="btn btn-primary">Add Menifiest</a></span>							  
                          </div>
                          <div class="card-body">
                              <div class="table-responsive">
                                  <table class="table layout-primary table-bordered">
                                      <thead>
                                          <tr>      
                                               <th scope="col">Id</th>
                                                <th scope="col">Menifiest ID</th>         
                                                <th scope="col">Source Branch</th>
                                               <!--  <th scope="col">Destination Branch</th> -->
                                                <th scope="col">Forwarder Name</th>
                                                <th scope="col">Mode</th>
                                                <th scope="col">Pcs</th>
                                                <th scope="col">Weight</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Action</th>
                                          </tr>
                                      </thead>
                                      <tbody>  

                                       <?php 
                  if (!empty($allpod)){
                    $cnt=0;
                    foreach ($allpod as  $ct) 
					{
          $cnt++;  ?>
                      <tr>
                        <td><?php echo $cnt;?></td>
                        <td><a href="admin/tracking-international-menifiest/<?php echo $ct->manifiest_id;?>"><?php echo $ct->manifiest_id;?></a></td>               
                        <td><?php echo $ct->source_branch;?></td>
                        <!-- <td><?php echo $ct->destination_branch;?></td>-->
                        <td><?php echo $ct->forwarder_name;?></td> 
                        <td><?php echo $ct->forwarder_mode;?></td>                         
                        <td><?php echo $ct->total_pcs;?></td>
                        <td><?php echo round($ct->total_weight,2);?></td>
                        <td><?php echo date("d-m-Y",strtotime($ct->date_added) );?></td>
                        <td><a href="admin/edit-international-menifiest/<?php echo $ct->id;?>" ><i class="ion-edit" style="color:var(--primarycolor)"></i></a></td>
                    </tr>
                  <?php } 
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
    <?php $this->load->view('admin/admin_shared/admin_footer');
     //include('admin_shared/admin_footer.php'); ?>
    <!-- START: Footer-->
</body>
<!-- END: Body-->

