<?php include(dirname(__FILE__) . '/../admin_shared/admin_header.php'); ?>
<!-- END Head-->

<!-- START: Body-->

<body id="main-container" class="default">


    <!-- END: Main Menu-->

    <?php include(dirname(__FILE__) . '/../admin_shared/admin_sidebar.php'); ?>
    <!-- END: Main Menu-->

    <!-- START: Main Content-->
    <main>
        <div class="container-fluid site-width">
            <!-- START: Listing-->
            <div class="row">
                <div class="col-12">
                    <div class="col-12 col-sm-12 mt-3">
                        <div class="card">

                            <div class="card-header">
                                <h4 class="card-title">Add Time</h4><span style="float: right;"></span>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php if (!empty($this->session->flashdata('msg'))) { ?>
                                                <div class="alert alert-success" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert">X</button>
                                                    <?php echo $this->session->flashdata('msg'); ?>
                                                <?php } ?>
                                                </div>
                                        </div>
                                        <div class="col-12">
                                            <form role="form" action="admin/add_pickup_time" method="post">
                                                <div class="box-body">
                                                    <div class=" row">
                                                        <div class="col-sm-8">
                                                            <label>Name:</label>
                                                            <input type="text" class="form-control" name="time_sloat" required placeholder="Enter Time Range" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-md-12">
                                                            <div class="box-footer">
                                                                <button type="submit" name="submit" class="btn btn-primary m-2">Add</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                  
                                        <div class="col-md-8">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <td>ID</td>
                                                        <td>Time</td>
                                                        <td>Action</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($time_data)) { ?>
                                                    <?php $i = 1;
                                                        foreach ($time_data as $v) : ?>
                                                        <tr>
                                                            <td><?php echo $i++; ?></td>
                                                            <td><?php echo $v->time; ?></td>
                                                            <td>
                                                                <!-- <a href="#" class="btn btn-success">Edit</a> -->
                                                                <a href="<?php echo base_url('Pickup_Request_Controller/delete_time/'.$v->id);?>" class="btn btn-danger">Delete</a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td colspan="2">No Data Found</td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>


                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- END: Listing-->
            </div>
        </div>
    </main>
    <!-- END: Content-->
    <!-- START: Footer-->

    <?php include(dirname(__FILE__) . '/../admin_shared/admin_footer.php'); ?>
    <!-- START: Footer-->
</body>
<!-- END: Body-->