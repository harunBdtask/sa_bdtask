<script type="text/javascript">
function printDiv() {
    var divName = "printArea";
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    // document.body.style.marginTop="-45px";
    window.print();
    document.body.innerHTML = originalContents;
}
</script>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="btn-group"> 
                    <a href="<?= base_url('accounts/accounts/user_reports')?>" class="btn btn-primary"> <i class="fa fa-list"></i>  <?php echo makeString(['user', 'wise', 'reports']) ?> </a>  
                </div>
            </div>
        <div class="panel-body" id="printArea">
            <div class="row">
                <div class="col-xs-6 logo_bar">
                    <img src="<?php echo base_url("$website->logo") ?>" class="img-responsive" alt=""></br>
                    <?php echo display('phone') ?>: <?php echo $website->phone; ?></br>
                    <?php echo display('email') ?>: <?php echo $website->email; ?>
                    <br>
                </div>
                <div class="col-xs-6 address_bar">
                    <div class="address_inner">
                        <address>
                            <strong><?php echo display('address'); ?></strong><br>
                            <strong><?php echo $website->title; ?></strong><br>
                            <?php echo $website->description; ?>
                        </address>
                    </div>
                </div>
            </div> <hr>

            <table width="100%" class="table table-bordered" cellpadding="5" cellspacing="5" > 

                <thead>
                <tr align="center">

                    <td colspan="7"><font size="+1" style="font-family:'Arial'"> <strong><?php echo makeString(['reports', 'of']).' '.$user->name.'['.$user->employee_id.']';?></strong></font><strong></th></strong>
                </tr>

                <tr>
                    <td height="25"><strong><?php echo display('voucher_no');?></strong></td>
                    <td><strong><?php echo display('transaction_date');?></strong></td>
                    <td><strong><?php echo makeString(['head', 'name']);?></strong></td>
                    <td><strong><?php echo display('particulars')?></strong></td>
                    <td align="right"><strong><?php echo display('debit');?></strong></td>
                    <td align="right"><strong><?php echo display('credit');?></strong></td>
                    <td align="right"><strong><?php echo display('balance');?></strong></td>
                </tr>
                </thead>
                <tbody>

                <?php
                if(!empty($reports)){
                $TotalCredit=0;
                $TotalDebit=0;
                $CurBalance =0;
                foreach($reports as $key=>$data) {
                    ?>
                    <tr>
                        <td height="25"><?php echo $data->VNo;?></td>
                        <td><?php echo $data->CreateDate; ?></td>
                        <td><?php echo $data->HeadName; ?></td>
                        <td><?php echo $data->Narration; ?></td>
                        <td align="right"><?php echo  number_format($data->Debit,2,'.',','); ?></td>
                        <td align="right"><?php echo  number_format($data->Credit,2,'.',','); ?></td>
                        <?php
                        $TotalDebit += $data->Debit;
                        $CurBalance += $data->Debit;

                        $TotalCredit += $data->Credit;
                        $CurBalance -= $data->Credit;
                        ?>
                        <td align="right"><?php echo  number_format($CurBalance,2,'.',','); ?></td>
                        
                    </tr>
                <?php } ?>

                <tfoot>
                <tr class="table_data">
                    <td colspan="4" align="right"><strong><?php echo display('total')?></strong></td>                    
                    <td align="right"><strong><?php echo number_format($TotalDebit,2,'.',','); ?></strong></td>
                    <td align="right"><strong><?php echo number_format($TotalCredit,2,'.',','); ?></strong></td>
                    <td align="right"><strong><?php echo number_format($CurBalance,2,'.',','); ?></strong></td>
                </tr>
                </tfoot>
                <?php
                }else{
                ?>
                    <tr>
                        <td height="25"></td>
                        <td></td>
                        <td><?php echo display('no_report')?>.</td>
                        <td></td>
                        <td align="right"></td>
                        <td align="right"></td>
                        <td align="right"></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
            <div class="text-center" id="print" style="margin: 20px">
                <input type="button" class="btn btn-warning" name="btnPrint" id="btnPrint" value="Print" onclick="printDiv();"/>
                
            </div>
        </div>
    </div></div>