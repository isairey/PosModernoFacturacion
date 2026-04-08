window.angularApp.controller("LanguageController", [
    "$scope",
    "API_URL",
    "window",
    "jQuery",
    "$compile",
    "$uibModal",
    "$http",
    "$sce",
    "LanguageCreateModal",
    'LanguageEditModal',
function (
    $scope,
    API_URL,
    window,
    $,
    $compile,
    $uibModal,
    $http,
    $sce,
    LanguageCreateModal,
    LanguageEditModal
) {
    "use strict";

    var dt = $("#language-language-list");
    var languageId;
    var i;
    
    var hideColums = dt.data("hide-colums").split(",");
    var hideColumsArray = [];
    if (hideColums.length) {
        for (i = 0; i < hideColums.length; i+=1) {     
           hideColumsArray.push(parseInt(hideColums[i]));
        }
    }

    var $lang = window.getParameterByName("lang");
    var $keyType = window.getParameterByName("key_type");
    var $actionType = window.getParameterByName("action_type");

    //================
    // Start datatable
    //================

    dt.dataTable({
        "oLanguage": {sProcessing: "<img src='../assets/itsolution24/img/loading2.gif'>"},
        "processing": true,
        "dom": "lfBrtip",
        "serverSide": true,
        "ajax": API_URL + "/_inc/language.php?ignore_lang_change=yes&lang="+$lang+"&key_type="+$keyType+"&action_type="+$actionType,
        "order": [[ 1, "asc"]],
        "aLengthMenu": [
            [10, 25, 50, 100, 200, -1],
            [10, 25, 50, 100, 200, "All"]
        ],
        "columnDefs": [
            {"targets": [1, 2, 3], "orderable": false},
            {"className": "text-center", "targets": [2, 3]},
            {"className": "text-right", "targets": [0]},
            {"visible": false,  "targets": hideColumsArray},
            { 
                "targets": [0],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#language-language-list thead tr th:eq(0)").html());
                }
            },
            { 
                "targets": [1],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#language-language-list thead tr th:eq(1)").html());
                }
            },
            { 
                "targets": [2],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#language-language-list thead tr th:eq(2)").html());
                }
            },
            { 
                "targets": [3],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#language-language-list thead tr th:eq(3)").html());
                }
            },
        ],
        "aoColumns": [
            {data : "lang_key"},
            {data : "lang_value"},
            {data : "btn_translate"},
            {data : "btn_delete"}
        ],
        "pageLength": window.settings.datatable_item_limit,
        "buttons": [
            {
                extend:    "print",footer: 'true',
                text:      "<i class=\"fa fa-print\"></i>",
                titleAttr: "Print",
                title: "Language",
                customize: function ( win ) {
                    $(win.document.body)
                        .css( 'font-size', '10pt' )
                        .append(
                            '<div><b><i>Powered by: ITsolution24.com</i></b></div>'
                        )
                        .prepend(
                            '<div class="dt-print-heading"><img class="logo" src="'+window.logo+'"/><h2 class="title">'+window.store.name+'</h2><p>Printed on: '+window.formatDate(new Date())+'</p></div>'
                        );
 
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
                },
                exportOptions: {
                    columns: [ 0, 1, 2 ]
                }
            },
            {
                extend:    "copyHtml5",
                text:      "<i class=\"fa fa-files-o\"></i>",
                titleAttr: "Copy",
                title: window.store.name + " > Language",
                exportOptions: {
                    columns: [ 0, 1, 2 ]
                }
            },
            {
                extend:    "excelHtml5",
                text:      "<i class=\"fa fa-file-excel-o\"></i>",
                titleAttr: "Excel",
                title: window.store.name + " > Language",
                exportOptions: {
                    columns: [ 0, 1, 2 ]
                }
            },
        ],
    });

    // Tranlation
    var $id, $btn, $langID, $langKey, $langValue, $queryData; 
    $(document).delegate(".transbtn", "click", function(e) {
        e.stopImmediatePropagation();
        e.stopPropagation();
        e.preventDefault();

        $btn = $(this);
        $id = $btn.data("id");
        $langID = $btn.data("langid");
        $langKey = $btn.data("key");
        $langValue = $("#value"+$id).val();

        $queryData = "id="+$id+"&lang_id="+$langID+"&lang_key="+$langKey+"&lang_value="+$langValue;
        $http({
            url: window.baseUrl + "/_inc/language.php?action_type=TRANSLATE",
            method: "POST",
            data: $queryData,
            cache: false,
            processData: false,
            contentType: false,
            dataType: "json"
        }).
        then(function(response) {

            $btn.button("reset");
            $(":input[type=\"button\"]").prop("disabled", false);
            $(":input[type=\"text\"]").prop("disabled", false);
            var alertMsg = response.data.msg;
            window.toastr.success(alertMsg, "Success!");

            $id = response.data.id;
            dt.DataTable().ajax.reload(function(json) {
                if ($("#row_"+$id).length) {
                    // $("#row_"+$id).flash("yellow", 5000);
                }
            }, false);

        }, function(response) {

            $btn.button("reset");
            $(":input[type=\"button\"]").prop("disabled", false);
            $(":input[type=\"text\"]").prop("disabled", false);
            var alertMsg = "<div>";
            window.angular.forEach(response.data, function(value) {
                alertMsg += "<p>" + value + ".</p>";
            });
            alertMsg += "</div>";
            window.toastr.warning(alertMsg, "Warning!");
        });

    });

    // Delete language key
    $(document).delegate(".deletebtn", "click", function(e) {
        e.stopImmediatePropagation();
        e.stopPropagation();
        e.preventDefault();
        var d = dt.DataTable().row( $(this).closest("tr") ).data();
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
                    method: "POST",
                    url: API_URL + "/_inc/language.php",
                    data: "lang_key="+d.lang_key+"&action_type=DELETE",
                    dataType: "JSON"
                })
                .then(function(response) {
                    dt.DataTable().ajax.reload( null, false );
                    window.swal("success!", response.data.msg, "success");
                }, function(response) {
                    window.swal("Oops!", response.data.errorMsg, "error");
                });
            }
        });
    });

    // Add New Language
    $scope.LanguageCreateModal = function() {
        LanguageCreateModal($scope);
    }

    // Edit Language
    $scope.LanguageEditModal = function(id, name) {
        LanguageEditModal({lang_id:id, lang_name:name});
    }

    // Delete Language
    $scope.deleteLanguage = function(id) {
        window.swal({
          title: "Delete!",
          text: "Are you sure?",
          icon: "warning",
          buttons: true,
          dangerMode: false,
        })
        .then(function(willDelete) {
            if (willDelete) {
                $(document).find("body").addClass("overlay-loader");
                $http({
                    method: "POST",
                    url: API_URL + "/_inc/language.php",
                    data: "id="+id+"&action_type=DELETELANGUAGE",
                    dataType: "JSON"
                })
                .then(function(response) {
                    $(document).find("body").removeClass("overlay-loader");
                    window.swal("success!", response.data.msg, "success");
                    window.location = window.baseUrl+"/"+window.adminDir+"/language.php?lang=en";
                }, function(response) {
                    window.swal("Oops!", response.data.errorMsg, "error");
                    $(document).find("body").removeClass("overlay-loader");
                });
            }
        });
    };
}]);