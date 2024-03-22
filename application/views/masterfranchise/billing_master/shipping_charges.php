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
                        <h6 class="">Billing - Shipping Charges</h6>
                    </div>
                </div>

              <?php  include(dirname(__FILE__).'/../billing_master/billing_tab.php'); ?>
                <hr>


                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-sm-3">
                                            <label>From Date</label>
                                            <input type="date" autocomplete="off" data-start-date="" data-end-date="" class="form-control date-range-picker col-sm-12 form-control-sm">
                                        </div>
                                        <div class="col-sm-3">
                                            <label> To Date</label>
                                            <input type="date" autocomplete="off" data-start-date="" data-end-date="" class="form-control date-range-picker col-sm-12 form-control-sm">
                                        </div>
                                        <div class="col-sm-3">
                                            <label>AWB Number</label>
                                            <input type="text" autocomplete="off" name="" value="" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-sm-3">
                                            <button type="submit" class="btn btn-sm mt-4 btn-outline-success">Apply</button>
                                            <a href="#" class="btn btn-sm mt-4  btn-outline-primary">Clear</a>
                                        </div>


                                    </div>

                                    <table class="table table-sm mt-4 ">
                                        <thead>
                                            <tr style="background-color:#ddd;">
                                                <th>Shipment Created</th>
                                                <th>Courier</th>
                                                <th>AWB Number</th>
                                                <th>Status</th>
                                                <th>Freight Charges(₹)</th>
                                                <th>COD Charges(₹)</th>
                                                <th>Entered Wgt(kg)</th>
                                                <th>Entered Dimension</th>
                                                <th>Applied Wgt(kg)</th>
                                                <th>Extra Wgt Charges(₹)</th>
                                                <th>RTO Charges</th>
                                                <th>COD Charge Reversed</th>
                                                <th>RTO Extra Wgt Charges</th>
                                                <th>Total Charges</th>
                                                <th>Action</th>

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
<style>
    .table:not(.table-dark) thead th, .table:not(.table-dark) tfoot th, .table:not(.table-dark) td, .table:not(.table-dark) th {
    padding: 7px;
    border-color: var(--bordercolor);
}
</style>


</main>
<?php include(dirname(__FILE__).'/../franchise_shared/franchise_footer.php'); ?>