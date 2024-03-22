  <!-- START: Footer-->
  </div>
  <!--<footer class="site-footer mt-3">-->
  <!--          2020 &copy; PICK-->
  <!--      </footer>-->
        <!-- END: Footer-->


        <!-- START: Back to top-->
        <a href="#" class="scrollup text-center"> 
            <i class="icon-arrow-up"></i>
        </a>
        <!-- END: Back to top-->

 
        <!-- START: Template JS-->
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/jquery/jquery-3.3.1.min.js"></script>
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/moment/moment.js"></script>
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>    
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/slimscroll/jquery.slimscroll.min.js"></script>
        <!-- END: Template JS-->

        <!-- START: APP JS-->
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/js/app.js"></script>
        <!-- END: APP JS-->

        <!-- START: Page Vendor JS-->
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/raphael/raphael.min.js"></script>
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/morris/morris.min.js"></script>
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/chartjs/Chart.min.js"></script>
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/starrr/starrr.js"></script>
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/jquery-flot/jquery.canvaswrapper.js"></script>
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/jquery-flot/jquery.colorhelpers.js"></script>
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/jquery-flot/jquery.flot.js"></script>
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/jquery-flot/jquery.flot.saturated.js"></script>
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/jquery-flot/jquery.flot.browser.js"></script>
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/jquery-flot/jquery.flot.drawSeries.js"></script>
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/jquery-flot/jquery.flot.uiConstants.js"></script>
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/jquery-flot/jquery.flot.legend.js"></script>
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/jquery-flot/jquery.flot.pie.js"></script>        
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/chartjs/Chart.min.js"></script>  
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/jquery-jvectormap/jquery-jvectormap-2.0.3.min.js"></script>
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/jquery-jvectormap/jquery-jvectormap-world-mill.js"></script>
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/jquery-jvectormap/jquery-jvectormap-de-merc.js"></script>
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/jquery-jvectormap/jquery-jvectormap-us-aea.js"></script>
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/vendors/apexcharts/apexcharts.min.js"></script>
        <!-- END: Page Vendor JS-->

        <!-- START: Page JS-->
        <script src="<?php echo base_url();?>assets/franchise_assets/dist/js/home.script.js"></script>
        <!-- END: Page JS-->
     


    <script>
        $('documents').ready(function () {

            $('#invoice_value ,#actual_weight,#chargable_weight,#recharge_wallet').keypress(function (event) {
                if (((event.which != 46 || (event.which == 46 && $(this).val() == '')) ||
                    $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57) || $(this).val().indexOf('.') !== -1 && event.keyCode == 190) {
                    event.preventDefault();
                }
            }).on('paste', function (event) {
                event.preventDefault();
            });
          
        });       
    </script>
<!-- Edit Contact -->
    </body>
    <!-- END: Body-->
</html>