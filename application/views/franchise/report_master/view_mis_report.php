<?php include(dirname(__FILE__).'/../franchise_shared/franchise_header.php'); ?>
<?php include(dirname(__FILE__).'/../franchise_shared/franchise_sidebar.php'); ?>
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
                                    <form role="form" action="<?php echo base_url();?>franchise/list-mis-report" method="post" enctype="multipart/form-data">
                                        <div class="form-row">
                                             <div class="form-row">
                                                    <div class="col-sm-2">
                                                        <label for="username">Bill Type</label>
                                                        <select class="form-control" name="bill_type">
                                                              <option value="ALL" <?php echo (isset($post_data['bill_type']) && $post_data['bill_type'] == 'ALL')?'selected':''; ?>>ALL</option> 
                                                              <option value="PrePaid" <?php echo (isset($post_data['bill_type']) && $post_data['bill_type'] == 'PrePaid')?'selected':''; ?>>PrePaid</option>
                                                              <option value="COD" <?php echo (isset($post_data['bill_type']) && $post_data['bill_type'] == 'COD')?'selected':''; ?>>COD</option>
                                                        </select>
                                                    </div>
                                                     <div class="col-sm-2">
                                                          <label for="">From Date</label>                       
                                                          <input type="date" name="from_date" value="<?php echo (isset($post_data['from_date']))?$post_data['from_date']:''; ?>" id="from_date" autocomplete="off" class="form-control">
                                                    </div>
                                                     <div class="col-sm-2">
                                                       <label for="">To Date</label>
                                                      <input type="date" name="to_date" value="<?php echo (isset($post_data['to_date']))?$post_data['to_date']:''; ?>" id="to_date" autocomplete="off" class="form-control">   
                                                </div>
                                                <div class="col-sm-2">
                                                     <div class="form-group">
                                                        <label for="">Doc/Non-Doc</label>
                                                        <select class="form-control" name="doc_type">
                                                              <option value="ALL" <?php echo (isset($post_data['doc_type']) && $post_data['doc_type'] == 'ALL')?'selected':''; ?>>ALL</option>           
                                                              <option value="1" <?php echo (isset($post_data['doc_type']) && $post_data['doc_type'] == '1')?'selected':''; ?>>Non-Doc</option>
                                                              <option value="0" <?php echo (isset($post_data['doc_type']) && $post_data['doc_type'] == '0')?'selected':''; ?>>Doc</option>
                                                        </select>
                                                    </div>                  
                                                </div>
                                                 <div class="col-sm-2">
                                                        <label>Status</label>
                										<select class="form-control" name="status">
                											<option value="ALL" <?php echo (isset($post_data['status']) && $post_data['status'] == 'ALL')?'selected':''; ?>>ALL</option>
                											<option value="0" <?php echo (isset($post_data['status']) && $post_data['status'] == '0')?'selected':''; ?>>Pending</option>
                											<option value="1" <?php echo (isset($post_data['status']) && $post_data['status'] == '1')?'selected':''; ?>>Delivered</option>
                											<option value="2" <?php echo (isset($post_data['status']) && $post_data['status'] == '2')?'selected':''; ?>>RTO</option>
                										</select>
                                                    </div>
                                                     <div class="col-sm-2">
                                                        <label>AWB No</label>
                										<input type="text" class="form-control" value="<?php echo (isset($post_data['awb_no']))?$post_data['awb_no']:''; ?>" name="awb_no">
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <label for="username">Network</label>
                                                        <select class="form-control" name="courier_company" id="courier_company">
                                                            <option value="ALL" <?php echo (isset($post_data['courier_company']) && $post_data['courier_company'] == 'ALL')?'selected':''; ?>>ALL</option>
                                                            <?php foreach ($courier_company as $cc) { ?>   
                                                            <option value="<?php echo $cc['c_id']; ?>" <?php echo (isset($post_data['courier_company']) && $post_data['courier_company'] == $cc['c_id'])?'selected':''; ?>><?php echo $cc['c_company_name']; ?></option>
                                                          <?php  }  ?>
                                                        </select>
                                                    </div>
                                                <div class="col-sm-3">
                                                    <input type="submit" class="btn btn-primary" style="margin-top: 25px;" name="submit" value="Search"> 
                                                    <input type="submit" class="btn btn-primary" style="margin-top: 25px;" name="submit" value="Download Excel">
                                                    <a href="<?= base_url('franchise/list-mis-report'); ?>" style="margin-top: 25px;" class="btn btn-primary">Reset</a> 
                                                </div>
                                            </div>
                                         
                                        </div>
                                    </form>
                                    </div>
                                </div>
                                </div>
                            </div>
                          <div class="card-body">
                             <div class="table-responsive">
                            <table id="" class="display table table-striped table-bordered layout-primary" data-sorting="true">
                                <thead>
                                       <th scope='col'>SrNo</th>
                                       <th scope='col'>Date</th>
                                       <th scope='col'>AWB</th>
                                       <th scope='col'>Network</th>
                                       <th cope='col'>Type</th>
                                       <th scope='col'>FWD No.</th>
                                       <th scope='col'>Destination</th>
                                       <th scope='col'>Sender</th>
                                       <th scope='col'>Receiver</th>
                                       <th scope='col'>Receiver Addr</th>
                                       <th scope='col'>Receiver Pincode</th>
                                       <th scope="col">Franchise Name</th>
											                	<th scope="col">Master Franchise Name</th>
                                       <!-- <th scope='col'>Doc/Non-doc</th> -->
                                       <th scope='col'>Weight</th>
                                       <th scope='col'>Bill Type</th>
                                       <th scope='col'>NOP</th>
                                       <th scope='col'>Status</th>
                                       <th scope='col'>Delivery Date</th>
                                       <!-- <th scope='col'>EDD Date</th> -->
                                       <!-- <th scope='col'>TAT</th> -->
                                       <th scope='col'>Deliverd TO</th>
                                       <th scope='col'>RTO Date</th>
                                       <th scope='col'>RTO Reason</th>
                                       <th scope='col'>Branch</th>
                                    </tr>
                                </thead>
                                      <tbody>                                 
                                       <tr>
                                        <?php  ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);
                                        $i=$serial_no;
                                            if (!empty($international_allpoddata)) {
                                              
                                               foreach ($international_allpoddata as $value) 
											   {
												   
												    $rto_reason 	= '';
													$rto_date 	= '';
													$delivery_date 	= '';
													if(@$post_data['status'] == '2')
													{
														$rto_reason 	= $value['comment'];
														$rto_date 		= $value['tracking_date'];
														$value['status'] = $value['o_status'];
													}
													

                          if (!empty($value['delivery_date'])) {
                            $value['delivery_date'] = date('d-m-Y',strtotime($value['delivery_date']));
                          }
													

                                                // echo "<pre>";
                                                // print_r($value);exit();

                                                if ($value['status']=='shifted') {
                                                  $value['status'] = 'Intransit';
                                                }
                                                if ($value['company_type']=='Domestic') {
                                                  $value['company_type'] = 'DOM';
                                                }else{
                                                  $value['company_type'] = 'INT';
                                                }
                                              ?>
                                                <td style="width:20px;"><?php echo ($i+1); ?></td>
                                                <td style="width:40px;"><?php echo date('d-m-Y', strtotime($value['booking_date'])); ?></td>
                                                <td style="width:20px;"><?php echo $value['pod_no']; ?></td>
                                                <td style="width:20px;"><?php echo $value['forworder_name']; ?></td>
                                                <td style="width:20px;"><?php echo $value['company_type']; ?></td>
                                                <td style="width:20px;"><?php echo $value['forwording_no']; ?></td>
                                                <td style="width:20px;"><?php echo $value['country_name']; ?></td> 
                                                <td style="width:20px;"><?php echo $value['sender_name']; ?></td>
                                                <td style="width:20px;"><?php echo $value['reciever_name']; ?></td>
                                                <td style="width:20px;"><?php echo ''; ?></td>
                                                <td style="width:20px;"><?php echo ''; ?></td>
                                                <td style="width:20px;"><?php echo ''; ?></td>
                                                <td style="width:20px;"><?php echo ''; ?></td>
                                                <!-- <td style="width:20px;"><?php //  echo $value['doc_nondoc']; ?></td> -->
                                                <td style="width:20px;"><?php echo ($value['chargable_weight']); ?></td>
                                                <td style="width:20px;"><?php echo $value['dispatch_details']; ?></td>
                                                <td style="width:20px;"><?php echo $value['no_of_pack']; ?></td>
                                                <td style="width:20px;"><?php echo $value['status']; ?></td>
                                                <td style="width:20px;"><?php echo $delivery_date; ?></td>
                                                <td style="width:20px;"></td>
                                                <td style="width:20px;"></td>
                                                <td style="width:20px;"><?php echo $value['comment']; ?></td>
                                                <td style="width:20px;"><?php echo $rto_date; ?></td>
                                                <td style="width:20px;"><?php echo $rto_reason; ?></td>
                                                <td style="width:20px;"><?php echo $value['branch_name']; ?></td>
                                            </tr>
                                            <?php
											$i++;
                                        }
                                    } 
										if (!empty($domestic_allpoddata)) 
										{
                                             
                                               foreach ($domestic_allpoddata as $value_d) 
											   {


												    $tat 			= '';
												    $rto_reason 	= '';
													$rto_date 		= '';
													$delivery_date 	= '';
													if($value_d['status'] == 'RTO' || $value_d['status']=='Return to Orgin' || $value_d['status']=='Door Close' || $value_d['status']=='Address ok no search person' || $value_d['status']=='Address not found' || $value_d['status']=='No service' || $value_d['status']=='Refuse' || $value_d['status']=='Shifted' || $value_d['status']=='Wrong address' || $value_d['status']=='Person expired' || $value_d['status']=='Lost Intransit' || $value_d['status']=='Not collected by consignee' || $value_d['status']=='Delivery not attempted')
                          {
                            $rto_reason     = $value_d['comment'];
                            $rto_date       = $value_d['tracking_date'];
                            $value_d['status']  = $value_d['status'];
                          }
													else if($value_d['is_delhivery_complete'] == '1')
													{
														$delivery_date 		=  date('d-m-Y',strtotime($value_d['tracking_date']));
														$value_d['status'] 	= 'Delivered';
														
														$booking_date 		= $start = date('d-m-Y', strtotime($value_d['booking_date']));
														$start 				= date('d-m-Y', strtotime($value_d['booking_date']));
														$end 				= date('d-m-Y', strtotime($value_d['tracking_date']));
														$tat 				= ceil(abs(strtotime($start)-strtotime($end))/86400);
														
													}
													else
													{
                            // echo "<pre>";
                            // print_r($value_d);exit();
                            if ($value_d['status']=='shifted') {
                              $value_d['status'] = 'Intransit';
                            }
														
													}
                          if ($value_d['company_type']=='Domestic') {
                            $value_d['company_type'] = 'DOM';
                          }else{
                            $value_d['company_type'] = 'INT';
                          }

                          if (!empty($value_d['delivery_date'])) {
                            $value_d['delivery_date'] = date('d-m-Y',strtotime($value_d['delivery_date']));
                          }
                                              ?>
                                                <td style="width:20px;"><?php echo ($i+1); ?></td>
                                                <td style="width:40px;"><?php echo date('d-m-Y', strtotime($value_d['booking_date'])); ?></td>
                                                <td style="width:20px;"><?php echo $value_d['pod_no']; ?></td>
                                                <td style="width:20px;"><?php echo $value_d['forworder_name']; ?></td>
                                                <td style="width:20px;"><?php echo $value_d['company_type']; ?></td>
                                                <td style="width:20px;"><?php echo $value_d['forwording_no']; ?></td>
                                                <td style="width:20px;"><?php echo $value_d['city']; ?></td>  
                                                <td style="width:20px;"><?php echo $value_d['sender_name']; ?></td>
                                                <td style="width:20px;"><?php echo $value_d['reciever_name']; ?></td>
                                                <td style="width:20px;"><?php echo $value_d['city']; ?></td>
                                                <td style="width:20px;"><?php echo $value_d['reciever_pincode']; ?></td>

                                                 
                           <?php
														$pod = $value_d['pod_no'];
														$customer_id = $value_d['customer_id'];
                                                        $getfranchise= array();
            $getMasterfranchise= array();
                                                        if($value_d['user_type']==2){
    														 $getfranchise = $this->db->query("select tbl_customers.customer_name from tbl_domestic_booking left join tbl_customers ON tbl_customers.customer_id = tbl_domestic_booking.customer_id where customer_type = 2 AND pod_no ='$pod'")->result_array(); 
    														 $getMasterfranchise = $this->db->query("select tbl_customers.customer_name from tbl_domestic_booking left join tbl_customers ON tbl_customers.parent_cust_id = tbl_domestic_booking.customer_id where parent_cust_id = '$customer_id' AND pod_no ='$pod'")->result_array(); 
                                                        }
														 
														 ?>

														<td><?php echo @$getfranchise[0]['customer_name'] ;?></td>
														<td><?php echo @$getMasterfranchise[0]['customer_name'] ;?></td>


                                                <!-- <td style="width:20px;"><?php //echo $value_d['doc_nondoc']; ?></td> -->
                                                <td style="width:20px;"><?php echo ($value_d['chargable_weight']); ?></td>
                                                <td style="width:20px;"><?php echo $value_d['dispatch_details']; ?></td>
                                                <td style="width:20px;"><?php echo $value_d['no_of_pack'];?></td>
                                                <td style="width:20px;"><?php echo $value_d['status'];?></td>
												                        <td style="width:20px;"><?php echo $delivery_date; ?></td>
												                        <!-- <td style="width:20px;"><?php echo $value_d['delivery_date']; ?></td>
												                        <td style="width:20px;"><?php echo $tat; ?></td> -->
                                                <td style="width:20px;"><?php echo $value_d['comment']; ?></td>
                                                <td style="width:20px;"><?php echo $rto_date; ?></td>
                                                <td style="width:20px;"><?php echo $rto_reason; ?></td>
                                                <td style="width:20px;"><?php echo $value_d['branch_name']; ?></td>
                                            </tr>
                                            <?php
											 $i++;
                                        }
                                    }
                                    else {
                                        echo "<p>No Data Found</p>";
                                    }
                                  
                                    ?>
                        
                              </table> 
                          </div>

                          <div class="row">
                            <div class="col-md-6">
                                <?php echo $this->pagination->create_links(); ?>
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
    <?php include(dirname(__FILE__).'/../franchise_shared/franchise_footer.php'); ?>
    <!-- START: Footer-->
</body>
<!-- END: Body-->

