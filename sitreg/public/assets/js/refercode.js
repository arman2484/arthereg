$(document).ready(function () {
  let baseUrlLive = sessionStorage.getItem('baseUrlLive');
  let token = localStorage.getItem('token');

  // Function to fetch the refer code
  window.refercode = function () {
    $.ajax({
      type: 'GET',
      url: baseUrlLive + '/api/refer-code',
      headers: { Authorization: `Bearer ${token}` },
      dataType: 'json',
      success: function (response) {
        console.log('Response:', response);
        if (response.success && response.data) {
          displayReferCode(response.data.refer_code);
        } else {
          console.error('Refer code not found');
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error: ' + error);
      }
    });
  };

  // Function to display the refer code
  function displayReferCode(referCode) {
    $('#referCode').text(referCode);
  }

  // Function to copy the refer code to clipboard
  function copyToClipboard(text) {
    const tempInput = document.createElement('input');
    tempInput.value = text;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);
    Toastify({
      text: 'Refer code copied to clipboard!',
      duration: 1000,
      close: true,
      gravity: 'top',
      position: 'right',
      backgroundColor: 'linear-gradient(to right, #00438F, #B2CBDD)',
      stopOnFocus: true
    }).showToast();
  }

  // Event listener for the copy icon
  $('#copyIcon').on('click', function () {
    const referCode = $('#referCode').text();
    if (referCode) {
      copyToClipboard(referCode);
    } else {
    }
  });

  // Call the refercode function on page load
  refercode();
});
