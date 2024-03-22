<?php include(dirname(__FILE__).'/../admin_shared/admin_header.php'); ?>
    <!-- END Head-->

    <!-- START: Body-->
    <?php 

    // echo "<pre>";

    // print_r($all_status);exit();


    ?>
    
    <body id="main-container" class="default">

        
        <!-- END: Main Menu-->
   
    <?php include(dirname(__FILE__).'/../admin_shared/admin_sidebar.php'); ?>
        <!-- END: Main Menu-->
        <main>
        <!-- START: Main Content-->
            <div class="container-fluid site-width">
                <!-- START: Listing-->
                <div class="row">          
                  <div class="col-12">
                      <div class="col-12 col-sm-12 mt-3">
			
                      <div class="card">
					   <?php if(!empty($success)) { ?> 
            <div class="alert alert-success">
              <strong>Success!</strong> <?php echo is_array($success) ? implode($success, '') : $success; ?>
            </div>
            <?php  $success = ''; } ?>
            
            <?php if(!empty($error)) { ?> 
            <div class="alert alert-warning">
              <strong>Warning!</strong> <?php echo is_array($error) ? implode($error, '') : $error; ?>
            </div>
            <?php  $error = ''; } ?>
                          <div class="card-header">                               
                            <h4 class="card-title">Manage Tracking</h4>
                              
							  
                          </div>

						    <div class="card-content">
                    <div class="card-body">
						          <div class="row">                                           
                        <div class="col-12">
                          <form role="form" id="generatePOD" action="<?php echo base_url('admin/update-tracking-status'); ?>" method="post" >
                            <input type="hidden" name="id" value="<?php echo $tracking_data[0]['id'];?>">
								            <div class="box-body">
								 
									            <div class="form-group row">
									
            										<!-- <div class="col-md-4">
            											<div class="form-group">
            											 
            												<span class="control-fileupload">
                                  
            												  <label for="file">AWB No :</label>
            												  <input type="text" id="pod_no" name="pod_no" value="<?php echo $tracking_data[0]['pod_no'];?>" class="form-control" required placeholder="Enter AWB No." >
            												</span>
            												<p>&nbsp;</p>
            											</div>
            										</div> -->

                                <div class="col-md-6">
                                  <div class="form-group">
                                   
                                    <span class="control-fileupload">
                                      <?php 

                                      $tracking_data[0]['tracking_date'] = date('Y-m-d H:i',strtotime($tracking_data[0]['tracking_date']));
                                      $tracking_data[0]['tracking_date']  = str_replace(" ", "T", $tracking_data[0]['tracking_date']);

                                      ?>
                                      <label for="file">Tracking Date : </label>
                                      <input type="datetime-local" class="form-control" name="tracking_date" id="tracking_date" value="<?php echo $tracking_data[0]['tracking_date'];?>" class="form-control" required placeholder="Enter Tracking Date.">
                                    </span>
                                    <p>&nbsp;</p>
                                  </div>
                                </div>

                                <div class="col-md-6">
                                  <div class="form-group">
                                   
                                    <span class="control-fileupload">
                                  
                                      <label for="file">Status :</label>

                                      <select name="status" id="status"  class="form-control">
                                        <?php 
                                        if(!empty($all_status))
                                        {
                                          foreach($all_status as $key => $values)
                                          { 
                                            if ($values['status']==$tracking_data[0]['status']) {
                                              echo "<option value='".$values['status']."' selected>".$values['status']."</option>";
                                            }else{
                                              echo "<option value='".$values['status']."'>".$values['status']."</option>";
                                            }
                                          } 
                                        } 
                                        ?>
                                        
                                        </select>
                                    </span>
                                    <p>&nbsp;</p>
                                  </div>
                                </div>

                               

                                <div class="col-md-6">
                                  <div class="form-group">
                                   
                                    <span class="control-fileupload">
                                  
                                      <label for="file">Comment :</label>

                                      <textarea class="form-control" id="comment" name="comment"><?php echo $tracking_data[0]['comment'];?></textarea>
                                      
                                    </span>
                                    <p>&nbsp;</p>
                                  </div>
                                </div>

                                <div class="col-md-6">
                                  <div class="form-group">
                                   
                                    <span class="control-fileupload">
                                  
                                      <label for="file">Remarks :</label>

                                      <textarea class="form-control" id="remarks" name="remarks"><?php echo $tracking_data[0]['remarks'];?></textarea>
                                      
                                    </span>
                                    <p>&nbsp;</p>
                                  </div>
                                </div>

                               <!--  <div class="col-md-4">
                                  <div class="form-group">
                                   
                                    <span class="control-fileupload">
                                  
                                      <label for="file">Forworder Name :</label>
                                      <input type="text" id="forworder_name" name="forworder_name" value="<?php echo $tracking_data[0]['forworder_name'];?>" class="form-control" required placeholder="Enter Forworder Name.">
                                    </span>
                                    <p>&nbsp;</p>
                                  </div>
                                </div> -->

                                <!-- <div class="col-md-4">
                                  <div class="form-group">
                                   
                                    <span class="control-fileupload">
                                  
                                      <label for="file">Forwording No. :</label>
                                      <input type="text" id="forwording_no" name="forwording_no" value="<?php echo $tracking_data[0]['forwording_no'];?>" class="form-control" required placeholder="Enter Forwording No.">
                                    </span>
                                    <p>&nbsp;</p>
                                  </div>
                                </div> -->

                                

                                

          										</div>
												
            									<div class="form-group row">
            										<div class="col-md-2">
            											<div class="box-footer">
                                    <input type="submit" name="submit" class="btn btn-primary" value="Update">
            												<!-- <button type="submit"  class="btn btn-primary">Search</button> -->
            											</div>
            										</div>
            										<!-- /.box-body -->
            									</div>
									
									           </div>
								
							             </form>
                        </div> 
                        </div> 
                        </div> 
                </div> 



                

                    </div>
                    </div>
                </div>
                <!-- END: Listing-->
            </div>
        </main>
        <!-- END: Content-->
        <!-- START: Footer-->
        
        <?php  include(dirname(__FILE__).'/../admin_shared/admin_footer.php'); ?>
        <!-- START: Footer-->
    </body>
    <!-- END: Body-->
</html>

<script type="text/javascript">
  function delete_tracking(id){
    y = confirm('Are you sure you want to delete this record?');

    if (y) {
      $.ajax({
        url : '<?php echo base_url("admin/delete-tracking-status");?>',
        method : 'POST',
        data : {id:id},
        success : function(data){
          if (data==1) {
            alert('Record Deleted Successfully');
            location.reload();
          }else{
            alert('Opps Something went wrong , please retry!');
          }
        }
      });
    }
  }
</script>
