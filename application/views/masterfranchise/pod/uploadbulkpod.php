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
                              <h4 class="card-title">Upload Bulk Pod</h4>  
                          </div>
                          <div class="card-body">
                              
						   <div class="row">                                           
                            <div class="col-12">
								 <form role="form" action="admin/insert-bulk-pod" method="post" enctype="multipart/form-data">
        <div class="box-body">
				<div class="form-group row">

					<label class="col-sm-1 col-form-label">ZIP :</label>
<div class="col-sm-2">
<input type="file" class="form-control" id="jq-validation-email" name="csv_zip" accept=".zip, .rar" placeholder="Slider Image">
					
				</div>
				<div class="col-md-4">	
              <div class="box-footer">
                <button type="submit" name="submit"  class="btn btn-primary">Submit</button>
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
    <?php $this->load->view('admin/admin_shared/admin_footer');
     //include('admin_shared/admin_footer.php'); ?>
    <!-- START: Footer-->
</body>
<!-- END: Body-->
