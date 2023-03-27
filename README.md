# County APP

> A simple application that searches county info by code.

## :bookmark_tabs: Table of contents

- [County APP](#county-app)
  * [:bookmark_tabs: Table of contents](#bookmark_tabs-table-of-contents)
  * [:mag: About the API](#mag-about-the-api)
    + [:books: First of all, documentation!](#books-first-of-all-documentation)
    + [:eyes: About the routes and how the API works](#eyes-about-the-routes-and-how-the-api-works)
    + [:globe_with_meridians: External APIs ](#globe_with_meridians-external-apis)
      - [:warning: Why not use IBGE API?](#warning-why-not-use-ibge-api)
  * [:coffee: Technical specification ](#coffee-technical-specification)
    + [:computer: This application is using... ](#computer-this-application-is-using)
  * [:runner: How to run this project locally?](#runner-how-to-run-this-project-locally)
    + [:arrow_down: Instalation of vendors](#arrow_down-instalation-of-vendors)
    + [:whale: Using Sail](#whale-using-sail)

## :mag: About the API

### :books: First of all, documentation!

I'm providing two tipes of documentation:

1 - In the Open API 3.x standart, [you can access here](https://github.com/albuquerque53/county-app/blob/main/docs/open_api_specification.yml) <br>
2 - In a temporary developer portal that I've published with APIMATIC, [you can access here](https://www.apimatic.io/apidocs/county-app)

### :eyes: About the routes and how the API works

1 - There is a simple HTTP route called `SEARCH_COUNTY_CODE`:
```
/search/county/{code}
```

> :warning: The "code" must be one of the allowed values (we validate received codes), check the [CountyCodeEnum.php](https://github.com/albuquerque53/county-app/blob/main/app/Enums/CountyCodeEnum.php) for more details.

2 - This route will search an external API, getting info about the county.

3 - The result is parsed and returned for you.

### :globe_with_meridians: External APIs 

There are two external APIs:

1 - Brasil API <br>
2 - IBGE API (Do not use!)

We can define the API to search by the `COUNTY_SERVICE` environment variable, that can be `brasil_api` or `ibge_api`.

#### :warning: Why not use IBGE API?

The IBGE API is running a old SSL and TLS version that are vulnerable to renegotiation attacks as described in [RFC 5746](rfc5746).

This project is using a recent version of CURL that by default does not request URLs running vulnerable SSL/TLS Versions, you can try in our own terminal running for example: `curl https://servicodados.ibge.gov.br/api/v1/localidades/estados/sp/municipios`, you'll get something like: `OpenSSL/3.0.8: error:0A000152:SSL routines::unsafe legacy renegotiation disabled`.

There is a way to disable this behaviour of CURL, but, disabling security resources is never recommended.

So, this is why the [IbgeCountyService are currently blocked](https://github.com/albuquerque53/county-app/blob/main/app/Services/IbgeCountyService.php#L26).

<hr>

## :coffee: Technical specification 

### :computer: This application is using... 

- Laravel 10.
- Swoole (by Laravel Octane).
- PHPUnit for unit, functional and external tests.
- Redis for caching.
- Docker & Docker Compose for application containerization.
- Github Actions for CI/CD.
- This application is hosted on Heroku (automatic deploy after success on CI/CD success).

<hr>

## :runner: How to run this project locally?

> Everything is containerized, so it's pretty easy. You do not even need PHP installed on your machine, just docker.

### :arrow_down: Instalation of vendors

1 - First, start the containers:
```sh
make start
```

2 - Now, get into the laravel container:
```
make attach
```

3 - Now, install the dependencies:
```sh
make install
```

4 - Well, now you have everything installed, can exit from container:
```sh
exit
```

### :whale: Using Sail

> Laravel provides Laravel Sail that are a helper for docker operations, we gonna use it.

* For start the application, you can run...
```
# will serve the API on localhost:80
vendor/bin/sail up
``` 

* Or if you want to run the tests...
```
vendor/bin/sail test
```

* Or even if you want to install something
```sh
vendor/bin/sail composer require ...
```

Basically, everything you want to do that needs the PHP of the Laravel container you can do with "vendor/bin/sail <command>"
