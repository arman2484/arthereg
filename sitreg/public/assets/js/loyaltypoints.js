function PointstoAmount(amount) {
  var token = localStorage.getItem('token');
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');

  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/pointstoamount',
    dataType: 'json',
    data: {
      amount: amount
    },
    headers: {
      Authorization: `Bearer ${token}`
    },
    success: function (response) {
      Toastify({
        text: response.message,
        duration: 1000,
        close: true,
        gravity: 'top',
        position: 'right',
        backgroundColor: 'linear-gradient(to right, #00438F, #B2CBDD)',
        stopOnFocus: true
      }).showToast();
      $('#amountview').modal('hide');
      Showaboveloyaltypoints();
      ShowAboveWalletBalance();
    },

    error: function () {
      console.log('Failed to convert loyalty points. Please try again later.');
    }
  });
}

$(document).ready(function () {
  // Function to handle form submission inside the modal
  $('#amountview form').submit(function (event) {
    event.preventDefault(); // Prevent default form submission
    var amount = $('#amount_add').val(); // Get the amount from the input field
    if (amount.trim() !== '') {
      // Check if amount is not empty
      PointstoAmount(amount); // Call the function to convert loyalty points
    } else {
      console.log('Please enter a valid amount.'); // Notify the user to enter a valid amount
    }
  });

  // Function to show loyalty points initially
  window.ShowLoyaltyPoints = function () {
    var token = localStorage.getItem('token');
    var baseUrlLive = sessionStorage.getItem('baseUrlLive');

    $.ajax({
      type: 'POST',
      url: baseUrlLive + '/api/showloyaltypoints',
      dataType: 'json',
      headers: {
        Authorization: `Bearer ${token}`
      },
      success: function (response) {
        // Update the total balance displayed on the webpage
        var loyaltyPoints = response.loyalty_points;
        document.getElementById('total_loyaltypoints').innerText = `${loyaltyPoints}`;
        document.getElementById('loyalty_points_value').innerText = `${loyaltyPoints}`;
      },

      error: function () {
        console.log('Failed to fetch loyalty points. Please try again later.');
      }
    });
  };

  ShowLoyaltyPoints();
});
