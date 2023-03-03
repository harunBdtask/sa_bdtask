
    function load_default_item(service_id){

        var submit_url = _baseURL+"inventory/consumption/getDefaultItemByServiceId"; 
        $.ajax({
            type: 'POST',
            url: submit_url,
            data: {'csrf_stream_name':csrf_val,'service_id':service_id },
            dataType: 'JSON',
            success: function(data){
                if(data != null && data.length >0){
                    $("#item_div").html(''); 
                    $("#item_counter").val(0);
                    $.each(data, function(index, value){
                        add_item_row(value.item_id, value.quantity);
                    });
                }
                    
            }
        });  
    }

    function add_item_row(item_id, quantity){

        var item_counter = parseInt($("#item_counter").val()); 
        item_counter += 1;
        $("#item_counter").val(item_counter);

        var consumed_by = $('#consumed_by').val();  
        if(consumed_by=='department'){
            var item_list = $('#department_item_list').html();
        } else {
            var item_list = $('#service_item_list').html();
        }

        var button_color = 'danger';
        var button_class = 'removeRow';
        var button_type = 'minus';
        if(item_counter == 1){
            button_color = 'success';
            button_class = 'addRow';
            button_type = 'plus';
        }

        var html = ' <tr><td><select name="item_id[]" id="item_id'+item_counter+'" class="form-control custom-select" onchange="item_info(this.value,'+item_counter+')" required>'+item_list+'</select></td><td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="qty'+item_counter+'" required autocomplete="off" ></td><td class="valign-middle"><span id="unit'+item_counter+'"></span></td><td><button type="button" class="btn btn-'+button_color+' '+button_class+'" ><i class="fa fa-'+button_type+'"></i></button></td></tr>';

        $("#item_div").append(html); 
        $('#item_div select').select2({
            placeholder: '<?php echo get_phrases(['select','item']);?>'                
        });
        
        $("#item_id"+item_counter).val(item_id).trigger('change');
        $("#qty"+item_counter).val(quantity);
    }


    function item_info(item_id,sl){
        //return;

        $.ajax({
            url: _baseURL+"inventory/consumption/getItemDetailsById/"+item_id,
            type: 'POST',
            data: {'csrf_stream_name':csrf_val, item_id: item_id},
            dataType:"JSON",
            success: function (data) {
                $('#unit'+sl).html(data.unit_name);
            }
        });
    }


    var showCallBackData = function () {
        $('#id').val('');        
        $('#action').val('add');        
        $('.ajaxForm')[0].reset();        
        $('#item_request-modal').modal('hide');
        $('#item_requestList').DataTable().ajax.reload(null, false);
    }

    var showCallBackDataReturn = function () {
        $('.ajaxForm')[0].reset();        
        $('#itemRequestDetails-modal').modal('hide');
        $('#item_requestList').DataTable().ajax.reload(null, false);
    }

    $(document).ready(function() { 
       "use strict";
        //$('option:first-child').val('').trigger('change');
        $('#department_item_list').hide();
        $('#service_item_list').hide();
        $('#doctor_list').hide();

        $('#patient_id').select2({
            placeholder: '<?php echo get_phrases(['search', 'patient']);?>',
            minimumInputLength: 1,
                ajax: {
                    url: _baseURL+'auth/searchPntWithFile',
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


        $('#patient_id').on('change', function(e){
            e.preventDefault();

            var id = $(this).val();
            
            var submit_url = _baseURL+"inventory/consumption/getInvoicesByPatientId/"+id; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(res) {
                   
                    var invoices = '<option value="">Select Invoice</option>';
                    //var doctors = '';
                    $.each(res.invoices, function (key, value) {                        
                        invoices += '<option value="'+key+'">'+value+'</option>';
                    });
                    /*$.each(res.doctors, function (key, value) {                        
                        doctors += '<option value="'+key+'">'+value+'</option>';
                    });*/
                    $('#invoice_id').html(invoices);
                    //$('#doctor_id').html(doctors);
                }
            });  
        });

        $('#invoice_id').on('change', function(e){
            e.preventDefault();

            var id = $(this).val();
            
            var submit_url = _baseURL+"inventory/consumption/getServicesByInvoiceId/"+id; 
            $.ajax({
                type: 'POST',
                url: submit_url,
                data: {'csrf_stream_name':csrf_val},
                dataType: 'JSON',
                success: function(res) {
                   
                    var services = '<option value="">Select Service</option>';
                    //var doctors = '';
                    $.each(res.services, function (key, value) {                        
                        services += '<option value="'+key+'">'+value+'</option>';
                    });
                    /*$.each(res.doctors, function (key, value) {                        
                        doctors += '<option value="'+key+'">'+value+'</option>';
                    });*/
                    $('#service_id').html(services);
                    //$('#doctor_id').html(doctors);
                }
            });  
        });

        $('#service_id').on('change', function(e){
            e.preventDefault();

            var service_id = $(this).val().split('-');


            load_default_item(service_id[2]);
        });

        $('body').on('click', '.addRow', function() {
            var item_counter = parseInt($("#item_counter").val()); 
            item_counter += 1;
            $("#item_counter").val(item_counter);

            var consumed_by = $('#consumed_by').val();  
            if(consumed_by=='department'){
                var item_list = $('#department_item_list').html();
            } else {
                var item_list = $('#service_item_list').html();
            }

            var html = ' <tr><td><select name="item_id[]" class="form-control custom-select" onchange="item_info(this.value,'+item_counter+')" required>'+item_list+'</select></td><td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="qty'+item_counter+'" required autocomplete="off" ></td><td class="valign-middle"><span id="unit'+item_counter+'"></span></td><td><button type="button" class="btn btn-danger removeRow" ><i class="fa fa-minus"></i></button></td></tr>';

            $("#item_div").append(html); 
            $('#item_div select').select2({
                placeholder: '<?php echo get_phrases(['select','item']);?>'                
            });
        });

        $('#consumed_by').on('change', function() {
            $("#item_counter").val(1);

            var consumed_by = $(this).val();  
            if(consumed_by=='department'){
                var item_list = $('#department_item_list').html();                
                $('.consumed_by_service').hide();
                $('.consumed_by_department').show();
            } else {
                var item_list = $('#service_item_list').html();
                $('.consumed_by_service').show();
                $('.consumed_by_department').hide();
            }

            var html = ' <tr><td><select name="item_id[]" class="form-control custom-select" onchange="item_info(this.value,1)" required>'+item_list+'</select></td><td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="qty1" required autocomplete="off" ></td><td class="valign-middle"><span id="unit1"></span></td><td><button type="button" class="btn btn-success addRow" ><i class="fa fa-plus"></i></button></td></tr>';

            $("#item_div").html(html); 
            $('#item_div select').select2({
                placeholder: '<?php echo get_phrases(['select','item']);?>'                
            });
        });


        $('body').on('click', '.removeRow', function() {
            var rowCount = $('#request_table >tbody >tr').length;
            if(rowCount > 1){
                $(this).parent().parent().remove();
                calculation_total();
            }else{
                alert("There only one row you can't delete.");
            } 
        });

        $('.addShowModal').on('click', function(){
            $('.ajaxForm').removeClass('was-validated');
            $('#id').val('');
            $('#action').val('add');

            $('#dept_warehouse_id').val('<?php echo $default_warehouse_id; ?>').trigger('change');
            $('#from_department_id').val('<?php echo session('departmentId'); ?>').trigger('change');
            //$('#payment_method').val('').trigger('change');
            //$('#voucher_no').val('REQ-<?php //echo $voucher_no; ?>');   
            $('#notes').val('');   
            $('#consumed_by').val('service').trigger('change');   
            $('.consumed_by_service').show();
            var service_item_list = $('#service_item_list').html();

            var html = ' <tr><td><select name="item_id[]" class="form-control custom-select" onchange="item_info(this.value,1)" required>'+service_item_list+'</select></td><td><input type="text" name="qty[]" class="form-control text-right onlyNumber" id="qty1" required autocomplete="off" ></td><td class="valign-middle"><span id="unit1"></span></td><td><button type="button" class="btn btn-success addRow" ><i class="fa fa-plus"></i></button></td></tr>';      

            $("#item_div").html(html); 
            $("#item_counter").val(1);
            //calculation(1);
            $('#item_div select').select2({
                placeholder: '<?php echo get_phrases(['select','item']);?>'                
            });

            getMAXID('inventory_item_requests','id','voucher_no','CONS-');

            $('#item_requestModalLabel').text('<?php echo get_phrases(['new','consumption','request']);?>');
            $('.modal_action').text('<?php echo get_phrases(['send']);?>');
            $('#item_request-modal').modal('show');
        });

    });