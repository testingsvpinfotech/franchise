<?php $this->load->view('user/admin_shared/admin_header'); ?>
<!-- END Head-->
<style>
    .buttons-copy {
        display: none;
    }

    .buttons-csv {
        display: none;
    }

    /*.buttons-excel{display: none;}*/
    .buttons-pdf {
        display: none;
    }

    .buttons-print {
        display: none;
    }

    /*#example_filter{display: none;}*/
    .input-group {
        width: 60% !important;
    }
</style>
<!-- START: Body-->

<body id="main-container" class="default">


    <!-- END: Main Menu-->

    <?php $this->load->view('user/admin_shared/admin_sidebar'); ?>
    <!-- END: Main Menu-->

    <!-- START: Main Content-->

    <style>
        .card .card-header {
            background-color: #e63033;
            border-color: var(--bordercolor);
            padding: 15px;
            color: #fff;
        }

        .wizard .round-tab i {
            width: 50px;
            height: 50px;
            background-color: #e1484b;
            border: 2px solid var(--primarycolor);
            font-size: 1.25rem;
            line-height: 45px;
            text-align: center !important;
            top: 15px;
            z-index: 99;
            display: inline-block;
        }

        .btn-primary,
        .btn-default,
        .btn-primary:hover,
        .btn-primary:not(:disabled):not(.disabled).active,
        .btn-primary:not(:disabled):not(.disabled):active,
        .show>.btn-primary.dropdown-toggle,
        .btn-primary.focus,
        .btn-primary:focus,
        .btn-primary.disabled,
        .btn-primary:disabled,
        .btn-primary.disabled:hover,
        .btn-primary:disabled:hover {
            background-color: #e53033; !important;
            border-color: #040911;
        }
        h6,h5{
            text-align: center;
        }
    </style>
    <main>

        <div class="container-fluid site-width">
            <!-- START: Breadcrumbs-->
            <div>
                <div class="row">
                    <div class="col-12 col-md-12 mt-3">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Pick Up Request</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h5>Total Till date</h5>
                                        <h6>41</h6>
                                    </div>
                                    <div class="col-md-4">
                                        <h5>Pending</h5>
                                        <h6>38</h6>
                                    </div>
                                    <div class="col-md-4">
                                        <h5>Successful</h5>
                                        <h6>3</h6>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Breadcrumbs-->

            <!-- START: Card Data-->
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="row">
                        <div class="col-12 col-md-6 mt-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="wizard mb-4 ml-4 mr-4">
                                        <div class="connecting-line"></div>
                                        <ul class="nav nav-tabs d-flex mb-3">
                                            <!-- <li class="nav-item ">
                                                <a class="nav-link position-relative round-tab text-left p-0 active border-0" data-toggle="tab" href="#id1">
                                                    <i class="fas fa-address-card position-relative text-white h5 mb-3"></i>
                                                    <small class="d-none d-md-block ">Consignee Details</small>
                                                </a>
                                            </li> -->
                                            <li class="nav-item">
                                                <a class="nav-link position-relative round-tab text-sm-center text-left p-0 border-0" data-toggle="tab" href="#id1">
                                                    <i class="fa fa-truck position-relative text-white h5 mb-3"></i>
                                                    <small class="d-none d-md-block"></small>
                                                </a>
                                            </li>
                                            <li class="nav-item ml-auto">
                                                <a class="nav-link position-relative round-tab text-sm-right text-left p-0 border-0" data-toggle="tab" href="#id2">
                                                    <i class="fas fa-box-open position-relative text-white h5 mb-3"></i>
                                                    <small class="d-none d-md-block"></small>
                                                </a>
                                            </li>
                                            <li class="nav-item ml-auto">
                                                <a class="nav-link position-relative round-tab text-sm-right text-left p-0 border-0" data-toggle="tab" href="#id3">
                                                    <i class="fas fa-sticky-note position-relative text-white h5 mb-3"></i>
                                                    <small class="d-none d-md-block"></small>
                                                </a>
                                            </li>
                                            <!-- <li class="nav-item ml-auto">
                                                <a class="nav-link position-relative round-tab text-sm-right text-left p-0 border-0" data-toggle="tab" href="#id5">
                                                    <i class="icon-credit-card position-relative text-white h5 mb-3"></i>
                                                    <small class="d-none d-md-block">Payment And Reciept</small>
                                                </a>
                                            </li> -->
                                        </ul>
                                    </div>
                                </div>
                            </div>






                            <form method="post" class="" action="<?php echo base_url();?>User_panel/pickup_request">
                            <div class="tab-content">
                                <div class="tab-pane fade active show" id="id1">
                                    <div class="row">
                                        <div class="col-12 col-sm-12">
                                            <div class="card p-4 mt-4">
                                                <div class="row  mb-4">

                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                                    <h4 class="card-title">Shipper Details</h4>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label>Shipper Name <span class="text-danger">*</span></label>
                                                                            <div class="form-group">
                                                                                <input name="consigner_name" type="text" class="form-control">
                                                                               
                                                                                <input name="pickup_request_id" type="hidden" value="<?php echo $request_id;?>" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label>Mobile No. <span class="text-danger">*</span></label>
                                                                            <div class="form-group">
                                                                                <input name="consigner_contact" type="text"  class="form-control">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label>E-mail ID <span class="text-danger">*</span></label>
                                                                            <div class="form-group">
                                                                                <input name="consigner_email" type="email" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label>Shipper Address1 <span class="text-danger">*</span></label>
                                                                            <div class="form-group">
                                                                                <input name="consigner_address1" type="text" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label>Shipper Address2</label>
                                                                            <div class="form-group">
                                                                                <input name="consigner_address2" type="text" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label>Shipper Address3</label>
                                                                            <div class="form-group">
                                                                                <input name="consigner_address3" type="text" class="form-control">
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <button type="button" class="btn float-right btn-primary nexttab">Next</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>





                                <!-- *******************************Mode Section Start tab-2 **************************************************** -->

                                <div class="tab-pane fade" id="id2">
                                    <div class="row">
                                        <div class="col-12 col-sm-12">
                                            <div class="card p-4 mt-4">
                                                <div class="row  mb-4">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h4 class="card-title">Pickup Details</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label> Pickup Pincode <span class="text-danger">*</span></label>
                                                                <div class="form-group">
                                                                    <input name="pickup_pincode" type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Destination Pincode <span class="text-danger">*</span></label>
                                                                <div class="form-group">
                                                                    <input name="destination_pincode" type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="col-md-12">
                                                                <label>Pickup Location <span class="text-danger">*</span></label>
                                                                <div class="form-group">
                                                                    <input name="pickup_location" type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Pickup Date <span class="text-danger">*</span></label>
                                                                <div class="form-group">
                                                                    <input type="datetime-local"  name="pickup_date" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>City <span class="text-danger">*</span></label>
                                                                <div class="form-group">
                                                                    <input name="city" type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label>Instruction<span class="text-danger">*</span></label>
                                                                <div class="form-group">
                                                                    <input type="text" name="instruction" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex">
                                                    <button type="button" class="btn btn-primary prevtab">Previous</button>
                                                    <button type="button" class="btn btn-primary nexttab ml-auto">Next</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>








                                <!-- *******************************Item Details tab-3 **************************************************** -->




                                <div class="tab-pane fade" id="id3">
                                    <div class="form">
                                        <div class="row">
                                            <div class="col-12 col-md-12 mt-3">
                                                <div class="card mb-4">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h4 class="card-title">Package Details</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Mode <span class="text-danger">*</span></label>
                                                                <div class="form-group">
                                                                    <select class="form-control" name="mode_id">
                                                                    <option value="">Select Mode</option>
                                                                        <?php if(!empty($transfer_mode)){?>
                                                                            <?php foreach($transfer_mode as $value):?>
                                                                               
                                                                        <option value="<?php echo $value->transfer_mode_id;?>"><?php echo $value->mode_name;?></option>
                                                                        <?php endforeach;?>
                                                                        <?php }?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Actual Weight(kg) <span class="text-danger">*</span></label>
                                                                <div class="form-group">
                                                                    <input name="actual_weight" type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Type Of the Package <span class="text-danger">*</span></label>
                                                                <div class="form-group">
                                                                    <select class="form-control" name="type_of_package">
                                                                    <option value="">Select Type Of the Package</option>
                                                                        <?php if(!empty('$type_of_package')){?>
                                                                            <?php foreach($type_of_package as $value):?>
                                                                        <option value="<?php echo $value->partial_type;?>"><?php echo $value->partial_type;?> </option>
                                                                        <?php endforeach;?>
                                                                        <?php }?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                           
                                                            <div class="col-md-6">
                                                                <label>No. of Packages<span class="text-danger">*</span></label>
                                                                <div class="form-group">
                                                                    <input name = "no_of_pack" type="text" class="form-control">
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="d-flex">
                                                            <button type="button" class="btn btn-primary prevtab">Previous</button>
                                                            <button type="submit" name="submit" class="btn btn-primary nexttab ml-auto">Submit</button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            </form>
                        </div>
                        <!-- **********************************************************************************8 -->
                        <div class="col-12 col-md-6 mt-3">
                            <div class="row">
                                <div class="col-12 col-md-12 mt-3">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h4 class="card-title">Consignee Information</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table">
                                                        <tbody>
                                                           
                                                            <tr style="background-color:#ddd;color:#333;">
                                                                <th>Request No.</th>
                                                                <th>From</th>
                                                                <th>To</th>
                                                                <th>Scheduled Pickup</th>
                                                            </tr>
                                                            <?php if(!empty($request_data)){?>
                                                                <?php foreach($request_data as $value):?>
                                                            <tr>
                                                                <td><?php echo $value->pickup_request_id;?></td>
                                                                <td><?php echo $value->pickup_pincode;?></td>
                                                                <td><?php echo $value->destination_pincode;?></td>
                                                                <td><?php echo $value->pickup_date;?></td>
                                                            </tr>
                                                            <?php endforeach;?>
                                                            <?php } else{?>
                                                            <tr><td style="color:red"colspan="10">No data Found</td></tr>
                                                           <?php }?>
                                                        </tbody>
                                                    </table>
                                                    <button type="button" class="btn btn-primary nexttab ml-auto">View All</button>
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
        </div>
        </div>
    </main>
    <!-- END: Content-->
    <!-- START: Footer-->

    <?php $this->load->view('user/admin_shared/admin_footer'); ?>
    <!-- START: Footer-->
</body>
<!-- END: Body-->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
 <?php  if ($this->session->flashdata('flash_message')): ?>
                            <script>
                                swal({
                                    title: "Done",
                                    text: "<?php echo $this->session->flashdata('flash_message'); ?>",
                                    timer: 1500,
                                    showConfirmButton: false,
                                    type: 'success'
                                });
                            </script>
                    <?php endif; ?> 

</html>