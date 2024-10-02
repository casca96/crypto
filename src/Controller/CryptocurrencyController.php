<?php
// src/Controller/CryptocurrencyController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CryptocurrencyController extends AbstractController
{
    /**
     * @Route("/cryptocurrencies", name="cryptocurrencies_index")
     */
    public function index(): JsonResponse
    {

        $cryptocurrencies = [

            ['name' => 'Bitcoin', 'symbol' => 'BTC'],
            ['name' => 'Ethereum', 'symbol' => 'ETH'],
        ];


        return new JsonResponse($cryptocurrencies);
    }
}
