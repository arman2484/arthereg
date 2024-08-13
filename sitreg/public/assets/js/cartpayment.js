// Function to handle placing order
function placeOrder(paymentMethod) {
  const user_id = localStorage.getItem('user_id'); // Retrieve user_id from localStorage
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');

  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/checkout',
    dataType: 'json',
    data: {
      payment_mode: paymentMethod,
      user_id: user_id
    },
    success: function (response) {
      if (response && response.status) {
        console.log('Order Placed Successfully:', response.message);
        Swal.fire('Success!', response.message, 'success').then(() => {
          // Check for 'token' in localStorage and redirect accordingly
          if (localStorage.getItem('token')) {
            window.location.href = '/orders';
          } else {
            window.location.href = '/'; // Redirect to a default page if 'token' is not present
          }
        });
      } else {
        console.error('Failed to create order:', response.message || 'Unknown error');
        Swal.fire('Error', response.message || 'Failed to create order', 'error');
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + error);
      Swal.fire('Error', 'An error occurred while processing your request', 'error');
    }
  });
}

// Function to fetch Stripe payment
function StripePaymentUrl(paymentMethod) {
  const user_id = localStorage.getItem('user_id'); // Retrieve user_id from localStorage
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');
  // Store payment method in session storage
  sessionStorage.setItem('payment_method', paymentMethod);

  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/' + paymentMethod.toLowerCase() + 'checkout',
    dataType: 'json',
    data: { user_id: user_id }, // Pass user_id here
    success: function (response) {
      window.location.replace(response.checkout_url);
    }
  });
}
// Function to generate Razorpay order dynamically
// Function to generate Razorpay order dynamically
// Function to generate Razorpay order dynamically
function generateRazorPayOrder(amount) {
  const user_id = localStorage.getItem('user_id'); // Retrieve user_id from localStorage
  let baseUrlLive = sessionStorage.getItem('baseUrlLive');
  var paymentMethod = 'RazorPay';
  sessionStorage.setItem('payment_method', paymentMethod);

  // Send a request to the backend to generate the order ID
  $.ajax({
    url: baseUrlLive + '/api/' + paymentMethod.toLowerCase() + 'checkout',
    // url: baseUrlLive + '/api/razorpaycheckout',
    method: 'POST',
    data: { amount: amount, user_id: user_id },
    success: function (response) {
      var orderId = response.order_id;
      var options = {
        key: 'rzp_test_ktbxSvVI7dsfn2', // Your Razorpay key ID
        amount: amount, // Amount in paise (e.g., 1000 paise = 10 INR)
        currency: 'INR',
        name: 'Sitreg',
        description: 'Purchase Description',
        order_id: orderId, // Pass the received order ID here
        prefill: {
          contact: 'user_contact_number', // Replace with dynamic user contact number
          email: 'user_email@example.com' // Replace with dynamic user email
        },
        handler: function (response) {
          // Handle the payment success or failure
          // You can verify payment signature here if needed
          window.location.href = 'paymentsuccess'; // Redirect after success
        },
        theme: {
          color: '#3399cc'
        }
      };
      var rzp = new Razorpay(options);
      rzp.open();
    },
    error: function (xhr, status, error) {
      console.error('Error generating order ID:', error);
      Swal.fire('Error', 'Failed to generate Razorpay order', 'error');
    }
  });
}

// Update the function to log the received amount
function generatePaypalOrder(orderId) {
  const user_id = localStorage.getItem('user_id'); // Retrieve user_id from localStorage
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');
  var paymentMethod = 'PayPal';
  sessionStorage.setItem('payment_method', paymentMethod);

  // Make an AJAX request to your server to get the PayPal order details
  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/' + paymentMethod.toLowerCase() + 'checkout',
    // url: baseUrlLive + '/api/paypalcheckout',
    contentType: 'application/json',
    data: JSON.stringify({ order_id: orderId, user_id: user_id }), // Send order_id to the server
    success: function (response) {
      // Initialize PayPal SDK with client ID and the order ID received from the server
      paypal
        .Buttons({
          createOrder: function (data, actions) {
            return actions.order.create({
              purchase_units: [
                {
                  amount: {
                    value: response.amount, // Use the amount received from the server
                    currency_code: 'USD'
                  },
                  description: 'Your purchase description',
                  custom_id: orderId
                }
              ]
            });
          },
          onApprove: function (data, actions) {
            return actions.order.capture().then(function (details) {
              console.log('Transaction completed by ' + details.payer.name.given_name);
              window.location.href = 'paymentsuccess';
            });
          },
          onError: function (err) {
            console.error('PayPal checkout error:', err);
            window.location.href = 'paymentfailed';
          }
        })
        .render('#paypal-button-container');
    },
    error: function (xhr, status, error) {
      console.error('Error initiating PayPal checkout:', error);
      window.location.href = 'paymentfailed';
    }
  });
}

function WalletCheckout(paymentMethod) {
  const user_id = localStorage.getItem('user_id'); // Retrieve user_id from localStorage
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');

  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/walletcheckout',
    dataType: 'json',
    data: {
      payment_mode: paymentMethod,
      user_id: user_id
    },
    success: function (response) {
      if (response && response.status) {
        console.log('Order Placed Successfully:', response.message);
        Swal.fire('Success!', response.message, 'success').then(() => {
          // Check for 'token' in localStorage and redirect accordingly
          if (localStorage.getItem('token')) {
            window.location.href = '/orders';
          } else {
            window.location.href = '/'; // Redirect to a default page if 'token' is not present
          }
        });
      } else {
        console.error('Failed to create order:', response.message || 'Unknown error');
        Swal.fire('Error', response.message || 'Failed to create order', 'error');
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + error);
      Swal.fire('Error', 'Insufficient wallet balance...!', 'error');
    }
  });
}

// Function to handle when "Next" button is clicked
$('#finalpaymentselection').click(function () {
  var selectedPaymentMethod = $('input[name="payment_options"]:checked').attr('id'); // Get the ID of the selected payment method

  // Check if the "I agree" checkbox is checked
  if ($('#place_order_checkbox').prop('checked')) {
    // Check which payment method is selected and call the corresponding function
    if (selectedPaymentMethod === 'cod') {
      placeOrder('COD');
    } else if (selectedPaymentMethod === 'stripe') {
      StripePaymentUrl('Stripe');
    } else if (selectedPaymentMethod === 'wallet') {
      WalletCheckout('Wallet');
    } else if (selectedPaymentMethod === 'paypal') {
      $('#paypalModal').modal('show');
    } else if (selectedPaymentMethod === 'razorpay') {
      generateRazorPayOrder(1000); // Pass the amount in paise (e.g., 1000 paise = 10 INR)
    } else {
      console.log('Selected payment method is not handled');
    }
  } else {
    Swal.fire({
      text: ' Please Select I agree that placing the order places me under Terms of Service & Privacy Policy.',
      icon: 'info',
      confirmButtonText: 'OK'
    });
  }
});

// Show PayPal modal and generate PayPal order
$('#paypalModal').on('show.bs.modal', function (e) {
  var orderId = 'your_order_id'; // Replace with your actual order ID
  generatePaypalOrder(orderId);
});
