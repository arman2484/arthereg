$(document).ready(function () {
  // Function to fetch user addresses
  function getAddresses() {
    var token = localStorage.getItem('token');
    let baseUrlLive = sessionStorage.getItem('baseUrlLive');
    $.ajax({
      type: 'GET',
      url: baseUrlLive + '/api/get-address',
      dataType: 'json',
      headers: { Authorization: `Bearer ${token}` },
      success: function (response) {
        if (response && (response.default_address.length > 0 || response.other_address.length > 0)) {
          console.log('Addresses found:', response.default_address, response.other_address);
          displayAddresses(response.default_address, response.other_address);
        } else {
          console.error('No addresses found');
          $('#no_address_placeholder').show();
          $('#address_data').empty();
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error: ' + error);
      }
    });
  }

  // Call function to fetch user addresses
  getAddresses();

  // Function to display user addresses
  function displayAddresses(defaultAddresses, otherAddresses) {
    // Clear any existing addresses
    $('#address_data').empty();

    // Function to generate address HTML
    function generateAddressHTML(address) {
      return `
        <div class="mx-2 border border-gray-300 pt-6 px-5 font-SourceSansPro mb-4 flex flex-col gap-7 justify-between">
            <div class="space-y-1">
                <div class="flex justify-between">
                    <div class="flex gap-x-2 items-center">
                        <h3 class="font-semibold text-xl">${address.first_name} ${address.last_name}</h3>
                        <span class="px-2 py-0 text-sm rounded-xl border border-gray-600 w-fit">${address.type}</span>
                    </div>
                </div>
                <p class="text-gray-600 py-3 text-lg capitalize">
                    <ul>
                        <li>${address.address}, ${address.locality}</li>
                        <li>${address.city}</li>
                        <li>${address.state}</li>
                        <li>${address.pincode}</li>
                    </ul>
                </p>
                <p class="text-gray-600">Mobile: ${address.mobile}</p>
            </div>
            <button type="button" class="edit-address uppercase flex justify-evenly border-t py-2 divide-x text-">
                <p class="text-lg cursor-pointer text-center w-full hover:opacity-70 edit-address-call" data-address-id="${address.id}" id="edit_address_btn">Edit</p>
                <p class="text-lg cursor-pointer text-center w-full hover:opacity-70 remove-address" data-address-id="${address.id}">Remove</p>
            </button>
        </div>
        `;
    }

    // Display default addresses
    defaultAddresses.forEach(function (address) {
      $('#address_data').append(generateAddressHTML(address));
    });

    // Display other addresses
    otherAddresses.forEach(function (address) {
      $('#address_data').append(generateAddressHTML(address));
    });

    // Add functionality to the "Remove" button
    $('.remove-address').click(function () {
      var addressId = $(this).data('address-id');
      removeAddress(addressId);
    });

    // Event listener for the Edit button
    $('.edit-address-call').click(function () {
      var addressId = $(this).data('address-id');
      editCartAddressData(addressId);
    });
  }

  // Function to edit an address
  function editCartAddressData(addressId) {
    console.log('Fetching address data for ID:', addressId);
    const token = localStorage.getItem('token');
    const baseUrlLive = sessionStorage.getItem('baseUrlLive');
    $.ajax({
      type: 'POST',
      url: baseUrlLive + '/api/edit-address',
      data: { address_id: addressId },
      headers: { Authorization: `Bearer ${token}` },
      success: function (response) {
        console.log('Address data fetched:', response);
        if (response.success) {
          const address = response.data;

          // Populate the modal fields with the address details
          $('#address_id_edit').val(address.id);
          $('#first_name_alert_edit').val(address.first_name);
          $('#last_name_alert_edit').val(address.last_name);
          $('#phone_number_alert_edit').val(address.mobile);
          $('#pin_code_alert_edit').val(address.pincode);
          $('#building_no_alert_edit').val(address.address);
          $('#locality_alert_edit').val(address.locality);
          $('#city_alert_edit').val(address.city);
          $('#state_alert_edit').val(address.state);

          // Set the address type
          $('input[name="address_type_edit"][value="' + address.type + '"]').prop('checked', true);
          // Check if the address is default
          $('#make_default_edit').prop('checked', address.default_address);

          // Show the edit address container
          $('#edit_address_container').show();
        } else {
          console.error('Failed to fetch address details: ' + response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error: ' + error);
      }
    });
  }

  // Handle form submission for updating address
  $('#editUpdateAddressForm').on('submit', function (e) {
    e.preventDefault(); // Prevent the default form submission

    const token = localStorage.getItem('token');
    const baseUrlLive = sessionStorage.getItem('baseUrlLive');

    const data = {
      address_id: $('#address_id_edit').val(),
      first_name: $('#first_name_alert_edit').val(),
      last_name: $('#last_name_alert_edit').val(),
      mobile: $('#phone_number_alert_edit').val(),
      pincode: $('#pin_code_alert_edit').val(),
      address: $('#building_no_alert_edit').val(),
      locality: $('#locality_alert_edit').val(),
      city: $('#city_alert_edit').val(),
      state: $('#state_alert_edit').val(),
      type: $('input[name="address_type_edit"]:checked').val(),
      default_address: $('#make_default_edit').is(':checked') ? 1 : 0
    };

    $.ajax({
      type: 'POST',
      url: baseUrlLive + '/api/update-address',
      data: data,
      headers: { Authorization: `Bearer ${token}` },
      success: function (response) {
        if (response.success) {
          Swal.fire('Success!', 'Address updated successfully.', 'success');
          getAddresses();
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
  function removeAddress(addressId) {
    Swal.fire({
      title: 'Are you sure?',
      text: 'You are about to remove this address!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, remove it!',
      cancelButtonText: 'No, keep it'
    }).then(result => {
      if (result.isConfirmed) {
        var token = localStorage.getItem('token');
        let baseUrlLive = sessionStorage.getItem('baseUrlLive');
        $.ajax({
          type: 'POST',
          url: baseUrlLive + '/api/remove-address',
          data: { address_id: addressId },
          headers: { Authorization: `Bearer ${token}` },
          success: function (response) {
            if (response.success) {
              getAddresses();
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
});
