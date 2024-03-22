<?php $this->load->view('sidebar');?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Sales Report</h1>
    <ol class="breadcrumb">
     <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
     <li><a href="#">Users</a></li>
     <li class="active">Sales Report</li>
   </ol>
 </section>
 <section class="content">

    <div class="box box-primary">
        
    <div class="box-body">
    <div class="modal-body">
 
        <form action="<?php echo base_url(); ?>booking/salesreport" method="post">
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="">Select Sales Person</label>
                        <select name="sales_person" id="" class="form-control">
                            <option value="">Select</option>
                            <?php foreach ($salesman_list as $key => $value) {?>
                                <option value="<?php echo $value->user_id;?>"><?php echo $value->full_name;?></option>
                           <?php }?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="">From Date</label>
                        <input type="text" name="from_date"  value="<?php echo date('d-m-Y'); ?>" class="form-control">
                    </div>
                </div>
                    <div class="col-sm-3">
                    <div class="form-group">
                        <label for="">To Date</label>
                        <input type="date" name="to_date" value="<?php echo date('d-m-Y'); ?>" class="form-control">
                    </div>
                </div>
                        <div class="col-sm-3 mt-4">
                            <input type="submit" name="Submit" value="Submit" class="btn btn-primary" style="margin-top:23px">
                            <a href="<?php echo base_url('booking/downloadrepot');?>" class="btn btn-primary" style="margin-top:23px">Download Excel</a>
                        </div>
            </div>
        </form><br>
            <h3>Sales Person Name : <?php echo @$fullname->full_name;?></h3>
            <h5>Total Sales:  <?php echo @$sales_count;?></h5>
            <h5>Commision : <?php echo @$sales_commission;?></h5>
        <table class="table">
            <tbody>
                <tr>
                    <th>Sr No.</th>
                    <th>Customer Name</th>
                    <th>Total AWB</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </tbody>
            <tbody>
                <tr>
                <?php if(!empty($salesdata)){$i = 1;
                    foreach ($salesdata as $key => $value) {
                    ?>
                    <td><?php echo  $i;?></td>
                    <td><?php echo  $value['customer_name'];?></td>
                    <td><?php echo  $value['total_pod'];?></td>
                    <td><?php echo  round($value['total_sub_total'],2);?></td>
                    <td><a href="<?php echo base_url('booking/customer_details/').$value['customer_id'];?>" class="btn btn-info"><i class="glyphicon glyphicon-eye-open"></i></a>
                    </td>
                    </tr>
               <?php $i++;} }?>
               
            </tbody>
        </table>   
</div>
<!--content-->
</section>
<!-- /.content-wrapper --> 
</div>
<?php $this->load->view('footer');?>
