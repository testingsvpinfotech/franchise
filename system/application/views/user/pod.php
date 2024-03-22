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
                              <h4 class="card-title">POD Search</h4>
                          </div>
                          <div class="card-content">
                                <div class="card-body">
								
                               <div class="row">
								<div class="form-group col-md-6">
									
									<div class="search-bx">
										<form role="search" method="post" action="<?php echo base_url()?>users/pod" enctype="multipart/form-data">
											<div class="input-group">
												<input name="pod_no" type="text" class="form-control" placeholder="Airway Number">
												
												<button type="submit" name="submit" class="btn btn-primary">Search</button>
												
											</div>
										</form>
									</div>
								</div>
						</div>	
						<div class="row">
<br>
		    <?php 
				  if (!empty ($pod)){
					  ?>
			  <table border='1px'>
                <thead>
                <tr width="5%">
                   Airway Number :
				</tr>
                </thead>
				<tbody>
				<tr>
					<?=$info->pod_no?>
				</tr>
				</tbody>
			  </table>
              <table border='1px'>
                <thead class='thead'>
                <tr>
					
				</tr>
                </thead>
                <tbody>
                <tr>
                  <?php
				  foreach ($pod as $value) {
                  ?>
		  <!-- <td><?php// echo $value['image'];?></td>-->
				  <img src="<?php echo base_url()."admin/uploads/pod/".$value['image'];?>" width="200" height="200">
                </tr>
					<?php 
						}
                   ?>
                </tbody>
              </table>
			  <?php
			    }
				?>
						</div>
					</div>
				</div>
				</div>
				
			</div>
                      </div>
                    </div> 

                </div>
               
    </main>
    <!-- END: Content-->
    <!-- START: Footer-->
    <?php $this->load->view('user/admin_shared/admin_footer');
     //include('admin_shared/admin_footer.php'); ?>
    <!-- START: Footer-->
</body>
<!-- END: Body-->

