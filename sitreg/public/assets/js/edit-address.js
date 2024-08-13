// Function to load data into edit modal and show it
function loadEditAddressModal(address) {
  $('#edit_address').modal('show');
  // Populate form fields with address data
  $('#first_name').val(address.first_name);
  $('#last_name').val(address.last_name);
  $('#phone_number').val(address.mobile);
  $('#pin_code').val(address.pincode);
  $('#building_no').val(address.address);
  $('#locality').val(address.locality);
  $('#city').val(address.city);
  $('#state').val(address.state);
  // Set radio button for address type
  $('input[name="address_type"]').filter(`[value="${address.type}"]`).prop('checked', true);
  // Set checkbox for default address
  $('#make_default').prop('checked', address.default_address === '1');
}

// Edit button click event handler to load modal with address data
$(document).on('click', '.edit-address', function () {
  var addressId = $(this).data('address-id');
  // Assuming you have an addresses object or array containing address details
  var address = addresses.find(function (addr) {
    return addr.id === addressId;
  });
  if (address) {
    loadEditAddressModal(address);
  } else {
    console.error('Address not found');
  }
});

// Submit form to update address
$('#addAddressForm').on('submit', function (e) {
  e.preventDefault();
  var formData = $(this).serialize(); // Serialize form data
  var token = localStorage.getItem('token');
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');
  var addressId = ''; // Assign the current address ID here if needed

  // Determine the API endpoint based on whether you are adding or updating
  var apiEndpoint = addressId ? `${baseUrlLive}/api/update-address/${addressId}` : `${baseUrlLive}/api/add-address`;

  $.ajax({
    type: 'POST',
    url: apiEndpoint,
    dataType: 'json',
    headers: { Authorization: `Bearer ${token}` },
    data: formData,
    success: function (response) {
      if (response && response.success) {
        console.log('Address updated successfully:', response.data);
        Toastify({
          text: 'Address updated successfully!',
          theme: 'light',
          duration: 2000,
          close: true,
          gravity: 'top',
          position: 'right',
          backgroundColor: 'linear-gradient(to right, #00438F, #B2CBDD)',
          stopOnFocus: true
        }).showToast();
        $('#edit_address').modal('hide');
        ChooseAddress(); // Replace with your function to refresh address list
      } else {
        console.error('Failed to update address:', response.message);
        // Update the error placeholders with the validation errors
        for (var key in response.errors) {
          $('#' + key + '_error')
            .text(response.errors[key].join(', '))
            .removeClass('hidden');
        }
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + error);
    }
  });
});
