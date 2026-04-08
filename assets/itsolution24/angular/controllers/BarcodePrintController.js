window.angularApp.controller("BarcodePrintController", [
"$scope",
"API_URL",
"window",
"jQuery",
"$compile",
"$uibModal",
"$http",
"$sce",
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
EmailModal
) {

    "use strict";

    var id;
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
                    itemAvailable: names[4],
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

    $(document).on("change keyup blur", ".quantity, .unit-price", function () {
        id = $(this).data("id");
        totalTax = 0;
        total = 0;
    });

    $(document).delegate(".remove", "click", function () {
        id = $(this).data("id");
        $("#"+id).remove();
    });

    var quantity;
    $scope.addProduct = function(data) {
        $(document).find("#product-table .noproduct").remove();
        var html = "<tr id=\""+data.itemId+"\" class=\""+data.itemId+" success\" data-item-id=\""+data.itemId+"\">";
        html += "<td class=\"text-center\" style=\"min-width:100px;\" data-title=\"Product Name\">";
        html += "<input name=\"products["+data.itemId+"][item_id]\" type=\"hidden\" class=\"item-id\" value=\""+data.itemId+"\">";
        html += "<input name=\"products["+data.itemId+"][item_name]\" type=\"hidden\" class=\"item-name\" value=\""+data.itemName+"\">";
        html += "<span class=\"name\" id=\"name-"+data.itemId+"\">"+data.itemName+"-"+data.itemCode+"</span>";
        html += "</td>";
        html += "<td class=\"text-center\" style=\"padding:2px;\" data-title=\"Available\">";
        html += window.formatDecimal(data.itemAvailable);
        html += "</td>";
        html += "<td style=\"padding:2px;\" data-title=\"Quantity\">";
        html += "<input class=\"form-control input-sm text-center quantity\" name=\"products["+data.itemId+"][quantity]\" type=\"number\" value=\""+parseInt(data.itemAvailable)+"\" data-id=\""+data.itemId+"\" id=\"quantity-"+data.itemId+"\" onclick=\"this.select();\" onKeyUp=\"if(this.value<=0){this.value=1;}\">";
        html += "</td>";
        html += "<td class=\"text-center\">";
        html += "<i class=\"fa fa-close text-red pointer remove\" data-id=\""+data.itemId+"\" title=\"Remove\"></i>";
        html += "</td>";
        html += "</tr>";

        // Update existing if find
        if ($("#"+data.itemId).length) {
            quantity = $(document).find("#quantity-"+data.itemId);
            quantity.val(parseFloat(quantity.val()) + 1);
        } else {
            $(document).find("#product-table tbody").append(html);
        }
    };

    if (window.getParameterByName("p_code")) {
        $("#add_item").val(window.getParameterByName("p_code"));
        $("#add_item").trigger("focus");
    }

    $(document).delegate("#reset", "click", function (e) {
        e.preventDefault();
    });

}]);