@extends('layouts/layoutMaster')


@section('title', 'Media Manager')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/jkanban/jkanban.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/dropzone/dropzone.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-kanban.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jkanban/jkanban.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
    <script src="{{ asset('public//assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

@endsection

@section('page-script')

    {{-- <script src="{{ asset('/assets/js/app-ecommerce-currecy-country-list.js') }}"></script> --}}
    <script src="{{ asset('/assets/js/media-manager.js') }}"></script>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<style>
    .image-upload-container {
        display: inline-block;
        position: relative;
        overflow: hidden;
    }

    .file-upload-label {
        display: block;
        padding: 10px 15px;
        background-color: #4CAF50;
        color: #fff;
        cursor: pointer;
        border-radius: 5px;
    }

    #imageUploadInput {
        position: absolute;
        top: 0;
        right: 0;
        margin: 0;
        padding: 0;
        font-size: 100px;
        cursor: pointer;
        opacity: 0;
    }


    .image_new {
        max-width: 100% !important;
        height: 170px !important;
        width: 100% !important;
        border-radius: 10px !important;
        object-fit: cover !important;

    }

    .image_new_container {
        border-radius: 1rem !important;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-items: center;
        background-color: #efefef !important;
        padding: .3rem .7rem !important;
        height: 14rem !important;
        gap: .5rem;
        justify-content: center;
    }
</style>
@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Media /</span> Media Manager List
    </h4>

    <div class="app-ecommerce-category">
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <!-- Category List Table -->
        <div class="contentbar bardashboard-card">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card m-b-30">
                        <div class="card-body">
                            <ul class="tabbable nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#product"
                                        role="tab" aria-controls="product" aria-selected="true"><i
                                            class="feather icon-folder"></i> Product</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="category-tab" data-toggle="pill" href="#category" role="tab"
                                        aria-controls="category-tab" aria-selected="false"><i
                                            class="feather icon-folder"></i> Category</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent" style="padding-inline: 0px !important;">
                                <h4 id="product_title"></h4><br>
                                <ul class="tabbable nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="tab-content-data" data-toggle="pill" href="#all-uploads"
                                            role="tab" aria-controls="product" aria-selected="true"><i
                                                class="feather icon-folder"></i> Upload</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="tab-content-all-data" data-toggle="pill"
                                            href="#all-files" role="tab" aria-controls="category-tab"
                                            aria-selected="false"><i class="feather icon-folder"></i>
                                            All Files</a>
                                    </li>
                                </ul>
                                <div class="tab-pane active" id="product" role="tabpanel"
                                    aria-labelledby="pills-home-tab">
                                    <div data-midia-can_choose="false" style="display: flex; flex-wrap: wrap;gap:0.6rem;"
                                        id="media1">
                                    </div>

                                </div>
                                <div class="tab-pane" id="category" role="tabpanel" aria-labelledby="pills-home-tab">
                                    <div data-midia-can_choose="false" style="display: flex; flex-wrap: wrap;gap:0.6rem;"
                                        id="media2">
                                    </div>
                                </div>
                                <div class="tab-pane" id="all-uploads" role="tabpanel" aria-labelledby="pills-home-tab">
                                    <div data-midia-can_choose="false" style="display: flex; flex-wrap: wrap;gap:0.6rem;"
                                        id="all-uploads1">
                                    </div>
                                </div>
                                <div class="tab-pane" id="all-files" role="tabpanel" aria-labelledby="pills-home-tab">
                                    <div data-midia-can_choose="false" style="display: flex; flex-wrap: wrap;gap:0.6rem;"
                                        id="all-files1">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>




            </div>
        @endsection

        {{-- <script src="{{ asset('assets/js/app-ecommerce-product-add.js') }}"></script> --}}
        <script>
            window.copyToClipboard = function(text) {
                const dummyTextArea = document.createElement('textarea');
                dummyTextArea.value = text;
                document.body.appendChild(dummyTextArea);
                dummyTextArea.select();
                document.execCommand('copy');
                document.body.removeChild(dummyTextArea);
            };
            // const baseUrl = '/';

            function deleteImage(id) {

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert Image!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete Image!',
                    customClass: {
                        confirmButton: 'btn btn-primary me-2',
                        cancelButton: 'btn btn-label-secondary'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: baseUrl + 'media/product-image/delete/' + id,
                            type: 'get',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                console.log(data.id);
                                $('#delete-image' + data.id).remove();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Image has been removed.',
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

            function deleteCategoryImage(id) {
                alert(id);
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert Image!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete Image!',
                    customClass: {
                        confirmButton: 'btn btn-primary me-2',
                        cancelButton: 'btn btn-label-secondary'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: baseUrl + 'media/category-image/delete/' + id,
                            type: 'get',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                console.log(data.id);
                                $('#delete-category-image' + data.id).remove();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Image has been removed.',
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

            function fetchAndCategoryAppendData(targetId, data, imagePath, nameProperty) {
                const $mediaContainer = $('#' + targetId);
                $mediaContainer.empty();
                console.log(data);
                for (let i = 0; i < data.length; i++) {
                    const item = data[i];
                    const $itemColumn = $(
                        '<div class="col-md-3 mb- image-container image_new_container" id="delete-category-image' + item
                        .category_id +
                        '" style="width: 24% !important;position: relative;"></div>');
                    const $itemImage = $('<img src="' + baseUrl + imagePath + item.category_image +
                        '" alt="Product Image"  class="img-fluid image_new">');
                    const $threeDots = $(
                        '<div class="dropdown" style="position: absolute;        right: 21px !important;top: 20px !important;font-size: 1.2rem;cursor: pointer;">' +
                        '<div class=" " type="button" data-bs-toggle="dropdown" aria-expanded="false">' +
                        '<i class="fa-solid fa-ellipsis-vertical"></i> ' +
                        '</div>' +
                        '<ul class="dropdown-menu">' +
                        '<li><a class="dropdown-item" href="javascript:void(0)"  onclick="copyImageUrl(\'' +
                        baseUrl + imagePath + item.category_image + '\')">Copy URL</a></li>' +
                        '<li><a class="dropdown-item" target="_blank" href="' + baseUrl + imagePath + item.category_image +
                        '">Download</a></li>' +
                        '<li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteCategoryImage(' +
                        item.category_id + ')">Delete</a></li>' +
                        '</ul>' +
                        '</div>'
                    );
                    const $itemName = $('<span class="' + nameProperty + '">' + item.category_image + '</span>');
                    $itemColumn.append($itemImage);
                    $itemColumn.append($itemName);
                    $itemColumn.append($threeDots);
                    $mediaContainer.append($itemColumn);
                    $threeDots.find('.dropdown-item[target="_blank"]').on('click', function(e) {
                        e.preventDefault();
                        const downloadLink = $(this).attr('href');
                        const invisibleLink = $('<a style="display: none;" download></a>').attr('href', downloadLink);
                        $('body').append(invisibleLink);
                        invisibleLink[0].click();
                        invisibleLink.remove();
                    });
                }
            }

            window.copyImageUrl = function(imageUrl) {
                console.log(imageUrl);
                copyToClipboard(imageUrl);
                // Optionally, you can show a message or perform any other action after copying.
                Swal.fire({
                    icon: 'success',
                    title: 'Copied!',
                    text: 'URL copied to clipboard.',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
            };




            $(document).ready(function() {

                $('#product').ready(function() {
                    $('#tab-content-all-data').addClass('btn btn-primary');
                    // $('#tab-content-all-data').attr('href', '#your_dynamic_href').text('All Files');
                    $('#product_title').text('Brand Media Manager');
                    $.ajax({
                        url: baseUrl + 'app/eccommerce/media/product',
                        type: 'get',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            // console.log(data);
                            fetchAndAppendData('media1', data,
                                'assets/images/product_images/',
                                'product_name');
                        }
                    });
                });

                function fetchDataAndAppend(targetId, url, imagePath, nameProperty) {

                    const $mediaContainer = $('#all-files1');
                    $mediaContainer.empty();

                    $.ajax({
                        url: baseUrl + url,
                        type: 'get',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            fetchAndAppendData(targetId, data, imagePath, nameProperty);
                        }
                    });
                }

                function fetchAndAppendData(targetId, data, imagePath, nameProperty) {
                    const $mediaContainer = $('#' + targetId);
                    $mediaContainer.empty();

                    for (let i = 0; i < data.length; i++) {
                        const item = data[i];
                        const $itemColumn = $(
                            '<div class="col-md-3 mb- image-container image_new_container" id="delete-image' + item
                            .image_id + '" style="width: 24% !important;position: relative;"></div>');
                        const $itemImage = $('<img src="' + baseUrl + imagePath + item.product_image +
                            '" alt="Product Image"  class="img-fluid image_new">');
                        const $threeDots = $(
                            '<div class="dropdown" style="position: absolute; right: 21px !important;top: 20px !important;font-size: 1.2rem;cursor: pointer;">' +
                            '<div class=" " type="button" data-bs-toggle="dropdown" aria-expanded="false">' +
                            '<i class="fa-solid fa-ellipsis-vertical"></i> ' +
                            '</div>' +
                            '<ul class="dropdown-menu">' +
                            '<li><a class="dropdown-item" href="javascript:void(0)"  onclick="copyImageUrl(\'' +
                            baseUrl + imagePath + item.product_image + '\')">Copy URL</a></li>' +
                            '<li><a class="dropdown-item" target="_blank" href="' + baseUrl + imagePath + item
                            .product_image + '">Download</a></li>' +
                            '<li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteImage(' +
                            item.image_id + ')">Delete</a></li>' +
                            '</ul>' +
                            '</div>');
                        const $itemName = $('<span class="' + nameProperty + '">' + item.product_image + '</span>');

                        $itemColumn.append($itemImage);
                        $itemColumn.append($itemName);
                        $itemColumn.append($threeDots);
                        $mediaContainer.append($itemColumn);

                        $threeDots.find('.dropdown-item[target="_blank"]').on('click', function(e) {
                            e.preventDefault();
                            const downloadLink = $(this).attr('href');
                            const invisibleLink = $('<a style="display: none;" download></a>').attr('href',
                                downloadLink);
                            $('body').append(invisibleLink);
                            invisibleLink[0].click();
                            invisibleLink.remove();
                        });
                    }
                }

                $('.nav-link').click(function(e) {
                    e.preventDefault();
                    $('.nav-link').removeClass('active');
                    $(this).addClass('active');
                    $('.tab-pane').hide();

                    const targetTabId = $(this).attr('href');
                    const targetDataUrl = $(this).data('url');
                    const targetImagePath = $(this).data('image-path');
                    const targetNameProperty = $(this).data('name-property');

                    $(targetTabId).show();

                    if (targetTabId === '#all-uploads') {
                        // Handle all uploads tab separately (if needed)
                    } else {
                        fetchDataAndAppend(targetTabId, targetDataUrl, targetImagePath, targetNameProperty);
                    }
                });

                $('.tab-pane').hide();
                $('.tab-pane.active').show();

                $('.nav-link').click(function(e) {
                    e.preventDefault();
                    $('.nav-link').removeClass('active');
                    $(this).addClass('active');
                    $('.tab-pane').hide();

                    const targetTabId = $(this).attr('href');
                    console.log(targetTabId);
                    $(targetTabId).show();
                    $('#tab-content-data').removeClass('btn-primary');
                    $('#tab-content-all-data').addClass('btn btn-primary');

                    $('#product_title').text('Brand Media Manager Product');
                    $('#pills-home-tab').ready(function() {
                        $.ajax({
                            url: baseUrl + 'app/eccommerce/media/product',
                            type: 'get',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                fetchAndAppendData('media1', data,
                                    'assets/images/product_images/',
                                    'product_name');
                            }
                        });
                    });
                    if (targetTabId === '#product') {
                        $('#product_title').text('Brand Media Manager Product');
                        $.ajax({
                            url: baseUrl + 'app/eccommerce/media/product',
                            type: 'get',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                fetchAndAppendData('media1', data,
                                    'assets/images/product_images/',
                                    'product_name');
                            }
                        });
                    }


                    if (targetTabId === '#category') {
                        $('#product_title').text('Brand Media Category');

                        $.ajax({
                            url: baseUrl + 'app/eccommerce/media/category',
                            type: 'get',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                fetchAndCategoryAppendData('media2', data,
                                    'assets/images/category_images/', 'category_name');
                            }
                        });
                    }
                    if (targetTabId === '#all-files') {
                        $('#tab-content-data').removeClass('btn-primary');
                        $(this).addClass('btn btn-primary');
                        $('#product_title').text('Brand Media Manager');
                        $.ajax({
                            url: baseUrl + 'app/eccommerce/media/product',
                            type: 'get',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                fetchAndAppendData('all-files1', data,
                                    'assets/images/product_images/',
                                    'product_name');
                            }
                        });
                    }

                    if (targetTabId == '#all-uploads') {
                        $('#all-uploads1').html("");
                        $('#tab-content-all-data').removeClass('btn-primary');
                        $(this).addClass('btn btn-primary');
                        const inputFile = $(
                            `<form action="/file-upload" class="dropzone needsclick" id="dropzone-basic" enctype="multipart/form-data">
                        <div class="dz-message needsclick my-5"><p class="fs-4 note needsclick my-2">Drag and drop your image here</p><small class="text-muted d-block fs-6 my-2">or</small><span class="note needsclick btn bg-label-primary d-inline" id="btnBrowse">Browse image</span></div><div class="fallback">
                <input name="file" type="file" /></div></form><div class="m-3"><button type="submit" id="file-upload" class="btn btn-primary me-sm-3 me-1 -submit">Submit</button></div>`
                        );
                        $('#all-uploads1').append(inputFile);

                        let myDropzone;

                        const dropzoneBasic = document.getElementById('dropzone-basic');
                        console.log(dropzoneBasic);
                        if (dropzoneBasic) {
                            myDropzone = new Dropzone(dropzoneBasic, {
                                parallelUploads: 1,
                                maxFilesize: 5,
                                acceptedFiles: '.jpg,.jpeg,.png,.gif',
                                addRemoveLinks: true,
                                maxFiles: 6
                            });
                        }

                        $('#file-upload').on('click', function() {
                            if (!myDropzone || myDropzone.files.length === 0) {
                                console.error('No files to upload.');
                                return;
                            }
                            const formData = new FormData();
                            for (const file of myDropzone.files) {
                                formData.append('files[]', file);
                            }
                            $.ajax({
                                url: baseUrl + 'file-upload',
                                type: 'post',
                                data: formData,
                                processData: false,
                                contentType: false,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(data) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'uploaded!',
                                        text: 'Image uploaded successfully.',
                                        customClass: {
                                            confirmButton: 'btn btn-success'
                                        }
                                    });

                                },
                                error: function(error) {
                                    console.error('Error uploading files:', error);
                                }
                            });
                        });
                    }

                });

            });

            // function fetchAndAppendData(targetId, data, imagePath, nameProperty) {
            //     const $mediaContainer = $('#' + targetId);
            //     console.log($mediaContainer + 'kap');
            //     $mediaContainer.empty();
            //     for (let i = 0; i < data.length; i++) {
            //         const item = data[i];
            //         const $itemColumn = $(
            //             '<div class="col-md-3 mb- image-container image_new_container" id="delete-image' + item.image_id +
            //             '" style="width: 24% !important;position: relative;"></div>'
            //         );
            //         const $itemImage = $('<img src="' + baseUrl + imagePath + item.product_image +
            //             '" alt="Product Image"  class="img-fluid image_new">');
            //         const $threeDots = $(
            //             '<div class="dropdown" style="position: absolute;    right: 21px !important;top: 20px !important;font-size: 1.2rem;cursor: pointer;">' +
            //             '<div class=" " type="button" data-bs-toggle="dropdown" aria-expanded="false">' +
            //             '<i class="fa-solid fa-ellipsis-vertical"></i> ' +
            //             '</div>' +
            //             '<ul class="dropdown-menu">' +
            //             '<li><a class="dropdown-item" href="javascript:void(0)"  onclick="copyImageUrl(\'' +
            //             baseUrl + imagePath + item.product_image + '\')">Copy URL</a></li>' +
            //             '<li><a class="dropdown-item" target="_blank" href="' + baseUrl + imagePath + item.product_image +
            //             '">Download</a></li>' +
            //             '<li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteImage(' +
            //             item.image_id + ')">Delete</a></li>' +
            //             '</ul>' +
            //             '</div>'
            //         );
            //         const $itemName = $('<span class="' + nameProperty + '">' + item.product_image + '</span>');
            //         $itemColumn.append($itemImage);
            //         $itemColumn.append($itemName);
            //         $itemColumn.append($threeDots);
            //         $mediaContainer.append($itemColumn);
            //         console.log($mediaContainer + "kapil");
            //         $threeDots.find('.dropdown-item[target="_blank"]').on('click', function(e) {
            //             e.preventDefault();
            //             const downloadLink = $(this).attr('href');
            //             const invisibleLink = $('<a style="display: none;" download></a>').attr('href', downloadLink);
            //             $('body').append(invisibleLink);
            //             invisibleLink[0].click();
            //             invisibleLink.remove();
            //         });
            //     }
            // }
        </script>
