window.angularApp.controller("UserProfileController", [
    "$scope",
    "API_URL",
    "window",
    "jQuery",
    "$compile",
    "$uibModal",
    "$http",
    "$sce",
    "UserEditModal",
    "PaymentOnlyModal",
function (
    $scope,
    API_URL,
    window,
    $,
    $compile,
    $uibModal,
    $http,
    $sce,
    UserEditModal,
    PaymentOnlyModal
) {
    "use strict";

    var dt = $("#invoice-invoice-list");

    // Edit user
    $scope.userEdit = function(user_id, username) {
        UserEditModal({id:user_id, username:username});
    };

    // user due paid
    $(document).delegate("#due-paid", "click", function(e) {
        e.preventDefault();
        var userId = $(this).data("id");
        var userName = $(this).data("name");
        var user = {
            id: userId,
            name: userName,
            dueAmount: 0,
        };
        UserDuePaidModal(user);
    });

    $(document).delegate("#user_id", "select2:select", function (e) {
        e.preventDefault();
        e.stopPropagation();
        var data = e.params.data;
        window.location = window.baseUrl+"/admin/user_profile.php?id="+data.element.value;
    });
    if (window.getParameterByName('id')) {
        $("#user_id").val(window.getParameterByName('id')).trigger("change");
    }

    // Payment From
    $(document).delegate("#pay_now", "click", function(e) {
        e.stopPropagation();
        e.preventDefault();
        var d = dt.DataTable().row( $(this).closest("tr") ).data();
        var $tag = $(this);
        var $btn = $tag.button("loading");
        $http({
          url: window.baseUrl + "/_inc/payment.php?action_type=ORDERDETAILS&invoice_id="+d.invoice_id,
          method: "GET"
        })
        .then(function(response, status, headers, config) {
            $scope.order = response.data.order;
            $scope.order.datatable = dt;
            PaymentOnlyModal($scope);
            setTimeout(function() {
                $tag.button("reset");
            }, 300);
        }, function(response) {
           window.swal("Oops!", response.data.errorMsg, "error");
           setTimeout(function() {
                $tag.button("reset");
            }, 300);
        });
    });

    // populate custoemr due paid form by query string
    if (window.getParameterByName("user_id") && window.getParameterByName("paid_form")) {
        user_id = window.getParameterByName("user_id");
        UserDuePaidModal({userId: user_id, dueAmount: 0});
    }
    
}]);