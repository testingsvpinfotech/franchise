<?php $this->load->view('admin/admin_shared/admin_header'); ?>
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
                                <h4 class="card-title">Branch-In-Scan</h4>
                            </div>
                            <div class="card-body">
                                <?php if ($this->session->flashdata('notify') != '') { ?>
                                    <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored"><?php echo $this->session->flashdata('notify'); ?></div>
                                <?php unset($_SESSION['class']);
                                    unset($_SESSION['notify']);
                                } ?>

                                <div class="col-md-12">
                                    <form action="<?= base_url('admin/branch-in-scan'); ?>" method="post">
                                        <input type="text" id="search_data" name="awb_no" placeholder="Enter AWB No" required style="float: right;">
                                        <input type="button" id="btn_search" style="float: right;" value="Search">

                                        <br>
                                        <!--  col-sm-4-->

                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>AWB No.</th>
                                                    <th>Shipper Name </th>
                                                    <th>Consignee</th>
                                                    <th>Mode</th>
                                                    <th>Booking From</th>
                                                    <th>Destination</th>
                                                    <th>To Pay</th>
                                                    <th>Qty/Pcs</th>
                                                    <th>Actual Wt</th>
                                                    <th>Charged Wt</th>
                                                </tr>
                                            </thead>
                                            <tbody id="change_status_id">

                                            </tbody>

                                        </table>

                                    
                                    <!--  box body-->
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="box-footer pull right">
                                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                        </div>
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
    <!-- START: Footer-->
    <?php $this->load->view('admin/admin_shared/admin_footer');
    //include('admin_shared/admin_footer.php'); 
    ?>
    <!-- START: Footer-->
</body>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/jQueryScannerDetectionmaster/jquery.scannerdetection.js"></script>
<script>
    $(document).ready(function() {
        $("#btn_search").click(function() {

            //var awb_no=$(this).val();
            var forwording_no = $("#search_data").val();



            // console.log(all);

            if (forwording_no != "") {

                forwording_no = forwording_no.trim();

                var message = '';

                $("input[name='pod_no[]']").map(function() {
                    var numbers = $(this).val();

                    var number = numbers.split("|");

                    if (number[0] == forwording_no) {
                        message = 'This Forwording No Already Exist In The List!';
                        // return false;
                    }
                }).get();

                if (message != '') {
                    alert(message);
                    return false;
                }
                $.ajax({
                    url: "Admin_Inscan/branch_awb_scan",
                    type: 'POST',
                    dataType: "html",
                    data: {
                        forwording_no: forwording_no
                    },
                    success: function(data) {
                        console.log(data);
                        if (data != "") {
                            $("#change_status_id").prepend(data);
                            var array = [];

                            tw = 0;
                            tp = 0;

                            $("input.cb[type=checkbox]:checked").each(function() {

                                tw = tw + parseFloat($(this).attr("data-tw"));
                                tp = tp + parseFloat($(this).attr("data-tp"));

                            });

                            document.getElementById('total_weight').value = tw.toFixed(2);
                            document.getElementById('total_pcs').value = tp;
                            // $("#search_data").val('');
                           
                        } else {
                            $("#change_status_id").prepend('');
                        }
                        $("#search_data").focus();
                        $('#search_data').each(function(){
                            this.reset();
                        });    
                    }

                });

            } else {
                alert("Please enter Forwording no");
            }



        });
    });
</script>


<?php

function dateTimeValue($timeStamp)
{
    $date = date('d-m-Y', $timeStamp);
    $time = date('H:i:s', $timeStamp);
    return $date . 'T' . $time;
}

?>