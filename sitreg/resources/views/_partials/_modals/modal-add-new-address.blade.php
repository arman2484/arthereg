<!-- Add New Address Modal -->
<div class="modal fade" id="addNewAddress" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="address-title">Add New Address</h3>
                    <p class="address-subtitle">Add new address for express delivery</p>
                </div>
                <form id="AddAddressdata" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md mb-md-0 mb-3">
                                <div class="form-check custom-option custom-option-icon">
                                    <label class="form-check-label custom-option-content" for="customRadioHome">
                                        <span class="custom-option-body">
                                            <i class="bx bx-home"></i>
                                            <span class="custom-option-title">Home</span>
                                            <small>Delivery time (9am – 9pm)</small>
                                        </span>
                                        <input name="type" class="form-check-input" type="radio" value="home"
                                            id="customRadioHome" checked />
                                    </label>
                                </div>
                            </div>
                            <div class="col-md mb-md-0 mb-3">
                                <div class="form-check custom-option custom-option-icon">
                                    <label class="form-check-label custom-option-content" for="customRadioOffice">
                                        <span class="custom-option-body">
                                            <i class='bx bx-briefcase'></i>
                                            <span class="custom-option-title">Office</span>
                                            <small>Delivery time (9am – 5pm)</small>
                                        </span>
                                        <input name="type" class="form-check-input" type="radio" value="office"
                                            id="customRadioOffice" />
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" placeholder="John"
                            value="{{ old('first_name') }}" />
                        <span class="error" id="error_first_name"></span>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Doe"
                            value="{{ old('last_name') }}" />
                        <span class="error" id="error_last_name"></span>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="mobile">Mobile Number</label>
                        <input type="text" id="mobile" name="mobile" class="form-control"
                            placeholder="1234567890" value="{{ old('mobile') }}" />
                        <span class="error" id="error_mobile"></span>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="address">Address</label>
                        <input type="text" id="address" name="address" class="form-control"
                            placeholder="12, Business Park" value="{{ old('address') }}" />
                        <span class="error" id="error_address"></span>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="locality">Locality</label>
                        <input type="text" id="locality" name="locality" class="form-control"
                            placeholder="Nr. Hard Rock Cafe" value="{{ old('locality') }}" />
                        <span class="error" id="error_locality"></span>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="city">City</label>
                        <input type="text" id="city" name="city" class="form-control"
                            placeholder="Los Angeles" value="{{ old('city') }}" />
                        <span class="error" id="error_city"></span>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="state">State</label>
                        <input type="text" id="state" name="state" class="form-control"
                            placeholder="California" value="{{ old('state') }}" />
                        <span class="error" id="error_state"></span>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="pincode">Zip Code</label>
                        <input type="text" id="pincode" name="pincode" class="form-control"
                            placeholder="99950" value="{{ old('pincode') }}" />
                        <span class="error" id="error_pincode"></span>
                    </div>
                    <div class="col-12">
                        <label>
                            <input type="checkbox" name="default_address" class="default-address-checkbox"> Make this
                            my default address
                        </label>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/ Add New Address Modal -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('AddAddressdata').addEventListener('submit', function(e) {
            e.preventDefault();

            // Clear previous errors
            document.querySelectorAll('.error').forEach(el => el.textContent = '');

            const formData = new FormData(this);

            fetch("{{ route('checkout.add-address') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.errors) {
                        for (const [key, value] of Object.entries(data.errors)) {
                            document.getElementById(`error_${key}`).textContent = value[0];
                        }
                    } else {
                        // Close modal and show success message
                        $('#addNewAddress').modal('hide');
                        Swal.fire({
                            text: 'Address added Successfully',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            timer: null // Prevent the alert from disappearing automatically
                        }).then(() => {
                            // Refresh the page after clicking OK
                            location.reload();
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });
</script>
