<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class Localisator
{

    public const BASE_URL = 'https://api-adresse.data.gouv.fr/search/';

    public function __construct(private HttpClientInterface $httpClientInterface)
    {
        $this->httpClientInterface = $httpClientInterface;
    }

    public function getLocation(string $address): array
    {
        $response = $this->httpClientInterface->request('GET', self::BASE_URL, [
            'query' => [
                'q' => $address,
            ]
        ]);

        try {
            $response->toArray()['features'][0]['geometry']['coordinates'];
        } catch (\Exception $e) {
            throw new $e('Adresse introuvable');
        }

        return $response->toArray()['features'][0]['geometry']['coordinates'];
    }
}
