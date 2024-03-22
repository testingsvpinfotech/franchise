<?php include 'shared/web_header.php'; ?>
     
<body class="home header-v4 hide-topbar-mobile">
    <div id="page">

        <!-- Preloader-->
       

        <?php include 'shared/web_menu.php'; ?>
        <!-- masthead end -->

       
      
        <!--Page Header-->
        <div class="page-header title-area">
            <div class="header-title">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h1 class="page-title">Pincode Tracking</h1> </div>
                    </div>
                </div>
            </div>
            <div class="breadcrumb-area">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col-sm-12 col-xs-12 site-breadcrumb">
                            <nav class="breadcrumb">
                                <a class="home" href="#"><span>Home</span></a>
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                                <span>Contact</span>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Page Header end-->

        <!--contact pagesec-->
        <section class="contactpagesec secpadd">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                       <form method="post" action="<?php echo base_url();?>/find_location" id="frmpinfinder">

                                <div class="row">
                                   
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="input-group"> 
                                                <textarea name="pincodes" id="pincodes" rows="2" class="form-control" required ><?php echo $pincodes;?></textarea>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="col-md-12">
                                        <button name="submit" type="submit" value="Submit" class="btn btn-primary"> 
											<span>Submit</span> 
										</button>
                                        <button name="Resat" type="reset" value="Reset" class="btn btn-danger" > 
											<span>Reset</span> 
										</button>
                                    </div>
                                </div>
                            </form>
							<div class="row"><div class="col-md-12"><?php echo $results;?></div></div>
                        </div>
                    </div>
                   
                </div>
            </div>
        </section>
        <!--contact end-->

        <!--google map end-->

<?php include 'shared/web_footer.php'; ?>