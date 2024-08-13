function FillBillingDetails() {
  var token = localStorage.getItem('token');
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');

  // Get form values
  var first_name = $('input[name="firstname"]').val();
  var last_name = $('input[name="lastname"]').val();
  var pincode = $('input[name="pincode"]').val();
  var address = $('input[name="address"]').val();
  var city = $('input[name="city"]').val();
  var state = $('input[name="state"]').val();
  var locality = $('input[name="locality"]').val();
  var type = $('select[name="cr_select_type"]').val();
  var country_code = $('input[name="country_code"]').val(); // New parameter
  var mobile = $('input[name="mobile"]').val(); // New parameter

  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/add-address',
    data: JSON.stringify({
      first_name: first_name,
      last_name: last_name,
      pincode: pincode,
      address: address,
      city: city,
      state: state,
      locality: locality,
      type: type,
      mobile: mobile, // Include combined phone number
      country_code: country_code
    }),
    dataType: 'json',
    contentType: 'application/json',
    headers: { Authorization: `Bearer ${token}` },
    success: function (response) {
      if (response && response.data) {
        console.log('Billing Details Add:', response.message);
        displayBillingdetails(response.data);
      } else {
        console.error('No filled details');
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + error);
    }
  });
}

// Function to display billing details
function displayBillingdetails(billDetails) {
  var billForm = $('#billingForm');

  // Fill in billing details
  billForm.find('input[name="firstname"]').val(billDetails.first_name);
  billForm.find('input[name="lastname"]').val(billDetails.last_name);
  billForm.find('input[name="pincode"]').val(billDetails.pincode);
  billForm.find('input[name="address"]').val(billDetails.address);
  billForm.find('input[name="locality"]').val(billDetails.locality);
  billForm.find('input[name="city"]').val(billDetails.city);
  billForm.find('input[name="state"]').val(billDetails.state);
  billForm.find('select[name="cr_select_type"]').val(billDetails.type);
}
