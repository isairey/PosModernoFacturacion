window.angularApp.factory("HoldingOrderDetailsModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function($scope) {
        $scope.showOrderDetails = false;
        $scope.refID = '';
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeHoldingOrderDetailsModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                            "<h3 class=\"modal-title\" id=\"modal-title\">" +
                                "<span class=\"fa fa-fw fa-list\"></span> {{ modal_title }}" +
                            "</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\" style=\"padding: 0px;overflow-x: hidden;\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>" +
                        "<div class=\"modal-footer\">" +
                            "<button ng-click=\"editTheOrder();\" type=\"button\" class=\"btn btn-info r-50\"><span class=\"fa fa-fw fa-edit\"></span>Edit The Order</button>" +
                            "<button ng-click=\"closeHoldingOrderDetailsModal();\" type=\"button\" class=\"btn btn-danger r-50\"><span class=\"fa fa-fw fa-close\"></span>Close</button>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $scope.loadModal = function() {
                    $(document).find("body").addClass("overlay-loader");
                    $http({
                      url: window.baseUrl + "/_inc/holding_order.php?action_type=HOLDINGORDERDETAILSMODAL",
                      method: "GET"
                    })
                    .then(function(response, status, headers, config) {
                        $scope.modal_title = "Hold Orders";
                        $scope.rawHtml = $sce.trustAsHtml(response.data.html); 
                        $scope.orders = response.data.orders;
                        $("#total-holding_order").text($scope.orders.length);
                        setTimeout(function() {
                            storeApp.bootBooxHeightAdjustment();
                            $(document).find("body").removeClass("overlay-loader");
                        }, 500);
                    }, function(response) {
                       window.swal("Oops!", response.data.errorMsg, "error");
                       $(document).find("body").removeClass("overlay-loader");
                    });
                };
                $scope.loadModal();

                $scope.loadHoldingOrderDetails = function(refNo) {
                    $(document).find("body").addClass("overlay-loader");
                    $(".holding-order-item").removeClass("active");
                    $("#holding-order-item-"+refNo).addClass("active");
                    $scope.modal_title = "Hold Orders > " + refNo;
                    $http({
                      url: window.baseUrl + "/_inc/holding_order.php?action_type=HOLDINGORDERDETAILS&ref_no="+refNo,
                      method: "GET"
                    })
                    .then(function(response, status, headers, config) {
                        $scope.showOrderDetails = true;
                        $scope.orderDetails = response.data.order;
                        $scope.orderDetails.items = response.data.items;
                        $scope.refID = response.data.order.ref_no;
                        $(document).find("body").removeClass("overlay-loader");
                    }, function(response) {
                       window.swal("Oops!", response.data.errorMsg, "error");
                       $(document).find("body").removeClass("overlay-loader");
                    });
                };

                $scope.closeHoldingOrderDetailsModal = function () {
                    $uibModalInstance.dismiss("cancel");
                };

                $scope.editTheOrder = function() {
                    if (!$scope.refID) {
                        window.swal("Oops!", "Please, select an order", "error");
                        return false;
                    }
                    window.location = window.baseUrl+"/admin/pos.php?holding_id="+$scope.refID;
                };

                $scope.deleteHoldingOrder = function(refNo) {
                    window.swal({
                    title: "Delete!",
                    text: "Are you sure?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                    })
                    .then(function(willDelete) {
                        if (willDelete) {
                            $http({
                                url: window.baseUrl + "/_inc/holding_order.php?action_type=DELETE",
                                method: "POST",
                                data: "ref_no="+refNo,
                                cache: false,
                                processData: false,
                                contentType: false,
                                dataType: "json"
                            }).
                            then(function(response) {
                                $(document).find(".modal").removeClass("overlay-loader");
                                $scope.loadModal();
                                window.swal("Success!",  response.data.msg, "success")
                                .then(function(value) {
                                    if (window.store.sound_effect == 1) {
                                        window.storeApp.playSound("modify.mp3");
                                    };
                                });
                            }, function(response) {
                                if (window.store.sound_effect == 1) {
                                    window.storeApp.playSound("error.mp3");
                                }
                                window.swal("Oops!", response.data.errorMsg, "error");
                                $(document).find(".modal").removeClass("overlay-loader");
                            });
                        }
                    });
                };
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