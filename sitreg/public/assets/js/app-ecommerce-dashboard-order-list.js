/**
 * app-ecommerce-order-list Script kapil
 */

'use strict';

// Datatable (jquery)

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

  var dt_order_table = $('.datatables-dashboard-order'),
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
    dt_order_table.before('<h4 style="margin-top: 1rem !important; margin-left:1rem;" class="">Latest 10 Orders</h4>');
    var dt_products = dt_order_table.DataTable({
      processing: true,
      serverSide: true,
      pageLength: 10,
      bFilter: false,
      bPaginate: false,
      ajax: '/app/ecommerce/dashboard/order/list',
      columns: [
        // columns according to JSON
        { data: 'orders_id' },
        { data: 'order_id' },
        { data: 'created_at' },
        { data: 'first_name' },
        { data: 'order_status' },
        { data: 'action' } //method_number
      ],
      columnDefs: [
        { visible: false, targets: [0] },
        // {
        //   // For Responsive
        //   className: 'control',
        //   searchable: false,
        //   orderable: false,
        //   responsivePriority: 2,
        //   targets: 0,
        //   render: function (data, type, full, meta) {
        //     return '';
        //   }
        // },
        // {
        //   // For Checkboxes
        //   targets: 0,
        //   orderable: false,
        //   checkboxes: {
        //     selectAllRender: '<input type="checkbox" class="form-check-input">'
        //   },
        //   render: function () {
        //     return '<input type="checkbox" class="dt-checkboxes form-check-input" >';
        //   },
        //   searchable: false
        // },
        {
          // Order ID
          targets: 1,
          render: function (data, type, full, meta) {
            var $order_id = full['order_id'];
            var id = full.orders_id;
            var url = 'app/ecommerce/order/details/' + id;
            // Creates full output for row
            var $row_output = '<a href=" ' + baseUrl + url + '"><span class="fw-medium">#' + $order_id + '</span></a>';
            return $row_output;
          }
        },
        {
          // Date and Time
          targets: 2,
          render: function (data, type, full, meta) {
            var date = full.created_at; // convert the date string to a Date object
            // var timeX = full['time'].substring(0, 5);
            // var formattedDate = date.toLocaleDateString('en-US', {
            //   month: 'short',
            //   day: 'numeric',
            //   year: 'numeric',
            //   time: 'numeric'
            // });
            return '<span class="text-nowrap">' + date + '</span>';
          }
        },
        {
          // Customers
          targets: 3,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            var $name =
              (full['first_name'] ? full['first_name'] : '') + ' ' + (full['last_name'] ? full['last_name'] : '');
            var $email = full['email'] ? full['email'] : full['mobile'];
            var $avatar = full['image'];
            var $isAdmin = full['admin'] == 1; // Check if admin is 1

            var viewUser = baseUrl + 'app/user/view/account/' + full['user_id'];

            // Avatar or Avatar badge based on presence of $avatar
            var $output;
            if ($avatar) {
              $output =
                '<img src="' + assetsPath + 'images/users_images/' + $avatar + '" alt="Avatar" class="rounded-circle">';
            } else {
              var stateNum = Math.floor(Math.random() * 6);
              var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              var $state = states[stateNum];
              var $initials = $name.trim() !== '' ? $name.match(/\b\w/g) || [] : ['?'];
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
              ($name.trim() !== '' ? $name : ' ') +
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
          // Status
          targets: 4,
          render: function (data, type, full, meta) {
            var $status = full['status'];
            // console.log($status);
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
        },

        {
          // Actions
          targets: 5,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            var viewUrl = 'app/ecommerce/order/details/' + full.orders_id;
            return (
              '<div class="d-inline-block text-nowrap">' +
              '<a href="javascript:0;" class="btn btn-sm btn-icon delete-record" title="Delete Order" onclick="deleteOrder(' +
              full.orders_id +
              ')" ><i class="bx bx-trash"></i>' +
              '<a href="' +
              baseUrl +
              viewUrl +
              '" data-bs-toggle="tooltip" class="text-body" data-bs-placement="top" title="Preview Order"><i class="bx bx-show mx-1"></i></a>' +
              '</div>'
            );
          }
        }
      ],
      order: [0, 'desc']
    });
    $('.dataTables_length').addClass('mt-0 mt-md-3 me-3');
    $('.dt-action-buttons').addClass('pt-0');
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
        url: baseUrl + 'app/ecommerce/dashboard/order/delete/' + id,
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
          location.href = baseUrl + 'app/ecommerce/dashboard';
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
