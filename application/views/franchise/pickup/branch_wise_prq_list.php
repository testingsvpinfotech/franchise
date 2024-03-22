<?php $this->load->view('admin/admin_shared/admin_header'); ?>
<!-- END Head-->

<!-- START: Body-->

<body id="main-container" class="default">
    <style>
        .btn-group,
        .btn-group-vertical {
            position: relative;
            display: -ms-inline-flexbox;
            display: block;
            vertical-align: middle;
            /* background-color: #ddd; */
        }

        .btn-secondary {
            background-color: #e1383b;
            border-color: var(--secondary);
        }

        .table thead th {
            background-color: #ee7f1b;
            color: #fff;
        }
      td .btn1{
            padding: 10px;
    margin: 6px;
    text-align: center;
    border-radius: 9px;
    background-color: #007bff96;
    cursor: pointer;
        }
        .table.layout-primary tbody td {
    border: none;
    border-bottom: 1px solid #dee2e6 !important;
    padding: 0.7rem;
}
    </style>


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
                                <h4 class="card-title" style="color: #ef7f1a;">Pickup Requests List</h4>
                            </div>
                          
                            <div class="card-body">
                            <?php if (!empty($this->session->flashdata('msg'))) { ?>
                                <div class="alert alert-success" role="alert">
                                    <button type="button" class="close" data-dismiss="alert">X</button>
                                    <?php echo $this->session->flashdata('msg'); ?>
                                </div>
                            <?php } ?>


                                <?php $user_type = $this->session->userdata('userType'); ?>
                                <?php if ($user_type != '10') { ?>
                                    <div style="margin:15px;">
                                    <form method="post" action="<?php echo base_url('Pickup_Request_Controller/prq_assign_pickupboy');?>">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Select Delivery Boy</label>
                                                    <select class="form-control" name="username" required>
                                                        <option value="">Select Delivery Boy</option>
                                                        <?php if (!empty($pickup_boy)) { ?>
                                                            <?php foreach ($pickup_boy as $rows) : ?>
                                                                <option value="<?= $rows['username'] ?>">
                                                                    <?= $rows['full_name'] ?>--<?= $rows['username'] ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Date</label>
                                                    <input type="datetime-local" class="form-control" name="pickup_boy_assigned_date"required>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>PRQ Number</label>
                                                    <input type="text" class="form-control" name="pickup_request_no" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-1">
                                                    <button class="btn btn-primary mt-4" type="submit">Assign</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    </div>
                                <?php } ?>
                                <div class="table-responsive">
                                    <table id="example" class="table layout-primary table-bordered">
                                        <thead>
                                            <tr style="background-color: #ef7f1a;">
                                                <th scope="col">Id</th>
                                                <th scope="col">Ref no</th>
                                                <th scope="col">Pickup Date Time</th>
                                                <th scope="col">Pickup Status</th>
                                            
                                                <th scope="col">Customer Name</th>
                                                <th scope="col">Branch Name</th>
                                                <th scope="col">Pickup Boy</th>
                                                <th scope="col">pickup Boy Assign Time</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($show_prq_list)) {
                                               // print_r($show_prq_list);
                                               $i=1; foreach ($show_prq_list as $ct) {
                                                   
                                            ?>
                                                    <tr class="odd gradeX">
                                                        <td><?php echo $i++; ?></td>
                                                        <td class="view_detail" relid="<?php echo $ct['id'];  ?>" style="color:green;cursor: pointer;"><?php echo $ct['pickup_request_id']; ?></td>
                                                        <td><?php echo $ct['pickup_date']; ?></td>
                                                        <td><?php if ($ct['pickup_status'] == '0') {?>
                                                                 <b style = "color: white; border: 1px solid #ddd;border-radius: 100%;padding: 8px;background-color: #4bbb13;">pending</b>
                                                            <?php } elseif ($ct['pickup_status'] == '2') { ?>   
                                                        
                                                                 <b style = "color: white; border: 1px solid #ddd;border-radius: 100%;padding: 8px;background-color: #dca708;">Assigned</b>
                                                           
                                                            <?php } else { ?>
                                                                <b style = "color: white; border: 1px solid #ddd;border-radius: 100%;padding: 8px;background-color:#e1383b;">Close</b>
                                                          <?php  } ?>
                                                        </td>
                                                       
                                                        <?php $customer_id = $ct['customer_id'];
                                                        $value = $this->db->query("select * from tbl_customers where customer_id = '$customer_id'")->row();
                                                        ?>
                                                        <td><?php echo $value->customer_name; ?></td>
                                                        <?php $branch_id = $ct['branch_id'];
                                                        $value1 = $this->db->query("select * from tbl_branch where branch_id = '$branch_id'")->row();
                                                        ?>
                                                        <td><?php echo $value1->branch_name; ?></td>
                                                        <td><?php echo $ct['pickup_boy']; ?></td>
                                                        <td><?php echo $ct['pickup_boy_date_assigned']; ?></td>
                                                        <td class=" btn1 change_status"data-id="<?php echo $ct['pickup_request_id']; ?>">Close PRQ</td>


                                                    </tr>
                                            <?php
                                                }
                                            } else {
                                                echo "<p>No Data Found</p>";
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






    <div id="show_modal" class="modal fade" role="dialog" style="background: #000;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 style="font-size: 24px; color: #17919e; text-shadow: 1px 1px #ccc;"><i class="fa fa-folder"></i> Pickup request Details</h3>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered ">
                        <tbody>

                            <!--          
                  <tr><th>Shipper Name</th><td id="consigner_name"> </td> </tr>
                <tr><th>Shipper Address</th><td id="consigner_address1"></td></tr>
                <tr><th>Mobile No</th><td id="consigner_contact"> </td></tr>
               <tr><th>Email Id</th><td id="consigner_email"> </td></tr>
               <tr><th>Pincode</th><td id="pickup_pincode"> </td></tr>
               <tr><th>City</th><td id="city"> </td></tr>
               <tr><th>Type Of tde Package</th><td id="type_of_package"> </td></tr>
               <tr><th>No Of  Package</th><td id="no_of_pack"> </td></tr>
               <tr><th>Weight</th><td id="actual_weight"> </td></tr>           
              <tr><th>Destination Pincode</th><td id="destination_pincode"> </td></tr>
               <tr><th>Mode Name</th><td id="mode_name"> </td></tr>
               <tr><th>Pickup Date Time</th><td id="pickup_date"> </td></tr>
               <tr><th>Pickup Location</th><td id="pickup_location"> </td></tr>
               <tr><th>Instruction</th><td id="instruction"> </td></tr>
               <tr><th>Reccuring</th><td id="recurring_data"> </td></tr> -->
                            <tr><td colspan="1">PRQ Number</td><td id="prq_number"colspan="3"></td></tr>
                            <tr>
                                <th>Shipper Name</th>
                                <td id="consigner_name"> </td>
                                <th>Mobile No</th>
                                <td id="consigner_contact"> </td>
                            </tr>
                            <tr>
                                <th>Email Id</th>
                                <td id="consigner_email"> </td>
                                <th>Shipper Address</th>
                                <td id="consigner_address1"></td>
                            </tr>
                            <tr>
                                <th>Pincode</th>
                                <td id="pickup_pincode"> </td>
                                <th>City</th>
                                <td id="city"> </td>
                            </tr>
                            <tr>
                                <th>Type Of The Package</th>
                                <td id="type_of_package"></td>
                                <th>No Of Package</th>
                                <td id="no_of_pack" > </td>
                            </tr>
                            <tr>
                                <th>Pickup Location</th>
                                <td id="pickup_location"></td>
                                <th>Pickup Date Time</th>
                                <td id="pickup_date"> </td>
                            </tr>
                            <tr>
                                <th>Weight</th>
                                <td id="actual_weight"> </td>
                                <th>Mode Name</th>
                                <td id="mode_name"> </td>
                            </tr>
                            <tr>
                                <th>Destination Pincode</th>
                                <td id="destination_pincode" colspan="3"> </td>
                            </tr>
                            <tr>
                                <th>Instruction</th>
                                <td id="instruction" colspan="3"> </td>
                            </tr>
                            <tr>
                                <th>Reccuring</th>
                                <td id="recurring_data" colspan="3"> </td>
                            </tr>


                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                </div>
            </div>
        </div>
    </div>



<!-- ************************************************************************************************************ -->


        <div id="show_modal_data" class="modal fade" role="dialog" style="background: #000;">
            <div class="modal-dialog" style="margin-top: 137px;">
                <div class="modal-content">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <form action="<?php echo base_url(); ?>Pickup_Request_Controller/status_change_pickup" method="post">

                        <div class="modal-body">
                            <?php $approved = 1; ?>
                            <h4 style="margin-top: 50px;text-align: center;">Are you sure, do you Want to Close PRQ ?</h4>
                            <label>PRQ Number</label>
                            <input type="text" name="pickup_request_id" class="form-control"  id="pickup_request_id" value="">
                           
                            <input type="hidden" name='status' class="form-control"  value="<?php echo $approved;  ?>">

                        </div>
                        <button type="submit" name="submit" class="btn btn-success m-2">Status Close</button>
                </div>
                </form>
            </div>
        </div>





    <!-- END: Content-->
    <!-- START: Footer-->
    <?php $this->load->view('admin/admin_shared/admin_footer');
    //include('admin_shared/admin_footer.php'); 
    ?>

    <script type="text/javascript">
        $(document).ready(function() {


            $(".change_status").click(function() {
              
            var id = $(this).attr('data-id'); //get the attribute value
           // alert(id);

            $.ajax({
                url: "<?php echo base_url(); ?>Pickup_Request_Controller/get_pickup_id",
                data: {
                    id: id
                },
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // console.log(response);
                    $('#pickup_request_id').val(response.pickup_request_id); //hold the response in id and show on popup
                    $('#show_modal_data').modal({
                        backdrop: 'static',
                        keyboard: true,
                        show: true
                    });
                }
            });
        });





            $('.view_detail').click(function() {

                var id = $(this).attr('relid'); //get the attribute value

                $.ajax({
                    url: "<?php echo base_url(); ?>Pickup_Request_Controller/prq_list",
                    data: {
                        id: id
                    },
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        $('#consigner_name').html(response[0].consigner_name);
                        $('#consigner_address1').html(response[0].consigner_address1);
                        $('#pickup_location').html(response[0].pickup_location);
                        $('#pickup_pincode').html(response[0].pickup_pincode);
                        $('#consigner_email').html(response[0].consigner_email);
                        $('#consigner_contact').html(response[0].consigner_contact);
                        $('#city').html(response[0].city);
                        $('#pickup_date').html(response[0].pickup_date);
                        $('#recurring_data').html(response[0].recurring_data);
                        $('#destination_pincode').html(response[0].destination_pincode);
                        $('#type_of_package').html(response[0].type_of_package);
                        $('#no_of_pack').html(response[0].no_of_pack);
                        //$('#no_of_pack1').html(response[1].no_of_pack);
                        $('#actual_weight').html(response[0].actual_weight);
                        $('#prq_number').html(response[0].pickup_request_id);
                        $('#mode_name').html(response[0].mode_name);
                        $('#show_modal').modal({
                            backdrop: 'static',
                            keyboard: true,
                            show: true
                        });
                    }
                });
            });
    });
 
    </script>
    <!-- START: Footer-->
</body>
<!-- END: Body-->