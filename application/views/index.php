<?php include 'shared/web_header.php'; ?>
<body class="home header-v4 hide-topbar-mobile">
    <div id="page">

    <?php //include 'shared/web_menuold.php'; ?>
       <?php include 'shared/web_menu.php'; ?>



      
        <!--Main Slider-->
        <section class="rev_slider_wrapper nav-after">
            <div id="slider2" class="rev_slider" data-version="5.0">

                <ul>
				   <?php //print_r($homeslider);die();
                    foreach ($homeslider as $value) { ?>
                    <!-- SLIDE  -->
                    <li data-index="rs-4" data-transition="fade" data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-easein="default" data-easeout="default" data-masterspeed="300" data-rotate="0" data-saveperformance="off" data-title="Slide" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
                        <!-- MAIN IMAGE -->
                        <img src="assets/homeslider/<?php echo $value->slider_image; ?>" alt="" title="Home Page 2" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="0" class="rev-slidebg" data-no-retina>
                        <!-- LAYERS -->
					</li>
					 
					
					 <?php } ?>
                </ul>
            </div>
        </section>
        <!--Main Slider  end-->

        <div class="nile-about-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 sm-mb-45px">
                    <div class="section-title-right text-main-color clearfix">
                        <div class="icon"><img src="<?= base_url(); ?>assets2/icons/title-icon-1.png" alt=""></div>
                        <h2 class="title-text">BOX 'N' FREIGHT Logistics Solutions  ?</h2>
                    </div>
                    <div class="about-text margin-tb-20px">
                    <p style="">BOX 'N' FREIGHT Logistics Solutions is the succeeding
venture OF GLOBAL GROUP completely involved in
Courier & Cargo (Domestic & International), Full Truck
Load, Part Load, Logistics & Warehousing. </p>

  <p style="">We are specializing in Air, Road & Sea Transportation &
multimodal transportation, Freight, Consolidation,
Packaging, Transport & Logistics, Warehousing &
Customs Clearance. </p>
<p>
    
    We have ODC Carriers, 17 ft 22ft 40ft 70ft 90ft and all
variety of vehicles to suite customer requirement.
www.boxnfreight.com
With Reliability & Timely services, the company has
grown progressively to becoming a major & reliable
player in the sector. It's currently serving clients in all
states of the India offering tailor-made services suitable
for individual needs that start from the point of origin
through various custom procedures to the client's
doorstep and ensures that clients get personalized
attention. 
</p>
                    <div class="fh-button-wrapper text-center">
                    <a href="<?php echo site_url('/abouts_page');?>" class="nile-bottom md">About our Company</a>
                </div>
                </div>
                </div>
            </div>
        </div>
    </div>

        <!-- new services  -->
        <div class="section padding-tb-100px section-ba-1">
        <div class="container">
           
            <!-- Title -->
            <div class="section-title services margin-bottom-40px">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="icon text-main-color"><i class="fa fa-truck"></i></div>
                        <div class="h2">Our Service</div>
                        <!-- <div class="des">In quis luctus dolor. Sed ac libero arcu. Phasellus vulputate ultrices augue, eget feugiat lectus efficitur in. Nulla non pharetra justo. Nunc viverra consectetur bibendum. </div> -->
                    </div>
                </div>
            </div>
            <!-- // End Title -->

            <div class="row">
    
                <div class="col-lg-3 col-md-6">
                        <div class="service-icon-box">
                            <div class="icon"><img src="<?= base_url(); ?>assets2/icons/service-dark-2.png" alt=""></div>
                            <a href="<?php echo site_url('/services_page');?>" class="title h2">Air  Cargo Services</a>
                            <div class="des">Box N Freight offers specialized and reliable cost-effective Domestic & International fast air logistics services in India for time-sensitive</div>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <div class="service-icon-box">
                            <div class="icon"><img src="<?= base_url(); ?>assets2/icons/service-dark-3.png" alt=""></div>
                            <a href="<?php echo site_url('/services_page');?>" class="title h2">Ocean Freight Services</a>
                            <div class="des">International freight forwarding with Box N Freight personalised to suit you. We look after your logistics needs so you can focus on your core business responsibilities.</div>
                        </div>
                    </div>

                <div class="col-lg-3 col-md-6">
                    <div class="service-icon-box">
                        <div class="icon"><img src="<?= base_url(); ?>assets2/icons/service-dark-1.png" alt=""></div>
                        <a href="<?php echo site_url('/services_page');?>" class="title h2">Surface Logistics</a>
                        <div class="des">Surface Logistics is basically transferring goods from one place to another via Road. The Surface freight services are the most common option that deliver your goods on time with Box N Freight.</div>
                    </div>
                </div>



                <div class="col-lg-3 col-md-6">
                    <div class="service-icon-box">
                        <div class="icon"><img src="<?= base_url(); ?>assets2/icons/service-dark-4.png" alt=""></div>
                        <a href="<?php echo site_url('/services_page');?>" class="title h2">Warehousing</a>
                        <div class="des">Warehousing refers to a storage system used for protecting the quantity and quality of the stored products. Box N Freight have own warehouse facility at different differnt locations.</div>
                    </div>
                </div>
            </div>


            <div class="text-center">
                <a href="<?php echo site_url('/services_page');?>" class="nile-bottom md">Show all <i class="fa fa-arrow-right"></i> </a>
            </div>

        </div>
    </div>


        <!--Why choose us-->
        <section class="whychoose-1 home4form">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-md-6  secpaddlf">
                        <div class="fh-section-title clearfix  text-left version-dark paddbtm40">
                            <h2>Why Choosing us?</h2>
                        </div>
                        <div class="fh-icon-box  style-2 icon-left has-line">
                            <span class="fh-icon"><i class="flaticon-international-delivery"></i></span>
                            <h4 class="box-title"><span>Global supply Chain Logistics</span></h4>
                            <div class="desc">
                                <p>Efficiently unleash cross-media information without cross-media value.</p>
                            </div>
                        </div>
                        <div class="fh-icon-box  style-2 icon-left has-line">
                            <span class="fh-icon"><i class="flaticon-people"></i></span>
                            <h4 class="box-title"><span>24 Hours - Technical Support</span></h4>
                            <div class="desc">
                                <p>Specialises in international freight forwarding of merchandise and associated logistic services</p>
                            </div>
                        </div>
                        <div class="fh-icon-box  style-2 icon-left has-line">
                            <span class="fh-icon"><i class="flaticon-route"></i></span>
                            <h4 class="box-title"><span>Mobile Shipment Tracking</span></h4>
                            <div class="desc">
                                <p>We Offers intellgent concepts for road and tail and well as complex special transport services</p>
                            </div>
                        </div>
                        <div class="fh-icon-box  style-2 icon-left">
                            <span class="fh-icon"><i class="flaticon-open-cardboard-box"></i></span>
                            <h4 class="box-title"><span>Careful Handling of Valuable Goods</span></h4>
                            <div class="desc">
                                <p>Justlog are transported at some stage of their journey along the world’s roads</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 quofrm1  secpaddlf">
                        <div class="fh-section-title clearfix  text-left version-dark paddbtm40">
                            <h2>REQUEST A QUOTE</h2>
                        </div>
                        <form>
                            <div class="fh-form-1 fh-form">
                                <div class="row fh-form-row">
                                    <div class="col-md-6 col-xs-12 col-sm-12">
                                        <p class="field">
                                            <select>
                                                <option value="Services">Services</option>
                                                <option value="Services 1">Services 1</option>
                                                <option value="Services 2">Services 2</option>
                                            </select>
                                        </p>
                                    </div>
                                    <div class="col-md-6 col-xs-12 col-sm-12">
                                        <p class="field">
                                            <input name="delivery-city" value="" placeholder="Delivery City*" type="text">
                                        </p>
                                    </div>
                                    <div class="col-md-6 col-xs-12 col-sm-12">
                                        <p class="field">
                                            <input name="distance" value="" placeholder="Distance*" type="text">
                                        </p>
                                    </div>
                                    <div class="col-md-6 col-xs-12 col-sm-12">
                                        <p class="field">
                                            <input name="weight" value="" placeholder="Weight*" type="text">
                                        </p>
                                    </div>
                                    <div class="col-md-6 col-xs-12 col-sm-12">
                                        <p class="field">
                                            <input name="your-name" value="" placeholder="Name*" type="text">
                                        </p>
                                    </div>
                                    <div class="col-md-6 col-xs-12 col-sm-12">
                                        <p class="field">
                                            <input name="your-email" value="" placeholder="Email*" type="email">
                                        </p>
                                    </div>
                                    <div class="col-md-12 col-xs-12 col-sm-12">
                                        <p class="field single-field">
                                            <textarea cols="40" placeholder="Message*"></textarea>
                                        </p>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <p class="field submit">
                                            <input value="Submit" class="fh-btn" type="submit">
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>



        
        <!-- <style>
            .layout-1 i{
            font-size:40px !important;
           }
        </style> -->
          

        <div class="section padding-tb-100px section-ba-2 nile-about-section">
        <div class="container">
            <div class="row">

                <div class="col-lg-4 col-md-6">
                    <div class="testimonials layout-1">
                        <div><i class="flaticon-internet"></i></div>
                        <div class="text">There are many variations of passages of available, but the majority have suffered alteration in some form, by or randomised slightly believable.</div>
                        <div class="meta">
                             <div class="name">Fast worldwide delivery</div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-4 col-md-6">
                    <div class="testimonials layout-1">
                        <div class="fh-icon"><i class="flaticon-technology"></i></div>
                        <div class="text">There are many variations of passages of available, but the majority have suffered alteration in some form, by or randomised slightly believable.</div>
                        <div class="meta">
                            <div class="name">24/7 customer support</div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-4 col-md-6">
                    <div class="testimonials layout-1">
                        <div><i class="flaticon-shield"></i></div>
                        <div class="text">Must explain to you how all this mistaken idea of out denouncing pleasure and praising pain was born and sed a complete account of the system.</div>
                        <div class="meta">
                            <div class="name">Safe and Secure</div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    

        <!-- <div class="map-layout">
        <div class="map-embed">
           <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15132.53544903305!2d73.866415!3d18.522852!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x9ea7325563ecd454!2sJUSTLOG%20Logistics%20LLP!5e0!3m2!1sen!2sin!4v1647067632831!5m2!1sen!2sin" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>

    
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8"></div>
                <div class="col-lg-4">
                    <div class="padding-tb-50px padding-lr-30px background-main-color pull-top-309px">
                        <div class="contact-info-map">
                            <div class="margin-bottom-30px">
                                <h2 class="title">Location</h2>
                                <div class="contact-info opacity-9">
                                    <div class="icon margin-top-5px"><span class="icon_pin_alt"></span></div>
                                    <div class="text">
                                        <span class="title-in">Location :</span> <br>
                                        <span class="font-weight-500">India Pune</span>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="call_center margin-top-30px">
                                <h2 class="title">Call Center</h2>
                                <div class="contact-info opacity-9">
                                    <div class="icon  margin-top-5px"><span class="icon_phone"></span></div>
                                    <div class="text">
                                        <span class="title-in">Call Us :</span><br>
                                        <span class="font-weight-500 text-uppercase"><?php echo $company_info->phone_no; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

        <!-- </div> -->



<?php include 'shared/web_footer.php'; ?>