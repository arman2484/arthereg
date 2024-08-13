function addAddress(formData) {
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');
  var user_id = localStorage.getItem('user_id'); // Retrieve user_id from localStorage
  formData.user_id = user_id; // Add user_id to the formData object

  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/add-address',
    dataType: 'json',
    data: formData,
    success: function (response) {
      if (response && response.success) {
        console.log('Address added successfully:', response.data);
        Toastify({
          text: 'Address added successfully!',
          theme: 'light',
          duration: 2000,
          close: true,
          gravity: 'top',
          position: 'right',
          backgroundColor: 'linear-gradient(to right, #00438F, #B2CBDD)',
          stopOnFocus: true
        }).showToast();
        $('#add_address').modal('hide');
        ChooseAddress();
      } else {
        console.error('Failed to add address:', response.message);
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
}

// Event listener for the form submission
$('#addAddressForm').on('submit', function (event) {
  event.preventDefault();

  $('.error-message').addClass('hidden');

  // Get form data
  var formData = {
    first_name: $('#floating_outlined').val(),
    last_name: $('#last_name').val(),
    mobile: $('#phone_number').val(),
    pincode: $('#pin_code').val(),
    address: $('#building_no').val(),
    locality: $('#locality').val(),
    city: $('#city').val(),
    state: $('#state').val(),
    type: $('input[name="address_type"]:checked').val(),
    default_address: $('#make_default').prop('checked') ? 'true' : 'false'
  };

  // Validate form data
  var errors = [];
  for (var key in formData) {
    if (!formData[key]) {
      errors.push(key);
    }
  }

  if (errors.length > 0) {
    addAddress(formData);
    return;
  }
  addAddress(formData);
});
