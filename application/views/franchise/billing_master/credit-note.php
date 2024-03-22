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
                        <h6 class="">Billing - Invoice</h6>
                    </div>
                </div>

                 <?php  include(dirname(__FILE__).'/../billing_master/billing_tab.php'); ?>
                <hr>


                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <table class="table table-sm mt-4 ">
                                    <thead>
                                        <tr>
                                            <th>CN No. #</th>
                                            <th>CN Date</th>
                                            <th>CN Period</th>
                                            <th>CN Amount</th>
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
    <!-- END: Card DATA-->
</div>
<style>
    .table:not(.table-dark) thead th,
    .table:not(.table-dark) tfoot th,
    .table:not(.table-dark) td,
    .table:not(.table-dark) th {
        padding: 7px;
        border-color: var(--bordercolor);
    }
</style>

</main>
<?php include(dirname(__FILE__).'/../franchise_shared/franchise_footer.php'); ?>