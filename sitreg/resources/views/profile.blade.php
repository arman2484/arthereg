<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    @include('layouts.head')
    <style>
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
        }

        #save_profile_button {
            display: none;
        }

        #add_address_container {
            display: none;
        }

        #edit_address_container {
            display: none;
        }

        #blue-tick {
            display: none;
            /* Hide by default */
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
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

    <!-- Breadcrumb -->
    <section class="section-breadcrumb">
        <div class="cr-breadcrumb-image">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="cr-breadcrumb-title">
                            <h2>Profile Settings</h2>
                            <span> <a href="index">Home</a> / Profile Settings</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Amount In Wallet -->
    @include('layouts.amountinwallet')

    <!-- Profile -->
    @include('layouts.profileTabbar')


    {{-- Edit Profile --}}
    <div class="w-[96vw] max-w-[70rem] mb-5 mx-auto p-5 border rounded-lg">
        <div class="flex flex-col lg:flex-row gap-2 lg:justify-between lg:items-center ">
            <div class="flex gap-4 items-center">
                <label for="profile_image"
                    class="bg-[#F0F0F0] rounded-lg grid place-content-center w-16 h-16 lg:h-28 lg:w-28 cursor-pointer">
                    <input type="file" name="" class="hidden" id="profile_image">
                    <img id="profile_image_preview" class="h-full w-full rounded-md object-cover" src="/assets/images/"
                        alt="">
                </label>
                <div class="flex flex-col gap-1 justify-center">
                    <span id="user_full_name" class="font-semibold text-lg"></span>
                    <span id="user_join_date" class="text-[#B7B7B7]"></span>
                </div>
            </div>
            <div id="edit_profile_button"
                class="flex gap-1 items-center border !border-[#00438F] cursor-pointer bg-[#00438F] bg-opacity-20 rounded-lg px-3 text-sm py-1">
                <i class="ri-edit-line text-lg"></i>
                <span>Edit Profile</span>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 my-5 lg:grid-cols-2">
            <div class="flex flex-col gap-2">
                <span class="font-bold">First name</span>
                <input type="text" id="first_name" class="border rounded-lg py-2 px-2 outline-none" disabled>
            </div>
            <div class="flex flex-col gap-2">
                <span class="font-bold">Last name</span>
                <input type="text" id="last_name" class="border rounded-lg py-2 px-2 outline-none" disabled>
            </div>
            <div class="flex flex-col gap-2 relative">
                <span class="font-bold">Phone</span>
                <div class="relative">
                    <input type="text" id="mobile" class="border rounded-lg py-2 px-2 outline-none w-full"
                        disabled>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-2" id="mobile-data"
                        style="display: none; padding-top: 12px;">
                        <div class="h-4 w-4 flex justify-center items-center bg-[#00438F] rounded-full blue-tick">
                            <i class="ri-check-line text-white"></i>
                        </div>
                    </span>
                </div>
            </div>
            <div class="flex flex-col gap-2 relative">
                <span class="font-bold">Email Address</span>
                <div class="relative ">
                    <input type="text" id="email" class="border rounded-lg py-2 px-2 outline-none w-full"
                        disabled>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-2" id="email-data"
                        style="display: none; padding-top: 12px;">
                        <div class="h-4 w-4 flex justify-center items-center bg-[#00438F] rounded-full blue-tick">
                            <i class="ri-check-line text-white"></i>
                        </div>
                    </span>
                </div>
            </div>
        </div>
        <div id="save_profile_button"
            class="flex gap-1 items-center justify-center border !border-[#00438F] cursor-pointer bg-[#00438F] bg-opacity-20 rounded-lg px-3 text-sm py-1 w-fit ml-auto mr-4"
            style="display: none;">
            <i class="ri-save-line text-lg"></i>
            <span>Save</span>
        </div>
    </div>



    {{-- Edit Address Data --}}
    <div id="all_addresses" class="w-full max-w-[70rem] mb-5 mx-auto p-5 border rounded-lg autodata">
        <div class="flex justify-between items-center">
            <div class="text-xl font-bold">My Addresses</div>
            <div id="add_address_btn"
                class="flex gap-1 items-center  border !border-[#00438F] cursor-pointer bg-[#00438F] bg-opacity-20 rounded-lg px-3 text-sm py-1">
                <i class="ri-add-line text-lg"></i>
                <span>Add Address</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mt-4" id="address_data">
            <!-- Addresses will be appended here -->
        </div>

        <div class="grid place-content-center min-h-[20rem]" id="no_address_placeholder" style="display:none;">
            <img class="h-40 w-44 object-contain" src="/assets/images/location_image.png" alt="">
            <div class="font-semibold">No Addresses Found</div>
        </div>
    </div>


    {{-- Add Address --}}
    <div id="add_address_container" class="w-full max-w-[70rem] mb-5 mx-auto p-5 border rounded-lg">
        <div class="flex justify-between items-center ">
            <div class="text-xl font-bold text-gray-600">Add Addresses</div>
            <div id="back_btn"
                class="flex gap-1 items-center text-[#00438F] cursor-pointer bg-opacity-20 rounded-lg px-3 text-sm py-1">
                <i class="ri-arrow-left-s-line text-lg"></i>
                <span>Go Back</span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 min-h-[20rem] mt-4">
            <div class="grid grid-cols-1 col-span-2 gap-4 h-fit text-gray-600">
                {{-- For Contact Information --}}
                <div>
                    <h3 class="font-bold font-lato mb-3">Contact Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="relative">
                            <input type="text" id="first_name_alert"
                                class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " />
                            <label for="first_name_alert"
                                class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">First
                                Name*</label>
                            <span id="first_name_alert_error" class="text-red-600 hidden error-message"></span>
                        </div>
                        <div class="relative">
                            <input type="text" id="last_name_alert"
                                class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " />
                            <label for="last_name_alert"
                                class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Last
                                Name*</label>
                            <span id="last_name_alert_error" class="text-red-600 hidden error-message"></span>
                        </div>
                        <div class="relative">
                            <input type="tel" id="phone_number_alert"
                                class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " />
                            <label for="phone_number_alert"
                                class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Mobile
                                Number</label>
                            <span id="phone_number_alert_error" class="text-red-600 hidden error-message"></span>
                        </div>
                    </div>
                </div>

                {{-- For Shipping Address --}}
                <div>
                    <h3 class="font-bold font-lato mb-3">Shipping Address</h3>
                    <div class="flex flex-col gap-3">
                        <div class="relative">
                            <input type="text" id="pin_code_alert"
                                class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " />
                            <label for="pin_code_alert"
                                class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Pin
                                Code*</label>
                            <span id="pin_code_alert_error" class="text-red-600 hidden error-message"></span>
                        </div>
                        <div class="relative">
                            <input type="text" id="building_no_alert"
                                class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " />
                            <label for="building_no_alert"
                                class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Address
                                (House No, Building, Street, Area)*</label>
                            <span id="building_no_alert_error" class="text-red-600 hidden error-message"></span>
                        </div>

                        <div class="grid grid-cols-3 gap-2">
                            <div class="relative">
                                <input type="text" id="locality_alert"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="locality_alert"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Locality/Town*</label>
                                <span id="locality_alert_error" class="text-red-600 hidden error-message"></span>
                            </div>
                            <div class="relative">
                                <input type="text" id="city_alert"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="city_alert"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">City/District*</label>
                                <span id="city_alert_error" class="text-red-600 hidden error-message"></span>
                            </div>
                            <div class="relative">
                                <input type="text" id="state_alert"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="state_alert"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">State/Region*</label>
                                <span id="state_alert_error" class="text-red-600 hidden error-message"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center" style="gap: 1rem;">
                    <div class="flex gap-2 items-center">
                        <input type="radio" id="home_address" name="address_type" value="home" checked />
                        <label for="home_address">Home Address</label>
                    </div>
                    <div class="flex gap-2 items-center">
                        <input type="radio" id="work_address" name="address_type" value="work" />
                        <label for="work_address">Work Address</label>
                    </div>
                </div>
                <label for="make_default_alert" class="">
                    <input class="h-4 w-4" type="checkbox" name="make_default_alert" id="make_default_alert">
                    <span class="ml-2">Make this my default address</span>
                </label>
            </div>
            <button type="button" id="addAddressData"
                class="py-2 px-4 text-white bg-blue-600 rounded-lg hover:bg-blue-700" style="width: 134px;">Add
                Address</button>
        </div>
    </div>


    {{-- Edit Address --}}
    {{-- Edit Address --}}
    <div id="edit_address_container" class="w-full max-w-[70rem] mb-5 mx-auto p-5 border rounded-lg">
        <div class="flex justify-between items-center">
            <div class="text-xl font-bold text-gray-600">Edit Addresses</div>
            <div id="back_btn"
                class="flex gap-1 items-center text-[#00438F] cursor-pointer bg-opacity-20 rounded-lg px-3 text-sm py-1">
                <i class="ri-arrow-left-s-line text-lg"></i>
                <span>Go Back</span>
            </div>
        </div>

        <form id="editUpdateAddressForm">
            <div class="grid grid-cols-1 gap-4 min-h-[20rem] mt-4">
                <div class="grid grid-cols-1 col-span-2 gap-4 h-fit text-gray-600">
                    {{-- For Contact Information --}}
                    <div>
                        <h3 class="font-bold font-lato mb-3">Contact Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="relative">
                                <input type="text" id="first_name_alert_edit"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="first_name_alert_edit"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">First
                                    Name*</label>
                                <span id="first_name_alert_edit_error"
                                    class="text-red-600 hidden error-message"></span>
                            </div>
                            <div class="relative">
                                <input type="text" id="last_name_alert_edit"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="last_name_alert_edit"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Last
                                    Name*</label>
                                <span id="last_name_alert_edit_error"
                                    class="text-red-600 hidden error-message"></span>
                            </div>
                            <div class="relative">
                                <input type="tel" id="phone_number_alert_edit"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="phone_number_alert_edit"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Mobile
                                    Number</label>
                                <span id="phone_number_alert_edit_error"
                                    class="text-red-600 hidden error-message"></span>
                            </div>
                        </div>
                    </div>

                    {{-- For Shipping Address --}}
                    <div>
                        <h3 class="font-bold font-lato mb-3">Shipping Address</h3>
                        <div class="flex flex-col gap-3">
                            <div class="relative">
                                <input type="text" id="pin_code_alert_edit"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="pin_code_alert_edit"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Pincode</label>
                                <span id="pin_code_alert_edit_error" class="text-red-600 hidden error-message"></span>
                            </div>
                            <div class="relative">
                                <input type="text" id="building_no_alert_edit"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="building_no_alert_edit"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Building
                                    No. / Street Name*</label>
                                <span id="building_no_alert_edit_error"
                                    class="text-red-600 hidden error-message"></span>
                            </div>
                            <div class="relative">
                                <input type="text" id="locality_alert_edit"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="locality_alert_edit"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Locality
                                    / Area / Village*</label>
                                <span id="locality_alert_edit_error" class="text-red-600 hidden error-message"></span>
                            </div>
                            <div class="relative">
                                <input type="text" id="city_alert_edit"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="city_alert_edit"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Town
                                    / City*</label>
                                <span id="city_alert_edit_error" class="text-red-600 hidden error-message"></span>
                            </div>
                            <div class="relative">
                                <input type="text" id="state_alert_edit"
                                    class="block pb-2.5 pt-4 w-full text-sm bg-[#F5F5F5] text-gray-900 rounded-lg px-4 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="state_alert_edit"
                                    class="absolute text-sm text-blue-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-4 peer-focus:px-4 peer-focus:text-blue-600 peer-placeholder-shown:scale-90 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-90 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">State*</label>
                                <span id="state_alert_edit_error" class="text-red-600 hidden error-message"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Address Type --}}
                <div class="grid grid-cols-1 col-span-2 gap-4 h-fit">
                    <div class="text-gray-600">
                        <h3 class="font-bold font-lato mb-3">Address Type</h3>
                        <div class="flex gap-5">
                            <label class="flex items-center gap-1">
                                <input type="radio" name="address_type_edit" value="home" checked>
                                <span>Home</span>
                            </label>
                            <label class="flex items-center gap-1">
                                <input type="radio" name="address_type_edit" value="office">
                                <span>Office</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="make_default_edit">
                        <label for="make_default_edit" class="ml-2 text-gray-600">Make this my default address</label>
                    </div>
                </div>

                {{-- Hidden Address ID Field --}}
                <input type="hidden" id="address_id_edit">
            </div>

            {{-- Update Address Button --}}
            <div class="flex justify-end">
                <button id="updateAddressData"
                    class="flex gap-2 items-center px-5 py-2 bg-[#00438F] text-white rounded-lg font-lato font-medium text-sm">
                    <span>Update Address</span>
                </button>
            </div>
        </form>
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


    {{-- Scripts --}}
    <script src="{{ asset('assets/js/profile.js') }}"></script>
    <script src="{{ asset('assets/js/address.js') }}"></script>
    <script src="{{ asset('assets/js/add-address.js') }}"></script>

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
            all_addresses.style.display = "grid";
        });
    </script>


    <script>
        let edit_address_container = document.getElementById("edit_address_container");
        let autodata = document.querySelector(".autodata"); // Use querySelector to select by class

        // Use event delegation for dynamically created elements
        $(document).on('click', '#edit_address_btn', function() {
            edit_address_container.style.display = "block";
            all_addresses.style.display = "none";
        });

        $(document).on('click', '#back_btn', function() {
            edit_address_container.style.display = "none";
            all_addresses.style.display = "grid";
        });
    </script>





</body>

</html>
