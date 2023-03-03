<link href="<?php echo base_url() ?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <nav aria-label="breadcrumb" class="order-sm-last p-0">
                            <ol class="breadcrumb d-inline-flex font-weight-600 fs-17 bg-white mb-0 float-sm-left p-0">
                                <li class="breadcrumb-item"><a href="#"><?php echo $moduleTitle; ?></a></li>
                                <li class="breadcrumb-item active"><?php echo $title; ?></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="text-right">
                        <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i>Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php echo form_open('account/reports/balanceSheetForm', array('class' => '', 'method' => 'post')) ?>
                <div class="row">

                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <input type="text" name="dtpFromDate" value="<?php echo $dtpFromDate ?>" placeholder="<?php echo get_phrases(['from', 'date']) ?>" class="datepickerui form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <input type="text" name="dtpToDate" value="<?php echo $dtpToDate ?>" placeholder="<?php echo get_phrases(['to', 'date']) ?>" class="datepickerui form-control" autocomplete="off">
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success rounded-pill mt-1"><?php echo get_phrases(['filter']); ?></button>
                        </div>
                    </div>
                </div>

                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card">

            <div id="printArea">
                <div class="card-body">
                    <p style="font-size:10px"><i>Print Date: <?php echo date('Y-m-d h:i:s')?></i></p>
                    <div class="text-center mb-4">
                        <h4> <strong><?php echo get_phrases(['balance', 'sheet']) . '  on ' . date('d-m-Y', strtotime($dtpFromDate)) . ' To '  . date('d-m-Y', strtotime($dtpToDate)); ?></strong>
                        </h4>
                    </div>
                    <table width="99%" align="left" class="datatableReport table table-bordered general_ledger_report_tble">


                        <thead>
                            <tr>
                                <td width="40%" style="background: #abbff9!important"><strong><?php echo get_phrases(['particulars']) ?></strong></td>
                                <td width="15%" style="background: #abbff9!important" align="right" class="profitamount"><strong><?php echo $active_fyear->yearName ?></strong></th>
                                    <?php foreach ($financialyears as $financialyear) { ?>
                                <td width="15%" style="background: #abbff9!important" align="right" class="profitamount"><strong><?php echo $financialyear; ?></strong></td>
                            <?php } ?>

                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $k = 0;
                            $pt = 2;
                            foreach ($assets as $asset) {
                                $k++;
                                $style = $pt % 2 ? '#efefef!important' : '';
                            ?>
                                <tr>
                                    <td align="left" style="background:<?php echo $style?>"><?php echo $asset['head']; ?></td>
                                    <td align="right" style="background:<?php echo $style?>" colspan="<?php echo 1 + $count_previous_fyear ?>"></td>
                                </tr>
                                <?php
                                if (count($asset['nextlevel']) > 0) {
                                    foreach ($asset['nextlevel'] as  $value) {
                                        $pt += 1;
                                        $style2 = $pt % 2 ? '#efefef!important' : '';
                                ?>
                                        <tr class="cv">
                                            <td style="background:<?php echo $style2; ?>" align="left" style="padding-left: 80px;"><?php echo $value['headName']; ?> </td>
                                            <td style="background:<?php echo $style2; ?>" align="right" class="profitamount"><?php echo number_format($value['subtotal'], 2); ?></td>
                                            <?php if ($count_previous_fyear == 3) { ?>
                                                <td style="background:<?php echo $style2; ?>" align="right" class="profitamount"><?php echo number_format($value['ssubtotal'], 2); ?></td>
                                                <td style="background:<?php echo $style2; ?>" align="right" class="profitamount"><?php echo number_format($value['tsubtotal'], 2); ?></td>
                                                <td style="background:<?php echo $style2; ?>" align="right" class="profitamount"><?php echo number_format($value['fsubtotal'], 2); ?></td>
                                            <?php } else if ($count_previous_fyear == 2) { ?>
                                                <td style="background:<?php echo $style2; ?>" align="right" class="profitamount"><?php echo number_format($value['ssubtotal'], 2); ?></td>
                                                <td style="background:<?php echo $style2; ?>" align="right" class="profitamount"><?php echo number_format($value['tsubtotal'], 2); ?></td>

                                            <?php } elseif ($count_previous_fyear == 1) { ?>
                                                <td style="background:<?php echo $style2; ?>" align="right" class="profitamount"><?php echo number_format($value['ssubtotal'], 2); ?></td>

                                            <?php } ?>

                                        </tr>
                                        <?php
                                        $j = $k + 1;
                                        if (count($value['innerHead']) > 0) {

                                            foreach ($value['innerHead'] as $inner) {
                                                $pt += 1;
                                                $style2 = $pt % 2 ? '#efefef!important' : '';

                                        ?>
                                                <tr>
                                                    <td align="left" style="padding-left: 160px;background:<?php echo $style2; ?>"><?php echo $inner['headName']; ?></td>
                                                    <td style="background:<?php echo $style2; ?>" align="right" class="profitamount"><?php echo number_format($inner['amount'], 2); ?></td>
                                                    <?php if ($count_previous_fyear == 3) { ?>
                                                        <td style="background:<?php echo $style2; ?>" align="right" class="profitamount"><?php echo number_format($inner['secondyear'], 2); ?></td>
                                                        <td style="background:<?php echo $style2; ?>" align="right" class="profitamount"><?php echo number_format($inner['thirdyear'], 2); ?></td>
                                                        <td style="background:<?php echo $style2; ?>" align="right" class="profitamount"><?php echo number_format($inner['fourthyear'], 2); ?></td>
                                                    <?php } elseif ($count_previous_fyear == 2) { ?>
                                                        <td style="background:<?php echo $style2; ?>" align="right" class="profitamount"><?php echo number_format($inner['secondyear'], 2); ?></td>
                                                        <td style="background:<?php echo $style2; ?>" align="right" class="profitamount"><?php echo number_format($inner['thirdyear'], 2); ?></td>
                                                    <?php } elseif ($count_previous_fyear == 1) { ?>
                                                        <td style="background:<?php echo $style2; ?>" align="right" class="profitamount"><?php echo number_format($inner['secondyear'], 2); ?></td>
                                                    <?php } ?>

                                                </tr>
                            <?php }
                                        }
                                    }
                                }
                                $pt++;} ?>
                                <?php  $pt += 1;
                                        $asfootstyle = $pt % 2 ? '#efefef!important' : '';?>

                            <tr>
                                <td align="right" style="background: <?php echo $asfootstyle?> ;"><strong><?php echo get_phrases(['total', 'assets']); ?></strong></td>
                                <td align="right" class="profitamount" style="background: <?php echo $asfootstyle?> ;"><strong><?php echo number_format($assets[0]['gtotal'], 2); ?></strong></td>
                                <?php if ($count_previous_fyear == 3) { ?>
                                    <td align="right" class="profitamount" style="background: <?php echo $asfootstyle?> ;"><strong><?php echo number_format($assets[0]['sgtotal'], 2); ?></strong></td>
                                    <td align="right" class="profitamount" style="background: <?php echo $asfootstyle?> ;"><strong><?php echo number_format($assets[0]['tgtotal'], 2); ?></strong></td>
                                    <td align="right" class="profitamount" style="background: <?php echo $asfootstyle?> ;"><strong><?php echo number_format($assets[0]['fgtotal'], 2); ?></strong></td>
                                <?php } elseif ($count_previous_fyear == 2) { ?>
                                    <td align="right" class="profitamount" style="background: <?php echo $asfootstyle?> ;"><strong><?php echo number_format($assets[0]['sgtotal'], 2); ?></strong></td>
                                    <td align="right" class="profitamount" style="background: <?php echo $asfootstyle?> ;"><strong><?php echo number_format($assets[0]['tgtotal'], 2); ?></strong></td>
                                <?php } elseif ($count_previous_fyear == 1) { ?>
                                    <td align="right" class="profitamount" style="background: <?php echo $asfootstyle?> ;"><strong><?php echo number_format($assets[0]['sgtotal'], 2); ?></strong></td>
                                <?php } ?>

                            </tr>






                            <tr bgcolor="#abbff9">
                                <td colspan="<?php echo 2 + $count_previous_fyear ?>"> &nbsp;</td>
                            </tr>

                            <?php 
                            $lt = $pt;
                            foreach ($liabilities as $liability) { $lt++;
                                $lbstyle1 = $lt % 2 ? '#efefef!important' : '';
                                ?>
                                <tr>
                                    <td align="left" style="background:<?php echo $lbstyle1?>"><?php echo $liability['head']; ?></td>
                                    <td align="right" style="background:<?php echo $lbstyle1?>" colspan="<?php echo 1 + $count_previous_fyear ?>"></td>
                                </tr>
                                <?php if (count($liability['nextlevel']) > 0) {
                                    foreach ($liability['nextlevel'] as  $value) { 
                                        $lt +=1;
                                        $lbstyle2 = $lt % 2 ? '#efefef!important' : '';
                                        ?>
                                        <tr>
                                            <td align="left" style="padding-left: 80px;background:<?php echo $lbstyle2?>"><?php echo $value['headName']; ?></td>
                                            <td align="right" class="profitamount" style="background:<?php echo $lbstyle2?>"><?php echo number_format($value['subtotal'], 2); ?></td>
                                            <?php if ($count_previous_fyear == 3) { ?>
                                                <td align="right" class="profitamount" style="background:<?php echo $lbstyle2?>"><?php echo number_format($value['ssubtotal'], 2); ?></td>
                                                <td align="right" class="profitamount" style="background:<?php echo $lbstyle2?>"><?php echo number_format($value['tsubtotal'], 2); ?></td>
                                                <td align="right" class="profitamount" style="background:<?php echo $lbstyle2?>"><?php echo number_format($value['fsubtotal'], 2); ?></td>
                                            <?php } elseif ($count_previous_fyear == 2) { ?>
                                                <td align="right" class="profitamount" style="background:<?php echo $lbstyle2?>"><?php echo number_format($value['ssubtotal'], 2); ?></td>
                                                <td align="right" class="profitamount" style="background:<?php echo $lbstyle2?>"><?php echo number_format($value['tsubtotal'], 2); ?></td>
                                            <?php } elseif ($count_previous_fyear == 1) { ?>
                                                <td align="right" class="profitamount" style="background:<?php echo $lbstyle2?>"><?php echo number_format($value['ssubtotal'], 2); ?></td>

                                            <?php } ?>

                                        </tr>
                                        <?php if (count($value['innerHead']) > 0) {
                                            foreach ($value['innerHead'] as $inner) {
                                                $lt +=1;
                                        $lbstyle3 = $lt % 2 ? '#efefef!important' : '';
                                                 ?>
                                                <tr>
                                                    <td align="left" style="padding-left: 160px;background:<?php echo $lbstyle3?>"><?php echo $inner['headName']; ?></td>
                                                    <td align="right" class="profitamount" style="background:<?php echo $lbstyle3?>"><?php echo number_format($inner['amount'], 2); ?></td>
                                                    <?php if ($count_previous_fyear == 3) { ?>
                                                        <td align="right" class="profitamount" style="background:<?php echo $lbstyle3?>"><?php echo number_format($inner['secondyear'], 2); ?></td>
                                                        <td align="right" class="profitamount" style="background:<?php echo $lbstyle3?>"><?php echo number_format($inner['thirdyear'], 2); ?></td>
                                                        <td align="right" class="profitamount" style="background:<?php echo $lbstyle3?>"><?php echo number_format($inner['fourthyear'], 2); ?></td>
                                                    <?php } elseif ($count_previous_fyear == 2) { ?>
                                                        <td align="right" class="profitamount" style="background:<?php echo $lbstyle3?>"><?php echo number_format($inner['secondyear'], 2); ?></td>
                                                        <td align="right" class="profitamount" style="background:<?php echo $lbstyle3?>"><?php echo number_format($inner['thirdyear'], 2); ?></td>
                                                    <?php } elseif ($count_previous_fyear == 1) { ?>
                                                        <td align="right" class="profitamount" style="background:<?php echo $lbstyle3?>"><?php echo number_format($inner['secondyear'], 2); ?></td>

                                                    <?php } ?>

                                                </tr>
                            <?php }
                                        }
                                    }
                                }
                                 } ?>
                         <?php     $lt +=1;
                                        $lbstylefoot = $lt % 2 ? '#efefef!important' : '';?>
                            <tr>
                                <td align="right" style="background:<?php echo $lbstylefoot?>"><strong><?php echo get_phrases(['total', 'liabilities']); ?></strong></td>
                                <td align="right" class="profitamount" style="background:<?php echo $lbstylefoot?>"><strong><?php echo number_format($liabilities[0]['gtotal'], 2); ?></strong></td>
                                <?php if ($count_previous_fyear == 3) { ?>
                                    <td align="right" class="profitamount" style="background:<?php echo $lbstylefoot?>"><strong><?php echo number_format($liabilities[0]['sgtotal'], 2); ?></strong></td>
                                    <td align="right" class="profitamount" style="background:<?php echo $lbstylefoot?>"><strong><?php echo number_format($liabilities[0]['tgtotal'], 2); ?></strong></td>
                                    <td align="right" class="profitamount" style="background:<?php echo $lbstylefoot?>"><strong><?php echo number_format($liabilities[0]['fgtotal'], 2); ?></strong></td>
                                <?php } elseif ($count_previous_fyear == 2) { ?>
                                    <td align="right" class="profitamount" style="background:<?php echo $lbstylefoot?>"><strong><?php echo number_format($liabilities[0]['sgtotal'], 2); ?></strong></td>
                                    <td align="right" class="profitamount" style="background:<?php echo $lbstylefoot?>"><strong><?php echo number_format($liabilities[0]['tgtotal'], 2); ?></strong></td>
                                <?php } elseif ($count_previous_fyear == 1) { ?>
                                    <td align="right" class="profitamount" style="background:<?php echo $lbstylefoot?>"><strong><?php echo number_format($liabilities[0]['sgtotal'], 2); ?></strong></td>

                                <?php } ?>

                            </tr>



                            <tr bgcolor="#abbff9">
                                <td colspan="<?php echo 2 + $count_previous_fyear ?>"> &nbsp;</td>
                            </tr>

                            <?php  $eqlt = $lt;
                             foreach ($equitys as $equity) {  $eqlt++;
                                $eqstyle = $eqlt % 2 ? '#efefef!important' : '';?>
                                <tr>
                                    <td align="left" style="background:<?php echo $eqstyle?>"><?php echo $equity['head']; ?></td>
                                    <td align="right" style="background:<?php echo $eqstyle?>" colspan="<?php echo 1 + $count_previous_fyear ?>"></td>
                                </tr>
                                <?php if (count($equity['nextlevel']) > 0) {
                                    foreach ($equity['nextlevel'] as  $value) { 
                                        $eqlt+=1;
                                     $eqstyle = $eqlt % 2 ? '#efefef!important' : '';
                                        ?>
                                        <tr>
                                            <td align="left" style="padding-left: 80px;background:<?php echo $eqstyle?>"><?php echo $value['headName']; ?></td>
                                            <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><?php echo number_format($value['subtotal'], 2); ?></td>
                                            <?php if ($count_previous_fyear == 3) { ?>
                                                <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><?php echo number_format($value['ssubtotal'], 2); ?></td>
                                                <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><?php echo number_format($value['tsubtotal'], 2); ?></td>
                                                <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><?php echo number_format($value['fsubtotal'], 2); ?></td>
                                            <?php } elseif ($count_previous_fyear == 2) { ?>
                                                <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><?php echo number_format($value['ssubtotal'], 2); ?></td>
                                                <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><?php echo number_format($value['tsubtotal'], 2); ?></td>
                                            <?php } elseif ($count_previous_fyear == 1) { ?>
                                                <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><?php echo number_format($value['ssubtotal'], 2); ?></td>
                                            <?php } ?>

                                        </tr>
                                        <?php if (count($value['innerHead']) > 0) {
                                             
                                            foreach ($value['innerHead'] as $inner) { 
                                                $eqlt+=1;
                                             $eqstyle = $eqlt % 2 ? '#efefef!important' : '';
                                                ?>
                                                <tr>
                                                    <td align="left" style="padding-left: 160px;background:<?php echo $eqstyle?>"><?php echo $inner['headName']; ?></td>
                                                    <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><?php echo number_format($inner['amount'], 2); ?></td>
                                                    <?php if ($count_previous_fyear == 3) { ?>
                                                        <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><?php echo number_format($inner['secondyear'], 2); ?></td>
                                                        <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><?php echo number_format($inner['thirdyear'], 2); ?></td>
                                                        <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><?php echo number_format($inner['fourthyear'], 2); ?></td>
                                                    <?php } elseif ($count_previous_fyear == 2) { ?>
                                                        <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><?php echo number_format($inner['secondyear'], 2); ?></td>
                                                        <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><?php echo number_format($inner['thirdyear'], 2); ?></td>
                                                    <?php } elseif ($count_previous_fyear == 1) { ?>
                                                        <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><?php echo number_format($inner['secondyear'], 2); ?></td>

                                                    <?php } ?>

                                                </tr>
                            <?php }
                                        }
                                    }
                                }
                            } ?>
                          <?php  $eqlt+=1;
                                $eqstyle = $eqlt % 2 ? '#efefef!important' : '';?>
                            <tr>
                                <td align="right" style="background:<?php echo $eqstyle?>"><strong><?php echo get_phrases(['total', 'equity']); ?></strong></td>
                                <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><strong><?php echo number_format($equitys[0]['gtotal'], 2); ?></strong></td>
                                <?php if ($count_previous_fyear == 3) { ?>
                                    <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><strong><?php echo number_format($equitys[0]['sgtotal'], 2); ?></strong></td>
                                    <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><strong><?php echo number_format($equitys[0]['tgtotal'], 2); ?></strong></td>
                                    <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><strong><?php echo number_format($equitys[0]['fgtotal'], 2); ?></strong></td>
                                <?php } elseif ($count_previous_fyear == 2) { ?>
                                    <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><strong><?php echo number_format($equitys[0]['sgtotal'], 2); ?></strong></td>
                                    <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><strong><?php echo number_format($equitys[0]['tgtotal'], 2); ?></strong></td>
                                <?php } elseif ($count_previous_fyear == 1) { ?>
                                    <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><strong><?php echo number_format($equitys[0]['sgtotal'], 2); ?></strong></td>

                                <?php } ?>

                            </tr>



                        </tbody>
                        <tfoot>
                        <?php  $eqlt+=1;
                                $eqstyle = $eqlt % 2 ? '#efefef!important' : '';?>
                            <tr>
                                <td align="right" style="background:<?php echo $eqstyle?>"><strong><?php echo get_phrases(['total', 'liabilities', 'equity']); ?></strong></td>
                                <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><strong><?php echo number_format(($liabilities[0]['gtotal'] + $equitys[0]['gtotal']), 2); ?></strong></td>
                                <?php if ($count_previous_fyear == 3) { ?>
                                    <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><strong><?php echo number_format(($liabilities[0]['sgtotal'] + $equitys[0]['sgtotal']), 2); ?></strong></td>
                                    <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><strong><?php echo number_format(($liabilities[0]['tgtotal'] + $equitys[0]['tgtotal']), 2); ?></strong></td>
                                    <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><strong><?php echo number_format(($liabilities[0]['fgtotal'] + $equitys[0]['fgtotal']), 2); ?></strong></td>
                                <?php } elseif ($count_previous_fyear == 2) { ?>
                                    <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><strong><?php echo number_format(($liabilities[0]['sgtotal'] + $equitys[0]['sgtotal']), 2); ?></strong></td>
                                    <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><strong><?php echo number_format(($liabilities[0]['tgtotal'] + $equitys[0]['tgtotal']), 2); ?></strong></td>
                                <?php } elseif ($count_previous_fyear == 1) { ?>
                                    <td align="right" class="profitamount" style="background:<?php echo $eqstyle?>"><strong><?php echo number_format(($liabilities[0]['sgtotal'] + $equitys[0]['sgtotal']), 2); ?></strong></td>

                                <?php } ?>
                            </tr>
                        </tfoot>


                    </table>
                </div>
            </div>
            <div class="text-center pt-4 pb-3">
                <input type="button" class="btn btn-warning" name="btnPrint" id="btnPrint" value="Print" onclick="printContent('printArea');" />
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url() ?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        "use strict";
        $('.datepickerui').datepicker({
            dateFormat: 'yy-mm-dd'
        });
        // $('tr:nth-child(even)>td').css('background', '#efefef');

        // $('tr:nth-child(even)').addClass('cv');
    });
</script>