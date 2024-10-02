<?php

namespace App\Controller;

use App\Entity\CryptoCurrency;
use App\Repository\CryptoCurrencyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CryptocurrencyController extends AbstractController
{
    private $cryptoCurrencyRepository;

    public function __construct(CryptoCurrencyRepository $cryptoCurrencyRepository)
    {
        $this->cryptoCurrencyRepository = $cryptoCurrencyRepository;
    }

    #[Route('/api/crypto-currency/{symbol}', name: 'get_crypto_currency_by_symbol', methods: ['GET'])]
    public function getBySymbol(string $symbol): JsonResponse
    {
        $cryptoCurrency = $this->cryptoCurrencyRepository->findOneBy(['symbol' => strtoupper($symbol)]);

        if (!$cryptoCurrency) {
            return new JsonResponse(['message' => 'Cryptocurrency not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse($this->serializeCryptoCurrency($cryptoCurrency));
    }

    #[Route('/api/crypto-currency/min', name: 'get_crypto_currencies_by_min', methods: ['GET'])]
    public function getByMin(Request $request): JsonResponse
    {
        $min = $request->query->get('min');

        if (!is_numeric($min)) {
            return new JsonResponse(['message' => 'Invalid min value'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $cryptoCurrencies = $this->cryptoCurrencyRepository->findBy(['currentPrice' => ['>', $min]]);

        return new JsonResponse(array_map([$this, 'serializeCryptoCurrency'], $cryptoCurrencies));
    }

    #[Route('/api/crypto-currency/max', name: 'get_crypto_currencies_by_max', methods: ['GET'])]
    public function getByMax(Request $request): JsonResponse
    {
        $max = $request->query->get('max');

        if (!is_numeric($max)) {
            return new JsonResponse(['message' => 'Invalid max value'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $cryptoCurrencies = $this->cryptoCurrencyRepository->findBy(['currentPrice' => ['<', $max]]);

        return new JsonResponse(array_map([$this, 'serializeCryptoCurrency'], $cryptoCurrencies));
    }

    private function serializeCryptoCurrency(CryptoCurrency $cryptoCurrency): array
    {
        return [
            'id' => $cryptoCurrency->getId(),
            'name' => $cryptoCurrency->getName(),
            'symbol' => $cryptoCurrency->getSymbol(),
            'currentPrice' => $cryptoCurrency->getCurrentPrice(),
            'totalVolume' => $cryptoCurrency->getTotalVolume(),
            'ath' => $cryptoCurrency->getAth(),
            'athDate' => $cryptoCurrency->getAthDate()->format('Y-m-d H:i:s'),
            'atl' => $cryptoCurrency->getAtl(),
            'atlDate' => $cryptoCurrency->getAtlDate()->format('Y-m-d H:i:s'),
            'updatedAt' => $cryptoCurrency->getUpdatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
