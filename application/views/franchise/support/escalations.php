<?php include(dirname(__FILE__).'/../franchise_shared/franchise_header.php'); ?>
<?php include(dirname(__FILE__).'/../franchise_shared/franchise_sidebar.php'); ?>
<main>
<!-- START: Card Data-->
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-body">
                <div class="row p-2">
                    <div class="col-md-6">
                        <h6 class="">Escalations</h6>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <a href="#" class="bg-primary py-2 px-2 rounded ml-auto text-white mr-2 text-center" data-toggle="modal" data-target="#newcontact">
                            <i class="icon-plus align-middle mr-1 text-white"></i>Add New Contact
                        </a>
                        <a href="" class="btn btn-outline btn-dark">Filter</a>
                    </div>



                    <div class="table-responsive mt-4">
                        <table class="display table  table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Sr.No.</th>
                                    <th scope="col">Escalation Type</th>
                                    <th scope="col">Details</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> 186342 </td>
                                    <td class="align-left">Forward Stuck In Transit<br><span class="badge badge-soft-primary">Shipment</span> </td>
                                    <td>AWB: <a style="color:#004080;font-weight: bold;" target="_blank" href="#">151239870199689</a><br>Status: <b>Delivered</b> </td>
                                    <td class="align-middle">Sep 30, 2022</td>
                                    <td class="align-middle"><span class=" text-success"> Closed</span> <br></td>
                                    <td class="align-middle">
                                        <a href="escalations/view/186342" class="btn btn-white">View Escalation</a>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- END: Card DATA-->
    <!-- Add Contact -->
    <div class="modal fade" id="newcontact">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="icon-pencil"></i> Add Contact
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="icon-close"></i>
                    </button>
                </div>
                <form class="add-contact-form">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="contact-name">
                                    <label for="contact-name" class="col-form-label">Name</label>
                                    <input type="text" id="contact-name" class="form-control" required="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="contact-phone">
                                    <label for="contact-phone" class="col-form-label">Select Query</label>
                                    <select name="issue_type" required="" class="form-control">
                                    <option value="">Select</option>
                                    <option value="shipment">Shipment</option>
                                    <option value="pickup">Pickup</option>
                                    <option value="billing">Billing &amp; Remittance</option>
                                    <option value="weight">Weight</option>
                                    <option value="tech">Tech</option>
                                    <option value="other">Other</option>
                                </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="contact-location">
                                    <label for="contact-location" class="col-form-label">Message</label>
                                    <input type="text" id="contact-location" class="form-control">
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary add-todo">Add Contact</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Contact -->
</main>
<?php include(dirname(__FILE__).'/../franchise_shared/franchise_footer.php'); ?>