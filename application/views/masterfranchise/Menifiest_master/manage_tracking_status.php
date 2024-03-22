<?php include(dirname(__FILE__).'/../admin_shared/admin_header.php'); ?>
    <!-- END Head-->

    <!-- START: Body-->
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
                          <form role="form" id="generatePOD" action="<?php echo base_url('admin/manage-tracking-status'); ?>" method="post" >
								            <div class="box-body">
								 
									             <div class="form-group row">
									
            										<div class="col-md-6">
            											<div class="form-group">
            											 <!-- <label>AWB No:</label> -->
            												<span class="control-fileupload">
                                  
            												  <label for="file">AWB No :</label>
            												  <input type="text" id="pod_no" name="pod_no"  class="form-control" value="<?php echo @$_POST['pod_no'];?>" required placeholder="Enter AWB No.">
            												</span>
            												<p>&nbsp;</p>
            											</div>
            										</div>
          										</div>
												
            									<div class="form-group row">
            										<div class="col-md-2">
            											<div class="box-footer">
                                    <input type="submit" name="search" class="btn btn-primary" value="Search">
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



                <div class="card-content">
                    <div class="card-body">
                      <div class="row">                                           
                        <div class="col-12">

                          <table class="table table-stripped">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>AWB No.</th>
                                <th>Status</th>
                                <th>Branch Name</th>
                                <th>Comment</th>
                                <th>Forworder Name</th>
                                <th>Forwording No</th>
                                <th>Tracking Date</th>
                                <!-- <th>is_delhivery_complete</th> -->
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php 

                              if (!empty($tracking_data)) {
                                foreach ($tracking_data as $key => $value) {

                                  $value['tracking_date'] = date('d-m-Y H:i',strtotime($value['tracking_date']));
                                  # code...
                                  echo "<tr>";
                                  echo "  <td>".($key+1)."</td>";
                                  echo "  <td>".$value['pod_no']."</td>";
                                  echo "  <td>".$value['status']."</td>";
                                  echo "  <td>".$value['branch_name']."</td>";
                                  echo "  <td>".$value['comment']."</td>";
                                  echo "  <td>".$value['forworder_name']."</td>";
                                  echo "  <td>".$value['forwording_no']."</td>";
                                  echo "  <td>".$value['tracking_date']."</td>";
                                  // echo "  <td>".$value['is_delhivery_complete']."</td>";
                                  // echo "  <td></td>";

                                  echo "  <td>";
                                  echo '    <a href="'.base_url('admin/edit-tracking-status/'.$value['id']).'" title="Edit"><i class="ion-edit" style="color:var(--primarycolor)"></i></a> |
                                            <button title="Delete" onclick="delete_tracking('.$value['id'].');"><i class="ion-trash-b" style="color:var(--danger)"></i></a>';
                                  echo "  </td>";

                                  echo "</tr>";
                                }

                              }else{
                                echo "<tr><td colspan='9'>No Result Found!</td></tr>";
                              }
                              
                              ?>
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
