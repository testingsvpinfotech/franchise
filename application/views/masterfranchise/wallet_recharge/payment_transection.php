<?php $this->load->view('masterfranchise/master_franchise_shared/admin_header'); 
  $this->load->view('masterfranchise/master_franchise_shared/admin_sidebar'); ?>
<main>
  <div class="container-fluid site-width">
    <div class="row">      
      <div class="col-12 col-sm-12 mt-5">
        <div class="card">
          <div class="card-header justify-content-between align-items-center">
            <div class="row">
              <div class="col-5">
                <h4 class="card-title">Payment Transaction</h4>    
              </div>
            </div>
          </div><!-- CARD HEADER CLOSE --->
          <div class="card-body">            
                <div class="row"> 
              
                  <div class="col-12 mt-4">
                  <?php if ($this->session->flashdata('notify') != '') { ?>
                                        <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored"><?php echo $this->session->flashdata('notify'); ?></div>
                                    <?php unset($_SESSION['class']);
                                        unset($_SESSION['notify']);
                                    } ?>
                    <div class="box p-2" style="background: #27336a1a">
                      <!-- <div class="box-header"><h5>WEIGHT DETAILS</h5> </div> -->
                      <div class="box-body  table-responsive">
                        <table id="example1" class="table table-bordered">
                          <thead>
                          <tr>
                              <th colspan="8"></th>
                              <th colspan="3">Total Successful Recharge Amount : <?php if(!empty($tot)){echo $tot;}else{echo '0.00';} ?></th>
                              
                            </tr>
                            <tr>
                              <th>Sr. No.</th>
                              <th>Order No</th>
                              <th>Franchise Id</th>
                              <th>Franchise Name</th>
                              <th>Transaction ID</th>
                              <th>Transaction Amount</th>
                              <th>Transaction Status</th>
                              <th>Transaction Massage</th>
                              <th>Transaction Description</th>
                              <th>Transaction Time</th>
                              <th>Transaction Completed</th>
                              
                            </tr>
                          </thead>
                          <tbody>
                            <?php $i=1; if(!empty($trans)){
                              foreach ($trans as $value) {  ?>
                            <tr>
                              <td><?= $i++; ?></td>
                              <td><?= $value->order_id; ?></td>
                              <td><?= $value->customer_cid; ?></td>
                              <td><?= $value->customer_name; ?></td>
                              <td><?= $value->transection_id; ?></td>
                              <td><?= $value->amount; ?></td>
                              <td> <?php  if($value->status == '0'){ echo 'In Process';}
                              elseif($value->status == '1'){ echo 'Success';}
                              elseif($value->status == '2'){ echo 'Failed';}
                              else{ echo 'Incomplete/Cancelled';} ?></td>
                               <td><?= $value->response_message; ?></td>
                              <td><?= $value->response_description; ?></td>
                              <td><?= date('d-m-Y h:i A',strtotime($value->transection_time_stamp)); ?></td>
                              <td><?php if(!empty($value->response_time_stamp)){ ?><?= date('d-m-Y h:i A',strtotime($value->response_time_stamp)); ?> <?php } ?></td>
                            </tr>
                            <?php  }
                            }else{ ?>
                             <tr><td colspan="11" style="text-align:center;"><b>No Data Found</b></td></tr>
                             <?php }?>
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
<?php $this->load->view('masterfranchise/master_franchise_shared/admin_footer'); ?>