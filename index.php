<?php

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Load the environment variables from the .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

interface WeatherProvider
{
    public function getWeather($longitude, $latitude, $zipCode);
}

class OpenWeatherMapProvider implements WeatherProvider
{
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getWeather($longitude, $latitude, $zipCode)
    {
        $url = 'https://api.openweathermap.org/data/2.5/weather';

    if ($longitude && $latitude) {
        $url .= '?lon=' . urlencode($longitude) . '&lat=' . urlencode($latitude);
    } elseif ($zipCode) {
        $url .= '?zip=' . urlencode($zipCode);
    }

    $url .= '&appid=' . urlencode($this->apiKey);
    $url .= '&units=imperial';

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Process the response and extract the required weather information
    $data = json_decode($response, true);

    if (!$data) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Failed to retrieve weather data']);
        return;
    }

    $currentTemperature = $data['main']['temp'];
    $minTemperature = $data['main']['temp_min'];
    $maxTemperature = $data['main']['temp_max'];
    $humidity = $data['main']['humidity'];

    // Prepare the weather response
    $weatherResponse = [
        'currentTemperature' => $currentTemperature,
        'minTemperature' => $minTemperature,
        'maxTemperature' => $maxTemperature,
        'humidity' => $humidity
    ];
        return $weatherResponse;
    }
}

class OtherWeatherProvider implements WeatherProvider
{
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getWeather($longitude, $latitude, $zipCode)
    {
        // Implement the integration with the OtherWeatherProvider API here
        // Make the HTTP request, process the response, and return the weather information
        // Sample implementation:
        // $weatherData = [
        //     'currentTemperature' => 23.4,
        //     'minTemperature' => 18.9,
        //     'maxTemperature' => 25.8,
        //     'humidity' => 75,
        // ];
        // return $weatherData;
    }
}

class WeatherService
{
    private $weatherProvider;

    public function __construct(WeatherProvider $weatherProvider)
    {
        $this->weatherProvider = $weatherProvider;
    }

    public function getWeather($longitude, $latitude, $zipCode)
    {
        // Validate the parameters and ensure at least one of them is provided
        if (!$longitude && !$latitude && !$zipCode) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'At least one of longitude, latitude, or zipCode must be provided']);
            return;
        }

        // Call the getWeather method of the weather provider
        $weather = $this->weatherProvider->getWeather($longitude, $latitude, $zipCode);

        // Prepare the weather response
        $weatherResponse = [
            'currentTemperature' => $weather['currentTemperature'] . ' F',
            'minTemperature' => $weather['minTemperature'] . ' F',
            'maxTemperature' => $weather['maxTemperature'] . ' F',
            'humidity' => $weather['humidity'] . '%',
        ];

        // Send the weather response back to the gnome
        header('Content-Type: application/json');
        echo json_encode($weatherResponse);

    }
}

// Retrieve the API key from the environment
$apiKey = $_ENV['OWM_API_KEY'] ?? null;

// Validate the API key
if (!$apiKey) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'API key not found']);
    return;
}

// Create an instance of the WeatherProvider using the desired provider class
$weatherProvider = new OpenWeatherMapProvider($apiKey);

// Create an instance of the WeatherService with the WeatherProvider
$weatherService = new WeatherService($weatherProvider);

// Handle the weather request
$weatherService->getWeather($_GET['longitude'] ?? null, $_GET['latitude'] ?? null, $_GET['zipCode'] ?? null);
