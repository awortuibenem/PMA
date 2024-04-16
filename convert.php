<?php

function fetchExchangeRates($apiKey) {
    $url = "https://www.alphavantage.co/query?function=CURRENCY_EXCHANGE_RATE&from_currency=USD&to_currency=NGN&apikey=$apiKey";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
        curl_close($ch);
        return null;
    }

    curl_close($ch);

    $data = json_decode($response, true);

    // Check if data is valid
    if (isset($data['Realtime Currency Exchange Rate'])) {
        return $data['Realtime Currency Exchange Rate'];
    } else {
        echo 'Error: Unable to fetch exchange rates';
        return null;
    }
}

$apiKey = 'CK3AKOEBP0CWZCYD';
$exchangeRate = fetchExchangeRates($apiKey);

if ($exchangeRate) {
    print_r($exchangeRate);
} else {
    echo 'Failed to fetch exchange rate.';
}

?>
