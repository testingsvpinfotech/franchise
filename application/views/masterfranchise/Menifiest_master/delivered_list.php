<?php $this->load->view('admin/admin_shared/admin_header'); ?>
     <!-- END Head-->

     <!-- START: Body-->

     <body id="main-container" class="default">

       <!-- END: Main Menu-->
       <?php $this->load->view('admin/admin_shared/admin_sidebar');
        // include('admin_shared/admin_sidebar.php'); 
        ?>
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
                     <h4 class="card-title">Delivered Status</h4>
                   </div>
                 
                   <div class="card-body">
                     <div class="row">
                       <div class="col-12">
                        
                         <div class="card-body">
                           <div class="row">
                             <div class="col-12">
                               <div class="table-responsive">
                                 <table id="example1" class="display table dataTable table-striped table-bordered layout-primary" data-sorting="true">
                                   <thead>
                                     <tr>
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
                                     </tr>
                                   </thead>
                                   <tbody>
                                     <?php
                                      if (!empty($international_booking)) {
                                        foreach ($international_booking as $value) {
                                          if ($value_d['status'] == 'Delivered') {
                                      ?>
                                         <tr>
                                         
                                           <td><?php echo $value['pod_no']; ?></td>
                                           <td><?php echo date('d/m/Y', strtotime($value['booking_date'])); ?></td>
                                           <td><input type="hidden" name="company_type[]" value="<?php echo $value['company_type']; ?>">
                                             <?php echo $value['company_type']; ?></td>
                                           <td><?php echo $value['sender_name']; ?></td>
                                           <td><?php echo $value['reciever_name']; ?></td>
                                           <td><?php
                                                $where_cn = array('z_id' => $value['reciever_country_id']);
                                                $country_details = $this->basic_operation_m->get_table_row('zone_master', $where_cn);
                                                echo $country_details->country_name; ?></td>
                                           <td><?php echo $value['forworder_name']; ?></td>
                                           <td><?php echo $value['forwording_no']; ?></td>
                                           <td><?php echo $value['mode_name']; ?></td>
                                           <td><?php echo date("d-m-Y", strtotime($value['tracking_date'])); ?></td>
                                           <td><?php echo $value['status']; ?></td>
                                           <td><?php echo $value['comment']; ?></td>
                                         </tr>
                                     <?php
                                        } }
                                      }
                                      ?>
                                        
                                     <?php
                                      if (!empty($domestic_booking)) {
                                        foreach ($domestic_booking as $value_d) {
                                          if ($value_d['status'] == 'Delivered') {
                                            $customer_info        = $this->basic_operation_m->get_table_row('tbl_customers', array('customer_id' => $value_d['customer_id']));

                                      ?>

                                    
                                           <tr>
                                             <td><?php echo $value_d['pod_no']; ?></td>
                                             <td><?php echo date('d/m/Y', strtotime($value_d['booking_date'])); ?></td>
                                             <td><input type="hidden" name="company_type[]" value="<?php echo $value_d['company_type']; ?>">
                                             <?php echo $value_d['company_type']; ?></td>
                                             <td><?php echo $value_d['sender_name']; ?></td>
                                             <td><?php echo $value_d['reciever_name']; ?></td>
                                             <td><?php echo $value_d['city']; ?></td>
                                             <td><?php echo $value_d['forworder_name']; ?></td>
                                             <td><?php echo $value_d['forwording_no']; ?></td>
                                             <td><?php echo $value_d['mode_name']; ?></td>
                                             <td><?php echo date("d-m-Y", strtotime($value_d['tracking_date'])); ?></td>
                                             <td><?php echo $value_d['status']; ?></td>
                                             <td><?php echo $value_d['comment']; ?></td>
                                           </tr>
                                       <?php
                                            }
                                          }
                                        
                                      } else {
                                        ?>
                                       <tr>
                                         <?php echo str_repeat("<td></td>", 12); ?>
                                       </tr>
                                     <?php
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
                 </div>
               </div>
               <!-- END: Listing-->
             </div>
       </main>
       <!-- END: Content-->
       <!-- START: Footer-->
       <?php $this->load->view('admin/admin_shared/admin_footer');
        //include('admin_shared/admin_footer.php'); 
        ?>
       <!-- START: Footer-->
     </body>
  