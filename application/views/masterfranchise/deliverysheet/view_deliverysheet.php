<?php $this->load->view('masterfranchise/master_franchise_shared/admin_header.php'); ?>
<!-- END Head-->
<style>
    .input:focus {
        outline: outline: aliceblue !important;
        border: 2px solid red !important;
        box-shadow: 2px #719ECE;
    }
</style>
<!-- START: Body-->

<body id="main-container" class="default">


    <!-- END: Main Menu-->
    <?php $this->load->view('masterfranchise/master_franchise_shared/admin_sidebar.php');
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
                                <h4 class="card-title">Delivery Sheet</h4>
                                <span style="float: right;"><a href="<?= base_url('master-franchise/add-deliverysheet'); ?>" class="fa fa-plus btn btn-primary">Add Deliverysheet</a></span>
                            </div>
                            <?php if ($this->session->flashdata('notify') != '') { ?>
                                <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored">
                                    <?php echo $this->session->flashdata('notify'); ?>
                                </div>
                                <?php unset($_SESSION['class']);
                                unset($_SESSION['notify']);
                            } ?>
                            <br>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table layout-primary bordered">
                                        <thead>
                                            <tr>

                                                <th scope="col">ID</th>
                                                <th scope="col">DeliverySheet ID</th>
                                                <th scope="col">DeliveryBoy Name</th>
                                                <th scope="col">Total Docket</th>
                                                <!-- <th scope="col">Airway Number</th> -->
                                                <th scope="col">Total Deliverd</th>
                                                <th scope="col">Branch Name</th>
                                                <th scope="col">Date</th>
                                                <!-- <th scope="col">Action</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            if (!empty($allpod)) {
                                                foreach ($allpod as  $row) {  ?>
                                                    <tr>
                                                        <td><?php echo $row['id']; ?></td>
                                                        <td><a href="<?=base_url('master-franchise/print-deliverysheet/'.$row['deliverysheet_id']); ?>"><?php echo $row['deliverysheet_id']; ?></a></td>
                                                        <td><?php echo $row['deliveryboy_name']; ?></td>
                                                        <td><?php echo $row['total_count']; ?></td>
                                                        <!-- <td><?php echo $row['pod_no']; ?></td> -->
                                                        <!-- <td><?php echo $row['status']; ?></td> -->
                                                        <td></td>
                                                        <td><?php echo $row['branch_name']; ?></td>
                                                        <td><?php echo $row['delivery_date']; ?></td>
                                                        <!-- <td><a href="franchise/deliverysheet-detail/<?php echo $row['deliverysheet_id']; ?>" class="btn btn-flat btn-lg btn-labeled btn-primary"><i class="icon-info"></i></a></td> -->
                                                    </tr>
                                            <?php }
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
    <?php $this->load->view('masterfranchise/master_franchise_shared/admin_footer.php');
     //include('admin_shared/admin_footer.php'); ?>
    <!-- START: Footer-->
</body>
<!-- END: Body-->