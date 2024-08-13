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

  var dt_order_table = $('.datatables-store'),
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
      ajax: '/app/ecommerce/store/list/data',
      columns: [
        // columns according to JSON
        { data: 'id' },
        { data: 'store_logo' },
        { data: 'store_name' },
        { data: 'vendor_id' },
        { data: 'status' },
        { data: 'vendor_request' },
        { data: 'action' } //method_number
      ],
      columnDefs: [
        { visible: false, targets: [0] },

        {
          // Order ID
          targets: 1,
          render: function (data, type, full, meta) {
            console.log(full);
            var $avatar = full['store_logo'];
            var $output =
              '<img src="' +
              assetsPath +
              'images/store_logo/' +
              full['store_logo'] +
              '" alt="Avatar" class="rounded-circle">';
            var $row_output = '<div class="avatar me-2">' + $output + '</div>';
            return $row_output;
          }
        },
        {
          // Customers
          targets: 2,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            function truncate(str, n) {
              return str.length > n ? str.substr(0, n - 1) + '...' : str;
            }

            var truncatedDescription = truncate(full['store_description'], 50); // Adjust the number to your desired length
            var truncatedAddress = truncate(full['store_address'], 50); // Adjust the number to your desired length

            var $row_output =
              '<div class="d-flex justify-content-start align-items-center order-name text-nowrap">' +
              '<div class="d-flex flex-column">' +
              '<small class="text-muted" style="line-height: 20px;"><b>Name: </b>' +
              full['store_name'] +
              '</small>' +
              '<small class="text-muted" style="line-height: 20px;"><b>Description: </b>' +
              truncatedDescription +
              '</small>' +
              '<small class="text-muted" style="line-height: 20px;"><b>Address: </b> ' +
              truncatedAddress +
              '</small>' +
              '<small class="text-muted"><b>Verified Store:</b> ' +
              '<span class="" style="background-color: #43D187;border-radius: 1rem;width: fit-content;padding-inline: 15px;font-size: 12px;color:white;padding-block:3px; font-weight: 400; font-size: 75%;"><i class="fa fa-check-circle"></i><span style="margin-left:3px;">Verified</span></span>' +
              '</small>' +
              '</div>' +
              '</div>';
            return $row_output;
          }
        },
        {
          targets: 3,
          render: function (data, type, full, meta) {
            return '<span class="text-nowrap">' + full['vendor_id'] + '</span>';
          }
        },
        {
          targets: 4,
          render: function (data, type, full, meta) {
            var status = full['status'];
            if (status == '1') {
              return (
                ' <label class="switch switch-primary switch-sm me-4 pe-2"><input type="checkbox" id="active" name="status" class="switch-input" checked=""><span class="switch-toggle-slider"  onclick="changeStatus(' +
                full.id +
                ',' +
                status +
                ')"><span class="switch-on"></span><span class="switch-off"></span></span></label>'
              );
            } else {
              return (
                ' <label class="switch switch-primary switch-sm me-4 pe-2"><input id="inactive" type="checkbox" name="status" class="switch-input"><span class="switch-toggle-slider" onclick="changeStatus(' +
                full.id +
                ',' +
                status +
                ')"><span class="switch-on"></span><span class="switch-off"></span></span></label>'
              );
            }
          }
        },
        {
          targets: 5,
          render: function (data, type, full, meta) {
            var $status = full['vendor_request'] ? 'Accepted' : 'Not Accepted';
            var $badgeClass = full['vendor_request'] ? 'bg-label-success' : 'bg-label-danger';
            return '<span class="badge px-2 ' + $badgeClass + '">' + $status + '</span>';
          }
        },
        {
          // Actions
          targets: 6,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            var editUrl = baseUrl + 'app/ecommerce/store/edit/' + full.id;
            var viewUrl = baseUrl + 'app/ecommerce/store/show/' + full.id;
            return (
              '<div class="d-inline-block text-nowrap">' +
              '<a href="' +
              editUrl +
              '" class="btn btn-sm btn-icon" title="Edit"><i class="bx bx-edit"></i></a>' +
              '<a href="javascript:0;" class="btn btn-sm btn-icon delete-record me-2" title="Delete" onclick="deleteStore(' +
              full.id +
              ')"><i class="bx bx-trash"></i></a>' +
              '<a href="' +
              viewUrl +
              '"data-bs-toggle="tooltip" class="text-body" data-bs-placement="top" title="Preview"><i class="bx bx-show mx-1"></i></a>' +
              // '</div>' +
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
        searchPlaceholder: 'Search',
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

function changeStatus(id, status) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert status!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, Change status!',
    customClass: {
      confirmButton: 'btn btn-primary me-2',
      cancelButton: 'btn btn-label-secondary'
    },
    buttonsStyling: false
  }).then(function (result) {
    if (result.value) {
      $.ajax({
        url: baseUrl + 'change/store/request/status/' + id,
        data: {
          status: status
        },
        type: 'get',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
          Swal.fire({
            icon: 'success',
            title: 'Changed!',
            text: 'Changed status.',
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });
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
function deleteStore(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert Store!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, Delete Store!',
    customClass: {
      confirmButton: 'btn btn-primary me-2',
      cancelButton: 'btn btn-label-secondary'
    },
    buttonsStyling: false
  }).then(function (result) {
    if (result.value) {
      $.ajax({
        url: baseUrl + 'app/ecommerce/store/delete/' + id,
        type: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
          var parentTr = $('#store-deleted-' + data.id).closest('tr');
          parentTr.remove();
          Swal.fire({
            icon: 'success',
            title: 'Deleted!',
            text: 'Store deleted successfully.',
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });
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
