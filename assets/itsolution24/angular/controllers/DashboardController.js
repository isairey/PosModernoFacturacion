window.angularApp.controller("DashboardController", [
    "$scope",
    "API_URL",
    "window",
    "jQuery",
    "$http",
    "ProductCreateModal",
    "BoxCreateModal",
    "SupplierCreateModal",
    "CustomerCreateModal",
    "UserCreateModal",
    "UserGroupCreateModal",
    "BankingRowViewModal",
    "QuotationViewModal",
    "EmailModal",
function ($scope,
    API_URL,
    window,
    $,
    $http,
    ProductCreateModal,
    BoxCreateModal,
    SupplierCreateModal,
    CustomerCreateModal,
    UserCreateModal,
    UserGroupCreateModal,
    BankingRowViewModal,
    QuotationViewModal,
    EmailModal
) {
    "use strict";

    // Create new product
    $scope.createNewProduct = function () {
        $scope.hideCategoryAddBtn = true;
        $scope.hideSupAddBtn = true;
        $scope.hideBrandAddBtn = true;
        $scope.hideBoxAddBtn = true;
        $scope.hideUnitAddBtn = true;
        $scope.hideTaxrateAddBtn = true;
        ProductCreateModal($scope);
    };

    // Create new box
    $scope.createNewBox = function () {
        BoxCreateModal($scope);
    };

    // Create new supplier
    $scope.createNewSupplier = function () {
        SupplierCreateModal($scope);
    };

    // Create new customer
    $scope.createNewCustomer = function () {
        CustomerCreateModal($scope);
    };

    // Create new user
    $scope.createNewUser = function () {
        $scope.hideGroupAddBtn = true;
        UserCreateModal($scope);
    };

    // Create new usergroup
    $scope.createNewUsergroup = function () {
        UserGroupCreateModal($scope);
    };

    // $http({
    //     url: API_URL + "/_inc/ecnesil.php?type=STOCKCHECK",
    //     method: "GET",
    //     cache: false,
    //     processData: false,
    //     contentType: false,
    //     dataType: "json"
    // }).
    // then(function(res) {
    //     if (res.data.status !== 'valid') {
    //         window.location = window.baseUrl+'/index.php';
    //     }
    // });


    // View deposit details
    $(".view-deposit").on("click", function (e) {
        e.preventDefault();
        var refNo = $(this).data("refno");
        BankingRowViewModal({ref_no: refNo}, 'deposit');
    });

    // View withdraw details
    $(".view-withdraw").on("click", function (e) {
        e.preventDefault();
        var refNo = $(this).data("refno");
        BankingRowViewModal({ref_no: refNo}, 'withdraw');
    });

    $scope.QuotationViewModal = function(data) {
        QuotationViewModal(data);
    }

}]);