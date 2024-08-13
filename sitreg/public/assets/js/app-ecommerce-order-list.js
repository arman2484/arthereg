'use strict';
let dt_products;
let dt_products_pending;
let dt_product_completed;
$(function () {
  let borderColor, bodyBg, headingColor;

  if (isDarkStyle) {
    borderColor = config.colors_dark.borderColor;
    bodyBg = config.colors_dark.bodyBg;
    headingColor = config.colors_dark.headingColor;
  } else {
    borderColor = config.colors.borderColor;
    bodyBg = config.colors.bodyBg;
    headingColor = config.colors.headingColor;
  }

  // Variable declaration for table

  var dt_order_table = $('.datatables-order'),
    statusObj = {
      1: { title: 'Cancel', class: 'bg-label-danger' },
      2: { title: 'Delivered', class: 'bg-label-success' },
      3: { title: 'Confirmed', class: 'bg-label-primary' }
    },
    paymentObj = {
      1: { title: 'Paid', class: 'text-success' },
      2: { title: 'Pending', class: 'text-warning' },
      3: { title: 'Failed', class: 'text-danger' },
      4: { title: 'Cancelled', class: 'text-secondary' }
    };

  // E-commerce Products datatable

  if (dt_order_table.length) {
    dt_products = dt_order_table.DataTable({
      processing: true,
      serverSide: true,
      pageLength: 10,
      ajax: '/app/ecommerce/order/list/data',
      columns: [
        // columns according to JSON
        { data: 'id' },
        { data: 'order_id' },
        { data: 'created_at' },
        { data: 'first_name' },
        { data: 'order_status' },
        { data: 'payment_mode' },
        { data: 'action' } //method_number
      ],
      columnDefs: [
        { visible: false, targets: [0] },

        {
          // Order ID
          targets: 1,
          render: function (data, type, full, meta) {
            var $order_id = full['order_id'];
            var id = full.id;
            var url = 'app/ecommerce/order/details/' + id;
            // Creates full output for row
            var $row_output = '<a href=" ' + baseUrl + url + '"><span class="fw-medium">#' + $order_id + '</span></a>';
            return $row_output;
          }
        },
        {
          targets: 2,
          render: function (data, type, full, meta) {
            var date = full.created_at;
            return '<span class="text-nowrap">' + date + '</span>';
          }
        },
        {
          // Customers
          targets: 3,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            var $name = full['first_name'] + ' ' + full['last_name'],
              $email = full['email'] ? full['email'] : full['mobile'],
              $avatar = full['image'],
              $isAdmin = full['admin'] == 1; // Check if admin is 1

            var viewUser = baseUrl + 'app/user/view/account/' + full['user_id'];

            // Avatar or Avatar badge based on presence of $avatar
            var $output;
            if ($avatar) {
              $output =
                '<img src="' + assetsPath + 'images/users_images/' + $avatar + '" alt="Avatar" class="rounded-circle">';
            } else {
              var stateNum = Math.floor(Math.random() * 6);
              var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              var $state = states[stateNum],
                $initials = $name.match(/\b\w/g) || [];
              $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
              $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';
            }

            // Create full output for row
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center order-name text-nowrap">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar me-2">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<h6 class="m-0"><a href="' +
              viewUser +
              '" class="text-body">' +
              $name +
              '</a>';

            // Add POS green label if admin is 1
            if ($isAdmin) {
              $row_output += ' <span class="badge px-2 bg-label-success text-nowrap">POS</span>'; // Green label
            }

            $row_output += '</h6>' + '<small class="text-muted">' + $email + '</small>' + '</div>' + '</div>';

            return $row_output;
          }
        },
        {
          targets: 4,
          render: function (data, type, full, meta) {
            var payment_mode = full.payment_mode;
            if (payment_mode == 'Stripe') {
              return '<span class="badge px-2 bg-label-success text-nowrap">' + payment_mode + '</span>';
            } else if (payment_mode == 'COD') {
              return '<span class="badge px-2 bg-label-warning text-nowrap">' + payment_mode + '</span>';
            } else if (payment_mode == 'Razorpay') {
              return '<span class="badge px-2 bg-label-primary text-nowrap">' + payment_mode + '</span>';
            } else if (payment_mode == 'card') {
              return '<span class="badge px-2 bg-label-info text-nowrap">' + payment_mode + '</span>';
            } else {
              return '<span class="badge px-2 bg-label-secondary text-nowrap">' + payment_mode + '</span>';
            }
          }
        },
        {
          // Status
          targets: 5,
          render: function (data, type, full, meta) {
            var $status = full['order_status'];
            if ($status == 1) {
              $status = 'Confirmed';
              return '<span class="badge px-2 bg-label-primary" text-capitalized>' + $status + '</span>';
            }
            if ($status == 0) {
              $status = 'Pending';
              return '<span class="badge px-2 bg-label-secondary" text-capitalized>' + $status + '</span>';
            }
            if ($status == 2) {
              $status = 'Delivered';
              return '<span class="badge px-2 bg-label-success" text-capitalized>' + $status + '</span>';
            }
            if ($status == 3) {
              $status = 'Cancel';
              return '<span class="badge px-2 bg-label-danger" text-capitalized>' + $status + '</span>';
            }
          }
        },
        {
          // Actions
          targets: 6,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            var url = 'app/ecommerce/order/details/' + full.id;
            return (
              '<div class="d-flex justify-content-sm-center align-items-sm-center">' +
              '<a href="' +
              baseUrl +
              url +
              '" data-bs-toggle="tooltip" class="text-body" data-bs-placement="top" title="Order Preview"><i class="bx bx-show mx-1"></i></a>' +
              '<a href="javascript:0;" class="dropdown-item delete-record" onclick="deleteOrder(' +
              full.id +
              ')" ><i class="bx bx-trash"></i>' +
              '</a>' +
              '</div>'
            );
          }
        }
      ],
      order: [0, 'desc'], //set any columns order asc/desc
      dom:
        '<"card-header d-flex flex-column flex-md-row align-items-start align-items-md-center"<"ms-n2"f><"d-flex align-items-md-center justify-content-md-end mt-2 mt-md-0"l<"dt-action-buttons"B>>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      lengthMenu: [10, 40, 60, 80, 100], //for length of menu
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search Order',
        info: 'Displaying _START_ to _END_ of _TOTAL_ entries'
      },

      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['customer'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });
    $('.dataTables_length').addClass('mt-0 mt-md-3 me-3');
    $('.dt-action-buttons').addClass('pt-0');
    // To remove default btn-secondary in export buttons
    $('.dt-buttons > .btn-group > button').removeClass('btn-secondary');
  }

  // Delete Record
  $('.datatables-order tbody').on('click', '.delete-record', function () {
    dt_products.row($(this).parents('tr')).remove().draw();
  });

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});
function deleteOrder(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert order!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, Delete order!',
    customClass: {
      confirmButton: 'btn btn-primary me-2',
      cancelButton: 'btn btn-label-secondary'
    },
    buttonsStyling: false
  }).then(function (result) {
    if (result.value) {
      $.ajax({
        url: baseUrl + 'app/ecommerce/order/delete/' + id,
        type: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function () {
          Swal.fire({
            icon: 'success',
            title: 'Deleted!',
            text: 'Order has been removed.',
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });
          location.href = baseUrl + 'app/ecommerce/order/list';
        }
      });
    } else if (result.dismiss === Swal.DismissReason.cancel) {
      Swal.fire({
        title: 'Cancelled',
        text: 'Cancelled Delete :)',
        icon: 'error',
        customClass: {
          confirmButton: 'btn btn-success'
        }
      });
    }
  });
}
function pendingOrder() {
  $('.datatables-order').dataTable().fnDestroy();
  var dt_order_tables = $('.datatables-order');

  if (dt_order_tables.length) {
    var dt_products_pending = dt_order_tables.DataTable({
      processing: true,
      serverSide: true,
      pageLength: 10,
      ajax: '/app/ecommerce/order/pending',
      columns: [
        // columns according to JSON
        { data: 'id' },
        { data: 'order_id' },
        { data: 'created_at' },
        { data: 'first_name' },
        { data: 'payment_mode' },
        { data: 'order_status' },
        { data: 'action' } //method_number
      ],
      columnDefs: [
        { visible: false, targets: [0] },

        {
          // Order ID
          targets: 1,
          render: function (data, type, full, meta) {
            var $order_id = full['order_id'];
            var id = full.id;
            var url = 'app/ecommerce/order/details/' + id;
            // Creates full output for row
            var $row_output = '<a href=" ' + baseUrl + url + '"><span class="fw-medium">#' + $order_id + '</span></a>';
            return $row_output;
          }
        },
        {
          targets: 2,
          render: function (data, type, full, meta) {
            var date = full.created_at;
            return '<span class="text-nowrap">' + date + '</span>';
          }
        },
        {
          // Customers
          targets: 3,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            var $name = full['first_name'] + ' ' + full['last_name'],
              $email = full['email'] ? full['email'] : full['mobile'],
              $avatar = full['image'],
              $isAdmin = full['admin'] == 1; // Check if admin is 1

            var viewUser = baseUrl + 'app/user/view/account/' + full['user_id'];

            // Avatar or Avatar badge based on presence of $avatar
            var $output;
            if ($avatar) {
              $output =
                '<img src="' + assetsPath + 'images/users_images/' + $avatar + '" alt="Avatar" class="rounded-circle">';
            } else {
              var stateNum = Math.floor(Math.random() * 6);
              var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              var $state = states[stateNum],
                $initials = $name.match(/\b\w/g) || [];
              $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
              $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';
            }

            // Create full output for row
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center order-name text-nowrap">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar me-2">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<h6 class="m-0"><a href="' +
              viewUser +
              '" class="text-body">' +
              $name +
              '</a>';

            // Add POS green label if admin is 1
            if ($isAdmin) {
              $row_output += ' <span class="badge px-2 bg-label-success text-nowrap">POS</span>'; // Green label
            }

            $row_output += '</h6>' + '<small class="text-muted">' + $email + '</small>' + '</div>' + '</div>';

            return $row_output;
          }
        },
        {
          targets: 4,
          render: function (data, type, full, meta) {
            var payment_mode = full.payment_mode;
            if (payment_mode == 'Stripe') {
              return '<span class="badge px-2 bg-label-success text-nowrap">' + payment_mode + '</span>';
            } else if (payment_mode == 'COD') {
              return '<span class="badge px-2 bg-label-warning text-nowrap">' + payment_mode + '</span>';
            } else if (payment_mode == 'Razorpay') {
              return '<span class="badge px-2 bg-label-primary text-nowrap">' + payment_mode + '</span>';
            } else if (payment_mode == 'card') {
              return '<span class="badge px-2 bg-label-info text-nowrap">' + payment_mode + '</span>';
            } else {
              return '<span class="badge px-2 bg-label-secondary text-nowrap">' + payment_mode + '</span>';
            }
          }
        },
        {
          // Status
          targets: 5,
          render: function (data, type, full, meta) {
            var $status = full['order_status'];
            if ($status == 1) {
              $status = 'Confirmed';
              return '<span class="badge px-2 bg-label-primary" text-capitalized>' + $status + '</span>';
            }
            if ($status == 0) {
              $status = 'Pending';
              return '<span class="badge px-2 bg-label-secondary" text-capitalized>' + $status + '</span>';
            }
            if ($status == 2) {
              $status = 'Delivered';
              return '<span class="badge px-2 bg-label-success" text-capitalized>' + $status + '</span>';
            }
            if ($status == 3) {
              $status = 'Cancel';
              return '<span class="badge px-2 bg-label-danger" text-capitalized>' + $status + '</span>';
            }
          }
        },

        {
          // Actions
          targets: 6,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            var url = 'app/ecommerce/order/details/' + full.id;
            return (
              '<div class="d-flex justify-content-sm-center align-items-sm-center">' +
              '<a href="' +
              baseUrl +
              url +
              '" data-bs-toggle="tooltip" class="text-body" data-bs-placement="top" title="Order Preview"><i class="bx bx-show mx-1"></i></a>' +
              '<a href="javascript:0;" class="dropdown-item delete-record" onclick="deleteOrder(' +
              full.id +
              ')" ><i class="bx bx-trash"></i>' +
              '</a>' +
              '</div>'
            );
          }
        }
      ],
      order: [0, 'desc'], //set any columns order asc/desc
      dom:
        '<"card-header d-flex flex-column flex-md-row align-items-start align-items-md-center"<"ms-n2"f><"d-flex align-items-md-center justify-content-md-end mt-2 mt-md-0"l<"dt-action-buttons"B>>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      lengthMenu: [10, 40, 60, 80, 100], //for length of menu
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search Order',
        info: 'Displaying _START_ to _END_ of _TOTAL_ entries'
      },

      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['customer'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });
    $('.dataTables_length').addClass('mt-0 mt-md-3 me-3');
    $('.dt-action-buttons').addClass('pt-0');
    // To remove default btn-secondary in export buttons
    $('.dt-buttons > .btn-group > button').removeClass('btn-secondary');
  }

  // Delete Record
  $('.datatables-order tbody').on('click', '.delete-record', function () {
    dt_products_pending.row($(this).parents('tr')).remove().draw();
  });

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300); // Clear the DataTable and redraw it
}
function completedOrder() {
  $('.datatables-order').dataTable().fnDestroy();
  let dt_order_tables = $('.datatables-order');

  // E-commerce Products datatable

  if (dt_order_tables.length) {
    dt_product_completed = dt_order_tables.DataTable({
      processing: true,
      serverSide: true,
      pageLength: 10,
      ajax: '/app/ecommerce/order/completed',
      columns: [
        // columns according to JSON
        { data: 'id' },
        { data: 'order_id' },
        { data: 'created_at' },
        { data: 'first_name' },
        { data: 'payment_mode' },
        { data: 'order_status' },
        { data: 'action' } //method_number
      ],
      columnDefs: [
        { visible: false, targets: [0] },

        {
          // Order ID
          targets: 1,
          render: function (data, type, full, meta) {
            var $order_id = full['order_id'];
            var id = full.id;
            var url = 'app/ecommerce/order/details/' + id;
            // Creates full output for row
            var $row_output = '<a href=" ' + baseUrl + url + '"><span class="fw-medium">#' + $order_id + '</span></a>';
            return $row_output;
          }
        },
        {
          targets: 2,
          render: function (data, type, full, meta) {
            var date = full.created_at;
            return '<span class="text-nowrap">' + date + '</span>';
          }
        },
        {
          // Customers
          targets: 3,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            var $name = full['first_name'] + ' ' + full['last_name'],
              $email = full['email'] ? full['email'] : full['mobile'],
              $avatar = full['image'],
              $isAdmin = full['admin'] == 1; // Check if admin is 1

            var viewUser = baseUrl + 'app/user/view/account/' + full['user_id'];

            // Avatar or Avatar badge based on presence of $avatar
            var $output;
            if ($avatar) {
              $output =
                '<img src="' + assetsPath + 'images/users_images/' + $avatar + '" alt="Avatar" class="rounded-circle">';
            } else {
              var stateNum = Math.floor(Math.random() * 6);
              var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              var $state = states[stateNum],
                $initials = $name.match(/\b\w/g) || [];
              $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
              $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';
            }

            // Create full output for row
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center order-name text-nowrap">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar me-2">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<h6 class="m-0"><a href="' +
              viewUser +
              '" class="text-body">' +
              $name +
              '</a>';

            // Add POS green label if admin is 1
            if ($isAdmin) {
              $row_output += ' <span class="badge px-2 bg-label-success text-nowrap">POS</span>'; // Green label
            }

            $row_output += '</h6>' + '<small class="text-muted">' + $email + '</small>' + '</div>' + '</div>';

            return $row_output;
          }
        },
        {
          targets: 4,
          render: function (data, type, full, meta) {
            var payment_mode = full.payment_mode;
            if (payment_mode == 'Stripe') {
              return '<span class="badge px-2 bg-label-success text-nowrap">' + payment_mode + '</span>';
            } else if (payment_mode == 'COD') {
              return '<span class="badge px-2 bg-label-warning text-nowrap">' + payment_mode + '</span>';
            } else if (payment_mode == 'Razorpay') {
              return '<span class="badge px-2 bg-label-primary text-nowrap">' + payment_mode + '</span>';
            } else if (payment_mode == 'card') {
              return '<span class="badge px-2 bg-label-info text-nowrap">' + payment_mode + '</span>';
            } else {
              return '<span class="badge px-2 bg-label-secondary text-nowrap">' + payment_mode + '</span>';
            }
          }
        },
        {
          // Status
          targets: 5,
          render: function (data, type, full, meta) {
            var $status = full['order_status'];
            if ($status == 1) {
              $status = 'Confirmed';
              return '<span class="badge px-2 bg-label-primary" text-capitalized>' + $status + '</span>';
            }
            if ($status == 0) {
              $status = 'Pending';
              return '<span class="badge px-2 bg-label-secondary" text-capitalized>' + $status + '</span>';
            }
            if ($status == 2) {
              $status = 'Delivered';
              return '<span class="badge px-2 bg-label-success" text-capitalized>' + $status + '</span>';
            }
            if ($status == 3) {
              $status = 'Cancel';
              return '<span class="badge px-2 bg-label-danger" text-capitalized>' + $status + '</span>';
            }
          }
        },

        {
          // Actions
          targets: 6,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            var url = 'app/ecommerce/order/details/' + full.id;
            return (
              '<div class="d-flex justify-content-sm-center align-items-sm-center">' +
              '<a href="' +
              baseUrl +
              url +
              '" data-bs-toggle="tooltip" class="text-body" data-bs-placement="top" title="Order Preview"><i class="bx bx-show mx-1"></i></a>' +
              '<a href="javascript:0;" class="dropdown-item delete-record" onclick="deleteOrder(' +
              full.id +
              ')" ><i class="bx bx-trash"></i>' +
              '</a>' +
              '</div>'
            );
          }
        }
      ],
      order: [0, 'desc'], //set any columns order asc/desc
      dom:
        '<"card-header d-flex flex-column flex-md-row align-items-start align-items-md-center"<"ms-n2"f><"d-flex align-items-md-center justify-content-md-end mt-2 mt-md-0"l<"dt-action-buttons"B>>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      lengthMenu: [10, 40, 60, 80, 100], //for length of menu
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search Order',
        info: 'Displaying _START_ to _END_ of _TOTAL_ entries'
      },

      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['customer'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });
    $('.dataTables_length').addClass('mt-0 mt-md-3 me-3');
    $('.dt-action-buttons').addClass('pt-0');
    // To remove default btn-secondary in export buttons
    $('.dt-buttons > .btn-group > button').removeClass('btn-secondary');
  }

  // Delete Record
  $('.datatables-order tbody').on('click', '.delete-record', function () {
    dt_product_completed.row($(this).parents('tr')).remove().draw();
  });

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300); // Clear the DataTable and redraw it
}
function cancelOrder() {
  $('.datatables-order').dataTable().fnDestroy();
  let dt_order_tables = $('.datatables-order');

  // E-commerce Products datatable

  if (dt_order_tables.length) {
    dt_product_completed = dt_order_tables.DataTable({
      processing: true,
      serverSide: true,
      pageLength: 10,
      ajax: '/app/ecommerce/order/cancle',
      columns: [
        // columns according to JSON
        { data: 'id' },
        { data: 'order_id' },
        { data: 'created_at' },
        { data: 'first_name' },
        { data: 'payment_mode' },
        { data: 'order_status' },
        { data: 'action' } //method_number
      ],
      columnDefs: [
        { visible: false, targets: [0] },

        {
          // Order ID
          targets: 1,
          render: function (data, type, full, meta) {
            var $order_id = full['order_id'];
            var id = full.id;
            var url = 'app/ecommerce/order/details/' + id;
            // Creates full output for row
            var $row_output = '<a href=" ' + baseUrl + url + '"><span class="fw-medium">#' + $order_id + '</span></a>';
            return $row_output;
          }
        },
        {
          targets: 2,
          render: function (data, type, full, meta) {
            var date = full.created_at;
            return '<span class="text-nowrap">' + date + '</span>';
          }
        },
        {
          // Customers
          targets: 3,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            var $name = full['first_name'] + ' ' + full['last_name'],
              $email = full['email'] ? full['email'] : full['mobile'],
              $avatar = full['image'],
              $isAdmin = full['admin'] == 1; // Check if admin is 1

            var viewUser = baseUrl + 'app/user/view/account/' + full['user_id'];

            // Avatar or Avatar badge based on presence of $avatar
            var $output;
            if ($avatar) {
              $output =
                '<img src="' + assetsPath + 'images/users_images/' + $avatar + '" alt="Avatar" class="rounded-circle">';
            } else {
              var stateNum = Math.floor(Math.random() * 6);
              var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              var $state = states[stateNum],
                $initials = $name.match(/\b\w/g) || [];
              $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
              $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';
            }

            // Create full output for row
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center order-name text-nowrap">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar me-2">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<h6 class="m-0"><a href="' +
              viewUser +
              '" class="text-body">' +
              $name +
              '</a>';

            // Add POS green label if admin is 1
            if ($isAdmin) {
              $row_output += ' <span class="badge px-2 bg-label-success text-nowrap">POS</span>'; // Green label
            }

            $row_output += '</h6>' + '<small class="text-muted">' + $email + '</small>' + '</div>' + '</div>';

            return $row_output;
          }
        },
        {
          targets: 4,
          render: function (data, type, full, meta) {
            var payment_mode = full.payment_mode;
            if (payment_mode == 'Stripe') {
              return '<span class="badge px-2 bg-label-success text-nowrap">' + payment_mode + '</span>';
            } else if (payment_mode == 'COD') {
              return '<span class="badge px-2 bg-label-warning text-nowrap">' + payment_mode + '</span>';
            } else if (payment_mode == 'Razorpay') {
              return '<span class="badge px-2 bg-label-primary text-nowrap">' + payment_mode + '</span>';
            } else if (payment_mode == 'card') {
              return '<span class="badge px-2 bg-label-info text-nowrap">' + payment_mode + '</span>';
            } else {
              return '<span class="badge px-2 bg-label-secondary text-nowrap">' + payment_mode + '</span>';
            }
          }
        },
        {
          // Status
          targets: 5,
          render: function (data, type, full, meta) {
            var $status = full['order_status'];
            if ($status == 1) {
              $status = 'Confirmed';
              return '<span class="badge px-2 bg-label-primary" text-capitalized>' + $status + '</span>';
            }
            if ($status == 0) {
              $status = 'Pending';
              return '<span class="badge px-2 bg-label-secondary" text-capitalized>' + $status + '</span>';
            }
            if ($status == 2) {
              $status = 'Delivered';
              return '<span class="badge px-2 bg-label-success" text-capitalized>' + $status + '</span>';
            }
            if ($status == 3) {
              $status = 'Cancel';
              return '<span class="badge px-2 bg-label-danger" text-capitalized>' + $status + '</span>';
            }
          }
        },

        {
          // Actions
          targets: 6,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            var url = 'app/ecommerce/order/details/' + full.id;
            return (
              '<div class="d-flex justify-content-sm-center align-items-sm-center">' +
              '<a href="' +
              baseUrl +
              url +
              '" data-bs-toggle="tooltip" class="text-body" data-bs-placement="top" title="Order Preview"><i class="bx bx-show mx-1"></i></a>' +
              '<a href="javascript:0;" class="dropdown-item delete-record" onclick="deleteOrder(' +
              full.id +
              ')" ><i class="bx bx-trash"></i>' +
              '</a>' +
              '</div>'
            );
          }
        }
      ],
      order: [0, 'desc'], //set any columns order asc/desc
      dom:
        '<"card-header d-flex flex-column flex-md-row align-items-start align-items-md-center"<"ms-n2"f><"d-flex align-items-md-center justify-content-md-end mt-2 mt-md-0"l<"dt-action-buttons"B>>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      lengthMenu: [10, 40, 60, 80, 100], //for length of menu
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search Order',
        info: 'Displaying _START_ to _END_ of _TOTAL_ entries'
      },

      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['customer'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });
    $('.dataTables_length').addClass('mt-0 mt-md-3 me-3');
    $('.dt-action-buttons').addClass('pt-0');
    // To remove default btn-secondary in export buttons
    $('.dt-buttons > .btn-group > button').removeClass('btn-secondary');
  }

  // Delete Record
  $('.datatables-order tbody').on('click', '.delete-record', function () {
    dt_product_completed.row($(this).parents('tr')).remove().draw();
  });

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300); // Clear the DataTable and redraw it
}
