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
                        <a href="<?php echo base_url('lc/lcs') ?>" class="btn btn-success btn-sm mr-1"><?php echo get_phrases(['manage', 'LC']); ?></a>
                    </div>
                </div>

            </div>
            
            <div class="card-body">
                <div id="accordion">
                    <?php echo form_open_multipart('lc/add_lc', 'class="ajaxForm needs-validation" id="lc_form" novalidate="" data="showCallBackData" ' ); ?>
                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                    <input type="hidden" id="po_item_counter" value="1">
                    <input type="hidden" name="ship_cost_counter" id="ship_cost_counter" value="1">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    LC Info
                                </button>
                            </h5>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="invoiceNo" class="font-weight-600">Invoice No. <i class="text-danger">*</i></label>
                                            <input type="text" name="invoiceNo" id="invoiceNo" class="form-control" value="<?php echo !empty($ah_lc->invoiceNo) ? $ah_lc->invoiceNo : ''; ?>" placeholder="" autocomplete="off" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="lc_number" class="font-weight-600">LC Number <i class="text-danger">*</i></label>
                                            <input type="text" name="lc_number" id="lc_number" class="form-control" value="<?php echo !empty($ah_lc->lc_number) ? $ah_lc->lc_number : ''; ?>" placeholder="<?php echo get_phrases(['enter', 'lC', 'number']); ?>" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="lc_open_date" class="font-weight-600">LC Open Date <i class="text-danger">*</i></label>
                                            <input type="date" name="lc_open_date" id="lc_open_date" class="form-control" value="<?php echo !empty($ah_lc->lc_open_date) ? $ah_lc->lc_open_date : date('Y-m-d'); ?>" autocomplete="off" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="lc_bank_id" class="font-weight-600">LC Bank Name <i class="text-danger">*</i></label>
                                            <select name="lc_bank_id" id="lc_bank_id" class=" form-control" required>
                                                <option value=""><?php echo get_phrases(['bank']) ?></option>
                                                <?php
                                                if (!empty($banks)) { ?>
                                                    <?php foreach ($banks as $key => $value) {  ?>
                                                        <option value="<?php echo $value->HeadCode; ?>" ><?php echo $value->HeadName; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="country" class="font-weight-600"><?php echo get_phrases(['country']) ?> <i class="text-danger">*</i></label>
                                            <select name="country_code" id="country_code" class="custom-select form-control">
                                                <option value=""><?php echo get_phrases(['country']) ?></option>
                                                <?php
                                                $country_origin = countries();
                                                if (!empty($country_origin)) { ?>
                                                    <?php foreach ($country_origin as $key => $value) {  ?>
                                                        <option value="<?php echo $value->code; ?>"><?php echo $value->name; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="lc_amount" class="font-weight-600">LC Amount <i class="text-danger">*</i></label>
                                            <input type="number" name="lc_amount" id="lc_amount" class="form-control onlyNumber" value="<?php echo !empty($ah_lc->lc_amount) ? $ah_lc->lc_amount : ''; ?>" onchange="rateCal()" placeholder="LC amount" autocomplete="off" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="lc_margin" class="font-weight-600">LC Margin (%)<i class="text-danger">*</i></label>
                                            <input type="number" name="lc_margin" id="lc_margin" class="form-control" value="<?php echo !empty($ah_lc->lc_margin) ? $ah_lc->lc_margin : ''; ?>" maxlength="30" autocomplete="off" placeholder="LC margin" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="currency" class="font-weight-600">Currency <i class="text-danger">*</i></label>
                                            <select name="currency" id="currency" class="custom-select form-control">
                                                <option value=""></option>
                                                <?php
                                                if (!empty($currency)) { ?>
                                                    <?php foreach ($currency as $key => $value) {  ?>
                                                        <option value="<?php echo $value->id; ?>" ><?php echo $value->nameE; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="bdt_rate" class="font-weight-600">Conversion Rate <i class="text-danger">*</i></label>
                                            <input type="number" name="bdt_rate" id="bdt_rate" class="form-control onlyNumber" value="<?php echo !empty($ah_lc->bdt_rate) ? $ah_lc->bdt_rate : ''; ?>" onchange="rateCal()" placeholder="Conversion Rate" autocomplete="off" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="lc_amount_bdt" class="font-weight-600"><?php echo get_phrases(['BDT Amount']) ?> <i class="text-danger">*</i></label>
                                            <input type="text" name="lc_amount_bdt" id="lc_amount_bdt" class="form-control" value="<?php echo !empty($ah_lc->bdt_amount) ? $ah_lc->bdt_amount : ''; ?>" readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="amendmentCost" class="font-weight-600">Amendment Cost <i class="text-danger">*</i></label>
                                            <input type="number" name="amendmentCost" class="form-control" value="<?php echo !empty($ah_lc->amendmentCost) ? $ah_lc->amendmentCost : ''; ?>" placeholder="" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="bankCharge" class="font-weight-600">Bank Charge <i class="text-danger">*</i></label>
                                            <input type="number" name="bankCharge" class="form-control" value="<?php echo !empty($ah_lc->bankCharge) ? $ah_lc->bankCharge : ''; ?>" placeholder="" autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="lc_amount" class="font-weight-600">Attachment <i class="text-danger">*</i> <button type="button" class="btn btn-success addRow"><i class="fa fa-plus"></i></button></label>
                                            <div id='attc'>
                                                <div class="input-group" style="margin-top:5px;">
                                                    <input type="text" name="name[]" class="form-control" placeholder="Attachment Name" />
                                                    <input type="file" name="lc_attc[]" class="form-control" />
                                                    <div class="input-group-prepend">
                                                        <button type="button" class="btn btn-success addRow"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="attch_edit" class="hidden">
                                                <?php
                                                if (!empty($ah_lc_attachment)) {
                                                    foreach ($ah_lc_attachment as $key => $value) { ?>
                                                        <div class="input-group" style="margin-top:5px;">
                                                            <input type="text" name="name[]" class="form-control" value="<?php echo !empty($value->name) ? $value->name : ''; ?>" />
                                                            <input type="file" name="lc_attc[]" class="form-control">
                                                            <a href="<?php echo base_url() . $value->attachment; ?>" target="_blank" rel="noopener noreferrer" class="btn btn-success"><i class="fa fa-download"></i> </a>
                                                        </div>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="button" class="btn btn-primary collapsed" data-toggle="collapse" data-target="#collapseShip" aria-expanded="false" aria-controls="collapseShip">Next</button>

                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingShip">
                            <h5 class="mb-0">
                                <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#collapseShip" aria-expanded="true" aria-controls="collapseShip">
                                    Shipment Cost
                                </button>
                            </h5>
                        </div>
                        <div id="collapseShip" class="collapse" aria-labelledby="headingShip" data-parent="#accordion">

                            <div id="ship_cost_div"></div>
                            <div id="edit_ship_cost_div" class="hidden">
                                <?php
                                if (!empty($wh_lc_shipment)) {
                                    $i = 1;
                                    foreach ($wh_lc_shipment as $value) { ?>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="" class="font-weight-600">Shipment Code</label>
                                                        <input type="text" name="shipment_code<?php echo $i;?>" class="form-control" value="<?php echo !empty($value->shipment_code) ? $value->shipment_code : ''; ?>" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="" class="font-weight-600">C&F Cost</label>
                                                        <input type="number" name="cf_cost<?php echo $i;?>" class="form-control" value="<?php echo !empty($value->cf_cost) ? $value->cf_cost : ''; ?>" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="" class="font-weight-600">Transport Cost</label>
                                                        <input type="number" name="transport_cost<?php echo $i;?>" class="form-control" value="<?php echo !empty($value->transport_cost) ? $value->transport_cost : ''; ?>" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="" class="font-weight-600">Extra Cost</label>
                                                        <input type="number" name="extra_cost<?php echo $i;?>" class="form-control" value="<?php echo !empty($value->extra_cost) ? $value->extra_cost : ''; ?>" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="" class="font-weight-600">Duty Cost</label>
                                                        <input type="number" name="duty_cost<?php echo $i;?>" class="form-control" value="<?php echo !empty($value->duty_cost) ? $value->duty_cost : ''; ?>" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                            <div><button type="button" class="btn btn-danger removeDivRow"><i class="fa fa-minus"></i></button><button type="button" class="btn btn-success addDivRow"><i class="fa fa-plus"></i></button></div>
                                        </div>
                                <?php
                                    $i++;
                                    }
                                }
                                ?>
                            </div>

                            <div class="card-footer text-right">
                                <button type="button" class="btn btn-primary collapsed" data-toggle="collapse" data-target="#collapseRef" aria-expanded="false" aria-controls="collapseRef">Next</button>

                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingRef">
                            <h5 class="mb-0">
                                <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#collapseRef" aria-expanded="true" aria-controls="collapseRef">
                                    Reference Information
                                </button>
                            </h5>
                        </div>
                        <div id="collapseRef" class="collapse" aria-labelledby="headingRef" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="lcaf_no" class="font-weight-600">LCAF No.</label>
                                            <input type="text" name="lcaf_no" class="form-control" value="<?php echo !empty($wh_lc_reference->lcaf_no) ? $wh_lc_reference->lcaf_no : ''; ?>" placeholder="" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="bin_vat_no" class="font-weight-600">BIN/VAT No.</label>
                                            <input type="text" name="bin_vat_no" class="form-control" value="<?php echo !empty($wh_lc_reference->bin_vat_no) ? $wh_lc_reference->bin_vat_no : ''; ?>" placeholder="" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="irc_no" class="font-weight-600">IRC No.</label>
                                            <input type="text" name="irc_no" class="form-control" value="<?php echo !empty($wh_lc_reference->irc_no) ? $wh_lc_reference->irc_no : ''; ?>" placeholder="" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="tin_no" class="font-weight-600">TIN No.</label>
                                            <input type="text" name="tin_no" class="form-control" value="<?php echo !empty($wh_lc_reference->tin_no) ? $wh_lc_reference->tin_no : ''; ?>" placeholder="" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="bank_bin_no" class="font-weight-600">Bank BIN No.</label>
                                            <input type="text" name="bank_bin_no" class="form-control" value="<?php echo !empty($wh_lc_reference->bank_bin_no) ? $wh_lc_reference->bank_bin_no : ''; ?>" placeholder="" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="container_no" class="font-weight-600">Container No.</label>
                                            <input type="text" name="container_no" class="form-control" value="<?php echo !empty($wh_lc_reference->container_no) ? $wh_lc_reference->container_no : ''; ?>" placeholder="" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="seal_no" class="font-weight-600">Seal No.</label>
                                            <input type="text" name="seal_no" class="form-control" value="<?php echo !empty($wh_lc_reference->seal_no) ? $wh_lc_reference->seal_no : ''; ?>" placeholder="" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="bl_no" class="font-weight-600">B/L No.</label>
                                            <input type="text" name="bl_no" class="form-control" value="<?php echo !empty($wh_lc_reference->bl_no) ? $wh_lc_reference->bl_no : ''; ?>" placeholder="" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="vessel" class="font-weight-600">Vessel</label>
                                            <input type="text" name="vessel" class="form-control" value="<?php echo !empty($wh_lc_reference->vessel) ? $wh_lc_reference->vessel : ''; ?>" placeholder="" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="voyage_no" class="font-weight-600">Voyage No.</label>
                                            <input type="text" name="voyage_no" class="form-control" value="<?php echo !empty($wh_lc_reference->voyage_no) ? $wh_lc_reference->voyage_no : ''; ?>" placeholder="" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="ref_country" class="font-weight-600"><?php echo get_phrases(['country']) ?> </label>
                                            <select name="ref_country" id="ref_country" class="custom-select form-control">
                                                <option value=""><?php echo get_phrases(['country']) ?></option>
                                                <?php
                                                $country_origin = countries();
                                                if (!empty($country_origin)) { ?>
                                                    <?php foreach ($country_origin as $key => $value) {  ?>
                                                        <option value="<?php echo $value->code; ?>"><?php echo $value->name; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="remarks" class="font-weight-600">Remarks</label>
                                            <textarea name="remarks" class="form-control" placeholder="" autocomplete="off"><?php echo !empty($wh_lc_reference->remarks) ? $wh_lc_reference->remarks : ''; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="button" class="btn btn-primary collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Next</button>

                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button type="button" class="btn btn-info collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Purchase Information
                                </button>
                            </h5>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label class="font-weight-600">PO No. <i class="text-danger">*</i></label>
                                            <input name="po_code" type="text" class="form-control" id="po_code" placeholder="<?php echo get_phrases(['purchase', 'order', 'no']) ?>" autocomplete="off" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label class="font-weight-600">PO Date <i class="text-danger">*</i></label>
                                            <input name="date" type="text" class="form-control datepicker1" id="po_date" placeholder="<?php echo get_phrases(['date']) ?>" value="<?php echo date('d/m/Y') ?>" autocomplete="off" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="supplier_id" class="font-weight-600">Supplier/Party <i class="text-danger">*</i></label>
                                            <select name="supplier_id" id="supplier_id" class="custom-select form-control">
                                                <option value=""></option>
                                                <?php
                                                if (!empty($supplier_list)) { ?>
                                                    <?php foreach ($supplier_list as $key => $value) {  ?>
                                                        <option value="<?php echo $value->id; ?>"><?php echo $value->nameE . ' - ' . $value->code_no; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <table class="table table-sm table-stripped w-100" id="purchase_table">
                                        <thead>
                                            <tr>
                                                <th width="15%" class="text-center"><?php echo get_phrases(['item', 'name']) ?><i class="text-danger">*</i></th>
                                                <th width="10%" class="text-center">H.S. Code</th>
                                                <th width="10%" class="text-center"><?php echo get_phrases(['quantity']) ?></th>
                                                <th width="10%"><?php echo get_phrases(['unit', 'price']) ?></th>
                                                <th width="10%"><?php echo get_phrases(['total', 'price']) ?></th>
                                                <th width="5%"><?php echo get_phrases(['action']) ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="po_item_div">

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-right"><b><?php echo get_phrases(['sub', 'total']) ?></b></td>
                                                <td><input type="text" name="sub_total" class="form-control text-right" id="po_sub_total" readonly=""></td>
                                            </tr>
                                            <tr class="hidden">
                                                <td colspan="4" class="text-right"><b><?php echo get_phrases(['vat']) ?></b></td>
                                                <td><input type="text" name="vat" class="form-control text-right" id="po_vat_amount" readonly=""></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-right"><b><?php echo get_phrases(['grand', 'total']) ?></b></td>
                                                <td><input type="text" name="grand_total" class="form-control text-right" id="po_grand_total" readonly=""></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-primary collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">Previous</button>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>

        </div>
    </div>
</div>


<div id="item_list"></div>


<script type="text/javascript">
    var showCallBackData = function (data) {  
        window.location.href = _baseURL+"lc/lcs";
    }

    function get_item_list() {
        $.ajax({
            url: _baseURL + "lc/get_item_list",
            type: 'POST',
            data: {
                'csrf_stream_name': csrf_val
            },
            dataType: "html",
            async: false,
            success: function(data) {
                $('#item_list').html(data);
            }
        });
    }

    //select item
    function po_item_info(item_id, sl) {
        if (item_id != '') {
            var item_counter = parseInt($("#po_item_counter").val());
            var item_id_each = 0;
            for (var i = 1; i <= item_counter; i++) {
                item_id_each = $("#item_id" + i).val();
                if (item_id == item_id_each && i != sl) {
                    toastr.warning('<?php echo get_notify('Same_item_can_not_be_added'); ?>');
                    $("#item_id" + sl).val('').trigger('change');
                    return false;
                }
            }
            var action = $('#po_action').val();
            var existing = $('#existing' + sl).val();
            preloader_ajax();
            $.ajax({
                url: _baseURL + "lc/getItemDetailsById",
                type: 'POST',
                data: {
                    'csrf_stream_name': csrf_val,
                    'item_id': item_id
                },
                dataType: "JSON",
                async: false,
                success: function(data) {
                    if (data != null) {
                        $('#po_unit' + sl).html(data.unit_name);
                        $('#vat_applicable' + sl).val(data.is_vat);
                        po_calculation(sl);
                    }
                }
            });
        }
    }

    function rateCal() {
        var usd = $('#lc_amount').val();
        var rate = $('#bdt_rate').val();
        var bdtamount = (usd * rate);
        $("#lc_amount_bdt").val(bdtamount);
    }

    function po_calculation(sl) {
        var qty = ($("#po_qty" + sl).val() == '') ? 0 : $("#po_qty" + sl).val();
        var price = ($("#po_price_" + sl).val() == '') ? 0 : $("#po_price_" + sl).val();
        var total = parseFloat(qty) * parseFloat(price);
        $("#po_total_price_" + sl).val(total.toFixed(2));
        var vat_applicable = ($("#vat_applicable" + sl).val() == '') ? 0 : $("#vat_applicable" + sl).val();
        // if (vat_applicable == '1') {
        //     $("#vat_amount" + sl).val(total * <?php echo $vat / 100; ?>);
        // } else {
        //     $("#vat_amount" + sl).val(0);
        // }
        $("#vat_amount" + sl).val(0);
        po_calculation_total();
    }


    function po_calculation_total() {
        var sub_total = 0;
        var vat_total = 0;
        $(".po_subtotal").each(function() {
            sub_total += parseFloat(this.value);
        });
        $(".vat_total").each(function() {
            vat_total += parseFloat(this.value);
        });
        $("#po_vat_amount").val(vat_total.toFixed(2));
        $("#po_sub_total").val(sub_total.toFixed(2));
        var grand_total = sub_total + vat_total;
        $("#po_grand_total").val(grand_total.toFixed(2));
    }

    function get_item_details_edit(edit_id) {
        if (edit_id != '') {
            var submit_url = _baseURL + 'lc/getPurchasedItemDetailsById';
            preloader_ajax();
            $.ajax({
                type: 'POST',
                url: submit_url,
                dataType: 'json',
                async: true,
                data: {
                    'csrf_stream_name': csrf_val,
                    'lc_id': edit_id
                },
                success: function(data) {
                    console.log(data);
                    if (data != null) {
                        $('#supplier_id').val(data.supplier_id).trigger('change');
                        $('#po_date').val(data.date);
                        $('#po_code').val(data.voucher_no);
                        var item_counter = 1;
                        $("#po_item_div").html('');
                        $.each(data, function(index, value) {
                            if (index == 'voucher_no' || index == 'date' || index == 'supplier_id') {
                                // alert(111);
                            }else{
                                add_item_row(item_counter);
                                $("#item_id" + item_counter).val(value.item_id).trigger('change');
                                $("#hs_code" + item_counter).val(value.hs_code);
                                $("#po_qty" + item_counter).val(value.qty);
                                $("#po_price_" + item_counter).val(value.price);
                                $("#po_total_price_" + item_counter).val(value.total);
                                $("#vat_applicable" + item_counter).val(value.vat_applicable);
                                $("#vat_amount" + item_counter).val(value.total * <?php echo $vat / 100; ?>);

                                po_calculation(item_counter);

                                item_counter += 1;
                                $("#po_item_counter").val(item_counter);
                            }
                        });
                    }
                }
            });
        } else {
            $('#po_item_div').html('');
        }
    }

    function add_item_row(item_counter){
        var item_list = $('#item_list').html();
        var html = '<tr>' +
                '<td><select name="item_id[]" id="item_id' + item_counter + '" class="form-control custom-select" onchange="po_item_info(this.value,' + item_counter + ')" required >' + item_list + '</select></td>' +
                '<td><input type="text" name="hs_code[]" class="form-control" id="hs_code' + item_counter + '" autocomplete="off" ></td>' +
                '<td><input type="number" name="qty[]" class="form-control text-right onlyNumber" id="po_qty' + item_counter + '" onkeyup="po_calculation(' + item_counter + ')" required autocomplete="off" ></td>' +
                '<td><input type="text" name="po_price[]" id="po_price_' + item_counter + '" class="form-control text-right" onkeyup="po_calculation(' + item_counter + ')" required autocomplete="off"/></td>' +
                '<td class="valign-middle" align="right"><input type="text" name="total[]" class="form-control po_subtotal text-right" id="po_total_price_' + item_counter + '" readonly=""></td>' +
                '<td><button type="button" class="btn btn-danger removeItemRow" ><i class="fa fa-minus"></i></button><button type="button" class="btn btn-success addItemRow"><i class="fa fa-plus"></i></button></td>' +
                '<input type="hidden" name="existing[]" id="existing' + item_counter + '" value="0">' +
                '<input type="hidden" name="vat_applicable[]" id="vat_applicable' + item_counter + '" />' +
                '<input type="hidden" name="vat_amount[]" id="vat_amount' + item_counter + '" class="vat_total" />' +
                '</tr>';
        $("#po_item_div").append(html); 
        $('select').select2({
            placeholder: '<?php echo get_phrases(['select','item']);?>'                
        });
    }

    function po_item_div() {
        var y = new Date().getFullYear();
        getMAXID('wh_material_purchase', 'id', 'po_code', 'SAAF/Purchase/' + y + '-');
        var item_list = $('#item_list').html();
        var html = '<tr>' +
            '<td><select name="item_id[]" id="item_id1" class="form-control custom-select" required onchange="po_item_info(this.value,1)">' + item_list + '</select></td>' +
            '<td><input type="test" name="hs_code[]" class="form-control" id="hs_code1" autocomplete="off" ></td>' +
            '<td><input type="number" name="qty[]" class="form-control text-right onlyNumber" id="po_qty1" onkeyup="po_calculation(1)" required autocomplete="off" ></td>' +
            '<td><input type="text" name="po_price[]" class="form-control text-right onlyNumber" id="po_price_1" onkeyup="po_calculation(1)" required autocomplete="off" ></td>' +
            '<td class="valign-middle" align="right"><input type="text" name="total[]" class="form-control po_subtotal text-right" id="po_total_price_1" readonly=""></td>' +
            '<td><button type="button" class="btn btn-danger removeItemRow" ><i class="fa fa-minus"></i></button><button type="button" class="btn btn-success addItemRow" ><i class="fa fa-plus"></i></button></td>' +

            '<input type="hidden" name="existing[]" id="existing1" value="0">' +
            '<input type="hidden" name="vat_applicable[]" id="vat_applicable1" />' +
            '<input type="hidden" name="vat_amount[]" id="vat_amount1" class="vat_total" />' +
            '</tr>';

        $("#po_item_div").html(html);
        $("#po_item_counter").val(1);
        po_calculation(1);
        $('select').select2({
            placeholder: "<?php echo get_phrases(['select', 'item']); ?>"
        });
    }

    function ship_cost_div() {
        var html = '<div class="card-body">' +
            '<div class="row">' +
            '<div class="col-md-6 col-sm-12">' +
            '<div class="form-group">' +
            '<label for="" class="font-weight-600">Shipment Code</label>' +
            '<input type="text" name="shipment_code1"  class="form-control" placeholder="" autocomplete="off">' +
            '</div>' +
            '</div>' +
            '<div class="col-md-6 col-sm-12">' +
            '<div class="form-group">' +
            '<label for="" class="font-weight-600">C&F Cost</label>' +
            '<input type="number" name="cf_cost1"  class="form-control" placeholder="" autocomplete="off">' +
            '</div>' +
            '</div>' +
            '<div class="col-md-6 col-sm-12">' +
            '<div class="form-group">' +
            '<label for="" class="font-weight-600">Transport Cost</label>' +
            '<input type="number" name="transport_cost1"  class="form-control" placeholder="" autocomplete="off">' +
            '</div>' +
            '</div>' +
            '<div class="col-md-6 col-sm-12">' +
            '<div class="form-group">' +
            '<label for="" class="font-weight-600">Extra Cost</label>' +
            '<input type="number" name="extra_cost1"  class="form-control" placeholder="" autocomplete="off">' +
            '</div>' +
            '</div>' +
            '<div class="col-md-6 col-sm-12">' +
            '<div class="form-group">' +
            '<label for="" class="font-weight-600">Duty Cost</label>' +
            '<input type="number" name="duty_cost1"  class="form-control" placeholder="" autocomplete="off">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div><button type="button" class="btn btn-danger removeDivRow" ><i class="fa fa-minus"></i></button><button type="button" class="btn btn-success addDivRow" ><i class="fa fa-plus"></i></button></div>' +
            '</div>';

        $("#ship_cost_div").html(html);
        $("#ship_cost_counter").val(1);
    }

    function edit_data() {
        var edit_id = '<?php echo $id; ?>';
        if (edit_id) {
            $('#lc_bank_id').val('<?php echo !empty($ah_lc->lc_bank_id) ? $ah_lc->lc_bank_id : ""; ?>').trigger('change');
            $('#country_code').val('<?php echo !empty($ah_lc->country_code) ? $ah_lc->country_code : ""; ?>').trigger('change');
            $('#currency').val('<?php echo !empty($ah_lc->currency) ? $ah_lc->currency : ""; ?>').trigger('change');
            $('#ref_country').val('<?php echo !empty($wh_lc_reference->ref_country) ? $wh_lc_reference->ref_country : ""; ?>').trigger('change');
            $("#attc").hide();
            $("#attch_edit").removeClass('hidden');
            $("#ship_cost_div").hide();
            $("#edit_ship_cost_div").removeClass('hidden');
            $("#ship_cost_counter").val('<?php echo count($wh_lc_shipment); ?>');
            get_item_details_edit(edit_id);
        }
    }

    $(document).ready(function() {
        "use strict";

        $('#item_list').hide();
        get_item_list();
        po_item_div();
        ship_cost_div();
        edit_data();

        $('body').on('click', '.addRow', function() {
            var html = '<div class="input-group" style="margin-top:5px;">' +
                '<input type="text" name="name[]" class="form-control" placeholder="Attachment Name" />' +
                '<input type="file" name="lc_attc[]" class="form-control" />' +
                '<div class="input-group-prepend">' +
                '<button type="button" class="btn btn-danger removeRow" ><i class="fa fa-minus"></i></button>' +
                '</div>' +
                '</div>';
            $("#attc").append(html);
        });

        $('body').on('click', '.removeRow', function() {
            var rowCount = $('#attc >div >div').length;
            if (rowCount > 0) {
                $(this).parent().parent().remove();
            } else {
                toastr.warning('<?php echo get_notify("There_only_one_row_you_can_not_delete."); ?>');
            }
        });

        $('body').on('click', '.addItemRow', function() {
            var item_counter = parseInt($("#po_item_counter").val());
            item_counter += 1;
            $("#po_item_counter").val(item_counter);
            add_item_row(item_counter);
        });

        $('body').on('click', '.removeItemRow', function() {
            var rowCount = $('#purchase_table >tbody >tr').length;
            if (rowCount > 1) {
                $(this).parent().parent().remove();
                po_calculation_total();
            } else {
                toastr.warning('<?php echo get_notify("There_only_one_row_you_can_not_delete."); ?>');
            }
        });


        $('body').on('click', '.addDivRow', function() {
            var ship_cost_counter = parseInt($("#ship_cost_counter").val());
            ship_cost_counter += 1;
            $("#ship_cost_counter").val(ship_cost_counter);
            var html = '<div class="card-body">' +
                '<div class="row">' +
                '<div class="col-md-6 col-sm-12">' +
                '<div class="form-group">' +
                '<label for="" class="font-weight-600">Shipment Code</label>' +
                '<input type="text" name="shipment_code' + ship_cost_counter + '"  class="form-control" placeholder="" autocomplete="off">' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6 col-sm-12">' +
                '<div class="form-group">' +
                '<label for="" class="font-weight-600">C&F Cost</label>' +
                '<input type="number" name="cf_cost' + ship_cost_counter + '"  class="form-control" placeholder="" autocomplete="off">' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6 col-sm-12">' +
                '<div class="form-group">' +
                '<label for="" class="font-weight-600">Transport Cost</label>' +
                '<input type="number" name="transport_cost' + ship_cost_counter + '"  class="form-control" placeholder="" autocomplete="off">' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6 col-sm-12">' +
                '<div class="form-group">' +
                '<label for="" class="font-weight-600">Extra Cost</label>' +
                '<input type="number" name="extra_cost' + ship_cost_counter + '" class="form-control" placeholder="" autocomplete="off">' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6 col-sm-12">' +
                '<div class="form-group">' +
                '<label for="" class="font-weight-600">Duty Cost</label>' +
                '<input type="number" name="duty_cost' + ship_cost_counter + '"  class="form-control" placeholder="" autocomplete="off">' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div><button type="button" class="btn btn-danger removeDivRow" ><i class="fa fa-minus"></i></button><button type="button" class="btn btn-success addDivRow" ><i class="fa fa-plus"></i></button></div>' +
                '</div>';
            var edit_id = '<?php echo $id; ?>';
            if (edit_id) {
                $("#edit_ship_cost_div").append(html);
            } else {
                $("#ship_cost_div").append(html);
            }

        });

        $('body').on('click', '.removeDivRow', function() {
            var edit_id = '<?php echo $id; ?>';
            if (edit_id) {
                var rowCount = $('#edit_ship_cost_div > div ').length;
            } else {
                var rowCount = $('#ship_cost_div > div ').length;
            }
            if (rowCount > 1) {
                var ship_cost_counter = parseInt($("#ship_cost_counter").val());
                ship_cost_counter -= 1;
                $("#ship_cost_counter").val(ship_cost_counter);
                $(this).parent().parent().remove();
            } else {
                toastr.warning('<?php echo get_notify("There_only_one_row_you_can_not_delete."); ?>');
            }
        });

    });

</script>