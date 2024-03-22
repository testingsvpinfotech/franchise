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
                              <h4 class="card-title">MIS Report</h4>
                          </div>
                          <div class="card-content">
                                <div class="card-body">
                                <div class="row">                                           
                                    <div class="col-12">
                                    <form role="role" action="<?php echo base_url();?>User_panel/report" method="post">
                    <div class="row">
							
							<div class="col-md-2">
								<div class="form-group">
								<label>From date</label> 
									<div class="input-group">
										
											<!-- <span class="input-group-addon"> --><!-- <i class="fa fa-user"></i></span> -->
										<input name="fromdate" type="date" required class="form-control" value="<?php  echo $from_date; ?>" >
									</div>
								</div>
							</div>
							<div class="col-md-2">
									<div class="form-group">
									<label>To date</label>
										<div class="input-group">
											 
										 <!-- <span class="input-group-addon"> --><!-- <i class="fa fa-user"></i></span> -->
											<input  name="todate" type="date" required class="form-control" value="<?php echo $to_date; ?>">
										</div>
									</div>
							</div>
							<div class="col-sm-2">
                                <label for="username">Type</label>
                                <select class="form-control" name="company_type">
                                      <option value="ALL"  <?php if($company_type=="ALL"){echo "selected";} ?>>ALL</option>                           
                                      <option value="International"  <?php if($company_type=="International"){echo "selected";} ?>>International</option>
                                      <option value="Domestic"  <?php if($company_type=="Domestic"){echo "selected";} ?>>Domestic</option>
                                </select>
                            </div>
						   <div class="col-md-2">
									<div class="form-group">
										<label>Status:</label>
										<select class="form-control"  name="status">
											<option value="ALL" <?php if($status=="ALL"){echo "selected";} ?> > ALL</option>
											<option value="0" <?php	if($status=="0"){echo "selected";} ?>>Pending</option>
											<option value="1" <?php if($status=="1"){echo "selected";} ?>>Delivered</option>
										</select>
									</div>
							</div>
						
                        <center>
                        <div class="col-md-2">
                            <label></label><br><br>
                            <input name="submit" type="submit" value="Submit" class="btn btn-primary ">
                         </div>   
                        </center>
						 </div>
						</form>
                                    </div>
                                </div>
                                </div>
                            </div>
                          <div class="card-body">
                             <div class="table-responsive">
                            <a href="<?php echo base_url();?>User_panel/mis_report_excel/<?php echo $from_date;?>/<?php echo $to_date;?>/<?php echo $status;?>/<?php echo $company_type;?>"><button type="button" class="btn btn-success"> <span>Excel</span></button></a>
                        <table class="table">
                                <thead>
                                    <tr>
                                       <th>Date</th>
                                       <th>AWB No</th>
                                       <th>Destination</th>
                                       <th>Receiver</th>
                                       <th>Doc/Non-doc</th>
                                       <th>Invoice No</th>
                                       <th>Invoice Amount</th>
                                       <th>Status</th>
                                    </tr>
                                </thead>
                            <tbody>
                            <tr><?php
                                 if (!empty ($alldata))
                                {
                                  foreach ($alldata as  $value) 
                                  {
                                  ?>
                                  <tr>
                                    <td><?php echo date("d-m-Y",strtotime($value['booking_date'])); ?></td>
                                    <td><?php echo $value['pod_no']; ?></td>
                                    <td><?php echo $value['country_name']; ?></td> 
                                    <td><?php echo $value['reciever_name']; ?></td>
                                    <td><?php echo $value['doc_nondoc']; ?></td>
                                    <td><?php echo $value['invoice_no']; ?></td>
                                    <td><?php echo $value['invoice_value']; ?></td>
                                    <td><?php echo $value['status']; ?></td>
                                </tr>
                                <?php
                                  }
                                }
                            ?>
                            <?php if (!empty ($alldata_d))
                            {
                                  foreach ($alldata_d as  $value_d) 
                                  {
                                      //echo "<pre>";print_r($value_d);
                                  ?>
                                  <tr>
                                    <td><?php echo date("d-m-Y",strtotime($value_d['booking_date'])); ?></td>
                                    <td><?php echo $value_d['pod_no']; ?></td>
                                    <td><?php echo $value_d['city']; ?></td> 
                                    <td><?php echo $value_d['reciever_name']; ?></td>
                                    <td><?php echo $value_d['doc_nondoc']; ?></td>
									<td><?php echo $value_d['invoice_no']; ?></td>
                                    <td><?php echo $value_d['invoice_value']; ?></td>
                                    <td><?php echo $value_d['status']; ?></td>
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
    <?php $this->load->view('user/admin_shared/admin_footer');
     //include('admin_shared/admin_footer.php'); ?>
    <!-- START: Footer-->
</body>
<!-- END: Body-->

