window.angularApp.factory("BannerEditModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(banner) {
        var bannerId;
        $scope.imgArray = [];
        $scope.imgSerial = 0;
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeBannerEditModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-pencil\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/banner.php?id=" + banner.id + "&action_type=EDIT",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = banner.name;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();

                        // Banner images
                        $http({
                          url: window.baseUrl + "/_inc/ajax.php?id=" + banner.id + "&type=BANNERIMAGES",
                          method: "GET"
                        })
                        .then(function(response, status, headers, config) {
                            window.angular.forEach(response.data.images, function(item, key) {
                                $scope.imgSerial++;
                                var item = {
                                    'id' : $scope.imgSerial,
                                    'url' : item.url,
                                    'link' : item.link,
                                    'sort_order' : item.sort_order,
                                };
                                $scope.imgArray.push(item);
                            });
                        }, function(data) {
                           window.swal("Oops!", "an error occured!", "error");
                        });

                        $scope.remoteImageItem = function(index)
                        {
                            $scope.imgArray.splice(index, 1);
                        }


                    }, 100);

                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                $(document).delegate("#banner-update", "click", function(e) {
                    
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
                        window.swal({
                          title: "Success!",
                          text: response.data.msg,
                          icon: "success",
                          buttons: true,
                          dangerMode: false,
                        })
                        .then(function (willDelete) {
                            if (willDelete) {
                                $scope.closeBannerEditModal();
                                $(document).find(".close").trigger("click");
                                bannerId = response.data.id;
                                $(datatable).DataTable().ajax.reload(function(json) {
                                    if ($("#row_"+bannerId).length) {
                                        $("#row_"+bannerId).flash("yellow", 5000);
                                    }
                                }, false);

                            } else {
                                $(datatable).DataTable().ajax.reload(null, false);
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

                $scope.addImageItem = function() {
                    $scope.imgSerial++;
                    var item = {
                        'id' : $scope.imgSerial,
                        'url' : '',
                        'link' : '',
                        'sort_order' : $scope.imgSerial,
                    };
                    $scope.imgArray.push(item);
                };
                $(document).delegate(".open-filemanager", "click", function(e) {
                    e.stopImmediatePropagation();
                    e.stopPropagation();
                    e.preventDefault();
                    var id = $(this).data('imageid');
                    POSFilemanagerModal({target:'image'+id, thumb:'thumb'+id});
                });

                $scope.closeBannerEditModal = function () {
                    $uibModalInstance.dismiss("cancel");
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