<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    @include('layouts.head')
    <style>
        /* CSS (styles.css) */
        .cr-button {
            background-color: #00438F;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .save-button {
            background-color: #00438F;
            /* Adjust as needed for save state */
        }

        #add_address_container {
            display: none;
        }

        #edit_address_container {
            display: none;
        }

        .Home {
            background-color: #00438F;
            color: white;
            border-color: #00438F;
        }

        .Office {
            background-color: #00438F;
            color: white;
            border-color: #00438F;
        }

        .error-message {
            font-size: 0.75rem;
            /* Adjust the font size as needed */
        }

        .font-lato {
            font-family: 'Lato', sans-serif;
        }

        .red-line {
            border: 2px solid rgb(55, 15, 15) !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAMZ4GbRFYSevy7tMaiH5s0JmMBBXc0qBA&callback=initAutocomplete&libraries=places&v=weekly"
        async></script>

    <script
        src="https://www.paypal.com/sdk/js?client-id=AVzMVWctLyouPgmfv9Nh6E5KakydG4JHiFGm-fgg6HRqFYUW-gHVKS1ebRfPgDOr2uYABGGcnU_3RaSL">
    </script>

    {{-- Lato fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&display=swap" rel="stylesheet">
</head>

<body class="body-bg-6">

    <!-- Loader -->
    <div id="cr-overlay">
        <span class="loader"></span>
    </div>

    <!-- Header -->
    @include('layouts.header')

    <!-- Mobile menu -->
    @include('layouts.mobilemenu')


    <!-- Choose address Modal  -->
    <div class="modal fade" id="choose_address" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered cr-modal-dialog w-auto lg:!w-[37rem] !max-w-[37rem]">
            <div class="modal-content !w-full">
                <button type="button" class="cr-close-model btn-close mt-2 mr-2" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <div class="modal-body">
                    <div class="flex flex-col font-lato  w-full gap-4 py-7">
                        <h2 class="font-semibold text-2xl">Choose an address to proceed</h2>
                        <button
                            class=" !border !border-dashed !border-[#00438F] rounded-lg w-full text-lg text-[#00438F]  font-medium text-center py-2 cursor-pointer"
                            data-bs-toggle="modal" href="#add_address">
                            Add New Address
                        </button>
                        <hr class="w-full border-borderColor" />

                        {{-- For Default address --}}
                        <h3 class="font-bold font-lato">Default Address</h3>
                        <div id="DefaultAddressDynamic"></div>

                        {{-- For Other address --}}
                        <hr class="w-full border-borderColor" />
                        <h3 class="font-bold font-lato">Other Address</h3>
                        <div id="OtherAddressDynamic"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Address Dynamic --}}
    <div class="modal fade" id="add_address" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered cr-modal-dialog w-auto lg:!w-[37rem] !max-w-[37rem]">
            <div class="modal-content !w-full">
                <button type="button" class="cr-close-model btn-close mt-2 mr-2" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <div class="modal-body">
                    <form id="addAddressForm" class="flex flex-col font-lato !px-5 lg:!px-10 w-full gap-4 py-7">
                        <h2 class="font-semibold text-2xl">Add Address</h2>

                        <!-- Contact Information Fields -->
                        <h3 class="font-bold font-lato">Contact Information</h3>
                        <div class="flex flex-col gap-3">
                            <!-- First Name Field -->
                            <div class="relative">
                                <input type="text" id="floating_outlined"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="floating_outlined"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">First
                                    Name*</label>
                                <span id="first_name_error" class="text-red-600 hidden error-message"></span>
                            </div>
                            <!-- Last Name Field -->
                            <div class="relative">
                                <input type="text" id="last_name"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="last_name"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">Last
                                    Name*</label>
                                <span id="last_name_error" class="text-red-600 hidden error-message"></span>
                            </div>
                            <!-- Mobile Number Field -->
                            <div class="relative">
                                <input type="text" id="phone_number"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="phone_number"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">Mobile
                                    Number</label>
                                <span id="phone_error" class="text-red-600 hidden error-message"></span>
                            </div>
                        </div>

                        <!-- Shipping Address Fields -->
                        <h3 class="font-bold font-lato">Shipping Address</h3>
                        <div class="flex flex-col gap-3">
                            <!-- Pin Code Field -->
                            <div class="relative">
                                <input type="text" id="pin_code"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="pin_code"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">Pin
                                    Code*</label>
                                <span id="pincode_error" class="text-red-600 hidden error-message"></span>
                            </div>
                            <!-- Address Field -->
                            <div class="relative">
                                <input type="text" id="building_no"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="building_no"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">Address
                                    (House No, Building, Street, Area)*</label>
                                <span id="address_error" class="text-red-600 hidden error-message"></span>
                            </div>
                            <!-- Locality/Town Field -->
                            <div class="relative">
                                <input type="text" id="locality"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="locality"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">Locality/Town*</label>
                                <span id="locality_error" class="text-red-600 hidden error-message"></span>
                            </div>
                            <!-- City and State Fields -->
                            <div class="grid grid-cols-2 gap-2">
                                <!-- City/District Field -->
                                <div class="relative">
                                    <input type="text" id="city"
                                        class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " />
                                    <label for="city"
                                        class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">City/District*</label>
                                    <span id="city_error" class="text-red-600 hidden error-message"></span>
                                </div>
                                <!-- State Field -->
                                <div class="relative">
                                    <input type="text" id="state"
                                        class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " />
                                    <label for="state"
                                        class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">State*</label>
                                    <span id="state_error" class="text-red-600 hidden error-message"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Save Address As -->
                        <h3 class="font-bold font-lato">Save Address As</h3>
                        <div class="flex gap-3">
                            <label class="!border-2 !border-[#00438F] py-1 px-4 rounded-lg cursor-pointer"
                                id="homeLabel">
                                <input type="radio" name="address_type" value="Home" class="hidden" checked>
                                Home
                            </label>
                            <label class="!border !border-black py-1 px-4 rounded-lg cursor-pointer" id="officeLabel">
                                <input type="radio" name="address_type" value="Office" class="hidden"> Office
                            </label>
                        </div>



                        <!-- Default Address Checkbox -->
                        <label for="make_default" class="">
                            <input class="h-4 w-4" type="checkbox" name="make_default" id="make_default">
                            <span class="ml-2">Make this my default address</span>
                        </label>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="bg-[#00438F] rounded-lg w-full text-lg text-white font-medium text-center py-2 my-4 cursor-pointer">
                            Add Address
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Address Dynamic --}}
    {{-- <div class="modal fade" id="edit_address" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered cr-modal-dialog w-auto lg:!w-[37rem] !max-w-[37rem]">
            <div class="modal-content !w-full">
                <button type="button" class="cr-close-model btn-close mt-2 mr-2" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <div class="modal-body">
                    <form id="addAddressForm" class="flex flex-col font-lato !px-5 lg:!px-10 w-full gap-4 py-7">
                        <h2 class="font-semibold text-2xl">Edit Address</h2>

                        <!-- Contact Information Fields -->
                        <h3 class="font-bold font-lato">Contact Information</h3>
                        <div class="flex flex-col gap-3">
                            <!-- First Name Field -->
                            <div class="relative">
                                <input type="text" id="floating_outlined"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="floating_outlined"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">First
                                    Name*</label>
                                <span id="first_name_error" class="text-red-600 hidden error-message"></span>
                            </div>
                            <!-- Last Name Field -->
                            <div class="relative">
                                <input type="text" id="last_name"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="last_name"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">Last
                                    Name*</label>
                                <span id="last_name_error" class="text-red-600 hidden error-message"></span>
                            </div>
                            <!-- Mobile Number Field -->
                            <div class="relative">
                                <input type="text" id="phone_number"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="phone_number"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">Mobile
                                    Number</label>
                                <span id="phone_error" class="text-red-600 hidden error-message"></span>
                            </div>
                        </div>

                        <!-- Shipping Address Fields -->
                        <h3 class="font-bold font-lato">Shipping Address</h3>
                        <div class="flex flex-col gap-3">
                            <!-- Pin Code Field -->
                            <div class="relative">
                                <input type="text" id="pin_code"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="pin_code"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">Pin
                                    Code*</label>
                                <span id="pincode_error" class="text-red-600 hidden error-message"></span>
                            </div>
                            <!-- Address Field -->
                            <div class="relative">
                                <input type="text" id="building_no"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="building_no"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">Address
                                    (House No, Building, Street, Area)*</label>
                                <span id="address_error" class="text-red-600 hidden error-message"></span>
                            </div>
                            <!-- Locality/Town Field -->
                            <div class="relative">
                                <input type="text" id="locality"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="locality"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">Locality/Town*</label>
                                <span id="locality_error" class="text-red-600 hidden error-message"></span>
                            </div>
                            <!-- City and State Fields -->
                            <div class="grid grid-cols-2 gap-2">
                                <!-- City/District Field -->
                                <div class="relative">
                                    <input type="text" id="city"
                                        class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " />
                                    <label for="city"
                                        class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">City/District*</label>
                                    <span id="city_error" class="text-red-600 hidden error-message"></span>
                                </div>
                                <!-- State Field -->
                                <div class="relative">
                                    <input type="text" id="state"
                                        class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " />
                                    <label for="state"
                                        class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">State*</label>
                                    <span id="state_error" class="text-red-600 hidden error-message"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Save Address As -->
                        <h3 class="font-bold font-lato">Save Address As</h3>
                        <div class="flex gap-3">
                            <label class="!border-2 !border-[#00438F] py-1 px-4 rounded-lg cursor-pointer"
                                id="homeLabel">
                                <input type="radio" name="address_type" value="Home" class="hidden" checked>
                                Home
                            </label>
                            <label class="!border !border-black py-1 px-4 rounded-lg cursor-pointer" id="officeLabel">
                                <input type="radio" name="address_type" value="Office" class="hidden"> Office
                            </label>
                        </div>



                        <!-- Default Address Checkbox -->
                        <label for="make_default" class="">
                            <input class="h-4 w-4" type="checkbox" name="make_default" id="make_default">
                            <span class="ml-2">Make this my default address</span>
                        </label>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="bg-[#00438F] rounded-lg w-full text-lg text-white font-medium text-center py-2 my-4 cursor-pointer">
                            Add Address
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Edit Address Modal -->
    <div class="modal fade" id="edit_address" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered cr-modal-dialog w-auto lg:!w-[37rem] !max-w-[37rem]">
            <div class="modal-content !w-full">
                <button type="button" class="cr-close-model btn-close mt-2 mr-2" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <div class="modal-body">
                    <form id="editAddressForm" class="flex flex-col font-lato !px-5 lg:!px-10 w-full gap-4 py-7">
                        <h2 class="font-semibold text-2xl">Edit Address</h2>

                        <!-- Contact Information Fields -->
                        <h3 class="font-bold font-lato">Contact Information</h3>
                        <div class="flex flex-col gap-3">
                            <!-- First Name Field -->
                            <div class="relative">
                                <input type="text" id="first_name_edit" name="first_name_edit"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="first_name_edit"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">First
                                    Name*</label>
                                <span id="first_name_edit_error" class="text-red-600 hidden error-message"></span>
                            </div>
                            <!-- Last Name Field -->
                            <div class="relative">
                                <input type="text" id="last_name_edit" name="last_name_edit"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="last_name_edit"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">Last
                                    Name*</label>
                                <span id="last_name_edit_error" class="text-red-600 hidden error-message"></span>
                            </div>
                            <!-- Mobile Number Field -->
                            <div class="relative">
                                <input type="text" id="phone_number_edit" name="phone_number_edit"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="phone_number_edit"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">Mobile
                                    Number</label>
                                <span id="phone_error" class="text-red-600 hidden error-message"></span>
                            </div>
                        </div>

                        <!-- Shipping Address Fields -->
                        <h3 class="font-bold font-lato">Shipping Address</h3>
                        <div class="flex flex-col gap-3">
                            <!-- Pin Code Field -->
                            <div class="relative">
                                <input type="text" id="pin_code_edit" name="pin_code_edit"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="pin_code_edit"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">Pin
                                    Code*</label>
                                <span id="pincode_error" class="text-red-600 hidden error-message"></span>
                            </div>
                            <!-- Address Field -->
                            <div class="relative">
                                <input type="text" id="building_no_edit" name="building_no_edit"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="building_no_edit"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">Address
                                    (House No, Building, Street, Area)*</label>
                                <span id="address_error" class="text-red-600 hidden error-message"></span>
                            </div>
                            <!-- Locality/Town Field -->
                            <div class="relative">
                                <input type="text" id="locality_edit" name="locality_edit"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="locality_edit"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">Locality/Town*</label>
                                <span id="locality_error" class="text-red-600 hidden error-message"></span>
                            </div>
                            <!-- City and State Fields -->
                            <div class="grid grid-cols-2 gap-2">
                                <!-- City/District Field -->
                                <div class="relative">
                                    <input type="text" id="city_edit" name="city_edit"
                                        class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " />
                                    <label for="city_edit"
                                        class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">City/District*</label>
                                    <span id="city_error" class="text-red-600 hidden error-message"></span>
                                </div>
                                <!-- State Field -->
                                <div class="relative">
                                    <input type="text" id="state_edit" name="state_edit"
                                        class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance="-none
                                        focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                                    <label for="state_edit"
                                        class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4">State*</label>
                                    <span id="state_error" class="text-red-600 hidden error-message"></span>
                                </div>
                            </div>
                        </div>
                        <!-- Add a hidden input field for address ID -->
                        <input type="hidden" id="address_id_edit" name="address_id_edit" />

                        <!-- Save Address As -->
                        <h3 class="font-bold font-lato">Save Address As</h3>
                        <div class="flex gap-3">
                            <label class="!border-2 !border-[#00438F] py-1 px-4 rounded-lg cursor-pointer"
                                id="homeeditLabel">
                                <input type="radio" name="address_type_edit" value="Home" class="hidden"
                                    checked>
                                Home
                            </label>
                            <label class="!border !border-black py-1 px-4 rounded-lg cursor-pointer"
                                id="officeeditLabel">
                                <input type="radio" name="address_type_edit" value="Office" class="hidden"> Office
                            </label>
                        </div>

                        <!-- Default Address Checkbox -->
                        <label for="make_default_edit">
                            <input class="h-4 w-4" type="checkbox" name="make_default_edit" id="make_default_edit">
                            <span class="ml-2">Make this my default address</span>
                        </label>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="bg-[#00438F] rounded-lg w-full text-lg text-white font-medium text-center py-2 my-4 cursor-pointer">
                            Update Address
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>




    <!-- Apply coupon Modal Dynamic -->
    <div class="modal fade" id="apply_coupons" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered cr-modal-dialog w-auto lg:!w-[36rem] !max-w-[36rem]">
            <div class="modal-content !w-full">
                <button type="button" class="cr-close-model btn-close mt-2 mr-2" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <div class="modal-body">
                    <div class="flex flex-col font-lato  w-full gap-4 py-7">
                        <h2 class="font-semibold text-2xl">Apply Coupons</h2>

                        <hr class="w-full border-borderColor" />

                        <div id="coupons-container"
                            class="grid grid-cols-1 lg:grid-cols-2 gap-3 text-black overflow-auto min-h-96 py-3 px-3">
                            <!-- Coupon Dynamically here-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Checkout Page Step One and two --}}
    <div id="order_details"
        class="px-4 w-full xl:w-[80vw] 2xl:w-[75vw] my-10 mx-auto grid grid-cols-1  lg:grid-cols-2 gap-5 font-lato">
        {{-- Order Summary --}}
        <div class=" col-span-1 shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] p-3 lg:!px-5 h-fit rounded-lg">
            <h4 class="font-bold text-xl my-4">Order Summary</h4>
            <div class="flex flex-col gap-4" id="cartCheckoutList">
                {{-- Order Summary dynamic --}}
            </div>
        </div>

        {{-- Second Section --}}
        <div class=" col-span-1">
            <div class="flex w-full items-center justify-center gap-3 !mb-10">
                <span class="w-20 text-[#00438F]">BAG</span>
                <hr id="line1" class="!border !border-dashed !border-[#00438F] w-full red-line " />
                <span class="w-20">ADDRESS</span>
                <hr id="line2" class="!border !border-dashed !border-[#B9B9B9] w-full red-line" />
                <span class="w-20">PAYMENT</span>
            </div>


            {{-- Address --}}
            <div
                class="flex w-full justify-between shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px]  p-4 lg:!px-5 rounded-lg my-5">
                <div class="w-fit space-y-2">
                    <div class="capitalize" id="CartAddressdataDisplayed">
                        <span class="font-bold">Deliver To:</span>
                        <span id="customerName"></span><span id="postalCode"></span>
                    </div>
                    <div class="capitalize" id="customerAddress"></div>
                </div>
                <div class="w-fit">
                    <button data-bs-toggle="modal" id="changeAddressButton" href="#choose_address"
                        class="bg-[#85BAC6] text-white rounded-lg w-36 py-2"
                        onclick="ChooseAddress();">Change</button>
                </div>
            </div>

            {{-- LoginSection --}}
            <div id="addressSection"
                class="flex w-full justify-between shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] p-4 lg:!px-5 rounded-lg my-5">
                <div class="w-fit space-y-2">
                    <div class="capitalize">
                        <span class="font-bold">Do you want to login?</span>
                        <p>Login yourself and be the member of sitreg</p>
                    </div>
                </div>
                <div class="w-fit">
                    <button data-bs-toggle="modal" class="bg-[#85BAC6] text-white rounded-lg w-36 py-2"
                        onclick="redirectToRegisterPage();">Click
                        Here</button>
                </div>
            </div>

            {{-- Apply coupons --}}
            <div data-bs-toggle="modal" href="#apply_coupons"
                class="bg-[#F2F2F2] cursor-pointer rounded-lg p-4 my-3 flex justify-between" id="ShowapplyCoupon">
                <div class="flex">
                    <div class="-rotate-45 w-fit">
                        <i class="ri-price-tag-line text-xl"></i>
                    </div>
                    <div class="text-lg ml-2 font-medium">Apply Coupon</div>
                </div>
                <div><i class="ri-arrow-right-s-line text-3xl"></i></div>
            </div>

            {{-- Applied Coupons --}}
            <div class="bg-[#F2F2F2] rounded-lg p-4 my-3 flex justify-between" id="showCouponbar"
                style="display: none;">
                <div class="flex gap-3" id="coupon-applied">
                    {{-- Applied Coupon display dynamic here --}}
                </div>
                <button
                    class="bg-[#85BAC6] text-white rounded-lg w-28 py-2 text-sm h-fit remove-coupon">Remove</button>
            </div>

            {{-- Order details --}}
            <div class="rounded-lg p-5">
                <h3 class="text-xl font-semibold text-darkTextColor">Order Details</h3>
                <div class="space-y-4">
                    <div class="flex justify-between mt-4">
                        <span class="text-base">Bag Total</span>
                        <span id="bagTotal" class="font-bold text-base text-green-500"></span>
                    </div>
                    <div class="flex justify-between mt-4">
                        <span class="text-base">Coupons</span>
                        <span id="coupons" class="font-bold text-red-500 text-base text-Yellow"></span>
                    </div>
                    <div class="flex justify-between mt-4">
                        <span class="text-base">TAX(10% included)</span>
                        <span class="font-bold text-base">$7</span>
                    </div>
                    <div class="flex justify-between mt-4">
                        <span class="text-base">Additional Charge</span>
                        <span class="font-bold text-base">$10</span>
                    </div>
                    <div class="flex justify-between mt-4">
                        <span class="text-base">Shipping Fee</span>
                        <span class="font-bold text-base">$9</span>
                    </div>
                    <hr class="w-full border-borderColor" />
                    <div class="flex font-bold justify-between mt-4">
                        <span class="text-base">Total Amount</span>
                        <span id="totalAmount" class="font-bold text-base"></span>
                    </div>
                    <div class="mx-auto w-full">
                        <button id="goto_payment"
                            class="bg-[#00438F] rounded-lg w-full text-lg text-white font-medium text-center py-2 my-10 cursor-pointer"
                            data-bs-toggle="modal" href="#signin_phone_modal">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Checkout Page Step three --}}
    <div id="payment_details"
        class=" hidden px-4 w-full xl:w-[80vw] 2xl:w-[75vw] my-10 mx-auto grid grid-cols-1  lg:grid-cols-2 gap-5 font-lato">
        {{-- Order Summary --}}
        <div class="space-y-5">
            {{-- <div class=" col-span-1 shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] p-3 lg:!px-5 h-fit rounded-lg ">
                <h4 class="font-bold text-xl my-4">Order Summary</h4>
                <div class="flex flex-col gap-4">
                    <div class="flex gap-3 items-centerv shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] rounded-lg p-3">
                        <img class="h-32 w-24 rounded-lg object-cover"
                            src="https://s3-alpha-sig.figma.com/img/b4fd/8b92/4466e78292d5fce00a4456b921c7a231?Expires=1717372800&Key-Pair-Id=APKAQ4GOSFWCVNEHN3O4&Signature=V3sKdORhW9XE9oNDmfGgKrYcTq4OeIJt5710vEr5n6B6BOrgG9nD3E3EYa8AhWiyFODaU7i8Q5r2XPNr4ghz-izZWFvZwubIozAz5LELqHdJdHfKdBzhPHwfGZ6iZ176AnLt8JjAji0MFxvXHvxsmMMwmttEc6ulWCQjsI6r41PvrmvW1DjinFEbvHiaSAKQrSl8XXHxSib8xjrzZ40OHYwHiiOSmlqseTHr61S~8tbxUsdzAifChSw8eynFk8LGv1LvaG4Et4LzCMtgC3j-DS8QDuM5ewxdJGTRSmo-cR6s8682krdAI1kvjZiuEzL9vaBsBoojWcwLZOcutaClXA__"
                            alt="" />
                        <div class="flex flex-col justify-between gap-[6px]">
                            <div class="">
                                <div class="capitalize font-semibold">
                                    Red Printed Co-ord set
                                </div>
                                <div class=" line-clamp-1 text-sm">by Dressbery</div>
                            </div>
                            <div>
                                <span>Qty</span>
                                <select class="ml-3 bg-gray-100 rounded-lg p-1 px-2 outline-none " name="Qty"
                                    id="Qty">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                            </div>
                            <div class="font-semibold text-secondary text-lg  mb-2">
                                <span class="text-[#00438F]">$90</span>
                                <span class="line-through text-black">$80</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3 items-centerv shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] rounded-lg p-3">
                        <img class="h-32 w-24 rounded-lg object-cover"
                            src="https://s3-alpha-sig.figma.com/img/2015/c299/f1c78fb9843e96ac0f9f7547a09523d0?Expires=1717372800&Key-Pair-Id=APKAQ4GOSFWCVNEHN3O4&Signature=Aa5kiWGm9oBzM0ItfQoGpngO57f9uc~ON-iwhnJ7asIn9~2f5zdRTF9YfVCPUTL3yPhpYdqiGwD4fT1IrchNsYECSDoFZfHGvwnmR7YkVxuWyaOzy3i8Qi624BNT~zv7N7TU9Lj7NRVMthiV1FDg-1ZgvW9DhSKfXZEdx8PCt7XAW7U86u14S07NZRi9fwg9WDuDEp9GlywfgyHZwz1bq~-~1cVC2jvcC5tmret9kI4g0KAviPque6brk2yVy4r0qL9KgnNJ45sR8wteH06yUcXzbTCcZ0pWf8k4YyEfDS3IwwdYKhYIparOfJOtPp1Jg4Uyv97zQXHwsAfEXXmvNQ__"
                            alt="" />
                        <div class="flex flex-col justify-between gap-[6px]">
                            <div class="">
                                <div class="capitalize font-semibold">
                                    Beige Gradient Sunglasses
                                </div>
                                <div class=" line-clamp-1 text-sm">by Vogue</div>
                            </div>
                            <div>
                                <span>Qty</span>
                                <select class="ml-3 bg-gray-100 rounded-lg p-1 px-2 outline-none " name="Qty"
                                    id="Qty">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                            </div>
                            <div class="font-semibold text-secondary text-lg  mb-2">
                                <span class="text-[#00438F]">$675</span>

                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3 items-centerv shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] rounded-lg p-3">
                        <img class="h-32 w-24 rounded-lg object-cover"
                            src="https://s3-alpha-sig.figma.com/img/ff45/2df1/2e5d903ac6091560d8acf88d0ff24e8e?Expires=1717372800&Key-Pair-Id=APKAQ4GOSFWCVNEHN3O4&Signature=EBFr9eeaWCP7geXTkIMGDtWnXHd8eu~RJRKumyo-5g1rD-CVw0nvE6DGFFXEJaGfWRgw-UPlmr8Rx~D8DAH8LtJ32ZtRxfBcmIrRnaPsPYHwuk9lWlbtujADRsmm1juH16nR3r7P9vvT-sfG6HN6uERLdgEeNcgH7-vfk0PKXabK0fnUlacs0p1Om7qBEC3kov-9gV~ZrYpxtsCzmJPq-p0R8~evdxqmMILNd0FM8JwN1-Do-I12TF9P19X~ynsOQ72muQYtug2JQb~26YWGx8nK4NpMG4Ox0jnRFRTwQdM9UnWZE1o95gGttdhNFkLDy7HGML8Py9JdckZIWLeCxg__"
                            alt="" />
                        <div class="flex flex-col justify-between gap-[6px]">
                            <div class="">
                                <div class="capitalize font-semibold">
                                    Beige Watch for Men
                                </div>
                                <div class=" line-clamp-1 text-sm">by Fastrack</div>
                            </div>
                            <div>
                                <span>Qty</span>
                                <select class="ml-3 bg-gray-100 rounded-lg p-1 px-2 outline-none " name="Qty"
                                    id="Qty">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                            </div>
                            <div class="font-semibold text-secondary text-lg  mb-2">
                                <span class="text-[#00438F]">$675</span>

                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class=" col-span-1 shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] p-3 lg:!px-5 h-fit rounded-lg">
                <h4 class="font-bold text-xl my-4">Order Summary</h4>
                <div class="flex flex-col gap-4" id="cartInnerCheckoutList">
                    {{-- Order Summary dynamic --}}
                </div>
            </div>
            {{-- Payment modes order details  --}}
            <div class="shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] p-3 lg:!px-5 h-fit rounded-lg">
                <h3 class="text-xl font-semibold text-darkTextColor">
                    Order Details
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between mt-4">
                        <span class="text-base">Bag Total</span>
                        <span id="bagTotalInner" class="font-bold text-base text-green-500">
                            $540
                        </span>
                    </div>
                    <div class="flex justify-between mt-4">
                        <span class="text-base">Coupon</span>
                        <span id="couponsInner" class="font-bold text-red-500 text-base text-Yellow">
                            - $20
                        </span>
                    </div>
                    <div class="flex justify-between mt-4">
                        <span class="text-base">TAX(10% included)</span>
                        <span class="font-bold text-base">$7</span>
                    </div>
                    <div class="flex justify-between mt-4">
                        <span class="text-base">Additional Charge</span>
                        <span class="font-bold text-base">$10</span>
                    </div>
                    <div class="flex justify-between mt-4">
                        <span class="text-base">Shipping Fee</span>
                        <span class="font-bold text-base">$9</span>
                    </div>
                    <hr class="w-full border-borderColor" />
                    <div class="flex font-bold justify-between mt-4">
                        <span class="text-base">Total Amount</span>
                        <span id="totalAmountInner" class="font-bold text-base">$450</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden Modal Container -->
        <div class="modal fade" id="paypalModal" tabindex="-1" role="dialog" aria-labelledby="paypalModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="paypalModalLabel">Payment with PayPal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <div id="paypal-button-container"></div>
                        <!-- Place your PayPal button container here -->
                    </div>
                </div>
            </div>
        </div>


        {{-- Second Section --}}
        <div class=" col-span-1">
            <div class="flex w-full items-center justify-center gap-3 !mb-10">
                <span class="w-20 text-[#00438F]">BAG</span>
                <hr class="!border !border-dashed !border-[#00438F] w-full" />
                <span class="w-20 text-[#00438F]">ADDRESS</span>
                <hr class="!border !border-dashed !border-[#00438F] w-full" />
                <span class="w-20">PAYMENT</span>
            </div>

            {{-- Payment Modes --}}
            <div id="payment_modes" class="bg-white p-5 lg:p-7 lg:px-10 space-y-5 h-fit">
                <div class="flex gap-3 items-center">
                    <h3 class="text-xl font-semibold ">Choose Payment Mode</h3>
                </div>
                <div class="text-blueTextColor flex flex-col gap-x-4 gap-y-5 w-fit px-6">
                    <!-- Pay Via Wallet -->
                    <label for="wallet" class="flex gap-5 items-center w-fit cursor-pointer">
                        <input type="radio" name="payment_options" id="wallet" class="h-7 w-7">
                        <span class="h-14 text-Dark rounded-lg flex gap-3 items-center">
                            <img src="/assets/images/Wallet.png" class="h-10 w-10 object-contain" alt="Wallet">
                            <span class="font-semibold text-lg">Pay Via Wallet</span>
                        </span>
                    </label>

                    <!-- Paypal -->
                    <label for="paypal" class="flex gap-5 items-center w-fit cursor-pointer">
                        <input type="radio" name="payment_options" id="paypal" class="h-7 w-7">
                        <span class="h-14 text-Dark rounded-lg flex gap-3 items-center">
                            <img src="/assets/images/paypal.png" class="h-16 w-16 object-contain" alt="Paypal">
                            <span class="font-semibold text-lg">Paypal</span>
                        </span>
                    </label>

                    <!-- Stripe -->
                    <label for="stripe" class="flex gap-5 items-center w-fit cursor-pointer">
                        <input type="radio" name="payment_options" id="stripe" class="h-7 w-7">
                        <span class="h-14 text-Dark rounded-lg flex gap-3 items-center">
                            <img src="/assets/images/stripe.png" class="h-16 w-16 object-contain" alt="Stripe">
                            <span class="font-semibold text-lg">Stripe</span>
                        </span>
                    </label>

                    <!-- Razorpay -->
                    <label for="razorpay" class="flex gap-5 items-center w-fit cursor-pointer">
                        <input type="radio" name="payment_options" id="razorpay" class="h-7 w-7">
                        <span class="h-14 text-Dark rounded-lg flex gap-3 items-center">
                            <img src="/assets/images/razorpay.png" class="h-24 w-24" alt="Razorpay">
                            <span class="font-semibold text-lg">Razorpay</span>
                        </span>
                    </label>

                    {{-- Cash On delivery --}}
                    <label for="cod" class="flex gap-5 items-center w-fit cursor-pointer">
                        <input type="radio" name="payment_options" id="cod" class="h-7 w-7">
                        <span class="h-14 text-Dark rounded-lg flex gap-3 items-center">
                            <img src="/assets/images/cash-on-delivery.png" style="height: 50px;" alt="cod">
                            <span class="font-semibold text-lg">Cash On Delivery</span>
                        </span>
                    </label>
                </div>
            </div>

            <label for="place_order_checkbox" class="text-sm flex items-center">
                <input class="h-4 w-4" type="checkbox" name="place_order_checkbox" id="place_order_checkbox">
                <span class="pl-3">
                    I agree that placing the order places me under <span class="text-[#00438F]">Terms of
                        Service</span> &
                    <span class="text-[#00438F]"> Privacy Policy</span>
                </span>
            </label>

            <div class="mx-auto w-full">
                <button id="finalpaymentselection"
                    class="bg-[#00438F] rounded-lg w-full text-lg text-white font-medium text-center py-2  my-10 cursor-pointer"
                    data-bs-toggle="modal">
                    Next
                </button>
            </div>
        </div>
    </div>

    </div>
    </div>


    <!-- Footer -->
    @include('layouts.footer')

    <!-- Tab to top -->
    @include('layouts.tabtotop')

    <!-- Cart -->
    @include('layouts.cart')

    <!-- Side-tool -->
    @include('layouts.sidetool')

    <!-- Main Scripts -->
    @include('layouts.webscripts');





    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>





    {{-- For Otp Enter --}}
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-input');
            const otpContainer = document.getElementById('otpContainer');

            const handleInputChange = (index, value) => {
                if (value && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                const otpValues = Array.from(otpInputs).map(input => input.value);
                if (otpValues.every(val => val !== '')) {
                    const enteredOtp = otpValues.join('');
                    console.log('Entered OTP:', enteredOtp);
                    // Dispatch action or perform any action here
                }
            };

            const handleInputBackspace = (index, value) => {
                if (value === '' && index > 0) {
                    otpInputs[index - 1].focus();
                }
            };

            otpInputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    handleInputChange(index, e.target.value);
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace') {
                        handleInputBackspace(index, e.target.value);
                    }
                });
            });
        });
    </script> --}}

    {{-- To change between payment --}}
    <script>
        let order_details = document.getElementById("order_details");
        let payment_details = document.getElementById("payment_details");
        let goto_payment = document.getElementById("goto_payment");

        goto_payment.addEventListener("click", () => {
            order_details.classList.add("hidden");
            payment_details.classList.remove("hidden");

            // Change the line colors to red
            document.getElementById('line1').classList.add('red-line');
            document.getElementById('line2').classList.add('red-line');
        });
    </script>

    <script>
        let add_address_btn = document.getElementById("add_address_btn");
        let add_address_container = document.getElementById("add_address_container");
        let all_addresses = document.getElementById("all_addresses");
        let back_btn = document.getElementById("back_btn");


        // Toggle visibility on button clicks
        add_address_btn.addEventListener("click", function() {
            add_address_container.style.display = "block";
            all_addresses.style.display = "none";
        });

        back_btn.addEventListener("click", function() {
            add_address_container.style.display = "none";
            all_addresses.style.display = "block";
        });
    </script>

    <script>
        let edit_address_btn = document.getElementById("edit_address_btn");
        let edit_address_container = document.getElementById("edit_address_container");
        let all_addresses = document.getElementById("all_addresses");
        let back_btn = document.getElementById("back_btn");


        // Toggle visibility on button clicks
        edit_address_btn.addEventListener("click", function() {
            edit_address_container.style.display = "block";
            all_addresses.style.display = "none";
        });

        back_btn.addEventListener("click", function() {
            add_address_container.style.display = "none";
            all_addresses.style.display = "block";
        });
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script src="{{ asset('assets/js/cart-checkout.js') }}"></script>
    <script src="{{ asset('assets/js/cart-checkoutscreen.js') }}"></script>
    <script src="{{ asset('assets/js/coupon-applyincart.js') }}"></script>
    <script src="{{ asset('assets/js/cart-address.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/edit-address.js') }}"></script> --}}
    <script src="{{ asset('assets/js/choose-address.js') }}"></script>
    <script src="{{ asset('assets/js/cartpayment.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const homeLabel = document.getElementById('homeLabel');
            const officeLabel = document.getElementById('officeLabel');

            homeLabel.classList.add('Home');

            document.querySelectorAll('input[name="address_type"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    // Reset classes
                    homeLabel.classList.remove('Home');
                    officeLabel.classList.remove('Office');

                    // Set the selected option's color
                    if (this.value === 'Home') {
                        homeLabel.classList.add('Home');
                    } else {
                        officeLabel.classList.add('Office');
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var applyCouponButton = document.getElementById('ShowapplyCoupon');

            if (applyCouponButton) {
                applyCouponButton.addEventListener('click', function() {
                    applyCouponInCart();
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const homeLabel = document.getElementById('homeeditLabel');
            const officeLabel = document.getElementById('officeeditLabel');

            homeLabel.classList.add('Home');

            document.querySelectorAll('input[name="address_type_edit"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    // Reset classes
                    homeLabel.classList.remove('Home');
                    officeLabel.classList.remove('Office');

                    // Set the selected option's color
                    if (this.value === 'Home') {
                        homeLabel.classList.add('Home');
                    } else {
                        officeLabel.classList.add('Office');
                    }
                });
            });
        });
    </script>


    {{-- <script>
        document.getElementById('goto_payment').addEventListener('click', function() {
            document.getElementById('line1').classList.add('red-line');
            document.getElementById('line2').classList.add('red-line');
        });
    </script> --}}


    <script>
        function redirectToRegisterPage() {
            window.location.href = "{{ route('register') }}"; // Update 'register' to your actual route name
        }

        // Check if token exists in local storage
        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('token'); // Replace 'token' with your actual token key
            const addressSection = document.getElementById('addressSection');

            if (token) {
                addressSection.style.display = 'none'; // Hide the section if token is present
            }
        });
    </script>


</body>

</html>
