<?php include('master_franchise_shared/admin_header.php'); ?>
    <!-- END Head-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Previous Month',     11],
          ['Current Month',      2]
        ]);

        var options = {
          title: '',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
    </script>
    <!-- START: Body-->
    <body id="main-container" class="default">
       
        <!-- END: Main Menu-->
		<?php include('master_franchise_shared/admin_sidebar.php'); ?>
        <!-- END: Main Menu-->
		
        <!-- START: Main Content-->
        <main>
            <div class="container-fluid site-width">
                <!-- START: Breadcrumbs-->
                <div class="row">
                    <div class="col-12  align-self-center">
                        <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                            <div class="w-sm-100 mr-auto"><h4 class="mb-0">Dashboard</h4> <p>Welcome to Franchise panel</p></div>
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
                   
                    <div class="col-12 col-md-6 col-lg-4 mt-3"> <?php $customer_id = $this->session->userdata('customer_id');
	$franchise_list = $this->db->query("select * from tbl_customers where parent_cust_id = $customer_id ")->result_array(); ?>
                    <span class="mb-0 h5 font-w-600">Unit Franchise Monthly shipment's</span>
                    <select name="" class="form-control" id="">
                    <?php  foreach($franchise_list as $value)
										{ ?>
                        <option value="<?php echo  $value['customer_id']; ?>"><?php echo  $value['customer_name']; ?></option>
                        <?php } ?>
                    </select>
                    <div id="piechart_3d" style="width: 500px; height: 250px;"></div>
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
        <?php include('master_franchise_shared/admin_footer.php'); ?>
        <!-- START: Footer-->
    </body>
    <!-- END: Body-->
</html>
