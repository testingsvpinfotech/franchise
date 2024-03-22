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
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row p-2">
                            <div class="col-md-6">
                                <h6 class="">Payment Details</h6>
                            </div>

                        </div>
                        <hr>



                        <div class="table-responsive">
                            <table id="id1" class="display table  table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>AWB No</th>
                                        <th>Customer ID</th>
                                        <th>Debit Amoun</th>
                                        <th>Credit AMount </th>
                                        <th>Balance Amount</th>
                                        <th>Payment TYpe</th>
                                        <th>Collectable Amount</th>
                                        <th>Payment Date</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>23</td>
                                        <td>Customer</td>
                                        <td><a style="color: #4c66fb;" target="_blank" href="<?= base_url('franchise/show-order'); ?>">MN65</a></td>
                                        <td>Sep 29, 2022</td>
                                        <td><span data-toggle="tooltip" data-html="true" title="" data-original-title="documents()">documents()</span></td>
                                        <td>10</td>
                                        <td></td>
                                        <td>PREPAID</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- END: Card DATA-->
        </div>
    </main>

    <?php $this->load->view('franchise/franchise_shared/franchise_footer'); ?>