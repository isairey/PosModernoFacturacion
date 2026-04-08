window.angularApp.factory("BrandCreateModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function($scope) {
        var uibBrandModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeBrandCreateModal();\" type=\"button\" class=\"close brand-create-modal\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-plus\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/brand.php?action_type=CREATE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Create New Brand";
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 500);

                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                // Submit Box Form
                $(document).delegate("#create-brand-submit", "click", function(e) {
                    e.stopImmediatePropagation();
                    e.stopPropagation();
                    e.preventDefault();

                    var $tag = $(this);
                    var $btn = $tag.button("loading");
                    var form = $($tag.data("form"));
                    form.find(".alert").remove();
                    var actionUrl = form.attr("action");
                    
                    $http({
                        url: window.baseUrl + "/_inc/" + actionUrl,
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
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {

                            // close modalwindow
                            $scope.closeBrandCreateModal();
                            $(document).find(".close").trigger("click");

                            // insert brand into select2
                            var select = $(document).find('#brand_id');
                            if (select.length) {
                                var option = $('<option></option>').
                                     attr('selected', true).
                                     text(response.data.brand.brand_name).
                                     val(response.data.id);
                                option.appendTo(select);
                                select.trigger('change');
                            }

                            // increase brand count
                            var brandCount = $(document).find("#brand-count h3");
                            if (brandCount) {
                                brandCount.text(parseInt(brandCount.text()) + 1);
                            }

                            // Callback
                            if ($scope.BrandCreateModalCallback) {
                                $scope.BrandCreateModalCallback($scope);
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

                $scope.closeBrandCreateModal = function () {
                    uibBrandModalInstance.dismiss("cancel");
                };
            },
            scope: $scope,
            size: "md",
            backdrop  : "static",
            keyboard: true,
        });

        uibBrandModalInstance.result.catch(function () { 
            uibBrandModalInstance.close(); 
        });
    };
}]);