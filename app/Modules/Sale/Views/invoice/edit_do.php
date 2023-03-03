<link href="<?php echo base_url() ?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<div class="row">
    <div class="col-sm-12">
        <div class="card">

            <div class="card-body">
                <?php echo form_open('sale/deliver_order/update_do', 'id="do_update"') ?>
                <div class="row form-group">
                    <label for="name" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['dealer', 'name']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">

                        <?php echo  form_dropdown('dealer_id', $dealer_list, ($do_main ? $do_main->dealer_id : ''), 'class="form-control select2" id="dealer_id" tabindex="1" required') ?>
                    </div>

                    <label for="date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['date']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input type="text" name="date" placeholder="<?php echo get_phrases(['date']); ?>" class="form-control" id="name" autocomplete="off" tabindex="2" required value="<?php echo ($do_main ? $do_main->do_date : '') ?>" readonly>
                        <input type="hidden" name="dealer_commission_rate" id="dealer_commission_rate" value="<?php echo ($do_details ? $do_details[0]->dealer_commission_rate : 0) ?>">
                        <input type="hidden" name="voucher_no" id="voucher_no" value="<?php echo ($do_main ? $do_main->vouhcer_no : '') ?>">
                    </div>
                </div>

                <div class="row form-group">
                    <label for="date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['dealer', 'phone','no']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input type="text" name="dealer_phone_no" placeholder="" value="<?php echo ($do_main ? $do_main->phone_no : '') ?>" class="form-control " id="dealer_phone_no" readonly>
                        
                    </div>
                    <label for="voucher_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['dealer','address']) ?> <i class="text-danger"></i></label>
                    <div class="col-sm-4">
                        <textarea id="address" class="form-control" placeholder="<?php echo get_phrases(['address']) ?>" readonly><?php echo ($do_main ? $do_main->address : '') ?></textarea>
                        <input type="hidden" name="do_id" value="<?php echo ($do_main ? $do_main->do_id : '') ?>">
                    </div>


                </div>

                 <div class="row form-group">
                    <label for="date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['sales', 'person']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input type="text" name="sales_man_name" placeholder="" value="<?php echo ($do_main ? $do_main->fullname : '') ?>" class="form-control " id="sales_man_name" readonly>
                        <input type="hidden" name="sales_man" placeholder="" value="<?php echo ($do_main ? $do_main->do_by : '') ?>" class="form-control " id="sales_man" required>
                    </div>
                    <label for="voucher_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['sales','person','address']) ?> <i class="text-danger"></i></label>
                    <div class="col-sm-4">
                        <textarea id="sales_person_address" class="form-control" placeholder="<?php echo get_phrases(['address']) ?>" readonly><?php echo ($salepersoninfo ? $salepersoninfo->present_address : '') ?></textarea>
                       
                    </div>


                </div>

                <div class="">
                    <table class="table table-bordered" id="doinvoice">
                        <thead>
                            <tr>
                                <th class="text-center"><?php echo get_phrases(['item', 'name']); ?><i class="text-danger">*</i></th>

                                <th class="text-center"><?php echo get_phrases(['order', 'quantity']) . '(' . get_phrases(['bag']) . ')'; ?><i class="text-danger">*</i></th>
                                <th class="text-center"><?php echo get_phrases(['bag', 'weight']); ?></th>
                                <th class="text-center"><?php echo get_phrases(['kg']); ?></th>
                                <th class="text-center"><?php echo get_phrases(['unit', 'price']); ?><i class="text-danger">*</i></th>
                                <th class="text-center"><?php echo get_phrases(['item', 'commission', 'rate']); ?></th>
                                <th class="text-center"><?php echo get_phrases(['item', 'commission']); ?></th>
                                <th class="text-center"><?php echo get_phrases(['dealer', 'commission', 'rate']); ?></th>
                                <th class="text-center"><?php echo get_phrases(['dealer', 'commission']); ?></th>
                                <th class="text-center">
                                    <nobr><?php echo get_phrases(['total', 'amount']); ?></nobr>
                                </th>
                                <th class="text-center"><?php echo get_phrases(['action']); ?></th>

                            </tr>
                        </thead>
                        <tbody id="do_tbl_body">
                            <?php
                            $sub_total = 0;
                            $gd_total_kg = 0;
                            if ($do_details) {
                                $sl = 1;
                                foreach ($do_details as $details) {

                            ?>
                                    <tr>
                                        <td><select class="form-control select2" id="product_id_<?php echo $sl ?>" name="product_id[]" required onchange="checkitem_stock(<?php echo $sl ?>)">
                                                <option value="<?php echo $details->item_id ?>"><?php echo $details->item_name . ' (' . $details->company_code . ')'; ?></option>
                                            </select></td>
                                        <td><input type="text" class="form-control text-right" name="quantity[]" id="quantity_<?php echo $sl ?>" onkeyup="do_calculation(<?php echo $sl ?>)" onchange="do_calculation(<?php echo $sl ?>)" tabindex="5" required autocomplete="off" value="<?php echo $details->quantity ?>"></td>
                                        <td> <input type="text" name="bag_weight[]" class="form-control text-right" id="bag_weight_<?php echo $sl ?>" value="<?php echo  $details->bag_weight; ?>" readonly></td>
                                        <td><input type="text" class="form-control text-right total_kg" id="kg_<?php echo $sl ?>" name="kg[]" onkeyup="do_calculation(<?php echo $sl ?>)" onchange="do_calculation(<?php echo $sl ?>)" readonly="" placeholder="0.00" value="<?php $kg_total = (($details->bag_weight ? $details->bag_weight : 0) * ($details->quantity ? $details->quantity : 0)); echo $kg_total; $gd_total_kg += $kg_total; ?>"></td>
                                        <td><input type="text" class="form-control text-right" name="price[]" id="price_<?php echo $sl ?>" onkeyup="do_calculation(<?php echo $sl ?>)" onchange="do_calculation(<?php echo $sl ?>)" tabindex="6" value="<?php echo  $details->unit_price; ?>" required autocomplete="off" readonly></td>

                                        <td><input type="text" class="form-control text-right" name="commission_rate[]" id="commission_rate_<?php echo $sl ?>" onkeyup="do_calculation(<?php echo $sl ?>)" onchange="do_calculation(<?php echo $sl ?>)" tabindex="6" value="<?php echo  $details->commission_rate; ?>" autocomplete="off" readonly></td>

                                        <td><input type="text" class="form-control text-right commission_am" name="commission_amount[]" id="commission_amount_<?php echo $sl ?>" onkeyup="do_calculation(<?php echo $sl ?>)" onchange="do_calculation(<?php echo $sl ?>)" tabindex="6" value="<?php echo  $details->commission_amount; ?>" readonly></td>
                                        <td><input type="text" class="form-control text-right" name="" id="dealer_commission_rate_<?php echo $sl ?>" value="<?php echo  $details->dealer_commission_rate; ?>" autocomplete="off" readonly></td>

                                        <td><input type="text" class="form-control text-right dealer_commission" name="dealer_commission[]" id="dealer_commission_<?php echo $sl ?>" value="<?php echo  $details->dealer_commission; ?>" readonly></td>
                                        <td><input type="text" class="form-control text-right total_price" name="total_price[]" id="total_price_<?php echo $sl ?>" readonly="" placeholder="0.00" value="<?php
                                                                                                                                                                                                        $row_total = ($details->total_price ? $details->total_price : 0);
                                                                                                                                                                                                        echo  $row_total;
                                                                                                                                                                                                        $sub_total += $row_total; ?>">

                                        </td>
                                        <td><a href="#" class="btn btn-danger" onclick="deleteRow(this,<?php echo $sl ?>)"><i class="fas fa-trash-alt"></i></a></td>

                                    </tr>
                            <?php $sl++;
                                }
                            } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="9" class="text-right"><?php echo get_phrases(['total']) ?></th>

                                <th class="text-right"><input type="text" name="subtotal" id="subtotal" class="form-control text-right" placeholder="0.00" readonly="" value="<?php echo ($sub_total ? $sub_total : '') ?>"> </th>
                                <th>
                                    <!-- <a href="javascript:void(0)" class="btn btn-info" tabindex="7" id="add_new_item" onclick="addnewRow('do_tbl_body')"><i class="fas fa-plus"></i></a> -->
                                </th>
                            </tr>
                            <tr>
                                <th colspan="9" class="text-right"><?php echo get_phrases(['transport', 'cost']) ?></th>

                                <th class="text-right"><input type="text" name="shippingcost" id="shippingCost" class="form-control text-right" placeholder="0.00" value="<?php echo ($do_main ? $do_main->transport_cost : '') ?>" onkeyup="shipping_chargeCalculation()" autocomplete="off"> </th>
                                <th></th>
                            </tr>
                            <tr>
                                <th colspan="9" class="text-right"><?php echo get_phrases(['dO', 'value']) ?></th>

                                <th class="text-right"><input type="hidden" name="grandcommission" id="grandcommission" class="form-control text-right" placeholder="0.00" readonly="" value=""><input type="text" name="grnadtotal" id="grandTotal" class="form-control text-right" placeholder="0.00" readonly="" value="<?php echo ($do_main ? $do_main->grand_total : '') ?>"> </th>
                                <th></th>
                            </tr>
                            <input type="hidden" id="grand_kg" name="grand_kg" value="<?php echo ($gd_total_kg ? $gd_total_kg : '') ?>">>
                        </tfoot>
                    </table>

                    <div class="row form-group">

                        <div class="col-sm-12 text-right">

                            <button type="submit" class="btn btn-success" id="save_do"><?php echo get_phrases(['update']) ?></button>
                        </div>

                    </div>
                </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url() ?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function() {
        "use strict";
        $(".select2").select2();
        $('.datepic').datepicker({
            dateFormat: 'yy-mm-dd'
        });

        var total_items = $('[name="product_id[]"]').length;

        $.ajax({
            type: 'GET',
            url: _baseURL + 'sale/deliver_order/getItemDropdown',
            dataType: 'json',
            data: {
                'csrf_stream_name': csrf_val
            },
        }).done(function(data) {
            for (var i = 0; i < total_items; i++) {
                var trd = i + 1;
                var item_id = $("#product_id_" + trd).val();
                $("#product_id_" + trd).select2({
                    placeholder: '<?php echo get_phrases(['select', 'Item']); ?>',
                    data: data
                });
            }

        });



    });



    function checkitem_stock(sl) {
        var item_id = $("#product_id_" + sl).val();
        $.ajax({
            url: _baseURL + "sale/deliver_order/search_item_stock",
            method: 'post',
            dataType: "json",
            data: {
                product_id: item_id,
                csrf_stream_name: csrf_val
            },
            success: function(data) {


                $("#price_" + sl).val(data.price);
                $("#bag_weight_" + sl).val(data.bag_weight);
                $("#commission_rate_" + sl).val(data.com_rate);
                do_calculation(sl);

            }
        });
    }

    function do_calculation(sl) {
        var quantity = $("#quantity_" + sl).val();
        var avai_qty = $("#available_qty_" + sl).val();
        var quantity = $("#quantity_" + sl).val();
        var price = $("#price_" + sl).val();
        var bag_weight = $("#bag_weight_" + sl).val();
        var kg = (bag_weight ? parseFloat(bag_weight) : 0) * (quantity ? parseFloat(quantity) : 0);
        $("#kg_" + sl).val(kg);

        var commission_rate = $("#commission_rate_" + sl).val();
        var dealer_commission_rate = $("#dealer_commission_rate").val();
        var total_dealer_commission = (kg ? parseFloat(kg) : 0) * (dealer_commission_rate ? parseFloat(dealer_commission_rate) : 0);
        var total_commission = (kg ? parseFloat(kg) : 0) * (commission_rate ? parseFloat(commission_rate) : 0);
        $("#commission_amount_" + sl).val(total_commission.toFixed(2, 2));
        $("#dealer_commission_" + sl).val(total_dealer_commission.toFixed(2, 2));
        var total_amount = (quantity ? parseFloat(quantity) : 0) * (price ? parseFloat(price) : 0);

        var totalwithout_commission = ((total_amount ? parseFloat(total_amount) : 0) - (total_commission ? parseFloat(total_commission) : 0)) - (total_dealer_commission ? parseFloat(total_dealer_commission) : 0);
        $("#total_price_" + sl).val(totalwithout_commission.toFixed(2, 2));



        var gr_com = 0;
        $(".commission_am").each(function() {
            isNaN(this.value) || 0 == this.value.length || (gr_com += parseFloat(this.value))
        });

        $("#grandcommission").val(gr_com.toFixed(2, 2));


        var kg_tot = 0;
        $(".total_kg").each(function() {
            isNaN(this.value) || 0 == this.value.length || (kg_tot += parseFloat(this.value))
        });
        $("#grand_kg").val(kg_tot.toFixed(2, 2));

        var gr_tot = 0;
        $(".total_price").each(function() {
            isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
        });

        $("#subtotal").val(gr_tot.toFixed(2, 2));
        var shipping_cost = $("#shippingCost").val();
        var grand_total = (gr_tot ? parseFloat(gr_tot) : 0) + (shipping_cost ? parseFloat(shipping_cost) : 0);
        $("#grandTotal").val('');
        $("#grandTotal").val(grand_total.toFixed(2, 2));
    }

    var count = 1;

    function addnewRow(t) {

        var row = $("#doinvoice tbody tr").length;
        var count = row + 1;
        var commission_rate = $("#dealer_commission_rate").val();
        var limits = 500;
        if (count == limits) alert("You have reached the limit of adding " + count + " inputs");
        else {
            var a = "product_id_" + count,
                e = document.createElement("tr");
            e.innerHTML = '<td><select class="form-control select2" id="product_id_' + count + '" name="product_id[]" required onchange="checkitem_stock(' + count + ')"><option value=""></option></select></td><td><input type="text" class="form-control text-right" name="quantity[]" id="quantity_' + count + '" onkeyup="do_calculation(' + count + ')" onchange="do_calculation(' + count + ')" required autocomplete="off"></td><td><input type="text" name="bag_weight[]" class="form-control text-right" id="bag_weight_' + count + '" readonly></td> <td><input type="text" class="form-control text-right total_kg"  id="kg_' + count + '" name="kg[]" onkeyup="do_calculation(' + count + ')" onchange="do_calculation(' + count + ')" readonly="" placeholder="0.00"></td><td><input type="text" class="form-control text-right" name="price[]" id="price_' + count + '" onkeyup="do_calculation(' + count + ')" onchange="do_calculation(' + count + ')" required autocomplete="off" readonly></td><td><input type="text" class="form-control text-right commission_rate"  id="commission_rate_' + count + '" readonly="" placeholder="0.00" name="commission_rate[]" value="' + (commission_rate ? commission_rate : 0) + '"></td><td><input type="text" class="form-control text-right commission_am"  id="commission_amount_' + count + '" readonly="" placeholder="0.00" name="commission_amount[]"></td><td><input type="text" class="form-control text-right total_price" name="total_price[]" id="total_price_' + count + '" readonly="" placeholder=""></td><td><a href="#" class="btn btn-danger" onclick="deleteRow(this,' + count + ')"><i class="fas fa-trash-alt"></i></a></td>',
                document.getElementById(t).appendChild(e),
                document.getElementById(a).focus();

            $(".select2").select2();
            var a = $("#doinvoice > tbody > tr").length;
            var max = 0;
            $('#do_form [tabindex]').attr('tabindex', function(a, b) {
                max = Math.max(max, +b);
            });
            if (a == 1) {
                var starttab = 2;
            } else {
                var starttab = max - 2;
            }

            var rnum = count;
            $.ajax({
                type: 'GET',
                url: _baseURL + 'sale/deliver_order/getItemDropdown',
                dataType: 'json',
                data: {
                    'csrf_stream_name': csrf_val
                },
            }).done(function(data) {
                $("#product_id_" + rnum).select2({
                    placeholder: '<?php echo get_phrases(['select', 'Item']); ?>',
                    data: data
                });
            });

            document.getElementById("product_id_" + count).setAttribute("tabindex", starttab + 1);
            document.getElementById("quantity_" + count).setAttribute("tabindex", starttab + 2);
            document.getElementById("price_" + count).setAttribute("tabindex", starttab + 3);
            document.getElementById("add_new_item").setAttribute("tabindex", starttab + 4);
            document.getElementById("save_do").setAttribute("tabindex", starttab + 5);


            count++;

        }

    }

    function deleteRow(t, sl) {
        var a = $("#doinvoice > tbody > tr").length;
        if (1 == a) {
            alert("There only one row you can't delete.");
            document.getElementById('add_new_item').focus();
        } else {

            var e = t.parentNode.parentNode;
            e.parentNode.removeChild(e),
                do_calculation(sl)
            document.getElementById('add_new_item').focus();

        }
    }

    $('body').on('change', '#dealer_id', function(e) {
        e.preventDefault();
        var id = this.value;

        $.ajax({
            type: 'POST',
            url: _baseURL + 'sale/dealer/getDealerDetailsById/' + id,
            dataType: 'JSON',
            data: {
                'csrf_stream_name': csrf_val
            },
            success: function(data) {
                $('#address').val(data.dealer.address);
                $('#dealer_commission_rate').val(data.dealer.commission_rate);
            },
            error: function() {

            }
        });
    });

    $(document).ready(function() {
        "use strict";
        var frm = $("#do_update");
        frm.on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                dataType: 'json',
                data: frm.serialize(),
                success: function(res) {
                    if (res.success == true) {
                        toastr.success(res.message);
                        $('#doDetails-modal').modal('hide');
                        $('#doList').DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error(res.message);
                    }
                },
                error: function(xhr) {
                    alert('failed!');
                }
            });
        });
    });

    function shipping_chargeCalculation() {
        var sub_total = $("#subtotal").val();
        var shipping_charge = $("#shippingCost").val();
        var grand_total = (sub_total ? parseFloat(sub_total) : 0) + (shipping_charge ? parseFloat(shipping_charge) : 0);
        $("#grandTotal").val(grand_total.toFixed(2, 2));
    }
</script>