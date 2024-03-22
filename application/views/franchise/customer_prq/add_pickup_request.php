<?php include(dirname(__FILE__).'/../franchise_shared/franchise_header.php'); ?>
<?php include(dirname(__FILE__).'/../franchise_shared/franchise_sidebar.php'); ?>
 <!-- START: Card Data-->
 <main>

        <div class="container-fluid site-width">
            <!-- START: Breadcrumbs-->
            <div>
                <div class="row">
                    <div class="col-12 col-md-12 mt-3">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Pick Up Request</h4>
                               
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h5> Today PRQ Booked</h5>
                                       
                                        <h6 style="color:#009846;"><?php echo $today_booking[0]['total_today_booking'];?></h6>
                                    </div>
                                    <div class="col-md-4">
                                        <h5>Pending</h5>
                                        <h6   style="color:#009846;"><?php echo $total_pending[0]['total_pending_shipment'];?></h6>
                                    </div>
                                    <div class="col-md-4">
                                        <h5>Successful</h5>
                                        <h6  style="color:#009846;"><?php echo $total_complete[0]['total_complete_shipment'];?></h6>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Breadcrumbs-->

            <!-- START: Card Data-->
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="row">
                        <div class="col-12 col-md-6 mt-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="wizard mb-4 ml-4 mr-4">
                                        <div class="connecting-line"></div>
                                        <ul class="nav nav-tabs d-flex mb-3">
                                            <!-- <li class="nav-item ">
                                                <a class="nav-link position-relative round-tab text-left p-0 active border-0" data-toggle="tab" href="#id1">
                                                    <i class="fas fa-address-card position-relative text-white h5 mb-3"></i>
                                                    <small class="d-none d-md-block ">Consignee Details</small>
                                                </a>
                                            </li> -->
                                            <li class="nav-item">
                                                <a class="nav-link position-relative round-tab text-sm-center text-left p-0 border-0" data-toggle="tab" href="#id1">
                                                    <i class="fa fa-truck position-relative text-white h5 mb-3"></i>
                                                    <small class="d-none d-md-block"></small>
                                                </a>
                                            </li>
                                            <li class="nav-item ml-auto">
                                                <a class="nav-link position-relative round-tab text-sm-right text-left p-0 border-0" data-toggle="tab" href="#id2">
                                                    <i class="fas fa-box-open position-relative text-white h5 mb-3"></i>
                                                    <small class="d-none d-md-block"></small>
                                                </a>
                                            </li>
                                            <li class="nav-item ml-auto">
                                                <a class="nav-link position-relative round-tab text-sm-right text-left p-0 border-0" data-toggle="tab" href="#id3">
                                                    <i class="fas fa-sticky-note position-relative text-white h5 mb-3"></i>
                                                    <small class="d-none d-md-block"></small>
                                                </a>
                                            </li>
                                            <!-- <li class="nav-item ml-auto">
                                                <a class="nav-link position-relative round-tab text-sm-right text-left p-0 border-0" data-toggle="tab" href="#id5">
                                                    <i class="icon-credit-card position-relative text-white h5 mb-3"></i>
                                                    <small class="d-none d-md-block">Payment And Reciept</small>
                                                </a>
                                            </li> -->
                                        </ul>
                                    </div>
                                </div>
                            </div>






                            <form method="post" class="" action="<?php echo base_url(); ?>Franchise_prq/pickup_request">
                                <div class="tab-content">
                                    <div class="tab-pane fade active show" id="id1">
                                        <div class="row">
                                            <div class="col-12 col-sm-12">
                                                <div class="card p-4 mt-4">
                                                    <div class="row  mb-4">

                                                    <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                                        <h4 class="card-title">Shipper Details</h4>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <label>Shipper Name <span class="text-danger">*</span></label>
                                                                                <div class="form-group">
                                                                                    <input name="consigner_name" type="text" class="form-control">

                                                                                    <input name="pickup_request_id" type="hidden" value="<?php echo $request_id; ?>" class="form-control">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label>Mobile No. <span class="text-danger">*</span></label>
                                                                                <div class="form-group">
                                                                                    <input name="consigner_contact" type="number" pattern="\d{10}" title="Please enter valid phone number" class="form-control check_phone_number" required>
                                                                                     <span id="lavel12" style="color:red;"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label>E-mail ID <span class="text-danger">*</span></label>
                                                                                <div class="form-group">
                                                                                    <input name="consigner_email" type="email" class="form-control" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label>Destination Address1 <span class="text-danger">*</span></label>
                                                                                <div class="form-group">
                                                                                    <input name="consigner_address1" type="text" class="form-control"required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label>Destination Address2</label>
                                                                                <div class="form-group">
                                                                                    <input name="consigner_address2" type="text" class="form-control">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label>Destination Address3</label>
                                                                                <div class="form-group">
                                                                                    <input name="consigner_address3" type="text" class="form-control">
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    <button type="button" class="btn float-right btn-primary nexttab">Next</button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>





                                    <!-- *******************************Mode Section Start tab-2 **************************************************** -->

                                    <div class="tab-pane fade" id="id2">
                                        <div class="row">
                                            <div class="col-12 col-sm-12">
                                                <div class="card p-4 mt-4">
                                                    <div class="row  mb-4">
                                                        <div class="card-header d-flex justify-content-between align-items-center">
                                                            <h4 class="card-title">Pickup Details</h4>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label> Pickup Pincode <span class="text-danger">*</span></label>
                                                                    <div class="form-group">
                                                                        <input name="pickup_pincode" type="text" class="form-control"  required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Destination Pincode <span class="text-danger">*</span></label>
                                                                    <div class="form-group">
                                                                        <input name="destination_pincode[]" type="text" class="form-control"  required>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <div class="col-md-12">
                                                                    <label>Pickup Location <span class="text-danger">*</span></label>
                                                                    <div class="form-group">
                                                                        <input name="pickup_location" type="text" class="form-control" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Pickup Date <span class="text-danger">*</span></label>
                                                                    <div class="form-group">
                                                                        <input type="date" name="pickup_date" id="inputdate" class="form-control" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Pickup Time <span class="text-danger">*</span></label>
                                                                    <div class="form-group">
                                                                        <select class="form-control" name ="pickup_time" required>
                                                                            <option value="">-Select Time-</option>
                                                                            <?php if (!empty($time_data)) { ?>
                                                                                <?php foreach ($time_data as  $t) : ?>
                                                                                    <option <?php echo $t->time; ?>><?php echo $t->time; ?></option>
                                                                                <?php endforeach; ?>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Destination City <span class="text-danger">*</span></label>
                                                                    <div class="form-group">
                                                                        <input name="city" type="text" class="form-control" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label>Instruction<span class="text-danger">*</span></label>
                                                                    <div class="form-group">
                                                                        <input type="text" name="instruction" class="form-control" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex">
                                                        <button type="button" class="btn btn-primary prevtab">Previous</button>
                                                        <button type="button" class="btn btn-primary nexttab ml-auto">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>








                                    <!-- *******************************Item Details tab-3 **************************************************** -->




                                    <div class="tab-pane fade" id="id3">
                                        <div class="form">
                                            <div class="row">
                                                <div class="col-12 col-md-12 mt-3">
                                                    <div class="card mb-4">
                                                        <div class="card-header d-flex justify-content-between align-items-center">
                                                            <h4 class="card-title">Package Details</h4>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label>Mode <span class="text-danger">*</span></label>
                                                                    <div class="form-group">
                                                                        <select class="form-control" name="mode_id" required>
                                                                            <option value="">Select Mode</option>
                                                                            <?php if (!empty($transfer_mode)) { ?>
                                                                                <?php foreach ($transfer_mode as $value) : ?>

                                                                                    <option value="<?php echo $value->transfer_mode_id; ?>"><?php echo $value->mode_name; ?></option>
                                                                                <?php endforeach; ?>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Actual Weight(kg) <span class="text-danger">*</span></label>
                                                                    <div class="form-group">
                                                                        <input name="actual_weight[]" type="text" class="form-control" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-3">
                                                                    <label>Type Of the Package <span class="text-danger">*</span></label>
                                                                        <select class="form-control" name="type_of_package[]" required>
                                                                            <option value="">Select Type Of the Package</option>
                                                                            <?php if (!empty($type_of_package)) { ?>
                                                                                <?php foreach ($type_of_package as $value) : ?>
                                                                                    <option value="<?php echo $value->partial_type; ?>"><?php echo $value->partial_type; ?> </option>
                                                                                <?php endforeach; ?>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-3">
                                                                    <label>No. of Packages<span class="text-danger">*</span></label>
                                                                        <input name="no_of_pack[]" type="text" class="form-control" required>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="d-flex mt-3">
                                                                <button type="button" class="btn btn-primary prevtab">Previous</button>
                                                                <button type="submit" name="submit" class="btn btn-primary nexttab ml-auto">Submit</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <!-- **********************************************************************************8 -->
                        <div class="col-12 col-md-6 mt-3">
                            <div class="row">
                                <div class="col-12 col-md-12 mt-3">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h4 class="card-title">Consignee Information</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table">
                                                        <tbody>

                                                            <tr style="background-color:#ddd;color:#333;">
                                                                <th>Request No.</th>
                                                                <th>From</th>
                                                                <th>To</th>
                                                                <th>Scheduled Pickup</th>
                                                            </tr>
                                                            <?php if (!empty($request_data)) { ?>
                                                                <?php foreach ($request_data as $value) : ?>
                                                                    <tr>
                                                                        <td><?php echo $value->pickup_request_id; ?></td>
                                                                        <td><?php echo $value->pickup_pincode; ?></td>
                                                                        <td><?php echo $value->destination_pincode; ?></td>
                                                                        <td><?php echo $value->pickup_date; ?></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            <?php } else { ?>
                                                                <tr>
                                                                    <td style="color:red" colspan="10">No data Found</td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                    <a href="<?php echo base_url('Franchise_prq/view_pickup_request');?>" class="btn btn-primary nexttab ml-auto">View All</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>
            </div>
        </div>
        </div>
   
    </main>

<?php include(dirname(__FILE__).'/../franchise_shared/franchise_footer.php'); ?>
    <!-- START: Footer-->
</body>
<!-- END: Body-->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
<?php if ($this->session->flashdata('flash_message')) : ?>
    <script>
        swal({
            title: "Done",
            text: "<?php echo $this->session->flashdata('flash_message'); ?>",
            timer: 1500,
            showConfirmButton: false,
            type: 'success'
        });
    </script>
<?php endif; ?>

<script>
    $(".check_phone_number").on("blur", function(){
        var mobNum = $(this).val();
        var filter = /^\d*(?:\.\d{1,2})?$/;

          if (filter.test(mobNum)) {
            if(mobNum.length==10){
             } else {
                // alert('Please put 10  digit mobile number');
                $('#lavel12').text('Please put 10  digit mobile number');
                return false;
              }
            }
            else {
                $('#lavel12').text('Please put 10  digit mobile number');
              return false;
           }
    
  });

  $(".pickup_pincode").on("blur", function(){
        var mobNum = $(this).val();
        var filter = /^\d*(?:\.\d{1,2})?$/;

          if (filter.test(mobNum)) {
            if(mobNum.length==10){
             } else {
                // alert('Please put 10  digit mobile number');
                $('#lavel12').text('Please Enter valid Pincode');
                return false;
              }
            }
            else {
                $('#lavel12').text('Please put 10  digit mobile number');
              return false;
           }
    
  });

  $(function() {
    var dtToday = new Date();

    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if (month < 10)
      month = '0' + month.toString();
    if (day < 10)
      day = '0' + day.toString();
    var maxDate = year + '-' + month + '-' + day;
    $('#inputdate').attr('min', maxDate);
  });
</script>

</html>