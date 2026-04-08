window.angularApp.factory("InvoiceViewModal", [
    "API_URL",
    "window",
    "jQuery",
    "$http",
    "$uibModal",
    "$sce",
    "InvoiceSMSModal",
    "EmailModal",
    "$rootScope", 
function (API_URL,
    window,
    $,
    $http,
    $uibModal,
    $sce,
    InvoiceSMSModal,
    EmailModal,
    $scope
) {
    return function (invoice) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeInvoiceViewModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                            "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-eye\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/invoice.php?invoice_id=" + invoice.invoice_id + '&action_type=INVOICEVIEW',
                  method: "GET"
                })
                .then(function (response, status, headers, config) {
                    $scope.modal_title = invoice.invoice_id;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);
                }, function (response) {
                   window.swal("Oops!", response.data.errorMsg, "error")
                    .then(function() {
                        $scope.closeInvoiceViewModal();
                    });
                });

                $scope.InvoiceSMSModal = function() {
                    InvoiceSMSModal($scope);
                }

                $(document).delegate("#sms-btn", "click", function (e) {
                    e.stopPropagation();
                    var invoiceID = $(this).data("invoiceid");
                    $scope.invoiceID = invoiceID;
                    InvoiceSMSModal($scope);
                });

                $(document).delegate("#email-btn", "click", function (e) {
                    e.stopPropagation();

                    var recipientName = $(this).data("customername");
                    var thehtml = $("#invoice").html();
                    var invoice = {
                        template: "invoice", 
                        styles:$($("#invoice").html()).find("#styles").text(),
                        subject: "Invoice#"+$(this).data("invoiceid"), 
                        title: "Send Invoice through Email", 
                        recipientName: recipientName, 
                        senderName: window.store.name, 
                        html: thehtml
                    };
                    EmailModal(invoice);
                });

                $scope.closeInvoiceViewModal = function () {
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