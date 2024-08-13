@extends('layouts/layoutMaster')

@section('title', 'Email - Apps')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-email.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/block-ui/block-ui.js') }}"></script>
@endsection

@section('page-script')
    {{-- <script src="{{ asset('assets/js/app-email.js') }}"></script> --}}

@section('content')
    <div class="col app-email-view flex-grow-0 bg-body" id="app-email-view">
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="app-email-view-header p-3 py-md-3 py-2 rounded-0">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center overflow-hidden">
                </div>
                <div class="d-flex">
                    <div class="dropdown ms-3">
                        @if ($data->ticket_id)
                            <a href="{{ url('app/ecommerce/contactus/close/ticket/' . $data->user_id) }}"
                                class="btn btn-danger">Close Ticket</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <hr class="m-0">
        <div class="app-email-view-content py-4 ps--active-y">
            @if (isset($chatCount))
                <p class="email-earlier-msgs text-center text-muted cursor-pointer mb-5"> <a
                        href="{{ url('app/ecommerce/chat/user/' . $data->user_id) }}"> {{ $chatCount }} Earlier Message
                    </a></p>
            @endif
            @foreach ($chat as $value)
                <div class="card email-card-last mx-sm-4 mx-3 mt-4 border">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-bottom">
                        <div class="d-flex align-items-center mb-sm-0 mb-3">
                            @if ($data->image)
                                <img src="{{ asset('assets/images/users_images/' . $data->image) }}"
                                    alt="user-avatar" class="flex-shrink-0 rounded-circle me-2" height="38"
                                    width="38" />
                            @else
                                <img src="{{ asset('assets/images/users_images/default.png') }}" alt="user-avatar"
                                    class="flex-shrink-0 rounded-circle me-2" height="38" width="38" />
                            @endif
                            <div class="flex-grow-1 ms-1">
                                <h6 class="m-0"> {{ $data->first_name . ' ' . $data->last_name }}
                                </h6>
                                <small class="text-muted">{{ $data->email }}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            @php
                                $dateTime = \Carbon\Carbon::parse($data->created_at);
                            @endphp
                            <small class="mb-0 me-3 text-muted">{{ $dateTime->format('F jS Y, h:i A') }}
                            </small>
                        </div>
                    </div>
                    <div class="card-body pt-3" style="height: 9rem;overflow: auto;">
                        <p>
                            {{ $value->message }}
                        </p>

                    </div>
                </div>
            @endforeach
            @foreach ($chatAdmin as $value)
                <div class="card email-card-last mx-sm-4 mx-3 mt-4 border">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-bottom">
                        <div class="d-flex align-items-center mb-sm-0 mb-3">
                            @if (Auth::guard('admin')->user()->image)
                                <img src="{{ asset('assets/images/admin_image/' . Auth::guard('admin')->user()->image) }}"
                                    alt="user-avatar" class="flex-shrink-0 rounded-circle me-2" height="38"
                                    width="38" />
                            @else
                                <img src="{{ asset('assets/images/admin_image/default.png') }}" alt="user-avatar"
                                    class="flex-shrink-0 rounded-circle me-2" height="38" width="38" />
                            @endif
                            <div class="flex-grow-1 ms-1">
                                <h6 class="m-0">
                                    {{ Auth::guard('admin')->user()->first_name . ' ' . Auth::guard('admin')->user()->last_name }}
                                </h6>
                                <small class="text-muted">{{ Auth::guard('admin')->user()->email }}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            @php
                                $dateTime = \Carbon\Carbon::parse($value->created_at);
                            @endphp
                            <small class="mb-0 me-3 text-muted">{{ $dateTime->format('F jS Y, h:i A') }}
                            </small>
                        </div>
                    </div>

                    <div class="card-body pt-3" style="height: 9rem;overflow: auto;">

                        <p class="text-left">
                            {{ $value->message }}
                        </p>
                        <hr>
                        @if ($value->image)
                            <p class="mb-2">Attachments</p>
                            <div class="cursor-pointer">
                                <span class="align-middle ms-1">
                                    <a href="{{ asset('assets/images/support_images/' . $value->image) }}"
                                        target="_blank">
                                        {{ $value->image }}
                                        <i class="bx bx-file"></i>
                                    </a>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
            <!-- Email View : Reply mail-->
            <div class="email-reply card mt-4 mx-sm-4 mx-3 border">
                <form action="{{ url('app/ecommerce/user/reply/' . $data->user_id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body pt-0 px-3">
                        <div class="d-flex justify-content-start">
                        </div>
                        <p style="border: 0;outline: none;padding-top: 1rem;">Reply to
                            {{ $data->first_name . ' ' . $data->last_name }} </p>
                        <textarea name="message" style="border: 0;outline: none;padding-top: 1rem;" cols="130" rows="10"
                            placeholder="Write your message ..."></textarea>
                        <span class="error">{{ $errors->first('message') }}</span>
                        <div class="d-flex justify-content-end align-items-center">
                            <div class="cursor-pointer me-3" id="attachmentContainer">
                                <i class="bx bx-paperclip"></i>
                                <span class="align-middle"> Attachments</span>
                                <input type="file" name="image" id="fileInput" style="display: none"
                                    class="form-control" accept="image/*">

                            </div>
                            <img id="uploadedImage" src="" alt="Uploaded Image" height="100px" width="100px"
                                style="display: none">

                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-paper-plane me-1"></i>
                                <span class="align-middle">Send</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        const attachmentContainer = document.getElementById("attachmentContainer");
        const fileInput = document.getElementById("fileInput");
        const uploadedImage = document.getElementById("uploadedImage");

        attachmentContainer.addEventListener("click", function() {
            fileInput.click();
        });

        fileInput.addEventListener("change", function() {
            const file = fileInput.files[0];
            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    uploadedImage.src = e.target.result;
                    uploadedImage.style.display = "block";
                };

                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
