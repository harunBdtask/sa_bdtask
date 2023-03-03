<?php $vmodel = new \App\Modules\Account\Models\Bdtaskt1m8VoucherModel();?>
<div class="row">
  <div class="col-md-6 col-sm-12">
 
    <div class="form-group">
      <label class="font-weight-600 mb-0">
        <?php echo get_phrases(['debit', 'account', 'head']); ?>
        <i class="text-danger">*
        </i>
      </label>
      <select name="credit_head" class="form-control custom-select select2" id="editcredit_head" required>
            <option value="">Select Head</option>
        <?php if ($dbtcrthead) {
foreach ($dbtcrthead as $dbtcrt) { ?>
        <option value="<?php echo $dbtcrt->id ?>" <?php echo ($results->RevCodde == $dbtcrt->id ?'selected':'')?>>
          <?php echo $dbtcrt->text ?>
        </option>
        <?php }
} ?>
      </select>
    </div>
  </div>
  <div class="col-md-6 col-sm-6">
    <div class="form-group">
      <label class="font-weight-600 mb-0">
        <?php echo get_phrases(['voucher', 'date']); ?>
      </label>
      <input type="text" name="voucher_date" id="voucher_date" class="form-control" value="<?php echo $results->VDate; ?>" readonly="">
      <input type="hidden" name="voucher_no" id="voucher_no" class="form-control" readonly="" value="<?php echo $results->VNo; ?>">
      <input type="hidden" name="createdby" id="createdby" class="form-control" readonly="" value="<?php echo $results->CreateBy; ?>">
      <input type="hidden" name="createddate" id="createddate" class="form-control" readonly="" value="<?php echo $results->CreateDate; ?>">
</div>
  </div>
  </div>
  <!--  <div class="col-md-3 col-sm-12">
<div class="form-group">
<label class="font-weight-600 mb-0"><?php echo get_phrases(['voucher', 'no']); ?></label>
<input type="text" name="voucher_no" id="voucher_no" class="form-control" readonly="" value="<?php echo $results->VNo; ?>">
</div>
</div> -->
</div>
<div class="row">
 
  <div class="col-md-12 col-sm-12">
  
    <div class="row mb-2">
      <div class="col-md-12 col-sm-12">
        <label class="font-weight-600 mb-0">
          <?php echo get_phrases(['remarks']); ?>
        </label>
        <textarea name="remarks" class="form-control" id="remarks" rows="2"><?php echo $results->Narration; ?></textarea>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-stripped table-sm">
        <thead>
          <tr>
            <th class="text-center" width="45%">
              <?php echo get_phrases(['credit', 'head']); ?>
              <i class="text-danger">*
              </i>
            </th>
            <th class="text-center" width="20%">
              <?php echo get_phrases(['sub', 'code']); ?>
            </th>
            <th class="text-center" width="20%">
              <?php echo get_phrases(['ledger', 'comments']); ?>
            </th>
            <th class="text-center" width="25%">
              <?php echo get_phrases(['amount']); ?>
            </th>
            <th class="text-center" width="10%">
            </th>
          </tr>
        </thead>
        <tbody id="edited_appenddiv">
          <?php
$Debit = 0;
$Credit = 0;
$sl = 1;
if (!empty($results)) {
foreach ($results->details as $row) {
$Debit += $row->Credit;
// $Credit += $row->credit;
$sub_type_list = $vmodel->bdtaskt1m8_04_getTransAccHead_subtypes($row->subType); 
?>
          <tr>
            <td>
              <select name="account_name[]" id="editaccount_name_<?php echo $sl ?>" class="custom-select form-control select2 editaccount_name" required="required">
                <?php if($all_trhead_dbt){
                  foreach($all_trhead_dbt as $tranhead){?>
                <option value="<?php echo $tranhead->id;?>" <?php echo ($tranhead->id == $row->COAID ?'selected':'');?>><?php echo $tranhead->text;?></option>
                  <?php }}?>
              </select>
              <input type="hidden" name="head_code[]" class="form-control edithead_code" value="<?php echo $row->COAID;?>" readonly>
              <input type="hidden" name="id[]" class="form-control edithead_code" value="<?php echo $row->id;?>" readonly>
              <input type="hidden" name="sub_type[]" value="<?php echo $row->subType ;?>" id="edit_sub_type_<?php echo $sl ?>" class="form-control sub_type" readonly>
            </td>
            <td>
              <select name="sub_code[]" id="editsub_code_<?php echo $sl ?>" class="custom-select form-control sub_code select2">
              <option value="">Select SubHead</option>
              <?php if($sub_type_list){
                foreach($sub_type_list as $subtypes){
                ?>
                <option value="<?php echo $subtypes->id?>" <?php echo ($subtypes->id == $row->subCode ? 'selected':'')?>><?php echo $subtypes->name?></option>
                <?php }}?>
              </select>
            </td>
            <td>
              <input type="text" name="ledger_comments[]" class="form-control" value="<?php echo $row->ledgerComment ;?>"  autocomplete="off">
            </td>
            <td>
              <input type="text" name="amount[]" class="form-control editamount onlyNumber text-right" autocomplete="off" onkeyup="edittotalCalcuation()" value="<?php echo $row->Credit ;?>" required>
            </td>
            <td>
              <a href="javascript:void(0);" class="btn btn-danger-soft btn-sm removeBtn text-center">
                <i class="far fa-trash-alt fs-22">
                </i>
              </a>
            </td>
          </tr>
          <?php $sl++;
}
} ?>
        </tbody>
        <tfoot>
          <tr>
            <td>
            </td>
            <td>
            </td>
            <td class="text-right mt-2">
              <label class="font-weight-600 mb-0">
                <?php echo get_phrases(['total']); ?>
              </label>
            </td>
            <td>
              <input type="text" id="edittotal_amount" class="form-control text-right" readonly="" value="<?php echo number_format($Debit,2)?>">
            </td>
            <td>
              <button type="button" class="btn btn-success btn-sm eaddMore">
                <i class="fa fa-plus">
                </i>
              </button>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
    <div class="col-sm-12 text-right">
    <button type="submit" class="btn btn-success"><?php echo get_phrases(['update', 'voucher']); ?></button>
  
  </div>
 
  </div>
 
</script>
<script src="<?php echo base_url() ?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript">
</script>
<script type="text/javascript">
  $(document).ready(function() {
    "use strict";
    $(".select2").select2();
    function loadFirstRow(id) {
      var addHTML = '<tr>' +
          '<td><select name="account_name[]" id="editaccount_name_' + id + '" class="custom-select form-control editaccount_name" required="required"></select><input type="hidden" name="head_code[]" class="form-control edithead_code" readonly><input type="hidden" name="sub_type[]" id="edit_sub_type_' + id + '" class="form-control sub_type" readonly></td>' +
          '<td><select name="sub_code[]" id="editsub_code_' + id + '" class="custom-select form-control sub_code"></select></td>' +
          '<td><input type="text" name="ledger_comments[]" class="form-control " autocomplete="off" ></td>' +
          '<td><input type="text" name="amount[]" class="form-control editamount onlyNumber text-right" autocomplete="off" onkeyup="edittotalCalcuation()" required></td>' +
          '<td><a href="javascript:void(0);" class="btn btn-danger-soft btn-sm removeBtn text-center"><i class="far fa-trash-alt fs-22"></i></a></td>' +
          '</tr>';
      $('#edited_appenddiv').append(addHTML);
      // search account
      $('#editaccount_name_' + id).select2({
        placeholder: '<?php echo get_phrases(['select', 'debit', 'account']); ?>',
        minimumInputLength: 1,
        ajax: {
        url: _baseURL + 'account/vouchers/searchTransactionAcc',
        dataType: 'json',
        delay: 250,
        processResults: function(data) {
        return {
        results: $.map(data, function(item) {
        return {
        text: item.text,
        id: item.id
      }
                                       }
                                      )
    };
  }
                    ,
                    cache: true
                    }
                    }
                   );
  }
 // var countPayId = 2;

  $('body').on('click', '.eaddMore', function() {
    var countPayId = $("#edited_appenddiv >tr").length;
    var tr_length = (countPayId?countPayId:0) + 1 + "<?php echo rand(0,9)?>";
    loadFirstRow(tr_length);
  
  }
              );
  $('body').on('click', '.removeBtn', function() {
    var rowCount = $('#edited_appenddiv >tr').length;
    if (rowCount > 1) {
      $(this).parent().parent().remove();
    }
    else {
      alert("There only one row you can't delete.");
    }
  }
              );
  $('body').on('change', '.editaccount_name', function(e) {
    e.preventDefault();
    var id = $(this).val();
    $(this).parent().parent().find(".edithead_code").val(id);
    var nameid = $(this).attr('id');
    var splitid = nameid.split("_");
    $.ajax({
      type: 'POST',
      url: _baseURL + 'account/vouchers/searchSubcode',
      dataType: 'json',
      data: {
        'csrf_stream_name': csrf_val,
        headcode: id
      }
      ,
    }
          ).done(function(data) {
      var listdata = JSON.stringify(data.list);
      var sub_type = data.subtype;
      $("#edit_sub_type_" + splitid[2]).val(sub_type);
      if (listdata == '[]') {
        $("#editsub_code_" + splitid[2]).empty();
      }
      else {
        $("#editsub_code_" + splitid[2]).select2({
          placeholder: 'Select Subcode',
          data: data.list
        }
                                            );
        $("#editsub_code_" + splitid[2]).val('').trigger('change');
      }
    }
                );
  }
              );
  }
  );
  function edittotalCalcuation() {
    var gr_tot = 0;
    $(".editamount").each(function() {
      isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
    }
                     );
    $("#edittotal_amount").val(gr_tot.toFixed(2, 2));
  }
</script>
