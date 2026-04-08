window.angularApp.factory("InstallmentPaymentModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function($parentScope) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button id=\"installment_payment_modal_close\" ng-click=\"closeInstallmentPaymentModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-pencil\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile='rawHtml'>Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: API_URL + "/_inc/installment.php?id=" + $parentScope.id + "&action_type=PAY",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    if ($parentScope.invoice_id && $parentScope.invoice_id != 'undefined') {
                        $scope.modal_title = 'Payment > ' + $parentScope.invoice_id;
                    } else {
                        $scope.modal_title = 'Payment';
                    }
                    $scope.rawHtml = $sce.trustAsHtml(response.data);
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                $(document).delegate("#payment-update", "click", function(e) {
                    e.stopImmediatePropagation();
                    e.stopPropagation();
                    e.preventDefault();

                    var $tag = $(this);
                    var $btn = $tag.button("loading");
                    var form = $($tag.data("form"));
                    var datatable = $tag.data("datatable");
                    form.find(".alert").remove();
                    var actionUrl = form.attr("action");
                    
                    $http({
                        url: API_URL + "/_inc/" + actionUrl,
                        method: "POST",
                        data: form.serialize(),
                        cache: false,
                        processData: false,
                        contentType: false,
                        dataType: "json"
                    }).
                    then(function(response) {

                        $btn.button("reset");
                        var alertMsg = "<div class=\"alert alert-success\">";
                        alertMsg += "<p><i class=\"fa fa-check\"></i> " + response.data.msg + ".</p>";
                        alertMsg += "</div>";
                        form.find(".box-body").before(alertMsg);

                        // Alert
                        window.swal({
                          title: "Success!",
                          text: response.data.msg,
                          icon: "success",
                          buttons: true,
                          dangerMode: false,
                        })
                        .then(function (willDelete) {
                            if (willDelete) {
                                if ($parentScope.loadInstallmentView) {
                                    $parentScope.loadInstallmentView();
                                }
                                if ($("#payment-payment-list").length) {
                                    $("#payment-payment-list").DataTable().ajax.reload(function(json) {
                                        if ($("#row_"+$parentScope.id).length) {
                                            $("#row_"+$parentScope.id).flash("yellow", 5000);
                                        }
                                    }, false);
                                }
                                $parentScope.closeInstallmentViewModal
                                $(document).find("#installment_payment_modal_close").trigger("click");
                            }
                        });

                    }, function(response) {

                        $btn.button("reset");
                        var alertMsg = "<div class=\"alert alert-danger\">";
                        window.angular.forEach(response.data, function(value, key) {
                            alertMsg += "<p><i class=\"fa fa-warning\"></i> " + value + ".</p>";
                        });
                        alertMsg += "</div>";
                        form.find(".box-body").before(alertMsg);
                        $(":input[type=\"button\"]").prop("disabled", false);
                        window.swal("Oops!", response.data.errorMsg, "error");
                    });

                });

                $scope.closeInstallmentPaymentModal = function () {
                    $uibModalInstance.dismiss("cancel");
                };
            },
            scope: $scope,
            size: "md",
            backdrop  : "static",
            keyboard: true,
        });

        uibModalInstance.result.catch(function () { 
            uibModalInstance.close(); 
        });
    };
}]);