<?php include(dirname(__FILE__).'/../franchise_shared/franchise_header.php'); ?>
<?php include(dirname(__FILE__).'/../franchise_shared/franchise_sidebar.php'); ?>
 <!-- START: Card Data-->
<main>
    <div class="container-fluid site-width">
<!-- START: Card Data-->
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-body">
                <div class="row p-2">
                    <div class="col-md-6">
                        <h6 class="">Tracking</h6>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">

                        <!-- <a href="#" target="_blank" class="btn btn-outline-dark btn-sm">label</a> -->
                        <a href="#" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i> Export</a>
                         <a href="" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle">close</i></a>

                    </div>
                </div>
                <hr>
               <?php  include(dirname(__FILE__).'/../tracking_master/b2b_tracking_tab.php'); ?>


                <div class="row">
                        <div class="col-md-3">
                            <label>From date </label>
                            <div class="form-group">
                                <input type="date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>AWB No </label>
                            <div class="form-group">
                                <input type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                   
                 



                <div class="table-responsive">
                    <table id="id1" class="display table  table-striped ">
                        <thead>
                            <tr>
                               
                                <th>Sender Name</th>
                                <th>Receiver Name</th>
                                <th>Reciver Pincode</th>
                                <th>Reciver City</th>
                                <th>Booking Date</th>
                                <th>Status</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            <?php  //print_r($pending_list);?>
                            <?php if(!empty($pending_list)){?>
                            <?php foreach( $pending_list as $value):?>
                            <tr>
                             
                                <td><?php echo $value['sender_name'] ;?></td>
                                <td><?php echo $value['reciever_name'] ;?></td>
                                <td><?php echo $value['reciever_pincode'] ;?></td>
                                <?php
                                $city_id = $value['reciever_city'];
                                 $city = $this->db->query("select city from city where id ='$city_id'")->row();?>
                                <td><?php echo $city->city ;?></td>
                                <td><?php echo $value['booking_date'] ;?></td>
                                <td> <?php if($value['status'] == 'pending'){?> <button type="button" class="btn btn-outline-info btn-sm">Pending</button> <?php }?></td>
                            </tr>
                            <?php endforeach ;?>
                          <?php }else { ?> 
                            <tr><td colspan="7" style="color:red; text-align:center;">No Data Found</td></tr> 
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- END: Card DATA-->
</div>
</main>

<?php include(dirname(__FILE__).'/../franchise_shared/franchise_footer.php'); ?>