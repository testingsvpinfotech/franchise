<?php $this->load->view('franchise/franchise_shared/franchise_header'); ?>

<body>
    <?php $this->load->view('franchise/franchise_shared/franchise_sidebar');?>    
    <main>
<div class="container-fluid site-width">
<!-- START: Listing-->
<div class="row">
<div class="col-12 mt-5">
<div class="card">
    <div class="card-header mt-3">                               
        <h4 class="card-title">Edit Profile</h4>                                
    </div>
        <div class="card-content">
            <div class="card-body">
                          <?php 
                  if ($this->session->flashdata('notify') != '') { 
              ?>
                  <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored">
                      <?php echo $this->session->flashdata('notify'); ?>
                  </div>
              <?php 
                      // Unset flash data after displaying it
                      $this->session->unset_userdata('notify');
                      $this->session->unset_userdata('class');
              ?>
              <?php 
                  } 
              ?>
                <div class="row">                                           
                    <div class="col-12">
                    <form role="form" action="<?php echo base_url('franchise/update/profile/'.$profile_info->customer_id);?>" method="post" enctype="multipart/form-data">
                        <div class="form-row">
                        <div class="col-3 mb-3">
                            <label for="username">Name</label>
                           <input type="text" class="form-control" name="customer_name" id="exampleInputEmail1" required placeholder="Name" value="<?php echo $profile_info->customer_name; ?>">                           
                           <input type="hidden" name="customer_id" value="<?php echo $_SESSION['customer_id'];?>">
                        </div>
                        <div class="col-3 mb-3"> 
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" name="email" id="exampleInputEmail1"  placeholder="Enter email" value="<?php echo $profile_info->email; ?>">
                        </div>
                        <div class="col-3 mb-3">
                          <label for="username">Branch Code</label>
                          <input type="text" class="form-control" name="branch_id" id="jq-validation-email" placeholder="Enter Branch Code" value="<?php echo $profile_info->branch_id;?>">
                        </div>
                        <div class="col-3 mb-3">
                          <label for="address">Address</label>
                          <input type="text" class="form-control" name="address" placeholder="Enter address" value="<?php echo $profile_info->address; ?>">
                        </div>
                        <div class="col-3 mb-3">
                          <label for="city">City</label>
                          <input type="text" class="form-control" name="city" placeholder="Enter City" value="<?php echo $profile_info->city; ?>">
                        </div>
                        <div class="col-3 mb-3">
                          <label for="state">State</label>
                          <input type="text" class="form-control" name="state" placeholder="Enter State" value="<?php echo $profile_info->state;?>">
                        </div>
                       <div class="col-3 mb-3">
                            <label for="username">Contact No</label>
                           <input type="text" class="form-control" name="phone" id="exampleInputEmail1" placeholder="Enter Contact No" value="<?php echo $profile_info->phone; ?>">
                      </div> 
                      <div class="col-3 mb-3">
                          <label for="Area">Area</label>
                          <input type="text" class="form-control" name="cmp_area" placeholder="Enter Area" value="<?php echo $franchise_info->cmp_area; ?>" readonly>
                      </div>
                      <div class="col-12">
                          <input type="submit" class="btn btn-primary" name="submit" value="Submit">  
                      </div>
                    </div>
                  </form>
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


    <?php $this->load->view('franchise/franchise_shared/franchise_footer');?>    
</body>