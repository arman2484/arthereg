/**
 * App eCommerce review
 */

'use strict';

// apex-chart
(function () {
  let cardColor, shadeColor, labelColor, headingColor;

  if (isDarkStyle) {
    cardColor = config.colors_dark.cardColor;
    labelColor = config.colors_dark.textMuted;
    headingColor = config.colors_dark.headingColor;
    shadeColor = 'dark';
  } else {
    cardColor = config.colors.cardColor;
    labelColor = config.colors.textMuted;
    headingColor = config.colors.headingColor;
    shadeColor = '';
  }
})();

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
  var dt_customer_review = $('.datatables-coupon');
  if (dt_customer_review.length) {
    var dt_review = dt_customer_review.DataTable({
      // ajax: assetsPath + 'json/app-ecommerce-reviews.json', // JSON file to add data
      processing: true,
      serverSide: true,
      pageLength: 10,
      // bFilter: false,
      ajax: baseUrl + 'app/ecommerce/coupon/list',
      columns: [
        { data: 'id' },
        { data: 'coupon_code' },
        { data: 'discount_amount' },
        { data: 'status' },
        { data: ' ' }
      ],
      columnDefs: [
        { visible: false, targets: [0] },

        {
          // Review
          targets: 1,
          responsivePriority: 2,
          render: function (data, type, full, meta) {
            console.log(full);
            var $coupon_code = full['coupon_code'];

            var $review = '<div>' + '<small class="fw-medium">' + $coupon_code + '</small>' + '</div>';

            return $review;
          }
        },
        {
          // Review
          targets: 2,
          responsivePriority: 2,
          render: function (data, type, full, meta) {
            var $discount_amount = full['discount_amount'];

            var $review = '<div>' + '<small class="fw-medium">' + $discount_amount + '</small>' + '</div>';

            return $review;
          }
        },
        {
          // Review
          targets: 3,
          responsivePriority: 2,
          render: function (data, type, full, meta) {
            var $status = full['status'];
            if ($status == 1) {
              $status = 'Active';
              return '<span class="badge bg-label-success" text-capitalized>' + $status + '</span>';
            } else {
              $status = 'Inactive';
              return '<span class="badge bg-label-danger" text-capitalized>' + $status + '</span>';
            }
          }
        },
        {
          // Actions
          targets: 4,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            var editUrl = 'app/ecommerce/coupon/edit/' + full.id;
            return (
              '<div class="d-flex align-items-sm-center justify-content-sm-center">' +
              '<a href="javascript:0;"  class="btn btn-sm btn-icon delete-record me-2" title="Delete" onclick="deleteCoupon(' +
              full.id +
              ')" ><i class="bx bx-trash"></i>' +
              '</a>' +
              '<a href="javascript:0;" data-bs-target="#offcanvasEcommerceCouponEdit"  onclick="editCoupon(' +
              full.id +
              ')" data-bs-toggle="offcanvas" class="btn btn-sm btn-icon"><i class="bx bx-edit"></i></a>' +
              '</div>'
            );
          }
        }
      ],
      order: [[0, 'desc']],
      dom:
        '<"card-header d-flex flex-wrap py-0"' +
        '<"me-5 ms-n2 pe-5"f>' +
        '<"d-flex justify-content-start justify-content-md-end align-items-baseline"<"dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center mb-3 mb-sm-0 gap-3"lB>>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      lengthMenu: [10, 30, 50, 70, 100],
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search coupon'
      },
      buttons: [
        {
          text: '<i class="bx bx-plus me-0 me-sm-1"></i>Add Coupon',
          className: 'add-new btn btn-primary ms-2',
          attr: {
            'data-bs-toggle': 'offcanvas',
            'data-bs-target': '#offcanvasEcommerceCouponList'
          }
        }
      ]

      // For responsive popup
    });
    $('.dataTables_length').addClass('mt-0 mt-md-3');
    $('.dt-action-buttons').addClass('pt-0');
    // $('.dataTables_length').addClass('mt-0 mt-md-3');
    // To remove default btn-secondary in export buttons
    // $('.dt-buttons > .btn-group > button').removeClass('btn-secondary');
  }

  // Delete Record
  $('.datatables-review tbody').on('click', '.delete-record', function () {
    dt_review.row($(this).parents('tr')).remove().draw();
  });

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});
function deleteCoupon(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert coupon!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, Delete coupon!',
    customClass: {
      confirmButton: 'btn btn-primary me-2',
      cancelButton: 'btn btn-label-secondary'
    },
    buttonsStyling: false
  }).then(function (result) {
    if (result.value) {
      $.ajax({
        url: baseUrl + 'app/ecommerce/coupon/delete/' + id,
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
          location.href = baseUrl + 'app/ecommerce/coupon';
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
function couponValidation() {
  var coupon_code = $('#coupon-code').val();
  var discount_amount = $('#discount-amount').val();
  var coupon_status = $('#coupon-status').val();
  var cnt = 0;
  if (coupon_code == '') {
    $('#coupon-code-error').html('Please enter coupon code.');
    cnt = 1;
  } else {
    $('#coupon-code-error').html('');
  }
  if (discount_amount == '') {
    $('#coupon-amount-error').html('Please enter coupon amount.');
    cnt = 1;
  } else {
    $('#category-image-error').html('');
  }
  if (coupon_status == '') {
    $('#coupon-status-error').html('Please select status.');
    cnt = 1;
  } else {
    $('#coupon-status-error').html('');
  }
  if (cnt == 1) {
    return false;
  }
}
function editCoupon(id) {
  $.ajax({
    url: baseUrl + 'app/ecommerce/coupon/edit/' + id,
    type: 'get',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function (data) {
      var json = data.data;
      $('#coupon_id').val(json.id);
      $('#coupon-code-edit').val(json.coupon_code);
      $('#discount-amount-edit').val(json.discount_amount);
      $('#coupon-description-edit').val(json.description);
      $('#coupon-status-edit').val(json.status);
    }
  });
}
function couponEditValidation() {
  var coupon_code = $('#coupon-code-edit').val();
  var discount_amount = $('#discount-amount-edit').val();
  var coupon_status = $('#coupon-status-edit').val();
  var cnt = 0;
  if (coupon_code == '') {
    $('#coupon-code-edit-error').html('Please enter coupon code.');
    cnt = 1;
  } else {
    $('#coupon-code-edit-error').html('');
  }
  if (discount_amount == '') {
    $('#coupon-amount-edit-error').html('Please enter coupon amount.');
    cnt = 1;
  } else {
    $('#coupon-amount-edit-error').html('');
  }
  if (coupon_status == '') {
    $('#coupon-status-edit-error').html('Please select status.');
    cnt = 1;
  } else {
    $('#coupon-status-edit-error').html('');
  }
  if (cnt == 1) {
    return false;
  }
}
