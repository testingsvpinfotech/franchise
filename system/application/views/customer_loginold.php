<?php include 'shared/web_header.php'; ?>
     
<body class="home header-v4 hide-topbar-mobile">
    <div id="page">

        <!-- Preloader-->
       

        <?php include 'shared/web_menu.php'; ?>
        <!-- masthead end -->

      <style type="text/css">
          .btn1{
            float:right;
          }
      </style> 
      
        <!--Page Header-->
        <div class="page-header title-area">
            <div class="header-title">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h1 class="page-title">Customer Login</h1> </div>
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
<div class="col-md-6">
<div class="form-heading text-center">
<h3 class="form-title" style="color:#d89444;">Login to your account!</h3>
</div>
</div>
</div>



<!-- <section class="sign-up-area ptb-100">
<div class="container">
<div class="row">
<div class="col-6 col-md-6">
<div class="contact-form-action">
<div class="form-heading text-center">
<h3 class="form-title" style="color:#d89444;">Login to your account!</h3>
</div> -->
<div class="row">

<form action="Login/index" method="post">
<div class="mainform"> 
 <div class="col-6 col-lg-6 col-md-6">   
<div class="form-group">
<label>Email</label>
<input class="form-control" type="text" name="email" placeholder="Enter Username or Email">
</div>
<br>

<div class="form-group">
<label>Password</label>
<input class="form-control" type="password" name="password" placeholder="Enter Password">
</div>

<div class=" form-condition">

</div>
<!-- <center>
<div class="col-12"> -->
<!-- <br> -->
<!-- </div> -->
<!-- </div> -->
<!-- <div class="row">
<div class="col-6 col-lg-6 col-md-6"> -->
<input type="submit" name="submit" class=" btn btn-lg default-btn btn1 btn-two btn-primary " value="Sign In">
</div>
</div>
</form>
<!-- <div class="col-md-6 col-lg-6">
    <img src="<?php echo base_url();?>assets/login-img.jpg" style="width:200px;height:auto;">
</div> -->
</div>
</div>

</form> 
</div>



</section>
</div>

<!-- </div> -->
<!--  </div>
</div> -->
<!-- </section> -->
<!--contact end-->

<!--google map end-->

<?php include 'shared/web_footer.php'; ?>