<?php

$apiKey = 'CK3AKOEBP0CWZCYD';

function getStockPrices($symbol) {
    global $apiKey;
    
    $apiUrl = "https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=$symbol&apikey=$apiKey";
    
    $response = file_get_contents($apiUrl);
    
    $data = json_decode($response, true);
    
    if(isset($data['Global Quote'])) {
        $stockData = $data['Global Quote'];
        $price = $stockData['05. price'];
        
        return json_encode(['symbol' => $symbol, 'price' => $price]);
    } else {
        return json_encode(['error' => 'Failed to retrieve stock prices']);
    }
}

if(isset($_GET['symbol'])) {
    $symbol = $_GET['symbol'];
    
    $stockPrices = getStockPrices($symbol);
    
    header('Content-Type: application/json');
    echo $stockPrices;
} else {
    echo json_encode(['error' => 'Symbol parameter is missing']);
}

?>
