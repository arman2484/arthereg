<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
</head>

<body>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        // Show SweetAlert error message when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Payment Failed',
                text: 'Sorry, something went wrong. You need to make a repayment.',
                icon: 'error',
                showConfirmButton: false,
                timer: 2000
            });
            // Redirect to index page after a delay
            // setTimeout(() => {
            //     window.location.href = "index"; // Assuming index.html is your homepage
            // }, 2200);
        });
    </script>
</body>

</html>
