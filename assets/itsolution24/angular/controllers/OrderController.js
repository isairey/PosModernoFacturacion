window.angularApp.controller("OrderController", [
    "$scope",
    "API_URL",
    "window",
    "jQuery",
    "$compile",
    "$uibModal",
    "$http",
    "$sce",
    "OrderViewModal",
    "ProductCreateModal",
    "CustomerCreateModal",
    "CustomerEditModal",
    "EmailModal", 
function (
    $scope,
    API_URL,
    window,
    $,
    $compile,
    $uibModal,
    $http,
    $sce,
    OrderViewModal,
    ProductCreateModal,
    CustomerCreateModal,
    CustomerEditModal,
    EmailModal
) {
    "use strict";

    var dt = $("#order-order-list");
    if (dt.length > 0) {
        var id = null;
        var i;

        var hideColums = dt.data("hide-colums").split(",");
        var hideColumsArray = [];
        if (hideColums.length) {
            for (i = 0; i < hideColums.length; i+=1) {     
               hideColumsArray.push(parseInt(hideColums[i]));
            }
        }

        var $type = window.getParameterByName("type");
        var $from = window.getParameterByName("from");
        var $to = window.getParameterByName("to");

        //================
        // Start datatable
        //================

        dt.dataTable({
            "oLanguage": {sProcessing: "<img src='../assets/itsolution24/img/loading2.gif'>"},
            "processing": true,
            "dom": "lfBrtip",
            "serverSide": true,
            "ajax": API_URL + "/_inc/order.php?from="+$from+"&to="+$to+"&type="+$type,
            "order": [[ 0, "desc"]],
            "aLengthMenu": [
                [10, 25, 50, 100, 200, -1],
                [10, 25, 50, 100, 200, "All"]
            ],
            "columnDefs": [
                {"targets": [6], "orderable": false},
                {"visible": false,  "targets": hideColumsArray},
                {"className": "text-right", "targets": [4]},
                {"className": "text-center", "targets": [0, 2, 3, 5, 6]},
                { 
                    "targets": [0],
                    'createdCell':  function (td, cellData, rowData, row, col) {
                       $(td).attr('data-title', $("#order-order-list thead tr th:eq(0)").html());
                    }
                },
                { 
                    "targets": [1],
                    'createdCell':  function (td, cellData, rowData, row, col) {
                       $(td).attr('data-title', $("#order-order-list thead tr th:eq(1)").html());
                    }
                },
                { 
                    "targets": [2],
                    'createdCell':  function (td, cellData, rowData, row, col) {
                       $(td).attr('data-title', $("#order-order-list thead tr th:eq(2)").html());
                    }
                },
                { 
                    "targets": [3],
                    'createdCell':  function (td, cellData, rowData, row, col) {
                       $(td).attr('data-title', $("#order-order-list thead tr th:eq(3)").html());
                    }
                },
                { 
                    "targets": [4],
                    'createdCell':  function (td, cellData, rowData, row, col) {
                       $(td).attr('data-title', $("#order-order-list thead tr th:eq(4)").html());
                    }
                },
                { 
                    "targets": [5],
                    'createdCell':  function (td, cellData, rowData, row, col) {
                       $(td).attr('data-title', $("#order-order-list thead tr th:eq(5)").html());
                    }
                },
                { 
                    "targets": [6],
                    'createdCell':  function (td, cellData, rowData, row, col) {
                       $(td).attr('data-title', $("#order-order-list thead tr th:eq(6)").html());
                    }
                },
            ],
            "aoColumns": [
                {data : "created_at"},
                {data : "reference_no"},
                {data : "created_by"},
                {data : "customer_name"},
                {data : "payable_amount"},
                {data : "status"},
                {data : "action"},
            ],
            "footerCallback": function ( row, data, start, end, display ) {
                var pageTotal;
                var api = this.api();
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === "string" ?
                        i.replace(/[\$,]/g, "")*1 :
                        typeof i === "number" ?
                            i : 0;
                };
                // Total over all pages at column 5
                pageTotal = api
                    .column( 5, { page: "current"} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                // Update footer
                $( api.column( 5 ).footer() ).html(
                    window.formatDecimal(pageTotal, 2)
                );
            },
            "pageLength": window.settings.datatable_item_limit,
            "buttons": [
                {
                    extend:    "print",footer: 'true',
                    text:      "<i class=\"fa fa-print\"></i>",
                    titleAttr: "Print",
                    title: "Order Listing",
                    customize: function ( win ) {
                        $(win.document.body)
                            .css( 'font-size', '10pt' )
                            .append(
                                '<div><b><i>Powered by: ITsolution24.com</i></b></div>'
                            )
                            .prepend(
                                '<div class="dt-print-heading"><img class="logo" src="'+window.logo+'"/><h2 class="title">'+window.store.name+'</h2><p>Printed on: '+window.formatDate(new Date())+'</p></div>'
                            );
     
                        $(win.document.body).find( 'table' )
                            .addClass( 'compact' )
                            .css( 'font-size', 'inherit' );
                    },
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend:    "copyHtml5",
                    text:      "<i class=\"fa fa-files-o\"></i>",
                    titleAttr: "Copy",
                    title: window.store.name + " > Order Listing",
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend:    "excelHtml5",
                    text:      "<i class=\"fa fa-file-excel-o\"></i>",
                    titleAttr: "Excel",
                    title: window.store.name + " > Order Listing",
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend:    "csvHtml5",
                    text:      "<i class=\"fa fa-file-text-o\"></i>",
                    titleAttr: "CSV",
                    title: window.store.name + " > Order Listing",
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend:    "pdfHtml5",
                    text:      "<i class=\"fa fa-file-pdf-o\"></i>",
                    titleAttr: "PDF",
                    download: "open",
                    title: window.store.name + " > Order Listing",
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    },
                    customize: function (doc) {
                        doc.content[1].table.widths =  Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                        doc.pageMargins = [10,10,10,10];
                        doc.defaultStyle.fontSize = 8;
                        doc.styles.tableHeader.fontSize = 8;doc.styles.tableHeader.alignment = "left";
                        doc.styles.title.fontSize = 10;
                        // Remove spaces around page title
                        doc.content[0].text = doc.content[0].text.trim();
                        // Header
                        doc.content.splice( 1, 0, {
                            margin: [ 0, 0, 0, 12 ],
                            alignment: 'center',
                            fontSize: 8,
                            text: 'Printed on: '+window.formatDate(new Date()),
                        });
                        // Create a footer
                        doc['footer']=(function(page, pages) {
                            return {
                                columns: [
                                    'Powered by ITSOLUTION24.COM',
                                    {
                                        // This is the right column
                                        alignment: 'right',
                                        text: ['page ', { text: page.toString() },  ' of ', { text: pages.toString() }]
                                    }
                                ],
                                margin: [10, 0]
                            };
                        });
                        // Styling the table: create style object
                        var objLayout = {};
                        // Horizontal line thickness
                        objLayout['hLineWidth'] = function(i) { return 0.5; };
                        // Vertikal line thickness
                        objLayout['vLineWidth'] = function(i) { return 0.5; };
                        // Horizontal line color
                        objLayout['hLineColor'] = function(i) { return '#aaa'; };
                        // Vertical line color
                        objLayout['vLineColor'] = function(i) { return '#aaa'; };
                        // Left padding of the cell
                        objLayout['paddingLeft'] = function(i) { return 4; };
                        // Right padding of the cell
                        objLayout['paddingRight'] = function(i) { return 4; };
                        // Inject the object in the document
                        doc.content[1].layout = objLayout;
                    }
                }
            ],
        });

        //================
        // End datatable
        //================
    }


    // Add Product
    $scope.ProductCreateModalCallback = function($res)
    {
        $("#add_item").val($res.product.p_name).focus();
    }

    $(document).delegate("#add_new_product", "click", function(e) {
        e.stopPropagation();
        e.preventDefault();
        $scope.hideBoxAddBtn = true;
        $scope.hideCategoryAddBtn = true;
        $scope.hideSupAddBtn = true;
        $scope.hideUnitAddBtn = true;
        $scope.hideTaxrateAddBtn = true;
        ProductCreateModal($scope);
    });

    // Add Customer
    $scope.CustomerCreateModalCallback = function($res)
    {
        $("#customer_id").append("<option value='"+$res.customerId+"' selected>"+$res.customerName+"</option>");
        $('#customer_id').trigger('change');    
    }

    $(document).delegate("#add_customer", "click", function(e) {
        e.stopPropagation();
        e.preventDefault();
        CustomerCreateModal($scope);
    });


    // Edit Customer
    $scope.CustomerEditModalCallback = function($res)
    {
        $("#customer_id").append("<option value='"+$res.customer_id+"' selected>"+$res.customer_name+"</option>");
        $('#customer_id').trigger('change');    
    }

    $(document).delegate("#edit_customer", "click", function(e) {
        e.stopPropagation();
        e.preventDefault();

        var customerID = $('#customer_id').val();
        var customerName = $("#customer_id").select2('data')[0].text;
        if (!customerID) {
            swal("warning", "Please, Select a customer!");
            return false;
        }
        CustomerEditModal({'customer_name':customerName,'customer_id':customerID});
    });


     // View Customer Profile
    $(document).delegate("#view_customer", "click", function(e) {
        e.stopPropagation();
        e.preventDefault();
        var customerID = $('#customer_id').val();
        if (!customerID) {
            swal("warning", "Please, Select a customer!");
            return false;
        }
        window.open(window.baseUrl + "/admin/customer_profile.php?customer_id=" + customerID);
    });


    // View order details
    $(document).delegate("#view-order-btn", "click", function (e) {
        e.stopPropagation();
        e.stopImmediatePropagation();
        e.preventDefault();
        var d = dt.DataTable().row( $(this).closest("tr") ).data();
        var $tag = $(this);
        var $btn = $tag.button("loading");
        OrderViewModal(d);
        setTimeout(function() {
            $tag.button("reset");
        }, 300);
    });


    //==============================================================


    var id;
    var sup_id;
    var sup_name;
    var quantity = 0;
    var unitPrice = 0;
    var taxAmount = 0;
    var subTotal = 0;
    var totalTax = 0;
    var total = 0;

    $scope.payableAmount = 0;
    $scope.orderTax = 0;
    $scope.shippingAmount = 0;
    $scope.othersCharge = 0;
    $scope.discountAmount = 0;
    $scope.searchBoxText;
    $scope.sup_id;
    if (sup_id) {
        $scope.sup_id = sup_id;
    }

    // Supplier Select
    $(document).delegate("#sup_id", "select2:select", function (e) {
        e.preventDefault();
        e.stopPropagation();
        var data = e.params.data;
        $scope.$apply(function() {
            $scope.modal_title = data.element.text;
            $scope.sup_id = data.element.value;
        });
    });

    // Product Autocomplete
    $(document).on("focus", ".autocomplete-product", function (e) {
		e.stopImmediatePropagation();
        e.stopPropagation();
        e.preventDefault();
        var $this = $(this);
        $this.attr('autocomplete', 'off');
        var type = $this.data("type");
        var autoTypeNo; 
        if(type =="p_id" ) autoTypeNo = 0;
        if(type =="p_name" ) autoTypeNo = 1;
        $this.autocomplete({
            source: function (request, response) {
                return $http({
                    url: window.baseUrl + "/_inc/ajax.php?type=SELLINGITEM",
                    dataType: "json",
                    method: "post",
                    data: $.param({
                       sup_id: $scope.sup_id,
                       name_starts_with: request.term,
                       type: type
                    }),
                })
                .then(function (data) {
                    return response( $.map( data.data, function (item) {
                        var code = item.split("|");
                        return {
                            label: code[autoTypeNo].replace(/&amp;/g, "&") + " (" + code[2] + ")",
                            value: code[autoTypeNo],
                            data : item
                        };
                    }));
                }, function (data) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });
            },
            focusOpen: true,
            autoFocus: true,
            minLength: 0,
            select: function ( event, ui ) {
                var names = ui.item.data.split("|");
                var data = {
                    itemId: names[0],
                    itemName: names[1],
                    itemCode: names[2],
                    categoryId: names[3],
                    itemQuantity: 1,
                    unitPrice: names[5],
                    itemSellPrice: names[6],
                    itemTaxAmount: names[7],
                    itemTaxMethod: names[8],
                    itemTaxrate: names[9],
                };
                $scope.addProduct(data);
            }, 
            open: function () {
                $(".ui-autocomplete").perfectScrollbar();
                if ($(".ui-autocomplete .ui-menu-item").length == 1) {
                    $(".ui-autocomplete .ui-menu-item:first-child").trigger("click");
                    $("#add_item").val("");
                    $("#add_item").focus();
                }
            }, 
            close: function () {
                $(document).find(".autocomplete-product").blur();
                $(document).find(".autocomplete-product").val("");
                $("#add_item").focus();
            },
        }).bind("focus", function() { 
            if ($("#add_item").val().length > 1) {
                $(this).autocomplete("search");
            }
        });
    });
    $("#add_item").trigger("focus");

    $(document).on("change keyup blur", ".quantity, .unit-price", function (){
        id = $(this).data("id");
        totalTax = 0;
        total = 0;
        $scope._calculate(id);
    });

    $(document).delegate(".remove", "click", function () {
        id = $(this).data("id");
        $("#"+id).remove();
        totalTax = 0;
        total = 0;
        $scope._calculate(id);
    });

    var itemTaxMethod;
    var itemTaxrate;
    var itemTaxAmount;
    var itemTaxAmountView;
    var realItemTaxAmount;
    $scope._calculate = function (id) {
        quantity = $(document).find("#quantity-"+id);
        unitPrice = $(document).find("#unit-price-"+id);
        itemTaxMethod = $(document).find("#tax-method-"+id);
        itemTaxrate = $(document).find("#taxrate-"+id);
        itemTaxAmount = $(document).find("#tax-amount-"+id);
        taxAmount = $(document).find("#tax-amount-"+id);
        realItemTaxAmount = parseFloat((itemTaxrate.val() / 100 ) * parseFloat(unitPrice.val()));
        itemTaxAmount.val(parseFloat(quantity.val()) * realItemTaxAmount);
        taxAmount.val(parseFloat(parseFloat(quantity.val()) * realItemTaxAmount).toFixed(2));
        itemTaxAmountView = $(document).find("#tax-amount-view-"+id);
        itemTaxAmountView.text(itemTaxAmount.val());
        $(document).find(".tax").each(function (i, obj) {
            totalTax = parseFloat($(this).text())*parseFloat(quantity.val());
        });
        subTotal = $(document).find("#subtotal-"+id);
        if (itemTaxMethod.val() == 'exclusive') {
            subTotal.text(parseFloat((parseFloat(quantity.val()) * parseFloat(unitPrice.val())) + parseFloat(taxAmount.val())).toFixed(2));
        } else {
            subTotal.text(parseFloat(parseFloat(quantity.val()) * parseFloat(unitPrice.val())).toFixed(2));
        }
        $(document).find(".subtotal").each(function (i, obj) {
            total = parseFloat(total) + parseFloat($(this).text());
        });

        $("#total-tax").val(totalTax);
        $("#total-amount").val(total);
        $("#total-amount-view").text(window.formatDecimal(total,2));

        $scope._calculateTotalPayable();
    };

    $scope._calculateTotalPayable = function() {
        var payableAmount = 0;
        if ($scope.orderTax < 1 || $scope.orderTax > 100) {
            $scope.orderTax = 0;
            $scope.taxInput = 0;
        } else {
            $scope.taxInput = (parseFloat($scope.orderTax) / 100) * parseFloat(total);
        }
        payableAmount = (parseFloat(total) + parseFloat($scope.taxInput) + parseFloat($scope.shippingAmount) + parseFloat($scope.othersCharge)) - parseFloat($scope.discountAmount);
        $scope.$applyAsync(function() {
            $scope.payableAmount = payableAmount;
            $scope.paidAmount = payableAmount;
        });
    };

    $scope.addOrderTax = function () {
        $scope._calculateTotalPayable();
    };

    $scope.addShippingAmount = function () {
        $scope._calculateTotalPayable();
    };

    $scope.addOthersCharge = function () {
        $scope._calculateTotalPayable();
    };

    $scope.addDiscountAmount = function () {
        $scope._calculateTotalPayable();
    };

    // Add Product
    var sellPrice = 0;
    $scope.addProduct = function(data) {
        if (data.itemTaxMethod == 'exclusive') {
            sellPrice = (parseFloat(data.itemSellPrice) * parseFloat(data.itemQuantity)) + parseFloat(data.itemTaxAmount);
        } else {
            sellPrice = parseFloat(data.itemSellPrice) * parseFloat(data.itemQuantity);
        }
        var html = "<tr id=\""+data.itemId+"\" class=\""+data.itemId+"\" data-item-id=\""+data.itemId+"\">";
        html += "<td class=\"text-center\" style=\"min-width:100px;\" data-title=\"Product Name\">";
        html += "<input name=\"products["+data.itemId+"][item_id]\" type=\"hidden\" class=\"item-id\" value=\""+data.itemId+"\">";
        html += "<input name=\"products["+data.itemId+"][item_name]\" type=\"hidden\" class=\"item-name\" value=\""+data.itemName+"\">";
        html += "<input name=\"products["+data.itemId+"][category_id]\" type=\"hidden\" class=\"categoryid\" value=\""+data.categoryId+"\">";
        html += "<span class=\"name\" id=\"name-"+data.itemId+"\">"+data.itemName+"-"+data.itemCode+"</span>";
        html += "</td>";
        html += "<td class=\"text-center\" data-title=\"Available\">";
        html += "<span class=\"text-center available\" id=\"available-"+data.itemId+"\">"+window.formatDecimal(data.available,2)+"</span>";
        html += "</td>";
        html += "<td style=\"padding:2px;\" data-title=\"Quantity\">";
        html += "<input class=\"form-control input-sm text-center quantity\" name=\"products["+data.itemId+"][quantity]\" type=\"text\" value=\""+data.itemQuantity+"\" data-id=\""+data.itemId+"\" id=\"quantity-"+data.itemId+"\" onclick=\"this.select();\" onkeypress=\"return IsNumeric(event);\" ondrop=\"return false;\" onpaste=\"return false;\" onKeyUp=\"if(this.value<0){this.value='1';}\">";
        html += "</td>";
        html += "<td style=\"padding:2px;min-width:80px;\" data-title=\"Unit Price\">";
        html += "<input id=\"unit-price-"+data.itemId+"\" class=\"form-control input-sm text-center unit-price\" type=\"text\" name=\"products["+data.itemId+"][unit_price]\" value=\""+data.itemSellPrice+"\" data-id=\""+data.itemId+"\" data-item=\""+data.itemId+"\" onclick=\"this.select();\" onkeypress=\"return IsNumeric(event);\" ondrop=\"return false;\" onpaste=\"return false;\" onKeyUp=\"if(this.value<0){this.value='1';}\">";
        html += "</td>";
        html += "<td class=\"text-center\" data-title=\"Tax Amount\">";
        html += "<input id=\"tax-method-"+data.itemId+"\" name=\"products["+data.itemId+"][tax_method]\" type=\"hidden\" value=\""+data.itemTaxMethod+"\">";
        html += "<input id=\"taxrate-"+data.itemId+"\" name=\"products["+data.itemId+"][taxrate]\" type=\"hidden\" value=\""+data.itemTaxrate+"\">";
        html += "<input id=\"tax-amount-"+data.itemId+"\" name=\"products["+data.itemId+"][tax_amount]\" type=\"hidden\" value=\""+data.itemTaxAmount+"\">";
        html += "<span id=\"tax-amount-view-"+data.itemId+"\" class=\"tax tax-amount-view\">"+window.formatDecimal(data.itemTaxAmount,2)+"</span>";
        html += "</td>";
        html += "<td class=\"text-right\" data-title=\"Total\">";
        html += "<span class=\"subtotal\" id=\"subtotal-"+data.itemId+"\">"+window.formatDecimal(sellPrice,2)+"</span>";
        html += "</td>";    
        html += "<td class=\"text-center\">";
        html += "<i class=\"fa fa-close text-red pointer remove\" data-id=\""+data.itemId+"\" title=\"Remove\"></i>";
        html += "</td>";
        html += "</tr>";

        totalTax = parseFloat(totalTax) + parseFloat(data.itemTaxAmount);
        total = parseFloat(total) + parseFloat(sellPrice);

        // Update existing if find
        if ($("#"+data.itemId).length) {
            quantity = $(document).find("#quantity-"+data.itemId);
            quantity.val(parseFloat(quantity.val()) + 1);
            unitPrice = $(document).find("#unit-price-"+data.itemId);
            itemTaxMethod = $(document).find("#tax-method-"+data.itemId);
            itemTaxrate = $(document).find("#taxrate-"+data.itemId);
            itemTaxAmount = $(document).find("#tax-amount-"+data.itemId);
            taxAmount = $(document).find("#tax-amount-"+data.itemId);
            realItemTaxAmount = parseFloat((itemTaxrate.val() / 100 ) * parseFloat(unitPrice.val()));
            itemTaxAmount.val(parseFloat(quantity.val()) * realItemTaxAmount);
            taxAmount.val(parseFloat(parseFloat(quantity.val()) * realItemTaxAmount).toFixed(2));
            itemTaxAmountView = $(document).find("#tax-amount-view-"+data.itemId);
            itemTaxAmountView.text(itemTaxAmount.val());
            subTotal = $(document).find("#subtotal-"+data.itemId);
            subTotal.text(window.formatDecimal(parseFloat(subTotal.text()) + parseFloat(sellPrice),2));
        } else {
            $(document).find("#product-table tbody").append(html);
        }

        $("#total-tax").val(totalTax);
        $("#total-amount").val(total);
        $("#total-amount-view").text(window.formatDecimal(total,2));

        $scope._calculateTotalPayable();
    };

    // Edit Order
    if (window.getParameterByName("reference_no")) {

        var refNo = window.getParameterByName("reference_no");
        $http({
            url: window.baseUrl + "/_inc/ajax.php?type=QUOTATIONINFO",
            dataType: "json",
            method: "post",
            data: $.param({
               ref_no: refNo,
            }),
        })
        .then(function (data) {
            var order = data.data.quotation;
            $scope.date = order.date;
            $scope.refNo = order.reference_no;
            $scope.orderNote = order.order_note;
            $("#status").val(order.status).trigger("change");
            $("#customer_id").val(order.customer_id).trigger("change");
            $scope.payableAmount = order.payable_amount;
            $scope.orderTax = order.order_tax;
            $scope.shippingAmount = window.formatDecimal(order.shipping_amount,2);
            $scope.othersCharge = window.formatDecimal(order.others_charge,2);
            $scope.discountAmount = window.formatDecimal(order.discount_amount,2);
            window.angular.forEach(order.items, function(item, key) {
                var data = {
                    itemId: item.item_id,
                    itemName: item.item_name,
                    itemCode: item.item_code,
                    categoryId: item.category_id,
                    itemQuantity: window.formatDecimal(item.item_quantity,2),
                    unitPrice: window.formatDecimal(item.item_purchase_price,2),
                    itemSellPrice: window.formatDecimal(item.item_price,2),
                    itemTaxAmount: item.item_tax,
                    itemTaxMethod: item.tax_method,
                    itemTaxrate: item.tax,
                };
                $scope.addProduct(data);
            });
        }, function (data) {
           window.swal("Oops!", response.data.errorMsg, "error");
        });
    }


    // Add Supplier And Product By Query String
    if (window.getParameterByName("sup_id")) {
        $("#sup_id").val(window.getParameterByName("sup_id")).trigger("change");
        $scope.sup_id = window.getParameterByName("sup_id");
    }
    if (window.getParameterByName("p_code")) {
        $("#add_item").val(window.getParameterByName("p_code"));
        $("#add_item").trigger("focus");
    }

    $("#sup_id").on("select2:select", function(e) {
        $("#product-table tbody").empty();
    });


    //=======================================================================



    // Create new order
    $(document).delegate("#create-order-submit", "click", function(e) {
        e.preventDefault();
        var $tag = $(this);
        var $btn = $tag.button("loading");
        var form = $($tag.data("form"));
        form.find(".alert").remove();
        var actionUrl = form.attr("action");
        
        $http({
            url: window.baseUrl + "/_inc/" + actionUrl + "?action_type=CREATE",
            method: "POST",
            data: form.serialize(),
            cache: false,
            processData: false,
            contentType: false,
            dataType: "json"
        }).
        then(function(response) {
            $("#reset").trigger("click");
            $btn.button("reset");
            $(":input[type=\"button\"]").prop("disabled", false);
            var alertMsg = response.data.msg;
            window.toastr.success(alertMsg, "Success!");
            id = response.data.id;
            dt.DataTable().ajax.reload(function(json) {
                if ($("#row_"+id).length) {
                    $("#row_"+id).flash("yellow", 5000);
                }
            }, false);
        }, function(response) {
            $btn.button("reset");
            $(":input[type=\"button\"]").prop("disabled", false);
            var alertMsg = "<div>";
            window.angular.forEach(response.data, function(value) {
                alertMsg += "<p>" + value + ".</p>";
            });
            alertMsg += "</div>";
            window.toastr.warning(alertMsg, "Warning!");
        });
    });

    // Edit order
    $(document).delegate("#update-order-submit", "click", function(e) {
        e.preventDefault();
        var $tag = $(this);
        var $btn = $tag.button("loading");
        var form = $($tag.data("form"));
        form.find(".alert").remove();
        var actionUrl = form.attr("action");
        
        $http({
            url: window.baseUrl + "/_inc/" + actionUrl + "?action_type=UPDATE",
            method: "POST",
            data: form.serialize(),
            cache: false,
            processData: false,
            contentType: false,
            dataType: "json"
        }).
        then(function(response) {
            $btn.button("reset");
            $(":input[type=\"button\"]").prop("disabled", false);
            var alertMsg = response.data.msg;
            window.swal({
              title: "Success!",
              text: "Going back to list...",
              icon: "success",
              buttons: true,
              dangerMode: false,
            })
            .then(function (willDelete) {
                if (willDelete) {
                    window.location = window.baseUrl+'/admin/order.php';
                } else {
                    window.toastr.success(alertMsg, "Success!");
                }
            });

        }, function(response) {
            $btn.button("reset");
            $(":input[type=\"button\"]").prop("disabled", false);
            var alertMsg = "<div>";
            window.angular.forEach(response.data, function(value) {
                alertMsg += "<p>" + value + ".</p>";
            });
            alertMsg += "</div>";
            window.toastr.warning(alertMsg, "Warning!");
        });
    });

    // View order
    $(document).delegate("#view-order-btn", "click", function (e) {
        e.stopPropagation();
        e.preventDefault();
        var d = dt.DataTable().row( $(this).closest("tr") ).data();
        OrderViewModal(d);
    });

    // Delete order
    $(document).delegate("#delete-order", "click", function(e) {
        e.stopPropagation();
        e.preventDefault();
        var d = dt.DataTable().row( $(this).closest("tr") ).data();
        var $tag = $(this);
        var $btn = $tag.button("loading");
        window.swal({
          title: "Delete!",
          text: "Are You Sure?",
          icon: "warning",
          buttons: {
			cancel: true,
			confirm: true,
		  },
        })
        .then(function (willDelete) {
            if (willDelete) {
                $http({
                    method: "POST",
                    url: API_URL + "/_inc/order.php?action_type=DELETE",
                    data: "reference_no="+d.reference_no,
                    dataType: "JSON"
                })
                .then(function(response) {
                    dt.DataTable().ajax.reload( null, false );
                    window.swal("success!", response.data.msg, "success");
                    setTimeout(function() {
                        $tag.button("reset");
                    }, 300);
                }, function(response) {
                    window.swal("Oops!", response.data.errorMsg, "error");
                    setTimeout(function() {
                        $tag.button("reset");
                    }, 300);
                });
            } else {
                setTimeout(function() {
                    $tag.button("reset");
                }, 300);
            }
        });
    });

    // Reset form
    $(document).delegate("#reset", "click", function (e) {
        e.preventDefault();
        $("#reference_no").val("");
        $("#order-tax").val(0);
        $("#discount-amount").val(0);
        $("#shipping-amount").val(0);
        $("#others-charge").val(0);
        $("#order-note").val("");
        $("#status").val("sent").trigger("change");
        $("#sup_id").val("").trigger("change");
        $("#customer_id").val("").trigger("change");
        $("#product-table tbody").empty();
        $("total-amount-view").text("0.00");
    });

    // Append email button into datatable buttons
    if (window.sendReportEmail) { $(".dt-buttons").append("<button id=\"email-btn\" class=\"btn btn-default buttons-email\" tabindex=\"0\" aria-controls=\"order-order-list\" type=\"button\" title=\"Email\"><span><i class=\"fa fa-envelope\"></i></span></button>"); };
    
    // Send order list through email
    $("#email-btn").on( "click", function (e) {
        e.stopPropagation();
        e.preventDefault();
        dt.find("thead th:nth-child(7), tbody td:nth-child(7), tfoot th:nth-child(7)").addClass("hide-in-mail");
        var thehtml = dt.html();
        EmailModal({template: "default", subject: "Order Listing", title:"Order Listing", html: thehtml});
    });
}]);