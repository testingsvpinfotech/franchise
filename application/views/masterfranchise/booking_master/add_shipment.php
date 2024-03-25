<?php $this->load->view('masterfranchise/master_franchise_shared/admin_header.php'); ?>
<?php $this->load->view('masterfranchise/master_franchise_shared/admin_sidebar.php'); ?>
 
 <main>

 <style>
    .form-control:disabled, .form-control[readonly] {
    background-color: #c9cccf!important;
    opacity: 1;
}
 </style>
<div class="container-fluid site-width">
    <!-- START: Breadcrumbs-->
   
    <!-- END: Breadcrumbs-->

    <!-- START: Card Data-->
    <div class="row">
        <div class="col-12 card  align-self-center">
            <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                <div class="w-sm-100 mr-auto">
                    <h4 class="mb-0">Shipment Form </h4>
                </div>
                 <!-- <span style="float: right;"><a href="<?php echo base_url(); ?>franchise/shipment-list" class="btn btn-primary">View Domestic Shipment</a></span> -->
            </div>
        </div>
        <div class="col-12 col-sm-12">
            <div class="row">
                <div class="col-12 col-md-12 mt-3">
                    <!-- <div class="card">
                        <div class="card-body">
                            <div class="wizard mb-4 ml-4 mr-4">
                                <div class="connecting-line"></div>
                                <ul class="nav nav-tabs d-flex mb-3">
                                    <li class="nav-item ">
                                        <a class="nav-link position-relative round-tab text-left p-0 active border-0" data-toggle="tab" href="#id1">
                                            <i class="fas fa-address-card position-relative text-white h5 mb-3"></i>
                                            <small class="d-none d-md-block ">Consignee Details</small>
                                        </a>
                                    </li>
                                    <li class="nav-item ml-auto">
                                        <a class="nav-link position-relative round-tab text-sm-right text-left p-0 border-0" data-toggle="tab" href="#id4">
                                            <i class="fas fa-sticky-note position-relative text-white h5 mb-3"></i>
                                            <small class="d-none d-md-block">Summary Details</small>
                                        </a>
                                    </li>
                                    <li class="nav-item ml-auto">
                                        <a class="nav-link position-relative round-tab text-sm-right text-left p-0 border-0" data-toggle="tab" href="#id5">
                                            <i class="icon-credit-card position-relative text-white h5 mb-3"></i>
                                            <small class="d-none d-md-block">Payment And Reciept</small>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div> -->







                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="id1">
                         
                               <form  action="<?php echo base_url(); ?>master_franchise/add-shipment" method="POST" id="formSubmit">
                <div class="row">
                    <div class="col-md-4 col-sm-12 mt-3">
                        <!-- Shipment Info -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Shipment Info</h4>
                                <!--<span style="float: right;"><a href="admin/view-domestic-shipment" class="btn btn-primary">View Domestic Shipment</a></span>-->
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

                                            // $tracking_data[0]['tracking_date'] = date('Y-m-d H:i',strtotime($tracking_data[0]['tracking_date']));
                                            $datec = str_replace(" ", "T", $datec);
                                            if ($this->session->userdata('booking_date') != '') { ?>

                                                        <input type="datetime-local" name="booking_date" value="<?php echo $this->session->userdata('booking_date'); ?>" id="booking_date" readonly class="form-control">
                                                    <?php
                                            } else { ?>
                                                        <input type="datetime-local" name="booking_date" value="<?php echo $datec; ?>" id="booking_date" readonly class="form-control">
                                            <?php } ?>
                                        </div>
                                    </div>
                                   
                                    
                                    <!--<div class="form-group row">-->
                                    <!--    <label class="col-sm-4 col-form-label">Airway No<span class="compulsory_fields">*</span></label>-->
                                    <!--    <div class="col-sm-8">-->
                                            <!-- <input type="hidden" name="awn" id="awn" class="form-control" value="<?php echo $bid; ?>">
                                            <input type="hidden" name="courier_company" id="courier_company" class="form-control" value="51"> -->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    
                                    <?php $customer_id = $_SESSION['customer_id'];
                                    $readonly = $this->db->query("SELECT * FROM tbl_branch_assign_cnode WHERE customer_id = '$customer_id'")->row(); ?>
                                    <div class="form-group row">
                                       <label class="col-sm-4 col-form-label">Airway No<span class="compulsory_fields">*</span></label>
                                       <div class="col-sm-8">
                                            <input type="text" name="awn"  class="form-control pod" value="" <?php if (empty($readonly)) {
                                                echo 'readonly';
                                            } else { ?> id="pod" <?php } ?> pattern="[a-zA-Z]" required autocomplete="off" style="text-transform: uppercase;">
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
                                                if (!empty($transfer_mode)) {
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
                                    <!--<div class="form-group row">-->
                                   
                                    <!--    <label class="col-sm-4 col-form-label">EDD</label>-->
                                    <!--    <div class="col-sm-8">-->
                                    <!--        <input type="date" id="delivery_date" name="delivery_date" value="<?php echo date('d-m-Y'); ?>"  class="form-control">-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                  
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Desc.</label>
                                        <div class="col-sm-8">
                                            <textarea name="special_instruction" class="form-control my-colorpicker1"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Risk Type<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="risk_type" disabled id="risk_type">
                                                 <option value="Customer">Customer</option>
                                                <option value="Carrier">Carrier</option>
                                            </select>
                                            <input type="hidden" name="risk_type" value="Customer">
                                        </div>
                                        <label class="col-sm-4 col-form-label">Bill Type<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="dispatch_details" id="dispatch_details">
                                                <option value="">-Select-</option>
                                                <option value="COD">COD</option>
                                                <option value="PrePaid">Pre-Paid</option>
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
                                                <input type="text" name="invoice_value" id="invoice_value" class="form-control my-colorpicker1">
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
                                        <label class="col-sm-4 col-form-label">Customer</label>
                                        <div class="col-sm-8" id="credit_div">
                                            <select class="form-control" name="customer_account_id" id="customer_account_id">
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
                                            <input type="text" maxlength="6" minlength="6" name="sender_pincode" id="sender_pincode" value="<?= $balance->pincode; ?>" class="form-control">
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
                                            <input type="text" maxlength="10" minlength="10" name="sender_contactno"  id="sender_contactno" class="form-control ">
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
                                            <input type="text" maxlength="6" minlength="6" class="form-control" name="reciever_pincode" id="reciever_pincode" autocomplete="off">
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
                                        <label class="col-sm-4 col-form-label">City<span class="compulsory_fields">*</span></label>
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
                                            <input type="text" class="form-control" maxlength="10" minlength="10" required id="reciever_contact" name="reciever_contact" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">GST NO.</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="receiver_gstno" id="receiver_gstno" class="form-control">
                                        </div>
                                    </div>
                                  
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
                                                    <input type="text" name="no_of_pack" autocomplete="off" class="form-control my-colorpicker1 no_of_pack" data-attr="1" id="no_of_pack1" required="required">
                                                </div>
                                                <label class="col-sm-2 col-form-label">Actual Weight</label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="actual_weight" autocomplete="off" class="form-control my-colorpicker1 actual_weight" data-attr="1" id="actual_weight" required="required">
                                                </div>
                                                <label class="col-sm-2 col-form-label">Chargeable Weight</label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="chargable_weight" autocomplete="off" class="form-control my-colorpicker1 chargable_weight" data-attr="1" id="chargable_weight" required="required">
                                                </div>
                                                <label class="col-sm-2 col-form-label">Is Volumetric</label>
                                                <div class="col-sm-4">

                                                    <input type="checkbox" id="is_volumetric" name="fav_language" value="">

                                                </div>
                                            </div>
                                            <div id="volumetric_table" style="display:none ! important;">
                                                <table class="weight-table">
                                                    <thead>
                                                        <tr><input type="hidden" class="form-control" name="length_unit" id="length_unit" class="custom-control-input" value="cm">
                                                            <th>Per Box Pack</th>
                                                            <th class="length_th">L ( Cm )</th>
                                                            <th class="breath_th">B ( Cm )</th>
                                                            <th class="height_th">H ( Cm )</th>
                                                            <th class="volumetric_weight_th">Valumetric Weight</th>
                                                            <th class="volumetric_weight_th">Actual Weight</th>
                                                            <th class="volumetric_weight_th">Chargeable Weight</th>

                                                        </tr>
                                                        <thead>
                                                        <tbody id="volumetric_table_row">
                                                            <tr>
                                                                <td><input type="text" name="per_box_weight_detail[]" required  class="form-control per_box_weight valid" data-attr="1" id="per_box_weight1" aria-invalid="false"></td>
                                                                <td class="length_td"><input type="text" autocomplete="off" name="length_detail[]" required class="form-control length" data-attr="1" id="length1"></td>
                                                                <td class="breath_td"><input type="text" autocomplete="off" name="breath_detail[]" required class="form-control breath" data-attr="1" id="breath1"></td>
                                                                <td class="height_td"><input type="text" autocomplete="off" name="height_detail[]" required class="form-control height" data-attr="1" id="height1"></td>
                                                                <td class="volumetic_weight_td"><input type="text" autocomplete="off" required name="valumetric_weight_detail[]" readonly class="form-control valumetric_weight" data-attr="1" id="valumetric_weight1"></td>

                                                                <td class="volumetic_weight_td"><input type="text" autocomplete="off" required name="valumetric_actual_detail[]" class="form-control valumetric_actual" data-attr="1" id="valumetric_actual1"></td>

                                                                <td class="volumetic_weight_td"><input type="text" autocomplete="off" required name="valumetric_chageable_detail[]" readonly class="form-control valumetric_chageable" data-attr="1" id="valumetric_chageable1"></td>
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
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Freight</label>
                                                <div class="col-sm-3">
                                                    <input type="number" min="1" name="frieht" class="form-control" value="" required  id="frieht" readonly />
                                                </div>
                                                <label class="col-sm-3 col-form-label">ODA Charge</label>
                                                <div class="col-sm-3">
                                                    <input type="number" name="delivery_charges" class="form-control" value="0" id="delivery_charges" readonly>
                                                </div>
                                                <!--<label class="col-sm-3 col-form-label">Handling Charge</label>-->
                                                <!--<div class="col-sm-3">-->
                                                <!--    <input type="number" name="transportation_charges" class="form-control" value="0" id="transportation_charges">-->
                                                <!--</div>-->
                                            </div>
                                            <div class="form-group row">
                                                <!--<label class="col-sm-3 col-form-label">Pickup</label>-->
                                                <!--<div class="col-sm-3">-->
                                                <!--    <input type="number" name="pickup_charges" class="form-control" value="0" id="pickup_charges">-->
                                                <!--</div>-->
                                                 <label class="col-sm-3 col-form-label">COD</label>
                                                <div class="col-sm-3">
                                                    <input type="number" name="courier_charges" class="form-control" value="0" id="courier_charges" readonly>
                                                </div>
                                                 <label class="col-sm-3 col-form-label">AWB Ch.</label>
                                                <div class="col-sm-3">
                                                    <input type="number" name="awb_charges" class="form-control" value="0" id="awb_charges" readonly>
                                                </div>
                                            </div>
                                            <!--<div class="form-group row">-->
                                                <!--<label class="col-sm-3 col-form-label">Insurance</label>-->
                                                <!--<div class="col-sm-3">-->
                                                <!--    <input type="number" name="insurance_charges" class="form-control" id="insurance_charges">-->
                                                <!--</div>-->
                                               
                                            <!--</div>-->
                                            <div class="form-group row">



                                               
                                                <label class="col-sm-3 col-form-label">Other Ch.</label>
                                                <div class="col-sm-3">
                                                    <input type="number" name="other_charges" class="form-control" value="0" id="other_charges" readonly>
                                                </div>
                                                <label class="col-sm-3 col-form-label">Fov Charges</label>
                                                <div class="col-sm-3">
                                                    <input type="number"  class="form-control" name="fov_charges" id="fov_charges" value="0" readonly>
                                                </div>
                                            </div>

                                            <!--<div class="form-group row">-->



                                                <!--<label class="col-sm-3 col-form-label">Green Tax.</label>-->
                                                <!--<div class="col-sm-3">-->
                                                <!--    <input type="number" name="green_tax" class="form-control" value="0" id="green_tax">-->
                                                <!--</div>-->
                                                <!--<label class="col-sm-3 col-form-label">Appt Ch.</label>-->
                                                <!--<div class="col-sm-3">-->
                                                <!--    <input type="number" name="appt_charges" class="form-control" value="0" id="appt_charges">-->
                                                <!--</div>-->
                                            <!--</div>-->
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
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group row" id="payby" style="display:none;">
                                                        <label class="col-sm-2 col-form-label">Pay By<span class="compulsory_fields">*</span></label>
                                                        <div class="col-sm-4">
                                                            <select class="form-control" name="payment_method" id="payment_method">
                                                                <option>-Select-</option>
                                                                <?php foreach ($payment_method as $pm) { ?>
                                                                            <option value="<?php echo $pm['id']; ?>"><?php echo $pm['method']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group row" id="Refno" style="display:none;">
                                                        <label class="col-sm-3 col-form-label">Ref No</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="ref_no" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>
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
                                                <!-- type="button" -->
                                                    <button type="button" class="btn btn-primary">Submit</button> &nbsp;
                                                    <button type="button"  onclick="return open_new_page()" class="btn btn-prima ry">New</button>
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












                        <!-- *******************************Summary Details tab-4 **************************************************** -->



                        <div class="tab-pane fade" id="id4">
                             <div class="row">
                                    <div class="col-12 col-sm-12">
                                        <div class="row">
                                            <div class="col-12 col-md-4 mt-3">
                                                <div class="card mb-4">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h4 class="card-title">Order Information</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                          <div class="col-md-6">
                                                            <div><p>Order ID : </p></div>
                                                            <div><p>Order Type : </p> </div>
                                                         </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="col-12 col-md-4 mt-3">
                                                <div class="card">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h4 class="card-title">Consignee Information</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                          <div class="col-md-6">
                                                          <div><p>Name : </p></div>
                                                            <div><p>Phone Number : </p> </div>
                                                            <div><p>Pincode : </p> </div>
                                                            <div><p>City : </p> </div>
                                                            <div><p> Consignee Address : </p> </div>
                                                         </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-4 mt-3">
                                                <div class="card">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h4 class="card-title">Consigner Information</h4>
                                                    </div>
                                                   <div class="card-body">
                                                        <div class="row">
                                                          <div class="col-md-6">
                                                            <div><p>Name : </p></div>
                                                            <div><p>Phone Number : </p> </div>
                                                            <div><p>Pincode : </p> </div>
                                                            <div><p>City : </p> </div>
                                                            <div><p> Consignee Address : </p> </div>
                                                         </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                           <div class="col-12 col-md-12 mt-3">
                                                <div class="card">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h4 class="card-title">Item Details</h4>
                                                    </div>
                                                   <div class="card-body">
                                                        <table class ="table table-bordered">
                                                            <tbody>
                                                                <tr style="background-color:#ddd;color:#333;">
                                                                    <th>Item Name</th>
                                                                    <th>Item Quantity</th>
                                                                    <th>Item Price</th>
                                                                    <th>Item SKU</th>
                                                                </tr>
                                                                <tr>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                            </tbody>
                                                        <table class ="table table-bordered">
                                                            <tbody>
                                                                <tr style="background-color:#ddd;color:#333;">
                                                                    <th>Weight</th>
                                                                    <th>Length</th>
                                                                    <th>Breadth</th>
                                                                    <th>Height</th>
                                                                </tr>
                                                                <tr>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                         <div class="d-flex">
                                            <button type="button" class="btn btn-primary prevtab">Previous</button>
                                            <button type="button" class="btn btn-primary nexttab ml-auto">Next</button>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    <div class="tab-pane fade" id="id5">
                            <div class="row">
                            <div class="col-12 col-md-12 mt-3 ">
                                <div class="card mb-12 pb-2">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4 class="card-title">Genrate Report</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-10">
                                            <label>Bill Genrate To XB</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control">
                                            </div>
                                            </div>
                                            <div class="col-md-10">
                                            <label>Invoice Genrated To Customer</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control">
                                            </div>
                                            </div>
                                        </div>
                                        <div class="d-flex" >
                                        <button type="button"  class="btn btn-primary prevtab">Previous</button>
                                        <button type="submit" name="submit"  class="btn btn-primary nexttab ml-auto">Submit</button>
                                    </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                   </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
<br><br>
<?php $this->load->view('masterfranchise/master_franchise_shared/admin_footer.php'); ?>
<br>
</main>
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

 
  
     <script src="<?php echo base_url(); ?>assets/franchise_assets/domestic_shipment.js"></script>
      <!--<script src="assets/js/domestic_shipment.js"></script>-->
      <Script>
          $(document).ready(function(){
           // alert('hello');
           $('.main').hide();
            $('#calculator').click(function(){
              $('.main').show();
              

            });
            $('#close').click(function(){
              $('.main').hide();
              

            });
            $('#input').keyup(function(){
              var input = $('#input').val();
              
              var result = input * 2.54;
              // alert(result);
              $('#result').val(result);
            });
          });
        </Script>
     <script>

        $("#submit").on('click', function () {
            // e.preventDefault();
            frieht = $('#frieht').val();
            if(frieht){
                frieht=frieht.trim();
                if (frieht==0 || frieht=="") {
                    alert("This Service is Not Available!");
                    return false;
                }
            }else{
                alert("This Service is Not Available!");
                return false;
            }

            $('#formSubmit').submit();
            
        });
    
         // ***************franchise persnal Details use Pincode
  $("#reciever_pincode").on('blur', function () 
  {
    var pincode = $(this).val();
    if (pincode != null || pincode != '') {

        $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>Franchise_manager/getState',
        data: 'pincode=' + pincode,
        dataType: "json",
        success: function (d) {         
          var option;         
          option += '<option value="' + d.result3.id + '">' + d.result3.state + '</option>';
          $('#reciever_state').html(option);          
          var oda = '';         
          oda += '<span style="color:red;">Service Type : '+d.oda+'</span>';
          $('#oda').html(oda);          
        },
        error: function () {
                    $('#oda').html('<p>Service Not Available</p>');	
                }
      });
      $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>Franchise_manager/getCityList',
        data: 'pincode=' + pincode,
        dataType: "json",
        success: function (d) {         
          var option;         
          option += '<option value="' + d.id + '">' + d.city + '</option>';
          $('#reciever_city').html(option);
          
        }
      });
    }
  }); 


  $("#reciever_state, #reciever_city").blur(function () 
    {
        var reciever_state =$("#reciever_state").val();
        var reciever_city =$("#reciever_city").val();

      $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>Franchise_manager/getZone',
        data: {reciever_city:reciever_city,reciever_state:reciever_state},
        dataType: "json",
        success: function (d) {         
                    $("#receiver_zone_id").val(d.region_id);						
                    $("#receiver_zone1").val(d.region_name);	
          
        }
      });
    
}); 




//   alert('hello');
//  $("#volumetric_table").hide();
 $('#fov_charges').hide();

    $("#risk_type").change(function () 
    {
        var risk_type = $(this).val();
        if(risk_type=='Carrier'){
            $('#fov_charges').show();
        }else{
            $('#fov_charges').hide();
        }

    });
     $('#submit').hide();
    // $("#sub_total").blur(function (){
    //       var frieht = $('#sub_total').val();
     
    // if (frieht == '0' || frieht=='') {
    //     $('#submit').hide();
    // }else{
    //     $('#submit').show();
    // }
    // });
    // $("#amount").blur(function (){
    //       var frieht = $('#amount').val();
     
    // if (frieht == '0' || frieht=='') {
    //     $('#submit').hide();
    // }else{
    //     $('#submit').show();
    // }
    // });
    
    document.addEventListener('contextmenu', function(e) {
  e.preventDefault();
});
     
    $("#frieht").blur(function (){
          var frieht = $('#frieht').val();
          var height = $('#height').val();
          var reciever_state = $('#reciever_state').val();
          var reciever_city = $('#reciever_city').val();
          var reciever_pincode = $('#reciever_pincode').val();
          var reciever_address = $('#reciever_address').val();
          var contactperson_name = $('#contactperson_name').val();
          var reciever = $('#reciever').val();
          var breath = $('.breath').val();
          var height = $('.height').val();
          var length = $('.length').val();
          var shipment =$("#doc_typee").val();
          var per_box_weight1 =$("#per_box_weight1").val();
          if(shipment == '1'){
                if (frieht == '0' || frieht=='' || contactperson_name == '' || reciever == '' ||
                    height =='0' || height=='' || reciever_pincode == '' || reciever_address =='' ||
                    breath =='0' || breath =='' || reciever_city == '' || reciever_state =='' ||
                    length == '0' || length =='') {
                    $('#submit').hide();
                    $("#pod").attr("readonly", false);
                    if(breath =='0' || breath =='' )
                    {
                        alert('Is Volumetric Details Required');
                    }
                }else{
                    $('#submit').show();
                    $("#pod").attr("readonly", true);
                }
           }
           else
           {
            if (frieht == '0' || frieht=='' || contactperson_name == '' || reciever == '' ||
                    per_box_weight1 =='0' || per_box_weight1 == '' || reciever_pincode == '' || reciever_address =='' ||
                     reciever_city == '' || reciever_state =='') {
                    $('#submit').hide();
                    $("#pod").attr("readonly", false);
                        alert('Is Volumetric Details Required');
                }else{
                    $('#submit').show();
                    $("#pod").attr("readonly", true);
                }
           }
    });

    
    
    //customer
        $("#customer_account_id").change(function () 
    {
        var customer_name = $(this).val();
    //	alert(customer_name);
        if (customer_name != null || customer_name != '') 
        {
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: '<?php echo base_url(); ?>Franchise_manager/getsenderdetails',
                data: 'customer_name=' + customer_name,
                success: function (data) 
                {
                    $("#sender_name").val(data.user.customer_name);
                    $("#sender_address").val(data.user.address);
                    $("#sender_pincode").val(data.user.pincode);
                    $("#sender_contactno").val(data.user.phone);
                    $("#sender_gstno").val(data.user.gstno);
                    $("#gst_charges").val(data.user.gst_charges);
                    // $("#sender_city").val(data.user.city);
                    // $("#sender_state").val(data.user.state);					
                    $("#customer_account_id").val(customer_name);

                     var option;					
                     option += '<option value="' + data.user.city_id + '">' + data.user.city_name + '</option>';
                    $('#sender_city').html(option);

                     var option1;					
                     option1 += '<option value="' + data.user.state_id + '">' + data.user.state_name + '</option>';
                    $('#sender_state').html(option1);
                    var dispatch_details =$("#dispatch_details").val();
                
                    document.getElementById("reciever").focus();								
                }
            });
        }
    });
    
        // chkceing duplicate number
    // $("#awn").blur(function () {
    //     var pod_no = $(this).val();
    //     if (pod_no != null || pod_no != '') {
    //         $.ajax({
    //             type: 'POST',
    //             dataType: "json",
    //             url: '<?php echo base_url(); ?>Franchise_manager/check_duplicate_awb_no',
    //             data: 'pod_no=' + pod_no,
    //             success: function (data) {
    //                 if(data.msg!=""){       
    //                 		 $('#awn').focus();
    //                 		 $('#awn').val("");
    //                 		 alert(data.msg);
    //                 }else{
    //                 }
                    
    //             }
    //         });
    //     }
    // });

    document.addEventListener('contextmenu', function(e) {
  e.preventDefault();
});

        // chkceing duplicate number
        $("#pod").on({
            keydown: function(e) {
                if (e.which === 32)
                return false;
            },
            change: function() {
                this.value = this.value.replace(/\s/g, "");
            }
        });
        $("#pod").blur(function () {        
        var pod_no = $(this).val().toUpperCase();
        if (pod_no != null || pod_no != '') {
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: '<?php echo base_url(); ?>Master_franchise_manager/check_duplicate_awb_no',
                data: 'pod_no=' + pod_no,
                success: function (data) {
                    if(data.msg!=""){       
                            //  $('#awn').focus();
                             $('#pod').val("");
                             alert(data.msg);
                    }else{
                        if(data.mode != ""){
                            var data1 = '';
                            data1 += '<option value="'+data.mode.transfer_mode_id+'">'+data.mode.mode_name+'</option>';
                            // $("#mode_dispatch").html(data1);
                        }else{
                            // $('#awn').focus(); 
                             $('#pod').val("");
                            //  alert("This AWB Not In Stock");
                             alert("This AWB No. is not in allocated range "+data.from+" to "+data.to+"");
                        }
                    }
                    
                }
            });
        }
    });
    
    
    //doc and nondoc
        $("#doc_typee").change(function ()
    {
            var shipment =$("#doc_typee").val();
            if(shipment==1)
            {
                $('#div_inv_row').show();

                $(".length_td").show();
                $(".height_td").show();
                $(".breath_td").show();
                $(".volumetic_weight_td").show();
                $(".cft_th").show();                                                    
                $(".volumetric_weight_th").show();
                $(".length_th").show();
                $(".breath_th").show();
                $(".height_th").show();
            }else{
                $('#div_inv_row').hide();
                $('#invoice_no').val("");
                $('#invoice_value').val("");
                $('#eway_no').val("");

                $(".length_td").hide();
                $(".height_td").hide();
                $(".breath_td").hide();
                $(".volumetic_weight_td").hide();
                $(".cft_th").hide();                                                    
                $(".volumetric_weight_th").hide();
                $(".length_th").hide();
                $(".breath_th").hide();
                $(".height_th").hide();  
            }
    });
    

     </script>
    