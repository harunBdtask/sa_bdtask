$(document).ready(function () {
    "use strict"; // Start of use strict

    //Product Grid
    $('.product-grid').each(function () {
        const ps = new PerfectScrollbar($(this)[0]);
    });
    //Select2
    $(".filter-select, .serial-select2").select2();

    //Data table
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
    });
    $('.basic').DataTable({
        iDisplayLength: 15,
        language: {
            oPaginate: {
                sNext: '<i class="ti-angle-right"></i>',
                sPrevious: '<i class="ti-angle-left"></i>'
            }
        }
    });
    //Footer bottom collapse
    $('#sidebarCollapse').on('click', function () {
        $('.fixedclasspos').toggleClass('active');
    });

    $('.collapse-btn2').on('click', function () {
        $('.fixedclasspos, .collapse-btn2').toggleClass('opened');
    });



});

     function onselectimage(id){
        var product_id = id;
        var exist      = $("#product_id_" + product_id).val();
        var qty        = $("#total_qntt_" + product_id).val();
        var add_qty    = parseInt(qty)+1;;
        var base_url   = $('#base_url').val();
        var CSRF_TOKEN = $('[name="csrf_test_name"]').val();
         if(product_id == exist){
            $("#total_qntt_" + product_id).val(add_qty);
            quantity_calculate_pos(product_id);
            calculateSum_pos();
            invoice_paidamount();
            image_activation(product_id);
            document.getElementById('add_item').value = '';
            document.getElementById('add_item').focus();       
         }else{
            $.ajax({
                type: "post",
                async: false,
                url: _baseURL + 'pharmacy/get_posdata',
                data: {product_id: product_id,csrf_stream_name:csrf_val},
                success: function (data) {
                    if (data == false) {
                        alert('This Product Not Found !');
                         document.getElementById('add_item').value = '';
                         document.getElementById('add_item').focus();
                           $(".select2").select2();
                         quantity_calculate_pos(product_id);
                         calculateSum_pos();
                         invoice_paidamount();
                    } else {
                        $("#hidden_tr").css("display", "none");
                        document.getElementById('add_item').value = '';
                        document.getElementById('add_item').focus();
                        $('#normalinvoice tbody').append(data);
                        calculateSum_pos();
                        invoice_paidamount();
                        image_select(product_id);
                        $("input[name='product_quantity[]']").TouchSpin({
                        verticalbuttons: true
                           });
                         $(".select2").select2();
                    }
                },
                error: function () {
                    alert('Request Failed, Please check your code and try again!');
                }
            });
        }
    

 }

   function image_select(id){
         var qty = $("#total_qntt_" + id).val();
         $("#image-active_"+ id ).addClass("active");
         $("#image-active_count_"+ id ).addClass("quantity");
        var active_product = $("#active_pro_"+id).text(qty); 
       }


    function image_activation(id){
    var batch = $('#batch_id_'+id).val();
if(batch =='Select Batch'){
  var  message ='Please Select Batch';
    toastr["error"](message);
    qty = $("#total_qntt_" + id).val(0);
    return false;
}
         var qty = $("#total_qntt_" + id).val();
         $("#image-active_"+ id ).addClass("active");
         $("#image-active_count_"+ id ).addClass("quantity");
        var active_product = $("#active_pro_"+id).text(qty); 
       }


    function quantity_calculate_pos(item){
    var quantity         = $("#total_qntt_" + item).val();
    var price_item       = $("#price_item_" + item).val();
    var discount         = $("#discount_"+ item).val();
    var invoice_discount = $("#invdcount").val();
    var box              = $("#u_box_"+item).val();
    var box_qty          = quantity/box;
    $("#box_qty_" + item).val(box_qty);
    var total_tax        = $("#total_tax_" + item).val();
    var total_discount   = $("#total_discount_" + item).val();
    var dis_type         = 1;
    var taxnumber        = $("#txfieldnum").val();

    var batch     = $("#batch_id_" + item).val();
    var available_quantity = $("#available_quantity_" + item).val();


    if (parseInt(quantity) > parseInt(available_quantity)) {
      if(batch == ''){
        var message = "Please Select Batch";
      }else{
         var message = "You can Sale maximum " + available_quantity + " Items";
      }
        
         $("#total_qntt_" + item).val(0);
        var quantity = 0;
          toastr["error"](message);
         //alert(message);
        $("#total_price_" + item).val(0);
        for(var i=0;i<taxnumber;i++){
        $("#all_tax"+i+"_" + item).val(0);  
    }    
    }
        
    if (quantity > 0 || discount > 0) {
        if (dis_type == 1) {
            var price = quantity * price_item;
            var dis   = +(price * discount / 100);
            $("#all_discount_" + item).val(dis);
            //Total price calculate per product
            var temp    = price - dis;
            var ttletax = 0;
            $("#total_price_" + item).val(temp.toFixed(2,2));
            for(var i=0;i<taxnumber;i++){
           var tax = (temp) * $("#total_tax"+i+"_" + item).val();
         
           ttletax += Number(tax);
            $("#all_tax"+i+"_" + item).val(tax);
           }
        }else if(dis_type == 2){
            var price  = quantity * price_item;
            // Discount cal per product
            var dis   = discount * quantity;
            $("#all_discount_" + item).val(dis);

            //Total price calculate per product
             var temp = price - dis;
            $("#total_price_" + item).val(temp.toFixed(2,2));

            var ttletax = 0;
             for(var i=0;i<taxnumber;i++){
           var tax = (temp) * $("#total_tax"+i+"_" + item).val();
           ttletax += Number(tax);
            $("#all_tax"+i+"_" + item).val(tax);
    }

        }else if(dis_type == 3){
             var total_price = quantity * price_item;
             var dis =  discount;
            // Discount cal per product
            $("#all_discount_" + item).val(dis);
            //Total price calculate per product
            var price = total_price - dis;
            $("#total_price_" + item).val(price.toFixed(2,2));

             var ttletax = 0;
             for(var i=0;i<taxnumber;i++){
           var tax = (price) * $("#total_tax"+i+"_" + item).val();
           ttletax += Number(tax);
            $("#all_tax"+i+"_" + item).val(tax);
    }
        }
    }else {
        var n = quantity * price_item;
        var c = quantity * price_item * total_tax;
        $("#total_price_" + item).val(n), 
        $("#all_tax_" + item).val(c)
    }

     calculateSum_pos();
    }

    "use strict";
function calculateSum_pos() {
document.getElementById("change").value = '';
  var taxnumber = $("#txfieldnum").val();

    var t = 0,
        a = 0,
        e = 0,
        o = 0,
        f = 0,
        p = 0,
        ad = 0,
        tx = 0,
        ds = 0,
     invdis =  $("#invdcount").val();
    //Total Tax
      for(var i=0;i<taxnumber;i++){
      
var j = 0;
    $(".total_tax"+i).each(function () {
        isNaN(this.value) || 0 == this.value.length || (j += parseFloat(this.value))
    });
            $("#total_tax_amount"+i).val(j.toFixed(2, 2));
             
    }
    //Total Discount
    $(".total_discount").each(function() {
        isNaN(this.value) || 0 == this.value.length || (p += parseFloat(this.value))
    }), 
    
    $("#total_discount_ammount").val(p.toFixed(2,2)), 
    $("#total_product_dis").val(p),
     $(".totalTax").each(function () {
        isNaN(this.value) || 0 == this.value.length || (f += parseFloat(this.value))
    }),
            $("#total_tax_amount").val(f.toFixed(2, 2)),

    //Total Price
    $(".total_price").each(function() {
        isNaN(this.value) || 0 == this.value.length || (t += parseFloat(this.value))
    }),
     $(".dppr").each(function () {
        isNaN(this.value) || 0 == this.value.length || (ad += parseFloat(this.value))
    }), 

    o  = a.toFixed(2,2), 
    e  = t.toFixed(2,2),
    tx = f.toFixed(2, 2),
    ds = p.toFixed(2, 2);

    var test = +tx + +e + -ds+ -invdis+ + ad;
    var dis = $("#total_product_dis").val();
    var totaldiscount = +dis + +invdis;
    var total =  parseFloat(t);
           if(totaldiscount > total){
    var message = 'Discount Can not Greater than Total Amount';
         toastr["error"](message);
         $("#invdcount").val(0);
         var test = +tx + +e + -ds+  +ad;
          var totaldiscount = dis;
    }else{
      var test = +tx + +e + -ds+ -invdis+ + ad;
    }
    $("#grandTotal").val(test.toFixed(2, 2));
    $("#total_discount_ammount").val(totaldiscount.toFixed(2, 2));
    var previous    = $("#previous").val();
    var gt          = $("#grandTotal").val();
   
   var grnt_totals = +gt+ +previous;
   $("#n_total").val(grnt_totals.toFixed(2,2));
   $("#net_total_text").text(grnt_totals.toFixed(2, 2)); 
   invoice_paidamount();

}

   "use strict";
function product_stock_pos(sl) {
            var  batch_id          = $('#batch_id_'+sl).val();
            var dataString         = 'batch_id='+ batch_id;
            var product_id         = $('#product_id_'+sl).val();
            var available_quantity = 'available_quantity_'+sl;
            var product_rate       = 'product_rate_'+sl;
            var expire_date        = 'expire_date_'+sl;
            var base_url           = $('#base_url').val();
            var price              = 'price_item_'+sl;
             $.ajax({
                type: "POST",
                url: _baseURL+"pharmacy/get_batch_stock",
                data: {batch_id:batch_id,product_id:product_id,csrf_stream_name:csrf_val},
                cache: false,
                success: function(data)
                {
                   var obj = JSON.parse(data);
                   var today = new Date();
                   var dd = today.getDate();
                   var mm = today.getMonth()+1; 
                   var yyyy = today.getFullYear();
                    if(dd<10){
                    var dd='0'+dd;
                    }
                    if(mm<10){
                     var mm='0'+mm;
                    }
                    var today = yyyy+'-'+mm+'-'+dd;

                   var aj  = new Date(today);
                   var exp = new Date(obj.expire_date);
                
                    if (aj >= exp) {
                     
                     toastr["error"]('Date Expired Please Select another');
                      $('#batch_id_'+sl)[0].selectedIndex = 0;
                      $('#'+expire_date).html('<p style="color:red;align:center">'+obj.expire_date+'</p>');
                       document.getElementById('expire_date_'+sl).innerHTML = '';
                    }else{
                       $('#'+expire_date).html('<p style="color:green;align:center">'+obj.expire_date+'</p>');
                    }


                    $('#'+available_quantity).val(obj.total_product);
                    $('#'+price).val(obj.price);

                }
             });

            $(this).unbind("change");
            return false;

}
"use strict";
function invoice_paidamount() {
    var d = 0;
    var b = 0;
    var t = $("#n_total").val(),
        a = $("#paidAmount").val(),
        e = t - a,
        d = a - t;
        if(e > 0){
        $("#dueAmmount").val(e.toFixed(2,2));
        $("#due_text").text(e.toFixed(2,2));
        $("#due_amount").val(e.toFixed(2,2));
        $("#change").val(b.toFixed(2,2));
       }else{
        $("#dueAmmount").val(0);
        $("#due_text").text(b.toFixed(2,2));
         $("#due_amount").val(0);   
        $("#change").val(d.toFixed(2,2));

      }
      }


"use strict";
function full_paid() {
    var grandTotal = $("#n_total").val();
    $("#paidAmount").val(grandTotal);
    invoice_paidamount();
    calculateSum_pos();
}

"use strict";
function deleteRow(t,product_id) {
    var a = $("#normalinvoice > tbody > tr").length;
    if (1 == a) alert("There only one row you can't delete.");
    else {
        var e = t.parentNode.parentNode;
        e.parentNode.removeChild(e), 
        image_inaactivation(product_id);
        calculateSum_pos();
        invoice_paidamount();
    }
}
function checkqty(id){

}

    "use strict";
 $('body').on('keyup', '#product_name', function() {
        var product_name  = $(this).val();
        $.ajax({
            type: "post",
            async: false,
            url: _baseURL + 'pharmacy/get_medicine_by_name',
            data: {product_name: product_name,csrf_stream_name:csrf_val},
            success: function(data) {
                if (data == '420') {
                    $("#product_search").html('<h1 class"srcalrt">Product not found !</h1>');
                }else{
                    $("#product_search").html(data); 
                }
            },
            error: function() {
                alert('Request Failed, Please check your code and try again!');
            }
        });
    });

    function check_category(category){
        $.ajax({
            type: "post",
            async: false,
            url: _baseURL + 'pharmacy/get_item_by_category',
            data: {category_id:category,csrf_stream_name:csrf_val},
            success: function(data) {
                if (data == '420') {
                    $("#product_search").html(data);
                }else{
                    $("#product_search").html(data); 
                }
               
            },
            error: function() {
                alert('Request Failed, Please check your code and try again!');
            }
        });
    } 


     $(function($){
    var barcodeScannerTimer;
    var barcodeString = '';

$('#add_item').on('keypress', function (e) {
    barcodeString = barcodeString + String.fromCharCode(e.charCode);

    clearTimeout(barcodeScannerTimer);
    barcodeScannerTimer = setTimeout(function () {
        processbarcodeGui();
    }, 300);
});


 function processbarcodeGui() {
    if (barcodeString != '') {  
        var product_id = barcodeString;
        var exist      = $("#product_id_" + product_id).val();
        var qty        = $("#total_qntt_" + product_id).val();
        var add_qty    = parseInt(qty)+1;
        if(product_id == exist){
          $("#total_qntt_" + product_id).val(add_qty);
          image_activation(product_id);
          quantity_calculate_pos(product_id);
          calculateSum_pos();
          invoice_paidamount();
          document.getElementById('add_item').value = '';
          document.getElementById('add_item').focus();       
         }else{
            $.ajax({
                type: "post",
                async: false,
                url: _baseURL + 'pharmacy/get_posdata',
                data: {product_id: product_id,csrf_stream_name:csrf_val},
                success: function (data) {
                    if (data == false) {
                        alert('This Product Not Found !');
                        document.getElementById('add_item').value = '';
                        document.getElementById('add_item').focus();
                        quantity_calculate_pos();
                         calculateSum_pos(barcodeString);
                        invoice_paidamount();
                    } else {
                        $("#hidden_tr").css("display", "none");
                        document.getElementById('add_item').value = '';
                        document.getElementById('add_item').focus();
                        $('#normalinvoice tbody').append(data);
                          $("input[name='product_quantity[]']").TouchSpin({
                        verticalbuttons: true
                           });
                        image_select(product_id);
                        calculateSum_pos();
                        invoice_paidamount();
                    }
                },
                error: function () {
                    alert('Request Failed, Please check your code and try again!');
                }
            });
        }
    } else {
        alert('barcode is invalid: ' + barcodeString);
    }

    barcodeString = ''; 
}

        var barcodeScannerTimer;
        var barcodeString = '';
        $('#add_item_m').keydown(function (e) {
        if (e.keyCode == 13) {
        var product_id = $(this).val();
        var exist      = $("#product_id_" + product_id).val();
        var qty        = $("#total_qntt_" + product_id).val();
        var add_qty    = parseInt(qty)+1;
         if(product_id == exist){
            $("#total_qntt_" + product_id).val(add_qty);
            image_activation(product_id);
          quantity_calculate_pos(product_id);
          calculateSum_pos();
          invoice_paidamount();
           document.getElementById('add_item_m').value = '';
           document.getElementById('add_item_m').focus();       
         }else{
            $.ajax({
                type: "post",
                async: false,
                url: _baseURL + 'pharmacy/get_posdata',
                data: {product_id: product_id,csrf_stream_name:csrf_val},
                success: function (r) {
                   
                        $("#hidden_tr").css("display", "none");
                        document.getElementById('add_item_m').value = '';
                        document.getElementById('add_item_m').focus();
                        $('#normalinvoice tbody').append(r);
                          $("input[name='product_quantity[]']").TouchSpin({
                        verticalbuttons: true
                           });
                        image_select(product_id);
                        calculateSum_pos();
                        invoice_paidamount();
                   
                },
                error: function () {
                    alert('Request Failed, Please check your code and try again!');
                }
            });
        }
        }
    });
});

           "use strict";
function detailsmodal(productname,model,unit,price,image,product_id){
    $("#detailsmodal").modal('show');
    var base_url = _baseURL;
    if(image !=''){
      var image = image;
    }else{
      var image = 'assets/dist/img/pharmacy/item.jpg';
    }
    var stock = document.getElementById("available_quantity_"+product_id).value;
    document.getElementById("modal_productname").innerHTML = productname;
    document.getElementById("modal_productstock").innerHTML = stock;
    document.getElementById("modal_productmodel").innerHTML = model;
    document.getElementById("modal_productunit").innerHTML = unit;
    document.getElementById("modal_productprice").innerHTML = price;
    document.getElementById("modalimg").innerHTML ='<img src="'+base_url+'/' + image + '" alt="image" style="width:100px; height:60px;" />';

}
