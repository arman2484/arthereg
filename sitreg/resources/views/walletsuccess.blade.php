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
            // Function to handle adding amount to wallet after successful Stripe payment
            function WalletSuccess() {
                var token = localStorage.getItem('token');
                var user_id = localStorage.getItem('user_id'); // Assuming user_id is stored in localStorage
                var baseUrlLive = sessionStorage.getItem('baseUrlLive');
                var amount = sessionStorage.getItem('amount'); // Assuming amount is stored in localStorage
                var payment_method = sessionStorage.getItem(
                'payment_method'); // Assuming amount is stored in localStorage

                $.ajax({
                    type: 'POST',
                    url: baseUrlLive + '/api/wallet-success',
                    dataType: 'json',
                    headers: {
                        Authorization: `Bearer ${token}`
                    },
                    data: {
                        user_id: user_id,
                        payment_method: payment_method,
                        amount: amount,
                    },
                    success: function(response) {
                        if (response && response.success == true) {
                            console.log('Amount added to wallet successfully:', response.message);
                            // showSuccessToast('Amount added to wallet successfully');

                            // Redirect to index page after successful wallet update
                            window.location.href = 'wallet';
                        } else {
                            console.error('Failed to add amount to wallet:', response.message ||
                                'Unknown error');
                            // showErrorToast('Failed to add amount to wallet');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error: ' + error);
                        // showErrorToast('An error occurred while processing your request');
                    }
                });
            }

            // Show SweetAlert success message when the page loads
            Swal.fire({
                title: 'Payment Successful',
                text: 'Thank you for your payment. We will be in contact with more details shortly.',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000,
            }).then(() => {
                // Call the function to add amount to wallet after the alert is closed
                WalletSuccess();
            });
        });
    </script>

</body>

</html>
