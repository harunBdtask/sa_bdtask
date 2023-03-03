<link href="<?php echo base_url() ?>/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<div class="row">
    <?php $domodel = new \App\Modules\Sale\Models\Bdtaskt1m12DeliveryOrderModel(); ?>
    <div class="col-sm-12">
        <div class="card">

            <div class="card-body">
                <?php echo form_open('sale/deliver_order/save_delivered', 'id="save_delivered"') ?>
                <div class="row form-group">
                    <label for="name" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['dealer', 'name']) ?> <i class="text-danger"></i></label>
                    <div class="col-sm-4">

                        <input type="text" class="form-control" name="" value="<?php echo ($do_main ? $do_main->dealer_name : '') ?>" readonly>
                    </div>

                    <label for="date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['date']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input type="text" name="date" placeholder="<?php echo get_phrases(['date']); ?>" class="form-control" id="name" autocomplete="off" tabindex="2" readonly value="<?php echo date('Y-m-d') ?>">
                        <input type="hidden" name="" id="dealer_commission_rate" value="<?php echo ($do_main ? $do_main->commission_rate : 0) ?>">
                        <input type="hidden" name="voucher_no" id="voucher_no" value="<?php echo ($do_main ? $do_main->vouhcer_no : '') ?>">
                    </div>
                </div>

                <div class="row form-group">
                    <label for="voucher_no" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['address']) ?> <i class="text-danger"></i></label>
                    <div class="col-sm-4">
                        <textarea id="address" class="form-control" placeholder="<?php echo get_phrases(['address']) ?>" readonly><?php echo ($do_main ? $do_main->address : '') ?></textarea>
                        <input type="hidden" name="do_id" value="<?php echo ($do_main ? $do_main->do_id : '') ?>">
                    </div>

                    <!--  <label for="date" class="col-sm-2 col-form-label font-weight-600"><?php echo get_phrases(['store']) ?> <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                       
                                               
                    </div>  -->
                </div>

                <div class="">
                    <table class="table table-bordered" id="doinvoice">
                        <thead>
                            <tr>
                                <th class="text-center"><?php echo get_phrases(['item', 'name']); ?><i class="text-danger"></i></th>
                                <th class="text-center"><?php echo get_phrases(['store']); ?><i class="text-danger">*</i></th>
                                <th class="text-center">
                                    <nobr><?php echo get_phrases(['request', 'quantity']) . '(' . get_phrases(['bag']) . ')'; ?></nobr>
                                </th>
                                <th class="text-center">
                                    <nobr><?php echo get_phrases(['delivered', 'quantity']) . '(' . get_phrases(['bag']) . ')'; ?></nobr>
                                </th>
                                <th class="text-center">
                                    <nobr><?php echo get_phrases(['delivery', 'quantity']) . '(' . get_phrases(['bag']) . ')'; ?><i class="text-danger">*</i></nobr>
                                </th>
                                <th class="text-center"><?php echo get_phrases(['bag', 'weight']); ?></th>
                                <th class="text-center"><?php echo get_phrases(['kg']); ?></th>



                            </tr>
                        </thead>
                        <tbody id="do_tbl_body">
                            <?php if ($do_details) {
                                $sl = 1;
                                foreach ($do_details as $details) {
                                    $itme_delivered = $domodel->delivered_itmedata($do_main->do_id, $details->item_id);

                            ?>
                                    <tr>
                                        <td class="text-center" width="200px;"><?php echo $details->item_name ?><input type="hidden" value="<?php echo $details->item_id ?>" name="product_id[]"></td>
                                        <td> <?php echo  form_dropdown('store_id[]', $store_list, null, 'class="form-control select2" id="store_id_<?php echo $sl?>"  required') ?> </td>
                                        <td><input type="text" class="form-control text-right" name="" id="sold_qty_<?php echo $sl ?>" readonly="" placeholder="0.00" value="<?php echo $details->quantity ?>">
                                            <input type="hidden" class="form-control text-right" name="" id="available_qty_<?php echo $sl ?>" readonly="" placeholder="0.00" value="<?php echo (($details->quantity ? $details->quantity : 0) - ($itme_delivered->deliverdqty ? $itme_delivered->deliverdqty : 0)); ?>">
                                        </td>
                                        <td><input type="text" class="form-control text-right" name="" id="delivered_qty_<?php echo $sl ?>" readonly="" placeholder="0.00" value="<?php echo ($itme_delivered->deliverdqty ? $itme_delivered->deliverdqty : 0) ?>"><input type="hidden" class="form-control text-right" name="price[]" id="price_<?php echo $sl ?>" value="<?php echo  $details->unit_price; ?>" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control text-right dqty" name="quantity[]" id="quantity_<?php echo $sl ?>" onkeyup="do_calculation(<?php echo $sl ?>)" onchange="do_calculation(<?php echo $sl ?>)" tabindex="5" required autocomplete="off" value="<?php echo $avail_qty = (($details->quantity ? $details->quantity : 0) - ($itme_delivered->deliverdqty ? $itme_delivered->deliverdqty : 0)) ?>"></td>
                                        <td><input type="text" name="bag_weight[]" id="bag_weight_<?php echo $sl ?>" value="<?php echo  $details->bag_weight; ?>" class="form-control text-right" readonly></td>
                                        <td><input type="text" name="kg[]" class="form-control text-right totalkg" id="kg_<?php echo $sl ?>" readonly="" placeholder="0.00" value="<?php echo (($avail_qty?$avail_qty:0)*($details->bag_weight?$details->bag_weight:0))?>">
                                            <input type="hidden" class="form-control text-right" name="commission_rate[]" id="commission_rate_<?php echo $sl ?>" onkeyup="do_calculation(<?php echo $sl ?>)" onchange="do_calculation(<?php echo $sl ?>)" tabindex="6" value="<?php echo  $details->commission_rate; ?>" autocomplete="off" readonly>
                                            <input type="hidden" class="form-control text-right commission_am" name="commission_amount[]" id="commission_amount_<?php echo $sl ?>" onkeyup="do_calculation(<?php echo $sl ?>)" onchange="do_calculation(<?php echo $sl ?>)" tabindex="6" value="<?php echo  $details->commission_amount; ?>" readonly>
                                            <input type="hidden" class="form-control text-right total_price" name="total_price[]" id="total_price_<?php echo $sl ?>" readonly="" placeholder="0.00" value="<?php echo  $details->total_price; ?>">

                                        </td>
                                    </tr>
                            <?php $sl++;
                                }
                            } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-right"><?php echo get_phrases(['total']) ?></th>
                                <th class="text-right"><input type="text" name="grandcommission" id="grandcommission" class="form-control text-right" placeholder="0.00" readonly="" value=""></th>
                                <th></th>
                                <th class="text-right"><input type="text" name="grnadtotal" id="grandTotal" class="form-control text-right" placeholder="0.00" readonly="" value=""> </th>

                            </tr>
                        </tfoot>
                    </table>

                    <div class="row form-group">

                        <div class="col-sm-12 text-right">

                            <button type="submit" class="btn btn-success" id="save_do"><?php echo get_phrases(['deliver']) ?></button>
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

                $("#available_qty_" + sl).val(data.avail_stock);
                $("#price_" + sl).val(data.price);
                $("#bag_weight_" + sl).val(data.bag_weight);
                do_calculation(sl);

            }
        });
    }

    function do_calculation(sl) {
        var quantity = $("#quantity_" + sl).val();
        var avai_qty = $("#available_qty_" + sl).val();
        if (parseFloat(avai_qty) < parseFloat(quantity)) {
            toastr.error('you can not order more than available number');
            $("#quantity_" + sl).val(0);
            $("#quantity_" + sl).focus();
        }
        var quantity = $("#quantity_" + sl).val();
        var price = $("#price_" + sl).val();
        var bag_weight = $("#bag_weight_" + sl).val();
        var kg = (bag_weight ? parseFloat(bag_weight) : 0) * (quantity ? parseFloat(quantity) : 0);
        $("#kg_" + sl).val(kg);

        var commission_rate = $("#commission_rate_" + sl).val();
        var total_commission = (kg ? parseFloat(kg) : 0) * (commission_rate ? parseFloat(commission_rate) : 0);
        $("#commission_amount_" + sl).val(total_commission.toFixed(2, 2));

        var total_amount = (quantity ? parseFloat(quantity) : 0) * (price ? parseFloat(price) : 0);

        var totalwithout_commission = (total_amount ? parseFloat(total_amount) : 0) - (total_commission ? parseFloat(total_commission) : 0);
        $("#total_price_" + sl).val(totalwithout_commission.toFixed(2, 2));



        var gr_com = 0;
        $(".dqty").each(function() {
            isNaN(this.value) || 0 == this.value.length || (gr_com += parseFloat(this.value))
        });

        $("#grandcommission").val(gr_com.toFixed(2, 2));


        var gr_tot = 0;
        $(".totalkg").each(function() {
            isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
        });

        $("#grandTotal").val(gr_tot.toFixed(2, 2));
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
            e.innerHTML = '<td><select class="form-control select2" id="product_id_' + count + '" name="product_id[]" required onchange="checkitem_stock(' + count + ')"><option value=""></option></select></td><td><input type="text" class="form-control text-right" name="" id="available_qty_' + count + '" readonly="" placeholder=""></td><td><input type="text" class="form-control text-right" name="quantity[]" id="quantity_' + count + '" onkeyup="do_calculation(' + count + ')" onchange="do_calculation(' + count + ')" required autocomplete="off"></td> <td><input type="text" class="form-control text-right"  id="kg_' + count + '" readonly="" placeholder="0.00"></td><td><input type="text" class="form-control text-right" name="price[]" id="price_' + count + '" onkeyup="do_calculation(' + count + ')" onchange="do_calculation(' + count + ')" required autocomplete="off"></td><td><input type="text" class="form-control text-right commission_rate"  id="commission_rate_' + count + '" readonly="" placeholder="0.00" name="commission_rate[]" value="' + (commission_rate ? commission_rate : 0) + '"></td><td><input type="text" class="form-control text-right commission_am"  id="commission_amount_' + count + '" readonly="" placeholder="0.00" name="commission_amount[]"></td><td><input type="text" class="form-control text-right total_price" name="total_price[]" id="total_price_' + count + '" readonly="" placeholder=""><input type="hidden" name="" id="bag_weight_' + count + '"></td><td><a href="#" class="btn btn-danger" onclick="deleteRow(this,' + count + ')"><i class="fas fa-trash-alt"></i></a></td>',
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

            },
            error: function() {

            }
        });
    });

    $(document).ready(function() {
        "use strict";
        var frm = $("#save_delivered");
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
                        printChallan(res.details);
                        // $('#doList').DataTable().ajax.reload(null, false);
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

    function printChallan(view) {
        printJS({
            printable: view,
            type: 'raw-html',
            onPrintDialogClose: printComplete,

        });

        function printComplete() {
            location.reload();
        }

    }
</script>