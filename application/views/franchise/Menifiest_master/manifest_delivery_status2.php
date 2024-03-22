<?php $this->load->view('franchise/franchise_shared/franchise_header'); ?>
     <!-- END Head-->

     <!-- START: Body-->

     <body id="main-container" class="default">

       <!-- END: Main Menu-->
       <?php $this->load->view('franchise/franchise_shared/franchise_sidebar');
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
                     <h4 class="card-title">Internal Tracking</h4>
                   </div>
                   <div class="card-body">
                     <div class="row"> 
                       <div class="col-12">
                         <form role="form" action="<?php echo base_url(); ?>franchise/view-internal-status" method="post" autocomplete="off">
                           <div class="form-row">
                             <div class="col-md-2">
                               <input type="text" class="form-control" name="filter_value" value="<?php if (!empty($_POST)) {
                                 echo $_POST['filter_value'];
                               } ?>"/>
                             </div>

                             <div class="col-sm-2">
                               <input type="submit" class="btn btn-primary" name="submit" value="Filter">
                               <a href="<?= base_url('franchise/view-internal-status');?>" class="btn btn-info">Reset</a>
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
                           <div class="row" id="div_transfer_rate" style="display:none;">


                           </div>
                         </div>
                         <!--//==============-->
                         <div class="card-body">
                           <div class="row">
                             <div class="col-12">
                               <div class="table-responsive">
                                 <table id="example1" class="table table-responsive ">
                                   <thead>
                                     <tr>

                                       <th>AWB</th>
                                       <th>AWB date</th>
                                       <th>Type</th>
                                       <th>EWay No</th>
                                       <th>Invoice No.</th>
                                       <th>Invoice Value</th>
                                       <th>Sender Name</th>
                                       <th>Receiver Name</th>
                                       <th>Receiver Address</th>
                                       <th>Destination</th>
                                       <th>Network</th>
                                       <th>ForwordingNo</th>
                                       <th>Mode</th>
                                       <th>TDate</th>
                                       <th>Status</th>
                                       <th>Comment</th>
                                       <th>Person</th>
                                       <!--<th>Remark</th>-->
                                     </tr>
                                   </thead>
                                   <tbody>
                                     <?php
                                      if (!empty($international_booking)) {
                                        foreach ($international_booking as $value) {
                                      ?>
                                         <tr>

                                           <td><?php echo $value['pod_no']; ?></td>
                                           <td><?php echo date('d/m/Y', strtotime($value['booking_date'])); ?></td>
                                           <!-- <td><input type="hidden" name="company_type[]" value="<?php echo $value['company_type']; ?>"></td> -->
                                           <!-- <td><?php echo $value_d['type_of_pack']; ?></td> -->
                                           <td><?php echo $value['company_type']; ?></td>
                                           <td><?php  echo $value['eway_no']; ?></td>
                                           <td><?php echo $value['invoice_no']; ?></td>
                                           <td><?php echo $value['invoice_value']; ?></td>


                                           <td><?php echo $value['sender_name']; ?></td>
                                           <td><?php echo $value['reciever_name']; ?></td>
                                           <td><?php echo $value['reciever_address']; ?></td>

                                           <td><?php echo $value['reciever_city']; ?></td>
                                           <td><?php echo $value['forworder_name']; ?></td>
                                           <td><?php echo $value['forwording_no']; ?></td>
                                           <td><?php echo $value['mode_dispatch']; ?></td>
                                           <td><?php echo date("d-m-Y", strtotime($value['tracking_date'])); ?></td>
                                           <td><?php echo $value['status']; ?></td>
                                           <td><?php echo $value['comment']; ?></td>
                                           <!--<td><?php //echo $value['remarks']; 
                                                    ?></td>-->
                                         </tr>
                                     <?php
                                        }
                                      }

                                      ?>
                                     <?php
                                      // print_r($domestic_booking);die;                                    
                                      if (!empty($domestic_booking)) {
                                        foreach ($domestic_booking as $value_d) {
                                          $customer_info        = $this->basic_operation_m->get_table_row('tbl_customers', array('customer_id' => $value_d['customer_id']));
                                          if (@$customer_info->access_status == 0) {
                                            $tracking_info = $this->basic_operation_m->get_query_row("SELECT * FROM tbl_domestic_deliverysheet WHERE pod_no ='" . $value_d['pod_no'] . "'");

                                      ?>
                                           <tr>

                                             <td><?php echo $value_d['pod_no']; ?></td>
                                             <td><?php echo date('d/m/Y', strtotime($value_d['booking_date'])); ?></td>
                                             <!-- <td><input type="hidden" name="company_type[]"  value="<?php echo $value_d['company_type']; ?>"></td> -->
                                             <td><?php echo $value_d['company_type']; ?></td>
                                             <td><?php ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL); echo $value_d['eway_no']; ?></td>

                                             <td><?php echo $value_d['invoice_no']; ?></td>
                                             <td><?php echo $value_d['invoice_value']; ?></td>



                                             <td><?php echo $value_d['sender_name']; ?></td>
                                             <td><?php echo $value_d['reciever_name']; ?></td>
                                             <td><?php echo $value_d['reciever_address']; ?></td>

                                             <td><?php echo $value_d['reciever_city']; ?></td>

                                             <td><?php echo $value_d['forworder_name']; ?></td>
                                             <td><?php echo $value_d['forwording_no']; ?></td>
                                             <td><?php echo $value_d['mode_dispatch']; ?></td>
                                             <td><?php echo date("d-m-Y", strtotime($value_d['tracking_date'])); ?></td>
                                             <td><?php echo $value_d['status']; ?></td>
                                             <td><?php echo $value_d['comment']; ?></td>
                                             <td><?php echo (isset($tracking_info->deliveryboy_name)) ? $tracking_info->deliveryboy_name : ''; ?></td>
                                             <!--<td><?php //echo $value_d['remarks']; 
                                                      ?></td>-->
                                           </tr>
                                       <?php
                                          }
                                        }
                                      } else {
                                        ?>
                                       <tr>
                                         <?php //echo str_repeat("<td></td>",12);
                                          ?>
                                       </tr>
                                     <?php
                                      }
                                      ?>
                                   </tbody>

                                 </table>
                               </div>


                               <div class="table-responsive">
                                 <br><br>
                                 <h3>Weight Details</h3>
                                 <table class="table">
                                   <tr>
                                     <td>Sr. No.</td>
                                     <td>NOP</td>
                                     <td>Actual Weight</td>
                                     <td>Chargable Weight</td>
                                     <td>Box</td>
                                     <td>Length</td>
                                     <td>Width</td>
                                     <td>Height</td>
                                     <td>V.W</td>
                                     <td>A.W</td>
                                   </tr>
                                   <?php if (!empty($weight_details)) { ?>
                                     <?php $i = 1;
                                      foreach ($weight_details as $value) : ?>

                                       <tr>
                                         <td><?= $i++; ?></td>
                                         <td><?= $value['no_of_pack']; ?></td>
                                         <td><?= $value['actual_weight']; ?></td>


                                         <td><?= $value['chargable_weight']; ?></td>

                                         <?php $weight_info    = $this->db->query("select * from tbl_domestic_weight_details where booking_id=" . $value['booking_id']);
                                          $weightt_info     = $weight_info->row();
                                          $weight_d = json_decode($weightt_info->weight_details, true);
                                          //print_r($weight_d);
                                          ?>

                                         <td style="width: 34px"><?php echo @$weight_d['per_box_weight_detail'][0]; ?></td>
                                         <td style="width: 34px"><?php echo @$weight_d['length_detail'][0]; ?></td>
                                         <td style="width: 34px"><?php echo @$weight_d['breath_detail'][0]; ?></td>
                                         <td style="width: 34px"><?php echo @$weight_d['height_detail'][0]; ?></td>
                                         <td style="width: 34px"><?php echo @$weight_d['valumetric_actual_detail'][0]; ?></td>
                                         <td style="width: 34px"><?php echo @$weight_d['valumetric_weight_detail'][0]; ?></td>

                                       </tr>
                                     <?php endforeach; ?>
                                     <?php $weight_info    = $this->db->query("select * from tbl_domestic_weight_details where booking_id=" . $value['booking_id']);
                                      $weightt_info     = $weight_info->row();
                                      $weight_d = json_decode($weightt_info->weight_details, true);
                                      //print_r($weight_d);
                                      ?>
                                     <tr>
                                       <td colspan="4"></td>
                                       <td style="width: 34px"><?php echo @$weight_d['per_box_weight_detail'][1]; ?></td>
                                       <td style="width: 34px"><?php echo @$weight_d['length_detail'][1]; ?></td>
                                       <td style="width: 34px"><?php echo @$weight_d['breath_detail'][1]; ?></td>
                                       <td style="width: 34px"><?php echo @$weight_d['height_detail'][1]; ?></td>
                                       <td style="width: 34px"><?php echo @$weight_d['valumetric_actual_detail'][1]; ?></td>
                                       <td style="width: 34px"><?php echo @$weight_d['valumetric_weight_detail'][1]; ?></td>
                                     </tr>

                                     <?php $weight_info    = $this->db->query("select * from tbl_domestic_weight_details where booking_id=" . $value['booking_id']);
                                      $weightt_info     = $weight_info->row();
                                      $weight_d = json_decode($weightt_info->weight_details, true);
                                      //print_r($weight_d);
                                      ?>
                                     <tr>
                                       <td colspan="4"></td>
                                       <td style="width: 34px"><?php echo @$weight_d['per_box_weight_detail'][2]; ?></td>
                                       <td style="width: 34px"><?php echo @$weight_d['length_detail'][2]; ?></td>
                                       <td style="width: 34px"><?php echo @$weight_d['breath_detail'][2]; ?></td>
                                       <td style="width: 34px"><?php echo @$weight_d['height_detail'][2]; ?></td>
                                       <td style="width: 34px"><?php echo @$weight_d['valumetric_actual_detail'][2]; ?></td>
                                       <td style="width: 34px"><?php echo @$weight_d['valumetric_weight_detail'][2]; ?></td>
                                     </tr>

                                     <?php $weight_info    = $this->db->query("select * from tbl_domestic_weight_details where booking_id=" . $value['booking_id']);
                                      $weightt_info     = $weight_info->row();
                                      $weight_d = json_decode($weightt_info->weight_details, true);
                                      //print_r($weight_d);
                                      ?>
                                     <tr>
                                       <td colspan="4"></td>
                                       <td style="width: 34px"><?php echo @$weight_d['per_box_weight_detail'][3]; ?></td>
                                       <td style="width: 34px"><?php echo @$weight_d['length_detail'][3]; ?></td>
                                       <td style="width: 34px"><?php echo @$weight_d['breath_detail'][3]; ?></td>
                                       <td style="width: 34px"><?php echo @$weight_d['height_detail'][3]; ?></td>
                                       <td style="width: 34px"><?php echo @$weight_d['valumetric_actual_detail'][3]; ?></td>
                                       <td style="width: 34px"><?php echo @$weight_d['valumetric_weight_detail'][3]; ?></td>
                                     </tr>

                                     <?php $weight_info    = $this->db->query("select * from tbl_domestic_weight_details where booking_id=" . $value['booking_id']);
                                      $weightt_info     = $weight_info->row();
                                      $weight_d = json_decode($weightt_info->weight_details, true);
                                      //print_r($weight_d);
                                      ?>
                                     <tr>
                                       <td colspan="4"></td>
                                       <td style="width: 34px"><?php echo @$weight_d['per_box_weight_detail'][4]; ?></td>
                                       <td style="width: 34px"><?php echo @$weight_d['length_detail'][4]; ?></td>
                                       <td style="width: 34px"><?php echo @$weight_d['breath_detail'][4]; ?></td>
                                       <td style="width: 34px"><?php echo @$weight_d['height_detail'][4]; ?></td>
                                       <td style="width: 34px"><?php echo @$weight_d['valumetric_actual_detail'][4]; ?></td>
                                       <td style="width: 34px"><?php echo @$weight_d['valumetric_weight_detail'][4]; ?></td>
                                     </tr>

                                   <?php } else { ?>
                                     <tr>
                                       <td>No Record Found</td>
                                     </tr>
                                   <?php } ?>
                                 </table>
                               </div>

                               <div class="table-responsive">
                                 <br>
                                 <br>
                                 <h3>Tracking Details</h3>
                                 <table class="table">
                                   <tr>
                                     <td>#</td>
                                     <td>Date</td>
                                     <td>Location</td>
                                     <td>Forworder</td>
                                     <td>comment</td>
                                     <td>Remarks</td>
                                     <td>Status</td>
                                   </tr>

                                   <?php
                                    if (!empty($history)) {

                                      foreach ($history as $key => $value) {

                                        // echo "<pre>";
                                        // print_r($value);
                                        // echo "</pre>";
                                        echo "<tr>";
                                        echo "  <td>" . ($key + 1) . "</td>";
                                        echo "  <td>" . date('Y-m-d', strtotime($value['tracking_date'])) . "</td>";
                                        echo "  <td>" . $value['branch_name'] . "</td>";
                                        echo "  <td>" . $value['forworder_name'] . "</td>";
                                        echo "  <td>" . $value['comment'] . "</td>";
                                        echo "  <td>" . $value['remarks'] . "</td>";
                                        echo "  <td>" . $value['status'] . "</td>";
                                        echo "</tr>";
                                      }
                                    } else {
                                      echo "<tr><td colspan='7'>No result Found!</td></tr>";
                                    }

                                    ?>
                                 </table>

                               </div>
                               </form>
                             </div>
                             </form>
                           </div>
                         </div>


                         <div class="table-responsive">
                           <br>
                           <br>
                           <h3>Tracking Manifest Details</h3>
                           <table class="table">
                             <tr>
                               <td>#</td>
                               <td>Date</td>
                               <td>Menifested By</td>
                               <td>Superviser</td>
                               <!-- <td>Receiver By</td> -->
                               <td>Received Date</td>
                               <td>Manifest No.</td>
                               <td>From</td>
                               <td>To</td>
                               <td>Lorry No</td>
                               <td>Driver</td>
                               <td>Contact No</td>
                               <!-- <td>Coloader</td>
                               <td>Forwarder Name</td> -->
                               <td>Received</td>
                             </tr>

                             <?php
                              if (!empty($menifest)) {

                                foreach ($menifest as $key => $value) {

                                  // echo "<pre>";
                                  // print_r($value);
                                  // echo "</pre>";
                                  if ($value['reciving_status'] == '1') {
                                    $value['reciving_status'] = 'Yes';
                                  } else {
                                    $value['reciving_status'] = 'No';
                                  }
                                  echo "<tr>";
                                  echo "  <td>" . ($key + 1) . "</td>";

                                  echo "  <td>" . date('Y-m-d', strtotime($value['date_added'])) . "</td>";
                                  echo "  <td>" . $value['username'] . "</td>";
                                  echo "  <td>" . $value['supervisor'] . "</td>";
                                  // echo "  <td>".$value['username']."</td>";
                                  echo "  <td>" . $value['date_added'] . "</td>";
                                  echo "  <td>" . $value['manifiest_id'] . "</td>";
                                  echo "  <td>" . $value['source_branch'] . "</td>";
                                  echo "  <td>" . $value['destination_branch'] . "</td>";
                                  echo "  <td>" . $value['lorry_no'] . "</td>";
                                  echo "  <td>" . $value['driver_name'] . "</td>";
                                  echo "  <td>" . $value['contact_no'] . "</td>";
                                  // echo "  <td>" . $value['coloader'] . "</td>";
                                  // echo "  <td>" . $value['coloder_contact'] . "</td>";
                                  echo "  <td>" . $value['reciving_status'] . "</td>";
                                  echo "</tr>";
                                }
                              } else {
                                echo "<tr><td colspan='11'>No result Found!</td></tr>";
                              }

                              ?>
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
       <?php $this->load->view('franchise/franchise_shared/franchise_footer');
     //include('admin_shared/admin_footer.php'); ?>
       <!-- START: Footer-->
     </body>
     <!-- END: Body-->
     <script type="text/javascript">
       $('.datepicker').datepicker({
         format: 'dd/mm/yyyy'
       });
       $(".check_all").click(function() {
         if ($(this).prop('checked')) {
           $(".row_check").prop('checked', true);
           show_div();
         } else {
           $(".row_check").prop('checked', false);
           hide_transfer_div();
         }
       });
       $(".row_check").click(function() {

         show_div();

       });


       function show_div() {
         // $("#transfer_customer_id").val(customer_id);

         $("#div_transfer_rate").show();
         return false;
       }

       function hide_transfer_div() {
         $("#div_transfer_rate").hide();
         return false;
       }
     </script>