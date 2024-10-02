<?php

namespace App\Command;

use App\Entity\CryptoCurrency;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand; // Ensure this import is present
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:fetch-crypto-data',
    description: 'Fetches crypto data and saves it to the database',
)]
final class FetchCryptoDataCommand extends Command
{
    private HttpClientInterface $client;
    private EntityManagerInterface $entityManager;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $entityManager)
    {
        // Assigning the client and entity manager to class properties
        $this->client = $client;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output); // Create an instance of SymfonyStyle

        // Sending GET request to fetch crypto data
        $response = $this->client->request('GET', 'https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=50');

        // Converting the response to an array
        $data = $response->toArray();

        // Iterating over the data to persist each cryptocurrency
        foreach ($data as $cryptoData) {
            $cryptoCurrency = new CryptoCurrency();
            $cryptoCurrency->setName($cryptoData['name']);
            $cryptoCurrency->setSymbol($cryptoData['symbol']);
            $cryptoCurrency->setCurrentPrice($cryptoData['current_price']);
            $cryptoCurrency->setTotalVolume($cryptoData['total_volume']);
            $cryptoCurrency->setAth($cryptoData['ath']);
            $cryptoCurrency->setAthDate(new \DateTime($cryptoData['ath_date']));
            $cryptoCurrency->setAtl($cryptoData['atl']);
            $cryptoCurrency->setAtlDate(new \DateTime($cryptoData['atl_date']));
            $cryptoCurrency->setUpdatedAt(new \DateTime()); // Set updated timestamp

            $this->entityManager->persist($cryptoCurrency); // Prepare the entity for insertion
        }

        $this->entityManager->flush(); // Save all persisted entities to the database

        $io->success('Crypto data has been successfully fetched and saved to the database.');

        return Command::SUCCESS; // Return success status
    }
}
