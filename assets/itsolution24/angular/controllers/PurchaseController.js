window.angularApp.controller("PurchaseController", [
    "$scope",
    "API_URL",
    "window",
    "jQuery",
    "$compile",
    "$uibModal",
    "$http",
    "$sce",
    "ProductCreateModal",
    "CustomerCreateModal",
    "CustomerEditModal",
    "PurchasePaymentModal",
    "PurchaseInvoiceViewModal",
    "PurchaseInvoiceInfoEditModal",
    "PurchaseReturnModal",
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
    ProductCreateModal,
    CustomerCreateModal,
    CustomerEditModal,
    PurchasePaymentModal,
    PurchaseInvoiceViewModal,
    PurchaseInvoiceInfoEditModal,
    PurchaseReturnModal,
    EmailModal
) {
    "use strict";

    var dt = $("#invoice-invoice-list");
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

    $("#invoice-invoice-list").dataTable({
        "oLanguage": {sProcessing: "<img src='../assets/itsolution24/img/loading2.gif'>"},
        "processing": true,
        "dom": "lfBrtip",
        "serverSide": true,
        "ajax": API_URL + "/_inc/purchase.php?from="+$from+"&to="+$to+"&type="+$type,
        "fixedHeader": true,
        "order": [[ 0, "desc"]],
        "aLengthMenu": [
            [10, 25, 50, 100, 200, -1],
            [10, 25, 50, 100, 200, "All"]
        ],
        "columnDefs": [
            {"targets": [7, 8, 9, 10, 11, 12], "orderable": false},
            {"className": "text-center", "targets": [0, 3, 7, 8, 9, 10, 11]},
            {"className": "text-right", "targets": [4, 5, 6]},
            { "visible": false,  "targets": hideColumsArray},
            { 
                "targets": [0],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(0)").html());
                }
            },
            { 
                "targets": [1],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(1)").html());
                }
            },
            { 
                "targets": [2],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(2)").html());
                }
            },
            { 
                "targets": [3],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(3)").html());
                }
            },
            { 
                "targets": [4],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(4)").html());
                }
            },
            { 
                "targets": [5],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(5)").html());
                }
            },
            { 
                "targets": [6],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(6)").html());
                }
            },
            { 
                "targets": [7],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(7)").html());
                }
            },
            { 
                "targets": [8],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(8)").html());
                }
            },
            { 
                "targets": [9],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(9)").html());
                }
            },
            { 
                "targets": [10],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(10)").html());
                }
            },
            { 
                "targets": [11],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(11)").html());
                }
            },
            { 
                "targets": [12],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(12)").html());
                }
            },
        ],
        "aoColumns": [
            {data : "created_at"},
            {data : "invoice_id"},
            {data : "sup_name"},
            {data : "created_by"},
            {data : "invoice_amount"},
            {data : "paid_amount"},
            {data : "due"},
            {data : "status"},
            {data : "btn_pay"},
            {data : "btn_return"},
            {data : "btn_view"},
            {data : "btn_edit"},
            {data : "btn_delete"}
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

            // Total over all pages at column 4
            pageTotal = api
                .column( 4, { page: "current"} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer
            $( api.column( 4 ).footer() ).html(
                window.formatDecimal(pageTotal, 2)
            );

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

            // Total over all pages at column 6
            pageTotal = api
                .column( 6, { page: "current"} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer
            $( api.column( 6 ).footer() ).html(
                window.formatDecimal(pageTotal, 2)
            );
        },
        "pageLength": window.settings.datatable_item_limit,
        "buttons": [
            {
                extend:    "print",footer: 'true',
                text:      "<i class=\"fa fa-print\"></i>",
                titleAttr: "Print",
                title: "Purchase Listing-"+from+" to "+to,
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
                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                }
            },
            {
                extend:    "copyHtml5",
                text:      "<i class=\"fa fa-files-o\"></i>",
                titleAttr: "Copy",
                title: window.store.name + " > Purchase Listing-"+from+" to "+to,
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                }
            },
            {
                extend:    "excelHtml5",
                text:      "<i class=\"fa fa-file-excel-o\"></i>",
                titleAttr: "Excel",
                title: window.store.name + " > Purchase Listing-"+from+" to "+to,
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                }
            },
            {
                extend:    "csvHtml5",
                text:      "<i class=\"fa fa-file-text-o\"></i>",
                titleAttr: "CSV",
                title: window.store.name + " > Purchase Listing-"+from+" to "+to,
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                }
            },
            {
                extend:    "pdfHtml5",
                text:      "<i class=\"fa fa-file-pdf-o\"></i>",
                titleAttr: "PDF",
                download: "open",
                title: window.store.name + " > Purchase Listing-"+from+" to "+to,
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
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

    // Edit invoice
    $(document).delegate("#edit-invoice-info", "click", function(e) {
        e.stopPropagation();
        e.preventDefault();
        var d = dt.DataTable().row( $(this).closest("tr") ).data();
        var $tag = $(this);
        var $btn = $tag.button("loading");
        PurchaseInvoiceInfoEditModal(d);
        setTimeout(function() {
            $tag.button("reset");
        }, 300);
    });


    // View invoice
    $(document).delegate("#view-invoice-btn", "click", function (e) {
        e.stopPropagation();
        e.preventDefault();
        var d = dt.DataTable().row( $(this).closest("tr") ).data();
        var $tag = $(this);
        var $btn = $tag.button("loading");
        PurchaseInvoiceViewModal(d);
        setTimeout(function() {
            $tag.button("reset");
        }, 300);
    });

    // Delete invoice
    $(document).delegate("#delete-invoice", "click", function(e) {
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
                    url: API_URL + "/_inc/purchase.php",
                    data: "invoice_id="+d.id+"&action_type=DELETE",
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


    // Payment From Table Selection Modal [for Dinein order type]
    $(document).delegate("#pay_now", "click", function(e) {
        e.stopPropagation();
        e.preventDefault();
        var d = dt.DataTable().row( $(this).closest("tr") ).data();
        var $tag = $(this);
        var $btn = $tag.button("loading");
        PurchasePaymentModal(d);
        setTimeout(function() {
            $tag.button("reset");
        }, 300);
    });

    // Return From
    $(document).delegate("#return_item", "click", function(e) {
        e.stopPropagation();
        e.preventDefault();
        var d = dt.DataTable().row( $(this).closest("tr") ).data();
        var $tag = $(this);
        var $btn = $tag.button("loading");
        $http({
          url: window.baseUrl + "/_inc/purchase_payment.php?action_type=ORDERDETAILS&invoice_id="+d.invoice_id,
          method: "GET"
        })
        .then(function(response, status, headers, config) {
            $scope.order = response.data.order;
            $scope.order.datatable = dt;
            PurchaseReturnModal($scope);
            setTimeout(function() {
                $tag.button("reset");
            }, 300);
        }, function(response) {
           window.swal("Oops!", response.data.errorMsg, "error");
           setTimeout(function() {
                $tag.button("reset");
            }, 300);
        });
    });


    // Create new purchase
    $(document).delegate("#create-purchase-submit", "click", function(e) {
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

        
    //--------------------------------------------------------------
    //==============================================================
    //--------------------------------------------------------------



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
    $scope.paidAmount = 0;
    $scope.dueAmount = 0;
    $scope.changeAmount = 0;
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
        if (!$scope.sup_id) {
            window.swal("Oops!", "Please, select supplier first", "warning");
        }
        var $this = $(this);
        $this.attr('autocomplete', 'off');
        var type = $this.data("type");
        var autoTypeNo; 
        if(type =="p_id" ) autoTypeNo = 0;
        if(type =="p_name" ) autoTypeNo = 1;
        $this.autocomplete({
            source: function (request, response) {
                return $http({
                    url: window.baseUrl + "/_inc/ajax.php?type=PURCHASEITEM",
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
                    itemUnitName: names[5],
                    itemPurchasePrice: names[6],
                    itemSellPrice: names[7],
                    itemTaxAmount: names[8],
                    itemTaxMethod: names[9],
                    itemTaxrate: names[10],
                    itemAvailable: names[11],
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

    $(document).on("change keyup blur", ".quantity, .purchase-price", function (){
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
        unitPrice = $(document).find("#purchase-price-"+id);
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
            if (parseFloat($(this).text()) > 0) {
                // totalTax = parseFloat($(this).text()) * parseFloat(quantity.val());
                totalTax = parseFloat($(this).text());
            }
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
            $scope.taxInput = (parseFloat($scope.orderTax) / 100) * parseFloat(total-totalTax);
        }
        if ($scope.taxInput == '') $scope.taxInput = 0;
        if ($scope.shippingAmount == '') $scope.shippingAmount = 0;
        if ($scope.othersCharge == '') $scope.othersCharge = 0;
        if ($scope.discountAmount == '') $scope.discountAmount = 0;
        payableAmount = (parseFloat(total) + parseFloat($scope.taxInput) + parseFloat($scope.shippingAmount) + parseFloat($scope.othersCharge)) - parseFloat($scope.discountAmount);
        $scope.$applyAsync(function() {
            $scope.payableAmount = payableAmount;
            $scope.paidAmount = payableAmount;
        });
        $scope.addPaidAmount();
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

    $scope.addPaidAmount = function () {
        $scope.$applyAsync(function() {
            $scope.dueAmount = parseFloat($scope.payableAmount) > parseFloat($scope.paidAmount) ? parseFloat($scope.payableAmount) - parseFloat($scope.paidAmount) : 0;
            $scope.changeAmount = parseFloat($scope.payableAmount) < parseFloat($scope.paidAmount) ? parseFloat($scope.paidAmount) - parseFloat($scope.payableAmount) : 0;
        });
    };

    // Add Product
    var purchasePrice = 0;
    $scope.addProduct = function(data) {
        if (data.itemTaxMethod == 'exclusive') {
            purchasePrice = (parseFloat(data.itemPurchasePrice) * parseFloat(data.itemQuantity)) + parseFloat(data.itemTaxAmount);
        } else {
            purchasePrice = parseFloat(data.itemPurchasePrice) * parseFloat(data.itemQuantity);
        }
        var html = "<tr id=\""+data.itemId+"\" class=\""+data.itemId+"\" data-item-id=\""+data.itemId+"\">";
        html += "<td class=\"text-center\" style=\"min-width:100px;\" data-title=\"Product Name\">";
        html += "<input name=\"products["+data.itemId+"][item_id]\" type=\"hidden\" class=\"item-id\" value=\""+data.itemId+"\">";
        html += "<input name=\"products["+data.itemId+"][item_name]\" type=\"hidden\" class=\"item-name\" value=\""+data.itemName+"\">";
        html += "<input name=\"products["+data.itemId+"][category_id]\" type=\"hidden\" class=\"categoryid\" value=\""+data.categoryId+"\">";
        html += "<span class=\"name\" id=\"name-"+data.itemId+"\">"+data.itemName+"-"+data.itemCode+"</span>";
        html += "</td>";
        html += "<td class=\"text-center\" data-title=\"Available\">";
        html += "<span class=\"text-center available\" id=\"available-"+data.itemId+"\">"+window.formatDecimal(data.itemAvailable,2)+"</span>";
        html += "</td>";
        html += "<td style=\"padding:2px;\" data-title=\"Quantity\">";
        html += "<input class=\"form-control input-sm text-center quantity\" name=\"products["+data.itemId+"][quantity]\" type=\"text\" value=\""+data.itemQuantity+"\" data-id=\""+data.itemId+"\" id=\"quantity-"+data.itemId+"\" onclick=\"this.select();\" onkeypress=\"return IsNumeric(event);\" ondrop=\"return false;\" onpaste=\"return false;\" onKeyUp=\"if(this.value<0){this.value='1';}\">";
        html += "</td>";
        html += "<td style=\"padding:2px; min-width:80px;\" data-title=\"Purchase Price\">";
        html += "<input id=\"purchase-price-"+data.itemId+"\" class=\"form-control input-sm text-center purchase-price\" type=\"text\" name=\"products["+data.itemId+"][purchase_price]\" value=\""+data.itemPurchasePrice+"\" data-id=\""+data.itemId+"\" data-item=\""+data.itemId+"\" onclick=\"this.select();\" onkeypress=\"return IsNumeric(event);\" ondrop=\"return false;\" onpaste=\"return false;\" onKeyUp=\"if(this.value<0){this.value='1';}\">"
        html += "</td>";
        html += "<td style=\"padding:2px;min-width:80px;\" data-title=\"Unit Price\">";
        html += "<input id=\"sell-price-"+data.itemId+"\" class=\"form-control input-sm text-center sell-price\" type=\"text\" name=\"products["+data.itemId+"][sell_price]\" value=\""+data.itemSellPrice+"\" data-id=\""+data.itemId+"\" data-item=\""+data.itemId+"\" onclick=\"this.select();\" onkeypress=\"return IsNumeric(event);\" ondrop=\"return false;\" onpaste=\"return false;\" onKeyUp=\"if(this.value<0){this.value='1';}\">";
        html += "</td>";
        html += "<td class=\"text-center\" data-title=\"Tax Amount\">";
        html += "<input id=\"tax-method-"+data.itemId+"\" name=\"products["+data.itemId+"][tax_method]\" type=\"hidden\" value=\""+data.itemTaxMethod+"\">";
        html += "<input id=\"taxrate-"+data.itemId+"\" name=\"products["+data.itemId+"][taxrate]\" type=\"hidden\" value=\""+data.itemTaxrate+"\">";
        html += "<input id=\"tax-amount-"+data.itemId+"\" name=\"products["+data.itemId+"][tax_amount]\" type=\"hidden\" value=\""+data.itemTaxAmount+"\">";
        html += "<span id=\"tax-amount-view-"+data.itemId+"\" class=\"tax tax-amount-view\">"+window.formatDecimal(data.itemTaxAmount,2)+"</span>";
        html += "</td>";
        html += "<td class=\"text-right\" data-title=\"Total\">";
        html += "<span class=\"subtotal\" id=\"subtotal-"+data.itemId+"\">"+window.formatDecimal(purchasePrice,2)+"</span>";
        html += "</td>";    
        html += "<td class=\"text-center\">";
        html += "<i class=\"fa fa-close text-red pointer remove\" data-id=\""+data.itemId+"\" title=\"Remove\"></i>";
        html += "</td>";
        html += "</tr>";

        totalTax = parseFloat(totalTax) + parseFloat(data.itemTaxAmount);
        total = parseFloat(total) + parseFloat(purchasePrice);

        // Update existing if find
        if ($("#"+data.itemId).length) {
            quantity = $(document).find("#quantity-"+data.itemId);
            quantity.val(parseFloat(quantity.val()) + 1);
            unitPrice = $(document).find("#purchase-price-"+data.itemId);
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
            subTotal.text(window.formatDecimal(parseFloat(subTotal.text()) + parseFloat(purchasePrice),2));
        } else {
            $(document).find("#product-table tbody").append(html);
        }

        $("#total-tax").val(totalTax);
        $("#total-amount").val(total);
        $("#total-amount-view").text(window.formatDecimal(total,2));
        
        $scope._calculateTotalPayable();
    };

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


    //--------------------------------------------------------------
    //==============================================================
    //--------------------------------------------------------------


        // Reset form
    $(document).delegate("#reset", "click", function (e) {
        e.preventDefault();

        $scope.payableAmount = 0;
        $scope.orderTax = 0;
        $scope.shippingAmount = 0;
        $scope.othersCharge = 0;
        $scope.discountAmount = 0;
        $scope.paidAmount = 0;
        $scope.dueAmount = 0;
        $scope.changeAmount = 0;
        $scope.searchBoxText;

        quantity = 0;
        unitPrice = 0;
        taxAmount = 0;
        subTotal = 0;
        totalTax = 0;
        total = 0;

        itemTaxrate = 0;
        itemTaxAmount = 0;
        realItemTaxAmount = 0;

        $("#reference_no").val("");
        $("#order-tax").val(0);
        $("#discount-amount").val(0);
        $("#shipping-amount").val(0);
        $("#others-charge").val(0);
        $("#purchase-note").val("");
        $("#sup_id").val("").trigger("change");
        $("#customer_id").val("").trigger("change");
        $("#product-table tbody").empty();
        $("#total-amount-view").text("0.00");
        $("#paid-amount").val(0);
        $("#image_thumb img").attr("src", "../assets/itsolution24/img/noimage.jpg");
        $("#image").val("");
    });



    // Append email button into datatable buttons
    if (window.sendReportEmail) { $(".dt-buttons").append("<button id=\"email-btn\" class=\"btn btn-default buttons-email\" tabindex=\"0\" aria-controls=\"purchase-purchase-list\" type=\"button\" title=\"Email\"><span><i class=\"fa fa-envelope\"></i></span></button>"); };
    
    // Send purchase list through email
    $("#email-btn").on( "click", function (e) {
        e.stopPropagation();
        e.preventDefault();
        dt.find("thead th:nth-child(9), thead th:nth-child(10), thead th:nth-child(11), thead th:nth-child(12), thead th:nth-child(13), tbody th:nth-child(9), tbody th:nth-child(10), tbody td:nth-child(11) tbody td:nth-child(12), tbody td:nth-child(13), tfoot td:nth-child(9), tfoot td:nth-child(10), tfoot td:nth-child(11), tfoot th:nth-child(12) tfoot th:nth-child(13)").addClass("hide-in-mail");
        var thehtml = dt.html();
        EmailModal({template: "default", subject: "Purchase Invoice Listing", title:"Purchase Invoice Listing", html: thehtml});
    });
}]);