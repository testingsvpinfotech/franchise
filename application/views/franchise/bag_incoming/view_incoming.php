<?php $this->load->view('franchise/franchise_shared/franchise_header'); ?>
<!-- END Head-->
<style>
    .input:focus {
        outline: outline: aliceblue !important;
        border: 2px solid red !important;
        box-shadow: 2px #719ECE;
    }
</style>
<!-- END Head-->

<!-- START: Body-->

<body id="main-container" class="default">

    <!-- END: Main Menu-->
    <?php $this->load->view('franchise/franchise_shared/franchise_sidebar'); ?>
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
                                <h4 class="card-title">Incoming Bag</h4>
                                <span style="float: right;"><a href="<?= base_url('franchise_bag/add-incoming-bag'); ?>"
                                        class="fa fa-plus btn btn-primary">Add Incoming Bag</a></span>
                            </div>
                            <br>
                            <?php if ($this->session->flashdata('notify') != '') { ?>
                                <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored">
                                    <?php echo $this->session->flashdata('notify'); ?>
                                </div>
                                <?php unset($_SESSION['class']);
                                unset($_SESSION['notify']);
                            } ?>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table layout-primary bordered">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Bag Id</th>
                                                <th>Source Branch</th>
                                                <th>NOP</th>
                                                <th>Recived</th>
                                                <th>Missed</th>
                                                <th>Total Weight</th>
                                                <th>Date</th>
                                                <!-- <th>Action</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            if (!empty($allinword)) {

                                                //  print_r($allinword);
                                                $cnt = 1;
                                                foreach ($allinword as $value) {



                                                    $podno = $value['pod_no'];
                                                    $res = $this->db->query("select count(pod_no) as total from tbl_domestic_tracking where pod_no='$podno' and status='delivered'");
                                                    $total = $res->row()->total;
                                                    // echo $total;
                                                    if ($total == 0) {
                                                        ?>
                                                        <td>
                                                            <?php echo $cnt; ?>
                                                        </td>
                                                        <td><a href="viewBagIncoming/<?php echo $value['bag_id']; ?>"
                                                                style="color:green;">
                                                                <?php echo $value['bag_id']; ?>
                                                            </a></td>
                                                        <!-- <td><a href="add-incoming-bag/<?php echo $value['bag_id']; ?>" style="color:green;"><?php echo $value['bag_id']; ?></a></td> -->
                                                        <td>
                                                            <?php echo $value['source_branch']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $value['total_pcs']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $value['total_coming']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $value['total'] - $value['total_coming']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $value['total_weight']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $value['date_added']; ?>
                                                        </td>
                                                        <!-- <td><a href="master_franchise/add-incoming-bag/<?php echo $value['bag_no']; ?>"><i class="ion-edit" style="color:var(--primarycolor)"></i></a></td> -->
                                                        </tr>
                                                        <?php
                                                        $cnt++;
                                                    }
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
    <!-- END: Content-->
    <!-- START: Footer-->

    <?php $this->load->view('franchise/franchise_shared/franchise_footer');
    //include('admin_shared/admin_footer.php'); 
    ?>
    <!-- START: Footer-->
</body>
<!-- END: Body-->