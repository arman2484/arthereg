/**
 * App User View - Account (jquery)
 */

$(function () {
  'use strict';

  // Variable declaration for table
  var dt_project_table = $('.datatable-project'),
    id = $('#user_id').val(),
    dt_invoice_table = $('.datatable-invoice');

  // Project datatable
  // --------------------------------------------------------------------
  if (dt_project_table.length) {
    var dt_project = dt_project_table.DataTable({
      processing: true,
      serverSide: true,
      pageLength: 10,
      ajax: '/app/user/view/account/order/list' + '/' + id,
      columns: [
        // columns according to JSON
        { data: 'id' },
        { data: 'order_id' },
        { data: 'created_at' },
        { data: 'order_status' }
      ],
      columnDefs: [
        { visible: false, targets: [0] },
        {
          // User full name and email
          targets: 1,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            var $order_id = full['order_id'];
            var url = 'app/ecommerce/order/details/' + full.id;
            // Creates full output for row
            var $row_output =
              '<div class="d-flex justify-content-left align-items-center">' +
              '<div class="d-flex flex-column">' +
              '<a href=" ' +
              baseUrl +
              url +
              '"><span class="fw-medium">#' +
              $order_id +
              '</span></a>' +
              '</div>' +
              '</div>';
            return $row_output;
          }
        },
        {
          // User full name and email
          targets: 2,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            console.log(full);
            var $created_at = full['created_at'];
            // Creates full output for row
            var $row_output =
              '<div class="d-flex justify-content-left align-items-center">' +
              '<div class="d-flex flex-column">' +
              '<span class="text-truncate fw-medium">' +
              $created_at +
              '</span>' +
              '</div>' +
              '</div>';
            return $row_output;
          }
        },
        {
          // User full name and email
          targets: 3,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            var $status = full['order_status'];
            if ($status == 1) {
              $status = 'Confirmed';
              return '<span class="badge px-2 bg-label-primary" text-capitalized>' + $status + '</span>';
            }
            if ($status == 3) {
              $status = 'Cancel';
              return '<span class="badge px-2 bg-label-danger" text-capitalized>' + $status + '</span>';
            }
            if ($status == 2) {
              $status = 'Delivered';
              return '<span class="badge px-2 bg-label-success" text-capitalized>' + $status + '</span>';
            }
            if ($status == 0) {
              $status = 'Pending';
              return '<span class="badge px-2 bg-label-primary" text-capitalized>' + $status + '</span>';
            }
          }
        }
        // {
        //   // Label
        //   targets: 4,
        //   responsivePriority: 3,
        //   render: function (data, type, full, meta) {
        //     var $created_at = full['created_at'];
        //       $color;
        //     switch (true) {
        //       case full['progress'] < 25:
        //         $color = 'bg-danger';
        //         break;
        //       case full['progress'] < 50:
        //         $color = 'bg-warning';
        //         break;
        //       case full['progress'] < 75:
        //         $color = 'bg-info';
        //         break;
        //       case full['progress'] <= 100:
        //         $color = 'bg-success';
        //         break;
        //     }
        //     return (
        //       '<div class="d-flex flex-column"><small class="mb-1">' +
        //       $progress +
        //       '</small>' +
        //       '<div class="progress w-100 me-3" style="height: 6px;">' +
        //       '<div class="progress-bar ' +
        //       $color +
        //       '" style="width: ' +
        //       $progress +
        //       '" aria-valuenow="' +
        //       $progress +
        //       '" aria-valuemin="0" aria-valuemax="100"></div>' +
        //       '</div>' +
        //       '</div>'
        //     );
        //   }
        // },
        // {
        //   targets: 5,
        //   orderable: false
        // }
      ],
      order: [[0, 'desc']],
      dom:
        '<"d-flex justify-content-between align-items-center flex-column flex-sm-row mx-4 row"' +
        '<"col-sm-4 col-12 d-flex align-items-center justify-content-sm-start justify-content-center"l>' +
        '<"col-sm-8 col-12 d-flex align-items-center justify-content-sm-end justify-content-center"f>' +
        '>t' +
        '<"d-flex justify-content-between mx-4 row"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      displayLength: 10,
      lengthMenu: [10, 25, 50, 75, 100],
      language: {
        sLengthMenu: 'Show _MENU_',
        // search: '',
        searchPlaceholder: 'Search Order'
      }
    });
  }

  // On each datatable draw, initialize tooltip
  dt_invoice_table.on('draw.dt', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl, {
        boundary: document.body
      });
    });
  });

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});
