<?php
session_start();

include_once('config/fonctions.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Index Page</title>
    <!-- Add your CSS styling here -->
    <style>
        /* Styling for the modal overlay */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* background-color: rgba(0, 0, 0, 0.2); */
            display: none;
            z-index: 9999;
        }

        /* Styling for the modal container */
        .modal-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #FDF7DF;
            border: 1px solid #FEEC6F;
            color: #C9971C;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        /* Styling for the modal message */
        .modal-message {
            color: #333;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- Add your page content here -->

    <?php
    // Check if the redirect_message is set in the session
    if (isset($_SESSION['redirect_message'])) {
        // Display the modal overlay and modal container
        echo '<div class="modal-overlay" id="modal-overlay"></div>';
        echo '<div class="modal-container" id="modal-container">';
        // Display the message and remove it from the session
        echo '<div class="modal-message">' . $_SESSION['redirect_message'] . '</div>';
        unset($_SESSION['redirect_message']);
        echo '</div>';
    }
    ?>

    <!-- Your page content goes here -->

    <!-- Add your JavaScript to show the modal -->
    <script>
        // Function to show the modal
        function showModal() {
            const modalOverlay = document.getElementById('modal-overlay');
            const modalContainer = document.getElementById('modal-container');

            modalOverlay.style.display = 'block';
            modalContainer.style.display = 'block';

            // Hide the modal after 10 seconds
            setTimeout(function () {
                modalOverlay.style.display = 'none';
                modalContainer.style.display = 'none';
            }, 5000); // 10000 milliseconds = 10 seconds
        }

        // Call the function when the page loads
        window.onload = showModal;
    </script>
</body>

</html>