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
                        <h4 class=""> <i class="icon-settings"></i> Setting</h4>
                    </div>
                    <!-- START: Card Data-->
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            <div class="row">
                                <div class="col-12 col-md-4 mt-3">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h4 class="card-title">Channels</h4>
                                        </div>
                                        <div class="card-body text-center">
                                            <i class="fab fa-audible pb-1" style="font-size:48px;"></i>
                                            <h6 class="card-subtitle mb-2 text-muted">Import orders from your online store</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 mt-3">
                                    <a href="#">
                                        <div class="card">
                                            <a href="{{ route('wharerhousing-list')}}">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <h4 class="card-title">Wharehouse</h4>
                                                </div>
                                                <div class="card-body text-center">
                                                    <i class="fas fa-location-arrow pb-1" style="font-size:48px;"></i>
                                                    <h6 class="card-subtitle mb-2 text-muted">Manage your pickup locations</h6>
                                                </div>
                                            </a>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-12 col-md-4 mt-3">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h4 class="card-title">Webhooks</h4>
                                        </div>
                                        <div class="card-body text-center">
                                            <i class="fab fa-creative-commons-sampling pb-1" style="font-size:48px;"></i>
                                            <h6 class="card-subtitle mb-2 text-muted">Receive shipment status notification on URL</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 mt-3">
                                    <div class="card">
                                        <a href="{{route('add-employee')}}">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h4 class="card-title">Employees</h4>
                                            </div>
                                            <div class="card-body text-center">
                                                <i class="fas fa-users pb-1" style="font-size:48px;"></i>
                                                <h6 class="card-subtitle mb-2 text-muted">Allow access to team members</h6>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 mt-3">
                                    <div class="card">
                                        <a href="{{route('company-profile')}}">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h4 class="card-title">Company Profile</h4>
                                            </div>
                                            <div class="card-body text-center">
                                                <i class="fas fa-industry pb-1" style="font-size:48px;"></i>
                                                <h6 class="card-subtitle mb-2 text-muted">Profile and Company Details</h6>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 mt-3">
                                    <div class="card">
                                        <a href="{{ route('print-label')}}">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h4 class="card-title">Label Settings</h4>
                                            </div>
                                            <div class="card-body text-center">
                                                <i class="fas fa-print pb-1" style="font-size:48px;"></i>
                                                <h6 class="card-subtitle mb-2 text-muted">Profile and Company Details</h6>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 mt-3">
                                    <div class="card">
                                        <a href="{{route('account-setting')}}">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h4 class="card-title">Account Settings</h4>
                                            </div>
                                            <div class="card-body text-center">
                                                <i class="fas fa-user-edit pb-1" style="font-size:48px;"></i>
                                                <h6 class="card-subtitle mb-2 text-muted">Profile and Company Details</h6>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 mt-3">
                                    <div class="card">
                                        <a href="<?php echo base_url('franchise/add-customer');?>">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h4 class="card-title">Cosigner/Customer Name</h4>
                                            </div>
                                            <div class="card-body text-center">
                                                <i class="fas fa-user-friends pb-1" style="font-size:48px;"></i>
                                                <h6 class="card-subtitle mb-2 text-muted">Profile and Company Details</h6>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- END: Card DATA-->

                </div>
            </div>

        </div>
    </div>
    <!-- END: Card DATA-->
</div>
</main>
<?php include(dirname(__FILE__).'/../franchise_shared/franchise_footer.php'); ?>