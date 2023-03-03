<?php $vmodel = new \App\Modules\Account\Models\Bdtaskt1m8VoucherModel();?>
<div class="row">
                    
                    <div class="col-md-6 col-sm-12">
                       <div class="form-group">
                            <label class="font-weight-600 mb-0"><?php echo get_phrases(['voucher', 'date']);?></label>
                            <input type="text" name="voucher_date" id="voucher_date" class="form-control" value="<?php echo $results->VDate; ?>" readonly="">
                        </div>
                    </div>
                   <div class="col-md-6 col-sm-12">
                   <label class="font-weight-600 mb-0"><?php echo get_phrases(['attach', 'file']);?></label>
                        <input type="file" name="attach_file" class="form-control" id="attach_file" accept=".png, .jpg, .jpeg, .pdf">
                        <input type="hidden" name="old_file" value="<?php echo $results->attachment; ?>">
                        <input type="hidden" name="voucher_no" value="<?php echo $results->VNo; ?>">
                        <input type="hidden" name="createdby" value="<?php echo $results->CreateBy; ?>">
                        <input type="hidden" name="createddate" value="<?php echo $results->CreateDate; ?>">
                   </div>

                   <div class="col-md-12 col-sm-12">
                        <label class="font-weight-600 mb-0"><?php echo get_phrases(['remarks']);?></label>
                        <textarea name="remarks" class="form-control" id="remarks" rows="2"><?php echo $results->Narration; ?></textarea>
                    </div>
                   
              

                   <div class="col-md-12 col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-stripped table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="200px"><nobr><?php echo get_phrases(['account','name']);?><i class="text-danger">*</i></nobr></th>
                                        <th class="text-center" width="200px"><nobr><?php echo get_phrases(['sub', 'code']);?></nobr></th>
                                        <th class="text-center"><nobr><?php echo get_phrases(['ledger', 'comments']);?></nobr></th>
                                        <th class="text-right"><nobr><?php echo get_phrases(['debit', 'amount']);?></nobr></th>
                                        <th class="text-right"><nobr><?php echo get_phrases(['credit', 'amount']);?></nobr></th>
                                        <th class="text-right" width="200px"><nobr><?php echo get_phrases(['reverse', 'head']);?></nobr></th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                        <tbody id="jv_editbody">
                        <?php
            $Debit = 0;
            $Credit = 0;
            $sl = 1;
            if (!empty($results)) {
            foreach ($results->details as $row) {
            $Debit += $row->Debit;
            $Credit += $row->Credit;
            $sub_type_list = $vmodel->bdtaskt1m8_04_getTransAccHead_subtypes($row->subType); 
            ?>
                      <tr><td><select name="account_name[]" id="account_name_<?php echo $sl;?>" class="select2 form-control account_name" required="required">

                       <?php if($jvheads){
                             foreach($jvheads as $jhead){?>
                             <option value="<?php echo $jhead->id;?>" <?php echo ($row->COAID == $jhead->id ? 'selected':'')?>><?php echo $jhead->text;?></option>
                        <?php }}?>
                      </select><div class="others"><input type="hidden" name="head_code[]" id="headcode_<?php echo $sl;?>" class="form-control head_code" value="<?php echo $row->COAID?>" readonly><input type="hidden" name="sub_type[]" value="<?php echo $row->subType?>" id="sub_type_<?php echo $sl;?>" class="form-control sub_type" readonly></div></td>
                      <td><select name="sub_code[]" id="sub_code_<?php echo $sl;?>" class="select2 form-control sub_code"><option value="">Select SubHead</option><?php if($sub_type_list){foreach($sub_type_list as $stypes){?><option value="<?php echo $stypes->id;?>" <?php echo ($row->subCode == $stypes->id ? 'selected':'')?>><?php echo $stypes->name;?></option><?php }}?></select></td>
                      <td><input type="text" name="ledger_comments[]" class="form-control " autocomplete="off" value="<?php echo $row->ledgerComment?>"></td>
                      <td><input type="text" name="debit[]" class="form-control edebit onlyNumber text-right" value="<?php echo $row->Debit?>" id="journal_debit_<?php echo $sl;?>" autocomplete="off" required></td>
                      <td><input type="text" name="credit[]" class="form-control ecredit onlyNumber text-right" value="<?php echo $row->Credit?>" id="journal_credit_<?php echo $sl;?>" autocomplete="off" required></td>
                      <td><select name="reversehead[]" id="reversehead_<?php echo $sl;?>" class="select2 form-control reversehead_name" required="required">
                       <?php if($jvheads){
                             foreach($jvheads as $jhead){?>
                             <option value="<?php echo $jhead->id;?>" <?php echo ($row->RevCodde == $jhead->id ? 'selected':'')?>><?php echo $jhead->text;?></option>
                        <?php }}?></select><div class="others"></div><input type="hidden" name="reversehead_code[]" class="form-control reversehead_code" readonly></td>
                      <td><a href="javascript:void(0);" class="btn btn-danger-soft btn-sm removeBtn text-center"><i class="far fa-trash-alt fs-20"></i></a></td>
                  </tr>
                  <?php $sl++;
}
} ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-right" colspan="3"><?php echo get_phrases(['total']);?></th>
                                        <th><input type="text" name="totalDebit" class="form-control onlyNumber text-right" id="etotalDebit" value="<?php echo number_format($Debit,2)?>" readonly=""></th>
                                        <th><input type="text" name="totalCredit" class="form-control onlyNumber text-right" id="etotalCredit" readonly="" value="<?php echo number_format($Credit,2)?>"></th>
                                        <th></th>
                                        <th><button type="button" class="btn btn-success btn-sm addMore"><i class="fa fa-plus"></i></button></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                   </div>
                   <div class="col-sm-12 text-right">
    <button type="submit" class="btn btn-success" id="updateBtn"><?php echo get_phrases(['update', 'voucher']); ?></button>
  
  </div>
                
                </div>
      <script>
    $(document).ready(function() {
    "use strict";
    $(".select2").select2();

    function loadFirstRow(id){
            var addHTML = '<tr>'+
                       '<td><select name="account_name[]" id="account_name_'+id+'" class="custom-select form-control account_name" required="required"></select><div class="others"><input type="hidden" name="head_code[]" id="headcode_' + id + '" class="form-control head_code" readonly><input type="hidden" name="sub_type[]" id="sub_type_' + id + '" class="form-control sub_type" readonly></div></td>'+
                       '<td><select name="sub_code[]" id="sub_code_' + id + '" class="custom-select form-control sub_code"></select></td>'+ '<td><input type="text" name="ledger_comments[]" class="form-control " autocomplete="off" ></td>' +
                       '<td><input type="text" name="debit[]" class="form-control edebit onlyNumber text-right" value="0" id="journal_debit_'+id+'" autocomplete="off" required></td>'+
                       '<td><input type="text" name="credit[]" class="form-control ecredit onlyNumber text-right" value="0" id="journal_credit_'+id+'" autocomplete="off" required></td>'+'<td><select name="reversehead[]" id="reversehead_'+id+'" class="custom-select form-control reversehead_name" required="required"></select><div class="others"></div><input type="hidden" name="reversehead_code[]" class="form-control reversehead_code" readonly></td>'+
                       '<td><a href="javascript:void(0);" class="btn btn-danger-soft btn-sm removeBtn text-center"><i class="far fa-trash-alt fs-20"></i></a></td>'+
                   '</tr>';
            $('#jv_editbody').append(addHTML);
            // search account
            $('#account_name_'+id).select2({
                placeholder: '<?php echo get_phrases(['select', 'account', 'name']);?>',
                minimumInputLength: 1,
                    ajax: {
                        url: _baseURL+'account/vouchers/searchallwithoutcashTransactionAcc',
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                          return {
                            results:  $.map(data, function (item) {
                                  return {
                                      text: item.text,
                                      id: item.id
                                  }
                              })
                          };
                        },
                        cache: true
                   }
            });
            $('#reversehead_'+id).select2({
                placeholder: '<?php echo get_phrases(['select', 'account', 'name']);?>',
                minimumInputLength: 1,
                    ajax: {
                        url: _baseURL+'account/vouchers/searchallwithoutcashTransactionAcc',
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                          return {
                            results:  $.map(data, function (item) {
                                  return {
                                      text: item.text,
                                      id: item.id
                                  }
                              })
                          };
                        },
                        cache: true
                   }
            });
        }
        

        $('body').on('click', '.addMore', function() {
            var countPayId = $("#jv_editbody >tr").length;
            var tr_length = (countPayId?countPayId:0) + 1 + "<?php echo rand(0,9)?>";
            loadFirstRow(tr_length);
        });
        
        $('body').on('click', '.removeBtn', function() {
            var rowCount = $('#jv_editbody >tr').length;
            if(rowCount > 1){
                $(this).parent().parent().remove();
                ebalanceBtn();
               
            }else{
                alert("There only one row you can't delete.");
            } 
        });

        $('body').on('change', '.account_name', function(e) {
            e.preventDefault();
            var id = $(this).val();
            $(this).parent().parent().find(".head_code").val(id);
            var nameid = $(this).attr('id');
            var splitid = nameid.split("_");

            $.ajax({
                type: 'POST',
                url: _baseURL + 'account/vouchers/searchSubcode',
                dataType: 'json',
                data: {
                    'csrf_stream_name': csrf_val,
                    headcode: id
                },
            }).done(function(data) {
                var listdata = JSON.stringify(data.list);
                var sub_type = data.subtype;
                $("#sub_type_" + splitid[2]).val(sub_type);
                if (listdata == '[]') {
                    $("#sub_code_" + splitid[2]).empty();
                } else {
                    $("#sub_code_" + splitid[2]).select2({
                        placeholder: 'Select Subcode',
                        data: data.list
                    });
                    $("#sub_code_" + splitid[2]).val('').trigger('change');
                }

            });
        });

        $('body').on('change', '.reversehead_name', function(e) {
            e.preventDefault();
            var id = $(this).val();
            $(this).parent().parent().find(".reversehead_code").val(id);
            var nameid   = $(this).attr('id');
            var splitid  = nameid.split("_");
            var headcode = $("#headcode_"+splitid[1]).val();
            if(headcode == id){
           toastr.error('You can not Select same Head');
           $('#reversehead_'+ splitid[1]).val('').trigger('change');
           $(this).parent().parent().find(".reversehead_code").val('');
           $('#reversehead_'+ splitid[1]).focus();
            }
        });

        $(document).on('keyup', '.edebit', function(e) {
            e.preventDefault();
            $(this).parent().parent().find('.ecredit').val(0);
            ebalanceBtn();
           
        });

        $(document).on('keyup', '.ecredit', function(e) {
            e.preventDefault();
            $(this).parent().parent().find('.edebit').val(0);

            ebalanceBtn();
            
        });

       
                });
                function ebalanceBtn(){
            // //total   
            var total = 0;
            $('.ecredit').each(function(){ 
                total  += parseFloat($(this).val());
            }); 
            $('#etotalCredit').val(isNaN(total)?0.00:total.toFixed(2));

            // debit
            var total1 = 0;
            $('.edebit').each(function(){ 
                total1  += parseFloat($(this).val());
            }); 
            $('#etotalDebit').val(isNaN(total1)?0.00:total1.toFixed(2));
        }

        function eactiveBtn() {
            var credit = $('#etotalCredit').val();
            var debit = $('#etotalDebit').val();
            if(credit==debit){
                $('#updateBtn').attr('disabled', false);
            }else{
                $('#updateBtn').attr('disabled', true);
            }
        }
    </script>