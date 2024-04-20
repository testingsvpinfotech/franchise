<?php include (dirname(__FILE__) . '/../franchise_shared/franchise_header.php'); ?>
<?php include (dirname(__FILE__) . '/../franchise_shared/franchise_sidebar.php'); ?>
 
 <main>

 <style>
    .form-control:disabled, .form-control[readonly] {
    background-color: #c9cccf!important;
    opacity: 1;
}
 </style>
<div class="container-fluid site-width">
   

    <!-- START: Card Data-->
    <div class="row">
        <div class="col-12 col-sm-12">
            <div class="row">
                <div class="col-12 col-md-12 mt-3">                   

                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="id1">
                         
                               <form  action="<?php echo base_url(); ?>franchise/add-shipment" method="POST" id="formSubmit">
                <div class="row">
                    <div class="col-md-4 col-sm-12 mt-3">
                        <!-- Shipment Info -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Shipment Info</h4>
                               <input type="hidden" id="franchise_type" value="<?= $_SESSION['franchise_type'];?>">
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <?php if ($this->session->flashdata('notify') != '') { ?>
                                            <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored"><?php echo $this->session->flashdata('notify'); ?></div>
                                        <?php unset($_SESSION['class']);
                                        unset($_SESSION['notify']);
                                    } ?>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Date<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">

                                            <?php
                                            $datec = date('Y-m-d H:i');

                                         
                                            $datec = str_replace(" ", "T", $datec);
                                            if ($this->session->userdata('booking_date') != '') { ?>

                                                    <input type="datetime-local" name="booking_date" value="<?php echo $this->session->userdata('booking_date'); ?>" id="booking_date" class="form-control" readonly>
                                                <?php
                                            } else { ?>
                                                    <input type="datetime-local" name="booking_date" value="<?php echo $datec; ?>" id="booking_date" class="form-control" readonly>
                                            <?php } ?>
                                        </div>
                                    </div>
                                 

                                    <?php $customer_id = $_SESSION['customer_id'];
                                    $readonly = $this->db->query("SELECT * FROM tbl_branch_assign_cnode WHERE customer_id = '$customer_id'")->row(); ?>
                                    <div class="form-group row">
                                       <label class="col-sm-4 col-form-label">Airway No<span class="compulsory_fields">*</span></label>
                                       <div class="col-sm-8">
                                            <input type="text" name="awn"  class="form-control pod" value="" <?php if (empty ($readonly)) {
                                                echo 'readonly';
                                            } else { ?> id="pod" <?php } ?>  required autocomplete="off" style="text-transform: uppercase;">
                                            <!-- <input type="hidden" name="awn" id="awn" class="form-control" value="<?php echo $bid; ?>"> -->
                                            <input type="hidden" name="courier_company" id="courier_company" class="form-control" value="51">
                                            <span class="text-danger" id="lblError"></span>
                                       </div>
                                    </div>                          
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Mode<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control mode_dispatch" name="mode_dispatch" id="mode_dispatch">
                                                <option value="">-Select Mode-</option>
                                                <?php
                                                if (!empty ($transfer_mode)) {
                                                    foreach ($transfer_mode as $row) {
                                                        ?>
                                                                <option value='<?php echo $row->transfer_mode_id; ?>'><?php echo $row->mode_name; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Desc.</label>
                                        <div class="col-sm-8">
                                            <textarea name="special_instruction" class="form-control my-colorpicker1"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Risk Type<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="risk_type" id="risk_type" disabled>
                                                 <option value="Customer">Customer</option>
                                                <option value="Carrier">Carrier</option>
                                            </select>
                                            <input type="hidden" name="risk_type" value="Customer">
                                        </div>
                                        <label class="col-sm-4 col-form-label">Bill Type<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="dispatch_details" id="dispatch_details">                                       
                                              
                                            <?php
                                               if($_SESSION['franchise_type']!=3){   ?>
                                                <option value="<?= bill_type[$_SESSION['franchise_type']];?>"><?= bill_type[$_SESSION['franchise_type']];?></option>
                                                <option value="TOPAY">TOPAY</option>
                                          <?php }else{  foreach(bill_type as $key =>$value){?>
                                                 <option value="<?= $value;?>" ><?= $value;?></option>
                                                <?php }}?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Product<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="doc_type" id="doc_typee">
                                                <option value="">-Select-</option>
                                                <option value="1">Non-Doc</option>
                                                <option value="0">Doc</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="div_inv_row" style="display: none;">
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">INV No.</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="invoice_no" id="invoice_no" class="form-control my-colorpicker1">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Inv. Value<span class="compulsory_fields">*</span></label>
                                            <div class="col-sm-8">
                                                <input type="text" name="invoice_value" required id="invoice_value" class="form-control my-colorpicker1">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Eway No</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="eway_no" minlength="12" maxlength="12" size="12" id="eway_no" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Eway Expiry date</label>
                                            <div class="col-sm-8">
                                                <input type="datetime-local" name="eway_expiry_date" id="eway_no" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                        <!-- Shipment Info -->
                    </div>
                    <div class="col-md-4 col-sm-12 mt-3">
                        <!-- Consigner Detail -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Consigner Detail</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Franchise Name</label>
                                        <div class="col-sm-8" id="credit_div">
                                            <select class="form-control" name="customer_account_id" <?php if($_SESSION['franchise_type']==2){?>  id="customer_account_id" <?php }else{ ?> id="franchise_id" <?php } ?>>
                                                <?php if($_SESSION['franchise_type']==2){ ?>
                                                    <option value="">Select Customer</option>  <?php }
                                                if (count($franchise)) {
                                                    foreach ($franchise as $rows) {
                                                        ?>
                                                                <option value="<?php echo $rows['customer_id']; ?>">
                                                                    <?php echo $rows['customer_name']; ?>--<?php echo $rows['cid']; ?>
                                                                </option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                         <?php if($_SESSION['franchise_type']==1 OR $_SESSION['franchise_type']==3){ ?>
                                            <label class="col-sm-5 col-form-label" id="credit_div_label">Company Customer</label>
                                        <div class="col-sm-7"> 
                                            <input type="checkbox" name="company_customer" id="company_customer"  value ='1'>
                                        </div>
                                        <label class="col-sm-4 col-form-label">Customer</label>
                                        <div class="col-sm-8" id="credit_div">
                                            <select class="form-control bnf_customer" name="customer_id" disabled id="customer_account_id">
                                                <option value="">Select Customer</option>
                                                <?php
                                                if (count($customers)) {
                                                    foreach ($customers as $rows) {
                                                        ?>
                                                                <option value="<?php echo $rows['customer_id']; ?>">
                                                                    <?php echo $rows['customer_name']; ?>--<?php echo $rows['cid']; ?>
                                                                </option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        
                                        <?php } ?>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" id="credit_div_label">Name<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="sender_name" id="sender_name" class="form-control my-colorpicker1">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Address</label>
                                        <div class="col-sm-8">
                                            <textarea name="sender_address" id="sender_address" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Pincode<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="sender_pincode" maxlength="6" minlength="6" id="sender_pincode" value="<?= $franchise[0]['pincode']; ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">State<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="sender_state" name="sender_state" readonly required>
                                                <option value="">Select State</option>
                                                <?php
                                                if (count($states)) {
                                                    foreach ($states as $st) {
                                                        ?>
                                                                <option value="<?php echo $st['id']; ?>">
                                                                    <?php echo $st['state']; ?>
                                                                </option>
                                                    <?php }
                                                }
                                                ?>
                                            </select>
                                            <input type="hidden" name="region_id" id="region_id" readonly required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">City<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="sender_city" readonly name="sender_city"  required>
                                                <option value="">Select City</option>
                                                <?php
                                                if (count($cities)) {
                                                    foreach ($cities as $rows) {
                                                        ?>
                                                                <option value="<?php echo $rows['id']; ?>">
                                                                    <?php echo $rows['city']; ?>
                                                                </option>
                                                    <?php }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">ContactNo.</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="sender_contactno" maxlength="10" minlength="10"  id="sender_contactno" class="form-control ">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">TypeOfDoc<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-4">
                                            <select name="type_of_doc" class="form-control">
                                                <option value="GSTIN">GSTIN</option>
                                                <option value="GSTIN(Govt.)">GSTIN(Govt.)</option>
                                                <option value="GSTIN(Diplomats)">GSTIN(Diplomats)</option>
                                                <option value="PAN">PAN</option>
                                                <option value="TAN">TAN</option>
                                                <option value="Passport">Passport</option>
                                                <option value="Aadhaar">Aadhaar</option>
                                                <option value="Voter Id">Voter Id</option>
                                                <option value="IEC">IEC</option>
                                            </select>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="sender_gstno"  id="sender_gstno" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Consigner Detail -->
                    </div>
                    <div class="col-md-4 col-sm-12 mt-3">
                        <!-- Consignee Detail -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">Consignee Detail</h6>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Name<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="reciever_name" id="reciever" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Company<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="contactperson_name" id="contactperson_name" required />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Address</label>
                                        <div class="col-sm-8">
                                            <textarea name="reciever_address" id="reciever_address" class="form-control" autocomplete="off"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Pincode<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" maxlength="6" minlength="6" name="reciever_pincode" id="reciever_pincode" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">state<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="reciever_state" readonly  name="reciever_state" >
                                                
                                                     <option value="">Select State</option>           
                                                   
                                            </select>
                                            <span id="oda"></span>
                                        </div>
                                        
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">City<span class="compulsory_fields">*</span>&nbsp;&nbsp;&nbsp;&nbsp;<span id="oda"></span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="reciever_city" readonly name="reciever_city" >
                                             <option value="">Select City</option>           
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Zone</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="receiver_zone" id="receiver_zone" readonly class="form-control">
                                            <input type="hidden" name="receiver_zone_id" id="receiver_zone_id" class="form-control">
                                            <input type="hidden" id="gst_charges" class="form-control">
                                            <input type="hidden" id="cft" class="form-control">
                                            <input type="hidden" id="air_cft" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">ContactNo.</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" required maxlength="10" minlength="10" id="reciever_contact" name="reciever_contact" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">GST NO.</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="receiver_gstno" id="receiver_gstno" maxlength="15" minlength="1" class="form-control">
                                            <div id="validation_message"></div>
                                        </div>
                                    </div>
                                    <?php if($_SESSION['franchise_type']==1 OR $_SESSION['franchise_type']==3){ ?>
                                    <div class=" row">
                                        <label class="col-sm-5 col-form-label" id="credit_div_label">Door Delivery</label>
                                        <div class="col-sm-7"> <br>
                                            <input type="checkbox" name="door_delivery_acces" id="door_delivery" value="1" disabled>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <!-- Consignee Detail -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12 mt-3">
                        <!-- Measurement Units -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Measurement Units</h4>
                                <a  id="calculator" style="color: #007bff; cursor:pointer; float:left;">Centimeter Calculator</a>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">PKT</label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="no_of_pack" class="form-control my-colorpicker1 no_of_pack" data-attr="1" id="no_of_pack1" autocomplete="off" required="required">
                                                </div>
                                                <label class="col-sm-2 col-form-label">Actual Weight</label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="actual_weight" class="form-control my-colorpicker1 actual_weight" data-attr="1" id="actual_weight" autocomplete="off" required="required">
                                                </div>
                                                <label class="col-sm-2 col-form-label">Chargeable Weight</label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="chargable_weight" readonly class="form-control my-colorpicker1 chargable_weight" data-attr="1" id="chargable_weight" autocomplete="off" required="required">
                                                </div>
                                                <?php if($_SESSION['franchise_type']==1 OR $_SESSION['franchise_type']==3){ ?>
                                                <label class="col-sm-3 col-form-label"><small><b>Is
															Appointment</b></small>&nbsp;&nbsp;&nbsp;
                                                            <input type="checkbox" id="is_appointment" name="is_appointment" value="1" disabled>
                                                        </label>
												<!-- <div class="col-sm-1">
													<br>
													
												</div> -->
                                                <!-- <label class="col-sm-3 col-form-label"><small><b>
															Pickup Charges</b></small>&nbsp;&nbsp;&nbsp;
                                                            <input type="checkbox" id="pickup_access" name="pickup_access" value="1" disabled>
                                                        </label> -->
												<div class="col-sm-1">
													<br>
													
												</div>
                                                <?php } ?>
                                            </div>
                                            <div id="volumetric_table" style="display:none;">
                                                <table class="weight-table">
                                                    <thead>
                                                        <tr><input type="hidden" class="form-control" name="length_unit" id="length_unit" class="custom-control-input" value="cm">
                                                            <th>Per Box Pack</th>
                                                            <th class="length_th">L ( Cm )</th>
                                                            <th class="breath_th">B ( Cm )</th>
                                                            <th class="height_th">H ( Cm )</th>
                                                            <th class="volumetric_weight_th">Valumetric Weight</th>
                                                            <th class="volumetric_weight_th">AW</th>
                                                            <th class="volumetric_weight_th">Chargeable Weight</th>

                                                        </tr>
                                                        <thead>
                                                        <tbody id="volumetric_table_row">
                                                            <tr>
                                                                <td><input type="text" name="per_box_weight_detail[]" autocomplete="off" class="form-control per_box_weight valid" data-attr="1" id="per_box_weight1" max="1" aria-invalid="false"></td>
                                                                <td class="length_td"><input type="text" name="length_detail[]" autocomplete="off" class="form-control length" data-attr="1" id="length1" required="required"></td>
                                                                <td class="breath_td"><input type="text" name="breath_detail[]" autocomplete="off" class="form-control breath" data-attr="1" id="breath1" required="required"></td>
                                                                <td class="height_td"><input type="text" name="height_detail[]" autocomplete="off" class="form-control height" data-attr="1" id="height1" required="required"></td>
                                                                <td class="volumetic_weight_td"><input type="text" autocomplete="off" name="valumetric_weight_detail[]" readonly class="form-control valumetric_weight" data-attr="1" id="valumetric_weight1"></td>

                                                                <td class="volumetic_weight_td"><input type="text" autocomplete="off" name="valumetric_actual_detail[]" class="form-control valumetric_actual" data-attr="1" id="valumetric_actual1"></td>

                                                                <td class="volumetic_weight_td"><input type="text" autocomplete="off" name="valumetric_chageable_detail[]" readonly class="form-control valumetric_chageable" data-attr="1" id="valumetric_chageable1"></td>
                                                            </tr>
                                                        </tbody>
                                                    <tfoot>

                                                    </tfoot>
                                                </table>
                                                <table>
                                                    <tr>

                                                        <th><input type="text" name="per_box_weight" readonly="readonly" class="form-control  per_box_weight" id="per_box_weight" required="required"></th>
                                                        <th class="length_td"><input type="text" name="length" readonly="readonly" class="form-control length" id="length"></th>
                                                        <th class="breath_td"><input type="text" name="breath" readonly="readonly" class="form-control breath" id="breath"></th>
                                                        <th class="height_td"><input type="text" name="height" readonly="readonly" class="form-control height" id="height"></th>
                                                        <th class="volumetic_weight_td"><input type="text" name="valumetric_weight" readonly="readonly" class="form-control my-colorpicker1 valumetric_weight" id="valumetric_weight"></th>

                                                        <th class="volumetic_weight_td"><input type="text" name="valumetric_actual" readonly="readonly" class="form-control my-colorpicker1 valumetric_weight" id="valumetric_actual"></th>

                                                        <th class="volumetic_weight_td"><input type="text" name="valumetric_chageable" readonly="readonly" class="form-control my-colorpicker1 valumetric_weight" id="valumetric_chageable"></th>
                                                        <!-- <td><input type="text" name="one_cft_kg" readonly="readonly" class="form-control my-colorpicker1 one_cft_kg" id="one_cft_kg"></td> -->
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Measurement Units -->
                    </div>
                    <div class="col-md-6 col-sm-12 mt-3">
                        <!-- Charges -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Charges</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 charges">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Freight Chg.</label>
                                                <div class="col-sm-3">
                                                    <input type="number" min="1" name="frieht" class="form-control" value="" required  id="frieht" readonly />
                                                </div>
                                                <label class="col-sm-3 col-form-label">ODA Chg.</label>
                                                <div class="col-sm-3">
                                                    <input type="number" name="delivery_charges" class="form-control" value="0" id="delivery_charges" readonly>
                                                </div>                                                
                                            </div>
                                            <div class="form-group row">                                                
                                                 <label class="col-sm-3 col-form-label">COD Chg.</label>
                                                <div class="col-sm-3">
                                                    <input type="number" name="courier_charges" class="form-control" value="0" id="courier_charges" readonly>
                                                </div>
                                                 <label class="col-sm-3 col-form-label">AWB Chg.</label>
                                                <div class="col-sm-3">
                                                    <input type="number" name="awb_charges" class="form-control" value="0" id="awb_charges" readonly>
                                                </div>
                                            </div>
                                         
                                            <div class="form-group row">

                                                <label class="col-sm-3 col-form-label">Other Chg.</label>
                                                <div class="col-sm-3">
                                                    <input type="number" name="other_charges" class="form-control" value="0" id="other_charges" readonly>
                                                </div>
                                                <label class="col-sm-3 col-form-label">Fov Chg.</label>
                                                <div class="col-sm-3">
                                                    <input type="number"  class="form-control" name="fov_charges" id="fov_charges" value="0" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">

                                                
                                                <label class="col-sm-3 col-form-label">Total</label>
                                                <div class="col-sm-3">
                                                    <input type="number"  name="amount" required class="form-control" value="0" id="amount" readonly />
                                                </div>
                                                <label class="col-sm-3 col-form-label">Fuel Surcharge</label>
                                                <div class="col-sm-3">
                                                    <input type="number"  class="form-control" name="fuel_subcharges" value="0" id="fuel_charges" readonly>
                                                </div>

                                            </div>
                                            <!--<div class="form-group row">-->

                                                
                                            <!--</div>-->

                                        </div>
                                        <div class="col-12 showcharges" style="height:200px; display:none;">
                                        <div class="form-group row">
                                                 <label class="col-sm-12 col-form-label" id="rate_display"></label>
                                                <div class="col-sm-3">
                                                    <!-- Charges   -->
                                                    <input type="hidden" name="frieht1" min="1" class="form-control" value="" required  id="frieht1" readonly>
                                                    <input type="hidden" name="delivery_charges1" class="form-control" value="0" id="delivery_charges1" readonly>
                                                    <input type="hidden" name="courier_charges1" class="form-control" value="0" id="courier_charges1" readonly>
                                                    <input type="hidden" name="other_charges1" class="form-control" value="0" id="other_charges1" readonly>
                                                    <input type="hidden" name="fov_charges1" class="form-control"  value="0" id="fov_charges1"  readonly>
                                                    <input type="hidden" name="appt_charges1" class="form-control"  value="0" id="appt_charges1"  readonly>
                                                    <input type="hidden" name="awb_charges1" class="form-control" value="0" id="awb_charges1" readonly>
                                                    <input type="hidden" name="pickup_charges1" class="form-control" value="0" id="pickup_charges1" readonly>
                                                    <input type="hidden" name="rate1" class="form-control" value="0" id="rate1" readonly>
                                                    <input type="hidden" name="topay1" class="form-control" value="0" id="green_tax1" readonly>
                                                    <input type="hidden" name="booking_charges" class="form-control" value="0" id="booking_charges" readonly>                  
                                                    <input type="hidden" name="delivery_c_charges" class="form-control" value="0" id="delivery_ccharges" readonly>
                                                    <input type="hidden" name="door_delivery_charges" class="form-control" value="0" id="door_delivery_charges" readonly>
                                                    <input type="hidden" name="amount1" class="form-control" value="0" id="amount1" readonly>
                                                    <input type="hidden" name="fuel_charges1" class="form-control" value="0" id="fuel_charges1" readonly>
                                                        <br>
                                                    <input type="hidden" name="sub_total1" class="form-control" value="0" id="sub_total1" readonly>
                                                    <input type="hidden" name="cgst1" class="form-control" value="0" id="cgst1" readonly>
                                                    <input type="hidden" name="sgst1" class="form-control" value="0" id="sgst1" readonly>
                                                    <input type="hidden" name="igst1" class="form-control" value="0" id="igst1" readonly>
                                                    <input type="hidden" name="grand_total1" class="form-control" value="0" id="grand_total1" readonly>
                                                    <br>
                                                    <!-- franchise Commision  -->             
                                                    <input type="hidden" name="booking_comission" class="form-control" value="0" id="booking_comission" readonly>                  
                                                    <input type="hidden" name="delivery_commission" class="form-control" value="0" id="delivery_commission" readonly>                  
                                                    <input type="hidden" name="door_delivery_share" class="form-control" value="0" id="door_delivery_share" readonly>     
                                                   

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-header">
                                <h4 class="card-title">Final Charge</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row charges">                                                                               
                                               
                                                <div class="col-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Sub Total</label>
                                                        <div class="col-sm-9">
                                                            <input type="number" readonly name="sub_total" class="form-control" value="0" id="sub_total" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">CGST Tax</label>
                                                        <div class="col-sm-9">
                                                            <input class="form-control" type="number" id="cgst" step="any" name="cgst" value="0" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">SGST Tax</label>
                                                        <div class="col-sm-9">
                                                            <input class="form-control" type="number" id="sgst" step="any" name="sgst" value="0" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">IGST Tax</label>
                                                        <div class="col-sm-9">
                                                            <input class="form-control" type="number" id="igst" step="any" name="igst" value="0" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Grand Total</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" readonly class="form-control" name="grand_total" value="0" id="grand_total" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                            <div class="form-group row mt-3">
                                                <div class="col-sm-12"  id="submit">
                                                   
                                                    <button type="submit" class="btn btn-primary" style="display:none"
                                                        id="submit1">Submit</button> &nbsp;
                                                    <button type="button" class="btn btn-primary"
                                                        onclick="return NotifySubmission();"
                                                        id="desabledBTN">Submit &nbsp;
                                                        <span class="spinner-border spinner-border-sm" id="spinner" style="display:none" role="status" aria-hidden="true"></span>
                                                    </button> &nbsp;
                                                    <button type="button" onclick="return open_new_page()"
                                                        class="btn btn-primary">New</button>
                                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Charges -->


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
</main>
<div class="modal fade bd-example-modal-lg" id="submit_notify" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Shipment Save Alert!</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body" id="mbg-color">
                <div  style="line-height:10px;padding-left:0px; margin:25px 0;">
                    <h4>Are You Sure , Want Save?</h4>
                </div>
            
            <div class="modal-footer">
                <button type="button" onclick="return checkForTheCondition();" class="btn btn-primary">Book</button>
                <button type="button" class="btn btn-danger" id="cancel_model" data-dismiss="modal">Cancel</button>
            </div>
            </div>
            </div>
        </div>
        </div>
<div class="main"> <i style="float: right; margin-right:20px; margin-top:10px; font-size:17px;cursor:pointer;" id="close" class="fa fa-times" aria-hidden="true"></i>
  <style>
    .main{
      position: absolute;
      top: 100%;
      left: 20%;
      z-index: 9;
      background-color: #fff;
      width: 600px;
      height: 250px;
      display: none;
      box-shadow: 5px 5px 30px grey;
      border-radius: 5px;
      
    } 
    .calculate{
      padding: 10% 20px;
    }

  </style>
  <div class="calculate">
  <h1 class="text-center">Calculator</h1>
       <div class="row">
        <div class="col-md-5">
         <label for="Inch">Inch</label><br>
          <input type="number" id="input" class="form-control" placeholder="Inch"></div>
        <div class="col-md-1"><br><br> = </div>
        <div class="col-md-5">
        <label for="Inch">Centimeter</label><br>  
        <input type="text" id="result" class="form-control"></div>
       </div>
  </div>

<?php include (dirname(__FILE__) . '/../franchise_shared/franchise_footer.php'); ?>