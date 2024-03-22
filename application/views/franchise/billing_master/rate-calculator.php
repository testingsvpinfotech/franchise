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
                        <h6 class="">Billing - Rate Calculator</h6>
                    </div>
                </div>
                <hr>
                <?php  include(dirname(__FILE__).'/../billing_master/billing_tab.php'); ?>

             
                                <div class="row">
                                    <div class="col-12 col-sm-12">
                                        <div class="row">
                                            <div class="col-12 col-md-6 mt-3">
                                                <div class="card mb-4">
                                                   
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h4 class="card-title">B2C Calculator</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <label>Origin Pincode .</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="col-md-4">
                                                                <label>Destination Pincod .</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label>Weight(KG).</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label>L(cm).</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label>H(cm).</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label>B(cm).</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Order value.</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Order Type .</label>
                                                                <div class="form-group">
                                                                    <select class="form-control">
                                                                        <option>Prepaid</option>
                                                                        <option>COD</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <button type="button" class="btn btn-success ml-4">Calculate</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="col-12 col-md-6 mt-3">
                                                <div class="card">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h4 class="card-title">B2B Calculator</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <label>Origin Pincode .</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="col-md-4">
                                                                <label>Destination Pincod .</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label>Weight(KG).</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label>L(cm).</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label>H(cm).</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label>B(cm).</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Order value.</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Order Type .</label>
                                                                <div class="form-group">
                                                                    <select class="form-control">
                                                                        <option>Prepaid</option>
                                                                        <option>COD</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <button type="button" class="btn ml-4 btn-success ">Calculate</button>
                                                        </div>
                                                    </div>
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