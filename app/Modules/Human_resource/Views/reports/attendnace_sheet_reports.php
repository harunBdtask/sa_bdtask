<?php

use App\Models\Bdtaskt1m1CommonModel;

$this->bdtasktCmModel = new Bdtaskt1m1CommonModel();

// echo "<pre>";
// print_r($results);


?>
<div class="row">
  <div class="col-md-12 text-center">
    <img src="<?php echo base_url() . $setting->logo; ?>" class="img-responsive" alt=""><strong><?php echo $setting->title; ?></strong>
    <br><?php echo $setting->address; ?>
    <br>Employee type: <?php echo $employee_type->type; ?> , Date: <?php echo $date; ?>

  </div>
</div>
<hr>
<h4>
  <center id="title"></center>
</h4>
<div class="table-responsive">
  <table class="table table-stripped table-sm table-hover table-bordered detailsTable">
    <thead>
      <tr>
        <th width="5%"><?php echo get_phrases(['sl']); ?></th>
        <th width="18%"><?php echo get_phrases(['name']); ?></th>
        <th width="10%"><?php echo get_phrases(['date', 'of', 'joining']); ?></th>
        <th width="8%"><?php echo get_phrases(['total', 'days']); ?></th>
        <th width="7%"><?php echo "WH & GH"; ?></th>
        <th width="10%"><?php echo get_phrases(['working', 'days']); ?></th>
        <th width="7%"><?php echo get_phrases(['present', 'days']); ?></th>
        <th width="7%"><?php echo get_phrases(['casual', 'leave']); ?></th>
        <th width="7%"><?php echo get_phrases(['sick', 'leave']); ?></th>
        <th width="7%"><?php echo get_phrases(['total', 'leave']); ?></th>
        <th width="7%"><?php echo get_phrases(['cpl']); ?></th>
        <th width="7%"><?php echo get_phrases(['payable', 'days']); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      
      if (!empty($results)) {
        foreach ($results as $key => $result) {
      ?>
            <tr>
              <td colspan = "12"><b><?php echo get_phrases(['department']); ?>: <?php echo esc($key); ?><b></td>
            </tr>

            <?php 

            $sl = 1;
            foreach ($result as $value) { 

            ?>

            <tr>
              <td><?php echo esc($sl); ?></td>
              <td><?php echo esc($value['name']); ?></td>
              <td><?php echo esc($value['date_of_joining']); ?></td>
              <td><?php echo esc($value['total_days']); ?></td>
              <td><?php echo esc($value['wh_gh']); ?></td>
              <td><?php echo esc($value['working_days']); ?></td>
              <td><?php echo esc($value['present_days']); ?></td>
              <td><?php echo esc($value['cl']); ?></td>
              <td><?php echo esc($value['sl']); ?></td>
              <td><?php echo esc($value['total_leave']); ?></td>
              <td><?php echo esc($value['cpl']); ?></td>
              <td><?php echo esc($value['payable_days']); ?></td>

            </tr>

          <?php $sl++; } ?>

        <?php }
      } else { ?>
        <tr>
          <th colspan="10" class="text-center text-muted text-danger"><?php echo get_notify('data_is_not_available'); ?></th>
        </tr>
      <?php } ?>
    </tbody>

  </table>
</div>

<?php if ($hasPrintAccess) { ?>

<div class="card-footer no-print">
  
  <button type="button" class="btn btn-info" onclick="printContent('printC')"><span class="fa fa-print"></span> <?php echo get_phrases(['print']); ?></button>
</div>

<?php } ?>