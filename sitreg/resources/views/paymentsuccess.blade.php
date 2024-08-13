<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function PaymentSuccess(paymentMethod) {
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
                    success: function(response) {
                        if (response && response.status) {
                            console.log('Order Placed Successfully:', response.message);
                            Swal.fire('Success!', response.message, 'success').then(() => {
                                // Check for 'token' in localStorage and redirect accordingly
                                if (localStorage.getItem('token')) {
                                    window.location.href = '/orders';
                                } else {
                                    window.location.href =
                                    '/'; // Redirect to a default page if 'token' is not present
                                }
                            });
                        } else {
                            console.error('Failed to create order:', response.message ||
                                'Unknown error');
                            Swal.fire('Error', response.message || 'Failed to create order', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error: ' + error);
                        Swal.fire('Error', 'An error occurred while processing your request', 'error');
                    }
                });
            }


            // Retrieve payment method from session storage
            var paymentMethod = sessionStorage.getItem('payment_method');

            // Ensure paymentMethod is defined before calling PaymentSuccess
            if (paymentMethod) {
                PaymentSuccess(paymentMethod);
            } else {
                console.error('Payment method not defined');
            }
        });
    </script>
</body>

</html>
