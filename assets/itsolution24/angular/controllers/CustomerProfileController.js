window.angularApp.controller("CustomerProfileController", [
    "$scope",
    "API_URL",
    "window",
    "jQuery",
    "$compile",
    "$uibModal",
    "$http",
    "$sce",
    "CustomerEditModal",
    "PaymentOnlyModal",
    "CustomerAddBalanceModal",
    "CustomerSubstractBalanceModal",
    "InstallmentViewModal",
function (
    $scope,
    API_URL,
    window,
    $,
    $compile,
    $uibModal,
    $http,
    $sce,
    CustomerEditModal,
    PaymentOnlyModal,
    CustomerAddBalanceModal,
    CustomerSubstractBalanceModal,
    InstallmentViewModal
) {
    "use strict";

    var dt = $("#invoice-invoice-list");
    var customer_id = dt.data("id");
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
    var $type = window.getParameterByName("type");

    //================
    // Start datatable
    //================

    dt.dataTable({
        "oLanguage": {sProcessing: "<img src='../assets/itsolution24/img/loading2.gif'>"},
        "processing": true,
        "dom": "lfBrtip",
        "serverSide": true,
        "ajax": API_URL + "/_inc/customer_profile.php?customer_id=" + customer_id + "&from="+$from+"&to="+$to+"&type="+$type,
        "order": [[ 0, "desc"]],
        "aLengthMenu": [
            [10, 25, 50, 100, 200, -1],
            [10, 25, 50, 100, 200, "All"]
        ],
        "columnDefs": [
            {"targets": [2, 9, 10], "orderable": false},
            {"visible": false,  "targets": hideColumsArray},
            {"className": "text-right", "targets": [4, 5, 6, 7, 8]},
            {"className": "text-center", "targets": [0, 1, 2, 3, 9, 10]},
            { 
                "targets": [0],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(0)").html());
                }
            },
            { 
                "targets": [1],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(1)").html());
                }
            },
            { 
                "targets": [2],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(2)").html());
                }
            },
            { 
                "targets": [3],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(3)").html());
                }
            },
            { 
                "targets": [4],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(4)").html());
                }
            },
            { 
                "targets": [5],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(5)").html());
                }
            },
            { 
                "targets": [6],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(6)").html());
                }
            },
            { 
                "targets": [7],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(7)").html());
                }
            },
            { 
                "targets": [8],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(8)").html());
                }
            },
            { 
                "targets": [9],
                'createdCell':  function (td, cellData, rowData, row, col) {
                   $(td).attr('data-title', $("#invoice-invoice-list thead tr th:eq(9)").html());
                }
            },
        ],
        "aoColumns": [
            {data : "created_at"},
            {data : "invoice_id"},
            {data : "invoice_note"},
            {data : "items"},
            {data : "invoice_amount"},
            {data : "previous_due"},
            {data : "payable_amount"},
            {data : "paid_amount"},
            {data : "due"},
            {data : "btn_view"},
            {data : "btn_pay"}
        ],
        "pageLength": window.settings.datatable_item_limit,
        "buttons": [
            {
                extend:    "print",footer: 'true',
                text:      "<i class=\"fa fa-print\"></i>",
                titleAttr: "Print",
                title: window.customerName + " - Invoice List-"+from+" to "+to,
                stripHtml: false,
                customize: function ( win ) {
                    $(win.document.body)
                        .css( 'font-size', '10pt' )
                        .append(
                            '<div><b><i>Powered by: ITsolution24.com</i></b></div>'
                        )
                        .prepend(
                            '<div class="dt-print-heading"><img class="logo" src="'+window.logo+'"/><h2 class="title">'+window.store.name+'</h2><p>Printed on: '+window.formatDate(new Date())+'</p><h2>Name: '+window.customerName+'</h2></div>'
                        );
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
                },
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 5, 6, 7, 8 ],
                    stripHtml: false
                }
            },
            {
                extend:    "copyHtml5",
                text:      "<i class=\"fa fa-files-o\"></i>",
                titleAttr: "Copy",
                title: window.customerName + " - Invoice List-"+from+" to "+to,
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 5, 6, 7, 8 ]
                }
            },
            {
                extend:    "excelHtml5",
                text:      "<i class=\"fa fa-file-excel-o\"></i>",
                titleAttr: "Excel",
                title: window.customerName + " - Invoice List-"+from+" to "+to,
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 5, 6, 7, 8 ]
                }
            },
            {
                extend:    "csvHtml5",
                text:      "<i class=\"fa fa-file-text-o\"></i>",
                titleAttr: "CSV",
                title: window.customerName + " - Invoice List-"+from+" to "+to,
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 5, 6, 7, 8 ]
                }
            },
            {
                extend:    "pdfHtml5",
                orientation: 'landscape',
                pageSize: 'LEGAL',
                text:      "<i class=\"fa fa-file-pdf-o\"></i>",
                titleAttr: "PDF",
                download: "open",
                title: window.customerName + " - Invoice List-"+from+" to "+to,
                exportOptions: {
                    columns: [ 0, 1, 2, 4, 5, 5, 6, 7 ],
                },
                customize: function (doc) {
                    doc.content[1].table.widths =  Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                    doc.pageMargins = [5,5,5,5];
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
                    doc.content.splice( 1, 0, {
                        margin: [ 0, 0, 0, 12 ],
                        alignment: 'center',
                        fontSize: 18,
                        text: 'Name: '+window.customerName,
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

    // Edit customer
    $scope.customerEdit = function(customer_id, customer_name) {
        CustomerEditModal({customer_id:customer_id, customer_name:customer_name});
    };

    // customer due paid
    $(document).delegate("#due-paid", "click", function(e) {
        e.preventDefault();
        var customerId = $(this).data("id");
        var customerName = $(this).data("name");
        var customer = {
            id: customerId,
            name: customerName,
            dueAmount: 0,
        };
        CustomerDuePaidModal(customer);
    });

    $(document).delegate("#customer_id", "select2:select", function (e) {
        e.preventDefault();
        e.stopPropagation();
        var data = e.params.data;
        window.location = window.baseUrl+"/admin/customer_profile.php?customer_id="+data.element.value;
    });
    if (window.getParameterByName('customer_id')) {
        $("#customer_id").val(window.getParameterByName('customer_id')).trigger("change");
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
    if (window.getParameterByName("customer_id") && window.getParameterByName("paid_form")) {
        customer_id = window.getParameterByName("customer_id");
        CustomerDuePaidModal({customerId: customer_id, dueAmount: 0});
    }

    // Substract customer balance
    $scope.substractCustomerBalance = function(customer) {
        CustomerSubstractBalanceModal(customer);
    };

    // Add customer balance
    $scope.addCustomerBalance = function(customer) {
        CustomerAddBalanceModal(customer);
    };

    // View installment
    $(document).delegate("#view-installment-btn", "click", function (e) {
        e.stopPropagation();
        e.preventDefault();
        var d = dt.DataTable().row( $(this).closest("tr") ).data();
        var $tag = $(this);
        var $btn = $tag.button("loading");
        InstallmentViewModal(d);
        setTimeout(function() {
            $tag.button("reset");
        }, 300);
    });
    
}]);