<?php

namespace App\Service;

use App\Helper\DateHelper;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class WeatherService
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }


    /**
     *  Fetches basic weather report for a city.
     *
     * @param string $city City name.
     * @return array{
     *      city: string,
     *      measurement_date: string,
     *      measurement_hour: string,
     *      temperature: string
     *  }
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws HttpException
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function fetchCityWeatherReport(string $city): array
    {
        $response = $this->requestWeatherDataForCity($city);
        $data     = $response->toArray();

        return [
            'city'             => $data['stacja']          ?? $city,
            'measurement_date' => $data['data_pomiaru']    ?? 'no data',
            'measurement_hour' => $data['godzina_pomiaru'] ?? 'no data',
            'temperature'      => $data['temperatura']     ?? 'no data',
        ];
    }


    /**
     * Fetches detailed weather report for a city.
     *
     * @param string $city City name.
     * @return array{
     *     city: string,
     *     measurement_date: string,
     *     measurement_hour: string,
     *     temperature: string,
     *     wind_speed: string,
     *     wind_direction: string,
     *     humidity: string,
     *     precipitation: string,
     *     pressure: string
     * }
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws HttpException
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function fetchCityWeatherFullReport(string $city): array
    {
        $response = $this->requestWeatherDataForCity($city);
        $data     = $response->toArray();

        return [
            'city'             => $data['stacja']              ?? $city,
            'measurement_date' => $data['data_pomiaru']        ?? 'no data',
            'measurement_hour' => $data['godzina_pomiaru']     ?? 'no data',
            'temperature'      => $data['temperatura']         ?? 'no data',
            'wind_speed'       => $data['predkosc_wiatru']     ?? 'no data',
            'wind_direction'   => $data['kierunek_wiatru']     ?? 'no data',
            'humidity'         => $data['wilgotnosc_wzgledna'] ?? 'no data',
            'precipitation'    => $data['suma_opadu']          ?? 'no data',
            'pressure'         => $data['cisnienie']           ?? 'no data',
        ];
    }


    /**
     * @throws TransportExceptionInterface
     * @throws HttpException
     */
    private function requestWeatherDataForCity(string $city): ResponseInterface
    {
        $city     = DateHelper::normalizeString($city);
        $response = $this->client->request('GET', "https://danepubliczne.imgw.pl/api/data/synop/station/{$city}");

        if ($response->getStatusCode() != 200) {
            throw match ($response->getStatusCode()) {
                404     => new NotFoundHttpException("Data for {$city} not found.", null, 404),
                400     => new BadRequestHttpException("Invalid request."),
                500     => new HttpException(500, "Server error occurred."),
                default => new HttpException(500, "Unexpected response status: {$response->getStatusCode()}"),
            };
        }
        return $response;
    }
}
