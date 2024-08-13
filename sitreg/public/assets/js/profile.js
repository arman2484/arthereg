$(document).ready(function () {
  // Function to fetch user profile data
  function getUserProfile() {
    var token = localStorage.getItem('token');
    let baseUrlLive = sessionStorage.getItem('baseUrlLive');
    $.ajax({
      type: 'GET',
      url: baseUrlLive + '/api/edit-profile',
      dataType: 'json',
      headers: { Authorization: `Bearer ${token}` },
      success: function (response) {
        if (response && response.user) {
          console.log('User found:', response.user);
          displayUserProfile(response.user);
        } else {
          console.error('No user found');
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error: ' + error);
      }
    });
  }

  // Call function to fetch user profile data
  getUserProfile();

  // Function to display user profile data
  function displayUserProfile(user) {
    $('#user_full_name').text(user.first_name + ' ' + user.last_name);
    $('#user_join_date').text('Join ' + formatDate(user.created_at));

    // Populate input fields
    $('#first_name').val(user.first_name);
    $('#last_name').val(user.last_name);
    $('#mobile').val(user.mobile);
    $('#email').val(user.email);

    // // Disable input fields initially
    // $('input[type=""]').prop('disabled', true);

    // Update profile image or display default if no image is provided
    if (user.image) {
      $('#profile_image_preview').attr('src', user.image);
    } else {
      $('#profile_image_preview').attr('src', 'assets/images/user-default.png');
    }

    // Toggle blue tick visibility based on field values
    toggleBlueTick();
  }

  // Edit Profile button click event
  $('#edit_profile_button').click(function () {
    // Toggle disabled attribute for first name and last name fields
    $('#first_name, #last_name').prop('disabled', function (i, val) {
      return !val;
    });

    // Conditionally enable mobile and email fields if they are empty
    if (!$('#mobile').val()) {
      $('#mobile').prop('disabled', false);
    }
    if (!$('#email').val()) {
      $('#email').prop('disabled', false);
    }

    // Toggle visibility of save button
    $('#save_profile_button').toggle(); // Toggle visibility

    // Toggle blue tick visibility based on field values
    toggleBlueTick();
  });

  // Save Profile button click event
  $('#save_profile_button').click(function () {
    // Call function to update user profile
    updateUserProfile();
  });

  // Function to update user profile
  function updateUserProfile() {
    var token = localStorage.getItem('token');
    let baseUrlLive = sessionStorage.getItem('baseUrlLive');
    var firstName = $('#first_name').val();
    var lastName = $('#last_name').val();
    var mobile = $('#mobile').val();
    var email = $('#email').val();
    var imageData = $('#profile_image')[0].files[0];

    var formData = new FormData();
    formData.append('first_name', firstName);
    formData.append('last_name', lastName);
    formData.append('mobile', mobile);
    formData.append('email', email);
    formData.append('image', imageData);

    $.ajax({
      type: 'POST',
      url: baseUrlLive + '/api/update-profile',
      headers: { Authorization: `Bearer ${token}` },
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        if (response && response.success) {
          console.log('Profile updated successfully');
          // Display success message
          Toastify({
            text: response.message,
            theme: 'light',
            duration: 1000,
            close: true,
            gravity: 'top',
            position: 'right',
            backgroundColor: 'linear-gradient(to right, #00438F, #B2CBDD)',
            stopOnFocus: true
          }).showToast();
          // Refresh the page after 3 seconds
          getUserProfile();

          $('#save_profile_button').css('display', 'none');
        } else {
          console.error('Failed to update profile');
          // Display error message if needed
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error: ' + error);
        // Display error message if needed
      }
    });

    // Toggle blue tick visibility based on field values after saving
    toggleBlueTick();
  }

  // Function to format date
  function formatDate(dateString) {
    const months = [
      'January',
      'February',
      'March',
      'April',
      'May',
      'June',
      'July',
      'August',
      'September',
      'October',
      'November',
      'December'
    ];
    const date = new Date(dateString);
    const month = months[date.getMonth()];
    const day = date.getDate();
    const year = date.getFullYear();
    return month + ' ' + day + ', ' + year;
  }

  // Change profile image preview on selecting new image
  $('#profile_image').change(function () {
    var reader = new FileReader();
    reader.onload = function (e) {
      $('#profile_image_preview').attr('src', e.target.result);
    };
    reader.readAsDataURL(this.files[0]);
  });

  // Function to toggle blue tick visibility based on field values
  // Function to toggle blue tick visibility based on field values
  function toggleBlueTick() {
    if ($('#mobile').val()) {
      $('#mobile-data').css('display', 'inline-block'); // Show the blue tick
    } else {
      $('#mobile-data').css('display', 'none'); // Hide the blue tick
    }

    if ($('#email').val()) {
      $('#email-data').css('display', 'inline-block'); // Show the blue tick
    } else {
      $('#email-data').css('display', 'none'); // Hide the blue tick
    }
  }

  // Bind toggleBlueTick to input changes (assuming you want to update on input change)
  $('#mobile, #email').on('input', toggleBlueTick);
});
