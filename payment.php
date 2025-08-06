<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'buyer') {
    echo "Access denied.";
    exit();
}

$buyer_id = $_SESSION['user']['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment - Mzansi Marketplace</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f1f1;
            padding: 30px;
        }
        h2 {
            text-align: center;
        }
        form {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="number"], input[type="month"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .payment-methods label {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 12px 0;
            cursor: pointer;
        }
        .payment-methods img {
            height: 24px;
        }
        .card-details {
            margin-top: 20px;
            display: none;
        }
        button {
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h2>Choose Payment Method</h2>

<form action="process_order.php" method="POST" id="paymentForm">
    <div class="payment-methods">
        <label>
            <input type="radio" name="payment_method" value="eft" required>
            <img src="https://img.icons8.com/ios-filled/24/000000/bank.png" alt="EFT">
            Pay via EFT (manual bank transfer)
        </label>

        <label>
            <input type="radio" name="payment_method" value="card">
            <img src="https://img.icons8.com/color/24/000000/visa.png" alt="Visa">
            <img src="https://img.icons8.com/color/24/000000/mastercard-logo.png" alt="Mastercard">
            Credit / Debit Card
        </label>

        <label>
            <input type="radio" name="payment_method" value="paypal">
            <img src="https://img.icons8.com/color/24/000000/paypal.png" alt="PayPal">
            PayPal
        </label>

        <label>
            <input type="radio" name="payment_method" value="trade">
            🤝 Propose a Trade
        </label>
    </div>

    <div class="card-details" id="cardFields">
        <label for="card_name">Cardholder Name</label>
        <input type="text" name="card_name" id="card_name">

        <label for="card_number">Card Number</label>
        <input type="text" name="card_number" id="card_number" maxlength="16">

        <label for="expiry">Expiry Date</label>
        <input type="month" name="expiry" id="expiry">

        <label for="cvv">CVV</label>
        <input type="text" name="cvv" id="cvv" maxlength="4">
    </div>

    <button type="submit">Place Order</button>
</form>

<script>
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const cardFields = document.getElementById('cardFields');

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            cardFields.style.display = radio.value === 'card' ? 'block' : 'none';
        });
    });
</script>

</body>
</html>
