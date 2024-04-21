<?php

namespace App\Controller;

use App\Service\WeatherService;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class WeatherController extends AbstractController
{
    private LoggerInterface $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    public function index(string $city, WeatherService $weatherService): Response
    {
        try {
            $weatherData = $weatherService->fetchCityWeatherReport($city);

            return $this->render('weather/index.html.twig', $weatherData);
        } catch (\Exception $e) {
            $this->logger->error("Error retrieving weather information for {city}:" . $e->getMessage());

            $this->addFlash("danger", "Failed to retrieve weather data. {$e->getMessage()}");
            return $this->render('weather/index.html.twig');
        }
    }


    #[OA\Get(
        path: '/api/weather/{city}',
        operationId: 'getCityWeather',
        description: 'This endpoint retrieves current weather data for a given city.',
        summary: 'Gets weather data for a specified city',
        tags: ['Weather'],
        parameters: [
            new OA\Parameter(
                name: 'city',
                description: 'City name to fetch the weather data for',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful response with weather data',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: 'temperature', description: 'Current temperature in Celsius', type: 'number'),
                            new OA\Property(property: 'measurement_date', description: 'Date of the measurement', type: 'string'),
                            new OA\Property(property: 'measurement_hour', description: 'Hour of the measurement', type: 'string'),
                            new OA\Property(property: 'city', description: 'City for which data is reported', type: 'string'),
                        ],
                        type: 'object'
                    )
                )
            ),
            new OA\Response(
                response: 404,
                description: 'City not found',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'City not found.'),
                        ],
                        type: 'object'
                    )
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'An unexpected error occurred.'),
                        ],
                        type: 'object'
                    )
                )
            ),
        ]
    )]
    public function api_city_weather_report(string $city, WeatherService $weatherService): Response
    {
        try {
            $weatherData = $weatherService->fetchCityWeatherReport($city);
            return $this->json($weatherData);
        } catch (HttpException $e) {
            $this->logger->error("HTTP error encountered while retrieving weather data for {$city}: " . $e->getMessage());
            return $this->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            $this->logger->error("Unexpected error occurred while fetching weather data for {$city}: " . $e->getMessage());

            return $this->json(
                ['error' => "An unexpected error occurred. {$e->getMessage()}"],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[OA\Get(
        path: '/api/weather/{city}/full',
        operationId: 'getCityWeatherFullReport',
        description: 'This endpoint retrieves a comprehensive weather report for a given city.',
        summary: 'Gets a detailed weather report for a specified city',
        tags: ['Weather'],
        parameters: [
            new OA\Parameter(
                name: 'city',
                description: 'City name to fetch the comprehensive weather data for',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful response with detailed weather data',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: 'city', type: 'string', description: 'City for which the data is reported'),
                            new OA\Property(property: 'measurement_date', type: 'string', description: 'Date of the measurement'),
                            new OA\Property(property: 'measurement_hour', type: 'string', description: 'Hour of the measurement'),
                            new OA\Property(property: 'temperature', type: 'string', description: 'Current temperature in Celsius'),
                            new OA\Property(property: 'wind_speed', type: 'string', description: 'Wind speed in km/h'),
                            new OA\Property(property: 'wind_direction', type: 'string', description: 'Wind direction'),
                            new OA\Property(property: 'humidity', type: 'string', description: 'Relative humidity in percent'),
                            new OA\Property(property: 'precipitation', type: 'string', description: 'Precipitation amount in mm'),
                            new OA\Property(property: 'pressure', type: 'string', description: 'Atmospheric pressure in hPa'),
                        ],
                        type: 'object'
                    )
                )
            ),
            new OA\Response(
                response: 404,
                description: 'City not found',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'City not found.'),
                        ],
                        type: 'object'
                    )
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'An unexpected error occurred.'),
                        ],
                        type: 'object'
                    )
                )
            ),
        ]
    )]
    public function api_city_weather_full_report(string $city, WeatherService $weatherService): Response
    {
        try {
            $weatherData = $weatherService->fetchCityWeatherFullReport($city);
            return $this->json($weatherData);
        } catch (HttpException $e) {
            $this->logger->error("HTTP error encountered while retrieving weather data for {$city}: " . $e->getMessage());
            return $this->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            $this->logger->error("Unexpected error occurred while fetching weather data for {$city}: " . $e->getMessage());

            return $this->json(
                ['error' => "An unexpected error occurred. {$e->getMessage()}"],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
