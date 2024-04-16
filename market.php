<?php
$apiKey = 'CK3AKOEBP0CWZCYD';

function getMarketData($symbol, $market) {
    global $apiKey;
    
    $apiFunction = ($market === 'stock') ? 'GLOBAL_QUOTE' : 'DIGITAL_CURRENCY_INTRADAY';
    $apiUrl = "https://www.alphavantage.co/query?function=$apiFunction&symbol=$symbol&apikey=$apiKey";
    
    $response = file_get_contents($apiUrl);
    $data = json_decode($response, true);
    
    if(isset($data['Global Quote']) || isset($data['Time Series (Digital Currency Intraday)'])) {
        $marketData = ($market === 'stock') ? $data['Global Quote'] : $data['Time Series (Digital Currency Intraday)'];
        return json_encode(['symbol' => $symbol, 'market' => $market, 'data' => $marketData]);
    } else {
        return json_encode(['error' => 'Failed to retrieve market data']);
    }
}

if(isset($_GET['symbol']) && isset($_GET['market'])) {
    $symbol = $_GET['symbol'];
    $market = $_GET['market'];
    $marketData = getMarketData($symbol, $market);
    header('Content-Type: application/json');
    echo $marketData;
} else {
    echo json_encode(['error' => 'Symbol and market parameters are required']);
}
?>
