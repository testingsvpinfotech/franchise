<?php $this->load->view('franchise/franchise_shared/franchise_header'); ?>
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
    <?php $this->load->view('franchise/franchise_shared/franchise_sidebar');
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
                                <h4 class="card-title">Single Delivery Update</h4>
                            </div>
                            <div class="card-body">
                                <?php if ($this->session->flashdata('notify') != '') { ?>
                                    <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored"><?php echo $this->session->flashdata('notify'); ?></div>
                                <?php unset($_SESSION['class']);
                                    unset($_SESSION['notify']);
                                } ?>

                                <div class="col-md-12">
                                    <form action="<?= base_url('franchise/single-delivery-update'); ?>" method="post">
                                    <input type="submit" id="btn_search" name="submit" style="float: right;" value="Search">
                                    <input type="text" id="search_data" name="awb_no" placeholder="Enter AWB No" required style="float: right;">
                                        <select name="shipment" id="shipment" required style="float: right; height:24px;">
                                            <option value="">--Select--</option>
                                            <option value="international">International</option>
                                            <option value="Domestic" selected>Domestic</option>
                                        </select>
                                       
                                       
                                    </form>
                                    <br>
                                    <!--  col-sm-4-->
                                    <form action="<?= base_url('franchise/single-delivery-insert'); ?>" method="post">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Date&Times</th>
                                                    <th>AWB No.</th>
                                                    <th>Consignee</th>
                                                    <th>Status</th>
                                                    <th>Comments</th>
                                                    <th>Remark</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $count = 1; if (!empty($result)) { //print_r($result);
                                                    foreach ($result as $value) { 
                                                        $pod = $value['pod_no'];
                                                         $drs_date = $this->db->query("SELECT * FROM tbl_domestic_tracking WHERE status = 'Out For Delivery' AND pod_no ='$pod' ORDER BY id DESC LIMIT 1")->row('tracking_date');
                                                        
                                                        ?>
                                                        <tr>
                                                        <td><input type="datetime-local" class="form-control"  value="<?php echo Date('Y-m-d\TH:i',time()) ?>" required name="datetime"  id="mastermenifest_back" min="<?= date('Y-m-d H:i', strtotime($drs_date)); ?>" max="<?= date('Y-m-d').'T'.date('H:i'); ?>"></td>
                                                            <td><?= $value['pod_no']; ?><input type="hidden" name="pod" value="<?= $value['pod_no']; ?>"></td>
                                                            <td><?= $value['reciever_name']; ?> <input type="hidden" name="selected_dockets" value="<?= $value['booking_id']; ?>"><input type="hidden" name="company_type" value="<?= $value['company_type']; ?>"></td>
                                                            <td>
                                                                <select name="status" id="status" class="form-control">
                                                                
                                                                <option value="">Select Satus</option>
                                                                <option value="Delivered">Delivered</option>
                                                                <option value="Undelivered">Undelivered</option>
                                                                <option value="RTO">RTO</option>

                                                                </select>
                                                            </td>
                                                            <td><textarea name="comment" class="form-control" placeholder="Comment"></textarea></td>
                                                            <td><textarea name="remark" class="form-control" placeholder="Remark"></textarea></td>


                                                        </tr>
                                                <?php     }
                                                } ?>
                                            </tbody>

                                        </table>
                                        <input type="hidden" name="awb" value="<?= $value['pod_no']; ?>">
                                        <input type="hidden" name="branch_id" value="<?= $value['booking_id']; ?>">
                                        <input type="hidden" name="branch" value="<?= $country_details1->city; ?>">
                                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                    <!--  box body-->
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
    <!-- START: Footer-->
    <?php $this->load->view('franchise/franchise_shared/franchise_footer');
    //include('admin_shared/admin_footer.php'); 
    ?>
    <!-- START: Footer-->
</body>


<?php

function dateTimeValue($timeStamp)
{
    $date = date('d-m-Y', $timeStamp);
    $time = date('H:i:s', $timeStamp);
    return $date . 'T' . $time;
}

?>