PocoMarketAPI
PocoMarketAPI is a an API for fetching stock market data, technical indicators, news headlines, and performing currency conversions only USD TO NGN For NOW. It provides developers with easy access to essential financial data.

Features
Stock Market Data: Retrieve real-time and historical stock market data.
Technical Indicators: Calculate moving averages, relative strength index (RSI), MACD, Bollinger Bands, and more.
News Headlines: Fetch recent headlines and articles related to specific stocks or cryptocurrencies.
Currency Conversion: Convert between USD and NGN.


Endpoints

/convert
This endpoint allows users to perform currency conversion. It takes three query parameters , Amount, From And To
http://localhost/PMA/convert.php?amount=100&from=USD&to=NGN, this Converts 100USD TO NGN and returns The info in A JSON FORMAT

/news
This endpoint returns Recent News Articles About The Cryptocurrency / Stock Passed In The URl
http://localhost/PMA/news?symbol=TSLA retuns the latest news On The Tesla Stock

/data
This endpoint is used to retrieve various data related to a specific symbol, such as historical prices, technical indicators, news headlines.
http://localhost/PMA//data?symbol=TSLA&type=historical_prices&period=365

/prices Endpoint:
This endpoint is specifically for retrieving current price information for The Symbol(s) Passed In The Url
http://localhost/PMA/prices?symbols=TSLA,AAPL,GOOGL

/info Endpoint:
This endpoint is used to retrieve information about a specific company
http://localhost/PMA//info?symbol=META will return information about the company META






Getting Started
To get started using the PocoMarketAPI, follow these steps:

Clone the repository to your local machine.
Install the required dependencies.
Run the API server locally using https://localhost/PMA
