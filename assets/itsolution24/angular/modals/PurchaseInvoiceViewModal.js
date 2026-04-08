window.angularApp.factory("PurchaseInvoiceViewModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function (invoice) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template:   "<div id=\"data-modal\" class=\"modal-inner\">" +
                            "<div class=\"modal-header\">" +
								"<button ng-click=\"closePurchaseInvoiceViewModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
							   "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-eye\"></span> {{ modal_title }}</h3>" +
							"</div>" +
							"<div class=\"modal-body\" id=\"modal-body\">" +
								"<div bind-html-compile=\"rawHtml\">Loading...</div>" +
							"</div>" +
                            "<div class=\"modal-footer\" style=\"text-align:center;\">" +
                                "<button onClick=\"window.printContent('data-modal', {headline:'<small>Printed on: "+window.formatDate(new Date())+"</small>',screenSize:'fullScreen'})\" class=\"btn btn-primary\"><span class=\"fa fa-fw fa-print\"></span> Print</button>" +
                            "</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/purchase.php?invoice_id=" + invoice.invoice_id + '&action_type=VIEW',
                  method: "GET"
                })
                .then(function (response, status, headers, config) {
                    $scope.modal_title = "Purchase > " + invoice.invoice_id;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);
                }, function (response) {
                   window.swal("Oops!", response.data.errorMsg, "error")
                    .then(function() {
                        $scope.closePurchaseInvoiceViewModal();
                    });
                });
                $scope.closePurchaseInvoiceViewModal = function () {
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