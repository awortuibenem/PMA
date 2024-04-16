<?php

function calculateMovingAverage($data, $period) {
    $movingAverages = [];
    $total = 0;
    $count = 0;
    foreach ($data as $price) {
        $total += $price;
        $count++;
        if ($count == $period) {
            $movingAverage = $total / $period;
            $movingAverages[] = $movingAverage;
            $total -= array_shift($data);
            $count--;
        }
    }
    return $movingAverages;
}

function calculateRSI($data, $period) {
    $gains = [];
    $losses = [];
    $rsiValues = [];
    foreach ($data as $key => $price) {
        if ($key > 0) {
            $change = $price - $data[$key - 1];
            if ($change >= 0) {
                $gains[] = $change;
                $losses[] = 0;
            } else {
                $gains[] = 0;
                $losses[] = abs($change);
            }
        }
        if (count($gains) == $period + 1) {
            $avgGain = array_sum(array_slice($gains, -$period)) / $period;
            $avgLoss = array_sum(array_slice($losses, -$period)) / $period;
            $rs = ($avgLoss == 0) ? 100 : (100 - (100 / (1 + ($avgGain / $avgLoss))));
            $rsiValues[] = round($rs, 2);
            array_shift($gains);
            array_shift($losses);
        }
    }
    return $rsiValues;
}

function calculateMACD($data, $shortPeriod, $longPeriod, $signalPeriod) {
    $shortEMA = [];
    $longEMA = [];
    $macdLine = [];
    $signalLine = [];
    $histogram = [];
    $shortMultiplier = 2 / ($shortPeriod + 1);
    $longMultiplier = 2 / ($longPeriod + 1);
    foreach ($data as $key => $price) {
        if ($key == 0) {
            $shortEMA[] = $price;
            $longEMA[] = $price;
        } else {
            $shortEMA[] = ($price - end($shortEMA)) * $shortMultiplier + end($shortEMA);
            $longEMA[] = ($price - end($longEMA)) * $longMultiplier + end($longEMA);
        }
        if (count($shortEMA) >= $longPeriod) {
            $macd = end($shortEMA) - end($longEMA);
            $macdLine[] = $macd;
            if (count($macdLine) >= $signalPeriod) {
                $signal = array_sum(array_slice($macdLine, -$signalPeriod)) / $signalPeriod;
                $signalLine[] = $signal;
                $histogram[] = $macd - $signal;
            }
        }
    }
    return ['macd_line' => $macdLine, 'signal_line' => $signalLine, 'histogram' => $histogram];
}

function calculateBollingerBands($data, $period, $deviation) {
    $bollingerBands = [];
    $sma = calculateMovingAverage($data, $period);
    $stdDev = [];
    foreach ($data as $key => $price) {
        $start = max(0, $key - $period);
        $slice = array_slice($data, $start, $period);
        $stdDev[] = stats_standard_deviation($slice);
    }
    foreach ($sma as $key => $s) {
        $upperBand = $s + ($deviation * $stdDev[$key]);
        $lowerBand = $s - ($deviation * $stdDev[$key]);
        $bollingerBands[] = ['upper_band' => $upperBand, 'lower_band' => $lowerBand];
    }
    return $bollingerBands;
}

function getHistoricalPrices($symbol, $period) {
    $apiKey = 'CK3AKOEBP0CWZCYD'; 
    $url = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=$symbol&outputsize=compact&apikey=$apiKey";

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

    if (isset($data['Time Series (Daily)'])) {
        $historicalPrices = [];
        $count = 0;
        foreach ($data['Time Series (Daily)'] as $date => $dailyData) {
            $historicalPrices[] = floatval($dailyData['4. close']); 
            $count++;
            if ($count >= $period) {
                break;
            }
        }
        return $historicalPrices;
    } else {
        echo 'Error: Unable to fetch historical prices from Alpha Vantage API';
        return null;
    }
}


function calculateTechnicalIndicators($symbol, $period) {
    $historicalPrices = getHistoricalPrices($symbol, $period);
    $movingAverages = [
        'sma_50' => calculateMovingAverage($historicalPrices, 50),
        'sma_200' => calculateMovingAverage($historicalPrices, 200),
    ];
    $rsi = calculateRSI($historicalPrices, 14);
    $macd = calculateMACD($historicalPrices, 12, 26, 9);
    $bollingerBands = calculateBollingerBands($historicalPrices, 20, 2);
    return [
        'symbol' => $symbol,
        'moving_averages' => $movingAverages,
        'rsi' => $rsi,
        'macd' => $macd,
        'bollinger_bands' => $bollingerBands
    ];
}

$symbol = isset($_GET['symbol']) ? $_GET['symbol'] : 'AAPL'; 
$period = 365; 
$technicalIndicators = calculateTechnicalIndicators($symbol, $period);
echo json_encode($technicalIndicators);

?>
