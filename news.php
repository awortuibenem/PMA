<?php

function getNewsHeadlines($query) {
    $apiKey = '8f77f07e2f164704bfd0beff22db2ee8'; 
    $baseUrl = 'https://newsapi.org/v2/';
    $endpoint = 'everything';
    $params = [
        'q' => $query,
        'apiKey' => $apiKey,
        'pageSize' => 10, 
    ];

    $url = $baseUrl . $endpoint . '?' . http_build_query($params);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return ['error' => 'Failed to fetch news data: ' . curl_error($ch)];
    }

    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data['articles'])) {
        return $data['articles'];
    } else {
        return ['error' => 'No news articles found.'];
    }
}

$query = isset($_GET['query']) ? $_GET['query'] : 'stocks'; 
$newsHeadlines = getNewsHeadlines($query);
echo json_encode($newsHeadlines);

?>
