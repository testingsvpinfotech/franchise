  <!-- Preloader-->
        <div class="preloader"></div>

        <!-- topbar -->
        <div id="fh-header-minimized" class="fh-header-minimized fh-header-v3"></div>
        <div id="topbar" class="topbar">
            <div class="container">

                <div class="topbar-left topbar-widgets text-left">
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
                </div>

                <div class="topbar-right topbar-widgets text-right">
                    <div class="widget cargohub-social-links-widget">
                        <div class="list-social style-1">
                            <a href="#" target="_blank"><i class="fa fa-facebook"></i></a>
                            <a href="#" target="_blank"><i class="fa fa-twitter"></i></a>
                            <a href="#" target="_blank"><i class="fa fa-google-plus"></i></a>
                            <a href="#" target="_blank"><i class="fa fa-skype"></i></a>
                            <a href="#" target="_blank"><i class="fa fa-youtube"></i></a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- topbar end -->
        <div class="ch-empty-space"></div>
        <!-- masthead -->
 <header id="masthead" class="site-header clearfix">
            <div class="header-main clearfix">
                <div class="fluid-container mobile_relative">
                    <div class="site-contact clearfix">

                        <div class="site-logo"style="text-align: left;" id="custom_html-5" >
                            <a href="index.php" class="logo">
                                <img src="assets/company/<?php echo $company_info->logo; ?>" width="50" height="200" alt="<?php echo $company_info->company_name; ?>" class="logo-light hide-logo" >
                                <img src="assets/company/<?php echo $company_info->logo; ?>"width="200" height="100" alt="<?php echo $company_info->company_name; ?>" class="logo-dark  show-logo">
                            </a>
                            <p class="site-title"><a href="#"><?php  echo $company_info->company_name; ?></a></p>
                            <h2 class="site-description">Complete Courier & Logistics</h2>
                        </div>

                        <div class="site-header-widget">
                          
                           




                            <div id="custom_html-3" class="widget_text widget">
                                <div class="textwidget custom-html-widget">
                                    <div class="header-contact"><span><i class="flaticon-phone-call "> </i></span>
                                        <p>Contact Number :</p>
                                        <h4><?php echo $company_info->phone_no; ?></h4></div>
                                </div>
                            </div>
                             <div id="custom_html-4" class="widget_text widget">
                                <div class="textwidget custom-html-widget">
                                    <div class="header-contact"><span><i class="fa fa-envelope "> </i></span>
                                        <p>Email :</p>
                                        <h4><?php echo $company_info->email; ?></h4></div>
                                </div>
                            </div>
                           
                        </div>
                        <a href="#" class="navbar-toggle">
                            <span class="navbar-icon">
								<span class="navbars-line"></span>
                            </span>
                        </a>
                    </div>

                    <div class="site-menu">
                        <div class="fluid-container">
                            <div class="row">
                                <div class="site-nav col-sm-12 col-xs-12">

                                    <nav id="site-navigation" class="main-nav primary-nav nav">
                                        <ul class="menu">
											<li class="nav-item">
												 <a href="<?php echo site_url();?>" class="nav-link">Home</a>
											  </li>
											  <li class="nav-item">
												 <a href="<?php echo site_url('/abouts_page');?>" class="nav-link">About Us</a>
											  </li>
											  <li class="nav-item">
												 <a href="<?php echo site_url('/services_page');?>" class="nav-link">Services</a>
											  </li>
											  <li class="nav-item">
												 <a href="<?php echo site_url('/Customerlogin_add');?>" class="nav-link">Login</a>
											  </li>
											  <li class="nav-item">
												 <a href="<?php echo site_url('/find_location');?>" class="nav-link">Find Location</a>
											  </li>
											  <li class="nav-item">
												 <a href="<?php echo site_url('/track_shipment');?>" class="nav-link">Track Shipment</a>
											  </li>
											    <li class="nav-item">
												 <a href="<?php echo site_url('/contacts_page');?>" class="nav-link">Contact</a>
											  </li>
											  <li class="nav-item">
												 <a href="<?php echo site_url('/career');?>" class="nav-link">Career</a>
											  </li>
											  
                                          
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- masthead end -->