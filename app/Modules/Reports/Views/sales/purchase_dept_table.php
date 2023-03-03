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
                <th width="10%">SPR No</th>
                <th width="10%">SPR Date</th>
                <th width="10%">SPR Qnty/Ton</th>
                <th width="10%">PO No</th>
                <th width="10%">PO Date</th>
                <th width="10%">Supplier</th>
                <th width="10%">PO Qnty</th>
                <th width="10%">Truck No</th>
                <th width="10%">Received Qnty</th>
                <th width="10%">Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 0;
            if (!empty($results)) {
                foreach ($results as $value) {
                    $sl++;
                    ?>
                    <tr onclick="onclick_change_bg('.detailsTable', this, 'cyan')">
                        <td><?php echo esc($sl); ?></td>
                        <td><?php echo esc($value->spr_no); ?></td>
                        <td><?php echo esc($value->spr_date); ?></td>
                        <td><?php echo esc($value->spr_qty); ?></td>
                        <td><?php echo esc($value->po_no); ?></td>
                        <td><?php echo esc($value->po_date); ?></td>
                        <td><?php echo esc($value->supplier_name); ?></td>
                        <td><?php echo esc($value->po_qty); ?></td>
                        <td><?php echo esc($value->truck_no); ?></td>
                        <td><?php echo esc($value->received_qty); ?></td>
                        <td></td>
                    </tr>
            <?php } } else { ?>
                <tr>
                    <th colspan="11" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available'); ?></th>
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
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
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