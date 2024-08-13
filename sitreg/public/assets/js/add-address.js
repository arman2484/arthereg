document.getElementById('addAddressData').addEventListener('click', function () {
  // Collect form data
  const firstName = document.getElementById('first_name_alert').value.trim();
  const lastName = document.getElementById('last_name_alert').value.trim();
  const phoneNumber = document.getElementById('phone_number_alert').value.trim();
  const pinCode = document.getElementById('pin_code_alert').value.trim();
  const buildingNo = document.getElementById('building_no_alert').value.trim();
  const locality = document.getElementById('locality_alert').value.trim();
  const city = document.getElementById('city_alert').value.trim();
  const state = document.getElementById('state_alert').value.trim();
  const addressType = document.querySelector('input[name="address_type"]:checked')?.value;
  const makeDefault = document.getElementById('make_default_alert').checked ? 'true' : 'false'; // Updated this line

  // Clear previous error messages
  document.querySelectorAll('.error-message').forEach(el => el.classList.add('hidden'));

  // Flag to check if there are any errors
  let hasErrors = false;

  // Validate form data
  const validationErrors = {
    first_name_alert_error: !firstName ? 'First Name is required.' : '',
    last_name_alert_error: !lastName ? 'Last Name is required.' : '',
    pin_code_alert_error: !pinCode ? 'Pin Code is required.' : '',
    building_no_alert_error: !buildingNo ? 'Building No. is required.' : '',
    locality_alert_error: !locality ? 'Locality/Town is required.' : '',
    city_alert_error: !city ? 'City/District is required.' : '',
    state_alert_error: !state ? 'State/Region is required.' : ''
  };

  // Display validation errors
  for (const [key, message] of Object.entries(validationErrors)) {
    if (message) {
      document.getElementById(key).textContent = message;
      document.getElementById(key).classList.remove('hidden');
      hasErrors = true;
    }
  }

  if (hasErrors) {
    return; // Prevent form submission if there are errors
  }

  // Prepare data for the API
  const formData = {
    first_name: firstName,
    last_name: lastName,
    mobile: phoneNumber,
    pincode: pinCode,
    address: buildingNo,
    locality: locality,
    city: city,
    state: state,
    type: addressType,
    default_address: makeDefault // Include default_address in form data
  };

  function addAddress(formData) {
    const token = localStorage.getItem('token');
    const baseUrlLive = sessionStorage.getItem('baseUrlLive');

    $.ajax({
      type: 'POST',
      url: `${baseUrlLive}/api/add-address`,
      dataType: 'json',
      headers: { Authorization: `Bearer ${token}` },
      data: formData,
      success: function (response) {
        if (response && response.success) {
          Swal.fire({
            title: 'Success!',
            text: 'Address added successfully!',
            icon: 'success',
            confirmButtonText: 'OK'
          }).then(result => {
            if (result.isConfirmed) {
              location.reload();
            }
          });
        } else {
          console.error('Failed to add address:', response.message);
          // Update the error placeholders with the validation errors
          for (const [key, errors] of Object.entries(response.errors)) {
            $(`#${key}_error`).text(errors.join(', ')).removeClass('hidden');
          }
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error: ' + error);
      }
    });
  }

  addAddress(formData);
});
