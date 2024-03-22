

<header>
        <div class="header-output padding-tb-10px background-white" id="topbar" class="topbar">
        <div id="fh-header-minimized" class="fh-header-minimized fh-header-v3"></div>
            <div class="container header-in">
            <!-- <div class="topbar-left topbar-widgets text-left">
                    <div class="widget widget_text">
                        <div class="textwidget">
                            <div class="topbar-contact"><i class="flaticon-cup "></i>We Deliver Cargo WorldWide</div>
                        </div>
                    </div>
                    <div class="widget widget_text">
                        <div class="textwidget">
                            <div class="topbar-contact"><i class="flaticon-office "></i> ISO 9001:2005 certified company</div>
                        </div>
                    </div>
                </div> -->
                <div class="position-relative">
                    <div class="row">
                        <div class="col-lg-3 col-md-12">
                            <a id="logo" href="<?php echo site_url();?>" class="d-inline-block margin-tb-15px"><img src="assets/company/<?php echo $company_info->logo; ?>" alt=""></a>
                            <a class="mobile-toggle padding-15px background-second-color border-radius-3" href="#"><i class="fa fa-bars"></i></a>
                        </div>
                        <div class="col-lg-9 col-md-12  d-none d-lg-block position-inherit">
                            <div class="row" id="resonsive-menu">

                                <!-- contact info -->
                                <div class="col-lg-offset-4 col-lg-4 padding-tb-15px">
                                    <div class="contact-info opacity-9">
                                        <div class="icon margin-top-5px"><span class="icon_mail_alt"></span></div>
                                        <div class="text">
                                            <span class="title-in">Email :	</span> <br>
                                            <span class="font-weight-500 text-main-color"><?php echo $company_info->email; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- // contact info -->


                                <!-- contact info -->
                                <div class="col-lg-4 padding-tb-15px">
                                    <div class="contact-info opacity-9">
                                        <div class="icon margin-top-5px"><span class="icon_phone"></span></div>
                                        <div class="text">
                                            <span class="title-in">Call Us :</span> <br>
                                            <span class="font-weight-500 text-uppercase text-main-color"><?php echo $company_info->phone_no; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- // contact info -->

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="background-main-color clearfix">
            <div class="container">
                <div class="position-relative">
                    <div class="row">
                        <div class="col-lg-10 position-inherit">
                            <ul id="menu-main" class="nav-menu float-lg-left text-lg-left link-padding-tb-16px white-link dropdown-dark">
                                <li><a href="<?php echo site_url();?>">Home</a></li>
                                <li><a href="<?php echo site_url('/abouts_page');?>">About Us</a></li>
                                <li><a href="<?php echo site_url('/services_page');?>">Services</a></li>
                               <!-- <li><a href="<?php echo site_url('/Customerlogin_add');?>">Login</a></li> -->
                                <li><a href="<?php echo site_url('/find_location');?>">Find Location</a>  </li>
                                <li><a href="<?php echo site_url('/track_shipment');?>">Track Shipment</a></li>
                                <li><a href="<?php echo site_url('/contacts_page');?>">Contact</a></li>
                                <li><a href="<?php echo site_url('/franchise/');?>">Franchise Login</a></li>
                               <!-- <li><a href="<?php echo site_url('/career');?>">Career</a></li>  -->
                    </div>
                </div>
            </div>
        </div>


    </header>
<body>