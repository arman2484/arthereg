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
  if (dt_customer_review.length) {
    var dt_review = dt_customer_review.DataTable({
      // ajax: assetsPath + 'json/app-ecommerce-reviews.json', // JSON file to add data
      processing: true,
      serverSide: true,
      pageLength: 10,
      bFilter: false,
      ajax: baseUrl + 'app/ecommerce/contactus/list',
      columns: [{ data: 'id' }, { data: 'username' }, { data: 'order_id' }, { data: 'message' },{ data: 'status' }, { data: ' ' }],
      columnDefs: [
        { visible: false, targets: [0] },
        {
          // reviewer
          targets: 1,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            var viewUser= baseUrl + 'app/user/view/account/'+full.user_id
            var $name = full['username'],
              $email = full['email'],
              $image = full['image'];

            if ($image) {
              // For Avatar image
              var $output =
                '<img src="' + assetsPath + 'images/users_images/' + $image + '" alt="Avatar" class="rounded-circle">';
            } else {
              // For Avatar badge
              var stateNum = Math.floor(Math.random() * 6);
              var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              var $state = states[stateNum],
                $name = full['username'],
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
              viewUser +
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
          targets: 2,
          responsivePriority: 2,
          render: function (data, type, full, meta) {
            console.log(full);
            var $order_id = full['order_id'];

            var $review = '<div>' + '<small class="fw-medium">' + $order_id + '</small>' + '</div>';

            return $review;
          }
        },
        {
          // Review
          targets: 3,
          responsivePriority: 2,
          render: function (data, type, full, meta) {
            console.log(full);
            var $message = full['message'];

            var $review = '<div>' + '<small class="fw-medium">' + $message + '</small>' + '</div>';

            return $review;
          }
        },
        {
          // User Status
          targets: 4,
          render: function (data, type, full, meta) {
            var $status = full['status'];
            if ($status == 1) {
              $status = 'In-progress';
              return '<span class="badge bg-label-primary" text-capitalized>' + $status + '</span>';
            } else {
              $status = 'Close';
              return '<span class="badge bg-label-danger" text-capitalized>' + $status + '</span>';
            }
          }
        },
        {
          // Actions
          targets: -1,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            console.log(full);
            var reply = '';
            var userReply = 'app/ecommerce/user/reply/' + full.user_id;
            if (full['status'] == 1) {
              var reply = '<i class="fa fa-reply">';
              var title = 'Reply';
            } else {
              var reply = '<i class="bx bx-show mx-1">';
              var title = 'View';
            }
            return (
              // '<div class="d-flex justify-content-sm-center align-items-sm-center">' +
              '<a href="javascript:0;" class="btn btn-sm btn-icon delete-record me-2" title="Delete" onclick="deleteReview(' +
              full.user_id +
              ')" ><i class="bx bx-trash"></i>' +
              '</a>' 
              // '</div>'
            );
          }
        }
      ],
      order: [[0, 'desc']],
      dom:
        '<"card-header d-flex flex-wrap py-3 py-sm-2"<"head-label text-center me-4 ms-1">f' +
        '>t' +
        '<"row mx-4"' +
        '<"col-md-12 col-xl-6 text-center text-xl-start pb-2 pb-lg-0 pe-0"i>' +
        '<"col-md-12 col-xl-6 d-flex justify-content-center justify-content-xl-end"p>' +
        '>',
      lengthMenu: [6, 30, 50, 70, 100],
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search order'
      }
      // Buttons with Dropdown

      // For responsive popup
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
  // alert(id);
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert review!",
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
