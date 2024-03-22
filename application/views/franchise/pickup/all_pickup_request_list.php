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
                                 
                                <?php if ($user_type == '16' || $user_type == '1' ) { ?>
                                    <div class="row mt-4">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <select class="form-control" id="branch_id" name="branch_id" required>
                                                    <option value="">Select Branch</option>
                                                    <?php if (!empty($branch_name)) { ?>
                                                        <?php foreach ($branch_name as $value) : ?>
                                                            <option value="<?php echo $value['branch_id']; ?>"><?php echo $value['branch_name']; ?></option>
                                                        <?php endforeach; ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-primary  update_all" data-url="Pickup_Request_Controller/assign_branch">Assign</button>
                                        </div>
                                    </div>
                                <?php } else { ?>


                                <?php //if ($user_type != '10' || $user_type != '1') { ?>
                                    <div style="margin:15px;">
                                        <form method="post" action="<?php echo base_url('Pickup_Request_Controller/prq_assign_pickupboy'); ?>">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Select Pickup Boy</label>
                                                        <select class="form-control" name="username" required>
                                                            <option value="">Select Pickup Boy</option>
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
                                                        <input type="datetime-local" class="form-control" name="pickup_boy_assigned_date" required>
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

                               


                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table layout-primary table-bordered">
                                            <thead>
                                                <tr style="background-color: #ef7f1a;">
                                                    <td></td>
                                                    <th scope="col">PRQ NUMBER</th>
                                                    <th scope="col">Pickup Date Time</th>
                                                    <th scope="col">Pickup Status</th>
                                                    <th scope="col">Customer Name</th>
                                                    <!-- <th scope="col">Docket Number</th> -->
                                                    <th scope="col">Branch Name</th>
                                                    <th>Docket</th>
                                                    <?php if ($user_type != '16') { ?>
                                                        <th scope="col">Pickup Boy</th>
                                                        <th scope="col">Assign Time</th>
                                                        <th>Status Closed By</th>
                                                        <th>Status</th>
                                                       

                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($show_prq_list)) {
                                                    foreach ($show_prq_list as $ct) {
                                                ?>
                                                        <tr class="odd gradeX">
                                                            <?php if (empty($ct['branch_id'])) { ?>
                                                                <td><input type="checkbox" class="sub_chk" data-id="<?php echo $ct['id']; ?>"></td>
                                                            <?php } else { ?> <td></td> <?php } ?>

                                                            <td class="view_detail" relid="<?php echo $ct['id'];  ?>" style="color:green;cursor: pointer;"><?php echo $ct['pickup_request_id']; ?></td>
                                                            <td><?php echo $ct['pickup_date']; ?></td>
                                                            <td><?php if ($ct['pickup_status'] == '0') { ?>
                                                                    <b style="color: white; border: 1px solid #ddd;border-radius: 100%;padding: 8px;background-color: #4bbb13;">pending</b>
                                                                <?php } elseif ($ct['pickup_status'] == '2') { ?>

                                                                    <b style="color: white; border: 1px solid #ddd;border-radius: 100%;padding: 8px;background-color: #dca708;">Assigned</b>

                                                                <?php } else { ?>
                                                                    <b style="color: white; border: 1px solid #ddd;border-radius: 100%;padding: 8px;background-color:#e1383b;">Close</b>
                                                                <?php  } ?>
                                                            </td>


                                                            <?php $customer_id = $ct['customer_id'];
                                                            $value = $this->db->query("select * from tbl_customers where customer_id = '$customer_id'")->row();
                                                            ?>
                                                            <td><?php echo $value->customer_name; ?></td>

                                                            <?php $branch_id = $ct['branch_id'];
                                                            $value1 = $this->db->query("select * from tbl_branch where branch_id = '$branch_id'")->row_array();
                                                            ?>
                                                            <td><?php echo $value1['branch_name']; ?></td>
  
                                                            <?php $pqno = $ct['pickup_request_id']; $d = $this->db->query("select pod_no from tbl_domestic_booking where prq_no = '$pqno'")->result_array();
                                                            if(!empty($d)){?>
                                                             <td class="show_docket_data" data-id="<?php echo $ct['pickup_request_id']; ?>" style="color:#e1383b">View Docket</td>
                                                            <?php }else{ ?><td></td><?php } ?>

                                                            <?php if ($user_type != '16') { ?>
                                                                <td><?php echo $ct['pickup_boy']; ?></td>
                                                                <td><?php echo $ct['pickup_boy_date_assigned']; ?></td>
                                                                <td>
                                                                    <?php $username =  $ct['status_closed_by'];
                                                                    $ddd = $this->db->query("select full_name from tbl_users where username ='$username' ")->row_array();
                                                                    echo $ddd['full_name']; ?>
                                                                </td>

                                                                <?php if ($ct['pickup_status'] == '1') { ?>
                                                                    <td><button class="btn btn-danger">Closed</button></td>
                                                                <?php  } else { ?>
                                                                    <td class=" btn1 change_status" data-id="<?php echo $ct['pickup_request_id']; ?>" style="cursor:pointer;">Close PRQ</td>
                                                                <?php } ?>

                                                            <?php } ?>
                                                           

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

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Listing-->
            </div>
    </main>
    <!-- END: Content-->
<style>
    @media (min-width: 576px){
.modal-dialog {
    max-width: 641px;
    margin: 1.75rem auto;
}}
</style>





     <div id="show_modal23" class="modal fade show_modal23" role="dialog" style="background: #000;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 style="font-size: 24px; color: #17919e; text-shadow: 1px 1px #ccc;"><i class="fa fa-folder"></i> Docket Details</h6>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered ">
                        <thead>
                           
                            <tr>
                                <th>Track</th>
                                <th>PRQ Number</th>
                                <th>Docket No</th>
                                <th>Origin Pincode</th>
                                <th>destination pincode</th>
                                <th>weight</th>
                            </tr>
                        </thead>

                        <tbody id="user">
                        
                            <!-- <tr>
                               <td><button class="btn btn-warning btn-sm">Track</button></td> 
                                <td id="pod_no"></td>
                                <td id="origin_pincode"></td>
                                <td id="destination_pincode"> </td>
                                <td id="weight"> </td>
                            </tr> -->

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger docket_close"><i class="fa fa-times"></i> Close</button>
                </div>
            </div>
        </div>
    </div>
   




    <!-- ************************************************************************************************************** -->



    <div id="show_modal" class="modal fade" role="dialog" style="background: #000;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 style="font-size: 24px; color: #17919e; text-shadow: 1px 1px #ccc;"><i class="fa fa-folder"></i> Pickup request Details</h3>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered ">
                        <tbody>   
                            <tr>
                                <td colspan="1">PRQ Number</td>
                                <td id="prq_number" colspan="3"></td>
                            </tr>
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
                                <td id="no_of_pack"> </td>
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
                        <input type="text" name="pickup_request_id" class="form-control" id="pickup_request_id" value="">

                        <?php $username = $this->session->userdata('userName'); ?>

                        <input type="hidden" name='status_closed_by' class="form-control" value="<?php echo $username;  ?>">
                        <input type="hidden" name='status' class="form-control" value="<?php echo $approved;  ?>">

                    </div>
                    <button type="submit" name="submit" class="btn btn-success m-2">Status Close</button>
            </div>
            </form>
        </div>
    </div>
    <!-- START: Footer-->
    <?php $this->load->view('admin/admin_shared/admin_footer');
    //include('admin_shared/admin_footer.php'); 
    ?>
    <!-- START: Footer-->
    <script type="text/javascript">
        $(document).ready(function() {

            $('#master').on('click', function(e) {
                if ($(this).is(':checked', true)) {
                    $(".sub_chk").prop('checked', true);
                } else {
                    $(".sub_chk").prop('checked', false);
                }
            });

            $('.update_all').on('click', function(e) {
                var branch_id = $('#branch_id :selected').val()
                var allVals = [];
                // console.log(allVals);
                $(".sub_chk:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                });

                if (allVals.length <= 0) {
                    alert("Please select row.");
                } else {

                    var check = confirm("Are you sure you want to Assign  this row?");
                    if (check == true) {

                        var join_selected_values = allVals.join(",");

                        $.ajax({
                            url: $(this).data('url'),
                            type: 'POST',
                            //  data: 'id='+join_selected_values+'branch_id='+branch_id,
                            data: {
                                id: join_selected_values,
                                branch_id: branch_id
                            },
                            success: function(data) {
                                console.log(data);
                                $(".sub_chk:checked").each(function() {
                                    $(this).parents("tr").remove();
                                });
                                alert("Item Update successfully.");
                            },
                            error: function(data) {
                                alert(data.responseText);
                            }
                        });

                        //   $.each(allVals, function( index, value ) {
                        //       $('table tr').filter("[data-row-id='" + value + "']").remove();
                        //   });
                    }
                }
            });
        });
    </script>


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

    <script>
        $(document).ready(function() {
            $(".show_docket_data").click(function() {
            var prq_no = $(this).attr('data-id'); 
            alert(prq_no);
            $.ajax({
                url: "<?php echo base_url(); ?>Pickup_Request_Controller/get_pickup_request",
                data: {
                    prq_no : prq_no
                },
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                   console.log(response);
                     var len = response.length;
                     for(var i=0; i<len; i++){
                var pod_no = response[i].pod_no;
                var prq_no = response[0].prq_no;
                var sender_pincode = response[i].sender_pincode;
                var reciever_pincode = response[i].reciever_pincode;
                var actual_weight = response[i].actual_weight;

                var tr_str = "<tr>" +
                    "<td align='center'><a href='<?php echo base_url();?>users/track_shipment/?pod_no=" + pod_no +" &submit=1' target='_blank' title='Track' class='ring-point'><i class='ion-radio-waves'></i></td></a>" +
                    "<td align='center'>" + prq_no + "</td>" +
                    "<td align='center'>" + pod_no + "</td>" +
                    "<td align='center'>" + sender_pincode + "</td>" +
                    "<td align='center'>" + reciever_pincode + "</td>" +
                    "<td align='center'>" + actual_weight + "</td>" +
                    "</tr>";
                  
                $("#user").append(tr_str);
                // $("#userTable tbody").append(tr_str);
            }
                     $("#prq_number").html(response['prq_no']);
                    // $("#pod_no").html(response['pod_no']);
                    // $("#origin_pincode").html(response['origin_pincode']);
                    // $("#destination_pincode").html(response['destination_pincode']);
                    // $("#weight").html(response['weight']);
                     $('#show_modal23').modal({
                            backdrop: 'static',
                            keyboard: true,
                            show: true
                        });

                  
               }
             });
             
               
            });

            $(".docket_close").click(function(){
                $(".show_modal23").modal('hide');
                window.location.reload();

            });

        });
    </script>

</body>
<!-- END: Body-->