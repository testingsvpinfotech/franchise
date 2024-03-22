
<?php include(dirname(__FILE__).'/../franchise_shared/franchise_header.php'); ?>
<?php include(dirname(__FILE__).'/../franchise_shared/franchise_sidebar.php'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
 <!-- START: Card Data-->
 <br><br>
 <main>
 <style>
    html {
  scroll-behavior: smooth !important;
}
</style>
    <div class="row">
        <div class="col-12 mt-3">
            <div class="card">
            <div class="card-body">
                <div class="row p-2">
                    <div class="col-md-6">
                        <h6 class="">Available Stock </h6>
                    </div>
                </div>
                <hr>
                <?php if ($this->session->flashdata('notify') != '') { ?>
                    <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored"><?php echo $this->session->flashdata('notify'); ?></div>
                <?php unset($_SESSION['class']);
                    unset($_SESSION['notify']);
                } ?>
           
                        
                    <div class="table-responsive">
                    <table id="example" class="display table  table-responsive table-striped table-bordered">
                            <thead>
                            <tr>                 
                                <th>Available Stock : <?php if(!empty($available_stock_count)){ echo $available_stock_count;}else{echo '0';} ?></th>
                                <th>Utilize Stock : <?php if(!empty($Utilize_count)){ echo $Utilize_count;}else{echo '0';}  ?></th>
                                <th>Utilized are marked in red </span></th>
                            </tr>
                            </thead>
                    </table> <br>
                    <form action="<?= base_url('franchise/awb-available-stock');?>" method="get" enctype="multipart/form-data">
                            <div class="row">
                            <div class="col-sm-4 col-sm-offset-3">
                            <input type="text" class="form-control" name="search" placeholder="Search.. " value="<?php if(!empty($_GET['search'])){ echo $_GET['search'];}else{ echo '';} ?>">
                            </div>
                            <div class="col-sm-4">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search" style="font-size:14px;"></i></button>
                            <a href="<?= base_url('franchise/awb-available-stock');?>" class="btn btn-primary"><i class="fa fa-refresh" style="font-size:14px;"></i></a>
                            </div>
                        </div>
                        </form>
                        <br>
                        <table id="myTable" class="display table table-bordered text-center">
                            <thead>
                            <tr>                 
                                <th>SR.No</th>
                                <th>AWB.No</th>
                            </tr>
                            </thead>
                            <tbody>
                                
                                <?php
                                if(!empty($search_data)){ 
                                    $booking = $this->db->query("select * from tbl_domestic_booking where pod_no = '$search_data'")->row();
                                   
                                    ?>
                                    <tr>
                                        <td>1</td>
                                        <?php if(!empty($booking)){ ?>
                                            <td style="color:red; cursor: pointer;"><b class="booking"><?= $search_data; ?></b></td>
                                        <?php  }else{ ?>
                                            <td><?= $search_data; ?></td>
                                        <?php } ?>
                                    </tr>
                              <?php  }else{
                                
                                if(!empty($available_stock)){ 
                                  $serial_no; foreach( $available_stock as  $value ): 
                                 ?>
                            <tr>
                                <td><?= $serial_no;?></td>
                                <?php if($value['u'] == '1'){ ?>   
                                    <td><b style="color:red; cursor: pointer;" class="booking"><?= $value[0];?></b></td>    
                                    <?php }else{?>                                       
                                <td><?= $value[0];?></td>     
                                <?php } ?>                                              
                                </tr>
                                <?php $serial_no++; endforeach; ?>
                                 <?php }else { ?> <tr><td colspan="2">Data Not Found</td></tr> <?php } }?>

                            </tbody>
                        </table>
                    </div>
                    <div class="modal fade bd-example-modal-lg booking_show" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Booking Information</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                  <div id="show_booking"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="col-md-6">
							<?php 
                            
                            if(!empty($available_stock)){ echo $this->pagination->create_links(); }?>
						
					</div>
                </div>
            </div>

        </div>
    </div>
    <!-- END: Card DATA-->
</div>
</main>

<?php include(dirname(__FILE__).'/../franchise_shared/franchise_footer.php'); ?>

<script>
    $('document').ready(function() {
        $(".booking").click(function () 
	{
		var pod_no =$(this).text();
      $.ajax({
        type: 'POST',
        url: '<?php echo base_url();?>Franchise_manager/get_booking_info',
        data: {pod_no:pod_no},
        dataType: "html",
        success: function (d) {     
            $('.booking_show').modal('show');    
            $('#show_booking').html(d);
        }
      });
    
}); 

    });
</script>
