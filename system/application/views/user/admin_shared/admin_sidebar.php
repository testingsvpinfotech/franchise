<!-- START: Pre Loader
        <div class="se-pre-con">
            <div class="loader"></div>
        </div>
        START: Header-->
<div id="header-fix" class="header fixed-top ">
	<div class="site-width">
		<nav class="navbar navbar-expand-lg  p-0">
			<div class="navbar-header  h-100 h4 mb-0 align-self-center logo-bar text-left">
				<a href="javascript:void(0);" class="horizontal-logo text-left">
					<?php $company_details = $this->basic_operation_m->get_table_row('tbl_company',array('id'=>1)); ?>
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
										
										<li class="active"><a href="User_panel/dashboard"><i class="icon-home mr-1"></i> Dashboard</a></li>
										<li class="active"><a href="User_panel/report"><i class="ion-android-list"></i> Report</a></li>
										<li class="dropdown"><a href="javascript:void(0);">Domestic</a>
											<ul class="sub-menu">
												<li>
													<li class="active"><a href="User_panel/list_domestic_shipment"><i class="icon-fire"></i> Domestic Shipment</a></li>
													<li><a href="User_panel/add_domestic_shipment"><i class="fa fa-eye"></i> Add Shipment</a></li>
												</li>
											</ul>
										</li>
										<li class="dropdown"><a href="#">International</a>
											<ul class="sub-menu">
												<li>
													<li class="active"><a href="User_panel/list_international_shipment"><i class="icon-fire"></i> International Shipment</a></li>
													<li><a href="User_panel/add_international_shipment"><i class="fa fa-eye"></i> Add Shipment</a></li>
												</li>
											</ul>
										</li>
										
										<li class="dropdown"><a href="javascript:void(0);">Pickup Request</a>
											<ul class="sub-menu">
												<li>
													<li class="active"><a href="User_panel/pickup_request"><i class="icon-fire"></i> Add Pickup Request</a></li>
													<li><a href="User_panel/view_pickup_request"><i class="fa fa-eye"></i> View Pickup Request</a></li>
												</li>
											</ul>
										</li>
										<li class="active"><a href="User_panel/pod"><i class="icon-fire"></i> Pod search</a></li>
										<li class="active"><a href="User_panel/complain_view"><i class="icon-fire"></i> Complain</a></li>
										                                           
                                        </ul>
                                    </li>
								
                                </ul>
								
								
                                
                            </li></ul></div>
                        </div>
                        <!-- END: Main Menu-->
                        
                       <ul id="top-menu" class="top-menu">   
                        
                            <li class="dropdown user-profile align-self-center d-inline-block">
                                <a href="#" class="nav-link py-0" data-toggle="dropdown" aria-expanded="false"> 
                                    <div class="media">                                   
                                        <img src="assets/image/avtar.png" alt="" title="LU0001" class="d-flex img-fluid rounded-circle" width="29">
                                    </div>
                                </a>
                                <center><b><?php echo $this->session->userdata('customer_name');?></b></center>                                                                  
                                <div class="dropdown-menu border dropdown-menu-right p-0">
                                    <a href="Login/logout" class="dropdown-item px-2 text-danger align-self-center d-flex">
                                        <span class="icon-logout mr-2 h6  mb-0"></span> Sign Out</a>
                                </div>

                            </li>

                        </ul>
                    </div>
				</nav>
			</div>
		</div>
		<!-- END: Header-->
		