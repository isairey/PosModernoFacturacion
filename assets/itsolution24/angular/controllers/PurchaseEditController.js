window.angularApp.controller("PurchaseEditController", [
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
    EmailModal
) {
    "use strict";


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


    // Edit purchase
    $(document).delegate("#update-purchase-submit", "click", function(e) {
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
                    window.location = window.baseUrl+'/admin/purchase.php';
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








    //=================================================================


    var id;
    var sup_id;
    var sup_name;
    var quantity = 0;
    var unitPrice = 0;
    var taxAmount = 0;
    var subTotal = 0;
    var totalTax = 0;
    var total = 0;

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
                            label: code[autoTypeNo].replace(/&amp;/g, "&"),
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
                    itemQuantity: names[4],
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
            }, 
            close: function () {
                $(document).find(".autocomplete-product").blur();
                $(document).find(".autocomplete-product").val("");
            },
        }).bind("focus", function() { 
            $(this).autocomplete("search"); 
        });
    });

    $(document).on("change keyup blur", ".quantity, .unit-price",function (){
        id = $(this).data("id");
        totalTax = 0;
        total = 0;
        $scope.calculate(id);
    });

    $(document).delegate(".remove", "click", function () {
        id = $(this).data("id");
        $("#"+id).remove();
        $(".rm-in-action").remove();
        totalTax = 0;
        total = 0;
        $scope.calculate(id);
    });

    var itemTaxMethod;
    var itemTaxrate;
    var itemTaxAmount;
    var realItemTaxAmount;
    $scope.calculate = function (id) {
        quantity = $(document).find("#quantity-"+id);
        unitPrice = $(document).find("#unit-price-"+id);
        itemTaxMethod = $(document).find("#tax-method-"+id);
        itemTaxrate = $(document).find("#taxrate-"+id);
        itemTaxAmount = $(document).find("#tax-amount-"+id);
        taxAmount = $(document).find("#tax-amount-"+id);
        realItemTaxAmount = parseFloat((itemTaxrate.val() / 100 ) * parseFloat(unitPrice.val()));
        itemTaxAmount.val(parseFloat(quantity.val()) * realItemTaxAmount);
        taxAmount.text(parseFloat(parseFloat(quantity.val()) * realItemTaxAmount).toFixed(2));
        $(document).find(".tax").each(function (i, obj) {
            totalTax = parseFloat($(this).text())*parseFloat(quantity.val());
        });
        subTotal = $(document).find("#subtotal-"+id);
        if (itemTaxMethod.val() == 'exclusive') {
            subTotal.text(parseFloat((parseFloat(quantity.val()) * parseFloat(unitPrice.val())) + parseFloat(taxAmount.text())).toFixed(2));
        } else {
            subTotal.text(parseFloat(parseFloat(quantity.val()) * parseFloat(unitPrice.val())).toFixed(2));
        }
        $(document).find(".subtotal-").each(function (i, obj) {
            total = parseFloat(total) + parseFloat($(this).text());
        });

        $("#total-tax").val(totalTax);
        $("#total-amount").val(total);
        $("#total-amount-view").text(window.formatDecimal(total,2));
    };

    // Add Product
    var sellPrice = 0;
    $scope.addProduct = function(data) {
        if (data.itemTaxMethod == 'exclusive') {
            sellPrice = parseFloat(data.itemSellPrice) + parseFloat(data.itemTaxAmount);
        } else {
            sellPrice = data.itemSellPrice;
        }
        var html = "<tr id=\""+data.itemId+"\" class=\""+data.itemId+" bg-gray\" data-item-id=\""+data.itemId+"\">";
        html += "<td class=\"text-center\" style=\"min-width:100px;\" data-title=\"Product Name\">";
        html += "<input name=\"products["+data.itemId+"][item_id]\" type=\"hidden\" class=\"item-id\" value=\""+data.itemId+"\">";
        html += "<input name=\"products["+data.itemId+"][item_name]\" type=\"hidden\" class=\"item-name\" value=\""+data.itemName+"\">";
        html += "<input name=\"products["+data.itemId+"][category_id]\" type=\"hidden\" class=\"categoryid\" value=\""+data.categoryId+"\">";
        html += "<span class=\"name\" id=\"name-"+data.itemId+"\">"+data.itemName+"-"+data.itemCode+"</span>";
        html += "</td>";
        html += "<td class=\"text-center\" data-title=\"Available\">";
        html += "<span class=\"text-center available\" id=\"available-"+data.itemId+"\">"+data.itemQuantity+"</span>";
        html += "</td>";
        html += "<td style=\"padding:2px;\" data-title=\"Product Name\">";
        html += "<input class=\"form-control input-sm text-center quantity\" name=\"products["+data.itemId+"][quantity]\" type=\"number\" value=\"1\" data-id=\""+data.itemId+"\" id=\"quantity-"+data.itemId+"\" onclick=\"this.select();\" onKeyUp=\"if(this.value<=0){this.value=1;}\">";
        html += "</td>";
        html += "<td style=\"padding:2px;min-width:80px;\" data-title=\"Unit Price\">";
        html += "<input id=\"unit-price-"+data.itemId+"\" class=\"form-control input-sm text-center unit-price\" type=\"number\" name=\"products["+data.itemId+"][unit_price]\" type=\"text\" value=\""+data.itemSellPrice+"\" data-id=\""+data.itemId+"\" data-item=\""+data.itemId+"\" onclick=\"this.select();\" onKeyUp=\"if(this.value<0){this.value=1;}\">";
        html += "</td>";
        html += "<td class=\"text-center\" data-title=\"Tax Amount\">";
        html += "<input id=\"tax-method-"+data.itemId+"\" name=\"products["+data.itemId+"][tax_method]\" type=\"hidden\" value=\""+data.itemTaxMethod+"\">";
        html += "<input id=\"taxrate-"+data.itemId+"\" name=\"products["+data.itemId+"][taxrate]\" type=\"hidden\" value=\""+data.itemTaxrate+"\">";
        html += "<input id=\"tax-amount-"+data.itemId+"\" name=\"products["+data.itemId+"][tax_amount]\" type=\"hidden\" value=\""+data.itemTaxAmount+"\">";
        html += "<span class=\"tax tax-amount-view\">"+window.formatDecimal(data.itemTaxAmount,2)+"</span>";
        html += "</td>";
        html += "<td class=\"text-right\" data-title=\"Total\">";
        html += "<span class=\"subtotal-\" id=\"subtotal-"+data.itemId+"\">"+window.formatDecimal(sellPrice,2)+"</span>";
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
            unitPrice = $(document).find("#unit-price-"+data.itemId);
            itemTaxAmount = $(document).find("#tax-amount-"+data.itemId);
            taxAmount = $(document).find("#tax-amount-"+data.itemId);
            subTotal = $(document).find("#subtotal-"+data.itemId);
            quantity.val(parseFloat(quantity.val()) + 1);
            itemTaxAmount.val(parseFloat(taxAmount.text()) + parseFloat(data.itemTaxAmount));
            taxAmount.text(window.formatDecimal(parseFloat(taxAmount.text()) + parseFloat(data.itemTaxAmount),2));
            subTotal.text(window.formatDecimal(parseFloat(subTotal.text()) + parseFloat(sellPrice),2));
        } else {
            $(document).find("#product-table tbody").append(html);
        }

        $("#total-tax").val(totalTax);
        $("#total-amount").val(total);
        $("#total-amount-view").text(window.formatDecimal(total,2));
    };

    //=================================================================









}]);