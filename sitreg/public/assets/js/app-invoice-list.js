/**
 * App Invoice List (jquery)
 */

"use strict";

$(function () {
  // Variable declaration for table
  var dt_invoice_table = $(".invoice-list-table");

  // Invoice datatable
  if (dt_invoice_table.length) {
    var dt_invoice = dt_invoice_table.DataTable({
      // ajax: assetsPath + 'json/invoice-list.json', // JSON file to add data
      processing: true,
      serverSide: true,
      pageLength: 10,
      // bFilter: false,
      ajax: baseUrl + "app/invoice/data",
      columns: [
        // columns according to JSON
        { data: "id" },
        { data: "order_id" },
        { data: "issued_date" },
        { data: "client_name" }, //
        { data: "total" }, //
        { data: "invoice_status" }, //
        { data: "action" }, //
      ],
      columnDefs: [
        {
          // For Responsive
          className: "control",
          responsivePriority: 2,
          searchable: false,
          targets: 0,
          render: function (data, type, full, meta) {
            return "";
          },
        },
        {
          // Invoice ID
          targets: 1,
          render: function (data, type, full, meta) {
            console.log(full);
            var $invoice_id = full["invoice_id"];
            var url = "app/invoice/preview/" + full.id;
            // Creates full output for row
            var $row_output =
              '<a href="' +
              baseUrl +
              url +
              '"><span class="fw-medium">#' +
              $invoice_id +
              "</span></a>";
            return $row_output;
          },
        },
        {
          // Client name and Service
          targets: 2,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            console.log(full);
            var $name = full["client_name"],
              $image = full["image"];
            // $rand_num = Math.floor(Math.random() * 11) + 1,
            // $user_img = $rand_num + '.png';
            if ($image) {
              // For Avatar image
              var $output =
                '<img src="' +
                assetsPath +
                "images/users_images/" +
                $image +
                '" alt="Avatar" class="rounded-circle">';
            } else {
              // For Avatar badge
              var stateNum = Math.floor(Math.random() * 6),
                states = [
                  "success",
                  "danger",
                  "warning",
                  "info",
                  "dark",
                  "primary",
                  "secondary",
                ],
                $state = states[stateNum],
                $name = full["client_name"],
                $initials = $name.match(/\b\w/g) || [];
              $initials = (
                ($initials.shift() || "") + ($initials.pop() || "")
              ).toUpperCase();
              $output =
                '<span class="avatar-initial rounded-circle bg-label-' +
                $state +
                '">' +
                $initials +
                "</span>";
            }
            // Creates full output for avatar row
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar avatar-sm me-2">' +
              $output +
              "</div>" +
              "</div>" +
              '<div class="d-flex flex-column">' +
              '<a href="' +
              baseUrl +
              "app/user/view/account/" +
              full.user_id +
              '" class="text-body text-truncate"><span class="fw-medium">' +
              $name +
              "</span></a>" +
              '<small class="text-truncate text-muted">' +
              // $service +
              "</small>" +
              "</div>" +
              "</div>";
            return $row_output;
          },
        },
        {
          // Total Invoice Amount
          targets: 3,
          render: function (data, type, full, meta) {
            var $total = full["total"];
            return '<span class="d-none">' + $total + "</span>$" + $total;
          },
        },
        {
          // Due Date
          targets: 4,
          render: function (data, type, full, meta) {
            var $issued_date = full["issued_date"];
            // // Creates full output for row
            // var $row_output =
            //   '<span class="d-none">' +
            //   moment($due_date).format('YYYYMMDD') +
            //   '</span>' +
            //   moment($due_date).format('DD MMM YYYY');
            // $due_date;
            return $issued_date;
          },
        },
        {
          targets: 5,
          visible: false,
        },
        {
          // Actions
          targets: -1,
          title: "Actions",
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            var url = "app/invoice/print/" + full.id;
            return (
              '<div class="d-flex align-items-center">' +
              '<a href="' +
              baseUrl +
              "app/invoice/preview/" +
              full.id +
              '"data-bs-toggle="tooltip" class="text-body" data-bs-placement="top" title="Preview Invoice"><i class="bx bx-show mx-1"></i></a>' +
              // '<div class="dropdown">' +
              // '<a href="javascript:;" class="btn dropdown-toggle hide-arrow text-body p-0" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
              '<a href="' +
              baseUrl +
              url +
              '"target="_blank" data-bs-toggle="tooltip" class="text-body" data-bs-placement="top" title="Invoice Download"><i class="fa-solid fa-download"></i></i></a>' +
              // '</div>' +
              "</div>"
            );
          },
        },
      ],
      order: [[0, "desc"]],
      dom:
        '<"row mx-1"' +
        '<"col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start gap-3"l<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start mt-md-0 mt-3"B>>' +
        '<"col-12 col-md-6 d-flex align-items-center justify-content-end flex-column flex-md-row pe-3 gap-md-3"f<"invoice_status mb-3 mb-md-0">>' +
        ">t" +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        ">",

      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return "Details of " + data["full_name"];
            },
          }),
          type: "column",
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== "" // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    "<td>" +
                    col.title +
                    ":" +
                    "</td> " +
                    "<td>" +
                    col.data +
                    "</td>" +
                    "</tr>"
                : "";
            }).join("");

            return data
              ? $('<table class="table"/><tbody />').append(data)
              : false;
          },
        },
      },
    });
  }

  // On each datatable draw, initialize tooltip
  dt_invoice_table.on("draw.dt", function () {
    var tooltipTriggerList = [].slice.call(
      document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl, {
        boundary: document.body,
      });
    });
  });

  // Delete Record
  $(".invoice-list-table tbody").on("click", ".delete-record", function () {
    dt_invoice.row($(this).parents("tr")).remove().draw();
  });

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $(".dataTables_filter .form-control").removeClass("form-control-sm");
    $(".dataTables_length .form-select").removeClass("form-select-sm");
  }, 300);
});
