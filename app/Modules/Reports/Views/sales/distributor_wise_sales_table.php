<div class="row">
    <div class="col-md-12 text-center">
        <img src="<?php echo base_url() . $setting->logo; ?>" class="img-responsive" alt=""><strong><?php echo $setting->title; ?></strong><br>
        <?php echo $setting->address; ?>
    </div>
</div>
<hr>
<h4>
    <center><?php echo $title; ?></center>
</h4>
<div class="row">
    <div class="col-md-6">
        
    </div>
    <div class="col-md-6 text-right">
    <b>Date: <?php echo $date; ?></b>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-stripped table-sm table-hover detailsTable">
        <thead>
            <tr>
                <th width="10%"><?php echo get_phrases(['sl']); ?></th>
                <th width="10%">Distributor Name</th>
                <th width="10%">Address</th>
                <th width="10%">DO No</th>
                <th width="10%">Type</th>
                <th width="10%">Qty/Mt</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_mt = 0;
            $sl = 0;
            if (!empty($results)) {
                foreach ($results as $value) {
                    $sl++;
                    $mt = $value->total_kg/1000;
                    $total_mt += $mt;
                    ?>
                    <tr onclick="onclick_change_bg('.detailsTable', this, 'cyan')">
                        <td><?php echo esc($sl); ?></td>
                        <td><?php echo esc($value->dealer_name); ?></td>
                        <td><?php echo esc($value->dealer_address); ?></td>
                        <td><?php echo esc($value->vouhcer_no); ?></td>
                        <td><?php echo esc($value->item_type); ?></td>
                        <td><?php echo esc(number_format($mt, 2)); ?></td>
                    </tr>
            <?php } } else { ?>
                <tr>
                    <th colspan="8" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available'); ?></th>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot3>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th><?php echo esc(number_format($total_mt, 2)); ?></th>
            </tr>
        </tfoot3>
    </table>
</div>
<div class="row form-group">
    <div class="col-sm-3">
        <br><br>
        ----------------<br>
        Sales Admin
    </div>
    <div class="col-sm-3 text-center">
        <br><br>
        ------------------<br>
        AGM (Accounts & Finance)
    </div>
    <div class="col-sm-3 text-center">
        <br><br>
        --------------------<br>
        DGM (Marketing)
    </div>
    <div class="col-sm-3 text-right">
        <br><br>
        --------------------<br>
        GM (Operation)
    </div>
</div>
<!-- <div class="row form-group">
    <div class="col-sm-4 text-center">
        <br><br>
        ----------------<br>
        Factory Manager
    </div>
    <div class="col-sm-4 text-center">
        <br><br>
        ------------------<br>
        GM ( Operations )
    </div>
    <div class="col-sm-4 text-center">
        <br><br>
        --------------------<br>
        Project Coordinator
    </div>
</div> -->
<?php if ($hasPrintAccess) { ?>
    <div class="card-footer no-print">
        <button type="button" class="btn btn-info" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']); ?></button>
    </div>
<?php } ?>