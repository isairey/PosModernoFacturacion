window.angularApp.controller("BannerController", [
    "$scope",
    "API_URL",
    "window",
    "jQuery",
    "$compile",
    "$uibModal",
    "$http",
    "$sce",
    "POSFilemanagerModal",
    "BannerEditModal",
function (
    $scope,
    API_URL,
    window,
    $,
    $compile,
    $uibModal,
    $http,
    $sce,
    POSFilemanagerModal,
    BannerEditModal
) {
    "use strict";

    var dt = $("#banner-banner-list");
    var bannerId;
    var i;
   
    var hideColums = dt.data("hide-colums").split(",");
    var hideColumsArray = [];
    if (hideColums.length) {
        for (i = 0; i < hideColums.length; i+=1) {     
           hideColumsArray.push(parseInt(hideColums[i]));
        }
    }

    var $from = window.getParameterByName("from");
    var $to = window.getParameterByName("to");

    //================
    // Start datatable
    //================

    dt.dataTable({
        "oLanguage": {sProcessing: "<img src='../assets/itsolution24/img/loading2.gif'>"},
        "processing": true,
        "dom": "lfBrtip",
        "serverSide": true,
        "ajax": API_URL + "/_inc/banner.php?from=" + $from + "&to=" + $to,
        "order": [[ 0, "desc"]],
        "aLengthMenu": [
            [10, 25, 50, 100, 200, -1],
            [10, 25, 50, 100, 200, "All"]
        ],
        "columnDefs": [
            {"targets": [6], "orderable": false},
            {"visible": false,  "targets": hideColumsArray},
            {"className": "text-center", "targets": [0, 3, 4, 5, 6]},
            {"className": "text-right", "targets": [2]},
            { 
                "targets": [0],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#banner-banner-list thead tr th:eq(0)").html());
                }
            },
            { 
                "targets": [1],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#banner-banner-list thead tr th:eq(1)").html());
                }
            },
            { 
                "targets": [2],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#banner-banner-list thead tr th:eq(2)").html());
                }
            },
            { 
                "targets": [3],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#banner-banner-list thead tr th:eq(3)").html());
                }
            },
            { 
                "targets": [4],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#banner-banner-list thead tr th:eq(4)").html());
                }
            },
            { 
                "targets": [5],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#banner-banner-list thead tr th:eq(5)").html());
                }
            },
            { 
                "targets": [6],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#banner-banner-list thead tr th:eq(6)").html());
                }
            },
        ],
        "aoColumns": [
            {data : "id"},
            {data : "name"},
            {data : "sort_order"},
            {data : "status"},
            {data : "created_at"},
            {data : "btn_edit"},
            {data : "btn_delete"},
        ],
        "footerCallback": function ( row, data, start, end, display ) {
            var total;
            var pageTotal;
            var api = this.api();
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === "string" ?
                    i.replace(/[\$,]/g, "")*1 :
                    typeof i === "number" ?
                        i : 0;
            };
            // Total over all pages at column 2
            total = api
                .column( 2 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Total over this page
            pageTotal = api
                .column( 2, { page: "current"} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer
            $( api.column( 2 ).footer() ).html(
                pageTotal
            );
        },
        "pageLength": window.settings.datatable_item_limit,
        "buttons": [
            {
                extend:    "print",footer: 'true',
                text:      "<i class=\"fa fa-print\"></i>",
                titleAttr: "Print",
                title: window.store.name + " > Banners",
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4 ]
                }
            },
            {
                extend:    "copyHtml5",
                text:      "<i class=\"fa fa-files-o\"></i>",
                titleAttr: "Copy",
                title: window.store.name + " > Banner List",
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4 ]
                }
            },
            {
                extend:    "excelHtml5",
                text:      "<i class=\"fa fa-file-excel-o\"></i>",
                titleAttr: "Excel",
                title: window.store.name + " > Banner List",
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4 ]
                }
            },
            {
                extend:    "csvHtml5",
                text:      "<i class=\"fa fa-file-text-o\"></i>",
                titleAttr: "CSV",
                title: window.store.name + " > Banner List",
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4 ]
                }
            },
            {
                extend:    "pdfHtml5",
                text:      "<i class=\"fa fa-file-pdf-o\"></i>",
                titleAttr: "PDF",
                download: "open",
                title: window.store.name + " > Banner List",
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4 ]
                },
                customize: function (doc) {
                    doc.content[1].table.widths =  Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                    doc.pageMargins = [10,10,10,10];
                    doc.defaultStyle.fontSize = 8;
                    doc.styles.tableHeader.fontSize = 8;doc.styles.tableHeader.alignment = "left";
                    doc.styles.title.fontSize = 10;
                    // Remove spaces around page title
                    doc.content[0].text = doc.content[0].text.trim();
                    // Header
                    doc.content.splice( 1, 0, {
                        margin: [ 0, 0, 0, 12 ],
                        alignment: 'center',
                        fontSize: 8,
                        text: 'Printed on: '+window.formatDate(new Date()),
                    });
                    // Create a footer
                    doc['footer']=(function(page, pages) {
                        return {
                            columns: [
                                'Powered by ITSOLUTION24.COM',
                                {
                                    // This is the right column
                                    alignment: 'right',
                                    text: ['page ', { text: page.toString() },  ' of ', { text: pages.toString() }]
                                }
                            ],
                            margin: [10, 0]
                        };
                    });
                    // Styling the table: create style object
                    var objLayout = {};
                    // Horizontal line thickness
                    objLayout['hLineWidth'] = function(i) { return 0.5; };
                    // Vertikal line thickness
                    objLayout['vLineWidth'] = function(i) { return 0.5; };
                    // Horizontal line color
                    objLayout['hLineColor'] = function(i) { return '#aaa'; };
                    // Vertical line color
                    objLayout['vLineColor'] = function(i) { return '#aaa'; };
                    // Left padding of the cell
                    objLayout['paddingLeft'] = function(i) { return 4; };
                    // Right padding of the cell
                    objLayout['paddingRight'] = function(i) { return 4; };
                    // Inject the object in the document
                    doc.content[1].layout = objLayout;
                }
            }
        ],
    });

    //================
    // End datatable
    //================

    // Create banner
    $(document).delegate("#create-banner-submit", "click", function(e) {
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
            $(":input[type=\"button\"]").prop("disabled", false);
            var alertMsg = response.data.msg;
            window.toastr.success(alertMsg, "Success!");
            bannerId = response.data.id;
            dt.DataTable().ajax.reload(function(json) {
                if ($("#row_"+bannerId).length) {
                    $("#row_"+bannerId).flash("yellow", 5000);
                }
            }, false);

            // Reset form
            $("#reset").trigger("click");
            $("#banner_sex").val(null).trigger("change");

        }, function(response) {

            $btn.button("reset");
            $(":input[type=\"button\"]").prop("disabled", false);
            var alertMsg = "<div>";
            window.angular.forEach(response.data, function(value) {
                alertMsg += "<p>" + value + ".</p>";
            });
            alertMsg += "</div>";
            window.toastr.warning(alertMsg, "Warning!");
        });
    });

    // Edit banner
    $(document).delegate("#edit-banner", "click", function(e) {
        e.stopPropagation();
        e.preventDefault();
        var d = dt.DataTable().row( $(this).closest("tr") ).data();
        BannerEditModal(d);
    });

    // Delete banner
    $(document).delegate("#delete-banner", "click", function(e) {
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
                    url: API_URL + "/_inc/banner.php",
                    data: "id="+d.id+"&action_type=DELETE",
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

    // Multiple Image
    $scope.imgArray = [];
    $scope.imgSerial = 0;
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

    $scope.remoteImageItem = function(index)
    {
        $scope.imgArray.splice(index, 1);
    }

    // Open edit modal dialog box by query string
    if (window.getParameterByName("banner_id") && window.getParameterByName("banner_name")) {
        bannerId = window.getParameterByName("banner_id");
        var bannerName = window.getParameterByName("banner_name");
        dt.DataTable().search(bannerName).draw();
        dt.DataTable().ajax.reload(function(json) {
            $.each(json.data, function(index, obj) {
                if (obj.DT_RowId === "row_" + bannerId) {
                    BannerEditModal({banner_id: bannerId, banner_name: obj.banner_name});
                    return false;
                }
            });
        }, false);
    }
}]);