# Crypto project

## Overview

Crypto project is a cryptocurrency data API built with Symfony. This application allows users to fetch cryptocurrency information based on various criteria, such as symbol, minimum price, and maximum price. 

## Features

- Retrieve cryptocurrency data by symbol
- Fetch cryptocurrencies with prices above a specified minimum
- Fetch cryptocurrencies with prices below a specified maximum
- JSON response format for easy integration

## Requirements

- PHP 8.2 or higher
- Composer
- MySQL database
- WAMP server (or any compatible server environment)

## Installation

1. **Clone the repository:**

   git clone <repository-url>


Install dependencies:

composer install

Set up your environment:

Create a .env file based on .env.example and configure your database connection settings.
Set up the database:

Import the database structure and sample data from database_dump.sql (if included) into your MySQL database.

-- Run the following SQL command in your MySQL client
SOURCE path/to/database_dump.sql;

Run migrations (if applicable):

php bin/console doctrine:migrations:migrate
Usage
Start the Server

Use the following command to start your local server:

symfony serve
or, if using WAMP, ensure Apache and MySQL services are running.

API Endpoints

Get Cryptocurrency by Symbol:

GET /api/crypto-currency/{symbol}

Example:

GET /api/crypto-currency/BTC

Get Cryptocurrencies Above Minimum Price:

GET /api/crypto-currency/min?min={value}

Example:

GET /api/crypto-currency/min?min=1000
Get Cryptocurrencies Below Maximum Price:

GET /api/crypto-currency/max?max={value}

Example:

GET /api/crypto-currency/max?max=50000
Response Format
All responses are returned in JSON format. For example:

json

{
  "id": 1,
  "name": "Bitcoin",
  "symbol": "BTC",
  "currentPrice": 45000,
  "totalVolume": 1000000,
  "ath": 65000,
  "athDate": "2021-11-10 00:00:00",
  "atl": 3000,
  "atlDate": "2013-07-05 00:00:00",
  "updatedAt": "2024-01-01 00:00:00"
}
