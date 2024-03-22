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

                <div class="mt-4 mb-4">
                    <a href="{{route('B2B-price-list') }}"><button class="btn btn-dark">Pricing Plans</button></a>
                    <a href="{{ route('tariff-list')}}"><button class="btn btn-dark">Tariff View Rate</button></a>
                </div>

                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title">B2C Pricing Plans</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-sm table-responsive">
                                        <thead>
                                            <tr class="p-t-10 sticky-top">
                                                <th>Name</th>
                                                <th>Charges</th>
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