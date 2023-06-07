# Weather Service

This Weather Service is a PHP application that retrieves weather information from various weather service providers. It allows users to obtain current temperature, min/max temperature, and humidity for a given location.

## Features

- Retrieve weather information using longitude/latitude or zip code
- Supports integration with multiple weather service providers
- Returns weather information as a JSON response
- Sends weather information via email
- Modular and easily swappable provider architecture

## Prerequisites

- PHP 8.0 or higher
- Composer (https://getcomposer.org/) for dependency management

## Installation

1. Clone the repository:

```shell
git clone https://github.com/your-username/weather-service.git
```

2. Install dependencies using Composer:

```shell
cd weather-service
composer install
```

3. Create a `.env` file based on the provided `.env.example` file. Add your API key from the chosen weather service provider.

## Usage

1. Update the `index.php` file in the root directory with your chosen weather service provider class and API key.

2. Start a local development server:

```shell
php -S localhost:8000
```

3. Access the Weather Service API using your web browser or API client:

- Retrieve weather by longitude/latitude:

```
http://localhost:8000/?longitude={longitude}&latitude={latitude}
```

- Retrieve weather by zip code:

```
http://localhost:8000/?zipCode={zipCode}
```

Replace `{longitude}`, `{latitude}`, and `{zipCode}` with the desired values.

## Customization

- To integrate with additional weather service providers, create a new provider class implementing the `WeatherProvider` interface and update the `index.php` file accordingly.

- Modify the `WeatherService` class to customize the response format or add additional functionality.

## Contributing

Contributions are welcome! If you find any issues or have suggestions for improvements, please open an issue or submit a pull request.

## License

This Weather Service is open-source and available under the [MIT License](https://opensource.org/licenses/MIT).
```

## Questions
At a high level, how does your system work?
This system retrieves weather information from various weather service providers. It allows users to obtain current temperature, min/max temperature, and humidity for a given location.

What documentation, websites, papers, etc. did you consult for this assignment?
The OpenWeatherMap current weather data documentation at https://openweathermap.org/current

What third-party libraries or other tools does your application use? How did you choose each library or framework you used?
I used the vlucas/phpdotenv library to manage environmental variables. This helps streamline the management of environment variables, improves code maintainability, and enhances the security of sensitive information used in the application

How long did you spend on this exercise? If you had unlimited time to spend on this, how would you spend it and how would you prioritize each item?
I did have to wait a couple of hours for the OpenWeatherMap API Key to become active, apart from that the entire code took about 45mins. The requirement specified the need to be able to integrate with weather services with ease - hence I gave priority to ensuring that the code is modular with a WeatherProvider interface and that new service classes can implement

What software design and best practices would you implement with more time?
 - Caching the weather data for some time to serve subsequent requests. This will reduce the number of calls to API (In case the free versions have limits on the number of calls) and also to reduce redundancy.
 - Dependency injection to improve flexibility and testing
