/**
 * App eCommerce Category List
 */

"use strict";

// Comment editor

const commentEditor = document.querySelector(".comment-editor");

if (commentEditor) {
  new Quill(commentEditor, {
    modules: {
      toolbar: ".comment-toolbar",
    },
    placeholder: "Enter category description...",
    theme: "snow",
  });
}

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

  // Variable declaration for category list table
  var dt_category_list_table = $(".datatables-notification-list");

  //select2 for dropdowns in offcanvas

  var select2 = $(".select2");
  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      $this.wrap('<div class="position-relative"></div>').select2({
        dropdownParent: $this.parent(),
        placeholder: $this.data("placeholder"), //for dynamic placeholder
      });
    });
  }

  // Customers List Datatable

  if (dt_category_list_table.length) {
    var dt_category = dt_category_list_table.DataTable({
      // ajax: assetsPath + 'json/ecommerce-category-list.json', // JSON file to add data
      processing: true,
      serverSide: true,
      pageLength: 10,
      ajax: baseUrl + "app/ecommerce/notification/list/data",
      columns: [
        // columns according to JSON
        {
          data: "id",
        },
        {
          data: "created_at",
        },
        {
          data: "title",
        },
        {
          data: "message",
        },
      ],
      columnDefs: [
        { visible: false, targets: [0] },
        {
          // Status
          targets: 1,
          render: function (data, type, full, meta) {
            var $created_at = full["created_at"];
            return $created_at;
          },
        },
        {
          // Status
          targets: 2,
          render: function (data, type, full, meta) {
            var $title = full["title"];
            return $title;
          },
        },
        {
          // Status
          targets: 2,
          render: function (data, type, full, meta) {
            var $message = full["message"];
            return $message;
          },
        },
      ],
      order: [0, "desc"], //set any columns order asc/desc
      dom:
        '<"card-header d-flex flex-wrap py-0"' +
        '<"me-5 ms-n2 pe-5"f>' +
        '<"d-flex justify-content-start justify-content-md-end align-items-baseline"<"dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center mb-3 mb-sm-0 gap-3"lB>>' +
        ">t" +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        ">",
      lengthMenu: [10, 20, 50, 70, 100], //for length of menu
      language: {
        sLengthMenu: "_MENU_",
        search: "",
        searchPlaceholder: "Search Category",
      },
      // Button for offcanvas
      buttons: [
        {
          text: '<i class="bx bx-plus me-0 me-sm-1"></i>Add Category',
          className: "add-new btn btn-primary ms-2",
          attr: {
            "data-bs-toggle": "offcanvas",
            "data-bs-target": "#offcanvasEcommerceCategoryList",
          },
        },
      ],
    });
    $(".dataTables_length").addClass("mt-0 mt-md-3");
    $(".dt-action-buttons").addClass("pt-0");
  }

  // Delete Record
  $(".datatables-category-list tbody").on(
    "click",
    ".delete-record",
    function () {
      dt_category.row($(this).parents("tr")).remove().draw();
    }
  );

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $(".dataTables_filter .form-control").removeClass("form-control-sm");
    $(".dataTables_length .form-select").removeClass("form-select-sm");
  }, 300);
});

function categoryValidaton() {
  var category_name = $("#category-name").val();
  var category_image = $("#category-image").val();
  var category_status = $("#ecommerce-category-status").val();
  var cnt = 0;
  if (category_name == "") {
    $("#category-name-error").html("Please enter category name.");
    cnt = 1;
  } else {
    $("#category-name-error").html("");
  }
  if (category_image == "") {
    $("#category-image-error").html("Please select category image.");
    cnt = 1;
  } else {
    $("#category-image-error").html("");
  }
  if (category_status == "") {
    $("#category-status-error").html("Please select status.");
    cnt = 1;
  } else {
    $("#category-status-error").html("");
  }
  if (cnt == 1) {
    return false;
  }
}
function deleteCategory(id) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert category!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, Delete category!",
    customClass: {
      confirmButton: "btn btn-primary me-2",
      cancelButton: "btn btn-label-secondary",
    },
    buttonsStyling: false,
  }).then(function (result) {
    if (result.value) {
      $.ajax({
        url: baseUrl + "app/ecommerce/product/category/delete/" + id,
        type: "POST",
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function () {
          Swal.fire({
            icon: "success",
            title: "Deleted!",
            text: "Category has been removed.",
            customClass: {
              confirmButton: "btn btn-success",
            },
          });
          location.href = baseUrl + "app/ecommerce/product/category";
        },
      });
    } else if (result.dismiss === Swal.DismissReason.cancel) {
      Swal.fire({
        title: "Cancelled",
        text: "Cancelled Delete :)",
        icon: "error",
        customClass: {
          confirmButton: "btn btn-success",
        },
      });
    }
  });
}
