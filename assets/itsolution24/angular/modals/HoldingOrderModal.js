window.angularApp.factory("HoldingOrderModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function($scope) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeHoldingOrderModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-crosshairs\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>" +
                        "<div style=\"text-align:center;\" class=\"modal-footer\">" +
                            "<button ng-click=\"putOrderOnHold();\" type=\"button\" class=\"btn btn-md btn-info radius-50\" style=\"border-radius:50px\"><span class=\"fa fa-fw fa-save\"></span> Save &rarr;</button>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {

                if (window.getParameterByName("holding_id")) {
                    window.swal({
                      title: "Oops!",
                      text: "Please, cancel existing holding order first",
                      icon: "error",
                      buttons: true,
                      dangerMode: false,
                    })
                    .then(function (willDelete) {
                        if (willDelete) {
                            window.location = window.baseUrl+"/admin/pos.php";
                        } else {
                            $scope.closeHoldingOrderModal();
                        }
                    });
                } else {
                    $http({
                      url: window.baseUrl + "/_inc/template/holding_order_form.php",
                      method: "GET"
                    })
                    .then(function(response, status, headers, config) {
                        $scope.modal_title = "Hold Order";
                        $scope.rawHtml = $sce.trustAsHtml(response.data);
                        setTimeout(function() {
                            storeApp.bootBooxHeightAdjustment();
                            $("#order-title").focus();
                        }, 500);                   
                    }, function(response) {
                       window.swal("Oops!", response.data.errorMsg, "error");
                    });
                }

                $scope.putOrderOnHold = function() {
                    $scope.done = false;
                    $scope.paidAmount = 0;
                    $(document).find(".modal").addClass("overlay-loader");
                    $scope.balance = parseFloat($scope.totalPayable);

                    // summit order form
                    var form = $("#order-place-form");
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
                        $(document).find(".modal").removeClass("overlay-loader");
                        $scope.done = true;
                        $scope.showProductList();
                        window.swal("Success!",  response.data.msg, "success")
                        .then(function(value) {
                            $scope.resetPos();
                            $(".modal").remove();
                            $(".modal-backdrop").remove();
                            $('body').removeClass("modal-open");
                            if (window.store.sound_effect == 1) {
                                window.storeApp.playSound("modify.mp3");
                            }
                        });
                    }, function(response) {
                        if (window.store.sound_effect == 1) {
                            window.storeApp.playSound("error.mp3");
                        }
                        window.swal("Oops!", response.data.errorMsg, "error");
                        $(document).find(".modal").removeClass("overlay-loader");
                    });

                };

                $scope.holdOrderWhilePressEnter = function($event) {
                    if(($event.keyCode || $event.which) == 13){
                        $event.preventDefault();
                        $event.stopPropagation();
                        $scope.putOrderOnHold();
                    }
                };

                $scope.closeHoldingOrderModal = function () {
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