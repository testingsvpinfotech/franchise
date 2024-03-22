 <?php include(dirname(__FILE__).'/../franchise_shared/franchise_header.php'); ?>
<?php include(dirname(__FILE__).'/../franchise_shared/franchise_sidebar.php'); ?>
 <!-- START: Card Data-->
<main>
    <div class="container-fluid site-width">

<!-- START: Card Data-->
<!-- START: Card Data-->
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-body">
                <div class="row p-2">
                    <div class="col-md-6">
                        <h4 class=""> <i class="fas fa fa-star"></i> Check Pincode</h4>
                    </div>
                    <!-- START: Card Data-->
                    <div class="col-12 col-md-12 mt-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row p-3">
                                    <div class="col-sm-3 col-md-2">
                                        <ul class=" nav nav-tabs d-block d-sm-flex mb-2">
                                            <li class="nav-item  mb-4">
                                                <a class="nav-link p-0 active" data-toggle="tab" href="#tab7">
                                                    <div class="d-flex">
                                                        <div class="mr-3 mb-0 h5">Check Pincode</div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="nav-item  mb-4">
                                                <a class="nav-link p-0" data-toggle="tab" href="#tab8">
                                                    <div class="d-flex">
                                                        <div class="mr-3 mb-0 h5">Order Sticker</div>
                                                    </div>
                                                </a>
                                            </li>

                                            <li class="nav-item mb-4">
                                                <a class="nav-link pl-4" data-toggle="tab" href="#tab9">
                                                    <div class="d-flex">
                                                        <div class="mr-3 h5 mb-0">B2B LR </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <div>
                                                <li class="nav-item mb-4">
                                                    <a class="nav-link pl-4" data-toggle="tab" href="#tab10">
                                                        <div class="d-flex">
                                                            <div class="mr-3 h5 mb-0">WAB List</div>
                                                        </div>
                                                    </a>
                                                </li>
                                            </div>
                                        </ul>
                                    </div>

                                    <div class="tab-content col-md-10 col-sm-9">
                                        <div class="tab-pane fade active show" id="tab7">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h4 class="card-title">Check Pincode Serviceability</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Shipment Mode .</label>
                                                        <div class="form-group">
                                                            <select class="form-control">
                                                                <option>B2C</option>
                                                                <option>B2B</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label>Origin Pincode </label>
                                                        <div class="form-group">
                                                            <input type="date" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label>Destination Pincode.</label>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label>Payment Type.</label>
                                                        <div class="form-group">
                                                            <select class="form-control">
                                                                <option>Cod</option>
                                                                <option>Prepaid</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>







                                        <div class="tab-pane fade" id="tab8">
                                            <div class="form">
                                                <div class="col-md-12">
                                                    <label>Company Name</label>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Upload</label>
                                                    <div class="form-group">
                                                        <input type="file" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="d-flex">

                                                    <button type="button" class="btn btn-primary nexttab ml-auto">Upload</button>

                                                </div>
                                            </div>
                                        </div>


                                        <div class="tab-pane fade" id="tab9">
                                            <div class="form p-5 text-center">
                                                <button type="button" class="btn btn-primary prevtab">Genrate</button>
                                            </div>
                                        </div>




                                        <div class="tab-pane fade" id="tab10">
                                            <!-- START: Card Data-->
                                            <div class="row">
                                                <div class="col-12 mt-3">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row p-2">
                                                                <div class="col-md-6">
                                                                    <h6 class="">Awb Lists</h6>
                                                                </div>
                                                                <div class="col-md-6 d-flex justify-content-end">
                                                                    <button type="button" class="btn btn-outline-primary mr-1">Export</button>
                                                                    <button type="button" class="btn btn-outline-success  mr-1">Filter</button>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            @include('PincodeServices.awb-tab')


                                                            <div class="table-responsive">
                                                                <table id="id1" class="display table  table-striped table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th><input data-switch="true" id="select_all_checkboxes" type="checkbox"></th>
                                                                            <th>Awb Number</th>
                                                                            <th>Consigner / Customer Name</th>
                                                                            <th> Courier Mode </th>

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                       
                                                                        <tr>
                                                                            <td> <input value="824114658" type="checkbox" class="multiple_checkboxes" name="shipping_ids"></td>
                                                                            <td>151239870099505 </td>
                                                                            <td> - - </td>
                                                                            <td> Surface </td>
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