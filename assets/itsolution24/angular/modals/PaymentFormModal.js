window.angularApp.factory("PaymentFormModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "InvoiceViewModal", "PrintReceiptModal", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, InvoiceViewModal, PrintReceiptModal, $scope) {
    return function($scope) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closePaymentFormModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                            "<h3 class=\"modal-title\" id=\"modal-title\">" + 
                                "<span class=\"fa fa-fw fa-list\"></span> {{ modal_title }}" +
                            "</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\" style=\"padding: 0px;overflow-x: hidden;\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>" +
                        "<div class=\"modal-footer\">" +
                            "<button ng-click=\"closePaymentFormModal();\" type=\"button\" class=\"btn btn-danger radius-50\"><i class=\"fa fa-fw fa-close\"></i> Close</button>" +
                            "<button  ng-click=\"checkout();\" type=\"button\" class=\"btn btn-success radius-50\"><i class=\"fa fa-fw fa-money\"></i> Checkout &rarr;</button>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $(document).find("body").addClass("overlay-loader");
                $http({
                  url: window.baseUrl + "/_inc/template/payment_form.php?customer_id="+$scope.customerId,
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Payment > " + $scope.customerName;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);
                    setTimeout(function() {
                        storeApp.bootBooxHeightAdjustment();
                        $(document).find("body").removeClass("overlay-loader");
                    }, 500);                 
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                   $(document).find("body").removeClass("overlay-loader");
                });

                $scope.sellWithInstallment = function() {
                    if ($scope.isInstallmentOrder == 0) {
                        $scope.isInstallmentOrder = 1;
                        $("#activeSellWithInstallmentBtn").removeClass("btn-default");
                        $("#activeSellWithInstallmentBtn").addClass("btn-success");
                    } else {
                        $scope.isInstallmentOrder = 0;
                        $("#activeSellWithInstallmentBtn").removeClass("btn-success");
                        $("#activeSellWithInstallmentBtn").addClass("btn-default");
                    }
                }

                $scope.selectPaymentMethod = function(pmethodId,pmethodCode) {
                    $(document).find("body").addClass("overlay-loader");
                    $scope.pmethodId = pmethodId;
                    $scope.pmethodCode = pmethodCode;
                    $(".pmethod_item").removeClass("active");
                    $("#pmethod_"+pmethodId).addClass("active");

                    $http({
                      url: window.baseUrl + "/_inc/payment.php?action_type=FIELD&pmethod_id=" + pmethodId,
                      method: "GET"
                    })
                    .then(function(response, status, headers, config) {
                        $scope.modal_title = "Payment > " + $scope.customerName;
                        $scope.rawPaymentMethodHtml = $sce.trustAsHtml(response.data);
                        if ($scope.pmethodCode == 'credit') {
                            if (parseFloat($scope.customerBalance) < parseFloat($scope.totalPayable)) {
                                window.toastr.error("Insufficient Balance!", "Warning!");
                            } else {
                                $scope.paidAmount = $scope.totalPayable;
                            }
                        }
                        $(document).find("body").removeClass("overlay-loader");
                    }, function(response) {
                       window.swal("Oops!", response.data.errorMsg, "error");
                       $(document).find("body").removeClass("overlay-loader");
                    });
                };

                $scope.checkout = function() {
                    $(document).find(".modal").addClass("overlay-loader");
                    var form = $("#checkout-form");
                    var actionUrl = form.attr("action");
                    var data = form.serialize();
                    $http({
                        url: window.baseUrl + "/_inc/" + actionUrl,
                        method: "POST",
                        data: data,
                        cache: false,
                        processData: false,
                        contentType: false,
                        dataType: "json"
                    }).
                    then(function(response) {
                        window.onbeforeunload = null;
                        $(document).find(".modal").removeClass("overlay-loader");
                        $scope.invoiceId = response.data.invoice_id;
                        $scope.invoiceInfo = response.data.invoice_info;
                        $scope.invoiceItems = response.data.invoice_items;
                        $scope.done = true;
                        if (window.store.sound_effect == 1) {
                            window.storeApp.playSound("modify.mp3");
                        }
                        if (window.store.auto_print == 1 && window.store.remote_printing == 1) {
                            PrintReceiptModal($scope);
                        }
                        
                        if (window.getParameterByName("holding_id") || window.getParameterByName("qref")) {
                            localStorage.setItem("swal",
                                window.swal({
                                  title: "Success!",
                                  text:  "Invoice ID: "+$scope.invoiceId,
                                  type: "success",
                                  timer: 3000,
                                  showConfirmButton: false
                                })
                                .then(function (willDelete) {
                                    if (willDelete) {
                                        window.location = "pos.php";
                                    }
                                })
                            );
                        } else {
                            if (window.settings.after_sell_page == 'receipt_in_new_window') {
                                window.open(window.baseUrl + "/admin/view_invoice.php?invoice_id=" + $scope.invoiceId);
                            } else if (window.settings.after_sell_page == 'receipt_in_popup') {
                                InvoiceViewModal({'invoice_id':$scope.invoiceId});
                            } else if (window.settings.after_sell_page == 'toastr_msg') {
                                window.toastr.success("ID: "+$scope.invoiceId, "Success!");
                            } else if (window.settings.after_sell_page == 'sweet_alert_msg') {
                                window.swal("Success.", "ID: "+$scope.invoiceId, "success");
                            } else {
                                window.swal("Success.", "ID: "+$scope.invoiceId, "success");
                            }
                        }

                        if ($scope.customerMobileNumber && window.settings.invoice_auto_sms == '1') {
                            $http({
                                url: window.baseUrl + "/_inc/sms/index.php",
                                method: "POST",
                                data: "phone_number="+$scope.customerMobileNumber+"&invoice_id="+$scope.invoiceId+"&action_type=SEND",
                                cache: false,
                                processData: false,
                                contentType: false,
                                dataType: "json"
                            }).
                            then(function(response) {
                                window.toastr.success("SMS sent to the number: " + $scope.customerMobileNumber, "Success!");
                            }, function(response) {
                                window.swal("Oops!", response.data.errorMsg, "error");
                            });
                        }

                        $scope.resetPos();
                        $scope.closePaymentFormModal();
                    }, function(response) {
                        if (window.store.sound_effect == 1) {
                            window.storeApp.playSound("error.mp3");
                        }
                        window.swal("Oops!", response.data.errorMsg, "error");
                        $(document).find(".modal").removeClass("overlay-loader");
                    });
                };

                $scope.checkoutWithFullPaid = function() {
                    $scope.paidAmount = $scope.totalPayable;
                    setTimeout(function() {
                        $scope.checkout();
                    }, 100);
                };

                $scope.checkoutWithFullDue = function() {
                    $scope.paidAmount = 0;
                    setTimeout(function() {
                        $scope.checkout();
                    }, 100);
                };

                $scope.checkoutWhilePressEnter = function($event) {
                    if(($event.keyCode || $event.which) == 13){
                        $scope.checkout();
                    }
                };

                $scope.closePaymentFormModal = function () {
                    $uibModalInstance.dismiss("cancel");
                };

                $scope.$watch('installmentInterestPercentage', function() {
                    $scope.installmentInterestAmount = ($scope.installmentInterestPercentage/100)*$scope.payable;
                    $scope._calcTotalPayable($scope);
                }, true);
            },
            scope: $scope,
            size: "lg",
            backdrop  : "static",
            keyboard: true,
        });

        uibModalInstance.result.catch(function () { 
            uibModalInstance.close(); 
        });
    };
}]);