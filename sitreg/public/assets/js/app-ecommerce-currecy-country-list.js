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
    placeholder: "Enter sub category description...",
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
  var dt_subcategory_list_table = $(".datatables-currency-list");

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

  if (dt_subcategory_list_table.length) {
    var dt_category = dt_subcategory_list_table.DataTable({
      // ajax: assetsPath + 'json/ecommerce-category-list.json', // JSON file to add data
      processing: true,
      serverSide: true,
      pageLength: 10,
      ajax: baseUrl + "app/ecommerce/currencies/list/data",
      columns: [
        // columns according to JSON
        {
          data: "id",
        },
        {
          data: "country_id",
        },
        {
          data: "currency_id",
        },
        {
          data: "code_id",
        },
        {
          data: "status",
        },
        {
          data: "action",
        },
      ],
      columnDefs: [
        { visible: false, targets: 0 },
        {
          // Status
          targets: 1,
          render: function (data, type, full, meta) {
            var $country = full["country"];
            return $country;
          },
        },
        {
          // Status
          targets: 2,
          render: function (data, type, full, meta) {
            var $currency = full["currency"];
            return $currency;
          },
        },
        {
          // Status
          targets: 3,
          render: function (data, type, full, meta) {
            var $code = full["code"];
            return $code;
          },
        },
        {
          // Status
          targets: 4,
          render: function (data, type, full, meta) {
            var $symbol = full["symbol"];
            return $symbol;
          },
        },

        {
          targets: 5,
          render: function (data, type, full, meta) {
            var status = full["status"];
            var uid = full["id"];
            if (status == "1") {
              return (
                ' <label class="switch switch-primary switch-sm me-4 pe-2"><input type="checkbox" id="active' +
                uid +
                '" name="status" class="switch-input active" checked=""><span class="switch-toggle-slider"  onclick="changeStatus(' +
                full.id +
                "," +
                status +
                ')"><span class="switch-on"></span><span class="switch-off"></span></span></label>'
              );
            } else {
              return (
                ' <label class="switch switch-primary switch-sm me-4 pe-2"><input id="inactive' +
                uid +
                '" type="checkbox" name="status" class="switch-input"><span class="switch-toggle-slider active' +
                uid +
                '" onclick="changeStatus(' +
                full.id +
                "," +
                status +
                ')"><span class="switch-on"></span><span class="switch-off"></span></span></label>'
              );
            }
          },
        },

        {
          targets: 6,
          title: "Actions",
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            var confirm = "'Are you sure want to delete?'";
            var editUrl = baseUrl + "app/ecommerce/currencies/edit/" + full.id;
            return (
              '<div class="d-flex align-items-sm-center justify-content-sm-center">' +
              '<a href="javascript:0;" class="btn btn-sm btn-icon delete-record me-2" title="Delete" onclick="deleteCurrency(' +
              full.id +
              ')"><i class="bx bx-trash"></i></a>' +
              '<a href="' +
              editUrl +
              '" class="btn btn-sm btn-icon"><i class="bx bx-edit"></i></a>' +
              "</div>"
            );
          },
        },
      ],
      order: [0, "desc"],
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
        searchPlaceholder: "Search Currency",
      },
      buttons: [
        {
          html:
            '<a href="' +
            baseUrl +
            "app/ecommerce/currencies/add/" +
            '" class="add-new btn btn-primary ms-2"><i class="bx bx-plus me-0 me-sm-1"></i>Add Currency</a>',
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

  setTimeout(() => {
    $(".dataTables_filter .form-control").removeClass("form-control-sm");
    $(".dataTables_length .form-select").removeClass("form-select-sm");
  }, 300);
});

function changeStatus(id, status) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert status!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, Change status!",
    customClass: {
      confirmButton: "btn btn-primary me-2",
      cancelButton: "btn btn-label-secondary",
    },
    buttonsStyling: false,
  }).then(function (result) {
    if (result.value) {
      $.ajax({
        url: baseUrl + "change/currency/status/" + id,
        data: {
          status: status,
        },
        type: "get",
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (data) {
          // console.log(data.currencyData.id);
          if (data.data == 1) {
            // if (data.currencyData.status == 1) {
            // $('#active').addClass('checked');
            Swal.fire({
              icon: "success",
              title: "Changed!",
              text: "Changed status.",
              customClass: {
                confirmButton: "btn btn-success",
              },
            });
            // }
          } else {
            $("#active" + data.currencyData.id).prop("checked", true);
            Swal.fire({
              icon: "danger",
              title: "Not Changed!",
              text: "Can not disable all currency.",
              customClass: {
                confirmButton: "btn btn-danger",
              },
            });
          }
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
function deleteCurrency(id) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert currency!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, Delete currency!",
    customClass: {
      confirmButton: "btn btn-primary me-2",
      cancelButton: "btn btn-label-secondary",
    },
    buttonsStyling: false,
  }).then(function (result) {
    if (result.value) {
      $.ajax({
        url: baseUrl + "delete/currency/status/" + id,
        type: "POST",
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function () {
          Swal.fire({
            icon: "success",
            title: "Deleted!",
            text: "Subcategory has been removed.",
            customClass: {
              confirmButton: "btn btn-success",
            },
          });
          location.href = baseUrl + "app/ecommerce/currencies";
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
