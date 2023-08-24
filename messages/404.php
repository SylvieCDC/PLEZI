<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Index Page</title>
    <!-- Icon sur onglet = favicon -->
    <link rel="icon" href="/assets/logo/LOGO_PLEZI_jaune.png" type="image/x-icon" />
    <link rel="apple_icon" href="/assets/logo/LOGO_PLEZI_jaune.png" />
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



    <!-- Display the modal overlay and modal container -->
    <div class="modal-overlay" id="modal-overlay"></div>
    <div class="modal-container" id="modal-container">
        <!-- Display the message and remove it from the session -->
        <div class="modal-message">
            <h1>Page non trouvée</h1>
        </div>
    </div>


    <!-- Add your JavaScript to show the modal -->
    <script>
        // Function to show the modal and redirect after 5 seconds
        function showModal() {
            const modalOverlay = document.getElementById('modal-overlay');
            const modalContainer = document.getElementById('modal-container');

            modalOverlay.style.display = 'block';
            modalContainer.style.display = 'block';

            // Hide the modal and redirect after 5 seconds
            setTimeout(function () {
                modalOverlay.style.display = 'none';
                modalContainer.style.display = 'none';
                window.location.href = "../Accueil";
            }, 3000); // 3000 milliseconds = 3 seconds
        }

        // Call the function when the page loads
        window.onload = showModal;
    </script>
</body>

</html>