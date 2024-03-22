<?php include(dirname(__FILE__).'/../franchise_shared/franchise_header.php'); ?>
<?php include(dirname(__FILE__).'/../franchise_shared/franchise_sidebar.php'); ?>
<main>
    
<!-- START: Card Data-->
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-body">
                <div class="row p-2">
                    <div class="col-md-6">
                        <h6 class="">Billing - B2B Pricing</h6>
                    </div>
                </div>

               <?php  include(dirname(__FILE__).'/../billing_master/billing_tab.php'); ?>

                <hr>

               
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="card-body">
                                    <div class="row m-b-20">
                                        <div class="col-sm-3 text-center border-right">
                                            <h5>Remitted Till Date</h5>
                                            <h5><b>₹</b>2000</h5>
                                        </div>
                                        <div class="col-sm-3 text-center border-right">
                                            <h5>Last Remittance</h5>
                                            <h5><b>₹</b>2000</h5>
                                        </div>
                                        <div class="col-sm-3 text-center border-right">
                                            <h5>Next Remittance<small>(Expected)</small></h5>
                                            <h5><b>₹</b>0</h5>
                                        </div>
                                        <div class="col-sm-3 text-center">
                                            <h5>Total Remittance Due</h5>
                                            <h5><b>₹</b>0</h5>
                                        </div>
                                    </div>

                                    <table class="table table-sm mt-4 ">
                                        <thead>
                                            <tr style="background-color:#dddc;">
                                                <th>#</th>
                                                <th>Remittance ID#</th>
                                                <th>COD Amount</th>
                                                <th>Status</th>
                                                <th>Payment Date</th>
                                                <th>Freight Deductions</th>
                                                <th>REMITTANCE AMOUNT</th>
                                                <th>Payment Ref#</th>
                                                <th>Download</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Card DATA-->
</div>
</main>
<?php include(dirname(__FILE__).'/../franchise_shared/franchise_footer.php'); ?>