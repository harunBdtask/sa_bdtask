<link href="<?php echo base_url() ?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card ">
            <div class="card-header py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <nav aria-label="breadcrumb" class="order-sm-last p-0">
                            <ol class="breadcrumb d-inline-flex font-weight-600 fs-17 bg-white mb-0 float-sm-left p-0">
                                <li class="breadcrumb-item"><a href="#"><?php echo $moduleTitle;?></a></li>
                                <li class="breadcrumb-item active"><?php echo $title;?></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="text-right">
                        <a href="<?php echo previous_url(); ?>" class="btn btn-warning btn-sm mr-1 ml-2"><i
                                class="fas fa-angle-double-left mr-1"></i>Back</a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <?php echo form_open('account/reports/profitLossReoprtResult',array('class' => '','method'=>'post'))?>

                <div class="row">

                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <input type="text" name="dtpFromDate" value="<?php echo date('Y-m-01')?>"
                                placeholder="<?php echo get_phrases(['from','date']) ?>"
                                class="datepickerui form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <input type="text" name="dtpToDate" value="<?php echo date('Y-m-t')?>"
                                placeholder="<?php echo get_phrases(['to','date']) ?>" class="datepickerui form-control"
                                autocomplete="off">
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <div class="form-group">
                            <button type="submit"
                                class="btn btn-sm btn-success rounded-pill mt-1"><?php echo get_phrases(['filter']);?></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="allResult">

                    </div>
                </div>
            </div>
            <?php echo form_close()?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card ">

            <div id="printArea">
                <p style="font-size:10px"><i>Print Date: <?php echo date('Y-m-d h:i:s')?></i></p>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="text-center">
                            <b><?php echo get_phrases(['statement','of','comprehensive','income'])?><br /><?php echo get_phrases(['from'])?>
                                <?php echo $dtpFromDate ?> <?php echo get_phrases(['to'])?> <?php echo $dtpToDate;?></b>
                        </div>
                    </div>
                    <table class="table table-bordered" style="margin-bottom:50px;">


                        <thead>

                            <tr>
                                <th width="60%" style="background: #abbff9!important" align="center">
                                    <?php echo get_phrases(['particulars'])?></th>
                                <th width="20%" style="background: #abbff9!important" align="right" class="profitamount">
                                    <?php echo get_phrases(['amount'])?></th>
                                <th width="20%" style="background: #abbff9!important" align="right" class="profitamount">
                                    <?php echo get_phrases(['amount'])?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $pt = 1;$sl=1; foreach($incomes as $income) {$pt +=1; $style1 = $pt % 2 ? '#efefef!important' : '';?>
                            <tr>
                                <td align="left" style="background:<?php echo $style1;?>"><?php echo $income['head'];?></td>
                                <td align="right" style="background:<?php echo $style1;?>" colspan="2"></td>
                            </tr>
                            <?php   
                         if(count($income['nextlevel']) > 0) { 
                          foreach ($income['nextlevel'] as  $value) {$pt +=1; $style2 = $pt % 2 ? '#efefef!important' : '';  ?>
                            <tr>
                                <td align="left" style="padding-left: 80px;background:<?php echo $style2;?>">
                                    <?php echo $value['headName'];?></td>
                                <td align="right" class="profitamount" style="background:<?php echo $style2;?>"> </td>
                                <td align="right" class="profitamount" style="background:<?php echo $style2;?>">
                                    <?php echo number_format($value['subtotal'],2); ?></td>

                            </tr>
                            <?php  if(count($value['innerHead']) > 0) {   foreach($value['innerHead'] as $inner) { $pt +=1;$style3 = $pt % 2 ? '#efefef!important' : ''; ?>
                            <tr>
                                <td align="left" style="padding-left: 160px;background:<?php echo $style3;?>"><?php echo $inner['headName'];?></td>
                                <td align="right" style="background:<?php echo $style3;?>" class="profitamount"><?php echo number_format($inner['amount'],2); ?>
                                </td>
                                <td style="background:<?php echo $style3;?>"> </td>
                            </tr>
                            <?php } }  } } } ?>
                            <?php $pt +=1;$style4 = $pt % 2 ? '#efefef!important' : '';?>
                            <tr>
                                <td align="right" style="background:<?php echo $style4?>"><strong><?php echo get_phrases(['total']);?></strong></td>
                                <td align="right" style="background:<?php echo $style4?>" class="profitamount" colspan="2">
                                    <strong><?php echo number_format($incomes[0]['gtotal'] ,2); ?></strong></td>

                            </tr>





                           
                            <?php
                    $sle = $pt+1;
                    
                    $j = 1;
                     foreach($expenses as $expense) { $style1 = $sle % 2 ? '#efefef!important' : '';?>
                            <tr>
                                <td align="left" style="background: <?php echo $style1;?>"><?php echo $expense['head'];?></td>
                                <td align="right" style="background: <?php echo $style1;?>" colspan="2"></td>
                            </tr>
                            <?php $sle = $sle+1; if(count($expense['nextlevel']) > 0) { $i=1; foreach ($expense['nextlevel'] as  $value) { $sle = ($i > 1?($sle-1):$sle);
                            $style2 = $sle % 2 ? '#efefef!important' : '';
                                ?>
                            <tr>
                                <td align="left" style="padding-left: 80px;background: <?php echo $style2;?>">
                                    <?php echo $value['headName'];?></td>
                                <td align="right" style="background: <?php echo $style2;?>" class="profitamount"> &nbsp;</td>
                                <td align="right" style="background: <?php echo $style2;?>" class="profitamount">
                                    <?php echo number_format($value['subtotal'],2); ?></td>
                            </tr>
                            <?php $sle = $sle+1;  if(count($value['innerHead']) > 0) {   foreach($value['innerHead'] as $inner) { $j +=1;
                                $style3 = $sle % 2 ? '#efefef!important' : '';?>
                            <tr>
                                <td align="left" style="padding-left: 160px;background: <?php echo $style3;?>">
                                    <?php echo $inner['headName'];?></td>
                                <td align="right" class="profitamount" style="background: <?php echo $style3;?>"><?php echo number_format($inner['amount'],2); ?>
                                </td>
                                <td  style="background: <?php echo $style3;?>"> &nbsp; </td>
                            </tr>
                            <?php $sle++;} }  $sle++;$i++;} } $sle++;} ?>
                            <?php $sle = $sle + 2;$exfstyle = $sle % 2 ? '#efefef!important' : '';?>
                            <tr>
                                <td align="right" style="background:<?php echo $exfstyle?>"><strong><?php echo get_phrases(['total']);?></strong></td>
                                <td align="right" style="background:<?php echo $exfstyle?>" class="profitamount" colspan="2">
                                    <strong><?php echo number_format($expenses[0]['gtotal']  ,2); ?></strong></td>

                            </tr>




                            <tr>
                                <td align="right" style="background: #abbff9!important"><strong><?php
                           $statuscheck = ($incomes[0]['gtotal']?$incomes[0]['gtotal']:0) - ($expenses[0]['gtotal']?$expenses[0]['gtotal']:0);
                           $rev_status =($expenses[0]['gtotal']?$expenses[0]['gtotal']:0) -  ($incomes[0]['gtotal']?$incomes[0]['gtotal']:0);
                            echo get_phrases(['profit','loss']).($statuscheck > 0 ?'(Profit)':'(Loss)')?></strong></td>
                                <td align="right" style="background: #abbff9!important" class="profitlossassetstyle" colspan="2">
                                    <strong><?php echo ($statuscheck > 0?number_format($statuscheck,2):number_format($rev_status,2)); ?></strong>
                                </td>

                            </tr>


                        </tbody>



                    </table>


                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label text-center">
                            <div class="border-top"><?php echo get_phrases(['prepared','by'])?></div>
                        </label>
                        <label for="name" class="col-sm-3 col-form-label text-center">
                            <div class="border-top"><?php echo get_phrases(['accounts'])?></div>
                        </label>
                        <label for="name" class="col-sm-3 col-form-label text-center">
                            <div class="border-top"><?php echo get_phrases(['authorised','signature'])?></div>
                        </label>
                        <label for="name" class="col-sm-3 col-form-label text-center">
                            <div class="border-top"><?php echo get_phrases(['chairman'])?></div>
                        </label>

                    </div>

                </div>
            </div>
            <div class="text-center pt-4 pb-3">
                <input type="button" class="btn btn-warning" name="btnPrint" id="btnPrint" value="Print"
                    onclick="printContent('printArea');" />
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url() ?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
        "use strict";
        $('.datepickerui').datepicker({
            dateFormat: 'yy-mm-dd'
        });

    });
</script>