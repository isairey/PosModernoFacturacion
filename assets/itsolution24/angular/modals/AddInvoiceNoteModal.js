window.angularApp.factory("AddInvoiceNoteModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function($scope) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeAddNoteModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-pencil\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<input type=\"text\" id=\"note\" class=\"form-control\" rows=\"3\" value=\"{{ invoiceNote }}\" placeholder=\"Type any note here...\">" +
                        "</div>" +
                        "<div class=\"modal-footer\">" +
                            "<button ng-click=\"clear();\" class=\"btn btn-danger\" type=\"button\"><span class=\"fa fa-fw fa-close\"></span> Clear&nbsp;</button>" +
                            "<button ng-click=\"closeAddNoteModal();\" id=\"reset-btn\" name=\"reset-btn\" class=\"btn btn-success\"><span class=\"fa fa-fw fa-check\"></span> SAVE </button>&nbsp;&nbsp;" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $scope.modal_title = "Add Note";            
                 var invoiceNote;
                $(document).on("change keyup blur", "#note", function () {
                    var $this = $(this);
                    invoiceNote = $this.val();
                    $("#invoice-note").data("note", invoiceNote);
                    $scope.invoiceNote = invoiceNote;
                });
                $scope.closeAddNoteModal = function () {
                    $uibModalInstance.dismiss("cancel");
                };
                $scope.clear = function () {
                    $("#invoice-note").data("note", "");
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