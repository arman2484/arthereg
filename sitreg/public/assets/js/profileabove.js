// window.Showaboveloyaltypoints = function () {
//   var token = localStorage.getItem('token');
//   var baseUrlLive = sessionStorage.getItem('baseUrlLive');

//   $.ajax({
//     type: 'POST',
//     url: baseUrlLive + '/api/showloyaltypoints',
//     dataType: 'json',
//     headers: {
//       Authorization: `Bearer ${token}`
//     },
//     success: function (response) {
//       // Update the total balance displayed on the webpage
//       var loyaltyPoints = response.loyalty_points;
//       document.getElementById('aboveloyaltypoints').innerText = `${loyaltyPoints}`;
//     },

//     error: function () {
//       console.log('Failed to fetch loyalty points. Please try again later.');
//     }
//   });
// };

// Showaboveloyaltypoints();

window.ShowAboveWalletBalance = function () {
  var token = localStorage.getItem('token');
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');

  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/showwalletbalance',
    dataType: 'json',
    headers: {
      Authorization: `Bearer ${token}`
    },
    success: function (response) {
      // Update the total balance displayed on the webpage
      document.getElementById('amountinwallet').innerText = `$${response.wallet_balance}`;
    },
    error: function () {
      console.log('Failed to fetch wallet balance. Please try again later.');
    }
  });
};

ShowAboveWalletBalance();

window.ShowAboveTotalOrder = function () {
  var token = localStorage.getItem('token');
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');

  $.ajax({
    type: 'GET',
    url: baseUrlLive + '/api/get-order',
    dataType: 'json',
    headers: {
      Authorization: `Bearer ${token}`
    },
    success: function (response) {
      // Update the total balance displayed on the webpage
      document.getElementById('totalordersdata').innerText = `${response.totalOrders}`;
    },
    error: function () {
      console.log('Failed to fetch wallet balance. Please try again later.');
    }
  });
};

ShowAboveTotalOrder();


