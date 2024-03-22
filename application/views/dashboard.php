   <?php include('franchise_shared/franchise_header.php'); ?>
    <!-- END Head-->
    	<?php include('franchise_shared/franchise_sidebar.php'); ?>
    	<main>
           <div class="container-fluid site-width">
                <!-- START: Breadcrumbs-->
                <div class="row">
                    <div class="col-12  align-self-center">
                        <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                            <div class="w-sm-100 mr-auto"><h4 class="mb-0">Dashboard</h4> <p>Welcome to liner admin panel</p></div>
                       
                        </div>
                    </div>
                </div>
                <!-- END: Breadcrumbs-->

                <!-- START: Card Data-->
                <div class="row">
                    <div class="col-12 col-lg-12  mt-3">
                                <div class="row">
                                    <div class="col-12 col-sm-4 mt-3">
                                        <div class="card bg-primary">
                                            <div class="card-body">
                                                <div class='d-flex px-0 px-lg-2 py-2 align-self-center'>
                                                    <i class="icon-basket icons card-liner-icon mt-2 text-white"></i>
                                                    <div class='card-liner-content'>
                                                        <h2 class="card-liner-title text-white">2,390</h2>
                                                        <h6 class="card-liner-subtitle text-white">Today Booking shipment</h6>                                       
                                                    </div>                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4 mt-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class='d-flex px-0 px-lg-2 py-2 align-self-center'>
                                                    <i class="icon-user icons card-liner-icon mt-2"></i>
                                                    <div class='card-liner-content'>
                                                        <h2 class="card-liner-title">9,390</h2>
                                                        <h6 class="card-liner-subtitle">Total Delivered Orders</h6> 
                                                    </div>                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4  mt-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class='d-flex px-0 px-lg-2 py-2 align-self-center'>
                                                    <i class="icon-bag icons card-liner-icon mt-2"></i>
                                                    <div class='card-liner-content'>
                                                        <h2 class="card-liner-title">$4,390</h2>
                                                        <h6 class="card-liner-subtitle">Total Booking Shipment</h6> 
                                                    </div>                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- <div class="row">
                                    <div class="col-12 col-sm-4 mt-3">
                                        <div class="card bg-primary">
                                            <div class="card-body">
                                                <div class='d-flex px-0 px-lg-2 py-2 align-self-center'>
                                                    <i class="icon-basket icons card-liner-icon mt-2 text-white"></i>
                                                    <div class='card-liner-content'>
                                                        <h2 class="card-liner-title text-white">2,390</h2>
                                                        <h6 class="card-liner-subtitle text-white">Total Delivered Orders</h6>                                       
                                                    </div>                                
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                    <!-- <div class="col-12 col-sm-4 mt-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class='d-flex px-0 px-lg-2 py-2 align-self-center'>
                                                    <i class="icon-user icons card-liner-icon mt-2"></i>
                                                    <div class='card-liner-content'>
                                                        <h2 class="card-liner-title">9,390</h2>
                                                        <h6 class="card-liner-subtitle">Today's Visitors</h6> 
                                                    </div>                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4  mt-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class='d-flex px-0 px-lg-2 py-2 align-self-center'>
                                                    <i class="icon-bag icons card-liner-icon mt-2"></i>
                                                    <div class='card-liner-content'>
                                                        <h2 class="card-liner-title">$4,390</h2>
                                                        <h6 class="card-liner-subtitle">Today's Sale</h6> 
                                                    </div>                                
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                           
                    </div>   

                    <!-- <div class="col-12 col-md-6 col-lg-4 mt-3">
                        <div class="card">                            
                            <div class="card-content">
                                <div class="card-body">  
                                    <div class="d-flex"> 
                                        <div class="media-body align-self-center ">
                                            <span class="mb-0 h5 font-w-600">Daily Reports</span><br>
                                            <p class="mb-0 font-w-500 tx-s-12">San Francisco, California, USA</p>                                                    
                                        </div>
                                        <div class="ml-auto border-0 outline-badge-warning circle-50"><span class="h5 mb-0">$</span></div>
                                    </div>
                                    <div id="flot-report" class="height-175 w-100 mt-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 mt-3">
                        <div class="card">                            
                            <div class="card-content">
                                <div class="card-body"> 
                                    <div class="d-flex"> 
                                        <div class="media-body align-self-center ">
                                            <span class="mb-0 h5 font-w-600">Stats Reports</span><br>
                                            <p class="mb-0 font-w-500 tx-s-12">San Francisco, California, USA</p>                                                    
                                        </div>
                                        <div class="ml-auto border-0 outline-badge-success circle-50"><span class="h5 mb-0">$</span></div>
                                    </div>
                                    <div class="d-flex mt-4">
                                        <div class="border-0 outline-badge-info w-50 p-3 rounded text-center"><span class="h5 mb-0">Income</span><br/>                                        
                                            $78,600
                                        </div>
                                        <div class="border-0 outline-badge-success w-50 p-3 rounded ml-2 text-center"><span class="h5 mb-0">Sales</span><br/>                                        
                                            $1,24,600
                                        </div>
                                    </div>

                                    <div class="d-flex mt-3">
                                        <div class="border-0 outline-badge-dark w-50 p-3 rounded text-center"><span class="h5 mb-0">Users</span><br/>                                        
                                            4,600
                                        </div>
                                        <div class="border-0 outline-badge-danger w-50 p-3 rounded ml-2 text-center"><span class="h5 mb-0">Orders</span><br/>                                        
                                            2,600
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 mt-3">
                        <div class="card">                            
                            <div class="card-content">
                                <div class="card-body">  
                                    <div class="height-235">
                                        <canvas id="chartjs-other-pie"></canvas>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div> -->

                    <!-- <div class="col-12  col-md-6 col-lg-3 mt-3">
                        <div class="card border-bottom-0">                            
                            <div class="card-content border-bottom border-primary border-w-5">
                                <div class="card-body p-4">                                   
                                    <div class="d-flex">                                        
                                        <img src="dist/images/author1.jpg" alt="author" width="55" class="rounded-circle  ml-auto">
                                        <div class="media-body align-self-center pl-3">
                                            <span class="mb-0 font-w-600">Jonathan</span><br>
                                            <p class="mb-0 font-w-500 tx-s-12">San Francisco, California, USA</p>                                                    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mt-3 border-bottom-0">                            
                            <div class="card-content border-bottom border-warning border-w-5">
                                <div class="card-body p-4">
                                    <p class="mb-3 font-w-600">View Order > Confirm Order</p>
                                    <p class="font-w-500 tx-s-12"><i class="icon-calendar"></i> March 14th, 2021</p> 
                                    <p class="font-w-500 tx-s-12">Time estimate: 12 days</p> 
                                    <div class="d-flex">
                                        <div class="my-auto line-h-1">
                                            <span class="badge outline-badge-secondary">Medium</span> 
                                            <span class="badge outline-badge-success ml-2">Tracking</span>                                       
                                        </div>
                                        <img src="dist/images/author9.jpg" alt="author" width="30" class="rounded-circle  ml-auto">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card border-bottom-0 mt-3">                            
                            <div class="card-content border-bottom border-info border-w-5">
                                <div class="card-body p-4">                                   
                                    <div class="d-flex">                                        
                                        <img src="dist/images/author9.jpg" alt="author" width="55" class="rounded-circle  ml-auto">
                                        <div class="media-body align-self-center pl-3">
                                            <span class="mb-0 font-w-600">Kelvin</span><br>
                                            <p class="mb-0 font-w-500 tx-s-12">San Francisco, California, USA</p>                                                    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <div class="col-md-12 col-lg-12 mt-3">
                        <div class="card overflow-hidden">
                            <div class="card-header d-flex justify-content-between align-items-center">                               
                                <h6 class="card-title">Today Booking Shipment</h6>
                            </div>
                            <div class="card-content">
                                <div class="card-body p-0">
                                    <ul class="list-group list-unstyled">
                                        <li class="p-2 border-bottom zoom">
                                            <div class="media d-flex w-100">
                                                <a href="#"><img src="dist/images/author2.jpg" alt="" class="img-fluid ml-0 mt-2  rounded-circle" width="40"></a>
                                                <div class="media-body align-self-center pl-2">
                                                    <span class="mb-0 font-w-600">kelvin</span><br>
                                                    <p class="mb-0 font-w-500 tx-s-12">San Francisco, California, USA</p>                                                    
                                                </div>
                                                <div class="ml-auto my-auto">
                                                    <a href="#"  data-toggle="dropdown">
                                                        <i class="icon-options icons h6 font-weight-bold"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                                            <span class="icon-pencil mr-2 h6 mb-0"></span> Edit Profile</a>
                                                        <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                                            <span class="icon-user mr-2 h6 mb-0"></span> View Profile</a>
                                                        <a href="" class="dropdown-item px-2 text-danger align-self-center d-flex">
                                                            <span class="icon-trash mr-2 h6  mb-0"></span> Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="p-2 border-bottom zoom">
                                            <div class="media d-flex w-100">
                                                <a href="#"><img src="dist/images/author3.jpg" alt="" class="img-fluid ml-0 mt-2 rounded-circle" width="40"></a>
                                                <div class="media-body align-self-center pl-2">
                                                    <span class="mb-0 font-w-600">Peter</span><br>
                                                    <p class="mb-0 font-w-500 tx-s-12">San Francisco, California, USA</p>                                                   
                                                </div>
                                                <div class="ml-auto my-auto">
                                                    <a href="#"  data-toggle="dropdown">
                                                        <i class="icon-options icons h6 font-weight-bold"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                                            <span class="icon-pencil mr-2 h6 mb-0"></span> Edit Profile</a>
                                                        <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                                            <span class="icon-user mr-2 h6 mb-0"></span> View Profile</a>
                                                        <a href="" class="dropdown-item px-2 text-danger align-self-center d-flex">
                                                            <span class="icon-trash mr-2 h6  mb-0"></span> Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="p-2 border-bottom zoom">
                                            <div class="media d-flex w-100">
                                                <a href="#"><img src="dist/images/author9.jpg" alt="" class="img-fluid ml-0 mt-2 rounded-circle" width="40"></a>
                                                <div class="media-body align-self-center pl-2">
                                                    <span class="mb-0 font-w-600">Ray Sin</span><br>
                                                    <p class="mb-0 font-w-500 tx-s-12">San Francisco, California, USA</p>                                                 
                                                </div>
                                                <div class="ml-auto my-auto">
                                                    <a href="#"  data-toggle="dropdown">
                                                        <i class="icon-options icons h6 font-weight-bold"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                                            <span class="icon-pencil mr-2 h6 mb-0"></span> Edit Profile</a>
                                                        <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                                            <span class="icon-user mr-2 h6 mb-0"></span> View Profile</a>
                                                        <a href="" class="dropdown-item px-2 text-danger align-self-center d-flex">
                                                            <span class="icon-trash mr-2 h6  mb-0"></span> Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="p-2 border-bottom zoom">
                                            <div class="media d-flex w-100">
                                                <a href="#"><img src="dist/images/author6.jpg" alt="" class="img-fluid ml-0 mt-2 rounded-circle" width="40"></a>
                                                <div class="media-body align-self-center pl-2">
                                                    <span class="mb-0 font-w-600">Abexon Dixon</span><br/>
                                                    <p class="mb-0 font-w-500 tx-s-12">San Francisco, California, USA</p>                                              
                                                </div>

                                                <div class="ml-auto mail-tools">
                                                    <a href="#"  data-toggle="dropdown">
                                                        <i class="icon-options icons h6 font-weight-bold"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                                            <span class="icon-pencil mr-2 h6 mb-0"></span> Edit Profile</a>
                                                        <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                                            <span class="icon-user mr-2 h6 mb-0"></span> View Profile</a>
                                                        <a href="" class="dropdown-item px-2 text-danger align-self-center d-flex">
                                                            <span class="icon-trash mr-2 h6  mb-0"></span> Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="p-2 zoom">
                                            <div class="media d-flex w-100">
                                                <a href="#"><img src="dist/images/author7.jpg" alt="" class="img-fluid ml-0 mt-2 rounded-circle" width="40"></a>
                                                <div class="media-body align-self-center pl-2">
                                                    <span class="mb-0 font-w-600">Nathan S. Johnson</span><br/>
                                                    <p class="mb-0 font-w-500 tx-s-12">San Francisco, California, USA</p>                                              
                                                </div>

                                                <div class="ml-auto mail-tools">
                                                    <a href="#"  data-toggle="dropdown">
                                                        <i class="icon-options icons h6 font-weight-bold"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                                            <span class="icon-pencil mr-2 h6 mb-0"></span> Edit Profile</a>
                                                        <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                                            <span class="icon-user mr-2 h6 mb-0"></span> View Profile</a>
                                                        <a href="" class="dropdown-item px-2 text-danger align-self-center d-flex">
                                                            <span class="icon-trash mr-2 h6  mb-0"></span> Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                    </ul> 
                                </div>
                            </div>
                        </div>
                    </div>

                   
                </div>
                <!-- END: Card DATA-->                 
            </div>
        </main>
        <!-- START: Footer-->
        <?php include('franchise_shared/franchise_footer.php'); ?>
