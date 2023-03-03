<?php

use App\Libraries\Numberconverter;

$numberToword = new Numberconverter();
?>
<style>
    @media print {
        tr.cv {

            -webkit-print-color-adjust: exact;
        }

    }
</style>
<div class="col-md-12" id="printArea">
   
    <p style="font-size:10px">Print Date: <?php echo date('Y-m-d h:i:s')?></p>
    <div class="row">
        <div class="col-md-3">
            <img src="<?php echo base_url() . $settings_info->logo; ?>" alt="Logo" height="40px"><br><br>
        </div>
        <div class="col-md-6 text-center">
            <h6><?php echo $settings_info->title; ?></h6>

            <strong><u class="pt-4"><?php echo 'Debit Voucher'; ?></u></strong>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-12">
            <div class="float-right">
                <label class="font-weight-600 mb-0"><?php echo get_phrases(['voucher', 'no']); ?></label> : <?php echo esc($results->VNo); ?><br>
                <label class="font-weight-600 mb-0"><?php echo get_phrases(['voucher', 'date']); ?></label> : <?php echo esc(date('d/m/Y', strtotime($results->VDate))); ?>
            </div>
        </div>
    </div>
<div class="card">
    <table class="table table-bordered table-sm mt-2">

        <thead>
            <tr class="cv">
                <th style="background: #abbff9!important" class="text-center"><?php echo get_phrases(['particulars']); ?></th>
                <th style="background: #abbff9!important" class="text-center"><?php echo get_phrases(['debit']); ?></th>
                <th style="background: #abbff9!important" class="text-center"><?php echo get_phrases(['credit']); ?></th>

            </tr>


        </thead>
        <tbody>
            <?php
            $Debit = 0;
            $Credit = 0;
            if (!empty($results)) {
                $k = 0;
                foreach ($results->details as $row) {
                    $Debit += $row->Debit;
                    $Credit += $row->Credit;
                    $k++;
                    $style = $k % 1 ? '#efefef!important' : '';
            ?>
                    <tr class="<?php echo $k % 1 ? 'cv' : '' ?>">
                        <td style="background:<?php echo $style; ?>"><strong style="font-size: 15px;;"><?php echo $row->HeadName . ($row->subType != 1 ? '(' . $row->subname . ')' : ''); ?></strong><br>
                            <span> <?php echo $row->ledgerComment ?></span>
                        </td>
                        <td style="background:<?php echo $style; ?>" class="text-right"><?php echo esc($row->Debit); ?></td>
                        <td style="background:<?php echo $style; ?>" class="text-right"><?php echo esc($row->Credit); ?></td>
                    </tr>
                <?php }
            } else { ?>
                <tr style="background:<?php echo $style; ?>">
                    <td colspan="3" class="text-center text-danger"><?php echo get_notify('data_is_not_available'); ?></td>
                </tr>
            <?php }
            $style = $k + 1 % 2 ? '#efefef!important' : ''; ?>
            <tr>
                <td style="background:<?php echo $style; ?>" class="text-left"><strong style="font-size: 15px;"><?php echo $results->dbtcrdHead; ?></strong></td>
                <td style="background:<?php echo $style; ?>" class="text-right">0.00</td>
                <td style="background:<?php echo $style; ?>" class="text-right"><?php echo number_format($Debit, 2); ?></td>

            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th class="text-right"><?php echo get_phrases(['total']); ?></th>
                <th class="text-right"><?php echo number_format($Debit, 2); ?></th>
                <th class="text-right"><?php echo number_format($Debit, 2); ?></th>
            </tr>
            <tr class="<?php echo $k % 1 ? 'cv' : '' ?>">

                <th style="background:<?php echo $style; ?>" class="" colspan="3"><?php echo get_phrases(['in', 'words']); ?> : <?php echo $numberToword->AmountInWords($Debit); ?></th>

            </tr>
            <tr>


                <th class="" colspan="3"><?php echo get_phrases(['remarks']); ?> : <?php echo $results->Narration; ?></th>
            </tr>
        </tfoot>
    </table>
    <div class="form-group row mt-5">
        <label for="name" class="col-sm-3 col-form-label text-center">
            <div class="border-top"><?php echo get_phrases(['prepared', 'by']) ?></div>
        </label>
        <label for="name" class="col-sm-3 col-form-label text-center">
            <div class="border-top"><?php echo get_phrases(['checked', 'by']) ?></div>
        </label>
        <label for="name" class="col-sm-3 col-form-label text-center">
            <div class="border-top"><?php echo get_phrases(['authorised', 'by']) ?></div>
        </label>
        <label for="name" class="col-sm-3 col-form-label text-center">
            <div class="border-top"><?php echo get_phrases(['pay', 'by']) ?></div>
        </label>

    </div>
</div>
    
</div>