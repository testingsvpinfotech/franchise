<?php include 'shared/web_header.php'; ?>
     
<body class="home header-v4 hide-topbar-mobile">
    <div id="page">

        <!-- Preloader-->
       

        <?php include 'shared/web_menu.php'; ?>
        <!-- masthead end -->

        <div class="page-title">
        <div class="container">
            <div class="padding-tb-120px">
                <h1>Pincode Tracking</h1>
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active">Pincode Tracking</li>
                </ol>
            </div>
        </div>
    </div>
       

        <!--contact pagesec-->
        <section class="contactpagesec secpadd">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                    <div class="center-login">

                       <form method="post" action="<?php echo base_url();?>/find_location" id="frmpinfinder">

                                <div class="row">
                                   
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="input-group"> 
                                                <textarea name="pincodes" id="pincodes" rows="2" class="form-control" required placeholder="Enter the pincode"><?php echo $pincodes;?></textarea>
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
            </div>
        </section>
        <!--contact end-->

        <!--google map end-->

<?php include 'shared/web_footer.php'; ?>