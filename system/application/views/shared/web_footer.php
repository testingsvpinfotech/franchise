<style type="text/css">
    .footer-sidebars1 {
    padding-top: 60px;
    padding-bottom: 30px;
}
</style>
<footer class="layout-light">
        <div class="container padding-tb-80px">
            <div class="row">

                <div class="col-lg-3 col-md-6">
                    <div class="about-us sm-mb-45px">
                        <div class="logo-footer margin-bottom-35px">
                       <a href="<?php echo site_url();?>"> <img src="assets/company/<?php echo $company_info->logo; ?>" width="" height="" alt="<?php echo $company_info->company_name; ?>" class="logo-light hide-logo" ></a>
                        </div>
                        <h4 class="widget-title" >About <?php echo $company_info->company_name; ?></h4>
                        <div class="text margin-bottom-35px">
                        <p><?php echo $company_info->company_name; ?> Logistics Services is a global supplier of transport and logistics solutions. We have offices in more than 20 countries and an <br> international network of partners and agents.</p>
                        </div>
                        <a href="<?php echo site_url('/abouts_page');?>" class="nile-bottom sm">Read More</a>
                    </div>
                </div>

                
                <div class="col-lg-3 col-md-6">
                    <div class="nile-widget widget_nav_menu sm-mb-45px">
                        <h2 class="title">USEFUL LINKS</h2>
                        <ul class="footer-menu">
                            <li><a href="<?php echo site_url();?>">Home </a></li>
                            <li><a href="<?php echo site_url('/abouts_page');?>">About Us </a></li>
                            <li><a href="<?php echo site_url('/services_page');?>"> Services </a></li>
                            <li><a href="<?php echo site_url('/Customerlogin_add');?>">login</a></li>
                        </ul>
                    </div>
                </div>


                <div class="col-lg-3 col-md-6">
                    <div class="nile-widget widget_nav_menu sm-mb-45px">
                    <h2 class="title">USEFUL LINKS</h2>
                        <ul class="footer-menu">
                            <li><a href="<?php echo site_url('/find_location');?>">Find Location</a></li>
                            <li><a href="<?php echo site_url('/track_shipment');?>">Track Shipment </a></li>
                            <li><a href="<?php echo site_url('/contacts_page');?>"> Contact </a></li>
                            <li><a href="<?php echo site_url('/career');?>">Career</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="nile-widget widget_nav_menu sm-mb-45px">
                        <h2 class="title">Corporate Office:</h2>
                        <p>Jagdamba House, 5th floor, next to Anupam Cinema, Peru Baug, Goregaon (East), Mumbai 400 063.	</p>
                        
                    </div>
                </div>

            </div>
        </div>
        <div class="copy-right">
            <div class="container padding-tb-10px">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="copy-right-text text-lg-left text-center sm-mb-15px"><a target="_blank" href="#">Copyright &copy; 2022 <a href="#"><?php echo $company_info->company_name; ?></a>, All Right Reserved</a> </div>
                    </div>
                    <div class="col-lg-6">
                        <!--  Social -->
                        <ul class="social-media list-inline text-lg-right text-center margin-0px text-dark">
                            <li class="list-inline-item"><a class="facebook" href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                            <li class="list-inline-item"><a class="youtube" href="#"><i class="fa fa-youtube-play" aria-hidden="true"></i></a></li>
                            <li class="list-inline-item"><a class="linkedin" href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                            <li class="list-inline-item"><a class="google" href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                            <li class="list-inline-item"><a class="twitter" href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            <li class="list-inline-item"><a class="rss" href="#"><i class="fa fa-rss" aria-hidden="true"></i></a></li>
                        </ul>
                        <!-- // Social -->
                    </div>
                </div>
            </div>
        </div><br>
    </footer>





   

    <!-- jquery Liabrary -->
    <script src="<?php echo base_url(); ?>assets/web_assets/js/jquery-1.12.4.min.js"></script>
    <!-- bootstrap v3.3.6 js -->
    <script src="<?php echo base_url(); ?>assets/web_assets/js/bootstrap.min.js"></script>
    <!-- fancybox js -->
    <script src="<?php echo base_url(); ?>assets/web_assets/js/jquery.fancybox.pack.js"></script>
    <script src="<?php echo base_url(); ?>assets/web_assets/js/jquery.fancybox-media.js"></script>
    <script src="../../../unpkg.com/isotope-layout%403.0.6/dist/isotope.pkgd.min.js"></script>
    <!-- owl.carousel js -->
    <script src="<?php echo base_url(); ?>assets/web_assets/js/owl.js"></script>
    <!-- counter js -->
    <script src="<?php echo base_url(); ?>assets/web_assets/js/jquery.appear.js"></script>
    <script src="<?php echo base_url(); ?>assets/web_assets/js/jquery.countTo.js"></script>
    <!-- validate js -->
    <script src="<?php echo base_url(); ?>assets/web_assets/js/validate.js"></script>
    <!-- switcher js -->
    <script src="<?php echo base_url(); ?>assets/web_assets/js/switcher.js"></script>

    <!-- google map -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAHzPSV2jshbjI8fqnC_C4L08ffnj5EN3A"></script>
    <script src="<?php echo base_url(); ?>assets/web_assets/js/gmap.js"></script>
    <script src="<?php echo base_url(); ?>assets/web_assets/js/map-helper-2.js"></script>

    <!-- REVOLUTION JS FILES -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/web_assets/js/revolution/jquery.themepunch.tools.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/web_assets/js/revolution/jquery.themepunch.revolution.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/web_assets/js/revolution/extensions/revolution.extension.actions.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/web_assets/js/revolution/extensions/revolution.extension.carousel.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/web_assets/js/revolution/extensions/revolution.extension.kenburn.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/web_assets/js/revolution/extensions/revolution.extension.layeranimation.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/web_assets/js/revolution/extensions/revolution.extension.migration.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/web_assets/js/revolution/extensions/revolution.extension.navigation.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/web_assets/js/revolution/extensions/revolution.extension.parallax.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/web_assets/js/revolution/extensions/revolution.extension.slideanims.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/web_assets/js/revolution/extensions/revolution.extension.video.min.js"></script>

    <!-- script JS  -->
    <script src="<?php echo base_url(); ?>assets/web_assets/js/scripts.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/web_assets/js/script.js"></script>
</body>

<!-- added by priesh  -->
<script src="<?= base_url();?>assets2/js/nile-slider.js"></script>
    <script src="<?= base_url();?>assets2/js/jquery-3.2.1.min.js"></script>
    <!-- REVOLUTION JS FILES -->
    <script src="<?= base_url();?>assets2/rslider/js/jquery.themepunch.tools.min.js"></script>
    <script src="<?= base_url();?>assets2/rslider/js/jquery.themepunch.revolution.min.js"></script>
    <!-- SLIDER REVOLUTION 5.0 EXTENSIONS -->
    <script src="<?= base_url();?>assets2/rslider/js/extensions/revolution.extension.actions.min.js"></script>
    <script src="<?= base_url();?>assets2/rslider/js/extensions/revolution.extension.carousel.min.js"></script>
    <script src="<?= base_url();?>assets2/rslider/js/extensions/revolution.extension.kenburn.min.js"></script>
    <script src="<?= base_url();?>assets2/rslider/js/extensions/revolution.extension.layeranimation.min.js"></script>
    <script src="<?= base_url();?>assets2/rslider/js/extensions/revolution.extension.migration.min.js"></script>
    <script src="<?= base_url();?>assets2/rslider/js/extensions/revolution.extension.navigation.min.js"></script>
    <script src="<?= base_url();?>assets2/rslider/js/extensions/revolution.extension.parallax.min.js"></script>
    <script src="<?= base_url();?>assets2/rslider/js/extensions/revolution.extension.slideanims.min.js"></script>
    <script src="<?= base_url();?>assets2/rslider/js/extensions/revolution.extension.video.min.js"></script>
    <script src="<?= base_url();?>assets2/js/YouTubePopUp.jquery.js"></script>
    <script src="<?= base_url();?>assets2/js/owl.carousel.min.js"></script>
    <script src="<?= base_url();?>assets2/js/imagesloaded.min.js"></script>
    <script src="<?= base_url();?>assets2/js/custom.js"></script>
    <script src="<?= base_url();?>assets2/js/popper.min.js"></script>
    <script src="<?= base_url();?>assets2/js/bootstrap.min.js"></script>

</html>