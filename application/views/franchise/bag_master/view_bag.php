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
                                <h4 class="card-title">Franchise Bag</h4>
                                <span style="float: right;"><a href="<?= base_url('franchise/bag-add'); ?>"
                                        class="btn btn-primary">Add Bag</a></span>
                            </div>
                            <?php if ($this->session->flashdata('notify') != '') { ?>
                                <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored">
                                    <?php echo $this->session->flashdata('notify'); ?>
                                </div>
                                <?php unset($_SESSION['class']);
                                unset($_SESSION['notify']);
                            } ?>
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table id="example"
                                        class="display table dataTable table-striped table-bordered layout-primary"
                                        data-sorting="true">
                                        <thead>
                                            <tr>
                                                <th scope="col">Id</th>
                                                <th scope="col">Bag ID</th>
                                                <th scope="col">Source Origin</th>
                                                <th scope="col">Mode</th>
                                                <th scope="col">Pcs</th>
                                                <th scope="col">Weight</th>
                                                <th scope="col">Bag Date</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            if (!empty($allpod)) {
                                                $cnt = 1;
                                                foreach ($allpod as $ct) { ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $cnt; ?>
                                                        </td>
                                                        <td><a
                                                                href="<?= base_url('franchise/tracking-franchise-bag/' . $ct->bag_id); ?>">
                                                                <?php echo $ct->bag_id; ?>
                                                            </a></td>
                                                        <td>
                                                            <?php echo $ct->source_branch; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $ct->forwarder_mode; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $ct->total_pcs; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $ct->total_weight; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo date("d-m-Y", strtotime($ct->date_added)); ?>
                                                        </td>

                                                    </tr>
                                                    <?php
                                                    $cnt++;
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
    <?php $this->load->view('franchise/franchise_shared/franchise_footer');
    //include('admin_shared/admin_footer.php'); ?>
    <!-- START: Footer-->
</body>
<!-- END: Body-->