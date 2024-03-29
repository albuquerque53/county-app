# County APP

![image](https://github.com/albuquerque53/county-app/assets/57183466/c81152f0-b315-474b-a7d5-23201bdeba3a)

> A simple application that searches county info by code.

<p align="center">
<a href="https://github.com/albuquerque53/county-app/actions/workflows/tests.yml"><img src="https://github.com/albuquerque53/county-app/actions/workflows/tests.yml/badge.svg" alt="Tests Status"></a>
</p>

## :bookmark_tabs: Table of contents

- [County APP](#county-app)
  * [:bookmark_tabs: Table of contents](#bookmark_tabs-table-of-contents)
  * [:desktop_computer: About the Web Application...](#desktop_computer-about-the-web-application)
    + [:brazil: County search](#brazil-county-search)
  * [:mag: About the API...](#mag-about-the-api)
    + [:books: First of all, documentation!](#books-first-of-all-documentation)
    + [:eyes: About the routes and how the API works](#eyes-about-the-routes-and-how-the-api-works)
    + [:globe_with_meridians: External APIs ](#globe_with_meridians-external-apis)
      - [:warning: Why not use IBGE API?](#warning-why-not-use-ibge-api)
  * [:coffee: Technical specification ](#coffee-technical-specification)
    + [:computer: This application is using... ](#computer-this-application-is-using)
  * [:runner: How to run this project locally?](#runner-how-to-run-this-project-locally)
    + [:arrow_down: Setup of containers](#arrow_down-setup-of-containers)
    + [:whale: Using Sail](#whale-using-sail)

## :desktop_computer: About the Web Application...

### :brazil: County search

Just a simple page with a field to input the county code that you wants to look for.

The page is reactive, so, after the search about the county code, the result will appear in the same page without reload or redirect 🔁.

## :mag: About the API...

### :books: First of all, documentation!

I'm providing two types of documentation:

1 - In the Open API 3.x standard, [you can access here](https://github.com/albuquerque53/county-app/blob/main/docs/open_api_specification.yml) <br>
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

The IBGE API is running an old SSL and TLS version that are vulnerable to renegotiation attacks as described in [RFC 5746](rfc5746).

This project is using a recent version of CURL that by default does not request URLs running vulnerable SSL/TLS Versions, you can try in our own terminal running for example: `curl https://servicodados.ibge.gov.br/api/v1/localidades/estados/sp/municipios`, you'll get something like: `OpenSSL/3.0.8: error:0A000152:SSL routines::unsafe legacy renegotiation disabled`.

There is a way to disable this behaviour of CURL, but, disabling security resources is never recommended.

So, this is why the [IbgeCountyService are currently blocked](https://github.com/albuquerque53/county-app/blob/main/app/Services/IbgeCountyService.php#L26).

<hr>

## :coffee: Technical specification 

### :computer: This application is using... 

- Laravel 10.
- Swoole (through Laravel Octane).
- Livewire 3.
- TailwindCss.
- Pest for unit and functional tests.
- Redis for caching.
- Docker & Docker Compose for application containerization.
- Github Actions for CI/CD.

<hr>

## :runner: How to run this project locally?

> Everything is containerized, so it's pretty easy. You do not even need PHP installed on your machine, just docker.

### :arrow_down: Setup of containers

1 - First, build the environment:
```sh
make build 
```
> **:warning: This command you just need to run once, if you alread runned this, you can start from step 2.**

2 - Now, you can just start the back and front-end servers with:
```sh
make up_d 
```

3 - Run the tests to check if everything is ok:
```
make test 
```

4 - To stop the application:
```sh
make stop
```

> From now, everything you want to run in the PHP inside the Laravel container you can do with `vendor/bin/sail <command>`, example: `vendor/bin/sail artisan make:model MyModel`.
