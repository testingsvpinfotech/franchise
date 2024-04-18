<!-- START: Pre Loader
        <div class="se-pre-con">
            <div class="loader"></div>
        </div>
        START: Header-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<div id="header-fix" class="header fixed-top ">
    <div class="site-width">
        <nav class="navbar navbar-expand-lg  p-0">
            <div class="navbar-header  h-100 h4 mb-0 align-self-center logo-bar text-left">
                <a href="javascript:void(0);" class="horizontal-logo text-left">
                    <?php $company_details = $this->basic_operation_m->get_table_row('tbl_company', array('id' => 1)); ?>
                    <img src="assets/company/<?php echo $company_details->logo; ?>" class="portfolioImage img-fluid">
                </a>
            </div>
            <div class="navbar-right">
                <!-- START: Main Menu-->
                <div class="sidebar">
                    <div class="site-width">
                        <!-- START: Menu-->
                        <!-- START: Menu-->
                        <ul id="side-menu" class="sidebar-menu">
                            <li class="dropdown active">
                                <ul>
                                    <li>
                                        <?php $value = $_SESSION['customer_id'];
                                        $balance = $this->db->query("Select * from tbl_customers where customer_id = '$value'")->row();
                                        // echo $this->db->last_query();
                                        ?>
                                        <?php if ($_SESSION['franchise_type'] == 1 || $_SESSION['franchise_type'] == 3) {
                                            $value = $_SESSION['customer_id'];
                                            $credit_limit = $this->db->query("Select * from tbl_franchise where fid = '$value'")->row();
                                            ?>
                                            <button class="btn btn-sm btn-light mr-1" data-toggle="tooltip" disabled
                                                data-html="true" data-original-title="" title="">Credit Balance ₹
                                                <?= $credit_limit->credit_limit - $credit_limit->credit_limit_utilize; ?></button><br>
                                            <button class="btn btn-sm btn-light mr-1" data-toggle="tooltip" disabled
                                                data-html="true" data-original-title="" title="">Utilize Balance ₹
                                                <?= $credit_limit->credit_limit_utilize; ?></button>
                                        <?php }else{ ?>
                                            <button class="btn btn-sm btn-light" data-toggle="tooltip" data-html="true"
                                            data-original-title="" title="" disabled>Wallet Balance ₹
                                            <?= $balance->wallet; ?>
                                        </button>
                                        <?php } ?>
                                        <?php $customer_id = $_SESSION['customer_id'];
                                        $transection = $this->db->query("SELECT * FROM tbl_wallet_recharge_transection WHERE status ='0' AND customer_id = '$customer_id'")->row();
                                        if (!empty($transection)) { ?>
                                            <a href="<?= base_url('atom_payment/Recharge_wallet/refresh_transcation'); ?>"
                                                title="Click to Refresh" style="margin-top:2px;"><img
                                                    src="<?= base_url('assets/update_icon.png'); ?>" width="40px"></a>
                                        <?php } ?>
                                    </li>
                                    <li class="nav-item">
                                        <a class="btn btn-sm btn-success"
                                            style="background:#12263f!important;color:#fff; border: 1px solid #12263f!important;"
                                            data-toggle="modal" data-target="#pincodeModal"> Pincode Track</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= base_url('master-franchise/rate-calculator'); ?>"
                                            class="btn btn-sm btn-info mt-1" style="color:#fff;"> Rate Calculator</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= base_url('master-franchise/view-internal-status'); ?>"
                                            target="_blank" class="btn btn-sm btn-danger text-white mr-1">Tracking</a>
                                    </li>
                                    <li class="nav-item ">
                                        <button class="btn btn-sm btn-success"
                                            style="background:#12263f!important;border: 1px solid #12263f!important;"
                                            data-toggle="modal" data-target="#myModal">Recharge</button>

                                    </li>
                                    <li class="active"><a href="<?= base_url('master_franchise/dashboard'); ?>"><i
                                                class="icon-home mr-1"></i> Dashboard</a></li>
                                    <li class="dropdown"><a href="javascript:void(0);">Domestic</a>
                                        <ul class="sub-menu">
                                            <li>
                                            <li class="active"><a
                                                    href="<?= base_url('master_franchise/add-shipment'); ?>"><i
                                                        class="icon-fire"></i> Add Shipment</a></li>
                                            <li><a href="<?= base_url('master-franchise/shipment-list'); ?>"><i
                                                        class="fa fa-eye"></i> View Shipment</a></li>
                                            <li><a href="<?= base_url('Ms-franchise/awb-available-stock'); ?>"><i
                                                        class="fa fa-eye"></i> Available Stock</a></li>
                                            <li><a href="<?= base_url('master-franchise/list-mis-report'); ?>"><i
                                                        class="fa fa-eye"></i> MIS Report</a></li>
                                    </li>

                                </ul>
                            </li>
                            <li class="dropdown"><a href="javascript:void(0);">Operations</a>
                                <ul class="sub-menu">
                                    <li>
                                    <li class="active"><a
                                            href="<?= base_url('master_franchise/franchise-incoming'); ?>"><i
                                                class="icon-fire"></i> View Franchise Incoming</a></li>
                                    <li class="active"><a
                                            href="<?= base_url('master_franchise/list-incoming-bag'); ?>"><i
                                                class="icon-fire"></i> In-Scan Bag</a></li>
                                    <li class="active"><a
                                            href="<?= base_url('master_franchise/list-domestic-bag'); ?>"><i
                                                class="icon-fire"></i> Bag Master </a></li>
                                    <li class="active"><a
                                            href="<?= base_url('franchisem/list-domestic-menifiest'); ?>"><i
                                                class="icon-fire"></i> Menifiest Master </a></li>
                                    <li class="active"><a href="<?= base_url('master-franchise/pickup-in-scan') ?>"><i
                                                class="fa fa-eye"></i> PICKUP INSCAN</a></li>
                                    <li class="active"><a
                                            href="<?= base_url('master-franchise/single-delivery-update') ?>"><i
                                                class="fa fa-eye"></i> Single Delivery Update</a></li>
                                    <li class="active"><a
                                            href="<?= base_url('master-franchise/wallet-transaction'); ?>">Billing
                                            Transaction</a></li>
                                    <li class="active"><a
                                            href="<?= base_url('master_franchise/franchise-list'); ?>">Unit
                                            Franchise List</a></li>
                                    <li class="active"><a
                                            href="<?= base_url('master_franchise/View_commision'); ?>">View
                                            Commission</a></li>
                                    <li class="active"><a href="<?= base_url('m-franchise-payment-transaction'); ?>">
                                            Payment Gateway Transection
                                        </a></li>
                            </li>
                        </ul>
                        </li>
                        <li class="dropdown"><a href="javascript:void(0);">PRQ</a>
                            <ul class="sub-menu">
                                <li>
                                <li class="active"><a href="<?= base_url('Master_Franchise_prq/pickup_request'); ?>"><i
                                            class="icon-fire"></i> Add PRQ</a></li>
                                <li class="active"><a
                                        href="<?= base_url('Master_Franchise_prq/view_pickup_request'); ?>"><i
                                            class="icon-fire"></i> View PRQ</a></li>
                        </li>
                        </ul>
                        </li>
                        <li class="dropdown"><a href="javascript:void(0);">DRS</a>
                            <ul class="sub-menu">
                                <li>
                                <li class="active"><a href="<?= base_url('master-franchise/add-deliverysheet'); ?>"><i
                                            class="icon-fire"></i> Create DRS</a></li>
                                <li class="active"><a href="<?= base_url('master-franchise/list-deilverysheet'); ?>"><i
                                            class="icon-fire"></i> View Deliverysheet</a></li>

                        </li>
                        </ul>
                        </li>
                        <li class="dropdown"><a href="javascript:void(0);">POD</a>
                            <ul class="sub-menu">
                                <li>
                                <li class="active"><a href="<?= base_url('master-franchise/add-pod'); ?>"><i
                                            class="icon-fire"></i> Add POD</a></li>
                                <li class="active"><a href="<?= base_url('master-franchise/upload-pod'); ?>"><i
                                            class="icon-fire"></i> View POD</a></li>

                        </li>
                        </ul>
                        </li>





                        <!-- <li class="active"><a href="User_panel/pod"><i class="icon-fire"></i> Pod search</a></li>
                                        <li class="active"><a href="User_panel/complain_view"><i class="icon-fire"></i> Complain</a></li>
                                                                                    -->
                        </ul>
                        </li>

                        </ul>



                        </li>
                        </ul>
                    </div>
                </div>
                <!-- END: Main Menu-->

                <ul id="top-menu" class="top-menu">

                    <li class="dropdown user-profile align-self-center d-inline-block">
                        <a href="#" class="nav-link py-0" data-toggle="dropdown" aria-expanded="false">
                            <div class="media">
                                <img src="assets/image/avtar.png" alt="" title="LU0001"
                                    class="d-flex img-fluid rounded-circle" width="29">
                            </div>
                        </a>
                        <center><b>
                                <?php echo $this->session->userdata('customer_name'); ?>
                            </b></center>
                        <div class="dropdown-menu border dropdown-menu-right p-0">
                            <a href="logout" class="dropdown-item px-2 text-danger align-self-center d-flex">
                                <span class="icon-logout mr-2 h6  mb-0"></span> Sign Out</a>
                        </div>

                    </li>

                </ul>
            </div>
        </nav>
    </div>
</div>
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Recharge Your Wallet
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="icon-close"></i>
                </button>
            </div>
            <form action="<?= base_url('pay-franchise-amount'); ?>" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="contact-occupation">
                                <label for="contact-occupation" class="col-form-label">Enter Amount</label>
                                <input type="number" min="500" step="any" placeholder="Enter Credit Amounts"
                                    name="recharge_wallet" id="recharge_wallet" class="form-control" required>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" name="save" class="btn btn-primary add-todo">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="pincodeModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Check Servicable Pincode
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="icon-close"></i>
                </button>
            </div>
            <form action="<?= base_url() . 'master-franchise/pincode-track'; ?>" method="GET">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="contact-occupation">
                                <label for="contact-occupation" class="col-form-label">Enter Pincode</label>
                                <input type="number" name="pincode" placeholder="Enter Pincode" class="form-control"
                                    required="">
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" name="filter" class="btn btn-primary add-todo">Search</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END: Header-->