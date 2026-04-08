window.angularApp.factory("ProductEditModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "POSFilemanagerModal", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, POSFilemanagerModal, $scope) {
    return function(product) {
        var productId;
        $scope.imgArray = [];
        $scope.imgSerial = 0;
        var uibModalInstance = $uibModal.open({
            // animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeProductEditModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-pencil\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/product.php?p_id=" + product.p_id + "&action_type=EDIT",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = product.p_name;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.datePicker();
                        window.storeApp.select2();
                        storeApp.intiTinymce("#edit_description");

                        // Generate random number
                        $(".random_num").click(function(){
                            $(this).parent(".input-group").children("input").val(window.storeApp.generateCardNo(8));
                        });

                        $scope.productType = $("#p_type").select2('val');
                        $scope.setFieldAsProductType($scope.productType);

                        // Product images
                        $http({
                          url: window.baseUrl + "/_inc/ajax.php?p_id=" + product.p_id + "&type=PRODUCTIMAGES",
                          method: "GET"
                        })
                        .then(function(response, status, headers, config) {
                            window.angular.forEach(response.data.images, function(item, key) {
                                $scope.imgSerial++;
                                var item = {
                                    'id' : $scope.imgSerial,
                                    'url' : item.url,
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
                        
                    }, 500);



                }, function(data) {
                   window.swal("Oops!", "an error occured!", "error");
                });

                $http({
                    url: API_URL + "/_inc/ajax.php?type=QUANTITYCHECK",
                    method: "GET",
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "json"
                }).
                then(function(response) {
                    if (response.data.error == true) {
                        window.location = window.baseUrl+'/maintenance.php';
                    }
                });

                // Submit product update form
                $(document).delegate("#product-update-submit", "click", function(e) {
                    e.stopImmediatePropagation();
                    e.stopPropagation();
                    e.preventDefault();
                    
                    var $tag = $(this);
                    var $btn = $tag.button("loading");
                    var form = $($tag.data("form"));
                    form.find(".alert").remove();
                    var actionUrl = form.attr("action");
                    var postData;
                    // var desc = window.tinymce ? window.tinymce.activeEditor.getContent() : '';
                    postData = form.serialize();
                    
                    $http({
                        url: window.baseUrl + "/_inc/" + actionUrl,
                        method: "POST",
                        data: postData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        dataType: "json"
                    }).
                    then(function (response) {

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

                                // close modalwindow
                                $scope.closeProductEditModal();
                                $(document).find(".close").trigger("click");
                                $("body").removeClass("modal-open");

                                productId = response.data.id;
                                
                                if ($("#product-product-list").length) {
                                   $("#product-product-list").DataTable().ajax.reload(function(json) {
                                        if ($("#row_"+productId).length) {
                                            $("#row_"+productId).flash("yellow", 5000);
                                        }
                                    }, false); 
                                }

                            } else {

                                if ($("#product-product-list").length) {
                                    $("#product-product-list").DataTable().ajax.reload(null, false);
                                }
                            }
                        });

                    }, function(response) {
                        $btn.button("reset");
                        var alertMsg = "<div class=\"alert alert-danger\">";
                        window.angular.forEach(response.data, function(value) {
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
                
                // Close modal
                $scope.closeProductEditModal = function () {
                    $uibModalInstance.dismiss("cancel");
                };


                // Product Type
                $scope.setFieldAsProductType = function(type) {
                    if (type == 'service') {
                        $scope.$apply(function() {
                            $scope.hideSupplier = 1;
                            $scope.hideBrand = 1;
                            $scope.hideBrand = 1;
                            $scope.hideUnit = 1;
                            $scope.hideBox = 1;
                            $scope.showPurchasePrice = 1;
                            $scope.hideExpiredAt = 1; 
                            $scope.showSellPrice = 1; 
                            $scope.hideAlertQuantity = 1;
                        });
                    } else {
                        $scope.$applyAsync(function() {
                            $scope.hideSupplier = !1;
                            $scope.hideBrand = !1;
                            $scope.hideBrand = !1;
                            $scope.hideUnit = !1;
                            $scope.hideBox = !1;
                            $scope.showPurchasePrice = !1;
                            $scope.hideExpiredAt = !1; 
                            $scope.showSellPrice = !1;
                            $scope.hideAlertQuantity = !1;
                        }); 
                    }
                }
                $scope.productType = 'standard';
                $(document).delegate("#p_type", 'select2:select', function (e) {
                    var data = e.params.data;
                    $scope.productType = data.element.value;
                    console.log($scope.productType);
                    $scope.setFieldAsProductType($scope.productType);
                });

            },
            scope: $scope,
            size: "lg",
            backdrop  : "static",
            keyboard: true,
        });

        uibModalInstance.result.catch(function () { 
            uibModalInstance.close(); 
            setTimeout(function() {
                $("body").removeClass("modal-open");
            }, 500);
        });
    };
}]);