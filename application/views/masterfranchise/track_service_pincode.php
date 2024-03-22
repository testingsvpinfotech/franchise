<?php include('master_franchise_shared/admin_header.php'); ?>
<?php include('master_franchise_shared/admin_sidebar.php'); ?>
<main>
  <div class="container-fluid site-width">
    <div class="row">      
      <div class="col-12 col-sm-12 mt-5">
        <div class="card">
          <div class="card-header justify-content-between align-items-center">
            <div class="row">
              <div class="col-5">
                <h4 class="card-title">PINCODE TRACKING</h4>    
              </div>
              <div class="col-7 text-right">
                <form role="form" action="<?php echo base_url(); ?>master-franchise/pincode-track" method="GET" autocomplete="off">
                  <div class="form-row">
                    <div class="col-md-5"></div>
                    <div class="col-md-5">
                      <input type="text" class="form-control" name="pincode" value="<?= isset($_GET['pincode'])?$_GET['pincode']:''; ?>" style="background: #fff" />
                    </div>
                    <div class="col-sm-1">
                      <input type="submit" class="btn btn-info" name="filter" value="Filter">
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div><!-- CARD HEADER CLOSE --->
          <div class="card-body">            
                <div class="row"> 
                 
                  <div class="col-12 mt-4">
                    <div class="box p-2" style="background: #27336a1a">
                      <!-- <div class="box-header"><h5>WEIGHT DETAILS</h5> </div> -->
                      <div class="box-body  table-responsive">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th>Sr. No.</th>
                              <th>PINCODE</th>
                              <th>BRANCH</th>
                              <th>STATE</th>
                              <th>CITY</th>
                              <th>SERVICE</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php $i=1; if(!empty($pincode)){
                              foreach ($pincode as $value) {  ?>
                            <tr>
                              <td><?= $i++; ?></td>
                              <td><?= $value->pincode; ?></td>
                              <td><?= $value->branch_name; ?></td>
                              <td><?= $value->state; ?></td>
                              <td><?= $value->city; ?></td>
                              <td><?= service_type[$value->isODA]; ?></td>
                            </tr>
                            <?php  }
                            } ?>
                          </tbody>
                        </table>
                      </div>
                    </div><!-- BOX END --->
                  </div><!-- WEIGHT DETAILS CLOSE --->

                
                </div>
          </div><!-- CARD BODY CLOSE --->
        </div><!-- CARD CLOSE --->
      </div>                  
    </div>
  </div>
</main>
<?php include('master_franchise_shared/admin_footer.php'); ?>