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
<?php
include ('Class/CConManager.php');
include ('Class/Ccommon.php');
include ('Class/CResult.php');
include ('Class/CAccount.php'); 
?>

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-body"  id="printArea">
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
                    <tr align="center">
                        <td id="ReportName" style="font:'Times New Roman', Times, serif; font-size:20px;"><b><?php echo display('chart_of_account')?></b></td>
                    </tr>
                    <div class="">
                        <table class="table table-bordered" cellpadding="0" cellspacing="0" width="100%" style="text-align: left" >
                            <?php
                            $oResult=new CResult();
                            $oAccount=new CAccount();
                            // get coa head of accounts
                            $sql="SELECT * FROM acc_coa WHERE IsActive=1 ORDER BY HeadCode";
                            $oResult=$oAccount->SqlQuery($sql);
                            // get maximum HeadLevel
                             $sql="SELECT MAX(HeadLevel) as MHL FROM acc_coa WHERE IsActive=1";
                                $oResultLevel=$oAccount->SqlQuery($sql);
                             $maxLevel=$oResultLevel->row['MHL'];
                            ?>

                            <tr>
                                <td colspan="<?php echo $maxLevel+2;?>"><center><?= display('head_of_account')?></center></td>
                                <td><?= display('credit')?></td>
                                <td><?= display('debit')?></td>

                            </tr>

                            <?php
                            for ($i = 0; $i < $oResult->num_rows; $i++)
                            {
                                // get total transaction debit && credit by headcode
                                $head = $oResult->rows[$i]['HeadCode'];
                                $transactions=$this->db->select('SUM(Credit) AS credit, SUM(Debit) AS debit')
                                                    ->from('acc_transaction')
                                                    ->where('COAID', $head)
                                                    ->group_by('COAID')
                                                    ->get()->row();

                                $HL=$oResult->rows[$i]['HeadLevel'];
                                $Level=$maxLevel+1;
                                $HL1=$Level-$HL;

                                echo '<tr>';
                                for($j=0; $j<$HL; $j++)
                                {
                                    echo '<td>&nbsp;</td>';
                                }
                                echo '<td>'.$oResult->rows[$i]['HeadCode'].'</td>';
                                echo '<td colspan='.$HL1.'>'.$oResult->rows[$i]['HeadName'].'</td>';
                                echo '<td>'.(!empty($transactions->credit)?number_format($transactions->credit, 2):number_format(0)).'</td>';
                                echo '<td>'.(!empty($transactions->debit)?number_format($transactions->debit, 2):number_format(0)).'</td>';
                                echo '</tr>';

                            }
                            ?>
                        </table>

                    </div>
                    <div class="text-center no-print" id="print" style="margin: 20px">
                        <input type="button" class="btn btn-warning" name="btnPrint" id="btnPrint" value="Print" onclick="printDiv();"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>