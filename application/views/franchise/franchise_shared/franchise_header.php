<!DOCTYPE html>
<html lang="en">
    <!-- START: Head-->
        <meta charset="UTF-8">
        <title>Pick Admin</title>
        <link rel="shortcut icon" href="<?php echo base_url();?>assets/franchise_assets/dist/images/favicon.ico" />
        <meta name="viewport" content="width=device-width,initial-scale=1"> 

        <!-- START: Template CSS-->
        <link rel="stylesheet" href="<?php echo base_url();?>assets/franchise_assets/dist/vendors/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>assets/franchise_assets/dist/vendors/jquery-ui/jquery-ui.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>assets/franchise_assets/dist/vendors/jquery-ui/jquery-ui.theme.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>assets/franchise_assets/dist/vendors/simple-line-icons/css/simple-line-icons.css">        
        <link rel="stylesheet" href="<?php echo base_url();?>assets/franchise_assets/dist/vendors/flags-icon/css/flag-icon.min.css">         
        <!-- END Template CSS-->

        <!-- START: Page CSS-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css" integrity="sha512-IXuoq1aFd2wXs4NqGskwX2Vb+I8UJ+tGJEu/Dc0zwLNKeQ7CW3Sr6v0yU3z5OQWe3eScVIkER4J9L7byrgR/fA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet"  href="<?php echo base_url();?>assets/franchise_assets/dist/vendors/chartjs/Chart.min.css">
        <!-- END: Page CSS-->

        <!-- START: Page CSS-->   
        <link rel="stylesheet" href="<?php echo base_url();?>assets/franchise_assets/dist/vendors/morris/morris.css"> 
        <link rel="stylesheet" href="<?php echo base_url();?>assets/franchise_assets/dist/vendors/weather-icons/css/pe-icon-set-weather.min.css"> 
        <link rel="stylesheet" href="<?php echo base_url();?>assets/franchise_assets/dist/vendors/chartjs/Chart.min.css"> 
        <link rel="stylesheet" href="<?php echo base_url();?>assets/franchise_assets/dist/vendors/starrr/starrr.css"> 
        <link rel="stylesheet" href="<?php echo base_url();?>assets/franchise_assets/dist/vendors/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>assets/franchise_assets/dist/vendors/ionicons/css/ionicons.min.css"> 
        <link rel="stylesheet" href="<?php echo base_url();?>assets/franchise_assets/dist/vendors/jquery-jvectormap/jquery-jvectormap-2.0.3.css">
        <!-- END: Page CSS-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- START: Custom CSS-->
        <link rel="stylesheet" href="<?php echo base_url();?>assets/franchise_assets/dist/css/main.css">
        <script src="https://psa.atomtech.in/staticdata/ots/js/atomcheckout.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js" integrity="sha512-JnjG+Wt53GspUQXQhc+c4j8SBERsgJAoHeehagKHlxQN+MtCCmFDghX9/AcbkkNRZptyZU4zC8utK59M5L45Iw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <!-- END: Custom CSS-->
 
    </head>
    <!-- END Head-->
    

    <!-- START: Body-->
    <body id="main-container" class="default">

        <!-- START: Pre Loader-->
        <!--<div class="se-pre-con">-->
        <!--    <div class="loader"></div>-->
        <!--</div>-->
        <!-- END: Pre Loader-->

  
        <!-- START: Header-->
        <div id="header-fix" class="header fixed-top">
            <div class="site-width">
                <nav class="navbar navbar-expand-lg  p-0">
                    <div class="navbar-header  h-100 h4 mb-0 align-self-center logo-bar text-left">  
                        <a href="<?= base_url('franchise/dashboard'); ?>" class="horizontal-logo text-left">
                           <img src="<?php echo base_url();?>assets/franchise_assets/dist/images/Final Logo.png" style="width:100px;height:auto;">            
                        </a>                   
                    </div>
                  
                    
                    <div class="navbar-right h-100">
                        
                        
                      <ul class="ml-auto p-0 m-0 list-unstyled d-flex top-icon h-100">
                        <li class="nav-item">
                        <a class="btn btn-sm btn-success" style="background:#12263f!important;color:#fff; border: 1px solid #12263f!important;" data-toggle="modal" data-target="#pincodeModal"><i class="mdi mdi-flash"></i> Pincode Track</a>

                        <a href="<?= base_url('franchise/rate-calculator'); ?>" target="_blank" class="btn btn-sm btn-info text-white mr-1"><i class="mdi mdi-cube-send"></i> Rate Calculator</a>

                        <a href="<?=base_url('assets/Franchise-Demo-Video/demo.mp4'); ?>" target="_blank" class="btn btn-sm btn-success text-white mr-1"><i class="mdi mdi-cube-send"></i> Franchise Demo</a>

                        <a href="<?= base_url('franchise/view-internal-status'); ?>" target="_blank" class="btn btn-sm btn-danger text-white mr-1"><i class="mdi mdi-cube-send"></i> Tracking</a>
                        <?php if($_SESSION['franchise_type']==1){ 
                             $value = $_SESSION['customer_id']; 
                            $credit_limit = $this->db->query("Select * from tbl_franchise where fid = '$value'")->row();
                               ?>
                         <li class="nav-item">
                            <button class="btn btn-sm btn-light mr-1" data-toggle="tooltip" data-html="true" data-original-title="" title="">Credit Limit ₹<?=$credit_limit->credit_limit;?></button> <br>
                            <button class="btn btn-sm btn-light mr-1" data-toggle="tooltip" data-html="true" data-original-title="" title="">Utilize  Amount₹<?=$credit_limit->credit_limit_utilize;?></button>
                        </li>
                        <?php } ?>                       
                        </li>
                        <?php if($_SESSION['franchise_type']==2){ ?>
                         <li class="nav-item"> <?php $value = $_SESSION['customer_id']; 
                         $balance = $this->db->query("Select * from tbl_customers where customer_id = '$value'")->row();
                         ?>
                            <button class="btn btn-sm btn-light" data-toggle="tooltip" data-html="true" data-original-title="" title="">Balance ₹ <?= $balance->wallet; ?></button>
                            <?php  $customer_id = $_SESSION['customer_id'];        
                            $transection = $this->db->query("SELECT * FROM tbl_wallet_recharge_transection WHERE status ='0' AND customer_id = '$customer_id'")->row();    
                            if(!empty($transection))
                            { ?>
                            <a href="<?= base_url('atom_payment/Recharge_wallet/refresh_transcation');?>" title ="Faild Transaction Refresh" style="margin-top:2px;"><img src="<?= base_url('assets/update_icon.png');?>" width="40px"></a>
                            <?php } ?>
                        </li> 
                         <?php } ?>
                          
                       
                         <li class="nav-item ">
                            <button class="btn btn-sm btn-success" style="background:#12263f!important;border: 1px solid #12263f!important;" data-toggle="modal" data-target="#myModal"><i class="mdi mdi-flash"></i> Recharge</button>
                          
                        </li>
                       
                            <li class="dropdown user-profile align-self-center d-inline-block">
                                <a href="#" class="nav-link py-0" data-toggle="dropdown" aria-expanded="false"> 
                                    <div class="media">                                   
                                        <img src="<?php echo base_url();?>assets/franchise_assets/dist/images/usericon.png" alt="" class="d-flex img-fluid rounded-circle" width="29">
                                         <b style="color:black; margin-left:5px;">Branch <br> <?php echo $this->session->userdata('branch_name');?></b>
                                        <br>
                                    </div>
                                      <b style="color:red;"><?php echo $this->session->userdata('customer_name');?></b>
                                     <br><br>
                                </a>

                                <div class="dropdown-menu border dropdown-menu-right p-0">
                                    <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                        <span class="icon-pencil mr-2 h6 mb-0"></span> Edit Profile</a>
                                    <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                        <span class="icon-user mr-2 h6 mb-0"></span> View Profile</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                        <span class="icon-support mr-2 h6  mb-0"></span> Help Center</a>
                                    <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                        <span class="icon-globe mr-2 h6 mb-0"></span> Forum</a>
                                    <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                        <span class="icon-settings mr-2 h6 mb-0"></span> Account Settings</a>
                                    <div class="dropdown-divider"></div>
                                    
                                    <a href="<?php echo base_url('logout');?>" class="dropdown-item px-2 text-danger align-self-center d-flex">

                                         <span class="icon-logout mr-2 h6  mb-0"></span> Sign Out</a>
                                      
                                </div>

                            </li>

                        </ul>
                    </div>
                </nav>
            </div><br>
            <?php $alert =$this->db->query("select * from tbl_news where  status = '1' order by id desc limit 1")->row(); if(!empty($alert)){ ?>
                <b><marquee style="color:red;">
                    <?= $alert->news_details; ?>
        </marquee></b>
      <?php } ?>
        </div>
        <!-- END: Header-->

            <div class="modal fade" id="myModal">
            <div class="modal-dialog modal-dialog-centered ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">
                            Recharge Your Wallet
                        </h6>
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
                                        <input type="number"  step="any" placeholder="Enter Credit Amounts"
                                            name="recharge_wallet" id="recharge_wallet" class="form-control" required>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="save" class="btn btn-primary add-todo">Recharge Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
<!-- Search Pincode -->
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
            <form  action="<?= base_url().'franchise/pincode-track'; ?>" method="GET">
                <div class="modal-body">
                     <div class="row">
                        <div class="col-md-12">
                            <div class="contact-occupation">
                                <label for="contact-occupation" class="col-form-label">Enter Pincode</label>
                                <input type="number" name="pincode" placeholder="Enter Pincode" class="form-control" required="">
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

      
