<?php

// Set your Safaricom Daraja API credentials
$consumerKey = 'X7ivlccQ99SEBIilnGc0QVZA7ux2jW3H';
$consumerSecret = 'T7oi8yFn0YNCMqFM';
$lipaNaMpesaOnlinePasskey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
$lipaNaMpesaOnlineShortcode = '174379';

// Set the Lipa na M-Pesa online endpoint
$lipaNaMpesaOnlineEndpoint = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

// Generate a random transaction reference
$transactionReference = 'LNM_' . time();

// Set the callback URL where Safaricom will send the payment notification
$callbackUrl = 'https://your-callback-url.com';

// Set the details of the payment
$amount = 1; // Replace with the actual amount
$phoneNumber = '254705875621'; // Replace with the customer's phone number

// Create the access token
$accessToken = generateAccessToken($consumerKey, $consumerSecret);

// Initiate Lipa na M-Pesa online payment
$response = lipaNaMpesaOnline($accessToken, $lipaNaMpesaOnlineShortcode, $lipaNaMpesaOnlinePasskey, $transactionReference, $amount, $phoneNumber, $callbackUrl);

// Print the response
print_r($response);

// Function to generate the access token
function generateAccessToken($consumerKey, $consumerSecret) {
    $credentials = base64_encode($consumerKey . ':' . $consumerSecret);
    $ch = curl_init('https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $credentials));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);
    return $response['access_token'];
}

// Function to initiate Lipa na M-Pesa online payment
function lipaNaMpesaOnline($accessToken, $shortcode, $passkey, $reference, $amount, $phoneNumber, $callbackUrl) {
    $data = array(
        'BusinessShortCode' => $shortcode,
        'Password' => base64_encode($shortcode . $passkey . date('YmdHis')),
        'Timestamp' => date('YmdHis'),
        'TransactionType' => 'CustomerPayBillOnline',
        'Amount' => $amount,
        'PartyA' => $phoneNumber,
        'PartyB' => $shortcode,
        'PhoneNumber' => $phoneNumber,
        'CallBackURL' => $callbackUrl,
        'AccountReference' => $reference,
        'TransactionDesc' => 'Payment for goods/services',
    );

    $ch = curl_init($lipaNaMpesaOnlineEndpoint);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $accessToken, 'Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);
    return $response;
}
?>