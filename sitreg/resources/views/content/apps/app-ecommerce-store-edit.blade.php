@extends('layouts/layoutMaster')

@section('title', 'Edit Store')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/dropzone/dropzone.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/app-ecommerce-product-add.js') }}"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAMZ4GbRFYSevy7tMaiH5s0JmMBBXc0qBA&callback=initAutocomplete&libraries=places&v=weekly"
        async></script>
@endsection

<style>
    .row {
        display: flex;
        flex-wrap: wrap;
    }

    .col-md-2 {
        flex: 1 1 20%;
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Sitreg /</span><span> Edit Store</span>
    </h4>

    <form action="{{ url('app/ecommerce/store/update', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="app-ecommerce">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1 mt-3">Edit your Store</h4>
                    <p class="text-muted">Discover the range of available store</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-3">
                    <button type="submit" class="btn btn-primary">Publish store</button>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Store information</h5>
                        </div>
                        {{-- Store Name & Description --}}
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label" for="store_name">Store Name<span style="color: red;">
                                        *</span></label>
                                <input type="text" class="form-control" id="store_name" placeholder="Enter Store Name"
                                    name="store_name" aria-label="Store title" value="{{ $data->store_name }}">
                                <span class="error">{{ $errors->first('store_name') }}</span>
                            </div>
                            <div>
                                <label class="form-label">Store Description <span style="color: red;">
                                        *</span></label>
                                <textarea name="store_description" class="form-control" id="store_description" placeholder="Enter Store Description"
                                    cols="30" rows="5">{{ $data->store_description }}</textarea>
                                <span class="error">{{ $errors->first('store_description') }}</span>
                            </div>
                        </div>
                    </div>


                    {{-- Store Images --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            @csrf
                            <h5 class="card-title mb-0">Store Images <small class="text-muted">(Multiple images can
                                    be uploaded
                                    )</small><span style="color: red;">*</span></h5>

                            <input type="file" name="store_images[]" class="form-control" multiple id="imageUpload"
                                accept="image/*" style="margin-top: 15px;" value="{{ $data->store_name }}">
                            <div id="imagePreview" class="mb-4 border">
                                @if ($data->storeImages)
                                    <div class="row" style="gap: 0px;">
                                        @foreach ($data->storeImages as $value)
                                            <div class="col-md-2" id="image-preview_{{ $value->id }}">
                                                <img src="{{ asset('assets/images/store_images/' . $value->store_images) }}"
                                                    style="width: 150px; height:150px;" class="me-4 border">
                                                <a href="javascript:void(0)" class="delete_product_image"
                                                    onclick="return deleteImage({{ $value->id }}, this);">Remove</a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <span class="error">{{ $errors->first('store_image') }}</span>
                            {{-- <div id="imagePreview" class="mb-4 border"></div> --}}
                        </div>
                    </div>


                    {{-- Store Logo --}}
                    <div class="col-12 col-lg-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Store Logo<span style="color: red;">*</span></h5>
                            </div>
                            <div class="card-body">
                                <input type="file" name="store_logo" class="form-control form-control-lg"
                                    id="exampleInputFile">
                                @if ($data->store_logo)
                                    <div class="mt-2">
                                        <img src="{{ asset('assets/images/store_logo/' . $data->store_logo) }}"
                                            alt="Store Logo" style="max-width: 100px; height:100px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>


                    {{-- Store Info --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Store Info</h5>
                        </div>
                        <div class="card-body">
                            <!-- Container for the dropdowns -->
                            <div class="row">
                                <!-- Days Dropdown (Full Row) -->
                                <div class="col-12" style="padding-bottom: 20px;">
                                    <label class="form-label mb-1" for="mfo">
                                        <span>Days</span>
                                    </label>
                                    <select class="select2 form-select" data-placeholder="Select Days" name="mfo[]"
                                        multiple="multiple">
                                        <option value="Mon" {{ in_array('Mon', $existingDays) ? 'selected' : '' }}>
                                            Monday
                                        </option>
                                        <option value="Tue" {{ in_array('Tue', $existingDays) ? 'selected' : '' }}>
                                            Tuesday
                                        </option>
                                        <option value="Wed" {{ in_array('Wed', $existingDays) ? 'selected' : '' }}>
                                            Wednesday</option>
                                        <option value="Thu" {{ in_array('Thu', $existingDays) ? 'selected' : '' }}>
                                            Thursday
                                        </option>
                                        <option value="Fri" {{ in_array('Fri', $existingDays) ? 'selected' : '' }}>
                                            Friday
                                        </option>
                                        <option value="Sat" {{ in_array('Sat', $existingDays) ? 'selected' : '' }}>
                                            Saturday
                                        </option>
                                        <option value="Sun" {{ in_array('Sun', $existingDays) ? 'selected' : '' }}>
                                            Sunday
                                        </option>
                                    </select>
                                    <span class="error">{{ $errors->first('mfo') }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <!-- Module Dropdown -->
                                <div class="col-md-3" style="padding-bottom: 20px;">
                                    <label class="form-label mb-1" for="module_id">
                                        <span>Module<span style="color: red;">*</span></span>
                                    </label>
                                    <select id="module_id" class="select2 form-select" name="module_id"
                                        data-placeholder="Select Module">
                                        <option value="">Select Module</option>
                                        @foreach ($module as $value)
                                            <option value="{{ $value->id }}"
                                                {{ $value->id == $data->module_id ? 'selected' : '' }}>
                                                {{ $value->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error">{{ $errors->first('module_id') }}</span>
                                </div>
                                <!-- Vendor Dropdown -->
                                <div class="col-md-3" style="padding-bottom: 20px;">
                                    <label class="form-label mb-1" for="vendor_id">
                                        <span>Vendor<span style="color: red;">*</span></span>
                                    </label>
                                    <select id="vendor_id" name="vendor_id" class="select2 form-select"
                                        data-placeholder="Select Vendor">
                                        <option value="">Select Vendor</option>
                                        @foreach ($vendors as $value)
                                            <option value="{{ $value->id }}"
                                                {{ $value->id == $data->vendor_id ? 'selected' : '' }}>
                                                {{ $value->first_name }} {{ $value->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error">{{ $errors->first('vendor_id') }}</span>
                                </div>
                                <!-- OPEN TIME Dropdown -->
                                <div class="col-md-3" style="padding-bottom: 20px;">
                                    <label class="form-label mb-1" for="open_time">
                                        <span>Open Time</span>
                                    </label>
                                    <select id="open_time" name="open_time" class="select2 form-select"
                                        data-placeholder="Open Time">
                                        <option value="">Open Time</option>
                                        <?php
                                        for ($hour = 1; $hour <= 12; $hour++) {
                                            $time = $hour . ':00 AM';
                                            echo "<option value=\"$time\" " . ($existingOpenTime == $time ? 'selected' : '') . ">$time</option>";
                                        }
                                        ?>
                                        <?php
                                        for ($hour = 1; $hour <= 12; $hour++) {
                                            $time = $hour . ':00 PM';
                                            echo "<option value=\"$time\" " . ($existingOpenTime == $time ? 'selected' : '') . ">$time</option>";
                                        }
                                        ?>
                                    </select>
                                    <span class="error">{{ $errors->first('open_time') }}</span>
                                </div>
                                <!-- Close Time Dropdown -->
                                <div class="col-md-3" style="padding-bottom: 20px;">
                                    <label class="form-label mb-1" for="close_time">
                                        <span>Close Time</span>
                                    </label>
                                    <select id="close_time" name="close_time" class="select2 form-select"
                                        data-placeholder="Close Time">
                                        <option value="">Close Time</option>
                                        <?php
                                        for ($hour = 1; $hour <= 12; $hour++) {
                                            $time = $hour . ':00 AM';
                                            echo "<option value=\"$time\" " . ($existingCloseTime == $time ? 'selected' : '') . ">$time</option>";
                                        }
                                        ?>
                                        <?php
                                        for ($hour = 1; $hour <= 12; $hour++) {
                                            $time = $hour . ':00 PM';
                                            echo "<option value=\"$time\" " . ($existingCloseTime == $time ? 'selected' : '') . ">$time</option>";
                                        }
                                        ?>
                                    </select>
                                    <span class="error">{{ $errors->first('close_time') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- delivery Information --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Delivery Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <div style="flex: 1; margin-right: 10px;">
                                    <label class="form-label" for="min_time">Min Delivery Time<span
                                            style="color: red;">*</span></label>
                                    <input type="number" class="form-control" id="min_time"
                                        placeholder="Enter Min delivery time" name="min_time"
                                        aria-label="Enter Min delivery time" value="{{ $data->min_time }}">
                                    <span class="error">{{ $errors->first('min_time') }}</span>
                                </div>
                                <div style="flex: 1; margin-right: 10px;">
                                    <label class="form-label" for="max_time">Max Delivery
                                        Time<span style="color: red;">*</span></label>
                                    <input type="number" class="form-control" id="max_time"
                                        placeholder="Enter Max Delivery Time" name="max_time"
                                        aria-label="Enter Max Delivery Time" value="{{ $data->max_time }}">
                                    <span class="error">{{ $errors->first('max_time') }}</span>
                                </div>
                                <div style="flex: 1;">
                                    <label class="form-label" for="time_type">Type<span
                                            style="color: red;">*</span></label>
                                    <select class="select2 form-select" data-placeholder="Select Time Type"
                                        name="time_type">
                                        <option value="mins" {{ $data->time_type == 'mins' ? 'selected' : '' }}>Minutes
                                        </option>
                                        <option value="hours" {{ $data->time_type == 'hours' ? 'selected' : '' }}>Hours
                                        </option>
                                        <option value="days" {{ $data->time_type == 'days' ? 'selected' : '' }}>Days
                                        </option>
                                    </select>
                                    <span class="error">{{ $errors->first('time_type') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Store Location --}}
                    <div class="col-12 col-lg-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Store Address<span style="color: red;">*</span></h5>
                            </div>
                            <div class="card-body">
                                <div class="custom-file">
                                    <textarea class="form-control" name="address" rows="2" placeholder="Enter Your Store Location" id="pac-input"
                                        style="margin-bottom: 20px;">{{ $data->store_address ?? '' }}</textarea>
                                    <div class="text-lg-center alert-danger" id="info"></div>
                                    <div id="map" data-lat="{{ $data->lat }}" data-lng="{{ $data->lon }}"
                                        style="height: 400px; width: 100%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection


<script>
    function initAutocomplete() {
        const map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: parseFloat(document.getElementById('map').getAttribute('data-lat')),
                lng: parseFloat(document.getElementById('map').getAttribute('data-lng'))
            },
            zoom: 13,
            mapTypeId: 'roadmap'
        });
        // Create the search box and link it to the UI element.
        const input = document.getElementById('pac-input');
        if (input) {
            const searchBox = new google.maps.places.SearchBox(input);

            // map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            map.addListener('bounds_changed', () => {
                searchBox.setBounds(map.getBounds());
            });

            let markers = [];

            // more details for that place.
            searchBox.addListener('places_changed', () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                // Clear out the old markers.
                markers.forEach(marker => {
                    marker.setMap(null);
                });
                markers = [];

                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();

                places.forEach(place => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log('Returned place contains no geometry');
                        return;
                    }

                    const icon = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25)
                    };

                    // Create a marker for each place.
                    markers.push(
                        new google.maps.Marker({
                            map,
                            icon,
                            title: place.name,
                            position: place.geometry.location
                        })
                    );
                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        }
    }
</script>


<script>
    $(document).ready(function() {
        $('#imageUpload').on('change', function() {
            $('#imagePreview').html('');
            var files = $(this)[0].files;
            for (var i = 0; i < files.length; i++) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').append('<img src="' + e.target.result +
                        '" width="80" height="80px" class="me-4 border">');
                };
                reader.readAsDataURL(files[i]);
            }
        });
    });


    function deleteImage(id, element) {
        $.ajax({
            url: "{{ url('app/ecommerce/store/image/delete/') }}" + "/" + id,
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                // Remove only the specific image preview element
                $(element).closest('div').remove();
            }
        });
    }
</script>
