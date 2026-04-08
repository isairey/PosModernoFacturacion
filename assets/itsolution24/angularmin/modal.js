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
window.angularApp.factory("InvoiceInfoEditModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(invoice) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button id=\"invoice_info_modal\" ng-click=\"closeInvoiceInfoEditModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-pencil\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/invoice.php?invoice_id=" + invoice.invoice_id + "&action_type=INVOICEINFOEDIT",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Edit Invoice > " + invoice.invoice_id;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 100);

                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                $(document).delegate("#invoice-update", "click", function(e) {
                    
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
                                invoiceId = response.data.id;
                                $(document).find("#invoice_info_modal").trigger("click");
                                if ($(datatable).length) {
                                    $(datatable).DataTable().ajax.reload(function(json) {
                                        if ($("#row_"+invoiceId).length) {
                                            $("#row_"+invoiceId).flash("yellow", 5000);
                                        }
                                    }, false);
                                }
                            } else {
                                if ($(datatable).length) {
                                    $(datatable).DataTable().ajax.reload(null, false);
                                }
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
                $scope.closeInvoiceInfoEditModal = function () {
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
window.angularApp.factory("BoxCreateModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function($scope) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeBoxCreateModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-plus\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/box.php?action_type=CREATE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Create New Box";
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 500);
                    
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                // Submit Box Form
                $(document).delegate("#create-box-submit", "click", function(e) {
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
                            $scope.closeBoxCreateModal();
                            $(document).find(".close").trigger("click");

                            // insert box into select2
                            var select = $(document).find("#box_id");
                            if (select.length) {
                            
                                var option = $("<option></option>").
                                     attr("selected", true).
                                     text(response.data.box.box_name).
                                     val(response.data.id);
                                option.appendTo(select);
                                select.trigger("change");
                            }

                            // increase store count
                            var boxCount = $(document).find("#box-count h3");
                            if (boxCount) {
                                boxCount.text(parseInt(boxCount.text()) + 1);
                            }

                            // Callback
                            if ($scope.BoxCreateModalCallback) {
                                $scope.BoxCreateModalCallback($scope);
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

                $scope.closeBoxCreateModal = function () {
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
window.angularApp.factory("BoxDeleteModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function(API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(box) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                           "<button ng-click=\"closeBoxDeleteModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-trash\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/box.php?box_id=" + box.box_id + "&action_type=DELETE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = box.box_name;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 100);
                    
                }, function(response) {
                    window.swal("Oops!", response.data.errorMsg, "error")
                    .then(function() {
                        $scope.closeBoxDeleteModal();
                    });
                });

                $(document).delegate("#box-delete", "click", function(e) {
                    
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
                        $(datatable).DataTable().ajax.reload(null, false);

                        // Alert
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {

                            $scope.closeBoxDeleteModal();
                            $(document).find(".close").trigger("click");
                        });

                        // Callback
                        if ($scope.BoxDeleteModalCallback) {
                            $scope.BoxDeleteModalCallback($scope);
                        }

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

                $scope.closeBoxDeleteModal = function () {
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
window.angularApp.factory("BoxEditModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(box) {
        var boxId;
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeBoxEditModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-pencil\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile='rawHtml'>Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: API_URL + "/_inc/box.php?box_id=" + box.box_id + "&action_type=EDIT",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                $scope.modal_title = box.box_name;
                $scope.rawHtml = $sce.trustAsHtml(response.data);
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                $(document).delegate("#box-update", "click", function(e) {
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
                        url: API_URL + "/_inc/" + actionUrl,
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

                                $scope.closeBoxEditModal();
                                $(document).find(".close").trigger("click");
                                boxId = response.data.id;
                                
                                $(datatable).DataTable().ajax.reload(function(json) {
                                    if ($("#row_"+boxId).length) {
                                        $("#row_"+boxId).flash("yellow", 5000);
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

                $scope.closeBoxEditModal = function () {
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
window.angularApp.factory("UnitCreateModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function($scope) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeUnitCreateModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-plus\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/unit.php?action_type=CREATE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Create New Unit";
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 500);
                    
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                // Submit Unit Form
                $(document).delegate("#create-unit-submit", "click", function(e) {
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
                        form.find(".unit-body").before(alertMsg);

                        // Alert
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {

                            // close modalwindow
                            $scope.closeUnitCreateModal();
                            $(document).find(".close").trigger("click");

                            // insert unit into select2
                            var select = $(document).find("#unit_id");
                            if (select.length) {
                            
                                var option = $("<option></option>").
                                     attr("selected", true).
                                     text(response.data.unit.unit_name).
                                     val(response.data.id);
                                option.appendTo(select);
                                select.trigger("change");
                            }

                            // increase store count
                            var unitCount = $(document).find("#unit-count h3");
                            if (unitCount) {
                                unitCount.text(parseInt(unitCount.text()) + 1);
                            }

                            // Callback
                            if ($scope.UnitCreateModalCallback) {
                                $scope.UnitCreateModalCallback($scope);
                            }

                        });

                    }, function(response) {
                        $btn.button("reset");
                        var alertMsg = "<div class=\"alert alert-danger\">";
                        window.angular.forEach(response.data, function(value, key) {
                            alertMsg += "<p><i class=\"fa fa-warning\"></i> " + value + ".</p>";
                        });
                        alertMsg += "</div>";
                        form.find(".unit-body").before(alertMsg);
                        $(":input[type=\"button\"]").prop("disabled", false);
                        window.swal("Oops!", response.data.errorMsg, "error");
                    });
                });

                $scope.closeUnitCreateModal = function () {
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
window.angularApp.factory("UnitDeleteModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function(API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(unit) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                           "<button ng-click=\"closeUnitDeleteModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-trash\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/unit.php?unit_id=" + unit.unit_id + "&action_type=DELETE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = unit.unit_name;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 100);
                    
                }, function(response) {
                    window.swal("Oops!", response.data.errorMsg, "error")
                    .then(function() {
                        $scope.closeUnitDeleteModal();
                    });
                });

                $(document).delegate("#unit-delete", "click", function(e) {
                    
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
                        form.find(".unit-body").before(alertMsg);
                        $(datatable).DataTable().ajax.reload(null, false);

                        // Alert
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {

                            $scope.closeUnitDeleteModal();
                            $(document).find(".close").trigger("click");
                        });

                        // Callback
                        if ($scope.UnitDeleteModalCallback) {
                            $scope.UnitDeleteModalCallback($scope);
                        }

                    }, function(response) {

                        $btn.button("reset");
                        var alertMsg = "<div class=\"alert alert-danger\">";
                        window.angular.forEach(response.data, function(value, key) {
                            alertMsg += "<p><i class=\"fa fa-warning\"></i> " + value + ".</p>";
                        });
                        alertMsg += "</div>";
                        form.find(".unit-body").before(alertMsg);
                        $(":input[type=\"button\"]").prop("disabled", false);
                        window.swal("Oops!", response.data.errorMsg, "error");
                    });

                });

                $scope.closeUnitDeleteModal = function () {
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
window.angularApp.factory("UnitEditModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(unit) {
        var unitId;
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeUnitEditModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-pencil\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile='rawHtml'>Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: API_URL + "/_inc/unit.php?unit_id=" + unit.unit_id + "&action_type=EDIT",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                $scope.modal_title = unit.unit_name;
                $scope.rawHtml = $sce.trustAsHtml(response.data);
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                $(document).delegate("#unit-update", "click", function(e) {
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
                        url: API_URL + "/_inc/" + actionUrl,
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
                        form.find(".unit-body").before(alertMsg);

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

                                $scope.closeUnitEditModal();
                                $(document).find(".close").trigger("click");
                                unitId = response.data.id;
                                
                                $(datatable).DataTable().ajax.reload(function(json) {
                                    if ($("#row_"+unitId).length) {
                                        $("#row_"+unitId).flash("yellow", 5000);
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
                        form.find(".unit-body").before(alertMsg);
                        $(":input[type=\"button\"]").prop("disabled", false);
                        window.swal("Oops!", response.data.errorMsg, "error");
                    });

                });

                $scope.closeUnitEditModal = function () {
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
window.angularApp.factory("TaxrateCreateModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function($scope) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeTaxrateCreateModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-plus\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/taxrate.php?action_type=CREATE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Create New Taxrate";
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 500);
                    
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                // Submit Taxrate Form
                $(document).delegate("#create-taxrate-submit", "click", function(e) {
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
                        form.find(".taxrate-body").before(alertMsg);

                        // Alert
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {

                            // close modalwindow
                            $scope.closeTaxrateCreateModal();
                            $(document).find(".close").trigger("click");

                            // insert taxrate into select2
                            var select = $(document).find("#taxrate_id");
                            if (select.length) {
                            
                                var option = $("<option></option>").
                                     attr("selected", true).
                                     text(response.data.taxrate.taxrate_name).
                                     val(response.data.id);
                                option.appendTo(select);
                                select.trigger("change");
                            }

                            // increase store count
                            var taxrateCount = $(document).find("#taxrate-count h3");
                            if (taxrateCount) {
                                taxrateCount.text(parseInt(taxrateCount.text()) + 1);
                            }

                            // Callback
                            if ($scope.TaxrateCreateModalCallback) {
                                $scope.TaxrateCreateModalCallback($scope);
                            }

                        });

                    }, function(response) {
                        $btn.button("reset");
                        var alertMsg = "<div class=\"alert alert-danger\">";
                        window.angular.forEach(response.data, function(value, key) {
                            alertMsg += "<p><i class=\"fa fa-warning\"></i> " + value + ".</p>";
                        });
                        alertMsg += "</div>";
                        form.find(".taxrate-body").before(alertMsg);
                        $(":input[type=\"button\"]").prop("disabled", false);
                        window.swal("Oops!", response.data.errorMsg, "error");
                    });
                });

                $scope.closeTaxrateCreateModal = function () {
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
window.angularApp.factory("TaxrateDeleteModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function(API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(taxrate) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                           "<button ng-click=\"closeTaxrateDeleteModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-trash\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/taxrate.php?taxrate_id=" + taxrate.taxrate_id + "&action_type=DELETE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = taxrate.taxrate_name;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 100);
                    
                }, function(response) {
                    window.swal("Oops!", response.data.errorMsg, "error")
                    .then(function() {
                        $scope.closeTaxrateDeleteModal();
                    });
                });

                $(document).delegate("#taxrate-delete", "click", function(e) {
                    
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
                        form.find(".taxrate-body").before(alertMsg);
                        $(datatable).DataTable().ajax.reload(null, false);

                        // Alert
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {

                            $scope.closeTaxrateDeleteModal();
                            $(document).find(".close").trigger("click");
                        });

                        // Callback
                        if ($scope.TaxrateDeleteModalCallback) {
                            $scope.TaxrateDeleteModalCallback($scope);
                        }

                    }, function(response) {

                        $btn.button("reset");
                        var alertMsg = "<div class=\"alert alert-danger\">";
                        window.angular.forEach(response.data, function(value, key) {
                            alertMsg += "<p><i class=\"fa fa-warning\"></i> " + value + ".</p>";
                        });
                        alertMsg += "</div>";
                        form.find(".taxrate-body").before(alertMsg);
                        $(":input[type=\"button\"]").prop("disabled", false);
                        window.swal("Oops!", response.data.errorMsg, "error");
                    });

                });

                $scope.closeTaxrateDeleteModal = function () {
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
window.angularApp.factory("TaxrateEditModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(taxrate) {
        var taxrateId;
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeTaxrateEditModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-pencil\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile='rawHtml'>Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: API_URL + "/_inc/taxrate.php?taxrate_id=" + taxrate.taxrate_id + "&action_type=EDIT",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                $scope.modal_title = taxrate.taxrate_name;
                $scope.rawHtml = $sce.trustAsHtml(response.data);
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                $(document).delegate("#taxrate-update", "click", function(e) {
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
                        url: API_URL + "/_inc/" + actionUrl,
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
                        form.find(".taxrate-body").before(alertMsg);

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

                                $scope.closeTaxrateEditModal();
                                $(document).find(".close").trigger("click");
                                taxrateId = response.data.id;
                                
                                $(datatable).DataTable().ajax.reload(function(json) {
                                    if ($("#row_"+taxrateId).length) {
                                        $("#row_"+taxrateId).flash("yellow", 5000);
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
                        form.find(".taxrate-body").before(alertMsg);
                        $(":input[type=\"button\"]").prop("disabled", false);
                        window.swal("Oops!", response.data.errorMsg, "error");
                    });

                });

                $scope.closeTaxrateEditModal = function () {
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
window.angularApp.factory("CategoryCreateModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function($scope) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeCategoryCreateModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-plus\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/category.php?action_type=CREATE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Create New Category";
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 500);

                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                // Submit Box Form
                $(document).delegate("#create-category-submit", "click", function(e) {
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
                            $scope.closeCategoryCreateModal();
                            $(document).find(".close").trigger("click");

                            // insert category into select2
                            var select = $(document).find('#category_id');
                            if (select.length) {
                                var option = $('<option></option>').
                                     attr('selected', true).
                                     text(response.data.category.category_name).
                                     val(response.data.id);
                                option.appendTo(select);
                                select.trigger('change');
                            }

                            // increase category count
                            var categoryCount = $(document).find("#category-count h3");
                            if (categoryCount) {
                                categoryCount.text(parseInt(categoryCount.text()) + 1);
                            }

                            // Callback
                            if ($scope.CategoryCreateModalCallback) {
                                $scope.CategoryCreateModalCallback($scope);
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

                $scope.closeCategoryCreateModal = function () {
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
window.angularApp.factory("CategoryDeleteModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(category) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeCategoryDeleteModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-trash\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/category.php?category_id=" + category.category_id + "&action_type=DELETE",
                  method: "GET"
                })
                .then(function(response) {
                    $scope.modal_title = category.category_name;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 100);

                }, function(response) {
                    window.swal("Oops!", response.data.errorMsg, "error")
                    .then(function() {
                        $scope.closeCategoryDeleteModal();
                    });
                });

                $(document).delegate("#category-delete", "click", function(e) {
                    
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
                        $(datatable).DataTable().ajax.reload( null, false );

                        // Alert
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {
                            $scope.closeCategoryDeleteModal();
                            $(document).find(".close").trigger("click");
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

                $scope.closeCategoryDeleteModal = function () {
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
window.angularApp.factory("CategoryEditModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(category) {
        var categoryId;
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeCategoryEditModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-pencil\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/category.php?category_id=" + category.category_id + "&action_type=EDIT",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = category.category_name;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 100);

                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                $(document).delegate("#category-update", "click", function(e) {
                    
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
                                $scope.closeCategoryEditModal();
                                $(document).find(".close").trigger("click");
                                categoryId = response.data.id;
                                
                                $(datatable).DataTable().ajax.reload(function(json) {
                                    if ($("#row_"+categoryId).length) {
                                        $("#row_"+categoryId).flash("yellow", 5000);
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
                $scope.closeCategoryEditModal = function () {
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
    window.angularApp.factory("CurrencyEditModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
        return function(currency) {
            var currencyId;
            var uibModalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: "modal-title",
                ariaDescribedBy: "modal-body",
                template: "<div class=\"modal-header\">" +
                                "<button ng-click=\"closeCurrencyEditModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                               "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-pencil\"></span> {{ modal_title }}</h3>" +
                            "</div>" +
                            "<div class=\"modal-body\" id=\"modal-body\">" +
                                "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                            "</div>",
                controller: function ($scope, $uibModalInstance) {
                    $http({
                      url: API_URL + "/_inc/currency.php?currency_id=" + currency.currency_id + "&action_type=EDIT",
                      method: "GET"
                    })
                    .then(function(response, status, headers, config) {
                        $scope.modal_title = currency.title;
                        $scope.rawHtml = $sce.trustAsHtml(response.data);

                        setTimeout(function() {
                            window.storeApp.select2();
                        }, 100);

                    }, function(response) {
                        window.swal("Oops!", response.data.errorMsg, "error")
                        .then(function() {
                            $scope.closeCurrencyEditModal();
                        });
                    });

                    $(document).delegate("#currency-update", "click", function(e) {
                        
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
                            url: API_URL + "/_inc/" + actionUrl,
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
                                    $scope.closeCurrencyEditModal();
                                    $(document).find(".close").trigger("click");
                                    currencyId = response.data.id;
                                    
                                    $(datatable).DataTable().ajax.reload(function(json) {
                                        if ($("#row_"+currencyId).length) {
                                            $("#row_"+currencyId).flash("yellow", 5000);
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

                    $scope.closeCurrencyEditModal = function () {
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
window.angularApp.factory("CustomerCreateModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function($scope) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeCustomerCreateModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-plus\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/customer.php?action_type=CREATE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Create New Customer";
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                        window.storeApp.datePicker();
                    }, 500);
                    
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                // Submit Customer Form
                $(document).delegate("#create-customer-submit", "click", function(e) {
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

                            $scope.customerMobileNumber = response.data.customer_contact;
                            $scope.customerName = response.data.customer_name + " (" + $scope.customerMobileNumber + ")";
                            $scope.customerId = response.data.id;
                            $scope.dueAmount = response.data.due_amount;

                            $(document).find("input[name=\"customer-name\"]").val(response.data.customer_name + ' (' + response.data.customer_contact + ')');
                            $(document).find("input[name=\"customer-id\"]").val(response.data.id);

                            // increase customer count
                            var customerCount = $(document).find("#customer-count h3");
                            if (customerCount) {
                                customerCount.text(parseInt(customerCount.text()) + 1);
                            }

                            // close modalwindow
                            $scope.closeCustomerCreateModal();
                            $(document).find(".close").trigger("click");

                            // Callback
                            if ($scope.CustomerCreateModalCallback) {
                                $scope.CustomerCreateModalCallback($scope);
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

                $scope.closeCustomerCreateModal = function () {
                    $uibModalInstance.dismiss("cancel");
                };
            },
            scope: $scope,
            size: "md",
            backdrop: "static",
            keyboard: true,
        });

        uibModalInstance.result.catch(function () { 
            uibModalInstance.close(); 
        });
    };
}]);
window.angularApp.factory("CustomerDeleteModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(customer) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeCustomerDeleteModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-trash\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/customer.php?customer_id=" + customer.customer_id + "&action_type=DELETE",
                  method: "GET"
                })
                .then(function(response) {
                    $scope.modal_title = customer.customer_name;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 100);

                }, function(response) {
                    window.swal("Oops!", response.data.errorMsg, "error")
                    .then(function() {
                        $scope.closeCustomerDeleteModal();
                    });
                });

                $(document).delegate("#customer-delete", "click", function(e) {
                    
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
                        $(datatable).DataTable().ajax.reload( null, false );

                        // Alert
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {
                            $scope.closeCustomerDeleteModal();
                            $(document).find(".close").trigger("click");
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

                $scope.closeCustomerDeleteModal = function () {
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
window.angularApp.factory("CustomerEditModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(customer) {
        var customerId;
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeCustomerEditModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-pencil\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/customer.php?customer_id=" + customer.customer_id + "&action_type=EDIT",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = customer.customer_name;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                        window.storeApp.datePicker();
                    }, 100);

                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                $(document).delegate("#customer-update", "click", function(e) {
                    
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
                                $scope.closeCustomerEditModal();
                                $(document).find(".close").trigger("click");
                                customerId = response.data.id;
                                
                                $(datatable).DataTable().ajax.reload(function(json) {
                                    if ($("#row_"+customerId).length) {
                                        $("#row_"+customerId).flash("yellow", 5000);
                                    }
                                }, false);

                            } else {
                                $(datatable).DataTable().ajax.reload(null, false);
                            }

                            // Callback
                            if ($scope.CustomerEditModalCallback) {
                                $scope.CustomerEditModalCallback($scope);
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
                $scope.closeCustomerEditModal = function () {
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
window.angularApp.factory("SupportDeskModal", ["API_URL", "$http", "$uibModal", "$sce", "$rootScope", function(API_URL, $http, $uibModal, $sce, $scope) {
    return function() {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"cancel();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-headphones\"></span> Support Desk</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: API_URL + "/_inc/template/partials/supportdesk_modal.php",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.rawHtml = $sce.trustAsHtml(response.data);
                }, function(data) {
                   window.swal("Oops!", "an unknown error occured!", "error");
                });
                $scope.cancel = function () {
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
window.angularApp.factory("DueCollectionDetailsModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    var $from = window.getParameterByName("from");
    var $to = window.getParameterByName("to");
    return function($scope) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeDueCollectionModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-list\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/duepaid.php?action_type=DUEPAIDDETAILS&created_by=" + $scope.createdBy + "&from=" + $from + "&to=" + $to,
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Due Collection Details";
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 500);
                    
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                $scope.closeDueCollectionModal = function () {
                    $uibModalInstance.dismiss("cancel");
                };
            },
            scope: $scope,
            size: "lg",
            backdrop  : "dynamic", //static
            keyboard: true,
        });

        uibModalInstance.result.catch(function () { 
            uibModalInstance.close(); 
        });
    };
}]);
//================================
// start deposit factory
//================================

window.angularApp.factory("BankingDepositModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function() {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeBankingDepositModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\">{{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/banking.php?action_type=DEPOSIT",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Deposit to Bank";
                    $scope.rawHtml = $sce.trustAsHtml(response.data);
                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 300);
                }, function(response) {
                    window.swal("Oops!", response.data.errorMsg, "error")
                    .then(function() {
                        $scope.closeBankingDepositModal();
                    });
                });

                $scope.showCapital = false;
                $(document).delegate('#source_id', 'select2:select', function (e) {
                    e.stopImmediatePropagation();
                    e.stopPropagation();
                    e.preventDefault();
                    var data = e.params.data;
                    var slug = $(data.element).data("slug");
                    if (slug == "capital_and_profit") {
                        $scope.$apply(function() {
                            $scope.showCapital = true;
                        });
                        $("#title").val("Capital/Profit Manual Entry");
                    } else {
                        $scope.$apply(function() {
                            $scope.showCapital = false;
                        });
                        $("#title").val("");
                    }
                });

                $(document).delegate("#deposit-confirm-btn", "click", function(e) {
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
                        swal("Success", response.data.msg, "success").then(function(value) {
                            $scope.closeBankingDepositModal();
                            $(document).find(".close").click();
                            // update balance    
                            $("#balance-display").text("TK "+response.data.balance);    
                            // flash update row    
                            var rowId = response.data.invoice.invoice_id;
                            $(datatable).DataTable().ajax.reload(function(json) {
                                if ($("#row_"+rowId).length) {
                                    $("#row_"+rowId).flash("yellow", 5000);
                                }
                            }, false);                        
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
                $scope.closeBankingDepositModal = function () {
                    $uibModalInstance.dismiss("cancel");
                };
            },
            scope: $scope,
            size: "md",
            backdrop  : "static",
            keyboard: true, // ESC key close enable/disable
            resolve: {
                userForm: function () {
                    // return pcForm;
                }
            }
        });

        uibModalInstance.result.catch(function () { 
            uibModalInstance.close(); 
        });
    };
}]);

//===============================
// end deposit factory
//===============================

window.angularApp.factory("BankingRowViewModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function (invoice, type) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template:   "<div id=\"data-modal\" class=\"modal-inner\">" +
                            "<div class=\"modal-header\">" +
								"<button ng-click=\"closeBankingRowViewModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
							   "<h3 class=\"modal-title\" id=\"modal-title\">{{ modal_title }}</h3>" +
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
                  url: window.baseUrl + "/_inc/banking.php?invoice_id=" + invoice.ref_no + '&action_type=VIEW&view_type=' + type,
                  method: "GET"
                })
                .then(function (response, status, headers, config) {
                    $scope.modal_title = "View " + type + " details";
                    $scope.rawHtml = $sce.trustAsHtml(response.data);
                }, function (response) {
                    window.swal("Oops!", response.data.errorMsg, "error").then(function() {
                        $scope.closeBankingRowViewModal();
                    });
                });

                $scope.closeBankingRowViewModal = function () {
                    $uibModalInstance.dismiss("cancel");
                };
            },
            scope: $scope,
            size: "md",
            backdrop  : "static",
            keyboard: true, // ESC key close enable/disable
            resolve: {
                userForm: function () {
                    // return supplierForm;
                }
            }
        });

        uibModalInstance.result.catch(function () { 
            uibModalInstance.close(); 
        });
    };
}]);
window.angularApp.factory("BankingWithdrawModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function() {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeBankingWithdrawModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\">{{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/banking.php?action_type=WITHDRAW",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Withdraw from Bank";
                    $scope.rawHtml = $sce.trustAsHtml(response.data);
                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 300);
                }, function(response) {
                    window.swal("Oops!", response.data.errorMsg, "error").then(function() {
                        $scope.closeBankingWithdrawModal();
                    });
                });

                $(document).delegate("#withdraw-confirm-btn", "click", function(e) {
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
                        swal("Success", response.data.msg, "success").then(function(value) {
                            $scope.closeBankingWithdrawModal();
                            $(document).find(".close").click(); 
                            // update balance    
                            $("#balance-display").text("TK "+response.data.balance);    
                            // flash update row    
                            var rowId = response.data.invoice.invoice_id;
                            $(datatable).DataTable().ajax.reload(function(json) {
                                if ($("#row_"+rowId).length) {
                                    $("#row_"+rowId).flash("yellow", 5000);
                                }
                            }, false); 
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
                $scope.closeBankingWithdrawModal = function () {
                    $uibModalInstance.dismiss("cancel");
                };
            },
            scope: $scope,
            size: "md",
            backdrop  : "static",
            keyboard: true, // ESC key close enable/disable
            resolve: {
                userForm: function () {
                    // return pcForm;
                }
            }
        });

        uibModalInstance.result.catch(function () { 
            uibModalInstance.close(); 
        });
    };
}]);

//===============================
// end withdraw factory
//===============================
window.angularApp.factory("BankAccountCreateModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function($scope) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeBankAccountCreateModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-plus\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/taxrate.php?action_type=CREATE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Create New BankAccount";
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 500);
                    
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                // Submit BankAccount Form
                $(document).delegate("#create-taxrate-submit", "click", function(e) {
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
                        form.find(".taxrate-body").before(alertMsg);

                        // Alert
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {

                            // close modalwindow
                            $scope.closeBankAccountCreateModal();
                            $(document).find(".close").trigger("click");

                            // insert taxrate into select2
                            var select = $(document).find("#taxrate_id");
                            if (select.length) {
                            
                                var option = $("<option></option>").
                                     attr("selected", true).
                                     text(response.data.taxrate.taxrate_name).
                                     val(response.data.id);
                                option.appendTo(select);
                                select.trigger("change");
                            }

                            // increase store count
                            var taxrateCount = $(document).find("#taxrate-count h3");
                            if (taxrateCount) {
                                taxrateCount.text(parseInt(taxrateCount.text()) + 1);
                            }

                            // Callback
                            if ($scope.BankAccountCreateModalCallback) {
                                $scope.BankAccountCreateModalCallback($scope);
                            }

                        });

                    }, function(response) {
                        $btn.button("reset");
                        var alertMsg = "<div class=\"alert alert-danger\">";
                        window.angular.forEach(response.data, function(value, key) {
                            alertMsg += "<p><i class=\"fa fa-warning\"></i> " + value + ".</p>";
                        });
                        alertMsg += "</div>";
                        form.find(".taxrate-body").before(alertMsg);
                        $(":input[type=\"button\"]").prop("disabled", false);
                        window.swal("Oops!", response.data.errorMsg, "error");
                    });
                });

                $scope.closeBankAccountCreateModal = function () {
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
window.angularApp.factory("BankAccountDeleteModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function(API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(account) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                           "<button ng-click=\"closeBankAccountDeleteModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-trash\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/bank_account.php?account_id=" + account.id + "&action_type=DELETE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = account.account_name;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 100);
                    
                }, function(response) {
                    window.swal("Oops!", response.data.errorMsg, "error")
                    .then(function() {
                        $scope.closeBankAccountDeleteModal();
                    });
                });

                $(document).delegate("#account-delete", "click", function(e) {
                    
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
                        form.find(".account-body").before(alertMsg);
                        $(datatable).DataTable().ajax.reload(null, false);

                        // Alert
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {

                            $scope.closeBankAccountDeleteModal();
                            $(document).find(".close").trigger("click");
                        });

                        // Callback
                        if ($scope.BankAccountDeleteModalCallback) {
                            $scope.BankAccountDeleteModalCallback($scope);
                        }

                    }, function(response) {

                        $btn.button("reset");
                        var alertMsg = "<div class=\"alert alert-danger\">";
                        window.angular.forEach(response.data, function(value, key) {
                            alertMsg += "<p><i class=\"fa fa-warning\"></i> " + value + ".</p>";
                        });
                        alertMsg += "</div>";
                        form.find(".account-body").before(alertMsg);
                        $(":input[type=\"button\"]").prop("disabled", false);
                        window.swal("Oops!", response.data.errorMsg, "error");
                    });

                });

                $scope.closeBankAccountDeleteModal = function () {
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
window.angularApp.factory("BankAccountEditModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(account) {
        var accountId;
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeBankAccountEditModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-pencil\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile='rawHtml'>Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: API_URL + "/_inc/bank_account.php?account_id=" + account.id + "&action_type=EDIT",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                $scope.modal_title = account.account_name;
                $scope.rawHtml = $sce.trustAsHtml(response.data);
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                $(document).delegate("#account-update", "click", function(e) {
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
                        url: API_URL + "/_inc/" + actionUrl,
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
                        form.find(".account-body").before(alertMsg);

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

                                $scope.closeBankAccountEditModal();
                                $(document).find(".close").trigger("click");
                                accountId = response.data.id;
                                
                                $(datatable).DataTable().ajax.reload(function(json) {
                                    if ($("#row_"+accountId).length) {
                                        $("#row_"+accountId).flash("yellow", 5000);
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
                        form.find(".account-body").before(alertMsg);
                        $(":input[type=\"button\"]").prop("disabled", false);
                        window.swal("Oops!", response.data.errorMsg, "error");
                    });

                });

                $scope.closeBankAccountEditModal = function () {
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
//================================
// start transfer factory
//================================

window.angularApp.factory("BankTransferModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function() {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"CloseBankTransferModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-paper-plane\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/banking.php?action_type=TRANSFER",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Transfer Balance";
                    $scope.rawHtml = $sce.trustAsHtml(response.data);
                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 100);
                }, function(response) {
                    window.swal("Oops!", response.data.errorMsg, "error")
                    .then(function() {
                        $scope.CloseBankTransferModal();
                    });
                });

                // Confirm transfer
                $(document).delegate("#transfer-confirm-btn", "click", function(e) {
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
                        swal("Success", response.data.msg, "success").then(function(value) {
                            $scope.CloseBankTransferModal();
                            $(document).find(".close").trigger("click");
                            // update balance    
                            $("#balance-display").text("TK "+response.data.balance);    
                            // flash update row    
                            var rowId = response.data.invoice.invoice_id;
                            $(datatable).DataTable().ajax.reload(function(json) {
                                if ($("#row_"+rowId).length) {
                                    $("#row_"+rowId).flash("yellow", 5000);
                                }
                            }, false);                        
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
                $scope.CloseBankTransferModal = function () {
                    $uibModalInstance.dismiss("cancel");
                };
            },
            scope: $scope,
            size: "md",
            backdrop  : "static",
            keyboard: true,
            resolve: {
                userForm: function () {
                    // return pcForm;
                }
            }
        });

        uibModalInstance.result.catch(function () { 
            uibModalInstance.close(); 
        });
    };
}]);

//===============================
// end transfer factory
//===============================

window.angularApp.factory("EmailModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(content) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"cancel();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-envelope\"></span>Email: {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>" +
                        "<div class=\"modal-footer\">" +
                            "<a id=\"sendEmailBtn\" class=\"btn-success btn btn-sm\" data-loading-text=\"Sending...\"><span class=\"fa fa-fw fa-send-o\"></span> SEND</a>" +
                            "<a ng-click=\"cancel();\" class=\"btn-danger btn btn-sm\" data-dismiss=\"modal\" aria-label=\"Close\"><span class=\"fa fa-fw- fa-close\"></span> CLOSE</a>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $scope.modal_title = content.title;
                var form = "<form method=\"post\" action=\"#\" id=\"email-form\" style=\"margin:40px 20px;\">";
                form += "<input type=\"hidden\" name=\"recipient_name\" value=\""+content.recipientName+"\">";
                form += "<input type=\"hidden\" name=\"template\" value=\""+content.template+"\">";
                form += "<input type=\"hidden\" name=\"subject\" value=\""+content.subject+"\">";
                form += "<input type=\"hidden\" name=\"title\" value=\""+content.title.trim()+"\">";
                form += "<input type=\"email\" name=\"email\" class=\"form-control\" placeholder=\"Please, type a valid email address\" required>";
                if (content.styles && content.styles != undefined) {
                    form += "<textarea style=\"display:none;\" name=\"styles\">"+content.styles.trim()+"</textarea>";
                }
                form += "<textarea style=\"display:none;\" name=\"emailbody\">"+content.html.trim()+"</textarea>";
                form += "</form>";
                $scope.rawHtml = $sce.trustAsHtml(form);
                $scope.content = content;
                $(document).delegate("#sendEmailBtn", "click", function(e) {
                    e.stopImmediatePropagation();
                    e.stopPropagation();
                    e.preventDefault();
                    var $tag = $(this);
                    var $btn = $tag.button("loading");
                    $("body").addClass("overlay-loader");
                    $http({
                        url: window.baseUrl + "/_inc/send_email.php",
                        method: "POST",
                        data: $("#email-form").serialize(),
                        cache: false,
                        processData: false,
                        contentType: false,
                        dataType: "json"
                    }).
                    then(function(response) {
                        $("body").removeClass("overlay-loader");
                        $btn.button("reset");
                        window.swal("Success", response.data.msg, "success").then(function() {
                            $scope.cancel();
                        });
                    }, function(response) {
                        $("body").removeClass("overlay-loader");
                        $btn.button("reset");
                        window.swal("Oops!", response.data.errorMsg, "error");
                    });
                });
                $scope.cancel = function () {
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
window.angularApp.factory("keyboardShortcutModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function() {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"cancel();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-keyboard-o\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: API_URL + "/_inc/template/partials/keyboard_shortcut.php",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Keyboard Shortcut";
                    $scope.rawHtml = $sce.trustAsHtml(response.data);
                }, function(data) {
                   window.swal("Oops!", "an unknown error occured!", "error");
                });
                $scope.cancel = function () {
                    $uibModalInstance.dismiss("cancel");
                };
            },
            scope: $scope,
            size: "md",
            backdrop  : "static",
            keyboard: true,
        });
        
        uibModalInstance.result.then(function (selectedItem) {
            // ...
        }, function () {
            $("#keyboard-shortcut").removeClass("open");
        });
    };
}]);
window.angularApp.factory("PmethodDeleteModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(pmethod) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closePmethodDeleteModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-trash\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/pmethod.php?pmethod_id=" + pmethod.pmethod_id + "&action_type=DELETE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = pmethod.name;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 100);
                    
                }, function(response) {
                    window.swal("Oops!", response.data.errorMsg, "error")
                    .then(function() {
                        $scope.closePmethodDeleteModal();
                    });
                });

                $(document).delegate("#pmethod-delete", "click", function(e) {
                    
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
                        $(datatable).DataTable().ajax.reload( null, false );

                        // Alert
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {
                            $scope.closePmethodDeleteModal();
                            $(document).find(".close").trigger("click");
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

                $scope.closePmethodDeleteModal = function () {
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
window.angularApp.factory("PmethodEditModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(pmethod) {
        var pmethodId;
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closePmethodEditModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-pencil\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: API_URL + "/_inc/pmethod.php?pmethod_id=" + pmethod.pmethod_id + "&action_type=EDIT",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = pmethod.name;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 100);

                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                $(document).delegate("#pmethod-update", "click", function(e) {
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
                        url: API_URL + "/_inc/" + actionUrl,
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
                                $scope.closePmethodEditModal();
                                $(document).find(".close").trigger("click");
                                pmethodId = response.data.id;
                                
                                $(datatable).DataTable().ajax.reload(function(json) {
                                    if ($("#row_"+pmethodId).length) {
                                        $("#row_"+pmethodId).flash("yellow", 5000);
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

                $scope.closePmethodEditModal = function () {
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
window.angularApp.factory("POSFilemanagerModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function (data) {
        var uibModalInstance = $uibModal.open({
            animation: false,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeFileManager()\" type=\"button\" id=\"close-filemanger\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\">{{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-bodyx\" id=\"filemanager\">" +
                            "<div bind-html-compile=\"rawHtml\"><div style=\"padding:10px;\">Loading...</div></div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/"+window.adminDir+"/filemanager.php?ajax=1&target=" + data.target + "&thumb=" + data.thumb,
                  dataType: "html",
                  method: "GET"
                })
                .then(function (response, status, headers, config) {
                    $scope.rawHtml = $sce.trustAsHtml(response.data);
                    $(".modal:first").addClass("filemanager-open");
                }, function (response) {
                    window.swal("Oops!", response.data.errorMsg, "error").then(function() {
                        $scope.closeFileManager();
                    });
                });

                $scope.closeFileManager = function () {
                    $uibModalInstance.dismiss("cancel");
                    setTimeout(function() {
                        console.log($(document).find('.modal').length);
                        if ($(document).find('.modal').length) {
                            $("body").addClass("modal-open");
                        }
                    }, 1000);
                };
            },
            scope: $scope,
            size: "lg",
            backdrop  : "static",
            keyboard: false,
        });
        
        uibModalInstance.result.catch(function () { 
            setTimeout(function() {
                $("body").removeClass("modal-open");
                $("body").removeClass("filemanager-open");
            }, 500);
            uibModalInstance.close(); 
        });
    };
}]);
window.angularApp.factory("PrinterDeleteModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(printer) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeprinterDeleteModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-trash\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/printer.php?printer_id=" + printer.printer_id + "&action_type=DELETE",
                  method: "GET"
                })
                .then(function(response) {
                    $scope.modal_title = printer.title;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 100);

                }, function(response) {
                    window.swal("Oops!", response.data.errorMsg, "error")
                    .then(function() {
                        $scope.closeprinterDeleteModal();
                    });
                });

                $(document).delegate("#printer-delete", "click", function(e) {
                    
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
                        $(datatable).DataTable().ajax.reload( null, false );

                        // Alert
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {
                            $scope.closeprinterDeleteModal();
                            $(document).find(".close").trigger("click");
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

                $scope.closeprinterDeleteModal = function () {
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
window.angularApp.factory("PrinterEditModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function($rscope) {
        var printerId;
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeprinterEditModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-pencil\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/printer.php?printer_id=" + $rscope.printer.printer_id + "&action_type=EDIT",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = $rscope.printer.title;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);
                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 100);
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                $(document).delegate("#printer-update", "click", function(e) {
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

                        // Alert Box
                        window.swal({
                          title: "Success!",
                          text: response.data.msg,
                          icon: "success",
                          buttons: true,
                          dangerMode: false,
                        })
                        .then(function (willDelete) {
                            if (willDelete) {
                                $scope.closeprinterEditModal();
                                $(document).find(".close").trigger("click");
                                printerId = response.data.id;
                                
                                $(datatable).DataTable().ajax.reload(function(json) {
                                    if ($("#row_"+printerId).length) {
                                        $("#row_"+printerId).flash("yellow", 5000);
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


                $scope.editPrinterType = 'network';
                $scope.isEditWindows = false;
                $scope.isEditLinux = $scope.isEditWindows;
                $scope.isEditNetwork = true;
                $(document).delegate("#edit-printer-type", "select2:select", function (e) {
                    var data = e.params.data;
                    var isEditWindows = false;
                    var isEditLinux = false;
                    var isEditNetwork = false;

                    if (data.element.value == "windows") {
                        isEditWindows = true;
                        isEditLinux = false;
                        isEditNetwork = false;
                    }
                    if (data.element.value == "linux") {
                        isEditWindows = false;
                        isEditLinux = true;
                        isEditNetwork = false;
                    }
                    if (data.element.value == "network") {
                        isEditWindows = false;
                        isEditLinux = false;
                        isEditNetwork = true;
                    }

                    $scope.$apply(function () {
                        $scope.isEditWindows = isEditWindows;
                        $scope.isEditLinux = isEditLinux;
                        $scope.isEditNetwork = isEditNetwork;
                    });
                });



                $scope.closeprinterEditModal = function () {
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
window.angularApp.factory("PrintReceiptModal", ["API_URL", "window", "jQuery", "$http", "$rootScope", function (API_URL, window, $, $http, $scope) {
    return function($scope) {

        var customerContact;
        if ($scope.invoiceInfo.customer_mobile && $scope.invoiceInfo.customer_mobile !== "undefined") {
            customerContact = $scope.invoiceInfo.customer_mobile;
        } else if ($scope.invoiceInfo.mobile_number && $scope.invoiceInfo.mobile_number !== "undefined") {
            customerContact = $scope.invoiceInfo.mobile_number;
        } else {
            customerContact = $scope.invoiceInfo.customer_email;
        }

        var receipt_data = {};
        receipt_data.store_name = window.store.name + "\n";

        receipt_data.header = "";
        receipt_data.header += window.store.address + "\n";
        receipt_data.header += window.store.mobile + "\n";
        receipt_data.header += "\n";

        receipt_data.info = "";
        receipt_data.info += "Date:" + $scope.invoiceInfo.created_at + "\n";
        receipt_data.info += "Invoice ID:" + $scope.invoiceInfo.invoice_id + "\n";
        receipt_data.info += "Created By:" + $scope.invoiceInfo.by + "\n";
        receipt_data.info += "\n";
        receipt_data.info += "Customer:" + $scope.invoiceInfo.customer_name + "\n";
        receipt_data.info += "Contact:" + customerContact + "\n";
        receipt_data.info += "\n";

        receipt_data.items = "";
        window.angular.forEach($scope.invoiceItems, function($row, key) {
            receipt_data.items += "#" + key+1 + " " + $row.item_name + "\n";
            receipt_data.items += $row.item_quantity + " x " + parseFloat($row.item_price).toFixed(2) + "  =  " + (parseInt($row.item_quantity) * parseFloat($row.item_price)).toFixed(2) + "\n";
        });

        var totalAmount = parseFloat($scope.invoiceInfo.payable_amount) + parseFloat($scope.invoiceInfo.previous_due);
        var paidAmount = parseFloat($scope.invoiceInfo.paid_amount) + parseFloat($scope.invoiceInfo.prev_due_paid) + (parseFloat($scope.invoiceInfo.balance) - parseFloat($scope.invoiceInfo.return_amount));
        var due = (parseFloat($scope.invoiceInfo.due) + parseFloat($scope.invoiceInfo.previous_due)) - parseFloat($scope.invoiceInfo.prev_due_paid);

        receipt_data.totals = "";
        receipt_data.totals += "\n";
        receipt_data.totals += "Subtotal: " + parseFloat($scope.invoiceInfo.payable_amount).toFixed(2) + "\n";
        receipt_data.totals += "Order Tax: " + parseFloat($scope.invoiceInfo.order_tax).toFixed(2) + "\n";
        receipt_data.totals += "Discount:" + parseFloat($scope.invoiceInfo.discount_amount).toFixed(2) + "\n";
        receipt_data.totals += "Shipping Chrg.:" + parseFloat($scope.invoiceInfo.shipping_amount).toFixed(2) + "\n";
        receipt_data.totals += "Others Chrg.:" + parseFloat($scope.invoiceInfo.others_charge).toFixed(2) + "\n";
        receipt_data.totals += "Previous Due:" + parseFloat($scope.invoiceInfo.previous_due).toFixed(2) + "\n";
        receipt_data.totals += "Amount Total:" + parseFloat(totalAmount).toFixed(2) + "\n";
        receipt_data.totals += "Amount Paid:" + parseFloat(paidAmount).toFixed(2) + "\n";
        receipt_data.totals += "Due Amount:" + parseFloat(due).toFixed(2) + "\n";
        receipt_data.totals += "Change:" + parseFloat($scope.invoiceInfo.balance).toFixed(2) + "\n";

        receipt_data.footer = "";
        if ($scope.invoiceInfo.invoice_note) {
            receipt_data.footer += $scope.invoiceInfo.invoice_note + "\n\n";
        }  else {
            receipt_data.footer += "Thank you for choosing us.";
        }

        var socket_data = {
            'printer': window.printer,
            'logo': '',
            'text': receipt_data,
            'cash_drawer': '',
        };
        $.get(window.baseUrl + '/_inc/print.php', {data: JSON.stringify(socket_data)});    
    };
}]);
window.angularApp.factory("ProductCreateModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "POSFilemanagerModal", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, POSFilemanagerModal, $scope) {
    return function($scope) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeProductCreateModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-plus\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/product.php?action_type=CREATE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Create New Product";
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.datePicker();
                        window.storeApp.select2();

                        // Generate random number
                        $(".random_num").click(function(){
                            $(this).parent(".input-group").children("input").val(window.storeApp.generateCardNo(8));
                        });
                        
                    }, 500);

                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                // Submit Product Form
                $(document).delegate("#create-product-submit", "click", function(e) {
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

                            $scope.product = response.data.product;
                            $scope.closeProductCreateModal();
                            $(document).find(".close").trigger("click");

                            // insert product into select2
                            var select = $(document).find('#product_id');
                            if (select.length) {
                                var option = $('<option></option>').
                                     attr('selected', true).
                                     text(response.data.product.p_name).
                                     val(response.data.id);
                                option.appendTo(select);
                                select.trigger('change');
                            }

                            // increase product count
                            var productCount = $(document).find("#product-count h3");
                            if (productCount) {
                                productCount.text(parseInt(productCount.text()) + 1);
                            }

                            // Callback
                            if ($scope.ProductCreateModalCallback) {
                                $scope.ProductCreateModalCallback($scope);
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

                // Multiple image
                $scope.imgArray = [];
                $scope.imgSerial = 0;
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

                $scope.closeProductCreateModal = function () {
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
window.angularApp.factory("ProductDeleteModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(product) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeProductDeleteModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-delete\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/product.php?p_id=" + product.p_id + "&action_type=DELETE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = product.p_name;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                }, function(data) {
                   window.swal("Oops!", "an error occured!", "error");
                });

                // Submit product delete form
                $(document).delegate("#product-delete-submit", "click", function(e) {
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
                        alertMsg += "<p><i class=\"fa fa-check\"></i> " + response.data.msg + "</p>";
                        alertMsg += "</div>";
                        form.find(".box-body").before(alertMsg);
                        $("#product-product-list").DataTable().ajax.reload( null, false);
                        if (response.data.action_type == "soft_delete") {
                            $("#total-trash").text(parseInt($("#total-trash").text())+1);
                        }

                        // Alert
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {
                            $scope.closeProductDeleteModal();
                            $(document).find(".close").trigger("click");
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

                $scope.closeProductDeleteModal = function () {
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
window.angularApp.factory("ProductReturnModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(product) {
        var productId;
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeProductDecrementModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-refresh\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/product_return.php?p_id=" + product.p_id,
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                $scope.modal_title = product.p_name;
                $scope.rawHtml = $sce.trustAsHtml(response.data);
                }, function(data) {
                   window.swal("Oops!", "an error occured!", "error");
                });

                 // Submit product return form
                $(document).delegate("#save-product-return", "click", function(e) {
                    e.stopImmediatePropagation();
                    e.stopPropagation();
                    e.preventDefault();

                    var $tag = $(this);
                    var $btn = $tag.button("loading");
                    var form = $($tag.data("form"));
                    //var datatable = $tag.data("datatable");
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
                            alertMsg += "<p><i class=\"fa fa-check\"></i>" + response.data.msg + ".</p>";
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
                                $scope.closeProductDecrementModal();
                                $(document).find(".close").trigger("click");
                                productId = response.data.id;
                                
                                $("#product-product-list").DataTable().ajax.reload(function(json) {
                                    if ($("#row_"+productId).length) {
                                        $("#row_"+productId).flash("yellow", 5000);
                                    }
                                }, false);

                            } else {
                                $("#product-product-list").DataTable().ajax.reload(null, false);
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
                
                // Close modal
                $scope.closeProductDecrementModal = function () {
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
window.angularApp.factory("ProductViewModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(product) {
       var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeProductViewModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-eye\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/product.php?p_id=" + product.p_id + "&action_type=VIEW",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = product.p_name;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);
                }, function(response) {
                    window.swal("Oops!", response.data.errorMsg, "error")
                    .then(function() {
                        $scope.closeProductViewModal();
                    });
                });

                // Close modal
                $scope.closeProductViewModal = function () {
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
window.angularApp.factory("StoreDeleteModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(store) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeStoreDeleteModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-trash\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/store.php?store_id=" + store.store_id + "&action_type=DELETE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = store.name;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 100);
                    
                }, function(data) {
                    window.swal("Oops!", window.response.data.errorMsg, "error")
                    .then(function() {
                        $scope.closeStoreDeleteModal();
                    });
                });

                $(document).delegate("#store-delete", "click", function(e) {
                    
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
                        $(datatable).DataTable().ajax.reload( null, false );

                        // Alert
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {
                            $scope.closeStoreDeleteModal();
                            $(document).find(".close").trigger("click");
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

                $scope.closeStoreDeleteModal = function () {
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
window.angularApp.factory("SupplierCreateModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function($scope) {
        var uibSupplierModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeSupplierCreateModal();\" type=\"button\" class=\"close supplier-create-modal\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-plus\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/supplier.php?action_type=CREATE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Create New Supplier";
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 500);

                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                // Submit Box Form
                $(document).delegate("#create-supplier-submit", "click", function(e) {
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
                            $scope.closeSupplierCreateModal();
                            $(document).find(".close").trigger("click");

                            // insert supplier into select2
                            var select = $(document).find('#sup_id');
                            if (select.length) {
                                var option = $('<option></option>').
                                     attr('selected', true).
                                     text(response.data.supplier.sup_name).
                                     val(response.data.id);
                                option.appendTo(select);
                                select.trigger('change');
                            }

                            // increase supplier count
                            var supplierCount = $(document).find("#supplier-count h3");
                            if (supplierCount) {
                                supplierCount.text(parseInt(supplierCount.text()) + 1);
                            }

                            // Callback
                            if ($scope.SupplierCreateModalCallback) {
                                $scope.SupplierCreateModalCallback($scope);
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

                $scope.closeSupplierCreateModal = function () {
                    uibSupplierModalInstance.dismiss("cancel");
                };
            },
            scope: $scope,
            size: "md",
            backdrop  : "static",
            keyboard: true,
        });

        uibSupplierModalInstance.result.catch(function () { 
            uibSupplierModalInstance.close(); 
        });
    };
}]);
window.angularApp.factory("SupplierDeleteModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(supplier) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeSupplierDeleteModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-trash\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/supplier.php?sup_id=" + supplier.sup_id + "&action_type=DELETE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = supplier.sup_name;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 100);
                
                }, function(response) {
                    window.swal("Oops!", response.data.errorMsg, "error")
                    .then(function() {
                        $scope.closeSupplierDeleteModal();
                    });
                });

                $(document).delegate("#supplier-delete", "click", function(e) {
                    
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
                        $(datatable).DataTable().ajax.reload( null, false );

                        // Alert
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {
                            $scope.closeSupplierDeleteModal();
                            $(document).find(".close").trigger("click");
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

                $scope.closeSupplierDeleteModal = function () {
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
window.angularApp.factory("SupplierEditModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(supplier) {
        var supId;
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeSupplierEditModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-pencil\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/supplier.php?sup_id=" + supplier.sup_id + "&action_type=EDIT",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = supplier.sup_name;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);
                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 100);
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                $(document).delegate("#supplier-update", "click", function(e) {
                    
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
                        $(datatable).DataTable().ajax.reload( null, false );

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
                                $scope.closeSupplierEditModal();
                                $(document).find(".close").trigger("click");
                                supId = response.data.id;
                                
                                $(datatable).DataTable().ajax.reload(function(json) {
                                    if ($("#row_"+supId).length) {
                                        $("#row_"+supId).flash("yellow", 5000);
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

                $scope.closeSupplierEditModal = function () {
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
window.angularApp.factory("BrandDeleteModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(brand) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeBrandDeleteModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-trash\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/brand.php?brand_id=" + brand.brand_id + "&action_type=DELETE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = brand.brand_name;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 100);
                
                }, function(response) {
                    window.swal("Oops!", response.data.errorMsg, "error")
                    .then(function() {
                        $scope.closeBrandDeleteModal();
                    });
                });

                $(document).delegate("#brand-delete", "click", function(e) {
                    
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
                        $(datatable).DataTable().ajax.reload( null, false );

                        // Alert
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {
                            $scope.closeBrandDeleteModal();
                            $(document).find(".close").trigger("click");
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

                $scope.closeBrandDeleteModal = function () {
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
window.angularApp.factory("BrandEditModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(brand) {
        var supId;
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeBrandEditModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-pencil\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/brand.php?brand_id=" + brand.brand_id + "&action_type=EDIT",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = brand.brand_name;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);
                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 100);
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                $(document).delegate("#brand-update", "click", function(e) {
                    
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
                        $(datatable).DataTable().ajax.reload( null, false );

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
                                $scope.closeBrandEditModal();
                                $(document).find(".close").trigger("click");
                                supId = response.data.id;
                                
                                $(datatable).DataTable().ajax.reload(function(json) {
                                    if ($("#row_"+supId).length) {
                                        $("#row_"+supId).flash("yellow", 5000);
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

                $scope.closeBrandEditModal = function () {
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
window.angularApp.factory("UserCreateModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function($scope) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeUserCreateModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-plus\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/user.php?action_type=CREATE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Create New User";
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 500);
                    
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                // Submit Box Form
                $(document).delegate("#create-user-submit", "click", function(e) {
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
                            $scope.closeUserCreateModal();
                            $(document).find(".close").trigger("click");

                            // insert user into select2
                            var select = $(document).find('#user_id');
                            if (select.length) {
                                var option = $('<option></option>').
                                     attr('selected', true).
                                     text(response.data.user.username).
                                     val(response.data.id);
                                option.appendTo(select);
                                select.trigger('change');
                            }

                            // increase user count
                            var userCount = $(document).find("#user-count h3");
                            if (userCount) {
                                userCount.text(parseInt(userCount.text()) + 1);
                            }

                            // Callback
                            if ($scope.UserCreateModalCallback) {
                                $scope.UserCreateModalCallback($scope);
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

                $scope.closeUserCreateModal = function () {
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
window.angularApp.factory("UserDeleteModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(user) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeUserDeleteModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-trash\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/user.php?id=" + user.id + "&action_type=DELETE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = user.username;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 100);
                
                }, function(response) {
                    window.swal("Oops!", response.data.errorMsg, "error")
                    .then(function() {
                        $scope.closeUserDeleteModal();
                    });
                });

                $(document).delegate("#user-delete", "click", function(e) {
                    
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
                        $(datatable).DataTable().ajax.reload( null, false );

                        // Alert
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {
                            $scope.closeUserDeleteModal();
                            $(document).find(".close").trigger("click");
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

                $scope.closeUserDeleteModal = function () {
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
window.angularApp.factory("UserEditModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(user) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeUserEditModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-pencil\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/user.php?id=" + user.id + "&action_type=EDIT",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = user.username;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                        window.storeApp.datePicker();
                    }, 100);

                }, function(data) {
                   window.swal("Oops!", window.response.data.errorMsg, "error");
                });

                $(document).delegate("#user-update", "click", function(e) {
                    
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
                                $scope.closeUserEditModal();
                                $(document).find(".close").trigger("click");
                                window.userId = response.data.id;
                                
                                $(datatable).DataTable().ajax.reload(function(json) {
                                    if ($("#row_"+window.userId).length) {
                                        $("#row_"+window.userId).flash("yellow", 5000);
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

                $scope.closeUserEditModal = function () {
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
window.angularApp.factory("UserGroupCreateModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function($scope) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeUserGroupCreateModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-plus\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/user_group.php?action_type=CREATE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Create New UserGroup";
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 500);
                    
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                // Submit Box Form
                $(document).delegate("#create-usergroup-submit", "click", function(e) {
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
                            $scope.closeUserGroupCreateModal();
                            $(document).find(".close").trigger("click");

                            // insert usergroup into select2
                            var select = $(document).find('#group_id');
                            if (select.length) {
                                var option = $('<option></option>').
                                     attr('selected', true).
                                     text(response.data.usergroup.name).
                                     val(response.data.id);
                                option.appendTo(select);
                                select.trigger('change');
                            }

                            // increase usergroup count
                            var usergroupCount = $(document).find("#usergroup-count h3");
                            if (usergroupCount) {
                                usergroupCount.text(parseInt(usergroupCount.text()) + 1);
                            }

                            // Callback
                            if ($scope.UserGroupCreateModalCallback) {
                                $scope.UserGroupCreateModalCallback($scope);
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

                $scope.closeUserGroupCreateModal = function () {
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
window.angularApp.factory("UserGroupDeleteModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(usergroup) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                        "<button ng-click=\"closeUsergroupDelteModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                       "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-trash\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                        "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                    "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/user_group.php?group_id=" + usergroup.group_id + "&action_type=DELETE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = usergroup.name;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 100);
                    
                }, function(response) {
                    window.swal("Oops!", response.data.errorMsg, "error")
                    .then(function() {
                        $scope.closeUsergroupDelteModal();
                    });
                });

                $(document).delegate("#user-group-delete", "click", function(e) {

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
                        $(datatable).DataTable().ajax.reload( null, false );

                        // Alert
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {
                            $scope.closeUsergroupDelteModal();
                            $(document).find(".close").trigger("click");
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

                $scope.closeUsergroupDelteModal = function () {
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
window.angularApp.factory("UserGroupEditModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function(API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(usergroup) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeUsergroupEditModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-pencil\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/user_group.php?group_id=" + usergroup.group_id + "&action_type=EDIT",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = usergroup.name;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);
                }, function(data) {
                   window.swal("Oops!", window.response.data.errorMsg, "error");
                });

                $(document).delegate(".user-group-update", "click", function(e) {
                    
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
                                $scope.closeUsergroupEditModal();
                                $(document).find(".close").trigger("click");
                                window.groupId = response.data.id;
                                
                                $(datatable).DataTable().ajax.reload(function(json) {
                                    if ($("#row_"+window.groupId).length) {
                                        $("#row_"+ window.groupId).flash("yellow", 5000);
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

                $scope.closeUsergroupEditModal = function () {
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
window.angularApp.factory("UserInvoiceDetailsModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    var $from = window.getParameterByName("from");
    var $to = window.getParameterByName("to");
    return function($scope) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeDueCollectionModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-list\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/invoice.php?action_type=INVOICEDETAILS&user_id=" + $scope.userID + "&from=" + $from + "&to=" + $to,
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Invoice List of " + $scope.username;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 500);
                    
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                $scope.closeDueCollectionModal = function () {
                    $uibModalInstance.dismiss("cancel");
                };
            },
            scope: $scope,
            size: "lg",
            backdrop  : "static",
            keyboard: true, // ESC key close enable/disable
            resolve: {
                userForm: function () {
                    // return usergroupForm;
                }
            }
        });

        uibModalInstance.result.catch(function () { 
            uibModalInstance.close(); 
        });
    };
}]);

window.angularApp.factory("UserInvoiceDueDetailsModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    var $from = window.getParameterByName("from");
    var $to = window.getParameterByName("to");
    return function($scope) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeDueCollectionModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\">{{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/invoice.php?action_type=INVOICEDUEDETAILS&user_id=" + $scope.userID + "&from=" + $from + "&to=" + $to,
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Invoice List of " + $scope.username;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 500);
                    
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                $scope.closeDueCollectionModal = function () {
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
window.angularApp.factory("GiftcardCreateModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function($scope) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeGiftcardCreateModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-plus\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/giftcard.php?action_type=CREATE",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Create New Giftcard";
                    $scope.rawHtml = $sce.trustAsHtml(response.data);
                    setTimeout(function() {
                        window.storeApp.datePicker();
                        window.storeApp.select2();
                        $(".random_card_no").click(function(){
                            $(this).parent(".input-group").children("input").val(window.storeApp.generateCardNo(16));
                        });
                         $(".random_card_no").trigger("click");
                    }, 500);
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                // Submit Giftcard Form
                $(document).delegate("#create-giftcard-submit", "click", function(e) {
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
                        $scope.card_no = response.data.giftcard.card_no;
                        $btn.button("reset");
                        var alertMsg = "<div class=\"alert alert-success\">";
                        alertMsg += "<p><i class=\"fa fa-check\"></i> " + response.data.msg + ".</p>";
                        alertMsg += "</div>";
                        form.find(".giftcard-body").before(alertMsg);

                        // Alert
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {

                            // close modalwindow
                            $scope.closeGiftcardCreateModal();
                            $(document).find(".close").trigger("click");

                            // insert giftcard into select2
                            var select = $(document).find("#giftcard_id");
                            if (select.length) {
                            
                                var option = $("<option></option>").
                                     attr("selected", true).
                                     text(response.data.giftcard.card_no).
                                     val(response.data.id);
                                option.appendTo(select);
                                select.trigger("change");
                            }

                            // increase store count
                            var giftcardCount = $(document).find("#giftcard-count h3");
                            if (giftcardCount) {
                                giftcardCount.text(parseInt(giftcardCount.text()) + 1);
                            }

                            // Callback
                            if ($scope.GiftcardCreateModalCallback) {
                                $scope.GiftcardCreateModalCallback($scope);
                            }

                        });

                    }, function(response) {
                        $btn.button("reset");
                        var alertMsg = "<div class=\"alert alert-danger\">";
                        window.angular.forEach(response.data, function(value, key) {
                            alertMsg += "<p><i class=\"fa fa-warning\"></i> " + value + ".</p>";
                        });
                        alertMsg += "</div>";
                        form.find(".giftcard-body").before(alertMsg);
                        $(":input[type=\"button\"]").prop("disabled", false);
                        window.swal("Oops!", response.data.errorMsg, "error");
                    });
                });

                $scope.closeGiftcardCreateModal = function () {
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
window.angularApp.factory("GiftcardEditModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(giftcard) {
        var giftcardId;
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeGiftcardEditModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-pencil\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile='rawHtml'>Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: API_URL + "/_inc/giftcard.php?id=" + giftcard.id + "&action_type=EDIT",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = giftcard.card_no;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);
                    setTimeout(function() {
                        window.storeApp.datePicker();
                        window.storeApp.select2();
                        $(".random_card_no").click(function(){
                            $(this).parent(".input-group").children("input").val(window.storeApp.generateCardNo(16));
                        });
                    }, 500);
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                $(document).delegate("#giftcard-update", "click", function(e) {
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
                        url: API_URL + "/_inc/" + actionUrl,
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
                        form.find(".giftcard-body").before(alertMsg);

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

                                $scope.closeGiftcardEditModal();
                                $(document).find(".close").trigger("click");
                                giftcardId = response.data.id;
                                
                                $(datatable).DataTable().ajax.reload(function(json) {
                                    if ($("#row_"+giftcardId).length) {
                                        $("#row_"+giftcardId).flash("yellow", 5000);
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
                        form.find(".giftcard-body").before(alertMsg);
                        $(":input[type=\"button\"]").prop("disabled", false);
                        window.swal("Oops!", response.data.errorMsg, "error");
                    });

                });

                $scope.closeGiftcardEditModal = function () {
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
window.angularApp.factory("GiftcardViewModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function (giftcard) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeGiftcardViewModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\">{{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/giftcard.php?card_no=" + giftcard.card_no + "&action_type=VIEW",
                  method: "GET"
                })
                .then(function (response, status, headers, config) {
                    $scope.modal_title = "Gift Card (" + giftcard.card_no + ")";
                    $scope.rawHtml = $sce.trustAsHtml(response.data);
                }, function (response) {
                    window.swal("Oops!", response.data.errorMsg, "error").then(function() {
                        $scope.closeGiftcardViewModal();
                    });
                });

                $scope.closeGiftcardViewModal = function () {
                    $uibModalInstance.dismiss("cancel");
                };
            },
            scope: $scope,
            size: "md",
            backdrop  : "static",
            keyboard: true,
            resolve: {
                userForm: function () {
                    // return supplierForm;
                }
            }
        });

        uibModalInstance.result.catch(function () { 
            uibModalInstance.close(); 
        });
    };
}]);
window.angularApp.factory("GiftcardTopupModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(giftcard) {
        var giftcardId;
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeGiftcardTopupModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-pencil\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile='rawHtml'>Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: API_URL + "/_inc/giftcard.php?id=" + giftcard.id + "&action_type=TOPUP",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Topup Gift Card (" + giftcard.card_no + ")";
                    $scope.rawHtml = $sce.trustAsHtml(response.data);
                    setTimeout(function() {
                        window.storeApp.datePicker();
                        window.storeApp.select2();
                        $(".random_card_no").click(function(){
                            $(this).parent(".input-group").children("input").val(window.storeApp.generateCardNo(16));
                        });
                    }, 500);
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                $(document).delegate("#giftcard-topup-save", "click", function(e) {
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
                        url: API_URL + "/_inc/" + actionUrl,
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
                        form.find(".giftcard-body").before(alertMsg);

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

                                $scope.closeGiftcardTopupModal();
                                $(document).find(".close").trigger("click");
                                giftcardId = response.data.id;
                                
                                $(datatable).DataTable().ajax.reload(function(json) {
                                    if ($("#row_"+giftcardId).length) {
                                        $("#row_"+giftcardId).flash("yellow", 5000);
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
                        form.find(".giftcard-body").before(alertMsg);
                        $(":input[type=\"button\"]").prop("disabled", false);
                        window.swal("Oops!", response.data.errorMsg, "error");
                    });

                });

                $scope.closeGiftcardTopupModal = function () {
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
window.angularApp.factory("InvoiceSMSModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function($scope) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closeInvoiceSMSModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-paper-plane\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/sms/index.php?action_type=FORM&invoice_id="+$scope.invoiceID,
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "SEND SMS";
                    $scope.rawHtml = $sce.trustAsHtml(response.data);   
                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                // Send SMS
                $(document).delegate("#send", "click", function(e) {
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
                            $scope.closeInvoiceSMSModal();
                            $(document).find(".close").trigger("click");
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

                $scope.closeInvoiceSMSModal = function () {
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
window.angularApp.factory("PaymentOnlyModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function($parentScope) {
        $scope.order = $parentScope.order;
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button id=\"payment_only_modal\" ng-click=\"closePaymentOnlyModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\">" +
                                "<span class=\"fa fa-fw fa-list\"></span> {{ modal_title }}" +
                                "<span ng-click=\"loadModal();\" class=\"fa fa-fw fa-refresh pointer\"></span>" +
                            "</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\" style=\"padding: 0px;overflow-x: hidden;\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>" +
                        "<div class=\"modal-footer\">" +
                            "<button ng-click=\"closePaymentOnlyModal();\" type=\"button\" class=\"btn btn-danger radius-50\"><i class=\"fa fa-fw fa-close\"></i> Close</button>" +
                            "<button  ng-click=\"payNow();\" type=\"button\" class=\"btn btn-success radius-50\"><i class=\"fa fa-fw fa-money\"></i> Pay Now &rarr;</button>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $scope.loadModal = function() {
                    $(document).find("body").addClass("overlay-loader");
                    $http({
                      url: window.baseUrl + "/_inc/template/payment_only_form.php?customer_id="+$scope.order.customer_id,
                      method: "GET"
                    })
                    .then(function(response, status, headers, config) {
                        $scope.modal_title = "Payment > " + $scope.order.customer_name;
                        $scope.rawHtml = $sce.trustAsHtml(response.data);
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
                        $scope.modal_title = "Payment > " + $scope.order.customer_name;
                        $scope.rawPaymentMethodHtml = $sce.trustAsHtml(response.data);
                        if ($scope.pmethodCode == 'credit') {
                            if (parseFloat($scope.customerBalance) < parseFloat($scope.order.due)) {
                                window.toastr.error("Insufficient Balance!", "Warning!");
                            } else {
                                $scope.paidAmount = parseFloat($scope.order.due);
                            }
                        }
                        $(document).find("body").removeClass("overlay-loader");
                    }, function(response) {
                       window.swal("Oops!", response.data.errorMsg, "error");
                       $(document).find("body").removeClass("overlay-loader");
                    });
                };

                $scope.payNow = function() {
                    $(document).find(".modal").addClass("overlay-loader");
                    var form = $("#checkout-form");
                    var data = form.serialize();
                    $http({
                        url: window.baseUrl + "/_inc/payment.php?action_type=PAYMENT",
                        method: "POST",
                        data: data,
                        cache: false,
                        processData: false,
                        contentType: false,
                        dataType: "json"
                    }).
                    then(function(response) {
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {
                            if ($scope.order.datatable) {
                                $($scope.order.datatable).DataTable().ajax.reload(null, false);
                            };
                            if ($parentScope.loadOrders) {
                                $parentScope.loadOrders();
                            }
                            $(document).find("#payment_only_modal").trigger("click");
                        });
                        $(document).find(".modal").removeClass("overlay-loader");
                    }, function(response) {
                        if (window.store.sound_effect == 1) {
                            window.storeApp.playSound("error.mp3");
                        }
                        window.swal("Oops!", response.data.errorMsg, "error");
                        $(document).find(".modal").removeClass("overlay-loader");
                    });
                };

                $scope.payNowWithFullPaid = function() {
                    $scope.paidAmount = $scope.order.due;
                    setTimeout(function() {
                        $scope.payNow();
                    }, 100);
                };

                $scope.payNowWhilePressEnter = function($event) {
                    if(($event.keyCode || $event.which) == 13){
                        $scope.payNow();
                    }
                };

                $scope.closePaymentOnlyModal = function () {
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
window.angularApp.factory("PurchaseInvoiceInfoEditModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(invoice) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closePurchaseInvoiceInfoEditModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\"><span class=\"fa fa-fw fa-pencil\"></span> {{ modal_title }}</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $http({
                  url: window.baseUrl + "/_inc/purchase.php?invoice_id=" + invoice.invoice_id + "&action_type=INVOICEINFOEDIT",
                  method: "GET"
                })
                .then(function(response, status, headers, config) {
                    $scope.modal_title = "Edit Invoice > " + invoice.invoice_id;
                    $scope.rawHtml = $sce.trustAsHtml(response.data);

                    setTimeout(function() {
                        window.storeApp.select2();
                    }, 100);

                }, function(response) {
                   window.swal("Oops!", response.data.errorMsg, "error");
                });

                $(document).delegate("#invoice-update", "click", function(e) {
                    
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
                                $scope.closePurchaseInvoiceInfoEditModal();
                                $(document).find(".close").trigger("click");
                                invoiceId = response.data.id;
                                
                                $(datatable).DataTable().ajax.reload(function(json) {
                                    if ($("#row_"+invoiceId).length) {
                                        $("#row_"+invoiceId).flash("yellow", 5000);
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
                $scope.closePurchaseInvoiceInfoEditModal = function () {
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
window.angularApp.factory("PurchasePaymentModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function(invoice) {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button ng-click=\"closePurchasePaymentModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\">" +
                                "<span class=\"fa fa-fw fa-list\"></span> {{ modal_title }}" +
                                "<span ng-click=\"loadModal();\" class=\"fa fa-fw fa-refresh pointer\"></span>" +
                            "</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\" style=\"padding: 0px;overflow-x: hidden;\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>" +
                        "<div class=\"modal-footer\">" +
                            "<button ng-click=\"closePurchasePaymentModal();\" type=\"button\" class=\"btn btn-danger radius-50\"><span class=\"fa fa-fw fa-close\"></span> Close</button>" +
                            "<button  ng-click=\"payNow();\" type=\"button\" class=\"btn btn-success radius-50\"><span class=\"fa fa-fw fa-money\"></span> Pay Now &rarr;</button>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $scope.loadModal = function() {
                    $(document).find("body").addClass("overlay-loader");
                    $http({
                      url: window.baseUrl + "/_inc/purchase.php?action_type=PAYMENTFORMDETAILS&invoice_id="+invoice.invoice_id,
                      method: "GET"
                    })
                    .then(function(response, status, headers, config) {
                        $scope.modal_title = "Purchase Payment";
                        $scope.order = response.data.order;
                        $scope.rawHtml = $sce.trustAsHtml(response.data.html);
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

                $scope.selectPaymentMethod = function(pmethodId,pmethodName) {
                    $(document).find("body").addClass("overlay-loader");
                    $scope.pmethodId = pmethodId;
                    $scope.pmethodName = pmethodName;
                    $(".pmethod_item").removeClass("active");
                    $("#pmethod_"+pmethodId).addClass("active");
                    $http({
                      url: window.baseUrl + "/_inc/purchase_payment.php?action_type=FIELD&pmethod_id=" + pmethodId,
                      method: "GET"
                    })
                    .then(function(response, status, headers, config) {
                        $scope.modal_title = "Purchase Payment";
                        $scope.rawPaymentMethodHtml = $sce.trustAsHtml(response.data);
                        $(document).find("body").removeClass("overlay-loader");
                    }, function(response) {
                       window.swal("Oops!", response.data.errorMsg, "error");
                       $(document).find("body").removeClass("overlay-loader");
                    });
                };

                $scope.payNow = function() {
                    $(document).find(".modal").addClass("overlay-loader");
                    var form = $("#checkout-form");
                    var data = form.serialize();
                    $http({
                        url: window.baseUrl + "/_inc/purchase_payment.php?action_type=PAYMENT",
                        method: "POST",
                        data: data,
                        cache: false,
                        processData: false,
                        contentType: false,
                        dataType: "json"
                    }).
                    then(function(response) {
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {
                            $scope.closePurchasePaymentModal();
                        });
                        $(document).find(".modal").removeClass("overlay-loader");
                        $("#invoice-invoice-list").DataTable().ajax.reload(null, false);
                    }, function(response) {
                        if (window.store.sound_effect == 1) {
                            window.storeApp.playSound("error.mp3");
                        }
                        window.swal("Oops!", response.data.errorMsg, "error");
                        $(document).find(".modal").removeClass("overlay-loader");
                    });
                };

                $scope.payNowWithFullPaid = function() {
                    $scope.paidAmount = $scope.order.due;
                    setTimeout(function() {
                        $scope.payNow();
                    }, 100);
                };

                $scope.payNowWhilePressEnter = function($event) {
                    if(($event.keyCode || $event.which) == 13){
                        $scope.payNow();
                    }
                };

                $scope.closePurchasePaymentModal = function () {
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
window.angularApp.factory("SellReturnModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function($parentScope) {
        $scope.order = $parentScope.order;
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button id=\"sell_return_modal\" ng-click=\"closeSellReturnModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\">" +
                                "<span class=\"fa fa-fw fa-list\"></span> {{ modal_title }}" +
                                "<span ng-click=\"loadModal();\" class=\"fa fa-fw fa-refresh pointer\"></span>" +
                            "</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\" style=\"padding: 0px;overflow-x: hidden;\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>" +
                        "<div class=\"modal-footer\">" +
                            "<button ng-click=\"closeSellReturnModal();\" type=\"button\" class=\"btn btn-danger radius-50\"><span class=\"fa fa-fw fa-close\"></span> Close</button>" +
                            "<button  ng-click=\"returnNow();\" type=\"button\" class=\"btn btn-success radius-50\"><span class=\"fa fa-fw fa-save\"></span> Return Now &rarr;</button>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $scope.loadModal = function() {
                    $(document).find("body").addClass("overlay-loader");
                    $http({
                      url: window.baseUrl + "/_inc/template/sell_return_form.php",
                      method: "GET"
                    })
                    .then(function(response, status, headers, config) {
                        $scope.modal_title = "Return > " +$scope.order.invoice_id;
                        $scope.rawHtml = $sce.trustAsHtml(response.data);
                        setTimeout(function() {
                            storeApp.customCheckbox();
                            storeApp.bootBooxHeightAdjustment();
                            $(document).find("body").removeClass("overlay-loader");
                        }, 500);                 
                    }, function(response) {
                       window.swal("Oops!", response.data.errorMsg, "error");
                       $(document).find("body").removeClass("overlay-loader");
                    });
                };
                $scope.loadModal();

                $scope.returnNow = function() {
                    $(document).find(".modal").addClass("overlay-loader");
                    var form = $("#sell-return-form");
                    var data = form.serialize();
                    $http({
                        url: window.baseUrl + "/_inc/sell_return.php?action_type=RETURN",
                        method: "POST",
                        data: data,
                        cache: false,
                        processData: false,
                        contentType: false,
                        dataType: "json"
                    }).
                    then(function(response) {
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {
                            if ($scope.order.datatable) {
                                $($scope.order.datatable).DataTable().ajax.reload(null, false);
                            };
                            if ($parentScope.loadOrders) {
                                $parentScope.loadOrders();
                            }
                            $(document).find("#sell_return_modal").trigger("click");
                        });
                        $(document).find(".modal").removeClass("overlay-loader");
                    }, function(response) {
                        if (window.store.sound_effect == 1) {
                            window.storeApp.playSound("error.mp3");
                        }
                        window.swal("Oops!", response.data.errorMsg, "error");
                        $(document).find(".modal").removeClass("overlay-loader");
                    });
                };

                $scope.closeSellReturnModal = function () {
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
window.angularApp.factory("PurchaseReturnModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function($parentScope) {
        $scope.order = $parentScope.order;
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template: "<div class=\"modal-header\">" +
                            "<button id=\"purchase-return-modal\" ng-click=\"closePurchaseReturnModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                           "<h3 class=\"modal-title\" id=\"modal-title\">" +
                                "<span class=\"fa fa-fw fa-list\"></span> {{ modal_title }}" +
                                "<span ng-click=\"loadModal();\" class=\"fa fa-fw fa-refresh pointer\"></span>" +
                            "</h3>" +
                        "</div>" +
                        "<div class=\"modal-body\" id=\"modal-body\" style=\"padding: 0px;overflow-x: hidden;\">" +
                            "<div bind-html-compile=\"rawHtml\">Loading...</div>" +
                        "</div>" +
                        "<div class=\"modal-footer\">" +
                            "<button ng-click=\"closePurchaseReturnModal();\" type=\"button\" class=\"btn btn-danger radius-50\"><span class=\"fa fa-fw fa-close\"></span> Close</button>" +
                            "<button  ng-click=\"returnNow();\" type=\"button\" class=\"btn btn-success radius-50\"><span class=\"fa fa-fw fa-save\"></span> Return Now &rarr;</button>" +
                        "</div>",
            controller: function ($scope, $uibModalInstance) {
                $scope.loadModal = function() {
                    $(document).find("body").addClass("overlay-loader");
                    $http({
                      url: window.baseUrl + "/_inc/template/purchase_return_form.php",
                      method: "GET"
                    })
                    .then(function(response, status, headers, config) {
                        $scope.modal_title = "Return > " +$scope.order.invoice_id;
                        $scope.rawHtml = $sce.trustAsHtml(response.data);
                        setTimeout(function() {
                             storeApp.customCheckbox();
                            storeApp.bootBooxHeightAdjustment();
                            $(document).find("body").removeClass("overlay-loader");
                        }, 500);                 
                    }, function(response) {
                       window.swal("Oops!", response.data.errorMsg, "error");
                       $(document).find("body").removeClass("overlay-loader");
                    });
                };
                $scope.loadModal();

                $scope.returnNow = function() {
                    $(document).find(".modal").addClass("overlay-loader");
                    var form = $("#purchase-return-form");
                    var data = form.serialize();
                    $http({
                        url: window.baseUrl + "/_inc/purchase_return.php?action_type=RETURN",
                        method: "POST",
                        data: data,
                        cache: false,
                        processData: false,
                        contentType: false,
                        dataType: "json"
                    }).
                    then(function(response) {
                        window.swal("Success", response.data.msg, "success")
                        .then(function(value) {
                            if ($scope.order.datatable) {
                                $($scope.order.datatable).DataTable().ajax.reload(null, false);
                            };
                            if ($parentScope.loadOrders) {
                                $parentScope.loadOrders();
                            }
                            $(document).find("#purchase-return-modal").trigger("click");
                        });
                        $(document).find(".modal").removeClass("overlay-loader");
                    }, function(response) {
                        if (window.store.sound_effect == 1) {
                            window.storeApp.playSound("error.mp3");
                        }
                        window.swal("Oops!", response.data.errorMsg, "error");
                        $(document).find(".modal").removeClass("overlay-loader");
                    });
                };

                $scope.closePurchaseReturnModal = function () {
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
window.angularApp.factory("ExpenseSummaryModal", ["API_URL", "window", "jQuery", "$http", "$uibModal", "$sce", "$rootScope", function (API_URL, window, $, $http, $uibModal, $sce, $scope) {
    return function () {
        var uibModalInstance = $uibModal.open({
            animation: true,
            ariaLabelledBy: "modal-title",
            ariaDescribedBy: "modal-body",
            template:   "<div id=\"data-modal\" class=\"modal-inner\">" +
                            "<div class=\"modal-header\">" +
								"<button ng-click=\"closeExpenceViewModal();\" type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
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
                  url: window.baseUrl + "/_inc/expense.php?action_type=SUMMARY",
                  method: "GET"
                })
                .then(function (response, status, headers, config) {
                    $scope.modal_title = "Expense Summary";
                    $scope.rawHtml = $sce.trustAsHtml(response.data);
                }, function (response) {
                   window.swal("Oops!", response.data.errorMsg, "error")
                    .then(function() {
                        $scope.closeExpenceViewModal();
                    });
                });

                $scope.closeExpenceViewModal = function () {
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