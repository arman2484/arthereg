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
  var dt_customer_review = $('.datatables-review');
  // customerView = baseUrl + 'app/ecommerce/customer/details/overview',
  // statusObj = {
  //   Pending: { title: 'Pending', class: 'bg-label-warning' },
  //   Published: { title: 'Published', class: 'bg-label-success' }
  // };
  // reviewer datatable
  if (dt_customer_review.length) {
    var dt_review = dt_customer_review.DataTable({
      // ajax: assetsPath + 'json/app-ecommerce-reviews.json', // JSON file to add data
      processing: true,
      serverSide: true,
      pageLength: 10,
      bFilter: false,
      ajax: baseUrl + 'app/ecommerce/reviews/list',
      columns: [
        { data: 'id' },
        { data: 'product_id' },
        { data: 'user_id' },
        { data: 'review_star' },
        { data: 'created_at' },
        { data: ' ' }
      ],
      columnDefs: [
        { visible: false, targets: [0] },
        {
          // product
          targets: 1,
          render: function (data, type, full, meta) {
            console.log(full);
            var $product = full['product_name'],
              $company_name = full['product_about'],
              $id = full['id'],
              $image = full.product_image[0]?.product_image;

            if ($image) {
              // For Avatar image
              var $output =
                '<img src="' +
                assetsPath +
                'images/product_images/' +
                $image +
                '" alt="Product-' +
                $id +
                '" class="rounded-2">';
            } else {
              // For Avatar badge
              var stateNum = Math.floor(Math.random() * 6);
              var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              var $state = states[stateNum],
                $product = full['product_name'],
                $initials = $product.match(/\b\w/g) || [];
              $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
              $output = '<span class="avatar-initial rounded bg-label-' + $state + '">' + $initials + '</span>';
            }
            // Creates full output for row
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center customer-name">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar me-2 rounded-2 bg-label-secondary">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<span class="fw-medium text-nowrap">' +
              $product +
              '</span></a>' +
              '<small class="text-muted">' +
              $company_name +
              '</small>' +
              '</div>' +
              '</div>';
            return $row_output;
          }
        },
        {
          // reviewer
          targets: 2,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            var $name = full['first_name'],
              $email = full['email'],
              $user_image = full['user_image'];

            if ($user_image) {
              // For Avatar image
              var $output =
                '<img src="' +
                assetsPath +
                'images/users_images/' +
                $user_image +
                '" alt="Avatar" class="rounded-circle">';
            } else {
              // For Avatar badge
              var stateNum = Math.floor(Math.random() * 6);
              var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              var $state = states[stateNum],
                $name = full['first_name'],
                $initials = $name.match(/\b\w/g) || [];
              $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
              $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';
            }
            // Creates full output for row
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center customer-name">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar me-2">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<a href="' +
              baseUrl +
              'app/user/view/account/' +
              full.user_id +
              '"><span class="fw-medium">' +
              $name +
              '</span></a>' +
              '<small class="text-muted text-nowrap">' +
              $email +
              '</small>' +
              '</div>' +
              '</div>';
            return $row_output;
          }
        },
        {
          // Review
          targets: 3,
          responsivePriority: 2,
          render: function (data, type, full, meta) {
            var $num = full['review_star'];
            // var $heading = full['head'];
            var $comment = full['review_message'];
            var $readOnlyRatings = $('<div class="read-only-ratings ps-0 mb-2"></div>');

            // Initialize rateYo plugin
            $readOnlyRatings.rateYo({
              rating: $num,
              rtl: isRtl,
              readOnly: true, // Make the rating read-only
              starWidth: '20px', // Set the width of each star
              spacing: '3px', // Spacing between the stars
              starSvg:
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12,2 L15.09,8.09 L22,9.9 L17,14 L18.18,20 L12,17.5 L5.82,20 L7,14 L2,9.9 L8.91,8.09 L12,2 Z" /></svg>'
            });

            var $review =
              '<div>' +
              $readOnlyRatings.prop('outerHTML') + // Get the HTML string of the rateYo plugin
              '<p class="fw-medium mb-1 text-truncate text-capitalize">' +
              '</p>' +
              '<small class="text-break pe-3">' +
              $comment +
              '</small>' +
              '</div>';

            return $review;
          }
        },
        {
          targets: 4,
          render: function (data, type, full, meta) {
            if (!full.created_at) {
              return ''; // Return empty string if created_at is not defined
            }

            var date = new Date(full.created_at); // convert the date string to a Date object
            var formattedDate = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            return '<span class="text-nowrap">' + formattedDate + '</span>';
          }
        },

        {
          // Actions
          targets: -1,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            var url = 'app/ecommerce/review/delete/' + full.id;
            return (
              '<div class="d-flex justify-content-sm-center align-items-sm-center">' +
              '<a href="javascript:0;" class="dropdown-item delete-record" title="Delete" onclick="deleteReview(' +
              full.id +
              ')" ><i class="bx bx-trash"></i>' +
              '</a>' +
              '</div>'
            );
          }
        }
      ],
      order: [[0, 'desc']],
      dom:
        '<"card-header d-flex align-items-md-center pb-md-2 flex-wrap"' +
        '<"me-5 ms-n2"f>' +
        '<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-end align-items-md-center justify-content-md-end pt-0 gap-3 flex-wrap"l<"review_filter"> <"mx-0 me-md-n3 mt-sm-0"B>>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',

      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search Review'
      }
      // Buttons with Dropdown

      // initComplete: function () {
      //   // Adding role filter once table initialized
      //   this.api()
      //     .columns(6)
      //     .every(function () {
      //       var column = this;
      //       var select = $('<select id="Review" class="form-select"><option value=""> All </option></select>')
      //         .appendTo('.review_filter')
      //         .on('change', function () {
      //           var val = $.fn.dataTable.util.escapeRegex($(this).val());
      //           column.search(val ? '^' + val + '$' : '', true, false).draw();
      //         });

      //       column
      //         .data()
      //         .unique()
      //         .sort()
      //         .each(function (d, j) {
      //           select.append('<option value="' + d + '" class="text-capitalize">' + d + '</option>');
      //         });
      //     });
      // }
    });
    $('.dataTables_length').addClass('mt-0 mt-md-3');
    // To remove default btn-secondary in export buttons
    $('.dt-buttons > .btn-group > button').removeClass('btn-secondary');
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
function deleteReview(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert review!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, Delete review!',
    customClass: {
      confirmButton: 'btn btn-primary me-2',
      cancelButton: 'btn btn-label-secondary'
    },
    buttonsStyling: false
  }).then(function (result) {
    if (result.value) {
      $.ajax({
        url: baseUrl + 'app/ecommerce/review/delete/' + id,
        type: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function () {
          Swal.fire({
            icon: 'success',
            title: 'Deleted!',
            text: 'Review has been removed.',
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });
          location.href = baseUrl + 'app/ecommerce/manage/reviews';
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
