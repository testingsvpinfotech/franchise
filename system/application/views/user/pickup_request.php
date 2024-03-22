     <?php $this->load->view('user/admin_shared/admin_header'); ?>
    <!-- END Head-->

    <!-- START: Body-->
    <body id="main-container" class="default">
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
        
        <!-- END: Main Menu-->
    <?php $this->load->view('user/admin_shared/admin_sidebar');
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
                              <h4 class="card-title">Pickup request</h4>
                          </div>
                          <div class="card-content">
                                <div class="card-body">
								<?php if($message) { ?><br><br>
					<div class="row alert alert-success"><div class="col-md-12"><?php echo $message;?></div></div>
				<?php } ?>
                                <div class="row">                                           
                                    <div class="col-12">
                                  
                                    </div>
                                </div>
                                </div>
                            </div>
                          <div class="card-body">
                               <form method="post" class="" action="<?php echo base_url();?>User_panel/pickup_request">
                                <input type="hidden" value="Contact" name="dzToDo" >
                                <div class="row">
								<div class="col-md-6">
									<h4>Consigner</h4>
                                        <div class="form-group">
                                            <div class="input-group"> <!--<span class="input-group-addon"><i class="fa fa-user"></i></span>-->
                                                <input type="text" name="consigner_name"  required class="form-control " placeholder="Enter Your Name">
                                            </div>
                                        </div>
										<div class="form-group">
                                            <div class="input-group"> <!--<span class="input-group-addon"><i class="fa fa-home"></i></span>-->
                                                <input type="text" name="consigner_address" required class="form-control " placeholder="Enter Your Address">
                                            </div>
                                        </div>
										<div class="form-group">
                                            <div class="input-group"> <!--<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>-->
                                                <input type="text" name="consigner_city" required class="form-control " placeholder="Enter Your City">
                                            </div>
                                        </div>
										<div class="form-group">
                                            <div class="input-group"> <!--<span class="input-group-addon"><i class="fa fa-user"></i></span>-->
                                                <input type="text" name="consigner_gstno" required class="form-control " placeholder="Enter GST No.">
                                            </div>
                                        </div>
										<div class="form-group">
                                            <div class="input-group"> <!--<span class="input-group-addon"><i class="fa fa-user"></i></span>-->
                                                <input type="text" name="consigner_pincode" class="form-control" required placeholder="Enter Pincode">
                                            </div>
                                        </div>
										<div class="form-group">
                                            <div class="input-group"> <!--<span class="input-group-addon"><i class="fa fa-user"></i></span>-->
                                                <input type="text" name="consigner_contact" class="form-control" required placeholder="Enter Contact">
                                            </div>
                                        </div>
										<div class="form-group">
                                            <div class="input-group"> <!--<span class="input-group-addon"><i class="fa fa-user"></i></span>-->
                                                <input type="text" name="consigner_email" class="form-control" required placeholder="Enter Email">
                                            </div>
                                        </div>
											
                                    </div>
									<div class="col-md-6">
									<h4>Consignee</h4>
                                        <div class="form-group">
                                            <div class="input-group"> <!--<span class="input-group-addon"><i class="fa fa-home"></i></span>-->
                                                <input type="text" name="consignee_name" class="form-control" required placeholder="Enter Your Name">
                                            </div>
                                        </div>
										<div class="form-group">
                                            <div class="input-group"> <!--<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>-->
                                                <input type="text" name="consignee_address" class="form-control" required placeholder="Enter Your Address">
                                            </div>
                                        </div>
										<div class="form-group">
                                            <div class="input-group"> <!--<span class="input-group-addon"><i class="fa fa-envelope"></i></span>-->
                                                <input type="text" name="consignee_city" class="form-control" required placeholder="Enter City">
                                            </div>
                                        </div>
										<div class="form-group">
                                            <div class="input-group"> <!--<span class="input-group-addon"><i class="fa fa-envelope"></i></span>-->
                                                <input type="text" name="consignee_gstno" class="form-control" required placeholder="Enter GST NO.">
                                            </div>
                                        </div>
										<div class="form-group">
                                            <div class="input-group"> <!--<span class="input-group-addon"><i class="fa fa-envelope"></i></span>-->
                                                <input type="text" name="consignee_pincode" class="form-control" required placeholder="Enter Pincode No.">
                                            </div>
                                        </div>
											<div class="form-group">
                                            <div class="input-group"> <!--<span class="input-group-addon"><i class="fa fa-user"></i></span>-->
                                                <input type="text" name="consignee_contact" class="form-control" required placeholder="Enter Contact">
                                            </div>
                                        </div>
										<div class="form-group">
                                            <div class="input-group"> <!--<span class="input-group-addon"><i class="fa fa-user"></i></span>-->
                                                <input type="text" name="consignee_email" class="form-control" required placeholder="Enter Email">
                                            </div>
                                        </div>
										
                                    </div>
									<div class="col-md-6">
									<br>
										<div class="row">
												<div class="col-md-6">
													<input type="text" name="weight" class="form-control" required placeholder="Enter Weight">
												</div>
											<div class="col-md-6">
												<div class="input-group"> 
													<input type="text" name="qty" class="form-control" required placeholder="Enter Qty">
												</div>
											</div>
                                        </div>
                                    <br>
                                    <div class="col-md-12">
                                        <button name="submit" type="submit" value="Submit" class="btn btn-primary "> <span>Submit</span> </button>
                                        <button name="Resat" type="reset" value="Reset"  class="btn btn-danger"> <span>Reset</span> </button>
                                    </div>
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
    <?php $this->load->view('user/admin_shared/admin_footer');
     //include('admin_shared/admin_footer.php'); ?>
    <!-- START: Footer-->
</body>
<!-- END: Body-->

