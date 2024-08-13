// Function to handle Stripe payment
function StripeWalletPay() {
  var token = localStorage.getItem('token');
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');
  var amount = document.getElementById('amount_input').value;

  // AJAX request to backend to initiate Stripe payment
  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/add-walletstripeweb',
    dataType: 'json',
    headers: {
      Authorization: `Bearer ${token}`
    },
    data: {
      amount: amount
    },
    success: function (response) {
      if (response.checkout_url) {
        sessionStorage.setItem('payment_method', 'stripe');
        sessionStorage.setItem('amount', amount);
        window.location.replace(response.checkout_url);
      } else {
        alert('Hey there! Stripe payment method is not available.');
      }
    }
  });
}

function addRazorPayWallet(amount) {
  var token = localStorage.getItem('token');
  let baseUrlLive = sessionStorage.getItem('baseUrlLive');
  // Send a request to the backend to generate the order ID
  $.ajax({
    url: baseUrlLive + '/api/add-walletrazorpay',
    method: 'POST',
    headers: { Authorization: `Bearer ${token}` },
    data: { amount: amount },
    success: function (response) {
      var orderId = response.order_id;
      // Use the received order ID to initialize Razorpay payment
      var options = {
        key: 'rzp_test_ktbxSvVI7dsfn2',
        amount: amount,
        currency: 'INR',
        name: 'Ecommerce',
        description: '',
        order_id: orderId, // Pass the received order ID here
        prefill: {
          contact: 'ur_mobile',
          email: 'ur_email'
        },
        handler: function (response) {
          // Handle the payment success or failure
          window.location.href = 'paymentsuccess';
        }
      };
      var rzp = new Razorpay(options);
      rzp.open();
    },
    error: function (xhr, status, error) {
      console.error('Error generating order ID:', error);
      window.location.href = 'paymentfailed';
    }
  });
}

function ShowWalletBalance() {
  var token = localStorage.getItem('token');
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');

  // AJAX request to backend to fetch wallet balance
  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/showwalletbalance',
    dataType: 'json',
    headers: {
      Authorization: `Bearer ${token}`
    },
    success: function (response) {
      // Update the total balance displayed on the webpage
      document.getElementById('total_balance').innerText = `$${response.wallet_balance}`;
      document.getElementById('amountinwallet').innerText = `$${response.wallet_balance}`;
    },
    error: function () {
      // Handle error if unable to fetch wallet balance
      alert('Failed to fetch wallet balance. Please try again later.');
    }
  });
}

ShowWalletBalance();

function addFundToWallet() {
  // Get the selected payment option
  var paymentOption = document.querySelector('input[name="payment_options"]:checked');

  // Check if a payment option is selected
  if (!paymentOption) {
    alert('Please select a payment option.');
    return;
  }

  // Get the ID of the selected payment option
  var paymentOptionId = paymentOption.id;

  // Get the amount from the input field
  var amount = document.getElementById('amount_input').value;

  // Call the appropriate function based on the selected payment option
  if (paymentOptionId === 'razorpay') {
    addRazorPayWallet(amount);
  } else if (paymentOptionId === 'stripe') {
    StripeWalletPay(amount);
  } else if (paymentOptionId === 'paypal') {
    generatewalletpaypal(amount);
  }
}
function generatewalletpaypal(amount) {
  var token = localStorage.getItem('token');
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');

  // Make an AJAX request to your server to get the PayPal order details
  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/add-walletpaypal',
    contentType: 'application/json',
    headers: { Authorization: `Bearer ${token}` },
    data: JSON.stringify({ amount: amount }), // Send the amount to the server
    success: function (response) {
      // Check if the response contains the order ID
      if (response.order_id) {
        // Initialize PayPal SDK with client ID and the order ID received from the server
        paypal_sdk
          .Buttons({
            createOrder: function (data, actions) {
              return actions.order.create({
                purchase_units: [
                  {
                    amount: {
                      value: amount, // Use the amount received from the client
                      currency_code: 'USD'
                    },
                    description: 'Your purchase description',
                    custom_id: response.order_id // Use the order_id received from the server
                  }
                ]
              });
            },
            onApprove: function (data, actions) {
              return actions.order.capture().then(function (details) {
                console.log('Transaction completed by ' + details.payer.name.given_name);
                showSuccessToast('Payment successful');
                sessionStorage.setItem('payment_method', 'paypal');
                sessionStorage.setItem('amount', amount);
                window.location.href = 'walletsuccess';
              });
            },
            onError: function (err) {
              console.error('PayPal checkout error:', err);
              showErrorToast('An error occurred during PayPal checkout');
              window.location.href = 'paymentfailed';
            }
          })
          .render('#paypal-button-container');
      } else {
        // If order_id is not received, handle the error
        console.error('Order ID not received from the server');
        showErrorToast('Failed to initiate PayPal checkout');
        window.location.href = 'paymentfailed';
      }
    },
    error: function (xhr, status, error) {
      console.error('Error initiating PayPal checkout:', error);
      showErrorToast('Failed to initiate PayPal checkout');
      window.location.href = 'paymentfailed';
    }
  });
}

function showSuccessToast(message) {
  Toastify({
    text: message,
    backgroundColor: 'linear-gradient(to right, #00b09b, #96c93d)'
  }).showToast();
}

// Function to display error toast
function showErrorToast(message) {
  Toastify({
    text: message,
    backgroundColor: 'linear-gradient(to right, #ff416c, #ff4b2b)'
  }).showToast();
}
