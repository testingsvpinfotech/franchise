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
                        <h6 class="">Billing</h6>
                    </div>
                </div>
                <hr>
              <?php  include(dirname(__FILE__).'/../billing_master/billing_tab.php'); ?>


                <div class="mt-4 mb-4"><button class="btn btn-sm btn-primary">Export</button></div>
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="card mb-4">
                            <div class="card-body">
                            <div class="card-body">
                               <table class="table table-sm">
                                <tbody>
                                <tr style="background-color:#dddc;">
                                <th class="text-center">Pickup</th>
                                <th class="text-center">PNQ</th>
                                <th class="text-center">BOM</th>
                                <th class="text-center">AMD</th>
                                <th class="text-center">DEL</th>
                                <th class="text-center">CHD</th>
                                <th class="text-center">J&amp;K</th>
                                <th class="text-center">HYD</th>
                                <th class="text-center">BLR</th>
                                <th class="text-center">MAA</th>
                                <th class="text-center">CJB</th>
                                <th class="text-center">COK</th>
                                <th class="text-center">IND</th>
                                <th class="text-center">NGP</th>
                                <th class="text-center">CCU</th>
                                <th class="text-center">GAU</th>
                                <th class="text-center">NE</th>
                                <th class="text-center">LOK</th>
                                <th class="text-center">PAT</th>
                            </tr>
                                </tbody>
                               </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Card DATA-->
</div>


</main>
<?php include(dirname(__FILE__).'/../franchise_shared/franchise_footer.php'); ?>