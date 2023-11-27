<?php
session_start();

include('config.php');

// Check if the user is authenticated (adjust this based on your authentication logic)
$is_authenticated = isset($_SESSION['user_id']);

require_once 'DBController.php';
require_once 'ShoppingCart.php';

$shoppingCart = new ShoppingCart();

// Procesarea plății
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['procesare_plata'])) {
    // Check if the stripeToken is set
    if (isset($_POST['stripeToken'])) {
        $token = $_POST['stripeToken'];

        try {
            // Include the Stripe library
            require_once 'C:\xampp\htdocs\Evenimente\stripe-php-master';  // Adjust the path accordingly
            \Stripe\Stripe::setApiKey('sk_test_51OH7g3J8NGqPZGbSdEPUGDntQIYn8nF5K9vWObA086NvdHKg7JCjOCZSqKaLN8zGSVSZVLdccc86BtWse4yLgyjK00lK99IQ5K');  // Replace with your actual Stripe secret key

            // Charge creation
            $charge = \Stripe\Charge::create([
                'amount' => 1, // or any other amount in cents (e.g., $20.00)
                'currency' => 'ron',
                'description' => 'Plata pentru bilete eveniment',
                'source' => $token,
            ]);

            // Payment processed successfully
            // Here you can update the database with payment details, send confirmations, etc.

            echo 'Plata a fost procesată cu succes!';
            exit;
        } catch (\Stripe\Exception\CardException $e) {
            echo 'Eroare la procesarea plății: ' . $e->getError()->message;
        }
    } else {
        echo 'Token de plată lipsă.';
    }
}

// The rest of your code...

// Afișare buton de plată
if ($is_authenticated && isset($_SESSION['bilete_in_cos']) && !empty($_SESSION['bilete_in_cos'])) {
    echo '<form action="" method="post">';
    echo '<script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
           data-key="your_stripe_publishable_key"  // Replace with your actual Stripe publishable key
           data-amount="1"
           data-name="Bilete Eveniment"
           data-description="Plata pentru bilete"
           data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
           data-locale="auto"
           data-currency="ron"></script>';
    echo '<input type="hidden" name="procesare_plata" value="1">';  // Add a hidden field to indicate form submission
    echo '</form>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        form {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        #card-element {
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 4px;
        }

        #card-errors {
            color: #e74c3c;
            margin-top: 10px;
        }

        button {
            background-color: #3498db;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>
<body>

<form action="" method="post">
    <label for="card-holder-name">Card Holder's Name:</label>
    <input type="text" id="card-holder-name" name="card-holder-name" required>

    <label for="card-element">
        Credit or debit card:
    </label>
    <div id="card-element">
        <!-- A Stripe Element will be inserted here. -->
    </div>

    <!-- Used to display form errors. -->
    <div id="card-errors" role="alert"></div>

    <button type="submit" name="procesare_plata">Submit Payment</button>
</form>

<!-- Include the Stripe.js library -->
<script src="https://js.stripe.com/v3/"></script>

<!-- Your client-side code for handling Stripe Elements -->
<script>
    // Create a Stripe client.
    var stripe = Stripe('your_stripe_publishable_key'); // Replace with your actual Stripe publishable key

    // Create an instance of Elements.
    var elements = stripe.elements();

    // Create an instance of the card Element.
    var card = elements.create('card');

    // Add an instance of the card Element into the `card-element` div.
    card.mount('#card-element');

    // Handle real-time validation errors from the card Element.
    card.addEventListener('change', function (event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Handle form submission.
    var form = document.querySelector('form');
    form.addEventListener('submit', function (event) {
        event.preventDefault();

        stripe.createToken(card).then(function (result) {
            if (result.error) {
                // Inform the user if there was an error.
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                // Send the token to your server.
                var tokenInput = document.createElement('input');
                tokenInput.setAttribute('type', 'hidden');
                tokenInput.setAttribute('name', 'stripeToken');
                tokenInput.setAttribute('value', result.token.id);
                form.appendChild(tokenInput);

                // Submit the form.
                form.submit();
            }
        });
    });
</script>

</body>
</html>
