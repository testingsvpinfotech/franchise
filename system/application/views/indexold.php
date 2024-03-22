<?php include 'shared/web_header.php'; ?>
<body class="home header-v4 hide-topbar-mobile">
    <div id="page">

       
       <?php include 'shared/web_menuold.php'; ?>
      
        <!--Main Slider-->
        <section class="rev_slider_wrapper">
            <div id="slider2" class="rev_slider" data-version="5.0">

                <ul>
				   <?php foreach ($homeslider as $value) { ?>
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
        <?php //include 'shared/web_menuold.php'; ?>

        <!-- welcome sec -->
        <div class="homeserv4 cearfix">
            <div class="container">
                <h4>We are JUSTLOG </h4>
                <p style="">JUSTLOG Logistics is set to accept the 'Change' that is coming in the new face-up business trends growing day by day to meet new challenges. So we thought of accepting the 'Change' and to support each and every corner of the industry in the courier & cargo field. We ensure Brand, Commitment & Costing.</p>
                <div class="fh-button-wrapper text-center">
                    <a href="<?php echo site_url('/abouts_page');?>" class="fh-button button align-center fh-btn">About our Company</a>
                </div>
            </div>
        </div>
        <!-- welcome sec end -->

        <!-- Services sec -->
        <div class="homeserv-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="fh-service-box-2 icon-type-theme_icon box-style-2 ">
                            <div class="box-thumb">
                                <span class="fh-icon"><i class="flaticon-transport-3"></i></span>
                                <img class="" src="assets/web_assets/images/services/serv-1-4.jpg" alt="">
                            </div>
                            <div class="box-wrapper">
                                <div class="box-header clearfix"><span class="fh-icon"><i class="flaticon-transport-3"></i></span>
                                    <h4 class="box-title">Air  Cargo Services</h4></div>
                                <div class="box-content">
                                    <p>JUSTLOG Logistics offers specialized and reliable cost-effective Domestic & International fast air logistics services in India for time-sensitive

</p>
                                </div>
                                <a href="<?php echo site_url('/services_page');?>" title="Read More" class="read-more">Read More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="fh-service-box-2 icon-type-theme_icon box-style-2 ">
                            <div class="box-thumb">
                                <span class="fh-icon"><i class="flaticon-transport-4"></i></span>
                                <img class="" src="assets/web_assets/images/services/serv-2-4.jpg" alt="">
                            </div>
                            <div class="box-wrapper">
                                <div class="box-header clearfix"><span class="fh-icon"><i class="flaticon-transport-4"></i></span>
                                    <h4 class="box-title">Ocean  Freight Services</h4></div>
                                <div class="box-content">
                                    <p>International freight forwarding with JUSTLOG Logistics personalised to suit you. We look after your logistics needs so you can focus on your core business responsibilities. </p>
                                </div>
                                <a href="<?php echo site_url('/services_page');?>" title="Read More" class="read-more">Read More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="fh-service-box-2 icon-type-theme_icon box-style-2 ">
                            <div class="box-thumb">
                                <span class="fh-icon"><i class="flaticon-transport-5"></i></span>
                                <img class="" src="assets/web_assets/images/services/serv-3-4.jpg" alt="">
                            </div>
                            <div class="box-wrapper">
                                <div class="box-header clearfix"><span class="fh-icon"><i class="flaticon-transport-5"></i></span>
                                    <h4 class="box-title">Surface Logistics</h4></div>
                                <div class="box-content">
                                   <p>Surface Logistics is basically transferring goods from one place to another via Road. The Surface freight services are the most common option that deliver your goods on time with JUSTLOG Logistics. </p>
                                </div>
                                <a href="<?php echo site_url('/services_page');?>" title="Read More" class="read-more">Read More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="fh-service-box-2 icon-type-theme_icon box-style-2 ">
                            <div class="box-thumb">
                                <span class="fh-icon"><i class="flaticon-open-cardboard-box"></i></span>
                                <img class="" src="assets/web_assets/images/services/serv-4-4.jpg" alt="">
                            </div>
                            <div class="box-wrapper">
                                <div class="box-header clearfix"><span class="fh-icon"><i class="flaticon-open-cardboard-box"></i></span>
                                    <h4 class="box-title">Warehousing</h4></div>
                                <div class="box-content">
                                    <p>Warehousing refers to a storage system used for protecting the quantity and quality of the stored products. JUSTLOG Logistics have own warehouse facility at different differnt locations.</p>
                                </div>
                                <a href="<?php echo site_url('/services_page');?>" title="Read More" class="read-more">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Services sec   end-->

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
        <!--Why choose us end-->

        <!--home counters -->
       <!--  <section class="homecounts-2">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="fh-counter icon-type-theme_icon icon-left ">
                            <span class="fh-icon"><i class="flaticon-business-1"></i></span>
                            <div class="counter">
                                <div class="value">9800</div>
                                <h4>Delivered Package</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="fh-counter icon-type-theme_icon icon-left ">
                            <span class="fh-icon"><i class="flaticon-global"></i></span>
                            <div class="counter">
                                <div class="value">230</div>
                                <h4>Office Wordwide</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="fh-counter icon-type-theme_icon icon-left ">
                            <span class="fh-icon"><i class="flaticon-people-3"></i></span>
                            <div class="counter">
                                <div class="value">1200</div>
                                <h4>Company Staffs</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="fh-counter icon-type-theme_icon icon-left ">
                            <span class="fh-icon"><i class="flaticon-transport-10"></i></span>
                            <div class="counter">
                                <div class="value">5200</div>
                                <h4>Tons of Goods</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->
        <!--home counters end -->

        <!--Our Team section-->
       <!--  <section class="hometeam-1 secpadd">
            <div class="container">
                <div class="fh-section-title clearfix  text-left version-dark">
                    <h2>Meet Our Team</h2></div>
                <div class="fh-team">
                    <div class="team-list slideteam">
                        <div class="team-member ">
                            <div class="team-header"><img src="assets/web_assets/images/team/team-1.jpg" class="attachment-full" alt="" />
                                <ul class="team-social">
                                    <li><a href="#" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="#" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="#" target="_blank"><i class="fa fa-skype"></i></a></li>
                                </ul>
                            </div>
                            <div class="team-info">
                                <h4 class="name">Vena Georgeyo</h4><span class="job">Chairman</span></div>
                        </div>
                        <div class="team-member ">
                            <div class="team-header"><img src="assets/web_assets/images/team/team-2.jpg" class="attachment-full" alt="" />
                                <ul class="team-social">
                                    <li><a href="#" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="#" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="#" target="_blank"><i class="fa fa-skype"></i></a></li>
                                </ul>
                            </div>
                            <div class="team-info">
                                <h4 class="name">Johny Zabrila</h4><span class="job">Co-Ordinator</span></div>
                        </div>
                        <div class="team-member ">
                            <div class="team-header"><img src="assets/web_assets/images/team/team-3.jpg" class="attachment-full" alt="" />
                                <ul class="team-social">
                                    <li><a href="#" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="#" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="#" target="_blank"><i class="fa fa-skype"></i></a></li>
                                </ul>
                            </div>
                            <div class="team-info">
                                <h4 class="name">Philil Burphly</h4><span class="job">Manager</span></div>
                        </div>
                        <div class="team-member ">
                            <div class="team-header"><img src="assets/web_assets/images/team/team-4.jpg" class="attachment-full" alt="" />
                                <ul class="team-social">
                                    <li><a href="#" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="#" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="#" target="_blank"><i class="fa fa-skype"></i></a></li>
                                </ul>
                            </div>
                            <div class="team-info">
                                <h4 class="name">Michal Wincent</h4><span class="job">Accountant</span></div>
                        </div>
                        <div class="team-member ">
                            <div class="team-header"><img src="assets/web_assets/images/team/team-1.jpg" class="attachment-full" alt="" />
                                <ul class="team-social">
                                    <li><a href="#" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="#" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="#" target="_blank"><i class="fa fa-skype"></i></a></li>
                                </ul>
                            </div>
                            <div class="team-info">
                                <h4 class="name">Vena Georgeyo</h4><span class="job">Chairman</span></div>
                        </div>
                    </div>
                </div>
                <div class="teampara">
                    We’re always looking for talented workers, creative directors and anyone has a
                    <br> passion for the logistic service <a class="main-color" style="text-decoration: underline;" href="#">join our team.</a>
                </div>
            </div>

        </section> -->
        <!--Our Team section end-->

        <!--testimonials -->
        <!-- <section class="testmonial-4 bluebg">
            <div class="container">
                <div class="fh-testimonials-carousel fh-testimonials column-2">
                    <div class="testi-list slidetest4">
                        <div class="testi-item">
                            <span class="testi-icon"><i class="flaticon-quotations "></i></span>
                            <div class="testi-content">
                                <div class="testi-star">
                                    <i class="fa fa-star fa-md"></i><i class="fa fa-star fa-md"></i>
                                    <i class="fa fa-star fa-md"></i><i class="fa fa-star fa-md"></i>
                                    <i class="fa fa-star fa-md"></i>
                                </div>
                                <div class="testi-des">These guys are just the coolest company ever! They were aware of our transported for road and tail and well as complex transport services.</div>
                                <div class="info clearfix">
                                    <span class="testi-name">Magdalena Donowan</span>
                                    <span class="testi-job">CFD Engineer</span>
                                </div>
                            </div>
                        </div>
                        <div class="testi-item">
                            <span class="testi-icon"><i class="flaticon-quotations "></i></span>
                            <div class="testi-content">
                                <div class="testi-star">
                                    <i class="fa fa-star fa-md"></i><i class="fa fa-star fa-md"></i>
                                    <i class="fa fa-star fa-md"></i><i class="fa fa-star fa-md"></i>
                                    <i class="fa fa-star fa-md"></i>
                                </div>
                                <div class="testi-des">The shipping process with this crew was a pleasurable experience! They did all in time and with no safety incidents. Thank you so much guys!</div>
                                <div class="info clearfix">
                                    <span class="testi-name">Emilia Crena</span>
                                    <span class="testi-job">CEO, VIP Construction, Australia</span>
                                </div>
                            </div>
                        </div>
                        <div class="testi-item">
                            <span class="testi-icon"><i class="flaticon-quotations "></i></span>
                            <div class="testi-content">
                                <div class="testi-star">
                                    <i class="fa fa-star fa-md"></i><i class="fa fa-star fa-md"></i>
                                    <i class="fa fa-star fa-md"></i><i class="fa fa-star fa-md"></i>
                                    <i class="fa fa-star fa-md"></i>
                                </div>
                                <div class="testi-des">Their performance on our project was extremely successful. As a result of this collaboration, the project was built with exceptional quality &amp; delivered.</div>
                                <div class="info clearfix">
                                    <span class="testi-name">Orlando E. Dougles</span>
                                    <span class="testi-job">CEO, Green Valley Inc, London</span>
                                </div>
                            </div>
                        </div>
                        <div class="testi-item">
                            <span class="testi-icon"><i class="flaticon-quotations "></i></span>
                            <div class="testi-content">
                                <div class="testi-star">
                                    <i class="fa fa-star fa-md"></i><i class="fa fa-star fa-md"></i>
                                    <i class="fa fa-star fa-md"></i><i class="fa fa-star fa-md"></i>
                                    <i class="fa fa-star fa-md"></i>
                                </div>
                                <div class="testi-des">The shipping process with this crew was a pleasurable experience! They did all in time and with no safety incidents. Thank you so much guys!</div>
                                <div class="info clearfix">
                                    <span class="testi-name">Emilia Crane</span>
                                    <span class="testi-job">CEO, VIP Construction, Australia</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->
        <!--testimonilas-->

        <!-- Featires sec -->
        <section class="features-1 bluebg">
            <div class="row">
                <div class="col-md-4 col-sm-6">
                    <div class="frs1box">
                        <div class="fh-icon-box  style-3 version-dark hide-button icon-left">
                            <span class="fh-icon"><i class="flaticon-internet"></i></span>
                            <h4 class="box-title"><span>Fast worldwide delivery</span></h4>
                            <div class="desc">
                                <p>There are many variations of passages of available, but the majority have suffered alteration in some form, by or randomised slightly believable.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 borderboth">
                    <div class="frs1box">
                        <div class="fh-icon-box  style-3 version-dark hide-button icon-left">
                            <span class="fh-icon"><i class="flaticon-technology"></i></span>
                            <h4 class="box-title"><span>24/7 customer support</span></h4>
                            <div class="desc">
                                <p>There are many variations of passages of available, but the majority have suffered alteration in some form, by or randomised slightly believable.</p>
                            </div>
                        </div>
                    </div>
                </div>				
                <div class="col-md-4 col-sm-6">
                    <div class="frs1box">
                        <div class="fh-icon-box  style-3 version-dark hide-button icon-left">
                            <span class="fh-icon"><i class="flaticon-shield"></i></span>
                            <h4 class="box-title"><span>Safe and Secure</span></h4>
                            <div class="desc">
                                <p>Must explain to you how all this mistaken idea of out denouncing pleasure and praising pain was born and sed a complete account of the system.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
        <!-- Featires sec end -->

        <!--google map-->
        <!-- <div class="google-map-area">
           <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7539.796097596177!2d72.852993!3d19.112128!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xaa80bc60f4611d6a!2sJet-Air%20Express%20Services!5e0!3m2!1sen!2sin!4v1641784859201!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div> -->




       <!--   <div class="google-map-area">
           <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d60331.338496976496!2d72.99856!3d19.076543!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7c136b519107b%3A0x8452b99754be0fc8!2sVashi%2C%20Navi%20Mumbai%2C%20Maharashtra!5e0!3m2!1sen!2sin!4v1646897104747!5m2!1sen!2sin" width="1700" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div> -->
        <!--google map end-->

         <!-- <div class="container"> -->
        <div class="google-map-area">
           <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15132.53544903305!2d73.866415!3d18.522852!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x9ea7325563ecd454!2sJUSTLOG%20Logistics%20LLP!5e0!3m2!1sen!2sin!4v1647067632831!5m2!1sen!2sin" width="1700" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
        <!-- </div> -->



<?php include 'shared/web_footerold.php'; ?>