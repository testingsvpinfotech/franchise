<?php $this->load->view('admin/admin_shared/admin_header'); ?>
<!-- END Head-->

<!-- START: Body-->

<body id="main-container" class="default">
    <style>
        .btn-group, .btn-group-vertical {
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
                                <div class="table-responsive">
                                    <table id="example" class="table layout-primary table-bordered">
                                        <thead>
                                            <tr style="background-color: #ef7f1a;">
                                                <th scope="col">Id</th>
                                                <th scope="col">Ref no</th>
                                                <th scope="col">Pickup Date Time</th>
                                                <th scope="col">Pickup Status</th>
                                                <th scope="col">Docket No</th>
                                                <th scope="col">Customer Name</th>
                                                <th scope="col">Branch Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($show_prq_list)) {
                                                foreach ($show_prq_list as $ct) {
                                            ?>
                                                    <tr class="odd gradeX">
                                                        <td><?php echo $ct['id']; ?></td>
                                                        <td><?php echo $ct['pickup_request_id']; ?></td>
                                                        <td><?php echo $ct['pickup_date']; ?></td>
                                                        <td><?php if($ct['pickup_status']=='0'){
                                                            echo 'pending';
                                                          }else{
                                                            echo 'close';
                                                          } ?>
                                                        </td>
                                                        <td><?php echo $ct['docket_no']; ?></td>
                                                        <?php  $customer_id = $ct['customer_id'];
                                                        $value = $this->db->query("select * from tbl_customers where customer_id = '$customer_id'")->row();
                                                        ?>
                                                       
                                                        <td><?php echo $value->customer_name; ?></td>
                                                        <?php  $branch_id = $ct['branch_id'];
                                                        $value = $this->db->query("select * from tbl_branch where branch_id = '$branch_id'")->row();
                                                        ?>
                                                        <td><?php echo $value->branch_name; ?></td>
                                                       
                                                    </tr>
                                            <?php
                                                }
                                            } else {?>
                                               <tr><td colspan="5" style="color:red">No Data Found</td></tr>
                                           <?php  }
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
    <?php $this->load->view('admin/admin_shared/admin_footer');
    //include('admin_shared/admin_footer.php'); 
    ?>
    <!-- START: Footer-->
</body>
<!-- END: Body-->