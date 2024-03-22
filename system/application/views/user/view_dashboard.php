   <?php include('admin_shared/admin_header.php'); ?>
    <!-- END Head-->

    <!-- START: Body-->
    <body id="main-container" class="default">
       
        <!-- END: Main Menu-->
		<?php include('admin_shared/admin_sidebar.php'); ?>
        <!-- END: Main Menu-->
		
        <!-- START: Main Content-->
        <main>
            <div class="container-fluid site-width">
                <!-- START: Breadcrumbs-->
                <div class="row">
                    <div class="col-12  align-self-center">
                        <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                            <div class="w-sm-100 mr-auto"><h4 class="mb-0">Dashboard</h4> <p>Welcome to admin panel</p></div>
                            <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- END: Breadcrumbs-->

                <!-- START: Card Data-->
                <div class="row"> 

                    <div class="col-12 col-md-6 col-lg-4 mt-3">
                        <div class="card">                            
                            <div class="card-content">
                                <div class="card-body">  
                                    <div class="d-flex"> 
                                        <div class="media-body align-self-center ">
                                            <span class="mb-0 h5 font-w-600">Montly Shippment</span><br>
                                            <p class="mb-0 font-w-500 tx-s-12"></p>                                                    
                                        </div>
                                        <!-- <div class="ml-auto border-0 outline-badge-warning circle-50"><span class="h5 mb-0">$</span></div> -->
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
                                    <div class="height-235">
										<input type="hidden" id="first" value="<?php echo $count_international_pod->int_cnt; ?>">
										<input type="hidden" id="second" value="<?php echo $count_delivered_international_pod->int_cnt; ?>">
										<input type="hidden" id="third" value="<?php echo $count_domestic_pod->int_cnt; ?>">
                                        <input type="hidden" id="fourth" value="<?php echo $count_delivered_domestic_pod->int_cnt; ?>">
										
										<input type="hidden" id="label_first" value="International : <?php echo $count_international_pod->int_cnt; ?>">
										<input type="hidden" id="label_second" value="Delivered International : <?php echo $count_delivered_international_pod->int_cnt; ?>">
										<input type="hidden" id="label_third" value="Domestic : <?php echo $count_domestic_pod->int_cnt; ?>">
                                        <input type="hidden" id="label_fourth" value="Delivered Domestic : <?php echo $count_delivered_domestic_pod->int_cnt; ?>">

                                        <canvas id="chartjs-other-pie"></canvas>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-12 col-lg-12 mt-3">
                        <div class="card">
                            <div class="card-header  justify-content-between align-items-center">                               
                                <h6 class="card-title">Latest International Shippment</h6> 
                            </div>
                            <div class="card-body table-responsive p-0">                         

                                <table class="table font-w-600 mb-0">
                                    <thead>
                                        <tr>                                           
                                            <th>AWB No</th>
                                            <th>Sender</th>
                                            <th>Receiver</th>
                                            <th>Country</th>  
                                            <th>Date</th>
                                            <th>Amount</th>                                               

                                        </tr>
                                    </thead>
                                    <tbody>
                                         <?php 
                                    if (!empty($latest_international_shippment))
                                    {
                                        $cnt = 1;
                                        foreach ($latest_international_shippment as $value) 
                                        {
                                    ?>
                                        <tr class="zoom">    
                                                 <td>
                                                    <!--<a href="admin/view_edit_international_shipment/<?php echo $value['booking_id'];?>"><?php echo $value['pod_no'];?></a>-->
                                                    <?php echo '<a href="'.base_url().'users/track_shipment?pod_no='.$value['pod_no'].'&submit=1" target="_blank" title="Track" class="ring-point" >'.$value['pod_no'].'</a>';?>
                                                </td>
                                                <td><?php echo $value['sender_name'];?></td>
                                                <td><?php echo $value['contactperson_name'];?></td>
                                                <td><?php echo $value['country_name'];?></td>     
                                                <td style="width: 11%;"><?php echo date('d-m-Y',strtotime($value['booking_date']));?>
                                                <td class="text-success"><?php echo round($value['grand_total'],2);?></td>

                                          
                                        </tr>  
                                     <?php } } ?>

                                                              
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-12 mt-3">
                        <div class="card">
                            <div class="card-header  justify-content-between align-items-center">                               
                                <h6 class="card-title">Latest Domestic Shippment</h6> 
                            </div>
                            <div class="card-body table-responsive p-0">                         

                                <table class="table font-w-600 mb-0">
                                    <thead>
                                        <tr>                                           
                                            <th>AWB No</th>
                                            <th>Sender</th>
                                            <th>Receiver</th>
                                            <th>City</th>  
                                            <th>Date</th>
                                            <th>Amount</th>                                               

                                        </tr>
                                    </thead>
                                    <tbody>
                                         <?php 
                                    if (!empty($latest_domestic_shippment))
                                    {
                                        $cnt = 1;
                                        foreach ($latest_domestic_shippment as $value) 
                                        {
                                    ?>
                                        <tr class="zoom">    
                                                 <td>
                                                    <?php echo '<a href="'.base_url().'users/track_shipment?pod_no='.$value['pod_no'].'&submit=1" target="_blank" title="Track" class="ring-point" >'.$value['pod_no'].'</a>';?>
                                                </td>
                                                <td><?php echo $value['sender_name'];?></td>
                                                <td><?php echo $value['reciever_name'];?></td>
                                                <td><?php echo $value['city'];?></td>                                       
                                                <td style="width: 11%;"><?php echo date('d-m-Y',strtotime($value['booking_date']));?>
                                                <td class="text-success"><?php echo round($value['grand_total'],2);?></td>
                                        </tr>  
                                     <?php } } ?>                            
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                   

                </div>
                <!-- END: Card DATA-->                 
            </div>
        </main>
        <!-- END: Content-->
        <!-- START: Footer-->
        <?php include('admin_shared/admin_footer.php'); ?>
        <!-- START: Footer-->
    </body>
    <!-- END: Body-->
</html>
