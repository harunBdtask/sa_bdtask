<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th><?php echo get_phrases(['head', 'code']);?></th>
                <th><?php echo get_phrases(['account', 'name']);?></th>
                <th class="text-right"><?php echo get_phrases(['debit']);?></th>
                <th class="text-right"><?php echo get_phrases(['credit']);?></th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $debit = 0;
            $credit = 0;
            if(!empty($results)){
                foreach ($results as  $row) { 
                    $debit += $row->Debit;
                    $credit += $row->Credit;
                    ?>
                    <tr>
                        <td><?php echo esc($row->HeadCode);?></td>
                        <td><?php echo esc($row->HeadName);?></td>
                        <td class="text-right"><?php echo number_format(esc($row->Debit), 2);?></td>
                        <td class="text-right"><?php echo number_format(esc($row->Credit), 2);?></td>
                    </tr>
            <?php } }?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-right"><?php echo get_phrases(['total', 'amount']);?></th>
                <th class="text-right"><?php echo number_format($debit, 2); ?></th>
                <th class="text-right"><?php echo number_format($credit, 2); ?></th>
            </tr>
        </tfoot>
    </table>
</div>