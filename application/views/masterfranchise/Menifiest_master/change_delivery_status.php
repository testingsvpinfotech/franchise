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
                              <h4 class="card-title">Update Status</h4>
                          </div>
                          <div class="card-body">
                            <div class="row">                                           
                          <div class="col-12">
                              <form role="form" action="<?php echo base_url(); ?>admin/view-delivery-status" method="post" autocomplete="off">
                                             <div class="form-row">
												<div class="col-md-2">
													<input type="text" class="form-control" name="filter_value" />
												</div>
												<div class="col-md-2">
													<select class="form-control" name="filter">
														<option selected disabled>Select Filter</option>
														<option value="pod_no" >Pod No</option>
														<option value="forwording_no" >Forwording No</option>
														<option value="sender" >Sender</option>
														<option value="receiver" >Receiver</option>
														<option value="mode" >Mode</option>
														<option value="receiver_city" >Receiver City</option>
													</select>
												</div>
											<!--	<div class="col-md-2">
													<select class="form-control" name="user_id" id="user_id">
													<option value="" >Selecte Customer</option><?php //if(!empty($customers_list)){foreach($customers_list as $key => $values){ ?><option value="<?php //echo $values['customer_id']; ?>" ><?php // echo $values['customer_name']; ?></option><?php // } } ?></select>
												</div>  --->
												 
													
												 
												  <div class="col-sm-1">
													  <input type="date" name="from_date" id="from_date" autocomplete="off" class="form-control">
												</div>
												 

												 
												 <div class="col-sm-1">
												  <input type="date" name="to_date" id="to_date" autocomplete="off" class="form-control">   
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="submit" class="btn btn-primary" name="submit" value="Filter"> 
													<a href="admin/view-delivery-status" class="btn btn-info">Reset</a>
                                                </div>
                                            </div>
                                    </form>
                            </div>
                    </div>
                </div>   
                <div class="card-body">
                            <div class="row">                                           
                          <div class="col-12">
                              <!--<form role="form" action="<?php echo base_url(); ?>admin/list-booking" method="post" autocomplete="off">-->

                                <?php 
                                $date = date('Y-m-d H:i');
                                $date = str_replace(' ', 'T', $date);
                                ?>
                                    <div class="form-row">
                                           <div class="row" id="div_transfer_rate" style="display:none;" >   
                                                <div class="col-12">                            
                                                  <form role="form" action="admin/change-delivery-status" method="post" enctype="multipart/form-data">
                                                    <div class="box-body border">   
                                                    <h6><b>Change Delivery Status</b><a href="" onclick="return hide_transfer_div()"  ><i class="ion-close-circled"></i></a></h6>              
                                                     <div class="form-group row">  
                                                           <label class="col-sm-1">Date</label>
                                                            <div class="col-sm-2" style="max-width: unset;">
                                                            <input type="datetime-local" class="form-control" name="tracking_date" id="tracking_date" value="<?php echo $date;?>" pattern="MM-DD-YYYY hh:mm" >
                                                           </div>                                     
                                                            <label class="">Status</label>
                                                            <div class="col-sm-2">
                                                             <select name="status" id="status"  class="form-control">
															<?php if(!empty($all_status))
															{
                                
																foreach($all_status as $key => $values)
																{     ?>
																
																 <option value="<?php echo $values['status']; ?>"><?php echo $values['status']; ?></option>
																<?php 
																} 
															} ?>
                                                               
                                                             </select>
                                                          </div>  
                                                          <label class="col-sm-1">Comment</label>
                                                            <div class="col-sm-2" class="form-control">
                                                            <textarea name="comment"></textarea>
                                                          </div> 
                                                           <label class="col-sm-1">Remark</label>
                                                            <div class="col-sm-2" class="form-control">
                                                            <textarea name="remarks"></textarea>
                                                          </div>   
                                                          <div class="col-sm-1">                                        
                                                             <input type="submit" class="btn btn-primary" name="submit" id="submit_transfer">
                                                          </div>          
                                                      </div>       
                                                  </div> 
                              
                            </div>
                        
                    </div>
                </div>
                <!--//==============-->
                <div class="card-body">
                            <div class="row">                                           
                          <div class="col-12">
						 <div class="table-responsive">
                                <table id="example1" class="display table dataTable table-striped table-bordered layout-primary" data-sorting="true">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" value="all" name="all_docket" class="check_all" /></th>
                                        <th>AWB</th>
                                        <th>AWB date</th>
                                        <th>Type</th>
                                        <th>Sender Name</th>
                                        <th>Receiver Name</th>
                                        <th>Destination</th>
                                        <th>Network</th>
										<th>ForwordingNo</th>
                                        <th>Mode</th>
                                        <th>TDate</th>
                                        <th>Status</th>
                                        <th>Comment</th>
                                        <!--<th>Remark</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                 <?php                                        
                                        if (!empty($international_booking)) {
                                            foreach ($international_booking as $value) {
                                                ?>
                                       <tr>
                                         <td>
                                            <input type="checkbox" name="selected_dockets[]" value="<?php echo $value['booking_id']; ?>"  class="row_check" />
                                            <input type="hidden" name="customer_id" value="<?php echo $value['customer_id']; ?>">
                                          </td>
                                          <td><?php echo $value['pod_no']; ?></td>
                                          <td><?php echo date('d/m/Y', strtotime($value['booking_date'])); ?></td>
                                          <td><input type="hidden" name="company_type[]" value="<?php echo $value['company_type']; ?>">
                                          <?php echo $value['company_type']; ?></td>
                                          <td><?php echo $value['sender_name']; ?></td>
                                          <td><?php echo $value['reciever_name']; ?></td>
                                          <td><?php 
                                                 $where_cn =array('z_id'=>$value['reciever_country_id']);
                            		             $country_details = $this->basic_operation_m->get_table_row('zone_master',$where_cn);
                                                echo $country_details->country_name; ?></td>
                                          <td><?php echo $value['forworder_name']; ?></td>
										  <td><?php echo $value['forwording_no']; ?></td>
                                          <td><?php echo $value['mode_name']; ?></td>
                                          <td><?php echo date("d-m-Y",strtotime($value['tracking_date'])); ?></td>
                                          <td><?php echo $value['status']; ?></td>
                                          <td><?php echo $value['comment']; ?></td>
                                          <!--<td><?php //echo $value['remarks']; ?></td>-->
                                      </tr>
                                            <?php
                                        }
                                    } 
                                    ?>
                                        <?php                                        
                                        if (!empty($domestic_booking)) {
                                            foreach ($domestic_booking as $value_d) 
											{
												if($value_d['status'] == 'Out For Delivery' || $value_d['status'] == 'In-scan' ) {
												$customer_info				= $this->basic_operation_m->get_table_row('tbl_customers',array('customer_id'=>$value_d['customer_id']));

                        // print_r($value_d);
												
												//  if($customer_info->access_status == 0)
												// {
                                                ?>
                                          <tr>
                                                <td>
                                                  <input type="checkbox" name="selected_dockets[]" value="<?php echo $value_d['booking_id']; ?>"  class="row_check" />
                                                  <input type="hidden" name="customer_id" value="<?php echo $value_d['customer_id']; ?>">
                                                </td>
                                                <td><?php echo $value_d['pod_no']; ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($value_d['booking_date'])); ?></td>
                                                <td><input type="hidden" name="company_type[]"  value="<?php echo $value_d['company_type']; ?>">
                                                <?php echo $value_d['company_type']; ?></td>
                                                <td><?php echo $value_d['sender_name']; ?></td>
                                                <td><?php echo $value_d['reciever_name']; ?></td>
                                                <td><?php 
                                                // $where_c =array('id'=>$value_d['reciever_city']);
                            		            // $city_details = $this->basic_operation_m->get_table_row('city',$where_c);
                                                echo $value_d['city']; ?></td>
                                                <td><?php echo $value_d['forworder_name']; ?></td>
												                        <td><?php echo $value_d['forwording_no']; ?></td>
                                                <td><?php echo $value_d['mode_name']; ?></td>
                                                <td><?php echo date("d-m-Y",strtotime($value_d['tracking_date'])); ?></td>
                                                <td><?php  echo $value_d['status']; ?></td>
                                                <td><?php echo $value_d['comment']; ?></td>
                                                <!--<td><?php //echo $value_d['remarks']; ?></td>-->
                                          </tr>
                                            <?php
                                        // }
											}}
                                    } else {
                                        ?>
											<tr>
											<?php echo str_repeat("<td></td>",12);?>
											</tr>
										<?php
                                    }
                                    ?>
                                </tbody>

                            </table>
							</div>
                                    </form> 
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
<!-- END: Content-->
        <!-- START: Footer-->
        <?php $this->load->view('admin/admin_shared/admin_footer');
         //include('admin_shared/admin_footer.php'); ?>
        <!-- START: Footer-->
    </body>
    <!-- END: Body-->
<script type="text/javascript">
  
  $('.datepicker').datepicker({
      format : 'dd/mm/yyyy' 
    });
  $(".check_all").click(function(){
      if($(this).prop('checked'))
      {
        $(".row_check").prop('checked', true);
        show_div();
      }
      else
      {
        $(".row_check").prop('checked', false);
        hide_transfer_div();
      }
    });
    $(".row_check").click(function(){
        
        show_div();
        
    });
        
        
   function show_div()
  {
     // $("#transfer_customer_id").val(customer_id);

      $("#div_transfer_rate").show();  
      return false; 
  }
    function hide_transfer_div()
  {
      $("#div_transfer_rate").hide();  
      return false; 
  }
    
</script>