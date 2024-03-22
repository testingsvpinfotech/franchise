<?php include 'shared/web_header.php'; ?>
     
<body class="home header-v4 hide-topbar-mobile">
    <div id="page">

        <!-- Preloader-->
       

        <?php include 'shared/web_menu.php'; ?>
        <!-- masthead end -->




        <div class="page-title">
        <div class="container">
            <div class="padding-tb-120px">
                <h1>Contact Us</h1>
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active">Contact Us</li>
                </ol>
            </div>
        </div>
    </div>


    <div class="padding-tb-100px">

        <div class="container">
            <div class="row">

                <div class="col-lg-6 sm-mb-45px">
                    <p>If you have any questions about the services we provide simply use the form below. We try and respond to all queries and comments within 24 hours.</p>
                    <h5>Phone :</h5>
                    <a href="tel:+91 9036007410"><span class="d-block"><i class="fa fa-phone text-main-color margin-right-10px" aria-hidden="true"></i>+91-9987767190</span></a>
                    <h5 class="margin-top-20px">Corporate Office :</h5>
                    <span class="d-block sm-mb-30px"><i class="fa fa-map text-main-color margin-right-10px" aria-hidden="true"></i> Jagdamba House, 5th floor, next to Anupam Cinema, Peru Baug, Goregaon (East), Mumbai 400 063.</span>
                    <h5 class="margin-top-20px">Email :</h5>
                   <span> <a href="mailto:customercare@boxnfreight.com<i class="fa fa-envelope-open text-main-color margin-right-10px" aria-hidden="true"></i><span class="__cf_email__"><i class="ri-mail-fill"></i> customercare@boxnfreight.com</span></a> <br>
                 </div>

                <div class="col-lg-6">
                    <div class="contact-modal">
                        <div class="background-main-color">
                            <div class="padding-30px">
                                <h3 class="padding-bottom-15px" style="color:#fff;">Get A Free Quote</h3>
                                <form method="post" action="http://st.ourhtmldemo.com/template/cargohub/ajax/mail.php" id="contact-form" novalidate="novalidate">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Full Name</label>
                                            <input type="text" name="name" class="form-control" id="inputName44" placeholder="Name">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Phone</label>
                                            <input type="text" name="phone" class="form-control" id="inputEmail44" placeholder="Phone">
                                        </div>
                                    </div>
                                    <div class="row">
                                    <div class="form-group col-md-12">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control" id="inputName44" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Message</label>
                                        <textarea class="form-control" name="message" id="exampleFormControlTextarea11" rows="3"></textarea>
                                    </div>
                                    <button class="btn-sm btn-lg btn-block background-dark text-white text-center  text-uppercase rounded-0 padding-15px" name="submit" type="submit">SEND MESSAGE</button>
                                </form>
                            </div>
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
        <!--google map end-->

<?php include 'shared/web_footer.php'; ?>
