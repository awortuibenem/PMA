<?php
$apiKey = 'CK3AKOEBP0CWZCYD';

function getHistoricalData($symbol, $startDate, $endDate) {
    global $apiKey;
    $apiUrl = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=$symbol&outputsize=compact&apikey=$apiKey";
    $response = file_get_contents($apiUrl);
    $data = json_decode($response, true);
    if(isset($data['Time Series (Daily)'])) {
        $historicalData = [];
        foreach($data['Time Series (Daily)'] as $date => $stockData) {
            if($date >= $startDate && $date <= $endDate) {
                $historicalData[$date] = [
                    'open' => $stockData['1. open'],
                    'high' => $stockData['2. high'],
                    'low' => $stockData['3. low'],
                    'close' => $stockData['4. close'],
                    'volume' => $stockData['5. volume']
                ];
            }
        }
        return json_encode(['symbol' => $symbol, 'historical_data' => $historicalData]);
    } else {
        return json_encode(['error' => 'Failed to retrieve historical data']);
    }
}

if(isset($_GET['symbol']) && isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $symbol = $_GET['symbol'];
    $startDate = $_GET['start_date'];
    $endDate = $_GET['end_date'];
    $historicalData = getHistoricalData($symbol, $startDate, $endDate);
    header('Content-Type: application/json');
    echo $historicalData;
} else {
    echo json_encode(['error' => 'Symbol, start_date, and end_date parameters are required']);
}
?>
