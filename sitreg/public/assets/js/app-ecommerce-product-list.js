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
  var dt_product_table = $('.datatables-products'),
    productAdd = baseUrl + 'app/ecommerce/product/add',
    statusObj = {
      1: {
        title: 'Scheduled',
        class: 'bg-label-warning'
      },
      2: {
        title: 'Publish',
        class: 'bg-label-success'
      },
      3: {
        title: 'Inactive',
        class: 'bg-label-danger'
      }
    },
    categoryObj = {
      0: {
        title: 'Household'
      },
      1: {
        title: 'Office'
      },
      2: {
        title: 'Electronics'
      },
      3: {
        title: 'Shoes'
      },
      4: {
        title: 'Accessories'
      },
      5: {
        title: 'Game'
      }
    },
    stockObj = {
      0: {
        title: 'Out_of_Stock'
      },
      1: {
        title: 'In_Stock'
      }
    },
    stockFilterValObj = {
      0: {
        title: 'Out of Stock'
      },
      1: {
        title: 'In Stock'
      }
    };

  // E-commerce Products datatable

  if (dt_product_table.length) {
    var dt_products = dt_product_table.DataTable({
      processing: true,
      serverSide: true,
      pageLength: 10,
      ajax: baseUrl + 'app/ecommerce/product/list/data',

      columns: [
        // columns according to JSON
        {
          data: 'id'
        },
        {
          data: 'product_name'
        },
        {
          data: 'product_price'
        },
        {
          data: 'product_sale_price'
        },
        {
          data: 'product_quantity'
        },
        {
          data: 'in_stock'
        },
        {
          data: 'action'
        }
      ],
      columnDefs: [
        // {
        //     // For Responsive
        //     className: 'control',
        //     searchable: false,
        //     orderable: false,
        //     responsivePriority: 2,
        //     targets: 2,
        //     render: function(data, type, full, meta) {
        //         console.log(full);
        //         return '';
        //     }
        // },
        { visible: false, targets: [0] },
        {
          // Product name and product_brand
          targets: 1,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            console.log(full);
            // console.log(full['product_image'][0]);
            var $name = full['product_name'],
              $product_about = full['product_about'];
            ($id = full['id']), ($image = full['product_image']);
            // console.log($image);
            $image = full.product_image[0]?.product_image;
            // console.log($image);
            if ($image) {
              // For Product image

              var $output =
                '<img src="' +
                assetsPath +
                'images/product_images/' +
                $image +
                '" alt="Product-' +
                $id +
                '" class="rounded-2">';
            } else {
              // For Product badge
              var stateNum = Math.floor(Math.random() * 6);
              var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              var $state = states[stateNum];
              var $name = full['product_name'],
                $initials = $name.match(/\b\w/g) || [];
              $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
              $output = '<span >' + $initials + '</span>';
            }
            // Creates full output
            // for Product name and product_brand
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center product-name">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar avatar me-2 rounded-2 bg-label-secondary">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<h6 class="text-body text-nowrap mb-0">' +
              $name +
              '</h6>' +
              '<small class="text-muted text-truncate d-none d-sm-block" style="display: inline-block; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' +
              $product_about +
              '</small>' +
              '</div>' +
              '</div>';
            return $row_output;
          }
        },

        {
          // price
          targets: 2,
          render: function (data, type, full, meta) {
            var $category_name = full['category_name'];
            // alert($product_price);
            return '<span>' + $category_name + '</span>';
          }
        },
        {
          // price
          targets: 3,
          render: function (data, type, full, meta) {
            var $product_price = full['product_price'];
            // alert($product_price);
            return '<span>' + '$' + $product_price + '</span>';
          }
        },
        {
          // sale price
          targets: 4,
          render: function (data, type, full, meta) {
            var $product_sale_price = full['product_sale_price'];
            // alert($product_price);
            return '<span>' + '$' + $product_sale_price + '</span>';
          }
        },
        {
          // Status
          targets: 5,
          render: function (data, type, full, meta) {
            var $in_stock = full['in_stock'];
            // alert($status);
            // console.log(statusObj[$status]);
            // 'bg-label-warning'
            if ($in_stock == '1') {
              $newStatus = 'InStock';
              return '<span class="badge bg-label-success" text-capitalized>' + $newStatus + '</span>';
            } else {
              $newStatus = 'OutStock';
              return '<span class="badge bg-label-danger" text-capitalized>' + $newStatus + '</span>';
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
            var confirm = "'Are you sure want to delete?'";
            var editUrl = baseUrl + 'app/ecommerce/product/edit/' + full.id;
            var viewUrl = baseUrl + 'app/ecommerce/product/show/' + full.id;
            return (
              '<div class="d-inline-block text-nowrap">' +
              '<a href="' +
              editUrl +
              '" class="btn btn-sm btn-icon" title="Edit"><i class="bx bx-edit"></i></a>' +
              '<a href="javascript:0;" class="btn btn-sm btn-icon delete-record me-2" title="Delete" onclick="deleteOrder(' +
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
        '<"card-header d-flex border-top rounded-0 flex-wrap py-md-0"' +
        '<"me-5 ms-n2 pe-5"f>' +
        '<"d-flex justify-content-start justify-content-md-end align-items-baseline"<"dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center mb-3 mb-sm-0"lB>>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      lengthMenu: [10, 20, 50, 70, 100], //for length of menu
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search Product',
        info: 'Displaying _START_ to _END_ of _TOTAL_ entries'
      }
    });
    $('.dataTables_length').addClass('mt-0 mt-md-3 me-3');
    // To remove default btn-secondary in export buttons
    $('.dt-buttons > .btn-group > button').removeClass('btn-secondary');
  }

  // Delete Record
  $('.datatables-products tbody').on('click', '.delete-record', function () {
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
    text: "You won't be able to revert product!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, Delete product!',
    customClass: {
      confirmButton: 'btn btn-primary me-2',
      cancelButton: 'btn btn-label-secondary'
    },
    buttonsStyling: false
  }).then(function (result) {
    if (result.value) {
      $.ajax({
        url: baseUrl + 'app/ecommerce/product/delete/' + id,
        type: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function () {
          Swal.fire({
            icon: 'success',
            title: 'Deleted!',
            text: 'Product has been removed.',
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });
          location.href = baseUrl + 'app/ecommerce/product/list';
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
