<?php $this->load->view('franchise/franchise_shared/franchise_header');
$this->load->view('franchise/franchise_shared/franchise_sidebar'); ?>
<main>
    <div class="container-fluid site-width">
        <div class="row">
            <div class="col-12 col-sm-12 mt-5">
                <div class="card">
                    <div class="card-header justify-content-between align-items-center">

                    </div><!-- CARD HEADER CLOSE --->
                    <div class="card-body">
                        <div class="row">
                            <?php $customer_id = $_SESSION['customer_id'];
                            $franchise_info = $this->db->query("select * from tbl_customers where customer_id = '$customer_id' and isdeleted = '0'")->row();
                            ?>
                            <div class="col-md-3">
                                <div class="contact-occupation">
                                    <label for="contact-occupation" class="col-form-label">Franchise code</label>
                                    <input type="text" name="customer_id" placeholder="Enter Franchise Code"
                                        value="<?= $franchise_info->cid; ?>" readonly class="form-control">
                                    <input type="hidden" name="transection_id" id="transection_id" readonly value="<?= $transactionId;?>"
                                        class="form-control">
                                    <input type="hidden" name="atom_id" id="atom_id" value="<?= $atomTokenId;?>" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="contact-occupation">
                                    <label for="contact-occupation" class="col-form-label">Franchise Name</label>
                                    <input type="text" name="customer_name" placeholder="Enter Franchise Code"
                                        value="<?= $franchise_info->customer_name; ?>" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="contact-occupation">
                                    <label for="contact-occupation" class="col-form-label">Franchise Email</label>
                                    <input type="text" name="email" placeholder="Enter Franchise Code"
                                        value="<?= $franchise_info->email; ?>" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="contact-occupation">
                                    <label for="contact-occupation" class="col-form-label">Franchise Contact No</label>
                                    <input type="text" name="phone" placeholder="Enter Franchise Code"
                                        value="<?= $franchise_info->phone; ?>" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="contact-occupation">
                                    <label for="contact-occupation" class="col-form-label">Franchise Address</label>
                                    <textarea name="address" readonly
                                        class="form-control"><?= $franchise_info->address; ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="contact-occupation">
                                    <label for="contact-occupation" class="col-form-label">Franchise Current
                                        Balance</label>
                                    <input type="text" placeholder="Enter Franchise Code"
                                        value="<?= $franchise_info->wallet; ?>" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="contact-occupation">
                                    <label for="contact-occupation" class="col-form-label">Enter Amount</label>
                                    <input type="number" min="0" step="any" placeholder="Enter Franchise Code"
                                        name="recharge_wallet" id="recharge_wallet" value="<?= $amount;?>" readonly class="form-control" required>
                                </div>
                            </div>

                        </div>

                    </div>

                      
                    <a name="" id="atomClick" class="btn btn-primary btn-lg" style="color:#fff;" role="button">Pay Now</a>
                </div><!-- CARD BODY CLOSE --->
            </div><!-- CARD CLOSE --->
        </div>
    </div>
    </div>
</main>
<?php $this->load->view('franchise/franchise_shared/franchise_footer');?>
<script>
    $('document').ready(function(){
        $('#atomClick').click(function () {
            $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>/atom_payment/Recharge_wallet/transection_entery',
            data: {amount:'<?= $amount;?>',transection_id:'<?= $transactionId;?>',atomID:'<?= $atomTokenId?>'},
            dataType: "json",
            success: function (d) {         
                       if(d=='1'){
                        // $('#atomClick').prop('disabled', true);
                        $('#atomClick').hide();
                        var options = {
                                "atomTokenId": <?= $atomTokenId; ?>,
                                "merchId": <?= $merchId; ?>,
                                "custEmail": "<?= $franchise_info->email; ?>",
                                "custMobile": <?= $franchise_info->phone; ?>,
                                "returnUrl": "https://boxnfreight.co.in/atom_payment/Recharge_wallet/response"

                        }
                        //console.log(options);
                       let atom = new AtomPaynetz(options, 'uat');
                       }
                       else
                       {
                        alert("Unable to Proceed it");
                       }
            
            }
      });
        

       
        });
    });
</script>