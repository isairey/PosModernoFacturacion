window.angularApp.factory("QuotationViewModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function (quotation) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template:   "<div id=\"data-modal\" class=\"modal-inner\">" +
                            "<div class=\"modal-header\">" +
								"<button ng-click=\"closeQuotationViewModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
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
                  url: window.baseUrl + "/_inc/quotation.php?reference_no=" + quotation.reference_no + '&action_type=VIEW',
                  method: "GET"
                })
                .then(function (response, status, headers, config) {
                    $scope.modal_title = "Quotation > " + quotation.reference_no;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);
                }, function (response) {
                   window.swal("Oops!", response.data.errorMsg, "error")
                    .then(function() {
                        $scope.closeQuotationViewModal();
                    });
                });
                $scope.closeQuotationViewModal = function () {
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