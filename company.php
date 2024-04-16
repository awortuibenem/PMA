<?php
$apiKey = 'CK3AKOEBP0CWZCYD';

function getCompanyInfo($symbol) {
    global $apiKey;
    $apiUrl = "https://www.alphavantage.co/query?function=OVERVIEW&symbol=$symbol&apikey=$apiKey";
    $response = file_get_contents($apiUrl);
    $data = json_decode($response, true);
    if(!empty($data)) {
        return json_encode(['symbol' => $symbol, 'company_info' => $data]);
    } else {
        return json_encode(['error' => 'Failed to retrieve company information']);
    }
}

if(isset($_GET['symbol'])) {
    $symbol = $_GET['symbol'];
    $companyInfo = getCompanyInfo($symbol);
    header('Content-Type: application/json');
    echo $companyInfo;
} else {
    echo json_encode(['error' => 'Symbol parameter is missing']);
}
?>
