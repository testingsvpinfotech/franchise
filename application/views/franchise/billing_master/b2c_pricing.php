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
                        <h6 class="">Billing - B2C Pricing</h6>
                    </div>
                </div>
                <hr>
                <?php  include(dirname(__FILE__).'/../billing_master/billing_tab.php'); ?>



                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="card mb-4">
                            <div class="card-body">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">B2C Pricing Plans</h4>
                            </div>
                            <div class="card-body">
                               <table class="table table-bordered table-responsive">
                                <tbody>
                                    <tr>
                                    <th style="text-align: center;">Courier Mode</th>
                                    <th style="text-align: center;">Weight (grams)</th>
                                    <th style="text-align: center;">Within City (z1)</th>
                                    <th style="text-align: center;">Within State (z2)</th>
                                    <th style="text-align: center;">Regional (z3)</th>
                                    <th style="text-align: center;">Metro To Metro (z4)</th>
                                    <th style="text-align: center;">North East, J&amp;K, KL, AN (z5)</th>
                                    <th style="text-align: center;">Rest of India (z6)</th>
                                    <th style="text-align: center;">COD Charges</th>
                                    <th style="text-align: center;">COD %</th>
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