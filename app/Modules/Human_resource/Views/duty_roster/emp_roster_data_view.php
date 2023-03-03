<div class="row justify-content-center">
    <div class="col-sm-12" id="printin">

        <!--/.End of header-->
        <div class="card card-body ">

            <div class="col-12 col-md-12">

                <div class="row">

                    <strong><?php echo get_phrases(['shift','start','date']).' :'?></strong>&ensp;<?php echo $rstr_vdata->roster_start;?>
                    &emsp;&emsp;
                    <strong><?php echo get_phrases(['shift','end','date']).' :' ?></strong>&ensp;<?php echo $rstr_vdata->roster_end;?>
                    &emsp;&emsp;

                    <strong><?php echo get_phrases(['shift','name']).':'?></strong>&ensp;<?php echo $rstr_vdata->shift_name.'-'.$rstr_vdata->department_name.' department'; ?>
                </div>

            </div>

            <strong class="assign_emp"><?php echo get_phrases(['assigned','employee']);?></strong>

            <div class="col-12">

                <div class="row">
                    <?php if (!empty($rosterempdata)) { ?>
                    <?php  $sl = 1;?>
                    <?php foreach ($rosterempdata as $row) { ?>

                    <div class="room-design">

                        <div class="form-check form-check-inline">
                            <label class="form-check-label" data-toggle="tooltip" data-placement="top" title="Ready"
                                for="materialInline101"><?php echo $row->first_name.' '.$row->last_name;?></label>
                        </div>
                        <p><?php echo get_phrases(['employee','id'])." : ".$row->emp_id?></p>
                    </div>


                    <?php $sl++; ?>
                    <?php }; ?>
                    <?php } ?>
                </div>

            </div>

        </div>
    </div>
</div>

<style>
.room-design {
    text-align: center;
    /* border: 1px solid #767676; */
    padding: 13px;
    position: relative;
}

.room-design .form-check-inline {
    margin-right: 0;
}
.assign_emp {
    margin-top: 17px;
    text-align: center;
    font-size: 17px;
}
</style>