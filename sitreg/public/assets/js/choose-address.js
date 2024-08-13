function ChooseAddress(formData) {
  const user_id = localStorage.getItem('user_id'); // Retrieve user_id from localStorage
  const baseUrlLive = sessionStorage.getItem('baseUrlLive');


  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/get-address',
    dataType: 'json',
    data: { user_id: user_id }, // Pass user_id here
    success: function (response) {
      if (response) {
        displayAddress(response);
      } else {
        console.error('Invalid response from server.');
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + error);
    }
  });
}

function displayAddress(addressData) {
  const defaultAddressContainer = document.getElementById('DefaultAddressDynamic');
  const otherAddressContainer = document.getElementById('OtherAddressDynamic');

  // Clear previous content
  defaultAddressContainer.innerHTML = '';
  otherAddressContainer.innerHTML = '';

  // Extract addresses
  const defaultAddresses = addressData.default_address || [];
  const otherAddresses = addressData.other_address || [];

  // Display default addresses
  if (defaultAddresses.length > 0) {
    defaultAddresses.forEach(address => {
      const addressHTML = createAddressHTML(address);
      defaultAddressContainer.innerHTML += addressHTML;
    });
  } else {
    defaultAddressContainer.innerHTML = `
      <div style="text-align: center;">
        <div id="lottie-no-product-default" style="width: 150px; height: 150px; margin: 0 auto;"></div>
        <div style="font-size: 18px; color: #00438f !important;" class="text-center py-4">No default addresses found</div>
      </div>`;

    // Load the Lottie animation
    lottie.loadAnimation({
      container: document.getElementById('lottie-no-product-default'), // The container for the animation
      renderer: 'svg', // The renderer to use
      loop: true, // Whether the animation should loop
      autoplay: true, // Whether the animation should start automatically
      path: 'assets/json/lottie.json' // The path to the Lottie file (you can also use a URL)
    });
  }

  // Display other addresses
  if (otherAddresses.length > 0) {
    otherAddresses.forEach(address => {
      const addressHTML = createAddressHTML(address);
      otherAddressContainer.innerHTML += addressHTML;
    });
  } else {
    otherAddressContainer.innerHTML = `
      <div style="text-align: center;">
        <div id="lottie-no-product-other" style="width: 150px; height: 150px; margin: 0 auto;"></div>
        <div style="font-size: 18px; color: #00438f !important;" class="text-center py-4">No other addresses found</div>
      </div>`;

    // Load the Lottie animation
    lottie.loadAnimation({
      container: document.getElementById('lottie-no-product-other'), // The container for the animation
      renderer: 'svg', // The renderer to use
      loop: true, // Whether the animation should loop
      autoplay: true, // Whether the animation should start automatically
      path: 'assets/json/lottie.json' // The path to the Lottie file (you can also use a URL)
    });
  }
}

function createAddressHTML(address) {
  return `
    <div class="shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] p-3 mb-4 rounded-lg">
      <label for="select_address_${address.id}" class="flex items-start gap-2">
        <input class="mt-1 h-4 w-4" type="radio" name="address" id="select_address_${address.id}" data-address-id="${address.id}">
        <div class="space-y-2">
          <span class="font-semibold">${address.first_name} ${address.last_name}</span>
          <div class="text-gray-600 text-sm">${address.address}, ${address.locality}, ${address.city}, ${address.state}, ${address.pincode}</div>
          <div class="font-semibold "><span>Mobile:</span><span class="text-[#00438F]"> ${address.mobile}</span></div>
          <button class="bg-[#85BAC6] text-white rounded-lg w-32 py-2 text-sm remove-address-cart" data-address-id="${address.id}">Remove</button>
          <button class="bg-[#85BAC6] text-white rounded-lg w-32 py-2 text-sm edit-address" data-address-id="${address.id}">Edit</button>
        </div>
        <div class="border border-[#00438F] text-[#00438F] rounded-lg w-fit text-center px-4 ml-auto mr-0 py-2 text-sm">${address.type}</div>
      </label>
    </div>
  `;
}

// Add functionality to the "Remove" button using event delegation
$(document).on('click', '.remove-address-cart', function () {
  const addressId = $(this).data('address-id');
  removeCartAddress(addressId);
});

// Add functionality to the "Edit" button using event delegation
$(document).on('click', '.edit-address', function () {
  const addressId = $(this).data('address-id');
  editCartAddress(addressId);
});

// Function to edit an address
// Function to edit an address
function editCartAddress(addressId) {
  const user_id = localStorage.getItem('user_id'); // Retrieve user_id from localStorage
  const baseUrlLive = sessionStorage.getItem('baseUrlLive');
  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/edit-address',
    data: { address_id: addressId, user_id: user_id },
    success: function (response) {
      if (response.success) {
        const address = response.data;

        // Populate the modal fields with the address details
        $('#first_name_edit').val(address.first_name);
        $('#last_name_edit').val(address.last_name);
        $('#phone_number_edit').val(address.mobile);
        $('#pin_code_edit').val(address.pincode);
        $('#building_no_edit').val(address.address);
        $('#locality_edit').val(address.locality);
        $('#city_edit').val(address.city);
        $('#state_edit').val(address.state);

        // Set the address type
        $('input[name="address_type_edit"][value="' + address.type + '"]').prop('checked', true);
        // Check if the address is default
        $('#make_default_edit').prop('checked', address.default_address);

        // Set the hidden address ID field
        $('#address_id_edit').val(addressId);

        // Open the modal
        $('#edit_address').modal('show');
      } else {
        console.error('Failed to fetch address details: ' + response.message);
        Swal.fire('Error!', 'Failed to fetch address details: ' + response.message, 'error');
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + error);
      Swal.fire('Error!', 'Failed to fetch address details. Please try again later.', 'error');
    }
  });
}

// Handle form submission for updating address
$('#editAddressForm').on('submit', function (e) {
  e.preventDefault(); // Prevent the default form submission

  const user_id = localStorage.getItem('user_id'); // Retrieve user_id from localStorage
  const baseUrlLive = sessionStorage.getItem('baseUrlLive');

  const data = {
    user_id: user_id, // Add user_id to the data
    address_id: $('#address_id_edit').val(),
    first_name: $('#first_name_edit').val(),
    last_name: $('#last_name_edit').val(),
    mobile: $('#phone_number_edit').val(),
    pincode: $('#pin_code_edit').val(),
    address: $('#building_no_edit').val(),
    locality: $('#locality_edit').val(),
    city: $('#city_edit').val(),
    state: $('#state_edit').val(),
    type: $('input[name="address_type_edit"]:checked').val(),
    default_address: $('#make_default_edit').is(':checked') ? 1 : 0
  };

  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/update-address',
    data: data,
    success: function (response) {
      if (response.success) {
        Swal.fire('Success!', 'Address updated successfully.', 'success');
        $('#edit_address').modal('hide');
        ChooseAddress();
      } else {
        console.error('Failed to update address: ' + response.message);
        Swal.fire('Error!', 'Failed to update address: ' + response.message, 'error');
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + error);
      Swal.fire('Error!', 'Failed to update address. Please try again later.', 'error');
    }
  });
});

// Function to remove an address
function removeCartAddress(addressId) {
  Swal.fire({
    title: 'Are you sure?',
    text: 'You are about to remove this address!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, remove it!',
    cancelButtonText: 'No, keep it'
  }).then(result => {
    if (result.isConfirmed) {
      const user_id = localStorage.getItem('user_id'); // Retrieve user_id from localStorage
      const baseUrlLive = sessionStorage.getItem('baseUrlLive');
      $.ajax({
        type: 'POST',
        url: baseUrlLive + '/api/remove-address',
        data: { address_id: addressId, user_id: user_id },
        success: function (response) {
          if (response.success) {
            ChooseAddress();
            Swal.fire('Deleted!', 'Address has been removed.', 'success');
          } else {
            console.error('Address removal failed: ' + response.message);
            Swal.fire('Error!', 'Failed to remove address: ' + response.message, 'error');
          }
        },
        error: function (xhr, status, error) {
          console.error('AJAX error: ' + error);
          Swal.fire('Error!', 'Failed to remove address. Please try again later.', 'error');
        }
      });
    }
  });
}

// Event listener for selecting an address
$(document).on('change', 'input[name="address"]', function () {
  const selectedAddressId = $(this).data('address-id');
  console.log('Selected Address ID:', selectedAddressId);
  updateCartAddress(selectedAddressId);
});

function updateCartAddress(addressId) {
  const user_id = localStorage.getItem('user_id');
  const baseUrlLive = sessionStorage.getItem('baseUrlLive');

  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/address',
    data: { address_id: addressId, user_id: user_id},
    success: function (response) {
      if (response.message) {
        console.log(response.message);
        Swal.fire('Success!', response.message, 'success');
        $('#choose_address').modal('hide');
        getCartCheckoutList();
      } else {
        console.error('Failed to update cart address.');
        Swal.fire('Error!', 'Failed to update cart address.', 'error');
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + error);
      Swal.fire('Error!', 'Failed to update cart address. Please try again later.', 'error');
    }
  });
}
