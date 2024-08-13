/**
     * App eCommerce Category List
     */

    'use strict';

    // Comment editor

    const commentEditor = document.querySelector('.comment-editor');

    if (commentEditor) {
        new Quill(commentEditor, {
            modules: {
                toolbar: '.comment-toolbar'
            },
            placeholder: 'Enter sub category description...',
            theme: 'snow'
        });
    }

    // Datatable (jquery)

    $(function() {
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
        var dt_subcategory_list_table = $('.datatables-subcategory-list');

        //select2 for dropdowns in offcanvas

        var select2 = $('.select2');
        if (select2.length) {
            select2.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    dropdownParent: $this.parent(),
                    placeholder: $this.data('placeholder') //for dynamic placeholder
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
                ajax: baseUrl + 'app/ecommerce/product/subcategory/list/data',
                columns: [
                    // columns according to JSON
                    {
                        data: 'id'
                    },
                    {
                        data: 'sub_category_name'
                    },
                    {
                        data: 'category_name'
                    },
                    {
                        data: 'sub_category_status'
                    },
                    {
                        data: 'action'
                    }
                ],
                columnDefs: [
                    {visible: false, targets:0},
                    {
                        // Categories and Category Detail
                        targets: 1,
                        responsivePriority: 2,
                        render: function(data, type, full, meta) {
                            // console.log(full);
                            var $name = full['sub_category_name'],
                                // $category_detail = full['category_detail'],
                                $image = full['sub_category_image'];
                            console.log($image);
                            // $id = full['id'];
                            if ($image) {
                                // For Product image
                                var $output =
                                    '<img src="' +
                                    assetsPath +
                                    'images/sub_category_images/' +
                                    $image +
                                    '" alt="Sub Category" class="rounded-2">';
                            } else {
                                var stateNum = Math.floor(Math.random() * 6);
                                var states = ['success', 'danger', 'warning', 'info', 'dark',
                                    'primary', 'secondary'
                                ];
                                var $state = states[stateNum],
                                    $name = full['sub_category_name'],
                                    $initials = $name.match(/\b\w/g) || [];
                                $initials = (($initials.shift() || '') + ($initials.pop() ||
                                    '')).toUpperCase();
                                $output = '<span class="avatar-initial rounded-2 bg-label-' +
                                    $state + '">' + $initials + '</span>';
                            }
                            var $row_output =
                                '<div class="d-flex align-items-center">' +
                                '<div class="avatar-wrapper me-2 rounded-2 bg-label-secondary">' +
                                '<div class="avatar">' +
                                $output +
                                '</div>' +
                                '</div>' +
                                '<div class="d-flex flex-column justify-content-center">' +
                                '<span class="text-body text-wrap fw-medium">' +
                                $name +
                                '</span>' +
                                '</div>' +
                                '</div>';
                            return $row_output;
                        }
                    },
                    
                    
                    {
                        targets: 2,
                        responsivePriority: 2,
                        render: function(data, type, full, meta) {
                            console.log(full);
                            var $name = full['category_name'],
                                // $category_detail = full['category_detail'],
                                $image = full['category_image'];
                            // $id = full['id'];
                            if ($image) {
                                // For Product image
                                var $output =
                                    '<img src="' +
                                    assetsPath +
                                    'images/category_images/' +
                                    $image +
                                    '" alt="Category" class="rounded-2">';
                            } else {
                                // For Product badge
                                var stateNum = Math.floor(Math.random() * 6);
                                var states = ['success', 'danger', 'warning', 'info', 'dark',
                                    'primary', 'secondary'
                                ];
                                var $state = states[stateNum],
                                    $name = full['category_name'],
                                    $initials = $name.match(/\b\w/g) || [];
                                $initials = (($initials.shift() || '') + ($initials.pop() ||
                                    '')).toUpperCase();
                                $output = '<span class="avatar-initial rounded-2 bg-label-' +
                                    $state + '">' + $initials + '</span>';
                            }
                            // Creates full output for Categories and Category Detail
                            var $row_output =
                                '<div class="d-flex align-items-center">' +
                                '<div class="avatar-wrapper me-2 rounded-2 bg-label-secondary">' +
                                '<div class="avatar">' +
                                $output +
                                '</div>' +
                                '</div>' +
                                '<div class="d-flex flex-column justify-content-center">' +
                                '<span class="text-body text-wrap fw-medium">' +
                                $name +
                                '</span>' +
                                '</div>' +
                                '</div>';
                            return $row_output;
                        }
                    },
                    {
                        // Status
                        targets: 3,
                        render: function(data, type, full, meta) {
                            var $status = full['sub_category_status'];
                            if ($status == '1') {
                                $status = 'Active';
                                return (
                                    '<span class="badge bg-label-success" text-capitalized>' +
                                    $status +
                                    '</span>'
                                );
                            } else {
                                $status = 'Inactive';
                                return (
                                    '<span class="badge bg-label-danger" text-capitalized>' +
                                    $status +
                                    '</span>'
                                );
                            }

                        }
                    },

                    {
                        // Actions
                        targets: 4,
                        title: 'Actions',
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                          var confirm="'Are you sure want to delete?'";
                            var editUrl = baseUrl+ 'app/ecommerce/product/subcategory/edit/' + full.id;
                            return (
                                '<div class="d-flex align-items-sm-center justify-content-sm-center">' +
                                '<a href="javascript:0;" class="btn btn-sm btn-icon delete-record me-2" title="Delete" onclick="deleteSubCategory(' +full.id +')"><i class="bx bx-trash"></i></a>' +
                                '<a href="' + editUrl +'" class="btn btn-sm btn-icon"><i class="bx bx-edit"></i></a>' +
                                '</div>'
                            );
                        }
                    }
                ],
                order: [0, 'desc'], 
                dom: '<"card-header d-flex flex-wrap py-0"' +
                    '<"me-5 ms-n2 pe-5"f>' +
                    '<"d-flex justify-content-start justify-content-md-end align-items-baseline"<"dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center mb-3 mb-sm-0 gap-3"lB>>' +
                    '>t' +
                    '<"row mx-2"' +
                    '<"col-sm-12 col-md-6"i>' +
                    '<"col-sm-12 col-md-6"p>' +
                    '>',
                lengthMenu: [10, 20, 50, 70, 100], //for length of menu
                language: {
                    sLengthMenu: '_MENU_',
                    search: '',
                    searchPlaceholder: 'Search Sub Category'
                },
                // Button for offcanvas
                buttons: [{
                    text: '<i class="bx bx-plus me-0 me-sm-1"></i>Add Sub Category',
                    className: 'add-new btn btn-primary ms-2',
                    attr: {
                        'data-bs-toggle': 'offcanvas',
                        'data-bs-target': '#offcanvasEcommerceCategoryList'
                    }
                }],
                
            });
            $('.dataTables_length').addClass('mt-0 mt-md-3');
            $('.dt-action-buttons').addClass(
                'pt-0');
        }

        // Delete Record
        $('.datatables-category-list tbody').on('click', '.delete-record', function() {
            dt_category.row($(this).parents('tr')).remove().draw();
        });

        setTimeout(() => {
            $('.dataTables_filter .form-control').removeClass('form-control-sm');
            $('.dataTables_length .form-select').removeClass('form-select-sm');
        }, 300);
    });

    //For form validation
    (function() {
        const eCommerceCategoryListForm = document.getElementById('eCommerceCategoryListForm');
        alert("asdf");
        //Add New customer Form Validation
        const fv = FormValidation.formValidation(eCommerceCategoryListForm, {
            fields: {
                categoryTitle: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter category title'
                        }
                    }
                },
                slug: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter slug'
                        }
                    }
                }
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    // Use this for enabling/changing valid/invalid class
                    eleValidClass: 'is-valid',
                    rowSelector: function(field, ele) {
                        // field is the field name & ele is the field element
                        return '.mb-3';
                    }
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),
                // Submit the form when all fields are valid
                // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                autoFocus: new FormValidation.plugins.AutoFocus()
            }
        });
    });
    function subCategoryValidaton() {
        var subcategory_name = $('#subcategory-name').val();
        var category_parent = $('#category-parent').val();
        var subcategory_status = $('#subcategory-status').val();
        var cnt = 0;
        if (subcategory_name == '') {
          $('#subcategory-name-error').html('Please enter sub category name.');
          cnt = 1;
        } else {
          $('#subcategory-name-error').html('');
        }
        if (category_parent == '') {
          $('#category-parent-error').html('Please select patent category.');
          cnt = 1;
        } else {
          $('#category-parent-error').html('');
        }
        if (subcategory_status == '') {
          $('#subcategory-status-error').html('Please select status.');
          cnt = 1;
        } else {
          $('#subcategory-status-error').html('');
        }
        if (cnt == 1) {
          return false;
        }
      }
      function deleteSubCategory(id) {
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert subcategory!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, Delete subcategory!',
          customClass: {
            confirmButton: 'btn btn-primary me-2',
            cancelButton: 'btn btn-label-secondary'
          },
          buttonsStyling: false
        }).then(function (result) {
          if (result.value) {
            $.ajax({
              url: baseUrl + 'app/ecommerce/product/subcategory/delete/' + id,
              type: 'POST',
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: function () {
                Swal.fire({
                  icon: 'success',
                  title: 'Deleted!',
                  text: 'Subcategory has been removed.',
                  customClass: {
                    confirmButton: 'btn btn-success'
                  }
                });
                location.href = baseUrl + 'app/ecommerce/product/subcategory';
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